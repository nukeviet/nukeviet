<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );
$set_layout_site = false;
$select_options = array();
$theme_array = nv_scandir( NV_ROOTDIR . "/themes", $global_config['check_theme'] );

foreach ( $theme_array as $themes_i )
{
    if ( file_exists( NV_ROOTDIR . '/themes/' . $themes_i . '/config.ini' ) )
    {
        $select_options[NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=setuplayout&amp;selectthemes=" . $themes_i] = $themes_i;
    }
}

$selectthemes_old = $nv_Request->get_string( 'selectthemes', 'cookie', $global_config['site_theme'] );
$selectthemes = $nv_Request->get_string( 'selectthemes', 'get', $selectthemes_old );
if ( ! in_array( $selectthemes, $theme_array ) )
{
    $selectthemes = "default";
}
if ( $selectthemes_old != $selectthemes )
{
    $nv_Request->set_Cookie( 'selectthemes', $selectthemes, NV_LIVE_COOKIE_TIME );
}

$layout_array = nv_scandir( NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', $global_config['check_op_layout'] );
if ( ! empty( $layout_array ) )
{
    $layout_array = preg_replace( $global_config['check_op_layout'], "\\1", $layout_array );
}
$array_layout_func_default = array();

$xml = simplexml_load_file( NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini' );
$layoutdefault = ( string )$xml->layoutdefault;
$layout = $xml->xpath( 'setlayout/layout' );

for ( $i = 0; $i < count( $layout ); $i ++ )
{
    $layout_name = ( string )$layout[$i]->name;
    if ( in_array( $layout_name, $layout_array ) )
    {
        $layout_funcs = $layout[$i]->xpath( 'funcs' );
        for ( $j = 0; $j < count( $layout_funcs ); $j ++ )
        {
            $mo_funcs = ( string )$layout_funcs[$j];
            $mo_funcs = explode( ":", $mo_funcs );
            $m = $mo_funcs[0];
            $arr_f = explode( ",", $mo_funcs[1] );
            foreach ( $arr_f as $f )
            {
                $array_layout_func_default[$m][$f] = $layout_name;
            }
        }
    }
}

$page_title = $lang_module['setup_layout'] . ':' . $selectthemes;

if ( $nv_Request->isset_request( 'save', 'post' ) and $nv_Request->isset_request( 'func', 'post' ) )
{
    nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['setup_layout'] . ' theme: "'.$selectthemes.'"', '', $admin_info['userid'] );
	$func_arr_save = $nv_Request->get_array( 'func', 'post' );
    foreach ( $func_arr_save as $func_id => $layout_name )
    {
        if ( in_array( $layout_name, $layout_array ) )
        {
            $sql = "UPDATE `" . NV_PREFIXLANG . "_modthemes` SET `layout`=" . $db->dbescape_string( $layout_name ) . " WHERE `func_id`='" . intval( $func_id ) . "' AND `theme`=" . $db->dbescape_string( $selectthemes ) . "";
            $db->sql_query( $sql );
        }
    }
    $set_layout_site = true;
    $contents .= "<div id='edit'></div>\n";
    $contents .= "<div class=\"quote\" style=\"width:810px;\">\n";
    $contents .= "<blockquote class='error'><span id='message'>" . $lang_module['setup_updated_layout'] . "</span></blockquote>\n";
    $contents .= "</div><div style='clear:both'></div>\n";
}

$array_layout_func_data = array();
$fnsql = "SELECT `func_id`, `layout` FROM `" . NV_PREFIXLANG . "_modthemes` WHERE `theme`='" . $selectthemes . "'";
$fnresult = $db->sql_query( $fnsql );
while ( list( $func_id, $layout ) = $db->sql_fetchrow( $fnresult ) )
{
    $array_layout_func_data[$func_id] = $layout;
}
if ( ! isset( $array_layout_func_data[0] ) )
{
    $db->sql_query( "INSERT INTO `" . NV_PREFIXLANG . "_modthemes` (`func_id`, `layout`, `theme`) VALUES ('0'," . $db->dbescape( $layoutdefault ) . ", " . $db->dbescape( $selectthemes ) . ")" );
    $set_layout_site = true;
}
elseif ( $array_layout_func_data[0] != $layoutdefault )
{
    $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_modthemes` SET `layout`=" . $db->dbescape_string( $layoutdefault ) . " WHERE `func_id`='0' AND `theme`=" . $db->dbescape_string( $selectthemes ) . "" );
    $set_layout_site = true;
}

$array_layout_func = array();
$fnsql = "SELECT func_id, func_name, func_custom_name, in_module FROM " . NV_MODFUNCS_TABLE . " WHERE show_func='1' ORDER BY `subweight` ASC";
$fnresult = $db->sql_query( $fnsql );
while ( list( $func_id, $func_name, $func_custom_name, $in_module ) = $db->sql_fetchrow( $fnresult ) )
{
    if ( isset( $array_layout_func_data[$func_id] ) and ! empty( $array_layout_func_data[$func_id] ) )
    {
        $layout_name = $array_layout_func_data[$func_id];
        if ( ! in_array( $layout_name, $layout_array ) )
        {
            $layout_name = $layoutdefault;
            $sql = "UPDATE `" . NV_PREFIXLANG . "_modthemes` SET `layout`=" . $db->dbescape_string( $layout_name ) . " WHERE `func_id`='" . intval( $func_id ) . "' AND `theme`=" . $db->dbescape_string( $selectthemes ) . "";
            $db->sql_query( $sql );
            $set_layout_site = true;
        }
    }
    else
    {
        $layout_name = ( isset( $array_layout_func_default[$in_module][$func_name] ) ) ? $array_layout_func_default[$in_module][$func_name] : $layoutdefault;
        $sql = "INSERT INTO `" . NV_PREFIXLANG . "_modthemes` (`func_id`, `layout`, `theme`) VALUES ('" . $func_id . "'," . $db->dbescape( $layout_name ) . ", " . $db->dbescape( $selectthemes ) . ")";
        $db->sql_query( $sql );
        $set_layout_site = true;
    }
    $array_layout_func[$in_module][$func_name] = array( 
        $func_id, $func_custom_name, $layout_name 
    );
}
if ( $set_layout_site )
{
    nv_set_layout_site();
}

$contents .= "<form method='post' action='' name='setuplayout'><table class=\"tab1\">\n";
$contents .= "<tr>\n";
$sql = "SELECT title, custom_title FROM `" . NV_MODULES_TABLE . "` ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
$i = 0;
while ( list( $mod_name, $mod_name_title ) = $db->sql_fetchrow( $result ) )
{
    if ( isset( $array_layout_func[$mod_name] ) )
    {
        $contents .= "<td style='vertical-align:top'><strong>" . $mod_name_title . "</strong><hr style='margin-bottom:10px'/>";
        $array_layout_func_mod = $array_layout_func[$mod_name];
        foreach ( $array_layout_func_mod as $func_name => $func_arr_val )
        {
            $contents .= '<span style="display:inline-block;width:150px">' . $func_arr_val[1] . "</span>";
            $contents .= "<select name='func[" . $func_arr_val[0] . "]' class='function'>";
            foreach ( $layout_array as $value )
            {
                $sel = ( $func_arr_val[2] == $value ) ? ' selected' : '';
                $contents .= "<option value='" . $value . "' " . $sel . ">" . $value . "</option>";
            }
            $contents .= "</select><br/>";
        }
        if ( $i < 3 )
        {
            $contents .= "</td>\n";
            $i ++;
        }
        if ( $i == 3 )
        {
            $contents .= "</tr><tr>\n";
            $i = 0;
        }
    }
}
$contents .= "</td></tr>\n";
$contents .= "<tr><td colspan='3' style='text-align:center'><input name='save' type='submit' value='" . $lang_module['setup_save_layout'] . "'/></td></tr>\n";
$contents .= "</table>\n";
$contents .= "</form>\n";
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>