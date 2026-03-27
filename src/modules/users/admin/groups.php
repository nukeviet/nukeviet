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
 * Lấy liên kết tĩnh của nhóm
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
    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups WHERE alias = :alias AND group_id != :id AND (idsite = :idsite OR (idsite = 0 AND siteus = 1))');
    $stmt->bindValue(':alias', $_alias, PDO::PARAM_STR);
    $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
    $stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        ++$num;

        return getAlias($alias, $id, $num);
    }
    $stmt->closeCursor();

    return $_alias;
}

// Lấy alias nhóm
if ($nv_Request->isset_request('getAlias, id, title', 'post')) {
    $id = $nv_Request->get_title('id', 'post', 0);
    $title = $nv_Request->get_title('title', 'post', '', 1);

    $alias = '';
    if (!empty($title)) {
        $alias = getAlias(change_alias($title), $id);
    }
    nv_htmlOutput($alias);
}

$page_title = $nv_Lang->getGlobal('mod_groups');

// Lấy danh sách nhóm
$stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_groups AS g LEFT JOIN ' . NV_MOD_TABLE . '_groups_detail d ON (g.group_id = d.group_id AND d.lang = :lang) WHERE g.idsite = :idsite OR (g.idsite = 0 AND g.group_id > 3 AND g.siteus = 1) ORDER BY g.idsite, g.weight ASC');
$stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
$stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
$stmt->execute();
$groupsList = [];
$weight_siteus = 0;
$checkEmptyGroup = 0; // Sử dụng cái này để tính cả những nhóm "SHARE"

while ($row = $stmt->fetch()) {
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
$stmt->closeCursor();

// Thống kê thành viên
if (!empty($global_config['idsite'])) {
    // Thành viên mới của site
    $stmt = $db->prepare('SELECT COUNT(userid) FROM ' . NV_MOD_TABLE . ' WHERE idsite = :idsite AND (group_id = 7 OR FIND_IN_SET(7, in_groups))');
    $stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
    $stmt->execute();
    $groupsList[7]['numbers'] = $stmt->fetchColumn();
    $stmt->closeCursor();

    // Thành viên chính thức của site
    $stmt = $db->prepare('SELECT COUNT(userid) FROM ' . NV_MOD_TABLE . ' WHERE idsite = :idsite');
    $stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
    $stmt->execute();
    $all_member = $stmt->fetchColumn();
    $stmt->closeCursor();
    $groupsList[4]['numbers'] = $all_member - $groupsList[7]['numbers'];
}
$groupsList[5]['numbers'] = '-';
$groupsList[6]['numbers'] = '-';

// Chuyển sang trang tạo nhóm nếu chưa có nhóm nào
if (!$checkEmptyGroup and !$nv_Request->isset_request('add', 'get')) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&add');
}

$checkss = $nv_Request->get_title('checkss', 'post', '');

// Thay đổi thứ tự nhóm
if ($nv_Request->isset_request('cWeight, id', 'post')) {
    if (!csrf_check($checkss, $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

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

    $stmt = $db->prepare('SELECT group_id FROM ' . NV_MOD_TABLE . '_groups WHERE group_id != :group_id AND idsite = :idsite ORDER BY weight ASC');
    $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
    $stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
    $stmt->execute();

    $weight = 0;
    $stmt_update = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET weight = :weight WHERE group_id = :gid');
    while ($row = $stmt->fetch()) {
        ++$weight;
        if ($weight == $cWeight) {
            ++$weight;
        }
        $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_update->bindValue(':gid', $row['group_id'], PDO::PARAM_INT);
        $stmt_update->execute();
    }
    $stmt->closeCursor();

    $stmt_update->bindValue(':weight', $cWeight, PDO::PARAM_INT);
    $stmt_update->bindValue(':gid', $group_id, PDO::PARAM_INT);
    $stmt_update->execute();

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('changeGroupWeight'), 'group_id: ' . $group_id, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'success'
    ]);
}

