<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

include('header.php');
?>
        <div id="wrapper">
            <header>
                <div class="container_12">
                    <div class="grid_12">
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
<?
if(isset($_GET['ban'])){
	$ip = $db->EscapeString($_GET['ban']);
	
	$pagina = $_GET['p'];
	$limita = 10;
	if(is_numeric($pagina)){
		$start = ($pagina-1)*$limita;
	}else{
		$start = 0;
	}
	
	$total_pages = $db->QueryGetNumRows("SELECT a.id FROM `[users]` a LEFT JOIN `[players]` b ON b.userid = a.id AND b.null = '0' WHERE a.IP_last LIKE '".$ip."'");
	include('lib/paginare.php');

	$players = $db->QueryFetchArrayAll("SELECT a.id, a.last_active, a.userlevel, b.id AS pid, b.name, b.health, b.level FROM `[users]` a LEFT JOIN `[players]` b ON b.userid = a.id AND b.null = '0' WHERE a.IP_last LIKE '".$ip."' ORDER BY a.last_active DESC LIMIT ".$start.",".$limita."");

	if (isset($_POST['dea_reason']))
	{
		$reason = $db->EscapeString($_POST['dea_reason']);
		$adminID = Player::Data('id');
		$time = time();
		
		if(View::Length($reason) <= $config['deactivate_reason_min_length']){
			$msgErr = 'Motivul trebuie sa contina cel putin '.$config['deactivate_reason_min_length'].' caractere.';
		}else{
			$users = $db->QueryFetchArrayAll("SELECT a.id, a.userlevel, b.id AS pid, b.level FROM `[users]` a LEFT JOIN `[players]` b ON b.userid = a.id AND b.null = '0' WHERE a.IP_last LIKE '".$ip."'");
		
			$reasons = array();
			foreach($users as $user){
				if ($user['userlevel'] > 0){
					$db->Query("UPDATE `[users]` SET `userlevel`='0', `online`='0' WHERE `id`='".$user['id']."'");
					$reasons[] = "('user', '".$user['id']."', '".$adminID."', '".$reason."', '".$time."')";
				}
				
				if ($user['level'] > 0){
					$db->Query("UPDATE `[players]` SET `level`='0', `online`='0' WHERE `userid`='".$user['id']."'");
					$reasons[] = "('player', '".$user['pid']."', '".$adminID."', '".$reason."', '".$time."')";
				}
			}

			$reasons = implode(',', $reasons);
			$db->Query("INSERT INTO `deactivations` (`type`, `victim`, `by_player`, `reason`, `time`) VALUES ".$reasons);
			$msgSucc = 'Conturile de utilizator au fost blocate!';
		}
	}
?>
                        <div class="main-box">
                            <header class="grid_12 head">
                                <h1>Suspend account - IP: <?=$_GET['ban']?></h1>
                                <span class="divider"></span>
                            </header>
							<?=(empty($msgErr) ? '' : '<div class="alert red air"><p><strong>'.$msgErr.'</strong></p><a href="#" class="close">close</a></div>')?>
							<?=(empty($msgSucc) ? '' : '<div class="alert green air"><p><strong>'.$msgSucc.'</strong></p><a href="#" class="close">close</a></div>')?>
                            <div class="grid_12 boxed-table">
								<div class="box grid_5">
									<header>
										<div class="inner">
											<div class="left title">
												<h1>Suspend Accounts</h1>
											</div>
										</div>
									</header>
								
									<div class="box-content">
										<form method="POST" class="validate">
											<div class="field fullwidth">
												<label for="textarea-input-grow">Reason:</label>
												<textarea id="textarea-input-grow" name="dea_reason" class="autogrow required"></textarea>
											</div>
											<footer class="pane">
												<input type="submit" class="bt blue" value="Suspend" />
											</footer>
										</form>
									</div>
								</div>
								<div class="grid_6"><div class="box">
									<header>
										<div class="inner">
											<div class="left title">
												<h1>Account (<?=number_format($total_pages)?>)</h1>
											</div>
										</div>
									</header>
								
									<div class="box-content no-inner-space">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Player</th>
                                                    <th>User ID</th>
                                                </tr>
                                            </thead>
                                            <tbody>
												<?php
													foreach ($players as $player){
												?>
                                                <tr>
                                                    <td class="center"><a href="<?=$config['base_url']?>?side=game_panel/player&amp;id=<?=$player['pid']?>"><?=($player['level'] == 0 ? '<del>'.$player['name'].'</del>' : $player['name'])?></a></td>
                                                    <td class="center"><a href="<?=$config['base_url']?>?side=game_panel/user&amp;id=<?=$player['id']?>">#<?=$player['id']?></a></td>
                                                </tr>
												<?}?>
                                            </tbody>
                                        </table>
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
								</div></div>
                            </div>
                        </div>
<?
}else{
	$pagina = $_GET['p'];
	$limita = 20;
	if(is_numeric($pagina)){
		$start = ($pagina-1)*$limita;
	}else{
		$start = 0;
	}

	if(isset($_GET['all'])){
		$total_pages = $db->QueryGetNumRows("SELECT id, COUNT(*) AS total_accounts FROM `[users]` WHERE IP_last != '0' GROUP BY IP_last HAVING total_accounts > '1'");
		include('lib/paginare.php');

		$jucatori = $db->QueryFetchArrayAll("SELECT IP_last, COUNT(*) AS total_accounts FROM `[users]` WHERE IP_last != '0' GROUP BY IP_last HAVING total_accounts > '1' ORDER BY total_accounts DESC LIMIT ".$start.",".$limita."");
	}else{
		$total_pages = $db->QueryGetNumRows("SELECT a.IP_last, COUNT(a.id) AS total_accounts FROM `[users]` a LEFT JOIN `[players]` b ON b.userid = a.id WHERE a.IP_last != '0' AND (b.health > 0 AND b.level > 0) GROUP BY a.IP_last HAVING total_accounts > '1'");
		include('lib/paginare.php');

		$jucatori = $db->QueryFetchArrayAll("SELECT a.IP_last, COUNT(a.id) AS total_accounts FROM `[users]` a LEFT JOIN `[players]` b ON b.userid = a.id WHERE a.IP_last != '0' AND (b.health > 0 AND b.level > 0) GROUP BY a.IP_last HAVING total_accounts > '1' ORDER BY total_accounts DESC LIMIT ".$start.",".$limita."");
	}
?>
                        <div class="main-box">
                            <header class="grid_12 head">
                                <h1>Users</h1>
                                <span class="divider"></span>
                            </header>
                            <div class="grid_12 boxed-table">
                                <div class="box">
                                    <header>
                                        <div class="inner">
                                            <div class="left title">
                                                <h1>Users</h1>
                                            </div><div class="right title">
                                                <?=(!isset($_GET['all']) ? '<a href="index.php?page=multi_account&all">All accounts</a>' : '<a href="index.php?page=multi_account">Only active accounts</a>')?>
                                            </div>
                                        </div>
                                    </header>

                                    <div class="box-content no-inner-space">
                                        <table>
                                            <thead>
                                                <tr>
													<th>#</th>
                                                    <th>IP Address</th>
													<th>Total Accounts</th>
                                                    <th class="sorting_disabled">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
	$j = 0;
	foreach($jucatori as $jucator):
		++$j;
?>
                                                <tr>
													<td><?=$j?></td>
                                                    <td><?=$jucator['IP_last']?></td>
													<td><?=number_format($jucator['total_accounts'])?> players</b> used this IP</td>
													<td class="center">
														<a href="/game/?side=game_panel/ip_lookup&ip=<?=$jucator['IP_last']?>" target="_blank" class="tooltip glyph number-list" title="See accounts"></a> | 
														<a href="index.php?page=multi_account&ban=<?=$jucator['IP_last']?>" class="tooltip glyph zoom-out" title="Suspend accounts"></a>
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
                        </div>
<?}?>
					</div>
                </div>
            </section>
        </div>
<?
include("footer.php");
?>