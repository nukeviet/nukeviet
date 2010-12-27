<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 05/07/2010 09:47
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array( 
    "name" => "News", // Tieu de module
"modfuncs" => "main,viewcat,topic,detail,search,content", // Cac function co block
"is_sysmod" => 0, // 1:0 => Co phai la module he thong hay khong
"virtual" => 1, // 1:0 => Co cho phep ao hao module hay khong
"version" => "3.0.01", // Phien ban cua modle
"date" => "Wed, 20 Oct 2010 00:00:00 GMT", // Ngay phat hanh phien ban
"author" => "VINADES (contact@vinades.vn)", // Tac gia
"note" => "", // Ghi chu
"uploads_dir" => array( 
    $module_name, $module_name . "/thumb", $module_name . "/block", $module_name . "/source", $module_name . "/temp_pic", $module_name . "/" . date( "Y_m" ) 
) 
);

?>