<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	require_once('_fam_check.php');
	
	if (Player::Data('family') == 0)
	{
		View::Message($langBase->get('fam-39'), 2, true, '/game/?side=familie/');
	}
	
	$family = Player::$familyDatavar;
	
	$familySize = $config['family_max_member_types'][$family['max_members_type']];
	
	$territories = unserialize($family['territories']);
	$territories_num = count($territories);
	
	$members = unserialize($family['members']);
	$memberData = $members[Player::Data('id')];
	
	$businesses = array();
	$sql = $db->Query("SELECT id,name,guards FROM `family_businesses` WHERE `family`='".$family['id']."'");
	while ($business = $db->FetchArray($sql))
	{
		$businesses[] = $business;
	}
	
	$options = array(
		'log' => $langBase->get('comp-42'),
		'donate' => $langBase->get('fam-29'),
		'leave' => $langBase->get('fam-30')
	);
	$option_key = $options[$_GET['a']] ? $_GET['a'] : 'log';
	$option = $options[$option_key];
	
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
	
	$businesses_is_guard = isGuard($memberData['player'], $businesses);
?>
<div class="left" style="width: 400px;">
	<div class="bg_c" style="width: 315px; margin-top: 0;">
        <p class="center" style="margin: 5px 0 5px 0;">
            <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=log" class="button<?=($option_key == 'log' ? ' active' : '')?>"><?=$langBase->get('comp-42')?></a>
            <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=donate" class="button<?=($option_key == 'donate' ? ' active' : '')?>"><?=$langBase->get('fam-29')?></a>
            <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=leave" class="button<?=($option_key == 'leave' ? ' active' : '')?>"><?=$langBase->get('fam-30')?></a>
        </p>
    </div>
	<div class="bg_c" style="width: 380px;">
    	<h1 class="big"><?=$option?></h1>
