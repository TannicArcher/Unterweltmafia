<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$filter = ($_GET['sd'] != 1 ? " WHERE `health`>'0'" : "");
	
	$sql  = 'SELECT id,rank,vip_days,last_active,level,name,health FROM `[players]`'.$filter.' ORDER BY level DESC, rankpoints DESC';
	$pagination = new Pagination($sql, 20, 'p');
	$pagination_links = $pagination->GetPageLinks();
	$spillere = $pagination->GetSQLRows();
?>
<script type="text/javascript">
function goSelect(selectobj){
 window.location.href='<?=$config['base_url']?>?side=membrii&sd='+selectobj
}
</script>
<h2 class="left"><?=$langBase->get('stats-01')?>: <?=View::CashFormat($pagination->num_rows)?></h2>
<div style="float: right;"><?=$langBase->get('min-38')?>: <select onChange="goSelect(this.value)"><option value=""<?=($_GET['sd'] == '' ? ' selected' : '')?>><?=$langBase->get('txt-49')?></option><option value="1"<?=($_GET['sd'] == 1 ? ' selected' : '')?>><?=$langBase->get('txt-50')?></option></select></div>
<table class="table center" width="90%">
	<thead>
    	<tr>
        	<td width="40%"><?=$langBase->get('txt-06')?></td>
            <td width="22%"><?=$langBase->get('ot-rank')?></td>
            <td width="25%"><?=$langBase->get('ot-lasta')?></td>
            <td width="13%"><?=$langBase->get('subMenu-09')?></td>
        </tr>
    </thead>
    <tbody>
    	
    	<tr class="c_3"><td colspan="4"><?=$pagination_links?></td></tr>
        
    	<?php
			foreach($spillere as $spiller):
				
				$c++;
				$color = ($c%2) ? 1 : 2;
?>
		<tr class="c_<?=$color?>">
        	<td width="40%" class="t_left" style="padding-left: 20px;"><?=View::Player($spiller)?> <?=View::NickAdd($spiller['level'])?></td>
            <td width="22%" class="t_left" style="padding-left: 20px;"><?=$config['ranks'][$spiller['rank']][0]?></td>
            <td width="25%"><span class="toggleHTML" title='<?=View::strTime(time()-$spiller['last_active'], 1, ', ', 0)?>'><?=View::Time($spiller['last_active'])?></span></td>
            <td width="13%"><a href="<?=$config['base_url']?>?side=mesaje&amp;a=ny&amp;nick=<?=$spiller['name']?>"><?=$langBase->get('subMenu-09')?></a></td>
        </tr>
<?php
			endforeach;
		?>
        
        <tr class="c_3"><td colspan="4"><?=$pagination_links?></td></tr>
        
    </tbody>
</table>