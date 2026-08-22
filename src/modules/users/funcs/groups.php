<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

use NukeViet\Module\users\Shared\Emails;

$page_title = $nv_Lang->getModule('group_manage');
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

$contents = '';

// SQL danh sách nhóm
$sql = "SELECT g.*, d.* FROM " . NV_MOD_TABLE . "_groups AS g
    LEFT JOIN " . NV_MOD_TABLE . "_groups_detail d ON ( g.group_id = d.group_id AND d.lang = :lang )
    LEFT JOIN " . NV_MOD_TABLE . "_groups_users u ON ( g.group_id = u.group_id )
    WHERE (g.idsite = :idsite OR (g.idsite = 0 AND g.group_id > 3 AND g.siteus = 1))
    AND (u.userid = :userid AND u.is_leader = 1 AND u.approved = 1)
    ORDER BY g.idsite, g.weight";
$stmt = $db->prepare($sql);
$stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
$stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
$stmt->bindValue(':userid', $user_info['userid'], PDO::PARAM_INT);
$stmt->execute();
$groupsList = [];
while ($row = $stmt->fetch()) {
    if ($row['group_id'] < 10) {
        $row['title'] = $nv_Lang->getGlobal('level' . $row['group_id']);
    }
    $row['config'] = unserialize($row['config'], NV_UNSERIALIZE_SAFE);
    $groupsList[$row['group_id']] = $row;
}
if (empty($groupsList)) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

// Tìm và lấy một tài khoản để thêm vào nhóm
if ($nv_Request->isset_request('gid, get_user_json ', 'post, get')) {
    $q = $nv_Request->get_title('q', 'post, get', '');
    $gid = $nv_Request->get_int('gid', 'post, get', 0);

    if (!csrf_check($nv_Request->get_string('checkss', 'post,get'), $g_csrf_key[$op_file])) {
        nv_jsonOutput([]);
    }
    if (!isset($groupsList[$gid])) {
        nv_jsonOutput([]);
    }
    // Báo lỗi nếu không có quyền thêm thành viên vào nhóm
    if (empty($groupsList[$gid]['config']['access_groups_add'])) {
        nv_jsonOutput([]);
    }
    // Không cho phép từ khóa quá ngắn để dò tài khoản
    if (nv_strlen($q) < 3) {
        nv_jsonOutput([]);
    }

    // Nhóm dùng chung của site mẹ thì chỉ tìm trong tài khoản thuộc site hiện tại
    $filter_idsite = '';
    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $filter_idsite = ' AND idsite = :idsite';
    }

    $sth = $db->prepare('SELECT userid, username, email, first_name, last_name
    FROM ' . NV_MOD_TABLE . ' WHERE (
        username LIKE :username OR email LIKE :email OR
        first_name LIKE :first_name OR last_name LIKE :last_name
    ) AND userid NOT IN (
        SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users
        WHERE group_id = :gid
    )' . $filter_idsite . ' ORDER BY username ASC LIMIT 20');
    $sth->bindValue(':gid', $gid, PDO::PARAM_INT);
    $sth->bindValue(':username', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':email', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':first_name', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':last_name', '%' . $q . '%', PDO::PARAM_STR);
    if ($filter_idsite !== '') {
        $sth->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
    }
    $sth->execute();

    $array_data = [];
    while ($row = $sth->fetch()) {
        $array_data[] = [
            'id' => $row['userid'],
            'username' => $row['username'],
            'fullname' => nv_show_name_user($row['first_name'], $row['last_name'])
        ];
    }

    nv_jsonOutput($array_data);
}

