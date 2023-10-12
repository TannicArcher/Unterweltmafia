<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	
	if( isset($_POST['ban_player']) ){
		
		$player = $db->EscapeString($_POST['ban_player']);
		$reason = $db->EscapeString($_POST['ban_reason']);
		$revoke = $db->EscapeString($_POST['ban_revoke']);
		
		$s = array('sekunder', 'sekund', 'minutter', 'minutt', 'timer', 'time', 'dager', 'dag');
		$r = array('seconds', 'second', 'minutes', 'minute', 'hours', 'hour', 'days', 'day');
		
		$revoke = str_replace($s, $r, $revoke) != $revoke ? str_replace($s, $r, $revoke) : $revoke;
		
		$revoke_time = $revoke == "" ? 'never' : strtotime($revoke);
		
		$sql    = $db->Query("SELECT id,level,forumban,name,health FROM `[players]` WHERE `name`='$player'");
		$player = $db->FetchArray($sql);
		
		if( $player['id'] == "" ){
			echo View::Message('This player doesn\'t exists!', 2);
			
		}elseif( !empty($player['forumban']) ){
			echo View::Message('This player was already blocked!', 2);
			
		}elseif( $player['id'] == Player::Data('id') ){
			echo View::Message('You cannot ban yourself!', 2);
			
		}elseif( $player['level'] > 3 ){
			echo View::Message('Administrator cannot be banned!', 2);
			
		}elseif( View::Lenght($reason) < 5 ){
			echo View::Message('The reason must have at leas 5 characters!', 2);
			
		}elseif( $revoke_time != 'never' && $revoke_time < time()+1800 ){
			echo View::Message('You have to block this player for at least 30 minutes!', 2);
	
		}else{
			
			$forumban = array(
				'start' => time(),
				'stop' => $revoke_time,
				'ban_by' => Player::Data('id'),
				'ban_reason' => $reason,
			);
			$forumban = serialize($forumban);
			
			$db->Query("UPDATE `[players]` SET `forumban`='$forumban' WHERE `id`='".$player['id']."'");
			
			$revoke = $revoke_time == 'never' ? 'aldri' : View::Time($revoke_time);
			View::Message(View::Player($player, true).' was banned.', 1, true);
			
		}
		
	}
?>
<ul class="breadcrumb" style="margin: 0px auto;">
	<li class="home"><a href="<?=$config['base_url']?>?side=forum/"><img src="<?=$config['base_url']?>images/icons/home_icon.png" alt="Forum oversikt" /></a></li>
    <li><a href="<?=$config['base_url']?>?side=forum/banned_players">Banned Players</a></li>
    <li class="last"><a href="<?=$config['base_url']?>?side=forum/ban_player">Ban Player</a></li>
</ul>
<div class="bg_c">
	<p class="topnav">
    	<a href="<?=$config['base_url']?>?side=forum/banned_players">&laquo; Back</a>
    </p>
	<h1>Info</h1>
    <p>Here you can ban players on forum. An banned player, can't post, read or open new threads on forum.</p>
    <ul>
    	<?php foreach( $config['forums'] as $f_id => $forum ){ if($forum[6] == true){ ?>
        <li><a href="<?=$config['base_url']?>?side=forum/forum&amp;f=<?=$f_id?>"><?=$forum[0]?></a></li>
        <?php } } ?>
    </ul>
    
    <h1>Ban Player</h1>
    <ul>
    	<li>"+x second(s)" = current time + X sec</li>
        <li>"+x minute(s)" = current time + X min</li>
        <li>"+x hour(s)" = current time + X hours</li>
        <li>"+x day(s)" = current time + X days</li>
    </ul>
    <form method="post" action="">
        <dl class="form">
            <dt>Player</dt>
            <dd><input type="text" name="ban_player" class="styled" value="<?=$_POST['ban_player']?>" /></dd>
            <dt>Reason</dt>
            <dd><textarea name="ban_reason" cols="32" rows="10"><?=$_POST['ban_reason']?></textarea></dd>
            <dt>End time</dt>
            <dd><input type="text" name="ban_revoke" class="styled" value="<?=$_POST['ban_revoke']?>" /></dd>
        </dl>
        <div class="center clear" style="margin-top: 5px;">
            <input type="submit" value="Ban" />
        </div>
    </form>
</div>