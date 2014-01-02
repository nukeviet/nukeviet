<?php

/**
 * @Project NUKEVIET V3
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 03-05-2010
 */

if( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

$sql = "SELECT SQL_CALC_FOUND_ROWS r.id, r.title, r.alias, r.catid, r.hometext, c.bodytext 
	FROM " . NV_PREFIXLANG . "_" . $m_values['module_data'] . "_rows r 
	INNER JOIN " . NV_PREFIXLANG . "_" . $m_values['module_data'] . "_bodytext c ON (r.id=c.id) 
	WHERE (" . nv_like_logic( 'r.title', $dbkeyword, $logic ) . " 
	OR " . nv_like_logic( 'r.hometext', $dbkeyword, $logic ) . ") 
	OR " . nv_like_logic( 'c.bodytext', $dbkeyword, $logic ) . " 
	AND r.status= 1 
	LIMIT " . $pages . "," . $limit;

$tmp_re = $db->query( $sql );

$result = $db->query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $result->fetch( 3 );

if( $all_page )
{
	$array_cat_alias = array();
	$array_cat_alias[0] = "other";

	$sql_cat = "SELECT catid, alias FROM " . NV_PREFIXLANG . "_" . $m_values['module_data'] . "_cat";
	$re_cat = $db->query( $sql_cat );
	while( list( $catid, $alias ) = $re_cat->fetch( 3 ) )
	{
		$array_cat_alias[$catid] = $alias;
	}

	$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';

	while( list( $id, $tilterow, $alias, $catid, $hometext, $bodytext ) = $tmp_re->fetch( 3 ) )
	{
		$content = $hometext . $bodytext;

		$url = $link . $array_cat_alias[$catid] . '/' . $alias . "-" . $id;

		$result_array[] = array( //
			'link' => $url, //
			'title' => BoldKeywordInStr( $tilterow, $key, $logic ), //
			'content' => BoldKeywordInStr( $content, $key, $logic ) //
		);
	}
}

?>