<?php
	/* Error Reporting */
	error_reporting(0);
	ini_set('display_errors', 0);

	/* Server configuration & optimisation */
	ini_set('implicit_flush', 1);
	ini_set('memory_limit', '64M');
	ini_set('mysql.connect_timeout', 5);
	ini_set('max_execution_time', 0);
	ini_set('session.cookie_httponly', true);

	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	session_start();
	ob_start();

	/* Set Charset */
	header('Content-type: text/html; charset=UTF-8');
	
	/*
	 * XSS Protection
	 */
	if (isset($_SESSION['HTTP_USER_AGENT']))
	{
		if ($_SESSION['HTTP_USER_AGENT'] !== sha1($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']))
		{
			session_destroy();
			header('Location: /');
			exit('SESS-ID');
		}
	}else{
		$_SESSION['HTTP_USER_AGENT'] = sha1($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
	}
	
	function check_redirect()
	{
		if (isset($_SESSION['MZ_Messages']) && count($_SESSION['MZ_Messages']) > 0)
		{
			return true;
		}
		
		return false;
	}
	
	$is_redirect = check_redirect();
	$is_ajax = defined('IS_AJAX') ? true : false;
		
	/*
		|-----------------------------------------|
		| MYSQL CONNECTION                        |
		|-----------------------------------------|
	*/
	require('database.php');
	$config['sql_logdb'] = $config['sql_database'];

	// Database class variable & Connect
	$config['sql_type'] = 'MySQL';		// MySQL or MySQLi
	require('libs/database/'.$config['sql_type'].'.php');
	$db = new MySQLConnection($config['sql_host'], $config['sql_username'], $config['sql_password'], $config['sql_database']);
	$db->Connect();
	
	unset($config['sql_password']);

	// Check if there is any update
	if(file_exists(realpath(dirname(__FILE__)).'/db_update.php')) {
		include('db_update.php');
	}
	
	/*
	 * LangBase
	*/
	require("langbase_inputs.php");
	require("libs/LangBase.php");
	$langBase_lang = 'RO';
	
	if ($languages_supported[$_COOKIE['MZ_Language']])
	{
		$langBase_lang = $_COOKIE['MZ_Language'];
	}
	
	$langBase = new LangBase($langBase_inputs, $langBase_lang);
	
	
	/*
		|-----------------------------------------|
		| INCLUDING CLASS LIBRARY                 |
		|-----------------------------------------|
	*/
	require("libs/User.php");
	require("libs/Player.php");
	require("libs/Accessories.php");
	require("libs/".$langBase_lang."/View.php");
	require("libs/".$langBase_lang."/Pagination.php");
	require("libs/".$langBase_lang."/BBCodeParser.php");
	require("libs/SQLOrder.php");
	require("libs/PlayingCardDeck.php");
	require("libs/Mission.php");

	/*
		admin_config
	*/
	$admin_config = array();
	
	$sql = $db->Query("SELECT config_name,config_value FROM `admin_config`");
	while ($con = $db->FetchArray($sql))
	{
		$admin_config[$con['config_name']] = array(
			'title' => $con['config_name'],
			'value' => $con['config_value']
		);
	}
	
	/* Crons */
	include("_Scripts/Cron/game_stats_update.php");
	include("_Scripts/Cron/rec_update_cron_daily.php");

	/*
		|-----------------------------------------|
		| IS THE USER ONLINE?  some options       |
		|-----------------------------------------|
	*/
	if (isset($_SESSION['MZ_LoginData']))
	{
		$online_user = new User($_SESSION['MZ_LoginData']['userid'], '*');
		$is_online = $online_user->is_online();
	}
	else
	{
		$is_online = false;
	}
	
	$config['game_url'] = $admin_config['game_url']['value'];
	if ($is_online)
	{
		$config['base_url'] = $config['game_url'].'/game/';
		define('IS_ONLINE', true);
		
		// Set User and Player variables etc.
		Player::UpdateData();
		Player::UpdateFamilyData();
		
		$config['limited_access'] = false;
		if( Player::Data('id') == "" || Player::Data('level') <= 0 || Player::Data('health') <= 0 || User::Data('userlevel') <= 0 )
			$config['limited_access'] = true;
	
		/*
		 * Update Health
		*/
		$config['max_health'] = 300;
		
		if ($is_redirect === false)
		{
			$health_inc_interval = (Player::Data('vip_days') > 0 ? 120 : 150);
			
			if (Player::Data('health') < $config['max_health'] && !isset($_SESSION['MZ_Health_Last_change'])) $_SESSION['MZ_Health_Last_change'] = time();
			$last_change = $_SESSION['MZ_Health_Last_change'];
			
			if ($last_change + $health_inc_interval < time() && Player::Data('health') < $config['max_health'])
			{
				$health_inc = rand(1,3);
				
				$multiple = round((time() - $last_change) / $health_inc_interval, 0);
				$multiple = $multiple <= 1 ? 1 : $multiple;
				if ($multiple > 5) $multiple = 5;
				
				$health_inc  = round($health_inc * $multiple, 0);
				
				$health_new  = Player::Data('health') + $health_inc;
				$health_new  = $health_new <= 0 ? 0 : $health_new;
				$health_new  = $health_new > $config['max_health'] ? $config['max_health'] : $health_new;
				
				if ($health_new != Player::Data('health'))
				{
					$db->Query("UPDATE `[players]` SET `health`='".$health_new."' WHERE `id`='".Player::Data('id')."'");
					Player::UpdateData();
				}
				
				$_SESSION['MZ_Health_Last_change'] = time();
				if($health_new >= $config['max_health']){
					unset($_SESSION['MZ_Health_Last_change']);
				}
			}
			else
			{
				if (Player::Data('health') > $config['max_health'])
				{
					$db->Query("UPDATE `[players]` SET `health`='".$config['max_health']."' WHERE `id`='".Player::Data('id')."'");
					Player::UpdateData();
				}
			}
		}

		/*
		 * Wanted level
		*/
		$config['max_wanted-level'] = 300;
		
		if ($is_redirect === false)
		{
			$wl_change_interval = (Player::Data('vip_days') > 0 ? 60 : 75);
			
			if (Player::Data('wanted-level') <= 0 || !isset($_SESSION['MZ_WL_Last_change'])) $_SESSION['MZ_WL_Last_change'] = time();
			$last_change = $_SESSION['MZ_WL_Last_change'];
			
			if ($last_change + $wl_change_interval < time() && Player::Data('wanted-level') > 0)
			{
				$wl_down = rand(20, 25);
				
				$multiple = round((time() - $last_change) / $wl_change_interval, 2);
				$multiple = $multiple <= 0 ? 1 : $multiple;
				if ($multiple > 5) $multiple = 5;
				
				$wl_down  = round($wl_down * $multiple, 0);
				
				$wl_new  = Player::Data('wanted-level') - $wl_down;
				$wl_new  = $wl_new <= 0 ? 0 : $wl_new;
				$wl_new  = $wl_new > $config['max_wanted-level'] ? $config['max_wanted-level'] : $wl_new;
				
				if ($wl_new != Player::Data('wanted-level'))
				{
					$db->Query("UPDATE `[players]` SET `wanted-level`='".$wl_new."' WHERE `id`='".Player::Data('id')."'");
					Player::UpdateData();
				}
				
				$_SESSION['MZ_WL_Last_change'] = time();
			}
			else
			{
				if (Player::Data('wanted-level') < 0)
				{
					$db->Query("UPDATE `[players]` SET `wanted-level`='0' WHERE `id`='".Player::Data('id')."'");
					Player::UpdateData();
				}
				elseif (Player::Data('wanted-level') > $config['max_wanted-level'])
				{
					$db->Query("UPDATE `[players]` SET `wanted-level`='".$config['max_wanted-level']."' WHERE `id`='".Player::Data('id')."'");
					Player::UpdateData();
				}
			}
		
			// VIP Update
			if (Player::Data('vip_days') < time())
			{
				$db->Query("UPDATE `[players]` SET `vip_days`='0' WHERE `id`='".Player::Data('id')."'");
			}
		}
	}
	else
	{
		$config['base_url'] = $config['game_url'].'/';
		define('IS_ONLINE', false);
	}
	
	/*
		|-----------------------------------------|
		| PLACES  places in the game              |
		|-----------------------------------------|
	*/
	$config['places'] = array(
		1  => array(
			'Oslo',
			array(
				'lat_lon' => array(59.916483, 10.722656),
				'px' => array(135, 245)
			)
		),
		2  => array(
			'Stockholm',
			array(
				'lat_lon' => array(59.338792, 18.072510),
				'px' => array(155, 345)
			)
		),
		3  => array(
			'Helsinki',
			array(
				'lat_lon' => array(60.179770, 24.927979),
				'px' => array(130, 430)
			)
		),
		4  => array(
			'Copenhaga',
			array(
				'lat_lon' => array(55.677584, 12.557373),
				'px' => array(244, 270)
			)
		),
		5  => array(
			'Amsterdam',
			array(
				'lat_lon' => array(52.375599, 4.877930),
				'px' => array(310, 165)
			)
		),
		6  => array(
			'Berlin',
			array(
				'lat_lon' => array(52.516221, 13.392334),
				'px' => array(306, 280)
			)
		),
		7  => array(
			'Varsovia',
			array(
				'lat_lon' => array(52.234528, 20.994873),
				'px' => array(310, 380)
			)
		),
		8  => array(
			'Minsk',
			array(
				'lat_lon' => array(53.547828, 27.346675),
				'px' => array(240, 430)
			)
		),
		9  => array(
			'Kiev',
			array(
				'lat_lon' => array(50.967828, 30.576675),
				'px' => array(340, 510)
			)
		)
	);
	
	/*
		Price for every KM
	*/
	$config['travelPrice_per_km'] = (Player::Data('vip_days') > 0 ? 20 : 26);
	
	/*
		Seconds for every KM
	*/
	$config['travel_latency_sec_per_km'] = (Player::Data('vip_days') > 0 ? 0.4 : 0.5);
	
	/*
		Price to reset waiting time
	*/
	$config['reset_travel_latency_price'] = 15;

	/*
		|-----------------------------------------|
		| RANKS all the ranks in the game         |
		|-----------------------------------------|
	*/
	$config['ranks'] = array(
		// Rank, Min. rankpoints, Max rankpoints, Rankup bullets, Max bullets to kill, Points
		1  => array('Neuling', 0, 350, 5, 500, 0),
		2  => array('Zänker', 350, 650, 20, 750, 2),
		3  => array('Dieb', 650, 1200, 25, 1200, 3),
		4  => array('Gangster', 1200, 2600, 30, 1650, 4),
		5  => array('Hitman', 2600, 7200, 50, 2500, 5),
		6  => array('Assassin', 7200, 15500, 75, 3000, 6),
		7  => array('Anführer', 15500, 32500, 100, 4500, 7),
		8  => array('Consigliere', 32500, 70000, 125, 6000, 8),
		9  => array('Caporegime', 70000, 125000, 150, 7500, 9),
		10 => array('Buchhalter', 125000, 220000, 200, 9500, 10),
		11 => array('Pate', 220000, 380000, 250, 14000, 11),
		12 => array('Don', 380000, 1000000, 300, 19000, 12),
		13 => array('Capo Crimini', 1000000, 3000000, 400, 23000, 15),
		14 => array('UWM Veteran', 3000000, 7500000, 500, 37500, 25),
		15 => array('Unterweltkönig', 5000000, 15000000, 600, 61000, 30)
	);

	$config['enlist_min_rank'] = 2;
	$config['enlist_reward_points'] = 20;
	
	// Minimum Level to reward the Affiliate
	$config['aff_min_rank'] = 3;
	
	// Check if affiliate module is installed
	$config['affiliate_module'] = false;
	if(file_exists(realpath(dirname(__FILE__)).'/modules/affiliate.php')) {
		$config['affiliate_module'] = true;
	}

	/*
		Check new rank
	*/
	if (!defined('IS_AJAX') && $is_redirect === false)
	{
		$newrank = '';
		foreach($config['ranks'] as $ranknum => $rank)
		{
			if ($ranknum == Player::Data('rank'))
			{
				if (Player::Data('rankpoints') < $rank['1'] && $ranknum > 1)
				{
					$newrank = $ranknum - 1;
				}
				elseif (Player::Data('rankpoints') > $rank['2'] && $ranknum < count($config['ranks']))
				{
					$newrank = $ranknum + 1;
				}
				else
				{
					$newrank = Player::Data('rank');
				}
			}
		}
		
		if ($newrank != Player::Data('rank'))
		{
			$db->Query("UPDATE `[players]` SET `rank`='".$newrank."' WHERE `id`='".Player::Data('id')."'");
			
			if ($newrank > Player::Data('rank'))
			{
				if ($config['ranks'][$newrank][3] > 0)
				{
					$db->Query("UPDATE `[players]` SET `points`=`points`+'".$config['ranks'][$newrank][5]."', `bullets`=`bullets`+'".$config['ranks'][$newrank][3]."' WHERE `id`='".Player::Data('id')."'");
				}
				
				Accessories::AddLogEvent(Player::Data('id'), 53, array(
					'-NEW_RANK-' => $config['ranks'][$newrank][0],
					'-BULLETS-' => View::CashFormat($config['ranks'][$newrank][3]),
					'-POINTS-' => $config['ranks'][$newrank][5]
				), User::Data('id'));
			}
			else
			{
				Accessories::AddLogEvent(Player::Data('id'), 34, array(
					'-NEW_RANK-' => $config['ranks'][$newrank][0]
				), User::Data('id'));
			}
			
			$sql = $db->Query("SELECT userid FROM `contacts` WHERE `contact_id`='".$online_user->uid."' AND `type`='1'");
			while($user = $db->FetchArray($sql))
			{
				if ($newrank > Player::Data('rank'))
				{
					Accessories::AddLogEvent('', 35, array(
						'-NEW_RANK-' => $config['ranks'][$newrank][0],
						'-PLAYER_NAME-' => Player::Data('name')
					), $user['userid']);
				}
				else
				{
					Accessories::AddLogEvent('', 36, array(
						'-NEW_RANK-' => $config['ranks'][$newrank][0],
						'-PLAYER_NAME-' => Player::Data('name')
					), $user['userid']);
				}
			}
			
			// Referal
			if ($newrank == $config['enlist_min_rank'] && User::Data('enlisted_by') != 0)
			{
				$e_player = $db->QueryFetchArray("SELECT id,userid,name,level FROM `[players]` WHERE `userid`='".User::Data('enlisted_by')."' AND `level`>'0' AND `health`>'0' ORDER BY id DESC LIMIT 1");

				if ($e_player['id'] != '')
				{
					$db->Query("UPDATE `[players]` SET `points`=`points`+'".$config['enlist_reward_points']."' WHERE `id`='".$e_player['id']."'");
					Accessories::AddLogEvent('', 30, array(
						'-POINTS-' => $config['enlist_reward_points'],
						'-PLAYER_NAME-' => Player::Data('name'),
						'-RANK-' => $config['ranks'][$config['enlist_min_rank']][0]
					), $e_player['userid']);
				}
			}

			if($config['affiliate_module']) {
				include('modules/affiliate.php');
			}
			
			Player::UpdateData();
		}
		
		/*
		 * Rankboost
		*/
		$rankboost = unserialize(Player::Data('rankboost'));
		
		// Rankbost done?
		if ($rankboost['ends'] <= time() && !empty($rankboost))
		{
			$rank_difference = Player::Data('rankpoints') - $rankboost['rank_start'];
			$extra_rank = round($rank_difference/100 * $rankboost['rank_percent'], 0);
			
			$db->Query("UPDATE `[players]` SET `rankpoints`=`rankpoints`+'".$extra_rank."', `rankboost`='a:0:{}' WHERE `id`='".Player::Data('id')."'");
			Player::UpdateData();
			
			$rank = $config['ranks'][Player::Data('rank')];
			Accessories::AddLogEvent(User::Data('id'), 'Extra-progresul s-a terminat. Ai primit <b>'.(View::AsPercent($extra_rank, $rank[2]-$rank[1], 4)).' %</b> progres nivel.', 24, '', Player::Data('id'));
		}
	}
	
	/*
		|-----------------------------------------|
		| Player Ranks			                  |
		|-----------------------------------------|
	*/
	$config['money_ranks'] = array(
		1  => array('Verschuldet', -1, 'less'),
		2  => array('Anfänger', 0, 9999),
		3  => array('Arbeiter', 10000, 99999),
		4  => array('Gangster', 100000, 999999),
		5  => array('Sammler', 1000000, 14999999),
		6  => array('Banker', 15000000, 99999999),
		7  => array('Millionär', 100000000, 999999999),
		8  => array('Multimillionär', 1000000000, 19999999999),
		9  => array('Milliardär', 20000000000, 99999999999),
		10 => array('Multimilliardär', 100000000000, 999999999999),
		11 => array('Der Boss', 1000000000000, 'more')
	);

	// Default content-type and charset.
	$config['ajax_default_header'] = "Content-type: text/plain; charset=utf-8";

	/*
		|-----------------------------------------|
		| MESSAGE OPTIONS                         |
		|-----------------------------------------|
	*/
	$config['message_latency'] = 1;
	$config['message_reply_min_chars'] = 2;
	$config['message_min_chars'] = 5;
	$config['message_title_min_chars'] = 5;
	$config['message_title_max_chars'] = 35;
	$config['message_max_receivers'] = 10;

	/*
		|-----------------------------------------|
		| EXTRAS                                 |
		|-----------------------------------------|
	*/
	$config['jail_min_breakout_reward'] = 5000;
	$config['jail_max_breakout_chance'] = 300;
	$config['jail_breakout_latency'] = 30;
	$config['jail_points_buyout_sum'] = 10;
	$config['crew_family'] = 'MN Crew';
	$config['jail_penalty'] = array(30, 360);
	$config['jail_brabe_max'] = 8000000;
	$config['profiletext_min_length'] = 0;
	$config['profiletext_max_length'] = 50000;
	
	$config['logevents'] = array(
		1 => 'Level UP',
		2 => 'Rank downgrade',
		3 => 'Broken out of jail',
		4 => 'New Player Account',
		5 => 'Added in contact list',
		6 => 'Added in block list',
		7 => 'Deleted from the blocked list',
		8 => 'Become the owner of a company',
		9 => 'Bankrupt company',
		10 => 'Registered bank account',
		11 => 'Disabled bank account',
		12 => 'Declined bank account setup',
		13 => 'Sacked from company',
		14 => 'Won this job in a company',
		15 => 'Received bank transfer',
		16 => 'Company auctioned',
		17 => 'Wins auction',
		18 => 'Family',
		19 => 'Newspaper Company',
		20 => 'Blackmail',
		21 => 'Street race',
		22 => 'Lottery',
		23 => 'Assassin',
		24 => 'Witness',
		25 => 'Rankboost',
		26 => 'Organized robbery',
		27 => 'Support',
		28 => '',
		29 => 'Missions',
		30 => 'Fights',
		31 => 'Marijuana'
	);
	
	// Other Settings
	$config['blackjack_game_expire'] = 600;
	$config['blackmail_min_rank'] = 3;
	$config['blackmail_money_range'] = array(2000, 10000);
	$config['blackmail_money_max_up'] = 5000;
	$config['blackmail_rankpoints_range'] = array(10, 35);
	$config['bordel_rankpoints_range'] = array(8, 20);
	$config['blackmail_wanted_range'] = array(30, 40);
	$config['blackmail_latency'] = Player::Data('vip_days') > 0 ? array(255, 320) : array(340, 420);
	$config['blackmail_penalty'] = array(150, 240);
	$config['blackmail_security_level'] = 3;
	
	$config['soknad_types'] = array(
		1 => 'firma'
	);
	
	$config['soknad_text_min_length'] = 20;
	$config['soknad_max_active'] = 5;

	$config['hospital_health_per_min'] = 10;
	$config['hospital_cost_per_min'] = Player::Data('vip_days') > 0 ? 112500 : 150000;
	
	$config['deactivate_reason_min_length'] = 10;
	
	// Horse Races
	$config['hs_ticket_price'] = 1000;
	$config['hs_max_tickets'] = 1000;
	$config['hs_round_time'] = 3600;
	$config['hs_buy_timestamp'] = 60;

	// Car thefts
	$config['car_thefts'] = array(
		1 => array(
			'title' => $langBase->get('c_fmsn-01'),
			'min_rank' => 3,
			'cars' => array(3, 4, 5, 6, 7, 8),
			'car_damage' => array(70, 180),
			'rankpoints' => array(
				'success' => array(15, 20),
				'fail' => array(5, 9)
			),
			'wanted_level' => array(
				'success' => array(25, 33),
				'fail' => array(23, 27)
			),
			'health_decrease' => array(
				'success' => array(0, 1),
				'fail' => array(0, 3)
			),
			'new_chance' => array(10, 15),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 260 : 350,
			'jail_securityLevel' => 4,
			'jail_penalty' => array(160, 200)
		),
		2 => array(
			'title' => $langBase->get('c_fmsn-02'),
			'min_rank' => 2,
			'cars' => array(3, 4, 5),
			'car_damage' => array(80, 170),
			'rankpoints' => array(
				'success' => array(11, 15),
				'fail' => array(4, 6)
			),
			'wanted_level' => array(
				'success' => array(22, 32),
				'fail' => array(20, 25)
			),
			'health_decrease' => array(
				'success' => array(0, 1),
				'fail' => array(0, 2)
			),
			'new_chance' => array(10, 16),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 225 : 300,
			'jail_securityLevel' => 3,
			'jail_penalty' => array(140, 180)
		),
		3 => array(
			'title' => $langBase->get('c_fmsn-03'),
			'min_rank' => 1,
			'cars' => array(1, 2),
			'car_damage' => array(20, 80),
			'rankpoints' => array(
				'success' => array(7, 15),
				'fail' => array(3, 6)
			),
			'wanted_level' => array(
				'success' => array(22, 32),
				'fail' => array(17, 22)
			),
			'health_decrease' => array(
				'success' => array(0, 1),
				'fail' => array(0, 2)
			),
			'new_chance' => array(10, 17),
			'max_chance' => array(300, 270),
			'wait_time' => Player::Data('vip_days') > 0 ? 175 : 230,
			'jail_securityLevel' => 2,
			'jail_penalty' => array(120, 160)
		),
		4 => array(
			'title' => $langBase->get('c_fmsn-04'),
			'min_rank' => 4,
			'cars' => array(9),
			'car_damage' => array(80, 180),
			'rankpoints' => array(
				'success' => array(20, 25),
				'fail' => array(8, 12)
			),
			'wanted_level' => array(
				'success' => array(35, 40),
				'fail' => array(25, 30)
			),
			'health_decrease' => array(
				'success' => array(0, 1),
				'fail' => array(0, 3)
			),
			'new_chance' => array(8, 12),
			'max_chance' => array(260, 230),
			'wait_time' => Player::Data('vip_days') > 0 ? 375 : 500,
			'jail_securityLevel' => 5,
			'jail_penalty' => array(200, 250)
		),
		5 => array(
			'title' => $langBase->get('c_fmsn-05'),
			'min_rank' => 6,
			'cars' => array(10, 11, 12),
			'car_damage' => array(85, 185),
			'rankpoints' => array(
				'success' => array(21, 26),
				'fail' => array(9, 13)
			),
			'wanted_level' => array(
				'success' => array(36, 41),
				'fail' => array(26, 31)
			),
			'health_decrease' => array(
				'success' => array(0, 2),
				'fail' => array(0, 5)
			),
			'new_chance' => array(8, 12),
			'max_chance' => array(260, 225),
			'wait_time' => Player::Data('vip_days') > 0 ? 405 : 540,
			'jail_securityLevel' => 5,
			'jail_penalty' => array(210, 260)
		),
		6 => array(
			'title' => $langBase->get('c_fmsn-06'),
			'min_rank' => 7,
			'cars' => array(13, 14, 15),
			'car_damage' => array(90, 180),
			'rankpoints' => array(
				'success' => array(22, 27),
				'fail' => array(9, 13)
			),
			'wanted_level' => array(
				'success' => array(37, 42),
				'fail' => array(27, 32)
			),
			'health_decrease' => array(
				'success' => array(0, 2),
				'fail' => array(0, 6)
			),
			'new_chance' => array(8, 12),
			'max_chance' => array(260, 224),
			'wait_time' => Player::Data('vip_days') > 0 ? 430 : 570,
			'jail_securityLevel' => 5,
			'jail_penalty' => array(220, 270)
		),
		7 => array(
			'title' => $langBase->get('c_fmsn-07'),
			'min_rank' => 8,
			'cars' => array(16, 17, 18, 22),
			'car_damage' => array(90, 180),
			'rankpoints' => array(
				'success' => array(20, 31),
				'fail' => array(8, 16)
			),
			'wanted_level' => array(
				'success' => array(35, 44),
				'fail' => array(25, 33)
			),
			'health_decrease' => array(
				'success' => array(0, 2),
				'fail' => array(0, 7)
			),
			'new_chance' => array(9, 14),
			'max_chance' => array(300, 250),
			'wait_time' => Player::Data('vip_days') > 0 ? 450 : 600,
			'jail_securityLevel' => 6,
			'jail_penalty' => array(210, 280)
		),
		8 => array(
			'title' => $langBase->get('c_fmsn-08'),
			'min_rank' => 9,
			'cars' => array(19, 20, 21),
			'car_damage' => array(90, 180),
			'rankpoints' => array(
				'success' => array(30, 40),
				'fail' => array(9, 20)
			),
			'wanted_level' => array(
				'success' => array(40, 50),
				'fail' => array(30, 36)
			),
			'health_decrease' => array(
				'success' => array(0, 3),
				'fail' => array(1, 7)
			),
			'new_chance' => array(9, 14),
			'max_chance' => array(300, 260),
			'wait_time' => Player::Data('vip_days') > 0 ? 540 : 720,
			'jail_securityLevel' => 6,
			'jail_penalty' => array(210, 300)
		)
	);

	// Max car damage
	$config['car_max_damage'] = 300;
	
	// Cars
	$config['cars'] = array(
		1 => array(
			'brand' => 'Think',
			'model' => 'City',
			'default_horsepowers' => 50,
			'price_per_hp' => 780
		),
		2 => array(
			'brand' => 'Fiat',
			'model' => 'Punto S75',
			'default_horsepowers' => 50,
			'price_per_hp' => 785
		),
		3 => array(
			'brand' => 'Volkswagen',
			'model' => 'Transporter',
			'default_horsepowers' => 88,
			'price_per_hp' => 790
		),
		4 => array(
			'brand' => 'Mitsubishi',
			'model' => 'Lancer 1,6 GLXi',
			'default_horsepowers' => 113,
			'price_per_hp' => 880
		),
		5 => array(
			'brand' => 'Mercedes-Benz',
			'model' => 'C-Klasse',
			'default_horsepowers' => 112,
			'price_per_hp' => 900
		),
		6 => array(
			'brand' => 'Mazda',
			'model' => 'RX-8',
			'default_horsepowers' => 192,
			'price_per_hp' => 900
		),
		7 => array(
			'brand' => 'BMW',
			'model' => 'M3 CSL',
			'default_horsepowers' => 360,
			'price_per_hp' => 910
		),
		8 => array(
			'brand' => 'Aston Martin',
			'model' => 'V8 Vantage',
			'default_horsepowers' => 384,
			'price_per_hp' => 920
		),
		9 => array(
			'brand' => 'Bugatti',
			'model' => 'Veyron',
			'default_horsepowers' => 1001,
			'price_per_hp' => 915
		),
		10 => array(
			'brand' => 'Ferrari',
			'model' => 'Vorsteiner',
			'default_horsepowers' => 570,
			'price_per_hp' => 860
		),
		11 => array(
			'brand' => 'Ferrari',
			'model' => 'Spider',
			'default_horsepowers' => 570,
			'price_per_hp' => 875
		),
		12=> array(
			'brand' => 'Ferrari',
			'model' => 'Fiorano',
			'default_horsepowers' => 612,
			'price_per_hp' => 885
		),
		13=> array(
			'brand' => 'Lamborghini',
			'model' => 'Gallardo',
			'default_horsepowers' => 513,
			'price_per_hp' => 900
		),
		14=> array(
			'brand' => 'Lamborghini',
			'model' => 'Aventador',
			'default_horsepowers' => 700,
			'price_per_hp' => 905
		),
		15=> array(
			'brand' => 'Lamborghini',
			'model' => 'Reventon',
			'default_horsepowers' => 650,
			'price_per_hp' => 885
		),
		16 => array(
			'brand' => 'BMW',
			'model' => 'M5',
			'default_horsepowers' => 340,
			'price_per_hp' => 900
		),
		17 => array(
			'brand' => 'Mercedes-Benz',
			'model' => 'SLR McLaren',
			'default_horsepowers' => 626,
			'price_per_hp' => 885
		),
		18 => array(
			'brand' => 'Cadillac',
			'model' => 'CTS',
			'default_horsepowers' => 420,
			'price_per_hp' => 900
		),
		19 => array(
			'brand' => 'Ford',
			'model' => 'Mustang',
			'default_horsepowers' => 260,
			'price_per_hp' => 900
		),
		20 => array(
			'brand' => 'Audi',
			'model' => 'RS6 Avant',
			'default_horsepowers' => 560,
			'price_per_hp' => 880
		),
		21 => array(
			'brand' => 'Aston Martin',
			'model' => 'One-77',
			'default_horsepowers' => 750,
			'price_per_hp' => 830
		),
		22 => array(
			'brand' => 'Audi',
			'model' => 'RS8',
			'default_horsepowers' => 560,
			'price_per_hp' => 915
		)
	);
	
	// Car sale fee
	$config['car_sale_transfer_fee'] = 5;
	
	$config['numbers_game_max_players'] = 10;
	$config['numbers_game_dices'] = 2;

	$config['numbers_game_round_expires'] = 3600;
	
	$config['auction_payment_methods'] = array(
		'cash' => array($langBase->get('ot-money'), '$'),
		'points' => array($langBase->get('ot-points'), 'C')
	);

	/*
		Support sections
	*/
	$config['support_tickets_categories'] = array(
		1 => $langBase->get('cats-01'),
		2 => $langBase->get('cats-02'),
		3 => $langBase->get('ot-points'),
		4 => $langBase->get('cats-03')
	);

	$config['marketplace_item_types'] = array(
		'points' => array(
			'title' => $langBase->get('ot-points')
		),
		'bullets' => array(
			'title' => $langBase->get('ot-bullets')
		)
	);

	// Price in coins for Coinroll
	$config['coinroll_price'] = 50;
	
	// Settings for Coinroll
	$config['coinroll_default_bank'] = 200000;		// Default money in bank
	$config['coinroll_default_max_bet'] = 20000;	// Default max bet
	$config['coinroll_min_max_bet'] = 500;			// Default Min bet
	
	// Price in coins for Scratch Tickets
	$config['lozuri_price'] = 50;
	
	// Lose life at robberies, car thefts, etc.
	$config['health_decrease_option'] = true;
	
	/*
		|-----------------------------------------|
		| Sscripts and options                    |
		|-----------------------------------------|
	*/
	
	$config['scripts'] = array(
		// 'script id' => 'title/heading', Disallow in jail, Min. userlevel, 'Folder', Show messages?, Smartmenu item?, Disallow in hospital, Disallow in bunker
		
		/*
			System scripts
		*/
		'404' => array($langBase->get('function-404'), false, 1, 'System', true, false, false, false),
		'error' => array($langBase->get('function-error'), false, 1, 'System', true, false, false, false),
		'limited_access' => array($langBase->get('function-limited_access'), false, 1, 'System', true, false, false, false),
		'in_jail' => array($langBase->get('function-in_jail'), false, 1, 'System', true, false, false, false),
		'antibot' => array('Antibot', false, 1, 'System', true, false, false, false),

		/*
			Game scripts
		*/
		'jafuri' => array($langBase->get('function-brekk'), true, 1, 'Game', false, true, true, true),
		'statistici_jaf' => array($langBase->get('function-brekk_stats'), false, 1, 'Game', false, true, false, false),
		'stiri' => array($langBase->get('function-news'), false, 1, 'Game', true, true, false, false),
		'changelog' => array($langBase->get('function-changelog'), false, 1, 'Game', true, false, false, false),
		'startside' => array($langBase->get('function-startpage'), false, 1, 'Game', true, true, false, false),
		'online_list' => array($langBase->get('function-online_list'), false, 1, 'Game', false, true, false, false),
		'membrii' => array($langBase->get('function-members_list'), false, 1, 'Game', true, true, false, false),
		'staff' => array($langBase->get('function-crew'), false, 1, 'Game', true, true, false, false),
		'spillerprofil' => array($langBase->get('function-playerprofile'), false, 1, 'Game', true, false, false, false),
		'inchisoare' => array($langBase->get('function-jail'), false, 1, 'Game', false, true, true, true),
		'rediger_profil' => array($langBase->get('function-edit_profile'), false, 1, 'Game', true, true, false, false),
		'cautare' => array($langBase->get('function-find_player'), false, 1, 'Game', true, true, false, false),
		'min_side' => array($langBase->get('function-headquarter'), false, 1, 'Game', true, true, false, false),
		'kontakter' => array($langBase->get('function-contacts'), false, 1, 'Game', true, true, false, false),
		'mesaje' => array($langBase->get('function-messages'), false, 1, 'Game', true, true, false, false),
		'cereri' => array($langBase->get('function-applications'), false, 1, 'Game', true, false, false, false),
		'banca' => array($langBase->get('function-bank'), false, 1, 'Game', true, true, false, false),
		'blackjack' => array($langBase->get('function-blackjack'), false, 1, 'Game', true, true, false, false),
		'harta' => array($langBase->get('function-map'), false, 1, 'Game', true, true, false, true),
		'numbers' => array($langBase->get('function-number_game'), false, 1, 'Game', true, true, false, false),
		'licitatii' => array($langBase->get('function-auction_house'), false, 1, 'Game', true, true, false, false),
		'statistici' => array($langBase->get('function-statistics'), false, 1, 'Game', true, false, false, false),
		'spital' => array($langBase->get('function-hospital'), true, 1, 'Game', true, true, false, true),
		'santaj' => array($langBase->get('function-blackmail'), true, 1, 'Game', false, true, true, true),
		'fura-masini' => array($langBase->get('function-carTheft'), true, 1, 'Game', false, true, true, true),
		'garaj' => array($langBase->get('function-garage'), true, 1, 'Game', false, true, true, true),
		'vanzari-masini' => array($langBase->get('function-carSales'), true, 1, 'Game', true, true, true, true),
		'curse' => array($langBase->get('function-streetrace'), false, 1, 'Game', false, true, false, false),
		'loterie' => array($langBase->get('function-lottery'), false, 1, 'Game', true, true, false, false),
		'asasin' => array($langBase->get('function-kill'), true, 1, 'Game', true, true, true, true),
		'drapsliste' => array($langBase->get('function-killList'), false, 1, 'Game', true, true, false, false),
		'armament' => array($langBase->get('function-killshop'), true, 1, 'Game', true, true, true, true),
		'magazin-credite' => array($langBase->get('function-pointshop'), false, 1, 'Game', true, true, false, false),
		'istoric-credite' => array($langBase->get('function-istcrd'), false, 1, 'Game', true, false, false, false),
		'transfer-credite' => array($langBase->get('function-transfer_points'), false, 1, 'Game', true, true, false, false),
		'jaf-organizat' => array($langBase->get('function-planned_crime'), true, 1, 'Game', true, true, true, true),
		'support' => array($langBase->get('function-support'), false, 1, 'Game', true, true, false, false),
		'faq' => array($langBase->get('function-faq'), false, 1, 'Game', true, true, false, false),
		'buncar' => array($langBase->get('function-bunker'), true, 1, 'Game', true, true, false, true),
		'bursa' => array($langBase->get('function-stocks'), true, 1, 'Game', true, true, true, true),
		'misiuni' => array($langBase->get('function-mission'), true, 1, 'Game', true, true, true, true),
		'locuinta' => array($langBase->get('function-property'), true, 1, 'Game', true, true, false, true),
		'lupte' => array($langBase->get('function-fighting'), true, 1, 'Game', true, true, false, true),
		'piata' => array($langBase->get('function-marketplace'), true, 1, 'Game', true, true, false, false),
		'moneda' => array($langBase->get('function-coinroll'), false, 1, 'Game', true, true, false, false),
		'guess' => array($langBase->get('function-guess'), false, 1, 'Game', true, true, false, false),
		'ruleta' => array($langBase->get('function-ruleta'), true, 1, 'Game', true, true, false, false),
		'roata-norocului' => array($langBase->get('function-rtnr'), true, 1, 'Game', true, true, false, false),
		'bannere' => array($langBase->get('function-bannere'), false, 1, 'Game', true, false, false, false),
		'sparge-seif' => array($langBase->get('function-sparge_seif'), false, 1, 'Game', true, true, false, false),
		'slot' => array($langBase->get('function-slots'), false, 1, 'Game', true, true, false, false),
		'c_name' => array($langBase->get('function-c_name'), true, 1, 'Game', true, false, false, false),
		'c_vip' => array($langBase->get('function-c_vip'), false, 1, 'Game', true, false, false, false),
		'srespect' => array('Respect', false, 1, 'Game', true, false, false, false),
		'lozuri' => array($langBase->get('function-lozuri'), true, 1, 'Game', true, false, false, false),
		'bordel' => array($langBase->get('function-bordel'), true, 1, 'Game', true, true, false, false),
		
		// Forum
		'forum/index' => array($langBase->get('function-forum_overview'), false, 1, 'Game', true, true, false, false),
		'forum/forum' => array('Forum', false, 1, 'Game', true, false, false, false),
		'forum/topic' => array($langBase->get('function-forum_topic'), false, 1, 'Game', true, false, false, false),
		'forum/reports' => array($langBase->get('function-forum_reports'), false, 3, 'Game', true, false, false, false),
		'forum/ban_player' => array($langBase->get('function-forum_ban_player'), true, 3, 'Game', true, false, false, false),
		'forum/banned_players' => array($langBase->get('function-forum_banned_players'), true, 3, 'Game', false, false, false, false),
		
		// Firma
		'firma/index' => array($langBase->get('function-companies_overview'), false, 1, 'Game', true, true, false, false),
		'firma/firma' => array($langBase->get('function-company'), false, 1, 'Game', true, false, false, false),
		'firma/panel' => array($langBase->get('function-company_panel'), false, 1, 'Game', true, false, false, false),
		'firma/avis' => array($langBase->get('function-newspaper'), false, 1, 'Game', true, false, false, false),
		'firma/lesavis' => array($langBase->get('function-read_newspaper'), false, 1, 'Game', true, false, false, false),
		'firma/journalistpanel' => array($langBase->get('function-journalist_panel'), false, 1, 'Game', true, false, false, false),
		
		// Firma
		'familie/index' => array($langBase->get('function-family_overview'), false, 1, 'Game', true, true, false, false),
		'familie/familie' => array($langBase->get('function-family'), false, 1, 'Game', true, false, false, false),
		'familie/m_panel' => array($langBase->get('function-family_membersPanel'), false, 1, 'Game', true, false, false, false),
		'familie/e_panel' => array($langBase->get('function-family_ownersPanel'), false, 1, 'Game', true, false, false, false),
		
		// Administrator / Moderator
		'game_panel/index' => array('Panou de Control', false, 3, 'Game', true, false, false, false),
		'game_panel/admin_config_edit' => array('Modificare Variabile', false, 4, 'Game', true, false, false, false),
		'game_panel/points' => array('Credite', false, 4, 'Game', true, false, false, false),
		'game_panel/stats_update' => array('Actualizare Statistici', false, 4, 'Game', true, false, false, false),
		'game_panel/stock_update' => array('Actualizare Bursa', false, 4, 'Game', true, false, false, false),
		'game_panel/user' => array('Benutzer-Bereich', false, 3, 'Game', true, false, false, false),
		'game_panel/player' => array('Spieler-Bereich', false, 3, 'Game', true, false, false, false),
		'game_panel/players' => array('Jucatori', false, 3, 'Game', true, false, false, false),
		'game_panel/news' => array('Noutati', false, 3, 'Game', true, false, false, false),
		'game_panel/logevents' => array('Jurnal Jucatori', false, 3, 'Game', true, false, false, false),
		'game_panel/edits_log' => array('Jurnal Modificari', false, 4, 'Game', true, false, false, false),
		'game_panel/messages' => array('Jurnal Mesaje', false, 3, 'Game', true, false, false, false),
		'game_panel/ip_lookup' => array('IP-Überprüfung', false, 3, 'Game', true, false, false, false),
		'game_panel/bulletsales' => array('Vanzari Gloante', false, 3, 'Game', true, false, false, false),
		'game_panel/vervekonk' => array('Concus de Referali', false, 4, 'Game', true, false, false, false),
		'game_panel/ads' => array('Reclame', false, 4, 'Game', true, false, false, false),
		'game_panel/voucher' => array('Vouchere', false, 4, 'Game', true, false, false, false)
	);
	
	$script_name = $db->EscapeString($_GET['side']);
	
	// '/' = '/index'
	$replaced = str_replace('/', '/index', $script_name);
	$script_name = in_array('index', explode('/', $replaced)) ? $replaced : $script_name;

	$current_script = $config['scripts'][$script_name];

	if (!defined('IS_AJAX'))
	{
		if ($script_name == '')
		{
			$script_name = 'startside';
		}
		elseif ($current_script[0] == '' || $current_script[3] == 'System')
		{
			$script_name = '404';
		}
		elseif ($config['limited_access'] == true){
			$script_name = 'limited_access';
		}
		elseif ($current_script[2] > User::Data('userlevel'))
		{
			$script_name = 'error';
		}
		elseif ($db->GetNumRows($db->Query("SELECT id FROM `antibot_sessions` WHERE `script_name`='".$script_name."' AND `active`='1' AND `playerid`='".Player::Data('id')."'")) > 0)
		{
			$script_name = 'antibot';
		}
		elseif ($current_script[1] == true && $db->GetNumRows($db->Query("SELECT id FROM `jail` WHERE `player`='".Player::Data('id')."' AND `added`+`penalty`>".time()." AND `active`='1'")) > 0)
		{
			$script_name = 'in_jail';
		}
		elseif ($current_script[7] === true)
		{
			$sql = $db->Query("SELECT id,last_session_ends FROM `bunker` WHERE `player`='".Player::Data('id')."'");
			$bunker = $db->FetchArray($sql);
			
			if ($bunker['last_session_ends']-time() > 0)
			{
				$script_name = 'buncar';
			}
			else
			{
				if (!empty($bunker['last_session_ends']))
				{
					$db->Query("UPDATE `bunker` SET `last_session_ends`='0' WHERE `id`='".$bunker['id']."'");
					
					View::Message($langBase->get('buncar-22'), 1, true);
				}
			}
		}
		elseif ($current_script[6] === true && Player::Data('hospital_data') != 'a:0:{}')
		{
			$script_name = 'spital';
		}
	}
	
	$config['scripts_path'] = '../system/_Scripts/';
	$config['scripts_ext']  = '.php';
	
	$current_script = $config['scripts'][$script_name];

	/*
		SUBMENUS - Submenus for each script (optional).
	*/
	$config['scripts_submenus'] = array(
		'statistici_jaf' => array(
			"<a href=\"".$config['base_url']."?side=jafuri\"".($_GET['side'] == 'jafuri' ? ' class="active"' : '')."><img src=\"".$config['base_url']."images/icons/utfor_brekk.png\" alt=\"\" /> ".$langBase->get('subMenu-01')."</a>"
		),

		'rediger_profil' => array(
			'<a href="'.$config['base_url'].'?side=rediger_profil&amp;a=profiletext"'.($_GET['a'] == 'profiletext' ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/page_edit.png" alt="" /><span style="font-size: 10px;">'.$langBase->get('subMenu-02').'</span></a>',
			'<a href="'.$config['base_url'].'?side=rediger_profil&amp;a=profileimage"'.($_GET['a'] == 'profileimage' ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/picture_edit.png" alt="" /><span style="font-size: 10px;">'.$langBase->get('subMenu-03').'</span></a>',
			'<a href="'.$config['base_url'].'?side=rediger_profil&amp;a=profilemusic"'.($_GET['a'] == 'profilemusic' ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/music.png" alt="" /><span style="font-size: 10px;">'.$langBase->get('subMenu-04').'</span></a>',
			'<a href="'.$config['base_url'].'?side=rediger_profil&amp;a=password"'.($_GET['a'] == 'password' ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/lock_edit.png" alt="" /><span style="font-size: 10px;">'.$langBase->get('subMenu-05').'</span></a>',
			'<a href="'.$config['base_url'].'?side=rediger_profil&amp;a=forum&amp;b=signatur"'.($_GET['a'] == 'forum' ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/note_edit.png" alt="" /><span style="font-size: 10px;">'.$langBase->get('subMenu-06').'</span></a>'
		),
		
		'min_side' => array(
			'<a href="'.$config['base_url'].'?side=min_side&amp;a=user&amp;b=main"'.($_GET['a'] == 'user' ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/folder_user.png" alt="" />'.$langBase->get('subMenu-07').'</a>',
			'<a href="'.$config['base_url'].'?side=min_side&amp;a=player&amp;b=main"'.($_GET['a'] == 'player' ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/user.png" alt="" />'.Player::Data('name').'</a>',
			'<a href="'.$config['base_url'].'?side=min_side&amp;a=ref&amp;b=main"'.($_GET['a'] == 'ref' ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/user_add.png" alt="" />'.$langBase->get('subMenu-08').'</a>'
		),
		
		'spillerprofil' => array(
			'<a href="'.$config['base_url'].'?side=srespect&amp;nick='.$_GET['name'].'"><img src="'.$config['base_url'].'images/icons/respect.png" alt="" />'.$langBase->get('subMenu-24').'</a>',
			'<a href="'.$config['base_url'].'?side=mesaje&amp;a=ny&amp;nick='.$_GET['name'].'"><img src="'.$config['base_url'].'images/icons/email_add.png" alt="" />'.$langBase->get('subMenu-09').'</a>',
			'<a href="'.$config['base_url'].'?side=kontakter&amp;a=new&amp;name='.$_GET['name'].'&amp;type=contact"><img src="'.$config['base_url'].'images/icons/user_add.png" alt="" />'.$langBase->get('subMenu-10').'</a>',
			'<a href="'.$config['base_url'].'?side=kontakter&amp;a=new&amp;name='.$_GET['name'].'&amp;type=block"><img src="'.$config['base_url'].'images/icons/user_delete.png" alt="" />'.$langBase->get('subMenu-11').'</a>'
		),
		
		'kontakter' => array(
			'<a href="'.$config['base_url'].'?side=kontakter&amp;a=contact"'.($_GET['a'] == 'contact' ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/user.png" alt="" />'.$langBase->get('subMenu-12').'</a>',
			'<a href="'.$config['base_url'].'?side=kontakter&amp;a=block"'.($_GET['a'] == 'block' ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/user_delete.png" alt="" />'.$langBase->get('subMenu-13').'</a>',
			'<a href="'.$config['base_url'].'?side=kontakter&amp;a=new"'.($_GET['a'] == 'new' ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/user_add.png" alt="" />'.$langBase->get('subMenu-14').'</a>'
		),
		
		'mesaje' => array(
			'<a href="'.$config['base_url'].'?side=mesaje&amp;a=innboks"'.($_GET['a'] == 'innboks' ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/email.png" alt="" />'.$langBase->get('txt-24').'</a>',
			'<a href="'.$config['base_url'].'?side=mesaje&amp;a=ny"'.($_GET['a'] == 'ny' ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/email_add.png" alt="" />'.$langBase->get('subMenu-15').'</a>'
		),
		
		'cereri' => array(
			'<a href="'.$config['base_url'].'?side=cereri"><img src="'.$config['base_url'].'images/icons/page_white_text.png" alt="" />'.$langBase->get('subMenu-17').'</a>',
			'<a href="'.$config['base_url'].'?side=cereri&amp;create"'.(isset($_GET['create']) ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/page_add.png" alt="" />'.$langBase->get('subMenu-16').'</a>'
			
		),
		
		'familie/index' => array(
			'<a href="'.$config['base_url'].'?side=familie/"'.(!isset($_GET['create']) && !isset($_GET['b']) && !isset($_GET['attacks']) ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/page_white_text.png" alt="" />'.$langBase->get('subMenu-18').'</a>',
			'<a href="'.$config['base_url'].'?side=familie/&amp;create"'.(isset($_GET['create']) && !isset($_GET['b']) ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/page_add.png" alt="" />'.$langBase->get('subMenu-19').'</a>',
			'<a href="'.$config['base_url'].'?side=familie/&amp;b"'.(isset($_GET['b']) && !isset($_GET['create']) ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/page_add.png" alt="" />'.$langBase->get('subMenu-20').'</a>',
			'<a href="'.$config['base_url'].'?side=familie/&amp;attacks"'.(isset($_GET['attacks']) ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/user_suit.png" alt="" />'.$langBase->get('subMenu-21').'</a>'
		),
		
		'asasin' => array(
			'<a href="'.$config['base_url'].'?side=armament"><img src="'.$config['base_url'].'images/icons/bullet_black.png" alt="" />'.$langBase->get('ot-bullets').'</a>',
			'<a href="'.$config['base_url'].'?side=drapsliste"><img src="'.$config['base_url'].'images/icons/table.png" alt="" />'.$langBase->get('subMenu-22').'</a>',
			'<a href="'.$config['base_url'].'?side=buncar"><img src="'.$config['base_url'].'images/icons/house.png" alt="" />'.$langBase->get('subMenu-23').'</a>'
		),
		'armament' => array(
			'<a href="'.$config['base_url'].'?side=asasin"><img src="'.$config['base_url'].'images/icons/bullet_black.png" alt="" />'.$langBase->get('function-kill').'</a>',
			'<a href="'.$config['base_url'].'?side=drapsliste"><img src="'.$config['base_url'].'images/icons/table.png" alt="" />'.$langBase->get('subMenu-22').'</a>',
			'<a href="'.$config['base_url'].'?side=buncar"><img src="'.$config['base_url'].'images/icons/house.png" alt="" />'.$langBase->get('subMenu-23').'</a>'
		),
		'buncar' => array(
			'<a href="'.$config['base_url'].'?side=asasin"><img src="'.$config['base_url'].'images/icons/bullet_black.png" alt="" />'.$langBase->get('function-kill').'</a>',
			'<a href="'.$config['base_url'].'?side=drapsliste"><img src="'.$config['base_url'].'images/icons/table.png" alt="" />'.$langBase->get('subMenu-22').'</a>',
			'<a href="'.$config['base_url'].'?side=armament"><img src="'.$config['base_url'].'images/icons/bullet_black.png" alt="" />'.$langBase->get('ot-bullets').'</a>'
		),
		'drapsliste' => array(
			'<a href="'.$config['base_url'].'?side=asasin"><img src="'.$config['base_url'].'images/icons/bullet_black.png" alt="" />'.$langBase->get('function-kill').'</a>',
			'<a href="'.$config['base_url'].'?side=buncar"><img src="'.$config['base_url'].'images/icons/house.png" alt="" />'.$langBase->get('subMenu-23').'</a>',
			'<a href="'.$config['base_url'].'?side=armament"><img src="'.$config['base_url'].'images/icons/bullet_black.png" alt="" />'.$langBase->get('ot-bullets').'</a>'
		),
		
		'moneda' => array(
			'<a href="'.$config['base_url'].'?side=moneda"'.(!isset($_GET['oversikt']) ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/coins.png" alt="" />'.$langBase->get('function-coinroll').'</a>',
			'<a href="'.$config['base_url'].'?side=moneda&amp;oversikt"'.(isset($_GET['oversikt']) ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/table.png" alt="" />'.$langBase->get('subMenu-17').'</a>'
		),
		
		'lozuri' => array(
			'<a href="'.$config['base_url'].'?side=lozuri"'.(!isset($_GET['general']) ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/coins.png" alt="" />'.$langBase->get('function-lozuri').'</a>',
			'<a href="'.$config['base_url'].'?side=lozuri&amp;general"'.(isset($_GET['general']) ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/table.png" alt="" />'.$langBase->get('subMenu-17').'</a>'
		),
		
		'magazin-credite' => array(
			'<a href="'.$config['base_url'].'?side=magazin-credite"'.($_GET['side'] == 'magazin-credite' ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/page_edit.png" alt="" />'.$langBase->get('function-pointshop').'</a>',
			'<a href="'.$config['base_url'].'?side=istoric-credite"'.($_GET['side'] == 'istoric-credite' ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/note_edit.png" alt="" />'.$langBase->get('function-istcrd').'</a>'
		),
		
		'istoric-credite' => array(
			'<a href="'.$config['base_url'].'?side=magazin-credite"'.($_GET['side'] == 'magazin-credite' ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/page_edit.png" alt="" />'.$langBase->get('function-pointshop').'</a>',
			'<a href="'.$config['base_url'].'?side=istoric-credite"'.($_GET['side'] == 'istoric-credite' ? ' class="active"' : '').'><img src="'.$config['base_url'].'images/icons/note_edit.png" alt="" />'.$langBase->get('function-istcrd').'</a>'
		)
	);
	
	
	/*
		Graphs to load in head section (optional).
	*/
	$config['graphs'] = array(
		// Script id => 'DOM element id', 'width', 'height', 'data file'
		'statistici_jaf' => array(
			array('graph_performed_brekk', '100%', 300, 'graphs/performed_brekk.php?player='.Player::Data('id').'&view=1')
		),
		'blackjack' => array(
			array('graph_bj_results', '100%', 300, 'graphs/blackjack_results.php')
		),
		'santaj' => array(
			array('graph_blackmail_results', '100%', 300, 'graphs/blackmail_results.php')
		),
		'min_side' => array(
			array('graph_da_stats', '100%', 300, 'graphs/daily_activity.php?player=' . Player::Data('id')),
			array('graph_ec_stats', '100%', 300, 'graphs/player_economy.php?player=' . Player::Data('id'))
		),
		'game_panel/player' => array(
			array('graph_antibot_stats', '100%', 200, 'graphs/antibot_stats.php?player=' . $_GET['id']),
			array('graph_economy_stats', '100%', 300, 'graphs/player_economy.php?player=' . $_GET['id']),
			array('graph_da_stats', '100%', 300, 'graphs/daily_activity.php?player=' . $_GET['id'])
		),
		'game_panel/points' => array(
			array('graph_points_stats', '100%', 300, 'graphs/point_orders.php')
		)
	);
	
	if (Player::Data('level') > 2)
	{
		$config['graphs']['statistici'][] = array('graph_money', '100%', 300, 'graphs/money_in_game.php');
	}
	
	
	/*
		Script headers options.
	*/
	// Path
	$config['script_headers_path'] = $config['base_url'] . 'images/script_headers/';
	
	// File type
	$config['script_headers_ext'] = '.jpg';
	
	
	/*
		Max smartmenu items
	*/
	$config['max_smartMenu_items'] = 8;
	
	
	
	/*
		|-----------------------------------------|
		| FORUMS					              |
		|-----------------------------------------|
	*/
		
	$config['forums'] = array(
		1 => array($langBase->get('function-forum_game'), false, 3, 120, 60, 1, true),
		2 => array($langBase->get('function-forum_sales'), false, 2, 120, 60, 1, true),
		3 => array($langBase->get('function-forum_application'), false, 2, 120, 60, 1, true),
		4 => array($langBase->get('function-forum_offtopic'), false, 2, 120, 60, 1, true),
		5 => array(Player::FamilyData('name'), true, 1, 60, 30, 1, false),
		6 => array($langBase->get('function-forum_supporter'), false, 1, 60, 30, 1, false),
		7 => array($langBase->get('function-forum_buy'), false, 2, 120, 60, 1, true)
	);
	
	
	$config['forum_topic_title_min_chars'] = 5;
	$config['forum_topic_title_max_chars'] = 30;
	$config['forum_topic_text_min_chars'] = 20;
	$config['forum_topic_text_max_chars'] = 80000;
	$config['forum_reply_text_min_chars'] = 2;
	$config['forum_reply_text_max_chars'] = 80000;
	$config['forum_report_min_chars'] = 5;
	$config['forum_report_latency'] = 120;
	$config['forumsignature_max_lines'] = 4;
	$config['forum_min_replies_per_page'] = 5;
	$config['forum_max_replies_per_page'] = 50;
	
	$config['game_smileys_path'] = $config['base_url'] . 'images/smileys/';
	$config['game_smileys'] = array(
		'18.png' => array('&gt;:)', '&gt;:)'),
		'21.png' => array(':))', ':))'),
		'1.png' => array(':)', ':)'),
		'19.png' => array(':((', ':(('),
		'2.png' => array(':(', ':('),
		'5.png' => array(';;)', ';;)'),
		'30.png' => array(';))', ';))'),
		'3.png' => array(';)', ';)'),
		'6.png' => array('&gt;:D&lt;', 'Hug'),
		'26.png' => array('\:D/', '\:D/'),
		'4.png' => array(':D', ':D'),
		'7.png' => array(':-/', ':-/'),
		'8.png' => array(':X', ':X'),
		'9.png' => array(':x', ':x'),
		'10.png' => array(':"&gt;', ':"&gt;'),
		'17.png' => array('&gt;:P', '&gt;:P'),
		'11.png' => array(':P', ':P'),
		'12.png' => array(':*', ':*'),
		'13.png' => array('=((', '=(('),
		'14.png' => array(':O', ':O'),
		'15.png' => array('X(', 'X('),
		'16.png' => array('B-)', 'B-)'),
		'20.png' => array('O:-)', 'O:-)'),
		'22.png' => array('brb', 'brb'),
		'23.png' => array('(*)', '(*)'),
		'24.png' => array(':|', ':|'),
		'25.png' => array(':-s', ':-s'),
		'27.png' => array(':-&amp;', ':-&amp;'),
		'28.png' => array('TV', 'TV'),
		'29.png' => array(':-q', ':-q'),
		'31.png' => array('=D&gt;', '=D&gt;'),
		'32.png' => array(':-|', ':-|'),
		'lopen.gif' => array(':lopen:', ':lopen:'),
		'mowhaha.gif' => array(':mowhaha:', ':mowhaha:'),
		'nerd.png' => array(':geek:', ':geek:'),
		'nooo.png' => array(':nooo:', ':nooo:'),
		'oeh.png' => array(':oeh:', ':oeh:'),
		'oh.png' => array(':oh:', ':oh:'),
		'Ohno.png' => array(':ohno:', ':ohno:'),
		'ohnoes.png' => array(':ohnoes:', ':ohnoes:'),
		'ohreally.png' => array(':ohreally:', ':ohreally:'),
		'ohyes.png' => array(':ohyes:', ':ohyes:'),
		'oke.png' => array('-.-', '-.-'),
		'omg.png' => array(':omg:', ':omg:'),
		'omygod.png' => array(':omygod:', ':omygod:'),
		'openmouth.png' => array(':O', ':O'),
		'pacman.png' => array(':pacman:', ':pacman:'),
		'pfff.png' => array(':pfff:', ':pfff:'),
		'Rawr.png' => array(':rawr:', ':rawr:'),
		'retard.png' => array(':retard:', ':retard:'),
		'Retarded.png' => array(':retarded:', ':retarded:'),
		'roll.gif' => array(':roll:', ':roll:'),
		'satisfied.png' => array(':satisfied:', ':satisfied:'),
		'shocked.png' => array(':shock:', ':shock:'),
		'sleep.png' => array(':sleep:', ':sleep:'),
		'supergrin.png' => array(':supergrin:', ':supergrin:'),
		'superhappy.png' => array(':superhappy:', ':superhappy:'),
		'suspicious.png' => array(':suspicious:', ':suspicious:'),
		'tonguey.png' => array(':tonguey:', ':tonguey:'),
		'toobad.png' => array(':toobad:', ':toobad:'),
		'twisted.gif' => array(':twisted:', ':twisted:'),
		'up.png' => array(':up:', ':up:'),
		'well.png' => array(':well:', ':well:'),
		'what.png' => array(':what:', ':what:'),
		'XD.png' => array(':XD:', ':XD:'),
		'Yee.png' => array(':yee:', ':yee:'),
		'yep.png' => array(':yep:', ':yep:'),
		'you.png' => array(':you:', ':you:'),
		'zzz.png' => array(':zzz:', ':zzz:')
	);

	/*
		|-----------------------------------------|
		| ANTIBOT                                 |
		|-----------------------------------------|
	*/
	
	$config['antibot_images'] = array(
		$langBase->get('antibot-01')      => 'ABImage_car_',
		$langBase->get('antibot-02')      => 'ABImage_plane_',
		$langBase->get('antibot-03')      => 'ABImage_pen_',
		$langBase->get('antibot-04')      => 'ABImage_clock_',
		$langBase->get('antibot-05')      => 'ABImage_train_',
		$langBase->get('antibot-06')      => 'ABImage_PC_',
		$langBase->get('antibot-07')      => 'ABImage_house_',
		$langBase->get('antibot-08')      => 'ABImage_TV_',
		$langBase->get('antibot-09')      => 'ABImage_keyboard_'
	);
	
	$config['antibot_images_per_session'] = 6;
	$config['antibot_images_per_title'] = 5;
	$config['antibot_images_path'] = $config['base_url'] . 'images/ABImages/';
	$config['antibot_image_file_ext'] = '.jpg';
	$config['antibot_try_latency'] = 10;
	$config['antibot_next_range'] = array(
		'brekk' => array(15, 20),
		'blackmail' => array(15, 20),
		'car_theft' => array(15, 20),
		'lottery' => array(20, 25),
		'car_race' => array(6, 10),
		'jail_breakout' => array(10, 15),
		'fighting_training' => array(7, 10),
		'fighting' => array(7, 10),
		'kastmynt' => array(6, 8)
	);
	
	// Player
	$config['playername_min_chars'] = 4;
	$config['playername_max_chars'] = 20;
	$config['playername_valid_regex'] = '/^[\sa-zA-Z0-9]{' . $config['playername_min_chars'] . ',' . $config['playername_max_chars'] . '}+$/';
	$config['player_default_money']['cash'] = 20000;
	$config['player_default_money']['bank'] = 0;
	$config['player_default_coins']['coins'] = $admin_config['bonus_credite']['value'];
	$config['default_profileimage'] = '/game/images/default_profileimage.png';
	
	/*
		Companies Settings
	*/
	$config['business_types'] = array(
		1 => array(
			// Bank
			'name'                =>  array($langBase->get('c_firma-01'), $langBase->get('c_firma-02'), $langBase->get('c_firma-03'), $langBase->get('c_firma-04')),
			'job_titles'          =>  array($langBase->get('c_firma-05'), $langBase->get('c_firma-06')),
			'max_num'             =>  7,
			'place_limit'         =>  true,
			'max_deficit_length'  =>  43200,
			'default_misc'        =>  array(
				'account_price' => 0,
				'rente_type'    => 1,
				'rente_classes' => array(
					array(1, 4999, 2),
					array(5000, 99999, 1.8),
					array(100000, 4999999, 1.6),
					array(5000000, 9999999, 1.4),
					array(10000000, 99999999, 1),
					array(100000000, 999999999, 0.7),
					array(1000000000, 9999999999, 0.3),
					array(10000000000, '99999999999', 0.2)
				),
				'transfer_fee'       => 2,
				'transfer_fee_pp'    => 100000,
				'rente_type_2_value' => 2,
				'deposit_fee'		 => 0,
				'deposit_fees' 		 => 0,
				'total_deposits'	 => 0
			),
			'extra' => array(
				'rente_types'                => array(1 => $langBase->get('c_firma-07'), 2 => $langBase->get('c_firma-08'), 3 => $langBase->get('c_firma-09')),
				'min_rente_classes'          =>  2,
				'max_rente_classes'          =>  10,
				'min_rente'                  =>  0.1,
				'max_rente'                  =>  60,
				'rente_max_decimals'         =>  2,
				'min_transfer_fee'           =>  0,
				'max_transfer_fee'           =>  60,
				'transfer_fee_max_decimals'  =>  2,
				'fransfer_min_fee_pp'        =>  0,
				'fransfer_max_fee_pp'        =>  100000000,
				'min_name_length' => 5,
				'max_name_length' => 30
			)
		),
		
		// Newspapers
		2 => array(
			'name'                =>  array($langBase->get('c_firma-10'), $langBase->get('c_firma-11'), $langBase->get('c_firma-12'), $langBase->get('c_firma-13')),
			'job_titles'          =>  array($langBase->get('c_firma-05'), $langBase->get('c_firma-06')),
			'max_num'             =>  7,
			'place_limit'         =>  true,
			'max_deficit_length'  =>  43200,
			'default_misc'        =>  array(
				'sold_papers' => 0,
				'journalists' => array(),
				'journalist_invites' => array()
			),
			'extra' => array(
				'paper_layouts' => array(
					1 => 'Type #1',
					2 => 'Type #2',
					3 => 'Type #3'
				),
				'article_types' => array(
					'top' => 'Sus',
					'left' => 'Stanga',
					'right' => 'Dreapta',
					'bottom' => 'Jos'
				),
				'min_title_length' => 5,
				'max_title_length' => 25,
				'max_description_length' => 120,
				'max_unpublished_papers' => 5,
				'article_title_min' => 5,
				'article_title_max' => 25,
				'article_text_min' => 20,
				'min_name_length' => 5,
				'max_name_length' => 30
			)
		),
		
		// Bullets Factory
		3 => array(
			'name'                =>  array($langBase->get('c_firma-14'), $langBase->get('c_firma-15'), $langBase->get('c_firma-16'), $langBase->get('c_firma-17')),
			'job_titles'          =>  array($langBase->get('c_firma-05'), $langBase->get('c_firma-06')),
			'max_num'             =>  7,
			'place_limit'         =>  true,
			'max_deficit_length'  =>  86400,
			'default_misc'        =>  array(
				'sold_bullets' => 0,
				'last_production' => 0,
				'last_release' => 0,
				'bullets_stored' => 0,
				'bullets' => 0,
				'bullets_reserved' => array()
			),
			'extra' => array(
				'bullet_price' => 5000,
				'max_bullets_per_production' => 3000,
				'production_wait' => 3600,
				'release_wait' => 3600,
				'min_name_length' => 5,
				'max_name_length' => 30
			)
		)
	);

	$config['businesses_incomePunish'] = 10000;
	$config['businesses_default_stockprice'] = 5000;
	$config['businesses_max_stocks'] = 500;
	
	$config['bank_transfertypes'] = array(
		'money'   =>  array($langBase->get('ot-money'), '$'),
		'points'  =>  array($langBase->get('ot-points'), 'C')
	);

	$config['bank_max_deposit_fee'] = 5;
	$config['bank_max_inactivity_logout'] = 1800;
	$config['business_default_image'] = '/game/images/default_firmabilde.jpg';

	/*
		Family Settings
	*/
	$config['family_default_logo'] = '/game/images/default_family_logo.jpg';
	$config['max_families_in_each_place'] = 2;
	$config['family_create_min_kills'] = 0;
	$config['family_create_min_rank'] = 7;
	
	$config['family_max_member_types'] = array(
		array($langBase->get('c_fam-01'), 10, 75),
		array($langBase->get('c_fam-02'), 20, 150),
		array($langBase->get('c_fam-03'), 50, 300),
		array($langBase->get('c_fam-04'), 100, 500)
	);
	
	$config['family_business_types'] = array(
		'travel' => array(
			'title' => $langBase->get('c_firma-18'),
			'buy_price' => 75000000,
			'guardspot_price' => 25000000,
			'max_guard_slots' => 2
		),
		'hospital' => array(
			'title' => $langBase->get('c_firma-19'),
			'buy_price' => 60000000,
			'guardspot_price' => 25000000,
			'max_guard_slots' => 4
		),
		'lottery' => array(
			'title' => $langBase->get('c_firma-20'),
			'buy_price' => 200000000,
			'guardspot_price' => 25000000,
			'max_guard_slots' => 5
		)
	);
	
	$config['family_attack_wait'] = 43200;
	$config['family_attack_reset'] = 40;
	$config['family_protection_length'] = 259200;
	
	$config['car_race_drivers'] = array(
		'Jeff Gordon',
		'Jimmie Johnson',
		'Mark Martin',
		'Tony Stewart',
		'Kurt Busch'
	);
	
	$config['car_race_winner_rank'] = 200;
	$config['car_races_timespan'] = 1800;
	$config['car_race_min_bet'] = 10000;
	
	/*
		Respect Settings
	*/
	$config['respect_f_user'] = 5;		// Daily limit for free users
	$config['respect_v_user'] = 10;		// Daily Limit for VIP users
	$config['respect_nivel'] = 3;		// Required level to send respect
	
	
	/*
		Lottery
	*/
	$config['lottery_winnerplaces'] = array(
		1 => array(
			'money_percent' => 30
		),
		2 => array(
			'money_percent' => 20
		),
		3 => array(
			'money_percent' => 12
		),
		4 => array(
			'money_percent' => 8
		),
		5 => array(
			'money_percent' => 5
		)
	);

	$config['lottery_winner_rank'] = array(
		1 => 20,
		2 => 15,
		3 => 12,
		4 => 10,
		5 => 7,
		6 => 5,
		7 => 4,
		8 => 3.1,
		9 => 2.2,
		10 => 1.1,
		11 => 0.9,
		12 => 0.7,
		13 => 0.6,
		14 => 0.5,
		15 => 0.4
	);
	
	$config['lottery_timespan'] = 3600;
	$config['lottery_ticket_price'] = 25000;
	$config['lottery_max_tickets_per_round'] = 400;
	$config['lottery_max_tickets'] = 40;
	$config['lottery_ticketBuy_waitTime'] = 60;
	
	
	/*
		Administrator/Moderator
	*/
	$config['killtime_start'] = strtotime($admin_config['killtime_start']['value']);
	$config['killtime_stop'] = strtotime($admin_config['killtime_stop']['value']);
	
	if ($config['killtime_stop'] <= time())
	{
		$config['killtime_start'] = strtotime($admin_config['killtime_start']['value'], time() + 86400);
		$config['killtime_stop'] = strtotime($admin_config['killtime_stop']['value'], time() + 86400);
	}
	
	$config['bunker_price'] = 25000000;
	$config['bunker_max_length'] = 720;
	$config['kill_witness_min_active'] = 259200;
	
	$config['weapons'] = array(
		1 => array(
			'name' => $langBase->get('waffe-01'),
			'price' => 100000,
			'bullets_decrease' => 1
		),
		2 => array(
			'name' => 'Beretta Px4 Storm',
			'price' => 500000,
			'bullets_decrease' => 5
		),
		3 => array(
			'name' => 'Desert Eagle',
			'price' => 1000000,
			'bullets_decrease' => 10
		),
		4 => array(
			'name' => 'Heckler &amp; Koch MP5',
			'price' => 3000000,
			'bullets_decrease' => 16
		),
		5 => array(
			'name' => 'Heckler &amp; Koch G36',
			'price' => 6000000,
			'bullets_decrease' => 22
		),
		6 => array(
			'name' => 'AK-47',
			'price' => 9000000,
			'bullets_decrease' => 27
		),
		7 => array(
			'name' => 'DSR-50 Sniper Rifle',
			'price' => 12000000,
			'bullets_decrease' => 32
		)
	);
	
	$config['weapon_min_training_to_buy'] = 150;
	$config['weapon_max_traning'] = 300;
	$config['weapon_training_wait'] = 900;
	$config['weapon_training_points'] = array(12, 15);
	
	$config['protections'] = array(
		1 => array(
			'name' => $langBase->get('c_weapon-01'),
			'price' => 1000000,
			'bullets_increase' => 6
		),
		2 => array(
			'name' => $langBase->get('c_weapon-02'),
			'price' => 2000000,
			'bullets_increase' => 15
		),
		3 => array(
			'name' => $langBase->get('c_weapon-03'),
			'price' => 5000000,
			'bullets_increase' => 23
		),
		4 => array(
			'name' => $langBase->get('c_weapon-04'),
			'price' => 7500000,
			'bullets_increase' => 28
		),
		5 => array(
			'name' => $langBase->get('c_weapon-05'),
			'price' => 15000000,
			'bullets_increase' => 35
		)
	);
	
	$config['kill_wanted'] = array(
		'no_find' => array(40, 50),
		'fail' => array(75, 90),
		'success' => array(100, 120)
	);
	
	$config['kill_rankpoints'] = array(
		'fail' => array(10, 30),
		'success' => array(55, 95)
	);
	
	$config['kill_jail_penalty'] = array(
		'no_find' => array(140, 180),
		'fail' => array(260, 360),
		'success' => array(360, 400)
	);
	
	$config['kill_jail_security'] = 7;
	
	$config['kill_protection'] = 259200;
	
	$config['kill_strengthPoints'] = array(
		'ranks' => array(
			1 => 0,
			2 => 2,
			3 => 4,
			4 => 7,
			5 => 9,
			6 => 10,
			7 => 11,
			8 => 13,
			9 => 15,
			10 => 18,
			11 => 20,
			12 => 25,
			13 => 30,
			14 => 35,
			15 => 40
		),
		'familyBoss' => 20,
		'underboss' => 15,
		'businessOwner' => 15
	);
	
	$config['bulletBuy_period'] = false;
	$periods = explode('|', $admin_config['bulletBuy_times']['value']);
	foreach ($periods as $time)
	{
		$times = explode('-', $time);
		$now = time();
		
		if ($now >= strtotime($times[0]) && $now < strtotime($times[1]))
		{
			$config['bulletBuy_period'] = true;
			break;
		}
	}
	
	$config['bulletBuy_session_time'] = array(850, 1200);
	$config['bullets_per_session'] = array(450, 750);
	$config['bullet_price'] = 6000;
	$config['bulletBuy_antibot_time'] = 10;

	$config['detective_max_length'] = 120;
	$config['detective_min_length'] = 5;
	$config['detective_price_per_min'] = 10000;

	$config['points_prices'] = array(
		40 => 40,
		80 => 60,
		160 => 100
	);

	/*
		Planned Crime
	*/
	$config['planned_crime_wait_time'] = (Player::Data('vip_days') > 0 ? 21600 : 28800);
	$config['planned_crime_start_min_rank'] = 5;
	$config['planned_crime_hostages_state_max'] = 300;
	$config['planned_crime_hostages_state_up_interval'] = 15;
	
	$config['planned_crime_hostages_state_up'] = array(11, 14);
	$config['planned_crime_shooter_wait'] = 40;
	$config['planned_crime_shooter_max_bullets'] = 5;
	$config['planned_crime_shooter_fire_impact'] = array(10, 18);

	$config['planned_crime_job_types'] = array(
		'starter' => array(
			'title' => $langBase->get('c_orgj-01'),
			'type' => 'starter',
			'equipment' => ''
		),
		'getaway_driver' => array(
			'title' => $langBase->get('c_orgj-02'),
			'type' => 'getaway_driver',
			'equipment' => 'car'
		),
		'crash_driver' => array(
			'title' => $langBase->get('c_orgj-03'),
			'type' => 'crash_driver',
			'equipment' => 'car'
		),
		'shooter' => array(
			'title' => $langBase->get('c_orgj-04'),
			'type' => 'shooter',
			'equipment' => 'weapon'
		)
	);

	$config['planned_crime_equipment'] = array(
		'car' => 'car',
		'weapon' => 'weapon'
	);

	$config['planned_crime_types'] = array(
		1 => array(
			'title' => $langBase->get('c_orgj-09'),
			'max_time' => 300,
			'result_money' => array(750000, 2000000),
			'rankpoints' => array(
				'success' => array(80, 100),
				'fail' => array(55, 70)
			),
			'wanted_level' => array(
				'success' => array(40, 50),
				'fail' => array(28, 35)
			),
			'jail_penalty' => 600
		),
		2 => array(
			'title' => $langBase->get('c_orgj-05'),
			'max_time' => 240,
			'result_money' => array(1500000, 2500000),
			'rankpoints' => array(
				'success' => array(100, 120),
				'fail' => array(65, 80)
			),
			'wanted_level' => array(
				'success' => array(50, 60),
				'fail' => array(35, 45)
			),
			'jail_penalty' => 700
		),
		3 => array(
			'title' => $langBase->get('c_orgj-06'),
			'max_time' => 200,
			'result_money' => array(2000000, 3000000),
			'rankpoints' => array(
				'success' => array(130, 140),
				'fail' => array(70, 85)
			),
			'wanted_level' => array(
				'success' => array(40, 70),
				'fail' => array(35, 45)
			),
			'jail_penalty' => 800
		),
		4 => array(
			'title' => $langBase->get('c_orgj-07'),
			'max_time' => 160,
			'result_money' => array(3600000, 4300000),
			'rankpoints' => array(
				'success' => array(150, 170),
				'fail' => array(75, 90)
			),
			'wanted_level' => array(
				'success' => array(50, 60),
				'fail' => array(35, 45)
			),
			'jail_penalty' => 900
		),
		5 => array(
			'title' => $langBase->get('c_orgj-08'),
			'max_time' => 150,
			'result_money' => array(5000000, 10000000),
			'rankpoints' => array(
				'success' => array(170, 210),
				'fail' => array(75, 95)
			),
			'wanted_level' => array(
				'success' => array(60, 75),
				'fail' => array(35, 50)
			),
			'jail_penalty' => 1200
		),
		6 => array(
			'title' => $langBase->get('c_orgj-10'),
			'max_time' => 120,
			'result_money' => array(6000000, 12000000),
			'rankpoints' => array(
				'success' => array(180, 225),
				'fail' => array(80, 100)
			),
			'wanted_level' => array(
				'success' => array(70, 90),
				'fail' => array(40, 60)
			),
			'jail_penalty' => 1500
		)
	);

	$config['missions'] = array(
		1 => array(
			'title' => $langBase->get('nmiss-01'),
			'description' => 'Hey, ' . Player::Data('name') . '!<br /><br />'.$langBase->get('nmiss-02'),
			'rewards' => array(
				'cash' => 7500000,
				'points' => 2,
				'bullets' => 200,
				'rankpoints' => 300
			),
			'objects' => array(
				$langBase->get('nmiss-03'),
				$langBase->get('nmiss-04'),
				$langBase->get('nmiss-05'),
				$langBase->get('nmiss-06'),
				$langBase->get('nmiss-07'),
			)
		),
		2 => array(
			'title' => 'It\'s time to kill',
			'description' => 'Hey, ' . Player::Data('name') . '!<br /><br />Michele Navarra, my driver, destroy my business so I have to kill him!<br>I know he play a lot of games of chance, so my assistant will give you more information. Complete these tasks and come back for your reward!',
			'rewards' => array(
				'cash' => 20000000,
				'points' => 2,
				'bullets' => 500,
				'rankpoints' => 700
			),
			'objects' => array(
				0 => 'Win against 5 different players at BlackJack',
				//1 => 'Task removed',
				2 => 'Win one time at "Lottery"',
				3 => 'Find where is Michele Navarra',
				4 => 'Kill Michele Navarra'
			),
			'target' => array(
				'rank' => 6,
				'protection' => 2,
				'cash' => array(300000, 700000)
			)
		),
		3 => array(
			'title' => 'Newcomers',
			'description' => 'Hey, ' . Player::Data('name') . '!<br /><br />You was the best in last 2 tasks so it\'s time to give you that new mission.<br />Jos&eacute; Macaruno is arrested in prison from Oslo and Mario De Aramengo is arrested in prison from  Copenhaga.<br> Complete these tasks and come back to get your reward!',
			'rewards' => array(
				'cash' => 25000000,
				'points' => 2,
				'bullets' => 600,
				'rankpoints' => 1000
			),
			'objects' => array(
				'Steal 3 Aston Martin',
				'Help Jos&eacute; Macaruno to escape from prison',
				'Help Mario De Aramengo to escape from prison',
				'Conducting a planned robbery with newcomers'
			)
		),
		4 => array(
			'title' => 'Valuable player',
			'description' => 'Hey, ' . Player::Data('name') . '!<br /><br />It\'s time to show that you are a valuable player.<br />Complete these easy tasks and come back to get your reward!.',
			'rewards' => array(
				'cash' => 40000000,
				'points' => 5,
				'bullets' => 1000,
				'rankpoints' => 1400
			),
			'objects' => array(
				0 => 'Win against 10 different players at BlackJack',
				1 => 'Win one time at "Open the safe"',
				2 => 'Win one time at "Lottery"',
				3 => 'Steal 5 Bugatti Veyron'
			)
		),
		5 => array(
			'title' => 'Luxury Cars',
			'description' => 'Hey, ' . Player::Data('name') . '!<br /><br />After latest robbery, our cars were either destroyed or were abandoned and raised by police, and now we need some new cars.<br />Get some cars and come back here to get your reward!.',
			'rewards' => array(
				'cash' => 45000000,
				'points' => 10,
				'bullets' => 1200,
				'rankpoints' => 1400
			),
			'objects' => array(
				0 => 'Steal 5 Bugatti Veyron',
				1 => 'Steal 5 Ferrari Spider',
				2 => 'Steal 5 Lamborghini Aventador',
				3 => 'Steal 5 Lamborghini Reventon'
			)
		),
		6 => array(
			'title' => 'Recruit some Players',
			'description' => 'Hey, ' . Player::Data('name') . '!<br /><br />We live in a dangerous world, so it\'s time we get some "fresh meat". I want you to recruit some new players in this wonderful game using your special link and complete some simple tasks.<br>You think you\'re able to go on this quest?<br><br>Good luck!',
			'rewards' => array(
				'cash' => 75000000,
				'points' => 20,
				'bullets' => 1500,
				'rankpoints' => 1600
			),
			'objects' => array(
				0 => 'Invite 5 players in game',
				1 => 'Win 5 times at "Slots"',
				2 => 'Win one time at "Lottery"'
			)
		),
		7 => array(
			'title' => 'A respected player',
			'description' => 'Hey, ' . Player::Data('name') . '!<br /><br /> We\'ve proven that you\'re a powerful player by completing our previous missions, so we have some new few simple tasks for you. After you complete the tasks, come back here to give you a reward.<br><br>Good luck!',
			'rewards' => array(
				'cash' => 90000000,
				'points' => 25,
				'bullets' => 1600,
				'rankpoints' => 1700
			),
			'objects' => array(
				0 => 'Send 10 points of respect',
				1 => 'Receive 10 points of respect',
				2 => 'Win 5 times at "Scratch Tickets"',
				3 => 'Steal 15 Bugatti Veyron',
				4 => 'Steal 5 Ferrari Spider'
			)
		),
		8 => array(
			'title' => 'Pimp',
			'description' => 'Hey, ' . Player::Data('name') . '!<br /><br /> It\'s time to get some cash from hookers, so I have few tasks that I\'m sure you can finish them. After you complete the tasks, come back here to give you a reward.<br><br>Good luck!',
			'rewards' => array(
				'cash' => 100000000,
				'points' => 25,
				'bullets' => 2000,
				'rankpoints' => 1800
			),
			'objects' => array(
				0 => 'Recruits 200 hookers',
				1 => 'Collect 1 000 000$ from hookers',
				2 => 'Add 100 hookers in the brothel'
			)
		)
	);

	$config['minimissions'] = array(
		1 => array(
			'title' => $langBase->get('c_miss-01'),
			'rewards' => array(
				'cash' => 1000000,
				'rankpoints' => 100,
				'bullets' => 50
			),
			'time_limit' => 1800,
			'wait_time' => 28800
		),
		2 => array(
			'title' => $langBase->get('c_miss-02'),
			'rewards' => array(
				'cash' => 5000000,
				'rankpoints' => 150,
				'bullets' => 100
			),
			'time_limit' => 1800,
			'wait_time' => 43200
		),
		3 => array(
			'title' => $langBase->get('c_miss-03'),
			'rewards' => array(
				'cash' => 6500000,
				'rankpoints' => 180,
				'bullets' => 150
			),
			'time_limit' => 2700,
			'wait_time' => 43200
		),
		4 => array(
			'title' => $langBase->get('c_miss-04'),
			'rewards' => array(
				'cash' => 7000000,
				'rankpoints' => 200,
				'bullets' => 175
			),
			'time_limit' => 3600,
			'wait_time' => 57600
		),
		5 => array(
			'title' => $langBase->get('c_miss-05'),
			'rewards' => array(
				'cash' => 8000000,
				'rankpoints' => 150,
				'bullets' => 100
			),
			'time_limit' => 1800,
			'wait_time' => 86400
		),
		6 => array(
			'title' => $langBase->get('c_miss-06', array('-PROGRES-' => View::AsPercent(600, $config['ranks'][Player::Data('rank')][2]-$config['ranks'][Player::Data('rank')][1], 4))),
			'rewards' => array(
				'cash' => 6000000,
				'rankpoints' => 150,
				'bullets' => 80
			),
			'time_limit' => 1800,
			'wait_time' => 86400
		),
		7 => array(
			'title' =>$langBase->get('c_miss-07'),
			'rewards' => array(
				'cash' => 9000000,
				'rankpoints' => 210,
				'bullets' => 130
			),
			'time_limit' => 1800,
			'wait_time' => 64800
		)
	);

	$config['menu_links'] = array(
		// category => [item 1 => [html, href, icon_id, menuads_className]]
		'kriminalitet' => array(
			0 => array($langBase->get('function-brekk'), '/game/?side=jafuri', 1, 'brekk_timeleft'),
			1 => array($langBase->get('function-carTheft'), '/game/?side=fura-masini', 2, 'car_theft_timeleft'),
			2 => array($langBase->get('function-blackmail'), '/game/?side=santaj', 3, 'blackmail_timeleft'),
			3 => array($langBase->get('function-mission'), '/game/?side=misiuni', 47),
			4 => array($langBase->get('function-planned_crime'), '/game/?side=jaf-organizat', 24, 'pr_timeleft'),
			5 => array($langBase->get('function-kill'), '/game/?side=asasin', 5, 'killtime_timeleft'),
			6 => array($langBase->get('function-killshop'), '/game/?side=armament', 45),
			7 => array($langBase->get('function-fighting'), '/game/?side=lupte', 49, 'training_timeleft'),
			8 => array($langBase->get('function-bordel'), '/game/?side=bordel', 6)
		),
		'attraksjoner' => array(
			0 => array($langBase->get('function-jail'), '/game/?side=inchisoare', 7, 'in_jail'),
			1 => array($langBase->get('function-bank'), '/game/?side=banca', 11),
			2 => array($langBase->get('function-stocks'), '/game/?side=bursa', 46),
			3 => array($langBase->get('function-map'), '/game/?side=harta', 10),
			4 => array($langBase->get('function-property'), '/game/?side=locuinta', 48),
			5 => array($langBase->get('function-bunker'), '/game/?side=buncar', 44),
			6 => array($langBase->get('function-hospital'), '/game/?side=spital', 8),
			7 => array($langBase->get('function-marketplace'), '/game/?side=piata', 50),
			8 => array($langBase->get('function-auction_house'), '/game/?side=licitatii', 9, 'auction_num'),
			9 => array($langBase->get('function-garage'), '/game/?side=garaj', 12),
			10 => array($langBase->get('function-companies'), '/game/?side=firma/', 13)
		),
		'gambling' => array(
			0 => array($langBase->get('function-blackjack'), '/game/?side=blackjack', 16, 'blackjack_num'),
			2 => array($langBase->get('function-lottery'), '/game/?side=loterie', 17),
			3 => array($langBase->get('function-streetrace'), '/game/?side=curse', 18),
			4 => array($langBase->get('function-coinroll'), '/game/?side=moneda', 17),
			5 => array($langBase->get('function-ruleta'), '/game/?side=ruleta', 15),
			6 => array($langBase->get('function-guess'), '/game/?side=guess', 14),
			7 => array($langBase->get('function-sparge_seif'), '/game/?side=sparge-seif', 42),
			8 => array($langBase->get('function-rtnr'), '/game/?side=roata-norocului', 15, 'rtnr_timeleft'),
			9 => array($langBase->get('function-slots'), '/game/?side=slot', 19),
			10 => array($langBase->get('function-lozuri'), '/game/?side=lozuri', 33),
			11 => array($langBase->get('function-number_game'), '/game/?side=numbers', 14, 'numbers_num')
		),
		'poeng' => array(
			0 => array($langBase->get('function-pointshop'), '/game/?side=magazin-credite', 40),
			1 => array($langBase->get('function-transfer_points'), '/game/?side=transfer-credite', 41)
		),
		'forum' => array(
			0 => array($langBase->get('function-forum_game'), '/game/?side=forum/forum&amp;f=1', 20),
			1 => array($langBase->get('function-forum_sales'), '/game/?side=forum/forum&amp;f=2', 21),
			2 => array($langBase->get('function-forum_buy'), '/game/?side=forum/forum&amp;f=7', 45),
			3 => array($langBase->get('function-forum_application'), '/game/?side=forum/forum&amp;f=3', 22),
			4 => array($langBase->get('function-forum_offtopic'), '/game/?side=forum/forum&amp;f=4', 23)
		),
		'familie' => array(
			0 => array($langBase->get('function-family_overview'), '/game/?side=familie/', 24),
			1 => array($langBase->get('function-family_businesses'), '/game/?side=familie/&amp;b', 24)
		),
		'bruker' => array(
			0 => array($langBase->get('function-messages'), '/game/?side=mesaje&amp;a=inbox', 30, 'new_pms'),
			1 => array($langBase->get('function-headquarter'), '/game/?side=min_side&amp;a=player&amp;b=main', 26),
			2 => array($langBase->get('function-edit_profile'), '/game/?side=rediger_profil&amp;a=profiletext', 25),
			3 => array($langBase->get('function-contacts'), '/game/?side=kontakter&amp;a=contact', 27),
			4 => array($langBase->get('function-applications'), '/game/?side=cereri', 28),
			5 => array($langBase->get('function-logs'), '/game/?side=min_side&amp;a=player&amp;b=log', 29, 'new_logevents'),
			6 => array($langBase->get('function-read_newspaper'), '/game/?side=firma/lesavis', 37),
			7 => array($langBase->get('function-p_rec'), '/game/?side=min_side&amp;a=ref&amp;b=main', 24)
		),
		'system' => array(
			0 => array($langBase->get('function-faq'), '/game/?side=faq', 42),
			1 => array($langBase->get('function-support'), '/game/?side=support', 39),
			2 => array($langBase->get('function-statistics'), '/game/?side=statistici', 46),
			3 => array($langBase->get('function-find_player'), '/game/?side=cautare', 32),
			4 => array($langBase->get('function-members_list'), '/game/?side=membrii', 33),
			5 => array($langBase->get('function-crew'), '/game/?side=staff', 34),
			6 => array($langBase->get('function-bannere'), '/game/?side=bannere', 24),
			9 => array($langBase->get('function-online_list'), '/game/?side=online_list', 35, 'online'),
			10 => array($langBase->get('function-logout'), '/game/?logout', 38)
		)
	);
	
	if (User::Data('userlevel') >= 1)
		$config['menu_links']['forum'][6] = array($langBase->get('function-forum_supporter'), '/game/?side=forum/forum&amp;f=6', 24);
	
	if (Player::FamilyData('id'))
	{
		$config['menu_links']['forum'][5] = array(Player::FamilyData('name'), '/game/?side=forum/forum&amp;f=5', 24);
		
		$config['menu_links']['familie'][2] = array($langBase->get('function-family_membersPanel'), '/game/?side=familie/m_panel', 24);
		if (in_array(Player::Data('id'), array(Player::FamilyData('boss'), Player::FamilyData('underboss'))))
			$config['menu_links']['familie'][4] = array($langBase->get('function-family_ownersPanel'), '/game/?side=familie/e_panel', 24);
	}
	else
	{
		$config['menu_links']['familie'][3] = array($langBase->get('function-family_create'), '/game/?side=familie/&amp;create', 24);
	}
	
	$user_menuSort = unserialize(User::Data('menuSort'));
	foreach ($config['menu_links'] as $category => $items)
	{
		if (empty($user_menuSort[$category]))
		{
			$i = 0;
			foreach ($items as $key => $value)
			{
				$user_menuSort[$category][$i] = $key;
				$i++;
			}
		}
		else
		{
			foreach ($items as $key => $item)
			{
				if (!in_array($key, $user_menuSort[$category]))
					$user_menuSort[$category][] = $key;
			}
		}
	}

	$config['houses'] = array(
		1 => array(
			'title' => $langBase->get('gebaeude-01'),
			'price' => 60000000,
			'training' => true,
			'basement' => true,
			'max_plants' => 16000
		),
		2 => array(
			'title' => 'Villa',
			'price' => 25000000,
			'training' => true,
			'basement' => true,
			'max_plants' => 12500
		),
		3 => array(
			'title' => $langBase->get('gebaeude-02'),
			'price' => 10000000,
			'training' => true,
			'basement' => true,
			'max_plants' => 10000
		),
		4 => array(
			'title' => $langBase->get('gebaeude-03'),
			'price' => 5000000,
			'training' => false,
			'basement' => true,
			'max_plants' => 8000
		),
		5 => array(
			'title' => $langBase->get('gebaeude-04'),
			'price' => 2500000,
			'training' => true,
			'basement' => false
		),
		6 => array(
			'title' => $langBase->get('gebaeude-05'),
			'price' => 1250000,
			'training' => false,
			'basement' => false
		),
		7 => array(
			'title' => $langBase->get('gebaeude-06'),
			'price' => 120000,
			'training' => false,
			'basement' => false
		)
	);

	$config['marijuanaplant_price'] = 10000;
	$config['marijuana_per_plant'] = 1;
	$config['marijuanaproduction_equipment'] = array(
		1 => array(
			'title' => 'Fertilizer',
			'price' => 2500000,
			'gram_add' => 0.2
		),
		2 => array(
			'title' => 'Sprinkler',
			'price' => 5000000,
			'gram_add' => 0.4
		),
		3 => array(
			'title' => 'Heater',
			'price' => 5000000,
			'gram_add' => 0.5
		),
		4 => array(
			'title' => 'Artificial light',
			'price' => 10000000,
			'gram_add' => 0.6
		),
		5 => array(
			'title' => 'Conditioning',
			'price' => 15000000,
			'gram_add' => 0.7
		),
		6 => array(
			'title' => 'Sera',
			'price' => 30000000,
			'gram_add' => 0.8
		)
	);

	$config['marijuana_productiontime'] = 86400;
	$config['marijuana_price'] = 250;

	$config['training_studios'] = array(
		'public' => array(
			'title' => $langBase->get('train-06'),
			'training_methods' => array(
				1 => array(
					'title' => $langBase->get('train-01'),
					'points' => 12,
					'wait' => 120
				),
				2 => array(
					'title' => $langBase->get('train-02'),
					'points' => 18,
					'wait' => 160
				),
				3 => array(
					'title' => $langBase->get('train-03'),
					'points' => 20,
					'wait' => 220
				),
				4 => array(
					'title' => $langBase->get('train-04'),
					'points' => 26,
					'wait' => 300
				),
				5 => array(
					'title' => $langBase->get('train-05'),
					'points' => 35,
					'wait' => 360
				)
			)
		),
		'private' => array(
			'title' => 'Privat',
			'training_methods' => array(
				1 => array(
					'title' => '30 pushups',
					'points' => 16,
					'wait' => 120
				),
				2 => array(
					'title' => '50 pushups',
					'points' => 19,
					'wait' => 160
				),
				3 => array(
					'title' => '100 pushups',
					'points' => 24,
					'wait' => 200
				),
				4 => array(
					'title' => 'Run 3.5 km',
					'points' => 29,
					'wait' => 250
				),
				5 => array(
					'title' => 'Run 6 km',
					'points' => 37,
					'wait' => 300
				),
				6 => array(
					'title' => 'Run 15 km',
					'points' => 45,
					'wait' => 360
				),
				7 => array(
					'title' => 'Workout',
					'points' => 52,
					'wait' => 430
				),
				8 => array(
					'title' => 'Boxing in the ring',
					'points' => 72,
					'wait' => 560
				)
			)
		)
	);

	$config['training_max_progress'] = 300;
	$config['fighting_wait_time'] = 90;

	$config['gametips_change_interval'] = 20;
	
	$config['gametips'] = array(
		$langBase->get('tipp-01'),
		$langBase->get('tipp-02'),
		$langBase->get('tipp-03'),
		$langBase->get('tipp-04'),
		$langBase->get('tipp-05'),
	);

	/*
		|-----------------------------------------|
		| META                                    |
		|-----------------------------------------|
	*/
	$config['meta_keywords'] = 'unterweltmafia, rpg, mafia rpg, mafia, mafia siciliana, mafie, bug mafia, rap, online mmorpg, online game, mmorpg spielen, rpg game, multiplayer, spiele Mafia, browserbasiertes Spiel, mafia, gangster, mafia familie, kriminell, raub, organisierter raub, deutsches spiel, deutsche mafia';
	$config['meta_description'] = 'Dies ist ein neues RPG-Spiel, in dem du darum kämpfen musst, um der mächtigste der Mafiosi der Unterwelt zu werden. <br> Du kannst illegale Aktionen wie Verbrechen, Autodiebstahl, Erpressung durchführen oder Glücksspiele wie Blackjack, Roulette und vieles mehr spielen .';
?>