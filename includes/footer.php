<?php

/**
 * NukeViet Content Management System
 * @version 4.x
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
global $db, $nv_Request, $nv_plugin_area, $headers, $lang_global, $global_config, $sys_info, $nv_BotManager;

$contents = ob_get_contents();
ob_end_clean();
$contents = nv_url_rewrite($contents);
if (!defined('NV_IS_AJAX')) {
    $contents = nv_change_buffer($contents);
    $optimizer = new NukeViet\Core\Optimizer($contents, NV_BASE_SITEURL);
    $contents = $optimizer->process();
    $optimizer->headerPreload($headers);
    if (defined('NV_IS_SPADMIN')) {
        $contents = str_replace('[MEMORY_TIME_USAGE]', sprintf($lang_global['memory_time_usage'], nv_convertfromBytes(memory_get_usage()), number_format((microtime(true) - NV_START_TIME), 3, '.', '')), $contents);
    }
}

if (isset($nv_plugin_area[3])) {
    // Kết nối với các plugin Trước khi website gửi nội dung tới trình duyệt
    foreach ($nv_plugin_area[3] as $_fplugin) {
        include NV_ROOTDIR . '/includes/plugin/' . $_fplugin;
    }
}

//Close the connection by setting the PDO object
$db = null;

$html_headers = $global_config['others_headers'];
if (defined('NV_ADMIN') or !defined('NV_ANTI_IFRAME') or NV_ANTI_IFRAME != 0) {
    $html_headers['X-Frame-Options'] = 'SAMEORIGIN';
}

if (!defined('NV_ADMIN') and !empty($global_config['nv_csp_act']) and !empty($global_config['nv_csp'])) {
    $html_headers['Content-Security-Policy'] = nv_unhtmlspecialchars($global_config['nv_csp']);
}

if (!empty($global_config['nv_rp_act']) and !empty($global_config['nv_rp'])) {
    $html_headers['Referrer-Policy'] = $global_config['nv_rp'];
}

// Chặn Google FLoC (thu thập dữ liệu người dùng mà không cần cookie của Google)
// https://github.com/WICG/floc/blob/main/README.md
$html_headers['Permissions-Policy'] = 'interest-cohort=()';

$html_headers['Content-Type'] = 'text/html; charset=' . $global_config['site_charset'];
$html_headers['Last-Modified'] = gmdate('D, d M Y H:i:s', strtotime('-1 day')) . ' GMT';
$html_headers['Cache-Control'] = 'max-age=0, no-cache, no-store, must-revalidate'; // HTTP 1.1.
$html_headers['Pragma'] = 'no-cache'; // HTTP 1.0.
$html_headers['Expires'] = '-1'; // Proxies.

if (str_contains(NV_USER_AGENT, 'MSIE')) {
    $html_headers['X-UA-Compatible'] = 'IE=edge,chrome=1';
}

/*
 * Xuất cấu hình robot vào header
 * Chú ý kiểm tra biến $nv_BotManager vì có trường hợp undefined $nv_BotManager
 */
if (!empty($nv_BotManager)) {
    $nv_BotManager->outputToHeaders($headers, $sys_info);
}

if (!empty($headers)) {
    // $headers sẽ ghi đè $html_headers
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

unset($lang_global, $global_config, $client_info);

echo $contents;
exit(0);
