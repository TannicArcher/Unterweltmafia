<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
if (Player::Data('level') < 4 || User::Data('userlevel') < 4) {
	include('lib/denied.php');
	exit;
}

$pagina = $_GET['p'];
$limita = 20;
if(is_numeric($pagina)){
	$start = ($pagina-1)*$limita;
}else{
	$start = 0;
}

$total_pages = $db->QueryGetNumRows("SELECT id FROM `[affiliates]` WHERE `balance`>='".$admin_config['aff_minp']['value']."' AND `p_status` > 0");
include('lib/paginare.php');

$affiliates = $db->QueryFetchArrayAll("SELECT id,username,paypal,balance,reg_time,last_log,IP_regged_with FROM `[affiliates]` WHERE `balance`>='".$admin_config['aff_minp']['value']."' AND `p_status` > 0 ORDER BY reg_time DESC LIMIT ".$start.",".$limita."");

if(isset($_POST['cinfo'])){
	$user = $db->EscapeString($_POST['username']);
	$mail = $db->EscapeString($_POST['email']);
	$paypal = $db->EscapeString($_POST['paypal']);
	$sold = $db->EscapeString($_POST['sold']);
	$pass = $db->EscapeString($_POST['pass']);
	$status = $db->EscapeString($_POST['pstatus']);
	$usid = $db->EscapeString($_POST['uid']);
	if(empty($user) || empty($mail) || empty($usid) || !is_numeric($sold)){
		$msgErr = 'Please complete all fields!';
	}else{
		$db->Query("UPDATE `[affiliates]` SET `username`='".$user."', `email`='".$mail."', `balance`='".$sold."', `p_status`='".$status."', `paypal`='".$paypal."' WHERE `id`='".$usid."'");
		$msgSucc = 'Affiliate account successfully edited!';
	}
	$uid = $usid;
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
            </header><?if($msgErr != ''){?>
			<div class="alert red air">
                <p><strong><?=$msgErr?></strong></p>
                     <a href="#" class="close">close</a>
            </div><?}elseif($msgSucc != ''){?>
			<div class="alert green air">
                <p><strong><?=$msgSucc?></strong></p>
                     <a href="#" class="close">close</a>
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
                                    <li><a href="index.php">Dashboard</a> <span class="divider"></span></li>
                                    <li><a href="#">Affiliates</a></li>
                                </ul>
                            </nav>
                        </div>

                        <div class="main-box">
                            <header class="grid_12 head">
                                <h1>Affiliates</h1>
                                <span class="divider"></span>
                            </header>
<?
	if(isset($_GET['edit']) && is_numeric($_GET['edit'])){
		$uid = $db->EscapeString($_GET['edit']);
		$user = $db->QueryFetchArray("SELECT * FROM `[affiliates]` WHERE `id`='".$uid."'");
		
		if(isset($_GET['paid'])){
			$db->Query("UPDATE `[affiliates]` SET `p_balance`=`p_balance`+`balance`, `balance`='0.00' WHERE `id`='".$user['id']."'");
			$user = $db->QueryFetchArray("SELECT * FROM `[affiliates]` WHERE `id`='".$user['id']."'");
			$msgSucc = 'Affiliate was successfully marked as paid!';
		}
?>
							<div class="box grid_6">
                                <header>
                                    <div class="inner">
                                        <div class="left title">
                                            <h1>Affiliate</h1>

                                        </div>
                                        <div class="right">
                                            <a href="#" class="close">close</a>
                                        </div>
                                    </div>
                                </header>
                            
                                <div class="box-content"><form method="POST"><input type="hidden" name="uid" value="<?=$uid?>" />
                                    <div class="field fullwidth">
                                        <label for="text-input-normal">Username: </label>
                                        <input type="text" id="text-input-normal" name="username" value="<?=$user['username']?>" data-icon="user" />
                                    </div>
									
									<div class="field fullwidth">
                                        <label for="text-input-normal">Email: </label>
                                        <input type="text" id="text-input-normal" name="email" value="<?=$user['email']?>" />
                                    </div>
									
									<div class="field fullwidth">
                                        <label for="text-input-normal">PayPal Email: </label>
                                        <input type="text" id="text-input-normal" name="paypal" value="<?=$user['paypal']?>" />
                                    </div>
									
									<div class="field fullwidth">
                                        <label for="text-input-normal">Account Balance: </label>
                                        <input type="text" id="text-input-normal" name="sold" value="<?=$user['balance']?>" />
                                    </div>

                                    <div class="field fullwidth">
                                        <label for="select">Receive Payment: </label>
                                        <select id="select" name="pstatus">
                                            <option value="1">Enabled</option>
                                            <option value="0"<?=($user['p_status'] == 0 ? ' selected' : '')?>>Disabled</option>
                                        </select>
                                    </div>
									<footer class="pane">
                                        <input type="submit" name="cinfo" class="bt blue" value="Submit" />
                                        <a href="index.php?page=affiliates&edit=<?=$user['id']?>&paid" onclick="return confirm('Do you want to mark user account balance as paid?');" class="bt green right">Mark as Paid</a>
                                    </footer>
                                </form></div>
                            </div>
							<div class="grid_6">
								<div class="box">
									<header>
										<div class="inner">
											<div class="left title">
												<h1>Personal Details</h1>
											</div>
									</header>
									<div class="box-content">
										<div class="field fullwidth">
											<label for="text-input-readonly">First Name: </label>
											<input type="text" id="text-input-readonly" value="<?=$user['f_name']?>" readonly />
										</div>

										<div class="field fullwidth">
											<label for="text-input-readonly">Last Name: </label>
											<input type="text" id="text-input-readonly" value="<?=$user['l_name']?>" readonly />
										</div>

										<div class="field fullwidth">
											<label for="text-input-readonly">Country: </label>
											<input type="text" id="text-input-readonly" value="<?=$user['country']?>" readonly />
										</div>
									</div>
								</div>
								<div class="simple-box">
									<table class="statistics">
										<tbody>
											<tr>
												<td class="amount"><a href="#" style="color:green">$<?=$user['balance']?></a></td>
												<td>Available Balance</td>
											</tr>
											<tr>
												<td class="amount"><a href="#" style="color:red">$<?=$user['p_balance']?></a></td>
												<td>Received Money</td>
											</tr>
											<tr class="last">
												<td class="amount"><a href="#">$<?=$user['t_balance']?></a></td>
												<td>Total Money</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
<?}else{?>
                            <!-- Media table -->
                            <div class="grid_12 boxed-table">
                                <div class="box">
                                    <header>
                                        <div class="inner">
                                            <div class="left title">
                                                <h1>Affiliates waiting for payment (<?=$total_pages?>)</h1>
                                            </div>
                                        </div>
                                    </header>

                                    <div class="box-content no-inner-space">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Username</th>
													<th>PayPal Email</th>
													<th>Balance</th>
													<th>Registered IP</th>
                                                    <th>Registration Date</th>
													<th>Last Activity</th>
                                                    <th class="sorting_disabled">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
	foreach($affiliates as $aff):
?>
                                                <tr>
                                                    <td><?=$aff['username']?></td>
													<td><?=$aff['paypal']?></td>
													<td>$<?=$aff['balance']?></td>
													<td class="center"><?=long2ip($aff['IP_regged_with'])?></td>
                                                    <td class="center"><?=date('d M Y - H:i', $aff['reg_time'])?></td>
													<td class="center"><?=date('d M Y - H:i', $aff['last_log'])?></td>
                                                    <td class="center">
                                                        <a href="index.php?page=aff_min&edit=<?=$aff['id']?>" class="tooltip glyph settings" title="Edit"></a>
														<a href="index.php?page=aff_min&edit=<?=$aff['id']?>&paid" onclick="return confirm('Do you want to mark user account balance as paid?');" class="tooltip glyph approved" title="Mark as Paid"></a>
													</td>
                                                </tr>
<?php
	endforeach;
?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="extension bottom inright pagination">
                                    <nav>
                                        <ul>
                                            <li<?=(($pagina <= 1 || $pagina == '') ? ' class="disabled"' : '')?>><?=(($pagina <= 1 || $pagina == '') ? '<span>&laquo; Back</span>' : '<a href="index.php?page=aff_min&p='.($pagina-1).'">&laquo; Back</a>')?></li>
                                            <?=$pagination?>
                                            <li<?=(($pagina >= $lastpage || $pagina == '') ? ' class="disabled"' : '')?>><?=(($pagina >= $lastpage || $pagina == '') ? '<span>Next &raquo; </span>' : '<a href="index.php?page=aff_min&p='.($pagina+1).'">Next &raquo;</a>')?></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <!-- /Media table -->
<?}?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- /wrapper -->
<?
include("footer.php");
?>