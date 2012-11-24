<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['config'];

$array_config = array();

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $array_config['type_main'] = $nv_Request->get_int( 'type_main', 'post', 0 );

    foreach ( $array_config as $config_name => $config_value )
    {
		$query = "REPLACE INTO `" . NV_PREFIXLANG . "_" . $module_data . "_config` VALUES (" . $db->dbescape( $config_name ) . "," . $db->dbescape( $config_value ) . ")";
		$db->sql_query( $query );
    }

    nv_del_moduleCache( $module_name );

    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
    die();
}

$array_config['type_main'] = 0;

$sql = "SELECT `config_name`, `config_value` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config`";
$result = $db->sql_query( $sql );
while ( list( $c_config_name, $c_config_value ) = $db->sql_fetchrow( $result ) )
{
    $array_config[$c_config_name] = $c_config_value;
}

//
$type_main = $array_config['type_main'];
$array_config['type_main'] = array();
$array_config['type_main'][] = array( //
	'key' => 0, //
	'title' => $lang_module['config_type_main_0'], //
	'selected' => 0 == $type_main ? " selected=\"selected\"" : "" //
);
$array_config['type_main'][] = array( //
	'key' => 1, //
	'title' => $lang_module['config_type_main_1'], //
	'selected' => 1 == $type_main ? " selected=\"selected\"" : "" //
);
$array_config['type_main'][] = array( //
	'key' => 2, //
	'title' => $lang_module['config_type_main_2'], //
	'selected' => 2 == $type_main ? " selected=\"selected\"" : "" //
);

$xtpl = new XTemplate( "config.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $array_config );

//
foreach ( $array_config['type_main'] as $type_main )
{
    $xtpl->assign( 'TYPE_MAIN', $type_main );
    $xtpl->parse( 'main.type_main' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>