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
		$sql = $db->Query("SELECT * FROM `[players]` WHERE `name`='$player'");
		$player = $db->FetchArray($sql);
		
		$sql = $db->Query("SELECT * FROM `[users]` WHERE `id`='".$player['userid']."'");
		$user = $db->FetchArray($sql);
		
		$sql = $db->Query("SELECT id,name,image,boss,underboss FROM `[families]` WHERE `id`='".$player['family']."'");
		$family = $db->FetchArray($sql);
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
	
	
	$sql = $db->Query("SELECT stats FROM `brekk` WHERE `playerid`='".$player['id']."'");
	$brekk = $db->FetchArray($sql);
	$brekkstats = unserialize($brekk['stats']);
	$brekk_performed = $brekkstats['failed'] + $brekkstats['sucessfull'];
	
	$sql = $db->Query("SELECT stats FROM `car_theft` WHERE `playerid`='".$player['id']."'");
	$car_theft = $db->FetchArray($sql);
	$car_theft_stats = unserialize($car_theft['stats']);
	$car_thefts = $car_theft_stats['total_success'] + $car_theft_stats['total_failed'];
	
	$sql = $db->Query("SELECT stats FROM `blackmail` WHERE `playerid`='".$player['id']."'");
	$blackmail = $db->FetchArray($sql);
	$blackmail_stats = unserialize($blackmail['stats']);
	$blackmails = $blackmail_stats['total_success'] + $blackmail_stats['total_failed'] + $blackmail_stats['not_found'];
	
	$forumposts = explode(",", $player['forum_num_posts']);
	$forum_posts = $forumposts[0] + $forumposts[1];
?>
<style type="text/css">
	<!--
	#profile_wrap {
		font-size: 11px;
		color: #515151;
		width: 639px;
		margin: 0px auto;
	}
	
	#profile_wrap #profile_top {
		width: 639px;
		margin: -10px auto;
		font-size: 12px;
		color: #4e4e4e;
	}
	#profile_wrap #profile_top .content {
		width: 601px;
		padding: 0 19px 0 19px;
		background: url(../../../game/images/playerprofile_top_content.png) center repeat-y;
		position: relative;
		height: 260px;
	}
	#profile_wrap #profile_top .bottom {
		width: 639px;
		height: 30px;
		background: url(../../../game/images/playerprofile_top_bottom.png) center top no-repeat;
	}
	
	#profile_wrap #profile_top .content .seperator {
		width: 2px;
		height: 264px;
		background: url(../../../game/images/playerprofile_seperator.png) center top no-repeat;
		float: right;
		margin: 5px 15px 0 15px;
	}
	
	#profile_wrap #profile_top .content .player {
		float: left;
		position: relative;
		margin-left: 20px;
		width: 200px;
	}
	#profile_wrap #profile_top .content .player p.rank_pos { position: absolute; margin: 0; left: 0; top: 13px; color: #777777; }
	#profile_wrap #profile_top .content .player p.playername { position: absolute; margin: 0; right: 0; top: 7px; font-size: 18px; }
	#profile_wrap #profile_top .content .player p.status { position: absolute; margin: 0; right: 0; top: 30px; font-size: 12px; color: #353535; }
	#profile_wrap #profile_top .content .player p.profileimage { position: absolute; margin: 0; right: 0; top: 50px; font-size: 12px; width: 200px; height: 200px; }
	
	#profile_wrap #profile_top .content .playerinfo {
		float: left;
		position: relative;
	}
	#profile_wrap #profile_top .content .playerinfo p {
		font-size: 12px;
		margin: 15px 0 0 30px;
		width: 320px;
		color: #4e4e4e;
		float: left;
	}
	#profile_wrap #profile_top .content .playerinfo p span {
		color: #777777;
	}
	
	#profile_wrap #profile_top .content .family {
		float: left;
		width: 125px;
		margin-left: 20px;
	}
	#profile_wrap #profile_top .content .family h2 {
		margin: 10px 0 10px 0;
		text-align: center;
		color: #2c2c2c;
	}
	
	
	#userprofiel {
	position	: relative;
	width		: 100%;
	height		: 430px;
	margin-top	: 0px;
}
#userprofiel_naam {
	position	: absolute;
	top			: 10px;
	left		: 5px;
	display		: block;
	width		: 630px;
	height		: 45px;
	padding		: 0px;
	background	: #131313;
	z-index		: 2;
}
#userprofiel_naam_usericon {
	position	: absolute;
	left		: 3px;
	top			: -8px;
}
#userprofiel_naam_vlag {
	position	: absolute;
	right		: 110px;
	top			: -4px;
}
#userprofiel_naam_stad {
	position	: absolute;
	left		: 470px;
	top			: 10px;
	font-size	: 18px;
	font-weight	: normal;
	font-family	: arial;
	color		: #4A2500;
}
#userprofiel_naam_gebruikersnaam {
	position	: absolute;
	left		: 68px;
	top			: 1px;
	font-size	: 20px;
	font-weight	: bold;
	font-family	: arial;
	color		: #4A2500;
}
#userprofiel_naam_crewstatus {
	position	: absolute;
	left		: 69px;
	top			: 24px;
	font-size	: 15px;
	font-weight	: normal;
	font-family	: arial;
	color		: #4A2500;
}

