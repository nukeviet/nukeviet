<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (theme_file_exists($global_config['module_theme'] . '/modules/comment/tpl.php')) {
    require NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/comment/tpl.php';
} else {
    require NV_ROOTDIR . '/modules/comment/tpl.php';
}

/**
 * Đọc CSDL để lấy các row bình luận + phân trang
 *
 * @param string $module
 * @param string $area
 * @param int    $id
 * @param int    $page
 * @param int    $sortcomm
 * @param string $base_url
 * @return array
 */
function nv_comment_data($module, $area, $id, $page, $sortcomm, $base_url)
{
    global $db, $module_config;

    $per_page_comment = empty($module_config[$module]['perpagecomm']) ? 5 : $module_config[$module]['perpagecomm'];

    $_where = 'a.module = :module';
    $params = [':module' => [$module, PDO::PARAM_STR]];
    if ($area) {
        $_where .= ' AND a.area = :area';
        $params[':area'] = [$area, PDO::PARAM_STR];
    }
    $_where .= ' AND a.id = :id AND a.status = 1 AND a.pid = 0';
    $params[':id'] = [$id, PDO::PARAM_INT];

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_comment a LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' b ON a.userid = b.userid WHERE ' . $_where);
    foreach ($params as $p => $v) {
        $stmt->bindValue($p, $v[0], $v[1]);
    }
    $stmt->execute();
    $num_items = $stmt->fetchColumn();

    if (empty($num_items)) {
        return [
            'comment' => [],
            'page' => ''
        ];
    }

    $total = ceil($num_items / $per_page_comment);
    if ($page > 1 and $page > $total) {
        $page = 1;
    }

    $order = 'a.cid DESC';
    if ($sortcomm == 1) {
        $order = 'a.cid ASC';
    } elseif ($sortcomm == 2) {
        $order = 'a.likes DESC, a.cid DESC';
    }

    $emailcomm = $module_config[$module]['emailcomm'];
    $sql = 'SELECT a.cid, a.pid, a.content, a.attach, a.post_time, a.post_name, a.post_email, a.likes, a.dislikes, b.userid, b.username, b.md5username, b.email, b.first_name, b.last_name, b.photo, b.view_mail
            FROM ' . NV_PREFIXLANG . '_comment a
            LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' b ON a.userid = b.userid
            WHERE ' . $_where . '
            ORDER BY ' . $order . '
            LIMIT :limit OFFSET :offset';

    $stmt = $db->prepare($sql);
    foreach ($params as $p => $v) {
        $stmt->bindValue($p, $v[0], $v[1]);
    }
    $stmt->bindValue(':limit', $per_page_comment, PDO::PARAM_INT);
    $stmt->bindValue(':offset', ($page - 1) * $per_page_comment, PDO::PARAM_INT);
    $stmt->execute();

    $comment_list_id = $comment_array = [];

    while ($row = $stmt->fetch()) {
        $comment_list_id[] = $row['cid'];
        if ($row['userid'] > 0) {
            $row['post_email'] = $row['email'];
            $row['post_name'] = $row['first_name'];
        }
        $row['check_like'] = md5($row['cid'] . '_' . NV_CHECK_SESSION);
        $row['post_email'] = ($emailcomm) ? $row['post_email'] : '';
        if (!empty($row['attach'])) {
            $row['attach'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=comment&amp;' . NV_OP_VARIABLE . '=down&cid=' . $row['cid'] . '&amp;tokend=' . md5($row['cid'] . '_' . NV_CHECK_SESSION);
        }
        $row['user'] = !empty($row['username']) ? change_alias($row['username']) . '-' . $row['md5username'] : '';
        $comment_array[$row['cid']] = $row;
    }
    $stmt->closeCursor();

    if (!empty($comment_list_id)) {
        foreach ($comment_list_id as $cid) {
            $comment_array[$cid]['subcomment'] = nv_comment_get_reply($cid, $module, NV_CHECK_SESSION, $sortcomm);
        }
        $generate_page = nv_generate_page($base_url, $num_items, $per_page_comment, $page, true, true, 'nv_urldecode_ajax', 'showcomment');
    } else {
        $generate_page = '';
    }

    return [
        'comment' => $comment_array,
        'page' => $generate_page
    ];
}

/**
 * Lấy các reply của một bình luận
 *
 * @param int    $cid
 * @param string $module
 * @param string $session_id
 * @param int    $sortcomm
 * @return array
 */
