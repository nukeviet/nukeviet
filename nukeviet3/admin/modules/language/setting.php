<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_IS_FILE_LANG' ) ) die( 'Stop!!!' );

$a = 1;
$page_title = $lang_module['nv_lang_setting'];
$array_type = array( 
    $lang_module['nv_setting_type_0'], $lang_module['nv_setting_type_1'], $lang_module['nv_setting_type_2'] 
);

if ( $nv_Request->get_string( 'checksessseting', 'post' ) == md5( session_id() . "seting" ) )
{
    $read_type = $nv_Request->get_int( 'read_type', 'post', 0 );
    $query = "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` SET `config_value` =  '" . $read_type . "' WHERE `lang`='sys' AND `module` = 'global' AND `config_name` =  'read_type'";
    $result = $db->sql_query( $query );
    nv_save_file_config_global();
    $contents = "<br /><br /><br /><p align=\"center\">" . $lang_module['nv_setting_save'] . "</p>";
    $contents .= "<meta http-equiv=\"Refresh\" content=\"2;URL=" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=setting\">";
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}
if ( $nv_Request->get_string( 'checksessshow', 'post' ) == md5( session_id() . "show" ) )
{
    $allow_sitelangs = $nv_Request->get_array( 'allow_sitelangs', 'post', array() );
    $allow_adminlangs = $nv_Request->get_array( 'allow_adminlangs', 'post', array() );
    $allow_adminlangs[] = NV_LANG_INTERFACE;
    $allow_adminlangs[] = NV_LANG_DATA;
    $allow_adminlangs[] = $global_config['site_lang'];
    foreach ( $allow_sitelangs as $lang_temp )
    {
        $allow_adminlangs[] = $lang_temp;
    }
    
    $allow_sitelangs[] = $global_config['site_lang'];
    
    $allow_adminlangs = array_unique( $allow_adminlangs );
    
    $allow_sitelangs_temp = array_unique( $allow_sitelangs );
    $allow_sitelangs = array();
    foreach ( $allow_sitelangs_temp as $lang_temp )
    {
        if ( file_exists( NV_ROOTDIR . "/language/" . $lang_temp . "/global.php" ) )
        {
            $allow_sitelangs[] = $lang_temp;
        }
    }
    
    $allow_sitelangs_temp = array_unique( $allow_adminlangs );
    $allow_adminlangs = array();
    foreach ( $allow_sitelangs_temp as $lang_temp )
    {
        if ( file_exists( NV_ROOTDIR . "/language/" . $lang_temp . "/global.php" ) )
        {
            $allow_adminlangs[] = $lang_temp;
        }
    }
    
    $global_config['allow_sitelangs'] = $allow_sitelangs;
    $global_config['allow_adminlangs'] = $allow_adminlangs;
    
    $allow_sitelangs = implode( ",", $global_config['allow_sitelangs'] );
    $allow_adminlangs = implode( ",", $global_config['allow_adminlangs'] );
    
    $query = "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` SET `config_value` =  " . $db->dbescape( $allow_sitelangs ) . " WHERE `lang`='sys' AND `module` = 'global' AND `config_name` =  'allow_sitelangs'";
    $result = $db->sql_query( $query );
    
    $query = "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` SET `config_value` =  " . $db->dbescape( $allow_adminlangs ) . " WHERE `lang`='sys' AND `module` = 'global' AND `config_name` =  'allow_adminlangs'";
    $result = $db->sql_query( $query );
    
    nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['nv_setting_save'] , " allow sitelangs : " .$allow_sitelangs . ", allow adminlangs :" .$allow_adminlangs , $admin_info['userid'] );
    nv_save_file_config_global();
    $contents = "<br /><br /><br /><p align=\"center\">" . $lang_module['nv_setting_save'] . "</p>";
    $contents .= "<meta http-equiv=\"Refresh\" content=\"2;URL=" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=setting\" />\n";
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}

$lang_array_exit = nv_scandir( NV_ROOTDIR . "/language", "/^[a-z]{2}+$/" );
$result = $db->sql_query( "SHOW COLUMNS FROM `" . NV_LANGUAGE_GLOBALTABLE . "_file`" );
$lang_array_data_exit = array();
while ( $row = $db->sql_fetch_assoc( $result ) )
{
    if ( substr( $row['Field'], 0, 7 ) == "author_" )
    {
        $lang_array_data_exit[] = substr( $row['Field'], 7, 2 );
    }
}

$sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
$result = $db->sql_query( $sql );
$array_lang_setup = array();
while ( $row = $db->sql_fetchrow( $result ) )
{
    $array_lang_setup[] = trim( $row['lang'] );
}

$contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
$contents .= "<table summary=\"\" class=\"tab1\">\n";
$contents .= "  <caption>" . $lang_module['nv_lang_show'] . "</caption>";
$contents .= "  <tr class=\"thead_box\">";
$contents .= "      <td style=\"width: 50px\">" . $lang_module['nv_lang_key'] . "</td>";
$contents .= "      <td style=\"width: 180px\">" . $lang_module['nv_lang_name'] . "</td>";
$contents .= "      <td style=\"width: 120px\">" . $lang_module['nv_lang_slsite'] . "</td>";
$contents .= "      <td style=\"width: 120px\">" . $lang_module['nv_lang_sladm'] . "</td>";
$contents .= "      <td></td>";
$contents .= "  </tr>";
$contents .= "  </table>";