// Kích hoạt/ Đình chỉ nhóm
if ($nv_Request->isset_request('act', 'post')) {
    if (!csrf_check($checkss, $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $group_id = $nv_Request->get_int('act', 'post');
    if (!isset($groupsList[$group_id]) or !defined('NV_IS_SPADMIN') or $group_id < 10 or $groupsList[$group_id]['idsite'] != $global_config['idsite']) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }

    $act = $groupsList[$group_id]['act'] ? 0 : 1;
    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET act = :act WHERE group_id = :group_id');
    $stmt->bindValue(':act', $act, PDO::PARAM_INT);
    $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
    $stmt->execute();

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('ChangeGroupAct'), 'group_id: ' . $group_id, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'success',
        'new_status' => $act
    ]);
}

// Xóa nhóm
if ($nv_Request->isset_request('del', 'post')) {
    if (!csrf_check($checkss, $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $group_id = $nv_Request->get_int('del', 'post', 0);

    if (!isset($groupsList[$group_id]) or !defined('NV_IS_SPADMIN') or $group_id < 10 or $groupsList[$group_id]['idsite'] != $global_config['idsite']) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }

    $array_groups = [];
    $stmt = $db->prepare('SELECT group_id, userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid IN (SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :group_id)');
    $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
    $stmt->execute();

    while ($row = $stmt->fetch()) {
        $array_groups[$row['userid']][$row['group_id']] = 1;
    }
    $stmt->closeCursor();

    if (!empty($array_groups)) {
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET in_groups = :in_groups, last_update = :last_update WHERE userid = :userid');
        foreach ($array_groups as $userid => $gr) {
            unset($gr[$group_id]);
            $in_groups = array_keys($gr);
            $stmt->bindValue(':in_groups', implode(',', $in_groups), PDO::PARAM_STR);
            $stmt->bindValue(':last_update', NV_CURRENTTIME, PDO::PARAM_INT);
            $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_groups WHERE group_id = :group_id');
    $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_groups_detail WHERE group_id = :group_id');
    $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :group_id');
    $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
    $stmt->execute();

    // Cập nhật lại thứ tự
    $stmt = $db->prepare('SELECT group_id FROM ' . NV_MOD_TABLE . '_groups WHERE idsite = :idsite ORDER BY weight ASC');
    $stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
    $stmt->execute();

    $weight = 0;
    $stmt_update = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET weight = :weight WHERE group_id = :gid');
    while ($row = $stmt->fetch()) {
        ++$weight;
        $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_update->bindValue(':gid', $row['group_id'], PDO::PARAM_INT);
        $stmt_update->execute();
    }
    $stmt->closeCursor();

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('delGroup'), 'group_id: ' . $group_id, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'success'
    ]);
}

// Xóa các nhóm đang ngưng kích hoạt
if ($nv_Request->isset_request('deleteinactive', 'post') and defined('NV_IS_SPADMIN')) {
    if (!csrf_check($checkss, $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $num_deleted = 0;

    foreach ($groupsList as $group_id => $group_row) {
        if ($group_id > 9 and $group_row['idsite'] == $global_config['idsite'] and empty($group_row['act'])) {
            $array_groups = [];
            $stmt = $db->prepare('SELECT group_id, userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid IN (
                SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :group_id
            )');
            $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $array_groups[$row['userid']][$row['group_id']] = 1;
            }
            $stmt->closeCursor();

            if (!empty($array_groups)) {
                $stmt_upd = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET in_groups = :in_groups, last_update = :last_update WHERE userid = :userid');
                foreach ($array_groups as $userid => $gr) {
                    unset($gr[$group_id]);
                    $in_groups = array_keys($gr);
                    $stmt_upd->bindValue(':in_groups', implode(',', $in_groups), PDO::PARAM_STR);
                    $stmt_upd->bindValue(':last_update', NV_CURRENTTIME, PDO::PARAM_INT);
                    $stmt_upd->bindValue(':userid', $userid, PDO::PARAM_INT);
                    $stmt_upd->execute();
                }
            }

            $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_groups WHERE group_id = :group_id');
            $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_groups_detail WHERE group_id = :group_id');
            $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :group_id');
            $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
            $stmt->execute();

            ++$num_deleted;
        }
    }

    // Cập nhật lại thứ tự
    $stmt = $db->prepare('SELECT group_id FROM ' . NV_MOD_TABLE . '_groups WHERE idsite = :idsite ORDER BY weight ASC');
    $stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
    $stmt->execute();

    $weight = 0;
    $stmt_upd = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET weight = :weight WHERE group_id = :gid');
    while ($row = $stmt->fetch()) {
        ++$weight;
        $stmt_upd->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_upd->bindValue(':gid', $row['group_id'], PDO::PARAM_INT);
        $stmt_upd->execute();
    }
    $stmt->closeCursor();

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('group_del_inactive'), 'Num: ' . $num_deleted, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getModule('delete_success')
    ]);
}

