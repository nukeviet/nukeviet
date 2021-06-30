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

$site_lang = $nv_Request->get_string(NV_LANG_VARIABLE, 'get,post');
if (empty($global_config['lang_multi'])) {
    if ($site_lang == $global_config['site_lang'] or empty($site_lang)) {
        define('NV_LANG_INTERFACE', $global_config['site_lang']);
        define('NV_LANG_DATA', $global_config['site_lang']);
    } else {
        nv_redirect_location(NV_BASE_SITEURL);
    }
} elseif (defined('NV_ADMIN')) {
    $cookie = $nv_Request->get_string('data_lang', 'cookie');
    if (preg_match('/^[a-z]{2}$/', $site_lang) and file_exists(NV_ROOTDIR . '/includes/language/' . $site_lang . '/global.php')) {
        if ($site_lang != $cookie) {
            $nv_Request->set_Cookie('data_lang', $site_lang, NV_LIVE_COOKIE_TIME);
        }

        define('NV_LANG_DATA', $site_lang);
    } elseif (preg_match('/^[a-z]{2}$/', $cookie) and file_exists(NV_ROOTDIR . '/includes/language/' . $cookie . '/global.php')) {
        define('NV_LANG_DATA', $cookie);
    } else {
        $nv_Request->set_Cookie('data_lang', $global_config['site_lang'], NV_LIVE_COOKIE_TIME);

        define('NV_LANG_DATA', $global_config['site_lang']);
    }

    $cookie = $nv_Request->get_string('int_lang', 'cookie');
    $langinterface = $nv_Request->get_string('langinterface', 'get,post', '');

    if (preg_match('/^[a-z]{2}$/', $langinterface) and file_exists(NV_ROOTDIR . '/includes/language/' . $langinterface . '/global.php')) {
        if ($langinterface != $cookie) {
            $nv_Request->set_Cookie('int_lang', $langinterface, NV_LIVE_COOKIE_TIME);
        }

        define('NV_LANG_INTERFACE', $langinterface);
    } elseif (preg_match('/^[a-z]{2}$/', $cookie) and file_exists(NV_ROOTDIR . '/includes/language/' . $cookie . '/global.php')) {
        define('NV_LANG_INTERFACE', $cookie);
    } else {
        $nv_Request->set_Cookie('int_lang', $global_config['site_lang'], NV_LIVE_COOKIE_TIME);

        define('NV_LANG_INTERFACE', $global_config['site_lang']);
    }

    unset($cookie, $site_lang, $langinterface);
} else {
    $cookie = $nv_Request->get_string('u_lang', 'cookie');
    if (preg_match('/^[a-z]{2}$/', $site_lang) and file_exists(NV_ROOTDIR . '/includes/language/' . $site_lang . '/global.php')) {
        if ($site_lang != $cookie) {
            $nv_Request->set_Cookie('u_lang', $site_lang, NV_LIVE_COOKIE_TIME);
        }
    } elseif (preg_match('/^[a-z]{2}$/', $cookie) and file_exists(NV_ROOTDIR . '/includes/language/' . $cookie . '/global.php')) {
        $site_lang = $cookie;
    } else {
        $site_lang = $global_config['site_lang'];

        if ($global_config['lang_geo']) {
            $config_geo = [];
            include NV_ROOTDIR . '/' . NV_DATADIR . '/config_geo.php';
            if (isset($config_geo[$client_info['country']])) {
                $cookie = $config_geo[$client_info['country']];
                if (preg_match('/^[a-z]{2}$/', $cookie) and file_exists(NV_ROOTDIR . '/includes/language/' . $cookie . '/global.php')) {
                    $site_lang = $cookie;
                }
            }
        }
        $nv_Request->set_Cookie('u_lang', $site_lang, NV_LIVE_COOKIE_TIME);
    }

    define('NV_LANG_INTERFACE', $site_lang);
    define('NV_LANG_DATA', $site_lang);
    unset($cookie, $site_lang);
}
