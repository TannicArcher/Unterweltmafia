<div class="script_header">
	<img src="<?=$config['base_url']?>images/script_headers/roulette.jpg" alt="" />
<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
?>
<script type="text/javascript" src="<?=$config['base_url']?>js/swfobject.js"></script>
<script type="text/javascript">
	var flashvars = {
		xml_url: "flash/roulette/xml_roulette_<?=strtolower($langBase_lang)?>.php?a=start",
		done_url: "?side=ruleta"
	};
	var params = {wmode:"transparent"};
	var attributes = {};
	swfobject.embedSWF("flash/roulette/roulette_<?=strtolower($langBase_lang)?>.swf", "flashcontent", "570", "500", "9.0.0", "flash/expressInstall.swf", flashvars, params, attributes);
</script>
<div align="center"><div id="flashcontent"></div></div>
</div>