<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 29, 2010  10:42:00 PM
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

// Gọi thư viện PayPal SDK
require_once( NV_ROOTDIR . '/includes/class/PayPal/PPBootStrap.php');

// Thông tin cấu hình gian hàng
foreach( $payment_config as $ckey => $cval )
{
	$payment_config[$ckey] = nv_unhtmlspecialchars( $cval );
}
unset( $ckey, $cval );

$config = array(
	"mode" => $payment_config['environment'],
	"acct1.UserName" => $payment_config['apiusername'],
	"acct1.Password" => $payment_config['apipassword'],
	"acct1.Signature" => $payment_config['signature'],
);

/* 
 * DoExpressCheckoutPayment API
 */

foreach( $array_order as $order_code => $order_data )
{
	$payment_data = unserialize( nv_base64_decode( $order_data['payment_data'] ) );
	
	if( ! empty( $payment_data ) )
	{
		unset( $getExpressCheckoutDetailsRequest, $getExpressCheckoutReq, $paypalService, $DoECResponse );
	
		// Lấy thông tin
		$payerID = $payment_data['id'];
		$token = $payment_data['token'];
		$paymentAction = $payment_config['paymentaction'];

		$getExpressCheckoutDetailsRequest = new GetExpressCheckoutDetailsRequestType( $token );
		$getExpressCheckoutReq = new GetExpressCheckoutDetailsReq();
		$getExpressCheckoutReq->GetExpressCheckoutDetailsRequest = $getExpressCheckoutDetailsRequest;

		$paypalService = new PayPalAPIInterfaceServiceService( $config );
		try
		{
			$getECResponse = $paypalService->GetExpressCheckoutDetails( $getExpressCheckoutReq );
		}
		catch( Exception $ex )
		{
			// Không cần làm gì cả
		}

		$orderTotal = new BasicAmountType();
		$orderTotal->currencyID = $payment_data['currency'];
		$orderTotal->value = $payment_data['amount'];

		$paymentDetails= new PaymentDetailsType();
		$paymentDetails->OrderTotal = $orderTotal;
		//$paymentDetails->NotifyURL = "";

		$DoECRequestDetails = new DoExpressCheckoutPaymentRequestDetailsType();
		$DoECRequestDetails->PayerID = $payerID;
		$DoECRequestDetails->Token = $token;
		$DoECRequestDetails->PaymentAction = $paymentAction;
		$DoECRequestDetails->PaymentDetails[0] = $paymentDetails;

		$DoECRequest = new DoExpressCheckoutPaymentRequestType();
		$DoECRequest->DoExpressCheckoutPaymentRequestDetails = $DoECRequestDetails;

		$DoECReq = new DoExpressCheckoutPaymentReq();
		$DoECReq->DoExpressCheckoutPaymentRequest = $DoECRequest;

		try
		{
			$DoECResponse = $paypalService->DoExpressCheckoutPayment($DoECReq);
		}
		catch( Exception $ex )
		{
			// Không làm gì cả
		}

		if( isset( $DoECResponse ) )
		{
			if( $DoECResponse->Ack == 'Success' or $DoECResponse->Ack == 'SuccessWithWarning' )
			{
				// Lấy thông tin chi tiết
				$details = $DoECResponse->DoExpressCheckoutPaymentResponseDetails;
				
				$payment_info = $details->PaymentInfo[0];
				$tran_ID = $payment_info->TransactionID;
				
				$amt_obj = $payment_info->GrossAmount;
				$amt = $amt_obj->value;
				$currency_cd = $amt_obj->currencyID;
				
				$PaymentStatus = $payment_info->PaymentStatus;
				$PaymentDate = $payment_info->PaymentDate;
				$PaymentDate = strtotime( $PaymentDate );
				if( $PaymentDate < 0 ) $PaymentDate = 0;
				
				/*
					Thông số mặc định của PayPal
					Completed - Thanh toán hoàn thành
					Pending - Thanh toán đang chờ
					Failed - Thanh toán không thành công
					Denied - Bị từ chối thanh toán 
					Refunded - Được hoàn tiền thanh toán
					Canceled_Reversal - Thanh toán ngược bị hủy
					Reversed - Thanh toán ngược lại (hoàn trả)
					Expired - Thanh toán bị hết hạn
					Processed - Đang thực hiện thanh toán
					Voided - Bị hủy bỏ vì không được xác thực
					Created - Đang khởi tạo
				 */
				 
				$Status = 0;
				switch( $PaymentStatus )
				{
					case 'Canceled_Reversal': $Status = 5; break;
					case 'Completed': $Status = 4; break;
					case 'Denied': $Status = 6; break;
					case 'Expired': $Status = 7; break;
					case 'Failed': $Status = 8; break;
					case 'Pending': $Status = 2; break;
					case 'Processed': $Status = 9; break;
					case 'Refunded': $Status = 10; break;
					case 'Reversed': $Status = 11; break;
					case 'Voided': $Status = 3; break;
					case 'Created': $Status = 0; break;
					default: $Status = -1;
				}
				
				$payment_data['transaction_status'] = $Status;
				$payment_data['transaction_time'] = $PaymentDate;
				$payment_data['transaction_id'] = $tran_ID;
				$payment_data = nv_base64_encode( serialize( $payment_data ) );
				
				if( $payment_data != $order_data['payment_data'] )
				{
					$order_id = $array_order[$order_code]['order_id'];

					$transaction_id = $db->insert_id( "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_transaction (transaction_id, transaction_time, transaction_status, order_id, userid, payment, payment_id, payment_time, payment_amount, payment_data) VALUES (NULL, " . NV_CURRENTTIME . ", '" . $Status . "', '" . $order_id . "', '0', '" . $payment . "', '" . $tran_ID . "', '" . $PaymentDate . "', '" . intval( $amt ) . "', '" . $payment_data . "')" );
					
					if( $transaction_id > 0 )
					{
						$db->query( "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_orders SET transaction_status=" . $Status . " , transaction_id = " . $transaction_id . " , transaction_count = transaction_count+1 WHERE order_id=" . $order_id );
						$array_update_order[] = $order_code;
					}
				}
			}
		}
	}
}

?>