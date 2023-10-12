<div class="script_header">
	<img src="<?=$config['base_url']?>images/script_headers/mission.jpg" alt="" />
<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	if ($player_mission->has_mission === true)
	{
		$rewards = $player_mission->current_mission_data['rewards'];
		$player_mission_data = $player_mission->missions_data[$player_mission->current_mission];
		
		$is_completed = true;
		if (!$player_mission->missions_data[$player_mission->current_mission] || count($player_mission->missions_data[$player_mission->current_mission]['objects']) < count($config['missions'][$player_mission->current_mission]['objects']))
		{
			$is_completed = false;
		}
		else
		{
			foreach ($player_mission->missions_data[$player_mission->current_mission]['objects'] as $obj)
			{
				if (intval($obj['completed']) !== 1)
				{
					$is_completed = false;
					break;
				}
			}
		}
		
		if (isset($_POST['start_mission']) && $player_mission_data['started'] != 1)
		{
			$startResult = $player_mission->start_mission();
			
			if ($startResult !== true)
			{
				echo View::Message('ERROR' . $startResult, 2);
			}
			else
			{
				View::Message($langBase->get('misiune-01'), 1, true);
			}
		}
		elseif (isset($_POST['complete_mission']) && $is_completed === true)
		{
			$checkResult = $player_mission->check_complete();
			
			if ($checkResult !== true)
			{
				echo View::Message($langBase->get('misiune-03'), 2);
			}
			else
			{
				View::Message($langBase->get('misiune-02'), 1, true);
			}
		}
		
		if ($player_mission->current_mission == 2)
		{
			if ($player_mission_data['objects'][0]['completed'] == 1 && $player_mission_data['objects'][2]['completed'] == 1 && $player_mission_data['objects'][3]['completed'] != 1)
			{
				$player_mission_data['objects'][3]['completed'] = 1;
				$player_mission_data['objects'][3]['place'] = array_rand($config['places']);
				$player_mission->missions_data[$player_mission->current_mission] = $player_mission_data;
				
				$player_mission->completeObject(3);
				$player_mission->saveMissionData();
			}
		}
	}
	
	$avaliable_minimissions = array();
	foreach ($config['minimissions'] as $key => $value)
	{
		if ($player_mission->minimissions[$key]['active'] == 0 && $player_mission->minimissions[$key]['last_finished']+$value['wait_time'] <= time())
		{
			$avaliable_minimissions[$key] = true;
		}
	}
	
	if (isset($_POST['startMiniMission']) && count($avaliable_minimissions) > 0)
	{
		$player_missionKey = $db->EscapeString($_POST['startMiniMission']);
		$miniMission = $config['minimissions'][$player_missionKey];
		$mData = $player_mission->minimissions[$player_missionKey];
		
		if (count($player_mission->active_minimissions) > 0)
		{
			echo View::Message($langBase->get('misiune-04'), 2);
		}
		elseif (!$avaliable_minimissions[$player_missionKey] || !$miniMission)
		{
			echo View::Message('ERROR', 2);
		}
		else
		{
			$player_missionStart = $player_mission->miniMission_start($player_missionKey);
			
			if ($player_missionStart === true)
			{
				View::Message($langBase->get('misiune-05'), 1, true);
			}
		}
	}
