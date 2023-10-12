<div class="script_header">
	<img src="<?=$config['base_url']?>images/script_headers/kaempfe.jpg" alt="" />
<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$my_fighter = $db->QueryFetchArray("SELECT * FROM `fighting` WHERE `player`='".Player::Data('id')."'");
	
	if ($my_fighter['id'] == '')
	{
		$db->Query("INSERT INTO `fighting` (`player`)VALUES('".Player::Data('id')."')");
		
		header('Location: /game/?side=' . $_GET['side']);
		exit;
	}

	if ($my_fighter['level_progress'] >= $config['training_max_progress'])
	{
		$db->Query("UPDATE `fighting` SET `level`=`level`+'1', `level_progress`='".($my_fighter['level_progress']-$config['training_max_progress'])."' WHERE `id`='".$my_fighter['id']."'");
		
		$my_fighter['level']++;
		$my_fighter['level_progress'] -= $config['training_max_progress'];
	}
	
	$stats = unserialize($my_fighter['stats']);
	
	$my_houses = unserialize(Player::Data('houses'));
	$my_house = $my_houses[Player::Data('live')];
	
	$studio = 'public';
	if ($config['houses'][$my_house['type']]['training'] === true)
	{
		$studio = 'private';
	}
	$studio_data = $config['training_studios'][$studio];
	
	$training_wait = $my_fighter['last_training']+$my_fighter['training_wait'] - time();
	$fight_wait = $my_fighter['last_fight']+$config['fighting_wait_time'] - time();
	
	if (isset($_POST['do_training']) && $training_wait <= 0)
	{
		$method_key = $db->EscapeString($_POST['do_training']);
		$method = $studio_data['training_methods'][$method_key];
		
		if (!$method)
		{
			echo View::Message('ERROR', 2);
		}
		else
		{
			$db->Query("UPDATE `fighting` SET `level_progress`=`level_progress`+'".$method['points']."', `last_training`='".time()."', `training_wait`='".$method['wait']."' WHERE `id`='".$my_fighter['id']."'");
			$_SESSION['MZ_Training_Last'] = $method_key;
			
			$abData = unserialize(Player::Data('antibot_data'));
			$abData['fighting_training']--;
			if ($abData['fighting_training'] <= 0)
			{
				$abData['fighting_training'] = rand($config['antibot_next_range']['fighting_training'][0], $config['antibot_next_range']['fighting_training'][1]);
				$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
				
				Accessories::CreateAntibotSession(Player::Data('id'), $db->EscapeString($_GET['side']));
				
				View::Message($langBase->get('lupta-01'), 1, true);
			}
			$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
			
			View::Message($langBase->get('lupta-01'), 1, true);
		}
	}
	elseif (isset($_GET['cm']) && $my_fighter['fight_bet'] > 0)
	{
		$db->Query("UPDATE `fighting` SET `fight_bet`='0' WHERE `id`='".$my_fighter['id']."'");
		$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$my_fighter['fight_bet']."' WHERE `id`='".Player::Data('id')."'");
		
		View::Message($langBase->get('lupta-02'), 1, true, '/game/?side=' . $_GET['side']);
	}
	elseif (isset($_POST['start_fight']) && $my_fighter['fight_bet'] <= 0 && $fight_wait <= 0)
	{
		$bet = View::NumbersOnly($db->EscapeString($_POST['start_fight']));
		
		if ($bet > Player::Data('cash'))
		{
			echo View::Message($langBase->get('err-01'), 2);
		}
		elseif ($bet < 1000)
		{
			echo View::Message($langBase->get('lupta-03'), 2);
		}
		else
		{
			$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$bet."' WHERE `id`='".Player::Data('id')."'");
			$db->Query("UPDATE `fighting` SET `fight_bet`='".$bet."' WHERE `id`='".$my_fighter['id']."'");
			
			$abData = unserialize(Player::Data('antibot_data'));
			$abData['fighting']--;
			if ($abData['fighting'] <= 0)
			{
				$abData['fighting'] = rand($config['antibot_next_range']['fighting'][0], $config['antibot_next_range']['fighting'][1]);
				$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
				
				Accessories::CreateAntibotSession(Player::Data('id'), $db->EscapeString($_GET['side']));
				
				View::Message($langBase->get('lupta-04'), 1, true);
			}
			$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
			
			View::Message($langBase->get('lupta-04'), 1, true);
		}
	}
	elseif (isset($_POST['join_fight']) && $fight_wait <= 0)
	{
		$fight = $db->EscapeString($_POST['join_fight']);
		$fight = $db->QueryFetchArray("SELECT id,player,fight_bet,level,stats FROM `fighting` WHERE `id`='".$fight."' AND `fight_bet`>'0'");
		
		if ($fight['id'] == '')
		{
			echo View::Message($langBase->get('lupta-07'), 2);
		}
		elseif ($fight['player'] == Player::Data('id'))
		{
			echo View::Message($langBase->get('lupta-05'), 2);
		}
		elseif ($fight['fight_bet'] > Player::Data('cash'))
		{
			echo View::Message($langBase->get('err-01'), 2);
		}
		else
		{
			$db->Query("UPDATE `fighting` SET `fight_bet`='0' WHERE `player`='".$fight['player']."'");
			$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$fight['fight_bet']."' WHERE `id`='".Player::Data('id')."'");
			
			if ($my_fighter['level'] == $fight['level'])
			{
				$winner = rand(0, 100) <= 50 ? $my_fighter : $fight;
			}
			else
			{
				$winner = $my_fighter['level'] > $fight['level'] ? $my_fighter : $fight;
			}
			$loser = $winner['id'] == $my_fighter['id'] ? $fight : $my_fighter;
			
			$stats = unserialize($winner['stats']);
			$stats['won']++;
			$stats['won_money'] += $fight['fight_bet'];
			
			$db->Query("UPDATE `fighting` SET `stats`='".serialize($stats)."', `last_fight`='".time()."' WHERE `id`='".$winner['id']."'");
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".($fight['fight_bet']*2)."', `rankpoints`=`rankpoints`+'".rand(6, 10)."' WHERE `id`='".$winner['player']."'");
			
			$stats = unserialize($loser['stats']);
			$stats['lost']++;
			$stats['lost_money'] += $fight['fight_bet'];
			
			$db->Query("UPDATE `fighting` SET `stats`='".serialize($stats)."', `last_fight`='".time()."' WHERE `id`='".$loser['id']."'");
			
			
			$loser_p = $db->QueryFetchArray("SELECT id,userid,name FROM `[players]` WHERE `id`='".$loser['player']."'");
			$winner_p = $db->QueryFetchArray("SELECT id,userid,name FROM `[players]` WHERE `id`='".$winner['player']."'");
			
			Accessories::AddLogEvent($loser_p['id'], 40, array(
				'-MONEY-' => View::CashFormat($fight['fight_bet']),
				'-PLAYER_NAME-' => $winner_p['name']
			), $loser_p['userid']);
			
			Accessories::AddLogEvent($winner_p['id'], 41, array(
				'-MONEY-' => View::CashFormat($fight['fight_bet']),
				'-PLAYER_NAME-' => $loser_p['name']
			), $winner_p['userid']);
			
			
			if ($winner['id'] == $my_fighter['id'])
			{
				$msg = $langBase->get('lupta-06', array('-PLAYER-' => View::Player(array('id' => $loser['player'])), '-CASH-' => View::CashFormat($fight['fight_bet'])));
				$msgType = 1;
			}
			else
			{
				$msg = 'Esec! ' . View::Player(array('id' => $winner['player'])) . ' te-a batut mar!';
				$msgType = 2;
			}
			
			$abData = unserialize(Player::Data('antibot_data'));
			$abData['fighting']--;
			if ($abData['fighting'] <= 0)
			{
				$abData['fighting'] = rand($config['antibot_next_range']['fighting'][0], $config['antibot_next_range']['fighting'][1]);
				$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
				
				Accessories::CreateAntibotSession(Player::Data('id'), $db->EscapeString($_GET['side']));
				
				View::Message($msg, $msgType, true);
			}
			$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
			
			View::Message($msg, $msgType, true);
		}
	}
