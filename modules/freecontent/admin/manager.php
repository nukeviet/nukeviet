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

$page_title = $lang_module['mng'];

// Get content info
if ($nv_Request->isset_request('getinfo', 'post')) {
    $id = $nv_Request->get_int('id', 'post', '0');

    $array = [];

    if ($id) {
        $sth = $db->prepare('SELECT title, description, link, target, image, start_time, end_time, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=:id');
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $array = $sth->fetch();

        if (!empty($array)) {
            // Check image exists
            if (!empty($array['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $array['image'])) {
                $array['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['image'];
            } else {
                $array['image'] = '';
            }

            $array['status'] = $array['status'] == 1 ? true : false;
            $array['exptime'] = 0;

            if ($array['end_time']) {
                $array['exptime'] = round(($array['end_time'] - $array['start_time']) / 3600);
            }

            unset($array['start_time'], $array['end_time']);
        }
    }

    $message = $array ? '' : 'Invalid post data';

    nv_jsonOutput([
        'status' => !empty($array) ? 'success' : 'error',
        'message' => $message,
        'data' => $array
    ]);
}

// Delete content
if ($nv_Request->isset_request('del', 'post')) {
    $id = $nv_Request->get_int('id', 'post', '0');
    $message = '';

    if ($id) {
        $sth = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=:id');
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();

        if ($sth->rowCount()) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Del Content', 'ID:' . $id, $admin_info['userid']);
            $nv_Cache->delMod($module_name);
        } else {
            $message = 'Nothing to do!';
        }
    } else {
        $message = 'Invalid post data';
    }

    nv_jsonOutput([
        'status' => !$message ? 'success' : 'error',
        'message' => $message,
    ]);
}

// Change content status
if ($nv_Request->isset_request('changestatus', 'post')) {
    $id = $nv_Request->get_int('id', 'post', '0');
    $message = '';
    $status = 0;

    if ($id) {
        $sth = $db->prepare('SELECT status, start_time, end_time FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=:id');
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $row = $sth->fetchAll();

        if (sizeof($row) == 1) {
            $row = $row[0];

            if ($row['status'] == 1) {
                // In-active

                $status = 0;
                $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET status = :status WHERE id=:id';
            } else {
                // Re-active, Active

                $status = 1;
                $start_time = 0;
                $end_time = 0;

                if (empty($row['start_time']) or (!empty($row['end_time']) and $row['end_time'] <= NV_CURRENTTIME)) {
                    $start_time = NV_CURRENTTIME;
                    $end_time = !empty($row['end_time']) ? (($row['end_time'] - $row['start_time']) + $start_time) : 0;
                } else {
                    $start_time = $row['start_time'];
                    $end_time = $row['end_time'];
                }

                $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET status = :status, start_time = ' . $start_time . ', end_time = ' . $end_time . ' WHERE id=:id';
            }

            $sth = $db->prepare($sql);
            $sth->bindParam(':status', $status, PDO::PARAM_INT);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->execute();

            // Get next execute
            $sql = 'SELECT MIN(end_time) next_execute FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE end_time > 0 AND status = 1';
            $result = $db->query($sql);
            $next_execute = (int) ($result->fetchColumn());
            $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = 'next_execute'");
            $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
            $sth->bindParam(':config_value', $next_execute, PDO::PARAM_STR);
            $sth->execute();

            nv_insert_logs(NV_LANG_DATA, $module_name, 'Change Status', 'ID:' . $id . ' - ' . $status, $admin_info['userid']);
            $nv_Cache->delMod($module_name);
        } else {
            $message = 'Nothing to do!';
        }
    } else {
        $message = 'Invalid post data';
    }

    nv_jsonOutput([
        'status' => !$message ? 'success' : 'error',
        'message' => $message,
        'responCode' => $status,
        'responText' => $lang_module['content_status_' . $status]
    ]);
}

$bid = $nv_Request->get_int('bid', 'post', '');
$block = [];

if ($bid) {
    $block = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_blocks WHERE bid=' . $bid)->fetch();
}

if (empty($block)) {
    nv_jsonOutput([
        'status' => 'error',
        'message' => 'Invalid data',
        'data' => [],
        'error' => [],
    ]);
}

// Add + Edit submit
if ($nv_Request->isset_request('submit', 'post')) {
    $data = $error = [];
    $message = '';

    $data['id'] = $nv_Request->get_int('id', 'post', 0);
    $data['title'] = nv_substr($nv_Request->get_title('title', 'post', ''), 0, 255);
    $data['description'] = $nv_Request->get_editor('description', '', NV_ALLOWED_HTML_TAGS);
    $data['link'] = nv_substr($nv_Request->get_string('link', 'post', ''), 0, 255);
    $data['target'] = nv_substr($nv_Request->get_title('target', 'post', ''), 0, 10);
    $data['image'] = nv_substr($nv_Request->get_title('image', 'post', ''), 0, 255);
    $data['status'] = ($nv_Request->get_int('status', 'post', 0) == 0) ? 0 : 1;
    $data['exptime'] = $nv_Request->get_int('exptime', 'post', 0);

    if (empty($data['title'])) {
        $error[] = [
            'name' => 'title',
            'value' => $lang_module['content_title_error']
        ];
    }

    if (!empty($data['link']) and !nv_is_url($data['link'], true)) {
        $data['link'] = '';
    }

    // Prosess image
    if (is_file(NV_DOCUMENT_ROOT . $data['image'])) {
        $data['image'] = substr($data['image'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } else {
        $data['image'] = '';
    }

    // Prosess time
    $data['start_time'] = $data['status'] ? NV_CURRENTTIME : 0;
    $data['end_time'] = $data['exptime'] ? ($data['start_time'] + ($data['exptime'] * 3600)) : 0;

    if (empty($error)) {
        if ($data['id']) {
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET
				title = :title, description = :description, link = :link, target = :target, image = :image, start_time = :start_time, end_time = :end_time, status = :status
			WHERE id = ' . $data['id'];
        } else {
            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_rows (bid, title, description, link, target, image, start_time, end_time, status) VALUES (
				' . $bid . ', :title, :description, :link, :target, :image, :start_time, :end_time, :status
			)';
        }

        try {
            $sth = $db->prepare($sql);
            $sth->bindParam(':title', $data['title'], PDO::PARAM_STR);
            $sth->bindParam(':description', $data['description'], PDO::PARAM_STR, strlen($data['description']));
            $sth->bindParam(':link', $data['link'], PDO::PARAM_STR);
            $sth->bindParam(':target', $data['target'], PDO::PARAM_STR);
            $sth->bindParam(':image', $data['image'], PDO::PARAM_STR);
            $sth->bindParam(':start_time', $data['start_time'], PDO::PARAM_INT);
            $sth->bindParam(':end_time', $data['end_time'], PDO::PARAM_INT);
            $sth->bindParam(':status', $data['status'], PDO::PARAM_INT);
            $sth->execute();

            if ($sth->rowCount()) {
                // Get next execute
                $sql = 'SELECT MIN(end_time) next_execute FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE end_time > 0 AND status = 1';
                $result = $db->query($sql);
                $next_execute = (int) ($result->fetchColumn());
                $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = 'next_execute'");
                $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
                $sth->bindParam(':config_value', $next_execute, PDO::PARAM_STR);
                $sth->execute();

                if ($data['id']) {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit Content', 'ID: ' . $data['id'], $admin_info['userid']);
                } else {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Add Content', $data['title'], $admin_info['userid']);
                }

                $nv_Cache->delMod('settings');
                $nv_Cache->delMod($module_name);
                $message = $lang_module['save_success'];
            } else {
                $error[] = [
                    'name' => '',
                    'value' => $lang_module['error_save']
                ];
            }
        } catch (PDOException $e) {
            $error[] = [
                'name' => '',
                'value' => $lang_module['error_save']
            ];
        }
    }

    nv_jsonOutput([
        'status' => empty($error) ? 'success' : 'error',
        'message' => $message,
        'error' => $error
    ]);
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
