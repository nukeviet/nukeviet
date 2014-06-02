<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_MOD_CONTACT' ) ) die( 'Stop!!!' );

/**
 * nv_SendMail2User()
 *
 * @param mixed $cid
 * @param mixed $fcontent
 * @param mixed $ftitle
 * @param mixed $femail
 * @param mixed $full_name
 * @return void
 */
function nv_SendMail2User( $cid, $fcontent, $ftitle, $femail, $full_name )
{
	global $db, $module_data, $db_config;

	$email_list = array();

	$sql = 'SELECT email, admins FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE id =' . $cid;
	$result = $db->query( $sql );
	list( $email, $admins ) = $result->fetch( 3 );

	if( ! empty( $email ) )
	{
		$email_list[] = $email;
	}

	if( ! empty( $admins ) )
	{
		$admins = array_map( 'trim', explode( ';', $admins ) );

		$a_l = array();
		foreach( $admins as $adm )
		{
			if( preg_match( '/^([0-9]+)\/([0-1]{1})\/([0-1]{1})\/([0-1]{1})$/i', $adm ) )
			{
				$adm2 = array_map( 'trim', explode( '/', $adm ) );

				if( $adm2[3] == 1 )
				{
					$a_l[] = intval( $adm2[0] );
				}
			}
		}

		if( ! empty( $a_l ) )
		{
			$a_l = implode( ',', $a_l );

			$sql = 'SELECT t2.email as admin_email FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.admin_id = t2.userid WHERE t1.lev!=0 AND t1.is_suspend=0 AND t1.admin_id IN (' . $a_l . ')';
			$result = $db->query( $sql );

			while( $row = $result->fetch() )
			{
				if( nv_check_valid_email( $row['admin_email'] ) == '' )
				{
					$email_list[] = $row['admin_email'];
				}
			}
		}
	}


	if( ! empty( $email_list ) )
	{
		$from = array( $full_name, $femail );
		$email_list = array_unique( $email_list );
		@nv_sendmail( $from, $email_list, $ftitle, $fcontent );
	}
}

