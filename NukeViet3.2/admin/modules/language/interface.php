<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_LANG' ) ) die( 'Stop!!!' );

$array_lang_exit = array();

$result = $db->sql_query( "SHOW COLUMNS FROM `" . NV_LANGUAGE_GLOBALTABLE . "_file`" );
while ( $row = $db->sql_fetch_assoc( $result ) )
{
    if ( substr( $row['Field'], 0, 7 ) == "author_" )
    {
        $array_lang_exit[] .= trim( substr( $row['Field'], 7, 2 ) );
    }
}
$select_options = array();
foreach ( $array_lang_exit as $langkey )
{
    $select_options[NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;dirlang=" . $langkey] = $language_array[$langkey]['name'];
}

$dirlang_old = $nv_Request->get_string( 'dirlang', 'cookie', NV_LANG_DATA );
$dirlang = $nv_Request->get_string( 'dirlang', 'get', $dirlang_old );
if ( ! in_array( $dirlang, $array_lang_exit ) )
{
    $dirlang = $global_config['site_lang'];
}
if ( $dirlang_old != $dirlang )
{
    $nv_Request->set_Cookie( 'dirlang', $dirlang, NV_LIVE_COOKIE_TIME );
}
$query = "SELECT `idfile`, `module`, `admin_file`, `langtype`, `author_" . $dirlang . "` FROM `" . NV_LANGUAGE_GLOBALTABLE . "_file` ORDER BY `idfile` ASC";
$result = $db->sql_query( $query );
if ( $db->sql_numrows( $result ) == 0 )
{
    $contents = "<center><br /><b>" . $lang_module['nv_lang_error_exit'] . "</b></center>";
    $contents .= "<meta http-equiv=\"Refresh\" content=\"3;URL=" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=read&dirlang=" . $dirlang . "&checksess=" . md5( "readallfile" . session_id() ) . "\" />";
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}
$a = 1;
$page_title = $lang_module['nv_lang_interface'] . " -> " . $language_array[$dirlang]['name'];

$contents .= "<table summary=\"\" class=\"tab1\">\n";
$contents .= "<thead>";
$contents .= "<tr align=\"center\">";
$contents .= "<td>" . $lang_module['nv_lang_nb'] . "</td>";
$contents .= "<td>" . $lang_module['nv_lang_module'] . "</td>";
$contents .= "<td>" . $lang_module['nv_lang_area'] . "</td>";
$contents .= "<td>" . $lang_module['nv_lang_author'] . "</td>";
$contents .= "<td>" . $lang_module['nv_lang_createdate'] . "</td>";
$contents .= "<td>" . $lang_module['nv_lang_func'] . "</td>";
$contents .= "</tr>";
$contents .= "</thead>";
$a = 0;
while ( list( $idfile, $module, $admin_file, $langtype, $author_lang ) = $db->sql_fetchrow( $result ) )
{
    //$array_translator = unserialize( base64_decode( $author_lang ) );
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
    
    if ( empty( $author_lang ) )
    {
        $array_translator = array();
        $array_translator['author'] = "";
        $array_translator['createdate'] = "";
        $array_translator['copyright'] = "";
        $array_translator['info'] = "";
        $array_translator['langtype'] = "";
    }
    else
    {
        eval( '$array_translator = ' . $author_lang . ';' );
    }
    $class = ( $a % 2 ) ? " class=\"second\"" : "";
    $a ++;
    $contents .= "<tbody" . $class . ">\n";
    $contents .= "<tr>";
    $contents .= " <td align=\"center\">" . $a . "</td>";
    $contents .= " <td>" . $module . "</td>";
    $contents .= " <td>" . $langsitename . "</td>";
    $contents .= " <td align=\"center\">" . $array_translator['author'] . "</td>";
    $contents .= " <td align=\"center\">" . $array_translator['createdate'] . "</td>";
    $contents .= " <td align=\"center\"><span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=edit&amp;dirlang=" . $dirlang . "&amp;idfile=" . $idfile . "&amp;checksess=" . md5( $idfile . session_id() ) . "\">" . $lang_module['nv_admin_edit'] . "</a></span>";
    $contents .= "     - <span class=\"default_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=write&amp;dirlang=" . $dirlang . "&amp;idfile=" . $idfile . "&amp;checksess=" . md5( $idfile . session_id() ) . "\">" . $lang_module['nv_admin_write'] . "</a></span>";
    $contents .= " </td>";
    $contents .= "</tr>";
    $contents .= "</tbody>";
}
$contents .= "</table>";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>