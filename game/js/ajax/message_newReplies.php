<?php
	define('IS_AJAX', true);
	define(BASEPATH, true);
	require_once('../../../system/config.php');
	
	header('Content-type: text/plain');
	
	if (!IS_ONLINE)
		$error = 'NOT-LOGGED-IN';
	elseif ($config['limited_access'] == true)
		$error = 'LIMITED-ACCESS';
	
	if ($error) die(json_encode(array('error' => $error)));
	
	$params = $_GET;
	
	$message = $db->EscapeString($params['id']);
	$sql = $db->Query("SELECT id,views FROM `messages` WHERE `id`='".$message."' AND `players` LIKE '%|" . Player::Data('id') . "|%' AND `deleted`='0'");
	$message = $db->FetchArray($sql);
	
	if ($message['id'] == '')
		die(json_encode(array('error' => 'BAD-ID')));
	
	$views = unserialize($message['views']);
	$last_view = $views[Player::Data('id')];
	$views[Player::Data('id')] = time();
	$db->Query("UPDATE `messages` SET `views`='".serialize($views)."' WHERE `id`='".$message['id']."'");
	
	$messages = array();
	$sql = $db->Query("SELECT id,creator,created,text FROM `messages_replies` WHERE `message_id`='".$message['id']."' AND `deleted`='0' AND `created`>'".$last_view."' ORDER BY id ASC");
	while ($message = $db->FetchArray($sql))
	{
		$bbText = new BBCodeParser($message['text'], 'message_textfield', true);
		$messages[] = array(
			'creator' => View::Player(array('id' => $message['creator'])),
			'created' => View::Time($message['created'], false, 'H:i'),
			'text' => $bbText->result
		);
	}
	
	echo json_encode(array(
		'messages' => $messages
	));
?>