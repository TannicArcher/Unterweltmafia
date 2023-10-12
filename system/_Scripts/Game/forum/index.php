<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	if( Player::Data('forumban') != "" ){
		$ban = unserialize(Player::Data('forumban'));
		
		$stop = $ban['stop'];
		
		
		if($stop != 'never'){
			
			$timeleft = $ban['stop'] - time();
			
			if( $timeleft <= 0 ){
				$db->Query("UPDATE `[players]` SET `forumban`='' WHERE `id`='".Player::Data('id')."'");
				$forum = isset($_GET['f']) ? 'forum&f='.$_GET['f'] : '';
				View::Message('', 1, true, '/game/?side=forum/'.$forum);
			}
			
		}
?>
<div class="info_form" id="ban_info"><h2><?php echo $stop == 'never' ? $langBase->get('forum-01') : $langBase->get('forum-02', array('-TIME-' => View::Time($ban['stop']))); ?><br /><br /><?=$langBase->get('limit-04')?>:<br /><?=View::NoHTML($ban['ban_reason'])?></h2></div>
<?php
	}else{
	
	echo "<ul>";
	
	foreach(array(1 => 1, 2 => 2 , 3 => 3, 4 => 4) as $f_id => $info)
	{
		$forum = $config['forums'][$f_id];
		
		echo '<li><a href="' . $config['base_url'] . '?side=forum/forum&amp;f=' . $f_id . '">' . $forum[0] . '</a></li>';
	}
	
	echo "</ul>";
	}
?>