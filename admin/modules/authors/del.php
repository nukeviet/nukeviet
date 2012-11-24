<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 21:23
 */

if( ! defined( 'NV_IS_FILE_AUTHORS' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_GODADMIN' ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	die();
}

$admin_id = $nv_Request->get_int( 'admin_id', 'get', 0 );
if( empty( $admin_id ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	die();
}

$sql = "SELECT * FROM `" . NV_AUTHORS_GLOBALTABLE . "` WHERE `admin_id`=" . $admin_id;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );
if( empty( $numrows ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	die();
}

$row = $db->sql_fetchrow( $result );

if( $row['lev'] == 1 )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	die();
}

function nv_checkAdmpass( $adminpass )
{
	global $db, $admin_info, $crypt;

	$sql = "SELECT `password` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $admin_info['userid'];
	$result = $db->sql_query( $sql );
	list( $pass ) = $db->sql_fetchrow( $result );
	return $crypt->validate( $adminpass, $pass );
}

$sql = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $admin_id;
$result = $db->sql_query( $sql );
$row_user = $db->sql_fetchrow( $result );

$error = "";
if( $nv_Request->get_int( 'ok', 'post', 0 ) )
{
	$sendmail = $nv_Request->get_int( 'sendmail', 'post', 0 );
	$reason = filter_text_input( 'reason', 'post', '', 1 );
	$adminpass = filter_text_input( 'adminpass_iavim', 'post' );

	if( empty( $adminpass ) )
	{
		$error = $lang_global['admin_password_empty'];
	}
	elseif( ! nv_checkAdmpass( $adminpass ) )
	{
		$error = sprintf( $lang_global['adminpassincorrect'], $adminpass );
		$adminpass = "";
	}
	else
	{
		if( $row['lev'] == 3 )
		{
			$is_delCache = false;
			$array_keys = array_keys( $site_mods );
			foreach( $array_keys as $mod )
			{
				if( ! empty( $mod ) )
				{
					if( ! empty( $site_mods[$mod]['admins'] ) )
					{
						$admins = explode( ",", $site_mods[$mod]['admins'] );
						if( in_array( $admin_id, $admins ) )
						{
							$admins = array_diff( $admins, array( $admin_id ) );
							$admins = implode( ",", $admins );
							$sql = "UPDATE `" . NV_MODULES_TABLE . "` SET `admins`=" . $db->dbescape( $admins ) . " WHERE `title`=" . $db->dbescape( $mod );
							$db->sql_query( $sql );
							$is_delCache = true;
						}
					}
				}
			}
			if( $is_delCache )
			{
				nv_del_moduleCache( 'modules' );
			}
		}
		$sql = "DELETE FROM `" . NV_AUTHORS_GLOBALTABLE . "` WHERE `admin_id` = " . $admin_id;
		nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['nv_admin_del'], "Username: " . $row_user['username'], $admin_info['userid'] );
		$db->sql_query( $sql );
		$db->sql_query( "LOCK TABLE " . NV_AUTHORS_GLOBALTABLE . " WRITE" );
		$db->sql_query( "REPAIR TABLE " . NV_AUTHORS_GLOBALTABLE );
		$db->sql_query( "OPTIMIZE TABLE " . NV_AUTHORS_GLOBALTABLE );
		$db->sql_query( "UNLOCK TABLE " . NV_AUTHORS_GLOBALTABLE );

		if( $sendmail )
		{
			$title = sprintf( $lang_module['delete_sendmail_title'], $global_config['site_name'] );
			$my_sig = ( ! empty( $admin_info['sig'] ) ) ? $admin_info['sig'] : "All the best";
			$my_mail = $admin_info['view_mail'] ? $admin_info['email'] : $global_config['site_email'];
			if( empty( $reason ) )
			{
				$message = sprintf( $lang_module['delete_sendmail_mess0'], $global_config['site_name'], nv_date( "d/m/Y H:i", NV_CURRENTTIME ), $my_mail );
			}
			else
			{
				$message = sprintf( $lang_module['delete_sendmail_mess1'], $global_config['site_name'], nv_date( "d/m/Y H:i", NV_CURRENTTIME ), $reason, $my_mail );
			}

			$message = trim( $message );

			$mess = $message;
			$mess .= "\r\n\r\n............................\r\n\r\n";
			$mess .= nv_EncString( $message );

			$mess = nv_nl2br( $mess, "<br />" );

			$xtpl = new XTemplate( "message.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/system" );

			$xtpl->assign( 'SITE_NAME', $global_config['site_name'] );
			$xtpl->assign( 'SITE_SLOGAN', $global_config['site_description'] );
			$xtpl->assign( 'SITE_EMAIL', $global_config['site_email'] );
			$xtpl->assign( 'SITE_FONE', $global_config['site_phone'] );
			$xtpl->assign( 'SITE_URL', $global_config['site_url'] );
			$xtpl->assign( 'TITLE', $title );
			$xtpl->assign( 'CONTENT', $mess );
			$xtpl->assign( 'AUTHOR_SIG', $my_sig );
			$xtpl->assign( 'AUTHOR_NAME', $admin_info['username'] );
			$xtpl->assign( 'AUTHOR_POS', $admin_info['position'] );
			$xtpl->assign( 'AUTHOR_EMAIL', $my_mail );

			$xtpl->parse( 'main' );
			$content = $xtpl->text( 'main' );

			$from = array( $admin_info['username'], $my_mail );
			$to = $row_user['email'];
			$send = nv_sendmail( $from, $to, nv_EncString( $title ), $content );
			if( ! $send )
			{
				nv_info_die( $lang_global['error_info_caption'], $lang_global['site_info'], $lang_global['error_sendmail_admin'], 1 );
			}
		}
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
		die();
	}
}
else
{
	$sendmail = 1;
	$reason = $adminpass = "";
}