function nv_comment_get_reply($cid, $module, $session_id, $sortcomm)
{
    global $db, $module_config;

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_comment WHERE pid = :pid AND status = 1');
    $stmt->bindValue(':pid', $cid, PDO::PARAM_INT);
    $stmt->execute();
    $num_items_sub = $stmt->fetchColumn();

    if (empty($num_items_sub)) {
        return [];
    }

    $data_reply_comment = [];
    $emailcomm = $module_config[$module]['emailcomm'];

    $order = 'a.cid DESC';
    if ($sortcomm == 1) {
        $order = 'a.cid ASC';
    } elseif ($sortcomm == 2) {
        $order = 'a.likes DESC, a.cid DESC';
    }

    $sql = 'SELECT a.cid, a.pid, a.content, a.attach, a.post_time, a.post_name, a.post_email, a.likes, a.dislikes, b.userid, b.username, b.md5username, b.email, b.first_name, b.last_name, b.photo, b.view_mail
            FROM ' . NV_PREFIXLANG . '_comment a
            LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' b ON a.userid = b.userid
            WHERE a.pid = :pid AND a.status = 1
            ORDER BY ' . $order;

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':pid', $cid, PDO::PARAM_INT);
    $stmt->execute();

    while ($row = $stmt->fetch()) {
        $row['check_like'] = md5($row['cid'] . '_' . $session_id);
        $row['post_email'] = ($emailcomm) ? $row['post_email'] : '';
        if (!empty($row['attach'])) {
            $row['attach'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=comment&amp;' . NV_OP_VARIABLE . '=down&cid=' . $row['cid'] . '&amp;tokend=' . md5($row['cid'] . '_' . NV_CHECK_SESSION);
        }
        $row['user'] = !empty($row['username']) ? change_alias($row['username']) . '-' . $row['md5username'] : '';
        $data_reply_comment[$row['cid']] = $row;
        $data_reply_comment[$row['cid']]['subcomment'] = nv_comment_get_reply($row['cid'], $module, $session_id, $sortcomm);
    }
    $stmt->closeCursor();

    return $data_reply_comment;
}

/**
 * Giao diện danh sách bình luận (không bao gồm form bình luận)
 * sử dụng qua ajax load
 *
 * @param string $module
 * @param string $checkss
 * @param string $area
 * @param int    $id
 * @param mixed  $allowed
 * @param int    $page
 * @param string $status_comment
 * @return string
 */
function nv_comment_load($module, $checkss, $area, $id, $allowed, $page, $status_comment = '')
{
    global $module_config, $nv_Request, $nv_Lang;

    // Kiểm tra module có được Sử dụng chức năng bình luận
    if (empty($module) or !isset($module_config[$module]) or empty($id) or empty($module_config[$module]['activecomm'])) {
        return '';
    }
    if ($checkss !== md5($module . '-' . $area . '-' . $id . '-' . $allowed . '-' . NV_CHECK_SESSION)) {
        return '';
    }

    $view_comm = nv_user_in_groups($module_config[$module]['view_comm']);
    if (empty($view_comm)) {
        return '';
    }

    $nv_Lang->loadModule('comment', false, true);

    $allowed_comm = nv_user_in_groups($allowed);
    $sortcomm_old = $nv_Request->get_int('sortcomm', 'cookie', $module_config[$module]['sortcomm']);
    $sortcomm = $nv_Request->get_int('sortcomm', 'post', $sortcomm_old);
    if ($sortcomm < 0 or $sortcomm > 2) {
        $sortcomm = 0;
    }
    if ($sortcomm_old != $sortcomm) {
        $nv_Request->set_Cookie('sortcomm', $sortcomm, NV_LIVE_COOKIE_TIME);
    }
    $per_page_comment = empty($module_config[$module]['perpagecomm']) ? 5 : $module_config[$module]['perpagecomm'];
    $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=comment&module=' . $module . '&area=' . $area . '&id=' . $id . '&allowed=' . $allowed . '&checkss=' . $checkss . '&comment_load=1&perpage=' . $per_page_comment;
    $comment_array = nv_comment_data($module, $area, $id, $page, $sortcomm, $base_url);

    $is_delete = false;
    if (defined('NV_IS_SPADMIN')) {
        $is_delete = true;
    } elseif (defined('NV_IS_ADMIN')) {
        global $admin_info;
        $adminscomm = array_map('intval', explode(',', $module_config[$module]['adminscomm']));
        if (in_array((int) $admin_info['admin_id'], $adminscomm, true)) {
            $is_delete = true;
        }
    }

    $contents = nv_comment_module_data($module, $comment_array, $is_delete, $allowed_comm, $status_comment);

    $nv_Lang->changeLang();

    return $contents;
}

