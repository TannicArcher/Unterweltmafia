<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	class User
	{
		static $users_raw = array(),
		       $online_uid = false,
			   $datavar;
		
		function __construct($uid, $toSelect = array())
		{
			global $is_ajax;
			global $db;
			
			$this->is_ajax = $is_ajax;
			$this->db = $db;
			
			$this->uid = $this->db->EscapeString($uid);
			
			$this->raw = @self::$users_raw[$this->uid];
			if (!$this->raw)
			{
				if ($toSelect != '*')
				{
					$required = array('id', 'pass');
					foreach ($required as $value)
					{
						if (!in_array($value, $toSelect))
							$toSelect[] = $value;
					}
				}
				
				$toSelect = $this->db->EscapeString($toSelect);
				$sql = $this->db->Query("SELECT " . ($toSelect == '*' ? '*' : implode(',', $toSelect)) . " FROM `[users]` WHERE `id`='" . $this->uid . "'");
				$raw = $this->db->FetchArray($sql);
				
				$this->uid = @$raw['id'];
				$this->raw = $raw;
				self::$users_raw[$this->uid] = $this->raw;
			}
			
			self::$datavar = $this->raw;
		}
		
		public function getRaw($str)
		{
			return @$this->raw[$str];
		}
		public function setRaw($key, $value)
		{
			$this->raw[$key] = $value;
			self::$users_raw[$this->uid][$key] = $value;
		}
		
		public function is_online()
		{
			if ($this->online)
				return $this->online;
			
			if (isset($_SESSION['MZ_LoginData']))
			{
				$loginData = $_SESSION['MZ_LoginData'];
				
				if (empty($loginData['userid']) || empty($loginData['sid']) || empty($loginData['password']))
				{
					$this->logout('AL-1');
					return false;
				}
				else
				{
					$sql = $this->db->Query("SELECT id FROM `[sessions]` WHERE `Userid`='" . $this->getRaw('id') . "' AND `id`='" . $loginData['sid'] . "' AND `Last_updated`+`Expires`>'" . time() . "' AND `Active`='1'");
					$session = $this->db->FetchArray($sql);
					
					if ($session['id'] == '')
					{
						$this->logout('AL-2');
						return false;
					}
					else
					{
						if ($loginData['userid'] != $this->uid || $loginData['password'] != $this->getRaw('pass'))
						{
							$this->logout('AL-3');
							return false;
						}
						else
						{
							if (!$this->is_ajax)
							{
								$this->db->Query("UPDATE `[sessions]` SET `Last_updated`='" . time() . "' WHERE `id`='" . $loginData['sid'] . "'");
								$this->db->Query("UPDATE `[users]` SET `online`='" . time() . "', `last_active`='" . time() . "', `IP_last`='" . $_SERVER['REMOTE_ADDR'] . "' WHERE `id`='" . $this->uid . "'");
								$this->db->Query("UPDATE `[players]` SET `last_active`='" . time() . "', `online`='" . time() . "', `IP_last`='" . $_SERVER['REMOTE_ADDR'] . "' WHERE `userid`='" . $this->uid . "' AND `health`>'0' AND `level`>'0'");
							}
							
							self::$online_uid = $this->uid;
							
							$this->online = true;
							return true;
						}
					}
				}
			}
			else
			{
				$this->online = false;
				return false;
			}
		}
		
		public function logout($where)
		{
			global $config;
			$this->online = false;
			$loginData = $_SESSION['MZ_LoginData'];
			session_destroy();
			
			$this->db->Query("UPDATE `[users]` SET `online`='0' WHERE `id`='" . $this->uid . "'");
			$this->db->Query("UPDATE `[players]` SET `status`='0', `online`='0' WHERE `userid`='" . $this->uid . "' AND `health`>'0' AND `level`>'0'");
			$this->db->Query("DELETE FROM `[sessions]` WHERE `id`='" . $loginData['sid'] . "'");
			$this->db->Query("OPTIMIZE TABLE `[sessions]`");
			
			return $where;
		}

		static function Data($str)
		{
			return self::$users_raw[self::$online_uid][$str];
		}
	}
?>