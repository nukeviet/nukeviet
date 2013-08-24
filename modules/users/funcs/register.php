<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

// Dang nhap thanh vien thi khong duoc truy cap
if( defined( 'NV_IS_USER' ) )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	die();
}

// Chuyen trang dang ki neu tich hop dien dan
if( defined( 'NV_IS_USER_FORUM' ) )
{
	require_once ( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/register.php' );
	exit();
}

$nv_redirect = $nv_Request->get_title( 'nv_redirect', 'post,get', '' );

// Ngung dang ki thanh vien
if( ! $global_config['allowuserreg'] )
{
	$page_title = $lang_module['register'];
	$key_words = $module_info['keywords'];
	$mod_title = $lang_module['register'];

	$contents = user_info_exit( $lang_module['no_allowuserreg'] );
	$contents .= "<meta http-equiv=\"refresh\" content=\"5;url=" . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name, true ) . "\" />";

	include ( NV_ROOTDIR . '/includes/header.php' );
	echo nv_site_theme( $contents );
	include ( NV_ROOTDIR . '/includes/footer.php' );
	exit();
}

/**
 * nv_check_username_reg()
 * Ham kiem tra ten dang nhap kha dung
 *
 * @param mixed $login
 * @return
 */
function nv_check_username_reg( $login )
{
	global $db, $db_config, $lang_module;

	$error = nv_check_valid_login( $login, NV_UNICKMAX, NV_UNICKMIN );
	if( $error != '' ) return preg_replace( "/\&(l|r)dquo\;/", '', strip_tags( $error ) );
	if( $login != $db->fixdb( $login ) ) return sprintf( $lang_module['account_deny_name'], '<strong>' . $login . '</strong>' );

	$sql = "SELECT `content` FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_config` WHERE `config`='deny_name'";
	$result = $db->sql_query( $sql );
	list( $deny_name ) = $db->sql_fetchrow( $result );
	$db->sql_freeresult();

	if( ! empty( $deny_name ) and preg_match( "/" . $deny_name . "/i", $login ) ) return sprintf( $lang_module['account_deny_name'], '<strong>' . $login . '</strong>' );

	$sql = "SELECT `userid` FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` WHERE `md5username`=" . $db->dbescape( md5( $login ) );
	if( $db->sql_numrows( $db->sql_query( $sql ) ) != 0 ) return sprintf( $lang_module['account_registered_name'], '<strong>' . $login . '</strong>' );

	$sql = "SELECT `userid` FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_reg` WHERE `md5username`=" . $db->dbescape( md5( $login ) );
	if( $db->sql_numrows( $db->sql_query( $sql ) ) != 0 ) return sprintf( $lang_module['account_registered_name'], '<strong>' . $login . '</strong>' );

	return '';
}

/**
 * nv_check_email_reg()
 * Ham kiem tra email kha dung
 *
 * @param mixed $email
 * @return
 */
function nv_check_email_reg( $email )
{
	global $db, $db_config, $lang_module;

	$error = nv_check_valid_email( $email );
	if( $error != '' ) return preg_replace( "/\&(l|r)dquo\;/", '', strip_tags( $error ) );

	$sql = "SELECT `content` FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_config` WHERE `config`='deny_email'";
	$result = $db->sql_query( $sql );
	list( $deny_email ) = $db->sql_fetchrow( $result );
	$db->sql_freeresult();

	if( ! empty( $deny_email ) and preg_match( "/" . $deny_email . "/i", $email ) ) return sprintf( $lang_module['email_deny_name'], $email );

	list( $left, $right ) = explode( "@", $email );
	$left = preg_replace( "/[\.]+/", '', $left );
	$pattern = str_split( $left );
	$pattern = implode( ".?", $pattern );
	$pattern = "^" . $pattern . "@" . $right . "$";

	$sql = "SELECT `userid` FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` WHERE `email` RLIKE " . $db->dbescape( $pattern );
	if( $db->sql_numrows( $db->sql_query( $sql ) ) != 0 ) return sprintf( $lang_module['email_registered_name'], $email );

	$sql = "SELECT `userid` FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_reg` WHERE `email`RLIKE " . $db->dbescape( $pattern );
	if( $db->sql_numrows( $db->sql_query( $sql ) ) != 0 ) return sprintf( $lang_module['email_registered_name'], $email );

	$sql = "SELECT `userid` FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_openid` WHERE `email` RLIKE " . $db->dbescape( $pattern );
	if( $db->sql_numrows( $db->sql_query( $sql ) ) != 0 ) return sprintf( $lang_module['email_registered_name'], $email );

	return '';
}

