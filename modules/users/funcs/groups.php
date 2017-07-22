<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (! defined('NV_IS_MOD_USER')) {
    die('Stop!!!');
}

$page_title = $lang_module['group_manage'];
$contents = '';

// Lay danh sach nhom
$sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_groups WHERE idsite = ' . $global_config['idsite'] . ' or (idsite =0 AND group_id > 3 AND siteus = 1) ORDER BY idsite, weight';
$result = $db->query($sql);
$groupsList = array();
while ($row = $result->fetch()) {
	$count = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id=' . $row['group_id'] . ' AND userid=' . $user_info['userid'] . ' AND is_leader=1')->rowCount();

	if ($count > 0) {
		$row['config'] = unserialize($row['config']);
		$groupsList[$row['group_id']] = $row;
	}
}

if ($nv_Request->isset_request('gid, get_user_json ', 'post, get')) {
    $q = $nv_Request->get_title('q', 'post, get', '');
	$gid = $nv_Request->get_int('gid', 'post, get', 0);

	if (! isset($groupsList[$gid])) {
		die($lang_module['no_premission_leader']);
	}

    $db->sqlreset()
    	->select('userid, username, email, first_name, last_name')
    	->from(NV_MOD_TABLE)
    	->where('( username LIKE :username OR email LIKE :email OR first_name like :first_name OR last_name like :last_name ) AND userid NOT IN (SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users)')
    	->order('username ASC')
    	->limit(20);

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

    nv_jsonOutput($array_data);
}

