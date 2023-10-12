<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	if (isset($_GET['pp_cancel']))
	{
		View::Message($langBase->get('mdc-01'), 2, true, '/game/?side=' . $_GET['side']);
	}
	elseif (isset($_GET['pp_success']))
	{
		View::Message($langBase->get('mdc-02'), 1, true, '/game/?side=' . $_GET['side']);
	}

	$point_usage = array(
		4 => array(
			'title' => '<img src="images/icons/set_1/vip.png" height="14" /> '.$langBase->get('mdc-68').' - 30 '.$langBase->get('lc-04'),
			'desc' => $langBase->get('mdc-69'),
			'price' => 350,
			'type' => 'vip_acc',
			'length' => 2592000
		),
		5 => array(
			'title' => '<img src="images/icons/set_1/vip.png" height="14" /> '.$langBase->get('mdc-68').' - 14 '.$langBase->get('lc-04'),
			'desc' => $langBase->get('mdc-69'),
			'price' => 200,
			'type' => 'vip_acc',
			'length' => 1209600
		),
		6 => array(
			'title' => '<img src="images/icons/set_1/vip.png" height="14" /> '.$langBase->get('mdc-68').' - 7 '.$langBase->get('lc-04'),
			'desc' => $langBase->get('mdc-69'),
			'price' => 120,
			'type' => 'vip_acc',
			'length' => 604800
		),
		21 => array(
			'title' => '<img src="images/icons/set_1/vip.png" height="14" /> '.$langBase->get('mdc-68').' - 2 '.$langBase->get('lc-04'),
			'desc' => $langBase->get('mdc-69'),
			'price' => 50,
			'type' => 'vip_acc',
			'length' => 172800
		),
		1 => array(
			'title' => '<img src="images/icons/set_1/status_offline.png" height="14" /> '.$langBase->get('mdc-66'),
			'desc' => $langBase->get('mdc-67'),
			'price' => 120,
			'type' => 'name_change',
			'amount' => 1
		),
		3 => array(
			'title' => '<img src="images/icons/set_1/cash_up.png" height="14" /> '.$langBase->get('mdc-41'),
			'desc' => $langBase->get('mdc-42'),
			'price' => 90,
			'type' => 'rankboost',
			'time_length' => 7200,
			'rank_percent' => 25
		),
		8 => array(
			'title' => '<img src="images/icons/set_1/stats_family.png" height="14" /> '.$langBase->get('mdc-46'),
			'desc' => $langBase->get('mdc-47'),
			'price' => 75,
			'type' => 'fight'
		),
		13 => array(
			'title' => '<img src="images/icons/set_1/attack.png" height="14" /> '.$langBase->get('mdc-50'),
			'desc' => $langBase->get('mdc-51'),
			'price' => 75,
			'type' => 'best_weapon'
		),
		14 => array(
			'title' => '<img src="images/icons/set_1/stats_protect.png" height="14" /> '.$langBase->get('mdc-52'),
			'desc' => $langBase->get('mdc-53'),
			'price' => 75,
			'type' => 'best_protection'
		),
		2 => array(
			'title' => '<img src="images/icons/set_1/cash_up.png" height="14" /> '.$langBase->get('mdc-39'),
			'desc' => $langBase->get('mdc-40'),
			'price' => 70,
			'type' => 'weapon_training'
		),
		9 => array(
			'title' => '<img src="images/icons/set_1/stats_bank.png" height="14" /> 40 000 000 $',
			'desc' => $langBase->get('mdc-43').' 40 000 000 $',
			'price' => 100,
			'type' => 'get_money',
			'amount' => 40000000
		),
		10 => array(
			'title' => '<img src="images/icons/set_1/stats_bank.png" height="14" /> 15 000 000 $',
			'desc' => $langBase->get('mdc-43').' 15 000 000 $',
			'price' => 50,
			'type' => 'get_money',
			'amount' => 15000000
		),
		11 => array(
			'title' => '<img src="images/icons/set_1/stats_bullets.png" height="14" /> '.$langBase->get('mdc-48', array('-NUM-' => 1000)),
			'desc' => $langBase->get('mdc-49', array('-NUM-' => 1000)),
			'price' => 70,
			'type' => 'get_bullets',
			'amount' => 1000
		),
		12 => array(
			'title' => '<img src="images/icons/set_1/stats_bullets.png" height="14" /> '.$langBase->get('mdc-48', array('-NUM-' => 500)),
			'desc' => $langBase->get('mdc-49', array('-NUM-' => 500)),
			'price' => 40,
			'type' => 'get_bullets',
			'amount' => 500
		),
		15 => array(
			'title' => '<img src="images/icons/set_1/stats_health.png" height="14" /> '.$langBase->get('mdc-54'),
			'desc' => $langBase->get('mdc-55'),
			'price' => 40,
			'type' => 'max_health'
		),
		7 => array(
			'title' => '<img src="images/icons/set_1/status_offline.png" height="14" /> '.$langBase->get('mdc-44'),
			'desc' => $langBase->get('mdc-45'),
			'price' => 40,
			'type' => 'detective_results'
		),
		16 => array(
			'title' => '<img src="images/icons/set_1/stats_ads.png" height="14" /> '.$langBase->get('mdc-56'),
			'desc' => $langBase->get('mdc-57'),
			'price' => 40,
			'type' => 'hide_ads',
			'length' => 1209600
		),
		20 => array(
			'title' => '<img src="images/icons/redlight.png" height="14" /> '.$langBase->get('mdc-72'),
			'desc' => $langBase->get('mdc-73', array('-NUM-' => 100)),
			'price' => 35,
			'type' => 'racolare_prostituate',
			'amount' => 100
		),
		17 => array(
			'title' => '<img src="images/icons/set_1/clock.png" height="14" /> '.$langBase->get('mdc-60'),
			'desc' => $langBase->get('mdc-61'),
			'price' => 20,
			'type' => 'reset_pr'
		),
		18 => array(
			'title' => '<img src="images/icons/set_1/clock.png" height="14" /> '.$langBase->get('mdc-62'),
			'desc' => $langBase->get('mdc-63'),
			'price' => 15,
			'type' => 'reset_rtnr',
			'amount' => 0
		),
		19 => array(
			'title' => '<img src="images/icons/set_1/clock.png" height="14" /> '.$langBase->get('mdc-64'),
			'desc' => $langBase->get('mdc-65'),
			'price' => 10,
			'type' => 'reset_ruleta',
			'amount' => 20
		)
	);

	if (isset($_POST['use_points']))
	{
		$object_id = $db->EscapeString($_POST['use_points']);
		$object = $point_usage[$object_id];
		
		$rankboost = unserialize(Player::Data('rankboost'));
		
		if (!$object)
		{
			echo View::Message('ERROR', 2);
		}
		elseif ($object['price'] > Player::Data('points') && $object['type'] != 'name_change')
		{
			echo View::Message($langBase->get('err-09'), 2);
		}
		elseif ($object['type'] == 'name_change' && Player::Data('c_name_r') < 1 && $object['price'] > Player::Data('points'))
		{
			echo View::Message($langBase->get('err-09'), 2);
		}
		elseif ($object['type'] == 'rankboost' && $rankboost['ends'] > time() && !empty($rankboost))
		{
			echo View::Message($langBase->get('mdc-03'), 2);
		}
		elseif ($object['type'] == 'vip_acc' && Player::Data('vip_days') > 0)
		{
			echo View::Message($langBase->get('mdc-70'), 2);
		}
		elseif ($object['type'] == 'weapon_training' && !$config['weapons'][Player::Data('weapon')])
		{
			echo View::Message($langBase->get('mdc-04'), 2);
		}
		else
		{
			if ($object['type'] == 'weapon_training')
			{
				$my_weapons = unserialize(Player::Data('weapons'));
				$weapon_data = $my_weapons[Player::Data('weapon')];
				$weapon = $config['weapons'][Player::Data('weapon')];
				
				if ($weapon_data['training'] >= $config['weapon_max_traning'])
				{
					echo View::Message($langBase->get('mdc-05'), 2);
				}
				else
				{
					$my_weapons[Player::Data('weapon')]['training'] = $config['weapon_max_traning'];
					$db->Query("UPDATE `[players]` SET `weapons`='".serialize($my_weapons)."', `points`=`points`-'".$object['price']."' WHERE `id`='".Player::Data('id')."'");
					
					Accessories::AddToLog(Player::Data('id'), array('object' => $object_id));
					
					View::Message($langBase->get('mdc-06', array('-COINS-' => View::CashFormat($object['price']))), 1, true);
				}
			}
			elseif ($object['type'] == 'detective_results')
			{
				$sql = $db->Query("SELECT id,to_find FROM `detective` WHERE `player`='".Player::Data('id')."' AND `ends`>'".time()."' AND `length`>='30' AND `finished`='0'");
				$results = $db->FetchArrayAll($sql);
				
				if (count($results) <= 0)
				{
					echo View::Message($langBase->get('mdc-07'), 2);
				}
				else
				{
					$finished = 0;
					foreach ($results as $result)
					{
						$finished++;
						$player = $db->QueryFetchArray("SELECT id,live,hospital_data FROM `[players]` WHERE `id`='".$result['to_find']."'");
						
						$no_find = false;
						
						$hospitalData = unserialize($player['hospital_data']);
						$hospital_timeleft = $hospitalData['added'] + $hospitalData['time_length'] - time();
						
						if ($hospital_timeleft > 0)
						{
							$no_find = true;
						}
						else
						{
							$sql = $db->Query("SELECT id,added,penalty FROM `jail` WHERE `player`='".$player['id']."' AND `added`+`penalty`>".time()." AND `active`='1'");
							$jail = $db->FetchArray($sql);
							$jailTime = $jail['added'] + $jail['penalty'] - time();
							
							if ($jailTime > 0)
							{
								$no_find = true;
							}
							else
							{
								$sql = $db->Query("SELECT id,last_session_ends FROM `bunker` WHERE `player`='".$player['id']."'");
								$bunker = $db->FetchArray($sql);
								
								if ($bunker['last_session_ends']-time() > 0)
								{
									$no_find = true;
								}
							}
						}
						
						$db->Query("UPDATE `detective` SET `finished`='1', `result`='".($no_find === true ? 0 : $player['live'])."' WHERE `id`='".$result['id']."'");
					}
					
					$db->Query("UPDATE `[players]` SET `points`=`points`-'".$object['price']."' WHERE `id`='".Player::Data('id')."'");
					
					Accessories::AddToLog(Player::Data('id'), array('object' => $object_id));
					
					View::Message(($finished == 0 ? '0' : $finished) . ' ' . ($finished != 1 ? 'cautari au fost finalizate' : 'cautare a fost finalizata') . '. Te-a costat <b>'.View::CashFormat($object['price']).' credite</b>.', 1, true);
				}
			}
			elseif ($object['type'] == 'rankboost')
			{
				$rankboost = array(
					'started' => time(),
					'ends' => (time() + $object['time_length']),
					'rank_percent' => $object['rank_percent'],
					'rank_start' => Player::Data('rankpoints')
				);
				
				$db->Query("UPDATE `[players]` SET `points`=`points`-'".$object['price']."', `rankboost`='".serialize($rankboost)."' WHERE `id`='".Player::Data('id')."'");
				
				Accessories::AddToLog(Player::Data('id'), array('object' => $object_id));
				
				View::Message($langBase->get('mdc-08', array('-COINS-' => View::CashFormat($object['price']))), 1, true);
			}
			elseif ($object['type'] == 'name_change')
			{
				if(Player::Data('c_name_r') < 1){
				$db->Query("UPDATE `[players]` SET `points`=`points`-'".$object['price']."', `c_name_r`=`c_name_r`+'".$object['amount']."' WHERE `id`='".Player::Data('id')."'");
				}
				
				Accessories::AddToLog(Player::Data('id'), array('object' => $object_id));
				
				View::Message($langBase->get('cname-01'), 1, true, '/game/?side=c_name');
			}
			elseif ($object['type'] == 'get_money')
			{
				$db->Query("UPDATE `[players]` SET `points`=`points`-'".$object['price']."', `cash`=`cash`+'".$object['amount']."' WHERE `id`='".Player::Data('id')."'");
				
				Accessories::AddToLog(Player::Data('id'), array('object' => $object_id));
				
				View::Message($langBase->get('mdc-09', array('-COINS-' => View::CashFormat($object['price']), '-CASH-' => View::CashFormat($object['amount']))), 1, true);
			}
			elseif ($object['type'] == 'best_weapon')
			{
				krsort($config['weapons']);
				$weapon_id = key($config['weapons']);
				$weapon = $config['weapons'][$weapon_id];
				$weapons = unserialize(Player::Data('weapons'));
				
				if ($weapons[$weapon_id])
				{
					echo View::Message($langBase->get('mdc-10'), 2);
				}
				else
				{
					$weapons[$weapon_id] = array(
						'key' => $weapon_id,
						'training' => 0
					);
					
					$db->Query("UPDATE `[players]` SET `weapons`='".serialize($weapons)."', `weapon`='".$weapon_id."', `points`=`points`-'".$object['price']."' WHERE `id`='".Player::Data('id')."'");
					
					Accessories::AddToLog(Player::Data('id'), array('object' => $object_id));
					
					View::Message($langBase->get('mdc-11', array('-COINS-' => View::CashFormat($object['price']), '-OBJECT-' => $weapon['name'])), 1, true);
				}
			}
			elseif ($object['type'] == 'best_protection')
			{
				krsort($config['protections']);
				$protection_id = key($config['protections']);
				$protection = $config['weapons'][$weapon_id];
				
				if ($protection_id == Player::Data('protection'))
				{
					echo View::Message($langBase->get('mdc-12'), 2);
				}
				else
				{
					$db->Query("UPDATE `[players]` SET `protection`='".$protection_id."', `points`=`points`-'".$object['price']."' WHERE `id`='".Player::Data('id')."'");
					
					Accessories::AddToLog(Player::Data('id'), array('object' => $object_id));
					
					View::Message($langBase->get('mdc-11', array('-COINS-' => View::CashFormat($object['price']), '-OBJECT-' => $protection['name'])), 1, true);
				}
			}
			elseif ($object['type'] == 'fight')
			{
					$db->Query("UPDATE `fighting` SET `level`=`level`+'5' WHERE `player`='".Player::Data('id')."'");
					$db->Query("UPDATE `[players]` SET `points`=`points`-'".$object['price']."' WHERE `id`='".Player::Data('id')."'");
					
					Accessories::AddToLog(Player::Data('id'), array('object' => $object_id));
					
					View::Message($langBase->get('mdc-14', array('-COINS-' => View::CashFormat($object['price']))), 1, true);
			}
			elseif ($object['type'] == 'max_health')
			{
				if (Player::Data('wanted-level') >= $config['max_health'])
				{
					echo View::Message($langBase->get('mdc-15'), 2);
				}
				else
				{
					$db->Query("UPDATE `[players]` SET `health`='".$config['max_health']."', `points`=`points`-'".$object['price']."' WHERE `id`='".Player::Data('id')."'");
					
					Accessories::AddToLog(Player::Data('id'), array('object' => $object_id));
					
					View::Message($langBase->get('mdc-16', array('-COINS-' => View::CashFormat($object['price']))), 1, true);
				}
			}
			elseif ($object['type'] == 'reset_pr')
			{
				if (Player::Data('last_planned_crime')+$config['planned_crime_wait_time'] <= time())
				{
					echo View::Message($langBase->get('mdc-17'), 2);
				}
				else
				{
					$db->Query("UPDATE `[players]` SET `last_planned_crime`='0', `points`=`points`-'".$object['price']."' WHERE `id`='".Player::Data('id')."'");
					
					Accessories::AddToLog(Player::Data('id'), array('object' => $object_id));
					
					View::Message($langBase->get('mdc-18', array('-COINS-' => View::CashFormat($object['price']))), 1, true);
				}
			}
			elseif ($object['type'] == 'get_bullets')
			{
				$db->Query("UPDATE `[players]` SET `points`=`points`-'".$object['price']."', `bullets`=`bullets`+'".$object['amount']."' WHERE `id`='".Player::Data('id')."'");
				
				Accessories::AddToLog(Player::Data('id'), array('object' => $object_id));
				
				View::Message($langBase->get('mdc-19', array('-COINS-' => View::CashFormat($object['price']), '-OBJECT-' => View::CashFormat($object['amount']))), 1, true);
			}
			elseif ($object['type'] == 'racolare_prostituate')
			{
				$bordel = unserialize(Player::Data('bordel'));
				$bordel['oras'] = $bordel['oras'] + $object['amount'];
				
				$db->Query("UPDATE `[players]` SET `points`=`points`-'".$object['price']."', `bordel`='".serialize($bordel)."' WHERE `id`='".Player::Data('id')."'");
				
				Accessories::AddToLog(Player::Data('id'), array('object' => $object_id));
				
				View::Message($langBase->get('mdc-74', array('-NUM-' => $object['amount'])), 1, true);
			}
			elseif ($object['type'] == 'vip_acc')
			{
				$db->Query("UPDATE `[players]` SET `points`=`points`-'".$object['price']."', `vip_days`='".(time() + $object['length'])."' WHERE `id`='".Player::Data('id')."'");
				
				Accessories::AddToLog(Player::Data('id'), array('object' => $object_id));
				
				View::Message($langBase->get('mdc-71', array('-COINS-' => View::CashFormat($object['price']))), 1, true);
			}
			elseif ($object['type'] == 'reset_rtnr')
			{
				$db->Query("UPDATE `[players]` SET `points`=`points`-'".$object['price']."', `roata_noroc`='".$object['amount']."' WHERE `id`='".Player::Data('id')."'");
				
				Accessories::AddToLog(Player::Data('id'), array('object' => $object_id));
				
				View::Message($langBase->get('mdc-20', array('-COINS-' => View::CashFormat($object['price']))), 1, true);
			}
			elseif ($object['type'] == 'reset_ruleta')
			{
				$db->Query("UPDATE `[players]` SET `points`=`points`-'".$object['price']."', `ruleta`=`ruleta`-'".$object['amount']."' WHERE `id`='".Player::Data('id')."'");
				
				Accessories::AddToLog(Player::Data('id'), array('object' => $object_id));
				
				View::Message($langBase->get('mdc-21', array('-COINS-' => View::CashFormat($object['price']), '-OBJECT-' => View::CashFormat($object['amount']))), 1, true);
			}
			elseif ($object['type'] == 'hide_ads')
			{
				if (User::Data('ads_hideUntil') >= time())
				{
					echo View::Message($langBase->get('mdc-22'), 2);
				}
				else
				{
					$db->Query("UPDATE `[players]` SET `points`=`points`-'".$object['price']."' WHERE `id`='".Player::Data('id')."'");
					$db->Query("UPDATE `[users]` SET `ads_hideUntil`='".(time()+$object['length'])."' WHERE `id`='".User::Data('id')."'");
					
					View::Message($langBase->get('mdc-23', array('-COINS-' => View::CashFormat($object['price']))), 1, true);
				}
			}
		}
	}

	if (isset($_POST['c_voucher']))
	{
	$v_code = $db->EscapeString($_POST['code']);
		if($_POST['code'] == ""){
			View::Message($langBase->get('mdc-24'), 2, true);
		}else{
			$sql = $db->Query("SELECT points, used FROM `p_vouchers` WHERE `code`='".$v_code."' AND `used`='0'")or die(mysql_error());
			$vcd = $db->FetchArray($sql);
			if($db->GetNumRows($sql) > 0 && $vcd['points'] > 0){
				$db->Query("UPDATE `[players]` SET `points`=`points`+'".$vcd['points']."' WHERE `id`='".Player::Data('id')."'");
				$db->Query("UPDATE `p_vouchers` SET `used`='".time()."', `player`='".Player::Data('id')."' WHERE `code`='".$v_code."' AND `used`='0'");
				View::Message($langBase->get('mdc-26', array('-COINS-' => $vcd['points'])), 1, true);
			}else{
				View::Message($langBase->get('mdc-25'), 2, true);
			}
		}
	}
