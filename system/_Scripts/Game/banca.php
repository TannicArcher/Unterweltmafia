<div class="script_header">
	<img src="<?=$config['base_url']?>images/script_headers/bank.jpg" alt="" />
<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$sql = $db->Query("SELECT * FROM `bank_clients` WHERE `playerid`='".Player::Data('id')."' AND `accepted`='1' AND `active`='1' ORDER BY id DESC LIMIT 1");
	$realClient = $db->FetchArray($sql);
	
	$sql = $db->Query("SELECT * FROM `businesses` WHERE `type`='1' AND `id`='".$realClient['b_id']."' AND `active`='1'");
	$firma = $db->FetchArray($sql);
	$firma_misc = unserialize($firma['misc']);
	$firmatype = $config['business_types'][$firma['type']];
	
	if ($realClient['id'] != '' && ($firma['id'] == '' || !$firmatype))
	{
		$db->Query("UPDATE `bank_clients` SET `active`='0' WHERE `id`='".$realClient['id']."'");
		unset($realClient);
		
		$db->Query("UPDATE `[players]` SET `cash`=`cash`+`bank`, `bank`='0', `bank_id`='0' WHERE `id`='".Player::Data('id')."'");
		
		View::Message($langBase->get('banca-01').' '.(Player::Data('bank') > 0 ? $langBase->get('banca-02', array('-CASH-' => View::CashFormat(Player::Data('bank')))) : ''), 2, true, '', '', 12);
	}
	
	if ($realClient['id'] == '' || $firma['id'] == '' || !$firmatype):
		
		$sql = $db->Query("SELECT id,type,misc,job_1,name,created FROM `businesses` WHERE `type`='1' AND `active`='1' ORDER BY id DESC");
		$banker = $db->FetchArrayAll($sql);
		$firmatype = $config['business_types'][$banker[0]['type']];
		
		if (isset($_POST['bankID']))
		{
			if ($db->GetNumRows($db->Query("SELECT id FROM `bank_clients` WHERE `playerid`='".Player::Data('id')."' AND `active`='1' AND `accepted`='0' LIMIT 1")) > 0)
			{
				View::Message($langBase->get('banca-03'), 2, true, '/game/?side=' . $_GET['side']);
			}
			else
			{
				$bank = $db->EscapeString($_POST['bankID']);
				$sql = $db->Query("SELECT id,misc FROM `businesses` WHERE `type`='1' AND `active`='1' AND `id`='".$bank."'");
				$bank = $db->FetchArray($sql);
				$misc = unserialize($bank['misc']);
				$regPrice = $misc['account_price'];
				
				if ($bank['id'] == '')
				{
					View::Message($langBase->get('banca-04'), 2, true, '/game/?side=' . $_GET['side']);
				}
				elseif ($regPrice > Player::Data('cash'))
				{
					View::Message($langBase->get('err-01'), 2, true, '/game/?side=' . $_GET['side']);
				}
				else
				{
					$db->Query("INSERT INTO `bank_clients` (`b_id`, `playerid`, `registred`, `active`, `accepted`, `reg_price`)VALUES('".$bank['id']."', '".Player::Data('id')."', '".time()."', '1', '0', '".$regPrice."')");
					
					View::Message($langBase->get('banca-05'), 1, true, '/game/?side=' . $_GET['side']);
				}
			}
		}
		elseif ($db->GetNumRows($db->Query("SELECT id FROM `bank_clients` WHERE `playerid`='".Player::Data('id')."' AND `active`='1' AND `accepted`='0' LIMIT 1")) > 0 && isset($_GET['remove']))
		{
			$db->Query("UPDATE `bank_clients` SET `active`='0' WHERE `playerid`='".Player::Data('id')."'");
			
			View::Message($langBase->get('banca-06'), 1, true, '/game/?side=' . $_GET['side']);
		}
?>

	
<div class="bg_c w550">
	<h1 class="big"><?=$langBase->get('banca-07')?></h1>
    <?=$langBase->get('banca-08')?>
    <div class="bg_c c_1 w500">
    	<h1 class="big"><?=$langBase->get('banca-09')?></h1>
        <p><?=$langBase->get('banca-10', array('-NUM-' => count($banker)))?></p>
        <?php
		if (count($banker) > 0):
		?>
        <form method="post" action="">
            <table class="table boxHandle">
                <thead>
                    <tr>
                        <td width="28%"><?=$langBase->get('banca-11')?></td>
                        <td width="27%"><?=$firmatype['job_titles'][0]?></td>
                        <td width="25%"><?=$langBase->get('banca-12')?></td>
                        <td width="25%"><?=$langBase->get('txt-37')?></td>
                    </tr>
                </thead>
                <tbody class="center">
                <?php
                foreach ($banker as $bank)
                {
                    $i++;
                    $c = $i%2 ? 1 : 2;
                    
                    $misc = unserialize($bank['misc']);
                ?>
                    <tr class="c_<?=$c?> boxHandle">
                        <td width="28%"><input type="radio" name="bankID" value="<?=$bank['id']?>" /><a href="<?=$config['base_url']?>?side=firma/firma&amp;id=<?=$bank['id']?>"><?=$bank['name']?></a></td>
                        <td width="27%"><?=View::Player(array('id' => $bank['job_1']))?></td>
                        <td width="25%"><?=View::CashFormat($misc['account_price'])?> $</td>
                        <td width="20%"><?=View::Time($bank['created'])?></td>
                    </tr>
                <?php
                }
                ?>
                	<tr class="c_3 center">
                    	<td colspan="4"><input type="submit" value="<?=$langBase->get('txt-16')?>" /></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <?php
		endif;
		?>
    </div>
