<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	include('../../../system/config.php');

	header($config['ajax_default_header']);
	
	if (!IS_ONLINE){
		die('ERROR:#1');
	} elseif($config['limited_access'] == true){
		die('ERROR:#2');
	}
	
	
	$script    = $db->EscapeString($_GET['script']);
	$imageHash = $db->EscapeString($_GET['hash']);
	
	$ab = $db->QueryFetchArray("SELECT id,images_data,correct_imageHash FROM `antibot_sessions` WHERE `playerid`='".Player::Data('id')."' AND `script_name`='$script' AND `active`='1'");

	if ($ab['id'] == '')
	{
		die('REDIRECT:invalid_ab');
	}
	
	$timeleft = Player::Data('antibot_last_try')+$config['antibot_try_latency'] - time();
	
	if ($timeleft > 0)
	{
		echo 'You have to wait <span class="countdown">' . $timeleft . '</span> seconds!';
	}
	else
	{
		$db->Query("UPDATE `antibot_sessions` SET `active`='0', `result`='" . ($imageHash == $ab['correct_imageHash'] ? 1 : 0) . "' WHERE `id`='".$ab['id']."'");
		$db->Query("UPDATE `[players]` SET `antibot_last_try`='".time()."' WHERE `id`='".Player::Data('id')."'");

		$result = $imageHash == $ab['correct_imageHash'] ? 'SUCCESS' : 'FAIL';
		
		if ($result == 'FAIL')
		{
			Accessories::CreateAntibotSession(Player::Data('id'), $script, false);
		}
		
		die('REDIRECT:' . $result);
	}
?>