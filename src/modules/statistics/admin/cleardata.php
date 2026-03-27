<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_STATISTICS')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('cleardata');

if ($nv_Request->isset_request('save', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $cleartype = $nv_Request->get_title('cleartype', 'post', '');
    $alllang = $nv_Request->get_int('alllang', 'post', 0);

    if (!in_array($cleartype, ['bot', 'browser', 'country', 'os', 'referer', 'hit', 'all'], true)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_send_data')
        ]);
    }

    $clearall = ($cleartype === 'all');

    $query_update = ['last_update=0'];
    foreach ($global_config['allow_sitelangs'] as $lang) {
        if ($alllang or $lang == NV_LANG_DATA) {
            $query_update[] = $lang . '_count=0';
        }
    }
    if ($alllang) {
        $query_update[] = 'c_count=0';
    }
    $query_update = implode(', ', $query_update);

    // Xóa máy chủ tìm kiếm
    if ($clearall or $cleartype === 'bot') {
        $db->query('UPDATE ' . NV_COUNTER_GLOBALTABLE . ' SET ' . $query_update . " WHERE c_type='bot'");
    }
    // Xóa thống kê theo trình duyệt
    if ($clearall or $cleartype === 'browser') {
        $db->query('UPDATE ' . NV_COUNTER_GLOBALTABLE . ' SET ' . $query_update . " WHERE c_type='browser'");
    }
    // Xóa thống kê quốc gia truy cập
    if ($clearall or $cleartype === 'country') {
        $db->query('UPDATE ' . NV_COUNTER_GLOBALTABLE . ' SET ' . $query_update . " WHERE c_type='country'");
    }
    // Xóa thống kê hệ điều hành
    if ($clearall or $cleartype === 'os') {
        $db->query('UPDATE ' . NV_COUNTER_GLOBALTABLE . ' SET ' . $query_update . " WHERE c_type='os'");
    }
    // Xóa đường dẫn đến site
    if ($clearall or $cleartype === 'referer') {
        foreach ($global_config['allow_sitelangs'] as $lang) {
            if ($alllang or $lang == NV_LANG_DATA) {
                $db->query('TRUNCATE ' . $db_config['prefix'] . '_' . $lang . '_referer_stats');
            }
        }
    }
    // Xóa bộ đếm lượt truy cập
    if ($clearall or $cleartype === 'hit') {
        $db->query('UPDATE ' . NV_COUNTER_GLOBALTABLE . ' SET ' . $query_update . " WHERE c_type IN('hour', 'dayofweek', 'day', 'month', 'year', 'total', 'c_time')");
    }

    $clearmode = $clearall ? 'All' : ucfirst($cleartype);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'Clear statistics', $clearmode, $admin_info['userid']);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getModule('clear_success')
    ]);
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('cleardata.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('LANG_MULTI', (bool) $global_config['lang_multi']);
$tpl->assign('ALLLANG', 0);
$tpl->assign('ALLLANG_MSG', $global_config['lang_multi'] ? $nv_Lang->getModule('clear_alllang_msg', $language_array[NV_LANG_DATA]['name']) : '');

$contents = $tpl->fetch('cleardata.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
