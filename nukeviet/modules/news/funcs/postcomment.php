<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$difftimeout = 360;
$id = $nv_Request->get_int( 'id', 'post', 0 );
$content = filter_text_input( 'content', 'post', '', 1 );
$code = filter_text_input( 'code', 'post', '' );
$checkss = filter_text_input( 'checkss', 'post' );
$status = $module_config[$module_name]['auto_postcomm'];
if( defined( 'NV_IS_USER' ) )
{
	$userid = $user_info['userid'];
	$name = $user_info['username'];
	$email = $user_info['email'];
}
elseif( defined( 'NV_IS_ADMIN' ) )
{
	$userid = $admin_info['userid'];
	$name = $admin_info['username'];
	$email = $admin_info['email'];
	$status = 1;
}
else
{
	$userid = 0;
	$name = filter_text_input( 'name', 'post', '', 1 );
	$email = filter_text_input( 'email', 'post', '' );
}

$contents = "";

if( $module_config[$module_name]['activecomm'] and $id > 0 and $checkss == md5( $id . session_id() . $global_config['sitekey'] ) and $name != "" and nv_check_valid_email( $email ) == "" and $code != "" and $content != "" )
{
	$timeout = $nv_Request->get_int( $module_name . '_' . $op . '_' . $id, 'cookie', 0 );
	if( ! nv_capcha_txt( $code ) )
	{
		$contents = "ERR_" . $lang_global['securitycodeincorrect'];
	}
	elseif( $timeout == 0 or NV_CURRENTTIME - $timeout > $difftimeout )
	{
		$query = $db->sql_query( "SELECT listcatid, allowed_comm FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id` = " . $id . " AND `status`=1" );
		$row = $db->sql_fetchrow( $query );
		if( isset( $row['allowed_comm'] ) and ( $row['allowed_comm'] == 1 or ( $row['allowed_comm'] == 2 and defined( 'NV_IS_USER' ) ) ) )
		{
			$array_catid = explode( ",", $row['listcatid'] );
			$content = nv_nl2br( $content, '<br />' );
			$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_comments` (`cid`, `id`, `content`, `post_time`, `userid`, `post_name`, `post_email`, `post_ip`, `status`) VALUES (NULL, " . $id . "," . $db->dbescape( $content ) . ", UNIX_TIMESTAMP(), " . $userid . ",  " . $db->dbescape( $name ) . ", " . $db->dbescape( $email ) . ", " . $db->dbescape( NV_CLIENT_IP ) . ", " . $status . ")";
			$result = $db->sql_query( $sql );
			if( $result )
			{
				$page = 0;
				list( $numf ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` where `id`= '" . $id . "' AND `status`=1" ) );
				if( $status )
				{
					$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `hitscm`=" . $numf . " WHERE `id`=" . $id;
					$db->sql_query( $query );
					foreach( $array_catid as $catid_i )
					{
						$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` SET `hitscm`=" . $numf . " WHERE `id`=" . $id;
						$db->sql_query( $query );
					}
				}
				$page = ceil( ( $numf - $per_page_comment ) / $per_page_comment ) * $per_page_comment;
				if( $page < 0 ) $page = 0;
				$nv_Request->set_Cookie( $module_name . '_' . $op . '_' . $id, NV_CURRENTTIME );
				$contents = "OK_" . $id . "_" . $checkss . "_" . $page . "_" . $lang_module['comment_success'];
			}
			else
			{
				$contents = "ERR_" . $lang_module['comment_unsuccess'];
			}
		}
		else
		{
			$contents = "ERR_" . $lang_module['comment_unsuccess'];
		}
	}
	else
	{
		$timeout = ceil( ( $difftimeout - NV_CURRENTTIME + $timeout ) / 60 );
		$timeoutmsg = sprintf( $lang_module['comment_timeout'], $timeout );
		$contents = "ERR_" . $timeoutmsg;
	}
}
else
{
	$contents = "ERR_" . $lang_module['comment_unsuccess'];
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>