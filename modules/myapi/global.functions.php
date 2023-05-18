<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * nv_get_api_actions()
 * Lấy danh sách các API dựa trên các file
 * trong thư mục api của hệ thống hoặc Api của module
 *
 * @return array
 */
function nv_get_api_actions($object)
{
    global $lang_module, $sys_mods;

    $array_keys = $array_cats = $array_apis = ['' => []];

    // Các API của hệ thống
    $system_api_dir = $object == 'admin' ? 'api' : 'uapi';
    $class_dir = $object == 'admin' ? 'Api' : 'Uapi';
    $files = nv_scandir(NV_ROOTDIR . '/includes/' . $system_api_dir, '/(.*?)/');
    foreach ($files as $file) {
        unset($m);
        if (preg_match('/^([a-z][a-z0-9\_]*)\.php$/i', $file, $m)) {
            $class_name = $m[1];
            $class_namespaces = 'NukeViet\\' . $class_dir . '\\' . $class_name;
            if (nv_class_exists($class_namespaces)) {
                $class_cat = $class_namespaces::getCat();
                $cat_title = !empty($lang_module['api_' . $class_cat]) ? $lang_module['api_' . $class_cat] : $class_cat;
                $api_title = !empty($lang_module['api_' . $class_cat . '_' . $class_name]) ? $lang_module['api_' . $class_cat . '_' . $class_name] : $class_cat . '_' . $class_name;
                !isset($array_apis[''][$class_cat]) && $array_apis[''][$class_cat] = [
                    'title' => $cat_title,
                    'apis' => []
                ];
                $array_apis[''][$class_cat]['apis'][$class_name] = [
                    'title' => $api_title,
                    'cmd' => $class_name
                ];
                $array_keys[''][$class_name] = $class_name;
                $array_cats[''][$class_name] = [
                    'key' => $class_cat,
                    'title' => $cat_title,
                    'api_title' => $api_title
                ];
            }
        }
    }

    $lang_module_backup = $lang_module;

    // Các API của module cung cấp
    foreach ($sys_mods as $module_name => $module_info) {
        $module_file = $module_info['module_file'];
        $module_api_dir = $object == 'admin' ? 'Api' : 'uapi';
        if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/' . $module_api_dir)) {
            // Đọc ngôn ngữ tạm của module
            $lang_module = [];
            if ($object == 'admin') {
                if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_INTERFACE . '.php')) {
                    include NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_INTERFACE . '.php';
                } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_en.php')) {
                    include NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_en.php';
                }
            } else {
                if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_INTERFACE . '.php')) {
                    include NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_INTERFACE . '.php';
                } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/en.php')) {
                    include NV_ROOTDIR . '/modules/' . $module_file . '/language/en.php';
                }
            }

            // Lấy các API
            $files = nv_scandir(NV_ROOTDIR . '/modules/' . $module_file . '/' . $module_api_dir, '/(.*?)/');
            foreach ($files as $file) {
                unset($m);
                if (preg_match('/^([a-z][a-z0-9\_]*)\.php$/i', $file, $m)) {
                    $class_name = $m[1];
                    $class_namespaces = 'NukeViet\\Module\\' . $module_file . '\\' . $module_api_dir . '\\' . $class_name;
                    if (nv_class_exists($class_namespaces)) {
                        $class_cat = $class_namespaces::getCat();
                        $cat_title = (!empty($class_cat) and !empty($lang_module['api_' . $class_cat])) ? $lang_module['api_' . $class_cat] : $class_cat;
                        $api_title = (!empty($class_cat) and !empty($lang_module['api_' . $class_cat . '_' . $class_name])) ? $lang_module['api_' . $class_cat . '_' . $class_name] : (!empty($lang_module['api_' . $class_name]) ? $lang_module['api_' . $class_name] : $class_name);

                        // Xác định cây thư mục
                        !isset($array_apis[$module_name]) && $array_apis[$module_name] = [];
                        !isset($array_apis[$module_name][$class_cat]) && $array_apis[$module_name][$class_cat] = [
                            'title' => $cat_title,
                            'apis' => []
                        ];
                        $array_apis[$module_name][$class_cat]['apis'][$class_name] = [
                            'title' => $api_title,
                            'cmd' => $class_name
                        ];

                        // Xác định key
                        !isset($array_keys[$module_name]) && $array_keys[$module_name] = [];
                        $array_keys[$module_name][$class_name] = $class_name;

                        // Phân theo cat
                        !isset($array_cats[$module_name]) && $array_cats[$module_name] = [];
                        $array_cats[$module_name][$class_name] = [
                            'key' => $class_cat,
                            'title' => $cat_title,
                            'api_title' => $api_title
                        ];
                    }
                }
            }
        }
    }

    $lang_module = $lang_module_backup;

    return [$array_apis, $array_keys, $array_cats];
}

