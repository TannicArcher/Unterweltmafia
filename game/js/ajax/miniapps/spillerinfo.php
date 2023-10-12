<?php
	define('IS_AJAX', false);
	define('BASEPATH', true);
	include('../../../../system/config.php');

	header($config['ajax_default_header']);

	if(!IS_ONLINE){
		die('<h2>You have to be loggedin!</h2>');
	}elseif($config['limited_access'] == true){
		die('<h2>Access denied!</h2>');
	}
	
	// Update last miniapp used
	$db->Query("UPDATE `[users]` SET `miniapps_last`='spillerinfo' WHERE `id`='".User::Data('id')."'");
	
	// progress
	$progressbars['liv']    = View::AsPercent(Player::Data('health'), $config['max_health'], 2);
	$progressbars['wanted'] = View::AsPercent(Player::Data('wanted-level'), $config['max_wanted-level'], 2);
	
	$rankp = View::AsPercent(Player::Data('rankpoints')-$config['ranks'][Player::Data('rank')][1], $config['ranks'][Player::Data('rank')][2]-$config['ranks'][Player::Data('rank')][1], 2);
	$progressbars['rank']   = $rankp;
	
	$hospitalData = unserialize(Player::Data('hospital_data'));
	$hospital_timeleft = $hospitalData['added'] + $hospitalData['time_length'] - time();
	
	$jail = $db->QueryFetchArray("SELECT id,added,penalty FROM `jail` WHERE `player`='".Player::Data('id')."' AND `added`+`penalty`>".time()." AND `active`='1'");

	$jailTime = 0;
	if ($jail['id'] != '')
	{
		$jailTime = $jail['added'] + $jail['penalty'] - time();
		if ($jailTime < 0) $jailTime = 0;
	}
	
	$rankboost = unserialize(Player::Data('rankboost'));
	
	$support_num = 0;
	if (Player::Data('level') >= 2)
	{
		$support_num = $db->QueryGetNumRows("SELECT id FROM `support_tickets` WHERE `treated`='0' AND `reservation`='0' LIMIT 1");
	}
	
	$isProtected = false;
	if (Player::Data('created')+$config['kill_protection'] > time() && Player::Data('kill_protection') == 1)
		$isProtected = true;
	
	if ($hospital_timeleft <= 0 && $jailTime <= 0 && $isProtected)
	{
		$bottomMsg = $langBase->get('topMenu_playerinfo_prt').' <p><a href="' . $config['base_url'] . '?side=startside&amp;' . $_SESSION['MZ_ProtectionTime_Stop_GET'] . '" class="button">'.$langBase->get('topMenu_playerinfo_prt_d').'</a></p>';
	}
	else
	{
		$bottomMsg = ($jailTime > 0 ? $langBase->get('ot-arestat', array('-TIME-' => $jailTime)).'<br />' : '') . ' 
				' . ($hospital_timeleft > 0 ? $langBase->get('ot-spital', array('-TIME-' => $hospital_timeleft)) : '');
	}
?>
<div class="spillerinfo">
	<div class="section">
		<center>
		<?=View::Player(Player::$datavar)?><br />
        <?=$langBase->get('ot-location')?> <span><b><a href="<?=$config['base_url']?>?side=harta"><?=$config['places'][Player::Data('live')][0]?></a></b></span><br />
        <?=$langBase->get('ot-cmoney')?> <span><?=View::CashFormat(Player::Data('cash'))?>$</span></center>
        <div class="hr big" style="margin: 5px 20px 5px 10px;"></div>
        <?=$langBase->get('ot-rank')?>: <span><?=$config['ranks'][Player::Data('rank')][0]?> (<?=$progressbars['rank']?>%)</span><br />
		<?=$langBase->get('ot-health')?>: <span><?=$progressbars['liv']?>%</span><br />
        <?=$langBase->get('ot-wanted_level')?>: <span class="wanted_progress"><?=$progressbars['wanted']?>%</span><br />
		<?=$langBase->get('ot-rankplace')?>: <span><?=View::CashFormat(Player::Data('rank_pos'))?></span>
        <?=($rankboost['ends'] > time() && !empty($rankboost) ? '<br />Progres Rapid: <span>'.(round(100 - View::AsPercent($rankboost['ends'] - time(), $rankboost['ends'] - $rankboost['started'], 2), 2)).' %</span> progress' : '')?>
        <?=($support_num > 0 ? '<p style="position: absolute; bottom: 0; right: 10px;"><a href="'.$config['base_url'].'?side=support&amp;panel">Support</a></p>' : '')?>
        <?php
		if (Player::Data('level') >= 3)
		{
			echo '<p style="position: absolute; bottom: 0;"><a href="/admin-panel" target="_blank">Admin Panel</a></p>';
		}
		?>
	</div>
    <div class="section s_right">
		<?=$langBase->get('ot-weapon')?>: <span><?=(Player::Data('weapon') == 0 ? 'N/A' : $config['weapons'][Player::Data('weapon')]['name'])?></span><br />
        <?=$langBase->get('ot-protection')?>: <span><?=(Player::Data('protection') == 0 ? 'N/A' : $config['protections'][Player::Data('protection')]['name'])?></span><br />
		<?=$langBase->get('ot-bullets')?>: <span><?=View::CashFormat(Player::Data('bullets'))?></span><br />
		<?=$langBase->get('ot-points')?>: <span><a href="<?=$config['base_url']?>?side=magazin-credite"><?=Player::Data('points')?></a></span><br />
        <?=$langBase->get('ot-family')?>: <span><?=(Player::FamilyData('id') == '' ? 'N/A' : '<a href="' . $config['base_url'] . '?side=familie/familie&amp;id=' . Player::FamilyData('id') . '">' . Player::FamilyData('name') . '</a>')?></span>
        <div class="hr big" style="margin: 5px 20px 5px 10px;"></div>
        <?php echo $bottomMsg;?>
	</div>
</div>