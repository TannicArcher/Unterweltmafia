<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$old_items = $db->QueryFetchArrayAll("SELECT * FROM `marketplace` WHERE `created`<'".(time()-(86400*7))."' AND `active`='1'");
	foreach($old_items as $old_item){
		$db->Query("UPDATE `[players]` SET `".$old_item['item_type']."`=`".$old_item['item_type']."`+'".$old_item['amount']."' WHERE `id`='".$old_item['seller']."'");
		$db->Query("UPDATE `marketplace` SET `active`='0' WHERE `id`='".$old_item['id']."'");
	}
	
	if (isset($_POST['buy_item']))
	{
		$item = $db->EscapeString($_POST['buy_item']);
		$item = $db->QueryFetchArray("SELECT id,seller,item_type,amount,price FROM `marketplace` WHERE `active`='1' AND `seller`!='".Player::Data('id')."' AND `id`='".$item."'");

		$item_type = $config['marketplace_item_types'][$item['item_type']];
		
		if ($item['id'] == '')
		{
			echo View::Message('ERROR', 2);
		}
		elseif ($item['price'] > Player::Data('cash'))
		{
			echo View::Message($langBase->get('err-01'), 2);
		}
		elseif (!$item_type)
		{
			echo View::Message('ERROR', 2);
		}
		else
		{
			$db->Query("UPDATE `marketplace` SET `active`='0', `sold_to`='".Player::Data('id')."', `sold_time`='".time()."' WHERE `id`='".$item['id']."'");
			$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$item['price']."', `".$item['item_type']."`=`".$item['item_type']."`+'".$item['amount']."' WHERE `id`='".Player::Data('id')."'");
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$item['price']."' WHERE `id`='".$item['seller']."'");
			
			View::Message($langBase->get('piata-01', array('-ITEM-' => View::CashFormat($item['amount']) . ' ' . strtolower($config['marketplace_item_types'][$item['item_type']]['title']), '-CASH-' => View::CashFormat($item['price']))), 1, true);
		}
	}
	elseif (isset($_POST['delete_item']))
	{
		$item = $db->EscapeString($_POST['delete_item']);
		$item = $db->QueryFetchArray("SELECT id,item_type,amount FROM `marketplace` WHERE `active`='1' AND `seller`='".Player::Data('id')."' AND `id`='".$item."'");

		if ($item['id'] == '')
		{
			echo View::Message('ERROR', 2);
		}
		else
		{
			$db->Query("UPDATE `marketplace` SET `active`='0' WHERE `id`='".$item['id']."'");
			$db->Query("UPDATE `[players]` SET `".$item['item_type']."`=`".$item['item_type']."`+'".$item['amount']."' WHERE `id`='".Player::Data('id')."'");
			
			View::Message($langBase->get('piata-02'), 1, true);
		}
	}
	elseif (isset($_POST['add_item_type'], $_GET['new']))
	{
		$type_key = $db->EscapeString($_POST['add_item_type']);
		$type = $config['marketplace_item_types'][$type_key];
		
		$amount = View::NumbersOnly($db->EscapeString($_POST['add_item_amount']));
		$price = View::NumbersOnly($db->EscapeString($_POST['add_item_price']));
		
		if (!$type)
		{
			echo View::Message($langBase->get('piata-03'), 2);
		}
		elseif ($amount > Player::Data($type_key))
		{
			echo View::Message($langBase->get('piata-04', array('-ITEM-' => strtolower($type['title']))), 2);
		}
		elseif ($db->GetNumRows($db->Query("SELECT id FROM `marketplace` WHERE `seller`='".Player::Data('id')."' AND `active`='1' AND `item_type`='".$type_key."' LIMIT 2")) >= 2)
		{
			echo View::Message($langBase->get('piata-05'), 2);
		}
		elseif ($amount < 1)
		{
			echo View::Message($langBase->get('piata-06'), 2);
		}
		elseif ($price < 1000)
		{
			echo View::Message($langBase->get('piata-07'), 2);
		}
		else
		{
			$db->Query("UPDATE `[players]` SET `".$type_key."`=`".$type_key."`-'".$amount."' WHERE `id`='".Player::Data('id')."'");
			$db->Query("INSERT INTO `marketplace` (`seller`, `item_type`, `amount`, `price`, `created`)VALUES('".Player::Data('id')."', '".$type_key."', '".$amount."', '".$price."', '".time()."')");
			
			View::Message($langBase->get('piata-08'), 1, true);
		}
	}
?>
<p class="center">
	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?><?=(!isset($_GET['new']) ? '&amp;new' : '')?>" class="button"><?=(isset($_GET['new']) ? '&laquo; '.$langBase->get('ot-back') : $langBase->get('piata-09').' &raquo;')?></a>
