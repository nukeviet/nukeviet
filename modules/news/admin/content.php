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

use NukeViet\Module\news\Shared\Logs;

// Xuất ajax autocomplete các dòng sự kiện
if ($nv_Request->isset_request('get_topic_json', 'post, get')) {
    $q = $nv_Request->get_title('q', 'post, get', '');

    $db->sqlreset()
        ->select('topicid, title')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_topics')
        ->where('title LIKE :q_title')
        ->order('weight ASC')
        ->limit(20);

    $sth = $db->prepare($db->sql());
    $sth->bindValue(':q_title', '%' . $q . '%', PDO::PARAM_STR);
    $sth->execute();

    $array_data = [];
    while (list($topicid, $title) = $sth->fetch(3)) {
        $array_data[] = [
            'id' => $topicid,
            'title' => $title
        ];
    }

    nv_jsonOutput($array_data);
}

// Kiểm tra xem đang sửa có bị cướp quyền hay không, cập nhật thêm thời gian chỉnh sửa
if ($nv_Request->isset_request('id', 'post') and $nv_Request->isset_request('check_edit', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $return = 'OK_';

    $_query = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE id =' . $id);
    if ($row_tmp = $_query->fetch()) {
        if ($row_tmp['admin_id'] == $admin_info['admin_id']) {
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET  time_late=' . NV_CURRENTTIME . ', ip=' . $db->quote($client_info['ip']) . ' WHERE id=' . $id);
            $return = 'OK_' . $id;
        } else {
            $_username = $db->query('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid =' . $row_tmp['admin_id'])->fetchColumn();
            $return = 'ERROR_' . sprintf($lang_module['dulicate_edit_takeover'], $_username, date('H:i d/m/Y', $row_tmp['time_edit']));
        }
    }
    nv_htmlOutput($return);
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

// Lựa chọn Layout
$selectthemes = (!empty($site_mods[$module_name]['theme'])) ? $site_mods[$module_name]['theme'] : $global_config['site_theme'];
$layout_array = nv_scandir(NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', $global_config['check_op_layout']);

// Xác định và tạo các thư mục upload
$username_alias = change_alias($admin_info['username']);
$array_structure_image = [];
$array_structure_image[''] = $module_upload;
$array_structure_image['Y'] = $module_upload . '/' . date('Y');
$array_structure_image['Ym'] = $module_upload . '/' . date('Y_m');
$array_structure_image['Y_m'] = $module_upload . '/' . date('Y/m');
$array_structure_image['Ym_d'] = $module_upload . '/' . date('Y_m/d');
$array_structure_image['Y_m_d'] = $module_upload . '/' . date('Y/m/d');
$array_structure_image['username'] = $module_upload . '/' . $username_alias;

$array_structure_image['username_Y'] = $module_upload . '/' . $username_alias . '/' . date('Y');
$array_structure_image['username_Ym'] = $module_upload . '/' . $username_alias . '/' . date('Y_m');
$array_structure_image['username_Y_m'] = $module_upload . '/' . $username_alias . '/' . date('Y/m');
$array_structure_image['username_Ym_d'] = $module_upload . '/' . $username_alias . '/' . date('Y_m/d');
$array_structure_image['username_Y_m_d'] = $module_upload . '/' . $username_alias . '/' . date('Y/m/d');

$structure_upload = isset($module_config[$module_name]['structure_upload']) ? $module_config[$module_name]['structure_upload'] : 'Ym';
$currentpath = isset($array_structure_image[$structure_upload]) ? $array_structure_image[$structure_upload] : '';

if (file_exists(NV_UPLOADS_REAL_DIR . '/' . $currentpath)) {
    $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $currentpath;
} else {
    $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $module_upload;
    $e = explode('/', $currentpath);
    if (!empty($e)) {
        $cp = '';
        foreach ($e as $p) {
            if (!empty($p) and !is_dir(NV_UPLOADS_REAL_DIR . '/' . $cp . $p)) {
                $mk = nv_mkdir(NV_UPLOADS_REAL_DIR . '/' . $cp, $p);
                if ($mk[0] > 0) {
                    $upload_real_dir_page = $mk[2];
                    try {
                        $db->query('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . NV_UPLOADS_DIR . '/' . $cp . $p . "', 0)");
                    } catch (PDOException $e) {
                        trigger_error($e->getMessage());
                    }
                }
            } elseif (!empty($p)) {
                $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $cp . $p;
            }
            $cp .= $p . '/';
        }
    }
    $upload_real_dir_page = str_replace('\\', '/', $upload_real_dir_page);
}

$currentpath = str_replace(NV_ROOTDIR . '/', '', $upload_real_dir_page);
$uploads_dir_user = NV_UPLOADS_DIR . '/' . $module_upload;
if (!defined('NV_IS_SPADMIN') and str_contains($structure_upload, 'username')) {
    $array_currentpath = explode('/', $currentpath);
    if ($array_currentpath[2] == $username_alias) {
        $uploads_dir_user = NV_UPLOADS_DIR . '/' . $module_upload . '/' . $username_alias;
    }
}

// Danh sách các nhóm tin
$array_block_cat_module = [];
$id_block_content = [];
$sql = 'SELECT bid, adddefault, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat ORDER BY weight ASC';
$result = $db->query($sql);
while (list($bid_i, $adddefault_i, $title_i) = $result->fetch(3)) {
    $array_block_cat_module[$bid_i] = $title_i;
    if ($adddefault_i) {
        $id_block_content[] = $bid_i;
    }
}

$catid = $nv_Request->get_int('catid', 'get', 0);
$parentid = $nv_Request->get_int('parentid', 'get', 0);
$array_imgposition = [
    0 => $lang_module['imgposition_0'],
    1 => $lang_module['imgposition_1'],
    2 => $lang_module['imgposition_2']
];
$total_news_current = nv_get_mod_countrows();
$is_submit_form = (($nv_Request->get_int('save', 'post') == 1) ? true : false);
$restore_id = $nv_Request->get_absint('restore', 'post,get', 0);
$restore_hash = $nv_Request->get_title('restorehash', 'post,get', '');

$rowcontent = [
    'id' => '',
    'catid' => $catid,
    'listcatid' => $catid . ',' . $parentid,
    'topicid' => '',
    'admin_id' => $admin_id,
    'author' => '',
    'internal_authors' => [],
    'internal_authors_old' => [],
    'sourceid' => 0,
    'addtime' => NV_CURRENTTIME,
    'edittime' => NV_CURRENTTIME,
    'status' => 0,
    'publtime' => NV_CURRENTTIME,
    'exptime' => 0,
    'archive' => 1,
    'title' => '',
    'alias' => '',
    'hometext' => '',
    'sourcetext' => '',
    'files' => [],
    'homeimgfile' => '',
    'homeimgalt' => '',
    'homeimgthumb' => '',
    'imgposition' => isset($module_config[$module_name]['imgposition']) ? $module_config[$module_name]['imgposition'] : 1,
    'titlesite' => '',
    'description' => '',
    'bodyhtml' => '',
    'copyright' => 0,
    'inhome' => 1,
    'allowed_comm' => $module_config[$module_name]['setcomm'],
    'allowed_rating' => 1,
    'external_link' => 0,
    'allowed_send' => 1,
    'allowed_print' => 1,
    'allowed_save' => 1,
    'hitstotal' => 0,
    'hitscm' => 0,
    'total_rating' => 0,
    'click_rating' => 0,
    'layout_func' => '',
    'tags' => '',
    'tags_old' => '',
    'keywords' => '',
    'instant_active' => isset($module_config[$module_name]['instant_articles_auto']) ? $module_config[$module_name]['instant_articles_auto'] : 0,
    'instant_template' => '',
    'instant_creatauto' => 0,
    'mode' => 'add'
];

$rowcontent['topictext'] = '';
$page_title = $lang_module['content_add'];
$error = [];
$groups_list = nv_groups_list();
$array_tags_old = [];
$FBIA = new \NukeViet\Facebook\InstantArticles($lang_module);
$internal_authors_list = [];

// ID của bài viết cần sửa hoặc cần copy
$rowcontent['id'] = $nv_Request->get_int('id', 'get,post', 0);
$copy = $nv_Request->get_int('copy', 'get,post', 0);

if ($rowcontent['id'] == 0) {
    $my_author_detail = my_author_detail($admin_info['userid']);
    $rowcontent['internal_authors'][] = $my_author_detail['id'];
    $internal_authors_list[$my_author_detail['id']] = [
        'id' => $my_author_detail['id'],
        'pseudonym' => $my_author_detail['pseudonym']
    ];
} else {
    $check_permission = false;
    $rowcontent = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $rowcontent['id'])->fetch();
    if (!empty($rowcontent['id'])) {
        $rowcontent['old_status'] = $rowcontent['status'];
        // Nếu bài viết đang bị đình chỉ thì trả lại trang thái ban đầu để thao tác, trước khi lưu vào CSDL sẽ căn cứ vào chuyên mục có bị khóa hay không mà build lại trạng thái
        if ($rowcontent['status'] > $global_code_defined['row_locked_status']) {
            $rowcontent['status'] -= ($global_code_defined['row_locked_status'] + 1);
        }
        if (!$copy) {
            $rowcontent['mode'] = 'edit';
        } else {
            $rowcontent['mode'] = 'add';
        }

        // Kiểm tra quyền sửa bài của admin
        $arr_catid = explode(',', $rowcontent['listcatid']);
        if (defined('NV_IS_ADMIN_MODULE')) {
            $check_permission = true;
        } else {
            $check_edit = 0;
            $status = $rowcontent['status'];
            foreach ($arr_catid as $catid_i) {
                if (isset($array_cat_admin[$admin_id][$catid_i])) {
                    if ($array_cat_admin[$admin_id][$catid_i]['admin'] == 1) {
                        ++$check_edit;
                    } else {
                        if ($array_cat_admin[$admin_id][$catid_i]['edit_content'] == 1) {
                            ++$check_edit;
                        } elseif ($array_cat_admin[$admin_id][$catid_i]['app_content'] == 1 and $status == 5) {
                            ++$check_edit;
                        } elseif ($array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1 and ($status == 0 or $status == 8 or $status == 2)) {
                            ++$check_edit;
                        } elseif (($status == 0 or $status == 4 or $status == 5) and $rowcontent['admin_id'] == $admin_id) {
                            ++$check_edit;
                        }
                    }
                }
            }
            if ($check_edit == sizeof($arr_catid)) {
                $check_permission = true;
            }
        }
        $rowcontent['old_listcatid'] = $arr_catid;
    }

    // Không có quyền sửa thì kết thúc
    if (!$check_permission) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    $page_title = $lang_module['content_edit'];
    $rowcontent['topictext'] = '';
    $rowcontent['files'] = '';

    // Lấy các file đính kèm
    $body_contents = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail WHERE id=' . $rowcontent['id'])->fetch();
    $rowcontent = array_merge($rowcontent, $body_contents);
    unset($body_contents);

    // Lấy các tag của bài viết
    $_query = $db->query('SELECT tid, keyword FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id=' . $rowcontent['id'] . ' ORDER BY keyword ASC');
    while ($row = $_query->fetch()) {
        $array_tags_old[$row['tid']] = $row['keyword'];
    }
    $rowcontent['tags'] = implode(', ', $array_tags_old);
    $rowcontent['tags_old'] = $rowcontent['tags'];

    // Lấy danh sach tac gia của bài viết
    $rowcontent['internal_authors'] = [];
    $rowcontent['internal_authors_old'] = [];
    $_query = $db->query('SELECT aid, pseudonym FROM ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist WHERE id=' . $rowcontent['id'] . ' ORDER BY alias ASC');
    while ($row = $_query->fetch()) {
        $rowcontent['internal_authors'][] = $row['aid'];
        if (!$copy) {
            $rowcontent['internal_authors_old'][] = $row['aid'];
        }
        $internal_authors_list[$row['aid']] = [
            'id' => $row['aid'],
            'pseudonym' => $row['pseudonym']
        ];
    }

    // Lấy và đè lại thông tin sẽ khôi phục
    $restore_data = [];
    if ($restore_id) {
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_row_histories WHERE new_id=" . $rowcontent['id'] . " AND id=" . $restore_id;
        $restore_data = $db->query($sql)->fetch();
        if (empty($restore_data) or $restore_hash !== md5(NV_CHECK_SESSION . $admin_info['admin_id'] . $rowcontent['id'] . $restore_id . $restore_data['historytime'])) {
            nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
        }
        unset($restore_data['id'], $restore_data['new_id'], $restore_data['admin_id'], $restore_data['changed_fields']);

        $rowcontent['internal_authors'] = '';
        $rowcontent = array_merge($rowcontent, $restore_data);

        // Lấy lại tác giả thuộc quyền quản lý
        $internal_authors = $rowcontent['internal_authors'];
        $rowcontent['internal_authors'] = [];
        if (!empty($internal_authors)) {
            $_query = $db->query('SELECT id, pseudonym FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id IN(' . $internal_authors . ') ORDER BY alias ASC');
            while ($row = $_query->fetch()) {
                $rowcontent['internal_authors'][] = $row['id'];
                if (!$copy) {
                    $rowcontent['internal_authors_old'][] = $row['id'];
                }
                $internal_authors_list[$row['id']] = [
                    'id' => $row['id'],
                    'pseudonym' => $row['pseudonym']
                ];
            }
        }
        unset($internal_authors);
    }
    $rowcontent['files'] = !empty($rowcontent['files']) ? explode(',', $rowcontent['files']) : [];

    // Các nhóm tin của bài viết
    $id_block_content = [];
    $sql = 'SELECT bid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE id=' . $rowcontent['id'];
    $result = $db->query($sql);
    while (list($bid_i) = $result->fetch(3)) {
        $id_block_content[] = $bid_i;
    }

    // Xóa thông báo của hệ thống về bài viết
    if (empty($rowcontent['status'])) {
        nv_status_notification(NV_LANG_DATA, $module_name, 'post_queue', $rowcontent['id']);
    }

    // Xác định lại đường dẫn upload theo đường dẫn của ảnh minh họa bài viết
    if (!empty($rowcontent['homeimgfile']) and !nv_is_url($rowcontent['homeimgfile']) and file_exists(NV_UPLOADS_REAL_DIR)) {
        $currentpath = NV_UPLOADS_DIR . '/' . $module_upload . '/' . dirname($rowcontent['homeimgfile']);
    }

    // Loại bỏ HTML khỏi giới thiệu ngắn gọn nếu không cho phép HTML
    if (empty($module_config[$module_name]['htmlhometext'])) {
        $rowcontent['hometext'] = strip_tags($rowcontent['hometext'], 'br');
    }
}
$old_rowcontent = $rowcontent;

// Xác định các chuyên mục được quyền đăng bài, xuất bản bài viết, sửa bài, kiểm duyệt bài, các chuyên mục hiện đang bị khóa
$array_cat_add_content = $array_cat_pub_content = $array_cat_edit_content = $array_censor_content = [];
$array_cat_locked = [];
foreach ($global_array_cat as $catid_i => $array_value) {
    if (!in_array((int) $array_value['status'], array_map('intval', $global_code_defined['cat_visible_status']), true)) {
        $array_cat_locked[] = $catid_i;
    }
    /*
     * Đăng bài thì kiểm tra chuyên mục không bị đình chỉ
     * Sửa bài thì kiểm tra thêm cả chuyên mục bị đình chỉ và bài viết đang sửa thuộc chuyên mục đó
     */
    if (in_array((int) $array_value['status'], array_map('intval', $global_code_defined['cat_visible_status']), true) or ($rowcontent['id'] > 0 and in_array($catid_i, $rowcontent['old_listcatid'], true))) {
        $check_add_content = $check_pub_content = $check_edit_content = $check_censor_content = false;
        if (defined('NV_IS_ADMIN_MODULE')) {
            $check_add_content = $check_pub_content = $check_edit_content = $check_censor_content = true;
        } elseif (isset($array_cat_admin[$admin_id][$catid_i])) {
            if ($array_cat_admin[$admin_id][$catid_i]['admin'] == 1) {
                $check_add_content = $check_pub_content = $check_edit_content = $check_censor_content = true;
            } else {
                if ($array_cat_admin[$admin_id][$catid_i]['add_content'] == 1) {
                    $check_add_content = true;
                }

                if ($array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1) {
                    $check_pub_content = true;
                }

                if ($array_cat_admin[$admin_id][$catid_i]['app_content'] == 1) {
                    $check_censor_content = true;
                }

                if ($array_cat_admin[$admin_id][$catid_i]['edit_content'] == 1) {
                    $check_edit_content = true;
                }
            }
        }

        if ($check_add_content) {
            $array_cat_add_content[] = $catid_i;
        }
        if ($check_pub_content) {
            $array_cat_pub_content[] = $catid_i;
        }
        if ($check_censor_content) {
            $array_censor_content[] = $catid_i;
        }
        if ($check_edit_content) {
            $array_cat_edit_content[] = $catid_i;
        }
    }
}

/*
 * Kiểm tra bị chiếm quyền sửa hoặc cố tình sửa bài của người đang sửa
 * Kiểm tra nếu đang sửa bài, đang thêm bài hoặc copy bài thì không kiểm tra
 * Đưa lên trước khi submit để tránh trường hợp đang sửa bài bị người khác chiếm quyền
 * sau đó tiếp tục nhấn submit thì dữ liệu vẫn được lưu
 */
if ($rowcontent['mode'] == 'edit') {
    $row_tmp = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE id=' . $rowcontent['id'])->fetch();
    if ($row_tmp) {
        // Xác định người đang sửa
        $_username = $db->query('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid =' . $row_tmp['admin_id'])->fetchColumn();

        // Kiểm tra nếu có người đang sửa
        if ($row_tmp['admin_id'] == $admin_info['admin_id']) {
            // Cập nhật thời gian sửa cuối
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET
                time_late=' . NV_CURRENTTIME . ',
                ip=' . $db->quote($client_info['ip']) . '
            WHERE id=' . $rowcontent['id']);
        } elseif ($row_tmp['time_late'] < (NV_CURRENTTIME - $global_code_defined['edit_timeout']) or empty($_username)) {
            /*
             * Cho phép sửa nếu:
             * - Người đang sửa 3 phút không thao tác đến
             * - Không tồn tại thành viên nữa (có thể bị xóa tài khoản)
             */
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET
                admin_id=' . $admin_info['admin_id'] . ',
                time_edit=' . NV_CURRENTTIME . ',
                time_late=' . NV_CURRENTTIME . ',
                ip=' . $db->quote($client_info['ip']) . '
            WHERE id=' . $rowcontent['id']);
        } else {
            $xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
            $xtpl->assign('GLANG', $lang_global);
            $xtpl->assign('LANG', $lang_module);

            // Thông báo không có quyền sửa.
            $_authors_lev = $db->query('SELECT lev FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id =' . $row_tmp['admin_id'])->fetchColumn();
            if ($admin_info['level'] < $_authors_lev) {
                $takeover = md5($rowcontent['id'] . '_takeover_' . NV_CHECK_SESSION);
                if ($takeover == $nv_Request->get_title('takeover', 'get', '')) {
                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET
                        admin_id=' . $admin_info['admin_id'] . ',
                        time_edit=' . NV_CURRENTTIME . ',
                        time_late=' . NV_CURRENTTIME . ',
                        ip=' . $db->quote($client_info['ip']) . '
                    WHERE id=' . $rowcontent['id']);
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&id=' . $rowcontent['id'] . '&rand=' . nv_genpass());
                }
                $message = sprintf($lang_module['dulicate_edit_admin'], $rowcontent['title'], $_username, date('H:i d/m/Y', $row_tmp['time_edit']));
                $xtpl->assign('TAKEOVER_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&id=' . $rowcontent['id'] . '&takeover=' . $takeover);
                $xtpl->parse('editing.takeover');
            } else {
                $message = sprintf($lang_module['dulicate_edit'], $rowcontent['title'], $_username, date('H:i d/m/Y', $row_tmp['time_edit']));
            }

            $xtpl->assign('MESSAGE', $message);

            $xtpl->parse('editing');
            $contents = $xtpl->text('editing');

            include NV_ROOTDIR . '/includes/header.php';
            echo nv_admin_theme($contents);
            include NV_ROOTDIR . '/includes/footer.php';
        }
    } elseif (!$is_submit_form) {
        // Khi bắt đầu sửa bài thì lưu thông tin người sửa
        // Không lưu nếu submit
        $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tmp (
            id, admin_id, time_edit, time_late, ip
        ) VALUES (
            ' . $rowcontent['id'] . ', ' . $admin_info['admin_id'] . ', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', ' . $db->quote($client_info['ip']) . '
        )');
    }
}

if ($is_submit_form) {
    $rowcontent['referer'] = $nv_Request->get_string('referer', 'get,post');
    $catids = array_unique($nv_Request->get_typed_array('catids', 'post', 'int', []));
    $rowcontent['listcatid'] = implode(',', $catids);
    $rowcontent['catid'] = $nv_Request->get_int('catid', 'post', 0);

    $id_block_content_post = array_unique($nv_Request->get_typed_array('bids', 'post', 'int', []));

    if ($nv_Request->isset_request('status1', 'post') or $copy) {
        // Xuất bản
        $rowcontent['status'] = 1;
    } elseif ($nv_Request->isset_request('status8', 'post')) {
        // Chuyển đăng bài
        $rowcontent['status'] = 8;
    } elseif ($nv_Request->isset_request('status4', 'post')) {
        // Luu tam
        $rowcontent['status'] = ($rowcontent['id'] > 0) ? $rowcontent['status'] : 4;
    } elseif ($nv_Request->isset_request('status5', 'post')) {
        // Chuyển duyệt bài
        $rowcontent['status'] = 5;
    } else {
        // Gui, cho bien tap
        $rowcontent['status'] = 6;
    }

    $message_error_show = $lang_module['permissions_pub_error'];
    if ($rowcontent['status'] == 1) {
        $array_cat_check_content = array_map('intval', $array_cat_pub_content);
    } elseif ($rowcontent['status'] == 1 and $rowcontent['publtime'] <= NV_CURRENTTIME) {
        $array_cat_check_content = array_map('intval', $array_cat_edit_content);
    } elseif ($rowcontent['status'] == 0) {
        $array_cat_check_content = array_map('intval', $array_censor_content);
        $message_error_show = $lang_module['permissions_sendspadmin_error'];
    } else {
        $array_cat_check_content = array_map('intval', $array_cat_add_content);
    }

    foreach ($catids as $catid_i) {
        if (!in_array($catid_i, $array_cat_check_content, true)) {
            $error[] = sprintf($message_error_show, $global_array_cat[$catid_i]['title']);
        }
    }
    if (!empty($catids)) {
        $rowcontent['catid'] = in_array($rowcontent['catid'], $catids, true) ? $rowcontent['catid'] : $catids[0];
    }

    $rowcontent['topicid'] = $nv_Request->get_int('topicid', 'post', 0);
    if ($rowcontent['topicid'] == 0) {
        $rowcontent['topictext'] = $nv_Request->get_title('topictext', 'post', '');
        if (!empty($rowcontent['topictext'])) {
            $stmt = $db->prepare('SELECT topicid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE title= :title');
            $stmt->bindParam(':title', $rowcontent['topictext'], PDO::PARAM_STR);
            $stmt->execute();
            $rowcontent['topicid'] = $stmt->fetchColumn();
        }
    }
    $rowcontent['author'] = $nv_Request->get_title('author', 'post', '', 1);
    $rowcontent['internal_authors'] = $nv_Request->get_typed_array('internal_authors', 'post', 'int', []);
    $rowcontent['sourcetext'] = $nv_Request->get_title('sourcetext', 'post', '');

    $publ_date = $nv_Request->get_title('publ_date', 'post', '');

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date, $m)) {
        $phour = $nv_Request->get_int('phour', 'post', 0);
        $pmin = $nv_Request->get_int('pmin', 'post', 0);
        $rowcontent['publtime'] = mktime($phour, $pmin, 0, $m[2], $m[1], $m[3]);
    } else {
        $rowcontent['publtime'] = NV_CURRENTTIME;
    }

    $exp_date = $nv_Request->get_title('exp_date', 'post', '');
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $exp_date, $m)) {
        $ehour = $nv_Request->get_int('ehour', 'post', 0);
        $emin = $nv_Request->get_int('emin', 'post', 0);
        $rowcontent['exptime'] = mktime($ehour, $emin, 0, $m[2], $m[1], $m[3]);
    } else {
        $rowcontent['exptime'] = 0;
    }

    $rowcontent['archive'] = $nv_Request->get_int('archive', 'post', 0);
    if ($rowcontent['archive'] > 0) {
        $rowcontent['archive'] = ($rowcontent['exptime'] > NV_CURRENTTIME) ? 1 : 2;
    }
    $rowcontent['title'] = $nv_Request->get_title('title', 'post', '', 1);
    // Xử lý file đính kèm
    $rowcontent['files'] = [];
    $fileupload = $nv_Request->get_array('files', 'post');
    if (!empty($fileupload)) {
        $fileupload = array_map('trim', $fileupload);
        $fileupload = array_unique($fileupload);
        foreach ($fileupload as $_file) {
            if (preg_match('/^' . str_replace('/', "\/", NV_BASE_SITEURL . NV_UPLOADS_DIR) . "\//", $_file)) {
                $_file = substr($_file, strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));

                if (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $_file)) {
                    $rowcontent['files'][] = $_file;
                }
            } elseif (preg_match('/^http*/', $_file)) {
                $rowcontent['files'][] = $_file;
            }
        }
    }
    $rowcontent['files'] = !empty($rowcontent['files']) ? implode(',', $rowcontent['files']) : '';

    // Xử lý liên kết tĩnh
    $alias = $nv_Request->get_title('alias', 'post', '');
    if (empty($alias)) {
        $alias = get_mod_alias($rowcontent['title']);
        if ($module_config[$module_name]['alias_lower']) {
            $alias = strtolower($alias);
        }
    } else {
        $alias = get_mod_alias($alias);
    }

    if (empty($alias) or !preg_match("/^([a-zA-Z0-9\_\-]+)$/", $alias)) {
        if (empty($rowcontent['alias'])) {
            $rowcontent['alias'] = 'post';
        }
    } else {
        $rowcontent['alias'] = $alias;
    }

    if (!empty($module_config[$module_name]['htmlhometext'])) {
        $rowcontent['hometext'] = $nv_Request->get_editor('hometext', '', NV_ALLOWED_HTML_TAGS);
    } else {
        $rowcontent['hometext'] = $nv_Request->get_textarea('hometext', '', 'br', 1);
    }

    $rowcontent['homeimgfile'] = $nv_Request->get_title('homeimg', 'post', '');
    $rowcontent['homeimgalt'] = $nv_Request->get_title('homeimgalt', 'post', '', 1);
    $rowcontent['imgposition'] = $nv_Request->get_int('imgposition', 'post', 0);
    if (!array_key_exists($rowcontent['imgposition'], $array_imgposition)) {
        $rowcontent['imgposition'] = 1;
    }
    // Lua chon Layout
    $rowcontent['layout_func'] = $nv_Request->get_title('layout_func', 'post', '');

    $rowcontent['titlesite'] = $nv_Request->get_title('titlesite', 'post', '');
    $rowcontent['description'] = $nv_Request->get_title('description', 'post', '');
    $rowcontent['bodyhtml'] = $nv_Request->get_editor('bodyhtml', '', NV_ALLOWED_HTML_TAGS);
    $rowcontent['copyright'] = (int) $nv_Request->get_bool('copyright', 'post');
    $rowcontent['inhome'] = (int) $nv_Request->get_bool('inhome', 'post');

    $_groups_post = $nv_Request->get_array('allowed_comm', 'post', []);
    $rowcontent['allowed_comm'] = !empty($_groups_post) ? implode(',', nv_groups_post(array_intersect($_groups_post, array_keys($groups_list)))) : '';

    $rowcontent['allowed_rating'] = (int) $nv_Request->get_bool('allowed_rating', 'post');
    $rowcontent['external_link'] = (int) $nv_Request->get_bool('external_link', 'post');
    if ($rowcontent['external_link'] and empty($rowcontent['sourcetext'])) {
        $rowcontent['external_link'] = 0;
    }

    $rowcontent['allowed_send'] = (int) $nv_Request->get_bool('allowed_send', 'post');
    $rowcontent['allowed_print'] = (int) $nv_Request->get_bool('allowed_print', 'post');
    $rowcontent['allowed_save'] = (int) $nv_Request->get_bool('allowed_save', 'post');

    $rowcontent['keywords'] = $nv_Request->get_array('keywords', 'post', '');
    $rowcontent['keywords'] = trim(nv_substr(implode(', ', $rowcontent['keywords']), 0, 255), ", \t\n\r\0\x0B");
    $rowcontent['tags'] = $nv_Request->get_typed_array('tags', 'post', 'title', []);
    $rowcontent['tags'] = implode(', ', $rowcontent['tags']);

    // Tu dong xac dinh tags
    if ($rowcontent['tags'] == '' and !empty($module_config[$module_name]['auto_tags'])) {
        $tags = ($rowcontent['hometext'] != '') ? $rowcontent['hometext'] : $rowcontent['bodyhtml'];
        $tags = nv_get_keywords($tags, 100);
        $tags = explode(',', $tags);

        // Ưu tiên lọc từ khóa theo các từ khóa đã có trong tags thay vì đọc từ từ điển
        $tags_return = [];
        foreach ($tags as $tag_i) {
            $sth = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id where keyword = :keyword');
            $sth->bindParam(':keyword', $tag_i, PDO::PARAM_STR);
            $sth->execute();
            if ($sth->fetchColumn()) {
                $tags_return[] = $tag_i;
                if (sizeof($tags_return) > 20) {
                    break;
                }
            }
        }

        if (sizeof($tags_return) < 20) {
            foreach ($tags as $tag_i) {
                if (!in_array($tag_i, $tags_return, true)) {
                    $tags_return[] = $tag_i;
                    if (sizeof($tags_return) > 20) {
                        break;
                    }
                }
            }
        }
        $rowcontent['tags'] = implode(',', $tags_return);
    }

    if (empty($rowcontent['title'])) {
        $error[] = $lang_module['error_title'];
    } elseif (empty($rowcontent['listcatid'])) {
        $error[] = $lang_module['error_cat'];
    } elseif (empty($rowcontent['external_link']) and trim(strip_tags($rowcontent['bodyhtml'])) == '' and !preg_match("/\<img[^\>]*alt=\"([^\"]+)\"[^\>]*\>/is", $rowcontent['bodyhtml']) and !preg_match("/<iframe.*src=\"(.*)\".*><\/iframe>/isU", $rowcontent['bodyhtml'])) {
        $error[] = $lang_module['error_bodytext'];
    }

    if (!empty($error)) {
        // Nếu có lỗi thì chuyển sang trạng thái đăng nháp, cho đến khi nào đủ thông tin mới cho xuất bản
        $rowcontent['status'] = 4;
        $error_data = $error;
        $error = [];
    }

    // Thao tác xử lý bài viết tức thời
    if (!empty($module_config[$module_name]['instant_articles_active'])) {
        $rowcontent['instant_active'] = (int) $nv_Request->get_bool('instant_active', 'post');
        $rowcontent['instant_template'] = $nv_Request->get_title('instant_template', 'post', '');
        $rowcontent['instant_creatauto'] = (int) $nv_Request->get_bool('instant_creatauto', 'post');
    } else {
        $rowcontent['instant_active'] = 0;
        $rowcontent['instant_template'] = '';
        $rowcontent['instant_creatauto'] = 0;
    }
    if (empty($rowcontent['instant_active'])) {
        $rowcontent['instant_template'] = '';
    }
    if ($rowcontent['instant_active'] and !$rowcontent['instant_creatauto']) {
        $FBIA->setArticle($rowcontent['bodyhtml']);
        $checkArt = $FBIA->checkArticle();
        if ($checkArt !== true) {
            $error[] = $checkArt;
        }
    }

    if (empty($error)) {
        if (!empty($rowcontent['topictext']) and empty($rowcontent['topicid'])) {
            $weightopic = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics')->fetchColumn();
            $weightopic = (int) $weightopic + 1;
            $aliastopic = get_mod_alias($rowcontent['topictext'], 'topics');
            $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . "_topics (title, alias, description, image, weight, keywords, add_time, edit_time) VALUES ( :title, :alias, :description, '', :weight, :keywords, " . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ')';
            $data_insert = [];
            $data_insert['title'] = $rowcontent['topictext'];
            $data_insert['alias'] = $aliastopic;
            $data_insert['description'] = $rowcontent['topictext'];
            $data_insert['weight'] = $weightopic;
            $data_insert['keywords'] = $rowcontent['topictext'];
            $rowcontent['topicid'] = $db->insert_id($_sql, 'topicid', $data_insert);
        }

        $rowcontent['sourceid'] = 0;
        if (!empty($rowcontent['sourcetext'])) {
            $url_info = parse_url($rowcontent['sourcetext']);
            if (isset($url_info['scheme']) and isset($url_info['host'])) {
                $sourceid_link = $url_info['scheme'] . '://' . $url_info['host'];
                $stmt = $db->prepare('SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE link= :link');
                $stmt->bindParam(':link', $sourceid_link, PDO::PARAM_STR);
                $stmt->execute();
                $rowcontent['sourceid'] = $stmt->fetchColumn();

                if (empty($rowcontent['sourceid'])) {
                    $weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources')->fetchColumn();
                    $weight = (int) $weight + 1;
                    $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . "_sources (title, link, logo, weight, add_time, edit_time) VALUES ( :title ,:sourceid_link, '', :weight, " . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ')';

                    $data_insert = [];
                    $data_insert['title'] = $url_info['host'];
                    $data_insert['sourceid_link'] = $sourceid_link;
                    $data_insert['weight'] = $weight;

                    $rowcontent['sourceid'] = $db->insert_id($_sql, 'sourceid', $data_insert);
                }

                $rowcontent['external_link'] = $rowcontent['external_link'] ? 1 : 0;
            } else {
                $stmt = $db->prepare('SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE title= :title');
                $stmt->bindParam(':title', $rowcontent['sourcetext'], PDO::PARAM_STR);
                $stmt->execute();
                $rowcontent['sourceid'] = $stmt->fetchColumn();

                if (empty($rowcontent['sourceid'])) {
                    $weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources')->fetchColumn();
                    $weight = (int) $weight + 1;
                    $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . "_sources (title, link, logo, weight, add_time, edit_time) VALUES ( :title, '', '', " . $weight . ' , ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ')';
                    $data_insert = [];
                    $data_insert['title'] = $rowcontent['sourcetext'];

                    $rowcontent['sourceid'] = $db->insert_id($_sql, 'sourceid', $data_insert);
                }

                $rowcontent['external_link'] = 0;
            }
        }

        // Xu ly anh minh hoa
        $rowcontent['homeimgthumb'] = 0;
        if (empty($rowcontent['homeimgfile']) and ($rowcontent['imgposition'] == 1 or $rowcontent['imgposition'] == 2)) {
            $rowcontent['homeimgfile'] = nv_get_firstimage($rowcontent['bodyhtml']);
        }
        if (!nv_is_url($rowcontent['homeimgfile']) and nv_is_file($rowcontent['homeimgfile'], NV_UPLOADS_DIR . '/' . $module_upload) === true) {
            $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
            $rowcontent['homeimgfile'] = substr($rowcontent['homeimgfile'], $lu);
            if (file_exists(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile'])) {
                $rowcontent['homeimgthumb'] = 1;
            } else {
                $rowcontent['homeimgthumb'] = 2;
            }
        } elseif (nv_is_url($rowcontent['homeimgfile'])) {
            $rowcontent['homeimgthumb'] = 3;
        } else {
            $rowcontent['homeimgfile'] = '';
        }

        // Xử lý lưu vào CSDL khi đăng mới hoặc sao chép
        if ($rowcontent['id'] == 0 or $copy) {
            // Toàn quyền module trở lên được đăng bài lùi về sau
            if (!$NV_IS_ADMIN_FULL_MODULE and (int) ($rowcontent['publtime']) < NV_CURRENTTIME) {
                $rowcontent['publtime'] = NV_CURRENTTIME;
            }
            if ($rowcontent['status'] == 1 and $rowcontent['publtime'] > NV_CURRENTTIME) {
                $rowcontent['status'] = 2;
            }
            // Reset lượt xem, lượt tải, số comment, số vote, điểm vote về 0
            if ($copy) {
                $rowcontent['hitstotal'] = 0;
                $rowcontent['hitscm'] = 0;
                $rowcontent['total_rating'] = 0;
                $rowcontent['click_rating'] = 0;
            }

            // Nếu bài viết trong chuyên mục bị khóa thì xây dựng lại status
            if (array_intersect($catids, $array_cat_locked) != [] and $rowcontent['status'] <= $global_code_defined['row_locked_status']) {
                $rowcontent['status'] += ($global_code_defined['row_locked_status'] + 1);
            }

            $_weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows')->fetchColumn();
            $_weight = (int) $_weight + 1;

            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_rows (
                catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, status, weight, publtime, exptime, archive, title, alias, hometext,
                homeimgfile, homeimgalt, homeimgthumb, inhome, allowed_comm, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating, instant_active, instant_template,
                instant_creatauto
            ) VALUES (
                 ' . (int) ($rowcontent['catid']) . ',
                 :listcatid,
                 ' . $rowcontent['topicid'] . ',
                 ' . (int) ($rowcontent['admin_id']) . ',
                 :author,
                 ' . (int) ($rowcontent['sourceid']) . ',
                 ' . (int) ($rowcontent['addtime']) . ',
                 ' . (int) ($rowcontent['edittime']) . ',
                 ' . (int) ($rowcontent['status']) . ',
                 ' . $_weight . ',
                 ' . (int) ($rowcontent['publtime']) . ',
                 ' . (int) ($rowcontent['exptime']) . ',
                 ' . (int) ($rowcontent['archive']) . ',
                 :title,
                 :alias,
                 :hometext,
                 :homeimgfile,
                 :homeimgalt,
                 :homeimgthumb,
                 ' . (int) ($rowcontent['inhome']) . ',
                 :allowed_comm,
                 ' . (int) ($rowcontent['allowed_rating']) . ',
                 ' . (int) ($rowcontent['external_link']) . ',
                 ' . (int) ($rowcontent['hitstotal']) . ',
                 ' . (int) ($rowcontent['hitscm']) . ',
                 ' . (int) ($rowcontent['total_rating']) . ',
                 ' . (int) ($rowcontent['click_rating']) . ',
                 ' . (int) ($rowcontent['instant_active']) . ',
                 :instant_template,
                 ' . (int) ($rowcontent['instant_creatauto']) . ')';

            $data_insert = [];
            $data_insert['listcatid'] = $rowcontent['listcatid'];
            $data_insert['author'] = $rowcontent['author'];
            $data_insert['title'] = $rowcontent['title'];
            $data_insert['alias'] = $rowcontent['alias'];
            $data_insert['hometext'] = $rowcontent['hometext'];
            $data_insert['homeimgfile'] = $rowcontent['homeimgfile'];
            $data_insert['homeimgalt'] = $rowcontent['homeimgalt'];
            $data_insert['homeimgthumb'] = $rowcontent['homeimgthumb'];
            $data_insert['allowed_comm'] = $rowcontent['allowed_comm'];
            $data_insert['instant_template'] = $rowcontent['instant_template'];

            $rowcontent['id'] = $db->insert_id($sql, 'id', $data_insert);
            if ($rowcontent['id'] > 0) {
                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['content_add'], $rowcontent['title'], $admin_info['userid']);
                $ct_query = [];

                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_detail (id, titlesite, description, bodyhtml, keywords, sourcetext, files, imgposition, layout_func, copyright, allowed_send, allowed_print, allowed_save) VALUES (
                    ' . $rowcontent['id'] . ',
                    :titlesite,
                    :description,
                    :bodyhtml,
                    :keywords,
                    :sourcetext,
                    :files,
                    ' . $rowcontent['imgposition'] . ',
                    :layout_func,
                    ' . $rowcontent['copyright'] . ',
                    ' . $rowcontent['allowed_send'] . ',
                    ' . $rowcontent['allowed_print'] . ',
                    ' . $rowcontent['allowed_save'] . '
                )');
                $stmt->bindParam(':files', $rowcontent['files'], PDO::PARAM_STR);
                $stmt->bindParam(':titlesite', $rowcontent['titlesite'], PDO::PARAM_STR);
                $stmt->bindParam(':layout_func', $rowcontent['layout_func'], PDO::PARAM_STR);
                $stmt->bindParam(':description', $rowcontent['description'], PDO::PARAM_STR, strlen($rowcontent['description']));
                $stmt->bindParam(':bodyhtml', $rowcontent['bodyhtml'], PDO::PARAM_STR, strlen($rowcontent['bodyhtml']));
                $stmt->bindParam(':keywords', $rowcontent['keywords'], PDO::PARAM_STR, strlen($rowcontent['keywords']));
                $stmt->bindParam(':sourcetext', $rowcontent['sourcetext'], PDO::PARAM_STR, strlen($rowcontent['sourcetext']));
                $ct_query[] = (int) $stmt->execute();

                foreach ($catids as $catid) {
                    $ct_query[] = (int) $db->exec('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $rowcontent['id']);
                }

                if (array_sum($ct_query) != sizeof($ct_query)) {
                    $error[] = $lang_module['errorsave'];
                }
                unset($ct_query);
                if ($module_config[$module_name]['elas_use'] == 1) {
                    /* connect to elasticsearch */
                    $body_contents = $db_slave->query('SELECT bodyhtml, sourcetext, imgposition, copyright, allowed_send, allowed_print, allowed_save FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail where id=' . $rowcontent['id'])->fetch();
                    $rowcontent = array_merge($rowcontent, $body_contents);

                    $rowcontent['unsigned_title'] = nv_EncString($rowcontent['title']);
                    $rowcontent['unsigned_bodyhtml'] = nv_EncString($rowcontent['bodyhtml']);
                    $rowcontent['unsigned_author'] = nv_EncString($rowcontent['author']);
                    $rowcontent['unsigned_hometext'] = nv_EncString($rowcontent['hometext']);

                    $nukeVietElasticSearh = new NukeViet\ElasticSearch\Functions($module_config[$module_name]['elas_host'], $module_config[$module_name]['elas_port'], $module_config[$module_name]['elas_index']);
                    $response = $nukeVietElasticSearh->insert_data(NV_PREFIXLANG . '_' . $module_data . '_rows', $rowcontent['id'], $rowcontent);
                }
            } else {
                $error[] = $lang_module['errorsave'];
            }
        } else {
            $rowcontent_old = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows where id=' . $rowcontent['id'])->fetch();
            if ($rowcontent_old['status'] > $global_code_defined['row_locked_status']) {
                $rowcontent_old['status'] -= ($global_code_defined['row_locked_status'] + 1);
            }
            if ($rowcontent_old['status'] == 1) {
                $rowcontent['status'] = 1;
            }

            if (!empty($error_data)) {
                // Nếu khi sửa bài viết mà có lỗi nhập liệu lại chuyển về trạng thái đăng nháp
                $rowcontent['status'] = 4;
            }

            // Toàn quyền module trở lên được sửa thời gian đăng bài lùi về sau
            if (!$NV_IS_ADMIN_FULL_MODULE and (int) ($rowcontent['publtime']) < (int) ($rowcontent_old['addtime'])) {
                $rowcontent['publtime'] = $rowcontent_old['addtime'];
            }

            if ($rowcontent['status'] == 1 and $rowcontent['publtime'] > NV_CURRENTTIME) {
                $rowcontent['status'] = 2;
            }

            // Nếu bài viết trong chuyên mục bị khóa thì xây dựng lại status
            if (array_intersect($catids, $array_cat_locked) != [] and $rowcontent['status'] <= $global_code_defined['row_locked_status']) {
                $rowcontent['status'] += ($global_code_defined['row_locked_status'] + 1);
            }

            // Cập nhật bảng rows
            $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET
                catid=' . (int) ($rowcontent['catid']) . ',
                listcatid=:listcatid,
                topicid=' . $rowcontent['topicid'] . ',
                author=:author,
                sourceid=' . (int) ($rowcontent['sourceid']) . ',
                status=' . (int) ($rowcontent['status']) . ',
                publtime=' . (int) ($rowcontent['publtime']) . ',
                exptime=' . (int) ($rowcontent['exptime']) . ',
                archive=' . (int) ($rowcontent['archive']) . ',
                title=:title,
                alias=:alias,
                hometext=:hometext,
                homeimgfile=:homeimgfile,
                homeimgalt=:homeimgalt,
                homeimgthumb=:homeimgthumb,
                inhome=' . (int) ($rowcontent['inhome']) . ',
                allowed_comm=:allowed_comm,
                allowed_rating=' . (int) ($rowcontent['allowed_rating']) . ',
                external_link=' . (int) ($rowcontent['external_link']) . ',
                instant_active=' . (int) ($rowcontent['instant_active']) . ',
                instant_template=:instant_template,
                instant_creatauto=' . (int) ($rowcontent['instant_creatauto']) . ',
                edittime=' . ($restore_id ? $rowcontent['historytime'] : NV_CURRENTTIME) . '
            WHERE id =' . $rowcontent['id']);

            $sth->bindParam(':listcatid', $rowcontent['listcatid'], PDO::PARAM_STR);
            $sth->bindParam(':author', $rowcontent['author'], PDO::PARAM_STR);
            $sth->bindParam(':title', $rowcontent['title'], PDO::PARAM_STR);
            $sth->bindParam(':alias', $rowcontent['alias'], PDO::PARAM_STR);
            $sth->bindParam(':hometext', $rowcontent['hometext'], PDO::PARAM_STR, strlen($rowcontent['hometext']));
            $sth->bindParam(':homeimgfile', $rowcontent['homeimgfile'], PDO::PARAM_STR);
            $sth->bindParam(':homeimgalt', $rowcontent['homeimgalt'], PDO::PARAM_STR);
            $sth->bindParam(':homeimgthumb', $rowcontent['homeimgthumb'], PDO::PARAM_STR);
            $sth->bindParam(':allowed_comm', $rowcontent['allowed_comm'], PDO::PARAM_STR);
            $sth->bindParam(':instant_template', $rowcontent['instant_template'], PDO::PARAM_STR);

            if ($sth->execute()) {
                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['content_edit'], $rowcontent['title'], $admin_info['userid']);

                $ct_query = [];

                // Cập nhật bảng detail
                $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_detail SET
                    titlesite=:titlesite,
                    description=:description,
                    bodyhtml=:bodyhtml,
                    keywords=:keywords,
                    sourcetext=:sourcetext,
                    files=:files,
                    imgposition=' . (int) ($rowcontent['imgposition']) . ',
                    layout_func=:layout_func,
                    copyright=' . (int) ($rowcontent['copyright']) . ',
                    allowed_send=' . (int) ($rowcontent['allowed_send']) . ',
                    allowed_print=' . (int) ($rowcontent['allowed_print']) . ',
                    allowed_save=' . (int) ($rowcontent['allowed_save']) . '
                WHERE id =' . $rowcontent['id']);

                $sth->bindParam(':files', $rowcontent['files'], PDO::PARAM_STR);
                $sth->bindParam(':titlesite', $rowcontent['titlesite'], PDO::PARAM_STR);
                $sth->bindParam(':layout_func', $rowcontent['layout_func'], PDO::PARAM_STR, strlen($rowcontent['layout_func']));
                $sth->bindParam(':description', $rowcontent['description'], PDO::PARAM_STR, strlen($rowcontent['description']));
                $sth->bindParam(':bodyhtml', $rowcontent['bodyhtml'], PDO::PARAM_STR, strlen($rowcontent['bodyhtml']));
                $sth->bindParam(':keywords', $rowcontent['keywords'], PDO::PARAM_STR, strlen($rowcontent['keywords']));
                $sth->bindParam(':sourcetext', $rowcontent['sourcetext'], PDO::PARAM_STR, strlen($rowcontent['sourcetext']));

                $ct_query[] = (int) $sth->execute();

                // Xóa trong bảng cat cũ
                if ($rowcontent_old['listcatid'] != $rowcontent['listcatid']) {
                    $array_cat_old = explode(',', $rowcontent_old['listcatid']);
                    $array_cat_new = explode(',', $rowcontent['listcatid']);
                    $array_cat_diff = array_diff($array_cat_old, $array_cat_new);
                    foreach ($array_cat_diff as $catid) {
                        if (!empty($catid)) {
                            $ct_query[] = $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id = ' . (int) ($rowcontent['id']));
                        }
                    }
                }

                // Xóa bảng cat và thêm lại
                foreach ($catids as $catid) {
                    if (!empty($catid)) {
                        $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id = ' . $rowcontent['id']);
                        $ct_query[] = $db->exec('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $rowcontent['id']);
                    }
                }

                if (array_sum($ct_query) != sizeof($ct_query)) {
                    $error[] = $lang_module['errorsave'];
                }

                // Cập nhật bên ES
                if ($module_config[$module_name]['elas_use'] == 1) {
                    $body_contents = $db_slave->query('SELECT bodyhtml, sourcetext, imgposition, copyright, allowed_send, allowed_print, allowed_save FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail where id=' . $rowcontent['id'])->fetch();
                    $rowcontent = array_merge($rowcontent, $body_contents);

                    $rowcontent['unsigned_title'] = nv_EncString($rowcontent['title']);
                    $rowcontent['unsigned_bodyhtml'] = nv_EncString($rowcontent['bodyhtml']);
                    $rowcontent['unsigned_author'] = nv_EncString($rowcontent['author']);
                    $rowcontent['unsigned_hometext'] = nv_EncString($rowcontent['hometext']);

                    $nukeVietElasticSearh = new NukeViet\ElasticSearch\Functions($module_config[$module_name]['elas_host'], $module_config[$module_name]['elas_port'], $module_config[$module_name]['elas_index']);
                    $result_search = $nukeVietElasticSearh->update_data(NV_PREFIXLANG . '_' . $module_data . '_rows', $rowcontent['id'], $rowcontent);
                }

                // Sau khi sửa, tiến hành xóa bản ghi lưu trạng thái sửa trong csdl
                $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE id = ' . $rowcontent['id']);

                // Lưu lịch sử sửa bài viết nếu bật và đây không phải là hành động khôi phục
                if (!empty($module_config[$module_name]['active_history']) and empty($restore_id)) {
                    $change_field = nv_save_history($old_rowcontent, $rowcontent);
                    if (empty($change_field)) {
                        // Trường hợp ấn sửa mà không thay đổi gì thì không cập nhật edittime mới lên
                        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET edittime=" . $old_rowcontent['edittime'] . " WHERE id=" . $rowcontent['id'];
                        $db->query($sql);
                    }
                }
            } else {
                $error[] = $lang_module['errorsave'];
            }
        }

        nv_set_status_module();
        if (empty($error)) {
            $id_block_content_new = $rowcontent['mode'] == 'edit' ? array_diff($id_block_content_post, $id_block_content) : $id_block_content_post;
            $id_block_content_del = $rowcontent['mode'] == 'edit' ? array_diff($id_block_content, $id_block_content_post) : [];

            $array_block_fix = [];
            foreach ($id_block_content_new as $bid_i) {
                $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_block (bid, id, weight) VALUES (' . $bid_i . ', ' . $rowcontent['id'] . ', 0)');
                $array_block_fix[] = $bid_i;
            }
            foreach ($id_block_content_del as $bid_i) {
                $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE id = ' . $rowcontent['id'] . ' AND bid = ' . $bid_i);
                $array_block_fix[] = $bid_i;
            }

            $array_block_fix = array_unique($array_block_fix);
            foreach ($array_block_fix as $bid_i) {
                nv_news_fix_block($bid_i, false);
            }

            if ($rowcontent['tags'] != $rowcontent['tags_old'] or $copy) {
                $tags = explode(',', $rowcontent['tags']);
                $tags = array_map('trim', $tags);
                $tags = array_diff($tags, [
                    ''
                ]);
                $tags = array_unique($tags);
                foreach ($tags as $_tag) {
                    if (!in_array($_tag, $array_tags_old, true)) {
                        $alias_i = ($module_config[$module_name]['tags_alias']) ? get_mod_alias($_tag) : change_alias_tags($_tag);
                        $alias_i = nv_strtolower($alias_i);
                        $sth = $db->prepare('SELECT tid, alias, description, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags where alias= :alias OR FIND_IN_SET(:keyword, keywords)>0');
                        $sth->bindParam(':alias', $alias_i, PDO::PARAM_STR);
                        $sth->bindParam(':keyword', $_tag, PDO::PARAM_STR);
                        $sth->execute();

                        list($tid, $alias, $tag_i) = $sth->fetch(3);
                        if (empty($tid)) {
                            $array_insert = [];
                            $array_insert['alias'] = $alias_i;
                            $array_insert['keyword'] = $_tag;

                            $tid = $db->insert_id('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . "_tags (numnews, alias, description, image, keywords) VALUES (1, :alias, '', '', :keyword)", 'tid', $array_insert);
                        } else {
                            if ($alias != $alias_i) {
                                if (!empty($tag_i)) {
                                    $tag_arr = explode(',', $tag_i);
                                    $tag_arr[] = $_tag;
                                    $tag_i2 = implode(',', array_unique($tag_arr));
                                } else {
                                    $tag_i2 = $_tag;
                                }
                                if ($tag_i != $tag_i2) {
                                    $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET keywords= :keywords WHERE tid =' . $tid);
                                    $sth->bindParam(':keywords', $tag_i2, PDO::PARAM_STR);
                                    $sth->execute();
                                }
                            }
                            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews = numnews+1 WHERE tid = ' . $tid);
                        }

                        // insert keyword for table _tags_id
                        try {
                            $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id (id, tid, keyword) VALUES (' . $rowcontent['id'] . ', ' . (int) $tid . ', :keyword)');
                            $sth->bindParam(':keyword', $_tag, PDO::PARAM_STR);
                            $sth->execute();
                        } catch (PDOException $e) {
                            $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id SET keyword = :keyword WHERE id = ' . $rowcontent['id'] . ' AND tid=' . (int) $tid);
                            $sth->bindParam(':keyword', $_tag, PDO::PARAM_STR);
                            $sth->execute();
                        }
                        unset($array_tags_old[$tid]);
                    }
                }

                foreach ($array_tags_old as $tid => $_tag_i) {
                    if (!in_array($_tag_i, $tags, true)) {
                        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews = numnews-1 WHERE tid = ' . $tid);
                        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id = ' . $rowcontent['id'] . ' AND tid=' . $tid);
                    }
                }
            }

            // Them/xoa tac gia noi bo
            $internal_authors_new = $rowcontent['mode'] == 'edit' ? array_diff($rowcontent['internal_authors'], $rowcontent['internal_authors_old']) : $rowcontent['internal_authors'];
            $internal_authors_del = $rowcontent['mode'] == 'edit' ? array_diff($rowcontent['internal_authors_old'], $rowcontent['internal_authors']) : [];

            if (!empty($internal_authors_new)) {
                $internal_authors_new = implode(',', $internal_authors_new);
                $_query = $db->query('SELECT id, alias, pseudonym FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id IN (' . $internal_authors_new . ')');
                $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist (id, aid, alias, pseudonym) VALUES (' . $rowcontent['id'] . ', :aid, :alias, :pseudonym)');
                while ($row = $_query->fetch()) {
                    $sth->bindParam(':aid', $row['id'], PDO::PARAM_INT);
                    $sth->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
                    $sth->bindParam(':pseudonym', $row['pseudonym'], PDO::PARAM_STR);
                    $sth->execute();
                }
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_author SET numnews = numnews+1 WHERE id IN (' . $internal_authors_new . ')');
            }
            if (!empty($internal_authors_del)) {
                $internal_authors_del = implode(',', $internal_authors_del);
                $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist WHERE aid IN (' . $internal_authors_del . ') AND id = ' . $rowcontent['id']);
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_author SET numnews = numnews-1 WHERE id IN (' . $internal_authors_del . ')');
            }

            // Lưu lịch sử thay đổi trạng thái của bài viết
            Logs::saveLogStatusPost($rowcontent['id'], $rowcontent['status']);

            if (!empty($error_data)) {
                $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&id=' . $rowcontent['id'];
                $msg1 = implode('<br />', $error_data);
                $msg2 = $lang_module['content_back'];
                redriect($msg1, $msg2, $url, $module_data . '_detail');
            } else {
                $referer = $crypt->decrypt($rowcontent['referer']);
                if ($restore_id) {
                    $url = $referer ?: (NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
                    $msg1 = $lang_module['history_restore_success'];
                    $msg2 = $lang_module['content_main'] . ' ' . $module_info['custom_title'];
                    redriect($msg1, $msg2, $url, $module_data . '_detail');
                }

                if (isset($module_config['seotools']['prcservice']) and !empty($module_config['seotools']['prcservice']) and $rowcontent['status'] == 1 and $rowcontent['publtime'] < NV_CURRENTTIME + 1 and ($rowcontent['exptime'] == 0 or $rowcontent['exptime'] > NV_CURRENTTIME + 1)) {
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=rpc&id=' . $rowcontent['id'] . '&rand=' . nv_genpass());
                } else {
                    if (!empty($referer)) {
                        nv_redirect_location($referer);
                    } else {
                        $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
                        $msg1 = $lang_module['content_saveok'];
                        $msg2 = $lang_module['content_main'] . ' ' . $module_info['custom_title'];
                        redriect($msg1, $msg2, $url, $module_data . '_detail');
                    }
                }
            }
        }
    } else {
        $url = 'javascript: history.go(-1)';
        $msg1 = implode('<br />', $error);
        $msg2 = $lang_module['content_back'];
        redriect($msg1, $msg2, $url, $module_data . '_detail', 'back');
    }
    $id_block_content = $id_block_content_post;
} elseif ($rowcontent['id'] > 0) {
    $rowcontent['referer'] = $crypt->encrypt($client_info['referer']);
}

if (!empty($module_config[$module_name]['htmlhometext'])) {
    $rowcontent['hometext'] = htmlspecialchars(nv_editor_br2nl($rowcontent['hometext']));
} else {
    $rowcontent['hometext'] = nv_htmlspecialchars(nv_br2nl($rowcontent['hometext']));
}
$rowcontent['bodyhtml'] = htmlspecialchars(nv_editor_br2nl($rowcontent['bodyhtml']));
$rowcontent['alias'] = ($rowcontent['status'] == 4 and empty($rowcontent['title'])) ? '' : $rowcontent['alias'];

if (!empty($rowcontent['homeimgfile']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile'])) {
    $rowcontent['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile'];
}

$array_catid_in_row = explode(',', $rowcontent['listcatid']);

$array_topic_module = [];
$array_topic_module[0] = $lang_module['topic_sl'];
if (!empty($rowcontent['topicid'])) {
    $db->sqlreset()
        ->select('topicid, title')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_topics')
        ->where('topicid=' . $rowcontent['topicid']);
    $result = $db->query($db->sql());

    while (list($topicid_i, $title_i) = $result->fetch(3)) {
        $array_topic_module[$topicid_i] = $title_i;
    }
}

$sql = 'SELECT sourceid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources ORDER BY weight ASC';
$result = $db->query($sql);
$array_source_module = [];
$array_source_module[0] = $lang_module['sources_sl'];
while (list($sourceid_i, $title_i) = $result->fetch(3)) {
    $array_source_module[$sourceid_i] = $title_i;
}

$tdate = date('H|i', $rowcontent['publtime']);
$publ_date = date('d/m/Y', $rowcontent['publtime']);
list($phour, $pmin) = explode('|', $tdate);
if ($rowcontent['exptime'] == 0) {
    $emin = $ehour = 0;
    $exp_date = '';
} else {
    $exp_date = date('d/m/Y', $rowcontent['exptime']);
    $tdate = date('H|i', $rowcontent['exptime']);
    list($ehour, $emin) = explode('|', $tdate);
}

if ($rowcontent['status'] == 1 and $rowcontent['publtime'] > NV_CURRENTTIME) {
    $array_cat_check_content = $array_cat_pub_content;
} elseif ($rowcontent['status'] == 1) {
    $array_cat_check_content = $array_cat_edit_content;
} else {
    $array_cat_check_content = $array_cat_add_content;
}

if (empty($array_cat_check_content)) {
    $redirect = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat';
    $contents = nv_theme_alert($lang_module['note_cat_title'], $lang_module['note_cat_content'], 'warning', $redirect, $lang_module['categories']);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$lang_global['title_suggest_max'] = sprintf($lang_global['length_suggest_max'], 65);
$lang_global['description_suggest_max'] = sprintf($lang_global['length_suggest_max'], 160);

$rowcontent['style_content_bodytext_required'] = $rowcontent['external_link'] ? 'hidden' : '';

$xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('rowcontent', $rowcontent);
$xtpl->assign('ISCOPY', $copy);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_DATA', $module_data);
$xtpl->assign('OP', $op);

$xtpl->assign('ERROR_BODYTEXT', str_replace('\'', '\\\'', $lang_module['error_bodytext']));
$xtpl->assign('ERROR_CAT', str_replace('\'', '\\\'', $lang_module['error_cat']));

$xtpl->assign('RESTORE_ID', $restore_id);
$xtpl->assign('RESTORE_HASH', $restore_hash);

if ($rowcontent['id'] > 0) {
    $op = '';
    $lang_module['save_temp'] = $lang_module['save'];
}
$xtpl->assign('LANG', $lang_module);

$xtpl->assign('module_name', $module_name);

foreach ($global_array_cat as $catid_i => $array_value) {
    if (defined('NV_IS_ADMIN_MODULE')) {
        $check_show = 1;
    } else {
        $array_cat = GetCatidInParent($catid_i);
        $check_show = array_intersect($array_cat, $array_cat_check_content);
    }
    /*
     * Thêm bài viết không hiển thị chuyên mục bị đình chỉ hoạt động
     * Sửa bài viết hiển thị chuyên mục bị đình chỉ hoạt động với:
     * - Bài viết đang thuộc chuyên mục thì enable
     * - Bài viết chưa nằm trong chuyên mục thì disable
     */
    if (!empty($check_show) and ($rowcontent['id'] > 0 or in_array((int) $array_value['status'], array_map('intval', $global_code_defined['cat_visible_status']), true))) {
        $space = (int) ($array_value['lev']) * 30;
        $catiddisplay = (sizeof($array_catid_in_row) > 1 and (in_array((int) $catid_i, array_map('intval', $array_catid_in_row), true))) ? '' : ' display: none;';
        $temp = [
            'catid' => $catid_i,
            'space' => $space,
            'title' => $array_value['title'],
            'disabled' => (!in_array((int) $catid_i, array_map('intval', $array_cat_check_content), true) or (!in_array((int) $array_value['status'], array_map('intval', $global_code_defined['cat_visible_status']), true) and !in_array((int) $catid_i, array_map('intval', $array_catid_in_row), true))) ? ' disabled="disabled"' : '',
            'checked' => (in_array((int) $catid_i, array_map('intval', $array_catid_in_row), true)) ? ' checked="checked"' : '',
            'catidchecked' => ($catid_i == $rowcontent['catid']) ? ' checked="checked"' : '',
            'catiddisplay' => $catiddisplay
        ];
        $xtpl->assign('CATS', $temp);
        $xtpl->parse('main.catid');
    }
}

$xtpl->assign('UPLOADS_DIR_USER', $uploads_dir_user);
$xtpl->assign('UPLOAD_CURRENT', $currentpath);
$xtpl->assign('NUMFILE', count($rowcontent['files']));

// Attach files
if (!empty($rowcontent['files'])) {
    $rowcontent['files'] = array_filter($rowcontent['files']);
    foreach ($rowcontent['files'] as $_id => $_file) {
        if (!empty($_file)) {
            $xtpl->assign('FILEUPL', [
                'id' => $_id,
                'value' => (!preg_match('/^http*/', $_file)) ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $_file : $_file
            ]);
            $xtpl->parse('main.files');
        }
    }
} else {
    $xtpl->assign('FILEUPL', [
        'id' => 0,
        'value' => ''
    ]);
    $xtpl->parse('main.files');
}

// Copyright
$checkcop = ($rowcontent['copyright']) ? ' checked="checked"' : '';
$xtpl->assign('checkcop', $checkcop);

// topic
foreach ($array_topic_module as $topicid_i => $title_i) {
    $sl = ($topicid_i == $rowcontent['topicid']) ? ' selected="selected"' : '';
    $xtpl->assign('topicid', $topicid_i);
    $xtpl->assign('topic_title', $title_i);
    $xtpl->assign('sl', $sl);
    $xtpl->parse('main.rowstopic');
}

// position images
foreach ($array_imgposition as $id_imgposition => $title_imgposition) {
    $sl = ($id_imgposition == $rowcontent['imgposition']) ? ' selected="selected"' : '';
    $xtpl->assign('id_imgposition', $id_imgposition);
    $xtpl->assign('title_imgposition', $title_imgposition);
    $xtpl->assign('posl', $sl);
    $xtpl->parse('main.looppos');
}

// time update
$xtpl->assign('publ_date', $publ_date);
$select = '';
for ($i = 0; $i <= 23; ++$i) {
    $select .= '<option value="' . $i . '"' . (($i == $phour) ? ' selected="selected"' : '') . '>' . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('phour', $select);
$select = '';
for ($i = 0; $i < 60; ++$i) {
    $select .= '<option value="' . $i . '"' . (($i == $pmin) ? ' selected="selected"' : '') . '>' . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('pmin', $select);

// time exp
$xtpl->assign('exp_date', $exp_date);
$select = '';
for ($i = 0; $i <= 23; ++$i) {
    $select .= '<option value="' . $i . '"' . (($i == $ehour) ? ' selected="selected"' : '') . '>' . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('ehour', $select);
$select = '';
for ($i = 0; $i < 60; ++$i) {
    $select .= '<option value="' . $i . '"' . (($i == $emin) ? ' selected="selected"' : '') . '>' . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('emin', $select);

// allowed comm
$allowed_comm = array_map('intval', explode(',', $rowcontent['allowed_comm']));
foreach ($groups_list as $_group_id => $_title) {
    $xtpl->assign('ALLOWED_COMM', [
        'value' => $_group_id,
        'checked' => in_array((int) $_group_id, $allowed_comm, true) ? ' checked="checked"' : '',
        'title' => $_title
    ]);
    $xtpl->parse('main.allowed_comm');
}
if ($module_config[$module_name]['allowed_comm'] != '-1') {
    $xtpl->parse('main.content_note_comm');
}

// Lua chon Layout
foreach ($layout_array as $value) {
    $value = preg_replace($global_config['check_op_layout'], '\\1', $value);
    $xtpl->assign('LAYOUT_FUNC', [
        'key' => $value,
        'selected' => ($rowcontent['layout_func'] == $value) ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.layout_func');
}

// source
$select = '';
foreach ($array_source_module as $sourceid_i => $source_title_i) {
    $source_sl = ($sourceid_i == $rowcontent['sourceid']) ? ' selected="selected"' : '';
    $select .= '<option value="' . $sourceid_i . '" ' . $source_sl . '>' . $source_title_i . "</option>\n";
}
$xtpl->assign('sourceid', $select);

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $has_editor = true;
} else {
    $has_editor = false;
}
if (!empty($module_config[$module_name]['htmlhometext']) and $has_editor) {
    $editshometext = nv_aleditor('hometext', '100%', '200px', $rowcontent['hometext'], '', $uploads_dir_user, $currentpath);
} else {
    $editshometext = '<textarea class="form-control" style="width: 100%" name="hometext" id="' . $module_name . '_hometext" rows="5">' . $rowcontent['hometext'] . '</textarea>';
}
if ($has_editor) {
    $edits = nv_aleditor('bodyhtml', '100%', '400px', $rowcontent['bodyhtml'], '', $uploads_dir_user, $currentpath);
} else {
    $edits = "<textarea class=\"form-control\" style=\"width: 100%\" name=\"bodyhtml\" id=\"' . $module_data . '_bodyhtml\" rows=\"15\">" . $rowcontent['bodyhtml'] . '</textarea>';
}

$shtm = '';
if (sizeof($array_block_cat_module)) {
    foreach ($array_block_cat_module as $bid_i => $bid_title) {
        $xtpl->assign('BLOCKS', [
            'title' => $bid_title,
            'bid' => $bid_i,
            'checked' => in_array((int) $bid_i, array_map('intval', $id_block_content), true) ? 'checked="checked"' : ''
        ]);
        $xtpl->parse('main.block_cat.loop');
    }
    $xtpl->parse('main.block_cat');
}

if (!empty($rowcontent['keywords'])) {
    $_array = explode(',', $rowcontent['keywords']);
    foreach ($_array as $_v) {
        $xtpl->assign('KEYWORDS', $_v);
        $xtpl->parse('main.keywords');
    }
}

if (!empty($rowcontent['tags'])) {
    $_array = explode(',', $rowcontent['tags']);
    foreach ($_array as $_v) {
        $xtpl->assign('TAGS', $_v);
        $xtpl->parse('main.tags');
    }
}

if (!empty($rowcontent['internal_authors'])) {
    foreach ($rowcontent['internal_authors'] as $_aid) {
        $xtpl->assign('INTERNAL_AUTHORS', $internal_authors_list[$_aid]);
        $xtpl->parse('main.internal_authors');
    }
}

$archive_checked = ($rowcontent['archive']) ? ' checked="checked"' : '';
$xtpl->assign('archive_checked', $archive_checked);
$inhome_checked = ($rowcontent['inhome']) ? ' checked="checked"' : '';
$xtpl->assign('inhome_checked', $inhome_checked);
$allowed_rating_checked = ($rowcontent['allowed_rating']) ? ' checked="checked"' : '';
$xtpl->assign('allowed_rating_checked', $allowed_rating_checked);
$external_link_checked = ($rowcontent['external_link']) ? ' checked="checked"' : '';
$xtpl->assign('external_link_checked', $external_link_checked);
$allowed_send_checked = ($rowcontent['allowed_send']) ? ' checked="checked"' : '';
$xtpl->assign('allowed_send_checked', $allowed_send_checked);
$allowed_print_checked = ($rowcontent['allowed_print']) ? ' checked="checked"' : '';
$xtpl->assign('allowed_print_checked', $allowed_print_checked);
$allowed_save_checked = ($rowcontent['allowed_save']) ? ' checked="checked"' : '';
$xtpl->assign('allowed_save_checked', $allowed_save_checked);
$instant_active_checked = ($rowcontent['instant_active']) ? ' checked="checked"' : '';
$xtpl->assign('instant_active_checked', $instant_active_checked);
$xtpl->assign('instant_creatauto_checked', empty($rowcontent['instant_creatauto']) ? '' : ' checked="checked"');

$xtpl->assign('edit_bodytext', $edits);
$xtpl->assign('edit_hometext', $editshometext);

if (!empty($error)) {
    $xtpl->assign('error', implode('<br />', $error));
    $xtpl->parse('main.error');
}

// Thông báo vượt quá hệ thống lớn
if (!$is_submit_form and $total_news_current == NV_MIN_MEDIUM_SYSTEM_ROWS and $rowcontent['mode'] == 'add') {
    $xtpl->assign('LARGE_SYS_MESSAGE', sprintf($lang_module['large_sys_message'], number_format($total_news_current, 0, ',', '.')));
    $xtpl->parse('main.large_sys_note');
}

$status_save = true;

// Gioi han quyen
if ($rowcontent['status'] == 1 and $rowcontent['id'] > 0) {
    $xtpl->parse('main.status_save');
} else {
    $xtpl->parse('main.status_4');
    if (!empty($array_cat_pub_content)) {
        // neu co quyen dang bai
        $xtpl->parse('main.status_1');
    }
    if (!empty($array_censor_content) and $rowcontent['status'] != 8) {
        // neu co quyen duyet bai thi
        $xtpl->parse('main.status_8');
    }

    if ($rowcontent['status'] != 5) {
        $xtpl->parse('main.status_5');
    }
}

if (empty($rowcontent['alias'])) {
    $xtpl->parse('main.getalias');
}

if ($module_config[$module_name]['auto_tags']) {
    $xtpl->parse('main.auto_tags');
}
if (!empty($module_config[$module_name]['instant_articles_active'])) {
    $xtpl->parse('main.instant_articles_active');
}

if ($rowcontent['mode'] == 'edit') {
    $xtpl->parse('main.holdon_edit');
}

if (!empty($module_config[$module_name]['allowed_rating'])) {
    $xtpl->parse('main.allowed_rating');
} else {
    $xtpl->parse('main.not_allowed_rating');
}

// Tự động submit form khôi phục
if ($restore_id and !$is_submit_form) {
    $xtpl->parse('main.restore_auto');
    $xtpl->parse('main.restore_note');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
