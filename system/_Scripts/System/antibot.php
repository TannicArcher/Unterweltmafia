<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
?>
<script type="text/javascript">
	<!--
	var c_date = '<?=mysql_escape_string($langBase->get('antibot-10'))?>';
	var error = '<?=mysql_escape_string($langBase->get('antibot-11'))?>';
	var image = '<?=mysql_escape_string($langBase->get('antibot-12'))?>';
	var check = '<?=mysql_escape_string($langBase->get('antibot-13'))?>';
	var d_error = '<?=mysql_escape_string($langBase->get('antibot-14'))?>';
	-->
</script>

<script type="text/javascript" src="<?=$config['base_url']?>js/antibot/js.js"></script>
<script type="text/javascript">
	<!--
	window.addEvent('domready', function()
	{
		new AntibotForm($('antibot_form'), '<?=$db->EscapeString($_GET['side'])?>', '<?=$_SERVER['REQUEST_URI']?>');
	});
	-->
</script>
<div id="antibot_form"></div>