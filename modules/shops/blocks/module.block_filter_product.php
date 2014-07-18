<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

if( ! function_exists( 'nv_filter_product' ) )
{
	/**
	 * nv_filter_product()
	 *
	 * @return
	 */
	function nv_filter_product( $block_config )
	{
		global $module_name, $lang_module, $module_info, $module_file, $db, $module_data, $db_config, $id, $catid, $pro_config, $global_config, $global_array_group, $nv_Request;

		$module = $block_config['module'];

		$xtpl = new XTemplate( 'block.filter_product.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
		$xtpl->assign( 'LANG', $lang_module );
		
		foreach( $global_array_group as $arr_group )
		{
			$space = '';
			
			if( ! empty( $arr_group['image'] ) )
			{
				$arr_group['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $arr_group['image'];
			}
			
			if( $arr_group['lev'] > 0 )
			{
				if( $global_array_group[$arr_group['parentid']]['inhome'] and $arr_group['inhome'] )
				{
					for( $i = 1; $i <= $arr_group['lev']; $i++ )
					{
						$space .= '&nbsp;&nbsp;&nbsp;';
					}
					$xtpl->assign( 'DATA', array( 'id' => $arr_group['groupid'], 'title' => $arr_group['title'], 'numpro' => $arr_group['numpro'], 'space' => $space, 'image' => $arr_group['image'] ) );
					$xtpl->parse( 'main.loop.sub_group.checkbox' );
					
					if( ! empty( $arr_group['image'] ) )
					{
						$xtpl->parse( 'main.loop.sub_group.image' );
					}
					
					$xtpl->parse( 'main.loop.sub_group' );
				}
				else
				{
					$global_array_group[$arr_group['groupid']]['inhome'] = 0;
				}
			}
			elseif( $arr_group['inhome'] )
			{
				$xtpl->assign( 'DATA', array( 'title' => $arr_group['title'], 'image' => $arr_group['image'] ) );
				
				if( ! empty( $arr_group['image'] ) )
				{
					$xtpl->parse( 'main.loop.main_group.image' );
				}
				
				$xtpl->parse( 'main.loop.main_group' );
			}
			
			$xtpl->parse( 'main.loop' );
		}

		if( $nv_Request->isset_request( 'filter', 'post' ) )
		{
			$array_id = $nv_Request->get_array( 'group_id', 'post', array() );
			if( ! empty( $array_id ) )
			{
				$array_id = nv_base64_encode( serialize( $array_id ) );
				Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=search_result&filter=' . $array_id );
			}
		}

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

$content = nv_filter_product( $block_config );