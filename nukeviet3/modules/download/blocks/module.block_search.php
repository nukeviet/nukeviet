<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );
global $module_name, $lang_module, $module_data, $nv_Request;

$xtpl = new XTemplate( "block_search.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$key = filter_text_input( 'q', 'post', '', 1, NV_MAX_SEARCH_LENGTH );
$cat = $nv_Request->get_int( 'cat', 'post' );
$xtpl->assign( 'keyvalue', $key );
$xtpl->assign( 'FORMACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=search' );
$query = "SELECT id, title, alias, parentid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid=0 ORDER BY weight";
$result = $db->sql_query( $query );
while ( $row = $db->sql_fetchrow( $result ) )
{
    $row['select'] = ( $row['id'] == $cat ) ? 'selected=selected' : '';
    $xtpl->assign( 'loop', $row );
    /*	$subdata = getsubcat ( $row ['id'], $cat, '--' );
	if (! empty ( $subdata )) {
		$xtpl->assign ( 'subcat', $subdata );
		unset ( $subdata );
	}*/
    $xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>