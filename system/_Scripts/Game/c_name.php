<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

if(Player::Data('c_name_r') < 1){
	echo View::Message($langBase->get('cname-03'), 2, true, '/game/?side=magazin-credite');
}else{
	
if(isset($_POST['submit']) ){
if($_POST['name'] != ''){
$name = trim($db->EscapeString($_POST['name']));
$name_validated = Accessories::ValidatePlayername($name);

	if($name_validated == false){
		View::Message($langBase->get('home-05'), 2, true);
	}else if($name == Player::Data('name')){
		View::Message($langBase->get('cname-05'), 2, true);
	}else if($db->GetNumRows($db->Query("SELECT id FROM `[players]` WHERE `name`='".$name."'")) > 0){
		View::Message($langBase->get('home-06'), 2, true);
	}else if(Player::Data('c_name_r') < 1){
		View::Message($langBase->get('cname-03'), 2, true);
	}else{
		$db->Query("INSERT INTO `name_changes` (`userid`, `playerid`, `old_name`, `new_name`, `date`) VALUES ('".User::Data('id')."', '".Player::Data('id')."', '".Player::Data('name')."', '".$name."', '".time()."')");
		$db->Query("UPDATE `[players]` SET `name`='".$name."', `c_name_r`=`c_name_r`-'1' WHERE `id`='".Player::Data('id')."'");
		View::Message($langBase->get('cname-04'), 1, true, '/game/?side=startside');
	}
}else{
	View::Message($langBase->get('cname-01'), 2, true);
}
}
?>
<div class="bg_c w500">
<div style="text-align: center; width: 100%; margin-top: 15px">
<p><?=$langBase->get('cname-01')?></p>
<form method="post">
<?=$langBase->get('txt-02')?>: <input type="text" class="flat" value="<?=$_POST['name']?>" name="name" />
<input type="submit" name="submit" value="<?=$langBase->get('txt-14')?>">
</form>
</div>
</div>
<?}?>