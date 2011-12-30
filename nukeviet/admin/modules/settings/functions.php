<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 1:58
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );
global $sys_info;
$submenu['main'] = $lang_module['site_config'];
$submenu['system'] = $lang_module['global_config'];
$submenu['statistics'] = $lang_module['global_statistics'];
$submenu['cronjobs'] = $lang_global['mod_cronjobs'];
$submenu['smtp'] = $lang_module['smtp_config'];
if ( $sys_info['ftp_support'] )
{
    $submenu['ftp'] = $lang_module['ftp_config'];
}
$submenu['pagetitle'] = $lang_module['pagetitle'];
$submenu['metatags'] = $lang_module['metaTagsConfig'];
$submenu['robots'] = $lang_module['robots'];
$submenu['bots'] = $lang_module['bots_config'];
$submenu['banip'] = $lang_module['banip'];
$submenu['uploadconfig'] = $lang_module['uploadconfig'];

if ( $module_name == "settings" )
{
    $allow_func = array( 'main', 'system', 'statistics', 'bots', 'robots', 'smtp', 'ftp', 'pagetitle', 'metatags', 'banip', 'uploadconfig', 'cronjobs', 'cronjobs_add', 'cronjobs_edit', 'cronjobs_del', 'cronjobs_act' );
    
    $menu_top = array( "title" => $module_name, "module_file" => "", "custom_title" => $lang_global['mod_settings'] );
    unset( $page_title, $select_options );
    
    define( 'NV_IS_FILE_SETTINGS', true );

    function nv_admin_add_theme ( $contents )
    {
        $return = "";
        $class = $contents['is_error'] ? " class=\"error\"" : "";
        $return .= "<div class=\"quote\" style=\"width:780px;\">\n";
        $return .= "<blockquote" . $class . "><span>" . $contents['title'] . "</span></blockquote>\n";
        $return .= "</div>\n";
        $return .= "<div class=\"clear\"></div>\n";
        
        $return .= "<form method=\"post\" action=\"" . $contents['action'] . "\">\n";
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"150px\" />\n";
        $return .= "<col valign=\"top\" width=\"10px\" />\n";
        $return .= "<col valign=\"top\" />\n";
        $return .= "<col valign=\"top\" width=\"300px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $contents['cron_name'][0] . ":</td>\n";
        $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
        $return .= "<td><input name=\"cron_name\" id=\"cron_name\" type=\"text\" value=\"" . $contents['cron_name'][1] . "\" style=\"width:300px\" maxlength=\"" . $contents['cron_name'][2] . "\" /></td>\n";
        $return .= "<td></td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"150px\" />\n";
        $return .= "<col valign=\"top\" width=\"10px\" />\n";
        $return .= "<col valign=\"top\" />\n";
        $return .= "<col valign=\"top\" width=\"300px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $contents['run_file'][0] . ":</td>\n";
        $return .= "<td></td>\n";
        $return .= "<td><select name=\"run_file\">\n";
        $return .= "<option value=\"\">" . $contents['run_file'][1] . "</option>\n";
        foreach ( $contents['run_file'][2] as $run )
        {
            $return .= "<option value=\"" . $run . "\"" . ( $contents['run_file'][3] == $run ? " selected=\"selected\"" : "" ) . ">" . $run . "</option>\n";
        }
        $return .= "</select></td>\n";
        $return .= "<td><span class=\"row\">&lArr;</span> " . $contents['run_file'][4] . "</td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"150px\" />\n";
        $return .= "<col valign=\"top\" width=\"10px\" />\n";
        $return .= "<col valign=\"top\" />\n";
        $return .= "<col valign=\"top\" width=\"300px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $contents['run_func'][0] . ":</td>\n";
        $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
        $return .= "<td><input name=\"run_func_iavim\" id=\"run_func_iavim\" type=\"text\" value=\"" . $contents['run_func'][1] . "\" style=\"width:300px\" maxlength=\"" . $contents['run_func'][2] . "\" /></td>\n";
        $return .= "<td><span class=\"row\">&lArr;</span> " . $contents['run_func'][3] . "</td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"150px\" />\n";
        $return .= "<col valign=\"top\" width=\"10px\" />\n";
        $return .= "<col valign=\"top\" />\n";
        $return .= "<col valign=\"top\" width=\"300px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $contents['params'][0] . ":</td>\n";
        $return .= "<td></td>\n";
        $return .= "<td><input name=\"params_iavim\" id=\"params_iavim\" type=\"text\" value=\"" . $contents['params'][1] . "\" style=\"width:300px\" maxlength=\"" . $contents['params'][2] . "\" /></td>\n";
        $return .= "<td><span class=\"row\">&lArr;</span> " . $contents['params'][3] . "</td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"150px\" />\n";
        $return .= "<col valign=\"top\" width=\"10px\" />\n";
        $return .= "<col valign=\"top\" />\n";
        $return .= "<col valign=\"top\" width=\"300px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $contents['start_time'] . ":</td>\n";
        $return .= "<td></td>\n";
        $return .= "<td>" . $contents['year'][0] . ": <select name=\"year\">\n";
        for ( $i = 2010; $i < 2030; ++$i )
        {
            $return .= "<option value=\"" . $i . "\"" . ( $i == $contents['year'][1] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
        }
        $return .= "</select> " . $contents['month'][0] . ": <select name=\"month\">\n";
        for ( $i = 1; $i < 13; ++$i )
        {
            $return .= "<option value=\"" . $i . "\"" . ( $i == $contents['month'][1] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
        }
        $return .= "</select> " . $contents['day'][0] . ": <select name=\"day\">\n";
        for ( $i = 1; $i < 31; ++$i )
        {
            $return .= "<option value=\"" . $i . "\"" . ( $i == $contents['day'][1] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
        }
        $return .= "</select></td>\n";
        $return .= "<td></td>\n";
        $return .= "</tr>\n";
        $return .= "<tr>\n";
        $return .= "<td></td>\n";
        $return .= "<td></td>\n";
        $return .= "<td>" . $contents['hour'][0] . ": <select name=\"hour\">\n";
        for ( $i = 0; $i < 24; ++$i )
        {
            $return .= "<option value=\"" . $i . "\"" . ( $i == $contents['hour'][1] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
        }
        $return .= "</select> " . $contents['min'][0] . ": <select name=\"min\">\n";
        for ( $i = 0; $i < 60; ++$i )
        {
            $return .= "<option value=\"" . $i . "\"" . ( $i == $contents['min'][1] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
        }
        $return .= "</select></td>\n";
        $return .= "<td></td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"150px\" />\n";
        $return .= "<col valign=\"top\" width=\"10px\" />\n";
        $return .= "<col valign=\"top\" />\n";
        $return .= "<col valign=\"top\" width=\"300px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $contents['interval'][0] . ":</td>\n";
        $return .= "<td></td>\n";
        $return .= "<td><input name=\"interval_iavim\" id=\"interval_iavim\" type=\"text\" value=\"" . $contents['interval'][1] . "\" style=\"width:100px\" maxlength=\"" . $contents['interval'][2] . "\" /> " . $contents['interval'][3] . "</td>\n";
        $return .= "<td><span class=\"row\">&lArr;</span> " . $contents['interval'][4] . "</td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"150px\" />\n";
        $return .= "<col valign=\"top\" width=\"10px\" />\n";
        $return .= "<col valign=\"top\" />\n";
        $return .= "<col valign=\"top\" width=\"300px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $contents['del'][0] . ":</td>\n";
        $return .= "<td></td>\n";
        $return .= "<td><input name=\"del\" type=\"checkbox\" value=\"1\"" . ( ! empty( $contents['del'][1] ) ? "checked=\"checked\"" : "" ) . " /></td>\n";
        $return .= "<td></td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"160px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td><input name=\"save\" id=\"save\" type=\"hidden\" value=\"1\" /></td>\n";
        $return .= "<td><input name=\"go_add\" type=\"submit\" value=\"" . $contents['submit'] . "\" /></td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "</form>\n";
        return $return;
    }

    function nv_admin_edit_theme ( $contents )
    {
        $return = "";
        $class = $contents['is_error'] ? " class=\"error\"" : "";
        $return .= "<div class=\"quote\" style=\"width:780px;\">\n";
        $return .= "<blockquote" . $class . "><span>" . $contents['title'] . "</span></blockquote>\n";
        $return .= "</div>\n";
        $return .= "<div class=\"clear\"></div>\n";
        
        $return .= "<form method=\"post\" action=\"" . $contents['action'] . "\">\n";
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"150px\" />\n";
        $return .= "<col valign=\"top\" width=\"10px\" />\n";
        $return .= "<col valign=\"top\" />\n";
        $return .= "<col valign=\"top\" width=\"300px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $contents['cron_name'][0] . ":</td>\n";
        $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
        $return .= "<td><input name=\"cron_name\" id=\"cron_name\" type=\"text\" value=\"" . $contents['cron_name'][1] . "\" style=\"width:300px\" maxlength=\"" . $contents['cron_name'][2] . "\" /></td>\n";
        $return .= "<td></td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"150px\" />\n";
        $return .= "<col valign=\"top\" width=\"10px\" />\n";
        $return .= "<col valign=\"top\" />\n";
        $return .= "<col valign=\"top\" width=\"300px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $contents['run_file'][0] . ":</td>\n";
        $return .= "<td></td>\n";
        $return .= "<td><select name=\"run_file\">\n";
        $return .= "<option value=\"\">" . $contents['run_file'][1] . "</option>\n";
        foreach ( $contents['run_file'][2] as $run )
        {
            $return .= "<option value=\"" . $run . "\"" . ( $contents['run_file'][3] == $run ? " selected=\"selected\"" : "" ) . ">" . $run . "</option>\n";
        }
        $return .= "</select></td>\n";
        $return .= "<td><span class=\"row\">&lArr;</span> " . $contents['run_file'][4] . "</td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"150px\" />\n";
        $return .= "<col valign=\"top\" width=\"10px\" />\n";
        $return .= "<col valign=\"top\" />\n";
        $return .= "<col valign=\"top\" width=\"300px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $contents['run_func'][0] . ":</td>\n";
        $return .= "<td></td>\n";
        $return .= "<td><input name=\"run_func_iavim\" id=\"run_func_iavim\" type=\"text\" value=\"" . $contents['run_func'][1] . "\" style=\"width:300px\" maxlength=\"" . $contents['run_func'][2] . "\" /></td>\n";
        $return .= "<td><span class=\"row\">&lArr;</span> " . $contents['run_func'][3] . "</td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"150px\" />\n";
        $return .= "<col valign=\"top\" width=\"10px\" />\n";
        $return .= "<col valign=\"top\" />\n";
        $return .= "<col valign=\"top\" width=\"300px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $contents['params'][0] . ":</td>\n";
        $return .= "<td></td>\n";
        $return .= "<td><input name=\"params_iavim\" id=\"params_iavim\" type=\"text\" value=\"" . $contents['params'][1] . "\" style=\"width:300px\" maxlength=\"" . $contents['params'][2] . "\" /></td>\n";
        $return .= "<td><span class=\"row\">&lArr;</span> " . $contents['params'][3] . "</td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"150px\" />\n";
        $return .= "<col valign=\"top\" width=\"10px\" />\n";
        $return .= "<col valign=\"top\" />\n";
        $return .= "<col valign=\"top\" width=\"300px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $contents['start_time'] . ":</td>\n";
        $return .= "<td></td>\n";
        $return .= "<td>" . $contents['year'][0] . ": <select name=\"year\">\n";
        for ( $i = 2010; $i < 2030; ++$i )
        {
            $return .= "<option value=\"" . $i . "\"" . ( $i == $contents['year'][1] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
        }
        $return .= "</select> " . $contents['month'][0] . ": <select name=\"month\">\n";
        for ( $i = 1; $i < 13; ++$i )
        {
            $return .= "<option value=\"" . $i . "\"" . ( $i == $contents['month'][1] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
        }
        $return .= "</select> " . $contents['day'][0] . ": <select name=\"day\">\n";
        for ( $i = 1; $i < 31; ++$i )
        {
            $return .= "<option value=\"" . $i . "\"" . ( $i == $contents['day'][1] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
        }
        $return .= "</select></td>\n";
        $return .= "<td></td>\n";
        $return .= "</tr>\n";
        $return .= "<tr>\n";
        $return .= "<td></td>\n";
        $return .= "<td></td>\n";
        $return .= "<td>" . $contents['hour'][0] . ": <select name=\"hour\">\n";
        for ( $i = 0; $i < 24; ++$i )
        {
            $return .= "<option value=\"" . $i . "\"" . ( $i == $contents['hour'][1] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
        }
        $return .= "</select> " . $contents['min'][0] . ": <select name=\"min\">\n";
        for ( $i = 0; $i < 60; ++$i )
        {
            $return .= "<option value=\"" . $i . "\"" . ( $i == $contents['min'][1] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
        }
        $return .= "</select></td>\n";
        $return .= "<td></td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"150px\" />\n";
        $return .= "<col valign=\"top\" width=\"10px\" />\n";
        $return .= "<col valign=\"top\" />\n";
        $return .= "<col valign=\"top\" width=\"300px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $contents['interval'][0] . ":</td>\n";
        $return .= "<td></td>\n";
        $return .= "<td><input name=\"interval_iavim\" id=\"interval_iavim\" type=\"text\" value=\"" . $contents['interval'][1] . "\" style=\"width:100px\" maxlength=\"" . $contents['interval'][2] . "\" /> " . $contents['interval'][3] . "</td>\n";
        $return .= "<td><span class=\"row\">&lArr;</span> " . $contents['interval'][4] . "</td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"150px\" />\n";
        $return .= "<col valign=\"top\" width=\"10px\" />\n";
        $return .= "<col valign=\"top\" />\n";
        $return .= "<col valign=\"top\" width=\"300px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $contents['del'][0] . ":</td>\n";
        $return .= "<td></td>\n";
        $return .= "<td><input name=\"del\" type=\"checkbox\" value=\"1\"" . ( ! empty( $contents['del'][1] ) ? "checked=\"checked\"" : "" ) . " /></td>\n";
        $return .= "<td></td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "<table style=\"margin-bottom:8px;width:800px;\">\n";
        $return .= "<col valign=\"top\" width=\"160px\" />\n";
        $return .= "<tr>\n";
        $return .= "<td><input name=\"save\" id=\"save\" type=\"hidden\" value=\"1\" /></td>\n";
        $return .= "<td><input name=\"go_add\" type=\"submit\" value=\"" . $contents['submit'] . "\" /></td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "</form>\n";
        return $return;
    }

    function main_theme ( $contents )
    {
        $return = "";
        if ( ! empty( $contents ) )
        {
            foreach ( $contents as $id => $values )
            {
                $return .= "<table summary=\"" . $values['caption'] . "\" class=\"tab1\">\n";
                $return .= "<col span=\"2\" valign=\"top\" width=\"50%\" />\n";
                $return .= "<thead>\n";
                $return .= "<tr>\n";
                $return .= "<td colspan=\"2\">\n";
                $return .= "<div style=\"position:absolute;right:10px\">\n";
                if ( ! empty( $values['edit'][0] ) )
                {
                    $return .= "<a class=\"button1\" href=\"" . $values['edit'][2] . "\"><span><span>" . $values['edit'][1] . "</span></span></a>\n";
                }
                if ( ! empty( $values['disable'][0] ) )
                {
                    $return .= "<a class=\"button1\" href=\"" . $values['disable'][2] . "\"><span><span>" . $values['disable'][1] . "</span></span></a>\n";
                }
                if ( ! empty( $values['delete'][0] ) )
                {
                    $return .= "<a class=\"button1\" href=\"javascript:void(0);\" onclick=\"nv_is_del_cron(" . $id . ");\"><span><span>" . $values['delete'][1] . "</span></span></a>\n";
                }
                $return .= "</div>\n";
                $return .= $values['caption'] . "</td>\n";
                $return .= "</tr>\n";
                $return .= "</thead>\n";
                $a = 0;
                foreach ( $values['detail'] as $key => $value )
                {
                    $class = ( $a % 2 ) ? " class=\"second\"" : "";
                    $return .= "<tbody" . $class . ">\n";
                    $return .= "<tr>\n";
                    $return .= "<td>" . $key . "</td>\n";
                    $return .= "<td>" . $value . "</td>\n";
                    $return .= "</tr>\n";
                    $return .= "</tbody>\n";
                    ++$a;
                }
                $return .= "</table>\n";
            }
        }
        
        return $return;
    }
}

?>