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

/**
 * nv_sitemapPing()
 *
 * @param mixed $module
 * @param mixed $link
 * @return
 */
function nv_sitemapPing($module, $link)
{
    global $sys_info, $global_config, $nv_Lang;

    $md5 = md5($link . $module . NV_LANG_DATA);
    $cacheFile = NV_ROOTDIR . '/' . NV_CACHEDIR . '/sitemapPing_' . $md5 . '.cache';

    if (file_exists($cacheFile) and filemtime($cacheFile) > (NV_CURRENTTIME - 3600)) {
        return $nv_Lang->getModule('pleasePingAgain');
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
        $url_parts = @parse_url($link);
        if (!$url_parts) {
            return $nv_Lang->getModule('searchEngineFailed');
        }
        if (!isset($url_parts['host'])) {
            return $nv_Lang->getModule('searchEngineFailed');
        }
        if (!isset($url_parts['path'])) {
            $url_parts['path'] = '/';
        }

        $sock = fsockopen($url_parts['host'], (isset($url_parts['port']) ? ( int )$url_parts['port'] : 80), $errno, $errstr, 3);
        if (!$sock) {
            return $nv_Lang->getModule('PingNotSupported');
        }

        $request = "GET " . $url_parts['path'] . (isset($url_parts['query']) ? '?' . $url_parts['query'] : '') . " HTTP/1.1\r\n";
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
            return $nv_Lang->getModule('searchEngineFailed');
        }
        if ($matches[1] != 200) {
            return $nv_Lang->getModule('searchEngineFailed');
        }
        $result = true;
    }

    if ($result) {
        file_put_contents($cacheFile, $link);
    }

    return $result ? $nv_Lang->getModule('pingOK') : $nv_Lang->getModule('PingNotSupported');
}

$file_searchEngines = NV_ROOTDIR . '/' . NV_DATADIR . '/search_engine_ping.xml';
$searchEngine = $module = '';
$searchEngines = [];
$searchEngines['searchEngine'] = [];
$info = '';

$sitemapFiles = [];
$sql = "SELECT f.in_module as name, m.custom_title as title FROM " . NV_MODFUNCS_TABLE . " f, " . NV_MODULES_TABLE . " m WHERE m.act = 1 AND f.func_name='sitemap' AND f.in_module = m.title";
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $sitemapFiles[$row['name']] = $row['title'];
}

if ($global_config['rewrite_enable'] and $global_config['check_rewrite_file']) {
    $url_sitemap = NV_MY_DOMAIN . NV_BASE_SITEURL . 'sitemap.xml';
} else {
    $url_sitemap = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=SitemapIndex' . $global_config['rewrite_endurl'];
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$tpl->assign('URL_SITEMAP', $url_sitemap);

$xtpl = new XTemplate('sitemap.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('URL_SITEMAP', $url_sitemap);
$xtpl->assign('ACTION_FORM', NV_BASE_ADMINURL. 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '='.$op);

if ($nv_Request->isset_request('submit', 'post') and empty($global_config['idsite'])) {
    $searchEngineName = $nv_Request->get_array('searchEngineName', 'post');
    $searchEngineValue = $nv_Request->get_array('searchEngineValue', 'post');

    foreach ($searchEngineName as $key => $name) {
        $name = trim(strip_tags($name));
        $value = trim(strip_tags($searchEngineValue[$key]));
        $active = intval($nv_Request->get_bool('searchEngineActive_' . $key, 'post', false));

        if (!empty($name) and !empty($value)) {
            $searchEngines['searchEngine'][] = array(
                'name' => $name,
                'value' => $value,
                'active' => $active
            );
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

    if (!empty($searchEngines['searchEngine']) and $nv_Request->isset_request('ping', 'post')) {
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
            $info = $nv_Lang->getModule('searchEngineSelect');
        } elseif (!$b) {
            $info = $nv_Lang->getModule('sitemapModule');
        }
    }
}

$tpl->assign('SEARCHENGINES', $searchEngines['searchEngine']);
$tpl->assign('SITEMAPFILES', $sitemapFiles);

$tpl->assign('SUBMIT_INFO', $info);
$tpl->assign('SUBMIT_SEARCHENGINE', $searchEngine);
$tpl->assign('SUBMIT_MODULE', $module);

$tpl->assign('ALLOWED_MANAGE', empty($global_config['idsite']));

$contents = $tpl->fetch('sitemap.tpl');
$page_title = $nv_Lang->getModule('sitemapPing');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