//lấy danh sách user chưa kích hoạt
if ($nv_Request->isset_request('gid, getuserid', 'post, get')) {
	$gid = $nv_Request->get_int('gid', 'post, get', 0);

	if (! isset($groupsList[$gid])) {
		die($lang_module['no_premission_leader']);
	}

	//Kich hoat thanh vien
	if ($nv_Request->isset_request('act', 'get, post')) {
	    $userid = $nv_Request->get_int('userid', 'get, post', 0);

	    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_reg WHERE userid=' . $userid;
	    $row = $db->query($sql)->fetch();
	    if (empty($row)) {
	        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
	    }

	    $sql = "INSERT INTO " . NV_MOD_TABLE . " (
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
			'', 0, 0, '', 1, '', 0, '', '', '', " . $global_config['idsite'] . ")";

	    $data_insert = array();
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
	        if (! empty($row['openid_info'])) {
	            $reg_attribs = unserialize(nv_base64_decode($row['openid_info']));
	            $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_openid VALUES (' . $userid . ', :server, :opid , :email)');
	            $stmt->bindParam(':server', $reg_attribs['server'], PDO::PARAM_STR);
	            $stmt->bindParam(':opid', $reg_attribs['opid'], PDO::PARAM_STR);
	            $stmt->bindParam(':email', $reg_attribs['email'], PDO::PARAM_STR);
	            $stmt->execute();
	        }

	        $db->query('INSERT INTO ' . NV_MOD_TABLE . '_groups_users VALUES('.$gid.','.$userid.',0,1,0)');
			$db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers+1 WHERE group_id=4 or group_id='.$gid);
			$db->query('UPDATE ' . NV_MOD_TABLE . ' SET group_id = '.$gid.', in_groups='.$gid.' WHERE userid='.$userid);
	        $users_info = unserialize(nv_base64_decode($row['users_info']));
	        $query_field = array();
	        $query_field['userid'] = $userid;
	        $result_field = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field ORDER BY fid ASC');
	        while ($row_f = $result_field->fetch()) {
	            $query_field[$row_f['field']] = (isset($users_info[$row_f['field']])) ? $users_info[$row_f['field']] : $db->quote($row_f['default_value']);
	        }

	        if ($db->exec('INSERT INTO ' . NV_MOD_TABLE . '_info (' . implode(', ', array_keys($query_field)) . ') VALUES (' . implode(', ', array_values($query_field)) . ')')) {
	            $db->query('DELETE FROM ' . NV_MOD_TABLE . '_reg WHERE userid=' . $row['userid']);

	            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['active_users'], 'userid: ' . $userid . ' - username: ' . $row['username'], $user_info['userid']);

	            $full_name = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
	            $subject = $lang_module['adduser_register'];
                $_url = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true);
	            if (strpos($_url, NV_MY_DOMAIN) !== 0) {
	                $_url = NV_MY_DOMAIN . $_url;
	            }
	            $message = sprintf($lang_module['adduser_register_info'], $full_name, $global_config['site_name'], $_url, $row['username']);
	            @nv_sendmail($global_config['site_email'], $row['email'], $subject, $message);
	        } else {
	            $db->query('DELETE FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $row['userid']);
	        }
	    }

	    die('OK');
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

	$array = array();
	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&amp;area=' . $area . '&amp;return=' . $return . '&amp;submit=1';

	if ($nv_Request->isset_request('submit', 'get')) {
	    $array_user = array();
	    $generate_page = '';

	    $array['user_id'] = $nv_Request->get_title('user_id', 'get', '');
	    $array['username'] = $nv_Request->get_title('username', 'get', '');
	    $array['full_name'] = $nv_Request->get_title('full_name', 'get', '');
	    $array['email'] = $nv_Request->get_title('email', 'get', '');

	    $is_null = true;
	    foreach ($array as $check) {
	        if (! empty($check)) {
	            $is_null = false;
	            break;
	        }
	    }

	    $array_where = array();

		if (! empty($array['user_id'])) {
	        $base_url .= '&amp;user_id=' . rawurlencode($array['user_id']);
	        $array_where[] = "( userid = '" . $array['user_id'] . "' )";
	    }

	    if (! empty($array['username'])) {
	        $base_url .= '&amp;username=' . rawurlencode($array['username']);
	        $array_where[] = "( username LIKE '%" . $db->dblikeescape($array['username']) . "%' )";
	    }

	    if (! empty($array['full_name'])) {
	        $base_url .= '&amp;full_name=' . rawurlencode($array['full_name']);

	        $where_fullname = $global_config['name_show'] == 0 ? "concat(last_name,' ',first_name)" : "concat(first_name,' ',last_name)";
	        $array_where[] =  "(" . $where_fullname ." LIKE '%" . $db->dblikeescape($array['full_name']) . "%' )";
	    }

	    if (! empty($array['email'])) {
	        $base_url .= '&amp;email=' . rawurlencode($array['email']);
	        $array_where[] = "( email LIKE '%" . $db->dblikeescape($array['email']) . "%' )";
	    }

	    $page = $nv_Request->get_int('page', 'get', 1);
	    $per_page = 10;

	    $db->sqlreset()
	        ->select('COUNT(*)')
	        ->from(NV_MOD_TABLE.'_reg');
	    if (! empty($array_where)) {
	        $db->where(implode(' AND ', $array_where));
	    }

	    $num_items = $db->query($db->sql())->fetchColumn();

	    $db->select('*')
	        ->limit($per_page)
	        ->offset(($page - 1) * $per_page);
	    $result2 = $db->query($db->sql());
	    while ($row = $result2->fetch()) {
	        $array_user[$row['userid']] = $row;
	    }

	    if (! empty($array_user)) {
	        foreach ($array_user as $row) {
	            $row['regdate'] = nv_date('d/m/Y H:i', $row['regdate']);
	            $row['return'] = $row[$return];
	            $xtpl->assign('ROW', $row);
	            $xtpl->parse('resultdata.data.row');
	        }

	        $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
	        if (! empty($generate_page)) {
	            $xtpl->assign('GENERATE_PAGE', $generate_page);
	            $xtpl->parse('resultdata.data.generate_page');
	        }

	        $xtpl->parse('resultdata.data');
	    } elseif ($nv_Request->isset_request('submit', 'get')) {
	        $xtpl->parse('resultdata.nodata');
	    }

	    $xtpl->parse('resultdata');
	    $contents = $xtpl->text('resultdata');

	    echo $contents;
		die();
	}
	else {
	    $xtpl->parse('main');
	    $contents = $xtpl->text('main');

	    echo $contents;
		die();
	}
}

