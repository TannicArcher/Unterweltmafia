<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$games = $db->QueryFetchArrayAll("SELECT * FROM `numbers_game` WHERE `active`='1' AND `started`<'".(time() - $config['numbers_game_round_expires'])."'");
	foreach($games as $game){
		if (($game['started']+$config['numbers_game_round_expires'] - time()) <= 0)
		{
			$players = unserialize($game['players']);
			if (count($players) <= 1){
				$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$game['entry_sum']."' WHERE `id`='".$game['starter']."'");
				$db->Query("UPDATE `numbers_game` SET `active`='0', `result`='expired:one_player' WHERE `id`='".$game['id']."'");
			}else{
				$winner_value = 0;
				for ($i = 0; $i < $config['numbers_game_dices']; $i++)
				{
					$winner_value += rand(1, 6);
				}
				
				$winners = array();
				foreach ($players as $player){
					if ($player[1] == $winner_value)
						$winners[] = $player;
				}
				
				$total_winners = count($winners);
				$total_cash = count($players) * $game['entry_sum'];
				$winner_cash = round($total_cash / ($total_winners <= 0 ? 1 : $total_winners), 0);

				foreach ($winners as $winner){
					$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$winner_cash."' WHERE `id`='".$winner[0]."'");
					
					$winners_text .= ',' . $winner[0];
				}
				
				$db->Query("UPDATE `numbers_game` SET `active`='0', `result`='winners:".substr($winners_text, 1)."', `number`='".$winner_value."', `winner_cash`='".$winner_cash."' WHERE `id`='".$game['id']."'");
			}
		}
	}
	
	$my_game = $db->QueryFetchArray("SELECT * FROM `numbers_game` WHERE `starter`='".Player::Data('id')."' AND `active`='1'");

	if ($my_game['id'] != '')
	{
		$players = unserialize($my_game['players']);
		
		$expires = $my_game['started']+$config['numbers_game_round_expires'] - time();
		
		if ($expires <= 0)
		{
			if (count($players) <= 1)
			{
				$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$my_game['entry_sum']."' WHERE `id`='".Player::Data('id')."'");
				
				$result = 'expired:one_player';
				$message = $langBase->get('numere-01');
			}
			else
			{
				foreach ($players as $player)
				{
					$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$my_game['entry_sum']."' WHERE `id`='".$player[0]."'");
				}
				
				$result = 'expired:more_players';
				$message = $langBase->get('numere-01');
			}
			
			$db->Query("UPDATE `numbers_game` SET `active`='0', `result`='".$result."' WHERE `id`='".$my_game['id']."'");
			
			View::Message($message, 2, true);
		}
		
		if (isset($_POST['cancel_round']) && count($players) <= 1)
		{
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$my_game['entry_sum']."' WHERE `id`='".Player::Data('id')."'");
			$db->Query("UPDATE `numbers_game` SET `active`='0', `result`='cancelled' WHERE `id`='".$my_game['id']."'");
			
			View::Message($langBase->get('numere-02'), 1, true);
		}
		elseif (isset($_POST['continue_round']) && count($players) > 1)
		{
			$total_cash = count($players) * $my_game['entry_sum'];
			
			$winner_value = 0;
			for ($i = 0; $i < $config['numbers_game_dices']; $i++)
			{
				$winner_value += rand(1, 6);
			}
			
			$winners = array();
			foreach ($players as $player)
			{
				if ($player[1] == $winner_value)
					$winners[] = $player;
			}
			
			$total_winners = count($winners);
			$winner_cash = round($total_cash / ($total_winners <= 0 ? 1 : $total_winners), 0);

			foreach ($winners as $winner)
			{
				$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$winner_cash."' WHERE `id`='".$winner[0]."'");
				
				$winners_text .= ',' . $winner[0];
			}
			
			$db->Query("UPDATE `numbers_game` SET `active`='0', `result`='winners:".substr($winners_text, 1)."', `number`='".$winner_value."', `winner_cash`='".$winner_cash."' WHERE `id`='".$my_game['id']."'");

			View::Message(($total_winners > 0 ? $langBase->get('numere-03', array('-NUM-' => $total_winners, '-CASH-' => View::CashFormat($winner_cash))) : $langBase->get('numere-04')), ($total_winners > 0 ? 1 : 2), true);
		}
