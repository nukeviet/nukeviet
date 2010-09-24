<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:13
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * nv_db_mods()
 * 
 * @return
 */
function nv_site_mods ( )
{
    global $db, $admin_info;
    $site_mods = array();
    $sql = "SELECT * FROM `" . NV_MODULES_TABLE . "` WHERE `admin_file`=1 ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $allowed = false;
        if ( defined( 'NV_IS_SPADMIN' ) )
        {
            $allowed = true;
        }
        elseif ( defined( 'NV_IS_ADMIN' ) and ! empty( $row['admins'] ) and in_array( $admin_info['admin_id'], explode( ",", $row['admins'] ) ) )
        {
            $allowed = true;
        }
        if ( $allowed )
        {
            $site_mods[$row['title']] = array( 
                'module_file' => $row['module_file'], 'module_data' => $row['module_data'], 'custom_title' => $row['custom_title'], 'theme' => $row['theme'], 'keywords' => $row['keywords'], 'groups_view' => $row['groups_view'], 'in_menu' => intval( $row['in_menu'] ), 'submenu' => intval( $row['submenu'] ), 'act' => intval( $row['act'] ), 'admins' => $row['admins'], 'rss' => $row['rss'] 
            );
        }
    }
    $db->sql_freeresult( $result );
    return $site_mods;
}

/**
 * nv_groups_list()
 * 
 * @return
 */
function nv_groups_list ( )
{
    global $db;
    $query = "SELECT `group_id`, `title` FROM `" . NV_GROUPS_GLOBALTABLE . "`";
    $result = $db->sql_query( $query );
    $groups = array();
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $groups[$row['group_id']] = $row['title'];
    }
    return $groups;
}

function nv_set_layout_site ( )
{
    global $db, $global_config;
    $array_layout_func_data = array();
    $fnsql = "SELECT `func_id`, `layout`, `theme` FROM `" . NV_PREFIXLANG . "_modthemes`";
    $fnresult = $db->sql_query( $fnsql );
    while ( list( $func_id, $layout, $theme ) = $db->sql_fetchrow( $fnresult ) )
    {
        $array_layout_func_data[$theme][$func_id] = $layout;
    }
    
    $func_id_mods = array();
    
    $sql = "SELECT `func_id`, `in_module` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `show_func`='1' ORDER BY `in_module` ASC, `subweight` ASC";
    $result = $db->sql_query( $sql );
    while ( list( $func_id, $in_module ) = $db->sql_fetchrow( $result ) )
    {
        $func_id_mods[$in_module][] = $func_id;
    }
    
    $sql = "SELECT title, theme FROM `" . NV_MODULES_TABLE . "` ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $is_delCache = false;
    while ( list( $title, $theme ) = $db->sql_fetchrow( $result ) )
    {
        if ( isset( $func_id_mods[$title] ) )
        {
            if ( empty( $theme ) )
            {
                $theme = $global_config['site_theme'];
            }
            foreach ( $func_id_mods[$title] as $func_id )
            {
                $layout = ( isset( $array_layout_func_data[$theme][$func_id] ) ) ? $array_layout_func_data[$theme][$func_id] : $array_layout_func_data[$theme][0];
                $db->sql_query( "UPDATE `" . NV_MODFUNCS_TABLE . "` SET `layout`=" . $db->dbescape( $layout ) . " WHERE `func_id`=" . $func_id . "" );
                $is_delCache = true;
            }
        }
    }
    
    if ( $is_delCache )
    {
        nv_del_moduleCache( 'modules' );
    }
}

function nv_save_file_config_global ( )
{
    global $db;
    
    $content_config = "<?php\n\n";
    $content_config .= NV_FILEHEAD . "\n\n";
    $content_config .= "if ( ! defined( 'NV_MAINFILE' ) )\n";
    $content_config .= "{\n";
    $content_config .= "    die( 'Stop!!!' );\n";
    $content_config .= "}\n\n";
    
    $sql = "SELECT `config_name`, `config_value` FROM `" . NV_CONFIG_GLOBALTABLE . "` WHERE `lang`='sys' ORDER BY `config_name` ASC";
    $result = $db->sql_query( $sql );
    while ( list( $c_config_name, $c_config_value ) = $db->sql_fetchrow( $result ) )
    {
        if ( ! is_numeric( $c_config_value ) || ( strlen( $c_config_value ) > 1 and $c_config_value{0} == '0' ) )
        {
            $content_config .= "\$global_config['" . $c_config_name . "'] = \"" . nv_unhtmlspecialchars( $c_config_value ) . "\";\n";
        }
        else
        {
            $content_config .= "\$global_config['" . $c_config_name . "'] = " . intval( $c_config_value ) . ";\n";
        }
    }
    $content_config .= "\n";
    $content_config .= "?>";
    nv_delete_all_cache();
    return file_put_contents( NV_ROOTDIR . "/" . NV_DATADIR . "/config_global.php", $content_config, LOCK_EX );
}

function get_version ( $updatetime = 3600 )
{
    global $global_config;
    
    $my_file = NV_ROOTDIR . '/' . NV_CACHEDIR . '/nukeviet.version.xml';
    
    $xmlcontent = false;
    
    if ( file_exists( $my_file ) and filemtime( $my_file ) > NV_CURRENTTIME - $updatetime )
    {
        $xmlcontent = simplexml_load_file( $my_file );
    }
    else
    {
        include ( NV_ROOTDIR . "/includes/class/geturl.class.php" );
        $getContent = new UrlGetContents( $global_config );
        $content = $getContent->get( 'http://update.nukeviet.vn/nukeviet.version.xml' );
        
        if ( $content )
        {
            $xmlcontent = simplexml_load_string( $content );
            if ( $xmlcontent != false )
            {
                file_put_contents( $my_file, $content );
            }
        }
    }
    
    return $xmlcontent;
}

function nv_version_compare ( $version1, $version2 )
{
    $v1 = explode( '.', $version1 );
    $v2 = explode( '.', $version2 );
    
    if ( $v1[0] > $v2[0] )
    {
        return 1;
    }
    
    if ( $v1[0] < $v2[0] )
    {
        return - 1;
    }
    
    if ( $v1[1] > $v2[1] )
    {
        return 1;
    }
    
    if ( $v1[1] < $v2[1] )
    {
        return - 1;
    }
    
    if ( $v1[2] > $v2[2] )
    {
        return 1;
    }
    
    if ( $v1[2] < $v2[2] )
    {
        return - 1;
    }
    
    return 0;
}

?>