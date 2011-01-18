<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['uploadconfig'];

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $type = $nv_Request->get_array( 'type', 'post' );
    $type = implode( ',', $type );

    $ext = $nv_Request->get_array( 'ext', 'post' );
    $ext[] = "php";
    $ext[] = "php3";
    $ext[] = "php4";
    $ext[] = "php5";
    $ext[] = "phtml";
    $ext[] = "inc";
    $ext = array_unique( $ext );
    $ext = implode( ',', $ext );

    $mime = $nv_Request->get_array( 'mime', 'post' );
    $mime = implode( ',', $mime );

    $upload_checking_mode = $nv_Request->get_string( 'upload_checking_mode', 'post', '' );
    if ( $upload_checking_mode != "mild" and $upload_checking_mode != "lite" ) $upload_checking_mode = "strong";

    $nv_max_size = $nv_Request->get_int( 'nv_max_size', 'post', $global_config['nv_max_size'] );
    $nv_max_size = min( nv_converttoBytes( ini_get( 'upload_max_filesize' ) ), nv_converttoBytes( ini_get( 'post_max_size' ) ), $nv_max_size );

    $db->sql_query( "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` SET `config_value`=" . $db->dbescape_string( $type ) . " WHERE `config_name` = 'file_allowed_ext' AND `lang` = 'sys' AND `module`='global' LIMIT 1" );
    $db->sql_query( "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` SET `config_value`=" . $db->dbescape_string( $ext ) . " WHERE `config_name` = 'forbid_extensions' AND `lang` = 'sys' AND `module`='global' LIMIT 1" );
    $db->sql_query( "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` SET `config_value`=" . $db->dbescape_string( $mime ) . " WHERE `config_name` = 'forbid_mimes' AND `lang` = 'sys' AND `module`='global' LIMIT 1" );
    $db->sql_query( "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` SET `config_value`=" . $db->dbescape_string( $nv_max_size ) . " WHERE `config_name` = 'nv_max_size' AND `lang` = 'sys' AND `module`='global' LIMIT 1" );
    $db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'upload_checking_mode', " . $db->dbescape_string( $upload_checking_mode ) . ")" );

    nv_save_file_config_global();

    Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
    die();
}

$ini = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/mime.ini', true );
$types = array_keys( $ini );
$extmime = array_values( $ini );
$mimes = $exts = array();
for ( $i = 0; $i < count( $extmime ); $i++ )
{
    $exts = array_merge( $exts, array_keys( $extmime[$i] ) );
    for ( $j = 0; $j < count( $exts ); $j++ )
    {
        if ( isset( $extmime[$i][$exts[$j]] ) )
        {
            $mimes = array_merge( ( array )$mimes, ( array )array_values( ( array )$extmime[$i][$exts[$j]] ) );
        }
    }
}
$mimes = array_unique( $mimes );

sort( $types );
sort( $exts );
sort( $mimes );

$contents = "<form action='' method='post'>\n";
$contents .= "<table class=\"tab1\" style='auto'>\n";
$contents .= "<tbody class='second'>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan='2'><strong>" . $lang_module['uploadconfig'] . "</strong></td>\n";
$contents .= "</tr>\n";

$contents .= "<tbody>";
$contents .= "<tr>";
$contents .= "<td align=\"right\"><strong>" . $lang_module['nv_max_size'] . ": </strong></td>\n";
$contents .= "<td>";
$contents .= "<select name=\"nv_max_size\">\n";
$sys_max_size = min( nv_converttoBytes( ini_get( 'upload_max_filesize' ) ), nv_converttoBytes( ini_get( 'post_max_size' ) ) );
$p_size = $sys_max_size / 100;
for ( $index = 100; $index > 0; $index-- )
{
    $size = floor( $index * $p_size );
    $sl = "";
    if ( $size == $global_config['nv_max_size'] )
    {
        $sl = " selected=\"selected\"";
    }
    $contents .= "<option value=\"" . $size . "\"" . $sl . ">" . nv_convertfromBytes( $size ) . "</option>\n";
}
$contents .= "</select> \n";
$contents .= " (" . $lang_module['sys_max_size'] . ": " . nv_convertfromBytes( $sys_max_size ) . ")";
$contents .= "</td>";
$contents .= "</tr>";
$contents .= "</tbody>";

