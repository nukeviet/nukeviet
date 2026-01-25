<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

function get_cdn_urls($urls = '', $countries_string = false, $except_inc = true)
{
    $cdn_urls = [];
    $except = [
        'val' => 'except',
        'is_default' => 0,
        'countries' => $countries_string ? '' : []
    ];

    if (empty($urls)) {
        global $db;
        $urls = $db->query('SELECT config_value FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE config_name='cdn_url' AND lang='sys' AND module='global'")->fetchColumn();
    }

    if (!empty($urls)) {
        $cdns = json_decode($urls, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            foreach ($cdns as $url => $vals) {
                if ($url == 'except') {
                    if (!empty($vals[1])) {
                        $except = [
                            'val' => $url,
                            'is_default' => 0,
                            'countries' => $countries_string ? (is_array($vals[1]) ? implode(' ', $vals[1]) : $vals[1]) : (is_array($vals[1]) ? $vals[1] : explode(' ', $vals[1]))
                        ];
                    }
                } else {
                    $cdn_urls[$url] = [
                        'val' => $url,
                        'is_default' => !empty($vals[0]) ? 1 : 0,
                        'countries' => $countries_string ? (!empty($vals[1]) ? (is_array($vals[1]) ? implode(' ', $vals[1]) : $vals[1]) : '') : (!empty($vals[1]) ? (is_array($vals[1]) ? $vals[1] : explode(' ', $vals[1])) : [])
                    ];
                }
            }
        } else {
            $cdn_urls[$urls] = [
                'val' => $urls,
                'is_default' => 1,
                'countries' => $countries_string ? '' : []
            ];
        }
    }

    if ($except_inc) {
        $cdn_urls['except'] = $except;
    }

    return $cdn_urls;
}

$page_title = $nv_Lang->getModule('cdn_backendhost');
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('cdn_backendhost.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', $checkss);

// Load form cấu hình CDN theo ngôn ngữ
if ($nv_Request->isset_request('by_country', 'get')) {
    $cdn_urls = get_cdn_urls();

    if ($checkss == $nv_Request->get_string('checkss', 'post')) {
        $urls = [];
        if (!empty($cdn_urls)) {
            foreach ($cdn_urls as $cdn_url) {
                $urls[$cdn_url['val']] = [];
                if ($cdn_url['is_default']) {
                    $urls[$cdn_url['val']][0] = 1;
                }
            }
        }
        $cdns = $nv_Request->get_typed_array('ccdn', 'post', 'title', []);
        $cdns = array_filter($cdns);
        if (!empty($cdns)) {
            $keys = [];
            foreach ($cdns as $code => $url) {
                if (!isset($keys[$url])) {
                    $keys[$url] = [];
                }
                $keys[$url][] = $code;
            }
            if (!empty($keys)) {
                foreach ($keys as $k => $vls) {
                    $urls[$k][1] = implode(' ', $vls);
                }
            }
        }

        if (empty($urls['except'])) {
            unset($urls['except']);
        }
        $urls = json_encode($urls);
        $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = 'cdn_url'");
        $sth->bindParam(':config_value', $urls, PDO::PARAM_STR);
        $sth->execute();

        nv_save_file_config_global();

        nv_jsonOutput([
            'status' => 'OK'
        ]);
    }

    $tpl->assign('COUNTRIES', $countries);
    $tpl->assign('CDN_URLS', $cdn_urls);

    $contents = $tpl->fetch('cdn_backendhost_country.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

// Lưu thiết lập CDN
if ($checkss == $nv_Request->get_string('checkss', 'post')) {
    $array_config_global = [];

    $array_config_global['nv_static_url'] = '';
    $static_url = rtrim($nv_Request->get_string('nv_static_url', 'post'), '/');
    if (!empty($static_url)) {
        if (!preg_match('/^(https?\:)?\/\//', $static_url)) {
            $static_url = '//' . $static_url;
        }
        unset($matches);
        preg_match('/^((https?\:)?\/\/)(.+)$/', $static_url, $matches);
        $scheme = $matches[1];
        $domain = $matches[3];
        $port = '';
        if (preg_match('/(.*)(\:[0-9]+)$/', $domain, $m)) {
            $domain = $m[1];
            $port = $m[2];
        }
        $domain = nv_check_domain(nv_strtolower($domain));
        if (!empty($domain) and array_search($domain, $global_config['my_domains'], true) === false) {
            $array_config_global['nv_static_url'] = $scheme . $domain . $port;
        }
    }

    $cdn_urls = $nv_Request->get_typed_array('cdn_url', 'post', 'title', []);
    $cdn_is_default = $nv_Request->get_typed_array('cdn_is_default', 'post', 'bool');
    $cdn_countries = $nv_Request->get_typed_array('cdn_countries', 'post', 'title', []);
    $cdns = [];
    $countries_codes = array_keys($countries);
    if (!empty($cdn_urls)) {
        foreach ($cdn_urls as $key => $url) {
            $url = rtrim($url, '/');
            if (!empty($url)) {
                if (!preg_match('/^(https?\:)?\/\//', $url)) {
                    $url = '//' . $url;
                }

                unset($matches);
                preg_match('/^((https?\:)?\/\/)(.+)$/', $url, $matches);
                $scheme = $matches[1];
                $domain = $matches[3];
                $port = '';
                if (preg_match('/(.*)(\:[0-9]+)$/', $domain, $m)) {
                    $domain = $m[1];
                    $port = $m[2];
                }
                $domain = nv_check_domain(nv_strtolower($domain));
                if (!empty($domain) and array_search($domain, $global_config['my_domains'], true) === false) {
                    $url = $scheme . $domain . $port;
                    $cdns[$url] = [];
                    if ($cdn_is_default[$key]) {
                        $cdns[$url][0] = 1;
                    }
                    if (!empty($cdn_countries[$key])) {
                        $codes = array_map('trim', explode(' ', $cdn_countries[$key]));
                        $codes = array_intersect($codes, $countries_codes);
                        sort($codes);
                        $cdns[$url][1] = implode(' ', $codes);
                    }
                }
            }
        }
    }
    $old_cdn_urls = get_cdn_urls('', true);
    if (!empty($old_cdn_urls['except']['countries'])) {
        $cdns['except'] = [1 => $old_cdn_urls['except']['countries']];
    }
    $array_config_global['cdn_url'] = json_encode($cdns);

    $array_config_global['assets_cdn'] = (int) $nv_Request->get_bool('assets_cdn', 'post', false);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
    foreach ($array_config_global as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_save_file_config_global();

    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

$array_config_global = [];
$result = $db->query('SELECT config_name, config_value FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE lang='sys' AND module='global'");
while ([$c_config_name, $c_config_value] = $result->fetch(3)) {
    $array_config_global[$c_config_name] = $c_config_value;
}

$core_cdn_url = !empty($global_config['core_cdn_url']) ? $global_config['core_cdn_url'] : 'https://cdn.jsdelivr.net/gh/nukeviet/nukeviet/';
$array_config_global['assets_cdn_note'] = $nv_Lang->getModule('assets_cdn_note', NV_ASSETS_DIR . '/css, ' . NV_ASSETS_DIR . '/fonts, ' . NV_ASSETS_DIR . '/images, ' . NV_ASSETS_DIR . '/js', NV_BASE_SITEURL . NV_ASSETS_DIR . '/js/jquery/jquery.min.js', $core_cdn_url . 'assets/js/jquery/jquery.min.js');

$cdn_urls = get_cdn_urls($array_config_global['cdn_url'], true, false);

if (empty($cdn_urls)) {
    array_unshift($cdn_urls, [
        'val' => '',
        'is_default' => 0,
        'countries' => ''
    ]);
}

$tpl->assign('DATA', $array_config_global);
$tpl->assign('CDN_URLS', $cdn_urls);

$contents = $tpl->fetch('cdn_backendhost.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
