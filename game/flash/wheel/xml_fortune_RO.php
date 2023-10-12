<?
define(BASEPATH, true);
require('../../../system/config.php');
$premiu = rand(1,16);
$rank = rand(40,100);
$w_rank = View::AsPercent($rank, $config['ranks'][Player::Data('rank')][2]-$config['ranks'][Player::Data('rank')][1], 2);
$text = array("","Pech! Du hast leider nichts gewonnen!","Bravo! Du hast 5 Coins gewonnen.","Bravo! Du hast 10 Coins gewonnen.","Super! Du hast ".$w_rank." Rangfortschritt gewonnen.","Bravo! Du hast volle Gesundheit gewonnen.","Super! Du hast 1 000$ gewonnen.","Bravo! du hast 5 000$ gewonnen.","Bravo! Du hast 10 000$ gewonnen.","Pech! Du hast leider nichts gewonnen!","Super! Du hast 25 000$ gewonnen!","Bravo! Du hast 50 000$ gewonnen.","Super! du hast 100 000$ gewonnen.","Super! Du hast 50 Munition gewonnen.","Bravo! Du hast 100 Munition gewonnen.","Bravo! Du hast 150 Munition gewonnen.","Toll! Du hast 200 Munition gewonnen.");
$text = $text[$premiu];
?>
price=<?=$premiu;?>&winmsg=<?=$text;?>
<?
$wtt = (Player::Data('vip_days') > 0 ? 2700 : 3600);
if(Player::Data('roata_noroc') + $wtt < time()){
	if($premiu == 2){
		$db->Query("UPDATE `[players]` SET `points`=`points`+'5' WHERE `id`='".Player::Data('id')."'");
	}elseif($premiu == 3){
		$db->Query("UPDATE `[players]` SET `points`=`points`+'10' WHERE `id`='".Player::Data('id')."'");
	}elseif($premiu == 4){
		$db->Query("UPDATE `[players]` SET `rankpoints`=`rankpoints`+'".$rank."' WHERE `id`='".Player::Data('id')."'");
	}elseif($premiu == 5){
		$db->Query("UPDATE `[players]` SET `health`='300' WHERE `id`='".Player::Data('id')."'");
	}elseif($premiu == 6){
		$db->Query("UPDATE `[players]` SET `cash`=`cash`+'1000' WHERE `id`='".Player::Data('id')."'");
	}elseif($premiu == 7){
		$db->Query("UPDATE `[players]` SET `cash`=`cash`+'5000' WHERE `id`='".Player::Data('id')."'");
	}elseif($premiu == 8){
		$db->Query("UPDATE `[players]` SET `cash`=`cash`+'10000' WHERE `id`='".Player::Data('id')."'");
	}elseif($premiu == 10){
		$db->Query("UPDATE `[players]` SET `cash`=`cash`+'25000' WHERE `id`='".Player::Data('id')."'");
	}elseif($premiu == 11){
		$db->Query("UPDATE `[players]` SET `cash`=`cash`+'50000' WHERE `id`='".Player::Data('id')."'");
	}elseif($premiu == 12){
		$db->Query("UPDATE `[players]` SET `cash`=`cash`+'100000' WHERE `id`='".Player::Data('id')."'");
	}elseif($premiu == 13){
		$db->Query("UPDATE `[players]` SET `bullets`=`bullets`+'50' WHERE `id`='".Player::Data('id')."'");
	}elseif($premiu == 14){
		$db->Query("UPDATE `[players]` SET `bullets`=`bullets`+'100' WHERE `id`='".Player::Data('id')."'");
	}elseif($premiu == 15){
		$db->Query("UPDATE `[players]` SET `bullets`=`bullets`+'150' WHERE `id`='".Player::Data('id')."'");
	}elseif($premiu == 16){
		$db->Query("UPDATE `[players]` SET `bullets`=`bullets`+'200' WHERE `id`='".Player::Data('id')."'");
	}
	$db->Query("UPDATE `[players]` SET `roata_noroc`='".time()."' WHERE `id`='".Player::Data('id')."'");
}
?>