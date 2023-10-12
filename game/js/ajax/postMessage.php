<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	require_once('../../../system/config.php');
	
	header($config['ajax_default_header']);

	if(!IS_ONLINE){
		die(View::Message('You must be loggedin!', 2));
	}elseif( $config['limited_access'] == true ){
		die(View::Message('Your access to this game was limited', 2));
	}
	
	$text = $db->EscapeString($_POST['text']);
	$id = $db->EscapeString($_POST['id']);
	
	if (View::Lenght($text) < $config['message_reply_min_chars'])
	{
		die(View::Message('Message must have at least '.$config['message_reply_min_chars'].' characters.', 2));
	}
	
	$message = $db->QueryFetchArray("SELECT id,subject,from_user,from_player FROM `messages` WHERE `id`='$id' AND `to_user`='".User::Data('id')."' AND `deleted`='0'");

	if (empty($message['id']))
	{
		die(View::Message('This message doesn\'t exists!', 2));
	}
	
	if ($db->QueryGetNumRows("SELECT id FROM `contacts` WHERE `userid`='".$message['from_user']."' AND `contact_id`='".User::Data('id')."' AND `type`='2'") > 0)
	{
		die(View::Message('You was blocked by this player.', 2));
	}
	
	if ($db->QueryGetNumRows("SELECT id FROM `contacts` WHERE `userid`='".User::Data('id')."' AND `contact_id`='".$message['from_user']."' AND `type`='2'") > 0)
	{
		die(View::Message('You was blocked by this person.', 2));
	}
	
	$timeleft = Player::Data('message_last')+$config['message_latency'] - time();
	
	if ($timeleft > 0 && Player::Data('level') < 3)
	{
		die(View::Message('You have to wait <span class="countdown">'.$timeleft.'</span> seconds.', 2));
	}
	
	$db->Query("INSERT INTO `messages`
			   (`from_ip`, `from_user`, `from_player`, `to_user`, `to_player`, `subject`, `text`, `sent`, `is_reply`)
			   VALUES
			   ('".$_SERVER['REMOTE_ADDR']."', '".User::Data('id')."', '".Player::Data('id')."', '".$message['from_user']."', '".$message['from_player']."', '".$message['subject']."', '$text', '".time()."', '1')");
	
	$db->Query("UPDATE `[players]` SET `message_last`='".time()."', `messages_sent`=`messages_sent`+'1' WHERE `id`='".Player::Data('id')."'");
	
	echo 'SUCCESS';
?>