<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2013 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 10 Jul 2013 09:15:32 GMT
 */

if( ! defined( 'NV_IS_MOD_MAKE_THEME' ) )
	die( 'Stop!!!' );

$page_title = $mod_title = $lang_module['page_title'];
$key_words = $module_info['keywords'];

$checkss = md5( session_id( ) . $global_config['sitekey'] );
$get_module_theme = $nv_Request->get_title( 'get_module_theme', 'get' );
if( ! empty( $get_module_theme ) )
{
	if( (preg_match( $global_config['check_theme'], $get_module_theme ) OR preg_match( $global_config['check_theme_mobile'], $get_module_theme )) AND $nv_Request->get_title( 'checkss', 'get' ) == $checkss )
	{
		$mod_list = nv_scandir( NV_ROOTDIR . '/modules/make-theme/themes/' . $get_module_theme . '/modules', $global_config['check_module'] );
		$html = '';
		foreach( $mod_list as $mod_theme )
		{
			$html .= '&nbsp;<label><input type="checkbox" value="' . $mod_theme . '" name="mod_theme[]">' . $mod_theme . '</label>';
		}
		die( $html );
	}
	die( 'Stop!!!' );
}

function nv_file_config( $data, $array_blocks )
{
	global $global_config;

	$position = "";
	foreach( $data['position_tag'] as $key => $tag )
	{
		$tag = str_replace( '-', '_', strtoupper( change_alias( $tag ) ) );
		$name_en = strip_punctuation( $data['position_name'][$key] );
		$name_vi = strip_punctuation( $data['position_name_vi'][$key] );
		if( ! empty( $tag ) AND ! empty( $name_en ) )
		{
			if( empty( $name_vi ) )
			{
				$name_vi = $name_en;
			}
			$position .= "\t\t<position>\n\t\t\t<tag>[" . $tag . "]</tag>\n\t\t\t<name>" . $name_en . "</name>\n\t\t\t<name_vi>" . $name_vi . "</name_vi>\n\t\t</position>\n";
		}
	}
	$res = "<?xml version='1.0'?>\n";
	$res .= "<theme>\n";
	$res .= "\t<info>\n";
	$res .= "\t\t<name>" . $data['info_name'] . "</name>\n";
	$res .= "\t\t<author>" . $data['info_author'] . "</author>\n";
	$res .= "\t\t<website>" . $data['info_website'] . "</website>\n";
	$res .= "\t\t<description>" . $data['info_description'] . "</description>\n";
	$res .= "\t\t<thumbnail>" . $data['theme'] . ".jpg</thumbnail>\n";
	$res .= "\t</info>\n";
	$res .= "\t<layoutdefault>body</layoutdefault>\n";
	$res .= "\n\t<positions>\n" . $position . "\t</positions>\n";
	$res .= "\n\t<setlayout>\n";
	$res .= "\t\t<layout>\n";
	$res .= "\t\t\t<name>body</name>\n";
	$res .= "\t\t\t<funcs>about:main</funcs>\n";
	$res .= "\t\t\t<funcs>rss:main</funcs>\n";
	$res .= "\t\t\t<funcs>statistics:main,allreferers,allcountries,allbrowsers,allos,allbots,referer</funcs>\n";
	$res .= "\t\t</layout>\n";
	$res .= "\t</setlayout>\n";

	if( ! empty( $array_blocks ) )
	{
		$res .= "\n\t<setblocks>\n";
		foreach( $array_blocks as $_arr_block )
		{
			$res .= "\t\t<block>\n";
			$res .= "\t\t\t<module>theme</module>\n";
			$res .= "\t\t\t<file_name>" . $_arr_block['file_name'] . "</file_name>\n";
			$res .= "\t\t\t<title>" . $_arr_block['title'] . "</title>\n";
			$res .= "\t\t\t<template>no_title</template>\n";
			$res .= "\t\t\t<position>" . $_arr_block['position'] . "</position>\n";
			$res .= "\t\t\t<all_func>1</all_func>\n";
			$res .= "\t\t\t<config></config>\n";
			$res .= "\t\t</block>\n\n";
		}
		$res .= "\t</setblocks>\n";
	}
	$res .= "</theme>";
	return $res;
}

function nv_file_layout_body( )
{
	$res = '<!-- BEGIN: main -->
{FILE "header.tpl"}
{MODULE_CONTENT}
{FILE "footer.tpl"}
<!-- END: main -->';
	return $res;
}

