<div class="script_header">
	<img src="<?=$config['base_url']?>images/script_headers/killer.jpg" alt="" />
<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	/*
	 * Only missions?
	*/
	$only_missionObjects = false;
	
	$sql = $db->Query("SELECT id,to_find,length FROM `detective` WHERE `player`='".Player::Data('id')."' AND `ends`<='".time()."' AND `finished`='0'");
	while ($detective = $db->FetchArray($sql))
	{
		$sql = $db->Query("SELECT id,live,hospital_data FROM `[players]` WHERE `id`='".$detective['to_find']."'");
		$player = $db->FetchArray($sql);
		
		$no_find = false;
		
		$hospitalData = unserialize($player['hospital_data']);
		$hospital_timeleft = $hospitalData['added'] + $hospitalData['time_length'] - time();
		
		if ($hospital_timeleft > 0)
		{
			$no_find = true;
		}
		else
		{
			$sql = $db->Query("SELECT id,added,penalty FROM `jail` WHERE `player`='".$player['id']."' AND `added`+`penalty`>".time()." AND `active`='1'");
			$jail = $db->FetchArray($sql);
			$jailTime = $jail['added'] + $jail['penalty'] - time();
			
			if ($jailTime > 0)
			{
				$no_find = true;
			}
			else
			{
				$sql = $db->Query("SELECT id,last_session_ends FROM `bunker` WHERE `player`='".$player['id']."'");
				$bunker = $db->FetchArray($sql);
				
				if ($bunker['last_session_ends']-time() > 0)
				{
					$no_find = true;
				}
			}
		}
		
		if (rand(0, $config['detective_max_length']) <= $detective['length'] && $no_find === false)
		{
			$result = $player['live'];
		}
		else
		{
			$result = 0;
		}
		
		$db->Query("UPDATE `detective` SET `finished`='1', `result`='".$result."' WHERE `id`='".$detective['id']."'");
	}
	
	if (isset($_POST['end_search']))
	{
		$searches = $db->EscapeString($_POST['end_search']);
		
		foreach ($searches as $search)
		{
			$sql = $db->Query("SELECT id FROM `detective` WHERE `finished`='0' AND `player`='".Player::Data('id')."' AND `id`='".$search."'");
			$detective = $db->FetchArray($sql);
			
			$cancelled = 0;
			if ($detective['id'] != '')
			{
				$cancelled++;
				$db->Query("UPDATE `detective` SET `finished`='1' WHERE `id`='".$detective['id']."'");
			}
		}
		
		View::Message($langBase->get('asasin-38'), 1, true);
	}
	elseif (isset($_POST['buy_result']))
	{
		$result = $db->EscapeString($_POST['buy_result']);
		$sql = $db->Query("SELECT id,sale_price,player,to_find,result FROM `detective` WHERE `finished`='1' AND `result`>'0' AND `on_sale`='1' AND `id`='".$result."'");
		$result = $db->FetchArray($sql);
		
		if ($result['id'] == '')
		{
			echo View::Message($langBase->get('asasin-01'), 2);
		}
		elseif ($result['sale_price'] > Player::Data('cash'))
		{
			echo View::Message($langBase->get('err-01'), 2);
		}
		else
		{
			$db->Query("UPDATE `detective` SET `on_sale`='0', `sold_to`='".Player::Data('id')."' WHERE `id`='".$result['id']."'");
			$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$result['sale_price']."' WHERE `id`='".Player::Data('id')."'");
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$result['sale_price']."' WHERE `id`='".$result['player']."'");
			
			View::Message($langBase->get('asasin-02', array('-PLAYER-' => View::Player(array('id' => $result['to_find']), true), '-CITY-' => $config['places'][$result['result']][0])), 1, true);
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
	
	$player_mission_data = $player_mission->missions_data[$player_mission->current_mission];
	
	$player_mission_objects = array();
	
	if ($player_mission->current_mission == 2 && $player_mission_data['objects'][4]['completed'] != 1)
	{
		$player_mission_objects[] = array(
			'name' => 'Michele Navarra',
			'rank' => 6,
			'protection' => 2,
			'place' => $player_mission_data['objects'][3]['place'],
			'mission_obj' => 4
		);
	}
	
	foreach ($player_mission_objects as $key => $obj)
	{
		if ($obj['place'] != Player::Data('live'))
		{
			unset($player_mission_objects[$key]);
			continue;
		}
	}
