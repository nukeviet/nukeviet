<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array( //
	"name" => "Videoclips", //
	"modfuncs" => "main", //
	"submenu" => "main", //
	"is_sysmod" => 0, //
	"virtual" => 1, //
	"version" => "1.0.0", //
	"date" => "Thu, 20 Sep 2012 04:05:46 GMT", //
	"author" => "VINADES (contact@vinades.vn)", //
	"uploads_dir" => array( $module_name ), //
	"note" => "Module playback of video-clips", //
	"uploads_dir" => array(
		$module_name,
		$module_name . "/icons",
		$module_name . "/images",
		$module_name . "/video" ),
	"files_dir" => array( $module_name ) );

?>