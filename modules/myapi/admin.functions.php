<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$allow_func = [
    'main'
];

if (defined('NV_IS_GODADMIN')) {
    $allow_func[] = 'roles';
    $allow_func[] = 'credential';
    $allow_func[] = 'logs';
    $allow_func[] = 'config';
}

define('NV_IS_FILE_ADMIN', true);
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

/**
 * getRoleList()
 * Lấy danh sách các role
 *
 * @param mixed $type
 * @param mixed $object
 * @param mixed $page
 * @param mixed $per_page
 * @return array
 */
function getRoleList($type, $object, $page, $per_page)
{
    global $db, $db_config;

    $where = [];
    if (!empty($type)) {
        $where[] = 'role_type = ' . $db->quote($type);
    }
    if (!empty($object)) {
        $where[] = 'role_object = ' . $db->quote($object);
    }
    $where = !empty($where) ? implode(' AND ', $where) : '';

    $db->sqlreset()
        ->select('COUNT(*)')
        ->from($db_config['prefix'] . '_api_role');
    if (!empty($where)) {
        $db->where($where);
    }

    $all_pages = $db->query($db->sql())
        ->fetchColumn();

    $db->select('*')
        ->order('role_id DESC');
    if (!empty($page)) {
        $db->limit($per_page)
            ->offset(($page - 1) * $per_page);
    }
    $result = $db->query($db->sql());

    $array = [];
    while ($row = $result->fetch()) {
        $array[$row['role_id']] = parseRole($row);
    }

    return [$all_pages, $array];
}

/**
 * checkRoleExist()
 *
 * @param mixed $id
 * @return bool
 */
function checkRoleExist($id)
{
    global $db, $db_config;

    $exists = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_role WHERE role_id =' . $id)->fetchColumn();

    return !empty($exists);
}

/**
 * getCredentialList()
 *
 * @param mixed $role_id
 * @param mixed $page
 * @param mixed $per_page
 * @param mixed $for_admin
 * @return array
 */
function getCredentialList($role_id, $for_admin, $page, $per_page)
{
    global $db, $db_config;

    $join = 'INNER JOIN ' . NV_USERS_GLOBALTABLE . ' tb2 ON (tb1.userid=tb2.userid)';
    $select = 'tb1.*, tb2.username, tb2.first_name, tb2.last_name';
    if ($for_admin) {
        $join .= ' INNER JOIN ' . NV_AUTHORS_GLOBALTABLE . ' tb3 ON tb1.userid=tb3.admin_id';
        $select .= ', tb3.lev AS level';
    }
    $db->sqlreset()
        ->select('COUNT(*)')
        ->from($db_config['prefix'] . '_api_role_credential tb1')
        ->join($join)
        ->where('tb1.role_id=' . $role_id);
    $all_pages = $db->query($db->sql())
        ->fetchColumn();

    $db->select($select)
        ->order('tb1.addtime DESC');
    if (!empty($page)) {
        $db->limit($per_page)
            ->offset(($page - 1) * $per_page);
    }
    $result = $db->query($db->sql());

    $array = [];
    while ($row = $result->fetch()) {
        $row['fullname'] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
        !isset($row['level']) && $row['level'] = 0;
        $array[$row['userid']] = $row;
    }

    return [$all_pages, $array];
}

[$array_api_actions, $array_api_keys, $array_api_cats] = nv_get_api_actions('admin');
[$user_array_api_actions, $user_array_api_keys, $user_array_api_cats] = nv_get_api_actions('user');
