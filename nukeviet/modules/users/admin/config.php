<?php

/**
 * @Project NUKEVIET 3.4
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 - 2012 VINADES.,JSC. All rights reserved
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['config'];
$array_config = array();

/**
 * valid_name_config()
 * 
 * @param mixed $array_name
 * @return
 */
function valid_name_config( $array_name )
{
    $array_retutn = array();
    foreach( $array_name as $v )
    {
        $v = trim( $v );
        if( ! empty( $v ) and preg_match( "/^[a-z0-9\-\.\_]+$/", $v ) )
        {
            $array_retutn[] = $v;
        }
    }
    return $array_retutn;
}
if( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $array_config['allowmailchange'] = $nv_Request->get_int( 'allowmailchange', 'post', 0 );
    $array_config['allowuserpublic'] = $nv_Request->get_int( 'allowuserpublic', 'post', 0 );
    $array_config['allowquestion'] = $nv_Request->get_int( 'allowquestion', 'post', 0 );
    $array_config['allowloginchange'] = $nv_Request->get_int( 'allowloginchange', 'post', 0 );
    $array_config['allowuserlogin'] = $nv_Request->get_int( 'allowuserlogin', 'post', 0 );
    $array_config['allowuserreg'] = $nv_Request->get_int( 'allowuserreg', 'post', 0 );
    $array_config['openid_mode'] = $nv_Request->get_int( 'openid_mode', 'post', 0 );
    $array_config['is_user_forum'] = $nv_Request->get_int( 'is_user_forum', 'post', 0 );
    $array_config['openid_servers'] = $nv_Request->get_typed_array( 'openid_servers', 'post', 'string' );
    $array_config['openid_servers'] = ! empty( $array_config['openid_servers'] ) ? implode( ",", $array_config['openid_servers'] ) : "";
    $array_config['whoviewuser'] = $nv_Request->get_int( 'whoviewuser', 'post', 0 );
    foreach( $array_config as $config_name => $config_value )
    {
        $query = "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES('sys', 'global', " . $db->dbescape( $config_name ) . ", " . $db->dbescape( $config_value ) . ")";
        $db->sql_query( $query );
    }
    $array_config['deny_email'] = filter_text_input( 'deny_email', 'post', '', 1 );
    if( ! empty( $array_config['deny_email'] ) )
    {
        $array_config['deny_email'] = valid_name_config( explode( ",", $array_config['deny_email'] ) );
        $array_config['deny_email'] = implode( "|", $array_config['deny_email'] );
    }
    $sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "_config` SET `content`=" . $db->dbescape( $array_config['deny_email'] ) . ", `edit_time`=" . NV_CURRENTTIME . " WHERE `config`='deny_email'";
    $db->sql_query( $sql );
    $array_config['deny_name'] = filter_text_input( 'deny_name', 'post', '', 1 );
    if( ! empty( $array_config['deny_name'] ) )
    {
        $array_config['deny_name'] = valid_name_config( explode( ",", $array_config['deny_name'] ) );
        $array_config['deny_name'] = implode( "|", $array_config['deny_name'] );
    }
    $sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "_config` SET `content`=" . $db->dbescape( $array_config['deny_name'] ) . ", `edit_time`=" . NV_CURRENTTIME . " WHERE `config`='deny_name'";
    $db->sql_query( $sql );
    nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['ChangeConfigModule'], "", $admin_info['userid'] );
    nv_save_file_config_global();
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&rand=" . nv_genpass() );
    die();
}
$array_config = array();
$sql = "SELECT `config_name`, `config_value` FROM `" . NV_CONFIG_GLOBALTABLE . "` WHERE `lang`='sys' AND `module`='global' AND \n`config_name` IN ('allowmailchange','allowuserpublic','allowquestion','allowuserreg','allowloginchange','allowuserlogin','openid_mode','is_user_forum','openid_servers', 'whoviewuser')";
$result = $db->sql_query( $sql );
while( list( $c_config_name, $c_config_value ) = $db->sql_fetchrow( $result ) )
{
    $array_config[$c_config_name] = $c_config_value;
}
$array_config['allowmailchange'] = ! empty( $array_config['allowmailchange'] ) ? " checked=\"checked\"" : "";
$array_config['allowuserpublic'] = ! empty( $array_config['allowuserpublic'] ) ? " checked=\"checked\"" : "";
$array_config['allowquestion'] = ! empty( $array_config['allowquestion'] ) ? " checked=\"checked\"" : "";
$array_config['allowloginchange'] = ! empty( $array_config['allowloginchange'] ) ? " checked=\"checked\"" : "";
$array_config['allowuserlogin'] = ! empty( $array_config['allowuserlogin'] ) ? " checked=\"checked\"" : "";
$array_config['openid_mode'] = ! empty( $array_config['openid_mode'] ) ? " checked=\"checked\"" : "";
$array_config['is_user_forum'] = ! empty( $array_config['is_user_forum'] ) ? " checked=\"checked\"" : "";
$servers = $array_config['openid_servers'];
$servers = ! empty( $servers ) ? explode( ",", $servers ) : array();
$openid_servers = array();
include ( NV_ROOTDIR . '/includes/openid.php' );
$array_config['openid_servers'] = array();
if( ! empty( $openid_servers ) )
{
    $array_keys = array_keys( $openid_servers );
    foreach( $array_keys as $server )
    {
        $checked = ( ! empty( $servers ) and in_array( $server, $servers ) ) ? " checked=\"checked\"" : "";
        $array_config['openid_servers'][] = array( 'name' => $server, 'checked' => $checked );
    }
}
$sql = "SELECT `config`, `content` FROM `" . NV_USERS_GLOBALTABLE . "_config` WHERE `config`='deny_email' OR `config`='deny_name'";
$result = $db->sql_query( $sql );
while( list( $config, $content ) = $db->sql_fetchrow( $result ) )
{
    if( ! empty( $content ) )
    {
        $content = array_map( "trim", explode( "|", $content ) );
        $content = implode( ", ", $content );
    }
    $array_config[$config] = $content;
}
$db->sql_freeresult();
$array_registertype = array(
    0 => $lang_module['active_not_allow'],
    1 => $lang_module['active_all'],
    2 => $lang_module['active_email'],
    3 => $lang_module['active_admin_check'] );
