<?php
	define('IS_AJAX', true);
	define(BASEPATH, true);
	require_once('../../../system/config.php');
	
	header($config['ajax_default_header']);
	
	if(! IS_ONLINE ){
		die('ERROR #1');
		
	}
	
	
	$db->Query("UPDATE `[users]` SET `small_header`='".(User::Data('small_header') == 1 ? 2 : 1)."' WHERE `id`='".User::Data('id')."'");
?>