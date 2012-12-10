<?php

define( 'NV_ROOTDIR', str_replace( '\\', '/', realpath( pathinfo( __file__, PATHINFO_DIRNAME ) . '/../../' ) ) );

if( $_SERVER['REMOTE_ADDR'] = '127.0.0.1' AND is_dir( NV_ROOTDIR . '/js/ui/development-bundle' ) )
{
	$data = file_get_contents( NV_ROOTDIR . '/js/ui/development-bundle/ui/minified/jquery.ui.core.min.js' );
	$data = str_replace( '* Includes: jquery.ui.core.js', '* Includes: jquery.ui.core.js, jquery.ui.widget.js, jquery.ui.position.js, jquery.ui.mouse.js', $data );

	$data .= file_get_contents( NV_ROOTDIR . '/js/ui/development-bundle/ui/minified/jquery.ui.widget.min.js' );
	$data .= file_get_contents( NV_ROOTDIR . '/js/ui/development-bundle/ui/minified/jquery.ui.mouse.min.js' );
	$data .= file_get_contents( NV_ROOTDIR . '/js/ui/development-bundle/ui/minified/jquery.ui.position.min.js' );

	$data = str_replace( '* Includes: jquery.ui.widget.js', '', $data );
	$data = str_replace( '* Includes: jquery.ui.position.js', '', $data );
	$data = str_replace( '* Includes: jquery.ui.mouse.js', '', $data );

	$data = str_replace( '/*! jQuery UI - v1.9.2 - 2012-11-23
* http://jqueryui.com

* Copyright 2012 jQuery Foundation and other contributors; Licensed MIT */', '', $data );

	file_put_contents( NV_ROOTDIR . '/js/ui/jquery.ui.core.min.js', $data, LOCK_EX );

	$data = file_get_contents( NV_ROOTDIR . '/js/ui/development-bundle/ui/minified/jquery.ui.datepicker.min.js' );
	file_put_contents( NV_ROOTDIR . '/js/ui/jquery.ui.datepicker.min.js', $data, LOCK_EX );

	$data = file_get_contents( NV_ROOTDIR . '/js/ui/development-bundle/ui/minified/jquery.ui.dialog.min.js' );
	file_put_contents( NV_ROOTDIR . '/js/ui/jquery.ui.dialog.min.js', $data, LOCK_EX );

	$data = file_get_contents( NV_ROOTDIR . '/js/ui/development-bundle/ui/minified/jquery.ui.draggable.min.js' );
	file_put_contents( NV_ROOTDIR . '/js/ui/jquery.ui.draggable.min.js', $data, LOCK_EX );

	$data = file_get_contents( NV_ROOTDIR . '/js/ui/development-bundle/ui/minified/jquery.ui.menu.min.js' );
	file_put_contents( NV_ROOTDIR . '/js/ui/jquery.ui.menu.min.js', $data, LOCK_EX );

	$data = file_get_contents( NV_ROOTDIR . '/js/ui/development-bundle/ui/minified/jquery.ui.resizable.min.js' );
	file_put_contents( NV_ROOTDIR . '/js/ui/jquery.ui.resizable.min.js', $data, LOCK_EX );

	$data = file_get_contents( NV_ROOTDIR . '/js/ui/development-bundle/ui/minified/jquery.ui.sortable.min.js' );
	file_put_contents( NV_ROOTDIR . '/js/ui/jquery.ui.sortable.min.js', $data, LOCK_EX );

	$data = file_get_contents( NV_ROOTDIR . '/js/ui/development-bundle/ui/minified/jquery.ui.tabs.min.js' );
	file_put_contents( NV_ROOTDIR . '/js/ui/jquery.ui.tabs.min.js', $data, LOCK_EX );

	$data = file_get_contents( NV_ROOTDIR . '/js/ui/development-bundle/ui/minified/jquery.ui.tooltip.min.js' );
	file_put_contents( NV_ROOTDIR . '/js/ui/jquery.ui.tooltip.min.js', $data, LOCK_EX );

	$array_css = array(
		'jquery.ui.core.css',
		'jquery.ui.theme.css',
		'jquery.ui.autocomplete.css',
		'jquery.ui.button.css',
		'jquery.ui.datepicker.css',
		'jquery.ui.dialog.css',
		'jquery.ui.menu.css',
		'jquery.ui.resizable.css',
		'jquery.ui.tabs.css',
		'jquery.ui.tooltip.css'
	);
	foreach( $array_css as $file )
	{
		$data = file_get_contents( NV_ROOTDIR . '/js/ui/development-bundle/themes/base/' . $file );
		file_put_contents( NV_ROOTDIR . '/js/ui/' . $file, $data, LOCK_EX );
	}

	$language = scandir( NV_ROOTDIR . '/language/' );
	$language[] = 'de';
	$language[] = 'ru';
	foreach( $language as $lang )
	{
		if( preg_match( "/^[a-z]{2}$/", $lang ) )
		{
			if( $lang == "en" )
			{
				$lang_f = 'en-GB';
			}
			else
			{
				$lang_f = $lang;
			}
			$filename = NV_ROOTDIR . '/js/ui/development-bundle/ui/minified/i18n/jquery.ui.datepicker-' . $lang_f . '.min.js';
			if( file_exists( $filename ) )
			{
				$data = file_get_contents( $filename );
				file_put_contents( NV_ROOTDIR . '/js/language/jquery.ui.datepicker-' . $lang . '.js', $data, LOCK_EX );
			}
		}
	}
}
else
{
	die( 'error development bundle ui' );
}
?>