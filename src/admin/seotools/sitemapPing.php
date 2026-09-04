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

$page_title = $nv_Lang->getModule('sitemapPing');

/**
 * Kiểm tra URL có an toàn để gửi request hay không (chống SSRF)
 *
 * @param string     $url
 * @param array|null $pin (out) Thông tin IP đã kiểm để ghim kết nối chống DNS rebinding
 * @return bool
 */
function nv_is_safe_url($url, &$pin = null)
{
    $pin = null;

    if (!nv_is_url($url)) {
        return false;
    }

    $parts = parse_url($url);
    if (empty($parts) or !isset($parts['scheme']) or !isset($parts['host'])) {
        return false;
    }

    // Chỉ cho phép http và https
    if (!in_array(strtolower($parts['scheme']), ['http', 'https'], true)) {
        return false;
    }

    $host = strtolower($parts['host']);

    // Chặn CRLF trong host
    if (strpbrk($host, "\r\n") !== false) {
        return false;
    }

    /*
     * Resolve tất cả bản ghi A và AAAA rồi yêu cầu mọi IP đều nằm ngoài dải
     * nội bộ/dành riêng, trả về IP đã kiểm để ghim kết nối chống DNS rebinding.
     */
    $pin_ip = null;
    if (!\NukeViet\Core\Ips::is_safe_host($host, $pin_ip, defined('NV_DEVELOPER_MODE'))) {
        return false;
    }

    $pin = [
        'host' => $host,
        'ip' => (string) $pin_ip,
        'port' => isset($parts['port']) ? (int) $parts['port'] : (strtolower($parts['scheme']) === 'https' ? 443 : 80),
    ];

    return true;
}

/**
 * @param string $module
 * @param string $link
 * @return array
 */
function nv_sitemapPing($module, $link)
{
    global $nv_Lang, $global_config;

    $pin = null;
    if (!nv_is_safe_url($link, $pin)) {
        return [0, $nv_Lang->getModule('searchEngineFailed')];
    }

    $md5 = md5($link . $module . NV_LANG_DATA);
    $cacheFile = NV_ROOTDIR . '/' . NV_CACHEDIR . '/sitemapPing_' . $md5 . '.cache';

    if (file_exists($cacheFile) and filemtime($cacheFile) > (NV_CURRENTTIME - 3600)) {
        return [2, $nv_Lang->getModule('pleasePingAgain')];
    }

    if ($global_config['rewrite_enable'] and $global_config['check_rewrite_file']) {
        $myUrl = NV_MY_DOMAIN . NV_BASE_SITEURL . 'sitemap-' . NV_LANG_DATA . '.' . $module . '.xml';
    } else {
        $myUrl = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=sitemap';
    }

    $link .= urlencode($myUrl);

    $result = false;
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

    // Tắt follow redirect để tránh SSRF qua chuyển hướng tới host nội bộ
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($c, CURLOPT_TIMEOUT, 30);
    curl_setopt($c, CURLOPT_URL, $link);

    // Ghim host vào IP đã xác minh để chống DNS rebinding
    if (!empty($pin['ip'])) {
        curl_setopt($c, CURLOPT_RESOLVE, [$pin['host'] . ':' . $pin['port'] . ':' . $pin['ip']]);
    }
    curl_exec($c);
    if (!curl_errno($c)) {
        $response = curl_getinfo($c);

        if ($response['http_code'] == 200) {
            $result = true;
        }
    }
    unset($c);

    if ($result) {
        file_put_contents($cacheFile, $link);
    }

    return $result ? [1, $nv_Lang->getModule('pingOK')] : [0, $nv_Lang->getModule('PingNotSupported')];
}

$file_searchEngines = NV_ROOTDIR . '/' . NV_DATADIR . '/search_engine_ping.xml';
$searchEngine = $module = '';
$searchEngines = [];
$searchEngines['searchEngine'] = [];
$info = '';

$sitemapFiles = [];
$sql = 'SELECT f.in_module as name, m.custom_title as title FROM ' . NV_MODFUNCS_TABLE . ' f, ' . NV_MODULES_TABLE . " m WHERE m.act = 1 AND f.func_name='sitemap' AND f.in_module = m.title";
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $sitemapFiles[$row['name']] = $row['title'];
}
$result->closeCursor();

if ($global_config['rewrite_enable'] and $global_config['check_rewrite_file']) {
    $url_sitemap = NV_MY_DOMAIN . NV_BASE_SITEURL . 'sitemap.xml';
} else {
    $url_sitemap = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=SitemapIndex' . $global_config['rewrite_endurl'];
}

// Lưu cấu hình các máy chủ ping
if ($nv_Request->isset_request('checkss2', 'post') and empty($global_config['idsite'])) {
    if (!csrf_check($nv_Request->get_string('checkss2', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $searchEngineName = $nv_Request->get_array('searchEngineName', 'post');
    $searchEngineValue = $nv_Request->get_array('searchEngineValue', 'post');
    $searchEngineActive = $nv_Request->get_array('searchEngineActive', 'post');

    foreach ($searchEngineName as $key => $name) {
        $name = trim(strip_tags($name));
        $value = trim(strip_tags($searchEngineValue[$key]));
        $active = (int) ($searchEngineActive[$key] ?? 0);

        if (!empty($name) and !empty($value)) {
            $searchEngines['searchEngine'][] = [
                'name' => $name,
                'value' => $value,
                'active' => $active
            ];
        }
    }

    if (file_exists($file_searchEngines)) {
        nv_deletefile($file_searchEngines);
    }

    if (!empty($searchEngines['searchEngine'])) {
        $array2XML = new NukeViet\Xml\Array2XML();
        $array2XML->saveXML($searchEngines, 'searchEngines', $file_searchEngines, $global_config['site_charset']);
    }

    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'refresh' => 1
    ]);
}

if (file_exists($file_searchEngines)) {
    $mt = simplexml_load_file($file_searchEngines);
    $mt = nv_object2array($mt);
    if ($mt['searchEngine_item']) {
        if (isset($mt['searchEngine_item'][0])) {
            $searchEngines['searchEngine'] = $mt['searchEngine_item'];
        } else {
            $searchEngines['searchEngine'][] = $mt['searchEngine_item'];
        }
    }
}

// Gửi ping
if (!empty($searchEngines['searchEngine']) and $nv_Request->isset_request('ping', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss1', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $searchEngine = $nv_Request->get_string('searchEngine', 'post');
    $module = $nv_Request->get_title('in_module', 'post', '', 255);

    if (empty($searchEngine)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'searchEngine',
            'mess' => $nv_Lang->getModule('searchEngineSelect')
        ]);
    }
    if (empty($module)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'in_module',
            'mess' => $nv_Lang->getModule('sitemapModule')
        ]);
    }

    foreach ($searchEngines['searchEngine'] as $value) {
        if ($value['name'] == $searchEngine and $value['active']) {
            if (!empty($sitemapFiles) and isset($sitemapFiles[$module])) {
                $info = nv_sitemapPing($module, $value['value']);
                nv_jsonOutput([
                    'status' => $info[0] == 1 ? 'success' : ($info[0] == 2 ? 'warning' : 'error'),
                    'mess' => $info[1]
                ]);
            }
        }
    }

    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'No data'
    ]);
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'array_merge', 'array_merge');
$tpl->setTemplateDir(get_module_tpl_dir('sitemap.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('GCONFIG', $global_config);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('URL_SITEMAP', $url_sitemap);
$tpl->assign('SITEMAPFILES', $sitemapFiles);
$tpl->assign('SEARCHENGINES', $searchEngines);

$contents = $tpl->fetch('sitemap.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
