<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
if (isset($_POST['login'])) {
	$login = $db->EscapeString($_POST['username']);
	
	$sql = "SELECT `id`,`pass` FROM `[users]` WHERE `userlevel`='4' AND ";
	if (is_numeric($login))
	{
		$sql .= "`id`='$login'";
	}
	elseif (strstr($login, '@'))
	{
		$sql .= "`email`='$login'";
	}
	else
	{
		$q = $db->Query("SELECT userid FROM `[players]` WHERE `name`='$login'");
		$player = $db->FetchArray($q);
		$sql .= "`id`='".$player['userid']."'";
	}
	
	$sql  = $db->Query($sql);
	$user = $db->FetchArray($sql);
	
	$pass = View::DoubleSalt($db->EscapeString($_POST['password']), $user['id']);
	
	if ($user['pass'] === $pass)
	{
		$userid = $user['id'];
		
		$sql = $db->Query("SELECT id FROM `[sessions]` WHERE `Userid`='" . $user['id'] . "' AND `IP`='" . $_SERVER['REMOTE_ADDR'] . "' AND `Active`='1'");
		while ($sess = $db->FetchArray($sql))
		{
			$db->Query("DELETE FROM `[sessions]` WHERE `id`='" . $sess['id'] . "'");
		}
		
		mysql_query("INSERT INTO `[sessions]` (`Userid`, `Time_start`, `Last_updated`, `IP`, `User_agent`)VALUES('".$user['id']."', '".time()."', '".time()."', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."')") or die(mysql_error());
		$sid = mysql_insert_id();
		$db->Query("UPDATE `[users]` SET `online`='".time()."', `last_active`='".time()."', `IP_last`='".$_SERVER['REMOTE_ADDR']."' WHERE `id`='$userid'");
		$db->Query("UPDATE `[players]` SET `last_active`='".time()."', `online`='".time()."', `status`='1' WHERE `userid`='".$user['id']."' AND `health`>'0' AND `level`>'0'");
		
		$_SESSION['MZ_LoginData'] = array(
			'sid' => $sid,
			'userid' => $user['id'],
			'password' => $user['pass']
		);
		
		header("Location: index.php");
		exit;
	}
	else
	{
		$fail = 1;
		$errorMsg2 = 'Error!';
	}
}
?>
<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="UTF-8" />
        <title>Kontrollbereich</title>
        
        <!-- CSSs -->
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/960.css" />
        <link rel="stylesheet" href="css/icons.css">
        <link rel="stylesheet" href="css/tipsy.css">
        <link rel="stylesheet" href="css/formalize.css">
        <link rel="stylesheet" href="css/prettyPhoto.css">
        <link rel="stylesheet" href="css/jquery-ui-1.8.18.custom.css">
        <link rel="stylesheet" href="css/chosen.css">
        <link rel="stylesheet" href="css/ui.spinner.css">
        <link rel="stylesheet" href="css/jquery.jqplot.min.css" />
        <link rel="stylesheet" href="css/fullcalendar.css" />
        <link rel="stylesheet" href="css/jquery.miniColors.css" />
        <link rel="stylesheet" href="css/elrte.min.css" />
        <link rel="stylesheet" href="css/elfinder.css" />
        <link rel="stylesheet" href="css/main.css" />
        
        <!-- JAVASCRIPTs -->
        <!--[if lt IE 9]>
            <script language="javascript" type="text/javascript" src="js/jqPlot/excanvas.min.js"></script>
            <script language="javascript" type="text/javascript" src="js/html5shiv.js"></script>
        <![endif]-->
        <script src="js/jquery.js"></script>
        <script src="js/jquery-ui-1.8.18.custom.min.js"></script>

        <script src="js/jquery.tipsy.js"></script>
        <script src="js/jquery.formalize.min.js"></script>
        <script src="js/jquery.modal.js"></script>
        <script src="js/prefixfree.min.js"></script>
        <script src="js/datables/js/jquery.dataTables.min.js"></script>
        <script src="js/jquery.prettyPhoto.js"></script>

        <script src="js/jquery.autogrowtextarea.js"></script>
        <script src="js/jquery.easing.1.3.js"></script>
        <script src="js/jquery.fileinput.js"></script>
        <script src="js/chosen.jquery.min.js"></script>
        <script src="js/ui.checkBox.js"></script>
        <script src="js/ui.spinner.min.js"></script>

        <script src="js/jquery.loading.js"></script>
        <script src="js/jquery.path.js"></script>
        <script src="js/jqPlot/jquery.jqplot.min.js"></script>
        <script src="js/jqPlot/plugins/jqplot.pieRenderer.min.js"></script>
        <script src="js/jqPlot/plugins/jqplot.cursor.min.js"></script>
        <!-- # -->

        <script src="js/jqPlot/plugins/jqplot.highlighter.min.js"></script>
        <script src="js/jqPlot/plugins/jqplot.dragable.min.js"></script>
        <script src="js/jqPlot/plugins/jqplot.dateAxisRenderer.min.js"></script>
        <script src="js/jqPlot/plugins/jqplot.ohlcRenderer.min.js"></script>
        <script src="js/jqPlot/plugins/jqplot.trendline.min.js"></script>
        <script src="js/jqPlot/plugins/jqplot.barRenderer.min.js"></script>

        <script src="js/jqPlot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
        <script src="js/jqPlot/plugins/jqplot.pointLabels.min.js"></script>
        <!-- /# -->
        <script src="js/fullcalendar.min.js"></script>
        <script src="js/jquery.miniColors.min.js"></script>
        <script src="js/jquery.maskedinput-1.3.min.js"></script>

        <script src="js/jquery-ui-timepicker-addon.js"></script>
        <script src="js/elrte.min.js"></script>
        <script src="js/elfinder.min.js"></script>
        <script src="js/jquery.validate.min.js"></script>
        <script src="js/jquery.metadata.js"></script>
        <script src="js/main.js"></script>
    </head>
    <body>
        <!-- show loading until the all page scripts are fully loaded and cached (use this only on login page) -->
        <div id="loading">
            <div class="inner">
                <div>
                    <div class="ajax-loader"></div>
                    <p>Lade<span>...</span></p>
                </div>
            </div>
        </div>
        <script>document.getElementById('loading').style.display = 'block';</script>

        <!-- wrapper -->
        <div id="wrapper">

            <section id="main">
                <div class="container_12">
                    <div id="content" class="compact-page">
                        <div class="min">
                            <div id="logo">
                                <img src="img/logo.png" alt="logo" />
                            </div>
                            <div class="main-box">
                                <form action="" method="POST">
                                    <header class="head">
									<? if($fail == 1){?>
									<div class="alert red air">
                                        <p><strong><?=$errorMsg2?></strong></p>
                                        <a class="close" href="#">schließen</a>
                                    </div><?}?>
                                        <h1>Anmelden</h1>
                                        
                                        <div class="alignright">
                                            <div class="note small">
                                                <input id="ck" type="checkbox" /> <label for="ck">Merke mich</label>
                                            </div>
                                        </div>
                                        <span class="divider"></span>
                                    </header>
                                    <div class="field fullwidth">
                                        <input type="text" name="username" placeholder="Login" data-icon="user" />
                                    </div>
                                    <div class="field fullwidth">
                                        <input type="password" name="password" placeholder="Password" data-icon="closed-lock" />
                                    </div>

                                    <span class="divider"></span>

                                    <div class="field fullwidth last">
                                        <input type="submit" name="login" class="bt blue large" value="Anmelden" />
                                    </div>
                                </form>
                            </div>
                            <div class="extension-wrap-center">
                                <div class="extension bottom menu">
                                    <nav>
                                        <ul>
                                            <li><a href="<?=$config['base_url']?>?side=recover">Passwort vergessen?</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- /wrapper -->
    </body>
</html>
