<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	require('businessValidator.php');
	
	$f_id   =  $db->EscapeString($_GET['id']);
	
	$sql    =  $db->Query("SELECT * FROM `businesses` WHERE `id`='".$f_id."' AND `active`='1'");
	$firma  =  $db->FetchArray($sql);
	
	if ($firma['id'] == '')
	{
		View::Message('ERROR', 2, true, '/game/?side=firma/');
	}
	
	$firma_misc = unserialize($firma['misc']);
	$firmatype = $config['business_types'][$firma['type']];
?>
<div class="bg_c w600">
	<h1 class="big"><?=$firma['name']?></h1>
    
    <p class="center" style="margin-top: 20px;">
    	<a href="<?=$config['base_url']?>?side=firma/" class="button big"><?=$langBase->get('function-companies')?></a>
        <?php if(in_array(Player::Data('id'), array($firma['job_1'], $firma['job_2'])) || Player::Data('level') == 4) echo ' <a href="'.$config['base_url'].'?side=firma/panel&amp;id='.$firma['id'].'&amp;a=main" class="button big">Admin</a>'; ?>
    </p>
    
    <div class="bg_c c_1 left" style="width: 270px;">
    	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
        <dl class="dd_right">
            <dt><?=$langBase->get('txt-02')?></dt>
            <dd><?=$firma['name']?></dd>
            <dt><?=$langBase->get('txt-29')?></dt>
            <dd><?=$firmatype['name'][2]?></dd>
            <dt><?=$langBase->get('comp-03')?></dt>
            <dd><?=($firma['accepts_soknader'] == 1 ? $langBase->get('ot-yes') : $langBase->get('ot-no'))?></dd>
            <dt><?=$langBase->get('txt-05')?></dt>
            <dd><a href="<?=$config['base_url']?>?side=harta&amp;sted=<?=$firma['place']?>"><?=$config['places'][$firma['place']][0]?></a></dd>
            <dt><?=$firmatype['job_titles'][0]?></dt>
            <dd><?=View::Player(array('id' => $firma['job_1']))?></dd>
            <dt><?=$firmatype['job_titles'][1]?></dt>
            <dd><?=View::Player(array('id' => $firma['job_2']), false, 'N/A')?></dd>
            <dt><?=$langBase->get('comp-01')?></dt>
            <dd><?=View::Time($firma['created'], 0)?></dd>
        </dl>
        <div class="clear"></div>
    </div>
    <div class="bg_c c_1 right" style="width: 270px;">
    	<h1 class="big"><?=$langBase->get('comp-05')?></h1>
        <dl class="dd_right">
        <?php
		if ($firma['type'] == 1)
		{
			// Banca
		?>
        	<dt><?=$langBase->get('comp-06')?></dt>
            <dd><?=View::CashFormat($firma_misc['account_price'])?> $</dd>
        	<dt><?=$langBase->get('comp-07')?></dt>
            <dd><?=$firma_misc['transfer_fee']?> %</dd>
            <dt><?=$langBase->get('comp-08')?></dt>
            <dd><?=$db->GetNumRows($db->Query("SELECT id FROM `bank_clients` WHERE `b_id`='".$firma['id']."' AND `active`='1' AND `accepted`='1'"))?></dd>
            <dt><?=$langBase->get('comp-09')?></dt>
            <dd><?=(strstr($firmatype['extra']['rente_types'][$firma_misc['rente_type']], '%s') ? sprintf($firmatype['extra']['rente_types'][$firma_misc['rente_type']], $firma_misc['rente_type_2_value']) : $firmatype['extra']['rente_types'][$firma_misc['rente_type']])?></dd>
        <?php
		}
		?>
        </dl>
        <div class="clear"></div>
        <?php
		if ($firma['type'] == 1 && $firma_misc['rente_type'] == 3)
		{
		?>
        <p class="center"><a href="#" class="button" onclick="$('classes').toggleClass('hidden'); return false;"><?=$langBase->get('comp-10')?></a></p>
        <div id="classes" class="hidden">
        	<table class="table center">
            	<thead>
                	<tr>
                    	<td width="40%"><?=$langBase->get('comp-11')?> $</td>
                        <td width="40%"><?=$langBase->get('comp-12')?> $</td>
                        <td width="20%"><?=$langBase->get('comp-13')?></td>
                    </tr>
                </thead>
                <tbody>
				<?php
                foreach ($firma_misc['rente_classes'] as $class)
                {
					$i++;
					$c = $i%2 ? 1 : 2;
                ?>
                	<tr class="c_<?=$c?>">
                    	<td width="40%"><?=View::CashFormat($class[0])?></td>
                        <td width="40%"><?=View::CashFormat($class[1])?></td>
                        <td width="20%" class="t_right"><?=$class[2]?>%</td>
                    </tr>
                <?php
                }
                ?>
                	<tr class="c_3"><td colspan="3"></td></tr>
            	</tbody>
        	</table>
        </div>
        <?php
		}
		elseif ($firma['type'] == 2)
		{
			$sql = $db->Query("SELECT id,title,sold_to FROM `newspapers` WHERE `b_id`='".$firma['id']."' AND `published`='1' ORDER BY id DESC");
			$papers = $db->FetchArrayAll($sql);
			
			if (count($papers) <= 0)
			{
				echo '<p>'.$langBase->get('err-06').'</p>';
			}
			else
			{
			?>
            <table class="table">
            	<thead>
                	<tr>
                    	<td colspan="2"><?=$langBase->get('comp-14')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
				foreach ($papers as $paper)
				{
					$i++;
					$c = $i%2 ? 1 : 2;
				?>
                	<tr class="c_<?=$c?>">
                    	<td class="center"><a href="<?=$config['base_url']?>?side=firma/avis&amp;id=<?=$paper['id']?>"><?=View::NoHTML($paper['title'])?></a></td>
                        <td class="t_right"><?=(in_array(Player::Data('id'), unserialize($paper['sold_to'])) ? $langBase->get('comp-15') : '')?></td>
                    </tr>
                <?php
				}
				?>
                	<tr class="c_3"><td colspan="2"></td></tr>
                </tbody>
            </table>
            <?php
			}
			
			$journalists = $firma_misc['journalists'];
			
			if (count($journalists) <= 0)
			{
				echo '<p>'.$langBase->get('err-06').'</p>';
			}
			else
			{
			?>
            <table class="table">
            	<thead>
                	<tr>
                    	<td colspan="2"><?=$langBase->get('comp-16')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
				$i = 0;
				
				foreach ($journalists as $journalist)
				{
					$i++;
					$c = $i%2 ? 1 : 2;
				?>
                	<tr class="c_<?=$c?>">
                    	<td class="center"><?=View::Player(array('id' => $journalist['player']))?></td>
                        <td class="t_right"><?=View::Time($journalist['added'], false, 'H:i')?></td>
                    </tr>
                <?php
				}
				?>
                	<tr class="c_3"><td colspan="2"></td></tr>
                </tbody>
            </table>
            <?php
			}
			
			$invites = $firma_misc['journalist_invites'];
			
			if ($invites[Player::Data('id')])
			{
				if (isset($_POST['accept_invite']))
				{
					unset($firma_misc['journalist_invites']);
					$firma_misc['journalists'][Player::Data('id')] = array(
						'player' => Player::Data('id'),
						'added' => time()
					);
					
					$db->Query("UPDATE `businesses` SET `misc`='".serialize($firma_misc)."' WHERE `id`='".$firma['id']."'");
					
					$db->Query("INSERT INTO `business_log` (`b_id`, `text`, `type`, `added`, `added_date`)VALUES('".$firma['id']."', '".View::Player(Player::$datavar, true)." este acum un jurnalist al ziarului1', '9', '".time()."', '".date('d.m.Y')."')");
					
					Accessories::AddLogEvent(Player::Data('id'), 11, array(
						'-COMPANY_IMG-' => $firma['image'],
						'-COMPANY_NAME-' => $firma['name'],
						'-COMPANY_ID-' => $firma['id']
					), User::Data('id'));
					
					View::Message($langBase->get('comp-17'), 1, true);
				}
				elseif (isset($_POST['reject_invite']))
				{
					unset($firma_misc['journalist_invites']);
					$db->Query("UPDATE `businesses` SET `misc`='".serialize($firma_misc)."' WHERE `id`='".$firma['id']."'");
					
					$db->Query("INSERT INTO `business_log` (`b_id`, `text`, `type`, `added`, `added_date`)VALUES('".$firma['id']."', '".View::Player(Player::$datavar, true)." a respins invitatia de jurnalist!', '10', '".time()."', '".date('d.m.Y')."')");
					
					View::Message($langBase->get('comp-18'), 1, true);
				}
			?>
            <div class="hr big" style="margin: 10px 0 10px 0;"></div>
            <p><?=$langBase->get('comp-19')?></p>
            <form method="post" action="">
            	<p class="center">
            		<input type="submit" name="accept_invite" value="<?=$langBase->get('comp-20')?>" /> 
                	<input type="submit" name="reject_invite" value="<?=$langBase->get('comp-21')?>" />
                </p>
            </form>
            <?php
			}
			
			if ($journalists[Player::Data('id')])
			{
			?>
            <div class="hr big" style="margin: 10px 0 10px 0;"></div>
            <p><?=$langBase->get('comp-22')?></p>
            <p class="center" style="margin-top: 15px;">
            	<a href="<?=$config['base_url']?>?side=firma/journalistpanel&amp;f=<?=$firma['id']?>" class="button"><?=$langBase->get('comp-23')?></a>
            </p>
            <?php
			}
		}
		?>
    </div>
    <div class="clear"></div>
    <div class="hr"></div>
    <?php if($firma['image'] != '') echo '<p class="center" style="margin: 20px 0 0 0;"><img src="'.(isset($_POST['preview_image']) ? stripslashes($_POST['preview_image']) : $firma['image']).'" alt="" class="handle_image" /></p>'; ?>
    <div style="margin: 10px 5px 10px 5px;">
	<?php
		$firma['info'] = $firma['info'] == '' ? '[i]N/A[/i]' : $firma['info'];
		$info = new BBCodeParser((isset($_POST['preview_info']) ? stripslashes($_POST['preview_info']) : $firma['info']), 'firmainfo', true);
		echo $info->result;
    ?>
    </div>
</div>