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

    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput($respon);
    }

    $q = $nv_Request->get_title('q', 'post', '');
    $page = $nv_Request->get_page('page', 'post', 1);
    $per_page = 20;

    if (nv_strlen($q) < 2) {
        nv_jsonOutput($respon);
    }

    $stmt = $db->prepare('SELECT COUNT(id) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE (alias LIKE :alias OR pseudonym LIKE :pseudonym)');
    $stmt->bindValue(':alias', '%' . $q . '%', PDO::PARAM_STR);
    $stmt->bindValue(':pseudonym', '%' . $q . '%', PDO::PARAM_STR);
    $stmt->execute();
    $num_items = $stmt->fetchColumn();

    $offset = ($page - 1) * $per_page;
    $stmt = $db->prepare('SELECT id, pseudonym FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE (alias LIKE :alias OR pseudonym LIKE :pseudonym) ORDER BY alias ASC LIMIT :limit OFFSET :offset');
    $stmt->bindValue(':alias', '%' . $q . '%', PDO::PARAM_STR);
    $stmt->bindValue(':pseudonym', '%' . $q . '%', PDO::PARAM_STR);
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    while ($_row = $stmt->fetch()) {
        $respon['results'][] = [
            'id' => $_row['id'],
            'text' => $_row['pseudonym']
        ];
    }
    $stmt->closeCursor();

    $respon['pagination']['more'] = ($page * $per_page) < $num_items;
    nv_jsonOutput($respon);
}

// Xoa tac gia
if ($nv_Request->isset_request('authordel', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $aid = $nv_Request->get_int('aid', 'post', 0);
    $stmt = $db->prepare('SELECT id, pseudonym FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id = :id');
    $stmt->bindValue(':id', $aid, PDO::PARAM_INT);
    $stmt->execute();
    $author = $stmt->fetch();
    $stmt->closeCursor();

    if (empty($author) or $aid == $my_author_detail['id']) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('author_unspecified_error')
        ]);
    }

    $stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist WHERE aid = :aid');
    $stmt->bindValue(':aid', $aid, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id = :id');
    $stmt->bindValue(':id', $aid, PDO::PARAM_INT);
    $stmt->execute();

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_author', $author['pseudonym'], $admin_info['userid']);
    $nv_Cache->delMod($module_name);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => ''
    ]);
}

// Vo hieu/Kich hoat tac gia
if ($nv_Request->isset_request('changeStatus', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $aid = $nv_Request->get_int('aid', 'post', 0);
    $stmt = $db->prepare('SELECT id, active, pseudonym FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id = :id');
    $stmt->bindValue(':id', $aid, PDO::PARAM_INT);
    $stmt->execute();
    $author = $stmt->fetch();
    $stmt->closeCursor();

    if (empty($author)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('author_unspecified_error')
        ]);
    }

    $status = empty($author['active']) ? 1 : 0;
    $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_author SET active = :active, edit_time = :edit_time WHERE id = :id');
    $stmt->bindValue(':active', $status, PDO::PARAM_INT);
    $stmt->bindValue(':edit_time', NV_CURRENTTIME, PDO::PARAM_INT);
    $stmt->bindValue(':id', $aid, PDO::PARAM_INT);
    $stmt->execute();

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

    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
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

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . ' WHERE ' . $where);
    $stmt->bindValue(':username', $keyword, PDO::PARAM_STR);
    $stmt->bindValue(':email', $keyword, PDO::PARAM_STR);
    $stmt->bindValue(':first_name', $keyword, PDO::PARAM_STR);
    $stmt->bindValue(':last_name', $keyword, PDO::PARAM_STR);
    $stmt->execute();
    $respon['total_count'] = (int) $stmt->fetchColumn();

    $offset = ($page - 1) * $per_page;
    $stmt = $db->prepare('SELECT userid, username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE ' . $where . ' ORDER BY username ASC LIMIT :limit OFFSET :offset');
    $stmt->bindValue(':username', $keyword, PDO::PARAM_STR);
    $stmt->bindValue(':email', $keyword, PDO::PARAM_STR);
    $stmt->bindValue(':first_name', $keyword, PDO::PARAM_STR);
    $stmt->bindValue(':last_name', $keyword, PDO::PARAM_STR);
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    while ($_row = $stmt->fetch()) {
        $respon['results'][] = [
            'id' => $_row['userid'],
            'title' => $_row['username'],
            'text' => $_row['username']
        ];
    }
    $stmt->closeCursor();

    $respon['pagination']['more'] = ($page * $per_page) < $respon['total_count'];

    nv_jsonOutput($respon);
}

