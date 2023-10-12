<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	include('_fam_check.php');
	
	$id = isset($_GET['f_id']) && Player::Data('level') == 4 ? $db->EscapeString($_GET['f_id']) : Player::FamilyData('id');
	$sql = $db->Query("SELECT *, (`total_rankpoints`/500+`strength`) as `strength` FROM `[families]` WHERE `id`='".$id."'");
	$family = $db->FetchArray($sql);
	
	if (!Player::FamilyData('id') && Player::Data('level') != 4)
	{
		View::Message($langBase->get('fam-39'), 2, true, '/game/?side=familie/');
	}
	elseif (!in_array(Player::Data('id'), array($family['boss'], $family['underboss'])) && Player::Data('level') != 4)
	{
		View::Message($langBase->get('fam-40'), 2, true, '/game/?side=familie/');
	}
	
	$owner_level = Player::Data('id') == $family['boss'] ? 3 : 2;
	if (Player::Data('level') == 4 && $family['underboss'] != Player::Data('id'))
	{
		$owner_level = 3;
	}
	
	$familySize = $config['family_max_member_types'][$family['max_members_type']];
	
	$territories = unserialize($family['territories']);
	$territories_num = count($territories);
	
	$members = unserialize($family['members']);
	$memberData = $members[Player::Data('id')];
	
	function isGuard($player, $businesses)
	{
		$isGuard = false;
		
		foreach ($businesses as $business)
		{
			$guards = unserialize($business['guards']);
			
			foreach ($guards as $guard)
			{
				if ($guard['player'] == $player)
				{
					$isGuard = $business;
					break;
				}
			}
			
			if ($isGuard !== false)
			{
				break;
			}
		}
		
		return  $isGuard;
	}
	
	$businesses = array();
	$f_businesses = array();
	$sql = $db->Query("SELECT id,name,guards,guard_slots FROM `family_businesses` WHERE `family`='".$family['id']."'");
	while ($business = $db->FetchArray($sql))
	{
		$businesses[$business['id']] = $business;
		$f_businesses[] = $business;
		if (in_array(Player::Data('id'), unserialize($business['guards'])))
			$businesses_is_guard = $business;
	}
	
	$businesses_is_guard = isGuard(Player::Data('id'), $f_businesses);
	
	$options = array(
		'log' => $langBase->get('comp-42'),
		'bank' => $langBase->get('banca-11'),
		'info' => $langBase->get('txt-22'),
		'members' => $langBase->get('fam-16'),
		'donations' => $langBase->get('fam-41'),
		'businesses' => $langBase->get('fam-42')
	);
	if ($owner_level == 3)
	{
		$options['leggned'] = $langBase->get('fam-43');
		$options['angrep'] = $langBase->get('fam-44');
		$options['upgrade'] = $langBase->get('fam-90');
	}
	
	$option_key = $options[$_GET['a']] ? $_GET['a'] : 'log';
	$option = $options[$option_key];
?>
<div class="left" style="width: 400px;">
	<div class="bg_c" style="width: 315px; margin-top: 0;">
        <p class="center" style="margin: 5px 0 5px 0;">
            <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=log<?php if(!empty($_GET['f_id'])) echo '&amp;f_id=' . $family['id'];?>" class="button<?=($option_key == 'log' ? ' active' : '')?>"><?=$langBase->get('comp-42')?></a>
            <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=donations<?php if(!empty($_GET['f_id'])) echo '&amp;f_id=' . $family['id'];?>" class="button<?=($option_key == 'donations' ? ' active' : '')?>"><?=$langBase->get('fam-41')?></a>
            <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=bank<?php if(!empty($_GET['f_id'])) echo '&amp;f_id=' . $family['id'];?>" class="button<?=($option_key == 'bank' ? ' active' : '')?>"><?=$langBase->get('banca-11')?></a>
            <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=members<?php if(!empty($_GET['f_id'])) echo '&amp;f_id=' . $family['id'];?>" class="button<?=($option_key == 'members' ? ' active' : '')?>"><?=$langBase->get('fam-16')?></a>
        </p>
        <p class="center" style="margin: 15px 0 5px 0;">
            <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=info<?php if(!empty($_GET['f_id'])) echo '&amp;f_id=' . $family['id'];?>" class="button<?=($option_key == 'info' ? ' active' : '')?>"><?=$langBase->get('txt-22')?></a>
            <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=businesses<?php if(!empty($_GET['f_id'])) echo '&amp;f_id=' . $family['id'];?>" class="button<?=($option_key == 'businesses' ? ' active' : '')?>"><?=$langBase->get('fam-42')?></a>
            <?php if($owner_level == 3){?><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=angrep<?php if(!empty($_GET['f_id'])) echo '&amp;f_id=' . $family['id'];?>" class="button<?=($option_key == 'angrep' ? ' active' : '')?>"><?=$langBase->get('fam-44')?></a><?php }?>
        </p>
        <?php if ($owner_level == 3):?>
        <p class="center" style="margin: 15px 0 5px 0;">
			<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=upgrade<?php if(!empty($_GET['f_id'])) echo '&amp;f_id=' . $family['id'];?>" class="button<?=($option_key == 'upgrade' ? ' active' : '')?>"><?=$langBase->get('fam-90')?></a>
			<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=leggned<?php if(!empty($_GET['f_id'])) echo '&amp;f_id=' . $family['id'];?>" class="button<?=($option_key == 'leggned' ? ' active' : '')?>"><?=$langBase->get('fam-43')?></a>
        </p>
        <?php endif;?>
    </div>
	<div class="bg_c" style="width: 380px;">
    	<h1 class="big"><?=$option?></h1>
<?php
if ($option_key == 'log')
{
	$sql = "SELECT text,added FROM `family_log` WHERE `family`='".$family['id']."' AND `access_level`<='".$owner_level."' ORDER BY id DESC";
	$pagination = new Pagination($sql, 20, 'p');
	$pagination_links = $pagination->GetPageLinks();
	$events = $pagination->GetSQLRows();
	
	if (count($events) <= 0)
	{
		echo '<p>'.$langBase->get('err-06').'</p>';
	}
	else
	{
		echo '<dl class="dt_90">';
		
		foreach ($events as $event)
		{
			echo '<dt>'.trim(View::Time($event['added'], false, 'H:i', false)).'</dt><dd>'.$event['text'].'</dd>';
		}
		
		echo '</dl><div class="clear"></div>';
		
		echo '<div style="margin: 15px 0 10px 0;">'.$pagination_links.'</div>';
	}
}
elseif ($option_key == 'donations')
{
	$donations = unserialize($family['donations']);
	
	if (count($donations) <= 0)
	{
		echo '<p>'.$langBase->get('err-06').'</p>';
	}
	else
	{
		krsort($donations);
		$output = '';
		
		foreach ($donations as $donation)
		{
			$total_donations_num++;
			$total_donations_amount += $donation['amount'];
			
			$output .= '<dt>'.View::Time($donation['added']).' - '.View::Player(array('id' => $donation['player']), true).'</dt><dd>'.View::CashFormat($donation['amount']).' $</dd>';
		}
		
		echo '<p>'.$langBase->get('fam-41').' ('.View::CashFormat($total_donations_num).'): <b>'.View::CashFormat($total_donations_amount).' $</b></p>';
		
		echo '<dl class="dd_right">'.$output.'</dl><div class="clear"></div>';
	}
}
elseif ($option_key == 'bank')
{
	$surplus = $family['bank_income'] - $family['bank_loss'];
	
	if (isset($_POST['taut_belop']))
	{
		$sum = View::NumbersOnly($db->EscapeString($_POST['taut_belop']));
		
		if ($owner_level != 3)
		{
			echo View::Message($langBase->get('fam-45'));
		}
		elseif ($sum <= 0)
		{
			echo View::Message($langBase->get('comp-51'), 2);
		}
		elseif ($sum > $family['bank'])
		{
			echo View::Message($langBase->get('comp-50'), 2);
		}
		else
		{
			$db->Query("UPDATE `[families]` SET `bank`=`bank`-'".$sum."' WHERE `id`='".$family['id']."'");
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$sum."' WHERE `id`='".Player::Data('id')."'");
			
			View::Message('Ai scos '.View::CashFormat($sum).' $ din banca familiei.', 1, true);
		}
	}
	elseif (isset($_POST['settinn_belop']))
	{
		$sum = View::NumbersOnly($db->EscapeString($_POST['settinn_belop']));
		
		if ($sum <= 0)
		{
			echo View::Message($langBase->get('comp-54'), 2);
		}
		elseif ($sum > Player::Data('cash'))
		{
			echo View::Message($langBase->get('err-01'), 2);
		}
		else
		{
			$db->Query("UPDATE `[families]` SET `bank`=`bank`+'".$sum."' WHERE `id`='".$family['id']."'");
			$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$sum."' WHERE `id`='".Player::Data('id')."'");
			
			View::Message($langBase->get('fam-46', array('-CASH-' => View::CashFormat($sum))), 1, true);
		}
	}
