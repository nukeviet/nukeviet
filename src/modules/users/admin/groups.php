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

/**
 * getAlias()
 *
 * @param string $alias
 * @param int    $id
 * @param int    $num
 * @return string
 * @throws PDOException
 */
function getAlias($alias, $id, $num = 0)
{
    global $db, $global_config;

    $_alias = $num ? $alias . '-' . $num : $alias;
    $stmt = $db->prepare('SELECT group_id FROM ' . NV_MOD_TABLE . '_groups WHERE alias = :alias AND group_id!= ' . (int) $id . ' AND (idsite=' . $global_config['idsite'] . ' OR (idsite=0 AND siteus=1))');
    $stmt->bindParam(':alias', $_alias, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        ++$num;

        return getAlias($alias, $id, $num);
    }

    return $_alias;
}

if ($nv_Request->isset_request('getAlias, id, title', 'post')) {
    $id = $nv_Request->get_title('id', 'post', 0);
    $title = $nv_Request->get_title('title', 'post', '', 1);

    $alias = '';
    if (!empty($title)) {
        $alias = getAlias(change_alias($title), $id);
    }
    echo $alias;
    exit(0);
}

$page_title = $nv_Lang->getGlobal('mod_groups');

// Lấy danh sách nhóm
$sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_groups AS g LEFT JOIN ' . NV_MOD_TABLE . "_groups_detail d ON ( g.group_id = d.group_id AND d.lang='" . NV_LANG_DATA . "' ) WHERE g.idsite = " . $global_config['idsite'] . ' OR (g.idsite=0 AND g.group_id>3 AND g.siteus=1) ORDER BY g.idsite, g.weight ASC';
$result = $db->query($sql);
$groupsList = [];
$weight_siteus = 0;
$checkEmptyGroup = 0; // Sử dụng cái này để tính cả những nhóm "SHARE"

while ($row = $result->fetch()) {
    if ($row['idsite'] == $global_config['idsite']) {
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
// Thống kê thành viên
if (!empty($global_config['idsite'])) {
    // Thành viên mới của site
    $db->sqlreset()
        ->select('COUNT(userid)')
        ->from(NV_MOD_TABLE)
        ->where('idsite = ' . $global_config['idsite'] . ' AND (group_id=7 OR FIND_IN_SET(7, in_groups))');
    $groupsList[7]['numbers'] = $db->query($db->sql())->fetchColumn();

    // Thành viên chính thức của site
    $db->sqlreset()
        ->select('COUNT(userid)')
        ->from(NV_MOD_TABLE)
        ->where('idsite = ' . $global_config['idsite']);
    $all_member = $db->query($db->sql())->fetchColumn();
    $groupsList[4]['numbers'] = $all_member - $groupsList[7]['numbers'];
}
$groupsList[5]['numbers'] = '-';
$groupsList[6]['numbers'] = '-';

// Neu khong co nhom => chuyen den trang tao nhom
if (!$checkEmptyGroup and !$nv_Request->isset_request('add', 'get')) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&add');
}

$request_tokend = $nv_Request->get_title('tokend', 'post', '');

// Thay đổi thứ tự nhóm
if ($nv_Request->isset_request('cWeight, id', 'post') and hash_equals(NV_CHECK_SESSION, $request_tokend)) {
    $group_id = $nv_Request->get_int('id', 'post');
    $cWeight = $nv_Request->get_int('cWeight', 'post');

    if (!isset($groupsList[$group_id]) or !defined('NV_IS_SPADMIN') or $groupsList[$group_id]['idsite'] != $global_config['idsite'] or ($global_config['idsite'] > 0 and $group_id < 10)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }

    $cWeight = min($cWeight, count($groupsList));
    if ($global_config['idsite'] > 0) {
        $cWeight -= $weight_siteus;
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
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('changeGroupWeight'), 'group_id: ' . $group_id, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'success'
    ]);
}

