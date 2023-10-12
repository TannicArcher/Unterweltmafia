<?php
	define('IS_AJAX', true);
	define(BASEPATH, true);
	require_once('../../../system/config.php');
	
	header($config['ajax_default_header']);
	
	if (!IS_ONLINE)
	{
		die('ERROR #1');
	}
	
	$db->Query("UPDATE `[users]` SET `chat_isOpen`='".(User::Data('chat_isOpen') == 1 ? 0 : 1)."', `u_chat`='0' WHERE `id`='".User::Data('id')."'");
	
	echo 'OK';
?>