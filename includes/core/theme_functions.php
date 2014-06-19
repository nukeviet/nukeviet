<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 4/13/2010 20:00
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * nv_error_info()
 *
 * @return
 */
function nv_error_info()
{
	global $lang_global, $global_config, $error_info;

	if( ! defined( 'NV_IS_ADMIN' ) ) return;
	if( empty( $error_info ) ) return;

	$errortype = array(
		E_ERROR => array( $lang_global['error_error'], 'bad.png' ),
		E_WARNING => array( $lang_global['error_warning'], 'warning.png' ),
		E_PARSE => array( $lang_global['error_error'], 'bad.png' ),
		E_NOTICE => array( $lang_global['error_notice'], 'comment.png' ),
		E_CORE_ERROR => array( $lang_global['error_error'], 'bad.png' ),
		E_CORE_WARNING => array( $lang_global['error_warning'], 'warning.png' ),
		E_COMPILE_ERROR => array( $lang_global['error_error'], 'bad.png' ),
		E_COMPILE_WARNING => array( $lang_global['error_warning'], 'warning.png' ),
		E_USER_ERROR => array( $lang_global['error_error'], 'bad.png' ),
		E_USER_WARNING => array( $lang_global['error_warning'], 'warning.png' ),
		E_USER_NOTICE => array( $lang_global['error_notice'], 'comment.png' ),
		E_STRICT => array( $lang_global['error_notice'], 'comment.png' ),
		E_RECOVERABLE_ERROR => array( $lang_global['error_error'], 'bad.png' ),
		E_DEPRECATED => array( $lang_global['error_notice'], 'comment.png' ),
		E_USER_DEPRECATED => array( $lang_global['error_warning'], 'warning.png' )
	);

	if( defined( 'NV_ADMIN' ) and file_exists( NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system/error_info.tpl' ) )
	{
		$tpl_path = NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system';
		$image_path = NV_BASE_SITEURL . 'themes/' . $global_config['admin_theme'] . '/images/icons/';
	}
	elseif( defined( 'NV_ADMIN' ) )
	{
		$tpl_path = NV_ROOTDIR . '/themes/admin_default/system';
		$image_path = NV_BASE_SITEURL . 'themes/admin_default/images/';
	}
	elseif( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/system/error_info.tpl' ) )
	{
		$tpl_path = NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/system';
		$image_path = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/icons/';
	}
	else
	{
		$tpl_path = NV_ROOTDIR . '/themes/default/system';
		$image_path = NV_BASE_SITEURL . 'themes/default/images/icons/';
	}

	$xtpl = new XTemplate( 'error_info.tpl', $tpl_path );
	$xtpl->assign( 'TPL_E_CAPTION', $lang_global['error_info_caption'] );

	$a = 0;
	foreach( $error_info as $key => $value )
	{
		$xtpl->assign( 'TPL_E_CLASS', ( $a % 2 ) ? ' class="second"' : '' );
		$xtpl->assign( 'TPL_E_ALT', $errortype[$value['errno']][0] );
		$xtpl->assign( 'TPL_E_SRC', $image_path . $errortype[$value['errno']][1] );
		$xtpl->assign( 'TPL_E_ERRNO', $errortype[$value['errno']][0] );
		$xtpl->assign( 'TPL_E_MESS', $value['info'] );
		$xtpl->set_autoreset();
		$xtpl->parse( 'error_info.error_item' );
		++$a;
	}

	$xtpl->parse( 'error_info' );
	return $xtpl->text( 'error_info' );
}

/**
 * nv_info_die()
 *
 * @param string $page_title
 * @param mixed $info_title
 * @param mixed $info_content
 * @param string $admin_link
 * @param string $admin_title
 * @param string $site_link
 * @param string $site_title
 * @return
 */
function nv_info_die( $page_title = '', $info_title, $info_content, $admin_link = NV_BASE_ADMINURL, $admin_title = '', $site_link = NV_BASE_SITEURL, $site_title = '')
{
	global $lang_global, $global_config;

	if( empty( $page_title ) ) $page_title = $global_config['site_description'];

	// Get theme
	$template = '';

	if( defined( 'NV_ADMIN' ) and isset( $global_config['admin_theme'] ) and file_exists( NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system/info_die.tpl' ) )
	{
		$tpl_path = NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system';
		$template = $global_config['admin_theme'];
	}
	elseif( defined( 'NV_ADMIN' ) and file_exists( NV_ROOTDIR . '/themes/admin_default/system/info_die.tpl' ) )
	{
		$tpl_path = NV_ROOTDIR . '/themes/admin_default/system';
		$template = 'admin_default';
	}
	elseif( isset( $global_config['module_theme'] ) and file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/system/info_die.tpl' ) )
	{
		$tpl_path = NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/system';
		$template = $global_config['module_theme'];
	}
	elseif( isset( $global_config['site_theme'] ) and file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/system/info_die.tpl' ) )
	{
		$tpl_path = NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/system';
		$template = $global_config['site_theme'];
	}
	else
	{
		$tpl_path = NV_ROOTDIR . '/themes/default/system';
		$template = 'default';
	}

	$size = @getimagesize( NV_ROOTDIR . '/' . $global_config['site_logo'] );

	$xtpl = new XTemplate( 'info_die.tpl', $tpl_path );
	$xtpl->assign( 'SITE_CHERSET', $global_config['site_charset'] );
	$xtpl->assign( 'PAGE_TITLE', $page_title );
	$xtpl->assign( 'HOME_LINK', $global_config['site_url'] );
	$xtpl->assign( 'LANG', $lang_global );
	$xtpl->assign( 'TEMPLATE', $template );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'SITE_NAME', $global_config['site_name'] );

	if( isset( $size[1] ) )
	{
		if( $size[0] > 490 )
		{
			$size[1] = ceil( 490 * $size[1] / $size[0] );
			$size[0] = 490;
		}
		$xtpl->assign( 'LOGO', NV_BASE_SITEURL . $global_config['site_logo'] );
		$xtpl->assign( 'WIDTH', $size[0] );
		$xtpl->assign( 'HEIGHT', $size[1] );
		if( isset( $size['mime'] ) and $size['mime'] == 'application/x-shockwave-flash' )
		{
			$xtpl->parse( 'main.swf' );
		}
		else
		{
			$xtpl->parse( 'main.image' );
		}
	}
	$xtpl->assign( 'INFO_TITLE', $info_title );
	$xtpl->assign( 'INFO_CONTENT', $info_content );

	if( defined( 'NV_IS_ADMIN' ) and ! empty( $admin_link ) )
	{
		$xtpl->assign( 'ADMIN_LINK', $admin_link );
		$xtpl->assign( 'GO_ADMINPAGE', empty( $admin_title ) ? $lang_global['admin_page'] : $admin_title );
		$xtpl->parse( 'main.adminlink' );
	}
	if( ! empty( $site_link ) )
	{
		$xtpl->assign( 'SITE_LINK', $site_link );
		$xtpl->assign( 'GO_SITEPAGE', empty( $site_title ) ? $lang_global['go_homepage'] : $site_title );
		$xtpl->parse( 'main.sitelink' );
	}

	$xtpl->parse( 'main' );

	$global_config['mudim_active'] = 0;

	include NV_ROOTDIR . '/includes/header.php';
	$xtpl->out( 'main' );
	include NV_ROOTDIR . '/includes/footer.php';
	die();
}

/**
 * nv_xmlOutput()
 *
 * @param string $content
 * @param mixed $lastModified
 * @return void
 */
function nv_xmlOutput( $content, $lastModified )
{
	if( class_exists( 'tidy' ) )
	{
		$tidy_options = array(
			'input-xml' => true,
			'output-xml' => true,
			'indent' => true,
			'indent-cdata' => true,
			'wrap' => false
		);
		$tidy = new tidy();
		$tidy->parseString( $content, $tidy_options, 'utf8' );
		$tidy->cleanRepair();
		$content = ( string )$tidy;
	}
	else
	{
		$content = trim( $content );
	}

	@Header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $lastModified ) . ' GMT' );
	@Header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', $lastModified ) . ' GMT' );
	@Header( 'Content-Type: text/xml; charset=utf-8' );

	if( ! empty( $_SERVER['SERVER_SOFTWARE'] ) and strstr( $_SERVER['SERVER_SOFTWARE'], 'Apache/2' ) )
	{
		@Header( 'Cache-Control: no-cache, pre-check=0, post-check=0' );
	}
	else
	{
		@Header( 'Cache-Control: private, pre-check=0, post-check=0, max-age=0' );
	}

	@Header( 'Pragma: no-cache' );

	$encoding = 'none';

	if( nv_function_exists( 'gzencode' ) and isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) )
	{
		$encoding = strstr( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip' ) ? 'gzip' : ( strstr( $_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate' ) ? 'deflate' : 'none' );

		if( $encoding != 'none' )
		{
			if( ! strstr( $_SERVER['HTTP_USER_AGENT'], 'Opera' ) && preg_match( '/^Mozilla\/4\.0 \(compatible; MSIE ([0-9]\.[0-9])/i', $_SERVER['HTTP_USER_AGENT'], $matches ) )
			{
				$version = floatval( $matches[1] );

				if( $version < 6 || ( $version == 6 && ! strstr( $_SERVER['HTTP_USER_AGENT'], 'EV1' ) ) ) $encoding = 'none';
			}
		}
	}

	if( $encoding != 'none' )
	{
		$content = gzencode( $content, 6, $encoding == 'gzip' ? FORCE_GZIP : FORCE_DEFLATE );
		header( 'Content-Encoding: ' . $encoding );
		header( 'Vary: Accept-Encoding' );
	}

	print_r( $content );
	die();
}

/**
 * nv_rss_generate()
 *
 * @param mixed $channel
 * @param mixed $items
 * @return void
 */
function nv_rss_generate( $channel, $items )
{
	global $db, $global_config, $client_info;

	$xtpl = new XTemplate( 'rss.tpl', NV_ROOTDIR . '/themes/default/layout/' );
	$xtpl->assign( 'CSSPATH', NV_BASE_SITEURL . 'themes/default/css/rss.xsl' );
	//Chi co tac dung voi IE6 va Chrome

	$channel['title'] = nv_htmlspecialchars( $channel['title'] );
	$channel['atomlink'] = str_replace( '&', '&amp;', $client_info['selfurl'] );
	$channel['lang'] = $global_config['site_lang'];
	$channel['copyright'] = $global_config['site_name'];
	$channel['docs'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=rss', true );
	$channel['generator'] = 'Nukeviet Version 4';

	if( preg_match( '/^' . nv_preg_quote( NV_MY_DOMAIN . NV_BASE_SITEURL ) . '(.+)$/', $channel['link'], $matches ) )
	{
		$channel['link'] = $matches[1];
	}
	elseif( preg_match( '/^' . nv_preg_quote( NV_BASE_SITEURL ) . '(.+)$/', $channel['link'], $matches ) )
	{
		$channel['link'] = $matches[1];
	}
	$channel['link'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . $channel['link'], true );

	if( preg_match( '/^' . nv_preg_quote( NV_MY_DOMAIN . NV_BASE_SITEURL ) . '(.+)$/', $channel['atomlink'], $matches ) )
	{
		$channel['atomlink'] = $matches[1];
	}
	elseif( preg_match( '/^' . nv_preg_quote( NV_BASE_SITEURL ) . '(.+)$/', $channel['atomlink'], $matches ) )
	{
		$channel['atomlink'] = $matches[1];
	}

	$channel['atomlink'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . $channel['atomlink'], true );
	$channel['pubDate'] = 0;

	if( ! empty( $items ) )
	{
		foreach( $items as $item )
		{
			if( ! empty( $item['title'] ) and ! empty( $item['link'] ) )
			{
				$item['title'] = nv_htmlspecialchars( $item['title'] );

				if( isset( $item['pubdate'] ) and ! empty( $item['pubdate'] ) )
				{
					$item['pubdate'] = intval( $item['pubdate'] );
					$channel['pubDate'] = max( $channel['pubDate'], $item['pubdate'] );
					$item['pubdate'] = gmdate( 'D, j M Y H:m:s', $item['pubdate'] ) . ' GMT';
				}

				if( preg_match( '/^' . nv_preg_quote( NV_MY_DOMAIN . NV_BASE_SITEURL ) . '(.+)$/', $item['link'], $matches ) )
				{
					$item['link'] = $matches[1];
				}
				elseif( preg_match( '/^' . nv_preg_quote( NV_BASE_SITEURL ) . '(.+)$/', $item['link'], $matches ) )
				{
					$item['link'] = $matches[1];
				}
				$item['link'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . $item['link'], true );

				$xtpl->assign( 'ITEM', $item );

				if( isset( $item['guid'] ) and ! empty( $item['guid'] ) )
				{
					$xtpl->parse( 'main.item.guid' );
				}

				if( isset( $item['pubdate'] ) and ! empty( $item['pubdate'] ) )
				{
					$xtpl->parse( 'main.item.pubdate' );
				}

				$xtpl->parse( 'main.item' );
			}
		}
	}

	$lastModified = NV_CURRENTTIME;

	if( ! empty( $channel['pubDate'] ) )
	{
		$lastModified = $channel['pubDate'];
		$channel['pubDate'] = gmdate( 'D, j M Y H:m:s', $channel['pubDate'] ) . ' GMT';
	}

	$xtpl->assign( 'CHANNEL', $channel );

	if( ! empty( $channel['description'] ) )
	{
		$xtpl->parse( 'main.description' );
	}

	if( ! empty( $channel['pubDate'] ) )
	{
		$xtpl->parse( 'main.pubDate' );
	}

	$image = file_exists( NV_ROOTDIR . '/' . $global_config['site_logo'] ) ? NV_ROOTDIR . '/' . $global_config['site_logo'] : NV_ROOTDIR . '/images/logo.png';
	$image = nv_ImageInfo( $image, 144, true, NV_UPLOADS_REAL_DIR );

	if( ! empty( $image ) )
	{
		$resSize = nv_imageResize( $image['width'], $image['height'], 144, 400 );
		$image['width'] = $resSize['width'];
		$image['height'] = $resSize['height'];
		$image['title'] = $channel['title'];
		$image['link'] = $channel['link'];
		$image['src'] = NV_MY_DOMAIN . nv_url_rewrite( $image['src'], true );

		$xtpl->assign( 'IMAGE', $image );
		$xtpl->parse( 'main.image' );
	}

	$xtpl->parse( 'main' );
	$content = $xtpl->text( 'main' );

	nv_xmlOutput( $content, $lastModified );
}

/**
 * nv_xmlSitemap_generate()
 *
 * @param mixed $url
 * @return void
 */
function nv_xmlSitemap_generate( $url )
{
	global $global_config;

	if( file_exists( NV_ROOTDIR . 'themes/' . $global_config['site_theme'] . '/css/sitemap.xsl' ) )
	{
		$path_css_sitemap = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/css/sitemap.xsl';
	}
	else
	{
		$path_css_sitemap = NV_BASE_SITEURL . 'themes/default/css/sitemap.xsl';
	}

	$sitemapHeader = '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="' . $path_css_sitemap . '"?><urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>';
	$xml = new SimpleXMLElement( $sitemapHeader );

	$lastModified = time() - 86400;

	if( ! empty( $url ) )
	{
		foreach( $url as $key => $values )
		{
			$publdate = date( 'c', $values['publtime'] );

			$row = $xml->addChild( 'url' );
			$row->addChild( 'loc', "'" . nv_url_rewrite( $values['link'], true ) . "'" );
			$row->addChild( 'lastmod', $publdate );
			$row->addChild( 'changefreq', 'daily' );
			$row->addChild( 'priority', '0.8' );

			if( $key == 0 ) $lastModified = $values['publtime'];
		}
	}

	$contents = $xml->asXML();
	$contents = nv_url_rewrite( $contents );
	$contents = preg_replace( "/(<loc>)\'(.*?)\'(<\/loc>)/", "\\1" . NV_MY_DOMAIN . "\\2\\3", $contents );

	nv_xmlOutput( $contents, $lastModified );
}

/**
 * nv_xmlSitemapIndex_generate()
 *
 * @return void
 */
function nv_xmlSitemapIndex_generate()
{
	global $db_config, $db, $global_config, $nv_Request, $sys_info;

	if( file_exists( NV_ROOTDIR . 'themes/' . $global_config['site_theme'] . '/css/sitemapindex.xsl' ) )
	{
		$path_css_sitemap = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/css/sitemapindex.xsl';
	}
	else
	{
		$path_css_sitemap = NV_BASE_SITEURL . 'themes/default/css/sitemapindex.xsl';
	}

	$sitemapHeader = '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="' . $path_css_sitemap . '"?><sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>';
	$xml = new SimpleXMLElement( $sitemapHeader );

	$lastModified = NV_CURRENTTIME - 86400;

	if( $global_config['lang_multi'] )
	{
		foreach( $global_config['allow_sitelangs'] as $lang )
		{
			$sql = "SELECT m.title FROM " . $db_config['prefix'] . '_' . $lang . "_modules m LEFT JOIN " . $db_config['prefix'] . '_' . $lang . "_modfuncs f ON m.title=f.in_module WHERE m.act = 1 AND m.groups_view='6' AND f.func_name = 'Sitemap' ORDER BY m.weight, f.subweight";
			$result = $db->query( $sql );
			while( list( $modname ) = $result->fetch( 3 ) )
			{
				$link = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=Sitemap';
				$row = $xml->addChild( 'sitemap' );
				$row->addChild( 'loc', $link );
			}
		}
	}
	else
	{
		$site_mods = nv_site_mods();

		foreach( $site_mods as $modname => $values )
		{
			if( isset( $values['funcs'] ) and isset( $values['funcs']['Sitemap'] ) )
			{
				$link = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=Sitemap';
				$row = $xml->addChild( 'sitemap' );
				$row->addChild( 'loc', $link );
			}
		}
	}
	$db = null;

	$contents = $xml->asXML();

	if( $global_config['check_rewrite_file'] )
	{
		$contents = preg_replace( "/index\.php\?" . NV_LANG_VARIABLE . "\=([a-z]{2})\&[amp\;]*" . NV_NAME_VARIABLE . "\=SitemapIndex/", "Sitemap-\\1.xml", $contents );
		$contents = preg_replace( "/index\.php\?" . NV_LANG_VARIABLE . "\=([a-z]{2})\&[amp\;]*" . NV_NAME_VARIABLE . "\=([a-zA-Z0-9\-]+)\&[amp\;]*" . NV_OP_VARIABLE . "\=Sitemap/", "Sitemap-\\1.\\2.xml", $contents );
	}
	elseif( $global_config['rewrite_optional'] )
	{
		$contents = preg_replace( "/index\.php\?" . NV_LANG_VARIABLE . "\=([a-z]{2})\&[amp\;]*" . NV_NAME_VARIABLE . "\=([a-zA-Z0-9\-]+)\&[amp\;]*" . NV_OP_VARIABLE . "\=Sitemap/", "index.php/\\2/Sitemap" . $global_config['rewrite_endurl'], $contents );
	}
	else
	{
		$contents = preg_replace( "/index\.php\?" . NV_LANG_VARIABLE . "\=([a-z]{2})\&[amp\;]*" . NV_NAME_VARIABLE . "\=([a-zA-Z0-9\-]+)\&[amp\;]*" . NV_OP_VARIABLE . "\=Sitemap/", "index.php/\\1/\\2/Sitemap" . $global_config['rewrite_endurl'], $contents );
	}

	nv_xmlOutput( $contents, $lastModified );
}

/**
 * nv_css_setproperties()
 *
 * @param mixed $tag
 * @param mixed $property_array
 * @return
 */
function nv_css_setproperties( $tag, $property_array )
{
    $css = $line = '';
    if( empty( $tag ) ) return '';

    if( is_array( $property_array ) )
    {
        foreach( $property_array as $property => $value )
        {
            if( $property != 'customcss' )
            {
                if( ! empty( $property ) and ! empty( $value ) )
                {
                    $property = str_replace( '_', '-', $property );
                    if( $property == 'background-image' ) $value = "url('" . $value . "')";
                    $css .= $property . ':' . $value . ';';
                }
            }
            elseif( ! empty( $value ) )
            {
                $value = substr(trim($value), -1) == ';' ? $value : $value . ';';
                $css .= $value;
            }
        }
        $line .= $css == '' ? '' : $tag . '{' . $css . '}';
    }
    else
    {
        $css .= $property_array;
        $line .= $css == '' ? '' : $css;
    }

    return $line;
}