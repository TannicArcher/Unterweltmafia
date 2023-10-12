<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$all_cars = array();
	$sql = $db->Query("SELECT id,place,damage FROM `cars` WHERE `owner`='".Player::Data('id')."' AND `active`='1' AND `sale`='0'");
	while ($car = $db->FetchArray($sql))
	{
		$all_cars[$car['place']]++;
		
		if ($car['damage'] >= $config['car_max_damage'])
		{
			$db->Query("UPDATE `cars` SET `active`='0' WHERE `id`='".$car['id']."'");
		}
	}
?>
<div class="script_header">
	<img src="<?=$config['base_url']?>images/script_headers/garage.jpg" alt="" />
    <div class="bottom">
    	<div class="infobox"><?=($all_cars[Player::Data('live')] <= 0 ? $langBase->get('garaj-01') : $langBase->get('garaj-02', array('-CARS-' => View::CashFormat($all_cars[Player::Data('live')]))))?></div>
        <a href="<?=$config['base_url']?>?side=fura-masini" class="link"><?=$langBase->get('function-carTheft')?></a>
    </div>
</div>
<?php
	show_messages();
	
	if (isset($_POST['sell_cars']) || isset($_POST['sell_all']))
	{
		if (isset($_POST['sell_all']))
		{
			$sql = "SELECT id,car_type,damage,horsepowers FROM `cars` WHERE `owner`='".Player::Data('id')."' AND `active`='1' AND `place`='".Player::Data('live')."' AND `sale`='0'";
		}
		else
		{
			$selected = isset($_POST['sell_cars']) ? $db->EscapeString($_POST['cars']) : array();
			if (empty($selected))
			{
				View::Message($langBase->get('garaj-03'), 2, true);
			}
			
			$sql = "SELECT id,car_type,damage,horsepowers FROM `cars` WHERE `owner`='".Player::Data('id')."' AND `active`='1' AND `place`='".Player::Data('live')."' AND `sale`='0' AND `id` IN(".implode(',', $selected).")";
		}
		
		$cars = array();
		$sql = $db->Query($sql);
		while ($car = $db->FetchArray($sql))
		{
			if ($cars[$car['id']])
				continue;
			
			$cars[$car['id']] = $car;
		}
		
		if (count($cars) <= 0)
		{
			echo View::Message($langBase->get('garaj-03'), 2);
		}
		else
		{
			$sold_cars = 0;
			$toDelete = array();
			
			foreach ($cars as $car)
			{
				if ($car['id'] != '')
				{
					$carType = $config['cars'][$car['car_type']];
					
					$price = $carType['price_per_hp'] * $car['horsepowers'];
					$price = round(($price - ($price/100 * View::AsPercent($car['damage'], $config['car_max_damage'], 2))), 0);
					
					$sold_cars++;
					$money += $price;
					
					$toDelete[] = $car['id'];
				}
			}
			
			$db->Query("UPDATE `cars` SET `active`='0' WHERE `id` IN(".implode(',', $toDelete).")");
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$money."' WHERE `id`='".Player::Data('id')."'");
			
			View::Message($sold_cars <= 0 ? $langBase->get('garaj-04') : $langBase->get('garaj-05', array('-CARS-' => View::CashFormat($sold_cars), '-CASH-' => View::CashFormat($money))), 1, true);
		}
	}
	elseif (isset($_POST['move_cars']))
	{
		$cars = $db->EscapeString($_POST['cars']);
		$placeID = $db->EscapeString($_POST['move_place']);
		$place = $config['places'][$placeID];
		
		if (count($cars) <= 0)
		{
			echo View::Message($langBase->get('garaj-06'), 2);
		}
		elseif (!$place)
		{
			echo View::Message($langBase->get('garaj-07'), 2);
		}
		elseif ($placeID == Player::Data('live'))
		{
			echo View::Message($langBase->get('garaj-08'), 2);
		}
		else
		{
			$moved_cars = array();
			
			$selected = isset($_POST['cars']) ? $db->EscapeString($_POST['cars']) : array();
			if (empty($selected))
			{
				View::Message($langBase->get('garaj-06'), 2, true);
			}
			else
			{
				$sql = $db->Query("SELECT id FROM `cars` WHERE `owner`='".Player::Data('id')."' AND `active`='1' AND `place`='".Player::Data('live')."' AND `sale`='0' AND `id` IN(".implode(',', $selected).")");
				while ($car = $db->FetchArray($sql))
				{
					if (in_array($car['id'], $moved_cars))
						continue;
					
					$moved_cars[] = $car['id'];
				}
			}
			
			$db->Query("UPDATE `cars` SET `place`='".$placeID."' WHERE `id` IN(".implode(',', $moved_cars).")");
			
			View::Message($langBase->get('garaj-32'), 1, true);
		}
	}
	elseif (isset($_POST['_sell_cars']))
	{
		$cars = $db->EscapeString($_POST['cars']);
		$price = View::NumbersOnly($db->EscapeString($_POST['sell_price']));
		
		if (count($cars) <= 0)
		{
			echo View::Message($langBase->get('garaj-06'), 2);
		}
		elseif ($price <= 0)
		{
			echo View::Message($langBase->get('err-08'), 2);
		}
		else
		{
			$listed_cars = array();
			
			$selected = isset($_POST['cars']) ? $db->EscapeString($_POST['cars']) : array();
			if (empty($selected))
			{
				View::Message($langBase->get('garaj-06'), 2, true);
			}
			else
			{
				$sql = $db->Query("SELECT id FROM `cars` WHERE `owner`='".Player::Data('id')."' AND `active`='1' AND `place`='".Player::Data('live')."' AND `sale`='0' AND `id` IN(".implode(',', $selected).")");
				while ($car = $db->FetchArray($sql))
				{
					if (in_array($car['id'], $listed_cars))
						continue;
					
					$listed_cars[] = $car['id'];
				}
			}
			
			$db->Query("UPDATE `cars` SET `sale`='".$price."' WHERE `id` IN(".implode(',', $listed_cars).")");
			
			View::Message(count($listed_cars) <= 0 ? $langBase->get('garaj-09') : $langBase->get('garaj-10', array('-CARS-' => View::CashFormat(count($listed_cars)), '-PRICE-' => View::CashFormat($price))), 1, true);
		}
	}
	elseif (isset($_POST['repair_cars']))
	{
		$cars_c = isset($_POST['cars']) ? $db->EscapeString($_POST['cars']) : array();
		
		if (count($cars_c) <= 0)
		{
			echo View::Message($langBase->get('garaj-06'), 2);
		}
		else
		{
			$toRepair = array();
			
			$sql = $db->Query("SELECT id,car_type,damage,horsepowers FROM `cars` WHERE `owner`='".Player::Data('id')."' AND `active`='1' AND `place`='".Player::Data('live')."' AND `damage`>='1' AND `sale`='0' AND `id` IN(".implode(',', $cars_c).")");
			while ($car = $db->FetchArray($sql))
			{
				$toRepair[] = $car['id'];
				
				$price = ($config['cars'][$car['car_type']]['price_per_hp'] * $car['horsepowers'])/100 * View::AsPercent($car['damage'], $config['car_max_damage'], 2);
				
				$money += $price;
			}
			
			if ($money > Player::Data('cash'))
			{
				echo View::Message($langBase->get('err-01'), 2);
			}
			else
			{
				if (count($toRepair) > 0)
				{
					$db->Query("UPDATE `cars` SET `damage`='0' WHERE `id` IN(".implode(',', $toRepair).")");
					$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$money."' WHERE `id`='".Player::Data('id')."'");
				}
				
				View::Message(count($toRepair) <= 0 ? $langBase->get('garaj-11') : $langBase->get('garaj-12', array('-CARS-' => View::CashFormat(count($toRepair)), '-PRICE-' => View::CashFormat($money))), 1, true);
			}
		}
	}
	
	$sql = "SELECT id,car_type,horsepowers,damage,acquired FROM `cars` WHERE `owner`='".Player::Data('id')."' AND `active`='1' AND `place`='".Player::Data('live')."' AND `sale`='0' ORDER BY id DESC";
	$pagination = new Pagination($sql, 10, 'p');
	$cars = $pagination->GetSQLRows();