$array_whoview = array(
    0 => $lang_module['whoview_all'],
    1 => $lang_module['whoview_user'],
    2 => $lang_module['whoview_admin'] );
        
$xtpl = new XTemplate( "config.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $array_config );
if( file_exists( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet' ) )
{
    $forum_files = @scandir( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet' );
    if( ! empty( $forum_files ) and in_array( 'is_user.php', $forum_files ) and in_array( 'changepass.php', $forum_files ) and in_array( 'editinfo.php', $forum_files ) and in_array( 'login.php', $forum_files ) and in_array( 'logout.php', $forum_files ) and in_array( 'lostpass.php', $forum_files ) and in_array( 'register.php', $forum_files ) )
    {
        $xtpl->parse( 'main.user_forum' );
    }
}
foreach( $array_registertype as $id => $titleregister )
{
    $select = ( $array_config['allowuserreg'] == $id ) ? " selected=\"selected\"" : "";
    $array = array(
        "id" => $id,
        "select" => $select,
        "value" => $titleregister );
    $xtpl->assign( 'REGISTERTYPE', $array );
    $xtpl->parse( 'main.registertype' );
}

foreach( $array_whoview as $id => $titleregister )
{
    $select = ( $array_config['whoviewuser'] == $id ) ? " selected=\"selected\"" : "";
    $array = array(
        "id" => $id,
        "select" => $select,
        "value" => $titleregister );
    $xtpl->assign( 'WHOVIEW', $array );
    $xtpl->parse( 'main.whoviewlistuser' );
}
if( ! empty( $array_config['openid_servers'] ) )
{
    foreach( $array_config['openid_servers'] as $server )
    {
        $xtpl->assign( 'OPENID', $server );
        $xtpl->parse( 'main.openid_servers' );
    }
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>