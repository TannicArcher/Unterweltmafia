<?php
	define('IS_AJAX', false);
	define('BASEPATH', true);
	include_once('../../../../system/config.php');

	header($config['ajax_default_header']);

	if(!IS_ONLINE){
		die('<h2>You have to be loggedin!</h2>');
	}elseif($config['limited_access'] == true){
		die('<h2>Access denied!</h2>');
	}
	
	$db->Query("UPDATE `[users]` SET `miniapps_last`='logghandlinger' WHERE `id`='".User::Data('id')."'");

	$logevents = $db->QueryFetchArrayAll("SELECT data,type FROM `logevents` WHERE `user`='".User::Data('id')."' AND `archived`='0' ORDER BY id DESC LIMIT 5");

	if(count($logevents) <= 0) die('<h2 class="center"Nothing found!</center>');
?>
<table width="95%" class="table">
	<tbody>
    	<?php
			foreach($logevents as $event):
				$i++;
				$color = ($i%2) ? 1 : 2;
		?>
        <tr class="c_<?=$color?>"><td><?=View::NoImages(str_replace('<br />', "\n", $langBase->getLogEventText($event['type'], unserialize(base64_decode($event['data'])))))?></td></tr>
        <?php
			endforeach;
		?>
    </tbody>
</table>