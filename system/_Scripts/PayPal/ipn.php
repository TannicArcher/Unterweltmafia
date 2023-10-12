<?php
define('BASEPATH', true);
require("../../config.php");
require('PaypalIPN.php');

$ipn = new PaypalIPN();

// Use the sandbox endpoint during testing.
//$ipn->useSandbox();
$verified = $ipn->verifyIPN();
if ($verified) {
	$payment_status = $_POST['payment_status'];
	$payment_amount = $_POST['mc_gross'];
	$payment_currency = $_POST['mc_currency'];
	$txn_id = $_POST['txn_id'];
	$subscr_id = $_POST['subscr_id'];
	$receiver_email = $_POST['receiver_email'];
	$payer_email = $_POST['payer_email'];
	$custom = $_POST['custom'];
	$get_data = explode('|', $custom);

	if($receiver_email == $admin_config['paypal_email']['value'] && !empty($txn_id))
	{
		$pack = $db->QueryFetchArray("SELECT * FROM `coins_packs` WHERE `id`='".$get_data[1]."'");
		$user = $db->QueryFetchArray("SELECT id,userid FROM `[players]` WHERE `id`='".$get_data[0]."'");
		$aff_id = $db->QueryFetchArray("SELECT aff_id FROM `[users]` WHERE `id`='".$user['userid']."'");
		
		if ($payment_amount >= $pack['price']){
			if(!empty($user['id'])){
				$db->Query("UPDATE `[players]` SET `points`=`points`+'".$pack['coins']."' WHERE `id`='".$user['id']."'");
				$db->Query("INSERT INTO `paypal_points` (`UserId`, `Payer_id`, `Payer_email`, `Option`, `Custom`, `Is_Collected`, `Timestamp`, `Num_Points`, `Revenue`) VALUES ('".$user['userid']."', '".$payer_id."', '".$payer_email."', '".$pack['name']."', '".$custom."', '0', '".time()."', '".$pack['coins']."', '".$payment_amount."')");
			
				if(!empty($admin_config['affiliate_module']['value']) && $aff_id['aff_id'] > 0){
					$affrev = (($admin_config['aff_paypalprc']['value']/100)*$payment_amount);
					$db->Query("UPDATE `[affiliates]` SET `balance`=`balance`+'".$affrev."', `t_balance`=`t_balance`+'".$affrev."' WHERE `id`='".$aff_id['aff_id']."'");
					$db->Query("INSERT INTO `aff_income` (`affiliate`, `referral`, `income`, `original`, `date`)VALUES('".$aff_id['aff_id']."', '".$user['userid']."', '".$affrev."', '".$payment_amount."', NOW())");
				}
			}
		}
	}
}

// Reply with an empty 200 response to indicate to paypal the IPN was received correctly.
header("HTTP/1.1 200 OK");
?>