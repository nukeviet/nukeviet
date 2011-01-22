<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );
$title = $note = $modfile = "";
if ( filter_text_input( 'checkss', 'post' ) == md5( session_id() . "addmodule" ) )
{
    $title = filter_text_input( 'title', 'post', '', 1 );
    $modfile = filter_text_input( 'module_file', 'post', '', 1 );
    $note = filter_text_input( 'note', 'post', '', 1 );
    $title = strtolower( change_alias( $title ) );
    if ( ! empty( $title ) and ! empty( $modfile ) and preg_match( $global_config['check_module'], $title ) and preg_match( $global_config['check_module'], $modfile ) )
    {
        $mod_version = "";
        $author = "";
        $note = nv_nl2br( $note, '<br />' );
        $module_data = preg_replace( '/(\W+)/i', '_', $title );
        $ok = $db->sql_query( "INSERT INTO `" . $db_config['prefix'] . "_setup_modules` (`title`, `is_sysmod`, `virtual`, `module_file`, `module_data`, `mod_version`, `addtime`, `author`, `note`) VALUES (" . $db->dbescape( $title ) . ", '0', '0', " . $db->dbescape( $modfile ) . ", " . $db->dbescape( $module_data ) . ", " . $db->dbescape( $mod_version ) . ", '" . NV_CURRENTTIME . "', " . $db->dbescape( $author ) . ", " . $db->dbescape( $note ) . ")" );
        if ( $ok )
        {
            nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['vmodule_add'] . ' "'.$module_data.'"', '', $admin_info['userid'] );
        	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=setup&setmodule=" . $title . "&checkss=" . md5( $title . session_id() . $global_config['sitekey'] ) );
        }
        else
        {
            Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=setup" );
        }
        die();
    }
}

$modules_exit = array_flip( nv_scandir( NV_ROOTDIR . "/modules", $global_config['check_module'] ) );
$modules_data = array();
$sql_data = "SELECT title FROM `" . $db_config['prefix'] . "_setup_modules` WHERE `virtual`='1' ORDER BY `addtime` ASC";
$result = $db->sql_query( $sql_data );

$page_title = $lang_module['vmodule_add'];

$contents .= "<div class=\"quote\" style=\"width:810px;\">\n";
$contents .= "<blockquote><span>" . $lang_module['vmodule_blockquote'] . "</span></blockquote>\n";
$contents .= "</div>\n";
$contents .= "<div class=\"clear\"></div>\n";

$contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$contents .= "<input name=\"checkss\" type=\"hidden\" value=\"" . md5( session_id() . "addmodule" ) . "\" />\n";
$contents .= "<table summary=\"\" class=\"tab1\">\n";
$contents .= "<tbody>";
$contents .= "<tr>";
$contents .= "<td align=\"right\" style=\"width: 250px\"><strong>" . $lang_module['vmodule_name'] . ": </strong></td>\n";
$contents .= "<td><input style=\"width: 450px\" name=\"title\" type=\"text\" value=\"" . $title . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>";
$contents .= "</tbody>";
$contents .= "<tbody class=\"second\">";
$contents .= "<tr>";
$contents .= "<td align=\"right\"><strong>" . $lang_module['vmodule_file'] . ": </strong></td>\n";
$contents .= "<td>";
$contents .= "<select name=\"module_file\">\n";
$contents .= "<option value=\"\">" . $lang_module['vmodule_select'] . "</option>\n";
while ( list( $modfile_i ) = $db->sql_fetchrow( $result ) )
{
    if ( in_array( $modfile_i, $modules_exit ) )
    {
        $sl = "";
        if ( $modfile_i == $modfile )
        {
            $sl = " selected=\"selected\"";
        }
        $contents .= "<option value=\"" . $modfile_i . "\" " . $sl . ">" . $modfile_i . "</option>\n";
    }
}
$contents .= "</select>\n";
$contents .= "</td>";
$contents .= "</tr>";
$contents .= "</tbody>";
$contents .= "<tbody>";
$contents .= "<tr>";
$contents .= "<td valign=\"top\" align=\"right\"><br><strong>" . $lang_module['vmodule_note'] . ":</strong></td>\n";
$contents .= "<td>";
$contents .= "<textarea style=\"width: 450px\" name=\"note\" cols=\"80\" rows=\"5\">" . $note . "</textarea>";
$contents .= "</td>";
$contents .= "</tr>";
$contents .= "</tbody>";
$contents .= "</table>";
$contents .= "<br><center><input name=\"submit1\" type=\"submit\" value=\"" . $lang_global['submit'] . "\" /></center>\n";
$contents .= "</form>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>