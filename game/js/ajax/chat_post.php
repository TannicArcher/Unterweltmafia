<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	require_once('../../../system/config.php');
	
	header('Content-type: text/plain');
	
	if (!IS_ONLINE)
	{
		$error = 'NOT-LOGGED-IN';
	}
	elseif ($config['limited_access'] == true)
	{
		$error = 'LIMITED-ACCESS';
	}
	
	if ($error)
	{
		die(json_encode(array('error' => $error)));
	}
	
	$text = $db->EscapeString($_GET['text']);
	
	if (View::Length($text) > 500)
	{
		echo 'No more than 500 characters';
	}
	elseif (View::Length($text) < 2)
	{
		echo 'At least 2 characters';
	}
	else
	{
		$db->Query("INSERT INTO `chat` (`player`, `text`, `time`)VALUES('".Player::Data('id')."', '".$text."', '".time()."')");
		$db->Query("UPDATE `[users]` SET `u_chat`='1' WHERE `online`+'3600'>'".time()."' AND (`u_chat`<'1' AND `chat_isOpen`='0')");
		
		echo json_encode(array(
			'player' => View::Player(Player::$datavar, true),
			'text' => nl2br(View::NoHTML(trim($text)))
		));
	}
?>