<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 25/12/2010, 1:5
 */

if( ! defined( 'NV_IS_FILE_SEOTOOLS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['keywordRank'];
$contents = '';

$xtpl = new XTemplate( 'keywordRank.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'TITLE', sprintf( $lang_module['keywordFormTitle'], NV_SERVER_NAME ) );

if( $nv_Request->isset_request( 'i', 'get' ) )
{
	$i = $nv_Request->get_string( 'i', 'get' );

	if( $i == 'process' )
	{
		$keyword = nv_substr( $nv_Request->get_title( 'k', 'get', '', 0 ), 0, 60 );
		$keyword = nv_unhtmlspecialchars( $keyword );
		$keyword = strip_punctuation( $keyword );
		$keyword = trim( $keyword );
		$len_key = nv_strlen( $keyword );
		//$keyword = nv_htmlspecialchars( $keyword );
		$lang = $nv_Request->get_title( 'l', 'get', '' );
		$accuracy = $nv_Request->get_title( 'a', 'get', '' );

		if( $len_key < 3 or $len_key > 60 )
		{
			$xtpl->assign( 'ERROR', $lang_module['keywordInfo'] );
			$xtpl->parse( 'process.error' );
		}
		else
		{
			$myDomain = NV_SERVER_NAME;
			// $myDomain = 'nukeviet.vn';
			$from = 'google';

			$tempFile = md5( $keyword . $lang . $accuracy . $from . $myDomain );
			$tempFile = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . $tempFile;

			if( file_exists( $tempFile ) and @filemtime( $tempFile ) > ( NV_CURRENTTIME - 600 ) )
			{
				$info = file_get_contents( $tempFile );
				$info = unserialize( $info );
			}
			else
			{
				if( ! isset( $keywordRank ) or ! is_object( $keywordRank ) )
				{
					if( ! class_exists( 'keywordRank' ) )
					{
						include NV_ROOTDIR . '/includes/class/keywordRank.class.php' ;
					}

					$keywordRank = new keywordRank();
				}

				$info = $keywordRank->process( $keyword, $lang, $accuracy, $from, $myDomain );

				$put_contents = serialize( $info );
				file_put_contents( $tempFile, $put_contents );
			}

			if( ! empty( $info ) )
			{
				$loop = array();
				$loop[] = array( 'key' => $lang_module['currentDomain'], 'value' => $info['myDomain'] );
				$loop[] = array( 'key' => $lang_module['keyword'], 'value' => $info['keyword'] );
				$loop[] = array( 'key' => $lang_module['language'], 'value' => ! empty( $info['lang'] ) ? strtoupper( $info['lang'] ) : $lang_module['langAll'] );
				$loop[] = array( 'key' => $lang_module['accuracy'], 'value' => $info['accuracy'] == 'keyword' ? $lang_module['byKeyword'] : $lang_module['byPhrase'] );
				$loop[] = array( 'key' => $lang_module['fromEngine'], 'value' => strtoupper( $info['fromEngine'] ) );
				$loop[] = array( 'key' => $lang_module['updDate'], 'value' => nv_date( 'd/m/Y H:i', $info['updtime'] ) );
				foreach( $loop as $a => $l )
				{
					$xtpl->assign( 'LOOP', $l );
					$xtpl->parse( 'process.result.loop' );
				}

				$xtpl->parse( 'process.result' );

				if( ! empty( $info['detail'] ) )
				{
					$mainResult = array();
					if( isset( $info['detail']['myPages'] ) ) $mainResult[] = array( 'key' => $lang_module['allPages'], 'value' => number_format( $info['detail']['allPages'] ) );
					if( isset( $info['detail']['myPages'] ) ) $mainResult[] = array( 'key' => $lang_module['myPages'], 'value' => number_format( $info['detail']['myPages'] ) );
					if( isset( $info['detail']['rank'] ) ) $mainResult[] = array( 'key' => $lang_module['rankResult'], 'value' => ( empty( $info['detail']['rank'] ) ? $lang_module['rank0'] : implode( ' - ', $info['detail']['rank'] ) ) );

					if( ! empty( $mainResult ) )
					{
						foreach( $mainResult as $a => $r )
						{
							$xtpl->assign( 'TR', $r );
							$xtpl->parse( 'process.MainResult.tr' );
						}
						$xtpl->parse( 'process.MainResult' );
					}
				}

				if( ! empty( $info['detail']['top10MyPages'] ) )
				{
					$xtpl->assign( 'CAPTION', $lang_module['Top10'] );
					foreach( $info['detail']['top10MyPages'] as $key => $link )
					{
						$xtpl->assign( 'ID', $key + 1 );
						$xtpl->assign( 'URL', $link );
						$xtpl->parse( 'process.TopPages.top' );
					}
					$xtpl->parse( 'process.TopPages' );
				}

				if( ! empty( $info['detail']['top50AllPages'] ) )
				{
					$xtpl->assign( 'CAPTION', $lang_module['Top50'] );
					foreach( $info['detail']['top50AllPages'] as $key => $link )
					{
						$a_class = isset( $info['detail']['rank'][$key] ) ? ' class="myLink"' : '';
						$xtpl->assign( 'ID', $key + 1 );
						$xtpl->assign( 'A_CLASS', $a_class );
						$xtpl->assign( 'URL', $link );
						$xtpl->parse( 'process.TopPages.top' );
					}
					$xtpl->parse( 'process.TopPages' );
				}
			}
			else
			{
				$xtpl->assign( 'ERROR', $lang_module['isLocalhost'] );
				$xtpl->parse( 'process.error' );
			}
		}

		$xtpl->parse( 'process' );
		echo $xtpl->text( 'process' );
	}
	die();
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';