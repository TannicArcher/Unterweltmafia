<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	
	$families = array();
	$sql = $db->Query("SELECT id,boss,underboss,image,members,territories,name,max_members_type,player_kills, (`total_rankpoints`/500+`strength`) as `strength`, place FROM `[families]` WHERE `active`='1'");
	while ($family = $db->FetchArray($sql))
	{
		$families[$family['id']] = $family;
	}	
	
	$businesses = array();
	$businesses_each_place = array();
	$sql = $db->Query("SELECT id,family,place,guards,guard_slots,name FROM `family_businesses`");
	while ($business = $db->FetchArray($sql))
	{
		$businesses_each_place[$business['place']]++;
		$businesses[$business['family']][$business['id']] = $business;
	}
	
	$players = array();
	$sql = $db->Query("SELECT id,family FROM `[players]` WHERE `family`!='0' AND `health`>'0' AND `level`>'0'");
	while ($player = $db->FetchArray($sql))
	{
		$players[$player['family']][$player['id']] = $player;
	}
	
	foreach ($families as $family)
	{
		$boss = $players[$family['id']][$family['boss']];
		$underboss = $players[$family['id']][$family['underboss']];
		
		if (!$boss && !$underboss)
		{
			$db->Query("UPDATE `[families]` SET `active`='0' WHERE `id`='".$family['id']."'");
			$db->Query("UPDATE `family_businesses` SET `bank_income`='0', `bank_loss`='0', `stats`='a:0:{}', `guard_slots`='2', `family`='0', `guards`='a:0:{}' WHERE `family`='".$family['id']."'");
			$db->Query("UPDATE `[players]` SET `family`='0' WHERE `family`='".$family['id']."'");
			
			foreach (unserialize($family['members']) as $member)
			{
				Accessories::AddLogEvent($member['player'], 1, array(
					'-FAMILY_IMG-' => $family['image'],
					'-FAMILY_NAME-' => $family['name']
				));
			}
			
			continue;
		}
		elseif ($family['strength'] <= 0)
		{
			$db->Query("UPDATE `[families]` SET `active`='0' WHERE `id`='".$family['id']."'");
			$db->Query("UPDATE `family_businesses` SET `bank_income`='0', `bank_loss`='0', `stats`='a:0:{}', `guard_slots`='2', `family`='0', `guards`='a:0:{}' WHERE `family`='".$family['id']."'");
			$db->Query("UPDATE `[players]` SET `family`='0' WHERE `family`='".$family['id']."'");
			
			foreach (unserialize($family['members']) as $member)
			{
				Accessories::AddLogEvent($member['player'], 2, array(
					'-FAMILY_IMG-' => $family['image'],
					'-FAMILY_NAME-' => $family['name']
				));
			}
			
			continue;
		}
		elseif (!$boss && $underboss)
		{
			$db->Query("UPDATE `[families]` SET `boss`=`underboss`, `underboss`='' WHERE `id`='".$family['id']."'");
			
			Accessories::AddLogEvent($underboss['id'], 3, array(
				'-FAMILY_IMG-' => $family['image'],
				'-FAMILY_NAME-' => $family['name'],
				'-FAMILY_ID-' => $family['id']
			), $underboss['userid']);
		}
		elseif ($boss['id'] == $underboss['id'])
		{
			$db->Query("UPDATE `[families]` SET `underboss`='' WHERE `id`='".$family['id']."'");
			$families[$family['id']]['underboss'] = '';
			unset($underboss);
		}
		else
		{
			$members = unserialize($family['members']);
			foreach ($members as $key => $member)
			{
				if ($member['player'] == $family['boss'] || $member['player'] == $family['underboss'])
				{
					continue;
				}
				
				$m_change = false;
				if (!$players[$family['id']][$member['player']])
				{
					unset($members[$key]);
					$m_change = true;
				}
			}
			
			if ($m_change === true)
			{
				$db->Query("UPDATE `[families]` SET `members`='".serialize($members)."' WHERE `id`='".$family['id']."'");
			}
		}
		
		$each_place = array();
		foreach ($businesses[$family['id']] as $business)
		{
			$guards = unserialize($business['guards']);
			
			if (count($guards) <= 0)
			{
				$db->Query("UPDATE `family_businesses` SET `bank_income`='0', `bank_loss`='0', `stats`='a:0:{}', `guard_slots`='2', `family`='0', `guards`='a:0:{}' WHERE `id`='".$business['id']."'");
				$db->Query("INSERT INTO `family_log` (`family`, `type`, `text`, `added`, `access_level`)VALUES('".$family['id']."', 'lost_business', 'The family lost &laquo;".$business['name']."&raquo;', '".time()."', '1')");
				$db->Query("UPDATE `[families]` SET `strength`=`strength`-'200' WHERE `id`='".$family['id']."'");
				
				continue;
			}
			
			if (count($guards) > $business['guard_slots'])
			{
				$guards = array_slice($guards, 0, $business['guard_slots']);
			}
			
			$change = false;
			foreach ($guards as $id => $guard)
			{
				if (!$players[$family['id']][$guard['player']])
				{
					unset($guards[$id]);
					$change = true;
				}
			}
			
			if ($change === true)
			{
				$db->Query("UPDATE `family_businesses` SET `guards`='".serialize($guards)."' WHERE `id`='".$business['id']."'");

			}
			
			$each_place[$business['place']]++;
		}
		
		$territories = unserialize($family['territories']);
		$terr_new = array();
		foreach ($businesses_each_place as $key => $value)
		{
			if ($value == $each_place[$key])
			{
				$terr_new[] = $key;
			}
		}
		
		if ($terr_new != $territories)
		{
			$db->Query("UPDATE `[families]` SET `territories`='".serialize($terr_new)."' WHERE `id`='".$family['id']."'");
		}
	}
?>