<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	if (Player::Data('level') < 3 || User::Data('userlevel') < 3)
	{
		View::Message('Du darfst nicht auf diese Seite zugreifen', 2, true, '/game/?side=startside');
	}
	
	$player = $db->EscapeString($_GET['id']);
	$player = $db->QueryFetchArray("SELECT * FROM `[players]` WHERE `id`='".$player."'");

	if (!empty($player['id'])) {
	$user = $db->QueryFetchArray("SELECT id,pass FROM `[users]` WHERE `id`='".$player['userid']."'");
	
	if (isset($_POST['do_login'])){
		$online_user->logout('IN-1');
		if(session_id() == ''){
			session_start();
		}
		
		$db->Query("INSERT INTO `[sessions]` (`Userid`, `Time_start`, `Last_updated`, `IP`, `User_agent`)VALUES('".$user['id']."', '".time()."', '".time()."', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."')");
		$sid = mysql_insert_id();
		$db->Query("UPDATE `[users]` SET `online`='".time()."', `last_active`='".time()."' WHERE `id`='".$user['id']."'");
		$db->Query("UPDATE `[players]` SET `last_active`='".time()."', `online`='".time()."', `status`='1' WHERE `userid`='".$user['id']."' AND `health`>'0' AND `level`>'0'");

		
		$_SESSION['MZ_LoginData'] = array(
			'sid' => $sid,
			'userid' => $user['id'],
			'password' => $user['pass']
		);
		
		header("Location: index.php?sid=".$sid);
		exit;
	}
	elseif (isset($_POST['dea_reason']))
	{
		$reason = $db->EscapeString($_POST['dea_reason']);
		
		if (View::DoubleSalt($db->EscapeString($_POST['dea_pass']), User::Data('id')) !== User::Data('pass'))
		{
			echo View::Message($langBase->get('txt-20'), 2);
		}
		elseif ($player['level'] <= 0)
		{
			echo View::Message($langBase->get('admin-05'), 2);
		}
		elseif (View::Length($reason) <= $config['deactivate_reason_min_length'])
		{
			echo View::Message($langBase->get('admin-06', array('-NUM-' => $config['deactivate_reason_min_length'])), 2);
		}
		else
		{
			$db->Query("UPDATE `[players]` SET `level`='0', `online`='0' WHERE `id`='".$player['id']."'");
			$db->Query("INSERT INTO `deactivations` (`type`, `victim`, `by_player`, `reason`, `time`)VALUES('player', '".$player['id']."', '".Player::Data('id')."', '".$reason."', '".time()."')");
			
			View::Message($langBase->get('admin-07'), 1, true);
		}
	}
	elseif (isset($_POST['dea_remove']))
	{
		if ($player['level'] > 0)
		{
			echo View::Message('ERROR!', 2);
		}
		else
		{
			$db->Query("UPDATE `[players]` SET `level`='1' WHERE `id`='".$player['id']."'");
			$db->Query("DELETE FROM `deactivations` WHERE `type`='player' AND `victim`='".$player['id']."'");
			
			View::Message($langBase->get('admin-08'), 1, true);
		}
	}
	elseif (isset($_POST['edit_name']))
	{
		$name = $db->EscapeString($_POST['edit_name']);
		$image = $db->EscapeString($_POST['edit_image']);
		$profiletext = $db->EscapeString($_POST['edit_profiletext']);
		$cash = View::NumbersOnly($db->EscapeString($_POST['edit_cash']));
		$bank = View::NumbersOnly($db->EscapeString($_POST['edit_bank']));
		$points = View::NumbersOnly($db->EscapeString($_POST['edit_points']));
		$bullets = View::NumbersOnly($db->EscapeString($_POST['edit_bullets']));
		$placeID = $db->EscapeString($_POST['edit_place']);
		$rankID = $db->EscapeString($_POST['edit_rank']);
		$rankp = View::NumbersOnly($db->EscapeString($_POST['edit_rankp']), 4);
		$health = View::NumbersOnly($db->EscapeString($_POST['edit_health']), 2);
		$wanted = View::NumbersOnly($db->EscapeString($_POST['edit_wanted']), 2);
		$respect = View::NumbersOnly($db->EscapeString($_POST['edit_respect']));
		
		$rank = $config['ranks'][$rankID];
		$place = $config['places'][$placeID];
		$name = Accessories::ValidatePlayername($name);
		
		
		if (View::DoubleSalt($db->EscapeString($_POST['my_pass']), User::Data('id')) !== User::Data('pass'))
		{
			echo View::Message($langBase->get('txt-20'), 2);
		}
		elseif ($name == $player['name'] && $image == $player['profileimage'] && $profiletext == $player['profiletext'] && $cash == $player['cash'] && $bank == $player['bank'] && $points == $player['points'] && $bullets == $player['bullets'] && $respect == $player['respect'] && $placeID == $player['live'] && $rankID == $player['rank'] && $rankp == View::AsPercent($player['rankpoints']-$config['ranks'][$player['rank']][1], $config['ranks'][$player['rank']][2]-$config['ranks'][$player['rank']][1], 4) && $health == View::AsPercent($player['health'], $config['max_health'], 2) && $wanted == View::AsPercent($player['wanted-level'], $config['max_wanted-level'], 2))
		{
			echo View::Message($langBase->get('admin-09'), 1, true);
		}
		elseif ($name === false)
		{
			echo View::Message($langBase->get('home-05'), 2);
		}
		elseif (!preg_match('%\A(?:^((http[s]?|ftp):/)?/?([^:/\s]+)(:([^/]*))?((/\w+)*/)([\w\-.]+[^#?\s]+)(\?([^#]*))?(#(.*))?$)\Z%', $image) && $image != $config['default_profileimage'])
		{
			echo View::Message($langBase->get('admin-10'), 2);
		}
		elseif (View::Length($profiletext) < $config['profiletext_min_length'])
		{
			echo View::Message($langBase->get('edp-01', array('-NUM-' => $config['profiletext_min_length'])), 2);
		}
		elseif (View::Length($profiletext) > $config['profiletext_max_length'])
		{
			echo View::Message($langBase->get('edp-02', array('-NUM-' => $config['profiletext_max_length'])), 2);
		}
		elseif ($cash < 0)
		{
			echo View::Message($langBase->get('admin-11'), 2);
		}
		elseif ($bank < 0)
		{
			echo View::Message($langBase->get('admin-11'), 2);
		}
		elseif ($points < 0)
		{
			echo View::Message($langBase->get('admin-11'), 2);
		}
		elseif ($bullets < 0)
		{
			echo View::Message($langBase->get('admin-11'), 2);
		}
		elseif ($respect < 0)
		{
			echo View::Message($langBase->get('admin-11'), 2);
		}
		elseif (!$rank)
		{
			echo View::Message($langBase->get('admin-12'), 2);
		}
		elseif (!$place)
		{
			echo View::Message($langBase->get('admin-13'), 2);
		}
		elseif ($rankp < 0)
		{
			echo View::Message($langBase->get('admin-11'), 2);
		}
		elseif ($health > 100 || $health < 0)
		{
			echo View::Message($langBase->get('admin-14'), 2);
		}
		elseif ($wanted > 100 || $wanted < 0)
		{
			echo View::Message($langBase->get('admin-15'), 2);
		}
		else
		{
			$rankpoints = $rank[1] + (($rank[2]-$rank[1])/100 * $rankp);
			$healthpoints = ($config['max_health']/100) * $health;
			$wantedpoints = ($config['max_wanted-level']/100) * $wanted;
			
			$db->Query("
			UPDATE `[players]` SET
			`name`='".$name."',
			`profileimage`='".$image."',
			`profiletext`='".$profiletext."',
			`cash`='".$cash."',
			`bank`='".$bank."',
			`points`='".$points."',
			`bullets`='".$bullets."',
			`live`='".$placeID."',
			`rank`='".$rankID."',
			`rankpoints`='".$rankpoints."',
			`health`='".$healthpoints."',
			`wanted-level`='".$wantedpoints."',
			`respect`='".$respect."'
			WHERE `id`='".$player['id']."'
			");
			
			$oldValues = array(
				'name' => $player['name'],
				'profileimage' => $player['profileimage'],
				'profiletext' => $player['profiletext'],
				'cash' => $player['cash'],
				'bank' => $player['bank'],
				'points' => $player['points'],
				'bullets' => $player['bullets'],
				'live' => $player['live'],
				'rank' => $player['rank'],
				'rankpoints' => $player['rankpoints'],
				'health' => $player['health'],
				'wanted-level' => $player['wanted-level'],
				'respect' => $player['respect']
			);
			$newValues = array(
				'name' => $name,
				'profileimage' => $image,
				'profiletext' => $profiletext,
				'cash' => $cash,
				'bank' => $bank,
				'points' => $points,
				'bullets' => $bullets,
				'live' => $placeID,
				'rank' => $rankID,
				'rankpoints' => $rankpoints,
				'health' => $healthpoints,
				'wanted-level' => $wantedpoints,
				'respect' => $respect
			);
			$changedValues = array();
			foreach ($oldValues as $key => $value)
			{
				if ($newValues[$key] != $value)
				{
					$changedValues[$key] = array(
						'old' => $value,
						'new' => $newValues[$key]
					);
				}
			}
			
			Accessories::AddToLog(Player::Data('id'), array('edit_type' => 'player', 'edited' => $player['id'], 'changed' => $changedValues));
			
			View::Message($langBase->get('comp-61'), 1, true);
		}
	}
	
	$antibots_success = array();
	$antibots_fail = array();
	$sql = $db->Query("SELECT result FROM `antibot_sessions` WHERE `playerid`='".$player['id']."' AND `active`='0'");
	while ($antibot = $db->FetchArray($sql))
	{
		if ($antibot['result'] == 1)
		{
			$antibots_success[] = $antibot;
		}
		else
		{
			$antibots_fail[] = $antibot;
		}
	}
	
	$jailstats = unserialize($player['jail_stats']);
	
	$hospital_data = unserialize($player['hospital_data']);
	$hospital_timeleft = $hospital_data['added'] + $hospital_data['time_length'] - time();
	
	if (!empty($player['bank_id']))
	{
		$sql = $db->Query("SELECT id,b_id,interests_num,interests_cash,transfers FROM `bank_clients` WHERE `playerid`='".$player['id']."' AND `active`='1' AND `accepted`='1'");
		$bank_acc = $db->FetchArray($sql);
		
		$bank_transfers = unserialize($bank_acc['transfers']);
	}
?>
<script type="text/javascript">
	<!--
	window.addEvent('domready', function()
	{
		var wrap_ab = $('antibotstats_change');
		var links_ab = wrap_ab.getChildren('a');
		var current_ab = 0;
		
		links_ab.each(function(elem)
		{
			elem.addEvent('click', function()
			{
				if (elem.hasClass('prev'))
					current_ab += 1;
				else
					current_ab -= 1;
				
				if (current_ab < 0)
					current_ab = 0;
				else
					$('graph_antibot_stats').reload('/game/graphs/antibot_stats.php?player=<?=$player['id']?>&time_add=' + current_ab);
				
				return false;
			});
		});
		
		var wrap_e = $('economystats_change');
		var links_e = wrap_e.getChildren('a');
		var current_e = 0;
		
		links_e.each(function(elem)
		{
			elem.addEvent('click', function()
			{
				if (elem.hasClass('prev'))
					current_e += 1;
				else
					current_e -= 1;
				
				if (current_e < 0)
					current_e = 0;
				else
					$('graph_economy_stats').reload('/game/graphs/player_economy.php?player=<?=$player['id']?>&time_add=' + current_e);
				
				return false;
			});
		});
		
		var wrap_da = $('da_change');
		var links_da = wrap_da.getChildren('a');
		var current_da = 0;
		
		links_da.each(function(elem)
		{
			elem.addEvent('click', function()
			{
				if (elem.hasClass('prev'))
					current_da += 1;
				else
					current_da -= 1;
				
				if (current_da < 0)
					current_da = 0;
				else
					$('graph_da_stats').reload('/game/graphs/daily_activity.php?player=<?=$player['id']?>&time_add=' + current_da);
				
				return false;
			});
		});
	});
	-->
</script>
<div class="left" style="width: 300px; margin-left: 10px;">
	<div class="bg_c" style="width: 280px;">
        <h1 class="big"><?=$langBase->get('txt-22')?></h1>
        <dl class="dd_right">
            <dt><?=$langBase->get('min-04')?></dt>
            <dd><?=View::Player($player)?></dd>
            <dt><?=$langBase->get('min-05')?></dt>
            <dd>#<?=View::CashFormat($player['id'])?></dd>
            <dt><?=$langBase->get('admin-03')?></dt>
            <dd><a href="<?=$config['base_url']?>?side=game_panel/user&amp;id=<?=$user['id']?>">#<?=View::CashFormat($user['id'])?></a></dd>
            <dt><?=$langBase->get('min-06')?></dt>
            <dd><?=View::Time($player['created'], true)?></dd>
            <dt><?=$langBase->get('txt-05')?></dt>
            <dd><?=$config['places'][$player['live']][0]?></dd>
            <dt><?=$langBase->get('subMenu-03')?></dt>
            <dd><a href="<?=$player['profileimage']?>"><?=$player['profileimage']?></a></dd>
        </dl>
        <div class="clear"></div>
        <div class="hr big" style="margin: 15px 0 10px 0;"></div>
        <dl class="dd_right">
            <dt><?=$langBase->get('ot-rank')?></dt>
            <dd><b><?=$config['ranks'][$player['rank']][0]?></b><br /><?=(View::AsPercent($player['rankpoints']-$config['ranks'][$player['rank']][1], $config['ranks'][$player['rank']][2]-$config['ranks'][$player['rank']][1], 4))?> %<br /><?=View::CashFormat($player['rankpoints'])?></dd>
            <dt><?=$langBase->get('ot-health')?></dt>
            <dd><?=(View::AsPercent($player['health'], $config['max_health'], 2))?> %</dd>
            <dt><?=$langBase->get('ot-wanted_level')?></dt>
            <dd><?=(View::AsPercent($player['wanted-level'], $config['max_wanted-level'], 2))?> %</dd>
            <dt><?=$langBase->get('admin-16')?></dt>
            <dd><span class="dark"><?=$langBase->get('min-18')?>:</span> <?=View::CashFormat($player['kills'])?><br /><span class="dark"><?=$langBase->get('min-19')?>:</span> <?=View::CashFormat($player['kills_failed'])?></dd>
        </dl>
        <div class="clear"></div>
        <?php if($hospital_timeleft > 0) echo '<p>JDer Spieler ist für <span class="countdown">' . $hospital_timeleft . '</span> Sekunden im Krankenhaus.</p>';?>
        <div class="hr big" style="margin: 15px 0 10px 0;"></div>
        <?php
		$latencies = array();
		$stats = array();
		$sql = $db->Query("SELECT side,extra FROM `[log]` WHERE `playerid`='".$player['id']."'");
		while ($log = $db->FetchArray($sql))
		{
			$extra = unserialize($log['extra']);
			
			if ($log['side'] == 'brekk')

			{
				$stats['brekk_' . $extra['result']]++;
				$stats['brekk_total']++;
				$latencies['brekk'] += $extra['latency'];
			}
			elseif ($log['side'] == 'biltyveri')
			{
				$stats['biltyveri_' . $extra['result']]++;
				$stats['biltyveri_total']++;
				$latencies['biltyveri'] += $extra['latency'];
			}
			elseif ($log['side'] == 'utpressing')
			{
				if ($extra['result'] == 'fail' || $extra['result'] == 'no_find')
				{
					$stats['utpressing_fail']++;
					$stats['utpressing_total']++;
					$latencies['utpressing'] += $extra['latency'];
				}
				elseif ($extra['result'] == 'success')
				{
					$stats['utpressing_success']++;
					$stats['utpressing_total']++;
					$stats['utpressing_money'] += $extra['money'];
					$latencies['utpressing'] += $extra['latency'];
				}
				elseif ($extra['blackmail_by'])
				{
					$stats['utpressing_received']++;
					$stats['utpressing_received_money'] += $extra['money'];
				}
			}
		}
		
		$latencies['brekk'] = round(abs($latencies['brekk']) / $stats['brekk_total'], 0);
		$latencies['biltyveri'] = round(abs($latencies['biltyveri']) / $stats['biltyveri_total'], 0);
		$latencies['utpressing'] = round(abs($latencies['utpressing']) / $stats['utpressing_total'], 0);
		?>
        <dl class="dd_right">
        	<dt>Ausbrüche:</dt>
            <dd><span class="dark"><?=$langBase->get('min-18')?>:</span> <b><?=View::CashFormat($stats['brekk_success'])?></b><br /><span class="dark"><?=$langBase->get('min-19')?>:</span> <b><?=View::CashFormat($stats['brekk_fail'])?></b></dd>
            <dt>Autos geklaut</dt>
            <dd><span class="dark"><?=$langBase->get('min-18')?>:</span> <b><?=View::CashFormat($stats['biltyveri_success'])?></b><br /><span class="dark"><?=$langBase->get('min-19')?>:</span> <b><?=View::CashFormat($stats['biltyveri_fail'])?></b></dd>
            <dt>Erpressungen</dt>
            <dd><span class="dark"><?=$langBase->get('min-18')?>:</span> <b><?=View::CashFormat($stats['utpressing_success'])?></b><br /><span class="dark"><?=$langBase->get('min-19')?>:</span> <b><?=View::CashFormat($stats['utpressing_fail'])?></b><br /><b><?=View::CashFormat($stats['utpressing_money'])?> $</b></dd>
            <dt>Erpresst worden</dt>
            <dd><b><?=View::CashFormat($stats['utpressing_received'])?></b><br /><b><?=View::CashFormat($stats['utpressing_received_money'])?> $</b></dd>
            <dd style="padding-top: 10px;"></dd>
            <dt>Raubüberfsll</dt>
            <dd><?=View::strTime($latencies['brekk'], 0, ' ', 1, '0')?></dd>
            <dt>Autodiebstahl</dt>
            <dd><?=View::strTime($latencies['biltyveri'], 0, ' ', 1, '0')?></dd>
            <dt>Erpressung</dt>
            <dd><?=View::strTime($latencies['utpressing'], 0, ' ', 1, '0')?></dd>
        </dl>
        <div class="clear"></div>
        <div class="hr big" style="margin: 15px 0 15px 0;"></div>
        <p class="center" style="margin-bottom: 5px;">
            <a href="#" class="button" onclick="$('player_edit').toggleClass('hidden'); return false;">Spieler bearbeiten</a> 
        </p>
    </div>
</div>
<div class="left" style="width: 300px; margin-left: 15px;">
	<div class="bg_c" style="width: 280px;">
    	<h1 class="big">Antibot</h1>
        <div class="clear"></div>
        <div class="graph_container" style="width: 270px;">
        	<p class="center" id="antibotstats_change" style="margin-bottom: 5px;">
            	<a href="#" class="prev">&laquo; <?=$langBase->get('ot-back')?></a>
                <a href="#" class="next" style="margin-left: 10px;"><?=$langBase->get('ot-next')?> &raquo;</a>
            </p>
        	<div id="graph_antibot_stats"></div>
    	</div>
    </div>
    <div class="bg_c" style="width: 280px;">
    	<h1 class="big">Spieler sperren</h1>
        <form method="post" action="">
        <?php
		if ($player['level'] <= 0)
		{
			$sql = $db->Query("SELECT reason FROM `deactivations` WHERE `type`='player' AND `victim`='".$player['id']."'");
			$dea = $db->FetchArray($sql);
		?>
            <p>Dieser Player wurde bereits gesperrt!</p>
            <p>Grund:</p>
            <div class="c_1 t_justify" style="margin: 10px; padding: 5px; overflow: hidden;"><?=nl2br($dea['reason'])?></div>
            <p class="center">
                <input type="submit" name="dea_remove" value="Aktivieren" />
            </p>
        <?php
		}
		else
		{
		?>
        	<dl class="dd_right">
            	<dt>Grund</dt>
                <dd><textarea name="dea_reason" rows="5" cols="30" style="width: 200px;"><?=(isset($_POST['dea_reason']) ? $_POST['dea_reason'] : $user['dea_reason'])?></textarea></dd>
                <dt style="padding-top: 10px;">Passwort</dt>
                <dd style="padding-top: 10px;"><input type="password" name="dea_pass" class="flat" value="" /></dd>
            </dl>
            <p class="clear center">
            	<input type="submit" value="Sperren" />
            </p>
        <?php
		}
		?>
        </form>
    </div>
	<div class="bg_c" style="width: 280px;">
        <form method="post">
			<center><input type="submit" name="do_login" value="Als diesen Spieler anmelden" /></center>
		</form>
    </div>
</div>
<div class="clear"></div>
<div class="bg_c w600<?php if(!isset($_POST['edit_name'])) echo ' hidden';?>" id="player_edit">
	<h1 class="big">Spieler bearbeiten</h1>
    <form method="post" action="">
    	<div class="c_1 left" style="width: 275px; padding: 10px;">
            <dl class="dd_right" style="margin: 0;">
                <dt>Name</dt>
                <dd><input type="text" name="edit_name" class="flat" value="<?=(isset($_POST['edit_name']) ? $_POST['edit_name'] : $player['name'])?>" /></dd>
                <dt>Profilbild</dt>
                <dd><input type="text" name="edit_image" class="flat" value="<?=(isset($_POST['edit_image']) ? $_POST['edit_image'] : $player['profileimage'])?>" /></dd>

                <dt>Geld</dt>
                <dd><input type="text" name="edit_cash" class="flat" value="<?=(isset($_POST['edit_cash']) ? View::CashFormat(View::NumbersOnly($_POST['edit_cash'])) : View::CashFormat($player['cash']))?> $" /></dd>
                <dt>Bank</dt>
                <dd><input type="text" name="edit_bank" class="flat" value="<?=(isset($_POST['edit_bank']) ? View::CashFormat(View::NumbersOnly($_POST['edit_bank'])) : View::CashFormat($player['bank']))?> $" /></dd>
                <dt>Coins</dt>
                <dd><input type="text" name="edit_points" class="flat" value="<?=(isset($_POST['edit_points']) ? View::CashFormat(View::NumbersOnly($_POST['edit_points'])) : View::CashFormat($player['points']))?> p" /></dd>
                <dt>Munition</dt>
                <dd><input type="text" name="edit_bullets" class="flat" value="<?=(isset($_POST['edit_bullets']) ? View::CashFormat(View::NumbersOnly($_POST['edit_bullets'])) : View::CashFormat($player['bullets']))?>" /></dd>
            </dl>
            <div class="clear"></div>
        </div>
        <div class="c_1 left" style="width: 275px; margin-left: 10px; padding: 10px;">
            <dl class="dd_right" style="margin: 0;">
            	<dt>Ort</dt>
                <dd>
                	<select name="edit_place">
                <?php
				foreach ($config['places'] as $key => $place)
				{
					echo '<option value="' . $key . '"' . (isset($_POST['edit_place']) && $_POST['edit_place'] == $key ? ' selected="selected"' : ($player['live'] == $key ? ' selected="selected"' : '')) . '>' . $place[0] . '</option>';
				}
				?>
                	</select>
                </dd>
                <dt>Rang</dt>
                <dd>
                <select name="edit_rank">
                <?php
				foreach ($config['ranks'] as $key => $rank)
				{
					echo '<option value="' . $key . '"' . (isset($_POST['edit_rank']) && $_POST['edit_rank'] == $key ? ' selected="selected"' : ($player['rank'] == $key ? ' selected="selected"' : '')) . '>' . $rank[0] . '</option>';
				}
				?>
                	</select>
                </dd>
                <dt>Rangfortschritt</dt>
                <dd><input type="text" name="edit_rankp" class="flat" value="<?=(isset($_POST['edit_rankp']) ? View::NumbersOnly($_POST['edit_rankp'], 4) : View::AsPercent($player['rankpoints']-$config['ranks'][$player['rank']][1], $config['ranks'][$player['rank']][2]-$config['ranks'][$player['rank']][1], 4))?> %" /></dd>
                <dt>Gesundheit</dt>
                <dd><input type="text" name="edit_health" class="flat" value="<?=(isset($_POST['edit_health']) ? View::NumbersOnly($_POST['edit_health'], 2) : View::AsPercent($player['health'], $config['max_health'], 2))?> %" /></dd>
                <dt>Gefahrenstatus</dt>
                <dd><input type="text" name="edit_wanted" class="flat" value="<?=(isset($_POST['edit_wanted']) ? View::NumbersOnly($_POST['edit_wanted'], 2) : View::AsPercent($player['wanted-level'], $config['max_wanted-level'], 2))?> %" /></dd>
				<dt>Respektpunkte</dt>
                <dd><input type="text" name="edit_respect" class="flat" value="<?=(isset($_POST['edit_respect']) ? View::CashFormat(View::NumbersOnly($_POST['edit_respect'])) : View::CashFormat($player['respect']))?>" /></dd>
            </dl>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
        <dl class="dd_right">
        	<dt>Profil Beschreibung</dt>
            <dd><textarea name="edit_profiletext" cols="80" rows="12" style="width: 500px;"><?=(isset($_POST['edit_profiletext']) ? $_POST['edit_profiletext'] : $player['profiletext'])?></textarea></dd>
        </dl>
        <p class="clear">Dein Passwort: <input type="password" name="my_pass" value="" class="flat" style="margin-left: 25px;" /></p>
        <p class="center">
        	<input type="submit" value="Speichern" />
        </p>
    </form>
</div>
<div class="bg_c w600">
	<h1 class="big">Statistiken</h1>
    <div class="c_1 left" style="width: 275px; padding: 10px;">
    	<dl class="dd_right" style="margin: 0;">
            <dt>Geld</dt>
            <dd><b class="dark">In bar: </b><?=View::CashFormat($player['cash'])?> $<br /><b class="dark">Bank: </b><?=View::CashFormat($player['bank'])?> $<br /><b><span class="dark">Gesamt:</span> <?=(View::CashFormat($player['cash']+$player['bank']))?> $</b></dd>
            <dt>Coins</dt>
            <dd><?=View::CashFormat($player['points'])?> C</dd>
        </dl>
        <div class="clear"></div>
    </div>
    <div class="c_1 left" style="width: 275px; margin-left: 10px; padding: 10px;">
        <dl class="dd_right">
            <dt>Bank ID</dt>
            <dd><?=($bank_acc['id'] == '' ? 'N/A' : '#' . $bank_acc['id'])?></dd>
            <dt>Gebühren</dt>
            <dd><b><?=View::CashFormat($bank_acc['interests_num'])?></b><br /><b><?=View::CashFormat($bank_acc['interests_cash'])?> $</b></dd>
            <dt>Überweisungen</dt>
            <dd><span class="dark">Geld:</span> <b><?=View::CashFormat($bank_transfers['money'])?></b><br /><span class="dark">Coins:</span> <b><?=View::CashFormat($bank_transfers['points'])?></b></dd>
        </dl>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
    <div class="graph_container" style="width: 580px;">
        <p class="center" id="economystats_change" style="margin-bottom: 5px;">
            <a href="#" class="prev">&laquo; <?=$langBase->get('ot-back')?></a>
            <a href="#" class="next" style="margin-left: 10px;"><?=$langBase->get('ot-next')?> &raquo;</a>
        </p>
        <div id="graph_economy_stats"></div>
    </div>
</div>
<div class="graph_container">
    <p class="center" id="da_change" style="margin-bottom: 5px;">
        <a href="#" class="prev">&laquo; <?=$langBase->get('ot-back')?></a>
        <a href="#" class="next" style="margin-left: 10px;"><?=$langBase->get('ot-next')?> &raquo;</a>
    </p>
    <div id="graph_da_stats"></div>
</div>
<?}else{?>
	<p class="center">
		<a href="<?=$config['base_url']?>?side=game_panel/" class="button">&laquo; <?=$langBase->get('ot-back')?></a>
	</p>
    <div class="bg_c w250 c_1">
    	<h1 class="big"><?=$langBase->get('txt-09')?> <?=$langBase->get('txt-06')?></h1>
        <form method="get" action="">
        	<input type="hidden" name="side" value="<?=$_GET['side']?>" />
            <dl class="dd_right">
            	<dt><?=$langBase->get('min-05')?></dt>
                <dd><input type="text" class="flat" name="id" value="" /></dd>
            </dl>
            <p class="center clear">
            	<input type="submit" value="<?=$langBase->get('txt-09')?>" />
            </p>
        </form>
	</div>
<?}?>