<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

if ( ! defined( 'NV_IS_USER' ) and $pro_config['who_comment'] == "member" )
{
	die( "ERR_" . $lang_module['comment_do_not_send'] );
}

$difftimeout = 360;
$id = $nv_Request->get_int( 'id', 'post', 0 );
$content = filter_text_input( 'content', 'post', '', 1 );
$code = filter_text_input( 'code', 'post', '' );
$status = $pro_config['comment_auto'];
$data = array( 
	"content" => $content,
	"title" => "none" 
);

if ( $content != "" )
{
	$timeout = $nv_Request->get_int( $module_name . '_' . $op . '_' . $id, 'cookie', 0 );
	if ( ! nv_capcha_txt( $code ) )
	{
		$contents = "ERR_" . $lang_global['securitycodeincorrect'];
	}
	elseif ( $timeout == 0 or NV_CURRENTTIME - $timeout > $difftimeout )
	{
		if ( empty( $user_info ) )
		{
			$user_info['username'] = $lang_module['comment_customer'];
			$user_info['userid'] = 0;
		}
		$sql = "INSERT INTO `" . $db_config['prefix'] . "_" . $module_data . "_comments_" . NV_LANG_DATA . "` (`cid`, `id`, `post_time`, `post_name`, `post_id`, `post_email`, `post_ip`, `status`, `photo`, `title`, `content`) VALUES (NULL, " . $id . ",'" . NV_CURRENTTIME . "', '" . $user_info['username'] . "', '" . $user_info['userid'] . "','" . $user_info['email'] . "'," . $db->dbescape( NV_CLIENT_IP ) . ", '" . $status . "','" . $user_info['photo'] . "'," . $db->dbescape( $data['title'] ) . ", " . $db->dbescape( $data['content'] ) . ")";
		
		$cid = $db->sql_query_insert_id( $sql );
		
		if ( $cid > 0 )
		{
			$contents = "OK_" . $lang_module['comment_success'];
			$nv_Request->set_Cookie( $module_name . '_' . $op . '_' . $id, NV_CURRENTTIME );
		}
		else
			$contents = "ERR_" . $lang_module['comment_unsuccess'];
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

echo $contents;

?>