<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (!defined('NV_MAINFILE') or !defined('NV_ADMIN')) {
    die('Stop!!!');
}

function nv_error_theme($page_title, $info_title, $info_content, $error_code, $admin_link, $admin_title, $site_link, $site_title, $template)
{
    global $global_config, $nv_Lang;

    if (!file_exists(NV_ROOTDIR . '/themes/' . $template . '/system/info_die.tpl')) {
        trigger_error('Error template!!!', 256);
    }

    $global_config['site_name'] = empty($global_config['site_name']) ? NV_SERVER_NAME : $global_config['site_name'];

    $site_favicon = NV_BASE_SITEURL . 'favicon.ico';
    if (!empty($global_config['site_favicon']) and file_exists(NV_ROOTDIR . '/' . $global_config['site_favicon'])) {
        $site_favicon = NV_BASE_SITEURL . $global_config['site_favicon'];
    }

    $tpl = new \NukeViet\Template\Smarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $template . '/system');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('SITE_CHARSET', $global_config['site_charset']);
    $tpl->assign('SITE_DESCRIPTION', empty($global_config['site_description']) ? $global_config['site_name'] : $global_config['site_description']);
    $tpl->assign('NV_SITE_COPYRIGHT', $global_config['site_name'] . ' [' . $global_config['site_email'] . '] ');
    $tpl->assign('NV_SITE_NAME', $global_config['site_name']);
    $tpl->assign('SITE_FAVICON', $site_favicon);
    $tpl->assign('TEMPLATE', $template);
    $tpl->assign('NV_SITE_TIMEZONE_OFFSET', round(NV_SITE_TIMEZONE_OFFSET / 3600));
    $tpl->assign('NV_CURRENTTIME', NV_CURRENTTIME);
    $tpl->assign('NV_COOKIE_PREFIX', $global_config['cookie_prefix']);
    $tpl->assign('NV_CHECK_PASS_MSTIME', (intval($global_config['admin_check_pass_time']) - 62) * 1000);

    $tpl->assign('PAGE_TITLE', $page_title);
    $tpl->assign('ERROR_CODE', $error_code);
    $tpl->assign('ERROR_TITLE', $info_title);
    $tpl->assign('ERROR_CONTENT', $info_content);

    if (defined('NV_IS_ADMIN') and !empty($admin_link)) {
        $tpl->assign('ADMIN_LINK', $admin_link);
        $tpl->assign('GO_ADMINPAGE', empty($admin_title) ? $nv_Lang->getGlobal('admin_page') : $admin_title);
    }
    if (!empty($site_link)) {
        $tpl->assign('SITE_LINK', $site_link);
        $tpl->assign('GO_SITEPAGE', empty($site_title) ? $nv_Lang->getGlobal('go_homepage') : $site_title);
    }

    return $tpl->fetch('info_die.tpl');
}
