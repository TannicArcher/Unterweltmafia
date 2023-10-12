<?php
$update = $db->QueryFetchArray("SELECT timestamp FROM `cron_tab` WHERE `name`='day'");

$time = time();
$date = date('j F Y');
$timestamp = strtotime($date);
	
if($update['timestamp'] < $timestamp){	
	$db->Query("UPDATE `cron_tab` SET `timestamp`='".$timestamp."' WHERE `name`='day'");

	$bjdays = $time-(86400 * 7);
	$logdays = $time-(86400 * 5);
	
	$db->Query("DELETE FROM `antibot_sessions` WHERE `active`='0'");
	$db->Query("DELETE FROM `jail` WHERE `active`='0'");
	$db->Query("DELETE FROM `temporary` WHERE `active`='0' OR (`time_added`+`expires`) < '".$time."'");
	$db->Query("DELETE FROM `blackjack` WHERE `active`='0' AND `started`<'".$bjdays."'");
	$db->Query("UPDATE `[players]` SET `ruleta`='0' WHERE `ruleta`>'0'");
	$db->Query("UPDATE `[players]` SET `vip_days`='0' WHERE `vip_days`<'".$time."'");
	$db->Query("UPDATE `[players]` SET `s_respect`='0' WHERE `s_respect`>'0'");
	
	$db->Query("TRUNCATE TABLE `sent_respect`");
	$db->Query("DELETE FROM `[log]` WHERE `timestamp`<'".$logdays."' OR `playerid`='0' AND (`side`!='game_panel/user' AND `side`!='game_panel/player')");
}

// Stock Update
$config['businesses_incomePunish'] = 10000;

$sql = $db->Query("SELECT id,current_income,last_income,current_price,changes,business_type,business_id,last_change_time FROM `stocks` WHERE `active`='1'");
while ($stocks = $db->FetchArray($sql))
{
	if ($stocks['last_change_time']+(60*60*24*2)-60 > $time)
	{
		continue;
	}
	
	$newIncome = $stocks['current_income'];
	$oldIncome = $stocks['last_income'];
	$oldPrice = $stocks['current_price'];
	
	if ($newIncome <= 0)
	{
		$newIncome = 1;
		
		if ($stocks['business_type'] == 'game_business')
		{
			$db->Query("UPDATE `businesses` SET `bank`=`bank`-'".$config['businesses_incomePunish']."', `bank_loss`=`bank_loss`+'".$config['businesses_incomePunish']."' WHERE `id`='".$stocks['business_id']."'");
			$db->Query("INSERT INTO `business_log` (`b_id`, `text`, `type`, `added`, `added_date`)VALUES('".$stocks['business_id']."', 'Stockes where changed and company lost ".number_format($config['businesses_incomePunish'], 0, '.', ' ')." $.', '11', '".$time."', '".date('d.m.Y')."')");
		}
	}
	
	$change = round($newIncome/$oldIncome * 100, 6);
	if ($change > 200) $change = 200;
	
	$newPrice = round($config['businesses_default_stockPrice']/100 * $change, 0);
	if ($newPrice <= 0)
		$newPrice = round($change, 0);
	
	if ($newIncome <= $oldIncome)
	{
		$change = round($oldIncome/$newIncome * 100, 6);
		if ($change > 200) $change = 200;
		
		$newPrice = round($config['businesses_default_stockPrice']/100 * $change, 0);
		if ($newPrice <= 0)
			$newPrice = round($change, 0);
		
		$newPrice = $oldPrice - $newPrice;
	}
	else
	{
		$newPrice = $oldPrice + $newPrice;
	}
	
	if ($newPrice < 0)
	{
		$newPrice = 0;
	}
	
	$changes = unserialize($stocks['changes']);
	$changes[] = array(
		'change' => $change,
		'newPrice' => $newPrice,
		'time' => $time
	);
	
	$db->Query("UPDATE `stocks` SET `current_income`='0', `last_income`='".($stocks['current_income'] <= 0 ? $stocks['last_income'] : $newIncome)."', `current_price`='".$newPrice."', `changes`='".serialize($changes)."', `last_change_percent`='".$change."', `last_change_time`='".$time."', `last_price`='".$oldPrice."' WHERE `id`='".$stocks['id']."'");
}
?>