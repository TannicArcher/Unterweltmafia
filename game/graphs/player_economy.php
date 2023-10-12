<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	include('../../system/config.php');
	
	header($config['ajax_default_header']);
	
	if (IS_ONLINE == false || $config['limited_access'] == true)
	{
		exit('ERROR #1');
	}
	
	
	$player = $db->EscapeString($_GET['player']);
	$sql = $db->Query("SELECT id,name FROM `[players]` WHERE `id`='".$player."'");
	$player = $db->FetchArray($sql);
	
	if ($player['id'] == '' || ($player['id'] != Player::Data('id') && Player::Data('level') < 3))
	{
		exit('ERROR #2');
	}
	
	$get_days = 30;
	$timeAdd = View::NumbersOnly($_GET['time_add']);
	$timeAdd *= 86400 * $get_days;
	if (!is_numeric($timeAdd))
	{
		$timeAdd = 0;
	}
	$time = time() - $timeAdd;
	
	$player_economy = array();
	$sql = $db->Query("SELECT players_economy,time FROM `game_economy` WHERE `time`<'".$time."' ORDER BY time ASC");
	while ($log = $db->FetchArray($sql))
	{
		$player_e = unserialize($log['players_economy']);
		$player_e = $player_e[$player['id']];
		
		$player_economy[] = array(
			'time' => $log['time'],
			'cash' => $player_e['cash'],
			'bank' => $player_e['bank'],
			'points' => $player_e['points']
		);
	}
	
	include('../../system/libs/open_flash_chart/open-flash-chart.php');
	
	$days = array();
	foreach ($player_economy as $economy)
	{
		$economy['cash'] = $economy['cash'];
		$economy['bank'] = $economy['bank'];
		$economy['points'] = $economy['points'];
		
		$days[date('d.m.Y', $economy['time'])] = $economy;
	}
	
	$dates = array();
	for ($i = 1; $i <= $get_days; $i++)
	{
		$dates[] = date('d.m.Y', strtotime('- ' . ($get_days - $i) . ' days', $time));
	}
	
	$data['date'] = array();
	$data['cash'] = array();
	$data['bank'] = array();
	$data['points'] = array();
	$values = array();
	
	foreach ($dates as $date)
	{
		$data['date'][] = str_replace('&aring;', 'å', trim(View::Time(strtotime($date), false, '', false)));
		
		$cash = $days[$date]['cash'] ? $days[$date]['cash'] : 0;
		$bank = $days[$date]['bank'] ? $days[$date]['bank'] : 0;
		$points = $days[$date]['points'] ? $days[$date]['points'] : 0;
		
		$values[] = max(array($cash, $bank, $points));
		
		$d = new hollow_dot($cash);
		$data['cash'][] = $d->colour('#008b00')->size(3)->tooltip('#x_label#<br>Cash<br>#val# $');
		
		$d = new hollow_dot($bank);
		$data['bank'][] = $d->colour('#ff0000')->size(3)->tooltip('#x_label#<br>Bank<br>#val# $');
		
		$d = new hollow_dot($points);
		$data['points'][] = $d->colour('#004f8c')->size(3)->tooltip('#x_label#<br>Coins<br>#val# C');
	}
	
	$chart = new open_flash_chart();
	$chart->set_bg_colour('#1b1b1b');
	$chart->set_number_format(0, false, '.', ' ');
	
	$title = new title('Stats for '.$player['name'].' in '.$get_days.' days'."\n".'(- '.View::CashFormat($_GET['time_add']).' Monate).');
	$title->set_style('{font-size: 10px; font-family: Verdana; color: #eeeeee; text-align: center;}');
	$chart->set_title($title);
	
	// Areas
	$area = new area();
	$area->set_width(1);
	$area->set_colour('#008b00');
	$area->set_values($data['cash']);
	$area->set_key('Cash', 10);
	$chart->add_element($area);
	
	$area = new area();
	$area->set_width(1);
	$area->set_colour('#ff0000');
	$area->set_values($data['bank']);
	$area->set_key('Bank', 10);
	$chart->add_element($area);
	
	$area = new area();
	$area->set_width(1);
	$area->set_colour('#004f8c');
	$area->set_values($data['points']);
	$area->set_key('Coins', 10);
	$chart->add_element($area);
	
	// /Areas
	
	$x_labels = new x_axis_labels();
	$x_labels->set_steps(1);
	$x_labels->rotate(-40);
	$x_labels->set_colour('#eeeeee');
	$x_labels->set_labels($data['date']);
	
	$x = new x_axis();
	$x->set_colours('#333333', '#161616');
	$x->set_offset(false);
	$x->set_steps(1);
	
	$x->set_labels($x_labels);
	
	$chart->set_x_axis($x);
	
	$y = new y_axis();
	$y->set_tick_length(16);
	$y->set_colours('#333333', '#161616');
	
	$min_num = min($values);
	$max_num = max($values);
	$max_num = $max_num < 500000 ? 500000 : $max_num;
	
	$y->set_range($min_num, $max_num, 10);
	
	$y_labels = new y_axis_labels();
	$y_labels->set_colour('#999999');
	$y_labels->set_size(7);
	$y_labels->set_text('#val#');
	
	$y->set_labels($y_labels);
	
	$chart->add_y_axis($y);
	
	
	/*
		echo JSON
	*/
	echo $chart->toPrettyString();
?>