// Cau hoi lay lai mat khau
$data_questions = array();
$data_questions[0] = array(
	'qid' => 0,
	'title' => $lang_module['select_question'],
	'selected' => ''
);
$sql = "SELECT `qid`, `title` FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_question` WHERE `lang`='" . NV_LANG_DATA . "' ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
while( $row = $db->sql_fetchrow( $result ) )
{
	$data_questions[$row['qid']] = array(
		'qid' => $row['qid'],
		'title' => $row['title'],
		'selected' => ''
	);
}

// Captcha
$gfx_chk = ( in_array( $global_config['gfx_chk'], array( 3, 4, 6, 7 ) ) ) ? 1 : 0;

$array_register = array();
$array_register['checkss'] = md5( $client_info['session_id'] . $global_config['sitekey'] );
$array_register['nv_redirect'] = $nv_redirect;
$checkss = $nv_Request->get_title( 'checkss', 'post', '' );

$contents = $error = '';

// Dang ky qua OpenID
if( defined( 'NV_OPENID_ALLOWED' ) and $nv_Request->get_bool( 'openid', 'get', false ) )
{
	$page_title = $lang_module['openid_register'];
	$key_words = $module_info['keywords'];
	$mod_title = $lang_module['openid_register'];

	$reg_attribs = $nv_Request->get_string( 'reg_attribs', 'session', '' );
	$reg_attribs = ! empty( $reg_attribs ) ? unserialize( $reg_attribs ) : array();

	if( empty( $reg_attribs ) or ! isset( $reg_attribs['username'] ) or ! isset( $reg_attribs['email'] ) or nv_check_valid_email( $reg_attribs['email'] ) != '' )
	{
		$nv_Request->unset_request( 'reg_attribs', 'session' );

		Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=register&nv_redirect=" . $nv_redirect, true ) );
		exit();
	}

	if( nv_check_email_reg( $reg_attribs['email'] ) != '' )
	{
		$nv_Request->unset_request( 'reg_attribs', 'session' );

		Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=register&nv_redirect=" . $nv_redirect, true ) );
		exit();
	}

	if( $checkss == $array_register['checkss'] )
	{
		$array_register['username'] = $nv_Request->get_title( 'username', 'post', '', 1 );
		$array_register['password'] = $nv_Request->get_title( 'password', 'post', '' );
		$array_register['re_password'] = $nv_Request->get_title( 're_password', 'post', '' );
		$array_register['question'] = $nv_Request->get_int( 'question', 'post', 0 );
		if( ! isset( $data_questions[$array_register['question']] ) ) $array_register['question'] = 0;

		$data_questions[$array_register['question']]['selected'] = " seleted=\"selected\"";

		$array_register['your_question'] = $nv_Request->get_title( 'your_question', 'post', '', 1 );
		$array_register['answer'] = $nv_Request->get_title( 'answer', 'post', '', 1, 255 );
		$array_register['agreecheck'] = $nv_Request->get_int( 'agreecheck', 'post', 0 );
		$nv_seccode = $nv_Request->get_title( 'nv_seccode', 'post', '' );

		$check_seccode = ! $gfx_chk ? true : ( nv_capcha_txt( $nv_seccode ) ? true : false );

		if( ! $check_seccode )
		{
			$error = $lang_global['securitycodeincorrect'];
		}
		elseif( ( $check_login = nv_check_username_reg( $array_register['username'] ) ) != '' )
		{
			$error = $check_login;
		}
		elseif( ! empty( $array_register['password'] ) and ( $check_pass = nv_check_valid_pass( $array_register['password'], NV_UPASSMAX, NV_UPASSMIN ) ) != '' )
		{
			$error = $check_pass;
		}
		elseif( ! empty( $array_register['password'] ) and $array_register['password'] != $array_register['re_password'] )
		{
			$error = sprintf( $lang_global['passwordsincorrect'], $array_register['password'], $array_register['re_password'] );
		}
		elseif( empty( $array_register['your_question'] ) and empty( $array_register['question'] ) )
		{
			$error = $lang_module['your_question_empty'];
		}
		elseif( empty( $array_register['answer'] ) )
		{
			$error = $lang_module['answer_empty'];
		}
		elseif( empty( $array_register['agreecheck'] ) )
		{
			$error = $lang_module['agreecheck_empty'];
		}
		else
		{
			$nv_Request->unset_request( 'reg_attribs', 'session' );

			$password = ! empty( $array_register['password'] ) ? $crypt->hash( $array_register['password'] ) : '';
			$your_question = ! empty( $array_register['your_question'] ) ? $array_register['your_question'] : $data_questions[$array_register['question']]['title'];
			if( empty( $reg_attribs['full_name'] ) ) $reg_attribs['full_name'] = $array_register['username'];

			$sql = "INSERT INTO `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "`
				(`userid`, `username`, `md5username`, `password`, `email`, `full_name`, `gender`, `photo`, `birthday`, `regdate`,
				`question`, `answer`, `passlostkey`, `view_mail`, `remember`, `in_groups`,
				`active`, `checknum`, `last_login`, `last_ip`, `last_agent`, `last_openid`, `idsite`)
				VALUES (
				NULL,
				" . $db->dbescape( $array_register['username'] ) . ",
				" . $db->dbescape( md5( $array_register['username'] ) ) . ",
				" . $db->dbescape( $password ) . ",
				" . $db->dbescape( $reg_attribs['email'] ) . ",
				" . $db->dbescape( $reg_attribs['full_name'] ) . ",
				" . $db->dbescape( $reg_attribs['gender'] ) . ",
				'', 0, " . NV_CURRENTTIME . ",
				" . $db->dbescape( $your_question ) . ",
				" . $db->dbescape( $array_register['answer'] ) . ",
				'', 0, 1, '', 1, '', 0, '', '', '', ".$global_config['idsite'].")";

			$userid = $db->sql_query_insert_id( $sql );

			if( ! $userid )
			{
				$contents = user_info_exit( $lang_module['err_no_save_account'] );
				$contents .= "<meta http-equiv=\"refresh\" content=\"3;url=" . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=register&nv_redirect=" . $nv_redirect, true ) . "\" />";

				include ( NV_ROOTDIR . '/includes/header.php' );
				echo nv_site_theme( $contents );
				include ( NV_ROOTDIR . '/includes/footer.php' );
				exit();
			}

			$query_field = array();
			$query_field['`userid`'] = $userid;
			$result_field = $db->sql_query( "SELECT * FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_field` ORDER BY `fid` ASC" );
			while( $row_f = $db->sql_fetch_assoc( $result_field ) )
			{
				$query_field["`" . $row_f['field'] . "`"] = $db->dbescape( $row_f['default_value'] );
			}
			$db->sql_query( "INSERT INTO `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_info` (" . implode( ', ', array_keys( $query_field ) ) . ") VALUES (" . implode( ', ', array_values( $query_field ) ) . ")" );

			$sql = "INSERT INTO `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_openid` VALUES (" . $userid . ", " . $db->dbescape( $reg_attribs['openid'] ) . ", " . $db->dbescape( $reg_attribs['opid'] ) . ", " . $db->dbescape( $reg_attribs['email'] ) . ")";
			$db->sql_query( $sql );

			$query = "SELECT * FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $userid . " AND `active`=1";
			$result = $db->sql_query( $query );
			$row = $db->sql_fetchrow( $result );
			$db->sql_freeresult( $result );

			validUserLog( $row, 1, $reg_attribs['opid'] );

			$subject = $lang_module['account_register'];
			$message = sprintf( $lang_module['openid_register_info'], $reg_attribs['full_name'], $global_config['site_name'], NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, $array_register['username'], $array_register['password'], $reg_attribs['openid'] );
			$message .= "<br /><br />------------------------------------------------<br /><br />";
			$message .= nv_EncString( $message );
			@nv_sendmail( $global_config['site_email'], $reg_attribs['email'], $subject, $message );

			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['register'], $array_register['username'] . " | " . $client_info['ip'] . " | OpenID", 0 );

			$nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;

			Header( "Location: " . $nv_redirect );
			exit();
		}

		$array_register['info'] = "<span style=\"color:#fb490b;\">" . $error . "</span>";
	}
	else
	{
		$array_register['username'] = $reg_attribs['username'];
		$array_register['password'] = $array_register['re_password'] = $array_register['your_question'] = $array_register['answer'] = '';
		$array_register['question'] = $array_register['agreecheck'] = 0;
		$array_register['info'] = $lang_module['openid_register'];
	}

	$array_register['agreecheck'] = $array_register['agreecheck'] ? " checked=\"checked\"" : '';

	$sql = "SELECT `content` FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_config` WHERE `config`='siteterms_" . NV_LANG_DATA . "'";
	$result = $db->sql_query( $sql );
	list( $siteterms ) = $db->sql_fetchrow( $result );
	$db->sql_freeresult();

	$contents = openid_register( $gfx_chk, $array_register, $siteterms, $data_questions );

	include ( NV_ROOTDIR . '/includes/header.php' );
	echo nv_site_theme( $contents );
	include ( NV_ROOTDIR . '/includes/footer.php' );
	exit();
}

