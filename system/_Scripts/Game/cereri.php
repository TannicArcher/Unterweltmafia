<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	if (isset($_GET['create']))
	{
		if (isset($_POST['cancel']))
		{
			header('Location: /game/?side=cereri');
			exit;
		}
		elseif (isset($_POST['text']))
		{
			$playerhistory = isset($_POST['show_playerhistory']) ? 1 : 0;
			$text = $db->EscapeString($_POST['text']);
			
			if ($db->GetNumRows($db->Query("SELECT id FROM `soknader` WHERE `from_player`='".Player::Data('id')."' AND `sent`='0' AND `confirmed`='0' AND `handled`='0' AND `deleted`='0' LIMIT 0,".$config['soknad_max_active']."")) >= $config['soknad_max_active'])
			{
				echo View::Message($langBase->get('cereri-01', array('-NUM-' => $config['soknad_max_active'])), 2);
			}
			elseif (View::Length($text) < $config['soknad_text_min_length'])
			{
				echo View::Message($langBase->get('cereri-02', array('-NUM-' => $config['soknad_text_min_length'])), 2);
			}
			else
			{
				$db->Query("INSERT INTO `soknader` (`from_user`, `from_player`, `text`, `show_playerhistory`, `time_created`)VALUES('".User::Data('id')."', '".Player::Data('id')."', '$text', '$playerhistory', '".time()."')");
				
				View::Message($langBase->get('cereri-03'), 1, true, '/game/?side=' . $_GET['side'] . '&view=' . mysql_insert_id());
			}
		}
?>
<p class="t_right">
    <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>" class="button big">&laquo; <?=$langBase->get('ot-back')?></a>
</p>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('cereri-04')?></h1>
    <form method="post" action="">
    	<dl class="form">
        	<dt><?=$langBase->get('cereri-05')?></dt>
            <dd><input type="checkbox" name="show_playerhistory"<?php if(isset($_POST['show_playerhistory'])) echo ' checked="checked"'; ?> /></dd>
            <dt><?=$langBase->get('txt-39')?></dt>
            <dd><textarea name="text" cols="60" rows="16" style="width: 378px;"><?=$_POST['text']?></textarea></dd>
            <dd><input type="submit" value="<?=$langBase->get('cereri-06')?>" style="width: 280px;" /><input type="submit" value="<?=$langBase->get('txt-10')?>" name="cancel" style="width: 100px; margin-left: 10px;" /></dd>
        </dl>
        <div class="clear"></div>
    </form>
</div>
<?php
	}
	elseif (!empty($_GET['view']))
	{
		$soknad = $db->EscapeString($_GET['view']);
		$sql = $db->Query("SELECT * FROM `soknader` WHERE `from_user`='".User::Data('id')."' AND `deleted`='0' AND `id`='$soknad'");
		$soknad = $db->FetchArray($sql);
		
		if ($soknad['id'] == '')
		{
			View::Message('ERROR', 2, true, '/game/?side=cereri');
		}
		else
		{
			if ($soknad['from_player'] != Player::Data('id'))
			{
				$db->Query("UPDATE `soknader` SET `from_player`='".Player::Data('id')."' WHERE `id`='".$soknad['id']."'");
				$soknad['from_player'] = Player::Data('id');
			}
			
			$treatment = $langBase->get('cereri-07');
			if ($soknad['handled'] == 1) {
				$treatment = $langBase->get('cereri-08');
			} elseif ($soknad['handled'] == 2) {
				$treatment = $langBase->get('cereri-09');
			}
			
			$receiver = explode(',', $soknad['receiver']);
			$receiver_type = $config['soknad_types'][$receiver[0]];
			
			$receiver_text = $langBase->get('cereri-10');
			if ($receiver[0] == 1 && $receiver_type != '')
			{
				$sql = $db->Query("SELECT id,name,active,type FROM `businesses` WHERE `id`='".$receiver[1]."'");
				$firma = $db->FetchArray($sql);
				
				$receiver_text = $firma['active'] == 0 ? '&laquo;' . $firma['name'] . '&raquo;' : '&laquo;<a href="' . $config['base_url'] . '?side=firma/firma&amp;id=' . $firma['id'] . '">' . $firma['name'] . '</a>&raquo;<br />' . $config['business_types'][$firma['type']]['name'][2];
			}
			elseif ($receiver[0] == 2 && $receiver_type != '')
			{
				$sql = $db->Query("SELECT id,name,active FROM `[families]` WHERE `id`='".$receiver[1]."'");
				$family = $db->FetchArray($sql);
				
				$receiver_text = $family['active'] == 0 ? '&laquo;' . $family['name'] . '&raquo;' : '&laquo;<a href="' . $config['base_url'] . '?side=familie/familie&amp;id=' . $family['id'] . '">' . $family['name'] . '</a>&raquo;<br />'.$langBase->get('ot-family');
			}
			
			
			if (($soknad['handled'] == 2 && $soknad['confirmed'] == 0) && isset($_GET['confirm']))
			{
				$receiver = explode(',', $soknad['receiver']);
				$receiver_type = $config['soknad_types'][$receiver[0]];
				
				if ($receiver[0] == 1 && $receiver_type != '')
				{
					$sql = $db->Query("SELECT id,name,active,type,job_1,job_2,image FROM `businesses` WHERE `id`='".$receiver[1]."'");
					$firma = $db->FetchArray($sql);
					$firmatype = $config['business_types'][$firma['type']];
					
					if ($firma['id'] == '' || $firma['active'] == 0)
					{
						$db->Query("UPDATE `soknader` SET `handled`='0', `receiver`='0,0' WHERE `id`='".$soknad['id']."'");
						
						View::Message($langBase->get('cereri-11'), 2, true, '/game/?side='.$_GET['side'].'&view='.$soknad['id']);
					}
					elseif(in_array(Player::Data('id'), array($firma['job_1'], $firma['job_2'])))
					{
						$db->Query("UPDATE `soknader` SET `handled`='0', `receiver`='0,0' WHERE `id`='".$soknad['id']."'");
						
						View::Message($langBase->get('cereri-12'), 2, true, '/game/?side='.$_GET['side'].'&view='.$soknad['id']);
					}
					else
					{
						$db->Query("UPDATE `businesses` SET `job_2`='".Player::Data('id')."' WHERE `id`='".$firma['id']."'");
						$db->Query("INSERT INTO `business_log` (`b_id`, `text`, `type`, `added`, `added_date`)VALUES('".$firma['id']."', '".View::Player(Player::$datavar, true)." a confirmat cererea lui ".View::Player(Player::$datavar, true)." trimisa pentru pozitia de ".strtolower($firmatype['job_titles'][1]).".', '4', '".time()."', '".date('d.m.Y')."')");
						$db->Query("UPDATE `soknader` SET `confirmed`='2' WHERE `id`='".$soknad['id']."'");
						
						Accessories::AddLogEvent(Player::Data('id'), 9, array(
							'-COMPANY_IMG-' => $firma['image'],
							'-COMPANY_NAME-' => $firma['name'],
							'-COMPANY_ID-' => $firma['id']
 						), User::Data('id'));
						
						View::Message($langBase->get('cereri-13'), 1, true, '/game/?side='.$_GET['side'].'&view='.$soknad['id']);
					}
				}
				elseif ($receiver[0] == 2 && $receiver_type != '')
				{
					$sql = $db->Query("SELECT id,name,active,members,max_members_type,image FROM `[families]` WHERE `id`='".$receiver[1]."'");
					$family = $db->FetchArray($sql);
					$sizeType = $config['family_max_member_types'][$family['max_members_type']];
					$members = unserialize($family['members']);
					
					if ($family['id'] == '' || $family['active'] == 0)
					{
						$db->Query("UPDATE `soknader` SET `handled`='0', `receiver`='0,0' WHERE `id`='".$soknad['id']."'");
						
						View::Message($langBase->get('cereri-14'), 2, true, '/game/?side='.$_GET['side'].'&view='.$soknad['id']);
					}
					elseif (Player::FamilyData('id'))
					{
						View::Message($langBase->get('cereri-15'), 2, true, '/game/?side='.$_GET['side'].'&view='.$soknad['id']);
					}
					elseif ($members[Player::Data('id')])
					{
						$db->Query("UPDATE `soknader` SET `handled`='0', `receiver`='0,0' WHERE `id`='".$soknad['id']."'");
						
						View::Message($langBase->get('cereri-15'), 2, true, '/game/?side='.$_GET['side'].'&view='.$soknad['id']);
					}
					elseif (count($members) >= $sizeType[1])
					{
						$db->Query("UPDATE `soknader` SET `handled`='0' WHERE `id`='".$soknad['id']."'");
						
						View::Message($langBase->get('cereri-16'), 2, true, '/game/?side='.$_GET['side'].'&view='.$soknad['id']);
					}
					else
					{
						$members[Player::Data('id')] = array(
							'player' => Player::Data('id'),
							'added' => time()
						);
						
						$db->Query("UPDATE `[families]` SET `members`='".serialize($members)."' WHERE `id`='".$family['id']."'");
						$db->Query("UPDATE `[players]` SET `family`='".$family['id']."' WHERE `id`='".Player::Data('id')."'");
						$db->Query("UPDATE `soknader` SET `confirmed`='2' WHERE `id`='".$soknad['id']."'");
						$db->Query("INSERT INTO `family_log` (`family`, `type`, `text`, `added`, `access_level`)VALUES('".$family['id']."', 'got_business', 'Familien har f&aring;tt et nytt medlem - ".View::Player(Player::$datavar, true)."!', '".time()."', '1')");
						
						Accessories::AddLogEvent(Player::Data('id'), 7, array(
							'-FAMILY_IMG-' => $family['image'],
							'-FAMILY_NAME-' => $family['name'],
							'-FAMILY_ID-' => $family['id']
 						), User::Data('id'));
						
						View::Message($langBase->get('cereri-17', array('-FAMILY-' => $family['name'])), 1, true, '/game/?side='.$_GET['side'].'&view='.$soknad['id']);
					}
				}
			}
			elseif (($soknad['handled'] == 2 && $soknad['confirmed'] == 0) && isset($_GET['deny']))
			{
				$db->Query("UPDATE `soknader` SET `confirmed`='1' WHERE `id`='".$soknad['id']."'");
				View::Message($langBase->get('cereri-18'), 1, true, '/game/?side='.$_GET['side'].'&view='.$soknad['id']);
			}
			elseif (isset($_GET['reset']))
			{
				$db->Query("UPDATE `soknader` SET `receiver`='0,0', `handled`='0', `confirmed`='0', `sent`='0' WHERE `id`='".$soknad['id']."'");
				View::Message($langBase->get('cereri-19'), 1, true, '/game/?side='.$_GET['side'].'&view='.$soknad['id']);
			}
			elseif (isset($_POST['save']) || isset($_POST['save_send']))
			{
				if ((isset($_POST['save']) || isset($_POST['save_send'])) && $soknad['sent'] == 0)
				{
					$r_1 = $db->EscapeString($_POST['edit_receiver_1']);
					$r_2 = $db->EscapeString($_POST['edit_receiver_2']);
					
					$r_type = $config['soknad_types'][$r_1];
					
					$valid_receiver = false;
					
					if ($r_type == 'firma')
					{
						$sql = $db->Query("SELECT id,name,accepts_soknader FROM `businesses` WHERE `id`='".$r_2."' AND `active`='1'");
						$firma = $db->FetchArray($sql);
						
						if ($firma['id'] == '')
						{
							echo View::Message('ERROR', 2);
						}
						elseif ($firma['accepts_soknader'] == 0 && isset($_POST['save_send']))
						{
							echo View::Message($langBase->get('cereri-20'), 2);
						}
						else
						{
							$valid_receiver = true;
						}
					}
					elseif ($r_type == 'familie')
					{
						$sql = $db->Query("SELECT id,name FROM `[families]` WHERE `id`='".$r_2."' AND `active`='1'");
						$family = $db->FetchArray($sql);
						
						if ($family['id'] == '')
						{
							echo View::Message('ERROR', 2);
						}
						else
						{
							$valid_receiver = false;
						}
					}
					
					$playerhistory = isset($_POST['edit_show_playerhistory']) ? 1 : 0;
					$text = $db->EscapeString($_POST['edit_text']);
					
					if (View::Length($text) < $config['soknad_text_min_length'])

					{
						echo View::Message($langBase->get('cereri-02', array('-NUM-' => $config['soknad_text_min_length'])), 2);
					}
					else
					{
						$sql = "UPDATE `soknader` SET `receiver`='".($valid_receiver === true ? $r_1.','.$r_2 : ($soknad['receiver'] == '0,0' ? '0,0' : $soknad['receiver']))."', `text`='".$text."', `show_playerhistory`='".$playerhistory."'";
						
						if (isset($_POST['save_send']))
						{
							if ($soknad['confirmed'] != 0 || $soknad['handled'] != 0)
							{
								echo View::Message($langBase->get('cereri-22'));
							}
							else
							{
								$sql .= $valid_receiver === true ? ", `sent`='".time()."'" : '';
								$is_sent = $valid_receiver === true ? true : false;
							}
						}
						
						$db->Query($sql . " WHERE `id`='".$soknad['id']."'");
						
						View::Message(($is_sent === true ? $langBase->get('cereri-25') : $langBase->get('cereri-26')).' '.(isset($_POST['save_send']) && (!$is_sent || !$valid_receiver) ? $langBase->get('cereri-27') : ''), 1, true, '/game/?side='.$_GET['side'].'&view='.$soknad['id']);
					}
				}
			}
			elseif (isset($_POST['undo']) && $soknad['sent'] != 0)
			{
				$db->Query("UPDATE `soknader` SET `sent`='0', `confirmed`='0', `handled`='0' WHERE `id`='".$soknad['id']."'");
				
				View::Message($langBase->get('cereri-23'), 1, true, '/game/?side='.$_GET['side'].'&view='.$soknad['id']);
			}
			elseif (isset($_POST['delete']))
			{
				$db->Query("UPDATE `soknader` SET `sent`='0', `confirmed`='0', `handled`='0', `deleted`='1' WHERE `id`='".$soknad['id']."'");
				
				View::Message($langBase->get('cereri-23'), 1, true, '/game/?side='.$_GET['side']);
			}
?>
<script type="text/javascript">
	<!--
	window.addEvent('domready', function()
	{
		var r_1 = document.getElement('select[name=edit_receiver_1]');
		var r_2 = document.getElement('select[name=edit_receiver_2]').addClass('hidden');
		
		r_1.addEvent('change', function()
		{
			if(this.selectedIndex == 0)
			{
				return;
			}
			
			if (this.xhr) this.xhr.cancel();
			
			this.xhr = new Request.JSON({ url: '/game/js/ajax/soknad_receivers.php', method: 'get', data: 'type=' + r_1.get('value') });
			this.xhr.addEvents(
			{
				success: function(data)
				{
					if ($chk(data.error))
					{
						alert('ERROR: ' + data.error);
					}
					else
					{
						r_2.empty();
						
						data.receivers.each(function(item)
						{
							new Element('option',
							{
								value: item.id,
								html: item.name
							}).inject(r_2);
						});
						
						r_2.removeClass('hidden');
					}
				},
				failure: function(data)
				{
					alert('ERROR: ' + data);
				}
			});
			this.xhr.send();
		});
	});
	-->
</script>
<div class="bg_c w550">
	<h1 class="big"><?=$langBase->get('cereri-24')?></h1>
    <div class="bg_c c_1 w300">
    	<h1 class="big"><?=$langBase->get('ot-status')?></h1>
        <dl class="dt_70">
        	<dt><?=$langBase->get('ot-status')?></dt>
            <dd><?=$treatment?></dd>
            <dt><?=$langBase->get('cereri-28')?>?</dt>
            <dd><?=($soknad['sent'] == 0 ? $langBase->get('ot-no') : $langBase->get('ot-yes').'<br />' . View::Time($soknad['sent'], true))?></dd>
            <dt><?=$langBase->get('txt-27')?></dt>
            <dd><?=View::Time($soknad['time_created'], true)?></dd>
            <dt><?=$langBase->get('txt-28')?></dt>
            <dd><?=$receiver_text?></dd>
        </dl>
        <p class="center clear">
        	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;view=<?=$soknad['id']?>&amp;reset"><?=$langBase->get('cereri-29')?></a>
        </p>
        <?php
		if ($soknad['handled'] == 2 && $soknad['confirmed'] == 0)
			echo View::Message($langBase->get('cereri-30', array('-CVID-' => $soknad['id'])), 1, false, '', '', 12);
		?>
    </div>
    <div class="bg_c c_1 w500">
    	<h1 class="big"><?=$langBase->get('cereri-24')?></h1>
        <form method="post" action="">
            <dl class="form">
                <dt><?=$langBase->get('txt-28')?></dt>
                <dd><select name="edit_receiver_1"><option><?=$langBase->get('cereri-32')?>...</option><?php foreach($config['soknad_types'] as $key => $type){ echo '<option value="'.$key.'">'.ucfirst($type).'</option>'; } ?></select><select name="edit_receiver_2" class="hidden" style="margin-left: 10px;"><option>N/A</option></select></dd>
                <dt><?=$langBase->get('cereri-33')?></dt>
                <dd><input type="checkbox" style="margin: 10px;" name="edit_show_playerhistory"<?php if($soknad['show_playerhistory'] == 1) echo ' checked="checked"'; ?> /></dd>
                <dt><?=$langBase->get('txt-39')?></dt>
                <dd><textarea name="edit_text" cols="60" rows="20"><?=(isset($_POST['edit_text']) ? $_POST['edit_text'] : $soknad['text'])?></textarea></dd>
                <dd>
                	<?php
					if ($soknad['sent'] == 0)
					{
					?>
                    <input type="submit" name="save" value="<?=$langBase->get('min-56')?>" />
                	<input type="submit" name="save_send" value="<?=$langBase->get('cereri-34')?>" style="margin-left: 5px;" />
                    <?php
					}
					else
					{
					?>
                    <input type="submit" name="undo" value="<?=$langBase->get('txt-10')?>" />
                    <?php
					}
					?>
                    <input type="submit" name="delete" value="<?=$langBase->get('txt-36')?>" onclick="return confirm('<?=$langBase->get('err-05')?>')" style="margin-left: 5px;" />
                </dd>
            </dl>
            <div class="clear"></div>
        </form>
    </div>
</div>
<?php
		}
	}
	else
	{
		$sql = "SELECT id,receiver,handled,sent,time_created,confirmed FROM `soknader` WHERE `from_user`='".User::Data('id')."' AND `deleted`='0' ORDER BY confirmed DESC, id DESC";
		$pagination = new Pagination($sql, 10, 'p');
		$pagination_links = $pagination->GetPageLinks();
		$soknader = $pagination->GetSQLRows();
		
		if (isset($_GET['delete']))
		{
			$soknad = $soknader[$_GET['delete']];
			
			if ($soknad['id'] != '')
			{
				$db->Query("UPDATE `soknader` SET `deleted`='1' WHERE `id`='".$soknad['id']."'");
				unset($soknader[$_GET['delete']]);
				
				View::Message('Successfully deleted!', 1, true, '/game/?side=' . $_GET['side']);
			}
		}
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('cereri-31')?></h1>
    <?php
	if (count($soknader) > 0)
	{
	?>
    <table class="table">
    	<thead>
        	<tr>
            	<td width="20%"><?=$langBase->get('txt-28')?></td>
                <td width="20%"><?=$langBase->get('ot-status')?></td>
                <td width="20%"><?=$langBase->get('txt-31')?></td>
                <td width="20%"><?=$langBase->get('txt-27')?></td>
                <td width="20%"></td>
            </tr>
        </thead>
        <tbody>
        <?php
		foreach ($soknader as $s_id => $soknad)
		{
			$i++;
			$c = $i%2 ? 1 : 2;
			
			$receiver = explode(',', $soknad['receiver']);
			$receiver_type = $config['soknad_types'][$receiver[0]];
			
			$receiver_text = $langBase->get('cereri-10');
			if ($receiver[0] == 1 && $receiver_type != '')
			{
				$sql = $db->Query("SELECT id,name,active,type FROM `businesses` WHERE `id`='".$receiver[1]."'");
				$firma = $db->FetchArray($sql);
				
				$receiver_text = $firma['active'] == 0 ? '&laquo;' . $firma['name'] . '&raquo;' : '&laquo;<a href="' . $config['base_url'] . '?side=firma/firma&amp;id=' . $firma['id'] . '">' . $firma['name'] . '</a>&raquo;<br /><span class="subtext">' . $config['business_types'][$firma['type']]['name'][2] . '</span>';
			}
			elseif ($receiver[0] == 2 && $receiver_type != '')
			{
				$sql = $db->Query("SELECT id,name,active FROM `[families]` WHERE `id`='".$receiver[1]."'");
				$family = $db->FetchArray($sql);
				
				$receiver_text = $family['active'] == 0 ? '&laquo;' . $family['name'] . '&raquo;' : '&laquo;<a href="' . $config['base_url'] . '?side=familie/familie&amp;id=' . $family['id'] . '">' . $family['name'] . '</a>&raquo;<br />Familie';
			}
			
			$treatment = $langBase->get('cereri-07');
			if ($soknad['handled'] == 1) {
				$treatment = $langBase->get('cereri-08');
			} elseif ($soknad['handled'] == 2) {
				$treatment = $langBase->get('cereri-09');
			}
		?>
        	<tr class="c_<?=$c?>">
            	<td width="20%"><?=$receiver_text?></td>
                <td width="20%" class="center"><?=$treatment?></td>
                <td width="20%" class="center"><?=($soknad['sent'] == 0 ? $langBase->get('cereri-36') : $langBase->get('txt-23').'<br /><span class="subtext">' . View::Time($soknad['sent']) . '</span>')?></td>
                <td width="20%" class="t_right"><?=View::Time($soknad['time_created'])?></td>
                <td width="20%" class="t_right"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;view=<?=$soknad['id']?>"><?=$langBase->get('cereri-35')?></a><br /><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;delete=<?=$s_id?>" onclick="return confirm('<?=$langBase->get('err-05')?>')"><?=$langBase->get('txt-36')?></a></td>
            </tr>
        <?php
		}
		?>
        	<tr class="c_3"><td colspan="5"><?=$pagination_links?></td></tr>
        </tbody>
    </table>
    <?php
	}else{ echo $langBase->get('err-06');}
	?>
</div>
<?php
	}
?>