// Kích hoạt/ Đình chỉ nhóm
if ($nv_Request->isset_request('act', 'post') and hash_equals(NV_CHECK_SESSION, $request_tokend)) {
    $group_id = $nv_Request->get_int('act', 'post');
    if (!isset($groupsList[$group_id]) or !defined('NV_IS_SPADMIN') or $group_id < 10 or $groupsList[$group_id]['idsite'] != $global_config['idsite']) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }

    $act = $groupsList[$group_id]['act'] ? 0 : 1;
    $sql = 'UPDATE ' . NV_MOD_TABLE . '_groups SET act=' . $act . ' WHERE group_id=' . $group_id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('ChangeGroupAct'), 'group_id: ' . $group_id, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'success',
        'new_status' => $act
    ]);
}

// Xóa nhóm
if ($nv_Request->isset_request('del', 'post') and hash_equals(NV_CHECK_SESSION, $request_tokend)) {
    $group_id = $nv_Request->get_int('del', 'post', 0);

    if (!isset($groupsList[$group_id]) or !defined('NV_IS_SPADMIN') or $group_id < 10 or $groupsList[$group_id]['idsite'] != $global_config['idsite']) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }

    $array_groups = [];
    $sql = 'SELECT group_id, userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid IN (
        SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id=' . $group_id . '
    )';
    $result = $db->query($sql);

    while ($row = $result->fetch()) {
        $array_groups[$row['userid']][$row['group_id']] = 1;
    }

    foreach ($array_groups as $userid => $gr) {
        unset($gr[$group_id]);
        $in_groups = array_keys($gr);
        $db->exec('UPDATE ' . NV_MOD_TABLE . " SET in_groups='" . implode(',', $in_groups) . "', last_update=" . NV_CURRENTTIME . ' WHERE userid=' . $userid);
    }

    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_groups WHERE group_id = ' . $group_id);
    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_groups_detail WHERE group_id = ' . $group_id);
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
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('delGroup'), 'group_id: ' . $group_id, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'success'
    ]);
}

// Xóa các nhóm đang ngưng kích hoạt
if ($nv_Request->isset_request('deleteinactive', 'post') and hash_equals(NV_CHECK_SESSION, $request_tokend) and defined('NV_IS_SPADMIN')) {
    $num_deleted = 0;

    foreach ($groupsList as $group_id => $group_row) {
        if ($group_id > 9 and $group_row['idsite'] == $global_config['idsite'] and empty($group_row['act'])) {
            $array_groups = [];
            $sql = 'SELECT group_id, userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid IN (
                SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id=' . $group_id . '
            )';
            $result = $db->query($sql);

            while ($row = $result->fetch()) {
                $array_groups[$row['userid']][$row['group_id']] = 1;
            }

            foreach ($array_groups as $userid => $gr) {
                unset($gr[$group_id]);
                $in_groups = array_keys($gr);
                $db->exec('UPDATE ' . NV_MOD_TABLE . " SET in_groups='" . implode(',', $in_groups) . "', last_update=" . NV_CURRENTTIME . ' WHERE userid=' . $userid);
            }

            $db->query('DELETE FROM ' . NV_MOD_TABLE . '_groups WHERE group_id = ' . $group_id);
            $db->query('DELETE FROM ' . NV_MOD_TABLE . '_groups_detail WHERE group_id = ' . $group_id);
            $db->query('DELETE FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = ' . $group_id);
            ++$num_deleted;
        }
    }

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
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('group_del_inactive'), 'Num: ' . $num_deleted, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getModule('delete_success')
    ]);
}

// Thêm thành viên vào nhóm
if ($nv_Request->isset_request('gid,uid', 'post') and hash_equals(NV_CHECK_SESSION, $request_tokend)) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('uid', 'post', 0);
    if (!isset($groupsList[$gid]) or $gid < 10) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $row = $db->query('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
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

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('addMemberToGroup'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid']);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'OK'
    ]);
}

