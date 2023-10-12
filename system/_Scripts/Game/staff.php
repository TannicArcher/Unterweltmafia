<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$order = explode("|", $db->EscapeString($_GET['order']));
	$order_row = $order[0];
	$order_method = $order[1];
	
	$order_row = (!in_array($order_row, array('name', 'level', 'last_active'))) ? 'last_active' : $order_row;
	$order_method = (!in_array($order_method, array('desc', 'asc'))) ? 'desc' : $order_method;
	
	$sql  = 'SELECT id,last_active,level,name,health FROM `[players]` WHERE `level`>"1" ORDER BY `'.$order_row.'` '.$order_method;

	$pagination = new Pagination($sql, 20, 'p');
	
	$pagination_links = $pagination->GetPageLinks();
	
	$spillere = $pagination->GetSQLRows();

	$sorts['name'] = ($_GET['order'] == 'name|desc') ? 'asc' : 'desc';
	$sorts['level'] = ($_GET['order'] == 'level|desc') ? 'asc' : 'desc';
	$sorts['last_active'] = ($_GET['order'] == 'last_active|desc') ? 'asc' : 'desc';
	
	
	if( $pagination->num_rows <= 0 ){
		echo '<h2 class="center">'.$langBase->get('err-06').'</h2>';
	}else{
		if( isset($_GET['fire']) && User::Data('userlevel') == 4 ){
			
			$player = $db->EscapeString($_GET['fire']);
			
			$sql    = $db->Query("SELECT id,userid,level,name,health FROM `[players]` WHERE `id`='$player'");
			$player = $db->FetchArray($sql);
			
			$sql    = $db->Query("SELECT id FROM `[users]` WHERE `id`='".$player['userid']."'");
			$user   = $db->FetchArray($sql);
			
			if( $player['id'] == "" ){
				View::Message($langBase->get('err-01'), 2, true, '/game/?side='.$_GET['side'].'&p='.$pagination->current_page);
				
			}elseif( $player['level'] <= 1 ){
				View::Message('This members is not from Staff', 2, true, '/game/?side='.$_GET['side'].'&p='.$pagination->current_page);
				
			}elseif( $player['level'] == 4 ){
				View::Message('You can\'t fire an Admin!', 2, true, '/game/?side='.$_GET['side'].'&p='.$pagination->current_page);
				
			}elseif( $player['id'] == Player::Data('id') ){
				View::Message('You cannot fire yourself!', 2, true, '/game/?side='.$_GET['side'].'&p='.$pagination->current_page);
				
			}else{
				
				$sql = "UPDATE `[players]` SET `level`='1'";
				$sql .= ($player['level'] > 2) ? ", `rank`='1', `rankpoints`='0', `cash`='1000', `bank`='1000'" : '';
				$sql .= " WHERE `userid`='".$user['id']."'";
				
				$db->Query($sql);
				$db->Query("UPDATE `[users]` SET `userlevel`='1' WHERE `id`='".$user['id']."'");
				
				Accessories::AddToLog(Player::Data('id'), array('fired_player' => $player['id']));
				
				View::Message(View::Player($player).' was fired', 1, true, '/game/?side='.$_GET['side'].'&p='.$pagination->current_page);
				
			}
			
		}elseif( isset($_GET['hire']) && User::Data('userlevel') == 4 ){
			
			if( isset($_POST['hire_player']) ){
				
				$player = $db->EscapeString($_POST['hire_player']);
				$pass   = View::DoubleSalt($_POST['user_pass'], User::Data('id'));
				
				$sql    = $db->Query("SELECT id,userid,level,name,health FROM `[players]` WHERE `name`='$player'");
				$player = $db->FetchArray($sql);
				
				$stilling = $db->EscapeString($_POST['hire_job']);
				
				if( $pass != User::Data('pass') ){
					echo View::Message($langBase->get('txt-20'), 2);
					
				}elseif( $player['id'] == "" ){
					echo View::Message($langBase->get('err-02'), 2);
					
				}elseif( !in_array($stilling, array(1, 2, 3)) ){
					echo View::Message('ERROR', 2);
					
				}else{
					
					$stilling++;
					
					$sql = "UPDATE `[players]` SET `level`='$stilling'";
					$sql .= ($stilling > 2) ? ", `rank`='".count($config['ranks'])."', `rankpoints`='".$config['ranks'][count($config['ranks'])][1]."'" : '';
					$sql .= " WHERE `id`='".$player['id']."'";
					
					$db->Query($sql);
					$db->Query("UPDATE `[users]` SET `userlevel`='$stilling' WHERE `id`='".$player['userid']."'");
					
					$stillinger = array('Support', 'Moderator', 'Administrator');
					
					Accessories::AddToLog(Player::Data('id'), array('hired_player' => $player['id']));
					
					View::Message(View::Player($player).' is now <b>'.$stillinger[$stilling-2].'</b>', 1, true, '/game/?side='.$_GET['side'].'&p='.$pagination->current_page);
					
				}
				
			}
?>
<div class="main" style="width: 50%;">
	<h1 class="heading">Upgrade / Downgrade member</h1>
    <form method="post" action="">
    	<dl class="form">
        	<dt><?=$langBase->get('txt-06')?></dt>
            <dd><input type="text" name="hire_player" class="styled" value="<?=$_POST['hire_player']?>" /></dd>
            <dt>Job</dt>
            <dd><select name="hire_job"><option value="1">Support</option><option value="2">Moderator</option><option value="3">Administrator</option></select></dd>
            <dt>Pasword</dt>
            <dd><input type="password" name="user_pass" class="styled" value="<?=$_POST['user_pass']?>" /></dd>
        </dl>
        <div class="center clear">
        	<input type="submit" value="Trimite" />
        </div>
    </form>
</div>
<?php
		}
		
		
		$stillinger = array(2 => $langBase->get('crew-02'), 3 => $langBase->get('crew-03'), 4 => $langBase->get('crew-04'));
		
		if( User::Data('userlevel') == 4 ){
?>
<div class="center" style="margin-top: 5px;">
	<input type="submit" value="Ofera Ranguri"<?php echo (isset($_GET['hire'])) ? ' class="active"' : ''; ?> onclick="window.location.href = '<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;p=<?=$pagination->current_page?><?php echo (isset($_GET['hire'])) ? '' : '&amp;hire'; ?>';" />
</div>
<?php } ?>
<table class="table center" width="90%">
	<thead>
    	<tr>
        	<td width="40%"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;p=<?=$pagination->current_page?>&amp;order=name|<?=$sorts['name']?>"><?=$langBase->get('txt-06')?></a></td>
            <td width="22%"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;p=<?=$pagination->current_page?>&amp;order=level|<?=$sorts['level']?>"><?=$langBase->get('ot-rank')?></a></td>
            <td width="25%"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;p=<?=$pagination->current_page?>&amp;order=last_active|<?=$sorts['last_active']?>"><?=$langBase->get('ot-lasta')?></a></td>
            <td width="13%"><?=$langBase->get('ot-send-pm')?></td>
            <?php echo (User::Data('userlevel') == 4) ? '<td width="5%">'.$langBase->get('crew-01').'</td>' : ''; ?>
        </tr>
    </thead>
    <tbody>
    	
    	<tr class="c_3"><td colspan="<?php echo (User::Data('userlevel') == 4) ? 5 : 4; ?>"><?=$pagination_links?></td></tr>
        
        <?php
			foreach($spillere as $spiller):
				
				$c++;
				$color = ($c%2) ? 1 : 2;
		?>
        <tr class="c_<?=$color?>">
        	<td width="40%" class="t_left" style="padding-left: 20px;"><?=View::Player($spiller)?> <?=View::NickAdd($spiller['level'])?></td>
            <td width="22%" class="t_left" style="padding-left: 20px;"><?=$stillinger[$spiller['level']]?></td>
            <td width="25%"><span class="toggleHTML" title='<?=View::strTime(time()-$spiller['last_active'], 1, ', ', 0)?>'><?=View::Time($spiller['last_active'])?></span></td>
            <td width="13%"><a href="<?=$config['base_url']?>?side=mesaje&amp;a=ny&amp;nick=<?=$spiller['name']?>"><?=$langBase->get('ot-send-pm')?></a></td>
            <?php echo (User::Data('userlevel') == 4) ? '<td width="5%"><a href="'.$config['base_url'].'?side='.$_GET['side'].'&amp;p='.$pagination->current_page.'&amp;fire='.$spiller['id'].'" onclick="return confirm(\''.$langBase->get('err-05').'\')">'.$langBase->get('crew-01').'</a></td>' : ''; ?>
        </tr>
        <?php
			endforeach;
		?>
        
        <tr class="c_3"><td colspan="<?php echo (User::Data('userlevel') == 4) ? 5 : 4; ?>"><?=$pagination_links?></td></tr>
        
    </tbody>
</table>
<?php
		
	}
?>