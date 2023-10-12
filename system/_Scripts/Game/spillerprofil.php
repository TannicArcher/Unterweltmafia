<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$player = $db->EscapeString($_GET['name']);
	
	if ($player == Player::Data('name'))
	{
		 $player = Player::$datavar;
		 $user = User::$datavar;
		 
		 if (Player::FamilyData('id') != '')
		 	$family = Player::$familyDatavar;
	}
	else
	{
		$player = $db->QueryFetchArray("SELECT * FROM `[players]` WHERE `name`='$player'");
		$user = $db->QueryFetchArray("SELECT * FROM `[users]` WHERE `id`='".$player['userid']."'");
		$family = $db->QueryFetchArray("SELECT id,name,image,boss,underboss FROM `[families]` WHERE `id`='".$player['family']."'");
	}
	
	if ($player['id'] == '')
	{
		View::Message($langBase->get('err-02'), 2, true, '/game/?side=cautare&name='.$_GET['name']);
	}
	
	if ($player['health'] <= 0)
	{
		$status = $langBase->get('min-47');
	}
	elseif ($player['level'] <= 0)
	{
		$status = $langBase->get('min-48');
	}
	else
	{
		$status = $langBase->get('min-46');
	}
	$status .= ' | ';
	$status .= $user['online']+3600 < time() || $user['hasPlayer'] == 0 || $player['level'] <= 0 || $player['health'] <= 0 ? '<span style="color: #693838;">'.$langBase->get('profil-02').'</span>' : ($player['status'] == 1 ? '<span style="color: #386943;">'.$langBase->get('profil-01').'</span>' : ($player['status'] == 2 ? '<span style="color: #6f2121;">'.$langBase->get('profil-03').'</span>' : '<span style="color: #9b5c31;">'.$langBase->get('profil-04').'</span>'));
	
	
	$brekk = $db->QueryFetchArray("SELECT stats FROM `brekk` WHERE `playerid`='".$player['id']."'");
	$brekkstats = unserialize($brekk['stats']);
	$brekk_performed = $brekkstats['failed'] + $brekkstats['sucessfull'];
	
	$car_theft = $db->QueryFetchArray("SELECT stats FROM `car_theft` WHERE `playerid`='".$player['id']."'");
	$car_theft_stats = unserialize($car_theft['stats']);
	$car_thefts = $car_theft_stats['total_success'] + $car_theft_stats['total_failed'];
	
	$blackmail = $db->QueryFetchArray("SELECT stats FROM `blackmail` WHERE `playerid`='".$player['id']."'");
	$blackmail_stats = unserialize($blackmail['stats']);
	$blackmails = $blackmail_stats['total_success'] + $blackmail_stats['total_failed'] + $blackmail_stats['not_found'];
	
	$forumposts = explode(",", $player['forum_num_posts']);
	$forum_posts = $forumposts[0] + $forumposts[1];
?>
<style type="text/css">
	<!--
	#profile_wrap {font-size: 11px;color: #515151;width: 639px;margin: 0px auto}
	#profile_wrap #profile_top {width: 639px;margin: -10px auto;font-size: 12px;color: #4e4e4e}
	#profile_wrap #profile_top .content {width: 601px;padding: 0 19px 0 19px;background: url(../../../game/images/playerprofile_top_content.png) center repeat-y;position: relative;height: 280px}
	#profile_wrap #profile_top .bottom {width: 639px;height: 30px;background: url(../../../game/images/playerprofile_top_bottom.png) center top no-repeat}
	#profile_wrap #profile_top .content .seperator {width: 2px;height: 264px;background: url(../../../game/images/playerprofile_seperator.png) center top no-repeat;float: right;margin: 5px 15px 0 15px}
	#profile_wrap #profile_top .content .player {float: left;position: relative;margin-left: 20px;width: 200px}
	#profile_wrap #profile_top .content .player p.rank_pos { position: absolute; margin: 0; left: 0; top: 13px; color: #777777}
	#profile_wrap #profile_top .content .player p.playername { position: absolute; margin: 0; right: 0; top: 7px; font-size: 18px}
	#profile_wrap #profile_top .content .player p.status { position: absolute; margin: 0; right: 0; top: 30px; font-size: 12px; color: #353535}
	#profile_wrap #profile_top .content .player p.profileimage { position: absolute; margin: 0; right: 0; top: 50px; font-size: 12px; width: 200px; height: 200px}
	#profile_wrap #profile_top .content .playerinfo {float: left;position: relative}
	#profile_wrap #profile_top .content .playerinfo p {font-size: 12px;margin: 15px 0 0 30px;width: 195px;color: #4e4e4e;float: left}
	#profile_wrap #profile_top .content .playerinfo p span {color: #777777}
	#profile_wrap #profile_top .content .family {float: left;width: 125px;margin-left: 20px}
	#profile_wrap #profile_top .content .family h2 {margin: 10px 0 10px 0;text-align: center;color: #2c2c2c}
	-->
