<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

$page_title = $lang_module['group_manage'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

$contents = '';

// Lay danh sach nhom
$sql = 'SELECT g.*, d.* FROM ' . NV_MOD_TABLE . '_groups AS g 
    LEFT JOIN ' . NV_MOD_TABLE . "_groups_detail d ON ( g.group_id = d.group_id AND d.lang='" . NV_LANG_DATA . "' ) 
    LEFT JOIN " . NV_MOD_TABLE . '_groups_users u ON ( g.group_id = u.group_id ) 
    WHERE (g.idsite = ' . $global_config['idsite'] . ' OR (g.idsite =0 AND g.group_id > 3 AND g.siteus = 1)) AND (u.userid = ' . $user_info['userid'] . ' AND u.is_leader = 1) 
    ORDER BY g.idsite, g.weight';
$result = $db->query($sql);
$groupsList = [];
while ($row = $result->fetch()) {
    if ($row['group_id'] < 10) {
        $row['title'] = $lang_global['level' . $row['group_id']];
    }
    $row['config'] = unserialize($row['config']);
    $groupsList[$row['group_id']] = $row;
}
if (empty($groupsList)) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

if ($nv_Request->isset_request('gid, get_user_json ', 'post, get')) {
    $q = $nv_Request->get_title('q', 'post, get', '');
    $gid = $nv_Request->get_int('gid', 'post, get', 0);

    if (!isset($groupsList[$gid])) {
        exit($lang_module['no_premission_leader']);
    }

    // Báo lỗi nếu không có quyền thêm thành viên vào nhóm
    if (empty($groupsList[$gid]['config']['access_groups_add'])) {
        exit($lang_module['no_premission']);
    }

    $db->sqlreset()
        ->select('userid, username, email, first_name, last_name')
        ->from(NV_MOD_TABLE)
        ->where('( username LIKE :username OR email LIKE :email OR first_name like :first_name OR last_name like :last_name ) AND userid NOT IN (SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = ' . $gid . ')')
        ->order('username ASC')
        ->limit(20);

    $sth = $db->prepare($db->sql());
    $sth->bindValue(':username', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':email', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':first_name', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':last_name', '%' . $q . '%', PDO::PARAM_STR);
    $sth->execute();

    $array_data = [];
    while (list($userid, $username, $email, $first_name, $last_name) = $sth->fetch(3)) {
        $array_data[] = [
            'id' => $userid,
            'username' => $username,
            'fullname' => nv_show_name_user($first_name, $last_name)
        ];
    }

    nv_jsonOutput($array_data);
}

// lấy danh sách user chưa kích hoạt
if ($nv_Request->isset_request('gid, getuserid', 'post, get')) {
    $gid = $nv_Request->get_int('gid', 'post, get', 0);

    if (!isset($groupsList[$gid])) {
        exit($lang_module['no_premission_leader']);
    }

    // Báo lỗi nếu không có quyền kích hoạt thành viên
    if (empty($groupsList[$gid]['config']['access_waiting'])) {
        exit($lang_module['no_premission']);
    }

    // Kich hoat thanh vien
    if ($nv_Request->isset_request('act', 'get, post')) {
        $userid = $nv_Request->get_int('userid', 'get, post', 0);

        $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_reg WHERE userid=' . $userid;
        $row = $db->query($sql)->fetch();
        if (empty($row)) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
        }

        $sql = 'INSERT INTO ' . NV_MOD_TABLE . " (
            username, md5username, password, email, first_name, last_name, gender, photo, birthday,
            regdate, question,
            answer, passlostkey, view_mail, remember, in_groups, active, checknum,
            last_login, last_ip, last_agent, last_openid, idsite
            ) VALUES (
            :username,
            :md5_username,
            :password,
            :email,
            :first_name,
            :last_name,
            '', '', 0, " . $row['regdate'] . ",
            :question,
            :answer,
            '', 0, 0, '', 1, '', 0, '', '', '', " . $global_config['idsite'] . ')';

        $data_insert = [];
        $data_insert['username'] = $row['username'];
        $data_insert['md5_username'] = nv_md5safe($row['username']);
        $data_insert['password'] = $row['password'];
        $data_insert['email'] = nv_strtolower($row['email']);
        $data_insert['first_name'] = $row['first_name'];
        $data_insert['last_name'] = $row['last_name'];
        $data_insert['question'] = $row['question'];
        $data_insert['answer'] = $row['answer'];
        $userid = $db->insert_id($sql, 'userid', $data_insert);

        if ($userid) {
            // Luu vao bang OpenID
            if (!empty($row['openid_info'])) {
                $reg_attribs = unserialize(nv_base64_decode($row['openid_info']));
                $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_openid VALUES (' . $userid . ', :server, :opid , :email)');
                $stmt->bindParam(':server', $reg_attribs['server'], PDO::PARAM_STR);
                $stmt->bindParam(':opid', $reg_attribs['opid'], PDO::PARAM_STR);
                $stmt->bindParam(':email', $reg_attribs['email'], PDO::PARAM_STR);
                $stmt->execute();
            }

            $db->query('INSERT INTO ' . NV_MOD_TABLE . '_groups_users (
                group_id, userid, is_leader, approved, data, time_requested, time_approved
            ) VALUES(
                ' . $gid . ', ' . $userid . ', 0, 1, \'\', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . '
            )');
            $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers+1 WHERE group_id=4 or group_id=' . $gid);
            $db->query('UPDATE ' . NV_MOD_TABLE . ' SET group_id = ' . $gid . ', in_groups=' . $gid . ' WHERE userid=' . $userid);
            $users_info = unserialize(nv_base64_decode($row['users_info']));
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
                    $default_value = $db->quote($row_f['default_value']);
                }
                $query_field[$row_f['field']] = (isset($users_info[$row_f['field']])) ? $users_info[$row_f['field']] : $default_value;
            }

            if ($db->exec('INSERT INTO ' . NV_MOD_TABLE . '_info (' . implode(', ', array_keys($query_field)) . ') VALUES (' . implode(', ', array_values($query_field)) . ')')) {
                $db->query('DELETE FROM ' . NV_MOD_TABLE . '_reg WHERE userid=' . $row['userid']);

                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['active_users'], 'userid: ' . $userid . ' - username: ' . $row['username'], $user_info['userid']);

                $full_name = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
                $subject = $lang_module['adduser_register'];
                $_url = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true);
                $message = sprintf($lang_module['adduser_register_info'], $full_name, $global_config['site_name'], $_url, $row['username']);
                @nv_sendmail([
                    $global_config['site_name'],
                    $global_config['site_email']
                ], $row['email'], $subject, $message);
            } else {
                $db->query('DELETE FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $row['userid']);
            }
        }

        exit('OK');
    }

    $xtpl = new XTemplate('getuserid.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_info['module_theme']);

    $lang_module['fullname'] = $global_config['name_show'] == 0 ? $lang_module['lastname_firstname'] : $lang_module['firstname_lastname'];
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('GLOBAL_CONFIG', $global_config);
    $xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&gid=' . $gid . '&getuserid=1');

    $array = [];
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&amp;area=' . $area . '&amp;return=' . $return . '&amp;sm=1';

    if ($nv_Request->isset_request('sm', 'get')) {
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

        if (!empty($array['user_id'])) {
            $base_url .= '&amp;user_id=' . rawurlencode($array['user_id']);
            $array_where[] = "( userid = '" . $array['user_id'] . "' )";
        }

        if (!empty($array['username'])) {
            $base_url .= '&amp;username=' . rawurlencode($array['username']);
            $array_where[] = "( username LIKE '%" . $db->dblikeescape($array['username']) . "%' )";
        }

        if (!empty($array['full_name'])) {
            $base_url .= '&amp;full_name=' . rawurlencode($array['full_name']);

            $where_fullname = $global_config['name_show'] == 0 ? "concat(last_name,' ',first_name)" : "concat(first_name,' ',last_name)";
            $array_where[] = '(' . $where_fullname . " LIKE '%" . $db->dblikeescape($array['full_name']) . "%' )";
        }

        if (!empty($array['email'])) {
            $base_url .= '&amp;email=' . rawurlencode($array['email']);
            $array_where[] = "( email LIKE '%" . $db->dblikeescape($array['email']) . "%' )";
        }

        $page = $nv_Request->get_int('page', 'get', 1);
        $per_page = 10;

        $db->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_MOD_TABLE . '_reg');
        if (!empty($array_where)) {
            $db->where(implode(' AND ', $array_where));
        }

        $num_items = $db->query($db->sql())
            ->fetchColumn();

        $db->select('*')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);
        $result2 = $db->query($db->sql());
        while ($row = $result2->fetch()) {
            $array_user[$row['userid']] = $row;
        }

        if (!empty($array_user)) {
            foreach ($array_user as $row) {
                $row['regdate'] = nv_date('d/m/Y H:i', $row['regdate']);
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
        } elseif ($nv_Request->isset_request('sm', 'get')) {
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
        exit($lang_module['error_group_not_found']);
    }

    // Báo lỗi nếu không có quyền xóa thành viên
    if (empty($groupsList[$gid]['config']['access_delus'])) {
        exit($lang_module['no_premission']);
    }

    // kiểm tra user_id xóa có nằm trong nhóm được quản lí k, hoặc nằm trong nhóm khác
    $result_user = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $uid);
    $array_groups_user = [];
    while ($_row = $result_user->fetch()) {
        $array_groups_user[$_row['group_id']] = $_row;
    }

    // Báo lỗi nếu thành viên không thuộc nhóm quản lý
    if (!isset($array_groups_user[$gid])) {
        exit($lang_module['del_user_err']);
    }

    // Báo lỗi nếu thành viên là trưởng nhóm
    if ($array_groups_user[$gid]['is_leader']) {
        exit($lang_module['not_del_leader']);
    }

    // Báo lỗi nếu thành viên còn tham gia nhóm khác
    if (sizeof($array_groups_user) > 1) {
        exit($lang_module['not_del_user']);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $row = $db->query('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                exit($lang_module['error_group_in_site']);
            }
        } else {
            exit($lang_module['search_not_result']);
        }
    }

    if (!nv_del_user($uid)) {
        exit($lang_module['del_user_err']);
    }

    $nv_Cache->delMod($module_name);
    exit('OK');
}