?>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('numere-05')?></h1>
    <div class="left" style="width: 245px;">
    	<div class="bg_c c_1" style="width: 225px;">
        	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
            <dl class="dd_right">
            	<dt><?=$langBase->get('numere-06')?></dt>
                <dd><?=View::Player(array('id' => $my_game['starter']))?></dd>
                <dt><?=$langBase->get('numere-07')?></dt>
                <dd><?=View::Time($my_game['started'])?></dd>
                <dt><?=$langBase->get('numere-08')?></dt>
                <dd><?=View::strTime($expires, 1)?></dd>
                <dt><?=$langBase->get('numere-09')?></dt>
                <dd><?=View::CashFormat($my_game['entry_sum'])?> $</dd>
                <dt><?=$langBase->get('numere-10')?></dt>
                <dd><?=View::CashFormat($my_game['entry_sum'] * count($players))?> $</dd>
            </dl>
            <div class="clear"></div>
            <form method="post" action="">
                <p class="center">
                    <input type="submit" name="<?=(count($players) <= 1 ? 'cancel_round' : 'continue_round')?>" value="<?=(count($players) <= 1 ? $langBase->get('numere-11') : $langBase->get('numere-12'))?>" />
                </p>
            </form>
        </div>
    </div>
    <div class="left" style="width: 245px; margin-left: 10px;">
    	<div class="bg_c c_1" style="width: 225px;">
        	<h1 class="big"><?=$langBase->get('numere-13')?> - <?=count($players)?>/<?=$config['numbers_game_max_players']?></h1>
            <?php
			if (count($players) > 0)
			{
			?>
            <table class="table center">
            	<thead>
                	<tr><td><?=$langBase->get('numere-14')?></td><td><?=$langBase->get('numere-15')?></td></tr>
                </thead>
                <tbody>
                <?php
				foreach ($players as $player)
				{
					$i++;
					$c = $i%2 ? 1 : 2;
				?>
                	<tr class="c_<?=$c?>"><td><?=View::Player(array('id' => $player[0]))?></td><td><?=$player[1]?></td></tr>
                <?php
				}
				?>
                	<tr class="c_3"><td></td><td></td></tr>
                </tbody>
            </table>
            <?php
			}
			?>
        </div>
    </div>
    <div class="break"></div>
