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
    $q = $nv_Request->get_title('q', 'post', '');
    $grid = $nv_Request->get_int('grid', 'post', 0);

    if (!empty($grid) and isset($grouplist[$grid])) {
        $where = '(username LIKE :username OR email LIKE :email OR first_name like :first_name OR last_name like :last_name) AND userid IN (SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE group_id = ' . $grid . ')';
    } else {
        $where = '(username LIKE :username OR email LIKE :email OR first_name like :first_name OR last_name like :last_name)';
    }
    $db->sqlreset()
        ->select('userid, username, email, first_name, last_name')
        ->from(NV_USERS_GLOBALTABLE)
        ->where($where)
        ->order('username ASC')
        ->limit(20);

    $sth = $db->prepare($db->sql());
    $sth->bindValue(':username', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':email', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':first_name', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':last_name', '%' . $q . '%', PDO::PARAM_STR);
    $sth->execute();

    $data = [];
    while ([$userid, $username, $email, $first_name, $last_name] = $sth->fetch(3)) {
        $full_name = $global_config['name_show'] ? [$first_name, $last_name] : [$last_name, $first_name];
        $full_name = array_filter($full_name);
        $data[] = [
            'id' => $userid,
            'username' => $username,
            'fullname' => implode(' ', $full_name),
            'email' => $email
        ];
    }

    nv_jsonOutput($data);
}

$page_url = $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$adminlist = admins_list();

$where = [];
if (!defined('NV_IS_SPADMIN')) {
    $where[] = '(mtb.sender_admin=' . $admin_info['admin_id'] . ')';
}

$action = $nv_Request->get_title('action', 'post', '');

