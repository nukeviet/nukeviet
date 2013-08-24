<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */

if( ( ! defined( 'NV_SYSTEM' ) and ! defined( 'NV_ADMIN' ) ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

unset( $lang_global, $lang_module, $language_array, $nv_parse_ini_browsers, $nv_parse_ini_mobile, $nv_parse_ini_os, $nv_parse_ini_timezone );
global $db, $nv_Request;

$contents = ob_get_contents();
ob_end_clean();
$contents = $db->unfixdb( $contents );
$contents = nv_url_rewrite( $contents );
if( ! defined( 'NV_IS_AJAX' ) )
{
	$contents = nv_change_buffer( $contents );
	if( defined( 'NV_IS_SPADMIN' ) )
	{
		$contents = str_replace( '[COUNT_SHOW_QUERIES]', sizeof( $db->query_strs ) . ' / ' . nv_convertfromBytes( memory_get_usage() ) . ' / ' . number_format( ( array_sum( explode( " ", microtime() ) ) - NV_START_TIME ), 3, '.', '' ), $contents );
	}
}
$db->sql_close();

//Nen trang
if( defined( 'NV_IS_GZIP' ) )
{
	$http_accept_encoding = $nv_Request->get_string( 'HTTP_ACCEPT_ENCODING', 'server', '' );

	if( ! empty( $http_accept_encoding ) )
	{
		$compress_list = array();
		$compress_list['deflate'] = 'gzdeflate';
		$compress_list['gzip'] = 'gzencode';
		$compress_list['x-gzip'] = 'gzencode';
		$compress_list['compress'] = 'gzcompress';
		$compress_list['x-compress'] = 'gzcompress';

		$http_accept_encoding = explode( ",", str_replace( ' ', '', $http_accept_encoding ) );

		foreach( $http_accept_encoding as $enc )
		{
			if( ! empty( $enc ) and isset( $compress_list[$enc] ) )
			{
				if( nv_function_exists( $compress_list[$enc] ) )
				{
					$contents = call_user_func( $compress_list[$enc], $contents, ZLIB_OUTPUT_COMPRESSION_LEVEL );
					@Header( 'Content-Encoding: ' . $enc );
					@Header( 'Vary: Accept-Encoding' );
					break;
				}
			}
		}
	}
}

echo $contents;
exit();

?>