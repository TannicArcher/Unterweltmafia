<div class="script_header">
	<img src="<?=$config['base_url']?>images/script_headers/kopfzahl.jpg" alt="" />
<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	if (isset($_GET['oversikt']))
	{
		$firmaer = array();
		$owners = array();
		
		$sql = $db->Query("SELECT id,owner,place,max_bet,win_chance,created FROM `coinroll` WHERE `active`='1' ORDER BY id DESC");
		while ($firma = $db->FetchArray($sql))
		{
			$owner = $db->QueryFetchArray("SELECT id,name,level,health FROM `[players]` WHERE `id`='".$firma['owner']."'");

			if ($owner['level'] <= 0 || $owner['health'] <= 0)
			{
				$db->Query("UPDATE `coinroll` SET `active`='0' WHERE `id`='".$firma['id']."'");
			}
			else
			{
				$firmaer[] = $firma;
				$owners[$firma['id']] = $owner;
			}
		}
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('subMenu-17')?></h1>
    <?php
	if (count($firmaer) <= 0)
	{
		echo '<p>'.$langBase->get('err-06').'</p>';
	}
	else
	{
?>
	<table class="table">
    	<thead>
        	<tr>
            	<td><?=$langBase->get('ot-city')?></td>
                <td><?=$langBase->get('moneda-01')?></td>
                <td><?=$langBase->get('moneda-02')?></td>
				<td><?=$langBase->get('moneda-19')?></td>
                <td><?=$langBase->get('txt-27')?></td>
            </tr>
        </thead>
        <tbody>
        <?php
		foreach ($firmaer as $firma)
		{
		?>
        	<tr class="c_<?=($i++%2 ? 1 : 2)?>">
            	<td class="center"><a href="<?=$config['base_url']?>?side=harta&amp;sted=<?=$firma['place']?>"><?=$config['places'][$firma['place']][0]?></a></td>
                <td><?=View::Player($owners[$firma['id']])?></td>
                <td class="center"><?=View::CashFormat($firma['max_bet'])?> $</td>
				<td class="center"><?=$firma['win_chance']?> %</td>
                <td class="t_right"><?=View::Time($firma['created'], false, 'H:i')?></td>
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
		$firma = $db->QueryFetchArray("SELECT * FROM `coinroll` WHERE `place`='".Player::Data('live')."' AND `active`='1'");

		if ($firma['id'] != '')
		{
			$firma_owner = $db->QueryFetchArray("SELECT id,name,level,health,userid FROM `[players]` WHERE `id`='".$firma['owner']."'");

			if ($firma_owner['level'] <= 0 || $firma_owner['health'] <= 0)
			{
				$db->Query("UPDATE `coinroll` SET `active`='0' WHERE `id`='".$firma['id']."'");
				unset($firma_owner, $firma);
			}
		}
		
		if ($firma['id'] == '')
		{
			if (isset($_POST['cr_buy']))
			{
				if (Player::Data('points') < $config['coinroll_price'])
				{
					echo View::Message($langBase->get('err-09'), 2);
				}
				else
				{
					$db->Query("UPDATE `[players]` SET `points`=`points`-'".$config['coinroll_price']."' WHERE `id`='".Player::Data('id')."'");
					
					$db->Query("INSERT INTO `coinroll` (`owner`, `place`, `max_bet`, `bank`, `created`)VALUES('".Player::Data('id')."', '".Player::Data('live')."', '".$config['coinroll_default_max_bet']."', '".$config['coinroll_default_bank']."', '".time()."')");
					
					View::Message($langBase->get('moneda-03'), 1, true);
				}
			}
?>
<div class="bg_c w300">
	<h1 class="big"><?=$langBase->get('moneda-04')?></h1>
    <p><?=$langBase->get('moneda-05', array('-CITY-' => $config['places'][Player::Data('live')][0], '-COINS-' => View::CashFormat($config['coinroll_price'])))?></p>
    <form method="post" action="">
    	<p class="center"><input type="submit" name="cr_buy" value="<?=$langBase->get('txt-01')?>" /></p>
    </form>
</div>
<?php
	}
	else
	{
		if (isset($_POST['cr_bet']) && $firma_owner['id'] != Player::Data('id'))
		{
			$bet = View::NumbersOnly($db->EscapeString($_POST['cr_bet']));
			
			if ($bet <= 10)
			{
				echo View::Message($langBase->get('moneda-06'), 2);
			}
			elseif ($bet > $firma['max_bet'])
			{
				echo View::Message($langBase->get('moneda-07', array('-CASH-' => View::CashFormat($firma['max_bet']))), 2);
			}
			elseif ($bet > Player::Data('cash'))
			{
				echo View::Message($langBase->get('err-01'), 2);
			}
			else
			{
				if (rand(0, 1000) <= ($firma['win_chance']*10))
				{

					if ($firma['bank']-$bet <= 0)
					{
						$db->Query("UPDATE `coinroll` SET `active`='0' WHERE `id`='".$firma['id']."'");
						
						$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$bet."' WHERE `id`='".Player::Data('id')."'");
						$db->Query("INSERT HIGH_PRIORITY INTO `coinroll` (`owner`, `place`, `max_bet`, `bank`, `created`)VALUES('".Player::Data('id')."', '".Player::Data('live')."', '".$config['coinroll_default_max_bet']."', '".$bet."', '".time()."')");
						
						Accessories::AddLogEvent($firma_owner['id'], 47, array(
							'-PLACE-' => $config['places'][Player::Data('live')][0]
						), $firma_owner['userid']);
						
						$messageType = 1;
						$message = $langBase->get('moneda-08', array('-CASH-' => View::CashFormat($bet)));
					}
					else
					{
						$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$bet."' WHERE `id`='".Player::Data('id')."'");
						$db->Query("UPDATE `coinroll` SET `bank`=`bank`-'".$bet."', `bank_loss`=`bank_loss`+'".$bet."' WHERE `id`='".$firma['id']."'");
						
						$messageType = 1;
						$message = $langBase->get('moneda-09', array('-CASH-' => View::CashFormat($bet)));
					}
				}
				else
				{

					$toBusiness = round($bet/100 * 75, 0);
					$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$bet."' WHERE `id`='".Player::Data('id')."'");
					$db->Query("UPDATE `coinroll` SET `bank`=`bank`+'".$toBusiness."', `bank_income`=`bank_income`+'".$toBusiness."' WHERE `id`='".$firma['id']."'");
					
					$messageType = 2;
					$message = $langBase->get('moneda-10', array('-CASH-' => View::CashFormat($bet)));
				}
				
				$abData = unserialize(Player::Data('antibot_data'));
				$abData['kastmynt']--;
				if ($abData['kastmynt'] <= 0)
				{
					$abData['kastmynt'] = rand($config['antibot_next_range']['kastmynt'][0], $config['antibot_next_range']['kastmynt'][1]);
					$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
					
					Accessories::CreateAntibotSession(Player::Data('id'), $db->EscapeString($_GET['side']));
					
					View::Message($message, $messageType, true);
				}
				$db->Query("UPDATE `[players]` SET `antibot_data`='".serialize($abData)."' WHERE `id`='".Player::Data('id')."'");
				
				View::Message($message, $messageType, true);
			}
		}
		elseif ($firma_owner['id'] == Player::Data('id'))
		{
			if (isset($_POST['bank_settinn']))
			{
				$amount = View::NumbersOnly($db->EscapeString($_POST['bank_settinn']));
				
				if ($amount <= 0)
				{
					echo View::Message($langBase->get('moneda-11'), 2);
				}
				elseif ($amount > Player::Data('cash'))
				{
					echo View::Message($langBase->get('err-01'), 2);
				}
				else
				{
					$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$amount."' WHERE `id`='".Player::Data('id')."'");
					$db->Query("UPDATE `coinroll` SET `bank`=`bank`+'".$amount."' WHERE `id`='".$firma['id']."'");
					
					View::Message($langBase->get('moneda-12', array('-CASH-' => View::CashFormat($amount))), 1, true);
				}
			}
			elseif ($_POST['bank_taut'])
			{
				$amount = View::NumbersOnly($db->EscapeString($_POST['bank_taut']));
				
				if ($amount <= 0)
				{
					echo View::Message($langBase->get('moneda-13'), 2);
				}
				elseif ($amount > $firma['bank'])
				{
					echo View::Message($langBase->get('err-10'), 2);
				}
				else
				{
					$db->Query("UPDATE `coinroll` SET `bank`=`bank`-'".$amount."' WHERE `id`='".$firma['id']."'");
					$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$amount."' WHERE `id`='".Player::Data('id')."'");
					
					View::Message($langBase->get('moneda-14', array('-CASH-' => View::CashFormat($amount))), 1, true);
				}
			}
			elseif ($_POST['max_bet_change'])
			{
				$max_bet = View::NumbersOnly($db->EscapeString($_POST['max_bet_change']));
				$win_chance = View::NumbersOnly($db->EscapeString($_POST['win_chance']));
				
				if ($max_bet < $config['coinroll_min_max_bet'])
				{
					echo View::Message($langBase->get('moneda-15', array('-CASH-' => View::CashFormat($config['coinroll_min_max_bet']))), 2);
				}
				elseif ($win_chance < 40 || $win_chance > 60)
				{
					echo View::Message($langBase->get('moneda-20'), 2);
				}
				else
				{
					$db->Query("UPDATE `coinroll` SET `max_bet`='".$max_bet."', `win_chance`='".$win_chance."' WHERE `id`='".$firma['id']."'");
					
					View::Message($langBase->get('moneda-16', array('-CASH-' => View::CashFormat($max_bet), '-PRC-' => $win_chance)), 1, true);
				}
			}
		}
