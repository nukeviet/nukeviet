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

// Lay danh sach nhom
$sql = "SELECT g.*, d.* FROM " . NV_MOD_TABLE . "_groups AS g
    LEFT JOIN " . NV_MOD_TABLE . "_groups_detail d ON ( g.group_id = d.group_id AND d.lang = :lang )
    LEFT JOIN " . NV_MOD_TABLE . "_groups_users u ON ( g.group_id = u.group_id )
    WHERE (g.idsite = :idsite OR (g.idsite = 0 AND g.group_id > 3 AND g.siteus = 1)) AND (u.userid = :userid AND u.is_leader = 1)
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

if ($nv_Request->isset_request('gid, get_user_json ', 'post, get')) {
    $q = $nv_Request->get_title('q', 'post, get', '');
    $gid = $nv_Request->get_int('gid', 'post, get', 0);

    if (!isset($groupsList[$gid])) {
        exit($nv_Lang->getModule('no_premission_leader'));
    }

    // Báo lỗi nếu không có quyền thêm thành viên vào nhóm
    if (empty($groupsList[$gid]['config']['access_groups_add'])) {
        exit($nv_Lang->getModule('no_premission'));
    }

    $sth = $db->prepare('SELECT userid, username, email, first_name, last_name FROM ' . NV_MOD_TABLE . ' WHERE ( username LIKE :username OR email LIKE :email OR first_name LIKE :first_name OR last_name LIKE :last_name ) AND userid NOT IN (SELECT userid FROM ' . NV_MOD_TABLE . "_groups_users WHERE group_id = :gid) ORDER BY username ASC LIMIT 20");
    $sth->bindValue(':gid', $gid, PDO::PARAM_INT);
    $sth->bindValue(':username', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':email', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':first_name', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':last_name', '%' . $q . '%', PDO::PARAM_STR);
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

// lấy danh sách user chưa kích hoạt
if ($nv_Request->isset_request('gid, getuserid', 'post, get')) {
    $gid = $nv_Request->get_int('gid', 'post, get', 0);

    if (!isset($groupsList[$gid])) {
        exit($nv_Lang->getModule('no_premission_leader'));
    }

    // Báo lỗi nếu không có quyền kích hoạt thành viên
    if (empty($groupsList[$gid]['config']['access_waiting'])) {
        exit($nv_Lang->getModule('no_premission'));
    }

    // Kich hoat thanh vien
    if ($nv_Request->isset_request('act', 'get, post')) {
        $userid = $nv_Request->get_int('userid', 'get, post', 0);

        $stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_reg WHERE userid = :userid');
        $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $stmt->closeCursor();
        if (empty($row)) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
        }

        $sql = "INSERT INTO " . NV_MOD_TABLE . " (
            username, md5username, password, email, first_name, last_name, gender, photo, birthday,
            regdate, question,
            answer, passlostkey, view_mail, remember, in_groups, active, checknum,
            last_login, last_ip, last_agent, last_openid, idsite, pass_creation_time, pass_reset_request, email_creation_time
        ) VALUES (
            :username, :md5_username, :password, :email, :first_name, :last_name, '', '', 0, :regdate, :question,
            :answer, '', 0, 0, '', 1, '', 0, '', '', '', :idsite,
            :pass_creation_time, 0, :email_creation_time
        )";

        $sth = $db->prepare($sql);
        $sth->bindValue(':username', $row['username'], PDO::PARAM_STR);
        $sth->bindValue(':md5_username', nv_md5safe($row['username']), PDO::PARAM_STR);
        $sth->bindValue(':password', $row['password'], PDO::PARAM_STR);
        $sth->bindValue(':email', nv_strtolower($row['email']), PDO::PARAM_STR);
        $sth->bindValue(':first_name', $row['first_name'], PDO::PARAM_STR);
        $sth->bindValue(':last_name', $row['last_name'], PDO::PARAM_STR);
        $sth->bindValue(':regdate', $row['regdate'], PDO::PARAM_INT);
        $sth->bindValue(':question', $row['question'], PDO::PARAM_STR);
        $sth->bindValue(':answer', $row['answer'], PDO::PARAM_STR);
        $sth->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
        $sth->bindValue(':pass_creation_time', (!empty($row['password']) ? NV_CURRENTTIME : 0), PDO::PARAM_INT);
        $sth->bindValue(':email_creation_time', NV_CURRENTTIME, PDO::PARAM_INT);
        $sth->execute();
        $userid = $db->lastInsertId();

        if ($userid) {
            // Luu vao bang OpenID
            if (!empty($row['openid_info'])) {
                $reg_attribs = json_decode($row['openid_info'], true);
                $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_openid VALUES (:userid, :server, :opid , :email)');
                $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
                $stmt->bindValue(':server', $reg_attribs['server'], PDO::PARAM_STR);
                $stmt->bindValue(':opid', $reg_attribs['opid'], PDO::PARAM_STR);
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
                $stmt->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        exit('OK');
    }

    $xtpl = new XTemplate('getuserid.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_info['module_theme']);

    $nv_Lang->setModule('fullname', $global_config['name_show'] == 0 ? $nv_Lang->getModule('lastname_firstname') : $nv_Lang->getModule('firstname_lastname'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLOBAL_CONFIG', $global_config);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&gid=' . $gid . '&getuserid=1');

    $array = [];
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&amp;area=' . $area . '&amp;return=' . $return . '&amp;fsubmit=1';

    if ($nv_Request->isset_request('fsubmit', 'get')) {
        $array_user = [];
        $generate_page = '';

        $array['user_id'] = $nv_Request->get_title('user_id', 'get', '');
        $array['username'] = $nv_Request->get_title('username', 'get', '');
        $array['full_name'] = $nv_Request->get_title('full_name', 'get', '');
        $array['email'] = $nv_Request->get_title('email', 'get', '');

        $is_null = true;
        foreach ($array as $check) {
            if (!empty($check)) {
                $is_null = false;
                break;
            }
        }

        $array_where = [];
        $params = [];

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

        $db->select('*')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $stmt = $db->prepare($db->sql());
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val[0], $val[1]);
        }
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            $array_user[$row['userid']] = $row;
        }

        if (!empty($array_user)) {
            foreach ($array_user as $row) {
                $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
                $row['regdate'] = nv_datetime_format($row['regdate']);
                $row['return'] = $row[$return];
                $xtpl->assign('ROW', $row);
                $xtpl->parse('resultdata.data.row');
            }

            $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
            if (!empty($generate_page)) {
                $xtpl->assign('GENERATE_PAGE', $generate_page);
                $xtpl->parse('resultdata.data.generate_page');
            }

            $xtpl->parse('resultdata.data');
        } elseif ($nv_Request->isset_request('fsubmit', 'get')) {
            $xtpl->parse('resultdata.nodata');
        }

        $xtpl->parse('resultdata');
        $contents = $xtpl->text('resultdata');

        echo $contents;
        exit();
    }
    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    echo $contents;
    exit();
}

