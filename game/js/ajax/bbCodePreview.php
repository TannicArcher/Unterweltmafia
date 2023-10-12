<?php
	define('IS_AJAX', true);
	define(BASEPATH, true);
	require_once('../../../system/config.php');
	
	header($config['ajax_default_header']);
	
	
	if(! IS_ONLINE ){
		die('ERROR #1 - Trebuie sa fi conectat!');
	}
	
	
	$text = new BBCodeParser($_GET['text'], $_GET['place'], isset($_GET['smileys']) ? true : false);
	
	echo $text->result;
?>