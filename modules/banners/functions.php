<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

$lang_array = array(
	'vi' => $lang_module['addads_block_lang_vi'],
	'en' => $lang_module['addads_block_lang_en'],
	'ru' => $lang_module['addads_block_lang_ru'],
	'zz' => $lang_module['addads_block_lang_zz']
);

/**
 * nv_banner_client_checkdata()
 *
 * @param mixed $cookie
 * @return
 */
function nv_banner_client_checkdata( $cookie )
{
	global $db;

	$client = unserialize( $cookie );

	$strlen = ( NV_CRYPT_SHA1 == 1 ) ? 40 : 32;

	$banner_client_info = array();

	if( isset( $client['login'] ) and preg_match( '/^[a-zA-Z0-9_]{' . NV_UNICKMIN . ',' . NV_UNICKMAX . '}$/', $client['login'] ) )
	{
		if( isset( $client['checknum'] ) and preg_match( '/^[a-z0-9]{' . $strlen . '}$/', $client['checknum'] ) )
		{
			$login = $client['login'];
			$stmt = $db->prepare( 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE. '_clients WHERE login = :login AND act=1');
			$stmt->bindParam( ':login', $login, PDO::PARAM_STR );
			$stmt->execute();
			$row = $stmt->fetch();

			if( empty( $row ) ) return array();

			if( strcasecmp( $client['checknum'], $row['check_num'] ) == 0 and 			//checknum
			! empty( $client['current_agent'] ) and strcasecmp( $client['current_agent'], $row['last_agent'] ) == 0 and 			//user_agent
			! empty( $client['current_ip'] ) and strcasecmp( $client['current_ip'], $row['last_ip'] ) == 0 and 			//IP
			! empty( $client['current_login'] ) and strcasecmp( $client['current_login'], intval( $row['last_login'] ) ) == 0 )
			{
				$banner_client_info['id'] = intval( $row['id'] );
				$banner_client_info['login'] = $row['login'];
				$banner_client_info['email'] = $row['email'];
				$banner_client_info['full_name'] = $row['full_name'];
				$banner_client_info['reg_time'] = intval( $row['reg_time'] );
				$banner_client_info['website'] = $row['website'];
				$banner_client_info['location'] = $row['location'];
				$banner_client_info['yim'] = $row['yim'];
				$banner_client_info['phone'] = $row['phone'];
				$banner_client_info['fax'] = $row['fax'];
				$banner_client_info['mobile'] = $row['mobile'];
				$banner_client_info['uploadtype'] = $row['uploadtype'];
				$banner_client_info['current_login'] = intval( $row['last_login'] );
				$banner_client_info['last_login'] = intval( $client['last_login'] );
				$banner_client_info['current_agent'] = $row['last_agent'];
				$banner_client_info['last_agent'] = $client['last_agent'];
				$banner_client_info['current_ip'] = $row['last_ip'];
				$banner_client_info['last_ip'] = $client['last_ip'];
			}
		}
	}

	return $banner_client_info;
}

$bncl = $nv_Request->get_string( 'bncl', 'cookie' );

if( ! empty( $bncl ) )
{
	$banner_client_info = nv_banner_client_checkdata( $bncl );

	if( empty( $banner_client_info ) )
	{
		$nv_Request->unset_request( 'bncl', 'cookie' );
		header( 'Location: ' . $client_info['selfurl'] );
		die();
	}
	define( 'NV_IS_BANNER_CLIENT', true );
}
unset( $bncl );

define( 'NV_IS_MOD_BANNERS', true );