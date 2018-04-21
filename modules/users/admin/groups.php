<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 15:5
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_global['mod_groups'];
$contents = '';

// Lay danh sach nhom
$sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_groups WHERE idsite = ' . $global_config['idsite'] . ' or (idsite=0 AND group_id>3 AND siteus=1) ORDER BY idsite, weight ASC';
$result = $db->query($sql);
$groupsList = array();
$groupcount = 0;
$weight_siteus = 0;
$checkEmptyGroup = 0; // Sử dụng cái này để tính cả những nhóm "SHARE"
while ($row = $result->fetch()) {
    if ($row['idsite'] == $global_config['idsite']) {
        ++$groupcount;
        ++$checkEmptyGroup;
    } else {
        $row['weight'] = ++$weight_siteus;
        $row['title'] = '<strong>' . $row['title'] . '</strong>';
        if ($row['group_id'] > 9) {
            ++$checkEmptyGroup;
        }
    }
    $groupsList[$row['group_id']] = $row;
}

// Neu khong co nhom => chuyen den trang tao nhom
if (!$checkEmptyGroup and ! $nv_Request->isset_request('add', 'get')) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&add');
}

// Thay đổi thứ tự nhóm
if ($nv_Request->isset_request('cWeight, id', 'post')) {
    $group_id = $nv_Request->get_int('id', 'post');
    $cWeight = $nv_Request->get_int('cWeight', 'post');
    if (!isset($groupsList[$group_id]) or !defined('NV_IS_SPADMIN') or $groupsList[$group_id]['idsite'] != $global_config['idsite'] or ($global_config['idsite'] > 0 and $group_id < 10)) {
        die('ERROR');
    }

    $cWeight = min($cWeight, sizeof($groupsList));
    if ($global_config['idsite'] > 0) {
        $cWeight = $cWeight - $weight_siteus;
    }
    if ($cWeight < 1) {
        $cWeight = 1;
    }

    $sql = 'SELECT group_id FROM ' . NV_MOD_TABLE . '_groups WHERE group_id!=' . $group_id . ' AND idsite=' . $global_config['idsite'] . ' ORDER BY weight ASC';
    $result = $db->query($sql);

    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $cWeight) {
            ++$weight;
        }
        $sql = 'UPDATE ' . NV_MOD_TABLE . '_groups SET weight=' . $weight . ' WHERE group_id=' . $row['group_id'];
        $db->query($sql);
    }
    $sql = 'UPDATE ' . NV_MOD_TABLE . '_groups SET weight=' . $cWeight . ' WHERE group_id=' . $group_id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['changeGroupWeight'], 'group_id: ' . $group_id, $admin_info['userid']);
    die('OK');
}

// Thay doi tinh trang hien thi cua nhom
if ($nv_Request->isset_request('act', 'post')) {
    $group_id = $nv_Request->get_int('act', 'post');
    if (!isset($groupsList[$group_id]) or !defined('NV_IS_SPADMIN') or $group_id < 10 or $groupsList[$group_id]['idsite'] != $global_config['idsite']) {
        die('ERROR|' . $groupsList[$group_id]['act']);
    }

    $act = $groupsList[$group_id]['act'] ? 0 : 1;
    $sql = 'UPDATE ' . NV_MOD_TABLE . '_groups SET act=' . $act . ' WHERE group_id=' . $group_id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['ChangeGroupAct'], 'group_id: ' . $group_id, $admin_info['userid']);
    die('OK|' . $act);
}