?>
<div class="bg_c" style="width: 280px;">
    <h1 class="big"><?=$langBase->get('asasin-03')?></h1>
    <?php
    if (!(time() > $config['killtime_start'] && time() <= $config['killtime_stop']))
    {
        echo $langBase->get('asasin-04', array('-START-' => date('H:i', $config['killtime_start']), '-END-' => date('H:i', $config['killtime_stop']), '-WAIT-' => View::strTime($config['killtime_start'] - time(), 1, ', ')));
    }
    elseif (Player::Data('created')+$config['kill_protection'] > time() && Player::Data('kill_protection') == 1)
    {
        echo $langBase->get('asasin-06');
    }
    elseif (!$my_weapon)
    {
        echo $langBase->get('asasin-07');
    }
    else
    {
        if (isset($_POST['kill_player']))
        {
			$player_mission_obj_id = isset($_POST['mission_kill']) ? $db->EscapeString(View::NumbersOnly($_POST['mission_kill'])) : false;
			$player_mission_obj = $player_mission_objects[$player_mission_obj_id];
			
            if (!$player_mission_obj)
			{
				$player = $db->EscapeString($_POST['kill_player']);
				$sql = $db->Query("SELECT id,name,protection,rank,live,level,health,hospital_data,userid,created,online,cash,family,kill_protection FROM `[players]` WHERE `name`='".$player."' AND `level`<'3'");
				$player = $db->FetchArray($sql);
			}
			else
			{
				$player = array(
					'id' => 0,
					'rank' => $player_mission->current_mission_data['target']['rank'],
					'protection' => $player_mission->current_mission_data['target']['protection'],
					'cash' => rand($player_mission->current_mission_data['target']['cash'][0], $player_mission->current_mission_data['target']['cash'][1]) * 2,
					'health' => is_numeric($player_mission_data['objects'][4]['health']) ? $player_mission_data['objects'][4]['health'] : $config['max_health'],
					'kill_protection' => Player::Data('kill_protection'),
					'is_mission' => true
				);
			}
            
            $bullets = View::NumbersOnly($db->EscapeString($_POST['kill_bullets']));
            
            if ($player['id'] == '' && !$player_mission_obj)
            {
                View::Message($langBase->get('err-02'), 2, true);
            }
			elseif ($player['is_mission'] !== true && $only_missionObjects)
			{
				View::Message($langBase->get('asasin-08'), 2, true);
			}
            elseif ($player['id'] == Player::Data('id') && !$player_mission_obj)
            {
                View::Message($langBase->get('asasin-09'), 2, true);
            }
            elseif ($player['created']+$config['kill_protection'] > time() && $player['created'] && $player['kill_protection'] == 1)
            {
                View::Message($langBase->get('asasin-10'), 2, true);
            }
			elseif ($player['rank'] < $admin_config['asasin_rank']['value'])
            {
                View::Message($langBase->get('asasin-10'), 2, true);
            }
            elseif (($player['level'] <= 0 || $player['health'] <= 0) && !$player_mission_obj)
            {
                View::Message($langBase->get('asasin-11'), 2, true);
            }
            elseif ($bullets > Player::Data('bullets'))
            {
                View::Message($langBase->get('asasin-12'), 2, true);
            }
            elseif ($bullets < 10)
            {
                View::Message($langBase->get('asasin-13'), 2, true);
            }
            else
            {
                if (isset($_POST['do_kill']))
                {
                    $no_find = false;
                    
                    if (!$player_mission_obj)
					{
						if (Player::Data('live') != $player['live'])
						{
							$no_find = true;
						}
						else
						{
							$hospitalData = unserialize($player['hospital_data']);
							$hospital_timeleft = $hospitalData['added'] + $hospitalData['time_length'] - time();
							
							if ($hospital_timeleft > 0)
							{
								$no_find = true;
							}
							else
							{
								$sql = $db->Query("SELECT id,added,penalty FROM `jail` WHERE `player`='".$player['id']."' AND `added`+`penalty`>".time()." AND `active`='1'");
								$jail = $db->FetchArray($sql);
								$jailTime = $jail['added'] + $jail['penalty'] - time();
								
								if ($jailTime > 0)
								{
									$no_find = true;
								}
								else
								{
									$sql = $db->Query("SELECT id,last_session_ends FROM `bunker` WHERE `player`='".$player['id']."'");
									$bunker = $db->FetchArray($sql);
									
									if ($bunker['last_session_ends']-time() > 0)
									{
										$no_find = true;
									}
								}
							}
						}
					}
                    
					if (!$player_mission_obj && !empty($player['family']))
					{
						$sql = $db->Query("SELECT name FROM `[families]` WHERE `id`='".$player['family']."'");
						$family = $db->FetchArray($sql);
					}
					
                    if ($no_find === true)
                    {
                        $db->Query("INSERT INTO `player_kills` (`player`, `victim`, `place`, `weapon_used`, `bullets_used`, `result`, `time`, `victim_result_health`, `victim_protection`, `victim_rank`, `victim_family_name`)VALUES('".Player::Data('id')."', '".$player['id']."', '".Player::Data('place')."', '".Player::Data('weapon')."', '".$bullets."', 'no_find', '".time()."', '".$player['health']."', '".$player['protection']."', '".$player['rank']."', '".$family['name']."')");
                        
                        $new_wanted = rand($config['kill_wanted']['no_find'][0], $config['kill_wanted']['no_find'][1]);
                        
                        $db->Query("UPDATE `[players]` SET `bullets`=`bullets`-'".$bullets."', `wanted-level`=`wanted-level`+'".$new_wanted."' WHERE `id`='".Player::Data('id')."'");
                        
                        if (rand(0, $config['max_wanted-level']) <= Player::Data('wanted-level')+$new_wanted)
                        {
                            $jail_penalty = Accessories::SetInJail(Player::Data('id'), $new_wanted);
                            
                            $message = $langBase->get('asasin-14', array('-PLAYER-' => View::Player($player, true), '-TIME-' => $jail_penalty));
                        }
                        else
                        {
                            $message = $langBase->get('asasin-15', array('-PLAYER-' => View::Player($player, true)));
                        }
                        
                        View::Message($message, 2, true);
                    }
                    else
                    {
                        $victim_protection = $config['protections'][$player['protection']];
                        
                        $max_bullets = $config['ranks'][$player['rank']][4];
                        if ($protection)
                        {
                            $max_bullets = round($max_bullets + ($max_bullets/100 * $protection['bullets_increase']), 0);
                        }
                        
                        $weapon_effect = $my_weapon['bullets_decrease'];
                        $weapon_effect = round($weapon_effect/100 * View::AsPercent($my_weapons[Player::Data('weapon')]['training'], $config['weapon_max_traning'], 2), 2);
                        
                        $max_bullets = round($max_bullets - ($max_bullets/100 * $weapon_effect), 0);
                        
                        $minus_health = View::AsPercent($bullets, $max_bullets, 4) * 0.40;
                        $new_health = $player['health'] - $minus_health;
                        if ($new_health < 0) $new_health = 0;
                        if ($minus_health > $config['max_health']) $minus_health = $config['max_health'];
                        
                        $result = $new_health <= 0 ? 'success' : 'fail';
                        
                        if (!$player_mission_obj)
						{
							$sql = $db->Query("SELECT id,userid FROM `[players]` WHERE `live`='".Player::Data('live')."' AND `level`>'0' AND `level`<'3' AND `health`>'0' AND `userid`!='".Player::Data('userid')."' AND `userid`!='".$player['userid']."' AND `last_active`+'".$config['kill_witness_min_active']."' > '".time()."' ORDER BY RAND() LIMIT 1");
							$witness = $db->FetchArray($sql);
							
							if ($witness['id'] == '')
							{
								$witnessID = 0;
							}
							else
							{
								$witnessID = $witness['id'];
								
								Accessories::AddLogEvent($witness['id'], ($result == 'success' ? 19 : 20), array(
									'-MURDERER_NAME-' => Player::Data('name'),
									'-VICTIM_NAME-' => $player['name']
								), $witness['userid']);
							}
							
							$cash_received = $result == 'success' ? round($player['cash']/2, 0) : 0;
							
							$db->Query("INSERT INTO `player_kills` (`player`, `victim`, `place`, `weapon_used`, `bullets_used`, `result`, `time`, `witness`, `victim_protection`, `victim_result_health`, `cash_received`, `victim_rank`, `victim_family_name`)VALUES('".Player::Data('id')."', '".$player['id']."', '".Player::Data('place')."', '".Player::Data('weapon')."', '".$bullets."', '".$result."', '".time()."', '".$witnessID."', '".$player['protection']."', '".$new_health."', '".$cash_received."', '".$player['rank']."', '".$family['name']."')");
							
							$db->Query("UPDATE `[players]` SET `health`='".$new_health."'".($result == 'success' ? ", `cash`='".round($player['cash']/2, 0)."'" : '')." WHERE `id`='".$player['id']."'");
							
							if ($result == 'success')
							{
								Accessories::AddLogEvent($player['id'], 54, array(
									'-MURDERER_NAME-' => Player::Data('name')
								), $player['userid']);
								
								$killpoints = $config['kill_strengthPoints']['ranks'][$player['rank']];
								
								$sql = $db->Query("SELECT id FROM `businesses` WHERE `active`='1' AND '".$player['id']."' IN(`job_1`, `job_2`)");
								$killpoints += $config['kill_strengthPoints']['businessOwner'] * $db->GetNumRows($sql);
								
								$sql = $db->Query("SELECT id FROM `[families]` WHERE `active`='1' AND `boss`='".$player['id']."'");
								$killpoints += $config['kill_strengthPoints']['familyBoss'] * $db->GetNumRows($sql);
								
								$sql = $db->Query("SELECT id FROM `[families]` WHERE `active`='1' AND `underboss`='".$player['id']."'");
								$killpoints += $config['kill_strengthPoints']['underboss'] * $db->GetNumRows($sql);
								
								if (Player::FamilyData('id'))
								{
									$db->Query("UPDATE `[families]` SET `strength`=`strength`+'".$killpoints."', `".($result == 'success' ? 'player_kills' : 'player_kills_fail')."`=`".($result == 'success' ? 'player_kills' : 'player_kills_fail')."`+'1' WHERE `id`='".Player::FamilyData('id')."'");
								}
							}
							else
							{
								Accessories::AddLogEvent($player['id'], 52, array(
									'-LOST_HEALTH-' => View::AsPercent($minus_health, $config['max_health'], 2),
									'-MURDERER_NAME-' => Player::Data('name')
								), $player['userid']);
							}
						}
						else
						{
							$player_mission_data['objects'][$player_mission_obj['mission_obj']]['health'] = $new_health;
							$player_mission->missions_data[$player_mission->current_mission]['objects'][$player_mission_obj['mission_obj']]['health'] = $new_health;
							
							$player_mission->saveMissionData();
							
							if ($result == 'success')
							{
								$player_mission->completeObject($player_mission_obj['mission_obj']);
								$killpoints = $config['kill_strengthPoints']['ranks'][$player['rank']];
							}
						}
                        
                        $new_wanted = rand($config['kill_wanted'][$result][0], $config['kill_wanted'][$result][1]);
                        $rankpoints = rand($config['kill_rankpoints'][$result][0], $config['kill_rankpoints'][$result][1]);
                        
                        $db->Query("UPDATE `[players]` SET `bullets`=`bullets`-'".($result == 'fail' ? $bullets : ($player_mission_obj ? 0 : $bullets))."', `wanted-level`=`wanted-level`+'".$new_wanted."', `rankpoints`=`rankpoints`+'".$rankpoints."', `kills".($result == 'fail' ? '_failed' : '')."`=`kills".($result == 'fail' ? '_failed' : '')."`+'1'".($result == 'success' ? ", `cash`=`cash`+'".$cash_received."'" : '').", `killpoints`=`killpoints`+'".$killpoints."' WHERE `id`='".Player::Data('id')."'");
                        
                        if (rand(0, $config['max_wanted-level']) <= Player::Data('wanted-level')+$new_wanted)
                        {
                            $jail_penalty = Accessories::SetInJail(Player::Data('id'), $new_wanted);
                        }
                        
                        View::Message(($result == 'success' ? $langBase->get('asasin-16', array('-VICTIM-' => ($player_mission_obj ? $player_mission_obj['name'] : View::Player($player, true)), '-CASH-' => View::CashFormat($cash_received))) : $langBase->get('asasin-17', array('-VICTIM-' => ($player_mission_obj ? $player_mission_obj['name'] : View::Player($player, true)), '-LIFE-' => View::AsPercent($minus_health, $config['max_health'], 2)))) . (!empty($jail_penalty) ? $langBase->get('asasin-18', array('-TIME-' => $jail_penalty)) : ''), ($result == 'success' ? 1 : 2), true);
                    }
                }
        ?>
        <dl class="dd_right">
            <dt><?=$langBase->get('asasin-19')?></dt>
            <dd><?=($player_mission_obj ? $player_mission_obj['name'] : View::Player($player))?></dd>
            <dt><?=$langBase->get('asasin-20')?></dt>
            <dd><?=$config['ranks'][$player['rank']][0]?></dd>
            <dt><?=$langBase->get('armament-28')?></dt>
            <dd><?=$langBase->get('asasin-21', array('-WEAPON-' => $my_weapon['name'], '-PERCENT-' => View::AsPercent($my_weapons[Player::Data('weapon')]['training'], $config['weapon_max_traning'], 2)))?></dd>
            <dt><?=$langBase->get('ot-bullets')?></dt>
            <dd><?=View::CashFormat($bullets).' '.$langBase->get('asasin-22')?></dd>
            <dt><?=$langBase->get('txt-05')?></dt>
            <dd><a href="<?=$config['base_url']?>?side=harta&amp;sted=<?=Player::Data('live')?>"><?=$config['places'][Player::Data('live')][0]?></a></dd>
        </dl>
        <div class="clear"></div>
        <form method="post" action="">
            <input type="hidden" name="kill_player" value="<?=$player['name']?>" />
            <input type="hidden" name="kill_bullets" value="<?=$bullets?>" />
            <p class="center">
                <input type="submit" name="do_kill" value="<?=$langBase->get('asasin-23')?>" />
            </p>
        </form>
        <?php
            }
        }
        else
        {
    ?>
    <p><?=$langBase->get('asasin-24', array('-BULLETS-' => View::CashFormat(Player::Data('bullets'))))?></p>
    <form method="post" action="">
        <dl class="dd_right" style="margin-top: 0;">
            <dt><?=$langBase->get('txt-06')?></dt>
            <dd>
            	<input type="text" class="flat" name="kill_player" value="<?=$_POST['kill_player']?>" />
                <?php
				if (count($player_mission_objects) > 0)
				{
					echo '<br /><select name="mission_kill" style="margin-top: 5px; margin-bottom: 5px;"><option>'.$langBase->get('asasin-25').'</option>';
					
					foreach ($player_mission_objects as $key => $obj)
					{
						echo '<option value="' . $key . '">' . $obj['name'] . '</option>';
					}
					
					echo '</select>';
				}
				?>
            </dd>
            <dt><?=$langBase->get('asasin-26')?></dt>
            <dd><input type="text" class="flat" name="kill_bullets" value="<?=View::CashFormat(View::NumbersOnly($_POST['kill_bullets']))?>" /></dd>
        </dl>
        <p class="clear center">
            <input type="submit" value="<?=$langBase->get('asasin-27')?>" />
        </p>
    </form>
    <?php
        }
    }
    ?>
