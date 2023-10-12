<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	if (isset($_POST['buy_car']))
	{
		$car = $db->EscapeString($_POST['buy_car']);
		$sql = $db->Query("SELECT id,sale,owner FROM `cars` WHERE `id`='".$car."' AND `active`='1' AND `place`='".Player::Data('live')."' AND `sale`>='1'");
		$car = $db->FetchArray($sql);
		
		if ($car['id'] == '')
		{
			echo View::Message($langBase->get('garaj-29'), 2);
		}
		elseif ($car['owner'] == Player::Data('id'))
		{
			echo View::Message('This car was removed from sale.', 1);
			$db->Query("UPDATE `cars` SET `sale`='0' WHERE `id`='".$car['id']."'");
		}
		elseif ($car['sale'] > Player::Data('cash'))
		{
			echo View::Message($langBase->get('err-01'), 2);
		}
		else
		{
			$db->Query("UPDATE `cars` SET `sale`='0', `owner`='".Player::Data('id')."' WHERE `id`='".$car['id']."'");
			$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$car['sale']."' WHERE `id`='".Player::Data('id')."'");
			
			$toSeller = round($car['sale'] - ($car['sale']/100 * $config['car_sale_transfer_fee']), 0);
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$toSeller."' WHERE `id`='".$car['owner']."'");
			
			View::Message($langBase->get('garaj-30', array('-CASH-' => View::CashFormat($car['sale']))), 1, true);
		}
	}
	
	$sql = "SELECT id,car_type,damage,horsepowers,acquired,sale,owner FROM `cars` WHERE `active`='1' AND `place`='".Player::Data('live')."' AND `sale`>='1'";
	$pagination = new Pagination($sql, 20, 'p');
	$cars = $pagination->GetSQLRows();
?>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('garaj-31')?> <?=$config['places'][Player::Data('live')][0]?><a href="<?=$config['base_url']?>?side=garaj" style="position: absolute; right: 5px;">&laquo; <?=$langBase->get('garaj-00')?></a></h1>
    <?php
	if (count($cars) <= 0)
	{
		echo '<h2>'.$langBase->get('err-06').'</h2>';
	}
	else
	{
	?>
    <form method="post" action="">
    	<table class="table boxHandle">
            <thead>
                <tr class="small">
                    <td><?=$langBase->get('garaj-15')?></td>
                    <td><?=$langBase->get('piata-11')?></td>
                    <td><?=$langBase->get('garaj-16')?></td>
                    <td><?=$langBase->get('garaj-17')?></td>
                    <td><?=$langBase->get('txt-03')?></td>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($cars as $car)
            {
                $i++;
                $c = $i%2 ? 1 : 2;
                
                $carType = $config['cars'][$car['car_type']];
            ?>
                <tr class="c_<?=$c?> boxHandle">
                    <td><input type="radio" name="buy_car" value="<?=$car['id']?>" /><?=$carType['brand']?><br /><b><?=$carType['model']?></b></td>
                    <td><?=View::Player(array('id' => $car['owner']))?></td>
                    <td class="center"><?=View::CashFormat($car['horsepowers'])?> <?=$langBase->get('garaj-19')?></td>
                    <td class="center"><?=View::AsPercent($car['damage'], $config['car_max_damage'], 2)?> %</td>
                    <td class="center"><?=View::CashFormat($car['sale'])?> $</td>
                </tr>
            <?php
            }
            ?>
                <tr class="c_3">
                    <td colspan="5"><?=$pagination->GetPageLinks()?></td>
                </tr>
            </tbody>
        </table>
        <p class="center">
        	<input type="submit" value="<?=$langBase->get('txt-01')?>" />
        </p>
    </form>
    <?php
	}
	?>
</div>