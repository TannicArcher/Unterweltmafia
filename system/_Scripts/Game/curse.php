<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$sql = $db->Query("SELECT id,started,ends,bets FROM `car_races` WHERE `active`='1' ORDER BY started DESC LIMIT 1");
	$car_race = $db->FetchArray($sql);
	
	if ($car_race['id'] != '' && $admin_config['car_race_closed']['value'] == 'false')
	{
		if ($car_race['ends'] <= time())
		{
			if (count(unserialize($car_race['bets'])) > 0)
			{
				$db->Query("UPDATE `car_races` SET `active`='0' WHERE `id`='".$car_race['id']."'");
				$db->Query("INSERT HIGH_PRIORITY INTO `car_races` (`started`, `ends`)VALUES('".time()."', '".(time() + $config['car_races_timespan'])."')");
				
				$winner_driver = array_rand($config['car_race_drivers']);
				
				$total_bet = 0;
				$bets = unserialize($car_race['bets']);
				$winners = array();
				
				foreach ($bets as $key => $bet)
				{
					$total_bet += $bet['bet_money'];
					
					if ($bet['bet_driver'] == $winner_driver)
					{
						$winners[$key] = $bet;
					}
				}
				
				$winner_money = floor($total_bet / count($winners));
				foreach ($winners as $bet)
				{
					$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$winner_money."', `rankpoints`=`rankpoints`+'".$config['car_race_winner_rank']."' WHERE `id`='".$bet['player']."'");
					
					Accessories::AddLogEvent($bet['player'], 25, array(
						'-MONEY-' => View::CashFormat($winner_money)
					));
				}
				
				$db->Query("UPDATE `car_races` SET `winner_driver`='".$winner_driver."', `winners`='".serialize($winners)."' WHERE `id`='".$car_race['id']."'");
			}
			else
			{
				$db->Query("UPDATE `car_races` SET `ends`='".(time() + $config['car_races_timespan'])."' WHERE `id`='".$car_race['id']."'");
			}
			
			header('Location: /game/?side=' . $_GET['side']);
			exit;
		}
	}
	
	$bets = unserialize($car_race['bets']);
	
	if (isset($_POST['bet_cash']) && $car_race['id'] != '' && !$bets[Player::Data('id')] && $admin_config['car_race_closed']['value'] == 'false')
	{
		$cash = View::NumbersOnly($db->EscapeString($_POST['bet_cash']));
		$driverID = $db->EscapeString($_POST['bet_driver']);
		$driver = $config['car_race_drivers'][$driverID];
		
		if (!$driver)
		{
			echo View::Message($langBase->get('curse-01'), 2);
		}
		elseif ($cash < $config['car_race_min_bet'])
		{
			echo View::Message($langBase->get('curse-02', array('-CASH-' => View::CashFormat($config['car_race_min_bet']))), 2);
		}
		elseif ($cash > Player::Data('cash'))
		{
			echo View::Message($langBase->get('err-01'), 2);
		}
		else
		{
			$bets[Player::Data('id')] = array(
				'player' => Player::Data('id'),
				'bet_money' => $cash,
				'bet_driver' => $driverID
			);
			
			$db->Query("UPDATE `car_races` SET `bets`='".serialize($bets)."' WHERE `id`='".$car_race['id']."'");
			$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$cash."' WHERE `id`='".Player::Data('id')."'");
			
			$abData = unserialize(Player::Data('antibot_data'));
			$abData['car_race']--;
			if ($abData['car_race'] <= 0)
			{
				$abData['car_race'] = rand($config['antibot_next_range']['car_race'][0], $config['antibot_next_range']['car_race'][1]);
				$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
				
				Accessories::CreateAntibotSession(Player::Data('id'), $db->EscapeString($_GET['side']));
				
				View::Message($langBase->get('curse-03', array('-CASH-' => View::CashFormat($cash), '-DRIVER-' => $driver)), 1, true);
			}
			
			$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
			
			View::Message($langBase->get('curse-03', array('-CASH-' => View::CashFormat($cash), '-DRIVER-' => $driver)), 1, true);
		}
	}
?>
<div class="script_header"><img src="<?=$config['base_url']?>images/script_headers/gatelop.jpg" alt="" /></div>
<?php
	show_messages();
	
	$total_bet = 0;
	$theBets = array();
	foreach ($bets as $bet)
	{
		$total_bet += $bet['bet_money'];
		$theBets[$bet['bet_driver']][] = $bet;
	}
	$winner_cash = floor($total_bet / count($bets));