</div>
<div class="bg_c w400">
	<h1 class="big"><?=$langBase->get('asasin-05')?></h1>
    <?php
	if (isset($_GET['add']))
	{
		if (isset($_POST['add_cancel']))
		{
			header('Location: /game/?side=' . $_GET['side']);
			exit;
		}
		elseif (isset($_POST['add_player']))
		{
			$player = $db->EscapeString($_POST['add_player']);
			$sql = $db->Query("SELECT id,health,level,name FROM `[players]` WHERE `name`='".$player."' AND `level`<'3'");
			$player = $db->FetchArray($sql);
			
			$length = View::NumbersOnly($_POST['add_length']);
			$price = round($length * $config['detective_price_per_min'], 0);
			
			if ($player['id'] == '')
			{
				echo View::Message($langBase->get('err-02'), 2);
			}
			elseif ($player['level'] <= 0 || $player['health'] <= 0)
			{
				echo View::Message($langBase->get('asasin-11'), 2);
			}
			elseif ($length < $config['detective_min_length'])
			{
				echo View::Message($langBase->get('asasin-28', array('-TIME-' => $config['detective_min_length'])), 2);
			}
			elseif ($length > $config['detective_max_length'])
			{
				echo View::Message($langBase->get('asasin-29', array('-TIME-' => $config['detective_min_length'])), 2);
			}
			elseif ($price > Player::Data('cash'))
			{
				echo View::Message($langBase->get('err-01'));
			}
			elseif ($db->GetNumRows($db->Query("SELECT id FROM `detective` WHERE `finished`='0' AND `player`='".Player::Data('id')."' AND `to_find`='".$player['id']."' LIMIT 1")) > 0)
			{
				echo View::Message($langBase->get('asasin-30'), 2);
			}
			else
			{
				$db->Query("INSERT INTO `detective` (`player`, `started`, `ends`, `to_find`, `length`)VALUES('".Player::Data('id')."', '".time()."', '".(time() + ($length * 60))."', '".$player['id']."', '".$length."')");
				$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$price."' WHERE `id`='".Player::Data('id')."'");
				
				View::Message($langBase->get('asasin-31', array('-PLAYER' => View::Player($player, true), '-TIME-' => $length)), 1, true, '/game/?side=' . $_GET['side']);
			}
		}
	?>
    <div class="bg_c c_1 w200">
    	<h1 class="big"><?=$langBase->get('asasin-32')?></h1>
        <form method="post" action="">
        	<p><?=$langBase->get('asasin-33', array('-CASH-' => View::CashFormat($config['detective_price_per_min'])))?></p>
        	<dl class="dd_right">
            	<dt><?=$langBase->get('txt-06')?></dt>
                <dd><input type="text" name="add_player" class="flat" value="<?=$_POST['add_player']?>" /></dd>
                <dt><?=$langBase->get('txt-07')?></dt>
                <dd><input type="text" name="add_length" class="flat" value="<?=View::NumbersOnly($_POST['add_length']).' '.$langBase->get('txt-08')?>" /></dd>
            </dl>
            <p class="center clear">
            	<input type="submit" value="<?=$langBase->get('txt-09')?>" /> 
                <input type="submit" name="add_cancel" value="<?=$langBase->get('txt-10')?>" />
            </p>
        </form>
    </div>
    <?php
	}
	else
	{
		echo '<p class="center" style="margin: 15px 0 20px 0;"><a href="' . $config['base_url'] . '?side=' . $_GET['side'] . '&amp;add" class="button">&laquo; '.$langBase->get('asasin-39').'</a></p>';
	}
	
	$sql = $db->Query("SELECT id,started,ends,to_find,length FROM `detective` WHERE `finished`='0' AND `player`='".Player::Data('id')."' ORDER BY ends DESC");
	$detectives = $db->FetchArrayAll($sql);
	
	if (count($detectives) <= 0)
	{
		echo $langBase->get('asasin-34');
	}
	else
	{
	?>
    <form method="post" action="">
        <table class="table boxHandle">
            <thead>
                <tr>
                    <td><?=$langBase->get('txt-06')?></td>
                    <td><?=$langBase->get('txt-11')?></td>
                    <td><?=$langBase->get('txt-12')?></td>
                    <td><?=$langBase->get('txt-13')?></td>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($detectives as $detective)
            {
                $i++;
                $c = $i%2 ? 1 : 2;
				
				$progress = ($detective['ends'] - time()) / 60;
            ?>
                <tr class="c_<?=$c?> boxHandle">
                    <td><input type="checkbox" name="end_search[]" value="<?=$detective['id']?>" /><?=View::Player(array('id' => $detective['to_find']))?></td>
                    <td class="center"><?=(100 - View::AsPercent($progress, $detective['length'], 2))?> %</td>
                    <td class="t_right"><?=View::Time($detective['started'])?></td>
                    <td class="t_right"><?=View::Time($detective['ends'])?></td>
                </tr>
            <?php
            }
            ?>
                <tr class="c_3 center">
                    <td colspan="4"><input type="submit" value="<?=$langBase->get('txt-10')?>" /></td>
                </tr>
            </tbody>
        </table>
    </form>
    <?php
	}
	?>
