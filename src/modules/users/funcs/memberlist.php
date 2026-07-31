<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

$page_title = $module_info['funcs'][$op]['func_site_title'];
$key_words = $module_info['keywords'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

if (!nv_user_in_groups($global_config['whoviewuser'])) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$array_mod_title[] = [
    'catid' => 0,
    'title' => $nv_Lang->getModule('listusers'),
    'link' => $page_url
];

// Chi tiết thành viên
if (isset($array_op[1]) and !empty($array_op[1])) {
    $page_url .= '/' . $array_op[1];
    $full = true;
    if (isset($array_op[2]) and $array_op[2] == 's') {
        $page_url .= '/s';
        $full = false;
    }

    $md5 = '';
    unset($matches);
    if (preg_match('/^(.*)\-([a-z0-9]{32})$/', $array_op[1], $matches)) {
        $md5 = $matches[2];
    }
    if (empty($md5)) {
        nv_error404();
    }

    $stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . ' WHERE md5username = :md5' . (defined('NV_IS_ADMIN') ? '' : ' AND active=1'));
    $stmt->bindParam(':md5', $md5, PDO::PARAM_STR);
    $stmt->execute();
    $item = $stmt->fetch();
    if (empty($item)) {
        nv_error404();
    }
    if (change_alias($item['username']) != $matches[1]) {
        nv_error404();
    }

    $array_mod_title[] = [
        'catid' => 0,
        'title' => $item['username'],
        'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . change_alias($item['username']) . '-' . $item['md5username']
    ];
    $array_field_config = nv_get_users_field_config();

    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_info WHERE userid=' . $item['userid'];
    $result = $db->query($sql);
    $custom_fields = $result->fetch();

    // Kiểm tra quyền sửa, xóa user của admin
    $item['is_admin'] = false;
    $item['allow_edit'] = false;
    $item['allow_delete'] = false;
    $item['link_edit'] = '';
    $item['link_delete'] = '';
    $item['link_delete_callback'] = '';

    if (defined('NV_IS_MODADMIN') and ($global_config['idsite'] == 0 or $item['idsite'] == $global_config['idsite'])) {
        $access_admin = unserialize($global_users_config['access_admin'], NV_UNSERIALIZE_SAFE);
        $check_admin = $db->query('SELECT admin_id, lev FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $item['userid'])->fetch();

        if (isset($access_admin['access_editus'][$admin_info['level']]) and $access_admin['access_editus'][$admin_info['level']] == 1 and (empty($check_admin) or $admin_info['userid'] == $item['userid'] or defined('NV_IS_GODADMIN') or (defined('NV_IS_SPADMIN') and !($check_admin['lev'] == 1 or $check_admin['lev'] == 2)))) {
            $item['is_admin'] = true;
            $item['allow_edit'] = true;
            $item['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;userid=' . $item['userid'];
        }
        if (isset($access_admin['access_delus'][$admin_info['level']]) and $access_admin['access_delus'][$admin_info['level']] == 1 and empty($check_admin)) {
            $item['is_admin'] = true;
            $item['allow_delete'] = true;
            $item['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=del&amp;nocache=' . NV_CURRENTTIME;
            $item['link_delete_callback'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
        }

        unset($access_admin, $check_admin);
    }

    $item['full_name'] = nv_show_name_user($item['first_name'], $item['last_name']);

    $contents = nv_memberslist_detail_theme($item, $array_field_config, $custom_fields, $full);
    $canonicalUrl = getCanonicalUrl($page_url);

    if ($nv_Request->get_int('nv_ajax', 'post', 0) == 1) {
        exit(nv_url_rewrite($contents, true));
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents, $full);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Danh sách thành viên
$orderby = $nv_Request->get_string('orderby', 'get', 'username');
$sortby = $nv_Request->get_string('sortby', 'get', 'ASC');
$page = $nv_Request->get_page('page', 'get', 1);

if (!($orderby == 'username' and $sortby == 'ASC')) {
    $page_url .= '&amp;orderby=' . $orderby . '&amp;sortby=' . $sortby;
}

if ((!empty($orderby) and !in_array($orderby, ['username', 'gender', 'regdate'], true)) or (!empty($sortby) and !in_array($sortby, ['DESC', 'ASC'], true))) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$base_url = $page_url;

$per_page = 25;
$array_order = [
    'username' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&orderby=username&sortby=' . $sortby,
    'gender' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&orderby=gender&sortby=' . $sortby,
    'regdate' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&orderby=regdate&sortby=' . $sortby
];

foreach ($array_order as $key => $link) {
    if ($orderby == $key) {
        $sortby_new = ($sortby == 'DESC') ? 'ASC' : 'DESC';
        $array_order_new[$key] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&orderby=' . $key . '&sortby=' . $sortby_new;
    } else {
        $array_order_new[$key] = $link;
    }
}

$where = defined('NV_IS_ADMIN') ? '' : 'WHERE active=1';
$num_items = $db->query('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . ' ' . $where)->fetchColumn();

$urlappend = '&page=';
betweenURLs($page, ceil($num_items / $per_page), $base_url, $urlappend, $prevPage, $nextPage);

$stmt = $db->prepare('SELECT userid, username, md5username, first_name, last_name, photo, gender, regdate FROM ' . NV_MOD_TABLE . ' ' . $where . ' ORDER BY ' . $orderby . ' ' . $sortby . ' LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
$stmt->execute();

$users_array = [];

while ($item = $stmt->fetch()) {
    $item['full_name'] = nv_show_name_user($item['first_name'], $item['last_name']);
    if (!empty($item['photo']) and file_exists(NV_ROOTDIR . '/' . $item['photo'])) {
        $item['photo'] = NV_BASE_SITEURL . $item['photo'];
        $item['avata'] = $item['photo'];
    } else {
        $item['photo'] = NV_STATIC_URL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no_avatar.png';
        $item['avata'] = '';
    }
    $item['avatar_letters'] = nv_user_avatar_letters($item['first_name'], $item['last_name'], $item['username']);
    $item['avatar_color'] = nv_user_avatar_color($item['username']);

    $item['regdate'] = nv_date_format(1, $item['regdate']);
    $item['user'] = change_alias($item['username']) . '-' . $item['md5username'];
    $item['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=memberlist/' . $item['user'];
    $item['gender'] = ($item['gender'] == 'M') ? $nv_Lang->getModule('male') : ($item['gender'] == 'F' ? $nv_Lang->getModule('female') : '');

    $users_array[$item['userid']] = $item;
}
$stmt->closeCursor();

if (!empty($orderby)) {
    $page_title .= ' ' . $nv_Lang->getModule('listusers_sort_by', $nv_Lang->getModule('listusers_sort_by_' . $orderby), $nv_Lang->getModule('listusers_order_' . $sortby));
}
if ($page > 1) {
    $page_title .= NV_TITLEBAR_DEFIS . $nv_Lang->getModule('page', ceil($page / $per_page));
    $page_url .= '&page=' . $page;
}

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

unset($result, $item);

$contents = nv_memberslist_theme($users_array, $orderby, $sortby, $array_order_new, $generate_page);
$canonicalUrl = getCanonicalUrl($page_url);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
