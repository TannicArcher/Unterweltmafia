<div class="script_header">
	<img src="<?=$config['base_url']?>images/script_headers/bunker.jpg" alt="" />
<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$sql = $db->Query("SELECT * FROM `bunker` WHERE `player`='".Player::Data('id')."'");
	$bunker = $db->FetchArray($sql);
	
	if ($bunker['id'] == '')
	{
		$db->Query("INSERT INTO `bunker`
					(`player`, `sessions`)
					VALUES
					('".Player::Data('id')."', '".serialize(array())."')");
		
		header('Location: /game/?side=' . $_GET['side']);
		exit;
	}
	
	$timeleft = $bunker['last_session_ends'] - time();
	
	$access = unserialize($bunker['access']);
	$bunkers = unserialize($bunker['bunkers']);
	
	if (isset($_POST['buy_bunker']) && !$bunkers[Player::Data('live')])
	{
		if ($config['bunker_price'] > Player::Data('cash'))
		{
			echo View::Message($langBase->get('err-01'), 2);
		}
		else
		{
			$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$config['bunker_price']."' WHERE `id`='".Player::Data('id')."'");
			
			$bunkers[Player::Data('live')] = array(
				'place' => Player::Data('live'),
				'time' => time()
			);
			$db->Query("UPDATE `bunker` SET `bunkers`='".serialize($bunkers)."' WHERE `id`='".$bunker['id']."'");
			
			View::Message($langBase->get('buncar-01', array('-CITY-' => $config['places'][Player::Data('live')][0])), 1, true);
		}
	}
	
	$hasAccess = array();
	$hasAccess[] = array(
		'player' => Player::Data('id'),
		'bunker' => $bunker,
		'place' => Player::Data('live')
	);
	
	foreach ($access['granted'] as $acc)
	{
		$sql = $db->Query("SELECT id,last_session_ends FROM `bunker` WHERE `player`='".$acc['player']."'");
		$b = $db->FetchArray($sql);
		
		$sql = $db->Query("SELECT id,live FROM `[players]` WHERE `id`='".$acc['player']."'");
		$player = $db->FetchArray($sql);
		
		$hasAccess[] = array(
			'player' => $player['id'],
			'bunker' => $b,
			'place' => $player['live']
		);
	}
	
	if (isset($_POST['setInBunker_player']))
	{
		$acc = $db->EscapeString($_POST['setInBunker_player']);
		$acc = $hasAccess[$acc];
		
		$length = View::NumbersOnly($db->EscapeString($_POST['setInBunker_length']));
		
		if (!$acc)
		{
			echo View::Message($langBase->get('err-02'), 2);
		}
		elseif ($db->GetNumRows($db->Query("SELECT id FROM `jail` WHERE `player`='".$acc['player']."' AND `active`='1'")) > 0)
		{
			echo View::Message($langBase->get('err-07'), 2);
		}
		elseif ($acc['bunker']['last_session_ends'] > time())
		{
			echo View::Message($langBase->get('buncar-02'), 2);
		}
		elseif (!$bunkers[$acc['place']])
		{
			echo View::Message($langBase->get('buncar-03', array('-CITY-' => $config['places'][$acc['place']][0])), 2);
		}
		elseif ($length <= 0)
		{
			echo View::Message($langBase->get('buncar-04'), 2);
		}
		elseif ($length > $config['bunker_max_length'])
		{
			echo View::Message($langBase->get('buncar-05', array('-TIME-' => $config['bunker_max_length'])), 2);
		}
		else
		{
			$db->Query("UPDATE `bunker` SET `last_session_start`='".time()."', `last_session_ends`='".(time()+($length*60))."' WHERE `id`='".$acc['bunker']['id']."'");
			
			View::Message(View::Player(array('id' => $acc['player']), true) . ' '.$langBase->get('buncar-07'), 1, true);
		}
	}
	elseif (isset($_GET['r_a']))
	{
		$acc_id = $db->EscapeString($_GET['r_a']);
		$acc = $access['given'][$acc_id];
		
		if ($acc)
		{
			unset($access['given'][$acc_id]);
			$db->Query("UPDATE `bunker` SET `access`='".serialize($access)."' WHERE `id`='".$bunker['id']."'");
			
			$sql = $db->Query("SELECT id,access FROM `bunker` WHERE `player`='".$acc['player']."'");
			$granted = $db->FetchArray($sql);
			$g_access = unserialize($granted['access']);
			unset($g_access['granted'][Player::Data('id')]);
			$db->Query("UPDATE `bunker` SET `access`='".serialize($g_access)."' WHERE `id`='".$granted['id']."'");
			
			View::Message($langBase->get('buncar-06'), 1, true, '/game/?side=' . $_GET['side']);
		}
		else
		{
			View::Message($langBase->get('err-02'), 2, true, '/game/?side=' . $_GET['side']);
		}
	}
	elseif (isset($_GET['r_r']))
	{
		$acc_id = $db->EscapeString($_GET['r_r']);
		$acc = $access['granted'][$acc_id];
		
		if ($acc)
		{
			unset($access['granted'][$acc_id]);
			$db->Query("UPDATE `bunker` SET `access`='".serialize($access)."' WHERE `id`='".$bunker['id']."'");
			
			$sql = $db->Query("SELECT id,access FROM `bunker` WHERE `player`='".$acc['player']."'");
			$granted = $db->FetchArray($sql);
			$g_access = unserialize($granted['access']);
			unset($g_access['given'][Player::Data('id')]);
			$db->Query("UPDATE `bunker` SET `access`='".serialize($g_access)."' WHERE `id`='".$granted['id']."'");
			
			View::Message($langBase->get('buncar-06'), 1, true, '/game/?side=' . $_GET['side']);
		}
		else
		{
			View::Message($langBase->get('err-02'), 2, true, '/game/?side=' . $_GET['side']);
		}
	}
	elseif (isset($_POST['give_access']))
	{
		$player = $db->EscapeString(trim($_POST['give_access']));
		$sql = $db->Query("SELECT id,name,level,health FROM `[players]` WHERE `name`='".$player."'");
		$player = $db->FetchArray($sql);
		
		if ($player['id'] == '')
		{
			echo View::Message($langBase->get('err-02'), 2);
		}
		elseif ($player['level'] <= 0 || $player['health'] <= 0)
		{
			echo View::Message($langBase->get('buncar-08'), 2);
		}
		elseif ($access['given'][$player['id']])
		{
			echo View::Message($langBase->get('buncar-09'), 2);
		}
		else
		{
			$access['given'][$player['id']] = array(
				'player' => $player['id'],
				'time' => time()
			);
			$db->Query("UPDATE `bunker` SET `access`='".serialize($access)."' WHERE `id`='".$bunker['id']."'");
			
			$sql = $db->Query("SELECT id,access FROM `bunker` WHERE `player`='".$player['id']."'");
			$granted = $db->FetchArray($sql);
			$g_access = unserialize($granted['access']);
			$g_access['granted'][Player::Data('id')] = array(
				'player' => Player::Data('id'),
				'time' => time()
			);
			$db->Query("UPDATE `bunker` SET `access`='".serialize($g_access)."' WHERE `id`='".$granted['id']."'");
			
			View::Message($langBase->get('buncar-10', array('-PLAYER-' => View::Player($player, true))), 1, true);
		}
	}
	
	if ($timeleft > 0)
	{
		if (isset($_POST['leave_bunker']))
		{
			$db->Query("UPDATE `bunker` SET `last_session_ends`='0' WHERE `id`='".$bunker['id']."'");
			
			View::Message($langBase->get('buncar-11'), 1, true);
		}
		
		echo '
			<h2 class="center">'.$langBase->get('buncar-13', array('-TIME-' => $timeleft)).'</h2>
			<form method="post" action="">
				<input type="hidden" name="leave_bunker" />
				<p class="center">
					<a href="#" class="button form_submit">&laquo; '.$langBase->get('buncar-12').'</a>
				</p>
			</form>
			';
	}
