<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	include('../../../system/config.php');

	header('Content-type: application/json');

	$script = $db->EscapeString($_GET['script']);
	$resp = array();
	
	if (!IS_ONLINE){
		$resp['error'] = '#1';
	} elseif($config['limited_access'] == true){
		$resp['error'] = '#2';
	} elseif(!isset($script)){
		$resp['error'] = '#3';
	}

	$ab = $db->QueryFetchArray("SELECT images_data FROM `antibot_sessions` WHERE `playerid`='".Player::Data('id')."' AND `script_name`='$script' AND `active`='1'");

	if (empty($ab['images_data'])) {
		$resp['error'] = '#4';
	}
	
	if ($resp['error']) {
		die( json_encode($resp) );
	}

	echo $ab['images_data'];
?>