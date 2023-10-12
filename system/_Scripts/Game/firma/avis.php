<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	function validate64($buffer)
	{
	  $VALID  = 1;
	  $INVALID= 0;
	
	  $p    = $buffer;   
	  $len  = strlen($p);      
	 
	  for($i=0; $i<$len; $i++)
	  {
		 if( ($p[$i]>="A" && $p[$i]<="Z")||
			 ($p[$i]>="a" && $p[$i]<="z")||
			 ($p[$i]>="/" && $p[$i]<="9")||
			 ($p[$i]=="+")||
			 ($p[$i]=="=")||
			 ($p[$i]=="\x0a")||
			 ($p[$i]=="\x0d")
		   )
		   continue;
		 else
		   return $INVALID;
	  }
	return $VALID;
	}
	
	//require_once('businessValidator.php');
	
	$paper = $db->EscapeString($_GET['id']);
	$paper = $db->QueryFetchArray("SELECT * FROM `newspapers` WHERE `id`='".$paper."' AND `deleted`='0'");



	$firma = $db->QueryFetchArray("SELECT * FROM `businesses` WHERE `id`='".$paper['b_id']."' AND `active`='1' AND `type`='2'");



	if (($paper['id'] == '' && !in_array(Player::Data('id'), array($firma['job_1'], $firma['job_2']))) || $firma['id'] == '')
	{
		View::Message('ERROR', 2, true, '/game/?side=firma/');
	}
	
	$sold_to = unserialize($paper['sold_to']);
	
	if (isset($_GET['buy']) && !in_array(Player::Data('id'), $sold_to))
	{
		if ($paper['price'] > Player::Data('cash'))
		{
			echo View::Message($langBase->get('err-01'), 2);
		}
		else
		{
			$sold_to[Player::Data('id')] = Player::Data('id');
			
			$db->Query("UPDATE `newspapers` SET `sold_to`='".serialize($sold_to)."' WHERE `id`='".$paper['id']."'");
			$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$paper['price']."' WHERE `id`='".Player::Data('id')."'");
			$db->Query("UPDATE `businesses` SET `bank`=`bank`+'".$paper['price']."', `bank_income`=`bank_income`+'".$paper['price']."' WHERE `id`='".$firma['id']."'");



			$stocks = $db->QueryFetchArray("SELECT id FROM `stocks` WHERE `business_type`='game_business' AND `business_id`='".$firma['id']."' AND `active`='1'");



			if ($stocks['id'] == '')
			{
				$db->Query("INSERT INTO `stocks`
							(`business_type`, `business_id`, `shares`, `changes`, `created`, `current_price`, `last_change_time`)
							VALUES
							('game_business', '".$firma['id']."', 'a:0:{}', 'a:0:{}', '".time()."', '".$config['businesses_default_stockprice']."', '".time()."')");
				
				$stocks['id'] = mysql_insert_id();
			}
			$db->Query("UPDATE `stocks` SET `current_income`=`current_income`+'".$paper['price']."' WHERE `id`='".$stocks['id']."'");
			
			View::Message($langBase->get('comp-29'), 1, true, '/game/?side=' . $_GET['side'] . '&id=' . $paper['id']);
		}
	}
	
	$articles['top'] = array();
	$articles['left'] = array();
	$articles['right'] = array();
	$articles['bottom'] = array();
	
	$all_articles = unserialize($paper['articles']);
	
	foreach ($all_articles as $key => $article)
	{
		$articles[$article['type']][$key] = $article;
	}
?>
<p class="t_right">
	<a href="<?=$config['base_url']?>?side=firma/firma&amp;id=<?=$firma['id']?>" class="button"><?=$langBase->get('ot-back')?></a>