?>
<div style="width: 540px; margin: 0px auto;">
    <div class="left" style="width: 250px;">
        <div class="bg_c" style="width: 230px;">
            <h1 class="big"><?=$langBase->get('curse-04')?></h1>
            <?php
			if ($admin_config['car_race_closed']['value'] == 'true')
			{
				echo '<p class="red">'.$langBase->get('curse-05').'</p>';
			}
			elseif ($car_race['id'] == '')
			{
				echo '<p>'.$langBase->get('curse-06').'</p>';
			}
			else
			{
				if ($bets[Player::Data('id')])
				{
					echo '<p>'.$langBase->get('curse-07').'</p>';
				}
				else
				{
			?>
            <p><?=$langBase->get('curse-08')?></p>
            <p style="margin-top: 10px; margin-bottom: 5px;"><?=$langBase->get('curse-09')?></p>
            <form method="post" action="">
                <dl class="dd_right">
                    <dt><?=$langBase->get('txt-25')?> (<a href="#" onclick="$$('input[name=bet_cash]').set('value', '<?=View::CashFormat(Player::Data('cash'))?> $'); return false;"><?=$langBase->get('txt-26')?></a>)</dt>
                    <dd><input type="text" class="flat" name="bet_cash" style="width: 110px; min-width: 110px;" value="<?=View::CashFormat($_POST['bet_cash'])?> $" /></dd>
                    <dt><?=$langBase->get('curse-10')?></dt>
                    <dd style="width: 150px;">
                        <table class="table boxHandle center">
                            <thead>
                                <tr class="small">
                                    <td><?=$langBase->get('txt-02')?></td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($config['car_race_drivers'] as $key => $driver)
                            {
                                $i++;
                                $c = $i%2 ? 1 : 2;
                            ?>
                                <tr class="c_<?=$c?> boxHandle">
                                    <td><input type="radio" name="bet_driver" value="<?=$key?>"<?php if(isset($_POST['bet_driver']) && $_POST['bet_driver'] == $key) echo ' checked="checked"';?> /><?=$driver?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </dd>
                </dl>
                <div class="clear"></div>
                <p class="center" style="margin: 10px;">
                    <a href="#" class="button form_submit"><?=$langBase->get('curse-11')?></a>
                </p>
            </form>
			<?php
            	}
            ?>
            <div class="hr big" style="margin: 15px 0 10px 0;"></div>
            <p class="yellow bold"><?=$langBase->get('curse-12')?></p>
            <p><?=$langBase->get('curse-13', array('-TIME-' => View::strTime($car_race['ends'] - time())))?></p>
            <dl class="dd_right" style="margin-top: 2px;">
            	<dt><?=$langBase->get('curse-14')?></dt>
                <dd><?=View::CashFormat(count($bets))?></dd>
                <dt class="bold"><?=$langBase->get('curse-15')?></dt>
                <dd><?=View::CashFormat($total_bet)?> $</dd>
            </dl>
            <div class="clear"></div>
            <?php if ($bets[Player::Data('id')]):?><p style="margin-top: 10px;"><?=$langBase->get('curse-16', array('-CASH-' => View::CashFormat(floor($total_bet / count($theBets[$bets[Player::Data('id')]['bet_driver']]))), '-PROGRESS-' => View::AsPercent($config['car_race_winner_rank'], $config['ranks'][Player::Data('rank')][2]-$config['ranks'][Player::Data('rank')][1], 4)))?></p><?php endif;?>
            <?php
			}
			?>
        </div>
    </div>
    <div class="left" style="width: 250px; margin-left: 30px;">
        <div class="bg_c" style="width: 230px;">
            <h1 class="big"><?=$langBase->get('curse-17')?></h1>
            <?php
			$sql = "SELECT ends,bets,winners FROM `car_races` WHERE `active`='0' ORDER BY id DESC";
			$pagination = new Pagination($sql, 5, 'p');
			$races = $pagination->GetSQLRows();
			
			if (count($races) <= 0)
			{
				echo '<p>'.$langBase->get('err-06').'</p>';
			}
			else
			{
				foreach ($races as $race)
				{
					$bets = unserialize($race['bets']);
					$winners = unserialize($race['winners']);
					
					$theTotalBet = 0;
					foreach ($bets as $bet)
					{
						$theTotalBet += $bet['bet_money'];
					}
				?>
                <div class="bg_c c_1 w200">
                    <h1><?=View::Time($race['ends'], false, 'H:i')?><span class="right"><?=View::CashFormat(count($bets))?> <?=$langBase->get('curse-20')?></span></h1>
                    <?php
					if (count($winners) <= 0)
					{
						echo '<p>'.$langBase->get('curse-18').'</p>';
					}
					else
					{
						echo '<p>'.$langBase->get('curse-19').'</p><ul>';
						
						foreach ($winners as $winner)
						{
							echo '<li>' . View::Player(array('id' => $winner['player'])) . ' - ' . (View::CashFormat(floor($theTotalBet / count($winners)))) . ' $</li>';
						}
						
						echo '</ul>';
					}
					?>
                </div>
                <?php
				}
			?>
            <div style="margin: 10px 0 10px 0;"><?=$pagination->GetPageLinks()?></div>
            <?php
			}
			?>
        </div>
    </div>
</div>
<div class="clear"></div>