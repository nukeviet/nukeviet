<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (! defined('NV_IS_MOD_USER')) {
    die('Stop!!!');
}

$page_title = $lang_module['group_manage'];
$contents = '';

if ($nv_Request->isset_request('get_user_json', 'post, get')) {
    $q = $nv_Request->get_title('q', 'post, get', '');

    $db->sqlreset()->select('userid, username, email, first_name, last_name')->from(NV_MOD_TABLE)->where('( username LIKE :username OR email LIKE :email OR first_name like :first_name OR last_name like :last_name ) AND userid NOT IN (SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users)')->order('username ASC')->limit(20);

    $sth = $db->prepare($db->sql());
    $sth->bindValue(':username', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':email', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':first_name', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':last_name', '%' . $q . '%', PDO::PARAM_STR);
    $sth->execute();

    $array_data = array();
    while (list($userid, $username, $email, $first_name, $last_name) = $sth->fetch(3)) {
        $array_data[] = array('id' => $userid, 'username' => $username, 'fullname' => nv_show_name_user($first_name, $last_name));
    }

    header('Cache-Control: no-cache, must-revalidate');
    header('Content-type: application/json');

    ob_start('ob_gzhandler');
    echo json_encode($array_data);
    exit();
}

// Lay danh sach nhom
$sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_groups WHERE idsite = ' . $global_config['idsite'] . ' or (idsite =0 AND group_id > 3 AND siteus = 1) ORDER BY idsite, weight';
$result = $db->query($sql);
$groupsList = array();
while ($row = $result->fetch()) {
	$count = $db->query('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id=' . $row['group_id'] . ' AND userid=' . $user_info['userid'] . ' AND is_leader=1')->fetchColumn();
	if ($count > 0) {
		$groupsList[$row['group_id']] = $row;
	}
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
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['addMemberToGroup'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);

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
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['exclude_user2'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);
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
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['promote'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);
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
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['demote'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);
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
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['approved'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);
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
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['denied'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);
    die('OK');
}

$lang_module['nametitle'] = $global_config['name_show'] == 0 ? $lang_module['lastname_firstname'] : $lang_module['firstname_lastname'];

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('TEMPLATE', $global_config['module_theme']);
$xtpl->assign('MODULE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE);
$xtpl->assign('OP', $op);

// Danh sach thanh vien
if (sizeof($array_op) == 2 and $array_op[0] == 'groups' and $array_op[1]) {
    $group_id = $array_op[1];
    if (! isset($groupsList[$group_id]) or ! ($group_id < 4 or $group_id > 9)) {
        Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }

	// Kiem tra lai quyen truong nhom
	$count = $db->query( 'SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id=' . $group_id . ' AND is_leader=1 AND userid=' . $user_info['userid'] )->fetchColumn();
	if ($count>0) {
	    $filtersql = ' userid NOT IN (SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id=' . $group_id . ')';
	    if ($groupsList[$group_id]['idsite'] != $global_config['idsite'] and $groupsList[$group_id]['idsite'] == 0) {
	        $filtersql .= ' AND idsite=' . $global_config['idsite'];
	    }
	    $xtpl->assign('FILTERSQL', nv_base64_encode($crypt->aes_encrypt($filtersql, md5($global_config['sitekey'] . $client_info['session_id']))));
	    $xtpl->assign('GID', $group_id);

	    if ($group_id > 9) {
	        $xtpl->parse('userlist.adduser');
	    }
	    $xtpl->parse('userlist');
	    $contents = $xtpl->text('userlist');
	} else {
		$contents = nv_theme_alert($lang_module['no_premission'], $lang_module['no_premission_leader'], 'danger');
	}

	// Them vao tieu de
	$array_mod_title[] = array(
	    'catid' => 0,
	    'title' => $lang_module['group_manage'],
	    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op
	);
	$array_mod_title[] = array(
	    'catid' => 0,
	    'title' => $groupsList[$group_id]['title'],
	    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_id
	);

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
                	if ($user_info['userid'] != $_userid) {
                		$xtpl->parse('listUsers.' . $_type . '.loop.tools');
					}
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

// Danh sach nhom (AJAX)
if ($nv_Request->isset_request('list', 'get')) {
    foreach ($groupsList as $group_id => $values) {
        $xtpl->assign('GROUP_ID', $group_id);

        $loop = array(
            'title' => $values['title'],
            'add_time' => nv_date('d/m/Y H:i', $values['add_time']),
            'exp_time' => ! empty($values['exp_time']) ? nv_date('d/m/Y H:i', $values['exp_time']) : $lang_global['unlimited'],
            'number' => number_format($values['numbers']),
            'link_userlist' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op .'/' . $group_id, true)
        );

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
        if ($_li['func_name'] == 'register' and ! $global_config['allowuserreg']) {
            continue;
        }

        $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
        if (! empty($nv_redirect)) {
            $href .= '&nv_redirect=' . $nv_redirect;
        }
        $li = array( 'href' => $href, 'title' => $_li['func_name'] == 'main' ? $module_info['custom_title'] : $_li['func_custom_name'] );
        $xtpl->assign('NAVBAR', $li);
        $xtpl->parse('main.navbar');
    }
}

// Them vao tieu de
$array_mod_title[] = array(
    'catid' => 0,
    'title' => $lang_module['group_manage'],
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op
);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';