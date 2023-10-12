<?php
	define('BASEPATH', true);
	require('system/config.php');

	if($_GET['e'] != '' && is_numeric($_GET['e'])){$ref_id = $db->EscapeString($_GET['e']); $_SESSION['NGRefCookie'] = $ref_id;}elseif(isset($_SESSION['NGRefCookie'])){$ref_id = $_SESSION['NGRefCookie'];}else{$ref_id = 0;}		

	if($config['affiliate_module']){
		if(!empty($_GET['aff']) && is_numeric($_GET['aff'])){
			$aff_id = $db->EscapeString($_GET['aff']); 
			$_SESSION['NGACookie'] = $aff_id;
		}elseif(isset($_SESSION['NGACookie'])){
			$aff_id = $_SESSION['NGACookie'];
		}else{
			$aff_id = 0;
		}		
	}

	$orign = empty($_GET['orign']) ? 'game/?side=startside' : $_GET['orign'];
	if (IS_ONLINE)
	{
		header("Location: " . $orign);
		exit;
	}
	
	$tSource = $_SERVER['HTTP_REFERER'];
	if(!empty($tSource)){
		$main_domain = parse_url($config['base_url']);
		$http_referer = parse_url($tSource);
		if($http_referer['host'] != $main_domain['host']){
			setcookie('refSource', $db->EscapeString($tSource), time()+60*60*24, '/');
		}
	}

	$sider = array(
		'login' => 'login',
		'signup' => 'signup',
		'recover' => 'recover',
		'contact' => 'contact'
	);
	$side = $sider[$_GET['side']];

	if (isset($_COOKIE['MZ_Language']) && $languages_supported[$_COOKIE['MZ_Language']])
	{
		$langBase_lang = $_COOKIE['MZ_Language'];
	}
	
	if (isset($_GET['setLang']) && $languages_supported[$_GET['setLang']])
	{
		$lang = $languages_supported[$_GET['setLang']];
		$langBase->language = $lang[0];
		setcookie('MZ_Language', $langBase->language);
	}
	
	if (isset($_POST['do_login']))
	{
		$login = $db->EscapeString($_POST['login']);
		
		$sql = "SELECT `id`,`pass` FROM `[users]` WHERE ";
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
			$player = $db->QueryFetchArray("SELECT userid FROM `[players]` WHERE `name`='$login'");
			$sql .= "`id`='".$player['userid']."'";
		}
		
		$user  = $db->QueryFetchArray($sql);
		$pass = View::DoubleSalt($db->EscapeString($_POST['password']), $user['id']);
		
		if ($user['pass'] === $pass)
		{
			$userid = $user['id'];
			
			$sql = $db->Query("SELECT id FROM `[sessions]` WHERE `Userid`='" . $user['id'] . "' AND `IP`='" . $_SERVER['REMOTE_ADDR'] . "' AND `Active`='1'");
			while ($sess = $db->FetchArray($sql))
			{
				$db->Query("INSERT INTO `" . $config['sql_logdb'] . "`.`[sessions]` SELECT * FROM `[sessions]` WHERE `id`='" . $sess['id'] . "'");
				$db->Query("UPDATE `" . $config['sql_logdb'] . "`.`[sessions]` SET `Active`='0' WHERE `id`='".$sess['id']."'");
				$db->Query("DELETE FROM `[sessions]` WHERE `id`='" . $sess['id'] . "'");
			}
			
			$db->Query("INSERT INTO `[sessions]` (`Userid`, `Time_start`, `Last_updated`, `IP`, `User_agent`)VALUES('".$user['id']."', '".time()."', '".time()."', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."')");
			$sid = $db->GetLastInsertId();
			$db->Query("UPDATE `[users]` SET `online`='".time()."', `last_active`='".time()."', `IP_last`='".$_SERVER['REMOTE_ADDR']."' WHERE `id`='$userid'");
			$db->Query("UPDATE `[players]` SET `last_active`='".time()."', `online`='".time()."', `status`='1' WHERE `userid`='".$user['id']."' AND `health`>'0' AND `level`>'0'");
			
			$_SESSION['MZ_LoginData'] = array(
				'sid' => $sid,
				'userid' => $user['id'],
				'password' => $user['pass']
			);
			
			echo $sid.' - '.$user['id'];
			
			/* Multi-account cookie protection system */
			setcookie('NGRegistered', $user['id'], time()+2592000, '/');
			
			header("Location: $orign" . '&sid=' . $sid);
			exit;
		}
		else
		{
			$errorMsg2 = $langBase->get('home-03');
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="ro">
<head><title><?=$admin_config['game_name']['value']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?if(isset($_GET['aff']) || isset($_GET['e'])){?><meta name="robots" content="noindex, nofollow"><?}?>
<meta name="keywords" content="<?=$config['meta_keywords']?>" />
<meta name="description" content="<?=$config['meta_description']?>" />
<link rel="shortcut icon" href="<?=$config['base_url']?>favicon.ico" />
<link rel="stylesheet" type="text/css" href="<?=$config['base_url']?>css/index.css" />
<link rel="stylesheet" type="text/css" href="<?=$config['base_url']?>css/style.css" />
<script type="text/javascript" src="<?=$config['base_url']?>game/js/mootools.js"></script>
<script type="text/javascript">var js_mootools = (new Date).getTime();</script>
<script type="text/javascript" src="<?=$config['base_url']?>js.js"></script>
<link rel="stylesheet" type="text/css" href="<?=$config['base_url']?>fancybox/jquery.fancybox-1.3.4.css" />
<script type="text/javascript" src="<?=$config['base_url']?>fancybox/jquery.min.js"></script>
<script type="text/javascript" src="<?=$config['base_url']?>fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<?=$config['base_url']?>fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript">
	<!--
	var js_start = (new Date).getTime();
	var server_time = <?=time()?>;
	window.orign = "/game/?side=startside";
	-->
	<!--
	$.noConflict();
	jQuery(document).ready(function()
	{
		jQuery("a[rel=previewImages]").fancybox({
			'transitionIn' : 'none',
			'transitionOut' : 'none',
			'titlePosition' : 'over',
			'titleFormat' : function(title, currentArray, currentIndex, currentOpts) {
				return '<span id="fancybox-title-over"><span style="color: #555555;">Imagine ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? '</span> &nbsp; ' + title : '') + '</span>';
			}
		});
		jQuery("a[rel=previewTrailer]").fancybox({
			'transitionIn' : 'none',
			'transitionOut' : 'none'
		});
	});
	-->

function goSelect(selectobj){
 window.location.href='<?=$config['base_url']?>?setLang='+selectobj
}
</script>
</head>
<body>
	<div id="default_top">
    	<div class="default_wrapper">
        	<ul class="right" style="float: left !important;">
            	<li><b><?=$langBase->get('ot-lang')?>:</b></li>
				<li style="margin-top:-2px;"><select onChange="goSelect(this.value)"><option value="RO"<?=($langBase_lang == "RO" || $_GET['setLang'] == "RO" ? ' selected' : '')?>>Deutsch</option><option value="EN"<?=($langBase_lang == "EN" || $_GET['setLang'] == "EN" ? ' selected' : '')?>>English</option><option value="FR"<?=($langBase_lang == "FR" || $_GET['setLang'] == "FR" ? ' selected' : '')?>>Fran&ccedil;ais</option></select></li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
    <div class="default_wrapper" style="margin-top: -18px;">
    	<div id="default_left">

        	<div class="user_info">
            	<div class="profileimage">
                	<center><a href="<?=$config['base_url']?>"><img src="<?=$config['base_url']?>/img/logo.png" border="0" title="<?=$admin_config['game_name']['value']?>" /></a></center>
                </div>
                <span id="au_status" class="medium dark"></span>
            </div>
            <div class="default_menu">
            	<h1><?=$admin_config['game_name']['value']?></h1>
                <ul>
                    <li><a href="<?=$config['base_url']?>rules.php" target="_blank"><?=$langBase->get('header-05')?></a></li>
					<?php if($config['affiliate_module']){ ?><li><a href="<?=$config['base_url']?>affiliates" target="_blank"><?=$langBase->get('header-13')?></a></li><?php } ?>
					<li><a href="<?=$config['base_url']?>?side=contact"><?=$langBase->get('header-08')?></a></li>
                </ul>
            </div>
            <div class="default_menu">
            	<h1><?=$langBase->get('header-09')?></h1>
                <ul>

                	<li><a href="<?=$config['base_url']?>?side=login"><?=$langBase->get('header-10')?></a></li>
                    <li><a href="<?=$config['base_url']?>?side=signup"><?=$langBase->get('header-11')?></a></li>
                    <li><a href="<?=$config['base_url']?>?side=recover"><?=$langBase->get('header-12')?></a></li>
                </ul>
            </div>
        </div>

        <div id="default_right">
        	<div id="map_container">
            	<div id="map" class="map_blur">
                	<div id="map_contents">
                    	<form method="post" action="">
							<p class="form_item">
								<span class="title"><?=$langBase->get('home-01')?></span><br />
								<input type="text" name="login" value="" />
							</p>
							<p class="form_item">
								<span class="title"><?=$langBase->get('home-02')?></span><br />
								<input type="password" name="password" value="" />
							</p>
                            <?php if (isset($errorMsg2)) echo '<p class="resultMsg">' . $errorMsg2 . '</p>';?> 
							<p class="buttons">
								<input type="submit" name="do_login" class="first" value="<?=$langBase->get('header-10')?>" />
							</p>
                            <div class="clear"></div>
						</form>
                    </div>
					<div id="map_c_right">
						<center><b style="font-size:16px;"><?=$langBase->get('txt-51', array('-GAME-' => $admin_config['game_name']['value']))?></b><br /><br />
						<?=$langBase->get('home-21', array('-GAME-' => $admin_config['game_name']['value']))?></center><br />
					</div>
                    <ul id="map_navigation">
                    	<li><a href="<?=$config['base_url']?>?side=login" <? if($side == 'login' || $side == ''){?>class="active"<?}?>><img src="<?=$config['base_url']?>images/icons/connect.png" alt="" title="Login" /><?=$langBase->get('header-10')?></a></li>
                        <li><a href="<?=$config['base_url']?>?side=signup" <? if($side == 'signup'){?>class="active"<?}?>><img src="<?=$config['base_url']?>images/icons/user_add.png" alt="" title="Signup" /><?=$langBase->get('header-11')?></a></li>
                        <li><a href="<?=$config['base_url']?>?side=recover" <? if($side == 'recover'){?>class="active"<?}?>><img src="<?=$config['base_url']?>images/icons/key.png" alt="" title="Recover password" /><?=$langBase->get('header-12')?></a></li>
                    </ul>
                </div>
            </div>
            <div id="default_content">
			<?php
				if ($side == 'login')
				{

					if (isset($_POST['do_login']))
					{
						$login = $db->EscapeString($_POST['login']);
						
						$sql = "SELECT `id`,`pass` FROM `[users]` WHERE ";
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
							$player = $db->QueryFetchArray("SELECT userid FROM `[players]` WHERE `name`='$login'");
							$sql .= "`id`='".$player['userid']."'";
						}
						
						$user  = $db->QueryFetchArray($sql);
						$pass = View::DoubleSalt($db->EscapeString($_POST['password']), $user['id']);
						
						if ($user['pass'] === $pass)
						{
							$userid = $user['id'];
							
							$sql = $db->Query("SELECT id FROM `[sessions]` WHERE `Userid`='" . $user['id'] . "' AND `IP`='" . $_SERVER['REMOTE_ADDR'] . "' AND `Active`='1'");
							while ($sess = $db->FetchArray($sql))
							{
								$db->Query("INSERT INTO `" . $config['sql_logdb'] . "`.`[sessions]` SELECT * FROM `[sessions]` WHERE `id`='" . $sess['id'] . "'");
								$db->Query("UPDATE `" . $config['sql_logdb'] . "`.`[sessions]` SET `Active`='0' WHERE `id`='".$sess['id']."'");
								$db->Query("DELETE FROM `[sessions]` WHERE `id`='" . $sess['id'] . "'");
							}
							
							$db->Query("INSERT INTO `[sessions]` (`Userid`, `Time_start`, `Last_updated`, `IP`, `User_agent`)VALUES('".$user['id']."', '".time()."', '".time()."', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."')") or die(mysql_error());
							$sid = $db->GetLastInsertId();
							$db->Query("UPDATE `[users]` SET `online`='".time()."', `last_active`='".time()."', `IP_last`='".$_SERVER['REMOTE_ADDR']."' WHERE `id`='$userid'");
							$db->Query("UPDATE `[players]` SET `last_active`='".time()."', `online`='".time()."', `status`='1' WHERE `userid`='".$user['id']."' AND `health`>'0' AND `level`>'0'");
							
							$_SESSION['MZ_LoginData'] = array(
								'sid' => $sid,
								'userid' => $user['id'],
								'password' => $user['pass']
							);
							
							header("Location: $orign" . '&sid=' . $sid);
							exit;
						}
						else
						{
							$errorMsg = $langBase->get('home-03');
						}
					}
				?>
				<div id="main_content">
                	<div class="main_head">
                        <h1><img src="/images/icons/star.png" alt="" title="<?=$admin_config['game_name']['value']?>" class="icon" id="headerTitleIcon" /><a href="<?=$config['base_url']?>"><?=$admin_config['game_name']['value']?> &raquo; <?=$langBase->get('header-10')?></a></h1>

                        <ul class="right">
                            <li><?=$langBase->get('home-04')?><h2 id="server_time" class="small time"><?=date('H:i')?></h2></li>
                        </ul>
                    </div>
                    <div class="clear"></div>
					<?php if($successMsg) echo '<p class="yellow" align="center" style="font-size: 12px;">' . $successMsg . '</p>';?>
                    <?php if ($errorMsg) echo '<p class="error">' . $errorMsg . '</p>';?> 
					<div class="bg_c" style="width: 400px;">
					<h1 class="big"><?=$langBase->get('header-10')?></h1>
						<form method="post" action="">
							<dl class="form" style="margin-top: 0; padding-top: 0;">
								<dt><?=$langBase->get('home-01')?></dt>
								<dd><input type="text" name="login" id="login" class="styled" value="<?=$_POST['login']?>" /></dd>
								<dt><?=$langBase->get('home-02')?></dt>
								<dd><input type="password" name="password" class="styled" value="" /></dd>
							</p>
							</dl>
							<p class="clear" style="float:right;">
								<input type="submit" class="first" name="do_login" value="<?=$langBase->get('header-10')?>" />
							</p>
						</form>
					</div>
					<div class="clear"></div>
				<?php
				}
				elseif ($side == 'signup')
				{				

					$steps = array(
						1,
						2
					);
					$step = $_GET['step'];
					if (!in_array($step, $steps))
					{
						$step = $steps[0];
					}
					
					if ($step == 2 && !empty($_GET['v']))
					{
						$last_temp = $db->EscapeString($_GET['v']);
						$sql = $db->Query("SELECT id,time_added,expires,extra FROM `temporary` WHERE `id`='".$last_temp."' AND `active`='1' AND `area`='register'");
						$last_temp = $db->FetchArray($sql);
						
						$lt_expire = $last_temp['time_added']+$last_temp['expires'] - time();
						if ($lt_expire <= 0)
						{
							$db->Query("UPDATE `temporary` SET `active`='0' WHERE `id`='".$last_temp['id']."'");
							unset($last_temp);
						}
						
						if ($last_temp['id'] == '')
						{
							header('Location: /?side=signup&step=1');
							exit;
						}
						
						$extra = unserialize($last_temp['extra']);
						
						if (isset($_POST['reg_name']))
						{
							$name = trim($db->EscapeString($_POST['reg_name']));
							$name_validated = Accessories::ValidatePlayername($name);
							$pass = $db->EscapeString($_POST['reg_pass']);
							$pass_re = $db->EscapeString($_POST['reg_pass_re']);
							
							if ($pass !== $pass_re)
							{
								$errorMsg = $langBase->get('err-04');
							}
							elseif (!View::ValidPassword($pass))
							{
								$errorMsg = $langBase->get('err-03');
							}
							elseif ($name_validated == false)
							{
								$errorMsg = $langBase->get('home-05');
							}
							elseif ($db->QueryGetNumRows("SELECT id FROM `[users]` WHERE `IP_regged_with`='".$_SERVER['REMOTE_ADDR']."' LIMIT 1") > 0)
							{
								$errorMsg = $langBase->get('home-11');
							}
							elseif ($db->GetNumRows($db->Query("SELECT id FROM `[players]` WHERE `name`='".$name."'")) > 0)
							{
								$errorMsg = $langBase->get('home-06');
							}
							elseif(isset($_COOKIE['NGRegistered']) && $db->QueryGetNumRows("SELECT id FROM `[users]` WHERE `id`='".$db->EscapeString($_COOKIE['NGRegistered'])."' LIMIT 1") == 0)
							{
								$errorMsg = $langBase->get('home-11');
							}
							else
							{
								$db->Query("UPDATE `temporary` SET `active`='0' WHERE `id`='".$last_temp['id']."'");
					
								$refSource = 'default';
								if(isset($_COOKIE['refSource'])){
									$refSource = $db->EscapeString($_COOKIE['refSource']);
								}

								$db->Query("INSERT INTO `[users]` (`email`, `reg_time`, `IP_regged_with`, `hasPlayer`, `enlisted_by`, `online`, `last_active`, `IP_last`, `register_source`, `aff_id`)VALUES('".$extra['mail']."', '".time()."', '".$_SERVER['REMOTE_ADDR']."', '1', '".($extra['enlist'])."', '".time()."', '".time()."', '".$_SERVER['REMOTE_ADDR']."', '".$refSource."', '".$extra['affid']."')");
								$userID = $db->GetLastInsertId();
					
								if($config['affiliate_module'] && $extra['affid'] > 0){
									$aff_newreg = $admin_config['aff_newreg']['value'];
									$db->Query("UPDATE `[affiliates]` SET `balance`=`balance`+'".$aff_newreg."', `t_balance`=`t_balance`+'".$aff_newreg."' WHERE `id`='".$extra['affid']."'");
								}

								$saltPass = View::DoubleSalt($pass, $userID);
								$db->Query("UPDATE `[users]` SET `pass`='".$saltPass."' WHERE `id`='".$userID."'");
								$db->Query("INSERT INTO `[players]` (`userid`, `name`, `IP_created_with`, `created`, `cash`, `points`, `bank`, `live`, `profileimage`, `online`, `last_active`, `status`)VALUES('".$userID."', '".$name_validated."', '".$_SERVER['REMOTE_ADDR']."', '".time()."', '".$config['player_default_money']['cash']."', '".$config['player_default_coins']['coins']."', '".$config['player_default_money']['bank']."', '".array_rand($config['places'])."', '".$config['default_profileimage']."', '".time()."', '".time()."', '1')");
								$db->Query("INSERT INTO `[sessions]` (`Userid`, `Time_start`, `Last_updated`, `IP`, `User_agent`)VALUES('".$userID."', '".time()."', '".time()."', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."')");
								$sid = $db->GetLastInsertId();
								
								$_SESSION['MZ_LoginData'] = array(
									'userid' => $userID,
									'sid' => $sid,
									'password' => $saltPass
								);
													
								/* Multi-account cookie protection system */
								setcookie('NGRegistered', $userID, time()+2592000, '/');

								View::Message($langBase->get('home-07'), 1, true, '/game/?side=faq');
							}
						}
				?>
				<div id="main_content">
                	<div class="main_head">
                        <h1><img src="/images/icons/star.png" alt="" class="icon" id="headerTitleIcon" /><a href="<?=$config['base_url']?>"><?=$admin_config['game_name']['value']?> &raquo; Login</a></h1>

                        <ul class="right">
                            <li><?=$langBase->get('home-04')?><h2 id="server_time" class="small"><?=date('H:i')?></h2></li>
                        </ul>
                    </div>
                    <div class="clear"></div>
                	<?php if($successMsg) echo '<p class="yellow" style="margin: 0; font-size: 12px;">' . $successMsg . '</p>';?>
                    <?php if($errorMsg) echo '<p class="red" style="margin: 0; font-size: 12px;">' . $errorMsg . '</p>';?>
<div class="bg_c" style="width: 400px;">
	<h1 class="big"><?=$langBase->get('header-11')?> &raquo; <?=$langBase->get('home-23')?> 2/2</h1>
    <form method="post" action="">
        <dl class="form" style="margin-top: 0; padding-top: 0;">
            <dt><?=$langBase->get('home-13')?></dt>
            <dd><input type="text" class="styled" name="reg_name" value="<?=$_POST['reg_mail']?>" style="width: 200px;" /></dd>
            <dt><?=$langBase->get('txt-17')?></dt>
            <dd><input type="password" class="styled" name="reg_pass" value="" style="width: 200px;" /></dd>
			<dt><?=$langBase->get('txt-18')?></dt>
            <dd><input type="password" class="styled" name="reg_pass_re" value="" style="width: 200px;" /></dd>
        </dl>
        <p style="clear: both; margin: 0; padding-top: 15px; text-align: center;">
            <input type="submit" value="<?=$langBase->get('header-11')?>" />

        </p>
    </form>
</div>
                <?php
					}
					else
					{
						if (isset($_POST['n_register']))
						{
							$mail = trim($db->EscapeString($_POST['reg_mail']));
							$mail_re = trim($db->EscapeString($_POST['reg_mail_re']));
							
							if (!preg_match("/^[a-zA-Z_\\-][\\w\\.\\-_]*[a-zA-Z0-9_\\-]@[a-zA-Z0-9][\\w\\.-]*[a-zA-Z0-9]\\.[a-zA-Z][a-zA-Z\\.]*[a-zA-Z]$/i", $mail))
							{
								$errorMsg = $langBase->get('home-08');
							}
							elseif ($mail !== $mail_re)
							{
								$errorMsg = $langBase->get('home-09');
							}
							elseif ($db->GetNumRows($db->Query("SELECT id FROM `[users]` WHERE `email`='".$mail."' LIMIT 1")) > 0)
							{
								$errorMsg = $langBase->get('home-10');
							}
							elseif ($db->GetNumRows($db->Query("SELECT id FROM `[users]` WHERE `IP_regged_with`='".$_SERVER['REMOTE_ADDR']."' LIMIT 1")) > 0)
							{
								$errorMsg = $langBase->get('home-11');
							}
							elseif ($db->GetNumRows($db->Query("SELECT id FROM `temporary` WHERE `playerid`='".$mail."' AND `active`='1' AND `area`='register' AND `time_added`+`expires`>'".time()."' LIMIT 1")) > 0)
							{
								$errorMsg = 'You have already received an activation email!';
							}
							elseif (!isset($_POST['acc_tos']))
							{
								$errorMsg = $langBase->get('home-12');
							}
							else
							{
								$extra = array(
									'mail' => $mail,
									'enlist' => isset($_GET['e']) ? $db->EscapeString($_GET['e']) : $ref_id,
									'affid' => isset($_GET['aff']) ? $db->EscapeString($_GET['aff']) : $aff_id
								);
								$enlist_id = isset($_GET['e']) ? $db->EscapeString($_GET['e']) : $ref_id;
								
								$tempID = substr(sha1(uniqid(rand())), 0, 6);
								$db->Query("INSERT INTO `temporary` (`id`, `playerid`, `area`, `expires`, `time_added`, `extra`)VALUES('".$tempID."', '".$mail."', 'register', '3600', '".time()."', '".serialize($extra)."')");
								
								mail($mail, $admin_config['game_name']['value'].' » Signup', 
								'<html>
									<body style="font-family: Verdana; color: #333333; font-size: 12px;">
										<table style="width: 400px; margin: 0px auto;">
											<tr style="text-align: center;">
												<td style="border-bottom: solid 1px #cccccc;"><h1 style="margin: 0; font-size: 20px;"><a href="'.$config['base_url'].'">'.$admin_config['game_name']['value'].'</a></h1><h2 style="text-align: right; font-size: 14px; margin: 7px 0 10px 0;">&laquo; Signup</h2></td>
											</tr>
											<tr style="text-align: justify;">
												<td style="padding-top: 15px; padding-bottom: 15px;">
													'.$langBase->get('regis-02').',
													<br />
													<br />
													'.$langBase->get('regis-03').'<br />
													<a href="'.$config['base_url'].'?side=signup&amp;step=2&amp;v='.$tempID.'">'.$config['base_url'].'?side=signup&amp;step=2&amp;v='.$tempID.'</a>
													<br />
													'.$langBase->get('regis-04').' <b>'.View::Time(time()+3600, true).'</b>.
												</td>
											</tr>
											<tr style="text-align: right; color: #777777;">
												<td style="padding-top: 10px; border-top: solid 1px #cccccc;">
													'.$langBase->get('regis-05').',
													<br>
													<span style="color: #222222;">'.$admin_config['game_name']['value'].'</span>
												</td>
											</tr>
										</table>
									</body>
								</html>',
								"Reply-To: ".$admin_config['game_name']['value']." <".$admin_config['contact_email']['value'].">\r\n" . 
								"From: ".$admin_config['game_name']['value']." <".$admin_config['contact_email']['value'].">\r\n" .
								"MIME-Version: 1.0\r\n" .
								"Content-type: text/html; charset=iso-8859-1");

								$successMsg = $langBase->get('home-24');
							}
						}
				?>
                <div id="main_content">
                	<div class="main_head">
                        <h1><img src="/images/icons/star.png" alt="" class="icon" id="headerTitleIcon" /><a href="<?=$config['base_url']?>"><?=$admin_config['game_name']['value']?> &raquo; <?=$langBase->get('header-11')?></a></h1>

                        <ul class="right">
                            <li><?=$langBase->get('home-04')?><h2 id="server_time" class="small"><?=date('H:i')?></h2></li>
                        </ul>
                    </div>
                    <div class="clear"></div>
                    <?php if($successMsg) echo '<p class="yellow" align="center" style="font-size: 12px;">' . $successMsg . '</p>';?>
                    <?php if($errorMsg) echo '<p class="red" align="center" style="font-size: 12px;">' . $errorMsg . '</p>';?>
<div class="bg_c" style="width: 400px;">
	<h1 class="big"><?=$langBase->get('header-11')?> &raquo; <?=$langBase->get('home-23')?> 1/2</h1>
    <form method="post" action="">
        <dl class="form" style="margin-top: 0; padding-top: 0;">
            <dt><?=$langBase->get('home-14')?></dt>
            <dd><input type="text" class="styled" name="reg_mail" value="<?=$_POST['reg_mail']?>" style="width: 200px;" /></dd>
            <dt><?=$langBase->get('home-15')?></dt>
            <dd><input type="text" class="styled" name="reg_mail_re" value="" style="width: 200px;" /></dd>
			<dt></dt>
            <dd><input type="checkbox" class="styled" name="acc_tos" value="" /> <?=$langBase->get('home-16')?></dd>
        </dl>
        <p style="clear: both; margin: 0; padding-top: 15px; text-align: center;">
            <input type="submit" name="n_register" value="<?=$langBase->get('home-19')?>" />
        </p>
    </form>
</div>
<?php
					}
				}
				elseif ($side == 'recover')
				{
					
					$last_temp = $db->EscapeString($_GET['v']);
					$sql = $db->Query("SELECT id,time_added,expires,extra FROM `temporary` WHERE `id`='".$last_temp."' AND `active`='1' AND `area`='forgot_pass'");
					$last_temp = $db->FetchArray($sql);
					
					$lt_expire = $last_temp['time_added']+$last_temp['expires'] - time();
					if ($lt_expire <= 0)
					{
						$db->Query("UPDATE `temporary` SET `active`='0' WHERE `id`='".$last_temp['id']."'");
						unset($last_temp);
					}
					
					if ($last_temp['id'] == '')
					{
						if (isset($_POST['fp_mail']))
						{
							$mail = $db->EscapeString(trim($_POST['fp_mail']));
							$sql = $db->Query("SELECT id,email FROM `[users]` WHERE `email`='".$mail."'");
							$user = $db->FetchArray($sql);
							
							if ($user['id'] == '')
							{
								$errorMsg = $langBase->get('err-02');
							}
							elseif ($db->GetNumRows($db->Query("SELECT id FROM `temporary` WHERE `playerid`='".$mail."' AND `active`='1' AND `area`='forgot_pass' AND `time_added`+`expires`>'".time()."' LIMIT 1")) > 0)
							{
								$errorMsg = $langBase->get('home-17');
							}
							else
							{
								$extra = array(
									'mail' => $user['email']
								);
								
								$tempID = substr(sha1(uniqid(rand())), 0, 6);
								$db->Query("INSERT INTO `temporary` (`id`, `playerid`, `area`, `expires`, `time_added`, `extra`)VALUES('".$tempID."', '".$user['email']."', 'forgot_pass', '3600', '".time()."', '".serialize($extra)."')");
								
								mail($user['email'], $admin_config['game_name']['value'].' » Recover password', 
								'<html>
									<body style="font-family: Verdana; color: #333333; font-size: 12px;">
										<table style="width: 400px; margin: 0px auto;">
											<tr style="text-align: center;">
												<td style="border-bottom: solid 1px #cccccc;"><h1 style="margin: 0; font-size: 20px;"><a href="'.$config['base_url'].'">'.$admin_config['game_name']['value'].'</a></h1><h2 style="text-align: right; font-size: 14px; margin: 7px 0 10px 0;">&laquo; Recover Password</h2></td>
											</tr>
											<tr style="text-align: justify;">
												<td style="padding-top: 15px; padding-bottom: 15px;">
													Hello,
													<br />
													<br />
													Click bellow to get a new password:<br />
													<a href="'.$config['base_url'].'?side=recover&amp;v='.$tempID.'">'.$config['base_url'].'?side=recover&amp;v='.$tempID.'</a><br />
													<br />
													URL active until: <b>'.View::Time(time()+3600, true).'</b>
												</td>
											</tr>
											<tr style="text-align: right; color: #777777;">
												<td style="padding-top: 10px; border-top: solid 1px #cccccc;">
													Best Regards,
													<br />
													<span style="color: #222222;">'.$admin_config['game_name']['value'].'</span>
												</td>
											</tr>
										</table>
									</body>
								</html>',
								"Reply-To: ".$admin_config['game_name']['value']." <".$admin_config['contact_email']['value'].">\r\n" . 
								"From: ".$admin_config['game_name']['value']." <".$admin_config['contact_email']['value'].">\r\n" .
								"MIME-Version: 1.0\r\n" .
								"Content-type: text/html; charset=iso-8859-1");
								
								$successMsg = $langBase->get('home-18');
							}
						}
				?>
                <div id="main_content">
                	<div class="main_head">
                        <h1><img src="/images/icons/star.png" alt="" class="icon" id="headerTitleIcon" /><a href="<?=$config['base_url']?>"><?=$admin_config['game_name']['value']?> &raquo; <?=$langBase->get('header-12')?></a></h1>

                        <ul class="right">
                            <li><?=$langBase->get('home-04')?><h2 id="server_time" class="small"><?=date('H:i')?></h2></li>
                        </ul>
                    </div>
                    <div class="clear"></div>
					<?php if($successMsg) echo '<p class="yellow" style="margin: 0; font-size: 12px;">' . $successMsg . '</p>';?>
                    <?php if($errorMsg) echo '<p class="red" style="margin: 0; font-size: 12px;">' . $errorMsg . '</p>';?>
<div class="bg_c" style="width: 400px;">
	<h1 class="big"><?=$langBase->get('header-12')?> &raquo; 1/2</h1>
    <form method="post" action="">
        <dl class="form" style="margin-top: 0; padding-top: 0;">
            <dt><?=$langBase->get('home-14')?></dt>
            <dd><input type="text" class="styled" name="fp_mail" value="" style="width: 200px;" /></dd>
        </dl>
        <p style="clear: both; margin: 0; padding-top: 15px; text-align: center;">
            <input type="submit" value="<?=$langBase->get('home-19')?>" />
        </p>
    </form>
</div>
                <?php
					}
					else
					{
						$extra = unserialize($last_temp['extra']);
						
						if (isset($_POST['new_pass']))
						{
							$sql = $db->Query("SELECT id FROM `[users]` WHERE `email`='".$extra['mail']."'");
							$user = $db->FetchArray($sql);
							
							$pass = View::DoubleSalt($db->EscapeString($_POST['new_pass']), $user['id']);
							$pass_re = View::DoubleSalt($db->EscapeString($_POST['new_pass_re']), $user['id']);
							
							if ($pass !== $pass_re)
							{
								$errorMsg = $langBase->get('err-04');
							}
							elseif (!View::ValidPassword($_POST['new_pass']))
							{
								$errorMsg = $langBase->get('err-03');
							}
							else
							{
								$db->Query("UPDATE `temporary` SET `active`='0' WHERE `id`='".$last_temp['id']."'");
								$db->Query("UPDATE `[users]` SET `pass`='".$pass."' WHERE `id`='".$user['id']."'");
								
								$successMsg = $langBase->get('home-20');
							}
						}
				?>
<div id="main_content">
                	<div class="main_head">
                        <h1><img src="/images/icons/star.png" alt="" class="icon" id="headerTitleIcon" /><a href="<?=$config['base_url']?>"><?=$admin_config['game_name']['value']?> &raquo; Recover Password</a></h1>

                        <ul class="right">
                            <li><?=$langBase->get('home-04')?><h2 id="server_time" class="small"><?=date('H:i')?></h2></li>
                        </ul>
                    </div>
                    <div class="clear"></div>
					<?php if($successMsg) echo '<p class="yellow" style="margin: 0; font-size: 12px;">' . $successMsg . '</p>';?>
                    <?php if($errorMsg) echo '<p class="red" style="margin: 0; font-size: 12px;">' . $errorMsg . '</p>';?>
<div class="bg_c" style="width: 400px;">
	<h1 class="big"><?=$langBase->get('header-12')?> &raquo; 2/2</h1>
    <form method="post" action="">
        <dl class="form" style="margin-top: 0; padding-top: 0;">
			<dt><?=$langBase->get('txt-17')?></dt>
            <dd><input type="password" class="styled" name="new_pass" value="" style="width: 200px;" /></dd>
            <dt><?=$langBase->get('txt-18')?></dt>
            <dd><input type="password" class="styled" name="new_pass_re" value="" style="width: 200px;" /></dd>
        </dl>
        <p style="clear: both; margin: 0; padding-top: 15px; text-align: center;">
            <input type="submit" value="<?=$langBase->get('txt-19')?>" />
        </p>
    </form>
</div>
                <?php
				}}
				elseif ($side == 'contact')
				{
					if(isset($_POST['trimite-msg'])){
						if($_POST['c_nume'] == ""){
							$errorMsg = $langBase->get('kontakt-04');
						}else if($_POST['c_email'] == ""){
							$errorMsg = $langBase->get('kontakt-05');
						}else if($_POST['c_subiect'] == ""){
							$errorMsg = $langBase->get('kontakt-06');
						}else if($_POST['c_nume'] == ""){
							$errorMsg = $langBase->get('kontakt-07');
						}else{
							mail($admin_config['contact_email']['value'], $admin_config['game_name']['value'].' » Kontaktformular', 
								'<html>
									<body style="font-family: Verdana; color: #333333; font-size: 12px;">
										<table style="width: 300px; margin: 0px auto;">
											<tr style="text-align: center;">
												<td style="border-bottom: solid 1px #cccccc;"><h1 style="margin: 0; font-size: 20px;"><a href="'.$config['base_url'].'">'.$admin_config['game_name']['value'].'</a></h1><h2 style="text-align: right; font-size: 14px; margin: 7px 0 10px 0;">&laquo; Kontaktformular</h2></td>
											</tr>
											<tr style="text-align: justify;">
												<td style="padding-top: 15px; padding-bottom: 15px;">
													Hallo,
													<br />
													Jemand mit dieser IP: '.$_SERVER['REMOTE_ADDR'].', kontaktierte dich von '.$admin_config['game_name']['value'].'
													<br />
													<br />
													<b>Name:</b> '.$_POST['c_nume'].'<br>
													<b>Email:</b> '.$_POST['c_email'].'<br>
													<b>Betreff:</b> '.$_POST['c_subiect'].'<br>
													<b>Nachricht:</b><br><br>
													"'.$_POST['c_mesaj'].'"
												</td>
											</tr>
											<tr style="text-align: right; color: #777777;">
												<td style="padding-top: 10px; border-top: solid 1px #cccccc;">
													Besten Gruß,
													<br />
													<span style="color: #222222;">'.$admin_config['game_name']['value'].'</span>
												</td>
											</tr>
										</table>
									</body>
								</html>',
								"Reply-To: ".$admin_config['game_name']['value']." <".$_POST['c_email'].">\r\n" . 
								"From: ".$admin_config['game_name']['value']." <".$admin_config['contact_email']['value'].">\r\n" .
								"MIME-Version: 1.0\r\n" .
								"Content-type: text/html; charset=utf-8");
								
							$successMsg = $langBase->get('kontakt-08');
						}
					}
				?>
<div id="main_content">
                	<div class="main_head">
                        <h1><img src="/images/icons/star.png" alt="" class="icon" id="headerTitleIcon" /><a href="<?=$config['base_url']?>"><?=$admin_config['game_name']['value']?> &raquo; <?=$langBase->get('header-08')?></a></h1>

                        <ul class="right">
                            <li><?=$langBase->get('home-04')?><h2 id="server_time" class="small"><?=date('H:i')?></h2></li>
                        </ul>
                    </div>
                    <div class="clear"></div>
					<?php if($successMsg) echo '<p class="yellow" align="center" style="font-size: 12px;">' . $successMsg . '</p>';?>
                    <?php if($errorMsg) echo '<p class="red" align="center" style="font-size: 12px;">' . $errorMsg . '</p>';?>
<div class="bg_c" style="width: 400px;">
	<form method="post" action="">
        <dl class="form" style="margin-top: 0; padding-top: 0;">
			<dt><?=$langBase->get('kontakt-01')?></dt>
			<dd><input type="text" class="styled" name="c_nume" value="<?=$_POST['c_nume']?>" /></dd>
			<dt><?=$langBase->get('home-14')?>:</dt>
			<dd><input type="text" class="styled" name="c_email" value="<?=$_POST['c_email']?>" /></dd>
			<dt><?=$langBase->get('kontakt-02')?></dt>
			<dd><input type="text" class="styled" name="c_subiect" value="<?=$_POST['c_subiect']?>" /></dd>
			<dt><?=$langBase->get('kontakt-03')?></dt>
			<dd><textarea class="styled" rows="5" name="c_mesaj" value="<?=$_POST['c_mesaj']?>" /></textarea></dd>
		</dl>
		<p style="clear: both; margin: 0; padding-top: 15px; text-align: center;">
            <input type="submit" name="trimite-msg" value="<?=$langBase->get('txt-14')?>" />
        </p>
	</form>
</div>
<div class="clear"></div>
<?
}else{			
	$sql = $db->Query("SELECT player_stats FROM `game_stats` ORDER BY last_updated DESC LIMIT 1");
	$game_stats = $db->FetchArray($sql);
	$player_stats = unserialize($game_stats['player_stats']);
	$sql = $db->Query("SELECT id FROM `[players]` WHERE `online`+'3600'>'".time()."' AND `level`>'0'");
	$online_usr = $db->GetNumRows($sql);
?>
            	<div id="main_content">
                	<div class="main_head">
                        <h1><img src="/images/icons/star.png" alt="" class="icon" id="headerTitleIcon" /><a href="<?=$config['base_url']?>"><?=$admin_config['game_name']['value']?> &raquo; <?=$langBase->get('header-07')?></a></h1>

                        <ul class="right">
                            <li><?=$langBase->get('home-04')?><h2 id="server_time" class="small"><?=date('H:i')?></h2></li>
                        </ul>
                    </div>
                    <div class="clear"></div>

<div class="startside_main">
    <div class="start_left">
    	<h1 class="title"><?=$langBase->get('header-02')?></h1>
        <?php
			$sql = $db->Query("SELECT id,text,added FROM `news` WHERE `deleted`='0' ORDER BY id DESC LIMIT 0,2");
			$all_news = $db->FetchArrayAll($sql);
			
			if( count($all_news) <= 0 ){
				echo '<h2 class="center">There are no news.</h2>';
				
			}else{
				
				$added = 0;
				
				foreach($all_news as $news):
					
					$added++;
					
					$text = $news['text'];
					
					if( View::Lenght($text) > 225 ){
						$text = substr($text, 0, 225)." [...]";
					}
					
					$bbText = new BBCodeParser($text, 'news_preview', false);
			?>
            <div class="news">
            	<p class="text<?=$text_size?>"><?=$bbText->result?></p>
                <p class="time"><?=View::Time($news['added'], true)?></p>
                <div class="hr big" style="margin: 10px;"></div>
            </div>
            <?php
				endforeach;
				
			}
		?>
    </div>
	<div class="start_right">
	<h1 class="title"><?=$langBase->get('ot-stats')?></h1>
	<p style="font-size: 16px; margin: 1px 5px 20px 5px;"><b><?=$langBase->get('runda-02')?>:</b> #<?=$admin_config['game_round']['value']?></p>
	<p style="font-size: 11px; margin: 1px 5px 5px 5px;"><b><?=$langBase->get('stats-01')?>:</b> <?=View::CashFormat($player_stats['num_total'])?></p>
	<p style="font-size: 11px; margin: 0px 5px 5px 5px;"><b><?=$langBase->get('stats-03')?>:</b> <?=View::CashFormat($player_stats['regged_today'])?></p>
	<p style="font-size: 12px; margin: 15px 5px 5px 5px;"><b><?=$langBase->get('stats-04')?>:</b> <span style="color:green"><?=View::CashFormat($online_usr)?></span></p>
	<div class="hr big" style="margin: 20px 10px;"></div>
	<p style="font-size: 14px; margin: 0 5px 5px 5px;"><center><b><a href="/?side=signup" style="font-size: 16px;"><?=$langBase->get('home-22')?></a></b></center></p><br />
	</div>
	<div class="clear"></div>
</div>
                <?}?></div>
                <div id="extra_content">
                	<ul>
                    	<li><a href="/images/preview/01.jpg" rel="previewImages" title="Homepage"><img src="/images/preview/t_01.jpg" alt="" title="Preview 1" width="70" height="50" /></a></li>
                        <li><a href="/images/preview/02.jpg" rel="previewImages" title="Robberies"><img src="/images/preview/t_02.jpg" alt="" title="Preview 2" width="70" height="50" /></a></li>
                        <li><a href="/images/preview/03.jpg" rel="previewImages" title="Forum"><img src="/images/preview/t_03.jpg" alt="" title="Preview 3" width="70" height="50" /></a></li>
                        <li><a href="/images/preview/04.jpg" rel="previewImages" title="Map"><img src="/images/preview/t_04.jpg" alt="" title="Preview 4" width="70" height="50" /></a></li>
                        <li><a href="/images/preview/05.jpg" rel="previewImages" title="Families"><img src="/images/preview/t_05.jpg" alt="" title="Preview 5" width="70" height="50" /></a></li>
                        <li><a href="/images/preview/06.jpg" rel="previewImages" title="Missions"><img src="/images/preview/t_06.jpg" title="Preview 6" alt="" width="70" height="50" /></a></li>
                        <li><a href="/images/preview/07.jpg" rel="previewImages" title="Car theft"><img src="/images/preview/t_07.jpg" title="Preview 7" alt="" width="70" height="50" /></a></li>
                        <li><a href="/images/preview/10.jpg" rel="previewImages" title="Profile"><img src="/images/preview/t_10.jpg" alt="" title="Preview 10" width="70" height="50" /></a></li>
                    </ul>
                </div>

        	</div>
        </div>
        <div class="clear"></div>
    </div>
	
	<p class="center"> <a href="<?=$config['base_url']?>?side=contact" target="_self"><font color=#6D6D6D><b>Support</b></font></a> | <a href="<?=$config['base_url']?>rules.php" target="_self"><font color=#6D6D6D><b>Regeln</b></font></a> | <a href="<?=$config['base_url']?>impress.php" target="_self"><font color=#6D6D6D><b>Impressum</b></font></a> | <a href="<?=$config['base_url']?>regulament.php" target="_self"><font color=#6D6D6D><b>Haftungsausschluss</b></font></a> | <a href="<?=$config['base_url']?>pdatelor.php" target="_self"><font color=#6D6D6D><b>Datenschutz</b></font></a></p>
    <p class="center">All rights reserved &copy; 2019-<?=date('Y').' '.$admin_config['game_name']['value']?> - V<?=$admin_config['game_version']['value']?> - Powered by <a href="https://nmafia.unterweltmafia.de/" target="_blank">Unterweltmafia</a></p>
	
</body>
</html>
<?php
	$db->Close();
	ob_end_flush();
?>