#userprofiel_avatar {
	position	: absolute;
	top			: 60px;
	left		: 463px;
	display		: block;
	width		: 117px;
	height		: 152px;
	background	: #131313;
	color		: #4A2500;
	overflow	: hidden;
}
#userprofiel_avatar_image {
	position	: absolute;
	top			: 18px;
	left		: 11px;
	padding		: 2px;
	background	: #fff;
	border		: 1px solid #4A2500;
	width		: 90px;
	height		: 90px;
}
#userprofiel_kortetekst {
	display		: none !important;
	position	: absolute;
	top			: 58px;
	left		: 108px;
	display		: block;
	width		: 259px;
	height		: 107px;
	padding		: 5px;
	background	: #131313;
	color		: #4A2500;
	overflow	: hidden;
}
#userprofiel_buttons {
	position	: absolute;
	display		: block;
	top			: 58px;
	left		: 5px;
	display		: block;
	width		: 107px;
	height		: 334px;
	padding		: 15px 3px 0px 10px;
	background	: #131313;
	color		: #4A2500;
	overflow	: hidden;
	margin		: 0px;
	z-index		: 1;
}
#userprofiel_buttons table {
	position	: relative;
}
#userprofiel_buttons a:link,
#userprofiel_buttons a:visited,
#userprofiel_buttons a:active {
	display		: block;
	width		: 100px;
	height		: 15px;
	position	: relative;
	border		: 0px;
}
#userprofiel_buttons a:hover {
	text-decoration	: none;
}
#userprofiel_buttons a img {
	position	: absolute;
	left		: 0px;
	top			: -1px;
}
#userprofiel_buttons a span {
	position	: absolute;
	left		: 20px;
	top			: 0px;
}
#userprofiel_properties {
	position	: absolute;
	top			: 58px;
	left		: 128px;
	width		: 332px;
	display		: block;
	padding		: 0px;
	z-index		: 50;
}
#userprofiel_bescherming {
	position	: absolute;
	top			: 210px;
	left		: 128px;
	display		: block;
	width		: 202px;
	height		: 22px;
	padding		: 5px;
	color		: #4A2500;
	border-bottom: 1px solid #4a2500;
}
#userprofiel_bescherming_icon {
	position	: absolute;
	top			: 3px;
	left		: 5px;
	z-index		: 100;
}
#userprofiel_bescherming_titel {
	position	: absolute;
	left		: 34px;
	top			: 8px;
	font-size	: 15px;
	font-weight	: bold;
	font-family	: arial;
	color		: #4A2500;
}
#userprofiel_bescherming_content {
	position	: absolute;
	left		: -2px;
	top			: 32px;
	font-size	: 11px;
	font-family	: arial;
	color		: #4A2500;
}
#userprofiel_bescherming_content table {
	width		: 206px;
}
#userprofiel_geld {
	position	: absolute;
	display		: block;
	top			: 210px;
	left		: 333px;
	display		: block;
	width		: 237px;
	height		: 22px;
	padding		: 5px;
	color		: #4A2500;
	border-bottom: 1px solid #4a2500;
}
#userprofiel_geld_icon {
	position	: absolute;
	top			: 4px;
	left		: 5px;
	z-index		: 100;
}
#userprofiel_geld_titel {
	position	: absolute;
	display		: block;
	left		: 34px;
	top			: 8px;
	font-size	: 15px;
	font-weight	: bold;
	font-family	: arial;
	color		: #4A2500;
}
#userprofiel_geld_content {
	position	: absolute;
	left		: -2px;
	top			: 32px;
	font-size	: 11px;
	font-family	: arial;
	color		: #4A2500;
}
#userprofiel_geld_content table {
	width		: 251px;
}

