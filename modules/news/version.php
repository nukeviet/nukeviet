<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @createdate 05/07/2010 09:47
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
	"name" => "News", // Tieu de module
	"modfuncs" => "main,viewcat,topic,groups,detail,search,content,tag,rss", // Cac function co block
	"change_alias" => "topic,groups,content,rss",
	"is_sysmod" => 0, // 1:0 => Co phai la module he thong hay khong
	"virtual" => 1, // 1:0 => Co cho phep ao hao module hay khong
	"version" => "3.5.00", // Phien ban cua modle
	"date" => "Wed, 20 Oct 2010 00:00:00 GMT", // Ngay phat hanh phien ban
	"author" => "VINADES (contact@vinades.vn)", // Tac gia
	"note" => "", // Ghi chu
	"uploads_dir" => array( $module_name, $module_name . "/source", $module_name . "/temp_pic", $module_name . "/topics" ),
	"files_dir" => array( $module_name . "/topics" )
);

?>