// Lấy danh sách user chưa kích hoạt
if ($nv_Request->isset_request('gid, getuserid', 'post, get')) {
    $gid = $nv_Request->get_int('gid', 'post, get', 0);

    if (!csrf_check($nv_Request->get_string('checkss', 'post,get'), $g_csrf_key[$op_file])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    // Chỉ thao tác với nhóm tự tạo, không cho gán tài khoản mới vào nhóm hệ thống
    if (!isset($groupsList[$gid]) or $gid < 10) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('no_premission_leader')
        ]);
    }

    // Báo lỗi nếu không có quyền kích hoạt thành viên
    if (empty($groupsList[$gid]['config']['access_waiting'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('no_premission')
        ]);
    }

    // Kích hoạt tài khoản
    if ($nv_Request->isset_request('act', 'get, post')) {
        $userid = $nv_Request->get_int('userid', 'get, post', 0);

        $stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_reg WHERE userid = :userid' . ($global_config['idsite'] > 0 ? ' AND idsite = :idsite' : ''));
        $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
        if ($global_config['idsite'] > 0) {
            $stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
        }
        $stmt->execute();
        $row = $stmt->fetch();
        $stmt->closeCursor();
        if (empty($row)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('search_not_result')
            ]);
        }

        $sql = "INSERT INTO " . NV_MOD_TABLE . " (
            username, md5username, password, email, first_name, last_name, gender, photo, birthday, sig,
            regdate, question,
            answer, passlostkey, view_mail, remember, in_groups, active, checknum,
            last_login, last_ip, last_agent, last_openid, idsite, pass_creation_time, pass_reset_request, email_creation_time,
            email_verification_time, active_obj
        ) VALUES (
            :username, :md5_username, :password, :email, :first_name, :last_name, :gender, '', :birthday, :sig,
            :regdate, :question,
            :answer, '', 0, 0, '', 1, '', 0, '', '', '', :idsite,
            :pass_creation_time, 0, :email_creation_time,
            -2, :active_obj
        )";

        $sth = $db->prepare($sql);
        $sth->bindValue(':username', $row['username'], PDO::PARAM_STR);
        $sth->bindValue(':md5_username', nv_md5safe($row['username']), PDO::PARAM_STR);
        $sth->bindValue(':password', $row['password'], PDO::PARAM_STR);
        $sth->bindValue(':email', nv_strtolower($row['email']), PDO::PARAM_STR);
        $sth->bindValue(':first_name', $row['first_name'], PDO::PARAM_STR);
        $sth->bindValue(':last_name', $row['last_name'], PDO::PARAM_STR);
        $sth->bindValue(':gender', $row['gender'], PDO::PARAM_STR);
        $sth->bindValue(':birthday', $row['birthday'], PDO::PARAM_INT);
        $sth->bindValue(':sig', $row['sig'], PDO::PARAM_STR);
        $sth->bindValue(':regdate', $row['regdate'], PDO::PARAM_INT);
        $sth->bindValue(':question', $row['question'], PDO::PARAM_STR);
        $sth->bindValue(':answer', $row['answer'], PDO::PARAM_STR);
        $sth->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
        $sth->bindValue(':pass_creation_time', (!empty($row['password']) ? NV_CURRENTTIME : 0), PDO::PARAM_INT);
        $sth->bindValue(':email_creation_time', NV_CURRENTTIME, PDO::PARAM_INT);
        $sth->bindValue(':active_obj', (string) $user_info['userid'], PDO::PARAM_STR);
        $sth->execute();
        $userid = $db->lastInsertId();

        if ($userid) {
            // Lưu vào bảng OpenID
            if (!empty($row['openid_info'])) {
                $reg_attribs = json_decode($row['openid_info'], true);
                $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_openid (
                    userid, openid, opid, id, email
                ) VALUES (
                    :userid, :openid, :opid, :id, :email
                )');
                $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
                $stmt->bindValue(':openid', $reg_attribs['server'], PDO::PARAM_STR);
                $stmt->bindValue(':opid', $reg_attribs['opid'], PDO::PARAM_STR);
                $stmt->bindValue(':id', $reg_attribs['openid'], PDO::PARAM_STR);
                $stmt->bindValue(':email', $reg_attribs['email'], PDO::PARAM_STR);
                $stmt->execute();
            }

            $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_groups_users (
                group_id, userid, is_leader, approved, data, time_requested, time_approved
            ) VALUES(
                :gid, :userid, 0, 1, \'\', :time_requested, :time_approved
            )');
            $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
            $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
            $stmt->bindValue(':time_requested', NV_CURRENTTIME, PDO::PARAM_INT);
            $stmt->bindValue(':time_approved', NV_CURRENTTIME, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers + 1 WHERE group_id = 4 OR group_id = :gid');
            $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET group_id = :gid, in_groups = :gid_str WHERE userid = :userid');
            $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
            $stmt->bindValue(':gid_str', (string) $gid, PDO::PARAM_STR);
            $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
            $stmt->execute();
            $users_info = json_decode($row['users_info'], true);
            $query_field = [];
            $query_field['userid'] = $userid;
            $result_field = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field ORDER BY fid ASC');
            while ($row_f = $result_field->fetch()) {
                if ($row_f['is_system'] == 1) {
                    continue;
                }
                if ($row_f['field_type'] == 'number' or $row_f['field_type'] == 'date') {
                    $default_value = (float) ($row_f['default_value']);
                } else {
                    $default_value = get_value_by_lang($row_f['default_value']);
                }
                $query_field[$row_f['field']] = (isset($users_info[$row_f['field']])) ? $users_info[$row_f['field']] : $default_value;
            }

            if (userInfoTabDb($query_field)) {
                $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_reg WHERE userid = :userid');
                $stmt->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
                $stmt->execute();

                // Xóa thông báo hệ thống về tài khoản chờ kích hoạt
                nv_delete_notification(NV_LANG_DATA, $module_name, 'send_active_link_fail', $row['userid']);

                nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('active_users'), 'userid: ' . $userid . ' - username: ' . $row['username'], $user_info['userid']);

                $send_data = [[
                    'to' => $row['email'],
                    'data' => [
                        'first_name' => $row['first_name'],
                        'last_name' => $row['last_name'],
                        'username' => $row['username'],
                        'email' => $row['email'],
                        'gender' => $row['gender'],
                        'link' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, NV_MY_DOMAIN),
                        'lang' => NV_LANG_INTERFACE
                    ]
                ]];
                nv_sendmail_template_async([$module_name, Emails::ADDED_BY_LEADER], $send_data, NV_LANG_INTERFACE);
            } else {
                $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . ' WHERE userid = :userid');
                $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
                $stmt->execute();
            }

            $nv_Cache->delMod($module_name);
        }

        nv_jsonOutput([
            'status' => 'ok',
            'mess' => $nv_Lang->getModule('actived_users')
        ]);
    }

    $nv_Lang->setModule('fullname', $global_config['name_show'] == 0 ? $nv_Lang->getModule('lastname_firstname') : $nv_Lang->getModule('firstname_lastname'));

    // Submit form tìm kiếm
    if ($nv_Request->isset_request('fsubmit', 'get')) {
        $array = [];
        $array_user = [];
        $generate_page = '';

        $array['user_id'] = $nv_Request->get_title('user_id', 'get', '');
        $array['username'] = $nv_Request->get_title('username', 'get', '');
        $array['full_name'] = $nv_Request->get_title('full_name', 'get', '');
        $array['email'] = $nv_Request->get_title('email', 'get', '');

        $array_where = [];
        $params = [];
        $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;getuserid=1&amp;gid=' . $gid . '&amp;checkss=' . csrf_create($g_csrf_key[$op_file]) . '&amp;fsubmit=1';

        // Chỉ tìm trong các tài khoản chờ thuộc site hiện tại
        if ($global_config['idsite'] > 0) {
            $array_where[] = 'idsite = :idsite';
            $params[':idsite'] = [$global_config['idsite'], PDO::PARAM_INT];
        }

        if (!empty($array['user_id'])) {
            $base_url .= '&amp;user_id=' . rawurlencode($array['user_id']);
            $array_where[] = "userid = :user_id";
            $params[':user_id'] = [$array['user_id'], PDO::PARAM_INT];
        }

        if (!empty($array['username'])) {
            $base_url .= '&amp;username=' . rawurlencode($array['username']);
            $array_where[] = "username LIKE :username";
            $params[':username'] = ['%' . $array['username'] . '%', PDO::PARAM_STR];
        }

        if (!empty($array['full_name'])) {
            $base_url .= '&amp;full_name=' . rawurlencode($array['full_name']);

            $where_fullname = $global_config['name_show'] == 0 ? "concat(last_name, ' ', first_name)" : "concat(first_name, ' ', last_name)";
            $array_where[] = $where_fullname . " LIKE :full_name";
            $params[':full_name'] = ['%' . $array['full_name'] . '%', PDO::PARAM_STR];
        }

        if (!empty($array['email'])) {
            $base_url .= '&amp;email=' . rawurlencode($array['email']);
            $array_where[] = "email LIKE :email";
            $params[':email'] = ['%' . $array['email'] . '%', PDO::PARAM_STR];
        }

        $page = $nv_Request->get_page('page', 'get', 1);
        $per_page = 10;

        $sql = 'SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_reg';
        if (!empty($array_where)) {
            $sql .= ' WHERE ' . implode(' AND ', $array_where);
        }

        $stmt = $db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val[0], $val[1]);
        }
        $stmt->execute();
        $num_items = $stmt->fetchColumn();

        $db->sqlreset()
            ->select('*')
            ->from(NV_MOD_TABLE . '_reg')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        if (!empty($array_where)) {
            $db->where(implode(' AND ', $array_where));
        }

        $stmt = $db->prepare($db->sql());
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val[0], $val[1]);
        }
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            $array_user[$row['userid']] = $row;
        }

        // Xử lý dữ liệu trước khi hiển thị
        foreach ($array_user as $_userid => $row) {
            $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
            $row['regdate'] = nv_datetime_format($row['regdate']);

            $array_user[$_userid] = $row;
        }

        $array['generate_page'] = nv_generate_page($base_url, $num_items, $per_page, $page, true, true, 'nv_urldecode_ajax', 'resultdata');
        $contents = user_groups_getuserid_result($array, $array_user);

        nv_htmlOutput($contents);
    }

    $contents = user_groups_getuserid($gid);
    nv_jsonOutput([
        'status' => 'ok',
        'mess' => 'Success!',
        'html' => $contents
    ]);
}

