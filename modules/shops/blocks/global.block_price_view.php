<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! function_exists( 'nv_view_product_price' ) )
{
	/**
	 * nv_block_config_product_price_blocks()
	 * 
	 * @param mixed $module
	 * @param mixed $data_block
	 * @param mixed $lang_block
	 * @return
	 */
	function nv_block_config_product_price_blocks( $module, $data_block, $lang_block )
	{
		$html = '<tr>';
		$html .= '	<td>' . $lang_block['price_begin'] . '</td>';
		$html .= '	<td><input type="text" name="config_price_begin" value="' . $data_block['price_begin'] . '"/></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '	<td>' . $lang_block['price_end'] . '</td>';
		$html .= '	<td><input type="text" name="config_price_end" value="' . $data_block['price_end'] . '"/></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '	<td>' . $lang_block['price_step'] . '</td>';
		$html .= '	<td><input type="text" name="config_price_step" value="' . $data_block['price_step'] . '"/></td>';
		$html .= '</tr>';
		return $html;
	}
	/**
	 * numoney_to_strmoney()
	 * 
	 * @param mixed $money
	 * @param mixed $mod_file
	 * @return
	 */
	function numoney_to_strmoney( $money, $mod_file )
	{
		include NV_ROOTDIR . '/modules/' . $mod_file . '/language/' . NV_LANG_DATA . '.php' ;
		if( $money > 1000 and $money < 1000000 )
		{
			$money = $money / 1000;
			return $money . ' ' . $lang_module['money_thousand'];
		}
		elseif( $money >= 1000000 )
		{
			$money = $money / 1000000;
			return $money . ' ' . $lang_module['money_million'];
		}
		return $money;
	}
	/**
	 * nv_block_config_product_price_blocks_submit()
	 * 
	 * @param mixed $module
	 * @param mixed $lang_block
	 * @return
	 */
	function nv_block_config_product_price_blocks_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['price_begin'] = $nv_Request->get_float( 'config_price_begin', 'post', 0 );
		$return['config']['price_end'] = $nv_Request->get_float( 'config_price_end', 'post', 0 );
		$return['config']['price_step'] = $nv_Request->get_float( 'config_price_step', 'post', 0 );
		return $return;
	}
	/**
	 * nv_view_product_price()
	 * 
	 * @param mixed $block_config
	 * @return
	 */
	function nv_view_product_price( $block_config )
	{
		global $site_mods, $module_info, $nv_Request, $catid;

		$cataid = $nv_Request->get_int( 'cata', 'get', 0 );

		if( $cataid == 0 ) $cataid = $catid;
		$recata = '';
		if( $cataid > 0 ) $recata = '&cata=' . $cataid;

		$module = $block_config['module'];
		$mod_data = $site_mods[$module]['module_data'];
		$mod_file = $site_mods[$module]['module_file'];

		include NV_ROOTDIR . '/modules/' . $mod_file . '/language/' . NV_LANG_DATA . '.php';

		if( file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module . '/block.price_view.tpl' ) )
		{
			$block_theme = $module_info['template'];
		}
		else
		{
			$block_theme = 'default';
		}

		$xtpl = new XTemplate( 'block.price_view.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $module );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'TEMPLATE', $block_theme );
		$xtpl->assign( 'MODULE_FILE', $mod_file );

		$val = $block_config['price_begin'];

		while( true )
		{
			$price1 = $val;
			$price2 = $val + $block_config['price_step'];
			$arr_price = array();

			if( $val < $block_config['price_end'] )
			{
				$title = '<span class="label label-success">' . numoney_to_strmoney( $price1, $mod_file ) . '</span> <span class="glyphicon glyphicon-arrow-right"></span> ' . '<span class="label label-success">' . numoney_to_strmoney( $price2, $mod_file ) . '</span>';
				$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=search_result&price1=' . $price1 . '&price2=' . $price2 . $recata;
				$arr_price = array( 'title' => $title, 'link' => $link );
			}
			elseif( $val >= $block_config['price_end'] )
			{
				$title = '<span class="label label-warning">' . $lang_module['price2_over'] . ' ' . numoney_to_strmoney( $val, $mod_file ) . '</span>';
				$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=search_result&price2=' . $val . $recata;
				$arr_price = array( 'title' => $title, 'link' => $link );
			}

			$xtpl->assign( 'ROW', $arr_price );
			$xtpl->parse( 'main.loopprice' );

			if( $val >= $block_config['price_end'] ) break;

			$val += $block_config['price_step'];
		}

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_view_product_price( $block_config );
}