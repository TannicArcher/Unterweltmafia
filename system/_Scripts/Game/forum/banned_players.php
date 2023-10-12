<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$sql = "SELECT id,forumban,level,name,health FROM `[players]` WHERE `forumban`!='' ORDER BY id DESC";
	
	$pagination = new Pagination($sql, 20, 'p');
	
	$pagination_rows = $pagination->GetSQLRows();
	
	$pagination_links = $pagination->GetPageLinks();
?>
<p class="right"><input type="submit" class="nomargin" onclick="window.location.href = '<?=$config['base_url']?>?side=forum/ban_player'" value="Blocheaza un Jucator" /></p>
<div class="clear"></div>
<ul class="breadcrumb" style="margin: 0px auto;">
	<li class="home"><a href="<?=$config['base_url']?>?side=forum/"><img src="<?=$config['base_url']?>images/icons/home_icon.png" alt="Forum" /></a></li>
    <li class="last"><a href="<?=$config['base_url']?>?side=forum/banned_players">Jucatori blocati pe Forum</a></li>
</ul>
<?php
	if( count($pagination_rows) <= 0 ){
		echo '<h2 class="center">Nu sunt jucatori banati pe forum!</h2>';
		
	}else{
		
		if( isset($_POST['playerid']) ){
			
			$playerid = $db->EscapeString($_POST['playerid']);
			
			$sql = $db->Query("SELECT id,forumban,level,name,health FROM `[players]` WHERE `id`='$playerid'");
			$player = $db->FetchArray($sql);
			
			if( $player['id'] == "" ){
				echo View::Message('This player doesn\'t exists!', 2);
				
			}elseif( $player['forumban'] == "" ){
				echo View::Message('This player isn\'t blocked!', 2);
				
			}else{
				
				$db->Query("UPDATE `[players]` SET `forumban`='' WHERE `id`='".$player['id']."'");
				
				View::Message(View::Player($player, true).' can now access forum!', 1, true);
				
			}
			
		}
?>
<form method="post" action="">
	<table class="table boxHandle">
    	<thead>
        	<tr>
            	<td width="25%">Player</td>
                <td width="35%">Reason</td>
                <td width="20%">Start Date</td>
                <td width="20%">End Date</td>
            </tr>
        </thead>
        <tbody>
        	<tr class="c_3 center">
            	<td colspan="4" style="padding: 0;"><input type="submit" value="Remove" /></td>
            </tr>
        	<?php
				foreach( $pagination_rows as $player ):
					
					$i++;
					$class = $i%2 ? 1 : 2;
					
					$forumban = unserialize($player['forumban']);
			?>
            <tr class="c_<?=$class?> boxHandle">
            	<td width="25%"><input type="radio" name="playerid" value="<?=$player['id']?>" /><?=View::Player($player)?></td>
                <td width="35%"><?=nl2br(View::NoHTML($forumban['ban_reason'], true))?></td>
                <td width="20%"><?=View::Time($forumban['start'])?><br /><span class="subtext"><?=View::Player(array('id' => $forumban['ban_by']))?></span></td>
                <td width="20%" class="center"><?=View::Time($forumban['stop'])?></td>
            </tr>
            <?php
				endforeach;
			?>
            <tr class="c_3">
            	<td colspan="4" style="padding: 10px;"><?=$pagination_links?></td>
            </tr>
        </tbody>
    </table>
</form>
<?php
		
	}
?>