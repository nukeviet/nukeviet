<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 22:5
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('site_configs_info');

$info = array();

$info['website'] = array(
    'caption' => $nv_Lang->getModule('site_configs_info'),
    'field' => array(
        array(
            'key' => $nv_Lang->getModule('site_domain'),
            'value' => NV_MY_DOMAIN
        ),
        array(
            'key' => $nv_Lang->getModule('site_url'),
            'value' => $global_config['site_url']
        ),
        array(
            'key' => $nv_Lang->getModule('site_root'),
            'value' => NV_ROOTDIR
        ),
        array(
            'key' => $nv_Lang->getModule('site_script_path'),
            'value' => $nv_Request->base_siteurl
        ),
        array(
            'key' => $nv_Lang->getModule('site_cookie_domain'),
            'value' => $global_config['cookie_domain']
        ),
        array(
            'key' => $nv_Lang->getModule('site_cookie_path'),
            'value' => $global_config['cookie_path']
        ),
        array(
            'key' => $nv_Lang->getModule('site_session_path'),
            'value' => $sys_info['sessionpath']
        ),
        array(
            'key' => $nv_Lang->getModule('site_timezone'),
            'value' => NV_SITE_TIMEZONE_NAME . (NV_SITE_TIMEZONE_GMT_NAME != NV_SITE_TIMEZONE_NAME ? ' (' . NV_SITE_TIMEZONE_GMT_NAME . ')' : '')
        )
    )
);

if (defined('NV_IS_GODADMIN')) {
    $global_config['version'] .= ' <a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=webtools&amp;' . NV_OP_VARIABLE . '=checkupdate">' . $nv_Lang->getModule('checkversion') . '</a>';
}

$info['server'] = array(
    'caption' => $nv_Lang->getModule('server_configs_info'),
    'field' => array(
        array(
            'key' => $nv_Lang->getModule('version'),
            'value' => $global_config['version']
        ),
        array(
            'key' => $nv_Lang->getModule('server_phpversion'),
            'value' => (PHP_VERSION != '' ? PHP_VERSION : phpversion())
        ),
        array(
            'key' => $nv_Lang->getModule('server_api'),
            'value' => (nv_function_exists('apache_get_version') ? apache_get_version() . ', ' : (nv_getenv('SERVER_SOFTWARE') != '' ? nv_getenv('SERVER_SOFTWARE') . ', ' : '')) . (PHP_SAPI != '' ? PHP_SAPI : php_sapi_name())
        ),
        array(
            'key' => $nv_Lang->getModule('server_phpos'),
            'value' => $sys_info['os']
        ),
        array(
            'key' => $nv_Lang->getModule('server_databaseversion'),
            'value' => $db->getAttribute(PDO::ATTR_DRIVER_NAME) . ' ' . $db->getAttribute(PDO::ATTR_SERVER_VERSION)
        )
    )
);

if (defined('NV_IS_GODADMIN') and substr($sys_info['os'], 0, 3) != 'WIN') {
    $info['chmod'] = array(
        'caption' => $nv_Lang->getModule('chmod'),
        'field' => array(
            array(
                'key' => NV_DATADIR,
                'value' => (is_writable(NV_ROOTDIR . '/' . NV_DATADIR) ? $nv_Lang->getModule('chmod_noneed') : $nv_Lang->getModule('chmod_need'))
            ),
            array(
                'key' => NV_CACHEDIR,
                'value' => (is_writable(NV_ROOTDIR . '/' . NV_CACHEDIR) ? $nv_Lang->getModule('chmod_noneed') : $nv_Lang->getModule('chmod_need'))
            ),
            array(
                'key' => NV_UPLOADS_DIR,
                'value' => (is_writable(NV_ROOTDIR . '/' . NV_UPLOADS_DIR) ? $nv_Lang->getModule('chmod_noneed') : $nv_Lang->getModule('chmod_need'))
            ),
            array(
                'key' => NV_TEMP_DIR,
                'value' => (is_writable(NV_ROOTDIR . '/' . NV_TEMP_DIR) ? $nv_Lang->getModule('chmod_noneed') : $nv_Lang->getModule('chmod_need'))
            ),
            array(
                'key' => NV_LOGS_DIR . '/data_logs',
                'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs') ? $nv_Lang->getModule('chmod_noneed') : $nv_Lang->getModule('chmod_need'))
            ),
            array(
                'key' => NV_LOGS_DIR . '/dump_backup',
                'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs') ? $nv_Lang->getModule('chmod_noneed') : $nv_Lang->getModule('chmod_need'))
            ),
            array(
                'key' => NV_LOGS_DIR . '/error_logs',
                'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs') ? $nv_Lang->getModule('chmod_noneed') : $nv_Lang->getModule('chmod_need'))
            ),
            array(
                'key' => NV_LOGS_DIR . '/error_logs/errors256',
                'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/errors256') ? $nv_Lang->getModule('chmod_noneed') : $nv_Lang->getModule('chmod_need'))
            ),
            array(
                'key' => NV_LOGS_DIR . '/error_logs/old',
                'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/old') ? $nv_Lang->getModule('chmod_noneed') : $nv_Lang->getModule('chmod_need'))
            ),
            array(
                'key' => NV_LOGS_DIR . '/error_logs/tmp',
                'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/tmp') ? $nv_Lang->getModule('chmod_noneed') : $nv_Lang->getModule('chmod_need'))
            ),
            array(
                'key' => NV_LOGS_DIR . '/ip_logs',
                'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs') ? $nv_Lang->getModule('chmod_noneed') : $nv_Lang->getModule('chmod_need'))
            ),
            array(
                'key' => NV_LOGS_DIR . '/ref_logs',
                'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ref_logs') ? $nv_Lang->getModule('chmod_noneed') : $nv_Lang->getModule('chmod_need'))
            ),
            array(
                'key' => NV_LOGS_DIR . '/voting_logs',
                'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/voting_logs') ? $nv_Lang->getModule('chmod_noneed') : $nv_Lang->getModule('chmod_need'))
            )
        )
    );
    if ($dh = opendir(NV_ROOTDIR . '/' . NV_CACHEDIR)) {
        while (($modname = readdir($dh)) !== false) {
            if (preg_match('/^([a-z0-9\_]+)$/', $modname)) {
                $info['chmod']['field'][] = array(
                    'key' => NV_CACHEDIR . '/' . $modname,
                    'value' => (is_writable(NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $modname) ? $nv_Lang->getModule('chmod_noneed') : $nv_Lang->getModule('chmod_need'))
                );
            }
        }
        closedir($dh);
    }
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('DATA', $info);
$tpl->assign('URL_CHMOD', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=siteinfo&amp;' . NV_OP_VARIABLE . '=checkchmod');

$contents = $tpl->fetch('system_info.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
