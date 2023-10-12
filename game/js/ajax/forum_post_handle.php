<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	require_once('../../../system/config.php');
	
	header($config['ajax_default_header']);

	if(! IS_ONLINE ){
		die('ERROR:offline');
	}elseif( $config['limited_access'] == true ){
		die('ERROR:limited_access');
	}elseif( empty($_GET['task']) ){
		die('MISSING:"task"');
	}elseif( empty($_GET['type']) ){
		die('MISSING:"type"');
	}elseif( empty($_GET['id']) ){
		die('MISSING:"id"');
	}
	
	$task = $_GET['task'];
	$type = $_GET['type'];
	$post_id = $db->EscapeString($_GET['id']);

	$allowed_tasks = array('delete', 'recreate', 'edit_form', 'edit', 'lock', 'unlock', 'set_sticky', 'undo_sticky', 'report_form', 'report');
	$allowed_types = array('t', 'r');
	
	if( !in_array($task, $allowed_tasks) ){
		die('ERROR:bad_task');
	}elseif( !in_array($type, $allowed_types) ){
		die('ERROR:bad_type');
	}
	
	if( $type == 't' ){

		
		$sql  = "SELECT * FROM `forum_topics` WHERE `id`='$post_id'";
		$sql .= (Player::Data('level') < 3) ? " AND `deleted`='0'" : '';
		$topic = $db->QueryFetchArray($sql);
		
		if( $topic['id'] == "" ){ die('ERROR:bad_id'); }
		
		$forum_id = $topic['forum_id'];
		$forum = $config['forums'][$forum_id];
		
		if( Player::Data('forumban') != "" && $forum[1] == false && $forum[5] < Player::Data('userlevel') ){
			die('ERROR:limited_access');
		}
		
		$forum[0] = ($topic['family'] != "") ? $topic['family'] : $forum[0];
		
		if( Player::Data('level') < $forum[5] ){ die('ERROR:bad_id'); }
		
		
		if( $forum[1] == true ){
			$fam = $db->QueryFetchArray("SELECT id,name,boss,underboss,active FROM `[families]` WHERE `id`='".$topic['family']."'");
			if( $fam['id'] != Player::Data('family') && Player::Data('level') < 4 ){
				die('ERROR:bad_id');
			}
			if( $fam['active'] == 0 ){
				$db->Query("UPDATE `forum_topics` SET `deleted`='1' WHERE `family`='".$fam['id']."'");
				if( Player::Data('level') < 4 ){
					die('ERROR:bad_id');
				}
			}
		}
		
		function IsForumAdmin($type)
		{
			if( in_array($GLOBALS['task'], array('report_form', 'report')) ){
				return true;
			}
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
		
		if( IsForumAdmin(1) == true || $topic['playerid'] == Player::Data('id') ){

			if( $task == 'delete' ){

				if( $topic['deleted'] == 1 ){
					die('ERROR:'.$task);
				}
				$db->Query("UPDATE `forum_topics` SET `deleted`='1' WHERE `id`='".$topic['id']."'");
				Accessories::AddToLog(Player::Data('id'), array('deleted_forumtopic' => $topic['id']));
				die('SUCCESS:'.$task . ':' . $topic['forum_id']);
				
			}elseif( $task == 'recreate' ){

				if( Player::Data('level') < 3 || $topic['deleted'] == 0 ){
					die('ERROR:'.$task);
				}
				$db->Query("UPDATE `forum_topics` SET `deleted`='0' WHERE `id`='".$topic['id']."'");
				Accessories::AddToLog(Player::Data('id'), array('recreated_forumtopic' => $topic['id']));
				die('SUCCESS:'.$task);
				
			}elseif( $task == 'lock' && IsForumAdmin(1) == true ){

				if( $topic['locked'] == 1 ){
					die('<h2 class="center">Thread already closed!</h2>');
				}
				$db->Query("UPDATE `forum_topics` SET `locked`='1' WHERE `id`='".$topic['id']."'");
				Accessories::AddToLog(Player::Data('id'), array('locked_forumtopic' => $topic['id']));
				die('<h2 class="center">Ati inchis topic-ul!</h2>');
				
			}elseif( $task == 'unlock' && IsForumAdmin(1) == true ){

				if( $topic['locked'] == 0 ){
					die('<h2 class="center">Thread already opened!</h2>');
				}
				$db->Query("UPDATE `forum_topics` SET `locked`='0' WHERE `id`='".$topic['id']."'");
				Accessories::AddToLog(Player::Data('id'), array('unlocked_forumtopic' => $topic['id']));
				die('<h2 class="center">Ai deschis topic-ul!</h2>');
				
			}elseif( $task == 'set_sticky' && IsForumAdmin(1) == true ){

				if( $topic['type'] == 2 ){
					die('<h2 class="center">This thread is already sticky!</h2>');
				}
				$db->Query("UPDATE `forum_topics` SET `type`='2' WHERE `id`='".$topic['id']."'");
				Accessories::AddToLog(Player::Data('id'), array('forumtopic_sticky' => $topic['id']));
				die('<h2 class="center">Thread marked as sticky!</h2>');
				
			}elseif( $task == 'undo_sticky' && IsForumAdmin(1) == true ){

				if( $topic['type'] == 1 ){
					die('<h2 class="center">This thread is already basic thread!</h2>');
				}
				$db->Query("UPDATE `forum_topics` SET `type`='1' WHERE `id`='".$topic['id']."'");
				Accessories::AddToLog(Player::Data('id'), array('forumtopic_sticky' => $topic['id']));
				die('<h2 class="center">Successfully changed!</h2>');

			}
		}else{
			die('ERROR:bad_id');
		}
	}else{
		$sql = "SELECT * FROM `forum_replies` WHERE `id`='$post_id'";
		$sql .= (Player::Data('level') < 3) ? " AND `deleted`='0'" : '';
		$reply = $db->QueryFetchArray($sql);
		
		if( $reply['id'] == "" ){ die('ERROR:bad_id'); }
		
		$sql = $db->Query("SELECT * FROM `forum_topics` WHERE `id`='".$reply['topic_id']."'");
		$topic = $db->FetchArray($sql);
		
		
		$forum_id = $topic['forum_id'];
		$forum = $config['forums'][$forum_id];
		
		if( Player::Data('forumban') != "" && $forum[1] == false && $forum[5] < Player::Data('userlevel') ){
			die('ERROR:limited_access');
		}
		
		$forum[0] = ($topic['family'] != "") ? $topic['family'] : $forum[0];
		
		if( Player::Data('level') < $forum[5] ){ die('ERROR:bad_id'); }
		
		
		if( $forum[1] == true ){
			$sql = $db->Query("SELECT id,name,boss,underboss,active FROM `[families]` WHERE `id`='".$topic['family']."'");
			$fam = $db->FetchArray($sql);
			if( $fam['id'] != Player::Data('family') && Player::Data('level') < 4 ){
				die('ERROR:bad_id');
			}
			if( $fam['active'] == 0 ){
				$db->Query("UPDATE `forum_topics` SET `deleted`='1' WHERE `family`='".$fam['id']."'");
				if( Player::Data('level') < 4 ){
					die('ERROR:bad_id');
				}
			}
		}
		
		function IsForumAdmin($type)
		{
			if( in_array($GLOBALS['task'], array('report_form', 'report')) ){
				return true;
			}
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
		
		if( IsForumAdmin(1) == true || $reply['playerid'] == Player::Data('id') ){

			if( $task == 'delete' ){
				if( $reply['deleted'] == 1 ){
					die('ERROR:'.$task);
				}
				
				$sql = $db->Query("SELECT id,posted,playerid FROM `forum_replies` WHERE `topic_id`='".$topic['id']."' AND `deleted`='0' ORDER BY id DESC LIMIT 0,2");
				$last_reply = $db->FetchArrayAll($sql);
				
				$sqladd = $last_reply[0]['id'] == $reply['id'] ? ", `last_reply`='".$last_reply[1]['posted']."', `last_reply_playerid`='".$last_reply[1]['playerid']."'" : '';
				
				$db->Query("UPDATE `forum_topics` SET `replies`=`replies`-'1'$sqladd WHERE `id`='".$topic['id']."'");
				$db->Query("UPDATE `forum_replies` SET `deleted`='1' WHERE `id`='".$reply['id']."'");
				Accessories::AddToLog(Player::Data('id'), array('deleted_forumreply' => $reply['id']));
				
				die('SUCCESS:'.$task);
				
			}elseif( $task == 'recreate' ){

				if( Player::Data('level') < 3 || $reply['deleted'] == 0 ){
					die('ERROR:'.$task);
				}
				
				$sql = $db->Query("SELECT id,posted,playerid FROM `forum_replies` WHERE `topic_id`='".$topic['id']."' ORDER BY id DESC");
				$last_reply = $db->FetchArray($sql);
				
				$sqladd = $last_reply['id'] == $reply['id'] ? ", `last_reply`='".$last_reply['posted']."', `last_reply_playerid`='".$last_reply['playerid']."'" : '';
				
				$db->Query("UPDATE `forum_topics` SET `replies`=`replies`+'1'$sqladd WHERE `id`='".$topic['id']."'");
				$db->Query("UPDATE `forum_replies` SET `deleted`='0' WHERE `id`='".$reply['id']."'");
				Accessories::AddToLog(Player::Data('id'), array('recreated_forumreply' => $reply['id']));
				
				die('SUCCESS:'.$task);
			}
		}else{
			die('ERROR:bad_id');
		}
	}

	if( $task == 'edit_form' ){
		$post = ($type == 't') ? $topic : $reply;
?>
<form action="<?=$config['base_url']?>js/ajax/forum_post_handle.php?task=edit&type=<?=$_GET['type']?>&id=<?=$post['id']?>" method="post" id="edit_form">
	<div id="send_result" style="margin: 10px;"></div>
    <dl class="form">
    	<?php if( $type == 't' ){ ?>
        <dt><?=$langBase->get('txt-38')?></dt>
        <dd><select name="f_lang"><option value="1"<?=($post['f_lang'] == 'RO' ? ' selected' : '')?>>RO</option><option value="2"<?=($post['f_lang'] == 'EN' ? ' selected' : '')?>>EN</option><option value="3"<?=($post['f_lang'] == 'FR' ? ' selected' : '')?>>FR</option></select> <?php if (User::Data('userlevel') >= 4 ) echo '<input type="text" class="styled" style="width: 50px;" value="' . $post['pre_title'] . '" name="pre_title" value="pre_title" /> ';?><input type="text" name="title" id="title" class="styled" style="width: <?php if(User::Data('userlevel') >= 4){ echo '300px;'; }else{ echo '460px';}?>" maxlength="<?=$config['forum_topic_title_max_chars']?>" value="<?=$post['title']?>" /></dd>
		<?php } ?>
        <dt><?=$langBase->get('txt-39')?></dt>
        <dd><textarea name="text" id="text" cols="90" rows="<?php echo ($type == 't') ? 9 : 12; ?>" style="width: 540px; height: 170px;"><?=$post['text']?></textarea></dd>
        <?php
        	if( $type == 't' && IsForumAdmin(1) == true && $forum[1] != true && $forum[5] <= 1 ){
				foreach($config['forums'] as $key => $f)
				{
					$checked = ($post['forum_id'] == $key) ? ' selected="selected"' : '';
					$forums .= ($f[1] != true && $f[5] <= 1) ? '<option value="'.$key.'"'.$checked.'>'.$f[0].'</option>' : '';
				}
				echo '<dt>Forum</dt><dd><select name="forum" id="forum">'.$forums.'</select></dd>';
			}
			else
			{
				echo '<input type="hidden" name="forum" id="forum" value="' . $post['forum_id'] . '" />';
			}
		?>
    </dl>
    <div class="center clear">
        <input type="submit" value="<?=$langBase->get('txt-21')?>" />
        <input type="submit" value="<?=$langBase->get('txt-10')?>" class="cancel" />
    </div>
</form>
<?php
	}elseif( $task == 'edit' ){
		$post = ($type == 't') ? $topic : $reply;
		$resp = array();
		
		if( $type == 't' ){
			$title = $db->EscapeString($_POST['title']);
			$text  = $db->EscapeString($_POST['text']);
			$forum = $db->EscapeString($_POST['forum']);
			$f_lang = $_POST['f_lang'] == 2 ? 'EN' : 'RO';
			
			$forum_id = $forum;
			$forum = $config['forums'][$forum_id];
			
			if( Player::Data('forumban') != "" && $forum[1] == false && $forum[5] < Player::Data('userlevel') ){
				$resp['error'] = 'limited_access';
				die(json_encode($resp));
			}
			
			$forum = ($forum[1] != true && $forum[5] <= 1) ? $forum_id : $post['forum_id'];
			$forum = ($forum == 0) ? 1 : $forum;
			
			if( View::Lenght($title) < $config['forum_topic_title_min_chars'] ){
				$resp['message'] = View::Message($langBase->get('msg-10', array('-NUM-' => $config['forum_topic_title_min_chars'])), 2);
			}elseif( View::Lenght($title) > $config['forum_topic_title_max_chars'] ){
				$resp['message'] = View::Message($langBase->get('msg-11', array('-NUM-' => $config['forum_topic_title_max_chars'])), 2);
			}elseif( View::Lenght($text) < $config['forum_topic_text_min_chars'] ){
				$resp['message'] = View::Message($langBase->get('msg-12', array('-NUM-' => $config['forum_topic_text_min_chars'])), 2);
			}elseif( View::Lenght($text) > $config['forum_topic_text_max_chars'] ){
				$resp['message'] = View::Message($langBase->get('forum-35', array('-NUM-' => $config['forum_topic_text_max_chars'])), 2);
			}elseif( $post['title'] == $title && $post['text'] == $text && $post['forum_id'] == $forum && $f_lang == $post['f_lang'] ){
				$resp['message'] = View::Message('Successfully changed!', 2);
			}
			if ($resp['message']) die(json_encode($resp));
			$preTitle = User::Data('userlevel') >= 4 ? $db->EscapeString($_POST['pre_title']) : '';

			$db->Query("UPDATE `forum_topics` SET `title`='$title', `text`='$text', `last_edit_playerid`='".Player::Data('id')."', `last_edit_time`='".time()."', `forum_id`='$forum', `pre_title`='".$preTitle."', `f_lang`='".$f_lang."' WHERE `id`='".$post['id']."'");

			Accessories::AddToLog(Player::Data('id'), array('task' => $task, 'edit_post'));
			
			$text = new BBCodeParser($text, 'forum', true);
			
			$resp['success'] = 'success';
			$resp['text']    = stripslashes($text->result);
			$resp['title']   = stripslashes(View::NoHTML($title));

			die(json_encode($resp));
		}else{
			$text  = $db->EscapeString($_POST['text']);

			if( $topic['locked'] == 1 ){
				$resp['message'] = View::Message('You cannot edit message on a closed topic!', 2);
			}elseif( View::Lenght($text) < $config['forum_reply_text_min_chars'] ){
				$resp['message'] = View::Message('Your answer must have at least '.$config['forum_reply_text_min_chars'].' characters.', 2);
			}elseif( View::Lenght($text) > $config['forum_reply_text_max_chars'] ){
				$resp['message'] = View::Message('Your answer must have under '.$config['forum_reply_text_max_chars'].' characters.', 2);
			}elseif( $text == $post['text'] ){
				$resp['message'] = View::Message('Successfully changed!', 2);
			}
			if ($resp['message'])
				die(json_encode($resp));
			
			$db->Query("UPDATE `forum_replies` SET `text`='$text', `last_edit_playerid`='".Player::Data('id')."', `last_edit_time`='".time()."' WHERE `id`='".$reply['id']."'");
			
			Accessories::AddToLog(Player::Data('id'), array('task' => $task, 'edit_reply'));
			
			$text = new BBCodeParser($text, 'forum', true);
			
			$resp['success'] = 'success';
			$resp['text']    = stripslashes($text->result);
			
			die(json_encode($resp));
		}

	}elseif( $task == 'report_form' ){
		$post = ($type == 't') ? $topic : $reply;
?>
<form action="<?=$config['base_url']?>js/ajax/forum_post_handle.php?task=report&type=<?=$_GET['type']?>&id=<?=$post['id']?>" method="post" id="report_form">
	<h2><?=$langBase->get('forum-32')?></h2>
	<div id="send_result" style="margin: 10px;"></div>
    <dl class="form">
        <dt><?=$langBase->get('limit-04')?></dt>
        <dd><dd><textarea name="reason" id="report_reason" cols="85" rows="10"></textarea></dd></dd>
    </dl>
    <div class="center clear">
        <input type="submit" value="<?=$langBase->get('forum-32')?>" />
        <input type="submit" value="<?=$langBase->get('txt-10')?>" class="cancel" />
    </div>
</form>
<?php
	}elseif( $task == 'report' ){
		$post = ($type == 't') ? $topic : $reply;
		$post_type = ($type == 't') ? 'topic' : 'reply';
		
		$reason = $db->EscapeString($_POST['reason']);

		$last = $db->QueryFetchArray("SELECT reported FROM `forum_reports` WHERE `post_type`='$post_type'");

		$timeleft = $last['reported']+$config['forum_report_latency'] - time();
		
		if( $db->GetNumRows($db->Query("SELECT reported FROM `forum_reports` WHERE `post_type`='$post_type' AND `post_id`='".$post['id']."'")) >= 1 ){
			$error = View::Message('Reported with success!', 2);
		}elseif( $timeleft > 0 ){
			$error = View::Message('You have to wait <span class="countdown">'.$timeleft.'</span> seconds!', 2);
		}elseif( View::Lenght($reason) < $config['forum_report_min_chars'] ){
			$error = View::Message('Report must have at least '.$config['forum_report_min_chars'].' characters.', 2);
		}
		
		if ($error)
		{
			die(json_encode(array('error' => $error)));
		}
		
		$db->Query("INSERT INTO `forum_reports` (`playerid`, `userid`, `post_type`, `post_id`, `reason`, `reported`)VALUES('".Player::Data('id')."', '".User::Data('id')."', '$post_type', '".$post['id']."', '$reason', '".time()."')");
		$report_id = mysql_insert_id($db->GetLinkIdentifier());
		
		$table = ($type == 't') ? 'topics' : 'replies';
		$db->Query("UPDATE `forum_$table` SET `reported`='1' WHERE `id`='".$post['id']."'");

		Accessories::AddToLog(Player::Data('id'), array('task' => $task, 'report_id' => $report_id));

		die(json_encode(array('success' => $task)));
	}
?>