<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_DOWNLOAD', true );

/**
 * nv_set_allow()
 * 
 * @param mixed $who
 * @param mixed $groups
 * @return
 */
function nv_set_allow( $who, $groups )
{
    global $user_info;

    if ( ! $who or ( $who == 1 and defined( 'NV_IS_USER' ) ) or ( $who == 2 and defined( 'NV_IS_ADMIN' ) ) )
    {
        return true;
    } elseif ( $who == 3 and ! empty( $groups ) and defined( 'NV_IS_USER' ) and nv_is_in_groups( $user_info['in_groups'], $groups ) )
    {
        return true;
    }

    return false;
}

/**
 * nv_setcats()
 * 
 * @param mixed $id
 * @param mixed $list
 * @param mixed $name
 * @param mixed $is_parentlink
 * @return
 */
function nv_setcats( $id, $list, $name, $is_parentlink )
{
    global $module_name;

    if ( $is_parentlink )
    {
        $name = "<a href=\"" . NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;cat=" . $list[$id]['alias'] . "\">" . $list[$id]['title'] . "</a> &raquo; " . $name;
    }
    else
    {
        $name = $list[$id]['title'] . " &raquo; " . $name;
    }
    $parentid = $list[$id]['parentid'];
    if ( $parentid )
    {
        $name = nv_setcats( $parentid, $list, $name, $is_parentlink );
    }

    return $name;
}

/**
 * nv_list_cats()
 * 
 * @param bool $is_link
 * @param bool $is_parentlink
 * @return
 */
function nv_list_cats( $is_link = false, $is_parentlink = true )
{
    global $db, $module_data, $module_name, $module_info;

    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `status`=1 ORDER BY `parentid`,`weight` ASC";
    $result = $db->sql_query( $sql );

    $list = array();
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        if ( nv_set_allow( $row['who_view'], $row['groups_view'] ) )
        {
            $list[$row['id']] = array( //
                'id' => ( int )$row['id'], //
                'title' => $row['title'], //
                'alias' => $row['alias'], //
                'description' => $row['description'], //
                'who_download' => $row['who_download'], //
                'is_download_allow' => ( int )nv_set_allow( $row['who_download'], $row['groups_download'] ), //
                'parentid' => ( int )$row['parentid'], //
                'subcats' => array() //
                );
        }
    }

    if ( ! empty( $list ) )
    {
        $list2 = array();

        foreach ( $list as $row )
        {
            if ( ! $row['parentid'] or isset( $list[$row['parentid']] ) )
            {
                $list2[$row['id']] = $list[$row['id']];
                $list2[$row['id']]['name'] = $list[$row['id']]['title'];
                if ( $is_link )
                {
                    $list2[$row['id']]['name'] = "<a href=\"" . NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;cat=" . $list2[$row['id']]['alias'] . "\">" . $list2[$row['id']]['name'] . "</a>";
                }

                if ( $row['parentid'] )
                {
                    $list2[$row['parentid']]['subcats'][] = $row['id'];

                    $list2[$row['id']]['name'] = nv_setcats( $row['parentid'], $list, $list2[$row['id']]['name'], $is_parentlink );
                }

                if ( $is_parentlink )
                {
                    $list2[$row['id']]['name'] = "<a href=\"" . NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "\">" . $module_info['custom_title'] . "</a> &raquo; " . $list2[$row['id']]['name'];
                }
            }
        }
    }

    return $list2;
}

/**
 * initial_config_data()
 * 
 * @return
 */
function initial_config_data()
{
    global $db, $module_data;

    $sql = "SELECT `config_name`,`config_value` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config`";
    $result = $db->sql_query( $sql );

    $download_config = array();
    while ( $data = $db->sql_fetchrow( $result ) )
    {
        $download_config[$data['config_name']] = $data['config_value'];
    }

    $download_config['upload_filetype'] = ! empty( $download_config['upload_filetype'] ) ? explode( ",", $download_config['upload_filetype'] ) : array();
    if ( ! empty( $download_config['upload_filetype'] ) ) $download_config['upload_filetype'] = array_map( "trim", $download_config['upload_filetype'] );

    if ( empty( $download_config['upload_filetype'] ) )
    {
        $download_config['is_upload'] = 0;
    }

    if ( $download_config['is_addfile'] )
    {
        $download_config['is_addfile_allow'] = nv_set_allow( $download_config['who_addfile'], $download_config['groups_addfile'] );
    }
    else
    {
        $download_config['is_addfile_allow'] = false;
    }

    if ( $download_config['is_addfile_allow'] and $download_config['is_upload'] )
    {
        $download_config['is_upload_allow'] = nv_set_allow( $download_config['who_upload'], $download_config['groups_upload'] );
    }
    else
    {
        $download_config['is_upload_allow'] = false;
    }

    $download_config['is_autocomment_allow'] = nv_set_allow( $download_config['who_autocomment'], $download_config['groups_autocomment'] );

    return $download_config;
}

?>