<div class="script_header">
	<img src="<?=$config['base_url']?>images/script_headers/bordell.jpg" alt="" />
<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$time = time();
	$cBordel['bprice'] = 10000000;
	$cBordel['oras'] = 30;
	$cBordel['bordel'] = 50;
	$cBordel['ractime'] = (Player::Data('vip_days') > 0 ? 900 : 1200);
	$bordel = unserialize(Player::Data('bordel'));
	$colectnum = round((($time - $bordel['ctime'])/3600), 0);
	
	if($bordel['ctime'] < 1 || $bordel['ctime'] == ''){
		$bordel['ctime'] = time();
		$db->Query("UPDATE `[players]` SET `bordel`='".serialize($bordel)."' WHERE `id`='".Player::Data('id')."'");
	}

	if (isset($_POST['racolare']))
	{
		$wanted_level = rand(15,25);
		if (($time - $bordel['ractime']) < $cBordel['ractime'])
		{
			echo View::Message($langBase->get('bordel-11'), 2);
		}
		elseif (rand(0, $config['max_wanted-level']) <= Player::Data('wanted-level')+$wanted_level)
		{
			$penalty = Accessories::SetInJail(Player::Data('id'), $wanted_level);		
			$log_data['jail_penalty'] = $penalty;
					
			View::Message($langBase->get('bordel-23', array('-SEC-' => $penalty)), 2, true);
		}
		else
		{
			$rankPoints = rand($config['bordel_rankpoints_range'][0], $config['bordel_rankpoints_range'][1]);
			$racolat = (Player::Data('vip_days') > 0 ? rand(2,10) : rand(2,5));
			$bordel['oras'] = $bordel['oras'] + $racolat;
			$bordel['ractime'] = $time;
			$db->Query("UPDATE `[players]` SET `rankpoints`=`rankpoints`+'".$rankPoints."', `wanted-level`=`wanted-level`+'".$wanted_level."', `bordel`='".serialize($bordel)."' WHERE `id`='".Player::Data('id')."'");
			
			// Misiune
			if ($player_mission->current_mission == 8 && $player_mission_data['started'] == 1)
			{
				if ($player_mission_data['objects'][0]['completed'] != 1)
				{
					$num = $player_mission_data['objects'][0]['num'] + $racolat;
					$player_mission_data['objects'][0]['num'] = $num;
					$player_mission->missions_data[$player_mission->current_mission]['objects'][0]['num'] = $num;
					$player_mission->saveMissionData();
					
					if ($num >= 200)
					{
						$player_mission->completeObject(0);
					}
				}
			}
			elseif ($player_mission->current_mission == 9 && $player_mission_data['started'] == 1)
			{
				if ($player_mission_data['objects'][4]['completed'] != 1)
				{
					$num = $player_mission_data['objects'][4]['num'] + $racolat;
					$player_mission_data['objects'][4]['num'] = $num;
					$player_mission->missions_data[$player_mission->current_mission]['objects'][4]['num'] = $num;
					$player_mission->saveMissionData();
					
					if ($num >= 50)
					{
						$player_mission->completeObject(4);
					}
				}
			}
			
			View::Message($langBase->get('bordel-12', array('-NUM-' => $racolat)), 1, true);
		}
	}
	elseif (isset($_POST['buyBordel']))
	{
		if ($bordel['hbordel'] > 0)
		{
			echo View::Message('ERROR', 2);
		}
		elseif(Player::Data('cash') < $cBordel['bprice'])
		{
			View::Message($langBase->get('err-01'), 2, true);
		}
		else
		{
			$bordel['hbordel'] = 1;
			$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$cBordel['bprice']."', `bordel`='".serialize($bordel)."' WHERE `id`='".Player::Data('id')."'");
			
			View::Message($langBase->get('bordel-10'), 1, true);
		}
	}
	elseif (isset($_POST['addBordel']))
	{
		$number = $db->EscapeString($_POST['bordelnum']);

		if ($number > $bordel['oras'])
		{
			echo View::Message($langBase->get('bordel-13'), 2);
		}
		elseif ($number < 1)
		{
			echo View::Message($langBase->get('bordel-14'), 2);
		}
		else
		{
			$bordel['oras'] = $bordel['oras'] - $number;
			$bordel['bordel'] = $bordel['bordel'] + $number;
			$db->Query("UPDATE `[players]` SET `bordel`='".serialize($bordel)."' WHERE `id`='".Player::Data('id')."'");
			
			// Misiune
			if ($player_mission->current_mission == 8 && $player_mission_data['started'] == 1)
			{
				if ($player_mission_data['objects'][2]['completed'] != 1)
				{
					$num = $player_mission_data['objects'][2]['num'] + $number;
					$player_mission_data['objects'][2]['num'] = $num;
					$player_mission->missions_data[$player_mission->current_mission]['objects'][2]['num'] = $num;
					$player_mission->saveMissionData();
					
					if ($num >= 100)
					{
						$player_mission->completeObject(2);
					}
				}
			}
						
			View::Message($langBase->get('bordel-15', array('-NUM-' => $number)), 1, true);
		}
	}
	elseif (isset($_POST['collect']))
	{
		if (($bordel['ctime']+3600) > time())
		{
			echo View::Message($langBase->get('bordel-16'), 2);
		}
		else
		{
			$ctotal = ($colectnum * ($bordel['oras'] * $cBordel['oras'])) + ($colectnum * ($bordel['bordel'] * $cBordel['bordel']));
			$bordel['ctime'] = time();
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$ctotal."', `bordel`='".serialize($bordel)."' WHERE `id`='".Player::Data('id')."'");
			
			// Misiune
			if ($player_mission->current_mission == 8 && $player_mission_data['started'] == 1)
			{
				if ($player_mission_data['objects'][1]['completed'] != 1)
				{
					$num = $player_mission_data['objects'][1]['sum'] + $ctotal;
					$player_mission_data['objects'][1]['sum'] = $num;
					$player_mission->missions_data[$player_mission->current_mission]['objects'][1]['sum'] = $num;
					$player_mission->saveMissionData();
					
					if ($num >= 1000000)
					{
						$player_mission->completeObject(1);
					}
				}
			}
			
			View::Message($langBase->get('bordel-17', array('-CASH-' => View::CashFormat($ctotal))), 1, true);
		}
	}
