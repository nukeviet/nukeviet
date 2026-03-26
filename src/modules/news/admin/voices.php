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

$page_title = $nv_Lang->getModule('voice_manager');

// Thay đổi thứ tự
if ($nv_Request->isset_request('changeweight', 'post')) {
    if (!csrf_check($nv_Request->get_title('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $id = $nv_Request->get_int('id', 'post', 0);
    $new_weight = $nv_Request->get_int('new_weight', 'post', 0);

    // Kiểm tra tồn tại
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voices WHERE id=' . $id;
    $array = $db->query($sql)->fetch();
    if (empty($array)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => ''
        ]);
    }
    if (empty($new_weight)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => ''
        ]);
    }

    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voices WHERE id!=' . $id . ' ORDER BY weight ASC';
    $result = $db->query($sql);

    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_voices SET weight=' . $weight . ' WHERE id=' . $row['id'];
        $db->query($sql);
    }

    $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_voices SET weight=' . $new_weight . ' WHERE id=' . $id;
    $db->query($sql);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_CHANGE_WEIGHT_VOICE', $id . ': ' . $array['title'], $admin_info['admin_id']);
    $nv_Cache->delMod($module_name);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => ''
    ]);
}

// Thay đổi trạng thái hoạt động
if ($nv_Request->isset_request('changestatus', 'post')) {
    if (!csrf_check($nv_Request->get_title('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $id = $nv_Request->get_int('id', 'post', 0);

    // Kiểm tra tồn tại
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voices WHERE id=' . $id;
    $array = $db->query($sql)->fetch();
    if (empty($array)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => ''
        ]);
    }

    $status = empty($array['status']) ? 1 : 0;

    $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_voices SET status = ' . $status . ' WHERE id = ' . $id;
    $db->query($sql);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_CHANGE_STATUS_VOICE', $id . ': ' . $array['title'], $admin_info['admin_id']);
    $nv_Cache->delMod($module_name);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => ''
    ]);
}

// Xóa
if ($nv_Request->isset_request('delete', 'post')) {
    if (!csrf_check($nv_Request->get_title('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $id = $nv_Request->get_int('id', 'post', 0);

    // Kiểm tra tồn tại
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voices WHERE id=' . $id;
    $array = $db->query($sql)->fetch();
    if (empty($array)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => ''
        ]);
    }

    // Xóa
    $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voices WHERE id=' . $id;
    $db->query($sql);

    // Cập nhật thứ tự
    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voices ORDER BY weight ASC';
    $result = $db->query($sql);
    $weight = 0;

    while ($row = $result->fetch()) {
        ++$weight;
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_voices SET weight=' . $weight . ' WHERE id=' . $row['id'];
        $db->query($sql);
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_VOICE', $id . ': ' . $array['title'], $admin_info['admin_id']);
    $nv_Cache->delMod($module_name);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => ''
    ]);
}

$item = [
    'id' => 0,
    'voice_key' => '',
    'title' => '',
    'description' => '',
];

$id = $nv_Request->get_int('id', 'get', 0);

if (!empty($id)) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voices WHERE id = ' . $id;
    $row = $db->query($sql)->fetch();

    if (empty($row)) {
        nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'));
    }

    $item = $row;
}

if ($nv_Request->isset_request('save', 'post')) {
    $item['title'] = nv_substr($nv_Request->get_title('title', 'post', ''), 0, 250);
    $item['voice_key'] = nv_substr($nv_Request->get_title('voice_key', 'post', ''), 0, 250);
    $item['description'] = $nv_Request->get_string('description', 'post', '');

    // Xử lý dữ liệu
    $item['description'] = nv_nl2br(nv_htmlspecialchars(strip_tags($item['description'])), '<br />');

    // Kiểm tra trùng
    $is_exists = false;
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voices WHERE title = :title' . ($id ? ' AND id != ' . $id : '');
    $sth = $db->prepare($sql);
    $sth->bindParam(':title', $item['title'], PDO::PARAM_STR);
    $sth->execute();
    if ($sth->fetchColumn()) {
        $is_exists = true;
    }

    if (empty($item['title'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('voice_error_title'),
            'input' => 'title'
        ]);
    }
    if ($is_exists) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('voice_error_exists'),
            'input' => 'title'
        ]);
    }

    if (!$id) {
        $sql = 'SELECT MAX(weight) weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voices';
        $weight = (int) ($db->query($sql)->fetchColumn()) + 1;

        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_voices (
            voice_key, title, description, weight, add_time, edit_time
        ) VALUES (
            :voice_key, :title, :description, ' . $weight . ', ' . NV_CURRENTTIME . ', 0
        )';
    } else {
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_voices SET
            voice_key=:voice_key, title = :title, description = :description, edit_time = ' . NV_CURRENTTIME . '
        WHERE id = ' . $id;
    }

    try {
        $sth = $db->prepare($sql);
        $sth->bindParam(':voice_key', $item['voice_key'], PDO::PARAM_STR);
        $sth->bindParam(':title', $item['title'], PDO::PARAM_STR);
        $sth->bindParam(':description', $item['description'], PDO::PARAM_STR, strlen($item['description']));
        $sth->execute();

        if ($sth->rowCount()) {
            if ($id) {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_VOICE', 'ID: ' . $id . ':' . $item['title'], $admin_info['userid']);
            } else {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_VOICE', $item['title'], $admin_info['userid']);
            }

            $nv_Cache->delMod($module_name);
            nv_jsonOutput([
                'status' => 'OK',
                'mess' => $nv_Lang->getGlobal('save_success'),
                'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true)
            ]);
        }

        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('errorsave')
        ]);
    } catch (Throwable $e) {
        trigger_error($e);
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('errorsave')
        ]);
    }
}

$item['description'] = nv_br2nl($item['description']);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voices ORDER BY weight ASC';
$voices = $db->query($sql)->fetchAll();
$num_voices = count($voices);

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('voices.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('IS_EDIT', !empty($id));
$tpl->assign('ITEM', $item);
$tpl->assign('VOICES', $voices);
$tpl->assign('NUM_VOICES', $num_voices);

$contents = $tpl->fetch('voices.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
