<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

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
	global $db, $db_config, $crypt, $nv_Request;

	$remember = intval( $remember );
	$checknum = nv_genpass( 10 );
	$checknum = $crypt->hash( $checknum );
	$user = array(
		'userid' => $array_user['userid'],
		'checknum' => $checknum,
		'current_agent' => NV_USER_AGENT,
		'last_agent' => $array_user['last_agent'],
		'current_ip' => NV_CLIENT_IP,
		'last_ip' => $array_user['last_ip'],
		'current_login' => NV_CURRENTTIME,
		'last_login' => intval( $array_user['last_login'] ),
		'last_openid' => $array_user['last_openid'],
		'current_openid' => $opid
	);

	$user = nv_base64_encode( serialize( $user ) );

	$stmt = $db->prepare( "UPDATE " . NV_USERS_GLOBALTABLE . " SET
		checknum = :checknum,
		last_login = " . NV_CURRENTTIME . ",
		last_ip = :last_ip,
		last_agent = :last_agent,
		last_openid = :opid,
		remember = " . $remember . "
		WHERE userid=" . $array_user['userid'] );

	$stmt->bindValue( ':checknum', $checknum, PDO::PARAM_STR );
	$stmt->bindValue( ':last_ip', NV_CLIENT_IP, PDO::PARAM_STR );
	$stmt->bindValue( ':last_agent', NV_USER_AGENT, PDO::PARAM_STR );
	$stmt->bindValue( ':opid', $opid, PDO::PARAM_STR );
	$stmt->execute();
	$live_cookie_time = ( $remember ) ? NV_LIVE_COOKIE_TIME : 0;

	$nv_Request->set_Cookie( 'nvloginhash', $user, $live_cookie_time );
}


//global config
$sql = "SELECT content FROM " . NV_USERS_GLOBALTABLE . "_config WHERE config='avatar_width'";
$result = $db->query( $sql );
$global_config['avatar_width'] = $result->fetchColumn();
$result->closeCursor();

$sql = "SELECT content FROM " . NV_USERS_GLOBALTABLE . "_config WHERE config='avatar_height'";
$result = $db->query( $sql );
$global_config['avatar_height'] = $result->fetchColumn();
$result->closeCursor();