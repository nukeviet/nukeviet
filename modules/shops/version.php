<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @License GNU/GPL version 2 or any later version
 * @Createdate 05/07/2010 09:47
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
	'name' => 'Shops', // Tieu de module
	'modfuncs' => 'main,viewcat,detail,search,cart,order,payment,complete,history,group,search_result,compare', // Cac function co block
	'is_sysmod' => 0, // 1:0 => Co phai la module he thong hay khong
	'virtual' => 1, // 1:0 => Co cho phep ao hao module hay khong
	'version' => '4.0.00', // Phien ban cua module
	'date' => 'Sun, 21 Jun 2013 00:50:00 GMT', // Ngay phat hanh phien ban
	'author' => 'VINADES (contact@vinades.vn)', // Tac gia
	'note' => '', // Ghi chu
	'uploads_dir' => array( $module_name, $module_name . '/temp_pic', $module_name . '/source', $module_name . '/' . date( 'Y_m' ) )
);