<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SETTINGS')) {
    exit('Stop!!!');
}

$pattern_plugin = '/^([a-zA-Z0-9\_]+)\.php$/';
$page_title = $nv_Lang->getModule('plugin');

// Thay đổi thứ tự ưu tiên
if ($nv_Request->isset_request('changeweight', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL!!!');
    }

    $pid = $nv_Request->get_int('pid', 'post', 0);
    $new_weight = $nv_Request->get_int('new_weight', 'post', 0);

    $row = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_plugins WHERE pid=' . $pid)->fetch();
    if (empty($row)) {
        nv_htmlOutput('ERROR');
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('plugin_log_weight'), $pid . '-' . $new_weight, $admin_info['userid']);

    $sql = 'SELECT pid FROM ' . $db_config['prefix'] . '_plugins
    WHERE pid!=' . $pid . ' AND (plugin_lang=' . $db->quote(NV_LANG_DATA) . ' OR plugin_lang=\'all\')
    AND plugin_area=' . $db->quote($row['plugin_area']) . '
    AND hook_module=' . $db->quote($row['hook_module']) . ' ORDER BY weight ASC';
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }
        $db->query('UPDATE ' . $db_config['prefix'] . '_plugins SET weight=' . $weight . ' WHERE pid=' . $row['pid']);
    }

    $db->query('UPDATE ' . $db_config['prefix'] . '_plugins SET weight=' . $new_weight . ' WHERE pid=' . $pid);

    nv_save_file_config_global();
    nv_htmlOutput('OK');
}

