<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$reasons = array();
	$data = $db->QueryFetchArray("SELECT id, points, profileimage, profiletext, forum_signature, health, level FROM `[players]` WHERE `userid`='".User::Data('id')."' AND `null`='0' LIMIT 1");
	
	if (User::Data('hasPlayer') == 0)
	{
		$txt = $langBase->get('limit-18');
		
		if ($data['health'] <= 0) $reasons[$txt][] = $langBase->get('limit-01');
		if ($data['level'] <= 0)  $reasons[$txt][] = $langBase->get('limit-02');
	}
	
	if (User::Data('userlevel') <= 0)
	{
		$reasons[] = $langBase->get('limit-02');
		$rsn = $db->QueryFetchArray("SELECT reason FROM `deactivations` WHERE `victim`='".User::Data('id')."' LIMIT 1");
	}
?>
<div class="bg_c" style="width: 350px;">
	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
	<p><?=$langBase->get('limit-03')?></p>
	
	<ul>
		<?php
		foreach ($reasons as $key => $reason)
		{
			if (!is_numeric($key))
			{
				foreach ($reason as $txt)
				{
					$text .= '<li>' . $txt . '</li>';
				}
				echo "<li>" . $key . "<ul style=\"margin-top: 3px;\">" . $text . "</ul></li>\n";
			}
			else
			{
				echo "<li>" . $reason . "</li>\n";
			}
		}
		?>
	</ul>
	<? if (User::Data('userlevel') <= 0){ ?>
	<b><?=$langBase->get('limit-04')?>:</b> <div class="c_1 t_justify" style="margin: 10px; padding: 5px; overflow: hidden;"><?=$rsn['reason'];?></div>
	<?}?>
    
    <?php
		if (User::Data('hasPlayer') == 0)
		{
			if (isset($_POST['newPlayer_name']) && User::Data('userlevel') > 0)
			{
				$name = trim($db->EscapeString($_POST['newPlayer_name']));
				$pass = View::DoubleSalt($db->EscapeString($_POST['newPlayer_pass']), User::Data('id'));
				
				$name_validated = Accessories::ValidatePlayername($name);
				
				$image = isset($_POST['newPlayer_keep_profileimage']) ? $data['profileimage'] : $config['default_profileimage'];
				$text  = isset($_POST['newPlayer_keep_profiletext']) ? $data['profiletext'] : '';
				$fsig  = isset($_POST['newPlayer_keep_forumsig']) ? $data['forum_signature'] : '';
				
				if (User::Data('userlevel') >= 4)
				{
					$level = 4;
				}
				else
				{
					$level = 1;
				}
				
				if ($pass != User::Data('pass'))
				{
					echo View::Message($langBase->get('txt-20'), 2);
				}
				elseif ($name_validated === false)
				{
					echo View::Message($langBase->get('home-05'), 2);
				}
				elseif ($db->GetNumRows($db->Query("SELECT id FROM `[players]` WHERE `name`='".$name_validated."' LIMIT 1")) > 0)
				{
					echo View::Message($langBase->get('home-06'), 2);
				}
				else
				{
					$db->Query("UPDATE `[players]` SET `null`='1' WHERE `userid`='".User::Data('id')."'");
					$sql = "
					INSERT INTO `[players]`
					(`userid`, `name`, `online`, `last_active`, `IP_last`, `IP_created_with`, `created`, `cash`, `bank`, `profileimage`, `profiletext`, `forum_signature`, `live`)
					VALUES
					('".User::Data('id')."', '$name_validated', '".time()."', '".time()."', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['REMOTE_ADDR']."', '".time()."', '".$config['player_default_money']['cash']."', '".$config['player_default_money']['bank']."', '".$db->EscapeString($image)."', '".$db->EscapeString($text)."', '".$db->EscapeString($fsig)."', '".array_rand($config['places'])."')";
					
					$db->Query($sql);
					
					$db->Query("UPDATE `[users]` SET `hasPlayer`='1' WHERE `id`='".User::Data('id')."'");
					
					Player::UpdateData();
					
					Accessories::AddLogEvent(User::Data('id'), 'Congratulations for you new player account - '.View::Player(Player::$datavar, true).'!', 4, 'picture_empty.png');
					
					View::Message($langBase->get('limit-06'), 1, true);
					
				}
				
			}
	?>
    <?php
	if (User::Data('userlevel') > 0 && $data['level'] > 0)
	{
	if (isset($_POST['points_buyout']))
			{
				if ($data['points'] < 60)
				{
					echo View::Message($langBase->get('err-09'), 2);
				}
				else
				{
					$db->Query("UPDATE `[players]` SET `points`=`points`-'60', `health`='300' WHERE `id`='".$data['id']."'")or die(mysql_error());
					
					View::Message($langBase->get('limit-07'), 1, true);
				}
			}
	?>
<script type="text/javascript">
	<!--
	function pp_status()
	{
		var status = $('pp_status').set('html', '<?=$langBase->get('txt-46')?>');
	}
	-->
</script>
    <div class="bg_c c_1 w300">
    	<h1 class="big"><?=$langBase->get('limit-05')?></h1>
        <?=$langBase->get('limit-08')?><br><br>
		1) <?=$langBase->get('limit-09', array('-NUM-' => '60'))?><br>
		<b><?=$langBase->get('ot-points')?>:</b> <?=$data['points']?>
		<form method="post" action=""><p class="center"><input type="submit" name="points_buyout" value="<?=$langBase->get('limit-09', array('-NUM-' => '60'))?>" /></p></form><br>
    </div>
    <div class="bg_c c_1 w300">
    	<h1 class="big"><?=$langBase->get('limit-13')?></h1>
        <form method="post" action="">
        	<dl class="dt_110">
                <dt><?=$langBase->get('limit-14')?></dt>
                <dd><input type="text" name="newPlayer_name" class="styled" value="<?=$_POST['newPlayer_name']?>" maxlength="<?=$config['playername_max_chars']?>" /></dd>
                <dt><u><?=$langBase->get('edp-18')?></u></dt>
                <dd><input type="password" name="newPlayer_pass" class="styled" value="<?=$_POST['newPlayer_pass']?>" /></dd>
                <dt><?=$langBase->get('limit-15')?></dt>
                <dd><input type="checkbox" name="newPlayer_keep_profileimage" /></dd>
                <dt><?=$langBase->get('limit-16')?></dt>
                <dd><input type="checkbox" name="newPlayer_keep_profiletext" /></dd>
                <dt><?=$langBase->get('limit-17')?></dt>
                <dd><input type="checkbox" name="newPlayer_keep_forumsig" /></dd>
            </dl>
            <div class="center clear">
            	<input type="submit" value="<?=$langBase->get('txt-14')?>" />
            </div>
        </form>
    </div>
    <?php
	}
		}
	?>
</div>