</style>
<div id="profile_wrap">
	<div id="profile_top">
    	<div class="content">
        	<div class="player">
            	<p class="rank_pos" style="font-size: <?=($player['rank_pos'] <= 99 && $player['rank_pos'] > 0 ? ($player['rank_pos'] <= 9 ? 26 : 22) : 18)?>px;">#<?=View::CashFormat($player['rank_pos'])?></p>
                <p class="playername"><?=View::Player($player)?></p>
                <p class="status"><?php echo $status;?></p>
                <p class="profileimage">
                	<img src="<?=$player['profileimage']?>" alt="" class="handle_image" />
                </p>
                <div class="seperator" style="margin-right: -20px;"></div>
            </div>
            <div class="playerinfo">
            	<p>
                	<?=$langBase->get('ot-rank')?>: <span><?=$config['ranks'][$player['rank']][0]?></span> (<span><?=View::MoneyRank($player['cash'] + $player['bank'], true)?></span>)<br />
                    <?=$langBase->get('ot-lasta')?>: <span><?=View::HowLongAgo($player['last_active'])?></span><br />
                    <?=$langBase->get('min-06')?>: <span><?=View::HowLongAgo($player['created'])?></span><br />
                    <br />
                    <?=$langBase->get('profil-05', array('-POINTS-' => View::CashFormat($player['killpoints'])))?><br />
                    <?=$langBase->get('profil-06', array('-NUM1-' => View::CashFormat($player['kills']+$player['kills_failed']), '-NUM2-' => View::CashFormat($player['kills'])))?><br />
					<?=$langBase->get('profil-07', array('-NUM1-' => View::CashFormat($brekk_performed), '-NUM2-' => View::CashFormat($car_thefts), '-NUM3-' => View::CashFormat($blackmails)))?><br />
					<?=$langBase->get('profil-16', array('-NUM-' => View::CashFormat($player['respect'])))?><br />
                    <br />
                    <?=$langBase->get('profil-08')?>: <span><?=View::CashFormat($player['messages_sent'])?> <?=$langBase->get('profil-10')?></span><br>
					<?=$langBase->get('profil-09')?>: <span><?=View::CashFormat($forum_posts)?> <?=$langBase->get('profil-10')?></span><br><br>
					<? if($player['play_profile_music'] == 1 && $player['profile_music'] != ""){?>					
					<embed src="<?=$config['base_url']?>flash/player.swf" width="205" height="20" allowscriptaccess="always" allowfullscreen="true" wmode="transparent" flashvars="width=205&height=20&controlbar=bottom&file=<?=$player['profile_music']?>" />
					<?}?>
                </p>
                <div class="seperator" style="margin-right: -20px;"></div>
            </div>
            <div class="family center">
            	<h2><?=$langBase->get('ot-family')?></h2>
                <?php
				if ($family['id'] == '')
				{
					echo '<p class="dark small">'.$langBase->get('profil-11').'</p>';
				}
				else
				{
					echo '<p style="width: 70px; height: 70px; margin: 0px auto;"><img src="' . $family['image'] . '" alt="" class="handle_image" /></p>
					<p style="font-size: 12px;"><a href="' . $config['base_url'] . '?side=familie/familie&amp;id=' . $family['id'] . '">' . $family['name'] . '</a></p>
					<dl class="dd_right small" style="padding: 5px;">
							<dt>'.$langBase->get('profil-12').'</dt>
							<dd>' . View::Player(array('id' => $family['boss'])) . '</dd>
							<dt>'.$langBase->get('profil-13').'</dt>
							<dd>' . View::Player(array('id' => $family['underboss']), false, 'N/A') . '</dd>
						</dl>';
				}
				?>
            </div>
            <div class="clear"></div>
        </div>
        <div class="bottom"></div>
    </div>
<?php
$profiletext = $player['profiletext'];
$profiletext = trim($profiletext) == '' ? '[i]'.$langBase->get('profil-14').'[/i]' : $profiletext;

$rankp = View::AsPercent($player['rankpoints']-$config['ranks'][$player['rank']][1], $config['ranks'][$player['rank']][2]-$config['ranks'][$player['rank']][1], 4);
$wanted = View::AsPercent($player['wanted-level'], $config['max_wanted-level'], 2);
$health = View::AsPercent($player['health'], $config['max_health'], 2);

$jailstats = unserialize($player['jail_stats']);

