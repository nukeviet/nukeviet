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

$grouplist = groups_list();

// Kết quả tìm kiếm thành viên
if ($nv_Request->isset_request('get_user_json', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $q = $nv_Request->get_title('q', 'post', '');
    $grid = $nv_Request->get_int('grid', 'post', 0);

    if (!empty($grid) and isset($grouplist[$grid])) {
        $where = '(username LIKE :username OR email LIKE :email OR first_name like :first_name OR last_name like :last_name) AND userid IN (SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE group_id = :grid)';
    } else {
        $where = '(username LIKE :username OR email LIKE :email OR first_name like :first_name OR last_name like :last_name)';
    }
    $sth = $db->prepare('SELECT userid, username, email, first_name, last_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE ' . $where . ' ORDER BY username ASC LIMIT 20');
    $sth->bindValue(':username', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':email', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':first_name', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':last_name', '%' . $q . '%', PDO::PARAM_STR);
    if (!empty($grid) and isset($grouplist[$grid])) {
        $sth->bindValue(':grid', $grid, PDO::PARAM_INT);
    }
    $sth->execute();

    $data = [];
    while ($row = $sth->fetch()) {
        $full_name = $global_config['name_show'] ? [$row['first_name'], $row['last_name']] : [$row['last_name'], $row['first_name']];
        $full_name = array_filter($full_name);
        $data[] = [
            'id' => $row['userid'],
            'username' => $row['username'],
            'fullname' => implode(' ', $full_name),
            'email' => $row['email']
        ];
    }
    $sth->closeCursor();

    nv_jsonOutput($data);
}

$page_url = $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$adminlist = admins_list();

$where = [];
$params = [];
if (!defined('NV_IS_SPADMIN')) {
    $where[] = '(mtb.sender_admin = :sender_admin)';
    $params[':sender_admin'] = [$admin_info['admin_id'], PDO::PARAM_INT];
}

$action = $nv_Request->get_title('action', 'post', '');

// Xóa thông báo
if ($action == 'inform_del') {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $id = $nv_Request->get_int('id', 'post', 0);
    if ($id) {
        $where[] = '(mtb.id = :id)';
        $where_str = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';

        $sth = $db->prepare('SELECT COUNT(*) FROM ' . NV_INFORM_GLOBALTABLE . ' AS mtb' . $where_str);
        $sth->bindValue(':id', $id, PDO::PARAM_INT);
        foreach ($params as $key => $val) {
            $sth->bindValue($key, $val[0], $val[1]);
        }
        $sth->execute();
        $num_items = $sth->fetchColumn();
        $sth->closeCursor();

        if ($num_items) {
            $sth = $db->prepare('DELETE FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' WHERE pid = :pid');
            $sth->bindValue(':pid', $id, PDO::PARAM_INT);
            $sth->execute();

            $sth = $db->prepare('DELETE FROM ' . NV_INFORM_GLOBALTABLE . ' WHERE id = :id');
            $sth->bindValue(':id', $id, PDO::PARAM_INT);
            $sth->execute();

            nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete inform', $id, $admin_info['userid']);
            nv_jsonOutput([
                'status' => 'OK',
                'mess' => $nv_Lang->getGlobal('save_success')
            ]);
        }
    }
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'ERROR'
    ]);
}