$contents .= "<tbody class='second'>";
$contents .= "<tr>";
$contents .= "<td align=\"right\"><strong>" . $lang_module['upload_checking_mode'] . ": </strong></td>\n";
$contents .= "<td>";
$contents .= "<select name=\"upload_checking_mode\">\n";
$_upload_checking_mode = array( 'strong' => $lang_module['strong_mode'], 'mild' => $lang_module['mild_mode'], 'lite' => $lang_module['lite_mode'] );
foreach ( $_upload_checking_mode as $m => $n )
{
    $sl = ( $m == $global_config['upload_checking_mode'] ) ? " selected=\"selected\"" : "";
    $contents .= "<option value=\"" . $m . "\"" . $sl . ">" . $n . "</option>\n";
}
$contents .= "</select>\n";

$strong = false;
if ( function_exists( 'finfo_open' ) and ( empty( $sys_info['disable_functions'] ) or ( ! empty( $sys_info['disable_functions'] ) and ! in_array( 'finfo_open', $sys_info['disable_functions'] ) ) ) )
{
    $strong = true;
}
if ( function_exists( 'mime_content_type' ) and ( empty( $sys_info['disable_functions'] ) or ( ! empty( $sys_info['disable_functions'] ) and ! in_array( 'mime_content_type', $sys_info['disable_functions'] ) ) ) )
{
    $strong = true;
}
if ( substr( $sys_info['os'], 0, 3 ) != 'WIN' )
{
    if ( function_exists( 'system' ) and ( empty( $sys_info['disable_functions'] ) or ( ! empty( $sys_info['disable_functions'] ) and ! in_array( 'system', $sys_info['disable_functions'] ) ) ) )
    {
        $strong = true;
    }

    if ( function_exists( 'exec' ) and ( empty( $sys_info['disable_functions'] ) or ( ! empty( $sys_info['disable_functions'] ) and ! in_array( 'exec', $sys_info['disable_functions'] ) ) ) )
    {
        $strong = true;
    }
}

if ( ! $strong )
{
    $contents .= " " . $lang_module['upload_checking_note'];
}

$contents .= "</td>";
$contents .= "</tr>";
$contents .= "</tbody>";

$contents .= "</tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:200px'><strong>" . $lang_module['uploadconfig_types'] . "</strong></td>\n";
$contents .= "<td>";
foreach ( $types as $type )
{
    $contents .= "<label style='display:inline-block;width:100px'><input type='checkbox' name='type[]' value='" . $type . "' " . ( in_array( $type, $global_config['file_allowed_ext'] ) ? 'checked=checked' : '' ) . "/> " . $type . "&nbsp;&nbsp;</label>";
}
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tbody class='second'>\n";
$contents .= "<tr>\n";
$contents .= "<td style='vertical-align:top'><strong>" . $lang_module['uploadconfig_ban_ext'] . "</strong></td>\n";
$contents .= "<td>";
foreach ( $exts as $ext )
{
    $contents .= "<label style='display:inline-block;width:100px'><input type='checkbox' name='ext[]' value='" . $ext . "' " . ( in_array( $ext, $global_config['forbid_extensions'] ) ? 'checked=checked' : '' ) . "/> " . $ext . "&nbsp;&nbsp;</label>";
}
$contents .= "</td>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$contents .= "<tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td style='vertical-align:top'><strong>" . $lang_module['uploadconfig_ban_mime'] . "</strong></td>\n";
$contents .= "<td>";
foreach ( $mimes as $mime )
{
    $contents .= "<label style='display:inline-block;width:320px'><input type='checkbox' name='mime[]' value='" . $mime . "' " . ( in_array( $mime, $global_config['forbid_mimes'] ) ? 'checked=checked' : '' ) . "/> " . $mime . "&nbsp;&nbsp;</label>";
}
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td colspan='2' style='text-align:center'>";
$contents .= "<input type='submit' value='" . $lang_module['banip_confirm'] . "' name='submit'/>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "</table>\n";
$contents .= "</form>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>