// Xóa thành viên
if ($nv_Request->isset_request('gid,del', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('del', 'post', 0);

    if (!isset($groupsList[$gid]) or $gid < 10) {
        exit($nv_Lang->getModule('error_group_not_found'));
    }

    // Báo lỗi nếu không có quyền xóa thành viên
    if (empty($groupsList[$gid]['config']['access_delus'])) {
        exit($nv_Lang->getModule('no_premission'));
    }

    // kiểm tra user_id xóa có nằm trong nhóm được quản lí k, hoặc nằm trong nhóm khác
    $stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid = :userid');
    $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
    $stmt->execute();
    $array_groups_user = [];
    while ($_row = $stmt->fetch()) {
        $array_groups_user[$_row['group_id']] = $_row;
    }

    // Báo lỗi nếu thành viên không thuộc nhóm quản lý
    if (!isset($array_groups_user[$gid])) {
        exit($nv_Lang->getModule('del_user_err'));
    }

    // Báo lỗi nếu thành viên là trưởng nhóm
    if ($array_groups_user[$gid]['is_leader']) {
        exit($nv_Lang->getModule('not_del_leader'));
    }

    // Báo lỗi nếu thành viên còn tham gia nhóm khác
    if (count($array_groups_user) > 1) {
        exit($nv_Lang->getModule('not_del_user'));
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $stmt = $db->prepare('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid = :userid');
        $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                exit($nv_Lang->getModule('error_group_in_site'));
            }
        } else {
            exit($nv_Lang->getModule('search_not_result'));
        }
    }

    if (!nv_del_user($uid)) {
        exit($nv_Lang->getModule('del_user_err'));
    }

    $nv_Cache->delMod($module_name);
    exit('OK');
}