$a = 0;
$contents .= "<div style=\"height: 350px; overflow: auto; margin-bottom: 5px;\">";
$contents .= "<table summary=\"\" class=\"tab1\" style=\"width:810px;\">\n";
while ( list( $key, $value ) = each( $language_array ) )
{
    $arr_lang_func = array();
    $check_lang_exit = false;
    if ( file_exists( NV_ROOTDIR . "/language/" . $key . "/global.php" ) )
    {
        $check_lang_exit = true;
        $arr_lang_func[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=read&amp;dirlang=" . $key . "&amp;checksess=" . md5( "readallfile" . session_id() ) . "\">" . $lang_module['nv_admin_read_all'] . "</a>";
    }
    if ( in_array( $key, $lang_array_data_exit ) )
    {
        $arr_lang_func[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=write&amp;dirlang=" . $key . "&amp;checksess=" . md5( "writeallfile" . session_id() ) . "\">" . $lang_module['nv_admin_write'] . "</a>";
        $arr_lang_func[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=download&amp;dirlang=" . $key . "&amp;checksess=" . md5( "downloadallfile" . session_id() ) . "\">" . $lang_module['nv_admin_download'] . "</a>";
    }
    $class = ( $a % 2 ) ? " class=\"second\"" : "";
    $contents .= "<tbody" . $class . ">\n";
    $contents .= "  <tr>";
    $contents .= "      <td style=\"width: 50px; text-align: center\">" . $key . "</td>";
    $contents .= "      <td style=\"width: 180px\">" . $value['name'] . "</td>";
    if ( $check_lang_exit and in_array( $key, $array_lang_setup ) )
    {
        $contents .= "<td style=\"width: 120px; text-align: center\"><input name=\"allow_sitelangs[]\" value=\"" . $key . "\" type=\"checkbox\" " . ( in_array( $key, $global_config['allow_sitelangs'] ) ? " checked=\"checked\"" : "" ) . " /></td>";
    }
    else
    {
        $contents .= "<td style=\"width: 120px; text-align: center\"><input name=\"allow_sitelangs[]\" value=\"" . $key . "\" type=\"checkbox\" disabled=\"disabled\" /></td>";
    }
    
    if ( $check_lang_exit )
    {
        $contents .= "<td style=\"width: 120px; text-align: center\"><input name=\"allow_adminlangs[]\" value=\"" . $key . "\" type=\"checkbox\" " . ( in_array( $key, $global_config['allow_adminlangs'] ) ? " checked=\"checked\"" : "" ) . " /></td>";
    }
    else
    {
        $contents .= "<td style=\"width: 120px; text-align: center\"><input name=\"allow_adminlangs[]\" value=\"" . $key . "\" type=\"checkbox\" disabled=\"disabled\" /></td>";
    }
    if ( ! empty( $arr_lang_func ) and ! in_array( $key, $global_config['allow_adminlangs'] ) )
    {
        $arr_lang_func[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=delete&amp;dirlang=" . $key . "&amp;checksess=" . md5( "deleteallfile" . session_id() ) . "\">" . $lang_module['nv_admin_delete'] . "</a>";
    }
    $contents .= "<td style=\"text-align: center\">" . implode( " - ", $arr_lang_func ) . "</td>";
    $contents .= "</tr>";
    $contents .= "</tbody>";
    $a ++;
}
$contents .= "</table>";
$contents .= "</div>";

$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\" value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\" value=\"" . $op . "\" />";
$contents .= "<input type=\"hidden\" name =\"checksessshow\" value=\"" . md5( session_id() . "show" ) . "\" />";
$contents .= "<center><input type=\"submit\" value=\"" . $lang_module['nv_admin_edit_save'] . "\" /></center>";
$contents .= "</form>";

$contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
$contents .= "<table summary=\"\" class=\"tab1\">\n";
$contents .= "      <caption>" . $lang_module['nv_setting_read'] . "</caption>";
foreach ( $array_type as $key => $value )
{
    $contents .= "  <tr>";
    $contents .= "      <td></td>";
    $contents .= "      <td><input name=\"read_type\" value=\"" . $key . "\" type=\"radio\" " . ( $global_config['read_type'] == $key ? " checked=\"checked\"" : "" ) . " /> " . $value . "</td>";
    $contents .= "  </tr>";
}
$contents .= "</table>";
$contents .= "<br />";
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\" value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\" value=\"" . $op . "\" />";
$contents .= "<input type=\"hidden\" name =\"checksessseting\" value=\"" . md5( session_id() . "seting" ) . "\" />";
$contents .= "<center><input type=\"submit\" value=\"" . $lang_module['nv_admin_edit_save'] . "\" /></center>\n";
$contents .= "</form>";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>