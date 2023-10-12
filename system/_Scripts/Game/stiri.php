<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
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
	
	#news_wrapper .comments_wrap {
		overflow: hidden;
		width: 600px;
		margin: 0px auto;
	}
	
	#news_wrapper .comments_wrap .comment {
		margin-top: 10px;
		margin-left: 11px;
	}
	
	#news_wrapper .comments_wrap .comment .arrow {
		width: 40px;
		height: 33px;
		float: left;
		margin-top: 10px;
		margin-right: 11px;
		background: url(../../../game/images/news_comment_arrow.png) center no-repeat;
		opacity: 0.5;
		filter: alpha(opacity=50);
	}
	
	#news_wrapper .comments_wrap .comment .content {
		float: left;
	}
	#news_wrapper .comments_wrap .comment .content .content_top {
		width: 538px;
		height: 5px;
		background: url(../../../game/images/news_comment_top.png) center no-repeat;
	}
	#news_wrapper .comments_wrap .comment .content .content_text {
		width: 518px;
		padding: 5px 10px 5px 10px;
		min-height: 40px;
		background: url(../../../game/images/news_comment_content.png) center repeat-y;
		overflow: hidden;
		text-align: justify;
		color: #464646;
		text-shadow: none;
	}
	#news_wrapper .comments_wrap .comment .content .content_bottom {
		width: 538px;
		height: 18px;
		padding-top: 2px;
		background: url(../../../game/images/news_comment_bottom.png) center no-repeat;
		text-align: center;
		color: #444444;
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
		$news = $db->EscapeString($_GET['id']);
		$sql = $db->Query("SELECT id,author,added,title,text,comments FROM `news` WHERE `id`='".$news."' AND `deleted`='0'");
		$news = $db->FetchArray($sql);
		
		if ($news['id'] == '')
		{
			echo View::Message('ERROR', 2, true, '/game/?side=' . $_GET['side']);
		}
		
		$comments = unserialize($news['comments']);
		
		if (isset($_POST['comment_text']))
		{
			$text = $db->EscapeString($_POST['comment_text']);
			
			if (View::Length($text) < 2)
			{
				echo View::Message($langBase->get('stiri-01'), 2);
			}
			elseif (View::Length($text) > 1000)
			{
				echo View::Message($langBase->get('stiri-02'), 2);
			}
			else
			{
				$comments[] = array(
					'text' => base64_encode($text),
					'player' => Player::Data('id'),
					'time' => time()
				);
				
				$db->Query("UPDATE `news` SET `comments`='".serialize($comments)."' WHERE `id`='".$news['id']."'");
				
				View::Message($langBase->get('stiri-03'), 1, true);
			}
		}
		elseif (isset($_GET['d']) && User::Data('userlevel') >= 3)
		{
			$comment_id = $db->EscapeString($_GET['d']);
			$comment = $comments[$comment_id];
			
			if ($comment)
			{
				unset($comments[$comment_id]);
				$db->Query("UPDATE `news` SET `comments`='".serialize($comments)."' WHERE `id`='".$news['id']."'");
				
				View::Message($langBase->get('stiri-04'), 1, true, '/game/?side=' . $_GET['side'] . '&id=' . $news['id']);
			}
		}
		
		$bbText = new BBCodeParser($news['text'], 'news_text', true);
		$author = $db->QueryFetchArray("SELECT id,name,level,health,profileimage FROM `[players]` WHERE `id`='".$news['author']."'");
?>
<p class="t_right" style="margin-top: 0;">
	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>" style="font-size: 13px;">&laquo; <?=$langBase->get('ot-back')?></a>
</p>
<div id="news_wrapper">
	<div class="news">
    	<div class="news_top">
        	<h1><?=View::NoHTML(trim($news['title']))?></h1>
            <p><?=View::Time($news['added'], true, 'H:i')?></p>
        </div>
        <div class="news_content">
        	<div class="info">
            	<p class="profileimage">
                	<a href="<?=$config['base_url']?>s/<?=$author['name']?>"><img src="<?=$author['profileimage']?>" alt="" /></a>
                    <span><?=View::Player($author)?></span>
                    <span class="hr big"></span>
                    <span class="t_left"><b><?=$langBase->get('stiri-05')?>:</b> <?=View::CashFormat(count($comments))?></span>
                </p>
            </div>
        	<div class="text">
            	<?php echo $bbText->result;?>
            </div>
            <div class="clear"></div>
        </div>
        <div class="news_bottom"></div>
    </div>
    <?php
	if (count($comments) > 0)
	{
	?>
    <div class="comments_wrap">
    	<?php
		krsort($comments);
		
		foreach ($comments as $c_key => $comment)
		{
			$bbText = new BBCodeParser(stripslashes(base64_decode($comment['text'])), 'news_comment', true);
			
			$comment_author = $db->QueryFetchArray("SELECT id,name,level,health,profileimage FROM `[players]` WHERE `id`='".$comment['player']."'");
	?>
    	<div class="comment">
        	<div class="arrow"></div>
            <div class="content">
            	<div class="content_top"></div>
                <div class="content_text">
                	<p class="img_left"><a href="<?=$config['base_url']?>s/<?=$comment_author['name']?>"><img src="<?=$comment_author['profileimage']?>" alt="" height="50" width="50" /></a></p>
                	<?php echo $bbText->result;?>
                </div>
                <div class="content_bottom"><?=$langBase->get('stiri-06')?> <?=View::Player($comment_author)?> &nbsp; &nbsp; <?=View::Time($comment['time'])?><?php if(User::Data('userlevel') >= 3) echo ' &nbsp; &nbsp; <a href="' . $config['base_url'] . '?side=' . $_GET['side'] . '&amp;id=' . $news['id'] . '&amp;d=' . $c_key . '">&laquo; '.$langBase->get('txt-36').'</a>';?></div>
            </div>
            <div class="clear"></div>
        </div>
    <?php
		}
		?>
    </div>
    <?php
	}
	?>
    <div class="bg_c w500">
        <h1 class="big"><?=$langBase->get('stiri-07')?></h1>
        <form method="post" action="">
            <p class="center">
                <textarea name="comment_text" cols="80" rows="4" style="width: 470px;"><?=stripslashes($_POST['comment_text'])?></textarea>
            </p>
            <p class="center">
                <input type="submit" value="<?=$langBase->get('stiri-08')?>" />
            </p>
        </form>
    </div>
</div>
<?php
	}
	else
	{
		echo '<p class="center" style="margin-bottom: 15px;"><a href="' . $config['base_url'] . '?side=changelog" class="button">Changelog</a></p>';
		
		$sql = $db->Query("SELECT id,title,added FROM `news` WHERE `deleted`='0' ORDER BY id DESC");
		$newslist = $db->FetchArrayAll($sql);
		
		if (count($newslist) <= 0)
		{
			echo '<h2>'.$langBase->get('err-06').'</h2>';
		}
		else
		{
			foreach ($newslist as $news)
			{
?>
<div id="news_wrapper">
	<div class="news" style="margin-bottom: 10px;">
    	<div class="news_top" style="height: 47px; background-position: top;">
        	<h1><a href="<?=$config['base_url']?>stiri/<?=$news['id']?>"><?=View::NoHTML(trim($news['title']))?></a></h1>
            <p><?=View::Time($news['added'], true, 'H:i')?></p>
        </div>
    </div>
</div>
<?php
			}
		}
	}
?>