// Them thanh vien vao nhom
if ($nv_Request->isset_request('gid,uid', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('uid', 'post', 0);
    if (!isset($groupsList[$gid]) or $gid < 10) {
        exit($nv_Lang->getModule('error_group_not_found'));
    }

    // Báo lỗi nếu không có quyền thêm thành viên vào nhóm
    if (empty($groupsList[$gid]['config']['access_groups_add'])) {
        exit($nv_Lang->getModule('no_premission'));
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $stmt = $db->prepare('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid = :userid');
        $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                exit($nv_Lang->getModule('error_group_in_site'));
            }
        } else {
            exit($nv_Lang->getModule('search_not_result'));
        }
    }

    if (!nv_groups_add_user($gid, $uid, 1, $module_data)) {
        exit($nv_Lang->getModule('search_not_result'));
    }

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET last_update = :last_update WHERE userid = :userid');
    $stmt->bindValue(':last_update', NV_CURRENTTIME, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
    $stmt->execute();

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('addMemberToGroup'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);

    exit('OK');
}

// Loai thanh vien khoi nhom
if ($nv_Request->isset_request('gid,exclude', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('exclude', 'post', 0);

    if ($uid == $user_info['userid']) {
        exit($nv_Lang->getModule('note_remove_leader'));
    }

    if (!isset($groupsList[$gid]) or $gid < 10) {
        exit($nv_Lang->getModule('error_group_not_found'));
    }

    // Báo lỗi nếu không có quyền loại thành viên khỏi nhóm
    if (empty($groupsList[$gid]['config']['access_groups_del'])) {
        exit($nv_Lang->getModule('no_premission'));
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $stmt = $db->prepare('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid = :userid');
        $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                exit($nv_Lang->getModule('error_group_in_site'));
            }
        } else {
            exit($nv_Lang->getModule('search_not_result'));
        }
    }

    // Không cho loại trừ quản trị khỏi nhóm
    $stmt = $db->prepare('SELECT is_leader FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :gid AND userid = :userid');
    $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    if (empty($row)) {
        exit($nv_Lang->getModule('search_not_result'));
    }
    if ($row['is_leader']) {
        exit($nv_Lang->getModule('not_exclude_leader'));
    }

    if (!nv_groups_del_user($gid, $uid, $module_data)) {
        exit($nv_Lang->getModule('UserNotInGroup'));
    }

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET last_update = :last_update WHERE userid = :userid');
    $stmt->bindValue(':last_update', NV_CURRENTTIME, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
    $stmt->execute();

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('exclude_user2'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);
    exit('OK');
}

// Duyet vao nhom
if ($nv_Request->isset_request('gid,approved', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('approved', 'post', 0);
    if (!isset($groupsList[$gid]) or $gid < 10) {
        exit($nv_Lang->getModule('error_group_not_found'));
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $stmt = $db->prepare('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid = :userid');
        $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                exit($nv_Lang->getModule('error_group_in_site'));
            }
        } else {
            exit($nv_Lang->getModule('search_not_result'));
        }
    }

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups_users SET approved = 1, time_approved = :current_time WHERE group_id = :gid AND userid = :userid');
    $stmt->bindValue(':current_time', NV_CURRENTTIME, PDO::PARAM_INT);
    $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers + 1 WHERE group_id = :gid');
    $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
    $stmt->execute();

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('approved'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);
    exit('OK');
}

