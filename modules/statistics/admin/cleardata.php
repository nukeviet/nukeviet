<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_STATISTICS')) {
    exit('Stop!!!');
}

$page_title = $lang_module['cleardata'];

$xtpl = new XTemplate('cleardata.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

if ($nv_Request->isset_request('save', 'post')) {
    $clearall = $nv_Request->isset_request('all', 'post');
    $alllang = $nv_Request->get_int('alllang', 'post', 0);
    $clearmode = '';

    $query_update = [];
    $query_update[] = 'last_update=0';
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
    if ($clearall or $nv_Request->isset_request('bot', 'post')) {
        $clearmode = 'Bot';
        $db->query('UPDATE ' . NV_COUNTER_GLOBALTABLE . ' SET ' . $query_update . " WHERE c_type='bot'");
    }
    // Xóa thống kê theo trình duyệt
    if ($clearall or $nv_Request->isset_request('browser', 'post')) {
        $clearmode = 'Browser';
        $db->query('UPDATE ' . NV_COUNTER_GLOBALTABLE . ' SET ' . $query_update . " WHERE c_type='browser'");
    }
    // Xóa thống kê quốc gia truy cập
    if ($clearall or $nv_Request->isset_request('country', 'post')) {
        $clearmode = 'Country';
        $db->query('UPDATE ' . NV_COUNTER_GLOBALTABLE . ' SET ' . $query_update . " WHERE c_type='country'");
    }
    // Xóa thống kê máy chủ tìm kiếm
    if ($clearall or $nv_Request->isset_request('os', 'post')) {
        $clearmode = 'OS';
        $db->query('UPDATE ' . NV_COUNTER_GLOBALTABLE . ' SET ' . $query_update . " WHERE c_type='os'");
    }
    // Xóa đường dẫn đến site
    if ($clearall or $nv_Request->isset_request('referer', 'post')) {
        $clearmode = 'Referer';
        foreach ($global_config['allow_sitelangs'] as $lang) {
            if ($alllang or $lang == NV_LANG_DATA) {
                $db->query('TRUNCATE ' . $db_config['prefix'] . '_' . $lang . '_referer_stats');
            }
        }
    }
    // Xóa bộ đếm lượt truy cập
    if ($clearall or $nv_Request->isset_request('hit', 'post')) {
        $db->query('UPDATE ' . NV_COUNTER_GLOBALTABLE . ' SET ' . $query_update . " WHERE c_type IN('hour', 'dayofweek', 'day', 'month', 'year', 'total', 'c_time')");
    }

    $clearmode = $clearall ? 'All' : $clearmode;

    $db->query('OPTIMIZE TABLE ' . NV_COUNTER_GLOBALTABLE);
    $db->query('OPTIMIZE TABLE ' . NV_REFSTAT_TABLE);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Clear statistics', $clearmode, $admin_info['userid']);
    $xtpl->parse('main.result');
} else {
    $alllang = 0;
}

if ($global_config['lang_multi']) {
    $xtpl->assign('ALLLANG_MSG', sprintf($lang_module['clear_alllang_msg'], $language_array[NV_LANG_DATA]['name']));
    $xtpl->assign('ALLLANG', $alllang ? ' checked="checked"' : '');
    $xtpl->parse('main.clearalllang1');
    $xtpl->parse('main.clearalllang2');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
