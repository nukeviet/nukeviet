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
 * @param string $module
 * @param string $link
 * @return array
 */
function nv_sitemapPing($module, $link)
{
    global $sys_info, $nv_Lang, $global_config;

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
    $open_basedir = @ini_get('open_basedir') ? true : false;
    if (!$open_basedir) {
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($c, CURLOPT_MAXREDIRS, 20);
    }
    curl_setopt($c, CURLOPT_TIMEOUT, 30);
    curl_setopt($c, CURLOPT_URL, $link);
    curl_exec($c);
    if (!curl_errno($c)) {
        $response = curl_getinfo($c);

        if ($response['http_code'] == 200) {
            $result = true;
        }
    }
    curl_close($c);

    if (!$result and nv_function_exists('fsockopen')) {
        $url_parts = parse_url($link);
        if (!$url_parts) {
            return [0, $nv_Lang->getModule('searchEngineFailed')];
        }
        if (!isset($url_parts['host'])) {
            return [0, $nv_Lang->getModule('searchEngineFailed')];
        }
        if (!isset($url_parts['path'])) {
            $url_parts['path'] = '/';
        }

        $sock = fsockopen($url_parts['host'], (isset($url_parts['port']) ? (int) $url_parts['port'] : 80), $errno, $errstr, 3);
        if (!$sock) {
            return [0, $nv_Lang->getModule('PingNotSupported')];
        }

        $request = 'GET ' . $url_parts['path'] . (isset($url_parts['query']) ? '?' . $url_parts['query'] : '') . " HTTP/1.1\r\n";
        $request .= 'Host: ' . $url_parts['host'] . "\r\n";
        $request .= "Connection: Close\r\n\r\n";
        fwrite($sock, $request);
        $response = '';
        while (!feof($sock)) {
            $response .= @fgets($sock, 4096);
        }
        fclose($sock);
        [$header, $result] = preg_split("/\r?\n\r?\n/", $response, 2);
        unset($matches);
        preg_match("/^HTTP\/[0-9\.]+\s+(\d+)\s+/", $header, $matches);
        if ($matches == []) {
            return [0, $nv_Lang->getModule('searchEngineFailed')];
        }
        if ($matches[1] != 200) {
            return [0, $nv_Lang->getModule('searchEngineFailed')];
        }
        $result = true;
    }

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

if ($global_config['rewrite_enable'] and $global_config['check_rewrite_file']) {
    $url_sitemap = NV_MY_DOMAIN . NV_BASE_SITEURL . 'sitemap.xml';
} else {
    $url_sitemap = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=SitemapIndex' . $global_config['rewrite_endurl'];
}
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);

// Lưu cấu hình các máy chủ ping
if ($checkss == $nv_Request->get_string('checkss2', 'post') and empty($global_config['idsite'])) {
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
if (!empty($searchEngines['searchEngine']) and $nv_Request->isset_request('ping', 'post') and $checkss == $nv_Request->get_string('checkss1', 'post')) {
    $searchEngine = $nv_Request->get_string('searchEngine', 'post');
    $module = nv_substr($nv_Request->get_title('in_module', 'post', '', 1), 0, 255);

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
$tpl->assign('CHECKSS', $checkss);
$tpl->assign('URL_SITEMAP', $url_sitemap);
$tpl->assign('SITEMAPFILES', $sitemapFiles);
$tpl->assign('SEARCHENGINES', $searchEngines);

$contents = $tpl->fetch('sitemap.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
