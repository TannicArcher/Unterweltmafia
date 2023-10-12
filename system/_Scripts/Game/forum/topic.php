<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$topic_id = $db->EscapeString($_GET['id']);
	
	$sql  = "SELECT * FROM `forum_topics` WHERE `id`='$topic_id'";
	$sql .= (Player::Data('level') < 3) ? " AND `deleted`='0'" : '';
	$topic = $db->QueryFetchArray($sql);
	
	if( $topic['id'] == "" ){ View::Message($langBase->get('forum-04'), 2, true, '/game/?side=forum/'); }
	
	
	$forum_id = $topic['forum_id'];
	$forum = $config['forums'][$forum_id];
	
	if( Player::Data('level') < $forum[5] || $forum[0] == "" ){
		View::Message($langBase->get('forum-04'), 2, true, '/game/?side=forum/');
	}

	if( $forum[1] == true ){
		$sql = "SELECT id,name,boss,underboss,active FROM `[families]` WHERE `id`='".$topic['family']."'";
		$fam = $db->QueryFetchArray($sql);
		if( $fam['id'] != Player::Data('family') && Player::Data('level') < 4 ){
			View::Message($langBase->get('forum-04'), 2, true, '/game/?side=forum/');
		}
		if( $fam['active'] == 0 ){
			$db->Query("UPDATE `forum_topics` SET `deleted`='1' WHERE `family`='".$fam['id']."'");
			if( Player::Data('level') < 4 ){
				View::Message($langBase->get('forum-04'), 2, true, '/game/?side=forum/');
			}
		}
		
		$forum[0] = $fam['name'];
	}
	
	
	
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
	
	
	$views = unserialize($topic['views']);
	
	$last_view = $views[User::Data('id')];
	if( $topic['last_reply'] > $last_view ){
		$views[User::Data('id')] = time();
		$views = serialize($views);
		$db->Query("UPDATE `forum_topics` SET `views`='$views' WHERE `id`='".$topic['id']."'");
	}

	$sql = "SELECT name,profileimage,rank,forum_num_posts,forum_signature,id,level,health FROM `[players]` WHERE `id`='".$topic['playerid']."'";
	$topic_writer = $db->QueryFetchArray($sql);
	
	$writer_num_posts = explode(",", $topic_writer['forum_num_posts']);
	$writer_num_posts = View::CashFormat($writer_num_posts[0]+$writer_num_posts[1]);
	
	$topic_text = $topic['text'];
	$topic_text = new BBCodeParser($topic_text, 'forum', true);
	$topic_text = $topic_text->result;
	
	$select = (isset($_GET['fr']) && !isset($_GET['deleted'])) ? "id,read_by" : "*";
	$sql = "SELECT $select FROM `forum_replies` WHERE `topic_id`='".$topic['id']."'";
	
	if( Player::Data('level') > 2 ){
		$sql .= !isset($_GET['deleted']) ? " AND `deleted`='0'" : '';
	}else{
		$sql .= " AND `deleted`='0'";
	}
	
	$sql .= " ORDER BY id ASC";
	
	
	$replies_per_page = User::Data('forum_replies_per_page');
	$pagination = new Pagination($sql, $replies_per_page, 'p');
	
	$topic_replies = $pagination->GetSQLRows();
	$topic_replies_all = (isset($_GET['fr']) && !isset($_GET['deleted'])) ? $pagination->GetSQLRows('all') : '';
	
	$pagination_links = $pagination->GetPageLinks();
	
	
	
	if( isset($_GET['fr']) && !isset($_GET['deleted']) ){
		
		$f_reply = $db->EscapeString($_GET['fr']);
	
		if( !empty($f_reply) ){
			
			$sql = "SELECT id,read_by FROM `forum_replies` WHERE `id`='$f_reply' AND `topic_id`='".$topic['id']."' AND `deleted`='0'";
			$f_reply = $db->QueryFetchArray($sql);
			
			if( $f_reply['id'] != "" ){
				
				for( $i = 1; $i <= $pagination->num_rows; $i++ )
				{
					if( $topic_replies_all[($i-1)]['id'] == $f_reply['id'] ){
						$f_reply_num = $i;
						break;
					}
				}
				
			}
			
			$page = ceil($f_reply_num / $replies_per_page);
			$location = "/game/?side=".$_GET['side']."&id=".$_GET['id']."&p=".$page."#r_".$f_reply['id'];
			
		}else{

			for( $i = 1; $i <= $pagination->num_rows; $i++ )
			{
				$reply = $topic_replies_all[($i-1)];
				$read_by = unserialize($reply['read_by']);
				if( !in_array(Player::Data('id'), $read_by) ){
					$f_reply_num = $i;
					break;
				}
			}
			//exit;
			$page = ceil($f_reply_num / $replies_per_page);
			$location = (count($read_by) <= 0) ? "/game/?side=".$_GET['side']."&id=".$_GET['id']."&p=".$pagination->current_page : "/game/?side=".$_GET['side']."&id=".$_GET['id']."&p=".$page."#r_".$reply['id'];
			
			unset($reply, $read_by);
			
		}
		
		header("Location: $location");
		exit;
		
	}