?>
<div class="bg_c c_1" style="width: 300px;">
	<h1 class="big"><?=$langBase->get('function-statistics')?></h1>
    <dl class="dd_right">
    	<dt><?=$langBase->get('fam-47')?></dt>
        <dd><?=View::CashFormat($family['bank'])?> $</dd>
    </dl>
    <div class="clear"></div>
    <div class="hr big" style="margin: 10px 0 10px 0;"></div>
    <dl class="dd_right">
    	<dt><?=$langBase->get('moneda-23')?></dt>
        <dd><?=View::CashFormat($family['bank_income'])?> $</dd>
        <dt><?=$langBase->get('comp-67')?></dt>
        <dd><?=View::CashFormat($family['bank_loss'])?> $</dd>
        <dt><?=$langBase->get('min-24')?></dt>
        <dd><span style="color: #<?=($surplus < 0 ? 'ff0000' : '999999')?>;"><?=View::CashFormat($surplus)?> $</span></dd>
    </dl>
    <div class="clear"></div>
</div>
<div style="width: 320px; margin: 0px auto;">
    <div class="left" style="width: 155px;">
        <div class="bg_c c_1" style="width: 135px; margin-top: 0;">
            <h1 class="big"><?=$langBase->get('banca-52')?></h1>
            <form method="post" action="">
                <dl class="dd_right">
                    <dt><?=$langBase->get('txt-25')?></dt>
                    <dd><input type="text" name="taut_belop" class="flat" style="width: 90px; min-width: 90px;" value="<?=View::CashFormat(View::NumbersOnly($_POST['taut_belop']))?> $" /></dd>
                </dl>
                <p class="center clear">
                	<input type="submit" value="<?=$langBase->get('banca-52')?>" />
                </p>
            </form>
        </div>
    </div>
    <div class="left" style="width: 155px; margin-left: 10px;">
        <div class="bg_c c_1" style="width: 135px; margin-top: 0;">
            <h1 class="big"><?=$langBase->get('banca-54')?></h1>
            <form method="post" action="">
                <dl class="dd_right">
                    <dt><?=$langBase->get('txt-25')?></dt>
                    <dd><input type="text" name="settinn_belop" class="flat" style="width: 90px; min-width: 90px;" value="<?=View::CashFormat(View::NumbersOnly($_POST['settinn_belop']))?> $" /></dd>
                </dl>
                <p class="center clear">
                	<input type="submit" value="<?=$langBase->get('banca-54')?>" />
                </p>
            </form>
        </div>
    </div>
