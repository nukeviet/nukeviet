<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SETTINGS')) {
    exit('Stop!!!');
}

function array_to_string_format($contents, $st = "\n")
{
    if (!empty($contents)) {
        $contents = implode($st, $contents);
        $contents = str_replace(['{', '}', '<', '>', '[', ']'], ['&lbrace;', '&rbrace;', '&lt;', '&gt;', '&lbrack;', '&rbrack;'], $contents);
    } else {
        $contents = '';
    }

    return $contents;
}

function mime_type_format($files)
{
    if (empty($files)) {
        return '';
    }

    $return = [];
    foreach ($files as $mime => $exts) {
        $return[] = $mime . ': ' . implode(' ', $exts);
    }

    return implode("\n", $return);
}

function compress_types_format($compress_types)
{
    if (empty($compress_types)) {
        return '';
    }

    $return = [];
    foreach ($compress_types as $type => $path) {
        $return[] = $type . ': ' . $path;
    }

    return implode("\n", $return);
}

function test_regex($regex)
{
    $regex = '~' . $regex . '~';
    preg_match($regex, '');
    $constants = get_defined_constants(true)['pcre'];
    foreach ($constants as $key => $value) {
        if (!str_ends_with($key, '_ERROR')) {
            unset($constants[$key]);
        }
    }

    return array_flip($constants)[preg_last_error()];
}

function set_files($files, $type)
{
    if (empty($files)) {
        return [];
    }

    $files = explode("\n", $files);
    $files = array_map(function ($val) use ($type) {
        $val = trim($val);
        unset($matches);
        if (preg_match('/^\[(.*)\]$/', $val, $matches)) {
            $matches[1] = preg_replace_callback('/\{([A-Z0-9\_]+)\}/', function ($v) {
                if (defined($v[1])) {
                    return preg_quote(constant($v[1]));
                }

                return $v[0];
            }, $matches[1]);
            if (test_regex($matches[1]) == 'PREG_NO_ERROR') {
                return $val;
            }

            return '';
        }
        $_val = preg_replace_callback('/\{([A-Z0-9\_]+)\}/', function ($v) {
            if (defined($v[1])) {
                return constant($v[1]);
            }

            return '';
        }, $val);
        if (!empty($_val) and file_exists(NV_ROOTDIR . '/' . $_val)) {
            if ($type == 'dir') {
                $test = is_dir(NV_ROOTDIR . '/' . $_val);
            } else {
                $test = file_exists(NV_ROOTDIR . '/' . $_val);
            }

            return $test ? $val : '';
        }

        return '';
    }, $files);
    $files = array_filter($files);
    $files = array_unique($files);
    asort($files);

    return array_values($files);
}

function set_exts($exts)
{
    if (empty($exts)) {
        return [];
    }

    $exts = explode(' ', $exts);
    $exts = array_map(function ($val) {
        unset($matches);
        if (preg_match('/^\[(.+)\]$/', $val, $matches)) {
            if (test_regex($matches[1]) == 'PREG_NO_ERROR') {
                return $val;
            }

            return '';
        }
        if (preg_match('/^[a-zA-Z0-9\.]+$/', $val)) {
            return $val;
        }

        return '';
    }, $exts);
    $exts = array_filter($exts);
    $exts = array_unique($exts);
    asort($exts);

    return array_values($exts);
}

function set_mime_types($mime_types)
{
    if (empty($mime_types)) {
        return [];
    }

    $return = [];
    $mime_types = explode("\n", $mime_types);
    foreach ($mime_types as $mime_type) {
        $mime_type = trim($mime_type);
        unset($matches);
        if (preg_match('/^([a-z0-9]+\/[a-z0-9\+\-\_\.]+)\s*\:\s*([a-z0-9\. ]+)$/i', $mime_type, $matches)) {
            $exts = explode(' ', $matches[2]);
            $exts = array_filter($exts);
            $exts = array_unique($exts);
            $return[$matches[1]] = array_values($exts);
        }
    }

    return $return;
}