// Xóa thành viên
if ($nv_Request->isset_request('gid,del', 'post')) {
	$gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('del', 'post', 0);

	if (! isset($groupsList[$gid]) or $gid < 10) {
        die($lang_module['error_group_not_found']);
    }

	//kiểm tra user_id xóa có nằm trong nhóm được quản lí k, hoặc nằm trong nhóm khác
	$result_user = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $uid );
	$array_groups_user = array();
	while ($_row = $result_user->fetch()) {
		$array_groups_user[$_row['group_id']] = $_row;
	}

	if (! isset($array_groups_user[$gid])){// không nằm trong danh sách nhóm dc quản lí
		die($lang_module['del_user_err']);
	}else{// nằm ở 2 nhóm khác
		die($lang_module['not_del_user']);
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

    if (! nv_del_user( $uid)) {
        die($lang_module['del_user_err']);
    }

    $nv_Cache->delMod($module_name);
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
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['addMemberToGroup'], 'Member Id: ' . $uid . ' group ID: ' . $gid, $user_info['userid']);

    die('OK');
}

// Loai thanh vien khoi nhom
if ($nv_Request->isset_request('gid,exclude', 'post')) {
    $gid = $nv_Request->get_int('gid', 'post', 0);
    $uid = $nv_Request->get_int('exclude', 'post', 0);

	if ($uid == $user_info['userid']) {
        die($lang_module['note_remove_leader']);
    }

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

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('TEMPLATE', $global_config['module_theme']);
$xtpl->assign('MODULE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE);
$xtpl->assign('OP', $op);

// Danh sach thanh vien
if (sizeof($array_op) == 2 and $array_op[0] == 'groups' and $array_op[1]) {
    $group_id = $array_op[1];
    if (! isset($groupsList[$group_id]) or ! ($group_id < 4 or $group_id > 9)) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }

	// Kiem tra lai quyen truong nhom
	$count = $db->query( 'SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id=' . $group_id . ' AND is_leader=1 AND userid=' . $user_info['userid'] )->fetchColumn();
	if ($count > 0) {
	    $filtersql = ' userid NOT IN (SELECT userid FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id=' . $group_id . ')';
	    if ($groupsList[$group_id]['idsite'] != $global_config['idsite'] and $groupsList[$group_id]['idsite'] == 0) {
	        $filtersql .= ' AND idsite=' . $global_config['idsite'];
	    }
	    $xtpl->assign('FILTERSQL', $crypt->encrypt($filtersql, NV_CHECK_SESSION));
	    $xtpl->assign('GID', $group_id);
		$xtpl->assign('MIN_SEARCH', sprintf($lang_module['min_search'], NV_MIN_SEARCH_LENGTH));

	    if ($group_id > 9) {
	    	if($groupsList[$group_id]['config']['access_groups_add'] != 0){
	    		$xtpl->parse('userlist.tools.addUserGroup');
	    	}
			if($groupsList[$group_id]['config']['access_addus'] != 0){
	    		$xtpl->parse('userlist.tools.add_user');
	    	}
			if($groupsList[$group_id]['config']['access_waiting'] != 0){
	    		$xtpl->parse('userlist.tools.user_waiting');
	    	}
			if($groupsList[$group_id]['config']['access_addus'] != 0 or $groupsList[$group_id]['config']['access_groups_add'] != 0 or $groupsList[$group_id]['config']['access_waiting'] != 0){
	    		$xtpl->parse('userlist.tools');
	    	}
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
            $stt=1;
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

						//kiểm tra thành viên có phải là admin k.
						$result_admin = $db->query('SELECT admin_id FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id = ' . $_userid );

						if (! $row_admin = $result_admin->fetch()) {
							if ($groupsList[$group_id]['config']['access_editus']) {
								$xtpl->assign('LINK_EDIT', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=editinfo/' . $group_id . '/' . $row['userid'], true));
		                		$xtpl->parse('listUsers.' . $_type . '.loop.tools.edituser');
							}

							$count = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $_userid )->rowCount();

							if ($groupsList[$group_id]['config']['access_delus'] and $count == 1) {
		                		$xtpl->parse('listUsers.' . $_type . '.loop.tools.deluser');
							}
						}

                		$xtpl->parse('listUsers.' . $_type . '.loop.tools');
					}
                }
                $xtpl->parse('listUsers.' . $_type . '.loop');
				$stt++;
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