// Thêm thành viên vào nhóm
if ($nv_Request->isset_request('gid,uid', 'post')) {
    if (!csrf_check($checkss, $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('uid', 'post', 0);
    if (!isset($groupsList[$gid]) or $gid < 10) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $stmt_idsite = $db->prepare('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid = :uid');
        $stmt_idsite->bindValue(':uid', $uid, PDO::PARAM_INT);
        $stmt_idsite->execute();
        $row = $stmt_idsite->fetch();
        $stmt_idsite->closeCursor();
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
        'mess' => 'ok'
    ]);
}

// Loại thành viên khỏi nhóm
if ($nv_Request->isset_request('gid,exclude', 'post')) {
    if (!csrf_check($checkss, $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('exclude', 'post', 0);
    if (!isset($groupsList[$gid]) or $gid < 10) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $stmt_idsite = $db->prepare('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid = :uid');
        $stmt_idsite->bindValue(':uid', $uid, PDO::PARAM_INT);
        $stmt_idsite->execute();
        $row = $stmt_idsite->fetch();
        $stmt_idsite->closeCursor();
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

    if (!nv_groups_del_user($gid, $uid, $module_data)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('admin_UserNotInGroup')
        ]);
    }

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('exclude_user2'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'ok'
    ]);
}

// Thăng cấp thành viên
if ($nv_Request->isset_request('gid,promote', 'post')) {
    if (!csrf_check($checkss, $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('promote', 'post', 0);
    if (!isset($groupsList[$gid]) or $gid < 10) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $stmt_idsite = $db->prepare('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid = :uid');
        $stmt_idsite->bindValue(':uid', $uid, PDO::PARAM_INT);
        $stmt_idsite->execute();
        $row = $stmt_idsite->fetch();
        $stmt_idsite->closeCursor();
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

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups_users SET is_leader = 1 WHERE group_id = :gid AND userid = :uid');
    $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
    $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
    $stmt->execute();

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('promote'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'ok'
    ]);
}

// Giáng cấp quản trị
if ($nv_Request->isset_request('gid,demote', 'post')) {
    if (!csrf_check($checkss, $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('demote', 'post', 0);
    if (!isset($groupsList[$gid]) or $gid < 10) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $stmt_idsite = $db->prepare('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid = :uid');
        $stmt_idsite->bindValue(':uid', $uid, PDO::PARAM_INT);
        $stmt_idsite->execute();
        $row = $stmt_idsite->fetch();
        $stmt_idsite->closeCursor();
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

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups_users SET is_leader = 0 WHERE group_id = :gid AND userid = :uid');
    $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
    $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
    $stmt->execute();

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('demote'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'ok'
    ]);
}

// Duyệt vào nhóm
if ($nv_Request->isset_request('gid,approved', 'post')) {
    if (!csrf_check($checkss, $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('approved', 'post', 0);
    if (!isset($groupsList[$gid]) or $gid < 10) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $stmt_idsite = $db->prepare('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid = :uid');
        $stmt_idsite->bindValue(':uid', $uid, PDO::PARAM_INT);
        $stmt_idsite->execute();
        $row = $stmt_idsite->fetch();
        $stmt_idsite->closeCursor();
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

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups_users SET approved = 1, time_approved = :time_approved WHERE group_id = :gid AND userid = :uid');
    $stmt->bindValue(':time_approved', NV_CURRENTTIME, PDO::PARAM_INT);
    $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
    $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers + 1 WHERE group_id = :gid');
    $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
    $stmt->execute();

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('approved'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'ok'
    ]);
}

// Từ chối gia nhập nhóm
if ($nv_Request->isset_request('gid,denied', 'post')) {
    if (!csrf_check($checkss, $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('denied', 'post', 0);
    if (!isset($groupsList[$gid]) or $gid < 10) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_group_not_found')
        ]);
    }

    if ($groupsList[$gid]['idsite'] != $global_config['idsite'] and $groupsList[$gid]['idsite'] == 0) {
        $stmt_idsite = $db->prepare('SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid = :uid');
        $stmt_idsite->bindValue(':uid', $uid, PDO::PARAM_INT);
        $stmt_idsite->execute();
        $row = $stmt_idsite->fetch();
        $stmt_idsite->closeCursor();
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

    $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :gid AND userid = :uid');
    $stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
    $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
    $stmt->execute();

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('denied'), 'Member Id: ' . $uid . ' group ID: ' . $gid, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'ok'
    ]);
}

$nv_Lang->setModule('nametitle', $global_config['name_show'] == 0 ? $nv_Lang->getModule('lastname_firstname') : $nv_Lang->getModule('firstname_lastname'));

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('groups.tpl'));
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('MODULE_FILE', $module_file);
$tpl->assign('OP', $op);

