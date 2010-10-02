<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['setting'];
$savesetting = $nv_Request->get_int( 'savesetting', 'post', 0 );
if ( ! empty( $savesetting ) )
{
    $array_config = array();
    $array_config['indexfile'] = filter_text_input( 'indexfile', 'post', '', 1 );
    $array_config['per_page'] = $nv_Request->get_int( 'per_page', 'post', 0 );
    $array_config['st_links'] = $nv_Request->get_int( 'st_links', 'post', 0 );
    $array_config['homewidth'] = $nv_Request->get_int( 'homewidth', 'post', 0 );
    $array_config['homeheight'] = $nv_Request->get_int( 'homeheight', 'post', 0 );
    $array_config['blockwidth'] = $nv_Request->get_int( 'blockwidth', 'post', 0 );
    $array_config['blockheight'] = $nv_Request->get_int( 'blockheight', 'post', 0 );
    $array_config['imagefull'] = $nv_Request->get_int( 'imagefull', 'post', 0 );
    
    $array_config['activecomm'] = $nv_Request->get_int( 'activecomm', 'post', 0 );
    $array_config['emailcomm'] = $nv_Request->get_int( 'emailcomm', 'post', 0 );
    $array_config['auto_postcomm'] = $nv_Request->get_int( 'auto_postcomm', 'post', 0 );
    $array_config['setcomm'] = $nv_Request->get_int( 'setcomm', 'post', 0 );
    $array_config['copyright'] = filter_text_input( 'copyright', 'post', '', 1 );
    $array_config['showhometext'] = $nv_Request->get_int( 'showhometext', 'post', 0 );
    
    foreach ( $array_config as $config_name => $config_value )
    {
        $query = "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` SET `config_value` = " . $db->dbescape( $config_value ) . " WHERE `lang`='" . NV_LANG_DATA . "' AND `module` = " . $db->dbescape( $module_name ) . " AND `config_name` = " . $db->dbescape( $config_name ) . "";
        $db->sql_query( $query );
    }
    $db->sql_freeresult();
    nv_del_moduleCache( 'settings' );
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&rand=" . nv_genpass() );
    die();
}

$contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$contents .= "<table summary=\"\" class=\"tab1\">
<tr>
    <td><strong>" . $lang_module['setting_indexfile'] . "</strong></td>
    <td><select name=\"indexfile\">";
foreach ( $array_viewcat_full as $key => $val )
{
    $contents .= "<option value=\"" . $key . "\"" . ( $key == $module_config[$module_name]['indexfile'] ? " selected=\"selected\"" : "" ) . ">" . $val . "</option>\n";
}
$contents .= "</select></td>
</tr>
<tbody class=\"second\">
<tr>
    <td><strong>" . $lang_module['setting_homesite'] . "</strong></td>
    <td>
        <input type=\"text\" value=\"" . $module_config[$module_name]['homewidth'] . "\" style=\"width: 40px;\" name=\"homewidth\"> x 
        <input type=\"text\" value=\"" . $module_config[$module_name]['homeheight'] . "\" style=\"width: 40px;\" name=\"homeheight\">
    </td>
</tr>
</tbody>

<tr>
    <td><strong>" . $lang_module['setting_thumbblock'] . "</strong></td>
    <td>
        <input type=\"text\" value=\"" . $module_config[$module_name]['blockwidth'] . "\" style=\"width: 40px;\" name=\"blockwidth\"> x 
        <input type=\"text\" value=\"" . $module_config[$module_name]['blockheight'] . "\" style=\"width: 40px;\" name=\"blockheight\">
    </td>
</tr>
<tbody class=\"second\">
<tr>
    <td><strong>" . $lang_module['setting_imagefull'] . "</strong></td>
    <td>
        <input type=\"text\" value=\"" . $module_config[$module_name]['imagefull'] . "\" style=\"width: 50px;\" name=\"imagefull\"> 
    </td>
</tr>
</tbody>
<tr>
    <td><strong>" . $lang_module['setting_per_page'] . "</strong></td>
    <td><select name=\"per_page\">";
for ( $i = 5; $i <= 30; $i ++ )
{
    $sl = "";
    if ( $i == $module_config[$module_name]['per_page'] )
    {
        $sl = " selected=\"selected\"";
    }
    
    $contents .= "<option  value=\"" . $i . "\" " . $sl . ">" . $i . "</option>";
}
$contents .= "</select></td>
</tr>
<tbody class=\"second\">
<tr>
    <td><strong>" . $lang_module['setting_st_links'] . "</strong></td>
    <td><select name=\"st_links\">";
for ( $i = 0; $i <= 20; $i ++ )
{
    $sl = "";
    if ( $i == $module_config[$module_name]['st_links'] )
    {
        $sl = " selected=\"selected\"";
    }
    
    $contents .= "<option  value=\"" . $i . "\" " . $sl . ">" . $i . "</option>";
}
$contents .= "</select></td>
</tr>
</tbody>
<tr>
    <td><strong>" . $lang_module['showhometext'] . "</strong></td>
    <td><input type=\"checkbox\" value=\"1\" name=\"showhometext\" " . ( ( $module_config[$module_name]['showhometext'] ) ? "checked=\"checked\"" : "" ) . "></td>
</tr>
<tbody class=\"second\">
<tr>
    <td><strong>" . $lang_module['activecomm'] . "</strong></td>
    <td><input type=\"checkbox\" value=\"1\" name=\"activecomm\" " . ( ( $module_config[$module_name]['activecomm'] ) ? "checked=\"checked\"" : "" ) . "></td>
</tr>
</tbody>
<tr>
    <td><strong>" . $lang_module['setting_auto_postcomm'] . "</strong></td>
    <td><input type=\"checkbox\" value=\"1\" name=\"auto_postcomm\" " . ( ( $module_config[$module_name]['auto_postcomm'] ) ? "checked=\"checked\"" : "" ) . "></td>
</tr>
<tbody class=\"second\">
<tr>
    <td><strong>" . $lang_module['setting_setcomm'] . "</strong></td>
    <td><select name=\"setcomm\">\n";
while ( list( $comm_i, $title_i ) = each( $array_allowed_comm ) )
{
    $sl = "";
    if ( $comm_i == $module_config[$module_name]['setcomm'] )
    {
        $sl = " selected=\"selected\"";
    }
    $contents .= "<option value=\"" . $comm_i . "\" " . $sl . ">" . $title_i . "</option>\n";

}
$contents .= "</select></td></tr>
</tbody>
<tr>
    <td><strong>" . $lang_module['emailcomm'] . "</strong></td>
    <td><input type=\"checkbox\" value=\"1\" name=\"emailcomm\" " . ( ( $module_config[$module_name]['emailcomm'] ) ? "checked=\"checked\"" : "" ) . "></td>
</tr>
<tbody class=\"second\">
<tr>
    <td><strong>" . $lang_module['setting_copyright'] . "</strong></td>
    <td><textarea style=\"width: 450px\" name=\"copyright\" id=\"copyright\" cols=\"20\" rows=\"4\">" . $module_config[$module_name]['copyright'] . "</textarea></td>
    </tr>
</tbody>
<tr>
    <td style=\"text-align: center;\" colspan=\"2\">
        <input type=\"submit\" value=\" " . $lang_module['save'] . " \" name=\"Submit1\">
        <input type=\"hidden\" value=\"1\" name=\"savesetting\">
    </td>
</tr>
</table>
</form>";
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>