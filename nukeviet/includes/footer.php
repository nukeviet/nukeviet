<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */

if( ( ! defined( 'NV_SYSTEM' ) and ! defined( 'NV_ADMIN' ) ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

global $db, $nv_Request, $sys_info;

$db->sql_close();
$page = ob_get_contents();
ob_end_clean();

if( ! defined( 'NV_IS_AJAX' ) )
{
	$page = nv_change_buffer( $page );
}
else
{
	$page = $db->unfixdb( $page );
	$page = nv_url_rewrite( $page );
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

		$http_accept_encoding = explode( ",", str_replace( ' ', '', $http_accept_encoding ) );

		foreach( $http_accept_encoding as $enc )
		{
			if( ! empty( $enc ) and isset( $compress_list[$enc] ) )
			{
				if( nv_function_exists( $compress_list[$enc] ) )
				{
					$page = call_user_func( $compress_list[$enc], $page, ZLIB_OUTPUT_COMPRESSION_LEVEL );
					
					@Header( 'Content-Encoding: ' . $enc );
					@Header( 'Vary: Accept-Encoding' );
					break;
				}
			}
		}
	}
}

/*$strlen = strlen( $page );
@Header( 'Content-Length: ' . $strlen );
@Header( 'Accept-Ranges: bytes' );*/

echo $page;
exit();

?>