// Xóa hẳn thành viên do mình quản lý
if ($nv_Request->isset_request('gid,del', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('del', 'post', 0);

    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $g_csrf_key[$op_file])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    if (!isset($groupsList[$gid]) or $gid < 10) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }

    // Báo lỗi nếu không có quyền xóa thành viên
    if (empty($groupsList[$gid]['config']['access_delus'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('no_premission')
        ]);
    }

    // Kiểm tra user_id xóa có nằm trong nhóm được quản lý không, hoặc nằm trong nhóm khác
    $stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid = :userid');
    $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
    $stmt->execute();
    $array_groups_user = [];
    while ($_row = $stmt->fetch()) {
        $array_groups_user[$_row['group_id']] = $_row;
    }

    // Báo lỗi nếu thành viên không thuộc nhóm quản lý
    if (!isset($array_groups_user[$gid])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('del_user_err')
        ]);
    }

    // Báo lỗi nếu thành viên là trưởng nhóm
    if ($array_groups_user[$gid]['is_leader']) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('not_del_leader')
        ]);
    }

    // Báo lỗi nếu thành viên còn tham gia nhóm khác
    if (count($array_groups_user) > 1) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('not_del_user')
        ]);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $stmt = $db->prepare('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid = :userid');
        $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('error_group_in_site')
                ]);
            }
        } else {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('search_not_result')
            ]);
        }
    }

    if (!nv_del_user($uid)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('del_user_err')
        ]);
    }

    $nv_Cache->delMod($module_name);
    nv_jsonOutput([
        'status' => 'ok',
        'mess' => $nv_Lang->getModule('active_success')
    ]);
}

