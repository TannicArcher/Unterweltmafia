<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	//require_once('businessValidator.php');
	
	$firmaer = array();
	$sql = $db->Query("SELECT id,name FROM `businesses` WHERE `active`='1' AND `type`='2'");
	while ($firma = $db->FetchArray($sql))
	{
		$firmaer[$firma['id']] = $firma;
	}
	
	$papers = array();
	$sql = $db->Query("SELECT id,b_id,published,title,description,sold_to,publish_time FROM `newspapers` WHERE `published`='1' ORDER BY id DESC LIMIT 6");
	while ($paper = $db->FetchArray($sql))
	{
		if ($firmaer[$paper['b_id']])
		{
			$papers[$paper['id']] = $paper;
		}
	}
?>
<?php
if (count($papers) > 0)
{
?>
<div style="width: 580px; margin: 0px auto;">
	<?php
	foreach ($papers as $paper)
	{
		$sold_to = unserialize($paper['sold_to']);
	?>
    <div class="bg_c left" style="width: 150px; margin: 10px;">
    	<h1 class="big"><a href="<?=$config['base_url']?>?side=firma/avis&amp;id=<?=$paper['id']?>"><?=View::NoHTML($paper['title'])?></a></h1>
        <dl class="dd_right">
        	<dt><?=$langBase->get('txt-27')?></dt>
            <dd><?=View::Time($paper['publish_time'], false, 'H:i')?></dd>
            <dt><?=$langBase->get('comp-25')?></dt>
            <dd><a href="<?=$config['base_url']?>?side=firma/firma&amp;id=<?=$firmaer[$paper['b_id']]['id']?>"><?=$firmaer[$paper['b_id']]['name']?></a></dd>
            <dt><?=$langBase->get('comp-26')?></dt>
            <dd><?=View::CashFormat(count($sold_to))?></dd>
            <dt><?=$langBase->get('comp-27')?></dt>
            <dd><?=(in_array(Player::Data('id'), $sold_to) ? $langBase->get('ot-yes') : $langBase->get('ot-no'))?></dd>
        </dl>
        <p class="clear"><?=(trim($paper['description']) == '' ? '' : trim($paper['description']))?></p>
    </div>
    <?php
	}
	?>
	<div class="clear"></div>
</div>
<?php
}
?>
<div class="hr big" style="margin: 0;"></div>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('c_firma-12')?></h1>
	<?php
	if (isset($_GET['f']) && $firmaer[$_GET['f']])
	{
		$firma = $firmaer[$_GET['f']];
		$sql = $db->Query("SELECT id,b_id,published,title,description,sold_to FROM `newspapers` WHERE `b_id`='".$firma['id']."' AND `published`='1' ORDER BY id DESC");
		$papers = $db->FetchArrayAll($sql);
		
		if (count($papers) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
		?>
        <p class="center">
        	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>" class="button">&laquo; <?=$langBase->get('ot-back')?></a>
        </p>
        <div style="width: 490px; margin: 0px auto;">
        	<?php
			foreach ($papers as $paper)
			{
				$sold_to = unserialize($paper['sold_to']);
			?>
			<div class="bg_c c_1 left" style="width: 200px; margin: 11px;">
				<h1 class="big"><a href="<?=$config['base_url']?>?side=firma/avis&amp;id=<?=$paper['id']?>"><?=View::NoHTML($paper['title'])?></a></h1>
				<dl class="dd_right">
					<dt><?=$langBase->get('txt-27')?></dt>
					<dd><?=View::Time($paper['published'], false, 'H:i')?></dd>
					<dt><?=$langBase->get('comp-25')?></dt>
					<dd><a href="<?=$config['base_url']?>?side=firma/firma&amp;id=<?=$firmaer[$paper['b_id']]['id']?>"><?=$firmaer[$paper['b_id']]['name']?></a></dd>
					<dt><?=$langBase->get('comp-26')?></dt>
					<dd><?=View::CashFormat(count($sold_to))?></dd>
					<dt><?=$langBase->get('comp-27')?></dt>
					<dd><?=(in_array(Player::Data('id'), $sold_to) ? $langBase->get('ot-yes') : $langBase->get('ot-no'))?></dd>
				</dl>
				<p class="clear"><?=(trim($paper['description']) == '' ? '' : trim($paper['description']))?></p>
			</div>
			<?php
			}
			?>
			<div class="clear"></div>
        </div>
        <?php
		}
	}
	else
	{
		if (count($firmaer) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
		?>
        <form method="get" action="" style="width: 200px; margin: 0px auto;">
        	<input type="hidden" name="side" value="<?=$_GET['side']?>" />
        	<table class="table boxHandle">
            	<thead>
                	<tr class="small">
                    	<td><?=$langBase->get('c_firma-12')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
				foreach ($firmaer as $key => $firma)
				{
					$i++;
					$c = $i%2 ? 1 : 2;
				?>
                	<tr class="c_<?=$c?> boxHandle">
                    	<td><input type="radio" name="f" value="<?=$key?>" /><?=$firma['name']?></td>
                    </tr>
                <?php
				}
				?>
                </tbody>
            </table>
            <p class="center">
            	<input type="submit" value="<?=$langBase->get('comp-28')?>" />
            </p>
        </form>
        <?php
		}
	}
	?>
</div>