function set_mimes($compress_file_exts, $site_mimetypes)
{
    if (empty($site_mimetypes) or empty($compress_file_exts)) {
        return [];
    }

    $compress_file_exts = explode(' ', $compress_file_exts);
    $return = [];
    foreach ($compress_file_exts as $mime) {
        if (!empty($site_mimetypes[$mime])) {
            $return[] = $mime;
        }
    }
    if (!empty($return)) {
        sort($return);
    }

    return $return;
}

function set_compress_types($compress_types)
{
    if (empty($compress_types)) {
        return [];
    }

    $return = [];
    $compress_types = explode("\n", $compress_types);
    foreach ($compress_types as $compress_type) {
        $compress_type = trim($compress_type);
        unset($matches);
        if (preg_match('/^([a-z0-9]+)\s*\:\s*(.+)$/i', $compress_type, $matches)) {
            $return[$matches[1]] = trim($matches[2]);
        }
    }

    return $return;
}

function set_expires($expires)
{
    if (preg_match('/^([0-9]+)(y|M|w|d|h|m|s)$/', $expires)) {
        return $expires;
    }

    return '';
}

function set_access_control_allow_origin($any_origin, $origins)
{
    if ($any_origin) {
        return ['*'];
    }

    if (empty($origins)) {
        return [];
    }

    $origins = explode(' ', $origins);
    $origins = array_map('trim', $origins);

    if (in_array('*', $origins, true)) {
        return ['*'];
    }

    $origins = array_map(function ($domain) {
        $domain = preg_replace('/^https?\:\/\//', '', $domain);
        $domain = preg_replace('/^www\./', '', $domain);

        return nv_check_domain($domain);
    }, $origins);
    $origins = array_filter($origins);
    $origins = array_unique($origins);

    return array_values($origins);
}

$sconfig_file = '';
$highlight_lang = '';
if ($sys_info['supports_rewrite'] == 'rewrite_mode_apache') {
    $sconfig_file = '.htaccess';
    $highlight_lang = 'apache';
} elseif ($sys_info['supports_rewrite'] == 'rewrite_mode_iis') {
    $sconfig_file = 'web.config';
    $highlight_lang = 'xml';
} elseif ($sys_info['supports_rewrite'] == 'nginx') {
    $highlight_lang = 'nginx';
}

// Lay noi dung File cau hinh
if ($nv_Request->isset_request('getSconfigContents', 'post')) {
    $contents = '';
    if (!empty($sconfig_file) and file_exists(NV_ROOTDIR . '/' . $sconfig_file)) {
        $contents = file_get_contents(NV_ROOTDIR . '/' . $sconfig_file);
    }

    nv_htmlOutput($contents);
}

// Lay noi dung cau hinh mac dinh theo thiet lap
if ($nv_Request->isset_request('getSconfigBySettings', 'post')) {
    $Sconfig = new NukeViet\Core\Sconfig($global_config);

    $contents = '';
    if ($sys_info['supports_rewrite'] == 'rewrite_mode_apache') {
        $contents .= $Sconfig->setApacheContents();
    } elseif ($sys_info['supports_rewrite'] == 'rewrite_mode_iis') {
        $contents .= $Sconfig->setIisContents();
    } elseif ($sys_info['supports_rewrite'] == 'nginx') {
        $contents .= $Sconfig->setNginxContents();
    }

    nv_htmlOutput($contents);
}

// Chuyen phan cau hinh chung ve mac dinh theo thiet lap
if ($nv_Request->isset_request('changeConfigs', 'post')) {
    $confirm = (bool) $nv_Request->get_int('confirm', 'post', 0);

    $contents = '';
    if ($confirm) {
        if ($sys_info['supports_rewrite'] == 'rewrite_mode_apache') {
            $save_config = nv_server_config_change();
            $contents = $save_config[0] !== true ? $lang_module['changes_not_saved'] : $lang_module['changes_saved'];
        }
    } else {
        $Sconfig = new NukeViet\Core\Sconfig($global_config);
        if ($sys_info['supports_rewrite'] == 'rewrite_mode_apache') {
            $contents = $Sconfig->setApacheConfigs();
        }
    }
    nv_htmlOutput($contents);
}

