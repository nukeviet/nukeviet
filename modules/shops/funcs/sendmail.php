<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

$checkss = $nv_Request->get_string( 'checkss', 'post', '' );
$id = $nv_Request->get_int( 'id', 'post,get', 0 );
$catid = $nv_Request->get_int( 'catid', 'post,get', 0 );

$sql = 'SELECT id, title, alias, hometext, allowed_send FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id =' . $id . ' AND status =1';
$result = $db->query( $sql );

list( $id, $title, $alias, $hometext, $allowed_send ) = $result->fetch( 3 );
if( $id > 0 )
{
	unset( $sql, $result );
	$result = '';
	$check = false;
	if( $checkss == md5( $id . session_id() . $global_config['sitekey'] ) and $allowed_send == 1 )
	{
		$link = $global_config['site_url'] . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'];
		$link = '<a href="' . $link . '" title="' . $title . '">$link</a>';
		$nv_seccode = $nv_Request->get_title( 'nv_seccode', 'post', '' );

		$name = $nv_Request->get_title( 'name', 'post', '', 1 );
		$youremail = $nv_Request->get_title( 'youremail', 'post', '' );
		$to_mail = $nv_Request->get_title( 'email', 'post', '' );
		$content = $nv_Request->get_title( 'content', 'post', '', 1 );
		$err_email = nv_check_valid_email( $to_mail );
		$err_youremail = nv_check_valid_email( $youremail );
		$err_name = '';
		$message = '';
		$success = '';
		if( $global_config['gfx_chk'] == 1 and ! nv_capcha_txt( $nv_seccode ) )
		{
			$err_name = $lang_global['securitycodeincorrect'];
		}
		elseif( empty( $name ) )
		{
			$err_name = $lang_module['sendmail_err_name'];
		}
		elseif( empty( $err_email ) and empty( $err_youremail ) )
		{
			$subject = $lang_module['sendmail_subject'] . $name;
			$message .= $lang_module['sendmail_welcome'] . ' <strong>' . $global_config['site_name'] . '</strong> ' . $lang_module['sendmail_welcome1'] . '<br /><br />' . $content . '<br /><br />' . $hometext . ' <br/><br /><strong>' . $lang_module['sendmail_welcome2'] . '</strong><br />' . $link;
			$from = array( $name, $youremail );
			$check = nv_sendmail( $from, $to_mail, $subject, $message );
			if( $check )
			{
				$success = $lang_module['sendmail_success'] . '<strong> ' . $to_mail . '</strong>';
			}
			else
			{
				$success = $lang_module['sendmail_success_err'];
			}
		}
		$result = array(
			'err_name' => $err_name,
			'err_email' => $err_email,
			'err_yourmail' => $err_youremail,
			'send_success' => $success,
			'check' => $check
		);
	}
	if( $id > 0 && $allowed_send == 1 )
	{
		if( defined( 'NV_IS_ADMIN' ) )
		{
			$name = $admin_info['username'];
			$youremail = $admin_info['email'];
		}
		elseif( defined( 'NV_IS_USER' ) )
		{
			$name = $user_info['username'];
			$youremail = $user_info['email'];
		}
		else
		{
			$name = '';
			$youremail = '';
		}
		$sendmail = array(
			'id' => $id,
			'catid' => $catid,
			'checkss' => md5( $id . session_id() . $global_config['sitekey'] ),
			'v_name' => $name,
			'v_mail' => $youremail,
			'result' => $result,
			'action' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=sendmail' //
		);

		$contents = sendmail_themme( $sendmail );

		include NV_ROOTDIR . '/includes/header.php';
		echo $contents;
		include NV_ROOTDIR . '/includes/footer.php';
	}
}

Header( 'Location: ' . $global_config['site_url'] );
exit();