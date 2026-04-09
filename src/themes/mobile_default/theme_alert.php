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
 * Giữ tệp này, chỉnh sửa nó nếu muốn phát triển giao diện alert riêng (success, info, warning, danger)
 * Vị trí gọi: Hàm nv_theme_alert()
 * Biến sẵn có:
 * - array $dir_basenames $dir_basenames các thư mục sẽ quét tệp tpl, để dùng trong hàm get_tpl_dir
 * - string $php_dir tên giao diện chứa tệp php này
 * - $global_config, $module_info, $page_title
 * - Và các biến trong hàm, truyền vào hàm nv_theme_alert()
 */

$template = get_tpl_dir($dir_basenames, 'default', '/system/alert.tpl');
$tpl_path = NV_ROOTDIR . '/themes/' . $template . '/system';
$xtpl = new XTemplate('alert.tpl', $tpl_path);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('LANG_BACK', $lang_back);
$xtpl->assign('CONTENT', $message_content);

if ($type == 'success') {
    $xtpl->parse('main.success');
} elseif ($type == 'warning') {
    $xtpl->parse('main.warning');
} elseif ($type == 'danger') {
    $xtpl->parse('main.danger');
} else {
    $xtpl->parse('main.info');
}

if (!empty($message_title)) {
    $page_title = $message_title;
    $xtpl->assign('TITLE', $message_title);
    $xtpl->parse('main.title');
} elseif (!empty($module_info['site_title'])) {
    // For admin if use in admin area
    $page_title = $module_info['site_title'];
} else {
    $page_title = $module_info['custom_title'];
}

if (!empty($url_back)) {
    $xtpl->assign('TIME', $time_back);
    $xtpl->assign('URL', $url_back);
    $xtpl->parse('main.url_back');
    $xtpl->parse('main.loading_icon');

    if (!empty($lang_back)) {
        $xtpl->parse('main.url_back_button');
    }
}

$xtpl->parse('main');
return $xtpl->text('main');
