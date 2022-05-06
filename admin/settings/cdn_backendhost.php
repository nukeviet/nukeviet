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

$page_title = $lang_module['cdn_backendhost'];
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);

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
    $cdn_actions = $nv_Request->get_typed_array('cdn_action', 'post', 'int');
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
                    $cdns[$url] = [$cdn_actions[$key]];
                    $codes = [];
                    if ($cdn_actions[$key] != 1 and !empty($cdn_countries[$key])) {
                        $codes = array_map('trim', explode(',', $cdn_countries[$key]));
                        $codes = array_intersect($codes, $countries_codes);
                        $codes = array_values($codes);
                    }
                    if (!empty($codes)) {
                        $cdns[$url][] = $codes;
                    }
                }
            }
        }
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

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

$array_config_global = [];
$result = $db->query('SELECT config_name, config_value FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE lang='sys' AND module='global'");
while (list($c_config_name, $c_config_value) = $result->fetch(3)) {
    $array_config_global[$c_config_name] = $c_config_value;
}

$array_config_global['assets_cdn_checked'] = $array_config_global['assets_cdn'] ? ' checked ' : '';
$array_config_global['assets_cdn_note'] = sprintf($lang_module['assets_cdn_note'], NV_ASSETS_DIR . '/css, ' . NV_ASSETS_DIR . '/fonts, ' . NV_ASSETS_DIR . '/images, ' . NV_ASSETS_DIR . '/js', NV_BASE_SITEURL . NV_ASSETS_DIR . '/js/jquery/jquery.min.js', $global_config['core_cdn_url'] . 'assets/js/jquery/jquery.min.js');
$cdn_urls = [];
if (!empty($array_config_global['cdn_url'])) {
    $cdns = json_decode($array_config_global['cdn_url'], true);
    if (json_last_error() === JSON_ERROR_NONE) {
        foreach ($cdns as $url => $vals) {
            $cdn_urls[] = [
                'key' => $url,
                'val' => $url,
                'action' => (int) $vals[0],
                'countries' => !empty($vals[1]) ? (array) $vals[1] : []
            ];
        }
    } else {
        $cdn_urls[] = [
            'key' => $array_config_global['cdn_url'],
            'val' => $array_config_global['cdn_url'],
            'action' => 1,
            'countries' => []
        ];
    }
}

if (empty($cdn_urls)) {
    $cdn_urls[] = [
        'key' => '',
        'val' => '',
        'action' => 0,
        'countries' => []
    ];
}

$xtpl = new XTemplate('cdn_backendhost.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('CHECKSS', $checkss);
$xtpl->assign('DATA', $array_config_global);

foreach ($cdn_urls as $cdn) {
    $cdn['countries_list'] = !empty($cdn['countries']) ? implode(', ', $cdn['countries']) : '';
    $xtpl->assign('CDN_URL', $cdn);
    $actions = [$lang_module['dont_use'], $lang_module['default'], $lang_module['bycountry']];
    foreach ($actions as $key => $action) {
        $xtpl->assign('ACTION', [
            'val' => $key,
            'sel' => (!empty($key) and $cdn['action'] == $key) ? ' selected="selected"' : '',
            'name' => $action
        ]);
        $xtpl->parse('main.cdn_item.action');
    }

    unset($countries['ZZ']);
    foreach ($countries as $key => $value) {
        $xtpl->assign('COUNTRY', [
            'code' => $key,
            'checked' => (!empty($cdn['countries']) and in_array($key, $cdn['countries'], true)) ? ' checked="checked"' : '',
            'name' => isset($lang_global['country_' . $key]) ? $lang_global['country_' . $key] : $value[1]
        ]);

        $xtpl->parse('main.cdn_item.country_list');
    }

    if ($cdn['action'] === 1) {
        $xtpl->parse('main.cdn_item.is_default');
    } elseif ($cdn['action'] === 2) {
        $xtpl->parse('main.cdn_item.by_country');
    } else {
        $xtpl->parse('main.cdn_item.is_secondary');
    }
    $xtpl->parse('main.cdn_item');
}

$xtpl->parse('main');
$content = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($content);
include NV_ROOTDIR . '/includes/footer.php';