?>
<script type="text/javascript" src="<?=$config['base_url']?>js/forum.js"></script>
<ul class="breadcrumb left">
	<li class="home"><a href="<?=$config['base_url']?>?side=forum/"><img src="<?=$config['base_url']?>images/icons/home_icon.png" alt="" /></a></li>
    <li><a href="<?=$config['base_url']?>?side=forum/forum&amp;f=<?=$forum_id?><?php echo ($topic['family'] != "") ? '&amp;family='.$topic['family'] : ''; ?>"><?=$forum[0]?></a></li>
    <li class="last"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$_GET['id']?>&amp;p=<?=$pagination->current_page?>"><?=View::NoHTML($topic['title'])?></a></li>
</ul>
<?php
	if( Player::Data('level') > 2 ){
		$href = $config['base_url']."?side=".$_GET['side']."&amp;id=".$_GET['id']."&amp;p=".$pagination->current_page;
		if( isset($_GET['deleted']) ){
			$value = $langBase->get('forum-21');
		}else{
			$href .= "&amp;deleted";
			$value = $langBase->get('forum-22');
		}
		echo '<div class="right" style="margin-top: 22px;"><a href="' . $href . '" class="button">' . $value . '</a></div>';
	}
?>
<div class="clear"></div>

<div class="forum_post topic" id="t_<?=$topic['id']?>">
	<div class="overlay" id="overlay_t_<?=$topic['id']?>"></div>
	<div class="post_top">
    	<div class="left_links">
        	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$_GET['id']?>">#1</a>
        	<?php echo (Player::Data('level') > 2 && $topic['reported'] == 1) ? '<a href="'.$config['base_url'].'?side=forum/reports">Raporteaza</a>' : ''; ?>
        </div>
    	<div class="links">
        	<?php if( IsForumAdmin(1) == true ){ ?>
            <a href="#" rel="<?=$topic['id']?>" class="<?php echo ($topic['type'] == 1) ? 'set_sticky' : 'undo_sticky'; ?> sticky_change"><img src="<?=$config['base_url']?>images/icons/page_attach.png" alt="Sticky" /> <?php echo ($topic['type'] == 1) ? $langBase->get('forum-23') : $langBase->get('forum-24'); ?></a>
            <a href="#" rel="<?=$topic['id']?>" class="<?php echo ($topic['locked'] == 0) ? 'lock' : 'unlock'; ?> lock_change"><img src="<?=$config['base_url']?>images/icons/lock<?php echo ($topic['locked'] == 1) ? '_open' : ''; ?>.png" alt="" /> <?php echo ($topic['locked'] == 0) ? $langBase->get('forum-25') : $langBase->get('forum-26'); ?></a>
        	<?php } if( IsForumAdmin(1) == true || $topic['playerid'] == Player::Data('id') ){ ?>
            <a href="#" rel="<?=$topic['id']?>" class="edit t"><img src="<?=$config['base_url']?>images/icons/page_edit.png" alt="" /> <?=$langBase->get('forum-27')?></a>
            <?php if( $topic['deleted'] == 0 ){ ?><a href="#" rel="<?=$topic['id']?>" class="delete t"><img src="<?=$config['base_url']?>images/icons/page_delete.png" alt="" /> <?=$langBase->get('txt-36')?></a><?php }else{ ?><a href="#" rel="<?=$topic['id']?>" class="recreate t"><img src="<?=$config['base_url']?>images/icons/page_add.png" alt="" /> <?=$langBase->get('forum-28')?></a><?php } ?>
            <?php } ?>
        </div>
    	<h1><?php echo ($topic['deleted'] != 0) ? '<span style="color: #ac0000;" title="">'.View::NoHTML($topic['title']).'</span>' : View::NoHTML($topic['title']); echo ($topic['locked'] != 0) ? ' <span style="color: #401a00;">(Blocat)</span>' : ''; ?></h1>
        <h2><?=$langBase->get('forum-29')?> <b><?=View::Time($topic['posted'])?></b></h2>
    </div>
    <div class="post_content">
    	<div class="post_left">
        	<div class="profileimage">
            	<a href="<?=$config['base_url']?>s/<?=$topic_writer['name']?>"><img src="<?=$topic_writer['profileimage']?>" alt="<?=$topic_writer['name']?>" class="profileimg" /></a>
                <div><?=View::Player($topic_writer)?></div>
            </div>
            <div class="playerstats">
                <b><?=$langBase->get('ot-rank')?>:</b> <?=$config['ranks'][$topic_writer['rank']][0]?><br />
                <b><?=$langBase->get('function-messages')?>:</b> <?=$writer_num_posts?>
            </div>
        </div>
        <div class="post_right">
        	<div class="topic_text">
        		<?=$topic_text?>
            </div>
        </div>
        <div class="clear"></div>
        <?php
        	if( !empty($topic_writer['forum_signature']) && User::Data('forum_view_signatures') == 1 ){
				
				$signature = $topic_writer['forum_signature'];
				$signature = new BBCodeParser($signature, 'forumsig', true, array(), 150);
				$signature = $signature->result;
				
				echo "<div class=\"bottom signature\">".$signature."</div>\n";
			}
			
			echo !empty($topic['last_edit_playerid']) ? "<div class=\"bottom last_edit\">".$langBase->get('forum-39').": ".View::Player(array('id' => $topic['last_edit_playerid']))." - ".View::Time($topic['last_edit_time'], true).".</div>\n" : '';
		?>
    </div>
    <div class="post_bottom"></div>
    <div class="post_bottom_menu">
    	<ul class="left">
        	<li><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$_GET['id']?>&amp;p=<?=$pagination->GetNumPages()?>&amp;post_reply&amp;quote=t|<?=$topic['id']?>"><img src="<?=$config['base_url']?>images/icons/page_copy.png" alt="" /><?=$langBase->get('forum-30')?></a></li>
            <li><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$_GET['id']?>&amp;p=<?=$pagination->GetNumPages()?>&amp;post_reply"><img src="<?=$config['base_url']?>images/icons/page_add.png" alt="" /><?=$langBase->get('forum-31')?></a></li>
        </ul>
        <ul class="right menu">
        	<li class="opt_4"><a href="#" rel="<?=$topic['id']?>" class="t"><img src="<?=$config['base_url']?>images/icons/flag_red.png" alt="" /><?=$langBase->get('forum-32')?></a></li>
        </ul>
        <div class="clear"></div>
    </div>
