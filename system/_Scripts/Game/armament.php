<div class="script_header">
	<img src="<?=$config['base_url']?>images/script_headers/waffenladen.jpg" alt="" />
<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	if (/*$config['bulletBuy_period'] === true*/ true)
	{
		$sql = $db->Query("SELECT id,misc FROM `businesses` WHERE `type`='3' AND `place`='".Player::Data('live')."' AND `active`='1'");
		$firma = $db->FetchArray($sql);
		$firma_misc = unserialize($firma['misc']);
		
		$reserved = $firma_misc['bullets_reserved'][Player::Data('id')];
		
		if (!isset($_SESSION['MZ_bulletBuy_hash']))
		{
			$_SESSION['MZ_bulletBuy_hash'] = 'MZ_' . substr(sha1(uniqid(rand())), 0, 10);
		}

		if ($reserved)
		{
			$sql = $db->Query("SELECT id,result FROM `antibot_sessions` WHERE `playerid`='".Player::Data('id')."' AND `script_name`='drapsshop' ORDER BY id DESC LIMIT 1");
			$antibot = $db->FetchArray($sql);
			
			if (floatval($reserved['time']+$config['bulletBuy_antibot_time'] < time()))
			{
				$db->Query("UPDATE `antibot_sessions` SET `active`='0', `result`='0' WHERE `id`='".$antibot['id']."'");
				$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$reserved['price']."' WHERE `id`='".Player::Data('id')."'");
				
				unset($bullets_reserved[Player::Data('id')], $firma_misc['bullets_reserved'][Player::Data('id')]);
				$firma_misc['bullets'] += $reserved['bullets'];
				$db->Query("UPDATE `businesses` SET `misc`='".serialize($firma_misc)."' WHERE `id`='".$firma['id']."'");
				
				View::Message($langBase->get('armament-01'), 2, true);
			}
			else
			{
				$db->Query("UPDATE `[players]` SET `bullets`=`bullets`+'".$reserved['bullets']."' WHERE `id`='".Player::Data('id')."'");
				
				unset($bullets_reserved[Player::Data('id')], $firma_misc['bullets_reserved'][Player::Data('id')]);
				$firma_misc['sold_bullets'] += $reserved['bullets'];
				$db->Query("UPDATE `businesses` SET `misc`='".serialize($firma_misc)."', `bank`=`bank`+'".$reserved['price']."', `bank_income`=`bank_income`+'".$reserved['price']."' WHERE `id`='".$firma['id']."'");
				
				View::Message($langBase->get('armament-02', array('-AMOUNT-' => View::CashFormat($reserved['bullets']))), 1, true);
			}
		}
	}
	
	if (isset($_POST['b_bullets_']))
	{
		if ($_POST['b_hash'] !== substr($_SESSION['MZ_bulletBuy_hash'], 3))
		{
			unset($_SESSION['MZ_bulletBuy_hash']);
			View::Message($langBase->get('armament-03'), 2, true);
		}
		
		if ($_POST['b_bullets'] == '0')
		{
			Accessories::AddToLog(Player::Data('id'), array('botChecker_numB'));
			View::Message($langBase->get('armament-03'), 2, true);
		}
		
		$bullets = View::NumbersOnly($db->EscapeString($_POST['b_bullets_']));
		$price = round($bullets * $config['bullet_price'], 0);
		
		if ($bullets <= 0)
		{
			View::Message($langBase->get('armament-04'), 2, true);
		}
		elseif ($bullets > $firma_misc['bullets'])
		{
			View::Message($langBase->get('armament-05'));
		}
		elseif ($price > Player::Data('cash'))
		{
			View::Message($langBase->get('err-01'), 2, true);
		}
		else
		{
			unset($_SESSION['MZ_bulletBuy_hash']);
			
			$firma_misc['bullets_reserved'][Player::Data('id')] = array(
				'time' => time(),
				'bullets' => $bullets,
				'price' => $price
			);
			$firma_misc['bullets'] -= $bullets;
			
			$db->Query("UPDATE `businesses` SET `misc`='".serialize($firma_misc)."' WHERE `id`='".$firma['id']."'");
			$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$price."' WHERE `id`='".Player::Data('id')."'");
			
			Accessories::CreateAntibotSession(Player::Data('id'), $db->EscapeString($_GET['side']));
			
			View::Message($langBase->get('armament-06', array('-BULLETS-' => View::CashFormat($bullets), '-TIME-' => $config['bulletBuy_antibot_time'])), 1, true);
		}
	}
	
	$my_weapons = unserialize(Player::Data('weapons'));
	ksort($my_weapons);
	
	$best_weapon = 0;
	foreach ($my_weapons as $key => $value)
	{
		if ($key > $best_weapon)
		{
			$best_weapon = $key;
		}
	}
	
	$best_weapon = $my_weapons[$best_weapon];
	$availiable_weapon = false;
	if ($best_weapon['training'] >= $config['weapon_min_training_to_buy'])
	{
		$availiable_weapon = $best_weapon['key'] + 1;
		
		if (!$config['weapons'][$availiable_weapon])
		{
			$availiable_weapon = 'nope';
		}
	}
	else
	{
		if (count($my_weapons) <= 0)
		{
			$availiable_weapon = 1;
		}
	}
	
	$my_weapon = $config['weapons'][Player::Data('weapon')];
	$my_weapon_data = $my_weapons[Player::Data('weapon')];
	
	$my_protection = $config['protections'][Player::Data('protection')];
	$next_protection = 0;
	foreach ($config['protections'] as $key => $value)
	{
		if ($key > Player::Data('protection'))
		{
			$next_protection = $key;
			break;
		}
	}
	
	if (isset($_POST['change_weapon']) && count($my_weapons) > 0)
	{
		$newWeapon = $db->EscapeString($_POST['change_weapon']);
		$weapon = $config['weapons'][$newWeapon];
		$weaponData = $my_weapons[$newWeapon];
		
		if (!$weapon || !$weaponData)
		{
			echo View::Message($langBase->get('armament-07'), 2);
		}
		elseif ($newWeapon == Player::Data('weapon'))
		{
			echo View::Message($langBase->get('armament-08'), 2);
		}
		else
		{
			$db->Query("UPDATE `[players]` SET `weapon`='".$newWeapon."' WHERE `id`='".Player::Data('id')."'");
			
			View::Message($langBase->get('armament-09', array('-WEAPON-' => $weapon['name'], '-TRAINING-' => View::AsPercent($weaponData['training'], $config['weapon_max_traning'], 2))), 1, true);
		}
	}
	elseif (isset($_POST['buy_weapon']) && $availiable_weapon != 'nope')
	{
		if ($availiable_weapon == false && count($my_weapons) > 0)
		{
			echo View::Message($langBase->get('armament-10'), 2);
		}
		else
		{
			$weapon = $config['weapons'][$availiable_weapon];
			
			if (!$weapon)
			{
				echo View::Message($langBase->get('armament-11'), 2);
			}
			elseif ($weapon['price'] > Player::Data('cash'))
			{
				echo View::Message($langBase->get('err-01'), 2);
			}
			else
			{
				$my_weapons[$availiable_weapon] = array(
					'key' => $availiable_weapon,
					'training' => 0
				);
				
				$db->Query("UPDATE `[players]` SET `weapons`='".serialize($my_weapons)."', `cash`=`cash`-'".$weapon['price']."' WHERE `id`='".Player::Data('id')."'");
				
				View::Message($langBase->get('armament-12', array('-WEAPON-' => $weapon['name'])), 1, true);
			}
		}
	}
	elseif (isset($_POST['buy_protection']) && $config['protections'][$next_protection])
	{
		$next = $config['protections'][$next_protection];
		
		if ($next['price'] > Player::Data('cash'))
		{
			echo View::Message($langBase->get('err-01'), 2);
		}
		else
		{
			$db->Query("UPDATE `[players]` SET `protection`='".$next_protection."', `cash`=`cash`-'".$next['price']."' WHERE `id`='".Player::Data('id')."'");
			
			View::Message($langBase->get('armament-13', array('-WEAPON-' => $next['name'])), 1, true);
		}
	}
	elseif (isset($_POST['weapon_train']) && $my_weapon && $my_weapon_data['training'] < $config['weapon_max_traning'] && Player::Data('last_weapon_training')+$config['weapon_training_wait'] <= time())
	{
		$points = rand($config['weapon_training_points'][0], $config['weapon_training_points'][1]);
		
		$my_weapons[Player::Data('weapon')]['training'] += $points;
		if ($my_weapons[Player::Data('weapon')]['training'] > $config['weapon_max_traning'])
			$my_weapons[Player::Data('weapon')]['training'] = $config['weapon_max_traning'];
		
		$db->Query("UPDATE `[players]` SET `weapons`='".serialize($my_weapons)."', `last_weapon_training`='".time()."' WHERE `id`='".Player::Data('id')."'");
		
		View::Message($langBase->get('armament-14', array('-PERCENT-' => View::AsPercent($points, $config['weapon_max_traning'], 2))), 1, true);
	}
