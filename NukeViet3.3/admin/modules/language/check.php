<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_LANG' ) ) die( 'Stop!!!' );

$page_title = $lang_module['nv_lang_check'];
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

$language_array_source = array( "vi", "en" );

$language_check_type = array( 0 => $lang_module['nv_check_type_0'], 1 => $lang_module['nv_check_type_1'], 2 => $lang_module['nv_check_type_2'] );

$typelang = filter_text_input( 'typelang', 'post,get', '' );
$sourcelang = filter_text_input( 'sourcelang', 'post,get', '' );
$idfile = $nv_Request->get_int( 'idfile', 'post,get', 0 );
$check_type = $nv_Request->get_int( 'check_type', 'post,get', 0 );

if ( $nv_Request->isset_request( 'idfile,savedata', 'post' ) and $nv_Request->get_string( 'savedata', 'post' ) == md5( $global_config['sitekey'] . session_id() ) )
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
}
$array_files = array();

$contents .= "<br /><form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"get\"><center>";
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$contents .= "<table class=\"tab1\">";
$contents .= "<tr><td align=\"right\">" . $lang_module['nv_lang_data'] . ":</td><td><select name=\"typelang\">\n";
$contents .= " <option value=\"\">" . $lang_module['nv_admin_sl3'] . "</option>\n";
foreach ( $language_array as $key => $value )
{
    if ( in_array( $key, $array_lang_exit ) )
    {
        $sl = ( $key == $typelang ) ? ' selected="selected"' : '';
        $contents .= "<option value=\"" . $key . "\" " . $sl . ">" . $value['name'] . "</option>\n";
    }
}
$contents .= "</select></td></tr>\n";