</div>

<?php
	echo ($pagination->num_rows > 0) ? "<div style=\"margin: 0 0 18px 0;\">$pagination_links</div>" : '';
	
	
	$reply_num = 1;
	$reply_num_add = ($pagination->current_page <= 2) ? $replies_per_page : ($replies_per_page * $pagination->current_page) - $replies_per_page;
	
	foreach( $topic_replies as $reply ):
		
		$sql = "SELECT name,profileimage,rank,forum_num_posts,forum_signature,id,level,health FROM `[players]` WHERE `id`='".$reply['playerid']."'";
		$reply_writer = $db->QueryFetchArray($sql);
		
		$reply_text = $reply['text'];
		$reply_text = new BBCodeParser($reply_text, 'forum', true);
		$reply_text = $reply_text->result;
		
		$writer_num_posts = explode(",", $reply_writer['forum_num_posts']);
		$writer_num_posts = View::CashFormat($writer_num_posts[0]+$writer_num_posts[1]);
		
		$views = unserialize($reply['read_by']);
		if( !in_array(User::Data('id'), $views) ){
			$views[] = User::Data('id');
			$views = serialize($views);
			$db->Query("UPDATE `forum_replies` SET `read_by`='$views' WHERE `id`='".$reply['id']."'");
		}
		
		$reply_num++;
		$reply_num = ($pagination->current_page <= 1) ? $reply_num : $reply_num + $reply_num_add;
		$reply_num_add = 0;
