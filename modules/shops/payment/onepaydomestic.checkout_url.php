<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (! defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

$SECURE_SECRET = $payment_config['secure_secret'];
$vpcURL = $payment_config['virtualPaymentClientURL'] . "?";



$array_post = array();
//$array_post['Title'] = nv_EncString( $global_config['site_name'] ); // Site title
$array_post['Title'] = $global_config['site_name'] ; // Site title
$array_post['vpc_Merchant'] = $payment_config['vpc_Merchant']; // Merchant ID
$array_post['vpc_AccessCode'] = $payment_config['vpc_AccessCode']; // Merchant AccessCode
$array_post['vpc_Version'] = $payment_config['vpc_Version']; // Phien ban
$array_post['vpc_Command'] = $payment_config['vpc_Command']; // Pay
$array_post['vpc_Locale'] = $payment_config['vpc_Locale']; // Viet Nam
$array_post['vpc_Currency'] = 'VND'; // Viet Nam Dong

$array_post['vpc_MerchTxnRef'] = nv_genpass(20); // ID giao dich tu tang
$array_post['vpc_OrderInfo'] = $data['order_code']; // Ten hoa don
$order_chage = nv_currency_conversion($data['order_total'], $pro_config['money_unit'], "VND");
$array_post['vpc_Amount'] = 100 * intval($order_chage);// So tien can thanh toan

$array_post['vpc_ReturnURL'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=complete&payment=" . $payment; // URL tra ve
$array_post['vpc_TicketNo'] = $client_info['ip']; // IP nguoi mua
$array_post['vpc_Customer_Phone'] = $data['order_phone'] ; // Dien thoai nguoi mua
//$array_post['vpc_Customer_Phone'] = nv_EncString($data['order_phone'] ); // Dien thoai nguoi mua
$array_post['vpc_Customer_Email'] = $data['order_email']; // Email nguoi mua

$stringHashData = "";
ksort($array_post);

$appendAmp = 0;

foreach ($array_post as $key => $value) {
    if (strlen($value) > 0) {
        if ($appendAmp == 0) {
            $vpcURL .= urlencode($key) . '=' . urlencode($value);
            $appendAmp = 1;
        } else {
            $vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
        }

        if ((strlen($value) > 0) and ((substr($key, 0, 4) == "vpc_") or (substr($key, 0, 5) == "user_"))) {
            $stringHashData .= $key . "=" . $value . "&";
        }
    }
}

$stringHashData = rtrim($stringHashData, "&");

if (strlen($SECURE_SECRET) > 0) {
    $vpcURL .= "&vpc_SecureHash=" . strtoupper(hash_hmac('SHA256', $stringHashData, pack('H*', $SECURE_SECRET)));
}

$url = $vpcURL;
