<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_PAGE', true );

// Cau hinh
$sql = "SELECT config_name,config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config";
$list = nv_db_cache( $sql );
$page_config = array();
foreach( $list as $values )
{
	$page_config[$values['config_name']] = $values['config_value'];
}

$id = 0;
$page = 1;
$ab_links = array();

$alias = ( ! empty( $array_op ) and ! empty( $array_op[0] ) ) ? $array_op[0] : '';

if( substr( $alias, 0, 5) == 'page-' )
{
    $page = intval( substr( $array_op[0], 5));
	$id = 0;
	$alias = '';
}
elseif( $page_config['viewtype'] == 0 )
{
	$row = array_shift( $rows );
	$id = $row['id'];
}