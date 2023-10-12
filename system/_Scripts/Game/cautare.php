<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
?>
<div class="main" style="width: 450px">
	<h1 class="heading"><?=$langBase->get('cautare-01')?></h1>
    <form method="get" action="">
    	<input type="hidden" name="side" value="<?=$_GET['side']?>" />
    	<dl class="form dd_l dt_70">
        	<dt><?=$langBase->get('txt-06')?></dt>
            <dd><input type="text" class="styled" name="name" style="width: 200px;" value="<?=$_GET['name']?>" /></dd>
            <dt><?=$langBase->get('cautare-02')?></dt>
            <dd><input type="text" class="styled" name="profiletext" style="width: 330px;" value="<?=$_GET['profiletext']?>" /></dd>
        </dl>
        <p class="center clear">
        	<input type="submit" value="<?=$langBase->get('cautare-01')?>" />
        </p>
    </form>
</div>
<?php
	
	if (isset($_GET['name']) || isset($_GET['profiletext']))
	{
		$name        = $db->EscapeString(trim($_GET['name']));
		$profiletext = $db->EscapeString(trim($_GET['profiletext']));
		
		$sql = "SELECT id,last_active,rank,level,name,health FROM `[players]` ".($name || $profiletext ? 'WHERE' : '')." ".($name ? "`name` LIKE '%".$name."%'" : '')." ".($name && $profiletext ? 'and' : '')." ".($profiletext ? "`profiletext` LIKE '%".$profiletext."%'" : '')."";
		
		$pagination = new Pagination($sql, 20, 'p');
		
		$pagination_links = $pagination->GetPageLinks();
		
		$resultater = $pagination->GetSQLRows();
?>
<p class="center"><?=$langBase->get('cautare-03')?>: <b><?=count($resultater)?></b></p>
<?php
	if (count($resultater) <= 0)
	{
		echo '<h2 class="center">'.$langBase->get('err-06').'</h2>';
		
	}else
	{
?>
<table class="table">
	<thead>
    	<tr>
        	<td width="10%"><?=$langBase->get('txt-06')?></td>
            <td width="10%"><?=$langBase->get('ot-rank')?></td>
            <td width="10%"><?=$langBase->get('ot-lasta')?></td>
        </tr>
    </thead>
    <tbody>
	<?php
		foreach ($resultater as $resultat)
		{
			$i++;
			$color = $i%2 ? 1 : 2;
	?>
    	<tr class="c_<?=$color?>">
        	<td width="10%" style="padding-left: 20px;"><?=View::Player($resultat)?></td>
            <td width="10%" style="padding-left: 20px;"><?=$config['ranks'][$resultat['rank']][0]?></td>
            <td width="10%" style="padding-left: 20px;"><span class="toggleHTML" title='<?=View::time($resultat['last_active'])?>'><?=View::strTime(time() - $resultat['last_active'])?></span></td>
        </tr>
    <?php
		}
	?>
    	<tr class="c_3">
        	<td colspan="3"><?=$pagination_links?></td>
        </tr>
    </tbody>
</table>
<?php
	}
?>
<?php
	}
?>