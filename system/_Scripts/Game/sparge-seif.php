<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$cfg['rankPoints'] = (Player::Data('rank') < 5 ? 80 : (Player::Data('rank') <= 10 ? 160 : 320));
	$cfg['dsCash'] = 75000;
	$cfg['dsBullet'] = 50;

	$n[1] = mt_rand(0,9)."-".mt_rand(0,9)."-".mt_rand(0,9)."-".mt_rand(0,9);
	$n[2] = mt_rand(0,9)."-".mt_rand(0,9)."-".mt_rand(0,9)."-".mt_rand(0,9);
	$n[3] = mt_rand(0,9)."-".mt_rand(0,9)."-".mt_rand(0,9)."-".mt_rand(0,9);
	$n[4] = mt_rand(0,9)."-".mt_rand(0,9)."-".mt_rand(0,9)."-".mt_rand(0,9);
	$wnr = mt_rand(1,4);

	if(isset($_POST['submit']) && Player::Data('id') != ""){
		$miza = 10 * View::NumbersOnly($_POST['miza']);
		$cash = $cfg['dsCash'] * View::NumbersOnly($_POST['miza']);
		$bullet = $cfg['dsBullet'] * View::NumbersOnly($_POST['miza']);
		$rankPoints = $cfg['rankPoints'] * View::NumbersOnly($_POST['miza']);

		if(Player::Data('points') < $miza){
			View::Message($langBase->get('err-09'), 2, true);
		}elseif($miza < 10 || $miza > 50 || !is_numeric($miza)){
			View::Message($langBase->get('seif-01'), 2, true);
		}elseif($_POST['safe'] < 1 || $_POST['safe'] > 4 || $_POST['safe'] == ""){
			View::Message($langBase->get('seif-02'), 2, true);
		}elseif($_POST['safe'] != $wnr){
			$db->Query("UPDATE `[players]` SET `points`=`points`-'".$miza."' WHERE `id`='".Player::Data('id')."'");
			View::Message($langBase->get('seif-03', array('-NR-' => $wnr)), 2, true);
		}else{
			if ($player_mission->current_mission == 4 && $player_mission_data['started'] == 1 && $player_mission_data['objects'][1]['completed'] != 1)
			{
				$player_mission->completeObject(1);
			}
			elseif($player_mission->current_mission == 9 && $player_mission_data['started'] == 1 && $player_mission_data['objects'][5]['completed'] != 1)
			{
				$wins = $player_mission_data['objects'][5]['wins'] + 1;
				$player_mission_data['objects'][5]['wins'] = $wins;
				$player_mission->missions_data[$player_mission->current_mission]['objects'][5]['wins'] = $wins;
				$player_mission->saveMissionData();
				
				if ($wins >= 3)
				{
					$player_mission->completeObject(5);
				}
			}
		
			if (in_array(7, $player_mission->active_minimissions))
			{
				$player_mission->minimissions[7]['data']['num']++;
				$player_mission->miniMissions_save();
				
				if ($player_mission->minimissions[7]['data']['num'] >= 5)
				{
					$player_mission->miniMission_success(7);
				}
			}
		
			$db->Query("UPDATE `[players]` SET `points`=`points`+'".$miza."', `cash`=`cash`+'".$cash."', `rankpoints`=`rankpoints`+'".$rankPoints."', `bullets`=`bullets`+'".$bullet."' WHERE `id`='".Player::Data('id')."'");
			View::Message($langBase->get('seif-04', array('-CASH-' => View::CashFormat($cash), '-COINS-' => $miza * 2, '-BULLETS-' => View::CashFormat($bullet))), 1, true);
		}
	}
?>
<div class="bg_c w500">
	<h1 class="big"><?=$langBase->get('function-sparge_seif')?></h1>
	<p><?=$langBase->get('seif-05')?><br><br></p>
	<form method="post" name="loandata">
	<table class="table">
    	<thead>
			<tr><td colspan="3"><img src="images/icons/stats_safe.png" /></td></tr>
        </thead>
        <tbody>
			<tr class="c_1">
            	<td class="center"><?=$langBase->get('seif-06')?></td>
                <td>
					<select name="miza" onchange="calculate();">
						<option value="1">10 <?=$langBase->get('ot-points')?></option>
						<option value="2">20 <?=$langBase->get('ot-points')?></option>
						<option value="3">30 <?=$langBase->get('ot-points')?></option>
						<option value="4">40 <?=$langBase->get('ot-points')?></option>
						<option value="5">50 <?=$langBase->get('ot-points')?></option>
					</select>
				</td>
                <td class="t_left" width="41%"><?=$langBase->get('seif-07', array('-CASH-' => $cfg['dsCash'], '-BULLETS-' => $cfg['dsBullet'], '-RANK-' => (View::AsPercent($cfg['rankPoints'], $config['ranks'][Player::Data('rank')][2]-$config['ranks'][Player::Data('rank')][1], 2))))?></td>
            </tr>
        	<tr class="c_2">
            	<td class="center" rowspan="4"><?=$langBase->get('seif-08')?></td>
                <td><input type="radio" name="safe" value="1"/> <?=$n[1]?></td>
                <td class="center" rowspan="4"><input type="submit" name="submit" value="<?=$langBase->get('seif-09')?>"></td>
            </tr>
			<tr class="c_1">
				<td><input type="radio" name="safe" value="2"/> <?=$n[2]?></td>
			</tr>
			<tr class="c_1">
				<td><input type="radio" name="safe" value="3"/> <?=$n[3]?></td>
			</tr>
			<tr class="c_1">
				<td><input type="radio" name="safe" value="4"/> <?=$n[4]?></td>
			</tr>
        	<tr class="c_3"><td colspan="4"></td></tr>
        </tbody>
    </table>
	</form>
<script type="text/javascript">
	function calculate() {
		var principal = document.loandata.miza.value;
		if (!isNaN(principal)) {
			document.getElementById("points").innerHTML = Math.round(principal * 20 * 100)/100;
			document.getElementById("cash").innerHTML = Math.round(principal * <?=$cfg['dsCash']?> * 100)/100;
			document.getElementById("bullet").innerHTML = Math.round(principal * <?=$cfg['dsBullet']?> * 100)/100;
			document.getElementById("rank").innerHTML = Math.round(principal * <?=View::AsPercent($cfg['rankPoints'], $config['ranks'][Player::Data('rank')][2]-$config['ranks'][Player::Data('rank')][1], 2)?> * 100)/100;
		} else {
			document.getElementById("points").innerHTML = "20";
			document.getElementById("cash").innerHTML = "<?=$cfg['dsCash']?>";
			document.getElementById("bullet").innerHTML = "<?=$cfg['dsBullet']?>";
			document.getElementById("rank").innerHTML = "<?=View::AsPercent($cfg['rankPoints'], $config['ranks'][Player::Data('rank')][2]-$config['ranks'][Player::Data('rank')][1], 2)?>";
		}
	}
</script>
</div>