</div>
<?php
if (isset($_GET['s_r']))
{
	$result = $db->EscapeString($_GET['s_r']);
	$sql = $db->Query("SELECT id,ends,to_find,result FROM `detective` WHERE `finished`='1' AND `result`>'0' AND `player`='".Player::Data('id')."' AND `on_sale`='0' AND `id`='".$result."'");
	$result = $db->FetchArray($sql);
	
	if ($result['id'] == '')
	{
		View::Message('Unknown Result', 2, true, '/game/?side=' . $_GET['side']);
	}
	
	if (isset($_POST['sell_price']))
	{
		$price = View::NumbersOnly($_POST['sell_price']);
		
		if ($price < 0)
		{
			echo View::Message($langBase->get('asasin-41'), 2);
		}
		else
		{
			$db->Query("UPDATE `detective` SET `on_sale`='1', `sale_price`='".$price."' WHERE `id`='".$result['id']."'");
			
			View::Message($langBase->get('asasin-40'), 1, true, '/game/?side=' . $_GET['side']);
		}
	}
?>
<div class="bg_c w250">
	<h1 class="big"><?=$langBase->get('asasin-35')?></h1>
    <form method="post" action="">
        <dl class="dd_right">
            <dt><?=$langBase->get('txt-06')?></dt>
            <dd><?=View::Player(array('id' => $result['to_find']))?></dd>
            <dt><?=$langBase->get('txt-05')?></dt>
            <dd><a href="<?=$config['base_url']?>?side=harta&amp;sted=<?=$result['result']?>"><?=$config['places'][$result['result']][0]?></a></dd>
            <dt><?=$langBase->get('txt-13')?></dt>
            <dd><?=View::Time($result['ends'])?></dd>
            <dt><?=$langBase->get('txt-03')?></dt>
            <dd><input type="text" name="sell_price" class="flat" value="<?=View::CashFormat(View::NumbersOnly($_POST['sell_price']))?> $" /></dd>
        </dl>
        <p class="center clear">
            <input type="submit" value="<?=$langBase->get('txt-14')?>" /> <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>" class="button">&laquo; <?=$langBase->get('txt-10')?></a>
        </p>
    </form>
</div>
<?php
}
?>
<div class="left" style="width: 300px; margin-left: 5px;">
	<div class="bg_c" style="width: 280px;">
    	<h1 class="big"><?=$langBase->get('asasin-36')?></h1>
        <?php
		$sql = $db->Query("SELECT id,ends,to_find,result FROM `detective` WHERE `finished`='1' AND `result`>'0' AND `player`='".Player::Data('id')."' AND `on_sale`='0' ORDER BY ends DESC LIMIT 15");
		$detectives = $db->FetchArrayAll($sql);
		
		if (count($detectives) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
		?>
        <form method="get" action="">
        	<input type="hidden" name="side" value="<?=$_GET['side']?>" />
            <table class="table boxHandle">
                <thead>
                    <tr>
                        <td><?=$langBase->get('txt-06')?></td>
                        <td><?=$langBase->get('txt-05')?></td>
                        <td><?=$langBase->get('txt-13')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
				$i = 0;
				
                foreach ($detectives as $detective)
                {
                    $i++;
                    $c = $i%2 ? 1 : 2;
                ?>
                    <tr class="c_<?=$c?> boxHandle">
                        <td><input type="radio" name="s_r" value="<?=$detective['id']?>" /><?=View::Player(array('id' => $detective['to_find']))?></td>
                        <td class="center"><a href="<?=$config['base_url']?>?side=harta&amp;sted=<?=$detective['result']?>"><?=$config['places'][$detective['result']][0]?></a></td>
                        <td class="t_right"><?=View::Time($detective['ends'])?></td>
                    </tr>
                <?php
                }
                ?>
                    <tr class="c_3 center">
                        <td colspan="3"><input type="submit" value="<?=$langBase->get('txt-15')?>" /></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <?php
		}
		?>
    </div>
