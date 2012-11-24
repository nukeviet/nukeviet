<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES. All rights reserved
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
	global $db, $module_data;

	$email_list = array();

	$sql = "SELECT `email`, `admins` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id` =" . $cid;
	$result = $db->sql_query( $sql );
	list( $email, $admins ) = $db->sql_fetchrow( $result );

	if( ! empty( $email ) )
	{
		$email_list[] = $email;
	}

	if( ! empty( $admins ) )
	{
		$admins = array_map( "trim", explode( ";", $admins ) );
	
		$a_l = array();
		foreach( $admins as $adm )
		{
			if( preg_match( "/^([0-9]+)\/([0-1]{1})\/([0-1]{1})\/([0-1]{1})$/i", $adm ) )
			{
				$adm2 = array_map( "trim", explode( "/", $adm ) );
			
				if( $adm2[3] == 1 )
				{
					$a_l[] = intval( $adm2[0] );
				}
			}
		}

		if( ! empty( $a_l ) )
		{
			$a_l = implode( ",", $a_l );
		
			$sql = "SELECT t2.email as admin_email FROM `" . NV_AUTHORS_GLOBALTABLE . "` AS t1 INNER JOIN  `" . NV_USERS_GLOBALTABLE . "` AS t2 ON t1.admin_id = t2.userid WHERE t1.lev!=0 AND t1.is_suspend=0 AND t1.admin_id IN (" . $a_l . ")";
			$result = $db->sql_query( $sql );
		
			while( $row = $db->sql_fetchrow( $result ) )
			{
				if( nv_check_valid_email( $row['admin_email'] ) == "" )
				{
					$email_list[] = $row['admin_email'];
				}
			}
		}
	}

	$email_list = array_unique( $email_list );

	if( ! empty( $email_list ) )
	{
		$from = array( $full_name, $femail );
	
		foreach( $email_list as $to )
		{
			@nv_sendmail( $from, $to, $ftitle, $fcontent );
		}
	}
}

//Danh sach cac bo phan
$sql = "SELECT `id`, `full_name`, `phone`, `fax`, `email`, `note` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `act`=1";
$array_rows = nv_db_cache( $sql, 'id' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$mod_title = isset( $lang_module['main_title'] ) ? $lang_module['main_title'] : $module_info['custom_title'];

$fname = "";
$femail = "";
$fphone = "";

if( defined( 'NV_IS_USER' ) )
{
	$fname = ! empty( $user_info['full_name'] ) ? $user_info['full_name'] : $user_info['username'];
	$femail = $user_info['email'];
	$fphone = $user_info['telephone'];
}

$fcon = "";
$fcode = "";
$error = "";
$fpart = isset( $array_op[0] ) ? $array_op[0] : 0;
$fpart = $nv_Request->get_int( 'fpart', 'post,get', $fpart );
$ftitle = filter_text_input( 'ftitle', 'post,get', '', 1, 250 );

if( ! empty( $array_rows ) )
{
	$checkss = filter_text_input( 'checkss', 'post', '' );

	if( $checkss == md5( $client_info['session_id'] . $global_config['sitekey'] ) )
	{
		if( defined( 'NV_IS_USER' ) )
		{
			$fname = ! empty( $user_info['full_name'] ) ? $user_info['full_name'] : $user_info['username'];
			$femail = $user_info['email'];
		}
		else
		{
			$fname = filter_text_input( 'fname', 'post', '', 1, 100 );
			$femail = filter_text_input( 'femail', 'post', '', 1, 100 );
		}

		$fphone = filter_text_input( 'fphone', 'post', '', 1, 100 );
		$fcon = filter_text_textarea( 'fcon', '', NV_ALLOWED_HTML_TAGS );
		$fcode = filter_text_input( 'fcode', 'post', '' );

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
		elseif( ! isset( $array_rows[$fpart] ) )
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

			$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_send` VALUES 
            (NULL , " . $fpart . ", " . $db->dbescape( $ftitle ) . ", " . $db->dbescape( $fcon ) . ", 
            " . NV_CURRENTTIME . ", " . $sender_id . ", " . $db->dbescape( $fname ) . ", " . $db->dbescape( $femail ) . ", 
            " . $db->dbescape( $fphone ) . ", " . $db->dbescape( $client_info['ip'] ) . ", 0, 0, '', 0, 0);";
			$db->sql_query( $sql );

			$website = "<a href=\"" . $global_config['site_url'] . "\">" . $global_config['site_name'] . "</a>";
			$fcon .= "<br /><br />----------------------------------------<br /><br />";
		
			if( empty( $fphone ) )
			{
				$fcon .= sprintf( $lang_module['sendinfo'], $website, $fname, $femail, $client_info['ip'], $array_rows[$fpart]['full_name'] );
			}
			else
			{
				$fcon .= sprintf( $lang_module['sendinfo2'], $website, $fname, $femail, $fphone, $client_info['ip'], $array_rows[$fpart]['full_name'] );
			}
		
			nv_SendMail2User( $fpart, $fcon, $ftitle, $femail, $fname );

			$url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA;
			$contents .= call_user_func( "sendcontact", $url );
		
			include ( NV_ROOTDIR . "/includes/header.php" );
			echo nv_site_theme( $contents );
			include ( NV_ROOTDIR . "/includes/footer.php" );
			exit();
		}
	}
}

$bodytext = "";
$content_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . 'Content.txt';

if( isset( $array_rows[$fpart] ) and ! empty( $array_rows[$fpart]['note'] ) )
{
	$bodytext = $array_rows[$fpart]['note'];
}
elseif( file_exists( $content_file ) )
{
	$bodytext = file_get_contents( $content_file );
}

$array_content = array( //
	"error" => $error, //
	"fpart" => $fpart, //
	"bodytext" => $bodytext, //
	"fname" => $fname, //
	"femail" => $femail, //
	"fcon" => $fcon, //
	"ftitle" => $ftitle, //
	'fphone' => $fphone //
);

$checkss = md5( $client_info['session_id'] . $global_config['sitekey'] );
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$contents = call_user_func( "main_theme", $array_content, $array_rows, $base_url, $checkss );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>