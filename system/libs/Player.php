<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	
	class Player
	{
		
		public static $datavar;
		public static $familyDatavar;
		
		
		public static function UpdateData()
		{
			$sql = mysql_query("SELECT * FROM `[players]` WHERE `userid`='".$_SESSION['MZ_LoginData']['userid']."' AND `health`>'0' AND `level`>'0' ORDER BY id DESC LIMIT 1");
			self::$datavar = mysql_fetch_array($sql);
		}
		public static function Data($str)
		{
			return self::$datavar[$str];
		}
		
		public static function UpdateFamilyData()
		{
			$sql = mysql_query("SELECT * FROM `[families]` WHERE `id`='".self::Data('family')."'");
			$data = mysql_fetch_array($sql);
			
			$members = unserialize($data['members']);
			if (!$members[self::Data('id')])
			{
				mysql_query("UPDATE `[players]` SET `family`='0' WHERE `id`='".self::Data('id')."'");
				self::$datavar['family'] = 0;
				self::$familyDatavar = 'noData';
				return false;
			}
			
			self::$familyDatavar = $data;
		}
		public static function FamilyData($str)
		{
			if (self::Data('family') == 0)
			{
				return false;
			}
			else
			{
				if (empty(self::$familyDatavar))
				{
					self::UpdateFamilyData();
				}
				elseif (self::$familyDatavar == 'noData')
				{
					return false;
				}
				else
				{
					return self::$familyDatavar[$str];
				}
			}
		}
		
	}
?>