// Thêm thành viên vào nhóm
if ($nv_Request->isset_request('gid,uid', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('uid', 'post', 0);

    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $g_csrf_key[$op_file])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    if (!isset($groupsList[$gid]) or $gid < 10) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }
    // Báo lỗi nếu không có quyền thêm thành viên vào nhóm
    if (empty($groupsList[$gid]['config']['access_groups_add'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('no_premission')
        ]);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $stmt = $db->prepare('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid = :userid');
        $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('error_group_in_site')
                ]);
            }
        } else {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('search_not_result')
            ]);
        }
    }

    if (!nv_groups_add_user($gid, $uid, 1, $module_data)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('search_not_result')
        ]);
    }

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET last_update = :last_update WHERE userid = :userid');
    $stmt->bindValue(':last_update', NV_CURRENTTIME, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
    $stmt->execute();

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('addMemberToGroup'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);

    nv_jsonOutput([
        'status' => 'ok',
        'mess' => $nv_Lang->getModule('active_success')
    ]);
}

// Loại thành viên khỏi nhóm
if ($nv_Request->isset_request('gid,exclude', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('exclude', 'post', 0);

    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $g_csrf_key[$op_file])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    if ($uid == $user_info['userid']) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('note_remove_leader')
        ]);
    }
    if (!isset($groupsList[$gid]) or $gid < 10) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }
    // Báo lỗi nếu không có quyền loại thành viên khỏi nhóm
    if (empty($groupsList[$gid]['config']['access_groups_del'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('no_premission')
        ]);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $stmt = $db->prepare('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid = :userid');
        $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('error_group_in_site')
                ]);
            }
        } else {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('search_not_result')
            ]);
        }
    }

    // Không cho loại trừ quản trị khỏi nhóm
    $stmt = $db->prepare('SELECT is_leader FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :gid AND userid = :userid');
    $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    if (empty($row)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('search_not_result')
        ]);
    }
    if ($row['is_leader']) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('not_exclude_leader')
        ]);
    }

    if (!nv_groups_del_user($gid, $uid, $module_data)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('UserNotInGroup')
        ]);
    }

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET last_update = :last_update WHERE userid = :userid');
    $stmt->bindValue(':last_update', NV_CURRENTTIME, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
    $stmt->execute();

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('exclude_user2'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);
    nv_jsonOutput([
        'status' => 'ok',
        'mess' => $nv_Lang->getModule('active_success')
    ]);
}

