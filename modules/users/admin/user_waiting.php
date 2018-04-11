<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/05/2010
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

//Xoa thanh vien
if ($nv_Request->isset_request('del', 'post')) {
    $userid = $nv_Request->get_int('userid', 'post', 0);

    $sql = 'DELETE FROM ' . NV_MOD_TABLE . '_reg WHERE userid=' . $userid;
    if ($db->exec($sql)) {
        die('OK');
    }
    die('NO');
}

//Kich hoat thanh vien
if ($nv_Request->isset_request('act', 'get')) {
    $userid = $nv_Request->get_int('userid', 'get', 0);

    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_reg WHERE userid=' . $userid;
    $row = $db->query($sql)->fetch();
    if (empty($row)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    $sql = "INSERT INTO " . NV_MOD_TABLE . " (
        group_id, username, md5username, password, email, first_name, last_name, gender, photo, birthday, sig,
        regdate, question,
        answer, passlostkey, view_mail, remember, in_groups, active, checknum,
        last_login, last_ip, last_agent, last_openid, idsite, email_verification_time
    ) VALUES (
        :group_id,
        :username,
        :md5_username,
        :password,
        :email,
        :first_name,
        :last_name,
        :gender,
        '',
        :birthday,
        :sig,
        " . $row['regdate'] . ",
        :question,
        :answer,
        '', 0, 0, '', 1, '', 0, '', '', '', " . $global_config['idsite'] . ", -2
    )";

    $data_insert = array();
    $data_insert['group_id'] = (!empty($global_users_config['active_group_newusers']) ? 7 : 4);
    $data_insert['username'] = $row['username'];
    $data_insert['md5_username'] = nv_md5safe($row['username']);
    $data_insert['password'] = $row['password'];
    $data_insert['email'] = nv_strtolower($row['email']);
    $data_insert['first_name'] = $row['first_name'];
    $data_insert['last_name'] = $row['last_name'];
    $data_insert['gender'] = $row['gender'];
    $data_insert['birthday'] = $row['birthday'];
    $data_insert['sig'] = $row['sig'];
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

        $users_info = unserialize(nv_base64_decode($row['users_info']));
        $query_field = array();
        $query_field['userid'] = $userid;
        $result_field = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field ORDER BY fid ASC');
        while ($row_f = $result_field->fetch()) {
            if ($row_f['system'] == 1) continue;
            if ($row_f['field_type'] == 'number' or $row_f['field_type'] == 'date') {
                $default_value = floatval($row_f['default_value']);
            } else {
                $default_value = $db->quote($row_f['default_value']);
            }
            $query_field[$row_f['field']] = (isset($users_info[$row_f['field']])) ? $users_info[$row_f['field']] : $default_value;
        }

        if ($db->exec('INSERT INTO ' . NV_MOD_TABLE . '_info (' . implode(', ', array_keys($query_field)) . ') VALUES (' . implode(', ', array_values($query_field)) . ')')) {
            if (!empty($global_users_config['active_group_newusers'])) {
                nv_groups_add_user(7, $row['userid'], 1, $module_data);
            } else {
                $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers+1 WHERE group_id=4');
            }
            $db->query('DELETE FROM ' . NV_MOD_TABLE . '_reg WHERE userid=' . $row['userid']);

            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['active_users'], 'userid: ' . $userid . ' - username: ' . $row['username'], $admin_info['userid']);

            $full_name = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
            $subject = $lang_module['adduser_register'];
            $_url = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true);
            if (strpos($_url, NV_MY_DOMAIN) !== 0) {
                $_url = NV_MY_DOMAIN . $_url;
            }
            $message = sprintf($lang_module['adduser_register_info'], $full_name, $global_config['site_name'], $_url, $row['username']);
            @nv_sendmail($global_config['site_email'], $row['email'], $subject, $message);
        } else {
            $db->query('DELETE FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid);
        }
    }
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=user_waiting');
}

$page_title = $table_caption = $lang_module['member_wating'];

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting';