// Chuyen phan Rewrite ve mac dinh theo thiet lap
if ($nv_Request->isset_request('changeRewrite', 'post')) {
    $confirm = (bool) $nv_Request->get_int('confirm', 'post', 0);

    $contents = '';
    if ($confirm) {
        if ($sys_info['supports_rewrite'] == 'rewrite_mode_apache' or $sys_info['supports_rewrite'] == 'rewrite_mode_iis') {
            $save = nv_rewrite_change();
            $contents = $save[0] !== true ? $lang_module['changes_not_saved'] : $lang_module['changes_saved'];
        }
    } else {
        $Sconfig = new NukeViet\Core\Sconfig($global_config);
        if ($sys_info['supports_rewrite'] == 'rewrite_mode_apache') {
            $contents = $Sconfig->setApacheRewrite();
        } elseif ($sys_info['supports_rewrite'] == 'rewrite_mode_iis') {
            $cts = $Sconfig->setIisRewrite();

            $doc = new \DOMDocument();
            $doc->preserveWhiteSpace = false;
            $doc->loadXML('<configuration><system.webServer><rewrite><rules/></rewrite></system.webServer></configuration>');

            $xpath = new \DOMXPath($doc);
            $xmlnodes = $xpath->query('/configuration/system.webServer/rewrite/rules');
            $rules_node = $xmlnodes->item(0);
            $config_fragment = $doc->createDocumentFragment();
            $config_fragment->appendXML($cts);
            $rules_node->appendChild($config_fragment);
            $doc->formatOutput = true;
            $cts = $doc->saveXML();
            unset($matches);
            preg_match("/[\s]*\<\!\-\-\s*NUKEVIET\_REWRITE\_START\s*\-\-\>(.*)\<\!\-\-\s*NUKEVIET\_REWRITE\_END\s*\-\-\>/si", $cts, $matches);
            $contents = $matches[0];
        }
    }
    nv_htmlOutput($contents);
}

// Chuyen tat ca ve mac dinh theo thiet lap
if ($nv_Request->isset_request('changeAll', 'post')) {
    $confirm = (bool) $nv_Request->get_int('confirm', 'post', 0);

    $Sconfig = new NukeViet\Core\Sconfig($global_config);

    $contents = '';
    if ($sys_info['supports_rewrite'] == 'rewrite_mode_apache') {
        $contents = $Sconfig->setApacheContents();
    } elseif ($sys_info['supports_rewrite'] == 'rewrite_mode_iis') {
        $contents = $Sconfig->setIisContents();
    }

    if ($confirm) {
        if (!empty($contents)) {
            $md5_old_file = md5_file(NV_ROOTDIR . '/' . $sconfig_file);
            $contents = (file_put_contents(NV_ROOTDIR . '/' . $sconfig_file, $contents, LOCK_EX) !== false) ? $lang_module['changes_saved'] : $lang_module['changes_not_saved'];
            $md5_new_file = md5_file(NV_ROOTDIR . '/' . $sconfig_file);
            if (strcmp($md5_new_file, $md5_old_file) !== 0) {
                nv_insert_notification($module_name, 'server_config_file_changed', ['file' => $sconfig_file], 0, 0, 0, 1, 1);
            }
        }
    }

    nv_htmlOutput($contents);
}

$page_title = $lang_module['ssettings'];
$server_config_file = NV_ROOTDIR . '/' . NV_DATADIR . '/server_config.json';
$server_configs = file_get_contents($server_config_file);
$server_configs = json_decode($server_configs, true);
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
$csrf = $nv_Request->get_string('_csrf', 'post', '');