?>
<div class="bg_c w300">
    <h1 class="big"><?=$langBase->get('armament-15')?></h1>
    <?php
        if ($firma_misc['bullets'] <= 0)
        {
            echo $langBase->get('armament-16');
        }
        else
        {
    ?>
    <p><?=$langBase->get('armament-17', array('-BULLETS-' => View::CashFormat($firma_misc['bullets'])))?></p>
    <p><?=$langBase->get('armament-18', array('-PRICE-' => View::CashFormat($config['bullet_price'])))?></p>
    <form method="post" action="">
        <input type="hidden" name="b_hash" value="<?php echo substr($_SESSION['MZ_bulletBuy_hash'], 3); ?>" />
        <dl class="dd_right">
            <dt><?=$langBase->get('armament-19')?> (<a href="#" onclick="$('b_bullets').value = '<?=(View::CashFormat(floor($firma_misc['bullets'] / 5)))?>'; return false;">1/5</a>)</dt>
            <dd><input type="text" name="b_bullets" class="flat hidden" value="0 G" /><input type="text" name="b_bullets_" id="b_bullets" class="flat" value="<?=View::CashFormat(View::NumbersOnly($_POST['b_bullets_']))?>" /></dd>
        </dl>
        <p class="center clear">
            <input type="submit" value="<?=$langBase->get('txt-01')?>" />
        </p>
    </form>
    <?php
        }
    ?>
