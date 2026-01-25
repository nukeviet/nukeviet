<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
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
        if (!empty($_val)) {
            if ($type == 'dir') {
                $test = is_dir(NV_ROOTDIR . '/' . $_val);
                $_val == 'vendor' && $test = true;
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

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);

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

// Lấy nội dung file cấu hình
if ($nv_Request->isset_request('getSconfigContents', 'post')) {
    if ($nv_Request->get_title('checkss', 'post', '') !== $checkss) {
        nv_htmlOutput('Error session!!!');
    }

    $contents = '';
    if (!empty($sconfig_file) and file_exists(NV_ROOTDIR . '/' . $sconfig_file)) {
        $contents = file_get_contents(NV_ROOTDIR . '/' . $sconfig_file);
    }

    nv_htmlOutput($contents);
}

// Lấy nội dung tệp cấu hình mặc định theo thiết lập
if ($nv_Request->isset_request('getSconfigBySettings', 'post')) {
    if ($nv_Request->get_title('checkss', 'post', '') !== $checkss) {
        nv_htmlOutput('Error session!!!');
    }

    $supporter = $nv_Request->get_title('rewrite_supporter', 'post', 'rewrite_mode_apache');
    $Sconfig = new NukeViet\Core\Sconfig($global_config);

    $contents = '';
    if ($supporter == 'rewrite_mode_apache') {
        $contents .= $Sconfig->setApacheContents();
    } elseif ($supporter == 'rewrite_mode_iis') {
        $contents .= $Sconfig->setIisContents();
    } elseif ($supporter == 'nginx') {
        $contents .= $Sconfig->setNginxContents();
    }

    nv_htmlOutput($contents);
}

$page_title = $nv_Lang->getModule('ssettings');
$server_config_file = NV_ROOTDIR . '/' . NV_DATADIR . '/server_config.json';
$server_configs = file_get_contents($server_config_file);
$server_configs = json_decode($server_configs, true);
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

    $posts = json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    file_put_contents($server_config_file, $posts, LOCK_EX);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success')
    ]);
}

$server_configs['file_deny_access_format'] = array_to_string_format($server_configs['deny_access']['file']);
$server_configs['dir_deny_access_format'] = array_to_string_format($server_configs['deny_access']['dir']);
$server_configs['include_dirs_deny_access_format'] = array_to_string_format($server_configs['deny_access']['include_dirs']);
$server_configs['include_exec_files_deny_access_format'] = array_to_string_format($server_configs['deny_access']['include_exec_files']);
$server_configs['exec_files_format'] = !empty($server_configs['exec_files']) ? implode(' ', $server_configs['exec_files']) : '';
$server_configs['site_mimetypes_format'] = mime_type_format($server_configs['site_mimetypes']);
$server_configs['compress_file_exts_format'] = !empty($server_configs['compress_file_exts']) ? implode(' ', $server_configs['compress_file_exts']) : '';
$server_configs['charset_types_format'] = !empty($server_configs['charset_types']) ? implode(' ', $server_configs['charset_types']) : '';
$server_configs['cors_origins_list'] = !empty($server_configs['cors_origins_any']) ? '' : array_to_string_format($server_configs['cors_origins'], ' ');
$server_configs['compress_types_format'] = compress_types_format($server_configs['compress_types']);
$server_configs['js_css_mime_types'] = !empty($server_configs['js_css_files']['mime_types']) ? implode(' ', $server_configs['js_css_files']['mime_types']) : '';
$server_configs['image_mime_types'] = !empty($server_configs['image_files']['mime_types']) ? implode(' ', $server_configs['image_files']['mime_types']) : '';
$server_configs['font_mime_types'] = !empty($server_configs['font_files']['mime_types']) ? implode(' ', $server_configs['font_files']['mime_types']) : '';

$info = [
    'server_software' => !empty($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '',
    'php_sapi' => defined('PHP_SAPI') ? PHP_SAPI : '',
    'rewrite_support' => !empty($sys_info['supports_rewrite']) ? $sys_info['supports_rewrite'] : '',
    'sconfig_file' => $sconfig_file
];

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('ssettings.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('DATA', $server_configs);
$tpl->assign('CHECKSS', $checkss);
$tpl->assign('INFO', $info);
$tpl->assign('SYS_INFO', $sys_info);
$tpl->assign('HIGHLIGHT_LANG', $highlight_lang);

$deny_access_codes = [403, 404, 301];
$tpl->assign('DENY_ACCESS_CODES', $deny_access_codes);

$errors = [400, 403, 404, 405, 408, 500, 502, 503, 504];
$tpl->assign('ERRORS', $errors);

$contents = $tpl->fetch('ssettings.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