if ($nv_Request->isset_request('save', 'post') and hash_equals($checkss, $csrf)) {
    $posts = [];
    $posts['site_mimetypes'] = set_mime_types($nv_Request->get_textarea('site_mimetypes', '', '', false, false));
    $posts['compress_file_exts'] = set_mimes($nv_Request->get_string('compress_file_exts', 'post', '', true, false), $posts['site_mimetypes']);
    $posts['charset_types'] = set_mimes($nv_Request->get_string('charset_types', 'post', '', true, false), $posts['site_mimetypes']);
    $posts['cors_origins'] = set_access_control_allow_origin($nv_Request->get_bool('any_origin', 'post', false), $nv_Request->get_textarea('cors_origins', '', '', false, false));
    $posts['compress_types'] = set_compress_types($nv_Request->get_textarea('compress_types', '', '', false, false));
    $posts['remove_x_powered_by'] = (bool) $nv_Request->get_int('remove_x_powered_by', 'post', 0);
    $posts['disable_server_signature'] = (bool) $nv_Request->get_int('disable_server_signature', 'post', 0);
    $posts['remove_etag'] = (bool) $nv_Request->get_int('remove_etag', 'post', 0);
    $posts['not_cache_and_snippet'] = (bool) $nv_Request->get_int('not_cache_and_snippet', 'post', 0);
    $posts['strict_transport_security'] = $nv_Request->get_title('strict_transport_security', 'post', '');
    $posts['x_content_type_options'] = $nv_Request->get_title('x_content_type_options', 'post', '');
    $posts['x_frame_options'] = $nv_Request->get_title('x_frame_options', 'post', '');
    $posts['x_xss_protection'] = $nv_Request->get_title('x_xss_protection', 'post', '');
    $posts['referrer_policy'] = $nv_Request->get_title('referrer_policy', 'post', '');
    $posts['deny_access'] = [
        'file' => set_files($nv_Request->get_textarea('file_deny_access', '', '', false, false), 'file'),
        'dir' => set_files($nv_Request->get_textarea('dir_deny_access', '', '', false, false), 'dir'),
        'include_dirs' => set_files($nv_Request->get_textarea('include_dirs_deny_access', '', '', false, false), 'dir'),
        'include_exec_files' => set_files($nv_Request->get_textarea('include_exec_files_deny_access', '', '', false, false), 'dir')
    ];
    $posts['exec_files'] = set_exts($nv_Request->get_string('exec_files', 'post', '', true, false));
    $posts['deny_access_code'] = $nv_Request->get_int('deny_access_code', 'post', 404);

    $error_document = $nv_Request->get_typed_array('error_document', 'post', 'title', []);
    $posts['error_document'] = [];
    if (!empty($error_document)) {
        foreach ($error_document as $code => $url) {
            if (nv_is_url(NV_BASE_SITEURL . $url, true)) {
                $posts['error_document'][$code] = $url;
            }
        }
    }

    $posts['js_css_files'] = [
        'mime_types' => set_mimes($nv_Request->get_string('js_css_mime_types', 'post', '', true, false), $posts['site_mimetypes']),
        'cache_control' => $nv_Request->get_title('js_css_cache_control', 'post', ''),
        'expires' => set_expires($nv_Request->get_title('js_css_expires', 'post', ''))
    ];

    $posts['image_files'] = [
        'mime_types' => set_mimes($nv_Request->get_string('image_mime_types', 'post', '', true, false), $posts['site_mimetypes']),
        'cache_control' => $nv_Request->get_title('image_cache_control', 'post', ''),
        'expires' => set_expires($nv_Request->get_title('image_expires', 'post', '')),
        'prevent_image_hot_linking' => (bool) $nv_Request->get_int('prevent_image_hot_linking', 'post', 0)
    ];

    $posts['font_files'] = [
        'mime_types' => set_mimes($nv_Request->get_string('font_mime_types', 'post', '', true, false), $posts['site_mimetypes']),
        'cache_control' => $nv_Request->get_title('font_cache_control', 'post', ''),
        'expires' => set_expires($nv_Request->get_title('font_expires', 'post', ''))
    ];

    $posts = json_encode($posts);
    $posts = json_pretty_print($posts);

    $old_md5_file = md5_file($server_config_file);
    file_put_contents($server_config_file, $posts, LOCK_EX);
    $new_md5_file = md5_file($server_config_file);
    if (strcmp($new_md5_file, $old_md5_file) !== 0) {
        nv_server_config_change();
    }
    exit('OK');
}

