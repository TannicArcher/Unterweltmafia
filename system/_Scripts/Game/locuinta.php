<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$my_houses = unserialize(Player::Data('houses'));
	if (empty($my_houses)) $my_houses = array();
	
	$my_house = $my_houses[Player::Data('live')];
	
	$production_stop = $my_houses['marijuana']['prod_stop'];
	if ($production_stop <= time() && $production_stop)
	{
		$amount = round($my_houses['marijuana']['prod_plants'] * $my_houses['marijuana']['prod_gpp'], 0);
		
		if ($amount > 0)
		{
			$my_houses['marijuana']['marijuana'] += $amount;
			unset($my_houses['marijuana']['prod_stop']);
			$db->Query("UPDATE `[players]` SET `houses`='".serialize($my_houses)."' WHERE `id`='".Player::Data('id')."'");
			
			Accessories::AddLogEvent(Player::Data('id'), 22, array(
				'-MARIJUANA-' => View::CashFormat($amount)
			), User::Data('id'));
			
			View::Message($langBase->get('casa-01', array('-GRAME-' => View::CashFormat($amount))), 1, true);
		}
	}
	else
	{
		if (isset($_POST['start_prod']) && $my_house && !$production_stop)
		{
			if ($my_house['plants'] <= 0)
			{
				echo View::Message($langBase->get('casa-02'), 2);
			}
			else
			{
				$gpp = $config['marijuana_per_plant'];
				foreach ($my_house['extra_equipment'] as $key)
				{
					$gpp += $config['marijuanaproduction_equipment'][$key]['gram_add'];
				}
				
				$my_houses['marijuana']['prod_start'] = time();
				$my_houses['marijuana']['prod_stop'] = time() + $config['marijuana_productiontime'];
				$my_houses['marijuana']['prod_place'] = Player::Data('live');
				$my_houses['marijuana']['prod_plants'] = $my_house['plants'];
				$my_houses['marijuana']['prod_gpp'] = $gpp;
				
				$db->Query("UPDATE `[players]` SET `houses`='".serialize($my_houses)."' WHERE `id`='".Player::Data('id')."'");
				
				View::Message($langBase->get('casa-03'), 1, true);
			}
		}
	}
	
	$avaliable_houses = array();
	foreach ($config['houses'] as $key => $house)
	{
		if ($key < $my_house['type'] || !$my_house['type'])
			$avaliable_houses[] = $key;
	}
	
	if (isset($_POST['buy_house']))
	{
		$house_id = $db->EscapeString($_POST['buy_house']);
		$house = $config['houses'][$house_id];
		
		if (!in_array($house_id, $avaliable_houses) || !$house)
		{
			echo View::Message('ERROR', 2);
		}
		elseif ($house['price'] > Player::Data('cash'))
		{
			echo View::Message($langBase->get('err-01'), 2);
		}
		else
		{
			$my_houses[Player::Data('live')] = array(
				'type' => $house_id
			);
			
			$db->Query("UPDATE `[players]` SET `houses`='".serialize($my_houses)."', `cash`=`cash`-'".$house['price']."' WHERE `id`='".Player::Data('id')."'");
			
			View::Message($langBase->get('casa-04'), 1, true);
		}
	}
?>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('casa-00')?></h1>
    <?php
	if (!$my_house)
	{
		echo '<h2>Du hast kein Haus in ' . $config['places'][Player::Data('live')][0] . '.</h2>';
	}
	else
	{
	?>
    <dl class="dd_right left large" style="width: 200px; margin-left: 50px;">
        <dt><?=$langBase->get('casa-00')?></dt>
        <dd><?=$config['houses'][$my_house['type']]['title']?></dd>
		<dt><?=$langBase->get('txt-05')?></dt>
		<dd><?=$config['places'][Player::Data('live')][0]?></dd>
        <dt><?=$langBase->get('casa-05')?></dt>
        <dd><?=($config['houses'][$my_house['type']]['training'] ? $langBase->get('ot-yes') : $langBase->get('ot-no'))?></dd>
    </dl>
    <p class="right" style="margin-right: 50px;">
        <span class="bbimg_thumbnail"><img src="<?=$config['base_url']?>images/houses/h_<?=$my_house['type']?>.jpg" alt="" class="handle_image" /></span>
    </p>
    <div class="clear"></div>
    <?php
	}
	?>
    <div class="bg_c c_1 w400">
        <h1 class="big"><?=$langBase->get('casa-06')?></h1>
        <?php
        if (count($avaliable_houses) <= 0)
        {
            echo '<p>'.$langBase->get('casa-13').'</p>';
        }
        else
        {
        ?>
        <form method="post" action="">
            <table class="table boxHandle">
                <thead>
                    <tr class="small">	
                        <td><?=$langBase->get('casa-00')?></td>
                        <td><?=$langBase->get('txt-22')?></td>
                        <td><?=$langBase->get('txt-03')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($avaliable_houses as $house_id)
                {
                    $i++;
                    $c = $i%2 ? 1 : 2;
                ?>
                    <tr class="c_<?=$c?> boxHandle">	
                        <td><input type="radio" name="buy_house" value="<?=$house_id?>" /><?=$config['houses'][$house_id]['title']?></td>
                        <td>
                            <div class="left" style="margin-bottom: 5px;"><span class="bbimg_thumbnail" style="max-width: 40px; max-height: 40px;"><img src="<?=$config['base_url']?>images/houses/h_<?=$house_id?>.jpg" alt="" width="150" height="150" class="handle_image" /></span></div>
                            <div class="right" style="margin-left: 10px;"><p><?=($config['houses'][$house_id]['training'] ? $langBase->get('casa-07') : $langBase->get('casa-08'))?></p><p><?=($config['houses'][$house_id]['basement'] ? $langBase->get('casa-09') : $langBase->get('casa-10'))?></p></div>
                            <div class="clear"></div>
                        </td>
                        <td class="t_right"><?=View::CashFormat($config['houses'][$house_id]['price'])?> $</td>
                    </tr>
                <?php
                }
                ?>
                    <tr class="c_3 center">
                        <td colspan="3"><input type="submit" value="<?=$langBase->get('casa-06')?>" /></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <?php
        }
        ?>
    </div>
