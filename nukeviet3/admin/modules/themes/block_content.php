<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$functionid = $nv_Request->get_int( 'func', 'get' );
$blockredirect = $nv_Request->get_string( 'blockredirect', 'get' );
$select_options = array();
$contents_error = '';
$theme_array = nv_scandir( NV_ROOTDIR . "/themes", $global_config['check_theme'] );
foreach ( $theme_array as $themes_i )
{
    $select_options[NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=add&selectthemes=" . $themes_i] = $themes_i;
}
$selectthemes = $nv_Request->get_string( 'selectthemes', 'post,get,cookie', $global_config['site_theme'] );

$row = array( 
    'bid' => 0, 'theme' => '', 'module' => 'global', 'file_name' => '', 'title' => '', 'link' => '', 'template' => '', 'position' => '', 'exp_time' => 0, 'active' => 1, 'groups_view' => '', 'all_func' => 1, 'weight' => 0, 'config' => '' 
);
$row['bid'] = $nv_Request->get_int( 'bid', 'get,post', 0 );
$submit = 0;
if ( $nv_Request->isset_request( 'confirm', 'post' ) )
{
    $submit = 1;
    $error = array();
    $list_file_name = filter_text_input( 'file_name', 'post', '', 0 );
    $array_file_name = explode( "|", $list_file_name );
    
    $file_name = $row['file_name'] = trim( $array_file_name[0] );
    $module = $row['module'] = filter_text_input( 'module', 'post', '', 0, 55 );
    $row['title'] = filter_text_input( 'title', 'post', '', 1, 255 );
    
    $path_file_php = $path_file_ini = '';
    unset( $matches );
    preg_match( $global_config['check_block_module'], $row['file_name'], $matches );
    if ( ! empty( $array_file_name[1] ) )
    {
        if ( $module == 'global' and file_exists( NV_ROOTDIR . '/includes/blocks/' . $file_name ) and file_exists( NV_ROOTDIR . '/includes/blocks/' . $matches[1] . '.' . $matches[2] . '.ini' ) )
        {
            $path_file_php = NV_ROOTDIR . '/includes/blocks/' . $file_name;
            $path_file_ini = NV_ROOTDIR . '/includes/blocks/' . $matches[1] . '.' . $matches[2] . '.ini';
        }
        elseif ( isset( $site_mods[$module] ) )
        {
            $module_file = $site_mods[$module]['module_file'];
            if ( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $file_name ) and file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini' ) )
            {
                $path_file_php = NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $file_name;
                $path_file_ini = NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini';
            }
        }
    }
    
    if ( empty( $row['title'] ) )
    {
        $row['title'] = str_replace( "_", " ", $matches[1] . ' ' . $matches[2] );
    }
    
    $row['link'] = filter_text_input( 'link', 'post' );
    if ( ! empty( $row['link'] ) and ! nv_is_url( $row['link'] ) )
    {
        $error[] = $lang_module['error_invalid_url'];
    }
    
    $row['template'] = filter_text_input( 'template', 'post', '', 0, 55 );
    $row['position'] = filter_text_input( 'position', 'post', '', 0, 55 );
    
    $exp_time = filter_text_input( 'exp_time', 'post', "", 1 );
    if ( ! empty( $exp_time ) && preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $exp_time ) )
    {
        $exp_time = explode( '/', $exp_time );
        $row['exp_time'] = mktime( 0, 0, 0, $exp_time[1], $exp_time[0], $exp_time[2] );
    }
    else
    {
        $row['exp_time'] = 0;
    }
    $row['active'] = $nv_Request->get_int( 'active', 'post', 0 );
    
    $who_view = $nv_Request->get_int( 'who_view', 'post', 0 );
    if ( $who_view < 0 or $who_view > 3 ) $who_view = 0;
    $groups_view = "";
    if ( $who_view == 3 )
    {
        $groups_view = $nv_Request->get_array( 'groups_view', 'post', array() );
        $row['groups_view'] = ! empty( $groups_view ) ? implode( ",", array_map( "intval", $groups_view ) ) : "";
    }
    else
    {
        $row['groups_view'] = ( string )$who_view;
    }
    
    $all_func = ( $nv_Request->get_int( 'all_func', 'post' ) == 1 and preg_match( $global_config['check_block_global'], $row['file_name'] ) ) ? 1 : 0;
    $array_funcid = $nv_Request->get_array( 'func_id', 'post' );
    if ( empty( $all_func ) and empty( $array_funcid ) )
    {
        $error[] = $lang_module['block_no_func'];
    }
    
    $row['leavegroup'] = $nv_Request->get_int( 'leavegroup', 'post', 0 );
    if ( ! empty( $row['leavegroup'] ) and ! empty( $row['bid'] ) )
    {
        $all_func = 0;
        $row['leavegroup'] = 1;
    }
    else
    {
        $row['leavegroup'] = 0;
    }
    $row['all_func'] = $all_func;
    $row['config'] = "";
    
    if ( ! empty( $path_file_php ) and ! empty( $path_file_ini ) )
    {
        //  load cac cau hinh cua block
        $xml = simplexml_load_file( $path_file_ini );
        if ( $xml !== false )
        {
            $submit_function = trim( $xml->submitfunction );
            if ( ! empty( $submit_function ) )
            {
                // neu ton tai function de xay dung cau truc cau hinh block
                include_once ( $path_file_php );
                if ( function_exists( $submit_function ) )
                {
                    $lang_block = array(); // Ngon ngu cua block
                    $xmllanguage = $xml->xpath( 'language' );
                    $language = ( array )$xmllanguage[0];
                    if ( isset( $language[NV_LANG_INTERFACE] ) )
                    {
                        $lang_block = ( array )$language[NV_LANG_INTERFACE];
                    }
                    elseif ( isset( $language['en'] ) )
                    {
                        $lang_block = ( array )$language['en'];
                    }
                    else
                    {
                        $key = array_keys( $array_config );
                        $lang_block = array_combine( $key, $key );
                    }
                    
                    // Goi ham xu ly hien thi block
                    $array_config = call_user_func( $submit_function, $module, $lang_block );
                    if ( ! empty( $array_config['config'] ) )
                    {
                        $row['config'] = serialize( $array_config['config'] );
                    }
                    else
                    {
                        $row['config'] = "";
                    }
                    
                    if ( ! empty( $array_config['error'] ) )
                    {
                        $error = array_merge( $error, $array_config['error'] );
                    }
                }
            }
        }
    }
    
    if ( ! empty( $error ) )
    {
        $contents_error .= "<div id='edit'></div>\n";
        $contents_error .= "<div class=\"quote\" style=\"width:780px;\">\n";
        $contents_error .= "<blockquote class='error'><span id='message'>" . implode( "<br>", $error ) . "</span></blockquote>\n";
        $contents_error .= "</div>\n";
    }
    else
    {
        if ( $all_func )
        {
            $array_funcid = array();
            $func_result = $db->sql_query( "SELECT `func_id` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `show_func` = '1' ORDER BY `in_module` ASC, `subweight` ASC" );
            while ( list( $func_id_i ) = $db->sql_fetchrow( $func_result ) )
            {
                $array_funcid[] = $func_id_i;
            }
        }
        else if ( ! empty( $row['module'] ) and isset( $site_mods[$row['module']] ) )
        {
            $array_funcid_module = array();
            $func_result = $db->sql_query( "SELECT `func_id` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `show_func` = '1' AND `in_module`='" . $row['module'] . "' ORDER BY `in_module` ASC, `subweight` ASC" );
            while ( list( $func_id_i ) = $db->sql_fetchrow( $func_result ) )
            {
                $array_funcid_module[] = $func_id_i;
            }
            $array_funcid = array_intersect( $array_funcid, $array_funcid_module );
        }
        
        if ( is_array( $array_funcid ) )
        {
            //Tach va tao nhom moi
            if ( ! empty( $row['leavegroup'] ) )
            {
                $db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "_groups` SET all_func='0' WHERE `bid`=" . $row['bid'] );
                $db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE `bid`=" . $row['bid'] . " AND `func_id` in (" . implode( ",", $array_funcid ) . ")" );
                $row['bid'] = 0;
            }
            
            if ( empty( $row['bid'] ) )
            {
                list( $maxweight ) = $db->sql_fetchrow( $db->sql_query( "SELECT MAX(weight) FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE theme =" . $db->dbescape( $selectthemes ) . " AND `position`=" . $db->dbescape( $row['position'] ) ) );
                $row['weight'] = intval( $maxweight ) + 1;
                $row['bid'] = $db->sql_query_insert_id( "INSERT INTO `" . NV_BLOCKS_TABLE . "_groups` (`bid`, `theme`, `module`, `file_name`, `title`, `link`, `template`, `position`, `exp_time`, `active`, `groups_view`, `all_func`, `weight`, `config`) VALUES ( NULL, " . $db->dbescape( $selectthemes ) . ", " . $db->dbescape( $row['module'] ) . ", " . $db->dbescape( $row['file_name'] ) . ", " . $db->dbescape( $row['title'] ) . ", " . $db->dbescape( $row['link'] ) . ", " . $db->dbescape( $row['template'] ) . ", " . $db->dbescape( $row['position'] ) . ", '" . $row['exp_time'] . "', '" . $row['active'] . "', " . $db->dbescape( $row['groups_view'] ) . ", '" . $row['all_func'] . "', '" . $row['weight'] . "', " . $db->dbescape( $row['config'] ) . " )" );
            }
            else
            {
                $db->sql_query( "UPDATE `" . NV_BLOCKS_TABLE . "_groups` SET 
                `module`=" . $db->dbescape( $row['module'] ) . ", 
                `file_name`=" . $db->dbescape( $row['file_name'] ) . ", 
                `title`=" . $db->dbescape( $row['title'] ) . ", 
                `link`=" . $db->dbescape( $row['link'] ) . ", 
                `template`=" . $db->dbescape( $row['template'] ) . ", 
                `position`=" . $db->dbescape( $row['position'] ) . ", 
                `exp_time`=" . $row['exp_time'] . ", 
                `active`=" . $row['active'] . ", 
                `groups_view`=" . $db->dbescape( $row['groups_view'] ) . ", 
                `all_func`=" . $row['all_func'] . ", 
                `config`=" . $db->dbescape( $row['config'] ) . "
                WHERE `bid` =" . $row['bid'] );
            }
            if ( ! empty( $row['bid'] ) )
            {
                $func_list = array();
                $result_func = $db->sql_query( "SELECT func_id FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE bid=" . $row['bid'] );
                while ( list( $func_inlist ) = $db->sql_fetchrow( $result_func ) )
                {
                    $func_list[] = $func_inlist;
                }
                $array_funcid_old = array_diff( $func_list, $array_funcid );
                if ( ! empty( $array_funcid_old ) )
                {
                    $db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE `bid`=" . $row['bid'] . " AND `func_id` in (" . implode( ",", $array_funcid_old ) . ")" );
                }
                foreach ( $array_funcid as $func_id )
                {
                    if ( ! in_array( $func_id, $func_list ) )
                    {
                        $sql = "SELECT MAX(t1.weight) FROM `" . NV_BLOCKS_TABLE . "_weight` AS t1 INNER JOIN `" . NV_BLOCKS_TABLE . "_groups` AS t2 ON t1.bid = t2.bid WHERE t1.func_id=" . $func_id . " AND t2.theme=" . $db->dbescape( $selectthemes ) . " AND t2.position=" . $db->dbescape( $row['position'] ) . "";
                        list( $weight ) = $db->sql_fetchrow( $db->sql_query( $sql ) );
                        $weight = intval( $weight ) + 1;
                        
                        $db->sql_query( "INSERT INTO `" . NV_BLOCKS_TABLE . "_weight` (`bid`, `func_id`, `weight`) VALUES ('" . $row['bid'] . "', '" . $func_id . "', '" . $weight . "')" );
                    }
                }
                
                nv_del_moduleCache( 'themes' );
                if ( empty( $blockredirect ) )
                {
                    $blockredirect = 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks';
                }
                echo '<script type="text/javascript">
    					parent.location="' . nv_base64_decode( $blockredirect ) . '";
    				</script>';
                die();
            }
        }
        elseif ( ! empty( $row['bid'] ) )
        {
            $db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `bid`=" . $row['bid'] );
            $db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE `bid`=" . $row['bid'] );
            nv_del_moduleCache( 'themes' );
        }
    }

}
if ( $row['bid'] > 0 and $submit == 0 )
{
    $result = $db->sql_query( "SELECT * FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE bid=" . $row['bid'] . "" );
    if ( $db->sql_numrows( $result ) > 0 )
    {
        $row = $db->sql_fetchrow( $result );
    }
}

