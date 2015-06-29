<?php

/**
 *
 * Phiên bản: 0.1
 * Tên lớp: NL_CheckOut
 * Chức năng: Tích hợp thanh toán qua nganluong.vn cho các merchant site có đăng ký API
 * - Xây dựng URL chuyển thông tin tới Nganluong.vn để xử lý việc thanh toán cho merchant site.
 * - Xác thực tính chính xác của thông tin đơn hàng được gửi về từ nganluong.vn.
 *
 **/

require_once NV_ROOTDIR . "/includes/class/nusoap.php";

class NL_Checkout
{

	// URL chheckout của nganluong.vn
	private $nganluong_url = '';

	private $merchant_site_code;

	private $secure_pass;

	function __construct( $nganluong_url, $merchant_site_code, $secure_pass )
	{
		$this->nganluong_url = trim( $nganluong_url );
		$this->merchant_site_code = trim( $merchant_site_code );
		$this->secure_pass = trim( $secure_pass );
	}

	//Hàm xây dựng url, trong đó có tham số mã hóa (còn gọi là public key)
	public function buildCheckoutUrl( $return_url, $receiver, $transaction_info, $order_code, $price )
	{
		// Mảng các tham số chuyển tới nganluong.vn
		$arr_param = array(
			'merchant_site_code' => strval( $this->merchant_site_code ),
			'return_url' => strtolower( urlencode( $return_url ) ),
			'receiver' => strval( $receiver ),
			'transaction_info' => strval( $transaction_info ),
			'order_code' => strval( $order_code ),
			'price' => strval( $price )
		);

		$secure_code = implode( ' ', $arr_param ) . ' ' . $this->secure_pass;
		$arr_param['secure_code'] = md5( $secure_code );
		/* Bước 2. Kiểm tra biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào*/
		$redirect_url = $this->nganluong_url;
		if( strpos( $redirect_url, '?' ) === false )
		{
			$redirect_url .= '?';
		}
		else if( substr( $redirect_url, strlen( $redirect_url ) - 1, 1 ) != '?' && strpos( $redirect_url, '&' ) === false )
		{
			// Nếu biến $redirect_url có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
			$redirect_url .= '&';
		}

		/* Bước 3. tạo url*/
		$url = '';
		foreach( $arr_param as $key => $value )
		{
			if( $url == '' ) $url .= $key . '=' . $value;
			else
				$url .= '&' . $key . '=' . $value;
		}

		return $redirect_url . $url;
	}

	/*Hàm thực hiện xác minh tính đúng đắn của các tham số trả về từ nganluong.vn*/

	public function verifyPaymentUrl( $transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code )
	{
		// Tạo mã xác thực từ chủ web
		$str = '';
		$str .= ' ' . strval( $transaction_info );
		$str .= ' ' . strval( $order_code );
		$str .= ' ' . strval( $price );
		$str .= ' ' . strval( $payment_id );
		$str .= ' ' . strval( $payment_type );
		$str .= ' ' . strval( $error_text );
		$str .= ' ' . strval( $this->merchant_site_code );
		$str .= ' ' . strval( $this->secure_pass );

		// Mã hóa các tham số
		$verify_secure_code = md5( $str );

		// Xác thực mã của chủ web với mã trả về từ nganluong.vn
		if( $verify_secure_code === $secure_code ) return true;

		return false;
	}

