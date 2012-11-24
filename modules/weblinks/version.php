<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 05/07/2010 09:47
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array( 
    "name" => "weblinks", //
    "modfuncs" => "main,viewcat,detail", //
    "is_sysmod" => 0, //
    "virtual" => 1, //
    "version" => "3.0.01", //
    "date" => "Wed, 20 Oct 2010 00:00:00 GMT", //
    "author" => "VINADES (contact@vinades.vn)", //
    "note" => "",
	"uploads_dir" => array(
		$module_name,
		$module_name . "/cat",
		$module_name . "/thumb",
	)
);

?>