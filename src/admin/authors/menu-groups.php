<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_AUTHORS')) {
    exit('Stop!!!');
}

require NV_ROOTDIR . '/includes/fontawesome.php';

/**
 * Phạm vi:
 * personal: nhóm riêng của admin đang đăng nhập
 * default: mặc định chung toàn site, chỉ điều hành chung trở lên được sửa
 */
$scope = (defined('NV_IS_SPADMIN') and $nv_Request->get_title('scope', 'get,post', '') === 'default') ? 'default' : 'personal';

// Lấy icon
if ($nv_Request->get_title('ajax_icon', 'post', '') === '1') {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    nv_jsonOutput(nv_fontawesome_search(
        $nv_Request->get_title('q', 'post', ''),
        $nv_Request->get_absint('page', 'post', 1)
    ));
}

// Các module có trang quản trị mà admin được quyền
$array_mods = [];
foreach ($site_mods as $mod => $row) {
    if (!empty($row['admin_file'])) {
        $array_mods[$mod] = $row['admin_title'];
    }
}

$sql_personal = ' FROM ' . NV_AUTHORS_GLOBALTABLE . '_vars WHERE admin_id=' . $admin_info['admin_id'] . '
AND lang=' . $db->quote(NV_LANG_DATA) . ' AND theme=' . $db->quote($admin_info['admin_theme']) . " AND config_name='menu_groups'";
$personal_config = $db->query('SELECT config_value' . $sql_personal)->fetchColumn();
$default_config = $global_config['admin_menu_groups'] ?? '';

// Lưu hoặc xóa cấu hình
if ($nv_Request->isset_request('checkss', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $is_reset = $nv_Request->isset_request('reset', 'post');
    $config_value = '';
    if (!$is_reset) {
        $titles = $nv_Request->get_typed_array('group_title', 'post', 'title', [], 100);
        $icons = $nv_Request->get_typed_array('group_icon', 'post', 'title', []);
        $mods = $nv_Request->get_typed_array('group_mods', 'post', 'title', []);

        $groups = [];
        foreach (array_values($titles) as $i => $title) {
            if ($title === '') {
                nv_jsonOutput([
                    'status' => 'error',
                    'input' => 'group_title',
                    'input_parent' => '[data-group-index="' . $i . '"]',
                    'mess' => $nv_Lang->getModule('menu_groups_error_title')
                ]);
            }
            $icon = $icons[$i] ?? '';
            $groups[] = [
                'title' => $title,
                'icon' => isset($fontawesome_packs[$icon]) ? $icon : '',
                'mods' => explode(',', $mods[$i] ?? '')
            ];
        }
        $config_value = json_encode(nv_admin_menu_groups($groups, array_keys($array_mods), true), NV_JSON_ENCODE);
    }

    if ($scope == 'default') {
        // Mặc định chung không có nhóm nào thì để rỗng để menu về dạng phẳng
        if ($is_reset or $config_value === '[]') {
            $config_value = '';
        }
        $stmt = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = :lang AND module = 'global' AND config_name = 'admin_menu_groups'");
        $stmt->bindValue(':config_value', $config_value, PDO::PARAM_STR);
        $stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
        $stmt->execute();
        $nv_Cache->delMod('settings');
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule($is_reset ? 'menu_groups_log_reset' : 'menu_groups_log_save'), '', $admin_info['userid']);
    } else {
        $db->query('DELETE' . $sql_personal);

        /**
         * Cấu hình riêng lưu cả khi không có nhóm nào để admin chọn được
         * menu tự động lấy từ các module dù đã có mặc định chung
         */
        if (!$is_reset) {
            $stmt = $db->prepare('INSERT INTO ' . NV_AUTHORS_GLOBALTABLE . "_vars (admin_id, lang, theme, config_name, config_value) VALUES (:admin_id, :lang, :theme, 'menu_groups', :config_value)");
            $stmt->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
            $stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
            $stmt->bindValue(':theme', $admin_info['admin_theme'], PDO::PARAM_STR);
            $stmt->bindValue(':config_value', $config_value, PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getModule($is_reset ? 'menu_groups_reset_success' : 'menu_groups_save_success'),
        'refresh' => true
    ]);
}

if ($scope == 'default') {
    $has_config = $default_config !== '';
    $groups = nv_admin_menu_groups($default_config, array_keys($array_mods), true);
} else {
    $has_config = $personal_config !== false;
    $groups = nv_admin_menu_groups($has_config ? (string) $personal_config : $default_config, array_keys($array_mods), true);
}

$page_title = $nv_Lang->getModule('menu_groups');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('menu-groups.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('SCOPE', $scope);
$tpl->assign('LANG_DATA_NAME', $language_array[NV_LANG_DATA]['name']);
$tpl->assign('HAS_CONFIG', $has_config);
$tpl->assign('GROUPS', $groups);
$tpl->assign('MODS', $array_mods);
$tpl->assign('ICON_PACKS', $fontawesome_packs);
$tpl->assign('CHECKSS', csrf_create($csrf_key));

$contents = $tpl->fetch('menu-groups.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
