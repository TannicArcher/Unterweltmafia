<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

$pagina = $_GET['p'];
$limita = 20;
if(is_numeric($pagina)){
	$start = ($pagina-1)*$limita;
}else{
	$start = 0;
}

$tt_pages = $db->Query("SELECT id FROM `[users]` WHERE `userlevel`='0' OR `hasPlayer`='0'");
$total_pages = $db->GetNumRows($tt_pages);
include('lib/paginare.php');

$sql = $db->Query("SELECT id,email,last_active,reg_time,IP_regged_with,IP_last,userlevel,hasPlayer FROM `[users]` WHERE `userlevel`='0' OR `hasPlayer`='0' ORDER BY userlevel ASC LIMIT ".$start.",".$limita."");
$jucatori = $db->FetchArrayAll($sql);
$numusr = $db->GetNumRows($tt_pages);

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
                                    <li><a href="#">Gesperrte Benutzer</a></li>
                                </ul>
                            </nav>
                        </div>

                        <div class="main-box">

                            <!-- # Regular tables -->
                            <header class="grid_12 head">
                                <h1>Benutzer</h1>
                                <span class="divider"></span>
                            </header>

                            <!-- Media table -->
                            <div class="grid_12 boxed-table">
                                <div class="box">
                                    <header>
                                        <div class="inner">
                                            <div class="left title">
                                                <h1>Benutzer (<?=$numusr?>)</h1>
                                            </div>
                                        </div>
                                    </header>

                                    <div class="box-content no-inner-space">
                                        <table>
                                            <thead>
                                                <tr>
													<th>ID</th>
                                                    <th>Email</th>
													<th>Grund</th>
													<th>Registrierte IP</th>
													<th>Letzte IP</th>
                                                    <th>Registrierungsdatum</th>
													<th>Letzte Aktivität</th>
                                                    <th class="sorting_disabled">Aktionen</th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
	foreach($jucatori as $jucator):
?>
                                                <tr>
													<td><?=$jucator['id']?></td>
                                                    <td><?=$jucator['email']?></td>
													<td><?=($jucator['userlevel'] == 0 ? 'Gesperrt' : 'Tot')?></td>
													<td><?=$jucator['IP_regged_with']?></td>
													<td><?=$jucator['IP_last']?></td>
                                                    <td class="center"><?=View::Time($jucator['reg_time'])?></td>
													<td class="center"><?=View::Time($jucator['last_active'])?></td>
                                                    <td class="center">
                                                        <a href="/game/?side=game_panel/user&id=<?=$jucator['id']?>" target="_blank" class="tooltip glyph settings" title="Bearbeiten"></a>
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