<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/29/2009 20:7
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

//Khong can doan nay vi da co AUTOLOAD
//require NV_ROOTDIR . '/includes/class/qrcode.class.php';

$url = $nv_Request->get_string( 'u', 'get', '' );
$level = $nv_Request->get_title( 'l', 'get', '' );
$pixel_per_point = $nv_Request->get_int( 'ppp', 'get', 4 );
$outer_frame = $nv_Request->get_int( 'of', 'get', 1 );

if( ! empty( $url ) and in_array( $level, array( 'L', 'M', 'Q', 'H' ) ) and ( $pixel_per_point > 0 and $pixel_per_point < 13 ) and ( $outer_frame > 0 and $outer_frame < 6 ) )
{
	QRcode::png( $url, false, $level, $pixel_per_point, $outer_frame );
}

exit;