?>
<div style="width: 620px; margin: 0px auto;">
	<div class="left" style="width: 415px;">
    	<div class="bg_c" style="width: 395px;">
        	<h1 class="big"><?=$langBase->get('garaj-00')?><a href="<?=$config['base_url']?>?side=vanzari-masini" style="position: absolute; right: 5px;">&laquo; <?=$langBase->get('garaj-13')?></a></h1>
            <?php
			if (count($cars) <= 0)
			{
				echo '<p>'.$langBase->get('garaj-14').'</p>';
			}
			else
			{
			?>
            <form method="post" action="">
            	<table class="table boxHandle">
                	<thead>
                    	<tr class="small">
                        	<td><a href="#" onclick="check_boxhandle('cars[]'); return false;"><?=$langBase->get('garaj-15')?></a></td>
                            <td><?=$langBase->get('txt-27')?></td>
                            <td><?=$langBase->get('garaj-16')?></td>
                            <td><?=$langBase->get('garaj-17')?></td>
                            <td><?=$langBase->get('garaj-18')?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
					foreach ($cars as $car)
					{
						$i++;
						$c = $i%2 ? 1 : 2;
						
						$carType = $config['cars'][$car['car_type']];
						
						$price = $carType['price_per_hp'] * $car['horsepowers'];
					?>
                    	<tr class="c_<?=$c?> boxHandle">
                        	<td><input type="checkbox" name="cars[]" value="<?=$car['id']?>" /><?=$carType['brand']?><br /><b><?=$carType['model']?></b></td>
                            <td class="center"><?=View::Time($car['acquired'], false, 'H:i')?></td>
                            <td class="center"><?=View::CashFormat($car['horsepowers'])?> <?=$langBase->get('garaj-19')?></td>
                            <td class="center"><?=View::AsPercent($car['damage'], $config['car_max_damage'], 2)?> %</td>
                            <td class="center"><?=(View::CashFormat(round(($price - ($price/100 * View::AsPercent($car['damage'], $config['car_max_damage'], 2))), 0)))?> $</td>
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
                	<input type="submit" name="sell_cars" value="<?=$langBase->get('txt-15')?>" /> 
                    <input type="submit" name="sell_all" value="<?=$langBase->get('garaj-20')?>" /> 
                    <input type="submit" name="repair_cars" value="<?=$langBase->get('garaj-22')?>" /> <br />
                    <input type="submit" onclick="$('movecars').toggleClass('hidden'); return false;" value="<?=$langBase->get('garaj-21')?>" /> <br />
                    <input type="submit" onclick="$('sellcars').toggleClass('hidden'); return false;" value="<?=$langBase->get('garaj-23')?>" />
                </p>
                <p id="movecars" class="hidden center c_1" style="margin-top: 10px; padding: 5px;">
                	<span class="yellow bold"><?=$langBase->get('garaj-24')?></span><br />
                    <select name="move_place" style="margin-top: 6px;">
                    <?php
					foreach ($config['places'] as $key => $place)
					{
						echo '<option value="' . $key . '"' . (isset($_POST['move_place']) && $_POST['move_place'] == $key ? ' selected="selected"' : '') . '>' . $place[0] . '</option>';
					}
					?>
                    </select><br />
                    <input type="submit" name="move_cars" value="<?=$langBase->get('garaj-21')?>" style="margin-top: 7px;" />
                </p>
                <p id="sellcars" class="hidden center c_1" style="margin-top: 10px; padding: 5px;">
                	<span class="yellow bold"><?=$langBase->get('txt-03')?>:</span><br />
                    <input type="text" name="sell_price" class="flat" style="margin-top: 7px;" value="<?=View::CashFormat(View::NumbersOnly($_POST['sell_price']))?> $" /><br />
                    <input type="submit" name="_sell_cars" value="Trimite" style="margin-top: 7px;" /><br />
                    <br />
                    <span class="dark"><?=$langBase->get('garaj-25', array('-PERCENT-' => 100-$config['car_sale_transfer_fee']))?></span>
                </p>
            </form>
            <?php
			}
			?>
        </div>
    </div>
    <div class="left" style="width: 185px; margin-left: 10px;">
    	<div class="bg_c" style="width: 165px;">
        	<h1 class="big"><?=$langBase->get('garaj-26')?></h1>
            <dl class="dd_right">
			<?php
            foreach ($config['places'] as $key => $place)
            {
                echo '<dt' . ($key == Player::Data('live') ? ' class="yellow"' : '') . '>' . $place[0] . '</dt><dd>' . View::CashFormat($all_cars[$key]) . ' ' . $langBase->get('garaj-27') . '</dd>';
            }
            ?>
            </dl>
            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
</div>