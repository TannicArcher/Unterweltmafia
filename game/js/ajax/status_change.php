<?php
	define('IS_AJAX', true);
	define('BASEPATH', true);
	require_once('../../../system/config.php');
	
	header($config['ajax_default_header']);

	if(!IS_ONLINE){
		die('ERROR:offline');
	}elseif($config['limited_access'] == true){
		die('ERROR:limited_access');
	}else{
		$new_status = $db->EscapeString($_GET['status']);
		$allowed_statuses = array(1, 2, 3);
		
		if(! in_array($new_status, $allowed_statuses) ){
			die('ERROR:status');
		}else{
			$statuses = array( array('Online', 'online'), array('Busy', 'busy'), array('Away', 'away') );

			if($new_status != Player::Data('status')){
				$db->Query("UPDATE `[players]` SET `status`='".$new_status."' WHERE `id`='".Player::Data('id')."'");
			}

			die("SUCCESS:".$statuses[$new_status-1][0].":".$statuses[$new_status-1][1]);
		}
	}
?>