// Tu choi gia nhap nhom
if ($nv_Request->isset_request('gid,denied', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('denied', 'post', 0);
    if (!isset($groupsList[$gid]) or $gid < 10) {
        exit($nv_Lang->getModule('error_group_not_found'));
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $stmt = $db->prepare('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid = :userid');
        $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                exit($nv_Lang->getModule('error_group_in_site'));
            }
        } else {
            exit($nv_Lang->getModule('search_not_result'));
        }
    }

    $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :gid AND userid = :userid');
    $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $uid, PDO::PARAM_INT);
    $stmt->execute();

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('denied'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);
    exit('OK');
}

// Chinh sua noi dung cua group
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

    $xtpl = new XTemplate($op . '.tpl', get_module_tpl_dir($op . '.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('EDIT_GROUP_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_id . '/edit');
    $xtpl->assign('DATA', $groupsList[$group_id]);
    $xtpl->assign('HTMLBODYTEXT', $htmlbodyhtml);

    $xtpl->parse('editgroup');
    $contents = $xtpl->text('editgroup');

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

    $xtpl = new XTemplate($op . '.tpl', get_module_tpl_dir($op . '.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('GROUP_MANAGER_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_id);
    $xtpl->assign('INFORM_MANAGER_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=inform&amp;manager=' . $group_id . '&amp;filter=active');
    $xtpl->assign('GTITLE', $groupsList[$group_id]['title']);

    $xtpl->parse('inform_notifications');
    $contents = $xtpl->text('inform_notifications');

    $canonicalUrl = getCanonicalUrl($page_url);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$nv_Lang->setModule('nametitle', $global_config['name_show'] == 0 ? $nv_Lang->getModule('lastname_firstname') : $nv_Lang->getModule('firstname_lastname'));

$xtpl = new XTemplate($op . '.tpl', get_module_tpl_dir($op . '.tpl'));
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('TEMPLATE', $global_config['module_theme']);
$xtpl->assign('MODULE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE);
$xtpl->assign('OP', $op);

// Danh sach thanh vien
if (count($array_op) == 2 and $array_op[0] == 'groups' and $array_op[1]) {
    $group_id = (int) $array_op[1];
    if (!isset($groupsList[$group_id]) or !($group_id < 4 or $group_id > 9)) {
        nv_redirect_location($page_url);
    }

    $page_url .= '/' . $group_id;

    // Kiem tra lai quyen truong nhom
    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :group_id AND is_leader = 1 AND userid = :userid');
    $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $user_info['userid'], PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if (!$count) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }

    $filtersql = ' userid NOT IN (SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id=' . $group_id . ')';
    if ($groupsList[$group_id]['idsite'] != $global_config['idsite'] and $groupsList[$group_id]['idsite'] == 0) {
        $filtersql .= ' AND idsite=' . $global_config['idsite'];
    }

    $groupsList[$group_id]['exp'] = !empty($groupsList[$group_id]['exp_time']) ? nv_date_format(1, $groupsList[$group_id]['exp_time']) : $nv_Lang->getModule('group_exp_unlimited');
    $groupsList[$group_id]['group_avatar'] = !empty($groupsList[$group_id]['group_avatar']) ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $groupsList[$group_id]['group_avatar'] : NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/user-group.jpg';
    $groupsList[$group_id]['group_type_mess'] = $nv_Lang->getModule('group_type_' . $groupsList[$group_id]['group_type']);
    $groupsList[$group_id]['group_type_note'] = !empty($nv_Lang->getModule('group_type_' . $groupsList[$group_id]['group_type'] . '_note')) ? $nv_Lang->getModule('group_type_' . $groupsList[$group_id]['group_type'] . '_note') : '';

    $xtpl->assign('FILTERSQL', $crypt->encrypt($filtersql, NV_CHECK_SESSION));
    $xtpl->assign('GID', $group_id);
    $xtpl->assign('DATA', $groupsList[$group_id]);
    $xtpl->assign('MIN_SEARCH', $nv_Lang->getModule('min_search', NV_MIN_SEARCH_LENGTH));
    $xtpl->assign('EDIT_GROUP_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_id . '/edit');

    if ($group_id > 9) {
        if (!empty($global_config['inform_active'])) {
            $xtpl->assign('INFORM_NOTIFICATIONS_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_id . '/inform');
            $xtpl->parse('userlist.tools.inform_notifications');
        }
        if ($groupsList[$group_id]['config']['access_groups_add'] != 0) {
            $xtpl->parse('userlist.tools.addUserGroup');
        }
        if ($groupsList[$group_id]['config']['access_addus'] != 0) {
            $xtpl->parse('userlist.tools.add_user');
        }
        if ($groupsList[$group_id]['config']['access_waiting'] != 0) {
            $xtpl->parse('userlist.tools.user_waiting');
        }
        $xtpl->parse('userlist.tools');
    }

    if (!empty($groupsList[$group_id]['description'])) {
        $xtpl->parse('userlist.group_desc');
    }

    if (!empty($groupsList[$group_id]['group_type_note'])) {
        $xtpl->parse('userlist.group_type_note');
    }

    if (!empty($groupsList[$group_id]['content'])) {
        $xtpl->parse('userlist.group_content');
    }

    $xtpl->parse('userlist');
    $contents = $xtpl->text('userlist');

    // Them vao tieu de
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

// Danh sach thanh vien (AJAX)
if ($nv_Request->isset_request('listUsers', 'get')) {
    $group_id = $nv_Request->get_int('listUsers', 'get', 0);
    $page = $nv_Request->get_page('page', 'get', 1);
    $type = $nv_Request->get_title('type', 'get', '');
    $per_page = 15;
    $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=groups&listUsers=' . $group_id;

    if (!isset($groupsList[$group_id])) {
        exit($nv_Lang->getModule('error_group_not_found'));
    }
    $xtpl->assign('GID', $group_id);
    $title = ($group_id < 10) ? $nv_Lang->getGlobal('level' . $group_id) : $groupsList[$group_id]['title'];

    $viewuser = nv_user_in_groups($global_config['whoviewuser']);

    $array_userid = [];
    $array_number = [];
    $group_users = [];

    // Danh sách xin gia nhập nhóm
    if (empty($type) or $type == 'pending') {
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :group_id AND approved = 0');
        $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
        $stmt->execute();
        $array_number['pending'] = $stmt->fetchColumn();

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
        }
    }

    // Danh sách quản trị nhóm
    if (empty($type) or $type == 'leaders') {
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :group_id AND is_leader = 1');
        $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
        $stmt->execute();
        $array_number['leaders'] = $stmt->fetchColumn();

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
        }
    }

    // Danh sách thành viên của nhóm
    if (empty($type) or $type == 'members') {
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :group_id AND approved = 1 AND is_leader = 0');
        $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
        $stmt->execute();
        $array_number['members'] = $stmt->fetchColumn();

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
        }
    }

    if (!empty($group_users)) {
        $array_userid = array_map('intval', $array_userid);
        $sql = "SELECT userid, username, md5username, first_name, last_name, email, idsite FROM " . NV_MOD_TABLE . " WHERE userid IN (" . implode(',', $array_userid) . ")";
        $result = $db->query($sql);
        $array_userid = [];
        while ($row = $result->fetch()) {
            $row['user'] = change_alias($row['username']) . '-' . $row['md5username'];
            $array_userid[$row['userid']] = $row;
        }
        $idsite = ($global_config['idsite'] == $groupsList[$group_id]['idsite']) ? 0 : $global_config['idsite'];
        foreach ($group_users as $_type => $arr_userids) {
            $xtpl->assign('PTITLE', $nv_Lang->getModule($_type . '_in_group_caption'));
            $stt = 1;
            foreach ($arr_userids as $_userid) {
                $row = $array_userid[$_userid];
                $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
                $row['stt'] = $stt;
                $xtpl->assign('LOOP', $row);

                if ($viewuser and $_type != 'pending') {
                    $xtpl->parse('listUsers.' . $_type . '.loop.viewuser');
                }

                if ($group_id > 3 and ($idsite == 0 or $idsite == $row['idsite']) and $_type != 'leaders') {
                    if ($user_info['userid'] != $_userid) {
                        if ($groupsList[$group_id]['config']['access_groups_del']) {
                            $xtpl->parse('listUsers.' . $_type . '.loop.tools.deletemember');
                        }

                        // kiểm tra thành viên có phải là admin k.
                        $stmt = $db->prepare('SELECT admin_id FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id = :userid');
                        $stmt->bindValue(':userid', $_userid, PDO::PARAM_INT);
                        $stmt->execute();

                        if (!$row_admin = $stmt->fetch()) {
                            if ($groupsList[$group_id]['config']['access_editus']) {
                                $xtpl->assign('LINK_EDIT', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=editinfo/' . $group_id . '/' . $row['userid'], true));
                                $xtpl->parse('listUsers.' . $_type . '.loop.tools.edituser');
                            }

                            $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid = :userid');
                            $stmt->bindValue(':userid', $_userid, PDO::PARAM_INT);
                            $stmt->execute();
                            $count = $stmt->fetchColumn();

                            if ($groupsList[$group_id]['config']['access_delus'] and $count == 1) {
                                $xtpl->parse('listUsers.' . $_type . '.loop.tools.deluser');
                            }
                        }

                        $xtpl->parse('listUsers.' . $_type . '.loop.tools');
                    }
                }
                $xtpl->parse('listUsers.' . $_type . '.loop');
                ++$stt;
            }

            $generate_page = nv_generate_page($base_url . '&type=' . $_type, $array_number[$_type], $per_page, $page, 'true', 'false', 'nv_urldecode_ajax', 'id_' . $_type);
            if (!empty($generate_page)) {
                $xtpl->assign('PAGE', $generate_page);
                $xtpl->parse('listUsers.' . $_type . '.page');
            }
            $xtpl->parse('listUsers.' . $_type);
        }

        if (empty($type) or $type == 'leaders') {
            // Đánh số lại số thành viên
            $numberusers = 0;
            if (isset($array_number['members'])) {
                $numberusers += $array_number['members'];
            }
            if (isset($array_number['leaders'])) {
                $numberusers += $array_number['leaders'];
            }
            if ($numberusers != $groupsList[$group_id]['numbers']) {
                $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = :numbers WHERE group_id = :group_id');
                $stmt->bindValue(':numbers', $numberusers, PDO::PARAM_INT);
                $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }

    $xtpl->parse('listUsers');
    $xtpl->out('listUsers');
    exit();
}

// Danh sach nhom (AJAX)
if ($nv_Request->isset_request('list', 'get')) {
    foreach ($groupsList as $group_id => $values) {
        $xtpl->assign('GROUP_ID', $group_id);

        $loop = [
            'title' => $values['title'],
            'add_time' => nv_datetime_format($values['add_time']),
            'exp_time' => !empty($values['exp_time']) ? nv_datetime_format($values['exp_time']) : $nv_Lang->getGlobal('indefinitely'),
            'number' => nv_number_format($values['numbers']),
            'link_userlist' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_id, true)
        ];

        $xtpl->assign('LOOP', $loop);
        $xtpl->parse('list.loop');
    }

    $xtpl->parse('list');
    $xtpl->out('list');
    exit();
}

$_lis = $module_info['funcs'];
$_alias = $module_info['alias'];
foreach ($_lis as $_li) {
    if ($_li['show_func'] and $_li['in_submenu'] and $_li['func_name'] != 'main') {
        if ($_li['func_name'] == $op or $_li['func_name'] == 'avatar') {
            continue;
        }
        if ($_li['func_name'] == 'register' and !$global_config['allowuserreg']) {
            continue;
        }

        $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
        if (!empty($nv_redirect)) {
            $href .= '&nv_redirect=' . $nv_redirect;
        }
        $li = [
            'href' => $href,
            'title' => $_li['func_name'] == 'main' ? $module_info['custom_title'] : $_li['func_custom_name']
        ];
        $xtpl->assign('NAVBAR', $li);
        $xtpl->parse('main.navbar');
    }
}

// Them vao tieu de
$array_mod_title[] = [
    'catid' => 0,
    'title' => $nv_Lang->getModule('group_manage'),
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op
];

$xtpl->parse('main');
$contents = $xtpl->text('main');

$canonicalUrl = getCanonicalUrl($page_url);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
