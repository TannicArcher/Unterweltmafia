<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	function getDeckValue($deck, $return_value = true)
	{
		foreach ($deck as $key => $card)
		{
			if ($card[1] == 11)
			{
				unset($deck[$key]);
				$deck[] = $card;
			}
		}
		
		$current_value = 0;
		foreach ($deck as $card)
		{
			$value = $card[1];
			if ($value == 11)
			{
				$value = $current_value+11 > 21 ? 1 : 11;
			}
			
			$current_value += $value;
		}
		
		return $return_value === true ? $current_value : $deck;
	}
	
	$sql = $db->Query("SELECT * FROM `blackjack` WHERE (`dealer`='".Player::Data('id')."' OR `opponent`='".Player::Data('id')."') AND `active`='1'");
	$bjRound = $db->FetchArray($sql);
	
	if ($bjRound['id'] == '')
	{
		if (isset($_POST['round_bet']))
		{
			$bet = View::NumbersOnly($db->EscapeString($_POST['round_bet']));
			$bet_type = View::NumbersOnly($db->EscapeString($_POST['round_type']));
			$btype_v = ($bet_type != 1 ? '`cash`' : '`points`');
			$btype_s = ($bet_type != 1 ? Player::Data('cash') : Player::Data('points'));
			$btype_m = ($bet_type != 1 ? $langBase->get('err-01') : $langBase->get('err-09'));
			
			if ($bet > $btype_s)
			{
				echo View::Message($btype_m, 2);
			}
			elseif ($bet <= 0)
			{
				echo View::Message($langBase->get('bj-01'), 2);
			}
			else
			{
				$theDeck = new PlayingCardDeck();
				
				$totalValue = 0;
				$cards = array();
				
				for ($i = 0; $i < 2; $i++)
				{
					$card = $theDeck->drawCard();
					
					$value = $card[2];
					if (!is_numeric($value))
					{
						$value = $value != 'ace' ? 10 : ($totalValue + 11 > 21 ? 1 : 11);
					}
					
					$cards[] = array($card, $value);
					$totalValue += $value;
				}
				
				$db->Query("UPDATE `[players]` SET ".$btype_v."=".$btype_v."-'".$bet."' WHERE `id`='".Player::Data('id')."'");
				$db->Query("INSERT INTO `blackjack` (`dealer`, `dealer_bet`, `bet_type`, `dealer_state`, `started`, `dealer_cards`, `opponent_cards`, `cardDeck`, `create_ip`)VALUES('".Player::Data('id')."', '".$bet."', '".$bet_type."', '1', '".time()."', '".serialize($cards)."', '".serialize(array())."', '".$theDeck->getDeck(true)."', '".$_SERVER['REMOTE_ADDR']."')");
				
				View::Message($langBase->get('bj-02'), 1, true);
			}
		}
		elseif (isset($_POST['join_round']))
		{
			$round = $db->EscapeString($_POST['join_round']);
			$sql = $db->Query("SELECT id,dealer_bet,bet_type,cardDeck,opponent FROM `blackjack` WHERE `id`='".$round."' AND `active`='1' AND `opponent`!='".Player::Data('id')."' AND `dealer`!='".Player::Data('id')."'");
			$round = $db->FetchArray($sql);
			$bet = $round['dealer_bet'];
			$btype_v = ($round['bet_type'] != 1 ? '`cash`' : '`points`');
			$btype_s = ($round['bet_type'] != 1 ? Player::Data('cash') : Player::Data('points'));
			$btype_m = ($round['bet_type'] != 1 ? $langBase->get('err-01') : $langBase->get('err-09'));
			
			if ($round['id'] == '' || $round['opponent'] != 0)
			{
				echo View::Message($langBase->get('bj-03'), 2);
			}
			elseif ($bet > $btype_s)
			{
				echo View::Message($btype_m, 2);
			}
			else
			{
				$theDeck = new PlayingCardDeck(unserialize($round['cardDeck']));
				
				$totalValue = 0;
				$cards = array();
				
				for ($i = 0; $i < 2; $i++)
				{
					$card = $theDeck->drawCard();
					
					$value = $card[2];
					if (!is_numeric($value))
					{
						$value = $value != 'ace' ? 10 : ($totalValue + 11 > 21 ? 1 : 11);
					}
					
					$cards[] = array($card, $value);
					$totalValue += $value;
				}
				
				$db->Query("UPDATE `[players]` SET ".$btype_v."=".$btype_v."-'".$bet."' WHERE `id`='".Player::Data('id')."'");
				$db->Query("UPDATE `blackjack` SET `opponent`='".Player::Data('id')."', `opponent_bet`='".$bet."', `opponent_cards`='".serialize($cards)."', `opponent_state`='1', `cardDeck`='".$theDeck->getDeck(true)."' WHERE `id`='".$round['id']."'");
				
				View::Message($langBase->get('bj-06'), 1, true);
			}
		}
?>
<div class="bg_c w600">
	<h1 class="big">Blackjack</h1>
    <div class="left" style="width: 255px;">
    	<div class="bg_c c_1" style="width: 235px;">
        	<h1 class="big"><?=$langBase->get('bj-04')?></h1>
            <form method="post" action="">
            	<dl class="dd_right">
                	<dt><?=$langBase->get('bj-05')?> (<a href="#" onclick="document.getElement('input[name=round_bet]').set('value', '<?=View::CashFormat(Player::Data('cash'))?> $'); return false;"><?=$langBase->get('txt-26')?></a>)</dt>
                    <dd><input type="text" name="round_bet" class="flat" value="<?=View::CashFormat(View::NumbersOnly($_POST['round_bet']))?> $" /></dd>
                </dl>
				<dl class="dd_right">
                	<dt><?=$langBase->get('txt-29')?></dt>
                    <dd><select name="round_type"><option value="0"><?=$langBase->get('min-07')?></option><option value="1"><?=$langBase->get('ot-points')?></option></select></dd>
                </dl>
                <p class="center clear">
                	<input type="submit" value="<?=$langBase->get('bj-07')?>" />
                </p>
            </form>
        </div>
    </div>
    <div class="left" style="width: 335px; margin-left: 10px;">
    	<div class="bg_c c_1" style="width: 315px;">
        	<h1 class="big"><?=$langBase->get('bj-08')?></h1>
            <?php
			$sql = $db->Query("SELECT * FROM `blackjack` WHERE `active`='1' AND `opponent`='0' ORDER BY id DESC");
			$rounds = $db->FetchArrayAll($sql);
			
			if (count($rounds) <= 0)
			{
				echo '<p>'.$langBase->get('bj-09').'</p>';
			}
			else
			{
			?>
            <form method="post" action="">
                <table class="table boxHandle">
                    <thead>
                        <tr class="small">
                            <td><?=$langBase->get('txt-06')?></td>
                            <td><?=$langBase->get('bj-05')?></td>
                            <td><?=$langBase->get('txt-32')?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($rounds as $key => $round)
                    {
						if ($round['started']+($config['blackjack_game_expire']-60) < time())
						{
							$btype_v = ($round['bet_type'] != 1 ? '`cash`' : '`points`');
							$db->Query("UPDATE `blackjack` SET `active`='0', `expired`='1' WHERE `id`='".$round['id']."'");
							unset($rounds[$key]);
							
							$db->Query("UPDATE `[players]` SET ".$btype_v."=".$btype_v."+'".$round['dealer_bet']."' WHERE `id`='".$round['dealer']."'");
							
							continue;
						}
						
                        $i++;
                        $c = $i%2 ? 1 : 2;
                    ?>
                        <tr class="c_<?=$c?> boxHandle">
                            <td><input type="radio" name="join_round" value="<?=$round['id']?>" /><?=View::Player(array('id' => $round['dealer']))?></td>
                            <td class="center"><?=View::CashFormat($round['dealer_bet'])?> <?=($round['bet_type'] != 1 ? '$' : 'C')?></td>
                            <td class="t_right"><?=(trim(View::strTime($round['started']+$config['blackjack_game_expire']-time())))?></td>
                        </tr>
                    <?php
                    }
                    ?>
                        <tr class="c_3 center">
                            <td colspan="3"><input type="submit" value="<?=$langBase->get('txt-14')?>" /></td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <?php
			}
			?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php
	}
	else
	{
		$btype_v = ($bjRound['bet_type'] != 1 ? '`cash`' : '`points`');
		
		if ($bjRound['started']+$config['blackjack_game_expire'] < time() && $bjRound['expired'] == 0)
		{
			if ($bjRound['opponent'] != 0)
			{
				$db->Query("UPDATE `blackjack` SET `dealer_state`='2', `opponent_state`='2', `expired`='1' WHERE `id`='".$bjRound['id']."'");
			}
			else
			{
				$db->Query("UPDATE `blackjack` SET `active`='0', `expired`='1' WHERE `id`='".$bjRound['id']."'");
				$db->Query("UPDATE `[players]` SET ".$btype_v."=".$btype_v."+'".$bjRound['dealer_bet']."' WHERE `id`='".$bjRound['dealer']."'");
			}
			
			View::Message($langBase->get('bj-10'), 2, true);
		}
		
		$statuses = array('N/A', $langBase->get('bj-11'), $langBase->get('bj-12'));
		
		$theDeck = new PlayingCardDeck(unserialize($bjRound['cardDeck']));
		$dealer_cards = unserialize($bjRound['dealer_cards']);
		$opponent_cards = unserialize($bjRound['opponent_cards']);
		
		$dealer_totalValue = getDeckValue($dealer_cards, true);
		$opponent_totalValue = getDeckValue($opponent_cards, true);
		
		if ($bjRound['dealer_state'] == 2 && $bjRound['opponent_state'] == 2)
		{
			$winner = $dealer_totalValue > $opponent_totalValue ? 'dealer' : 'opponent';
			
			if ($dealer_totalValue == $opponent_totalValue)


			{
				$db->Query("UPDATE `[players]` SET ".$btype_v."=".$btype_v."+'".$bjRound['dealer_bet']."' WHERE `id`='".$bjRound['dealer']."'");
				$db->Query("UPDATE `[players]` SET ".$btype_v."=".$btype_v."+'".$bjRound['opponent_bet']."' WHERE `id`='".$bjRound['opponent']."'");
				$db->Query("UPDATE `blackjack` SET `result`='push', `active`='0' WHERE `id`='".$bjRound['id']."'");
				
				$result = 'push';
			}
			elseif ($dealer_totalValue <= 21 && $opponent_totalValue <= 21)
			{
				$db->Query("UPDATE `[players]` SET ".$btype_v."=".$btype_v."+'".($bjRound['dealer_bet']+$bjRound['opponent_bet'])."' WHERE `id`='".$bjRound[$winner]."'");
				$db->Query("UPDATE `blackjack` SET `result`='winner:".$winner."', `active`='0' WHERE `id`='".$bjRound['id']."'");
				
				$result = 'winner:' . $winner;
			}
			elseif ($dealer_totalValue > 21 || $opponent_totalValue > 21)
			{
				if ($dealer_totalValue > 21 && $opponent_totalValue > 21)
				{
					$db->Query("UPDATE `[players]` SET ".$btype_v."=".$btype_v."+'".$bjRound['dealer_bet']."' WHERE `id`='".$bjRound['dealer']."'");
					$db->Query("UPDATE `[players]` SET ".$btype_v."=".$btype_v."+'".$bjRound['opponent_bet']."' WHERE `id`='".$bjRound['opponent']."'");
					$db->Query("UPDATE `blackjack` SET `result`='over_21_both', `active`='0' WHERE `id`='".$bjRound['id']."'");
					
					$result = 'over_21_both';
				}
				else
				{
					$w = $dealer_totalValue <= 21 ? 'dealer' : 'opponent';
					$l = $dealer_totalValue > 21 ? 'dealer' : 'opponent';
					
					$db->Query("UPDATE `[players]` SET ".$btype_v."=".$btype_v."+'".($bjRound['dealer_bet']+$bjRound['opponent_bet'])."' WHERE `id`='".$bjRound[$w]."'");
					$db->Query("UPDATE `blackjack` SET `result`='over_21:".$l."', `active`='0' WHERE `id`='".$bjRound['id']."'");
					
					$result = 'over_21:' . $l;
				}
			}
			
			$results = array(
				'push' => $langBase->get('bj-13'),
				'over_21_both' => $langBase->get('bj-14')
			);
			
			$result_text = $results[$result];
			if (!$result_text)
			{
				$result = explode(':', $result);
				if ($result[0] == 'winner')
				{
					$winner = $result[1] == 'dealer' ? $bjRound['dealer'] : $bjRound['opponent'];
					$loser = $result[1] == 'dealer' ? $bjRound['opponent'] : $bjRound['dealer'];
				}
				elseif ($result[0] == 'over_21')
				{
					$winner = $bjRound[$w];
					$loser = $bjRound[$l];
				}
				$btype_sb = ($bjRound['bet_type'] != 1 ? '$' : 'C');
				$result_text = ' ' . View::Player(array('id' => $winner), true) . ' '.$langBase->get('bj-30').' ' . View::CashFormat($bjRound['dealer_bet']+$bjRound['opponent_bet']).' '.$btype_sb;
				
				$sql = $db->Query("SELECT id,userid FROM `[players]` WHERE `id`='".$winner."'");
				$player = $db->FetchArray($sql);
				
				$mission = new Mission($player);
				$mission_data = $mission->missions_data[$mission->current_mission];
				
				if ($mission->current_mission == 2)
				{
					if ($mission_data['objects'][0]['completed'] != 1)
					{
						$wins = $mission_data['objects'][0]['wins'];
						if (!in_array($loser, $wins))
						{
							$wins[] = $loser;
							$mission_data['objects'][0]['wins'] = $wins;
							$mission->missions_data[$mission->current_mission]['objects'][0]['wins'] = $wins;
							
							$mission->saveMissionData();
						}
						
						if (count($wins) >= 5)
						{
							$mission->completeObject(0);
						}
					}
				}else if ($mission->current_mission == 4)
				{
					if ($mission_data['objects'][0]['completed'] != 1)
					{
						$wins = $mission_data['objects'][0]['wins'];
						if (!in_array($loser, $wins))
						{
							$wins[] = $loser;
							$mission_data['objects'][0]['wins'] = $wins;
							$mission->missions_data[$mission->current_mission]['objects'][0]['wins'] = $wins;
							
							$mission->saveMissionData();
						}
						
						if (count($wins) >= 10)
						{
							$mission->completeObject(0);
						}
					}
				}
			}
			
			View::Message($langBase->get('bj-10') . $result_text, 1, true);
		}
		
		
		if (isset($_POST['newcard']) && $bjRound[($bjRound['dealer'] == Player::Data('id') ? 'dealer' : 'opponent') . '_state'] != 2)
		{
			$player = $bjRound['dealer'] == Player::Data('id') ? 'dealer' : 'opponent';
			$totalValue = $player == 'dealer' ? $dealer_totalValue : $opponent_totalValue;
			
			if ($totalValue >= 21)
			{
				View::Message($langBase->get('bj-15'), 2, true);
			}
			
			$cards = unserialize($bjRound[$player . '_cards']);
			$card = $theDeck->drawCard();
			
			$value = $card[2];
			if (!is_numeric($value))
			{
				$value = $value != 'ace' ? 10 : ($totalValue + 11 > 21 ? 1 : 11);
			}
			
			$cards[] = array($card, $value);
			
			$db->Query("UPDATE `blackjack` SET `".$player."_cards`='".serialize($cards)."', `cardDeck`='".$theDeck->getDeck(true)."' WHERE `id`='".$bjRound['id']."'");
			
			View::Message($langBase->get('bj-16'), 1, true);
		}
		elseif (isset($_POST['finish']) && $bjRound[($bjRound['dealer'] == Player::Data('id') ? 'dealer' : 'opponent') . '_state'] != 2)
		{
			$player = $bjRound['dealer'] == Player::Data('id') ? 'dealer' : 'opponent';
			
			$db->Query("UPDATE `blackjack` SET `".$player."_state`='2' WHERE `id`='".$bjRound['id']."'");
			
			View::Message($langBase->get('bj-17'), 1, true);
		}
?>
<table class="table" style="width: 300px;">
	<thead>
    	<tr><td colspan="2">Blackjack</td></tr>
    </thead>
    <tbody>
    	<tr class="c_1">
        	<td style="background: #1d1d1d;" width="35%"><?=$langBase->get('bj-18')?></td>
            <td width="65%"><?=View::strTime(time() - $bjRound['started'])?></td>
        </tr>
        <tr class="c_2">
        	<td style="background: #1d1d1d;" width="35%"><?=$langBase->get('bj-19')?></td>
            <td width="65%"><?=(trim(View::strTime($bjRound['started']+$config['blackjack_game_expire']-time())))?></td>
        </tr>
        <tr class="c_1">
        	<td style="background: #1d1d1d;" width="35%"><?=$langBase->get('bj-05')?></td>
            <td width="65%"><?=View::CashFormat($bjRound['dealer_bet'])?> <?=($bjRound['bet_type'] != 1 ? '$' : 'C')?></td>
        </tr>
        <tr class="c_3 center">
        	<td colspan="2" style="padding: 15px;"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>" class="button"><?=$langBase->get('txt-33')?></a></td>
        </tr>
    </tbody>
</table>
<div class="bg_c w400">
	<h1 class="big"><?=$langBase->get('bj-20')?><?=($bjRound['dealer'] == Player::Data('id') ? ' (<b>'.$langBase->get('txt-34').'</b>)' : '')?></h1>
    <div class="bg_c w200 c_1" style="padding-top: 5px; margin: 0px auto 0;">
    	<dl class="dd_right" style="margin: 0;">
        	<dt><?=$langBase->get('txt-06')?></dt>
            <dd><?=View::Player(array('id' => $bjRound['dealer']))?></dd>
            <dt><?=$langBase->get('bj-21')?></dt>
            <dd><?=$statuses[$bjRound['dealer_state']]?></dd>
        </dl>
        <div class="clear"></div>
    </div>
    <div class="hr"></div>
    <?php
	if (count($dealer_cards) <= 0)
	{
		echo '<p>'.$langBase->get('bj-23').'</p>';
	}
	else
	{
		echo '<div class="blackjack_cards_wrap">';
		
		$x = 0;
		foreach ($dealer_cards as $card)
		{
			if ($bjRound['opponent'] != 0)
				$x++;
	?>
    <div class="card <?=($bjRound['dealer'] == Player::Data('id') && $bjRound['opponent'] != 0 ? $card[0][1] : 'back')?>">
    	<p><?=($bjRound['dealer'] == Player::Data('id') && $bjRound['opponent'] != 0 ? strtoupper($card[0][3]) : '')?></p>
    </div>
    <?php
		}
		
		echo '</div><div class="clear"></div>';
	}
	
	if ($bjRound['dealer'] == Player::Data('id'))
	{
		if ($bjRound['opponent'] != 0)
			echo '<p class="center large">' . getDeckValue($dealer_cards, true) . '</p>';
	?>
    <form method="post" action="">
    	<p class="center">
        	<input type="submit" name="newcard" value="<?=$langBase->get('bj-25')?>" />
            <input type="submit" name="finish" value="<?=$langBase->get('bj-26')?>" style="margin-left: 5px;" />
        </p>
    </form>
    <?php
	}
	?>
</div>
<div class="bg_c w400">
	<h1 class="big"><?=$langBase->get('bj-22')?><?=($bjRound['opponent'] == Player::Data('id') ? ' (<b>'.$langBase->get('txt-34').'</b>)' : '')?></h1>
    <?php
	if ($bjRound['opponent'] == 0)
	{
		echo '<p>'.$langBase->get('bj-24').'</p>';
	}
	else
	{
	?>
    <div class="bg_c w200 c_1" style="padding-top: 5px; margin: 0px auto 0;">
    	<dl class="dd_right" style="margin: 0;">
        	<dt><?=$langBase->get('txt-06')?></dt>
            <dd><?=View::Player(array('id' => $bjRound['opponent']))?></dd>
            <dt><?=$langBase->get('bj-21')?></dt>
            <dd><?=$statuses[$bjRound['opponent_state']]?></dd>
        </dl>
        <div class="clear"></div>
    </div>
    <div class="hr"></div>
	<?php
    if (count($opponent_cards) <= 0)
    {
        echo '<p>'.$langBase->get('bj-23').'</p>';
    }
    else
    {
        echo '<div class="blackjack_cards_wrap">';
        
        foreach ($opponent_cards as $card)
        {
    ?>
    <div class="card <?=($bjRound['opponent'] == Player::Data('id') ? $card[0][1] : 'back')?>">
        <p><?=($bjRound['opponent'] == Player::Data('id') ? strtoupper($card[0][3]) : '')?></p>
    </div>
    <?php
        }
        
        echo '</div><div class="clear"></div>';
    }
    
    if ($bjRound['opponent'] == Player::Data('id'))
    {
    ?>
    <p class="center large"><?=getDeckValue($opponent_cards, true)?></p>
    <form method="post" action="">
        <p class="center">
            <input type="submit" name="newcard" value="<?=$langBase->get('bj-25')?>" />
            <input type="submit" name="finish" value="<?=$langBase->get('bj-26')?>" style="margin-left: 5px;" />
        </p>
    </form>
    <?php
    }
    ?>
    <?php
	}
	?>
</div>
<?php
	}
	
	$sql = "SELECT id,dealer,opponent,started,dealer_bet,opponent_bet,bet_type,result,dealer_cards,opponent_cards FROM `blackjack` WHERE (`dealer`='".Player::Data('id')."' OR `opponent`='".Player::Data('id')."') AND `active`='0' ORDER BY id DESC LIMIT 10";
	$pagination = new Pagination($sql, 10, 'p');
	$rounds = $pagination->GetSQLRows();
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('bj-27')?></h1>
    <?php
	if (count($rounds) > 0)
	{
	?>
    <table class="table">
    	<thead>
        	<tr>
            	<td width="20%"><?=$langBase->get('bj-22')?></td>
                <td width="25%"><?=$langBase->get('bj-28')?></td>
                <td width="20%"><?=$langBase->get('txt-27')?></td>
                <td width="18%"><?=$langBase->get('bj-05')?></td>
                <td width="17%"><?=$langBase->get('bj-35')?></td>
            </tr>
        </thead>
        <tbody>
        <?php
		$i = 0;
		
		foreach ($rounds as $round)
		{
			$i++;
			$c = $i%2 ? 1 : 2;
			
			$opponent = $round['dealer'] == Player::Data('id') ? 'opponent' : 'dealer';
			$player = $round['dealer'] == Player::Data('id') ? 'dealer' : 'opponent';
			
			$resultat = 'Necunoscut';
			
			$result = explode(':', $round['result']);
			$b_type = ($round['bet_type'] != 1 ? '$' : 'C');
			
			if ($result[0] == 'push')
			{
				$resultat = '<b>'.$langBase->get('bj-31').'</b><br /><span class="subtext">'.View::CashFormat($round[$player.'_bet']).' '.$b_type.'</span>';
			}
			elseif ($result[0] == 'winner')
			{
				$resultat = $result[1] == $opponent ? '<b>'.$langBase->get('bj-32').'</b><br> <span class="subtext">'.View::CashFormat($round[$player.'_bet']).' $</span>' : '<b>'.$langBase->get('bj-34').'</b><br> <span class="subtext">'.View::CashFormat($round['dealer_bet']+$round['opponent_bet']).' '.$b_type.'</span>';
			}
			elseif ($result[0] == 'over_21_both')
			{
				$resultat = '<b>'.$langBase->get('bj-33').'</b><br /><span class="subtext">'.View::CashFormat($round[$player.'_bet']).' '.$b_type.'</span>';
			}
			elseif ($result[0] == 'over_21')
			{
				$resultat = '<b>'.($result[1] == $player ? $langBase->get('bj-32') : $langBase->get('bj-34')).'</b><br /><span class="subtext">'.View::CashFormat($round['dealer_bet']+$round['opponent_bet']).' '.$b_type.'</span>';
			}
			
			$dealer_cards = unserialize($round['dealer_cards']);
			$opponent_cards = unserialize($round['opponent_cards']);
			$dealer_totalValue = getDeckValue($dealer_cards, true);
			$dealer_cardResult = '';
			$opponent_totalValue = getDeckValue($opponent_cards, true);
			$opponent_cardResult = '';
			
			foreach ($dealer_cards as $card)
			{
				$dealer_cardResult .= '<b>'.strtoupper($card[0][3]).'</b> ';
			}
			foreach ($opponent_cards as $card)
			{
				$opponent_cardResult .= '<b>'.strtoupper($card[0][3]).'</b> ';
			}
		?>
        	<tr class="c_<?=$c?>">
            	<td width="20%" class="center"><?=($round[$opponent] == 0 ? 'N/A' : View::Player(array('id' => $round[$opponent])))?></td>
                <td width="25%" class="center">
                	<dl class="dd_right" style="margin: 0;">
                        <?php
						if ($round['dealer'] == Player::Data('id'))
						{

						?>
                        <dt><?=trim($dealer_cardResult)?></dt><dd><span style="color: #ff6600"><?=$dealer_totalValue?></span></dd>
                        <?php if($round['opponent'] != 0){ ?><dt><span class="subtext"><?=trim($opponent_cardResult)?></span></dt><dd><span style="color: #762f00" class="subtext"><?=$opponent_totalValue?></span></dd><?php } ?>
                        <?php
						}
						else
						{
						?>
                        <dt><?=trim($opponent_cardResult)?></dt><dd><span style="color: #ff6600"><?=$opponent_totalValue?></span></dd>
                        <dt><span class="subtext"><?=trim($dealer_cardResult)?></span></dt><dd><span style="color: #762f00" class="subtext"><?=$dealer_totalValue?></span></dd>
                        <?php
						}
						?>
                    </dl>
                    <div class="clear"></div>
                </td>
                <td width="20%" class="center"><?=View::Time($round['started'])?></td>
                <td width="18%" class="t_right"><?=View::CashFormat($round['dealer_bet'])?> <?=$b_type?></td>
                <td width="17%" class="t_right"><?=$resultat?></td>
            </tr>
        <?php
		}
		?>
        </tbody>
    </table>
    <?php
	}
	?>
</div>
<div class="graph_container">
	<div id="graph_bj_results"></div>
</div>