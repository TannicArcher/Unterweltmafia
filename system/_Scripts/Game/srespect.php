<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
if(isset($_GET['nick']) && Player::Data('id') != ''){
	$nick = $db->EscapeString(trim($_GET['nick']));
	$limit = (Player::Data('vip_days') > 0 ? $config['respect_v_user'] : $config['respect_f_user']);
	$sql = $db->Query("SELECT id,userid FROM `[players]` WHERE `name`='".$nick."' AND `level`>'0' AND `health`>'0'");
	$player = $db->FetchArray($sql);
	
	if($player['id'] == ''){
		View::Message($langBase->get('respect-03'), 2, true, '/game/s/'.$_GET['nick']);
	}elseif(Player::Data('rank') < $config['respect_nivel'] && Player::Data('level') < 3){
		View::Message($langBase->get('respect-06', array('-RANK-' => $config['ranks'][$config['respect_nivel']][0])), 1, true, '/game/s/'.$_GET['nick']);
	}elseif(Player::Data('s_respect') > $limit){
		View::Message($langBase->get('respect-01', array('-NUM-' => $limit)), 1, true, '/game/s/'.$_GET['nick']);
	}elseif($db->GetNumRows($db->Query("SELECT id FROM `sent_respect` WHERE `sender`='".Player::Data('id')."' AND `receiver`='".$player['id']."'")) > 0){
		View::Message($langBase->get('respect-02'), 2, true, '/game/s/'.$_GET['nick']);
	}elseif(Player::Data('id') == $player['id']){
		View::Message($langBase->get('respect-04'), 2, true, '/game/s/'.$_GET['nick']);
	}else{
		$db->Query("UPDATE `[players]` SET `s_respect`=`s_respect`+'1' WHERE `id`='".Player::Data('id')."'");
		$db->Query("UPDATE `[players]` SET `respect`=`respect`+'1' WHERE `id`='".$player['id']."'");
		$db->Query("INSERT INTO `sent_respect` (`sender`, `receiver`, `date`)VALUES('".Player::Data('id')."', '".$player['id']."', '".time()."')");
		
		
		$sql = $db->Query("SELECT id,userid,rank,rankpoints FROM `[players]` WHERE `id`='".$player['id']."'");
		$player_data = $db->FetchArray($sql);
		$mission = new Mission($player_data);
		$mission_data = $mission->missions_data[$mission->current_mission];
						
		if ($mission->current_mission == 7 && $mission_data['started'] == 1)
		{
			if ($mission_data['objects'][1]['completed'] != 1)
			{
				$num = $mission_data['objects'][1]['recv'] + 1;
				$mission_data['objects'][1]['recv'] = $num;
				$mission->missions_data[$mission->current_mission]['objects'][1]['recv'] = $num;
				$mission->saveMissionData();
					
				if ($num >= 10)
				{
					$mission->completeObject(1);
				}
			}
		}
		
		
		$sql = $db->Query("SELECT id,userid,rank,rankpoints FROM `[players]` WHERE `id`='".Player::Data('id')."'");
		$player_data = $db->FetchArray($sql);
		$player_mission = new Mission($player_data);
		$player_mission_data = $player_mission->missions_data[$player_mission->current_mission];
		if ($player_mission->current_mission == 7 && $player_mission_data['started'] == 1)
		{
			if ($player_mission_data['objects'][0]['completed'] != 1)
			{
				$num = $player_mission_data['objects'][0]['sent'] + 1;
				$player_mission_data['objects'][0]['sent'] = $num;
				$player_mission->missions_data[$player_mission->current_mission]['objects'][0]['sent'] = $num;
				$player_mission->saveMissionData();
					
				if ($num >= 10)
				{
					$player_mission->completeObject(0);
				}
			}
		}
		
		
		Accessories::AddLogEvent($player['id'], 55, array(
				'-SENDER_NAME-' => Player::Data('name')
			), $player['userid']);
		
		View::Message($langBase->get('respect-05'), 1, true, '/game/s/'.$_GET['nick']);
	}
}
?>