?>
<div style="margin: 0px auto; width: 620px;">
	<div class="left" style="width: 280px;">
    	<div class="bg_c" style="width: 260px;">
        	<h1 class="big"><?=$langBase->get('bordel-01')?></h1>
			<p><?=$langBase->get('bordel-02')?>: <b><?=View::CashFormat($bordel['oras'])?></b></p>
			<p><?=$langBase->get('bordel-03')?>: <b><?=View::CashFormat($bordel['bordel'])?></b></p>
            <div class="hr big" style="margin: 10px 0 10px 0;"></div>
			<?if($bordel['ractime']+$cBordel['ractime'] > time()){?>
			<p><span class="red"><?=$langBase->get('bordel-22', array('-SEC-' => ($bordel['ractime']+$cBordel['ractime']) - time()))?></span></p>
			<?}else{?>
            <form method="post" action="">
            	<p class="center">
                	<input type="submit" name="racolare" value="<?=$langBase->get('bordel-04')?>" />
                </p>
            </form><?}?>
        </div>
    </div>
    <div class="left" style="width: 320px; margin-left: 20px;">
    	<div class="bg_c" style="width: 300px;">
        	<h1 class="big"><?=$langBase->get('bordel-05')?></h1>
			<?if($bordel['hbordel'] < 1 || $bordel['hbordel'] == ''){?>
			<p><?=$langBase->get('bordel-08', array('-PRICE-' => View::CashFormat($cBordel['bprice'])))?></p>
			<form method="POST"><p class="center"><input type="submit" name="buyBordel" value="<?=$langBase->get('bordel-09')?>" /></p></form>
			<?}else{?>
            <form method="post" action="">
                <dl class="dd_right">
                	<dt><?=$langBase->get('bordel-01')?></dt>
                    <dd><input type="text" name="bordelnum" class="flat" value="<?=View::CashFormat($bordel['oras'])?>" /></dd>
                </dl>
                <p class="center clear">
                	<input type="submit" name="addBordel" value="<?=$langBase->get('bordel-07')?>" />
                </p>
            </form><?}?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<div class="bg_c w400" style="margin-top: 0;">
	<h1 class="big"><?=$langBase->get('bordel-18')?></h1>
	<p><?=$langBase->get('bordel-06')?></p>
	<div class="hr big" style="margin: 10px 0 10px 0;"></div>
    <div class="left t_justify" style="width: 190px; padding-right: 10px;  border-right: dashed 2px #191919;">
		<p><?=$langBase->get('bordel-19')?>: <b><?=View::CashFormat(($colectnum * ($bordel['oras'] * $cBordel['oras'])))?> $</b></p>
		<p><?=$langBase->get('bordel-20')?>: <b><?=View::CashFormat(($colectnum * ($bordel['bordel'] * $cBordel['bordel'])))?> $</b></p>
    <div class="clear"></div>
    </div>
    <div class="left t_justify" style="width: 180px; padding-left: 10px;">
	<?if($bordel['ctime']+3600 > time()){?>
		<p><span class="red"><?=$langBase->get('bordel-22', array('-SEC-' => ($bordel['ctime']+3600) - time()))?></span></p>
	<?}else{?>
		<form method="POST"><p class="center"><input type="submit" name="collect" value="<?=$langBase->get('bordel-21')?>" /></p></form>
    <?}?>
	</div>
    <div class="clear"></div>
</div>
</div>