<div class="script_header">
	<img src="<?=$config['base_url']?>images/script_headers/lotto.jpg" alt="" />
<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	function removeTickets($tickets, $player)
	{
		foreach ($tickets as $key => $ticket)
		{
			if ($ticket == $player)
			{
				unset($tickets[$key]);
			}
		}
		
		return $tickets;
	}
	
	$sql = $db->Query("SELECT id,started,ends,tickets,buy_timestamps FROM `lottery` WHERE `active`='1' ORDER BY started DESC LIMIT 1");
	$lottery = $db->FetchArray($sql);
	
	if ($lottery['id'] != '' && $admin_config['lottery_closed']['value'] == 'false')
	{
		if ($lottery['ends'] <= time())
		{
			if (count(explode(',', $lottery['tickets'])) > 0)
			{
				$db->Query("UPDATE `lottery` SET `active`='0' WHERE `id`='".$lottery['id']."'");
				$db->Query("INSERT HIGH_PRIORITY INTO `lottery` (`started`, `ends`, `winners`, `buy_timestamps`)VALUES('".time()."', '".(time() + $config['lottery_timespan'])."', 'a:0:{}', 'a:0:{}')");
				
				$tickets = explode(',', $lottery['tickets']);
				
				$winners = array();
				
				$total_bet = round(count($tickets) * $config['lottery_ticket_price'], 0);
				
				foreach ($config['lottery_winnerplaces'] as $key => $value)
				{
					$winnerID = array_rand($tickets);
					$player = $tickets[$winnerID];
					
					if (!empty($player))
					{
						$tickets = removeTickets($tickets, $player);
						$money = floor($total_bet/100 * $value['money_percent']);
						$winners[$key] = array(
							'player' => $player,
							'money' => $money
						);
						
						$player = $db->EscapeString($player);
						$sql = $db->Query("SELECT id,userid,rank,rankpoints FROM `[players]` WHERE `id`='".$player."'");
						$player_data = $db->FetchArray($sql);
						
						$mission = new Mission($player_data);
						$mission_data = $mission->missions_data[$mission->current_mission];
						
						if ($mission->current_mission == 2 && $mission_data['objects'][2]['completed'] != 1)
						{
							$mission->completeObject(2);
						}else if ($mission->current_mission == 4 && $mission_data['objects'][2]['completed'] != 1)
						{
							$mission->completeObject(2);
						}else if ($mission->current_mission == 6 && $mission_data['objects'][2]['completed'] != 1)
						{
							$mission->completeObject(2);
						}
						
						$rankp = $config['lottery_winner_rank'][$player_data['rank']];
						$rankp /= $key;
						$rank = $config['ranks'][$player_data['rank']];
						$rankpoints = ($rank[2]-$rank[1])/100 * $rankp;
						
						$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$money."', `rankpoints`=`rankpoints`+'".$rankpoints."' WHERE `id`='".$player_data['id']."'");
						
						Accessories::AddLogEvent($player_data['id'], 26, array(
							'-PLACE-' => $key,
							'-MONEY-' => View::CashFormat($money)
						), $player_data['userid']);
					}
				}
				
				$db->Query("UPDATE `lottery` SET `winners`='".serialize($winners)."' WHERE `id`='".$lottery['id']."'");
			}
			else
			{
				$db->Query("UPDATE `lottery` SET `ends`='".(time() + $config['lottery_timespan'])."' WHERE `id`='".$lottery['id']."'");
			}
			
			header('Location: /game/?side=' . $_GET['side']);
			exit;
		}
	}
	
	$tickets = explode(',', $lottery['tickets']);
	$total_bet = round(count($tickets) * $config['lottery_ticket_price'], 0);
	
	$my_tickets = 0;
	foreach ($tickets as $ticket)
	{
		if ($ticket == Player::Data('id'))
		{
			$my_tickets++;
		}
	}
	
	$timeleft = $lottery['ends'] - time();
	
	$last_buy = unserialize($lottery['buy_timestamps']);
	$waitTime = $last_buy[Player::Data('id')] + $config['lottery_ticketBuy_waitTime'] - time();
	
	if (isset($_POST['buy_tickets']) && $waitTime <= 0 && $admin_config['lottery_closed']['value'] == 'false')
	{
		$num_tickets = floor(View::NumbersOnly($_POST['buy_tickets']));
		$price = round($num_tickets * $config['lottery_ticket_price'], 0);
		
		if ($num_tickets > $config['lottery_max_tickets'])
		{
			echo View::Message($langBase->get('loto-01', array('-MAX-' => View::CashFormat($config['lottery_max_tickets']))), 2);
		}
		elseif ($num_tickets < 1)
		{
			echo View::Message($langBase->get('loto-02'), 2);
		}
		elseif ($price > Player::Data('cash'))
		{
			echo View::Message($langBase->get('err-01'), 2);
		}
		else
		{
			$last_buy[Player::Data('id')] = time();
			
			for ($i = 1; $i <= $num_tickets; $i++)
			{
				$tickets[] = Player::Data('id');
			}
			
			$db->Query("UPDATE `lottery` SET `tickets`='".implode(',', $tickets)."', `buy_timestamps`='".serialize($last_buy)."' WHERE `id`='".$lottery['id']."'");
			$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$price."' WHERE `id`='".Player::Data('id')."'");
			
			$sql = $db->Query("SELECT id,family,bank_income FROM `family_businesses` WHERE `place`='".Player::Data('live')."' AND `family`!='0' AND `type`='lottery'");
			$business = $db->FetchArray($sql);
			
			if ($business['id'] != '')
			{
				$toFamily = 5; // %
				$toFamily = $price/100 * $toFamily;
				
				$db->Query("UPDATE `family_businesses` SET `bank_income`=`bank_income`+'".$toFamily."' WHERE `id`='".$business['id']."'");
				$db->Query("UPDATE `[families]` SET `bank_income`=`bank_income`+'".$toFamily."', `bank`=`bank`+'".$toFamily."' WHERE `id`='".$business['family']."'");
			}
			
			$abData = unserialize(Player::Data('antibot_data'));
			$abData['lottery']--;
			if ($abData['lottery'] <= 0)
			{
				$abData['lottery'] = rand($config['antibot_next_range']['lottery'][0], $config['antibot_next_range']['lottery'][1]);
				$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
				
				Accessories::CreateAntibotSession(Player::Data('id'), $db->EscapeString($_GET['side']));
				
				View::Message($langBase->get('loto-03', array('-NUM-' => $num_tickets)), 1, true);
			}
			
			$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
			
			View::Message($langBase->get('loto-03', array('-NUM-' => $num_tickets)), 1, true);
		}
	}
