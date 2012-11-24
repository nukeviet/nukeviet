<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 05/10/2010 14:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
	"name" => "Banners", //
	"modfuncs" => "main, clientinfo, addads, stats", //
	"is_sysmod" => 1, //
	"virtual" => 0, //
	"version" => "3.0.01", //
	"date" => "Wed, 20 Oct 2010 00:00:00 GMT", //
	"author" => "VINADES (contact@vinades.vn)", //
	"note" => "",
	"uploads_dir" => array( $module_name )
);

?>