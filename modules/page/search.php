<?php

/**
 * @Project NUKEVIET V3
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 03-05-2010
 */

if( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

$sql = "SELECT SQL_CALC_FOUND_ROWS id,title,alias,bodytext 
FROM " . NV_PREFIXLANG . "_" . $m_values['module_data'] . " 
WHERE status=1 AND (" . nv_like_logic( 'title', $dbkeyword, $logic ) . " 
OR " . nv_like_logic( 'bodytext', $dbkeyword, $logic ) . ") 
LIMIT " . $pages . "," . $limit;

$tmp_re = $db->query( $sql );

$result = $db->query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $result->fetch( 3 );

if( $all_page )
{
	$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';

	while( list( $id, $tilterow, $alias, $content ) = $tmp_re->fetch( 3 ) )
	{
		$url = $link . $alias;

		$result_array[] = array(
			'link' => $url, //
			'title' => BoldKeywordInStr( $tilterow, $key, $logic ), //
			'content' => BoldKeywordInStr( $content, $key, $logic ) //
		);
	}
}

?>