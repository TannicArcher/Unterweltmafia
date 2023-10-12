<?php
	define('IS_AJAX', true);
	define(BASEPATH, true);
	require('../../../system/config.php');
	
	header($config['ajax_default_header']);
	
	if(! IS_ONLINE ){
		die('ERROR #1');
		
	}elseif( $config['limited_access'] == true ){
		die('ERROR #2');
	}
	
	$firma = $db->EscapeString($_GET['b_id']);
	$sql = $db->Query("SELECT id,job_1,job_2,misc FROM `businesses` WHERE `id`='".$firma."' AND `active`='1' AND `type`='1'");
	$firma = $db->FetchArray($sql);
	$firmatype = $config['business_types'][1];
	
	if ($firma['id'] == '')
	{
		die('ERROR!');
	}
	elseif (!in_array(Player::Data('id'), array($firma['job_1'], $firma['job_2'])) && Player::Data('level') != 4)
	{
		die('ERROR!');
	}
	
	$classItems = json_decode(stripslashes($_GET['class']), true);
	
	if (count($classItems) < $firmatype['extra']['min_rente_classes'])
	{
		die('You have to add at least '.$firmatype['extra']['min_rente_classes'].' commission classes.');
	}
	elseif (count($classItems) > $firmatype['extra']['max_rente_classes'])
	{
		die('You can add maximum '.$firmatype['extra']['max_rente_classes'].' commission classes.');
	}
	
	$newClass = array();
	
	$i = 0;
	foreach ($classItems as $item)
	{
		$i++;
		$next = $classItems[$i];
		
		$from = View::NumbersOnly($item['from']);
		$to = View::NumbersOnly($item['to']);
		$percent = floatval(round($item['percent'], $firmatype['extra']['rente_max_decimals']));
		
		$next_from = View::NumbersOnly($next['from']);
		$next_to = View::NumbersOnly($next['from']);
		
		if ($from >= $to)
		{
			$error = 1;
		}
		elseif ($from <= 0 || $to <= 0)
		{
			$error = 2;	
		}
		elseif ($percent < $firmatype['extra']['min_rente'])
		{
			$error = 3;
		}
		elseif ($percent > $firmatype['extra']['max_rente'])
		{
			$error = 4;
		}
		elseif ($next && !(($from < $next_from && $from < $next_to) && ($to < $next_from && $to < $next_to)))
		{
			$error = 5;
		}
		
		if ($error)
		{
			$readableErrors = array(
				1 => '#1',
				2 => '#2',
				3 => '#3',
				4 => '#4',
				5 => '#5'
			);
			
			$hasError = '<b>ERROR:</b><br /><i>' . $readableErrors[$error] . '</i>';
			break;
		}
		else
		{
			$newClass[] = array(
				$from,
				$to,
				$percent
			);
		}
	}
	
	if ($hasError)
	{
		echo 'Error occured.<br />' . $hasError . '.';
	}
	else
	{
		$misc = unserialize($firma['misc']);
		$misc['rente_classes'] = $newClass;
		$db->Query("UPDATE `businesses` SET `misc`='".serialize($misc)."' WHERE `id`='".$firma['id']."'");
		
		echo 'Successfully saved!';
	}
?>