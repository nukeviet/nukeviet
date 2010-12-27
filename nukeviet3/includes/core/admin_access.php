<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1-27-2010 5:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * nv_admin_checkip()
 * 
 * @return
 */
function nv_admin_checkip ( )
{
    global $global_config, $client_info;
    if ( $global_config['block_admin_ip'] and file_exists( NV_ROOTDIR . "/" . NV_DATADIR . "/admin_config.php" ) )
    {
        include ( NV_ROOTDIR . "/" . NV_DATADIR . "/admin_config.php" );
        if ( empty( $array_adminip ) )
        {
            return true;
        }
        foreach ( $array_adminip as $ip_i => $array_ip )
        {
            if ( $array_ip['begintime'] < NV_CURRENTTIME and ( $array_ip['endtime'] == 0 or $array_ip['endtime'] > NV_CURRENTTIME ) )
            {
                if ( preg_replace( $array_ip['mask'], "", $client_info['ip'] ) == preg_replace( $array_ip['mask'], "", $ip_i ) )
                {
                    return true;
                }
            }
        }
        return false;
    }
    else
    {
        return true;
    }
}

/**
 * nv_set_authorization()
 * 
 * @return
 */
function nv_set_authorization ( )
{
    $auth_user = $auth_pw = "";
    if ( nv_getenv( 'PHP_AUTH_USER' ) )
    {
        $auth_user = nv_getenv( 'PHP_AUTH_USER' );
    }
    elseif ( nv_getenv( 'REMOTE_USER' ) )
    {
        $auth_user = nv_getenv( 'REMOTE_USER' );
    }
    elseif ( nv_getenv( 'AUTH_USER' ) )
    {
        $auth_user = nv_getenv( 'AUTH_USER' );
    }
    elseif ( nv_getenv( 'HTTP_AUTHORIZATION' ) )
    {
        $auth_user = nv_getenv( 'HTTP_AUTHORIZATION' );
    }
    elseif ( nv_getenv( 'Authorization' ) )
    {
        $auth_user = nv_getenv( 'Authorization' );
    }
    
    if ( nv_getenv( 'PHP_AUTH_PW' ) )
    {
        $auth_pw = nv_getenv( 'PHP_AUTH_PW' );
    }
    elseif ( nv_getenv( 'REMOTE_PASSWORD' ) )
    {
        $auth_pw = nv_getenv( 'REMOTE_PASSWORD' );
    }
    elseif ( nv_getenv( 'AUTH_PASSWORD' ) )
    {
        $auth_pw = nv_getenv( 'AUTH_PASSWORD' );
    }
    
    if ( strcmp( substr( $auth_user, 0, 6 ), 'Basic ' ) == 0 )
    {
        $usr_pass = base64_decode( substr( $auth_user, 6 ) );
        if ( ! empty( $usr_pass ) && strpos( $usr_pass, ':' ) !== false )
        {
            list( $auth_user, $auth_pw ) = explode( ':', $usr_pass );
        }
        unset( $usr_pass );
    }
    return array( 
        'auth_user' => $auth_user, 'auth_pw' => $auth_pw 
    );
}

/**
 * nv_admin_checkfirewall()
 * 
 * @return
 */
function nv_admin_checkfirewall ( )
{
    global $global_config;
    if ( $global_config['admfirewall'] and file_exists( NV_ROOTDIR . "/" . NV_DATADIR . "/admin_config.php" ) )
    {
        include ( NV_ROOTDIR . "/" . NV_DATADIR . "/admin_config.php" );
        if ( empty( $adv_admins ) )
        {
            return true;
        }
        $auth = nv_set_authorization();
        if ( empty( $auth['auth_user'] ) || empty( $auth['auth_pw'] ) ) return false;
        $md5_auth_user = md5( $auth['auth_user'] );
        if ( isset( $adv_admins[$md5_auth_user] ) )
        {
            $array_us = $adv_admins[$md5_auth_user];
            if ( $array_us['password'] == md5( $auth['auth_pw'] ) and $array_us['begintime'] < NV_CURRENTTIME and ( $array_us['endtime'] == 0 or $array_us['endtime'] > NV_CURRENTTIME ) )
            {
                return true;
            }
        }
        return false;
    }
    else
    {
        return true;
    }
}

/**
 * nv_admin_checkdata()
 * 
 * @param mixed $adm_session_value
 * @return
 */