?>
<div style="width: 600px; margin: 0px auto;">
	<div class="left" style="width: 290px;">
    	<div class="bg_c" style="width: 270px;">
        	<h1 class="big"><?=$langBase->get('misiune-00')?></h1>
            <?php
			if ($player_mission->has_mission === true)
			{
			?>
            <p class="medium bold" style="color: #888888;"><?=$player_mission->current_mission_data['title']?></p>
            <p><?=$player_mission->current_mission_data['description']?></p>
            <?php
			}
			else
			{
				echo '<p>'.$langBase->get('misiune-06').'</p>';
			}
			?>
        </div>
    </div>
    <div class="left" style="width: 290px; margin-left: 20px;">
    <div class="bg_c" style="width: 270px;">
        	<h1 class="big"><?=$langBase->get('ot-status')?></h1>
            <?php
			if ($player_mission->has_mission === true)
			{
			?>
            <p class="bold"><?=$langBase->get('misiune-07')?></p>
            <ul>
            <?php
			$completed = 0;
			foreach ($player_mission->current_mission_data['objects'] as $key => $object)
			{
				if ($player_mission_data['objects'][$key]['completed'] == 1)
					$completed++;
				
				echo '<li class="' . ($player_mission_data['objects'][$key]['completed'] == 1 ? 'green' : 'red') . '"><span style="color: #777777;">' . $object . '</span></li>';
			}
			?>
            </ul>
            <div class="small_progressbar w200">
            	<div class="progress" style="width: <?=(round(View::AsPercent($completed, count($player_mission->current_mission_data['objects']), 0), 0))?>%;"><p><?=(round(View::AsPercent($completed, count($player_mission->current_mission_data['objects']), 2), 2))?> %</p></div>
            </div>
            <?php
			if ($is_completed === true)
			{
			?>
            <form method="post" action="">
            	<p class="center">
                	<input type="submit" name="complete_mission" value="<?=$langBase->get('misiune-08')?>" />
                </p>
            </form>
            <?php
			}
			?>
            <p><?=$langBase->get('misiune-09', array('-CASH-' => View::CashFormat($rewards['cash']), '-BULLETS-' => View::CashFormat($rewards['bullets']), '-COINS-' => View::CashFormat($rewards['points'])))?></p>
            <div class="hr big" style="margin: 10px 0 5px 0;"></div>
            <?php
			if ($player_mission_data['started'] != 1)
			{
			?>
            <form method="post" action="">
            	<p class="center">
                	<input type="submit" name="start_mission" value="<?=$langBase->get('misiune-10')?>" />
                </p>
            </form>
            <?php
			}
			else
			{
				echo '<p>'.$langBase->get('misiune-11').':	<b>' . View::Time($player_mission_data['start_time'], true) . '</b>.</p>';
				
				if ($player_mission->current_mission == 1)
				{
			?>
            <ul>
            	<li><?=$langBase->get('misiune-29', array('-NUM-' => View::CashFormat(count($player_mission_data['objects'][0]['completed_places']))))?></li>
                <li><?=$langBase->get('misiune-30', array('-NUM-' => View::CashFormat($player_mission_data['objects'][1]['num_completed'])))?></li>
                <li><?=$langBase->get('misiune-31', array('-NUM-' => View::CashFormat($player_mission_data['objects'][2]['num_stolen'])))?></li>
                <li><?=$langBase->get('misiune-32', array('-NUM-' => View::CashFormat(count($player_mission_data['objects'][3]['blackmailed_players']))))?></li>
                <li><?=$langBase->get('misiune-33', array('-NUM-' => View::CashFormat(count($player_mission_data['objects'][4]['broken_out_players']))))?></li>
            </ul>
            <?php
				}
				elseif ($player_mission->current_mission == 2)
				{
			?>
            <ul>
            	<li><?=$langBase->get('misiune-34', array('-NUM-' => count($player_mission_data['objects'][0]['wins'])))?></li>
                <li><?=($player_mission_data['objects'][3]['completed'] != 1 ? $langBase->get('misiune-35') : $langBase->get('misiune-36', array('-CITY-' => $config['places'][$player_mission_data['objects'][3]['place']][0])))?></li>
            </ul>
            <?php
				}
				elseif ($player_mission->current_mission == 3)
				{
			?>
            <ul>
            	<li><?=$langBase->get('misiune-26')?> <?=View::CashFormat($player_mission_data['objects'][0]['num_stolen'])?> Aston Martin</li>
            </ul>
			<?php
				}
				elseif ($player_mission->current_mission == 4)
				{
			?>
            <ul>
            	<li><?=$langBase->get('misiune-34', array('-NUM-' => count($player_mission_data['objects'][0]['wins'])))?></li>
                <li><?=($player_mission_data['objects'][1]['completed'] != 1 ? $langBase->get('misiune-37') : $langBase->get('misiune-38'))?></li>
                <li><?=($player_mission_data['objects'][2]['completed'] != 1 ? $langBase->get('misiune-27') : $langBase->get('misiune-28'))?></li>
                <li><?=$langBase->get('misiune-26')?> <?=View::CashFormat($player_mission_data['objects'][3]['num_stolen'])?> Bugatti Veyron</li>
            </ul>
			<?php
				}
				elseif ($player_mission->current_mission == 5)
				{
			?>
            <ul>
            	<li><?=$langBase->get('misiune-26')?> <?=View::CashFormat($player_mission_data['objects'][0]['num_stolen'])?> Bugatti Veyron</li>
                <li><?=$langBase->get('misiune-26')?> <?=View::CashFormat($player_mission_data['objects'][1]['num_stolen'])?> Ferrari Spider</li>
                <li><?=$langBase->get('misiune-26')?> <?=View::CashFormat($player_mission_data['objects'][2]['num_stolen'])?> Lamborghini Aventador</li>
                <li><?=$langBase->get('misiune-26')?> <?=View::CashFormat($player_mission_data['objects'][3]['num_stolen'])?> Lamborghini Reventon</li>
            </ul>
			<?php
				}
				elseif ($player_mission->current_mission == 6)
				{
			?>
            <ul>
            	<li><?=$langBase->get('misiune-40', array('-NUM-' => View::CashFormat($player_mission_data['objects'][0]['num_rec'])))?></li>
                <li><?=$langBase->get('misiune-39', array('-NUM-' => View::CashFormat($player_mission_data['objects'][1]['wins'])))?></li>
                <li><?=($player_mission_data['objects'][2]['completed'] != 1 ? $langBase->get('misiune-27') : $langBase->get('misiune-28'))?></li>
            </ul>
			<p class="center"><input type="text" class="flat" onfocus="this.select()" value="https://nmafia.unterweltmafia.de/inregistrare/<?=User::Data('id')?>" style="min-width: 200px;" /></p>
						<?php
				}
				elseif ($player_mission->current_mission == 7)
				{
			?>
            <ul>
            	<li><?=$langBase->get('misiune-41', array('-NUM-' => View::CashFormat($player_mission_data['objects'][0]['sent'])))?></li>
                <li><?=$langBase->get('misiune-42', array('-NUM-' => View::CashFormat($player_mission_data['objects'][1]['recv'])))?></li>
                <li><?=$langBase->get('misiune-43', array('-NUM-' => View::CashFormat($player_mission_data['objects'][2]['wins'])))?></li>
				<li><?=$langBase->get('misiune-44', array('-NUM-' => View::CashFormat($player_mission_data['objects'][3]['num_stolen'])))?></li>
				<li><?=$langBase->get('misiune-45', array('-NUM-' => View::CashFormat($player_mission_data['objects'][4]['num_stolen'])))?></li>
            </ul>
			<?php
				}
				elseif ($player_mission->current_mission == 8)
				{
			?>
            <ul>
            	<li><?=$langBase->get('misiune-46', array('-NUM-' => View::CashFormat($player_mission_data['objects'][0]['num'])))?></li>
                <li><?=$langBase->get('misiune-47', array('-SUM-' => View::CashFormat($player_mission_data['objects'][1]['sum'])))?></li>
                <li><?=$langBase->get('misiune-48', array('-NUM-' => View::CashFormat($player_mission_data['objects'][2]['num'])))?></li>
            </ul>
            <?php
				}
			}
			}
			else
			{
				echo '<p>'.$langBase->get('misiune-14').'</p>';
			}
			?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<div class="bg_c w600" style="margin: 0 auto;">
	<h1 class="big"><?=$langBase->get('misiune-12')?></h1>
    <p><?=$langBase->get('misiune-13')?></p>
    <?php
	if (count($avaliable_minimissions) <= 0)
	{
		echo '<p>'.$langBase->get('misiune-14').'</p>';
	}
	
	$mid = $player_mission->active_minimissions[0];
	$active = $config['minimissions'][$mid];
	if ($active)
	{
		$mData = $player_mission->minimissions[$mid];
		$timeProgress = 100 - round(View::AsPercent($mData['started']+$active['time_limit'] - time(), $active['time_limit'], 2), 2);
	?>
    <div class="hr big" style="margin: 10px 0 10px 0;"></div>
    <dl class="dd_right" style="width: 400px; margin: 0 auto;">
    	<dt><?=$langBase->get('misiune-11')?></dt>
        <dd><?=$active['title']?></dd>
        <dt><?=$langBase->get('misiune-15')?></dt>
        <dd><?=View::strTime($active['time_limit'], 1)?></dd>
        <dt><?=$langBase->get('misiune-16')?></dt>
        <dd>
			<?=View::strTime((time()-$mData['started']), 1)?>
            <div class="small_progressbar w200">
            	<div class="progress" style="width: <?=(round($timeProgress, 0)*2)?>px;"><p><?=$timeProgress?> %</p></div>
            </div>
        </dd>
        <dt><?=$langBase->get('ot-status')?></dt>
        <dd>
        <?php
		if ($mid == 1)
		{
			echo $langBase->get('misiune-20', array('-NUM-' => View::CashFormat($mData['data']['num_stolen'])));
		}
		elseif ($mid == 2)
		{
			echo $langBase->get('misiune-21', array('-NUM-' => View::CashFormat($mData['data']['num'])));
		}
		elseif ($mid == 3)
		{
			echo $langBase->get('misiune-22', array('-NUM-' => View::CashFormat($mData['data']['money'])));
		}
		elseif ($mid == 4)
		{
			echo $langBase->get('misiune-23', array('-NUM-' => View::CashFormat($mData['data']['money'])));
		}
		elseif ($mid == 5)
		{
			echo $langBase->get('misiune-24', array('-NUM-' => View::CashFormat($mData['data']['num'])));
		}
		elseif ($mid == 6)
		{
			echo $langBase->get('misiune-25', array('-NUM-' => (abs(View::AsPercent(600 - (Player::Data('rankpoints')-$mData['data']['startRankpoints']), $config['ranks'][Player::Data('rank')][1]-$config['ranks'][Player::Data('rank')][2], 2)))));
		}
		?>
        </dd>
    </dl>
    <div class="clear"></div>
    <?php
	}
	else
	{
	?>
    <div class="hr big" style="margin: 10px 0 10px 0;"></div>
    <form method="post" action="">
    	<table class="table boxHandle">
        	<thead>
            	<tr class="small">
                	<td><?=$langBase->get('cautare-02')?></td>
                    <td><?=$langBase->get('misiune-17')?></td>
                    <td><?=$langBase->get('misiune-15')?></td>
                    <td><?=$langBase->get('misiune-18')?></td>
                </tr>
            </thead>
            <tbody>
            <?php
			foreach ($avaliable_minimissions as $key => $value)
			{
				$i++;
				$c = $i%2 ? 1 : 2;
				
				$mini = $config['minimissions'][$key];
			?>
            	<tr class="c_<?=$c?> boxHandle">
                	<td><input type="radio" name="startMiniMission" value="<?=$key?>" /><?=$mini['title']?></td>
                    <td><?=View::CashFormat($mini['rewards']['cash'])?> $, <?=View::CashFormat($mini['rewards']['bullets'])?> <?=$langBase->get('ot-bullets')?><br /><?=(View::AsPercent($mini['rewards']['rankpoints'], $config['ranks'][Player::Data('rank')][2]-$config['ranks'][Player::Data('rank')][1], 2))?> % <?=$langBase->get('misiune-19')?></td>
                    <td class="center"><?=View::strTime($mini['time_limit'], 1)?></td>
                    <td class="t_right"><?=View::strTime($mini['wait_time'], 1)?></td>
                </tr>
            <?php
			}
			?>
            	<tr class="c_3 center">
                	<td colspan="4">
                    	<input type="submit" value="<?=$langBase->get('misiune-10')?>" />
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