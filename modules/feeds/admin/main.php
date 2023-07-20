<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_RSS_ADMIN')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('feeds_config');
$feed_configs_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . $module_data . '_' . NV_LANG_DATA . '.json';

if ($nv_Request->isset_request('save', 'post')) {
    $new_configs = [
        'rss_logo' => $nv_Request->get_title('rss_logo', 'post', ''),
        'atom_logo' => $nv_Request->get_title('atom_logo', 'post', ''),
        'contents' => $nv_Request->get_editor('contents', '', NV_ALLOWED_HTML_TAGS)
    ];

    if (!empty($new_configs['rss_logo'])) {
        $new_configs['rss_logo'] = substr($new_configs['rss_logo'], strlen(NV_BASE_SITEURL));

        if (!file_exists(NV_ROOTDIR . '/' . $new_configs['rss_logo'])) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'rss_logo',
                'mess' => $nv_Lang->getModule('file_not_found')
            ]);
        }

        $size = getimagesize(NV_ROOTDIR . '/' . $new_configs['rss_logo']);
        if (empty($size['mime']) or empty($size[0]) or empty($size[1])) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'rss_logo',
                'mess' => $nv_Lang->getModule('file_not_found')
            ]);
        }

        if (!preg_match('/(gif|jpe?g|png)$/i', $size['mime'])) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'rss_logo',
                'mess' => $nv_Lang->getModule('rss_logo_error1')
            ]);
        }

        if ($size[0] > 144 or $size[1] > 400) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'rss_logo',
                'mess' => $nv_Lang->getModule('rss_logo_error2')
            ]);
        }
    }

    if (!empty($new_configs['atom_logo'])) {
        $new_configs['atom_logo'] = substr($new_configs['atom_logo'], strlen(NV_BASE_SITEURL));

        if (!file_exists(NV_ROOTDIR . '/' . $new_configs['atom_logo'])) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'atom_logo',
                'mess' => $nv_Lang->getModule('file_not_found')
            ]);
        }

        $size = getimagesize(NV_ROOTDIR . '/' . $new_configs['atom_logo']);
        if (empty($size['mime']) or empty($size[0]) or empty($size[1])) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'atom_logo',
                'mess' => $nv_Lang->getModule('file_not_found')
            ]);
        }

        if (!preg_match('/(gif|jpe?g|png)$/i', $size['mime'])) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'atom_logo',
                'mess' => $nv_Lang->getModule('atom_logo_error1')
            ]);
        }

        if ($size[0] > 144 or $size[1] > 400) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'atom_logo',
                'mess' => $nv_Lang->getModule('atom_logo_error2')
            ]);
        }
    }

    !empty($new_configs['contents']) && $new_configs['contents'] = preg_replace('/[\r\n\t]+/', '', $new_configs['contents']);
    $new_configs = array_filter($new_configs);
    if (!empty($new_configs)) {
        $new_configs = json_encode($new_configs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        file_put_contents($feed_configs_file, $new_configs, LOCK_EX);
    } elseif (file_exists($feed_configs_file)) {
        nv_deletefile($feed_configs_file);
    }

    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$feed_configs = [];
if (file_exists($feed_configs_file)) {
    $feed_configs = json_decode(file_get_contents($feed_configs_file), true);
}

if (!empty($feed_configs['rss_logo'])) {
    $feed_configs['rss_logo'] = NV_BASE_SITEURL . $feed_configs['rss_logo'];
}

if (!empty($feed_configs['atom_logo'])) {
    $feed_configs['atom_logo'] = NV_BASE_SITEURL . $feed_configs['atom_logo'];
}

$feed_configs['contents'] = !empty($feed_configs['contents']) ? htmlspecialchars(nv_editor_br2nl($feed_configs['contents'])) : '';
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $feed_configs['contents'] = nv_aleditor('contents', '100%', '300px', $feed_configs['contents']);
} else {
    $feed_configs['contents'] = '<textarea style="width:100%;height:300px" name="contents">' . $feed_configs['contents'] . '</textarea>';
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('DATA', $feed_configs);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
