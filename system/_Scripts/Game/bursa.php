<div class="script_header">
	<img src="<?=$config['base_url']?>images/script_headers/boerse.jpg" alt="" />
<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	if (isset($_POST['buy']))
	{
		$amount = View::NumbersOnly($db->EscapeString($_POST['num_stocks']));
		
		$stocks = $db->EscapeString($_POST['stockID']);
		$sql = $db->Query("SELECT id,shares,current_price,business_id FROM `stocks` WHERE `id`='".$stocks."' AND `active`='1'");
		$stocks = $db->FetchArray($sql);
		$shares = unserialize($stocks['shares']);
		
		$price = $stocks['current_price'] * $amount;
		
		if ($stocks['id'] == '')
		{
			echo View::Message($langBase->get('bursa-02'), 2);
		}
		elseif ($amount <= 0)
		{
			echo View::Message($langBase->get('bursa-03'), 2);
		}
		elseif ($shares[Player::Data('id')]+$amount > $config['businesses_max_stocks'])
		{
			echo View::Message($langBase->get('bursa-04', array('-MAX-' => View::CashFormat($config['businesses_max_stocks']))), 2);
		}
		elseif ($price > Player::Data('cash'))
		{
			echo View::Message($langBase->get('err-01'), 2);
		}
		else
		{
			$shares[Player::Data('id')] += $amount;
			
			$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$price."' WHERE `id`='".Player::Data('id')."'");
			$db->Query("UPDATE `stocks` SET `shares`='".serialize($shares)."', `current_income`=`current_income`+'".$price."' WHERE `id`='".$stocks['id']."'");
			$db->Query("UPDATE `businesses` SET `bank`=`bank`+'".$price."', `bank_income`=`bank_income`+'".$price."' WHERE `id`='".$stocks['business_id']."'") or die(mysql_error());
			$db->Query("INSERT INTO `business_log` (`b_id`, `text`, `type`, `added`, `added_date`)VALUES('".$stocks['business_id']."', 'Das Unternehmen erhielt ".number_format($price, 0, '.', ' ')."$, din actiuni', '11', '".time()."', '".date('d.m.Y')."')");
			
			View::Message($langBase->get('bursa-05', array('-NUM-' => View::CashFormat($amount), '-CASH-' => View::CashFormat($price))), 1, true);
		}
	}
	elseif (isset($_POST['sell']))
	{
		$amount = View::NumbersOnly($db->EscapeString($_POST['num_stocks']));
		
		$stocks = $db->EscapeString($_POST['stockID']);
		$sql = $db->Query("SELECT id,shares,current_price,business_id FROM `stocks` WHERE `id`='".$stocks."' AND `active`='1'");
		$stocks = $db->FetchArray($sql);
		$shares = unserialize($stocks['shares']);
		
		$price = $stocks['current_price'] * $amount;
		
		if ($stocks['id'] == '')
		{
			echo View::Message($langBase->get('bursa-02'), 2);
		}
		elseif ($amount <= 0)
		{
			echo View::Message($langBase->get('bursa-06'), 2);
		}
		elseif ($amount > $shares[Player::Data('id')])
		{
			echo View::Message($langBase->get('bursa-07'), 2);
		}
		else
		{
			$shares[Player::Data('id')] -= $amount;
			
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$price."' WHERE `id`='".Player::Data('id')."'");
			$db->Query("UPDATE `stocks` SET `shares`='".serialize($shares)."', `current_income`=`current_income`-'".$price."' WHERE `id`='".$stocks['id']."'");
			$db->Query("UPDATE `businesses` SET `bank`=`bank`-'".$price."', `bank_loss`=`bank_loss`+'".$price."' WHERE `id`='".$stocks['business_id']."'");
			$db->Query("INSERT INTO `business_log` (`b_id`, `text`, `type`, `added`, `added_date`)VALUES('".$stocks['business_id']."', 'Das Unternehmen hat ausgegeben: ".number_format($price, 0, '.', ' ')."$, in actiuni', '11', '".time()."', '".date('d.m.Y')."')");
			
			View::Message($langBase->get('bursa-08', array('-NUM-' => View::CashFormat($amount), '-CASH-' => View::CashFormat($price))), 1, true);
		}
	}
	
	$sql = $db->Query("SELECT id,business_type,business_id,current_price,shares,last_change_time,last_change_percent,last_price FROM `stocks` WHERE `active`='1'");
	$stocks = $db->FetchArrayAll($sql);
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('bursa-01')?></h1>
    <?php
	if (count($stocks) <= 0)
	{
		echo '<p>'.$langBase->get('err-06').'</p>';
	}
	else
	{
	?>
    <form method="post" action="">
        <table class="table boxHandle">
            <thead>
                <tr>
                    <td><?=$langBase->get('function-company')?></td>
                    <td><?=$langBase->get('txt-03')?></td>
                    <td><?=$langBase->get('bursa-09')?></td>
                    <td><?=$langBase->get('bursa-10')?></td>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($stocks as $stock)
            {
                $i++;
                $c = $i%2 ? 1 : 2;
                
                $shares = unserialize($stock['shares']);
                
				$percent = View::AsPercent($stock['current_price'], $stock['last_price'], 2);
				$percent = $percent == 0 ? 0 : ($percent - 100);
                $last_change = '';
                if ($percent == 0)
                {
                    $last_change = '<span class="large">'.$langBase->get('bursa-11').'</span>';
                }
                elseif ($percent > 0)
                {
                    $last_change = '<span class="large yellow"><b>&uarr; ' . round($percent, 2) . ' %</b></span>';
                }
                elseif ($percent < 0)
                {
                    $last_change = '<span class="large red"><b>&darr; ' . abs(round($percent, 2)) . ' %</b></span>';
                }
            ?>
                <tr class="c_<?=$c?> boxHandle">
                    <td><input type="radio" name="stockID" value="<?=$stock['id']?>" /><?php
                    if ($stock['business_type'] == 'game_business')
                    {
                        $sql = $db->Query("SELECT id,name FROM `businesses` WHERE `id`='".$stock['business_id']."'");
                        $firma = $db->FetchArray($sql);
                        
                        echo '&laquo;<a href="' . $config['base_url'] . '?side=firma/firma&amp;id=' . $firma['id'] . '">' . $firma['name'] . '</a>&raquo;';
                    }
                    ?></td>
                    <td class="center"><?=View::CashFormat($stock['current_price'])?> $</td>
                    <td class="t_right"><?=$last_change?><br /><span class="subtext"><?=View::Time($stock['last_change_time'], true, 'H:i')?></span></td>
                    <td class="t_right"><?=View::CashFormat($shares[Player::Data('id')])?></td>
                </tr>
            <?php
            }
            ?>
                <tr class="c_3">
                    <td colspan="4">
                        <dl class="dd_right" style="margin: 0px auto; width: 250px;">
                            <dt><?=$langBase->get('bursa-12')?></dt>
                            <dd><input type="text" class="flat" name="num_stocks" value="<?=View::CashFormat(View::NumbersOnly($_POST['num_stocks']))?>" /></dd>
                        </dl>
                        <p class="center clear">
                            <input type="submit" name="buy" value="<?=$langBase->get('txt-01')?>" /> 
                            <input type="submit" name="sell" value="<?=$langBase->get('txt-15')?>" />
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    <?php
	}
	?>
</div>
</div>