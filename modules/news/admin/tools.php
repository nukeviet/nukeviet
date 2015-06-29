<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 - 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );

$page_title = 'tools';
$fcatid = $nv_Request->get_int( 'fcatid', 'get', 0 );
$tcatid = $nv_Request->get_int( 'tcatid', 'get', 0 );

$checkss = $nv_Request->get_string( 'checkss', 'get', '' );
if( ($checkss == md5( session_id( ) ) and ! empty( $fcatid ) and ! empty( $tcatid )) and $fcatid != $tcatid )
{
	$_sql = 'SELECT id, listcatid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fcatid;
	$_query = $db->query( $_sql );
	while( $row = $_query->fetch( ) )
	{
		$arrcatid = explode( ',', $row['listcatid'] );
		if( ! in_array( $tcatid, $arrcatid ) )
		{
			$arrcatid[] = $tcatid;
			$listcatid = implode( ',', array_unique( $arrcatid ) );
			$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_" . $fcatid . " SET `listcatid` = '" . $listcatid . "' WHERE id = " . $row['id'] );
			try
			{
				$db->exec( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $tcatid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fcatid . ' WHERE id=' . $row['id'] );
				$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET `listcatid` = '" . $listcatid . "' WHERE id = " . $row['id'] );
			}
			catch( PDOException $e )
			{
				trigger_error( $e->getMessage( ) );
			}
		}
	}

}

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'CHECKSS', md5( session_id( ) ) );

$check_declined = false;
foreach( $global_array_cat as $catid_i => $array_value )
{
	$lev_i = $array_value['lev'];

	$xtitle_i = '';
	if( $lev_i > 0 )
	{
		$xtitle_i .= '&nbsp;&nbsp;&nbsp;|';
		for( $i = 1; $i <= $lev_i; ++$i )
		{
			$xtitle_i .= '---';
		}
		$xtitle_i .= '>&nbsp;';
	}
	$xtitle_i .= $array_value['title'];

	$cat_content = array(
		'value' => $catid_i,
		'selected' => ($catid_i == $fcatid) ? ' selected="selected"' : '',
		'title' => $xtitle_i
	);
	$xtpl->assign( 'CAT_CONTENT', $cat_content );
	$xtpl->parse( 'main.fcatid' );

	$cat_content['selected'] = ($catid_i == $tcatid) ? ' selected="selected"' : '';
	$xtpl->assign( 'CAT_CONTENT', $cat_content );
	$xtpl->parse( 'main.tcatid' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
?>