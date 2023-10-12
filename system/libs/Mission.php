<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	class Mission
	{
		private $player_data = array(),
				$db = false,
				$config = false;
		
		public $current_mission = false,
			   $current_mission_data = false,
			   $has_mission = true,
			   $mission_raw = array(),
			   $missions_data = array(),
			   
			   $active_minimissions = array(),
			   $minimissions = false;
		
		function __construct($player)
		{
			global $config,
				   $db;
			
			$this->config = $config;
			$this->db = $db;
			
			$this->player_data = $player;
			
			$sql = $this->db->Query("SELECT id,current_mission,missions_data,minimissions FROM `mission` WHERE `player`='".$this->player_data['id']."'");
			$this->mission_raw = $this->db->FetchArray($sql);
			
			if ($this->mission_raw['id'] == '')
			{
				$sql = $this->db->Query("INSERT INTO `mission`
										(`player`, `current_mission`, `missions_data`, `minimissions`)
										VALUES
										('".$this->player_data['id']."', '1', 'a:0:{}', 'a:0:{}')");
				
				$this->mission_raw['id'] = $this->db->GetLastInsertId();
				$this->mission_raw['player'] = $this->player_data['id'];
				$this->mission_raw['current_mission'] = 1;
				$this->mission_raw['missions_data'] = 'a:0:{}';
				$this->mission_raw['minimissions'] = 'a:0:{}';
			}
			
			$this->missions_data = unserialize($this->mission_raw['missions_data']);
			$this->current_mission = $this->mission_raw['current_mission'];
			$this->current_mission_data = $this->config['missions'][$this->current_mission];
			
			if (empty($this->current_mission) || !$this->current_mission_data)
			{
				$this->has_mission = false;
			}
			
			$this->minimissions = unserialize($this->mission_raw['minimissions']);
			
			foreach ($this->minimissions as $key => $mini)
			{
				if ($mini['started']+$this->config['minimissions'][$key]['time_limit'] > time())
				{
					$this->minimissions[$key]['active'] = 1;
					$this->active_minimissions[] = $key;
				}
				else
				{
					if ($mini['active'] == 1)
					{
						$this->miniMission_failed($key);
					}
					
					$this->minimissions[$key]['active'] = 0;
				}
			}
		}
		
		public function miniMissions_save()
		{
			$this->db->Query("UPDATE `mission` SET `minimissions`='".serialize($this->minimissions)."' WHERE `id`='".$this->mission_raw['id']."'");
		}
		
		public function miniMission_failed($missionKey)
		{
			$this->minimissions[$missionKey]['active'] = 0;
			$this->minimissions[$missionKey]['data'] = array();
			$this->minimissions[$missionKey]['started'] = 0;
			$this->minimissions[$missionKey]['num_failed']++;
			$this->minimissions[$missionKey]['last_finished'] = time();
			
			$this->db->Query("UPDATE `mission` SET `minimissions`='".serialize($this->minimissions)."' WHERE `id`='".$this->mission_raw['id']."'");
			
			Accessories::AddLogEvent($this->player_data['id'], 44, array(), $this->player_data['userid']);
		}
		
		public function miniMission_success($missionKey)
		{
			$this->minimissions[$missionKey]['active'] = 0;
			$this->minimissions[$missionKey]['data'] = array();
			$this->minimissions[$missionKey]['started'] = 0;
			$this->minimissions[$missionKey]['num_completed']++;
			$this->minimissions[$missionKey]['last_finished'] = time();
			
			foreach ($this->config['minimissions'][$missionKey]['rewards'] as $key => $value)
			{
				$this->minimissions[$missionKey]['rewards'][$key] += $value;
			}
			
			$this->db->Query("UPDATE `mission` SET `minimissions`='".serialize($this->minimissions)."' WHERE `id`='".$this->mission_raw['id']."'");
			$this->db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$this->config['minimissions'][$missionKey]['rewards']['cash']."', `bullets`=`bullets`+'".$this->config['minimissions'][$missionKey]['rewards']['bullets']."', `rankpoints`=`rankpoints`+'".$this->config['minimissions'][$missionKey]['rewards']['rankpoints']."' WHERE `id`='".$this->player_data['id']."'");
			
			Accessories::AddLogEvent($this->player_data['id'], 45, array(
				'-MONEY-' => View::CashFormat($this->config['minimissions'][$missionKey]['rewards']['cash']),
				'-BULLETS-' => View::CashFormat($this->config['minimissions'][$missionKey]['rewards']['bullets'])
			), $this->player_data['userid']);
		}
		
		public function miniMission_start($missionKey)
		{
			if (!$this->config['minimissions'][$missionKey] || in_array($missionKey, $this->active_minimissions))
				return false;
			
			$this->minimissions[$missionKey]['active'] = 1;
			$this->minimissions[$missionKey]['started'] = time();
			
			if ($missionKey == 6)
				$this->minimissions[$missionKey]['data']['startRankpoints'] = $this->player_data['rankpoints'];
			
			$this->db->Query("UPDATE `mission` SET `minimissions`='".serialize($this->minimissions)."' WHERE `id`='".$this->mission_raw['id']."'");
			
			return true;
		}
		
		public function check_complete()
		{
			$mission = $this->current_mission;
			$mission_data = $this->missions_data[$mission];
			$mission_curent_data = $this->current_mission_data;
			
			if ($this->has_mission === false)
			{
				return 'ERROR:NO-MISSION';
			}
			elseif ($mission_data['started'] != 1)
			{
				return 'ERROR:NOT-STARTED';
			}
			else
			{
				$completed = true;
				foreach ($mission_data['objects'] as $object)
				{
					if ($object['completed'] != 1)
					{
						$completed = false;
						break;
					}
				}
				
				if ($completed === true)
				{
					$this->setMissionData('completed', time());
					$this->saveMissionData();
					
					$rewards = $this->current_mission_data['rewards'];
					
					$this->current_mission++;
					$this->db->Query("UPDATE `mission` SET `current_mission`='".$this->current_mission."' WHERE `id`='".$this->mission_raw['id']."'");
					
					Accessories::AddLogEvent($this->player_data['id'], 46, array(
						'-MISSION_NUM-' => ($this->current_mission - 1),
						'-MONEY-' => View::CashFormat($rewards['cash']),
						'-BULLETS-' => View::CashFormat($rewards['bullets'])
					), $this->player_data['userid']);
					
					$this->db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$rewards['cash']."', `points`=`points`+'".$rewards['points']."', `bullets`=`bullets`+'".$rewards['bullets']."', `rankpoints`=`rankpoints`+'".$rewards['rankpoints']."' WHERE `id`='".$this->player_data['id']."'");
				}
				
				return $completed;
			}
		}
		public function start_mission()
		{
			$mission = $this->current_mission;
			$mission_data = $this->missions_data[$mission];
			$mission_curent_data = $this->current_mission_data;
			
			if ($this->has_mission === false)
			{
				return 'ERROR:NO-MISSION';
			}
			elseif ($mission_data['started'] == 1)
			{
				return 'ERROR:IS-STARTED';
			}
			else
			{
				$this->setMissionData('started', 1);
				$this->setMissionData('start_time', time());
				$this->saveMissionData();
				
				return true;
			}
		}
		
		public function completeObject($object_id)
		{
			if ($this->has_mission === false)
			{
				return 'ERROR:NO-MISSION';
			}
			elseif ($this->missions_data[$this->current_mission]['started'] != 1)
			{
				return 'ERROR:NOT-STARTED';
			}
			
			if ($this->missions_data[$this->current_mission]['objects'][$object_id]['completed'] == 1)
			{
				return true;
			}
			
			$this->missions_data[$this->current_mission]['objects'][$object_id]['completed'] = 1;
			$this->db->Query("UPDATE `mission` SET `missions_data`='".serialize($this->missions_data)."' WHERE `id`='".$this->mission_raw['id']."'");
			
			return true;
		}
		
		public function setMissionData($key, $value)
		{
			if ($this->has_mission === false)
			{
				return 'ERROR:NO-MISSION';
			}
			
			$this->missions_data[$this->current_mission][$key] = $value;
			
			return true;
		}
		public function saveMissionData()
		{
			if ($this->has_mission === false)
			{
				return 'ERROR:NO-MISSION';
			}
			
			$this->db->Query("UPDATE `mission` SET `missions_data`='".serialize($this->missions_data)."' WHERE `id`='".$this->mission_raw['id']."'");
			
			return true;
		}
	}
?>