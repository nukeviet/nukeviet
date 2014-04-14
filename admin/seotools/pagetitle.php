<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/10/2011, 23:14
 */

if( ! defined( 'NV_IS_FILE_SEOTOOLS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['pagetitle'];

$array_config = array();
if( $nv_Request->isset_request( 'save', 'post' ) )
{
	$pageTitleMode = $nv_Request->get_title( 'pageTitleMode', 'post', '', 1);

	$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = 'pageTitleMode'" );
	$sth->bindParam( ':config_value', $pageTitleMode, PDO::PARAM_STR, 255 );
	$sth->execute();

	nv_delete_all_cache( false );
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
	exit();
}

if( ! isset( $global_config['pageTitleMode'] ) or empty( $global_config['pageTitleMode'] ) ) $global_config['pageTitleMode'] = 'pagetitle - sitename';

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $global_config );

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $content );
include NV_ROOTDIR . '/includes/footer.php';