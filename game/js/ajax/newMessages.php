<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	require_once('../../../system/config.php');

	header('Content-type: application/json');

	$resp['time'] = date('H:i:s');

	if(!IS_ONLINE){
		$resp['error'] = 'ERROR #1';
	}elseif($config['limited_access'] == true){
		$resp['error'] = 'ERROR #2';
	}
	
	if ($resp['error'])
	{
		die( json_encode($resp) );
	}
	
	$messages = $db->QueryFetchArrayAll("SELECT id,from_player,subject,text,sent,saved,is_reply FROM `messages` WHERE `to_player`='".Player::Data('id')."' AND `read`='0' AND `deleted`='0' ORDER BY id ASC");

	if (count($messages) <= 0)
	{
		$resp['messages'] = 'empty';
		die(json_encode($resp));
	}
	
	$db->Query("UPDATE `messages` SET `read`='1' WHERE `to_player`='".Player::Data('id')."'");
	
	foreach ($messages as $message)
	{
		$text = new BBCodeParser($message['text'], 'message', true);
		$preText = new BBCodeParser(substr(trim(stripslashes($message['text'])), 0, 200));
		
		$resp['messages'][] = array(
			'id'           =>  $message['id'],
			'from_player'  =>  View::Player(array('id' => $message['from_player']), true),
			'subject'      =>  $message['subject'],
			'text_pre'     =>  str_replace("<br />", "\n", $preText->result),
			'text_full'    =>  $text->result,
			'sent'         =>  View::Time($message['sent'], 1),
			'saved'        =>  $message['saved'],
			'is_reply'     =>  $message['is_reply']
		);
	}
	
	echo json_encode($resp);
?>