</p>
<?php
if (isset($_GET['new']))
{
?>
<div class="bg_c w300">
    <h1 class="big"><?=$langBase->get('piata-09')?></h1>
    <form method="post" action="">
    	<dl class="dd_right">
        	<dt><?=$langBase->get('txt-29')?></dt>
            <dd>
            	<select name="add_item_type">
                	<option><?=$langBase->get('auction-014')?>...</option>
                <?php
				foreach ($config['marketplace_item_types'] as $key => $value)
				{
					echo '<option value="' . $key . '"' . ($_POST['add_item_type'] == $key ? ' selected="selected"' : '') . '>' . $value['title'] . '</option>';
				}
				?>
                </select>
            </dd>
            <dt><?=$langBase->get('txt-25')?></dt>
            <dd><input type="text" name="add_item_amount" class="flat" value="<?=(View::CashFormat(isset($_POST['add_item_amount']) ? View::NumbersOnly($_POST['add_item_amount']) : 5))?>" /></dd>
            <dt><?=$langBase->get('txt-03')?></dt>
            <dd><input type="text" name="add_item_price" class="flat" value="<?=(View::CashFormat(isset($_POST['add_item_price']) ? View::NumbersOnly($_POST['add_item_price']) : 1000))?> $" /></dd>
        </dl>
        <p class="center clear">
        	<input type="submit" value="<?=$langBase->get('txt-15')?>" />
        </p>
    </form>
</div>
<?php
}
?>
<div style="width: 620px; margin: 0px auto;">
	<div class="left" style="width: 320px;">
    	<div class="bg_c" style="width: 300px;">
        	<h1 class="big"><?=$langBase->get('piata-10')?></h1>
            <?php
			$sql = "SELECT id,seller,item_type,amount,price FROM `marketplace` WHERE `active`='1' AND `seller`!='".Player::Data('id')."' ORDER BY `price`/`amount` ASC";
			$pagination = new Pagination($sql, 15, 'sp');
			$sales = $pagination->GetSQLRows();
			
			if (count($sales) <= 0)
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
                        	<td><?=$langBase->get('piata-11')?></td>
                            <td><?=$langBase->get('txt-29')?></td>
                            <td><?=$langBase->get('txt-25')?></td>
                            <td><?=$langBase->get('txt-03')?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
					foreach ($sales as $item)
					{
					?>
                    	<tr class="boxHandle c_<?=($i++%2 ? 1 : 2)?>">
                        	<td><input type="radio" name="buy_item" value="<?=$item['id']?>" /><?=View::Player(array('id' => $item['seller']))?></td>
                            <td class="center"><?=$config['marketplace_item_types'][$item['item_type']]['title']?></td>
                            <td class="center"><?=View::CashFormat($item['amount'])?> <?=strtolower($config['marketplace_item_types'][$item['item_type']]['title'])?></td>
                            <td class="t_right"><?=View::CashFormat($item['price'])?> $</td>
                        </tr>
                    <?php
					}
					?>
                    	<tr class="c_3">
                        	<td colspan="4"><?=$pagination->GetPageLinks()?></td>
                        </tr>
                    </tbody>
                </table>
                <p class="center">
                	<input type="submit" value="<?=$langBase->get('txt-01')?>" />
                </p>
            </form>
            <?php
			}
			?>
        </div>
    </div>
    <div class="left" style="width: 280px; margin-left: 20px;">
    <div class="bg_c" style="width: 260px;">
        	<h1 class="big"><?=$langBase->get('piata-12')?></h1>
            <?php
			$sql = "SELECT id,sold_to,item_type,amount,price,active FROM `marketplace` WHERE `seller`='".Player::Data('id')."' AND `active`='1' ORDER BY active DESC, id DESC";
			$pagination = new Pagination($sql, 15, 'mp');
			$sales = $pagination->GetSQLRows();
			
			if (count($sales) <= 0)
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
                        	<td><?=$langBase->get('piata-13')?></td>
                            <td><?=$langBase->get('txt-25')?></td>
                            <td><?=$langBase->get('txt-03')?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
					unset($i);
					foreach ($sales as $item)
					{
					?>
                    	<tr class="<?=($item['active'] == 1 ? 'boxHandle ' : '')?>c_<?=($i++%2 ? 1 : 2)?>">
                            <td><?=($item['active'] == 1 ? '<input type="radio" name="delete_item" value="' . $item['id'] . '" />' : '')?><?=($item['sold_to'] == 0 ? 'N/A' : View::Player(array('id' => $item['sold_to'])))?></td>
                            <td class="center"><?=View::CashFormat($item['amount'])?> <?=strtolower($config['marketplace_item_types'][$item['item_type']]['title'])?></td>
                            <td class="t_right"><?=View::CashFormat($item['price'])?> $</td>
                        </tr>
                    <?php
					}
					?>
                    	<tr class="c_3">
                        	<td colspan="4"><?=$pagination->GetPageLinks()?></td>
                        </tr>
                    </tbody>
                </table>
                <p class="center">
                	<input type="submit" value="<?=$langBase->get('txt-36')?>" />
                </p>
            </form>
            <?php
			}
			?>
        </div>
    </div>
    <div class="clear"></div>
</div>