<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

$pagina = $_GET['p'];
$limita = 20;
if(is_numeric($pagina)){
	$start = ($pagina-1)*$limita;
}else{
	$start = 0;
}

$total_pages = $db->GetNumRows($db->Query("SELECT id,ads_hideUntil FROM `[users]` WHERE `ads_hideUntil`>'0'"));
include('lib/paginare.php');

$sql = $db->Query("SELECT id,ads_hideUntil FROM `[users]` WHERE `ads_hideUntil`>'0' ORDER BY ads_hideUntil DESC LIMIT ".$start.",".$limita."");
$adss = $db->FetchArrayAll($sql);

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
                                    <li><a href="index.php">Kontrollbereich</a> <span class="divider"></span></li>
                                    <li><a href="#">Versteckte Anzeigen</a></li>
                                </ul>
                            </nav>
                        </div>

                        <div class="main-box">
                            <!-- Media table -->
                            <div class="grid_12 boxed-table">
                                <div class="box">
                                    <header>
                                        <div class="inner">
                                            <div class="left title">
                                                <h1>Versteckte Anzeigen</h1>
                                            </div>
                                        </div>
                                    </header>

                                    <div class="box-content no-inner-space">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Spieler</th>
													<th>Abgelaufen?</th>
													<th>Verbleibende Zeit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
	foreach($adss as $ads):
	$usr1 = mysql_fetch_object($db->Query("SELECT id FROM `[players]` WHERE `userid`='".$ads['id']."'"));
?>
                                                <tr>
                                                    <td><?=View::Player(array('id' => $usr1->id))?></td>
													<td><?=($ads['ads_hideUntil'] > time() ? 'Nein' : 'Ja')?></td>
													<td><?=($ads['ads_hideUntil'] > time() ? View::strTime($ads['ads_hideUntil']-time(), true, ', ') : 'Expired')?></td>
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
                            <!-- /Media table -->

                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- /wrapper -->
<?
include("footer.php");
?>