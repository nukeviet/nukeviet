<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

// Call jquery datepicker + shadowbox

$my_head = "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.css\" rel=\"stylesheet\" />\n";
$my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.theme.css\" rel=\"stylesheet\" />\n";
$my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.datepicker.css\" rel=\"stylesheet\" />\n";

$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.min.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.datepicker.min.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.ui.datepicker-" . NV_LANG_INTERFACE . ".js\"></script>\n";

/**
 * nv_save_file_admin_config()
 * 
 * @return
 */
function nv_save_file_admin_config()
{
	global $db;
	$content_config_ip = $content_config_user = "";

	$sql = "SELECT `keyname`, `mask`, `begintime`, `endtime`, `notice` FROM `" . NV_AUTHORS_GLOBALTABLE . "_config`";
	$result = $db->sql_query( $sql );
	while( list( $keyname, $dbmask, $dbbegintime, $dbendtime, $dbnotice ) = $db->sql_fetchrow( $result ) )
	{
		$dbendtime = intval( $dbendtime );
		if( $dbendtime == 0 or $dbendtime > NV_CURRENTTIME )
		{
			if( $dbmask == -1 )
			{
				$content_config_user .= "\$adv_admins['" . md5( $keyname ) . "'] = array( 'password' => \"" . trim( $dbnotice ) . "\", 'begintime' => " . $dbbegintime . ", 'endtime' => " . $dbendtime . " );\n";
			}
			else
			{
				switch( $dbmask )
				{
					case 3:
						$ip_mask = "/\.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}$/";
						break;
					case 2:
						$ip_mask = "/\.[0-9]{1,3}.[0-9]{1,3}$/";
						break;
					case 1:
						$ip_mask = "/\.[0-9]{1,3}$/";
						break;
					default:
						$ip_mask = "//";
				}
				$content_config_ip .= "\$array_adminip['" . $keyname . "'] = array( 'mask' => \"" . $ip_mask . "\", 'begintime' => " . $dbbegintime . ", 'endtime' => " . $dbendtime . " );\n";
			}
		}
	}
	$content_config = "<?php\n\n";
	$content_config .= NV_FILEHEAD . "\n\n";
	$content_config .= "if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );\n\n";
	$content_config .= "\$array_adminip = array();\n";
	$content_config .= $content_config_ip . "\n";
	$content_config .= "\$adv_admins = array();\n";
	$content_config .= $content_config_user . "\n";
	$content_config .= "?>";
	
	return file_put_contents( NV_ROOTDIR . "/" . NV_DATADIR . "/admin_config.php", $content_config, LOCK_EX );
}

$delid = $nv_Request->get_int( 'delid', 'get' );
if( ! empty( $delid ) )
{
	$sql = "SELECT `keyname` FROM `" . NV_AUTHORS_GLOBALTABLE . "_config` WHERE id=" . $delid . " LIMIT 1";
	$res = $db->sql_query( $sql );
	list( $keyname ) = $db->sql_fetchrow( $res );
	$db->sql_query( "DELETE FROM `" . NV_AUTHORS_GLOBALTABLE . "_config` WHERE id=" . $delid . " LIMIT 1" );
	nv_save_file_admin_config();
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['adminip_delete'] . " " . $lang_module['config'], " keyname : " . $keyname, $admin_info['userid'] );
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
	die();
}

$error = array();
$contents = "";

if( $nv_Request->isset_request( 'savesetting', 'post' ) )
{
	$array_config_global = array();
	$array_config_global['admfirewall'] = $nv_Request->get_int( 'admfirewall', 'post' );
	$array_config_global['block_admin_ip'] = $nv_Request->get_int( 'block_admin_ip', 'post' );

	$array_config_global['spadmin_add_admin'] = $nv_Request->get_int( 'spadmin_add_admin', 'post' );
	$array_config_global['authors_detail_main'] = $nv_Request->get_int( 'authors_detail_main', 'post' );

	foreach( $array_config_global as $config_name => $config_value )
	{
		$query = "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES('sys', 'global', " . $db->dbescape( $config_name ) . ", " . $db->dbescape( $config_value ) . ")";
		$db->sql_query( $query );
	}
	
	nv_save_file_config_global();
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['save'] . " " . $lang_module['config'], "config", $admin_info['userid'] );
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
	exit();
}

