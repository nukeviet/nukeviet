<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 5:53
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$menu_top = array( 
    "title" => $module_name, "module_file" => "", "custom_title" => $lang_global['mod_modules'] 
);
if ( $module_name == "modules" )
{
    $submenu['setup'] = $lang_module['modules'];
    $submenu['vmodule'] = $lang_module['vmodule_add'];
    
    $allow_func = array( 
        'main', 'list', 'setup', 'vmodule', 'edit', 'del', 'change_inmenu', 'change_submenu', 'change_weight', 'change_act', 'empty_mod', 'recreate_mod', 'show', 'change_func_weight', 'change_custom_name', 'change_func_submenu', 'change_block_weight' 
    );
    
    if ( defined( "NV_IS_GODADMIN" ) )
    {
        $submenu['autoinstall'] = $lang_module['autoinstall'];
        $allow_func[] = "autoinstall";
        $allow_func[] = "install_module";
        $allow_func[] = "install_package";
        $allow_func[] = "install_check";
        $allow_func[] = "getfile";
    }
}
define( 'NV_IS_FILE_MODULES', true );

function nv_parse_vers ( $ver )
{
    return $ver[1] . "-" . nv_date( "d/m/Y", $ver[2] );
}

function nv_fix_module_weight ( )
{
    global $db;
    $query = "SELECT `title` FROM `" . NV_MODULES_TABLE . "` ORDER BY `weight` ASC";
    $result = $db->sql_query( $query );
    $weight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $weight ++;
        $sql = "UPDATE `" . NV_MODULES_TABLE . "` SET `weight`=" . $weight . " WHERE `title`=" . $db->dbescape( $row['title'] );
        $db->sql_query( $sql );
    }
    nv_del_moduleCache( 'modules' );
}

function nv_fix_subweight ( $mod )
{
    global $db;
    
    $sql = "SELECT `func_id` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `in_module`=" . $db->dbescape( $mod ) . " AND `show_func`='1' ORDER BY `subweight` ASC";
    $result = $db->sql_query( $sql );
    $subweight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $subweight ++;
        $sql = "UPDATE `" . NV_MODFUNCS_TABLE . "` SET `subweight`=" . $subweight . " WHERE `func_id`=" . $row['func_id'];
        $db->sql_query( $sql );
        nv_del_moduleCache( 'modules' );
    }
}

function nv_setup_block_module ( $mod, $func_id = 0 )
{
    global $db, $db_config, $global_config;
    if ( empty( $func_id ) )
    {
        //xoa du lieu tai bang blocks
        $db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE `bid` in (SELECT `bid` FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `module`=" . $db->dbescape( $mod ) . ")" );
        $db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `module`=" . $db->dbescape( $mod ) );
        $db->sql_query( "DELETE FROM `" . NV_BLOCKS_TABLE . "_weight` WHERE `func_id` in (SELECT `func_id` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `in_module`=" . $db->dbescape( $mod ) . ")" );
    }
    
    $array_funcid = array();
    $func_result = $db->sql_query( "SELECT `func_id` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `show_func` = '1' AND `in_module`=" . $db->dbescape( $mod ) . " ORDER BY `subweight` ASC" );
    while ( list( $func_id_i ) = $db->sql_fetchrow( $func_result ) )
    {
        if ( $func_id == 0 or $func_id == $func_id_i )
        {
            $array_funcid[] = $func_id_i;
        }
    }
    
    $weight = 0;
    $old_theme = $old_position = "";
    $sql = "SELECT `bid`,`theme`, `position` FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `all_func`='1' ORDER BY `theme` ASC, `position` ASC, `weight` ASC";
    $result = $db->sql_query( $sql );
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        if ( $old_theme == $row['theme'] and $old_position == $row['position'] )
        {
            $weight ++;
        }
        else
        {
            $weight = 1;
            $old_theme = $row['theme'];
            $old_position = $row['position'];
        }
        foreach ( $array_funcid as $func_id )
        {
            $db->sql_query( "INSERT INTO `" . NV_BLOCKS_TABLE . "_weight` (`bid`, `func_id`, `weight`) VALUES ('" . $row['bid'] . "', '" . $func_id . "', '" . $weight . "')" );
        }
    }
}

