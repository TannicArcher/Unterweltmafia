<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	require_once('../../../system/config.php');
	
	header('Content-type: text/plain');
	
	if (!IS_ONLINE)
	{
		$error = 'NOT-LOGGED-IN';
	}
	elseif ($config['limited_access'] == true)
	{
		$error = 'ACCES-LIMITAT';
	}
	
	if ($error)
	{
		die();
	}
	
	$progress['health'] = View::AsPercent(Player::Data('health'), $config['max_health'], 2);
	$progress['wanted'] = View::AsPercent(Player::Data('wanted-level'), $config['max_wanted-level'], 2);
	$progress['rank'] = View::AsPercent(Player::Data('rankpoints')-$config['ranks'][Player::Data('rank')][1], $config['ranks'][Player::Data('rank')][2]-$config['ranks'][Player::Data('rank')][1], 2);
	
	$hospitalData = unserialize(Player::Data('hospital_data'));
	$hospital_timeleft = $hospitalData['added'] + $hospitalData['time_length'] - time();
	
	$sql = $db->Query("SELECT id,added,penalty FROM `jail` WHERE `player`='".Player::Data('id')."' AND `added`+`penalty`>".time()." AND `active`='1'");
	$jail = $db->FetchArray($sql);
	
	$jailTime = 0;
	if ($jail['id'] != '')
	{
		$jailTime = $jail['added'] + $jail['penalty'] - time();
		if ($jailTime < 0) $jailTime = 0;
	}
	
	$data = array(
		'menuAdds' => array(),
		
		'progressbars' => '<div class="progressbar" title="Viata: '.$progress['health'].' %"><div class="progress" style="width: '.round($progress['health'], 0).'%;"><p>'.$langBase->get('ot-health').': '.$progress['health'].' %</p></div></div>
			<div class="progressbar" title="Pericol: '.$progress['wanted'].' %"><div class="progress" style="width: '.round($progress['wanted'], 0).'%;"><p>'.$langBase->get('ot-wanted_level').': '.$progress['wanted'].' %</p></div></div>
			<div class="progressbar" title="'.$config['ranks'][Player::Data('rank')][0].', '.$progress['rank'].' %"><div class="progress" style="width: '.round($progress['rank'], 0).'%;"><p>'.$langBase->get('ot-rank').': '.$progress['rank'].' %</p></div></div>'
	);
	
	// top
	$data['top'] = '';
	
	if ($_GET['get'] == 'logghandlinger')
	{
		$sql       = $db->Query("SELECT data,type FROM `" . $config['sql_logdb'] . "`.`logevents` WHERE `user`='".User::Data('id')."' AND `archived`='0' ORDER BY id DESC LIMIT 5");
		$logevents = $db->FetchArrayAll($sql);
		
		if( count($logevents) <= 0 ){
			$data['top'] = '<h2 class="center">'.$langBase->get('topMenu_log_no').'</h2>';
		}
		else
		{
			$events = '';
			
			foreach ($logevents as $event)
			{
				$i++;
				$color = $i%2 ? 1 : 2;
				
				$events .= '<tr class="c_'.$color.'"><td>'.View::NoImages(str_replace('<br />', "\n", $langBase->getLogEventText($event['type'], unserialize(base64_decode($event['data']))))).'</td></tr>';
			}
			
			$data['top'] = '
				<table class="table">
					<tbody>
					'.$events.'
					</tbody>
				</table>
			';
		}
	}
	else
	{
		$rankboost = unserialize(Player::Data('rankboost'));
		
		$support_num = 0;
		if (Player::Data('level') >= 2)
		{
			$support_num = $db->GetNumRows($db->Query("SELECT id FROM `support_tickets` WHERE `treated`='0' AND `reservation`='0' LIMIT 1"));
		}
		
		$isProtected = false;
		if (Player::Data('created')+$config['kill_protection'] > time() && Player::Data('kill_protection') == 1)
			$isProtected = true;
		
		if ($hospital_timeleft <= 0 && $jailTime <= 0 && $isProtected)
		{
			$bottomMsg = $langBase->get('topMenu_playerinfo_prt').' <p><a href="' . $config['base_url'] . '?side=startside&amp;' . $_SESSION['MZ_ProtectionTime_Stop_GET'] . '" class="button">'.$langBase->get('topMenu_playerinfo_prt_d').'</a></p>';
		}
		else
		{
			$bottomMsg = ($jailTime > 0 ? $langBase->get('ot-arestat', array('-TIME-' => $jailTime)).'<br />' : '') . ' 
					' . ($hospital_timeleft > 0 ? $langBase->get('ot-spital', array('-TIME-' => $hospital_timeleft)) : '');
		}
		
		$data['top'] = '<div class="spillerinfo">
			<div class="section"><center>
				'.View::Player(Player::$datavar).'<br />
				'.$langBase->get('ot-location').' <span><b><a href="'.$config['base_url'].'?side=harta">'.$config['places'][Player::Data('live')][0].'</a></b></span><br />
				'.$langBase->get('ot-cmoney').' <span>'.View::CashFormat(Player::Data('cash')).'$</span></center>
				<div class="hr big" style="margin: 5px 20px 5px 10px;"></div>
				'.$langBase->get('ot-rank').': <span>'.$config['ranks'][Player::Data('rank')][0].' ('.$progress['rank'].'%)</span><br />
				'.$langBase->get('ot-health').': <span>'.$progress['health'].'%</span><br />
				'.$langBase->get('ot-wanted_level').': <span class="wanted_progress">'.$progress['wanted'].'%</span><br />
				'.$langBase->get('ot-rankplace').': <span>'.View::CashFormat(Player::Data('rank_pos')).'</span>
				'.($rankboost['ends'] > time() && !empty($rankboost) ? '<br />Progres rapid: <span>'.(round(100 - View::AsPercent($rankboost['ends'] - time(), $rankboost['ends'] - $rankboost['started'], 2), 2)).'%</span> progress' : '').'
				'.(Player::Data('level') >= 3 ? '<p style="position: absolute; bottom: 0;"><a href="/admin-panel" target="_blank">Admin Panel</a></p>' : '').'
				'.($support_num > 0 ? '<p style="position: absolute; bottom: 0; right: 10px;"><a href="'.$config['base_url'].'?side=support&amp;panel">Support</a></p>' : '').'
			</div>
			<div class="section s_right">
				'.$langBase->get('ot-weapon').': <span>'.(Player::Data('weapon') == 0 ? 'N/A' : $config['weapons'][Player::Data('weapon')]['name']).'</span><br />
				'.$langBase->get('ot-protection').': <span>'.(Player::Data('protection') == 0 ? 'N/A' : $config['protections'][Player::Data('protection')]['name']).'</span><br />
				'.$langBase->get('ot-bullets').': <span>'.View::CashFormat(Player::Data('bullets')).'</span><br />
				'.$langBase->get('ot-points').': <span><a href="' . $config['base_url'] . '?side=magazin-credite">'.Player::Data('points').'</a></span><br />
				'.$langBase->get('ot-family').': <span>'.(Player::FamilyData('id') == '' ? 'N/A' : '<a href="' . $config['base_url'] . '?side=familie/familie&amp;id=' . Player::FamilyData('id') . '">' . Player::FamilyData('name') . '</a>').'</span>
				<div class="hr big" style="margin: 5px 20px 5px 10px;"></div>
				' . $bottomMsg . '
			</div>
		</div>';
	}
	
	$menuads = array(
		'brekk_timeleft',
		'blackmail_timeleft',
		'car_theft_timeleft',
		'killtime_timeleft',
		'pr_timeleft',
		'in_jail',
		'new_logevents',
		'new_pms',
		'online',
		'training_timeleft',
		'blackjack_num',
		'auction_num',
		'rtnr_timeleft'
	);
	
	$hospital_data = unserialize(Player::Data('hospital_data'));
	$hospitalTime = $hospital_data['added'] + $hospital_data['time_length'] - time();
	
	$waitTime = 0;
	if ($jailTime > 0 || $hospitalTime > 0)
	{
		$waitTime = $jailTime > $hospitalTime ? $jailTime : $hospitalTime;
	}
	
	foreach ($menuads as $ad_id)
	{
		if ($ad_id == 'brekk_timeleft')
		{
			$sql = $db->Query("SELECT last,latency FROM `brekk` WHERE `playerid`='".Player::Data('id')."'");
			$brekk = $db->FetchArray($sql);
			
			$last_crime = explode(",", $brekk['last']);
			$last_time  = $last_crime[0];
			
			$latency = round($last_time + $brekk['latency'] - time(), 0);
			$latency = $latency <= 0 ? 0 : $latency;
			
			$addData = $waitTime > $latency ? $waitTime : $latency;
		}
		elseif ($ad_id == 'blackmail_timeleft')
		{
			$sql = $db->Query("SELECT last_time,latency FROM `blackmail` WHERE `playerid`='".Player::Data('id')."'");
			$blackmail = $db->FetchArray($sql);
			
			$latency = $blackmail['last_time'] + $blackmail['latency'] - time();
			$latency = $latency <= 0 ? 0 : $latency;
			
			$addData = $waitTime > $latency ? $waitTime : $latency;
		}
		elseif ($ad_id == 'car_theft_timeleft')
		{
			$sql = $db->Query("SELECT last_time,latency FROM `car_theft` WHERE `playerid`='".Player::Data('id')."'");
			$car_theft = $db->FetchArray($sql);
			
			$latency = $car_theft['last_time'] + $car_theft['latency'] - time();
			$latency = $latency <= 0 ? 0 : $latency;
			
			$addData = $waitTime > $latency ? $waitTime : $latency;
		}
		elseif ($ad_id == 'killtime_timeleft')
		{
			$latency = $config['killtime_start'] - time();
			
			$addData = $waitTime > $latency ? $waitTime : $latency;
		}
		elseif ($ad_id == 'pr_timeleft')
		{
			$latency = Player::Data('last_planned_crime')+$config['planned_crime_wait_time'] - time();
			
			$addData = $waitTime > $latency ? $waitTime : $latency;
		}
		elseif ($ad_id == 'in_jail')
		{
			$num = $db->GetNumRows($db->Query("SELECT id FROM `jail` WHERE `active`='1'"));
			$addData = $num;
		}
		elseif ($ad_id == 'new_logevents')
		{
			$num = $db->GetNumRows($db->Query("SELECT id FROM `" . $config['sql_logdb'] . "`.`logevents` WHERE `user`='".Player::Data('userid')."' AND `read`='0'"));
			$addData = $num;
		}
		elseif ($ad_id == 'new_pms')
		{
			$num = 0;
			
			$sql = $db->Query("SELECT views,last_reply FROM `messages` WHERE `players` LIKE '%|" . Player::Data('id') . "|%' AND `deleted`='0'");
			while ($message = $db->FetchArray($sql))
			{
				$views = unserialize($message['views']);
				if ($message['last_reply'] > $views[Player::Data('id')]) $num++;
			}
			
			$addData = $num;
		}
		elseif ($ad_id == 'online')
		{
			$num = $db->GetNumRows($db->Query("SELECT id FROM `[players]` WHERE `online`+'3600' > '".time()."'"));
			$addData = $num;
			
			$sql = $db->Query("SELECT online_stats FROM `game_stats` ORDER BY last_updated DESC LIMIT 1");
			$stats = $db->FetchArray($sql);
			$online_stats = unserialize($stats['online_stats']);
			
			if ($num > $online_stats['highest_online'][0])
			{
				$online_stats['highest_online'] = array($num, time());
				$db->Query("UPDATE `game_stats` SET `online_stats`='".serialize($online_stats)."'");
			}
		}
		elseif ($ad_id == 'training_timeleft')
		{
			$sql = $db->Query("SELECT last_training,training_wait FROM `fighting` WHERE `player`='".Player::Data('id')."'");
			$my_fighter = $db->FetchArray($sql);
			
			$latency = $my_fighter['last_training']+$my_fighter['training_wait'] - time();
			$latency = $latency <= 0 ? 0 : $latency;
			
			$addData = $waitTime > $latency ? $waitTime : $latency;
		}
		elseif ($ad_id == 'blackjack_num')
		{
			$num = $db->GetNumRows($db->Query("SELECT id FROM `blackjack` WHERE `active`='1' AND `expired`='0' AND `started`+".$config['blackjack_game_expire']." > ".time().""));
			$addData = $num;
		}
		elseif ($ad_id == 'auction_num')
		{
			$num = $db->GetNumRows($db->Query("SELECT id FROM `auctions` WHERE `active`='1'"));
			$addData = $num;
		}
		elseif ($ad_id == 'rtnr_timeleft')
		{
			$wtt = (Player::Data('vip_days') > 0 ? 2700 : 3600);
			$addData = ((Player::Data('roata_noroc') + $wtt) - time());
		}
		
		$data['menuAdds'][] = array(
			'class' => $ad_id,
			'data' => $addData
		);
	}
	
	echo json_encode($data);
?>