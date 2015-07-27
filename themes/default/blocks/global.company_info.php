<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jan 17, 2011 11:34:27 AM
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_company_info' ) )
{
	function nv_company_info_config()
	{
		global $lang_global, $data_block;

		$html = '<tr>';
		$html .= '<td>' . $lang_global['company_name'] . '</td>';
		$html .= '<td><input type="text" class="form-control" name="company_name" value="' . $data_block['company_name'] . '"></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td>' . $lang_global['company_sortname'] . '</td>';
		$html .= '<td><input type="text" class="form-control" name="company_sortname" value="' . $data_block['company_sortname'] . '"></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<tr>';
		$html .= '<td>' . $lang_global['company_regcode'] . '</td>';
		$html .= '<td><input type="text" class="form-control" name="company_regcode" value="' . $data_block['company_regcode'] . '"></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td>' . $lang_global['company_regplace'] . '</td>';
		$html .= '<td><input type="text" class="form-control" name="company_regplace" value="' . $data_block['company_regplace'] . '"></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td>' . $lang_global['company_licensenumber'] . '</td>';
		$html .= '<td><input type="text" class="form-control" name="company_licensenumber" value="' . $data_block['company_licensenumber'] . '"></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td>' . $lang_global['company_responsibility'] . '</td>';
		$html .= '<td><input type="text" class="form-control" name="company_responsibility" value="' . $data_block['company_responsibility'] . '"></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td>' . $lang_global['company_address'] . '</td>';
		$html .= '<td><input type="text" class="form-control" name="company_address" value="' . $data_block['company_address'] . '"></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td>' . $lang_global['company_phone'] . '</td>';
		$html .= '<td><input type="text" class="form-control" name="company_phone" value="' . $data_block['company_phone'] . '"></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td>' . $lang_global['company_fax'] . '</td>';
		$html .= '<td><input type="text" class="form-control" name="company_fax" value="' . $data_block['company_fax'] . '"></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td>' . $lang_global['company_email'] . '</td>';
		$html .= '<td><input type="text" class="form-control" name="company_email" value="' . $data_block['company_email'] . '"></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td>' . $lang_global['company_website'] . '</td>';
		$html .= '<td><input type="text" class="form-control" name="company_website" value="' . $data_block['company_website'] . '"></td>';
		$html .= '</tr>';

		return $html;
	}

	function nv_company_info_submit()
	{
		global $nv_Request;

		$return = array();
		$return['error'] = array();
		$return['config']['company_name'] = $nv_Request->get_title( 'company_name', 'post' );
		$return['config']['company_sortname'] = $nv_Request->get_title( 'company_sortname', 'post' );
		$return['config']['company_regcode'] = $nv_Request->get_title( 'company_regcode', 'post' );
		$return['config']['company_regplace'] = $nv_Request->get_title( 'company_regplace', 'post' );
		$return['config']['company_licensenumber'] = $nv_Request->get_title( 'company_licensenumber', 'post' );
		$return['config']['company_responsibility'] = $nv_Request->get_title( 'company_responsibility', 'post' );
		$return['config']['company_address'] = $nv_Request->get_title( 'company_address', 'post' );
		$return['config']['company_phone'] = $nv_Request->get_title( 'company_phone', 'post' );
		$return['config']['company_fax'] = $nv_Request->get_title( 'company_fax', 'post' );
		$return['config']['company_email'] = $nv_Request->get_title( 'company_email', 'post' );
		$return['config']['company_website'] = $nv_Request->get_title( 'company_website', 'post' );
		return $return;
	}

	/**
	 * nv_menu_theme_default_footer()
	 *
	 * @param mixed $block_config
	 * @return
	 */
	function nv_company_info( $block_config )
	{
		global $global_config, $lang_global, $my_head;

		if ( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.company_info.tpl' ) )
		{
			$block_theme = $global_config['module_theme'];
		}
		elseif ( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.company_info.tpl' ) )
		{
			$block_theme = $global_config['site_theme'];
		}
		else
		{
			$block_theme = 'default';
		}

		$xtpl = new XTemplate( 'global.company_info.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks' );
		$xtpl->assign( 'LANG', $lang_global );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'DATA', $block_config );

		if ( ! empty( $block_config['company_name'] ) )
		{
			if ( ! empty( $block_config['company_sortname'] ) )
			{
				$xtpl->parse( 'main.company_name.company_sortname' );
			}
			$xtpl->parse( 'main.company_name' );
		}

		$key = array();
		$i = 0;
		if ( ! empty( $block_config['company_regcode'] ) )
		{
			$key[$i] = $lang_global['company_regcode2'] . ": " . $block_config['company_regcode'];
			if ( ! empty( $block_config['company_regplace'] ) )
			{
				$key[$i] .= ", " . $lang_global['company_regplace'] . " " . $block_config['company_regplace'];
			}
			++$i;
		}
		if ( ! empty( $block_config['company_licensenumber'] ) )
		{
			$key[$i] = $lang_global['company_licensenumber'] . ": " . $block_config['company_licensenumber'];
			++$i;
		}

		if ( $i )
		{
			$key = implode( ".<br>", $key );
			$xtpl->assign( 'LICENSE', $key );
			$xtpl->parse( 'main.company_regcode' );
		}

		if ( ! empty( $block_config['company_responsibility'] ) )
		{
			$xtpl->parse( 'main.company_responsibility' );
		}

		if ( ! empty( $block_config['company_address'] ) )
		{
			$my_head .= "<script type=\"text/javascript\" src=\"http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false\"></script>" . NV_EOL;
			$xtpl->parse( 'main.company_address' );
			$xtpl->parse( 'main.company_address1' );
		}
		if ( ! empty( $block_config['company_phone'] ) )
		{
			$xtpl->parse( 'main.company_phone' );
		}
		if ( ! empty( $block_config['company_fax'] ) )
		{
			$xtpl->parse( 'main.company_fax' );
		}
		if ( ! empty( $block_config['company_email'] ) )
		{
			$xtpl->parse( 'main.company_email' );
		}
		if ( ! empty( $block_config['company_website'] ) )
		{
			$xtpl->parse( 'main.company_website' );
		}
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if ( defined( 'NV_SYSTEM' ) )
{
	$content = nv_company_info( $block_config );
}
