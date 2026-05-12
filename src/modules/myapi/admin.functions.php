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
 * @param int $role_id
 * @param bool $for_admin
 * @param int $page
 * @param int $per_page
 * @param array $search
 * @return array{0: string, 1: array}
 */
function getCredentialList($role_id, $for_admin, $page, $per_page, $search = [])
{
    global $db, $db_config, $global_config;

    $join = 'INNER JOIN ' . NV_USERS_GLOBALTABLE . ' tb2 ON (tb1.userid = tb2.userid)';
    $select = 'tb1.*, tb2.username, tb2.email, tb2.first_name, tb2.last_name';
    if ($for_admin) {
        $join .= ' INNER JOIN ' . NV_AUTHORS_GLOBALTABLE . ' tb3 ON tb1.userid = tb3.admin_id';
        $select .= ', tb3.lev AS level';
    }

    $where_parts = ['tb1.role_id = :role_id'];
    $params = [':role_id' => [$role_id, PDO::PARAM_INT]];

    if (!empty($search['q'])) {
        $q_val = '%' . $search['q'] . '%';
        $name_concat = $global_config['name_show'] == 0
            ? "CONCAT(tb2.last_name, ' ', tb2.first_name)"
            : "CONCAT(tb2.first_name, ' ', tb2.last_name)";
        $where_parts[] = "(tb2.username LIKE :q0 OR tb2.email LIKE :q1 OR {$name_concat} LIKE :q2)";
        $params[':q0'] = [$q_val, PDO::PARAM_STR];
        $params[':q1'] = [$q_val, PDO::PARAM_STR];
        $params[':q2'] = [$q_val, PDO::PARAM_STR];
    }
    if (!empty($search['t_addtime_from'])) {
        $where_parts[] = 'tb1.addtime >= :t_addtime_from';
        $params[':t_addtime_from'] = [$search['t_addtime_from'], PDO::PARAM_INT];
    }
    if (!empty($search['t_addtime_to'])) {
        $where_parts[] = 'tb1.addtime <= :t_addtime_to';
        $params[':t_addtime_to'] = [$search['t_addtime_to'], PDO::PARAM_INT];
    }
    if (!empty($search['t_endtime_from'])) {
        $where_parts[] = 'tb1.endtime >= :t_endtime_from';
        $params[':t_endtime_from'] = [$search['t_endtime_from'], PDO::PARAM_INT];
    }
    if (!empty($search['t_endtime_to'])) {
        $where_parts[] = 'tb1.endtime > 0 AND tb1.endtime <= :t_endtime_to';
        $params[':t_endtime_to'] = [$search['t_endtime_to'], PDO::PARAM_INT];
    }
    if (!empty($search['t_last_access_from'])) {
        $where_parts[] = 'tb1.last_access >= :t_last_access_from';
        $params[':t_last_access_from'] = [$search['t_last_access_from'], PDO::PARAM_INT];
    }
    if (!empty($search['t_last_access_to'])) {
        $where_parts[] = 'tb1.last_access > 0 AND tb1.last_access <= :t_last_access_to';
        $params[':t_last_access_to'] = [$search['t_last_access_to'], PDO::PARAM_INT];
    }

    $where_str = implode(' AND ', $where_parts);

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_role_credential tb1 ' . $join . ' WHERE ' . $where_str);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val[0], $val[1]);
    }
    $stmt->execute();
    $all_pages = $stmt->fetchColumn();

    $sql = 'SELECT ' . $select . ' FROM ' . $db_config['prefix'] . '_api_role_credential tb1 ' . $join . ' WHERE ' . $where_str . ' ORDER BY tb1.addtime DESC';
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
        $row['fullname'] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
        !isset($row['level']) && $row['level'] = 0;
        $array[$row['userid']] = $row;
    }
    $stmt->closeCursor();

    return [$all_pages, $array];
}

[$array_api_actions, $array_api_keys, $array_api_cats] = nv_get_api_actions('admin');
[$user_array_api_actions, $user_array_api_keys, $user_array_api_cats] = nv_get_api_actions('user');
