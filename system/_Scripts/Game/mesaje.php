<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$options = array('innboks', 'les', 'ny');
	$option = $_GET['a'];
	
	if (!in_array($option, $options))
	{
		header('Location: /game/?side=' . $_GET['side'] . '&a=' . $options[0]);
		exit;
	}
	
	if ($option == 'innboks')
	{
		$sql = "SELECT id,title,num_replies,last_reply,last_player,views FROM `messages` WHERE `players` LIKE '%|" . Player::Data('id') . "|%' AND `deleted`='0' ORDER BY last_reply DESC";
		$pagination = new Pagination($sql, 15, 'p');
		$messages = $pagination->GetSQLRows();
		
		if (count($messages) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
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
	.forum_wrapper ul.topics li a.topic span.post_replies {
		position: absolute;
		left: 300px;
		top: 9px;
	}
	.forum_wrapper ul.topics li a.topic .nicks {
		position: absolute;
		left: 390px;
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
	foreach ($messages as $message)
	{
		$last_player = in_array($message['last_player'], array(0, Player::Data('id'))) ? Player::$datavar : $db->QueryFetchArray("SELECT name FROM `[players]` WHERE `id`='".$message['last_player']."'");
		$views = unserialize($message['views']);
		
		if (!$views[Player::Data('id')] && $message['last_player'] == 0) $views[Player::Data('id')] = time();
	?>
    	<li>
        	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=les&amp;id=<?=$message['id']?>" class="topic<?=($i++%2 ? '' : ' op')?>">
            	<span><?=('<img class="post_image" src="' . $config['base_url'] . 'images/' . ($message['last_reply'] > $views[Player::Data('id')] ? 'forum_new_post.png" alt="" title=""' : 'forum_post.png" alt="" title=""') . ' />')?></span>
                <span class="post_title"><?=View::NoHTML($message['title'])?></span>
                <span class="post_replies"><?=View::CashFormat($message['num_replies'])?> <?=($message['num_replies'] == 1 ? $langBase->get('msg-01') : $langBase->get('msg-02'))?></span>
                <span class="nicks"><span class="post_lastReply"><span class="toggleHTML" title="<?=View::HowLongAgo($message['last_reply'])?>"><?=$last_player['name']?></span></span></span>
            </a>

        </li>
    <?php
	}
	?>
    </ul>
</div>
<div style="margin: 20px;"><?=$pagination->GetPageLinks()?></div>
<?php
		}
	}
	elseif ($option == 'les')
	{
		$message = $db->EscapeString($_GET['id']);
		$sql = $db->Query("SELECT * FROM `messages` WHERE `id`='".$message."' " . (User::Data('userlevel') >= 3 ? '' : " AND `players` LIKE '%|" . Player::Data('id') . "|%'") . " AND `deleted`='0'");
		$message = $db->FetchArray($sql);
		if (isset($_POST['message_del']))
		{
			$db->Query("UPDATE `messages` SET `deleted`='1' WHERE `id`='".$message['id']."'");
			
			View::Message($langBase->get('msg-03'), 1, true, '/game/?side=' . $_GET['side'] . '&a=' . $options[0]);
		}
		if ($message['id'] == '')
		{
			View::Message('ERROR', 2, true, '/game/?side=' . $_GET['side'] . '&a=' . $options[0]);
		}
		
		$players = explode('|', $message['players']);
		foreach ($players as $key => $player) if (empty($player)) unset($players[$key]);
		
		$views = unserialize($message['views']);
		$last_view = $views[Player::Data('id')];
		$views[Player::Data('id')] = time();
		$db->Query("UPDATE `messages` SET `views`='".serialize($views)."' WHERE `id`='".$message['id']."'");
?>
<style type="text/css">
	<!--
	#message_replies {
		margin-top: 20px;
		position: relative;
	}
	.reply_box {
		margin-top: 2px;
		padding: 5px;
		border-radius: 3px;
		background: #151515;
	}
	.reply_box.c {
		background: #161616;
	}
	
	.reply_box .col_1, .reply_box .col_2, .reply_box .col_3 { float: left; overflow: hidden; }
	.reply_box .col_1 {
		width: 90px;
		padding-right: 20px;
		text-align: right;
	}
	.reply_box .col_2 {
		width: 360px;
	}
	.reply_box .col_3 {
		width: 80px;
		text-align: right;
		color: #333333;
		font-size: 10px;
	}
	-->
</style>
<script type="text/javascript">
	<!--
	var Messages = new Class(
	{
		options: {
			check_interval: 3000
		},
		
		initialize: function(wrapper)
		{
			this.wrapper = wrapper;
			this.statusTxt = $('msg_ajaxStatus');
			
			this.timer = false;
			this.paused = false;
			
			var self = this;
			this.timer = setTimeout(function()
			{
				self.getReplies();
			}, this.options.check_interval);
			
			document.addEvents({
				'active': function()
				{
					self.start();
				},
				'idle': function()
				{
					self.pause();
				}
			});
		},
		appendMessage: function(creator, text, created)
		{
			var msg = new Element('div',
			{
				'class': 'reply_box c',
				'html': '<div class="col_1">' + creator + '</div><div class="col_2">' + text + '</div><div class="col_3"><span class="yellow">' + created + '</span></div><div class="clear"></div>'
			}).inject(this.wrapper, 'top');
			check_html(msg);
		},
		
		getReplies: function()
		{
			this.statusTxt.set('html', '<?=$langBase->get('msg-18')?>');
			
			try {
				this.xhr.cancel();
			} catch(e){}
			
			var self = this;
			this.xhr = new Request.JSON({ 'url': 'js/ajax/message_newReplies.php', 'method': 'get', 'data': {id: '<?=$message['id']?>'} }).addEvents(
			{
				success: function(data)
				{
					if ($chk(data.error))
					{
						self.stop();
					}
					else
					{
						if ($chk(data.messages) && data.messages != 'empty')
						{
							data.messages.each(function(item)
							{
								self.appendMessage(item.creator, item.text, item.created);
							});
						}
						
						$clear(self.timer);
						self.timer = setTimeout(function()
						{
							self.getReplies();
						}, self.options.check_interval);
					}
				},
				failure: function()
				{
					self.stop();
				}
			}).send();
		},
		start: function()
		{
			if (this.paused == false)
			{
				return;
			}
			this.paused = false;
			
			this.getReplies();
		},
		pause: function()
		{
			if (this.paused == true)
			{
				return;
			}
			this.paused = true;
			
			this.statusTxt.set('html', '<?=$langBase->get('msg-19')?>');
			
			try {
				this.xhr.cancel();
			} catch(e){}
			$clear(this.timer);
			this.timer = false;
		},
		stop: function()
		{
			this.statusTxt.set('html', '<?=$langBase->get('msg-20')?>');
			
			this.getReplies = $empty;
			$clear(this.timer);
			this.timer = false;
			try {
				this.xhr.cancel();
			} catch(e){}
		}
	});
	
	window.addEvent('domready', function()
	{
		var msgHandler = new Messages($('message_replies'));
		
		var form = $('create_reply_form');
		form.addEvent('submit', function(e)
		{
			e.stop();
			
			try {
				this.request.cancel();
			} catch(e){}
			
			if ([this.lastMsg, ''].contains(this.getElement('textarea').value.trim()))
			{
				alert('Ugyldig melding.');
				return false;
			}
			this.lastMsg = this.getElement('textarea').value.trim();
			
			var self = this;
			this.request = new Request.JSON({ url: 'js/ajax/message_createReply.php', data: { id: '<?=$message['id']?>', text: this.getElement('textarea').value}, method: 'post' }).addEvents(
			{
				success: function(data)
				{
					if ($chk(data.error))
					{
						alert('<?=$langBase->get('msg-04')?>');
					}
					else
					{
						msgHandler.appendMessage(data.creator, data.text, data.created);
						
						self.getElement('textarea').value = '';
						self.getElement('textarea').fireEvent('blur');
					}
				},
				failure: function()
				{
					alert('<?=$langBase->get('msg-04')?>');
				}
			}).send();
		});
		
		form.getElement('textarea').addEvents(
		{
			'focus': function()
			{
				this.set('rows', 5);
				
				if (!this.submitB) this.submitB = new Element('input', {'type': 'submit', 'value': '<?=$langBase->get('msg-05')?>'}).inject(this.getParent());
			},
			'blur': function()
			{
				if (this.value.trim() == '')
				{
					this.set('rows', 1);
					this.submitB.destroy();
					this.submitB = false;
				}
			}
		});
	});
	-->
</script>
<h2 class="left"><?=$message['title']?><br /><span class="dark small"><?=View::Time($message['created'], true, 'H:i')?></span></h2>
<p style="margin-right: 30px;" class="right dark small" id="msg_ajaxStatus"></p>
<div class="clear"></div>
<p style="margin-left: 30px;" class="left"><?=$langBase->get('msg-06')?>:<br /><?php foreach ($players as $player){ echo View::Player(array('id' => $player)) . '&nbsp; &nbsp;'; }?></p>
<form method="post"><p style="margin-right: 30px;" class="right dark small"><input type="submit" name="message_del" value="<?=$langBase->get('txt-36')?>" /></p></form>
<div class="clear"></div>
<div class="bg_c" style="width: 560px;">
	<form method="post" action="" id="create_reply_form">
        <div class="reply_box">
            <div class="col_1"><?=View::Player(Player::$datavar)?></div>
            <div class="col_2"><textarea name="reply_create" rows="1" cols="50" style="width: 340px;"><?=stripslashes($_POST['reply_create'])?></textarea></div>
            <div class="col_3"><?=$langBase->get('msg-07')?></div>
            <div class="clear"></div>
        </div>
    </form>
    <div id="message_replies">
    <?php
	$sql = $db->Query("SELECT id,text,creator,created FROM `messages_replies` WHERE `message_id`='".$message['id']."' AND `deleted`='0' ORDER BY id DESC");
	while ($reply = $db->FetchArray($sql))
	{
		$bbText = new BBCodeParser($reply['text'], 'message_textfield', true);
	?>
    	<div class="reply_box<?=($i++%2 ? ' c' : '')?>">
        	<div class="col_1"><?=($reply['creator'] != $lastPlayer ? View::Player(array('id' => $reply['creator'])) : View::Player(array('id' => $reply['creator'])))?></div>
            <div class="col_2"><?=$bbText->result?></div>
            <div class="col_3"><?=($reply['created'] > $last_view ? '<span class="yellow">' . View::Time($reply['created'], false, 'H:i') . '</span>' : View::Time($reply['created'], false, 'H:i', false))?></div>
            <div class="clear"></div>
        </div>
    <?php
		$lastPlayer = $reply['creator'];
	}
	?>
    </div>
</div>
<?php
	}
	elseif ($option == 'ny')
	{
		if (isset($_POST['exit'], $_GET['nick']))
		{
			header('Location: /game/s/' . $_GET['nick']);
			exit;
		}
		
		if (isset($_POST['message_title']))
		{
			$title = $db->EscapeString($_POST['message_title']);
			$text = $db->EscapeString($_POST['message_text']);
			$recTxt = trim($db->EscapeString($_POST['message_receivers']));
			
			$receivers = array_unique(explode('|', $recTxt));
			foreach ($receivers as $key => $player) if (empty($player)) unset($receivers[$key]);
			
			$players = array();
			foreach ($receivers as $player)
			{
				$sql = $db->Query("SELECT id FROM `[players]` WHERE `id`='".$player."' AND `health`>'0' AND `level`>'0'");
				$player = $db->FetchArray($sql);
				
				if ($player['id'] != '') $players[] = $player['id'];
			}
			
			if (!in_array(Player::Data('id'), $players))
				echo $players[] = Player::Data('id');
			
			if (count($players) <= 1)
			{
				echo View::Message($langBase->get('msg-08'), 2);
			}
			elseif (count($players) > $config['message_max_receivers'])
			{
				echo View::Message($langBase->get('msg-09', array('-MAX-' => $config['message_max_receivers'])), 2);
			}
			elseif (View::Length($title) < $config['message_title_min_chars'])
			{
				echo View::Message($langBase->get('msg-10', array('-NUM-' => $config['message_title_min_chars'])), 2);
			}
			elseif (View::Length($title) > $config['message_title_max_chars'])
			{
				echo View::Message($langBase->get('msg-11', array('-NUM-' => $config['message_title_max_chars'])), 2);
			}
			elseif (View::Length($text) < $config['message_min_chars'])
			{
				echo View::Message($langBase->get('msg-12', array('-NUM-' => $config['message_min_chars'])), 2);
			}
			else
			{
				$playersTxt = '|';
				foreach ($players as $player) $playersTxt .= $player . '|';
				
				$db->Query("INSERT INTO `messages`
						   (`title`, `players`, `creator`, `creator_ip`, `created`, `last_reply`, `last_player`, `num_replies`)
						   VALUES
						   ('".$title."', '".$playersTxt."', '".Player::Data('id')."', '".$_SERVER['REMOTE_ADDR']."', '".time()."', '".time()."', '".Player::Data('id')."', '1')");
				
				$message = mysql_insert_id();
				
				$db->Query("INSERT INTO `messages_replies`
							(`message_id`, `text`, `creator`, `creator_ip`, `created`)
							VALUES
							('".$message."', '".$text."', '".Player::Data('id')."', '".$_SERVER['REMOTE_ADDR']."', '".time()."')");
				
				$db->Query("UPDATE `[players]` SET `message_last`='".time()."', `messages_sent`=`messages_sent`+'1' WHERE `id`='".Player::Data('id')."'");
				
				View::Message($langBase->get('msg-13'), 1, true, '/game/?side=' . $_GET['side'] . '&a=les&id=' . $message);
			}
		}
?>
<style type="text/css">
	<!--
	ul#msg_recList, ul#msg_recList li {
		margin: 0;
		padding: 0;
		list-style: none;
	}
	ul#msg_recList li {
		padding: 3px;
		margin-top: 1px;
		background: #171717;
		border-radius: 3px;
		width: 150px;
	}
	ul#msg_recList li:hover {
		background-color: #1c1c1c;
	}
	ul#msg_recList li a.rem {
		font-size: 15px;
		font-weight: bold;
		float: right;
		margin-top: -3px;
	}
	-->