/**
 * Lấy giao diện đầy đủ của khối bình luận cho một module
 *
 * @param string $module
 * @param string $checkss
 * @param string $area
 * @param int    $id
 * @param mixed  $allowed
 * @param int    $page
 * @param string $status_comment
 * @param int    $header
 * @return string
 */
function nv_comment_module($module, $checkss, $area, $id, $allowed, $page, $status_comment = '', $header = 1)
{
    global $module_config, $nv_Request, $global_config, $nv_Lang;

    // Kiểm tra module có được Sử dụng chức năng bình luận
    if (empty($module) or !isset($module_config[$module]) or empty($id) or empty($module_config[$module]['activecomm'])) {
        return '';
    }
    if ($checkss !== md5($module . '-' . $area . '-' . $id . '-' . $allowed . '-' . NV_CHECK_SESSION)) {
        return '';
    }

    $per_page_comment = empty($module_config[$module]['perpagecomm']) ? 5 : $module_config[$module]['perpagecomm'];
    $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=comment&module=' . $module . '&area=' . $area . '&id=' . $id . '&allowed=' . $allowed . '&checkss=' . $checkss . '&comment_load=1&perpage=' . $per_page_comment;

    $nv_Lang->loadModule('comment', false, true);

    // Kiểm tra quyền xem bình luận
    $form_login = [
        'display' => 0, // Có hiển thị form login hay ẩn
        'mode' => 'direct', // Trực tiếp đăng nhập hay đăng nhập nhóm
        'link' => '', // Link thông báo
        'groups' => [] // Các nhóm cần tham gia vào, hoặc đăng nhập dưới quyền
    ];
    $view_comm = nv_user_in_groups($module_config[$module]['view_comm']);
    $allowed_comm = nv_user_in_groups($allowed);

    // Xử lý nếu có quyền xem và không có quyền bình
    if ($view_comm and !$allowed_comm and $global_config['allowuserlogin']) {
        $allowed_tmp = explode(',', $allowed);
        $allowed_tmp = array_flip(array_diff($allowed_tmp, [1, 2, 3])); // Loại nhóm quản trị ra
        if ((isset($allowed_tmp['4']) or isset($allowed_tmp['7']))) {
            // Thành viên chính thức hoặc thành viên mới thì đăng nhập trực tiếp
            $form_login['display'] = 1;
            if (!isset($allowed_tmp['7'])) {
                // Thành viên chính thức
                $form_login['groups'][0] = $nv_Lang->getGlobal('level4');
            } else {
                // Thành viên chính thức hoặc thành viên mới
                $form_login['groups'][0] = $nv_Lang->getModule('user');
            }
        } else {
            $list_groups = array_intersect_key(nv_groups_list_pub(), $allowed_tmp);
            if (!empty($list_groups)) {
                $form_login['display'] = 1;
                $form_login['mode'] = 'reggroups';
                $form_login['groups'] = $list_groups;
                if (defined('NV_IS_USER')) {
                    $form_login['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=editinfo/group';
                } else {
                    $form_login['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=login&amp;nv_redirect=' . nv_redirect_encrypt(nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=editinfo/group', true));
                }
            }
        }
    }

    $sortcomm = $nv_Request->get_int('sortcomm', 'cookie', $module_config[$module]['sortcomm']);
    if ($sortcomm < 0 or $sortcomm > 2) {
        $sortcomm = 0;
    }

    $is_delete = false;
    if (defined('NV_IS_SPADMIN')) {
        $is_delete = true;
    } elseif (defined('NV_IS_ADMIN')) {
        global $admin_info;
        $adminscomm = array_map('intval', explode(',', $module_config[$module]['adminscomm']));
        if (in_array((int) $admin_info['admin_id'], $adminscomm, true)) {
            $is_delete = true;
        }
    }
    if ($view_comm) {
        $comment_array = nv_comment_data($module, $area, $id, $page, $sortcomm, $base_url);
        $comment = nv_comment_module_data($module, $comment_array, $is_delete, $allowed_comm, $status_comment);
    } else {
        $comment = '';
    }

    $contents = nv_theme_comment_module($module, $area, $id, $allowed, $checkss, $comment, $sortcomm, $form_login, $header);

    $nv_Lang->changeLang();

    return $contents;
}
