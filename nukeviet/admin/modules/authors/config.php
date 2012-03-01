<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

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
				$content_config_user .= "\$adv_admins['" . md5( $keyname ) . "'] = array('password'=>\"" . trim( $dbnotice ) . "\", 'begintime'=>" . $dbbegintime . ", 'endtime'=>" . $dbendtime . ");\n";
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
				$content_config_ip .= "\$array_adminip['" . $keyname . "'] = array('mask'=>\"" . $ip_mask . "\", 'begintime'=>" . $dbbegintime . ", 'endtime'=>" . $dbendtime . ");\n";
			}
		}
	}
	$content_config = "<?php\n\n";
	$content_config .= NV_FILEHEAD . "\n\n";
	$content_config .= "if ( ! defined( 'NV_MAINFILE' ) )\n";
	$content_config .= "{\n";
	$content_config .= "    die( 'Stop!!!' );\n";
	$content_config .= "}\n\n";
	$content_config .= "\n";
	$content_config .= "\$array_adminip = array();\n";
	$content_config .= $content_config_ip . "\n\n";
	$content_config .= "\$adv_admins = array();\n";
	$content_config .= $content_config_user . "\n\n";
	$content_config .= "\n";
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

if( ! empty( $error ) )
{
	$contents .= "<div class=\"quote\" style=\"width:780px;\">\n";
	$contents .= "<blockquote class='error'>" . implode( '<br/>', $error ) . "</blockquote>\n";
	$contents .= "</div>\n";
	$contents .= "<div style='clear:both'></div>\n";
}
$contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$contents .= "<table  class=\"tab1\">";
$contents .= "<col width=\"65%\" />";
$contents .= "<col width=\"45%\" />";
$contents .= "<thead>
            <tr>
                <td colspan=\"2\">" . $lang_module['config'] . "</td>
            </tr>
        </thead>";
$contents .= "<tbody class=\"second\">
<tr>
    <td>" . $lang_module['admfirewall'] . "</td>
    <td><input type=\"checkbox\" value=\"1\" name=\"admfirewall\" " . ( ( $global_config['admfirewall'] ) ? "checked=\"checked\"" : "" ) . " /></td>
</tr>
</tbody>";

$contents .= "<tbody>
<tr>
    <td>" . $lang_module['block_admin_ip'] . "</td>
    <td><input type=\"checkbox\" value=\"1\" name=\"block_admin_ip\" " . ( ( $global_config['block_admin_ip'] ) ? "checked=\"checked\"" : "" ) . " /></td>
</tr>
</tbody>";

$contents .= "<tbody class=\"second\">
<tr>
    <td>" . $lang_module['authors_detail_main'] . "</td>
    <td><input type=\"checkbox\" value=\"1\" name=\"authors_detail_main\" " . ( ( $global_config['authors_detail_main'] ) ? "checked=\"checked\"" : "" ) . " /></td>
</tr>
</tbody>";

$contents .= "<tbody>
<tr>
    <td>" . $lang_module['spadmin_add_admin'] . "</td>
    <td><input type=\"checkbox\" value=\"1\" name=\"spadmin_add_admin\" " . ( ( $global_config['spadmin_add_admin'] ) ? "checked=\"checked\"" : "" ) . " /></td>
</tr>
</tbody>";

$contents .= "
<tbody>
<tr>
    <td colspan=\"2\">
        <input type=\"submit\" value=\" " . $lang_module['save'] . " \" name=\"Submit1\" />
        <input type=\"hidden\" value=\"1\" name=\"savesetting\" />
    </td>
</tr>
</tbody>
</table>
</form>";