// Duyệt vào nhóm
if ($nv_Request->isset_request('gid,approved', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('approved', 'post', 0);

    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $g_csrf_key[$op_file])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    if (!isset($groupsList[$gid]) or $gid < 10) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $stmt = $db->prepare('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid = :userid');
        $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('error_group_in_site')
                ]);
            }
        } else {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('search_not_result')
            ]);
        }
    }

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups_users
        SET approved = 1, time_approved = :current_time
        WHERE group_id = :gid AND userid = :userid AND approved = 0');
    $stmt->bindValue(':current_time', NV_CURRENTTIME, PDO::PARAM_INT);
    $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
    $stmt->execute();

    if (!$stmt->rowCount()) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('UserNotInGroup')
        ]);
    }

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers + 1 WHERE group_id = :gid');
    $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
    $stmt->execute();

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('approved'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);
    nv_jsonOutput([
        'status' => 'ok',
        'mess' => $nv_Lang->getModule('active_success')
    ]);
}

// Từ chối gia nhập nhóm
if ($nv_Request->isset_request('gid,denied', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('denied', 'post', 0);

    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $g_csrf_key[$op_file])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    if (!isset($groupsList[$gid]) or $gid < 10) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $stmt = $db->prepare('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid = :userid');
        $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('error_group_not_found')
                ]);
            }
        } else {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('search_not_result')
            ]);
        }
    }

    $stmt = $db->prepare('SELECT approved, is_leader FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :gid AND userid = :userid');
    $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $stmt->closeCursor();

    if (empty($row) or !empty($row['approved']) or !empty($row['is_leader'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('UserNotInGroup')
        ]);
    }

    $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :gid AND userid = :userid AND approved = 0 AND is_leader = 0');
    $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
    $stmt->execute();

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('denied'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);
    nv_jsonOutput([
        'status' => 'ok',
        'mess' => $nv_Lang->getModule('active_success')
    ]);
}

