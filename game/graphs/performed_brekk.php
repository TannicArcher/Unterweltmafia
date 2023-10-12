<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	include('../../system/config.php');
	
	header($config['ajax_default_header']);
	
	if (IS_ONLINE == false || $config['limited_access'] == true)
	{
		exit('ERROR #1');
	}
	
	$player = isset($_GET['player']) ? $db->EscapeString($_GET['player']) : Player::Data('id');
	if ($player == Player::Data('id'))
	{
		$player = Player::$datavar;
	}
	else
	{
		$sql = $db->Query("SELECT id,name FROM `[players]` WHERE `id`='".$player."'");
		$player = $db->FetchArray($sql);
	}
	
	if ($player['id'] == '')
	{
		die('ERROR #2');
	}
	elseif ($player['id'] != Player::Data('id') && Player::Data('level') < 3)
	{
		die('No access. #3');
	}
	
	$timeAdd = View::NumbersOnly($_GET['time_add']);
	$timeAdd *= 86400;
	if (!is_numeric($timeAdd))
	{
		$timeAdd = 0;
	}
	$time = strtotime('23:59:59') - $timeAdd;
	
	include('../../system/libs/open_flash_chart/open-flash-chart.php');
	
	$days = array();
	$sql = $db->Query("SELECT id,timestamp,extra FROM `[log]` WHERE `playerid`='".$player['id']."' AND `side`='brekk' AND `timestamp`<='".$time."'");
	while ($event = $db->FetchArray($sql))
	{
		$extra = unserialize($event['extra']);
		
		$days[date('d.m.Y H', $event['timestamp'])][($extra['result'] == 'success' ? 'success' : 'failed')]++;
	}
	
	$get_hours = 24;
	$dates = array();
	for ($i = 1; $i <= $get_hours; $i++)
	{
		$dates[] = strtotime('- ' . ($get_hours - $i) . ' hours', $time);
	}
	
	$data['date'] = array();
	$data['success'] = array();
	$data['failed'] = array();
	$values = array();
	
	foreach ($dates as $date)
	{
		$data['date'][] = date('H', $date) . ':00';
		
		$success = $days[date('d.m.Y H', $date)]['success'] ? $days[date('d.m.Y H', $date)]['success'] : 0;
		$failed = $days[date('d.m.Y H', $date)]['failed'] ? $days[date('d.m.Y H', $date)]['failed'] : 0;
		
		$d = new hollow_dot($success);
		$data['success'][] = $d->colour('#999999')->size(3)->tooltip('#x_label#<br>Success<br>#val#');
		
		$d = new hollow_dot($failed);
		$data['failed'][] = $d->colour('#999999')->size(3)->tooltip('#x_label#<br>Failed<br>#val#');
		
		$values[] = $success > $failed ? $success : $failed;
	}
	
	$chart = new open_flash_chart();
	$chart->set_bg_colour('#1b1b1b');
	$chart->set_number_format(0, false, '.', ' ');
	
	$title = new title('Robberies stats for '.$player['name'].''."\n".'('.trim(str_replace('&aring;', 'å', View::Time($time, false, ''))).')');
	$title->set_style('{font-size: 10px; font-family: Verdana; color: #eeeeee; text-align: center;}');
	$chart->set_title($title);
	
	$area = new area();
	$area->set_width(1);
	$area->set_colour('#008b00');
	$area->set_fill_colour('#008b00');
	$area->set_fill_alpha(0.2);
	$area->set_values($data['success']);
	$area->set_key('Success', 10);
	$chart->add_element($area);
	
	$area = new area();
	$area->set_width(1);
	$area->set_colour('#ff0000');
	$area->set_fill_colour('#ff0000');
	$area->set_fill_alpha(0.1);
	$area->set_values($data['failed']);
	$area->set_key('Failures', 10);
	$chart->add_element($area);
	
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
	$max_num = $max_num < 10 ? 10 : $max_num;
	
	$y->set_range($min_num, $max_num, (round($max_num/10, -1) < 5 ? 5 : round($max_num/10, -1)));
	
	$y_labels = new y_axis_labels();
	$y_labels->set_colour('#999999');
	$y_labels->set_size(9);
	$y_labels->set_text('#val#');
	
	$y->set_labels($y_labels);
	
	$chart->add_y_axis($y);
	
	
	/*
		echo JSON
	*/
	echo $chart->toPrettyString();
?>