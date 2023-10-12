<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	
	class View
	{
		private static $playerData = array();
		
		public static function Error($dir, $type, $ext)
		{
			$default_file_ext = '.html';
			if(! $ext ){ $ext = $default_file_ext; }
			include($dir . $type . $ext);
		}
		
		public static function Message($message, $type = 1, $reload = false, $reload_url, $box_id, $fontSize = 13)
		{
			$class   = $type == 1 ? 'green' : 'red';
			$box_id  = $box_id == "" ? '' : " id=\"$box_id\"";
			
			$message = "<div class=\"message_".$class."\"$box_id><h2 style=\"font-size: ".$fontSize."px;\">".$message."</h2></div>";
			
			if( $reload == false ){
				return $message;
				
			}else{
				$_SESSION['MZ_Messages'] = (isset($_SESSION['MZ_Messages']) && is_array($_SESSION['MZ_Messages'])) ? $_SESSION['MZ_Messages'] : array();
				$reload_url = empty($reload_url) ? $_SERVER['REQUEST_URI'] : $reload_url;
				$_SESSION['MZ_Messages'][] = $message;
				$header = @header("Location: ".$reload_url);
				if (!$header) echo 'ERROR in View::Message #1';
				exit;
			}
		}
		
		public static function Player($player, $nameonly = false, $errorMSG, $wheres = 1)
		{
			if (empty($player) || empty($player['id']))
			{
				return $errorMSG == '' ? '#' . $player['id'] . ' (Inexistent)' : $errorMSG;
			}
			
			$tmp = self::$playerData;
			
			if ($player['id'] == '' || $player['name'] == '' || $player['level'] == '' || $player['health'] == '')
			{
				if ($tmp[$player['id']])
				{
					$player = $tmp[$player['id']];
				}
				else
				{
				
					$where = ($wheres == 2 ? 'userid' : 'id');
					$null = ($wheres == 2 ? " AND `null`='0'" : "");
				
					$sql = mysql_query("SELECT id,name,vip_days,level,health FROM `[players]` WHERE `".$where."`='".$player['id']."'".$null."");
					$res = mysql_fetch_array($sql);
					
					$player['id'] = $res['id'];
					$player['name'] = $res['name'];
					$player['level'] = $res['level'];
					$player['health'] = $res['health'];
					$player['vip_days'] = $res['vip_days'];
					
					self::$playerData[$player['id']] = $player;
				}
			}
			else
			{
				self::$playerData[$player['id']]['id'] = $player['id'];
				self::$playerData[$player['id']]['name'] = $player['name'];
				self::$playerData[$player['id']]['level'] = $player['level'];
				self::$playerData[$player['id']]['health'] = $player['health'];
				self::$playerData[$player['id']]['vip_days'] = $player['vip_days'];
			}
			
			if ($player['id'] == '')
			{
				return $errorMSG == '' ? '#' . $player['id'] . ' (Inexistent)' : $errorMSG;
			}
			else
			{
				$href = $GLOBALS['config']['base_url'] . 's/' . urlencode($player['name']);
				$vip_pl = $player['vip_days'] > 0 && $player['level'] < 3 ? ' vip_pl' : '';
				
				if ($nameonly === true)
				{
					return '@<a href="' . $href . '" class="global_playerlink playerlink'.$vip_pl.'" rel="' . $player['name'] . '">' . $player['name'] . '</a>';
				}
				else
				{
					$colors = array(
						2 => 'sup',
						3 => 'mod',
						4 => 'admin',
						5 => 'ina'
					);
					$color = $colors[$player['level'] <= 0 || $player['health'] <= 0 ? 5 : $player['level']];
					$color = !$color ? '' : ' ' . $color;
					
					$name = $player['name'];
					/*if ($name == 'Google')
					{
						$name = '<span style="color: #3659cd;">G</span><span style="color: #d8172e;">o</span><span style="color: #fbae0f;">o</span><span style="color: #3659cd;">g</span><span style="color: #00a414;">l</span><span style="color: #d8172e;">e</span>';
						$color = '';
					}
					elseif ($name == 'Pikachu')
					{
						$name = '<span style="font-weight: bold; color: #e74b95;">Pikachu <span class="small">liker rumpeballer</span></span>';
						$color = '';
					}*/
					
					return '<a href="' . $href . '" class="playerlink player_link' . $color . ''.$vip_pl.'" rel="' . $name . '">' . $name . '</a>';
				}
			}
		}
		
		public static function CashFormat($str, $decimals = 0)
		{
			return is_numeric($str) ? number_format($str, $decimals, '.', ' ') : 0;
		}
		
		public static function NickAdd($userlevel)
		{
			$levels = array(1 => '', 2 => 'Suport', 3 => 'Moderator', 4 => 'Administrator');
			$colors = array(1 => '', 2 => 'ea7502', 3 => '04b6d0', 4 => 'a7d004');
			return !$levels[$userlevel] ? '' : '<span style="color: #595959;"><b>(</b>'.$levels[$userlevel].'<b>)</b></span>';
		}
		
		
		public static function NoHTML($str)
		{
			$str = htmlspecialchars($str);
			
			return $str;
		}
		
		public static function FixQuot($str)
		{
			return stripslashes(str_replace('"', '&quot;', $str));
		}
		
		public static function NoImages($str)
		{
			return preg_replace('/<img[^>]+\>/i', '', $str);
		}
		
		public static function Lenght($str)
		{
			return strlen(trim($str));
		}
		public static function Length($str){
			return self::Lenght($str);
		}
		
		public static function ValidPassword($password)
		{
			if (strlen($password) > 4 && strlen($password) < 24)
			{ 
				return true;
			}
			else
			{ 
				return false;
			}
		}
		
		public static function Time($timestamp, $month_big = false, $timeFormat = 'H:i:s', $showYear = true)
		{
			$months[false] = array("Ian", "Feb", "Mar", "Apr", "Mai", "Iun", "Iul", "Aug", "Sep", "Oct", "Nov", "Dec");
			$months[true]  = array("Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie", "Iulie", "August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie");
			
			$month = $months[$month_big][date("m", $timestamp)-1];
			
			$time  = @date($timeFormat, $timestamp);
			if (!$time)
				$time = '';
			
			if( date("j.m.Y", $timestamp) == date("j.m.Y") ){
				$result = "Azi la ora " . $time;
				
			}elseif( date("j.m.Y", $timestamp+86400) == date("j.m.Y") ){
				$result = "Ieri la ora " . $time;
				
			}elseif( date('j.m.Y', $timestamp) == date('j.m.Y', time() + 86400) ){
				$result = 'Maine la ora ' . $time;
				
			}else{
				$d = date("j", $timestamp);
				$y = date("Y", $timestamp);
				$result = "$d $month " . ($showYear === true ? $y : '') . " - $time";
			}
			
			return $result;
		}
		
		public static function strTime($s, $long = 0, $value_seperator = ' ', $bold = 1, $emptyMsg = 'Chiar acum')
		{
			$y = intval($s/31536000);
			$s -= $y*31536000;
			
			$w = intval($s/604800);
			$s -= $w*604800;
			
			$d = intval($s/86400);
			$s -= $d*86400;
			
			$h = intval($s/3600);
			$s -= $h*3600;
			
			$m = intval($s/60);
			$s -= $m*60;
			
			$values = array();
			$values['y'] = $y;
			$values['w'] = $w;
			$values['d'] = $d;
			$values['h'] = $h;
			$values['m'] = $m;
			$values['s'] = $s;
			
			$text_opts = array(
				'y' => array( array('a', 'a'), array('an', 'ani') ),
				'w' => array( array('s', 's'), array('saptamana', 'saptamani') ),
				'd' => array( array('z', 'z'), array('zi', 'zile') ),
				'h' => array( array('h', 'h'), array('ora', 'ore') ),
				'm' => array( array('m', 'm'), array('minut', 'minute') ),
				's' => array( array('s', 's'), array('secunda', 'secunde') ),
			);
			
			foreach( $text_opts as $id => $text )
			{
				$text_value = $values[$id];
				if( $text_value > 0 ){
					
					$result = $bold == 1 ? '<b>'.$text_value.'</b>' : $text_value;
					$label = $text_value > 1 ? $text[$long][1] : $text[$long][0];
					$space = $long == 1 ? ' ' : '';
					$label = $space.$label;
					$result .= $label;
					
					$seperator = $id == 's' ? '' : $value_seperator;
					$str .= $result . $seperator;
				}
			}
			
			return empty($str) ? $emptyMsg : trim($str);
		}
		
		public static function HowLongAgo($time)
		{
			$delta = time() - $time;
			if ($delta < 60) {
				return $delta . ' secunde in urma';
			} else if ($delta < 120) {
				return '~1 minut in urma';
			} else if ($delta < (45 * 60)) {
				return floor($delta / 60) . ' minute in urma';
			} else if ($delta < (90 * 60)) {
				return '~1 ora in urma';
			} else if ($delta < (24 * 60 * 60)) {
				return '~' . floor($delta / 3600) . ' ore in urma';
			} else if ($delta < (48 * 60 * 60)) {
				return 'Nu sunt informatii!';
			} else {
				return floor($delta / 86400) . ' zile in urma';
			}
		}
		
		public static function AsPercent($min, $max, $decimals = 2)
		{
			return round($min/$max * 100, $decimals);
		}
		
		public static function NumbersOnly($str, $decimals)
		{
			return floatval(round(str_replace(' ', '', $str), $decimals));
		}
		
		public static function DoubleSalt($toHash, $userid)
		{
			$password = str_split($toHash,(strlen($toHash)/2)+1);
			@$hash = hash('sha512', $userid.$password[0].'centerSalt'.$password[1]);
			return $hash;
		}
		
		public static function MoneyRank($money, $returnAsText = false)
		{
			global $config;
			
			$rank = false;
			
			foreach ($config['money_ranks'] as $key => $value)
			{
				if ($money <= $value[1] && $value[2] == 'less')
				{
					$rank = $key;
					break;
				}
				elseif ($money >= $value[1] && $value[2] == 'more')
				{
					$rank = $key;
					break;
				}
				else
				{
					if ($money >= $value[1] && $money <= $value[2])
					{
						$rank = $key;
						break;
					}
				}
			}
			
			return $returnAsText == true ? $config['money_ranks'][$key][0] : $key;
		}
		
		public static function IPLocation($IP)
		{
			return strtoupper(file_get_contents('http://api.hostip.info/country.php?ip=' . $IP));
		}
	}
?>