?>
<div class="left" style="width: 300px; margin-left: 10px;">
	<div class="bg_c" style="width: 280px;">
    	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
        <dl class="dd_right">
        	<dt><?=$langBase->get('lupta-08')?></dt>
            <dd><?=View::CashFormat($my_fighter['level'])?></dd>
            <dt><?=$langBase->get('lupta-09')?></dt>
            <dd><?=View::AsPercent($my_fighter['level_progress'], $config['training_max_progress'], 2)?> %</dd>
            <dt><?=$langBase->get('lupta-10')?></dt>
            <dd><?=View::CashFormat($stats['won'])?></dd>
            <dt><?=$langBase->get('lupta-11')?></dt>
            <dd><?=View::CashFormat($stats['lost'])?></dd>
        </dl>
        <div class="clear"></div>
        <div style="padding-top: 10px;"></div>
        <div class="hr big" style="margin: 0;"></div>
        <div style="padding-bottom: 10px;"></div>
        <h2 style="margin: 0; padding: 5px; padding-bottom: 0;"><?=$langBase->get('lupta-12')?></h2>
        <form method="post" action="">
            <dl class="dd_right">
            	<dt><?=$langBase->get('lupta-13')?></dt>
                <dd><?=$studio_data['title']?></dd>
                <dd>
                	<?php
					if (count($studio_data['training_methods']) <= 0)
					{
						echo $langBase->get('err-06');
					}
					else
					{
					?>
					<table class="table boxHandle" style="width: 235px; margin: 3px 0 0 0;">
                    	<thead>
                        	<tr class="small">
                            	<td><?=$langBase->get('lupta-12')?></td>
                                <td><?=$langBase->get('lupta-09')?></td>
                                <td><?=$langBase->get('lupta-14')?></td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
						foreach ($studio_data['training_methods'] as $method_key => $method)
						{
							$i++;
							$c = $i%2 ? 1 : 2;
						?>
                        	<tr class="c_<?=$c?> boxHandle">
                            	<td><input type="radio" name="do_training" value="<?=$method_key?>"<?php if ($_SESSION['MZ_Training_Last'] == $method_key) echo ' checked="checked"';?> /><?=$method['title']?></td>
                                <td class="center"><?=View::AsPercent($method['points'], $config['training_max_progress'], 2)?> %</td>
                                <td class="t_right"><?=View::strTime($method['wait'])?></td>
                            </tr>
                        <?php
						}
						?>
                        </tbody>
                    </table>
					<?php
					}
					?>
                </dd>
            </dl>
            <p class="clear center">
            	<?=($training_wait > 0 ? $langBase->get('armament-30', array('-TIME-' => $training_wait)) : '<input type="submit" value="'.$langBase->get('lupta-15').'" />')?>
            </p>
            <div class="clear"></div>
        </form>
        <div style="padding-top: 10px;"></div>
        <div class="hr big" style="margin: 0;"></div>
        <div style="padding-bottom: 10px;"></div>
        <h2 style="margin: 0; padding: 5px; padding-bottom: 0;"><?=$langBase->get('lupta-16')?></h2>
        <?php
		if ($my_fighter['fight_bet'] > 0)
		{
			echo '<p>'.$langBase->get('lupta-19').' <a href="' . $config['base_url'] . '?side=' . $_GET['side'] . '&amp;cm">'.$langBase->get('txt-10').'</a></p>';
		}
		elseif ($fight_wait > 0)
		{
			echo $langBase->get('armament-30', array('-TIME-' => $fight_wait));
		}
		else
		{
		?>
        <form method="post" action="">
        	<dl class="dd_right">
            	<dt><?=$langBase->get('txt-25')?></dt>
                <dd><input type="text" name="start_fight" class="flat" value="<?=View::CashFormat(View::NumbersOnly($_POST['start_fight']))?> $" /></dd>
            </dl>
            <p class="clear center">
            	<input type="submit" value="<?=$langBase->get('txt-14')?>" />
            </p>
        </form>
        <?php
		}
		?>
    </div>
