<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 10/03/2010 10:51
 */

if ( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

if ( defined( 'NV_IS_USER' ) or defined( 'NV_IS_USER_FORUM' ) or $global_config['allowuserreg'] != 2 )
{
    Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
    die();
}

$userid = $nv_Request->get_int( 'userid', 'get', '', 1 );
$checknum = filter_text_input( 'checknum', 'get', '', 1 );

if ( empty( $userid ) or empty( $checknum ) )
{
    Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
    die();
}

$del = NV_CURRENTTIME - 86400;
$sql = "DELETE FROM `" . NV_USERS_GLOBALTABLE . "_reg` WHERE `regdate` < " . $del;
$db->sql_query( $sql );

$sql = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "_reg` WHERE `userid`=" . $userid;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );

if ( $numrows != 1 )
{
    Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
    die();
}

$page_title = $mod_title = $lang_module['register'];
$key_words = $module_info['keywords'];

$row = $db->sql_fetchrow( $result );

if ( $checknum != $row['checknum'] )
{
    $info = $lang_module['account_active_error'] . "<br /><br />\n";
    $info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
    $info .= "[<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "\">" . $lang_module['redirect_to_login'] . "</a>]";

    $contents = user_info_exit( $info );
    $contents .= "<meta http-equiv=\"refresh\" content=\"5;url=" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "\" />";

    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_site_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit;
}

$sql = "INSERT INTO `" . NV_USERS_GLOBALTABLE . "` (
`userid`, `username`, `password`, `email`, `full_name`, `gender`, `photo`, `birthday`, `regdate`, `website`, 
`location`, `yim`, `telephone`, `fax`, `mobile`, `question`, `answer`, `passlostkey`, `view_mail`, `remember`, `in_groups`, 
`active`, `checknum`, `last_login`, `last_ip`, `last_agent`, `last_openid`) VALUES (
NULL, 
" . $db->dbescape( $row['username'] ) . ", 
" . $db->dbescape( $row['password'] ) . ", 
" . $db->dbescape( $row['email'] ) . ", 
" . $db->dbescape( $row['full_name'] ) . ", 
'', '', 0, 
" . $db->dbescape( $row['regdate'] ) . ", 
'', '', '', '', '', '', 
" . $db->dbescape( $row['question'] ) . ", 
" . $db->dbescape( $row['answer'] ) . ", 
'', 1, 1, '', 1, '', 0, '', '', '')";

if ( ! $db->sql_query_insert_id( $sql ) )
{
    $info = $lang_module['account_active_error'] . "<br /><br />\n";
    $info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
    $info .= "[<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "\">" . $lang_module['redirect_to_login'] . "</a>]";

    $contents = user_info_exit( $info );
    $contents .= "<meta http-equiv=\"refresh\" content=\"5;url=" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "\" />";

    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_site_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit;
}

$sql = "DELETE FROM `" . NV_USERS_GLOBALTABLE . "_reg` WHERE `userid`=" . $db->dbescape( $userid );
$db->sql_query( $sql );

$info = $lang_module['account_active_ok'] . "<br /><br />\n";
$info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
$info .= "[<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "\">" . $lang_module['redirect_to_login'] . "</a>]";

$contents = user_info_exit( $info );
$contents .= "<meta http-equiv=\"refresh\" content=\"5;url=" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "\" />";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>