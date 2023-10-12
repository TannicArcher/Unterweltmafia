<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

$cfg['maxbet'] = 250000;
$cfg['minbet'] = 100;

$n[1] = rand(1,6);
$n[2] = rand(1,6);
$n[3] = rand(1,6);
$nv[1] = rand(10000,99999);
$nv[2] = rand(10000,99999);
$nv[3] = rand(10000,99999);

$bet = View::NumbersOnly($_POST['bet']);

if(isset($_POST['submit']) && $bet > 0){
	if($bet < $cfg['minbet'] || $bet > $cfg['maxbet'] || $bet < 0){
		View::Message('Miza trebui sa fie cuprinsa intre '.View::CashFormat($cfg['minbet']).'$ si '.View::CashFormat($cfg['maxbet']).'$',2, true);
	}else if($bet > Player::Data('cash')){
		View::Message($langBase->get('err-01'),2, true);
	}else{
		if($n[1] == $n[2] && $n[2] == $n[3]){
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".($bet * 3)."' WHERE `id`='".Player::Data('id')."'");
			$msg = '<font color="green"><b>'.$langBase->get('slot-02', array('-CASH-' => View::CashFormat($bet * 4))).'</b></font>';
		
		$player_mission_data = $player_mission->missions_data[$player_mission->current_mission];
		if ($player_mission->current_mission == 6 && $player_mission_data['started'] == 1)
			{
				if ($player_mission_data['objects'][1]['completed'] != 1)
				{
					$wins = $player_mission_data['objects'][1]['wins'] + 1;
					$player_mission_data['objects'][1]['wins'] = $wins;
					$player_mission->missions_data[$player_mission->current_mission]['objects'][1]['wins'] = $wins;
						
					$player_mission->saveMissionData();
						
					if ($wins >= 5)
					{
						$player_mission->completeObject(1);
					}
				}
			}
		
		}else if($n[1] == $n[2] || $n[2] == $n[3]){
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".($bet/2)."' WHERE `id`='".Player::Data('id')."'");
			
		$player_mission_data = $player_mission->missions_data[$player_mission->current_mission];
		if ($player_mission->current_mission == 6 && $player_mission_data['started'] == 1)
			{
				if ($player_mission_data['objects'][1]['completed'] != 1)
				{
					$wins = $player_mission_data['objects'][1]['wins'] + 1;
					$player_mission_data['objects'][1]['wins'] = $wins;
					$player_mission->missions_data[$player_mission->current_mission]['objects'][1]['wins'] = $wins;
						
					$player_mission->saveMissionData();
						
					if ($wins >= 5)
					{
						$player_mission->completeObject(1);
					}
				}
			}
			
			$msg = '<font color="green"><b>'.$langBase->get('slot-02', array('-CASH-' => View::CashFormat($bet + ($bet/2)))).'</b></font>';
		}else{
			$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$bet."' WHERE `id`='".Player::Data('id')."'");
			$msg = '<font color="red"><b>'.$langBase->get('slot-03').'</b></font>';
		}
	}
?>
<script>
var t=setTimeout("alertMsg()",2000);
function alertMsg()
{
document.getElementById("result").innerHTML = '<?=$msg?>';
}
</script><?}?>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('slot-01')?></h1>
<div style="text-align: center; width: 100%; margin-top: 15px">
<div style="margin:0 auto; background:#2c2f38; padding: 15px; border: 1px solid #eee; width: 240px; height: 110px; text-align: center;">
<?if(isset($_POST['submit']) && $bet > 0){?>
<img src="images/slots/<?=$n[1];?>.gif?<?=$nv[1];?>" /><img src="images/slots/<?=$n[2];?>.gif?<?=$nv[2];?>" /><img src="images/slots/<?=$n[3];?>.gif?<?=$nv[3];?>" />
<b><?=$langBase->get('slot-04')?>:</b> <?=View::CashFormat($bet);?>$<br />
<b><?=$langBase->get('slot-05')?>:</b> <?=View::CashFormat($bet*4);?>$<br>
<div id="result"></div>
<?}else{?>
<center><b><?=$langBase->get('slot-06')?></b></center>
<?}?>
</div>
<br />
<form method="post">
<?=$langBase->get('bj-05')?>: <input type="text" class="flat" value="1 000 $" name="bet" />
<input type="submit" name="submit" value="<?=$langBase->get('slot-07')?>">
</form>
</div>
</div>