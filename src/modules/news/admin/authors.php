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

$page_title = $nv_Lang->getModule('author_manage');
$my_author_detail = my_author_detail($admin_info['userid']);

// Tìm tác giả thuộc quyền quản lý qua ajax
if ($nv_Request->isset_request('searchAjax', 'post')) {
    $respon = [
        'results' => [],
        'pagination' => [
            'more' => false
        ]
    ];

    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key_author)) {
        nv_jsonOutput($respon);
    }

    $q = $nv_Request->get_title('q', 'post', '');
    $page = $nv_Request->get_page('page', 'post', 1);
    $per_page = 20;

    if (nv_strlen($q) < 2) {
        nv_jsonOutput($respon);
    }

    $db_slave->sqlreset()
        ->select('COUNT(id)')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_author')
        ->where('(alias LIKE :alias OR pseudonym LIKE :pseudonym)');
    $sth = $db_slave->prepare($db_slave->sql());
    $sth->bindValue(':alias', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':pseudonym', '%' . $q . '%', PDO::PARAM_STR);
    $sth->execute();
    $num_items = $sth->fetchColumn();
    $sth->closeCursor();

    $db_slave->select('id, pseudonym')->order('alias ASC')->limit($per_page)->offset(($page - 1) * $per_page);

    $sth = $db_slave->prepare($db_slave->sql());
    $sth->bindValue(':alias', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':pseudonym', '%' . $q . '%', PDO::PARAM_STR);
    $sth->execute();

    while ($_scratch = $sth->fetch(3)) {
        [$id, $pseudonym] = $_scratch;
        unset($_scratch);
        $respon['results'][] = [
            'id' => $id,
            'text' => $pseudonym
        ];
    }
    $respon['pagination']['more'] = ($page * $per_page) < $num_items;
    nv_jsonOutput($respon);
}

// Xoa tac gia
if ($nv_Request->isset_request('authordel', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key_author)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $aid = $nv_Request->get_int('aid', 'post', 0);
    $author = $db->query('SELECT id, pseudonym FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id=' . $aid)->fetch();
    if (empty($author) or $aid == $my_author_detail['id']) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('author_unspecified_error')
        ]);
    }

    $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist WHERE aid=' . $aid);
    $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id=' . $aid);
    $db->query('OPTIMIZE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist');
    $db->query('OPTIMIZE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_author');

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_author', $author['pseudonym'], $admin_info['userid']);
    $nv_Cache->delMod($module_name);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => ''
    ]);
}

// Vo hieu/Kich hoat tac gia
if ($nv_Request->isset_request('changeStatus', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key_author)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $aid = $nv_Request->get_int('aid', 'post', 0);
    $author = $db->query('SELECT id, active, pseudonym FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id =' . $aid)->fetch();
    if (empty($author)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('author_unspecified_error')
        ]);
    }

    $status = empty($author['active']) ? 1 : 0;
    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_author SET active=' . $status . ', edit_time=' . NV_CURRENTTIME . ' WHERE id=' . $aid);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_change_author_status', 'id ' . $aid . ': ' . $author['pseudonym'], $admin_info['userid']);
    $nv_Cache->delMod($module_name);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => '',
        'active' => $status
    ]);
}

// Xuất ajax tim kiem thanh vien
if ($nv_Request->isset_request('get_account_json', 'post, get')) {
    $respon = [
        'results' => [],
        'pagination' => [
            'more' => false
        ],
        'total_count' => 0
    ];

    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key_author)) {
        nv_jsonOutput($respon);
    }

    $q = $nv_Request->get_title('q', 'post, get', '');
    $q = str_replace('+', ' ', $q);
    $q = nv_htmlspecialchars($q);
    $page = $nv_Request->get_page('page', 'post, get', 1);
    $per_page = 30;

    if (nv_strlen($q) < 2) {
        nv_jsonOutput($respon);
    }

    $keyword = '%' . $q . '%';
    $where = '(username LIKE :username OR email LIKE :email OR first_name LIKE :first_name OR last_name LIKE :last_name) AND userid NOT IN (SELECT uid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author)';

    $db->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_USERS_GLOBALTABLE)
        ->where($where);
    $sth = $db->prepare($db->sql());
    $sth->bindValue(':username', $keyword, PDO::PARAM_STR);
    $sth->bindValue(':email', $keyword, PDO::PARAM_STR);
    $sth->bindValue(':first_name', $keyword, PDO::PARAM_STR);
    $sth->bindValue(':last_name', $keyword, PDO::PARAM_STR);
    $sth->execute();
    $respon['total_count'] = (int) $sth->fetchColumn();
    $sth->closeCursor();

    $db->select('userid, username')
        ->order('username ASC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $sth = $db->prepare($db->sql());
    $sth->bindValue(':username', $keyword, PDO::PARAM_STR);
    $sth->bindValue(':email', $keyword, PDO::PARAM_STR);
    $sth->bindValue(':first_name', $keyword, PDO::PARAM_STR);
    $sth->bindValue(':last_name', $keyword, PDO::PARAM_STR);
    $sth->execute();

    while ($_scratch = $sth->fetch(3)) {
        [$userid, $username] = $_scratch;
        unset($_scratch);
        $respon['results'][] = [
            'id' => $userid,
            'title' => $username,
            'text' => $username
        ];
    }
    $sth->closeCursor();
    $respon['pagination']['more'] = ($page * $per_page) < $respon['total_count'];

    nv_jsonOutput($respon);
}

