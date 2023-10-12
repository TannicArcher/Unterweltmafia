<?php
	define('IS_AJAX', true);
	define(BASEPATH, true);
	require_once('../../../system/config.php');
	
	header($config['ajax_default_header']);
	
	if (!IS_ONLINE)
    {
		die('ERROR #1');
	}
	
	$db->Query("UPDATE `[players]` SET `show_jail_logevents`='".(Player::Data('show_jail_logevents') == 1 ? 0 : 1)."' WHERE `id`='".Player::Data('id')."'");
?>