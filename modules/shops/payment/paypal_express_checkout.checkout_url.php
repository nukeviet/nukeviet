<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 29, 2010  10:42:00 PM
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

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

// Đường dẫn trả về
$returnUrl = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&order_id=' . $order_id . '&payment=' . $payment . '&checksum=' . md5( $order_id . $payment . $global_config['sitekey'] . session_id() ) . '&getexpresscheckoutdetails=1';
// Đường dẫn hủy thanh toán
$cancelUrl = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&order_id=' . $order_id . '&checkss=' . md5( $order_id . $global_config['sitekey'] . session_id() );

if( $nv_Request->isset_request( "getexpresscheckoutdetails", "get" ) )
{
	/*
	 * GetExpressCheckout API
	 */

	$token = nv_htmlspecialchars( $nv_Request->get_string( "token", "get", "" ) );

	$getExpressCheckoutDetailsRequest = new GetExpressCheckoutDetailsRequestType( $token );

	$getExpressCheckoutReq = new GetExpressCheckoutDetailsReq();
	$getExpressCheckoutReq->GetExpressCheckoutDetailsRequest = $getExpressCheckoutDetailsRequest;

	$paypalService = new PayPalAPIInterfaceServiceService( $config );

	try
	{
		$getECResponse = $paypalService->GetExpressCheckoutDetails($getExpressCheckoutReq);
	}
	catch( Exception $ex )
	{
		redict_link( $ex->getMessage(), $lang_module['cart_back'], $cancelUrl );
	}

	if( isset( $getECResponse ) )
	{
		if( $getECResponse->Ack == 'Success')
		{
			// Trích xuất thông tin
			$responseDetails = $getECResponse->GetExpressCheckoutDetailsResponseDetails;
			$payerInfo = $responseDetails->PayerInfo;

			$payer = $payerInfo->Payer;
			$payerID = $payerInfo->PayerID;
			$payer_name = $payerInfo->PayerName;
			$payer_fname = $payer_name->FirstName;
			$payer_lname = $payer_name->LastName;

			$address = $payerInfo->Address;
			$street1 = $address->Street1;
			$street2 = $address->Street2;
			$cityName = $address->CityName;
			$stateOrProvince = $address->StateOrProvince;
			$postalCode = $address->PostalCode;
			$countryCode = $address->CountryName;

			$PaymentDetails = $responseDetails->PaymentDetails[0]->OrderTotal;

			$PayerData = array(
				"token" => $token,
				"id" => $payerID,
				"payer" => $payer,
				"fname" => $payer_fname,
				"lname" => $payer_lname,
				"street1" => $street1,
				"street2" => $street2,
				"cityname" => $cityName,
				"stateorprovince" => $stateOrProvince,
				"postalcode" => $postalCode,
				"countrycode" => $countryCode,
				"amount" => $PaymentDetails->value,
				"currency" => $PaymentDetails->currencyID,
				"order_id" => $order_id,
			);

			$nv_Request->set_Session( $module_data . "_payerdata_paypal", serialize( $PayerData ) );

			$doExpressURL = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=complete&payment=" . $payment . "&paycomplete&token=" . $token . "&payerid=" . $payerID;

			header( "Location:" . $doExpressURL );
			exit();
		}
		else
		{
			redict_link( $getECResponse->Errors[0]->ShortMessage . "<br />" . $getECResponse->Errors[0]->LongMessage, $lang_module['cart_back'], $cancelUrl );
		}
	}

	redict_link( "Unknow Error!!!", $lang_module['cart_back'], $cancelUrl );
}

/*
 * SetExpressCheckout API
 */

$currencyCode = 'USD'; // Đơn vị tiền tệ
$shippingTotal = new BasicAmountType($currencyCode, 0); // Phí vận chuyển (0)
$handlingTotal = new BasicAmountType($currencyCode, 0); // Phí xử lý
$insuranceTotal = new BasicAmountType($currencyCode, 0); // Phí bảo hiểm

// Thông tin người nhận
$address = new AddressType();
$address->CityName = $data['order_address'];
$address->Name = $data['order_name'];
$address->Street1 = '';
$address->StateOrProvince = '';
$address->PostalCode = '';
$address->Country = '';
$address->Phone = $data['order_phone'];

// Thông tin chi tiết về các mặt hàng
$paymentDetails = new PaymentDetailsType();
$itemTotalValue = 0;
$taxTotalValue = 0;

$temppro = array();

