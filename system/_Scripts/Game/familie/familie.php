<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	require_once('_fam_check.php');
	
	$family = $db->EscapeString($_GET['id']);
	$sql = $db->Query("SELECT * FROM `[families]` WHERE `id`='".$family."' AND `active`='1'");
	$family = $db->FetchArray($sql);
	
	if ($family['id'] == '')
	{
		View::Message($langBase->get('fam-17'), 2, true, '/game/?side=familie/');
	}
	
	$sql = $db->Query("SELECT id FROM `family_businesses` WHERE `family`='".$family['id']."'");
	$businesses = $db->FetchArrayAll($sql);
	
	$territories = unserialize($family['territories']);
	$territories_num = count($territories);
	
	$members = unserialize($family['members']);
	
	$familySize = $config['family_max_member_types'][$family['max_members_type']];
	$members_places = $familySize[1] - count($members);
	
	$information = new BBCodeParser((trim($family['information']) == '' ? '[i]No Description[/i]' : $family['information']), 'family_info', true);
	
	$invites = unserialize($family['invites']);
	
	if (isset($_POST['invite_accept']) && $invites[Player::Data('id')])
	{
		if (Player::FamilyData('id') != '')
		{
			echo View::Message($langBase->get('fam-06'), 2);
		}
		elseif ($members_places <= 0)
		{
			echo View::Message($langBase->get('fam-18'), 2);
		}
		elseif ($members[Player::Data('id')])
		{
			echo View::Message($langBase->get('fam-06'), 2);
		}
		else
		{
			unset($invites[Player::Data('id')]);
			
			$members[Player::Data('id')] = array(
				'player' => Player::Data('id'),
				'added' => time()
			);
			
			$db->Query("UPDATE `[families]` SET `members`='".serialize($members)."', `invites`='".serialize($invites)."', `total_rankpoints`=`total_rankpoints`+'".Player::Data('rankpoints')."' WHERE `id`='".$family['id']."'");
			$db->Query("UPDATE `[players]` SET `family`='".$family['id']."' WHERE `id`='".Player::Data('id')."'");
			$db->Query("INSERT INTO `family_log` (`family`, `type`, `text`, `added`, `access_level`)VALUES('".$family['id']."', 'new_member', 'The family has a new member - ".View::Player(Player::$datavar, true)."!', '".time()."', '1')");
			
			Accessories::AddLogEvent(Player::Data('id'), 7, array(
				'-FAMILY_IMG-' => $family['image'],
				'-FAMILY_NAME-' => $family['name'],
				'-FAMILY_ID-' => $family['id']
			), User::Data('id'));
			
			View::Message($langBase->get('fam-19'), 1, true);
		}
	}
	elseif (isset($_POST['invite_reject']) && $invites[Player::Data('id')])
	{
		if ($members[Player::Data('id')])
		{
			echo View::Message($langBase->get('fam-06'), 2);
		}
		else
		{
			unset($invites[Player::Data('id')]);

			$db->Query("UPDATE `[families]` SET `invites`='".serialize($invites)."' WHERE `id`='".$family['id']."'");

			View::Message($langBase->get('fam-94'), 1, true);
		}
	}
?>
<div class="left" style="width: 400px;">
	<div class="bg_c" style="width: 380px;">
    	<h1 class="big"><?=$family['name']?></h1>
        <?=$information->result?>
        <div class="hr big" style="margin: 10px 0 10px 0;"></div>
        <table class="table">
        	<thead>
            	<tr><td colspan="2"><?=$langBase->get('fam-16')?></td></tr>
                <tr class="small">
                	<td><?=$langBase->get('txt-02')?></td>
                    <td><?=$langBase->get('fam-20')?></td>
                </tr>
            </thead>
            <tbody>
            <?php
			foreach ($members as $member)
			{
				$i++;
				$c = $i%2 ? 1 : 2;
			?>
            	<tr class="c_<?=$c?>">
                	<td class="center"><?=View::Player(array('id' => $member['player']))?><?php if($member['player'] == $family['boss']) echo ' ('.$langBase->get('fam-21').')';?><?php if($member['player'] == $family['underboss']) echo ' ('.$langBase->get('fam-22').')';?></td>
                    <td class="t_right"><?=View::Time($member['added'], true, 'H:i')?></td>
                </tr>
            <?php
			}
			?>
            	<tr class="c_3"><td colspan="2"></td></tr>
            </tbody>
        </table>
    </div>
</div>
<div class="left" style="width: 230px; margin-left: 10px;">
	<div class="family_logo"><img src="<?=$family['image']?>" alt="" class="handle_image" /></div>
    <div class="bg_c" style="width: 210px;">
    	<p style="margin-top: 0;"><?=$langBase->get('txt-29')?>: <b><?=$familySize[0]?></b><br> <?=$langBase->get('fam-23')?>: <b><?=$members_places?></b><br /><?=$langBase->get('txt-05')?>: <b><?=$config['places'][$family['place']][0]?></b></p>
        <div class="hr big" style="margin: 10px 0 10px 0;"></div>
        <dl class="dd_right">
        	<dt><?=$langBase->get('fam-24')?></dt>
            <dd><?=View::CashFormat($family['player_kills']).' '.strtolower($langBase->get('txt-43'))?></dd>
        </dl>
        <div class="clear"></div>
        <div class="hr big" style="margin: 10px 0 10px 0;"></div>
        <p><?=$langBase->get('fam-25', array('-NUM-' => $territories_num))?></p>
        <?php
		if ($territories_num > 0)
		{
			echo '<ul style="margin: 5px 0 5px 0;">';
			
			foreach ($territories as $territory)
			{
				echo '<li><a href="'.$config['base_url'].'?side=harta&amp;sted='.$territory.'">'.$config['places'][$territory][0].'</a></li>';
			}
			
			echo '</ul>';
		}
		
		if ($members[Player::Data('id')] || Player::Data('level') > 2)
		{
		?>
        <div class="hr big" style="margin: 10px 0 10px 0;"></div>
        <p class="center" style="margin: 15px 0 5px 0;">
        	<a href="<?=$config['base_url']?>?side=familie/e_panel<?php if(!$members[Player::Data('id')] && Player::Data('level') > 2) echo '&amp;f_id=' . $family['id'];?>" class="button"><?=$langBase->get('function-family_ownersPanel')?></a>
            <a href="<?=$config['base_url']?>?side=familie/m_panel" class="button"><?=$langBase->get('fam-16')?></a>
        </p>
        <?php
		}
		
		if ($invites[Player::Data('id')])
		{
		?>
        <div class="hr big" style="margin: 10px 0 10px 0;"></div>
        <p style="margin: 15px 0 5px 0;" class="yellow"><?=$langBase->get('fam-26')?></p>
        <form method="post" action="">
			<p class="center" style="padding: 2px">
				<input type="submit" name="invite_accept" class="button form_submit" value="<?=$langBase->get('fam-27')?>" />
				<input type="submit" name="invite_reject" class="button form_submit" value="<?=$langBase->get('fam-93')?>" />
			</p>
        </form>
        <?php
		}
		?>
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