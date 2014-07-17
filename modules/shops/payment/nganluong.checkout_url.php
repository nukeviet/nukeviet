<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 29, 2010 10:42:00 PM
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

require_once NV_ROOTDIR . "/modules/" . $module_file . "/payment/nganluong.class.php";

$receiver = $payment_config['receiver_pay'];

$return_url = $global_config['site_url'] . "/?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=complete&payment=nganluong";
$price = $data['order_total'];
$price_array = nv_currency_conversion( $price, $pro_config['money_unit'], "VND" );
$price_vn = $price_array['sale'];

$order_code = $data['order_code'];
$transaction_info = $data['order_note'];

$nl = new NL_Checkout( $payment_config['checkout_url'], $payment_config['merchant_site'], $payment_config['secure_pass'] );
$url = $nl->buildCheckoutUrl( $return_url, $receiver, $transaction_info, $order_code, $price_vn );
$url .= "&key_refer=5c429fb7cc74299b5d1e53fb0906b8cb";