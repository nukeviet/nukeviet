<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_LANG' ) ) die( 'Stop!!!' );

$page_title = $lang_module['nv_admin_copy'];
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
    $contents = "<center><br><b>" . $lang_module['nv_lang_error_exit'] . "</b></center>";
    $contents .= "<META HTTP-EQUIV=\"refresh\" content=\"3;URL=" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=setting\">";
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}

if ( $nv_Request->isset_request( 'newslang,typelang,checksess', 'post' ) and $nv_Request->get_string( 'checksess', 'post' ) == md5( session_id() ) )
{
    $newslang = filter_text_input( 'newslang', 'post', '' );
    $typelang = filter_text_input( 'typelang', 'post', '' );
    if ( $typelang == "-vi" )
    {
        $typelang = "-";
        $replace_lang_vi = true;
    }
    else
    {
        $replace_lang_vi = false;
    }
    if ( isset( $language_array[$newslang] ))
    {
        nv_admin_add_field_lang( $newslang );
        if ( $replace_lang_vi == true )
        {
            $db->sql_query( "UPDATE `". NV_LANGUAGE_GLOBALTABLE . "_file` SET `author_" . $newslang . "`=`author_vi`" );
            $query = "SELECT `id`, `lang_vi` FROM `" . NV_LANGUAGE_GLOBALTABLE . "`";
            $result = $db->sql_query( $query );
            while ( list( $id, $author_lang ) = $db->sql_fetchrow( $result ) )
            {
                $author_lang = nv_EncString( $author_lang );
                $db->sql_query( "UPDATE `" . NV_LANGUAGE_GLOBALTABLE . "` SET `lang_" . $newslang . "` ='" . $author_lang . "' WHERE `id` = '" . $id . "'" );
            }
        }
        elseif ( isset( $language_array[$typelang] ) )
        {
            $db->sql_query( "UPDATE `". NV_LANGUAGE_GLOBALTABLE . "_file` SET `author_" . $newslang . "`=`author_" . $typelang . "`" );
            $db->sql_query( "UPDATE `" . NV_LANGUAGE_GLOBALTABLE . "` SET `lang_" . $newslang . "`=`lang_" . $typelang . "`" );
        }
        $nv_Request->set_Cookie( 'dirlang', $newslang, NV_LIVE_COOKIE_TIME );
        $contents = "<br><br><p align=\"center\"><strong>" . $lang_module['nv_lang_copyok'] . "</strong></p>";
        $contents .= "<META HTTP-EQUIV=\"refresh\" content=\"3;URL=" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main\">";
        include ( NV_ROOTDIR . "/includes/header.php" );
        echo nv_admin_theme( $contents );
        include ( NV_ROOTDIR . "/includes/footer.php" );
    }
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

$contents .= "<br><br><br><form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$contents .= "<input type=\"hidden\" name =\"checksess\" value=\"" . md5( session_id() ) . "\" />";
$contents .= "<center><select name=\"newslang\">\n";
$contents .= "<option value=\"\">" . $lang_module['nv_admin_sl1'] . "</option>\n";
foreach ( $language_array as $key => $value )
{
    if ( ! in_array( $key, $array_lang_exit ) and ! in_array( $key, $lang_array_file ) ) $contents .= "<option value=\"" . $key . "\">" . $value['name'] . "</option>\n";
}
$contents .= "</select>\n";

$contents .= "<select name=\"typelang\">\n";
$contents .= "<option value=\"\">" . $lang_module['nv_admin_sl2'] . "</option>\n";
if ( in_array( "vi", $array_lang_exit ) )
{
    $contents .= "<option value=\"-vi\">" . $lang_module['nv_lang_copy'] . ":" . $language_array['vi']['name'] . " " . $lang_module['nv_lang_encstring'] . "</option>\n";
}

foreach ( $language_array as $key => $value )
{
    if ( in_array( $key, $array_lang_exit ) )
    {
        $contents .= "<option value=\"" . $key . "\">" . $lang_module['nv_lang_copy'] . ":" . $value['name'] . "</option>\n";
    }
}
$contents .= "</select>\n";
$contents .= "<input type=\"submit\" value=\"" . $lang_module['nv_admin_submit'] . "\" /><center>";
$contents .= "</form>";
$contents .= "<br>";
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>