$sql = "SELECT id, keyname, begintime, endtime FROM `" . NV_AUTHORS_GLOBALTABLE . "_config` WHERE `mask` = '-1' ORDER BY keyname DESC";
$result = $db->sql_query( $sql );
if( $db->sql_numrows( $result ) )
{
	$contents .= "<br />\n";
	$contents .= "<table id=\"iduser\" class=\"tab1\">\n";
	$contents .= "<caption>" . $lang_module['title_username'] . "</caption>\n";
	$contents .= "<thead>\n";
	$contents .= "<tr align=\"center\">\n";
	$contents .= "<td>" . $lang_global['username'] . "</td>\n";
	$contents .= "<td>" . $lang_module['adminip_timeban'] . "</td>\n";
	$contents .= "<td>" . $lang_module['adminip_timeendban'] . "</td>\n";
	$contents .= "<td>" . $lang_module['adminip_funcs'] . "</td>\n";
	$contents .= "</tr>\n";
	$contents .= "</thead>\n";
	while( list( $dbid, $keyname, $dbbegintime, $dbendtime ) = $db->sql_fetchrow( $result ) )
	{
		$contents .= "<tbody>\n";
		$contents .= "<tr>\n";
		$contents .= "<td align=\"left\">" . $keyname . "</td>\n";
		$contents .= "<td align=\"center\">" . ( ! empty( $dbbegintime ) ? date( 'd.m.Y', $dbbegintime ) : '' ) . "</td>\n";
		$contents .= "<td align=\"center\">" . ( ! empty( $dbendtime ) ? date( 'd.m.Y', $dbendtime ) : $lang_module['adminip_nolimit'] ) . "</td>\n";
		$contents .= "<td align=\"center\">
		<span class=\"edit_icon\">
			<a class='edit' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;uid=" . $dbid . "#iduser\">" . $lang_global['edit'] . "</a>
		</span>	- 
		<span class=\"delete_icon\">
			<a class='deleteuser' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;delid=" . $dbid . "\">" . $lang_global['delete'] . "</a>
		</span></td>\n";
		$contents .= "</tr>\n";
		$contents .= "</tbody>\n";
	}
	$contents .= "</table>\n";
}

if( ! empty( $uid ) )
{
	list( $username, $begintime1, $endtime1 ) = $db->sql_fetchrow( $db->sql_query( "SELECT keyname, begintime, endtime FROM `" . NV_AUTHORS_GLOBALTABLE . "_config` WHERE `mask` = '-1' AND id=$uid" ) );
	$lang_module['username_add'] = $lang_module['username_edit'];
	$password2 = $password = "";
}
$contents .= "<form id = \"form_add_user\" action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$contents .= "<input type=\"hidden\" name =\"uid\" value=\"" . $uid . "\" />";
$contents .= "<table class=\"tab1\">\n";
$contents .= "<tbody class='second'>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan='2'><strong>" . $lang_module['username_add'] . "</strong></td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tbody class='second'>\n";
$contents .= "<tr>\n";
$contents .= "<td style=\"width:150px\">" . $lang_global['username'] . " (<span style='color:red'>*</span>)</td>\n";
$contents .= "<td><input type='text' name='username' value='" . $username . "' style='width:200px'/></td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_global['password'] . " (<span style='color:red'>*</span>)</td>\n";
$contents .= "<td><input type='password' name='password' value='" . $password . "'  style='width:200px'/></td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_global['password2'] . " (<span style='color:red'>*</span>)</td>\n";
$contents .= "<td><input type='password' name='password2' value='" . $password2 . "' style='width:200px'/></td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tbody class='second'>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['adminip_begintime'] . "</td>\n";
$contents .= "<td><input type='text' name='begintime1' id='begintime1' value='" . ( ! empty( $begintime1 ) ? date( 'd.m.Y', $begintime1 ) : '' ) . "' style='width:150px'/>\n";
$contents .= "<img src=\"" . NV_BASE_SITEURL . "images/calendar.jpg\" style=\"widht:18px; height:17px; cursor: pointer; vertical-align: middle;\" onclick=\"popCalendar.show(this, 'begintime1', 'dd.mm.yyyy', true);\" alt=\"\" />\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['adminip_endtime'] . "</td>\n";
$contents .= "<td><input type='text' name='endtime1' id='endtime1' value='" . ( ! empty( $endtime1 ) ? date( 'd.m.Y', $endtime1 ) : '' ) . "' style='width:150px'/>\n";
$contents .= "<img src=\"" . NV_BASE_SITEURL . "images/calendar.jpg\" style=\"widht:18px; height:17px; cursor: pointer; vertical-align: middle;\" onclick=\"popCalendar.show(this, 'endtime1', 'dd.mm.yyyy', true);\" alt=\"\" />\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan='2'>";
$contents .= "<input type='submit' value='" . $lang_module['save'] . "' name='submituser'/><br /><br />\n";
if( ! empty( $uid ) ) $contents .= $lang_module['nochangepass'];
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "</table>\n";
$contents .= "</form>\n";

