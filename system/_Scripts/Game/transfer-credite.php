<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	if (isset($_POST['cancel_transfer']))
	{
		$transfer = $db->EscapeString($_POST['cancel_transfer']);
		$sql = $db->Query("SELECT id,`from`,amount FROM `point_transfers` WHERE `from`='".Player::Data('id')."' AND `active`='1' AND `id`='".$transfer."'");
		$transfer = $db->FetchArray($sql);
		
		if ($transfer['id'] == '')
		{
			echo View::Message('ERROR', 2);
		}
		else
		{
			$db->Query("UPDATE `point_transfers` SET `active`='0' WHERE `id`='".$transfer['id']."'");
			$db->Query("UPDATE `[players]` SET `points`=`points`+'".$transfer['amount']."' WHERE `id`='".$transfer['from']."'");
			
			View::Message($langBase->get('trc-01'), 1, true);
		}
	}
	elseif (isset($_POST['accept_transfer']))
	{
		$transfer = $db->EscapeString($_POST['accept_transfer']);
		$sql = $db->Query("SELECT id,amount,price,`from` FROM `point_transfers` WHERE `to`='".Player::Data('id')."' AND `active`='1' AND `id`='".$transfer."'");
		$transfer = $db->FetchArray($sql);
		
		if ($transfer['id'] == '')
		{
			echo View::Message('ERROR', 2);
		}
		elseif ($transfer['price'] > Player::Data('cash'))
		{
			echo View::Message($langBase->get('err-01'));
		}
		else
		{
			$db->Query("UPDATE `point_transfers` SET `active`='0', `completed`='1' WHERE `id`='".$transfer['id']."'");
			$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$transfer['price']."', `points`=`points`+'".$transfer['amount']."' WHERE `id`='".Player::Data('id')."'");
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$transfer['price']."' WHERE `id`='".$transfer['from']."'");
			
			View::Message($langBase->get('trc-02'), 1, true);
		}
	}