</div>
<div class="left" style="width: 300px; margin-left: 17px;">
	<div class="bg_c" style="width: 290px;">
    	<h1 class="big"><?=$langBase->get('asasin-37')?></h1>
		<?php
		$sql = $db->Query("SELECT id,ends,to_find,sale_price FROM `detective` WHERE `finished`='1' AND `result`>'0' AND `on_sale`='1' ORDER BY ends DESC LIMIT 15");
		$detectives = $db->FetchArrayAll($sql);
		
		if (count($detectives) <= 0)
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
                        <td><?=$langBase->get('txt-06')?></td>
                        <td><?=$langBase->get('txt-03')?></td>
                        <td><?=$langBase->get('txt-13')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
				$i = 0;
				
                foreach ($detectives as $detective)
                {
					if($db->GetNumRows($db->Query("SELECT id FROM `[players]` WHERE `id`='".$detective['to_find']."' AND `health`='0' LIMIT 1")) > 0){
						$db->Query("UPDATE `detective` SET `on_sale`='0' WHERE `to_find`='".$detective['to_find']."' AND `result`>'0' AND `on_sale`='1'");
					}
				
                    $i++;
                    $c = $i%2 ? 1 : 2;
                ?>
                    <tr class="c_<?=$c?> boxHandle">
                        <td><input type="radio" name="buy_result" value="<?=$detective['id']?>" /><?=View::Player(array('id' => $detective['to_find']))?></td>
                        <td class="center"><?=View::CashFormat($detective['sale_price'])?> $</td>
                        <td class="t_right"><?=View::Time($detective['ends'])?></td>
                    </tr>
                <?php
                }
                ?>
                    <tr class="c_3 center">
                        <td colspan="3"><input type="submit" value="<?=$langBase->get('txt-01')?>" /></td>
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
</div>