<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	if (isset($_GET['new']) && User::Data('userlevel') >= 4)
	{
		if (isset($_POST['new_title']))
		{
			$title = $db->EscapeString($_POST['new_title']);
			$text = $db->EscapeString($_POST['new_text']);
			
			if (View::Length($title) < 5 || View::Length($text) < 5)
			{
				echo View::Message('You have to write at leas 5 characters!', 2);
			}
			else
			{
				$db->Query("INSERT INTO `changelog` (`author`, `title`, `text`, `added`)VALUES('".Player::Data('id')."', '".trim($title)."', '".$text."', '".time()."')");
				
				View::Message('Changelog list successfully added!', 1, true, '/game/?side=' . $_GET['side']);
			}
		}
?>
<div class="bg_c w400">
	<h1 class="big"><?=$langBase->get('change-01')?></h1>
    <form method="post" action="">
    	<dl class="form">
        	<dt><?=$langBase->get('txt-38')?></dt>
            <dd><input type="text" name="new_title" class="styled" value="<?=stripslashes($_POST['new_title'])?>" /></dd>
            <dt><?=$langBase->get('txt-39')?></dt>
            <dd><textarea name="new_text" cols="50" rows="8"><?=stripslashes($_POST['new_text'])?></textarea></dd>
        </dl>
        <p class="center clear">
        	<input type="submit" value="<?=$langBase->get('txt-14')?>" />
        </p>
    </form>
</div>
<?php
	}
	else
	{
		if (User::Data('userlevel') >= 4)
			echo '<p class="center"><a href="' . $config['base_url'] . '?side=' . $_GET['side'] . '&amp;new" class="button">'.$langBase->get('subMenu-14').'</a></p>';
	}
?>
<style type="text/css">
	<!--
	#news_wrapper {
		overflow: hidden;
		width: 604px;
		margin: 0px auto;
	}
	
	#news_wrapper .news {
		width: 604px;
		overflow: hidden;
	}
	
	#news_wrapper .news .news_top {
		width: 604px;
		height: 55px;
		overflow: hidden;
		background: url(../../../game/images/news_top_bg.png) center no-repeat;
		position: relative;
	}
	#news_wrapper .news .news_top h1, #news_wrapper .news .news_top p {
		position: absolute;
		left: 120px;
		top: 8px;
		text-transform: uppercase;
		font-size: 12px;
		color: #727272;
		margin: 0;
		padding: 0;
	}
	#news_wrapper .news .news_top p {
		font-size: 11px;
		color: #424242;
		text-transform: none;
		top: 23px;
	}
	
	#news_wrapper .news .news_bottom {
		width: 600px;
		height: 5px;
		margin: 0px auto;
		background: url(../../../game/images/news_bottom_bg.png) center no-repeat;
	}
	
	#news_wrapper .news .news_content {
		width: 600px;
		margin: 0px auto;
		min-height: 50px;
		overflow: hidden;
		background: url(../../../game/images/news_content_bg.png) center repeat-y;
		position: relative;
	}
	#news_wrapper .news .news_content .info {
		float: right;
		margin: 0 10px 10px 10px;
	}
	#news_wrapper .news .news_content .info p.profileimage { width: 90px; text-align: center; }
	#news_wrapper .news .news_content .info p.profileimage img { width: 90px; height: 100px; }
	#news_wrapper .news .news_content .info p.profileimage span { display: block; margin-top: 5px; }
	#news_wrapper .news .news_content .text {
		overflow: hidden;
		text-align: justify;
		color: #555555;
		text-shadow: none;
		margin: 0 10px 0 10px;
	}
	
	.img_left {
		float: left;
		margin: 0 10px 5px 0;
	}
	-->
</style>
<?php
	if (isset($_GET['id']))
	{
		$item = $db->EscapeString($_GET['id']);
		$sql = $db->Query("SELECT id,author,added,title,text FROM `changelog` WHERE `id`='".$item."' AND `deleted`='0'");
		$item = $db->FetchArray($sql);
		
		if ($item['id'] == '')
		{
			echo View::Message('Error.', 2, true, '/game/?side=' . $_GET['side']);
		}
		
		if (isset($_GET['del']) && User::Data('userlevel') >= 4)
		{
			$db->Query("UPDATE `changelog` SET `deleted`='1' WHERE `id`='" . $item['id'] . "'");
			View::Message('Successfully deleted!', 1, true, '/game/?side=' . $_GET['side']);
		}
		
		$bbText = new BBCodeParser($item['text'], 'news_text', true);
		$author = $db->QueryFetchArray("SELECT id,name,level,health,profileimage FROM `[players]` WHERE `id`='".$item['author']."'");
?>
<p class="t_right" style="margin-top: 0;">

	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>" style="font-size: 13px;">&laquo; <?=$langBase->get('txt-40')?></a>
</p>
<div id="news_wrapper">
	<div class="news">
    	<div class="news_top">
        	<h1><?=View::NoHTML(trim($item['title']))?></h1>
            <p><?=View::Time($item['added'], true, 'H:i')?></p>
            <?php if (User::Data('userlevel') >= 4):?><p class="right" style="position: static; margin: 10px;"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$item['id']?>&amp;del">Delete</a></p><?php endif;?>
        </div>
        <div class="news_content">
        	<div class="info">
            	<p class="profileimage">
                	<a href="<?=$config['base_url']?>s/<?=$author['name']?>"><img src="<?=$author['profileimage']?>" alt="" /></a>
                    <span><?=View::Player($author)?></span>
                </p>
            </div>
        	<div class="text">
            	<?php echo $bbText->result;?>
            </div>
            <div class="clear"></div>
        </div>
        <div class="news_bottom"></div>
    </div>
</div>
<?php
	}
	else
	{
		$sql = "SELECT id,title,added FROM `changelog` WHERE `deleted`='0' ORDER BY id DESC";
		$pagination = new Pagination($sql, 20, 'p');
		$itemlist = $pagination->GetSQlRows();
		
		if (count($itemlist) <= 0)
		{
			echo '<h2>Nothing found!</h2>';
		}
		else
		{
			foreach ($itemlist as $item)
			{
?>
<div id="news_wrapper">
	<div class="news" style="margin-bottom: 10px;">
    	<div class="news_top" style="height: 47px; background-position: top;">
        	<h1><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$item['id']?>"><?=View::NoHTML(trim($item['title']))?></a></h1>
            <p><?=View::Time($item['added'], true, 'H:i')?></p>
        </div>
    </div>
</div>
<?php
			}
			
			echo $pagination->GetPageLinks();
		}
	}
?>