?>
<div class="forum_post" id="r_<?=$reply['id']?>">
	<div class="overlay" id="overlay_r_<?=$reply['id']?>"></div>
	<div class="post_top">
    	<div class="left_links">
        	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$_GET['id']?>&amp;fr=<?=$reply['id']?>">#<?=$reply_num?></a>
        	<?php echo (Player::Data('level') > 2 && $reply['reported'] == 1) ? '<a href="'.$config['base_url'].'?side=forum/reports">'.$langBase->get('forum-33').'</a>' : ''; ?>
        </div>
    	<div class="links">
        	<?php if( IsForumAdmin(1) == true || $reply['playerid'] == Player::Data('id') ){ ?>
            <a href="#" rel="<?=$reply['id']?>" class="edit r"><img src="<?=$config['base_url']?>images/icons/page_edit.png" alt="" /> <?=$langBase->get('forum-27')?></a>
            <?php if( $reply['deleted'] == 0 ){ ?><a href="#" rel="<?=$reply['id']?>" class="delete r"><img src="<?=$config['base_url']?>images/icons/page_delete.png" alt="" /> <?=$langBase->get('txt-36')?></a><?php }else{ ?><a href="#" rel="<?=$reply['id']?>" class="recreate r"><img src="<?=$config['base_url']?>images/icons/page_add.png" alt="" /> <?=$langBase->get('forum-28')?></a><?php } ?>
            <?php } ?>
        </div>
    	<h1><?php echo ($reply['deleted'] != 0) ? '<span style="color: #ac0000;">'.$langBase->get('txt-36').'</span>' : ''; ?></h1>
        <h2><?=$langBase->get('forum-29')?> <b><?=View::Time($reply['posted'])?></b></h2>
    </div>
    <div class="post_content">
    	<div class="post_left">
        	<div class="profileimage">
            	<a href="<?=$config['base_url']?>s/<?=$reply_writer['name']?>"><img src="<?=$reply_writer['profileimage']?>" alt="<?=$reply_writer['name']?>" class="profileimg" /></a>
                <div><?=View::Player($reply_writer)?></div>
            </div>
            <div class="playerstats">
                <b><?=$langBase->get('ot-rank')?>:</b> <?=$config['ranks'][$reply_writer['rank']][0]?><br />
                <b><?=$langBase->get('function-messages')?>:</b> <?=$writer_num_posts?>
            </div>
        </div>
        <div class="post_right">
        	<div class="topic_text">
        		<?=$reply_text?>
            </div>
        </div>
        <div class="clear"></div>
        <?php
        	if( !empty($reply_writer['forum_signature']) && User::Data('forum_view_signatures') == 1 ){
				
				$signature = $reply_writer['forum_signature'];
				$signature = new BBCodeParser($signature, 'forumsig', true, array(), 150);
				$signature = $signature->result;
				
				echo "<div class=\"bottom signature\">".$signature."</div>\n";
			}
			
			echo !empty($reply['last_edit_playerid']) ? "<div class=\"bottom last_edit\">".$langBase->get('forum-39').": ".View::Player(array('id' => $reply['last_edit_playerid']))." - ".View::Time($reply['last_edit_time'], true).".</div>\n" : '';
		?>
    </div>
    <div class="post_bottom"></div>
    <div class="post_bottom_menu">
    	<ul class="left">
        	<li><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$_GET['id']?>&amp;p=<?=$pagination->GetNumPages()?>&amp;post_reply&amp;quote=r|<?=$reply['id']?>"><img src="<?=$config['base_url']?>images/icons/page_copy.png" alt="" /><?=$langBase->get('forum-30')?></a></li>
            <li><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;id=<?=$_GET['id']?>&amp;p=<?=$pagination->GetNumPages()?>&amp;post_reply"><img src="<?=$config['base_url']?>images/icons/page_add.png" alt="" /><?=$langBase->get('forum-31')?></a></li>
        </ul>
        <ul class="right menu">
        	<li class="opt_4"><a href="#" rel="<?=$reply['id']?>" class="r"><img src="<?=$config['base_url']?>images/icons/flag_red.png" alt="" /><?=$langBase->get('forum-32')?></a></li>
        </ul>
        <div class="clear"></div>
    </div>
