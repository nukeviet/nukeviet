<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 15:5
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_global['mod_groups'];
$group_id = $nv_Request->get_int( 'group_id', 'get', 0 );
if ( $group_id > 0 )
{
    $query = "SELECT * FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `group_id`=" . $group_id;
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if ( $numrows != 1 )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=groups" );
        die();
    }
}
else
{
    $query = "SELECT * FROM `" . NV_GROUPS_GLOBALTABLE . "`";
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if ( empty( $numrows ) )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=groups_add" );
        die();
    }
}

$contents = array();
$contents['caption'] = $lang_global['mod_groups'];
$contents['thead'] = array( 
    $lang_module['title'], $lang_module['add_time'], $lang_module['exp_time'], $lang_module['public'], $lang_module['users'], $lang_global['active'], $lang_global['actions'] 
);
while ( $row = $db->sql_fetchrow( $result ) )
{
    $contents['row'][$row['group_id']]['title'] = array( 
        $row['title'], NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=groups&amp;group_id=" . $row['group_id'] 
    );
    $contents['row'][$row['group_id']]['add_time'] = nv_date( "l, d/m/Y H:i", $row['add_time'] );
    $contents['row'][$row['group_id']]['exp_time'] = ! empty( $row['exp_time'] ) ? nv_date( "l, d/m/Y H:i", $row['exp_time'] ) : $lang_global['unlimited'];
    $contents['row'][$row['group_id']]['public'] = $row['public'] == 1 ? $lang_global['yes'] : $lang_global['no'];
    $contents['row'][$row['group_id']]['users'] = ! empty( $row['users'] ) ? count( explode( ",", $row['users'] ) ) : 0;
    $contents['row'][$row['group_id']]['act'] = $row['act'];
    $contents['row'][$row['group_id']]['actions'] = array( 
        $lang_global['edit'], NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=groups_edit&amp;group_id=" . $row['group_id'], $lang_global['delete'] 
    );
    
    if ( $group_id )
    {
        $page_title = $lang_global['mod_groups'] . " -> " . $lang_module['group_pgtitle'];
        $contents['caption'] = sprintf( $lang_module['group_info'], $row['title'] );
        $contents['containerid'] = "list_users";
        $contents['users_list'] = rawurlencode( NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=groups_users&group_id=" . $group_id );
        $contents['add_user']['caption'] = sprintf( $lang_module['add_users'], $row['title'] );
        $contents['add_user']['form_search']['label0'] = $lang_module['form_search_label0'];
        $contents['add_user']['form_search']['label1'] = $lang_module['form_search_label1'];
        $contents['add_user']['form_search']['select']['name'] = "search_option";
        $contents['add_user']['form_search']['select']['options'] = array( 
            $lang_module['form_search_select0'], $lang_module['form_search_select1'], $lang_module['form_search_select2'] 
        );
        $contents['add_user']['form_search']['input_txt']['name'] = "search_query";
        $contents['add_user']['form_search']['input_submit']['name'] = "search_click";
        $contents['add_user']['form_search']['input_submit']['value'] = $lang_global['submit'];
        $contents['add_user']['form_search']['input_submit']['onclick'] = "nv_group_search_users('" . rawurlencode( NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=groups_search_users&group_id=" . $group_id ) . "');";
        $contents['add_user']['containerid'] = "search_users_result";
        break;
    }
}

$contents = call_user_func( "main_theme", $contents );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>