?>
<?php
if (isset($_GET['new']))
{
	if (isset($_POST['new_amount']))
	{
		$amount = View::NumbersOnly($db->EscapeString($_POST['new_amount']));
		$price = View::NumbersOnly($db->EscapeString($_POST['new_price']));
		
		$to = $db->EscapeString($_POST['new_to']);
		$to = $db->QueryFetchArray("SELECT id FROM `[players]` WHERE `name`='".$to."' AND `level`>'0' AND `health`>'0'");
		
		if ($to['id'] == '')
		{
			echo View::Message($langBase->get('err-02'), 2);
		}
		elseif ($amount > Player::Data('points'))
		{
			echo View::Message($langBase->get('err-09'), 2);
		}
		elseif ($amount <= 0)
		{
			echo View::Message($langBase->get('trc-03'), 2);
		}
		elseif ($price <= ($amount * 5000))
		{
			echo View::Message($langBase->get('trc-04', array('-CASH-' => View::CashFormat($amount * 5000))), 2);
		}
		else
		{
			$db->Query("UPDATE `[players]` SET `points`=`points`-'".$amount."' WHERE `id`='".Player::Data('id')."'");
			$db->Query("INSERT INTO `point_transfers` (`from`, `to`, `amount`, `price`, `time`)VALUES('".Player::Data('id')."', '".$to['id']."', '".$amount."', '".$price."', '".time()."')");
			
			Accessories::AddLogEvent($to['id'], 51, array(
							'-PLAYER_NAME-' => Player::Data('name'),
							'-CREDITS-' => $amount,
							'-CASH-' => $price
							), $to['userid']);
							
			View::Message($langBase->get('trc-05'), 1, true, '/game/?side=' . $_GET['side']);
		}
	}
?>
<div class="bg_c w300">
	<h1 class="big"><?=$langBase->get('trc-06')?></h1>
    <p><?=$langBase->get('trc-07', array('-NUM-' => View::CashFormat(Player::Data('points'))))?></p>
    <form method="post" action="">
    	<dl class="dd_right">
        	<dt><?=$langBase->get('txt-28')?></dt>
            <dd><input type="text" name="new_to" class="flat" value="<?=View::FixQuot($_POST['new_to'])?>" /></dd>
            <dt><?=$langBase->get('ot-points')?></dt>
            <dd><input type="text" name="new_amount" class="flat" value="<?=View::CashFormat(View::NumbersOnly($_POST['new_amount']))?> C" /></dd>
            <dt><?=$langBase->get('txt-03')?></dt>
            <dd><input type="text" name="new_price" class="flat" value="<?=View::CashFormat(View::NumbersOnly($_POST['new_price']))?> $" /></dd>
        </dl>
        <p class="clear center">
        	<input type="submit" value="<?=$langBase->get('txt-14')?>" /> 
            <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>" class="button">&laquo; <?=$langBase->get('txt-10')?></a>
        </p>
    </form>
</div>
<?php
}
else
{
	echo '<p class="center"><a href="' . $config['base_url'] . '?side=' . $_GET['side'] . '&amp;new" class="button">New Transfer &raquo;</a></p>';
}
?>
<div class="left" style="width: 300px; margin-left: 8px;">
	<div class="bg_c" style="width: 280px;">
    	<h1 class="big"><?=$langBase->get('trc-08')?></h1>
        <?php
		$sql = $db->Query("SELECT id,`to`,amount,price,time FROM `point_transfers` WHERE `from`='".Player::Data('id')."' AND `active`='1' ORDER BY id DESC");
		$transfers = $db->FetchArrayAll($sql);
		
		if (count($transfers) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
		?>
        <form method="post" action="">
        	<table class="table boxHandle">
            	<thead>
                	<tr class="small">
                    	<td><?=$langBase->get('txt-28')?></td>
                        <td><?=$langBase->get('ot-points')?></td>
                        <td><?=$langBase->get('txt-03')?></td>
                        <td><?=$langBase->get('txt-27')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
				foreach ($transfers as $transfer)
				{
					$i++;
					$c = $i%2 ? 1 : 2;
				?>
                	<tr class="boxHandle c_<?=$c?>">
                    	<td><input type="radio" name="cancel_transfer" value="<?=$transfer['id']?>" /><?=View::Player(array('id' => $transfer['to']))?></td>
                        <td class="center"><?=View::CashFormat($transfer['amount'])?> C</td>
                        <td class="center"><?=View::CashFormat($transfer['price'])?> $</td>
                        <td class="t_right"><?=View::Time($transfer['time'])?></td>
                    </tr>
                <?php
				}
				?>
                	<tr class="c_3 center">
                    	<td colspan="4"><input type="submit" value="Cancel" /></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <?php
		}
		?>
    </div>
</div>
<div class="left" style="width: 300px; margin-left: 20px;">
	<div class="bg_c" style="width: 280px;">
    	<h1 class="big"><?=$langBase->get('trc-09')?></h1>
        <?php
		$sql = $db->Query("SELECT id,`from`,time,amount,price FROM `point_transfers` WHERE `to`='".Player::Data('id')."' AND `active`='1' ORDER BY id DESC");
		$transfers = $db->FetchArrayAll($sql);
		
		if (count($transfers) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
		?>
        <form method="post" action="">
        	<table class="table boxHandle">
            	<thead>
                	<tr class="small">
                    	<td><?=$langBase->get('txt-31')?></td>
                        <td><?=$langBase->get('ot-points')?></td>
                        <td><?=$langBase->get('txt-03')?></td>
                        <td><?=$langBase->get('txt-27')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
				foreach ($transfers as $transfer)
				{
					$i++;
					$c = $i%2 ? 1 : 2;
				?>
                	<tr class="boxHandle c_<?=$c?>">
                    	<td><input type="radio" name="accept_transfer" value="<?=$transfer['id']?>" /><?=View::Player(array('id' => $transfer['from']))?></td>
                        <td class="center"><?=View::CashFormat($transfer['amount'])?> C</td>
                        <td class="center"><?=View::CashFormat($transfer['price'])?> $</td>
                        <td class="t_right"><?=View::Time($transfer['time'])?></td>
                    </tr>
                <?php
				}
				?>
                	<tr class="c_3 center">
                    	<td colspan="4"><input type="submit" value="<?=$langBase->get('txt-01')?>" /></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <?php
		}
		?>
    </div>
</div>
<div class="clear"></div>