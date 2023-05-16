<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_INFORM')) {
    exit('Stop!!!');
}

if (empty($global_config['inform_active']) or !defined('NV_IS_USER')) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
}

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$u_groups = array_values(array_unique(array_filter(array_map(function ($gr) {
    return $gr >= 10 ? (int) $gr : 0;
}, $user_info['in_groups']))));

// Khu vực quản lý thông báo của trưởng nhóm
if ($nv_Request->isset_request('manager', 'get')) {
    $group_id = $nv_Request->get_int('manager', 'get', 0);

    if (!in_array($group_id, $u_groups, true)) {
        exit(0);
    }

    $count = $db->query('SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE group_id=' . $group_id . ' AND is_leader=1 AND userid=' . $user_info['userid'])->fetchColumn();
    if (!$count) {
        exit(0);
    }

    // Lấy danh sách thành viên nhóm qua ajax
    if ($nv_Request->isset_request('get_user_json', 'post')) {
        $q = $nv_Request->get_title('q', 'post', '');

        $where = '(username LIKE :username OR email LIKE :email OR first_name like :first_name OR last_name like :last_name) AND userid IN (SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE group_id = ' . $group_id . ')';

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
        while (list($userid, $username, $email, $first_name, $last_name) = $sth->fetch(3)) {
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

    $checkss = md5(NV_CHECK_SESSION . '_' . $module_name);
    $where = "(mtb.sender_role='group' AND mtb.sender_group=" . $group_id . ')';
    $base_url .= '&amp;manager=' . $group_id;
    $page_url = $base_url;

    // Xóa thông báo
    if ($nv_Request->isset_request('delete,_csrf', 'post')) {
        $csrf = $nv_Request->get_title('_csrf', 'post', '');
        if (!hash_equals($checkss, $csrf)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => 'ERROR'
            ]);
        }
        $id = $nv_Request->get_int('delete', 'post', 0);
        if ($id) {
            $where .= ' AND (mtb.id=' . $id . ')';
            $db->sqlreset()
                ->select('COUNT(*)')
                ->from(NV_INFORM_GLOBALTABLE . 'AS mtb')
                ->where($where);
            $num_items = $db->query($db->sql())
                ->fetchColumn();
            if ($num_items) {
                $db->query('DELETE FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' WHERE pid = ' . $id);
                $db->query('DELETE FROM ' . NV_INFORM_GLOBALTABLE . ' WHERE id = ' . $id);
                $db->query('OPTIMIZE TABLE ' . NV_INFORM_STATUS_GLOBALTABLE);
                $db->query('OPTIMIZE TABLE ' . NV_INFORM_GLOBALTABLE);
            }
        }
        nv_jsonOutput([
            'status' => 'OK'
        ]);
    }

    // Thêm/Sửa thông báo
    if ($nv_Request->isset_request('action', 'post')) {
        $id = $nv_Request->get_int('action', 'post', 0);

        $data = ['id' => 0, 'add_time' => NV_CURRENTTIME, 'exp_time' => NV_CURRENTTIME + $global_config['inform_default_exp']];
        if (!empty($id)) {
            $where .= ' AND (mtb.id=' . $id . ')';
            $db->sqlreset()
                ->select('*')
                ->from(NV_INFORM_GLOBALTABLE . ' AS mtb')
                ->where($where);
            $result = $db->query($db->sql());
            $data = $result->fetch();
            if (empty($data)) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => 'ERROR'
                ]);
            }
        }

        $csrf = $nv_Request->get_title('_csrf', 'post', '');
        if (hash_equals($checkss, $csrf)) {
            $postdata = [
                'receiver_ids' => $nv_Request->get_typed_array('receiver_ids', 'post', 'int', []),
                'message' => $nv_Request->get_typed_array('message', 'post', 'title', []),
                'isdef' => $nv_Request->get_title('isdef', 'post', ''),
                'link' => $nv_Request->get_typed_array('link', 'post', 'title', []),
                'add_time' => $nv_Request->get_title('add_time', 'post', ''),
                'add_hour' => $nv_Request->get_int('add_hour', 'post', 0),
                'add_min' => $nv_Request->get_int('add_min', 'post', 0),
                'exp_time' => $nv_Request->get_title('exp_time', 'post', ''),
                'exp_hour' => $nv_Request->get_int('exp_hour', 'post', -1),
                'exp_min' => $nv_Request->get_int('exp_min', 'post', -1)
            ];

            if (empty($postdata['isdef']) or !in_array($postdata['isdef'], $global_config['setup_langs'], true)) {
                $postdata['isdef'] = in_array('en', $global_config['setup_langs'], true) ? 'en' : $global_config['setup_langs'][0];
            }

            if (nv_strlen($postdata['message'][$postdata['isdef']]) < 3) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => sprintf($lang_module['please_enter_content'], $language_array[$postdata['isdef']]['name'])
                ]);
            }

            $other_link = false;
            foreach ($postdata['link'] as $lang => $link) {
                if (!empty($link) and !nv_is_url($link, true)) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'mess' => $lang_module['please_enter_valid_link']
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
                    'mess' => $lang_module['please_enter_default_link']
                ]);
            }

            $add_time_array = [];
            if (!preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', $postdata['add_time'], $add_time_array)) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $lang_module['please_enter_valid_add_time']
                ]);
            }

            $exp_time_array = [];
            if (!empty($postdata['exp_time']) and !preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', $postdata['exp_time'], $exp_time_array)) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $lang_module['please_enter_valid_exp_time']
                ]);
            }

            if (!empty($postdata['receiver_ids'])) {
                $postdata['receiver_ids'] = userlist_by_ids($postdata['receiver_ids'], $group_id);
                $postdata['receiver_ids'] = array_keys($postdata['receiver_ids']);
                $postdata['receiver_ids'] = implode(',', $postdata['receiver_ids']);
            } else {
                $postdata['receiver_ids'] = '';
            }
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
                receiver_ids = :receiver_ids, message = :message, link = :link, add_time = ' . $postdata['add_time'] . ', exp_time = ' . $postdata['exp_time'] . '
                WHERE id = ' . $id);
            } else {
                $sth = $db->prepare('INSERT INTO ' . NV_INFORM_GLOBALTABLE . "
                (receiver_ids, sender_role, sender_group, sender_admin, message, link, add_time, exp_time) VALUES
                (:receiver_ids, 'group', " . $group_id . ', 0, :message, :link, ' . $postdata['add_time'] . ', ' . $postdata['exp_time'] . ')');
            }

            $sth->bindValue(':receiver_ids', $postdata['receiver_ids'], PDO::PARAM_STR);
            $sth->bindValue(':message', $postdata['message'], PDO::PARAM_STR);
            $sth->bindValue(':link', $postdata['link'], PDO::PARAM_STR);
            $sth->execute();

            nv_jsonOutput([
                'status' => 'OK'
            ]);
        }

        $data['receiver_ids'] = !empty($data['receiver_ids']) ? userlist_by_ids($data['receiver_ids'], $group_id) : [];
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

        list($data['add_time'], $data['add_hour'], $data['add_min']) = explode('|', date('d/m/Y|H|i', $data['add_time']));
        if (!empty($data['exp_time'])) {
            list($data['exp_time'], $data['exp_hour'], $data['exp_min']) = explode('|', date('d/m/Y|H|i', $data['exp_time']));
        } else {
            $data['exp_time'] = '';
            $data['exp_hour'] = $data['exp_min'] = -1;
        }

        $contents = notification_action_theme($data, $page_url, $checkss);
        nv_jsonOutput([
            'status' => 'OK',
            'content' => $contents
        ]);
    }

    // Danh sách thông báo của nhóm
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'get', 1);
    $filter = $nv_Request->get_title('filter', 'get', '');
    !in_array($filter, ['active', 'waiting', 'expired'], true) && $filter = '';
    if (!empty($filter)) {
        $base_url .= '&amp;filter=' . $filter;
    }
    $ajax = (float) $nv_Request->get_int('ajax', 'get', 0);
    $page > 1 && $ajax = true;
    $base_url .= '&amp;ajax=' . nv_genpass(10);

    if ($filter == 'active') {
        $where .= ' AND (mtb.add_time <= ' . NV_CURRENTTIME . ' AND (mtb.exp_time = 0 OR mtb.exp_time > ' . NV_CURRENTTIME . '))';
    } elseif ($filter == 'waiting') {
        $where .= ' AND (mtb.add_time > ' . NV_CURRENTTIME . ')';
    } elseif ($filter == 'expired') {
        $where .= ' AND (mtb.exp_time != 0 AND mtb.exp_time < ' . NV_CURRENTTIME . ')';
    }

    $db->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_INFORM_GLOBALTABLE . ' AS mtb')
        ->where($where);
    $num_items = $db->query($db->sql())
        ->fetchColumn();
    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page, true, true, 'nv_urldecode_ajax', 'generate_page');

    $db->select('mtb.*, (SELECT COUNT(*) FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' WHERE pid = mtb.id AND viewed_time != 0) AS views')
        ->order('mtb.add_time DESC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $result = $db->query($db->sql());
    $items = [];
    $members = [];
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

        $row['receiver_ids'] = !empty($row['receiver_ids']) ? array_map('intval', explode(',', $row['receiver_ids'])) : [];
        !empty($row['receiver_ids']) && $members = array_merge($members, $row['receiver_ids']);

        $row['add_time_format'] = nv_date('d.m.y H:i', $row['add_time']);
        if (!empty($row['exp_time'])) {
            $row['exp_time_format'] = nv_date('d.m.y H:i', $row['exp_time']);
            if ($row['exp_time'] < NV_CURRENTTIME) {
                $row['exp_time_format'] .= '<br/>' . $lang_module['to_be_removed'] . '<br/>' . (nv_date('d.m.y H:i', ($row['exp_time'] + $global_config['inform_exp_del'])));
            }
        } else {
            $row['exp_time_format'] = $lang_module['unlimited'];
        }

        if ($row['add_time'] > NV_CURRENTTIME) {
            $row['status'] = 'waiting';
        } elseif (!empty($row['exp_time']) and $row['exp_time'] < NV_CURRENTTIME) {
            $row['status'] = 'expired';
        } else {
            $row['status'] = 'active';
        }

        $items[$row['id']] = $row;
    }

    if (!empty($members)) {
        $members = userlist_by_ids(array_unique($members), $group_id, true);
    }

    $contents = !empty($items) ? getlist_theme($items, $generate_page, $group_id, $members) : '';

    if ($ajax) {
        nv_htmlOutput($contents);
    }

    nv_htmlOutput(notifications_manager_theme($contents, nv_url_rewrite($page_url, true), $filter, $checkss));
}

