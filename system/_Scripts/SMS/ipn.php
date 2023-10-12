<?php
define('BASEPATH', true);
require('../../config.php');

// check that the request comes from Fortumo server
  if(!in_array($_SERVER['REMOTE_ADDR'],
      array('81.20.151.38', '81.20.148.122', '79.125.125.1', '209.20.83.207'))) {
      header("HTTP/1.0 403 Forbidden");
      die("Error: Unknown IP");
  }

  // check the signature
  $secret = $admin_config['fortumo_secret']['value']; // insert your secret between ''
  if(empty($secret) || !check_signature($_GET, $secret)) {
    header("HTTP/1.0 404 Not Found");
    die("Error: Invalid signature");
  }

  $sender = $_GET['sender'];//phone num.
  $amount = $_GET['amount'];//credit
  $cuid = $_GET['cuid'];//resource i.e. user
  $payment_id = $_GET['payment_id'];//unique id
  $operator = $_GET['operator'];
  $price = $_GET['price'];
  $revenue = $_GET['revenue'];
  $currency = $_GET['currency'];

  //hint: find or create payment by payment_id
  //additional parameters: operator, price, user_share, country
  
  if(preg_match("/OK/i", $_GET['status']) && !empty($cuid)) {
	$date = time();
	$db->Query("INSERT INTO `sms_points`(UserId,Operator,Phone,Message_ID,Date,Num_Points,Price,Revenue,Currency) values('".$cuid."','".$operator."','".$sender."','".$payment_id."','".$date."','".$amount."','".$price."','".$revenue."','".$currency."')");
	$db->Query("UPDATE `[players]` SET `points`=`points`+'".$amount."' WHERE `userid`='".$cuid."'");
	
	$usr = $db->QueryFetchArray("SELECT id,aff_id FROM `[users]` WHERE `id`='".$cuid."'");
	
	if($config['affiliate_module'] && $usr['aff_id'] > 0){
		$affrev = ($admin_config['aff_smsprc']['value']/100)*$revenue;
		$db->Query("UPDATE `[affiliates]` SET `balance`=`balance`+'".$affrev."', `t_balance`=`t_balance`+'".$affrev."' WHERE `id`='".$usr['aff_id']."'");
		$db->Query("INSERT INTO `aff_income` (`affiliate`, `referral`, `income`, `original`, `date`)VALUES('".$usr['aff_id']."', '".$usr['id']."', '".$affrev."', '".$revenue."', NOW())");
	}
  }

  echo "Thank you! Coins where successfully added!";

  function check_signature($params_array, $secret) {
    ksort($params_array);

    $str = '';
    foreach ($params_array as $k=>$v) {
      if($k != 'sig') {
        $str .= "$k=$v";
      }
    }
    $str .= $secret;
    $signature = md5($str);

    return ($params_array['sig'] == $signature);
  }

$db->Close();
?>