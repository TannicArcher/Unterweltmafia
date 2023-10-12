<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$options = array('profiletext', 'profileimage', 'profilemusic', 'password', 'forum');
	
	$option = $_GET['a'];
	
	if (!isset($_GET['a']) || !in_array($option, $options)){
		header('Location: /game/?side='.$_GET['side'].'&a=profiletext');
		exit;
	}
	
	
	if ($option == 'profiletext')
	{
		
		if (isset($_POST['text']))
		{
			$text = $db->EscapeString($_POST['text']);
			
			if (View::Lenght($text) < $config['profiletext_min_length']){
				$errormsg = $langBase->get('edp-01', array('-NUM-' => $config['profiletext_min_length']));
				
			} elseif(View::Lenght($text) > $config['profiletext_max_length']){
				$errormsg = $langBase->get('edp-02', array('-NUM-' => $config['profiletext_max_length']));
				
			}
			
			if ($errormsg)
			{
				echo View::Message($errormsg, 2);
			}else
			{
				$db->Query("UPDATE `[players]` SET `profiletext`='$text' WHERE `id`='".Player::Data('id')."'");
				
				View::Message($langBase->get('edp-03'), 1, true);
			}
		}
?>
<div class="bg_c w500">
	<h1 class="left"><?=$langBase->get('edp-04')?></h1>
    <h1 class="right"><a href="<?=$config['base_url']?>?side=spillerprofil&amp;name=<?=Player::Data('name')?>">Profil anzeigen</a></h1>
    <div class="clear"></div>
    <form action="" method="post">
    	<div class="center"><textarea name="text" cols="78" rows="20"><?php echo isset($_POST['text']) ? $_POST['text'] : Player::Data('profiletext'); ?></textarea></div>
        <p class="center">
        	<input type="submit" value="<?=$langBase->get('txt-21')?>" />
        </p>
    </form>
</div>
<?php
	}
	elseif($option == 'profileimage')
	{
		define ("MAX_SIZE","500"); 
		
		function getExtension($str) {
				$i = strrpos($str,".");
				if (!$i) { return ""; }
				$l = strlen($str) - $i;
				$ext = substr($str,$i+1,$l);
			return $ext;
		}

		if(isset($_POST['up_av']) && $_FILES['cons_image']['name']){
			$filename = stripslashes($_FILES['cons_image']['name']);			
			$extension = getExtension($filename);
			$extension = strtolower($extension);
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))	
				{
					View::Message($langBase->get('edp-05'), 2, true);
				}else{
					$size = getimagesize($_FILES['cons_image']['tmp_name']);
					$sizekb = filesize($_FILES['cons_image']['tmp_name']);

					if ($sizekb > MAX_SIZE*1024){
						View::Message($langBase->get('edp-06'), 2, true);
					}else{	
						$image_name = 'av-'.Player::Data('id').'_'.Player::Data('userid').'.'.$extension;
						$copied = copy($_FILES['cons_image']['tmp_name'], $_SERVER['DOCUMENT_ROOT']."/upload/avatar/".$image_name);
						$url = $config['game_url']."/upload/avatar/".$image_name;
						$db->Query("UPDATE `[players]` SET `profileimage`='$url' WHERE `id`='".Player::Data('id')."'");

						if (!$copied) {
							View::Message('ERROR', 2, true);
						}else{
							View::Message($langBase->get('edp-07'), 1, true);
						}
					}
				}	
		}
