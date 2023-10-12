<?php
define('BASEPATH', true);
require("../../config.php");

define('DEBUG', 0);
define('LOG_FILE', './payeer.log');

if (!in_array($_SERVER['REMOTE_ADDR'], array('185.71.65.92', '185.71.65.189'))) {
	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Invalid query server! ".$_SERVER['REMOTE_ADDR']." Valid servers are 185.71.65.92 and 185.71.65.189" . PHP_EOL, 3, LOG_FILE);
     }
} else {
	$arHash = array($_POST['m_operation_id'],
			$_POST['m_operation_ps'],
			$_POST['m_operation_date'],
			$_POST['m_operation_pay_date'],
			$_POST['m_shop'],
			$_POST['m_orderid'],
			$_POST['m_amount'],
			$_POST['m_curr'],
			$_POST['m_desc'],
			$_POST['m_status'],
			$admin_config['payeer_secret']['value']);
	$sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));

	$payment_id			= $db->EscapeString($_POST['m_operation_id']);
	$payee_account 		= $db->EscapeString($_POST['m_shop']);
	$payment_amount 	= $db->EscapeString($_POST['m_amount']);
	$payment_units		= $db->EscapeString($_POST['m_curr']);
	$item_id					= $db->EscapeString($_POST['m_orderid']);
	$item_desc 				= $db->EscapeString($_POST['m_desc']);
	$txn_id					= $db->EscapeString($_POST['m_operation_id']);

	if($sign_hash == $_POST['m_sign'])
	{
		$get_data = explode('|', base64_decode($item_desc)); 
			
		$pack = $db->QueryFetchArray("SELECT * FROM `coins_packs` WHERE `id`='".$get_data[1]."' LIMIT 1");
		$user = $db->QueryFetchArray("SELECT id,userid FROM `[players]` WHERE `id`='".$get_data[0]."'");
		$aff_id = $db->QueryFetchArray("SELECT aff_id FROM `[users]` WHERE `id`='".$user['userid']."'");
		
		if ($payment_amount >= $pack['price']){
			if(!empty($user['id'])){
				$db->Query("UPDATE `[players]` SET `points`=`points`+'".$pack['coins']."' WHERE `id`='".$user['id']."'");
				$db->Query("INSERT INTO `payeer_points` (`UserId`, `Payer_id`, `Transaction`, `Option`, `Custom`, `Is_Collected`, `Timestamp`, `Num_Points`, `Revenue`) VALUES ('".$user['userid']."', '".$payer_id."', '".$txn_id."', '".$pack['name']."', '".$custom."', '0', '".time()."', '".$pack['coins']."', '".$payment_amount."')");
			
				if($config['affiliate_module'] && $aff_id['aff_id'] > 0){
					$affrev = (($admin_config['aff_paypalprc']['value']/100)*$payment_amount);
					$db->Query("UPDATE `[affiliates]` SET `balance`=`balance`+'".$affrev."', `t_balance`=`t_balance`+'".$affrev."' WHERE `id`='".$aff_id['aff_id']."'");
					$db->Query("INSERT INTO `aff_income` (`affiliate`, `referral`, `income`, `original`, `date`)VALUES('".$aff_id['aff_id']."', '".$user['userid']."', '".$affrev."', '".$payment_amount."', NOW())");
				}
			}
		}

		echo $_POST['m_orderid'].'|success';
	} else {
		if(DEBUG == true) {
			error_log(date('[Y-m-d H:i e] '). "Invalid Transaction hash! ".$txn_id." " . PHP_EOL, 3, LOG_FILE);
		}
	}
}
?>
