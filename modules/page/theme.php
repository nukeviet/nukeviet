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
function nv_page_main( $row, $ab_links )
{
	global $module_file, $lang_module, $module_info, $meta_property, $my_head, $client_info;

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
		if( ! defined( 'FACEBOOK_JSSDK' ) and $row['facebookappid'] )
		{
			$meta_property['fb:app_id'] = $row['facebookappid'];
			
			$xtpl->assign( 'FACEBOOK_LANG', ( NV_LANG_DATA == 'vi' ) ? 'vi_VN' : 'en_US' );
			$xtpl->assign( 'FACEBOOK_APPID', $row['facebookappid'] );
			
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

	if( defined( 'NV_COMM_URL' ) )
	{
		$xtpl->assign( 'NV_COMM_URL', NV_COMM_URL );
		$xtpl->parse( 'main.comment' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}