$server_configs['file_deny_access_format'] = array_to_string_format($server_configs['deny_access']['file']);
$server_configs['dir_deny_access_format'] = array_to_string_format($server_configs['deny_access']['dir']);
$server_configs['include_dirs_deny_access_format'] = array_to_string_format($server_configs['deny_access']['include_dirs']);
$server_configs['include_exec_files_deny_access_format'] = array_to_string_format($server_configs['deny_access']['include_exec_files']);
$server_configs['exec_files_format'] = !empty($server_configs['exec_files']) ? implode(' ', $server_configs['exec_files']) : '';
$server_configs['site_mimetypes_format'] = mime_type_format($server_configs['site_mimetypes']);
$server_configs['compress_file_exts_format'] = !empty($server_configs['compress_file_exts']) ? implode(' ', $server_configs['compress_file_exts']) : '';
$server_configs['charset_types_format'] = !empty($server_configs['charset_types']) ? implode(' ', $server_configs['charset_types']) : '';
$server_configs['cors_origins_any'] = (!empty($server_configs['cors_origins']) and in_array('*', $server_configs['cors_origins'], true)) ? ' checked="checked"' : '';
$server_configs['cors_origins_list'] = !empty($server_configs['cors_origins_any']) ? '' : array_to_string_format($server_configs['cors_origins'], ' ');
$server_configs['compress_types_format'] = compress_types_format($server_configs['compress_types']);
$server_configs['remove_x_powered_by_checked'] = $server_configs['remove_x_powered_by'] ? 'checked' : '';
$server_configs['disable_server_signature_checked'] = $server_configs['disable_server_signature'] ? 'checked' : '';
$server_configs['remove_etag_checked'] = $server_configs['remove_etag'] ? 'checked' : '';
$server_configs['not_cache_and_snippet_checked'] = $server_configs['not_cache_and_snippet'] ? 'checked' : '';
$server_configs['prevent_image_hot_linking_checked'] = $server_configs['image_files']['prevent_image_hot_linking'] ? 'checked' : '';
$server_configs['js_css_mime_types'] = !empty($server_configs['js_css_files']['mime_types']) ? implode(' ', $server_configs['js_css_files']['mime_types']) : '';
$server_configs['image_mime_types'] = !empty($server_configs['image_files']['mime_types']) ? implode(' ', $server_configs['image_files']['mime_types']) : '';
$server_configs['font_mime_types'] = !empty($server_configs['font_files']['mime_types']) ? implode(' ', $server_configs['font_files']['mime_types']) : '';

$info = [
    'server_software' => !empty($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '',
    'php_sapi' => defined('PHP_SAPI') ? PHP_SAPI : '',
    'rewrite_support' => !empty($sys_info['supports_rewrite']) ? $sys_info['supports_rewrite'] : '',
    'sconfig_file' => $sconfig_file
];

$xtpl = new XTemplate('ssettings.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

$xtpl->assign('LANG', $lang_module);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('DATA', $server_configs);
$xtpl->assign('CHECKSS', $checkss);
$xtpl->assign('INFO', $info);
$xtpl->assign('HIGHLIGHT_LANG', $highlight_lang);

if (!empty($sys_info['supports_rewrite'])) {
    if (!empty($info['sconfig_file'])) {
        $xtpl->parse('main.sconfig_file');
    }

    if ($sys_info['supports_rewrite'] == 'rewrite_mode_apache') {
        $xtpl->parse('main.tools.change_configs');
    }

    if ($sys_info['supports_rewrite'] == 'rewrite_mode_apache' or $sys_info['supports_rewrite'] == 'rewrite_mode_iis') {
        $xtpl->parse('main.tools.change_rewrite');
        $xtpl->parse('main.tools.change_all');
    }

    $xtpl->parse('main.tools');
    $xtpl->parse('main.rewrite_support');
}

if ($sys_info['supports_rewrite'] != 'rewrite_mode_iis') {
    $xtpl->parse('main.if_not_iis');
}

$deny_access_codes = [403, 404, 301];
foreach ($deny_access_codes as $code) {
    $xtpl->assign('CODE', [
        'num' => $code,
        'name' => $lang_module['deny_access_code_' . $code],
        'sel' => ($code == $server_configs['deny_access_code']) ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.deny_access_code');
}

$errors = [400, 403, 404, 405, 408, 500, 502, 503, 504];
foreach ($errors as $code) {
    $xtpl->assign('EDOC', [
        'code' => $code,
        'title' => $lang_module['error_pages_' . $code],
        'val' => $server_configs['error_document'][$code]
    ]);
    $xtpl->parse('main.error_document');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
