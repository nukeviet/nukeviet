<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_LANG' ) ) die( 'Stop!!!' );

$page_title = $lang_module['nv_lang_check_title'];
$array_lang_exit = array();
$result = $db->sql_query( "SHOW COLUMNS FROM `" . NV_LANGUAGE_GLOBALTABLE . "_file`" );
$add_field = true;
while ( $row = $db->sql_fetch_assoc( $result ) )
{
    if ( substr( $row['Field'], 0, 7 ) == "author_" )
    {
        $array_lang_exit[] .= trim( substr( $row['Field'], 7, 2 ) );
    }
}
if ( empty( $array_lang_exit ) )
{
    $contents = "<center><br /><b>" . $lang_module['nv_lang_error_exit'] . "</b></center>";
    $contents .= "<meta http-equiv=\"Refresh\" content=\"3;URL=" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=setting\" />";
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}
$lang_array_file = array();

$lang_array_file_temp = nv_scandir( NV_ROOTDIR . "/language", "/^[a-z]{2}+$/" );
foreach ( $lang_array_file_temp as $value )
{
    if ( file_exists( NV_ROOTDIR . "/language/" . $value . "/global.php" ) )
    {
        $lang_array_file[] = $value;
    }
}

$language_array_source = array( 
    "vi", "en" 
);

$typelang = filter_text_input( 'typelang', 'post,get', '' );
$sourcelang = filter_text_input( 'sourcelang', 'post,get', '' );
$idfile = $nv_Request->get_int( 'idfile', 'post,get', 0 );

if ( $nv_Request->isset_request( 'idfile,savedata', 'post' ) and $nv_Request->get_string( 'savedata', 'post' ) == md5( session_id() ) )
{
    $pozlang = $nv_Request->get_array( 'pozlang', 'post', array() );
    if ( ! empty( $pozlang ) )
    {
        foreach ( $pozlang as $id => $lang_value )
        {
            $id = intval( $id );
            $lang_value = trim( strip_tags( $lang_value, NV_ALLOWED_HTML_LANG ) );
            if ( ! empty( $lang_value ) )
            {
                $db->sql_query( "UPDATE `" . NV_LANGUAGE_GLOBALTABLE . "` SET `lang_" . $typelang . "`='" . mysql_real_escape_string( $lang_value ) . "' WHERE `id`='" . $id . "'" );
            }
        }
    }
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&typelang=" . $typelang . "&sourcelang=" . $sourcelang );
    die();
}

$contents .= "<br /><form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"get\"><center>";
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$contents .= "<table class=\"tab1\">";
$contents .= "<tr><td align=\"right\">" . $lang_module['nv_lang_data'] . ":</td><td><select name=\"typelang\">\n";
$contents .= " <option value=\"\">&nbsp;</option>\n";
foreach ( $language_array as $key => $value )
{
    if ( in_array( $key, $array_lang_exit ) )
    {
        $sl = ( $key == $typelang ) ? ' selected="selected"': '';
        $contents .= "<option value=\"" . $key . "\" " . $sl . ">" . $value['name'] . "</option>\n";
    }
}
$contents .= "</select></td></tr>\n";

$contents .= "<tr><td align=\"right\">" . $lang_module['nv_lang_data_source'] . ":</td><td><select name=\"sourcelang\">\n";
$contents .= " <option value=\"\">&nbsp;</option>\n";
foreach ( $language_array_source as $key )
{
    if ( in_array( $key, $array_lang_exit ) )
    {
        $sl = ( $key == $sourcelang ) ? ' selected="selected"': '';
        $contents .= "<option value=\"" . $key . "\" " . $sl . ">" . $language_array[$key]['name'] . "</option>\n";
    }
}
$contents .= "</select></td></tr>\n";

