<?php
$sql = $db->Query("SELECT timestamp FROM `cron_tab` WHERE `name`='minute'");
$update = $db->FetchArray($sql);
	
$update_minutes = 15;
	
if(((60*$update_minutes)+$update['timestamp']) < time()){
	$db->Query("UPDATE `cron_tab` SET `timestamp`='".time()."' WHERE `name`='minute'");
	
	$sql = $db->Query("SELECT online_stats FROM `game_stats` ORDER BY last_updated DESC LIMIT 1");
	$stats = $db->FetchArray($sql);
	$game_stats['online_stats'] = unserialize($stats['online_stats']);
	
	$game_stats = array(
		'user_stats' => array(
			'num_total' => 0,
			'num_active' => 0,
			'num_deactivated' => 0,
			'regged_today' => 0,
			'regged_yesterday' => 0,
			'regged_2_days' => 0
		),
		'player_stats' => array(
			'num_total' => 0,
			'num_active' => 0,
			'num_dead' => 0,
			'num_deactivated' => 0,
			'ranklist' => array(),
			'ranklist_num' => 0,
			'regged_today' => 0,
			'regged_yesterday' => 0,
			'regged_2_days' => 0,
			'top_jail_breakouts' => array()
		),
		'money_stats' => array(
			'total' => 0,
			'players_cash' => 0,
			'players_bank' => 0,
			'players_points' => 0,
			'business' => 0,
			'families' => 0,
			'coinroll' => 0
		),
		'online_stats' => array(
			'highest_online' => array($game_stats['online_stats']['highest_online'][0], $game_stats['online_stats']['highest_online'][1]),
			'last_24_hours' => 0,
			'last_12_hours' => 0,
			'last_6_hours' => 0
		),
		'messages_stats' => array(
			'num_total' => 0
		),
		'forum_stats' => array(
			'threads_num_total' => 0,
			'posts_num_total' => 0,
			'num_total' => 0
		),
		'logevent_stats' => array(
			'num_total' => 0
		)
	);
	
	$cash_log = 0;
	$points_log = 0;
	
	$crew_businesses = array(1);
	$crew_coinrolls = array(1);
	$crew_families = array(1);
	
	$game_economy = array();
	
	/*
		USER STATS
	*/
	$sql = $db->Query("SELECT id,userlevel,last_active,reg_time FROM `[users]`");
	while ($user = $db->FetchArray($sql))
	{
		$game_stats['user_stats']['num_total']++;
		if($user['userlevel']  > 0)
			$game_stats['user_stats']['num_active']++;
		else
			$game_stats['user_stats']['num_deactivated']++;
		
		$reg = date('j.m.Y', $user['reg_time']);
		if($reg == date('j.m.Y'))
			$game_stats['user_stats']['regged_today']++;
		elseif($reg == date('j.m.Y', time() - 86400))
			$game_stats['user_stats']['regged_yesterday']++;
		elseif($reg == date('j.m.Y', time() - 172800))
			$game_stats['user_stats']['regged_2_days']++;
			
		if ($user['last_active'] > time() - 86400)
			$game_stats['online_stats']['last_24_hours']++;
		
		if ($user['last_active'] > time() - 43200)
			$game_stats['online_stats']['last_12_hours']++;
		
		if ($user['last_active'] > time() - 21600)
			$game_stats['online_stats']['last_6_hours']++;
	}
	
	
	/*
		PLAYER STATS
	*/
	$rankPositions = array();
	$current_pos = 0;
	
	$family_rankpoints = array();
	
	$players = array();
	$topJail = array();
	
	$sql = $db->Query("SELECT id,level,health,bank,cash,rank,created,points,rank_pos,last_active,`wanted-level`,family,rankpoints,jail_stats FROM `[players]` ORDER BY rankpoints DESC");
	while ($player = $db->FetchArray($sql))
	{
		$players[$player['id']] = $player;
		
		if ($player['level'] < 3 && ($player['level'] > 0 && $player['health'] > 0)) $current_pos++;
		
		$rankPositions[] = array(
			'player' => $player['id'],
			'old' => $player['rank_pos'],
			'new' => ($player['level'] < 3 && !($player['level'] <= 0 || $player['health'] <= 0) ? $current_pos : 0)
		);
		
		$game_stats['player_stats']['num_total']++;
		
		$reg = date('j.m.Y', $player['created']);
		if($reg == date('j.m.Y'))
			$game_stats['player_stats']['regged_today']++;
		elseif($reg == date('j.m.Y', time() - 86400))
			$game_stats['player_stats']['regged_yesterday']++;
		elseif($reg == date('j.m.Y', time() - 172800))
			$game_stats['player_stats']['regged_2_days']++;
		
		if ($player['level'] <= 0 || $player['health'] <= 0)
		{
			$game_stats['player_stats']['num_' . ($player['level'] <= 0 ? 'deactivated' : 'dead')]++;
		}
		else
		{
			$game_stats['player_stats']['num_active']++;
			
			if (!empty($player['family']))
			{
				$family_rankpoints[$player['family']] += $player['rankpoints'];
			}
			
			if ($player['level'] < 3)
			{
				$cash_log += $player['cash'] + $player['bank'];
				$points_log += $player['points'];
				$game_stats['money_stats']['total'] += $player['cash'] + $player['bank'];
				$game_stats['money_stats']['players_cash'] += $player['cash'];
				$game_stats['money_stats']['players_bank'] += $player['bank'];
				$game_stats['money_stats']['players_points'] += $player['points'];
				$game_stats['player_stats']['ranklist'][$player['rank']]++;
				$game_stats['player_stats']['ranklist_num']++;
				
				$jailStats = unserialize($player['jail_stats']);
				if ($jailStats['breakouts_successed'] > 0)
					$topJail[$player['id']] = $jailStats['breakouts_successed'];
			}
		}
		
		if (time() - $player['last_active'] > 1800 && $player['wanted-level'] > 0)
		{
			$db->Query("UPDATE `[players]` SET `wanted-level`='0' WHERE `id`='".$player['id']."'");
		}
		
		$economy = array();
		if ($player['cash'] >= 100000) $economy['cash'] = $player['cash'];
		if ($player['bank'] >= 100000) $economy['bank'] = $player['bank'];
		if ($player['points'] >= 1) $economy['points'] = $player['points'];
		
		if (count($economy) > 0) $game_economy['players_economy'][$player['id']] = $economy;
	}
	
	foreach ($rankPositions as $position)
	{
		if ($position['new'] != $position['old'])
		{
			$db->Query("UPDATE `[players]` SET `rank_pos`='".$position['new']."' WHERE `id`='".$position['player']."'");
		}
	}
	
	arsort($topJail);
	foreach (array_slice($topJail, 0, 10, true) as $p_id => $num)
	{
		$game_stats['player_stats']['top_jail_breakouts'][] = array($p_id, $num);
	}
	
	
	/*
		MONEY STATS
	*/
	$sql = $db->Query("SELECT id,bank FROM `businesses` WHERE `active`='1'");
	while ($firma = $db->FetchArray($sql))
	{
		$game_stats['money_stats']['total'] += $firma['bank'];
		$game_stats['money_stats']['business'] += $firma['bank'];
		
		if (!in_array($firma['id'], $crew_businesses))
			$cash_log += $firma['bank'];
		
		$economy = array();
		$economy['bank'] = $firma['bank'];
		
		$game_economy['businesses_economy'][$firma['id']] = $economy;
	}
	
	$sql = $db->Query("SELECT id,bank FROM `coinroll` WHERE `active`='1'");
	while ($firma = $db->FetchArray($sql))
	{
		$game_stats['money_stats']['total'] += $firma['bank'];
		$game_stats['money_stats']['coinroll'] += $firma['bank'];
		
		if (!in_array($firma['id'], $crew_coinrolls))
			$cash_log += $firma['bank'];
		
		$economy = array();
		$economy['bank'] = $firma['bank'];
		
		$game_economy['coinroll'][$firma['id']] = $economy;
	}
	
	$sql = $db->Query("SELECT id,bank,total_rankpoints FROM `[families]` WHERE `active`='1'");
	while ($family = $db->FetchArray($sql))
	{
		$game_stats['money_stats']['total'] += $family['bank'];
		$game_stats['money_stats']['families'] += $family['bank'];
		
		if (!in_array($family['id'], $crew_families))
			$cash_log += $family['bank'];
		
		$economy = array();
		$economy['bank'] = $family['bank'];
		
		$game_economy['families_economy'][$family['id']] = $economy;
		
		if ($family_rankpoints[$family['id']] != $family['total_rankpoints'])
		{
			$db->Query("UPDATE `[families]` SET `total_rankpoints`='".$family_rankpoints[$family['id']]."' WHERE `id`='".$family['id']."'");
		}
	}
	
	$sql = $db->Query("SELECT players,entry_sum FROM `numbers_game` WHERE `active`='1'");
	while ($game = $db->FetchArray($sql))
	{
		$cash_log += count(unserialize($game['players'])) * $game['entry_sum'];
	}
	
	$sql = $db->Query("SELECT dealer_bet,opponent_bet FROM `blackjack` WHERE `active`='1'");
	while ($game = $db->FetchArray($sql))
	{
		$cash_log += $game['dealer_bet'] + $game['opponent_bet'];
	}
	
	$sql = $db->Query("SELECT bids,payment_method FROM `auctions` WHERE `active`='1'");
	while ($auction = $db->FetchArray($sql))
	{
		$bids = unserialize($auction['bids']);
		$payment = $auction['payment_method'];
		
		$highest = array();
		foreach ($bids as $bid)
		{
			if ($bid['sum'] > $highest['bid'])
				$highest = $bid;
		}
		
		if ($payment == 'cash')
		{
			$cash_log += $highest['bid'];
		}
		else
		{
			$points_log += $highest['bid'];
		}
	}
	
	/*
		MESSAGE STATS
	*/
	$message_senders = array();
	
	$sql = $db->Query("SELECT creator FROM `messages`");
	while ($message = $db->FetchArray($sql))
	{
		$game_stats['messages_stats']['num_total']++;
		
		if ($players[$message['creator']]['health'] > 0 && $players[$message['creator']]['level'] > 0)
			$message_senders[$message['creator']]++;
	}
	
	
	/*
		FORUM STATS
	*/
	$creators = array();
	
	$sql = $db->Query("SELECT playerid FROM `forum_topics`");
	while ($topic = $db->FetchArray($sql))
	{
		$game_stats['forum_stats']['num_total']++;
		$game_stats['forum_stats']['threads_num_total']++;
		$creators[$topic['playerid']]++;
	}
	
	$sql = $db->Query("SELECT playerid FROM `forum_replies`");
	while ($post = $db->FetchArray($sql))
	{
		$game_stats['forum_stats']['num_total']++;
		$game_stats['forum_stats']['posts_num_total']++;
		
		if ($players[$post['playerid']]['health'] > 0 && $players[$post['playerid']]['level'] > 0)
			$creators[$post['playerid']]++;
	}
	
	$game_stats['logevent_stats']['num_total'] = $db->GetNumRows($db->Query("SELECT id FROM `logevents`"));

	$db->Query("UPDATE `game_stats` SET
	`user_stats`='".serialize($game_stats['user_stats'])."',
	`player_stats`='".serialize($game_stats['player_stats'])."',
	`money_stats`='".serialize($game_stats['money_stats'])."',
	`online_stats`='".serialize($game_stats['online_stats'])."',
	`messages_stats`='".serialize($game_stats['messages_stats'])."',
	`forum_stats`='".serialize($game_stats['forum_stats'])."',
	`logevent_stats`='".serialize($game_stats['logevent_stats'])."',
	`last_updated`='".time()."'");
	
	
	$db->Query("UPDATE `game_economy` SET `money`='".$cash_log."', `points`='".$points_log."', `time`='".time()."', `players_economy`='".serialize($game_economy['players_economy'])."', `businesses_economy`='".serialize($game_economy['businesses_economy'])."', `families_economy`='".serialize($game_economy['families_economy'])."' WHERE `id`='1'");
}
?>