// Loai thanh vien khoi nhom
if ($nv_Request->isset_request('gid,exclude', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('exclude', 'post', 0);
    if (!isset($groupsList[$gid]) or $gid < 10) {
        exit($nv_Lang->getModule('error_group_not_found'));
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $row = $db->query('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                exit($nv_Lang->getModule('error_group_in_site'));
            }
        } else {
            exit($nv_Lang->getModule('search_not_result'));
        }
    }

    if (!nv_groups_del_user($gid, $uid, $module_data)) {
        exit($nv_Lang->getModule('admin_UserNotInGroup'));
    }

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('exclude_user2'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid']);
    exit('OK');
}

// Thăng cấp thành viên
if ($nv_Request->isset_request('gid,promote', 'post') and hash_equals(NV_CHECK_SESSION, $request_tokend)) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('promote', 'post', 0);
    if (!isset($groupsList[$gid]) or $gid < 10) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $row = $db->query('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
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

    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups_users SET is_leader = 1 WHERE group_id = ' . $gid . ' AND userid=' . $uid);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('promote'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'OK'
    ]);
}

// Giang cap quan tri
if ($nv_Request->isset_request('gid,demote', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('demote', 'post', 0);
    if (!isset($groupsList[$gid]) or $gid < 10) {
        exit($nv_Lang->getModule('error_group_not_found'));
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $row = $db->query('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                exit($nv_Lang->getModule('error_group_in_site'));
            }
        } else {
            exit($nv_Lang->getModule('search_not_result'));
        }
    }

    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups_users SET is_leader = 0 WHERE group_id = ' . $gid . ' AND userid=' . $uid);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('demote'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid']);
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
        $row = $db->query('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                exit($nv_Lang->getModule('error_group_in_site'));
            }
        } else {
            exit($nv_Lang->getModule('search_not_result'));
        }
    }

    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups_users SET approved = 1, time_approved = ' . NV_CURRENTTIME . ' WHERE group_id = ' . $gid . ' AND userid=' . $uid);
    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers+1 WHERE group_id = ' . $gid);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('approved'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid']);
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
        $row = $db->query('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $uid)->fetch();
        if (!empty($row)) {
            if ($row['idsite'] != $global_config['idsite']) {
                exit($nv_Lang->getModule('error_group_in_site'));
            }
        } else {
            exit($nv_Lang->getModule('search_not_result'));
        }
    }

    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = ' . $gid . ' AND userid=' . $uid);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('denied'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid']);
    exit('OK');
}