function nv_setup_data_module ( $lang, $module_name )
{
    global $db, $db_config, $global_config;
    $return = 'NO_' . $module_name;
    $sql = "SELECT `module_file`, `module_data` FROM `" . $db_config['prefix'] . "_" . $lang . "_modules` WHERE `title`=" . $db->dbescape( $module_name );
    $result = $db->sql_query( $sql );
    $numrows = $db->sql_numrows( $result );
    if ( $numrows == 1 )
    {
        list( $module_file, $module_data ) = $db->sql_fetchrow( $result );
        $module_version = array();
        $version_file = NV_ROOTDIR . "/modules/" . $module_file . "/version.php";
        if ( file_exists( $version_file ) )
        {
            include ( $version_file );
        }
        $arr_modfuncs = ( isset( $module_version['modfuncs'] ) and ! empty( $module_version['modfuncs'] ) ) ? array_map( "trim", explode( ",", $module_version['modfuncs'] ) ) : array();
        //xoa du lieu tai bang _config
        $sql = "DELETE FROM `" . NV_CONFIG_GLOBALTABLE . "` WHERE `lang`=" . $db->dbescape( $lang ) . " AND `module`=" . $db->dbescape( $module_name );
        $db->sql_query( $sql );
        nv_save_file_config_global();
        if ( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/action.php' ) )
        {
            $sql_recreate_module = array();
            include ( NV_ROOTDIR . '/modules/' . $module_file . '/action.php' );
            if ( ! empty( $sql_create_module ) )
            {
                foreach ( $sql_create_module as $sql )
                {
                    if ( ! $db->sql_query( $sql ) )
                    {
                        return $return;
                    }
                }
            }
        }
        
        $arr_func_id = array();
        $arr_show_func = array();
        $new_funcs = nv_scandir( NV_ROOTDIR . '/modules/' . $module_file . '/funcs', $global_config['check_op_file'] );
        if ( ! empty( $new_funcs ) )
        {
            // get default layout
            $layout_array = nv_scandir( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/layout', $global_config['check_op_layout'] );
            if ( ! empty( $layout_array ) )
            {
                $layout_array = preg_replace( $global_config['check_op_layout'], "\\1", $layout_array );
            }
            $array_layout_func_default = array();
            $xml = simplexml_load_file( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/config.ini' );
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
            // end get default layout
            

            $arr_func_id_old = array();
            $sql = "SELECT `func_id`, `func_name` FROM `" . $db_config['prefix'] . "_" . $lang . "_modfuncs` WHERE `in_module`=" . $db->dbescape( $module_name );
            $result = $db->sql_query( $sql );
            while ( $row = $db->sql_fetchrow( $result ) )
            {
                $arr_func_id_old[$row['func_name']] = $row['func_id'];
            }
            
            $new_funcs = preg_replace( $global_config['check_op_file'], "\\1", $new_funcs );
            $new_funcs = array_flip( $new_funcs );
            foreach ( array_keys( $new_funcs ) as $func )
            {
                $show_func = 0;
                $weight = 0;
                $layout = ( isset( $array_layout_func_default[$module_name][$func] ) ) ? $array_layout_func_default[$module_name][$func] : $layoutdefault;
                if ( isset( $arr_func_id_old[$func] ) and isset( $arr_func_id_old[$func] ) > 0 )
                {
                    $arr_func_id[$func] = $arr_func_id_old[$func];
                    $db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $lang . "_modfuncs` SET `layout`=" . $db->dbescape( $layout ) . ", `show_func`= " . $show_func . ", `subweight`='0' WHERE `func_id`=" . $arr_func_id[$func] . "" );
                }
                else
                {
                    $sql = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_modfuncs` (`func_id`, `func_name`, `func_custom_name`, `in_module`, `show_func`, `in_submenu`, `subweight`, `layout`, `setting`) VALUES (NULL, " . $db->dbescape( $func ) . ", " . $db->dbescape( ucfirst( $func ) ) . ", " . $db->dbescape( $module_name ) . ", " . $show_func . ", 0, " . $weight . ", " . $db->dbescape( $layout ) . ", '')";
                    $arr_func_id[$func] = $db->sql_query_insert_id( $sql );
                }
            }
            $subweight = 0;
            foreach ( $arr_modfuncs as $func )
            {
                if ( isset( $arr_func_id[$func] ) )
                {
                    $func_id = $arr_func_id[$func];
                    $arr_show_func[] = $func_id;
                    $show_func = 1;
                    $subweight ++;
                    $sql = "UPDATE `" . $db_config['prefix'] . "_" . $lang . "_modfuncs` SET `subweight`=" . $subweight . ", show_func=" . $show_func . " WHERE `func_id`=" . $db->dbescape( $func_id );
                    $db->sql_query( $sql );
                }
            }
        }
        else
        {
            //xoa du lieu tai bang _modfuncs
            $sql = "DELETE FROM `" . $db_config['prefix'] . "_" . $lang . "_modfuncs` WHERE `in_module`=" . $db->dbescape( $module_name );
            $db->sql_query( $sql );
        }
        if ( isset( $module_version['uploads_dir'] ) and ! empty( $module_version['uploads_dir'] ) )
        {
            foreach ( $module_version['uploads_dir'] as $path )
            {
                $cp = '';
                $arr_p = explode( "/", $path );
                foreach ( $arr_p as $p )
                {
                    if ( trim( $p ) != "" )
                    {
                        if ( ! is_dir( NV_UPLOADS_REAL_DIR . '/' . $cp . $p ) )
                        {
                            nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $cp, $p );
                        }
                        $cp .= $p . '/';
                    }
                }
            }
        }
        $return = 'OK_' . $module_name;
        nv_save_file_config_global();
    }
    return $return;
}

function main_theme ( $contents )
{
    $return = "";
    $return .= "<div id=\"" . $contents['div_id'] . "\"></div>\n";
    $return .= "<script type=\"text/javascript\">\n";
    $return .= $contents['ajax'] . "\n";
    $return .= "</script>\n";
    
    return $return;
}

function list_theme ( $contents, $act_modules, $deact_modules, $bad_modules, $weight_list )
{
    $return = "";
    if ( ! empty( $act_modules ) )
    {
        $return .= "<table summary=\"" . $contents['caption'][0] . "\" class=\"tab1\">\n";
        $return .= "<caption>" . $contents['caption'][0] . "</caption>\n";
        $return .= "<col style=\"width:60px;white-space:nowrap\" />\n";
        $return .= "<col style=\"width:110px;white-space:nowrap\" />\n";
        $return .= "<col span=\"5\" valign=\"top;white-space:nowrap\" />\n";
        $return .= "<col style=\"white-space:nowrap\" />\n";
        $return .= "<thead>\n";
        $return .= "<tr>\n";
        foreach ( $contents['thead'] as $thead )
        {
            $return .= "<td>" . $thead . "</td>\n";
        }
        $return .= "</tr>\n";
        $return .= "</thead>\n";
        $a = 0;
        foreach ( $act_modules as $mod => $values )
        {
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $return .= "<tbody" . $class . ">\n";
            $return .= "<tr>\n";
            $return .= "<td><select name=\"change_weight_" . $mod . "\" id=\"change_weight_" . $mod . "\" onchange=\"" . $values['weight'][1] . "\">\n";
            foreach ( $weight_list as $new_weight )
            {
                $return .= "<option value=\"" . $new_weight . "\"" . ( $new_weight == $values['weight'][0] ? " selected=\"selected\"" : "" ) . ">" . $new_weight . "</option>\n";
            }
            $return .= "</select></td>\n";
            $return .= "<td><span class=\"search_icon\"><a href=\"" . $values['title'][0] . "\">" . $values['title'][1] . "</a></span></td>\n";
            $return .= "<td>" . $values['custom_title'] . "</td>\n";
            $return .= "<td>" . $values['version'] . "</td>\n";
            $return .= "<td><input name=\"change_inmenu_" . $mod . "\" id=\"change_inmenu_" . $mod . "\" type=\"checkbox\" value=\"1\" onclick=\"" . $values['in_menu'][1] . "\"" . ( $values['in_menu'][0] ? " checked=\"checked\"" : "" ) . " /></td>\n";
            $return .= "<td><input name=\"change_submenu_" . $mod . "\" id=\"change_submenu_" . $mod . "\" type=\"checkbox\" value=\"1\" onclick=\"" . $values['submenu'][1] . "\"" . ( $values['submenu'][0] ? " checked=\"checked\"" : "" ) . " /></td>\n";
            
            $return .= "<td><input name=\"change_act_" . $mod . "\" id=\"change_act_" . $mod . "\" type=\"checkbox\" value=\"1\" onclick=\"" . $values['act'][1] . "\" checked=\"checked\"" . ( ( isset( $values['act'][2] ) and $values['act'][2] == 1 ) ? " disabled=\"disabled\"" : "" ) . " /></td>\n";
            $return .= "<td><span class=\"edit_icon\"><a href=\"" . $values['edit'][0] . "\">" . $values['edit'][1] . "</a></span>\n";
            $return .= "&nbsp;-&nbsp;<span class=\"default_icon\"><a href=\"javascript:void(0);\" onclick=\"" . $values['recreate'][0] . "\">" . $values['recreate'][1] . "</a></span>\n";
            if ( ! empty( $values['del'] ) ) $return .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"" . $values['del'][0] . "\">" . $values['del'][1] . "</a></span>\n";
            $return .= "</td>\n";
            $return .= "</tr>\n";
            $return .= "</tbody>\n";
            $a ++;
        }
        $return .= "</table>\n";
    }
    
    if ( ! empty( $deact_modules ) )
    {
        $return .= "<table summary=\"" . $contents['caption'][1] . "\" class=\"tab1\">\n";
        $return .= "<caption>" . $contents['caption'][1] . "</caption>\n";
        $return .= "<col style=\"width:60px;white-space:nowrap\" />\n";
        $return .= "<col style=\"width:110px;white-space:nowrap\" />\n";
        $return .= "<col span=\"5\" valign=\"top;white-space:nowrap\" />\n";
        $return .= "<col style=\"white-space:nowrap\" />\n";
        $return .= "<thead>\n";
        $return .= "<tr>\n";
        foreach ( $contents['thead'] as $thead )
        {
            $return .= "<td>" . $thead . "</td>\n";
        }
        $return .= "</tr>\n";
        $return .= "</thead>\n";
        $a = 0;
        foreach ( $deact_modules as $mod => $values )
        {
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $return .= "<tbody" . $class . ">\n";
            $return .= "<tr>\n";
            $return .= "<td><select name=\"change_weight_" . $mod . "\" id=\"change_weight_" . $mod . "\" onchange=\"" . $values['weight'][1] . "\">\n";
            foreach ( $weight_list as $new_weight )
            {
                $return .= "<option value=\"" . $new_weight . "\"" . ( $new_weight == $values['weight'][0] ? " selected=\"selected\"" : "" ) . ">" . $new_weight . "</option>\n";
            }
            $return .= "</select></td>\n";
            $return .= "<td><span class=\"search_icon\"><a href=\"" . $values['title'][0] . "\">" . $values['title'][1] . "</a></span></td>\n";
            $return .= "<td>" . $values['custom_title'] . "</td>\n";
            $return .= "<td>" . $values['version'] . "</td>\n";
            $return .= "<td><input name=\"change_inmenu_" . $mod . "\" id=\"change_inmenu_" . $mod . "\" type=\"checkbox\" value=\"1\" onclick=\"" . $values['in_menu'][1] . "\"" . ( $values['in_menu'][0] ? " checked=\"checked\"" : "" ) . " /></td>\n";
            $return .= "<td><input name=\"change_submenu_" . $mod . "\" id=\"change_submenu_" . $mod . "\" type=\"checkbox\" value=\"1\" onclick=\"" . $values['submenu'][1] . "\"" . ( $values['submenu'][0] ? " checked=\"checked\"" : "" ) . " /></td>\n";
            
            $return .= "<td><input name=\"change_act_" . $mod . "\" id=\"change_act_" . $mod . "\" type=\"checkbox\" value=\"1\" onclick=\"" . $values['act'][1] . "\" /></td>\n";
            $return .= "<td><span class=\"edit_icon\"><a href=\"" . $values['edit'][0] . "\">" . $values['edit'][1] . "</a></span>\n";
            $return .= "&nbsp;-&nbsp;<span class=\"default_icon\"><a href=\"javascript:void(0);\" onclick=\"" . $values['recreate'][0] . "\">" . $values['recreate'][1] . "</a></span>\n";
            if ( ! empty( $values['del'] ) ) $return .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"" . $values['del'][0] . "\">" . $values['del'][1] . "</a></span>\n";
            $return .= "</td>\n";
            $return .= "</tr>\n";
            $return .= "</tbody>\n";
            $a ++;
        }
        $return .= "</table>\n";
    }
    
    if ( ! empty( $bad_modules ) )
    {
        $return .= "<table summary=\"" . $contents['caption'][2] . "\" class=\"tab1\">\n";
        $return .= "<caption>" . $contents['caption'][2] . "</caption>\n";
        $return .= "<col style=\"width:60px;white-space:nowrap\" />\n";
        $return .= "<col style=\"width:110px;white-space:nowrap\" />\n";
        $return .= "<col span=\"5\" valign=\"top;white-space:nowrap\" />\n";
        $return .= "<col style=\"white-space:nowrap\" />\n";
        $return .= "<thead>\n";
        $return .= "<tr>\n";
        foreach ( $contents['thead'] as $thead )
        {
            $return .= "<td>" . $thead . "</td>\n";
        }
        $return .= "</tr>\n";
        $return .= "</thead>\n";
        $a = 0;
        foreach ( $bad_modules as $mod => $values )
        {
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $return .= "<tbody" . $class . ">\n";
            $return .= "<tr>\n";
            $return .= "<td><select name=\"change_weight_" . $mod . "\" id=\"change_weight_" . $mod . "\" onchange=\"" . $values['weight'][1] . "\">\n";
            foreach ( $weight_list as $new_weight )
            {
                $return .= "<option value=\"" . $new_weight . "\"" . ( $new_weight == $values['weight'][0] ? " selected=\"selected\"" : "" ) . ">" . $new_weight . "</option>\n";
            }
            $return .= "</select></td>\n";
            $return .= "<td><span class=\"search_icon\"><a href=\"" . $values['title'][0] . "\">" . $values['title'][1] . "</a></span></td>\n";
            $return .= "<td>" . $values['custom_title'] . "</td>\n";
            $return .= "<td>" . $values['version'] . "</td>\n";
            $return .= "<td><input name=\"change_inmenu_" . $mod . "\" id=\"change_inmenu_" . $mod . "\" type=\"checkbox\" value=\"1\" onclick=\"" . $values['in_menu'][1] . "\"" . ( $values['in_menu'][0] ? " checked=\"checked\"" : "" ) . " /></td>\n";
            $return .= "<td><input name=\"change_submenu_" . $mod . "\" id=\"change_submenu_" . $mod . "\" type=\"checkbox\" value=\"1\" onclick=\"" . $values['submenu'][1] . "\"" . ( $values['submenu'][0] ? " checked=\"checked\"" : "" ) . " /></td>\n";
            
            $return .= "<td><input name=\"change_act_" . $mod . "\" id=\"change_act_" . $mod . "\" type=\"checkbox\" value=\"1\" onclick=\"" . $values['act'][1] . "\" /></td>\n";
            $return .= "<td><span class=\"edit_icon\"><a href=\"" . $values['edit'][0] . "\">" . $values['edit'][1] . "</a></span>\n";
            $return .= "&nbsp;-&nbsp;<span class=\"default_icon\"><a href=\"javascript:void(0);\" onclick=\"" . $values['recreate'][0] . "\">" . $values['recreate'][1] . "</a></span>\n";
            if ( ! empty( $values['del'] ) ) $return .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"" . $values['del'][0] . "\">" . $values['del'][1] . "</a></span>\n";
            $return .= "</td>\n";
            $return .= "</tr>\n";
            $return .= "</tbody>\n";
            $a ++;
        }
        $return .= "</table>\n";
    }
    
    return $return;
}

function edit_theme ( $contents )
{
    $return = "<br />";
    $return .= "<form method=\"post\" action=\"" . $contents['action'] . "\">\n";
    $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
    $return .= "<col valign=\"top\" width=\"150px\" />\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['custom_title'][0] . ":</td>\n";
    $return .= "<td><input name=\"custom_title\" id=\"custom_title\" type=\"text\" value=\"" . $contents['custom_title'][1] . "\" style=\"width:300px\" maxlength=\"" . $contents['custom_title'][2] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</table>\n";
    
    $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
    $return .= "<col valign=\"top\" width=\"150px\" />\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['theme'][0] . ":</td>\n";
    $return .= "<td><select name=\"theme\" id=\"theme\">\n";
    $return .= "<option value=\"\">" . $contents['theme'][1] . "</option>\n";
    foreach ( $contents['theme'][2] as $tm )
    {
        $return .= "<option value=\"" . $tm . "\"" . ( $tm == $contents['theme'][3] ? " selected=\"selected\"" : "" ) . ">" . $tm . "</option>\n";
    }
    $return .= "</select></td>\n";
    $return .= "<td></td>\n";
    $return .= "</tr>\n";
    $return .= "</table>\n";
    
    $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
    $return .= "<col valign=\"top\" width=\"150px\" />\n";
    $return .= "<col valign=\"top\" width=\"310px\" />\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['keywords'][0] . ":</td>\n";
    $return .= "<td><input name=\"keywords\" id=\"keywords\" type=\"text\" value=\"" . $contents['keywords'][1] . "\" style=\"width:300px\" maxlength=\"" . $contents['keywords'][2] . "\" /></td>\n";
    $return .= "<td>" . $contents['keywords'][3] . "</td>\n";
    $return .= "</tr>\n";
    $return .= "</table>\n";
    
    if ( isset( $contents['who_view'] ) )
    {
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"150px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $contents['who_view'][0] . ":</td>\n";
        $return .= "<td><select name=\"who_view\" id=\"who_view\" onchange=\"nv_sh('who_view','groups_list')\">\n";
        foreach ( $contents['who_view'][1] as $k => $w )
        {
            $return .= "<option value=\"" . $k . "\"" . ( $k == $contents['who_view'][2] ? " selected=\"selected\"" : "" ) . ">" . $w . "</option>\n";
        }
        $return .= "</select></td>\n";
        $return .= "<td></td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "<div id=\"groups_list\" style=\"" . ( $contents['who_view'][2] == 3 ? "visibility:visible;display:block;" : "visibility:hidden;display:none;" ) . "\">\n";
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"150px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $contents['groups_view'][0] . ":</td>\n";
        $return .= "<td>\n";
        foreach ( $contents['groups_view'][1] as $group_id => $grtl )
        {
            $return .= "<p><input name=\"groups_view[]\" type=\"checkbox\" value=\"" . $group_id . "\"";
            if ( in_array( $group_id, $contents['groups_view'][2] ) ) $return .= " checked=\"checked\"";
            $return .= " />&nbsp;" . $grtl . "</p>\n";
        }
        $return .= "</td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        $return .= "</div>\n";
    }
    $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
    $return .= "<col valign=\"top\" width=\"150px\" />\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['act'][0] . ":</td>\n";
    $return .= "<td><input name=\"act\" id=\"act\" type=\"checkbox\" value=\"1\" " . ( ( $contents['act'][1] == 1 ) ? "checked" : "" ) . " /></td>\n";
    $return .= "</tr>\n";
    $return .= "</table>\n";
    
    if ( isset( $contents['rss'] ) )
    {
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"150px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $contents['rss'][0] . ":</td>\n";
        $return .= "<td><input name=\"rss\" id=\"rss\" type=\"checkbox\" value=\"1\" " . ( ( $contents['rss'][1] == 1 ) ? "checked" : "" ) . " /></td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
    }
    $return .= "<br />\n";
    $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
    $return .= "<col valign=\"top\" width=\"150px\" />\n";
    $return .= "<tr>\n";
    $return .= "<td><input name=\"save\" id=\"save\" type=\"hidden\" value=\"1\" /></td>\n";
    $return .= "<td><input name=\"go_add\" type=\"submit\" value=\"" . $contents['submit'] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</table>\n";
    
    $return .= "</form>\n";
    return $return;
}

function show_funcs_theme ( $contents )
{
    $return = "";
    $return .= "<div id=\"" . $contents['div_id'][0] . "\"></div>\n";
    $return .= "<div>\n";
    $return .= "<div style=\"DISPLAY:inline;FLOAT:left;PADDING:0;WIDTH:800px;\" id=\"" . $contents['div_id'][1] . "\"></div>\n";
    $return .= "<div class=\"clear\"></div>\n";
    $return .= "</div>\n";
    
    $return .= "<script type=\"text/javascript\">\n";
    if ( ! empty( $contents['ajax'][0] ) ) $return .= $contents['ajax'][0] . "\n";
    if ( ! empty( $contents['ajax'][1] ) ) $return .= $contents['ajax'][1] . "\n";
    $return .= "</script>\n";
    
    return $return;
}

function aj_show_funcs_theme ( $contents )
{
    $return = "";
    $return .= "<table summary=\"" . $contents['caption'] . "\" class=\"tab1\">\n";
    $return .= "<caption>" . $contents['caption'] . "</caption>\n";
    $return .= "<col style=\"width:60px;white-space:nowrap\" />\n";
    $return .= "<thead>\n";
    $return .= "<tr>\n";
    foreach ( $contents['thead'] as $key => $thead )
    {
        $return .= "<td>" . $thead . "</td>\n";
    }
    $return .= "</tr>\n";
    $return .= "</thead>\n";
    
    $a = 0;
    foreach ( $contents['rows'] as $id => $values )
    {
        $class = ( $a % 2 ) ? " class=\"second\"" : "";
        $return .= "<tbody" . $class . ">\n";
        $return .= "<tr>\n";
        $return .= "<td><select name=\"change_weight_" . $id . "\" id=\"change_weight_" . $id . "\" onchange=\"" . $values['weight'][1] . "\">\n";
        foreach ( $contents['weight_list'] as $new_weight )
        {
            $return .= "<option value=\"" . $new_weight . "\"" . ( $new_weight == $values['weight'][0] ? " selected=\"selected\"" : "" ) . ">" . $new_weight . "</option>\n";
        }
        $return .= "</select></td>\n";
        $return .= "<td><input name=\"chang_func_in_submenu_" . $id . "\" id=\"chang_func_in_submenu_" . $id . "\" type=\"checkbox\" value=\"1\" onclick=\"" . $values['in_submenu'][1] . "\"" . ( $values['in_submenu'][0] ? " checked=\"checked\"" : "" ) . "  " . $values['disabled'] . " /></td>\n";
        $return .= "<td>" . $values['name'][0] . "</td>\n";
        $return .= "<td><a href=\"#action\" onclick=\"" . $values['name'][2] . "\">" . $values['name'][1] . "</a></td>\n";
        $return .= "<td>" . $values['layout'][0] . "</td>\n";
        $return .= "</tr>\n";
        $return .= "</tbody>\n";
        $a ++;
    }
    
    $return .= "</table>\n";
    return $return;
}

function change_custom_name_theme ( $contents )
{
    $return = "<table summary=\"" . $contents['caption'] . "\" class=\"tab1\">\n";
    $return .= "<caption>" . $contents['caption'] . "</caption>\n";
    $return .= "<col style=\"width:150px;white-space:nowrap\" />\n";
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['func_custom_name'][0] . "</td>\n";
    $return .= "<td><input name=\"" . $contents['func_custom_name'][3] . "\" id=\"" . $contents['func_custom_name'][3] . "\" type=\"text\" value=\"" . $contents['func_custom_name'][1] . "\" style=\"width:300px\" maxlength=\"" . $contents['func_custom_name'][2] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td></td>\n";
    $return .= "<td>\n";
    $return .= "<div style=\"HEIGHT:24px;\">\n";
    $return .= "<a class=\"button2\" href=\"javascript:void(0);\" onclick=\"" . $contents['submit'][1] . "\"><span><span>" . $contents['submit'][0] . "</span></span></a>\n";
    $return .= "<a class=\"button2\" href=\"javascript:void(0);\" onclick=\"" . $contents['cancel'][1] . "\"><span><span>" . $contents['cancel'][0] . "</span></span></a>\n";
    $return .= "</div>\n";
    $return .= "</td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    $return .= "</table>\n";
    
    return $return;
}

function setup_modules ( $array_head, $array_modules, $array_virtual_head, $array_virtual_modules )
{
    $return = "";
    $return .= "<table class=\"tab1\">\n";
    $return .= "<caption>" . $array_head['caption'] . "</caption>\n";
    $return .= "<thead>\n";
    $return .= "<tr>\n";
    foreach ( $array_head['head'] as $thead )
    {
        $return .= "<td>" . $thead . "</td>\n";
    }
    $return .= "</tr>\n";
    $return .= "</thead>\n";
    $a = 0;
    foreach ( $array_modules as $mod => $values )
    {
        $class = ( $a % 2 ) ? " class=\"second\"" : "";
        $a ++;
        $return .= "<tbody" . $class . ">\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $a . "</td>\n";
        $return .= "<td>" . $values['title'] . "</td>\n";
        $return .= "<td>" . $values['version'] . "</td>\n";
        $return .= "<td>" . $values['addtime'] . "</td>\n";
        $return .= "<td>" . $values['author'] . "</td>\n";
        $return .= "<td>" . $values['setup'] . "</td>\n";
        $return .= "</tr>\n";
        $return .= "</tbody>\n";
    }
    $return .= "</table>\n";
    
    if ( ! empty( $array_virtual_modules ) )
    {
        $return .= "<table class=\"tab1\">\n";
        $return .= "<caption>" . $array_virtual_head['caption'] . "</caption>\n";
        $return .= "<thead>\n";
        $return .= "<tr>\n";
        foreach ( $array_virtual_head['head'] as $thead )
        {
            $return .= "<td>" . $thead . "</td>\n";
        }
        $return .= "</tr>\n";
        $return .= "</thead>\n";
        $a = 0;
        foreach ( $array_virtual_modules as $mod => $values )
        {
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $a ++;
            
            $return .= "<tbody" . $class . ">\n";
            $return .= "<tr>\n";
            $return .= "<td>" . $a . "</td>\n";
            $return .= "<td>" . $values['title'] . "</td>\n";
            $return .= "<td>" . $values['module_file'] . "</td>\n";
            $return .= "<td>" . $values['addtime'] . "</td>\n";
            $return .= "<td>" . $values['note'] . "</td>\n";
            $return .= "<td>" . $values['setup'] . "</td>\n";
            $return .= "</tr>\n";
            $return .= "</tbody>\n";
        }
        $return .= "</table>\n";
    }
    return $return;
}

?>