function nv_file_layout_html( $dir_theme )
{
	$array_blocks = array( );
	$html = file_get_contents( $dir_theme . '/index.html' );
	//Xóa bỏ jquery.min.js
	if( preg_match_all( "/<script[^>]+src\s*=([^>]+)>[\s\r\n\t]*<\/script>/is", $html, $m ) )
	{
		foreach( $m[1] as $key => $value )
		{
			if( strpos( $value, '/jquery.min.js' ) OR preg_match( '/\/jquery\-[0-9\.]+\.min\.js/', $value ) )
			{
				$value = str_replace( '"', '', $value );
				$value = str_replace( "'", '', $value );
				if( is_file( $dir_theme . '/' . $value ) )
				{
					unlink( $dir_theme . '/' . $value );
				}
				$html = str_replace( $m[0][$key], '', $html );
			}
		}
	}

	$html = str_replace( '="css/', '="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/', $html );
	$html = str_replace( '="/css/', '="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/', $html );
	$html = str_replace( '="images/', '="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/', $html );
	$html = str_replace( '="/images/', '="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/', $html );
	$html = str_replace( '="js/', '="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/', $html );
	$html = str_replace( '="/js/', '="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/', $html );
	$html = str_replace( '="uploads/', '="{NV_BASE_SITEURL}themes/{TEMPLATE}/uploads/', $html );
	$html = str_replace( '="/uploads/', '="{NV_BASE_SITEURL}themes/{TEMPLATE}/uploads/', $html );
	$html = preg_replace( '/<title>[^<]+<\/title>/', '', $html );
	if( preg_match_all( '/<meta([^>]+)>/', $html, $m ) )
	{
		foreach( $m[0] as $meta_i )
		{
			if( ! preg_match( '/name\s*=\s*"viewport"/', $meta_i ) )
			{
				$html = str_replace( $meta_i, '', $html );
			}
		}
	}

	$html = preg_replace( '/<head>/i', "<head>\n\t{THEME_PAGE_TITLE}\n\t{THEME_META_TAGS}\n", $html, 1 );
	$html = preg_replace( '/<\/head>/i', "\n\t{THEME_CSS}\n\t{THEME_SITE_RSS}\n\t{THEME_SITE_JS}\n</head>", $html, 1 );

	//Xóa các dòng trống có tab, hoặc có nhiều hơn 1 dòng trống
	$html = trim( preg_replace( '/\n([\t\n]+)\n/', "\n\n", $html ) );

	if( preg_match_all( '/<!--\sbegin\sblock\:([^\>]+)\s-->/', $html, $variable ) )
	{
		foreach( $variable[1] as $tag_i )
		{
			$a1 = strpos( $html, '<!-- begin block:' . $tag_i . ' -->' );
			$a2 = strpos( $html, '<!-- end block:' . $tag_i . ' -->' );
			if( $a1 AND $a2 > $a1 )
			{
				$html_block_i = substr( $html, $a1, $a2 + strlen( '<!-- end block:' . $tag_i . ' -->' ) - $a1 );
				$tag_i = str_replace( '-', '_', strtolower( change_alias( $tag_i ) ) );
				$html = str_replace( $html_block_i, '[' . strtoupper( $tag_i ) . ']', $html );

				$html_block_i = preg_replace( '/<!--\sbegin\sblock\:([^\>]+)\s-->/', "<!-- BEGIN: main -->\n", $html_block_i );
				$html_block_i = preg_replace( '/<!--\send\sblock\:([^\>]+)\s-->/', "\n<!-- END: main -->", $html_block_i );
				//Xóa các dòng trống có tab, hoặc có nhiều hơn 1 dòng trống
				$html_block_i = trim( preg_replace( '/\n([\t\n]+)\n/', "\n\n", $html_block_i ) );

				$php_block_i = "<?php" . "\n\n";
				$php_block_i .= NV_FILEHEAD . "\n\n";
				$php_block_i .= "if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );\n\n";

				$php_block_i .= "function nv4_block_" . $tag_i . "( \$block_config )\n";
				$php_block_i .= "{\n";
				$php_block_i .= "\tglobal \$global_config, \$db, \$site_mods, \$module_name;\n\n";

				$php_block_i .= "\t\$mod_name = 'news'; // or \$module_name;\n";
				$php_block_i .= "\t\$list = array();\n";
				$php_block_i .= "\tif( isset( \$site_mods[\$mod_name] ) )\n";
				$php_block_i .= "\t{\n";
				$php_block_i .= "\t\t\$mod_file = \$site_mods[\$mod_name]['module_file'];\n";
				$php_block_i .= "\t\t\$mod_data = \$site_mods[\$mod_name]['module_data'];\n";
				$php_block_i .= "\t}\n";

				$php_block_i .= "\t\$xtpl = new XTemplate( 'global." . $tag_i . ".tpl', NV_ROOTDIR . '/themes/' . \$global_config['module_theme'] . '/blocks' );\n";
				$php_block_i .= "\t\$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );\n";
				$php_block_i .= "\t\$xtpl->assign( 'TEMPLATE', \$global_config['module_theme'] );\n";
				$php_block_i .= "/*\n";
				$php_block_i .= "\tforeach( \$list as \$row )\n";
				$php_block_i .= "\t{\n";
				$php_block_i .= "\t\t\$xtpl->assign( 'ROW', \$row );\n";
				$php_block_i .= "\t\t\$xtpl->parse( 'main.loop' );\n";
				$php_block_i .= "\t}\n";
				$php_block_i .= "*/\n";
				$php_block_i .= "\t\$xtpl->parse( 'main' );\n";
				$php_block_i .= "\treturn \$xtpl->text( 'main' );\n";
				$php_block_i .= "}\n\n";

				$php_block_i .= "if( defined( 'NV_SYSTEM' ) )\n";
				$php_block_i .= "{\n";
				$php_block_i .= "\t\$content = nv4_block_" . $tag_i . "( \$block_config );\n";
				$php_block_i .= "}\n";
				$php_block_i .= "\n?>";

				file_put_contents( $dir_theme . '/blocks/global.' . $tag_i . '.php', $php_block_i );
				file_put_contents( $dir_theme . '/blocks/global.' . $tag_i . '.tpl', $html_block_i );

				$array_blocks[] = array(
					'file_name' => 'global.' . $tag_i . '.php',
					'title' => 'global ' . str_replace( '_', ' ', $tag_i ),
					'position' => '[' . strtoupper( $tag_i ) . ']'
				);
			}
		}
	}

	$a1 = strpos( $html, '<!-- begin nv_content -->' );
	$a2 = strpos( $html, '<!-- end nv_content -->' );
	$html_nv_content = substr( $html, $a1, $a2 + strlen( '<!-- end nv_content -->' ) - $a1 );
	$html = str_replace( $html_nv_content, '{MODULE_CONTENT}', $html );

	$a1 = strpos( $html, '<!-- begin nv_body -->' );
	$a2 = strpos( $html, '<!-- end nv_body -->' );
	$html_header = substr( $html, 0, $a1 );
	$html_footer = substr( $html, $a2 + strlen( '<!-- end nv_body -->' ) );
	$a1 = $a1 + strlen( '<!-- begin nv_body -->' );
	$html = substr( $html, $a1, $a2 - $a1 );

	file_put_contents( $dir_theme . '/layout/header.tpl', $html_header );
	file_put_contents( $dir_theme . '/layout/footer.tpl', $html_footer );
	$html = "<!-- BEGIN: main -->\n{FILE \"header.tpl\"}\n" . $html . "\n{FILE \"footer.tpl\"}\n<!-- END: main -->";

	//Xóa các dòng trống có tab, hoặc có nhiều hơn 1 dòng trống
	$html = trim( preg_replace( '/\n([\t\n]+)\n/', "\n\n", $html ) );

	file_put_contents( $dir_theme . '/layout/layout.body.tpl', $html );
	return $array_blocks;
}

