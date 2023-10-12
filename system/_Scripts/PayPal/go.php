<?
define('BASEPATH', true);
require_once('../../config.php');
if(!$is_online){
	include('../../errors/error_forbidden.html');
	exit;
}

$s_host = parse_url($config['game_url']);

if($_GET['id'] != ''){
	$id = $db->EscapeString($_GET['id']);
	$sql = $db->Query("SELECT * FROM `coins_packs` WHERE `id`='".$id."'");
	$pack = $db->FetchArray($sql);
	if($pack['id'] == ''){
		include('../../errors/error_forbidden.html');
		exit;
	}
}else{
	include('../../errors/error_forbidden.html');
	exit;
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Redirecting...</title>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<style>body{background: #fff;font: 13px Trebuchet MS, Arial, Helvetica, Sans-Serif;color: #333;line-height: 160%;margin: 0;padding: 0;text-align: center;}h1{font-size: 200%;font-weight: normal}.centerdiv{position: absolute;top: 50%; left: 50%; width: 340px; height: 200px;margin-top: -100px; margin-left: -160px;}</style>
<script type="text/javascript">
	setTimeout('document.paypalform.submit()',1000);
</script>
</head>
<body>
<div class="centerdiv"><h1>Connecting to Paypal <img src="<?=$config['game_url']?>/system/_Scripts/PayPal/go_loader.gif" /></h1></div>
<form name="paypalform" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?=$admin_config['paypal_email']['value']?>">
<input type="hidden" name="item_name" value="<?=$pack['name'].' - '.$s_host['host']?>">
<input type="hidden" name="item_number" value="<?=$pack['coins']?>">
<input type="hidden" name="custom" value="<?=(Player::Data('id').'|'.$pack['id'].'|'.$pack['coins'])?>">
<input type="hidden" name="amount" value="<?=$pack['price']?>">
<input type="hidden" name="currency_code" value="EUR">
<input type="hidden" name="button_subtype" value="services">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="no_shipping" value="2">
<input type="hidden" name="rm" value="1">
<input type="hidden" name="return" value="<?=$config['game_url']?>/game/?side=magazin-credite&amp;pp_success">
<input type="hidden" name="cancel_return" value="<?=$config['game_url']?>/game/?side=magazin-credite&amp;pp_cancel">
<input type="hidden" name="notify_url" value="<?=$config['game_url']?>/system/_Scripts/PayPal/ipn.php">
</form>
</body>
</html>