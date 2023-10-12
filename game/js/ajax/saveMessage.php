<?php
	define('IS_AJAX', true);
	define(BASEPATH, true);
	require_once('../../../system/config.php');
	
	header($config['ajax_default_header']);
	
	
	if(! IS_ONLINE ){
		die('ERROR #1');
		
	}elseif( $config['limited_access'] == true ){
		die('ERROR #2');
		
	}
	
	$id = $db->EscapeString($_GET['id']);
	
	if ($db->Query("UPDATE `messages` SET `saved`='1' WHERE `id`='$id' AND `to_user`='".User::Data('id')."' AND `saved`='0'"))
	{
		echo 'SUCCESS';
	}
	else
	{
		echo 'FAIL';
	}
?>