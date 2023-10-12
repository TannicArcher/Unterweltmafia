<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$sql = $db->Query("SELECT * FROM `car_theft` WHERE `playerid`='".Player::Data('id')."'");
	$car_theft = $db->FetchArray($sql);
	
	if ($car_theft['id'] == '')
	{
		$sql = $db->Query("INSERT INTO `car_theft` (`playerid`)VALUES('".Player::Data('id')."')");
		
		header('Location: /game/?side=' . $_GET['side']);
		exit;
	}
	
	$stats = unserialize($car_theft['stats']);
	$chances = unserialize($car_theft['chances']);
	
	if (!isset($_SESSION['MZ_car_theft_hash']))
	{
		$_SESSION['MZ_car_theft_hash'] = 'MZ_' . substr(sha1(uniqid(rand())), 0, 10);
	}
	
	$waitTime = $car_theft['last_time'] + $car_theft['latency'] - time();
	
	if (isset($_POST['car_theft']))
	{
		if ($_POST['hash'] !== substr($_SESSION['MZ_car_theft_hash'], 3))
		{
			unset($_SESSION['MZ_car_theft_hash']);
			View::Message('ERROR!', 2, true);
			exit;
		}
		
		$theftID = $db->EscapeString($_POST['car_theft']);
		$theft = $config['car_thefts'][$theftID];
		
		if (!$theft)
		{
			View::Message($langBase->get('cars-01'), 2, true);
		}
		elseif (Player::Data('rank') < $theft['min_rank'])
		{
			View::Message($langBase->get('cars-02', array('-RANK-' => $config['ranks'][$theft['min_rank']][0])), 2, true);
		}
		elseif ($waitTime > 0)
		{
			View::Message($langBase->get('cars-03', array('-TIME-' => $waitTime)), 2, true);
		}
		else
		{
			unset($_SESSION['MZ_car_theft_hash']);
			
			$db->Query("UPDATE `car_theft` SET `last_time`='".time()."', `last`='".$theftID."' WHERE `id`='".$car_theft['id']."'");
			
			$chance = $chances[$theftID] - (Player::Data('wanted-level') / 3);
			if (rand(0, $theft['max_chance'][0]) <= $chance)
			{
				$rankPoints = rand($theft['rankpoints']['success'][0], $theft['rankpoints']['success'][1]);
				
				$wanted_level  = Player::Data('wanted-level') + rand($theft['wanted_level']['success'][0], $theft['wanted_level']['success'][1]);
				$wanted_level  = $wanted_level > $config['max_wanted-level'] ? $config['max_wanted-level'] : $wanted_level;
				
				$db->Query("UPDATE `[players]` SET `rankpoints`=`rankpoints`+'".$rankPoints."', `wanted-level`='".$wanted_level."' WHERE `id`='".Player::Data('id')."'");
				
				$newChance = $chances[$theftID] + rand($theft['new_chance'][0], $theft['new_chance'][1]);
				$newChance = $newChance > $theft['max_chance'][1] ? $theft['max_chance'][1] : $newChance;
				$chances[$theftID] = $newChance;
				
				$stats['total_success']++;
				
				$db->Query("UPDATE `car_theft` SET `latency`='".$theft['wait_time']."', `chances`='".serialize($chances)."', `stats`='".serialize($stats)."' WHERE `id`='".$car_theft['id']."'");
				
				$cars = array();
				foreach ($config['cars'] as $key => $car)
				{
					if (in_array($key, $theft['cars']))
					{
						$cars[$key] = $car;
					}
				}
				
				$carID = array_rand($cars);
				$car = $cars[$carID];
				$damage = rand($theft['car_damage'][0], $theft['car_damage'][1]);
				
				$db->Query("INSERT INTO `cars` (`owner`, `car_type`, `damage`, `horsepowers`, `acquired`, `place`)VALUES('".Player::Data('id')."', '".$carID."', '".$damage."', '".$car['default_horsepowers']."', '".time()."', '".Player::Data('live')."')");
				
				Accessories::AddToLog(Player::Data('id'), array('result' => 'success', 'car_id' => $carID, 'latency' => abs($waitTime)));
				
				$message = $langBase->get('cars-04', array('-CAR-' => $car['brand'].' '.$car['model'], '-POWER-' => View::CashFormat($car['default_horsepowers']), '-DAMAGE-' => View::AsPercent($damage, $config['car_max_damage'], 2)));
				$messageType = 1;
				
				/*
				 * Misiuni
				*/
				$player_mission_data = $player_mission->missions_data[$player_mission->current_mission];
				
				if ($player_mission->current_mission == 1 && Player::Data('live') == 1 && $player_mission_data['started'] == 1)
				{
					if ($player_mission_data['objects'][2]['completed'] != 1)
					{
						$num = $player_mission_data['objects'][2]['num_stolen'] + 1;
						$player_mission_data['objects'][2]['num_stolen'] = $num;
						$player_mission->missions_data[$player_mission->current_mission]['objects'][2]['num_stolen'] = $num;
						
						$player_mission->saveMissionData();
						
						if ($num >= 10)
						{
							$player_mission->completeObject(2);
						}
					}
				}
				elseif ($player_mission->current_mission == 3 && $player_mission_data['started'] == 1 && $carID == 8)
				{
					if ($player_mission_data['objects'][0]['completed'] != 1)
					{
						$num = $player_mission_data['objects'][0]['num_stolen'] + 1;
						$player_mission_data['objects'][0]['num_stolen'] = $num;
						$player_mission->missions_data[$player_mission->current_mission]['objects'][0]['num_stolen'] = $num;
						
						$player_mission->saveMissionData();
						
						if ($num >= 3)
						{
							$player_mission->completeObject(0);
						}
					}
				}
				elseif ($player_mission->current_mission == 4 && $player_mission_data['started'] == 1 && $carID == 9)
				{
					if ($player_mission_data['objects'][3]['completed'] != 1)
					{
						$num = $player_mission_data['objects'][3]['num_stolen'] + 1;
						$player_mission_data['objects'][3]['num_stolen'] = $num;
						$player_mission->missions_data[$player_mission->current_mission]['objects'][3]['num_stolen'] = $num;
						
						$player_mission->saveMissionData();
						
						if ($num >= 5)
						{
							$player_mission->completeObject(3);
						}
					}
				}
				elseif ($player_mission->current_mission == 5 && $player_mission_data['started'] == 1)
				{
					if ($player_mission_data['objects'][0]['completed'] != 1 && $carID == 9)
					{
						$num = $player_mission_data['objects'][0]['num_stolen'] + 1;
						$player_mission_data['objects'][0]['num_stolen'] = $num;
						$player_mission->missions_data[$player_mission->current_mission]['objects'][0]['num_stolen'] = $num;
						$player_mission->saveMissionData();
						
						if ($num >= 5)
						{
							$player_mission->completeObject(0);
						}
					}
					else if ($player_mission_data['objects'][1]['completed'] != 1 && $carID == 11)
					{
						$num = $player_mission_data['objects'][1]['num_stolen'] + 1;
						$player_mission_data['objects'][1]['num_stolen'] = $num;
						$player_mission->missions_data[$player_mission->current_mission]['objects'][1]['num_stolen'] = $num;
						$player_mission->saveMissionData();
						
						if ($num >= 5)
						{
							$player_mission->completeObject(1);
						}
					}
					else if ($player_mission_data['objects'][2]['completed'] != 1 && $carID == 14)
					{
						$num = $player_mission_data['objects'][2]['num_stolen'] + 1;
						$player_mission_data['objects'][2]['num_stolen'] = $num;
						$player_mission->missions_data[$player_mission->current_mission]['objects'][2]['num_stolen'] = $num;
						$player_mission->saveMissionData();
						
						if ($num >= 5)
						{
							$player_mission->completeObject(2);
						}
					}
					else if ($player_mission_data['objects'][3]['completed'] != 1 && $carID == 15)
					{
						$num = $player_mission_data['objects'][3]['num_stolen'] + 1;
						$player_mission_data['objects'][3]['num_stolen'] = $num;
						$player_mission->missions_data[$player_mission->current_mission]['objects'][3]['num_stolen'] = $num;
						$player_mission->saveMissionData();
						
						if ($num >= 5)
						{
							$player_mission->completeObject(3);
						}
					}
				}
				elseif ($player_mission->current_mission == 7 && $player_mission_data['started'] == 1)
				{
					if ($player_mission_data['objects'][3]['completed'] != 1 && $carID == 9)
					{
						$num = $player_mission_data['objects'][3]['num_stolen'] + 1;
						$player_mission_data['objects'][3]['num_stolen'] = $num;
						$player_mission->missions_data[$player_mission->current_mission]['objects'][3]['num_stolen'] = $num;
						$player_mission->saveMissionData();
						
						if ($num >= 15)
						{
							$player_mission->completeObject(3);
						}
					}
					else if ($player_mission_data['objects'][4]['completed'] != 1 && $carID == 11)
					{
						$num = $player_mission_data['objects'][4]['num_stolen'] + 1;
						$player_mission_data['objects'][4]['num_stolen'] = $num;
						$player_mission->missions_data[$player_mission->current_mission]['objects'][4]['num_stolen'] = $num;
						$player_mission->saveMissionData();
						
						if ($num >= 5)
						{
							$player_mission->completeObject(4);
						}
					}
				}
				
				/*
				 * Misiuni Scurte
				*/
				if (in_array(1, $player_mission->active_minimissions))
				{
					$player_mission->minimissions[1]['data']['num_stolen']++;
					$player_mission->miniMissions_save();
					
					if ($player_mission->minimissions[1]['data']['num_stolen'] >= 5)
					{
						$player_mission->miniMission_success(1);
					}
				}
			}
			else
			{
				$rankPoints = rand($theft['rankpoints']['fail'][0], $theft['rankpoints']['fail'][1]);
				$wanted_level  = rand($theft['wanted_level']['fail'][0], $theft['wanted_level']['fail'][1]);
				
				$db->Query("UPDATE `[players]` SET `rankpoints`=`rankpoints`+'".$rankPoints."' WHERE `id`='".Player::Data('id')."'");
				
				$newChance = $chances[$theftID] + rand($theft['new_chance'][0], $theft['new_chance'][1]);
				$newChance = $newChance > $theft['max_chance'][1] ? $theft['max_chance'][1] : $newChance;
				$chances[$theftID] = $newChance;
				
				$stats['total_failed']++;
				
				$db->Query("UPDATE `car_theft` SET `latency`='".$theft['wait_time']."', `chances`='".serialize($chances)."', `stats`='".serialize($stats)."' WHERE `id`='".$car_theft['id']."'");
				$db->Query("UPDATE `[players]` SET `wanted-level`=`wanted-level`+'".$wanted_level."' WHERE `id`='".Player::Data('id')."'");
				
				Accessories::AddToLog(Player::Data('id'), array('result' => 'fail', 'latency' => abs($waitTime)));
				
				if (rand(0, $config['max_wanted-level']) <= Player::Data('wanted-level')+$wanted_level)
				{
					$penalty = Accessories::SetInJail(Player::Data('id'), $wanted_level);
					
					$log_data['jail_penalty'] = $penalty;
					
					$message = $langBase->get('cars-05', array('-TIME-' => $penalty));
				}
				else
				{
					$message = $langBase->get('cars-06');
				}
				$messageType = 2;
			}
			
			$abData = unserialize(Player::Data('antibot_data'));
			$abData['car_theft']--;
			if ($abData['car_theft'] <= 0)
			{
				$abData['car_theft'] = rand($config['antibot_next_range']['car_theft'][0], $config['antibot_next_range']['car_theft'][1]);
				$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
				
				Accessories::CreateAntibotSession(Player::Data('id'), $db->EscapeString($_GET['side']));
				
				View::Message($message, $messageType, true);
			}
			$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
			
			View::Message($message, $messageType, true);
		}
	}