$methods = array(
    'userid' => array(
        'key' => 'userid',
        'sql' => 'userid',
        'value' => $lang_module['search_id'],
        'selected' => ''
    ),
    'username' => array(
        'key' => 'username',
        'sql' => 'username',
        'value' => $lang_module['search_account'],
        'selected' => ''
    ),
    'full_name' => array(
        'key' => 'full_name',
        'sql' => $global_config['name_show'] == 0 ? "concat(last_name,' ',first_name)" : "concat(first_name,' ',last_name)",
        'value' => $lang_module['search_name'],
        'selected' => ''
    ),
    'email' => array(
        'key' => 'email',
        'sql' => 'email',
        'value' => $lang_module['search_mail'],
        'selected' => ''
    )
);
$method = $nv_Request->isset_request('method', 'post') ? $nv_Request->get_string('method', 'post', '') : ($nv_Request->isset_request('method', 'get') ? urldecode($nv_Request->get_string('method', 'get', '')) : '');
$methodvalue = $nv_Request->isset_request('value', 'post') ? $nv_Request->get_string('value', 'post') : ($nv_Request->isset_request('value', 'get') ? urldecode($nv_Request->get_string('value', 'get', '')) : '');

$orders = array(
    'userid',
    'username',
    'full_name',
    'email',
    'regdate'
);
$orderby = $nv_Request->get_string('sortby', 'get', '');
$ordertype = $nv_Request->get_string('sorttype', 'get', '');
if ($ordertype != 'ASC') {
    $ordertype = 'DESC';
}

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_MOD_TABLE . '_reg');

if (!empty($method) and isset($methods[$method]) and !empty($methodvalue)) {
    $base_url .= '&amp;method=' . urlencode($method) . '&amp;value=' . urlencode($methodvalue);
    $methods[$method]['selected'] = ' selected="selected"';
    $table_caption = $lang_module['search_page_title'];

    $db->where($methods[$method]['sql'] . " LIKE '%" . $db->dblikeescape($methodvalue) . "%'");
}

$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 30;

$num_items = $db->query($db->sql())
    ->fetchColumn();

$db->select('*')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);

if (!empty($orderby) and in_array($orderby, $orders)) {
    $orderby_sql = $orderby != 'full_name' ? $orderby : ($global_config['name_show'] == 0 ? "concat(first_name,' ',last_name)" : "concat(last_name,' ',first_name)");
    $db->order($orderby_sql . ' ' . $ordertype);
    $base_url .= '&amp;sortby=' . $orderby . '&amp;sorttype=' . $ordertype;
}

$result = $db->query($db->sql());

$users_list = array();
while ($row = $result->fetch()) {
    $users_list[$row['userid']] = array(
        'userid' => $row['userid'],
        'username' => $row['username'],
        'full_name' => nv_show_name_user($row['first_name'], $row['last_name'], $row['username']),
        'email' => $row['email'],
        'regdate' => date('d/m/Y H:i', $row['regdate'])
    );
}

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$head_tds = array();
$head_tds['userid']['title'] = $lang_module['userid'];
$head_tds['userid']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=userid&amp;sorttype=ASC';
$head_tds['username']['title'] = $lang_module['account'];
$head_tds['username']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=username&amp;sorttype=ASC';
$head_tds['full_name']['title'] = $lang_module['name'];
$head_tds['full_name']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=full_name&amp;sorttype=ASC';
$head_tds['email']['title'] = $lang_module['email'];
$head_tds['email']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=email&amp;sorttype=ASC';
$head_tds['regdate']['title'] = $lang_module['register_date'];
$head_tds['regdate']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=regdate&amp;sorttype=ASC';

foreach ($orders as $order) {
    if ($orderby == $order and $ordertype == 'ASC') {
        $head_tds[$order]['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=' . $order . '&amp;sorttype=DESC';
        $head_tds[$order]['title'] .= ' &darr;';
    } elseif ($orderby == $order and $ordertype == 'DESC') {
        $head_tds[$order]['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;sortby=' . $order . '&amp;sorttype=ASC';
        $head_tds[$order]['title'] .= ' &uarr;';
    }
}

$xtpl = new XTemplate('user_waitting.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting');
$xtpl->assign('SORTURL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
$xtpl->assign('SEARCH_VALUE', nv_htmlspecialchars($methodvalue));
$xtpl->assign('TABLE_CAPTION', $table_caption);

if (defined('NV_IS_USER_FORUM')) {
    $xtpl->parse('main.is_forum');
}

foreach ($methods as $m) {
    $xtpl->assign('METHODS', $m);
    $xtpl->parse('main.method');
}

foreach ($head_tds as $head_td) {
    $xtpl->assign('HEAD_TD', $head_td);
    $xtpl->parse('main.head_td');
}

foreach ($users_list as $u) {
    $xtpl->assign('CONTENT_TD', $u);
    $xtpl->assign('ACTIVATE_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;act=1&amp;userid=' . $u['userid']);
    $xtpl->assign('EDIT_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_waiting&amp;del&amp;userid=' . $u['userid']);
    $xtpl->parse('main.xusers');
}

if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';