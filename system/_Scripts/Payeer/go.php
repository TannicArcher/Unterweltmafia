<?php
	define('BASEPATH', true);
	require_once('../../config.php');
	if(!$is_online){
		include('../../errors/error_forbidden.html');
		exit;
	}

	if(isset($_GET['id']) && is_numeric($_GET['id'])){
		$id = $db->EscapeString($_GET['id']);
		$pack = $db->QueryFetchArray("SELECT * FROM `coins_packs` WHERE `id`='".$id."'");
		if(empty($pack['id'])) {
			include('../../errors/error_forbidden.html');
			exit;
		}
		
		
		$s_orderid = rand(1000,99999);
		$s_amount = number_format($pack['price'], 2, '.', '');
		$s_desc = base64_encode(base64_encode(Player::Data('id').'|'.$pack['id'].'|'.$pack['coins']));

		$arHash = array(
			$admin_config['payeer_key']['value'],
			$s_orderid,
			$s_amount,
			'USD',
			$s_desc,
			$admin_config['payeer_secret']['value']
		);
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
	setTimeout('document.payeerform.submit()',1000);
</script>
</head>
<body>
<div class="centerdiv"><h1>Connecting to Payeer <img src="<?=$config['game_url']?>/system/_Scripts/PayPal/go_loader.gif" /></h1></div>
 <form name="payeerform" method="GET" action="https://payeer.com/merchant/">
  <input type="hidden" name="m_shop" value="<?=$admin_config['payeer_key']['value']?>">
  <input type="hidden" name="m_orderid" value="<?=$s_orderid?>">
  <input type="hidden" name="m_amount" value="<?=$s_amount?>">
  <input type="hidden" name="m_curr" value="USD">
  <input type="hidden" name="m_desc" value="<?=$s_desc?>">
  <input type="hidden" name="m_sign" value="<?=strtoupper(hash('sha256', implode(':', $arHash)))?>">
  <input type="hidden" name="m_process" value="send" />
 </form>
</body>
</html>