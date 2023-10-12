<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

$pagina = $_GET['p'];
$limita = 20;
if(is_numeric($pagina)){
	$start = ($pagina-1)*$limita;
}else{
	$start = 0;
}

$search = $db->EscapeString($_GET['s']);
$db_value = ($search != '' ?  " WHERE `name` LIKE '%".$search."%'" : "");


$tt_pages = $db->Query("SELECT id FROM `[players]`".$db_value."");
$total_pages = $db->GetNumRows($tt_pages);
include('lib/paginare.php');

$sql = $db->Query("SELECT id,userid,rank,health,last_active,IP_last,IP_created_with,created,level,name FROM `[players]`".$db_value." ORDER BY level DESC LIMIT ".$start.",".$limita."");
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
                                    <li><a href="#">Spieler</a></li>
                                </ul>
                            </nav>
                        </div>

                        <div class="main-box">

                            <!-- # Regular tables -->
                            <header class="grid_12 head">
                                <h1>Spieler</h1>
                                <span class="divider"></span>
                            </header>

                            <!-- Media table -->
                            <div class="grid_12 boxed-table">
                                <div class="box">
                                    <header>
                                        <div class="inner">
                                            <div class="left title">
                                                <h1>Spieler (<?=$numusr?>)</h1>
                                            </div>
                                        </div>
                                    </header>

                                    <div class="box-content no-inner-space">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Spieler</th>
													<th>Rank</th>
													<th>Tot</th>
													<th>Registrierte IP</th>
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
                                                    <td><?=View::Player($jucator)?></td>
													<td><?=$config['ranks'][$jucator['rank']][0]?></td>
													<td><?=($jucator['health'] <= 0 ? 'Ja' : 'Nein')?></td>
													<td><?=$jucator['IP_created_with']?></td>
                                                    <td class="center"><?=View::Time($jucator['created'])?></td>
													<td class="center"><?=View::Time($jucator['last_active'])?></td>
                                                    <td class="center">
                                                        <a href="/game/?side=game_panel/player&id=<?=$jucator['id']?>" target="_blank" class="tooltip glyph user" title="Spieler bearbeiten"></a> <a href="/game/?side=game_panel/user&id=<?=$jucator['userid']?>" target="_blank" class="tooltip glyph settings" title="Benutzer bearbeiten"></a>
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