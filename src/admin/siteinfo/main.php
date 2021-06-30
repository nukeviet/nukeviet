<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    exit('Stop!!!');
}

$page_title = $lang_global['mod_siteinfo'];

//Noi dung chinh cua trang
$info = $pending_info = [];

foreach ($site_mods as $mod => $value) {
    if (file_exists(NV_ROOTDIR . '/modules/' . $value['module_file'] . '/siteinfo.php')) {
        $siteinfo = $pendinginfo = [];
        $mod_data = $value['module_data'];

        include NV_ROOTDIR . '/modules/' . $value['module_file'] . '/siteinfo.php';

        if (!empty($siteinfo)) {
            $info[$mod]['caption'] = $value['custom_title'];
            $info[$mod]['field'] = $siteinfo;
        }

        if (!empty($pendinginfo)) {
            $pending_info[$mod]['caption'] = $value['custom_title'];
            $pending_info[$mod]['field'] = $pendinginfo;
        }
    }
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);

// Kiem tra file nang cap tren he thong
if (defined('NV_IS_GODADMIN') and file_exists(NV_ROOTDIR . '/install/update_data.php')) {
    $xtpl->assign('URL_DELETE_PACKAGE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=webtools&amp;' . NV_OP_VARIABLE . '=deleteupdate&amp;checksess=' . NV_CHECK_SESSION);
    $xtpl->assign('URL_UPDATE', NV_BASE_SITEURL . 'install/update.php');
    $xtpl->parse('main.updateinfo');
}

// Thong tin thong ke tu cac module
if (!empty($info) or !empty($pending_info)) {
    if (!empty($info)) {
        $i = 0;
        foreach ($info as $if) {
            foreach ($if['field'] as $field) {
                $xtpl->assign('KEY', $field['key']);
                $xtpl->assign('VALUE', $field['value']);
                $xtpl->assign('MODULE', $if['caption']);

                if (!empty($field['link'])) {
                    $xtpl->assign('LINK', $field['link']);
                    $xtpl->parse('main.info.loop.link');
                } else {
                    $xtpl->parse('main.info.loop.text');
                }

                $xtpl->parse('main.info.loop');
            }
        }

        $xtpl->parse('main.info');
    }

    // Thong tin dang can duoc xu ly tu cac module
    if (!empty($pending_info)) {
        $i = 0;
        foreach ($pending_info as $if) {
            foreach ($if['field'] as $field) {
                $xtpl->assign('KEY', $field['key']);
                $xtpl->assign('VALUE', $field['value']);
                $xtpl->assign('MODULE', $if['caption']);

                if (!empty($field['link'])) {
                    $xtpl->assign('LINK', $field['link']);
                    $xtpl->parse('main.pendinginfo.loop.link');
                } else {
                    $xtpl->parse('main.pendinginfo.loop.text');
                }

                $xtpl->parse('main.pendinginfo.loop');
            }
        }

        $xtpl->parse('main.pendinginfo');
    }
} elseif (!defined('NV_IS_SPADMIN') and !empty($site_mods)) {
    $arr_mod = array_keys($site_mods);
    $module_name = $arr_mod[0];

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

// Thong tin phien ban NukeViet
if (defined('NV_IS_GODADMIN')) {
    $field = [];
    $field[] = ['key' => $lang_module['version_user'], 'value' => $global_config['version']];
    if (file_exists(NV_ROOTDIR . '/' . NV_CACHEDIR . '/nukeviet.version.' . NV_LANG_INTERFACE . '.xml')) {
        $new_version = simplexml_load_file(NV_ROOTDIR . '/' . NV_CACHEDIR . '/nukeviet.version.' . NV_LANG_INTERFACE . '.xml');
    } else {
        $new_version = [];
    }

    $info = '';
    if (!empty($new_version)) {
        $field[] = [
            'key' => $lang_module['version_news'],
            'value' => sprintf($lang_module['newVersion_detail'], (string) $new_version->version, nv_date('d/m/Y H:i', strtotime($new_version->date)))
        ];

        if (nv_version_compare($global_config['version'], $new_version->version) < 0) {
            $info = sprintf($lang_module['newVersion_info'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=webtools&amp;' . NV_OP_VARIABLE . '=checkupdate');
        }
    }

    $xtpl->assign('ULINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=webtools&amp;' . NV_OP_VARIABLE . '=checkupdate');
    $xtpl->assign('CHECKVERSION', $lang_module['checkversion']);

    foreach ($field as $key => $value) {
        $xtpl->assign('KEY', $value['key']);
        $xtpl->assign('VALUE', $value['value']);
        $xtpl->parse('main.version.loop');
    }

    if (!empty($info)) {
        $xtpl->assign('INFO', $info);
        $xtpl->parse('main.version.inf');
    }

    $xtpl->parse('main.version');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