// Them/Sua tac gia
if ($nv_Request->isset_request('save', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $aid = $nv_Request->get_int('aid', 'post', 0);
    $pseudonym = $nv_Request->get_title('pseudonym', 'post', '');
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

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id != :id AND uid = :uid');
    $stmt->bindValue(':id', $aid, PDO::PARAM_INT);
    $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
    $stmt->execute();
    $is_exists = $stmt->fetchColumn();

    if (!empty($is_exists)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'uid',
            'mess' => $nv_Lang->getModule('author_uid_error')
        ]);
    }

    $image_old = '';
    if ($aid) {
        $stmt = $db->prepare('SELECT image FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id = :id');
        $stmt->bindValue(':id', $aid, PDO::PARAM_INT);
        $stmt->execute();
        $image_old = $stmt->fetchColumn();
    }

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
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id != :id AND image = :image');
        $stmt->bindValue(':id', $aid, PDO::PARAM_INT);
        $stmt->bindValue(':image', basename($image_old), PDO::PARAM_STR);
        $stmt->execute();
        $_count = $stmt->fetchColumn();

        if (empty($_count)) {
            @unlink(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/authors/' . $image_old);
            @unlink(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/authors/' . $image_old);

            $stmt = $db->prepare('SELECT did FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir WHERE dirname = :dirname');
            $stmt->bindValue(':dirname', dirname(NV_UPLOADS_DIR . '/' . $module_upload . '/authors/' . $image_old), PDO::PARAM_STR);
            $stmt->execute();
            $_did = $stmt->fetchColumn();

            $stmt = $db->prepare('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = :did AND title = :title');
            $stmt->bindValue(':did', $_did, PDO::PARAM_INT);
            $stmt->bindValue(':title', basename($image_old), PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    $description = $nv_Request->get_string('description', 'post', '');
    $description = nv_nl2br(nv_htmlspecialchars(strip_tags($description)), '<br />');

    if ($aid == 0) {
        $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_author (uid, alias, pseudonym, image, description, add_time) VALUES (' . $uid . ', :alias, :pseudonym, :image, :description, ' . NV_CURRENTTIME . ')');
        $stmt->bindValue(':alias', $alias, PDO::PARAM_STR);
        $stmt->bindValue(':pseudonym', $pseudonym, PDO::PARAM_STR);
        $stmt->bindValue(':image', $image, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->execute();
        if ($db->lastInsertId()) {
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
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_author SET uid = :uid, alias = :alias, pseudonym = :pseudonym, image = :image, description = :description, edit_time = :edit_time WHERE id = :id');
        $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
        $stmt->bindValue(':alias', $alias, PDO::PARAM_STR);
        $stmt->bindValue(':pseudonym', $pseudonym, PDO::PARAM_STR);
        $stmt->bindValue(':image', $image, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':edit_time', NV_CURRENTTIME, PDO::PARAM_INT);
        $stmt->bindValue(':id', $aid, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist SET alias = :alias, pseudonym = :pseudonym WHERE aid = :aid');
            $stmt->bindValue(':alias', $alias, PDO::PARAM_STR);
            $stmt->bindValue(':pseudonym', $pseudonym, PDO::PARAM_STR);
            $stmt->bindValue(':aid', $aid, PDO::PARAM_INT);
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

$num = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author')->fetchColumn();
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=authors';
$num_items = ($num > 1) ? $num : 1;
$per_page = 20;
$page = $nv_Request->get_page('page', 'get', 1);
$authors = [];
$uids = [];
if ($num) {
    $offset = ($page - 1) * $per_page;
    $stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author ORDER BY alias LIMIT :limit OFFSET :offset');
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $authors[] = $row;
        $uids[] = $row['uid'];
    }
    $stmt->closeCursor();
}

if (!empty($uids)) {
    $uids_str = implode(',', array_map('intval', $uids));
    $result = $db->query('SELECT userid, username, email, md5username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN (' . $uids_str . ')');
    $uids = [];
    while ($_row = $result->fetch()) {
        $uids[$_row['userid']] = [
            'username' => $_row['username'],
            'email' => $_row['email'],
            'md5username' => $_row['md5username']
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
        $stmt = $db->prepare('SELECT uid, pseudonym, image, description FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id = :id');
        $stmt->bindValue(':id', $item['aid'], PDO::PARAM_INT);
        $stmt->execute();
        $row_author = $stmt->fetch();
        $stmt->closeCursor();

        if (empty($row_author)) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        }

        $item['uid'] = $row_author['uid'];
        $item['pseudonym'] = $row_author['pseudonym'];
        $item['image'] = $row_author['image'];
        $item['description'] = $row_author['description'];

        $stmt = $db->prepare('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = :userid');
        $stmt->bindValue(':userid', $item['uid'], PDO::PARAM_INT);
        $stmt->execute();
        $item['u_account'] = $stmt->fetchColumn();

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
            'newslist_link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;q=' . urlencode($row['alias']) . '&amp;stype=author&amp;checkss=' . csrf_create($csrf_key),
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
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('ROWS', $rows);
$tpl->assign('ITEM', $item);
$tpl->assign('IS_EDIT', $is_edit);
$tpl->assign('CAN_CHANGE_UID', $can_change_uid);
$tpl->assign('PAGINATION', $pagination);

$contents = $tpl->fetch('authors.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