$where = [];
$where[] = "(mtb.receiver_grs = '' AND mtb.receiver_ids = '')";
if (!empty($u_groups)) {
    $wh = [];
    foreach ($u_groups as $gr) {
        $wh[] = 'FIND_IN_SET(' . $gr . ', mtb.receiver_grs)';
    }
    $wh = implode(' OR ', $wh);
    $where[] = "(mtb.receiver_grs != '' AND (" . $wh . '))';
}
$where[] = "(mtb.receiver_ids != '' AND FIND_IN_SET(" . $user_info['userid'] . ', mtb.receiver_ids))';
$where = '(' . implode(' OR ', $where) . ') AND (mtb.add_time <= ' . NV_CURRENTTIME . ') AND (mtb.exp_time = 0 OR mtb.exp_time > ' . NV_CURRENTTIME . ')';
if (!empty($u_groups)) {
    $where .= " AND (mtb.sender_role != 'group' OR (mtb.sender_role = 'group' AND mtb.sender_group IN (" . implode(',', $u_groups) . ')))';
} else {
    $where .= " AND (mtb.sender_role != 'group')";
}

// Lấy tổng số thông báo chưa xem
/*if (defined('NV_IS_AJAX') and $nv_Request->isset_request('getNew', 'post')) {
    $where .= ' AND NOT EXISTS (SELECT * FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS exc WHERE (exc.pid = mtb.id AND exc.userid = ' . $user_info['userid'] . ') AND (exc.shown_time != 0 OR exc.hidden_time != 0))';
    $sql = 'SELECT mtb.id FROM ' . NV_INFORM_GLOBALTABLE . ' AS mtb WHERE ' . $where . ' ORDER BY mtb.id DESC';
    $result = $db->query($sql);
    $count = $result->rowCount();
    if ($result) {
        nv_jsonOutput([
            'count' => $count
        ]);
    }

    nv_jsonOutput([
        'count' => 0
    ]);
}*/