// Them/Sua tac gia
if ($nv_Request->isset_request('save', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key_author)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

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
            'mess' => $nv_Lang->getModule('author_pseudonym_empty')
        ]);
    }

    $alias = get_pseudonym_alias($pseudonym, $aid);
    if (!$alias) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'pseudonym',
            'mess' => $nv_Lang->getModule('author_pseudonym_error')
        ]);
    }

    if (empty($uid)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'uid',
            'mess' => $nv_Lang->getModule('author_uid_empty')
        ]);
    }

    $is_exists = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id !=' . $aid . ' AND uid = ' . $uid)->fetchColumn();
    if (!empty($is_exists)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'uid',
            'mess' => $nv_Lang->getModule('author_uid_error')
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
            $nv_Cache->delMod($module_name);
            nv_jsonOutput([
                'status' => 'OK',
                'mess' => $nv_Lang->getGlobal('save_success'),
                'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true)
            ]);
        } else {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('author_unspecified_error')
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
            $nv_Cache->delMod($module_name);
            nv_jsonOutput([
                'status' => 'OK',
                'mess' => $nv_Lang->getGlobal('save_success'),
                'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true)
            ]);
        } else {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('author_unspecified_error')
            ]);
        }
    }
}

$num = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author')->fetchColumn();
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=authors';
$num_items = ($num > 1) ? $num : 1;
$per_page = 20;
$page = $nv_Request->get_page('page', 'get', 1);
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
    while ($_scratch = $result->fetch(3)) {
        [$userid, $username, $email, $md5username] = $_scratch;
        unset($_scratch);
        $uids[$userid] = [
            'username' => $username,
            'email' => $email,
            'md5username' => $md5username
        ];
    }
}

$item = [
    'aid' => 0,
    'pseudonym' => '',
    'uid' => 0,
    'u_account' => '',
    'image' => '',
    'description' => ''
];
$is_edit = false;
$can_change_uid = true;

if ($nv_Request->isset_request('aid', 'get')) {
    $item['aid'] = $nv_Request->get_int('aid', 'get', 0);
    if ($item['aid']) {
        [$item['uid'], $item['pseudonym'], $item['image'], $item['description']] = $db->query('SELECT uid, pseudonym, image, description FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author where id=' . $item['aid'])->fetch(3);
        if (empty($item['uid'])) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        }

        $item['u_account'] = $db->query('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid =' . $item['uid'])->fetchColumn();
        if (!empty($item['image'])) {
            $item['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/authors/' . $item['image'];
        }
        if (!empty($item['description'])) {
            $item['description'] = nv_htmlspecialchars(nv_br2nl($item['description']));
        }
        $is_edit = true;
    }
}

$rows = [];
if (!empty($authors)) {
    foreach ($authors as $row) {
        $user_info = $uids[$row['uid']] ?? [
            'username' => '',
            'email' => '',
            'md5username' => ''
        ];

        $rows[] = [
            'id' => (int) $row['id'],
            'pseudonym' => $row['pseudonym'],
            'alias' => $row['alias'],
            'numnews' => (int) $row['numnews'],
            'account' => $user_info['username'],
            'email' => $user_info['email'],
            'newslist_link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;q=' . urlencode($row['alias']) . '&amp;stype=author&amp;checkss=' . NV_CHECK_SESSION,
            'has_news' => !empty($row['numnews']),
            'account_link' => !empty($user_info['username']) ? NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=memberlist/' . change_alias($user_info['username']) . '-' . $user_info['md5username'] : '',
            'add_time_format' => nv_date_format(1, $row['add_time']),
            'is_active' => !empty($row['active']),
            'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;aid=' . $row['id'],
            'can_delete' => $row['id'] != $my_author_detail['id']
        ];
    }
}

$can_change_uid = ($item['aid'] != $my_author_detail['id']);

$pagination = nv_generate_page($base_url, $num_items, $per_page, $page);

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('authors.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('MODULE_UPLOAD', $module_upload);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key_author));
$tpl->assign('ROWS', $rows);
$tpl->assign('ITEM', $item);
$tpl->assign('IS_EDIT', $is_edit);
$tpl->assign('CAN_CHANGE_UID', $can_change_uid);
$tpl->assign('PAGINATION', $pagination);

$contents = $tpl->fetch('authors.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