</p>
<?php
	if (!in_array(Player::Data('id'), $sold_to))
	{
?>
<div class="bg_c w200">
	<h1 class="big">&laquo;<?=View::NoHTML($paper['title'])?>&raquo;</h1>
    <p>
        <?=$langBase->get('txt-03')?>: <b><?=View::CashFormat($paper['price'])?> $</b>
    </p>
    <p><?=$langBase->get('comp-30')?></p>
    <ul>
    <?php
    foreach ($all_articles as $article)
	{
		echo '<li>' . View::NoHTML($article['title']) . '</li>';
	}
	?>
    </ul>
    <p><b><?=$langBase->get('cautare-02')?>:</b><br /><?=nl2br(View::NoHTML((trim($paper['description']) == '' ? '' : $paper['description'])))?></p>
    <p class="center" style="margin-top: 15px;">
    	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$paper['id']?>&amp;buy" class="button"><?=$langBase->get('txt-01')?></a>
    </p>
</div>
<?php
	}
	else
	{
?>
<div class="hr big" style="margin: 10px;"></div>
<div class="newspaper_wrap layout_<?=$paper['layout']?>">
	<h1 class="center" style="margin: 0 0 15px 0;"><?=View::NoHTML($paper['title'])?><br /><span style="font-size: 10px; color: #555555;"><?=$langBase->get('comp-32')?> <a href="<?=$config['base_url']?>?side=firma/firma&amp;id=<?=$firma['id']?>" style="color: #666666;"><?=$firma['name']?></a>.</span></h1>
    <?php if (!empty($paper['logo'])) echo '<div class="logo"><img src="' . $paper['logo'] . '" alt="" /></div>';?>
	<div class="top">
    <?php
	foreach ($articles['top'] as $article):

		if (validate64($article['text']))
			$text = base64_decode($article['text']);


		$text = new BBCodeParser($text, 'newspaper_article', true);
		$text = str_replace('\r\n','<br>',$text->result);
		$text = stripslashes($text);
	?>
    	<div class="article_wrap">
            <div class="head"><?=View::NoHTML($article['title'])?></div>
            <div class="text"><?=$text?></div>
            <div class="bottom"><?=$langBase->get('comp-31', array('-PLAYER-' => View::Player(array('id' => $article['journalist'])), '-TIME-' => View::Time($article['added'], false, 'H:i')))?></div>
        </div>
    <?php
	endforeach;
	?>
    </div>
    <div class="left">
    <?php
	foreach ($articles['left'] as $article):
		$text = $article['text'];
		if (validate64($text))
			$text = base64_decode($text);
		
		$text = new BBCodeParser(stripslashes($text), 'newspaper_article', true);
	?>
    	<div class="article_wrap">
            <div class="head"><?=View::NoHTML($article['title'])?></div>
            <div class="text"><?=$text->result?></div>
            <div class="bottom"><?=$langBase->get('comp-31', array('-PLAYER-' => View::Player(array('id' => $article['journalist'])), '-TIME-' => View::Time($article['added'], false, 'H:i')))?></div>
        </div>
    <?php
	endforeach;
	?>
    </div>
    <div class="right">
    <?php
	foreach ($articles['right'] as $article):
		$text = $article['text'];
		if (validate64($text))
			$text = base64_decode($text);
		
		$text = new BBCodeParser(stripslashes($text), 'newspaper_article', true);;
	?>
    	<div class="article_wrap">
            <div class="head"><?=View::NoHTML($article['title'])?></div>
            <div class="text"><?=$text->result?></div>
            <div class="bottom"><?=$langBase->get('comp-31', array('-PLAYER-' => View::Player(array('id' => $article['journalist'])), '-TIME-' => View::Time($article['added'], false, 'H:i')))?></div>
        </div>
    <?php
	endforeach;
	?>
    </div>
    <div class="clear"></div>
    <div class="bottom">
    <?php
	foreach ($articles['bottom'] as $article):
		$text = $article['text'];
		if (validate64($text))
			$text = base64_decode($text);
		
		$text = new BBCodeParser(stripslashes($text), 'newspaper_article', true);
	?>
    	<div class="article_wrap">
            <div class="head"><?=View::NoHTML($article['title'])?></div>
            <div class="text"><?=$text->result?></div>
            <div class="bottom"><?=$langBase->get('comp-31', array('-PLAYER-' => View::Player(array('id' => $article['journalist'])), '-TIME-' => View::Time($article['added'], false, 'H:i')))?></div>
        </div>
    <?php
	endforeach;
	?>
    </div>
</div>
<?php
	}
?>