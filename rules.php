<?php
	define('BASEPATH', true);
	require_once('system/config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="<?=$config['meta_keywords']?>" />
	<meta name="description" content="<?=$config['meta_description']?>" />
	<title>Spielregeln &bull; <?=$admin_config['game_name']['value']?></title>
	<link rel="icon" href="game/favicon.png" type="image/png" />
	<link href="css.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="default_wrap">
		<div class="default_header"></div>
        <div class="terms_wrap">
        	<h1>Spielregeln <a href="<?=$config['base_url']?>" style="float: right">&laquo; <?=$admin_config['game_name']['value']?></a></h1>
            <p class="last_update">Letztes Update: 18 Jun 2019</p>
			<p><font color=red>The rules in English -> scroll down.</font></p>
	
            <p>Um um das Spiel Untwerweltmafia spielen zu dürfen,musst du diese Regeln lesen und akzeptieren, ansonsten musst du das Spiel verlassen.</p>
            <div class="section">
            	<h2>1 &raquo; Spielverhalten</h2>
                <ol>
                	<li><span>Diskriminierung, Beleidigung oder ähnliches der anderen Spieler ist strengstens untersagt. Bitte bewahre einen anständigen Umgangston miteinander.</span></li>
                    <li><span>Spamen ist strengstens verboten.</span></li>
                </ol>
            </div>
            <div class="section">
            	<h2>2 &raquo; Allgemeine Regeln</h2>
                <ol>
                	<li><span>Jeder Spieler darf maximal 1 Account besitzen.</span></li>
                	<li><span>Das Ausnutzen möglicher Fehler im Spiel ist strengstens untersagt. Bitte melde gefundene Fehler im Spiel den Administratoren!</span></li>
					<li><span>Das weitergeben seines Accounts an andere Spieler/Personen ist untersagt.</span></li>
                </ol>
            </div>
            <div class="section">
            	<h2>3 &raquo; Spenden</h2>
                <p>Solltest du im Spiel etwas spenden, erklärst du dich mit den damit verbundenen Bedingungen einverstanden.</p>
                <ol>
                	<li><span>Spenden können unter keinen Umständen rückerstattet werden.</span></li>
                </ol>
            </div>
            <div class="section">
            	<h2>4 &raquo; Regeln Forum</h2>
                <p>Alle Regeln unter <b>Spielverhalten</b> finden in den Spielforen die gleiche Anwendung.</p>
                <ol>
                	<li><span>Bitte sei freundlich, nett und achte auf deinen Umgangston.</span></li>
                </ol>
            </div>
			<div class="section">
            	<h2>5 &raquo; Chat Regeln</h2>
                <ol>
                	<li><span>Werbung jeglicher Art ist strengstens untersagt.</span></li>
                </ol>
            </div>
            <div class="section">
            	<h2>6 &raquo; Andere Regeln</h2>
                <ol>
                	<li><span>Wir behalten uns das Recht vor, wichtige Ankündigungen oder ähnliches an deine E-Mail-Adresse zu senden.</span></li>
                </ol>
            </div>
        
		            <br><br><br>
					<h1>Rules</h1>
					<p>To play our game you have to read and accept this rules, else you have to leave this game.</p>
            <div class="section">
            	<h2>1 &raquo; Game play</h2>
                <ol>
                	<li><span>Discrimination and insults to the other players is strictly forbidden. Please keep a decent language.</span></li>
                    <li><span>Spam is strictly forbidden.</span></li>
                </ol>
            </div>
            <div class="section">
            	<h2>2 &raquo; General rules</h2>
                <ol>
                	<li><span>Every player can have maximum 1 account.</span></li>
                	<li><span>Benefiting from potential errors is strictly prohibited. Please report any errors found in game!</span></li>
                </ol>
            </div>
            <div class="section">
            	<h2>3 &raquo; Rules for paid things</h2>
                <p>When buying coins in the game you agree to these terms.</p>
                <ol>
                	<li><span>Irrespective of the amount used to purchase credits, we don't make refunds.</span></li>
                </ol>
            </div>
            <div class="section">
            	<h2>4 &raquo; Forum rules</h2>
                <p>All rules from <b>Game Play</b> are applied in the same way on forum</p>
                <ol>
                	<li><span>Please keep a decent language.</span></li>
                </ol>
            </div>
			<div class="section">
            	<h2>5 &raquo; Chat Rules</h2>
                <ol>
                	<li><span>It is strictly forbidden to advertise any kind.</span></li>
                </ol>
            </div>
            <div class="section">
            	<h2>6 &raquo; Other Rules</h2>
                <ol>
                	<li><span>We reserve the right to send newsletters to your email address.</span></li>
                </ol>
            </div>
		
		
		</div>
    </div>


	
</body>
</html>