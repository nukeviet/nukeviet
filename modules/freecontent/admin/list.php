<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 24-06-2011 10:35
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$bid = $nv_Request->get_int('bid', 'get', '');
$block = array();

if ($bid) {
    $block = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_blocks WHERE bid=' . $bid)->fetch();
}

$page_title = $lang_module['content_list'] . ': ' . $block['title'];

// Write row
$xtpl = new XTemplate('list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$allow_editor = (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) ? true : false;

if (! defined('CKEDITOR') and $allow_editor) {
    define('CKEDITOR', true);
    $my_head .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . NV_EDITORSDIR . '/ckeditor/ckeditor.js?t=' . $global_config['timestamp'] . '"></script>';
}

$xtpl->assign('EDITOR', $allow_editor ? 'true' : 'false');
$xtpl->assign('UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('BID', $bid);

$sql = 'SELECT id, title, description, link, image, start_time, end_time, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE bid=' . $bid . ' ORDER BY bid DESC';
$array = $db->query($sql)->fetchAll();
$num_rows = sizeof($array);

if ($num_rows < 1) {
    $xtpl->parse('main.empty');
} else {
    $xtpl->assign('NUM_ROWS', $num_rows);
    
    foreach ($array as $row) {
        if (! empty($row['image'])) {
            if (file_exists(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/' . $row['image'])) {
                $row['image'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $row['image'];
            } elseif (file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'])) {
                $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
            } else {
                $row['image'] = '';
            }
        }
        
        $row['status_text'] = $lang_module['content_status_' . $row['status']];
        
        if ($row['start_time'] > 0) {
            $row['status_text'] .= '. ' . $lang_module['content_status_note0'] . ' ' . nv_date('H:i:s d/m/Y', $row['start_time']);
            
            if ($row['end_time'] > 0) {
                $row['status_text'] .= '. ' .  sprintf($row['status'] == 2 ? $lang_module['content_status_note2'] : $lang_module['content_status_note1'], nv_date('H:i:s d/m/Y', $row['end_time']));
            }
        }
        
        $xtpl->assign('ROW', $row);
        
        if (! empty($row['image'])) {
            $xtpl->parse('main.rows.loop.image');
        }
        
        $xtpl->parse('main.rows.loop');
    }
    
    $xtpl->parse('main.rows');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
