<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$mark = $nv_Request->get_title('mark', 'post', '');

if (!empty($mark) and ($mark == 'read' or $mark == 'unread')) {
    $mark = $mark == 'read' ? 1 : 0;
    $sends = $nv_Request->get_array('sends', 'post', array());
    if (empty($sends)) {
        nv_jsonOutput(array( 'status' => 'error', 'mess' => $lang_module['please_choose'] ));
    }

    foreach ($sends as $id) {
        nv_status_notification(NV_LANG_DATA, $module_name, 'contact_new', $id, $mark);
    }

    $sends = implode(',', $sends);
    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_send SET is_read=' . $mark . ' WHERE id IN (' . $sends . ')');
    nv_jsonOutput(array( 'status' => 'ok', 'mess' => '' ));
}

$page_title = $module_info['site_title'];

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MODULE_NAME', $module_name);

$contact_allowed = nv_getAllowed();

if (!empty($contact_allowed['view'])) {
    $in = implode(',', array_keys($contact_allowed['view']));

    $page = $nv_Request->get_int('page', 'get', 1);
    $per_page = 30;
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;

    $db->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_send')
        ->where('cid IN (' . $in . ')');

    $num_items = $db->query($db->sql())->fetchColumn();

    if ($num_items) {
        $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=del&amp;t=2');

        $a = 0;
        $currday = mktime(0, 0, 0, date('n'), date('j'), date('Y'));

        $db->select('*')
            ->order('id DESC')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $result = $db->query($db->sql());

        while ($row = $result->fetch()) {
            $image = array( NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/mail_new.gif', 12, 9 );
            $status = 'New';
            $style = " style=\"font-weight:bold;cursor:pointer;white-space:nowrap;\"";

            if ($row['is_read'] == 1) {
            	$style = " style=\"cursor:pointer;white-space:nowrap;\"";
	            if ($row['is_reply']==1) {
	                $image = array( NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/mail_reply.gif', 13, 14 );
	                $status = $lang_module['tt2_row_title'];
	            }elseif ($row['is_reply']==2) {
	                $image = array( NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/mail_forward.gif', 13, 14 );
	                $status = $lang_module['tt2_row_title'];
	            }else{
	                $image = array( NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/mail_old.gif', 12, 11 );
	                $status = $lang_module['tt1_row_title'];
	            }
            }

            $onclick = "onclick=\"location.href='" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=view&amp;id=" . $row['id'] . "'\"";

            $xtpl->assign('ROW', array(
                'id' => $row['id'],
                'sender_name' => $row['sender_name'],
                'path' => $contact_allowed['view'][$row['cid']],
                'cat' => $row['cat'],
                'title' => nv_clean60($row['title'], 60),
                'time' => $row['send_time'] >= $currday ? nv_date('H:i d/m/Y', $row['send_time']) : nv_date('d/m/Y', $row['send_time']),
                'style' => $style,
                'onclick' => $onclick,
                'status' => $status,
                'image' => $image
            ));

            $xtpl->parse('main.data.row');
        }

        $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.data.generate_page');
        }
    }
}

if (empty($num_items)) {
    $xtpl->parse('main.empty');
} else {
    $xtpl->parse('main.data');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
