<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-11-2010 0:44
 */

if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$mod = filter_text_input( 'mod', 'get' );

if ( empty( $mod ) or ! preg_match( $global_config['check_module'], $mod ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
    die();
}

$query = "SELECT * FROM `" . NV_MODULES_TABLE . "` WHERE `title`=" . $db->dbescape( $mod );
$result = $db->sql_query( $query );
$numrows = $db->sql_numrows( $result );
if ( $numrows != 1 )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
    die();
}

$row = $db->sql_fetchrow( $result );

$theme_list = array();
$theme_array_file = nv_scandir( NV_ROOTDIR . "/themes", $global_config['check_theme'] );
$theme_array_file = array_flip( $theme_array_file );
$theme_array_file = array_keys( $theme_array_file );

$sql = "SELECT DISTINCT `theme` FROM `" . NV_PREFIXLANG . "_modthemes`  WHERE `func_id`=0";
$result = $db->sql_query( $sql );
while ( list( $theme ) = $db->sql_fetchrow( $result ) )
{
    if ( in_array( $theme, $theme_array_file ) )
    {
        $theme_list[] = $theme;
    }
}

$groups_list = nv_groups_list();

if ( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
    $custom_title = filter_text_input( 'custom_title', 'post', 1 );
    $theme = filter_text_input( 'theme', 'post', '', 1 );
    $keywords = filter_text_input( 'keywords', 'post', '', 1 );
    $act = $nv_Request->get_int( 'act', 'post', 0 );
    $rss = $nv_Request->get_int( 'rss', 'post', 0 );
    if ( ! empty( $theme ) and ! in_array( $theme, $theme_list ) ) $theme = "";
    if ( ! empty( $keywords ) )
    {
        $keywords = explode( ",", $keywords );
        $keywords = array_map( "trim", $keywords );
        $keywords = implode( ", ", $keywords );
    }
    if ( $mod != $global_config['site_home_module'] )
    {
        $who_view = $nv_Request->get_int( 'who_view', 'post', 0 );
        if ( $who_view < 0 or $who_view > 3 ) $who_view = 0;
        $groups_view = "";
        if ( $who_view == 3 )
        {
            $groups_view = $nv_Request->get_array( 'groups_view', 'post', array() );
            $groups_view = ! empty( $groups_view ) ? implode( ",", array_map( "intval", $groups_view ) ) : "";
        }
        else
        {
            $groups_view = ( string )$who_view;
        }
    }
    else
    {
        $act = 1;
        $who_view = 0;
        $groups_view = "0";
    }
    if ( $groups_view != "" and $custom_title != "" )
    {
        $sql = "UPDATE `" . NV_MODULES_TABLE . "` SET `custom_title`=" . $db->dbescape( $custom_title ) . ", `theme`=" . $db->dbescape( $theme ) . ", `keywords`=" . $db->dbescape( $keywords ) . ", `groups_view`=" . $db->dbescape( $groups_view ) . ", `act`='" . $act . "', `rss`='" . $rss . "'WHERE `title`=" . $db->dbescape( $mod );
        $db->sql_query( $sql );
        if ( $theme != $site_mods[$mod]['theme'] )
        {
            nv_set_layout_site();
        }
        nv_delete_all_cache();
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
        exit();
    }
    elseif ( $groups_view != "" )
    {
        $row['groups_view'] = $groups_view;
    }
}
else
{
    $custom_title = $row['custom_title'];
    $theme = $row['theme'];
    $act = $row['act'];
    $keywords = $row['keywords'];
    $rss = $row['rss'];
}

$who_view = 3;
$groups_view = array();
if ( $row['groups_view'] == "0" or $row['groups_view'] == "1" or $row['groups_view'] == "2" )
{
    $who_view = intval( $row['groups_view'] );
}
else
{
    $groups_view = array_map( "intval", explode( ",", $row['groups_view'] ) );
}
if ( empty( $custom_title ) ) $custom_title = $mod;

$page_title = sprintf( $lang_module['edit'], $mod );
$contents = array();

if ( file_exists( NV_ROOTDIR . "/modules/" . $row['module_file'] . "/funcs/rss.php" ) )
{
    $contents['rss'] = array( 
        $lang_module['activate_rss'], $rss 
    );
}

$contents['action'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=edit&amp;mod=" . $mod;
$contents['custom_title'] = array( 
    $lang_module['custom_title'], $custom_title, 70 
);
$contents['theme'] = array( 
    $lang_module['theme'], $lang_module['theme_default'], $theme_list, $theme 
);
$contents['act'] = array( 
    $lang_global['activate'], $act 
);

$contents['keywords'] = array( 
    $lang_module['keywords'], $keywords, 255, $lang_module['keywords_info'] 
);
if ( $mod != $global_config['site_home_module'] )
{
    $contents['who_view'] = array( 
        $lang_global['who_view'], array( 
        $lang_global['who_view0'], $lang_global['who_view1'], $lang_global['who_view2'], $lang_global['who_view3'] 
    ), $who_view 
    );
    $contents['groups_view'] = array( 
        $lang_global['groups_view'], $groups_list, $groups_view 
    );
}
$contents['submit'] = $lang_global['submit'];

$contents = edit_theme( $contents );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>