?>
<div style="width: 620px; margin: 0px auto;">
    <div class="left" style="width: 320px;">
    	<div class="bg_c" style="width: 310px;">
            <h1 class="big"><?=$langBase->get('mdc-27')?></h1>
            <form method="post" action="">
            	<table class="table boxHandle">
                	<thead>
                    	<tr class="small">
                        	<td><?=$langBase->get('mdc-29')?></td>
                            <td width="35"><?=$langBase->get('txt-03')?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
					foreach ($point_usage as $key => $value)
					{
						$i++;
						$c = $i%2 ? 1 : 2;
					?>
                    	<tr class="c_<?=$c?> boxHandle">
                        	<td><input type="radio" name="use_points" value="<?=$key?>" /><?=$value['title']?><br /><span class="dark small"><?=$value['desc']?></span></td>
                            <td class="t_right" width="31"><b><?=View::CashFormat($value['price'])?> C</b></td>
                        </tr>
                    <?php
					}
					?>
                    	<tr class="c_3 center">
                        	<td colspan="2"><input type="submit" value="<?=$langBase->get('txt-01')?>" /></td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
	<script type="text/javascript">
		function go_pay() {
			var pid = document.getElementById("pid").value;
			var gateway = document.getElementById("gateway").value;
			var url = "<?=$config['game_url']?>/system/_Scripts/"+gateway+"/go.php?id="+pid;
			$('pp_status').set('html', '<?=$langBase->get('txt-46')?>');
			location.href=url;
			return false;
		}
	</script>
    <div class="left" style="width: 280px; margin-left: 20px;">
		<div class="bg_c" style="width: 265px;">
            <h1 class="big"><?=$langBase->get('ot-points')?></h1>
			<p><center><?=$langBase->get('mdc-28', array('-COINS-' => View::CashFormat(Player::Data('points'))))?></center></p>
		</div>
    	<div class="bg_c" style="width: 265px;">
            <h1 class="big"><?=$langBase->get('mdc-30')?></h1>
			<?php
				if(!empty($admin_config['fortumo_id']['value']) && !empty($admin_config['fortumo_secret']['value'])) {
			?>
				<h2 style="margin-top: 10px;"><?=$langBase->get('mdc-31')?> SMS</h2>
				<div class="bg_c c_1" style="width: 150px; margin-top: 0;">
					<center><a id="fmp-button" href="#" rel="<?=$admin_config['fortumo_id']['value']?>?cuid=<?=User::Data('id')?>"><img src="http://fortumo.com/images/fmp/fortumopay_96x47.png" width="96" height="47" alt="<?=$langBase->get('mdc-31')?> SMS" border="0" /></a></center>
				</div>
			<?php } ?>
            <h2 style="margin-top: 10px;"><?=$langBase->get('mdc-31')?> Paypal</h2>
           	<div class="bg_c c_1" style="width: 200px; margin-top: 0;">
            	<form onSubmit="return go_pay();">
                <table>
                <tr><td><?=$langBase->get('ot-points')?></td></tr>
				<tr>
					<td>
						<select name="pid" id="pid" style="padding:5px;width:198px">
						<?
							$packs = $db->QueryFetchArrayAll("SELECT id,price,coins FROM `coins_packs` ORDER BY price ASC");
							foreach($packs as $pack){
								echo '<option value="'.$pack['id'].'">'.$pack['coins'].' '.$langBase->get('mdc-33').' - '.$pack['price'].' €</option>';
							}
						?>
						</select>
					</td>
				</tr>
                <tr><td><?=$langBase->get('mdc-75')?></td></tr>
				<tr>
					<td>
						<select name="gateway" id="gateway" style="padding:5px;width:198px">
							<option value="PayPal">PayPal</option>
							<?=(!empty($admin_config['payeer_key']['value']) ? '<option value="Payeer">Payeer</option>' : '')?>
						</select>
					</td>
				</tr>
                </table>
                <h2 id="pp_status"></h2>
                <p class="center"><input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online!" /></p>
                </form>
            </div>
			<p style="margin-top: 10px;">
            	<?=$langBase->get('mdc-34')?>
            </p>
            <div class="hr big" style="margin: 10px 0 10px 0;"></div>
            <h2><?=$langBase->get('mdc-35')?></h2>
        </div>
		<div class="bg_c" style="width: 265px;">
            <h1 class="big"><?=$langBase->get('mdc-36')?></h1><form method="post">
			<p><?=$langBase->get('mdc-37')?></p><br>
			<div class="bg_c c_1" style="width: 200px; margin-top: 0;">
			<center><?=$langBase->get('mdc-38')?>: <input type="text" class="flat" name="code" /><br>
			<input type="submit" name="c_voucher" value="<?=$langBase->get('txt-14')?>" /></center></div></form>
		</div>
    </div>
    <div class="clear"></div>
</div>
<script src="//fortumo.com/javascripts/fortumopay.js" type="text/javascript"></script>