<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
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

$page_title = $lang_module['cdn_backendhost'];
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);

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

        exit('OK');
    }

    $xtpl = new XTemplate('cdn_backendhost.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&by_country=1');
    $xtpl->assign('CHECKSS', $checkss);

    foreach ($countries as $code => $vals) {
        $xtpl->assign('COUNTRY', [
            'code' => $code,
            'name' => isset($lang_global['country_' . $code]) ? $lang_global['country_' . $code] : $vals[1]
        ]);

        $is_sel = false;
        if (!empty($cdn_urls)) {
            foreach ($cdn_urls as $cdn) {
                $isel = false;
                if (!empty($cdn['countries']) and in_array($code, $cdn['countries'], true)) {
                    $is_sel = true;
                    $isel = true;
                }
                $xtpl->assign('CDN', [
                    'key' => $cdn['val'],
                    'sel' => $isel ? ' selected="selected"' : '',
                    'url' => ($cdn['val'] == 'except') ? $lang_module['dont_use'] : $cdn['val']
                ]);
                $xtpl->parse('by_country.country.cdn');
            }
        }

        if ($is_sel) {
            $xtpl->parse('by_country.country.selected');
        }
        $xtpl->parse('by_country.country');
    }

    $xtpl->parse('by_country');
    $content = $xtpl->text('by_country');

    nv_htmlOutput($content);
}

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
while (list($c_config_name, $c_config_value) = $result->fetch(3)) {
    $array_config_global[$c_config_name] = $c_config_value;
}

$array_config_global['assets_cdn_checked'] = $array_config_global['assets_cdn'] ? ' checked ' : '';
$core_cdn_url = !empty($global_config['core_cdn_url']) ? $global_config['core_cdn_url'] : 'https://cdn.jsdelivr.net/gh/nukeviet/nukeviet/';
$array_config_global['assets_cdn_note'] = sprintf($lang_module['assets_cdn_note'], NV_ASSETS_DIR . '/css, ' . NV_ASSETS_DIR . '/fonts, ' . NV_ASSETS_DIR . '/images, ' . NV_ASSETS_DIR . '/js', NV_BASE_SITEURL . NV_ASSETS_DIR . '/js/jquery/jquery.min.js', $core_cdn_url . 'assets/js/jquery/jquery.min.js');

$cdn_urls = get_cdn_urls($array_config_global['cdn_url'], true, false);

if (empty($cdn_urls)) {
    array_unshift($cdn_urls, [
        'val' => '',
        'is_default' => 0,
        'countries' => ''
    ]);
}

$xtpl = new XTemplate('cdn_backendhost.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('CDN_BY_COUNTRY_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;by_country=1');
$xtpl->assign('CHECKSS', $checkss);
$xtpl->assign('DATA', $array_config_global);

foreach ($cdn_urls as $cdn) {
    $cdn['is_default_checked'] = $cdn['is_default'] ? ' checked="checked"' : '';
    $xtpl->assign('CDN_URL', $cdn);
    $xtpl->parse('main.cdn_item');
}

$xtpl->parse('main');
$content = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($content);
include NV_ROOTDIR . '/includes/footer.php';