</style>
<script type="text/javascript">
	<!--
	var NyMelding = new Class(
	{
		max_rec: <?=$config['message_max_receivers']?>,
		my_id: <?=Player::Data('id')?>,
		receivers: new Hash(),
		
		initialize: function()
		{
			this.msg_receivers = $('msg_receivers');
			this.msg_recList = $('msg_recList');
			this.rec_num = $('rec_num');
			this.addBox = $('msg_addRec');
			
			var self = this;
			this.addBox.addEvent('keypress', function(event)
			{
				if (event.key == 'enter')
				{
					event.stop();
					
					try {
						self.xhr.cancel();
					} catch(e) {}
					
					this.xhr = new Request.JSON(
					{
						'url': 'js/ajax/player.php',
						'method': 'get',
						'data': { name: this.value }
					}).addEvents(
					{
						success: function(data)
						{
							if ($chk(data.error))
							{
								alert('<?=$langBase->get('msg-14')?>');
							}
							else
							{
								self.addRec(data.id, data.link);
								self.make_recList();
							}
							
							self.addBox.select();
						},
						failure: function()
						{
							alert('ERROR');
						}
					}).send();
				}
			});
		},
		
		make_recList: function()
		{
			if (this.receivers.getLength() <= 0) return;
			
			this.msg_recList.empty();
			
			var self = this;
			this.receivers.each(function(p_link, p_id)
			{
				var elem = new Element('li').set('html', p_link).inject(self.msg_recList);
				if (p_id != self.my_id)
				{
					new Element('a', { 'class': 'rem', 'html': 'X' }).inject(elem).addEvent('click', function()
					{
						self.remRec(p_id);
						elem.destroy();
						return false;
					});
				}
			});
		},
		
		addRec: function(p_id, p_link)
		{
			if (this.receivers.get(p_id) || this.receivers.getLength() >= this.max_rec) return;
			this.receivers.set(p_id, p_link);
			this.rec_num.set('html', this.receivers.getLength());
		},
		remRec: function(p_id)

		{
			if (!this.receivers.get(p_id) || p_id == this.my_id) return;
			this.receivers.erase(p_id);
			this.rec_num.set('html', this.receivers.getLength());
		},
		
		getRecStr: function()
		{
			var str = '|';
			this.receivers.each(function(p_link, p_id)
			{
				str += p_id + '|';
			});
			
			return str;
		}
	});
	
	window.addEvent('domready', function()
	{
		var melding = new NyMelding();
		
		melding.addRec(melding.my_id, '<?=View::Player(Player::$datavar)?>');
		<?php
		if (isset($_POST['message_receivers']))
		{
			$receivers = array_unique(explode('|', $db->EscapeString($_POST['message_receivers'])));
			foreach ($receivers as $key => $player) if (empty($player) || $player == Player::Data('id')) unset($receivers[$key]);
			
			foreach ($receivers as $player)
			{
				$sql = $db->Query("SELECT id FROM `[players]` WHERE `id`='".$player."' AND `health`>'0' AND `level`>'0'");
				$player = $db->FetchArray($sql);
				
				echo "melding.addRec('" . $player['id'] . "', '" . View::Player($player) . "');\n";
			}
		}
		elseif (isset($_GET['nick']))
		{
			$player = $db->EscapeString(trim($_GET['nick']));
			$sql = $db->Query("SELECT id,level,health,name FROM `[players]` WHERE `name`='".$player."' AND `level`>'0' AND `health`>'0'");
			$player = $db->FetchArray($sql);
			
			if ($player['id'] != '')
			{
				echo "melding.addRec('" . $player['id'] . "', '" . View::Player($player) . "');\n";
			}
		}
		?>
		
		melding.make_recList();
		
		$('msg_form').addEvent('submit', function()
		{
			$('msg_receivers').value = melding.getRecStr();
		});
	});
	-->
