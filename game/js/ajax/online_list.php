<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	require_once('../../../system/config.php');
	
	header($config['ajax_default_header']);

	if(!IS_ONLINE){
		die('ERROR:offline');
	}elseif($config['limited_access'] == true){
		die('ERROR:limited_access');
	}elseif(!in_array($_GET['view'], array('grid', 'list'))){
		die('ERROR:view');
	}

	$users = $db->QueryFetchArrayAll("SELECT id,userlevel FROM `[users]` WHERE `online`+'3600' > '".time()."' ORDER BY last_active DESC");

	if( $_GET['view'] == 'grid' )
	{
		foreach($users as $user)
		{
			$player = $db->QueryFetchArray("SELECT id,name,profileimage FROM `[players]` WHERE `userid`='".$user['id']."' ORDER BY id DESC");		
?>
<a href="<?=$config['base_url']?>/s/<?=$player['name']?>" class="user_container" onMouseOver="$('#<?=$player['id']?>').stop(true, true).slideDown('fast')" onMouseOut="$('#<?=$player['id']?>').stop(true, true).slideUp('fast')"><img src="<?=$player['profileimage']?>" alt="" class="profileimage" /><span id="<?=$player['id']?>"><?=$player['name']?></span></a>
<?php
		}
		echo '<div class="clear"></div>';

	}
	else
	{
		foreach($users as $user)
		{
			$player = $db->QueryFetchArray("SELECT id,name,profileimage,rank,last_active,level,health FROM `[players]` WHERE `userid`='".$user['id']."' ORDER BY id DESC");
?>
<div class="user_container" onclick="window.location.href = '<?=$config['base_url']?>/s/<?=$player['name']?>';"><img src="<?=$player['profileimage']?>" alt="<?=$player['profileimage']?>" class="profileimage" /><span class="name"><?=View::Player($player)?><br /><?=$config['ranks'][$player['rank']][0]?></span><div class="right">Pengerank: x<br />Ultima activitate: <?=View::Time($player['last_active'])?></div><a href="<?=$config['base_url']?>?side=meldingssenter&amp;a=new&amp;nick=<?=$player['name']?>" class="send_pm">Send PM</a></div>
<?php
		}
	}
?>