</div>
<div class="clear"></div>
<?php
}
elseif ($option_key == 'info')
{
	if (isset($_POST['family_logo']))
	{
		$logo = $db->EscapeString($_POST['family_logo']);
		$info = $db->EscapeString($_POST['family_info']);
		$valid_exts = array("jpg","jpeg","gif","png");
		$extim = end(explode(".",strtolower(basename($logo))));
		
		if ($logo == '') $logo = $config['family_default_logo'];
		
		if (!preg_match('%\A(?:^((http[s]?|ftp):/)?/?([^:/\s]+)(:([^/]*))?((/\w+)*/)([\w\-.]+[^#?\s]+)(\?([^#]*))?(#(.*))?$)\Z%', $logo) && $logo != $config['family_default_logo'])
		{
			echo View::Message('BAD IMAGE', 2);
		}
		elseif (!in_array($extim,$valid_exts))
		{
			echo View::Message('Imaginea trebuie sa fie in format JPG, PNG sau GIF', 2);
		}
		else
		{
			$db->Query("UPDATE `[families]` SET `information`='".$info."', `image`='".$logo."' WHERE `id`='".$family['id']."'");
			
			View::Message($langBase->get('fam-48'), 1, true);
		}
	}
?>
<form method="post" action="">
	<dl class="form">
    	<dt><?=$langBase->get('comp-93')?></dt>
        <dd><input type="text" name="family_logo" class="styled" style="width: 260px;" value="<?=(isset($_POST['family_logo']) ? $_POST['family_logo'] : $family['image'])?>" /></dd>
        <dt><?=$langBase->get('cautare-02')?></dt>
        <dd><textarea name="family_info" cols="40" rows="10" style="width: 260px; height: 150px;"><?=(isset($_POST['family_info']) ? $_POST['family_info'] : $family['information'])?></textarea></dd>
    </dl>
    <div class="clear"></div>
    <p class="center">
    	<input type="submit" value="<?=$langBase->get('txt-21')?>" />
    </p>
</form>
<?php
}
elseif ($option_key == 'members')
{
	$invites = unserialize($family['invites']);
	
	if (isset($_POST['invite_player']))
	{
		$player = $db->EscapeString(trim($_POST['invite_player']));
		$sql = $db->Query("SELECT id,userid FROM `[players]` WHERE `name`='".$player."' AND `level`>'0' AND `health`>'0'");
		$player = $db->FetchArray($sql);
		
		if ($player['id'] == '')
		{
			echo View::Message($langBase->get('err-02'), 2);
		}
		elseif ($invites[$player['id']])
		{
			echo View::Message($langBase->get('fam-49'), 2);
		}
		elseif ($members[$player['id']])
		{
			echo View::Message($langBase->get('fam-50'), 2);
		}
		else
		{
			$invites[$player['id']] = array(
				'player' => $player['id'],
				'time' => time()
			);
			
			$db->Query("UPDATE `[families]` SET `invites`='".serialize($invites)."' WHERE `id`='".$family['id']."'");
			
			Accessories::AddLogEvent($player['id'], 4, array(
				'-FAMILY_IMG-' => $family['image'],
				'-FAMILY_NAME-' => $family['name'],
				'-FAMILY_ID-' => $family['id']
			), $player['userid']);
			
			View::Message($langBase->get('fam-51'), 1, true);
		}
	}
	elseif (isset($_POST['removeInvite']))
	{
		$invite = $invites[$_POST['removeInvite']];
		
		if (!$invite)
		{
			echo View::Message('ERROR', 2);
		}
		else
		{
			unset($invites[$invite['player']]);
			$db->Query("UPDATE `[families]` SET `invites`='".serialize($invites)."' WHERE `id`='".$family['id']."'");
			
			View::Message($langBase->get('comp-122'), 1, true);
		}
	}
	
	if (isset($_GET['m']) && $members[$_GET['m']])
	{
		$member = $members[$_GET['m']];
		
		$donations = unserialize($family['donations']);
		$member_donations = array();
		
		foreach ($donations as $donation)
		{
			if ($donation['player'] == $member['player'])
			{
				$member_donations[] = $donation;
			}
		}
		
		$guard_business = isGuard($member['player'], $f_businesses);
		
		if (isset($_POST['place_member']) && count($businesses) > 0 && !$guard_business)
		{
			$business_key = $db->EscapeString($_POST['place_member']);
			$business = $businesses[$business_key];
			
			$guards = unserialize($business['guards']);
			
			if (!$business)
			{
				echo View::Message('ERROR', 2);
			}
			elseif (count($guards) >= $business['guard_slots'])
			{
				echo View::Message($langBase->get('fam-18'), 2);
			}
			else
			{
				$guards[$member['player']] = array(
					'player' => $member['player'],
					'added' => time()
				);
				
				$db->Query("UPDATE `family_businesses` SET `guards`='".serialize($guards)."' WHERE `id`='".$business['id']."'");
				
				View::Message(View::Player(array('id' => $member['player']), true) . ' '.$langBase->get('fam-56').' &laquo;'.$business['name'].'&raquo;.', 1, true);
			}
		}
		elseif (isset($_POST['edit_place_member']) && !isset($_POST['remove_guard']) && count($businesses) > 0 && $guard_business)
		{
			$business_key = $db->EscapeString($_POST['edit_place_member']);
			$business = $businesses[$business_key];
			
			$guards = unserialize($business['guards']);
			
			if (!$business)
			{
				echo View::Message('ERROR', 2);
			}
			elseif ($isGuard['id'] == $business['id'])
			{
				echo View::Message($langBase->get('fam-52'), 2);
			}
			elseif ((count($guards) - 1) <= 0)
			{
				echo View::Message($langBase->get('fam-53'), 2);
			}
			elseif (count($guards) >= $business['guard_slots'])
			{
				echo View::Message($langBase->get('fam-18'), 2);
			}
			else
			{
				$guards[$member['player']] = array(
					'player' => $member['player'],
					'added' => time()
				);
				
				$db->Query("UPDATE `family_businesses` SET `guards`='".serialize($guards)."' WHERE `id`='".$business['id']."'");
				
				View::Message(View::Player(array('id' => $member['player']), true) . ' '.$langBase->get('fam-56').' &laquo;'.$business['name'].'&raquo;.', 1, true);
			}
		}
		elseif (isset($_POST['remove_guard']) && $guard_business)
		{
			$guards = unserialize($guard_business['guards']);
			
			$gid = 'unknown';
			foreach ($guards as $key => $value)
			{
				if ($value['player'] == $member['player'])
					$gid = $key;
			}
			
			if ((count($guards) - 1) <= 0)
			{
				echo View::Message($langBase->get('fam-53'), 2);
			}
			else
			{
				unset($guards[$gid]);
				
				$db->Query("UPDATE `family_businesses` SET `guards`='".serialize($guards)."' WHERE `id`='".$guard_business['id']."'");
				
				View::Message(View::Player(array('id' => $member['player']), true) . ' '.$langBase->get('fam-56').' &laquo;'.$guard_business['name'].'&raquo;.', 1, true);
			}
		}
		elseif (isset($_POST['kick_member']) && !in_array($member['player'], array($family['boss'], $family['underboss'])))
		{
			unset($members[$member['player']]);
			
			if ($guard_business)
			{
				$guards = unserialize($guard_business['guards']);
				unset($guards[$member['player']]);
				
				$db->Query("UPDATE `family_businesses` SET `guards`='".serialize($guards)."' WHERE `id`='".$guard_business['id']."'");
			}
			
			$db->Query("UPDATE `[families]` SET `members`='".serialize($members)."' WHERE `id`='".$family['id']."'");
			$db->Query("UPDATE `[players]` SET `family`='0' WHERE `id`='".$member['player']."'");
			$db->Query("INSERT INTO `family_log` (`family`, `type`, `text`, `added`, `access_level`)VALUES('".$family['id']."', 'kicked_member', '".View::Player(Player::$datavar, true)." kicked ".View::Player(array('id' => $member['player']), true).".', '".time()."', '1')");
			
			Accessories::AddLogEvent($member['player'], 5, array(
				'-FAMILY_IMG-' => $family['image'],
				'-FAMILY_NAME-' => $family['name'],
				'-FAMILY_ID-' => $family['id']
			));
			
			View::Message($langBase->get('fam-57', array('-PLAYER-' => View::Player(array('id' => $member['player']), true))), 1, true, '/game/?side='.$_GET['side'].'&a=members');
		}
		elseif (isset($_POST['unset_underboss']) && $member['player'] == $family['underboss'] && Player::Data('id') == $family['boss'])
		{
			$db->Query("UPDATE `[families]` SET `underboss`='0' WHERE `id`='".$family['id']."'");
			$db->Query("INSERT INTO `family_log` (`family`, `type`, `text`, `added`, `access_level`)VALUES('".$family['id']."', 'unset_underboss', '".View::Player(array('id' => $member['player']), true)." no longer deputy.', '".time()."', '1')");
			
			View::Message($langBase->get('fam-54'), 1, true);
		}
		elseif (isset($_POST['set_underboss']) && ($member['player'] != $family['underboss'] && $member['player'] != $family['boss'] && Player::Data('id') == $family['boss']))
		{
			$db->Query("UPDATE `[families]` SET `underboss`='".$member['player']."' WHERE `id`='".$family['id']."'");
			$db->Query("INSERT INTO `family_log` (`family`, `type`, `text`, `added`, `access_level`)VALUES('".$family['id']."', 'set_underboss', '".View::Player(array('id' => $member['player']), true)." is now deputy!', '".time()."', '1')");
			
			View::Message($langBase->get('fam-55'), 1, true);
		}
?>
<div class="left" style="width: 195px;">
	<div class="bg_c c_1" style="width: 180px;">
    	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
        <dl class="dd_right">
        	<dt><?=$langBase->get('txt-06')?></dt>
            <dd><?=View::Player(array('id' => $member['player']))?></dd>
            <dt><?=$langBase->get('fam-20')?></dt>
            <dd><?=View::Time($member['added'])?></dd>
        </dl>
        <div class="clear"></div>
        <div class="hr big" style="margin: 10px 0 10px 0;"></div>
        <p><?=(in_array($member['player'], array($family['boss'], $family['underboss'])) ? ($member['player'] == $family['boss'] ? $langBase->get('fam-58') : $langBase->get('fam-59')) : '')?></p>
        <div class="hr big" style="margin: 10px 0 10px 0;"></div>
        <form method="post" action="" class="center">
        <?php if(!in_array($member['player'], array($family['boss'], $family['underboss']))) echo '<input type="submit" name="kick_member" value="'.$langBase->get('crew-01').'" class="warning" />';?>
        <?php if($member['player'] == $family['underboss'] && Player::Data('id') == $family['boss']) echo '<input type="submit" name="unset_underboss" value="'.$langBase->get('crew-01').' '.$langBase->get('profil-13').'" class="warning" />';?>
        <?php if($member['player'] != $family['underboss'] && $member['player'] != $family['boss'] && Player::Data('id') == $family['boss']) echo '<input type="submit" name="set_underboss" value="'.$langBase->get('fam-60').'" class="warning" />';?>
        </form>
    </div>
    <div class="bg_c c_1" style="width: 180px;">
    	<h1 class="big"><?=$langBase->get('fam-61')?></h1>
        <?php
		if ($guard_business)
		{
		?>
        <p><?=View::Player(array('id' => $member['player']), true)?> <?=$langBase->get('fam-56')?> &laquo;<?=$guard_business['name']?>&raquo;.</p>
        <?php
		if (count($businesses) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
		?>
        <form method="post" action="">
        	<dl class="dd_right">
            	<dt><?=$langBase->get('fam-62')?></dt>
                <dd><select name="edit_place_member">
                <?php
				foreach ($businesses as $key => $business)
				{
					echo '<option value="'.$key.'"'.($_POST['edit_place_member'] === $key ? ' selected="selected"' : '').'>'.$business['name'].'</option>';
				}
				?>
                </select></dd>
            </dl>
            <p class="center clear">
            	<input type="submit" value="<?=$langBase->get('txt-21')?>" />
                <input type="submit" value="<?=$langBase->get('txt-36')?>" class="warning" name="remove_guard" style="margin-left: 5px;" />
            </p>
        </form>
        <?php
		}
		?>
        <?php
		}
		else
		{
		?>
        <div class="hr big" style="margin: 10px 0 10px 0;"></div>
        <?php
		if (count($businesses) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
		?>
        <form method="post" action="">
        	<dl class="dd_right">
            	<dt><?=$langBase->get('fam-62')?></dt>
                <dd><select name="place_member">
                <?php
				foreach ($businesses as $key => $business)
				{
					echo '<option value="'.$key.'"'.($_POST['place_member'] === $key ? ' selected="selected"' : '').'>'.$business['name'].'</option>';
				}
				?>
                </select></dd>
            </dl>
            <p class="center clear">
            	<input type="submit" value="<?=$langBase->get('txt-21')?>" />
            </p>
        </form>
        <?php
		}
		?>
        <?php
		}
		?>
    </div>
</div>
<div class="right" style="width: 175px; margin-left: 5px;">
	<div class="bg_c c_1" style="width: 160px;">
    	<h1 class="big"><?=$langBase->get('fam-41')?></h1>
        <p><?=$langBase->get('fam-41')?>: <b><?=View::CashFormat($member['donations_money'])?> $</b></p>
        <?php
		if (count($member_donations) > 0)
		{
			echo '<div class="hr big" style="margin: 10px 0 10px 0;"></div><dl class="dd_right">';
			
			krsort($member_donations);
			foreach ($member_donations as $donation)
			{
				echo '<dt>'.trim(View::Time($donation['added'], false, 'H:i', false)).'</dt><dd>'.View::CashFormat($donation['amount']).' $</dd>';
			}
			
			echo '</dl><div class="clear"></div>';
		}
		?>
   	</div>
</div>
<div class="clear"></div>
<?php
	}
	else
	{
?>
<table class="table">
	<thead>
    	<tr>
        	<td><?=$langBase->get('txt-02')?></td>
            <td><?=$langBase->get('fam-63')?></td>
            <td><?=$langBase->get('fam-20')?></td>
            <td></td>
        </tr>
    </thead>
    <tbody>
    <?php
	foreach ($members as $m_key => $member)
	{
		$i++;
		$c = $i%2 ? 1 : 2;
		
		$guard = 'N/A';
		$isGuard = isGuard($member['player'], $f_businesses);
		if ($isGuard)
		{
			$guard = $isGuard['name'];
		}
	?>
    	<tr class="c_<?=$c?>">
        	<td><?=View::Player(array('id' => $member['player']))?></td>
            <td class="center"><?=$guard?></td>
            <td class="t_right"><?=View::Time($member['added'])?></td>
            <td class="t_right"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?><?php if(!empty($_GET['f_id'])) echo '&amp;f_id=' . $family['id'];?>&amp;a=<?=$option_key?>&amp;m=<?=$m_key?>">&laquo; <?=$langBase->get('fam-64')?></a></td>
        </tr>
    <?php
	}
	?>
    	<tr class="c_3"><td colspan="4"></td></tr>
    </tbody>
</table>
<div class="hr big" style="margin: 20px 0 0 0;"></div>
<div class="bg_c c_1" style="width: 360px;">
	<h1 class="big"><?=$langBase->get('fam-71')?></h1>
    <?php
	if (count($invites) <= 0)
	{
		echo '<p>'.$langBase->get('err-06').'</p>';
	}
	else
	{
	?>
    <form method="post" action="">
        <table class="table boxHandle">
            <thead>
                <tr class="small">
                    <td><?=$langBase->get('txt-06')?></td>
                    <td><?=$langBase->get('txt-27')?></td>
                </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            
            foreach ($invites as $key => $invite)
            {
                $i++;
                $c = $i%2 ? 1 : 2;
            ?>
                <tr class="c_<?=$c?> boxHandle">
                    <td><input type="radio" name="removeInvite" value="<?=$key?>" /><?=View::Player(array('id' => $invite['player']))?></td>
                    <td class="t_right"><?=View::Time($invite['time'], false, 'H:i')?></td>
                </tr>
            <?php
            }
            ?>
                <tr class="c_3 center">
                    <td colspan="2"><input type="submit" value="<?=$langBase->get('txt-36')?>" /></td>
                </tr>
            </tbody>
        </table>
    </form>
    <?php
	}
	?>
    <div class="bg_c c_2 w200">
    	<h1 class="big"><?=$langBase->get('jaforg-26')?></h1>
        <form method="post" action="">
        	<dl class="dd_right">
            	<dt><?=$langBase->get('txt-06')?></dt>
                <dd><input type="text" name="invite_player" class="flat" value="<?=$_POST['invite_player']?>" /></dd>
            </dl>
            <p class="center clear">
            	<input type="submit" value="<?=$langBase->get('jaforg-26')?>" />
            </p>
        </form>
    </div>
</div>
<?php
	}
}
elseif ($option_key == 'businesses')
{
	$sql = $db->Query("SELECT id,name,type,family,guards,guard_slots,bank_income,bank_loss FROM `family_businesses`");
	$all_businesses = $db->FetchArrayAll($sql);
	
	if (isset($_GET['b']) && $all_businesses[$_GET['b']])
	{
		$business = $all_businesses[$_GET['b']];
		$type = $config['family_business_types'][$business['type']];
		
		$guards = unserialize($business['guards']);
		
		$owner = 'N/A';
		if ($business['family'] != 0)
		{
			$fam = $db->QueryFetchArray("SELECT id,name FROM `[families]` WHERE `id`='".$business['family']."'");
			$owner = '<a href="'.$config['base_url'].'?side=familie/familie&amp;id='.$fam['id'].'">'.$fam['name'].'</a>';
		}
		
		if (isset($_POST['buy_guard']))
		{
			$members = unserialize($family['members']);
			$member = $members[$db->EscapeString($_POST['buy_guard'])];
			
			if (!$member)
			{
				echo View::Message('ERROR', 2);
			}
			elseif (isGuard($member['player'], $f_businesses))
			{
				echo View::Message($langBase->get('fam-52'), 2);
			}
			elseif ($type['buy_price'] > $family['bank'])
			{
				echo View::Message($langBase->get('fam-65'), 2);
			}
			else
			{
				$guards[] = array(
					'player' => $member['player'],
					'added' => time()
				);
				
				$db->Query("UPDATE `[families]` SET `bank`=`bank`-'".$type['buy_price']."', `bank_loss`=`bank_loss`+'".$type['buy_price']."', `strength`=`strength`+'200' WHERE `id`='".$family['id']."'");
				$db->Query("UPDATE `family_businesses` SET `family`='".$family['id']."', `guards`='".serialize($guards)."', `bank_income`='0', `bank_loss`='0', `guard_slots`='2' WHERE `id`='".$business['id']."'");
				$db->Query("INSERT INTO `family_log` (`family`, `type`, `text`, `added`, `access_level`)VALUES('".$family['id']."', 'got_business', 'The family bought &laquo;<b>".$business['name']."</b>&raquo;', '".time()."', '1')");
				
				View::Message($langBase->get('fam-66'), 1, true);
			}
		}
		elseif (isset($_POST['leggned_pass']) && $business['family'] == $family['id'])
		{
			$pass = View::DoubleSalt($db->EscapeString($_POST['leggned_pass']), User::Data('id'));
			
			if ($pass !== User::Data('pass'))
			{
				echo View::Message($langBase->get('txt-20'), 2);
			}
			else
			{
				$db->Query("UPDATE `family_businesses` SET `family`='0', `bank_income`='0', `bank_loss`='0', `guards`='a:0:{}', `guard_slots`='2', `stats`='a:0:{}' WHERE `id`='".$business['id']."'");
				$db->Query("UPDATE `[families]` SET `strength`=`strength`-'200' WHERE `id`='".$family['id']."'");
				
				View::Message($langBase->get('fam-67'), 1, true, '/game/?side='.$_GET['side'].''.(!empty($_GET['f_id']) ? '&amp;f_id=' . $family['id'] : '').'&a='.$option_key);
			}
		}
?>
<div class="left" style="width: 190px;">
    <div class="bg_c c_1" style="width: 170px;">
    	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
        <dl class="dd_right">
        	<dt><?=$langBase->get('txt-02')?></dt>
            <dd><?=$business['name']?></dd>
            <dt><?=$langBase->get('txt-29')?></dt>
            <dd><?=$type['title']?></dd>
            <dt><?=$langBase->get('moneda-01')?></dt>
            <dd><?=$owner?></dd>
        </dl>
        <div class="clear"></div>
        <div class="hr big" style="margin: 10px 0 10px 0;"></div>
        <?php
		if (count($guards) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
			echo '<ul>';
			
			foreach ($guards as $guard)
			{
				echo '<li>' . View::Player(array('id' => $guard['player']), true) . '</li>';
			}
			
			echo '</ul>';
		}
		?>
    </div>
</div>
<div class="left" style="width: 180px; margin-left: 10px;">
	<div class="bg_c c_1" style="width: 160px;">
        <?php
		if ($business['family'] == 0)
		{
		?>
        <form method="post" action="">
        	<dl class="dd_right">
            	<dt><?=$langBase->get('txt-03')?></dt>
                <dd><?=View::CashFormat($type['buy_price'])?> $</dd>
                <dt><?=$langBase->get('fam-61')?></dt>
                <dd>
                	<select name="buy_guard">
                    	<option><?=$langBase->get('cereri-32')?>...</option>
					<?php
					foreach (unserialize($family['members']) as $m_key => $member)
					{
						if (isGuard($member['player'], $f_businesses))
							continue;
						
						$player = $db->Query("SELECT name FROM `[players]` WHERE `id`='".$member['player']."'");
						$player = $db->FetchArray($player);
						
						echo '<option value="'.$m_key.'">'.$player['name'].'</option>';
					}
					?>
                    </select>
                </dd>
            </dl>
            <p class="center clear">
            	<input type="submit" value="<?=$langBase->get('txt-01')?>" />
            </p>
        </form>
        <?php
		}
		elseif ($business['family'] == $family['id'])
		{
		?>
        <form method="post" action="">
		<h1 class="big"><?=$langBase->get('fam-68')?></h1>
        	<dl class="dd_right">
            	<dt><?=$langBase->get('home-02')?></dt>
                <dd><input type="password" name="leggned_pass" class="flat" style="min-width: 100px; width: 100px;" /></dd>
            </dl>
        	<p class="center clear">
            	<input type="submit" class="warning" value="<?=$langBase->get('fam-68')?>" />
            </p>
        </form>
        <?php
		}
		else
		{
		?>
        <p><?=$langBase->get('fam-69')?></p>
        <?php
		}
		?>
    </div>
</div>
<div class="clear"></div>
<?php
if ($business['family'] == $family['id'])
{
	$surplus = $business['bank_income'] - $business['bank_loss'];
	
	if (isset($_POST['buy_guard_s']) && $business['guard_slots'] < $type['max_guard_slots'])
	{
		if ($family['bank'] < $type['guardspot_price'])
		{
			echo View::Message($langBase->get('fam-70'), 2);
		}
		else
		{
			$db->Query("UPDATE `[families]` SET `bank`=`bank`-'".$type['guardspot_price']."', `bank_loss`=`bank_loss`+'".$type['guardspot_price']."' WHERE `id`='".$family['id']."'");
			$db->Query("UPDATE `family_businesses` SET `guard_slots`=`guard_slots`+'1' WHERE `id`='".$business['id']."'");
			
			View::Message('Ati achizitionat camera de paza #'.($business['guard_slots'] + 1).'.', 1, true);
		}
	}
?>
<div class="bg_c c_1" style="width: 360px;">
	<h1 class="big"><?=$langBase->get('fam-72')?></h1>
    <div class="left" style="width: 175px;">
    	<div class="bg_c c_2" style="width: 155px;">
        	<h1 class="big"><?=$langBase->get('fam-73')?></h1>
            <dl class="dd_right">
                <dt><?=$langBase->get('min-22')?></dt>
                <dd><?=View::CashFormat($business['bank_income'])?> $</dd>
                <dt><?=$langBase->get('min-23')?></dt>
                <dd><?=View::CashFormat($business['bank_loss'])?> $</dd>
                <dt><?=$langBase->get('min-24')?></dt>
                <dd><span style="color: #<?=($surplus < 0 ? 'ff0000' : '999999')?>;"><?=View::CashFormat($surplus)?> $</span></dd>
            </dl>
            <div class="clear"></div>
        </div>
    </div>
    <div class="left" style="width: 175px; margin-left: 10px;">
    	<div class="bg_c c_2" style="width: 155px;">
        	<h1 class="big"><?=$langBase->get('fam-61')?></h1>
            <dl class="dd_right">
            	<dt><?=$langBase->get('fam-74')?></dt>
                <dd><?=View::CashFormat($business['guard_slots'])?></dd>
                <dt><?=$langBase->get('fam-75')?></dt>
                <dd><?=View::CashFormat($type['max_guard_slots'])?></dd>
                <dt><?=$langBase->get('txt-03')?></dt>
                <dd><?=View::CashFormat($type['guardspot_price'])?> $</dd>
            </dl>
            <div class="clear"></div>
            <?php if($business['guard_slots'] < $type['max_guard_slots']):?>
            <form method="post" action="">
                <p class="center">
                    <input type="submit" name="buy_guard_s" value="<?=$langBase->get('txt-01')?> #<?=($business['guard_slots'] + 1)?>" />
                </p>
            </form>
            <?php else:?>
            <p><?=$langBase->get('fam-76')?></p>
            <?php endif;?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php
}
?>
<?php
	}
	else
	{
		if (count($all_businesses) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
?>
<table class="table">
	<thead>
    	<tr>
        	<td><?=$langBase->get('txt-02')?></td>
            <td><?=$langBase->get('txt-29')?></td>
            <td><?=$langBase->get('moneda-01')?></td>
            <td></td>
        </tr>
    </thead>
    <tbody>
    <?php
	foreach ($all_businesses as $b_id => $business)
	{
		$i++;
		$c = $i%2 ? 1 : 2;
		
		$owner = 'N/A';
		if ($business['family'] != 0)
		{
			$fam = $families[$business['family']];
			$owner = '<a href="'.$config['base_url'].'?side=familie/familie&amp;id='.$fam['id'].'">'.$fam['name'].'</a>';
		}
	?>
    	<tr class="c_<?=$c?>">
        	<td class="center"><?=$business['name']?></td>
            <td class="center"><?=$config['family_business_types'][$business['type']]['title']?></td>
            <td class="center"><?=$owner?></td>
            <td class="t_right"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$option_key?><?php if(!empty($_GET['f_id'])) echo '&amp;f_id=' . $family['id'];?>&amp;b=<?=$b_id?>"><?=$langBase->get('fam-77')?></a></td>
        </tr>
    <?php
	}
	?>
    	<tr class="c_3"><td colspan="4"></td></tr>
    </tbody>
</table>
<?php
		}
	}
}
elseif ($option_key == 'leggned')
{
	if (isset($_POST['leggned_pass']))
	{
		$pass = View::DoubleSalt($db->EscapeString($_POST['leggned_pass']), User::Data('id'));
		
		if ($pass !== User::Data('pass'))
		{
			echo View::Message($langBase->get('txt-20'), 2);
		}
		else
		{
			$db->Query("UPDATE `[players]` SET `family`='0' WHERE `family`='".$family['id']."'");
			$db->Query("UPDATE `[families]` SET `bank`='0', `active`='0', `territories`='a:0:{}' WHERE `id`='".$family['id']."'");
			$db->Query("UPDATE `family_businesses` SET `family`='0', `bank_income`='0', `bank_loss`='0', `guards`='a:0:{}', `guard_slots`='2' WHERE `family`='".$family['id']."'");
			
			foreach (unserialize($family['members']) as $member)
			{
				Accessories::AddLogEvent($member['player'], 6, array(
					'-FAMILY_IMG-' => $family['image'],
					'-FAMILY_NAME-' => $family['name']
				));
			}
			
			View::Message($langBase->get('fam-78'), 1, true, '/game/?side=familie/');
		}
	}
?>
<form method="post" action="" style="width: 220px; margin: 0px auto; background: #191919; padding: 10px; border: 2px dashed #222222;">
	<dl class="dd_right">
    	<dt><?=$langBase->get('home-02')?></dt>
        <dd><input type="password" name="leggned_pass" class="flat" value="" /></dd>
    </dl>
    <p class="center clear">
    	<input type="submit" value="<?=$langBase->get('txt-36')?>" class="warning" onclick="return confirm('<?=$langBase->get('err-05')?>')" />
    </p>
</form>
<?php
}
elseif ($option_key == 'upgrade')
{
	$actual_size = $config['family_max_member_types'][$family['max_members_type']][1];
	if (isset($_POST['family_size']))
	{
		$size_key = $db->EscapeString($_POST['family_size']);
		
		$size = $config['family_max_member_types'][$size_key];
		$price = $size[2];
		
		if ($price > Player::Data('points'))
		{
			echo View::Message($langBase->get('err-09'), 2);
		}
		elseif (!$size || $size[1] < $actual_size)
		{
			echo View::Message('ERROR!', 2);
		}
		else
		{
			$db->Query("UPDATE `[players]` SET `points`=`points`-'".$price."' WHERE `id`='".Player::Data('id')."'");
			$db->Query("UPDATE `[families]` SET `max_members_type`='".$size_key."' WHERE `id`='".$family['id']."'");

			View::Message($langBase->get('fam-91'), 1, true);
		}
	}
	
	$upgradable = false;
	foreach($config['family_max_member_types'] as $key => $value){
		if($actual_size < $value[1]){
			$upgradable = true;
		}
	}

	if(!$upgradable){
		echo '<p>'.$langBase->get('fam-92').'</p>';
	}else{
?>
<script type="text/javascript">
	<!--
	var prices = [<?php foreach($config['family_max_member_types'] as $key => $value){ if($actual_size < $value[1]){ echo '"'.$value[2].'"' . ($key != (count($config['family_max_member_types'])-1) ? ', ' : ''); } } ?>];
	
	window.addEvent('domready', function()
	{
		setPrice(0);
	});
	
	function setPrice(index)
	{
		return $('fam_price').set('html', number_format(prices[index], 0, '.', ' ') + ' <?=$langBase->get('ot-points')?>');
	}
	-->
</script>
<form method="post" action="" style="width: 220px; margin: 0px auto; background: #191919; padding: 10px; border: 2px dashed #222222;">
	<dl class="dd_right">
		<dt><?=$langBase->get('fam-08')?></dt>
		<dd><select name="family_size" onchange="setPrice(this.selectedIndex)"><?php foreach($config['family_max_member_types'] as $key => $value){ if($actual_size < $value[1]){ echo '<option value="'.$key.'">'.$value[0].' - '.$value[1].'</option>'; } } ?></select></dd>
		<dt><?=$langBase->get('txt-03')?></dt>
		<dd id="fam_price" style="padding-top: 3px;"></dd>
	</dl>
	<p class="center clear">
		<input type="submit" value="<?=$langBase->get('txt-14')?>" onclick="return confirm('<?=$langBase->get('err-05')?>')" />
	</p>
</form>
<?php
}}
elseif ($option_key == 'angrep')
{
	if (isset($_POST['attack_family']) && $family['created']+$config['family_protection_length'] < time() && $family['last_attack']+$config['family_attack_wait'] < time())
	{
		$target = $db->EscapeString(trim($_POST['attack_family']));
		$sql = $db->Query("SELECT id,name,created,bank, (`total_rankpoints`/500+`strength`) as `strength` FROM `[families]` WHERE `name`='".$target."' AND `active`='1' AND `id`!='0'");
		$target = $db->FetchArray($sql);
		
		if ($target['id'] == '')
		{
			echo View::Message('ERROR', 2);
		}
		elseif ($target['id'] == $family['id'])
		{
			echo View::Message($langBase->get('fam-79'), 2);
		}
		elseif ($target['created']+$config['family_protection_length'] >= time())
		{
			echo View::Message($langBase->get('fam-80'), 2);
		}
		else
		{
			if (rand(0, ($family['strength']*0.15)+$target['strength']) <= $family['strength'])
			{
				$strength_lost = rand(20, 40);
				$cash = rand(0, ($target['bank']/2));
				
				$db->Query("INSERT INTO `family_attacks` (`family`, `victim`, `result`, `cash_received`, `strength_lost`, `time`)VALUES('".$family['id']."', '".$target['id']."', 'success', '".$cash."', '".$strength_lost."', '".time()."')");
				
				$db->Query("UPDATE `[families]` SET `strength`=`strength`-'".$strength_lost."', `bank`=`bank`-'".$cash."' WHERE `id`='".$target['id']."'");
				$db->Query("INSERT INTO `family_log` (`family`, `type`, `text`, `added`, `access_level`)VALUES('".$target['id']."', 'attack', '".$family['name']." attacked our family. They stollen <b>".View::CashFormat($cash)." $</b> and we lost <b>".View::CashFormat($strength_lost)." strength points</b>.', '".time()."', '1')");
				
				$db->Query("INSERT INTO `family_log` (`family`, `type`, `text`, `added`, `access_level`)VALUES('".$family['id']."', 'attack', 'The family attacked ".$target['name'].". We won this fight and the family received <b>".View::CashFormat($cash)." $</b>.', '".time()."', '1')");
				$db->Query("UPDATE `[families]` SET `strength`=`strength`+'".($strength_lost/2)."', `bank`=`bank`+'".$cash."', `bank_income`=`bank_income`+'".$cash."', `last_attack`='".time()."' WHERE `id`='".$family['id']."'");
				
				View::Message($langBase->get('fam-81', array('-CASH-' => View::CashFormat($cash))), 1, true);
			}
			else
			{
				$strength_lost = rand(20, 40);
				
				$db->Query("INSERT INTO `family_attacks` (`family`, `victim`, `result`, `cash_received`, `strength_lost`, `time`)VALUES('".$family['id']."', '".$target['id']."', 'fail', '0', '".$strength_lost."', '".time()."')");
				
				$db->Query("INSERT INTO `family_log` (`family`, `type`, `text`, `added`, `access_level`)VALUES('".$target['id']."', 'attack', '".$family['name']." attacked us, but they lost!', '".time()."', '1')");
				
				$db->Query("INSERT INTO `family_log` (`family`, `type`, `text`, `added`, `access_level`)VALUES('".$family['id']."', 'attack', 'We attacked ".$target['name'].". The attack failed and we losed <b>".View::CashFormat($strength_lost)." power</b>.', '".time()."', '1')");
				$db->Query("UPDATE `[families]` SET `last_attack`='".time()."', `strength`=`strength`-'".$strength_lost."' WHERE `id`='".$family['id']."'");
				
				View::Message($langBase->get('fam-82', array('-NUM-' => View::CashFormat($strength_lost))), 2, true);
			}
		}
	}

	echo '<p>'.($family['last_attack'] == 0 ? $langBase->get('fam-83') : $langBase->get('fam-84').' '. View::Time($family['last_attack'], false, 'H:i')).'</p>';

	if ($family['created']+$config['family_protection_length'] >= time())
	{
		echo '<p>'.$langBase->get('fam-85').'</p>';
	}
	elseif ($family['last_attack']+$config['family_attack_wait'] >= time())
	{
		if(isset($_POST['reset_time']) && $family['created']+$config['family_protection_length'] < time() && $family['last_attack']+$config['family_attack_wait'] > time()){
			if(Player::Data('points') < $config['family_attack_reset']){
				View::Message($langBase->get('fam-96', array('-NUM-' => View::CashFormat(Player::Data('points')))), 2, true);
			}else{
				$db->Query("UPDATE `[players]` SET `points`=`points`-'".$config['family_attack_reset']."' WHERE `id`='". Player::Data('id') ."'");
				$db->Query("UPDATE `[families]` SET `last_attack`='0' WHERE `id`='".$family['id']."'");
				
				View::Message($langBase->get('fam-97'), 1, true);
			}
		}
?>
<p class="red"><?=$langBase->get('santaj-07', array('-TIME-' => ($family['last_attack']+$config['family_attack_wait']-time())))?></p>
<div class="bg_c c_1 w200">
	<form method="post" action="">
		<p class="center clear">
			<?=$langBase->get('fam-98', array('-NUM-' => View::CashFormat($config['family_attack_reset'])))?>
		</p>
        <p class="center clear">
            <input type="submit" name="reset_time" value="<?=$langBase->get('fam-95')?>" />
        </p>
    </form>
</div>
<?php
	}
	else
	{
?>
<div class="bg_c c_1 w200">
	<form method="post" action="">
        <dl class="dd_right">
            <dt><?=$langBase->get('ot-family')?></dt>
            <dd><input type="text" class="flat" name="attack_family" value="<?=$_POST['attack_family']?>" /></dd>
        </dl>
        <p class="center clear">
            <input type="submit" value="<?=$langBase->get('fam-86')?>" />
        </p>
    </form>
</div>
<?php
	}
}
?>
    </div>
