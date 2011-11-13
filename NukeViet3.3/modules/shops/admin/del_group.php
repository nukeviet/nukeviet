<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$groupid = $nv_Request->get_int( 'groupid', 'post', 0 );
$contents = "NO_" . $groupid;
list( $groupid, $parentid, $title ) = $db->sql_fetchrow( $db->sql_query( "SELECT `groupid`, `parentid`, `" . NV_LANG_DATA . "_title` FROM `" . $db_config['prefix'] . "_" . $module_data . "_group` WHERE `groupid`=" . intval( $groupid ) . "" ) );
if ( $groupid > 0 )
{
    $delallcheckss = $nv_Request->get_string( 'delallcheckss', 'post', "" );
    list( $check_parentid ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . $db_config['prefix'] . "_" . $module_data . "_group` WHERE `parentid` = '" . $groupid . "'" ) );
    if ( intval( $check_parentid ) > 0 )
    {
        $contents = "ERR_CAT_" . sprintf( $lang_module['delgroup_msg_group'], $check_parentid );
    }
    else
    {
        list( $check_rows ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `listgroupid` LIKE '%" . $groupid . "%'" ) );
        if ( intval( $check_rows ) > 0 )
        {
            if ( $delallcheckss == md5( $groupid . session_id() . $global_config['sitekey'] ) )
            {
                $delgroupandrows = $nv_Request->get_string( 'delgroupandrows', 'post', "" );
                $movegroup = $nv_Request->get_string( 'movegroup', 'post', "" );
                $groupidnews = $nv_Request->get_int( 'groupidnews', 'post', 0 );
                if ( empty( $delgroupandrows ) and empty( $movegroup ) )
                {
                    $sql = "SELECT groupid, " . NV_LANG_DATA . "_title, lev FROM `" . $db_config['prefix'] . "_" . $module_data . "_group` WHERE `groupid` !='" . $groupid . "' ORDER BY `order` ASC";
                    $result = $db->sql_query( $sql );
                    $array_group_list = array();
                    $array_group_list[0] = "&nbsp;";
                    while ( list( $groupid_i, $title_i, $lev_i ) = $db->sql_fetchrow( $result ) )
                    {
                        $xtitle_i = "";
                        if ( $lev_i > 0 )
                        {
                            $xtitle_i .= "&nbsp;&nbsp;&nbsp;|";
                            for ( $i = 1; $i <= $lev_i; ++$i )
                            {
                                $xtitle_i .= "---";
                            }
                            $xtitle_i .= ">&nbsp;";
                        }
                        $xtitle_i .= $title_i;
                        $array_group_list[$groupid_i] = $xtitle_i;
                    }
                    
                    $contents = "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
                    $contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
                    $contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
                    $contents .= "<input type=\"hidden\" name =\"groupid\" value=\"" . $groupid . "\" />";
                    $contents .= "<input type=\"hidden\" name =\"delallcheckss\" value=\"" . $delallcheckss . "\" />";
                    $contents .= "<center>";
                    $contents .= "<b>" . sprintf( $lang_module['delgroup_msg_rows_select'], $title, $check_rows ) . "</b>";
                    $contents .= "<br><br><input name=\"delgroupandrows\" type=\"submit\" value=\"" . $lang_module['delgroupandrows'] . "\" />";
                    $contents .= "<br><br><b>" . $lang_module['delgroup_msg_rows_move'] . "</b>: <select name=\"groupidnews\">\n";
                    while ( list( $groupid_i, $title_i ) = each( $array_group_list ) )
                    {
                        $contents .= "<option value=\"" . $groupid_i . "\">" . $title_i . "</option>\n";
                    }
                    $contents .= "</select><input name=\"movegroup\" type=\"submit\" value=\"" . $lang_module['action'] . "\"  onclick=\"return nv_check_movegroup(this.form, '" . $lang_module['delgroup_msg_rows_noselect'] . "')\">\n";
                    $contents .= "</center>";
                    $contents .= "</form>";
                }
                elseif ( ! empty( $delgroupandrows ) )
                {
                    $query = $db->sql_query( "SELECT id, listgroupid FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `listgroupid` LIKE '%" . $groupid . "%'" );
                    while ( $row = $db->sql_fetchrow( $query ) )
                    {
                        if ( ( string )$row['listgroupid'] == ( string )$groupid )
                        {
                            nv_del_content_module( $row['id'] );
                        }
                    }
                    $db->sql_query( "DELETE FROM `" . $db_config['prefix'] . "_" . $module_data . "_group` WHERE `groupid`=" . $groupid );
                    
                    nv_fix_group_order();
                    nv_del_moduleCache( $module_name );
                    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=group&parentid=" . $parentid . "" );
                    die();
                }
                elseif ( ! empty( $movegroup ) and $groupidnews > 0 and $groupidnews != $groupid )
                {
                    list( $groupidnews ) = $db->sql_fetchrow( $db->sql_query( "SELECT `groupid` FROM `" . $db_config['prefix'] . "_" . $module_data . "_group`  WHERE `groupid` =" . $groupidnews ) );
                    if ( $groupidnews > 0 )
                    {
                        $query = $db->sql_query( "SELECT id, listgroupid FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `listgroupid` LIKE '%" . $groupid . "%'" );
                        while ( $row = $db->sql_fetchrow( $query ) )
                        {
                            $db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_rows` SET `listgroupid` = '" . $groupidnews . "' WHERE `id` =" . $row['id'] );
                        }
                        $db->sql_query( "DELETE FROM `" . $db_config['prefix'] . "_" . $module_data . "_group` WHERE `groupid`=" . $groupid );
                        nv_fix_group_order();
                        nv_del_moduleCache( $module_name );
                        
                        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=group&parentid=" . $parentid . "" );
                        die();
                    }
                }
            }
            else
            {
                $contents = "ERR_ROWS_" . $groupid . "_" . md5( $groupid . session_id() . $global_config['sitekey'] ) . "_" . sprintf( $lang_module['delgroup_msg_rows'], $check_rows );
            }
        }
    }
    if ( $contents == "NO_" . $groupid )
    {
        $query = "DELETE FROM `" . $db_config['prefix'] . "_" . $module_data . "_group` WHERE groupid=" . $groupid . "";
        if ( $db->sql_query( $query ) )
        {
            $db->sql_freeresult();
            nv_fix_group_order();
            $contents = "OK_" . $parentid;
            nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['delgroupandrows'],$title, $admin_info['userid'] );
        }
        nv_del_moduleCache( $module_name );
    }
}
if ( defined( 'NV_IS_AJAX' ) )
{
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo $contents;
    include ( NV_ROOTDIR . "/includes/footer.php" );
}
else
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=group" );
    die();
}

?>