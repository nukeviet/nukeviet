<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * Tệp này không bắt buộc trong giao diện nếu không có hệ thống lấy từ giao diện default
 * Giữ tệp này, chỉnh sửa nó nếu muốn phát triển giao diện công cụ của quản trị viên riêng
 * Vị trí gọi: Hàm nv_admin_menu()
 * Biến sẵn có:
 * - string $php_dir tên giao diện chứa tệp php này
 * - array $dir_basenames các thư mục sẽ quét tệp tpl, để dùng trong hàm get_tpl_dir
 * - bool $enable_drag cho phép bật kéo thả hay không
 * - array $module_info
 * - array $global_config
 * - array $db_config
 * - array $admin_info
 * - array $nv_Cache
 */

global $nv_Lang, $module_name, $client_info;

$block_theme = get_tpl_dir($dir_basenames, 'default', '/system/admin_toolbar.tpl');
$xtpl = new XTemplate('admin_toolbar.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/system');
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('NV_ADMINDIR', NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
$xtpl->assign('URL_AUTHOR', NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=authors&amp;id=' . $admin_info['admin_id']);
$xtpl->assign('TEMPLATE', $block_theme);

if ($enable_drag) {
    $new_drag_block = (defined('NV_IS_DRAG_BLOCK')) ? 0 : 1;
    $lang_drag_block = ($new_drag_block) ? $nv_Lang->getGlobal('drag_block') : $nv_Lang->getGlobal('no_drag_block');

    $xtpl->assign('URL_DBLOCK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;drag_block=' . $new_drag_block . '&amp;nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']));
    $xtpl->assign('LANG_DBLOCK', $lang_drag_block);
    $xtpl->parse('main.is_spadmin');
}

if (defined('NV_IS_MODADMIN') and !empty($module_info['admin_file'])) {
    $xtpl->assign('URL_MODULE', NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
    $xtpl->assign('MODULENAME', $module_info['custom_title']);
    $xtpl->parse('main.is_modadmin');
}

$xtpl->parse('main');
return $xtpl->text('main');