/**
 * parseRole()
 * Phân tích thông tin của role trong CSDL
 *
 * @param mixed $row
 * @return mixed
 */
function parseRole($row)
{
    global $lang_module, $array_api_cats, $user_array_api_cats, $global_config;

    $row['role_data'] = !empty($row['role_data']) ? json_decode($row['role_data'], true) : [];
    $cats = $row['role_object'] == 'admin' ? $array_api_cats : $user_array_api_cats;
    // Xử lý các API theo cat
    $row['apis'] = [];
    $row['apis'][''] = [];
    foreach ($global_config['setup_langs'] as $_lg) {
        $row['apis'][$_lg] = [];
    }
    $row['apitotal'] = 0;
    $row['api_doesnt_exist'] = [];
    if (!empty($row['role_data']['sys'])) {
        foreach ($row['role_data']['sys'] as $api_cmd) {
            if (isset($cats[''][$api_cmd])) {
                $cat = $cats[''][$api_cmd];
                if (!isset($row['apis'][''][$cat['key']])) {
                    $row['apis'][''][$cat['key']] = [
                        'title' => $cat['title'],
                        'apis' => []
                    ];
                }
                $row['apis'][''][$cat['key']]['apis'][$api_cmd] = $cat['api_title'];
                ++$row['apitotal'];
            } else {
                $row['api_doesnt_exist'][] = $api_cmd . ' (' . $lang_module['api_of_system'] . ')';
            }
        }
    }
    foreach ($global_config['setup_langs'] as $_lg) {
        if (!empty($row['role_data'][$_lg])) {
            foreach ($row['role_data'][$_lg] as $mod_title => $mod_data) {
                foreach ($mod_data as $api_cmd) {
                    if (isset($cats[$mod_title][$api_cmd])) {
                        $cat = $cats[$mod_title][$api_cmd];
                        if (!isset($row['apis'][$_lg][$mod_title])) {
                            $row['apis'][$_lg][$mod_title] = [];
                        }
                        if (!isset($row['apis'][$_lg][$mod_title][$cat['key']])) {
                            $row['apis'][$_lg][$mod_title][$cat['key']] = [
                                'title' => $cat['title'],
                                'apis' => []
                            ];
                        }
                        $row['apis'][$_lg][$mod_title][$cat['key']]['apis'][$api_cmd] = $cat['api_title'];
                        if ($_lg == NV_LANG_DATA) {
                            ++$row['apitotal'];
                        }
                    } else {
                        $row['api_doesnt_exist'][] = $api_cmd . '(' . $mod_title . ')';
                    }
                }
            }
        }
    }

    $row['flood_rules'] = !empty($row['flood_rules']) ? json_decode($row['flood_rules'], true) : [];

    return $row;
}

/**
 * myApiRoleList()
 *
 * @param string $type
 * @param int    $page
 * @param int    $per_page
 * @return array
 */