?>
<div style="width: 530px; margin: 0px auto;">
    <div class="bg_c left" style="width: 235px; margin: 20px 8px 20px 4px;">
        <h1><?=$langBase->get('ot-avatar')?></h1>
		<form method="post" enctype="multipart/form-data" action="">
			<p class="center">
				<input type="file" id="image_up" class="styled" name="cons_image" >
				<input name="up_av" type="submit" id="image1" value="<?=$langBase->get('txt-47')?>" />
			</p>
		</form>
    </div>
    <div class="bg_c left" style="width: 235px;">
        <h1><?=$langBase->get('edp-08')?></h1>
        <div class="center" style="width: 235px; margin: 0px auto; overflow: hidden;">
        	<img src="<?=Player::Data('profileimage')?>" alt="" class="handle_image" />
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php
	}
	elseif($option == 'profilemusic')
	{
		if (isset($_POST['music_url']))
		{
			$url = $db->EscapeString($_POST['music_url']);
			$playMusic = isset($_POST['music_play']) ? 1 : 0;
			
			if (!preg_match('%\A(?:^((http[s]?|ftp):/)?/?([^:/\s]+)(:([^/]*))?((/\w+)*/)([\w\-.]+[^#?\s]+)(\?([^#]*))?(#(.*))?$)\Z%', $url) && $playMusic != Player::Data('play_profile_music'))
			{
				$url = '';
			}
			
			if (!preg_match('%\A(?:^((http[s]?|ftp):/)?/?([^:/\s]+)(:([^/]*))?((/\w+)*/)([\w\-.]+[^#?\s]+)(\?([^#]*))?(#(.*))?$)\Z%', $url) && $url != '')
			{
				echo View::Message('ERROR', 2);
			}
			else
			{
				$db->Query("UPDATE `[players]` SET `profile_music`='".$url."', `play_profile_music`='".$playMusic."' WHERE `id`='".Player::Data('id')."'");
				
				View::Message($langBase->get('edp-12'), 1, true);
			}
		}
?>
<div style="width: 530px; margin: 0px auto;">
    <div class="bg_c left" style="width: 235px; margin: 20px 8px 20px 4px;">
        <h1><?=$langBase->get('edp-09')?></h1>
        <form action="" method="post">
            <p class="center">
                <label for="music_url" class="textbox"><?=$langBase->get('edp-10')?></label>
                <input type="text" class="styled" id="music_url" name="music_url" style="width: 180px;" value="<?php echo isset($_POST['music_url']) ? $_POST['music_url'] : Player::Data('profile_music');?>" onfocus="this.select()" />
            </p>
            <dl class="dd_right">
            	<dt><?=$langBase->get('edp-11')?></dt>
                <dd><input type="checkbox" name="music_play"<?php if(Player::Data('play_profile_music')) echo ' checked="checked"';?> /></dd>
            </dl>
            <p class="center clear">
            	<input type="submit" value="<?=$langBase->get('txt-21')?>" />
            </p>
        </form>
    </div>
    <div class="bg_c left" style="width: 235px;">
        <h1><?=$langBase->get('edp-13')?></h1>
        <?php
		if (Player::Data('profile_music') == '')
		{
			echo '<p>'.$langBase->get('edp-14').'</p>';
		}
		else
		{
			echo '<embed src="'.$config['base_url'].'flash/player.swf" width="220" height="24" allowscriptaccess="always" allowfullscreen="true" wmode="transparent" flashvars="width=200&height=24&controlbar=bottom&file=' . Player::Data('profile_music') . '" />';
		}
		?>
    </div>
    <div class="clear"></div>
</div>
<?php
	}
	elseif($option == 'password')
	{
		if (isset($_POST['password']))
		{
			$sql = $db->Query("SELECT id,pass FROM `bank_clients` WHERE `playerid`='".Player::Data('id')."' AND `active`='1' AND `accepted`='1'");
			$bankClient = $db->FetchArray($sql);
			
			$password         = View::DoubleSalt($db->EscapeString($_POST['password']), User::Data('id'));
			$new_password     = View::DoubleSalt($db->EscapeString($_POST['new_password']), User::Data('id'));
			$new_password_rep = View::DoubleSalt($db->EscapeString($_POST['new_password_rep']), User::Data('id'));
			
			if ($password !== User::Data('pass')){
				$errormsg = $langBase->get('txt-20');
				
			}elseif ($new_password !== $new_password_rep){
				$errormsg = $langBase->get('err-04');
				
			}elseif (!View::ValidPassword($_POST['new_password'])){
				$errormsg = $langBase->get('err-03');
				
			}elseif (View::DoubleSalt($db->EscapeString($_POST['new_password']), $bankClient['id']) == $bankClient['pass']){
				$errormsg = $langBase->get('edp-15');
			}
			
			if ($errormsg)
			{
				echo View::Message($errormsg, 2);
			}else
			{
				$db->Query("UPDATE `[users]` SET `pass`='$new_password' WHERE `id`='".User::Data('id')."'");
				
				View::Message($langBase->get('home-20'), 1, true);
			}
		}
?>
<div class="bg_c">
	<h1><?=$langBase->get('edp-16')?></h1>
    <form method="post" action="">
    	<dl class="form">
        	<dt><?=$langBase->get('edp-17')?></dt>
            <dd><input type="password" class="styled" name="password" value="<?=$_POST['password']?>" /></dd>
            <dt><?=$langBase->get('edp-18')?></dt>
            <dd><input type="password" class="styled" name="new_password" value="<?=$_POST['new_password']?>" /></dd>
            <dt><?=$langBase->get('edp-19')?></dt>
            <dd><input type="password" class="styled" name="new_password_rep" value="<?=$_POST['new_password_rep']?>" /></dd>
        </dl>
        <p class="center clear">
        	<input type="submit" value="<?=$langBase->get('txt-21')?>" />
        </p>
    </form>
</div>
<?php
	} elseif($option == 'forum') {
		
		$submenu = $_GET['b'];
		if (!in_array($submenu, array('signatur', 'annet'))) $submenu = 'signatur';
		
		if ($submenu == 'signatur')
		{
			
			if (isset($_POST['text']))
			{
				$text = $db->EscapeString($_POST['text']);
				
				if (count(explode("\n", $text)) > $config['forumsignature_max_lines']){
					echo View::Message($langBase->get('edp-20', array('-NUM-' => $config['forumsignature_max_lines'])), 2);
					
				}else{
					
					$db->Query("UPDATE `[players]` SET `forum_signature`='".$text."' WHERE `id`='".Player::Data('id')."'");
					
					View::Message($langBase->get('edp-21'), 1, true);
				}
			}
?>
<p class="center">
	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$_GET['a']?>&amp;b=signatur" class="button active"><?=$langBase->get('edp-22')?></a>
    <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$_GET['a']?>&amp;b=annet" class="button"><?=$langBase->get('edp-23')?></a>
</p>
<div class="bg_c w500 forumsignature">
	<h1><?=$langBase->get('edp-22')?></h1>
    <form method="post" action="">
    	<p class="center">
        	<textarea name="text" cols="78" rows="6"><?php echo isset($_POST['text']) ? $_POST['text'] : Player::Data('forum_signature'); ?></textarea>
        </p>
        <p class="center">
        	<input type="submit" value="<?=$langBase->get('txt-21')?>" />
        </p>
    </form>
</div>
<?php
		}else{
			
			if (isset($_POST['per_page']))
			{
				$replies_pp = $db->EscapeString($_POST['per_page']);
				$view_sigs  = $db->EscapeString($_POST['view_sigs']);
				
				$replies_pp = $replies_pp < $config['forum_min_replies_per_page'] ? $config['forum_min_replies_per_page'] : ($replies_pp > $config['forum_max_replies_per_page'] ? $config['forum_max_replies_per_page'] : $replies_pp);
				$view_sigs  = $view_sigs ? 1 : 0;
				
				$edited = false;
				
				if ($replies_pp != User::Data('forum_replies_per_page') || $view_sigs != User::Data('forum_view_signatures')) $edited = true;
				
				if ($edited)
				{
					$db->Query("UPDATE `[users]` SET `forum_replies_per_page`='$replies_pp', `forum_view_signatures`='$view_sigs' WHERE `id`='".User::Data('id')."'");
					
					View::Message($langBase->get('comp-61'), 1, true);
					
				}
			}
?>
<p class="center">
	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$_GET['a']?>&amp;b=signatur" class="button"><?=$langBase->get('edp-22')?></a>
    <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$_GET['a']?>&amp;b=annet" class="button active"><?=$langBase->get('edp-23')?></a>
</p>
<div class="bg_c">
	<h1><?=$langBase->get('edp-23')?></h1>
    <form method="post" action="">
        <div class="c_1" style="padding: 5px; margin: 5px auto 5px;">
            <input type="checkbox" id="view_sigs" name="view_sigs"<?php if (User::Data('forum_view_signatures') == 1) echo ' checked="checked"'; ?> />
            <label for="view_sigs"><?=$langBase->get('edp-24')?></label>
        </div>
        <div class="c_1" style="padding: 5px; margin: 5px auto 0;">
            <?=$langBase->get('edp-25')?>: <input type="text" class="flat numbersOnly" name="per_page" maxlength="2" value="<?=User::Data('forum_replies_per_page')?>" style="width: 30px;" />
        </div>
        <p class="center">
            <input type="submit" value="<?=$langBase->get('txt-21')?>" />
        </p>
    </form>
</div>
<?php
		}
		
		
	}
?>