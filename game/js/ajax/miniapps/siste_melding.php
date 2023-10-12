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

	$messages = $db->QueryFetchArrayAll("SELECT * FROM `forum_topics` WHERE `forum_id`!='5' AND (`deleted`='0' AND `locked`='0') ORDER BY last_reply DESC LIMIT 6");

	if(!count($messages)) die('<h2 class="center">Nothing found!</center>');
?>
<table width="95%" class="table">
	<thead>
    	<tr>
        	<td width="40%"><?=$langBase->get('ot-subject')?></td>
            <td width="22%"><?=$langBase->get('ot-lastr')?></td>
        </tr>
    </thead>
	<tbody>
    	<?php
			foreach($messages as $message):
				$i++;
				$color = ($i%2) ? 1 : 2;
		?>
        <tr class="c_<?=$color?>"><td><a href="<?=$config['base_url']?>?side=forum/topic&id=<?=$message['id']?>" class="topic<?=($i++%2 ? '' : ' op')?>"><?=View::NoHTML($message['title'])?></a></td><td><?=($message['last_reply_playerid'] == 0 ? View::Player(array('id' => $message['playerid'])) : View::Player(array('id' => $message['last_reply_playerid'])))?></td></tr>
        <?php
			endforeach;
		?>
    </tbody>
</table>