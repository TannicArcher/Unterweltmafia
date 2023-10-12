<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	include('../../system/config.php');
	
	header($config['ajax_default_header']);
	
	if (IS_ONLINE == false || $config['limited_access'] == true || Player::Data('level') < 3)
	{
		exit('ERROR #1');
	}
	
	$player = $db->EscapeString($_GET['player']);
	$sql = $db->Query("SELECT id,name FROM `[players]` WHERE `id`='".$player."'");
	$player = $db->FetchArray($sql);
	
	if ($player['id'] == '')
	{
		exit('Utilizator inexistent. #2');
	}
	
	$timeAdd = View::NumbersOnly($_GET['time_add']);
	$timeAdd *= 86400;
	if (!is_numeric($timeAdd))
	{
		$timeAdd = 0;
	}
	$time = time() - $timeAdd;
	
	include('../../system/libs/open_flash_chart/open-flash-chart.php');
	
	$days = array();
	
	$sql = $db->Query("SELECT result,added FROM `antibot_sessions` WHERE `playerid`='".$player['id']."' AND `active`='0'");
	while ($antibot = $db->FetchArray($sql))
	{
		$days[date('d.m.Y H', $antibot['added'])][$antibot['result'] == 1 ? 'success' : 'fail']++;
	}
	
	$get_hours = 24;
	$dates = array();
	for ($i = 1; $i <= $get_hours; $i++)
	{
		$dates[] = strtotime('- ' . ($get_hours - $i) . ' hours', $time);
	}
	
	$data['date'] = array();
	$data['success'] = array();
	$data['fail'] = array();
	$values = array();
	
	foreach ($dates as $date)
	{
		$data['date'][] = str_replace('&aring;', 'å', trim(View::Time($date, false, 'H', false)));
		
		$date = date('d.m.Y H', $date);
		
		$success = $days[$date]['success'] ? $days[$date]['success'] : 0;
		$fail = $days[$date]['fail'] ? $days[$date]['fail'] : 0;
		
		$d = new hollow_dot($success);
		$data['success'][] = $d->colour('#999999')->size(3)->tooltip('Success<br>#val# stk');
		
		$d = new hollow_dot($fail);
		$data['fail'][] = $d->colour('#999999')->size(3)->tooltip('Failed<br>#val# stk');
		
		$values[] = $success > $fail ? $success : $fail;
	}
	
	
	$chart = new open_flash_chart();
	$chart->set_bg_colour('#1b1b1b');
	$chart->set_number_format(0, false, '.', ' ');
	
	$title = new title('AntiBot Stats '.$player['name']."\n".'(- '.View::CashFormat($_GET['time_add']).' day(s).');
	$title->set_style('{font-size: 10px; font-family: Verdana; color: #eeeeee; text-align: center;}');
	$chart->set_title($title);
	
	$area = new area();
	$area->set_width(1);
	$area->set_colour('#008b00');
	$area->set_fill_colour('#008b00');
	$area->set_fill_alpha(0.6);
	$area->set_values($data['success']);
	$area->set_key('Succes', 10);
	
	$chart->add_element($area);
	
	$area = new area();
	$area->set_width(1);
	$area->set_colour('#ff0000');
	$area->set_fill_colour('#ff0000');
	$area->set_fill_alpha(0.5);
	$area->set_values($data['fail']);
	$area->set_key('Esuat', 10);
	
	$chart->add_element($area);
	
	$x_labels = new x_axis_labels();
	$x_labels->set_steps(2);
	$x_labels->rotate(-40);
	$x_labels->set_colour('#eeeeee');
	$x_labels->set_labels($data['date']);
	
	$x = new x_axis();
	$x->set_colours('#333333', '#161616');
	$x->set_offset(false);
	$x->set_steps(2);
	
	$x->set_labels($x_labels);
	
	$chart->set_x_axis($x);
	
	$y = new y_axis();
	$y->set_tick_length(16);
	$y->set_colours('#333333', '#161616');
	
	$min_num = min($values);
	$max_num = max($values);
	$max_num = $max_num < 5 ? 5 : $max_num;
	
	$y->set_range($min_num, $max_num, 4);
	
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