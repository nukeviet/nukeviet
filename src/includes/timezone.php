<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * nv_getTimezoneName_from_cookie()
 * Xác định tên múi giờ
 *
 * @param mixed $cookie
 * @return
 */
function nv_getTimezoneName_from_cookie($cookie)
{
    global $nv_parse_ini_timezone;
    if (preg_match('/^([\-]*\d+)\.([\-]*\d+)\.([\-]*\d+)\|(.*)$/', rawurldecode($cookie), $matches)) {
        foreach ($nv_parse_ini_timezone as $name => $offset) {
            if ($offset['winter_offset'] == (int) ($matches[2]) * 60 and $offset['summer_offset'] == (int) ($matches[1]) * 60) {
                return $name;
            }
        }
    }

    return '';
}

$global_config['cookie_prefix'] = (empty($global_config['cookie_prefix'])) ? 'nv4' : $global_config['cookie_prefix'];

if (isset($_COOKIE[$global_config['cookie_prefix'] . '_cltn'])) {
    $nv_cltn = base64_decode($_COOKIE[$global_config['cookie_prefix'] . '_cltn'], true);

    if (preg_match('/^([^\.]+)\.([\-]*\d+)\.(\d{1})$/', $nv_cltn, $matches)) {
        define('NV_CLIENT_TIMEZONE_NAME', $matches[1]);
        define('NV_CLIENT_TIMEZONE_OFFSET', $matches[2]);
        define('NV_CLIENT_TIMEZONE_DST', $matches[3]);
    } else {
        setcookie($global_config['cookie_prefix'] . '_cltn', false, NV_CURRENTTIME - 86400);
    }
}

if (!defined('NV_CLIENT_TIMEZONE_NAME') and isset($_COOKIE[$global_config['cookie_prefix'] . '_cltz']) and preg_match('/^([\-]*\d+)\.([\-]*\d+)\.([\-]*\d+)\|([^\|]*)\|(.*)$/', rawurldecode($_COOKIE[$global_config['cookie_prefix'] . '_cltz']), $matches2)) {
    $client_timezone_name = nv_getTimezoneName_from_cookie($_COOKIE[$global_config['cookie_prefix'] . '_cltz']);

    if (!empty($client_timezone_name)) {
        define('NV_CLIENT_TIMEZONE_NAME', $client_timezone_name);
        define('NV_CLIENT_TIMEZONE_OFFSET', $matches2[3] * 60);
    } else {
        $sd = floor($matches2[2] >= 0 ? $matches2[2] / 60 : -$matches2[2] / 60);

        define('NV_CLIENT_TIMEZONE_NAME', ($matches2[2] >= 0 ? '+' : '-') . str_pad($sd, 2, '0', STR_PAD_LEFT) . ':00');
        define('NV_CLIENT_TIMEZONE_OFFSET', floor($matches2[3] / 60) * 3600);
    }

    define('NV_CLIENT_TIMEZONE_DST', $matches2[1] != $matches2[2] ? 1 : 0);

    $client_timezone_name = base64_encode(NV_CLIENT_TIMEZONE_NAME . '.' . NV_CLIENT_TIMEZONE_OFFSET . '.' . NV_CLIENT_TIMEZONE_DST);

    $secure = NV_SERVER_PROTOCOL == 'https' ? true : false;
    if (version_compare(PHP_VERSION, '7.3.0', '>=')) {
        $options = [
            'expires' => 0,
            'path' => $matches2[4],
            'domain' => $matches2[5],
            'secure' => $secure,
            'httponly' => true
        ];
        if (!empty($global_config['cookie_SameSite']) and ('Lax' == $global_config['cookie_SameSite'] or 'Strict' == $global_config['cookie_SameSite'] or ('None' == $global_config['cookie_SameSite'] and $secure))) {
            $options['samesite'] = $global_config['cookie_SameSite'];
        }
        setcookie($global_config['cookie_prefix'] . '_cltn', $client_timezone_name, $options);
    } else {
        setcookie($global_config['cookie_prefix'] . '_cltn', $client_timezone_name, 0, $matches2[4], $matches2[5], $secure, true);
    }

    unset($client_timezone_name, $sd);
}

$site_timezone = ($global_config['site_timezone'] == 'byCountry') ? $countries[$client_info['country']][2] : $global_config['site_timezone'];

if ($site_timezone == '') {
    $site_timezone = defined('NV_CLIENT_TIMEZONE_NAME') ? NV_CLIENT_TIMEZONE_NAME : ((isset($global_config['statistics_timezone']) ? $global_config['statistics_timezone'] : ''));
    if ($site_timezone == '') {
        $site_timezone = 'Asia/Ho_Chi_Minh';
    }
}

date_default_timezone_set($site_timezone);
define('NV_SITE_TIMEZONE_GMT_NAME', preg_replace('/^([\+|\-]{1}\d{2})(\d{2})$/', '$1:$2', date('O')));

if (strcasecmp(date_default_timezone_get(), $site_timezone) == 0) {
    define('NV_SITE_TIMEZONE_NAME', $site_timezone);
} else {
    define('NV_SITE_TIMEZONE_NAME', NV_SITE_TIMEZONE_GMT_NAME);
}

unset($site_timezone);

define('NV_SITE_TIMEZONE_OFFSET', date('Z'));
