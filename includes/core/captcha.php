<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/28/2009 23:50
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( $global_config['captcha_type'] == 1 )
{
	require NV_ROOTDIR . '/includes/class/SimpleCaptcha.class.php';

	$captcha = new SimpleCaptcha();

	if( file_exists( NV_ROOTDIR . '/includes/keywords/ccaptcha_' . NV_LANG_INTERFACE . '.php' ) )
	{
		$captcha->wordsFile = 'keywords/ccaptcha_' . NV_LANG_INTERFACE . '.php';
	}
	else
	{
		$captcha->wordsFile = 'keywords/captcha_vi.php';
	}

	$captcha->session_var = 'scaptcha';
	$captcha->width = NV_GFX_WIDTH;
	$captcha->height = NV_GFX_HEIGHT;

	$captcha->minWordLength = NV_GFX_NUM;
	$captcha->maxWordLength = NV_GFX_NUM;

	$captcha->Yperiod = 1;
	$captcha->Yamplitude = 0;
	$captcha->Xperiod = 1;
	$captcha->Xamplitude = 0;

	$captcha->maxRotation = 30;
	$captcha->scale = 3;
	$captcha->debug = false;

	$captcha->backgroundColor = array( 228, 228, 228 );
	$captcha->resourcesPath = NV_ROOTDIR . '/includes';

	$captcha->CreateImage();
	die();
}
else
{
	mt_srand( ( double )microtime() * 1000000 );
	$maxran = 1000000;
	$random_num = mt_rand( 1, $maxran );

	$nv_Request->set_Session( 'random_num', $random_num );

	$datekey = date( 'F j' );
	$rcode = strtoupper( md5( NV_USER_AGENT . $global_config['sitekey'] . $random_num . $datekey ) );
	$code = substr( $rcode, 2, NV_GFX_NUM );

	$image = imagecreate( NV_GFX_WIDTH, NV_GFX_HEIGHT );
	$bgc = imagecolorallocate( $image, 240, 240, 240 );
	imagefilledrectangle( $image, 0, 0, NV_GFX_WIDTH, NV_GFX_HEIGHT, $bgc );

	$text_color = ImageColorAllocate( $image, 50, 50, 50 );
	/* output each character */
	for( $l = 0; $l < 5; ++$l )
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
	$font = NV_ROOTDIR . '/includes/fonts/captcha/font' . $ff . '.ttf';

	if( file_exists( $font ) and nv_function_exists( 'imagettftext' ) )
	{
		imagettftext( $image, 15, 0, 5, NV_GFX_HEIGHT - 3, $text_color, $font, $code );
	}
	else
	{
		ImageString( $image, 5, 20, 6, $code, $text_color );
	}

	Header( 'Content-type: image/jpeg' );
	header( 'Cache-Control:' );
	header( 'Pragma:' );
	header( 'Set-Cookie:' );
	imagejpeg( $image, null, 80 );
	imagedestroy( $image );
	die();
}