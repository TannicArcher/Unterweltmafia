<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

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
                                    <li><a href="#">Spieler Ereignisse</a></li>
                                </ul>
                            </nav>
                        </div>

                        <div class="main-box">

                            <!-- # Regular tables -->
                            <header class="grid_12 head">
                                <h1>Spieler Ereignisse</h1>
                                <span class="divider"></span>
                            </header>
<?
if (isset($_GET['pl']))
	{	
$pagina = $_GET['p'];
$limita = 20;
if(is_numeric($pagina)){
	$start = ($pagina-1)*$limita;
}else{
	$start = 0;
}
$player = $db->EscapeString($_GET['pl']);

$usr_id = $db->FetchArray($db->Query("SELECT id FROM `[players]` WHERE `name`='".$player."' LIMIT 1"));

$total_pages = $db->GetNumRows($db->Query("SELECT id FROM `" . $config['sql_logdb'] . "`.`logevents` WHERE `player`='".$usr_id['id']."' ORDER BY id DESC"));
include('lib/paginare.php');

$sql = $db->Query("SELECT id,time,data,type,`read`,archived,player,user FROM `" . $config['sql_logdb'] . "`.`logevents` WHERE `player`='".$usr_id['id']."' ORDER BY id DESC LIMIT ".$start.",".$limita."");
$logevents = $db->FetchArrayAll($sql);
?>
                            <!-- Media table -->
                            <div class="grid_12 boxed-table">
                                <div class="box">
                                    <header>
                                        <div class="inner">
                                            <div class="left title">
                                                <h1>Spieler Ereignisse</h1>
                                            </div>
                                        </div>
                                    </header>

                                    <div class="box-content no-inner-space">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
													<th>Spieler</th>
													<th>Art</th>
													<th>Nachricht</th>
                                                    <th>Datum</th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
	foreach($logevents as $event):
?>
                                                <tr>
                                                    <td>#<?=$event['id']?></td>
													<td><?=View::Player(array('id' => $event['player']))?><br /><span class="subtext">Uid: <?=$event['user']?></span></td>
													<td><?=$event['type']?></td>
													<td><?php echo $langBase->getLogEventText($event['type'], unserialize(base64_decode($event['data'])));?></td>
                                                    <td class="center"><?=View::Time($event['time'])?></td>
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
<?}else{?>
    <div class="grid_6 box">
        <header>
           <div class="inner">
                <div class="left title">
					<h1>Spieler suchen</h1>
                </div>
            </div>
        </header>
		<div class="box-content center">
        <form method="get" action="" class="validate">
        	<input type="hidden" name="page" value="<?=$_GET['page']?>" />
				<div class="field fullwidth">
					<label for="text-input-required">Spieler</label>
					<input type="text" class="required" id="text-input-required" name="pl" value="" />
				</div>
				<footer class="pane">
					<input type="submit" class="bt blue" value="Suchen" />
				</footer>
        </form>
		</div>
	</div>
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