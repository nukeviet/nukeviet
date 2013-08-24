<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-31-2010 0:33
 */

if( ! defined( 'NV_IS_MOD_WEBLINKS' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$mod_title = isset( $lang_module['main_title'] ) ? $lang_module['main_title'] : $module_info['custom_title'];
$array_cat = array();

$sort = ( $weblinks_config['sort'] == 'des' ) ? 'desc' : 'asc';
if( $weblinks_config['sortoption'] == 'byhit' ) $orderby = 'hits_total ';
elseif( $weblinks_config['sortoption'] == 'byid' ) $orderby = 'id ';
elseif( $weblinks_config['sortoption'] == 'bytime' ) $orderby = 'add_time ';
else $orderby = 'rand() ';

$array_cat_content = array();
$urllink = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=";

foreach( $global_array_cat as $catid_i => $array_cat_i )
{
	$content = array();
	if( $array_cat_i['parentid'] == 0 )
	{
		$array_cat[$catid_i] = array(
			"title" => $array_cat_i['title'],
			"link" => $array_cat_i['link'],
			"description" => $array_cat_i['description'],
			"catimage" => $array_cat_i['catimage'],
			"subcat" => array()
		);

		$sql = "SELECT `id` , `author` , `title` , `alias` , `url` , `urlimg` , `note` , `description` , `add_time` , `hits_total` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status` = 1 AND `catid` =" . $catid_i . " ORDER BY " . $orderby . $sort . " LIMIT 0,3";
		$result = $db->sql_query( $sql );
		while( $row = $db->sql_fetch_assoc( $result ) )
		{
			$row['link'] = $global_array_cat[$catid_i]['link'] . "/" . $row['alias'] . "-" . $row['id'];
			$row['linkvi'] = $urllink . "visitlink-" . $row['alias'] . "-" . $row['id'];
			$content[] = $row;
		}
	}
	else
	{
		$parentid = $array_cat_i['parentid'];

		$array_cat[$parentid]['subcat'][] = array(
			"title" => $global_array_cat[$catid_i]['title'],
			"link" => $global_array_cat[$catid_i]['link'],
			"count_link" => $global_array_cat[$catid_i]['count_link']
		);
	}
	$array_cat_content[$catid_i] = $content;
}

$contents = call_user_func( "main_theme", $array_cat, $array_cat_content );

include ( NV_ROOTDIR . '/includes/header.php' );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . '/includes/footer.php' );

?>