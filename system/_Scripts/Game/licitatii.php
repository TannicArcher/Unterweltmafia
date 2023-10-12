<div class="script_header">
	<img src="<?=$config['base_url']?>images/script_headers/auktionen.jpg" alt="" />
<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	function highestBid($bids, $return_key = false)
	{		
		foreach ($bids as $key => $bid)
		{
			if ($bid['sum'] > $highest['sum'])
			{
				$highest_key = $key;
			}
		}
		
		$highest = $bids[$highest_key] ? $bids[$highest_key] : false;
		
		return $return_key === true ? $highest_key : $highest;
	}
	
	$sql = $db->Query("SELECT id,bids,object_type,object_id,end_time,payment_method,added_by FROM `auctions` WHERE `active`='1'");
	while ($auction = $db->FetchArray($sql))
	{
		if ($auction['end_time'] <= time())
		{
			$db->Query("UPDATE `auctions` SET `active`='0' WHERE `id`='".$auction['id']."'");
			
			$bids = unserialize($auction['bids']);
			$highest_bid = highestBid($bids);
			
			if (!$highest_bid)
			{
				continue;
			}
			
			$payment_key = $auction['payment_method'];
			$payment_method = $config['auction_payment_methods'][$payment_key];
			
			if ($auction['object_type'] == 'Company')
			{
				$sql = $db->Query("SELECT id,name,job_1,active,bank,image FROM `businesses` WHERE `id`='".$auction['object_id']."'");
				$firma = $db->FetchArray($sql);
				
				if ($firma['active'] != 1)
				{
					$db->Query("UPDATE `[players]` SET `".$payment_key."`=`".$payment_key."`+'".$highest_bid['sum']."' WHERE `id`='".$highest_bid['player']."'");
				}
				else
				{
					$db->Query("UPDATE `businesses` SET `bank`='0', `job_1`='".$highest_bid['player']."' WHERE `id`='".$firma['id']."'");
					$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$firma['bank']."' WHERE `id`='".$firma['job_1']."'");
					$db->Query("UPDATE `[players]` SET `".$payment_key."`=`".$payment_key."`+'".$highest_bid['sum']."' WHERE `id`='".$auction['added_by']."'");
					
					Accessories::AddLogEvent($firma['job_1'], 17, array(
						'-COMPANY_NAME-' => $firma['name'],
						'-COMPANY_ID-' => $firma['id']
					));
					
					Accessories::AddLogEvent($highest_bid['player'], 32, array(
						'-COMPANY_NAME-' => $firma['name'],
						'-COMPANY_ID-' => $firma['id']
					));
				}
			}
			elseif ($auction['object_type'] == 'Coinroll')
			{
				$sql = $db->Query("SELECT id,owner,place,bank,active FROM `coinroll` WHERE `id`='".$auction['object_id']."'");
				$coinroll = $db->FetchArray($sql);
				
				if ($coinroll['active'] != 1)
				{
					$db->Query("UPDATE `[players]` SET `".$payment_key."`=`".$payment_key."`+'".$highest_bid['sum']."' WHERE `id`='".$highest_bid['player']."'");
				}
				else
				{
					$db->Query("UPDATE `coinroll` SET `bank`='0', `max_bet`='100', `owner`='".$highest_bid['player']."' WHERE `id`='".$coinroll['id']."'");
					$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$coinroll['bank']."' WHERE `id`='".$coinroll['owner']."'");
					$db->Query("UPDATE `[players]` SET `".$payment_key."`=`".$payment_key."`+'".$highest_bid['sum']."' WHERE `id`='".$auction['added_by']."'");
					
					Accessories::AddLogEvent($coinroll['owner'], 48, array(
						'-PLACE-' => $config['places'][$coinroll['place']][0]
					));
					
					Accessories::AddLogEvent($highest_bid['player'], 49, array(
						'-PLACE-' => $config['places'][$coinroll['place']][0]
					));
				}
			}
			elseif ($auction['object_type'] == 'Scratch Tickets')
			{
				$sql = $db->Query("SELECT id,owner,place,bank,active FROM `lozuri` WHERE `id`='".$auction['object_id']."'");
				$lozuri = $db->FetchArray($sql);
				
				if ($lozuri['active'] != 1)
				{
					$db->Query("UPDATE `[players]` SET `".$payment_key."`=`".$payment_key."`+'".$highest_bid['sum']."' WHERE `id`='".$highest_bid['player']."'");
				}
				else
				{
					$db->Query("UPDATE `lozuri` SET `bank`='0', `lozuri`='10', `owner`='".$highest_bid['player']."' WHERE `id`='".$lozuri['id']."'");
					$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$lozuri['bank']."' WHERE `id`='".$lozuri['owner']."'");
					$db->Query("UPDATE `[players]` SET `".$payment_key."`=`".$payment_key."`+'".$highest_bid['sum']."' WHERE `id`='".$auction['added_by']."'");
					
					Accessories::AddLogEvent($coinroll['owner'], 56, array(
						'-PLACE-' => $config['places'][$lozuri['place']][0]
					));
					
					Accessories::AddLogEvent($highest_bid['player'], 57, array(
						'-PLACE-' => $config['places'][$lozuri['place']][0]
					));
				}
			}
			elseif ($auction['object_type'] == 'Family')
			{
				$sql = $db->Query("SELECT id,name,boss,active,bank,image,members FROM `[families]` WHERE `id`='".$auction['object_id']."'");
				$family = $db->FetchArray($sql);
				
				if ($family['active'] != 1)
				{
					$db->Query("UPDATE `[players]` SET `".$payment_key."`=`".$payment_key."`+'".$highest_bid['sum']."' WHERE `id`='".$highest_bid['player']."'");
				}
				else
				{
					$members = unserialize($family['members']);
					unset($members[$family['boss']]);
					
					$members[$highest_bid['player']] = array(
						'player' => $highest_bid['player'],
						'added' => time()
					);
					
					$db->Query("UPDATE `[players]` SET `family`='".$family['id']."' WHERE `id`='".$highest_bid['player']."'");
					$db->Query("UPDATE `[families]` SET `bank`='0', `boss`='".$highest_bid['player']."', `members`='".serialize($members)."' WHERE `id`='".$family['id']."'");
					$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$family['bank']."' WHERE `id`='".$family['boss']."'");
					$db->Query("UPDATE `[players]` SET `".$payment_key."`=`".$payment_key."`+'".$highest_bid['sum']."' WHERE `id`='".$auction['added_by']."'");
					
					$sql = $db->Query("SELECT id,family FROM `[players]` WHERE `id`='".$highest_bid['player']."'");
					$winner = $db->FetchArray($sql);
					
					if ($winner['family'] != 0 && $winner['family'] != $family['id'])
					{
						$sql = $db->Query("SELECT id,members,boss,underboss FROM `[families]` WHERE `id`='".$winner['family']."' AND `active`='1'");
						$fam = $db->FetchArray($sql);
						
						$members = unserialize($fam['members']);
						unset($members[$winner['id']]);
						
						$db->Query("UPDATE `[families]` SET `members`='".serialize($members)."' WHERE `id`='".$fam['id']."'");
						
						if ($winner['id'] == $fam['boss'])
						{
							$db->Query("UPDATE `[families]` SET `boss`='0', `underboss`=`boss` WHERE `id`='".$fam['id']."'");
						}
						if ($winner['id'] == $fam['underboss'])
						{
							$db->Query("UPDATE `[families]` SET `underboss`='0' WHERE `id`='".$fam['id']."'");
						}

						$sql = $db->Query("SELECT id,guards FROM `family_businesses` WHERE `family`='".$fam['id']."'");
						while ($business = $db->FetchArray($sql))
						{
							$guards = unserialize($business['guards']);
							
							foreach ($guards as $key => $guard)
							{
								if ($guard['player'] == $winner['id'])
								{
									unset($guards[$key]);
									$change = true;
									break;
								}
							}
							
							if ($change === true)
							{
								$db->Query("UPDATE `family_businesses` SET `guards`='".serialize($guards)."' WHERE `id`='".$business['id']."'");
							}
						}
					}
					
					Accessories::AddLogEvent($firma['job_1'], 38, array(
						'-FAMILY_NAME-' => $family['name'],
						'-FAMILY_ID-' => $family['id']
					));
					
					Accessories::AddLogEvent($highest_bid['player'], 37, array(
						'-FAMILY_NAME-' => $family['name'],
						'-FAMILY_ID-' => $family['id']
					));
				}
			}
		}
	}
	
	if (isset($_GET['create']))
	{
		$objects = array();
		
		$sql = $db->Query("SELECT id,name FROM `businesses` WHERE `job_1`='".Player::Data('id')."' AND `active`='1'");
		while ($firma = $db->FetchArray($sql))
		{
			$objects[] = array('Company', $firma['id'], $firma['name']);
		}
		
		$sql = $db->Query("SELECT id,name FROM `[families]` WHERE `boss`='".Player::Data('id')."' AND `active`='1'");
		while ($family = $db->FetchArray($sql))
		{
			$objects[] = array('Family', $family['id'], $family['name']);
		}
		
		$sql = $db->Query("SELECT id,place FROM `coinroll` WHERE `active`='1' AND `owner`='".Player::Data('id')."'");
		while ($coinroll = $db->FetchArray($sql))
		{
			$objects[] = array('Coinroll', $coinroll['id'], $config['places'][$coinroll['place']][0]);
		}
		
		$sql = $db->Query("SELECT id,place FROM `lozuri` WHERE `active`='1' AND `owner`='".Player::Data('id')."'");
		while ($lozuri = $db->FetchArray($sql))
		{
			$objects[] = array('Scratch Tickets', $lozuri['id'], $config['places'][$lozuri['place']][0]);
		}
		
		if (isset($_POST['cancel']))
		{
			header('Location: /game/?side=' . $_GET['side']);
			exit;
		}
		elseif (isset($_POST['auction_object']))
		{
			$object_key = $db->EscapeString($_POST['auction_object']);
			$object = $objects[$object_key];
			
			$bid_start = View::NumbersOnly($db->EscapeString($_POST['auction_start']));
			$bid_min_increase = View::NumbersOnly($db->EscapeString($_POST['auction_min_increase']));
			$end_time = strtotime($_POST['auction_end']);
			
			$payment_key = $db->EscapeString(strtolower($_POST['payment_method']));
			$payment_method = $config['auction_payment_methods'][$payment_key];
			
			if (!$payment_method)
			{
				echo View::Message($langBase->get('msgContent-error'), 2);
			}
			elseif (!$object)
			{
				echo View::Message($langBase->get('msgContent-error'), 2);
			}
			elseif ($db->GetNumRows($db->Query("SELECT id FROM `auctions` WHERE `object_id`='".$object[1]."' AND `object_type`='".$object[0]."' AND `active`='1'")) > 0)
			{
				echo View::Message($langBase->get('auction-001'), 2);
			}
			elseif ($bid_start < ($payment_key == 'cash' ? 100 : 5))
			{
				echo View::Message($langBase->get('auction-002', array('-AMOUNT-' => $payment_key == 'cash' ? 100 : 5). ' ' . $payment_method[1]), 2);
			}
			elseif ($bid_min_increase < ($payment_key == 'cash' ? 100 : 1))
			{
				echo View::Message($langBase->get('auction-003', array('-AMOUNT-' => $payment_key == 'cash' ? 100 : 5).' '.$payment_method[1]), 2);
			}
			elseif ($end_time === false)
			{
				echo View::Message($langBase->get('auction-004'), 2);
			}
			elseif ($end_time < time() + 600)
			{
				echo View::Message($langBase->get('auction-005'), 2);
			}
			else
			{
				$db->Query("INSERT INTO `auctions` (`added_by`, `added_by_ip`, `object_type`, `object_id`, `bid_start`, `smallest_increase`, `end_time`, `added_time`, `payment_method`)VALUES('".Player::Data('id')."', '".$_SERVER['REMOTE_ADDR']."', '".$object[0]."', '".$object[1]."', '".$bid_start."', '".$bid_min_increase."', '".$end_time."', '".time()."', '".$payment_key."')");
				
				View::Message($langBase->get('auction-006'), 1, true, '/game/?side=' . $_GET['side']);
			}
		}
?>
<div class="bg_c w300">
	<h1 class="big"><?=$langBase->get('auction-create')?></h1>
    <form method="post" action="">
        <dl class="dd_right">
            <dt><?=$langBase->get('auction-create')?></dt>
            <dd><select name="auction_object"><option><?=$langBase->get('auction-014')?>...</option><?php foreach($objects as $key => $object){ echo '<option value="'.$key.'"'.($_POST['auction_object'] === $key ? ' selected="selected"' : '').'>'.$object[0].' - '.$object[2].'</option>'; } ?></select></dd>
            <dt><?=$langBase->get('auction-payment')?></dt>
            <dd><select name="payment_method"><?php foreach($config['auction_payment_methods'] as $key => $method){ echo '<option value="'.$key.'"'.($_POST['payment_method'] == $key ? ' selected="selected"' : '').'>'.$method[0].'</option>'; } ?></select></dd>
            <dt><?=$langBase->get('auction-bidstart')?></dt>
            <dd><input type="text" class="flat" name="auction_start" value="<?=View::CashFormat($_POST['auction_start'])?> $" /></dd>
            <dt><?=$langBase->get('auction-min_increase')?></dt>
            <dd><input type="text" class="flat" name="auction_min_increase" value="<?=View::CashFormat($_POST['auction_min_increase'])?> $" /></dd>
            <dt><?=$langBase->get('auction-endtime')?></dt>
            <dd><input type="text" class="flat" name="auction_end" value="<?=(isset($_POST['auction_end']) ? (strtotime($_POST['auction_end']) === false ? date('j.m.Y H:i', time() + 604800) : $_POST['auction_end']) : date('j.m.Y H:i', time() + 604800))?>" /></dd>
        </dl>
        <p class="center clear">
        	<input type="submit" value="<?=$langBase->get('auction-create')?>" />
            <input type="submit" name="cancel" value="<?=$langBase->get('ot-cancel')?>" style="margin-left: 5px;" />
        </p>
    </form>
</div>
<?php
	}
	else
	{
		if (isset($_GET['id']))
		{
			$auction = $db->EscapeString($_GET['id']);
			$sql = $db->Query("SELECT * FROM `auctions` WHERE `id`='".$auction."'");
			$auction = $db->FetchArray($sql);
			
			if ($auction['id'] == '')
			{
				View::Message($langBase->get('bad_inputs'), 2, true, '/game/?side=' . $_GET['side']);
			}
			
			$payment_key = $auction['payment_method'];
			$payment_method = $config['auction_payment_methods'][$payment_key];
			
			$bids = unserialize($auction['bids']);
			$highest_bid = highestBid($bids);
			
			$min_bid = $highest_bid ? ($highest_bid['sum'] + $auction['smallest_increase']) : $auction['bid_start'];
			
			$auctionName = 'Anonim';
			if ($auction['object_type'] == 'Company')
			{
				$sql = $db->Query("SELECT id,name FROM `businesses` WHERE `id`='".$db->EscapeString($auction['object_id'])."'");
				$firma = $db->FetchArray($sql);
				$auctionName = '<a href="' . $config['base_url'] . '?side=firma/firma&amp;id=' . $firma['id'] . '">' . $firma['name'] . '</a>';
			}
			elseif ($auction['object_type'] == 'Family')
			{
				$sql = $db->Query("SELECT id,name FROM `[families]` WHERE `id`='".$db->EscapeString($auction['object_id'])."'");
				$family = $db->FetchArray($sql);
				$auctionName = '<a href="' . $config['base_url'] . '?side=familie/familie&amp;id=' . $family['id'] . '">' . $family['name'] . '</a>';
			}
			elseif ($auction['object_type'] == 'Coinroll')
			{
				$sql = $db->Query("SELECT place FROM `coinroll` WHERE `id`='".$db->EscapeString($auction['object_id'])."'");
				$coinroll = $db->FetchArray($sql);
				$auctionName = $config['places'][$coinroll['place']][0];
			}
			elseif ($auction['object_type'] == 'Scratch Tickets')
			{
				$sql = $db->Query("SELECT place FROM `lozuri` WHERE `id`='".$db->EscapeString($auction['object_id'])."'");
				$lozuri = $db->FetchArray($sql);
				$auctionName = $config['places'][$lozuri['place']][0];
			}
			
			if (isset($_POST['add_bid']) && $auction['active'] == 1)
			{
				if ($auction['added_by'] == Player::Data('id'))
				{
					View::Message($langBase->get('auction-007'), 2, true);
				}
				
				$sql = $db->Query("SELECT id,bids FROM `auctions` WHERE `id`='".$auction['id']."' AND `active`='1'");
				$auc = $db->FetchArray($sql);
				
				if ($auc['id'] == '')
				{
					echo View::Message($langBase->get('auction-008'), 2);
				}
				else
				{
					$bid = View::NumbersOnly($db->EscapeString($_POST['add_bid']));
					$bids = unserialize($auc['bids']);
					
					$highest_bid = highestBid($bids);
					$min_bid = $highest_bid ? ($highest_bid['sum'] + $auction['smallest_increase']) : $auction['bid_start'];
					
					if ($bid > Player::Data($payment_key))
					{
						echo View::Message($langBase->get('auction-009', array('-CURRENCY-' => strtolower($payment_method[0]))), 2);
					}
					elseif ($bid < $min_bid)
					{
						echo View::Message($langBase->get('auction-010', array('-AMOUNT-' => (View::CashFormat($min_bid) . ' ' . strtolower($payment_method[1])))), 2);
					}
					else
					{
						if ($highest_bid['player'] == Player::Data('id'))
						{
							$bids[highestBid($bids, true)] = array(
								'player' => Player::Data('id'),
								'sum' => $bid,
								'time' => time()
							);
							
							$bid -= $highest_bid['sum'];
						}
						else
						{
							$bids[] = array(
								'player' => Player::Data('id'),
								'sum' => $bid,
								'time' => time()
							);
							
							$db->Query("UPDATE `[players]` SET `".$payment_key."`=`".$payment_key."`+'".$highest_bid['sum']."' WHERE `id`='".$highest_bid['player']."'");
						}
						
						$db->Query("UPDATE `auctions` SET `bids`='".serialize($bids)."' WHERE `id`='".$auc['id']."'");
						$db->Query("UPDATE `[players]` SET `".$payment_key."`=`".$payment_key."`-'".$bid."' WHERE `id`='".Player::Data('id')."'");
						
						View::Message($langBase->get('auction-011'), 1, true);
					}
				}
			}
			elseif (isset($_POST['auction_remove']) && $auction['active'] == 1 && ($auction['added_by'] == Player::Data('id') || Player::Data('level') == 4))
			{
				if (count($bids) <= 0)
				{
					$db->Query("UPDATE `auctions` SET `active`='0' WHERE `id`='".$auction['id']."'");
					View::Message($langBase->get('auction-012'), 1, true);
				}
				else
				{
					$db->Query("UPDATE `auctions` SET `end_time`='".(time() - 1)."' WHERE `id`='".$auction['id']."'");
					View::Message($langBase->get('auction-013'), 1, true);
				}
			}
?>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('auction-auction')?></h1>
    <div class="bg_c c_1 w250">
    	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
        <dl class="dd_right">
        	<dt><?=$langBase->get('auction-auction')?></dt>
            <dd><?=$auction['object_type']?> - &laquo;<?=$auctionName?>&raquo;</dd>
            <dt><?=$langBase->get('auction-starttime')?></dt>
            <dd><?=View::Time($auction['added_time'], true)?></dd>
            <dt><?=$langBase->get('auction-endtime')?></dt>
            <dd><?=View::Time($auction['end_time'], true)?></dd>
            <dt><?=$langBase->get('auction-payment')?></dt>
            <dd><?=$payment_method[0]?></dd>
            <dt><?=$langBase->get('auction-bidstart')?></dt>
            <dd><?=View::CashFormat($auction['bid_start'])?> <?=$payment_method[1]?></dd>
            <dt><?=$langBase->get('auction-min_increase')?></dt>
            <dd><?=View::CashFormat($auction['smallest_increase'])?> <?=$payment_method[1]?></dd>
            <?php
			if ($auction['active'] == 1):
			?>
            <dt><?=$langBase->get('ot-status')?></dt>
            <dd><?=$langBase->get('ot-status_open')?></dd>
            <dt><?=$langBase->get('ot-highest_bid')?></dt>
            <dd><?=($highest_bid ? (View::Player(array('id' => $highest_bid['player'])) . '<br />' . View::CashFormat($highest_bid['sum']) . ' ' . $payment_method[1]) : $langBase->get('ot-none'))?></dd>
            <dt><?=$langBase->get('auction-ends_in')?></dt>
            <dd><span class="countdown"><?=($auction['end_time'] - time())?></span> <?=strtolower($langBase->get('ot-seconds'))?></dd>
            <?php
			else:
			?>
            <dt><?=$langBase->get('ot-status')?></dt>
            <dd><?=$langBase->get('ot-status_ended')?></dd>
            <dt><?=$langBase->get('ot-winner_bid')?></dt>
            <dd><?=($highest_bid ? (View::Player(array('id' => $highest_bid['player'])) . '<br />' . View::CashFormat($highest_bid['sum']) . ' ' . $payment_method[1]) : $langBase->get('ot-none'))?></dd>
            <?php
			endif;
			?>
        </dl>
    </div>
    <div class="clear"></div>
    <?php
	if (($auction['added_by'] == Player::Data('id') || Player::Data('level') == 4) && $auction['active'] == 1):
	?>
    <form method="post" action="" class="center">
        <input type="submit" name="auction_remove" value="<?=(count($bids) <= 0 ? $langBase->get('auction-delete_auction') : $langBase->get('auction-end_auction'))?>" />
    </form>
    <?php
	endif;
	
	if ($auction['active'] == 1):
	?>
    <div class="bg_c c_1 left" style="width: 200px; margin-right: 10px;">
        <h1 class="big"><?=$langBase->get('auction-make_bid')?></h1>
        <form method="post" action="">
        	<dl class="dd_right">
            	<dt><?=$langBase->get('auction-bid')?></dt>
                <dd><input type="text" name="add_bid" class="flat" value="<?=View::CashFormat($min_bid)?> <?=$payment_method[1]?>" /></dd>
            </dl>
            <p class="center clear">
            	<input type="submit" value="<?=$langBase->get('auction-bid')?>" />
            </p>
        </form>
    </div>
    <?php
	endif;
	?>
    <div class="bg_c c_1<?=($auction['active'] == 1 ? ' left' : '')?>" style="width: 245px;">
        <h1 class="big"><?=$langBase->get('auction-015')?></h1>
        <?php
		if (count($bids) <= 0)
		{
			echo '<p>' . $langBase->get('ot-nofind', array('-WHAT-' => strtolower($langBase->get('auction-bids')))) . '</p>';
		}
		else
		{
			echo '<dl class="dd_right">';
			
			$h_key = highestBid($bids, true);
			foreach ($bids as $key => $bid)
			{
				echo '<dt>'.($key != $h_key ? '<s>' : '').'' . View::Player(array('id' => $bid['player']), true) . ' ' . trim(View::Time($bid['time'], false, 'H:i', false)) . ''.($key != $h_key ? '</s>' : '').'</dt><dd>' . View::CashFormat($bid['sum']) . ' ' . $payment_method[1] . '</dd>';
			}
			
			echo '</dl>';
		}
		?>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php
		}
		else
		{
			$a_pagination = new Pagination("SELECT id,bids,end_time,object_type,object_id,payment_method FROM `auctions` WHERE `active`='1' ORDER BY id DESC", 10, 'a_p');
			$active_auctions = $a_pagination->GetSQLRows();
			
			$i_pagination = new Pagination("SELECT id,bids,end_time,object_type,object_id,payment_method FROM `auctions` WHERE `active`='0' ORDER BY end_time DESC", 10, 'i_p');
			$inactive_auctions = $i_pagination->GetSQLRows();
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('function-auction_house')?></h1>
    <p class="t_right">
    	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;create">&raquo; <?=$langBase->get('auction-make_bid')?></a>
    </p>
    <div class="bg_c c_1" style="width: 580px;">
    	<h1 class="big"><?=$langBase->get('auction-016')?></h1>
        <?php
		if (count($active_auctions) <= 0)
		{
			echo '<p>' . $langBase->get('ot-nofind', array('-WHAT-' => strtolower($langBase->get('auction-auctions')))) . '</p>';
		}
		else
		{
		?>
        <table class="table">
        	<thead>
            	<tr>
                	<td><?=$langBase->get('auction-auction')?></td>
                    <td><?=$langBase->get('auction-endtime')?></td>
                    <td colspan="2"><?=$langBase->get('auction-highest_bid')?></td>
                    <td><?=$langBase->get('auction-bids')?></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
            <?php
			foreach ($active_auctions as $auction)
			{
				$i++;
				$c = $i%2 ? 1 : 2;
				
				$bids = unserialize($auction['bids']);
				
				$auctionName = 'N/A';
				if ($auction['object_type'] == 'Company')
				{
					$sql = $db->Query("SELECT id,name FROM `businesses` WHERE `id`='".$db->EscapeString($auction['object_id'])."'");
					$firma = $db->FetchArray($sql);
					$auctionName = '<a href="' . $config['base_url'] . '?side=firma/firma&amp;id=' . $firma['id'] . '">' . $firma['name'] . '</a>';
				}
				elseif ($auction['object_type'] == 'Family')
				{
					$sql = $db->Query("SELECT id,name FROM `[families]` WHERE `id`='".$db->EscapeString($auction['object_id'])."'");
					$family = $db->FetchArray($sql);
					$auctionName = '<a href="' . $config['base_url'] . '?side=familie/familie&amp;id=' . $family['id'] . '">' . $family['name'] . '</a>';
				}
				elseif ($auction['object_type'] == 'Coinroll')
				{
					$sql = $db->Query("SELECT place FROM `coinroll` WHERE `id`='".$db->EscapeString($auction['object_id'])."'");
					$coinroll = $db->FetchArray($sql);
					$auctionName = $config['places'][$coinroll['place']][0];
				}
				elseif ($auction['object_type'] == 'Scratch Tickets')
				{
					$sql = $db->Query("SELECT place FROM `lozuri` WHERE `id`='".$db->EscapeString($auction['object_id'])."'");
					$coinroll = $db->FetchArray($sql);
					$auctionName = $config['places'][$coinroll['place']][0];
				}
				
				$highest_bid = highestBid($bids);
				
				$payment_method = $config['auction_payment_methods'][$auction['payment_method']];
			?>
            	<tr class="c_<?=$c?>">
                	<td><?=$auction['object_type']?><br />&laquo;<?=$auctionName?>&raquo;</td>
                    <td class="t_right"><?=View::Time($auction['end_time'])?></td>
                    <td class="center"><?=(!$highest_bid ? $langBase->get('ot-none') : View::Player(array('id' => $highest_bid['player'])))?></td>
                    <td class="t_right"><?=View::CashFormat($highest_bid['sum'])?> <?=$payment_method[1]?></td>
                    <td class="center"><?=count($bids)?></td>
                    <td class="t_right"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$auction['id']?>">&raquo; <?=$langBase->get('auction-bid')?></a></td>
                </tr>
            <?php
			}
			?>
            	<tr class="c_3">
                	<td colspan="6"><?=$a_pagination->GetPageLinks()?></td>
                </tr>
            </tbody>
        </table>
        <?php
		}
		?>
    </div>
    <div class="bg_c c_1" style="width: 580px;">
    	<h1 class="big"><?=$langBase->get('auction-017')?></h1>
        <?php
		if (count($inactive_auctions) <= 0)
		{
			echo '<p>' . $langBase->get('ot-nofind', array('-WHAT-' => strtolower($langBase->get('auction-auctions')))) . '</p>';
		}
		else
		{
		?>
        <table class="table">
        	<thead>
            	<tr>
                	<td><?=$langBase->get('auction-auction')?></td>
                    <td><?=$langBase->get('auction-endtime')?></td>
                    <td colspan="2"><?=$langBase->get('auction-winner_bid')?></td>
                    <td><?=$langBase->get('auction-bids')?></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
            <?php
			unset($i);
			
			foreach ($inactive_auctions as $auction)
			{
				$i++;
				$c = $i%2 ? 1 : 2;
				
				$bids = unserialize($auction['bids']);
				
				$auctionName = 'Unknown';
				if ($auction['object_type'] == 'Company')
				{
					$sql = $db->Query("SELECT id,name FROM `businesses` WHERE `id`='".$db->EscapeString($auction['object_id'])."'");
					$firma = $db->FetchArray($sql);
					$auctionName = '<a href="' . $config['base_url'] . '?side=firma/firma&amp;id=' . $firma['id'] . '">' . $firma['name'] . '</a>';
				}
				elseif ($auction['object_type'] == 'Family')
				{
					$sql = $db->Query("SELECT id,name FROM `[families]` WHERE `id`='".$db->EscapeString($auction['object_id'])."'");
					$family = $db->FetchArray($sql);
					$auctionName = '<a href="' . $config['base_url'] . '?side=familie/familie&amp;id=' . $family['id'] . '">' . $family['name'] . '</a>';
				}
				elseif ($auction['object_type'] == 'Coinroll')
				{
					$sql = $db->Query("SELECT place FROM `coinroll` WHERE `id`='".$db->EscapeString($auction['object_id'])."'");
					$coinroll = $db->FetchArray($sql);
					$auctionName = $config['places'][$coinroll['place']][0];
				}
				elseif ($auction['object_type'] == 'Scratch Tickets')
				{
					$sql = $db->Query("SELECT place FROM `lozuri` WHERE `id`='".$db->EscapeString($auction['object_id'])."'");
					$coinroll = $db->FetchArray($sql);
					$auctionName = $config['places'][$coinroll['place']][0];
				}
				
				$highest_bid = highestBid($bids);
				
				$payment_method = $config['auction_payment_methods'][$auction['payment_method']];
			?>
            	<tr class="c_<?=$c?>">
                	<td><?=$auction['object_type']?><br />&laquo;<?=$auctionName?>&raquo;</td>
                    <td class="t_right"><?=View::Time($auction['end_time'])?></td>
                    <td class="center"><?=(!$highest_bid ? $langBase->get('ot-none') : View::Player(array('id' => $highest_bid['player'])) . '<br />' . View::Time($highest_bid['time']))?></td>
                    <td class="t_right"><?=View::CashFormat($highest_bid['sum'])?> <?=$payment_method[1]?></td>
                    <td class="center"><?=count($bids)?></td>
                    <td class="t_right"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$auction['id']?>">&raquo; <?=$langBase->get('ot-view')?></a></td>
                </tr>
            <?php
			}
			?>
            	<tr class="c_3">
                	<td colspan="6"><?=$i_pagination->GetPageLinks()?></td>
                </tr>
            </tbody>
        </table>
        <?php
		}
		?>
    </div>
</div>
<?php
		}
	}
?>
</div>