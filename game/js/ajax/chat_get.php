<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	require('../../../system/config.php');

	header('Content-type: text/plain');

	if (!IS_ONLINE)
	{
		$error = 'NOT-LOGGED-IN';
	}
	elseif ($config['limited_access'] == true)
	{
		$error = 'LIMITED-ACCESS';
	}

	if ($error)
	{
		die(json_encode(array('error' => $error)));
	}

	if(isset($_GET['gettime'])){
		echo time();
		exit;
	}
	
	function get_chat_smileys($str){
		$config = $GLOBALS['config'];

		foreach( $config['game_smileys'] as $smiley_id => $smiley ){
			$str = str_replace($smiley[0], '<img src="'.$config['game_smileys_path'].$smiley_id.'" />', $str);
		}

		return $str;
	}

	$time = $db->EscapeString(View::NumbersOnly($_GET['time']));
	$posts = array();
	if ($time <= 0 || empty($_GET['time']))
	{
		$sql = "SELECT player,text,time FROM `chat` WHERE `deleted`='0' ORDER BY id DESC LIMIT 30";
	}
	else
	{
		$sql = "SELECT player,text,time FROM `chat` WHERE `deleted`='0' AND `time`>'".$time."' ORDER BY id DESC";
	}
	$sql = $db->Query($sql);
	while ($post = $db->FetchArray($sql))
	{
		$posts[] = View::Player(array('id' => $post['player']), true) . ' &nbsp; <span class="dark">' . View::HowLongAgo($post['time']) . '</span><div class="hr big"></div>' . nl2br(get_chat_smileys(View::NoHTML(trim($post['text']))));
	}

	echo json_encode($posts);
?>