$nv_Lang->setModule('nametitle', $global_config['name_show'] == 0 ? $nv_Lang->getModule('lastname_firstname') : $nv_Lang->getModule('firstname_lastname'));

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('groups.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('MODULE_FILE', $module_file);
$tpl->assign('OP', $op);

// Danh sach thanh vien (AJAX)
if ($nv_Request->isset_request('listUsers', 'get')) {
    $group_id = $nv_Request->get_int('listUsers', 'get', 0);
    $page = $nv_Request->get_page('page', 'get', 1);
    $type = $nv_Request->get_title('type', 'get', '');
    $per_page = 15;
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=groups&listUsers=' . $group_id;

    if (!isset($groupsList[$group_id])) {
        exit($nv_Lang->getModule('error_group_not_found'));
    }
    $tpl->assign('GID', $group_id);
    $title = ($group_id < 10) ? $nv_Lang->getGlobal('level' . $group_id) : $groupsList[$group_id]['title'];

    $array_userid = [];
    $array_number = [];
    $group_users = [];

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
        $array_userid = [];
        while ($row = $result->fetch()) {
            $array_userid[$row['userid']] = $row;
        }
        $idsite = ($global_config['idsite'] == $groupsList[$group_id]['idsite']) ? 0 : $global_config['idsite'];
        $listUsers_data = [];
        foreach ($group_users as $_type => $arr_userids) {
            $type_data = [
                'title' => $nv_Lang->getModule('admin_' . $_type . '_in_group_caption', $title, nv_number_format($array_number[$_type])),
                'type' => $_type,
                'loop' => [],
                'page' => ''
            ];

            foreach ($arr_userids as $_userid) {
                $row = $array_userid[$_userid];
                $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
                $row['show_tools'] = ($group_id > 3 and ($idsite == 0 or $idsite == $row['idsite'])) ? true : false;
                $type_data['loop'][] = $row;
            }

            $generate_page = nv_generate_page($base_url . '&type=' . $_type, $array_number[$_type], $per_page, $page, true, false, 'nv_urldecode_ajax', 'id_' . $_type);
            if (!empty($generate_page)) {
                $type_data['page'] = $generate_page;
            }
            $listUsers_data[$_type] = $type_data;
        }

        $tpl->assign('LIST_USERS', $listUsers_data);

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

    nv_htmlOutput($tpl->fetch('groups_listusers.tpl'));
}

// Danh sach thanh vien
if ($nv_Request->isset_request('userlist', 'get')) {
    $group_id = $nv_Request->get_int('userlist', 'get', 0);
    if (!isset($groupsList[$group_id]) or !($group_id < 4 or $group_id > 9)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }

    $filtersql = ' userid NOT IN (SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id=' . $group_id . ')';
    if ($groupsList[$group_id]['idsite'] != $global_config['idsite'] and $groupsList[$group_id]['idsite'] == 0) {
        $filtersql .= ' AND idsite=' . $global_config['idsite'];
    }
    $tpl->assign('FILTERSQL', $crypt->encrypt($filtersql, NV_CHECK_SESSION));
    $tpl->assign('GID', $group_id);
    $tpl->assign('SHOW_ADD_USER', ($group_id > 9) ? true : false);

    $contents = $tpl->fetch('groups_userlist.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Thêm, sửa nhóm
if ($nv_Request->isset_request('add', 'get') or $nv_Request->isset_request('edit, id', 'get')) {
    if (defined('NV_IS_SPADMIN')) {
        $post = [];
        $post['id'] = $nv_Request->get_int('id', 'get');
        $checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $post['id']);

        if ($nv_Request->isset_request('edit', 'get')) {
            if (empty($post['id']) or !isset($groupsList[$post['id']]) or $groupsList[$post['id']]['idsite'] != $global_config['idsite']) {
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
            }

            $page_title = $nv_Lang->getModule('nv_admin_edit');
            $log_title = $nv_Lang->getModule('nv_admin_edit');
        } else {
            $page_title = $nv_Lang->getModule('nv_admin_add');
            $log_title = $nv_Lang->getModule('nv_admin_add');
        }

        if (defined('NV_EDITOR')) {
            require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
        }

        if ($nv_Request->isset_request('save', 'post')) {
            $checkss = $nv_Request->get_string('checkss', 'post', '');
            if (!hash_equals($checkss, md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $post['id']))) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getGlobal('error_invalid_session')
                ]);
            }
            // Sửa / Thêm full thông tin
            if (empty($post['id']) or $post['id'] > 9) {
                $post['title'] = $nv_Request->get_title('title', 'post', '', 1);
                if (empty($post['title'])) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'mess' => $nv_Lang->getModule('title_empty'),
                        'input' => 'title'
                    ]);
                }

                $post['alias'] = $nv_Request->get_title('alias', 'post', '');
                $post['alias'] = change_alias($post['alias'] ?: $post['title']);

                // Kiểm tra trùng tên nhóm
                $stmt = $db->prepare('SELECT group_id FROM ' . NV_MOD_TABLE . '_groups WHERE alias = :alias AND group_id!= ' . (int) ($post['id']) . ' AND (idsite=' . $global_config['idsite'] . ' or (idsite=0 AND siteus=1))');
                $stmt->bindParam(':alias', $post['alias'], PDO::PARAM_STR);
                $stmt->execute();
                if ($stmt->fetchColumn()) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'mess' => $nv_Lang->getModule('error_alias_exists', $post['alias']),
                        'input' => 'alias'
                    ]);
                }

                $post['description'] = $nv_Request->get_title('description', 'post', '', 1);
                $post['content'] = $nv_Request->get_editor('content', '', NV_ALLOWED_HTML_TAGS);
                $post['exp_time'] = nv_d2u_post($nv_Request->get_title('exp_time', 'post', ''), 23, 59, 59);

                $post['group_type'] = $nv_Request->get_int('group_type', 'post', 0);
                if (!in_array($post['group_type'], [0, 1, 2], true)) {
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
                $check_email = nv_check_valid_email($post['email'], true);
                if (!empty($post['email']) and $check_email[0] != '') {
                    nv_jsonOutput([
                        'status' => 'error',
                        'mess' => $check_email[0],
                        'input' => 'email'
                    ]);
                }
                $post['email'] = $check_email[1];
            } else {
                $post['email'] = '';
            }

            if (empty($post['id']) or $post['id'] > 9 or $post['id'] == 0 or $post['id'] == 1 or $post['id'] == 2 or $post['id'] == 3) {
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

            if (preg_match('/^([0-9a-fA-F]{6})$/i', $post['group_color']) or preg_match('/^([0-9a-fA-F]{3})$/i', $post['group_color'])) {
                $post['group_color'] = '#' . $post['group_color'];
            }

            $post['group_avatar'] = $nv_Request->get_title('group_avatar', 'post', '');

            if (!nv_is_url($post['group_avatar']) and nv_is_file($post['group_avatar'], NV_UPLOADS_DIR . '/' . $module_upload)) {
                $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
                $post['group_avatar'] = substr($post['group_avatar'], $lu);
            } elseif (!nv_is_url($post['group_avatar'])) {
                $post['group_avatar'] = '';
            }

            $post['require_2step_admin'] = $nv_Request->get_int('require_2step_admin', 'post', 0) ? 1 : 0;
            $post['require_2step_site'] = $nv_Request->get_int('require_2step_site', 'post', 0) ? 1 : 0;

            if (isset($post['id'])) {
                if ($nv_Request->isset_request('add', 'get')) {
                    $weight = $db->query('SELECT max(weight) FROM ' . NV_MOD_TABLE . '_groups WHERE idsite=' . $global_config['idsite'])->fetchColumn();
                    $weight = (int) $weight + 1;

                    $_sql = 'INSERT INTO ' . NV_MOD_TABLE . '_groups (
                        alias, email, group_type, group_color, group_avatar, require_2step_admin, require_2step_site, is_default, add_time, exp_time, weight, act,
                        idsite, numbers, siteus, config
                    ) VALUES (
                        :alias, :email, ' . $post['group_type'] . ', :group_color,
                        :group_avatar, ' . $post['require_2step_admin'] . ', ' . $post['require_2step_site'] . ', ' . $post['is_default'] . ', ' . NV_CURRENTTIME . ', ' . $post['exp_time'] . ',
                        ' . $weight . ', 1, ' . $global_config['idsite'] . ', 0, ' . $post['siteus'] . ', :config
                    )';

                    $data_insert = [];
                    $data_insert['alias'] = $post['alias'];
                    $data_insert['email'] = $post['email'];
                    $data_insert['group_color'] = $post['group_color'];
                    $data_insert['group_avatar'] = $post['group_avatar'];
                    $data_insert['config'] = $post['config'];

                    $ok = $post['id'] = $db->insert_id($_sql, 'group_id', $data_insert);
                    if ($ok) {
                        $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_groups_detail (group_id, lang, title, description, content) VALUES (' . $post['id'] . ", '" . NV_LANG_DATA . "', :title, :description, :content)");
                        $stmt->bindParam(':title', $post['title'], PDO::PARAM_STR);
                        $stmt->bindParam(':description', $post['description'], PDO::PARAM_STR);
                        $stmt->bindParam(':content', $post['content'], PDO::PARAM_STR, strlen($post['content']));
                        $stmt->execute();
                    }
                } elseif ($post['id'] > 9) {
                    // Sửa nhóm tự tạo
                    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . "_groups SET
                        alias = :alias,
                        email = :email,
                        group_type = '" . $post['group_type'] . "',
                        group_color = :group_color,
                        group_avatar = :group_avatar,
                        require_2step_admin = " . $post['require_2step_admin'] . ',
                        require_2step_site = ' . $post['require_2step_site'] . ',
                        is_default = ' . $post['is_default'] . ",
                        exp_time ='" . $post['exp_time'] . "',
                        siteus = '" . $post['siteus'] . "',
                        config = :config
                    WHERE group_id = " . $post['id']);

                    $stmt->bindParam(':alias', $post['alias'], PDO::PARAM_STR);
                    $stmt->bindParam(':email', $post['email'], PDO::PARAM_STR);
                    $stmt->bindParam(':group_color', $post['group_color']);
                    $stmt->bindParam(':group_avatar', $post['group_avatar']);
                    $stmt->bindParam(':config', $post['config'], PDO::PARAM_STR);

                    $ok = $stmt->execute();
                    if ($ok) {
                        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups_detail SET
                            title = :title,
                            description = :description,
                            content = :content
                        WHERE group_id = ' . $post['id'] . " AND lang='" . NV_LANG_DATA . "'");

                        $stmt->bindParam(':title', $post['title'], PDO::PARAM_STR);
                        $stmt->bindParam(':description', $post['description'], PDO::PARAM_STR);
                        $stmt->bindParam(':content', $post['content'], PDO::PARAM_STR, strlen($post['content']));
                        $stmt->execute();
                    }
                } else {
                    // Sửa nhóm hệ thống
                    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET
                        email = :email,
                        group_color = :group_color,
                        group_avatar = :group_avatar,
                        require_2step_admin = ' . $post['require_2step_admin'] . ',
                        require_2step_site = ' . $post['require_2step_site'] . ',
                        config = :config
                    WHERE group_id=' . $post['id']);

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
                nv_jsonOutput([
                    'status' => 'success',
                    'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true)
                ]);
            }
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('errorsave')
            ]);
        }

        if ($nv_Request->isset_request('edit', 'get')) {
            $post = $groupsList[$post['id']];
            $post['content'] = nv_editor_br2nl($post['content']);
            $post['exp_time'] = nv_u2d_post($post['exp_time']);
            $post['id'] = $post['group_id'];

            if (empty($post['config'])) {
                $post['config']['access_groups_add'] = $post['config']['access_groups_del'] = 1;
                $post['config']['access_addus'] = $post['config']['access_waiting'] = $post['config']['access_editus'] = $post['config']['access_delus'] = $post['config']['access_passus'] = $post['config']['access_passus'] = 0;
            } else {
                $post['config'] = unserialize($post['config']);
            }
        } else {
            $post['title'] = '';
            $post['email'] = '';
            $post['description'] = '';
            $post['content'] = '';
            $post['exp_time'] = '';
            $post['alias'] = '';
            $post['group_type'] = 0;
            $post['id'] = 0;
            $post['is_default'] = 0;
            $post['require_2step_admin'] = 0;
            $post['require_2step_site'] = 0;
            $post['siteus'] = 0;
            $post['group_color'] = '';
            $post['group_avatar'] = '';

            $post['config']['access_groups_add'] = $post['config']['access_groups_del'] = 1;
            $post['config']['access_addus'] = $post['config']['access_waiting'] = $post['config']['access_editus'] = $post['config']['access_delus'] = $post['config']['access_passus'] = $post['config']['access_passus'] = 0;
        }

        $post['content'] = htmlspecialchars(nv_editor_br2nl($post['content']));

        if (!empty($post['group_avatar']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $post['group_avatar'])) {
            $post['group_avatar'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $post['group_avatar'];
        }
        $post['checkss'] = $checkss;

        // Chuẩn bị dữ liệu cho template
        $tpl->assign('PAGE_TITLE', $page_title);
        $tpl->assign('DATA', $post);
        $tpl->assign('SHOW_SITEUS', (defined('NV_CONFIG_DIR') and empty($global_config['idsite'])) ? true : false);
        $tpl->assign('AVATAR_PATH', NV_UPLOADS_DIR . '/' . $module_upload);
        $tpl->assign('AVATAR_CURENT_PATH', NV_UPLOADS_DIR . '/' . $module_upload . '/groups');
        $tpl->assign('SHOW_BASIC_INFO', ($post['id'] > 9 or $post['id'] == 0) ? true : false);
        $tpl->assign('SHOW_EMAIL', ($post['id'] > 9 or $post['id'] == 0 or $post['id'] == 1 or $post['id'] == 2 or $post['id'] == 3 or $post['id'] == 4 or $post['id'] == 7) ? true : false);
        $tpl->assign('SHOW_CONFIG', ($post['id'] > 9 or $post['id'] == 0 or $post['id'] == 1 or $post['id'] == 2 or $post['id'] == 3) ? true : false);
        $tpl->assign('SHOW_2STEP_ADMIN', in_array((int) $global_config['two_step_verification'], [1, 3], true));
        $tpl->assign('SHOW_2STEP_SITE', in_array((int) $global_config['two_step_verification'], [2, 3], true));

        if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
            $_cont = nv_aleditor('content', '100%', '300px', $post['content']);
        } else {
            $_cont = '<textarea style="width:100%;height:300px" name="content" id="content">' . $post['content'] . '</textarea>';
        }
        $tpl->assign('EDITOR_CONTENT', $_cont);

        $group_type_options = [];
        for ($i = 0; $i <= 2; ++$i) {
            $group_type_options[] = [
                'key' => $i,
                'title' => $nv_Lang->getModule('group_type_' . $i),
                'selected' => $i == $post['group_type']
            ];
        }
        $tpl->assign('GROUP_TYPE_OPTIONS', $group_type_options);

        $contents = $tpl->fetch('groups_add.tpl');
    } else {
        $contents = $nv_Lang->getGlobal('admin_no_allow_func');
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Danh sách nhóm
$weight_op = 1;
$allGroupCount = count($groupsList);

$groups_list = [];
foreach ($groupsList as $group_id => $values) {
    if ($group_id < 4 or $group_id > 9) {
        $link_userlist = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;userlist=' . $group_id;
    } elseif ($group_id == 4) {
        $link_userlist = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;usactive=-3';
    } elseif ($group_id == 7) {
        $link_userlist = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;usactive=-2';
    } else {
        $link_userlist = '#';
    }

    $loop = [
        'group_id' => $group_id,
        'title' => ($group_id < 10) ? $nv_Lang->getGlobal('level' . $group_id) : $values['title'],
        'add_time' => nv_datetime_format($values['add_time']),
        'exp_time' => !empty($values['exp_time']) ? nv_datetime_format($values['exp_time']) : $nv_Lang->getGlobal('unlimited'),
        'number' => is_numeric($values['numbers']) ? nv_number_format($values['numbers']) : $values['numbers'],
        'act' => (int) $values['act'],
        'disabled' => ($group_id < 10 or !defined('NV_IS_SPADMIN') or $values['idsite'] != $global_config['idsite']) ? true : false,
        'link_userlist' => $link_userlist,
        'show_weight' => false,
        'weight_text' => '',
        'weight' => 0,
        'show_action' => false,
        'can_delete' => false
    ];

    if (defined('NV_IS_SPADMIN') and $values['idsite'] == $global_config['idsite']) {
        $_bg = empty($global_config['idsite']) ? 1 : $weight_op;
        $loop['show_weight'] = true;
        $loop['weight'] = $_bg + $values['weight'] - 1;
        $loop['show_action'] = true;

        if ($group_id > 9) {
            $loop['can_delete'] = true;
        }
    } else {
        ++$weight_op;
        $loop['weight_text'] = $values['weight'];
    }

    $groups_list[] = $loop;
}

$tpl->assign('GROUPS_LIST', $groups_list);
$tpl->assign('MAX_WEIGHT', $allGroupCount);
$tpl->assign('START_WEIGHT', empty($global_config['idsite']) ? 1 : ($weight_siteus + 1));
$tpl->assign('SHOW_ADD_NEW', defined('NV_IS_SPADMIN') ? true : false);
$tpl->assign('SHOW_ACTION_JS', defined('NV_IS_SPADMIN') ? true : false);

$contents = $tpl->fetch('groups.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