</div>
<div class="left" style="width: 300px; margin-left: 20px;">
	<div class="bg_c" style="width: 280px;">
    	<h1 class="big"><?=$langBase->get('lupta-17')?></h1>
        <?php
		$sql = "SELECT id,player,fight_bet FROM `fighting` WHERE `fight_bet`>'0' ORDER BY fight_bet DESC";
		$pagination = new Pagination($sql, 10, 'p');
		$fights = $pagination->GetSQLRows();
		
		if (count($fights) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
		?>
        <form method="post" action="">
        	<table class="table boxHandle">
            	<thead>
                	<tr>
                    	<td><?=$langBase->get('txt-06')?></td>
                        <td><?=$langBase->get('txt-25')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
				unset($i);
				foreach ($fights as $fight)
				{
					$i++;
					$c = $i%2 ? 1 : 2;
				?>
                	<tr class="c_<?=$c?> boxHandle">
                    	<td><input type="radio" name="join_fight" value="<?=$fight['id']?>" /><?=View::Player(array('id' => $fight['player']))?></td>
                        <td class="t_right"><?=View::CashFormat($fight['fight_bet'])?> $</td>
                    </tr>
                <?php
				}
				?>
                	<tr class="c_3 center">
                    	<td colspan="2"><?=$pagination->GetPageLinks()?></td>
                    </tr>
                </tbody>
            </table>
            <?=($fight_wait > 0 ? $langBase->get('armament-30', array('-TIME-' => $fight_wait)) : '<p class="center"><input type="submit" value="'.$langBase->get('lupta-18').'" /></p>')?>
        </form>
        <?php
		}
		?>
    </div>
</div>
<div class="clear"></div>
</div>