#userprofiel_spelwaarden {
	position	: absolute;
	display		: block;
	top			: 287px;
	left		: 128px;
	display		: block;
	width		: 202px;
	height		: 22px;
	padding		: 5px;
	color		: #4A2500;
	border-bottom: 1px solid #4a2500;
}
#userprofiel_spelwaarden_icon {
	position	: absolute;
	top			: 3px;
	left		: 5px;
	z-index		: 100;
}
#userprofiel_spelwaarden_titel {
	position	: absolute;
	display		: block;
	left		: 34px;
	top			: 8px;
	font-size	: 15px;
	font-weight	: bold;
	font-family	: arial;
	color		: #4A2500;
}
#userprofiel_spelwaarden_content {
	position	: absolute;
	left		: -2px;
	top			: 32px;
	font-size	: 11px;
	font-family	: arial;
	color		: #4A2500;
}
#userprofiel_spelwaarden_content table {
	width		: 206px; 
}
#userprofiel_aanval {
	position	: absolute;
	display		: block;
	top			: 287px;
	left		: 333px;
	display		: block;
	width		: 237px;
	height		: 22px;
	padding		: 5px;
	color		: #4A2500;
	border-bottom: 1px solid #4a2500;
}
#userprofiel_aanval_icon {
	position	: absolute;
	top			: 4px;
	left		: 5px;
	z-index		: 100;
}
#userprofiel_aanval_titel {
	position	: absolute;
	display		: block;
	left		: 34px;
	top			: 8px;
	font-size	: 15px;
	font-weight	: bold;
	font-family	: arial;
	color		: #4A2500;
}
#userprofiel_aanval_content {
	position	: absolute;
	left		: -2px;
	top			: 32px;
	font-size	: 11px;
	font-family	: arial;
	color		: #4A2500;
}
#userprofiel_aanval_content table {
	width		: 251px;
}
#userprofiel_spelresultaten {
	position	: absolute;
	display		: block;
	top			: 405px;
	left		: 128px;
	display		: block;
	width		: 202px;
	height		: 22px;
	padding		: 5px;
	color		: #4A2500;
	border-bottom: 1px solid #4a2500;
}
#userprofiel_spelresultaten_icon {
	position	: absolute;
	top			: 3px;
	left		: 5px;
	z-index		: 100;
}
#userprofiel_spelresultaten_titel {
	position	: absolute;
	display		: block;
	left		: 34px;
	top			: 8px;
	font-size	: 15px;
	font-weight	: bold;
	font-family	: arial;
	color		: #4A2500;
}
#userprofiel_spelresultaten_content {
	position	: absolute;
	left		: -2px;
	top			: 32px;
	font-size	: 11px;
	font-family	: arial;
	color		: #4A2500;
}
#userprofiel_spelresultaten_content table {
	width		: 206px; 
}

	
	
	-->
