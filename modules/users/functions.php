<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 10/03/2010 10:51
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_USER', true );

$lang_module['in_groups'] = $lang_global['in_groups'];

/**
 * validUserLog()
 * 
 * @param mixed $array_user
 * @param mixed $remember
 * @param mixed $opid
 * @return
 */
function validUserLog( $array_user, $remember, $opid )
{
    global $db, $client_info, $crypt, $nv_Request;

    $remember = intval( $remember );
    $checknum = nv_genpass( 10 );
    $checknum = $crypt->hash( $checknum );
    $user = array( //
        'userid' => $array_user['userid'], //
        'checknum' => $checknum, //
        'current_agent' => $client_info['agent'], //
        'last_agent' => $array_user['last_agent'], //
        'current_ip' => $client_info['ip'], //
        'last_ip' => $array_user['last_ip'], //
        'current_login' => NV_CURRENTTIME, //
        'last_login' => intval( $array_user['last_login'] ), //
        'last_openid' => $array_user['last_openid'], //
        'current_openid' => $opid
	);

    $user = nv_base64_encode( serialize( $user ) );

    $db->sql_query( "UPDATE `" . NV_USERS_GLOBALTABLE . "` SET 
    `checknum` = " . $db->dbescape( $checknum ) . ", 
    `last_login` = " . NV_CURRENTTIME . ", 
    `last_ip` = " . $db->dbescape( $client_info['ip'] ) . ", 
    `last_agent` = " . $db->dbescape( $client_info['agent'] ) . ", 
    `last_openid` = " . $db->dbescape( $opid ) . ", 
    `remember` = " . $remember . " 
    WHERE `userid`=" . $array_user['userid'] );

    $live_cookie_time = ( $remember ) ? NV_LIVE_COOKIE_TIME : 0;

    $nv_Request->set_Cookie( 'nvloginhash', $user, $live_cookie_time );
}

?>