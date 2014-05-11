<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 05-05-2010
 */

if( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_sdown_cats' ) )
{
	/**
	 * nv_sdown_cats()
	 *
	 * @param mixed $module_data
	 * @return
	 */
	function nv_sdown_cats( $module_data )
	{
		global $db;

		$sql = 'SELECT id, title, alias, groups_view FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories WHERE status=1';
		$result = $db->query( $sql );

		$list = array();
		while( $row = $result->fetch() )
		{
			if( nv_user_in_groups( $row['groups_view'] ) )
			{
				$list[$row['id']] = array(
					'id' => $row['id'],
					'title' => $row['title'],
					'alias' => $row['alias']
				);
			}
		}
		return $list;
	}
}

$list_cats = nv_sdown_cats( $m_values['module_data'] );
if( ! empty( $list_cats ) )
{
	$_where = 'catid IN (' . implode( ',', array_keys( $list_cats ) ) . ')
	AND (' . nv_like_logic( 'title', $dbkeyword, $logic ) . '
	OR ' . nv_like_logic( 'description', $dbkeyword, $logic ) . '
	OR ' . nv_like_logic( 'introtext', $dbkeyword, $logic ) . ')';


	$db->sqlreset()
		->select( 'COUNT(*)' )
		->from( NV_PREFIXLANG . '_' . $m_values['module_data'] )
		->where( $_where );

	$num_items = $db->query( $db->sql() )->fetchColumn();

	if( $num_items )
	{
		$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';

		$db->select( 'alias,title,description, introtext, catid' )
			->limit($limit)->offset($page);

		$tmp_re = $db->query( $db->sql() );
		while( list( $alias, $tilterow, $content, $introtext, $catid ) = $tmp_re->fetch( 3 ) )
		{
			$content = $content . ' ' . $introtext;

			$result_array[] = array(
				'link' => $link . $list_cats[$catid]['alias'] . '/' . $alias . $global_config['rewrite_exturl'],
				'title' => BoldKeywordInStr( $tilterow, $key, $logic ),
				'content' => BoldKeywordInStr( $content, $key, $logic )
			);
		}
	}
}