</style>
<div id="profile_wrap">
	<div id="profile_top">
    	<div class="content">
        	<div class="player">
            	<p class="rank_pos" style="font-size: <?=($player['rank_pos'] <= 99 && $player['rank_pos'] > 0 ? ($player['rank_pos'] <= 9 ? 26 : 22) : 18)?>px;">#<?=View::CashFormat($player['rank_pos'])?></p>
                <p class="playername"><?=View::Player($player)?></p>
                <p class="status"><?=$status?></p>
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
                    <br />
                    <?=$langBase->get('profil-08')?>: <span><?=View::CashFormat($player['messages_sent'])?> <?=$langBase->get('profil-10')?></span><br>
					<?=$langBase->get('profil-09')?>: <span><?=View::CashFormat($forum_posts)?> <?=$langBase->get('profil-10')?></span><br><br>
					<? if($player['play_profile_music'] == 1 && $player['profile_music'] != ""){?>					
					<embed src="<?=$config['base_url']?>flash/player.swf" width="205" height="24" allowscriptaccess="always" allowfullscreen="true" wmode="transparent" flashvars="width=200&height=24&controlbar=bottom&file=<?=$player['profile_music']?>" />
					<?}?>
                </p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="bottom"></div>
    </div>

	<div id="userprofiel">
		<div id="userprofiel_naam">

			<img id="userprofiel_naam_usericon" src="images/profil/man.png" />
			<div id="userprofiel_naam_gebruikersnaam"><?=View::Player($player)?></div>
			<div id="userprofiel_naam_crewstatus"><span style="font-size: <?=($player['rank_pos'] <= 99 && $player['rank_pos'] > 0 ? ($player['rank_pos'] <= 9 ? 21 : 20) : 18)?>px;">#<?=View::CashFormat($player['rank_pos'])?></span></div>
		</div>
		<div id="userprofiel_buttons">

		<table border="0" width="100%" style="font-weight:bold;text-align:left;">
			<tr><td><a href="mesaje.php?box=new&to=brok"><img src="images/profil/pm.png" alt="" /><span>Trimite PM</span></a></td></tr>
			<tr><td><a href="respect.php?x=4428"><img src="images/profil/respect.png" alt="" /><span>Respect</span></a></td></tr>
			<tr><td><a href="prieteni.php?add=4428"><img src="images/profil/prieten.png" alt="" /><span>Prieten</span></a></td></tr>
			<tr><td><a href="javascript://"><img src="images/profil/dusman.png" alt="" /><span>Dusman</span></a></td></tr>
		</table>
		</div>
		<div id="userprofiel_properties">
			<table width="100%">

			<tr class="licht">
				<td class="icon"><img src="images/profil/rang.png" /></td>
				<td><?=$config['ranks'][$player['rank']][0]?></td>
			</tr>

			<tr class="licht">
				<td class="icon"><img src="images/profil/cash.png" /></td>
				<td><?=View::MoneyRank($player['cash'] + $player['bank'], true)?></td>

			</tr>
			<tr class="licht">
				<td class="icon"><img src="images/profil/familie.png" /></td>
				<td><a href="<?=$config['base_url']?>?side=familie/familie&amp;id=<?=$family['id']?>"><?=$family['name']?></a></td>
			</tr>

			<tr class="licht">
				<td class="icon"><img src="images/icoons/getrouwd.png" /></td>
				<td><a href="index.php?a=casatorie">Necasatorit</a></td>

			</tr>
			<tr class="licht">
				<td class="icon"><img src="images/icoons/klok.png" /></td>
				<td>0 ore si 0 minute</td>
			</tr>
			<tr class="licht">
				<td class="icon"><img src="images/icoons/accountverwijderen.png" /></td>
				<td>Nu are penalizari</td>

			</tr>
			</table>
		</div>
		
		<div id="userprofiel_avatar">
			<img id="userprofiel_avatar_image" src="images/nopicture.jpg" />
		</div>

		<div id="userprofiel_bescherming">
			<img id="userprofiel_bescherming_icon" src="images/profiel/bescherming.png" />

			<div id="userprofiel_bescherming_titel">Protectie</div>

			<div id="userprofiel_bescherming_content">
				<table>
					<tr class="licht">
						<td class="tablekey">Protectie</td>
						<td class="icon"><img src="images/icoons/bescherming.png" /></td>
						<td>0 ore</td>

					</tr>
					<tr class="licht">

						<td class="tablekey">Siguranta</td>
						<td class="icon"><img src="images/icoons/veilig.png" /></td>
						<td>0 ore</td>
					</tr>
				</table>

			</div>
		</div>

		<div id="userprofiel_geld">
			<img id="userprofiel_geld_icon" src="images/profiel/geld.png" />
			<div id="userprofiel_geld_titel">Bani</div>
			<div id="userprofiel_geld_content">
				<table>
					<tr class="licht">

						<td class="tablekey">Cash</td>
						<td class="icon"><img src="images/icoons/geld.png" /></td>

						<td>100000 &euro;</td>
					</tr>
					<tr class="licht">
						<td class="tablekey">Cont</td>
						<td class="icon"><img src="images/icoons/bank.png" /></td>

						<td>22313 &euro;</td>

					</tr>
				</table>
			</div>
		</div>

		<div id="userprofiel_spelwaarden">
			<img id="userprofiel_spelwaarden_icon" src="images/profiel/speler.png" />

			<div id="userprofiel_spelwaarden_titel">Valori</div>
			<div id="userprofiel_spelwaarden_content">

				<table>
					<tr class="licht">
						<td class="tablekey">Energie</td>

						<td class="icon"><img src="images/icoons/experience.png" /></td>
						<td>100</td>

					</tr>
					<tr class="licht">
						<td class="tablekey">Respect</td>
						<td class="icon"><img src="images/icoons/respectpunten.png" /></td>
						<td>2</td>

					</tr>
					<tr class="licht">

						<td class="tablekey">Credite</td>
						<td class="icon"><img src="images/icoons/premiepunten.png" /></td>
						<td>10</td>
					</tr>
					<tr class="licht">
						<td class="tablekey">Gloante</td>
						<td class="icon"><img src="images/icoons/omgelegdelden.png" /></td>

						<td>0</td>

					</tr>
					</table>
			</div>
		</div>

		<div id="userprofiel_aanval">
			<img id="userprofiel_aanval_icon" src="images/profiel/aanval.png" />

			<div id="userprofiel_aanval_titel">Atacuri</div>
			<div id="userprofiel_aanval_content">

				<table>
					<tr class="licht">
						<td class="tablekey">Castigate</td>
						<td class="icon"><img src="images/icoons/aanvallengewonnen.png" /></td>
						<td>0</td>

					</tr>
					<tr class="licht">
						<td class="tablekey">Pierdute</td>

						<td class="icon"><img src="images/icoons/aanvallenverloren.png" /></td>
						<td>0</td>
					</tr>
					<tr class="licht">

						<td class="tablekey">Criminali Antrenati</td>
						<td class="icon"><img src="images/icoons/killonline.png" /></td>
						<td>0</td>

					</tr>
					<tr class="licht">
						<td class="tablekey">Jucatori Recrutati</td>

						<td class="icon"><img src="images/icoons/killonline.png" /></td>
						<td>0</td>
					</tr>
					</table>
			</div>
		</div></div>