?>
<div class="bg_c w300">
	<h1 class="big"><?=$config['places'][Player::Data('live')][0]?> - <?=$langBase->get('moneda-00')?></h1>
    <p><?=$langBase->get('moneda-17')?> <?=View::Player($firma_owner)?>!</p>
    <div class="hr big" style="margin: 10px 0 10px 0;"></div>
    <?php
	if ($firma_owner['id'] == Player::Data('id'))
	{
		echo '<p>'.$langBase->get('moneda-18').'</p>';
	}
	else
	{
	?>
    <form method="post" action="">
    	<dl class="dd_right">
        	<dt><?=$langBase->get('moneda-19')?></dt>
            <dd><?=View::CashFormat($firma['win_chance'])?> %</dd>
            <dt><?=$langBase->get('moneda-21')?></dt>
            <dd><?=View::CashFormat($firma['max_bet'])?> $</dd>
            <dt><?=$langBase->get('txt-25')?></dt>
            <dd><input type="text" class="flat" name="cr_bet" value="<?=View::CashFormat(View::NumbersOnly($_POST['cr_bet']))?> $" /></dd>
        </dl>
        <p class="center clear">
        	<input type="submit" value="<?=$langBase->get('txt-14')?>" />
        </p>
    </form>
    <?php
	}
	?>
