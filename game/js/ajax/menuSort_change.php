<?php
	define('IS_AJAX', true);
	define(BASEPATH, true);
	require_once('../../../system/config.php');
	
	header($config['ajax_default_header']);
	
	if (!IS_ONLINE)
	{
		die('ERROR #1');
	}
	
	$sort = explode(',', $db->EscapeString($_GET['sort']));
	$category = explode('_', $sort[0]);
	$category = $category[0];
	
	
	$i = 0;
	foreach ($sort as $value)
	{
		if (trim($value) == '')
			continue;
		
		$value = explode('_', $value);
		$value = $value[1];
		
		if (!$config['menu_links'][$category][$value])
		{
			$error = $category . '.' . $value;
			break;
		}
		else
		{
			$user_menuSort[$category][$i] = $value;
			$i++;
		}
	}
	
	if ($error)
	{
		echo 'ERROR:' . $error;
	}
	else
	{
		$db->Query("UPDATE `[users]` SET `menuSort`='".serialize($user_menuSort)."' WHERE `id`='".User::Data('id')."'");
		
		echo 'OK';
	}
?>