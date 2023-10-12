<div class="script_header">
	<img src="<?=$config['base_url']?>images/script_headers/planraub.jpg" alt="" />
<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	class Planned_Crime
	{
		var $player = false, $db = false, $config = false;
		var $in_crime = false, $my_job = false;
		var $crime_jobs = false, $invites = false, $hostages_state = false, $crime_type = false, $crime_started = false, $hasEquipmentData = false, $hasPlayerData, $last_times = false;
		var $type_data = false, $status = 0, $started_time = 0, $equipmentError = false;
		
		function __construct($player, $db, $config, $player_mission)
		{
			$this->db = $db;
			$this->player = $player;
			$this->config = $config;
			$this->mission = $player_mission;
			
			$sql = $this->db->Query("SELECT id,crime_type,starter,jobs,hostages_state,started_time,started,status,last_times,invites FROM `planned_crime` WHERE (`starter`='".$this->player['id']."' OR `jobs` LIKE '%".$this->player['id']."%') AND `active`='1'");
			while ($crime = $this->db->FetchArray($sql))
			{
				$jobs = unserialize($crime['jobs']);
				
				if ($crime['starter'] == $this->player['id'])
				{
					$this->in_crime = $crime;
					$this->my_job = 'starter';
					break;
				}
				else
				{
					foreach ($jobs as $key => $job)
					{
						if ($job['player'] == $this->player['id'])
						{
							$this->in_crime = $crime;
							$this->my_job = $job['job_type'];
							break;
						}
					}
				}
			}
			
			if ($this->isInCrime() === true)
			{
				$this->crime_type = $this->in_crime['crime_type'];
				$this->crime_jobs = $jobs;
				$this->invites = unserialize($this->in_crime['invites']);
				$this->hostages_state = $this->in_crime['hostages_state'];
				$this->crime_started = $this->in_crime['started'] == 1 ? true : false;
				$this->type_data = $this->config['planned_crime_types'][$this->in_crime['crime_type']];
				$this->status = $this->in_crime['status'];
				$this->last_times = unserialize($this->in_crime['last_times']);
				$this->started_time = $this->in_crime['started_time'];
				
				$this->updateJobs();
				
				if ($this->crime_started === true)
				{
					if ($this->last_times['hostages_state_up']+$this->config['planned_crime_hostages_state_up_interval'] <= time())
					{
						$this->hostages_state_up();
					}
					
					if ($this->started_time+$this->type_data['max_time'] <= time())
					{
						$this->crime_failed();
						
						View::Message($langBase->get('jaforg-01'), 2, true);
					}
				}
			}
		}
		
		private function hostages_state_up()
		{
			$up = rand($this->config['planned_crime_hostages_state_up'][0], $this->config['planned_crime_hostages_state_up'][1]);
			$this->hostages_state += $up;
			if ($this->hostages_state > $this->config['planned_crime_hostages_state_max'])
				$this->hostages_state = $this->config['planned_crime_hostages_state_max'];
			
			$this->last_times['hostages_state_up'] = time();
			
			$this->db->Query("UPDATE `planned_crime` SET `last_times`='".serialize($this->last_times)."', `hostages_state`='".$this->hostages_state."' WHERE `id`='".$this->in_crime['id']."'");
			
			return true;
		}
		
		public function start_crime($type)
		{
			if ($this->crime_started === true || count($this->missing_jobs()) > 0 || $this->hasAllEquipment() !== true)
			{
				return false;
			}
			else
			{
				$this->crime_started = true;
				$this->status = 1;
				$this->hostages_state = rand(70, 100);
				$this->db->Query("UPDATE `planned_crime` SET `started`='1', `started_time`='".time()."', `hostages_state`='".$this->hostages_state."', `invites`='a:0:{}', `crime_type`='".$type."', `status`='1' WHERE `id`='".$this->in_crime['id']."'");
				
				if ($this->is_mission() === true)
				{
					$this->crash_driver_do();
				}
				
				return true;
			}
		}
		
		public function is_mission()
		{
			$retval = false;
			foreach ($this->crime_jobs as $job)
			{
				if ($job['player'] == 'mission')
				{
					$retval = true;
					break;
				}
			}
			
			return $retval;
		}
		
		private function updateJobs()
		{
			foreach ($this->crime_jobs as $key => $value)
			{
				if ($value['player'] == 'mission')
					continue;
				
				$sql = $this->db->Query("SELECT id,weapons,weapon FROM `[players]` WHERE `id`='".$value['player']."' AND `health`>'0' AND `level`>'0'");
				$player = $this->db->FetchArray($sql);
				
				if ($player['id'] == '')
				{
					$this->errorStopCrime();
					break;
				}
				else
				{
					$this->crime_jobs[$key]['player_data']['id'] = $player['id'];
					$this->crime_jobs[$key]['player_data']['weapon'] = $player['weapon'];
					$this->crime_jobs[$key]['player_data']['weapons'] = unserialize($player['weapons']);
				}
				
				$equipment = $value['equipment'];
				$equipment_data = $value['equipment_data'];
				if ($this->config['planned_crime_equipment'][$equipment])
				{
					if ($equipment == 'car')
					{
						$sql = $this->db->Query("SELECT id,car_type,horsepowers,damage FROM `cars` WHERE `id`='".$equipment_data['car_id']."' AND `owner`='".$value['player']."' AND `active`='1' AND `horsepowers`>'0' AND `sale`='0'");
						$car = $this->db->FetchArray($sql);
						
						if ($this->crime_started === true && ($car['damage'] >= $this->config['car_max_damage'] && $value['job_type'] != 'crash_driver'))
						{
							$this->errorStopCrime();
							break;
						}
						else
						{
							if ($car['id'] == '')
							{
								$this->equipmentError = true;
							}
							else
							{
								$this->crime_jobs[$key]['equipment_data']['car_horsepowers'] = $car['horsepowers'];
								$this->crime_jobs[$key]['equipment_data']['car_type'] = $car['car_type'];
								$this->crime_jobs[$key]['equipment_data']['car_damage'] = $car['damage'];
							}
						}
					}
					elseif ($equipment == 'weapon')
					{
						if (!$this->config['weapons'][$player['weapon']])
						{
							if ($this->crime_started === true)
							{
								$this->errorStopCrime();
								break;
							}
							else
							{
								$this->crime_jobs[$key]['equipment_data']['has_weapon'] = 'nope';
							}
						}
						else
						{
							$this->crime_jobs[$key]['equipment_data']['has_weapon'] = 'yeah';
						}
					}
				}
			}
			
			$this->hasJobData = true;
		}
		
		public function starterStopCrime()
		{
			if ($this->crime_started === true || $this->my_job != 'starter')
			{
				return false;
			}
			else
			{
				$this->db->Query("UPDATE `planned_crime` SET `active`='0', `ended`='".time()."', `stopped_by`='starter' WHERE `id`='".$this->in_crime['id']."'");
				$this->in_crime = false;
				
				return true;
			}
		}
		
		private function crime_failed()
		{
			if ($this->started_time+$this->type_data['max_time'] > time())
			{
				return false;
			}
			else
			{
				$this->db->Query("UPDATE `planned_crime` SET `active`='0', `ended`='".time()."', `stopped_by`='failed', `status`='3' WHERE `id`='".$this->in_crime['id']."'");
				
				$rankpoints = rand($this->type_data['rankpoints']['fail'][0], $this->type_data['rankpoints']['fail'][1]);
				$wanted = rand($this->type_data['wanted_level']['fail'][0], $this->type_data['wanted_level']['fail'][1]);
				foreach ($this->crime_jobs as $value)
				{
					if ($value['player'] == 'mission')
						continue;
					
					$this->db->Query("UPDATE `[players]` SET `last_planned_crime`='".time()."', `wanted-level`=`wanted-level`+'".$wanted."', `rankpoints`=`rankpoints`+'".$rankpoints."', `cash`=`cash`+'".$money_to_each."' WHERE `id`='".$value['player']."'");
					
					Accessories::SetInJail($value['player'], $wanted);
				}
				
				$this->in_crime = false;
				
				return true;
			}
		}
		
		public function getaway_do()
		{
			if ($this->status != 2)
			{
				return false;
			}
			else
			{
				$result_money = rand($this->type_data['result_money'][0], $this->type_data['result_money'][1]);
				$result_money = round($result_money/100 * View::AsPercent(time() - $this->started_time, $this->type_data['max_time'], 2), 0);
				
				$getaway_job = $this->crime_jobs[$this->getJob('getaway_driver')];
				$getaway_car = $getaway_job['equipment_data'];
				
				$car_price = $this->config['cars'][$getaway_car['car_type']]['price_per_hp'] * $getaway_car['car_horsepowers'];
				$car_price = round(($car_price - ($car_price/100 * View::AsPercent($getaway_car['car_damage'], $this->config['car_max_damage'], 2))), 0);
				
				$result_money += $car_price * 2;
				$result_money = round(($result_money - ($result_money/100 * View::AsPercent($this->hostages_state, $this->config['planned_crime_hostages_state_max'], 2))), 0);
				
				$money_to_each = round($result_money/3, 0);
				$rankpoints = rand($this->type_data['rankpoints']['success'][0], $this->type_data['rankpoints']['success'][1]);
				$wanted = rand($this->type_data['wanted_level']['success'][0], $this->type_data['wanted_level']['success'][1]);
				foreach ($this->crime_jobs as $value)
				{
					if ($value['player'] == 'mission')
						continue;
					
					$this->db->Query("UPDATE `[players]` SET `last_planned_crime`='".time()."', `wanted-level`=`wanted-level`+'".$wanted."', `rankpoints`=`rankpoints`+'".$rankpoints."', `cash`=`cash`+'".$money_to_each."' WHERE `id`='".$value['player']."'");
					
					Accessories::AddLogEvent($value['player'], 27, array(
						'-MONEY-' => View::CashFormat($money_to_each)
					));
				}
				
				$this->db->Query("UPDATE `planned_crime` SET `active`='0', `ended`='".time()."', `stopped_by`='getaway', `status`='3', `money_result`='".$result_money."' WHERE `id`='".$this->in_crime['id']."'");
				$this->in_crime = false;
				
				if ($this->is_mission() === true)
				{
					$this->mission->completeObject(3);
				}
				
				return true;
			}
		}
		
		public function job_remove($job_id)
		{
			if ($job_id == 'starter' || $this->crime_started === true)
			{
				return false;
			}
			
			$jobs = unserialize($this->in_crime['jobs']);
			unset($jobs[$job_id]);
			
			$this->db->Query("UPDATE `planned_crime` SET `jobs`='".serialize($jobs)."' WHERE `id`='".$this->in_crime['id']."'");
			
			return true;
		}
		
		public function shooter_do($bullets)
		{
			if ($this->status != 2 || $this->my_job != 'shooter' || $this->shooter_timeleft() > 0 || $bullets > $this->player['bullets'] || $bullets <= 0)
			{
				return false;
			}
			else
			{
				$weapon_id = $this->player['weapon'];
				$weapons = unserialize($this->player['weapons']);
				$weapon_data = $weapons[$weapon_id];
				$weapon = $this->config['weapons'][$weapon_id];
				
				$this->last_times['shooter'] = time();
				
				$hostage_impact = 0;
				for ($i = 1; $i <= $bullets; $i++)
				{
					$hostage_impact += rand($this->config['planned_crime_shooter_fire_impact'][0], $this->config['planned_crime_shooter_fire_impact'][1]);
				}
				
				$this->hostages_state -= $hostage_impact;
				if ($this->hostages_state < 0)
					$this->hostages_state = 0;
				
				$this->db->Query("UPDATE `planned_crime` SET `hostages_state`='".$this->hostages_state."', `last_times`='".serialize($this->last_times)."' WHERE `id`='".$this->in_crime['id']."'");
				$this->db->Query("UPDATE `[players]` SET `bullets`=`bullets`-'".$bullets."' WHERE `id`='".$this->player['id']."'");
				
				return true;
			}
		}
		
		public function crash_driver_do()
		{
			if ($this->status != 1)
			{
				return false;
			}
			else
			{
				$crash_job = $this->crime_jobs[$this->getJob('crash_driver')];
				$crash_car = $crash_job['equipment_data'];
				
				$damage = rand(100, 160);
				$new_damage = $crash_car['car_damage'] + $damage;
				if ($new_damage > $this->config['car_max_damage']) $new_damage = $this->config['car_max_damage'];
				
				$this->status = 2;
				
				$this->db->Query("UPDATE `planned_crime` SET `status`='".$this->status."' WHERE `id`='".$this->in_crime['id']."'");
				$this->db->Query("UPDATE `cars` SET `damage`='".$new_damage."'".($new_damage >= $this->config['car_max_damage'] ? ", `active`='0'" : '')." WHERE `id`='".$crash_car['car_id']."'");
				
				return true;
			}
		}
		
		private function errorStopCrime()
		{
			$this->db->Query("UPDATE `planned_crime` SET `active`='0', `ended`='".time()."', `stopped_by`='error' WHERE `id`='".$this->in_crime['id']."'");
			$this->in_crime = false;
			
			return true;
		}
		
		public function getJob($job_id)
		{
			$retval = false;
			
			foreach ($this->crime_jobs as $key => $value)
			{
				if ($value['job_type'] == $job_id)
				{
					$retval = $key;
					break;
				}
			}
			
			return $retval;
		}
		
		public function missing_jobs()
		{
			$missing = array();
			foreach ($this->config['planned_crime_job_types'] as $key => $value)
			{
				if ($this->getJob($key) === false)
				{
					$missing[] = $key;
				}
			}
			
			return $missing;
		}
		
		public function hasAllEquipment()
		{
			if ($this->hasPlayerData === false)
			{
				return false;
			}
			else
			{
				foreach ($this->crime_jobs as $value)
				{
					if (empty($value['equipment_data']) && $value['job_type'] != 'starter' || ($value['job_type'] == 'shooter' && $value['equipment_data']['has_weapon'] != 'yeah'))
					{
						return false;
					}
				}
			}
			
			return !$this->equipmentError;
		}
		
		public function isInCrime()
		{
			return $this->in_crime === false ? false : true;
		}
		
		public function shooter_timeleft()
		{
			return $this->last_times['shooter'] + $this->config['planned_crime_shooter_wait'] - time();
		}
	}
	
	$player_mission_data = $player_mission->missions_data[$player_mission->current_mission];
	$planned_crime = new Planned_Crime(Player::$datavar, $db, $config, $player_mission);
	$crime_timeleft = Player::Data('last_planned_crime') + $config['planned_crime_wait_time'] - time();
	
	if ($planned_crime->isInCrime() !== true)
	{
		if ($crime_timeleft > 0)
		{
			echo View::Message($langBase->get('jaforg-02', array('-TIME-' => $crime_timeleft)), 2);
		}
		else
		{
			if (isset($_POST['start_crime']))
			{
				if ($config['planned_crime_start_min_rank'] > Player::Data('rank'))
				{
					echo View::Message($langBase->get('jaforg-03', array('-LEVEL-' => $config['ranks'][$config['planned_crime_start_min_rank']][0])), 2);
				}
				else
				{
					$jobs = array(
						Player::Data('id') => array(
							'player' => Player::Data('id'),
							'job_type' => 'starter',
							'equipment' => '',
							'equipment_data' => array()
						)
					);
					
					if ($player_mission->current_mission == 3 && $player_mission_data['objects'][0]['completed'] == 1 && $player_mission_data['objects'][1]['completed'] == 1 && $player_mission_data['objects'][2]['completed'] == 1 && $player_mission_data['objects'][3]['completed'] != 1)
					{
						$cars = array();
						$car = $config['cars'][8];
						for ($i = 0; $i++; $i < 2)
						{
							$db->Query("INSERT INTO `cars` (`car_type`, `horsepowers`, `damage`, `place`, `acquired`)VALUES('8', '".$car['default_horsepowers']."', '".rand(80, 120)."', '".Player::Data('live')."', '".time()."')");
							$cars[] = mysql_insert_id();
						}
						
						$jobs['Jos&eacute; Macaruno'] = array(
							'player' => 'mission',
							'job_type' => 'getaway_driver',
							'equipment' => 'car',
							'equipment_data' => array('car_id' => $cars[0])
						);
						$jobs['Mario De Aramengo'] = array(
							'player' => 'mission',
							'job_type' => 'crash_driver',
							'equipment' => 'car',
							'equipment_data' => array('car_id' => $cars[1])
						);
					}
					
					$db->Query("INSERT INTO `planned_crime` (`starter`, `jobs`)VALUES('".Player::Data('id')."', '".serialize($jobs)."')");
					
					View::Message($langBase->get('jaforg-04'), 1, true);
				}
			}
			elseif (isset($_POST['join_crime']))
			{
				$crime = $db->EscapeString($_POST['join_crime']);
				$sql = $db->Query("SELECT id,invites,jobs FROM `planned_crime` WHERE `invites`!='a:0:{}' AND `active`='1' AND `started`='0' AND `id`='".$crime."'");
				$crime = $db->FetchArray($sql);
				
				if ($crime['id'] == '')
				{
					echo View::Message($langBase->get('jaforg-05'), 2);
				}
				else
				{
					$invites = unserialize($crime['invites']);
					$invite = $invites[Player::Data('id')];
					
					if (!$invite)
					{
						echo View::Message($langBase->get('jaforg-06'), 2);
					}
					else
					{
						unset($invites[Player::Data('id')]);
						
						$jobs = unserialize($crime['jobs']);
						$jobs[$invite['player']] = array(
							'player' => $invite['player'],
							'job_type' => $invite['job_type'],
							'equipment' => $config['planned_crime_job_types'][$invite['job_type']]['equipment'],
							'equipment_data' => array()
						);
						
						$db->Query("UPDATE `planned_crime` SET `invites`='".serialize($invites)."', `jobs`='".serialize($jobs)."' WHERE `id`='".$crime['id']."'");
						
						View::Message($langBase->get('jaforg-07'), 1, true);
					}
				}
			}
?>
<div class="left" style="width: 300px; margin-left: 8px;">
	<div class="bg_c" style="width: 280px;">
    	<h1 class="big"><?=$langBase->get('jaforg-08')?></h1>
        <form method="post" action="">
        	<p class="center">
            	<input type="submit" name="start_crime" value="<?=$langBase->get('jaforg-09')?>" />
            </p>
        </form>
    </div>
</div>
<div class="left" style="width: 300px; margin-left: 20px;">
	<div class="bg_c" style="width: 280px;">
    	<h1 class="big"><?=$langBase->get('jaforg-10')?></h1>
        <?php
		$invites = array();
		
		$sql = $db->Query("SELECT id,starter,invites FROM `planned_crime` WHERE `invites`!='a:0:{}' AND `active`='1' AND `started`='0'");
		while ($crime = $db->FetchArray($sql))
		{
			$inv = unserialize($crime['invites']);
			
			if ($inv[Player::Data('id')])
			{
				$invites[] = $crime;
			}
		}
		
		if (count($invites) <= 0)
		{
			echo '<p>'.$langBase->get('jaforg-11').'</p>';
		}
		else
		{
		?>
        <form method="post" action="">
        	<table class="table boxHandle">
            	<thead>
                	<tr class="small">
                		<td><?=$langBase->get('c_orgj-01')?></td>
                        <td><?=$langBase->get('jaforg-12')?></td>
                    	<td><?=$langBase->get('txt-27')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($invites as $key => $crime)
                {
                    $i++;
                    $c = $i%2 ? 1 : 2;
                    
                    $inv = unserialize($crime['invites']);
                ?>
                    <tr class="c_<?=$c?> boxHandle">
                        <td><input type="radio" name="join_crime" value="<?=$crime['id']?>" /><?=View::Player(array('id' => $crime['starter']))?></td>
                        <td class="center"><?=$config['planned_crime_job_types'][$inv[Player::Data('id')]['job_type']]['title']?></td>
                        <td class="t_right"><?=View::Time($inv[Player::Data('id')]['time'], false, 'H:i')?></td>
                    </tr>
                <?php
                }
                ?>
                	<tr class="c_3 center">
                    	<td colspan="3"><input type="submit" value="<?=$langBase->get('txt-44')?>" /></td>
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
<?php
		}
	}
	else
	{
?>
<div class="left" style="width: 300px; margin-left: 8px;">
	<div class="bg_c" style="width: 280px;">
    	<h1 class="big"><?=$langBase->get('jaforg-13')?> - &laquo;<?=$config['planned_crime_job_types'][$planned_crime->my_job]['title']?>&raquo;</h1>
        <?php
		if ($planned_crime->my_job != 'starter' && $planned_crime->crime_started === false)
		{
			if (isset($_POST['leave_pr']))
			{
				$remove = $planned_crime->job_remove($planned_crime->getJob($planned_crime->my_job));
				
				View::Message(($remove === true ? $langBase->get('jaforg-14') : $langBase->get('jaforg-15')), ($remove === true ? 1 : 2), true);
			}
			
			echo '
				<form method="post" action="">
					<p class="center" style="margin-bottom: 15px;">
						<input type="submit" name="leave_pr" value="&laquo; '.$langBase->get('txt-10').'" class="button form_submit">
					</p>
				</form>
				';
		}
		
		if ($config['planned_crime_job_types'][$planned_crime->my_job]['equipment'] == 'car')
		{
			$car = $db->EscapeString($planned_crime->crime_jobs[Player::Data('id')]['equipment_data']['car_id']);
			$sql = $db->Query("SELECT id,car_type,damage,horsepowers FROM `cars` WHERE `owner`='".Player::Data('id')."' AND `active`='1' AND `place`='".Player::Data('live')."' AND `id`='".$car."'");
			$car = $db->FetchArray($sql);
			
			if ($car['id'] == '')
			{
				$sql = "SELECT id,car_type,horsepowers,damage FROM `cars` WHERE `owner`='".Player::Data('id')."' AND `active`='1' AND `place`='".Player::Data('live')."' AND `sale`='0' ORDER BY id DESC";
				$pagination = new Pagination($sql, 10, 'car_p');
				$cars = $pagination->GetSQLRows();
		?>
        <p><?=$langBase->get('jaforg-16')?></p>
        <?php
			if (count($cars) <= 0)
			{
				echo '<p>'.$langBase->get('err-06').'</p>';
			}
			else
			{
				if (isset($_POST['choose_car']))
				{
					$car = $db->EscapeString($_POST['choose_car']);
					$sql = $db->Query("SELECT id FROM `cars` WHERE `owner`='".Player::Data('id')."' AND `active`='1' AND `place`='".Player::Data('live')."' AND `sale`='0' AND `id`='".$car."'");
					$car = $db->FetchArray($sql);
					
					if ($car['id'] == '')
					{
						echo View::Message($langBase->get('jaforg-17'), 2);
					}
					else
					{
						$planned_crime->crime_jobs[Player::Data('id')]['equipment_data']['car_id'] = $car['id'];
						$db->Query("UPDATE `planned_crime` SET `jobs`='".serialize($planned_crime->crime_jobs)."' WHERE `id`='".$planned_crime->in_crime['id']."'");
						
						View::Message($langBase->get('jaforg-18'), 1, true);
					}
				}
		?>
        <form method="post" action="">
        	<table class="table boxHandle">
        		<thead>
                	<tr class="small">
                    	<td><?=$langBase->get('garaj-28')?></td>
                        <td><?=$langBase->get('garaj-17')?></td>
                        <td><?=$langBase->get('garaj-16')?></td>
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
                    	<td><input type="radio" name="choose_car" value="<?=$car['id']?>" /><?=$carType['brand']?><br /><b><?=$carType['model']?></b></td>
                        <td class="center"><?=View::AsPercent($car['damage'], $config['car_max_damage'], 2)?> %</td>
                        <td class="center"><?=View::CashFormat($car['horsepowers'])?> <?=$langBase->get('garaj-19')?></td>
                    </tr>
                <?php
					}
				?>
                	<tr class="c_3 center">
                    	<td colspan="3"><?=$pagination->GetPageLinks()?></td>
                    </tr>
                </tbody>
        	</table>
            <p class="center">
            	<input type="submit" value="Alege" />
            </p>
        </form>
        <?php
			}
		}
		else
		{
			$carType = $config['cars'][$car['car_type']];
		?>
        <p><?=$langBase->get('jaforg-19', array('-CAR-' => $carType['brand'].' '.$carType['model'], '-POWER-' => View::CashFormat($car['horsepowers']), '-DAMAGE-' => View::AsPercent($car['damage'], $config['car_max_damage'], 2)))?></p>
        <?php
		}
		?>
        <?php
		}
		elseif ($config['planned_crime_job_types'][$planned_crime->my_job]['equipment'] == 'weapon' && !$config['weapons'][Player::Data('weapon')])
		{
		?>
        <p><?=$langBase->get('jaforg-20')?></p>
        <?php
		}
		elseif ($planned_crime->my_job == 'starter')
		{
			$is_ready = (count($planned_crime->missing_jobs()) > 0 || $planned_crime->hasAllEquipment() !== true || $planned_crime->status != 0) ? false : true;
			
			if ($is_ready === true && $planned_crime->crime_started === false)
			{
				if (isset($_POST['start_type']))
				{
					$type_id = $db->EscapeString($_POST['start_type']);
					
					if ($config['planned_crime_types'][$type_id])
					{
						$start = $planned_crime->start_crime($type_id);
						
						View::Message(($start == true ? $langBase->get('jaforg-21') : $langBase->get('jaforg-22')), 1, true);
					}
				}
		?>
        <form method="post" action="">
        	<p><?=$langBase->get('jaforg-23')?></p>
            <table class="table boxHandle">
            	<thead>
                	<tr class="small">
                    	<td>#</td>
                        <td><?=$langBase->get('txt-35')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
				foreach ($config['planned_crime_types'] as $key => $type)
				{
					$i++;
					$c = $i%2 ? 1 : 2;
				?>
                	<tr class="c_<?=$c?> boxHandle">
                    	<td><input type="radio" name="start_type" value="<?=$key?>" /><?=$type['title']?></td>
                        <td class="center"><?=trim(View::strTime($type['max_time']))?></td>
                    </tr>
                <?php
				}
				?>
                	<tr class="c_3 center">
                    	<td colspan="2"><input type="submit" value="<?=$langBase->get('txt-14')?>" /></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <?php
			}
			else
			{
				if (count($planned_crime->missing_jobs()) > 0)
				{
					if (isset($_POST['invite_player']))
					{
						$player = $db->EscapeString(trim($_POST['invite_player']));
						$sql = $db->Query("SELECT id FROM `[players]` WHERE `name`='".$player."' AND `level`>'0' AND `health`>'0'");
						$player = $db->FetchArray($sql);
						
						$job_id = $db->EscapeString($_POST['invite_job']);
						$job = $config['planned_crime_job_types'][$job_id];
						
						if ($player['id'] == '')
						{
							echo View::Message($langBase->get('err-02'), 2);
						}
						elseif (!$job || !in_array($job_id, $planned_crime->missing_jobs()))
						{
							echo View::Message('ERROR', 2);
						}
						else
						{
							$removeInvite = false;
							foreach ($planned_crime->invites as $key => $value)
							{
								if ($value['job_type'] == $job_id)
								{
									$removeInvite = $key;
								}
							}
							
							$removeJob = false;
							foreach ($planned_crime->crime_jobs as $key => $value)
							{
								if ($value['job_type'] == $job_id)
								{
									$removeJob = $key;
								}
							}
							
							if ($removeInvite !== false)
								unset($planned_crime->invites[$removeInvite]);
							
							if ($removeJob !== false)
								unset($planned_crime->crime_jobs[$removeJob]);
								
							$planned_crime->invites[$player['id']] = array(
								'player' => $player['id'],
								'job_type' => $job_id,
								'time' => time()
							);
							
							$db->Query("UPDATE `planned_crime` SET `invites`='".serialize($planned_crime->invites)."', `jobs`='".serialize($planned_crime->crime_jobs)."' WHERE `id`='".$planned_crime->in_crime['id']."'");
							
							Accessories::AddLogEvent($player['id'], 50, array(
							'-PLAYER_NAME-' => Player::Data('name')
							), $player['userid']);
							
							View::Message($langBase->get('jaforg-24'), 1, true);
						}
					}
		?>
        <p><?=$langBase->get('jaforg-25')?></p>
        <div class="bg_c c_1" style="width: 260px;">
        	<h1 class="big"><?=$langBase->get('jaforg-26')?></h1>
            <form method="post" action="">
            	<dl class="dd_right">
                	<dt><?=$langBase->get('txt-06')?></dt>
                    <dd><input type="text" name="invite_player" class="flat" value="<?=View::FixQuot($_POST['invite_player'])?>" /></dd>
                    <dt><?=$langBase->get('jaforg-12')?></dt>
                    <dd>
                    	<select name="invite_job">
                        <?php
						foreach ($planned_crime->missing_jobs() as $value)
						{
							$job = $config['planned_crime_job_types'][$value];
							echo "<option value=\"" . $value . "\">" . $job['title'] . "</option>\n";
						}
						?>
                        </select>
                    </dd>
                </dl>
                <p class="clear center">
                	<input type="submit" value="<?=$langBase->get('jaforg-26')?>" />
                </p>
            </form>
        </div>
        <?php
				}
			}
			
			if ($planned_crime->status == 2)
			{
				if (isset($_POST['crime_finish']))
				{
					$getaway = $planned_crime->getaway_do();
					
					View::Message(($getaway === true ? $langBase->get('jaforg-27') : $langBase->get('jaforg-28')), 1, true);
				}
			?>
            <form method="post" action="">
            	<p class="center">
                	<input type="submit" name="crime_finish" value="<?=$langBase->get('jaforg-29')?>" />
                </p>
            </form>
            <?php
			}
			elseif ($planned_crime->crime_started === false)
			{
				if (isset($_POST['crime_finish']))
				{
					$stopCrime = $planned_crime->starterStopCrime();
					
					View::Message(($stopCrime === true ? $langBase->get('jaforg-31') : $langBase->get('jaforg-32')), 1, true);
				}
			?>
            <form method="post" action="">
            	<p class="center">
                	<input type="submit" name="crime_finish" value="<?=$langBase->get('jaforg-30')?>" />
                </p>
            </form>
            <?php
			}
		}
		elseif ($planned_crime->my_job == 'shooter')
		{
			if ($planned_crime->status == 2)
			{
				$wait_time = $planned_crime->shooter_timeleft();
				
				if (isset($_POST['fire_bullets']) && $wait_time <= 0)
				{
					$bullets = View::NumbersOnly($db->EscapeString($_POST['fire_bullets']));
					
					if ($bullets > $config['planned_crime_hostages_state_max'])
					{
						echo View::Message($langBase->get('jaforg-32', array('-MAX-' => $config['planned_crime_hostages_state_max'])), 2);
					}
					elseif ($bullets <= 0)
					{
						echo View::Message($langBase->get('jaforg-33'), 2);
					}
					elseif ($bullets > Player::Data('bullets'))
					{
						echo View::Message($langBase->get('jaforg-34'), 2);
					}
					else
					{
						$fire = $planned_crime->shooter_do($bullets);
						
						View::Message($fire === true ? $langBase->get('jaforg-35', array('-BULLETS-' => $bullets)) : $langBase->get('jaforg-36'), ($fire === true ? 1 : 2), true);
					}
				}
		?>
        <p><?=$langBase->get('jaforg-37', array('-MAX-' => $config['planned_crime_shooter_max_bullets']))?></p>
        <p><?=$langBase->get('jaforg-38')?> <b><?=View::AsPercent($planned_crime->hostages_state, $config['planned_crime_hostages_state_max'], 2)?> %</b></p>
        <?php
		if ($wait_time > 0)
		{
			echo $langBase->get('armament-30', array('-TIME-' => $wait_time));
		}
		else
		{
		?>
        <form method="post" action="">
        	<dl class="dd_right">
            	<dt><?=$langBase->get('ot-bullets')?></dt>
                <dd><input type="text" name="fire_bullets" value="<?=(Player::Data('bullets') >= $config['planned_crime_shooter_max_bullets'] ? $config['planned_crime_shooter_max_bullets'] : Player::Data('bullets'))?>" class="flat numbersOnly" maxlength="2" style="min-width: 30px; width: 30px;" /></dd>
            </dl>
            <p class="t_right clear">
            	<input type="submit" value="<?=$langBase->get('txt-14')?>" />
            </p>
        </form>
        <?php
		}
		?>
        <?php
			}
			else
			{
				echo '<p>'.$langBase->get('jaforg-39').'</p>';
			}
		}
		if ($planned_crime->my_job == 'crash_driver')
		{
			if ($planned_crime->status == 1)
			{
				if (isset($_POST['break_wall']))
				{
					$planned_crime->status = 2;
					$db->Query("UPDATE `planned_crime` SET `status`='2' WHERE `id`='".$planned_crime->in_crime['id']."'");
					
					$crash_job = $planned_crime->crime_jobs[$planned_crime->getJob('crash_driver')];
					$crash_car = $crash_job['equipment_data'];
					
					$newDamage = $crash_car['car_damage'] + rand(80, 140);
					$db->Query("UPDATE `cars` SET `damage`='".$newDamage."' WHERE `id`='".$crash_car['car_id']."'");
					
					View::Message($newDamage >= $config['car_max_damage'] ? $langBase->get('jaforg-40') : $langBase->get('jaforg-41', array('-DAMAGE-' => View::AsPercent($newDamage, $config['car_max_damage'], 2))), 1, true);
				}
		?>
        <p><?=$langBase->get('jaforg-42')?></p>
        <form method="post" action="">
        	<p class="center">
            	<input type="submit" name="break_wall" value="<?=$langBase->get('jaforg-43')?>" />
            </p>
        </form>
        <?php
			}
			else
			{
				echo '<p>'.$langBase->get('jaforg-39').'</p>';
			}
		}
		?>
    </div>
</div>
<div class="left" style="width: 300px; margin-left: 20px;">
	<div class="bg_c" style="width: 280px;">
    	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
        <dl class="dd_right">
        	<dt><?=$langBase->get('jaforg-44')?></dt>
            <dd><?=($planned_crime->crime_started === true ? $langBase->get('ot-yes') : $langBase->get('ot-no'))?></dd>
            <dt><?=$langBase->get('jaforg-45')?></dt>
            <dd><?=($planned_crime->crime_type == 0 ? 'N/A' : $planned_crime->type_data['title'])?></dd>
            <dt class="bold" style="padding-top: 10px;"><?=$langBase->get('jaforg-51')?></dt>
            <dd style="padding-top: 10px;"></dd>
            <?php
			foreach ($planned_crime->crime_jobs as $j_key => $job)
			{
				$hasEquipment = false;
				if ($job['job_type'] == 'starter')
				{
					$hasEquipment = true;
				}
				elseif ($job['equipment'] == 'weapon' && $job['equipment_data']['has_weapon'] == 'yeah')
				{
					$hasEquipment = true;
				}
				elseif ($job['equipment'] == 'car' && !empty($job['equipment_data']))
				{
					$hasEquipment = true;
				}
				
				echo "<dt>" . $config['planned_crime_job_types'][$job['job_type']]['title'] . "</dt><dd>" . (is_numeric($j_key) ? View::Player(array('id' => $job['player'])) : $j_key) . " - " . ($hasEquipment === true ? $langBase->get('jaforg-49') : $langBase->get('jaforg-50')) . "</dd>\n";
			}
			?>
            <?php
			if ($planned_crime->crime_started === true)
			{
			?>
            <dt class="bold" style="padding-top: 10px;"><?=$langBase->get('txt-22')?></dt>
            <dd style="padding-top: 10px;"></dd>
            <dt><?=$langBase->get('jaforg-46')?></dt>
            <dd><?=View::AsPercent($planned_crime->hostages_state, $config['planned_crime_hostages_state_max'], 2)?> %</dd>
            <dt><?=$langBase->get('bj-18')?></dt>
            <dd><?=(time() - $planned_crime->in_crime['started_time'])?> <?=$langBase->get('txt-45')?></dd>
            <dt><?=$langBase->get('bj-19')?></dt>
            <dd><span class="countdown"><?=($planned_crime->in_crime['started_time']+$planned_crime->type_data['max_time'] - time())?></span> <?=$langBase->get('txt-45')?></dd>
            <?php
			}
			?>
        </dl>
    </div>	
</div>
<div class="clear"></div>
<?php
	}
?>
</div>