function myApiRoleList($type = 'public', $page = 0, $per_page = 20)
{
    global $db, $db_config, $admin_info, $user_info;

    $type != 'private' && $type = 'public';
    $userid = defined('NV_ADMIN') ? $admin_info['admin_id'] : $user_info['userid'];

    $select = 'tb1.*, IFNULL(tb2.access_count,-1) AS credential_access_count, IFNULL(tb2.last_access,-1) AS credential_last_access, IFNULL(tb2.addtime,-1) AS credential_addtime, IFNULL(tb2.endtime,-1) AS credential_endtime, IFNULL(tb2.quota,-1) AS credential_quota, IFNULL(tb2.status,-1) AS credential_status';
    $where = "tb1.role_type = '" . $type . "'";
    if (!defined('NV_ADMIN')) {
        $where .= " AND tb1.role_object ='user'";
    }
    $join = ($type == 'private' ? 'INNER JOIN' : 'LEFT JOIN') . ' ' . $db_config['prefix'] . '_api_role_credential tb2 ON (tb2.role_id =tb1.role_id AND tb2.userid=' . $userid . ')';

    $db->sqlreset()
        ->select('COUNT(*)')
        ->from($db_config['prefix'] . '_api_role tb1')
        ->join($join)
        ->where($where);
    $all_pages = $db->query($db->sql())
        ->fetchColumn();

    $db->select($select)
        ->order('tb1.role_id DESC');
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
 * getRoleDetails()
 * Lấy thông tin một role cụ thể
 *
 * @param mixed $id
 * @param bool  $isParseRole
 * @return mixed
 */
function getRoleDetails($id, $isParseRole = true)
{
    global $db, $db_config;

    $row = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_api_role WHERE role_id = ' . $id)->fetch();
    if (!$isParseRole) {
        return $row;
    }

    return !empty($row) ? parseRole($row) : [];
}

/**
 * delAuth()
 *
 * @param mixed $method
 * @param int   $userid
 * @return true
 */
function delAuth($method, $userid = 0)
{
    global $db, $db_config, $admin_info, $user_info, $crypt;

    if (empty($userid)) {
        $userid = defined('NV_ADMIN') ? $admin_info['admin_id'] : $user_info['userid'];
    }

    $db->query('DELETE FROM ' . $db_config['prefix'] . '_api_user WHERE userid = ' . $userid . ' AND method = ' . $db->quote($method));

    return true;
}

/**
 * createAuth()
 *
 * @param mixed $method
 * @param int   $userid
 * @return string[]
 * @throws PDOException
 */
function createAuth($method, $userid = 0)
{
    global $db, $db_config, $admin_info, $user_info, $crypt;

    if (empty($userid)) {
        $userid = defined('NV_ADMIN') ? $admin_info['admin_id'] : $user_info['userid'];
    }

    $new_ident = '';
    $new_secret = '';
    while (empty($new_ident) or $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_user WHERE ident = ' . $db->quote($new_ident) . ' AND userid != ' . $userid)->fetchColumn()) {
        $new_ident = nv_genpass(32, 3);
    }
    while (empty($new_secret) or $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_user WHERE secret = ' . $db->quote($new_secret) . ' AND userid != ' . $userid)->fetchColumn()) {
        $new_secret = nv_genpass(32, 3);
    }
    $new_secret_db = $crypt->encrypt($new_secret);

    if ($db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_user WHERE userid = ' . $userid . ' AND method=' . $db->quote($method))->fetchColumn()) {
        $sql = 'UPDATE ' . $db_config['prefix'] . '_api_user SET
            ident = :ident,
            secret = :secret,
            edittime=' . NV_CURRENTTIME . '
            WHERE userid =' . $userid . ' AND method=:method';
    } else {
        $sql = 'INSERT INTO ' . $db_config['prefix'] . '_api_user (
            userid, ident, secret, ips, method, addtime
        ) VALUES (
            ' . $userid . ", :ident, :secret, '[]', :method, " . NV_CURRENTTIME . '
        )';
    }
    $sth = $db->prepare($sql);
    $sth->bindParam(':ident', $new_ident, PDO::PARAM_STR);
    $sth->bindParam(':secret', $new_secret_db, PDO::PARAM_STR);
    $sth->bindParam(':method', $method, PDO::PARAM_STR);
    $sth->execute();

    return [$new_ident, $new_secret];
}

/**
 * ipsUpdate()
 *
 * @param mixed $api_ips
 * @param mixed $method
 * @param int   $userid
 * @return bool
 * @throws PDOException
 */
function ipsUpdate($api_ips, $method, $userid = 0)
{
    global $db, $db_config, $admin_info, $user_info;

    if (empty($userid)) {
        $userid = defined('NV_ADMIN') ? $admin_info['admin_id'] : $user_info['userid'];
    }

    $sth = $db->prepare('UPDATE ' . $db_config['prefix'] . '_api_user SET
    ips = :ips,
    edittime=' . NV_CURRENTTIME . '
    WHERE userid =' . $userid . ' AND method=:method');
    $sth->bindParam(':ips', $api_ips, PDO::PARAM_STR);
    $sth->bindParam(':method', $method, PDO::PARAM_STR);

    return $sth->execute();
}

/**
 * get_api_user()
 *
 * @param int $userid
 * @return array
 */
function get_api_user($userid = 0)
{
    global $db, $db_config, $admin_info, $user_info;

    if (empty($userid)) {
        $userid = defined('NV_ADMIN') ? $admin_info['admin_id'] : $user_info['userid'];
    }

    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_api_user WHERE userid = ' . $userid;
    $result = $db->query($sql);
    $api_user = [];
    while ($row = $result->fetch()) {
        if (!empty($row['ips'])) {
            $row['ips'] = json_decode($row['ips'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $row['ips'] = implode(', ', $row['ips']);
            } else {
                $row['ips'] = '';
            }
        }
        $api_user[$row['method']] = $row;
    }

    return $api_user;
}
