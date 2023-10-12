<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$code = rand(1000,9999).'-'.rand(1000,9999).'-'.rand(1000,9999).'-'.rand(1000,9999);
	
	if(isset($_POST['add']) && $_POST['points'] > 0 && $_POST['code'] != ""){
		$db->Query("INSERT INTO `p_vouchers` (`points`,`code`,`created`) VALUES ('".$_POST['points']."','".$_POST['code']."','".time()."')")or die(mysql_error());
		$msgSucc = 'Der Gutscheincode "'.$_POST['code'].'" mit einem Wert von '.$_POST['points'].' Coins wurde erfolgreich hinzugefügt!';
	}

$pagina = $_GET['p'];
$limita = 10;
if(is_numeric($pagina)){
	$start = ($pagina-1)*$limita;
}else{
	$start = 0;
}

$total_pages = $db->GetNumRows($db->Query('SELECT id FROM `p_vouchers`'));
include('lib/paginare.php');

$sql = $db->Query('SELECT * FROM `p_vouchers` ORDER BY created DESC LIMIT '.$start.','.$limita.'');
$vouchers = $db->FetchArrayAll($sql);

include("header.php");
?>
        <!-- wrapper -->
        <div id="wrapper">
            <header>
                <div class="container_12">
                    <div class="grid_12">
                        <!-- navigation menu -->
                        <? include('head_menu.php');?>
                    </div>                
                </div>
            </header><?if($msgSucc != ''){?>
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
                                    <li><a href="index.php">Kontrollbereich</a> <span class="divider"></span></li>
                                    <li><a href="#">Gutscheine</a></li>
                                </ul>
                            </nav>
                        </div>

                        <div class="main-box">

                            <!-- # Regular tables -->
                            <header class="grid_12 head">
                                <h1>Gutscheine</h1>
                                <span class="divider"></span>
                            </header>

							<div class="grid_12 boxed-table">
								<div class="box">
									<form method="post" action="">
										<header>
											<div class="inner">
												<div class="left title">
													<h1>Gutschein erstellen</h1>
												</div>
											</div>
										</header>
									<div class="box-content no-inner-space">
										<table>
											<thead>
												<tr>
													<th>Coins</th>
													<th>Code</th>
													<th>Aktion</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td><input type="text" class="flat" name="points" value="10" /></td>
													<td><input type="text" class="flat" name="code" value="<?=$code?>" /></td>
													<td class="center"><input type="submit" class="bt blue" name="add" value="Erstellen" /></td>
												</tr>
											</tbody>
										</table>
									</form>
									</div>
								</div>
							</div>

                            <!-- Media table -->
                            <div class="grid_12 boxed-table">
                                <div class="box">
                                    <header>
                                        <div class="inner">
                                            <div class="left title">
                                                <h1>Gutscheine</h1>
                                            </div>
                                        </div>
                                    </header>

                                    <div class="box-content no-inner-space">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
													<th>Coins</th>
													<th>Code</th>
													<th>Erstellt</th>
                                                    <th>Eingelöst</th>
													<th>Spieler</th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
	foreach($vouchers as $voucher):
?>
                                                <tr>
                                                    <td><?=$voucher['id']?></td>
													<td><?=$voucher['points']?></td>
													<td><?=$voucher['code']?></td>
													<td><?=View::time($voucher['created'])?></td>
                                                    <td><?=($voucher['used'] == 0 ? 'Nefolosit' : View::time($voucher['used']))?></td>
													<td><?=($voucher['player'] == 0 ? 'N/A' : View::Player(array('id' => $voucher['player'])))?></td>
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
                                            <li<?=(($pagina <= 1 || $pagina == '') ? ' class="disabled"' : '')?>><?=(($pagina <= 1 || $pagina == '') ? '<span>&laquo; zurück</span>' : '<a href="index.php?page=users&p='.($pagina-1).'">&laquo; zurück</a>')?></li>
                                            <?=$pagination?>
                                            <li<?=(($pagina >= $lastpage || $pagina == '') ? ' class="disabled"' : '')?>><?=(($pagina >= $lastpage || $pagina == '') ? '<span>weiter &raquo; </span>' : '<a href="index.php?page=users&p='.($pagina+1).'">weiter &raquo;</a>')?></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- /wrapper -->
<?
include("footer.php");
?>