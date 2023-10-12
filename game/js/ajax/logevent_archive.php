<?php
	define('IS_AJAX', true);
	define(BASEPATH, true);
	require_once('../../../system/config.php');
	
	header($config['ajax_default_header']);
	
	if(! IS_ONLINE ){
		die('ERROR');
		
	}elseif( $config['limited_access'] == true ){
		die('ERROR');
	}
	
	
	$event = $db->EscapeString($_GET['id']);
	
	if ($db->Query("UPDATE `" . $config['sql_logdb'] . "`.`logevents` SET `archived`='1' WHERE `id`='$event' AND `user`='".User::Data('id')."' AND `archived`='0'"))
	{
		echo 'SUCCESS';
	}
	else
	{
		echo 'ERROR';
	}
?>