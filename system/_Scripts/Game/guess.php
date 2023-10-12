<div class="script_header">
	<img src="<?=$config['base_url']?>images/script_headers/zahlenspiel.jpg" alt="" />
<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
?>
<script type="text/javascript" src="<?=$config['base_url']?>js/swfobject.js"></script>
<script type="text/javascript">
	var flashvars = {
		xml_url: "flash/guess/xml_guess_<?=strtolower($langBase_lang)?>.php?a=start",
		done_url: "?side=guess"
	};
	var params = {wmode:"transparent"};
	var attributes = {};
	swfobject.embedSWF("flash/guess/guess_<?=strtolower($langBase_lang)?>.swf", "flashcontent", "550", "400", "9.0.0", "flash/expressInstall.swf", flashvars, params, attributes);
</script>
<div align="center"><div id="flashcontent"></div></div>
</div>