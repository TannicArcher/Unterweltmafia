<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$brekk = $db->QueryFetchArray("SELECT * FROM `brekk` WHERE `playerid`='".Player::Data('id')."'");
	
	if ($brekk['id'] == '')
	{
		$stats = array(
			'failed' => 0,
			'successfull' => 0,
			'conducted_each_place' => array(),
			'cash_earned' => 0,
			'rankpoints_earned' => 0,
			'wanted_level_earned' => 0
		);
		
		$db->Query("INSERT INTO `brekk` (`playerid`, `chances`, `stats`)VALUES('".Player::Data('id')."', '".serialize(array())."', '".serialize($stats)."')");
		
		header('Location: /game/?side=' . $_GET['side']);
		exit;
	}
	
	if (!isset($_SESSION['MZ_brekk_hash']))
	{
		$_SESSION['MZ_brekk_hash'] = 'MZ_' . substr(sha1(uniqid(rand())), 0, 10);
	}
	
	/*
		Jafuri
	*/
	$config['brekk_typer'] = array(
		1 => array(
			'icon' => 1,
			'title' => $langBase->get('jaf-11'),
			'min_rank' => 3,
			'places' => array(1),
			'earnings' => array(4000, 7500),
			'rankpoints' => array(
				'success' => array(6, 13),
				'fail' => array(3, 6)
			),
			'wanted_level' => array(
				'success' => array(20, 35),
				'fail' => array(15, 25)
			),
			'health_decrease' => array(
				'success' => array(0, 1),
				'fail' => array(0, 3)
			),
			'new_chance' => array(4, 14),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 155 : 210,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(120, 180),
			'bullets_min_chance' => 150,
			'bullets' => array(2, 4)
		),
		2 => array(
			'icon' => 2,
			'title' => $langBase->get('jaf-12'),
			'min_rank' => 4,
			'places' => array(1),
			'earnings' => array(6000, 10000),
			'rankpoints' => array(
				'success' => array(13, 15),
				'fail' => array(3, 6)
			),
			'wanted_level' => array(
				'success' => array(20, 30),
				'fail' => array(15, 20)
			),
			'health_decrease' => array(
				'success' => array(0, 1),
				'fail' => array(0, 4)
			),
			'new_chance' => array(1, 6),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 155 : 210,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(140, 180),
			'bullets_min_chance' => 150,
			'bullets' => array(2, 4)
		),
		3 => array(
			'icon' => 1,
			'title' => $langBase->get('jaf-13'),
			'min_rank' => 3,
			'places' => array(2),
			'earnings' => array(4000, 7500),
			'rankpoints' => array(
				'success' => array(8, 15),
				'fail' => array(3, 6)
			),
			'wanted_level' => array(
				'success' => array(20, 30),
				'fail' => array(15, 20)
			),
			'health_decrease' => array(
				'success' => array(0, 1),
				'fail' => array(0, 3)
			),
			'new_chance' => array(1, 6),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 155 : 210,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(140, 180),
			'bullets_min_chance' => 150,
			'bullets' => array(1, 3)
		),
		4 => array(
			'icon' => 1,
			'title' => $langBase->get('jaf-14'),
			'min_rank' => 4,
			'places' => array(2),
			'earnings' => array(6000, 10000),
			'rankpoints' => array(
				'success' => array(13, 16),
				'fail' => array(3, 6)
			),
			'wanted_level' => array(
				'success' => array(20, 30),
				'fail' => array(15, 20)
			),
			'health_decrease' => array(
				'success' => array(0, 1),
				'fail' => array(0, 3)
			),
			'new_chance' => array(5, 10),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 150 : 200,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(140, 180),
			'bullets_min_chance' => 150,
			'bullets' => array(1, 3)
		),
		5 => array(
			'icon' => 1,
			'title' => $langBase->get('jaf-15'),
			'min_rank' => 3,
			'places' => array(3),
			'earnings' => array(3000, 6000),
			'rankpoints' => array(
				'success' => array(8, 13),
				'fail' => array(3, 6)
			),
			'wanted_level' => array(
				'success' => array(20, 30),
				'fail' => array(15, 20)
			),
			'health_decrease' => array(
				'success' => array(0, 1),
				'fail' => array(0, 3)
			),
			'new_chance' => array(10, 15),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 120 : 160,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(140, 180),
			'bullets_min_chance' => 150,
			'bullets' => array(1, 3)
		),
		6 => array(
			'icon' => 1,
			'title' => $langBase->get('jaf-16'),
			'min_rank' => 4,
			'places' => array(3),
			'earnings' => array(6000, 10000),
			'rankpoints' => array(
				'success' => array(13, 16),
				'fail' => array(3, 6)
			),
			'wanted_level' => array(
				'success' => array(20, 30),
				'fail' => array(15, 20)
			),
			'health_decrease' => array(
				'success' => array(0, 2),
				'fail' => array(0, 5)
			),
			'new_chance' => array(5, 10),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 150 : 200,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(140, 180),
			'bullets_min_chance' => 150,
			'bullets' => array(1, 3)
		),
		7 => array(
			'icon' => 1,
			'title' => $langBase->get('jaf-17'),
			'min_rank' => 3,
			'places' => array(4),
			'earnings' => array(3500, 6000),
			'rankpoints' => array(
				'success' => array(8, 13),
				'fail' => array(3, 6)
			),
			'wanted_level' => array(
				'success' => array(20, 30),
				'fail' => array(15, 20)
			),
			'health_decrease' => array(
				'success' => array(0, 1),
				'fail' => array(0, 3)
			),
			'new_chance' => array(10, 15),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 120 : 160,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(140, 180),
			'bullets_min_chance' => 150,
			'bullets' => array(1, 3)
		),
		8 => array(
			'icon' => 1,
			'title' => $langBase->get('jaf-12'),
			'min_rank' => 4,
			'places' => array(4),
			'earnings' => array(5000, 8000),
			'rankpoints' => array(
				'success' => array(13, 16),
				'fail' => array(3, 6)
			),
			'wanted_level' => array(
				'success' => array(20, 30),
				'fail' => array(15, 20)
			),
			'health_decrease' => array(
				'success' => array(0, 2),
				'fail' => array(0, 4)
			),
			'new_chance' => array(5, 10),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 135 : 180,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(150, 180),
			'bullets_min_chance' => 150,
			'bullets' => array(1, 3)
		),
		9 => array(
			'icon' => 1,
			'title' => $langBase->get('jaf-18'),
			'min_rank' => 3,
			'places' => array(5),
			'earnings' => array(3000, 6000),
			'rankpoints' => array(
				'success' => array(8, 13),
				'fail' => array(3, 6)
			),
			'wanted_level' => array(
				'success' => array(20, 30),
				'fail' => array(15, 20)
			),
			'health_decrease' => array(
				'success' => array(0, 1),
				'fail' => array(0, 3)
			),
			'new_chance' => array(10, 15),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 120 : 160,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(160, 180),
			'bullets_min_chance' => 150,
			'bullets' => array(1, 3)
		),
		10 => array(
			'icon' => 1,
			'title' => $langBase->get('jaf-17'),
			'min_rank' => 4,
			'places' => array(5),
			'earnings' => array(4000, 8000),
			'rankpoints' => array(
				'success' => array(13, 16),
				'fail' => array(3, 6)
			),
			'wanted_level' => array(
				'success' => array(20, 30),
				'fail' => array(15, 20)
			),
			'health_decrease' => array(
				'success' => array(0, 1),
				'fail' => array(0, 4)
			),
			'new_chance' => array(5, 10),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 135 : 180,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(150, 180),
			'bullets_min_chance' => 150,
			'bullets' => array(1, 3)
		),
		11 => array(
			'icon' => 11,
			'title' => $langBase->get('jaf-19'),
			'min_rank' => 3,
			'places' => array(6, 8),
			'earnings' => array(500, 3000),
			'rankpoints' => array(
				'success' => array(8, 13),
				'fail' => array(3, 6)
			),
			'wanted_level' => array(
				'success' => array(20, 30),
				'fail' => array(15, 20)
			),
			'health_decrease' => array(
				'success' => array(0, 1),
				'fail' => array(0, 3)
			),
			'new_chance' => array(10, 15),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 115 : 150,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(160, 180),
			'bullets_min_chance' => 150,
			'bullets' => array(1, 3)
		),
		12 => array(
			'icon' => 10,
			'title' => $langBase->get('jaf-20'),
			'min_rank' => 4,
			'places' => array(6, 8),
			'earnings' => array(3000, 7000),
			'rankpoints' => array(
				'success' => array(13, 16),
				'fail' => array(3, 6)
			),
			'wanted_level' => array(
				'success' => array(20, 30),
				'fail' => array(15, 20)
			),
			'health_decrease' => array(
				'success' => array(0, 1),
				'fail' => array(0, 3)
			),
			'new_chance' => array(5, 10),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 135 : 180,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(150, 180),
			'bullets_min_chance' => 150,
			'bullets' => array(1, 3)
		),
		13 => array(
			'icon' => 1,
			'title' => $langBase->get('jaf-21'),
			'min_rank' => 3,
			'places' => array(7, 9),
			'earnings' => array(1000, 3500),
			'rankpoints' => array(
				'success' => array(8, 13),
				'fail' => array(3, 6)
			),
			'wanted_level' => array(
				'success' => array(20, 30),
				'fail' => array(15, 20)
			),
			'health_decrease' => array(
				'success' => array(0, 1),
				'fail' => array(0, 2)
			),
			'new_chance' => array(10, 15),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 115 : 150,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(160, 180),
			'bullets_min_chance' => 150,
			'bullets' => array(1, 3)
		),
		14 => array(
			'icon' => 1,
			'title' => $langBase->get('jaf-22'),
			'min_rank' => 4,
			'places' => array(7, 9),
			'earnings' => array(3000, 7000),
			'rankpoints' => array(
				'success' => array(13, 16),
				'fail' => array(3, 6)
			),
			'wanted_level' => array(
				'success' => array(20, 30),
				'fail' => array(15, 20)
			),
			'health_decrease' => array(
				'success' => array(0, 1),
				'fail' => array(0, 3)
			),
			'new_chance' => array(5, 10),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 135 : 180,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(150, 180),
			'bullets_min_chance' => 150,
			'bullets' => array(1, 3)
		),
		15 => array(
			'icon' => 3,
			'title' => $langBase->get('jaf-23'),
			'min_rank' => 1,
			'places' => array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'earnings' => array(125, 650),
			'rankpoints' => array(
				'success' => array(3, 7),
				'fail' => array(2, 4)
			),
			'wanted_level' => array(
				'success' => array(15, 30),
				'fail' => array(15, 20)
			),
			'health_decrease' => array(
				'success' => array(0, 0),
				'fail' => array(0, 1)
			),
			'new_chance' => array(13, 20),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 130 : 170,
			'jail_securityLevel' => 1,
			'jail_penalty' => array(120, 140),
			'bullets_min_chance' => 150,
			'bullets' => array(1, 3)
		),
		16 => array(
			'icon' => 4,
			'title' => $langBase->get('jaf-24'),
			'min_rank' => 1,
			'places' => array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'earnings' => array(50, 500),
			'rankpoints' => array(
				'success' => array(3, 7),
				'fail' => array(2, 4)
			),
			'wanted_level' => array(
				'success' => array(15, 17),
				'fail' => array(10, 15)
			),
			'health_decrease' => array(
				'success' => array(0, 0),
				'fail' => array(0, 1)
			),
			'new_chance' => array(13, 20),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 130 : 170,
			'jail_securityLevel' => 1,
			'jail_penalty' => array(120, 140),
			'bullets_min_chance' => 150,
			'bullets' => array(1, 3)
		),
		17 => array(
			'icon' => 5,
			'title' => $langBase->get('jaf-25'),
			'min_rank' => 5,
			'places' => array(1, 3, 5, 7, 9),
			'earnings' => array(7000, 11000),
			'rankpoints' => array(
				'success' => array(25, 35),
				'fail' => array(15, 25)
			),
			'wanted_level' => array(
				'success' => array(20, 35),
				'fail' => array(20, 30)
			),
			'health_decrease' => array(
				'success' => array(0, 2),
				'fail' => array(0, 5)
			),
			'new_chance' => array(1, 6),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 180 : 240,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(160, 200),
			'bullets_min_chance' => 150,
			'bullets' => array(1, 3)
		),
		18 => array(
			'icon' => 6,
			'title' => $langBase->get('jaf-26'),
			'min_rank' => 5,
			'places' => array(2, 4, 6, 8),
			'earnings' => array(7000, 11000),
			'rankpoints' => array(
				'success' => array(25, 35),
				'fail' => array(15, 25)
			),
			'wanted_level' => array(
				'success' => array(20, 35),
				'fail' => array(20, 30)
			),
			'health_decrease' => array(
				'success' => array(0, 2),
				'fail' => array(0, 5)
			),
			'new_chance' => array(1, 6),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 180 : 240,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(160, 200),
			'bullets_min_chance' => 150,
			'bullets' => array(1, 3)
		),
		19 => array(
			'icon' => 7,
			'title' => $langBase->get('jaf-27'),
			'min_rank' => 7,
			'places' => array(2, 4, 6, 8),
			'earnings' => array(15000, 20000),
			'rankpoints' => array(
				'success' => array(30, 40),
				'fail' => array(20, 30)
			),
			'wanted_level' => array(
				'success' => array(30, 45),
				'fail' => array(30, 40)
			),
			'health_decrease' => array(
				'success' => array(0, 2),
				'fail' => array(0, 6)
			),
			'new_chance' => array(1, 5),
			'max_chance' => array(270, 240),
			'wait_time' => Player::Data('vip_days') > 0 ? 225 : 300,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(200, 240),
			'bullets_min_chance' => 150,
			'bullets' => array(2, 6)
		),
		20 => array(
			'icon' => 8,
			'title' => $langBase->get('jaf-28'),
			'min_rank' => 7,
			'places' => array(1, 3, 5, 7, 9),
			'earnings' => array(15000, 20000),
			'rankpoints' => array(
				'success' => array(30, 40),
				'fail' => array(20, 30)
			),
			'wanted_level' => array(
				'success' => array(30, 45),
				'fail' => array(30, 40)
			),
			'health_decrease' => array(
				'success' => array(0, 2),
				'fail' => array(0, 6)
			),
			'new_chance' => array(1, 5),
			'max_chance' => array(270, 240),
			'wait_time' => Player::Data('vip_days') > 0 ? 225 : 300,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(200, 240),
			'bullets_min_chance' => 150,
			'bullets' => array(2, 6)
		),
		21 => array(
			'icon' => 9,
			'title' => $langBase->get('jaf-29'),
			'min_rank' => 9,
			'places' => array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'earnings' => array(50000, 75000),
			'rankpoints' => array(
				'success' => array(50, 60),
				'fail' => array(30, 40)
			),
			'wanted_level' => array(
				'success' => array(50, 65),
				'fail' => array(50, 60)
			),
			'health_decrease' => array(
				'success' => array(0, 3),
				'fail' => array(0, 7)
			),
			'new_chance' => array(1, 5),
			'max_chance' => array(240, 210),
			'wait_time' => Player::Data('vip_days') > 0 ? 375 : 500,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(270, 300),
			'bullets_min_chance' => 150,
			'bullets' => array(10, 20)
		),
		22 => array(
			'icon' => 7,
			'title' => $langBase->get('jaf-30'),
			'min_rank' => 11,
			'places' => array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'earnings' => array(75000, 100000),
			'rankpoints' => array(
				'success' => array(55, 65),
				'fail' => array(35, 45)
			),
			'wanted_level' => array(
				'success' => array(55, 70),
				'fail' => array(55, 75)
			),
			'health_decrease' => array(
				'success' => array(0, 4),
				'fail' => array(0, 8)
			),
			'new_chance' => array(1, 4),
			'max_chance' => array(210, 180),
			'wait_time' => Player::Data('vip_days') > 0 ? 525 : 700,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(300, 330),
			'bullets_min_chance' => 150,
			'bullets' => array(10, 20)
		),
		23 => array(
			'icon' => 1,
			'title' => $langBase->get('jaf-31'),
			'min_rank' => 4,
			'places' => array(8),
			'earnings' => array(3000, 7000),
			'rankpoints' => array(
				'success' => array(13, 16),
				'fail' => array(3, 6)
			),
			'wanted_level' => array(
				'success' => array(20, 30),
				'fail' => array(15, 20)
			),
			'health_decrease' => array(
				'success' => array(0, 1),
				'fail' => array(0, 3)
			),
			'new_chance' => array(5, 10),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 135 : 180,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(150, 180),
			'bullets_min_chance' => 150,
			'bullets' => array(1, 3)
		),
		24 => array(
			'icon' => 9,
			'title' => $langBase->get('jaf-32'),
			'min_rank' => 13,
			'places' => array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'earnings' => array(250000, 750000),
			'rankpoints' => array(
				'success' => array(70, 90),
				'fail' => array(45, 55)
			),
			'wanted_level' => array(
				'success' => array(65, 80),
				'fail' => array(65, 85)
			),
			'health_decrease' => array(
				'success' => array(0, 6),
				'fail' => array(0, 9)
			),
			'new_chance' => array(1, 4),
			'max_chance' => array(200, 170),
			'wait_time' => Player::Data('vip_days') > 0 ? 675 : 900,
			'jail_securityLevel' => 3,
			'jail_penalty' => array(400, 430),
			'bullets_min_chance' => 150,
			'bullets' => array(15, 25)
		),
		25 => array(
			'icon' => 9,
			'title' => $langBase->get('jaf-33'),
			'min_rank' => 14,
			'places' => array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'earnings' => array(500000, 1000000),
			'rankpoints' => array(
				'success' => array(75, 95),
				'fail' => array(50, 60)
			),
			'wanted_level' => array(
				'success' => array(70, 85),
				'fail' => array(70, 90)
			),
			'health_decrease' => array(
				'success' => array(0, 6),
				'fail' => array(0, 12)
			),
			'new_chance' => array(1, 3),
			'max_chance' => array(170, 140),
			'wait_time' => Player::Data('vip_days') > 0 ? 900 : 1200,
			'jail_securityLevel' => 3,
			'jail_penalty' => array(420, 450),
			'bullets_min_chance' => 150,
			'bullets' => array(25, 35)
		),
	);
	
	$brekk_chances = unserialize($brekk['chances']);
	$brekk_stats = unserialize($brekk['stats']);
	
	$last_crime = explode(",", $brekk['last']);
	$last_time = $last_crime[0];
	
	$latency = $last_time + $brekk['latency'] - time();
	
	if (isset($_POST['brekk_id']))
	{
		if ($_POST['hash'] !== substr($_SESSION['MZ_brekk_hash'], 3))
		{
			unset($_SESSION['MZ_brekk_hash']);
			View::Message('ERROR!', 2, true, '/game/?side=jafuri');
			exit;
		}
		
		$brekk_id = $db->EscapeString($_POST['brekk_id']);
		$brekk = $config['brekk_typer'][$brekk_id];
		
		if (!$brekk || !in_array(Player::Data('live'), $brekk['places']))
		{
			View::Message('ERROR', 2, true);
		}
		elseif ($latency > 0)
		{
			View::Message($langBase->get('cars-03', array('-TIME-' => $latency)), 2, true);
		}
		elseif (Player::Data('rank') < $brekk['min_rank'])
		{
			View::Message($langBase->get('cars-03', array('-TIME-' => $latency)), 2, true);
		}
		else
		{
			unset($_SESSION['MZ_brekk_hash']);
			
			$db->Query("UPDATE `brekk` SET `last`='".time().",".$config['places'][Player::Data('live')][0].",".$brekk_id."' WHERE `playerid`='".Player::Data('id')."'");

			$chance = $brekk_chances[$brekk_id] - (Player::Data('wanted-level') / 3);
			if($chance < 0) $chance = 0;
			
			if (rand(0, $brekk['max_chance'][0]) <= $chance)
			{
				$earned = rand($brekk['earnings'][0], $brekk['earnings'][1]);
				$rankPoints = rand($brekk['rankpoints']['success'][0], $brekk['rankpoints']['success'][1]);
				
				$healthDec = 0;
				if($config['health_decrease_option'] && rand(0, 1) == 1){
					$healthDec = rand($brekk['health_decrease']['success'][0], $brekk['health_decrease']['success'][1]);
				}
				
				$wanted_level  = Player::Data('wanted-level') + rand($brekk['wanted_level']['success'][0], $brekk['wanted_level']['success'][1]);
				$wanted_level  = $wanted_level > $config['max_wanted-level'] ? $config['max_wanted-level'] : $wanted_level;
				
				$bullets = 0;
				if ($chance >= $brekk['bullets_min_chance'])
				{
					$bullets = rand($brekk['bullets'][0], $brekk['bullets'][1]);
				}
				
				$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$earned."', `rankpoints`=`rankpoints`+'".$rankPoints."', `health`=`health`-'".$healthDec."', `wanted-level`='".$wanted_level."', `bullets`=`bullets`+'".$bullets."' WHERE `id`='".Player::Data('id')."'");
				
				$newChance = $brekk_chances[$brekk_id] + rand($brekk['new_chance'][0], $brekk['new_chance'][1]);
				$newChance = $newChance > $brekk['max_chance'][1] ? $brekk['max_chance'][1] : $newChance;
				$brekk_chances[$brekk_id] = $newChance;
				
				$brekk_stats['sucessfull']++;
				$brekk_stats['cash_earned'] += $earned;
				$brekk_stats['health_lost'] += $healthDec;
				$brekk_stats['rankpoints_earned'] += $rankPoints;
				$brekk_stats['wanted_level_earned'] += $wanted_level - Player::Data('wanted-level');
				$brekk_stats['conducted_each_place'][$config['places'][Player::Data('live')][0]]++;
				
				$db->Query("UPDATE `brekk` SET `latency`='".$brekk['wait_time']."', `chances`='".serialize($brekk_chances)."', `stats`='".serialize($brekk_stats)."' WHERE `playerid`='".Player::Data('id')."'");
				
				$log_data = array('result' => 'success', 'reward' => $earned, 'rankpoints' => $rankpoints, 'latency' => abs($latency));
				Accessories::AddToLog(Player::Data('id'), $log_data);
				
				$message = $langBase->get('jaf-08', array('-CASH-' => View::CashFormat($earned)));
				$messageType = 1;
				
				if ($bullets > 0)
				{
					$message .= $langBase->get('jaf-01', array('-BULLETS-' => View::CashFormat($bullets)));
				}
				
				if ($healthDec > 0)
				{
					$message .= ($bullets < 1 ? '. ' : ' ').$langBase->get('jaf-34', array('-PERCENT-' => View::AsPercent($healthDec, $config['max_health'], 2)));
				}
				
				if ($player_mission->current_mission == 1)
				{
					if ($player_mission_data['objects'][0]['completed'] != 1)
					{
						$places = $player_mission_data['objects'][0]['completed_places'];
						if (!in_array(Player::Data('live'), $places))
						{
							$places[] = Player::Data('live');
							$player_mission_data['objects'][0]['completed_places'] = $places;
							$player_mission->missions_data[$player_mission->current_mission]['objects'][0]['completed_places'] = $places;
							
							$player_mission->saveMissionData();
						}
						
						if (count($places) >= count($config['places']))
						{
							$player_mission->completeObject(0);
						}
					}
					
					if ($player_mission_data['objects'][1]['completed'] != 1)
					{
						$num = $player_mission_data['objects'][1]['num_completed'] + 1;
						$player_mission_data['objects'][1]['num_completed'] = $num;
						$player_mission->missions_data[$player_mission->current_mission]['objects'][1]['num_completed'] = $num;
						
						$player_mission->saveMissionData();
						
						if ($num >= 3)
						{
							$player_mission->completeObject(1);
						}
					}
				}
				
				if (in_array(2, $player_mission->active_minimissions))
				{
					$player_mission->minimissions[2]['data']['num']++;
					$player_mission->miniMissions_save();
					
					if ($player_mission->minimissions[2]['data']['num'] >= 8)
					{
						$player_mission->miniMission_success(2);
					}
				}
				elseif (in_array(3, $player_mission->active_minimissions))
				{
					$player_mission->minimissions[3]['data']['money'] += $earned;
					$player_mission->miniMissions_save();
					
					if ($player_mission->minimissions[3]['data']['money'] >= 100000)
					{
						$player_mission->miniMission_success(3);
					}
				}
			}
			else
			{
				$rankPoints = rand($brekk['rankpoints']['fail'][0], $brekk['rankpoints']['fail'][1]);
				$wanted_level  = rand($brekk['wanted_level']['fail'][0], $brekk['wanted_level']['fail'][1]);
				
				$healthDec = 0;
				if($config['health_decrease_option']){
					$healthDec = rand($brekk['health_decrease']['fail'][0], $brekk['health_decrease']['fail'][1]);
				}

				$newChance = $brekk_chances[$brekk_id] + rand($brekk['new_chance'][0], $brekk['new_chance'][1]);
				$newChance = $newChance > $brekk['max_chance'][1] ? $brekk['max_chance'][1] : $newChance;
				$brekk_chances[$brekk_id] = $newChance;
				
				$brekk_stats['failed']++;
				$brekk_stats['health_lost'] += $healthDec;
				$brekk_stats['rankpoints_earned'] += $rankPoints;
				$brekk_stats['wanted_level_earned'] += $wanted_level - Player::Data('wanted-level');
				$brekk_stats['conducted_each_place'][$config['places'][Player::Data('live')][0]]++;
				
				$db->Query("UPDATE `brekk` SET `latency`='".$brekk['wait_time']."', `chances`='".serialize($brekk_chances)."', `stats`='".serialize($brekk_stats)."' WHERE `playerid`='".Player::Data('id')."'");
				$db->Query("UPDATE `[players]` SET `rankpoints`=`rankpoints`+'".$rankPoints."', `health`=`health`-'".$healthDec."', `wanted-level`=`wanted-level`+'".$wanted_level."' WHERE `id`='".Player::Data('id')."'");
				
				$log_data = array('brekk_id' => $brekk_id, 'result' => 'fail', 'rankpoints' => $rankPoints, 'latency' => abs($latency));
				
				if (rand(0, $config['max_wanted-level']) <= Player::Data('wanted-level')+$wanted_level)
				{
					$penalty = Accessories::SetInJail(Player::Data('id'), $wanted_level);
					
					$log_data['jail_penalty'] = $penalty;
					
					$message = $langBase->get('jaf-09', array('-TIME-' => $penalty));
				}
				else
				{
					$message = $langBase->get('jaf-10');
				}
				
				if ($healthDec > 0)
				{
					$message .= ' '.$langBase->get('jaf-34', array('-PERCENT-' => View::AsPercent($healthDec, $config['max_health'], 2)));
				}
				
				$messageType = 2;
				
				Accessories::AddToLog(Player::Data('id'), $log_data);
				
				if ($player_mission->current_mission == 1)
				{
					if ($player_mission_data['objects'][1]['completed'] != 1)
					{
						$num = 0;
						$player_mission_data['objects'][1]['num_completed'] = $num;
						$player_mission->missions_data[$player_mission->current_mission]['objects'][1]['num_completed'] = $num;
						
						$player_mission->saveMissionData();
					}
				}
				
				View::Message($message, 2, true);
			}
			
			$abData = unserialize(Player::Data('antibot_data'));
			$abData['brekk']--;
			if ($abData['brekk'] <= 0)
			{
				$abData['brekk'] = rand($config['antibot_next_range']['brekk'][0], $config['antibot_next_range']['brekk'][1]);
				$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
				
				Accessories::CreateAntibotSession(Player::Data('id'), $db->EscapeString($_GET['side']));
				
				View::Message($message, $messageType, true);
			}
			$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
			
			View::Message($message, $messageType, true);
		}
	}
