<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21/12/2010, 8:10
 */

if( ! defined( 'NV_IS_FILE_WEBTOOLS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['checkupdate'];
$contents = '';

$xtpl = new XTemplate( 'checkupdate.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'LANG', $lang_module );

if( $nv_Request->isset_request( 'i', 'get' ) )
{
	$i = $nv_Request->get_string( 'i', 'get' );

	if( $i == 'sysUpd' or $i == 'sysUpdRef' )
	{
		$values = array();
		$values['userVersion'] = $global_config['version'];
		$new_version = ( $i == 'sysUpd' ) ? nv_geVersion( 28800 ) : nv_geVersion( 120 );

		$error = '';
		if( $new_version === false )
		{
			$error = $lang_module['error_unknow'];
		}
		elseif( is_string( $new_version ) )
		{
			$error = $new_version;
		}
		
		if( ! empty( $error ) )
		{	
			$xtpl->assign( 'ERROR', $lang_module['checkSystem'] . ': ' . $error );
			
			$xtpl->parse( 'error' );
			echo $xtpl->text( 'error' );
		}
		else
		{
			$values['onlineVersion'] = sprintf( $lang_module['newVersion_detail'], ( string ) $new_version->version, ( string ) $new_version->name, nv_date( 'd/m/Y H:i', strtotime( ( string ) $new_version->date ) ) );
			$xtpl->assign( 'VALUE', $values );
			
			if( nv_version_compare( $global_config['version'], ( string ) $new_version->version ) < 0 )
			{
				$xtpl->assign( 'VERSION_INFO', ( string ) $new_version->message );
				
				// Allow auto update to newest version 
				if( ( string ) $new_version->version == ( string ) $new_version->updateable )
				{
					$xtpl->assign( 'VERSION_LINK', sprintf( $lang_module['newVersion_info1'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=getupdate&amp;version=' . ( ( string ) $new_version->updateable ) . '&amp;checksess=' . md5( ( ( string ) $new_version->updateable ) . $global_config['sitekey'] . session_id() ) ) );
				}
				elseif( ( ( string ) $new_version->updateable ) != '' )
				{
					$xtpl->assign( 'VERSION_LINK', sprintf( $lang_module['newVersion_info2'], ( ( string ) $new_version->updateable ), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=getupdate&amp;version=' . ( ( string ) $new_version->updateable ) . '&amp;checksess=' . md5( ( ( string ) $new_version->updateable ) . $global_config['sitekey'] . session_id() ) ) );
				}
				else
				{
					$xtpl->assign( 'VERSION_LINK', sprintf( $lang_module['newVersion_info3'], ( string ) $new_version->link ) );
				}
				
				$xtpl->parse( 'sysUpd.inf' );
			}
	
			clearstatcache();
			$sysUpdDate = filemtime( NV_ROOTDIR . '/' . NV_CACHEDIR . '/nukeviet.version.' . NV_LANG_INTERFACE . '.xml' );
			$xtpl->assign( 'SYSUPDDATE', nv_date( 'd/m/Y H:i', $sysUpdDate ) );
	
			$xtpl->parse( 'sysUpd' );
			echo $xtpl->text( 'sysUpd' );
		}
	}
	elseif( $i == 'extUpd' or $i == 'extUpdRef' )
	{
		$exts = ( $i == 'extUpd' ) ? nv_getExtVersion( 28800 ) : nv_getExtVersion( 120 );
		
		$error = '';
		if( $exts === false )
		{
			$error = $lang_module['error_unknow'];
		}
		elseif( is_string( $exts ) )
		{
			$error = $exts;
		}
		
		if( ! empty( $error ) )
		{	
			$xtpl->assign( 'ERROR', $lang_module['checkExtensions'] . ': ' . $error );
			
			$xtpl->parse( 'error' );
			echo $xtpl->text( 'error' );
		}
		else
		{
			clearstatcache();
			$extUpdDate = filemtime( NV_ROOTDIR . '/' . NV_CACHEDIR . '/extensions.version.' . NV_LANG_INTERFACE . '.xml' );
			
			$exts = $exts->xpath('extension');
			
			$a = 1;
			foreach( $exts as $extname => $values )
			{
				$value = array(
					'name' => ( string ) $values->name,
					'version' => ( string ) $values->version,
					'date' => ( string ) $values->date,
					'new_version' => ( string ) $values->new_version,
					'new_date' => ( string ) $values->new_date,
					'author' => ( string ) $values->author,
					'license' => ( string ) $values->license,
					'mode' => ( string ) $values->mode,
					'message' => ( string ) $values->message,
					'link' => ( string ) $values->link,
					'support' => ( string ) $values->support,
					'updateable' => ( ( string ) $values->updateable ) === 'true' ? true : false,
					'origin' => ( ( string ) $values->origin ) === 'true' ? true : false,
				);
				
				if( ! isset( $value['version'] ) )
				{
					$note = $lang_module['extNote1'];
					$cl = 'Note1';
				}
				elseif( empty( $value['new_version'] ) )
				{
					$note = sprintf( $lang_module['extNote3'], $value['link'] );
					$cl = 'Note3';
				}
				elseif( nv_version_compare( $value['version'], $value['new_version'] ) < 0 )
				{
					$note = sprintf( $lang_module['extNote4'], $value['link'] );
					$cl = 'Note4';
				}
				else
				{
					$note = $lang_module['extNote5'];
					$cl = 'Note5';
				}

				$info = $lang_module['userVersion'] . ': ';
				$info .= ! empty( $value['version'] ) ? $value['version'] : 'n/a';
				$info .= '; ' . $lang_module['onlineVersion'] . ': ';
				$info .= ! empty( $value['new_version'] ) ? $value['new_version'] : 'n/a';

				$tooltip = array();
				$tooltip[] = array( 'title' => $lang_module['userVersion'], 'content' => ( ! empty( $value['version'] ) ? $value['version'] : 'n/a' ) . ( ! empty( $value['date'] ) ? ' (' . nv_date( 'd/m/Y H:i', strtotime( $value['date'] ) ) . ')' : '' ) );
				$tooltip[] = array( 'title' => $lang_module['onlineVersion'], 'content' => ( ! empty( $value['new_version'] ) ? $value['new_version'] : 'n/a' ) . ( ! empty( $value['new_date'] ) ? ' (' . nv_date( 'd/m/Y H:i', strtotime( $value['new_date'] ) ) . ')' : '' ) );

				if( ! empty( $value['author'] ) )
				{
					$tooltip[] = array( 'title' => $lang_module['extAuthor'], 'content' => $value['author'] );
				}

				if( ! empty( $value['license'] ) )
				{
					$tooltip[] = array( 'title' => $lang_module['extLicense'], 'content' => ( string ) $value['license'] );
				}

				if( ! empty( $value['mode'] ) )
				{
					$tooltip[] = array( 'title' => $lang_module['extMode'], 'content' => $value['mode'] == 'sys' ? $lang_module['extModeSys'] : $lang_module['extModeOther'] );
				}

				if( ! empty( $value['link'] ) )
				{
					$tooltip[] = array( 'title' => $lang_module['extLink'], 'content' => "<a href=\"" . $value['link'] . "\">" . $value['link'] . "</a>" );
				}

				if( ! empty( $value['support'] ) )
				{
					$tooltip[] = array( 'title' => $lang_module['extSupport'], 'content' => "<a href=\"" . $value['support'] . "\">" . $value['support'] . "</a>" );
				}

				$xtpl->assign( 'EXTNAME', ( string ) $value['name'] );
				$xtpl->assign( 'EXTINFO', $info );

				foreach( $tooltip as $t )
				{
					$xtpl->assign( 'EXTTOOLTIP', $t );
					$xtpl->parse( 'extUpd.loop.li' );
				}

				if( ! isset( $value['version'] ) )
				{
					$xtpl->parse( 'extUpd.loop.note1' );
				}

				$xtpl->assign( 'EXTCL', $cl );
				$xtpl->assign( 'EXTNOTE', $note );
				$xtpl->parse( 'extUpd.loop' );
				++$a;
			}

			$xtpl->assign( 'EXTUPDDATE', nv_date( 'd/m/Y H:i', $extUpdDate ) );
			$xtpl->assign( 'LINKNEWEXT', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=extensions&amp;' . NV_OP_VARIABLE . '=newest' );

			$xtpl->parse( 'extUpd' );
			echo $xtpl->text( 'extUpd' );
		}
	}
	die();
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';