// Thêm/sửa thông báo
if ($action == 'inform_action') {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $id = $nv_Request->get_int('id', 'post', 0);
    $data = [
        'id' => 0,
        'sender_role' => 'admin',
        'sender_group' => 0,
        'sender_group_disabled' => 1,
        'sender_admin_disabled' => 0,
        'receiver_type' => 'ids',
        'receiver_grs_disabled' => 1,
        'receiver_ids_disabled' => 0,
        'is_receiver_grs' => 0,
        'sender_admin' => $admin_info['admin_id'],
        'add_time' => NV_CURRENTTIME,
        'exp_time' => NV_CURRENTTIME + $global_config['inform_default_exp']
    ];
    if (!empty($id)) {
        $where[] = '(mtb.id = :id)';

        $sth = $db->prepare('SELECT * FROM ' . NV_INFORM_GLOBALTABLE . ' AS mtb WHERE ' . implode(' AND ', $where));
        $sth->bindValue(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $data = $sth->fetch();
        $sth->closeCursor();

        if (empty($data)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('notification_not_exist')
            ]);
        }
    }

    if ($nv_Request->isset_request('save', 'post')) {
        // CSRF check is already here, but keep it for consistency
        if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_checkss')
            ]);
        }
        $postdata = [
            'sender_role' => $nv_Request->get_title('sender_role', 'post', ''),
            'sender_group' => $nv_Request->get_int('sender_group', 'post', 0),
            'sender_admin' => $nv_Request->get_int('sender_admin', 'post', 0),
            'receiver_type' => $nv_Request->get_title('receiver_type', 'post', ''),
            'receiver_grs' => $nv_Request->get_typed_array('receiver_grs', 'post', 'int', []),
            'receiver_ids' => $nv_Request->get_typed_array('receiver_ids', 'post', 'int', []),
            'message' => $nv_Request->get_typed_array('message', 'post', 'title', []),
            'isdef' => $nv_Request->get_title('isdef', 'post', ''),
            'link' => $nv_Request->get_typed_array('link', 'post', 'title', []),
            'add_time' => $nv_Request->get_title('add_time', 'post', ''),
            'add_hour' => $nv_Request->get_int('add_hour', 'post', 0),
            'add_min' => $nv_Request->get_int('add_min', 'post', 0),
            'exp_time' => $nv_Request->get_title('exp_time', 'post', ''),
            'exp_hour' => $nv_Request->get_int('exp_hour', 'post', -1),
            'exp_min' => $nv_Request->get_int('exp_min', 'post', -1),
        ];

        if (!defined('NV_IS_SPADMIN')) {
            $postdata['sender_role'] = 'admin';
            $postdata['sender_admin'] = $admin_info['admin_id'];
        }

        !in_array($postdata['sender_role'], ['system', 'group', 'admin'], true) && $postdata['sender_role'] = 'system';
        if ($postdata['sender_role'] == 'system') {
            $postdata['sender_group'] = 0;
            $postdata['sender_admin'] = 0;
        } elseif ($postdata['sender_role'] == 'group') {
            $postdata['sender_admin'] = 0;
            $postdata['receiver_type'] = 'ids';
        } elseif ($postdata['sender_role'] == 'admin') {
            $postdata['sender_group'] = 0;
        }
        !in_array($postdata['receiver_type'], ['grs', 'ids'], true) && $postdata['receiver_type'] = 'ids';
        if ($postdata['receiver_type'] == 'ids') {
            $postdata['receiver_grs'] = [];
        } elseif ($postdata['receiver_type'] == 'grs') {
            $postdata['receiver_ids'] = [];
        }

        if ($postdata['sender_role'] == 'group' and empty($postdata['sender_group'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('please_select_group')
            ]);
        }
        if ($postdata['sender_role'] == 'admin' and empty($postdata['sender_admin'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('please_select_admin')
            ]);
        }
        if ($postdata['receiver_type'] == 'grs' and empty($postdata['receiver_grs'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('please_select_receiver_group')
            ]);
        }
        if (empty($postdata['isdef']) or !in_array($postdata['isdef'], $global_config['setup_langs'], true)) {
            $postdata['isdef'] = in_array('en', $global_config['setup_langs'], true) ? 'en' : $global_config['setup_langs'][0];
        }

        if (nv_strlen($postdata['message'][$postdata['isdef']]) < 3) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('please_enter_content', $language_array[$postdata['isdef']]['name'])
            ]);
        }

        $other_link = false;
        foreach ($postdata['link'] as $lang => $link) {
            if (!empty($link) and !nv_is_url($link, true)) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('please_enter_valid_link')
                ]);
            }
            if (!empty($link) and $lang != $postdata['isdef']) {
                $other_link = true;
            }
            if (!empty($link) and !preg_match('#^https?\:\/\/#', $link)) {
                str_starts_with($link, NV_BASE_SITEURL) && $postdata['link'][$lang] = substr($link, strlen(NV_BASE_SITEURL));
            }
        }
        if ($other_link and empty($postdata['link'][$postdata['isdef']])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('please_enter_default_link')
            ]);
        }

        $add_time_array = [];
        if (!preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', $postdata['add_time'], $add_time_array)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('please_enter_valid_add_time')
            ]);
        }

        $exp_time_array = [];
        if (!empty($postdata['exp_time']) and !preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', $postdata['exp_time'], $exp_time_array)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('please_enter_valid_exp_time')
            ]);
        }

        empty($postdata['sender_admin']) && $postdata['sender_admin'] = $admin_info['admin_id'];
        $postdata['receiver_grs'] = !empty($postdata['receiver_grs']) ? implode(',', $postdata['receiver_grs']) : '';
        $postdata['receiver_ids'] = !empty($postdata['receiver_ids']) ? implode(',', $postdata['receiver_ids']) : '';

        $contents = [];
        foreach ($postdata['message'] as $lang => $message) {
            if (nv_strlen($message) >= 3 and in_array($lang, $global_config['setup_langs'], true)) {
                $contents[$lang] = nv_nl2br($message, '<br />');
            }
        }
        $postdata['message'] = json_encode([
            'isdef' => $postdata['isdef'],
            'contents' => $contents
        ], NV_JSON_ENCODE);

        $postdata['link'] = json_encode([
            'isdef' => $postdata['isdef'],
            'contents' => $postdata['link']
        ], NV_JSON_ENCODE);

        $postdata['add_time'] = mktime($postdata['add_hour'], $postdata['add_min'], 0, $add_time_array[2], $add_time_array[1], $add_time_array[3]);
        if (!empty($exp_time_array)) {
            $postdata['exp_hour'] == -1 && $postdata['exp_hour'] = 23;
            $postdata['exp_min'] == -1 && $postdata['exp_min'] = 59;
            $postdata['exp_time'] = mktime($postdata['exp_hour'], $postdata['exp_min'], 0, $exp_time_array[2], $exp_time_array[1], $exp_time_array[3]);
        } else {
            $postdata['exp_time'] = 0;
        }

        if (!empty($id)) {
            $sth = $db->prepare('UPDATE ' . NV_INFORM_GLOBALTABLE . ' SET
            receiver_grs = :receiver_grs, receiver_ids = :receiver_ids, sender_role = :sender_role,
            sender_group = :sender_group, sender_admin = :sender_admin,
            message = :message, link = :link, add_time = :add_time, exp_time = :exp_time
            WHERE id = :id');
            $sth->bindValue(':id', $id, PDO::PARAM_INT);
        } else {
            $sth = $db->prepare('INSERT INTO ' . NV_INFORM_GLOBALTABLE . '
            (receiver_grs, receiver_ids, sender_role, sender_group, sender_admin, message, link, add_time, exp_time) VALUES
            (:receiver_grs, :receiver_ids, :sender_role, :sender_group, :sender_admin, :message, :link, :add_time, :exp_time)');
        }

        $sth->bindValue(':receiver_grs', $postdata['receiver_grs'], PDO::PARAM_STR);
        $sth->bindValue(':receiver_ids', $postdata['receiver_ids'], PDO::PARAM_STR);
        $sth->bindValue(':sender_role', $postdata['sender_role'], PDO::PARAM_STR);
        $sth->bindValue(':sender_group', $postdata['sender_group'], PDO::PARAM_INT);
        $sth->bindValue(':sender_admin', $postdata['sender_admin'], PDO::PARAM_INT);
        $sth->bindValue(':message', $postdata['message'], PDO::PARAM_STR);
        $sth->bindValue(':link', $postdata['link'], PDO::PARAM_STR);
        $sth->bindValue(':add_time', $postdata['add_time'], PDO::PARAM_INT);
        $sth->bindValue(':exp_time', $postdata['exp_time'], PDO::PARAM_INT);
        $sth->execute();

        $log_id = !empty($id) ? $id : $db->lastInsertId();
        nv_insert_logs(NV_LANG_DATA, $module_name, !empty($id) ? 'Edit inform' : 'Add inform', $log_id, $admin_info['userid']);
        nv_jsonOutput([
            'status' => 'OK',
            'mess' => $nv_Lang->getGlobal('save_success'),
            'refresh' => true
        ]);
    }

    // Chuẩn bị dữ liệu cho form sửa
    if (!empty($id)) {
        $data['receiver_grs'] = !empty($data['receiver_grs']) ? array_map('intval', explode(',', $data['receiver_grs'])) : [];
        if (!empty($data['receiver_grs'])) {
            $data['receiver_type'] = 'grs';
            $data['is_receiver_grs'] = 1;
            $data['receiver_grs_disabled'] = 0;
            $data['receiver_ids_disabled'] = 1;
        } else {
            $data['receiver_type'] = 'ids';
            $data['is_receiver_grs'] = 0;
            $data['receiver_grs_disabled'] = 1;
            $data['receiver_ids_disabled'] = 0;
        }

        $data['sender_group_disabled'] = $data['sender_admin_disabled'] = 0;
        if ($data['sender_role'] == 'system') {
            $data['sender_group_disabled'] = $data['sender_admin_disabled'] = 1;
        } elseif ($data['sender_role'] == 'group') {
            $data['sender_admin_disabled'] = 1;
            $data['receiver_grs_disabled'] = 1;
            $data['receiver_ids_disabled'] = 0;
        } elseif ($data['sender_role'] == 'admin') {
            $data['sender_group_disabled'] = 1;
        }

        if (!empty($data['receiver_ids'])) {
            $data['receiver_ids'] = userlist_by_ids($data['receiver_ids'], ($data['sender_role'] == 'group' ? $data['sender_group'] : 0));
        } else {
            $data['receiver_ids'] = [];
        }
    } else {
        $data['receiver_grs'] = [];
        $data['receiver_ids'] = [];
    }

    // Xử lý message
    $data['isdef'] = '';
    if (!empty($data['message'])) {
        $messages = json_decode($data['message'], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $data['isdef'] = $messages['isdef'];
            $data['message'] = $messages['contents'];
        } else {
            $data['isdef'] = NV_LANG_DATA;
            $data['message'] = [
                NV_LANG_DATA => $data['message']
            ];
        }
    } else {
        $data['message'] = [];
    }
    empty($data['isdef']) && $data['isdef'] = in_array('en', $global_config['setup_langs'], true) ? 'en' : $global_config['setup_langs'][0];

    // Xử lý link
    if (!empty($data['link'])) {
        $links = json_decode($data['link'], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $data['link'] = $links['contents'];
        } else {
            $data['link'] = [
                NV_LANG_DATA => $data['link']
            ];
        }
    } else {
        $data['link'] = [];
    }
    foreach ($data['link'] as $lang => $link) {
        if (!empty($link) and !preg_match('#^https?\:\/\/#', $link)) {
            $data['link'][$lang] = NV_BASE_SITEURL . $link;
        }
    }

    [$data['add_time_format'], $data['add_hour'], $data['add_min']] = explode('|', date('d/m/Y|H|i', $data['add_time']));
    if (!empty($data['exp_time'])) {
        [$data['exp_time_format'], $data['exp_hour'], $data['exp_min']] = explode('|', date('d/m/Y|H|i', $data['exp_time']));
    } else {
        $data['exp_time_format'] = '';
        $data['exp_hour'] = $data['exp_min'] = -1;
    }

    // Danh sách vai trò gửi (chỉ spadmin)
    $roles = [];
    if (defined('NV_IS_SPADMIN')) {
        $roles = [
            ['key' => 'system', 'name' => $nv_Lang->getModule('admin_from_system')],
            ['key' => 'group', 'name' => $nv_Lang->getModule('admin_from_group')],
            ['key' => 'admin', 'name' => $nv_Lang->getModule('admin_from_admin')],
        ];
    }

    // Danh sách nhóm và admin người gửi
    $sender_grouplist = [];
    foreach ($grouplist as $key => $name) {
        $sender_grouplist[] = [
            'key' => $key,
            'name' => $name
        ];
    }

    $sender_adminlist = [];
    foreach ($adminlist as $key => $name) {
        $sender_adminlist[] = [
            'key' => $key,
            'name' => $name
        ];
    }

    // Loại người nhận
    $receiver_types = [
        [
            'key' => 'ids',
            'name' => $data['sender_role'] == 'group' ? $nv_Lang->getModule('to_members') : $nv_Lang->getModule('to_users'),
            'disabled' => false
        ],
        [
            'key' => 'grs',
            'name' => $nv_Lang->getModule('to_group'),
            'disabled' => $data['sender_role'] == 'group'
        ],
    ];

    // Danh sách nhóm người nhận
    $receiver_grouplist = [];
    foreach ($grouplist as $key => $name) {
        $receiver_grouplist[] = [
            'key' => $key,
            'name' => $name,
            'selected' => in_array($key, $data['receiver_grs'])
        ];
    }

    // Danh sách ngôn ngữ kèm nội dung
    $setup_langs = [];
    foreach ($global_config['setup_langs'] as $lang) {
        $setup_langs[] = [
            'key' => $lang,
            'name' => $language_array[$lang]['name'],
            'message' => !empty($data['message'][$lang]) ? nv_br2nl($data['message'][$lang]) : '',
            'link' => !empty($data['link'][$lang]) ? $data['link'][$lang] : ''
        ];
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('action.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('CHECKSS', csrf_create($csrf_key));
    $tpl->assign('IS_SPADMIN', defined('NV_IS_SPADMIN'));
    $tpl->assign('DATA', $data);
    $tpl->assign('ROLES', $roles);
    $tpl->assign('SENDER_GROUPLIST', $sender_grouplist);
    $tpl->assign('SENDER_ADMINLIST', $sender_adminlist);
    $tpl->assign('RECEIVER_TYPES', $receiver_types);
    $tpl->assign('RECEIVER_GROUPLIST', $receiver_grouplist);
    $tpl->assign('SETUP_LANGS', $setup_langs);

    nv_jsonOutput([
        'status' => 'OK',
        'content' => $tpl->fetch('action.tpl')
    ]);
}

// Danh sách thông báo
$per_page = 20;
$page = $nv_Request->get_page('page', 'get', 1);

$filter = '';
$filters = [];
if (defined('NV_IS_SPADMIN')) {
    $filter = $nv_Request->get_title('filter', 'get', '');
    !in_array($filter, ['system', 'group', 'admins', 'admin', 'active', 'waiting', 'expired'], true) && $filter = '';
    !empty($filter) && $base_url .= '&amp;filter=' . $filter;
    if ($filter == 'system') {
        $where[] = "(mtb.sender_role = 'system')";
    } elseif ($filter == 'group') {
        $where[] = "(mtb.sender_role = 'group')";
    } elseif ($filter == 'admins') {
        $where[] = "(mtb.sender_role = 'admin')";
    } elseif ($filter == 'admin') {
        $where[] = "(mtb.sender_role = 'admin' AND mtb.sender_admin = :sender_admin)";
        $params[':sender_admin'] = [$admin_info['admin_id'], PDO::PARAM_INT];
    } elseif ($filter == 'active') {
        $where[] = '(mtb.add_time <= :current_time1 AND (mtb.exp_time = 0 OR mtb.exp_time > :current_time2))';
        $params[':current_time1'] = [NV_CURRENTTIME, PDO::PARAM_INT];
        $params[':current_time2'] = [NV_CURRENTTIME, PDO::PARAM_INT];
    } elseif ($filter == 'waiting') {
        $where[] = '(mtb.add_time > :current_time)';
        $params[':current_time'] = [NV_CURRENTTIME, PDO::PARAM_INT];
    } elseif ($filter == 'expired') {
        $where[] = '(mtb.exp_time != 0 AND mtb.exp_time < :current_time)';
        $params[':current_time'] = [NV_CURRENTTIME, PDO::PARAM_INT];
    }

    $filters = [
        'system' => $nv_Lang->getModule('filter_system'),
        'group' => $nv_Lang->getModule('filter_group'),
        'admins' => $nv_Lang->getModule('filter_admins'),
        'admin' => $nv_Lang->getModule('filter_admin'),
        'active' => $nv_Lang->getModule('active'),
        'waiting' => $nv_Lang->getModule('waiting'),
        'expired' => $nv_Lang->getModule('expired')
    ];
}

$where_str = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';

$sth = $db->prepare('SELECT COUNT(*) FROM ' . NV_INFORM_GLOBALTABLE . ' AS mtb' . $where_str);
foreach ($params as $key => $val) {
    $sth->bindValue($key, $val[0], $val[1]);
}
$sth->execute();
$num_items = $sth->fetchColumn();
$sth->closeCursor();

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$sth = $db->prepare('SELECT mtb.*, (SELECT COUNT(*) FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' WHERE pid = mtb.id AND viewed_time != 0) AS views
    FROM ' . NV_INFORM_GLOBALTABLE . ' AS mtb' . $where_str . '
    ORDER BY mtb.add_time DESC
    LIMIT :limit OFFSET :offset');
foreach ($params as $key => $val) {
    $sth->bindValue($key, $val[0], $val[1]);
}
$sth->bindValue(':limit', (int) $per_page, PDO::PARAM_INT);
$sth->bindValue(':offset', (int) (($page - 1) * $per_page), PDO::PARAM_INT);
$sth->execute();
$items = [];
$user_ids = [];
while ($row = $sth->fetch()) {
    if (!empty($row['message'])) {
        $messages = json_decode($row['message'], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            if (!empty($messages['contents'][NV_LANG_DATA])) {
                $row['message'] = $messages['contents'][NV_LANG_DATA];
            } else {
                $row['message'] = $messages['contents'][$messages['isdef']];
            }
        }
    }

    if (!empty($row['message'])) {
        $row['message'] = preg_replace('/(\<\/?br\s*\/?\>)+/', '<br/>', $row['message']);
        $row['message'] = text_split($row['message'], 120);
    } else {
        $row['message'] = [];
    }

    if (!empty($row['link'])) {
        $links = json_decode($row['link'], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            if (!empty($links['contents'][NV_LANG_DATA])) {
                $row['link'] = $links['contents'][NV_LANG_DATA];
            } else {
                $row['link'] = $links['contents'][$links['isdef']];
            }
        }
    }

    if (!empty($row['link']) and !preg_match('#^https?\:\/\/#', $row['link'])) {
        $row['link'] = NV_BASE_SITEURL . $row['link'];
    }

    if (!($row['sender_role'] == 'admin' and !empty($row['sender_admin'])) and !($row['sender_role'] == 'group' and !empty($row['sender_group']) and !empty($grouplist[$row['sender_group']]))) {
        $row['sender_role'] = 'system';
    }

    if ($row['sender_role'] == 'group') {
        $row['sender_group_name'] = $grouplist[$row['sender_group']];
        $row['sender_group_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=groups&amp;userlist=' . $row['sender_group'];
    } elseif ($row['sender_role'] == 'admin') {
        $row['sender_admin_name'] = $adminlist[$row['sender_admin']] ?? '';
        $row['sender_admin_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=authors&amp;id=' . $row['sender_admin'];
    }

    $row['receiver_grs'] = !empty($row['receiver_grs']) ? array_map('intval', explode(',', $row['receiver_grs'])) : [];
    $row['receiver_ids'] = !empty($row['receiver_ids']) ? array_map('intval', explode(',', $row['receiver_ids'])) : [];

    if (!empty($row['receiver_grs'])) {
        $row['receiver_title'] = count($row['receiver_grs']) === 1 ? $nv_Lang->getModule('to_group') : $nv_Lang->getModule('to_groups');
    } elseif (!empty($row['receiver_ids'])) {
        $row['receiver_title'] = $row['sender_role'] == 'group' ? $nv_Lang->getModule('to_members') : $nv_Lang->getModule('to_users');
        $user_ids = array_merge($user_ids, $row['receiver_ids']);
    } else {
        $row['receiver_title'] = $nv_Lang->getModule('to_all');
    }

    $row['add_time_format'] = nv_datetime_format($row['add_time']);
    $row['exp_time_format'] = !empty($row['exp_time']) ? nv_datetime_format($row['exp_time']) : $nv_Lang->getModule('unlimited');

    if ($row['add_time'] > NV_CURRENTTIME) {
        $row['status'] = 'waiting';
    } elseif (!empty($row['exp_time']) and $row['exp_time'] < NV_CURRENTTIME) {
        $row['status'] = 'expired';
    } else {
        $row['status'] = 'active';
    }

    $items[$row['id']] = $row;
}
$sth->closeCursor();

// Lấy thông tin người dùng nhận thông báo
$users = !empty($user_ids) ? userlist_by_ids($user_ids, 0, true) : [];

// Bổ sung thông tin nhóm/người dùng vào từng item để template dùng trực tiếp
foreach ($items as &$item) {
    $item['receiver_groups'] = [];
    foreach ($item['receiver_grs'] as $gr) {
        $item['receiver_groups'][] = [
            'id' => $gr,
            'name' => $grouplist[$gr] ?? '',
            'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=groups&amp;userlist=' . $gr
        ];
    }

    $item['receiver_users'] = [];
    foreach ($item['receiver_ids'] as $uid) {
        if (isset($users[$uid])) {
            $item['receiver_users'][] = [
                'uid' => $users[$uid][0],
                'username' => $users[$uid][1],
                'fullname' => $users[$uid][2]
            ];
        }
    }
}
unset($item);

$page_title = $module_info['site_title'];

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir(basename(__FILE__, '.php') . '.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('IS_SPADMIN', defined('NV_IS_SPADMIN'));
$tpl->assign('FILTER', $filter);
$tpl->assign('FILTERS', $filters);
$tpl->assign('ITEMS', $items);
$tpl->assign('PAGINATION', $generate_page);
$contents = $tpl->fetch(basename(__FILE__, '.php') . '.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
