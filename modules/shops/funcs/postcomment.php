<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$difftimeout = 360;
$id = $nv_Request->get_int( 'id', 'post', 0 );
$content = nv_substr( $nv_Request->get_title( 'content', 'post', '', 1 ), 0, 250 );
$code = $nv_Request->get_title( 'code', 'post', '' );
$checkss = $nv_Request->get_title( 'checkss', 'post' );
$status = $pro_config['comment_auto'];
$per_page_comment = 10;

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
	$name = '';
	$email = '';
}

$contents = 'ERR_Error Access!!!';

if( $pro_config['comment'] and $id > 0 and $checkss == md5( $id . session_id() . $global_config['sitekey'] ) and $code != '' and $content != '' )
{
	$timeout = $nv_Request->get_int( $module_data . '_' . $op . '_' . $id, 'cookie', 0 );
	if( ! nv_capcha_txt( $code ) )
	{
		$contents = 'ERR_' . $lang_global['securitycodeincorrect'];
	}
	elseif( $timeout == 0 or NV_CURRENTTIME - $timeout > $difftimeout )
	{
		$result = $db->query( 'SELECT listcatid, allowed_comm FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id = ' . $id . ' AND status =1' );
		$row = $result->fetch();
		if( isset( $row['allowed_comm'] ) and ( $row['allowed_comm'] == 1 or ( $row['allowed_comm'] == 2 and defined( 'NV_IS_USER' ) ) ) )
		{
			$content = nv_nl2br( $content, '<br />' );
			$stmt = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_comments_" . NV_LANG_DATA . " (cid, id, post_time, post_name, post_id, post_email, post_ip, status , photo, title, content) VALUES (NULL, " . $id . ", UNIX_TIMESTAMP(), :name, " . $userid . ", :email, NV_CLIENT_IP, " . $status . ", '', '', :content)" );
			$stmt->bindParam( ':name', $name, PDO::PARAM_STR );
			$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
			$stmt->bindParam( ':content', $content, PDO::PARAM_STR );
			if( $stmt->execute() )
			{
				$page = 0;
				$numf = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_comments_' . NV_LANG_DATA . ' WHERE id= ' . $id . ' AND status =1' )->fetchColumn();
				if( $status )
				{
					$result = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET hitscm=' . $numf . ' WHERE id=' . $id;
					$db->query( $result );
				}
				$page = ceil( ( $numf - $per_page_comment ) / $per_page_comment ) * $per_page_comment;
				if( $page < 0 ) $page = 0;
				$nv_Request->set_Cookie( $module_data . '_' . $op . '_' . $id, NV_CURRENTTIME );
				$contents = 'OK_' . $id . '_' . $checkss . '_' . $page . '_' . $lang_module['comment_success'];
			}
			else
			{
				$contents = 'ERR_' . $lang_module['comment_unsuccess'];
			}
		}
		else
		{
			$contents = 'ERR_' . $lang_module['comment_unsuccess'];
		}
	}
	else
	{
		$timeout = ceil( ( $difftimeout - NV_CURRENTTIME + $timeout ) / 60 );
		$timeoutmsg = sprintf( $lang_module['comment_timeout'], $timeout );
		$contents = 'ERR_' . $timeoutmsg;
	}
}
else
{
	$contents = 'ERR_' . $lang_module['comment_unsuccess'];
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';

?>