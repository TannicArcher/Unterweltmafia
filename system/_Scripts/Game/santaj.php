<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
?>
<div class="script_header"><img src="<?=$config['base_url']?>images/script_headers/utpressing.jpg" alt="" /></div>
<?php
	show_messages();
	
	if (Player::Data('rank') >= $config['blackmail_min_rank'])
	{
		$sql = $db->Query("SELECT * FROM `blackmail` WHERE `playerid`='".Player::Data('id')."'");
		$blackmail = $db->FetchArray($sql);
		
		if ($blackmail['id'] == '')
		{
			$sql = $db->Query("INSERT INTO `blackmail` (`playerid`)VALUES('".Player::Data('id')."')");
			
			header('Location: /game/?side=' . $_GET['side']);
			exit;
		}
		
		$stats = unserialize($blackmail['stats']);
		$made = unserialize($blackmail['blackmails_made']);
		$got = unserialize($blackmail['blackmails_got']);
		
		if (!isset($_SESSION['MZ_blackmail_hash']))
		{
			$_SESSION['MZ_blackmail_hash'] = 'MZ_' . substr(sha1(uniqid(rand())), 0, 10);
		}
	}
	
	$ranks_to_blackmail = array();
	
	foreach ($config['ranks'] as $key => $rank)
	{
		if ($key <= Player::Data('rank') && $key >= $config['blackmail_min_rank'])
		{
			$ranks_to_blackmail[$key] = $rank;
		}
	}
	
	$waitTime = $blackmail['last_time'] + $blackmail['latency'] - time();
	
	if (isset($_POST['rank_blackmail']) && $waitTime <= 0)
	{
		if ($_POST['hash'] !== substr($_SESSION['MZ_blackmail_hash'], 3))
		{
			unset($_SESSION['MZ_blackmail_hash']);
			View::Message('ERROR', 2, true);
			exit;
		}
		
		$rankID = $db->EscapeString($_POST['rank_blackmail']);
		$rank = $ranks_to_blackmail[$rankID];
		
		if (!$rank)
		{
			echo View::Message($langBase->get('santaj-01'), 2);
		}
		else
		{
			unset($_SESSION['MZ_blackmail_hash']);
			
			$db->Query("UPDATE `blackmail` SET `last_time`='".time()."', `last`='".$rankID."' WHERE `id`='".$blackmail['id']."'");
			
			$money = rand($config['blackmail_money_range'][0], $config['blackmail_money_range'][1] + ($config['blackmail_money_max_up'] * ($rankID - 2)));
			
			$sql = $db->Query("SELECT id,userid,rankpoints,level,name,health FROM `[players]` WHERE `rank`='".$rankID."' AND `cash`>='".$money."' AND `health`>'0' AND `level`>'0' AND `level`<'3' AND `userid`!='".User::Data('id')."'".(Player::FamilyData('id') ? " AND `family`!='".Player::FamilyData('id')."'" : "")." ORDER BY RAND() LIMIT 1");
			$victim = $db->FetchArray($sql);
			
			if ($victim['id'] == '')
			{
				$stats['total_failed']++;
				$db->Query("UPDATE `blackmail` SET `latency`='".rand($config['blackmail_latency'][0] - 100, $config['blackmail_latency'][1] - 100)."', `stats`='".serialize($stats)."' WHERE `id`='".$blackmail['id']."'");
				
				$wanted_level = Player::Data('wanted-level') + rand($config['blackmail_wanted_range'][0], $config['blackmail_wanted_range'][1]);
				$wanted_level = $wanted_level > $config['max_wanted-level'] ? $config['max_wanted-level'] : $wanted_level;
				
				$db->Query("UPDATE `[players]` SET `wanted-level`='".$wanted_level."' WHERE `id`='".Player::Data('id')."'");
				
				Accessories::AddToLog(Player::Data('id'), array('result' => 'no_find', 'latency' => $waitTime));
				
				View::Message($langBase->get('santaj-02'), 2, true);
			}
			else
			{
				$sql = $db->Query("SELECT id,stats,blackmails_got FROM `blackmail` WHERE `playerid`='".$victim['id']."'");
				$v_blackmail = $db->FetchArray($sql);
				
				$v_stats = array();
				$v_got = array();
				if ($v_blackmail['id'] != '')
				{
					$v_stats = unserialize($v_blackmail['stats']);
					$v_got = unserialize($v_blackmail['blackmails_got']);
				}
				
				$rankDifference = Player::Data('rankpoints') - $victim['rankpoints'];
				$chance = round($rankDifference / Player::Data('rankpoints') * 1000, 0);
				
				$wanted = View::AsPercent(Player::Data('wanted-level'), $config['max_wanted-level'], 0) * 10;
				if ($wanted > 0)
				{
					$chance -= $wanted;
				}
				
				$chance = round($chance, 0);
				
				if (rand(0, 1000) <= $chance)
				{
					$wanted_level = Player::Data('wanted-level') + rand($config['blackmail_wanted_range'][0], $config['blackmail_wanted_range'][1]);
					$wanted_level = $wanted_level > $config['max_wanted-level'] ? $config['max_wanted-level'] : $wanted_level;
					
					$rankPoints = rand($config['blackmail_rankpoints_range'][0], $config['blackmail_rankpoints_range'][1]);
					
					$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$money."', `wanted-level`='".$wanted_level."', `rankpoints`=`rankpoints`+'".$rankPoints."' WHERE `id`='".Player::Data('id')."'");
					$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$money."' WHERE `id`='".$victim['id']."'");
					
					Accessories::AddLogEvent($victim['id'], 43, array(
						'-PLAYER_IMG-' => Player::Data('profileimage'),
						'-PLAYER_NAME-' => Player::Data('name'),
						'-MONEY-' => View::CashFormat($money)
					), $victim['userid']);
					
					$stats['total_success']++;
					$stats['cash_got'] += $money;
					$v_stats['cash_lost'] += $money;
					$v_stats['total_got']++;
					
					$v_got[] = array(
						'player' => Player::Data('id'),
						'money' => $money,
						'date' => time()
					);
					$made[] = array(
						'player' => $victim['id'],
						'money' => $money,
						'date' => time()
					);
					
					$log_data = array('result' => 'success', 'money' => $money, 'latency' => abs($waitTime));
					Accessories::AddToLog($victim['player'], array('blackmail_by' => Player::Data('id'), 'money' => $money));
					
					$message = array($langBase->get('santaj-03', array('-PLAYER-' => View::Player($victim, true), '-CASH-' => View::CashFormat($money))), 1);
					
					$mission = new Mission(Player::$datavar);
					$mission_data = $mission->missions_data[$mission->current_mission];
					
					if ($mission->current_mission == 1)
					{
						if ($mission_data['objects'][3]['completed'] != 1)
						{
							$victims = $mission_data['objects'][3]['blackmailed_players'];
							if (!in_array($victim['id'], $victims))
							{
								$victims[] = $victim['id'];
								$mission_data['objects'][3]['blackmailed_players'] = $victims;
								$mission->missions_data[$mission->current_mission]['objects'][3]['blackmailed_players'] = $victims;
								
								$mission->saveMissionData();
							}
							
							if (count($victims) >= 3)
							{
								$mission->completeObject(3);
							}
						}
					}
					
					if (in_array(4, $mission->active_minimissions))
					{
						$mission->minimissions[4]['data']['money'] += $money;
						$mission->miniMissions_save();
						
						if ($mission->minimissions[4]['data']['money'] >= 200000)
						{
							$mission->miniMission_success(4);
						}
					}
				}
				else
				{
					$stats['total_failed']++;
					
					$wanted_level = rand($config['blackmail_wanted_range'][0], $config['blackmail_wanted_range'][1]);
					
					$db->Query("UPDATE `[players]` SET `wanted-level`=`wanted-level`+'".$wanted_level."' WHERE `id`='".Player::Data('id')."'");
					
					$log_data = array('result' => 'fail');
					
					if (rand(0, $config['max_wanted-level']) <= Player::Data('wanted-level')+$wanted_level)
					{
						$penalty = Accessories::SetInJail(Player::Data('id'), $wanted_level);
						
						$message = array($langBase->get('santaj-04', array('-PLAYER-' => View::Player($victim, true), '-TIME-' => $penalty)), 2);
					}
					else
					{
						$message = array($langBase->get('santaj-05', array('-PLAYER-' => View::Player($victim, true))), 2);
					}
				}
				
				$db->Query("UPDATE `blackmail` SET `stats`='".serialize($stats)."', `blackmails_made`='".serialize($made)."', `latency`='".rand($config['blackmail_latency'][0], $config['blackmail_latency'][1])."' WHERE `id`='".$blackmail['id']."'");
				if ($v_blackmail['id'] != '')
				{
					$db->Query("UPDATE `blackmail` SET `stats`='".serialize($v_stats)."', `blackmails_got`='".serialize($v_got)."' WHERE `id`='".$v_blackmail['id']."'");
				}
				
				Accessories::AddToLog(Player::Data('id'), $log_data);
				
				$abData = unserialize(Player::Data('antibot_data'));
				$abData['blackmail']--;
				if ($abData['blackmail'] <= 0)
				{
					$abData['blackmail'] = rand($config['antibot_next_range']['blackmail'][0], $config['antibot_next_range']['blackmail'][1]);
					$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
					
					Accessories::CreateAntibotSession(Player::Data('id'), $db->EscapeString($_GET['side']));
					
					View::Message($message[0], $message[1], true);
				}
				$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
				
				View::Message($message[0], $message[1], true);
			}
		}
	}