$contents .= "<tr><td align=\"right\"> " . $lang_module['nv_lang_area'] . ":</td><td><select name=\"idfile\">\n";
$contents .= " <option value=\"0\">" . $lang_module['nv_lang_checkallarea'] . "</option>\n";
$query = "SELECT `idfile`, `module`, `admin_file` FROM `" . NV_LANGUAGE_GLOBALTABLE . "_file` ORDER BY `idfile` ASC";
$result = $db->sql_query( $query );
while ( list( $idfile_i, $module, $admin_file, ) = $db->sql_fetchrow( $result ) )
{
    $langsitename = ( $admin_file == 1 ) ? $lang_module['nv_lang_admin'] : $lang_module['nv_lang_site'];
    $sl = ( $idfile_i == $idfile ) ? ' selected="selected"': '';
    $contents .= " <option value=\"" . $idfile_i . "\" " . $sl . ">" . $module . " " . $langsitename . "</option>\n";
}

$contents .= "</select></td></tr>\n";
$contents .= "</table>";
$contents .= "<input type=\"hidden\" name =\"submit\" value=\"1\" />";
$contents .= "<input type=\"submit\" value=\"" . $lang_module['nv_admin_submit'] . "\" /></center>";
$contents .= "</form>";
$contents .= "<br />";

$submit = $nv_Request->get_int( 'submit', 'get', 0 );
if ( $submit > 0 and in_array( $sourcelang, $array_lang_exit ) and in_array( $typelang, $array_lang_exit ) )
{
    $contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
    $contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
    $contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
    $contents .= "<input type=\"hidden\" name =\"submit\" value=\"1\" />";
    $contents .= "<input type=\"hidden\" name =\"typelang\" value=\"" . $typelang . "\" />";
    $contents .= "<input type=\"hidden\" name =\"sourcelang\" value=\"" . $sourcelang . "\" />";
    $contents .= "<input type=\"hidden\" name =\"idfile\" value=\"" . $idfile . "\" />";
    $contents .= "<input type=\"hidden\" name =\"savedata\" value=\"" . md5( session_id() ) . "\" />";
    
    $contents .= "<table summary=\"\" class=\"tab1\">\n";
    $contents .= "<col width=\"40\" />";
    $contents .= "<col width=\"200\" />";
    $contents .= "<thead>";
    $contents .= "<tr>";
    $contents .= "<td>" . $lang_module['nv_lang_nb'] . "</td>";
    $contents .= "<td>" . $lang_module['nv_lang_key'] . "</td>";
    $contents .= "<td>" . $lang_module['nv_lang_value'] . "</td>";
    $contents .= "</tr>";
    $contents .= "</thead>";
    if ( $idfile > 0 )
    {
        $query = "SELECT `id`, `lang_key`, `lang_" . $sourcelang . "` FROM `" . NV_LANGUAGE_GLOBALTABLE . "` WHERE `idfile`='" . $idfile . "' AND `lang_" . $typelang . "`='' ORDER BY `id` ASC";
    }
    else
    {
        $query = "SELECT `id`, `lang_key`, `lang_" . $sourcelang . "` FROM `" . NV_LANGUAGE_GLOBALTABLE . "` WHERE `lang_" . $typelang . "`='' ORDER BY `id` ASC";
    }
    $result = $db->sql_query( $query );
    while ( list( $id, $lang_key, $lang_value ) = $db->sql_fetchrow( $result ) )
    {
        $i ++;
        $class = ( $i % 2 ) ? " class=\"second\"" : "";
        $contents .= "<tbody" . $class . ">\n";
        $contents .= "<tr>";
        $contents .= "<td align=\"center\">" . $i . "</td>";
        $contents .= "<td align=\"right\">" . $lang_key . "</td>";
        $contents .= "<td align=\"left\"><input type=\"text\" value=\"\" name=\"pozlang[" . $id . "]\" size=\"90\" /><br />" . nv_htmlspecialchars( $lang_value ) . "</td>";
        $contents .= "</tr>";
        $contents .= "</tbody>";
    }
    $contents .= "</table>";
    $contents .= "<center><input type=\"submit\" value=\"" . $lang_module['nv_admin_edit_save'] . "\" /></center>";
    $contents .= "</form>";

}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>