if( $nv_Request->isset_request( 'submituser', 'post' ) )
{
	$uid = $nv_Request->get_int( 'uid', 'post', 0 );
	$username = filter_text_input( 'username', 'post', '', 1 );
	$password = filter_text_input( 'password', 'post', '', 1 );
	$password2 = filter_text_input( 'password2', 'post', '', 1 );
	$begintime1 = filter_text_input( 'begintime1', 'post', 0, 1 );
	$endtime1 = filter_text_input( 'endtime1', 'post', 0, 1 );

	$errorlogin = nv_check_valid_login( $username, NV_UNICKMAX, NV_UNICKMIN );
	if( ! empty( $errorlogin ) )
	{
		$error[] = $errorlogin;
	}
	elseif( preg_match( "/[^a-zA-Z0-9_-]/", $username ) )
	{
		$error[] = $lang_module['rule_user'];
	}
	if( ! empty( $password ) or empty( $uid ) )
	{
		$errorpassword = nv_check_valid_pass( $password, NV_UPASSMAX, NV_UPASSMIN );
		if( ! empty( $errorpassword ) )
		{
			$error[] = $errorpassword;
		}
		if( $password != $password2 )
		{
			$error[] = $lang_module['passwordsincorrect'];
		}
		elseif( preg_match( "/[^a-zA-Z0-9_-]/", $password ) )
		{
			$error[] = $lang_module['rule_pass'];
		}
	}

	if( ! empty( $begintime1 ) && preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $begintime1, $m ) )
	{
		$begintime1 = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$begintime1 = NV_CURRENTTIME;
	}
	if( ! empty( $endtime1 ) && preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $endtime1, $m ) )
	{
		$endtime1 = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$endtime1 = 0;
	}
	if( empty( $error ) )
	{
		if( $uid > 0 and $password != "" )
		{
			$db->sql_query( "UPDATE `" . NV_AUTHORS_GLOBALTABLE . "_config` SET `keyname`=" . $db->dbescape( $username ) . ", `mask`='-1',`begintime`=" . $begintime1 . ", `endtime`=" . $endtime1 . ", `notice`=" . $db->dbescape( md5( $password ) ) . " WHERE `id`=" . $uid . "" );
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['title_username'], $lang_module['username_edit'] . " username: " . $username, $admin_info['userid'] );
		}
		elseif( $uid > 0 )
		{
			$db->sql_query( "UPDATE `" . NV_AUTHORS_GLOBALTABLE . "_config` SET `keyname`=" . $db->dbescape( $username ) . ", `mask`='-1',`begintime`=" . $begintime1 . ", `endtime`=" . $endtime1 . " WHERE `id`=" . $uid . "" );
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['title_username'], $lang_module['username_edit'] . " username: " . $username, $admin_info['userid'] );
		}
		else
		{
			$db->sql_query( "REPLACE INTO `" . NV_AUTHORS_GLOBALTABLE . "_config` VALUES (NULL, " . $db->dbescape( $username ) . ",'-1',$begintime1, $endtime1," . $db->dbescape( md5( $password ) ) . " )" );
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['title_username'], $lang_module['username_add'] . " username: " . $username, $admin_info['userid'] );
		}
		nv_save_file_admin_config();
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
		die();
	}
}
else
{
	$username = $password = $password2 = $begintime1 = $endtime1 = '';
}

if( $nv_Request->isset_request( 'submitip', 'post' ) )
{
	$cid = $nv_Request->get_int( 'cid', 'post', 0 );
	$keyname = filter_text_input( 'keyname', 'post', '', 1 );
	$mask = $nv_Request->get_int( 'mask', 'post', 0 );
	$begintime = filter_text_input( 'begintime', 'post', 0, 1 );
	$endtime = filter_text_input( 'endtime', 'post', 0, 1 );

	if( empty( $keyname ) || ! $ips->nv_validip( $keyname ) )
	{
		$error[] = $lang_module['adminip_error_validip'];
	}
	if( ! empty( $begintime ) && preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $begintime, $m ) )
	{
		$begintime = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$begintime = NV_CURRENTTIME;
	}
	if( ! empty( $endtime ) && preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $endtime, $m ) )
	{
		$endtime = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$endtime = 0;
	}
	$notice = filter_text_input( 'notice', 'post', '', 1 );
	if( empty( $error ) )
	{
		if( $cid > 0 )
		{
			$db->sql_query( "UPDATE `" . NV_AUTHORS_GLOBALTABLE . "_config` SET `keyname`=" . $db->dbescape( $keyname ) . ", `mask`=" . $db->dbescape( $mask ) . ",`begintime`=" . $begintime . ", `endtime`=" . $endtime . ", `notice`=" . $db->dbescape( $notice ) . " WHERE `id`=" . $cid . "" );
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['adminip'], $lang_module['adminip_edit'] . " ID " . $cid . " -> " . $keyname, $admin_info['userid'] );
		}
		else
		{
			$db->sql_query( "REPLACE INTO `" . NV_AUTHORS_GLOBALTABLE . "_config` VALUES (NULL, " . $db->dbescape( $keyname ) . "," . $db->dbescape( $mask ) . ",$begintime, $endtime," . $db->dbescape( $notice ) . " )" );
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['adminip'], $lang_module['adminip_add'] . " " . $keyname, $admin_info['userid'] );
		}
		nv_save_file_admin_config();
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
		die();
	}
}
else
{
	$id = $keyname = $mask = $begintime = $endtime = $notice = '';
}

$cid = $nv_Request->get_int( 'id', 'get,post' );
$uid = $nv_Request->get_int( 'uid', 'get,post' );