</div>
<?php
	elseif (empty($realClient['pass']) || $realClient['pass'] == User::Data('pass')):
		
		if (isset($_POST['pass']))
		{
			$pass = View::DoubleSalt($db->EscapeString($_POST['pass']), $realClient['id']);
			$pass_re = View::DoubleSalt($db->EscapeString($_POST['pass_repeated']), $realClient['id']);
			
			if (!View::ValidPassword($_POST['pass']))
			{
				echo View::Message($langBase->get('err-03'), 2);
			}
			elseif ($pass != $pass_re)
			{
				echo View::Message($langBase->get('err-04'), 2);
			}
			elseif (View::DoubleSalt($db->EscapeString($_POST['pass']), User::Data('id')) == User::Data('pass'))
			{
				echo View::Message($langBase->get('banca-13'), 2);
			}
			else
			{
				$db->Query("UPDATE `bank_clients` SET `pass`='".$pass."' WHERE `id`='".$realClient['id']."'");
				
				View::Message($langBase->get('banca-14'), 1, true);
			}
		}
?>
<div class="bg_c w250">
	<h1 class="big"><?=$langBase->get('banca-15')?></h1>
    <p><?=($realClient['pass'] == User::Data('pass') ? $langBase->get('banca-13') : $langBase->get('banca-16'))?></p>
	<form method="post" action="">
    	<dl class="dd_right">
            <dt><?=$langBase->get('txt-17')?></dt>
            <dd><input type="password" name="pass" class="flat" /></dd>
            <dt><?=$langBase->get('txt-18')?></dt>
            <dd><input type="password" name="pass_repeated" class="flat" /></dd>
        </dl>
        <p class="center clear">
            <input type="submit" value="<?=$langBase->get('txt-19')?>" />
        </p>
    </form>
