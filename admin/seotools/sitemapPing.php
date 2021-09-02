<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SEOTOOLS')) {
    exit('Stop!!!');
}

/**
 * nv_sitemapPing()
 *
 * @param string $module
 * @param string $link
 * @return string
 */
function nv_sitemapPing($module, $link)
{
    global $sys_info, $lang_module, $global_config;

    $md5 = md5($link . $module . NV_LANG_DATA);
    $cacheFile = NV_ROOTDIR . '/' . NV_CACHEDIR . '/sitemapPing_' . $md5 . '.cache';

    if (file_exists($cacheFile) and filemtime($cacheFile) > (NV_CURRENTTIME - 3600)) {
        return $lang_module['pleasePingAgain'];
    }

    if ($global_config['rewrite_enable'] and $global_config['check_rewrite_file']) {
        $myUrl = NV_MY_DOMAIN . NV_BASE_SITEURL . 'sitemap-' . NV_LANG_DATA . '.' . $module . '.xml';
    } else {
        $myUrl = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=sitemap';
    }

    $link = $link . urlencode($myUrl);

    $result = false;
    if ($sys_info['curl_support']) {
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
    }

    if (!$result and nv_function_exists('fsockopen')) {
        $url_parts = parse_url($link);
        if (!$url_parts) {
            return $lang_module['searchEngineFailed'];
        }
        if (!isset($url_parts['host'])) {
            return $lang_module['searchEngineFailed'];
        }
        if (!isset($url_parts['path'])) {
            $url_parts['path'] = '/';
        }

        $sock = fsockopen($url_parts['host'], (isset($url_parts['port']) ? (int) $url_parts['port'] : 80), $errno, $errstr, 3);
        if (!$sock) {
            return $lang_module['PingNotSupported'];
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
        list($header, $result) = preg_split("/\r?\n\r?\n/", $response, 2);
        unset($matches);
        preg_match("/^HTTP\/[0-9\.]+\s+(\d+)\s+/", $header, $matches);
        if ($matches == []) {
            return $lang_module['searchEngineFailed'];
        }
        if ($matches[1] != 200) {
            return $lang_module['searchEngineFailed'];
        }
        $result = true;
    }

    if ($result) {
        file_put_contents($cacheFile, $link);
    }

    return $result ? $lang_module['pingOK'] : $lang_module['PingNotSupported'];
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

$xtpl = new XTemplate('sitemap.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('URL_SITEMAP', $url_sitemap);
$xtpl->assign('ACTION_FORM', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('CHECKSS', $checkss);
if ($checkss == $nv_Request->get_string('checkss2', 'post') and empty($global_config['idsite'])) {
    $searchEngineName = $nv_Request->get_array('searchEngineName', 'post');
    $searchEngineValue = $nv_Request->get_array('searchEngineValue', 'post');
    $searchEngineActive = $nv_Request->get_array('searchEngineActive', 'post');

    foreach ($searchEngineName as $key => $name) {
        $name = trim(strip_tags($name));
        $value = trim(strip_tags($searchEngineValue[$key]));
        $active = (int) ($searchEngineActive[$key]);

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
} else {
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

    if (!empty($searchEngines['searchEngine']) and $nv_Request->isset_request('ping', 'post') and $checkss == $nv_Request->get_string('checkss1', 'post')) {
        $searchEngine = $nv_Request->get_string('searchEngine', 'post');
        $module = nv_substr($nv_Request->get_title('in_module', 'post', '', 1), 0, 255);

        $a = false;
        $b = false;
        foreach ($searchEngines['searchEngine'] as $value) {
            if ($value['name'] == $searchEngine and $value['active']) {
                if (!empty($sitemapFiles) and isset($sitemapFiles[$module])) {
                    $info = nv_sitemapPing($module, $value['value']);
                    $b = true;
                }

                $a = true;

                break;
            }
        }

        if (!$a) {
            $info = $lang_module['searchEngineSelect'];
        } elseif (!$b) {
            $info = $lang_module['sitemapModule'];
        }
    }
}

if (!empty($searchEngines['searchEngine'])) {
    if (!empty($sitemapFiles)) {
        foreach ($searchEngines['searchEngine'] as $value) {
            if ($value['active']) {
                $value['selected'] = $value['name'] == $searchEngine ? ' selected="selected"' : '';
                $xtpl->assign('ENGINE', $value);
                $xtpl->parse('main.is_ping.Engine');
            }
        }

        foreach ($sitemapFiles as $name => $title) {
            $xtpl->assign('MODULE_NAME', $name);
            $xtpl->assign('MODULE_TITLE', $title);
            $xtpl->assign('MODULE_SELECTED', ($name == $module ? ' selected="selected"' : ''));
            $xtpl->parse('main.is_ping.Module');
        }

        if (!empty($info)) {
            $xtpl->assign('INFO', $info);
            $xtpl->parse('main.is_ping.info');
        }

        $xtpl->parse('main.is_ping');
    }

    foreach ($searchEngines['searchEngine'] as $value) {
        $value['selected'] = $value['active'] ? ' selected="selected"' : '';
        $xtpl->assign('DATA', $value);
        $xtpl->parse('main.searchEngineList.loop');
    }
}

if (empty($global_config['idsite'])) {
    for ($i = 0; $i < 2; ++$i) {
        $data = [
            'name' => '',
            'value' => '',
            'checked' => ''
        ];
        $xtpl->assign('DATA', $data);
        $xtpl->parse('main.searchEngineList.loop');
    }
    $xtpl->parse('main.searchEngineList');
}
$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['sitemapPing'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