$i = 0;
foreach( $listid as $proid )
{
	if( empty( $listprice[$i] ) ) $listprice[$i] = 0;
	if( empty( $listnum[$i] ) ) $listnum[$i] = 0;

	$temppro[$proid] = array( 'price' => $listprice[$i], 'num' => $listnum[$i] );

	$arrayid[] = $proid;
	$i++;
}

if( ! empty( $arrayid ) )
{
	$templistid = implode( ',', $arrayid );

	$sql = 'SELECT t1.id, t1.listcatid, t1.' . NV_LANG_DATA . '_title, t1.money_unit FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows AS t1 LEFT JOIN ' . $db_config['prefix'] . '_' . $module_data . '_units AS t2 ON t1.product_unit = t2.id WHERE t1.id IN (' . $templistid . ') AND t1.status =1';

	$result = $db->query( $sql );
	while( list( $id, $listcatid, $title, $money_unit ) = $result->fetch( 3 ) )
	{
		$itemAmount = nv_currency_conversion( $temppro[$id]['price'], $money_unit, 'USD' );
		$itemAmount = new BasicAmountType($currencyCode, $itemAmount );

		$itemTotalValue += $itemAmount->value;

		$itemDetails = new PaymentDetailsItemType();
		$itemDetails->Name = $title;
		$itemDetails->Amount = $itemAmount;
		$itemDetails->Quantity = $temppro[$id]['num'];
		$itemDetails->ItemCategory = "Digital";
		$itemDetails->Tax = new BasicAmountType($currencyCode, 0);

		$paymentDetails->PaymentDetailsItem[$i] = $itemDetails;
	}
}

// Giá trị tổng cộng của đơn hàng
$orderTotalValue = $shippingTotal->value + $handlingTotal->value + $insuranceTotal->value + $itemTotalValue + $taxTotalValue;

// Thông tin thanh toán chi tiết
$paymentDetails->ShipToAddress = $address;
$paymentDetails->ItemTotal = new BasicAmountType($currencyCode, $itemTotalValue);
$paymentDetails->TaxTotal = new BasicAmountType($currencyCode, $taxTotalValue);
$paymentDetails->OrderTotal = new BasicAmountType($currencyCode, $orderTotalValue);
$paymentDetails->PaymentAction = $payment_config['paymentaction'];
$paymentDetails->HandlingTotal = $handlingTotal;
$paymentDetails->InsuranceTotal = $insuranceTotal;
$paymentDetails->ShippingTotal = $shippingTotal;
//$paymentDetails->NotifyURL = $notifyURL; // Đường dẫn Instant Payment Notification (IPN) tạm thời chưa hỗ trợ

$setECReqDetails = new SetExpressCheckoutRequestDetailsType();
$setECReqDetails->PaymentDetails[0] = $paymentDetails;
$setECReqDetails->CancelURL = $cancelUrl;
$setECReqDetails->ReturnURL = $returnUrl;
$setECReqDetails->NoShipping = 1;
$setECReqDetails->AddressOverride = 0;
$setECReqDetails->ReqConfirmShipping = 0;

// Thỏa thuận thanh toán
$billingAgreementDetails = new BillingAgreementDetailsType( "None" );
$billingAgreementDetails->BillingAgreementDescription = "";
$setECReqDetails->BillingAgreementDetails = array( $billingAgreementDetails );

$setECReqType = new SetExpressCheckoutRequestType();
$setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;
$setECReq = new SetExpressCheckoutReq();
$setECReq->SetExpressCheckoutRequest = $setECReqType;

$paypalService = new PayPalAPIInterfaceServiceService( $config );

try
{
	$setECResponse = $paypalService->SetExpressCheckout( $setECReq );
}
catch( Exception $ex )
{
	redict_link( $ex->getMessage(), $lang_module['cart_back'], $cancelUrl );
}

if( isset( $setECResponse ) )
{
	if( $setECResponse->Ack == 'Success')
	{
		$token = $setECResponse->Token;

		$payPalURL = "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=" . $token;
		if( "sandbox" === $payment_config['environment'] || "beta-sandbox" === $payment_config['environment'] )
		{
			$payPalURL = "https://www." . $payment_config['environment'] . ".paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=" . $token;
		}
		header( "Location: " . $payPalURL );
		exit;
	}
	else
	{
		redict_link( $setECResponse->Errors[0]->ShortMessage . "<br />" . $setECResponse->Errors[0]->LongMessage, $lang_module['cart_back'], $cancelUrl );
	}
}

redict_link( "Unknow Error!!!", $lang_module['cart_back'], $cancelUrl );

?>