$step = $nv_Request->get_int( 'step', 'get,post' );

if( $step == 2 )
{
	$theme = $nv_Request->get_title( 'theme_upload', 'session' );
	if( ! empty( $theme ) AND is_dir( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . md5( $global_config['sitekey'] . session_id( ) ) . '/' . $theme ) )
	{
		$data = array( 'theme' => $theme );
		$data['theme'] = change_alias( $nv_Request->get_title( 'theme', 'post', $theme ) );
		$data['info_name'] = $nv_Request->get_title( 'info_name', 'post', 'Theme ' . $theme, 1 );

		$data['info_author'] = $nv_Request->get_title( 'info_author', 'post', 'VinaDes.,Jsc', 1 );
		$data['info_website'] = $nv_Request->get_title( 'info_website', 'post', 'http://vinades.vn' );
		if( ! nv_is_url( $data['info_website'] ) )
		{
			$data['info_website'] = '';
		}
		$data['info_description'] = $nv_Request->get_title( 'info_description', 'post', 'Theme for NukeViet 4', 1 );
		$data['version'] = $nv_Request->get_title( 'version', 'post', '4.0' );
		$data['layout'] = $nv_Request->get_typed_array( 'layout', 'post', null, 'int' );
		$data['layoutdefault'] = $nv_Request->get_title( 'layoutdefault', 'post', 'body', 1 );

		$data['position_tag'] = $nv_Request->get_typed_array( 'position_tag', 'post', null, 'int' );
		$data['position_name'] = $nv_Request->get_typed_array( 'position_name', 'post', null, 'int' );
		$data['position_name_vi'] = $nv_Request->get_typed_array( 'position_name_vi', 'post', null, 'int' );
		$data['source_theme'] = $nv_Request->get_title( 'source_theme', 'post', 'responsive' );

		$data['mod_theme'] = $nv_Request->get_typed_array( 'mod_theme', 'post' );

		$source_theme = NV_ROOTDIR . '/modules/make-theme/themes/' . $data['source_theme'];
		if( $nv_Request->isset_request( 'submit', 'post' ) AND (preg_match( $global_config['check_theme'], $data['source_theme'] ) OR preg_match( $global_config['check_theme_mobile'], $data['source_theme'] )) AND is_dir( $source_theme ) )
		{
			$dest_theme = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . md5( $global_config['sitekey'] . session_id( ) ) . '/' . $theme;
			copy( $source_theme . '/favicon.ico', $dest_theme . '/favicon.ico' );
			copy( $source_theme . '/theme.php', $dest_theme . '/theme.php' );

			mkdir( $dest_theme . '/blocks' );
			file_put_contents( $dest_theme . '/blocks/index.html', '' );
			copy( $source_theme . '/blocks/global.counter.tpl', $dest_theme . '/blocks/global.counter.tpl' );
			copy( $source_theme . '/blocks/global.rss.tpl', $dest_theme . '/blocks/global.rss.tpl' );

			if( ! is_dir( $dest_theme . '/css' ) )
			{
				mkdir( $dest_theme . '/css' );
				file_put_contents( $dest_theme . '/css/index.html', '' );
			}
			$copy_file_css = array_map( 'trim', explode( ',', 'admin.css, icons.css, ie6.css, sitemap.xsl, sitemapindex.xsl, tab_info.css' ) );
			foreach( $copy_file_css as $file_i )
			{
				copy( $source_theme . '/css/' . $file_i, $dest_theme . '/css/' . $file_i );
			}

			if( ! is_dir( $dest_theme . '/images' ) )
			{
				mkdir( $dest_theme . '/images' );
				file_put_contents( $dest_theme . '/images/index.html', '' );
			}
			$copy_file_images = array_map( 'trim', explode( ',', 'admin, arrows, icons' ) );
			foreach( $copy_file_images as $dir_i )
			{
				mkdir( $dest_theme . '/images/' . $dir_i );
				$array_file_i = nv_scandir( $source_theme . '/images/' . $dir_i, "/([a-zA-Z0-9\.\-\_\\s\(\)]+)\.([a-zA-Z0-9]+)$/" );
				foreach( $array_file_i as $file_i )
				{
					copy( $source_theme . '/images/' . $dir_i . '/' . $file_i, $dest_theme . '/images/' . $dir_i . '/' . $file_i );
				}
			}

			mkdir( $dest_theme . '/layout' );
			file_put_contents( $dest_theme . '/layout/index.html', '' );
			file_put_contents( $dest_theme . '/layout/header.tpl', '' );
			file_put_contents( $dest_theme . '/layout/footer.tpl', '' );
			copy( $source_theme . '/layout/block.default.tpl', $dest_theme . '/layout/block.default.tpl' );
			copy( $source_theme . '/layout/block.no_title.tpl', $dest_theme . '/layout/block.no_title.tpl' );

			$array_blocks = nv_file_layout_html( $dest_theme );

			file_put_contents( $dest_theme . '/config.ini', nv_file_config( $data, $array_blocks ) );

			mkdir( $dest_theme . '/modules' );
			file_put_contents( $dest_theme . '/modules/index.html', '' );

			mkdir( $dest_theme . '/language' );
			file_put_contents( $dest_theme . '/language/index.html', '' );

			mkdir( $dest_theme . '/system' );
			file_put_contents( $dest_theme . '/system/index.html', '' );
			copy( $source_theme . '/system/admin_toolbar.tpl', $dest_theme . '/system/admin_toolbar.tpl' );
			copy( $source_theme . '/system/error_info.tpl', $dest_theme . '/system/error_info.tpl' );
			copy( $source_theme . '/system/flood_blocker.tpl', $dest_theme . '/system/flood_blocker.tpl' );
			copy( $source_theme . '/system/info_die.tpl', $dest_theme . '/system/info_die.tpl' );

			if( is_file( $dest_theme . '/view.jpg' ) )
			{
				$imageinfo = @getimagesize( $dest_theme . '/view.jpg' );
				if( $imageinfo[0] > 400 )
				{
					// Resize về kích thước 300x145
					require_once (NV_ROOTDIR . "/includes/class/image.class.php");
					$createImage = new image( $dest_theme . '/view.jpg' );
					$createImage->resizeXY( 300 );
					$createImage->cropFromLeft( 0, 0, 300, 145 );
					$createImage->save( $dest_theme, $data['theme'] . '.jpg', 100 );
					$createImage->close( );

					unlink( $dest_theme . '/view.jpg' );
				}
				else
				{
					rename( $dest_theme . '/view.jpg', $dest_theme . '/' . $data['theme'] . '.jpg' );
				}
			}
			else
			{
				// Tạo ảnh minh họa rỗng
				// Create a blank image and add some text
				$im = imagecreatetruecolor( 300, 145 );
				$bgc = imagecolorallocate( $im, 255, 255, 255 );
				imagefilledrectangle( $im, 0, 0, 300, 145, $bgc );
				$text_color = imagecolorallocate( $im, 0, 0, 0 );
				imagestring( $im, 1, 5, 5, 'Theme ' . $data['theme'], $text_color );
				// Save the image as 'simpletext.jpg'
				imagejpeg( $im, $dest_theme . '/' . $data['theme'] . '.jpg', 100 );
				// Free up memory
				imagedestroy( $im );
			}

			file_put_contents( $dest_theme . '/css/index.html', '' );
			file_put_contents( $dest_theme . '/fonts/index.html', '' );
			file_put_contents( $dest_theme . '/images/index.html', '' );
			file_put_contents( $dest_theme . '/js/index.html', '' );
			file_put_contents( $dest_theme . '/uploads/index.html', '' );
			file_put_contents( $dest_theme . '/index.html', '' );

			if( file_exists( $source_theme . '/css/font-awesome.min.css' ) AND file_exists( $source_theme . '/fonts/FontAwesome.otf' ) AND ! file_exists( $dest_theme . '/css/font-awesome.min.css' ) AND ! file_exists( $dest_theme . '/fonts/FontAwesome.otf' ) )
			{
				copy( $source_theme . '/css/font-awesome.css', $dest_theme . '/css/font-awesome.css' );
				copy( $source_theme . '/css/font-awesome.min.css', $dest_theme . '/css/font-awesome.min.css' );

				copy( $source_theme . '/fonts/FontAwesome.otf', $dest_theme . '/fonts/FontAwesome.otf' );
				copy( $source_theme . '/fonts/fontawesome-webfont.eot', $dest_theme . '/fonts/awesome-webfont.eot' );
				copy( $source_theme . '/fonts/fontawesome-webfont.svg', $dest_theme . '/fonts/fontawesome-webfont.svg' );
				copy( $source_theme . '/fonts/fontawesome-webfont.ttf', $dest_theme . '/fonts/fontawesome-webfont.ttf' );
				copy( $source_theme . '/fonts/fontawesome-webfont.woff', $dest_theme . '/fonts/fontawesome-webfont.woff' );

				$html_header = file_get_contents( $dest_theme . '/layout/header.tpl' );
				if( strpos( $html_header, 'css/font-awesome' ) === false )
				{
					$html_header = str_replace( '{THEME_CSS}', "<link href=\"{NV_BASE_SITEURL}themes/{TEMPLATE}/css/font-awesome.min.css\" rel=\"stylesheet\">\n\t{THEME_CSS}", $html_header );
					file_put_contents( $dest_theme . '/layout/header.tpl', $html_header );
				}
			}
			if( ! empty( $data['mod_theme'] ) )
			{
				foreach( $data['mod_theme'] as $modulename )
				{
					if( preg_match( $global_config['check_module'], $modulename ) )
					{
						if( is_dir( $source_theme . '/modules/' . $modulename ) )
						{
							mkdir( $dest_theme . '/modules/' . $modulename );
							$array_filename = scandir( $source_theme . '/modules/' . $modulename );
							foreach( $array_filename as $file )
							{
								if( $file == "." || $file == ".." )
								{
									continue;
								}
								copy( $source_theme . '/modules/' . $modulename . '/' . $file, $dest_theme . '/modules/' . $modulename . '/' . $file );
							}
						}

						if( is_dir( $source_theme . '/images/' . $modulename ) )
						{
							mkdir( $dest_theme . '/images/' . $modulename );
							$array_filename = scandir( $source_theme . '/images/' . $modulename );
							foreach( $array_filename as $file )
							{
								if( $file == "." || $file == ".." )
								{
									continue;
								}
								copy( $source_theme . '/images/' . $modulename . '/' . $file, $dest_theme . '/images/' . $modulename . '/' . $file );
							}
						}

						if( file_exists( $source_theme . '/css/' . $modulename . '.css' ) )
						{
							copy( $source_theme . '/css/' . $modulename . '.css', $dest_theme . '/css/' . $modulename . '.css' );
						}
					}
				}
			}

			if( $theme != $data['theme'] )
			{
				rename( $dest_theme, NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . md5( $global_config['sitekey'] . session_id( ) ) . '/' . $data['theme'] );
				$dest_theme = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . md5( $global_config['sitekey'] . session_id( ) ) . '/' . $data['theme'];
			}
			$file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'theme_' . $data['theme'] . '_' . md5( nv_genpass( 10 ) . session_id( ) ) . '.zip';
			require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';

			$zip = new PclZip( $file_src );
			$zip->create( $dest_theme, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . md5( $global_config['sitekey'] . session_id( ) ) );

			//Xoa file tam
			nv_deletefile( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . md5( $global_config['sitekey'] . session_id( ) ), 1 );

			//Download file
			require_once (NV_ROOTDIR . '/includes/class/download.class.php');
			$download = new download( $file_src, NV_ROOTDIR . "/" . NV_TEMP_DIR, 'nv' . intval( $data['version'] ) . '_theme_' . $data['theme'] . '.zip' );
			$download->download_file( );
			exit( );
		}
		else
		{
			$html = file_get_contents( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . md5( $global_config['sitekey'] . session_id( ) ) . '/' . $theme . '/index.html' );

			$xtpl = new XTemplate( "info.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
			$xtpl->assign( 'LANG', $lang_module );
			$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
			$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
			$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
			$xtpl->assign( 'MODULE_NAME', $module_name );
			$xtpl->assign( 'OP', $op );
			$xtpl->assign( 'DATA', $data );

			$theme_list = nv_scandir( NV_ROOTDIR . '/modules/make-theme/themes/', $global_config['check_theme'] );
			$theme_mobile_list = nv_scandir( NV_ROOTDIR . '/modules/make-theme/themes/', $global_config['check_theme_mobile'] );
			$theme_list = array_merge( $theme_list, $theme_mobile_list );

			if( ! empty( $theme_list ) )
			{
				foreach( $theme_list as $source_theme )
				{
					$xtpl->assign( 'SELECT_THEME', ($source_theme == $data['source_theme']) ? 'selected="selected"' : '' );
					$xtpl->assign( 'SOURCE_THEME', $source_theme );
					$xtpl->parse( 'main.source_theme' );
				}
				$selected_theme = (in_array( $data['source_theme'], $theme_list )) ? $data['source_theme'] : $theme_list[0];
				$mod_list = nv_scandir( NV_ROOTDIR . '/modules/make-theme/themes/' . $selected_theme . '/modules', $global_config['check_module'] );
				foreach( $mod_list as $mod_theme )
				{
					$xtpl->assign( 'MOD_THEME', $mod_theme );
					$xtpl->parse( 'main.mod_theme' );
				}
			}
			else
			{
				nv_info_die( $global_config['site_description'], $lang_global['site_info'], $lang_module['theme_no_exit'] );
			}

			$id = 0;
			if( preg_match_all( '/<!--\sbegin\sblock\:([^\>]+)\s-->/', $html, $variable ) )
			{
				foreach( $variable[1] as $tag_name )
				{
					$tag_i = str_replace( '-', '_', strtoupper( change_alias( $tag_name ) ) );
					$position = array(
						'id' => ++$id,
						'class' => ($id % 2 == 0) ? ' class="second"' : '',
						'tag' => $tag_i,
						'name' => $tag_name,
						'name_vi' => $tag_name
					);
					$xtpl->assign( 'POSITION', $position );
					$xtpl->parse( 'main.loop' );
				}
			}
			$xtpl->assign( 'ITEMS_POSITIONS', $id );
			$xtpl->assign( 'CHECKSS', $checkss );

			$xtpl->parse( 'main' );
			$contents = $xtpl->text( 'main' );
		}
	}
	else
	{
		$redirect = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
		$nv_Request->set_Session( 'theme_upload', $theme );
		nv_info_die( $global_config['site_description'], $lang_global['site_info'], $lang_module['upload_no_exit'] . " \n <meta http-equiv=\"refresh\" content=\"3;URL=" . $redirect . "\" />" );
	}
}
else
{
	if( $nv_Request->isset_request( 'submit', 'post' ) )
	{
		$redirect = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
		$http_equiv = 1;
		if( is_uploaded_file( $_FILES['zipfile']['tmp_name'] ) )
		{
			preg_match( "/^(.*)\.[a-zA-Z0-9]+$/", $_FILES['zipfile']['name'], $f );
			$theme = change_alias( $f[1] );

			$filename = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'theme_' . $theme . '-' . md5( $global_config['sitekey'] . session_id( ) ) . '.zip';
			if( move_uploaded_file( $_FILES['zipfile']['tmp_name'], $filename ) )
			{
				require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
				$zip = new PclZip( $filename );
				$status = $zip->properties( );

				if( $status['status'] == 'ok' )
				{
					$list = $zip->listContent( );
					$error = array( );
					$filen_index_html = 0;

					// Check xem có file php không
					foreach( $list as $_arf )
					{
						if( preg_match( "/^(.*)\.php$/i", $_arf['filename'], $f ) )
						{
							$error[] = $lang_module['upload_error_php_file'];
						}
						elseif( $_arf['filename'] == 'index.html' )
						{
							$filen_index_html = 1;
						}
					}
					if( empty( $filen_index_html ) )
					{
						$error[] = $lang_module['upload_error_index_html'];
					}
					if( empty( $error ) )
					{
						$temp_extract_dir = NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . md5( $global_config['sitekey'] . session_id( ) );
						if( NV_ROOTDIR . '/' . $temp_extract_dir )
						{
							nv_deletefile( NV_ROOTDIR . '/' . $temp_extract_dir, true );
						}
						$extract = $zip->extract( PCLZIP_OPT_PATH, NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $theme );
						$redirect = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&step=2";
					}
					else
					{
						$lang_module['upload_ok'] = implode( '<br />', $error );
						$http_equiv = 10;
					}
					nv_deletefile( $filename );

					$nv_Request->set_Session( 'theme_upload', $theme );
				}
				else
				{
					$lang_module['upload_ok'] = $lang_module['upload_error_zip_file'];
				}
				nv_info_die( $global_config['site_description'], $lang_global['site_info'], $lang_module['upload_ok'] . " \n <meta http-equiv=\"refresh\" content=\"" . $http_equiv . ";URL=" . $redirect . "\" />" );
			}
		}
		else
		{
			$sys_max_size = min( nv_converttoBytes( ini_get( 'upload_max_filesize' ) ), nv_converttoBytes( ini_get( 'post_max_size' ) ) );
			$sys_max_size = nv_convertfromBytes( $sys_max_size );

			nv_info_die( $global_config['site_description'], $lang_global['site_info'], sprintf( $lang_module['upload_no'], $sys_max_size ) . " \n <meta http-equiv=\"refresh\" content=\"10;URL=" . $redirect . "\" />" );
		}
	}

	$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
}

include (NV_ROOTDIR . "/includes/header.php");
echo nv_site_theme( $contents );
include (NV_ROOTDIR . "/includes/footer.php");
?>