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

use NukeViet\Module\news\Shared\Emails;

$action = $nv_Request->get_title('action', 'post', '');

// Xóa báo cáo lỗi
if (($action == 'del_action' or $action == 'del_mail_action') and $nv_Request->isset_request('rid', 'post')) {
    $rid = $nv_Request->get_int('rid', 'post', 0);
    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key) or empty($rid)) {
        nv_jsonOutput([
            'success' => 0,
            'text' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $stmt = $db->prepare('SELECT id, post_email FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE id = :rid');
    $stmt->bindValue(':rid', $rid, PDO::PARAM_INT);
    $stmt->execute();
    $report_rows = $stmt->fetch();
    $stmt->closeCursor();

    if (empty($report_rows)) {
        nv_jsonOutput([
            'success' => 0,
            'text' => 'Not exists!!!'
        ]);
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'DEL_REPORT_ID', $rid . '+' . $action, $admin_info['userid']);
    
    $stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE id = :rid');
    $stmt->bindValue(':rid', $rid, PDO::PARAM_INT);
    $stmt->execute();

    nv_delete_notification(NV_LANG_DATA, $module_name, 'report', $rid);
    if ($action == 'del_mail_action' and !empty($report_rows['post_email'])) {
        $maillang = NV_LANG_INTERFACE;
        if (NV_LANG_DATA != NV_LANG_INTERFACE) {
            $maillang = NV_LANG_DATA;
        }

        $send_data = [[
            'to' => $report_rows['post_email']
        ]];
        nv_sendmail_template_async([$module_file, Emails::REPORT_THANKS], $send_data, $maillang);
    }
    nv_jsonOutput([
        'success' => 1
    ]);
}

// Xóa hàng loạt báo cáo lỗi
if ($action == 'multidel' and $nv_Request->isset_request('list', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'success' => 0,
            'text' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $list = $nv_Request->get_typed_array('list', 'post', 'int', []);
    if (!empty($list)) {
        foreach ($list as $rid) {
            nv_delete_notification(NV_LANG_DATA, $module_name, 'report', $rid);
        }
        
        $ids = implode(',', $list);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'DEL_REPORT_IDS', $ids, $admin_info['userid']);
        
        $stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE id IN (' . $ids . ')');
        $stmt->execute();
    }
    nv_jsonOutput([
        'success' => 1
    ]);
}

$page_title = $nv_Lang->getModule('report');

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
$page = $nv_Request->get_page('page', 'get', 1);
$per_page = 20;

$stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report AS r INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_rows n ON r.newsid=n.id');
$stmt->execute();
$num_items = $stmt->fetchColumn();

if (empty($num_items)) {
    $contents = nv_theme_alert('', $nv_Lang->getModule('report_empty'));
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$offset = ($page - 1) * $per_page;
$stmt = $db->prepare('SELECT r.*, n.title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report r INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_rows n ON r.newsid=n.id ORDER BY r.post_time DESC LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$array = [];
while ($_row = $stmt->fetch()) {
    $_row['url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $_row['newsid'] . '&amp;rid=' . $_row['id'];
    $_row['orig_content_short'] = text_split($_row['orig_content'], 50);
    $_row['orig_content_short'] = $_row['orig_content_short'][0] . (!empty($_row['orig_content_short'][1]) ? '...' : '');
    $_row['post_time_format'] = nv_datetime_format($_row['post_time']);
    $array[] = $_row;
}
$stmt->closeCursor();

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('report.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('ROWS', $array);
$tpl->assign('GENERATE_PAGE', $generate_page);

$contents = $tpl->fetch('report.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
