<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$difftimeout = 360;
$id = $nv_Request->get_int( 'id', 'post', 0 );
$content = $nv_Request->get_title( 'content', 'post', '', 1 );
$code = $nv_Request->get_title( 'code', 'post', '' );
$checkss = $nv_Request->get_title( 'checkss', 'post' );
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
	$name = $nv_Request->get_title( 'name', 'post', '', 1 );
	$email = $nv_Request->get_title( 'email', 'post', '' );
}

$contents = '';

if( $module_config[$module_name]['activecomm'] == 1 and $id > 0 and $checkss == md5( $id . session_id() . $global_config['sitekey'] ) and $name != '' and nv_check_valid_email( $email ) == '' and $code != "" and $content != "" )
{
	$timeout = $nv_Request->get_int( $module_name . '_' . $op . '_' . $id, 'cookie', 0 );
	if( ! nv_capcha_txt( $code ) )
	{
		$contents = "ERR_" . $lang_global['securitycodeincorrect'];
	}
	elseif( $timeout == 0 or NV_CURRENTTIME - $timeout > $difftimeout )
	{
		$query = $db->query( "SELECT listcatid, allowed_comm FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id = " . $id . " AND status=1" );
		$row = $query->fetch();
		if( isset( $row['allowed_comm'] ) and ( $row['allowed_comm'] == 1 or ( $row['allowed_comm'] == 2 and defined( 'NV_IS_USER' ) ) ) )
		{
			$array_catid = explode( ',', $row['listcatid'] );
			$content = nv_nl2br( $content, '<br />' );
			$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_comments (id, content, post_time, userid, post_name, post_email, post_ip, status) VALUES (" . $id . "," . $db->quote( $content ) . ", " . NV_CURRENTTIME . ", " . $userid . ", " . $db->quote( $name ) . ", " . $db->quote( $email ) . ", " . $db->quote( NV_CLIENT_IP ) . ", " . $status . ")";
			$result = $db->query( $sql );
			if( $result )
			{
				$page = 0;
				$numf = $db->query( "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_comments where id= '" . $id . "' AND status=1" )->fetchColumn();
				if( $status )
				{
					$query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET hitscm=" . $numf . " WHERE id=" . $id;
					$db->exec( $query );
					foreach( $array_catid as $catid_i )
					{
						$query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . " SET hitscm=" . $numf . " WHERE id=" . $id;
						$db->exec( $query );
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

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';

?>