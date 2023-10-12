<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	if (Player::Data('level') < 4 || User::Data('userlevel') < 4) {
		include('lib/denied.php');
		exit;
	}

	if(isset($_POST['save_settings'])){
		$posts = $db->EscapeString($_POST['edit_value']);
		foreach ($posts as $key => $value){
			if($site[$key] != $value){
				if($key == 'aff_smsprc' || $key == 'aff_paypalprc'){
					$value = ($value > 90 ? 90 : ($value < 0 ? 0 : $value));
				}elseif($key == 'aff_minp'){
					$value = ($value < 0.01 ? 0.01 : $value);
				}elseif($key == 'aff_newreg' || $key == 'aff_reward_cash' || $key == 'bonus_credite'){
					$value = ($value < 0 ? 0 : $value);
				}

				$db->Query("UPDATE `admin_config` SET `config_value`='".$value."' WHERE `config_name`='".$key."'");
				$admin_config[$key]['value'] = $value;
			}
		}
		
		$msgSucc = 'Erfolgreich gespeichert!';
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
		</header><?if(!empty($msgErr)){?>
		<div class="alert red air">
			<p><strong><?=$msgErr?></strong></p>
				 <a href="#" class="close">schließen</a>
		</div><?}elseif(!empty($msgSucc)){?>
		<div class="alert green air">
			<p><strong><?=$msgSucc?></strong></p>
				 <a href="#" class="close">schließen</a>
		</div><?}?>

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
								<li><a href="index.php">Kontrollbereich</a> <span class="divider"></span></li>
								<li><a href="#">Einstellungen</a></li>
							</ul>
						</nav>
					</div>

                    <div class="main-box">
						<div class="grid_6">
							<div class="box">
								<header>
									<div class="inner">
										<div class="left title">
											<h1>Spiel Einstellungen</h1>
										</div>
									</div>
								</header>
								<div class="box-content">
									<form method="post">
										<div class="field fullwidth">
											<label for="text-input-normal">Spiel Name</label>
											<input type="text" id="text-input-normal" name="edit_value[game_name]" value="<?=$admin_config['game_name']['value']?>" />
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">Spiel Runde</label>
											<input type="text" id="text-input-normal" name="edit_value[game_round]" value="<?=$admin_config['game_round']['value']?>" />
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">Spiel URL <br /><span>ohne / am Ende</span></label>
											<input type="text" id="text-input-normal" name="edit_value[game_url]" value="<?=$admin_config['game_url']['value']?>" />
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">Kontakt Email</label>
											<input type="text" id="text-input-normal" name="edit_value[contact_email]" value="<?=$admin_config['contact_email']['value']?>" />
										</div>
										<footer class="pane">
											<input type="submit" name="save_settings" class="bt blue" value="Speichern" />
										</footer>
									</form>
								</div>
							</div>
							<div class="box">
								<header>
									<div class="inner">
										<div class="left title">
											<h1>Zahlungseinstellungen</h1>
										</div>
									</div>
								</header>
								<div class="box-content">
									<form method="post">
										<div class="field fullwidth">
											<label for="text-input-normal">PayPal Email</label>
											<input type="text" id="text-input-normal" name="edit_value[paypal_email]" value="<?=$admin_config['paypal_email']['value']?>" />
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">Fortumo - Service ID</label>
											<input type="text" id="text-input-normal" name="edit_value[fortumo_id]" value="<?=$admin_config['fortumo_id']['value']?>" />
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">Fortumo - Secret Key</label>
											<input type="text" id="text-input-normal" name="edit_value[fortumo_secret]" value="<?=$admin_config['fortumo_secret']['value']?>" />
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">Payeer Merchant ID</label>
											<input type="text" id="text-input-normal" name="edit_value[payeer_key]" value="<?=$admin_config['payeer_key']['value']?>" />
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">Payeer Secret Key</label>
											<input type="text" id="text-input-normal" name="edit_value[payeer_secret]" value="<?=$admin_config['payeer_secret']['value']?>" />
										</div>
										<footer class="pane">
											<input type="submit" name="save_settings" class="bt blue" value="Speichern" />
										</footer>
									</form>
								</div>
							</div>
							<?php
								if($config['affiliate_module']) {
							?>
							<div class="box">
								<header>
									<div class="inner">
										<div class="left title">
											<h1>Affiliate Settings</h1>
										</div>
									</div>
								</header>
								<div class="box-content">
									<form method="post">
										<div class="field fullwidth">
											<label for="text-input-normal">New Player <br /><span>Money received for every new player</span></label>
											<input type="text" id="text-input-normal" name="edit_value[aff_newreg]" value="<?=$admin_config['aff_newreg']['value']?>" />
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">SMS Commission <br /><span>Commission received from every SMS sale (maximum is 90)</span></label>
											<input type="text" id="text-input-normal" name="edit_value[aff_smsprc]" value="<?=$admin_config['aff_smsprc']['value']?>" />
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">PayPal Commission <br /><span>Commission received from every PayPal sale (maximum is 90)</span></label>
											<input type="text" id="text-input-normal" name="edit_value[aff_paypalprc]" value="<?=$admin_config['aff_paypalprc']['value']?>" />
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">Minimum Payment <br /><span>Required money before withdrawal</span></label>
											<input type="text" id="text-input-normal" name="edit_value[aff_minp]" value="<?=$admin_config['aff_minp']['value']?>" />
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">Level Reached <br /><span>Money paid when invited player reach required level (level <?=$config['aff_min_rank']?>)</span></label>
											<input type="text" id="text-input-normal" name="edit_value[aff_reward_cash]" value="<?=$admin_config['aff_reward_cash']['value']?>" />
										</div>
										<footer class="pane">
											<input type="submit" name="save_settings" class="bt blue" value="Speichern" />
										</footer>
									</form>
								</div>
							</div>
							<?php } ?>
                        </div>
						<div class="grid_6">
							<div class="box">
								<header>
									<div class="inner">
										<div class="left title">
											<h1>Spieleinstellungen</h1>
										</div>
									</div>
								</header>
								<div class="box-content">
									<form method="post">
										<div class="field fullwidth">
											<label for="text-input-normal">Munition Verkauf <br /><span>Zeiten, in denen Munition zum Verkauf angeboten wird</span></label>
											<input type="text" id="text-input-normal" name="edit_value[bulletBuy_times]" value="<?=$admin_config['bulletBuy_times']['value']?>" />
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">Rennen schließen <br /><span>Deaktiviere "Straßenrennen" option (true / false)</span></label>
											<input type="text" id="text-input-normal" name="edit_value[car_race_closed]" value="<?=$admin_config['car_race_closed']['value']?>" />
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">Lotterie schließen <br /><span>Deaktiviere "Lotterie" option (true / false)</span></label>
											<input type="text" id="text-input-normal" name="edit_value[lottery_closed]" value="<?=$admin_config['lottery_closed']['value']?>" />
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">Killer Start <br /><span>Zeit wenn "Killer" Funktion startet</span></label>
											<input type="text" id="text-input-normal" name="edit_value[killtime_start]" value="<?=$admin_config['killtime_start']['value']?>" />
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">Killer Stop <br /><span>Zeit wenn "Killer" Funktion endet</span></label>
											<input type="text" id="text-input-normal" name="edit_value[killtime_stop]" value="<?=$admin_config['killtime_stop']['value']?>" />
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">Coins bei Anmeldung <br /><span>Coins die man bei Registrierung bekommt</span></label>
											<input type="text" id="text-input-normal" name="edit_value[bonus_credite]" value="<?=$admin_config['bonus_credite']['value']?>" />
										</div>
										<footer class="pane">
											<input type="submit" name="save_settings" class="bt blue" value="Speichern" />
										</footer>
									</form>
								</div>
							</div>
							
							<div class="box">
								<header>
									<div class="inner">
										<div class="left title">
											<h1>Fortumo Instructions</h1>
										</div>
									</div>
								</header>
								<div class="box-content">
									<p>1) Create a new account on fortumo here: <a href="http://fortumo.com/affiliate/tfd.do.am" target="_blank">fortumo.com/register</a></p>
									<p>2) Login and start creating a new service here: <a href="https://fortumo.com/services/pay-by-mobile/new" target="_blank">fortumo.com/services/pay-by-mobile/new</a></p>
									<p style="margin-left:20px">Add your desired countries, complete everything following instructions and click "Next". On this page complete "To which URL will your payment requests be forwarded to?" with:<br /><br />
									<?=$admin_config['game_url']['value']?>system/_Scripts/SMS/ipn.php<br /><br />
									and "Where to redirect the user after completing the payment?" with:<br /><br />
									<?=$admin_config['game_url']['value']?>?side=magazin-credite&amp;pp_success<br /><br />
									</p>
									<p>3) Save the service and then complete here with your Fortumo ID and Secret Key (you will find them on your service page). Done!</p>
								</div>
							</div>
							
							<div class="box">
								<header>
									<div class="inner">
										<div class="left title">
											<h1>Payeer Instructions</h1>
										</div>
									</div>
								</header>
								<div class="box-content">
									<p>1) Login on your <a href="https://payeer.com/04641331" target="_blank">Payeer account</a>, go to <i>Dashboard</i> -> <i>Merchant Settings</i> then click on <i>Add Merchant</i>. <strong style="color:red;">ATENTION:</strong> Please copy the <i>Secret key</i>, then past it here into <i>Secret Key</i> field</p>
									<p>2) Complete first form with required info then confirm your website following provided instructions.</p>
									<p>3) Complete <i>Merchant Settings</i> using URL's from bellow then submit your website for approval.</p>
									<p style="margin-left:20px">
										<ul>
											<li><b>Success URL</b><br /><input type="text" value="<?=$admin_config['game_url']['value']?>?side=magazin-credite&amp;pp_success" onclick="this.select()" style="width:300px" readonly /></li><br />
											<li><b>Fail URL</b><br /><input type="text" value="<?=$admin_config['game_url']['value']?>?side=magazin-credite&amp;pp_cancel" onclick="this.select()" style="width:300px" readonly /></li><br />
											<li><b>Status URL</b><br /><input type="text" value="<?=$admin_config['game_url']['value']?>/System/_Scripts/Payeer/ipn.php" onclick="this.select()" style="width:300px" readonly /></li>
										</ul>
									</p>
									<p>4) Complete field <i>Merchant ID</i> with your Payeer Merchant ID for this website.</p>
								</div>
							</div>
						</div>
                    </div>
				</div>
			</div>
		</section>
	</div>
<?php
	include("footer.php");
?>