<?php
if ($option_key == 'log')
{
	$sql = "SELECT text,added FROM `family_log` WHERE `family`='".$family['id']."' AND `access_level`<'2' ORDER BY added DESC";
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
elseif ($option_key == 'donate')
{
	$donations = unserialize($family['donations']);
	
	if (isset($_POST['donate_amount']))
	{
		$amount = View::NumbersOnly($db->EscapeString($_POST['donate_amount']));
		
		if ($amount < 100)
		{
			echo View::Message($langBase->get('fam-31'), 2);
		}
		elseif ($amount > Player::Data('cash'))
		{
			echo View::Message($langBase->get('err-01'), 2);
		}
		else
		{
			$donations[] = array(
				'player' => Player::Data('id'),
				'amount' => $amount,
				'added'  => time()
			);
			
			$memberData['donations_num']++;
			$memberData['donations_money'] += $amount;
			$members[Player::Data('id')] = $memberData;
			
			$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$amount."' WHERE `id`='".Player::Data('id')."'");
			$db->Query("UPDATE `[families]` SET `bank`=`bank`+'".$amount."', `bank_income`=`bank_income`+'".$amount."', `donations`='".serialize($donations)."', `members`='".serialize($members)."' WHERE `id`='".$family['id']."'");
			
			View::Message($langBase->get('fam-32', array('-CASH-' => View::CashFormat($amount))), 1, true);
		}
	}
	
	$my_donations = array();
	foreach ($donations as $donation)
	{
		if ($donation['player'] == Player::Data('id'))
		{
			$my_donations[] = $donation;
		}
	}
?>
<div class="left" style="width: 180px;">
	<div class="bg_c c_1" style="width: 160px;">
    	<h1 class="big"><?=$langBase->get('fam-29')?></h1>
        <form method="post" action="">
        	<dl class="dd_right">
            	<dt><?=$langBase->get('txt-25')?></dt>
                <dd><input type="text" name="donate_amount" class="flat" style="width: 110px; min-width: 110px;" value="<?=View::CashFormat($_POST['donate_amount'])?> $" /></dd>
            </dl>
            <p class="center clear">
            	<input type="submit" value="<?=$langBase->get('fam-29')?>" />
            </p>
        </form>
    </div>
</div>
<div class="left" style="width: 190px; margin-left: 10px;">
	<div class="bg_c c_1" style="width: 170px;">
    	<h1 class="big"><?=$langBase->get('fam-33')?></h1>
        <p><?=$langBase->get('fam-32', array('-CASH-' => View::CashFormat($memberData['donations_money'])))?></p>
        <?php
		if (count($my_donations) > 0)
		{
			echo '<div class="hr big" style="margin: 10px 0 10px 0;"></div><dl class="dd_right">';
			
			krsort($my_donations);
			foreach ($my_donations as $donation)
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
elseif ($option_key == 'leave')
{
	if (isset($_POST['leave_pass']))
	{
		$pass = View::DoubleSalt($db->EscapeString($_POST['leave_pass']), User::Data('id'));
		
		if ($pass !== User::Data('pass'))
		{
			echo View::Message($langBase->get('txt-20'), 2);
		}
		elseif ($family['boss'] == Player::Data('id'))
		{
			echo View::Message($langBase->get('fam-34'), 2);
		}
		else
		{
			unset($members[Player::Data('id')]);
			
			$guard_business = isGuard($memberData['player'], $businesses);
			if ($guard_business)
			{
				$guards = unserialize($guard_business['guards']);
				unset($guards[$member['player']]);
				
				$db->Query("UPDATE `family_businesses` SET `guards`='".serialize($guards)."' WHERE `id`='".$guard_business['id']."'");
			}
			
			$db->Query("UPDATE `[players]` SET `family`='0' WHERE `id`='".Player::Data('id')."'");
			$db->Query("UPDATE `[families]` SET `total_rankpoints`=`total_rankpoints`-'".Player::Data('rankpoints')."', `members`='".serialize($members)."'".($memberData['player'] == $family['underboss'] ? ', `underboss`=\'0\'' : '')." WHERE `id`='".$family['id']."'");
			$db->Query("INSERT INTO `family_log` (`family`, `type`, `text`, `added`, `access_level`)VALUES('".$family['id']."', 'player_left', '".View::Player(Player::$datavar, true)." left the family!', '".time()."', '1')");
			
			Accessories::AddLogEvent(Player::Data('id'), 8, array(
				'-FAMILY_IMG-' => $family['image'],
				'-FAMILY_NAME-' => $family['name'],
				'-FAMILY_ID-' => $family['id']
			), User::Data('id'));
			
			View::Message($langBase->get('fam-35'), 1, true, '/game/?side=familie/');
		}
	}
?>
<form method="post" action="" style="width: 220px; margin: 0px auto; background: #191919; padding: 10px; border: 2px dashed #222222;">
	<dl class="dd_right">
    	<dt><?=$langBase->get('home-02')?></dt>
        <dd><input type="password" name="leave_pass" class="flat" value="" /></dd>
    </dl>
    <p class="center clear">
    	<input type="submit" value="<?=$langBase->get('fam-36')?>" class="warning" onclick="return confirm('<?=$langBase->get('err-05')?>')" />
    </p>
</form>
<?php
}
?>
    </div>
</div>
<div class="left" style="width: 230px; margin-left: 10px;">
	<div class="family_logo"><img src="<?=$family['image']?>" alt="" class="handle_image" /></div>
    <div class="bg_c" style="width: 210px;">
        <p><?=($businesses_is_guard == false ? $langBase->get('fam-37') : $langBase->get('fam-38'))?></p>
        <p><?=$langBase->get('fam-32', array('-CASH-' => View::CashFormat($memberData['donations_money'])))?></p>
        <div class="hr big" style="margin: 10px 0 10px 0;"></div>
        <dl class="dd_right">
            <dt><?=$langBase->get('fam-21')?></dt>
            <dd><?=View::Player(array('id' => $family['boss']))?></dd>
            <dt><?=$langBase->get('fam-22')?></dt>
            <dd><?=($family['underboss'] == 0 ? 'N/A' : View::Player(array('id' => $family['underboss'])))?></dd>
            <dt><?=$langBase->get('comp-01')?></dt>
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