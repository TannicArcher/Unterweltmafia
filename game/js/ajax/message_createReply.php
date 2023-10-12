<?php
	define('IS_AJAX', true);
	define(BASEPATH, true);
	require_once('../../../system/config.php');
	
	header('Content-type: text/plain');
	
	$timeleft = Player::Data('message_last')+$config['message_latency'] - time();
	
	if (!IS_ONLINE)
		$error = 'NOT-LOGGED-IN';
	elseif ($config['limited_access'] == true)
		$error = 'LIMITED-ACCESS';
	elseif ($timeleft > 0 && Player::Data('level') < 3)
		$error = 'WAIT-TIME';
	
	if ($error) die(json_encode(array('error' => $error)));
	
	$params = $_POST;
	
	$text = $db->EscapeString($params['text']);
	if (View::Length($text) < $config['message_reply_min_chars'])
		die(json_encode(array('minChars' => $config['message_reply_min_chars'])));
	
	$message = $db->EscapeString($params['id']);
	$sql = $db->Query("SELECT id,views FROM `messages` WHERE `id`='".$message."' AND `players` LIKE '%|" . Player::Data('id') . "|%' AND `deleted`='0'");
	$message = $db->FetchArray($sql);
	
	if ($message['id'] == '')
		die(json_encode(array('error' => 'BAD-ID')));
	
	$db->Query("INSERT INTO `messages_replies`
			    (`message_id`, `text`, `creator`, `creator_ip`, `created`)
				VALUES
				('".$message['id']."', '".$text."', '".Player::Data('id')."', '".$_SERVER['REMOTE_ADDR']."', '".time()."')");
	
	$db->Query("UPDATE `[players]` SET `message_last`='".time()."', `messages_sent`=`messages_sent`+'1' WHERE `id`='".Player::Data('id')."'");
	
	$views = unserialize($message['views']);
	$views[Player::Data('id')] = time();
	$db->Query("UPDATE `messages` SET `views`='".serialize($views)."', `num_replies`=`num_replies`+'1', `last_reply`='".time()."', `last_player`='".Player::Data('id')."' WHERE `id`='".$message['id']."'");
	
	$bbText = new BBCodeParser($text, 'message_textfield', true);
	echo json_encode(array
	(
		'creator' => View::Player(Player::$datavar),
		'created' => View::Time(time(), false, 'H:i'),
		'text' => $bbText->result
	));
?>