$contents .= "<script type=\"text/javascript\">
//<![CDATA[
document.getElementById('form_add_user').setAttribute(\"autocomplete\", \"off\");
//]]>
</script>";

$mask_text_array = array();
$mask_text_array[0] = "255.255.255.255";
$mask_text_array[3] = "255.255.255.xxx";
$mask_text_array[2] = "255.255.xxx.xxx";
$mask_text_array[1] = "255.xxx.xxx.xxx";

$sql = "SELECT id, keyname, mask, begintime, endtime FROM `" . NV_AUTHORS_GLOBALTABLE . "_config` WHERE `mask` != '-1' ORDER BY keyname DESC";
$result = $db->sql_query( $sql );
if( $db->sql_numrows( $result ) )
{
	$contents .= "<br />\n";
	$contents .= "<table id=\"idip\"  class=\"tab1\">\n";
	$contents .= "<caption>" . $lang_module['adminip'] . "</caption>";
	$contents .= "<thead>\n";

	$contents .= "<tr align=\"center\">\n";
	$contents .= "<td>" . $lang_module['adminip_ip'] . "</td>\n";
	$contents .= "<td>" . $lang_module['adminip_mask'] . "</td>\n";
	$contents .= "<td>" . $lang_module['adminip_timeban'] . "</td>\n";
	$contents .= "<td>" . $lang_module['adminip_timeendban'] . "</td>\n";
	$contents .= "<td>" . $lang_module['adminip_funcs'] . "</td>\n";
	$contents .= "</tr>\n";
	$contents .= "</thead>\n";

	while( list( $dbid, $keyname, $dbmask, $dbbegintime, $dbendtime ) = $db->sql_fetchrow( $result ) )
	{
		$contents .= "<tbody>\n";
		$contents .= "<tr>\n";
		$contents .= "<td align=\"center\">" . $keyname . "</td>\n";
		$contents .= "<td align=\"center\">" . $mask_text_array[$dbmask] . "</td>\n";
		$contents .= "<td align=\"center\">" . ( ! empty( $dbbegintime ) ? date( 'd.m.Y', $dbbegintime ) : '' ) . "</td>\n";
		$contents .= "<td align=\"center\">" . ( ! empty( $dbendtime ) ? date( 'd.m.Y', $dbendtime ) : $lang_module['adminip_nolimit'] ) . "</td>\n";
		$contents .= "<td align=\"center\">
		<span class=\"edit_icon\">
			<a class='edit' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $dbid . "#idip\">" . $lang_global['edit'] . "</a>
		</span>	- 
		<span class=\"delete_icon\">
			<a class='deleteone' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;delid=" . $dbid . "\">" . $lang_global['delete'] . "</a>
		</span></td>\n";
		$contents .= "</tr>\n";
		$contents .= "</tbody>\n";
	}
	$contents .= "</table>\n";
}
if( ! empty( $cid ) )
{
	list( $id, $keyname, $mask, $begintime, $endtime, $notice ) = $db->sql_fetchrow( $db->sql_query( "SELECT id, keyname, mask, begintime, endtime, notice FROM `" . NV_AUTHORS_GLOBALTABLE . "_config` WHERE `mask` != '-1' AND id=$cid" ) );
	$lang_module['adminip_add'] = $lang_module['adminip_edit'];
}
$my_head = "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/popcalendar/popcalendar.js\"></script>\n";

$contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$contents .= "<input type=\"hidden\" name =\"cid\" value=\"" . $cid . "\" />";
$contents .= "<table class=\"tab1\">\n";
$contents .= "<tbody class='second'>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan='2'><strong>" . $lang_module['adminip_add'] . "</strong></td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td style=\"width:150px\">" . $lang_module['adminip_address'] . " (<span style='color:red'>*</span>)</td>\n";
$contents .= "<td><input type='text' name='keyname' value='" . $keyname . "' style='width:200px'/> (xxx.xxx.xxx.xxx)</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tbody class='second'>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['adminip_mask'] . "</td>\n";
$contents .= "<td>";
$contents .= "<select name='mask'>";
$contents .= "<option value='0'>" . $mask_text_array[0] . "</option>";
$contents .= "<option value='3' " . ( ( $mask == 3 ) ? 'selected=selected' : '' ) . ">" . $mask_text_array[3] . "</option>";
$contents .= "<option value='2' " . ( ( $mask == 2 ) ? 'selected=selected' : '' ) . ">" . $mask_text_array[2] . "</option>";
$contents .= "<option value='1' " . ( ( $mask == 1 ) ? 'selected=selected' : '' ) . ">" . $mask_text_array[1] . "</option>";
$contents .= "</select>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tbody class='second'>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['adminip_begintime'] . "</td>\n";
$contents .= "<td><input type='text' name='begintime' id='begintime' value='" . ( ! empty( $begintime ) ? date( 'd.m.Y', $begintime ) : '' ) . "' style='width:150px'/>\n";
$contents .= "<img src=\"" . NV_BASE_SITEURL . "images/calendar.jpg\" style=\"widht:18px; height:17px; cursor: pointer; vertical-align: middle;\" onclick=\"popCalendar.show(this, 'begintime', 'dd.mm.yyyy', true);\" alt=\"\" />\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['adminip_endtime'] . "</td>\n";
$contents .= "<td><input type='text' name='endtime' id='endtime' value='" . ( ! empty( $endtime ) ? date( 'd.m.Y', $endtime ) : '' ) . "' style='width:150px'/>\n";
$contents .= "<img src=\"" . NV_BASE_SITEURL . "images/calendar.jpg\" style=\"widht:18px; height:17px;  cursor: pointer; vertical-align: middle;\" onclick=\"popCalendar.show(this, 'endtime', 'dd.mm.yyyy', true);\" alt=\"\" />\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tbody class='second'>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['adminip_notice'] . "</td>\n";
$contents .= "<td>";
$contents .= "<textarea rows=\"4\" cols=\"\" name='notice' style='width:400px;height:50px'>" . $notice . "</textarea>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan='2'>";
$contents .= "<input type='submit' value='" . $lang_module['save'] . "' name='submitip'/><br /><br />\n";
$contents .= $lang_module['adminip_note'];
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "</table>\n";
$contents .= "</form>\n";
$contents .= "
<script type='text/javascript'>
	//<![CDATA[
	$('input[name=submitip]').click(function(){
		var ip = $('input[name=keyname]').val();
		$('input[name=keyname]').focus();
		if (ip==''){
			alert('" . $lang_module['adminip_error_ip'] . "');
			return false;
		}
	});
	$('input[name=submituser]').click(function(){
		var username= $('input[name=username]').val();
		var nv_rule = /^([a-zA-Z0-9_-])+$/;
		if (username==''){
			$('input[name=username]').focus();
			alert('" . addslashes( $lang_global['username_empty'] ) . "');
			return false;
		}
		else if (!nv_rule.test(username)){
			$('input[name=username]').focus();
			alert('" . addslashes( $lang_module['rule_user'] ) . "');
			return false;
		}
		var password = $('input[name=password]').val();
		if (password== '' && $('input[name=uid]').val()=='0'){
			$('input[name=password]').focus();
			alert('" . addslashes( $lang_global['password_empty'] ) . "');
			return false;
		}			
		if (password!=$('input[name=password2]').val()){
			$('input[name=password2]').focus();
			alert('" . addslashes( $lang_module['passwordsincorrect'] ) . "');
			return false;
		}
		else if (password!='' && !nv_rule.test(password)){
			$('input[name=password]').focus();
			alert('" . addslashes( $lang_module['rule_pass'] ) . "');
			return false;
		}		
	});
	$('a.deleteone').click(function(){
        if (confirm('" . addslashes( $lang_module['adminip_delete_confirm'] ) . "')){
        	var url = $(this).attr('href');	
	        $.ajax({        
		        type: 'POST',
		        url: url,
		        data:'',
		        success: function(data){  
		            alert('" . $lang_module['adminip_del_success'] . "');
		            window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "';
		        }
	        });  
        }
		return false;
	});
	$('a.deleteuser').click(function(){
        if (confirm('" . addslashes( $lang_module['nicknam_delete_confirm'] ) . "')){
        	var url = $(this).attr('href');	
	        $.ajax({        
		        type: 'POST',
		        url: url,
		        data:'',
		        success: function(data){  
		            alert('" . addslashes( $lang_module['adminip_del_success'] ) . "');
		            window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "';
		        }
	        });  
        }
		return false;
	});
	//]]>
</script>";

$page_title = $lang_module['config'];

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>