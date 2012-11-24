<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['weblink_link_broken'];

$xtpl = new XTemplate( "link_broken.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$numcat = $db->sql_numrows( $db->sql_query( "SELECT a.id FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_report` b ON a.id=b.id" ) );

$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
$all_page = ( $numcat > 1 ) ? $numcat : 1;
$per_page = 10;
$page = $nv_Request->get_int( 'page', 'get', 0 );

$sql = "SELECT a.url,a.title,b.type,a.id FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_report` b ON a.id=b.id LIMIT $page,$per_page"; // GROUP BY a.url
$result = $db->sql_query( $sql );

if( $numcat > 0 )
{
	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=delbroken" );

	$a = 0;
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$xtpl->assign( 'ROW', array(
			"class" => ( $a % 2 ) ? " class=\"second\"" : "",
			"id" => $row['id'],
			"title" => $row['title'],
			"url" => $row['url'],
			"type" => $row['type'] == 1 ? $lang_module['weblink_link_broken_die'] : $lang_module['weblink_link_broken_bad'],
			"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content&amp;id=" . $row['id'],
		) );
		
		$xtpl->parse( 'main.data.loop' );
		++ $a;
	}
	
	$xtpl->parse( 'main.data' );
}
else
{
	$xtpl->parse( 'main.empty' );
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
if( ! empty( $generate_page ) )
{
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>