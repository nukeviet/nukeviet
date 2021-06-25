<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$my_author_detail = my_author_detail($admin_info['userid']);

// searchAjax
if ($nv_Request->isset_request('searchAjax', 'get')) {
    $q = $nv_Request->get_title('term', 'get', '', 1);
    if (empty($q)) {
        return;
    }
    $aids = $nv_Request->get_title('aids', 'get', '');
    $aids = preg_replace("/[^0-9\,]/", '', $aids);

    $where = '(alias LIKE :alias OR pseudonym LIKE :pseudonym)';
    if (!empty($aids)) {
        $where .= ' AND id NOT IN (' . $aids . ')';
    }

    $db_slave->sqlreset()
        ->select('id,pseudonym')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_author')
        ->where($where)
        ->order('alias ASC')
        ->limit(50);
    $sth = $db_slave->prepare($db_slave->sql());
    $sth->bindValue(':alias', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':pseudonym', '%' . $q . '%', PDO::PARAM_STR);
    $sth->execute();

    $array_data = [];
    while (list($id, $pseudonym) = $sth->fetch(3)) {
        $array_data[$id] = $pseudonym;
    }

    nv_jsonOutput($array_data);
}

// Xoa tac gia
if ($nv_Request->isset_request('authordel', 'post')) {
    $aid = $nv_Request->get_int('aid', 'post', 0);
    if ($aid != $my_author_detail['id']) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist WHERE aid=' . $aid);
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id=' . $aid);
        $db->query('OPTIMIZE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist');
        $db->query('OPTIMIZE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_author');
    }
    echo 'OK';
    exit(0);
}

// Vo hieu/Kich hoat tac gia
if ($nv_Request->isset_request('changeStatus', 'post')) {
    $aid = $nv_Request->get_int('aid', 'post', 0);
    $status = $db->query('SELECT active FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id =' . $aid)->fetchColumn();
    $status = $status ? 0 : 1;
    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_author SET active=' . $status . ', edit_time=' . NV_CURRENTTIME . ' WHERE id=' . $aid);
    echo 'OK';
    exit(0);
}

// Xuất ajax tim kiem thanh vien
if ($nv_Request->isset_request('get_account_json', 'post, get')) {
    $q = $nv_Request->get_title('q', 'post, get', '');
    $q = str_replace('+', ' ', $q);
    $q = nv_htmlspecialchars($q);
    $dbkeyhtml = $db->dblikeescape($q);

    $page = $nv_Request->get_int('page', 'post, get', 1);
    $array_data = [];

    $where = "(username LIKE '%" . $dbkeyhtml . "%' OR email LIKE '%" . $dbkeyhtml . "%' OR first_name like '%" . $dbkeyhtml . "%' OR last_name like '%" . $dbkeyhtml . "%') AND userid NOT IN (SELECT uid FROM " . NV_PREFIXLANG . '_' . $module_data . '_author)';

    $db->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_USERS_GLOBALTABLE)
        ->where($where);
    $array_data['total_count'] = $db->query($db->sql())
        ->fetchColumn();

    $db->select('userid, username')
        ->order('username ASC')
        ->limit(30)
        ->offset(($page - 1) * 30);
    $result = $db->query($db->sql());
    $array_data['results'] = [];
    while (list($userid, $username) = $result->fetch(3)) {
        $array_data['results'][] = [
            'id' => $userid,
            'title' => $username
        ];
    }

    nv_jsonOutput($array_data);
}

// Them/Sua tac gia
if ($nv_Request->isset_request('save', 'post')) {
    $aid = $nv_Request->get_int('aid', 'post', 0);
    $pseudonym = $nv_Request->get_title('pseudonym', 'post', '', 1);
    $uid = $nv_Request->get_int('uid', 'post', 0);
    if ($aid == $my_author_detail['id']) {
        $uid = $my_author_detail['uid'];
    }

    if (empty($pseudonym)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'pseudonym',
            'mess' => $lang_module['author_pseudonym_empty']
        ]);
    }

    $alias = get_pseudonym_alias($pseudonym, $aid);
    if (!$alias) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'pseudonym',
            'mess' => $lang_module['author_pseudonym_error']
        ]);
    }

    if (empty($uid)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'uid',
            'mess' => $lang_module['author_uid_empty']
        ]);
    }

    $is_exists = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id !=' . $aid . ' AND uid = ' . $uid)->fetchColumn();
    if (!empty($is_exists)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'uid',
            'mess' => $lang_module['author_uid_error']
        ]);
    }

    $image_old = $aid ? $db->query('SELECT image FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id =' . $aid)->fetchColumn() : '';

    $image = $nv_Request->get_string('image', 'post', '');
    if (!nv_is_url($image) and nv_is_file($image, NV_UPLOADS_DIR . '/' . $module_upload . '/authors')) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/authors/');
        $image = substr($image, $lu);
    } elseif (!nv_is_url($image) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/authors/' . $image_old)) {
        $image = $image_old;
    } else {
        $image = '';
    }

    if (($image != $image_old) and !empty($image_old)) {
        $_count = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id != ' . $aid . ' AND image =' . $db->quote(basename($image_old)))
            ->fetchColumn();
        if (empty($_count)) {
            @unlink(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/authors/' . $image_old);
            @unlink(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/authors/' . $image_old);

            $_did = $db->query('SELECT did FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir WHERE dirname=' . $db->quote(dirname(NV_UPLOADS_DIR . '/' . $module_upload . '/authors/' . $image_old)))
                ->fetchColumn();
            $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = ' . $_did . ' AND title=' . $db->quote(basename($image_old)));
        }
    }

    $description = $nv_Request->get_string('description', 'post', '');
    $description = nv_nl2br(nv_htmlspecialchars(strip_tags($description)), '<br />');

    if ($aid == 0) {
        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_author (uid, alias, pseudonym, image, description, add_time) VALUES ( ' . $uid . ', :alias, :pseudonym, :image, :description, ' . NV_CURRENTTIME . ')';
        $data_insert = [];
        $data_insert['alias'] = $alias;
        $data_insert['pseudonym'] = $pseudonym;
        $data_insert['image'] = $image;
        $data_insert['description'] = $description;

        if ($db->insert_id($sql, 'id', $data_insert)) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_author', ' ', $admin_info['userid']);
            nv_jsonOutput([
                'status' => 'OK',
                'input' => '',
                'mess' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op
            ]);
        } else {
            nv_jsonOutput([
                'status' => 'error',
                'input' => '',
                'mess' => $lang_module['author_unspecified_error']
            ]);
        }
    } else {
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_author SET uid=' . $uid . ', alias= :alias, pseudonym = :pseudonym, image= :image, description= :description, edit_time=' . NV_CURRENTTIME . ' WHERE id =' . $aid);
        $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
        $stmt->bindParam(':pseudonym', $pseudonym, PDO::PARAM_STR);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        if ($stmt->execute()) {
            $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist SET alias= :alias, pseudonym = :pseudonym WHERE aid =' . $aid);
            $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
            $stmt->bindParam(':pseudonym', $pseudonym, PDO::PARAM_STR);
            $stmt->execute();

            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_author', 'id ' . $aid, $admin_info['userid']);
            nv_jsonOutput([
                'status' => 'OK',
                'input' => '',
                'mess' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op
            ]);
        } else {
            nv_jsonOutput([
                'status' => 'error',
                'input' => '',
                'mess' => $lang_module['author_unspecified_error']
            ]);
        }
    }
}