// Xóa thông báo
if ($action == 'inform_del') {
    $id = $nv_Request->get_int('id', 'post', 0);
    if ($id) {
        $where[] = '(mtb.id = ' . $id . ')';
        $where = implode(' AND ', $where);
        $db->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_INFORM_GLOBALTABLE . ' AS mtb')
            ->where($where);
        $num_items = $db->query($db->sql())
            ->fetchColumn();
        if ($num_items) {
            $db->query('DELETE FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' WHERE pid = ' . $id);
            $db->query('DELETE FROM ' . NV_INFORM_GLOBALTABLE . ' WHERE id = ' . $id);
            $db->query('OPTIMIZE TABLE ' . NV_INFORM_STATUS_GLOBALTABLE);
            $db->query('OPTIMIZE TABLE ' . NV_INFORM_GLOBALTABLE);
            nv_jsonOutput([
                'status' => 'OK'
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
        $where[] = '(mtb.id = ' . $id . ')';
        $sql = 'SELECT * FROM ' . NV_INFORM_GLOBALTABLE . ' AS mtb WHERE ' . implode(' AND ', $where);
        $data = $db->query($sql)->fetch();
        if (empty($data)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('notification_not_exist')
            ]);
        }
    }

    if ($nv_Request->isset_request('save', 'post')) {
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
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $postdata['link'] = json_encode([
            'isdef' => $postdata['isdef'],
            'contents' => $postdata['link']
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

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
            sender_group = ' . $postdata['sender_group'] . ', sender_admin = ' . $postdata['sender_admin'] . ',
            message = :message, link = :link, add_time = ' . $postdata['add_time'] . ', exp_time = ' . $postdata['exp_time'] . '
            WHERE id = ' . $id);
        } else {
            $sth = $db->prepare('INSERT INTO ' . NV_INFORM_GLOBALTABLE . '
            (receiver_grs, receiver_ids, sender_role, sender_group, sender_admin, message, link, add_time, exp_time) VALUES
            (:receiver_grs, :receiver_ids, :sender_role, ' . $postdata['sender_group'] . ', ' . $postdata['sender_admin'] . ', :message, :link, ' . $postdata['add_time'] . ', ' . $postdata['exp_time'] . ')');
        }

        $sth->bindValue(':receiver_grs', $postdata['receiver_grs'], PDO::PARAM_STR);
        $sth->bindValue(':receiver_ids', $postdata['receiver_ids'], PDO::PARAM_STR);
        $sth->bindValue(':sender_role', $postdata['sender_role'], PDO::PARAM_STR);
        $sth->bindValue(':message', $postdata['message'], PDO::PARAM_STR);
        $sth->bindValue(':link', $postdata['link'], PDO::PARAM_STR);
        $sth->execute();

        nv_jsonOutput([
            'status' => 'OK'
        ]);
    }

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
    }

    $data['sender_group_disabled'] = !empty($data['sender_group_disabled']) ? ' disabled="disabled"' : '';
    $data['sender_admin_disabled'] = !empty($data['sender_admin_disabled']) ? ' disabled="disabled"' : '';
    $data['receiver_grs_disabled'] = !empty($data['receiver_grs_disabled']) ? ' disabled="disabled"' : '';
    $data['receiver_ids_disabled'] = !empty($data['receiver_ids_disabled']) ? ' disabled="disabled"' : '';
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

    // Xử lý lại phần link
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

    $xtpl = new XTemplate('action.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('PAGE_URL', $page_url);
    $xtpl->assign('DATA', $data);

    if (!defined('NV_IS_SPADMIN')) {
        $xtpl->parse('main.is_sender_not_select');
    } else {
        $roles = ['system' => $nv_Lang->getModule('admin_from_system'), 'group' => $nv_Lang->getModule('admin_from_group'), 'admin' => $nv_Lang->getModule('admin_from_admin')];
        foreach ($roles as $key => $name) {
            $xtpl->assign('ROLE', [
                'key' => $key,
                'sel' => (!empty($data['sender_role']) and $key == $data['sender_role']) ? ' selected="selected"' : '',
                'name' => $name
            ]);
            $xtpl->parse('main.is_sender_select.sender_role');
        }

        foreach ($grouplist as $key => $name) {
            $xtpl->assign('GROUP', [
                'key' => $key,
                'sel' => (!empty($data['sender_group']) and $key == $data['sender_group']) ? ' selected="selected"' : '',
                'name' => $name
            ]);
            $xtpl->parse('main.is_sender_select.sender_group');
        }

        foreach ($adminlist as $key => $name) {
            $xtpl->assign('ADMIN', [
                'key' => $key,
                'sel' => (!empty($data['sender_admin']) and $key == $data['sender_admin']) ? ' selected="selected"' : '',
                'name' => $name
            ]);
            $xtpl->parse('main.is_sender_select.sender_admin');
        }

        $xtpl->parse('main.is_sender_select');
    }

    $receiver_types = [
        'ids' => $data['sender_role'] == 'group' ? $nv_Lang->getModule('to_members') : $nv_Lang->getModule('to_users'),
        'grs' => $nv_Lang->getModule('to_group')
    ];
    foreach ($receiver_types as $key => $name) {
        $xtpl->assign('TYPE', [
            'key' => $key,
            'sel' => ($key == $data['receiver_type']) ? ' selected="selected"' : '',
            'disabled' => ($key == 'grs' and $data['sender_role'] == 'group') ? ' disabled="disabled"' : '',
            'name' => $name
        ]);
        $xtpl->parse('main.receiver_type');
    }

    foreach ($grouplist as $key => $name) {
        $xtpl->assign('RECEIVER_GROUP', [
            'key' => $key,
            'sel' => (!empty($data['receiver_grs']) and in_array($key, $data['receiver_grs'], true)) ? ' selected="selected"' : '',
            'name' => $name
        ]);
        $xtpl->parse('main.receiver_grs');
    }

    if (!empty($data['receiver_ids'])) {
        foreach ($data['receiver_ids'] as $key => $name) {
            $xtpl->assign('RECEIVER_ID', [
                'key' => $key,
                'name' => $name
            ]);
            $xtpl->parse('main.receiver_ids');
        }
    }

    foreach ($global_config['setup_langs'] as $lang) {
        $xtpl->assign('MESS', [
            'lang' => $lang,
            'langname' => $language_array[$lang]['name'],
            'content' => !empty($data['message'][$lang]) ? nv_br2nl($data['message'][$lang]) : '',
            'checked' => $lang == $data['isdef'] ? ' checked="checked"' : ''
        ]);
        $xtpl->parse('main.message');

        $xtpl->assign('LINK', [
            'lang' => $lang,
            'langname' => $language_array[$lang]['name'],
            'content' => !empty($data['link'][$lang]) ? $data['link'][$lang] : '',
        ]);
        $xtpl->parse('main.link');
    }

    for ($i = 0; $i < 24; ++$i) {
        $name = str_pad($i, 2, '0', STR_PAD_LEFT);
        $xtpl->assign('HOUR', [
            'val' => $i,
            'name' => $name,
            'sel' => $i == $data['add_hour'] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.add_hour');

        $xtpl->assign('EXP_HOUR', [
            'val' => $i,
            'name' => $name,
            'sel' => $i == $data['exp_hour'] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.exp_hour');
    }

    for ($i = 0; $i < 60; ++$i) {
        $name = str_pad($i, 2, '0', STR_PAD_LEFT);
        $xtpl->assign('MIN', [
            'val' => $i,
            'name' => $name,
            'sel' => $i == $data['add_min'] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.add_min');

        $xtpl->assign('EXP_MIN', [
            'val' => $i,
            'name' => $name,
            'sel' => $i == $data['exp_min'] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.exp_min');
    }

    $xtpl->parse('main');
    nv_jsonOutput([
        'status' => 'OK',
        'content' => $xtpl->text('main')
    ]);
}

$per_page = 20;
$page = $nv_Request->get_page('page', 'get', 1);

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
        $where[] = "(mtb.sender_role = 'admin' AND mtb.sender_admin=" . $admin_info['admin_id'] . ')';
    } elseif ($filter == 'active') {
        $where[] = '(mtb.add_time <= ' . NV_CURRENTTIME . ' AND (mtb.exp_time = 0 OR mtb.exp_time > ' . NV_CURRENTTIME . '))';
    } elseif ($filter == 'waiting') {
        $where[] = '(mtb.add_time > ' . NV_CURRENTTIME . ')';
    } elseif ($filter == 'expired') {
        $where[] = '(mtb.exp_time != 0 AND mtb.exp_time < ' . NV_CURRENTTIME . ')';
    }
}

$where = implode(' AND ', $where);

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_INFORM_GLOBALTABLE . ' AS mtb')
    ->where($where);
$num_items = $db->query($db->sql())
    ->fetchColumn();
$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$db->select('mtb.*, (SELECT COUNT(*) FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' WHERE pid = mtb.id AND viewed_time != 0) AS views')
    ->order('mtb.add_time DESC')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);
$result = $db->query($db->sql());
$items = [];
$users = [];
while ($row = $result->fetch()) {
    if (!empty($row['message'])) {
        $messages = json_decode($row['message'], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            if (!empty($messages['contents'][NV_LANG_DATA])) {
                $row['message'] = $messages['contents'][NV_LANG_DATA];
            } else {
                $row['message'] = $messages['contents'][$messages['isdef']];
            }
        }

        if (!empty($row['message'])) {
            $row['message'] = preg_replace('/(\<\/?br\s*\/?\>)+/', '<br/>', $row['message']);
            $row['message'] = text_split($row['message'], 120);
        } else {
            $row['message'] = [];
        }
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
        $row['sender_admin_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=authors&amp;id=' . $row['sender_admin'];
    }

    $row['receiver_grs'] = !empty($row['receiver_grs']) ? array_map('intval', explode(',', $row['receiver_grs'])) : [];
    $row['receiver_ids'] = !empty($row['receiver_ids']) ? array_map('intval', explode(',', $row['receiver_ids'])) : [];
    if (!empty($row['receiver_grs'])) {
        $row['receiver_title'] = count($row['receiver_grs']) === 1 ? $nv_Lang->getModule('to_group') : $nv_Lang->getModule('to_groups');
    } elseif (!empty($row['receiver_ids'])) {
        $row['receiver_title'] = $row['sender_role'] == 'group' ? $nv_Lang->getModule('to_members') : $nv_Lang->getModule('to_users');
        $users = array_merge($users, $row['receiver_ids']);
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

$users = !empty($users) ? userlist_by_ids($users, 0, true) : [];

$page_title = $module_info['site_title'];

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('PAGE_URL', $page_url);

if (defined('NV_IS_SPADMIN')) {
    $filters = [
        'system' => $nv_Lang->getModule('filter_system'),
        'group' => $nv_Lang->getModule('filter_group'),
        'admins' => $nv_Lang->getModule('filter_admins'),
        'admin' => $nv_Lang->getModule('filter_admin'),
        'active' => $nv_Lang->getModule('active'),
        'waiting' => $nv_Lang->getModule('waiting'),
        'expired' => $nv_Lang->getModule('expired')
    ];
    foreach ($filters as $key => $title) {
        $xtpl->assign('FILTER', [
            'key' => $key,
            'sel' => $key == $filter ? ' selected="selected"' : '',
            'title' => $title
        ]);
        $xtpl->parse('main.filter.loop');
    }
    $xtpl->parse('main.filter');
}

if (empty($items)) {
    $xtpl->parse('main.is_empty');
} else {
    foreach ($items as $item) {
        if ($item['sender_role'] == 'admin') {
            $item['sender_admin_name'] = $adminlist[$item['sender_admin']];
        }

        $xtpl->assign('ITEM', $item);

        if ($item['status'] == 'waiting') {
            $xtpl->parse('main.items.loop.waiting');
        } elseif ($item['status'] == 'expired') {
            $xtpl->parse('main.items.loop.expired');
        } else {
            $xtpl->parse('main.items.loop.active');
        }

        if ($item['sender_role'] == 'group') {
            $xtpl->parse('main.items.loop.from_group');
        } elseif ($item['sender_role'] == 'admin') {
            $xtpl->parse('main.items.loop.from_admin');
        } else {
            $xtpl->parse('main.items.loop.from_system');
        }

        if (!empty($item['receiver_grs'])) {
            $count = count($item['receiver_grs']) - 1;
            foreach ($item['receiver_grs'] as $key => $gr) {
                $xtpl->assign('GROUP', [
                    'name' => $grouplist[$gr],
                    'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=groups&amp;userlist=' . $gr
                ]);

                if ($key < $count) {
                    $xtpl->parse('main.items.loop.to_group.group.comma');
                }
                $xtpl->parse('main.items.loop.to_group.group');
            }
            $xtpl->parse('main.items.loop.to_group');
        } elseif (!empty($item['receiver_ids'])) {
            $count = count($item['receiver_ids']) - 1;
            foreach ($item['receiver_ids'] as $key => $uid) {
                $xtpl->assign('USER', $users[$uid]);

                if ($key < $count) {
                    $xtpl->parse('main.items.loop.to_user.user.comma');
                }
                $xtpl->parse('main.items.loop.to_user.user');
            }
            $xtpl->parse('main.items.loop.to_user');
        }

        if (!empty($item['message'][1])) {
            $xtpl->parse('main.items.loop.message_1');
        }
        if (!empty($item['link'])) {
            $xtpl->parse('main.items.loop.link');
        }

        $xtpl->parse('main.items.loop');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.items.generate_page');
    }
    $xtpl->parse('main.items');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
