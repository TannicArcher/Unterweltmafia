<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	if (Player::Data('level') < 3 || User::Data('userlevel') < 3)
	{
		View::Message('Du darfst nicht auf diese Seite zugreifen!', 2, true, '/game/?side=startside');
	}
	
	$user = $db->EscapeString($_GET['id']);
	$sql = $db->Query("SELECT * FROM `[users]` WHERE `id`='".$user."'");
	$user = $db->FetchArray($sql);
	
	if ($user['id'] != '')
	{
	$players = $db->QueryFetchArrayAll("SELECT id,level,name FROM `[players]` WHERE `userid`='".$user['id']."' ORDER BY id DESC");

	if (isset($_POST['edit_email']))
	{
		$email = trim($db->EscapeString($_POST['edit_email']));
		$pass = $db->EscapeString($_POST['edit_pass']);
		
		if (View::DoubleSalt($db->EscapeString($_POST['my_pass']), User::Data('id')) !== User::Data('pass'))
		{
			echo View::Message('Falsches Passwort!', 2);
		}
		elseif ($email == $user['email'] && View::DoubleSalt($pass, $user['id']) == $user['pass'])
		{
			View::Message('Keine Änderungen gefunden!', 1, true);
		}
		elseif (!preg_match("/^[a-zA-Z_\\-][\\w\\.\\-_]*[a-zA-Z0-9_\\-]@[a-zA-Z0-9][\\w\\.-]*[a-zA-Z0-9]\\.[a-zA-Z][a-zA-Z\\.]*[a-zA-Z]$/i", $email))
		{
			echo View::Message('Falsche Email!', 2);
		}
		elseif ($db->QueryGetNumRows("SELECT id FROM `[users]` WHERE `email`='".$email."' AND `id`!='".User::Data('id')."'") > 0)
		{
			echo View::Message('Diese Email wird bereits verwendet!', 2);
		}
		elseif (View::ValidPassword($pass) != true && trim($pass) != '')
		{
			echo View::Message('Falsches Passwort!', 2);
		}
		else
		{
			$db->Query("UPDATE `[users]` SET `email`='".$email."'".(trim($pass) != '' ? ", `pass`='".View::DoubleSalt($pass, $user['id'])."'" : '')." WHERE `id`='".$user['id']."'");
			
			$oldValues = array(
				'email' => $user['email'],
				'pass' => $user['pass']
			);
			$newValues = array(
				'email' => $email,
				'pass' => View::DoubleSalt($pass, $user['id'])
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
			
			Accessories::AddToLog(Player::Data('id'), array('edit_type' => 'user', 'edited' => $user['id'], 'changed' => $changedValues));
			
			View::Message('Änderungen erfolgreich gespeichert!', 1, true);
		}
	}
	elseif (isset($_POST['sess_logout']))
	{
		$sessions = $db->EscapeString($_POST['sess_logout']);
		
		$logged_out = 0;
		foreach ($sessions as $session)
		{
			$sql = $db->Query("SELECT id FROM `[sessions]` WHERE `id`='".$session."'");
			$session = $db->FetchArray($sql);
			
			if ($session['id'] != '')
			{
				$db->Query("UPDATE `[sessions]` SET `Active`='0' WHERE `id`='".$session['id']."'");
				$logged_out++;
			}
			
			$db->Query("OPTIMIZE TABLE `[sessions]`");
		}
		
		View::Message(($logged_out == 0 ? 'Ingen' : $logged_out) . ' &oslash;kt' . ($logged_out != 1 ? 'er' : '') . ' wurde abgemeldet.', 1, true);
	}
	elseif (isset($_POST['dea_reason']))
	{
		$reason = $db->EscapeString($_POST['dea_reason']);
		
		if (View::DoubleSalt($db->EscapeString($_POST['dea_pass']), User::Data('id')) !== User::Data('pass'))
		{
			echo View::Message('Wrong password!', 2);
		}
		elseif ($user['userlevel'] <= 0)
		{
			echo View::Message('Dieser Benutzer war bereits gesperrt!', 2);
		}
		elseif (View::Length($reason) <= $config['deactivate_reason_min_length'])
		{
			echo View::Message('Der Grund muss mindestens '.$config['deactivate_reason_min_length'].' Zeichen enthalten.', 2);
		}
		else
		{
			$db->Query("UPDATE `[users]` SET `userlevel`='0', `online`='0' WHERE `id`='".$user['id']."'");
			$db->Query("UPDATE `[players]` SET `online`='0' WHERE `userid`='".$user['id']."'");
			$db->Query("INSERT INTO `deactivations` (`type`, `victim`, `by_player`, `reason`, `time`)VALUES('user', '".$user['id']."', '".Player::Data('id')."', '".$reason."', '".time()."')");
			
			View::Message('Benutzer erfolgreich gesperrt!', 1, true);
		}
	}
	elseif (isset($_POST['dea_remove']))
	{
		if ($user['userlevel'] > 0)
		{
			echo View::Message('Benutzer ist nicht gesperrt!', 2);
		}
		else
		{
			$db->Query("UPDATE `[users]` SET `userlevel`='1' WHERE `id`='".$user['id']."'");
			$db->Query("DELETE FROM `deactivations` WHERE `type`='user' AND `victim`='".$user['id']."'");
			
			View::Message('Benutzer erfolgreich aktiviert!', 1, true);
		}
	}
?>
<div class="left" style="width: 300px; margin-left: 10px;">
	<div class="bg_c" style="width: 280px;">
    	<h1 class="big">Benutzer Info</h1>
        <dl class="dd_right">
        	<dt>User ID</dt>
            <dd>#<?=$user['id']?></dd>
            <dt>Eingeladen von</dt>
            <dd><?=(empty($user['enlisted_by']) ? 'N/A' : '<a href="' . $config['base_url'] . '?side=game_panel/user&amp;id=' . $user['enlisted_by'] . '">#' . $user['enlisted_by'] . '</a>')?></dd>
        	<dt>Spieler-Account</dt>
            <dd><?=($user['hasPlayer'] == 0 ? 'N/A' : View::Player($players[0]))?></dd>
            <dt>Email</dt>
            <dd><a href="mailto:<?=$user['email']?>"><?=$user['email']?></a></dd>
            <dt>Registrierungsdatum</dt>
            <dd><?=View::Time($user['reg_time'], true)?></dd>
            <dt>Letzte Aktivität</dt>
            <dd><?=View::Time($user['last_active'], true)?></dd>
            <dt>Registrierte IP</dt>
            <dd><a href="<?=$config['base_url']?>?side=game_panel/ip_lookup&amp;ip=<?=$user['IP_regged_with']?>"><?=$user['IP_regged_with']?></a></dd>
            <dt>Letzte verwendete IP</dt>
            <dd><a href="<?=$config['base_url']?>?side=game_panel/ip_lookup&amp;ip=<?=$user['IP_last']?>"><?=$user['IP_last']?></a></dd>
            <dt>Statusebene</dt>
            <dd><?=$user['userlevel']?></dd>
        </dl>
        <p class="clear center">
        	<a href="#" onclick="$('user_edit').toggleClass('hidden'); return false;" class="button">Bearbeiten</a> <a href="#" onclick="$('user_dea').toggleClass('hidden'); return false;" class="button">Sperren</a>
        </p>
        <div class="hr big" style="margin: 15px 0 10px 0;"></div>
        <dl class="dd_right">
        	<dt>Letzte Miniapp</dt>
            <dd><?=$user['miniapps_last']?></dd>
            <dt>Kleiner Header</dt>
            <dd><?=$user['small_header']?></dd>
            <dt>Forum Signaturen</dt>
            <dd><?=$user['forum_view_signatures']?></dd>
            <dt>Forum Antworten pro Seite</dt>
            <dd><?=$user['forum_replies_per_page']?></dd>
            <dt>Benutzt Schnellmenü</dt>
            <dd><?=$user['use_smartMenu']?></dd>
        </dl>
        <div class="clear"></div>
    </div>
    <div class="bg_c<?php if(!isset($_POST['edit_email'])) echo ' hidden';?>" style="width: 280px;" id="user_edit">
    	<h1 class="big">Benutzer bearbeiten</h1>
        <form method="post" action="">
        	<dl class="dd_right">
            	<dt>Email</dt>
                <dd><input type="text" name="edit_email" class="flat" value="<?=(isset($_POST['edit_email']) ? $_POST['edit_email'] : $user['email'])?>" /></dd>
                <dt>Passwort</dt>
                <dd><input type="password" name="edit_pass" class="flat" value="" /></dd>
                <dt style="padding-top: 5px;">Dein Password</dt>
                <dd style="padding-top: 5px;"><input type="password" name="my_pass" class="flat" value="" /></dd>
            </dl>
            <p class="clear center">
            	<input type="submit" value="Speichern" />
            </p>
        </form>
    </div>
    <div class="bg_c<?php if(!isset($_POST['dea_reason'])) echo ' hidden';?>" style="width: 280px;" id="user_dea">
    	<h1 class="big">Benutzer sperren</h1>
        <form method="post" action="">
        <?php
		if ($user['userlevel'] <= 0)
		{
			$sql = $db->Query("SELECT reason FROM `deactivations` WHERE `type`='user' AND `victim`='".$user['id']."'");
			$dea = $db->FetchArray($sql);
		?>
            <p>Dieser Benutzer ist bereits gesperrt!</p>
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
                <dt style="padding-top: 10px;">Deine Passwort</dt>
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
</div>
<div class="left" style="width: 300px; margin-left: 15px;">
	<div class="bg_c" style="width: 280px;">
    	<h1 class="big">Spieler Accounts</h1>
        <?php
		$sql = $db->Query("SELECT id,health,level,last_active FROM `[players]` WHERE `userid`='".$user['id']."' ORDER BY id DESC");
		$players = $db->FetchArrayAll($sql);
		
		if (count($players) <= 0)
		{
			echo '<p>Nichts gefunden</p>';
		}
		else
		{
		?>
        <table class="table">
        	<thead>
            	<tr class="small">
                	<td>Name</td>
                    <td>Status</td>
                    <td>Letzte Aktivität</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
            <?php
			foreach ($players as $player)
			{
				$i++;
				$c = $i%2 ? 1 : 2;
			?>
            	<tr class="c_<?=$c?>">
                	<td><?=View::Player($player)?><br /><span class="subtext">#<?=$player['id']?></span></td>   
                    <td class="center"><?=($player['level'] <= 0 ? 'Gesperrt' : ($player['health'] <= 0 ? 'Tot' : 'Aktiv'))?></td>
                    <td class="t_right"><?=View::Time($player['last_active'])?></td>
                    <td class="t_right"><a href="<?=$config['base_url']?>?side=game_panel/player&amp;id=<?=$player['id']?>">&laquo; Bearbeiten</a></td>
                </tr>
            <?php
			}
			?>
            	<tr class="c_3">
                	<td colspan="4"></td>
                </tr>
            </tbody>
        </table>
        <?php
		}
		?>
    </div>
    <div class="bg_c" style="width: 280px;">
    	<h1 class="big">Geworbene Benutzer</h1>
        <?php
		$sql = $db->Query("SELECT id,last_active FROM `[users]` WHERE `enlisted_by`='".$user['id']."' ORDER BY id DESC");
		$users = $db->FetchArrayAll($sql);
		
		if (count($users) <= 0)
		{
			echo '<p>Nichts gefunden!</p>';
		}
		else
		{
		?>
        <table class="table">
        	<thead>
            	<tr class="small">
                	<td>Bebutzer ID</td>
                    <td>Letzte Aktivität</td>
                </tr>
            </thead>
            <tbody>
            <?php
			$i = 0;
			
			foreach ($users as $theUser)
			{
				$i++;
				$c = $i%2 ? 1 : 2;
			?>
            	<tr class="c_<?=$c?>">
                	<td><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$theUser['id']?>">#<?=$theUser['id']?></a></td>   
                    <td class="t_right"><?=View::Time($theUser['last_active'], true)?></td>
                </tr>
            <?php
			}
			?>
            	<tr class="c_3">
                	<td colspan="4"></td>
                </tr>
            </tbody>
        </table>
        <?php
		}
		?>
    </div>
</div>
<div class="clear"></div>
<div class="bg_c w600" style="margin-top: 0;">
	<h1 class="big">Sitzungen</h1>
    <?php
	$sql = "SELECT id,IP,User_agent,Time_start,Last_updated,Active FROM `[sessions]` WHERE `Userid`='".$user['id']."'" . (isset($_GET['sess_group']) ? " GROUP BY IP" : '') . " ORDER BY Active DESC, id DESC";
	$pagination = new Pagination($sql, 50, 'p');
	$pagination_links = $pagination->GetPageLinks();
	$sessions = $pagination->GetSQLRows();
	
	if (count($sessions) <= 0)
	{
		echo '<p>Nichts gefunden!</p>';
	}
	else
	{
	?>
    <form method="post" action="">
    	<p class="center">
            <input type="submit" value="Abmelden" />
        </p>
        <p class="center">
        	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$user['id']?>&amp;p=<?=$pagination->current_page?><?=(isset($_GET['sess_group']) ? '' : '&amp;sess_group')?>"><?=(isset($_GET['sess_group']) ? "Alle IP's" : "IP's gruppieren")?></a>
        </p>
        <table class="table boxHandle">
            <thead>
                <tr>
                    <td>#</td>
                    <td>Datum</td>
                    <td>Letzte Aktivität</td>
                    <td>Browser</td>
                    <td>IP</td>
                    <td>Aktiv?</td>
                </tr>
            </thead>
            <tbody>
                <tr class="c_3">
                    <td colspan="6"><?=$pagination_links?></td>
                </tr>
            <?php
            $i = 0;
            
            foreach ($sessions as $session)
            {
                $i++;
                $c = $i%2 ? 1 : 2;
                
                $browser = get_browser($session['User_agent'], true);
            ?>
                <tr class="c_<?=$c?><?=($session['Active'] == 1 ? ' boxHandle' : '')?>">
                    <td><?php if ($session['Active'] == 1) echo '<input type="checkbox" name="sess_logout[]" value="' . $session['id'] . '" />';?>#<?=View::CashFormat($session['id'])?></td>
                    <td><?=View::Time($session['Time_start'])?></td>
                    <td><?=View::Time($session['Last_updated'])?></td>
                    <td><?=$browser['parent']?></td>
                    <td><a href="<?=$config['base_url']?>?side=game_panel/ip_lookup&amp;ip=<?=$session['IP']?>"><?=$session['IP']?></a></td>
                    <td><?=($session['Active'] == 1 ? 'Ja' : 'Nein')?></td>
                </tr>
            <?php
            }
            ?>
                <tr class="c_3">
                    <td colspan="6"><?=$pagination_links?></td>
                </tr>
            </tbody>
        </table>
        <p class="center">
            <input type="submit" value="Abmelden" />
        </p>
    </form>
    <?php
	}
	?>
</div>
<?}else{?>
    <div class="bg_c w250 c_1">
    	<h1 class="big">Benutzer suchen</h1>
        <form method="get" action="">
        	<input type="hidden" name="side" value="<?=$_GET['side']?>" />
            <dl class="dd_right">
            	<dt>Benutzer ID</dt>
                <dd><input type="text" class="flat" name="id" value="" /></dd>
            </dl>
            <p class="center clear">
            	<input type="submit" value="Suchen" />
            </p>
        </form>
	</div>
<?}?>