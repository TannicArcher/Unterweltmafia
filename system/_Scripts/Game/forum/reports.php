<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$sql = "SELECT id,playerid,post_type,post_id,reason,treatment,reported FROM `forum_reports` WHERE";
	$sql .= isset($_GET['treated']) ? " `treatment`!='0'" : " `treatment`='0'";
	$sql .= isset($_GET['player']) ? " AND `playerid`='".$db->EscapeString($_GET['player'])."'" : "";
	$sql .= " ORDER BY id DESC";
	
	
	$pagination = new Pagination($sql, 20, 'p');
	
	$pagination_rows = $pagination->GetSQLRows();
	
	$pagination_links = $pagination->GetPageLinks();
?>
<ul class="breadcrumb" style="margin: 0px auto;">
	<li class="home"><a href="<?=$config['base_url']?>?side=forum/"><img src="<?=$config['base_url']?>images/icons/home_icon.png" alt="" /></a></li>
    <li class="last"><a href="<?=$config['base_url']?>?side=forum/reports">Repoted Posts</a></li>
</ul>
<?php	
	if( count($pagination_rows) <= 0 ){
		echo '<h2 class="center">No posts reported yet! <a href="'.$config['base_url'].'?side='.$_GET['side'].'&amp;treated">View checked reports</a>.</h2>';
		
	}else{
		
		if( isset($_GET['player']) || isset($_GET['treated']) ){
			
			$viewing .= isset($_GET['player']) ? ' &raquo; Only reported by '.View::Player(array('id' => $db->EscapeString($_GET['player']))) : '';
			$viewing .= isset($_GET['treated']) ? ' &raquo; Checked' : '';
			
			$viewing .= ' &raquo; <a href="'.$config['base_url'].'?side='.$_GET['side'].'&amp;p='.$pagination->current_page.'">Remove Filter</a>';
			
		}else{
			
			$viewing = 'Show all';
			
		}
		
		
		
		if( isset($_POST['reports']) ){
			
			$reports = $db->EscapeString($_POST['reports']);
			
			if( count($reports) > 0 ){
				
				$total_treated = 0;
				
				foreach( $reports as $report )
				{
					$sql = "SELECT id FROM `forum_reports` WHERE `id`='".$report."' AND `treatment`='0'";
					$report = $db->QueryFetchArray($sql);
					
					if( $report['id'] != "" ){
						$db->Query("UPDATE `forum_reports` SET `treatment`='3' WHERE `id`='".$report['id']."'");
						$total_treated++;
					}
					
				}
				
				$msg_type = $total_treated <= 0 ? 2 : 1;
				
				View::Message($total_treated.' reports checked.', $msg_type, true);
				
			}else{
				
				View::Message('Please select at leas 1 report!', 2, true);
				
			}
			
			
		}
?>
<form method="post" action="">
<table class="table boxHandle">
	<thead>
    	<tr>
        	<td width="5%"><a href="#" class="checkAll" rel="name,reports[]">Select</a></td>
        	<td width="20%">Player</td>
            <td width="27%">Reason</td>
            <td width="22%">Message</td>
            <td width="13%">Status</td>
            <td width="13%">Date</td>
        </tr>
    </thead>
    <tbody>
    	<tr class="c_3"><td colspan="6" class="center"><?=$viewing?> &raquo; <a href="<?=$config['base_url']?>?side=<?=$_GET['side']?><?php echo isset($_GET['player']) ? '&amp;player='.$_GET['player'] : ''; ?>&amp;p=<?=$pagination->current_page?><?php echo isset($_GET['treated']) ? '' : '&amp;rezolvat'; ?>"><?php echo isset($_GET['treated']) ? 'Hide' : 'Show'; ?> checked</a></td></tr>
    	<?php
			$players_num_reports_temp = array();
			
			foreach( $pagination_rows as $report ):
				
				$i++;
				$class = $i%2 ? 1 : 2;
				
				$table = $report['post_type'] == 'topic' ? 'forum_topics' : 'forum_replies';
				$select = $report['post_type'] == 'topic' ? ',locked,title,forum_id' : ',topic_id';
				$sql = "SELECT id,deleted$select FROM `$table` WHERE `id`='".$report['post_id']."'";
				$post = $db->QueryFetchArray($sql);
				
				if( $post['deleted'] == 0 && $post['locked'] == 0 ){
					$treatment = '<span style="color: #ff6600;">Pending</span>';
					
				}elseif( $report['treatment'] == 3 ){
					$treatment = 'Elimina din lista!';
					
				}else{
					if( $post['deleted'] == 1 ){
						$treatment = '<span style="color: #ff0000;">Deleted</span>';
						
					}elseif( $post['locked'] == 1 ){
						$treatment = '<span style="color: #401a00;">Blocked</span>';
					}
					
				}
				
				if( $post['deleted'] == 1 && in_array($report['treatment'], array(0, 2)) ){
					$db->Query("UPDATE `forum_reports` SET `treatment`='1' WHERE `id`='".$report['id']."'");
					
				}elseif( $post['locked'] == 1 && in_array($report['treatment'], array(1, 0)) ){
					$db->Query("UPDATE `forum_reports` SET `treatment`='2' WHERE `id`='".$report['id']."'");
					
				}
				
				
				$reports_by_player = $players_num_reports_temp[$report['playerid']];
				if( $reports_by_player == "" ){
					$sql = $db->Query("SELECT id FROM `forum_reports` WHERE `playerid`='".$report['playerid']."'");
					$players_num_reports_temp[$report['playerid']] = $db->GetNumRows($sql);
				}
				$reports_by_player = $players_num_reports_temp[$report['playerid']];
				
				if( $report['post_type'] == 'topic' ){
					$topic = $post;
				}else{
					$sql = "SELECT forum_id,id,title FROM `forum_topics` WHERE `id`='".$post['topic_id']."'";
					$topic = $db->QueryFetchArray($sql);
					$fr = $post['deleted'] == 0 ? '&amp;fr='.$post['id'] : '';
				}
				
				$forum = $config['forums'][$topic['forum_id']];
				
				$var = $report['post_type'] == 'reply' ? 'Answer ' : '';
				$title = "<a href=\"".$config['base_url']."?side=forum/topic&amp;id=".$topic['id']."$fr\">$var&laquo;".View::NoHTML($topic['title'])."&raquo;</a><br /><a href=\"".$config['base_url']."?side=forum/forum&amp;f=".$topic['forum_id']."\" class=\"subtext\">".$forum[0]."</a>";
		?>
        <tr class="boxHandle c_<?=$class?>">
        	<td width="25%" colspan="2"><input type="checkbox" name="reports[]" value="<?=$report['id']?>" /><?=View::Player(array('id' => $report['playerid']))?><br /><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;player=<?=$report['playerid']?><?php echo isset($_GET['treated']) ? '&amp;treated' : ''; ?>" class="subtext"><?=$reports_by_player?> reports</a></td>
            <td width="27%"><?=nl2br(View::NoHTML($report['reason']))?></td>
            <td width="22%"><?=$title?></td>
            <td width="13%" class="center"><?=$treatment?></td>
            <td width="13%" class="center"><?=View::Time($report['reported'])?></td>
        </tr>
        <?php
			endforeach;
		?>
        <tr class="c_3"><td colspan="6" style="padding: 10px;"><?=$pagination_links?></td></tr>
        <tr class="c_3 center"><td colspan="6" style="padding: 5px;"><input type="submit" value="Mark as fixed" /></td></tr>
    </tbody>
</table>
</form>
<?php
	}
?>