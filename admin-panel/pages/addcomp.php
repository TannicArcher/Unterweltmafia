<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	if (isset($_GET['d']))
	{
		$cid = $db->EscapeString($_GET['d']);
		$db->Query("UPDATE `businesses` SET `active`='0' WHERE `id`='".$cid."'");
		
		$msgSucc = 'Successfully deleted!';
	}
	elseif (isset($_POST['new_title']))
	{
		$title = $db->EscapeString($_POST['new_title']);
		$type = $db->EscapeString($_POST['new_type']);
		$city = $db->EscapeString($_POST['new_city']);
		$bank = $db->EscapeString($_POST['new_bank']);
		$boss = $db->EscapeString($_POST['new_boss']);
		$text = $db->EscapeString($_POST['new_text']);
		
		if (View::Length($title) < 3 || View::Length($title) > 30)
		{
			$msgErr = 'Titel muss zwischen 3 und 30 Zeichen haben!';
		}
		elseif ($type == '' || $city == '')
		{
			$msgErr = 'Bitte fülle alle Felder aus.';
		}
		elseif ($bank < 0)
		{
			$msgErr = 'Bitte mindestens 1$ eingeben.';
		}
		elseif ($boss < 1)
		{
			$msgErr = 'Bitte Spieler ID eingeben.';
		}
		elseif ($text != '' && View::Length($text) < 15)
		{
			$msgErr = 'Die Beschreibung muss mindestens 15 Zeichen enthalten.';
		}
		else
		{
			$db->Query("INSERT INTO `businesses` (`type`, `place`, `name`, `job_1`, `bank`, `info`, `created`)VALUES('".$type."', '".$city."', '".$title."', '".$boss."', '".$bank."', '".$text."', '".time()."')");
			
			$msgSucc = 'Erfolgreich hinzugefügt!';
		}
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
            </header><?if($msgErr != ''){?>
			<div class="alert red air">
                <p><strong><?=$msgErr?></strong></p>
                     <a href="#" class="close">schließen</a>
            </div><?}elseif($msgSucc != ''){?>
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
                                    <li><a href="#">Firmen</a></li>
                                </ul>
                            </nav>
                        </div>

                        <div class="main-box">

                            <!-- # Regular tables -->
                            <header class="grid_12 head">
                                <h1>Firmen</h1>
                                <span class="divider"></span>
                            </header>

                            <div class="box grid_6">
                                <header>
                                    <div class="inner">
                                        <div class="left title">
                                            <h1>Firma hinzufügen</h1>
                                        </div>
										<div class="right">
                                            <a href="#" class="close">schließen</a>
                                        </div>
                                    </div>
                                </header>
                                <div class="box-content">
									<form method="post" action="" class="validate">
										<div class="field fullwidth">
											<label for="text-input-normal">Name:</label>
											<input type="text" class="required" name="new_title" id="text-input-normal" value="<?=stripslashes($_POST['new_title'])?>" />
										</div>
										<div class="field fullwidth">
											<label for="select">Art:</label>
											<select name="new_type" id="select"><?php foreach($config['business_types'] as $key => $value){ echo '<option value="'.$key.'">'.$value['name'][2].'</option>'; } ?></select>
										</div>
										<div class="field fullwidth">
											<label for="select">Stadt:</label>
											<select name="new_city" id="select"><?php foreach($config['places'] as $key => $value){ echo '<option value="'.$key.'">'.$value[0].'</option>'; } ?></select>
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">Bank (Geld):</label>
											<input type="text" class="required" name="new_bank" id="text-input-normal" min="0" value="<?=($_POST['new_bank'] != '' ? stripslashes($_POST['new_bank']) : '0')?>" />
										</div>
										<div class="field fullwidth">
											<label for="text-input-normal">Direktor (Spieler ID):</label>
											<input type="text" class="required" name="new_boss" id="text-input-normal" min="1" value="<?=($_POST['new_boss'] != '' ? stripslashes($_POST['new_boss']) : '1')?>" />
										</div>
										<div class="field fullwidth">
											<label for="textarea-input-normal">Beschreibung:</label>
											<textarea name="new_text" id="textarea-input-normal"><?=stripslashes($_POST['new_text'])?></textarea>
										</div>
										<footer class="pane">
											<input type="submit" name="submit" class="bt blue" value="Hinzufügen" />
										</footer>
									</form>
                                </div>
                            </div>
							<div class="box grid_6 boxed-table">
                                <header>
                                    <div class="inner">
                                        <div class="left title">
                                            <h1>Firmen</h1>
                                        </div>
										<div class="right">
                                            <a href="#" class="close">schließen</a>
                                        </div>
                                    </div>
                                </header>
                                <div class="box-content no-inner-space">
									<?php
			$sql = $db->Query("SELECT id,type,name FROM `businesses` WHERE `active`='1' ORDER BY created DESC");
			$news = $db->FetchArrayAll($sql);
			
			if (count($news) <= 0)
			{
				echo '<p>Nichts gefunden!</p>';
			}
			else
			{
			?>
            <table>
				<thead>
					<tr>
						<th>Name</th>
						<th>Art</th>
						<th>Aktionen</th>
					</tr>
				</thead>
				<tbody>
           	<?php
			foreach ($news as $n){
			?>
					<tr>
						<td><a href="<?=$config['base_url']?>?side=firma/firma&amp;id=<?=$n['id']?>" target="_blank"><?=View::NoHTML($n['name'])?></a></td>
						<td><?=$config['business_types'][$n['type']]['name'][2]?></td>
						<td><a href="?page=<?=$_GET['page']?>&amp;d=<?=$n['id']?>" onclick="return confirm('Are you sure?');">Löschen</a></td>
					</tr>
			<?}?>
				</tbody>
			</table>
            <?php
			}
			?>
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