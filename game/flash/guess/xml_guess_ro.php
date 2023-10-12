<?
define(BASEPATH, true);
require('../../../system/config.php');
$win = "false";
if(!isset($_GET['money']) || $_GET['money'] < 100){
	$error = "Der Einsatz muss mindestens 100$ betragen!";
}else{
	$bidMoney = $db->EscapeString($_GET['money']);
	if($bidMoney > Player::Data('cash')){
		$error = "Du hast nicht genug Geld!";
	}else{
		if($_GET['number'] == ""){
			$error = "Bitte klicke eine Zahl!";
		}else{
			$numar = rand(1,10);
			if($numar == $_GET['number']){
				$win = "true";
			}
			$money = $bidMoney*5;
			if($win == "false"){
				$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$bidMoney."' WHERE `id`='".Player::Data('id')."'");
			}if($win == "true"){
				$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$money."' WHERE `id`='".Player::Data('id')."'");
			}
		}
	}
}?>number=<?=$numar?>&money=<?=$money?>&win=<?=$win?>&errormsg=<?=urlencode($error)?>
