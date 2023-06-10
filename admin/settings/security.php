<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$page_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$proxy_blocker_list = [
    0 => $nv_Lang->getModule('proxy_blocker_0'),
    1 => $nv_Lang->getModule('proxy_blocker_1'),
    2 => $nv_Lang->getModule('proxy_blocker_2'),
    3 => $nv_Lang->getModule('proxy_blocker_3')
];
$captcha_opts = ['', 'captcha', 'recaptcha'];
$captcha_area_list = ['a', 'l', 'r', 'm', 'p'];
$recaptcha_vers = [2, 3];
$captcha_comm_list = [
    0 => $nv_Lang->getModule('captcha_comm_0'),
    1 => $nv_Lang->getModule('captcha_comm_1'),
    2 => $nv_Lang->getModule('captcha_comm_2'),
    3 => $nv_Lang->getModule('captcha_comm_3')
];

$recaptcha_type_list = ['image' => $nv_Lang->getModule('recaptcha_type_image'), 'audio' => $nv_Lang->getModule('recaptcha_type_audio')];
$admin_2step_providers = ['code', 'facebook', 'google', 'zalo'];
$iptypes = [
    4 => 'IPv4',
    6 => 'IPv6'
];
$ipv4_mask_list = [
    0 => '255.255.255.255',
    3 => '255.255.255.xxx',
    2 => '255.255.xxx.xxx',
    1 => '255.xxx.xxx.xxx'
];
$banip_area_list = [$nv_Lang->getModule('area_select'), $nv_Lang->getModule('area_front'), $nv_Lang->getModule('area_admin'), $nv_Lang->getModule('area_both')];
$csp_directives = [
    'default-src' => ['none' => 0, 'all' => 0, 'self' => 0, 'data' => 0, 'unsafe-inline' => 0, 'unsafe-eval' => 0, 'hosts' => []],
    'script-src' => ['none' => 0, 'all' => 0, 'self' => 1, 'data' => 0, 'unsafe-inline' => 1, 'unsafe-eval' => 1, 'hosts' => ['*.google.com', '*.google-analytics.com', '*.googletagmanager.com', '*.gstatic.com', '*.facebook.com', '*.facebook.net', '*.twitter.com', '*.zalo.me', '*.zaloapp.com', '*.tawk.to', '*.cloudflareinsights.com']],
    'style-src' => ['none' => 0, 'all' => 0, 'self' => 1, 'data' => 1, 'unsafe-inline' => 1, 'hosts' => ['*.google.com', '*.googleapis.com', '*.tawk.to']],
    'img-src' => ['none' => 0, 'all' => 0, 'self' => 1, 'data' => 1, 'hosts' => ['*.twitter.com', '*.google.com', '*.googleapis.com', '*.gstatic.com', '*.facebook.com', 'tawk.link', '*.tawk.to', 'static.nukeviet.vn']],
    'font-src' => ['none' => 0, 'all' => 0, 'self' => 1, 'data' => 1, 'hosts' => ['*.googleapis.com', '*.gstatic.com', '*.tawk.to']],
    'connect-src' => ['none' => 0, 'all' => 0, 'self' => 1, 'hosts' => ['*.zalo.me', '*.tawk.to', 'wss://*.tawk.to']],
    'media-src' => ['none' => 0, 'all' => 0, 'self' => 1, 'hosts' => ['*.tawk.to']],
    'object-src' => ['none' => 0, 'all' => 0, 'self' => 1, 'hosts' => []],
    'prefetch-src' => ['none' => 0, 'all' => 0, 'self' => 1, 'hosts' => []],
    'frame-src' => ['none' => 0, 'all' => 0, 'self' => 1, 'hosts' => ['*.google.com', '*.youtube.com', '*.facebook.com', '*.facebook.net', '*.twitter.com', '*.zalo.me']],
    'frame-ancestors' => ['none' => 0, 'all' => 0, 'self' => 1, 'hosts' => []],
    'form-action' => ['none' => 0, 'all' => 0, 'self' => 1, 'hosts' => ['*.google.com']],
    'base-uri' => ['none' => 0, 'all' => 0, 'self' => 1, 'hosts' => []],
    'manifest-src' => ['none' => 0, 'all' => 0, 'self' => 1, 'hosts' => []]
];
$rp_directives = [
    'no-referrer' => $nv_Lang->getModule('rp_no_referrer'),
    'no-referrer-when-downgrade' => $nv_Lang->getModule('rp_no_referrer_when_downgrade'),
    'origin' => $nv_Lang->getModule('rp_origin'),
    'origin-when-cross-origin' => $nv_Lang->getModule('rp_origin_when_cross_origin'),
    'same-origin' => $nv_Lang->getModule('rp_same_origin'),
    'strict-origin' => $nv_Lang->getModule('rp_strict_origin'),
    'strict-origin-when-cross-origin' => $nv_Lang->getModule('rp_strict_origin_when_cross_origin'),
    'unsafe-url' => $nv_Lang->getModule('rp_unsafe_url')
];

$selectedtab = $nv_Request->get_int('selectedtab', 'get,post', 0);
if (!defined('NV_IS_GODADMIN')) {
    if ($selectedtab < 5 or $selectedtab > 6) {
        $selectedtab = 5;
    }
} elseif ($selectedtab < 0 or $selectedtab > 6) {
    $selectedtab = 0;
}

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);

