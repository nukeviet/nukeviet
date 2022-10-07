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
    die('Stop!!!');
}

$page_title = $lang_module['voice_manager'];

// Thay đổi thứ tự
if ($nv_Request->get_title('changeweight', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $new_weight = $nv_Request->get_int('new_weight', 'post', 0);

    // Kiểm tra tồn tại
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voices WHERE id=' . $id;
    $array = $db->query($sql)->fetch();
    if (empty($array)) {
        nv_htmlOutput('NO_' . $id);
    }
    if (empty($new_weight)) {
        nv_htmlOutput('NO_' . $id);
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
    nv_htmlOutput('OK_' . $id);
}

// Thay đổi hoạt động
if ($nv_Request->get_title('changestatus', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_int('id', 'post', 0);

    // Kiểm tra tồn tại
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voices WHERE id=' . $id;
    $array = $db->query($sql)->fetch();
    if (empty($array)) {
        nv_htmlOutput('NO_' . $id);
    }

    $status = empty($array['status']) ? 1 : 0;

    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_voices SET status = " . $status . " WHERE id = " . $id;
    $db->query($sql);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_CHANGE_STATUS_VOICE', $id . ': ' . $array['title'], $admin_info['admin_id']);
    $nv_Cache->delMod($module_name);

    nv_htmlOutput("OK");
}

// Xóa
if ($nv_Request->get_title('delete', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_int('id', 'post', 0);

    // Kiểm tra tồn tại
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voices WHERE id=' . $id;
    $array = $db->query($sql)->fetch();
    if (empty($array)) {
        nv_htmlOutput('NO_' . $id);
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

    nv_htmlOutput("OK");
}

$array = [];
$error = '';

$id = $nv_Request->get_int('id', 'get', 0);

if (!empty($id)) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voices WHERE id = ' . $id;
    $result = $db->query($sql);
    $array = $result->fetch();

    if (empty($array)) {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content']);
    }

    $caption = $lang_module['voice_edit'];
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id;
} else {
    $array = [
        'id' => 0,
        'voice_key' => '',
        'title' => '',
        'description' => '',
    ];

    $caption = $lang_module['voice_add'];
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

if ($nv_Request->isset_request('save', 'post')) {
    $array['title'] = nv_substr($nv_Request->get_title('title', 'post', ''), 0, 250);
    $array['voice_key'] = nv_substr($nv_Request->get_title('voice_key', 'post', ''), 0, 250);
    $array['description'] = $nv_Request->get_string('description', 'post', '');

    // Xử lý dữ liệu
    $array['description'] = nv_nl2br(nv_htmlspecialchars(strip_tags($array['description'])), '<br />');

    // Kiểm tra trùng
    $is_exists = false;
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voices WHERE title = :title' . ($id ? ' AND id != ' . $id : '');
    $sth = $db->prepare($sql);
    $sth->bindParam(':title', $array['title'], PDO::PARAM_STR);
    $sth->execute();
    if ($sth->fetchColumn()) {
        $is_exists = true;
    }

    if (empty($array['title'])) {
        $error = $lang_module['voice_error_title'];
    } elseif ($is_exists) {
        $error = $lang_module['voice_error_exists'];
    } else {
        if (!$id) {
            $sql = 'SELECT MAX(weight) weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voices';
            $weight = intval($db->query($sql)->fetchColumn()) + 1;

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
            $sth->bindParam(':voice_key', $array['voice_key'], PDO::PARAM_STR);
            $sth->bindParam(':title', $array['title'], PDO::PARAM_STR);
            $sth->bindParam(':description', $array['description'], PDO::PARAM_STR, strlen($array['description']));
            $sth->execute();

            if ($sth->rowCount()) {
                if ($id) {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_VOICE', 'ID: ' . $id . ':' . $array['title'], $admin_info['userid']);
                } else {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_VOICE', $array['title'], $admin_info['userid']);
                }

                $nv_Cache->delMod($module_name);
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
            } else {
                $error = $lang_module['errorsave'];
            }
        } catch (PDOException $e) {
            $error = $lang_module['errorsave'];
        }
    }
}

$array['description'] = nv_br2nl($array['description']);

$xtpl = new XTemplate('voices.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('CAPTION', $caption);
$xtpl->assign('FORM_ACTION', $form_action);
$xtpl->assign('DATA', $array);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voices ORDER BY weight ASC';
$array_voices = $db->query($sql)->fetchAll();
$num = sizeof($array_voices);

foreach ($array_voices as $row) {
    $row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $row['id'];
    $row['status_render'] = empty($row['status']) ? '' : ' checked="checked"';

    for ($i = 1; $i <= $num; ++$i) {
        $xtpl->assign('WEIGHT', [
            'w' => $i,
            'selected' => ($i == $row['weight']) ? ' selected="selected"' : ''
        ]);

        $xtpl->parse('main.loop.weight');
    }

    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.loop');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
