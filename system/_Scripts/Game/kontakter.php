<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$options = array('contact', 'block', 'new');
	$option = $_GET['a'];
	
	
	if (!in_array($option, $options))
	{
		header('Location: /game/?side='.$_GET['side'].'&a=contact');
		exit;
	}
	
	
	if ($option == 'contact' || $option == 'block')
	{
		$type = $option == 'contact' ? 1 : 2;
		
		$sql = "SELECT id,contact_id,info,added FROM `contacts` WHERE `userid`='".User::Data('id')."' AND `type`='$type' ORDER BY id DESC";
		
		$pagination = new Pagination($sql, 15, 'p');
		$pagination_links = $pagination->GetPageLinks();
		$contacts = $pagination->GetSQLRows();	
?>
<div class="bg_c w600">
	<h1 class="big"><?=($option == 'contact' ? $langBase->get('subMenu-12') : $langBase->get('subMenu-13'))?></h1>
	<?php
		if (count($contacts) <= 0)
		{
			echo '<h2 class="center">'.($option == 'contact' ? $langBase->get('err-06') : $langBase->get('err-06')).'</h2>';
		}
		else
		{
			if (isset($_POST['c_id']))
			{
				$deleted = 0;
				
				foreach ($_POST['c_id'] as $c_id => $contact)
				{
					$contact = $contacts[$c_id];
					
					unset($contacts[$c_id]);
					$db->Query("DELETE FROM `contacts` WHERE `id`='".$contact['id']."'");
					
					$deleted++;
				}
				
				View::Message($langBase->get('txt-53'), 1, true);
			}
?>
<form method="post" action="">
	<table class="table boxHandle">
    	<thead>
        	<tr>
            	<td width="10%"><?=$langBase->get('txt-06')?></td>
                <td width="10%"><?=$langBase->get('cautare-02')?></td>
                <td width="10%"><?=$langBase->get('txt-27')?></td>
            </tr>
        </thead>
        <tbody>
        	<?php
			foreach ($contacts as $c_id => $contact)
			{
				$i++;
				$c = $i%2 ? 1 : 2;
				
				$sql = $db->Query("SELECT id,level,name,health FROM `[players]` WHERE `userid`='".$contact['contact_id']."' ORDER BY id DESC");
				$player = $db->FetchArray($sql);
			?>
            <tr class="c_<?=$c?> boxHandle">
            	<td width="25%" class="center"><input type="checkbox" name="c_id[]" value="<?=$c_id?>" /><?=View::Player($player)?></td>
                <td width="45%"><?=nl2br(View::NoHTML($contact['info']));?></td>
                <td width="25%" class="t_right"><span class="toggleHTML" title='<?=View::strTime(time()-$contact['added'], 1, ', ', 0)?>'><?=View::Time($contact['added'])?></span></td>
            </tr>
            <?php
			}
			?>
            <tr class="c_3"><td colspan="3"><?=$pagination_links?></td></tr>
        </tbody>
    </table>
    <p class="center"><input type="submit" value="<?=$langBase->get('txt-36')?>" /></p>
</form>
<?php
		}
?>
</div>
<?php
	}elseif ($option == 'new')
	{
		$type    = $_GET['type'] == 'block' ? 'block' : 'contact';
		$typenum = $type == 'contact' ? 1 : 2;
		
		if (isset($_POST['exit']))
		{
			header('Location: /game/?side=' . (isset($_GET['name']) ? 'spillerprofil&name='.$_GET['name'] : 'kontakter&a='.$type));
			exit;
		}
		
		if (isset($_POST['newContact']))
		{
			$type    = $_POST['newContact']['type'] == 'block' ? 'block' : 'contact';
			$typenum = $type == 'contact' ? 1 : 2;
			
			$contact = $db->EscapeString($_POST['newContact']);
			
			$sql = $db->Query("SELECT id,userid,level,name,health FROM `[players]` WHERE `name`='".$contact['name']."'");
			$player = $db->FetchArray($sql);
			
			if ($player['userid'] == '')
			{
				echo View::Message($langBase->get('err-01'), 2);
			}
			elseif ($player['userid'] == User::Data('id'))
			{
				echo View::Message($langBase->get('contacte-01'), 2);
			}
			elseif ($db->GetNumRows($db->Query("SELECT id FROM `contacts` WHERE `userid`='".User::Data('id')."' AND `contact_id`='".$player['userid']."' AND `type`='$typenum'")) > 0)
			{
				echo View::Message($langBase->get('contacte-02'), 2);
			}
			else
			{
				$db->Query("INSERT INTO `contacts` (`userid`, `contact_id`, `info`, `type`, `added`)VALUES('".User::Data('id')."', '".$player['userid']."', '".$contact['info']."', '$typenum', '".time()."')");
				
				View::Message($langBase->get('contacte-03'), 1, true);
			}
		}
?>
<div class="bg_c w300">
	<h1 class="big"><?=$langBase->get('subMenu-14')?> <?=($type == 'block' ? $langBase->get('subMenu-11') : $langBase->get('subMenu-10'))?></h1>
    <form method="post" action="">
    	<dl class="form">
        	<dt><?=$langBase->get('txt-06')?></dt>
            <dd><input type="text" class="styled" name="newContact[name]" value="<?=(isset($_POST['newContact']['name']) ? $_POST['newContact']['name'] : $_GET['name'])?>" /></dd>
            <dt><?=$langBase->get('txt-29')?></dt>
            <dd><select name="newContact[type]"><option value="contact"<?php if ($type == 'contact') echo ' selected="selected"'; ?>><?=$langBase->get('subMenu-10')?></option><option value="block"<?php if ($type == 'block') echo ' selected="selected"'; ?>><?=$langBase->get('subMenu-11')?></option></select></dd>
            <dt><?=$langBase->get('cautare-02')?></dt>
            <dd><textarea name="newContact[info]" cols="30" rows="6"><?=$_POST['newContact']['info']?></textarea></dd>
        </dl>
        <p class="center clear"><input type="submit" value="<?=$langBase->get('subMenu-14')?>" /><input type="submit" name="exit" value="<?=$langBase->get('txt-10')?>" style="margin-left: 10px;" /></p>
    </form>
</div>
<?php
	}
?>