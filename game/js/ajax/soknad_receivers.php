<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	require('../../../system/config.php');

	header('Content-type: application/json');

	if(!IS_ONLINE){
		$resp['error'] = 'ERROR #1';
	}elseif($config['limited_access'] == true){
		$resp['error'] = 'ERROR #2';
	}
	
	$type = $db->EscapeString($_GET['type']);
	$type = $config['soknad_types'][$type];
	
	if (empty($type))
	{
		$resp['error'] = 'ERROR #3';
	}
	
	if ($resp['error'])
	{
		die(json_encode($resp));
	}

	$resp['receivers'] = array();
	
	if ($type == 'firma')
	{
		$firmaer = $db->QueryFetchArrayAll("SELECT id,name FROM `businesses` WHERE `active`='1' ORDER BY name ASC");

		foreach ($firmaer as $firma)
		{
			$resp['receivers'][] = array(
				'id' => $firma['id'],
				'name' => $firma['name']
			);
		}
	}
	
	echo json_encode($resp);
?>