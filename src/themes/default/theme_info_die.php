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
 * Giữ tệp này, chỉnh sửa nó nếu muốn phát triển giao diện thông báo lỗi riêng
 * Vị trí gọi: Hàm nv_info_die()
 * Biến sẵn có:
 * - string $php_dir tên giao diện chứa tệp php này
 * - $nv_Lang, $global_config
 * - Và các biến trong hàm nv_info_die()
 */

$template = get_tpl_dir($dir_basenames, 'default', '/system/info_die.tpl');
$tpl_path = NV_ROOTDIR . '/themes/' . $template . '/system';

$xtpl = new XTemplate('info_die.tpl', $tpl_path);
$xtpl->assign('SITE_CHARSET', $global_config['site_charset']);
$xtpl->assign('PAGE_TITLE', $page_title);
$xtpl->assign('HOME_LINK', $global_config['site_url']);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('TEMPLATE', $template);
$xtpl->assign('SITE_NAME', empty($global_config['site_name']) ? '' : $global_config['site_name']);

$site_favicon = NV_BASE_SITEURL . 'favicon.ico';
if (!empty($global_config['site_favicon']) and file_exists(NV_ROOTDIR . '/' . $global_config['site_favicon'])) {
    $site_favicon = NV_BASE_SITEURL . $global_config['site_favicon'];
}
$xtpl->assign('SITE_FAVICON', $site_favicon);

$xtpl->assign('LOGO', NV_BASE_SITEURL . $global_config['site_logo']);
$xtpl->assign('INFO_TITLE', $info_title);
$xtpl->assign('INFO_CONTENT', $info_content);

if (defined('NV_IS_ADMIN') and !empty($admin_link)) {
    $xtpl->assign('ADMIN_LINK', $admin_link);
    $xtpl->assign('GO_ADMINPAGE', empty($admin_title) ? $nv_Lang->getGlobal('admin_page') : $admin_title);
    $xtpl->parse('main.adminlink');
}
if (!empty($site_link)) {
    $xtpl->assign('SITE_LINK', $site_link);
    $xtpl->assign('GO_SITEPAGE', empty($site_title) ? $nv_Lang->getGlobal('go_homepage') : $site_title);
    $xtpl->parse('main.sitelink');
}

if ($error_code >= 400) {
    $xtpl->parse('main.is_error');
} else {
    $xtpl->parse('main.is_info');
}

$xtpl->parse('main');
return $xtpl->text('main');
