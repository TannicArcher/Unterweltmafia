<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$sql = $db->Query("SELECT id,added,penalty FROM `jail` WHERE `player`='".Player::Data('id')."' AND `added`+`penalty`>".time()." AND `active`='1'");
	$jail = $db->FetchArray($sql);
	
	if ($jail['id'] == '')
	{
		header("Location: /game/?side=startside");
		exit;
	}
	
	$timeleft = $jail['added']+$jail['penalty'] - time();
	
	if ( $timeleft <= 0 )
	{
		header("Location: " . $_SERVER['REQUEST_URI']);
		exit;
	}
	
	if (isset($_POST['breakout_amount']))
	{
		$amount = View::NumbersOnly($_POST['breakout_amount']);
		
		if ($amount < 10000)
		{
			echo View::Message($langBase->get('jail-21'), 2);
		}
		elseif ($amount > Player::Data('cash'))
		{
			echo View::Message($langBase->get('err-01'), 2);
		}
		else
		{
			$max_brabe = $config['jail_brabe_max'];
			$max_brabe = round(($max_brabe + ($max_brabe/100 * View::AsPercent(Player::Data('wanted-level'), $config['max_wanted-level'], 2))), 0);
			
			if (rand(10000, $max_brabe) <= $amount)
			{
				$jail_stats = unserialize(Player::Data('jail_stats'));
				$jail_stats['times_bribed']++;
				$jail_stats['bribe_cash'] += $amount;
				
				$new_wanted = Player::Data('wanted-level') + rand(25, 35);
				if ($new_wanted > $config['max_wanted-level'])
					$new_wanted = $config['max_wanted-level'];
				
				$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$amount."', `jail_stats`='".serialize($jail_stats)."', `wanted-level`='$new_wanted' WHERE `id`='".Player::Data('id')."'");
				$db->Query("UPDATE `jail` SET `active`='0' WHERE `id`='".$jail['id']."'");
				
				Accessories::AddToLog(Player::Data('id'), array('bribe_result' => 'success', 'bribe_cash' => $amount));
				
				View::Message($langBase->get('jail-22'), 1, true);
			}
			else
			{
				$db->Query("UPDATE `jail` SET `active`='0' WHERE `id`='".$jail['id']."'");
				
				$jail_stats = unserialize(Player::Data('jail_stats'));
				$jail_stats['times_bribed']++;
				$jail_stats['bribe_cash'] += $amount;
				
				$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$amount."', `jail_stats`='".serialize($jail_stats)."' WHERE `id`='".Player::Data('id')."'");
				$penalty = Accessories::SetInJail(Player::Data('id'), rand(15, 25));
				
				Accessories::AddToLog(Player::Data('id'), array('bribe_result' => 'fail', 'penalty' => $penalty, 'bribe_cash' => $amount));
				
				View::Message($langBase->get('jail-23', array('-TIME-' => $penalty)), 2, true);
			}
		}
	}
?>
<div class="bg_c w300">
	<h1 class="big"><?=$langBase->get('jail-00')?></h1>
    <p><?=$langBase->get('ot-arestat', array('-TIME-' => $timeleft))?></p>
    <div class="hr big" style="margin: 10px 0 10px 0;"></div>
    <p><?=$langBase->get('jail-24')?></p>
    <form method="post" action="">
        <dl class="dd_right">
            <dt><?=$langBase->get('txt-25')?></dt>
            <dd><input type="text" name="breakout_amount" class="flat" value="<?=View::CashFormat(View::NumbersOnly($_POST['breakout_amount']))?>" /></dd>
        </dl>
        <p class="clear center">
            <input type="submit" value="<?=$langBase->get('jail-25')?>" />
        </p>
    </form>
</div>