</div>
<?php
	endforeach;
	
	/*
		Opprett nytt innlegg
	*/
	if( isset($_GET['post_reply']) && $topic['locked'] == 0 ){
?>
<script type="text/javascript">
	<!--
	window.addEvent('domready', function()
	{
		document.location.hash = 'pr_form';
		
		var result = $("new_reply_result");
		var text = $("reply_text");
		
		text.focus();
		
		$$('#pr_form form').addEvent('submit', function(e)
		{
			new Event(e).stop();
			
			if (this.xhr)
			{
				this.xhr.cancel();
			}
			
			result.set('html', '<img src="<?=$config['base_url']?>images/ajax_load_small.gif" alt="Se incarca..." />');
			
			this.xhr = new Request({ url: '/game/js/ajax/forum_new_reply.php', data: { text: text.value, topic: '<?=$topic['id']?>' }, method: 'post' });
			this.xhr.addEvents(
			{
				success: function(data)
				{
					var res = data.split(':');
					
					if (res[0] == 'SUCCESS')
					{
						NavigateTo('/game/?side=<?=$_GET['side']?>&id=<?=$topic['id']?>&p=<?=$pagination->GetNumPages(1)?>#r_' + res[1]);
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
<?php
	$value = '';
	
	if( isset($_GET['quote']) ){
		
		$quote = explode("|", $db->EscapeString($_GET['quote']));
		
		if( $quote[0] == 't' ){
			$sql = "SELECT text,playerid FROM `forum_topics` WHERE `id`='".$topic['id']."'";
		}elseif( $quote[0] == 'r' ){
			$sql = "SELECT text,playerid FROM `forum_replies` WHERE `id`='".$quote[1]."' AND `topic_id`='".$topic['id']."'";
			$sql .= (Player::Data('level') < 3) ? " AND `deleted`='0'" : '';
		}
		
		$result = $db->QueryFetchArray($sql);
		
		$value = ($result['text'] != "") ? "[quote]".$result['text']."[/quote]\n" : "[quote]".$db->EscapeString($_GET['quote'])."[/quote]\n";
	}
?>
<div class="main" id="pr_form">
	<h1 class="heading"><?=$langBase->get('forum-31')?></h1>
    <form action="" method="post">
    	<div id="new_reply_result" style="margin: 10px;"></div>
    	<dl class="form">
        	<dt><?=$langBase->get('txt-39')?></dt>
            <dd><textarea id="reply_text" cols="87" rows="15" style="width: 510px; height: 200px;"><?=$value?></textarea></dd>
        </dl>
        <div class="center clear">
        	<input type="submit" value="<?=$langBase->get('txt-14')?>" />
        </div>
    </form>
</div>
<?php
	}
?>
<div style="margin: 10px;"><?=$pagination_links?></div>
