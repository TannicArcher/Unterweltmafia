<?php
	session_start();
	define('BASEPATH', true);

	$config['sql_type'] = 'MySQL';		// MySQL or MySQLi
	require('../../../system/database.php');
	require('../../../system/libs/database/'.$config['sql_type'].'.php');
	
	$db = new MySQLConnection($config['sql_host'], $config['sql_username'], $config['sql_password'], $config['sql_database']);
	$db->Connect();

	$player = $db->QueryFetchArray("SELECT id FROM `[players]` WHERE `userid`='".$_SESSION['MZ_LoginData']['userid']."' AND `health`>'0' AND `level`>'0' ORDER BY id DESC LIMIT 1");

	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	
	$imageHash = $db->EscapeString($_GET['hash']);
	$script = $db->EscapeString($_GET['script']);
	
	$ab = $db->QueryFetchArray("SELECT images_data FROM `antibot_sessions` WHERE `playerid`='".$player['id']."' AND `script_name`='".$script."' AND `active`='1'");

	$images_data = json_decode($ab['images_data'], true);
	$imageFile = false;

	foreach ($images_data['images'] as $image)
	{
		if ($image['hash'] == $imageHash)
		{
			$imageFile = $image['file'];
			break;
		}
	}

	if (!$imageFile)
	{
		header('Content-type: text/plain');
		die('Invalid ID');
	}
	else
	{
		header('Content-type: image/jpeg');
	}

	$config['antibot_image_file_ext'] = '.jpg';
	$imageurl = 'images/' . $imageFile . $config['antibot_image_file_ext'];
	$img = imagecreatefromjpeg($imageurl);
	$num_lines = rand(6, 10);
	for ($i = 0; $i <= $num_lines; $i++)
	{
		$color = imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));
		imageline($img, rand(5,95), rand(5,95), rand(5,95), rand(5,95), $color);
	}
	imagefilter($img, IMG_FILTER_COLORIZE, rand(1, 50), rand(1, 50), rand(1, 50));
	imagejpeg($img, null, 30);
	imagedestroy($img);
?>