$who_view = 3;
$groups_view = array();
if ( empty( $row['groups_view'] ) or $row['groups_view'] == "1" or $row['groups_view'] == "2" )
{
    $who_view = intval( $row['groups_view'] );
}
else
{
    $groups_view = array_map( "intval", explode( ",", $row['groups_view'] ) );
}

$sql = "SELECT `func_id` , `func_custom_name` , `in_module` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `show_func` = '1' ORDER BY `in_module` ASC, `subweight` ASC";
$func_result = $db->sql_query( $sql );
$aray_mod_func = array();
while ( list( $id_i, $func_custom_name_i, $in_module_i ) = $db->sql_fetchrow( $func_result ) )
{
    $aray_mod_func[$in_module_i][] = array( 
        "id" => $id_i, "func_custom_name" => $func_custom_name_i 
    );
}
#load position file
$xml = @simplexml_load_file( NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini' ) or nv_info_die( $lang_global['error_404_title'], $lang_module['block_error_fileconfig_title'], $lang_module['block_error_fileconfig_content'] );
$xmlpositions = $xml->xpath( 'positions' ); //array
$positions = $xmlpositions[0]->position; //object


$contents = "<link rel=\"StyleSheet\" href=\"" . NV_BASE_SITEURL . "themes/" . $global_config['admin_theme'] . "/css/admin.css\" type=\"text/css\" />";
$contents .= "<link type='text/css' href='" . NV_BASE_SITEURL . "js/ui/jquery.ui.all.css' rel='stylesheet' />\n";
$contents .= "<script type=\"text/javascript\">\n";
$contents .= "	var nv_siteroot = '" . NV_BASE_SITEURL . "'\n";
$contents .= "	var htmlload = '<tr><td align=\"center\" colspan=\"2\"<img src=\"" . NV_BASE_SITEURL . "/images/load_bar.gif\"/></td></tr>';\n";
$contents .= "</script>\n";
$contents .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/" . NV_LANG_INTERFACE . ".js\"></script>\n";
$contents .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/global.js\"></script>\n";
$contents .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/admin.js\"></script>\n";
$contents .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.min.js\"></script>\n";
$contents .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery-ui-1.8.2.custom.js\"></script>\n";
$contents .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/popcalendar/popcalendar.js\"></script>\n";
if ( $row['bid'] != 0 )
{
    $contents .= "<div class=\"quote\" style=\"width:740px;\">\n";
    $contents .= "<blockquote class='error'><span id='message'>" . $lang_module['block_group_notice'] . "</span></blockquote>\n";
    $contents .= "</div>\n";
}
$contents .= $contents_error;
$contents .= "<div style='clear:both'></div>";
$contents .= "<form method='post' action='" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=themes&" . NV_OP_VARIABLE . "=" . $op . "&selectthemes=" . $selectthemes . "&blockredirect=" . $blockredirect . "'>";
$contents .= "<table class=\"tab1\" style=\"WIDTH:100%\">\n";
$contents .= "<col style=\"width: 160px; white-space: nowrap;\">";
$contents .= "<col style=\"width: 600px; white-space: nowrap;\">";

$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_type'] . ":</td>\n";
$contents .= "<td>";
$contents .= "<select name='module'>";
$contents .= "<option value=\"\"> " . $lang_module['block_select_type'] . "</option>";
$contents .= "<option value=\"global\" " . ( ( $row['module'] == 'global' ) ? ' selected' : '' ) . "> " . $lang_module['block_type_global'] . "</option>";
$sql = "SELECT title, custom_title FROM `" . NV_MODULES_TABLE . "` ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
while ( list( $m_title, $m_custom_title ) = $db->sql_fetchrow( $result ) )
{
    if ( isset( $aray_mod_func[$m_title] ) and count( $aray_mod_func[$m_title] ) > 0 )
    {
        $sel = ( $m_title == trim( $row['module'] ) ) ? ' selected' : '';
        $contents .= "<option value=\"" . $m_title . "\" " . $sel . "> " . $m_custom_title . "</option>";
    }
}
$contents .= "</select>";
$contents .= "<select name=\"file_name\"><option value=\"\">" . $lang_module['block_select'] . "</option></select>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$contents .= "<tbody id='block_config'>\n";
$contents .= "</tbody>\n";
$contents .= "<tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_title'] . ":</td>\n";
$contents .= "<td><input name=\"title\" type=\"text\" value=\"" . $row['title'] . "\" style=\"width:300px\"/></td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_link'] . ":</td>\n";
$contents .= "<td><input name=\"link\" type=\"text\" value=\"" . $row['link'] . "\" style=\"width:500px\"/></td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$contents .= "<tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_tpl'] . ":</td>\n";
$contents .= "<td>";
$contents .= "<select id=\"template\" name=\"template\">\n";
$contents .= "<option value=\"\">" . $lang_module['block_default'] . "</option>\n";
$templ_list = nv_scandir( NV_ROOTDIR . "/themes/" . $selectthemes . "/layout", "/^block\.([a-zA-Z0-9\-\_]+)\.tpl$/" );
$templ_list = preg_replace( "/^block\.([a-zA-Z0-9\-\_]+)\.tpl$/", "\\1", $templ_list );
foreach ( $templ_list as $value )
{
    if ( ! empty( $value ) and $value != "default" )
    {
        $sel = ( $row['template'] == $value ) ? ' selected' : '';
        $contents .= "<option value=\"" . $value . "\" " . $sel . ">" . $value . "</option>\n";
    }
}
$contents .= "</select>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_pos'] . ":</td>\n";
$contents .= "<td>";
$contents .= "<select name='position'>";
$tag = $nv_Request->get_string( 'tag', 'get' );
for ( $i = 0; $i < count( $positions ); $i ++ )
{
    $sel = ( $tag == $positions[$i]->tag || $row['position'] == $positions[$i]->tag ) ? ' selected' : '';
    $contents .= "<option value=\"" . $positions[$i]->tag . "\" " . $sel . "> " . $positions[$i]->name . '</option>';
}

$contents .= "</select>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$contents .= "<tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_exp_time'] . ":</td>\n";
$contents .= "<td>";
$contents .= "<input name=\"exp_time\" id=\"exp_time\" value=\"" . ( ( $row['exp_time'] > 0 ) ? date( 'd.m.Y', $row['exp_time'] ) : '' ) . "\" style=\"width: 90px;\" maxlength=\"10\" type=\"text\">\n";
$contents .= "<img src=\"" . NV_BASE_SITEURL . "images/calendar.jpg\" widht=\"18\" style=\"cursor: pointer; vertical-align: middle;\" onclick=\"popCalendar.show(this, 'exp_time', 'dd/mm/yyyy', false);\" alt=\"\" height=\"17\"> (dd/mm/yyyy)\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_active'] . ":</td>\n";
$sel = ( intval( $row['active'] ) == 1 ) ? "checked=\"checked\"" : "";
$contents .= "<td><input type=\"checkbox\" name=\"active\" value=\"1\" " . $sel . " /> " . $lang_module['block_yes'] . "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$contents .= "<tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_group'] . ":</td>\n";
$contents .= "<td>";
$array_who_view = array( 
    $lang_global['who_view0'], $lang_global['who_view1'], $lang_global['who_view2'], $lang_global['who_view3'] 
);
$contents .= "<select name=\"who_view\" style=\"width: 250px;\" id=\"who_view\" onchange=\"nv_sh('who_view','groups_list')\">\n";
$row['groups_view'] = intval( $row['groups_view'] );
foreach ( $array_who_view as $k => $w )
{
    $contents .= "<option value=\"" . $k . "\" " . ( ( $k == $row['groups_view'] ) ? ' selected' : '' ) . ">" . $w . "</option>\n";
}
$contents .= "</select>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$contents .= "<tbody id=\"groups_list\" style=\"" . ( $who_view == 3 ? "visibility: visible; display: table-row-group;" : "visibility: hidden; display: none;" ) . "\">\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_global['groups_view'] . ":</td>\n";
$contents .= "<td>\n";
$groups_list = nv_groups_list();
foreach ( $groups_list as $group_id => $grtl )
{
    $contents .= "<p><input name=\"groups_view[]\" type=\"checkbox\" value=\"" . $group_id . "\"";
    if ( in_array( $group_id, $groups_view ) ) $contents .= " checked=\"checked\"";
    $contents .= " />&nbsp;" . $grtl . "</p>\n";
}
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

if ( $row['bid'] != 0 )
{
    $contents .= "<tbody>\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $lang_module['block_groupbl'] . ":</td>\n";
    $contents .= "<td><span style='color:red;font-weight:bold'>" . $row['bid'] . "</span>";
    list( $blocks_num ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE `bid`=" . $row['bid'] . "" ) );
    $contents .= "&nbsp;&nbsp;&nbsp;<label><input type='checkbox' value='1' name='leavegroup'/>  " . $lang_module['block_leavegroup'] . ' (' . $blocks_num . ' ' . $lang_module['block_count'] . ')</label>';
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
}
$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['add_block_module'] . ":</td>\n";
$contents .= "<td>";
$add_block_module = array( 
    1 => $lang_module['add_block_all_module'], 0 => $lang_module['add_block_select_module'] 
);
$i = 1;
foreach ( $add_block_module as $b_key => $b_value )
{
    $ck = ( $row['all_func'] == $b_key ) ? " checked=\"checked\"" : "";
    
    $showsdisplay = ( ! preg_match( $global_config['check_block_global'], $row['file_name'] ) and $b_key == 1 ) ? " style='display:none'" : "";
    $contents .= "<label id='labelmoduletype" . $i . "' " . $showsdisplay . "><input type=\"radio\" name=\"all_func\" class='moduletype" . $i . "' value=\"" . $b_key . "\" " . $ck . " />  " . $b_value . "</label> ";
    $i ++;
}
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$shows_all_func = ( intval( $row['all_func'] ) ) ? " style='display:none' " : "";
$contents .= "<tbody " . $shows_all_func . " id='shows_all_func'>\n";
$contents .= "<tr>\n";
$contents .= "<td style='vertical-align:top'>" . $lang_module['block_function'] . ":<br><br><label><input type='button' name='checkmod' value='" . $lang_module['block_check'] . "'style='margin-bottom:5px;'/></label></td>\n";
$contents .= "<td>\n";
$contents .= "<div style=\"width: 600px; overflow: auto;\"><table border=\"0\" cellpadding=\"3\" cellspacing=\"3\">";
$func_list = array();
if ( $row['bid'] )
{
    $result_func = $db->sql_query( "SELECT func_id FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE `bid`=" . $row['bid'] );
    while ( list( $func_inlist ) = $db->sql_fetchrow( $result_func ) )
    {
        $func_list[] = $func_inlist;
    }
}
$sql = "SELECT title, custom_title FROM `" . NV_MODULES_TABLE . "` ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
while ( list( $m_title, $m_custom_title ) = $db->sql_fetchrow( $result ) )
{
    if ( isset( $aray_mod_func[$m_title] ) and count( $aray_mod_func[$m_title] ) > 0 )
    {
        $contents .= "<tbody class=\"funclist\" id=\"idmodule_$m_title\">\n";
        $contents .= "<tr><td style=\"font-weight:bold\" nowrap=\"nowrap\"> " . $m_custom_title . "</td>";
        foreach ( $aray_mod_func[$m_title] as $aray_mod_func_i )
        {
            $sel = ( in_array( $aray_mod_func_i['id'], $func_list ) || $functionid == $aray_mod_func_i['id'] ) ? ' checked=checked' : '';
            $contents .= "<td nowrap=\"nowrap\"><label><input style type=\"checkbox\" " . $sel . " name=\"func_id[]\" value=\"" . $aray_mod_func_i['id'] . "\"> " . $aray_mod_func_i['func_custom_name'] . "</label></td>";
        }
        $contents .= "</tr>";
        $contents .= "</tbody>\n";
    }
}
$contents .= "</table></div>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$contents .= "</table>\n";
$contents .= "<div style=\"PADDING:10px;text-align:center\">\n";
$contents .= "<input type='hidden' name='bid' value='" . $row['bid'] . "'/>";
$contents .= "<input type=\"submit\" name='confirm' value=\"" . $lang_module['block_confirm'] . "\" />\n";
$contents .= "</div>\n";
$contents .= "</form>\n";
$contents .= "<br>\n";

$load_block_config = false;

unset( $matches );
if ( preg_match( $global_config['check_block_module'], $row['file_name'], $matches ) )
{
    if ( $row['module'] == 'global' and file_exists( NV_ROOTDIR . '/includes/blocks/' . $row['file_name'] ) and file_exists( NV_ROOTDIR . '/includes/blocks/' . $matches[1] . '.' . $matches[2] . '.ini' ) )
    {
        $load_block_config = true;
    }
    elseif ( isset( $site_mods[$row['module']] ) )
    {
        $module_file = $site_mods[$row['module']]['module_file'];
        if ( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $row['file_name'] ) and file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini' ) )
        {
            $load_block_config = true;
        }
        $contents .= '<script type="text/javascript">
					$("tbody.funclist").css({"display":"none"});
					$("tbody#idmodule_' . $row['module'] . '").css({"display":"block"});
   				 </script>';
    }
}

if ( $load_block_config )
{
    $contents .= '<script type="text/javascript">
	    			$("#block_config").show();
				$("#block_config").html(htmlload);
				$.get("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=block_config&bid=' . $row['bid'] . '&module=' . $row['module'] . '&file_name=' . $row['file_name'] . '", function(theResponse){
					if (theResponse.length>10){	
						$("#block_config").html(theResponse);
					}
					else{
						$("#block_config").hide();
					}
				});
    </script>';
}

$contents .= '<script type="text/javascript">
	$("select[name=file_name]").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=loadblocks&module=' . $row['module'] . '&bid=' . $row['bid'] . '");
	$(function(){
		$("select[name=module]").change(function(){
			var type = $("select[name=module]").val();
			$("select[name=file_name]").html("");
			if (type!=""){
				$("#block_config").html("");
				$("#block_config").hide();
				$("select[name=file_name]").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=loadblocks&module="+type);
			}
		});
		
		$("select[name=file_name]").change(function(){
			var file_name = $("select[name=file_name]").val();
			var type = $("select[name=module]").val();
			if(file_name.substring(0,7)=="global."){
				$("tbody.funclist").css({"display":""});
				$("#labelmoduletype1").css({"display":""});	
			}
			else{
				$("#labelmoduletype1").css({"display":"none"});		
				$("tbody.funclist").css({"display":"none"});
				$("tbody#idmodule_"+type).css({"display":"block"});
				var $radios = $("input:radio[name=all_func]");
	        	$radios.filter("[value=0]").attr("checked", true);
	        	$("#shows_all_func").show();
			}
			var blok_file_name = "";
			if(file_name!=""){
				var arr_file = file_name.split("|");
				if(parseInt(arr_file[1])==1){
					blok_file_name = arr_file[0];
				}
			}
			if(blok_file_name!=""){
				$("#block_config").show();
				$("#block_config").html(htmlload);
				$.get("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=block_config&bid=' . $row['bid'] . '&module="+type+"&file_name="+blok_file_name, function(theResponse){
					if (theResponse.length>10){	
						$("#block_config").html(theResponse);
					}
					else{
						$("#block_config").hide();
					}
				});
			}
			else{
				$("#block_config").hide();
			}
		});
		
		$("input[name=all_func]").click(function(){
			var module = $("select[name=module]").val();
			var af = $(this).val();
	    	if (af=="0" && module!="global"){
	    		$("#shows_all_func").show();
	    	} else if (module=="global" && af==0){
	    		$("#shows_all_func").show();
	    	} else if (af==1) {
	    		$("#shows_all_func").hide();
	    	}
		});
		
		$("input[name=leavegroup]").click(function(){
			var lv = $("input[name=\'leavegroup\']:checked").val();
			if(lv=="1"){
				var $radios = $("input:radio[name=all_func]");
	        	$radios.filter("[value=0]").attr("checked", true);
	        	$("#shows_all_func").show();
			}
		});		
	
		$("input[name=checkmod]").toggle(function(){
			$("input[name=\'func_id[]\']:checkbox").each(function(){
				$("input[name=\'func_id[]\']:visible").attr("checked","checked");			
			});
		},function(){
			$("input[name=\'func_id[]\']:checkbox").each(function(){
				$("input[name=\'func_id[]\']:visible").removeAttr("checked");
			});
			}
		);
		$("select[name=who_view]").change(function(){
			var groups = $("select[name=who_view]").val();
			if (groups==3){
				$("#groups_list").show();
			} else {
				$("#groups_list").hide();
			}
		});
		
		$("input[name=confirm]").click(function(){
			var leavegroup = $("input[name=leavegroup]").is(":checked")?1:0;
			var all_func = $("input[name=\'all_func\']:checked").val();
			if(all_func==0){
	    		var funcid = [];
	    		$("input[name=\'func_id[]\']:checked").each(function(){
	    			funcid.push($(this).val());
	    		});
	    		if (funcid.length<1){
	    			alert("' . $lang_module['block_no_func'] . '");
	    			return false;
	    		}
			}
			var who_view = $("select[name=who_view]").val();
			if (who_view==3){
		        var grouplist = [];
		        $("input[name=\'groups_view[]\']:checked").each(function(){
		        	grouplist.push($(this).val());
		        });
		        if (grouplist.length<1){
			        alert("' . $lang_module['block_error_nogroup'] . '");
			        return false;
		        }
	        }
		});
	});
</script>';

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>