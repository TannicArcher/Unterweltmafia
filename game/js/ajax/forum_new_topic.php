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

	$title = $db->EscapeString($_POST['title']);
	$text  = $db->EscapeString($_POST['text']);
	
	
	$f_id  = $db->EscapeString($_POST['f']);
	$forum = $config['forums'][$f_id];
	
	if( Player::Data('forumban') != "" && $forum[1] == false && $forum[5] < Player::Data('userlevel') ){
		die(View::Message($langBase->get('forum-05'), 2));
	}
	
	$last  = explode(",", Player::Data('last_forum_posts'));
	$timeleft = ($last[0] + $forum[3]) - time();

	
	$fam = (isset($_POST['fam'])) ? $db->EscapeString($_POST['fam']) : Player::Data('family');
	$result = $db->Query("SELECT id,name,boss,underboss FROM `[families]` WHERE `id`='$fam'");
	$fam = $db->FetchArray($result);
	
	
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
	
	$type = $db->EscapeString($_POST['type']);
	$type = empty($type) ? 1 : $type;
	$type = (IsForumAdmin(1) == true) ? $type : 1;
	
	
	
	if( $timeleft > 0 && Player::Data('level') <= 2 ){
		die( View::Message('Trebuie sa astepti '.$timeleft.' secunde inainte de a deschide un subiect!', 2) );
	
	}elseif( View::Lenght($title) < $config['forum_topic_title_min_chars'] ){
		die( View::Message($langBase->get('msg-10', array('-NUM-' => $config['forum_topic_title_min_chars'])), 2) );
		
	}elseif( View::Lenght($title) > $config['forum_topic_title_max_chars'] ){
		die( View::Message($langBase->get('msg-11', array('-NUM-' => $config['forum_topic_title_max_chars'])), 2) );
		
	}elseif( View::Lenght($text) < $config['forum_topic_text_min_chars'] ){
		die( View::Message($langBase->get('msg-12', array('-NUM-' => $config['forum_topic_text_min_chars'])), 2) );
		
	}elseif( View::Lenght($text) > $config['forum_topic_text_max_chars'] ){
		die( View::Message($langBase->get('forum-35', array('-NUM-' => $config['forum_topic_text_max_chars'])), 2) );
		
	}elseif( !in_array($type, array(1, 2)) ){
		die( View::Message('ERROR', 2) );
		
	}elseif( ($forum[2] == "") || ($forum[1] == true && Player::Data('family') == "") ){
		die( View::Message('ERROR', 2) );
		
	}
	
	
	$family = ($forum[1] == true) ? $fam['id'] : '';
	
	$pre_title = User::Data('userlevel') >= 4 ? $db->EscapeString($_POST['pre_title']) : '';
	$f_lang = $_POST['lng'] == 1 ? 'RO' : 'EN';
	
	$db->Query("INSERT INTO `forum_topics` (`playerid`, `forum_id`, `family`, `title`, `text`, `posted`, `type`, `last_reply`, `userid`, `pre_title`, `f_lang`)VALUES('".Player::Data('id')."', '$f_id', '$family', '$title', '$text', '".time()."', '$type', '".time()."', '".User::Data('id')."', '".$pre_title."', '".$f_lang."')");
	$topic_id = mysql_insert_id($db->GetLinkIdentifier());
	
	
	$num_posts = explode(",", Player::Data('forum_num_posts'));
	$num_topics = $num_posts[0] + 1;
	
	$db->Query("UPDATE `[players]` SET `last_forum_posts`='".time().",".$last[1]."', `forum_num_posts`='$num_topics,".$num_posts[1]."' WHERE `id`='".Player::Data('id')."'");
	
	Accessories::AddToLog(Player::Data('id'), array('new_forumtopic' => $topic_id));
	
	die('SUCCESS|'.$topic_id);
?>