// Chỉnh sửa nội dung của nhóm
if (count($array_op) == 3 and $array_op[0] == 'groups' and $array_op[1] and $array_op[2] == 'edit') {
    $group_id = (int) $array_op[1];
    if (!isset($groupsList[$group_id]) or !($group_id < 4 or $group_id > 9)) {
        nv_redirect_location($page_url);
    }

    $page_url .= '/' . $group_id . '/edit';

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :group_id AND is_leader = 1 AND userid = :userid');
    $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $user_info['userid'], PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if (!$count) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }

    if (!defined('NV_EDITOR')) {
        define('NV_EDITOR', 'ckeditor5-classic');
    }
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';

    if ($nv_Request->isset_request('save', 'post')) {
        if (!csrf_check($nv_Request->get_string('checkss', 'post'), $g_csrf_key[$op_file])) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => '',
                'mess' => $nv_Lang->getGlobal('error_checkss')
            ]);
        }

        $rowcontent = [];
        $rowcontent['group_title'] = $nv_Request->get_title('group_title', 'post', '');
        if (empty($rowcontent['group_title'])) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'group_title',
                'mess' => $nv_Lang->getModule('group_title_empty')
            ]);
        }
        $rowcontent['group_desc'] = $nv_Request->get_title('group_desc', 'post', '');
        $group_content = $nv_Request->get_string('group_content', 'post', '');
        $rowcontent['group_content'] = defined('NV_EDITOR') ? nv_nl2br($group_content, '') : nv_nl2br(nv_htmlspecialchars(strip_tags($group_content)), '<br />');

        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups_detail
            SET title = :title, description = :description, content = :content
            WHERE group_id = :group_id AND lang = :lang');
        $stmt->bindValue(':title', $rowcontent['group_title'], PDO::PARAM_STR);
        $stmt->bindValue(':description', $rowcontent['group_desc'], PDO::PARAM_STR);
        $stmt->bindValue(':content', $rowcontent['group_content'], PDO::PARAM_STR);
        $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
        $stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();

        $nv_Cache->delMod($module_name);
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('group_edit'), 'Group ID: ' . $group_id, $user_info['userid']);

        nv_jsonOutput([
            'status' => 'ok',
            'redirect' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '/' . $group_id, true),
            'mess' => $nv_Lang->getModule('group_edit_saved')
        ]);
    }

    $htmlbodyhtml = htmlspecialchars(nv_editor_br2nl($groupsList[$group_id]['content']));
    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
        $htmlbodyhtml = nv_aleditor('group_content', '100%', '300px', $htmlbodyhtml, 'Basic');
    } else {
        $htmlbodyhtml = '<textarea class="textareaform" name="group_content" id="group_content" cols="60" rows="15">' . $htmlbodyhtml . '</textarea>';
    }

    $group_data = $groupsList[$group_id];
    $group_data['htmlbodyhtml'] = $htmlbodyhtml;
    $group_data['checkss'] = csrf_create($g_csrf_key[$op_file]);

    $contents = user_groups_edit($group_data);

    $array_mod_title[] = [
        'catid' => 0,
        'title' => $nv_Lang->getModule('group_manage'),
        'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op
    ];
    $array_mod_title[] = [
        'catid' => 0,
        'title' => $groupsList[$group_id]['title'],
        'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_id
    ];

    $canonicalUrl = getCanonicalUrl($page_url);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Quản lý thông báo
if (!empty($global_config['inform_active']) and count($array_op) == 3 and $array_op[0] == 'groups' and $array_op[1] and $array_op[2] == 'inform') {
    $group_id = (int) $array_op[1];
    if (!isset($groupsList[$group_id]) or !($group_id < 4 or $group_id > 9)) {
        nv_redirect_location($page_url);
    }

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :group_id AND is_leader = 1 AND userid = :userid');
    $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $user_info['userid'], PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if (!$count) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }

    $page_url .= '/' . $group_id . '/inform';
    $contents = user_groups_inform($groupsList[$group_id]);
    $canonicalUrl = getCanonicalUrl($page_url);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$nv_Lang->setModule('nametitle', $global_config['name_show'] == 0 ? $nv_Lang->getModule('lastname_firstname') : $nv_Lang->getModule('firstname_lastname'));

