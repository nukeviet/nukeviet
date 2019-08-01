<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 5/12/2010, 1:34
 */

if (!defined('NV_IS_FILE_SEOTOOLS')) {
    die('Stop!!!');
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

$cache_file = NV_ROOTDIR . '/' . NV_DATADIR . '/robots.php';

if ($nv_Request->isset_request('submit', 'post')) {
    $robots_data = $nv_Request->get_array('filename', 'post');
    $fileother = $nv_Request->get_array('fileother', 'post');
    $optionother = $nv_Request->get_array('optionother', 'post');
    $robots_other = [];
    foreach ($fileother as $key => $value) {
        if (!empty($value)) {
            $robots_other[$value] = intval($optionother[$key]);
        }
    }

    $content_config = "<?php\n\n";
    $content_config .= NV_FILEHEAD . "\n\n";
    $content_config .= "if (!defined('NV_MAINFILE'))\n    die('Stop!!!');\n\n";
    $content_config .= "\$cache = '" . serialize($robots_data) . "';\n\n";
    $content_config .= "\$cache_other = '" . serialize($robots_other) . "';\n";

    file_put_contents($cache_file, $content_config, LOCK_EX);

    $redirect = false;
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

        $rbcontents[] = 'Sitemap: ' . $global_config['site_url'] . '/index.php?' . NV_NAME_VARIABLE . '=SitemapIndex' . $global_config['rewrite_endurl'];

        $rbcontents = implode("\n", $rbcontents);

        if (!is_writable(NV_ROOTDIR . '/robots.txt')) {
            file_put_contents(NV_ROOTDIR . '/robots.txt', $rbcontents, LOCK_EX);
            $redirect = true;
        } else {
            $tpl->assign('ERROR_WRITE_FILE', nv_htmlspecialchars($rbcontents));
        }
    }

    if ($redirect) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    }
}

$robots_data = [];
$robots_other = [];

if (file_exists($cache_file)) {
    include $cache_file;
    $robots_data = unserialize($cache);
    $robots_other = unserialize($cache_other);
} else {
    $robots_data['/' . NV_DATADIR . '/'] = 0;
    $robots_data['/includes/'] = 0;
    $robots_data['/install/'] = 0;
    $robots_data['/modules/'] = 0;
    $robots_data['/robots.php'] = 0;
    $robots_data['/web.config'] = 0;
}

if ($global_config['rewrite_enable']) {
    foreach ($site_mods as $key => $value) {
        if ($value['module_file'] == 'users' or $value['module_file'] == 'statistics') {
            $_url = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $key, true);
            if (!isset($robots_other[$_url])) {
                $robots_other[$_url] = 0;
            }
        }
    }
}
$files = scandir(NV_ROOTDIR, true);
sort($files);
$contents = [];
$contents[] = 'User-agent: *';

$array_files = [];
$number = 0;

foreach ($files as $file) {
    if (!preg_match('/^\.(.*)$/', $file)) {
        if (is_dir(NV_ROOTDIR . '/' . $file)) {
            $file = '/' . $file . '/';
        } else {
            $file = '/' . $file;
        }

        $array_files[] = [
            'number' => ++$number,
            'filename' => $file,
            'type' => isset($robots_data[$file]) ? $robots_data[$file] : 1,
            'isother' => false
        ];
    }
}

foreach ($robots_other as $file => $value) {
    $array_files[] = [
        'number' => ++$number,
        'filename' => $file,
        'type' => $value,
        'isother' => true
    ];
}

$tpl->assign('FILES', $array_files);

$contents = $tpl->fetch('robots.tpl');
$page_title = $nv_Lang->getModule('robots');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
