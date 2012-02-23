<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1-27-2010 5:25
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$submenu['file'] = $lang_module['file_backup'];
$submenu['setting'] = $lang_global['mod_settings'];

if ( $module_name == "database" )
{
    $menu_top = array( 
        "title" => $module_name, "module_file" => "", "custom_title" => $lang_global['mod_database'] 
    );
    
    $allow_func = array( 
        'main', 'savefile', 'download', 'optimize', 'setting', 'file', 'getfile', 'delfile' 
    );
    unset( $page_title, $select_options );
    
    define( 'NV_IS_FILE_DATABASE', true );

    function nv_show_tables ( )
    {
        global $db, $db_config, $lang_module, $lang_global, $module_name;
        
        $tables = array();
        
        $db_size = 0;
        $db_totalfree = 0;
        $db_tables_count = 0;
        
        $tables = array();
        $result = $db->sql_query( "SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_%'" );
        while ( $item = $db->sql_fetch_assoc( $result ) )
        {
            $tables_size = floatval( $item['Data_length'] ) + floatval( $item['Index_length'] );
            $tables[$item['Name']]['table_size'] = nv_convertfromBytes( $tables_size );
            $tables[$item['Name']]['table_max_size'] = ! empty( $item['Max_data_length'] ) ? nv_convertfromBytes( floatval( $item['Max_data_length'] ) ) : 0;
            $tables[$item['Name']]['table_datafree'] = ! empty( $item['Data_free'] ) ? nv_convertfromBytes( floatval( $item['Data_free'] ) ) : 0;
            $tables[$item['Name']]['table_numrow'] = intval( $item['Rows'] );
            $tables[$item['Name']]['table_charset'] = ( ! empty( $item['Collation'] ) && preg_match( "/^([a-z0-9]+)_/i", $item['Collation'], $m ) ) ? $m[1] : "";
            $tables[$item['Name']]['table_type'] = ( isset( $item['Engine'] ) ) ? $item['Engine'] : $item['Type'];
            $tables[$item['Name']]['table_auto_increment'] = ( isset( $item['Auto_increment'] ) ) ? intval( $item['Auto_increment'] ) : "n/a";
            $tables[$item['Name']]['table_create_time'] = ! empty( $item['Create_time'] ) ? strftime( "%H:%M %d/%m/%Y", strtotime( $item['Create_time'] ) ) : "n/a";
            $tables[$item['Name']]['table_update_time'] = ! empty( $item['Update_time'] ) ? strftime( "%H:%M %d/%m/%Y", strtotime( $item['Update_time'] ) ) : "n/a";
            $db_size += $tables_size;
            $db_totalfree += floatval( $item['Data_free'] );
            ++$db_tables_count;
        }
        $db->sql_freeresult( $result );
        $db_size = ! empty( $db_size ) ? nv_convertfromBytes( $db_size ) : 0;
        $db_totalfree = ! empty( $db_totalfree ) ? nv_convertfromBytes( $db_totalfree ) : 0;
        
        $contents = array();
        $contents['action'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
        $contents['op'] = array( 
            'download' => $lang_module['download'], 'savefile' => $lang_module['savefile'], 'optimize' => $lang_module['optimize'] 
        );
        $contents['op_name'] = NV_OP_VARIABLE;
        $contents['type'] = array( 
            'all' => $lang_module['download_all'], 'str' => $lang_module['download_str'] 
        );
        $contents['type_name'] = "type";
        $contents['ext'] = array( 
            'sql' => $lang_module['ext_sql'], 'gz' => $lang_module['ext_gz'] 
        );
        $contents['ext_name'] = "ext";
        $contents['submit'] = $lang_module['submit'];
        $contents['captions']['tables_info'] = sprintf( $lang_module['tables_info'], $db->dbname );
        $contents['columns'] = array( 
            $lang_module['table_name'], $lang_module['table_size'], $lang_module['table_max_size'], $lang_module['table_datafree'], $lang_module['table_numrow'], $lang_module['table_charset'], $lang_module['table_type'], $lang_module['table_auto_increment'], $lang_module['table_create_time'], $lang_module['table_update_time'] 
        );
        
        foreach ( $tables as $key => $values )
        {
            $table_name = substr( $key, strlen( $db_config['prefix'] ) + 1 );
            $table_name = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;tab=" . $key . "\">" . $table_name . "</a>";
            eval( "\$contents['rows'][\$key] = array('" . $table_name . "','" . implode( "','", $values ) . "');" );
        }
        $contents['third'] = sprintf( $lang_module['third'], $db_tables_count, $db_size, $db_totalfree );
        
        $contents = call_user_func( "nv_show_tables_theme", $contents );
        include ( NV_ROOTDIR . "/includes/header.php" );
        echo $contents;
        include ( NV_ROOTDIR . "/includes/footer.php" );
    }

    function nv_highlight_string ( $tab, $type = "sql" )
    {
        global $db;
        $db->sql_query( "SET SQL_QUOTE_SHOW_CREATE = 1" );
        $result = $db->sql_query( "SHOW CREATE TABLE `" . $tab . "`" );
        $show = $db->sql_fetchrow( $result );
        $db->sql_freeresult( $result );
        $show = preg_replace( '/(KEY[^\(]+)(\([^\)]+\))[\s\r\n\t]+(USING BTREE)/i', '\\1\\3 \\2', $show[1] );
        $show = preg_replace( '/(default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP|DEFAULT CHARSET=\w+|COLLATE=\w+|character set \w+|collate \w+|AUTO_INCREMENT=\w+)/i', ' \\1', $show );
        if ( $type == "sql" )
        {
            return highlight_string( $show . ";", 1 );
        }
        else
        {
            return highlight_string( "<?php\n\n\$sql = \"" . $show . "\";\n\n?>", 1 );
        }
    }

    function nv_show_tab( )
    {
        global $db, $db_config, $module_name, $page_title, $lang_module;
        
        $tab = filter_text_input( 'tab', 'get' );
        
        $result = $db->sql_query( "SHOW TABLE STATUS WHERE `Name`=" . $db->dbescape( $tab ) );
        $item = $db->sql_fetch_assoc( $result );
        $db->sql_freeresult( $result );
        if ( empty( $item ) )
        {
            Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
            die();
        }
        
        if ( in_array( filter_text_input( 'show_highlight', 'post' ), array( 
            'php', 'sql' 
        ) ) )
        {
            $content = nv_highlight_string( $tab, filter_text_input( 'show_highlight', 'post' ) );
            include ( NV_ROOTDIR . "/includes/header.php" );
            echo $content;
            include ( NV_ROOTDIR . "/includes/footer.php" );
        }
        
        $tablename = substr( $item['Name'], strlen( $db_config['prefix'] ) + 1 );
        $contents = array();
        $contents['table']['caption'] = sprintf( $lang_module['table_caption'], $tablename );
        $contents['table']['info']['name'] = array( 
            $lang_module['table_name'], $tablename 
        );
        $contents['table']['info']['engine'] = array( 
            $lang_module['table_type'], ( ( isset( $item['Engine'] ) ) ? $item['Engine'] : $item['Type'] ) 
        );
        $contents['table']['info']['row_format'] = array( 
            $lang_module['row_format'], $item['Row_format'] 
        );
        $contents['table']['info']['data_length'] = array( 
            $lang_module['table_size'], nv_convertfromBytes( intval( $item['Data_length'] ) + intval( $item['Index_length'] ) ) 
        );
        $contents['table']['info']['max_data_length'] = array( 
            $lang_module['table_max_size'], ( ! empty( $item['Max_data_length'] ) ? nv_convertfromBytes( floatval( $item['Max_data_length'] ) ) : 'n/a' ) 
        );
        $contents['table']['info']['data_free'] = array( 
            $lang_module['table_datafree'], ( ! empty( $item['Data_free'] ) ? nv_convertfromBytes( intval( $item['Data_free'] ) ) : 0 ) 
        );
        $contents['table']['info']['rows'] = array( 
            $lang_module['table_numrow'], $item['Rows'] 
        );
        $contents['table']['info']['auto_increment'] = array( 
            $lang_module['table_auto_increment'], ( ( isset( $item['Auto_increment'] ) ) ? intval( $item['Auto_increment'] ) : "n/a" ) 
        );
        $contents['table']['info']['create_time'] = array( 
            $lang_module['table_create_time'], ( ! empty( $item['Create_time'] ) ? strftime( "%H:%M:%S %d/%m/%Y", strtotime( $item['Create_time'] ) ) : "n/a" ) 
        );
        $contents['table']['info']['update_time'] = array( 
            $lang_module['table_update_time'], ( ! empty( $item['Update_time'] ) ? strftime( "%H:%M:%S %d/%m/%Y", strtotime( $item['Update_time'] ) ) : "n/a" ) 
        );
        $contents['table']['info']['check_time'] = array( 
            $lang_module['table_check_time'], ( ! empty( $item['Check_time'] ) ? strftime( "%H:%M:%S %d/%m/%Y", strtotime( $item['Check_time'] ) ) : "n/a" ) 
        );
        $contents['table']['info']['collation'] = array( 
            $lang_module['table_charset'], ( ( ! empty( $item['Collation'] ) && preg_match( "/^([a-z0-9]+)_/i", $item['Collation'], $m ) ) ? $m[1] : "" ) 
        );
        
        $contents['table']['show'] = nv_highlight_string( $tab, "php" );
        $contents['table']['show_lang'] = array( 
            $lang_module['php_code'], $lang_module['sql_code'] 
        );
        
        $contents['table']['row']['caption'] = sprintf( $lang_module['table_row_caption'], $tablename );
        $contents['table']['row']['columns'] = array( 
            $lang_module['field_name'], $lang_module['field_type'], $lang_module['field_null'], $lang_module['field_key'], $lang_module['field_default'], $lang_module['field_extra'] 
        );
        
        $contents['table']['row']['detail'] = array();
        $result = $db->sql_query( "SHOW COLUMNS FROM `" . $tab . "`" );
        while ( $row = $db->sql_fetch_assoc( $result ) )
        {
            $row['Null'] = ( $row['Null'] == "NO" ) ? "NOT NULL" : "NULL";
            $row['Key'] = empty( $row['Key'] ) ? "" : ( $row['Key'] == 'PRI' ? "PRIMARY KEY" : ( $row['Key'] == 'UNI' ? "UNIQUE KEY" : "KEY" ) );
            $contents['table']['row']['detail'][] = $row;
        }
        $db->sql_freeresult( $result );
        
        $contents = call_user_func( "nv_show_tab_theme", $contents );
        
        $page_title = sprintf( $lang_module['nv_show_tab'], $tablename );
        
        include ( NV_ROOTDIR . "/includes/header.php" );
        echo nv_admin_theme( $contents );
        include ( NV_ROOTDIR . "/includes/footer.php" );
    }

    function main_theme ( $contents )
    {
        $return = "";
        $return .= "<table summary=\"\" class=\"tab1\">\n";
        $return .= "<caption>" . $contents['captions']['database_info'] . "</caption>\n";
        $return .= "<col span=\"2\" valign=\"top\" width=\"50%\" />\n";
        $a = 0;
        foreach ( $contents['database'] as $key => $value )
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
        
        $return .= "<div id=\"show_tables\">\n";
        $return .= "</div>\n";
        $return .= "<script type=\"text/javascript\">\n";
        $return .= "nv_show_dbtables();\n";
        $return .= "</script>\n";
        $return .= "<br /><br />\n";
        
        return $return;
    }

    function nv_show_tables_theme ( $contents )
    {
        $return = "";
        $return .= "<form name=\"myform\" method=\"post\" action=\"" . $contents['action'] . "\" onsubmit=\"nv_chsubmit(this,'tables[]');return false;\">\n";
        $return .= "<table summary=\"\" class=\"tab1\">\n";
        $return .= "<caption>" . $contents['captions']['tables_info'] . "</caption>\n";
        $return .= "<col valign=\"middle\" width=\"10px\" />\n";
        $return .= "<col span=\"10\" valign=\"top\" />\n";
        $return .= "<thead>\n";
        $return .= "<tr>\n";
        $return .= "<td><input name=\"check_all[]\" type=\"checkbox\" value=\"yes\" onclick=\"nv_checkAll(this.form, 'tables[]', 'check_all[]',this.checked);\" /></td>\n";
        foreach ( $contents['columns'] as $value )
        {
            $return .= "<td>" . $value . "</td>\n";
        }
        $return .= "</tr>\n";
        $return .= "</thead>\n";
        
        $return .= "<tfoot>\n";
        $return .= "<tr>\n";
        $return .= "<td colspan=\"11\">\n";
        $return .= "<select id=\"op_name\" name=\"" . $contents['op_name'] . "\" onchange=\"nv_checkForm();\">\n";
        foreach ( $contents['op'] as $key => $val )
        {
            $return .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
        }
        $return .= "</select>\n";
        $return .= "<select id=\"type_name\" name=\"" . $contents['type_name'] . "\">\n";
        foreach ( $contents['type'] as $key => $val )
        {
            $return .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
        }
        $return .= "</select>\n";
        $return .= "<select id=\"ext_name\" name=\"" . $contents['ext_name'] . "\">\n";
        foreach ( $contents['ext'] as $key => $val )
        {
            $return .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
        }
        $return .= "</select>\n";
        $return .= "<input name=\"Submit1\" id=\"subm_form\" type=\"submit\" value=\"" . $contents['submit'] . "\" />\n";
        $return .= "</td>\n";
        $return .= "</tr>\n";
        $return .= "</tfoot>\n";
        
        $a = 0;
        foreach ( $contents['rows'] as $key => $values )
        {
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $return .= "<tbody" . $class . ">\n";
            $return .= "<tr>\n";
            $tag = ( empty( $values[3] ) ) ? "td" : "th";
            $return .= "<" . $tag . "><input name=\"tables[]\" type=\"checkbox\" value=\"" . $key . "\" onclick=\"nv_UncheckAll(this.form, 'tables[]', 'check_all[]', this.checked);\" /></" . $tag . ">\n";
            foreach ( $values as $value )
            {
                $return .= "<" . $tag . ">" . $value . "</" . $tag . ">\n";
            }
            $return .= "</tr>\n";
            $return .= "</tbody>\n";
            ++$a;
        }
        $return .= "<tbody class=\"third\">\n";
        $return .= "<tr>\n";
        $return .= "<td><input name=\"check_all[]\" type=\"checkbox\" value=\"yes\" onclick=\"nv_checkAll(this.form, 'tables[]', 'check_all[]',this.checked);\" /></td>\n";
        $return .= "<td colspan=\"10\">\n";
        $return .= "<strong>" . $contents['third'] . "</strong>\n";
        $return .= "</td>\n";
        $return .= "</tr>\n";
        $return .= "</tbody>\n";
        $return .= "</table>\n";
        $return .= "</form>\n";
        $return .= "<br /><br />\n";
        return $return;
    }

    function nv_show_tab_theme ( $contents )
    {
        $return = "";
        $return .= "<div style=\"width:300px;position:relative;float:left\">\n";
        $return .= "<table summary=\"\" class=\"tab1\" style=\"width:330px\">\n";
        $return .= "<caption>" . $contents['table']['caption'] . "</caption>\n";
        $return .= "<col span=\"2\" valign=\"top\" width=\"50%\" />\n";
        $a = 0;
        foreach ( $contents['table']['info'] as $key => $value )
        {
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $return .= "<tbody" . $class . ">\n";
            $return .= "<tr>\n";
            $return .= "<td>" . $value[0] . "</td>\n";
            $return .= "<td>" . $value[1] . "</td>\n";
            $return .= "</tr>\n";
            $return .= "</tbody>\n";
            ++$a;
        }
        $return .= "</table>\n";
        $return .= "</div>\n";
        $return .= "<div style=\"margin-top:10px;position:relative;float:right;width:490px;\">\n";
        $return .= "<a class=\"button2\" href=\"javascript:void(0);\" onclick=\"nv_show_highlight('php');\"><span><span>" . $contents['table']['show_lang'][0] . "</span></span></a>\n";
        $return .= "<a class=\"button2\" href=\"javascript:void(0);\" onclick=\"nv_show_highlight('sql');\"><span><span>" . $contents['table']['show_lang'][1] . "</span></span></a>\n";
        $return .= "<div class=\"clear\"></div>\n";
        $return .= "<div id=\"my_highlight\" style=\"background-color:#F2F2F2;border: 1px solid #CCC;margin-top:10px;padding:10px;\">\n";
        $return .= $contents['table']['show'];
        $return .= "</div>\n";
        $return .= "</div>\n";
        $return .= "<div class=\"clear\"></div>\n";
        $return .= "<br />\n";
        
        $return .= "<table summary=\"\" class=\"tab1\">\n";
        $return .= "<caption>" . $contents['table']['row']['caption'] . "</caption>\n";
        $return .= "<col valign=\"top\" width=\"40%\" />\n";
        $return .= "<col span=\"5\" valign=\"top\" />\n";
        $return .= "<thead>\n";
        $return .= "<tr>\n";
        foreach ( $contents['table']['row']['columns'] as $value )
        {
            $return .= "<td>" . $value . "</td>\n";
        }
        $return .= "</tr>\n";
        $return .= "</thead>\n";
        $a = 0;
        foreach ( $contents['table']['row']['detail'] as $key => $values )
        {
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $return .= "<tbody" . $class . ">\n";
            $return .= "<tr>\n";
            foreach ( $values as $value )
            {
                $return .= "<td>" . $value . "</td>\n";
            }
            $return .= "</tr>\n";
            $return .= "</tbody>\n";
            ++$a;
        }
        $return .= "</table>\n";
        $return .= "<br /><br />\n";
        return $return;
    }
}

?>