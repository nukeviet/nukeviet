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
 * Giữ tệp này, chỉnh sửa nó nếu muốn phát triển giao diện thông báo lỗi riêng
 * Vị trí gọi: Hàm nv_info_die()
 * Biến sẵn có:
 * - string $php_dir tên giao diện chứa tệp php này
 * - $nv_Lang, $global_config
 * - Và các biến trong hàm nv_info_die()
 */

$template = get_tpl_dir($dir_basenames, 'default', '/system/info_die.tpl');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $template . '/system');
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('PAGE_TITLE', $page_title);
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('INFO_TITLE', $info_title);
$tpl->assign('INFO_CONTENT', $info_content);
$tpl->assign('ADMIN_LINK', $admin_link);
$tpl->assign('SITE_LINK', $site_link);
$tpl->assign('SITE_TITLE', $site_title);
$tpl->assign('ADMIN_TITLE', $admin_title);
$tpl->assign('ERROR_CODE', $error_code);

return $tpl->fetch('info_die.tpl');
