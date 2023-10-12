<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

$pagina = $_GET['p'];
$limita = 20;
if(is_numeric($pagina)){
	$start = ($pagina-1)*$limita;
}else{
	$start = 0;
}

$tt_pages = $db->Query('SELECT id FROM `[users]`');
$total_pages = $db->GetNumRows($tt_pages);
include('lib/paginare.php');

$sql = $db->Query('SELECT id,email,last_active,reg_time,IP_regged_with,IP_last,hasPlayer FROM `[users]` ORDER BY userlevel DESC LIMIT '.$start.','.$limita.'');
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
                                    <li><a href="index.php">Dashboard</a> <span class="divider"></span></li>
                                    <li><a href="#">Users</a></li>
                                </ul>
                            </nav>
                        </div>

                        <div class="main-box">

                            <!-- # Regular tables -->
                            <header class="grid_12 head">
                                <h1>Users</h1>
                                <span class="divider"></span>
                            </header>

                            <!-- Media table -->
                            <div class="grid_12 boxed-table">
                                <div class="box">
                                    <header>
                                        <div class="inner">
                                            <div class="left title">
                                                <h1>Users (<?=$numusr?>)</h1>
                                            </div>
                                        </div>
                                    </header>

                                    <div class="box-content no-inner-space">
                                        <table>
                                            <thead>
                                                <tr>
													<th>ID</th>
                                                    <th>Email</th>
													<th>Player Name</th>
													<th>Registered IP</th>
													<th>Last IP</th>
                                                    <th>Registration Date</th>
													<th>Last Activity</th>
                                                    <th class="sorting_disabled">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
	foreach($jucatori as $jucator):
?>
                                                <tr>
													<td><?=$jucator['id']?></td>
                                                    <td><?=$jucator['email']?></td>
													<td><?=View::Player(array('id' => $jucator['id']), false, 'N/A', 2)?></td>
													<td><?=$jucator['IP_regged_with']?></td>
													<td><?=$jucator['IP_last']?></td>
                                                    <td class="center"><?=View::Time($jucator['reg_time'])?></td>
													<td class="center"><?=View::Time($jucator['last_active'])?></td>
                                                    <td class="center">
                                                        <a href="/game/?side=game_panel/user&id=<?=$jucator['id']?>" target="_blank" class="tooltip glyph settings" title="Edit User"></a>
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
                                            <li<?=(($pagina <= 1 || $pagina == '') ? ' class="disabled"' : '')?>><?=(($pagina <= 1 || $pagina == '') ? '<span>&laquo; Back</span>' : '<a href="index.php?page=users&p='.($pagina-1).'">&laquo; Back</a>')?></li>
                                            <?=$pagination?>
                                            <li<?=(($pagina >= $lastpage || $pagina == '') ? ' class="disabled"' : '')?>><?=(($pagina >= $lastpage || $pagina == '') ? '<span>Next &raquo; </span>' : '<a href="index.php?page=users&p='.($pagina+1).'">Next &raquo;</a>')?></li>
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