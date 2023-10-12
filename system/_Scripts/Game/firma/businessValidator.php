<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }


	$sql = $db->Query("SELECT id,type,misc,bank,place,job_1,job_2,name,deficit_start,image FROM `businesses` WHERE `active`='1'");
	while ($firma = $db->FetchArray($sql))
	{
		$firmatype = $config['business_types'][$firma['type']];
		
		$job_1 = $db->QueryFetchArray("SELECT id,userid FROM `[players]` WHERE `id`='".$firma['job_1']."' AND `health`>'0' AND `level`>'0'");

		$job_2 = $db->QueryFetchArray("SELECT id,userid FROM `[players]` WHERE `id`='".$firma['job_2']."' AND `health`>'0' AND `level`>'0'");

		
		if ($job_1['id'] == '' && $job_2['id'] != '')
		{
			$db->Query("UPDATE `businesses` SET `job_1`='".$job_2['id']."', `job_2`='0' WHERE `id`='".$firma['id']."'");
			
			Accessories::AddLogEvent($job_2['id'], 9, array(
				'-COMPANY_IMG-' => $firma['image'],
				'-COMPANY_NAME-' => $firma['name'],
				'-COMPANY_ID-' => $firma['id']
			), $job_2['userid']);
		}
		if ($job_2['id'] == '')
		{
			$db->Query("UPDATE `businesses` SET `job_2`='0' WHERE `id`='".$firma['id']."'");
		}
		if ($job_1['id'] == '' && $job_2['id'] == '')
		{
			$db->Query("UPDATE `businesses` SET `active`='0' WHERE `id`='".$firma['id']."'");
			$db->Query("UPDATE `stocks` SET `active`='0' WHERE `business_type`='game_business' AND `business_id`='".$firma['id']."'");
			
			continue;
		}
		
		if (!empty($firma['deficit_start']))
		{
			$timeleft = $firma['deficit_start']+$firmatype['max_deficit_length'] - time();
			
			if ($timeleft <= 0 && $firma['bank'] < 0)
			{
				$db->Query("UPDATE `businesses` SET `active`='0' WHERE `id`='".$firma['id']."'");
				$db->Query("UPDATE `stocks` SET `active`='0' WHERE `business_type`='game_business' AND `business_id`='".$firma['id']."'");
				
				Accessories::AddLogEvent($job_1['id'], 10, array(
					'-COMPANY_IMG-' => $firma['image'],
					'-COMPANY_NAME-' => $firma['name']
				), $job_1['userid']);
			}
			elseif ($firma['bank'] >= 0)
			{
				$db->Query("UPDATE `businesses` SET `deficit_start`='0' WHERE `id`='".$firma['id']."'");
			}
		}
		elseif ($firma['bank'] < 0)
		{
			$db->Query("UPDATE `businesses` SET `deficit_start`='".time()."' WHERE `id`='".$firma['id']."'");
		}
		
		$misc = unserialize($firma['misc']);
		$misc_changed = false;
		
		foreach ($firmatype['default_misc'] as $key => $value)
		{
			if ($misc[$key] == '' && !is_numeric($misc[$key]) && !is_array($misc[$key]))
			{
				$misc_changed = true;
				$misc[$key] = $value;
			}
		}
		
		foreach ($misc as $key => $value)
		{
			if ($firmatype['default_misc'][$key] == '' && !is_numeric($firmatype['default_misc'][$key]) && !is_array($firmatype['default_misc'][$key]))
			{
				$misc_changed = true;
				unset($misc[$key]);
			}
		}
		
		if ($misc_changed === true)
		{
			$db->Query("UPDATE `businesses` SET `misc`='".serialize($misc)."' WHERE `id`='".$firma['id']."'");
		}
	}
?>