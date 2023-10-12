<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	// Player Stats
	$game_stats = $db->QueryFetchArray("SELECT * FROM `game_stats` WHERE `id`='1' LIMIT 1");
	$user_stats      =  unserialize($game_stats['user_stats']);
	$player_stats    =  unserialize($game_stats['player_stats']);
	
	$onplayers = $db->QueryGetNumRows("SELECT * FROM `[players]` WHERE `online`+'3600'>'".time()."'");

	// Sales Stats
	$ssctotal = $db->QueryFetchArray("SELECT COUNT(*) AS total, SUM(Revenue) AS income, SUM(Num_Points) AS coins FROM `sms_points` WHERE `Currency`='EUR'");
	$ssctotal['income'] = number_format($ssctotal['income'], 2);
	$ssctotal['total'] = number_format($ssctotal['total']);

	$spctotal = $db->QueryFetchArray("SELECT COUNT(*) AS total, SUM(Revenue) AS income, SUM(Num_Points) AS coins FROM `paypal_points`");
	$spctotal['income'] = number_format($spctotal['income'], 2);
	$spctotal['total'] = number_format($spctotal['total']);

	$sprctotal = $db->QueryFetchArray("SELECT COUNT(*) AS total, SUM(Revenue) AS income, SUM(Num_Points) AS coins FROM `payeer_points`");
	$sprctotal['income'] = number_format($sprctotal['income'], 2);
	$sprctotal['total'] = number_format($sprctotal['total']);

	$ssctotal_td = $db->QueryFetchArray("SELECT COUNT(*) AS total, SUM(Revenue) AS income FROM `sms_points` WHERE `Currency`='EUR' AND `Date` >= UNIX_TIMESTAMP(DATE(NOW()))");
	$ssctotal_td['income'] = number_format($ssctotal_td['income'], 2);
	$ssctotal_td['total'] = number_format($ssctotal_td['total']);
	
	$spctotal_td = $db->QueryFetchArray("SELECT COUNT(*) AS total, SUM(Revenue) AS income FROM `paypal_points` WHERE `Timestamp` >= UNIX_TIMESTAMP(DATE(NOW()))");
	$spctotal_td['income'] = number_format($spctotal_td['income'], 2);
	$spctotal_td['total'] = number_format($spctotal_td['total']);
	
	$sprctotal_td = $db->QueryFetchArray("SELECT COUNT(*) AS total, SUM(Revenue) AS income FROM `payeer_points` WHERE `Timestamp` >= UNIX_TIMESTAMP(DATE(NOW()))");
	$sprctotal_td['income'] = number_format($sprctotal_td['income'], 2);
	$sprctotal_td['total'] = number_format($sprctotal_td['total']);

	$dtm = strtotime(date('F Y'));
	$s_thismonth = $db->QueryFetchArray("SELECT COUNT(*) AS total, SUM(Revenue) AS income FROM `sms_points` WHERE `Currency`='EUR' AND Date >= ".$dtm);
	$s_thismonth['income'] = number_format($s_thismonth['income'], 2);
	$s_thismonth['total'] = number_format($s_thismonth['total']);

	$p_thismonth = $db->QueryFetchArray("SELECT COUNT(*) AS total, SUM(Revenue) AS income FROM `paypal_points` WHERE Timestamp >= ".$dtm);
	$p_thismonth['income'] = number_format($p_thismonth['income'], 2);
	$p_thismonth['total'] = number_format($p_thismonth['total']);

	$pr_thismonth = $db->QueryFetchArray("SELECT COUNT(*) AS total, SUM(Revenue) AS income FROM `payeer_points` WHERE Timestamp >= ".$dtm);
	$pr_thismonth['income'] = number_format($pr_thismonth['income'], 2);
	$pr_thismonth['total'] = number_format($pr_thismonth['total']);

	// Affiliates Stats
	if($config['affiliate_module']) {
		$affiliate = $db->QueryFetchArray("SELECT COUNT(*) AS total, SUM(balance) AS balance, SUM(p_balance) AS paid FROM `[affiliates]`");
		$affiliate['balance'] = number_format($affiliate['balance'], 2);
		$affiliate['paid'] = number_format($affiliate['paid'], 2);
		$affiliate['total'] = number_format($affiliate['total']);
		
		$aff_today = $db->QueryGetNumRows("SELECT * FROM `[affiliates]` WHERE DATE(`reg_time`)='".date('Y-m-d')."'");
		$referred = $db->QueryFetchArray("SELECT COUNT(*) AS total FROM `[users]` WHERE `aff_id` > 0");
		$aff_waiting = $db->QueryGetNumRows("SELECT * FROM `[affiliates]` WHERE `balance`>='".$admin_config['aff_minp']['value']."' AND `p_status` > 0");
	}

	include("header.php");
