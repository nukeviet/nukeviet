<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

define('NV_IS_MOD_CONTACT', true);
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

function parse_others($others)
{
    if (!empty($others)) {
        $_others = json_decode($others, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $_others = unserialize($others);
        }

        return $_others;
    }

    return [];
}

/**
 * get_department_list()
 *
 * @return array|void
 */
function get_department_list()
{
    global $nv_Cache, $module_name, $module_info;

    $cache_file = 'departmentlist' . NV_CACHE_PREFIX . '.cache';
    if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
        return unserialize($cache);
    }

    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_department ORDER BY weight';
    $_departments = $nv_Cache->db($sql, 'id', $module_name);

    $departments_by_id = [];
    $departments_by_alias = [];
    if (!empty($_departments)) {
        foreach ($_departments as $id => $department) {
            if (empty($department['act'])) {
                continue;
            }
            $department['image'] = !empty($department['image']) ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_info['module_upload'] . '/' . $department['image'] : '';
            $department['phone'] = !empty($department['phone']) ? nv_parse_phone($department['phone']) : [];
            $department['email'] = !empty($department['email']) ? array_map('trim', explode(',', $department['email'])) : [];
            $department['others'] = parse_others($department['others']);
            if (!empty($department['cats'])) {
                $cats = json_decode($department['cats'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $cats = explode('|', $department['cats']);
                }
                $department['cats'] = [];
                foreach ($cats as $k => $cat) {
                    $department['cats'][$id . '_' . $k] = $cat;
                }
            } else {
                $department['cats'] = [];
            }
            if (!empty($department['admins'])) {
                $department['admins'] = parse_admins($department['admins']);
            } else {
                $department['admins'] = [];
            }

            $departments_by_id[$id] = $department;
            $departments_by_alias[$department['alias']] = $id;
        }
    }
    $departments = [$departments_by_id, $departments_by_alias];
    $cache = serialize($departments);
    $nv_Cache->setItem($module_name, $cache_file, $cache);

    return $departments;
}

/**
 * get_supporter_list()
 *
 * @return mixed
 * @param mixed $departments
 */
function get_supporter_list($departments)
{
    global $db, $nv_Cache, $module_name, $module_info;

    $cache_file = 'supporterlist' . NV_CACHE_PREFIX . '.cache';
    if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
        return unserialize($cache);
    }

    $supporter_list = [];
    $result = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_supporter WHERE act = 1 ORDER BY departmentid, weight');
    while ($row = $result->fetch()) {
        !isset($supporter_list[$row['departmentid']]) && $supporter_list[$row['departmentid']] = [];
        $supporter_list[$row['departmentid']][$row['id']] = [
            'full_name' => $row['full_name'],
            'image' => !empty($row['image']) ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_info['module_upload'] . '/' . $row['image'] : NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/supporter.svg',
            'phone' => !empty($row['phone']) ? nv_parse_phone($row['phone']) : [],
            'email' => $row['email'],
            'others' => parse_others($row['others'])
        ];
    }

    $supporters = [];
    if (isset($supporter_list[0])) {
        $supporters[0] = $supporter_list[0];
    }
    if (!empty($departments)) {
        $keys = array_keys($departments);
        foreach ($keys as $key) {
            if (isset($supporter_list[$key])) {
                $supporters[$key] = $supporter_list[$key];
            }
        }
    }

    $cache = serialize($supporters);
    $nv_Cache->setItem($module_name, $cache_file, $cache);

    return $supporters;
}
