<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_WEBLINKS' ) ) die( 'Stop!!!' );

global $global_array_cat;

$page_title = $global_array_cat[$catid]['title'];
$key_words = $global_array_cat[$catid]['keywords'];
$description = $global_array_cat[$catid]['description'];

$items = array();
$array_subcat = array();
$array_cat = array();
foreach( $global_array_cat as $array_cat_i )
{
	if( $array_cat_i['parentid'] == $catid )
	{
		$array_subcat[] = array(
			"title" => $array_cat_i['title'],
			"link" => $array_cat_i['link'],
			"count_link" => $array_cat_i['count_link']
		);
	}
}

$array_cat[] = array(
	"title" => $global_array_cat[$catid]['title'],
	"link" => $global_array_cat[$catid]['link'],
	"description" => $global_array_cat[$catid]['description']
);

$sort = ( $weblinks_config['sort'] == 'des' ) ? 'desc' : 'asc';
if( $weblinks_config['sortoption'] == 'byhit' ) $orderby = 'hits_total ';
elseif( $weblinks_config['sortoption'] == 'byid' ) $orderby = 'id ';
elseif( $weblinks_config['sortoption'] == 'bytime' ) $orderby = 'add_time ';
else  $orderby = 'rand() ';
$base_url = $global_array_cat[$catid]['link'];

$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `author`, `title`, `alias`, `url`, `urlimg`, `add_time`, `description`,`hits_total` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE status='1' AND catid='" . $catid . "' ORDER BY " . $orderby . $sort . " LIMIT " . ( $page - 1 ) * $per_page . "," . $per_page;
$result = $db->sql_query( $sql );

$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $result_all );

while( $row = $db->sql_fetchrow( $result ) )
{
	$author = explode( '|', $row['author'] );
	
	if( $author[0] == 1 )
	{
		$sql1 = "SELECT * FROM `" . NV_AUTHORS_GLOBALTABLE . "` WHERE `id`=" . $author[1] . "";
		$result1 = $db->sql_query( $sql1 );
		$row1 = $db->sql_fetchrow( $result1 );
		$row['author'] = $row1;
	}
	
	$row['link'] = $global_array_cat[$catid]['link'] . "/" . $row['alias'] . "-" . $row['id'];
	$row['visit'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=visitlink-" . $row['alias'] . "-" . $row['id'];
	$row['report'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=reportlink-" . $row['alias'] . "-" . $row['id'];
	
	$urlimg = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $row['urlimg'];
	$imageinfo = nv_ImageInfo( $urlimg, 300, true, NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb' );
	$row['urlimg'] = $imageinfo['src'];
	
	$items[] = $row;
}

$contents = call_user_func( "viewcat", $array_subcat, $array_cat, $items );
$contents .= nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );

if( $page > 1 )
{
	$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>