?>
	<div id="wrapper">
		<header>
			<div class="container_12">
				<div class="grid_12">
						<? include('head_menu.php');?>
				</div>                
			</div>
		</header>

		<section id="main">
			<div class="container_12">
				<div class="grid_12" id="content-top">
					<div id="logo">
						<img src="img/logo.png" alt="logo" />
					</div>
				</div>

				<div id="content">
					<div class="extension top inleft breadcrumbs">
						<nav>
							<ul>
								<li><a href="index.php">Kontrollbereich</a></li>
							</ul>
						</nav>
					</div>

					<div class="main-box">
						<header class="grid_12 head">
							<h1>Statistiken</h1>
							<span class="divider"></span>
						</header>
						<div class="grid_5">
							<div class="box">
							    <header>
                                    <div class="inner">
                                        <div class="left title">
                                            <h1>Spieler</h1>
                                        </div>
                                    </div>
                                </header>
								<div class="box-content no-inner-space">
									<table class="statistics">
										<tbody>
											<tr>
												<td class="amount"><a href="?page=users"><?=$player_stats['num_total']?></a></td>
												<td>Spieler gesamt</td>
											</tr>
											<tr>
												<td class="amount"><a href="#"><?=$player_stats['num_active']?></a></td>
												<td>Aktive Spieler</td>
											</tr>
											<tr>
												<td class="amount"><a href="#"><?=$player_stats['num_dead']?></a></td>
												<td>Tote Spieler</td>
											</tr>
											<tr>
												<td class="amount"><a href="#"><?=$user_stats['num_deactivated']?></a></td>
												<td>Gesperrte Spieler</td>
											</tr>
											<tr>
												<td class="amount"><a href="#"><?=$player_stats['regged_today']?></a></td>
												<td>Heute registriert</td>
											</tr>
											<tr class="last">
												<td class="amount"><a href="#"><?=$onplayers?></a></td>
												<td>Spieler online</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="grid_7">
							<div class="box">
							    <header>
                                    <div class="inner">
                                        <div class="left title">
                                            <h1>Verkäufe</h1>
                                        </div>
                                    </div>
                                </header>
								<div class="box-content no-inner-space">
									<table class="statistics">
										<tbody>
											<tr>
												<td class="amount"><a href="#"><?=($ssctotal['total']+$spctotal['total'])?></a></td>
												<td>Gesamtumsatz</td>
												<td class="changes">$<?=($spctotal['income'].' und '.$ssctotal['income'])?>&euro;</td>
											</tr>
											<tr>
												<td class="amount"><a href="#"><?=($ssctotal_td['total']+$spctotal_td['total'])?></a></td>
												<td>Verkäufe heute</td>
												<td class="changes">$<?=($spctotal_td['income'].' und '.$ssctotal_td['income'])?>&euro;</td>
											</tr>
											<tr>
												<td class="amount"><a href="#"><?=($p_thismonth['total']+$s_thismonth['total'])?></a></td>
												<td>Diesen Monat</td>
												<td class="changes">$<?=($p_thismonth['income'].' und '.$s_thismonth['income'])?>&euro;</td>
											</tr>
											<tr>
												<td class="amount"><a href="#"><?=($ssctotal['total'])?></a></td>
												<td>via SMS</td>
												<td class="changes"><?=$ssctotal['income']?>&euro;</td>
											</tr>
											<tr>
												<td class="amount"><a href="#"><?=($spctotal['total'])?></a></td>
												<td>via PayPal</td>
												<td class="changes">$<?=$spctotal['income']?></td>
											</tr>
											<tr>
												<td class="amount"><a href="#"><?=($sprctotal['total'])?></a></td>
												<td>via Payeer</td>
												<td class="changes">$<?=$sprctotal['income']?></td>
											</tr>
											<tr class="last">
												<td class="amount"><a href="#"><?=($ssctotal['coins']+$spctotal['coins']+$sprctotal['coins'])?></a></td>
												<td>Verkaufte Coins</td>
												<td class="changes"><?=($ssctotal['total']+$spctotal['total']+$sprctotal['total'])?> verkuft</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div> 
						<div class="box grid_6">
							<header>
								<div class="inner">
									<div class="left title">
										<h1>Verkaufsgrafik</h1>
									</div>
								</div>
							</header>
							<div class="box-content no-inner-space">
								<div id="container1"></div>
							</div>
						</div>

						<?php
							if($config['affiliate_module']) {
						?>
						<div class="grid_6">
							<div class="box">
							    <header>
                                    <div class="inner">
                                        <div class="left title">
                                            <h1>Partner</h1>
                                        </div>
                                    </div>
                                </header>
								<div class="box-content no-inner-space">
									<table class="statistics">
										<tbody>
											<tr>
												<td class="amount"><a href="?page=affiliates"><?=$affiliate['total']?></a></td>
												<td>Partner gesamt</td>
											</tr>
											<tr>
												<td class="amount"><a href="#"><?=$aff_today?></a></td>
												<td>Heute registriert</td>
											</tr>
											<tr>
												<td class="amount"><a href="?page=aff_min"><?=$aff_waiting?></a></td>
												<td>Warte auf Zahlung</td>
											</tr>
											<tr>
												<td class="amount"><a href="#"><?=$referred['total']?></a></td>
												<td>Vermittelte Spieler</td>
											</tr>
											<tr>
												<td class="amount"><a href="#">$<?=$affiliate['balance']?></a></td>
												<td>Gesamtkontostand</td>
											</tr>
											<tr class="last">
												<td class="amount"><a href="#">$<?=$affiliate['paid']?></a></td>
												<td>Bezahlt gesamt</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<?php } ?>
						
						<script>
							if($('#container1')[0])
							(function(){
								var data = [
									['Verkäufe via SMS', <?=($ssctotal['total'])?>],['Verkäufe via Paypal', <?=($spctotal['total'])?>],['Verkäufe via Payeer', <?=($sprctotal['total'])?>]
								];
								var plot1 = jQuery.jqplot ('container1', [data],
									{
									  seriesDefaults: {
										renderer: jQuery.jqplot.PieRenderer,
										rendererOptions: {
										  showDataLabels: true
										}
									  },
									  grid: { borderWidth: 0, shadow: false },
									  legend: { show:true, location: 'e' }
									}
								  );
							})();
						</script>
					</div>
				</div>
			</div>
		</section>
	</div>
<?
include("footer.php");
?>