</div>
<?php
	}
	else
	{
		if (isset($_GET['join']))
		{
			$round = $db->EscapeString($_GET['join']);
			$round = $db->QueryFetchArray("SELECT * FROM `numbers_game` WHERE `active`='1' AND `id`='".$round."'");

			$expires = $round['started']+$config['numbers_game_round_expires'] - time();
			
			if ($round['id'] == '' || $expires <= 0)
			{
				View::Message('ERROR', 2, true, '/game/?side=' . $_GET['side']);
			}
			
			$players = unserialize($round['players']);
			
			$is_in = false;
			foreach ($players as $player)
			{
				if ($player[0] == Player::Data('id'))
					$is_in = true;
			}
			
			if (isset($_POST['round_number']) && $is_in == false)
			{
				$number = View::NumbersOnly($db->EscapeString($_POST['round_number']));
				
				if (count($players) >= $config['numbers_game_max_players'])
				{
					echo View::Message($langBase->get('numere-16'), 2);
				}
				elseif ($round['entry_sum'] > Player::Data('cash'))
				{
					echo View::Message($langBase->get('err-01'), 2);
				}
				elseif ($number < $config['numbers_game_dices'] || $number > $config['numbers_game_dices'] * 6)
				{
					echo View::Message($langBase->get('numere-17', array('-MIN-' => $config['numbers_game_dices'], '-MAX-' => ($config['numbers_game_dices'] * 6))), 2);
				}
				else
				{
					$players[] = array(Player::Data('id'), $number);
					
					$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$round['entry_sum']."' WHERE `id`='".Player::Data('id')."'");
					$db->Query("UPDATE `numbers_game` SET `players`='".serialize($players)."' WHERE `id`='".$round['id']."'");
					
					View::Message($langBase->get('numere-18'), 1, true);
				}
			}
?>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('numere-05')?></h1>
    <div class="left" style="width: 245px;">
   	  <div class="bg_c c_1" style="width: 225px;">
        	<h1 class="big"Informatii Runda</h1>
            <dl class="dd_right">
            	<dt><?=$langBase->get('numere-06')?></dt>
                <dd><?=View::Player(array('id' => $round['starter']))?></dd>
                <dt><?=$langBase->get('numere-07')?></dt>
                <dd><?=View::Time($round['started'])?></dd>
                <dt><?=$langBase->get('numere-08')?></dt>
                <dd><?=View::strTime($expires, 1)?></dd>
                <dt><?=$langBase->get('numere-09')?></dt>
                <dd><?=View::CashFormat($round['entry_sum'])?> $</dd>
                <dt><?=$langBase->get('numere-10')?></dt>
                <dd><?=View::CashFormat($round['entry_sum'] * count($players))?> $</dd>
            </dl>
            <div class="clear"></div>
            <div class="hr"></div>
            <?php
			if ($is_in == true)
			{
				echo '<p class="center">'.$langBase->get('numere-19').'</p>';
			}
			else
			{
			?>
            <form method="post" action="">
            	<dl class="dd_right" style="margin-bottom: 0;">
                	<dt><?=$langBase->get('numere-20')?></dt>
                    <dd><input type="text" class="flat numbersOnly" name="round_number" value="<?=$_POST['round_number']?>" style="width: 50px; min-width: 50px;" maxlength="2" /></dd>
                </dl>
                <p class="center clear">
                	<input type="submit" value="<?=$langBase->get('numere-21')?>" />
                </p>
            </form>
            <?php
			}
			?>
        </div>
    </div>
    <div class="left" style="width: 245px; margin-left: 10px;">
    	<div class="bg_c c_1" style="width: 225px;">
        	<h1 class="big"><?=$langBase->get('numere-13')?> - <?=count($players)?>/<?=$config['numbers_game_max_players']?></h1>
            <?php
			if (count($players) <= 0)
			{
				echo '<p>'.$langBase->get('err-06').'</p>';
			}
			else
			{
			?>
            <table class="table center">
            	<thead>
                	<tr><td><?=$langBase->get('numere-14')?></td><?=($is_in == true ? '<td>'.$langBase->get('numere-15').'</td>' : '')?></tr>
                </thead>
                <tbody>
                <?php
				foreach ($players as $player)
				{
					$i++;
					$c = $i%2 ? 1 : 2;
				?>
                	<tr class="c_<?=$c?>"><td><?=View::Player(array('id' => $player[0]))?></td><?=($is_in == true ? '<td>'.$player[1].'</td>' : '')?></tr>
                <?php
				}
				?>
                	<tr class="c_3"><td></td><?=($is_in == true ? '<td></td>' : '')?></tr>
                </tbody>
            </table>
            <?php
			}
			?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php
		}
		elseif (isset($_GET['log']))
		{
			$rounds = array();
			
			$sql = $db->Query("SELECT players,starter,entry_sum,result,started,winner_cash FROM `numbers_game` WHERE `active`='0' AND `result`!='unknown' ORDER BY id DESC");
			while ($round = $db->FetchArray($sql))
			{
				$players = unserialize($round['players']);
				
				foreach ($players as $player)
				{
					if ($player[0] == Player::Data('id'))
					{
						$rounds[] = $round;
						break;
					}
				}
			}
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('numere-22')?></h1>
    <p class="t_right">
    	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>"><?=$langBase->get('ot-back')?> &laquo;</a>
    </p>
    <?php
	if (count($rounds) > 0)
	{
	?>
    <table class="table">
    	<thead>
        	<tr>
            	<td width="10%"><?=$langBase->get('numere-06')?></td>
                <td width="10%"><?=$langBase->get('numere-13')?></td>
                <td width="10%"><?=$langBase->get('numere-09')?></td>
                <td width="10%"><?=$langBase->get('bj-29')?></td>
                <td width="10%"><?=$langBase->get('txt-27')?></td>
            </tr>
        </thead>
        <tbody>
        <?php
		foreach ($rounds as $round)
		{
			$i++;
			$c = $i%2 ? 1 : 2;
			
			$players = unserialize($round['players']);
			$result = explode(':', $round['result']);
			
			$result_text = 'Unknown';
			if ($result[0] == 'expired')
			{
				$result_text = '<b>Runda Expirata!</b><br /><span class="subtext">' . ($result[1] == 'more_players' ? 'Ati primit banii inapoi' : 'Ai primit banii inapoi') . '</span>';
			}
			elseif ($result[0] == 'cancelled')
			{
				$result_text = 'Round cancelled!';
			}
			elseif ($result[0] == 'winners')
			{
				$winners = explode(',', $result[1]);
				if (in_array(Player::Data('id'), $winners))
				{
					$result_text = '<span style="font-weight: bold; color: #ff6600;">You won</span><br /><span class="subtext">'.View::CashFormat($round['winner_cash']).' $</span>';
				}
				else
				{
					$result_text = '<span style="font-weight: bold; color: #ff0000;">You lost </span><br /><span class="subtext">'.View::CashFormat($round['entry_sum']).' $</span>';
				}
			}
		?>
        	<tr class="c_<?=$c?>">
            	<td width="10%" class="center"><?=View::Player(array('id' => $round['starter']))?></td>
                <td width="10%" class="center"><?=count($players)?>/<?=$config['numbers_game_max_players']?></td>
                <td width="10%" class="center"><?=View::CashFormat($round['entry_sum'] * count($players))?> $</td>
                <td width="10%" class="t_right"><?=$result_text?></td>
                <td width="10%" class="t_right"><?=View::Time($round['started'])?></td>
            </tr>
        <?php
		}
		?>
        	<tr class="c_3"><td colspan="5"></td></tr>
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
			$all_games = $db->QueryFetchArrayAll("SELECT id,players,starter,started,entry_sum FROM `numbers_game` WHERE `active`='1' ORDER BY id DESC");

			if (isset($_POST['newRound_buyin']))
			{
				$buyIn = View::NumbersOnly($db->EscapeString($_POST['newRound_buyin']));
				$number = View::NumbersOnly($db->EscapeString($_POST['newRound_number']));
				
				if ($buyIn > Player::Data('cash'))
				{
					echo View::Message($langBase->get('err-01'), 2);
				}
				elseif ($buyIn < $config['numbers_game_max_players'] * 2)
				{
					echo View::Message('The stake must be higher than $'.(View::CashFormat($config['numbers_game_max_players'] * 2)).'.', 2);
				}
				elseif ($number < $config['numbers_game_dices'] || $number > $config['numbers_game_dices'] * 6)
				{
					echo View::Message($langBase->get('numere-17', array('-MIN-' => $config['numbers_game_dices'], '-MAX-' => ($config['numbers_game_dices'] * 6))), 2);
				}
				else
				{
					$players = array();
					$players[] = array(Player::Data('id'), $number);
					
					$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$buyIn."' WHERE `id`='".Player::Data('id')."'");
					$db->Query("INSERT INTO `numbers_game` (`starter`, `players`, `entry_sum`, `started`)VALUES('".Player::Data('id')."', '".serialize($players)."', '".$buyIn."', '".time()."')");
					
					View::Message('You joined the game!', 1, true);
				}
			}
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('numere-00')?></h1>
    <div class="bg_c c_1 w300 left">
    	<h1 class="big"><?=$langBase->get('numere-24')?></h1>
        <form method="post" action="">
        	<dl class="dd_right">
            	<dt><?=$langBase->get('numere-09')?> (<a href="#" onclick="document.getElement('input[name=newRound_buyin]').set('value', '<?=View::CashFormat(Player::Data('cash'))?> $'); return false;"><?=$langBase->get('txt-26')?></a>)</dt>
                <dd><input type="text" class="flat" name="newRound_buyin" value="<?=View::CashFormat(View::NumbersOnly($_POST['newRound_buyin']))?> $" /></dd>
                <dt><?=$langBase->get('numere-15')?> (<?=$langBase->get('numere-25', array('-MIN-' => $config['numbers_game_dices'], '-MAX-' => ($config['numbers_game_dices'] * 6)))?>)</dt>
                <dd><input type="text" class="flat numbersOnly" name="newRound_number" value="<?=View::NumbersOnly($_POST['newRound_number'])?>" style="width: 50px; min-width: 50px;" maxlength="2" /></dd>
            </dl>
            <p class="center clear">
            	<input type="submit" value="<?=$langBase->get('numere-26')?>" />
            </p>
        </form>
        <p class="center"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;log">&raquo; <?=$langBase->get('numere-22')?></a></p>
    </div>
	<div class="left" style="width: 265px; margin-left: 10px;">
    	<div class="bg_c c_1" style="width: 245px;">
        	<h1 class="big"><?=$langBase->get('numere-27')?></h1>
            <?=$langBase->get('numere-29')?>
        </div>
    </div>
	<div class="clear"></div>
	<div class="bg_c c_1" style="width:580px;margin-top:0">
		<h1 class="big"><?=$langBase->get('numere-28')?></h1>
		<?php
		if (count($all_games) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
		?>
		<table class="table">
			<thead>
				<tr>
					<td><?=$langBase->get('numere-06')?></td>
					<td><?=$langBase->get('numere-13')?></td>
					<td><?=$langBase->get('numere-09')?></td>
					<td><?=$langBase->get('numere-08')?></td>
					<td><?=$langBase->get('numere-23')?></td>
					<td></td>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ($all_games as $game)
			{
				$i++;
				$c = $i%2 ? 1 : 2;
				
				$players = unserialize($game['players']);
				
				$joined = false;
				foreach ($players as $player)
				{
					if ($player[0] == Player::Data('id'))
					{
						$number = $player[1];
						$joined = true;
						break;
					}
				}
			?>
				<tr class="c_<?=$c?>">
					<td class="center"><?=View::Player(array('id' => $game['starter']), true)?></td>
					<td class="center"><?=count($players)?>/<?=$config['numbers_game_max_players']?></td>
					<td class="center"><?=View::CashFormat($game['entry_sum'])?> $</td>
					<td class="center"><?=View::strTime(($game['started']+$config['numbers_game_round_expires'] - time()), 1, ' '.$langBase->get('comp-65').' ')?></td>
					<td class="center"><?=($joined ? $number : '<font color="red">N/A</font>')?></td>
					<td class="center"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;join=<?=$game['id']?>"> <?=($joined ? 'Detalii' : $langBase->get('numere-21'))?></a></td>
				</tr>
			<?php
			}
			?>
				<tr class="c_3"><td colspan="7"></td></tr>
			</tbody>
		</table>
		<?php
		}
		?>
	</div>
    <div class="break"></div>
</div>
<?php
		}
	}
?>