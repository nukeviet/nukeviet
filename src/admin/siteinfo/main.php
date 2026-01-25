<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getGlobal('mod_siteinfo');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
$tpl->assign('LANG', $nv_Lang);

// Gói cập nhật
$package_update = 0;
if (defined('NV_IS_GODADMIN') and file_exists(NV_ROOTDIR . '/install/update_data.php')) {
    $package_update = 1;
}
$tpl->assign('PACKAGE_UPDATE', $package_update);

// Cấu hình giao diện
$theme_config = get_theme_config();

$select_options = [];
$is_edit = (int) $nv_Request->get_bool('edit', 'get', false);

if ($is_edit) {
    $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
    $select_options[$url] = $nv_Lang->getModule('ok_grid');
} else {
    $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;edit=1';
    $select_options[$url] = $nv_Lang->getModule('edit_grid');
}

$get_widget = $nv_Request->isset_request('load_list_widgets', 'post') ? 0 : 1;

// Thông tin thống kê và thông tin chờ xử lý từ các module
$stat_info = $pending_info = [];

if ($get_widget) {
    foreach ($site_mods as $mod => $value) {
        if (file_exists(NV_ROOTDIR . '/modules/' . $value['module_file'] . '/siteinfo.php')) {
            $siteinfo = $pendinginfo = [];
            $mod_data = $value['module_data'];

            // Đọc tạm ngôn ngữ của module
            $nv_Lang->loadModule($value['module_file'], false, true);

            include NV_ROOTDIR . '/modules/' . $value['module_file'] . '/siteinfo.php';

            // Xóa ngôn ngữ đã đọc tạm
            $nv_Lang->changeLang();

            if (!empty($siteinfo)) {
                $stat_info[$mod]['caption'] = $value['custom_title'];
                $stat_info[$mod]['field'] = $siteinfo;
            }

            if (!empty($pendinginfo)) {
                $pending_info[$mod]['caption'] = $value['custom_title'];
                $pending_info[$mod]['field'] = $pendinginfo;
            }
        }
    }
}

// Lấy các widgets
$html_widgets  = $array_widgets = [];
$mbackup = $module_name;
$mibackup = $module_info;
$mfbackup = $module_file;
$mubackup = $module_upload;
$mdbackup = $module_data;

foreach ($admin_mods as $module_name => $module_info) {
    $module_file = $module_upload = $module_data = $module_name;

    $widgets = nv_scandir(NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $module_name . '/widgets', '/^(.*)\.php$/i');
    if (empty($widgets)) {
        continue;
    }

    foreach ($widgets as $widget) {
        $widget_info = [];
        $content = '';
        $nv_Lang->loadModule($module_name, true, true);

        require NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $module_name . '/widgets/' . $widget;

        if (empty($widget_info['id']) or !preg_match('/^[a-zA-Z0-9\_]+$/i', $widget_info['id']) or !isset($widget_info['func']) or !is_callable($widget_info['func'])) {
            continue;
        }

        $widget_id = 'adm_' . $module_name . '_' . $widget_info['id'];
        $array_widgets[$widget_id] = [
            'admin' => 1,
            'module_name' => $module_name,
            'file_name' => $widget,
            'data' => $widget_info
        ];
        unset($widget_info);

        if ($get_widget and in_array($widget_id, $theme_config['widgets'])) {
            $html_widgets[$widget_id] = $array_widgets[$widget_id]['data']['func']();
        }
    }
}
foreach ($site_mods as $module_name => $module_info) {
    $module_file = $module_info['module_file'];
    $module_upload = $module_info['module_upload'];
    $module_data = $module_info['module_data'];

    $widgets = nv_scandir(NV_ROOTDIR . '/modules/' . $module_name . '/widgets', '/^(.*)\.php$/i');
    if (empty($widgets)) {
        continue;
    }

    foreach ($widgets as $widget) {
        $widget_info = [];
        $nv_Lang->loadModule($module_name, false, true);

        require NV_ROOTDIR . '/modules/' . $module_name . '/widgets/' . $widget;

        if (empty($widget_info['id']) or !preg_match('/^[a-zA-Z0-9\_]+$/i', $widget_info['id']) or !isset($widget_info['func']) or !is_callable($widget_info['func'])) {
            continue;
        }

        $widget_id = 'usr_' . $module_name . '_' . $widget_info['id'];
        $array_widgets[$widget_id] = [
            'admin' => 0,
            'module_name' => $module_name,
            'file_name' => $widget,
            'data' => $widget_info
        ];
        unset($widget_info);

        if ($get_widget and in_array($widget_id, $theme_config['widgets'])) {
            $html_widgets[$widget_id] = $array_widgets[$widget_id]['data']['func']();
        }
    }
}

$module_name = $mbackup;
$module_info = $mibackup;
$module_file = $mfbackup;
$module_upload = $mubackup;
$module_data = $mdbackup;
unset($mbackup, $mibackup, $mfbackup, $mubackup, $mdbackup);
$nv_Lang->changeLang();

if (!$get_widget) {
    // Chỉ lấy những tiện ích chưa tích hợp
    $array_widgets = array_diff_key($array_widgets, array_flip($theme_config['widgets']));
    $tpl->assign('WIDGETS', $array_widgets);

    $contents = $tpl->fetch('main_widgets.tpl');
    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

$tpl->assign('TCONFIG', $theme_config);
$tpl->assign('WIDGETS', $html_widgets);
$tpl->assign('IS_EDIT', $is_edit);
$tpl->assign('THEME_GRIDS', [
    'xs' => '&lt;576px',
    'sm' => '≥576px',
    'md' => '≥768px',
    'lg' => '≥992px',
    'xl' => '≥1200px',
    'xxl' => '≥1400px'
]);

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
