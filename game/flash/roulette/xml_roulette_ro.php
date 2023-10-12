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
					$winmsg = "Du+hast+gewonnen!+Die+Gewinnzahl+war+".$getal."+und+du+gewinnst+".$bedrag."$";
				}
				if($win == "false"){
					$db->Query("UPDATE `[players]` SET `ruleta`=`ruleta`+'1', `cash`=`cash`-'".$bidMoney."' WHERE `id`='".Player::Data('id')."'");
					$winmsg = "Leider+verloren!+Die+Kugel+blieb+nicht+an+der+richtigen+Zahl+oder+Farbe+liegen!";
				}
				echo '&winnumber='.$getal.'in='.$win.'&errormsg=&winmsg='.$winmsg.'&choice='.$bidNumber.'&aapje=dood';
			}else{
				echo '&winnumber=&win=&errormsg=Die+Zahl+muss+eine+zwischen+0+und+36+sein!&winmsg=&choice=&aapje=dood';
			}
		}else{
			echo '&winnumber=&win=&errormsg=Der+Einsatz+muss+zwischen+1+000$+und+1+000+000$+liegen!&winmsg=&choice=&aapje=dood';
		}
	}else{
		echo "&winnumber=&win=&errormsg=Du+hast+nicht+genug+Geld!&winmsg=&choice=&aapje=dood";
	}
}else{
	echo '&winnumber=&win=&errormsg=Du+kannst+maximal+'.$nrgames.'+am+Tag+spielen!&winmsg=&choice=&aapje=dood';
}
?>