?>
<div style="margin: 0px auto; width: 620px;">
	<div class="left" style="width: 280px;">
    	<div class="bg_c" style="width: 260px;">
        	<h1 class="big"><?=$langBase->get('buncar-00')?> (<?=$config['places'][Player::Data('live')][0]?>)</h1>
            <?php
			if (!$bunkers[Player::Data('live')])
			{
				echo '<p>Du hast keinen Bunker in ' . $config['places'][Player::Data('live')][0] . '.</p>';
			?>
            <div class="hr big" style="margin: 10px 0 10px 0;"></div>
            <p><?=$langBase->get('txt-03')?>: <b><?=View::CashFormat($config['bunker_price'])?> $</b></p>
            <form method="post" action="">
            	<p class="center">
                	<input type="submit" name="buy_bunker" value="<?=$langBase->get('txt-01')?>" />
                </p>
            </form>
            <?php
			}
			else
			{
				echo '<p>'.$langBase->get('buncar-14', array('-CITY-' => $config['places'][Player::Data('live')][0], '-DATE-' => View::Time($bunkers[Player::Data('live')]['time']))).'</p>';
			}
			?>
        </div>
    </div>
    <div class="left" style="width: 320px; margin-left: 20px;">
    	<div class="bg_c" style="width: 300px;">
        	<h1 class="big"><?=$langBase->get('buncar-15')?></h1>
            <form method="post" action="">
            	<table class="table boxHandle">
                	<thead>
                    	<tr class="small">
                            <td><?=$langBase->get('txt-06')?></td>
                            <td><?=$langBase->get('txt-05')?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
					foreach ($hasAccess as $key => $acc)
					{
						$i++;
						$c = $i%2 ? 1 : 2;
					?>
                    	<tr class="c_<?=$c?> boxHandle">
                        	<td><input type="radio" name="setInBunker_player" value="<?=$key?>"<?=(isset($_POST['setInBunker_player']) && $_POST['setInBunker_player'] == $key ? ' checked="checked"' : ($acc['player'] == Player::Data('id') ? ' checked="checked"' : ''))?> /><?=View::Player(array('id' => $acc['player']))?></td>
                            <td class="center"><?=$config['places'][$acc['place']][0]?></td>
                        </tr>
                    <?php
					}
					?>
                    </tbody>
                </table>
                <dl class="dd_right">
                	<dt><?=$langBase->get('txt-35')?></dt>
                    <dd><input type="text" name="setInBunker_length" class="flat" value="<?=View::CashFormat(View::NumbersOnly($_POST['setInBunker_length']))?> <?=$langBase->get('txt-08')?>" /></dd>
                </dl>
                <p class="center clear">
                	<input type="submit" value="<?=$langBase->get('txt-14')?>" />
                </p>
            </form>
        </div>
    </div>
    <div class="clear"></div>
