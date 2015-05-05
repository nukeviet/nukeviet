<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 11, 2010 8:43:46 PM
 */

if( ! defined( 'NV_IS_MOD_PAGE' ) ) die( 'Stop!!!' );

/**
 * nv_page_main()
 *
 * @param mixed $row
 * @param mixed $ab_links
 * @return
 */
function nv_page_main( $row, $ab_links, $content_comment)
{
	global $module_file, $lang_module, $module_info, $meta_property, $my_head, $client_info, $page_config;

	if( ! defined( 'SHADOWBOX' ) )
	{
		$my_head .= "<link type=\"text/css\" rel=\"Stylesheet\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
		$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
		$my_head .= "<script type=\"text/javascript\">Shadowbox.init({ handleOversize: \"drag\" });</script>";
		define( 'SHADOWBOX', true );
	}

	$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'CONTENT', $row );

	if( $row['socialbutton'] )
	{
		if( ! defined( 'FACEBOOK_JSSDK' ) )
		{
			$xtpl->assign( 'FACEBOOK_LANG', ( NV_LANG_DATA == 'vi' ) ? 'vi_VN' : 'en_US' );
			if( ! empty( $page_config['facebookapi']  ) )
			{
				$xtpl->assign( 'FACEBOOK_APPID', $page_config['facebookapi'] );
				$meta_property['fb:app_id'] = $page_config['facebookapi'];
			}

			$xtpl->parse( 'main.facebookjssdk' );

			define( 'FACEBOOK_JSSDK', true );
		}

		if( defined( 'FACEBOOK_JSSDK' ) )
		{
			$xtpl->assign( 'SELFURL', $client_info['selfurl'] );

			$xtpl->parse( 'main.socialbutton.facebook' );
		}

		$xtpl->parse( 'main.socialbutton' );
	}

	if( ! empty( $row['image'] ) )
	{
		$xtpl->parse( 'main.image' );
	}

	if( ! empty( $ab_links ) )
	{
		foreach( $ab_links as $row )
		{
			$xtpl->assign( 'OTHER', $row );
			$xtpl->parse( 'main.other.loop' );
		}
		$xtpl->parse( 'main.other' );
	}

	if( !empty( $content_comment ) )
	{
		$xtpl->assign( 'CONTENT_COMMENT', $content_comment );
		$xtpl->parse( 'main.comment' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_page_main_list()
 *
 * @param mixed $array_data
 * @return
 */
function nv_page_main_list( $array_data, $generate_page )
{
	global $module_file, $lang_module, $module_info, $meta_property, $my_head, $client_info, $page_config, $module_name;

	$template = ( file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file .'/main_list.tpl' ) ) ? $module_info['template'] : 'default';

	$xtpl = new XTemplate( 'main_list.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );

	if( ! empty( $array_data ) )
	{
		foreach( $array_data as $data )
		{
			if( ! empty( $data['image'] ) )
			{
				$data['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $data['image'];
				$data['imagealt'] = ! empty( $data['imagealt'] ) ? $data['imagealt'] : $data['title'];
			}

			$xtpl->assign( 'DATA', $data );

			if( ! empty( $data['image'] ) )
			{
				$xtpl->parse( 'main.loop.image' );
			}

			$xtpl->parse( 'main.loop' );
		}
		if( $generate_page != '' )
		{
	        $xtpl->assign( 'GENERATE_PAGE', $generate_page );
	    }
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}