?>
<form method="post" action="" id="carTheft_form">
	<input type="hidden" name="car_theft" id="car_theft" />
	<input type="hidden" name="hash" value="<?php echo substr($_SESSION['MZ_car_theft_hash'], 3); ?>" />
	<div class="brekk_container">
        <div class="brekk_top">
            <div class="header car_theft"></div>
            <div class="infobox"><?=($waitTime > 0 ? $langBase->get('cars-03', array('-TIME-' => $waitTime)) : $langBase->get('cars-10'))?></div>
            <a href="<?=$config['base_url']?>?side=garaj" class="stats_link"><?=$langBase->get('function-garage')?></a>
        </div>
        <div class="hr"></div>
        <?php
		show_messages();
		
		foreach ($config['car_thefts'] as $key => $theft)
		{
			$chance = View::AsPercent(($chances[$key] - (Player::Data('wanted-level') / 3)), $theft['max_chance'][0], 2);
			$chance = $chance < 0 ? 0 : $chance;
		?>
        <div class="brekk_boks<?php if($key == $car_theft['last']){ echo ' last_bg'; }?>">
            <?php if(Player::Data('rank') < $theft['min_rank']):?><div class="brekk_overlay"><p class="text"><?=$langBase->get('cars-02', array('-RANK-' => $config['ranks'][$theft['min_rank']][0]))?></p></div><?php endif;?>
            <p class="icon"><img src="<?=$config['base_url']?>images/brekk/icon_5.png" alt="" /></p>
			<p class="info">
                <span><FONT SIZE="2"><?=$theft['title']?></font></span><br />
                <?=View::strTime($theft['wait_time'])?> <?=$langBase->get('cars-07')?> <span class="sep">|</span> <b><?=$chance?> %</b> <?=$langBase->get('cars-08')?>
            </p>
            <a href="#" class="submit" onclick="$('car_theft').set('value', '<?=$key?>'); $('carTheft_form').submit(); return false;"><?=$langBase->get('cars-09')?></a>
        </div>
        <?php
		}
		?>
    </div>
</form>
<div class="bg_c" style="width: 250px;">
    <h1 class="big"><?=$langBase->get('ot-stats')?></h1>
    <p><?=$langBase->get('cars-11', array('-TOTAL-' => View::CashFormat($stats['total_success'] + $stats['total_failed']), '-SUCCES-' => View::CashFormat($stats['total_success']), '-ESEC-' => View::CashFormat($stats['total_failed'])))?></p>
</div>