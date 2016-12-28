<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['addtotopics'];

$id_array = array();
$listid = $nv_Request->get_string('listid', 'get,post', '');

if ($nv_Request->isset_request('topicsid', 'post')) {
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_topic', 'listid ' . $listid, $admin_info['userid']);

    $topicsid = $nv_Request->get_int('topicsid', 'post');
    $listid = array_filter(array_unique(array_map('trim', explode(',', $listid))));

    foreach ($listid as $_id) {
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET topicid=' . $topicsid . ' WHERE id=' . $_id);

        $result = $db->query('SELECT listcatid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $_id);
        list($listcatid) = $result->fetch(3);
        $listcatid = explode(',', $listcatid);

        foreach ($listcatid as $catid) {
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' SET topicid=' . $topicsid . ' WHERE id=' . $_id);
        }
    }

    $nv_Cache->delMod($module_name);

    exit($lang_module['topic_update_success']);
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

$xtpl = new XTemplate('addtotopics.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

while (list($id, $title) = $result->fetch(3)) {
    $xtpl->assign('ROW', array(
        'id' => $id,
        'title' => $title,
        'checked' => in_array($id, $id_array) ? ' checked="checked"' : ''
    ));

    $xtpl->parse('main.loop');
}

$result = $db->query('SELECT topicid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics ORDER BY weight ASC');
while ($row = $result->fetch()) {
    $xtpl->assign('TOPICSID', array( 'key' => $row['topicid'], 'title' => $row['title'] ));
    $xtpl->parse('main.topicsid');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$set_active_op = 'topics';
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