$num = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author')->fetchColumn();
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=authors';
$num_items = ($num > 1) ? $num : 1;
$per_page = 20;
$page = $nv_Request->get_int('page', 'get', 1);
$authors = [];
$uids = [];
if ($num) {
    $db_slave->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_author')
        ->order('alias')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $result = $db_slave->query($db_slave->sql());
    while ($row = $result->fetch()) {
        $authors[] = $row;
        $uids[] = $row['uid'];
    }
}

if (!empty($uids)) {
    $uids = implode(',', $uids);
    $db_slave->sqlreset()
        ->select('userid, username, email, md5username')
        ->from(NV_USERS_GLOBALTABLE)
        ->where('userid IN (' . $uids . ')');
    $result = $db_slave->query($db_slave->sql());
    $uids = [];
    while (list($userid, $username, $email, $md5username) = $result->fetch(3)) {
        $uids[$userid] = [
            'username' => $username,
            'email' => $email,
            'md5username' => $md5username
        ];
    }
}

$data = [
    'title' => $lang_module['add_author'],
    'aid' => 0,
    'pseudonym' => '',
    'uid' => 0,
    'u_account' => '',
    'image' => '',
    'description' => ''
];

if ($nv_Request->isset_request('aid', 'get')) {
    $data['aid'] = $nv_Request->get_int('aid', 'get', 0);
    if ($data['aid']) {
        list($data['uid'], $data['pseudonym'], $data['image'], $data['description']) = $db->query('SELECT uid, pseudonym, image, description FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author where id=' . $data['aid'])->fetch(3);
        if (empty($data['uid'])) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        }

        $data['title'] = $lang_module['edit_author'];
        $data['u_account'] = $db->query('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid =' . $data['uid'])->fetchColumn();
        if (!empty($data['image'])) {
            $data['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/authors/' . $data['image'];
        }
        if (!empty($data['description'])) {
            $data['description'] = nv_htmlspecialchars(nv_br2nl($data['description']));
        }
    }
}

$xtpl = new XTemplate('authors.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('OP', $op);
$xtpl->assign('DATA', $data);

if (!empty($authors)) {
    foreach ($authors as $row) {
        $row['newslist_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;q=' . urlencode($row['alias']) . '&amp;stype=author&amp;checkss=' . NV_CHECK_SESSION;
        $row['account'] = $uids[$row['uid']]['username'];
        $row['email'] = $uids[$row['uid']]['email'];
        $row['account_link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=memberlist/' . change_alias($uids[$row['uid']]['username']) . '-' . $uids[$row['uid']]['md5username'];
        $row['add_time_format'] = nv_date('d/m/Y', $row['add_time']);
        $row['status_sel'] = $row['active'] ? ' selected="selected"' : '';
        $row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;aid=' . $row['id'];
        $xtpl->assign('ROW', $row);

        if ($row['numnews']) {
            $xtpl->parse('main.authorlist.loop.newslist_link');
        } else {
            $xtpl->parse('main.authorlist.loop.newslist');
        }

        if ($row['id'] != $my_author_detail['id']) {
            $xtpl->parse('main.authorlist.loop.del_author');
        }
        $xtpl->parse('main.authorlist.loop');
    }

    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.authorlist.generate_page');
    }

    $xtpl->parse('main.authorlist');
}

if ($data['aid'] == $my_author_detail['id']) {
    $xtpl->parse('main.not_change_uid');
} else {
    if (!empty($data['uid'])) {
        $xtpl->parse('main.change_uid.uid');
    }
    $xtpl->parse('main.change_uid');
}

if (!empty($data['image'])) {
    $xtpl->parse('main.image');
}

if (!empty($data['aid'])) {
    $xtpl->parse('main.scroll');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['author_manage'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
