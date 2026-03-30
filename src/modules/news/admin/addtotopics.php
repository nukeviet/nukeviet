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

$page_title = $nv_Lang->getModule('addtotopics');

$id_array = [];
$listid = $nv_Request->get_string('listid', 'get,post', '');

if ($nv_Request->isset_request('topicsid', 'post')) {
    $checkss = $nv_Request->get_title('checkss', 'post', '');
    if (!csrf_check($checkss, $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $topicsid = $nv_Request->get_int('topicsid', 'post');
    $listid = array_filter(array_unique(array_map('intval', explode(',', $listid))));

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_topic', 'listid ' . implode(',', $listid), $admin_info['userid']);

    foreach ($listid as $_id) {
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET topicid=' . $topicsid . ' WHERE id=' . $_id);

        $result = $db->query('SELECT listcatid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $_id);
        [$listcatid] = $result->fetch(3);
        $listcatid = explode(',', $listcatid);

        foreach ($listcatid as $catid) {
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' SET topicid=' . $topicsid . ' WHERE id=' . $_id);
        }
    }

    $nv_Cache->delMod($module_name);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getModule('topic_update_success'),
        'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=topicsnews&topicid=' . $topicsid, true)
    ]);
}

$db->sqlreset()
    ->select('id, title')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
    ->order('id DESC');
if ($listid == '') {
    $db->where('inhome=1')->limit(20);
} else {
    $id_array = array_map('intval', explode(',', $listid));
    $db->where('inhome=1 AND id IN (' . implode(',', $id_array) . ')');
}

$result = $db->query($db->sql());

$rows = [];
while ($_scratch = $result->fetch(3)) {
    [$id, $title] = $_scratch;
    $rows[] = [
        'id' => $id,
        'title' => $title,
        'checked' => in_array((int) $id, $id_array, true)
    ];
}

$topics = [];
$result = $db->query('SELECT topicid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics ORDER BY weight ASC');
while ($row = $result->fetch()) {
    $topics[] = [
        'key' => $row['topicid'],
        'title' => $row['title']
    ];
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('addtotopics.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('ROWS', $rows);
$tpl->assign('TOPICS', $topics);

$contents = $tpl->fetch('addtotopics.tpl');

$set_active_op = 'topics';

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
