<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	require('_fam_check.php');
	
	if (isset($_GET['create']))
	{
		if (isset($_POST['family__name']) && Player::Data('family') == 0 && Player::Data('kills') >= $config['family_create_min_kills'] && Player::Data('rank') >= $config['family_create_min_rank'])
		{
			$name = trim($db->EscapeString($_POST['family__name']));
			$size_key = $db->EscapeString($_POST['family_size']);
			
			$size = $config['family_max_member_types'][$size_key];
			$price = $size[2];
			$name_valid = Accessories::ValidatePlayername($name);
			
			if ($price > Player::Data('points'))
			{
				echo View::Message($langBase->get('err-09'), 2);
			}
			elseif (!$name_valid)
			{
				echo View::Message($langBase->get('fam-01'), 2);
			}
			elseif (!$size)
			{
				echo View::Message('ERROR', 2);
			}
			elseif ($db->GetNumRows($db->Query("SELECT id FROM `[families]` WHERE `place`='".Player::Data('live')."' AND `active`='1' LIMIT " . $config['max_families_in_each_place'] . "")) >= $config['max_families_in_each_place'])
			{
				echo View::Message($langBase->get('fam-02'), 2);
			}
			elseif ($db->GetNumRows($db->Query("SELECT id FROM `[families]` WHERE `name`='".$name."' AND `active`='1' LIMIT 1")) > 0)
			{
				echo View::Message($langBase->get('fam-03'), 2);
			}
			else
			{
				$members = array(
					Player::Data('id') => array(
						'player' => Player::Data('id'),
						'added' => time()
					)
				);
				
				$db->Query("INSERT HIGH_PRIORITY INTO `[families]` (`name`, `boss`, `max_members_type`, `created`, `members`, `image`, `total_rankpoints`, `place`)VALUES('".$name."', '".Player::Data('id')."', '".$size_key."', '".time()."', '".serialize($members)."', '".$config['family_default_logo']."', '".Player::Data('rankpoints')."', '".Player::Data('live')."')");
				$db->Query("UPDATE `[players]` SET `points`=`points`-'".$price."', `family`='".mysql_insert_id()."' WHERE `id`='".Player::Data('id')."'");
				
				View::Message($langBase->get('fam-04'), 1, true);
			}
		}
?>
<div class="bg_c" style="width: 210px;">
    <h1 class="big"><?=$langBase->get('fam-05')?></h1>
    <?php
    if (Player::Data('family') != 0)
    {
        echo '<h2 class="center">'.$langBase->get('fam-06').'</h2>';
    }
	elseif (Player::Data('rank') < $config['family_create_min_rank'])
	{
		echo '<h2 class="center">'.$langBase->get('fam-07', array('-RANK-' => $config['ranks'][$config['family_create_min_rank']][0])).'</h2>';
	}
    else
    {
    ?>
    <script type="text/javascript">
        <!--
		var prices = [<?php foreach($config['family_max_member_types'] as $key => $value){ echo '"'.$value[2].'"' . ($key != (count($config['family_max_member_types'])-1) ? ', ' : ''); } ?>];
		
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
    <form method="post" action="">
        <dl class="dd_right">
            <dt><?=$langBase->get('txt-02')?></dt>
            <dd><input type="text" name="family__name" class="flat" value="<?=$_POST['family__name']?>" /></dd>
            <dt><?=$langBase->get('fam-08')?></dt>
            <dd><select name="family_size" onchange="setPrice(this.selectedIndex)"><?php foreach($config['family_max_member_types'] as $key => $value){ echo '<option value="'.$key.'">'.$value[0].' - '.$value[1].'</option>'; } ?></select></dd>
            <dt><?=$langBase->get('txt-05')?></dt>
            <dd><b><?=$config['places'][Player::Data('live')][0]?></b></dd>
            <dt><?=$langBase->get('txt-03')?></dt>
            <dd id="fam_price" style="padding-top: 3px;"></dd>
        </dl>
        <p class="center clear">
            <input type="submit" value="<?=$langBase->get('fam-09')?>" />
        </p>
    </form>
    <?php
    }
    ?>
</div>
<?php
	}
	elseif (isset($_GET['b']))
	{
	$fl2 = $db->EscapeString($_GET['fl']);
	$fl = '';
	if($fl2 != '' && ($fl2 == 'travel' || $fl2 == 'hospital' || $fl2 == 'lottery' || $fl2 == 'horserace')){
		$fl = " WHERE `type`='".$fl2."'";
	}
	
		$sql = $db->Query("SELECT id,name,type,guards,family FROM `family_businesses`".$fl." ORDER BY type DESC");
		$businesses = $db->FetchArrayAll($sql);
?>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('fam-10')?></h1>
    <?php
	if (count($businesses) <= 0)
	{
		echo '<p>'.$langBase->get('err-06').'</p>';
	}
	else
	{
	?>
<script type="text/javascript">
function goSelect(selectobj){
 window.location.href='<?=$config['base_url']?>?side=familie/&b&fl='+selectobj
}
</script>
   <div style="float:right;margin-bottom: 7px;"><?=$langBase->get('fam-88')?>: <select onChange="goSelect(this.value)"><option value=""<?=($_GET['fl'] == '' ? ' selected' : '')?>><?=$langBase->get('txt-29')?>...</option><?php foreach($config['family_business_types'] as $key => $value){ echo '<option value="'.$key.'" '.($_GET['fl'] == $key ? ' selected' : '').'>'.$value['title'].'</option>'; } ?></select></div>
    
	<table class="table">
    	<thead>
        	<tr>
            	<td><?=$langBase->get('txt-02')?></td>
                <td><?=$langBase->get('txt-29')?></td>
                <td><?=$langBase->get('fam-11')?></td>
                <td><?=$langBase->get('fam-12')?></td>
            </tr>
        </thead>
        <tbody>
        <?php
		foreach ($businesses as $business)
		{
			$i++;
			$c = $i%2 ? 1 : 2;
			
			$owner = 'N/A';
			if ($business['family'] != 0)
			{
				$fam = $families[$business['family']];
				$owner = '<a href="'.$config['base_url'].'?side=familie/familie&amp;id='.$fam['id'].'">'.$fam['name'].'</a>';
			}
			
			$guards = unserialize($business['guards']);
		?>
        	<tr class="c_<?=$c?>">
            	<td class="center"><?=$business['name']?></td>
                <td class="center"><?=$config['family_business_types'][$business['type']]['title']?></td>
                <td class="center"><?=$owner?></td>
                <td>
                <?php
				if (count($guards) <= 0)
				{
					echo 'N/A';
				}
				else
				{
					echo '<ul style="margin: 0;">';
					
					foreach ($guards as $guard)
					{
						echo '<li>'.View::Player(array('id' => $guard['player']), true).'</li>';
					}
					
					echo '</ul>';
				}
				?>
                </td>
            </tr>
        <?php
		}
		?>
        	<tr class="c_3"><td colspan="4"></td></tr>
        </tbody>
    </table>
    <?php
	}
	?>
</div>
<?php
	}
	elseif (isset($_GET['attacks']))
	{
		$pagination = new Pagination("SELECT id,family,victim,result,time FROM `family_attacks` ORDER BY id DESC", 20, 'p');
		$attacks = $pagination->GetSQLRows();
?>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('fam-13')?></h1>
    <?php
	if (count($attacks) <= 0)
	{
		echo '<p>'.$langBase->get('err-06').'</p>';
	}
	else
	{
	?>
    <table class="table">
    	<thead>
        	<tr>
            	<td><?=$langBase->get('fam-14')?></td>
                <td><?=$langBase->get('fam-15')?></td>
                <td><?=$langBase->get('bj-29')?></td>
                <td><?=$langBase->get('txt-27')?></td>
            </tr>
        </thead>
        <tbody>
        <?php
		foreach ($attacks as $attack)
		{
			$i++;
			$c = $i%2 ? 1 : 2;
			
			$theFam = $families[$attack['family']];
			if (!$theFam)
				$theFam = $db->QueryFetchArray("SELECT id,name FROM `[families]` WHERE `id`='".$attack['family']."'");
			
			$targetFam = $families[$attack['victim']];
			if (!$targetFam)
				$targetFam = $db->QueryFetchArray("SELECT id,name FROM `[families]` WHERE `id`='".$attack['victim']."'");
		?>
        	<tr class="c_<?=$c?>">
            	<td class="center"><a href="<?=$config['base_url']?>?side=familie/familie&amp;id=<?=$theFam['id']?>"><?=$theFam['name']?></a></td>
                <td class="center"><a href="<?=$config['base_url']?>?side=familie/familie&amp;id=<?=$targetFam['id']?>"><?=$targetFam['name']?></a></td>
                <td><?=($attack['result'] == 'success' ? $langBase->get('lc-07') : $langBase->get('lc-08'))?></td>
                <td class="t_right"><?=View::Time($attack['time'], false, 'H:i')?></td>
            </tr>
        <?php
		}
		?>
        	<tr class="c_3">
            	<td colspan="4"><?=$pagination->GetPageLinks()?></td>
            </tr>
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
		$sql = $db->Query("SELECT id,name,members,player_kills,image,max_members_type,boss, (`total_rankpoints`/500+`strength`) as `strength`, place FROM `[families]` WHERE `active`='1' ORDER BY strength DESC");
		$families = $db->FetchArrayAll($sql);
?>
<div class="bg_c w500">
    <h1 class="big"><?=$langBase->get('function-family_overview')?></h1>
    <?php
    if (count($families) <= 0)
    {
        echo '<p>'.$langBase->get('err-06').'</p>';
    }
    else
    {
    ?>
    <table class="table center">
        <thead>
            <tr>
                <td colspan="3"><?=$langBase->get('ot-family')?></td>
                <td><?=$langBase->get('c_orgj-01')?></td>
                <td><?=$langBase->get('fam-16')?></td>
                <td><?=$langBase->get('fam-89')?></td>
            </tr>
        </thead>
        <tbody>
        <?php
		$familyNum = ($pagination->current_page-1) * $perPage;
        foreach ($families as $family)
        {
            $i++;
            $c = $i%2 ? 1 : 2;
            
			/*if (!in_array($family['id'], array(1)))*/
				$familyNum++;
			
            $members_type = $config['family_max_member_types'][$family['max_members_type']];
        ?>
            <tr class="c_<?=$c?>">
            	<td><span style="font-size: 18px;">#<?=$familyNum?></span></td>
                <td><a href="<?=$config['base_url']?>?side=familie/familie&amp;id=<?=$family['id']?>"><img src="<?=$family['image']?>" alt="" height="35" width="35" /></a></td>
                <td><a href="<?=$config['base_url']?>?side=familie/familie&amp;id=<?=$family['id']?>"><?=$family['name']?></a><br /><span class="subtext"><?=$config['places'][$family['place']][0]?></span></td>
                <td><?=View::Player(array('id' => $family['boss']))?></td>
                <td><?=count(unserialize($family['members']))?>/<?=$members_type[1]?></td>
                <td><?=View::CashFormat($family['player_kills'])?></td>
            </tr>
        <?php
        }
        ?>
            <tr class="c_3"><td colspan="6"></td></tr>
        </tbody>
    </table>
    <?php
    }
    ?>
</div>
<?php
	}
?>