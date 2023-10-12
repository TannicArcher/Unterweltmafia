<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
?>
<div class="script_header">
	<img src="<?=$config['base_url']?>images/script_headers/jail.jpg" alt="" />
	<div class="hr"></div>
</div>
<?php
	show_messages();
	
	$db->Query("UPDATE `jail` SET `active`='0' WHERE `active`='1' AND `added`+`penalty` < '".time()."'");
	
	$player_mission_data = $player_mission->missions_data[$player_mission->current_mission];
	
	$sql = "SELECT id,player,penalty,added,breakout_reward FROM `jail` WHERE `active`='1' ORDER BY id DESC";
	$pagination = new Pagination($sql, 20, 'p');
	$pagination_links = $pagination->GetPageLinks();
	$prisoners = $pagination->GetSQLRows();
	
	$player_missionObjects = array();
	if ($player_mission->current_mission == 3 && $player_mission_data['started'] == 1)
	{
		if ($player_mission_data['objects'][1]['completed'] != 1 && Player::Data('live') == 1)
		{
			$player_missionObjects['mission_1'] = array(
				'id' => 'mission',
				'object_id' => 1,
				'player' => 'Jos&eacute; Macaruno',
				'breakout_reward' => 0
			);
		}
		if ($player_mission_data['objects'][2]['completed'] != 1 && Player::Data('live') == 4)
		{
			$player_missionObjects['mission_2'] = array(
				'id' => 'mission',
				'object_id' => 2,
				'player' => 'Mario De Aramengo',
				'breakout_reward' => 0
			);
		}
	}
	
	if (count($player_missionObjects) > 0)
		$prisoners = array_merge($player_missionObjects, $prisoners);
	
	if (count($prisoners) <= 0)
	{
		echo View::Message($langBase->get('jail-01'), 2);
	}
	else
	{
		$sql = $db->Query("SELECT id,added,penalty,breakout_reward FROM `jail` WHERE `player`='".Player::Data('id')."' AND `active`='1'");
		$jail = $db->FetchArray($sql);
		
		if ($jail['id'] != '')
		{
			$timeleft = $jail['added']+$jail['penalty'] - time();
			
			if (isset($_POST['points_buyout']))
			{
				if (Player::Data('points') < $config['jail_points_buyout_sum'])
				{
					echo View::Message($langBase->get('err-09'), 2);
				}
				else
				{
					$db->Query("UPDATE `jail` SET `active`='0' WHERE `id`='".$jail['id']."'");
					$db->Query("UPDATE `[players]` SET `points`=`points`-'".$config['jail_points_buyout_sum']."' WHERE `id`='".Player::Data('id')."'");
					
					View::Message($langBase->get('jail-02', array('-COINS-' => $config['jail_points_buyout_sum'])), 1, true);
				}
			}
			elseif (isset($_POST['breakout_reward']))
			{
				$sum = View::NumbersOnly($db->EscapeString($_POST['breakout_reward']));
				
				if ($sum < $config['jail_min_breakout_reward'] && $sum != 0)
				{
					echo View::Message($langBase->get('jail-03', array('-CASH-' => View::CashFormat($config['jail_min_breakout_reward']))), 2);
				}
				elseif ($sum > Player::Data('cash'))
				{
					echo View::Message($langBase->get('err-01'), 2);
				}
				else
				{
					$db->Query("UPDATE `jail` SET `breakout_reward`='".$sum."' WHERE `id`='".$jail['id']."'");
					
					View::Message(($sum == 0 ? $langBase->get('jail-04') : $langBase->get('jail-05')), 1, true);
				}
			}
?>
<script type="text/javascript">
	function confirmBuyout() {return confirm('<?=$langBase->get('jail-26', array('-COINS-' => $config['jail_points_buyout_sum']))?>');}
</script>
<div class="bg_c">
	<h1 class="left"><?=$langBase->get('jail-00')?></h1>
    <div class="clear"></div>
    <h2><?=$langBase->get('jail-06', array('-TIME-' => $timeleft))?></h2>
    <form method="post" action="" onsubmit="return confirmBuyout();"><p class="center"><input type="submit" name="points_buyout" value="<?=$langBase->get('jail-07')?> (<?=$config['jail_points_buyout_sum']?> <?=$langBase->get('ot-coins')?>)" /></p></form>
    <div class="hr big" style="margin: 10px 0 10px 0;"></div>
    <form method="post" action="">
    	<dl class="dd_right" style="margin: 0; padding-top: 5px;">
        	<dt><?=$langBase->get('jail-08')?></dt>
            <dd><input type="text" name="breakout_reward" class="flat" value="<?=(View::CashFormat((isset($_POST['breakout_reward']) ? $_POST['breakout_reward'] : $jail['breakout_reward'])))?> $" /></dd>
        </dl>
        <p class="center clear">
        	<input type="submit" value="<?=$langBase->get('jail-09')?>" />
        </p>
    </form>
</div>
<?php
		}
		
		if (isset($_POST['prisoner']))
		{
			$prisoner = $db->EscapeString($_POST['prisoner']);
			
			if (strstr($prisoner, 'mission'))
			{
				$prisoner = $prisoners[$prisoner];
			}
			else
			{
				$sql = $db->Query("SELECT id,player,breakout_reward,added,penalty FROM `jail` WHERE `id`='$prisoner' AND `added`+`penalty` > '".time()."' AND `active`='1'");
				$prisoner = $db->FetchArray($sql);
			}
			
			$timeleft = Player::Data('jail_breakout_last')+$config['jail_breakout_latency'] - time();
			
			if ($timeleft > 0)
			{
				echo View::Message($langBase->get('jail-10', array('-TIME-' => $timeleft)), 2, true);
			}
			elseif ($prisoner['id'] == '')
			{
				echo View::Message($langBase->get('jail-11'), 2, true);
			}
			elseif ($timeleft > 0)
			{
				echo View::Message($langBase->get('jail-12'), 2, true);
			}
			elseif ($prisoner['player'] == Player::Data('id'))
			{
				echo View::Message($langBase->get('jail-13'), 2, true);
			}
			else
			{
				$progress = $prisoner['added']+($prisoner['penalty']/2) - time();
				$progress = 100 - View::AsPercent($progress, $prisoner['penalty'], 2);
				$chance = Player::Data('jail_breakout_chance') - (Player::Data('wanted-level') / 2);
				$chance = ($chance/100) * $progress;
				if($chance < 0) $chance = 0;
				if($chance > 250) $chance = 250;
				
				if (rand(0, $config['jail_max_breakout_chance']) <= $chance)
				{
					if ($prisoner['id'] != 'mission')
					{
						$sql = $db->Query("SELECT id,userid,cash,jail_stats,show_jail_logevents FROM `[players]` WHERE `id`='".$prisoner['player']."'");
						$player = $db->FetchArray($sql);
					}
					
					$reward = $prisoner['breakout_reward'] > $player['cash'] ? $player['cash'] : $prisoner['breakout_reward'];
					$rankpoints = rand(14,20);
					$wanted = Player::Data('wanted-level') + rand(22,32);
					$wanted = $wanted > $config['max_wanted-level'] ? $config['max_wanted-level'] : $wanted;
					$newchance = Player::Data('jail_breakout_chance') + rand(2,6);
					$newchance = $newchance > $config['jail_max_breakout_chance']-50 ? $config['jail_max_breakout_chance']-50 : $newchance;
					
					$stats = unserialize(Player::Data('jail_stats'));
					$stats['breakouts_earned'] += $reward;
					$stats['breakouts_successed']++;
					$stats = serialize($stats);
					
					if ($prisoner['id'] == 'mission')
					{
						$player_mission->completeObject($prisoner['object_id']);
					}
					else
					{
						$db->Query("UPDATE `jail` SET `active`='0' WHERE `id`='".$prisoner['id']."'");
					}
					
					$db->Query("UPDATE `[players]` SET `cash`=`cash`+'$reward', `rankpoints`=`rankpoints`+'$rankpoints', `wanted-level`='$wanted', `jail_breakout_chance`='$newchance', `jail_stats`='$stats', `jail_breakout_last`='".time()."' WHERE `id`='".Player::Data('id')."'");
					
					$prisonerStats = unserialize($player['jail_stats']);
					$prisonerStats['breakouts_used'] += $reward;
					if ($player['id'] != '')
						$db->Query("UPDATE `[players]` SET `cash`=`cash`-'$reward', `jail_stats`='".serialize($prisonerStats)."' WHERE `id`='".$prisoner['player']."'");
					
					Accessories::AddToLog(Player::Data('id'), array('breakout_player' => $player['id'], 'result' => 'success', 'reward' => $reward));
					if ($player['show_jail_logevents'] == 1 && $player['id'] != 'mission')
					{
						Accessories::AddLogEvent($player['id'], 24, array(
							'-PLAYER_NAME-' => Player::Data('name'),
							'-REWARD-' => View::CashFormat($reward)
						), $player['userid']);
					}
					
					$message = $langBase->get('jail-14', array('-PLAYER-' => ($prisoner['id'] == 'mission' ? $prisoner['player'] : View::Player(array('id' => $prisoner['player']))), '-CASH-' => View::CashFormat($reward)));
					$messageType = 1;
					
					if ($player_mission->current_mission == 1)
					{
						if ($player_mission_data['objects'][4]['completed'] != 1)
						{
							$players = $player_mission_data['objects'][4]['broken_out_players'];
							if (!in_array($prisoner['player'], $players))
							{
								$players[] = $prisoner['player'];
								$player_mission_data['objects'][4]['broken_out_players'] = $players;
								$player_mission->missions_data[$player_mission->current_mission]['objects'][4]['broken_out_players'] = $players;
								
								$player_mission->saveMissionData();
							}
							
							if (count($players) >= 5)
							{
								$player_mission->completeObject(4);
							}
						}
					}
					
					if (in_array(5, $player_mission->active_minimissions))
					{
						$player_mission->minimissions[5]['data']['num']++;
						$player_mission->miniMissions_save();
						
						if ($player_mission->minimissions[5]['data']['num'] >= 20)
						{
							$player_mission->miniMission_success(5);
						}
					}
				}
				else
				{
					$wanted = rand(18,26);
					$newchance = Player::Data('jail_breakout_chance') + rand(1,4);
					$newchance = $newchance > $config['jail_max_breakout_chance']-50 ? $config['jail_max_breakout_chance']-50 : $newchance;
					
					$stats = unserialize(Player::Data('jail_stats'));
					$stats['breakouts_failed']++;
					$stats = serialize($stats);
					
					$db->Query("UPDATE `[players]` SET `jail_breakout_chance`='$newchance', `jail_stats`='$stats', `jail_breakout_last`='".time()."', `wanted-level`=`wanted-level`+'".$wanted."' WHERE `id`='".Player::Data('id')."'");
					
					$log_data = array('breakout_player' => $prisoner['player'], 'result' => 'fail');
					
					if ( rand(0, $config['max_wanted-level']) <= Player::Data('wanted-level') )
					{
						$penalty = Accessories::SetInJail(Player::Data('id'), $wanted);
						$log_data['jail_penalty'] = $penalty;
						
						$message = $langBase->get('jail-15', array('-PLAYER-' => ($prisoner['id'] == 'mission' ? $prisoner['player'] : View::Player(array('id' => $prisoner['player']))), '-TIME-' => View::strTime($penalty, 1, ', ')));
						
					}
					else
					{
						$message = $langBase->get('jail-16', array('-PLAYER-' => ($prisoner['id'] == 'mission' ? $prisoner['player'] : View::Player(array('id' => $prisoner['player'])))));
					}
					
					
					Accessories::AddToLog(Player::Data('id'), $log_data);
					
					$messageType = 2;
				}
				
				$abData = unserialize(Player::Data('antibot_data'));
				$abData['fengsel']--;
				if ($abData['fengsel'] <= 0)
				{
					$abData['fengsel'] = rand($config['antibot_next_range']['jail_breakout'][0], $config['antibot_next_range']['jail_breakout'][1]);
					$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
					
					Accessories::CreateAntibotSession(Player::Data('id'), $db->EscapeString($_GET['side']));
					
					View::Message($message, $messageType, true);
				}
				$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
				
				View::Message($message, $messageType, true);
			}
		}
?>
<form method="post" action="">
	
    <table class="table boxHandle center">
    	<thead>
        	<tr>
            	<td><?=$langBase->get('txt-06')?></td>
                <td><?=$langBase->get('jail-17')?></td>
                <td><?=$langBase->get('jail-18')?></td>
                <td><?=$langBase->get('jail-19')?></td>
            </tr>
        </thead>
        <tbody>
        	<?php
				foreach ($prisoners as $prisoner)
				{
					$i++;
					$c = $i%2 ? 1 : 2;
					
					$progress = $prisoner['added']+($prisoner['penalty']/2) - time();
					$progress = 100 - View::AsPercent($progress, $prisoner['penalty'], 2);
					$chance = Player::Data('jail_breakout_chance') - (Player::Data('wanted-level') / 2);
					$chance = ($chance/100) * $progress;
					if($chance < 0) $chance = 0;
					if($chance > 250) $chance = 250;
			?>
            <tr class="boxHandle c_<?=$c?>">
            	<td><input type="radio" name="prisoner" value="<?=($prisoner['id'] == 'mission' ? 'mission_' . $prisoner['object_id'] : $prisoner['id'])?>"<?php if ($prisoner['player'] == Player::Data('id') || $timeleft) echo ' disabled="disabled"'; ?> /><?=($prisoner['id'] == 'mission' ? $prisoner['player'] : View::Player(array('id' => $prisoner['player'])))?></td>
                <td class="center"><?=View::CashFormat($prisoner['breakout_reward'])?> $</td>
                <td class="center"><div class="small_progressbar w150"><div class="progress" style="width: <?=View::AsPercent($chance, $config['jail_max_breakout_chance'], 0)?>%;"><p><?=View::AsPercent($chance, $config['jail_max_breakout_chance'], 2)?> %</p></div></div></td>
                <td class="t_right"><?=($prisoner['id'] == 'mission' ? 'Lifetime' : View::strTime($prisoner['added']+$prisoner['penalty']-time()))?></td>
            </tr>
            <?php
				}
			?>
            <tr class="c_3">
            	<td colspan="5"><?=$pagination_links?></td>
            </tr>
        </tbody>
    </table>
    <p class="center">
    	<input type="submit" value="<?=$langBase->get('jail-20')?>" />
    </p>
</form>
<?php
	}
?>