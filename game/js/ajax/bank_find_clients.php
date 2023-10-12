<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	include('../../../system/config.php');

	header('Content-type: application/json');

	if(!IS_ONLINE){
		$result['error'] = '1';
	}elseif($config['limited_access'] == true){
		$result['error'] = '2';
	}
	
	if ($result['error'])
		die(json_encode($result));
	
	$q = $db->EscapeString(trim($_GET['q']));
	$bank = $db->EscapeString($_GET['bank']);
	
	if (!isset($q) || !isset($bank))
		$result['error'] = '3';
	
	if ($result['error'])
		die(json_encode($result));
		
	
	$bank = $db->QueryFetchArray("SELECT id,job_1,job_2 FROM `businesses` WHERE `type`='1' AND `active`='1' AND `id`='".$bank."' LIMIT 1");

	if ($bank['id'] == '')
		$result['error'] = '4';
	
	if (!in_array(Player::Data('id'), array($bank['job_1'], $bank['job_2'])) && Player::Data('level') < 4)
		$result['error'] = '5';
	
	if ($result['error'])
		die(json_encode($result));
	
	
	$result['clients'] = array();
	
	$sql = $db->Query("SELECT id,name FROM `[players]` WHERE `name` LIKE '%".$q."%' AND `bank_id`='".$bank['id']."' ORDER BY name ASC");
	while ($client = $db->FetchArray($sql))
	{
		$result['clients'][] = array(
			'id' => $client['id'],
			'name' => preg_replace('/(?i)('.$q.')/', '<span style="color: #ff0000;">$1</span>', $client['name'])
		);
	}
	
	echo json_encode(count($result['clients']) <= 0 ? array('clients' => 'no_result') : $result);
?>