?>
<div style="width: 500px; margin: 0px auto;">
	<div class="left" style="width: 220px;">
    	<div class="bg_c w200">
            <h1 class="big"><?=$langBase->get('santaj-00')?></h1>
            <?php
            if (Player::Data('rank') < $config['blackmail_min_rank'])
            {
                echo '<p class="red medium">'.$langBase->get('santaj-06', array('-RANK-' => $config['ranks'][$config['blackmail_min_rank']][0])).'</p>';
            }
            elseif ($waitTime > 0)
            {
                echo '<p class="red medium">'.$langBase->get('santaj-07', array('-TIME-' => $waitTime)).'</p>';
            }
            else
            {
        	?>
            <p><?=$langBase->get('santaj-08')?></p>
            <form method="post" action="">
                <input type="hidden" name="hash" value="<?php echo substr($_SESSION['MZ_blackmail_hash'], 3); ?>" />
                <table class="table boxHandle">
                    <thead>
                        <tr>
                            <td><?=$langBase->get('ot-rank')?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($ranks_to_blackmail as $key => $rank)
                    {
                        $i++;
                        $c = $i%2 ? 1 : 2;
                    ?>
                        <tr class="c_<?=$c?> boxHandle">
                            <td><input type="radio" name="rank_blackmail" value="<?=$key?>"<?php if ($key == $blackmail['last']){ echo ' checked="checked"'; }?> /><?=$rank[0]?><?php if ($key == Player::Data('rank')){ echo ' <span class="yellow">['.$langBase->get('santaj-09').']</span>'; }?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
                <p class="center">
                    <input type="submit" value="<?=$langBase->get('santaj-10')?>" />
                </p>
            </form>
        	<?php
            }
        	?>
        </div>
    </div>
    <div class="left" style="width: 270px; margin-left: 10px;">
    	<div class="bg_c" style="width: 250px;">
            <h1 class="big"><?=$langBase->get('ot-stats')?></h1>
            <p><?=$langBase->get('santaj-11', array('-TOTAL-' => View::CashFormat($stats['total_success'] + $stats['total_failed']), '-SUCCESS-' => View::CashFormat($stats['total_success']), '-FAIL-' => View::CashFormat($stats['total_failed'])))?></p>
            <p><?=$langBase->get('santaj-12', array('-NUM-' => View::CashFormat($stats['total_got'])))?></p>
            <p><?=$langBase->get('santaj-13', array('-WIN-' => View::CashFormat($stats['cash_got']), '-LOST-' => View::CashFormat($stats['cash_lost'])))?><br /><?=$langBase->get('min-24')?>: <b class="<?=($stats['cash_got'] - $stats['cash_lost'] < 0 ? 'red' : 'yellow')?>"><?=(View::CashFormat($stats['cash_got'] - $stats['cash_lost']))?> $</b></p>
            <?php
            if (count($got) > 0):
			krsort($got);
			$got = array_slice($got, 0, 5);
			?>
            <table class="table">
            	<thead>
                	<tr>
                    	<td colspan="3"><?=$langBase->get('santaj-15')?></td>
                    </tr>
                    <tr class="small">
                    	<td><?=$langBase->get('txt-06')?></td>
                        <td><?=$langBase->get('santaj-14')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
				foreach ($got as $bm)
				{
					$i++;
					$c = $i%2 ? 1 : 2;
				?>
                	<tr class="c_<?=$c?>">
                    	<td class="center"><?=View::Player(array('id' => $bm['player']))?></td>
                        <td class="center"><?=View::CashFormat($bm['money'])?> $</td>
                    </tr>
                <?php
				}
				?>
                </tbody>
            </table>
            <?php
            endif;
			
            if (count($made) > 0):
			krsort($made);
			$made = array_slice($made, 0, 5);
			?>
            <table class="table">
            	<thead>
                	<tr>
                    	<td colspan="2"><?=$langBase->get('santaj-16')?></td>
                    </tr>
                    <tr class="small">
                    	<td><?=$langBase->get('txt-06')?></td>
                        <td><?=$langBase->get('santaj-14')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
				$i = 0;
				
				foreach ($made as $bm)
				{
					$i++;
					$c = $i%2 ? 1 : 2;
				?>
                	<tr class="c_<?=$c?>">
                    	<td class="center"><?=View::Player(array('id' => $bm['player']))?></td>
                        <td class="center"><?=View::CashFormat($bm['money'])?> $</td>
                    </tr>
                <?php
				}
				?>
                </tbody>
            </table>
            <?php endif;?>
        </div>
    </div>
    <div class="clear"></div>
</div>