</div>
<div class="bg_c w400" style="margin-top: 0;">
	<h1 class="big"><?=$langBase->get('buncar-16')?></h1>
    <div class="left t_justify" style="width: 190px; padding-right: 10px;  border-right: dashed 2px #191919;">
	<?php
    if (count($access['given']) <= 0)
	{
		echo '<p>'.$langBase->get('buncar-20').'</p>';
	}
	else
	{
	?>
    <p class="bold"><?=$langBase->get('txt-31')?></p>
    <dl class="dd_right">
   	<?php
	foreach ($access['given'] as $key => $value)
	{
		echo '
			<dt>' . View::Player(array('id' => $value['player']), true) . '</dt>
			<dd><a href="' . $config['base_url'] . '?side=' . $_GET['side'] . '&amp;r_a=' . $key . '">'.$langBase->get('txt-36').'</a></dd>
			';
	}
	?>
    </dl>
    <div class="clear"></div>
    <?php
	}
    ?>
    <div class="hr big" style="margin: 10px 0 10px 0;"></div>
    <p class="bold"><?=$langBase->get('buncar-18')?></p>
    <form method="post" action="">
    	<dl class="dd_right">
        	<dt><?=$langBase->get('txt-06')?></dt>
            <dd><input type="text" name="give_access" class="flat" value="<?=$_POST['give_access']?>" /></dd>
        </dl>
        <p class="center clear">
        	<input type="submit" value="<?=$langBase->get('buncar-19')?>" />
        </p>
    </form>
    </div>
    <div class="left t_justify" style="width: 180px; padding-left: 10px;">
    <?php
	if (count($access['granted']) <= 0)
	{
		echo '<p>'.$langBase->get('buncar-17').'</p>';
	}
	else
	{
	?>
    <p class="bold"><?=$langBase->get('buncar-21')?></p>
    <dl class="dd_right">
    <?php
	foreach ($access['granted'] as $key => $value)
	{
		echo '
			<dt>' . View::Player(array('id' => $value['player']), true) . '</dt>
			<dd><a href="' . $config['base_url'] . '?side=' . $_GET['side'] . '&amp;r_r=' . $key . '">'.$langBase->get('txt-36').'</a></dd>
			';
	}
	?>
    </dl>
    <?php
	}
	?>
    </div>
    <div class="clear"></div>
</div>
</div>