$contents = array();
$contents['is_error'] = ( ! empty( $error ) ) ? 1 : 0;
$contents['title'] = ( ! empty( $error ) ) ? $error : sprintf( $lang_module['delete_sendmail_info'], $row_user['username'] );
$contents['action'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=del&amp;admin_id=" . $admin_id;
$contents['sendmail'] = array( $lang_module['admin_del_sendmail'], $sendmail );

$contents['reason'] = array(
	$lang_module['admin_del_reason'],
	$reason,
	255
);
	
$contents['admin_password'] = array(
	$lang_global['admin_password'],
	$adminpass,
	NV_UPASSMAX
);
	
$contents['submit'] = $lang_module['nv_admin_del'];

$page_title = $lang_module['nv_admin_del'];

// Parse content
$xtpl = new XTemplate( "del.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );

$class = $contents['is_error'] ? " class=\"error\"" : "";
$xtpl->assign( 'CLASS', $contents['is_error'] ? " class=\"error\"" : "" );
$xtpl->assign( 'TITLE', $contents['title'] );
$xtpl->assign( 'ACTION', $contents['action'] );
$xtpl->assign( 'SENDMAIL', $contents['sendmail'][0] );
$xtpl->assign( 'CHECKED', $contents['sendmail'][1] ? " checked=\"checked\"" : "" );

$xtpl->assign( 'REASON0', $contents['reason'][0] );
$xtpl->assign( 'REASON1', $contents['reason'][1] );
$xtpl->assign( 'REASON2', $contents['reason'][2] );

$xtpl->assign( 'ADMIN_PASSWORD0', $contents['admin_password'][0] );
$xtpl->assign( 'ADMIN_PASSWORD1', $contents['admin_password'][1] );
$xtpl->assign( 'ADMIN_PASSWORD2', $contents['admin_password'][2] );

$xtpl->assign( 'SUBMIT', $contents['submit'] );

$xtpl->parse( 'del' );
$contents = $xtpl->text( 'del' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>