</div>
<?php
	else:
		$bankLogin = $_SESSION['MZ_BankLogin'];

		$temp_area = 'bankPass_reset';
		$temp_expires = 3600;
		
		
		if ($bankLogin[2]+$config['bank_max_inactivity_logout'] < time())
		{
			unset($_SESSION['MZ_BankLogin']);
			
			if (isset($_POST['clientPass']))
			{
				$id = $db->EscapeString($_POST['clientID']);
				$pass = $db->EscapeString($_POST['clientPass']);
				
				if ($id != $realClient['id'] && Player::Data('level') < 3)
					$id = $realClient['id'];
				
				$sql = $db->Query("SELECT id,pass FROM `bank_clients` WHERE `id`='".$id."' AND `accepted`='1'");
				$client = $db->FetchArray($sql);
				
				if ($client['id'] == '')
				{
					echo View::Message('ERROR!', 2);
				}
				elseif (View::DoubleSalt($pass, $client['id']) != $client['pass'] && $client['id'] == $realClient['id'])
				{
					echo View::Message($langBase->get('banca-17'), 2);
				}
				else
				{
					$_SESSION['MZ_BankLogin'] = array(
						$client['id'],
						time(),
						time()
					);
					
					View::Message($langBase->get('banca-18'), 1, true);
				}
			}
			elseif (isset($_POST['reset_pass']) && !isset($_GET['rp']))
			{
				$pass = View::DoubleSalt($db->EscapeString($_POST['reset_pass']), User::Data('id'));
				if ($pass != User::Data('pass'))
					View::Message($langBase->get('txt-20'), 2, true);
				
				$sql = $db->Query("SELECT id,time_added,expires FROM `temporary` WHERE `playerid`='".Player::Data('id')."' AND `active`='1' AND `area`='".$temp_area."'");
				$last_temp = $db->FetchArray($sql);
				
				$lt_expire = $last_temp['time_added']+$last_temp['expires'] - time();
				if ($lt_expire <= 0)
				{
					$db->Query("UPDATE `temporary` SET `active`='0' WHERE `id`='".$last_temp['id']."'");
					unset($last_temp);
				}
				
				if ($lt_expire > 0)
				{
					View::Message($langBase->get('home-17'), 2, true);
				}
				else
				{
					$receiver = User::Data('email');
					if (!preg_match('/^[a-zA-Z_\\-][\\w\\.\\-_]*[a-zA-Z0-9_\\-]@[a-zA-Z0-9][\\w\\.-]*[a-zA-Z0-9]\\.[a-zA-Z][a-zA-Z\\.]*[a-zA-Z]$/i', $receiver))
					{
						View::Message($langBase->get('home-08'), 2, true);
					}
					else
					{
						$tempID = substr(sha1(uniqid(rand())), 0, 6);
						$db->Query("INSERT INTO `temporary` (`id`, `playerid`, `area`, `expires`, `time_added`)VALUES('".$tempID."', '".Player::Data('id')."', '".$temp_area."', '".$temp_expires."', '".time()."')");
						
						mail($receiver, $admin_config['game_name']['value'].' » Ban password', 
							'<html>
								<body style="font-family: Verdana; color: #333333; font-size: 12px;">
									<table style="width: 350px; margin: 0px auto;">
										<tr style="text-align: center;">
											<td style="border-bottom: solid 1px #cccccc;"><h1 style="margin: 0; font-size: 20px;"><a href="'.$config['base_url'].'">'.$admin_config['game_name']['value'].'</a></h1><h2 style="text-align: right; font-size: 14px; margin: 7px 0 10px 0;">&laquo; Bank Password</h2></td>
										</tr>
										<tr style="text-align: justify;">
											<td style="padding-top: 15px; padding-bottom: 15px;">
												Hello,
												<br />
												<br />
												Access this URL to recover you bank password: <a href="'.$config['base_url'].'?side='.$_GET['side'].'&amp;rp='.$tempID.'">'.$config['base_url'].'?side='.$_GET['side'].'&amp;rp='.$tempID.'</a>.
												<br />
												URL activ until: <b>'.View::Time(time()+$temp_expires, true).'</b>.
											</td>
										</tr>
										<tr style="text-align: right; color: #777777;">
											<td style="padding-top: 10px; border-top: solid 1px #cccccc;">
												Best Regards,
												<br>
												<span style="color: #222222;">'.$admin_config['game_name']['value'].'</span>
											</td>
										</tr>
									</table>
								</body>
							</html>',
							"Reply-To: ".$admin_config['game_name']['value']." <".$admin_config['contact_email']['value'].">\r\n" . 
							"Return-Path: ".$admin_config['game_name']['value']." <".$admin_config['contact_email']['value'].">\r\n" . 
							"From: ".$admin_config['game_name']['value']." <".$admin_config['contact_email']['value'].">\r\n" .
							"MIME-Version: 1.0\r\n" .
							"Content-type: text/html; charset=iso-8859-1");
						
						View::Message($langBase->get('banca-19'), 1, true);
					}
				}
			}
			elseif (isset($_GET['rp']))
			{
				$temp = $db->EscapeString($_GET['rp']);
				$sql = $db->Query("SELECT * FROM `temporary` WHERE `playerid`='".Player::Data('id')."' AND `active`='1' AND `area`='".$temp_area."' AND `id`='".$temp."'");
				$temp = $db->FetchArray($sql);
				
				if ($temp['id'] == '')
				{
					View::Message('ERROR!', 2, true, '/game/?side=' . $_GET['side']);
				}
				
				$t_expire = $temp['time_added']+$temp['expires'] - time();
				if ($t_expire <= 0)
				{
					$db->Query("UPDATE `temporary` SET `active`='0' WHERE `id`='".$temp['id']."'");
					
					View::Message('URL expired.', 2, true, '/game/?side=' . $_GET['side']);
				}
				
				
				if (isset($_POST['user_pass']))
				{
					$user_pass = View::DoubleSalt($db->EscapeString($_POST['user_pass']), User::Data('id'));
					$pass = View::DoubleSalt($db->EscapeString($_POST['newPass']), $realClient['id']);
					$pass_re = View::DoubleSalt($db->EscapeString($_POST['newPass_re']), $realClient['id']);
					
					if (!View::ValidPassword($_POST['newPass']))
					{
						echo View::Message($langBase->get('err-03'), 2);
					}
					elseif ($user_pass != User::Data('pass'))
					{
						echo View::Message($langBase->get('txt-20'), 2);
					}
					elseif ($pass != $pass_re)
					{
						echo View::Message($langBase->get('err-04'), 2);
					}
					else
					{
						$db->Query("UPDATE `bank_clients` SET `pass`='".$pass."' WHERE `id`='".$realClient['id']."'");
						$db->Query("UPDATE `temporary` SET `active`='0' WHERE `id`='".$temp['id']."'");
						
						View::Message($langBase->get('home-20'), 1, true, '/game/?side=' . $_GET['side']);
					}
				}
			}
?>
<script type="text/javascript">
	<!--
	window.addEvent('domready', function()
	{
		document.getElement('input[name=clientPass]').focus();
	});
	-->
</script>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('banca-20')?></h1>
    <p><?=$langBase->get('banca-21')?></p>
    <div class="bg_c c_1 left" style="width: 220px;">
    	<h1 class="big"><?=$langBase->get('banca-20')?></h1>
        <?php
		if (isset($bankLogin))
			echo View::Message($langBase->get('banca-22'), 2, false, '', '', 12);
		?>
        <form method="post" action="">
            <dl class="dd_right">
                <dt><?=$langBase->get('banca-23')?></dt>
                <dd><input type="text" name="clientID" class="flat numbersOnly" value="<?=(isset($_POST['clientID']) ? $_POST['clientID'] : $realClient['id'])?>"<?=(Player::Data('level') < 3 ? ' disabled="disabled"' : '')?> onchange="if(this.value != '<?=$realClient['id']?>'){ document.getElement('input[name=clientPass]').addClass('hidden'); }else{ document.getElement('input[name=clientPass]').removeClass('hidden'); }" /></dd>
                <dt><?=$langBase->get('txt-17')?></dt>
                <dd><input type="password" name="clientPass" class="flat" /></dd>
            </dl>
            <p class="center clear">
            	<input type="submit" value="<?=$langBase->get('header-10')?>" />
            </p>
        </form>
    </div>
    <div class="bg_c c_1 left" style="width: 220px; margin-left: 15px;">
    	<h1 class="big"><?=$langBase->get('header-12')?></h1>
        <?php
		if (isset($_GET['rp']) && $temp)
		{
		?>
        <p class="t_justify"><?=$langBase->get('banca-24')?></p>
        <form method="post" action="">
            <dl class="dd_right">
                <dt><?=$langBase->get('banca-25')?></dt>
                <dd><input type="password" name="user_pass" class="flat" style="min-width: 110px; width: 110px;" value="<?=$_POST['user_pass']?>" /></dd>
                <dt><?=$langBase->get('banca-26')?></dt>
                <dd><input type="password" name="newPass" class="flat" style="min-width: 110px; width: 110px;" /></dd>
                <dt><?=$langBase->get('banca-27')?></dt>
                <dd><input type="password" name="newPass_re" class="flat" style="min-width: 110px; width: 110px;" /></dd>
            </dl>
            <p class="center clear">
                <input type="submit" value="<?=$langBase->get('txt-21')?>" />
            </p>
        </form>
        <?php
		}
		else
		{
		?>
        <p class="t_justify"><?=$langBase->get('banca-27')?></p>
        <form method="post" action="">
            <dl class="dd_right">
                <dt><?=$langBase->get('banca-25')?></dt>
                <dd><input type="password" name="reset_pass" class="flat" style="min-width: 110px; width: 110px;" /></dd>
            </dl>
            <p class="center clear">
                <input type="submit" value="<?=$langBase->get('txt-14')?>" />
            </p>
        </form>
        <?php
		}
		?>
    </div>
    <div class="clear"></div>
</div>
<?php
		}
		else
		{
			if (isset($_GET['bank_logout']))
			{
				unset($_SESSION['MZ_BankLogin']);
				
				View::Message($langBase->get('banca-28'), 1, true, '/game/?side=' . $_GET['side']);
			}
			
			$bankLogin = $db->EscapeString($_SESSION['MZ_BankLogin']);
			$_SESSION['MZ_BankLogin'][2] = time();
			
			if ($bankLogin[0] == $realClient['id'])
			{
				$client = $realClient;
				$owner = Player::$datavar;
			}
			else
			{
				$sql = $db->Query("SELECT * FROM `bank_clients` WHERE `id`='".$bankLogin[0]."'");
				$client = $db->FetchArray($sql);
				
				$sql = $db->Query("SELECT * FROM `[players]` WHERE `id`='".$client['playerid']."'");
				$owner = $db->FetchArray($sql);
			}
			
			
			if (isset($_POST['leave_bank']))
			{
				unset($_SESSION['MZ_BankLogin']);
				$db->Query("UPDATE `bank_clients` SET `active`='0' WHERE `id`='".$client['id']."'");
				$db->Query("UPDATE `[players]` SET `bank_id`='0', `cash`=`cash`+'".$owner['bank']."', `bank`='0' WHERE `id`='".$owner['id']."'");
				
				View::Message($langBase->get('banca-29'), 1, true, '/game/?side=' . $_GET['side']);
			}
			
			
			$sql = $db->Query("SELECT id,name,misc,bank FROM `businesses` WHERE `id`='".$client['b_id']."' AND `type`='1' AND `active`='1'");
			$firma = $db->FetchArray($sql);
			$firma_misc = unserialize($firma['misc']);
			
			
			$sql = "SELECT id,type,to_player,sum,sent,to_bank FROM `bank_transfers` WHERE `from_player`='".$owner['id']."' ORDER BY id DESC";
			$pagination_sent = new Pagination($sql, 10, 'p_s');
			$transfers_sent = $pagination_sent->GetSQLRows();
			$transfers_sent_links = $pagination_sent->GetPageLinks();
			
			$sql = "SELECT id,type,from_player,sum,sent,to_bank FROM `bank_transfers` WHERE `to_player`='".$owner['id']."' ORDER BY id DESC";
			$pagination_received = new Pagination($sql, 10, 'p_r');
			$transfers_received = $pagination_received->GetSQLRows();
			$transfers_received_links = $pagination_received->GetPageLinks();
			
			$all_transfers = array_merge($pagination_sent->GetSQLRows('all'), $pagination_received->GetSQLRows('all'));
			
			
			foreach ($all_transfers as $transfer)
			{
				if (!$transfer['from_player'])
				{
					$stats['num_sent']++;
					if ($transfer['type'] == 'money')
						$stats['money_sent'] += $transfer['sum'];
					elseif ($transfer['type'] == 'points')
						$stats['points_sent'] += $transfer['sum'];
				}
				else
				{
					$stats['num_received']++;
					if ($transfer['type'] == 'money')
						$stats['money_received'] += $transfer['sum'];
					elseif ($transfer['type'] == 'points')
						$stats['points_received'] += $transfer['sum'];
				}
			}
			
			
			$sql = "SELECT id,money_to_player,date FROM `bank_interests` WHERE `to_player`='".$owner['id']."' ORDER BY id DESC";
			$interests_pagination = new Pagination($sql, 5, 'i_p');
			$interests = $interests_pagination->GetSQLRows();
			$interests_links = $interests_pagination->GetPageLinks();
			$all_interests = $interests_pagination->GetSQLRows('all');
			
			foreach ($all_interests as $interest)
			{
				$stats['num_interests']++;
				$stats['interests_total_sum'] += $interest['money_to_player'];
			}
			
			
			if (isset($_POST['taut_sum']))
			{
				$sum = $db->EscapeString(View::NumbersOnly($_POST['taut_sum']));
				
				if ($sum <= 0)
				{
					echo View::Message($langBase->get('banca-30'), 2);
				}
				elseif ($sum > $owner['bank'])
				{
					echo View::Message($langBase->get('banca-31'), 2);
				}
				else
				{
					$db->Query("UPDATE `[players]` SET `bank`=`bank`-'".$sum."' WHERE `id`='".$owner['id']."'");
					$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$sum."' WHERE `id`='".Player::Data('id')."'");
					
					View::Message($langBase->get('banca-32', array('-CASH-' => View::CashFormat($sum))), 1, true);
				}
			}
			elseif (isset($_POST['settinn_sum']))
			{
				$sum = $db->EscapeString(View::NumbersOnly($_POST['settinn_sum']));
				
				if ($sum <= 0)
				{
					echo View::Message($langBase->get('banca-34'), 2);
				}
				elseif ($sum > Player::Data('cash'))
				{
					echo View::Message($langBase->get('banca-31'), 2);
				}
				else
				{
					$db->Query("UPDATE `[players]` SET `bank`=`bank`+'".$sum."' WHERE `id`='".$owner['id']."'");
					$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$sum."' WHERE `id`='".Player::Data('id')."'");
					
					View::Message($langBase->get('banca-33', array('-CASH-' => View::CashFormat($sum))), 1, true);
				}
			}
			elseif (isset($_POST['transfer_receiver']))
			{
				$receiver = $db->EscapeString($_POST['transfer_receiver']);
				$amount = $db->EscapeString(View::NumbersOnly($_POST['transfer_amount']));
				$typeKey = $db->EscapeString($_POST['transfer_type']);
				$type = $config['bank_transfertypes'][$typeKey];
				
				$fee = 0;
				if ($typeKey == 'money')
					$fee = ($firma_misc['transfer_fee'] / 100) * $amount;
				elseif ($typeKey == 'points')
					$fee = $amount * $firma_misc['transfer_fee_pp'];
				
				$fee = round($fee);
				
				$sql = $db->Query("SELECT id,userid FROM `[players]` WHERE `name`='".$receiver."'");
				$receiver = $db->FetchArray($sql);
				
				$sql = $db->Query("SELECT id FROM `bank_clients` WHERE `playerid`='".$receiver['id']."' AND `accepted`='1' AND `active`='1'");
				$r_client = $db->FetchArray($sql);
				
				if ($receiver['id'] == '')
				{
					echo View::Message('ERROR!', 2);
				}
				elseif ($receiver['id'] == $owner['id'])
				{
					echo View::Message($langBase->get('banca-35'), 2);
				}
				elseif ($r_client['id'] == '')
				{
					echo View::Message($langBase->get('banca-36'), 2);
				}
				elseif (!$type)
				{
					echo View::Message($langBase->get('banca-37'), 2);
				}
				elseif ($amount <= 0)
				{
					echo View::Message($langBase->get('banca-38').' '. $type[1] . '.', 2);
				}
				elseif ($typeKey == 'money' && $amount > $owner['bank'])
				{
					echo View::Message($langBase->get('banca-39'), 2);
				}
				elseif ($typeKey == 'points' && $amount > $owner['points'])
				{
					echo View::Message($langBase->get('banca-40'), 2);
				}
				elseif ($typeKey == 'points' && $fee > $owner['bank'])
				{
					echo View::Message($langBase->get('banca-41'), 2);
				}
				else
				{
					$transfers = unserialize($client['transfers']);
					$transfers[$typeKey]++;
					$db->Query("UPDATE `bank_clients` SET `transfers`='".serialize($transfers)."', `used`=`used`+'".$fee."' WHERE `id`='".$client['id']."'");
					
					$db->Query("INSERT INTO `bank_transfers` (`b_id`, `from_ip`, `from_player`, `from_user`, `to_player`, `to_user`, `type`, `sum`, `to_bank`, `sent`)VALUES('".$firma['id']."', '".$_SERVER['REMOTE_ADDR']."', '".$owner['id']."', '".$owner['userid']."', '".$receiver['id']."', '".$receiver['userid']."', '".$typeKey."', '".$amount."', '".$fee."', '".time()."')");
					$db->Query("UPDATE `businesses` SET `bank`=`bank`+'".$fee."', `bank_income`=`bank_income`+'".$fee."' WHERE `id`='".$firma['id']."'");
					
					// Aksjer
					$sql = $db->Query("SELECT id FROM `stocks` WHERE `business_type`='game_business' AND `business_id`='".$firma['id']."' AND `active`='1'");
					$stocks = $db->FetchArray($sql);
					
					if ($stocks['id'] == '')
					{
						$db->Query("INSERT INTO `stocks`
									(`business_type`, `business_id`, `shares`, `changes`, `created`, `current_price`, `last_change_time`)
									VALUES
									('game_business', '".$firma['id']."', 'a:0:{}', 'a:0:{}', '".time()."', '".$config['businesses_default_stockprice']."', '".time()."')");
						
						$stocks['id'] = mysql_insert_id();
					}
					$db->Query("UPDATE `stocks` SET `current_income`=`current_income`+'".$fee."' WHERE `id`='".$stocks['id']."'");
					
					Accessories::AddLogEvent($reveiver['id'], 18, array(
						'-PLAYER_NAME-' => Player::Data('name'),
						'-TO_ME-' => (View::CashFormat($amount - $fee)),
						'-TO_BANK-' => View::CashFormat($fee)
					), $receiver['userid']);
					
					if ($typeKey == 'money')
					{
						$db->Query("UPDATE `[players]` SET `bank`=`bank`-'".$amount."' WHERE `id`='".$owner['id']."'");
						$db->Query("UPDATE `[players]` SET `bank`=`bank`+'".($amount - $fee)."' WHERE `id`='".$receiver['id']."'");
					}
					elseif ($typeKey == 'points')
					{
						$db->Query("UPDATE `[players]` SET `bank`=`bank`-'".$fee."', `points`=`points`-'".$amount."' WHERE `id`='".$owner['id']."'");
						$db->Query("UPDATE `[players]` SET `points`=`points`+'".$amount."' WHERE `id`='".$receiver['id']."'");
					}
					
					View::Message($langBase->get('banca-42', array('-AMOUNT-' => View::CashFormat($amount).' '.$type[1], '-PLAYER-' => View::Player(array('id' => $receiver['id']), true))), 1, true);
				}
			}
?>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('banca-11')?></h1>
	
    <p class="center">
    	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;bank_logout" class="button"><?=$langBase->get('banca-43')?></a>
    </p>
    <div class="left" style="width: 245px;">
    	<div class="bg_c c_1" style="width: 225px;">
        	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
            <dl class="dd_right">
            	<dt><?=$langBase->get('banca-23')?></dt>
                <dd>#<?=View::CashFormat($client['id'])?></dd>
                <dt><?=$langBase->get('banca-44')?></dt>
                <dd><?=View::Player(array('id' => $client['playerid']))?></dd>
                <dt><?=$langBase->get('banca-45')?></dt>
                <dd><?=View::CashFormat($owner['bank'])?> $</dd>
                <dt><?=$langBase->get('ot-points')?></dt>
                <dd><?=View::CashFormat($owner['points'])?> C</dd>
            </dl>
            <div class="clear"></div>
            <h2 style="margin-bottom: 1;"><b><?=$langBase->get('banca-46')?></b></h2>
            <dl class="dd_right" style="margin-top: 0;">
            	<dt><?=$langBase->get('txt-02')?></dt>
                <dd><a href="<?=$config['base_url']?>?side=firma/firma&amp;id=<?=$firma['id']?>"><?=$firma['name']?></a></dd>
                <dt><?=$langBase->get('banca-47')?> ($)</dt>
                <dd><?=$firma_misc['transfer_fee']?> %</dd>
                <dt><?=$langBase->get('banca-47')?> (C)</dt>
                <dd><?=View::CashFormat($firma_misc['transfer_fee_pp'])?> $</dd>
                <dt><?=$langBase->get('banca-48')?></dt>
                <dd><?=(strstr($firmatype['extra']['rente_types'][$firma_misc['rente_type']], '%s') ? sprintf($firmatype['extra']['rente_types'][$firma_misc['rente_type']], $firma_misc['rente_type_2_value']) : $firmatype['extra']['rente_types'][$firma_misc['rente_type']])?></dd>
            </dl>
            <div class="clear"></div>
            <h2 style="margin-bottom: 1;"><b><?=$langBase->get('banca-49')?></b></h2>
            <dl class="dd_right" style="margin-top: 0;">
            	<dt><?=$langBase->get('txt-23')?> (<?=View::CashFormat($stats['num_sent'])?>)</dt>
                <dd><?=View::CashFormat($stats['money_sent'])?> $<br /><?=View::CashFormat($stats['points_sent'])?> C</dd>
                <dt><?=$langBase->get('txt-24')?> (<?=View::CashFormat($stats['num_received'])?>)</dt>
                <dd><?=View::CashFormat($stats['money_received'])?> $<br /><?=View::CashFormat($stats['points_received'])?> C</dd>
                <dt><?=$langBase->get('banca-48')?> (<?=View::CashFormat($stats['num_interests'])?>)</dt>
                <dd><?=View::CashFormat($stats['interests_total_sum'])?> $</dd>
            </dl>
            <div class="clear"></div>
            <div class="hr"></div>
            <form method="post" action="" class="center">
                <input type="submit" name="leave_bank" class="warning" value="<?=$langBase->get('banca-50')?>" onclick="return confirm('<?=$langBase->get('err-05')?>')" />
            </form>
        </div>
    </div>
    <div class="left" style="width: 245px; margin-left: 10px;">
    	<div class="bg_c c_1" style="width: 225px;">
        	<h1 class="big"><?=$langBase->get('banca-51')?></h1>
            <form method="post" action="">
            	<dl class="dd_right">
                	<dt><?=$langBase->get('txt-25')?> (<a href="#" onclick="document.getElement('input[name=taut_sum]').set('value', '<?=View::CashFormat($owner['bank'])?> $'); return false;"><?=$langBase->get('txt-26')?></a>)</dt>
                    <dd><input type="text" class="flat" name="taut_sum" value="<?=View::CashFormat($_POST['taut_sum'])?> $" /></dd>
                </dl>
                <p class="center clear">
                	<input type="submit" value="<?=$langBase->get('banca-52')?>" />
                </p>
            </form>
        </div>
        <div class="bg_c c_1" style="width: 225px;">
        	<h1 class="big"><?=$langBase->get('banca-53')?></h1>
            <form method="post" action="">
            	<dl class="dd_right">
                	<dt><?=$langBase->get('txt-25')?> (<a href="#" onclick="document.getElement('input[name=settinn_sum]').set('value', '<?=View::CashFormat(Player::Data('cash'))?> $'); return false;"><?=$langBase->get('txt-26')?></a>)</dt>
                    <dd><input type="text" class="flat" name="settinn_sum" value="<?=View::CashFormat($_POST['settinn_sum'])?> $" /></dd>
                </dl>
                <p class="center clear">
                	<input type="submit" value="<?=$langBase->get('banca-54')?>" />
                </p>
            </form>
        </div><div class="bg_c c_1" style="width: 225px;">
        	<h1 class="big"><?=$langBase->get('banca-55')?></h1>
            <?php
			if (count($interests) <= 0)
			{
				echo '<p>'.$langBase->get('err-06').'</p>';
			}
			else
			{
			?>
            <table class="table">
            	<thead>
                	<tr>
                    	<td width="10%"><?=$langBase->get('banca-56')?></td>
                        <td width="10%"><?=$langBase->get('txt-27')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
				foreach ($interests as $interest)
				{
					$i++;
					$c = $i%2 ? 1 : 2;
				?>
                	<tr class="c_<?=$c?>">
                    	<td width="10%" class="center"><?=View::CashFormat($interest['money_to_player'])?> $</td>
                        <td width="10%" class="t_right"><?=View::Time($interest['date'], true, 'H:i', false)?></td>
                    </tr>
                <?php
				}
				?>
                	<tr class="c_3">
                    	<td colspan="2" style="padding: 8px;"><?=$interests_links?></td>
                    </tr>
                </tbody>
            </table>
            <?php
			}
			?>
        </div>
    </div>
</div>
<script type="text/javascript">
	<!--
	var fee = '<?=$firma_misc['transfer_fee']?>';
	var fee_pp = '<?=$firma_misc['transfer_fee_pp']?>';
	var ends = {<?php foreach($config['bank_transfertypes'] as $key => $value){ echo "'$key':'".$value[1]."'" . ($value != end($config['bank_transfertypes']) ? ', ' : ''); }?>};
	
	function calculate_fee(type, amount)
	{
		result = 0;
		amount = parseFloat(amount.replace(' ', ''));
		
		if (type == 'money')
		{
			result = (fee / 100) * amount;
		}
		else if (type == 'points')
		{
			result = amount * fee_pp;
		}
		
		return number_format(result, 0, ',', ' ') + ' $';
	}
	
	window.addEvent('domready', function()
	{
		var form = document.getElement('form.transfer');
		
		form.getElements('select[name="transfer_type"], input[name="transfer_amount"]').each(function(elem)
		{
			if ($(elem).retrieve('calculateFee'))
			{
				return;
			}
			elem.store('calculateFee', true);
			
			elem.addEvent('change', function()
			{
				$('transfer_fee').set('value', calculate_fee(form.getElement('select[name="transfer_type"]').get('value'), form.getElement('input[name="transfer_amount"]').get('value')));
			});
		});
		
		$('transfer_fee').set('value', calculate_fee(form.getElement('select[name="transfer_type"]').get('value'), form.getElement('input[name="transfer_amount"]').get('value')));
	});
	-->
</script>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('banca-49')?></h1>
    <div class="bg_c c_1 w300">
    	<h1 class="big"><?=$langBase->get('banca-57')?></h1>
        <form method="post" action="" class="transfer">
            <dl class="dd_right">
                <dt><?=$langBase->get('txt-28')?></dt>
                <dd>
                	<input type="text" name="transfer_receiver" class="flat" value="<?=$_POST['transfer_receiver']?>" style="min-width: 100px; width: 100px;" />
                    <select onchange="if(this.selectedIndex != 0) $$('form.transfer input[name=transfer_receiver]').set('value', this.value)">
                    	<option><?=$langBase->get('txt-30')?>...</option>
                        <?php
						$sql = $db->Query("SELECT contact_id FROM `contacts` WHERE `userid`='".User::Data('id')."' AND `type`='1'");
						
						while ($contact = $db->FetchArray($sql))
						{
							$query = $db->Query("SELECT name FROM `[players]` WHERE `userid`='".$contact['contact_id']."' ORDER BY id DESC");
							$player = $db->FetchArray($query);
							
							echo "<option value=\"" . $player['name'] . "\">" . $player['name'] . "</option>\n";
						}
						?>
                    </select>
                </dd>
                <dt><?=$langBase->get('txt-29')?></dt>
                <dd><select name="transfer_type"><?php foreach($config['bank_transfertypes'] as $key => $value){ echo '<option value="'.$key.'"'.($_POST['transfer_type'] == $key ? ' selected="selected"' : '').'>'.$value[0].'</option>'; } ?></select></dd>
                <dt><?=$langBase->get('txt-25')?></dt>
                <dd><input type="text" name="transfer_amount" class="flat" value="<?=View::CashFormat(View::NumbersOnly($_POST['transfer_amount']))?> <?=(isset($_POST['transfer_type']) ? ($config['bank_transfertypes'][$_POST['transfer_type']] ? $config['bank_transfertypes'][$_POST['transfer_type']][1] : '$') : '$')?>" /></dd>
                <dt><?=$langBase->get('banca-58')?></dt>
                <dd><input type="text" disabled="disabled" class="flat" id="transfer_fee" value="0 $" /></dd>
            </dl>
            <p class="center clear">
            	<input type="submit" value="<?=$langBase->get('txt-14')?>" />
            </p>
        </form>
    </div>
    <div class="left" style="width: 295px;">
    	<div class="bg_c c_1 nomargin" style="width: 275px;">
        	<h1 class="big"><?=$langBase->get('txt-24')?> (<?=$pagination_received->num_rows?>)</h1>
            <?php
			if (count($transfers_received) > 0)
			{
			?>
            <table class="table">
                <thead>
                	<tr>
                    	<td width="35%"><?=$langBase->get('txt-31')?></td>
                        <td width="37%"><?=$langBase->get('txt-25')?></td>
                        <td width="28%"><?=$langBase->get('txt-27')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
				foreach ($transfers_received as $transfer)
				{
					$x++;
					$c = $x%2 ? 1 : 2;
					
					$transferType = $config['bank_transfertypes'][$transfer['type']];
				?>
                	<tr class="c_<?=$c?>">
                    	<td width="35%"><?=View::Player(array('id' => $transfer['from_player']))?></td>
                        <td width="37%" class="center"><span class="subtext"><?=View::CashFormat($transfer['sum'])?> <?=$transferType[1]?></span></td>
                        <td width="28%" class="t_right"><span class="subtext"><?=trim(View::Time($transfer['sent'], false, ''))?><br /><?=date('H:i:s', $transfer['sent'])?></span></td>
                    </tr>
                <?php
				}
				?>
                	<tr class="c_3">
                    	<td colspan="4"><?=$transfers_received_links?></td>
                    </tr>
                </tbody>
            </table>
            <?php
			}else{ echo '<p>'.$langBase->get('err-06').'</p>';}
			?>
        </div>
    </div>
    <div class="left" style="width: 295px;">
    	<div class="bg_c c_1" style="width: 275px; margin: 0 0 0 10px;">
        	<h1 class="big"><?=$langBase->get('txt-23')?> (<?=$pagination_sent->num_rows?>)</h1>
            <?php
			if (count($transfers_sent) > 0)
			{
			?>
            <table class="table">
                <thead>
                	<tr>
                    	<td width="35%"><?=$langBase->get('txt-28')?></td>
                        <td width="37%"><?=$langBase->get('txt-25')?></td>
                        <td width="28%"><?=$langBase->get('txt-27')?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
				foreach ($transfers_sent as $transfer)
				{
					$y++;
					$c = $y%2 ? 1 : 2;
					
					$transferType = $config['bank_transfertypes'][$transfer['type']];
				?>
                	<tr class="c_<?=$c?>">
                    	<td width="35%"><?=View::Player(array('id' => $transfer['to_player']))?></td>
                        <td width="37%" class="center"><span class="subtext"><?=View::CashFormat($transfer['sum'])?> <?=$transferType[1]?></span></td>
                        <td width="28%" class="t_right"><span class="subtext"><?=trim(View::Time($transfer['sent'], false, ''))?><br /><?=date('H:i:s', $transfer['sent'])?></span></td>
                    </tr>
                <?php
				}
				?>
                	<tr class="c_3">
                    	<td colspan="4"><?=$transfers_sent_links?></td>
                    </tr>
                </tbody>
            </table>
            <?php
			}else{ echo '<p>'.$langBase->get('err-06').'</p>';}
			?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php
		}
		
	endif;
?>
</div>