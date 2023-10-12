<?
define(BASEPATH, true);
require('../../../system/config.php');
$premiu = rand(1,16);
$rank = rand(40,100);
$w_rank = View::AsPercent($rank, $config['ranks'][Player::Data('rank')][2]-$config['ranks'][Player::Data('rank')][1], 2);
$text = array("","Bad luck! Unfortunately you didn't won anything!","Congratulations! You won 5 coins.","Congratulations! You won 10 coins.","Congratulations! You won ".$w_rank." rank progress.","Congratulations! You won completely health refill!","Congratulations! You won 1 000$","Congratulations! You won 5 000$","Congratulations! You won 10 000$","Bad luck! Unfortunately you didn't won anything!","Congratulations! You won 25 000$","Congratulations! You won 50 000$","Congratulations! You won 100 000$","Congratulations! You won 50 bullets","Congratulations! You won 100 bullets","Congratulations! You won 150 bullets","Congratulations! You won 200 bullets");
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