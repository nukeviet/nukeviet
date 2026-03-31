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
 * Giữ tệp này, chỉnh sửa nó nếu muốn phát triển giao diện alert riêng (success, info, warning, danger)
 * Vị trí gọi: Hàm nv_theme_alert()
 * Biến sẵn có:
 * - array $dir_basenames $dir_basenames các thư mục sẽ quét tệp tpl, để dùng trong hàm get_tpl_dir
 * - string $php_dir tên giao diện chứa tệp php này
 * - $global_config, $module_info, $page_title
 * - Và các biến trong hàm, truyền vào hàm nv_theme_alert()
 */

global $nv_Lang, $page_title;

$template = get_tpl_dir($dir_basenames, 'default', '/system/alert.tpl');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $template . '/system');
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('TEMPLATE', $template);

$tpl->assign('URL_BACK', $url_back);
$tpl->assign('TIME_BACK', $time_back);
$tpl->assign('TYPE', $type);
$tpl->assign('CONTENT', $message_content);
$tpl->assign('TITLE', $message_title);
$tpl->assign('LANG_BACK', $lang_back);

if (!empty($message_title)) {
    $page_title = $message_title;
} elseif (!empty($module_info['site_title'])) {
    // For admin if use in admin area
    $page_title = $module_info['site_title'];
} else {
    $page_title = $module_info['custom_title'];
}

return $tpl->fetch('alert.tpl');