// Thay đổi trạng thái đã xem/chưa xem, đã ẩn/chưa ẩn
if ($nv_Request->isset_request('setStatus', 'post')) {
    $status = $nv_Request->get_title('setStatus', 'post', '');
    if (!in_array($status, ['viewed', 'unviewed', 'favorite', 'unfavorite', 'hidden', 'unhidden'], true)) {
        $status = '';
    }
    $id = $nv_Request->get_int('id', 'post', 0);
    if (!empty($status) and $id) {
        switch ($status) {
            case 'viewed':
                $field_name = 'viewed_time';
                $field_value = NV_CURRENTTIME;
                break;
            case 'unviewed':
                $field_name = 'viewed_time';
                $field_value = 0;
                break;
            case 'favorite':
                $field_name = 'favorite_time';
                $field_value = NV_CURRENTTIME;
                break;
            case 'unfavorite':
                $field_name = 'favorite_time';
                $field_value = 0;
                break;
            case 'hidden':
                $field_name = 'hidden_time';
                $field_value = NV_CURRENTTIME;
                break;
            case 'unhidden':
                $field_name = 'hidden_time';
                $field_value = 0;
                break;
        }

        $db->select('mtb.id, IFNULL(jtb.shown_time, 0) AS shown_time, IFNULL(jtb.viewed_time, 0) AS viewed_time, IFNULL(jtb.favorite_time, 0) AS favorite_time, IFNULL(jtb.hidden_time, 0) AS hidden_time')
            ->from(NV_INFORM_GLOBALTABLE . ' AS mtb')
            ->join('LEFT JOIN ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS jtb ON (jtb.pid = mtb.id AND jtb.userid = ' . $user_info['userid'] . ')')
            ->where($where . ' AND mtb.id=' . $id);
        $result = $db->query($db->sql());
        $row = $result->fetch();
        if (!empty($row['id'])) {
            if (empty($row['shown_time']) and empty($row['viewed_time']) and empty($row['favorite_time']) and empty($row['hidden_time'])) {
                $db->query('INSERT IGNORE INTO ' . NV_INFORM_STATUS_GLOBALTABLE . ' (pid, userid, ' . $field_name . ') VALUES (' . $id . ', ' . $user_info['userid'] . ', ' . $field_value . ')');
            } else {
                $db->query('UPDATE ' . NV_INFORM_STATUS_GLOBALTABLE . ' SET ' . $field_name . ' = ' . $field_value . ' WHERE pid=' . $id . ' AND userid=' . $user_info['userid']);
            }
        }

        nv_jsonOutput([
            'status' => 'OK'
        ]);
    }
    nv_jsonOutput([
        'status' => 'error'
    ]);
}