// Them thanh vien vao nhom
if ($nv_Request->isset_request('gid,uid', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('uid', 'post', 0);
    if (!isset($groupsList[$gid]) or $gid < 10) {
        exit($lang_module['error_group_not_found']);
    }

    // Báo lỗi nếu không có quyền thêm thành viên vào nhóm
    if (empty($groupsList[$gid]['config']['access_groups_add'])) {
        exit($lang_module['no_premission']);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $row = $db->query('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                exit($lang_module['error_group_in_site']);
            }
        } else {
            exit($lang_module['search_not_result']);
        }
    }

    if (!nv_groups_add_user($gid, $uid, 1, $module_data)) {
        exit($lang_module['search_not_result']);
    }

    // Update for table users
    $in_groups = [];
    $result_gru = $db->query('SELECT group_id FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $uid);
    while ($row_gru = $result_gru->fetch()) {
        $in_groups[] = $row_gru['group_id'];
    }
    $db->exec('UPDATE ' . NV_MOD_TABLE . " SET in_groups='" . implode(',', $in_groups) . "', last_update=" . NV_CURRENTTIME . ' WHERE userid=' . $uid);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['addMemberToGroup'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);

    exit('OK');
}

// Loai thanh vien khoi nhom
if ($nv_Request->isset_request('gid,exclude', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('exclude', 'post', 0);

    if ($uid == $user_info['userid']) {
        exit($lang_module['note_remove_leader']);
    }

    if (!isset($groupsList[$gid]) or $gid < 10) {
        exit($lang_module['error_group_not_found']);
    }

    // Báo lỗi nếu không có quyền loại thành viên khỏi nhóm
    if (empty($groupsList[$gid]['config']['access_groups_del'])) {
        exit($lang_module['no_premission']);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $row = $db->query('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                exit($lang_module['error_group_in_site']);
            }
        } else {
            exit($lang_module['search_not_result']);
        }
    }

    // Không cho loại trừ quản trị khỏi nhóm
    $row = $db->query('SELECT is_leader FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id=' . $gid . ' AND userid=' . $uid)->fetch();
    if (empty($row)) {
        exit($lang_module['search_not_result']);
    }
    if ($row['is_leader']) {
        exit($lang_module['not_exclude_leader']);
    }

    if (!nv_groups_del_user($gid, $uid, $module_data)) {
        exit($lang_module['UserNotInGroup']);
    }

    // Update for table users
    $in_groups = [];
    $result_gru = $db->query('SELECT group_id FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $uid);
    while ($row_gru = $result_gru->fetch()) {
        $in_groups[] = $row_gru['group_id'];
    }
    $db->query('UPDATE ' . NV_MOD_TABLE . " SET in_groups='" . implode(',', $in_groups) . "', last_update=" . NV_CURRENTTIME . ' WHERE userid=' . $uid);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['exclude_user2'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);
    exit('OK');
}

// Duyet vao nhom
if ($nv_Request->isset_request('gid,approved', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('approved', 'post', 0);
    if (!isset($groupsList[$gid]) or $gid < 10) {
        exit($lang_module['error_group_not_found']);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $row = $db->query('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                exit($lang_module['error_group_in_site']);
            }
        } else {
            exit($lang_module['search_not_result']);
        }
    }

    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups_users SET approved = 1, time_approved = ' . NV_CURRENTTIME . ' WHERE group_id = ' . $gid . ' AND userid=' . $uid);
    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers+1 WHERE group_id = ' . $gid);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['approved'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);
    exit('OK');
}

// Tu choi gia nhap nhom
if ($nv_Request->isset_request('gid,denied', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('denied', 'post', 0);
    if (!isset($groupsList[$gid]) or $gid < 10) {
        exit($lang_module['error_group_not_found']);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $row = $db->query('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                exit($lang_module['error_group_in_site']);
            }
        } else {
            exit($lang_module['search_not_result']);
        }
    }

    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = ' . $gid . ' AND userid=' . $uid);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['denied'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);
    exit('OK');
}

// Chinh sua noi dung cua group
if (sizeof($array_op) == 3 and $array_op[0] == 'groups' and $array_op[1] and $array_op[2] == 'edit') {
    $group_id = (int) $array_op[1];
    if (!isset($groupsList[$group_id]) or !($group_id < 4 or $group_id > 9)) {
        nv_redirect_location($page_url);
    }

    $page_url .= '/' . $group_id . '/edit';

    $count = $db->query('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id=' . $group_id . ' AND is_leader=1 AND userid=' . $user_info['userid'])->fetchColumn();
    if (!$count) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }

    if (defined('NV_EDITOR')) {
        require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
    } elseif (!nv_function_exists('nv_aleditor') and file_exists(NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor.js')) {
        define('NV_EDITOR', true);
        define('NV_IS_CKEDITOR', true);
        $my_head .= '<script type="text/javascript" src="' . NV_STATIC_URL . NV_EDITORSDIR . '/ckeditor/ckeditor.js"></script>';

        function nv_aleditor($textareaname, $width = '100%', $height = '450px', $val = '', $customtoolbar = '')
        {
            global $module_data;
            $return = '<textarea style="width: ' . $width . '; height:' . $height . ';" id="' . $module_data . '_' . $textareaname . '" name="' . $textareaname . '">' . $val . '</textarea>';
            $return .= "<script type=\"text/javascript\">
            CKEDITOR.replace( '" . $module_data . '_' . $textareaname . "', {" . (!empty($customtoolbar) ? 'toolbar : "' . $customtoolbar . '",' : '') . " width: '" . $width . "',height: '" . $height . "',removePlugins: 'uploadfile,uploadimage'});
            </script>";

            return $return;
        }
    }

    if ($nv_Request->isset_request('save', 'post')) {
        $rowcontent = [];
        $rowcontent['group_title'] = $nv_Request->get_title('group_title', 'post', '', 1);
        if (empty($rowcontent['group_title'])) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'group_title',
                'mess' => $lang_module['group_title_empty']
            ]);
        }
        $rowcontent['group_desc'] = $nv_Request->get_title('group_desc', 'post', '', 1);
        $group_content = $nv_Request->get_string('group_content', 'post', '');
        $rowcontent['group_content'] = defined('NV_EDITOR') ? nv_nl2br($group_content, '') : nv_nl2br(nv_htmlspecialchars(strip_tags($group_content)), '<br />');

        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups_detail 
            SET title = :title, description = :description, content = :content 
            WHERE group_id = ' . $group_id . " AND lang='" . NV_LANG_DATA . "'");
        $stmt->bindParam(':title', $rowcontent['group_title'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $rowcontent['group_desc'], PDO::PARAM_STR);
        $stmt->bindParam(':content', $rowcontent['group_content'], PDO::PARAM_STR, strlen($rowcontent['group_content']));
        $stmt->execute();

        $nv_Cache->delMod($module_name);
        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['group_edit'], 'Group ID: ' . $group_id, $user_info['userid']);

        nv_jsonOutput([
            'status' => 'ok',
            'input' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '/' . $group_id, true),
            'mess' => $lang_module['group_edit_saved']
        ]);
    }

    $htmlbodyhtml = htmlspecialchars(nv_editor_br2nl($groupsList[$group_id]['content']));
    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
        $htmlbodyhtml = nv_aleditor('group_content', '100%', '300px', $htmlbodyhtml, 'Basic');
    } else {
        $htmlbodyhtml = '<textarea class="textareaform" name="group_content" id="group_content" cols="60" rows="15">' . $htmlbodyhtml . '</textarea>';
    }

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('EDIT_GROUP_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_id . '/edit');
    $xtpl->assign('DATA', $groupsList[$group_id]);
    $xtpl->assign('HTMLBODYTEXT', $htmlbodyhtml);

    $xtpl->parse('editgroup');
    $contents = $xtpl->text('editgroup');

    $array_mod_title[] = [
        'catid' => 0,
        'title' => $lang_module['group_manage'],
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

$lang_module['nametitle'] = $global_config['name_show'] == 0 ? $lang_module['lastname_firstname'] : $lang_module['firstname_lastname'];

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('TEMPLATE', $global_config['module_theme']);
$xtpl->assign('MODULE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE);
$xtpl->assign('OP', $op);

// Danh sach thanh vien
if (sizeof($array_op) == 2 and $array_op[0] == 'groups' and $array_op[1]) {
    $group_id = (int) $array_op[1];
    if (!isset($groupsList[$group_id]) or !($group_id < 4 or $group_id > 9)) {
        nv_redirect_location($page_url);
    }

    $page_url .= '/' . $group_id;

    // Kiem tra lai quyen truong nhom
    $count = $db->query('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id=' . $group_id . ' AND is_leader=1 AND userid=' . $user_info['userid'])->fetchColumn();
    if (!$count) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }

    $filtersql = ' userid NOT IN (SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id=' . $group_id . ')';
    if ($groupsList[$group_id]['idsite'] != $global_config['idsite'] and $groupsList[$group_id]['idsite'] == 0) {
        $filtersql .= ' AND idsite=' . $global_config['idsite'];
    }

    $groupsList[$group_id]['exp'] = !empty($groupsList[$group_id]['exp_time']) ? nv_date('d/m/Y', $groupsList[$group_id]['exp_time']) : $lang_module['group_exp_unlimited'];
    $groupsList[$group_id]['group_avatar'] = !empty($groupsList[$group_id]['group_avatar']) ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $groupsList[$group_id]['group_avatar'] : NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/user-group.jpg';
    $groupsList[$group_id]['group_type_mess'] = $lang_module['group_type_' . $groupsList[$group_id]['group_type']];
    $groupsList[$group_id]['group_type_note'] = !empty($lang_module['group_type_' . $groupsList[$group_id]['group_type'] . '_note']) ? $lang_module['group_type_' . $groupsList[$group_id]['group_type'] . '_note'] : '';

    $xtpl->assign('FILTERSQL', $crypt->encrypt($filtersql, NV_CHECK_SESSION));
    $xtpl->assign('GID', $group_id);
    $xtpl->assign('DATA', $groupsList[$group_id]);
    $xtpl->assign('MIN_SEARCH', sprintf($lang_module['min_search'], NV_MIN_SEARCH_LENGTH));
    $xtpl->assign('EDIT_GROUP_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_id . '/edit');

    if ($group_id > 9) {
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
        'title' => $lang_module['group_manage'],
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
    $page = $nv_Request->get_int('page', 'get', 1);
    $type = $nv_Request->get_title('type', 'get', '');
    $per_page = 15;
    $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=groups&listUsers=' . $group_id;

    if (!isset($groupsList[$group_id])) {
        exit($lang_module['error_group_not_found']);
    }
    $xtpl->assign('GID', $group_id);
    $title = ($group_id < 10) ? $lang_global['level' . $group_id] : $groupsList[$group_id]['title'];

    $array_userid = [];
    $array_number = [];
    $group_users = [];

    // Danh sách xin gia nhập nhóm
    if (empty($type) or $type == 'pending') {
        $db->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_MOD_TABLE . '_groups_users')
            ->where('group_id=' . $group_id . ' AND approved=0');
        $array_number['pending'] = $db->query($db->sql())
            ->fetchColumn();
        if ($array_number['pending']) {
            $db->select('userid')
                ->limit($per_page)
                ->offset(($page - 1) * $per_page);
            $result = $db->query($db->sql());
            while ($row = $result->fetch()) {
                $group_users['pending'][] = $row['userid'];
                $array_userid[] = $row['userid'];
            }
            $result->closeCursor();
        }
    }

    // Danh sách quản trị nhóm
    if (empty($type) or $type == 'leaders') {
        $db->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_MOD_TABLE . '_groups_users')
            ->where('group_id=' . $group_id . ' AND is_leader=1');
        $array_number['leaders'] = $db->query($db->sql())
            ->fetchColumn();
        if ($array_number['leaders']) {
            $db->select('userid')
                ->limit($per_page)
                ->offset(($page - 1) * $per_page);
            $result = $db->query($db->sql());
            while ($row = $result->fetch()) {
                $group_users['leaders'][] = $row['userid'];
                $array_userid[] = $row['userid'];
            }
            $result->closeCursor();
        }
    }

    // Danh sách thành viên của nhóm
    if (empty($type) or $type == 'members') {
        $db->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_MOD_TABLE . '_groups_users')
            ->where('group_id=' . $group_id . ' AND approved=1 AND is_leader=0');
        $array_number['members'] = $db->query($db->sql())
            ->fetchColumn();
        if ($array_number['members']) {
            $db->select('userid')
                ->limit($per_page)
                ->offset(($page - 1) * $per_page);
            $result = $db->query($db->sql());
            while ($row = $result->fetch()) {
                $group_users['members'][] = $row['userid'];
                $array_userid[] = $row['userid'];
            }
            $result->closeCursor();
        }
    }

    if (!empty($group_users)) {
        $sql = 'SELECT userid, username, first_name, last_name, email, idsite FROM ' . NV_MOD_TABLE . ' WHERE userid IN (' . implode(',', $array_userid) . ')';
        $result = $db->query($sql);
        $array_userid = [];
        while ($row = $result->fetch()) {
            $array_userid[$row['userid']] = $row;
        }
        $idsite = ($global_config['idsite'] == $groupsList[$group_id]['idsite']) ? 0 : $global_config['idsite'];
        foreach ($group_users as $_type => $arr_userids) {
            $xtpl->assign('PTITLE', sprintf($lang_module[$_type . '_in_group_caption'], $title, number_format($array_number[$_type], 0, ',', '.')));
            $stt = 1;
            foreach ($arr_userids as $_userid) {
                $row = $array_userid[$_userid];
                $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
                $row['stt'] = $stt;
                $xtpl->assign('LOOP', $row);

                if ($group_id > 3 and ($idsite == 0 or $idsite == $row['idsite']) and $_type != 'leaders') {
                    if ($user_info['userid'] != $_userid) {
                        if ($groupsList[$group_id]['config']['access_groups_del']) {
                            $xtpl->parse('listUsers.' . $_type . '.loop.tools.deletemember');
                        }

                        // kiểm tra thành viên có phải là admin k.
                        $result_admin = $db->query('SELECT admin_id FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id = ' . $_userid);

                        if (!$row_admin = $result_admin->fetch()) {
                            if ($groupsList[$group_id]['config']['access_editus']) {
                                $xtpl->assign('LINK_EDIT', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=editinfo/' . $group_id . '/' . $row['userid'], true));
                                $xtpl->parse('listUsers.' . $_type . '.loop.tools.edituser');
                            }

                            $count = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $_userid)->rowCount();

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
                $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = ' . $numberusers . ' WHERE group_id=' . $group_id);
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
            'add_time' => nv_date('d/m/Y H:i', $values['add_time']),
            'exp_time' => !empty($values['exp_time']) ? nv_date('d/m/Y H:i', $values['exp_time']) : $lang_global['unlimited'],
            'number' => number_format($values['numbers']),
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
    'title' => $lang_module['group_manage'],
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op
];

$xtpl->parse('main');
$contents = $xtpl->text('main');

$canonicalUrl = getCanonicalUrl($page_url);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