// Xoa nhom
if ($nv_Request->isset_request('del', 'post')) {
    $group_id = $nv_Request->get_int('del', 'post', 0);

    if (!isset($groupsList[$group_id]) or !defined('NV_IS_SPADMIN') or $group_id < 10 or $groupsList[$group_id]['idsite'] != $global_config['idsite']) {
        die($lang_module['error_group_not_found']);
    }

    $array_groups = array();
    $result = $db->query('SELECT group_id, userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid IN (SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id=' . $group_id . ')');

    while ($row = $result->fetch()) {
        $array_groups[$row['userid']][$row['group_id']] = 1;
    }

    foreach ($array_groups as $userid => $gr) {
        unset($gr[$group_id]);
        $in_groups = array_keys($gr);
        $db->exec("UPDATE " . NV_MOD_TABLE . " SET in_groups='" . implode(',', $in_groups) . "' WHERE userid=" . $userid);
    }

    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_groups WHERE group_id = ' . $group_id);
    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = ' . $group_id);

    // Cập nhật lại thứ tự
    $sql = 'SELECT group_id FROM ' . NV_MOD_TABLE . '_groups WHERE idsite=' . $global_config['idsite'] . ' ORDER BY weight ASC';
    $result = $db->query($sql);

    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        $sql = 'UPDATE ' . NV_MOD_TABLE . '_groups SET weight=' . $weight . ' WHERE group_id=' . $row['group_id'];
        $db->query($sql);
    }

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['delGroup'], 'group_id: ' . $group_id, $admin_info['userid']);
    die('OK');
}