// Danh sách các thông báo gửi đến người dùng hiện tại
if (defined('NV_IS_AJAX') or $nv_Request->isset_request('ajax', 'get')) {
    $inform_filter_default = $nv_Request->get_title('inform_filter', 'session', 'all');
    $filter = $nv_Request->get_title('filter', 'get', '');
    !in_array($filter, ['unviewed', 'favorite', 'hidden'], true) && $filter = '';
    !empty($filter) && $base_url .= '&amp;filter=' . $filter;
    if (defined('NV_IS_AJAX') and $filter != $inform_filter_default) {
        $nv_Request->set_Session('inform_filter', $filter);
    }
    if ($nv_Request->isset_request('ajax', 'get')) {
        $base_url .= '&amp;ajax=' . nv_genpass(10);
    }
    $page_url = $base_url;
    $page = $nv_Request->get_int('page', 'get', 1);
    if ($page > 1) {
        $page_url .= '&page=' . $page;
    }
    $per_page = defined('NV_IS_AJAX') ? $global_config['inform_numrows'] : 20;

    if ($filter == 'unviewed') {
        $where .= ' AND NOT EXISTS (SELECT 1 FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS exc WHERE exc.pid = mtb.id AND exc.userid = ' . $user_info['userid'] . ' AND (exc.viewed_time != 0 OR exc.hidden_time != 0))';
    } elseif ($filter == 'favorite') {
        $where .= ' AND EXISTS (SELECT 1 FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS exc WHERE exc.pid = mtb.id AND exc.userid = ' . $user_info['userid'] . ' AND (exc.favorite_time != 0 AND exc.hidden_time = 0))';
    } elseif ($filter == 'hidden') {
        $where .= ' AND EXISTS (SELECT 1 FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS exc WHERE exc.pid = mtb.id AND exc.userid = ' . $user_info['userid'] . ' AND exc.hidden_time != 0)';
    } else {
        $where .= ' AND NOT EXISTS (SELECT 1 FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS exc WHERE exc.pid = mtb.id AND exc.userid = ' . $user_info['userid'] . ' AND exc.hidden_time != 0)';
    }

    $db->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_INFORM_GLOBALTABLE . ' AS mtb')
        ->where($where);

    $num_items = $db->query($db->sql())
        ->fetchColumn();
    if ($num_items) {
        // Không cho tùy ý đánh số page + xác định trang trước, trang sau
        betweenURLs($page, ceil($num_items / $per_page), $base_url, '&amp;page=', $prevPage, $nextPage);
    }

    $generate_page = !defined('NV_IS_AJAX') ? nv_generate_page($base_url, $num_items, $per_page, $page, true, true) : '';

    $db->select('mtb.id, mtb.sender_role, mtb.sender_group, mtb.sender_admin, mtb.message, mtb.link, mtb.add_time, IFNULL(jtb.shown_time, 0) AS shown_time, IFNULL(jtb.viewed_time, 0) AS viewed_time, IFNULL(jtb.favorite_time, 0) AS favorite_time')
        ->join('LEFT JOIN ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS jtb ON (jtb.pid = mtb.id AND jtb.userid = ' . $user_info['userid'] . ')')
        ->order('mtb.add_time DESC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $result = $db->query($db->sql());
    $items = [];
    $notshown = [];
    $adminlist = admins_list();
    $grouplist = groups_list();
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
                $row['message'] = preg_replace('/\<\/?br\s*\/?\>/', '<br/>', $row['message']);
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

        if (!($row['sender_role'] == 'admin' and !empty($row['sender_admin'])) and !($row['sender_role'] == 'group' and !empty($row['sender_group']) and !empty($grouplist[$row['sender_group']]))) {
            $row['sender_role'] = 'system';
        }
        if (empty($row['shown_time'])) {
            $notshown[] = $row['id'];
        }

        $items[$row['id']] = $row;
    }

    if (!empty($notshown)) {
        foreach ($notshown as $id) {
            if (empty($item[$id]['viewed_time']) and empty($item[$id]['favorite_time']) and empty($item[$id]['hidden_time'])) {
                $db->query('INSERT IGNORE INTO ' . NV_INFORM_STATUS_GLOBALTABLE . ' (pid, userid, shown_time) VALUES (' . $id . ', ' . $user_info['userid'] . ', ' . NV_CURRENTTIME . ')');
            } else {
                $db->query('UPDATE ' . NV_INFORM_STATUS_GLOBALTABLE . ' SET shown_time = ' . NV_CURRENTTIME . ' WHERE pid=' . $id . ' AND userid=' . $user_info['userid']);
            }
        }
    }

    $contents = user_getlist_theme($items, $generate_page, $filter, $grouplist, $adminlist, $page_url);

    if (defined('NV_IS_AJAX')) {
        nv_jsonOutput([
            'content' => $contents,
            'count' => count($notshown)
        ]);
    }

    nv_htmlOutput($contents);
}

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];

$contents = main_theme();

$canonicalUrl = getCanonicalUrl($base_url, true, true);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