<?php
	if (User::Data('userlevel') >= 2)
	{
		if (isset($_POST['crew_note']))
		{
			$note = $db->EscapeString($_POST['crew_note']);
			$db->Query("UPDATE `[players]` SET `crew_note`='".$note."' WHERE `id`='".$player['id']."'");
			
			View::Message('Ai schimbat notita!', 1, true);
		}
	?>
    <div class="hr big" style="margin: 10px 0 10px 0;"></div>
    <div class="bg_c w400">
    	<h2>Notite</h2>
        <form method="post" action="">
        	<p class="center"><textarea name="crew_note" cols="60" rows="8"><?php echo $player['crew_note'];?></textarea></p>
            <p class="center"><input type="submit" value="Salveaza" /></p>
        </form>
        <?php
		if (User::Data('userlevel') >= 3)
		{
		?>
        <h2>Moderator / Administrator</h2>
        <div class="left" style="width: 190px;">
        	<dl class="dd_right">
            	<dt>ID Jucator</dt>
                <dd><?=$player['id']?></dd>
                <dt>ID Utilizator</dt>
                <dd><?=$user['id']?></dd>
                <dt>Bani</dt>
                <dd>
                	<span class="dark"><?=$langBase->get('ot-money')?>:</span> <?=View::CashFormat($player['cash'])?> $<br />
                    <span class="dark">Banca:</span> <?=View::CashFormat($player['bank'])?> $<br />
                    <span class="dark">Total:</span> <?=(View::CashFormat($player['cash']+$player['bank']))?> $
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
            </dl>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
        <p class="center" style="margin-top: 25px;">
        	<a href="<?=$config['base_url']?>?side=game_panel/player&amp;id=<?=$player['id']?>" class="button">Jucator</a> 
            <a href="<?=$config['base_url']?>?side=game_panel/user&amp;id=<?=$user['id']?>" class="button">Utilizator</a>
        </p>
        <?php
		}
		?>
    </div>
    <?php
	}
	?>
</div>