<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
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
    $params = [];
    if (!empty($type)) {
        $where[] = 'role_type = :role_type';
        $params[':role_type'] = [$type, PDO::PARAM_STR];
    }
    if (!empty($object)) {
        $where[] = 'role_object = :role_object';
        $params[':role_object'] = [$object, PDO::PARAM_STR];
    }
    $where_str = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_role' . $where_str);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val[0], $val[1]);
    }
    $stmt->execute();
    $all_pages = $stmt->fetchColumn();

    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_api_role' . $where_str . ' ORDER BY role_id DESC';
    if (!empty($page)) {
        $sql .= ' LIMIT ' . (int) ($page - 1) * $per_page . ',' . (int) $per_page;
    }
    $stmt = $db->prepare($sql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val[0], $val[1]);
    }
    $stmt->execute();

    $array = [];
    while ($row = $stmt->fetch()) {
        $array[$row['role_id']] = parseRole($row);
    }
    $stmt->closeCursor();

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

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_role WHERE role_id = :role_id');
    $stmt->bindValue(':role_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $exists = $stmt->fetchColumn();

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

    $join = 'INNER JOIN ' . NV_USERS_GLOBALTABLE . ' tb2 ON (tb1.userid = tb2.userid)';
    $select = 'tb1.*, tb2.username, tb2.first_name, tb2.last_name';
    if ($for_admin) {
        $join .= ' INNER JOIN ' . NV_AUTHORS_GLOBALTABLE . ' tb3 ON tb1.userid = tb3.admin_id';
        $select .= ', tb3.lev AS level';
    }

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_role_credential tb1 ' . $join . ' WHERE tb1.role_id = :role_id');
    $stmt->bindValue(':role_id', $role_id, PDO::PARAM_INT);
    $stmt->execute();
    $all_pages = $stmt->fetchColumn();

    $sql = 'SELECT ' . $select . ' FROM ' . $db_config['prefix'] . '_api_role_credential tb1 ' . $join . ' WHERE tb1.role_id = :role_id ORDER BY tb1.addtime DESC';
    if (!empty($page)) {
        $sql .= ' LIMIT ' . (int) ($page - 1) * $per_page . ',' . (int) $per_page;
    }
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':role_id', $role_id, PDO::PARAM_INT);
    $stmt->execute();

    $array = [];
    while ($row = $stmt->fetch()) {
        $row['fullname'] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
        !isset($row['level']) && $row['level'] = 0;
        $array[$row['userid']] = $row;
    }
    $stmt->closeCursor();

    return [$all_pages, $array];
}

[$array_api_actions, $array_api_keys, $array_api_cats] = nv_get_api_actions('admin');
[$user_array_api_actions, $user_array_api_keys, $user_array_api_cats] = nv_get_api_actions('user');
