<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	$wtt = (Player::Data('vip_days') > 0 ? 2700 : 3600);
	if (Player::Data('roata_noroc') + $wtt > time()){
		echo View::Message($langBase->get('txt-52', array('-TIME-' => ((Player::Data('roata_noroc') + $wtt) - time()))), 2);
	}else{
?>
<script type="text/javascript" src="<?=$config['base_url']?>js/swfobject.js"></script>
<script type="text/javascript">
	var flashvars = {
		xml_url: "flash/wheel/xml_fortune_<?=($langBase_lang == 'EN' ? 'EN' : 'RO')?>.php?a=start",
		done_url: "?side=roata-norocului"
	};
	var params = {wmode:"transparent"};
	var attributes = {};
	swfobject.embedSWF("flash/wheel/wheel.of.fortune.<?=($langBase_lang == 'EN' ? 'EN' : 'RO')?>.swf", "flashcontent", "570", "450", "9.0.0", "flash/expressInstall.swf", flashvars, params, attributes);
</script>
<div align="center"><div id="flashcontent"></div></div><?php }?>