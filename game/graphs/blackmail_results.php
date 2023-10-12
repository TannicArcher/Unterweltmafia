<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	include('../../system/config.php');
	
	header($config['ajax_default_header']);
	
	if (IS_ONLINE == false || $config['limited_access'] == true)
	{
		exit('Unable to load graph data. #1');
	}
	
	include('../../system/libs/open_flash_chart/open-flash-chart.php');
	
	
	$days = array();
	
	$sql = $db->Query("SELECT extra,timestamp FROM `[log]` WHERE `playerid`='".Player::Data('id')."' AND `side`='utpressing'");
	while ($blackmail = $db->FetchArray($sql))
	{
		$extra = unserialize($blackmail['extra']);
		
		if ($extra['result'] != 'success' && !$extra['blackmail_by'])
		{
			continue;
		}
		
		if ($extra['blackmail_by'])
		{
			$days[date('d.m.Y', $blackmail['timestamp'])] -= $extra['money'];
		}
		else
		{
			$days[date('d.m.Y', $blackmail['timestamp'])] += $extra['money'];
		}
	}
	
	
	$get_days = 30;
	$dates = array();
	for ($i = 1; $i <= $get_days; $i++)
	{
		$dates[] = date('d.m.Y', strtotime('- ' . ($get_days - $i) . ' days', time()));
	}
	
	$data['date'] = array();
	$data['cash'] = array();
	$values = array();
	
	foreach ($dates as $date)
	{
		$data['date'][] = str_replace('&aring;', 'å', trim(View::Time(strtotime($date), false, '', false)));
		
		$cash = $days[$date] ? intval($days[$date]) : 0;
		$d = new hollow_dot($cash);
		$data['cash'][] = $d->colour('#FF6600')->size(4)->tooltip('#x_label#<br>#val# $');
		$values[] = $cash;
	}
	
	
	$chart = new open_flash_chart();
	$chart->set_bg_colour('#1b1b1b');
	$chart->set_number_format(0, false, '.', ' ');
	
	$title = new title('Blackmail by '.Player::Data('name').' in '.$get_days.' days.');
	$title->set_style('{font-size: 10px; font-family: Verdana; color: #eeeeee; text-align: center;}');
	$chart->set_title($title);
	
	$area = new area();
	
	$area->set_width(2);
	$area->set_colour('#ff6600');
	$area->set_fill_colour('#0b0b0b');
	$area->set_fill_alpha(0.6);
	$area->set_values($data['cash']);
	$area->set_key('Bani', 10);
	
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
	$max_num = $max_num < 80000 ? 80000 : $max_num;
	
	if (abs($min_num) > $max_num)
		$max_num = abs($min_num);
	
	$y->set_range(intval(round($min_num, -4)), intval(round($max_num, -4)), 12);
	
	$y_labels = new y_axis_labels();
	$y_labels->set_colour('#999999');
	$y_labels->set_size(7);
	$y_labels->set_text('#val# $');
	
	$y->set_labels($y_labels);
	
	$chart->add_y_axis($y);
	
	
	/*
		echo JSON
	*/
	echo $chart->toPrettyString();
?>