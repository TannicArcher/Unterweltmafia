<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$forum_id = $db->EscapeString($_GET['f']);
	$forum    = $config['forums'][$forum_id];
	
	if( $forum[2] == "" ){
		View::Message($langBase->get('forum-03'), 2, true, '/game/?side=forum/');
		
	}elseif( $forum[1] == true && Player::Data('family') == 0 && IsForumAdmin(2) == false ){
		View::Message($langBase->get('forum-03'), 2, true, '/game/?side=forum/');
		
	}elseif( $forum[5] > User::Data('userlevel') ){
		View::Message($langBase->get('forum-04'), 2, true, '/game/?side=forum/');
		
	}elseif( $forum[6] == true && Player::Data('forumban') != "" ){
		View::Message($langBase->get('forum-05'), 2, true, '/game/?side=forum/&f='.$forum_id);
		
	}
	
	if(isset($_GET['lng'])){
		if (@$languages_supported[$_GET['lng']])
		{
			$langBase_flang = $_GET['lng'];
		}
		else
		{
			$langBase_flang = '';
		}
	}
	
	$flng = ($langBase_flang != '' ? " AND `f_lang`='".$langBase_flang."'" : "");
	
	$sql = "SELECT * FROM `forum_topics` WHERE `forum_id`='".$forum_id."'".$flng."";
	
	if( Player::Data('level') > 2 ){
		$sql .= !isset($_GET['deleted']) ? " AND `deleted`='0'" : '';
	}else{
		$sql .= " AND `deleted`='0'";
	}
	
	if( $forum[1] == true ){
		
		$fam = isset($_GET['family']) ? $db->EscapeString($_GET['family']) : Player::FamilyData('id');
		$result = $db->Query("SELECT id,name,boss,underboss FROM `[families]` WHERE `id`='$fam'");
		$fam = $db->FetchArray($result);
		
		if( isset($_GET['family']) && Player::Data('level') > 3 ){

			$fam_name = $fam['name'];
			
			if( $fam['id'] == "" ){
				$sql .= ($forum[1] == true) ? " AND `family`='".Player::FamilyData('id')."'" : '';
				$fam_name = Player::Data('family');
			}else{
				$sql .= ($forum[1] == true) ? " AND `family`='".$fam['id']."'" : '';
				$forum[0] = $fam['name'];
			}
		}else{
			$sql .= ($forum[1] == true) ? " AND `family`='".Player::FamilyData('id')."'" : '';
			$fam_name = Player::Data('family');
		}
		
	}
	
	$sql .= " ORDER BY type DESC, pre_title DESC, last_reply DESC";
	$pagination = new Pagination($sql, 20, 'p');
	$forum_posts = $pagination->GetSQLRows();
	$pagination_links = $pagination->GetPageLinks();
	
	function IsForumAdmin($type)
	{
		$forum = $GLOBALS['forum'];
		$fam = $GLOBALS['fam'];
		if( $type == 1 && Player::Data('level') > 2 ){
			return true;
		}elseif( $type == 2 && Player::Data('level') > 3 ){
			return true;
		}else{
			if($type == 1){
				if( $forum[1] == true ){
					if( $fam['boss'] == Player::Data('id') ){
						return true;
					}elseif( $fam['underboss'] == Player::Data('id') ){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
	}
	
	
	
	if( isset($_GET['empty']) && IsForumAdmin(1) == true ){
		$sql = "UPDATE `forum_topics` SET `deleted`='1' WHERE `forum_id`='".$forum_id."' AND `type`!='2' AND `deleted`='0'";
		$sql .= ($forum[1] == true) ? " AND `family`='".$fam['id']."'" : "";
		$db->Query($sql);
		
		$location  = '/game/?side='.$_GET['side'].'&f='.$_GET['f'];
		$location .= ($forum[1] == true) ? '&family='.$fam['id'] : '';
		View::Message($langBase->get('forum-06').' '.$forum[0].'.', 1, true, $location);
	}
	
	if ( IsForumAdmin(1) == true)
	{
?>
<ul id="main_submenu">
    <li><a href="<?=$config['base_url']?>?side=forum/banned_players" class="noimg"><?=$langBase->get('forum-07')?></a></li>
    <li><a href="<?=$config['base_url']?>?side=forum/reports" class="noimg"><?=$langBase->get('forum-08')?></a></li>
    <li><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;f=<?=$_GET['f']?><?php echo ($forum[1] == true && isset($_GET['family'])) ? '&amp;family='.$_GET['family'] : ''; echo isset($_GET['deleted']) ? '' : '&amp;deleted'; ?>" class="noimg"><?php echo isset($_GET['deleted']) ? $langBase->get('forum-09') : $langBase->get('forum-10'); ?></a></li>
    <li><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;f=<?=$_GET['f']?>&amp;family=<?=$fam_name?>&amp;empty" class="noimg" onclick="return confirm('<?=$langBase->get('err-05')?>')"><?=$langBase->get('forum-11')?></a></li>
</ul>
<div class="clear"></div>
<?php
	}
?>
<style type="text/css">
	<!--
	#button_new_topic {
		width: 124px;
		height: 29px;
		background: url(../../../../game/images/forum_createTopic_button.png) center no-repeat;
		border: none;
		text-shadow: #000000 -1px -1px -1px !important;
	}
	#button_filter {
		width: 94px;
		height: 29px;
		background: url(../../../../game/images/forum_filter_button.png) center no-repeat;
		border: none;
		text-shadow: #000000 -1px -1px -1px !important;
	}
	-->
</style>
<script type="text/javascript">
	<!--
	window.addEvent('domready', function()
	{
		var container = $('new_topic');
		var title = container.getElement('#new_topic_title');
		var text = container.getElement('#new_topic_text');
		var result = container.getElement('#new_topic_result');
		
		$('button_new_topic').addEvent('click', function()
		{
			this.toggleClass('active');
			container.toggleClass('hidden');
		});
		
		$('createTopic_form').addEvent('submit', function(e)
		{
			new Event(e).stop();
			
			if (this.xhr)
			{
				this.xhr.cancel();
			}
			
			result.set('html', '<img src="<?=$config['base_url']?>images/ajax_load_small.gif" alt="Se incarca..." />');
			
			var data =
			{
				title: title.get('value'),
				text: text.value,
				f: '<?=$_GET['f']?>',
				fam: '<?=$fam_name?>'
				<?php echo ',lng: $(\'f_lang\').value';?>
				<?php if(IsForumAdmin(1) == true) echo ',type: $(\'new_topic_type\').value';?>
				<?php if (User::Data('userlevel') >= 4) echo ',pre_title: $(\'new_topic_pre_title\').get(\'value\')';?>
			}
			
			this.xhr = new Request({ 'url': '/game/js/ajax/forum_new_topic.php', 'data': data, 'method': 'post' });
			this.xhr.addEvents(
			{
				success: function(data)
				{
					var res = data.split('|');
					
					if (res[0] == 'SUCCESS')
					{
						NavigateTo('/game/?side=forum/topic&id=' + res[1]);
					}
					else
					{
						result.set('html', data);
						detect_counters(result);
					}
				},
				failure: function(data)
				{
					result.set('html', 'ERROR: ' + data);
				}
			});
			this.xhr.send();
		});
	});
	-->
</script>
<script type="text/javascript">
function goSelect(selectobj){
 window.location.href='<?=$config['base_url']?>?side=forum/forum&f=<?=$forum_id?>&lng='+selectobj
}
</script>
<div id="new_topic" class="main hidden">
	<h1 class="heading"><?=$langBase->get('forum-12')?> - <?=$forum[0]?></h1>
    <form action="" method="post" id="createTopic_form">
    	<div id="new_topic_result" style="margin: 10px;"></div>
    	<dl class="form">
        	<dt><?=$langBase->get('txt-38')?></dt>
            <dd><select id="f_lang"><option value="1"<?=($langBase_lang == 'RO' ? ' selected' : '')?>>DE</option><option value="2"<?=($langBase_lang == 'EN' ? ' selected' : '')?>>EN</option><option value="3"<?=($langBase_lang == 'FR' ? ' selected' : '')?>>FR</option></select> <?php if (User::Data('userlevel') >= 4) echo '<input type="text" id="new_topic_pre_title" class="styled" style="width: 30px;" /> ';?><input type="text" id="new_topic_title" class="styled" style="width: <?php if(User::Data('userlevel') >= 4){ echo '300px;'; }else{ echo '460px';}?>" maxlength="<?=$config['forum_topic_title_max_chars']?>" /></dd>
            <dt><?=$langBase->get('txt-39')?></dt>
            <dd><textarea id="new_topic_text" cols="85" rows="20" style="width: 520px; height: 300px;"></textarea></dd>
            <?php echo (IsForumAdmin(1) == true) ? '<dt>'.$langBase->get('txt-29').'</dt><dd><select id="new_topic_type"><option value="1">'.$langBase->get('forum-14').'</option><option value="2">'.$langBase->get('forum-13').'</option></select></dd>' : ''; ?>
        </dl>
        <div class="center clear">
        	<input type="submit" value="<?=$langBase->get('txt-14')?>" />
        </div>
    </form>
</div>
<div style="margin: 0 0 10px 5px; display:inline-block"><input type="submit" value="<?=$langBase->get('forum-12')?>" id="button_new_topic" /></div>
<div style="float: right; margin-top: 15px; margin-right: 10px;"><select onChange="goSelect(this.value)"><option value=""<?=($_GET['lng'] == '' ? ' selected' : '')?>><?=$langBase->get('forum-20')?>...</option><option value="RO"<?=($_GET['lng'] == 'RO' ? ' selected' : '')?>>DE</option><option value="EN"<?=($_GET['lng'] == 'EN' ? ' selected' : '')?>>EN</option><option value="FR"<?=($_GET['lng'] == 'FR' ? ' selected' : '')?>>FR</option></select></div>
<?php if( Player::Data('level') > 2 ){ ?>
<div class="clear"></div>
<?php
	}
	
	if( $forum_id == 5 && IsForumAdmin(2) == true ){

		$sql = $db->Query("SELECT id,name,active FROM `[families]` ORDER BY active DESC, id DESC");
		$families = $db->FetchArrayAll($sql);
?>
<div class="right">
	<form method="get" action="<?=$config['base_url']?>">
		<input type="hidden" name="side" value="<?=$_GET['side']?>" />
		<input type="hidden" name="f" value="<?=$_GET['f']?>" />
		<?=$langBase->get('ot-family')?>: <select name="family" onchange="this.form.submit()"><?php foreach($families as $family){ $selected = ($family['name'] == $forum[0]) ? ' selected="selected"' : ''; echo '<option'.$selected.' value="'.$family['id'].'">'.($family['active'] == 1 ? 'Activ' : 'Inactiv').' - '.$family['name'].'</option>'; } ?></select>
	</form>
</div>
<div class="clear"></div>
<?php
	}
	
	
	if( $pagination->num_rows <= 0 ){
		echo '<h2 class="center">'.$langBase->get('forum-15').'</h2>';
		
	}else{
?>
<style type="text/css">
	<!--
	.forum_wrapper {
		width: 629px;
		margin: 0px auto;
	}
	.forum_wrapper ul.topics, .forum_wrapper ul.topics li {
		list-style: none;
		margin: 0;
		padding: 0;
	}
	.forum_wrapper ul.topics li {
		margin-top: 1px;
		position: relative;
	}
	.forum_wrapper ul.topics li a.topic {
		display: block;
		position: relative;
		height: 31px;
		width: 629px;
		background: url(../../../../game/images/forum_topic_background.png) center no-repeat;
		opacity: 0.9;
		filter: alpha(opacity=90);
		color: #555555;
		text-decoration: none;
		overflow: hidden;
	}
	.forum_wrapper ul.topics li a.topic.op {
		opacity: 0.7;
		filter: alpha(opacity=70);
	}
	.forum_wrapper ul.topics li a.topic:hover {
		opacity: 1;
		filter: alpha(opacity=100);
		color: #555555;
		text-decoration: none;
	}
	
	.forum_wrapper ul.topics li a.topic img.post_image {
		position: absolute;
		left: 5px;
		top: 6px;
	}
	.forum_wrapper ul.topics li a.topic span.post_title {
		position: absolute;
		left: 32px;
		top: 9px;
		font-size: 12px;
		color: #999999;
	}
	.forum_wrapper ul.topics li a.topic span.post_title .sticky { color: #ff6600; font-size: 10px; font-weight: bold; opacity: 0.6; filter: alpha(opacity=60); }
	.forum_wrapper ul.topics li a.topic span.post_replies {
		position: absolute;
		left: 315px;
		top: 9px;
	}
	.forum_wrapper ul.topics li a.topic .nicks {
		position: absolute;
		left: 400px;
		width: 220px;
		top: 9px;
		display: block;
	}
	.forum_wrapper ul.topics li a.topic span.nicks .post_lastReply {
		position: absolute;
		right: 0;
	}
	-->
</style>
<div class="forum_wrapper">
	<ul class="topics">
    	<?php
		foreach($forum_posts as $topic)
		{
			$views = unserialize($topic['views']);
			$last_view = $views[User::Data('id')];
			
			$img  = '<img class="post_image" src="'.$config['base_url'].'images/';
			if( $topic['last_reply'] > $last_view ){
				$img .= 'forum_new_post.png" alt="" title="'.$langBase->get('forum-16').'"';
				$fr = !empty($last_view) ? '&amp;fr' : '';
			}elseif( $topic['locked'] > 0 ){
				$img .= 'forum_locked_post.png" alt="" title="'.$langBase->get('forum-17').'"';
				$fr = '';
			}else{
				$img .= 'forum_post.png" alt="" title="'.$langBase->get('forum-18').'"';
				$fr = '';
			}
			$img .= ' />';
			
			$author = $db->QueryFetchArray("SELECT name FROM `[players]` WHERE `id`='".$topic['playerid']."'");
			$lastReply = $db->QueryFetchArray("SELECT name FROM `[players]` WHERE `id`='".$topic['last_reply_playerid']."'");
			
			$preTitle = false;
			if ($topic['type'] == 2)
				$preTitle = strtoupper($langBase->get('forum-13')).':';
			
			if ($preTitle == false && $topic['pre_title'] != '')
				$preTitle = $topic['pre_title'];
			
			if ($topic['f_lang'] == "RO")
				$topicNEU = "DE";
			if ($topic['f_lang'] == "EN")
				$topicNEU = "EN";
			if ($topic['f_lang'] == "FR")
				$topicNEU = "FR";
		
		?>
        <li>
        	<a href="<?=$config['base_url']?>?side=forum/topic&amp;id=<?=$topic['id']?><?=$fr?>" class="topic<?=($i++%2 ? '' : ' op')?>">
            	<span><?=$img?></span>
                <span class="post_title"><?=($preTitle != false ? '<span class="sticky">' . $preTitle . '</span> ' : '')?>[<?=View::NoHTML($topicNEU)?>] <?=View::NoHTML($topic['title'])?></span>
                <span class="post_replies"><?=View::CashFormat($topic['replies'])?> <?=$langBase->get('forum-19')?></span>
                <span class="nicks">
                	<span class="post_creator toggleHTML" title="<?=View::HowLongAgo($topic['posted'])?>"><?=$author['name']?></span>
                    <span class="post_lastReply"><?php echo $topic['last_reply_playerid'] == 0 ? 'N/A' : '<span class="toggleHTML" title="' . View::HowLongAgo($topic['last_reply']) . '">' . $lastReply['name'] . '</span>';?></span>
                </span>
            </a>
        </li>
        <?php
		}
		?>
    </ul>
</div>
<div style="margin: 20px;"><?=$pagination_links?></div>
<?php
	}
?>