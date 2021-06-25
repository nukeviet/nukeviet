<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$topicid = $nv_Request->get_int('topicid', 'get');
$page = $nv_Request->get_int('page', 'get', 1);

$topictitle = $db_slave->query('SELECT title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE topicid =' . $topicid)->fetchColumn();
if (empty($topictitle)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=topics');
}

$page_title = $lang_module['topic_page'] . ': ' . $topictitle;

$global_array_cat = [];

$sql = 'SELECT catid, alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat ORDER BY sort ASC';
$result = $db_slave->query($sql);
while (list($catid_i, $alias_i) = $result->fetch(3)) {
    $global_array_cat[$catid_i] = [
        'alias' => $alias_i
    ];
}
$per_page = 50;

$sql = 'SELECT count(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE topicid=' . $topicid;
$num_items = $db_slave->query($sql)->fetchColumn();

$sql = 'SELECT id, catid, alias, title, publtime, status, hitstotal, hitscm FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE topicid=' . $topicid . ' ORDER BY ' . $order_articles_by . ' DESC LIMIT ' . $per_page . ' OFFSET ' . (($page - 1) * $per_page);
$result = $db_slave->query($sql);

$generate_page = nv_generate_page(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;topicid=' . $topicid, $num_items, $per_page, $page);

$xtpl = new XTemplate('topicsnews.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('TOPICID', $topicid);
$xtpl->assign('GENERATE_PAGE', $generate_page);

$i = 0;
while ($row = $result->fetch()) {
    ++$i;
    $row['publtime'] = nv_date('H:i d/m/y', $row['publtime']);
    $row['status'] = $lang_module['status_' . $row['status']];
    $row['hitstotal'] = number_format($row['hitstotal'], 0, ',', '.');
    $row['hitscm'] = number_format($row['hitscm'], 0, ',', '.');
    $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$row['catid']]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];
    $row['delete'] = nv_link_edit_page($row['id']);
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.data.loop');
}
$result->closeCursor();

if ($i) {
    $xtpl->assign('URL_DELETE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=topicdelnews');
    $xtpl->parse('main.data');
} else {
    $xtpl->parse('main.empty');
}
$xtpl->parse('main');
$contents = $xtpl->text('main');

$set_active_op = 'topics';
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
