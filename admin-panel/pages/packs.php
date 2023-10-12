<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	if(isset($_POST['add']) && $_POST['points'] > 0 && $_POST['price'] > 0 && $_POST['name'] != ''){
		$db->Query("INSERT INTO `coins_packs` (`name`,`coins`,`price`) VALUES ('".$_POST['name']."','".$_POST['points']."','".$_POST['price']."')");
		$msgSucc = 'Erfolgreich hinzugefügt!';
	}

	if (isset($_GET['d']))
	{
		$cid = $db->EscapeString($_GET['d']);
		$db->Query("DELETE FROM `coins_packs` WHERE `id`='".$cid."'");
		$msgSucc = 'Erfolgreich gelöscht!';
	}
	
$pagina = $_GET['p'];
$limita = 10;
if(is_numeric($pagina)){
	$start = ($pagina-1)*$limita;
}else{
	$start = 0;
}

$total_pages = $db->GetNumRows($db->Query('SELECT id FROM `coins_packs`'));
include('lib/paginare.php');

$sql = $db->Query('SELECT * FROM `coins_packs` ORDER BY price ASC LIMIT '.$start.','.$limita.'');
$packs = $db->FetchArrayAll($sql);

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
                                    <li><a href="#">Coin Pakete</a></li>
                                </ul>
                            </nav>
                        </div>

                        <div class="main-box">

                            <!-- # Regular tables -->
                            <header class="grid_12 head">
                                <h1>Coin Pakete</h1>
                                <span class="divider"></span>
                            </header>

							<div class="grid_12 boxed-table">
								<div class="box">
									<form method="post" action="">
										<header>
											<div class="inner">
												<div class="left title">
													<h1>Paket hinzufügen</h1>
												</div>
											</div>
										</header>
									<div class="box-content no-inner-space">
										<table>
											<thead>
												<tr>
													<th>Name</th>
													<th>Coins</th>
													<th>Preis</th>
													<th>Aktion</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td><input type="text" class="flat" name="name" value="0 coins" /></td>
													<td><input type="text" class="flat" style="width:200px" name="points" value="0" /></td>
													<td><input type="text" class="flat" style="width:200px" name="price" value="0.00" /></td>
													<td class="center"><input type="submit" class="bt blue" name="add" value="Hinzufügen" /></td>
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
                                                <h1>Pakete</h1>
                                            </div>
                                        </div>
                                    </header>

                                    <div class="box-content no-inner-space">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
													<th>Paket Name</th>
													<th>Coins</th>
													<th>Preis</th>
													<th>Aktionen</th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
	foreach($packs as $pack):
?>
                                                <tr>
                                                    <td><?=$pack['id']?></td>
													<td><?=$pack['name']?></td>
													<td><?=$pack['coins']?></td>
													<td><?=$pack['price']?> €</td>
                                                    <td><a href="?page=<?=$_GET['page']?>&amp;d=<?=$pack['id']?>" onclick="return confirm('Bist du sicher?');">Löschen</a></td>
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