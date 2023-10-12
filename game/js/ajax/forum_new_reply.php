<?php
	define('IS_AJAX', true);
	define(BASEPATH, true);
	require_once('../../../system/config.php');
	
	header($config['ajax_default_header']);
	
	
	if(! IS_ONLINE ){
		die(View::Message('ERROR', 2));
		
	}elseif( $config['limited_access'] == true ){
		die(View::Message($langBase->get('forum-34'), 2));
		
	}
	
	$text = $db->EscapeString($_POST['text']);
	$topic = $db->EscapeString($_POST['topic']);
	
	
	$sql = "SELECT id,forum_id,family,title,locked FROM `forum_topics` WHERE `id`='$topic'";
	$sql .= (Player::Data('level') < 3) ? " AND `deleted`='0'" : '';
	$sql = $db->Query($sql);
	$topic = $db->FetchArray($sql);
	
	
	$forum = $config['forums'][$topic['forum_id']];
	
	if( Player::Data('forumban') != "" && $forum[1] == false && $forum[5] < Player::Data('userlevel') ){
		die(View::Message($langBase->get('forum-05'), 2));
	}
	
	$last  = explode(",", Player::Data('last_forum_posts'));
	$timeleft = ($last[1] + $forum[4]) - time();
	
	if( $forum[1] == true ){
		$sql = $db->Query("SELECT name,active,members FROM `[families]` WHERE `id`='".$topic['family']."'");
		$fam = $db->FetchArray($sql);
		$members = unserialize($fam['members']);
		if( $fam['active'] == 0 ){
			die(View::Message($langBase->get('forum-36'), 2));
		}elseif( !$members[Player::Data('id')] && Player::Data('level') < 4 ){
			die(View::Message($langBase->get('forum-36'), 2));
		}
	}
	
	
	if( $timeleft > 0 && Player::Data('level') < 3 ){
		die(View::Message($langBase->get('forum-38', array('-TIME-' => $timeleft)), 2));
		
	}elseif( $topic['locked'] != 0 ){
		die(View::Message($langBase->get('forum-37'), 2));
	
	}elseif( ($topic['id'] == "") || (Player::Data('level') < $forum[5]) ){
		die(View::Message($langBase->get('forum-36'), 2));
		
	}elseif( View::Lenght($text) < $config['forum_reply_text_min_chars'] ){
		die(View::Message($langBase->get('msg-12', array('-NUM-' => $config['forum_reply_text_min_chars'])), 2));
		
	}elseif( View::Lenght($text) > $config['forum_reply_text_max_chars'] ){
		die(View::Message($langBase->get('forum-35', array('-NUM-' => $config['forum_reply_text_max_chars'])), 2));
		
	}
	
	
	
	$db->Query("INSERT INTO `forum_replies` (`topic_id`, `playerid`, `posted`, `text`, `userid`)VALUES('".$topic['id']."', '".Player::Data('id')."', '".time()."', '$text', '".User::Data('id')."')");
	$reply_id = mysql_insert_id($db->GetLinkIdentifier());
	
	$db->Query("UPDATE `forum_topics` SET `last_reply`='".time()."', `last_reply_playerid`='".Player::Data('id')."', `replies`=`replies`+'1' WHERE `id`='".$topic['id']."'");
	
	
	$num_posts = explode(",", Player::Data('forum_num_posts'));
	$num_replies = $num_posts[1] + 1;
	
	$db->Query("UPDATE `[players]` SET `last_forum_posts`='".$last[0].",".time()."', `forum_num_posts`='".$num_posts[0].",$num_replies' WHERE `id`='".Player::Data('id')."'");
	
	
	Accessories::AddToLog(Player::Data('id'), array('new_forumreply' => $reply_id));
	
	
	die('SUCCESS:'.$reply_id);
?>