// Xóa plugin
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL!!!');
    }

    $pid = $nv_Request->get_int('pid', 'post', 0);
    $row = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_plugins WHERE pid=' . $pid)->fetch();
    if (empty($row) or !empty($row['plugin_module_file']) or ($row['plugin_lang'] != 'all' and $row['plugin_lang'] != NV_LANG_DATA)) {
        nv_htmlOutput('ERROR');
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('plugin_log_del'), $pid . '-' . $row['plugin_file'], $admin_info['userid']);

    $db->exec('DELETE FROM ' . $db_config['prefix'] . '_plugins WHERE pid = ' . $pid);

    $weight = (int) ($row['weight']);
    $_query = $db->query('SELECT pid FROM ' . $db_config['prefix'] . '_plugins
    WHERE (plugin_lang=' . $db->quote(NV_LANG_DATA) . ' OR plugin_lang=\'all\')
    AND plugin_area=' . $db->quote($row['plugin_area']) . '
    AND hook_module=' . $db->quote($row['hook_module']) . '
    AND weight > ' . $weight . ' ORDER BY weight ASC');

    while ([$pid] = $_query->fetch(3)) {
        $db->query('UPDATE ' . $db_config['prefix'] . '_plugins SET weight = ' . $weight . ' WHERE pid=' . $pid);
        ++$weight;
    }

    nv_save_file_config_global();
    nv_htmlOutput('OK');
}

// Lấy list các HOOK theo module hoặc hệ thống
$sql = 'SELECT DISTINCT plugin_area, hook_module FROM ' . $db_config['prefix'] . "_plugins
WHERE plugin_lang='all' OR plugin_lang='" . NV_LANG_DATA . "'";
$result = $db->query($sql);
$array_areas = [];
while ($row = $result->fetch()) {
    $_key = (empty($row['hook_module']) ? '' : $row['hook_module'] . ':') . $row['plugin_area'];
    $array_areas[$_key] = $_key;
}

// Tìm kiếm
$array_search = [
    'area' => $nv_Request->get_title('a', 'get', ''),
    's_plugin_area' => '',
    's_hook_module' => ''
];
if (!empty($array_search['area']) and !isset($array_areas[$array_search['area']])) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

// Đọc các plugin trong CSDL
$max_weight = 0;
$sql = 'SELECT * FROM ' . $db_config['prefix'] . "_plugins WHERE plugin_lang='all' OR plugin_lang='" . NV_LANG_DATA . "'";
if (!empty($array_search['area'])) {
    // Xử lý lại phần tìm kiếm
    $_area = explode(':', $array_search['area']);
    if (isset($_area[1])) {
        $array_search['s_plugin_area'] = $_area[1];
        $array_search['s_hook_module'] = $_area[0];
    } else {
        $array_search['s_plugin_area'] = $array_search['area'];
    }

    $sql .= ' ORDER BY weight ASC';
    $max_weight = $db->query('SELECT MAX(weight) FROM ' . $db_config['prefix'] . "_plugins
    WHERE (plugin_lang='all' OR plugin_lang='" . NV_LANG_DATA . "')
    AND plugin_area=" . $db->quote($array_search['s_plugin_area']) . '
    AND hook_module=' . $db->quote($array_search['s_hook_module']))->fetchColumn();
} else {
    $sql .= ' ORDER BY hook_module ASC, plugin_area ASC';
}
$result = $db->query($sql);

$array = $sys_exists = $mod_counts = $mod_exists = [];
while ($row = $result->fetch()) {
    if (empty($row['plugin_module_file'])) {
        // Không tính các plugin đi theo module
        if (empty($row['hook_module']) and empty($row['plugin_module_name'])) {
            // Plugin thuần hệ thống đã cài
            $sys_exists[] = $row['plugin_file'];
        } else {
            // Xác định tổng số plugin đã cài cho 1 key plugin => Để check khả dụng tích hợp mới
            $file_key = md5($row['plugin_file']);

            $key1 = empty($row['hook_module']) ? '__' : false;
            $key2 = empty($row['plugin_module_name']) ? '__' : false;
            if (isset($site_mods[$row['hook_module']])) {
                $key1 = $site_mods[$row['hook_module']]['module_file'];
            }
            if (isset($site_mods[$row['plugin_module_name']])) {
                $key2 = $site_mods[$row['plugin_module_name']]['module_file'];
            }
            if ($key1 !== false and $key2 !== false) {
                $key = $key1 . ':' . $row['plugin_area'] . ':' . $key2;
                if (!isset($mod_counts[$key]) or !isset($mod_counts[$key][$file_key])) {
                    $mod_counts[$key][$file_key] = 0;
                }
                ++$mod_counts[$key][$file_key];

                $key1 = empty($row['hook_module']) ? '' : $row['hook_module'];
                $mod_exists[$key][$file_key][$key1][] = $row['plugin_module_name'];
            }
        }
    }

    if (empty($array_search['area']) or ($array_search['s_plugin_area'] == $row['plugin_area'] and $array_search['s_hook_module'] == $row['hook_module'])) {
        !empty($row['hook_module']) && $row['hook_module'] .= ':';
        $array[] = $row;
    }
}

// Quét các plugin khả dụng chưa thêm
$file_plugins = nv_scandir(NV_ROOTDIR . '/includes/plugin', $pattern_plugin);
$available_plugins = [];

foreach ($file_plugins as $file_name) {
    $file = NV_ROOTDIR . '/includes/plugin/' . $file_name;
    $pl_area = nv_get_plugin_area($file); // Array các hook
    $pl_hook_module = nv_get_hook_require($file); // Module nguồn (module file)
    $pl_receive_module = nv_get_hook_revmod($file); // Module đích (module file)

    if (isset($pl_area[0])) {
        $_key = (empty($pl_hook_module) ? '__' : $pl_hook_module) . ':' . $pl_area[0] . ':' . (empty($pl_receive_module) ? '__' : $pl_receive_module);
        $file_key = md5($file_name);
        $is_exists = false;

        if (empty($pl_hook_module) and empty($pl_receive_module)) {
            $is_exists = in_array($file_name, $sys_exists, true);
        } elseif (isset($mod_counts[$_key]) and isset($mod_counts[$_key][$file_key]) and $mod_counts[$_key][$file_key] >= get_max_pulgin($pl_hook_module, $pl_receive_module)) {
            $is_exists = true;
        }

        if (!$is_exists) {
            $available_plugins[$_key][$file_key] = [
                'file' => $file_name,
                'area' => $pl_area,
                'hook_module' => $pl_hook_module,
                'receive_module' => $pl_receive_module
            ];
        }
    }
}

// Data cho modal chọn module nguồn, đích
if ($nv_Request->isset_request('loadform', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL!!!');
    }

    $respon = [
        'message' => '', // Có lỗi thì trả vào đây
        'tag' => '', // Tên tag (tên hook)
        'hook_mod' => '', // Module_file nguồn
        'hook_mods' => [], // Các module nguồn
        'exists' => [], // Các plugin đã cài vào CSDL
        'receive_mod' => '', // Module_file đích
        'receive_mods' => [] // Các module đích
    ];

    $post = [];
    $post['hook_key'] = $nv_Request->get_title('hook_key', 'post', '');
    $post['file_key'] = $nv_Request->get_title('file_key', 'post', '');

    if (!isset($available_plugins[$post['hook_key']]) or !isset($available_plugins[$post['hook_key']][$post['file_key']])) {
        $respon['message'] = 'File not exists!!!';
        nv_jsonOutput($respon);
    }

    $row = $available_plugins[$post['hook_key']][$post['file_key']];
    $respon['hook_mod'] = $row['hook_module'];
    $respon['receive_mod'] = $row['receive_module'];
    $respon['tag'] = $row['area'][0];

    /*
     * Build data các hook_mod đã được cài trước đó
     * tương thích với javascript object
     */
    if (isset($mod_exists[$post['hook_key']]) and isset($mod_exists[$post['hook_key']][$post['file_key']])) {
        foreach ($mod_exists[$post['hook_key']][$post['file_key']] as $hook_mod => $rev_mods) {
            $respon['exists'][] = [
                'hook_mod' => $hook_mod,
                'receive_mods' => $rev_mods
            ];
        }
    }

    foreach ($site_mods as $mod_title => $mod_data) {
        if ($mod_data['module_file'] == $row['hook_module']) {
            // Các module nguồn
            $respon['hook_mods'][] = [
                'key' => $mod_title,
                'title' => $mod_data['custom_title'] . ' (' . $mod_title . ')',
            ];
        }
        if ($mod_data['module_file'] == $row['receive_module']) {
            // Các module đích
            $respon['receive_mods'][] = [
                'key' => $mod_title,
                'title' => $mod_data['custom_title'] . ' (' . $mod_title . ')',
            ];
        }
    }

    if (empty($respon['receive_mods']) and empty($respon['hook_mods'])) {
        $respon['message'] = 'No data for integrate!!!';
    }

    nv_jsonOutput($respon);
}

// Tích hợp plugin mới
if ($nv_Request->isset_request('integrate', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL!!!');
    }

    $respon = [
        'message' => ''
    ];

    $post = [];
    $post['hook_key'] = $nv_Request->get_title('hook_key', 'post', '');
    $post['file_key'] = $nv_Request->get_title('file_key', 'post', '');

    if (!isset($available_plugins[$post['hook_key']]) or !isset($available_plugins[$post['hook_key']][$post['file_key']])) {
        $respon['message'] = 'File not exists!!!';
        nv_jsonOutput($respon);
    }

    $post['hook_module'] = $nv_Request->get_title('hook_module', 'post', '');
    $post['receive_module'] = $nv_Request->get_title('receive_module', 'post', '');

    $row = $available_plugins[$post['hook_key']][$post['file_key']];
    $post['lang'] = (!empty($row['hook_module']) or !empty($row['receive_module'])) ? NV_LANG_DATA : 'all';

    if (!empty($row['hook_module'])) {
        if (!isset($site_mods[$post['hook_module']]) or $site_mods[$post['hook_module']]['module_file'] != $row['hook_module']) {
            $respon['message'] = 'hook_module not exists!!!';
            nv_jsonOutput($respon);
        }
    } else {
        $post['hook_module'] = '';
    }
    if (!empty($row['receive_module'])) {
        if (!isset($site_mods[$post['receive_module']]) or $site_mods[$post['receive_module']]['module_file'] != $row['receive_module']) {
            $respon['message'] = 'receive_module not exists!!!';
            nv_jsonOutput($respon);
        }
    } else {
        $post['receive_module'] = '';
    }

    // Kiểm tra trùng
    $sql = 'SELECT pid FROM ' . $db_config['prefix'] . '_plugins WHERE plugin_lang=' . $db->quote($post['lang']) . '
    AND plugin_file=' . $db->quote($row['file']) . ' AND plugin_area=' . $db->quote($row['area'][0]) . '
    AND plugin_module_name=' . $db->quote($post['receive_module']) . ' AND hook_module=' . $db->quote($post['hook_module']);
    if ($db->query($sql)->fetchColumn()) {
        $respon['message'] = 'Error exists!!!';
        nv_jsonOutput($respon);
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('plugin_log_integrate'), $post['hook_module'] . ' - ' . $row['file'] . ' - ' . $post['receive_module'], $admin_info['userid']);

    // Lấy vị trí mới
    $_sql = 'SELECT MAX(weight) FROM ' . $db_config['prefix'] . '_plugins
    WHERE plugin_lang=' . $db->quote($post['lang']) . ' AND plugin_area=' . $db->quote($row['area'][0]) . '
    AND hook_module=' . $db->quote($post['hook_module']);
    $weight = $db->query($_sql)->fetchColumn();
    $weight = (int) $weight + 1;

    try {
        $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_plugins (
            plugin_lang, plugin_file, plugin_area, plugin_module_name, hook_module, weight
        ) VALUES (
            :plugin_lang, :plugin_file, :plugin_area, :plugin_module_name, :hook_module, :weight
        )');
        $sth->bindParam(':plugin_lang', $post['lang'], PDO::PARAM_STR);
        $sth->bindParam(':plugin_file', $row['file'], PDO::PARAM_STR);
        $sth->bindParam(':plugin_area', $row['area'][0], PDO::PARAM_STR);
        $sth->bindParam(':plugin_module_name', $post['receive_module'], PDO::PARAM_STR);
        $sth->bindParam(':hook_module', $post['hook_module'], PDO::PARAM_STR);
        $sth->bindParam(':weight', $weight, PDO::PARAM_INT);
        $sth->execute();

        nv_save_file_config_global();
    } catch (PDOException $e) {
        trigger_error($e->getMessage());
        $respon['message'] = 'Error DB1';
    }

    nv_jsonOutput($respon);
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

// List danh sách các hook đã tích hợp để lọc
foreach ($array_areas as $area) {
    $p_area = [
        'key' => $area,
        'selected' => $area == $array_search['area'] ? ' selected="selected"' : ''
    ];
    $xtpl->assign('AREA', $p_area);
    $xtpl->parse('main.select_hook');
}

// Hiển thị cột chỉnh thứ tự
if (!empty($array_search['area'])) {
    $xtpl->parse('main.col_weight');
    $xtpl->parse('main.note_order');
}

// Xuất plugin đã tích hợp
foreach ($array as $row) {
    $row['type'] = empty($row['plugin_module_name']) ? $nv_Lang->getModule('plugin_type_sys') : $nv_Lang->getModule('plugin_type_module') . ':' . $row['plugin_module_name'];
    $xtpl->assign('ROW', $row);

    if (!empty($array_search['area'])) {
        for ($i = 1; $i <= $max_weight; ++$i) {
            $xtpl->assign('WEIGHT', [
                'key' => $i,
                'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
            ]);
            $xtpl->parse('main.loop.weight.loop');
        }

        $xtpl->parse('main.loop.weight');
    }

    /*
     * Plugin trong thư mục modules/ thì chỉ xóa khi xóa module để đảm bảo module hoạt động bình thường
     * Plugin trong thư mục includes/plugin là phần cấu hình có thể xóa/thêm tự do
     */
    if (empty($row['plugin_module_file'])) {
        $xtpl->parse('main.loop.delete');
    }

    $xtpl->parse('main.loop');
}

// Xuất các plugin khả dụng
if (!empty($available_plugins)) {
    foreach ($available_plugins as $hook_key => $rows) {
        $xtpl->assign('HOOK_KEY', $hook_key);

        foreach ($rows as $file_key => $row) {
            $sizeof = !empty($row['area']);
            $row['area'] = implode(', ', $row['area']);
            !empty($row['hook_module']) && $row['area'] = $row['hook_module'] . ':' . $row['area'];
            $row['type'] = empty($row['receive_module']) ? $nv_Lang->getModule('plugin_type_sys') : $nv_Lang->getModule('plugin_type_module') . ':' . $row['receive_module'];
            $row['status'] = $sizeof ? $nv_Lang->getModule('plugin_status_ok') : $nv_Lang->getModule('plugin_status_error');
            $xtpl->assign('FILE_KEY', $file_key);
            $xtpl->assign('ROW', $row);

            if ($sizeof) {
                $xtpl->assign('RAND', nv_genpass());
                $xtpl->parse('main.plugin_available.row.plugin_integrate');
            }
            $xtpl->parse('main.plugin_available.row');
        }
    }
    $xtpl->parse('main.plugin_available');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