// Them thanh vien vao nhom
if ($nv_Request->isset_request('gid,uid', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('uid', 'post', 0);
    if (! isset($groupsList[$gid]) or $gid < 10) {
        die($lang_module['error_group_not_found']);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $row = $db->query('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
        if (! empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                die($lang_module['error_group_in_site']);
            }
        } else {
            die($lang_module['search_not_result']);
        }
    }

    if (! nv_groups_add_user($gid, $uid, 1, $module_data)) {
        die($lang_module['search_not_result']);
    }

    // Update for table users
    $in_groups = array();
    $result_gru = $db->query('SELECT group_id FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $uid);
    while ($row_gru = $result_gru->fetch()) {
        $in_groups[] = $row_gru['group_id'];
    }
    $db->exec("UPDATE " . NV_MOD_TABLE . " SET in_groups='" . implode(',', $in_groups) . "' WHERE userid=" . $uid);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['addMemberToGroup'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid']);

    die('OK');
}

// Loai thanh vien khoi nhom
if ($nv_Request->isset_request('gid,exclude', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('exclude', 'post', 0);
    if (! isset($groupsList[$gid]) or $gid < 10) {
        die($lang_module['error_group_not_found']);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $row = $db->query('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
        if (! empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                die($lang_module['error_group_in_site']);
            }
        } else {
            die($lang_module['search_not_result']);
        }
    }

    if (! nv_groups_del_user($gid, $uid, $module_data)) {
        die($lang_module['UserNotInGroup']);
    }

    // Update for table users
    $in_groups = array();
    $result_gru = $db->query('SELECT group_id FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $uid);
    while ($row_gru = $result_gru->fetch()) {
        $in_groups[] = $row_gru['group_id'];
    }
    $db->query("UPDATE " . NV_MOD_TABLE . " SET in_groups='" . implode(',', $in_groups) . "' WHERE userid=" . $uid);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['exclude_user2'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid']);
    die('OK');
}

// Thang cap thanh vien
if ($nv_Request->isset_request('gid,promote', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('promote', 'post', 0);
    if (! isset($groupsList[$gid]) or $gid < 10) {
        die($lang_module['error_group_not_found']);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $row = $db->query('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
        if (! empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                die($lang_module['error_group_in_site']);
            }
        } else {
            die($lang_module['search_not_result']);
        }
    }

    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups_users SET is_leader = 1 WHERE group_id = ' . $gid . ' AND userid=' . $uid);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['promote'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid']);
    die('OK');
}

// Giang cap quan tri
if ($nv_Request->isset_request('gid,demote', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('demote', 'post', 0);
    if (! isset($groupsList[$gid]) or $gid < 10) {
        die($lang_module['error_group_not_found']);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $row = $db->query('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
        if (! empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                die($lang_module['error_group_in_site']);
            }
        } else {
            die($lang_module['search_not_result']);
        }
    }

    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups_users SET is_leader = 0 WHERE group_id = ' . $gid . ' AND userid=' . $uid);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['demote'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid']);
    die('OK');
}

// Duyet vao nhom
if ($nv_Request->isset_request('gid,approved', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('approved', 'post', 0);
    if (! isset($groupsList[$gid]) or $gid < 10) {
        die($lang_module['error_group_not_found']);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $row = $db->query('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
        if (! empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                die($lang_module['error_group_in_site']);
            }
        } else {
            die($lang_module['search_not_result']);
        }
    }

    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups_users SET approved = 1 WHERE group_id = ' . $gid . ' AND userid=' . $uid);
    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers+1 WHERE group_id = ' . $gid);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['approved'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid']);
    die('OK');
}

// Tu choi gia nhap nhom
if ($nv_Request->isset_request('gid,denied', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('denied', 'post', 0);
    if (! isset($groupsList[$gid]) or $gid < 10) {
        die($lang_module['error_group_not_found']);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $row = $db->query('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
        if (! empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                die($lang_module['error_group_in_site']);
            }
        } else {
            die($lang_module['search_not_result']);
        }
    }

    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = ' . $gid . ' AND userid=' . $uid);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['denied'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid']);
    die('OK');
}

$lang_module['nametitle'] = $global_config['name_show'] == 0 ? $lang_module['lastname_firstname'] : $lang_module['firstname_lastname'];

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('TEMPLATE', $global_config['module_theme']);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('MODULE_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE);
$xtpl->assign('OP', $op);

// Danh sach thanh vien (AJAX)
if ($nv_Request->isset_request('listUsers', 'get')) {
    $group_id = $nv_Request->get_int('listUsers', 'get', 0);
    $page = $nv_Request->get_int('page', 'get', 1);
    $type = $nv_Request->get_title('type', 'get', '');
    $per_page = 15;
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=groups&listUsers=' . $group_id;

    if (!isset($groupsList[$group_id])) {
        die($lang_module['error_group_not_found']);
    }
    $xtpl->assign('GID', $group_id);
    $title = ($group_id < 10) ? $lang_global['level' . $group_id] : $groupsList[$group_id]['title'];

    $array_userid = array();
    $array_number = array();
    $group_users = array();

    //Danh sách xin gia nhập nhóm
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

    //Danh sách quản trị nhóm
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

    //Danh sách thành viên của nhóm
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
        $array_userid = array();
        while ($row = $result->fetch()) {
            $array_userid[$row['userid']] = $row;
        }
        $idsite = ($global_config['idsite'] == $groupsList[$group_id]['idsite']) ? 0 : $global_config['idsite'];
        foreach ($group_users as $_type => $arr_userids) {
            $xtpl->assign('PTITLE', sprintf($lang_module[$_type . '_in_group_caption'], $title, number_format($array_number[$_type], 0, ',', '.')));
            foreach ($arr_userids as $_userid) {

                $row = $array_userid[$_userid];
                $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
                $xtpl->assign('LOOP', $row);
                if ($group_id > 3 and ($idsite == 0 or $idsite == $row['idsite'])) {
                    $xtpl->parse('listUsers.' . $_type . '.loop.tools');
                }
                $xtpl->parse('listUsers.' . $_type . '.loop');
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

// Danh sach thanh vien
if ($nv_Request->isset_request('userlist', 'get')) {
    $group_id = $nv_Request->get_int('userlist', 'get', 0);
    if (! isset($groupsList[$group_id]) or ! ($group_id < 4 or $group_id > 9)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }

    $filtersql = ' userid NOT IN (SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id=' . $group_id . ')';
    if ($groupsList[$group_id]['idsite'] != $global_config['idsite'] and $groupsList[$group_id]['idsite'] == 0) {
        $filtersql .= ' AND idsite=' . $global_config['idsite'];
    }
    $xtpl->assign('FILTERSQL', $crypt->encrypt($filtersql, NV_CHECK_SESSION));
    $xtpl->assign('GID', $group_id);

    if ($group_id > 9) {
        $xtpl->parse('userlist.adduser');
    }
    $xtpl->parse('userlist');
    $contents = $xtpl->text('userlist');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Them + sua nhom
if ($nv_Request->isset_request('add', 'get') or $nv_Request->isset_request('edit, id', 'get')) {
    if (defined('NV_IS_SPADMIN')) {
        $post = array();
        $post['id'] = $nv_Request->get_int('id', 'get');

        if ($nv_Request->isset_request('edit', 'get')) {
            if (empty($post['id']) or ! isset($groupsList[$post['id']]) or $groupsList[$post['id']]['idsite'] != $global_config['idsite']) {
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
            }

            $xtpl->assign('PTITLE', $lang_module['nv_admin_edit']);
            $xtpl->assign('ACTION_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&edit&id=' . $post['id']);
            $log_title = $lang_module['nv_admin_edit'];
        } else {
            $xtpl->assign('PTITLE', $lang_module['nv_admin_add']);
            $xtpl->assign('ACTION_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&add');
            $log_title = $lang_module['nv_admin_add'];
        }

        if (defined('NV_EDITOR')) {
            require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' ;
        }

        if ($nv_Request->isset_request('save', 'post')) {
            // Sửa / Thêm full thông tin
            if (empty($post['id']) or $post['id'] > 9) {
                $post['title'] = $nv_Request->get_title('title', 'post', '', 1);
                if (empty($post['title'])) {
                    die($lang_module['title_empty']);
                }

                // Kiểm tra trùng tên nhóm
                $stmt = $db->prepare('SELECT group_id FROM ' . NV_MOD_TABLE . '_groups WHERE title LIKE :title AND group_id!= ' . intval($post['id']) . ' AND (idsite=' . $global_config['idsite'] . ' or (idsite=0 AND siteus=1))');
                $stmt->bindParam(':title', $post['title'], PDO::PARAM_STR);
                $stmt->execute();
                if ($stmt->fetchColumn()) {
                    die(sprintf($lang_module['error_title_exists'], $post['title']));
                }

                $post['description'] = $nv_Request->get_title('description', 'post', '', 1);
                $post['content'] = $nv_Request->get_editor('content', '', NV_ALLOWED_HTML_TAGS);
                $post['exp_time'] = $nv_Request->get_title('exp_time', 'post', '');

                if (preg_match('/^([\d]{1,2})\/([\d]{1,2})\/([\d]{4})$/', $post['exp_time'], $matches)) {
                    $post['exp_time'] = mktime(23, 59, 59, $matches[2], $matches[1], $matches[3]);
                } else {
                    $post['exp_time'] = 0;
                }

                $post['group_type'] = $nv_Request->get_int('group_type', 'post', 0);
                if (!in_array($post['group_type'], array(0, 1, 2))) {
                    $post['group_type'] = 0;
                }

                $post['siteus'] = $nv_Request->get_int('siteus', 'post', 0);
                if ($post['siteus'] != 1) {
                    $post['siteus'] = 0;
                }

                $post['is_default'] = $nv_Request->get_int('is_default', 'post', 0);
                if ($post['is_default'] != 1) {
                    $post['is_default'] = 0;
                }
            }

            if (empty($post['id']) or $post['id'] > 9 or $post['id'] == 1 or $post['id'] == 2 or $post['id'] == 3 or $post['id'] == 4 or $post['id'] == 7) {
                $post['email'] = $nv_Request->get_title('email', 'post', '', 1);
                if ( !empty($post['email']) AND ($error_xemail = nv_check_valid_email($post['email'])) != '') {
                    die($error_xemail);
                }
            } else {
                $post['email'] = '';
            }

            if (empty($post['id']) or $post['id'] > 9 or $post['id'] == 0 or $post['id'] == 1 or $post['id'] == 2 or $post['id'] == 3 ) {
                //lấy thông tin cấu hình phân quyền
                $post['config']['access_groups_add'] = $nv_Request->get_int('access_groups_add', 'post', 0);
                $post['config']['access_groups_del'] = $nv_Request->get_int('access_groups_del', 'post', 0);
                $post['config']['access_addus'] = $nv_Request->get_int('access_addus', 'post', 0);
                $post['config']['access_waiting'] = $nv_Request->get_int('access_waiting', 'post', 0);
                $post['config']['access_editus'] = $nv_Request->get_int('access_editus', 'post', 0);
                $post['config']['access_delus'] = $nv_Request->get_int('access_delus', 'post', 0);
                $post['config']['access_passus'] = $nv_Request->get_int('access_passus', 'post', 0);
                $post['config'] = serialize($post['config']);
            }

            // Thông tin của tất cả các nhóm kể cả các nhóm hệ thống
            $post['group_color'] = nv_substr($nv_Request->get_title('group_color', 'post', '', 1), 0, 10);

            if (preg_match("/^([0-9a-fA-F]{6})$/i", $post['group_color']) or preg_match("/^([0-9a-fA-F]{3})$/i", $post['group_color'])) {
                $post['group_color'] = '#' . $post['group_color'];
            }

            $post['group_avatar'] = $nv_Request->get_title('group_avatar', 'post', '');

            if (! nv_is_url($post['group_avatar']) and nv_is_file($post['group_avatar'], NV_UPLOADS_DIR . '/' . $module_upload)) {
                $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
                $post['group_avatar'] = substr($post['group_avatar'], $lu);
            } elseif (!nv_is_url($post['group_avatar'])) {
                $post['group_avatar'] = '';
            }

            $post['require_2step_admin'] = $nv_Request->get_int('require_2step_admin', 'post', 0) ? 1 : 0;
            $post['require_2step_site'] = $nv_Request->get_int('require_2step_site', 'post', 0) ? 1 : 0;

            if (isset($post['id'])) {
                if ($nv_Request->isset_request('add', 'get')) {
                    $weight = $db->query("SELECT max(weight) FROM " . NV_MOD_TABLE . "_groups WHERE idsite=" . $global_config['idsite'])->fetchColumn();
                    $weight = intval($weight) + 1;

                    $_sql = "INSERT INTO " . NV_MOD_TABLE . "_groups (
                        title, email, description, content, group_type, group_color, group_avatar, require_2step_admin, require_2step_site, is_default, add_time, exp_time, weight, act,
                        idsite, numbers, siteus, config
                    ) VALUES (
                        :title, :email, :description, :content, " . $post['group_type'] . ", :group_color,
                        :group_avatar, " . $post['require_2step_admin'] . ", " . $post['require_2step_site'] . ", " . $post['is_default'] . ", " . NV_CURRENTTIME . ", " . $post['exp_time'] . ",
                        " . $weight . ", 1, " . $global_config['idsite'] . ", 0, " . $post['siteus'] . ", :config
                    )";

                    $data_insert = array();
                    $data_insert['title'] = $post['title'];
                    $data_insert['email'] = $post['email'];
                    $data_insert['description'] = $post['description'];
                    $data_insert['content'] = $post['content'];
                    $data_insert['group_color'] = $post['group_color'];
                    $data_insert['group_avatar'] = $post['group_avatar'];
                    $data_insert['config'] = $post['config'];

                    $ok = $post['id'] = $db->insert_id($_sql, 'group_id', $data_insert);
                } elseif ($post['id'] > 9) {
                    // Sửa nhóm tự tạo
                    $stmt = $db->prepare("UPDATE " . NV_MOD_TABLE . "_groups SET
                        title = :title,
                        email = :email,
                        description = :description,
                        content = :content,
                        group_type = '" . $post['group_type'] . "',
                        group_color = :group_color,
                        group_avatar = :group_avatar,
                        require_2step_admin = " . $post['require_2step_admin'] . ",
                        require_2step_site = " . $post['require_2step_site'] . ",
                        is_default = " . $post['is_default'] . ",
                        exp_time ='" . $post['exp_time'] . "',
                        siteus = '" . $post['siteus'] . "',
                        config = :config
                    WHERE group_id = " . $post['id']);

                    $stmt->bindParam(':title', $post['title'], PDO::PARAM_STR);
                    $stmt->bindParam(':email', $post['email'], PDO::PARAM_STR);
                    $stmt->bindParam(':description', $post['description'], PDO::PARAM_STR);
                    $stmt->bindParam(':content', $post['content'], PDO::PARAM_STR, strlen($post['content']));
                    $stmt->bindParam(':group_color', $post['group_color']);
                    $stmt->bindParam(':group_avatar', $post['group_avatar']);
                    $stmt->bindParam(':config', $post['config'], PDO::PARAM_STR);

                    $ok = $stmt->execute();
                } else {
                    // Sửa nhóm hệ thống
                    $stmt = $db->prepare("UPDATE " . NV_MOD_TABLE . "_groups SET
                        email = :email,
                        group_color = :group_color,
                        group_avatar = :group_avatar,
                        require_2step_admin = " . $post['require_2step_admin'] . ",
                        require_2step_site = " . $post['require_2step_site'] . ",
                        config = :config
                    WHERE group_id=" . $post['id']);

                    $stmt->bindParam(':email', $post['email']);
                    $stmt->bindParam(':group_color', $post['group_color']);
                    $stmt->bindParam(':group_avatar', $post['group_avatar']);
                    $stmt->bindParam(':config', $post['config']);

                    $ok = $stmt->execute();
                }
            }

            if ($ok) {
                $nv_Cache->delMod($module_name);
                nv_insert_logs(NV_LANG_DATA, $module_name, $log_title, 'Id: ' . $post['id'], $admin_info['userid']);
                die('OK');
            } else {
                die($lang_module['errorsave']);
            }
        }

        if ($nv_Request->isset_request('edit', 'get')) {
            $post = $groupsList[$post['id']];
            $post['content'] = nv_editor_br2nl($post['content']);
            $post['exp_time'] = ! empty($post['exp_time']) ? date('d/m/Y', $post['exp_time']) : '';
            $post['siteus'] = $post['siteus'] ? ' checked="checked"' : '';
            $post['id'] = $post['group_id'];

            if(empty($post['config'])){
                $post['config']['access_groups_add'] = $post['config']['access_groups_del'] = 1;
                $post['config']['access_addus'] = $post['config']['access_waiting'] = $post['config']['access_editus'] = $post['config']['access_delus'] = $post['config']['access_passus'] = $post['config']['access_passus'] = 0;
            }
            else {
                $post['config'] = unserialize($post['config']);
            }
        } else {
            $post['title'] = $post['email'] =  $post['description'] = $post['content'] = $post['exp_time'] = '';
            $post['group_type'] = 0;
            $post['id'] = $post['is_default'] = $post['require_2step_admin'] = $post['require_2step_site'] = 0;

            $post['config']['access_groups_add'] = $post['config']['access_groups_del'] = 1;
            $post['config']['access_addus'] = $post['config']['access_waiting'] = $post['config']['access_editus'] = $post['config']['access_delus'] = $post['config']['access_passus'] = $post['config']['access_passus'] = 0;
        }


        $post['content'] = htmlspecialchars(nv_editor_br2nl($post['content']));
        $post['is_default'] = $post['is_default'] ? ' checked="checked"' : '';
        $post['require_2step_admin'] = $post['require_2step_admin'] ? ' checked="checked"' : '';
        $post['require_2step_site'] = $post['require_2step_site'] ? ' checked="checked"' : '';

        $post['config']['access_groups_add'] = $post['config']['access_groups_add'] ? ' checked="checked"' : '';
        $post['config']['access_groups_del'] = $post['config']['access_groups_del'] ? ' checked="checked"' : '';
        $post['config']['access_addus'] = $post['config']['access_addus'] ? ' checked="checked"' : '';
        $post['config']['access_waiting'] = $post['config']['access_waiting'] ? ' checked="checked"' : '';
        $post['config']['access_editus'] = $post['config']['access_editus'] ? ' checked="checked"' : '';
        $post['config']['access_delus'] = $post['config']['access_delus'] ? ' checked="checked"' : '';
        $post['config']['access_passus'] = $post['config']['access_passus'] ? ' checked="checked"' : '';

        if (! empty($post['group_avatar']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $post['group_avatar'])) {
            $post['group_avatar'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $post['group_avatar'];
        }

        $xtpl->assign('CONFIG', $post['config']);
        $xtpl->assign('DATA', $post);

        if (defined('NV_CONFIG_DIR') and empty($global_config['idsite'])) {
            $xtpl->parse('add.basic_infomation.siteus');
        }

        if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
            $_cont = nv_aleditor('content', '100%', '300px', $post['content']);
        } else {
            $_cont = '<textarea style="width:100%;height:300px" name="content" id="content">' . $post['content'] . '</textarea>';
        }

        for ($i = 0; $i <= 2; $i ++) {
            $group_type = array(
                'key' => $i,
                'title' => $lang_module['group_type_' . $i],
                'selected' => $i == $post['group_type'] ? ' selected="selected"' : ''
            );

            $xtpl->assign('GROUP_TYPE', $group_type);
            $xtpl->parse('add.basic_infomation.group_type');
        }

        $xtpl->assign('CONTENT', $_cont);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
        $xtpl->assign('AVATAR_PATH', NV_UPLOADS_DIR . '/' . $module_upload);
        $xtpl->assign('AVATAR_CURENT_PATH', NV_UPLOADS_DIR . '/' . $module_upload . '/groups');

        if ($post['id'] > 9 or $post['id'] == 0) {
            $xtpl->parse('add.basic_infomation');
        }

        if ($post['id'] > 9 or $post['id'] == 0 or $post['id'] == 1 or $post['id'] == 2 or $post['id'] == 3 or $post['id'] == 4 or $post['id'] == 7) {
            $xtpl->parse('add.email');
        }

        if ($post['id'] > 9 or $post['id'] == 0 or $post['id'] == 1 or $post['id'] == 2 or $post['id'] == 3 ) {
            $xtpl->parse('add.config');
        }

        if (!empty($post['group_color'])) {
            $xtpl->parse('add.group_color');
        }

        if (in_array($global_config['two_step_verification'], array(1, 3))) {
            $xtpl->parse('add.2step_admin_default');
            $xtpl->parse('add.2step_admin_default_active');
        }
        if (in_array($global_config['two_step_verification'], array(2, 3))) {
            $xtpl->parse('add.2step_site_default');
            $xtpl->parse('add.2step_site_default_active');
        }

        $xtpl->parse('add');
        $contents = $xtpl->text('add');
    } else {
        $contents = $lang_global['admin_no_allow_func'];
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Danh sach nhom (AJAX)
if ($nv_Request->isset_request('list', 'get')) {
    $weight_op = 1;
    $allGroupCount = sizeof($groupsList);
    foreach ($groupsList as $group_id => $values) {
        $xtpl->assign('GROUP_ID', $group_id);
        if ($group_id < 4 or $group_id > 9) {
            $link_userlist = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op .'&amp;userlist=' . $group_id;
        } elseif ($group_id == 4) {
            $link_userlist = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;usactive=-3';
        } elseif ($group_id == 7) {
            $link_userlist = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;usactive=-2';
        } else {
            $link_userlist = '#';
        }

        $loop = array(
            'title' => $values['title'],
            'add_time' => nv_date('d/m/Y H:i', $values['add_time']),
            'exp_time' => ! empty($values['exp_time']) ? nv_date('d/m/Y H:i', $values['exp_time']) : $lang_global['unlimited'],
            'number' => number_format($values['numbers']),
            'act' => $values['act'] ? ' checked="checked"' : '',
            'link_userlist' => $link_userlist
        );

        if (defined('NV_IS_SPADMIN') and $values['idsite'] == $global_config['idsite']) {
            $_bg = empty($global_config['idsite']) ? 1 : $weight_op;

            for ($i = $_bg; $i <= $allGroupCount; $i++) {
                $opt = array('value' => $i, 'selected' => $i == ($_bg + $values['weight'] - 1) ? ' selected="selected"' : '');
                $xtpl->assign('NEWWEIGHT', $opt);
                $xtpl->parse('list.loop.weight.loop');
            }
            $xtpl->parse('list.loop.weight');

            if ($group_id > 9) {
                $xtpl->parse('list.loop.action.delete');
            }

            $xtpl->parse('list.loop.action');
        } else {
            ++$weight_op;
            $xtpl->assign('WEIGHT_TEXT', $values['weight']);
            $xtpl->parse('list.loop.weight_text');

            $loop['act'] .= ' disabled="disabled"';
            if ($group_id < 9) {
                $loop['title'] = $lang_global['level' . $group_id];
            }
        }
        $xtpl->assign('LOOP', $loop);
        $xtpl->parse('list.loop');
    }

    if (defined('NV_IS_SPADMIN')) {
        $xtpl->parse('list.action_js');
    }

    $xtpl->parse('list');
    $contents = $xtpl->text('list');

    include NV_ROOTDIR . '/includes/header.php';
    echo ($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

if (defined('NV_IS_SPADMIN')) {
    $xtpl->parse('main.addnew');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
