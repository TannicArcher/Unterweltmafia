<?php
	$starttime = microtime(true);
	
	define('BASEPATH', true);
	require('../system/config.php');
	
	if (!IS_ONLINE)
	{
		header("Location: ../index.php?orign=" . rawurlencode($_SERVER['REQUEST_URI']));
		exit;
	}

	$wanted_reset_price = 5;
	$wanted_reset_price = round($wanted_reset_price/100 * View::AsPercent(Player::Data('wanted-level'), $config['max_wanted-level']), 0);
	if ($wanted_reset_price < 1) $wanted_reset_price = 1;
	
	// Log out.
	if (isset($_GET['logout']))
	{
		$online_user->logout('IN-1');
		header("Location: ../index.php");
		exit;
	}
	elseif (isset($_GET['reset_wanted']) && Player::Data('wanted-level') > 0)
	{
		if ($wanted_reset_price > Player::Data('points'))
		{
			$wantedReset_msg = View::Message($langBase->get('err-09'), 2);
		}
		else
		{
			$db->Query("UPDATE `[players]` SET `points`=`points`-'".$wanted_reset_price."', `wanted-level`='0' WHERE `id`='".Player::Data('id')."'");
			Player::$datavar['wanted-level'] = 0;
			
			$wantedReset_msg = View::Message('Du hast <b>' . $wanted_reset_price . ' Coins</b> zum zurücksetzen von "Gefahrenstatus" ausgegeben.', 1);
		}
	}
	
	if (Player::Data('status') == 0)
	{
		$db->Query("UPDATE `[players]` SET `status`='1' WHERE `id`='".$online_user->uid."'");
	}
	
	/*
		Remove players from jail that has finished their penalty.
	*/
	$db->Query("UPDATE `jail` SET `active`='0' WHERE `active`='1' AND `added`+`penalty`<=".time()."");

	if (Player::Data('health') > $config['max_health'] && !empty($config['max_health']))
	{
		$db->Query("UPDATE `[players]` SET `health`='".$config['max_health']."' WHERE `id`='".$online_user->uid."'");
		Player::UpdateData();
	}
	
	if ($config['limited_access'] === true && User::Data('hasPlayer') == 1)
	{
		$db->Query("UPDATE `[users]` SET `hasPlayer`='0' WHERE `id`='".User::Data('id')."'");
	}
	elseif ($config['limited_access'] === false && User::Data('hasPlayer') == 0)
	{
		$db->Query("UPDATE `[users]` SET `hasPlayer`='1' WHERE `id`='".User::Data('id')."'");
	}
	
	function menu_show_links($category)
	{
		global $user_menuSort, $config;
		
		$links = $user_menuSort[$category];
		
		$tmp = array();
		foreach ($links as $link)
		{
			if (!$config['menu_links'][$category][$link] || $tmp[$link])
				continue;
			
			$tmp[$link] = true;
			
			$linkConfig = $config['menu_links'][$category][$link];
			echo '<li id="' . $category . '_' . $link . '"><span class="mouseMove"></span><a href="' . $linkConfig[1] . '" class="i_' . $linkConfig[2] . '">' . $linkConfig[0] . '</a>' . ($linkConfig[3] ? '<p class="menuad ' . $linkConfig[3] . '"></p>' : '') . '</li>';
		}
	}
	
	/*
	 * Game Tips
	*/
	$last_gametip_update = $_SESSION['MZ_GameTips_Last_Update'];
	$current_gametip = $_SESSION['MZ_GameTips_Current'];
	
	if ($last_gametip_update+$config['gametips_change_interval'] <= time() || !$config['gametips'][$current_gametip])
	{
		$_SESSION['MZ_GameTips_Last_Update'] = time();
		
		$current_gametip = array_rand($config['gametips']);
		$_SESSION['MZ_GameTips_Current'] = $current_gametip;
	}
	
	$player_mission = new Mission(Player::$datavar);
	$player_mission_data = $player_mission->missions_data[$player_mission->current_mission];
	
	if (in_array(6, $player_mission->active_minimissions))
	{
		if (Player::Data('rankpoints') - $player_mission->minimissions[6]['data']['startRankpoints'] >= 600)
		{
			$player_mission->miniMission_success(6);
		}
	}

	if ($player_mission->current_mission == 6 && $player_mission_data['started'] == 1 && $player_mission_data['objects'][0]['completed'] != 1) {
		$ref_num = $db->QueryGetNumRows("SELECT id FROM `[users]` WHERE `enlisted_by`='".User::Data('id')."' AND `reg_time`>='".$player_mission_data['start_time']."'");

		if ($player_mission_data['objects'][0]['completed'] != 1)
		{
			if($player_mission->missions_data[$player_mission->current_mission]['objects'][0]['num_rec'] != $ref_num){
				$player_mission->missions_data[$player_mission->current_mission]['objects'][0]['num_rec'] = $ref_num;
				$player_mission->saveMissionData();
			}

			if ($ref_num >= 5)
			{
				$player_mission->completeObject(0);
			}
		}
	}
	
	$isProtected = false;
	if (Player::Data('created')+$config['kill_protection'] > time() && Player::Data('kill_protection') == 1)
		$isProtected = true;
	
	if ($isProtected && !isset($_SESSION['MZ_ProtectionTime_Stop_GET']))
		$_SESSION['MZ_ProtectionTime_Stop_GET'] = substr(sha1(uniqid(rand())), 0, 6);
	
	if (isset($_GET['setLang']) && $languages_supported[$_GET['setLang']])
	{
		$lang = $languages_supported[$_GET['setLang']];
		$langBase->language = $lang[0];
		setcookie('MZ_Language', $langBase->language);
		
		View::Message($langBase->get('msgContent-langChange', array('-NEWLANG-' => $lang[2])), 1, true, '/game/?side=startside');
	}
	if (isset($_GET[$_SESSION['MZ_ProtectionTime_Stop_GET']], $_SESSION['MZ_ProtectionTime_Stop_GET']) && $isProtected)
	{
		unset($_SESSION['MZ_ProtectionTime_Stop_GET']);
		$isProtected = false;
		$db->Query("UPDATE `[players]` SET `kill_protection`='0' WHERE `id`='".Player::Data('id')."'");
		Player::$datavar['kill_protection'] = 0;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ro">
<head><title><?=$current_script[0]?> &bull; <?=$admin_config['game_name']['value']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?=$config['meta_keywords']?>" />
<meta name="description" content="<?=$config['meta_description']?>" />
<link rel="stylesheet" type="text/css" href="<?=$config['base_url']?>css/global.min.css" />
<script type="text/javascript">
	<!--
	var js_start = (new Date).getTime();
	var last_top = '<?=User::Data('miniapps_last')?>';
	var server_time = <?=time()?>;
	var clear = '<?=$langBase->get('js-01')?>';
	var close = '<?=$langBase->get('js-02')?>';
	var news = '<?=$langBase->get('js-03')?>';
	var bottombar_chatIsOpen = <?=(User::Data('chat_isOpen') == 1 && User::Data('ban_chat') == 0 ? 'true' : 'false')?>;
	-->
</script>
<script type="text/javascript" src="<?=$config['base_url']?>js/mootools.js"></script>
<script type="text/javascript">var js_mootools = (new Date).getTime();</script>
<script type="text/javascript" src="<?=$config['base_url']?>js/global_moo.min.js"></script>
<?php
	$graphs = $config['graphs'][$script_name];
	
	if( count($graphs) > 0 ){
?>
<script type="text/javascript" src="<?=$config['base_url']?>js/swfobject.js"></script>
<script type="text/javascript">
<!--
	<?php
		foreach($graphs as $graph)
		{
?>
swfobject.embedSWF("<?=$config['base_url']?>graphs/open-flash-chart.swf", "<?=$graph[0]?>", "<?=$graph[1]?>", "<?=$graph[2]?>", "9.0.0", "expressInstall.swf", {"data-file":"<?=rawurlencode($graph[3])?>", "loading":"Analizare date..."} );
<?php
		}
?>
-->
</script>
<?php
	}
?>
<link rel="shortcut icon" href="/favicon.ico" />
</head>
<body>
<div class="n_topbar">
<div class="n_links"><a href="<?=$config['base_url']?>?side=startside" style="color: #9bc104;"><img src="<?=$config['base_url']?>images/icons/home_icon.png" alt="" /><?=$langBase->get('header-01')?></a></div><div class="n_links"><a href="<?=$config['base_url']?>?side=stiri" style="color: #9bc104;"><img src="<?=$config['base_url']?>images/icons/nws.png" alt="" /><?=$langBase->get('header-02')?></a></div><div class="n_links"><a href="<?=$config['base_url']?>?logout" style="color: #c10404;"><img src="<?=$config['base_url']?>images/icons/dec.png" alt="" /><?=$langBase->get('header-03')?></a></div>
<div style="float: right;"><b><?=$langBase->get('ot-lang')?>:</b> <a href="<?=$config['base_url']?>?setLang=RO"><img src="<?=$config['game_url']?>/img/lang/ro.gif" class="n_ro"></a><a href="<?=$config['base_url']?>?setLang=EN"><img src="<?=$config['game_url']?>/img/lang/en.png" class="n_en"></a><a href="<?=$config['base_url']?>?setLang=FR"><img src="<?=$config['game_url']?>/img/lang/fr.png" class="n_fr"></a></div>
</div>
	<div id="default_container">
		<div id="default_header"<?php if (User::Data('small_header') == 1) echo ' class="header_small"';?>>
<?php if (User::Data('ads_hideUntil') < time() && User::Data('small_header') == 1):?>
<div style="position: absolute; left: 250px; top: 3px; width: 468px; height: 60px; overflow: hidden;">
<!-- Place your 468x60 banner here -->
</div>
<?php endif;?>
        	<p class="headerSize_toggle"><input type="checkbox" id="headerSize_toggle"<?php if (User::Data('small_header') == 1) echo ' checked="checked"'; ?> /> <label for="headerSize_toggle"><?=$langBase->get('header-04')?></label></p>
        	<div class="bottomleft">
                <?php if($config['limited_access'] === true) echo 'ID: ' . User::Data('id');?> <span id="au_status"></span><br />
            </div>
			<div class="bottomright">
                <a href="/rules.php" style="color: #9bc104;" target="_self"><img src="<?=$config['base_url']?>images/icons/page_white_text.png" height="14" alt="" /><b><?=$langBase->get('header-05')?></b></a>
            </div>
            <div id="header_content" style="width: 430px;">
            	<div class="gametips<? echo $langBase->get('header-06'); if(User::Data('small_header') == 1) echo ' hidden';?>"><?php echo $config['gametips'][$current_gametip];?></div>
            	<div id="progressbars">
					<?php
                    $progressbars['liv'] = View::AsPercent(Player::Data('health'), $config['max_health'], 2);
                    $progressbars['wanted'] = View::AsPercent(Player::Data('wanted-level'), $config['max_wanted-level'], 2);
                    $progressbars['rank'] = View::AsPercent(Player::Data('rankpoints')-$config['ranks'][Player::Data('rank')][1], $config['ranks'][Player::Data('rank')][2]-$config['ranks'][Player::Data('rank')][1], 4);
                    ?>
                    <div class="progressbar" title="Viata: <?=$progressbars['liv']?> %"><div class="progress" style="width: <?=round($progressbars['liv'], 0)?>%;"><p><?=$langBase->get('ot-health')?>: <?=$progressbars['liv']?> %</p></div></div>
                    <div class="progressbar" title="Pericol: <?=$progressbars['wanted']?> %"><div class="progress" style="width: <?=round($progressbars['wanted'], 0)?>%;"><p><?=$langBase->get('ot-wanted_level')?>: <?=round($progressbars['wanted'], 2)?> %</p></div></div>
                    <div class="progressbar" title="<?=$config['ranks'][Player::Data('rank')][0]?>: <?=$progressbars['rank']?> %"><div class="progress" style="width: <?=round($progressbars['rank'], 0)?>%;"><p><?=$langBase->get('ot-rank')?>: <?=round($progressbars['rank'], 2)?> %</p></div></div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
		<div id="default_left">
        	<div id="profileimage">
            	<div class="playername"><a href="<?=$config['base_url']?>s/<?=Player::Data('name')?>"><img src="<?=$config['base_url']?>images/icons/status_online.png" alt="" /><?=Player::Data('name')?></a></div>
            	<a href="<?=$config['base_url']?>s/<?=Player::Data('name')?>"><img src="<?=Player::Data('profileimage')?>" alt="<?=Player::Data('name')?>" class="profileimage" /></a>
                <div class="links">
                	<a href="<?=$config['base_url']?>?side=rediger_profil&amp;a=profileimage"><img src="<?=$config['base_url']?>images/icons/image_edit.png" alt="<?=$langBase->get('ot-avatar')?>" /><?=$langBase->get('ot-avatar')?></a>
                </div>
            </div>
            <div class="default_menu">
            	<div class="title"><h1><?=$langBase->get('menu-01')?></h1><h2><?=$langBase->get('menus-01')?></h2></div>
                <ul class="kriminalitet">
                	<?php menu_show_links('kriminalitet');?>
                    <li class="sep"></li>
                </ul>
                <div class="title"><h1><?=$langBase->get('menu-02')?></h1><h2><?=$langBase->get('menus-02')?></h2></div>
                <ul class="attraksjoner">
                	<?php menu_show_links('attraksjoner');?>
                    <li class="sep"></li>
                </ul>
                <div class="title"><h1><?=$langBase->get('menu-03')?></h1><h2><?=$langBase->get('menus-03')?></h2></div>
                <ul class="gambling">
                	<?php menu_show_links('gambling');?>
                    <li class="sep"></li>
                </ul>
            </div>
        </div>
        <div id="default_center">
            <div id="default_top">
            	<div id="default_top_menu">
                	<ul>
                    	<li><a href="#" rel="siste_melding"><span class="h1"><?=$langBase->get('topMenu_message_title')?><br /><span class="h2"><?=$langBase->get('topMenu_message_desc')?></span></span></a></li>
                        <li><a href="#" rel="logghandlinger"><span class="h1"><?=$langBase->get('topMenu_log_title')?><br /><span class="h2"><?=$langBase->get('topMenu_log_desc')?></span></span></a></li>
                        <li><a href="#" rel="spillerinfo"><span class="h1"><?=$langBase->get('topMenu_playerinfo_title')?><br /><span class="h2"><?=$langBase->get('topMenu_playerinfo_desc')?></span></span></a></li>
                    </ul>
                </div>
            	<div id="default_top_content">
                	<script type="text/javascript">
						<!--
						document.write('<p class="center"><img src="<?=$config['base_url']?>images/ajax_load.gif" alt="Loading..." style="margin-top: 65px;" /></p>');
						-->
					</script>
                    <noscript>
                    	<h2 class="center">Es wird JavaScript für dieses Spiel benötigt! Bitte aktiviere JavaScript!</h2>
                    </noscript>
                </div>
            </div>
            <div id="default_main">
<div class="main_head">
	<?php
    $smartMenu_items = array_reverse(unserialize(User::Data('smart_menu')));
    
    if (count($smartMenu_items) > 0 && User::Data('use_smartMenu') == 1)
    {
    ?>
    <div class="links">
    	<?php
		foreach ($smartMenu_items as $key)
		{
			$item = $config['scripts'][$key];
			if(!$item) continue;
			
			$text = substr($item[0], 0, 12);
			
			echo "<a href=\"".$config['base_url']."?side=" . $key . "\">" . (trim($text) . ($text != $item[0] ? '...' : '')) . "</a>\n";
		}
		?>
    </div>
    <?php
    }
    ?>
	<a href="<?=str_replace('&', '&amp;', $_SERVER['REQUEST_URI'])?>" class="title"><?=$current_script[0]?></a>
    <?php
		$submenu = $config['scripts_submenus'][$script_name];
		
		if( count($submenu) > 0 ){
	?>
	<ul id="main_submenu">
		<?php foreach($submenu as $item) echo '<li>' . $item . '</li>'; ?>
	</ul>
	<?php
		}
	?>
    <p class="head_time menuad time"><?=date('H:i')?></p>
</div>
<?php
	/*
		Messages
	*/
	function show_messages()
	{
		if (isset($_SESSION['MZ_Messages']))
		{
			foreach ($_SESSION['MZ_Messages'] as $message) echo $message . "\n";
			
			unset($_SESSION['MZ_Messages']);
		}
	}
	
	if ($current_script[4] == true) show_messages();
	if ($wantedReset_msg) echo $wantedReset_msg;
	
	require($config['scripts_path'] . $current_script[3] . "/" . $script_name . $config['scripts_ext']);
?>
            </div>
<?php if (User::Data('ads_hideUntil') < time()):?>
<div style="margin: 15px auto 15px; width: 468px;">
<!-- Place your 468x60 banner here -->
</div>
<?php endif;?>
        </div>
        <div id="default_right">
        	<div class="default_menu">
            	<div class="title"><h1 style="color: #728600; font-weight: bold;"><?=$langBase->get('menu-04')?></h1><h2 style="color: #394300;"><?=$langBase->get('menus-04')?></h2></div>
                <ul class="poeng">
                	<?php menu_show_links('poeng');?>
                    <li class="sep"></li>
                </ul>
            	<div class="title"><h1><?=$langBase->get('menu-05')?></h1><h2><?=$langBase->get('menus-05')?></h2></div>
                <ul class="forum">
                	<?php menu_show_links('forum');?>
                    <li class="sep"></li>
                </ul>
                <div class="title"><h1><?=$langBase->get('menu-06')?></h1><h2><?=$langBase->get('menus-06')?></h2><?php if(Player::FamilyData('id')) echo '<a href="'.$config['base_url'].'?side=familie/familie&amp;id='.Player::FamilyData('id').'" class="b_l">'.Player::FamilyData('name').'</a>'; ?></div>
                <ul class="familie">
                	<?php menu_show_links('familie');?>
                    <li class="sep"></li>
                </ul>
                <div class="title"><h1><?=$langBase->get('menu-07')?></h1><h2><?=$langBase->get('menus-07')?></h2></div>
                <ul class="bruker">
                	<?php menu_show_links('bruker');?>
                    <li class="sep"></li>
                </ul>
                <div class="title"><h1><?=$langBase->get('menu-08')?></h1><h2><?=$langBase->get('menus-08')?></h2></div>
                <ul class="system">
                	<?php menu_show_links('system');?>
                    <li class="sep"></li>
                </ul>
            </div>
        </div>
	</div>
    <div id="bottombar_wrap">
		<div class="chat_wrap<?=(User::Data('chat_isOpen') != 1 || User::Data('ban_chat') == 1 ? ' hidden' : '')?>">
        	<div class="chat_content">
            	<div class="write">
                	<form method="get" action="">
                    	<div>
                    		<div class="left"><textarea name="text" cols="30" rows="2" style="width: 238px;height:40px"></textarea></div>
                            <div class="right"><input type="submit" value="&laquo; Send" style="margin: 4px 0 0 3px; padding: 2px;" /></div>
                            <div class="clear"></div>
                        </div>
                        <p class="post_result center" style="margin: 0; padding: 5px 0 0 0;">&nbsp;</p>
                    </form>
                </div>
            	<div class="posts">
                    <?php
					if (User::Data('chat_isOpen') == 1 && User::Data('ban_chat') == 0)
					{
						$sql = $db->Query("SELECT `player`,`text`,`time` FROM `chat` WHERE `deleted`='0' ORDER BY id DESC LIMIT 30");
						while ($post = $db->FetchArray($sql))
						{
							echo '
						<div class="post">
							' . View::Player(array('id' => $post['player']), true) . ' &nbsp; <span class="dark">' . View::HowLongAgo($post['time']) . '</span><div class="hr big"></div>' . nl2br(View::NoHTML(trim($post['text']))) . '
						</div>
							';
						}
					}
					?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    	<div class="bar_content">
        	<div class="left">
				<ul class="nav">
                	<li><?php if(User::Data('ban_chat') == 0){?><a href="#" class="toggleChat"><img src="<?=$config['base_url']?>images/icons/email<?=(User::Data('u_chat') == 0 ? "" : "_add")?>.png" alt="" style="margin-bottom: -5px; margin-right: 5px;" />Chat</a><?}else{?><span><b>You was blocked on chat!</b></span><?}?></li>
                </ul>
            </div>
            <div class="right">
            	<ul class="nav">
                	<?php if(Player::Data('wanted-level') > 0){?><li><a href="<?=str_replace('&', '&amp;', $_SERVER['REQUEST_URI'])?>&amp;reset_wanted" onclick="return confirm('<?=$langBase->get('err-05')?>');"><img src="<?=$config['base_url']?>images/icons/user_suit.png" alt="" style="margin-bottom: -3px; margin-right: 5px;" /><?=$langBase->get('ot-r-danger')?> (<?php echo View::CashFormat($wanted_reset_price);?> <?=$langBase->get('ot-points')?>)</a></li><?php }?>
                	<li style="padding-top: 2px;"><a href="#" class="toggleJailLog<?=(Player::Data('show_jail_logevents') == 1 ? ' active' : '')?>" style="font-size: 9px;"><img src="<?=$config['base_url']?>images/icons/arrow_switch.png" alt="" style="margin-bottom: -5px; margin-right: 5px;" /><?=(Player::Data('show_jail_logevents') == 1 ? $langBase->get('ot-iston') : $langBase->get('ot-istoff'))?></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div id="default_bottom">
    	<p>All rights reserved &copy; <?=date('Y').' '.$admin_config['game_name']['value']?> - V<?=$admin_config['game_version']['value']?> - Powered by <a href="https://nmafia.unterweltmafia.de/" target="_self">Unterweltmafia</a></p>
        <p style="font-size: 9px; margin-top: 10px;">Script: <?=(round(microtime(true)-$starttime - $db->UsedTime, 4))?> Sek. - Datenbank: <?=(round($db->UsedTime, 4))?> Sek<span id="js_debug"></span><?php if(User::Data('userlevel') == 4) echo '<br />MySQL Queries: ' . $db->GetNumberOfQueries();?></p>
    </div>
</body>
</html>
<?php
	$db->Close();
	ob_end_flush();
?>