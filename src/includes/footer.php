<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if ((!defined('NV_SYSTEM') and !defined('NV_ADMIN')) or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

unset($lang_module, $language_array, $nv_parse_ini_timezone, $countries, $module_info, $site_mods);

// Không xóa biến $lang_global khỏi dòng gọi global bởi vì footer.php có thể được include từ trong function
global $db, $nv_Request, $nv_plugin_area, $headers, $nv_Lang, $global_config;

$contents = ob_get_contents();
ob_end_clean();
$contents = nv_url_rewrite($contents);
if (!defined('NV_IS_AJAX')) {
    $contents = nv_change_buffer($contents);
    if (defined('NV_IS_SPADMIN')) {
        $contents = str_replace('[MEMORY_TIME_USAGE]', sprintf($nv_Lang->getGlobal('memory_time_usage'), nv_convertfromBytes(memory_get_usage()), number_format((microtime(true) - NV_START_TIME), 3, '.', '')), $contents);
    }
}
$contents = nv_apply_hook('', 'change_site_buffer', [$global_config, $contents], $contents);

$html_headers = $global_config['others_headers'];
if (defined('NV_ADMIN') or !defined('NV_ANTI_IFRAME') or NV_ANTI_IFRAME != 0) {
    $html_headers['X-Frame-Options'] = 'SAMEORIGIN';
}
$html_headers['Content-Type'] = 'text/html; charset=' . $global_config['site_charset'];
$html_headers['Last-Modified'] = gmdate('D, d M Y H:i:s', strtotime('-1 day')) . ' GMT';
$html_headers['Cache-Control'] = 'max-age=0, no-cache, no-store, must-revalidate'; // HTTP 1.1.
$html_headers['Pragma'] = 'no-cache'; // HTTP 1.0.
$html_headers['Expires'] = '-1'; // Proxies.

if (strpos(NV_USER_AGENT, 'MSIE') !== false) {
    $html_headers['X-UA-Compatible'] = 'IE=edge,chrome=1';
}

if (!empty($headers)) {
    $html_headers = array_merge($html_headers, $headers);
}

if (!isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] != 'on') {
    unset($html_headers['Strict-Transport-Security']);
}

foreach ($html_headers as $key => $value) {
    $_key = strtolower($key);
    if (!isset($sys_info['server_headers'][$_key])) {
        if (!is_array($value)) {
            $value = [$value];
        }

        foreach ($value as $val) {
            $replace = ($key != 'link') ? true : false;
            header($key . ': ' . $val, $replace);
        }
    }
}

$db = null;
unset($lang_global, $global_config, $client_info);

echo $contents;
exit(0);
