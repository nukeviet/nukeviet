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

$xtpl = new XTemplate('robots.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);

$cache_file = NV_ROOTDIR . '/' . NV_DATADIR . '/robots.php';

if ($nv_Request->isset_request('submit', 'post')) {
    $robots_data = $nv_Request->get_array('filename', 'post');
    $fileother = $nv_Request->get_array('fileother', 'post');
    $optionother = $nv_Request->get_array('optionother', 'post');
    $robots_other = array();
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
        $rbcontents = array();
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

        if (is_writable(NV_ROOTDIR . '/robots.txt')) {
            file_put_contents(NV_ROOTDIR . '/robots.txt', $rbcontents, LOCK_EX);
            $redirect = true;
        } else {
            $xtpl->assign('TITLE', $lang_module['robots_error_writable']);
            $xtpl->assign('CONTENT', str_replace(array(
                "\n",
                "\t"
            ), array(
                '<br />',
                '&nbsp;&nbsp;&nbsp;&nbsp;'
            ), nv_htmlspecialchars($rbcontents)));
            $xtpl->parse('main.nowrite');
        }
    }

    if ($redirect) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    }
}

$robots_data = array();
$robots_other = array();

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
$contents = array();
$contents[] = 'User-agent: *';
$number = 0;
foreach ($files as $file) {
    if (!preg_match('/^\.(.*)$/', $file)) {
        if (is_dir(NV_ROOTDIR . '/' . $file)) {
            $file = '/' . $file . '/';
        } else {
            $file = '/' . $file;
        }

        $data = array(
            'number' => ++$number,
            'filename' => $file
        );

        $type = isset($robots_data[$file]) ? $robots_data[$file] : 1;

        for ($i = 0; $i <= 2; $i++) {
            $option = array(
                'value' => $i,
                'title' => $lang_module['robots_type_' . $i],
                'selected' => ($type == $i) ? ' selected="selected"' : ''
            );

            $xtpl->assign('OPTION', $option);
            $xtpl->parse('main.loop.option');
        }

        $xtpl->assign('DATA', $data);
        $xtpl->parse('main.loop');
    }
}
foreach ($robots_other as $file => $value) {
    $data = array(
        'number' => ++$number,
        'filename' => $file
    );
    $xtpl->assign('DATA', $data);

    for ($i = 0; $i <= 2; $i++) {
        $option = array(
            'value' => $i,
            'title' => $lang_module['robots_type_' . $i],
            'selected' => ($value == $i) ? ' selected="selected"' : ''
        );

        $xtpl->assign('OPTION', $option);
        $xtpl->parse('main.other.option');
    }
    $xtpl->parse('main.other');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['robots'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