// Dang ky thong thuong
$page_title = $lang_module['register'];
$key_words = $module_info['keywords'];
$mod_title = $lang_module['register'];

$array_field_config = array();
$result_field = $db->sql_query( "SELECT * FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_field` ORDER BY `weight` ASC" );
while( $row_field = $db->sql_fetch_assoc( $result_field ) )
{
	$language = unserialize( $row_field['language'] );
	$row_field['title'] = ( isset( $language[NV_LANG_DATA] ) ) ? $language[NV_LANG_DATA][0] : $row['field'];
	$row_field['description'] = ( isset( $language[NV_LANG_DATA] ) ) ? nv_htmlspecialchars( $language[NV_LANG_DATA][1] ) : '';
	if( ! empty( $row_field['field_choices'] ) ) $row_field['field_choices'] = unserialize( $row_field['field_choices'] );
	elseif( ! empty( $row_field['sql_choices'] ) )
	{
		$row_field['sql_choices'] = explode( "|", $row_field['sql_choices'] );
		$query = "SELECT `" . $row_field['sql_choices'][2] . "`, `" . $row_field['sql_choices'][3] . "` FROM `" . $row_field['sql_choices'][1] . "`";
		$result = $db->sql_query( $query );
		$weight = 0;
		while( list( $key, $val ) = $db->sql_fetchrow( $result ) )
		{
			$row_field['field_choices'][$key] = $val;
		}
	}
	$array_field_config[] = $row_field;
}
if( defined( 'NV_EDITOR' ) )
{
	require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}