// Danh sách thành viên trong nhóm
if (count($array_op) == 2 and $array_op[0] == 'groups' and $array_op[1]) {
    $group_id = (int) $array_op[1];
    if (!isset($groupsList[$group_id]) or !($group_id < 4 or $group_id > 9)) {
        nv_redirect_location($page_url);
    }

    $page_url .= '/' . $group_id;

    // Kiểm tra quyền trưởng nhóm
    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :group_id AND is_leader = 1 AND userid = :userid');
    $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $user_info['userid'], PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if (!$count) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }

    $group_data = $groupsList[$group_id];
    $group_data['exp'] = !empty($group_data['exp_time']) ? nv_date_format(1, $group_data['exp_time']) : $nv_Lang->getModule('group_exp_unlimited');
    $group_data['group_avatar'] = !empty($group_data['group_avatar']) ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $group_data['group_avatar'] : NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/user-group.jpg';
    $group_data['group_type_mess'] = $nv_Lang->getModule('group_type_' . $group_data['group_type']);
    $group_data['group_type_note'] = !empty($nv_Lang->getModule('group_type_' . $group_data['group_type'] . '_note')) ? $nv_Lang->getModule('group_type_' . $group_data['group_type'] . '_note') : '';
    $group_data['in_idsite'] = ($global_config['idsite'] == $group_data['idsite']) ? 0 : $global_config['idsite'];
    $group_data['viewuser_allowed'] = nv_user_in_groups($global_config['whoviewuser']);
    $group_data['link_types'] = [];

    // Breadcrumbs
    $array_mod_title[] = [
        'catid' => 0,
        'title' => $nv_Lang->getModule('group_manage'),
        'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op
    ];
    $array_mod_title[] = [
        'catid' => 0,
        'title' => $group_data['title'],
        'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_id
    ];

    /**
     * Phần lấy và hiển thị danh sách thành viên.
     */
    $array_search = [];
    $array_search['type'] = $nv_Request->get_title('type', 'get', '');
    if (!empty($array_search['type']) && !in_array($array_search['type'], ['pending', 'leaders', 'members'], true)) {
        nv_error404();
    }

    $page = $nv_Request->get_page('page', 'get', 1);
    $per_page_detail = 20;
    $per_page = empty($array_search['type']) ? 5 : $per_page_detail;
    $base_url = $page_url;
    if (!empty($array_search['type'])) {
        $base_url .= '&amp;type=' . $array_search['type'];
    }
    if (empty($array_search['type']) and $page != 1) {
        nv_error404();
    }

    $array_userid = [];
    $array_number = [];
    $group_users = [];
    $array_users = [];
    $array_admins = [];

    // Danh sách xin gia nhập nhóm
    if (empty($array_search['type']) or $array_search['type'] == 'pending') {
        $db->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_MOD_TABLE . '_groups_users')
            ->where('group_id = :group_id AND approved = 0');
        $stmt = $db->prepare($db->sql());
        $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
        $stmt->execute();
        $array_number['pending'] = $stmt->fetchColumn();

        // Không cho tùy ý đánh số page + xác định trang trước, trang sau
        betweenURLs($page, ceil($array_number['pending'] / $per_page), $base_url, '&amp;page=', $prevPage, $nextPage);

        if ($array_number['pending']) {
            $db->select('userid')
                ->limit($per_page)
                ->offset(($page - 1) * $per_page);
            $stmt = $db->prepare($db->sql());
            $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $group_users['pending'][] = $row['userid'];
                $array_userid[] = $row['userid'];
            }
            $stmt->closeCursor();

            $group_data['link_types']['pending'] = $page_url . '&amp;type=pending';
        }
    }

    // Danh sách quản trị nhóm
    if (empty($array_search['type']) or $array_search['type'] == 'leaders') {
        $db->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_MOD_TABLE . '_groups_users')
            ->where('group_id = :group_id AND is_leader = 1');
        $stmt = $db->prepare($db->sql());
        $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
        $stmt->execute();
        $array_number['leaders'] = $stmt->fetchColumn();

        // Không cho tùy ý đánh số page + xác định trang trước, trang sau
        betweenURLs($page, ceil($array_number['leaders'] / $per_page), $base_url, '&amp;page=', $prevPage, $nextPage);

        if ($array_number['leaders']) {
            $db->select('userid')
                ->limit($per_page)
                ->offset(($page - 1) * $per_page);
            $stmt = $db->prepare($db->sql());
            $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $group_users['leaders'][] = $row['userid'];
                $array_userid[] = $row['userid'];
            }
            $stmt->closeCursor();

            $group_data['link_types']['leaders'] = $page_url . '&amp;type=leaders';
        }
    }

    // Danh sách thành viên của nhóm
    if (empty($array_search['type']) or $array_search['type'] == 'members') {
        $db->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_MOD_TABLE . '_groups_users')
            ->where('group_id = :group_id AND approved = 1 AND is_leader = 0');
        $stmt = $db->prepare($db->sql());
        $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
        $stmt->execute();
        $array_number['members'] = $stmt->fetchColumn();

        // Không cho tùy ý đánh số page + xác định trang trước, trang sau
        betweenURLs($page, ceil($array_number['members'] / $per_page), $base_url, '&amp;page=', $prevPage, $nextPage);

        if ($array_number['members']) {
            $db->select('userid')
                ->limit($per_page)
                ->offset(($page - 1) * $per_page);
            $stmt = $db->prepare($db->sql());
            $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $group_users['members'][] = $row['userid'];
                $array_userid[] = $row['userid'];
            }
            $stmt->closeCursor();

            $group_data['link_types']['members'] = $page_url . '&amp;type=members';
        }
    }

    // Lấy thông tin của các thành viên nếu có
    if (!empty($array_userid)) {
        $array_userid = array_map('intval', $array_userid);
        $sql = "SELECT userid, username, md5username, first_name, last_name, email, idsite
        FROM " . NV_MOD_TABLE . " WHERE userid IN (" . implode(',', $array_userid) . ")";
        $result = $db->query($sql);
        while ($row = $result->fetch()) {
            $row['user'] = change_alias($row['username']) . '-' . $row['md5username'];
            $row['group_count'] = 0;
            $array_users[$row['userid']] = $row;
        }
        $result->closeCursor();

        // Lấy danh sách admin_id của các thành viên này
        $sql = "SELECT admin_id FROM " . NV_AUTHORS_GLOBALTABLE . " WHERE admin_id IN (" . implode(',', $array_userid) . ")";
        $array_admins = $db->query($sql)->fetchAll(PDO::FETCH_COLUMN, 0);
        !empty($array_admins) && $array_admins = array_map('intval', $array_admins);

        // Lấy số nhóm mà các thành viên này đang tham gia
        $sql = "SELECT userid, COUNT(*) AS group_count FROM " . NV_MOD_TABLE . "_groups_users
        WHERE userid IN (" . implode(',', $array_userid) . ") GROUP BY userid";
        $result = $db->query($sql);
        while ($row = $result->fetch()) {
            if (isset($array_users[$row['userid']])) {
                $array_users[$row['userid']]['group_count'] = $row['group_count'];
            }
        }
        $result->closeCursor();
    }

    // Cập nhật lại số lượng thành viên trong nhóm nếu sai lệch giữa SQL thật với giá trị đã lưu
    if (empty($array_search['type']) or $array_search['type'] == 'leaders') {
        $numberusers = 0;
        if (isset($array_number['members'])) {
            $numberusers += $array_number['members'];
        }
        if (isset($array_number['leaders'])) {
            $numberusers += $array_number['leaders'];
        }
        if ($numberusers != $group_data['numbers']) {
            $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = :numbers WHERE group_id = :group_id');
            $stmt->bindValue(':numbers', $numberusers, PDO::PARAM_INT);
            $stmt->bindValue(':group_id', $group_data['group_id'], PDO::PARAM_INT);
            $stmt->execute();

            $group_data['numbers'] = $numberusers;
        }
    }

    // Xử lý lại dữ liệu để đưa vào giao diện
    foreach ($group_users as $_type => $arr_userids) {
        foreach ($arr_userids as $_key => $_userid) {
            $_userid = (int) $_userid;
            if (!isset($array_users[$_userid])) {
                unset($group_users[$_type][$_key]);
                continue;
            }

            $row = $array_users[$_userid];
            $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
            $row['tools_allowed'] = ($group_id > 3 and ($group_data['in_idsite'] == 0 or $group_data['in_idsite'] == $row['idsite']) and $_type != 'leaders') && $user_info['userid'] != $_userid;
            $row['is_admin'] = in_array($_userid, $array_admins, true);
            $row['link_edit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/' . $group_id . '/' . $row['userid'];
            $row['link_view'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=memberlist/' . $row['user'];

            $group_users[$_type][$_key] = $row;
        }
    }

    $group_data['data_number'] = $array_number;
    $group_data['checkss'] = csrf_create($g_csrf_key[$op_file]);

    // Phân trang khi xem danh sách thành viên của một loại cụ thể
    $group_data['generate_page'] = '';
    if (!empty($array_search['type']) and isset($array_number[$array_search['type']]) and $array_number[$array_search['type']] > $per_page) {
        $group_data['generate_page'] = nv_generate_page($base_url, $array_number[$array_search['type']], $per_page, $page);
    }

    $page_url = $base_url;
    if ($page > 1) {
        $page_url .= '&amp;page=' . $page;
    }

    $canonicalUrl = getCanonicalUrl($page_url);
    $contents = user_groups_list_users($group_data, $group_users, $array_users, $array_search);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Breadcrumb
$array_mod_title[] = [
    'catid' => 0,
    'title' => $nv_Lang->getModule('group_manage'),
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op
];

$canonicalUrl = getCanonicalUrl($page_url);
$contents = user_groups($groupsList);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