function nv_admin_checkdata ( $adm_session_value )
{
    global $db;
    
    $admin_info = array();
    $strlen = ( NV_CRYPT_SHA1 == 1 ) ? 40 : 32;
    $array_admin = unserialize( $adm_session_value );
    
    if ( isset( $array_admin['admin_id'] ) and is_numeric( $array_admin['admin_id'] ) and $array_admin['admin_id'] > 0 and isset( $array_admin['checknum'] ) and preg_match( "/^[a-z0-9]{" . $strlen . "}$/", $array_admin['checknum'] ) )
    {
        $query = "SELECT * FROM `" . NV_AUTHORS_GLOBALTABLE . "` WHERE `admin_id` = " . $array_admin['admin_id'] . " AND `lev`!=0 AND `is_suspend`=0";
        $result = $db->sql_query( $query );
        $numrows = $db->sql_numrows( $result );
        if ( $numrows != 1 ) return array();
        
        $row = $db->sql_fetchrow( $result );
        $db->sql_freeresult( $result );
        
        if ( strcasecmp( $array_admin['checknum'], $row['check_num'] ) == 0 and //check_num
isset( $array_admin['current_agent'] ) and ! empty( $array_admin['current_agent'] ) and strcasecmp( $array_admin['current_agent'], $row['last_agent'] ) == 0 and //user_agent
isset( $array_admin['current_ip'] ) and ! empty( $array_admin['current_ip'] ) and strcasecmp( $array_admin['current_ip'], $row['last_ip'] ) == 0 and //IP
isset( $array_admin['current_login'] ) and ! empty( $array_admin['current_login'] ) and strcasecmp( $array_admin['current_login'], intval( $row['last_login'] ) ) == 0 ) //current_login
        

        {
            if ( empty( $row['files_level'] ) )
            {
                $allow_files_type = array();
                $allow_modify_files = $allow_create_subdirectories = $allow_modify_subdirectories = 0;
            }
            else
            {
                list( $allow_files_type, $allow_modify_files, $allow_create_subdirectories, $allow_modify_subdirectories ) = explode( "|", $row['files_level'] );
                $allow_files_type = ! empty( $allow_files_type ) ? explode( ",", $allow_files_type ) : array();
            }
            
            $admin_info['admin_id'] = intval( $row['admin_id'] );
            $admin_info['level'] = intval( $row['lev'] );
            $admin_info['position'] = $row['position'];
            $admin_info['current_login'] = intval( $row['last_login'] );
            $admin_info['last_login'] = intval( $array_admin['last_login'] );
            $admin_info['current_agent'] = $row['last_agent'];
            $admin_info['last_agent'] = $array_admin['last_agent'];
            $admin_info['current_ip'] = $row['last_ip'];
            $admin_info['last_ip'] = $array_admin['last_ip'];
            $admin_info['editor'] = $row['editor'];
            $admin_info['allow_files_type'] = $allow_files_type;
            $admin_info['allow_modify_files'] = intval( $allow_modify_files );
            $admin_info['allow_create_subdirectories'] = intval( $allow_create_subdirectories );
            $admin_info['allow_modify_subdirectories'] = intval( $allow_modify_subdirectories );
            
            $query = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid` = " . $admin_info['admin_id'] . " AND `active`='1'";
            $result = $db->sql_query( $query );
            $numrows = $db->sql_numrows( $result );
            if ( $numrows != 1 ) return array();
            
            $row = $db->sql_fetchrow( $result );
            $db->sql_freeresult( $result );
            
            $admin_info['userid'] = $row['userid'];
            $admin_info['username'] = $row['username'];
            $admin_info['email'] = $row['email'];
            $admin_info['full_name'] = $row['full_name'];
            $admin_info['view_mail'] = intval( $row['view_mail'] );
            
            $admin_info['regdate'] = intval( $row['regdate'] );
            $admin_info['sig'] = $row['sig'];
            
            $admin_info['gender'] = $row['gender'];
            $admin_info['photo'] = $row['photo'];
            $admin_info['birthday'] = intval( $row['birthday'] );
            $admin_info['website'] = $row['website'];
            $admin_info['location'] = $row['location'];
            $admin_info['yim'] = $row['yim'];
            $admin_info['telephone'] = $row['telephone'];
            $admin_info['fax'] = $row['fax'];
            $admin_info['mobile'] = $row['mobile'];
            $admin_info['in_groups'] = nv_user_groups( $row['in_groups'] );
            
            $admin_info['current_openid'] = '';
            $admin_info['last_openid'] = $row['last_openid'];
            $admin_info['st_login'] = ! empty( $row['password'] ) ? true : false;
            $admin_info['valid_question'] = ( ! empty( $row['question'] ) and ! empty( $row['answer'] ) ) ? true : false;
            $admin_info['current_mode'] = 3;
        }
    }
    return $admin_info;
}

?>