elseif( ! nv_function_exists( 'nv_aleditor' ) and file_exists( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor_php5.php' ) )
{
	define( 'NV_EDITOR', true );
	define( 'NV_IS_CKEDITOR', true );
	require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor_php5.php' );

	/**
	 * nv_aleditor()
	 *
	 * @param mixed $textareaname
	 * @param string $width
	 * @param string $height
	 * @param string $val
	 * @return
	 */
	function nv_aleditor( $textareaname, $width = "100%", $height = '450px', $val = '' )
	{
		// Create class instance.
		$editortoolbar = array( array( 'Link', 'Unlink', 'Image', 'Table', 'Font', 'FontSize', 'RemoveFormat' ), array( 'Bold', 'Italic', 'Underline', 'StrikeThrough', '-', 'Subscript', 'Superscript', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'OrderedList', 'UnorderedList', '-', 'Outdent', 'Indent', 'TextColor', 'BGColor', 'Source' ) );
		$CKEditor = new CKEditor();
		// Do not print the code directly to the browser, return it instead
		$CKEditor->returnOutput = true;
		$CKEditor->config['skin'] = 'kama';
		$CKEditor->config['entities'] = false;
		// $CKEditor->config['enterMode'] = 2;
		$CKEditor->config['language'] = NV_LANG_INTERFACE;
		$CKEditor->config['toolbar'] = $editortoolbar;
		// Path to CKEditor directory, ideally instead of relative dir, use an
		// absolute path:
		// $CKEditor->basePath = '/ckeditor/'
		// If not set, CKEditor will try to detect the correct path.
		$CKEditor->basePath = NV_BASE_SITEURL . '' . NV_EDITORSDIR . '/ckeditor/';
		// Set global configuration (will be used by all instances of CKEditor).
		if( ! empty( $width ) )
		{
			$CKEditor->config['width'] = strpos( $width, '%' ) ? $width : intval( $width );
		}
		if( ! empty( $height ) )
		{
			$CKEditor->config['height'] = strpos( $height, '%' ) ? $height : intval( $height );
		}
		// Change default textarea attributes
		$CKEditor->textareaAttributes = array( "cols" => 80, "rows" => 10 );
		$val = nv_unhtmlspecialchars( $val );
		return $CKEditor->editor( $textareaname, $val );
	}
}

$custom_fields = $nv_Request->get_array( 'custom_fields', 'post' );
if( $checkss == $array_register['checkss'] )
{
	$array_register['full_name'] = nv_substr( $nv_Request->get_title( 'full_name', 'post', '', 1 ), 0, 255 );
	$array_register['username'] = $nv_Request->get_title( 'username', 'post', '', 1 );
	$array_register['password'] = $nv_Request->get_title( 'password', 'post', '' );
	$array_register['re_password'] = $nv_Request->get_title( 're_password', 'post', '' );
	$array_register['email'] = nv_substr( $nv_Request->get_title( 'email', 'post', '', 1 ), 0, 100);

	$array_register['question'] = $nv_Request->get_int( 'question', 'post', 0 );
	if( ! isset( $data_questions[$array_register['question']] ) ) $array_register['question'] = 0;
	$data_questions[$array_register['question']]['selected'] = " selected=\"selected\"";

	$array_register['your_question'] = $nv_Request->get_title( 'your_question', 'post', '', 1 );
	$array_register['answer'] = nv_substr( $nv_Request->get_title( 'answer', 'post', '', 1 ), 0, 255 );

	$array_register['agreecheck'] = $nv_Request->get_int( 'agreecheck', 'post', 0 );
	$nv_seccode = $nv_Request->get_title( 'nv_seccode', 'post', '' );

	$check_seccode = ! $gfx_chk ? true : ( nv_capcha_txt( $nv_seccode ) ? true : false );

	if( ! $check_seccode )
	{
		$error = $lang_global['securitycodeincorrect'];
	}
	elseif( ( ( $check_login = nv_check_username_reg( $array_register['username'] ) ) ) != '' )
	{
		$error = $check_login;
	}
	elseif( ( $check_email = nv_check_email_reg( $array_register['email'] ) ) != '' )
	{
		$error = $check_email;
	}
	elseif( ( $check_pass = nv_check_valid_pass( $array_register['password'], NV_UPASSMAX, NV_UPASSMIN ) ) != '' )
	{
		$error = $check_pass;
	}
	elseif( $array_register['password'] != $array_register['re_password'] )
	{
		$error = sprintf( $lang_global['passwordsincorrect'], $array_register['password'], $array_register['re_password'] );
	}
	elseif( empty( $array_register['your_question'] ) and empty( $array_register['question'] ) )
	{
		$error = $lang_module['your_question_empty'];
	}
	elseif( empty( $array_register['answer'] ) )
	{
		$error = $lang_module['answer_empty'];
	}
	elseif( empty( $array_register['agreecheck'] ) )
	{
		$error = $lang_module['agreecheck_empty'];
	}
	else
	{
		$query_field = array( '`userid`' => 0 );
		if( ! empty( $array_field_config ) )
		{
			$userid = 0;
			require ( NV_ROOTDIR . "/modules/users/fields.check.php" );
		}
		if( empty( $error ) )
		{
			$password = $crypt->hash( $array_register['password'] );
			$your_question = ! empty( $array_register['your_question'] ) ? $array_register['your_question'] : $data_questions[$array_register['question']]['title'];
			$checknum = nv_genpass( 10 );
			$checknum = md5( $checknum );
			if( empty( $array_register['full_name'] ) ) $array_register['full_name'] = $array_register['username'];

			if( $global_config['allowuserreg'] == 2 or $global_config['allowuserreg'] == 3 )
			{
				$sql = "INSERT INTO `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_reg` VALUES (
					NULL,
					" . $db->dbescape( $array_register['username'] ) . ",
					" . $db->dbescape( md5( $array_register['username'] ) ) . ",
					" . $db->dbescape( $password ) . ",
					" . $db->dbescape( $array_register['email'] ) . ",
					" . $db->dbescape( $array_register['full_name'] ) . ",
					" . NV_CURRENTTIME . ",
					" . $db->dbescape( $your_question ) . ",
					" . $db->dbescape( $array_register['answer'] ) . ",
					" . $db->dbescape( $checknum ) . ", '" . nv_base64_encode( serialize( $query_field ) ) . "'
				)";

				$userid = $db->sql_query_insert_id( $sql );

				if( ! $userid )
				{
					die( $sql );
					$contents = user_info_exit( $lang_module['err_no_save_account'] );
					$contents .= "<meta http-equiv=\"refresh\" content=\"5;url=" . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=register", true ) . "\" />";

					include ( NV_ROOTDIR . '/includes/header.php' );
					echo nv_site_theme( $contents );
					include ( NV_ROOTDIR . '/includes/footer.php' );
					exit();
				}

				if( $global_config['allowuserreg'] == 2 )
				{
					$subject = $lang_module['account_active'];
					$message = sprintf( $lang_module['account_active_info'], $array_register['full_name'], $global_config['site_name'], NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=active&userid=" . $userid . "&checknum=" . $checknum, $array_register['username'], $array_register['email'], $array_register['password'], nv_date( "H:i d/m/Y", NV_CURRENTTIME + 86400 ) );
					$message .= "<br /><br />------------------------------------------------<br /><br />";
					$message .= nv_EncString( $message );
					$send = nv_sendmail( $global_config['site_email'], $array_register['email'], $subject, $message );
					if( $send )
					{
						$info = $lang_module['account_active_mess'] . "<br /><br />\n";
					}
					else
					{
						$info = $lang_module['account_active_mess_error_mail'] . "<br /><br />\n";
					}
				}
				else
				{
					$info = $lang_module['account_register_to_admin'] . "<br /><br />\n";
				}

				$info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
				$info .= "[<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "\">" . $lang_module['redirect_to_login'] . "</a>]";

				$contents = user_info_exit( $info );
				$contents .= "<meta http-equiv=\"refresh\" content=\"5;url=" . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name, true ) . "\" />";

				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['register'], $array_register['username'] . " | " . $client_info['ip'] . " | Simple", 0 );

				include ( NV_ROOTDIR . '/includes/header.php' );
				echo nv_site_theme( $contents );
				include ( NV_ROOTDIR . '/includes/footer.php' );
				exit();
			}
			else
			{
				$sql = "INSERT INTO `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "`
					(`userid`, `username`, `md5username`, `password`, `email`, `full_name`, `gender`, `photo`, `birthday`, `regdate`,
					`question`, `answer`, `passlostkey`, `view_mail`, `remember`, `in_groups`,
					`active`, `checknum`, `last_login`, `last_ip`, `last_agent`, `last_openid`, `idsite`) VALUES (
					NULL,
					" . $db->dbescape( $array_register['username'] ) . ",
					" . $db->dbescape( md5( $array_register['username'] ) ) . ",
					" . $db->dbescape( $password ) . ",
					" . $db->dbescape( $array_register['email'] ) . ",
					" . $db->dbescape( $array_register['full_name'] ) . ",
					'', '', 0, " . NV_CURRENTTIME . ",
					" . $db->dbescape( $your_question ) . ",
					" . $db->dbescape( $array_register['answer'] ) . ",
					'', 0, 1, '', 1, '', 0, '', '', '', ".$global_config['idsite'].")";

				$userid = $db->sql_query_insert_id( $sql );

				if( ! $userid )
				{
					$contents = user_info_exit( $lang_module['err_no_save_account'] );
					$contents .= "<meta http-equiv=\"refresh\" content=\"5;url=" . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=register", true ) . "\" />";

					include ( NV_ROOTDIR . '/includes/header.php' );
					echo nv_site_theme( $contents );
					include ( NV_ROOTDIR . '/includes/footer.php' );
					exit();
				}

				$query_field['`userid`'] = $userid;
				$db->sql_query( "INSERT INTO `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_info` (" . implode( ', ', array_keys( $query_field ) ) . ") VALUES (" . implode( ', ', array_values( $query_field ) ) . ")" );

				$subject = $lang_module['account_register'];
				$message = sprintf( $lang_module['account_register_info'], $array_register['full_name'], $global_config['site_name'], NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, $array_register['username'], $array_register['password'] );
				$message .= "<br /><br />------------------------------------------------<br /><br />";
				$message .= nv_EncString( $message );
				nv_sendmail( $global_config['site_email'], $array_register['email'], $subject, $message );

				$info = $lang_module['register_ok'] . "<br /><br />\n";
				$info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
				$info .= "[<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "\">" . $lang_module['redirect_to_login'] . "</a>]";

				$contents = user_info_exit( $info );
				$contents .= "<meta http-equiv=\"refresh\" content=\"5;url=" . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name, true ) . "\" />";

				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['register'], $array_register['username'] . " | " . $client_info['ip'] . " | Simple", 0 );

				include ( NV_ROOTDIR . '/includes/header.php' );
				echo nv_site_theme( $contents );
				include ( NV_ROOTDIR . '/includes/footer.php' );
				exit();
			}
		}
	}

	$array_register['info'] = "<span style=\"color:#fb490b;\">" . $error . "</span>";
}
else
{
	$array_register['full_name'] = $array_register['username'] = $array_register['email'] = '';
	$array_register['password'] = $array_register['re_password'] = $array_register['your_question'] = $array_register['answer'] = '';
	$array_register['question'] = $array_register['agreecheck'] = 0;
	$array_register['info'] = $lang_module['info'];
}

$array_register['agreecheck'] = $array_register['agreecheck'] ? " checked=\"checked\"" : '';

$sql = "SELECT `content` FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_config` WHERE `config`='siteterms_" . NV_LANG_DATA . "'";
$result = $db->sql_query( $sql );
list( $siteterms ) = $db->sql_fetchrow( $result );
$db->sql_freeresult();

$contents = user_register( $gfx_chk, $array_register, $siteterms, $data_questions, $array_field_config, $custom_fields );

include ( NV_ROOTDIR . '/includes/header.php' );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . '/includes/footer.php' );

?>