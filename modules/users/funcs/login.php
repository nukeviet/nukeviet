<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 10/03/2010 10:51
 */

if ( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

if ( defined( 'NV_IS_USER' ) or ! $global_config['allowuserlogin'] )
{
    Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
    die();
}

$gfx_chk = ( in_array( $global_config['gfx_chk'], array( 2, 4, 5, 7 ) ) ) ? 1 : 0;

/**
 * openidLogin_Res0()
 * Function hien thi cac thong bao loi cua OpenID
 * 
 * @param mixed $info
 * @return
 */
function openidLogin_Res0 ( $info )
{
    global $page_title, $key_words, $mod_title, $module_name, $module_info, $lang_module, $nv_redirect;
    
    $page_title = $lang_module['openid_login'];
    $key_words = $module_info['keywords'];
    $mod_title = $lang_module['openid_login'];
    $contents = user_info_exit( $info );
    $nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
    $contents .= "<meta http-equiv=\"refresh\" content=\"3;url=" . nv_url_rewrite( $nv_redirect ) . "\" />";
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_site_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
}

/**
 * set_reg_attribs()
 * 
 * @param mixed $attribs
 * @return
 */
function set_reg_attribs ( $attribs )
{
    global $crypt, $db;
    
    $reg_attribs = array();
    $reg_attribs['server'] = $attribs['server'];
    $reg_attribs['username'] = "";
    $reg_attribs['email'] = $attribs['contact/email'];
    $reg_attribs['full_name'] = "";
    $reg_attribs['gender'] = "";
    $reg_attribs['yim'] = "";
    $reg_attribs['openid'] = $attribs['id'];
    $reg_attribs['opid'] = $crypt->hash( $attribs['id'] );
    
    $username = explode( "@", $attribs['contact/email'] );
    $username = array_shift( $username );
    
    if ( $attribs['server'] == 'yahoo' )
    {
        $reg_attribs['yim'] = $username;
    }
    
    $username = str_pad( $username, NV_UNICKMIN, "0", STR_PAD_RIGHT );
    $username = substr( $username, 0, ( NV_UNICKMAX - 2 ) );
    $username2 = $username;
    for ( $i = 0; $i < 100; ++$i )
    {
        if ( $i > 0 )
        {
            $username2 = $username . str_pad( $i, 2, "0", STR_PAD_LEFT );
        }
        
        $query = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `username`=" . $db->dbescape( $username2 );
        $result = $db->sql_query( $query );
        $numrows = $db->sql_numrows( $result );
        if ( ! $numrows )
        {
            $query = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "_reg` WHERE `username`=" . $db->dbescape( $username2 );
            $result = $db->sql_query( $query );
            $numrows = $db->sql_numrows( $result );
            if ( ! $numrows )
            {
                $reg_attribs['username'] = $username2;
                break;
            }
        }
    }
    
    if ( isset( $attribs['namePerson'] ) and ! empty( $attribs['namePerson'] ) )
    {
        $reg_attribs['full_name'] = $attribs['namePerson'];
    }
    elseif ( isset( $attribs['namePerson/friendly'] ) and ! empty( $attribs['namePerson/friendly'] ) )
    {
        $reg_attribs['full_name'] = $attribs['namePerson/friendly'];
    }
    elseif ( isset( $attribs['namePerson/first'] ) and ! empty( $attribs['namePerson/first'] ) )
    {
        $reg_attribs['full_name'] = $attribs['namePerson/first'];
    }
    
    if ( isset( $attribs['namePerson/last'] ) and ! empty( $attribs['namePerson/last'] ) )
    {
        if ( ! empty( $reg_attribs['full_name'] ) )
        {
            $reg_attribs['full_name'] = $attribs['namePerson/last'] . ' ' . $reg_attribs['full_name'];
        }
        else
        {
            $reg_attribs['full_name'] = $attribs['namePerson/last'];
        }
    }
    
    if ( isset( $attribs['person/gender'] ) and ! empty( $attribs['person/gender'] ) )
    {
        $reg_attribs['gender'] = $attribs['person/gender'];
    }
    
    return $reg_attribs;
}

/**
 * openidLogin_Res1()
 * Function thuc hien khi OpenID duoc nhan dien
 * 
 * @param mixed $attribs
 * @return
 */
function openidLogin_Res1 ( $attribs )
{
    global $page_title, $key_words, $mod_title, $db, $crypt, $nv_Request, $lang_module, $lang_global, $module_name, $module_info, $global_config, $gfx_chk, $nv_redirect, $op;
    $email = ( isset( $attribs['contact/email'] ) and nv_check_valid_email( $attribs['contact/email'] ) == "" ) ? $attribs['contact/email'] : "";
    if ( empty( $email ) )
    {
        $nv_Request->unset_request( 'openid_attribs', 'session' );
        openidLogin_Res0( $lang_module['logged_in_failed'] );
        die();
    }
    $opid = $crypt->hash( $attribs['id'] );
    
    $query = "SELECT a.userid AS uid, a.email AS uemail, b.active AS uactive FROM `" . NV_USERS_GLOBALTABLE . "_openid` a, `" . NV_USERS_GLOBALTABLE . "` b 
    WHERE a.opid=" . $db->dbescape( $opid ) . " 
    AND a.email=" . $db->dbescape( $email ) . " 
    AND a.userid=b.userid";
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if ( $numrows )
    {
        list( $user_id, $op_email, $user_active ) = $db->sql_fetchrow( $result );
        $db->sql_freeresult( $result );
        
        $nv_Request->unset_request( 'openid_attribs', 'session' );
        
        if ( $op_email != $email )
        {
            openidLogin_Res0( $lang_module['not_logged_in'] );
            die();
        }
        
        if ( ! $user_active )
        {
            openidLogin_Res0( $lang_module['login_no_active'] );
            die();
        }
        
        $query = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $db->dbescape( $user_id );
        $result = $db->sql_query( $query );
        if ( defined( 'NV_IS_USER_FORUM' ) and file_exists( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/set_user_login.php' ) )
        {
            require_once ( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/set_user_login.php' );
            
            if ( defined( 'NV_IS_USER_LOGIN_FORUM_OK' ) )
            {
                $nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
            }
            else
            {
                $nv_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
            }
        }
        elseif ( $db->sql_numrows( $result ) )
        {
            $row = $db->sql_fetchrow( $result );
            validUserLog( $row, 1, $opid );
            $nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
        }
        else
        {
            $nv_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
        }
        Header( "Location: " . nv_url_rewrite( $nv_redirect, true ) );
        die();
    }
    
    $query = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `email`=" . $db->dbescape( $email );
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if ( $numrows )
    {
        $nv_row = $db->sql_fetchrow( $result );
        $db->sql_freeresult( $result );
        
        $login_allowed = false;
        
        if ( empty( $nv_row['password'] ) )
        {
            $nv_Request->unset_request( 'openid_attribs', 'session' );
            $login_allowed = true;
        }
        
        if ( $nv_Request->isset_request( 'openid_account_confirm', 'post' ) )
        {
            $password = $nv_Request->get_string( 'password', 'post', '' );
            $nv_seccode = filter_text_input( 'nv_seccode', 'post', '' );
            $nv_seccode = ! $gfx_chk ? 1 : ( nv_capcha_txt( $nv_seccode ) ? 1 : 0 );
            
            $nv_Request->unset_request( 'openid_attribs', 'session' );
            if ( defined( 'NV_IS_USER_FORUM' ) and file_exists( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/login.php' ) )
            {
                $nv_username = $nv_row['username'];
                $nv_password = $password;
                require_once ( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/login.php' );
                if ( empty( $error ) )
                {
                    $login_allowed = true;
                }
                else
                {
                    openidLogin_Res0( $lang_module['openid_confirm_failed'] );
                    die();
                }
            }
            else
            {
                
                if ( $crypt->validate( $password, $nv_row['password'] ) and $nv_seccode )
                {
                    $login_allowed = true;
                }
                else
                {
                    openidLogin_Res0( $lang_module['openid_confirm_failed'] );
                    die();
                }
            }
        }
        if ( $login_allowed )
        {
            $sql = "INSERT INTO `" . NV_USERS_GLOBALTABLE . "_openid` VALUES (" . intval( $nv_row['userid'] ) . ", " . $db->dbescape( $attribs['id'] ) . ", " . $db->dbescape( $opid ) . ", " . $db->dbescape( $email ) . ")";
            $db->sql_query( $sql );
            if ( intval( $nv_row['active'] ) != 1 )
            {
                openidLogin_Res0( $lang_module['login_no_active'] );
            }
            else
            {
                validUserLog( $nv_row, 1, $opid );
                Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
            }
            die();
        }
        $page_title = $lang_module['openid_login'];
        $key_words = $module_info['keywords'];
        $mod_title = $lang_module['openid_login'];
        
        $lang_module['login_info'] = sprintf( $lang_module['openid_confirm_info'], $email );
        $contents = openid_account_confirm( $gfx_chk, $attribs );
        
        include ( NV_ROOTDIR . "/includes/header.php" );
        echo nv_site_theme( $contents );
        include ( NV_ROOTDIR . "/includes/footer.php" );
        exit();
    }
    if ( $global_config['allowuserreg'] == 2 or $global_config['allowuserreg'] == 3 )
    {
        $query = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "_reg` WHERE `email`=" . $db->dbescape( $email );
        if ( $global_config['allowuserreg'] == 2 )
        {
            $query .= " AND `regdate`>" . ( NV_CURRENTTIME - 86400 );
        }
        $result = $db->sql_query( $query );
        $numrows = $db->sql_numrows( $result );
        if ( $numrows )
        {
            if ( $global_config['allowuserreg'] == 2 )
            {
                $row = $db->sql_fetchrow( $result );
                $db->sql_freeresult( $result );
                
                if ( $nv_Request->isset_request( 'openid_active_confirm', 'post' ) )
                {
                    $nv_Request->unset_request( 'openid_attribs', 'session' );
                    
                    $password = $nv_Request->get_string( 'password', 'post', '' );
                    $nv_seccode = filter_text_input( 'nv_seccode', 'post', '' );
                    $nv_seccode = ! $gfx_chk ? 1 : ( nv_capcha_txt( $nv_seccode ) ? 1 : 0 );
                    
                    if ( $crypt->validate( $password, $row['password'] ) and $nv_seccode )
                    {
                        $reg_attribs = set_reg_attribs( $attribs );
                        
                        $sql = "INSERT INTO `" . NV_USERS_GLOBALTABLE . "` (
                        `userid`, `username`, `md5username`, `password`, `email`, `full_name`, `gender`, `photo`, `birthday`, `regdate`, `website`, 
                        `location`, `yim`, `telephone`, `fax`, `mobile`, `question`, `answer`, `passlostkey`, `view_mail`, `remember`, `in_groups`, 
                        `active`, `checknum`, `last_login`, `last_ip`, `last_agent`, `last_openid`) VALUES (
                        NULL, 
                        " . $db->dbescape( $row['username'] ) . ", 
                        " . $db->dbescape( md5( $row['username'] ) ) . ", 
                        " . $db->dbescape( $row['password'] ) . ", 
                        " . $db->dbescape( $row['email'] ) . ", 
                        " . $db->dbescape( ! empty( $row['full_name'] ) ? $row['full_name'] : $reg_attribs['full_name'] ) . ", 
                        " . $db->dbescape( $reg_attribs['gender'] ) . ", 
                        '', 0, 
                        " . $db->dbescape( $row['regdate'] ) . ", 
                        '', '', 
                        " . $db->dbescape( $reg_attribs['yim'] ) . ", 
                        '', '', '', 
                        " . $db->dbescape( $row['question'] ) . ", 
                        " . $db->dbescape( $row['answer'] ) . ", 
                        '', 1, 1, '', 1, '', 0, '', '', '')";
                        
                        $userid = $db->sql_query_insert_id( $sql );
                        
                        if ( ! $userid )
                        {
                            openidLogin_Res0( $lang_module['account_active_error'] );
                            die();
                        }
                        
                        $sql = "DELETE FROM `" . NV_USERS_GLOBALTABLE . "_reg` WHERE `userid`=" . $db->dbescape( $row['userid'] );
                        $db->sql_query( $sql );
                        
                        $sql = "INSERT INTO `" . NV_USERS_GLOBALTABLE . "_openid` VALUES (" . $userid . ", " . $db->dbescape( $attribs['id'] ) . ", " . $db->dbescape( $opid ) . ", " . $db->dbescape( $email ) . ")";
                        $db->sql_query( $sql );
                        
                        $query = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $db->dbescape( $userid );
                        $result = $db->sql_query( $query );
                        $row = $db->sql_fetchrow( $result );
                        
                        validUserLog( $row, 1, $opid );
                        
                        $info = $lang_module['account_active_ok'] . "<br /><br />\n";
                        $info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
                        $info .= "[<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "\">" . $lang_module['redirect_to_home'] . "</a>]";
                        $contents .= user_info_exit( $info );
                        $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) . "\" />";
                        
                        include ( NV_ROOTDIR . "/includes/header.php" );
                        echo nv_site_theme( $contents );
                        include ( NV_ROOTDIR . "/includes/footer.php" );
                        exit();
                    }
                    else
                    {
                        openidLogin_Res0( $lang_module['openid_confirm_failed'] );
                        die();
                    }
                }
                
                $page_title = $mod_title = $lang_module['openid_active_title'];
                $key_words = $module_info['keywords'];
                
                $lang_module['login_info'] = sprintf( $lang_module['openid_active_confirm_info'], $email );
                
                $contents = openid_active_confirm( $gfx_chk, $attribs );
                
                include ( NV_ROOTDIR . "/includes/header.php" );
                echo nv_site_theme( $contents );
                include ( NV_ROOTDIR . "/includes/footer.php" );
                exit();
            }
            else
            {
                $nv_Request->unset_request( 'openid_attribs', 'session' );
                openidLogin_Res0( $lang_module['account_register_to_admin'] );
                die();
            }
        }
    }
    
    $option = $nv_Request->get_int( 'option', 'get', 0 );
    
    if ( ! $global_config['allowuserreg'] )
    {
        $option = 3;
    }
    
    $contents = "";
    if ( $option == 3 )
    {
        $error = "";
        if ( $nv_Request->isset_request( 'nv_login', 'post' ) )
        {
            $nv_username = filter_text_input( 'nv_login', 'post', '', 1, NV_UNICKMAX );
            $nv_password = filter_text_input( 'nv_password', 'post', '' );
            $nv_seccode = filter_text_input( 'nv_seccode', 'post', '' );
            //$check_login = nv_check_valid_login( $nv_username, NV_UNICKMAX, NV_UNICKMIN );
            // $check_pass = nv_check_valid_pass( $nv_password, NV_UPASSMAX, NV_UPASSMIN );
            $check_seccode = ! $gfx_chk ? true : ( nv_capcha_txt( $nv_seccode ) ? true : false );
            
            if ( ! $check_seccode )
            {
                $error = $lang_global['securitycodeincorrect'];
            }
            elseif ( empty( $nv_username ) )
            {
                $error = $lang_global['username_empty'];
            }
            elseif ( empty( $nv_password ) )
            {
                $error = $lang_global['password_empty'];
            }
            else
            {
                if ( defined( 'NV_IS_USER_FORUM' ) )
                {
                    require_once ( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/login.php' );
                }
                else
                {
                    $error = $lang_global['loginincorrect'];
                    
                    $sql = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "` WHERE md5username ='" . md5( $nv_username ) . "'";
                    $result = $db->sql_query( $sql );
                    if ( $db->sql_numrows( $result ) == 1 )
                    {
                        $row = $db->sql_fetchrow( $result );
                        if ( $row['username'] == $nv_username and $crypt->validate( $nv_password, $row['password'] ) )
                        {
                            if ( ! $row['active'] )
                            {
                                $error = $lang_module['login_no_active'];
                            }
                            else
                            {
                                $error = "";
                                $sql = "INSERT INTO `" . NV_USERS_GLOBALTABLE . "_openid` VALUES (" . intval( $row['userid'] ) . ", " . $db->dbescape( $attribs['id'] ) . ", " . $db->dbescape( $opid ) . ", " . $db->dbescape( $email ) . ")";
                                $db->sql_query( $sql );
                                validUserLog( $row, 1, $opid );
                            }
                        }
                    }
                }
            }
            
            if ( empty( $error ) )
            {
                $nv_Request->unset_request( 'openid_attribs', 'session' );
                
                $nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
                $info = $lang_module['login_ok'] . "<br /><br />\n";
                $info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
                $info .= "[<a href=\"" . $nv_redirect . "\">" . $lang_module['redirect_to_back'] . "</a>]";
                $contents .= user_info_exit( $info );
                $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . nv_url_rewrite( $nv_redirect, true ) . "\" />";
                
                include ( NV_ROOTDIR . "/includes/header.php" );
                echo nv_site_theme( $contents );
                include ( NV_ROOTDIR . "/includes/footer.php" );
                exit();
            }
            
            $array_login = array( "nv_login" => $nv_username, "nv_password" => $nv_password, "nv_redirect" => $nv_redirect, 'login_info' => "<span style=\"color:#fb490b;\">" . $error . "</span>" );
        }
        else
        {
            $array_login = array( "nv_login" => '', "nv_password" => '', 'login_info' => $lang_module['openid_note1'], "nv_redirect" => $nv_redirect );
        }
        
        $contents .= user_openid_login( $gfx_chk, $array_login, $attribs );
        
        include ( NV_ROOTDIR . "/includes/header.php" );
        echo nv_site_theme( $contents );
        include ( NV_ROOTDIR . "/includes/footer.php" );
        exit();
    }
    elseif ( $option == 1 or $option == 2 )
    {
        $nv_Request->unset_request( 'openid_attribs', 'session' );
        
        $reg_attribs = set_reg_attribs( $attribs );
        if ( empty( $reg_attribs['username'] ) )
        {
            openidLogin_Res0( $lang_module['logged_in_failed'] );
            die();
        }
        
        if ( $option == 2 )
        {
            $sql = "INSERT INTO `" . NV_USERS_GLOBALTABLE . "` 
            (`userid`, `username`, `md5username`, `password`, `email`, `full_name`, `gender`, `photo`, `birthday`, 
            `regdate`, `website`, `location`, `yim`, `telephone`, `fax`, `mobile`, `question`, `answer`, `passlostkey`, 
            `view_mail`, `remember`, `in_groups`, `active`, `checknum`, `last_login`, `last_ip`, `last_agent`, `last_openid`) VALUES 
            (
            NULL, 
            " . $db->dbescape( $reg_attribs['username'] ) . ", 
            " . $db->dbescape( md5( $reg_attribs['username'] ) ) . ", 
            '', 
            " . $db->dbescape( $reg_attribs['email'] ) . ", 
            " . $db->dbescape( $reg_attribs['full_name'] ) . ", 
            " . $db->dbescape( ucfirst( $reg_attribs['gender'] ? $reg_attribs['gender']{0} : "" ) ) . ", 
            '', 0, " . NV_CURRENTTIME . ", '', '', 
            " . $db->dbescape( $reg_attribs['yim'] ) . ", 
            '', '', '', '', '', '', 0, 0, '', 1, '', 0, '', '', ''
            )";
            $userid = $db->sql_query_insert_id( $sql );
            
            if ( ! $userid )
            {
                openidLogin_Res0( $lang_module['err_no_save_account'] );
                die();
            }
            
            $query = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $userid . " AND `active`=1";
            $result = $db->sql_query( $query );
            $row = $db->sql_fetchrow( $result );
            $db->sql_freeresult( $result );
            
            $sql = "INSERT INTO `" . NV_USERS_GLOBALTABLE . "_openid` VALUES (" . intval( $row['userid'] ) . ", " . $db->dbescape( $reg_attribs['openid'] ) . ", " . $db->dbescape( $reg_attribs['opid'] ) . ", " . $db->dbescape( $reg_attribs['email'] ) . ")";
            $db->sql_query( $sql );
            validUserLog( $row, 1, $reg_attribs['opid'] );
            $nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
            
            Header( "Location: " . nv_url_rewrite( $nv_redirect, true ) );
            exit();
        }
        else
        {
            $reg_attribs = serialize( $reg_attribs );
            $nv_Request->set_Session( 'reg_attribs', $reg_attribs );
            
            Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=register&openid=1&nv_redirect=" . $nv_redirect, true ) );
            exit();
        }
    }
    $array_user_login = array();
    if ( ! defined( 'NV_IS_USER_FORUM' ) )
    {
        $array_user_login[] = array( "title" => $lang_module['openid_note3'], "link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=login&amp;server=" . $attribs['server'] . "&amp;result=1&amp;option=1&amp;nv_redirect=" . $nv_redirect );
        $array_user_login[] = array( "title" => $lang_module['openid_note4'], "link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=login&amp;server=" . $attribs['server'] . "&amp;result=1&amp;option=2&amp;nv_redirect=" . $nv_redirect );
    }
    else
    {
        $array_user_login[] = array( "title" => $lang_module['openid_note6'], "link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=register&amp;nv_redirect=" . $nv_redirect );
    }
    $array_user_login[] = array( "title" => $lang_module['openid_note5'], "link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=login&amp;server=" . $attribs['server'] . "&amp;result=1&amp;option=3&amp;nv_redirect=" . $nv_redirect );
    
    $contents .= user_openid_login2( $attribs, $array_user_login );
    
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_site_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}

$nv_redirect = filter_text_input( 'nv_redirect', 'post,get', '' );

//Dang nhap bang Open ID
if ( defined( 'NV_OPENID_ALLOWED' ) )
{
    $server = $nv_Request->get_string( 'server', 'get', '' );
    if ( ! empty( $server ) and isset( $openid_servers[$server] ) )
    {
        include_once ( NV_ROOTDIR . "/includes/class/openid.class.php" );
        $openid = new LightOpenID();
        
        if ( $nv_Request->isset_request( 'openid_mode', 'get' ) )
        {
            $openid_mode = $nv_Request->get_string( 'openid_mode', 'get', '' );
            
            if ( $openid_mode == "cancel" )
            {
                $attribs = array( 'result' => 'cancel' );
            }
            elseif ( ! $openid->validate() )
            {
                $attribs = array( 'result' => 'notlogin' );
            }
            else
            {
                $attribs = array( 'result' => 'is_res', 'id' => $openid->identity, 'server' => $server ) + $openid->getAttributes();
            }
            
            $attribs = serialize( $attribs );
            $nv_Request->set_Session( 'openid_attribs', $attribs );
            Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=login&server=" . $server . "&result=1&nv_redirect=" . $nv_redirect );
            exit();
        }
        
        if ( ! $nv_Request->isset_request( 'result', 'get' ) )
        {
            $openid->identity = $openid_servers[$server]['identity'];
            $openid->required = array_values( $openid_servers[$server]['required'] );
            header( 'Location: ' . $openid->authUrl() );
            die();
        }
        
        $openid_attribs = $nv_Request->get_string( 'openid_attribs', 'session', '' );
        $openid_attribs = ! empty( $openid_attribs ) ? unserialize( $openid_attribs ) : array();
        
        if ( empty( $openid_attribs ) or $openid_attribs['server'] != $server )
        {
            $nv_Request->unset_request( 'openid_attribs', 'session' );
            $nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
            Header( "Location: " . nv_url_rewrite( $nv_redirect ) );
            die();
        }
        
        if ( $openid_attribs['result'] == 'cancel' )
        {
            $nv_Request->unset_request( 'openid_attribs', 'session' );
            openidLogin_Res0( $lang_module['canceled_authentication'] );
        }
        elseif ( $openid_attribs['result'] == 'notlogin' )
        {
            $nv_Request->unset_request( 'openid_attribs', 'session' );
            openidLogin_Res0( $lang_module['not_logged_in'] );
        }
        else
        {
            openidLogin_Res1( $openid_attribs );
        }
        exit();
    }
}

//Dang nhap kieu thong thuong
$page_title = $lang_module['login'];
$key_words = $module_info['keywords'];
$mod_title = $lang_module['login'];

$contents = "";
$error = "";
if ( $nv_Request->isset_request( 'nv_login', 'post' ) )
{
    $nv_username = filter_text_input( 'nv_login', 'post', '', 1, NV_UNICKMAX );
    $nv_password = filter_text_input( 'nv_password', 'post', '' );
    $nv_seccode = filter_text_input( 'nv_seccode', 'post', '' );
    //$check_login = nv_check_valid_login( $nv_username, NV_UNICKMAX, NV_UNICKMIN );
    // $check_pass = nv_check_valid_pass( $nv_password, NV_UPASSMAX, NV_UPASSMIN );
    $check_seccode = ! $gfx_chk ? true : ( nv_capcha_txt( $nv_seccode ) ? true : false );
    
    if ( ! $check_seccode )
    {
        $error = $lang_global['securitycodeincorrect'];
    }
    elseif ( empty( $nv_username ) )
    {
        $error = $lang_global['username_empty'];
    }
    elseif ( empty( $nv_password ) )
    {
        $error = $lang_global['password_empty'];
    }
    else
    {
        if ( defined( 'NV_IS_USER_FORUM' ) )
        {
            require_once ( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/login.php' );
        }
        else
        {
            $error = $lang_global['loginincorrect'];
            
            $sql = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "` WHERE md5username ='" . md5( $nv_username ) . "'";
            $result = $db->sql_query( $sql );
            if ( $db->sql_numrows( $result ) == 1 )
            {
                $row = $db->sql_fetchrow( $result );
                if ( $row['username'] == $nv_username and $crypt->validate( $nv_password, $row['password'] ) )
                {
                    if ( ! $row['active'] )
                    {
                        $error = $lang_module['login_no_active'];
                    }
                    else
                    {
                        $error = "";
                        validUserLog( $row, 1, '' );
                    }
                }
            }
        }
    }
    
    if ( empty( $error ) )
    {
        $nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
        $info = $lang_module['login_ok'] . "<br /><br />\n";
        $info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
        $info .= "[<a href=\"" . $nv_redirect . "\">" . $lang_module['redirect_to_back'] . "</a>]";
        $contents .= user_info_exit( $info );
        $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . nv_url_rewrite( $nv_redirect ) . "\" />";
        
        include ( NV_ROOTDIR . "/includes/header.php" );
        echo nv_site_theme( $contents );
        include ( NV_ROOTDIR . "/includes/footer.php" );
        exit();
    }
    $lang_module['login_info'] = "<span style=\"color:#fb490b;\">" . $error . "</span>";
    $array_login = array( "nv_login" => $nv_username, "nv_password" => $nv_password, "nv_redirect" => $nv_redirect );
}
else
{
    $array_login = array( "nv_login" => '', "nv_password" => '', "nv_redirect" => $nv_redirect );
}

$array_login['openid_info'] = $lang_module['what_is_openid'];
if ( $global_config['allowuserreg'] == 2 )
{
    $array_login['openid_info'] .= "<br />" . $lang_module['or_activate_account'];
}

$contents .= user_login( $gfx_chk, $array_login );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>