$contents .= "<tr><td align=\"right\">" . $lang_module['nv_lang_data_source'] . ":</td><td><select name=\"sourcelang\">\n";
$contents .= " <option value=\"\">" . $lang_module['nv_admin_sl3'] . "</option>\n";
foreach ( $language_array_source as $key )
{
    if ( in_array( $key, $array_lang_exit ) )
    {
        $sl = ( $key == $sourcelang ) ? ' selected="selected"' : '';
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
    switch ( $admin_file )
    {
        case '1':
            $langsitename = $lang_module['nv_lang_admin'];
            break;
        case '0':
            $langsitename = $lang_module['nv_lang_site'];
            break;
        default:
            $langsitename = $admin_file;
            break;
    }
    $sl = ( $idfile_i == $idfile ) ? ' selected="selected"' : '';
    $contents .= " <option value=\"" . $idfile_i . "\" " . $sl . ">" . $module . " " . $langsitename . "</option>\n";
    $array_files[$idfile_i] = $module . " " . $langsitename;
}

$contents .= "</select></td></tr>\n";

$contents .= "<tr><td align=\"right\">" . $lang_module['nv_check_type'] . ":</td><td><select name=\"check_type\">\n";
foreach ( $language_check_type as $key => $value )
{
    $sl = ( $key == $check_type ) ? ' selected="selected"' : '';
    $contents .= "<option value=\"" . $key . "\" " . $sl . ">" . $value . "</option>\n";
}
$contents .= "</select></td></tr>\n";

$contents .= "</table>";
$contents .= "<input type=\"hidden\" name =\"submit\" value=\"1\" />";
$contents .= "<input type=\"submit\" value=\"" . $lang_module['nv_admin_submit'] . "\" /></center>";
$contents .= "</form>";
$contents .= "<br />";

$submit = $nv_Request->get_int( 'submit', 'post,get', 0 );
if ( $submit > 0 and in_array( $sourcelang, $array_lang_exit ) and in_array( $typelang, $array_lang_exit ) )
{
    $array_where = array();
    if ( $idfile > 0 )
    {
        $array_where[] = "`idfile`='" . $idfile . "'";
    }
    
    if ( $check_type == 0 )
    {
        $array_where[] = "`lang_" . $typelang . "`=''";
    }
    elseif ( $check_type == 1 )
    {
        $array_where[] = "`lang_" . $typelang . "`=`lang_" . $sourcelang . "`";
    }
    
    if ( empty( $array_where ) )
    {
        $query = "SELECT `id`, `idfile`, `lang_key`, `lang_" . $typelang . "` as datalang, `lang_" . $sourcelang . "` as sourcelang FROM `" . NV_LANGUAGE_GLOBALTABLE . "` ORDER BY `id` ASC";
    }
    else
    {
        $query = "SELECT `id`, `idfile`, `lang_key`, `lang_" . $typelang . "` as datalang, `lang_" . $sourcelang . "` as sourcelang FROM `" . NV_LANGUAGE_GLOBALTABLE . "` WHERE " . implode( " AND ", $array_where ) . " ORDER BY `id` ASC";
    }
    $result = $db->sql_query( $query );
    
    $array_lang_data = array();
    
    while ( list( $id, $idfile_i, $lang_key, $datalang, $datasourcelang ) = $db->sql_fetchrow( $result ) )
    {
        $array_lang_data[$idfile_i][$id] = array( 'lang_key' => $lang_key, 'datalang' => $datalang, 'sourcelang' => $datasourcelang );
    }
    if ( ! empty( $array_lang_data ) )
    {
        $contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
        $contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
        $contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
        $contents .= "<input type=\"hidden\" name =\"submit\" value=\"1\" />";
        $contents .= "<input type=\"hidden\" name =\"typelang\" value=\"" . $typelang . "\" />";
        $contents .= "<input type=\"hidden\" name =\"sourcelang\" value=\"" . $sourcelang . "\" />";
        $contents .= "<input type=\"hidden\" name =\"check_type\" value=\"" . $check_type . "\" />";
        $contents .= "<input type=\"hidden\" name =\"idfile\" value=\"" . $idfile . "\" />";
        $contents .= "<input type=\"hidden\" name =\"savedata\" value=\"" . md5( $global_config['sitekey'] . session_id() ) . "\" />";
        foreach ( $array_lang_data as $idfile_i => $array_lang_file )
        {
            $contents .= "<table summary=\"\" class=\"tab1\">\n";
            $contents .= "<caption>" . $array_files[$idfile_i] . "</caption>";
            $contents .= "<col width=\"40\" />";
            $contents .= "<col width=\"200\" />";
            $contents .= "<thead>";
            $contents .= "<tr>";
            $contents .= "<td>" . $lang_module['nv_lang_nb'] . "</td>";
            $contents .= "<td>" . $lang_module['nv_lang_key'] . "</td>";
            $contents .= "<td>" . $lang_module['nv_lang_value'] . "</td>";
            $contents .= "</tr>";
            $contents .= "</thead>";
            foreach ( $array_lang_file as $id => $row )
            {
                ++$i;
                $class = ( $i % 2 ) ? " class=\"second\"" : "";
                $contents .= "<tbody" . $class . ">\n";
                $contents .= "<tr>";
                $contents .= "<td align=\"center\">" . $i . "</td>";
                $contents .= "<td align=\"right\">" . $row['lang_key'] . "</td>";
                $contents .= "<td align=\"left\"><input type=\"text\" value=\"" . nv_htmlspecialchars( $row['datalang'] ) . "\" name=\"pozlang[" . $id . "]\" size=\"90\" /><br />" . nv_htmlspecialchars( $row['sourcelang'] ) . "</td>";
                $contents .= "</tr>";
                $contents .= "</tbody>";
            }
            $contents .= "</table>";
        }
        $contents .= "<center><input type=\"submit\" value=\"" . $lang_module['nv_admin_edit_save'] . "\" /></center>";
        $contents .= "</form>";
    }
    else
    {
        $contents .= "<br /><br /><center><b>" . $lang_module['nv_lang_check_no_data'] . "</b></center><br /><br /><br /><br />";
    }
    unset( $array_lang_data, $array_files );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>