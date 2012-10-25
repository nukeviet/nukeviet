<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 10/03/2010 10:51
 */

if ( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

if ( ! defined( 'NV_IS_USER' ) or ! $global_config['allowuserlogin'] )
{
    Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
    die();
}

if ( defined( 'NV_IS_USER_FORUM' ) )
{
    require_once ( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/editinfo.php' );
    exit();
}

/**
 * nv_check_username_change()
 * 
 * @param mixed $login
 * @return
 */
function nv_check_username_change ( $login )
{
    global $db, $lang_module, $user_info;
    
    $error = nv_check_valid_login( $login, NV_UNICKMAX, NV_UNICKMIN );
    if ( $error != "" ) return preg_replace( "/\&(l|r)dquo\;/", "", strip_tags( $error ) );
    if ( $login != $db->fixdb( $login ) )
    {
        return sprintf( $lang_module['account_deny_name'], $login );
    }
    
    $sql = "SELECT `content` FROM `" . NV_USERS_GLOBALTABLE . "_config` WHERE `config`='deny_name'";
    $result = $db->sql_query( $sql );
    list( $deny_name ) = $db->sql_fetchrow( $result );
    $db->sql_freeresult();
    
    if ( ! empty( $deny_name ) and preg_match( "/" . $deny_name . "/i", $login ) ) return sprintf( $lang_module['account_deny_name'], $login );
    
    $sql = "SELECT `userid` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`!=" . $user_info['userid'] . " AND `username`=" . $db->dbescape( $login );
    if ( $db->sql_numrows( $db->sql_query( $sql ) ) != 0 ) return sprintf( $lang_module['account_registered_name'], $login );
    
    $sql = "SELECT `userid` FROM `" . NV_USERS_GLOBALTABLE . "_reg` WHERE `userid`!=" . $user_info['userid'] . " AND `username`=" . $db->dbescape( $login );
    if ( $db->sql_numrows( $db->sql_query( $sql ) ) != 0 ) return sprintf( $lang_module['account_registered_name'], $login );
    
    return "";
}

/**
 * nv_check_email_change()
 * 
 * @param mixed $email
 * @return
 */
function nv_check_email_change ( $email )
{
    global $db, $lang_module, $user_info;
    
    $error = nv_check_valid_email( $email );
    if ( $error != "" ) return preg_replace( "/\&(l|r)dquo\;/", "", strip_tags( $error ) );
    
    $sql = "SELECT `content` FROM `" . NV_USERS_GLOBALTABLE . "_config` WHERE `config`='deny_email'";
    $result = $db->sql_query( $sql );
    list( $deny_email ) = $db->sql_fetchrow( $result );
    $db->sql_freeresult();
    
    if ( ! empty( $deny_email ) and preg_match( "/" . $deny_email . "/i", $email ) ) return sprintf( $lang_module['email_deny_name'], $email );
    
    list( $left, $right ) = explode( "@", $email );
    $left = preg_replace( "/[\.]+/", "", $left );
    $pattern = str_split( $left );
    $pattern = implode( ".?", $pattern );
    $pattern = "^" . $pattern . "@" . $right . "$";
    
    $sql = "SELECT `userid` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`!=" . $user_info['userid'] . " AND `email` RLIKE " . $db->dbescape( $pattern );
    if ( $db->sql_numrows( $db->sql_query( $sql ) ) != 0 ) return sprintf( $lang_module['email_registered_name'], $email );
    
    $sql = "SELECT `userid` FROM `" . NV_USERS_GLOBALTABLE . "_reg` WHERE `email` RLIKE " . $db->dbescape( $pattern );
    if ( $db->sql_numrows( $db->sql_query( $sql ) ) != 0 ) return sprintf( $lang_module['email_registered_name'], $email );
    
    $sql = "SELECT `userid` FROM `" . NV_USERS_GLOBALTABLE . "_openid` WHERE `userid`!=" . $user_info['userid'] . " AND `email` RLIKE " . $db->dbescape( $pattern );
    if ( $db->sql_numrows( $db->sql_query( $sql ) ) != 0 ) return sprintf( $lang_module['email_registered_name'], $email );
    
    return "";
}

$sql = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $user_info['userid'];
$query = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $query );

$array_data = array();
$array_data['checkss'] = md5( $client_info['session_id'] . $global_config['sitekey'] );
$checkss = filter_text_input( 'checkss', 'post', '' );

//Thay doi cau hoi - cau tra loi du phong
if ( $nv_Request->isset_request( 'changequestion', 'get' ) )
{
    $oldpassword = $row['password'];
    $oldquestion = $row['question'];
    $oldanswer = $row['answer'];
    
    $page_title = $mod_title = $lang_module['change_question_pagetitle'];
    $key_words = $module_info['keywords'];
    
    $array_data['your_question'] = $oldquestion;
    $array_data['answer'] = $oldanswer;
    $array_data['nv_password'] = filter_text_input( 'nv_password', 'post', '' );
    $array_data['send'] = $nv_Request->get_bool( 'send', 'post', false );
    
    $step = 1;
    $error = "";
    
    if ( empty( $oldpassword ) )
    {
        $step = 2;
    }
    else
    {
        if ( $checkss == $array_data['checkss'] )
        {
            if ( $crypt->validate( $array_data['nv_password'], $oldpassword ) or $array_data['nv_password'] == md5( $oldpassword ) )
            {
                $step = 2;
                
                if ( ! isset( $array_data['nv_password']{31} ) )
                {
                    $array_data['nv_password'] = md5( $crypt->hash( $array_data['nv_password'] ) );
                }
            }
            else
            {
                $step = 1;
                $error = $lang_global['incorrect_password'];
            }
        }
    }
    
    if ( $step == 2 )
    {
        if ( $array_data['send'] )
        {
            $array_data['your_question'] = filter_text_input( 'your_question', 'post', '', 1, 255 );
            $array_data['answer'] = filter_text_input( 'answer', 'post', '', 1, 255 );
            
            if ( empty( $array_data['your_question'] ) )
            {
                $error = $lang_module['your_question_empty'];
            }
            elseif ( empty( $array_data['answer'] ) )
            {
                $error = $lang_module['answer_empty'];
            }
            else
            {
                $sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "` 
                SET `question`=" . $db->dbescape( $array_data['your_question'] ) . ", 
                `answer`=" . $db->dbescape( $array_data['answer'] ) . " 
                WHERE `userid`=" . $user_info['userid'];
                $db->sql_query( $sql );
                
                $contents = user_info_exit( $lang_module['change_question_ok'] );
                $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name, true ) . "\" />";
                
                include ( NV_ROOTDIR . "/includes/header.php" );
                echo nv_site_theme( $contents );
                include ( NV_ROOTDIR . "/includes/footer.php" );
                exit();
            }
        }
    }
    
    $array_data['step'] = $step;
    $array_data['info'] = empty( $error ) ? $lang_module['changequestion_step' . $array_data['step']] : "<span style=\"color:#fb490b;\">" . $error . "</span>";
    
    if ( $step == 2 )
    {
        $array_data['questions'] = array();
        $array_data['questions'][] = $lang_module['select_question'];
        $sql = "SELECT `title` FROM `" . NV_USERS_GLOBALTABLE . "_question`  WHERE `lang`='" . NV_LANG_DATA . "' ORDER BY `weight` ASC";
        $result = $db->sql_query( $sql );
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            $array_data['questions'][$row['title']] = $row['title'];
        }
    }
    
    $contents = user_changequestion( $array_data );
    
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_site_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}

