<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

function nv_error_theme($page_title, $info_title, $info_content, $error_code, $admin_link, $admin_title, $site_link, $site_title, $template)
{
    global $global_config, $nv_Lang;

    if (!file_exists(NV_ROOTDIR . '/themes/' . $template . '/system/info_die.tpl')) {
        trigger_error('Error template!!!', 256);
    }

    $size = @getimagesize(NV_ROOTDIR . '/' . $global_config['site_logo']);

    $xtpl = new XTemplate('info_die.tpl', NV_ROOTDIR . '/themes/' . $template . '/system');
    $xtpl->assign('SITE_CHARSET', $global_config['site_charset']);
    $xtpl->assign('PAGE_TITLE', $page_title);
    $xtpl->assign('HOME_LINK', $global_config['site_url']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $template);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
    $xtpl->assign('SITE_NAME', $global_config['site_name']);

    $site_favicon = NV_BASE_SITEURL . 'favicon.ico';
    if (!empty($global_config['site_favicon']) and file_exists(NV_ROOTDIR . '/' . $global_config['site_favicon'])) {
        $site_favicon = NV_BASE_SITEURL . $global_config['site_favicon'];
    }
    $xtpl->assign('SITE_FAVICON', $site_favicon);

    if (isset($size[1])) {
        if ($size[0] > 490) {
            $size[1] = ceil(490 * $size[1] / $size[0]);
            $size[0] = 490;
        }
        $xtpl->assign('LOGO', NV_BASE_SITEURL . $global_config['site_logo']);
        $xtpl->assign('WIDTH', $size[0]);
        $xtpl->assign('HEIGHT', $size[1]);
        if (isset($size['mime']) and $size['mime'] == 'application/x-shockwave-flash') {
            $xtpl->parse('main.swf');
        } else {
            $xtpl->parse('main.image');
        }
    }
    $xtpl->assign('INFO_TITLE', $info_title);
    $xtpl->assign('INFO_CONTENT', $info_content);

    if (defined('NV_IS_ADMIN') and !empty($admin_link)) {
        $xtpl->assign('ADMIN_LINK', $admin_link);
        $xtpl->assign('GO_ADMINPAGE', empty($admin_title) ? $nv_Lang->getGlobal('admin_page') : $admin_title);
        $xtpl->parse('main.adminlink');
    }
    if (!empty($site_link)) {
        $xtpl->assign('SITE_LINK', $site_link);
        $xtpl->assign('GO_SITEPAGE', empty($site_title) ? $nv_Lang->getGlobal('go_homepage') : $site_title);
        $xtpl->parse('main.sitelink');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
