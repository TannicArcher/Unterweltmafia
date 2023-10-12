<?
define(BASEPATH, true);
require('../../../system/config.php');
$nrgames = (Player::Data('vip_days') > 0 ? 40 : 20);
if(Player::Data('ruleta') < $nrgames){
	if(isset($_GET['money']) && $_GET['money'] <= Player::Data('cash')){
		$bidMoney = $db->EscapeString($_GET['money']);
		if($bidMoney >= 1000 && $bidMoney <= 1000000){
			if(isset($_GET['number']) && $_GET['number'] >= 0 && $_GET['number'] <= 36){
				$bidNumber = $db->EscapeString($_GET['number']);
				$bedrag1 = 0;
				$bedrag2 = 0;
				$win = "false";
				$kleur = array("0","2","1","2","1","2","1","2","1","2","1","1","2","1","2","1","2","1","2","2","1","2","1","2","1","2","1","2","1","1","2","1","2","1","2","1","2");
				$getal = rand(0,36);
				if($getal == 20){$getal = 21;}
				if($bidNumber == $getal){
					$win = "true"; 
					$bedrag1 = ($bidMoney*10);
				}
				$eigenkleur = $kleur[$bidNumber];
				$gegooidekleur = $kleur[$getal];
				if($eigenkleur == $gegooidekleur && $gegooidekleur != 0){
					$win = "true"; 
					$bedrag2 = ($bidMoney*2);
				}
				if($eigenkleur == $gegooidekleur && $gegooidekleur == 0){
					$win = "true"; 
					$bedrag2 = ($bidMoney*10);
				}
				$bedrag = $bedrag1+$bedrag2;
				if($win == "true"){
					$db->Query("UPDATE `[players]` SET `ruleta`=`ruleta`+'1', `cash`=`cash`+'".($bedrag-$bidMoney)."' WHERE `id`='".Player::Data('id')."'");
					$winmsg = "You+won!+The+winning+number+was+".$getal."+and+you+win+$".$bedrag;
				}
				if($win == "false"){
					$db->Query("UPDATE `[players]` SET `ruleta`=`ruleta`+'1', `cash`=`cash`-'".$bidMoney."' WHERE `id`='".Player::Data('id')."'");
					$winmsg = "You+lose!+The+ball+didn't+hit+the+right+number+or+color!";
				}
				echo '&winnumber='.$getal.'in='.$win.'&errormsg=&winmsg='.$winmsg.'&choice='.$bidNumber.'&aapje=dood';
			}else{
				echo '&winnumber=&win=&errormsg=Chosen+number+must+be+between+0+and+36!&winmsg=&choice=&aapje=dood';
			}
		}else{
			echo '&winnumber=&win=&errormsg=The+bet+must+be+between+$1+000+and+$1+000+000&winmsg=&choice=&aapje=dood';
		}
	}else{
		echo "&winnumber=&win=&errormsg=You+don't+have+enough+money!&winmsg=&choice=&aapje=dood";
	}
}else{
	echo '&winnumber=&win=&errormsg=You+can+play+only+'.$nrgames.'+times+daily!&winmsg=&choice=&aapje=dood';
}
?>