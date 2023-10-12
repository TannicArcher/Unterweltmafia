<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	//require_once('businessValidator.php');
	
	$firma = $db->EscapeString($_GET['f']);
	$sql = $db->Query("SELECT * FROM `businesses` WHERE `id`='".$firma."' AND `active`='1' AND `type`='2'");
	$firma = $db->FetchArray($sql);
	
	if ($firma['id'] == '')
	{
		View::Message('ERROR', 2, true, '/game/?side=firma/');
	}
	
	$firma_misc = unserialize($firma['misc']);
	$firmatype = $config['business_types'][$firma['type']];
	
	$journalists = $firma_misc['journalists'];
	
	if (!$journalists[Player::Data('id')])
	{
		View::Message('ERROR', 2, true, '/game/?side=firma/');
	}
	
	if (isset($_POST['quit_pass']))
	{
		$pass = View::DoubleSalt($db->EscapeString($_POST['quit_pass']), User::Data('id'));
		
		if (Player::Data('id') == $firma['job_1'])
		{
			echo View::Message($langBase->get('comp-33'), 2);
		}
		elseif ($pass !== User::Data('pass'))
		{
			echo View::Message($langBase->get('txt-20'), 2);
		}
		else
		{
			unset($journalists[Player::Data('id')]);
			$firma_misc['journalists'] = $journalists;
			
			$db->Query("UPDATE `businesses` SET `misc`='".serialize($firma_misc)."' WHERE `id`='".$firma['id']."'");
			
			$db->Query("INSERT INTO `business_log` (`b_id`, `text`, `type`, `added`, `added_date`)VALUES('".$firma['id']."', '".View::Player(Player::$datavar, true)." har forlatt avisfirmaet.', '6', '".time()."', '".date('d.m.Y')."')");
			
			Accessories::AddLogEvent(Player::Data('id'), 14, array(
				'-COMPANY_IMG-' => $firma['image'],
				'-COMPANY_NAME-' => $firma['name'],
				'-COMPANY_ID-' => $firma['id']
			), Player::Data('id'));
		}
	}
	elseif (isset($_POST['create_title']))
	{
		$title = $db->EscapeString($_POST['create_title']);
		$text = $db->EscapeString($_POST['create_text']);
		
		$typeKey = $db->EscapeString($_POST['create_type']);
		$type = $firmatype['extra']['article_types'][$typeKey];
		
		$paper = $db->EscapeString($_POST['create_newspaper']);
		$sql = $db->Query("SELECT id,pending_articles FROM `newspapers` WHERE `b_id`='".$firma['id']."' AND `published`='0' AND `deleted`='0' AND `id`='".$paper."'");
		$paper = $db->FetchArray($sql);
		
		if ($paper['id'] == '')
		{
			echo View::Message('ERROR', 2);
		}
		elseif (!$type)
		{
			echo View::Message($langBase->get('comp-34'), 2);
		}
		elseif (View::Length($title) < $firmatype['extra']['article_title_min'])
		{
			echo View::Message($langBase->get('msg-10', array('-NUM-' => $firmatype['extra']['article_title_min'])), 2);
		}
		elseif (View::Length($title) > $firmatype['extra']['article_title_max'])
		{
			echo View::Message($langBase->get('msg-11', array('-NUM-' => $firmatype['extra']['article_title_max'])), 2);
		}
		elseif (View::Length($text) < $firmatype['extra']['article_text_min'])
		{
			echo View::Message($langBase->get('msg-13', array('-NUM-' => $firmatype['extra']['article_text_min'])), 2);
		}
		else
		{
			$articles = unserialize($paper['pending_articles']);
			
			$artID = substr(sha1(uniqid(rand())), 0, 4);
			$articles[$artID] = array(
				'id' => $artID,
				'title' => $title,
				'text' => base64_encode($text),
				'type' => $typeKey,
				'journalist' => Player::Data('id'),
				'added' => time()
			);
			
			$db->Query("UPDATE `newspapers` SET `pending_articles`='".serialize($articles)."' WHERE `id`='".$paper['id']."'");
			
			View::Message($langBase->get('comp-35'), 1, true);
		}
	}
?>
<div style="width: 600px; margin: 0px auto;">
    <div class="left" style="width: 245px;">
        <div class="bg_c" style="width: 225px;">
            <h1 class="big"><?=$langBase->get('comp-36')?></h1>
            <form method="post" action="">
                <dl class="dd_right">
                	<dt><?=$langBase->get('home-02')?></dt>
                    <dd><input type="password" name="quit_pass" class="flat" /></dd>
                </dl>
                <p class="center clear" style="margin-top: 15px;">
                	<a href="#" class="button form_submit"><?=$langBase->get('comp-36')?></a>
                </p>
            </form>
        </div>
    </div>
    <div class="left" style="width: 345px; margin-left: 10px;">
        <div class="bg_c" style="width: 325px;">
            <h1 class="big"><?=$langBase->get('comp-37')?></h1>
            <form method="post" action="">
            	<dl class="form">
                	<dt><?=$langBase->get('txt-38')?></dt>
                    <dd><input type="text" class="styled" name="create_title" maxlength="<?=$firmatype['extra']['article_title_max']?>" value="<?=View::FixQuot($_POST['create_title'])?>" /></dd>
                    <dt><?=$langBase->get('comp-38')?></dt>
                    <dd><textarea name="create_text" rows="12" cols="38" style="width: 250px; height: 170px;"><?=View::FixQuot($_POST['create_text'])?></textarea></dd>
                    <dt><?=$langBase->get('txt-29')?></dt>
                    <dd><select name="create_type" style="margin: 10px 0 0 0;">
                    <?php
                    foreach ($firmatype['extra']['article_types'] as $key => $value)
                    {
                        echo '<option value="' . $key . '"' . ((isset($_POST['create_type']) && $_POST['create_type'] === $key) ? ' selected="selected"' : '') . '>' . $value . '</option>';
                    }
                    ?>
                    </select></dd>
                    <dt><?=$langBase->get('function-newspaper')?></dt>
                    <dd>
                    	<select name="create_newspaper" style="margin: 10px 0 0 0;">
                        	<option><?=$langBase->get('cereri-32')?>...</option>
                        <?php
						$sql = $db->Query("SELECT id,title FROM `newspapers` WHERE `b_id`='".$firma['id']."' AND `published`='0' AND `deleted`='0' ORDER BY id DESC");
						while ($paper = $db->FetchArray($sql))
						{
							echo '<option value="' . $paper['id'] . '"' . ((isset($_POST['create_newspaper']) && $_POST['create_newspaper'] === $paper['id']) ? ' selected="selected"' : '') . '>' . View::FixQuot($paper['title']) . '</option>';
						}
						?>
                        </select>
                    </dd>
                </dl>
                <p class="center clear">
                	<input type="submit" value="<?=$langBase->get('txt-14')?>" />
                </p>
            </form>
        </div>
    </div>
    <div class="clear"></div>
</div>