<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 05/07/2010 09:47
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array( //
    "name" => "Download", //
    "modfuncs" => "main,down,upload,report", //
    "is_sysmod" => 0, //
    "virtual" => 0, //
    "version" => "3.0.09", //
    "date" => "Fri, 7 May 2010 09:47:15 GMT", //
    "author" => "VINADES (contact@vinades.vn)", //
    "note" => "", //
    "uploads_dir" => array( //
    $module_name, //
    $module_name . "/files", //
    $module_name . "/images", //
    $module_name . "/temp", //
    $module_name . "/thumb" //
    ) );

?>