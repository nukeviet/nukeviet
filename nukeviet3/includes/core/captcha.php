<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/28/2009 23:50
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

mt_srand( ( double )microtime() * 1000000 );
$maxran = 1000000;
$random_num = mt_rand( 1, $maxran );

$scaptcha = $nv_Request->get_string( 'scaptcha', 'get' );
$scaptcha = preg_replace( '/[^a-z0-9\-]/', '', $scaptcha );
$skeycaptcha = ( $scaptcha == "captcha" ) ? "random_num" : "random_" . substr( $scaptcha, 0, 20 );

$nv_Request->set_Session( $skeycaptcha, $random_num );

$datekey = date( "F j" );
$rcode = strtoupper( md5( NV_USER_AGENT . $global_config['sitekey'] . $random_num . $datekey ) );
$code = substr( $rcode, 2, NV_GFX_NUM );

$image = imagecreate( NV_GFX_WIDTH, NV_GFX_HEIGHT );
$bgc = imagecolorallocate( $image, 240, 240, 240 );
imagefilledrectangle( $image, 0, 0, NV_GFX_WIDTH, NV_GFX_HEIGHT, $bgc );

$text_color = ImageColorAllocate( $image, 50, 50, 50 );
/* output each character */
for ( $l = 0; $l < 5; $l ++ )
{
    $r = mt_rand( 120, 255 );
    $g = mt_rand( 120, 255 );
    $b = mt_rand( 120, 255 );
    $color_elipse = ImageColorAllocate( $image, round( $r * 0.90 ), round( $g * 0.90 ), round( $b * 0.90 ) );
    $cx = mt_rand( 0, NV_GFX_WIDTH - NV_GFX_HEIGHT );
    $cy = mt_rand( 0, NV_GFX_WIDTH - NV_GFX_HEIGHT );
    $rx = mt_rand( 10, NV_GFX_WIDTH - NV_GFX_HEIGHT );
    $ry = mt_rand( 10, NV_GFX_WIDTH - NV_GFX_HEIGHT );
    ImageFilledEllipse( $image, $cx, $cy, $rx, $ry, $color_elipse );
}
$r = mt_rand( 0, 100 );
$g = mt_rand( 0, 100 );
$b = mt_rand( 0, 100 );
$text_color = ImageColorAllocate( $image, $r, $g, $b );

$ff = mt_rand( 1, 15 );
$font = NV_ROOTDIR . "/includes/fonts/captcha/font" . $ff . ".ttf";
if ( file_exists( $font ) and function_exists( 'imagettftext' ) )
{
    imagettftext( $image, 15, 0, 5, NV_GFX_HEIGHT - 3, $text_color, $font, $code );
}
else
{
    ImageString( $image, 5, 20, 6, $code, $text_color );
}
Header( "Content-type: image/jpeg" );
ImageJPEG( $image, '', 90 );
ImageDestroy( $image );
die();

?>;