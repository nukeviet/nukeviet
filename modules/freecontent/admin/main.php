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

$page_title = $lang_module['block_list'];

// Get block info
if ($nv_Request->isset_request('getinfo', 'post')) {
    $bid = $nv_Request->get_int('bid', 'post', '0');

    $array = array();

    if ($bid) {
        $sth = $db->prepare('SELECT title, description FROM ' . NV_PREFIXLANG . '_' . $module_data . '_blocks WHERE bid=:bid');
        $sth->bindParam(':bid', $bid, PDO::PARAM_INT);
        $sth->execute();
        $array = $sth->fetch();
    }

    $message = $array ? '' : 'Invalid post data';

    nv_jsonOutput(array(
        'status' => ! empty($array) ? 'success' : 'error',
        'message' => $message,
        'data' => $array
    ));
}

// Delete block
if ($nv_Request->isset_request('del', 'post')) {
    $bid = $nv_Request->get_int('bid', 'post', '0');
    $message = '';

    if ($bid) {
        $sth = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_blocks WHERE bid=:bid');
        $sth->bindParam(':bid', $bid, PDO::PARAM_INT);
        $sth->execute();

        if ($sth->rowCount()) {
            $sth = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE bid=:bid');
            $sth->bindParam(':bid', $bid, PDO::PARAM_INT);
            $sth->execute();

            nv_insert_logs(NV_LANG_DATA, $module_name, 'Del Block', 'ID:' . $bid, $admin_info['userid']);
            $nv_Cache->delMod($module_name);
        } else {
            $message = 'Nothing to do!';
        }
    } else {
        $message = 'Invalid post data';
    }

    nv_jsonOutput(array(
        'status' => ! $message ? 'success' : 'error',
        'message' => $message,
    ));
}

// Add + Edit submit
if ($nv_Request->isset_request('submit', 'post')) {
    $data = $error = array();
    $message = '';

    $data['bid'] = $nv_Request->get_int('bid', 'post', 0);
    $data['title'] = nv_substr($nv_Request->get_title('title', 'post', ''), 0, 255);
    $data['description'] = $nv_Request->get_title('description', 'post', '');

    if (empty($data['title'])) {
        $error[] = array(
            'name' => 'title',
            'value' => $lang_module['block_title_error']
        );
    } else {
        if ($data['bid']) {
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_blocks SET title = :title, description = :description WHERE bid = ' . $data['bid'];
        } else {
            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_blocks (title, description) VALUES (:title, :description)';
        }

        try {
            $sth = $db->prepare($sql);
            $sth->bindParam(':title', $data['title'], PDO::PARAM_STR);
            $sth->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $sth->execute();

            if ($sth->rowCount()) {
                if ($data['bid']) {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit Block', 'ID: ' . $data['bid'], $admin_info['userid']);
                } else {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Add Block', $data['title'], $admin_info['userid']);
                }

                $nv_Cache->delMod($module_name);
                $message = $lang_module['save_success'];
            } else {
                $error[] = array(
                    'name' => '',
                    'value' => $lang_module['error_save']
                );
            }
        } catch (PDOException $e) {
            $error[] = array(
                'name' => '',
                'value' => $lang_module['error_save']
            );
        }
    }

    nv_jsonOutput(array(
        'status' => empty($error) ? 'success' : 'error',
        'message' => $message,
        'error' => $error
    ));
}

// Write row
$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_blocks ORDER BY bid DESC';
$array = $db->query($sql)->fetchAll();

if (sizeof($array) < 1) {
    $xtpl->parse('main.empty');
} else {
    foreach ($array as $row) {
        $row['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=list&amp;bid=' . $row['bid'];

        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.rows.loop');
    }

    $xtpl->parse('main.rows');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';