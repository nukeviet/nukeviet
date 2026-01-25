<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SEOTOOLS')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('robots');

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
$cache_file = NV_ROOTDIR . '/' . NV_DATADIR . '/robots.php';

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'array_merge', 'array_merge');
$tpl->setTemplateDir(get_module_tpl_dir('robots.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

// Các tệp cố định
$files = scandir(NV_ROOTDIR, true);
sort($files);
$static_files = [];
foreach ($files as $file) {
    if (!preg_match('/^\.(.*)$/', $file)) {
        if (is_dir(NV_ROOTDIR . '/' . $file)) {
            $file = '/' . $file . '/';
        } else {
            $file = '/' . $file;
        }
        $static_files[] = $file;
    }
}

if ($checkss == $nv_Request->get_string('checkss', 'post', '')) {
    $_robots_data = $nv_Request->get_array('filename', 'post');
    $_fileother = $nv_Request->get_array('fileother', 'post');
    $_optionother = $nv_Request->get_array('optionother', 'post');

    // Xử lý dữ liệu
    $robots_other = $robots_data = [];
    foreach ($_robots_data as $key => $value) {
        if (in_array($key, $static_files, true)) {
            $value = intval($value);
            if (!in_array($value, [0, 1, 2],  true)) {
                $value = 1;
            }
            $robots_data[$key] = $value;
        }
    }

    foreach ($_fileother as $key => $value) {
        $value = nv_substr(trim(strip_tags($value)), 0, 255);
        if (strpos($value, '/') === 0 and !in_array($value, $static_files, true)) {
            $cfg_value = intval($_optionother[$key] ?? 1);
            if (!in_array($cfg_value, [0, 1, 2],  true)) {
                $cfg_value = 1;
            }
            $robots_other[$value] = $cfg_value;
        }
    }

    nv_update_robots([$robots_data, $robots_other], true);

    // Không hỗ trợ rewrite thì ghi trực tiếp vào file txt. Không thì dùng rewrite để đọc trong php
    if (!$global_config['check_rewrite_file'] or !$global_config['rewrite_enable']) {
        $rbcontents = [];
        $rbcontents[] = 'User-agent: *';

        foreach ($robots_data as $key => $value) {
            if ($value == 0) {
                $rbcontents[] = 'Disallow: ' . $key;
            } elseif ($value == 2) {
                $rbcontents[] = 'Allow: ' . $key;
            }
        }
        foreach ($robots_other as $key => $value) {
            if ($value == 0) {
                $rbcontents[] = 'Disallow: ' . $key;
            } elseif ($value == 2) {
                $rbcontents[] = 'Allow: ' . $key;
            }
        }

        $rbcontents[] = 'Sitemap: ' . $global_config['site_url'] . '/index.php?' . NV_NAME_VARIABLE . '=SitemapIndex' . $global_config['rewrite_endurl'];

        $rbcontents = implode("\n", $rbcontents);

        if (is_writable(NV_ROOTDIR . '/robots.txt')) {
            file_put_contents(NV_ROOTDIR . '/robots.txt', $rbcontents, LOCK_EX);
        } else {
            $tpl->assign('CONTENT', nv_htmlspecialchars($rbcontents));
            $contents = $tpl->fetch('robots-error.tpl');

            include NV_ROOTDIR . '/includes/header.php';
            echo nv_admin_theme($contents);
            include NV_ROOTDIR . '/includes/footer.php';
        }
    }
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

[$robots_data, $robots_other] = nv_update_robots(false);

$tpl->assign('CHECKSS', $checkss);
$tpl->assign('ROBOTS_DATA', $robots_data);
$tpl->assign('STATIC_FILES', $static_files);
$tpl->assign('ROBOTS_OTHER', $robots_other);

$contents = $tpl->fetch('robots.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
