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

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $block_theme . '/system');
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_INFO', $module_info);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('ENABLE_DRAG', $enable_drag);
$tpl->assign('URL_AUTHOR', NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=authors&amp;id=' . $admin_info['admin_id']);

if ($enable_drag) {
    $tpl->assign('URL_DBLOCK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;drag_block=' . intval(!defined('NV_IS_DRAG_BLOCK')) . '&amp;nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']));
}

return $tpl->fetch('admin_toolbar.tpl');