?>
<div style="width: 540px; margin: 0px auto;">
    <div class="left" style="width: 250px;">
        <div class="bg_c" style="width: 230px;">
            <h1 class="big"><?=$langBase->get('loto-04')?></h1>
            <?php
			if ($admin_config['lottery_closed']['value'] == 'true' || $lottery['id'] == '')
			{
				echo '<p class="red">'.$langBase->get('loto-05').'</p>';
			}
			else
			{
				echo '<p>'.$langBase->get('loto-06', array('-TIME-' => View::strTime($timeleft))).'</p>';
			?>
            <p><?=$langBase->get('loto-07', array('-TICK-' => View::CashFormat($my_tickets), '-MAX-' => View::CashFormat($config['lottery_max_tickets_per_round'] - $my_tickets)))?></p><br>
            <p><?=$langBase->get('loto-09', array('-CASH-' => View::CashFormat($config['lottery_ticket_price'])))?></p><br>
            <?php
			if ($waitTime > 0)
			{
				echo '<p class="red">'.$langBase->get('loto-15', array('-TIME-' => $waitTime)).'</p>';
			}
			else
			{
			?>
            <form method="post" action="">
                <dl class="dd_right">
                    <dt><?=$langBase->get('loto-08')?></dt>
                    <dd><input type="text" name="buy_tickets" class="flat" style="min-width: 50px; width: 50px;" value="<?=(floor(Player::Data('cash') / $config['lottery_ticket_price']) > $config['lottery_max_tickets'] ? $config['lottery_max_tickets'] : floor(Player::Data('cash') / $config['lottery_ticket_price']))?>" /></dd>
                </dl>
                <p class="clear center" style="padding-bottom: 10px;"><a href="#" class="button form_submit"><?=$langBase->get('txt-01')?></a></p>
            </form>
            <?php
			}
			?>
            <div class="hr big" style="margin: 10px;"></div>
            <dl class="dd_right">
            	<dt><?=$langBase->get('loto-10')?></dt>
                <dd><?=View::CashFormat(count($tickets))?></dd>
                <dt><?=$langBase->get('bj-05')?></dt>
                <dd><?=View::CashFormat($total_bet)?> $</dd>
            </dl>
            <div class="clear"></div>
            <div class="bg_c c_1" style="width: 210px;">
            	<h1 class="big"><?=$langBase->get('loto-11')?></h1>
                <table class="table center">
                	<thead>
                    	<tr class="small">
                        	<td><?=$langBase->get('concurs-09')?></td>
                            <td><?=$langBase->get('loto-12')?></td>
                            <td><?=$langBase->get('loto-13')?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
					foreach ($config['lottery_winnerplaces'] as $key => $value)
					{
						$i++;
						$c = $i%2 ? 1 : 2;
						
						$rankp = $config['lottery_winner_rank'][Player::Data('rank')] / $key;
						$rank = $config['ranks'][Player::Data('rank')];
						$rankpoints = ($rank[2]-$rank[1])/100 * $rankp;
					?>
                    	<tr class="c_<?=$c?>">
                        	<td><?=$key?></td>
                            <td><?=(round(View::AsPercent($rankpoints, $rank[2]-$rank[1], 4), 2))?> %</td>
                            <td><?=$value['money_percent']?> %</td>
                        </tr>
                    <?php
					}
					?>
                    </tbody>
                </table>
            </div>
            <?php
			}
			?>
        </div>
    </div>
    <div class="left" style="width: 250px; margin-left: 30px;">
        <div class="bg_c" style="width: 230px;">
            <h1 class="big"><?=$langBase->get('loto-14')?></h1>
            <?php
			$sql = $db->Query("SELECT ends,tickets,winners FROM `lottery` WHERE `active`='0' ORDER BY id DESC LIMIT 5");
			$rounds = $db->FetchArrayAll($sql);
			
			if (count($rounds) <= 0)
			{
				echo '<p>'.$langBase->get('err-06').'</p>';
			}
			else
			{
				foreach ($rounds as $round)
				{
					$tickets = explode(',', $round['tickets']);
					
					$winners = unserialize($round['winners']);
				?>
                <div class="bg_c c_1 w200">
                    <h1><?=View::Time($round['ends'], false, 'H:i')?><span class="right"><?=View::CashFormat(count($tickets))?> <?=$langBase->get('loto-08')?></span></h1>
                    <?php
					if (count($winners) <= 0)
					{
						echo '<p>'.$langBase->get('curse-18').'</p>';
					}
					else
					{
						echo '<p>'.$langBase->get('curse-19').'</p><p>';
						
						foreach ($winners as $key => $winner)
						{
							echo $key . '. - ' . View::Player(array('id' => $winner['player'])) . ' - ' . View::CashFormat($winner['money']) . ' $<br />';
						}
						
						echo '</p>';
					}
					?>
                </div>
                <?php
				}
			}
			?>
        </div>
    </div>
</div>
<div class="clear"></div>
</div>