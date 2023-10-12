<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	if (isset($_GET['d']))
	{
		$news = $db->EscapeString($_GET['d']);
		$db->Query("UPDATE `news` SET `deleted`='1' WHERE `id`='".$news."'");
		
		$msgSucc = 'Erfolgreich gelöscht!';
	}
	elseif (isset($_POST['new_title']))
	{
		$title = $db->EscapeString($_POST['new_title']);
		$text = $db->EscapeString($_POST['new_text']);
		
		if (View::Length($title) < 5)
		{
			$msgErr = 'Titel muss mindestens 5 Zeichen haben!';
		}
		elseif (View::Length($text) < 20)
		{
			$msgErr = 'Die Nachricht muss aus mindestens 20 Zeichen bestehen.';
		}
		else
		{
			$db->Query("INSERT INTO `news` (`author`, `title`, `text`, `added`, `comments`)VALUES('".Player::Data('id')."', '".trim($title)."', '".$text."', '".time()."', 'a:0:{}')");
			
			$msgSucc = 'Nachricht erfolgreich hinzugefügt!';
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
                                    <li><a href="#">News</a></li>
                                </ul>
                            </nav>
                        </div>

                        <div class="main-box">

                            <!-- # Regular tables -->
                            <header class="grid_12 head">
                                <h1>News</h1>
                                <span class="divider"></span>
                            </header>

                            <div class="box grid_6">
                                <header>
                                    <div class="inner">
                                        <div class="left title">
                                            <h1>News hinzufügen</h1>
                                        </div>
										<div class="right">
                                            <a href="#" class="close">schließen</a>
                                        </div>
                                    </div>
                                </header>
                                <div class="box-content">
									<form method="post" action="" class="validate">
										<div class="field fullwidth">
											<label for="text-input-normal">Titel:</label>
											<input type="text" class="required" name="new_title" id="text-input-normal" value="<?=stripslashes($_POST['new_title'])?>" />
										</div>
										<div class="field fullwidth">
											<label for="textarea-input-normal">Nachricht</label>
											<textarea name="new_text" class="required" id="textarea-input-normal"><?=stripslashes($_POST['new_text'])?></textarea>
										</div>
										<footer class="pane">
											<input type="submit" name="submit" class="bt blue" value="Speichern" />
										</footer>
									</form>
                                </div>
                            </div>
							<div class="box grid_6 boxed-table">
                                <header>
                                    <div class="inner">
                                        <div class="left title">
                                            <h1>News</h1>
                                        </div>
										<div class="right">
                                            <a href="#" class="close">schließen</a>
                                        </div>
                                    </div>
                                </header>
                                <div class="box-content no-inner-space">
									<?php
			$sql = $db->Query("SELECT id,title FROM `news` WHERE `deleted`='0' ORDER BY id DESC");
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
						<th>Titel</th>
						<th>Aktionen</th>
					</tr>
				</thead>
				<tbody>
           	<?php
			foreach ($news as $n){
			?>
					<tr>
						<td><a href="<?=$config['base_url']?>?side=stiri&amp;id=<?=$n['id']?>" target="_blank"><?=View::NoHTML($n['title'])?></a></td>
						<td><a href="?page=<?=$_GET['page']?>&amp;d=<?=$n['id']?>">Löschen</a></td>
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