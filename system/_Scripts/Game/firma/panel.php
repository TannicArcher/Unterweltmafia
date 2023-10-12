<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	function validate64($buffer)
	{
	  $VALID  = 1;
	  $INVALID= 0;
	
	  $p    = $buffer;   
	  $len  = strlen($p);      
	 
	  for($i=0; $i<$len; $i++)
	  {
		 if( ($p[$i]>="A" && $p[$i]<="Z")||
			 ($p[$i]>="a" && $p[$i]<="z")||
			 ($p[$i]>="/" && $p[$i]<="9")||
			 ($p[$i]=="+")||
			 ($p[$i]=="=")||
			 ($p[$i]=="\x0a")||
			 ($p[$i]=="\x0d")
		   )
		   continue;
		 else
		   return $INVALID;
	  }
	return $VALID;
	}
	
	$subOption = $_GET['b'];
	$subOptions = array(
		'1' => array('transfers', 'interests', 'clients'),
		'2' => array('add', 'papers', 'journalists'),
		'3' => array('kf')
	);

	$f_id = $db->EscapeString($_GET['id']);
	
	$firma = $db->QueryFetchArray("SELECT * FROM `businesses` WHERE `id`='$f_id' AND `active`='1'");
	
	if ($firma['id'] == '')
	{
		View::Message('Firma necunoscuta.', 2, true, '/game/?side=firma/');
	}
	
	$options = array('main', 'bank', 'log', 'f', 'settings', 'sl');
	$option = $_GET['a'];
	
	$ownerLevel = 0;
	if ($firma['job_1'] == Player::Data('id') || Player::Data('level') == 4)
	{
		$ownerLevel = 1;
	}
	elseif ($firma['job_2'] == Player::Data('id'))
	{
		$ownerLevel = 2;
	}
	
	if ($firma['job_2'] == Player::Data('id') && Player::Data('level') == 4)
	{
		$ownerLevel = 2;
	}
	
	if ($ownerLevel == 0)
	{
		View::Message('ERROR', 2, true, '/game/?side=firma/');
	}
	
	if ($ownerLevel == 1)
		$options[] = 'leggned';
	
	if (!in_array($option, $options))
	{
		header('Location: /game/?side='.$_GET['side'].'&id='.$_GET['id'].'&a=main');
		exit;
	}
	
	if (!in_array($subOption, $subOptions[$firma['type']]) && $option == 'f')
	{
		header('Location: /game/?side='.$_GET['side'].'&id='.$_GET['id'].'&a=f&b='.$subOptions[$firma['type']][0]);
		exit;
	}
	
	$firma_misc = unserialize($firma['misc']);
	$firmatype = $config['business_types'][$firma['type']];
	
	// Bursa
	$stocks = $db->QueryFetchArray("SELECT id FROM `stocks` WHERE `business_type`='game_business' AND `business_id`='".$firma['id']."' AND `active`='1'");
	
	if ($stocks['id'] == '')
	{
		$db->Query("INSERT INTO `stocks`
					(`business_type`, `business_id`, `shares`, `changes`, `created`, `current_price`, `last_change_time`)
					VALUES
					('game_business', '".$firma['id']."', 'a:0:{}', 'a:0:{}', '".time()."', '".$config['businesses_default_stockprice']."', '".time()."')");
		
		header('Location: ' . $_SERVER['REQUEST_URI']);
		exit;
	}
?>
<?php
if (!empty($firma['deficit_start']))
{
	$timeleft = $firma['deficit_start']+$firmatype['max_deficit_length'] - time();
	
	echo '<div style="margin-top: 15px; width: 400px; margin: 0px auto;">' . View::Message($langBase->get('comp-39', array('-TIME-' => trim(View::strTime($timeleft, 1, ' ', 0)))), 2, false, '', '', 11) . '</div>';
}
?>
<div class="bg_c w400">
	<?php
	if ($firma['image'])
	{
	?>
    <div class="left" style="padding: 5px 10px 5px 0;"><a href="<?=$config['base_url']?>?side=firma/firma&amp;id=<?=$firma['id']?>" style="display: block; float: left; max-width: 150px; max-height: 100px;"><img src="<?=$firma['image']?>" alt="" class="handle_image noZoom" /></a></div>
    <?php
	}
	?>
    <div class="left" style="padding-left: 10px; border-left: dashed 1px #444444;">
    	<dl class="dt_90" style="margin: 0;">
        	<dt><?=$langBase->get('txt-02')?></dt>
            <dd><a href="<?=$config['base_url']?>?side=firma/firma&amp;id=<?=$firma['id']?>"><?=$firma['name']?></a></dd>
            <dt><?=$langBase->get('txt-29')?></dt>
            <dd><?=$firmatype['name'][2]?></dd>
            <dt><?=$langBase->get('txt-05')?></dt>
            <dd><a href="<?=$config['base_url']?>?side=harta&amp;sted=<?=$firma['place']?>"><?=$config['places'][$firma['place']][0]?></a></dd>
            <dt><?=$firmatype['job_titles'][0]?></dt>
            <dd><?=View::Player(array('id' => $firma['job_1']))?></dd>
            <dt><?=$firmatype['job_titles'][1]?></dt>
            <dd><?=View::Player(array('id' => $firma['job_2']), false, 'N/A')?></dd>
        </dl>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<div class="clear"></div>
<div class="center">
	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=main" class="button big<?php if($_GET['a'] == 'main') echo ' active'; ?>"><?=$langBase->get('subMenu-17')?></a>
    <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=bank" class="button big<?php if($_GET['a'] == 'bank') echo ' active'; ?>"><?=$langBase->get('comp-40')?></a>
    <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=settings" class="button big<?php if($_GET['a'] == 'settings') echo ' active'; ?>"><?=$langBase->get('comp-41')?></a>
    <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=log" class="button big<?php if($_GET['a'] == 'log') echo ' active'; ?>"><?=$langBase->get('comp-42')?></a>
    <?php if($ownerLevel == 1){ ?><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=sl" class="button big<?php if($_GET['a'] == 'sl') echo ' active'; ?>"><?=$config['business_types'][$firma['type']]['job_titles'][1]?></a><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=leggned" class="button big<?php if($_GET['a'] == 'leggned') echo ' active'; ?>"><?=$langBase->get('txt-36')?></a><?php } ?>
    <?php
	if ($firma['type'] == 1):
	?>
    <p class="center" style="margin-top: 20px;">
    	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=f&amp;b=transfers" class="button big<?php if($_GET['a'] == 'f' && $_GET['b'] == 'transfers') echo ' active'; ?>"><?=$langBase->get('comp-43')?></a>
        <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=f&amp;b=interests" class="button big<?php if($_GET['a'] == 'f' && $_GET['b'] == 'interests') echo ' active'; ?>"><?=$langBase->get('comp-44')?></a>
        <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=f&amp;b=clients" class="button big<?php if($_GET['a'] == 'f' && $_GET['b'] == 'clients') echo ' active'; ?>"><?=$langBase->get('comp-45')?></a>
    </p>
    <?php
	elseif ($firma['type'] == 2):
	?>
    <p class="center" style="margin-top: 20px;">
    	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=f&amp;b=add" class="button big<?php if($_GET['a'] == 'f' && $_GET['b'] == 'add') echo ' active'; ?>"><?=$langBase->get('comp-46')?></a>
        <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=f&amp;b=papers" class="button big<?php if($_GET['a'] == 'f' && $_GET['b'] == 'papers') echo ' active'; ?>"><?=$langBase->get('comp-47')?></a>
        <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=f&amp;b=journalists" class="button big<?php if($_GET['a'] == 'f' && $_GET['b'] == 'journalists') echo ' active'; ?>"><?=$langBase->get('comp-16')?></a>
    </p>
	<?php
	elseif ($firma['type'] == 3):
	?>
    <p class="center" style="margin-top: 20px;">
    	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=f&amp;b=kf" class="button big<?php if($_GET['a'] == 'f' && $_GET['b'] == 'kf') echo ' active'; ?>"><?=$langBase->get('comp-48')?></a>
    </p>
    <?php
	endif;
	?>
</div>
<div class="hr big" style="margin: 20px 10px 0 10px;"></div>
<?php
	if ($option == 'main')
	{
		$showEvents = 15;
		
		$sql = $db->Query("SELECT text,added FROM `business_log` WHERE `b_id`='".$firma['id']."' ORDER BY id DESC LIMIT 0,$showEvents");
		$log = $db->FetchArrayAll($sql);
?>
<div style="width: 270px;" class="left">
    <div class="bg_c" style="width: 250px;">
    	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
        <dl class="dd_right">
        	<dt><?=$langBase->get('txt-02')?></dt>
            <dd><?=$firma['name']?></dd>
            <dt><?=$langBase->get('txt-29')?></dt>
            <dd><?=$firmatype['name'][2]?></dd>
            <dt><?=$langBase->get('txt-05')?></dt>
            <dd><a href="<?=$config['base_url']?>?side=harta&amp;sted=<?=$firma['place']?>"><?=$config['places'][$firma['place']][0]?></a></dd>
            <dt><?=$firmatype['job_titles'][0]?></dt>
            <dd><?=View::Player(array('id' => $firma['job_1']))?></dd>
            <dt><?=$firmatype['job_titles'][1]?></dt>
            <dd><?=View::Player(array('id' => $firma['job_2']), false, 'N/A')?></dd>
            <dt><?=$langBase->get('comp-01')?></dt>
            <dd><?=View::Time($firma['created'], true, 'H:i:s')?></dd>
            <dt><?=$langBase->get('comp-40')?></dt>
            <dd><?=View::CashFormat($firma['bank'])?> $</dd>
        </dl>
        <div class="clear"></div>
    </div>
</div>
<div style="width: 350px; margin-left: 20px;" class="left">
    <div class="bg_c" style="width: 330px;">
    	<h1 class="big"><?=$langBase->get('comp-49', array('-NUM-' => $showEvents))?></h1>
        <?php
		if (count($log) <= 0)
		{
			echo '<h2 class="center">'.$langBase->get('err-06').'</h2>';
		}
		else
		{
		?>
        <dl class="dt_90">
        	<?php
			foreach ($log as $event)
			{
				echo "<dt><b>".View::Time($event['added'], false, 'H:i', false)."</b></dt>\n<dd>".$event['text']."</dd>\n";
			}
			?>
        </dl>
        <div class="clear"></div>
        <?php
		}
		?>
    </div>
</div>
<div class="clear"></div>
<?php
	}
	elseif ($option == 'bank')
	{

		if (isset($_POST['taut_sum']) && $firma['job_1'] == Player::Data('id'))
		{
			$sum = $db->EscapeString(View::NumbersOnly($_POST['taut_sum']));
			
			if ($sum > $firma['bank'])
			{
				echo View::Message($langBase->get('comp-50'), 2);
			}
			elseif ($sum <= 0)
			{
				echo View::Message($langBase->get('comp-51'), 2);
			}
			else
			{
				$db->Query("UPDATE `businesses` SET `bank`=`bank`-'$sum' WHERE `id`='".$firma['id']."'");
				$db->Query("UPDATE `[players]` SET `cash`=`cash`+'$sum' WHERE `id`='".Player::Data('id')."'");
				
				$db->Query("INSERT INTO `business_log` (`b_id`, `text`, `type`, `added`, `added_date`)VALUES('".$firma['id']."', '".View::Player(Player::$datavar, true)." a luat ".View::CashFormat($sum)." $ din contul companiei.', '1', '".time()."', '".date('d.m.Y')."')");
				
				View::Message($langBase->get('comp-52', array('-CASH-' => View::CashFormat($sum))), 1, true);
			}
		}
		elseif (isset($_POST['settinn_sum']))
		{
			$sum = $db->EscapeString(View::NumbersOnly($_POST['settinn_sum']));
			
			if ($sum > Player::Data('cash'))
			{
				echo View::Message($langBase->get('comp-53'), 2);
			}
			elseif ($sum <= 0)
			{
				echo View::Message($langBase->get('comp-54'), 2);
			}
			else
			{
				$db->Query("UPDATE `businesses` SET `bank`=`bank`+'$sum' WHERE `id`='".$firma['id']."'");
				$db->Query("UPDATE `[players]` SET `cash`=`cash`-'$sum' WHERE `id`='".Player::Data('id')."'");
				
				$db->Query("INSERT INTO `business_log` (`b_id`, `text`, `type`, `added`, `added_date`)VALUES('".$firma['id']."', '".View::Player(Player::$datavar, true)." a depus ".View::CashFormat($sum)." $ in contul companiei.', '2', '".time()."', '".date('d.m.Y')."')");
				
				View::Message($langBase->get('comp-55', array('-CASH-' => View::CashFormat($sum))), 1, true);
			}
		}
?>
<div style="width: 310px;" class="left">
    <div class="bg_c" style="width: 290px;">
    	<h1 class="big"><?=$langBase->get('comp-40')?></h1>
        <dl class="dd_right">
            <dt><?=$langBase->get('comp-40')?></dt>
            <dd><?=View::CashFormat($firma['bank'])?> $</dd>
        </dl>
        <div class="clear"></div>
        <div class="hr big"></div>
        <dl class="dd_right">
            <dt><?=$langBase->get('moneda-23')?></dt>
            <dd><?=View::CashFormat($firma['bank_income'])?> $</dd>
            <dt><?=$langBase->get('moneda-24')?></dt>
            <dd><?=View::CashFormat($firma['bank_loss'])?> $</dd>
            <dt><?=($firma['bank_income']-$firma['bank_loss'] < 0 ? $langBase->get('min-26') : $langBase->get('min-25'))?></dt>
            <dd><span style="color: #<?=($firma['bank_income']-$firma['bank_loss'] < 0 ? 'ff0000' : '00ff00')?>;"><?=View::CashFormat(View::NumbersOnly($firma['bank_income']-$firma['bank_loss']))?> $</span></dd>
        </dl>
        <div class="clear"></div>
    </div>
</div>
<div style="width: 310px; margin-left: 20px;" class="left">
    <div class="bg_c" style="width: 290px;">
    	<h1 class="big"><?=$langBase->get('comp-56')?></h1>
        <div style="width: 140px;" class="left">
        	<?php
			if ($firma['job_1'] == Player::Data('id') || Player::Data('level') == 4):
			?>
        	<div class="bg_c c_1" style="width: 120px; margin-top: 0;">
            	<h1 class="big"><?=$langBase->get('banca-51')?></h1>
                <form method="post" action="">
                    <p>
                        <?=$langBase->get('txt-25')?>: (<a href="#" onclick='$$("input[name=taut_sum]").set("value", "<?=View::CashFormat($firma['bank'])?> $"); return false;'><?=$langBase->get('txt-26')?></a>)<br />
                        <input type="text" name="taut_sum" class="styled" style="min-width: 110px; width: 110px; margin-top: 5px;" value="<?=View::CashFormat($_POST['taut_sum'])?> $" />
                        <br />
                    </p>
                    <p class="center"><input type="submit" value="<?=$langBase->get('banca-52')?>" /></p>
                </form>
            </div>
            <?php
			endif;
			?>
        </div>
        <div style="width: 140px;" class="left">
        	<div class="bg_c c_1" style="width: 120px; margin-left: 10px; margin-top: 0;">
            	<h1 class="big"><?=$langBase->get('banca-53')?></h1>
                <form method="post" action="">
                    <p>
                        <?=$langBase->get('txt-25')?>: (<a href="#" onclick='$$("input[name=settinn_sum]").set("value", "<?=View::CashFormat(Player::Data('cash'))?> $"); return false;'><?=$langBase->get('txt-26')?></a>)<br />
                        <input type="text" name="settinn_sum" class="styled" style="min-width: 110px; width: 110px; margin-top: 5px;" value="<?=View::CashFormat($_POST['settinn_sum'])?> $" />
                    </p>
                    <p class="center"><input type="submit" value="<?=$langBase->get('banca-54')?>" /></p>
                </form>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<div class="clear"></div>
<?php
	}
	elseif ($option == 'log')
	{
		$sql = "SELECT text,added,added_date FROM `business_log` WHERE `b_id`='".$firma['id']."' ORDER BY id DESC";
		
		$pagination       = new Pagination($sql, 50, 'p');
		$all_logevents    = $pagination->GetSQLRows();
		$pagination_links = $pagination->GetPageLinks();
		
		$events = array();
		$days   = array();
		foreach ($all_logevents as $event)
		{
			$day = $event['added_date'];
			if (!in_array($day, $days))
			{
				$days[] = $day;
			}
			
			$events[$day][] = $event;
		}
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('comp-42')?></h1>
    <?php
	if ($pagination->num_rows <= 0)
	{
		echo '<p>'.$langBase->get('err-06').'</p>';
	}
	else
	{
		echo $pagination_links;
		
		foreach ($days as $day)
		{
			$theEvents = $events[$day];
	?>
    <div class="bg_c c_1 w500">
    	<h1 class="big"><?=trim(View::Time($theEvents[0]['added'], true, '', true))?></h1>
    	<dl class="dt_50">
        	<?php
			foreach ($theEvents as $event)
			{
				echo "<dt><b>".date('H:i:s', $event['added'])."</b></dt>\n<dd>".$event['text']."</dd>\n";
			}
			?>
        </dl>
        <div class="clear"></div>
    </div>
    <?php
		}
	}
	?>
</div>
<?php
	}
	elseif ($option == 'leggned')
	{
		if (isset($_POST['leggned_pass']))
		{
			$pass = View::DoubleSalt($db->EscapeString($_POST['leggned_pass']), User::Data('id'));
			
			if ($pass !== User::Data('pass'))
			{
				echo View::Message($langBase->get('txt-20'), 2);
			}
			else
			{
				$db->Query("UPDATE `businesses` SET `active`='0' WHERE `id`='".$firma['id']."'");
				
				View::Message($langBase->get('comp-57'), 1, true, '/game/?side=firma/');
			}
		}
?>
<div class="bg_c w300">
	<h1 class="big"><?=$langBase->get('comp-57')?></h1>
    <form method="post" action="" onsubmit="return confirm('<?=$langBase->get('err-05')?>')">
    	<dl class="dd_right">
        	<dt><?=$langBase->get('home-02')?></dt>
            <dd><input type="password" name="leggned_pass" value="" class="flat" /></dd>
        </dl>
        <p class="center clear">
        	<input type="submit" class="warning" value="<?=$langBase->get('txt-14')?>" />
        </p>
    </form>
</div>
<?php
	}
	elseif ($option == 'f')
	{
		if ($firma['type'] == 1)
		{
			if ($subOption == 'transfers')
			{
				$order = new SQLOrder(array(
					'type'    => 'type',
					'sum'     => 'sum',
					'to_bank' => 'to_bank',
					'date'    => 'sent'
				), 'id', 'o');
				
				$sql = "SELECT id,from_player,to_player,sum,to_bank,sent,type FROM `bank_transfers` WHERE `b_id`='".$firma['id']."' ORDER BY " . $order->GetOrderRow() . " " . $order->GetOrderMethod();
				
				$pagination = new Pagination($sql, 20, 'p');
				$transfers = $pagination->GetSQLRows();
				$pagination_links = $pagination->GetPageLinks();
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('comp-43')?></h1>
    <?php
	if ($pagination->num_rows > 0)
	{
	?>
    <table class="table">
    	<thead>
        	<tr>
            	<td width="17%"><?=$langBase->get('txt-28')?></td>
                <td width="17%"><?=$langBase->get('txt-31')?></td>
                <td width="15%"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=<?=$option?>&amp;b=<?=$subOption?>&amp;p=<?=$pagination->current_page?>&amp;<?=$order->GetParam('type')?>"><?=$langBase->get('txt-29')?></a></td>
                <td width="18%"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=<?=$option?>&amp;b=<?=$subOption?>&amp;p=<?=$pagination->current_page?>&amp;<?=$order->GetParam('sum')?>"><?=$langBase->get('txt-25')?></a></td>
                <td width="18%"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=<?=$option?>&amp;b=<?=$subOption?>&amp;p=<?=$pagination->current_page?>&amp;<?=$order->GetParam('to_bank')?>"><?=$langBase->get('comp-64')?></a></td>
                <td width="15%"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=<?=$option?>&amp;b=<?=$subOption?>&amp;p=<?=$pagination->current_page?>&amp;<?=$order->GetParam('date')?>"><?=$langBase->get('txt-27')?></a></td>
            </tr>
        </thead>
        <tbody>
			<?php
            foreach ($transfers as $transfer)
			{
				$i++;
				$c = $i%2 ? 1 : 2;
			?>
            <tr class="c_<?=$c?>">
            	<td width="17%" class="center"><?=View::Player(array('id' => $transfer['to_player']))?></td>
                <td width="17%" class="center"><?=View::Player(array('id' => $transfer['from_player']))?></td>
                <td width="15%" class="center"><?=$config['bank_transfertypes'][$transfer['type']][0]?></td>
                <td width="18%"><?=View::CashFormat($transfer['sum'])?> <?=$config['bank_transfertypes'][$transfer['type']][1]?></td>
                <td width="18%"><?=View::CashFormat($transfer['to_bank'])?> $</td>
                <td width="15%" class="t_right"><?=trim(View::Time($transfer['sent'], false, ''))?><br /><?=date('H:i:s', $transfer['sent'])?></td>
            </tr>
            <?php
			}
            ?>
            <tr class="c_3"><td colspan="6"><?=$pagination_links?></td></tr>
        </tbody>
    </table>
    <?php
	}else{echo '<p>'.$langBase->get('err-06').'</p>';}
	?>
</div>
<?php
			}
			elseif ($subOption == 'interests')
			{
				$stats_m = $db->QueryFetchArray("SELECT COUNT(*) as money_transfers, SUM(sum) as all_transfers_sum_cash, SUM(to_bank) as to_bank_m FROM `bank_transfers` WHERE `b_id`='".$firma['id']."' AND `type`='money'");
				$stats_p = $db->QueryFetchArray("SELECT COUNT(*) as point_transfers, SUM(sum) as all_transfers_sum_points, SUM(to_bank) as to_bank_p FROM `bank_transfers` WHERE `b_id`='".$firma['id']."' AND `type`='points'");
			
				if (isset($_POST['transfer_fee_change']))
				{
					$fee = $db->EscapeString(View::NumbersOnly($_POST['transfer_fee'], $firmatype['extra']['transfer_fee_max_decimals']));
					$fee_pp = $db->EscapeString(View::NumbersOnly($_POST['transfer_fee_pp']));
					
					if ($fee < $firmatype['extra']['min_transfer_fee'])
					{
						echo View::Message($langBase->get('comp-59', array('-NUM-' => $firmatype['extra']['min_transfer_fee'])).' %', 2);
					}
					elseif ($fee > $firmatype['extra']['max_transfer_fee'])
					{
						echo View::Message($langBase->get('comp-60', array('-NUM-' => $firmatype['extra']['max_transfer_fee'])).' %', 2);
					}
					elseif ($fee_pp < $firmatype['extra']['fransfer_min_fee_pp'])
					{
						echo View::Message($langBase->get('comp-59', array('-NUM-' => $firmatype['extra']['fransfer_min_fee_pp'])).' $', 2);
					}
					elseif ($fee_pp > $firmatype['extra']['fransfer_max_fee_pp'])
					{
						echo View::Message($langBase->get('comp-60', array('-NUM-' => $firmatype['extra']['fransfer_max_fee_pp'])).' $', 2);
					}
					else
					{
						$firma_misc['transfer_fee'] = $fee;
						$firma_misc['transfer_fee_pp'] = $fee_pp;
						$db->Query("UPDATE `businesses` SET `misc`='".serialize($firma_misc)."' WHERE `id`='".$firma['id']."'");
						
						View::Message($langBase->get('comp-61'), 1, true);
					}
				}
				elseif(isset($_POST['deposit_fee_change']))
				{
					$deposit_fee = $db->EscapeString(View::NumbersOnly($_POST['deposit_fee']));

					if ($deposit_fee < 0 || $deposit_fee > $config['bank_max_deposit_fee'])
					{
						View::Message($langBase->get('comp-138', array('-NUM-' => $config['bank_max_deposit_fee'])), 2, true);
					}
					else
					{
						$firma_misc['deposit_fee'] = $deposit_fee;

						$db->Query("UPDATE `businesses` SET `misc`='".serialize($firma_misc)."' WHERE `id`='".$firma['id']."'");
						
						View::Message($langBase->get('comp-61'), 1, true);
					}
				}
if ($firma_misc['rente_type'] == 3 && null)
{
?>
<script type="text/javascript">
	<!--
	window.addEvent('domready', function()
	{
		new ClassList($('class_list'));
	});
	
	var ClassList = new Class({
		initialize: function(wrap)
		{
			this.wrap = wrap;
			
			var self = this;
			$('new_elem').addEvent('click', function()
			{
				self.makeElement();
				
				return false;
			});
			
			$('save_list').addEvent('click', function()
			{
				self.save_changes();
				
				return false;
			});
			
			this.wrap.getElements('.c_2').each(function(elem)
			{
				self.makeElement(elem);
			});
		},
		makeElement: function(elem)
		{
			if (!$chk(elem))
			{
				elem = new Element('div',
				{
					'class': 'c_3 padding list',
					'html': '<p><?=$langBase->get('comp-66')?> <input type="text" class="flat" name="from" value="0 $" style="width: 60px;" /> <?=$langBase->get('comp-65')?> <input type="text" class="flat" name="to" value="0 $" style="width: 70px;" /></p>' +
							'<p><?=$langBase->get('comp-64')?> <input type="text" class="flat" name="percent" value="0" style="width: 20px;" /> % </p>' +
							'<p style="position: absolute; bottom: 5px; right: 5px;"><a href="#" class="delete"><?=$langBase->get('txt-36')?></a></p>'
				}).setStyle('position', 'relative').inject(this.wrap);
			}
			
			elem.getElement('a.delete').addEvent('click', function()
			{
				elem.destroy();
			});
		},
		save_changes: function()
		{
			var result = $$('#class_edit form p.result').set('html', '<img src="/game/images/ajax_load_small.gif" alt="Se incarca..." />');
			var elements = [];
			
			this.wrap.getElements('.c_2, .c_3').each(function(elem)
			{
				elements.push(
				{
					'from': elem.getElement('input[name=from]').get('value'),
					'to': elem.getElement('input[name=to]').get('value'),
					'percent': elem.getElement('input[name=percent]').get('value')
				});
			});
			
			this.xhr = $empty;
			
			elements = JSON.encode(elements, true);
			
			this.xhr = new Request({ url: '/game/js/ajax/firma_interestsClass_save.php', data: 'b_id=<?=$firma['id']?>&class=' + elements, method: 'get' });
			this.xhr.addEvents(
			{
				success: function(data)
				{
					result.set('html', data);
				},
				failure: function(data)
				{
					result.set('html', 'ERROR: ' + data);
				}
			});
			this.xhr.send();
		}
	});
	-->
</script>
<?php
}
?>
<div class="left" style="width: 320px;">
	<div class="bg_c" style="width: 300px;">
    	<h1 class="big"><?=$langBase->get('function-statistics')?></h1>
        <dl class="dd_right">
			<dt><?=$langBase->get('comp-140')?></dt>
            <dd><b><?=round($firma_misc['total_deposits'], 0)?></b></dd>
			<dt><?=$langBase->get('comp-141')?></dt>
            <dd><b><?=View::CashFormat($firma_misc['deposit_fees'])?> $</b></dd>
        </dl>
		<div class="clear"></div>
		<div class="hr big"></div>
		<dl class="dd_right">
			<dt><?=$langBase->get('banca-49')?> (<?=($stats_m['money_transfers']+$stats_p['point_transfers'])?>)</dt>
			<dd><?=View::CashFormat($stats_m['all_transfers_sum_cash'])?> $<br /><?=View::CashFormat($stats_p['all_transfers_sum_points'])?> <?=$langBase->get('ot-points')?></dd>
		</dl>
		<div class="clear"></div>
		<div class="hr big"></div>
		<dl class="dd_right">
			<dt><?=$langBase->get('comp-62')?> (<?=View::CashFormat($stats_m['money_transfers'])?>)</dt>
			<dd><?=View::CashFormat($stats_m['to_bank_m'])?> $</dd>
			<dt><?=$langBase->get('comp-63')?> (<?=View::CashFormat($stats_p['point_transfers'])?>)</dt>
			<dd><?=View::CashFormat($stats_p['to_bank_p'])?> $</dd>
			<dt><?=$langBase->get('min-20')?> (<?=View::CashFormat($stats_m['money_transfers']+$stats_p['point_transfers'])?>)</dt>
			<dd><?=View::CashFormat($stats_m['to_bank_m']+$stats_p['to_bank_p'])?> $</dd>
		</dl>
        <div class="clear"></div>
    </div>
</div>
<div class="left" style="width: 300px; margin-left: 10px;">
	<div class="bg_c" style="width: 280px;">
    	<h1 class="big"><?=$langBase->get('comp-137')?></h1>
        <form method="post" action="">
        	<dl class="form">
            	<dt><?=$langBase->get('comp-13')?></dt><dd><input type="text" class="styled" name="deposit_fee" value="<?=(empty($firma_misc['deposit_fee']) ? 0 : $firma_misc['deposit_fee'])?> %" /></dd>
            </dl>
            <div class="clear"></div>
            <p class="center"><input type="submit" name="deposit_fee_change" value="<?=$langBase->get('txt-21')?>" /></p>
        </form>
    </div>
	<div class="bg_c" style="width: 280px;">
    	<h1 class="big"><?=$langBase->get('comp-136')?></h1>
		<form method="post" action="">
			<dl class="form">
				<dt><?=$langBase->get('comp-07')?> <u><abbr>$</abbr></u></dt>
				<dd><input type="text" name="transfer_fee" class="styled" value="<?=floatval($firma_misc['transfer_fee'])?> %" style="min-width: 100px; width: 100px;" /></dd>
				<dt><?=$langBase->get('comp-07')?> <u><abbr><?=$langBase->get('ot-points')?></abbr></u></dt>
				<dd><input type="text" name="transfer_fee_pp" class="styled" value="<?=View::CashFormat($firma_misc['transfer_fee_pp'])?> $" style="min-width: 100px; width: 100px;" /></dd>
			</dl>
			<p class="center clear">
				<input type="submit" name="transfer_fee_change" value="<?=$langBase->get('txt-21')?>" />
			</p>
		</form>
    </div>
</div>
<div class="clear"></div>
<?php
			}
			elseif ($subOption == 'clients')
			{
				$order = new SQLOrder(array(
					'used' => 'used',
					'registred' => 'registred'
				), 'id', 'o');
				
				$sql = "SELECT id,playerid,registred,used,transfers FROM `bank_clients` WHERE `b_id`='".$firma['id']."' AND `active`='1' AND `accepted`='1' ORDER BY " . $order->GetOrderRow() . " " . $order->GetOrderMethod();
				$pagination = new Pagination($sql, 10, 'p');
				$clients = $pagination->GetSQLRows();
				$pagination_links = $pagination->GetPageLinks();
?>
<?php
if (isset($_GET['client']))
{
	$client = $db->EscapeString($_GET['client']);
	$client = $db->QueryFetchArray("SELECT id,playerid,registred,used,transfers FROM `bank_clients` WHERE `b_id`='".$firma['id']."' AND `active`='1' AND `accepted`='1' AND `playerid`='".$client."'");

	if ($client['id'] != '')
	{
		if (isset($_POST['removeClient']))
		{
			if (in_array($client['playerid'], array($firma['job_1'], $firma['job_2'], Player::Data('id'))))
			{
				echo View::Message($langBase->get('comp-72'), 2);
			}
			else
			{
				$db->Query("UPDATE `bank_clients` SET `active`='0' WHERE `id`='".$client['id']."'");
				$db->Query("UPDATE `[players]` SET `bank_id`='0' WHERE `id`='".$client['playerid']."'");
				
				View::Message($langBase->get('comp-73'), 1, true);
			}
		}
		
		$transfers = unserialize($client['transfers']);
?>
<div class="bg_c w300">
	<h1 class="big"><?=$langBase->get('comp-74')?>: <?=View::Player(array('id' => $client['playerid']), true)?></h1>
    <div class="bg_c c_1 w250">
    	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
        <dl class="dd_right">
        	<dt><?=$langBase->get('txt-06')?></dt>
            <dd><?=View::Player(array('id' => $client['playerid']))?></dd>
            <dt><?=$langBase->get('txt-27')?></dt>
            <dd><?=View::Time($client['registred'], true)?></dd>
            <dt><?=$langBase->get('comp-43')?></dt>
            <dd><?php foreach($config['bank_transfertypes'] as $key => $type){ echo $type[0].': '.View::CashFormat($transfers[$key]).'<br />'; } ?></dd>
            <dt><?=$langBase->get('comp-139')?></dt>
            <dd><?=View::CashFormat($client['used'])?> $</dd>
        </dl>
        <div class="clear"></div>
    </div>
    <form method="post" action="" onsubmit="return confirm('<?=$langBase->get('err-05')?>')">
        <p class="center" style="margin-top: -10px;">
            <input type="submit" name="removeClient" value="<?=$langBase->get('txt-36')?>" />
        </p>
    </form>
</div>
<?php
	}
}
?>

<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('comp-45')?></h1>
    <p><?=$langBase->get('comp-76', array('-NUM-' => $pagination->num_rows))?></p>
    <?php
	if (count($clients) > 0)
	{
		if (isset($_POST['removeClients']))
		{
			$toRemove = $db->EscapeString($_POST['removeClients']);
			$removed = 0;
			
			foreach ($toRemove as $key)
			{
				$client = $clients[$key];
				
				if ($client)
				{
					if (in_array($client['playerid'], array($firma['job_1'], $firma['job_2'], Player::Data('id'))))
						continue;
					
					unset($clients[$key]);
					$db->Query("UPDATE `bank_clients` SET `active`='0' WHERE `id`='".$client['id']."' AND `b_id`='".$firma['id']."'");
					$db->Query("UPDATE `[players]` SET `bank_id`='0' WHERE `id`='".$client['playerid']."'");
					
					$removed++;
				}
			}
			
			View::Message($langBase->get('comp-77'), 1, true);
		}
	?>
    <form method="post" action="" onsubmit="return confirm('Esti sigur ca vrei sa il stergi?')">
        <table class="table boxHandle">
            <thead>
                <tr>
                    <td width="22%"><?=$langBase->get('txt-06')?></td>
                    <td width="20%"><?=$langBase->get('comp-43')?></td>
                    <td width="21%"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=<?=$option?>&amp;b=<?=$subOption?>&amp;p=<?=$pagination->current_page?>&amp;<?=$order->GetParam('used')?>"><?=$langBase->get('comp-139')?></a></td>
                    <td width="17%"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=<?=$option?>&amp;b=<?=$subOption?>&amp;p=<?=$pagination->current_page?>&amp;<?=$order->GetParam('registred')?>"><?=$langBase->get('txt-27')?></a></td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($clients as $c_key => $client)
                {
                    $i++;
                    $c = $i%2 ? 1 : 2;
                    
                    $transfers = unserialize($client['transfers']);
                ?>
                <tr class="c_<?=$c?> boxHandle">
                    <td width="22%" class="center"><input type="checkbox" name="removeClients[]" value="<?=$c_key?>" /><?=View::Player(array('id' => $client['playerid']))?></td>
                    <td width="20%"><?php foreach($config['bank_transfertypes'] as $key => $type){ echo $type[0].': '.View::CashFormat($transfers[$key]).'<br />'; } ?></td>
                    <td width="21%"><?=View::CashFormat($client['used'])?> $</td>
                    <td width="17%" class="t_right"><?=trim(View::Time($client['registred'], true, ''))?><br /><?=date('H:i:s', $client['registred'])?></td>
                </tr>
                <?php
                }
                ?>
                <tr class="c_3"><td colspan="5" style="padding: 0 10px 10px 10px;"><p class="center" style="margin-bottom: 10px;"><input type="submit" value="<?=$langBase->get('txt-36')?>" /></p><?=$pagination_links?></td></tr>
            </tbody>
        </table>
    </form>
    <?php
	}
	if (isset($_POST['account_price']))
	{
		$price = $db->EscapeString(View::NumbersOnly($_POST['account_price']));
		
		if ($price < 0)
		{
			View::Message($langBase->get('comp-78'), 2, true);
		}
		else
		{
			$firma_misc['account_price'] = $price;
			
			$db->Query("UPDATE `businesses` SET `misc`='".serialize($firma_misc)."' WHERE `id`='".$firma['id']."'");
			
			View::Message($langBase->get('comp-79'), 1, true);
		}
	}
	?>
    <script type="text/javascript">
	<!--
	window.addEvent('domready', function()
	{
		var searchBox = $('search_box');
		var search_result = document.getElement('.find_client .result');
		
		searchBox.addEvent('keyup', function(event)
		{
			if (event.key == 'enter')
			{
				search_result.set('html', '<img src="/game/images/ajax_load_small.gif" alt="Please wait..." />');
				
				this.xhr = $empty;
				
				this.xhr = new Request.JSON({ url: '/game/js/ajax/bank_find_clients.php', data: 'q=' + this.get('value') + '&bank=<?=$firma['id']?>', method: 'get' });
				this.xhr.addEvents(
				{
					success: function(result)
					{
						if ($chk(result.error))
						{
							search_result.set('html', '<p>ERROR</p>');
						}
						else
						{
							if (result.clients == 'no_result')
							{
								search_result.set('html', '<p><?=$langBase->get('comp-80')?></p>');
							}
							else
							{
								search_result.empty();
								
								result.clients.each(function(client)
								{
									new Element('p', { 'class': 'center', html: '<a href="/game/?side=<?=$_GET['side']?>&id=<?=$firma['id']?>&a=<?=$option?>&b=<?=$subOption?>&amp;p=<?=$pagination->current_page?>&amp;client=' + client.id + '">' + client.name + '</a>' }).set('tween', { duration: 300 }).fade('hide').inject(search_result).fade(1);
								});
							}
						}
					},
					failure: function(result)
					{
						search_result.set('html', '<p>ERROR: ' + result + '</p>');
					}
				});
				this.xhr.send();
			}
		});
	});
	-->
	</script>
    <div class="bg_c c_1 w200 left" style="margin-left: 15px;">
    	<h1 class="big"><?=$langBase->get('comp-81')?></h1>
        <div class="find_client">
        	<p class="center"><input type="text" class="styled" id="search_box" maxlength="<?=$config['playername_max_chars']?>" /></p>
            <p class="center dark small"><?=$langBase->get('comp-82')?></p>
        	<div class="result center"></div>
        </div>
    </div>
    <div class="bg_c c_1 w300 left" style="margin-left: 25px;">
    	<h1 class="big"><?=$langBase->get('comp-83')?></h1>
        
        <div class="bg_c w250 c_2 hidden" id="account_price_form">
        	<h1><?=$langBase->get('comp-06')?></h1>
            <form method="post" action="" class="center">
            	<input type="text" name="account_price" class="styled" value="<?=View::CashFormat($firma_misc['account_price'])?> $" />
                <p class="center">
                	<input type="submit" value="<?=$langBase->get('min-56')?>" />
                </p>
            </form>
        </div>
        <p class="center"><a href="#" class="button" onclick="$('account_price_form').toggleClass('hidden'); return false;"><?=$langBase->get('comp-84')?></a></p>
        <div class="hr big" style="margin: 15px 5px 15px 5px;"></div>
        
        <?php
		$sql = "SELECT id,playerid,registred,reg_price FROM `bank_clients` WHERE `b_id`='".$firma['id']."' AND `active`='1' AND `accepted`='0'";
		$pagination = new Pagination($sql, 10, 'p');
		$clients = $pagination->GetSQLRows();
		$pagination_links = $pagination->GetPageLinks();
		
		if (count($clients) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
			if (isset($_POST['accept_all']))
			{
				$sql = $db->Query("SELECT id,playerid,reg_price FROM `bank_clients` WHERE `b_id`='".$firma['id']."' AND `active`='1' AND `accepted`='0'");
				$toAccept = $db->FetchArrayAll($sql);
			}
			elseif (isset($_POST['accept']))
			{
				$accounts = $db->EscapeString($_POST['accounts']);
				$toAccept = array();
				
				foreach ($accounts as $key => $account)
				{
					if (!$clients[$key])
						continue;
					
					$toAccept[] = $clients[$key];
					unset($clients[$key]);
				}
			}
			elseif (isset($_POST['deny_all']))
			{
				$sql = $db->Query("SELECT id,playerid FROM `bank_clients` WHERE `b_id`='".$firma['id']."' AND `active`='1' AND `accepted`='0'");
				$toDeny = $db->FetchArrayAll($sql);
			}
			elseif (isset($_POST['deny']))
			{
				$accounts = $db->EscapeString($_POST['accounts']);
				$toDeny = array();
				
				foreach ($accounts as $key => $account)
				{
					if (!$clients[$key])
						continue;
					
					$toDeny[] = $clients[$key];
					unset($clients[$key]);
				}
			}
			
			if ($toAccept)
			{
				$accepted = 0;
				$cashToAdd = 0;
				
				foreach ($toAccept as $acc)
				{
					$sql = $db->Query("SELECT id,userid,cash FROM `[players]` WHERE `id`='".$acc['playerid']."'");
					$thePlayer = $db->FetchArray($sql);
					
					if ($thePlayer['cash'] >= $acc['reg_price'])
					{
						$db->Query("UPDATE `bank_clients` SET `accepted`='1', `used`='".$acc['reg_price']."' WHERE `id`='".$acc['id']."'");
						$db->Query("UPDATE `[players]` SET `bank_id`='".$firma['id']."', `cash`=`cash`-'".$acc['reg_price']."' WHERE `id`='".$acc['playerid']."'");
						
						Accessories::AddLogEvent($thePlayer['id'], 12, array(
							'-COMPANY_IMG-' => $firma['image'],
							'-COMPANY_NAME-' => $firma['name'],
							'-COMPANY_ID-' => $firma['id']
						), $thePlayer['userid']);
						
						$cashToAdd += $acc['reg_price'];
						$accepted++;
					}
				}
				
				if ($accepted > 0)
				{
					$db->Query("INSERT INTO `business_log` (`b_id`, `text`, `type`, `added`, `added_date`)VALUES('".$firma['id']."', '".View::CashFormat($accepted)." genehmigtes".($accepted != 1 ? 'uri' : '')." Konto".($accepted != 1 ? 'e' : '').". Das Unternehmen erhielt ".View::CashFormat($cashToAdd)." $.', '3.1', '".time()."', '".date('d.m.Y')."')");
					$db->Query("UPDATE `businesses` SET `bank`=`bank`+'".$cashToAdd."', `bank_income`=`bank_income`+'".$cashToAdd."' WHERE `id`='".$firma['id']."'");
					$db->Query("UPDATE `stocks` SET `current_income`=`current_income`+'".$cashToAdd."' WHERE `id`='".$stocks['id']."'");
				}
				
				View::Message($langBase->get('comp-85', array('-CASH-' => View::CashFormat($cashToAdd))), 1, true);
			}
			elseif ($toDeny)
			{
				$denied = 0;
				
				foreach ($toDeny as $acc)
				{
					$db->Query("UPDATE `bank_clients` SET `accepted`='0', `active`='0' WHERE `id`='".$acc['id']."'");
					
					Accessories::AddLogEvent($acc['playerid'], 13, array(
						'-COMPANY_IMG-' => $firma['image'],
						'-COMPANY_NAME-' => $firma['name'],
						'-COMPANY_ID-' => $firma['id']
					));
					
					$denied++;
				}
				
				View::Message($langBase->get('comp-86'), 1, true);
			}
		?>
        <form method="post" action="">
        	<p class="center">
            	<input type="submit" name="accept_all" value="<?=$langBase->get('comp-87')?>" />
                <input type="submit" name="deny_all" value="<?=$langBase->get('comp-88')?>" style="margin-left: 10px" />
            </p>
            <table class="table boxHandle">
                <thead>
                    <tr>
                        <td width="40%"><?=$langBase->get('txt-06')?></td>
                        <td width="30%"><?=$langBase->get('comp-64')?></td>
                        <td width="30%"><?=$langBase->get('txt-22')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($clients as $key => $client)
                {
                    $i++;
                    $c = $i%2 ? 1 : 2;
                ?>
                    <tr class="c_<?=$c?> boxHandle">
                        <td width="40%"><input type="checkbox" name="accounts[]" value="<?=$key?>" /><?=View::Player(array('id' => $client['playerid']))?></td>
                        <td width="30%" class="center"><?=View::CashFormat($client['reg_price'])?> $</td>
                        <td width="30%" class="t_right"><?=trim(View::Time($client['registred'], true, ''))?><br /><?=date('H:i:s', $client['registred'])?></td>
                    </tr>
                <?php
                }
                ?>
                	<tr class="c_3"><td colspan="5" style="padding: 0 10px 10px 10px;"><p class="center" style="margin-bottom: 10px;"><input type="submit" value="<?=$langBase->get('comp-89')?>" name="accept" /><input type="submit" value="<?=$langBase->get('comp-90')?>" name="deny" style="margin-left: 10px;" /></p><?=$pagination_links?></td></tr>
                </tbody>
            </table>
        </form>
        <?php
		}
		?>
    </div>
    <div class="clear"></div>
	</div>
	<?php
	}
	?>
<?php
		}
		elseif ($firma['type'] == 2)
		{
			if ($subOption == 'add')
			{
				if (isset($_POST['p_title']))
				{
					$title = $db->EscapeString(trim($_POST['p_title']));
					$logo = $db->EscapeString(trim($_POST['p_logo']));
					$desc = $db->EscapeString($_POST['description']);
					$price = View::NumbersOnly($db->EscapeString($_POST['price']));
					$layout_key = $db->EscapeString($_POST['layout']);
					
					if (!$firmatype['extra']['paper_layouts'][$layout_key])
						$layout_key = 1;
					
					if ($db->GetNumRows($db->Query("SELECT id FROM `newspapers` WHERE `deleted`='0' AND `published`='0' AND `b_id`='".$firma['id']."' LIMIT ".$firmatype['extra']['max_unpublished_papers']."")) >= $firmatype['extra']['max_unpublished_papers'])
					{
						echo View::Message($langBase->get('comp-91', array('-NUM-' => $firmatype['extra']['max_unpublished_papers'])), 2);
					}
					elseif ($price < 0)
					{
						echo View::Message($langBase->get('err-08'), 2);
					}
					elseif (strlen($desc) > $firmatype['extra']['max_description_length'])
					{
						echo View::Message($langBase->get('msg-12', array('-NUM-' => $firmatype['extra']['max_description_length'])), 2);
					}
					elseif (strlen($title) < $firmatype['extra']['min_title_length'])
					{
						echo View::Message($langBase->get('msg-10', array('-NUM-' => $firmatype['extra']['min_title_length'])), 2);
					}
					elseif (strlen($title) > $firmatype['extra']['max_title_length'])
					{
						echo View::Message($langBase->get('msg-11', array('-NUM-' => $firmatype['extra']['max_title_length'])), 2);
					}
					elseif (!preg_match('%\A(?:^((http[s]?|ftp):/)?/?([^:/\s]+)(:([^/]*))?((/\w+)*/)([\w\-.]+[^#?\s]+)(\?([^#]*))?(#(.*))?$)\Z%', $logo) && !empty($logo))
					{
						echo View::Message('LOGO ERROR!', 2);
					}
					else
					{
						$db->Query("INSERT INTO `newspapers` (`b_id`, `title`, `description`, `price`, `layout`, `created`, `articles`, `pending_articles`, `logo`)VALUES('".$firma['id']."', '".$title."', '".$desc."', '".$price."', '".$layout_key."', '".time()."', 'a:0:{}', 'a:0:{}', '".$logo."')");
						
						View::Message($langBase->get('comp-92'), 1, true, '/game/?side='.$_GET['side'].'&id='.$firma['id'].'&a='.$option.'&b=papers&p_id='.mysql_insert_id());
					}
				}
?>
<div class="bg_c w400">
	<h1 class="big"><?=$langBase->get('comp-46')?></h1>
    <form method="post" action="">
    	<dl class="form">
        	<dt><?=$langBase->get('txt-38')?></dt>
            <dd><input type="text" name="p_title" class="styled" style="width: 260px;" maxlength="<?=$firmatype['extra']['max_title_length']?>" value="<?=stripslashes(trim($_POST['p_title']))?>" /></dd>
            <dt><?=$langBase->get('comp-93')?></dt>
            <dd><input type="text" name="p_logo" class="styled" style="width: 260px;" value="<?=stripslashes(trim($_POST['p_logo']))?>" /></dd>
        	<dt><?=$langBase->get('cautare-02')?></dt>
            <dd><textarea name="description" rows="7" cols="40" style="width: 260px; height: 100px;"><?=stripslashes($_POST['description'])?></textarea></dd>
            <dt><?=$langBase->get('txt-03')?></dt>
            <dd><input type="text" name="price" class="styled" style="width: 200px;" value="<?=View::CashFormat(View::NumbersOnly($_POST['price']))?> $" /></dd>
            <dt><?=$langBase->get('comp-41')?></dt>
            <dd><select name="layout" style="margin: 5px 0 5px 0;"><?php foreach($firmatype['extra']['paper_layouts'] as $key => $value){ echo '<option value="'.$key.'"'.($key === (int)$_POST['layout'] ? ' selected="selected"' : '').'>'.$value.'</option>'; }?></select></dd>
        </dl>
        <p class="center clear">
        	<input type="submit" value="<?=$langBase->get('comp-46')?>" />
        </p>
    </form>
</div>
<?php
			}
			elseif ($subOption == 'papers')
			{
				if (isset($_GET['p_id']))
				{
					$paper = $db->EscapeString($_GET['p_id']);
					$sql = $db->Query("SELECT * FROM `newspapers` WHERE `id`='".$paper."' AND `deleted`='0' AND `b_id`='".$firma['id']."'");
					$paper = $db->FetchArray($sql);
					
					$articles = unserialize($paper['articles']);
					$pending_articles = unserialize($paper['pending_articles']);
					
					if ($paper['id'] == '')
					{
						View::Message('ERROR', 2, true, '/game/?side='.$_GET['side'].'&id='.$firma['id'].'&a='.$option.'&b='.$subOption);
					}
					
					if (isset($_POST['edit_title']) && $paper['published'] == 0)
					{
						$title = trim($_POST['edit_title']) != '' ? $db->EscapeString(trim($_POST['edit_title'])) : $paper['title'];
						$logo = trim($_POST['edit_logo']) != '' ? $db->EscapeString(trim($_POST['edit_logo'])) : $paper['logo'];
						$desc = trim($_POST['edit_description']) != '' ? $db->EscapeString($_POST['edit_description']) : $paper['description'];
						$price = trim($_POST['edit_price']) != '' ? View::NumbersOnly($db->EscapeString($_POST['edit_price'])) : $paper['price'];
						$layout_key = $db->EscapeString($_POST['edit_layout']);
						
						if (!$firmatype['extra']['paper_layouts'][$layout_key])
							$layout_key = $paper['layout'];
						
						if ($price < 0)
						{
							echo View::Message($langBase->get('err-08'), 2);
						}
						elseif (strlen($desc) > $firmatype['extra']['max_description_length'])
						{
							echo View::Message($langBase->get('msg-12', array('-NUM-' => $firmatype['extra']['max_description_length'])), 2);
						}
						elseif (strlen($title) < $firmatype['extra']['min_title_length'])
						{
							echo View::Message($langBase->get('msg-10', array('-NUM-' => $firmatype['extra']['min_title_length'])), 2);
						}
						elseif (strlen($title) > $firmatype['extra']['max_title_length'])
						{
							echo View::Message($langBase->get('msg-11', array('-NUM-' => $firmatype['extra']['max_title_length'])), 2);
						}
						elseif (!preg_match('%\A(?:^((http[s]?|ftp):/)?/?([^:/\s]+)(:([^/]*))?((/\w+)*/)([\w\-.]+[^#?\s]+)(\?([^#]*))?(#(.*))?$)\Z%', $logo) && !empty($logo))
						{
							echo View::Message('BAD LOGO!', 2);
						}
						else
						{
							$db->Query("UPDATE `newspapers` SET `title`='".$title."', `description`='".$desc."', `price`='".$price."', `layout`='".$layout_key."', `logo`='".$logo."' WHERE `id`='".$paper['id']."'");
							
							View::Message($langBase->get('comp-61'), 1, true);
						}
					}
					elseif (isset($_POST['delete']))
					{
						$db->Query("UPDATE `newspapers` SET `deleted`='1', `published`='0' WHERE `id`='".$paper['id']."'");
						
						View::Message($langBase->get('comp-94'), 1, true, '/game/?side='.$_GET['side'].'&id='.$firma['id'].'&a='.$option.'&b='.$subOption);
					}
					elseif (isset($_POST['publish']))
					{
						if (count($articles) <= 0 && $paper['published'] == 0)
						{
							echo View::Message($langBase->get('comp-95'), 2);
						}
						else
						{
							$db->Query("UPDATE `newspapers` SET `published`='".($paper['published'] == 0 ? 1 : 0)."'".($paper['published'] == 0 ? ', `publish_time`=\''.time().'\'' : '')." WHERE `id`='".$paper['id']."'");
							
							View::Message(($paper['published'] == 0 ? $langBase->get('comp-96') : $langBase->get('comp-97')), 1, true);
						}
					}
?>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('comp-98')?></h1>
    <div class="left" style="width: 230px;">
    	<div class="bg_c c_1" style="width: 200px;">
        	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
            <dl class="dd_right">
            	<dt><?=$langBase->get('txt-38')?></dt>
                <dd><?=View::NoHTML($paper['title'])?></dd>
                <dt><?=$langBase->get('txt-03')?></dt>
                <dd><?=View::CashFormat($paper['price'])?> $</dd>
                <dt><?=$langBase->get('comp-26')?></dt>
                <dd><?=View::CashFormat(count(unserialize($paper['sold_to'])))?></dd>
                <dt><?=$langBase->get('comp-41')?></dt>
                <dd><?=$firmatype['extra']['paper_layouts'][$paper['layout']]?></dd>
                <dt><?=$langBase->get('txt-27')?></dt>
                <dd><?=View::Time($paper['created'])?></dd>
                <dt><?=$langBase->get('comp-99')?>?</dt>
                <dd><?=($paper['published'] == 1 ? $langBase->get('ot-yes') : $langBase->get('ot-no'))?></dd>
            </dl>
            <div class="clear"></div>
            <div class="hr big" style="margin: 10px 0 10px 0;"></div>
            <form method="post" action="">
            	<p class="center">
            		<input type="submit" name="publish" value="<?=($paper['published'] == 0 ? $langBase->get('comp-100') : $langBase->get('comp-101'))?>" />
                    <input type="submit" name="delete" class="warning" value="<?=$langBase->get('txt-36')?>" onclick="return confirm('<?=$langBase->get('err-05')?>')" />
            	</p>
                <p class="center">
                	<a href="<?=$config['base_url']?>?side=firma/avis&amp;id=<?=$paper['id']?>"<?=($paper['published'] == 0 ? ' rel="target:blank"' : '')?> class="button"><?=($paper['published'] == 0 ? $langBase->get('comp-102') : $langBase->get('comp-103'))?></a>
                </p>
            </form>
        </div>
    </div>
    <div class="left" style="width: 260px; margin-left: 10px;">
    	<div class="bg_c c_1" style="width: 240px;">
        	<h1 class="big"><?=$langBase->get('comp-104')?></h1>
            <?php
			if ($paper['published'] != 0):
			?>
            <p class="t_justify"><?=$langBase->get('comp-105')?></p>
            <?php
			else:
			?>
            <form method="post" action="">
            	<dl class="form">
                	<dt><?=$langBase->get('txt-38')?></dt>
                    <dd><input type="text" name="edit_title" class="styled" maxlength="<?=$firmatype['extra']['max_title_length']?>" value="<?=(trim($_POST['edit_title']) == '' ? str_replace('"', '&quot;', $paper['title']) : stripslashes(str_replace('"', '&quot;', $_POST['edit_title'])))?>" /></dd>
                    <dt><?=$langBase->get('comp-93')?></dt>
                    <dd><input type="text" name="edit_logo" class="styled" value="<?=(trim($_POST['edit_logo']) == '' ? str_replace('"', '&quot;', $paper['logo']) : stripslashes(str_replace('"', '&quot;', $_POST['edit_logo'])))?>" /></dd>
                    <dt><?=$langBase->get('cautare-02')?></dt>
                    <dd><textarea name="edit_description" rows="5" cols="20"><?=(trim($_POST['edit_description']) == '' ? str_replace('"', '&quot;', $paper['description']) : stripslashes(str_replace('"', '&quot;', $_POST['edit_description'])))?></textarea></dd>
                    <dt><?=$langBase->get('txt-03')?></dt>
                    <dd><input type="text" name="edit_price" class="styled" value="<?=(trim($_POST['edit_price']) == '' ? View::CashFormat($paper['price']) : View::CashFormat(View::NumbersOnly($_POST['edit_price'])))?> $" /></dd>
                    <dt><?=$langBase->get('comp-41')?></dt>
                    <dd><select name="edit_layout" style="margin: 10px 0 0 0;"><?php foreach($firmatype['extra']['paper_layouts'] as $key => $value){ echo '<option value="'.$key.'"'.($key === (int)$_POST['edit_layout'] ? ' selected="selected"' : ($key == $paper['layout'] && !isset($_POST['edit_layout']) ? ' selected="selected"' : '')).'>'.$value.'</option>'; }?></select></dd>
                </dl>
                <p class="center clear" style="margin-top: 20px;">
               		<input type="submit" value="<?=$langBase->get('min-56')?>" />
                </p>
            </form>
            <?php
			endif;
			?>
        </div>
    </div>
    <div class="clear"></div>
    <div class="bg_c c_1" style="width: 480px; margin-top: 0;">
    	<h1 class="big"><?=$langBase->get('comp-106')?></h1>
        <?php
        if (isset($_GET['art']) && ($articles[$_GET['art']] || $pending_articles[$_GET['art']])):
		
		$article = $articles[$_GET['art']] ? $articles[$_GET['art']] : $pending_articles[$_GET['art']];
		$is_pending = $pending_articles[$_GET['art']] ? true : false;
		
		if (isset($_POST['article_changeState']) && $paper['published'] == 0)
		{
			if ($is_pending == true)
			{
				unset($pending_articles[$article['id']]);
				$articles[$article['id']] = $article;
			}
			else
			{
				unset($articles[$article['id']]);
				$pending_articles[$article['id']] = $article;
			}
			
			$db->Query("UPDATE `newspapers` SET `pending_articles`='".serialize($pending_articles)."', `articles`='".serialize($articles)."' WHERE `id`='".$paper['id']."'");
			
			View::Message($is_pending == true ? $langBase->get('comp-107') : $langBase->get('comp-108'), 1, true);
		}
		elseif (isset($_POST['article_delete']) && $paper['published'] == 0)
		{
			if ($is_pending == true)
			{
				unset($pending_articles[$article['id']]);
				
				$db->Query("UPDATE `newspapers` SET `pending_articles`='".serialize($pending_articles)."' WHERE `id`='".$paper['id']."'");
			}
			else
			{
				unset($articles[$article['id']]);
				
				$db->Query("UPDATE `newspapers` SET `articles`='".serialize($articles)."' WHERE `id`='".$paper['id']."'");
			}
			
			View::Message($langBase->get('comp-108'), 1, true);
		}
		elseif (isset($_POST['art_edit_title']) && $paper['published'] == 0)
		{
			$title = $db->EscapeString($_POST['art_edit_title']);
			$text = $db->EscapeString($_POST['art_edit_text']);
			
			$typeKey = $db->EscapeString($_POST['art_edit_type']);
			$type = $firmatype['extra']['article_types'][$typeKey];
			
			if (!$type)
			{
				echo View::Message('ERROR', 2);
			}
			elseif (View::Length($title) < $firmatype['extra']['article_title_min'])
			{
				echo View::Message($langBase->get('msg-10', array('-NUM-' => $firmatype['extra']['article_title_min'])), 2);
			}
			elseif (View::Length($title) > $firmatype['extra']['article_title_max'])
			{
				echo View::Message($langBase->get('msg-11', array('-NUM-' => $firmatype['extra']['article_title_max'])), 2);
			}
			elseif (View::Length($text) < $firmatype['extra']['article_text_min'])
			{
				echo View::Message($langBase->get('msg-12', array('-NUM-' => $firmatype['extra']['article_text_min'])), 2);
			}
			else
			{
				$article['title'] = $title;
				$article['text'] = base64_encode($text);
				$article['type'] = $typeKey;
				$article['edited'] = Player::Data('id');
				
				if ($is_pending == true)
				{
					$pending_articles[$article['id']] = $article;
					
					$db->Query("UPDATE `newspapers` SET `pending_articles`='".serialize($pending_articles)."' WHERE `id`='".$paper['id']."'");
				}
				else
				{
					$articles[$article['id']] = $article;
					
					$db->Query("UPDATE `newspapers` SET `articles`='".serialize($articles)."' WHERE `id`='".$paper['id']."'");
				}
				
				View::Message($langBase->get('comp-61'), 1, true);
			}
		}
		
		echo '<p class="center"><a href="'.$config['base_url'].'?side='.$_GET['side'].'&amp;id='.$firma['id'].'&amp;a='.$option.'&amp;b='.$subOption.'&amp;p_id='.$paper['id'].'" class="button">'.$langBase->get('ot-back').'</a></p>';
		
		$bbText = new BBCodeParser(trim($article['text']) == '' ? '' : (validate64($article['text']) ? stripslashes(base64_decode($article['text'])) : $article['text']), 'unknown', false);
		?>
        <div class="left" style="width: 215px;">
        	<div class="bg_c c_2" style="width: 195px;">
            	<h1 class="big"><?=$langBase->get('comp-109')?></h1>
               	<dl class="dd_right">
                	<dt><?=$langBase->get('txt-38')?></dt>
                    <dd><?=View::NoHTML($article['title'])?></dd>
                	<dt><?=$langBase->get('comp-110')?></dt>
                    <dd><?=View::Player(array('id' => $article['journalist']))?></dd>
                    <dt><?=$langBase->get('txt-27')?></dt>
                    <dd><?=View::Time($article['added'])?></dd>
                    <dt><?=$langBase->get('comp-99')?>?</dt>
                    <dd><?=($is_pending == true ? $langBase->get('ot-no') : $langBase->get('ot-yes'))?></dd>
                    <dt><?=$langBase->get('txt-29')?></dt>
                	<dd><?=$firmatype['extra']['article_types'][$article['type']]?></dd>
                </dl>
                <div class="clear"></div>
                <?php if ($paper['published'] == 0):?>
                <div class="hr big" style="margin: 10px 0 10px 0;"></div>
                <form method="post" action="">
                	<p class="center">
                    	<input type="submit" name="article_changeState" value="<?=($is_pending == true ? $langBase->get('comp-111') : $langBase->get('comp-112'))?>" /> 
                        <input type="submit" name="article_delete" value="<?=$langBase->get('txt-36')?>" onclick="return confirm('<?=$langBase->get('err-05')?>')" class="warning" />
                    </p>
                    <p class="center"><a href="#" class="button" onclick="$('article_text').toggleClass('hidden'); $('article_edit').toggleClass('hidden'); this.toggleClass('active'); return false;"><?=$langBase->get('comp-113')?></a></p>
                </form>
                <?php endif;?>
            </div>
        </div>
        <div class="left" style="width: 245px; margin-left: 10px;">
        	<div class="bg_c c_2" style="width: 225px;">
            	<h1 class="big">&laquo;<?=View::NoHTML($article['title'])?>&raquo;</h1>
               	<div id="article_text"><?=$bbText->result?></div>
                <div id="article_edit" class="hidden">
                	<form method="post" action="">
                    	<dl class="dd_right" style="font-size: 12px;">
                        	<dt><?=$langBase->get('txt-38')?></dt>
                            <dd><input type="text" name="art_edit_title" class="flat" maxlength="<?=$firmatype['extra']['article_title_max']?>" value="<?=(!empty($_POST['art_edit_title']) ? $_POST['art_edit_title'] : $article['title'])?>" /></dd>
                            <dt><?=$langBase->get('comp-38')?></dt>
                            <dd><textarea name="art_edit_text" class="flat" rows="10" cols="8" style="min-width: 170px;"><?=(!empty($_POST['art_edit_text']) ? $_POST['art_edit_text'] : (validate64($article['text']) ? stripslashes(base64_decode($article['text'])) : stripslashes($article['text'])))?></textarea></dd>
                            <dt><?=$langBase->get('txt-29')?></dt>
                            <dd><select name="art_edit_type">
                            <?php
							foreach ($firmatype['extra']['article_types'] as $key => $value)
							{
								echo '<option value="' . $key . '"' . ((isset($_POST['art_edit_type']) && $_POST['art_edit_type'] === $key) ? ' selected="selected"' : ($article['type'] == $key ? ' selected="selected"' : '')) . '>' . $value . '</option>';
							}
							?>
                            </select></dd>
                        </dl>
                        <p class="center clear">
                        	<input type="submit" value="<?=$langBase->get('min-56')?>" />
                        </p>
                    </form>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <?php else:?>
        <div class="left" style="width: 235px;">
        	<div class="bg_c c_2" style="width: 215px;">
            	<h1 class="big"><?=$langBase->get('comp-106')?></h1>
                <?php
				if (count($articles) <= 0)
				{
					echo '<p>'.$langBase->get('err-06').'</p>';
				}
				else
				{
				?>
                <dl class="dd_right" style="color: #444444;">
                    <dt><?=$langBase->get('txt-38')?></dt>
                    <dd><?=$langBase->get('txt-27')?></dd>
                </dl>
                <div class="clear"></div>
                <dl class="dd_right">
                <?php
                foreach ($articles as $art => $article)
                {
                    echo '<dt>&laquo;<a href="'.$config['base_url'].'?side='.$_GET['side'].'&amp;id='.$firma['id'].'&amp;a='.$option.'&amp;b='.$subOption.'&amp;p_id='.$paper['id'].'&amp;art='.$art.'">'.View::NoHTML($article['title']).'</a>&raquo;</dt><dd>'.View::Time($article['added']).'</dd>';
                }
                ?>
                </dl>
                <div class="clear"></div>
                <?php
				}
				?>
            </div>
        </div>
        <div class="left" style="width: 235px; margin-left: 10px;">
        	<div class="bg_c c_2" style="width: 215px;">
            	<h1 class="big"><?=$langBase->get('comp-114')?></h1>
                <?php
				if (count($pending_articles) <= 0)
				{
					echo '<p>'.$langBase->get('err-06').'</p>';
				}
				else
				{
				?>
                <dl class="dd_right" style="color: #444444;">
                    <dt><?=$langBase->get('txt-38')?></dt>
                    <dd><?=$langBase->get('txt-27')?></dd>
                </dl>
                <div class="clear"></div>
                <dl class="dd_right">
                <?php
                foreach ($pending_articles as $art => $article)
                {
                    echo '<dt>&laquo;<a href="'.$config['base_url'].'?side='.$_GET['side'].'&amp;id='.$firma['id'].'&amp;a='.$option.'&amp;b='.$subOption.'&amp;p_id='.$paper['id'].'&amp;art='.$art.'">'.View::NoHTML($article['title']).'</a>&raquo;</dt><dd>'.View::Time($article['added']).'</dd>';
                }
                ?>
                </dl>
                <div class="clear"></div>
                <?php
				}
				?>
            </div>
        </div>
        <div class="clear"></div>
        <?php endif;?>
    </div>
</div>
<?php
				}
				else
				{
?>
<div class="left" style="width: 315px;">
	<div class="bg_c" style="width: 295px;">
    	<h1 class="big"><?=$langBase->get('comp-115')?></h1>
        <?php
		$sql = "SELECT id,title,publish_time FROM `newspapers` WHERE `b_id`='".$firma['id']."' AND `deleted`='0' AND `published`='1' ORDER BY publish_time DESC";
		$pagination = new Pagination($sql, 10, 'p');
		$published = $pagination->GetSQLRows();
		
		if (count($published) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
		?>
        <dl class="dd_right" style="color: #444444;">
        	<dt><?=$langBase->get('comp-98')?></dt>
            <dd><?=$langBase->get('comp-99')?></dd>
        </dl>
        <div class="clear"></div>
        <dl class="dd_right">
        <?php
		foreach ($published as $paper)
		{
			echo '<dt>&laquo;<a href="'.$config['base_url'].'?side='.$_GET['side'].'&amp;id='.$firma['id'].'&amp;a='.$option.'&amp;b='.$subOption.'&amp;p_id='.$paper['id'].'">'.View::NoHTML($paper['title']).'</a>&raquo;</dt><dd>'.View::Time($paper['publish_time']).'</dd>';
		}
		?>
        </dl>
        <div class="clear"></div>
        <div class="hr big" style="margin: 5px 0 15px 0;"></div>
        <?=$pagination->GetPageLinks()?>
        <?php
		}
		?>
    </div>
</div>
<div class="left" style="width: 315px; margin-left: 10px;">
	<div class="bg_c" style="width: 295px;">
    	<h1 class="big"><?=$langBase->get('comp-114')?></h1>
        <?php
		$sql = "SELECT id,title,created FROM `newspapers` WHERE `b_id`='".$firma['id']."' AND `deleted`='0' AND `published`='0' ORDER BY id DESC";
		$pagination = new Pagination($sql, 10, 'u_p');
		$published = $pagination->GetSQLRows();
		
		if (count($published) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
		?>
        <dl class="dd_right" style="color: #444444;">
        	<dt><?=$langBase->get('comp-98')?></dt>
            <dd><?=$langBase->get('comp-99')?></dd>
        </dl>
        <div class="clear"></div>
        <dl class="dd_right">
        <?php
		foreach ($published as $paper)
		{
			echo '<dt>&laquo;<a href="'.$config['base_url'].'?side='.$_GET['side'].'&amp;id='.$firma['id'].'&amp;a='.$option.'&amp;b='.$subOption.'&amp;p_id='.$paper['id'].'">'.View::NoHTML($paper['title']).'</a>&raquo;</dt><dd>'.View::Time($paper['created']).'</dd>';
		}
		?>
        </dl>
        <div class="clear"></div>
        <div class="hr big" style="margin: 5px 0 15px 0;"></div>
        <?=$pagination->GetPageLinks()?>
        <?php
		}
		?>
    </div>
</div>
<div class="clear"></div>
<?php
				}
			}
			elseif ($subOption == 'journalists')
			{
				$journalists = $firma_misc['journalists'];
				$invites = $firma_misc['journalist_invites'];
				
				if (isset($_POST['fire_journalist']) && $journalists[$_POST['fire_journalist']])
				{
					$journalist = $journalists[$_POST['fire_journalist']];
					
					if ($journalist['player'] == Player::Data('id'))
					{
						echo View::Message($langBase->get('comp-116'), 2);
					}
					elseif ($journalist['player'] == $firma['job_1'])
					{
						echo View::Message($langBase->get('comp-117'), 2);
					}
					else
					{
						unset($journalists[$_POST['fire_journalist']]);
						$firma_misc['journalists'] = $journalists;
						
						$db->Query("UPDATE `businesses` SET `misc`='".serialize($firma_misc)."' WHERE `id`='".$firma['id']."'");
						
						$db->Query("INSERT INTO `business_log` (`b_id`, `text`, `type`, `added`, `added_date`)VALUES('".$firma['id']."', '".View::Player(array('id' => $journalist['player']), true)." nu mai este jurnalist la ziar!', '6', '".time()."', '".date('d.m.Y')."')");
						
						Accessories::AddLogEvent($journalist['player'], 14, array(
							'-COMPANY_IMG-' => $firma['image'],
							'-COMPANY_NAME-' => $firma['name'],
							'-COMPANY_ID-' => $firma['id']
						));
						
						View::Message($langBase->get('comp-118'), 1, true);
					}
				}
				elseif (isset($_POST['invite_player']))
				{
					$player = $db->EscapeString($_POST['invite_player']);
					$sql = $db->Query("SELECT id,health,level,userid,name FROM `[players]` WHERE `name`='".$player."'");
					$player = $db->FetchArray($sql);
					
					if ($player['id'] == '')
					{
						echo View::Message('ERROR', 2);
					}
					elseif ($player['health'] <= 0 || $player['level'] <= 0)
					{
						echo View::Message($langBase->get('comp-119'), 2);
					}
					elseif ($journalists[$player['id']])
					{
						echo View::Message($langBase->get('comp-120'), 2);
					}
					elseif ($invites[$player['id']])
					{
						echo View::Message($langBase->get('comp-120'), 2);
					}
					else
					{
						$invites[$player['id']] = array(
							'player' => $player['id'],
							'invited' => time()
						);
						$firma_misc['journalist_invites'] = $invites;
						
						$db->Query("UPDATE `businesses` SET `misc`='".serialize($firma_misc)."' WHERE `id`='".$firma['id']."'");
						
						$db->Query("INSERT INTO `business_log` (`b_id`, `text`, `type`, `added`, `added_date`)VALUES('".$firma['id']."', '".View::Player(Player::$datavar, true)." l-a invitat pe ".View::Player($player, true)." sa devina jurnalist!', '7', '".time()."', '".date('d.m.Y')."')");
						
						Accessories::AddLogEvent($player['id'], 15, array(
							'-COMPANY_IMG-' => $firma['image'],
							'-COMPANY_NAME-' => $firma['name'],
							'-COMPANY_ID-' => $firma['id']
						), $player['userid']);
						
						View::Message($langBase->get('comp-121'), 1, true);
					}
				}
				elseif (isset($_POST['remove_invite']) && $invites[$_POST['remove_invite']])
				{
					$invite = $invites[$_POST['remove_invite']];
					
					unset($invites[$_POST['remove_invite']]);
					$firma_misc['journalist_invites'] = $invites;
					
					$db->Query("UPDATE `businesses` SET `misc`='".serialize($firma_misc)."' WHERE `id`='".$firma['id']."'");
					
					$db->Query("INSERT INTO `business_log` (`b_id`, `text`, `type`, `added`, `added_date`)VALUES('".$firma['id']."', '".View::Player(array('id' => $invite['player']), true)." nu mai este invitat sa devina jurnalist!', '8', '".time()."', '".date('d.m.Y')."')");
					
					View::Message($langBase->get('comp-122'), 1, true);
				}
?>
<div style="width: 600px; margin: 0px auto;">
    <div class="left" style="width: 245px;">
        <div class="bg_c" style="width: 225px;">
            <h1 class="big"><?=$langBase->get('comp-16')?></h1>
            <?php
			if (count($journalists) <= 0)
			{
				echo '<p>'.$langBase->get('err-06').'</p>';
			}
			else
			{
			?>
            <form method="post" action="">
                <table class="table boxHandle">
                    <thead>
                        <tr>
                            <td><?=$langBase->get('txt-06')?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($journalists as $key => $journalist)
                    {
                        $i++;
                        $c = $i%2 ? 1 : 2;
                    ?>
                        <tr class="c_<?=$c?> boxHandle">
                            <td><input type="radio" name="fire_journalist" value="<?=$key?>" /><?=View::Player(array('id' => $journalist['player']))?></td>
                        </tr>
                    <?php
                    }
                    ?>
                        <tr class="c_3 center">
                            <td style="padding: 15px;"><a href="#" class="button form_submit"><?=$langBase->get('crew-01')?></a></td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <?php
			}
			?>
        </div>
    </div>
    <div class="left" style="width: 345px; margin-left: 10px;">
        <div class="bg_c" style="width: 325px;">
            <h1 class="big"><?=$langBase->get('comp-123')?></h1>
            <div class="left" style="width: 147px;">
            	<div class="bg_c c_1" style="width: 127px;">
                	<h1 class="big"><?=$langBase->get('comp-123')?> <?=$langBase->get('txt-06')?></h1>
                    <form method="post" action="">
                    	<dl class="dd_right">
                        	<dt><?=$langBase->get('txt-06')?></dt>
                            <dd><input type="text" name="invite_player" value="<?=$_POST['invite_player']?>" class="flat" /></dd>
                        </dl>
                        <p class="center clear" style="margin-top: 15px;">
                        	<a href="#" class="button form_submit"><?=$langBase->get('comp-123')?></a>
                        </p>
                    </form>
                </div>
            </div>
            <div class="left" style="width: 168px; margin-left: 10px;">
            	<div class="bg_c c_1" style="width: 148px;">
                	<h1 class="big"><?=$langBase->get('comp-124')?></h1>
                    <?php
					if (count($invites) <= 0)
					{
						echo '<p>'.$langBase->get('err-06').'</p>';
					}
					else
					{
					?>
                    <form method="post" action="">
                        <table class="table boxHandle">
                            <thead>
                                <tr>
                                    <td><?=$langBase->get('txt-06')?></td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($invites as $key => $invite)
                            {
                                $i++;
                                $c = $i%2 ? 1 : 2;
                            ?>
                                <tr class="c_<?=$c?> boxHandle">
                                    <td><input type="radio" name="remove_invite" value="<?=$key?>" /><?=View::Player(array('id' => $invite['player']))?></td>
                                </tr>
                            <?php
                            }
                            ?>
                                <tr class="c_3 center">
                                    <td style="padding: 15px 0 15px 0;"><a href="#" class="button form_submit"><?=$langBase->get('txt-36')?></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                    <?php
					}
					?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php
			}
		}
		elseif ($firma['type'] == 3)
		{
			if ($subOption == 'kf')
			{
				$release_timeleft = $firma_misc['last_release']+$firmatype['extra']['release_wait'] - time();
				$production_timeleft = $firma_misc['last_production']+$firmatype['extra']['production_wait'] - time();
				
				if (isset($_POST['release_bullets']) && $release_timeleft <= 0)
				{
					$amount = View::NumbersOnly($db->EscapeString($_POST['release_bullets']));
					
					if ($amount <= 0)
					{
						echo View::Message($langBase->get('comp-125'), 2);
					}
					elseif ($amount > $firma_misc['bullets_stored'])
					{
						echo View::Message($langBase->get('comp-126'), 2);
					}
					else
					{
						$firma_misc['last_release'] = time();
						$firma_misc['bullets_stored'] -= $amount;
						$firma_misc['bullets'] += $amount;
						$db->Query("UPDATE `businesses` SET `misc`='".serialize($firma_misc)."' WHERE `id`='".$firma['id']."'");
						
						View::Message($langBase->get('comp-127', array('-NUM-' => View::CashFormat($amount))), 1, true);
					}
				}
				elseif (isset($_POST['produce_bullets']) && $production_timeleft <= 0)
				{
					$amount = View::NumbersOnly($db->EscapeString($_POST['produce_bullets']));
					$price = $amount * $firmatype['extra']['bullet_price'];
					
					if ($amount <= 0)
					{
						echo View::Message($langBase->get('comp-125'), 2);
					}
					elseif ($amount > $firmatype['extra']['max_bullets_per_production'])
					{
						echo View::Message($langBase->get('comp-128', array('-BULLETS-' => View::CashFormat($firmatype['extra']['max_bullets_per_production']))), 2);
					}
					elseif ($price > $firma['bank'])
					{
						echo View::Message($langBase->get('comp-129'), 2);
					}
					else
					{
						$firma_misc['last_production'] = time();
						$firma_misc['bullets_stored'] += $amount;
						$db->Query("UPDATE `businesses` SET `misc`='".serialize($firma_misc)."', `bank_loss`=`bank_loss`+'".$price."', `bank`=`bank`-'".$price."' WHERE `id`='".$firma['id']."'");
						
						View::Message($langBase->get('comp-130'), 1, true);
					}
				}
?>
<div style="width: 620px; margin: 0px auto;">
	<div class="left" style="width: 300px;">
    	<div class="bg_c" style="width: 280px;">
        	<h1 class="big"><?=$langBase->get('comp-48')?></h1>
            <p><?=$langBase->get('txt-03')?> / <?=$langBase->get('c_firma-14')?>: <?=View::CashFormat($firmatype['extra']['bullet_price'])?> $</p>
            <div class="hr big" style="margin: 10px 0 10px 0;"></div>
            <form method="post" action="">
            	<dl class="dd_right">
                	<dt><?=$langBase->get('ot-bullets')?></dt>
                    <dd><input type="text" name="produce_bullets" class="flat" value="<?=View::CashFormat(View::NumbersOnly($_POST['release_bullets']))?>" /></dd>
                </dl>
                <p class="center clear"><?=($production_timeleft > 0 ? '<span class="red">' .$langBase->get('santaj-07', array('-TIME-' => $production_timeleft)) . '</span>' : '<input type="submit" value="'.$langBase->get('comp-48').'" />')?></p>
            </form>
        </div>
    </div>
    <div class="left" style="width: 300px; margin-left: 20px;">
    <div class="bg_c" style="width: 280px;">
        	<h1 class="big"><?=$langBase->get('comp-131')?></h1>
            <p><?=$langBase->get('comp-132', array('-BULLETS1-' => View::CashFormat($firma_misc['bullets_stored']), '-BULLETS2-' => View::CashFormat($firma_misc['bullets'])))?></p>
            <div class="hr big" style="margin: 10px 0 10px 0;"></div>
            <form method="post" action="">
            	<dl class="dd_right">
                	<dt><?=$langBase->get('casa-25')?></dt>
                    <dd><input type="text" name="release_bullets" class="flat" value="<?=View::CashFormat(View::NumbersOnly($_POST['release_bullets']))?> gloante" /></dd>
                </dl>
                <p class="center clear"><?=($release_timeleft > 0 ? '<span class="red">' .$langBase->get('santaj-07', array('-TIME-' => $release_timeleft)) . '</span>' : '<input type="submit" value="'.$langBase->get('comp-131').'" />')?></p>
            </form>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php
			}
		}
	}
	elseif ($option == 'settings')
	{
		if (isset($_POST['f_image']))
		{
			$name = $db->EscapeString($_POST['f_name']);
			$image = $db->EscapeString($_POST['f_image']);
			$info  = $db->EscapeString($_POST['f_info']);
			$a_s   = isset($_POST['a_s']) ? '1' : '0';
			$valid_exts = array("jpg","jpeg","gif","png");
			$extim = end(explode(".",strtolower(basename($image))));
			
			if ($image == '')
				$image = $config['business_default_image'];
			
			if ($image == $firma['image'] && $info == $firma['info'] && $name == $firma['name'] && $a_s == $firma['accepts_soknader'])
			{
				echo View::Message($langBase->get('comp-135'), 1);
			}
			elseif (strlen($name) < $firmatype['extra']['min_name_length'])
			{
				echo View::Message($langBase->get('msg-10', array('-NUM-' => $firmatype['extra']['min_name_length'])), 1);
			}
			elseif (strlen($name) > $firmatype['extra']['max_name_length'])
			{
				echo View::Message($langBase->get('msg-11', array('-NUM-' => $firmatype['extra']['max_name_length'])), 1);
			}
			elseif (!preg_match('%\A(?:^((http[s]?|ftp):/)?/?([^:/\s]+)(:([^/]*))?((/\w+)*/)([\w\-.]+[^#?\s]+)(\?([^#]*))?(#(.*))?$)\Z%', $image) && $image != $config['business_default_image'])
			{
				echo View::Message('BAD IMAGE', 2);
			}
			elseif (!in_array($extim,$valid_exts))
			{
				echo View::Message('Imaginea trebuie sa fie in format JPG, PNG sau GIF', 2);
			}
			else
			{
				$db->Query("UPDATE `businesses` SET `name`='$name', `image`='$image', `info`='$info', `accepts_soknader`='".$a_s."' WHERE `id`='".$firma['id']."'");
				
				echo View::Message($langBase->get('comp-61'), 1, true);
			}
		}
?>

<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('comp-41')?></h1>
    <form method="post" action="" id="settings_form">
        <dl class="form" style="margin: 0;">
			<dt><?=$langBase->get('txt-02')?></dt>
            <dd><input type="text" name="f_name" class="styled" value="<?=(isset($_POST['f_name']) ? $_POST['f_name'] : $firma['name'])?>" style="width: 400px;" /></dd>
            <dt><?=$langBase->get('comp-93')?></dt>
            <dd><input type="text" name="f_image" class="styled" value="<?=(isset($_POST['f_image']) ? $_POST['f_image'] : $firma['image'])?>" style="width: 400px;" /></dd>
            <dt><?=$langBase->get('txt-22')?></dt>
            <dd><textarea name="f_info" cols="63" rows="12" style="width: 400px;"><?=(isset($_POST['f_info']) ? $_POST['f_info'] : $firma['info'])?></textarea></dd>
            <dt><?=$langBase->get('comp-04')?></dt>
            <dd><input type="checkbox" name="a_s" style="margin: 5px;"<?php if($firma['accepts_soknader'] == 1) echo ' checked="checked"'; ?> /></dd>
        </dl>
        <p class="center clear">
        	<input type="submit" value="<?=$langBase->get('min-56')?>" />
        </p>
    </form>
</div>
<?php
	}
	elseif ($option == 'sl' && $ownerLevel == 1)
	{
		$jobTitle = $config['business_types'][$firma['type']]['job_titles'][1];
		
		$sql = $db->Query("SELECT id,rank,last_active,userid,level,name FROM `[players]` WHERE `id`='".$firma['job_2']."'");
		$current = $db->FetchArray($sql);
		
		if (isset($_GET['s']))
		{
			$soknad = $db->EscapeString($_GET['s']);
			$sql = $db->Query("SELECT * FROM `soknader` WHERE `id`='".$soknad."' AND `receiver`='1," . $firma['id'] . "' AND `sent`!='0' AND `handled`='0' AND `deleted`='0'");
			$soknad = $db->FetchArray($sql);
			
			if ($soknad['id'] == '')
			{
				View::Message('ERROR', 2, true, '/game/?side='.$_GET['side'].'&id='.$firma['id'].'&a='.$_GET['a']);
			}
			
			$text = new BBCodeParser($soknad['text'], 'firma_soknad', true);
			
			$treatment = $langBase->get('cereri-07');
			if ($soknad['handled'] == 1) {
				$treatment = $langBase->get('cereri-08');
			} elseif ($soknad['handled'] == 2) {
				$treatment = $langBase->get('cereri-09');
			}
			
			
			if (isset($_POST['approve']))
			{
				$db->Query("UPDATE `soknader` SET `handled`='2' WHERE `id`='".$soknad['id']."'");
				
				View::Message($langBase->get('cereri-13'), 1, true, '/game/?side='.$_GET['side'].'&id='.$firma['id'].'&a='.$_GET['a']);
			}
			elseif (isset($_POST['reject']))
			{
				$db->Query("UPDATE `soknader` SET `handled`='1' WHERE `id`='".$soknad['id']."'");
				
				View::Message($langBase->get('cereri-18'), 1, true, '/game/?side='.$_GET['side'].'&id='.$firma['id'].'&a='.$_GET['a']);
			}
?>
<div class="bg_c left" style="width: 230px;">
	<h1 class="big"><?=$langBase->get('cereri-24')?></h1>
    <div class="bg_c c_1 w200" style="margin: 0 auto 0;">
    	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
        <dl class="dd_right">
            <dt><?=$langBase->get('txt-31')?></dt>
            <dd><?=View::Player(array('id' => $soknad['from_player']))?></dd>
            <dt><?=$langBase->get('txt-27')?></dt>
            <dd><?=View::Time($soknad['sent'], true)?></dd>
            <dt><?=$langBase->get('ot-status')?></dt>
            <dd><?=$treatment?></dd>
        </dl>
        <div class="clear"></div>
    </div>
    <form method="post" action="">
        <p class="center">
        	<input type="submit" value="<?=$langBase->get('comp-89')?>" name="approve" />
            <input type="submit" value="<?=$langBase->get('comp-90')?>" name="reject" style="margin-left: 5px;" />
        </p>
    </form>
</div>
<div class="bg_c left" style="width: 356px; margin-left: 10px;">
	<h1 class="big"><?=$langBase->get('comp-133')?></h1>
    <?php
    if ($soknad['show_playerhistory'] == 1)
	{
		$sql = $db->Query("SELECT id,rank,last_active,level,name,health FROM `[players]` WHERE `userid`='".$soknad['from_user']."' ORDER BY last_active DESC");
		$players = $db->FetchArrayAll($sql);
	?>
	<div class="bg_c c_1 w300" style="margin-top: 0;">
    	<h1 class="big"><?=$langBase->get('comp-42')?></h1>
        <?php
		if (count($players) > 0)
		{
		?>
        <table class="table">
        	<thead>
            	<tr>
                	<td width="34%"><?=$langBase->get('txt-02')?></td>
                    <td width="33%"><?=$langBase->get('ot-rank')?></td>
                    <td width="33%"><?=$langBase->get('ot-lasta')?></td>
                </tr>
            </thead>
            <tbody>
            <?php
			foreach ($players as $player)
			{
				$i++;
				$c = $i%2 ? 1 : 2;
			?>
            	<tr class="c_<?=$c?>">
                	<td width="34%"><?=View::Player($player)?></td>
                    <td width="33%" class="center"><?=$config['ranks'][$player['rank']][0]?></td>
                    <td width="33%" class="t_right"><?=View::Time($player['last_active'])?></td>
                </tr>
            <?php
			}
			?>
            	<tr class="c_3"><td colspan="3"></td></tr>
            </tbody>
        </table>
        <?php
		}
		?>
    </div>
    <div class="hr big" style="margin-bottom: 15px;"></div>
    <?php
	}
	
	echo $text->result;
	?>
</div>
<div class="clear"></div>
<?php
		}
		else
		{
			$sql = "SELECT id,sent,show_playerhistory,from_player FROM `soknader` WHERE `receiver`='1," . $firma['id'] . "' AND `sent`!='0' AND `handled`='0' AND `deleted`='0' ORDER BY id DESC";
			$pagination = new Pagination($sql, 10, 'p');
			$pagination_links = $pagination->GetPageLinks();
			$soknader = $pagination->GetSQLRows();
			
			if (isset($_POST['fire_sl']))
			{
				if ($current['id'] == '')
				{
					View::Message($langBase->get('err-02'), 2, true);
				}
				else
				{
					$db->Query("UPDATE `businesses` SET `job_2`='0' WHERE `id`='".$firma['id']."'");
					
					Accessories::AddLogEvent($current['id'], 16, array(
						'-COMPANY_IMG-' => $firma['image'],
						'-COMPANY_NAME-' => $firma['name'],
						'-COMPANY_ID-' => $firma['id']
					), $current['userid']);
					
					$db->Query("INSERT INTO `business_log` (`b_id`, `text`, `type`, `added`, `added_date`)VALUES('".$firma['id']."', '".View::Player($current, true)." a fost concediat!', '5', '".time()."', '".date('d.m.Y')."')");
					
					View::Message($langBase->get('comp-134'), 1, true);
				}
			}
			elseif (isset($_POST['approve']))
			{
				$soknad = $soknader[$_POST['soknad']];
				
				if (!empty($soknad))
				{
					$db->Query("UPDATE `soknader` SET `handled`='2' WHERE `id`='".$soknad['id']."'");
					unset($soknader[$_POST['soknad']]);
					
					View::Message($langBase->get('cereri-13'), 1, true);
				}
			}
			elseif (isset($_POST['reject']))
			{
				$soknad = $soknader[$_POST['soknad']];
				
				if (!empty($soknad))
				{
					$db->Query("UPDATE `soknader` SET `handled`='1' WHERE `id`='".$soknad['id']."'");
					unset($soknader[$_POST['soknad']]);
					
					View::Message($langBase->get('cereri-08'), 1, true);
				}
			}
?>
<div class="bg_c w200 left" style="margin-left: 13px;">
	<h1 class="big"><?=$jobTitle?></h1>
    <?php
	if ($current['id'] == '')
	{
		echo '<h2 class="center">N/A</h2>';
	}
	else
	{
	?>
    <dl class="dd_right">
    	<dt><?=$langBase->get('txt-02')?></dt>
        <dd><?=View::Player($current)?></dd>
        <dt><?=$langBase->get('ot-rank')?></dt>
        <dd><?=$config['ranks'][$current['rank']][0]?></dd>
        <dt><?=$langBase->get('ot-lasta')?></dt>
        <dd><?=View::Time($current['last_active'], true)?></dd>
    </dl>
    <div class="clear"></div>
    <form method="post" action="">
        <p class="center">
            <input type="submit" value="<?=$langBase->get('crew-01')?>" name="fire_sl" onclick="return confirm('<?=$langBase->get('err-05')?>')" />
        </p>
    </form>
    <?php
	}
	?>
</div>
<div class="bg_c left" style="width: 350px; margin-left: 20px;">
	<h1 class="big"><?=$langBase->get('cereri-31')?></h1>
    <?php
	if (count($soknader) > 0)
	{
	?>
    <form method="post" action="">
        <table class="table boxHandle">
            <thead>
                <tr>
                    <td width="40%"><?=$langBase->get('txt-06')?></td>
                    <td width="35%"><?=$langBase->get('txt-27')?></td>
                    <td width="25%"></td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($soknader as $s_key => $soknad)
                {
                    $i++;
                    $c = $i%2 ? 1 : 2;
                ?>
                <tr class="c_<?=$c?> boxHandle">
                    <td width="40%"><input type="radio" name="soknad" value="<?=$s_key?>" /><?=View::Player(array('id' => $soknad['from_player']))?></td>
                    <td width="35%" class="t_right"><?=View::Time($soknad['sent'], true)?></td>
                    <td width="25%" class="t_right"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$firma['id']?>&amp;a=sl&amp;s=<?=$soknad['id']?>"><?=$langBase->get('cereri-35')?></a></td>
                </tr>
                <?php
                }
                ?>
                <tr class="c_3"><td colspan="3"><?=$pagination_links?></td></tr>
                <tr class="c_3 center">
                    <td colspan="3">
                    	<input type="submit" name="approve" value="<?=$langBase->get('comp-89')?>" onclick="return confirm('<?=$langBase->get('err-05')?>')" />
                        <input type="submit" name="reject" value="<?=$langBase->get('comp-90')?>" onclick="return confirm('<?=$langBase->get('err-05')?>')" style="margin-left: 5px;" />
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    <?php
	}else{ echo '<p>'.$langBase->get('err-06').'</p>';}
	?>
</div>
<div class="clear"></div>
<?php
		}
	}
?>