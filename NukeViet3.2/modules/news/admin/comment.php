<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
 
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['comment'];

$global_array_cat = array();
$global_array_cat[0] = array( 
    "catid" => 0, 
	"parentid" => 0, 
	"title" => "Other", 
	"alias" => "Other", 
	"link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=Other", 
	"viewcat" => "viewcat_page_new", 
	"subcatid" => 0, 
	"numlinks" => 3, 
	"description" => "", 
	"keywords" => "" 
);

$sql = "SELECT catid, parentid, title, alias, viewcat, subcatid, numlinks, del_cache_time, description, keywords, lev FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY `order` ASC";
$result = $db->sql_query( $sql );

while ( list( $catid_i, $parentid_i, $title_i, $alias_i, $viewcat_i, $subcatid_i, $numlinks_i, $del_cache_time_i, $description_i, $keywords_i, $lev_i ) = $db->sql_fetchrow( $result ) )
{
    $global_array_cat[$catid_i] = array( 
        "catid" => $catid_i, 
		"parentid" => $parentid_i, 
		"title" => $title_i, 
		"alias" => $alias_i, 
		"link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i, 
		"viewcat" => $viewcat_i, 
		"subcatid" => $subcatid_i, 
		"numlinks" => $numlinks_i, 
		"description" => $description_i, 
		"keywords" => $keywords_i 
    );
}

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 20;
$sql = "SELECT SQL_CALC_FOUND_ROWS a.cid, a.content, a.post_email, a.status, b.id, b.title, b.listcatid, b.alias, c.userid, c.email FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_rows` b ON (a.id=b.id) LEFT JOIN `" . NV_USERS_GLOBALTABLE . "` as c ON (a.userid =c.userid) ORDER BY a.cid DESC LIMIT " . $page . "," . $per_page . "";
$result = $db->sql_query( $sql );

$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $result_all );
$all_page = ( $all_page ) ? $all_page : 1;

$array = array();
$a = 0;
while ( list( $cid, $content, $email, $status, $id, $title, $listcatid, $alias, $userid, $user_email ) = $db->sql_fetchrow( $result ) )
{
    $a ++;
    $arr_listcatid = explode( ",", $listcatid );
    $catid_i = end( $arr_listcatid );
	
    if ( $userid > 0 )
    {
        $email = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=edit&amp;userid=" . $userid . "\"> " . $user_email . "</a>";
    }
	
	$array[$cid] = array (
		"class" => ( $a % 2 ) ? " class=\"second\"" : "",
		"cid" => $cid,
		"email" => $email,
		"content" => $content,
		"link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid_i]['alias'] . "/" . $alias . "-" . $id,
		"title" => $title,
		"status" => ( $status == 1 ) ? $lang_module['comment_enable'] : $lang_module['comment_disable'],
		"linkedit" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=edit_comment&cid=" . $cid,
		"linkdelete" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=del_comment&list=" . $cid
	);
}

$base_url = "" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$xtpl = new XTemplate( "comment.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
//$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

foreach ( $array as $row )
{
	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'main.loop' );	
}

if ( ! empty ( $generate_page ) )
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