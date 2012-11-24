<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-6-2010 21:16
 */

if ( defined( 'NV_CLASS_CRYPT' ) ) return;
define( 'NV_CLASS_CRYPT', true );

/**
 * nv_Crypt
 * 
 * @package   
 * @author NUKEVIET 3.0
 * @copyright Nguyen Anh Tu
 * @version 2010
 * @access public
 */
class nv_Crypt
{

    public $_otk;

    private $_func;

    private $_ipad;

    private $_opad;

    /**
     * nv_Crypt::__construct()
     * 
     * @param mixed $key
     * @param mixed $method
     * @return
     */
    function __construct ( $key, $method )
    {
        $this->_otk = ! empty ( $key ) ? true : false;
        $this->_func = $method != 'md5' ? 'sha1' : 'md5';
        
        if ( isset( $key{64} ) ) $key = pack( 'H32', call_user_func( $this->_func, $key ) );
        
        if ( ! isset ($key{63}) ) $key = str_pad( $key, 64, chr( 0 ) );
        
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
    public function hash ( $data, $is_salt = false )
    {
        $inner = pack( 'H32', call_user_func( $this->_func, $this->_ipad . $data ) );
        $digest = call_user_func( $this->_func, $this->_opad . $inner );
        if ( ! $is_salt ) return $digest;
        
        $mhast = constant( 'MHASH_' . strtoupper( $this->_func ) );
        $salt = substr( str_shuffle( "abcdefghijklmnopqrstuvwxyz0123456789" ), 0, 8 );
        $salt = mhash_keygen_s2k( $mhast, $digest, substr( pack( 'h*', md5( $salt ) ), 0, 8 ), 4 );
        $hash = strtr( base64_encode( mhash( $mhast, $digest . $salt ) . $salt ), '+/=', '-_,' );
        return $hash;
    }

    /**
     * nv_Crypt::validate()
     * 
     * @param mixed $data
     * @param mixed $hash
     * @param bool $is_salt
     * @return
     */
    public function validate ( $data, $hash )
    {
        if ( strlen( $hash ) == 32 )
        {
            $new_hash = md5( $data );
        }
        else
        {
            $new_hash = $this->hash( $data );
        }
        if ( $new_hash == $hash )
        {
            return true;
        }
        return false;
    }
}

?>