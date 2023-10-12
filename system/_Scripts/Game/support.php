<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	function validate64($buffer)
	{
	  $VALID  = 1;
	  $INVALID= 0;
	
	  $p    = $buffer;   
	  $len  = strlen($p);      
	 
	  for($i=0; $i<$len; $i++)
	  {
		 if( ($p[$i]>="A" && $p[$i]<="Z")||
			 ($p[$i]>="a" && $p[$i]<="z")||
			 ($p[$i]>="/" && $p[$i]<="9")||
			 ($p[$i]=="+")||
			 ($p[$i]=="=")||
			 ($p[$i]=="\x0a")||
			 ($p[$i]=="\x0d")
		   )
		   continue;
		 else
		   return $INVALID;
	  }
	return $VALID;
	}
	
	if (isset($_GET['id']))
	{
		$ticket = $db->EscapeString($_GET['id']);
		$sql = $db->Query("SELECT * FROM `support_tickets` WHERE `id`='".$ticket."'");
		$ticket = $db->FetchArray($sql);
		
		if ($ticket['player'] != Player::Data('id') && Player::Data('level') < 2)
		{
			View::Message('ERROR!', 2, true, '/game/?side=' . $_GET['side']);
		}
		
		if (isset($_POST['reservation']) && $ticket['reservation'] == 0 && Player::Data('level') >= 2)
		{
			$db->Query("UPDATE `support_tickets` SET `reservation`='".Player::Data('id')."' WHERE `id`='".$ticket['id']."'");
			
			View::Message($langBase->get('suport-01'), 1, true);
		}
		elseif (isset($_POST['ticket_changeState']) && Player::Data('level') >= 2)
		{
			$newState = $ticket['treated'] == 0 ? 1 : 0;
			$db->Query("UPDATE `support_tickets` SET `treated`='".$newState."' WHERE `id`='".$ticket['id']."'");
			
			if ($newState == 1)
			{
				Accessories::AddLogEvent($ticket['player'], 28, array(
					'-TICKET_ID-' => $ticket['id']
				));
			}
			
			View::Message($langBase->get('suport-02'), 1, true);
		}
		elseif (isset($_POST['addReply_text']))
		{
			$text = $db->EscapeString($_POST['addReply_text']);
			
			if (View::Length($text) < 2)
			{
				echo View::Message($langBase->get('suport-03'), 2);
			}
			else
			{
				$replies = unserialize($ticket['replies']);
				$replies[] = array(
					'player' => Player::Data('id'),
					'text' => base64_encode($text),
					'time' => time()
				);
				
				if ($ticket['player'] != Player::Data('id'))
				{
					Accessories::AddLogEvent($ticket['player'], 42, array(
						'-TICKET_ID-' => $ticket['id']
					));
				}
				
				$db->Query("UPDATE `support_tickets` SET `replies`='".serialize($replies)."' WHERE `id`='".$ticket['id']."'");
				
				View::Message($langBase->get('suport-04'), 1, true);
			}
		}
		elseif (isset($_GET['d']))
		{
			$replies = unserialize($ticket['replies']);
			$r_id = $db->EscapeString($_GET['d']);
			$reply = $replies[$r_id];
			
			if (!$reply || !(($reply['player'] == Player::Data('id') && Player::Data('level') > 1) || Player::Data('level') > 1))
			{
				echo View::Message('ERROR', 2);
			}
			else
			{
				unset($replies[$r_id]);
				$db->Query("UPDATE `support_tickets` SET `replies`='".serialize($replies)."' WHERE `id`='".$ticket['id']."'");
				
				View::Message($langBase->get('suport-05'), 1, true, '/game/?side=' . $_GET['side'] . '&id=' . $ticket['id']);
			}
		}
		
		$replies = unserialize($ticket['replies']);
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('suport-06')?></h1>
    <p class="t_right" style="margin-bottom: 20px;">
        <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>" class="button">&laquo; <?=$langBase->get('ot-back')?></a>
    </p>
    <div class="c_1 left" style="padding: 10px; margin-left: 5px; width: 220px;">
    	<dl class="dd_right" style="margin: 0;">
        	<dt><?=$langBase->get('suport-07')?></dt>
            <dd><?=View::NoHTML($ticket['title'])?></dd>
            <dt><?=$langBase->get('suport-08')?></dt>
            <dd><?=$config['support_tickets_categories'][$ticket['category']]?></dd>
        	<dt><?=$langBase->get('txt-31')?></dt>
            <dd><?=View::Player(array('id' => $ticket['player']))?></dd>
            <dt><?=$langBase->get('txt-27')?></dt>
            <dd><?=View::Time($ticket['created'], false, 'H:i')?></dd>
            <dt><?=$langBase->get('ot-status')?></dt>
            <dd><?=($ticket['treated'] == 0 ? $langBase->get('suport-09') : $langBase->get('suport-10'))?></dd>
            <?php
			if (Player::Data('level') >= 2):
			?>
            <dt<?php if ($ticket['reservation'] != 0) echo ' class="red"';?>><?=$langBase->get('suport-11')?></dt>
            <dd><?=($ticket['reservation'] == 0 ? '<b>N/A</b>' : View::Player(array('id' => $ticket['reservation'])))?></dd>
            <?php
			endif;
			?>
        </dl>
        <div class="clear"></div>
        <?php if (Player::Data('level') >= 2):?>
        <form method="post" action="">
        	<p class="center">
            	<?php if ($ticket['reservation'] == 0) echo '<input type="submit" name="reservation" value="'.$langBase->get('suport-10').'" /> ';?>
            	<input type="submit" name="ticket_changeState" value="<?=($ticket['treated'] == 0 ? $langBase->get('suport-12') : $langBase->get('suport-13'))?>" />
            </p>
        </form>
        <?php endif;?>
    </div>
    <div class="c_1 left" style="padding: 10px; margin-left: 10px; width: 320px;">
   	<?php
	$bb = new BBCodeParser(trim($ticket['text']) == '' ? 'N/A' : stripslashes($ticket['text']), 'support_ticket', true);
	echo $bb->result;
	?>
    </div>
    <div class="clear"></div>
    <div class="bg_c c_1" style="width: 580px;">
    	<h1 class="big"><?=$langBase->get('suport-14')?></h1>
        <form method="post" action="">
        	<dl class="form">
            	<dt><?=$langBase->get('txt-39')?></dt>
                <dd><textarea name="addReply_text" cols="75" rows="6" style="width: 500px;"><?=$_POST['addReply_text']?></textarea></dd>
            </dl>
            <p class="clear center">
            	<input type="submit" value="<?=$langBase->get('suport-14')?>" />
            </p>
        </form>
    </div>
    <div class="bg_c c_1 w400" style="width: 580px;">
    	<h1 class="big"><?=$langBase->get('suport-15')?></h1>
        <?php
		if (count($replies) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
			krsort($replies);
			
			foreach ($replies as $r_id => $reply)
			{
		?>
        <div class="c_2" style="padding: 10px; margin: 5px;">
        	<p style="margin: 0 0 10px 0;">
            <?php
			$text = $reply['text'];
			if (validate64($text))
				$text = base64_decode($text);

			$bb = new BBCodeParser(trim($text) == '' ? 'N/A' : $text, 'support_ticket_reply', true);
			$text = str_replace('\r\n','<br />',$bb->result);
			echo stripslashes($text);
			?>
            </p>
            <p class="small dark left"><?=$langBase->get('stiri-06')?> <?=View::Player(array('id' => $reply['player']))?> - <?=View::Time($reply['time'], true)?></p>
            <p class="right"><?php if (($reply['player'] == Player::Data('id') && Player::Data('level') > 1) || Player::Data('level') > 1) echo '<a href="' . $config['base_url'] . '?side=' . $_GET['side'] . '&amp;id=' . $ticket['id'] . '&amp;d=' . $r_id . '">&laquo; '.$langBase->get('txt-36').'</a>';?></p>
            <div class="clear"></div>
        </div>
        <?php
			}
		}
		?>
    </div>
</div>
<?php
	}
	else
	{
		if (isset($_GET['panel']) && Player::Data('level') >= 2)
		{
			echo '<p class="center"><a href="'.$config['base_url'].'?side='.$_GET['side'].'" class="button">&laquo; '.$langBase->get('ot-back').'</a></p>';
			
			$sql = "SELECT id,title,category,treated,created FROM `support_tickets`" . (!isset($_GET['st']) ? " WHERE `treated`='0'" : '') . " ORDER BY id DESC";
			$pagination = new Pagination($sql, 10, 'p');
			$tickets = $pagination->GetSQLRows();
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('suport-16')?></h1>
    <?php
	echo '<p class="t_right" style="margin: 15px 5px 15px 5px;"><a href="'.$config['base_url'].'?side='.$_GET['side'].'&amp;panel'.(!isset($_GET['st']) ? '&amp;st' : '').'" class="button">&laquo; '.(isset($_GET['st']) ? $langBase->get('suport-17') : $langBase->get('suport-18')).'</a></p>';
	
	if (count($tickets) <= 0)
	{
		echo '<p>'.$langBase->get('err-06').'</p>';
	}
	else
	{
	?>
    <table class="table">
    	<thead>
        	<tr>
            	<td><?=$langBase->get('suport-07')?></td>
                <td><?=$langBase->get('suport-08')?></td>
                <td><?=$langBase->get('ot-stats')?></td>
                <td><?=$langBase->get('txt-27')?></td>
            </tr>
        </thead>
        <tbody>
        <?php
		foreach ($tickets as $ticket)
		{
			$i++;
			$c = $i%2 ? 1 : 2;
		?>
        	<tr class="c_<?=$c?>">
            	<td>&laquo;<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$ticket['id']?>"><?=View::NoHTML($ticket['title'])?></a>&raquo;</td>
                <td class="center"><?=$config['support_tickets_categories'][$ticket['category']]?></td>
                <td class="center"><?=($ticket['treated'] == 0 ? $langBase->get('suport-09') : $langBase->get('suport-10'))?></td>
                <td class="t_right"><?=View::Time($ticket['created'])?></td>
            </tr>
        <?php
		}
		?>
        	<tr class="c_3 center">
            	<td colspan="4"><?=$pagination->GetPageLinks()?></td>
            </tr>
        </tbody>
    </table>
    <?php
	}
	?>
</div>
<?php
		}
		else
		{
			echo '<p class="center">';
			if (Player::Data('level') >= 2)
				echo '<a href="' . $config['base_url'] . '?side=' . $_GET['side'] . '&amp;panel" class="button">'.$langBase->get('suport-19').' &raquo;</a> ';
			
			echo '<a href="' . $config['base_url'] . '?side=' . $_GET['side'] . '&amp;new" class="button">'.$langBase->get('suport-20').' &raquo;</a>
				  </p>';
			
			if (isset($_GET['new']))
			{
				if (isset($_POST['new_title']))
				{
					$title_min = 5;
					$title_max = 35;
					$text_min = 20;
					
					$title = $db->EscapeString($_POST['new_title']);
					$text = $db->EscapeString($_POST['new_text']);
					$category = $db->EscapeString($_POST['new_category']);
					
					if (View::Length($title) < $title_min)
					{
						echo View::Message($langBase->get('suport-21', array('-NUM-' => $title_min)), 2);
					}
					elseif (View::Length($title) > $title_max)
					{
						echo View::Message($langBase->get('suport-22', array('-NUM-' => $title_max)), 2);
					}
					elseif (View::Length($text) < $text_min)
					{
						echo View::Message($langBase->get('suport-23', array('-NUM-' => $text_min)), 2);
					}
					elseif (!$config['support_tickets_categories'][$category])
					{
						echo View::Message($langBase->get('suport-24'), 2);
					}
					else
					{
						$db->Query("INSERT INTO `support_tickets` (`player`, `created`, `text`, `title`, `category`)VALUES('".Player::Data('id')."', '".time()."', '".$text."', '".$title."', '".$category."')");
						
						View::Message($langBase->get('suport-02'), 1, true, '/game/?side=' . $_GET['side'] . '&id=' . mysql_insert_id());
					}
				}
?>
<div class="bg_c w400">
	<h1 class="big"><?=$langBase->get('suport-25')?></h1>
    <form method="post" action="">
    	<dl class="form">
        	<dt><?=$langBase->get('suport-07')?></dt>
            <dd><input type="text" name="new_title" class="styled" style="min-width: 200px; width: 200px;" maxlength="<?=$title_max?>" value="<?=View::FixQuot($_POST['new_title'])?>" /></dd>
            <dt><?=$langBase->get('suport-08')?></dt>
            <dd>
            	<select name="new_category" style="margin: 5px 0 5px 0;">
                	<option value=""><?=$langBase->get('cereri-32')?>...</option>
                <?php
				foreach ($config['support_tickets_categories'] as $key => $value)
				{
					echo '<option value="' . $key . '"' . (isset($_POST['new_category']) && $_POST['new_category'] == $key ? ' selected="selected"' : '') . '>' . $value . '</option>';
				}
				?>
                </select>
            </dd>
            <dt><?=$langBase->get('txt-39')?></dt>
            <dd><textarea name="new_text" cols="50" rows="10"><?=$_POST['new_text']?></textarea></dd>
        </dl>
        <p class="center clear">
        	<input type="submit" value="<?=$langBase->get('suport-26')?>" /> <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>" class="button">&laquo; <?=$langBase->get('txt-10')?></a>
        </p>
    </form>
</div>
<?php
			}
?>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('suport-27')?></h1>
    <?php
	$sql = "SELECT id,title,treated,created,category FROM `support_tickets` WHERE `player`='".Player::Data('id')."' ORDER BY treated ASC, id DESC";
	$pagination = new Pagination($sql, 10, 'p');
	$tickets = $pagination->GetSQLRows();
	
	if (count($tickets) <= 0)
	{
		echo '<p>'.$langBase->get('err-06').'</p>';
	}
	else
	{
	?>
    <table class="table">
    	<thead>
        	<tr>
            	<td><?=$langBase->get('suport-07')?></td>
                <td><?=$langBase->get('suport-08')?></td>
                <td><?=$langBase->get('ot-status')?></td>
                <td><?=$langBase->get('txt-27')?></td>
            </tr>
        </thead>
        <tbody>
        <?php
		foreach ($tickets as $ticket)
		{
			$i++;
			$c = $i%2 ? 1 : 2;
		?>
        	<tr class="c_<?=$c?>">
            	<td>&laquo;<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$ticket['id']?>"><?=View::NoHTML($ticket['title'])?></a>&raquo;</td>
                <td class="center"><?=$config['support_tickets_categories'][$ticket['category']]?></td>
                <td class="center"><?=($ticket['treated'] == 0 ? $langBase->get('suport-09') : $langBase->get('suport-10'))?></td>
                <td class="t_right"><?=View::Time($ticket['created'])?></td>
            </tr>
        <?php
		}
		?>
        	<tr class="c_3 center">
            	<td colspan="4"><?=$pagination->GetPageLinks()?></td>
            </tr>
        </tbody>
    </table>
    <?php
	}
	?>
</div>
<?php
		}
	}
?>