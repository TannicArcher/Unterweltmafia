<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	if (Player::Data('level') < 3 || User::Data('userlevel') < 3)
	{
		View::Message('Access denied.', 2, true, '/game/?side=startside');
	}
	
	$ip = $db->EscapeString($_GET['ip']);
	
	if (!preg_match('/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/', $ip))
	{
		View::Message('Falsche IP Adresse!', 2, true, '/game/?side=startside');
	}
?>
<p class="medium center"><?=$ip?></p>
<div class="bg_c w500">
	<h1 class="big">Sitzungen</h1>
    <?php
	$sql = "SELECT id,Userid,Time_start,Last_updated FROM `[sessions]` WHERE `IP` LIKE '".$ip."'" . (isset($_GET['sess_group']) ? " GROUP BY Userid" : '') . " ORDER BY id DESC";
	$pagination = new Pagination($sql, 20, 'page');
	$sessions = $pagination->GetSQLRows();
	
	if (count($sessions) <= 0)
	{
		echo '<p>Nothing found!</p>';
	}
	else
	{
	?>
    <p class="center">
    	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;ip=<?=$ip?>&amp;p=<?=$pagination->current_page?><?=(isset($_GET['sess_group']) ? '' : '&amp;sess_group')?>"><?=(isset($_GET['sess_group']) ? 'Alle Ip\´s' : 'IP\'s gruppieren')?></a>
    </p>
    <table class="table">
    	<thead>
        	<tr>
            	<td>#</td>
            	<td>Benutzer</td>
                <td>Datum</td>
                <td>Letzte Aktivität</td>
            </tr>
        </thead>
        <tbody>
        <?php
		foreach ($sessions as $session)
		{
			$i++;
			$c = $i%2 ? 1 : 2;
		?>
        	<tr class="c_<?=$c?>">
            	<td>#<?=View::CashFormat($session['id'])?></td>
            	<td><a href="<?=$config['base_url']?>?side=game_panel/user&amp;id=<?=$session['Userid']?>">#<?=$session['Userid']?></a></td>
                <td class="t_right"><?=View::Time($session['Time_start'])?></td>
                <td class="t_right"><?=View::Time($session['Last_updated'])?></td>
            </tr>
        <?php
		}
		?>
        	<tr class="c_3">
            	<td colspan="4"><?=$pagination->GetPageLinks()?></td>
            </tr>
        </tbody>
    </table>
    <?php
	}
	?>
</div>
<div class="bg_c w500">
	<h1 class="big">Spieler</h1>
    <?php
	$sql = "SELECT a.id, a.last_active, b.id AS pid, b.name, b.health, b.level FROM `[users]` a LEFT JOIN `[players]` b ON b.userid = a.id AND b.null = '0' WHERE a.IP_last LIKE '".$ip."' ORDER BY a.last_active DESC";
	$pg = new Pagination($sql, 20, 'page');
	$players = $pg->GetSQLRows();
	
	if (count($players) <= 0)
	{
		echo '<p>Nichts gefunden!</p>';
	}
	else
	{
	?>
    <table class="table">
    	<thead>
        	<tr>
            	<td>Spieler</td>
            	<td>Benutzer ID</td>
				<td>Status</td>
                <td>Letzte Aktivität</td>
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
            	<td><a href="<?=$config['base_url']?>?side=game_panel/player&amp;id=<?=$player['pid']?>"><?=($player['level'] == 0 ? '<del>'.$player['name'].'</del>' : $player['name'])?></a></td>
            	<td><a href="<?=$config['base_url']?>?side=game_panel/user&amp;id=<?=$player['id']?>">#<?=$player['id']?></a></td>
                <td><?=($player['level'] == 0 ? 'Gesperrt' : ($player['health'] == 0 ? 'Tot' : 'Aktiv'))?></td>
            	<td class="t_right"><?=View::Time($player['last_active'])?></td>
            </tr>
        <?php
		}
		?>
        	<tr class="c_3">
            	<td colspan="4"><?=$pg->GetPageLinks()?></td>
            </tr>
        </tbody>
    </table>
    <?php
	}
	?>
</div>
<div class="bg_c w500">
	<h1 class="big">Nachrichten</h1>
    <?php
	$sql = "SELECT * FROM `messages` WHERE `creator_ip`='".$ip."' ORDER BY id DESC";
	$pagination = new Pagination($sql, 20, 'p');
	$messages = $pagination->GetSQLRows();
	
	if (count($messages) <= 0)
	{
		echo '<p>Keine Nachrichten gefunden!</p>';
	}
	else
	{
	?>
    <table class="table">
    	<thead>
        	<tr>
            	<td>Versender</td>
            	<td>Empfänger</td>
                <td>Nachricht</td>
                <td>Datum</td>
            </tr>
        </thead>
        <tbody>
        <?php
		$i = 0;
		
		foreach ($messages as $message)
		{
			$i++;
			$c = $i%2 ? 1 : 2;
			$msg = explode("|", $message['players']);
		?>
        	<tr class="c_<?=$c?>">
            	<td><?=View::Player(array('id' => $msg[1]))?></td>
            	<td><?=View::Player(array('id' => $msg[2]))?></td>
                <td>&laquo;<a href="<?=$config['base_url']?>?side=game_panel/messages&amp;id=<?=$message['id']?>"><?=View::NoHTML($message['title'])?></a>&raquo;</td>
                <td class="t_right"><?=View::Time($message['created'])?></td>
            </tr>
        <?php
		}
		?>
        	<tr class="c_3">
            	<td colspan="4"><?=$pagination->GetPageLinks()?></td>
            </tr>
        </tbody>
    </table>
    <?php
	}
	?>
</div>