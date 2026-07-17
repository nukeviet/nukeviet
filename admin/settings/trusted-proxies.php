<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SETTINGS')) {
    exit('Stop!!!');
}

$page_title = $lang_module['trusted_proxy'];
$csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_' . $op;

/**
 * Tách chuỗi chứa ký tự xuống dòng hoặc dấu phảy thành mảng CIDR không trùng,
 * các dòng không hợp lệ được trả về trong tham chiếu $invalid
 *
 * @param string $raw
 * @param array  $invalid Tham chiếu, trả về các mục không hợp lệ
 * @return array
 */
function trusted_proxy_parse_list($raw, &$invalid = [])
{
    $invalid = [];
    $list = [];
    $lines = preg_split('/[\r\n,]+/', (string) $raw);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        if (\NukeViet\Core\Ips::validCidr($line)) {
            $list[strtolower($line)] = $line;
        } else {
            $invalid[] = $line;
        }
    }

    return array_values($list);
}

// Lấy danh sách dải IP của Cloudflare từ máy chủ Cloudflare
if ($nv_Request->isset_request('fetch_cloudflare', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Security check failed!!!'
        ]);
    }

    $merged = [];
    $seen = [];

    // Các dòng người dùng đang nhập
    foreach (trusted_proxy_parse_list($nv_Request->get_textarea('trusted_proxies', '', '')) as $cidr) {
        $key = strtolower($cidr);
        if (!isset($seen[$key])) {
            $seen[$key] = true;
            $merged[] = $cidr;
        }
    }

    // Fetch các dải IP Cloudflare
    $cf_count = 0;
    foreach (['https://www.cloudflare.com/ips-v4/', 'https://www.cloudflare.com/ips-v6/'] as $url) {
        $body = url_get_contents($url);
        if ($body === false or $body === '') {
            continue;
        }
        foreach (trusted_proxy_parse_list($body) as $cidr) {
            ++$cf_count;
            $key = strtolower($cidr);
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $merged[] = $cidr;
            }
        }
    }

    // Không lấy được dải nào từ Cloudflare thì báo lỗi
    if (empty($cf_count)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['trusted_proxy_cf_error']
        ]);
    }

    nv_jsonOutput([
        'status' => 'OK',
        'data' => $merged
    ]);
}

// Lưu cấu hình
if ($nv_Request->isset_request('save', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_global['error_checkss']
        ]);
    }

    $enable = $nv_Request->get_int('trusted_proxy_enable', 'post', 0) ? 1 : 0;
    $raw = $nv_Request->get_textarea('trusted_proxies', '', '');

    $invalid = [];
    $list = trusted_proxy_parse_list($raw, $invalid);

    if (!empty($invalid)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'trusted_proxies',
            'mess' => sprintf($lang_module['trusted_proxy_invalid'], implode(', ', array_slice($invalid, 0, 10)))
        ]);
    }

    // Bật trust proxy thì bắt buộc phải có ít nhất một dải IP hợp lệ
    if ($enable and empty($list)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'trusted_proxies',
            'mess' => $lang_module['trusted_proxy_empty_list']
        ]);
    }

    $config = [
        'trusted_proxy_enable' => (string) $enable,
        'trusted_proxies' => json_encode($list, NV_JSON_ENCODE)
    ];

    $sql = "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value=:config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name";
    $sth = $db->prepare($sql);
    foreach ($config as $config_name => $config_value) {
        $sth->bindValue(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindValue(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_CHANGE_TRUSTED_PROXY', json_encode([
        'enable' => $enable,
        'count' => count($list)
    ], NV_JSON_ENCODE), $admin_info['userid']);

    nv_save_file_config_global();

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $lang_global['save_success']
    ]);
}

// Đọc cấu hình hiện tại từ CSDL
$data = [
    'trusted_proxy_enable' => 0,
    'trusted_proxies' => ''
];
$stmt = $db->prepare("SELECT config_name, config_value FROM " . NV_CONFIG_GLOBALTABLE . " WHERE lang = 'sys' AND module = 'global' AND config_name IN ('trusted_proxy_enable', 'trusted_proxies')");
$stmt->execute();
while ($row = $stmt->fetch()) {
    if ($row['config_name'] == 'trusted_proxy_enable') {
        $data['trusted_proxy_enable'] = (int) $row['config_value'];
    } elseif ($row['config_name'] == 'trusted_proxies') {
        $list = json_decode((string) $row['config_value'], true);
        $data['trusted_proxies'] = is_array($list) ? implode("\n", $list) : '';
    }
}
$data['enable_checked'] = $data['trusted_proxy_enable'] ? ' checked="checked"' : '';

$xtpl = new XTemplate('trusted-proxies.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('CHECKSS', csrf_create($csrf_key));
$xtpl->assign('DATA', $data);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
