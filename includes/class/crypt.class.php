<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-6-2010 21:16
 */

class nv_Crypt
{
	private $_ipad;
	private $_opad;
	private $_key;

	/**
	 * nv_Crypt::__construct()
	 *
	 * @param mixed $key
	 * @return
	 */
	function __construct( $key )
	{
		$this->_key = sha1( $key );
		if( isset( $key{64} ) ) $key = pack( 'H32', $this->_key );

		if( ! isset( $key{63} ) ) $key = str_pad( $key, 64, chr( 0 ) );

		$this->_ipad = substr( $key, 0, 64 ) ^ str_repeat( chr( 0x36 ), 64 );
		$this->_opad = substr( $key, 0, 64 ) ^ str_repeat( chr( 0x5C ), 64 );
	}

	/**
	 * nv_Crypt::hash()
	 *
	 * @param mixed $data
	 * @param bool $is_salt
	 * @return
	 */
	public function hash( $data, $is_salt = false )
	{
		$inner = pack( 'H32', sha1( $this->_ipad . $data ) );
		$digest = sha1( $this->_opad . $inner );
		if( ! $is_salt ) return $digest;

		$mhast = constant( 'MHASH_SHA1' );
		$salt = substr( str_shuffle( 'abcdefghijklmnopqrstuvwxyz0123456789' ), 0, 8 );
		$salt = mhash_keygen_s2k( $mhast, $digest, substr( pack( 'h*', md5( $salt ) ), 0, 8 ), 4 );
		$hash = strtr( base64_encode( mhash( $mhast, $digest . $salt ) . $salt ), '+/=', '-_,' );
		return $hash;
	}

	/**
	 * nv_Crypt::hash_password()
	 *
	 * @param mixed $password
	 * @param mixed $hashprefix
	 * @return
	 */
	public function hash_password( $password, $hashprefix = '{SSHA}' )
	{
		if( $hashprefix == '{SSHA}' )
		{
			$salt = substr( str_shuffle( str_repeat( 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', 4 ) ), 0, 4 );
			return '{SSHA}' . base64_encode( sha1( $password . $salt, TRUE ) . $salt );
		}
		elseif( $hashprefix == '{SHA}' )
		{
			return '{SHA}' . base64_encode( sha1( $password, TRUE ) );
		}
		elseif( $hashprefix == '{MD5}' )
		{
			return '{MD5}' . base64_encode( md5( $password, TRUE ) );
		}
		else
		{
			return $this->hash( $password );
		}
	}
	/**
	 * nv_Crypt::validate_password()
	 *
	 * @param mixed $password
	 * @param mixed $hash
	 * @return
	 */
	public function validate_password( $password, $hash )
	{
		if( substr( $hash, 0, 6 ) == '{SSHA}' )
		{
			$salt = substr( base64_decode( substr( $hash, 6 ) ), 20 );
			$validate_hash = '{SSHA}' . base64_encode( sha1( $password . $salt, true ) . $salt );
		}
		elseif( substr( $hash, 0, 5 ) == '{SHA}' )
		{
			$validate_hash = '{SHA}' . base64_encode( sha1( $password, true ) );
		}
		elseif( substr( $hash, 0, 5 ) == '{MD5}' )
		{
			$validate_hash = '{MD5}' . base64_encode( md5( $password, true ) );
		}
		else
		{
			$validate_hash = $this->hash( $password );
		}
		if( $hash == $validate_hash ) return true;
		return false;
	}

	/**
	 * nv_Crypt::aes_encrypt()
	 *
	 * @param mixed $val
	 * @param mixed $ky
	 * @return
	 */
	public function aes_encrypt( $val, $ky = '' )
	{
		if( empty( $ky ) )
		{
			$ky = $this->_key;
		}
		$key = "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
		for( $a = 0; $a < strlen( $ky ); $a++ )
			$key[$a % 16] = chr( ord( $key[$a % 16] ) ^ ord( $ky[$a] ) );
		$mode = MCRYPT_MODE_ECB;
		$enc = MCRYPT_RIJNDAEL_128;
		$val = str_pad( $val, ( 16 * ( floor( strlen( $val ) / 16 ) + ( strlen( $val ) % 16 == 0 ? 2 : 1 ) ) ), chr( 16 - ( strlen( $val ) % 16 ) ) );
		return mcrypt_encrypt( $enc, $key, $val, $mode, mcrypt_create_iv( mcrypt_get_iv_size( $enc, $mode ), MCRYPT_DEV_URANDOM ) );
	}

	/**
	 * nv_Crypt::aes_decrypt()
	 *
	 * @param mixed $val
	 * @param mixed $ky
	 * @return
	 */
	public function aes_decrypt( $val, $ky = '' )
	{
		if( empty( $ky ) )
		{
			$ky = $this->_key;
		}
		$key = "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
		for( $a = 0; $a < strlen( $ky ); $a++ )
			$key[$a % 16] = chr( ord( $key[$a % 16] ) ^ ord( $ky[$a] ) );
		$mode = MCRYPT_MODE_ECB;
		$enc = MCRYPT_RIJNDAEL_128;
		$dec = mcrypt_decrypt( $enc, $key, $val, $mode, @mcrypt_create_iv( @mcrypt_get_iv_size( $enc, $mode ), MCRYPT_DEV_URANDOM ) );

		$slast = ord( substr( $dec, -1 ) );
		$slastc = chr( $slast );
		$pcheck = substr( $dec, -$slast );
		$dec_len = strlen( $dec );
		if( $dec_len )
		{
			return rtrim( $dec, ( ( ord( substr( $dec, $dec_len - 1, 1 ) ) >= 0 and ord( substr( $dec, $dec_len - 1, 1 ) ) <= 16 ) ? chr( ord( substr( $dec, $dec_len - 1, 1 ) ) ) : null ) );
		}
		else
		{
			return null;
		}
	}
}