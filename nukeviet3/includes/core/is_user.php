<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/29/2009 4:15
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$user_info = array();

if ( defined( "NV_IS_ADMIN" ) )
{
    $user_info = $admin_info;
    define( 'NV_IS_USER', true );
}
elseif ( defined( 'NV_IS_USER_FORUM' ) )
{
    require_once ( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/is_user.php' );
    if ( isset( $user_info['userid'] ) and $user_info['userid'] > 0 )
    {
        $query = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid` = " . $user_info['userid'] . " AND `active`=1";
        $result = $db->sql_query( $query );
        $numrows = $db->sql_numrows( $result );
        if ( $numrows == 1 )
        {
            define( 'NV_IS_USER', true );
            $row = $db->sql_fetchrow( $result );
            $user_info['userid'] = intval( $row['userid'] );
            $user_info['username'] = $row['username'];
            $user_info['email'] = $row['email'];
            $user_info['full_name'] = $row['full_name'];
            $user_info['gender'] = $row['gender'];
            $user_info['photo'] = $row['photo'];
            $user_info['birthday'] = intval( $row['birthday'] );
            $user_info['regdate'] = intval( $row['regdate'] );
            $user_info['website'] = $row['website'];
            $user_info['location'] = $row['location'];
            $user_info['yim'] = $row['yim'];
            $user_info['telephone'] = $row['telephone'];
            $user_info['fax'] = $row['fax'];
            $user_info['mobile'] = $row['mobile'];
            $user_info['view_mail'] = intval( $row['view_mail'] );
            $user_info['remember'] = intval( $row['remember'] );
            $user_info['in_groups'] = nv_user_groups( $row['in_groups'] );
            $user_info['current_login'] = intval( $row['last_login'] );
            // $user_info['last_login'] = intval( $user['last_login'] );
            $user_info['current_agent'] = $row['last_agent'];
            // $user_info['last_agent'] = $user['last_agent'];
            $user_info['current_ip'] = $row['last_ip'];
            $user_info['last_openid'] = $row['last_openid'];
            //$user_info['last_ip'] = $user['last_ip'];
            $user_info['st_login'] = ! empty( $row['password'] ) ? true : false;
            //$user_info['current_mode'] = $user['current_mode'];
            $user_info['current_mode'] = 1;
            $user_info['valid_question'] = true;
        }
        else
        {
            $user_info = array();
        }
    }
}
else
{
    if ( $nv_Request->get_bool( 'nvloginhash', 'cookie', false ) )
    {
        $_user = $nv_Request->get_string( 'nvloginhash', 'cookie', '' );
        
        if ( ! empty( $_user ) and $global_config['allowuserlogin'] )
        {
            $user = unserialize( nv_base64_decode( $_user ) );
            $strlen = ( NV_CRYPT_SHA1 == 1 ) ? 40 : 32;
            
            if ( isset( $user['userid'] ) and is_numeric( $user['userid'] ) and $user['userid'] > 0 )
            {
                if ( isset( $user['checknum'] ) and preg_match( "/^[a-z0-9]{" . $strlen . "}$/", $user['checknum'] ) )
                {
                    $query = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid` = " . $user['userid'] . " AND `active`=1";
                    $result = $db->sql_query( $query );
                    $numrows = $db->sql_numrows( $result );
                    if ( $numrows == 1 )
                    {
                        $row = $db->sql_fetchrow( $result );
                        $db->sql_freeresult( $result );
                        
                        if ( strcasecmp( $user['checknum'], $row['checknum'] ) == 0 and //checknum
isset( $user['current_agent'] ) and ! empty( $user['current_agent'] ) and strcasecmp( $user['current_agent'], $row['last_agent'] ) == 0 and //user_agent
isset( $user['current_ip'] ) and ! empty( $user['current_ip'] ) and strcasecmp( $user['current_ip'], $row['last_ip'] ) == 0 and //current IP
isset( $user['current_login'] ) and ! empty( $user['current_login'] ) and strcasecmp( $user['current_login'], intval( $row['last_login'] ) ) == 0 ) //current login
                        

                        {
                            $user_info['userid'] = intval( $row['userid'] );
                            $user_info['username'] = $row['username'];
                            $user_info['email'] = $row['email'];
                            $user_info['full_name'] = $row['full_name'];
                            $user_info['gender'] = $row['gender'];
                            $user_info['photo'] = $row['photo'];
                            $user_info['birthday'] = intval( $row['birthday'] );
                            $user_info['regdate'] = intval( $row['regdate'] );
                            $user_info['website'] = $row['website'];
                            $user_info['location'] = $row['location'];
                            $user_info['yim'] = $row['yim'];
                            $user_info['telephone'] = $row['telephone'];
                            $user_info['fax'] = $row['fax'];
                            $user_info['mobile'] = $row['mobile'];
                            $user_info['view_mail'] = intval( $row['view_mail'] );
                            $user_info['remember'] = intval( $row['remember'] );
                            $user_info['in_groups'] = nv_user_groups( $row['in_groups'] );
                            $user_info['current_login'] = intval( $row['last_login'] );
                            $user_info['last_login'] = intval( $user['last_login'] );
                            $user_info['current_agent'] = $row['last_agent'];
                            $user_info['last_agent'] = $user['last_agent'];
                            $user_info['current_ip'] = $row['last_ip'];
                            $user_info['last_ip'] = $user['last_ip'];
                            $user_info['current_openid'] = $row['last_openid'];
                            $user_info['last_openid'] = $user['last_openid'];
                            $user_info['st_login'] = ! empty( $row['password'] ) ? true : false;
                            $user_info['valid_question'] = ( ! empty( $row['question'] ) and ! empty( $row['answer'] ) ) ? true : false;
                            $user_info['current_mode'] = ! empty( $row['last_openid'] ) ? 2 : 1;
                            
                            if ( ! empty( $row['last_openid'] ) )
                            {
                                $query2 = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "_openid` WHERE `opid`=" . $db->dbescape( $row['last_openid'] );
                                $result2 = $db->sql_query( $query2 );
                                $numrows2 = $db->sql_numrows( $result2 );
                                if ( $numrows2 != 1 )
                                {
                                    $user_info = array();
                                }
                                else
                                {
                                    $row2 = $db->sql_fetchrow( $result2 );
                                    $db->sql_freeresult( $result2 );
                                    $user_info['openid_id'] = $row2['openid'];
                                    $user_info['openid_email'] = $row2['email'];
                                    $user_info['openid_server'] = parse_url( $row2['openid'] );
                                    $user_info['openid_server'] = $user_info['openid_server']['host'];
                                    $user_info['openid_server'] = preg_replace( "/^([w]{3})\./", "", $user_info['openid_server'] );
                                }
                            }
                        }
                    }
                }
            }
        }
        
        if ( ! empty( $user_info ) and isset( $user_info['userid'] ) and $user_info['userid'] > 0 )
        {
            define( 'NV_IS_USER', true );
        }
        else
        {
            $nv_Request->unset_request( 'nvloginhash', 'cookie' );
            $user_info = array();
        }
    
    }
    
    unset( $user, $_user, $query, $result, $numrows, $row, $query2, $result2, $numrows2, $row2 );
}

?>