	public function checkOrder( $public_api_url, $order_code, $payment_id )
	{
		$payment_id = ( empty( $payment_id ) ) ? "" : $payment_id;
		$param = "<ORDERS>
 	<TOTAL>1</TOTAL>
 	<ORDER>
 		<ORDER_CODE>" . $order_code . "</ORDER_CODE>
 		<PAYMENT_ID>" . $payment_id . "</PAYMENT_ID>		
 	</ORDER>
 </ORDERS>";

		$client = new nusoap_client( $public_api_url, 'wsdl' );
		$result = $client->call( 'checkOrder', array(
			'merchant_id' => $this->merchant_site_code,
			'param' => $param,
			'checksum' => md5( $this->merchant_site_code . $param . $this->secure_pass )
		) );
		if( $xml = simplexml_load_string( $result ) )
		{
			$error_code = ( string )$xml->ERROR_CODE;
			if( $error_code == "00" )
			{
				$transaction = $xml->xpath( 'TRANSACTION' );
				$data = array_map( "trim", ( array )$transaction[0] );
				if( $data['TRANSACTION_ERROR_CODE'] == '00' )
				{
					$preg_match_time = '/(\d{2}):(\d{2}):(\d{2}) - (\d{2})\/(\d{2})\/(\d{4})/';

					unset( $matches );
					preg_match( $preg_match_time, $data['CREATED_TIME'], $matches );
					$data['CREATED_TIME'] = mktime( $matches[1], $matches[2], $matches[3], $matches[5], $matches[4], $matches[6] );

					unset( $matches );
					preg_match( $preg_match_time, $data['PAID_TIME'], $matches );
					$data['PAID_TIME'] = mktime( $matches[1], $matches[2], $matches[3], $matches[5], $matches[4], $matches[6] );

					/* CHUẨN HÓA CÁC TRẠNG THÁI GIAO DỊCH VỀ CÁC TRẠNG THÁI SAU
					 0 – Giao dịch mới tạo;
					 1 – Chưa thanh toán;
					 2 – Đã thanh toán, đang bị tạm giữ;
					 3 – Giao dịch bị huỷ;
					 4 – Giao dịch đã hoàn thành thành công (trường hợp thanh toán ngay hoặc thanh toán tạm giữ nhưng người mua đã phê chuẩn)
					 */
					$data['nv_transaction_status'] = intval( $data['TRANSACTION_STATUS'] );

					return $data;
				}

			}
		}
		return false;
	}

	public function checkOrders( $public_api_url, $array_order )
	{
		$data_orders_return = array();
		$param = "<ORDERS>
			<TOTAL>" . count( $array_order ) . "</TOTAL>";
		foreach( $array_order as $arr_order_i )
		{
			$payment_id = ( empty( $arr_order_i['payment_id'] ) ) ? "" : $arr_order_i['payment_id'];
			$param .= "<ORDER>
 		<ORDER_CODE>" . $arr_order_i['order_code'] . "</ORDER_CODE>
 		<PAYMENT_ID>" . $payment_id . "</PAYMENT_ID>		
 	</ORDER>";
		}
		$param .= "</ORDERS>";

		$client = new nusoap_client( $public_api_url, 'wsdl' );
		$result = $client->call( 'checkOrder', array(
			'merchant_id' => $this->merchant_site_code,
			'param' => $param,
			'checksum' => md5( $this->merchant_site_code . $param . $this->secure_pass )
		) );

		if( $xml = simplexml_load_string( $result ) )
		{
			$error_code = ( string )$xml->ERROR_CODE;
			if( $error_code == "00" )
			{
				$transactions = $xml->xpath( 'TRANSACTION' );
				foreach( $transactions as $transaction )
				{
					$data = array_map( "trim", ( array )$transaction );
					if( $data['TRANSACTION_ERROR_CODE'] == '00' )
					{
						$preg_match_time = '/(\d{2}):(\d{2}):(\d{2}) - (\d{2})\/(\d{2})\/(\d{4})/';

						unset( $matches );
						preg_match( $preg_match_time, $data['CREATED_TIME'], $matches );
						$data['CREATED_TIME'] = mktime( $matches[1], $matches[2], $matches[3], $matches[5], $matches[4], $matches[6] );

						unset( $matches );
						preg_match( $preg_match_time, $data['PAID_TIME'], $matches );
						$data['PAID_TIME'] = mktime( $matches[1], $matches[2], $matches[3], $matches[5], $matches[4], $matches[6] );

						/* CHUẨN HÓA CÁC TRẠNG THÁI GIAO DỊCH VỀ CÁC TRẠNG THÁI SAU
						 0 – Giao dịch mới tạo;
						 1 – Chưa thanh toán;
						 2 – Đã thanh toán, đang bị tạm giữ;
						 3 – Giao dịch bị huỷ;
						 4 – Giao dịch đã hoàn thành thành công (trường hợp thanh toán ngay hoặc thanh toán tạm giữ nhưng người mua đã phê chuẩn)
						 */
						$data['nv_transaction_status'] = intval( $data['TRANSACTION_STATUS'] );

						$data_orders_return[] = $data;
					}
				}
			}
		}
		return $data_orders_return;
	}

}