</div>
<div class="left" style="width: 230px; margin-left: 10px;">
	<div class="family_logo"><img src="<?=$family['image']?>" alt="" class="handle_image" /></div>
    <div class="bg_c" style="width: 210px;">
        <p><?=$langBase->get('fam-20')?>: <?=View::Time($memberData['added'], true)?></p>
        <p><?=$langBase->get('fam-32', array('-CASH-' => View::CashFormat($memberData['donations_money'])))?></p>
        <p><?=$langBase->get('fam-87')?>: <b><?=number_format($family['strength'])?></b></p>
        <div class="hr big" style="margin: 10px 0 10px 0;"></div>
        <dl class="dd_right">
            <dt><?=$langBase->get('fam-21')?></dt>
            <dd><?=View::Player(array('id' => $family['boss']))?></dd>
            <dt><?=$langBase->get('fam-22')?></dt>
            <dd><?=($family['underboss'] == 0 ? 'N/A' : View::Player(array('id' => $family['underboss'])))?></dd>
            <dt><?=$langBase->get('txt-37')?></dt>
            <dd><?=View::Time($family['created'], false, 'H:i')?></dd>
            <dt><?=$langBase->get('fam-16')?></dt>
            <dd><?=count($members)?>/<?=$familySize[1]?></dd>
            <dt><?=$langBase->get('fam-28')?></dt>
            <dd><?=$territories_num?></dd>
            <dt><?=$langBase->get('fam-24')?></dt>
            <dd><?=View::CashFormat($family['player_kills'])?></dd>
        </dl>
        <div class="clear"></div>
    </div>
</div>
<div class="clear"></div>