$xtpl = new XTemplate( "config.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$xtpl->assign( 'DATA', array(
	'admfirewall' => $global_config['admfirewall'] ? " checked=\"checked\"" : "",
	'block_admin_ip' => $global_config['block_admin_ip'] ? " checked=\"checked\"" : "",
	'authors_detail_main' => $global_config['authors_detail_main'] ? " checked=\"checked\"" : "",
	'spadmin_add_admin' => $global_config['spadmin_add_admin'] ? " checked=\"checked\"" : "",
) );

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', implode( '<br/>', $error ) );
	$xtpl->parse( 'main.error' );
}

$sql = "SELECT `id`, `keyname`, `begintime`, `endtime` FROM `" . NV_AUTHORS_GLOBALTABLE . "_config` WHERE `mask` = '-1' ORDER BY `keyname` DESC";
$result = $db->sql_query( $sql );

if( $db->sql_numrows( $result ) )
{
	$i = 0;
	while( list( $dbid, $keyname, $dbbegintime, $dbendtime ) = $db->sql_fetchrow( $result ) )
	{
		$xtpl->assign( 'ROW', array(
			'class' => ++ $i % 2 ? ' class="second"' : '',
			'keyname' => $keyname,
			'dbbegintime' => ! empty( $dbbegintime ) ? date( 'd.m.Y', $dbbegintime ) : '',
			'dbendtime' => ! empty( $dbendtime ) ? date( 'd.m.Y', $dbendtime ) : $lang_module['adminip_nolimit'],
			'url_edit' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;uid=" . $dbid . "#iduser",
			'url_delete' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;delid=" . $dbid,
		) );
		
		$xtpl->parse( 'main.list_firewall.loop' );
	}
	
	$xtpl->parse( 'main.list_firewall' );
}

if( ! empty( $uid ) )
{
	list( $username, $begintime1, $endtime1 ) = $db->sql_fetchrow( $db->sql_query( "SELECT `keyname`, `begintime`, `endtime` FROM `" . NV_AUTHORS_GLOBALTABLE . "_config` WHERE `mask` = '-1' AND id=" . $uid ) );
	
	$lang_module['username_add'] = $lang_module['username_edit'];
	$password2 = $password = "";
}

$xtpl->assign( 'FIREWALLDATA', array(
	'uid' => $uid,
	'username' => $username,
	'password' => $password,
	'password2' => $password2,
	'begintime1' => ! empty( $begintime1 ) ? date( 'd.m.Y', $begintime1 ) : '',
	'endtime1' => ! empty( $endtime1 ) ? date( 'd.m.Y', $endtime1 ) : '',
) );

if( ! empty( $uid ) ) $xtpl->parse( 'main.nochangepass' );

$mask_text_array = array();
$mask_text_array[0] = "255.255.255.255";
$mask_text_array[3] = "255.255.255.xxx";
$mask_text_array[2] = "255.255.xxx.xxx";
$mask_text_array[1] = "255.xxx.xxx.xxx";

$sql = "SELECT `id`, `keyname`, `mask`, `begintime`, `endtime` FROM `" . NV_AUTHORS_GLOBALTABLE . "_config` WHERE `mask`!='-1' ORDER BY `keyname` DESC";
$result = $db->sql_query( $sql );

if( $db->sql_numrows( $result ) )
{
	$i = 0;
	while( list( $dbid, $keyname, $dbmask, $dbbegintime, $dbendtime ) = $db->sql_fetchrow( $result ) )
	{
		$xtpl->assign( 'ROW', array(
			'class' => ++ $i % 2 ? ' class="second"' : '',
			'keyname' => $keyname,
			'mask_text_array' => $mask_text_array[$dbmask],
			'dbbegintime' => ! empty( $dbbegintime ) ? date( 'd.m.Y', $dbbegintime ) : '',
			'dbendtime' => ! empty( $dbendtime ) ? date( 'd.m.Y', $dbendtime ) : '',
			'url_edit' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $dbid . "#idip",
			'url_delete' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;delid=" . $dbid,
		) );
		
		$xtpl->parse( 'main.ipaccess.loop' );
	}

	$xtpl->parse( 'main.ipaccess' );
}

if( ! empty( $cid ) )
{
	list( $id, $keyname, $mask, $begintime, $endtime, $notice ) = $db->sql_fetchrow( $db->sql_query( "SELECT id, keyname, mask, begintime, endtime, notice FROM `" . NV_AUTHORS_GLOBALTABLE . "_config` WHERE `mask` != '-1' AND id=" . $cid ) );
	$lang_module['adminip_add'] = $lang_module['adminip_edit'];
}

$xtpl->assign( 'IPDATA', array(
	'cid' => $cid,
	'keyname' => $keyname,
	'selected3' => ( $mask == 3 ) ? ' selected="selected"' : '',
	'selected2' => ( $mask == 2 ) ? ' selected="selected"' : '',
	'selected1' => ( $mask == 1 ) ? ' selected="selected"' : '',
	'begintime' => ! empty( $begintime ) ? date( 'd.m.Y', $begintime ) : '',
	'endtime' => ! empty( $endtime ) ? date( 'd.m.Y', $endtime ) : '',
	'notice' => $notice,
) );

$xtpl->assign( 'MASK_TEXT_ARRAY', $mask_text_array );

$page_title = $lang_module['config'];

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>