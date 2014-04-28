<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 03-05-2010
 */

if( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

$db->sqlreset()
	->select( 'COUNT(*)' )
	->from(NV_PREFIXLANG . '_' . $m_values['module_data'])
	->where( 'status=1 AND (' . nv_like_logic( 'title', $dbkeyword, $logic ) . ' OR ' . nv_like_logic( 'description', $dbkeyword, $logic ) . ' OR ' . nv_like_logic( 'bodytext', $dbkeyword, $logic ) . ')' );
$num_items = $db->query( $db->sql() )->fetchColumn();

if( $num_items )
{
	$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';

	$db->select( 'id,title, alias, description, bodytext' )
		->limit( $limit )
		->offset( ( $page - 1 ) * $limit );
	$result = $db->query( $db->sql() );
	while( list( $id, $tilterow, $alias, $description, $content ) = $result->fetch( 3 ) )
	{
		$result_array[] = array(
			'link' => $link . $alias . $global_config['rewrite_exturl'],
			'title' => BoldKeywordInStr( $tilterow, $key, $logic ),
			'content' => BoldKeywordInStr( $description . ' ' . $content, $key, $logic )
		);
	}
}