?>
<form method="post" action="" id="brekkForm">
	<input type="hidden" name="brekk_id" id="brekk_id" />
	<input type="hidden" name="hash" value="<?php echo substr($_SESSION['MZ_brekk_hash'], 3); ?>" />
	<div class="brekk_container">
        <div class="brekk_top">
            <div class="header"></div>
            <div class="infobox"><?=$langBase->get('jaf-02', array('-CITY-' => $config['places'][Player::Data('live')][0]))?> <?=($brekk['last'][0] == "" ? $langBase->get('jaf-03') : ($latency > 0 ? $langBase->get('cars-03', array('-TIME-' => $latency)) : $langBase->get('jaf-04', array('-CITY-' => $last_crime[1], '-TIME-' => View::Time($last_crime[0])))))?></div>
            <a href="<?=$config['base_url']?>?side=statistici_jaf" class="stats_link"><?=$langBase->get('function-brekk_stats')?></a>
        </div>
        <div class="hr"></div>
        <?php
		show_messages();
		
		foreach ($config['brekk_typer'] as $b_id => $brekk)
		{
			if (!in_array(Player::Data('live'), $brekk['places']))
				continue;
			
			$chance = $brekk_chances[$b_id] - (Player::Data('wanted-level') / 3);
			if($chance < 0) $chance = 0;
		?>
        <div class="brekk_boks<?php if($b_id == $last_crime[2]){ echo ' last_bg'; }?>">
            <?php if(Player::Data('rank') < $brekk['min_rank']):?><div class="brekk_overlay"><p class="text"><?=$langBase->get('jaf-06', array('-RANK-' => $config['ranks'][$brekk['min_rank']][0]))?></p></div><?php endif;?>
            <p class="icon"><img src="<?=$config['base_url']?>images/brekk/icon_<?=$brekk['icon']?>.png" alt="" /></p>
            <p class="info">
                <span><?=$brekk['title']?></span><br />
                <?=$langBase->get('jaf-07', array('-CASH1-' => View::CashFormat($brekk['earnings'][0]), '-CASH2-' => View::CashFormat($brekk['earnings'][1])))?> <span class="sep">|</span> <?=View::strTime($brekk['wait_time'])?> <?=$langBase->get('cars-07')?> <span class="sep">|</span> <b><?=View::AsPercent($chance, $brekk['max_chance'][0], 2)?> %</b> <?=$langBase->get('cars-08')?>
            </p>
            <a href="#" class="submit" onclick="$('brekk_id').set('value', '<?=$b_id?>'); $('brekkForm').submit(); return false;"><?=$langBase->get('jaf-05')?></a>
        </div>
        <?php
		}
		?>
    </div>
</form>