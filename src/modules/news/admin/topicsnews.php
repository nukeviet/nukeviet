<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

// Xóa khỏi dòng sự kiện
if ($nv_Request->isset_request('action', 'post')) {
    $checkss = $nv_Request->get_string('checkss', 'post', '');
    if (!csrf_check($checkss, $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $action = $nv_Request->get_string('action', 'post');

    if ($action === 'delnews') {
        $topicid_post = $nv_Request->get_int('topicid', 'post');
        $id = $nv_Request->get_string('list', 'post');
        $arr_id = array_map('intval', array_unique(array_filter(explode(',', $id))));

        foreach ($arr_id as $id) {
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET topicid=0 WHERE id = ' . $id);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_topic_del', 'topicid: ' . $topicid_post . ', ids: ' . implode(',', $arr_id), $admin_info['userid']);

        nv_jsonOutput([
            'status' => 'OK',
            'mess' => $nv_Lang->getModule('topic_delete_success'),
            'refresh' => true
        ]);
    }
}

$topicid = $nv_Request->get_int('topicid', 'get');
$page = $nv_Request->get_page('page', 'get', 1);

$topictitle = $db_slave->query('SELECT title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE topicid =' . $topicid)->fetchColumn();
if (empty($topictitle)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=topics');
}

$page_title = $nv_Lang->getModule('topic_page') . ': ' . $topictitle;

$global_array_cat = [];

$sql = 'SELECT catid, alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat ORDER BY sort ASC';
$result = $db_slave->query($sql);
while ($_scratch = $result->fetch(3)) {
    [$catid_i, $alias_i] = $_scratch;
    unset($_scratch);
    $global_array_cat[$catid_i] = [
        'alias' => $alias_i
    ];
}
$per_page = 50;

$sql = 'SELECT count(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE topicid=' . $topicid;
$num_items = (int) $db_slave->query($sql)->fetchColumn();

$sql = 'SELECT id, catid, listcatid, alias, title, publtime, status, hitstotal, hitscm FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows
WHERE topicid=' . $topicid . ' ORDER BY ' . $order_articles_by . ' DESC LIMIT ' . $per_page . ' OFFSET ' . (($page - 1) * $per_page);
$result = $db_slave->query($sql);

$pagination = nv_generate_page(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;topicid=' . $topicid, $num_items, $per_page, $page);

$array = [];
while ($row = $result->fetch()) {
    $listcatid = array_filter(array_map('intval', explode(',', $row['listcatid'])));
    if (defined('NV_SYSTEM') and !defined('NV_IS_ADMIN_MODULE')) {
        global $admin_permissions;
        $can_edit = count(array_intersect($listcatid, $admin_permissions['edit_content'] ?? [])) > 0;
    } else {
        $can_edit = true;
    }
    $array[] = [
        'id' => (int) $row['id'],
        'title' => $row['title'],
        'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . ($global_array_cat[$row['catid']]['alias'] ?? '') . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'],
        'publtime' => nv_datetime_format($row['publtime'], 1),
        'status' => $nv_Lang->getModule('status_' . $row['status']),
        'hitstotal' => nv_number_format($row['hitstotal']),
        'hitscm' => nv_number_format($row['hitscm']),
        'can_edit' => $can_edit,
    ];
}
$result->closeCursor();

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('topicsnews.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('TOPICID', $topicid);
$tpl->assign('TOPIC_TITLE', $topictitle);
$tpl->assign('ARRAY', $array);
$tpl->assign('PAGINATION', $pagination);
$tpl->assign('NUM_ITEMS', $num_items);

$contents = $tpl->fetch('topicsnews.tpl');

$set_active_op = 'topics';
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
