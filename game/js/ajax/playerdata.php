<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	require('../../../system/config.php');
	
	header($config['ajax_default_header']);
	
	if (!IS_ONLINE)
	{
		die('You have to be loggedin');
	}
	elseif ($config['limited_access'] == true)
	{
		die();
	}
	
	$player = $db->EscapeString($_GET['name']);
	$player = $db->QueryFetchArray("SELECT id,`name`,profileimage,last_active,vip_days,cash,bank,rank,family,rank_pos FROM `[players]` WHERE `name`='" . $player . "'");

	$family = false;
	if (!empty($player['family']))
	{
		$fam = $db->QueryFetchArray("SELECT id,name,boss,underboss FROM `[families]` WHERE `active`='1' AND `id`='".$player['family']."'");

		if (!empty($fam['id']))
		{
			$family = array(
				'id' => $fam['id'],
				'name' => $fam['name'],
				'status' => $fam['boss'] == $player['id'] ? 'Boss' : ($fam['underboss'] == $player['id'] ? 'Deputy' : 'Member')
			);
		}
	}
?>
<div class="profileStuff">
	<div class="leftSide">
    	<h2><a href="<?=$config['base_url']?>s/<?=$player['name']?>"<?=($player['vip_days'] > 0 ? 'class="vip_pl"' : '')?>><?=$player['name']?></a></h2>
        <div class="playerinfo">
        	<p><?=$langBase->get('ot-rank')?>: <span><?=$config['ranks'][$player['rank']][0]?></span></p>
            <p><?=$langBase->get('ot-rankplace')?>: <span>#<?=$player['rank_pos']?></span></p>
            <p><?=$langBase->get('ot-rankcash')?>: <span><?=(View::MoneyRank($player['cash']+$player['bank'], true))?></span></p>
        	<p><?=$langBase->get('ot-lasta')?>: <span><?=(View::strTime(time()-$player['last_active']))?></span></p>
            <ul style="margin-top: 15px;">
            	<?php if ($family) echo '<li>' . $family['status'] . ' in <a href="' . $config['base_url'] . '?side=familie/familie&amp;id=' . $family['id'] . '">' . $family['name'] . '</a></li>';?>
            </ul>
        </div>
    </div>
    <div class="rightSide">
    	<p class="avatar"><img src="<?=$player['profileimage']?>" alt="<?=$player['name']?>" class="handle_image noZoom" /></p>
        <ul>
        	<li><a href="<?=$config['base_url']?>s/<?=$player['name']?>"><?=$langBase->get('ot-view-p')?></a></li>
            <?php if ($player['id'] != Player::Data('id')) echo '<li><a href="' . $config['base_url'] . '?side=mesaje&amp;a=ny&amp;nick=' . $player['name'] . '">'.$langBase->get('ot-send-pm').'</a></li>';?>
        </ul>
    </div>
    <div class="clear"></div>
</div>