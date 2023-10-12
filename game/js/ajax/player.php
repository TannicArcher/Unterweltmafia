<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	require('../../../system/config.php');
	
	header('Content-type: text/plain');
	
	if (!IS_ONLINE)
		$error = 'NOT-LOGGED-IN';
	elseif ($config['limited_access'] == true)
		$error = 'LIMITED-ACCESS';
	
	if ($error) die(json_encode(array('error' => $error)));
	
	$params = $_GET;
	
	$player = $db->EscapeString($params['name']);
	$player = $db->QueryFetchArray("SELECT id,name,health,level FROM `[players]` WHERE `name`='".$player."' AND `level`>'0' AND `health`>'0'");

	if ($player['id'] == '')
		die(json_encode(array('error' => 'NO-FIND')));
	
	echo json_encode(array
	(
		'id' => $player['id'],
		'link' => View::Player($player)
	));
?>