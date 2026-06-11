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

$page_title = $nv_Lang->getModule('topics_add');

$id_array = [];
$listid = $nv_Request->get_string('listid', 'get,post', '');
$action_csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_action';

if ($nv_Request->isset_request('topicsid', 'post')) {
    $checkss = $nv_Request->get_string('checkss', 'post', '');
    if (!csrf_check($checkss, $action_csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $topicsid = $nv_Request->get_int('topicsid', 'post');
    $listid = array_filter(array_unique(array_map('intval', explode(',', $listid))));

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_topic', 'listid ' . implode(',', $listid), $admin_info['userid']);

    $stmt_upd_rows = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET topicid = :topicid WHERE id = :id');
    $stmt_sel_cat = $db->prepare('SELECT listcatid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = :id');

    foreach ($listid as $_id) {
        $stmt_upd_rows->bindValue(':topicid', $topicsid, PDO::PARAM_INT);
        $stmt_upd_rows->bindValue(':id', $_id, PDO::PARAM_INT);
        $stmt_upd_rows->execute();

        $stmt_sel_cat->bindValue(':id', $_id, PDO::PARAM_INT);
        $stmt_sel_cat->execute();
        $_row_cat = $stmt_sel_cat->fetch();
        $stmt_sel_cat->closeCursor();
        $listcatid = explode(',', $_row_cat['listcatid'] ?? '');

        foreach ($listcatid as $catid) {
            $stmt_upd_cat = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . intval($catid) . ' SET topicid = :topicid WHERE id = :id');
            $stmt_upd_cat->bindValue(':topicid', $topicsid, PDO::PARAM_INT);
            $stmt_upd_cat->bindValue(':id', $_id, PDO::PARAM_INT);
            $stmt_upd_cat->execute();
        }
    }

    $nv_Cache->delMod($module_name);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getModule('topic_update_success'),
        'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=topics-news&topicid=' . $topicsid, true)
    ]);
}

if ($listid == '') {
    $result = $db->query('SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE inhome=1 ORDER BY id DESC LIMIT 20');
} else {
    $id_array = array_map('intval', explode(',', $listid));
    $result = $db->query('SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE inhome=1 AND id IN (' . implode(',', $id_array) . ') ORDER BY id DESC');
}

$rows = [];
while ($_row = $result->fetch()) {
    $rows[] = [
        'id' => (int) $_row['id'],
        'title' => $_row['title'],
        'checked' => in_array((int) $_row['id'], $id_array, true)
    ];
}
$result->closeCursor();

$topics = [];
$result = $db->query('SELECT topicid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics ORDER BY weight ASC');
while ($row = $result->fetch()) {
    $topics[] = [
        'key' => $row['topicid'],
        'title' => $row['title']
    ];
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('topics-add.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($action_csrf_key));
$tpl->assign('ROWS', $rows);
$tpl->assign('TOPICS', $topics);

$contents = $tpl->fetch('topics-add.tpl');

$set_active_op = 'topics';

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