</script>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('subMenu-09')?></h1>
    <form method="post" action="" id="msg_form">
    	<input type="hidden" name="message_receivers" id="msg_receivers" value="" />
    	<dl class="dt_70 form">
        	<dt><?=$langBase->get('msg-06')?> (<span id="rec_num">0</span>/<?=$config['message_max_receivers']?>)</dt>
            <dd>
            	<p class="left" style="margin-right: 10px;">Nume: <input type="text" class="styled" id="msg_addRec" /></p>
            	<ul id="msg_recList" class="left">
                	<!--<li><?=View::Player(Player::$datavar)?><a href="#" class="rem">X</a></li>-->
                </ul>
                <div class="clear"></div>
            </dd>
			<dt><?=$langBase->get('msg-15')?></dt>
            <dd><input type="text" name="message_title" class="styled" value="<?=$_POST['message_title']?>" style="width: 250px;" maxlength="<?=$config['message_title_max_chars']?>" /></dd>
        	<dt><?=$langBase->get('msg-16')?></dt>
            <dd><textarea cols="64" rows="15" name="message_text"><?=$_POST['message_text']?></textarea></dd>
        </dl>
        <p class="center clear"><input type="submit" value="<?=$langBase->get('msg-05')?>" /><?php if(isset($_GET['nick'])) echo '<input type="submit" name="exit" value="'.$langBase->get('txt-10').'" style="margin-left: 10px;" />'; ?></p>
    </form>
</div>
<?php
	}
?>