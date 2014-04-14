<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if( ( ! defined( 'NV_SYSTEM' ) and ! defined( 'NV_ADMIN' ) ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

unset( $lang_module, $language_array, $nv_parse_ini_browsers, $nv_parse_ini_mobile, $nv_parse_ini_os, $nv_parse_ini_timezone );
global $db, $nv_Request, $nv_plugin_area;

$contents = ob_get_contents();
ob_end_clean();
$contents = nv_url_rewrite( $contents );
if( ! defined( 'NV_IS_AJAX' ) )
{
	$contents = nv_change_buffer( $contents );
	if( defined( 'NV_IS_SPADMIN' ) )
	{
		$contents = str_replace( '[MEMORY_TIME_USAGE]', sprintf( $lang_global['memory_time_usage'] , nv_convertfromBytes( memory_get_usage() ), number_format( ( microtime( true ) - NV_START_TIME ), 3, '.', '' ) ), $contents );
	}
}
$db = null;

if( isset( $nv_plugin_area[3] ) )
{
    // Kết nối với các plugin Trước khi website gửi nội dung tới trình duyệt
    foreach ( $nv_plugin_area[3] as $_fplugin )
    {
        include NV_ROOTDIR . '/includes/plugin/' . $_fplugin;
    }
}

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

		$http_accept_encoding = explode( ',', str_replace( ' ', '', $http_accept_encoding ) );

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