</div>
<div class="left" style="width: 300px; margin-left: 5px;">
	<div class="bg_c" style="width: 280px;">
    	<h1 class="big"><?=$langBase->get('armament-20')?></h1>
        <?php
		$next = $config['protections'][$next_protection];
		
		if (!$my_protection)
		{
			echo $langBase->get('armament-21');
		}
		else
		{
		?>
        <p class="yellow"><?=$langBase->get('armament-22')?> <b><?=$my_protection['name']?></b>.</p>
        <?php
		}
		
		if ($next)
		{
		?>
        <p><?=$langBase->get('armament-23')?></p>
        <form method="post" action="">
        	<dl class="dd_right" style="margin-top: 0;">
        		<dt><?=$langBase->get('txt-02')?></dt>
                <dd><?=$next['name']?></dd>
                <dt><?=$langBase->get('txt-03')?></dt>
                <dd><?=View::CashFormat($next['price'])?> $</dd>
        	</dl>
            <p class="clear center">
            	<input type="submit" name="buy_protection" value="<?=$langBase->get('txt-01')?>" />
            </p>
        </form>
        <?php
		}
		?>
    </div>
</div>
<div class="left" style="width: 300px; margin-left: 25px;">
	<div class="bg_c" style="width: 280px;">
    	<h1 class="big"><?=$langBase->get('armament-24')?></h1>
        <?php
		if (count($my_weapons) <= 0)
		{
			echo $langBase->get('armament-25');
		}
		else
		{
		?>
        <p><?=$langBase->get('armament-26', array('-WEAPON-' => $my_weapon['name']))?></p>
        <form method="post" action="">
            <table class="table boxHandle">
                <thead>
                    <tr class="small">
                        <td><?=$langBase->get('armament-28')?></td>
                        <td><?=$langBase->get('armament-27')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($my_weapons as $key => $weapon)
                {
                    $i++;
                    $c = $i%2 ? 1 : 2;
                ?>
                    <tr class="c_<?=$c?> boxHandle">
                        <td class="center"><input type="radio" name="change_weapon" value="<?=$key?>"<?php if($key == Player::Data('weapon')) echo ' checked="checked"';?> /><?=$config['weapons'][$key]['name']?></td>
                        <td class="t_right"><?=View::AsPercent($my_weapons[$key]['training'], $config['weapon_max_traning'], 2)?> %</td>
                    </tr>
                <?php
                }
                ?>
                	<tr class="c_3 center">
                    	<td colspan="2"><input type="submit" value="<?=$langBase->get('armament-29')?>" /></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <?php
		}
		
		if ($my_weapon && $my_weapon_data['training'] < $config['weapon_max_traning'])
		{
			$timeleft = Player::Data('last_weapon_training')+$config['weapon_training_wait'] - time();
			
			if ($timeleft > 0)
			{
				echo $langBase->get('armament-30', array('-TIME-' => $timeleft));
			}
			else
			{
		?>
        <form method="post" action="">
        	<p class="center">
            	<input type="submit" name="weapon_train" value="Start training" />
            </p>
            <p class="center dark"><?=View::strTime($config['weapon_training_wait'])?> <?=$langBase->get('txt-04')?></p>
        </form>
        <?php
			}
		}
		?>
        <div class="hr big" style="margin: 10px 0 10px 0;"></div>
        <?php
		if ($availiable_weapon != 'nope')
		{
			if ($availiable_weapon == false && count($my_weapons) > 0)
			{
				echo $langBase->get('armament-31', array('-PERCENT-' => View::AsPercent($config['weapon_min_training_to_buy'], $config['weapon_max_traning'], 2)));
			}
			else
			{
				$weapon = $config['weapons'][$availiable_weapon];
		?>
        <form method="post" action="">
            <p><?=$langBase->get('armament-32')?></p>
            <dl class="dd_right" style="margin-top: 0;">
                <dt><?=$langBase->get('armament-28')?></dt>
                <dd><?=$weapon['name']?></dd>
                <dt><?=$langBase->get('txt-03')?></dt>
                <dd><?=View::CashFormat($weapon['price'])?> $</dd>
            </dl>
            <p class="clear center">
                <input type="submit" name="buy_weapon" value="<?=$langBase->get('txt-01')?>" />
            </p>
        </form>
        <?php
			}
		}
		?>
    </div>
</div>
<div class="clear"></div>
</div>