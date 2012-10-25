<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 9/9/2010, 6:38
 */

if( ! defined( 'NV_IS_FILE_WEBTOOLS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['nukevietChange_caption'];

/**
 * NukevietChange_getContents()
 *
 * @param bool $refresh
 * @return
 */
function NukevietChange_getContents( $refresh = false )
{
	global $global_config;
	$url = "http://code.google.com/feeds/p/nuke-viet/svnchanges/basic";
	$xmlfile = "nukevietGoogleCode.cache";
	$load = false;
	$p = NV_CURRENTTIME - 18000;
	$p2 = NV_CURRENTTIME - 120;
	if( ! file_exists( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $xmlfile ) ) $load = true;
	else
	{
		$filemtime = @filemtime( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $xmlfile );
		if( $filemtime < $p ) $load = true;
		elseif( $refresh and $filemtime < $p2 ) $load = true;
	}

	if( $load )
	{
		include ( NV_ROOTDIR . '/includes/class/geturl.class.php' );
		$UrlGetContents = new UrlGetContents( $global_config );
		$content = $UrlGetContents->get( $url );
		if( ! empty( $content ) )
		{
			if( nv_function_exists( 'mb_convert_encoding' ) ) $content = mb_convert_encoding( $content, "utf-8" );
			$content = simplexml_load_string( $content );
			$content = nv_object2array( $content );
			if( ! empty( $content ) )
			{
				$code = array();
				$code['updated'] = strtotime( $content['updated'] );
				$code['link'] = $content['link'][0]['@attributes']['href'];
				$code['entry'] = array();

				if( isset( $content['entry'] ) and ! empty( $content['entry'] ) )
				{
					foreach( $content['entry'] as $entry )
					{
						unset( $matches );
						$cont = $entry['content'];
						preg_match_all( "/(modify|add|delete)[^a-z0-9\/\.\-\_]+(\/trunk\/nukeviet\/)([a-z0-9\/\.\-\_]+)/mi", $cont, $matches, PREG_SET_ORDER );
						$cont = array();
						if( ! empty( $matches ) )
						{
							foreach( $matches as $matche )
							{
								$key = strtolower( $matche[1] );
								if( ! isset( $cont[$key] ) ) $cont[$key] = array();
								$cont[$key][] = $matche[3];
							}
						}

						unset( $matches2 );
						preg_match( "/Revision[\s]+([\d]*)[\s]*\:[\s]+(.*?)/Uis", $entry['title'], $matches2 );
						$code['entry'][] = array( //
							'updated' => strtotime( $entry['updated'] ), //
							'title' => $matches2[2], //
							'id' => $matches2[1], //
							'link' => $entry['link']['@attributes']['href'], //
							'author' => $entry['author']['name'], //
							'content' => $cont //
								);
					}

					nv_set_cache( $xmlfile, serialize( $code ) );
					return $code;
				}
			}
		}
	}

	$content = nv_get_cache( $xmlfile );
	if( ! $content ) return false;
	$content = unserialize( $content );

	return $content;
}

//Cap nhat thong tin tu du an NukeViet tren Google Code
if( $nv_Request->isset_request( 'gcode', 'get' ) and ( $gcode = $nv_Request->get_int( 'gcode', 'get', 0 ) ) )
{
	if( ! defined( 'NV_IS_SPADMIN' ) ) die();

	if( $gcode != 1 ) $changes = NukevietChange_getContents( true );
	else  $changes = NukevietChange_getContents();
	if( ! empty( $changes ) and ! empty( $changes['entry'] ) )
	{
		$xtpl = new XTemplate( "googlecode.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );

		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
		$xtpl->assign( 'MODULE_NAME', $module_name );
		$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );

		$xtpl->assign( 'UPDATED', $lang_module['nukevietChange_upd'] . nv_date( " d-m-Y H:i", $changes['updated'] ) );
		$xtpl->assign( 'REFRESH', $lang_module['nukevietChange_refresh'] );
		$xtpl->assign( 'VISIT', $changes['link'] );

		foreach( $changes['entry'] as $key => $entry )
		{
			//if ( $key == 10 ) break;
			$entry['tooltip'] = array();
			foreach( $entry['content'] as $k => $v )
			{
				$entry['tooltip'][] = "<strong>" . $lang_module['nukevietChange_' . $k] . "</strong>: " . implode( ", ", $v );
			}
			$entry['tooltip'] = ! empty( $entry['tooltip'] ) ? "<ul><li>" . implode( "</li><li>", $entry['tooltip'] ) . "</li></ul>" : "";
			$entry['updated'] = nv_date( "d-m-Y H:i", $entry['updated'] );
			$xtpl->assign( 'CLASS', ( $key % 2 ) ? " class=\"second\"" : "" );
			$xtpl->assign( 'ENTRY', $entry );
			$xtpl->parse( 'NukevietChange.loop' );
		}
		$xtpl->parse( 'NukevietChange' );
		$contents = $xtpl->text( 'NukevietChange' );

		include ( NV_ROOTDIR . "/includes/header.php" );
		echo $contents;
		include ( NV_ROOTDIR . "/includes/footer.php" );
	}
	die();
}

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>