//Danh sach cac bo phan
$sql = 'SELECT id, full_name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE act=1';
$array_department = nv_db_cache( $sql, 'id' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$mod_title = isset( $lang_module['main_title'] ) ? $lang_module['main_title'] : $module_info['custom_title'];

$fname = '';
$femail = '';
$fphone = '';

if( defined( 'NV_IS_USER' ) )
{
	$fname = ! empty( $user_info['full_name'] ) ? $user_info['full_name'] : $user_info['username'];
	$femail = $user_info['email'];
}

$fcon = '';
$fcode = '';
$error = '';
$fpart = isset( $array_op[0] ) ? $array_op[0] : 0;
$fpart = $nv_Request->get_int( 'fpart', 'post,get', $fpart );
$ftitle = nv_substr( $nv_Request->get_title( 'ftitle', 'post,get', '', 1 ), 0, 250 );

$full = isset( $array_op[1] ) ? $array_op[1] : 1;
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

if( ! empty( $array_department ) )
{
	$checkss = $nv_Request->get_title( 'checkss', 'post', '' );

	if( $checkss == md5( $client_info['session_id'] . $global_config['sitekey'] ) )
	{
		if( defined( 'NV_IS_USER' ) )
		{
			$fname = ! empty( $user_info['full_name'] ) ? $user_info['full_name'] : $user_info['username'];
			$femail = $user_info['email'];
		}
		else
		{
			$fname = nv_substr( $nv_Request->get_title( 'fname', 'post', '', 1 ), 0, 100 );
			$femail = nv_substr( $nv_Request->get_title( 'femail', 'post', '', 1), 0, 100 );
		}

		$fphone = nv_substr( $nv_Request->get_title( 'fphone', 'post', '', 1 ), 0, 100 );
		$fcon = $nv_Request->get_editor( 'fcon', '', NV_ALLOWED_HTML_TAGS );
		$fcode = $nv_Request->get_title( 'fcode', 'post', '' );

		$check_valid_email = nv_check_valid_email( $femail );

		if( empty( $fname ) )
		{
			$error = $lang_module['error_fullname'];
		}
		elseif( ! empty( $check_valid_email ) )
		{
			$error = $check_valid_email;
		}
		elseif( empty( $ftitle ) )
		{
			$error = $lang_module['error_title'];
		}
		elseif( empty( $fcon ) )
		{
			$error = $lang_module['error_content'];
		}
		elseif( ! isset( $array_department[$fpart] ) )
		{
			$error = $lang_module['error_part'];
		}
		elseif( ! nv_capcha_txt( $fcode ) )
		{
			$error = $lang_module['error_captcha'];
		}
		else
		{
			$fcon = nv_nl2br( $fcon );

			$sender_id = intval( defined( 'NV_IS_USER' ) ? $user_info['userid'] : 0 );

			$sth = $db->prepare( "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_send
				(cid, title, content, send_time, sender_id, sender_name, sender_email, sender_phone, sender_ip, is_read, is_reply) VALUES
				(" . $fpart . ", :title, :content, " . NV_CURRENTTIME . ", " . $sender_id . ", :sender_name, :sender_email, :sender_phone, :sender_ip, 0, 0)" );
			$sth->bindParam( ':title', $ftitle, PDO::PARAM_STR );
			$sth->bindParam( ':content', $fcon, PDO::PARAM_STR, strlen( $fcon ) );
			$sth->bindParam( ':sender_name', $fname, PDO::PARAM_STR );
			$sth->bindParam( ':sender_email', $femail, PDO::PARAM_STR );
			$sth->bindParam( ':sender_phone', $fphone, PDO::PARAM_STR );
			$sth->bindParam( ':sender_ip', $client_info['ip'], PDO::PARAM_STR );
			if( $sth->execute() )
			{
				$website = '<a href="' . $global_config['site_url'] . '">' . $global_config['site_name'] . '</a>';
				$fcon .= '<br /><br />----------------------------------------<br /><br />';

				if( empty( $fphone ) )
				{
					$fcon .= sprintf( $lang_module['sendinfo'], $website, $fname, $femail, $client_info['ip'], $array_department[$fpart]['full_name'] );
				}
				else
				{
					$fcon .= sprintf( $lang_module['sendinfo2'], $website, $fname, $femail, $fphone, $client_info['ip'], $array_department[$fpart]['full_name'] );
				}

				nv_SendMail2User( $fpart, $fcon, $ftitle, $femail, $fname );

				$url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;
				$contents .= call_user_func( 'sendcontact', $url );

				include NV_ROOTDIR . '/includes/header.php';
				echo nv_site_theme( $contents );
				include NV_ROOTDIR . '/includes/footer.php';
				exit();
			}
		}
	}
    else
    {
    	$base_url_rewrite = $base_url;
        if( isset( $array_op[0] ) and isset( $array_department[$fpart] ) )
        {
        	$array_department_i = $array_department[$fpart];
        	$array_department = array( $fpart => $array_department_i );

            $base_url_rewrite .= '&amp;' . NV_OP_VARIABLE . '=' . $fpart;
            if( isset( $array_op[1] ) and $array_op[1] == 0 )
            {
                $base_url_rewrite .= '/0';
                if( isset( $array_op[2] ) )
                {
                    $global_config['mudim_active'] = $array_op[2];
                    $base_url_rewrite .= '/' . $array_op[2];
                }
            }
        }
        $base_url_rewrite = nv_url_rewrite( $base_url_rewrite, true );
        if( $_SERVER['REQUEST_URI'] != $base_url_rewrite )
        {
            header( 'Location:' . $base_url_rewrite );
            die();
        }
        $canonicalUrl = NV_MY_DOMAIN . nv_url_rewrite( $base_url, true);
    }
}

$bodytext = '';
if( isset( $array_department[$fpart] ) and ! empty( $array_department[$fpart]['note'] ) )
{
	$bodytext = $array_department[$fpart]['note'];
}
elseif( isset( $module_config[$module_name]['bodytext'] ) )
{
	$bodytext = $module_config[$module_name]['bodytext'];
}

if( ! empty( $bodytext ) )
{
	$lang_module['note'] = $bodytext;
}

$array_content = array(
	'error' => $error,
	'fpart' => $fpart,
	'fname' => $fname,
	'femail' => $femail,
	'fcon' => $fcon,
	'ftitle' => $ftitle,
	'fphone' => $fphone
);

$checkss = md5( $client_info['session_id'] . $global_config['sitekey'] );
$contents = contact_main_theme( $array_content, $array_department, $base_url, $checkss );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents, $full );
include NV_ROOTDIR . '/includes/footer.php';