</div>
<?php
if ($my_house)
{
?>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('casa-11')?></h1>
    <?php
	if (!$config['houses'][$my_house['type']]['basement'])
	{
		echo '<h2>'.$langBase->get('casa-12').'</h2>';
	}
	else
	{
		$avaliable_equipment = array();
		foreach ($config['marijuanaproduction_equipment'] as $key => $value)
		{
			if (!in_array($key, $my_house['extra_equipment']))
				$avaliable_equipment[] = $key;
		}
		
		$avaliable_plants = $config['houses'][$my_house['type']]['max_plants'] - $my_house['plants'];
		
		if (isset($_POST['buy_plants']) && $avaliable_plants > 0)
		{
			$num = View::NumbersOnly($db->EscapeString($_POST['buy_plants']));
			$price = $config['marijuana_price'] * $num;
			
			if ($num <= 0)
			{
				echo View::Message($langBase->get('casa-14'), 2);
			}
			elseif ($num > $avaliable_plants)
			{
				echo View::Message($langBase->get('casa-15'), 2);
			}
			elseif ($price <= 0)
			{
				echo View::Message($langBase->get('err-08'), 2);
			}
			elseif ($price > Player::Data('cash'))
			{
				echo View::Message($langBase->get('err-01'), 2);
			}
			else
			{
				$my_houses[Player::Data('live')]['plants'] += $num;
				$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$price."', `houses`='".serialize($my_houses)."' WHERE `id`='".Player::Data('id')."'");
				
				View::Message($langBase->get('casa-16', array('-PLANTS-' => View::CashFormat($num), '-CASH-' => View::CashFormat($price))), 1, true);
			}
		}
		elseif (isset($_POST['buy_extra']) && count($avaliable_equipment) > 0)
		{
			$key = $db->EscapeString($_POST['buy_extra']);
			$equipment = $config['marijuanaproduction_equipment'][$key];
			
			if (!$equipment)
			{
				echo View::Message('ERROR', 2);
			}
			elseif (in_array($key, $my_house['extra_equipment']))
			{
				echo View::Message($langBase->get('casa-17'), 2);
			}
			elseif ($equipment['price'] > Player::Data('cash'))
			{
				echo View::Message($langBase->get('err-01'), 2);
			}
			else
			{
				$my_houses[Player::Data('live')]['extra_equipment'][] = $key;
				$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$equipment['price']."', `houses`='".serialize($my_houses)."' WHERE `id`='".Player::Data('id')."'");
				
				View::Message($langBase->get('casa-18', array('-PRODUCT-' => $equipment['title'])), 1, true);
			}
		}
		elseif (isset($_POST['sell_marijuana']))
		{
			$amount = View::NumbersOnly($db->EscapeString($_POST['sell_marijuana']));
			$price = $amount * $config['marijuana_price'];
			
			if ($amount <= 0)
			{
				echo View::Message($langBase->get('casa-19'), 2);
			}
			elseif ($amount > $my_houses['marijuana']['marijuana'])
			{
				echo View::Message($langBase->get('casa-20'), 2);
			}
			else
			{
				$my_houses['marijuana']['marijuana'] -= $amount;
				$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$price."', `houses`='".serialize($my_houses)."' WHERE `id`='".Player::Data('id')."'");
				
				Accessories::AddLogEvent(Player::Data('id'), 23, array(
					'-MARIJUANA-' => View::CashFormat($amount),
					'-MONEY-' => View::CashFormat($price)
				), User::Data('id'));
				
				View::Message($langBase->get('casa-21', array('-PLANTS-' => View::CashFormat($amount), '-CASH-' => View::CashFormat($price))), 1, true);
			}
		}
	?>
    <div class="right">
    	<div class="bg_c c_1 w200" style="margin-top: 0; margin-right: 0;">
            <h1 class="big"><?=$langBase->get('casa-22')?></h1>
            <form method="post" action="">
                <p><?=$langBase->get('casa-23', array('-GRAME-' => View::CashFormat($my_houses['marijuana']['marijuana'])))?></p>
                <p><?=View::CashFormat($config['marijuana_price'])?> $ / <?=$langBase->get('casa-24')?>.</p>
                <dl class="dd_right">
                    <dt><?=$langBase->get('casa-25')?> (<a href="#" onclick="document.getElementById('sell_marijuana').value = '<?=View::CashFormat($my_houses['marijuana']['marijuana'])?>'; return false;"><?=$langBase->get('txt-26')?></a>)</dt>
                    <dd><input type="text" name="sell_marijuana" id="sell_marijuana" class="flat" value="<?=View::CashFormat(View::NumbersOnly($_POST['sell_marijuana']))?>" /></dd>
                </dl>
                <p class="clear center">
                    <input type="submit" value="<?=$langBase->get('casa-22')?>" />
                </p>
            </form>
        </div>
    <?php if($avaliable_plants > 0):?>
    <div class="bg_c c_1 w200" style="margin-top: 0; margin-right: 0;">
    	<h1 class="big"><?=$langBase->get('casa-26')?></h1>
        <form method="post" action="">
        	<p><?=$langBase->get('casa-27', array('-PLANTE-' => View::CashFormat($avaliable_plants)))?></p>
        	<dl class="dd_right">
            	<dt><?=$langBase->get('casa-25')?></dt>
                <dd><input type="text" name="buy_plants" class="flat" value="<?=View::CashFormat(View::NumbersOnly($_POST['buy_plants']))?>" /></dd>
            </dl>
            <p class="clear center">
            	<input type="submit" value="<?=$langBase->get('txt-01')?>" />
            </p>
        </form>
    </div>
    <?php endif;?>
    <?php if(count($avaliable_equipment) > 0):?>
    <div class="bg_c c_1 w200" style="margin-top: 0; margin-right: 0;">
    	<h1 class="big"><?=$langBase->get('casa-28')?></h1>
        <form method="post" action="">
        	<p><?=$langBase->get('casa-29')?></p>
        	<dl class="dd_right">
            	<dt></dt>
                <dd>
                	<select name="buy_extra">
                    	<?php
						foreach ($avaliable_equipment as $key)
						{
							echo '<option value="' . $key . '">' . $config['marijuanaproduction_equipment'][$key]['title'] . ' - ' . View::CashFormat($config['marijuanaproduction_equipment'][$key]['price']) . ' $</option>';
						}
						?>
                    </select>
                </dd>
            </dl>
            <p class="clear center">
            	<input type="submit" value="<?=$langBase->get('txt-01')?>" />
            </p>
        </form>
    </div>
    <?php endif;?>
    </div>
    <h2><?=$langBase->get('casa-30')?></h2>
    <p><?=$langBase->get('casa-31', array('-PLANTE-' => View::CashFormat($my_house['plants'])))?></p><br>
	<p><b><?=$langBase->get('casa-32')?>:</b></p>
    <?php
	if (count($my_house['extra_equipment']) <= 0)
	{
		echo '<p>'.$langBase->get('err-06').'</p>';
	}
	else
	{
		echo '<ul>';
		
		foreach ($my_house['extra_equipment'] as $key)
		{
			echo '<li>' . $config['marijuanaproduction_equipment'][$key]['title'] . '</li>';
		}
		
		echo '</ul>';
	}
	?>
    <div class="hr big" style="margin: 10px 0 10px 0;"></div>
    <?php
	if ($production_stop > time())
	{
	?>
    <p><?=$langBase->get('casa-33', array('-CITY-' => $config['places'][$my_houses['marijuana']['prod_place']][0], '-TIME-' => View::strTime($production_stop - time(), 1, ', ')))?></p>
    <?php
	}
	else
	{
	?>
    <form method="post" action="">
    	<p><?=$langBase->get('casa-34')?></p>
        <p><input type="submit" name="start_prod" value="<?=$langBase->get('casa-35')?>" /></p>
    </form>
    <?php
	}
	?>
    <?php
	}
	?>
</div>
<?php
}
?>