$profiletext = new BBCodeParser($profiletext, 'playerprofile', true, array(
	'/\[level rang\]/' => $config['ranks'][$player['rank']][0],
	'/\[level prozent\]/' => round($rankp, 4),
	'/\[level balken\]/' => '<div class="small_progressbar w200"><div class="progress" style="width: ' . round($rankp, 0) . '%;"><p>' . $rankp . ' %</p></div></div>',
	
	'/\[prozentuale Gefahr\]/' => round($wanted, 2),
	'/\[Gefahrenbalken\]/' => '<div class="small_progressbar w200"><div class="progress" style="width: ' . round($wanted, 0) . '%;"><p>' . $wanted . ' %</p></div></div>',
	
	'/\[Leben Prozent\]/' => round($health, 2),
	'/\[Lebensleiste\]/' => '<div class="small_progressbar w200"><div class="progress" style="width: ' . round($health, 0) . '%;"><p>' . $health . ' %</p></div></div>',
	
	'/\[Bargeld\]/' => View::CashFormat($player['cash']) . ' $',
	'/\[Bankgeld\]/' => View::CashFormat($player['bank']) . ' $',
	'/\[Gesamtgeld\]/' => View::CashFormat(($player['cash']+$player['bank'])) . ' $',
	
	'/\[Ausbruch\]/' => View::CashFormat($jailstats['breakouts_successed'])
));

echo '<div style="margin-top: 20px; margin-bottom: 20px; margin-left:10px;">'. $profiletext->result. '</div>';

if (User::Data('userlevel') >= 2)
{
	if (isset($_POST['crew_note']))
	{
		$note = $db->EscapeString($_POST['crew_note']);
		$db->Query("UPDATE `[players]` SET `crew_note`='".$note."' WHERE `id`='".$player['id']."'");
		
		View::Message('OK!', 1, true);
	}
	
	$bunker = $db->QueryFetchArray("SELECT last_session_ends FROM `bunker` WHERE `player`='".$player['id']."'");
?>
<div class="hr big" style="margin: 10px 0 10px 0;"></div>
<div class="bg_c w400">
	<h2><?=$langBase->get('admin-01')?></h2>
	<form method="post" action="">
		<p class="center"><textarea name="crew_note" cols="60" rows="8"><?php echo $player['crew_note'];?></textarea></p>
		<p class="center"><input type="submit" value="<?=$langBase->get('min-56')?>" /></p>
	</form>
	<?php
	if (User::Data('userlevel') >= 3){
	?>
	<h2><?=$langBase->get('admin-02')?></h2>
	<div class="left" style="width: 190px;">
		<dl class="dd_right">
			<dt><?=$langBase->get('min-05')?></dt>
			<dd><?=$player['id']?></dd>
			<dt><?=$langBase->get('admin-03')?></dt>
			<dd><?=$user['id']?></dd>
			<dt><?=$langBase->get('min-07')?></dt>
			<dd>
				<span class="dark"><?=$langBase->get('ot-money')?>:</span> <?=View::CashFormat($player['cash'])?> $<br />
				<span class="dark"><?=$langBase->get('c_firma-01')?>:</span> <?=View::CashFormat($player['bank'])?> $<br />
				<span class="dark"><?=$langBase->get('min-20')?>:</span> <?=(View::CashFormat($player['cash']+$player['bank']))?> $
			</dd>
			<dt><?=$langBase->get('ot-points')?></dt>
			<dd><?=View::CashFormat($player['points'])?> C</dd>
			<dt><?=$langBase->get('ot-bullets')?></dt>
			<dd><?=View::CashFormat($player['bullets'])?> G</dd>
		</dl>
		<div class="clear"></div>
	</div>
	<div class="left" style="width: 190px; margin-left: 20px;">
		<dl class="dd_right">
			<dt><?=$langBase->get('ot-rank')?></dt>
			<dd><?=$config['ranks'][$player['rank']][0]?><br /><?=$rankp?> %<br /><span class="small"><?=View::CashFormat($player['rankpoints'])?></span></dd>
			<dt><?=$langBase->get('ot-wanted_level')?></dt>
			<dd><?=$wanted?> %</dd>
			<dt><?=$langBase->get('ot-health')?></dt>
			<dd><?=$health?> %</dd>
			<dt><?=$langBase->get('txt-05')?></dt>
			<dd><?=$config['places'][$player['live']][0]?></dd>
			<?php if ($bunker['last_session_ends']-time() > 0){ ?><dt><span style="color:yellow">Jucatorul se afla in buncar!</span></dt><?php }?>
		</dl>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	<p class="center" style="margin-top: 25px;">
		<a href="<?=$config['base_url']?>?side=game_panel/player&amp;id=<?=$player['id']?>" class="button"><?=$langBase->get('txt-06')?></a> 
		<a href="<?=$config['base_url']?>?side=game_panel/user&amp;id=<?=$user['id']?>" class="button"><?=$langBase->get('header-09')?></a>
	</p>
	<?php } ?>
</div>
<?php } ?>
<div class="hr big" style="margin: 10px 0 10px 0;"></div>
</div>