//Thay doi thong tin khac
$page_title = $mod_title = $lang_module['editinfo_pagetitle'];
$key_words = $module_info['keywords'];

$array_data['username'] = $row['username'];
$array_data['email'] = $row['email'];
$array_data['photo'] = $row['photo'];

$array_data['allowmailchange'] = $global_config['allowmailchange'];
$array_data['allowloginchange'] = ( $global_config['allowloginchange'] or ( ! empty( $row['last_openid'] ) and empty( $user_info['last_login'] ) and empty( $user_info['last_agent'] ) and empty( $user_info['last_ip'] ) and empty( $user_info['last_openid'] ) ) ) ? 1 : 0;

if ( $checkss == $array_data['checkss'] )
{
    $error = array();
    $array_data['full_name'] = filter_text_input( 'full_name', 'post', '', 1, 255 );
    $array_data['gender'] = filter_text_input( 'gender', 'post', '', 1, 1 );
    $array_data['birthday'] = filter_text_input( 'birthday', 'post', '', 0, 10 );
    $array_data['website'] = filter_text_input( 'website', 'post', '', 0, 255 );
    $array_data['address'] = filter_text_input( 'address', 'post', '', 1, 255 );
    $array_data['yim'] = filter_text_input( 'yim', 'post', '', 1, 100 );
    $array_data['telephone'] = filter_text_input( 'telephone', 'post', '', 1, 100 );
    $array_data['fax'] = filter_text_input( 'fax', 'post', '', 1, 100 );
    $array_data['mobile'] = filter_text_input( 'mobile', 'post', '', 1, 100 );
    $array_data['view_mail'] = $nv_Request->get_int( 'view_mail', 'post', 0 );
    
    if ( $array_data['allowloginchange'] )
    {
        $array_data['username'] = filter_text_input( 'username', 'post', '', 1, NV_UNICKMAX );
        if ( nv_check_username_change( $array_data['username'] ) != "" )
        {
            $array_data['username'] = $row['username'];
            $error[] = $lang_module['account'];
        }
    }
    
    if ( empty( $array_data['full_name'] ) )
    {
        $array_data['full_name'] = $row['full_name'];
        $error[] = $lang_module['name'];
        if ( empty( $array_data['full_name'] ) )
        {
            $array_data['full_name'] = $row['username'];
        }
    }
    
    if ( $array_data['gender'] != "M" and $array_data['gender'] != "F" ) $array_data['gender'] = "";
    
    if ( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $array_data['birthday'], $m ) )
    {
        $array_data['birthday'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
    }
    else
    {
        $array_data['birthday'] = 0;
    }
    
    if ( ! empty( $array_data['yim'] ) and ! preg_match( "/^([a-zA-Z0-9\_\.]+)$/", $array_data['yim'] ) )
    {
        $array_data['yim'] = $row['yim'];
        $error[] = $lang_module['yahoo'];
    }
    
    if ( $array_data['gender'] == "N" ) $array_data['gender'] = "";
    
    if ( ! empty( $array_data['website'] ) )
    {
        if ( ! preg_match( "#^(http|https|ftp|gopher)\:\/\/#", $array_data['website'] ) )
        {
            $array_data['website'] = "http://" . $array_data['website'];
        }
        if ( ! nv_is_url( $array_data['website'] ) )
        {
            $array_data['website'] = $row['website'];
            $error[] = $lang_module['website'];
        }
    }
    
    if ( $array_data['view_mail'] != 1 ) $array_data['view_mail'] = 0;
    
    if ( $array_data['allowmailchange'] )
    {
        $email_new = filter_text_input( 'email', 'post', '', 1, 100 );
        if ( $email_new != $row['email'] )
        {
            $checknum = nv_genpass( 10 );
            $checknum = md5( $checknum . $email_new );
            $md5_username = md5( $array_data['username'] );
            
            $sql = "DELETE FROM `" . NV_USERS_GLOBALTABLE . "_reg` WHERE `md5username`=" . $db->dbescape( $md5_username );
            $db->sql_query( $sql );
            $error_email_change = nv_check_email_change( $email_new );
            if ( ! empty( $error_email_change ) )
            {
                $error[] = $error_email_change;
            }
            else
            {
                $sql = "INSERT INTO `" . NV_USERS_GLOBALTABLE . "_reg` VALUES (
                NULL, 
                'CHANGE_EMAIL_USERID_" . $user_info['userid'] . "', 
                " . $db->dbescape( $md5_username ) . ", 
                '', 
                " . $db->dbescape( $email_new ) . ", 
                '', 
                " . NV_CURRENTTIME . ", 
                '', 
                '', 
                " . $db->dbescape( $checknum ) . ")";
                $userid_check = $db->sql_query_insert_id( $sql );
                
                if ( $userid_check > 0 )
                {
                    $subject = $lang_module['email_active'];
                    $message = sprintf( $lang_module['email_active_info'], $array_data['full_name'], $array_data['username'], NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=active&userid=" . $userid_check . "&checknum=" . $checknum, nv_date( "H:i d/m/Y", NV_CURRENTTIME + 86400 ), $global_config['site_name'] );
                    $message .= "<br /><br />------------------------------------------------<br /><br />";
                    if ( NV_LANG_DATA == 'vi' ) $message .= nv_EncString( $message );
                    $send = nv_sendmail( $global_config['site_email'], $email_new, $subject, $message );
                    if ( $send )
                    {
                        $error[] = $lang_module['email_active_mes'];
                    }
                    else
                    {
                        $error[] = $lang_module['email_active_error_mail'];
                    }
                }
            }
        }
    }
    
    $sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "` SET 
    `username`=" . $db->dbescape_string( $array_data['username'] ) . ", 
    `md5username`=" . $db->dbescape_string( md5( $array_data['username'] ) ) . ", 
    `email`=" . $db->dbescape_string( $array_data['email'] ) . ", 
    `full_name`=" . $db->dbescape_string( $array_data['full_name'] ) . ", 
    `gender`=" . $db->dbescape_string( $array_data['gender'] ) . ", 
    `birthday`=" . $db->dbescape( $array_data['birthday'] ) . ", 
    `website`=" . $db->dbescape_string( $array_data['website'] ) . ", 
    `location`=" . $db->dbescape_string( $array_data['address'] ) . ", 
    `yim`=" . $db->dbescape_string( $array_data['yim'] ) . ", 
    `telephone`=" . $db->dbescape_string( $array_data['telephone'] ) . ", 
    `fax`=" . $db->dbescape_string( $array_data['fax'] ) . ", 
    `mobile`=" . $db->dbescape_string( $array_data['mobile'] ) . ", 
    `view_mail`=" . $db->dbescape_string( $array_data['view_mail'] ) . " 
    WHERE `userid`=" . $user_info['userid'];
    $db->sql_query( $sql );
    
    if ( isset( $_FILES['avatar'] ) and is_uploaded_file( $_FILES['avatar']['tmp_name'] ) )
    {
        @require_once ( NV_ROOTDIR . "/includes/class/upload.class.php" );
        
        $upload = new upload( array( 'images' ), $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT );
        $upload_info = $upload->save_file( $_FILES['avatar'], NV_UPLOADS_REAL_DIR . '/' . $module_name, false );
        
        @unlink( $_FILES['avatar']['tmp_name'] );
        
        if ( empty( $upload_info['error'] ) )
        {
            @chmod( $upload_info['name'], 0644 );
            
            if ( ! empty( $array_data['photo'] ) and is_file( NV_ROOTDIR . '/' . $array_data['photo'] ) )
            {
                @nv_deletefile( NV_ROOTDIR . '/' . $array_data['photo'] );
            }
            
            $image = $upload_info['name'];
			$basename = $upload_info['basename'];

			$imginfo = nv_is_image( $image );

			$basename = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . $user_info['userid'] . '-' . 80 . '-' . 80 . '\2', $basename );

			$_image = new image( $image, 80, 80 );
			$_image->resizeXY( 80, 80 );
			$_image->save( NV_UPLOADS_REAL_DIR . '/' . $module_name, $basename );
			if( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $basename ) )
			{
				$file_name = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $basename;
				//@chmod($file_name, 0644);
				$file_name = str_replace( NV_ROOTDIR . "/", "", $file_name );
				@nv_deletefile( $upload_info['name'] );
			}
            
            $sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "` SET `photo`=" . $db->dbescape_string( $file_name ) . " WHERE `userid`=" . $user_info['userid'];
            $db->sql_query( $sql );
        }
        else
        {
            $error[] = $lang_module['avata'];
        }
    }
    
    $info = $lang_module['editinfo_ok'];
    $sec = 3;
    if ( ! empty( $error ) )
    {
        $error = implode( "<br />", $error );
        $info = $info . ", " . sprintf( $lang_module['editinfo_error'], "<span style=\"color:#fb490b;\">" . $error . "</span>" );
        $sec = 5;
    }
    
    $contents = user_info_exit( $info );
    $contents .= "<meta http-equiv=\"refresh\" content=\"" . $sec . ";url=" . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name, true ) . "\" />";
    
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_site_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}
else
{
    $array_data['full_name'] = $row['full_name'];
    $array_data['gender'] = $row['gender'];
    $array_data['birthday'] = ! empty( $row['birthday'] ) ? date( "d.m.Y", $row['birthday'] ) : "";
    $array_data['website'] = $row['website'];
    $array_data['address'] = $row['location'];
    $array_data['yim'] = $row['yim'];
    $array_data['telephone'] = $row['telephone'];
    $array_data['fax'] = $row['fax'];
    $array_data['mobile'] = $row['mobile'];
    $array_data['view_mail'] = intval( $row['view_mail'] );
}

$array_data['view_mail'] = $array_data['view_mail'] ? " selected=\"selected\"" : "";

$array_data['gender_array'] = array();
$array_data['gender_array']['N'] = array( 'value' => 'N', 'title' => 'N/A', 'selected' => '' );
$array_data['gender_array']['M'] = array( 'value' => 'M', 'title' => $lang_module['male'], 'selected' => ( $array_data['gender'] == 'M' ? " selected=\"selected\"" : "" ) );
$array_data['gender_array']['F'] = array( 'value' => 'F', 'title' => $lang_module['female'], 'selected' => ( $array_data['gender'] == 'F' ? " selected=\"selected\"" : "" ) );

$contents = user_info( $array_data );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>