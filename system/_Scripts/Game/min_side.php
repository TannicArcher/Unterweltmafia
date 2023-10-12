<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$options = array('user', 'player', 'ref');
	$option = $_GET['a'];
	
	$subOption = $_GET['b'];
	$subOptions = array(
		'player' => array('main', 'forum', 'log'),
		'user' => array('main', 'players', 'sessions', 'smartmenu'),
		'ref' => array('main', 'players')
	);
	
	if (!in_array($option, $options))
	{
		header('Location: /game/?side='.$_GET['side'].'&a=player&b=main');
		exit;
	}
	if (!in_array($subOption, $subOptions[$option]) && !empty($subOption[$option]))
	{
		header('Location: /game/?side='.$_GET['side'].'&a='.$option.'&b='.$subOptions[$option][0].'');
		exit;
	}
	
	$sql = $db->Query("SELECT id,level,health,created,last_active,name FROM `[players]` WHERE `userid`='".User::Data('id')."' ORDER BY id DESC");
    $players = $db->FetchArrayAll($sql);
	
	$playerIDs = array();
	foreach ($players as $player)
	{
		$playerIDs[] = $player['id'];
	}
	
	if ($option == 'player')
	{
?>
<p class="center nomargin">
	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$option?>&amp;b=main" class="button big<?=($subOption == 'main' ? ' active' : '')?>"><?=$langBase->get('subMenu-17')?></a>
    <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$option?>&amp;b=forum" class="button big<?=($subOption == 'forum' ? ' active' : '')?>"><?=$langBase->get('min-01')?></a>
    <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$option?>&amp;b=log" class="button big<?=($subOption == 'log' ? ' active' : '')?>"><?=$langBase->get('min-02')?></a>
</p>
<?php
		if ($subOption == 'main')
		{
			$progressbars['liv']    = View::AsPercent(Player::Data('health'), $config['max_health'], 2);
			$progressbars['wanted'] = View::AsPercent(Player::Data('wanted-level'), $config['max_wanted-level'], 2);
			
			$rankp = View::AsPercent(Player::Data('rankpoints')+$brekk_stats['rankpoints_earned']-$config['ranks'][Player::Data('rank')][1], $config['ranks'][Player::Data('rank')][2]-$config['ranks'][Player::Data('rank')][1], 2);
			$progressbars['rank']   = $rankp;
			
			$sql = $db->Query("SELECT stats FROM `brekk` WHERE `playerid`='".Player::Data('id')."'");
			$brekk = $db->FetchArray($sql);
			$brekkstats = unserialize($brekk['stats']);
			$brekk_performed = $brekkstats['failed'] + $brekkstats['sucessfull'];
			
			$forumposts = explode(",", Player::Data('forum_num_posts'));
			$forum_posts = $forumposts[0] + $forumposts[1];
			
			$jailstats = unserialize(Player::Data('jail_stats'));
			
			$antibot_success = $db->GetNumRows($db->Query("SELECT id FROM `antibot_sessions` WHERE `playerid`='".Player::Data('id')."' AND `active`='0' AND `result`='1'"));
			$antibot_fail = $db->GetNumRows($db->Query("SELECT id FROM `antibot_sessions` WHERE `playerid`='".Player::Data('id')."' AND `active`='0' AND `result`='0'"));
?>
<script type="text/javascript">
	<!--
	window.addEvent('domready', function()
	{
		var wrap_da = $('da_change');
		var links_da = wrap_da.getChildren('a');
		var current_da = 0;
		
		links_da.each(function(elem)
		{
			elem.addEvent('click', function()
			{
				if (elem.hasClass('prev'))
					current_da += 1;
				else
					current_da -= 1;
				
				if (current_da < 0)
					current_da = 0;
				else
					$('graph_da_stats').reload('/game/graphs/daily_activity.php?player=<?=Player::Data('id')?>&time_add=' + current_da);
				
				return false;
			});
		});
		
		var wrap_ec = $('ec_change');
		var links_ec = wrap_ec.getChildren('a');
		var current_ec = 0;
		
		links_ec.each(function(elem)
		{
			elem.addEvent('click', function()
			{
				if (elem.hasClass('prev'))
					current_ec += 1;
				else
					current_ec -= 1;
				
				if (current_ec < 0)
					current_ec = 0;
				else
					$('graph_ec_stats').reload('/game/graphs/player_economy.php?player=<?=Player::Data('id')?>&time_add=' + current_ec);
				
				return false;
			});
		});
	});
	-->
</script>
<div style="margin: 20px 10px 10px 10px; padding: 10px; background: #1c1c1c;">
	<p class="large" style="margin-top: 0;"><?=$langBase->get('concurs-05')?>: <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=ref">&laquo; <?=$langBase->get('min-03')?></a></p>
    <p class="center"><input type="text" class="flat" onfocus="this.select()" value="<?=$config['game_url']?>/reg/<?=User::Data('id')?>" style="min-width: 400px; width: 400px;" /></p>
</div>
<div style="width: 310px;" class="left">
    <div class="bg_c" style="width: 290px;">
        <h1 class="big"><?=$langBase->get('ot-rank')?></h1>
        <div class="progressbar">
        	<div class="progress" style="width: <?=round($progressbars['rank'], 0)?>%;"><p><?=$config['ranks'][Player::Data('rank')][0]?>: <b><?=$progressbars['rank']?> %</b></p></div>
        </div>
    </div>
    <div class="bg_c" style="width: 290px;">
        <h1 class="big"><?=$langBase->get('ot-wanted_level')?></h1>
        <div class="progressbar">
        	<div class="progress" style="width: <?=round($progressbars['wanted'], 0)?>%;"><p><b><?=$progressbars['wanted']?> %</b></p></div>
        </div>
    </div>
    <div class="bg_c" style="width: 290px;">
        <h1 class="big"><?=$langBase->get('ot-health')?></h1>
        <div class="progressbar">
        	<div class="progress" style="width: <?=round($progressbars['liv'], 0)?>%;"><p><b><?=$progressbars['liv']?> %</b></p></div>
        </div>
    </div>
    <div class="bg_c" style="width: 290px;">
    	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
        <dl class="dd_right">
        	<dt><?=$langBase->get('min-04')?></dt>
            <dd><?=View::Player(Player::$datavar)?></dd>
        	<dt><?=$langBase->get('min-05')?></dt>
            <dd>#<?=Player::Data('id')?></dd>
            <dt><?=$langBase->get('min-06')?></dt>
            <dd><span class="toggleHTML" title="<?=View::strTime(time()-Player::Data('created'), 1, ', ', 0)?> siden"><?=View::Time(Player::Data('created'), true)?></span></dd>
            <dt><?=$langBase->get('txt-05')?></dt>
            <dd><?=$config['places'][Player::Data('live')][0]?></dd>
        </dl>
        <div class="clear"></div>
    </div>
</div>
<div style="width: 310px; margin-left: 20px;" class="left">
    <div class="bg_c" style="width: 290px;">
        <h1 class="big"><?=$langBase->get('ot-stats')?></h1>
        <h2 class="nomargin"><?=$langBase->get('min-08')?></h2>
        <dl class="dd_right" style="margin: 5px;">
        	<dt><?=$langBase->get('min-07')?></dt>
            <dd><?=$langBase->get('ot-money')?>: <?=View::CashFormat(Player::Data('cash'))?><br />In der Bank: <?=View::CashFormat(Player::Data('bank'))?><br />Total: <?=View::CashFormat(Player::Data('cash')+Player::Data('bank'))?></dd>
        	<dt><?=$langBase->get('min-09')?></dt>
            <dd><?=View::CashFormat(Player::Data('messages_sent'))?></dd>
            <dt><?=$langBase->get('min-10')?></dt>
            <dd><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=player&amp;b=forum"><?=View::CashFormat($forum_posts)?></a></dd>
            <dt><?=$langBase->get('min-11')?></dt>
            <dd><a href="<?=$config['base_url']?>?side=statistici_jaf"><?=View::CashFormat($brekk_performed)?></a></dd>
            <dt><?=$langBase->get('min-12')?></dt>
            <dd><?=View::CashFormat($db->GetNumRows($db->Query("SELECT id FROM `contacts` WHERE `userid`='".User::Data('id')."' AND `type`='1'")))?></dd>
        </dl>
        <div class="clear"></div>
        <h2 style="margin: 10px 0 0 0;"><?=$langBase->get('min-13')?></h2>
        <dl class="dd_right" style="margin: 5px;">
        	<dt><?=$langBase->get('min-14')?></dt>
            <dd><?=View::CashFormat($jailstats['times_in_jail'])?> <?=$langBase->get('min-15')?> (<span class="toggleHTML" title="<?=View::strTime($jailstats['time_in_jail'], 0, ' ', 0, '0s')?>"><?=$jailstats['time_in_jail']?> <?=$langBase->get('min-16')?></span>)</dd>
            <dt><?=$langBase->get('min-17')?></dt>
            <dd><?=$langBase->get('min-18')?>: <?=View::CashFormat($jailstats['breakouts_successed'])?><br /><?=$langBase->get('min-19')?>: <?=View::CashFormat($jailstats['breakouts_failed'])?><br /><?=$langBase->get('min-20')?>: <?=View::CashFormat($jailstats['breakouts_successed']+$jailstats['breakouts_failed'])?></dd>
            <dt><?=$langBase->get('min-21')?></dt>
            <dd><?=$langBase->get('min-22')?>: <?=View::CashFormat($jailstats['breakouts_earned'])?> $<br /><?=$langBase->get('min-23')?>: <?=View::CashFormat($jailstats['breakouts_used'])?> $<br /><?php $result = $jailstats['breakouts_earned']-$jailstats['breakouts_used']; echo $result >= 0 ? $langBase->get('min-25').': <span style="color: #ff6600;">'.View::CashFormat($result).' $</span>' : $langBase->get('min-26').': <span style="color: #ff0000;">'.View::CashFormat(abs($result)).' $</span>'; ?></dd>
            <dt><?=$langBase->get('min-27')?></dt>
            <dd><?=$langBase->get('min-20')?>: <?=View::CashFormat($jailstats['times_bribed']+$jailstats['times_stole_keys'])?></dd>
            <dt><?=$langBase->get('min-28')?></dt>
            <dd><?=View::CashFormat($jailstats['bribe_cash'])?> $ (<?=$jailstats['times_bribed']?> <?=$langBase->get('min-15')?>)</dd>
        </dl>
        <div class="clear"></div>
    </div>
</div>
<div class="clear"></div>
<div class="graph_container">
    <p class="center" id="da_change" style="margin-bottom: 5px;">
        <a href="#" class="prev">&laquo; <?=$langBase->get('ot-back')?></a>
        <a href="#" class="next" style="margin-left: 10px;"><?=$langBase->get('ot-next')?>&raquo;</a>
    </p>
    <div id="graph_da_stats"></div>
</div>
<div class="graph_container">
    <p class="center" id="ec_change" style="margin-bottom: 5px;">
        <a href="#" class="prev">&laquo; <?=$langBase->get('ot-back')?></a>
        <a href="#" class="next" style="margin-left: 10px;"><?=$langBase->get('ot-next')?> &raquo;</a>
    </p>
    <div id="graph_ec_stats"></div>
</div>
<?php
		}elseif ($subOption == 'forum')
		{
			$show = in_array($_GET['show'], array('ft', 'fr')) ? $_GET['show'] : 'ft';
?>
<p class="center" style="margin-top: 20px;">
	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$option?>&amp;b=<?=$subOption?>&amp;show=ft" class="button<?=($show == 'ft' ? ' active' : '')?>"><?=$langBase->get('min-29')?></a>
    <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$option?>&amp;b=<?=$subOption?>&amp;show=fr" class="button<?=($show == 'fr' ? ' active' : '')?>"><?=$langBase->get('min-30')?></a>
</p>
<?php
	if ($show == 'ft')
	{
		$sql = "SELECT id,forum_id,family,title,posted,type,deleted,views,replies FROM `forum_topics` WHERE `playerid` IN (" . implode(',', $playerIDs) . ") ORDER BY id DESC";
		$pagination = new Pagination($sql, 20, 'p');
		$forum_posts = $pagination->GetSQLRows();
		$pagination_links = $pagination->GetPageLinks();
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('min-29')?></h1>
    <?php
		if ($pagination->num_rows > 0)
		{
	?>
    <table class="table">
    	<thead>
        	<tr>
            	<td width="40%"><?=$langBase->get('txt-38')?></td>
                <td width="15%"><?=$langBase->get('min-32')?></td>
                <td width="30%"><?=$langBase->get('txt-27')?></td>
            </tr>
        </thead>
        <tbody>
        <?php
			foreach ($forum_posts as $post)
			{
				$i++;
				$color = $i%2 ? 1 : 2;
				
				$forum = $config['forums'][$post['forum_id']];
				$forum_name = $forum[1] == true ? $post['family'] : $forum[0];
		?>
        	<tr class="c_<?=$color?>">
            	<td width="40%">&laquo;<?=($post['deleted'] != 1 ? '<a href="'.$config['base_url'].'?side=forum/topic&amp;id='.$post['id'].'">'.$post['title'].'</a>' : $post['title'])?>&raquo;<?=($post['deleted'] == 1 ? ' <span style="color: #ff0000;">('.$langBase->get('min-31').')</span>' : '')?><br /><a href="<?=$config['base_url']?>?side=forum/forum&amp;f=<?=$post['forum_id']?><?=($post['family'] && Player::Data('level') > 3 ? '&amp;family='.$post['family'] : '')?>" class="subtext"><?=$forum_name?></a></td>
                <td width="15%" class="center"><?=View::CashFormat($post['replies'])?></td>
                <td width="30%" class="t_right"><span class="toggleHTML" title="<?=View::strTime(time()-$post['posted'], 1, ', ', 0, '0 secunde')?> siden"><?=View::Time($post['posted'], true)?></span></td>
            </tr>
        <?php
			}
		?>
        	<tr class="c_3"><td colspan="4"><?=$pagination_links?></td></tr>
        </tbody>
    </table>
    <?php
		}else{ echo '<p>'.$langBase->get('err-06').'</p>'; }
	?>
    <div class="clear"></div>
</div>
<?php
	}
	else
	{
		$sql = "SELECT id,topic_id,posted,deleted FROM `forum_replies` WHERE `playerid` IN (" . implode(',', $playerIDs) . ") ORDER BY id DESC";
		$pagination = new Pagination($sql, 40, 'p');
		$forum_replies = $pagination->GetSQLRows();
		$pagination_links = $pagination->GetPageLinks();
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('min-30')?></h1>
    <?php
		if ($pagination->num_rows > 0)
		{
	?>
    <table class="table">
    	<thead>
        	<tr>
            	<td width="35%"><?=$langBase->get('txt-38')?></td>
                <td width="25%"><?=$langBase->get('min-33')?></td>
                <td width="25%"><?=$langBase->get('txt-27')?></td>
            </tr>
        </thead>
        <tbody>
        <?php
			foreach ($forum_replies as $reply)
			{
				$i++;
				$color = $i%2 ? 1 : 2;
				
				$sql = $db->Query("SELECT id,title,playerid,views,deleted,forum_id,family FROM `forum_topics` WHERE `id`='".$reply['topic_id']."'");
				$topic = $db->FetchArray($sql);
				
				$forum = $config['forums'][$topic['forum_id']];
				$forum_name = $forum[1] == true ? 'Familieforum' : $forum[0];
		?>
        	<tr class="c_<?=$color?>">
            	<td width="35%">&laquo;<?=($topic['deleted'] != 1 ? '<a href="'.$config['base_url'].'?side=forum/topic&amp;id='.$topic['id'].''.($reply['deleted'] != 1 ? '&amp;fr='.$reply['id'] : '').'">'.$topic['title'].'</a>' : $topic['title'])?>&raquo;<?=($topic['deleted'] == 1 ? ' <span style="color: #444444; font-size: 10px;">('.$langBase->get('min-31').')</span>' : '')?><?=($reply['deleted'] == 1 ? ' <span style="color: #ff0000;">'.$langBase->get('min-31').'</span>' : '')?><br /><a href="<?=$config['base_url']?>?side=forum/forum&amp;f=<?=$topic['forum_id']?><?=($topic['family'] != 0 && Player::Data('level') > 3 ? '&amp;family='.$topic['family'] : '')?>" class="subtext"><?=$forum_name?></a></td>
                <td width="25%" class="center"><?=View::Player(array('id' => $topic['playerid']))?></td>
                <td width="25%" class="t_right"><span class="toggleHTML" title="<?=View::strTime(time()-$reply['posted'], 1, ', ', 0, '0 sekunder')?> siden"><?=View::Time($reply['posted'], true)?></span></td>
            </tr>
        <?php
			}
		?>
        	<tr class="c_3"><td colspan="4"><?=$pagination_links?></td></tr>
        </tbody>
    </table>
    <?php
		}else{ echo '<p>'.$langBase->get('err-06').'</p>'; }
	?>
</div>
<?php
	}
		}
		elseif ($subOption == 'log')
		{
			$sql = "SELECT id,time,data,type,`read`,archived,player FROM `logevents` WHERE `user`='".User::Data('id')."'".(isset($_GET['sa']) ? " AND `archived`='1'" : " AND `archived`='0'")." ORDER BY id DESC";
			
			$pagination       = new Pagination($sql, 30, 'p');
			$all_logevents    = $pagination->GetSQLRows();
			$pagination_links = $pagination->GetPageLinks();
			
			$events = array();
			$days = array();
			foreach ($all_logevents as $event)
			{
				$day = date('d.m.Y', $event['time']);
				if (!in_array($day, $days))
				{
					$days[] = $day;
				}
				
				$events[$day][] = $event;
			}
?>
<script type="text/javascript">
	<!--
	window.addEvent('domready', function()
	{
		document.getElements('.logevents_wrap .entry_wrap').each(function(wrap)
		{
			var bottom_right = wrap.getElement('.bottom .right');
			
			wrap.addEvents(
			{
				mouseenter: function()
				{
					bottom_right.setStyle('display', 'inherit');
				},
				mouseleave: function()
				{
					bottom_right.setStyle('display', 'none');
				}
			});
			
			wrap.set('tween', { duration: 500 });
			
			bottom_right.getElement('a').addEvent('click', function()
			{
				var xhr = new Request({ url: '/game/js/ajax/logevent_archive.php', data: 'id=' + this.get('rel'), method: 'get' });
				xhr.addEvents(
				{
					success: function(data)
					{
						if (data == 'SUCCESS')
						{
							wrap.fade(0);
							(function()
							{
								wrap.destroy();
							}).delay(500);
						}
						else
						{
							alert('<?=$langBase->get('min-34')?>');
						}
					},
					failure: function(data)
					{
						alert("<?=$langBase->get('min-34')?>");
					}
				});
				xhr.send();
				
				return false;
			});
		});
	});
	-->
</script>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('min-02')?></h1>
    <h2 class="left"><?=($pagination->num_rows <= 0 ? $langBase->get('err-06') : $langBase->get('min-35', array('-NUM-' => $pagination->num_rows)))?></h2>
    <div class="clear"></div>
    <?php
		if ($pagination->num_rows > 0)
		{
			$setRead = array();
			
			echo '<div class="logevents_wrap left" style="width: 460px;">';
			echo $pagination_links;
			
			foreach ($days as $day)
			{
				$theEvents = $events[$day];
	?>
    <div class="bg_c c_1" style="width: 440px;">
    	<h1 class="big"><?=trim(View::Time($theEvents[0]['time'], true, '', true))?></h1>
        <?php
			foreach ($theEvents as $event)
			{
				if ($event['read'] == 0) $setRead[] = $event['id'];
		?>
        <div class="entry_wrap" id="entry_<?=$event['id']?>">
        	<div class="top"><span></span></div>
            <div class="middle"><?php echo $langBase->getLogEventText($event['type'], unserialize(base64_decode($event['data'])));?></div>
            <div class="bottom"><?=($event['read'] == 0 ? '<span class="unread">'.$langBase->get('min-36').'</span>' : '')?><?=($event['archived'] == 1 ? ' <span class="archived">'.$langBase->get('min-37').'</span>' : '')?><?=date('H:i:s', $event['time'])?><div class="right"><?php if($event['archived'] == 0){ ?><a href="#" class="archive" rel="<?=$event['id']?>"><img src="<?=$config['base_url']?>images/icons/textfield_delete.png" alt="" title="" /></a><?php }?></div></div>
        </div>
        <?php
			}
		?>
    </div>
    <?php
			}
			
			echo $pagination_links;
			echo '</div>';
			
			if (count($setRead) > 0)
				$db->Query("UPDATE `logevents` SET `read`='1' WHERE `id` IN(".implode(',', $setRead).")");
	?>
    <div class="left" style="width: 120px; margin-left: 15px;">
    	<div class="bg_c c_1" style="width: 100px;">
        	<h1 class="big"><?=$langBase->get('min-38')?></h1>
            <p class="center"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$option?>&amp;b=<?=$subOption?><?=(isset($_GET['sa']) ? '' : '&amp;sa')?>" class="button"><?=(isset($_GET['sa']) ? $langBase->get('min-40') : $langBase->get('min-39'))?></a></p>
        </div>
    </div>
    <div class="clear"></div>
    <?php
		}
	?>
</div>
<?php
		}
		
	}elseif ($option == 'user')
	{
?>
<p class="center nomargin">
	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$option?>&amp;b=main" class="button big<?=($subOption == 'main' ? ' active' : '')?>"><?=$langBase->get('subMenu-17')?></a>
    <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$option?>&amp;b=players" class="button big<?=($subOption == 'players' ? ' active' : '')?>"><?=$langBase->get('txt-43')?></a>
    <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$option?>&amp;b=sessions" class="button big<?=($subOption == 'sessions' ? ' active' : '')?>"><?=$langBase->get('min-41')?></a>
    <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$option?>&amp;b=smartmenu" class="button big<?=($subOption == 'smartmenu' ? ' active' : '')?>"><?=$langBase->get('min-42')?></a>
</p>
<?php
		if ($subOption == 'main')
		{
?>
<div style="width: 270px;" class="left">
    <div class="bg_c" style="width: 250px;">
    	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
        <dl class="dd_right">
        	<dt>ID</dt>
            <dd>#<?=User::Data('id')?></dd>
            <dt><?=$langBase->get('min-06')?></dt>
            <dd><span class="toggleHTML" title="<?=View::strTime(time()-User::Data('reg_time'), 1, ', ', 0)?> siden"><?=View::Time(User::Data('reg_time'), true)?></span></dd>
            <dt><?=$langBase->get('home-14')?></dt>
            <dd><a href="mailto:<?=User::Data('email')?>"><?=User::Data('email')?></a></dd>
            <dt><?=$langBase->get('min-43')?></dt>
            <dd><?=User::Data('IP_regged_with')?></dd>
            <dt><?=$langBase->get('min-44')?></dt>
            <dd><?=User::Data('IP_last')?></dd>
        </dl>
        <div class="clear"></div>
    </div>
</div>
<div style="width: 350px; margin-left: 20px;" class="left">
    <div class="bg_c" style="width: 330px;">
    	<h1 class="big"><?=$langBase->get('txt-43')?></h1>
        <div class="clear"></div>
        <table class="table">
            <thead>
                <tr>
                    <td width="30%"><?=$langBase->get('txt-02')?></td>
                    <td width="70%"><?=$langBase->get('txt-22')?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($players as $player)
                {
                    $i++;
                    $c = $i%2 ? 1 : 2;
                    
                    $status = 'In viata - <b>activ</b>';
                    if ($player['health'] <= 0) $status = '<span style="color: #ff0000">Mort</span> - <b>inactiv</b>';
                    if ($player['level'] <= 0) $status = '<span style="color: #ff0000">Dezactivat</span> - <b>inactiv</b>';
                ?>
                <tr class="c_<?=$c?>">
                    <td width="40%"><?=View::Player($player)?></td>
                    <td width="60%">
                        <dl class="dt_70 nomargin" style="font-size: 10px; color: #666666;">
                            <dt><?=$langBase->get('ot-status')?></dt>
                            <dd><?=$status?></dd>
                            <dt><?=$langBase->get('ot-lasta')?></dt>
                            <dd><span class="toggleHTML" title="<?=View::strTime(time()-$player['last_active'], 1, ', ', 0, '0 sec')?>"><?=View::Time($player['last_active'], true)?></span></dd>
                            <dt><?=$langBase->get('min-45')?></dt>
                            <dd><span class="toggleHTML" title="<?=View::strTime(time()-$player['created'], 1, ', ', 0)?>"><?=View::Time($player['created'], true)?></span></dd>
                        </dl>
                        <div class="clear"></div>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="clear"></div>
<?php
		} elseif ($subOption == 'players')
		{
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
    <?php
		$sql     = $db->Query("SELECT id,level,health,created,last_active,cash,bank,rank,rankpoints,name FROM `[players]` WHERE `userid`='".User::Data('id')."' ORDER BY id DESC");
		$players = $db->FetchArrayAll($sql);
	?>
    <table class="table">
    	<thead>
        	<tr>
            	<td width="23%"><?=$langBase->get('txt-02')?></td>
                <td width="32%"><?=$langBase->get('ot-status')?></td>
                <td width="21%"><?=$langBase->get('min-07')?></td>
                <td width="24%"><?=$langBase->get('ot-rank')?></td>
            </tr>
        </thead>
        <tbody>
       	<?php
			foreach ($players as $player)
			{
				$i++;
				$c = $i%2 ? 1 : 2;
				
				$status = '<b>'.$langBase->get('min-46').'</b>';
				if ($player['health'] <= 0) $status = '<span style="color: #ff0000">'.$langBase->get('min-47').'</span>';
				if ($player['level'] <= 0) $status = '<span style="color: #ff0000">'.$langBase->get('min-48').'</span>';
		?>
        	<tr class="c_<?=$c?>">
            	<td width="10%"><?=View::Player($player)?></td>
                <td width="10%">
                	<dl class="dt_70 nomargin" style="font-size: 10px; color: #666666;">
                        <dt><?=$langBase->get('ot-status')?></dt>
                        <dd><?=$status?></dd>
                        <dt><?=$langBase->get('min-46')?></dt>
                        <dd><span class="toggleHTML" title="<?=View::strTime(time()-$player['last_active'], 1, ', ', 0, '0 sec')?>"><?=View::Time($player['last_active'], true)?></span></dd>
                        <dt><?=$langBase->get('min-45')?></dt>
                        <dd><span class="toggleHTML" title="<?=View::strTime(time()-$player['created'], 1, ', ', 0)?>"><?=View::Time($player['created'], true)?></span></dd>
                    </dl>
                    <div class="clear"></div>
                </td>
                <td width="10%"><span class="subtext"><?=$langBase->get('ot-money')?>: <?=View::CashFormat($player['cash'])?> $<br /><?=$langBase->get('min-49')?>: <?=View::CashFormat($player['bank'])?> $<br /><?=$langBase->get('min-20')?>: <?=View::CashFormat($player['cash']+$player['bank'])?> $</span></td>
                <td width="10%"><?=$config['ranks'][$player['rank']][0]?> (<?=View::AsPercent($player['rankpoints']-$config['ranks'][$player['rank']][1], $config['ranks'][$player['rank']][2]-$config['ranks'][$player['rank']][1], 2)?> %)</td>
            </tr>
        <?php
			}
		?>
        </tbody>
    </table>
</div>
<?php
		} elseif ($subOption == 'sessions')
		{
			$show = in_array($_GET['show'], array('as', 'is')) ? $_GET['show'] : 'as';
?>
<p class="center" style="margin-top: 20px;">
	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$option?>&amp;b=<?=$subOption?>&amp;show=as" class="button<?=($show == 'as' ? ' active' : '')?>"><?=$langBase->get('min-50')?></a>
    <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$option?>&amp;b=<?=$subOption?>&amp;show=is" class="button<?=($show == 'is' ? ' active' : '')?>"><?=$langBase->get('min-51')?></a>
</p>
<?php
			if ($show == 'as')
			{
				$sql = $db->Query("SELECT id,IP,Time_start,Last_updated FROM `[sessions]` WHERE `Userid`='".User::Data('id')."' AND `Active`='1' ORDER BY Last_updated DESC");
				$sessions = $db->FetchArrayAll($sql);
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('min-50')?></h1>
    <?php
		if (count($sessions) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
			
			if (isset($_POST['sessions']))
			{
				$sess = $db->EscapeString($_POST['sessions']);
				
				$deleted = 0;
				
				foreach ($sess as $session)
				{
					$session = $sessions[$session];
					
					if ($_SESSION['MZ_LoginData']['validate'] != $session['Validate'] && $session['id'] != "")
					{
						$db->Query("UPDATE `[sessions]` SET `Active`='0' WHERE `id`='".$session['id']."'");
						$deleted++;
					}
				}
			}
	?>
    <form method="post" action="">
        <table class="table boxHandle">
            <thead>
                <tr>
                    <td width="15%">#</td>
                    <td width="20%">IP</td>
                    <td width="20%"><?=$langBase->get('min-52')?></td>
                    <td width="25%"><?=$langBase->get('ot-lasta')?></td>
                    <td width="20%"><?=$langBase->get('txt-35')?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($sessions as $sess_id => $session)
                    {
                        $i++;
                        $c = $i%2 ? 1 : 2;
                ?>
                <tr class="c_<?=$c?> boxHandle">
                    <td width="15%"><input type="checkbox" name="sessions[]" value="<?=$sess_id?>"<?php if ($_SESSION['MZ_LoginData']['validate'] == $session['Validate']) echo ' disabled="disabled"'; ?> />#<?=$session['id']?></td>
                    <td width="20%" class="center"><?=$session['IP']?></td>
                    <td width="20%"><span class="toggleHTML" title="<?=View::strTime(time()-$session['Time_start'], 1, ', ', 0)?>"><?=View::Time($session['Time_start'], true)?></span></td>
                    <td width="25%"><span class="toggleHTML" title="<?=View::strTime(time()-$session['Last_updated'], 1, ', ', 0, '0 sec')?>"><?=View::Time($session['Last_updated'], true)?></span></td>
                    <td width="20%"><?=View::strTime(time()-$session['Time_start'], 1)?></td>
                </tr>
                <?php
                    }
                ?>
                <tr class="center">
                    <td colspan="5"><input type="submit" value="Submit" /></td>
                </tr>
            </tbody>
        </table>
    </form>
    <?php
		}
	?>
</div>
<?php
			}else
			{
				
				$sql = "SELECT id,IP,Time_start,Last_updated FROM `[sessions]` WHERE `Userid`='".User::Data('id')."' AND `Active`='0' ORDER BY id DESC";
				
				$pagination = new Pagination($sql, 15, 'p');
				$pagination_links = $pagination->GetPageLinks();
				$sessions = $pagination->GetSQLRows();
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('min-51')?></h1>
    <?php
		if (count($sessions) <= 0)
		{
			echo '<p>'.$langBase->get('err-06').'</p>';
		}
		else
		{
?>
	<table class="table">
        <thead>
            <tr>
                    <td width="15%">#</td>
                    <td width="20%">IP</td>
                    <td width="20%"><?=$langBase->get('min-52')?></td>
                    <td width="25%"><?=$langBase->get('ot-lasta')?></td>
                    <td width="20%"><?=$langBase->get('txt-35')?></td>
            </tr>
        </thead>
        <tbody>
        	<?php
				foreach ($sessions as $session)
				{
					$i++;
					$c = $i%2 ? 1 : 2;
			?>
            <tr class="c_<?=$c?>">
                <td width="15%">#<?=$session['id']?></td>
                <td width="20%" class="center"><?=$session['IP']?></td>
                <td width="20%"><span class="toggleHTML" title="<?=View::strTime(time()-$session['Time_start'], 1, ', ', 0)?>"><?=View::Time($session['Time_start'], true)?></span></td>
                <td width="25%"><span class="toggleHTML" title="<?=View::strTime(time()-$session['Last_updated'], 1, ', ', 0, '0 sec')?>"><?=View::Time($session['Last_updated'], true)?></span></td>
                <td width="20%"><?=View::strTime(time()-$session['Time_start'], 1)?></td>
            </tr>
            <?php
				}
			?>
            <tr class="c_3"><td colspan="5"><?=$pagination_links?></td></tr>
        </tbody>
	</table>
<?php
		}
?>
</div>
<?php
			}
			
		}
		elseif ($subOption == 'smartmenu')
		{
			$current_links = unserialize(User::Data('smart_menu'));
			
			if (isset($_POST['scriptID']))
			{
				$use = isset($_POST['use_smartmenu']) ? 1 : 0;
				$scripts = $db->EscapeString($_POST['scriptID']);
				
				$db->Query("UPDATE `[users]` SET `use_smartMenu`='".$use."'".(count($scripts) <= $config['max_smartMenu_items'] ? ", `smart_menu`='".serialize($scripts)."'" : "")." WHERE `id`='".User::Data('id')."'");
				
				View::Message((count($scripts) > $config['max_smartMenu_items'] ? 'You can chose more than '.$config['max_smartMenu_items'].' links.' : 'Successfully saved.'), (count($scripts) > $config['max_smartMenu_items'] ? 2 : 1), true);
			}
?>
<div class="bg_c w200">
	<h1 class="big"><?=$langBase->get('min-42')?></h1>
    <form method="post" action="">
    	<p><?=$langBase->get('min-53', array('-MAX-' => $config['max_smartMenu_items']))?></p>
    	<p><input type="checkbox" name="use_smartmenu" id="use_smartmenu"<?php if(User::Data('use_smartMenu') == 1) echo ' checked="checked"'; ?> /> <label for="use_smartmenu"><?=$langBase->get('min-54')?></label></p>
        <p class="text center"></p>
        <table class="table boxHandle">
        	<thead>
            	<tr>
                	<td><?=$langBase->get('min-55')?></td>
                </tr>
            </thead>
            <tbody>
            <?php
			foreach ($config['scripts'] as $s_id => $script)
			{
				if ($script[5] !== true)
					continue;
				
				$i++;
				$c = $i%2 ? 1 : 2;
			?>
            	<tr class="c_<?=$c?> boxHandle">
                	<td><input type="checkbox" name="scriptID[]" class="scriptID" value="<?=$s_id?>"<?php if(in_array($s_id, $current_links)) echo ' checked="checked"'; ?> /><?=$script[0]?></td>
                </tr>
            <?php
			}
			?>
            	<tr class="c_3 center">
                	<td><input type="submit" value="<?=$langBase->get('min-56')?>" /></td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
<?php
		}
	}
	elseif ($option == 'ref')
	{
?>
<p class="center nomargin">
	<a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$option?>&amp;b=main" class="button big<?=($subOption == 'main' ? ' active' : '')?>"><?=$langBase->get('txt-22')?></a>
    <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;a=<?=$option?>&amp;b=players" class="button big<?=($subOption == 'players' ? ' active' : '')?>"><?=$langBase->get('min-57')?></a>
</p>
<?php
	if ($subOption == 'main')
	{
?>
<div class="bg_c w300">
	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
    <h2 align="center"><?=$langBase->get('min-58')?></h2>
    <p><?=$langBase->get('min-59', array('-RANK-' => $config['ranks'][$config['enlist_min_rank']][0], '-COINS-' => $config['enlist_reward_points']))?></p><br>
    
    <h2><?=$langBase->get('concurs-05')?>:<br /></h2>
    <p class="center"><input type="text" class="flat" onfocus="this.select()" value="<?=$config['game_url']?>/reg/<?=User::Data('id')?>" style="min-width: 295px; width: 295px;" /></p>
    <p><?=$langBase->get('min-60')?></p><br>
    <p class="large"><?=$langBase->get('min-61')?>: <b><?=$config['enlist_reward_points']?> <?=$langBase->get('ot-points')?></b></p>
</div>
<?}else if ($subOption == 'players'){?>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('min-57')?></h1>
	<table class="table">
    	<thead>
        	<tr class="small">
            	<td>#</td>
            	<td><?=$langBase->get('txt-06')?></td>
				<td><?=$langBase->get('min-06')?></td>
				<td><?=$langBase->get('concurs-04')?>?</td> 
            </tr>
        </thead>
        <tbody>
		<?php
		$pagination = new Pagination("SELECT * FROM `[users]` WHERE `enlisted_by`='".User::Data('id')."' ORDER BY reg_time DESC", 25, 'p');
		$orders = $pagination->GetSQLRows();
		$pagination_links = $pagination->GetPageLinks();
		
		foreach ($orders as $order)
		{
		$i++;
		$c = $i%2 ? 1 : 2;
		
		if($_GET['p'] > 1){$x = ($_GET['p'] - 1) * 25;}
		
		$sql = $db->Query("SELECT * FROM `[players]` WHERE `userid`='".$order['id']."' AND `null`='0'");
		$usr = mysql_fetch_object($sql);
        ?>
        	<tr class="c_<?=$c?>">
            	<td><?=($i + $x)?></td>
            	<td><?=View::Player(array('id' => $usr->id))?></td>
				<td><?=View::Time($order['reg_time'], false, 'H:m:i')?></td>
				<td><?=($usr->rank >= $config['enlist_min_rank'] ? "<b>".$langBase->get('ot-yes')."</b>" : $langBase->get('ot-no'))?></td>
            </tr>
        <?php
        }
		?>
    	</tbody>
    </table>
    	<tr class="c_3">
        	<td colspan="7"><?=$pagination_links?></td>
        </tr>
</div>
<?php
	}}
?>