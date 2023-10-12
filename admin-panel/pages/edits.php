<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
if (Player::Data('level') < 4 || User::Data('userlevel') < 4)
	{
		include('lib/denied.php');
		exit;
	}

$pagina = $_GET['p'];
$limita = 10;
if(is_numeric($pagina)){
	$start = ($pagina-1)*$limita;
}else{
	$start = 0;
}

$total_pages = $db->GetNumRows($db->Query("SELECT id FROM `[log]` WHERE `side`='game_panel/user' OR `side`='game_panel/player'"));
include('lib/paginare.php');

$sql = $db->Query("SELECT id,playerid,extra,timestamp FROM `[log]` WHERE `side`='game_panel/user' OR `side`='game_panel/player' ORDER BY id DESC LIMIT ".$start.",".$limita."");
$edits = $db->FetchArrayAll($sql);

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
                                    <li><a href="#">Änderungen</a></li>
                                </ul>
                            </nav>
                        </div>

                        <div class="main-box">

                            <!-- # Regular tables -->
                            <header class="grid_12 head">
                                <h1>Änderungen</h1>
                                <span class="divider"></span>
                            </header>

                            <!-- Media table -->
                            <div class="grid_12 boxed-table">
                                <div class="box">
                                    <header>
                                        <div class="inner">
                                            <div class="left title">
                                                <h1>Personelle Änderungen</h1>
                                            </div>
                                        </div>
                                    </header>

                                    <div class="box-content no-inner-space">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Bearbeitet von</th>
													<th>Spieler</th>
													<th>Änderung</th>
													<th>Datum</th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
	foreach($edits as $edit):
	$extra = unserialize($edit['extra']);
?>
                                                <tr>
                                                    <td><strong><?=View::Player(array('id' => $edit['playerid']))?></strong></td>
													<td><?=($extra['edit_type'] == 'user' ? '<a href="' . $config['base_url'] . '?side=game_panel/user&amp;id=' . $extra['edited'] . '">#' . $extra['edited'] . '</a>' : View::Player(array('id' => $extra['edited'])))?></td>
													<td><?php
															if (count($extra['changed']) <= 0)
															{
																echo 'N/A';
															}else{
																echo '<ul style="margin: 1px;"><li><strong>&bull; ' . $extra['edit_type'] . '</strong></li>';
					
															foreach ($extra['changed'] as $key => $value)
															{
																echo '<li>&bull; ' . $key . '</li>';
															}
																echo '</ul>';
															}
													?></td>
													<td><?=View::Time($edit['timestamp'])?></td>
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
                                            <li<?=(($pagina <= 1 || $pagina == '') ? ' class="disabled"' : '')?>><?=(($pagina <= 1 || $pagina == '') ? '<span>&laquo; zurück</span>' : '<a href="index.php?page=edits&p='.($pagina-1).'">&laquo; zurück</a>')?></li>
                                            <?=$pagination?>
                                            <li<?=(($pagina >= $lastpage || $pagina == '') ? ' class="disabled"' : '')?>><?=(($pagina >= $lastpage || $pagina == '') ? '<span>weiter &raquo; </span>' : '<a href="index.php?page=edits&p='.($pagina+1).'">weiter &raquo;</a>')?></li>
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