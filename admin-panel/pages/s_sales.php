<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
if (Player::Data('level') < 4 || User::Data('userlevel') < 4)
	{
		include('lib/denied.php');
		exit;
	}

$pagina = $_GET['p'];
$limita = 15;
if(is_numeric($pagina)){
	$start = ($pagina-1)*$limita;
}else{
	$start = 0;
}

$total_pages = $db->GetNumRows($db->Query('SELECT id FROM `sms_points`'));
include('lib/paginare.php');

$sql = $db->Query('SELECT * FROM `sms_points` ORDER BY Date DESC LIMIT '.$start.','.$limita.'');
$orders = $db->FetchArrayAll($sql);

$ssctotal = $db->Query("SELECT Currency, SUM(Revenue) FROM `sms_points` GROUP BY Currency");

$totcash = '';
while($row = mysql_fetch_array($ssctotal)){
	$totcash .= $row['SUM(Revenue)']." ".$row['Currency'].", ";
}


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
                                    <li><a href="#">Verkäufe</a></li>
                                </ul>
                            </nav>
                        </div>

                        <div class="main-box">

                            <!-- # Regular tables -->
                            <header class="grid_12 head">
                                <h1>Verkäufe</h1>
                                <span class="divider"></span>
                            </header>

                            <!-- Media table -->
                            <div class="grid_12 boxed-table">
                                <div class="box">
                                    <header>
                                        <div class="inner">
                                            <div class="left title">
                                                <h1>Verkäufe mit SMS</h1>
                                            </div>
											<div class="right title">
												<?=$totcash?>
											</div>
                                        </div>
                                    </header>

                                    <div class="box-content no-inner-space">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Spieler</th>
													<th>Anbieter</th>
													<th>SMS ID</th>
													<th>Datum</th>
                                                    <th>Type</th>
													<th>Coins</th>
                                                    <th>Preis</th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
	foreach($orders as $order):
	$pps = $db->QueryFetchArray("SELECT id FROM `[players]` WHERE `userid`='".$order['UserId']."' AND `null`='0'");
?>
                                                <tr>
                                                    <td><?=View::Player(array('id' => $pps['id']))?></td>
													<td><?=($order['Operator'] == '' ? 'N/A' : $order['Operator'])?></td>
													<td><?=$order['Message_ID']?></td>
													<td><?=View::Time($order['Date'])?></td>
                                                    <td><?=($order['Num_Points'] == 0 ? 'Viata' : 'Credite')?></td>
													<td><?=$order['Num_Points']?></td>
                                                    <td><?=$order['Revenue']?> <?=$order['Currency']?></td>
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
                                            <li<?=(($pagina <= 1 || $pagina == '') ? ' class="disabled"' : '')?>><?=(($pagina <= 1 || $pagina == '') ? '<span>&laquo; zurück</span>' : '<a href="index.php?page=s_sales&p='.($pagina-1).'">&laquo; zurück</a>')?></li>
                                            <?=$pagination?>
                                            <li<?=(($pagina >= $lastpage || $pagina == '') ? ' class="disabled"' : '')?>><?=(($pagina >= $lastpage || $pagina == '') ? '<span>weiter &raquo; </span>' : '<a href="index.php?page=s_sales&p='.($pagina+1).'">weiter &raquo;</a>')?></li>
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