// Xử lý các thiết lập cơ bản
if (defined('NV_IS_GODADMIN') and $nv_Request->isset_request('basicsave', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $post = [
        'str_referer_blocker' => (int) $nv_Request->get_bool('str_referer_blocker', 'post'),
        'is_login_blocker' => (int) $nv_Request->get_bool('is_login_blocker', 'post', false),
        'login_number_tracking' => $nv_Request->get_int('login_number_tracking', 'post', 0),
        'login_time_tracking' => $nv_Request->get_int('login_time_tracking', 'post', 0),
        'login_time_ban' => $nv_Request->get_int('login_time_ban', 'post', 0),
        'two_step_verification' => $nv_Request->get_int('two_step_verification', 'post', 0),
        'admin_2step_opt' => $nv_Request->get_typed_array('admin_2step_opt', 'post', 'title', []),
        'admin_2step_default' => $nv_Request->get_title('admin_2step_default', 'post', ''),
        'domains_restrict' => (int) $nv_Request->get_bool('domains_restrict', 'post', false),
        'XSSsanitize' => (int) $nv_Request->get_bool('XSSsanitize', 'post', false),
        'admin_XSSsanitize' => (int) $nv_Request->get_bool('admin_XSSsanitize', 'post', false),
        'passshow_button' => $nv_Request->get_int('passshow_button', 'post', 0),
        'request_uri_check' => $nv_Request->get_title('request_uri_check', 'post', 'page')
    ];
    $proxy_blocker = $nv_Request->get_int('proxy_blocker', 'post');
    if (isset($proxy_blocker_list[$proxy_blocker])) {
        $post['proxy_blocker'] = $proxy_blocker;
    }

    $domains = $nv_Request->get_textarea('domains_whitelist', '', NV_ALLOWED_HTML_TAGS, true);
    $domains = explode('<br />', strip_tags($domains, '<br>'));

    $post['domains_whitelist'] = [];
    foreach ($domains as $domain) {
        if (!empty($domain)) {
            $domain = parse_url($domain);
            if (is_array($domain)) {
                if (sizeof($domain) == 1 and !empty($domain['path'])) {
                    $domain['host'] = $domain['path'];
                }
                if (!isset($domain['scheme'])) {
                    $domain['scheme'] = 'http';
                }
                $domain_name = nv_check_domain($domain['host']);
                if (!empty($domain_name)) {
                    $post['domains_whitelist'][] = $domain_name;
                }
            }
        }
    }
    $post['domains_whitelist'] = empty($post['domains_whitelist']) ? '' : json_encode(array_unique($post['domains_whitelist']));

    $post['login_number_tracking'] < 1 && $post['login_number_tracking'] = 5;
    $post['login_time_tracking'] <= 0 && $post['login_time_tracking'] = 5;
    if ($post['two_step_verification'] < 0 or $post['two_step_verification'] > 3) {
        $post['two_step_verification'] = 0;
    }
    $post['admin_2step_opt'] = array_intersect($post['admin_2step_opt'], $admin_2step_providers);
    if (!in_array($post['admin_2step_default'], $admin_2step_providers, true)) {
        $post['admin_2step_default'] = '';
    }
    if (!in_array($post['admin_2step_default'], $post['admin_2step_opt'], true)) {
        $post['admin_2step_default'] = current($post['admin_2step_opt']);
    }
    $post['admin_2step_opt'] = empty($post['admin_2step_opt']) ? '' : implode(',', $post['admin_2step_opt']);

    $end_url_variables = $nv_Request->get_typed_array('end_url_variables', 'post', 'title', []);
    $parameters = $nv_Request->get_typed_array('parameters', 'post', 'title', []);

    $_end_url_variables = [];
    if (!empty($end_url_variables)) {
        foreach ($end_url_variables as $key => $variable) {
            if (preg_match('/^[a-zA-Z0-9\_]+$/', $variable)) {
                $vals = !empty($parameters[$key]) ? array_filter(array_map(function ($parameter) {
                    $parameter = trim($parameter);
                    if (!in_array($parameter, ['lower', 'upper', 'number', 'dash', 'under', 'dot', 'at'], true)) {
                        $parameter = '';
                    }

                    return $parameter;
                }, explode(',', $parameters[$key]))) : [];

                if (!empty($vals)) {
                    $_end_url_variables[$variable] = $vals;
                }
            }
        }
    }
    $post['end_url_variables'] = !empty($_end_url_variables) ? json_encode($_end_url_variables) : '';

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
    foreach ($post as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $post = [
        'nv_anti_agent' => (int) $nv_Request->get_bool('nv_anti_agent', 'post'),
        'nv_anti_iframe' => (int) $nv_Request->get_bool('nv_anti_iframe', 'post')
    ];

    $variable = $nv_Request->get_string('nv_allowed_html_tags', 'post');
    $variable = str_replace(';', ',', strtolower($variable));
    $variable = explode(',', $variable);
    $nv_allowed_html_tags = [];
    foreach ($variable as $value) {
        $value = trim($value);
        if (preg_match('/^[a-z0-9]+$/', $value) and !in_array($value, $nv_allowed_html_tags, true)) {
            $nv_allowed_html_tags[] = $value;
        }
    }
    $post['nv_allowed_html_tags'] = implode(', ', $nv_allowed_html_tags);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'define' AND config_name = :config_name");
    foreach ($post as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_save_file_config_global();

    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

// Chống Flood
if (defined('NV_IS_GODADMIN') and $nv_Request->isset_request('floodsave', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $post = [
        'is_flood_blocker' => (int) $nv_Request->get_bool('is_flood_blocker', 'post'),
        'max_requests_60' => $nv_Request->get_int('max_requests_60', 'post'),
        'max_requests_300' => $nv_Request->get_int('max_requests_300', 'post')
    ];

    if ($post['max_requests_60'] <= 0 or $post['max_requests_300'] <= 0) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('max_requests_error')
        ]);
    }

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
    foreach ($post as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_save_file_config_global();

    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

//Thêm/sửa IP
$action = $nv_Request->get_title('action', 'get', '');
if (defined('NV_IS_GODADMIN') and ($action == 'fip' or $action == 'bip')) {
    $page_url .= '&amp;action=' . $action;

    $id = $nv_Request->get_int('id', 'get', 0);
    $type = $action == 'fip' ? 1 : 0;
    if (!empty($id)) {
        $ipdetails = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_ips WHERE id=' . $id . ' AND type=' . $type)->fetch();
        if (empty($ipdetails)) {
            exit('IP not found in database');
        }

        $page_url .= '&amp;id=' . $id;
        $version = $ips->isIp4($ipdetails['ip']) ? 4 : 6;
    } else {
        $ipdetails = [
            'id' => 0,
            'type' => $type,
            'mask' => 0,
            'area' => 0,
            'begintime' => 0,
            'endtime' => 0,
            'notice' => ''
        ];
        $version = 4;
    }

    if ($nv_Request->isset_request('save', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
        $post = [
            'version' => $nv_Request->get_int('version', 'post', 4),
            'ip' => $nv_Request->get_title('ip', 'post', ''),
            'mask' => $nv_Request->get_int('mask', 'post', 0),
            'area' => $nv_Request->get_int('area', 'post', 0)
        ];
        $post['version'] != 6 && $post['version'] = 4;

        if (empty($post['ip'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('ip_not_entered')
            ]);
        }

        if (($post['version'] == 4 and !$ips->isIp4($post['ip'])) or ($post['version'] == 6 and !$ips->isIp6($post['ip']))) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('ip_incorrect')
            ]);
        }

        if (!$type and empty($post['area'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('area_not_selected')
            ]);
        }

        if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('begintime', 'post'), $m)) {
            $post['begintime'] = mktime($nv_Request->get_int('beginhour', 'post'), $nv_Request->get_int('beginmin', 'post'), 0, $m[2], $m[1], $m[3]);
        } else {
            $post['begintime'] = NV_CURRENTTIME;
        }

        if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('endtime', 'post'), $m)) {
            $post['endtime'] = mktime($nv_Request->get_int('endhour', 'post'), $nv_Request->get_int('endmin', 'post'), 0, $m[2], $m[1], $m[3]);
        } else {
            $post['endtime'] = 0;
        }

        if (!empty($post['endtime']) and $post['endtime'] < $post['begintime']) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('end_time_error')
            ]);
        }

        $type && $post['area'] = 1;

        if ($post['version'] == 4) {
            ($post['mask'] < 0 or $post['mask'] > 3) && $post['mask'] = 0;
        } else {
            ($post['mask'] < 1 or $post['mask'] > 128) && $post['mask'] = 128;
        }

        $post['notice'] = $nv_Request->get_title('notice', 'post', '', 1);

        if ($id) {
            $db->query('DELETE FROM ' . $db_config['prefix'] . '_ips WHERE type = ' . $type . ' AND ip = ' . $db->quote($post['ip']) . ' AND id != ' . $id);
            $sth = $db->prepare('UPDATE ' . $db_config['prefix'] . '_ips
                SET ip = :ip, mask = ' . $post['mask'] . ', area = ' . $post['area'] . ', begintime = ' . $post['begintime'] . ', endtime = ' . $post['endtime'] . ', notice = :notice
                WHERE id=' . $id);
        } else {
            $db->query('DELETE FROM ' . $db_config['prefix'] . '_ips WHERE type = ' . $type . ' AND ip = ' . $db->quote($post['ip']));
            $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_ips (type, ip, mask, area, begintime, endtime, notice) VALUES 
            (' . $type . ', :ip, ' . $post['mask'] . ', ' . $post['area'] . ', ' . $post['begintime'] . ', ' . $post['endtime'] . ', :notice )');
        }
        $sth->bindParam(':ip', $post['ip'], PDO::PARAM_STR);
        $sth->bindParam(':notice', $post['notice'], PDO::PARAM_STR);
        $sth->execute();

        $save = nv_save_file_ips($type);

        if ($save !== true) {
            $mess = $type ? $nv_Lang->getModule('ip_write_error', NV_DATADIR, 'efloodip.php') : $nv_Lang->getModule('ip_write_error', NV_DATADIR, 'banip.php');
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $mess . "\n\n" . $save
            ]);
        }

        nv_jsonOutput([
            'status' => 'OK',
            'type' => $type,
            'url' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=' . ($type ? 'fiplist' : 'biplist')
        ]);
    }

    if (!empty($ipdetails['begintime'])) {
        list($ipdetails['begintime'], $beginhour, $beginmin) = explode('|', date('d/m/Y|H|i', $ipdetails['begintime']));
    } else {
        $ipdetails['begintime'] = '';
        $beginhour = $beginmin = 0;
    }
    if (!empty($ipdetails['endtime'])) {
        list($ipdetails['endtime'], $endhour, $endmin) = explode('|', date('d/m/Y|H|i', $ipdetails['endtime']));
    } else {
        $ipdetails['endtime'] = '';
        $endhour = 23;
        $endmin = 59;
    }

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('FORM_ACTION', $page_url);
    $xtpl->assign('DATA', $ipdetails);
    $xtpl->assign('CHECKSS', $checkss);

    foreach ($iptypes as $key => $value) {
        $xtpl->assign('IP_VERSION', [
            'key' => $key,
            'title' => $value,
            'sel' => ($key == $version) ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('ip_action.version');
    }

    foreach ($ipv4_mask_list as $key => $value) {
        $xtpl->assign('MASK', [
            'val' => $key,
            'title' => $value,
            'ver' => 4,
            'selected' => ($version == 4 and $key == $ipdetails['mask']) ? ' selected="selected"' : '',
            'disabled' => $version == 4 ? '' : ' disabled="disabled" style="display:none"'
        ]);
        $xtpl->parse('ip_action.mask');
    }

    for ($i = 0; $i < 128; ++$i) {
        $key = 128 - $i;
        $xtpl->assign('MASK', [
            'val' => $key,
            'title' => '/' . $key,
            'ver' => 6,
            'selected' => ($version == 6 and $key == $ipdetails['mask']) ? ' selected="selected"' : '',
            'disabled' => $version == 6 ? '' : ' disabled="disabled" style="display:none"'
        ]);
        $xtpl->parse('ip_action.mask');
    }

    if ($type == 0) {
        foreach ($banip_area_list as $key => $value) {
            $xtpl->assign('AREA', [
                'val' => $key,
                'title' => $value,
                'sel' => ($key == $ipdetails['area']) ? ' selected="selected"' : ''
            ]);
            $xtpl->parse('ip_action.is_area.area');
        }
        $xtpl->parse('ip_action.is_area');
    }

    for ($i = 0; $i < 24; ++$i) {
        $xtpl->assign('BEGIN_HOUR', [
            'val' => $i,
            'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
            'sel' => ($i == $beginhour) ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('ip_action.beginhour');

        $xtpl->assign('END_HOUR', [
            'val' => $i,
            'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
            'sel' => ($i == $endhour) ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('ip_action.endhour');
    }

    for ($i = 0; $i < 60; ++$i) {
        $xtpl->assign('BEGIN_MIN', [
            'val' => $i,
            'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
            'sel' => ($i == $beginmin) ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('ip_action.beginmin');

        $xtpl->assign('END_MIN', [
            'val' => $i,
            'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
            'sel' => ($i == $endmin) ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('ip_action.endmin');
    }

    $xtpl->parse('ip_action');
    $contents = $xtpl->text('ip_action');
    nv_htmlOutput($contents);
}

// Xóa IP
if (defined('NV_IS_GODADMIN') and ($action == 'delfip' or $action == 'delbip') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $type = $action == 'delfip' ? 1 : 0;
    if (!empty($id)) {
        $db->query('DELETE FROM ' . $db_config['prefix'] . '_ips WHERE type = ' . $type . ' AND id = ' . $id);
    }

    $save = nv_save_file_ips($type);

    if ($save !== true) {
        $mess = $type ? $nv_Lang->getModule('ip_write_error', NV_DATADIR, 'efloodip.php') : $nv_Lang->getModule('ip_write_error', NV_DATADIR, 'banip.php');
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $mess . "\n\n" . $save
        ]);
    }

    nv_jsonOutput([
        'status' => 'OK',
        'type' => $type,
        'url' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=' . ($type ? 'fiplist' : 'biplist')
    ]);
}

// Lấy danh sách IP
if (defined('NV_IS_GODADMIN') and ($action == 'fiplist' or $action == 'biplist')) {
    $type = $action == 'fiplist' ? 1 : 0;
    $iplist = get_list_ips($type);

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    if (!empty($iplist)) {
        foreach ($iplist as $ip_details) {
            $xtpl->assign('ROW', $ip_details);
            $xtpl->parse('iplist.loop');
        }
    }
    $xtpl->parse('iplist');
    $contents = $xtpl->text('iplist');
    nv_htmlOutput($contents);
}

// Cấu hình captcha chung
if (defined('NV_IS_GODADMIN') and $nv_Request->isset_request('captchasave', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $post = [
        'recaptcha_ver' => $nv_Request->get_int('recaptcha_ver', 'post', 2),
        'recaptcha_sitekey' => $nv_Request->get_title('recaptcha_sitekey', 'post', ''),
        'recaptcha_secretkey' => $nv_Request->get_title('recaptcha_secretkey', 'post', ''),
        'recaptcha_type' => $nv_Request->get_title('recaptcha_type', 'post', '')
    ];

    if (!isset($recaptcha_type_list[$post['recaptcha_type']])) {
        $post['recaptcha_type'] = array_key_first($recaptcha_type_list);
    }
    if (!empty($post['recaptcha_secretkey'])) {
        $post['recaptcha_secretkey'] = $crypt->encrypt($post['recaptcha_secretkey']);
    }

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
    foreach ($post as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $post = [
        'nv_gfx_num' => $nv_Request->get_int('nv_gfx_num', 'post'),
        'nv_gfx_width' => $nv_Request->get_int('nv_gfx_width', 'post'),
        'nv_gfx_height' => $nv_Request->get_int('nv_gfx_height', 'post')
    ];

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'define' AND config_name = :config_name");
    foreach ($post as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_save_file_config_global();

    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

// Cấu hình hiển thị captcha cho từng module
if (defined('NV_IS_GODADMIN') and $nv_Request->isset_request('modcapt', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $mod_capts = $nv_Request->get_typed_array('captcha_type', 'post', 'title', '');
    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = :lang AND module = :module AND config_name = 'captcha_type'");
    foreach ($mod_capts as $mod => $type) {
        unset($lg, $modl);
        if (empty($type) or in_array($type, $captcha_opts, true)) {
            if ($mod == 'users' and $type != $global_config['captcha_type']) {
                $lg = 'sys';
                $modl = 'site';
            } elseif ($mod == 'banners' and $type != $module_config['banners']['captcha_type']) {
                $lg = 'sys';
                $modl = 'banners';
            } elseif (isset($module_config[$mod]['captcha_type']) and $type != $module_config[$mod]['captcha_type']) {
                $lg = NV_LANG_DATA;
                $modl = $mod;
            }
        }
        if (isset($lg, $modl)) {
            $sth->bindParam(':config_value', $type, PDO::PARAM_STR);
            $sth->bindParam(':lang', $lg, PDO::PARAM_STR);
            $sth->bindParam(':module', $modl, PDO::PARAM_STR);
            $sth->execute();
        }
    }

    $nv_Cache->delMod('settings');

    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

// Khu vực sử dụng captcha của module Thành viên
if (defined('NV_IS_GODADMIN') and $nv_Request->isset_request('captarea', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $captcha_areas = $nv_Request->get_typed_array('captcha_area', 'post', 'string');
    $captcha_areas = !empty($captcha_areas) ? implode(',', $captcha_areas) : '';
    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = 'captcha_area'");
    $sth->bindParam(':config_value', $captcha_areas, PDO::PARAM_STR);
    $sth->execute();

    $nv_Cache->delMod('settings');

    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

// Đối tượng áp dụng captcha khi tham gia Bình luận
if (defined('NV_IS_GODADMIN') and $nv_Request->isset_request('captcommarea', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $captcha_areas_comm = $nv_Request->get_typed_array('captcha_area_comm', 'post', 'int', 0);
    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module AND config_name = 'captcha_area_comm'");
    foreach ($captcha_areas_comm as $mod => $area) {
        if (isset($module_config[$mod]['captcha_area_comm'], $module_config[$mod]['activecomm'], $captcha_comm_list[$area])) {
            $sth->bindParam(':config_value', $area, PDO::PARAM_STR);
            $sth->bindParam(':module', $mod, PDO::PARAM_STR);
            $sth->execute();
        }
    }

    $nv_Cache->delMod('settings');

    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

// Xử lý thiết lập CORS, Anti CSRF
if (defined('NV_IS_GODADMIN') and $nv_Request->isset_request('corssave', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $post = [
        'crosssite_restrict' => (int) $nv_Request->get_bool('crosssite_restrict', 'post', false),
        'crossadmin_restrict' => (int) $nv_Request->get_bool('crossadmin_restrict', 'post', false)
    ];

    // Lấy các request domain
    $cfg_keys = ['crosssite_valid_domains', 'crossadmin_valid_domains'];
    foreach ($cfg_keys as $cfg_key) {
        $domains = $nv_Request->get_textarea($cfg_key, '', NV_ALLOWED_HTML_TAGS, true);
        $domains = explode('<br />', strip_tags($domains, '<br>'));

        $post[$cfg_key] = [];
        foreach ($domains as $domain) {
            $domain = trim($domain);
            if (!empty($domain)) {
                $domain = parse_url($domain);
                if (is_array($domain)) {
                    if (sizeof($domain) == 1 and !empty($domain['path'])) {
                        $domain['host'] = $domain['path'];
                    }
                    !isset($domain['scheme']) && $domain['scheme'] = 'http';
                    if (($domain_name = nv_check_domain($domain['host'])) != '') {
                        $post[$cfg_key][] = $domain['scheme'] . '://' . $domain_name . ((isset($domain['port']) and $domain['port'] != '80') ? (':' . $domain['port']) : '');
                    }
                }
            }
        }
        $post[$cfg_key] = empty($post[$cfg_key]) ? '' : json_encode(array_unique($post[$cfg_key]));
    }

    // Lấy các request IPs
    $cfg_keys = ['crosssite_valid_ips', 'crossadmin_valid_ips', 'ip_allow_null_origin'];
    foreach ($cfg_keys as $cfg_key) {
        $str_ips = $nv_Request->get_textarea($cfg_key, '', NV_ALLOWED_HTML_TAGS, true);
        $str_ips = explode('<br />', strip_tags($str_ips, '<br>'));

        $post[$cfg_key] = [];
        foreach ($str_ips as $str_ip) {
            $str_ip = trim($str_ip);
            if ($ips->isIp4($str_ip) or $ips->isIp6($str_ip)) {
                $post[$cfg_key][] = $str_ip;
            }
        }
        $post[$cfg_key] = empty($post[$cfg_key]) ? '' : json_encode(array_unique($post[$cfg_key]));
    }

    // Lấy các request có biến được chấp nhận
    $crosssite_allowed_variables = $nv_Request->get_textarea('crosssite_allowed_variables', '', NV_ALLOWED_HTML_TAGS, true);
    $crosssite_allowed_variables = explode('<br />', strip_tags($crosssite_allowed_variables, '<br>'));
    $res = [];
    if (!empty($crosssite_allowed_variables)) {
        foreach ($crosssite_allowed_variables as $variable) {
            if (!empty($variable)) {
                parse_str($variable, $result);
                $_res = [];
                foreach ($result as $k => $v) {
                    if (preg_match('/^[a-zA-Z0-9\_]+$/', $k) and (empty($v) or preg_match('/^[a-zA-Z0-9\-\_\.\@]+$/', $v))) {
                        $_res[$k] = $v;
                    }
                }

                if (!empty($_res)) {
                    $res[] = $_res;
                }
            }
        }
    }

    $post['crosssite_allowed_variables'] = empty($res) ? '' : json_encode($res);
    $post['allow_null_origin'] = (int) $nv_Request->get_bool('allow_null_origin', 'post', false);
    $post['auto_acao'] = (int) $nv_Request->get_bool('auto_acao', 'post', false);
    $post['load_files_seccode'] = $nv_Request->get_string('load_files_seccode', 'post', '');
    !empty($post['load_files_seccode']) && $post['load_files_seccode'] = $crypt->encrypt($post['load_files_seccode']);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value=:config_value WHERE lang='sys' AND module='global' AND config_name=:config_name");
    foreach ($post as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_CHANGE_CORS_SETTING', json_encode($post), $admin_info['userid']);
    nv_save_file_config_global();

    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

// Thiết lập CSP
if ($nv_Request->isset_request('cspsave', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $_directives = $_POST['directives'];
    $directives = [];
    foreach ($_directives as $directive => $sources) {
        $rs = [];
        foreach ($sources as $source => $val) {
            if (!empty($val)) {
                if ($source == 'hosts') {
                    $val = strip_tags($val);
                    $val = array_map('trim', explode("\n", $val));
                    $val = array_unique($val);
                } else {
                    $val = 1;
                }
                $rs[$source] = $val;
            }
        }
        if (!empty($rs)) {
            $directives[$directive] = $rs;
        }
    }

    $post = [
        'nv_csp' => json_encode($directives),
        'nv_csp_act' => (int) $nv_Request->get_bool('nv_csp_act', 'post', false),
        'nv_csp_script_nonce' => (int) $nv_Request->get_bool('nv_csp_script_nonce', 'post', false)
    ];

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($post as $config_name => $config_value) {
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->execute();
    }

    $nv_Cache->delMod('settings');

    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

// Thiết lập RP
if ($nv_Request->isset_request('rpsave', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $post = [];
    $post['nv_rp'] = [];
    $nv_rp = $nv_Request->get_title('nv_rp', 'post', '');
    if (!empty($nv_rp)) {
        $nv_rp = preg_replace("/[^a-zA-Z\-]/", ' ', $nv_rp);
        $nv_rp = preg_replace("/[\s]+/", ' ', $nv_rp);
    }
    $nv_rp = !empty($nv_rp) ? array_map('trim', explode(' ', $nv_rp)) : [];
    foreach ($nv_rp as $rp) {
        if (!empty($rp) and isset($rp_directives[$rp]) and $rp != 'no-referrer') {
            $post['nv_rp'][] = $rp;
        }
    }
    $post['nv_rp'] = !empty($post['nv_rp']) ? implode(', ', $post['nv_rp']) : '';
    $post['nv_rp_act'] = (int) $nv_Request->get_bool('nv_rp_act', 'post', false);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($post as $config_name => $config_value) {
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->execute();
    }

    $nv_Cache->delMod('settings');

    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

$global_config_list = $global_config;
$global_config_list['admin_2step_opt'] = empty($global_config['admin_2step_opt']) ? [] : explode(',', $global_config['admin_2step_opt']);
$global_config_list['domains_whitelist'] = empty($global_config['domains_whitelist']) ? '' : implode("\n", $global_config['domains_whitelist']);

$define_config_list = [
    'nv_anti_agent' => NV_ANTI_AGENT,
    'nv_anti_iframe' => NV_ANTI_IFRAME,
    'nv_allowed_html_tags' => NV_ALLOWED_HTML_TAGS
];

$flood_config_list = [
    'is_flood_blocker' => $global_config['is_flood_blocker'],
    'max_requests_60' => $global_config['max_requests_60'],
    'max_requests_300' => $global_config['max_requests_300']
];

$captcha_config_list = $global_config;
$array_define_captcha = [
    'nv_gfx_num' => NV_GFX_NUM,
    'nv_gfx_width' => NV_GFX_WIDTH,
    'nv_gfx_height' => NV_GFX_HEIGHT
];

$cross_config_list = [
    'crosssite_restrict' => $global_config['crosssite_restrict'],
    'crosssite_valid_domains' => empty($global_config['crosssite_valid_domains']) ? '' : implode("\n", $global_config['crosssite_valid_domains']),
    'crosssite_valid_ips' => empty($global_config['crosssite_valid_ips']) ? '' : implode("\n", $global_config['crosssite_valid_ips']),
    'crossadmin_restrict' => $global_config['crossadmin_restrict'],
    'crossadmin_valid_domains' => empty($global_config['crossadmin_valid_domains']) ? '' : implode("\n", $global_config['crossadmin_valid_domains']),
    'crossadmin_valid_ips' => empty($global_config['crossadmin_valid_ips']) ? '' : implode("\n", $global_config['crossadmin_valid_ips']),
    'allow_null_origin' => $global_config['allow_null_origin'],
    'ip_allow_null_origin' => empty($global_config['ip_allow_null_origin']) ? '' : implode("\n", $global_config['ip_allow_null_origin']),
    'load_files_seccode' => !empty($global_config['load_files_seccode']) ? $crypt->decrypt($global_config['load_files_seccode']) : '',
    'auto_acao' => !empty($global_config['auto_acao']) ? $global_config['auto_acao'] : 0
];
if (!empty($global_config['crosssite_allowed_variables'])) {
    $res = [];
    foreach ($global_config['crosssite_allowed_variables'] as $variable) {
        $res[] = http_build_query($variable);
    }
    $cross_config_list['crosssite_allowed_variables'] = implode("\n", $res);
} else {
    $cross_config_list['crosssite_allowed_variables'] = '';
}

$nv_Lang->setModule('two_step_verification_note', $nv_Lang->getModule('two_step_verification_note', $nv_Lang->getModule('two_step_verification0'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=groups'));

if (!empty($global_config['nv_csp'])) {
    $directives = json_decode($global_config['nv_csp'], true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $directives = $csp_directives;
    }
} else {
    $directives = [];
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('SELECTEDTAB', $selectedtab);
$xtpl->assign('CHECKSS', $checkss);

for ($i = 0; $i <= 6; ++$i) {
    $xtpl->assign('TAB' . $i . '_ACTIVE', $i == $selectedtab ? ' active' : '');
    $xtpl->assign('TAB' . $i . '_SEL', $i == $selectedtab ? ' selected="selected"' : '');
}

if (defined('NV_IS_GODADMIN')) {
    $xtpl->assign('ADD_FLOODIP_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&action=fip');
    $xtpl->assign('DEL_FLOODIP_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&action=delfip');
    $xtpl->assign('ADD_BANIP_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&action=bip');
    $xtpl->assign('DEL_BANIP_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&action=delbip');

    $xtpl->parse('main.sys_tabs');
    $xtpl->parse('main.sys_tabs2');

    $cross_config_list['crosssite_restrict'] = empty($cross_config_list['crosssite_restrict']) ? '' : ' checked="checked"';
    $cross_config_list['crosssite_options'] = empty($cross_config_list['crosssite_restrict']) ? '' : ' in';
    $cross_config_list['crossadmin_restrict'] = empty($cross_config_list['crossadmin_restrict']) ? '' : ' checked="checked"';
    $cross_config_list['crossadmin_options'] = empty($cross_config_list['crossadmin_restrict']) ? '' : ' in';
    $cross_config_list['allow_null_origin'] = empty($cross_config_list['allow_null_origin']) ? '' : ' checked="checked"';
    $cross_config_list['auto_acao'] = empty($cross_config_list['auto_acao']) ? '' : ' checked="checked"';

    $xtpl->assign('CONFIG_CROSS', $cross_config_list);

    $xtpl->assign('IS_FLOOD_BLOCKER', ($flood_config_list['is_flood_blocker']) ? ' checked="checked"' : '');
    $xtpl->assign('MAX_REQUESTS_60', $flood_config_list['max_requests_60']);
    $xtpl->assign('MAX_REQUESTS_300', $flood_config_list['max_requests_300']);

    $xtpl->assign('ANTI_AGENT', $define_config_list['nv_anti_agent'] ? ' checked="checked"' : '');
    foreach ($proxy_blocker_list as $proxy_blocker_i => $proxy_blocker_v) {
        $xtpl->assign('PROXYSELECTED', ($global_config_list['proxy_blocker'] == $proxy_blocker_i) ? ' selected="selected"' : '');
        $xtpl->assign('PROXYOP', $proxy_blocker_i);
        $xtpl->assign('PROXYVALUE', $proxy_blocker_v);
        $xtpl->parse('main.sys_contents.proxy_blocker');
    }
    $xtpl->assign('REFERER_BLOCKER', ($global_config_list['str_referer_blocker']) ? ' checked="checked"' : '');
    $xtpl->assign('ANTI_IFRAME', $define_config_list['nv_anti_iframe'] ? ' checked="checked"' : '');

    $xtpl->assign('IS_LOGIN_BLOCKER', ($global_config_list['is_login_blocker']) ? ' checked="checked"' : '');
    $xtpl->assign('DOMAINS_RESTRICT', ($global_config_list['domains_restrict']) ? ' checked="checked"' : '');
    $xtpl->assign('XSSSANITIZE', ($global_config_list['XSSsanitize']) ? ' checked="checked"' : '');
    $xtpl->assign('ADMIN_XSSSANITIZE', ($global_config_list['admin_XSSsanitize']) ? ' checked="checked"' : '');
    $xtpl->assign('LOGIN_NUMBER_TRACKING', $global_config_list['login_number_tracking']);
    $xtpl->assign('LOGIN_TIME_TRACKING', $global_config_list['login_time_tracking']);
    $xtpl->assign('LOGIN_TIME_BAN', $global_config_list['login_time_ban']);
    $xtpl->assign('DOMAINS_WHITELIST', $global_config_list['domains_whitelist']);

    $xtpl->assign('RECAPTCHA_SITEKEY', $captcha_config_list['recaptcha_sitekey']);
    $xtpl->assign('RECAPTCHA_SECRETKEY', $captcha_config_list['recaptcha_secretkey'] ? $crypt->decrypt($captcha_config_list['recaptcha_secretkey']) : '');

    foreach ($recaptcha_type_list as $recaptcha_type_key => $recaptcha_type_title) {
        $array = [
            'value' => $recaptcha_type_key,
            'select' => ($captcha_config_list['recaptcha_type'] == $recaptcha_type_key) ? ' selected="selected"' : '',
            'text' => $recaptcha_type_title
        ];
        $xtpl->assign('RECAPTCHA_TYPE', $array);
        $xtpl->parse('main.sys_contents.recaptcha_type');
    }

    foreach ($recaptcha_vers as $ver) {
        $array = [
            'value' => $ver,
            'select' => ($ver == $captcha_config_list['recaptcha_ver']) ? ' selected="selected"' : ''
        ];
        $xtpl->assign('OPTION', $array);
        $xtpl->parse('main.sys_contents.recaptcha_ver');
    }

    for ($i = 2; $i < 10; ++$i) {
        $array = [
            'value' => $i,
            'select' => ($i == $array_define_captcha['nv_gfx_num']) ? ' selected="selected"' : '',
            'text' => $i
        ];
        $xtpl->assign('OPTION', $array);
        $xtpl->parse('main.sys_contents.nv_gfx_num');
    }
    $xtpl->assign('NV_GFX_WIDTH', $array_define_captcha['nv_gfx_width']);
    $xtpl->assign('NV_GFX_HEIGHT', $array_define_captcha['nv_gfx_height']);
    $xtpl->assign('NV_ALLOWED_HTML_TAGS', $define_config_list['nv_allowed_html_tags']);

    for ($i = 0; $i <= 3; ++$i) {
        $two_step_verification = [
            'key' => $i,
            'title' => $nv_Lang->getModule('two_step_verification' . $i),
            'selected' => $i == $global_config_list['two_step_verification'] ? ' selected="selected"' : ''
        ];
        $xtpl->assign('TWO_STEP_VERIFICATION', $two_step_verification);
        $xtpl->parse('main.sys_contents.two_step_verification');
    }

    foreach ($admin_2step_providers as $admin_2step) {
        $admin_2step_opt = [
            'key' => $admin_2step,
            'title' => $nv_Lang->getGlobal('admin_2step_opt_' . $admin_2step),
            'checked' => in_array($admin_2step, $global_config_list['admin_2step_opt'], true) ? ' checked="checked"' : ''
        ];
        $xtpl->assign('ADMIN_2STEP_OPT', $admin_2step_opt);

        if ($admin_2step == 'facebook' or $admin_2step == 'google') {
            $xtpl->assign('LINK_CONFIG', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=config&amp;oauth_config=' . $admin_2step);
            $xtpl->parse('main.sys_contents.admin_2step_opt.link_config');
        } elseif ($admin_2step == 'zalo') {
            $xtpl->assign('LINK_CONFIG', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=zalo&amp;' . NV_OP_VARIABLE . '=settings');
            $xtpl->parse('main.sys_contents.admin_2step_opt.link_config');
        }

        $xtpl->parse('main.sys_contents.admin_2step_opt');

        $admin_2step_default = [
            'key' => $admin_2step,
            'title' => $nv_Lang->getGlobal('admin_2step_opt_' . $admin_2step),
            'selected' => $global_config_list['admin_2step_default'] == $admin_2step ? ' selected="selected"' : ''
        ];
        $xtpl->assign('ADMIN_2STEP_DEFAULT', $admin_2step_default);
        $xtpl->parse('main.sys_contents.admin_2step_default');
    }

    // Cấu hình hiển thị captcha cho từng module
    foreach ($site_mods as $title => $mod) {
        if ($title == 'users' or isset($module_config[$title]['captcha_type'])) {
            $mod['title'] = $title;
            $xtpl->assign('MOD', $mod);

            $captcha_type = $title == 'users' ? $global_config['captcha_type'] : $module_config[$title]['captcha_type'];
            foreach ($captcha_opts as $val) {
                $xtpl->assign('OPT', [
                    'val' => $val,
                    'sel' => (!empty($captcha_type) and $captcha_type == $val) ? ' selected="selected"' : '',
                    'title' => $nv_Lang->getModule('captcha_' . $val)
                ]);
                $xtpl->parse('main.sys_contents.mod.opt');
            }

            if ($captcha_type != 'recaptcha' or ($captcha_type == 'recaptcha' and !empty($global_config['recaptcha_sitekey']) and !empty($global_config['recaptcha_secretkey']))) {
                $xtpl->parse('main.sys_contents.mod.dnone');
            }
            $xtpl->parse('main.sys_contents.mod');
        }
    }

    // Khu vực sử dụng captcha của module Thành viên
    foreach ($captcha_area_list as $area) {
        $captcha_area = [
            'key' => $area,
            'checked' => str_contains($global_config['captcha_area'], $area) ? ' checked="checked"' : '',
            'title' => $nv_Lang->getModule('captcha_area_' . $area)
        ];
        $xtpl->assign('CAPTCHAAREA', $captcha_area);
        $xtpl->parse('main.sys_contents.captcha_area');
    }

    // Đối tượng áp dụng captcha khi tham gia Bình luận
    foreach ($captcha_comm_list as $i => $title_i) {
        $xtpl->assign('OPTALL', [
            'val' => $i,
            'title' => $title_i
        ]);
        $xtpl->parse('main.sys_contents.optAll');
    }

    foreach ($site_mods as $title => $mod) {
        if (isset($module_config[$title]['captcha_area_comm'], $module_config[$title]['activecomm'])) {
            $mod['title'] = $title;
            $xtpl->assign('MOD', $mod);

            foreach ($captcha_comm_list as $i => $title_i) {
                $xtpl->assign('OPT', [
                    'val' => $i,
                    'title' => $title_i,
                    'sel' => $i == $module_config[$title]['captcha_area_comm'] ? ' selected="selected"' : ''
                ]);
                $xtpl->parse('main.sys_contents.modcomm.opt');
            }
            $xtpl->parse('main.sys_contents.modcomm');
        }
    }

    $uri_check_values = [
        'page' => $nv_Lang->getModule('request_uri_check_page'),
        'not' => $nv_Lang->getModule('request_uri_check_not'),
        'path' => $nv_Lang->getModule('request_uri_check_path'),
        'query' => $nv_Lang->getModule('request_uri_check_query'),
        'abs' => $nv_Lang->getModule('request_uri_check_abs')
    ];
    foreach ($uri_check_values as $key => $val) {
        $xtpl->assign('URI_CHECK', [
            'val' => $key,
            'name' => $val,
            'sel' => $key == $global_config_list['request_uri_check'] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.sys_contents.request_uri_check');
    }

    empty($global_config_list['end_url_variables']) && $global_config_list['end_url_variables'][] = [];
    foreach ($global_config_list['end_url_variables'] as $key => $vals) {
        $xtpl->assign('VAR', [
            'parameters' => !empty($vals) ? implode(',', $vals) : '',
            'name' => !empty($key) ? $key : '',
            'lower_checked' => (!empty($vals) and in_array('lower', $vals, true)) ? ' checked="checked"' : '',
            'upper_checked' => (!empty($vals) and in_array('upper', $vals, true)) ? ' checked="checked"' : '',
            'number_checked' => (!empty($vals) and in_array('number', $vals, true)) ? ' checked="checked"' : '',
            'dash_checked' => (!empty($vals) and in_array('dash', $vals, true)) ? ' checked="checked"' : '',
            'under_checked' => (!empty($vals) and in_array('under', $vals, true)) ? ' checked="checked"' : '',
            'dot_checked' => (!empty($vals) and in_array('dot', $vals, true)) ? ' checked="checked"' : '',
            'at_checked' => (!empty($vals) and in_array('at', $vals, true)) ? ' checked="checked"' : ''
        ]);
        $xtpl->parse('main.sys_contents.end_url_variable');
    }

    $passshow_button_opts = [$nv_Lang->getModule('passshow_button_0'), $nv_Lang->getModule('passshow_button_1'), $nv_Lang->getModule('passshow_button_2'), $nv_Lang->getModule('passshow_button_3')];
    foreach ($passshow_button_opts as $val => $name) {
        $xtpl->assign('OPT', [
            'val' => $val,
            'sel' => $val == $global_config_list['passshow_button'] ? ' selected="selected"' : '',
            'name' => $name
        ]);
        $xtpl->parse('main.sys_contents.passshow_button');
    }

    $xtpl->parse('main.sys_contents');
}

//csp_directive
foreach ($csp_directives as $name => $sources) {
    $direct = [
        'name' => $name,
        'desc' => $nv_Lang->getModule('csp_' . $name)
    ];
    $xtpl->assign('DIRECTIVE', $direct);

    $is_none = !empty($directives[$name]['none']);
    foreach ($sources as $key => $default) {
        $val = '';
        if ($key == 'hosts' and !empty($directives[$name][$key])) {
            $val = is_array($directives[$name][$key]) ? implode(chr(13) . chr(10), $directives[$name][$key]) : preg_replace('/[\s]+/', chr(13) . chr(10), $directives[$name][$key]);
        }
        $source = [
            'key' => $key,
            'val' => $val,
            'checked' => !empty($directives[$name][$key]) ? ' checked="checked"' : '',
            'disabled' => ($key != 'none' and $is_none) ? ' disabled' : '',
            'name' => $nv_Lang->existsModule('csp_source_' . $name . '_' . $key) ? $nv_Lang->getModule('csp_source_' . $name . '_' . $key) : $nv_Lang->getModule('csp_source_' . $key)
        ];
        $xtpl->assign('SOURCE', $source);
        if ($key != 'hosts') {
            $xtpl->parse('main.csp_directive.checkbox');
        } else {
            $xtpl->parse('main.csp_directive.input');
        }
    }

    $xtpl->assign('CSP_ACT', $global_config['nv_csp_act'] ? ' checked="checked"' : '');
    $xtpl->assign('CSP_OPTIONS', $global_config['nv_csp_act'] ? ' in' : '');

    if ($name == 'script-src') {
        $xtpl->assign('CSP_SCRIPT_NONCE', $global_config['nv_csp_script_nonce'] ? ' checked="checked"' : '');
        $xtpl->parse('main.csp_directive.csp_script_nonce');
    }

    $xtpl->parse('main.csp_directive');
}

$xtpl->assign('RP', $global_config['nv_rp']);
$xtpl->assign('RP_ACT', $global_config['nv_rp_act'] ? ' checked="checked"' : '');
$xtpl->assign('RP_OPTIONS', $global_config['nv_rp_act'] ? ' in' : '');
foreach ($rp_directives as $name => $desc) {
    $rp_direct = [
        'name' => $name,
        'desc' => $desc
    ];
    $xtpl->assign('RP_DIRECTIVE', $rp_direct);
    $xtpl->parse('main.rp_directive');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $nv_Lang->getModule('security');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