</div>
<?php
if ($firma_owner['id'] == Player::Data('id'))
{
?>
<div class="bg_c w300">
	<h1 class="big"><?=$langBase->get('moneda-22')?></h1>
    <h2 class="center" style="margin-bottom: 0;"><?=$config['places'][Player::Data('live')][0]?> - <?=$langBase->get('moneda-00')?></h2>
    <p class="center dark small" style="margin-top: 0;"><?=View::Time($firma['created'])?></p>
    <form method="post" action="">
        <dl class="dd_right" style="width: 200px; margin: 10px auto;">
            <dt><?=$langBase->get('moneda-21')?></dt>
            <dd><input type="text" name="max_bet_change" class="flat" value="<?=(View::CashFormat(View::NumbersOnly(isset($_POST['max_bet_change']) ? $_POST['max_bet_change'] : $firma['max_bet'])))?> $" /></dd>
            <dt><?=$langBase->get('moneda-19')?></dt>
            <dd><input type="text" name="win_chance" class="flat" value="<?=(View::CashFormat(View::NumbersOnly(isset($_POST['win_chance']) ? $_POST['win_chance'] : $firma['win_chance'])))?> %" /></dd>
			<dt></dt>
            <dd><input type="submit" value="<?=$langBase->get('txt-21')?>" /></dd>
        </dl>
    </form>
    <div class="clear"></div>
    <div class="hr big" style="margin: 10px;"></div>
    <dl class="dd_right" style="width: 200px; margin: 10px auto;">
    	<dt><?=$langBase->get('moneda-23')?></dt>
        <dd><?=View::CashFormat($firma['bank_income'])?> $</dd>
        <dt><?=$langBase->get('moneda-24')?></dt>
        <dd><?=View::CashFormat($firma['bank_loss'])?> $</dd>
        <dt><?=$langBase->get('moneda-25')?></dt>
        <dd><?=(View::CashFormat($firma['bank_income']-$firma['bank_loss']))?> $</dd>
    </dl>
    <div class="clear"></div>
    <dl class="dd_right" style="width: 200px; margin: 10px auto;">
    	<dt><?=$langBase->get('banca-45')?></dt>
        <dd><?=View::CashFormat($firma['bank'])?> $</dd>
    </dl>
    <div class="clear"></div>
    <form method="post" action="">
    	<dl class="dd_right" style="width: 200px; margin: 10px auto;">
            <dt><?=$langBase->get('banca-54')?></dt>
            <dd><input type="text" name="bank_settinn" class="flat" value="<?=View::CashFormat(View::NumbersOnly($_POST['panel_taut']))?> $" /></dd>
            <dt></dt>
            <dd><input type="submit" value="<?=$langBase->get('banca-54')?>" /></dd>
        </dl>
        <div class="clear"></div>
    </form>
    <form method="post" action="">
    	<dl class="dd_right" style="width: 200px; margin: 10px auto;">
            <dt><?=$langBase->get('banca-52')?></dt>
            <dd><input type="text" name="bank_taut" class="flat" value="<?=View::CashFormat(View::NumbersOnly($_POST['panel_taut']))?> $" /></dd>
            <dt></dt>
            <dd><input type="submit" value="<?=$langBase->get('banca-52')?>" /></dd>
        </dl>
        <div class="clear"></div>
    </form>
</div>
<?php
}}}
?>
</div>