// Danh sách thành viên của nhóm (AJAX)
if ($nv_Request->isset_request('listUsers', 'get')) {
    $group_id = $nv_Request->get_int('listUsers', 'get', 0);
    $page = $nv_Request->get_page('page', 'get', 1);
    $type = $nv_Request->get_title('type', 'get', '');
    $per_page = 15;
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=groups&listUsers=' . $group_id;

    if (!isset($groupsList[$group_id])) {
        nv_htmlOutput($nv_Lang->getModule('error_group_not_found'));
    }
    $tpl->assign('GID', $group_id);
    $title = ($group_id < 10) ? $nv_Lang->getGlobal('level' . $group_id) : $groupsList[$group_id]['title'];

    $array_userid = [];
    $array_number = [];
    $group_users = [];

    // Danh sách xin gia nhập nhóm
    if (empty($type) or $type == 'pending') {
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :gid AND approved = 0');
        $stmt->bindValue(':gid', $group_id, PDO::PARAM_INT);
        $stmt->execute();
        $array_number['pending'] = $stmt->fetchColumn();
        $stmt->closeCursor();

        if ($array_number['pending']) {
            $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :gid AND approved = 0 LIMIT :limit OFFSET :offset');
            $stmt->bindValue(':gid', $group_id, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
            $stmt->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
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
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :gid AND is_leader = 1');
        $stmt->bindValue(':gid', $group_id, PDO::PARAM_INT);
        $stmt->execute();
        $array_number['leaders'] = $stmt->fetchColumn();
        $stmt->closeCursor();

        if ($array_number['leaders']) {
            $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :gid AND is_leader = 1 LIMIT :limit OFFSET :offset');
            $stmt->bindValue(':gid', $group_id, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
            $stmt->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
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
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :gid AND approved = 1 AND is_leader = 0');
        $stmt->bindValue(':gid', $group_id, PDO::PARAM_INT);
        $stmt->execute();
        $array_number['members'] = $stmt->fetchColumn();
        $stmt->closeCursor();

        if ($array_number['members']) {
            $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = :gid AND approved = 1 AND is_leader = 0 LIMIT :limit OFFSET :offset');
            $stmt->bindValue(':gid', $group_id, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
            $stmt->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $group_users['members'][] = $row['userid'];
                $array_userid[] = $row['userid'];
            }
            $stmt->closeCursor();
        }
    }

    if (!empty($group_users)) {
        $in_userids = implode(',', array_fill(0, count($array_userid), '?'));
        $stmt = $db->prepare('SELECT userid, username, first_name, last_name, email, idsite FROM ' . NV_MOD_TABLE . ' WHERE userid IN (' . $in_userids . ')');
        foreach (array_values($array_userid) as $k => $uid) {
            $stmt->bindValue(($k + 1), $uid, PDO::PARAM_INT);
        }
        $stmt->execute();
        $array_userid = [];
        while ($row = $stmt->fetch()) {
            $array_userid[$row['userid']] = $row;
        }
        $stmt->closeCursor();
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

            $generate_page = nv_generate_page($base_url . '&type=' . $_type, $array_number[$_type], $per_page, $page, true, true, 'nv_urldecode_ajax', 'id_' . $_type);
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
                $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = :numberusers WHERE group_id = :gid');
                $stmt->bindValue(':numberusers', $numberusers, PDO::PARAM_INT);
                $stmt->bindValue(':gid', $group_id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }

    nv_htmlOutput($tpl->fetch('groups_listusers.tpl'));
}

// Danh sách thành viên (Page HTML)
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
            if (!csrf_check($checkss, $csrf_key)) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getGlobal('error_checkss')
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
                $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups WHERE alias = :alias AND group_id != :id AND (idsite = :idsite OR (idsite = 0 AND siteus = 1))');
                $stmt->bindValue(':alias', $post['alias'], PDO::PARAM_STR);
                $stmt->bindValue(':id', (int) $post['id'], PDO::PARAM_INT);
                $stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
                $stmt->execute();
                if ($stmt->fetchColumn()) {
                    $stmt->closeCursor();
                    nv_jsonOutput([
                        'status' => 'error',
                        'mess' => $nv_Lang->getModule('error_alias_exists', $post['alias']),
                        'input' => 'alias'
                    ]);
                }
                $stmt->closeCursor();

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
                    $stmt = $db->prepare('SELECT MAX(weight) FROM ' . NV_MOD_TABLE . '_groups WHERE idsite = :idsite');
                    $stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
                    $stmt->execute();
                    $weight = (int) $stmt->fetchColumn();
                    $stmt->closeCursor();
                    $weight = $weight + 1;

                    $_sql = 'INSERT INTO ' . NV_MOD_TABLE . '_groups (
                        alias, email, group_type, group_color, group_avatar, require_2step_admin, require_2step_site, is_default, add_time, exp_time, weight, act,
                        idsite, numbers, siteus, config
                    ) VALUES (
                        :alias, :email, :group_type, :group_color,
                        :group_avatar, :require_2step_admin, :require_2step_site, :is_default, :add_time, :exp_time,
                        :weight, 1, :idsite, 0, :siteus, :config
                    )';

                    $stmt = $db->prepare($_sql);
                    $stmt->bindValue(':alias', $post['alias'], PDO::PARAM_STR);
                    $stmt->bindValue(':email', $post['email'], PDO::PARAM_STR);
                    $stmt->bindValue(':group_type', $post['group_type'], PDO::PARAM_INT);
                    $stmt->bindValue(':group_color', $post['group_color'], PDO::PARAM_STR);
                    $stmt->bindValue(':group_avatar', $post['group_avatar'], PDO::PARAM_STR);
                    $stmt->bindValue(':require_2step_admin', $post['require_2step_admin'], PDO::PARAM_INT);
                    $stmt->bindValue(':require_2step_site', $post['require_2step_site'], PDO::PARAM_INT);
                    $stmt->bindValue(':is_default', $post['is_default'], PDO::PARAM_INT);
                    $stmt->bindValue(':add_time', NV_CURRENTTIME, PDO::PARAM_INT);
                    $stmt->bindValue(':exp_time', $post['exp_time'], PDO::PARAM_INT);
                    $stmt->bindValue(':weight', $weight, PDO::PARAM_INT);
                    $stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
                    $stmt->bindValue(':siteus', $post['siteus'], PDO::PARAM_INT);
                    $stmt->bindValue(':config', $post['config'], PDO::PARAM_STR);

                    $ok = $stmt->execute();
                    $post['id'] = $db->lastInsertId();
                    if ($ok) {
                        $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_groups_detail (group_id, lang, title, description, content) VALUES (:group_id, :lang, :title, :description, :content)');
                        $stmt->bindValue(':group_id', $post['id'], PDO::PARAM_INT);
                        $stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
                        $stmt->bindValue(':title', $post['title'], PDO::PARAM_STR);
                        $stmt->bindValue(':description', $post['description'], PDO::PARAM_STR);
                        $stmt->bindValue(':content', $post['content'], PDO::PARAM_STR);
                        $stmt->execute();
                    }
                } elseif ($post['id'] > 9) {
                    // Sửa nhóm tự tạo
                    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET
                    alias = :alias, email = :email, group_type = :group_type, group_color = :group_color, group_avatar = :group_avatar, require_2step_admin = :require_2step_admin, require_2step_site = :require_2step_site, is_default = :is_default, exp_time = :exp_time, siteus = :siteus, config = :config
                        WHERE group_id = :group_id');

                    $stmt->bindValue(':alias', $post['alias'], PDO::PARAM_STR);
                    $stmt->bindValue(':email', $post['email'], PDO::PARAM_STR);
                    $stmt->bindValue(':group_type', $post['group_type'], PDO::PARAM_INT);
                    $stmt->bindValue(':group_color', $post['group_color'], PDO::PARAM_STR);
                    $stmt->bindValue(':group_avatar', $post['group_avatar'], PDO::PARAM_STR);
                    $stmt->bindValue(':require_2step_admin', $post['require_2step_admin'], PDO::PARAM_INT);
                    $stmt->bindValue(':require_2step_site', $post['require_2step_site'], PDO::PARAM_INT);
                    $stmt->bindValue(':is_default', $post['is_default'], PDO::PARAM_INT);
                    $stmt->bindValue(':exp_time', $post['exp_time'], PDO::PARAM_INT);
                    $stmt->bindValue(':siteus', $post['siteus'], PDO::PARAM_INT);
                    $stmt->bindValue(':config', $post['config'], PDO::PARAM_STR);
                    $stmt->bindValue(':group_id', $post['id'], PDO::PARAM_INT);

                    $ok = $stmt->execute();
                    if ($ok) {
                        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups_detail SET
                            title = :title,
                            description = :description,
                            content = :content
                        WHERE group_id = :group_id AND lang = :lang');

                        $stmt->bindValue(':title', $post['title'], PDO::PARAM_STR);
                        $stmt->bindValue(':description', $post['description'], PDO::PARAM_STR);
                        $stmt->bindValue(':content', $post['content'], PDO::PARAM_STR);
                        $stmt->bindValue(':group_id', $post['id'], PDO::PARAM_INT);
                        $stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
                        $stmt->execute();
                    }
                } else {
                    // Sửa nhóm hệ thống
                    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET
                        email = :email,
                        group_color = :group_color,
                        group_avatar = :group_avatar,
                        require_2step_admin = :require_2step_admin,
                        require_2step_site = :require_2step_site,
                        config = :config
                    WHERE group_id = :group_id');

                    $stmt->bindValue(':email', $post['email'], PDO::PARAM_STR);
                    $stmt->bindValue(':group_color', $post['group_color'], PDO::PARAM_STR);
                    $stmt->bindValue(':group_avatar', $post['group_avatar'], PDO::PARAM_STR);
                    $stmt->bindValue(':require_2step_admin', $post['require_2step_admin'], PDO::PARAM_INT);
                    $stmt->bindValue(':require_2step_site', $post['require_2step_site'], PDO::PARAM_INT);
                    $stmt->bindValue(':config', $post['config'], PDO::PARAM_STR);
                    $stmt->bindValue(':group_id', $post['id'], PDO::PARAM_INT);

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
                $post['config'] = unserialize($post['config'], NV_UNSERIALIZE_SAFE);
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
        $post['checkss'] = csrf_create($csrf_key);

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
