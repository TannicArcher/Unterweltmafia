<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	class Accessories
	{
		public static function AddLogEvent($player, $type, $data, $user)
		{
			global $db, $config;
			
			$to_user = $db->EscapeString($user);
			$data = $db->EscapeString($data);
			$type = $db->EscapeString($type);
			$playerid = $db->EscapeString($player);
			
			
			if (!$playerid)
			{
				$sql = $db->Query("SELECT id FROM `[players]` WHERE `userid`='".$to_user."' ORDER BY id DESC LIMIT 0,1");
				$playerID = $db->FetchArray($sql);
				$playerID = $playerID['id'];
			}
			else
			{
				$playerID = $playerid;
				if (empty($to_user))
				{
					$sql = $db->Query("SELECT userid FROM `[players]` WHERE `id`='".$playerID."'");
					$player = $db->FetchArray($sql);
					$to_user = $player['userid'];
				}
			}
			
			$db->Query("INSERT INTO `" . $config['sql_logdb'] . "`.`logevents` (`user`, `player`, `type`, `data`, `time`)VALUES('".$to_user."', '".$playerID."', '".$type."', '".base64_encode(serialize($data))."', '".time()."')");
		}
		
		
		/*
		 * Returnerer straffen i sekunder
		*/
		public static function SetInJail($player, $extra_wanted, $penalty)
		{
			global $db, $config;
			
			$sql = $db->Query("SELECT id,`wanted-level`,jail_stats FROM `[players]` WHERE `id`='".$player."'");
			$player = $db->FetchArray($sql);
			
			if (empty($player))
				exit('Accessories::SetInJail$player - <b>Please report this error to admin!</b>');
			
			$penalty = !empty($penalty) ? $penalty : ($config['jail_penalty'][1]/100 * View::AsPercent($player['wanted-level']+$extra_wanted, $config['max_wanted-level'], 2));
			if ($penalty < $config['jail_penalty'][0])
				$penalty = $config['jail_penalty'][0];
			
			$penalty = round($penalty, 0);
			
			$db->Query("INSERT INTO `jail` (`player`, `added`, `penalty`)VALUES('".$player['id']."', '".time()."', '".$penalty."')") or die('SQL Error #1 in Accessories::SetInJail - <b>Vennlist rapporter til administrator og/eller administrator!</b>') or die('SQL Error #3 in Accessories::SetInJail - <b>Vennlist rapporter til administrator og/eller administrator!</b>');
			
			$jail_stats = unserialize($player['jail_stats']);
			$jail_stats['times_in_jail']++;
			$jail_stats['time_in_jail'] += $penalty;
			
			$db->Query("UPDATE `[players]` SET `jail_stats`='".serialize($jail_stats)."' WHERE `id`='".$player['id']."'") or die('SQL Error #2 in Accessories::SetInJail - <b>Vennlist rapporter til administrator og/eller administrator!</b>');
			
			return $penalty;
		}
		
		
		
		public static function AddToLog($playerid, $extra)
		{
			global $db, $config;
			$playerid = $db->EscapeString($playerid);
			$extra = $db->EscapeString($extra);
			
			$extra = is_array($extra) ? $extra : array();
			
			$db->Query("INSERT INTO `" . $config['sql_logdb'] . "`.`[log]` (`playerid`, `IP`, `timestamp`, `side`, `extra`)VALUES('".$playerid."', '".$_SERVER['REMOTE_ADDR']."', '".time()."', '".($GLOBALS['script_name'] == '404' && $_GET['side'] != '404' ? $_SERVER['PHP_SELF'] : $GLOBALS['script_name'])."', '".serialize($extra)."')");
		}
		
		
		// Lag en antibot økt
		public static function CreateAntibotSession($playerid, $script_name, $clearLastTry = true)
		{
			global $db;
			$config = $GLOBALS['config'];
			$images = $config['antibot_images'];
			
			// Lag image data
			
			$imageData = array();
			$SALT      = $playerid . '¨æØå^'.time().'85P^¤%' . $script_name;
			
			$numImages = $config['antibot_images_per_session'];
			$size      = sizeof($images);
			$num       = min(array($size, $numImages));
			
			$keys = array_keys($images);
			$used = array();
			
			for ($i = 0; $i < $num; $i++)
			{
				$r = rand(0, $size-1);
				while (array_search($keys[$r], $used) !== false) {
					$r = rand(0, $size-1);
				}
				array_push($used, $keys[$r]);
			}
			
			$selectText        = $used[rand(0, $num-1)];
			$correct_imageHash = sha1($selectText . $SALT);
			
			$imageData['text']   = '' . $selectText;
			$imageData['images'] = array();
			
			shuffle($used);
			for ($i = 0; $i < $config['antibot_images_per_session']; $i++)
			{
				array_push($imageData['images'], array(
					'hash' => sha1($used[$i] . $SALT),
					'file' => $images[$used[$i]] . rand(1, $config['antibot_images_per_title'])
				));
			}
			
			$imageData = json_encode($imageData);
			
			// Fjern gamle antibot økter
			$db->Query("UPDATE `antibot_sessions` SET `active`='0', `result`='2' WHERE `playerid`='$playerid' AND `script_name`='$script_name' AND `active`='1'") or die('Accessories::CreateAntibotSession SQL ERROR #1');
			
			$db->Query("INSERT INTO `antibot_sessions` (`playerid`, `script_name`, `images_data`, `correct_imageHash`, `added`)VALUES('".Player::Data('id')."', '$script_name', '$imageData', '$correct_imageHash', '".time()."')") or die('Accessories::CreateAntibotSession SQL ERROR #2');
			
			if ($clearLastTry === true)
				$db->Query("UPDATE `[players]` SET `antibot_last_try`='0' WHERE `id`='$playerid'") or die('Accessories::CreateAntibotSession SQL ERROR #3');
		}
		
		
		public static function ValidatePlayername($name)
		{
			$config = $GLOBALS['config'];
			
			$name = trim($name);
			$name = str_replace("\n", '', $name);
			$name = str_replace('  ', ' ', $name);
			
			return preg_match($config['playername_valid_regex'], $name) == 1 ? $name : false;
		}
		
		
	}
?>