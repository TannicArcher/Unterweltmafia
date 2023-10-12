<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$period = isset($_GET['period']) ? View::NumbersOnly($db->EscapeString($_GET['period'])) : 86400;
	
	$sql = "SELECT victim,result,time,victim_rank,victim_family_name FROM `player_kills` WHERE `result`!='no_find'".($period > 0 ? " AND `time`+'".$period."' > '".time()."'" : '')." ORDER BY id DESC";
	$pagination = new Pagination($sql, 15, 'p');
	$kills = $pagination->GetSQLRows();
	
	$results = array(
		'success' => array('green', $langBase->get('lc-07')),
		'fail' => array('red', $langBase->get('lc-08')),
		'no_find' => array('red', $langBase->get('lc-09'))
	);
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('lc-01')?></h1>
    <form method="get" action="">
    	<input type="hidden" name="side" value="<?=$_GET['side']?>" />
        <input type="hidden" name="p" value="<?=$pagination->current_page?>" />
    	<dl class="dd_right">
        	<dt><?=$langBase->get('lc-02')?></dt>
            <dd><select name="period" onchange="this.form.submit()"><option value="86400"<?php if($period == 86400) echo ' selected="selected"';?>>24 <?=$langBase->get('lc-03')?></option><option value="172800"<?php if($period == 172800) echo ' selected="selected"';?>>48 <?=$langBase->get('lc-03')?></option><option value="604800"<?php if($period == 604800) echo ' selected="selected"';?>>7 <?=$langBase->get('lc-04')?></option><option value="0"<?php if($period == 0) echo ' selected="selected"';?>><?=$langBase->get('txt-41')?></option></select></dd>
        </dl>
        <div class="clear"></div>
    </form>
    <?php
	if (count($kills) <= 0)
	{
		echo '<p>'.$langBase->get('err-06').'</p>';
	}
	else
	{
	?>
    <table class="table">
    	<thead>
        	<tr>
            	<td><?=$langBase->get('lc-05')?></td>
                <td><?=$langBase->get('ot-family')?></td>
                <td><?=$langBase->get('lc-06')?></td>
                <td><?=$langBase->get('txt-27')?></td>
            </tr>
        </thead>
        <tbody>
        <?php
		foreach ($kills as $kill)
		{
			$i++;
			$c = $i%2 ? 1 : 2;
		?>
        	<tr class="c_<?=$c?>">
            	<td><?=View::Player(array('id' => $kill['victim']))?><br /><span class="subtext"><?=(!$config['ranks'][$kill['victim_rank']] ? 'Unknown' : $config['ranks'][$kill['victim_rank']][0])?></span></td>
                <td><?=(empty($kill['victim_family_name']) ? 'N/A' : $kill['victim_family_name'])?></td>
                <td class="center"><span class="<?=$results[$kill['result']][0]?>" style="opacity: 0.6; filter: alpha(opacity=60);"><?=$results[$kill['result']][1]?></span></td>
                <td class="t_right"><?=View::Time($kill['time'])?></td>
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