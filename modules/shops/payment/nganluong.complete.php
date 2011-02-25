<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 29, 2010  10:42:00 PM 
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

require_once ( NV_ROOTDIR . "/modules/" . $module_file . "/payment/nganluong.class.php" );

$receiver = $payment_config['receiver_pay'];

$transaction_info = $nv_Request->get_string( 'transaction_info', 'get', '' );
$order_code = $nv_Request->get_string( 'order_code', 'get', "" );
$price = $nv_Request->get_int( 'price', 'get', "" );
$payment_id = $nv_Request->get_string( 'payment_id', 'get', "", 1 );
$payment_type = $nv_Request->get_string( 'payment_type', 'get', "", 1 );
$error_text = $nv_Request->get_string( 'error_text', 'get', "", 1 );
$secure_code = $nv_Request->get_string( 'secure_code', 'get', "", 1 );
/////////////////////////////////////////////////////////////////////////////////


$nl = new NL_Checkout( $payment_config['checkout_url'], $payment_config['merchant_site'], $payment_config['secure_pass'] );
$check = $nl->verifyPaymentUrl( $transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code );
if ( $check )
{
    $transaction_i = $nl->checkOrder( $payment_config['public_api_url'], $order_code, $payment_id );
    if ( $transaction_i !== false )
    {
        list( $order_id ) = $db->sql_fetchrow( $db->sql_query( "SELECT `order_id` FROM `" . $db_config['prefix'] . "_" . $module_data . "_orders` WHERE `order_code`=" . $db->dbescape_string( $order_code ) ) );
        if ( $order_id > 0 )
        {
            $error_update = false;
            $payment_data = nv_base64_encode( serialize( $transaction_i ) );
            list( $payment_data_old ) = $db->sql_fetchrow( $db->sql_query( "SELECT `payment_data` FROM `" . $db_config['prefix'] . "_" . $module_data . "_transaction` WHERE `payment`='" . $payment . "' AND `payment_id`=" . $db->dbescape_string( $payment_id ) . " ORDER BY `transaction_id` DESC LIMIT 1" ) );
            if ( $payment_data != $payment_data_old )
            {
                $nv_transaction_status = intval( $transaction_i['nv_transaction_status'] );
                $payment_amount = intval( $transaction_i['AMOUNT'] );
                $payment_time = max( $transaction_i['CREATED_TIME'], $transaction_i['PAID_TIME'] );
                
                $transaction_id = $db->sql_query_insert_id( "INSERT INTO `" . $db_config['prefix'] . "_" . $module_data . "_transaction` (`transaction_id`, `transaction_time`, `transaction_status`, `order_id`, `userid`, `payment`, `payment_id`, `payment_time`, `payment_amount`, `payment_data`) VALUES (NULL, UNIX_TIMESTAMP(), '" . $nv_transaction_status . "', '" . $order_id . "', '0', '" . $payment . "', '" . $payment_id . "', '" . $payment_time . "', '" . $payment_amount . "', '" . $payment_data . "')" );
                if ( $transaction_id > 0 )
                {
                    $db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_orders` SET transaction_status=" . $nv_transaction_status . " , transaction_id = " . $transaction_id . " , transaction_count = transaction_count+1 WHERE `order_id`=" . $order_id );
                }
                else
                {
                    $error_update = true;
                }
            }
            if ( ! $error_update )
            {
                $nv_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=history";
                $contents = redict_link( $lang_module['payment_complete'], $lang_module['back_history'], $nv_redirect );
            }
        }
    }
}

if ( $error_text != "" )
{
    $contents = $error_text;
}
else
{
    $contents = $lang_module['payment_erorr'];
}

?>