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

use NukeViet\Module\news\Shared\Logs;
use NukeViet\Module\news\Shared\Posts;

// Xuất ajax các dòng sự kiện
if ($nv_Request->isset_request('get_topic_json', 'post')) {
    $respon = [
        'results' => [],
        'pagination' => [
            'more' => false
        ]
    ];

    $q = $nv_Request->get_title('q', 'post', '');
    $page = $nv_Request->get_page('page', 'post', 1);
    $per_page = 20;

    if (nv_strlen($q) < 2 or !csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput($respon);
    }

    $stmt = $db->prepare('SELECT COUNT(topicid) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE title LIKE :q_title');
    $stmt->bindValue(':q_title', '%' . $q . '%', PDO::PARAM_STR);
    $stmt->execute();
    $num_items = $stmt->fetchColumn();

    $stmt = $db->prepare('SELECT topicid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE title LIKE :q_title ORDER BY weight ASC LIMIT :limit OFFSET :offset');
    $stmt->bindValue(':q_title', '%' . $q . '%', PDO::PARAM_STR);
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
    $stmt->execute();

    if ($page == 1) {
        $respon['results'][] = [
            'id' => 0,
            'text' => $nv_Lang->getModule('admin_topic_slnone')
        ];
    }
    while ($_row = $stmt->fetch()) {
        $respon['results'][] = [
            'id' => (int) $_row['topicid'],
            'text' => nv_unhtmlspecialchars($_row['title'])
        ];
    }
    $stmt->closeCursor();

    $respon['pagination']['more'] = ($page * $per_page) < $num_items;
    nv_jsonOutput($respon);
}

// Xuất ajax tin liên quan
if ($nv_Request->isset_request('get_article_json', 'post')) {
    $respon = [
        'results' => [],
        'pagination' => [
            'more' => false
        ]
    ];

    $id = $nv_Request->get_absint('id', 'post', 0);
    $q = $nv_Request->get_title('q', 'post', '');
    $page = $nv_Request->get_page('page', 'post', 1);
    $per_page = 20;

    if (nv_strlen($q) < 2 or !csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput($respon);
    }

    $stmt = $db->prepare('SELECT COUNT(id) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE title LIKE :q_title AND id != :id');
    $stmt->bindValue(':q_title', '%' . $q . '%', PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $num_items = $stmt->fetchColumn();

    $stmt = $db->prepare('SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE title LIKE :q_title AND id != :id ORDER BY ' . $order_articles_by . ' DESC LIMIT :limit OFFSET :offset');
    $stmt->bindValue(':q_title', '%' . $q . '%', PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
    $stmt->execute();

    while ($_row = $stmt->fetch()) {
        $respon['results'][] = [
            'id' => (int) $_row['id'],
            'text' => nv_unhtmlspecialchars($_row['title'])
        ];
    }
    $stmt->closeCursor();

    $respon['pagination']['more'] = ($page * $per_page) < $num_items;
    nv_jsonOutput($respon);
}

$is_submit_form = (($nv_Request->get_int('save', 'post') == 1 and csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) ? true : false);
$is_auto_save = ($is_submit_form and $nv_Request->get_int('ajax_content', 'post', 0) == 1) ? true : false;

// Kiểm tra xem đang sửa có bị cướp quyền hay không, cập nhật thêm thời gian chỉnh sửa
if ($nv_Request->isset_request('id', 'post') and $nv_Request->isset_request('check_edit', 'post') and $is_submit_form) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE new_id = :id AND type = 0');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($row_tmp = $stmt->fetch()) {
        if ($row_tmp['admin_id'] == $admin_info['admin_id']) {
            $stmt_up = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET time_late = :time, ip = :ip WHERE id = :tmp_id');
            $stmt_up->bindValue(':time', NV_CURRENTTIME, PDO::PARAM_INT);
            $stmt_up->bindValue(':ip', $client_info['ip'], PDO::PARAM_STR);
            $stmt_up->bindValue(':tmp_id', $row_tmp['id'], PDO::PARAM_INT);
            $stmt_up->execute();
        } else {
            $stmt_user = $db->prepare('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = :uid');
            $stmt_user->bindValue(':uid', $row_tmp['admin_id'], PDO::PARAM_INT);
            $stmt_user->execute();
            $_username = $stmt_user->fetchColumn();
            nv_jsonOutput([
                'status' => 'compromised',
                'mess' => $nv_Lang->getModule('dulicate_edit_takeover', $_username, nv_datetime_format($row_tmp['time_edit']))
            ]);
        }
    }
    $stmt->closeCursor();
}

// Lấy keywords từ nội dung bài viết
if ($nv_Request->isset_request('getKeywordsFromContent', 'post') and csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
    $content = $nv_Request->get_string('content', 'post', '');
    $keywords = nv_get_mod_tags($content);
    $size = count($keywords);
    if ($size < 20) {
        $keywords = array_merge($keywords, nv_get_keywords($content, 20 - $size, true));
    }
    $keywords = array_unique($keywords);
    $keywords = array_values($keywords);
    nv_jsonOutput($keywords);
}

if (!empty($global_config['over_capacity']) and !defined('NV_IS_GODADMIN')) {
    $contents = nv_theme_alert('', $nv_Lang->getGlobal('error_upload_over_capacity1'));
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

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

$structure_upload = $module_config[$module_name]['structure_upload'] ?? 'Ym';
$currentpath = $array_structure_image[$structure_upload] ?? '';

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
                        $stmt = $db->prepare('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . '_dir (dirname, time) VALUES (:dirname, 0)');
                        $stmt->bindValue(':dirname', NV_UPLOADS_DIR . '/' . $cp . $p, PDO::PARAM_STR);
                        $stmt->execute();
                    } catch (Throwable $e) {
                        trigger_error($e);
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
$stmt = $db->prepare('SELECT bid, adddefault, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat ORDER BY weight ASC');
$stmt->execute();
while ($_row = $stmt->fetch()) {
    $array_block_cat_module[$_row['bid']] = $_row['title'];
    if ($_row['adddefault']) {
        $id_block_content[] = (int) $_row['bid'];
    }
}
$stmt->closeCursor();

$catid = $nv_Request->get_int('catid', 'get', 0);
$parentid = $nv_Request->get_int('parentid', 'get', 0);
$array_imgposition = [
    0 => $nv_Lang->getModule('imgposition_0'),
    1 => $nv_Lang->getModule('admin_imgposition_1'),
    2 => $nv_Lang->getModule('imgposition_2')
];
$total_news_current = nv_get_mod_countrows();
$restore_id = $nv_Request->get_absint('restore', 'post,get', 0);
$restore_hash = $nv_Request->get_title('restorehash', 'post,get', '');

$langues = nv_parse_ini_file(NV_ROOTDIR . '/includes/ini/langs.ini', true);
$langues = ['x-default' => ['name' => $nv_Lang->getModule('lang_default')]] + $langues;

$rowcontent = [
    'id' => '',
    'catid' => $catid,
    'listcatid' => $catid . ',' . $parentid,
    'topicid' => '',
    'topictext' => '',
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
    'imgposition' => $module_config[$module_name]['imgposition'] ?? 1,
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
    'auto_nav' => 0,
    'hitstotal' => 0,
    'hitscm' => 0,
    'total_rating' => 0,
    'click_rating' => 0,
    'layout_func' => '',
    'tags' => '',
    'tags_old' => '',
    'keywords' => '',
    'mode' => 'add',
    'voicedata' => [],
    'group_view' => '',
    'localversions' => [],
    'related_ids' => '',
    'related_pos' => 2,
    'uuid' => '',
    'schema_type' => $module_config[$module_name]['schema_type'],
    'reject_reason' => ''
];

$page_title = $nv_Lang->getModule('content_add');
$error = [];
$groups_list = nv_groups_list();
$array_tags_old = [];
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
    $stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = :id');
    $stmt->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
    $stmt->execute();
    $rowcontent = $stmt->fetch();
    $stmt->closeCursor();

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
                        // Toàn quyền
                        ++$check_edit;
                    } else {
                        if ($array_cat_admin[$admin_id][$catid_i]['edit_content'] == 1) {
                            // Có quyền sửa
                            ++$check_edit;
                        } elseif ($array_cat_admin[$admin_id][$catid_i]['app_content'] == 1 and ($status == 5 or $status == 6)) {
                            // Quyền duyệt bài và bài đang chờ duyệt, từ chối duyệt
                            ++$check_edit;
                        } elseif ($array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1 and ($status == 0 or $status == 8 or $status == 9 or $status == 2)) {
                            // Quyền đăng bài và bài bài đang tắt, chờ đăng, chuyển đăng, từ chối đăng
                            ++$check_edit;
                        } elseif (($status == 0 or $status == 4 or $status == 5 or $status == 6) and $rowcontent['admin_id'] == $admin_id) {
                            // Quyền tạo bài và bài đang tắt, nháp, chuyển duyệt, từ chối duyệt
                            ++$check_edit;
                        }
                    }
                }
            }
            if ($check_edit == count($arr_catid)) {
                $check_permission = true;
            }
        }
        $rowcontent['old_listcatid'] = $arr_catid;
    }

    // Không có quyền sửa thì kết thúc
    if (!$check_permission) {
        if ($is_auto_save) {
            nv_jsonOutput([
                'status' => 'not_allowed',
                'mess' => $nv_Lang->getModule('action_not_allowed')
            ]);
        }
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    $page_title = $nv_Lang->getModule('content_edit');
    $rowcontent['topictext'] = '';
    $rowcontent['files'] = '';

    // Lấy các file đính kèm
    $stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail WHERE id = :id');
    $stmt->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
    $stmt->execute();
    $body_contents = $stmt->fetch();
    $stmt->closeCursor();

    $body_contents['localversions'] = !empty($body_contents['localization']) ? json_decode($body_contents['localization'], true) : [];
    $rowcontent = array_merge($rowcontent, $body_contents);
    unset($body_contents);

    // Lấy các tag của bài viết
    $stmt = $db->prepare('SELECT tid, keyword FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id = :id ORDER BY keyword ASC');
    $stmt->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $array_tags_old[$row['tid']] = $row['keyword'];
    }
    $rowcontent['tags'] = implode(',', $array_tags_old);
    $rowcontent['tags_old'] = $rowcontent['tags'];
    $stmt->closeCursor();

    // Lấy danh sach tac gia của bài viết
    $rowcontent['internal_authors'] = [];
    $rowcontent['internal_authors_old'] = [];
    $stmt = $db->prepare('SELECT aid, pseudonym FROM ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist WHERE id = :id ORDER BY alias ASC');
    $stmt->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $rowcontent['internal_authors'][] = $row['aid'];
        if (!$copy) {
            $rowcontent['internal_authors_old'][] = $row['aid'];
        }
        $internal_authors_list[$row['aid']] = [
            'id' => $row['aid'],
            'pseudonym' => $row['pseudonym']
        ];
    }
    $stmt->closeCursor();

    // Lấy và đè lại thông tin sẽ khôi phục
    $restore_data = [];
    if ($restore_id) {
        $stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row_histories WHERE new_id = :new_id AND id = :restore_id');
        $stmt->bindValue(':new_id', $rowcontent['id'], PDO::PARAM_INT);
        $stmt->bindValue(':restore_id', $restore_id, PDO::PARAM_INT);
        $stmt->execute();
        $restore_data = $stmt->fetch();
        $stmt->closeCursor();

        if (empty($restore_data) or !csrf_check($restore_hash, get_article_restore_csrf_key($rowcontent['id'], $restore_id, $restore_data['historytime']))) {
            nv_error404();
        }
        unset($restore_data['id'], $restore_data['new_id'], $restore_data['admin_id'], $restore_data['changed_fields']);

        $rowcontent['internal_authors'] = '';
        $rowcontent = array_merge($rowcontent, $restore_data);

        // Lấy lại tác giả thuộc quyền quản lý
        $internal_authors = $rowcontent['internal_authors'];
        $rowcontent['internal_authors'] = [];
        if (!empty($internal_authors)) {
            $internal_authors_ids = array_map('intval', explode(',', $internal_authors));
            $placeholders = implode(',', array_fill(0, count($internal_authors_ids), '?'));
            $stmt = $db->prepare('SELECT id, pseudonym FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id IN (' . $placeholders . ') ORDER BY alias ASC');
            foreach ($internal_authors_ids as $k => $id) {
                $stmt->bindValue(($k + 1), $id, PDO::PARAM_INT);
            }
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $rowcontent['internal_authors'][] = $row['id'];
                if (!$copy) {
                    $rowcontent['internal_authors_old'][] = $row['id'];
                }
                $internal_authors_list[$row['id']] = [
                    'id' => $row['id'],
                    'pseudonym' => $row['pseudonym']
                ];
            }
            $stmt->closeCursor();
        }
        unset($internal_authors);
    }
    $rowcontent['files'] = !empty($rowcontent['files']) ? explode(',', $rowcontent['files']) : [];
    $rowcontent['voicedata'] = !empty($rowcontent['voicedata']) ? json_decode($rowcontent['voicedata'], true) : [];

    // Các nhóm tin của bài viết
    $id_block_content = [];
    $stmt = $db->prepare('SELECT bid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE id = :id');
    $stmt->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
    $stmt->execute();
    while ($_row = $stmt->fetch()) {
        $id_block_content[] = (int) $_row['bid'];
    }
    $stmt->closeCursor();

    // Xóa thông báo của hệ thống về bài viết
    if (empty($rowcontent['status'])) {
        nv_status_notification(NV_LANG_DATA, $module_name, 'post_queue', $rowcontent['id']);
    }

    // Xác định lại đường dẫn upload theo đường dẫn của ảnh minh họa bài viết
    if (!empty($rowcontent['homeimgfile']) and !nv_is_url($rowcontent['homeimgfile']) and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile'], NV_UPLOADS_DIR . '/' . $module_upload)) {
        $currentpath = dirname(NV_UPLOADS_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile']);
    }

}

// Tiếp tục từ bản nháp
$draft_id = $nv_Request->get_absint('draft_id', 'get', 0);
if ($draft_id) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE id = :draft_id AND type = 1 AND admin_id = :admin_id';
    if ($rowcontent['mode'] === 'add') {
        $sql .= ' AND new_id = 0';
    } else {
        $sql .= ' AND new_id = :new_id';
    }
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':draft_id', $draft_id, PDO::PARAM_INT);
    $stmt->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
    if ($rowcontent['mode'] !== 'add') {
        $stmt->bindValue(':new_id', $rowcontent['id'], PDO::PARAM_INT);
    }
    $stmt->execute();
    $draft = $stmt->fetch() ?: [];
    $stmt->closeCursor();

    $draft['properties'] = !empty($draft['properties']) ? json_decode($draft['properties'], true) : [];
    if (empty($draft['properties']) or !is_array($draft['properties'])) {
        nv_error404();
    }
    $rowcontent = array_merge($rowcontent, $draft['properties']);
    $rowcontent['uuid'] = $draft['uuid'];
    $rowcontent['draft_id'] = $draft_id;
    unset($draft);
}

// Loại bỏ HTML khỏi giới thiệu ngắn gọn nếu không cho phép HTML
if (empty($module_config[$module_name]['htmlhometext'])) {
    $rowcontent['hometext'] = strip_tags($rowcontent['hometext'], 'br');
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

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'dnumber', 'nv_number_format');
$tpl->registerPlugin('modifier', 'text_split', 'text_split');
$tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
$tpl->setTemplateDir(get_module_tpl_dir('content.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('MODULE_DATA', $module_data);
$tpl->assign('OP', $op);

/*
 * Kiểm tra bị chiếm quyền sửa hoặc cố tình sửa bài của người đang sửa
 * Kiểm tra nếu đang sửa bài, đang thêm bài hoặc copy bài thì không kiểm tra
 * Đưa lên trước khi submit để tránh trường hợp đang sửa bài bị người khác chiếm quyền
 * sau đó tiếp tục nhấn submit thì dữ liệu vẫn được lưu
 */
if ($rowcontent['mode'] == 'edit') {
    $stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE new_id = :id AND type = 0');
    $stmt->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
    $stmt->execute();
    $row_tmp = $stmt->fetch();
    $stmt->closeCursor();

    if ($row_tmp) {
        // Xác định người đang sửa
        $stmt_user = $db->prepare('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = :uid');
        $stmt_user->bindValue(':uid', $row_tmp['admin_id'], PDO::PARAM_INT);
        $stmt_user->execute();
        $_username = $stmt_user->fetchColumn();
        $stmt_user->closeCursor();

        // Kiểm tra nếu có người đang sửa
        if ($row_tmp['admin_id'] == $admin_info['admin_id']) {
            // Cập nhật thời gian sửa cuối
            $stmt_up = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET time_late = :time, ip = :ip WHERE id = :tmp_id');
            $stmt_up->bindValue(':time', NV_CURRENTTIME, PDO::PARAM_INT);
            $stmt_up->bindValue(':ip', $client_info['ip'], PDO::PARAM_STR);
            $stmt_up->bindValue(':tmp_id', $row_tmp['id'], PDO::PARAM_INT);
            $stmt_up->execute();
        } elseif ($row_tmp['time_late'] < (NV_CURRENTTIME - $global_code_defined['edit_timeout']) or empty($_username)) {
            /*
             * Cho phép sửa nếu:
             * - Người đang sửa 3 phút không thao tác đến
             * - Không tồn tại thành viên nữa (có thể bị xóa tài khoản)
             */
            $stmt_up = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET admin_id = :admin_id, time_edit = :time_edit, time_late = :time_late, ip = :ip WHERE id = :tmp_id');
            $stmt_up->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
            $stmt_up->bindValue(':time_edit', NV_CURRENTTIME, PDO::PARAM_INT);
            $stmt_up->bindValue(':time_late', NV_CURRENTTIME, PDO::PARAM_INT);
            $stmt_up->bindValue(':ip', $client_info['ip'], PDO::PARAM_STR);
            $stmt_up->bindValue(':tmp_id', $row_tmp['id'], PDO::PARAM_INT);
            $stmt_up->execute();
        } else {
            $link_takeover = '';
            $stmt_lev = $db->prepare('SELECT lev FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id = :admin_id');
            $stmt_lev->bindValue(':admin_id', $row_tmp['admin_id'], PDO::PARAM_INT);
            $stmt_lev->execute();
            $_authors_lev = $stmt_lev->fetchColumn();
            $stmt_lev->closeCursor();

            if ($admin_info['level'] < $_authors_lev) {
                // Có quyền chiếm
                $takeover = md5($rowcontent['id'] . '_takeover_' . $csrf_key);
                if ($takeover == $nv_Request->get_title('takeover', 'get', '')) {
                    $stmt_up = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET admin_id = :admin_id, time_edit = :time_edit, time_late = :time_late, ip = :ip WHERE id = :tmp_id');
                    $stmt_up->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
                    $stmt_up->bindValue(':time_edit', NV_CURRENTTIME, PDO::PARAM_INT);
                    $stmt_up->bindValue(':time_late', NV_CURRENTTIME, PDO::PARAM_INT);
                    $stmt_up->bindValue(':ip', $client_info['ip'], PDO::PARAM_STR);
                    $stmt_up->bindValue(':tmp_id', $row_tmp['id'], PDO::PARAM_INT);
                    $stmt_up->execute();
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&id=' . $rowcontent['id'] . '&rand=' . nv_genpass());
                }
                $message = $nv_Lang->getModule('dulicate_edit_admin', $rowcontent['title'], $_username, date('H:i d/m/Y', $row_tmp['time_edit']));
                $link_takeover = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&id=' . $rowcontent['id'] . '&takeover=' . $takeover;
            } else {
                // Thông báo không có quyền sửa.
                $message = $nv_Lang->getModule('dulicate_edit', $rowcontent['title'], $_username, date('H:i d/m/Y', $row_tmp['time_edit']));
            }

            $tpl->assign('MESSAGE', $message);
            $tpl->assign('LINK_TAKEOVER', $link_takeover);

            $contents = $tpl->fetch('content-takeover.tpl');

            include NV_ROOTDIR . '/includes/header.php';
            echo nv_admin_theme($contents);
            include NV_ROOTDIR . '/includes/footer.php';
        }
    } elseif (!$is_submit_form) {
        // Khi bắt đầu sửa bài thì lưu thông tin người sửa
        // Không lưu nếu submit
        $stmt_ins = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tmp (type, new_id, admin_id, time_edit, time_late, ip) VALUES (0, :id, :admin_id, :time_edit, :time_late, :ip)');
        $stmt_ins->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
        $stmt_ins->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
        $stmt_ins->bindValue(':time_edit', NV_CURRENTTIME, PDO::PARAM_INT);
        $stmt_ins->bindValue(':time_late', NV_CURRENTTIME, PDO::PARAM_INT);
        $stmt_ins->bindValue(':ip', $client_info['ip'], PDO::PARAM_STR);
        $stmt_ins->execute();
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
    } elseif ($nv_Request->isset_request('status6', 'post')) {
        // Từ chối duyệt bài
        $rowcontent['status'] = 6;
    } elseif ($nv_Request->isset_request('status9', 'post')) {
        // Từ chối đăng bài
        $rowcontent['status'] = 9;
    } else {
        $rowcontent['status'] = 4;
    }

    $message_error_show = $nv_Lang->getModule('permissions_pub_error');
    if ($rowcontent['status'] == 1) {
        $array_cat_check_content = array_map('intval', $array_cat_pub_content);
    } elseif ($rowcontent['status'] == 1 and $rowcontent['publtime'] <= NV_CURRENTTIME) {
        $array_cat_check_content = array_map('intval', $array_cat_edit_content);
    } elseif ($rowcontent['status'] == 0) {
        $array_cat_check_content = array_map('intval', $array_censor_content);
        $message_error_show = $nv_Lang->getModule('permissions_sendspadmin_error');
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
            $stmt = $db->prepare('SELECT topicid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE title = :title');
            $stmt->bindValue(':title', $rowcontent['topictext'], PDO::PARAM_STR);
            $stmt->execute();
            $rowcontent['topicid'] = (int) $stmt->fetchColumn();
            $stmt->closeCursor();
        }
    }
    $rowcontent['author'] = $nv_Request->get_title('author', 'post', '');
    $rowcontent['internal_authors'] = $nv_Request->get_typed_array('internal_authors', 'post', 'int', []);
    $rowcontent['sourcetext'] = $nv_Request->get_title('sourcetext', 'post', '');
    $rowcontent['publtime'] = nv_d2u_post($nv_Request->get_title('publ_date', 'post', ''));
    $rowcontent['publtime'] = $rowcontent['publtime'] ?: NV_CURRENTTIME;
    $rowcontent['exptime'] = nv_d2u_post($nv_Request->get_title('exp_date', 'post', ''));
    $rowcontent['archive'] = $nv_Request->get_int('archive', 'post', 0);
    if ($rowcontent['archive'] > 0) {
        $rowcontent['archive'] = ($rowcontent['exptime'] > NV_CURRENTTIME) ? 1 : 2;
    }
    $rowcontent['title'] = $nv_Request->get_title('title', 'post', '');
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

    // Xử lý giọng đọc
    $rowcontent['voicedata'] = [];
    foreach ($global_array_voices as $voice) {
        $voice_file = $nv_Request->get_title('voice_' . $voice['id'], 'post', '');
        if (!empty($voice_file)) {
            if (nv_is_url($voice_file)) {
                $rowcontent['voicedata'][$voice['id']] = $voice_file;
            } elseif (nv_is_file($voice_file, NV_UPLOADS_DIR . '/' . $module_upload) === true) {
                $rowcontent['voicedata'][$voice['id']] = substr($voice_file, strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
            }
        }
    }

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
    $rowcontent['homeimgalt'] = $nv_Request->get_title('homeimgalt', 'post', '');
    $rowcontent['imgposition'] = $nv_Request->get_int('imgposition', 'post', 0);
    if (!array_key_exists($rowcontent['imgposition'], $array_imgposition)) {
        $rowcontent['imgposition'] = 1;
    }
    // Lua chon Layout
    $rowcontent['layout_func'] = $nv_Request->get_title('layout_func', 'post', '');
    if (!in_array('layout.' . $rowcontent['layout_func'] . '.tpl', $layout_array)) {
        $rowcontent['layout_func'] = '';
    }

    // Tự động tạo mục lục
    $rowcontent['auto_nav'] = $nv_Request->get_int('auto_nav', 'post', 0);

    $rowcontent['titlesite'] = $nv_Request->get_title('titlesite', 'post', '');
    $rowcontent['description'] = $nv_Request->get_title('description', 'post', '');
    $rowcontent['bodyhtml'] = $nv_Request->get_editor('bodyhtml', '', NV_ALLOWED_HTML_TAGS);
    $rowcontent['copyright'] = (int) $nv_Request->get_bool('copyright', 'post');
    $rowcontent['inhome'] = (int) $nv_Request->get_bool('inhome', 'post');

    $_groups_post = $nv_Request->get_typed_array('allowed_comm', 'post', 'int', []);
    $rowcontent['allowed_comm'] = !empty($_groups_post) ? implode(',', nv_groups_post(array_intersect($_groups_post, array_keys($groups_list)))) : '';

    $_groups_post = $nv_Request->get_typed_array('group_view', 'post', 'int', []);
    $rowcontent['group_view'] = !empty($_groups_post) ? implode(',', nv_groups_post(array_intersect($_groups_post, array_keys($groups_list)))) : '';

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

    $tags = $nv_Request->get_typed_array('tags', 'post', 'title', []);
    $rowcontent['tags'] = !empty($tags) ? implode(',', $tags) : '';

    // Phien ban ngon ngu
    $rowcontent['localversions'] = [];
    $enable_localization = (int) $nv_Request->get_bool('enable_localization', 'post');
    if ($enable_localization) {
        $locallangs = $nv_Request->get_typed_array('locallang', 'post', 'title', []);
        $locallinks = $nv_Request->get_typed_array('locallink', 'post', 'title', []);

        foreach($locallangs as $key => $lg) {
            if (!isset($rowcontent['localversions'][$lg]) and isset($langues[$lg]) and !empty($locallinks[$key]) and nv_is_url($locallinks[$key])) {
                $rowcontent['localversions'][$lg] = $locallinks[$key];
            }
        }
    }

    if (empty($rowcontent['title'])) {
        $error[] = $nv_Lang->getModule('error_title');
    } elseif (empty($rowcontent['listcatid'])) {
        $error[] = $nv_Lang->getModule('error_cat');
    } elseif (empty($rowcontent['external_link']) and trim(strip_tags($rowcontent['bodyhtml'])) == '' and !preg_match("/\<img[^\>]*alt=\"([^\"]+)\"[^\>]*\>/is", $rowcontent['bodyhtml']) and !preg_match("/<iframe.*src=\"(.*)\".*><\/iframe>/isU", $rowcontent['bodyhtml'])) {
        $error[] = $nv_Lang->getModule('error_bodytext');
    }

    // Tin liên quan
    $rowcontent['related_pos'] = $nv_Request->get_int('related_pos', 'post', 0);
    if (!in_array($rowcontent['related_pos'], [0, 1, 2], true)) {
        $rowcontent['related_pos'] = 0;
    }
    $related_ids = $nv_Request->get_typed_array('related_ids', 'post', 'int', []);
    if (!empty($rowcontent['id'])) {
        $related_ids = array_diff($related_ids, [$rowcontent['id']]);
    }
    if (!empty($related_ids)) {
        $placeholders = implode(',', array_fill(0, count($related_ids), '?'));
        $stmt = $db->prepare('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id IN (' . $placeholders . ')');
        foreach ($related_ids as $k => $r_id) {
            $stmt->bindValue(($k + 1), $r_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        $related_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    $rowcontent['related_ids'] = empty($related_ids) ? '' : implode(',', $related_ids);

    // Loại dữ liệu có cấu trúc của bài viết
    $rowcontent['schema_type'] = $nv_Request->get_title('schema_type', 'post', '');
    if (!array_key_exists($rowcontent['schema_type'], $schema_types)) {
        $rowcontent['schema_type'] = 'newsarticle';
    }

    $rowcontent['reject_reason'] = $nv_Request->get_string('reject_reason', 'post', '');
    $rowcontent['reject_reason'] = nv_nl2br(nv_htmlspecialchars(strip_tags($rowcontent['reject_reason'])));
    if (empty($rowcontent['reject_reason']) and in_array($rowcontent['status'], [6, 9], true)) {
        $error[] = $nv_Lang->getModule('reject_reason_error');
    }
    if (!in_array($rowcontent['status'], [6, 9, 5, 8, 10, 7], true)) {
        $rowcontent['reject_reason'] = '';
    }

    // Xử lý tự động lưu
    $uuid = $nv_Request->get_title('uuid', 'post', '');
    if ($is_auto_save) {
        // Chỉ lưu 30s mỗi lần
        if (NV_CURRENTTIME - $nv_Request->get_absint('last_data_saved', 'post', 0) < 30) {
            nv_jsonOutput([
                'status' => 'not_yet_time',
                'mess' => 'Not yet time'
            ]);
        }
        // Chỉ lưu nếu có nội dung, hoặc tiêu đề
        if (empty($rowcontent['title']) and empty($rowcontent['bodyhtml'])) {
            nv_jsonOutput([
                'status' => 'not_save',
                'mess' => 'Not save'
            ]);
        }
        unset($rowcontent['referer'], $rowcontent['uuid'], $rowcontent['internal_authors_old'], $rowcontent['old_listcatid'], $rowcontent['old_status'], $rowcontent['tags_old']);

        if ($rowcontent['mode'] == 'add') {
            // Lưu mới
            $stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE uuid = :uuid AND type = 1 AND new_id = 0 AND admin_id = :admin_id');
            $stmt->bindValue(':uuid', $uuid, PDO::PARAM_STR);
            $stmt->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
            $stmt->execute();
            $tmp = $stmt->fetch();
            $stmt->closeCursor();

            if (empty($tmp)) {
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tmp (type, new_id, admin_id, time_edit, time_late, ip, uuid, properties) VALUES (1, 0, :admin_id, :time_edit, :time_late, :ip, :uuid, :properties)');
                $stmt->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
                $stmt->bindValue(':time_edit', NV_CURRENTTIME, PDO::PARAM_INT);
                $stmt->bindValue(':time_late', NV_CURRENTTIME, PDO::PARAM_INT);
                $stmt->bindValue(':ip', $client_info['ip'], PDO::PARAM_STR);
                $stmt->bindValue(':uuid', $uuid, PDO::PARAM_STR);
                $stmt->bindValue(':properties', json_encode($rowcontent, NV_JSON_ENCODE), PDO::PARAM_STR);
                $stmt->execute();
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET time_late = :time_late, ip = :ip, properties = :properties WHERE uuid = :uuid AND type = 1 AND new_id = 0 AND admin_id = :admin_id');
                $stmt->bindValue(':time_late', NV_CURRENTTIME, PDO::PARAM_INT);
                $stmt->bindValue(':ip', $client_info['ip'], PDO::PARAM_STR);
                $stmt->bindValue(':properties', json_encode($rowcontent, NV_JSON_ENCODE), PDO::PARAM_STR);
                $stmt->bindValue(':uuid', $uuid, PDO::PARAM_STR);
                $stmt->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
                $stmt->execute();
            }
        } else {
            // Sửa bài
            $stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE new_id = :id AND type = 1 AND admin_id = :admin_id');
            $stmt->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
            $stmt->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
            $stmt->execute();
            $tmp = $stmt->fetch();
            $stmt->closeCursor();

            if (empty($tmp)) {
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tmp (type, new_id, admin_id, time_edit, time_late, ip, properties) VALUES (1, :id, :admin_id, :time_edit, :time_late, :ip, :properties)');
                $stmt->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
                $stmt->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
                $stmt->bindValue(':time_edit', NV_CURRENTTIME, PDO::PARAM_INT);
                $stmt->bindValue(':time_late', NV_CURRENTTIME, PDO::PARAM_INT);
                $stmt->bindValue(':ip', $client_info['ip'], PDO::PARAM_STR);
                $stmt->bindValue(':properties', json_encode($rowcontent, NV_JSON_ENCODE), PDO::PARAM_STR);
                $stmt->execute();
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET time_late = :time_late, ip = :ip, properties = :properties WHERE new_id = :id AND type = 1 AND admin_id = :admin_id');
                $stmt->bindValue(':time_late', NV_CURRENTTIME, PDO::PARAM_INT);
                $stmt->bindValue(':ip', $client_info['ip'], PDO::PARAM_STR);
                $stmt->bindValue(':properties', json_encode($rowcontent, NV_JSON_ENCODE), PDO::PARAM_STR);
                $stmt->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
                $stmt->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        nv_jsonOutput([
            'status' => 'success',
            'mess' => '',
            'last_data_saved' => NV_CURRENTTIME
        ]);
    }
    $rowcontent['uuid'] = $uuid;

    if (!empty($error)) {
        // Nếu có lỗi thì chuyển sang trạng thái đăng nháp, cho đến khi nào đủ thông tin mới cho xuất bản
        $rowcontent['status'] = 4;
        $error_data = $error;
        $error = [];
    }

    if (empty($error)) {
        if (!empty($rowcontent['topictext']) and empty($rowcontent['topicid'])) {
            $stmt = $db->prepare('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics');
            $stmt->execute();
            $weightopic = (int) $stmt->fetchColumn() + 1;
            $aliastopic = get_mod_alias($rowcontent['topictext'], 'topics');

            $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_topics (title, alias, description, image, weight, keywords, add_time, edit_time) VALUES (:title, :alias, :description, :image, :weight, :keywords, :add_time, :edit_time)');
            $stmt->bindValue(':title', $rowcontent['topictext'], PDO::PARAM_STR);
            $stmt->bindValue(':alias', $aliastopic, PDO::PARAM_STR);
            $stmt->bindValue(':description', $rowcontent['topictext'], PDO::PARAM_STR);
            $stmt->bindValue(':image', '', PDO::PARAM_STR);
            $stmt->bindValue(':weight', $weightopic, PDO::PARAM_INT);
            $stmt->bindValue(':keywords', $rowcontent['topictext'], PDO::PARAM_STR);
            $stmt->bindValue(':add_time', NV_CURRENTTIME, PDO::PARAM_INT);
            $stmt->bindValue(':edit_time', NV_CURRENTTIME, PDO::PARAM_INT);
            $stmt->execute();
            $rowcontent['topicid'] = (int) $db->lastInsertId();
        }

        $rowcontent['sourceid'] = 0;
        if (!empty($rowcontent['sourcetext'])) {
            $url_info = parse_url($rowcontent['sourcetext']);
            if (isset($url_info['scheme']) and isset($url_info['host'])) {
                $sourceid_link = $url_info['scheme'] . '://' . $url_info['host'];
                $stmt = $db->prepare('SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE link = :link');
                $stmt->bindValue(':link', $sourceid_link, PDO::PARAM_STR);
                $stmt->execute();
                $rowcontent['sourceid'] = (int) $stmt->fetchColumn();

                if (empty($rowcontent['sourceid'])) {
                    $stmt = $db->prepare('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources');
                    $stmt->execute();
                    $weight = (int) $stmt->fetchColumn() + 1;

                    $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_sources (title, link, logo, weight, add_time, edit_time) VALUES (:title, :link, :logo, :weight, :add_time, :edit_time)');
                    $stmt->bindValue(':title', $url_info['host'], PDO::PARAM_STR);
                    $stmt->bindValue(':link', $sourceid_link, PDO::PARAM_STR);
                    $stmt->bindValue(':logo', '', PDO::PARAM_STR);
                    $stmt->bindValue(':weight', $weight, PDO::PARAM_INT);
                    $stmt->bindValue(':add_time', NV_CURRENTTIME, PDO::PARAM_INT);
                    $stmt->bindValue(':edit_time', NV_CURRENTTIME, PDO::PARAM_INT);
                    $stmt->execute();
                    $rowcontent['sourceid'] = (int) $db->lastInsertId();
                }

                $rowcontent['external_link'] = $rowcontent['external_link'] ? 1 : 0;
            } else {
                $stmt = $db->prepare('SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE title = :title');
                $stmt->bindValue(':title', $rowcontent['sourcetext'], PDO::PARAM_STR);
                $stmt->execute();
                $rowcontent['sourceid'] = (int) $stmt->fetchColumn();

                if (empty($rowcontent['sourceid'])) {
                    $stmt = $db->prepare('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources');
                    $stmt->execute();
                    $weight = (int) $stmt->fetchColumn() + 1;

                    $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_sources (title, link, logo, weight, add_time, edit_time) VALUES (:title, :link, :logo, :weight, :add_time, :edit_time)');
                    $stmt->bindValue(':title', $rowcontent['sourcetext'], PDO::PARAM_STR);
                    $stmt->bindValue(':link', '', PDO::PARAM_STR);
                    $stmt->bindValue(':logo', '', PDO::PARAM_STR);
                    $stmt->bindValue(':weight', $weight, PDO::PARAM_INT);
                    $stmt->bindValue(':add_time', NV_CURRENTTIME, PDO::PARAM_INT);
                    $stmt->bindValue(':edit_time', NV_CURRENTTIME, PDO::PARAM_INT);
                    $stmt->execute();
                    $rowcontent['sourceid'] = (int) $db->lastInsertId();
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
            // Tu dong xac dinh tags va keywords khi dang bai viet moi
            $ct = ($rowcontent['hometext'] != '') ? $rowcontent['hometext'] . ' ' . $rowcontent['bodyhtml'] : $rowcontent['bodyhtml'];

            if ($rowcontent['tags'] == '' and !empty($module_config[$module_name]['auto_tags'])) {
                $tags = nv_get_mod_tags($ct);
                !empty($tags) && $tags = array_slice($tags, 0, 20, true);
                $rowcontent['tags'] = !empty($tags) ? implode(',', $tags) : '';
            }

            if (empty($rowcontent['keywords'])) {
                $keywords = $tags;
                if (($size = count($keywords)) < 20) {
                    $keywords = array_merge($keywords, nv_get_keywords($ct, 20 - $size, true));
                }
                !empty($keywords) && $keywords = array_unique($keywords);
                $rowcontent['keywords'] = !empty($keywords) ? trim(nv_substr(implode(',', $keywords), 0, 255), ", \t\n\r\0\x0B") : '';
            }

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
                homeimgfile, homeimgalt, homeimgthumb, inhome, allowed_comm, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating
            ) VALUES (
                 :catid,
                 :listcatid,
                 :topicid,
                 :admin_id,
                 :author,
                 :sourceid,
                 :addtime,
                 :edittime,
                 :status,
                 :weight,
                 :publtime,
                 :exptime,
                 :archive,
                 :title,
                 :alias,
                 :hometext,
                 :homeimgfile,
                 :homeimgalt,
                 :homeimgthumb,
                 :inhome,
                 :allowed_comm,
                 :allowed_rating,
                 :external_link,
                 :hitstotal,
                 :hitscm,
                 :total_rating,
                 :click_rating
            )';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':catid', $rowcontent['catid'], PDO::PARAM_INT);
            $stmt->bindValue(':listcatid', $rowcontent['listcatid'], PDO::PARAM_STR);
            $stmt->bindValue(':topicid', $rowcontent['topicid'], PDO::PARAM_INT);
            $stmt->bindValue(':admin_id', $rowcontent['admin_id'], PDO::PARAM_INT);
            $stmt->bindValue(':author', $rowcontent['author'], PDO::PARAM_STR);
            $stmt->bindValue(':sourceid', $rowcontent['sourceid'], PDO::PARAM_INT);
            $stmt->bindValue(':addtime', $rowcontent['addtime'], PDO::PARAM_INT);
            $stmt->bindValue(':edittime', $rowcontent['edittime'], PDO::PARAM_INT);
            $stmt->bindValue(':status', $rowcontent['status'], PDO::PARAM_INT);
            $stmt->bindValue(':weight', $_weight, PDO::PARAM_INT);
            $stmt->bindValue(':publtime', $rowcontent['publtime'], PDO::PARAM_INT);
            $stmt->bindValue(':exptime', $rowcontent['exptime'], PDO::PARAM_INT);
            $stmt->bindValue(':archive', $rowcontent['archive'], PDO::PARAM_INT);
            $stmt->bindValue(':title', $rowcontent['title'], PDO::PARAM_STR);
            $stmt->bindValue(':alias', $rowcontent['alias'], PDO::PARAM_STR);
            $stmt->bindValue(':hometext', $rowcontent['hometext'], PDO::PARAM_STR);
            $stmt->bindValue(':homeimgfile', $rowcontent['homeimgfile'], PDO::PARAM_STR);
            $stmt->bindValue(':homeimgalt', $rowcontent['homeimgalt'], PDO::PARAM_STR);
            $stmt->bindValue(':homeimgthumb', $rowcontent['homeimgthumb'], PDO::PARAM_INT);
            $stmt->bindValue(':inhome', $rowcontent['inhome'], PDO::PARAM_INT);
            $stmt->bindValue(':allowed_comm', $rowcontent['allowed_comm'], PDO::PARAM_STR);
            $stmt->bindValue(':allowed_rating', $rowcontent['allowed_rating'], PDO::PARAM_INT);
            $stmt->bindValue(':external_link', $rowcontent['external_link'], PDO::PARAM_INT);
            $stmt->bindValue(':hitstotal', $rowcontent['hitstotal'], PDO::PARAM_INT);
            $stmt->bindValue(':hitscm', $rowcontent['hitscm'], PDO::PARAM_INT);
            $stmt->bindValue(':total_rating', $rowcontent['total_rating'], PDO::PARAM_INT);
            $stmt->bindValue(':click_rating', $rowcontent['click_rating'], PDO::PARAM_INT);
            $stmt->execute();
            $rowcontent['id'] = (int) $db->lastInsertId();
            if ($rowcontent['id'] > 0) {
                nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('content_add'), $rowcontent['title'], $admin_info['userid']);
                $ct_query = [];

                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_detail (
                    id, titlesite, description, bodyhtml, voicedata, keywords, sourcetext,
                    files, reject_reason, imgposition, layout_func, copyright,
                    allowed_send, allowed_print, allowed_save, auto_nav, group_view, localization,
                    related_ids, related_pos, schema_type
                ) VALUES (
                    :id,
                    :titlesite,
                    :description,
                    :bodyhtml,
                    :voicedata,
                    :keywords,
                    :sourcetext,
                    :files,
                    :reject_reason,
                    :imgposition,
                    :layout_func,
                    :copyright,
                    :allowed_send,
                    :allowed_print,
                    :allowed_save,
                    :auto_nav,
                    :group_view,
                    :localization,
                    :related_ids,
                    :related_pos,
                    :schema_type
                )');

                $voicedata = empty($rowcontent['voicedata']) ? '' : json_encode($rowcontent['voicedata'], NV_JSON_ENCODE);
                $localization = empty($rowcontent['localversions']) ? '' : json_encode($rowcontent['localversions'], NV_JSON_ENCODE);

                $stmt->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
                $stmt->bindValue(':files', $rowcontent['files'], PDO::PARAM_STR);
                $stmt->bindValue(':reject_reason', $rowcontent['reject_reason'], PDO::PARAM_STR);
                $stmt->bindValue(':titlesite', $rowcontent['titlesite'], PDO::PARAM_STR);
                $stmt->bindValue(':layout_func', $rowcontent['layout_func'], PDO::PARAM_STR);
                $stmt->bindValue(':description', $rowcontent['description'], PDO::PARAM_STR);
                $stmt->bindValue(':bodyhtml', $rowcontent['bodyhtml'], PDO::PARAM_STR);
                $stmt->bindValue(':voicedata', $voicedata, PDO::PARAM_STR);
                $stmt->bindValue(':keywords', $rowcontent['keywords'], PDO::PARAM_STR);
                $stmt->bindValue(':sourcetext', $rowcontent['sourcetext'], PDO::PARAM_STR);
                $stmt->bindValue(':group_view', $rowcontent['group_view'], PDO::PARAM_STR);
                $stmt->bindValue(':localization', $localization, PDO::PARAM_STR);
                $stmt->bindValue(':imgposition', $rowcontent['imgposition'], PDO::PARAM_INT);
                $stmt->bindValue(':copyright', $rowcontent['copyright'], PDO::PARAM_INT);
                $stmt->bindValue(':allowed_send', $rowcontent['allowed_send'], PDO::PARAM_INT);
                $stmt->bindValue(':allowed_print', $rowcontent['allowed_print'], PDO::PARAM_INT);
                $stmt->bindValue(':allowed_save', $rowcontent['allowed_save'], PDO::PARAM_INT);
                $stmt->bindValue(':auto_nav', $rowcontent['auto_nav'], PDO::PARAM_INT);
                $stmt->bindValue(':related_ids', $rowcontent['related_ids'], PDO::PARAM_STR);
                $stmt->bindValue(':related_pos', $rowcontent['related_pos'], PDO::PARAM_INT);
                $stmt->bindValue(':schema_type', $rowcontent['schema_type'], PDO::PARAM_STR);
                $ct_query[] = (int) $stmt->execute();

                foreach ($catids as $catid) {
                    $stmt_cp = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = :id');
                    $stmt_cp->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
                    $stmt_cp->execute();
                    $ct_query[] = $stmt_cp->rowCount();
                }

                if (array_sum($ct_query) != count($ct_query)) {
                    $error[] = $nv_Lang->getModule('errorsave');
                }
                unset($ct_query);
                if ($module_config[$module_name]['elas_use'] == 1) {
                    /* connect to elasticsearch */
                    $stmt_es = $db->prepare('SELECT bodyhtml, sourcetext, reject_reason, imgposition, copyright, allowed_send, allowed_print, allowed_save, auto_nav FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail WHERE id= :id');
                    $stmt_es->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
                    $stmt_es->execute();
                    $body_contents = $stmt_es->fetch();
                    $stmt_es->closeCursor();
                    $rowcontent = array_merge($rowcontent, $body_contents);

                    $rowcontent['unsigned_title'] = nv_EncString($rowcontent['title']);
                    $rowcontent['unsigned_bodyhtml'] = nv_EncString($rowcontent['bodyhtml']);
                    $rowcontent['unsigned_author'] = nv_EncString($rowcontent['author']);
                    $rowcontent['unsigned_hometext'] = nv_EncString($rowcontent['hometext']);

                    $nukeVietElasticSearh = new NukeViet\ElasticSearch\Functions($module_config[$module_name]['elas_host'], $module_config[$module_name]['elas_port'], $module_config[$module_name]['elas_index']);
                    $response = $nukeVietElasticSearh->insert_data(NV_PREFIXLANG . '_' . $module_data . '_rows', $rowcontent['id'], $rowcontent);
                }
            } else {
                $error[] = $nv_Lang->getModule('errorsave');
            }
        } else {
            $stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id= :id');
            $stmt->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
            $stmt->execute();
            $rowcontent_old = $stmt->fetch();
            $stmt->closeCursor();
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
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET
                catid=:catid,
                listcatid=:listcatid,
                topicid=:topicid,
                author=:author,
                sourceid=:sourceid,
                status=:status,
                publtime=:publtime,
                exptime=:exptime,
                archive=:archive,
                title=:title,
                alias=:alias,
                hometext=:hometext,
                homeimgfile=:homeimgfile,
                homeimgalt=:homeimgalt,
                homeimgthumb=:homeimgthumb,
                inhome=:inhome,
                allowed_comm=:allowed_comm,
                allowed_rating=:allowed_rating,
                external_link=:external_link,
                edittime=:edittime
            WHERE id = :id';
            $sth = $db->prepare($sql);

            $sth->bindValue(':catid', $rowcontent['catid'], PDO::PARAM_INT);
            $sth->bindValue(':listcatid', $rowcontent['listcatid'], PDO::PARAM_STR);
            $sth->bindValue(':topicid', $rowcontent['topicid'], PDO::PARAM_INT);
            $sth->bindValue(':author', $rowcontent['author'], PDO::PARAM_STR);
            $sth->bindValue(':sourceid', $rowcontent['sourceid'], PDO::PARAM_INT);
            $sth->bindValue(':status', $rowcontent['status'], PDO::PARAM_INT);
            $sth->bindValue(':publtime', $rowcontent['publtime'], PDO::PARAM_INT);
            $sth->bindValue(':exptime', $rowcontent['exptime'], PDO::PARAM_INT);
            $sth->bindValue(':archive', $rowcontent['archive'], PDO::PARAM_INT);
            $sth->bindValue(':title', $rowcontent['title'], PDO::PARAM_STR);
            $sth->bindValue(':alias', $rowcontent['alias'], PDO::PARAM_STR);
            $sth->bindValue(':hometext', $rowcontent['hometext'], PDO::PARAM_STR);
            $sth->bindValue(':homeimgfile', $rowcontent['homeimgfile'], PDO::PARAM_STR);
            $sth->bindValue(':homeimgalt', $rowcontent['homeimgalt'], PDO::PARAM_STR);
            $sth->bindValue(':homeimgthumb', $rowcontent['homeimgthumb'], PDO::PARAM_INT);
            $sth->bindValue(':inhome', $rowcontent['inhome'], PDO::PARAM_INT);
            $sth->bindValue(':allowed_comm', $rowcontent['allowed_comm'], PDO::PARAM_STR);
            $sth->bindValue(':allowed_rating', $rowcontent['allowed_rating'], PDO::PARAM_INT);
            $sth->bindValue(':external_link', $rowcontent['external_link'], PDO::PARAM_INT);
            $sth->bindValue(':edittime', $restore_id ? $rowcontent['historytime'] : NV_CURRENTTIME, PDO::PARAM_INT);
            $sth->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);

            if ($sth->execute()) {
                nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('content_edit'), $rowcontent['title'], $admin_info['userid']);

                $ct_query = [];

                // Cập nhật bảng detail
                $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_detail SET
                    titlesite=:titlesite,
                    description=:description,
                    bodyhtml=:bodyhtml,
                    voicedata=:voicedata,
                    keywords=:keywords,
                    sourcetext=:sourcetext,
                    files=:files,
                    reject_reason=:reject_reason,
                    imgposition=:imgposition,
                    layout_func=:layout_func,
                    copyright=:copyright,
                    allowed_send=:allowed_send,
                    allowed_print=:allowed_print,
                    allowed_save=:allowed_save,
                    auto_nav=:auto_nav,
                    group_view=:group_view,
                    localization=:localization,
                    related_ids=:related_ids,
                    related_pos=:related_pos,
                    schema_type=:schema_type
                WHERE id = :id');

                $voicedata = empty($rowcontent['voicedata']) ? '' : json_encode($rowcontent['voicedata'], NV_JSON_ENCODE);
                $localization = empty($rowcontent['localversions']) ? '' : json_encode($rowcontent['localversions'], NV_JSON_ENCODE);

                $sth->bindValue(':titlesite', $rowcontent['titlesite'], PDO::PARAM_STR);
                $sth->bindValue(':description', $rowcontent['description'], PDO::PARAM_STR);
                $sth->bindValue(':bodyhtml', $rowcontent['bodyhtml'], PDO::PARAM_STR);
                $sth->bindValue(':voicedata', $voicedata, PDO::PARAM_STR);
                $sth->bindValue(':keywords', $rowcontent['keywords'], PDO::PARAM_STR);
                $sth->bindValue(':sourcetext', $rowcontent['sourcetext'], PDO::PARAM_STR);
                $sth->bindValue(':files', $rowcontent['files'], PDO::PARAM_STR);
                $sth->bindValue(':reject_reason', $rowcontent['reject_reason'], PDO::PARAM_STR);
                $sth->bindValue(':imgposition', $rowcontent['imgposition'], PDO::PARAM_INT);
                $sth->bindValue(':layout_func', $rowcontent['layout_func'], PDO::PARAM_STR);
                $sth->bindValue(':copyright', $rowcontent['copyright'], PDO::PARAM_INT);
                $sth->bindValue(':allowed_send', $rowcontent['allowed_send'], PDO::PARAM_INT);
                $sth->bindValue(':allowed_print', $rowcontent['allowed_print'], PDO::PARAM_INT);
                $sth->bindValue(':allowed_save', $rowcontent['allowed_save'], PDO::PARAM_INT);
                $sth->bindValue(':auto_nav', $rowcontent['auto_nav'], PDO::PARAM_INT);
                $sth->bindValue(':group_view', $rowcontent['group_view'], PDO::PARAM_STR);
                $sth->bindValue(':localization', $localization, PDO::PARAM_STR);
                $sth->bindValue(':related_ids', $rowcontent['related_ids'], PDO::PARAM_STR);
                $sth->bindValue(':related_pos', $rowcontent['related_pos'], PDO::PARAM_INT);
                $sth->bindValue(':schema_type', $rowcontent['schema_type'], PDO::PARAM_STR);
                $sth->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);

                $ct_query[] = (int) $sth->execute();

                // Xóa trong bảng cat cũ
                if ($rowcontent_old['listcatid'] != $rowcontent['listcatid']) {
                    $array_cat_old = explode(',', $rowcontent_old['listcatid']);
                    $array_cat_new = explode(',', $rowcontent['listcatid']);
                    $array_cat_diff = array_diff($array_cat_old, $array_cat_new);
                    foreach ($array_cat_diff as $catid) {
                        if (!empty($catid)) {
                            $stmt_del = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id = :id');
                            $stmt_del->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
                            $stmt_del->execute();
                            $ct_query[] = $stmt_del->rowCount();
                        }
                    }
                }

                // Xóa bảng cat và thêm lại
                foreach ($catids as $catid) {
                    if (!empty($catid)) {
                        $stmt_del = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id = :id');
                        $stmt_del->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
                        $stmt_del->execute();

                        $stmt_cp = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = :id');
                        $stmt_cp->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
                        $stmt_cp->execute();
                        $ct_query[] = $stmt_cp->rowCount();
                    }
                }

                if (array_sum($ct_query) != count($ct_query)) {
                    $error[] = $nv_Lang->getModule('errorsave');
                }

                // Cập nhật bên ES
                if ($module_config[$module_name]['elas_use'] == 1) {
                    $stmt_es = $db->prepare('SELECT bodyhtml, sourcetext, imgposition, copyright, allowed_send, allowed_print, allowed_save, auto_nav FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail WHERE id = :id');
                    $stmt_es->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
                    $stmt_es->execute();
                    $body_contents = $stmt_es->fetch();
                    $stmt_es->closeCursor();
                    $rowcontent = array_merge($rowcontent, $body_contents);

                    $rowcontent['unsigned_title'] = nv_EncString($rowcontent['title']);
                    $rowcontent['unsigned_bodyhtml'] = nv_EncString($rowcontent['bodyhtml']);
                    $rowcontent['unsigned_author'] = nv_EncString($rowcontent['author']);
                    $rowcontent['unsigned_hometext'] = nv_EncString($rowcontent['hometext']);

                    $nukeVietElasticSearh = new NukeViet\ElasticSearch\Functions($module_config[$module_name]['elas_host'], $module_config[$module_name]['elas_port'], $module_config[$module_name]['elas_index']);
                    $result_search = $nukeVietElasticSearh->update_data(NV_PREFIXLANG . '_' . $module_data . '_rows', $rowcontent['id'], $rowcontent);
                }

                // Sau khi sửa, tiến hành xóa bản ghi lưu trạng thái sửa trong csdl
                $stmt_tmp = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE new_id = :new_id AND type = 0');
                $stmt_tmp->bindValue(':new_id', $rowcontent['id'], PDO::PARAM_INT);
                $stmt_tmp->execute();

                // Lưu lịch sử sửa bài viết nếu bật và đây không phải là hành động khôi phục
                if (!empty($module_config[$module_name]['active_history']) and empty($restore_id)) {
                    $change_field = nv_save_history($old_rowcontent, $rowcontent);
                    if (empty($change_field)) {
                        // Trường hợp ấn sửa mà không thay đổi gì thì không cập nhật edittime mới lên
                        $stmt_up = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET edittime = :edittime WHERE id = :id');
                        $stmt_up->bindValue(':edittime', $old_rowcontent['edittime'], PDO::PARAM_INT);
                        $stmt_up->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
                        $stmt_up->execute();
                    }
                }
            } else {
                $error[] = $nv_Lang->getModule('errorsave');
            }
        }

        nv_set_status_module();
        if (empty($error)) {
            $id_block_content_new = $rowcontent['mode'] == 'edit' ? array_diff($id_block_content_post, $id_block_content) : $id_block_content_post;
            $id_block_content_del = $rowcontent['mode'] == 'edit' ? array_diff($id_block_content, $id_block_content_post) : [];

            $array_block_fix = [];
            $stmt_ins = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_block (bid, id, weight) VALUES (:bid, :id, 0)');
            foreach ($id_block_content_new as $bid_i) {
                $stmt_ins->bindValue(':bid', $bid_i, PDO::PARAM_INT);
                $stmt_ins->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
                $stmt_ins->execute();
                $array_block_fix[] = $bid_i;
            }

            $stmt_del = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE id = :id AND bid = :bid');
            foreach ($id_block_content_del as $bid_i) {
                $stmt_del->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
                $stmt_del->bindValue(':bid', $bid_i, PDO::PARAM_INT);
                $stmt_del->execute();
                $array_block_fix[] = $bid_i;
            }

            $array_block_fix = array_unique($array_block_fix);
            foreach ($array_block_fix as $bid_i) {
                nv_news_fix_block($bid_i);
            }

            if ($rowcontent['tags'] != $rowcontent['tags_old'] or $copy) {
                $tags = setTagKeywords($rowcontent['tags'], true);
                foreach ($tags as $_tag) {
                    if (!in_array($_tag, $array_tags_old, true)) {
                        $alias_i = ($module_config[$module_name]['tags_alias']) ? get_mod_alias($_tag) : change_alias_tags($_tag);
                        $alias_i = nv_strtolower($alias_i);
                        $sth = $db->prepare('SELECT tid, alias, description, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags WHERE alias= :alias OR FIND_IN_SET(:keyword, keywords) > 0');
                        $sth->bindValue(':alias', $alias_i, PDO::PARAM_STR);
                        $sth->bindValue(':keyword', $_tag, PDO::PARAM_STR);
                        $sth->execute();

                        $_row_tag = $sth->fetch();
                        $sth->closeCursor();
                        $tid = empty($_row_tag) ? 0 : (int) $_row_tag['tid'];
                        $alias = empty($_row_tag) ? '' : $_row_tag['alias'];
                        $tag_i = empty($_row_tag) ? '' : $_row_tag['keywords'];
                        if (empty($tid)) {
                            $stmt_ins = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tags (numnews, alias, description, image, keywords) VALUES (1, :alias, \'\', \'\', :keyword)');
                            $stmt_ins->bindValue(':alias', $alias_i, PDO::PARAM_STR);
                            $stmt_ins->bindValue(':keyword', $_tag, PDO::PARAM_STR);
                            $stmt_ins->execute();
                            $tid = (int) $db->lastInsertId();
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
                                    $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET keywords = :keywords WHERE tid = :tid');
                                    $sth->bindValue(':keywords', $tag_i2, PDO::PARAM_STR);
                                    $sth->bindValue(':tid', $tid, PDO::PARAM_INT);
                                    $sth->execute();
                                }
                            }
                            $stmt_up = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews = numnews+1 WHERE tid = :tid');
                            $stmt_up->bindValue(':tid', $tid, PDO::PARAM_INT);
                            $stmt_up->execute();
                        }

                        // insert keyword for table _tags_id
                        try {
                            $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id (id, tid, keyword) VALUES (:id, :tid, :keyword)');
                            $sth->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
                            $sth->bindValue(':tid', $tid, PDO::PARAM_INT);
                            $sth->bindValue(':keyword', $_tag, PDO::PARAM_STR);
                            $sth->execute();
                        } catch (Throwable $e) {
                            trigger_error($e);
                            $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id SET keyword = :keyword WHERE id = :id AND tid = :tid');
                            $sth->bindValue(':keyword', $_tag, PDO::PARAM_STR);
                            $sth->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
                            $sth->bindValue(':tid', $tid, PDO::PARAM_INT);
                            $sth->execute();
                        }
                        unset($array_tags_old[$tid]);
                    }
                }

                $stmt_up = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews = numnews-1 WHERE tid = :tid');
                $stmt_del = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id = :id AND tid = :tid');
                foreach ($array_tags_old as $tid => $_tag_i) {
                    if (!in_array($_tag_i, $tags, true)) {
                        $stmt_up->bindValue(':tid', $tid, PDO::PARAM_INT);
                        $stmt_up->execute();

                        $stmt_del->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
                        $stmt_del->bindValue(':tid', $tid, PDO::PARAM_INT);
                        $stmt_del->execute();
                    }
                }
            }

            // Them/xoa tac gia noi bo
            $internal_authors_new = $rowcontent['mode'] == 'edit' ? array_diff($rowcontent['internal_authors'], $rowcontent['internal_authors_old']) : $rowcontent['internal_authors'];
            $internal_authors_del = $rowcontent['mode'] == 'edit' ? array_diff($rowcontent['internal_authors_old'], $rowcontent['internal_authors']) : [];

            if (!empty($internal_authors_new)) {
                $internal_authors_new = implode(',', $internal_authors_new);
                $_query = $db->query('SELECT id, alias, pseudonym FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE id IN (' . $internal_authors_new . ')');
                $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist (id, aid, alias, pseudonym) VALUES (:id, :aid, :alias, :pseudonym)');
                while ($_row = $_query->fetch()) {
                    $sth->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
                    $sth->bindValue(':aid', $_row['id'], PDO::PARAM_INT);
                    $sth->bindValue(':alias', $_row['alias'], PDO::PARAM_STR);
                    $sth->bindValue(':pseudonym', $_row['pseudonym'], PDO::PARAM_STR);
                    $sth->execute();
                }
                $_query->closeCursor();
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_author SET numnews = numnews+1 WHERE id IN (' . $internal_authors_new . ')');
            }
            if (!empty($internal_authors_del)) {
                $internal_authors_del = implode(',', $internal_authors_del);
                $stmt_del = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist WHERE aid IN (' . $internal_authors_del . ') AND id = :id');
                $stmt_del->bindValue(':id', $rowcontent['id'], PDO::PARAM_INT);
                $stmt_del->execute();
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_author SET numnews = numnews-1 WHERE id IN (' . $internal_authors_del . ')');
            }

            // Lưu lịch sử thay đổi trạng thái của bài viết
            Logs::saveLogStatusPost($rowcontent['id'], $rowcontent['status']);

            // Xóa trạng thái nháp
            $stmt_tmp = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE type = 1 AND admin_id = :admin_id AND (new_id = :new_id OR uuid = :uuid)');
            $stmt_tmp->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
            $stmt_tmp->bindValue(':new_id', $rowcontent['id'], PDO::PARAM_INT);
            $stmt_tmp->bindValue(':uuid', $rowcontent['uuid'], PDO::PARAM_STR);
            $stmt_tmp->execute();

            if (!empty($error_data)) {
                $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&id=' . $rowcontent['id'];
                $msg1 = implode('<br />', $error_data);
                $msg2 = $nv_Lang->getModule('content_back');
                redriect($msg1, $msg2, $url, $module_data . '_detail');
            } else {
                $referer = $crypt->decrypt($rowcontent['referer']);
                if ($restore_id) {
                    $url = $referer ?: (NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
                    $msg1 = $nv_Lang->getModule('history_restore_success');
                    $msg2 = $nv_Lang->getModule('content_main') . ' ' . $module_info['custom_title'];
                    redriect($msg1, $msg2, $url, $module_data . '_detail');
                }

                if (isset($module_config['seotools']['prcservice']) and !empty($module_config['seotools']['prcservice']) and $rowcontent['status'] == 1 and $rowcontent['publtime'] < NV_CURRENTTIME + 1 and ($rowcontent['exptime'] == 0 or $rowcontent['exptime'] > NV_CURRENTTIME + 1)) {
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=rpc&id=' . $rowcontent['id'] . '&rand=' . nv_genpass());
                } else {
                    if (!empty($referer)) {
                        nv_redirect_location($referer);
                    } else {
                        $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
                        $msg1 = $nv_Lang->getModule('content_saveok');
                        $msg2 = $nv_Lang->getModule('content_main') . ' ' . $module_info['custom_title'];
                        redriect($msg1, $msg2, $url, $module_data . '_detail');
                    }
                }
            }
        }
    } else {
        $url = 'javascript: history.go(-1)';
        $msg1 = implode('<br />', $error);
        $msg2 = $nv_Lang->getModule('content_back');
        redriect($msg1, $msg2, $url, $module_data . '_detail', 'back');
    }
    $id_block_content = $id_block_content_post;
} elseif ($rowcontent['id'] > 0) {
    $rowcontent['referer'] = $crypt->encrypt($client_info['referer']);
} else {
    $rowcontent['referer'] = '';
}

if (!empty($module_config[$module_name]['htmlhometext'])) {
    $rowcontent['hometext'] = htmlspecialchars(nv_editor_br2nl($rowcontent['hometext']));
} else {
    $rowcontent['hometext'] = nv_htmlspecialchars(nv_br2nl($rowcontent['hometext']));
}
$rowcontent['bodyhtml'] = htmlspecialchars(nv_editor_br2nl($rowcontent['bodyhtml']));
$rowcontent['alias'] = ($rowcontent['status'] == 4 and empty($rowcontent['title'])) ? '' : $rowcontent['alias'];
$rowcontent['reject_reason'] = !empty($rowcontent['reject_reason']) ? nv_br2nl($rowcontent['reject_reason']) : '';

if (!empty($rowcontent['homeimgfile']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile'])) {
    $rowcontent['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile'];
}

$array_catid_in_row = explode(',', $rowcontent['listcatid']);

$array_topic_module = [];
$array_topic_module[0] = $nv_Lang->getModule('admin_topic_slnone');
if (!empty($rowcontent['topicid'])) {
    $stmt = $db->prepare('SELECT topicid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE topicid = :topicid');
    $stmt->bindValue(':topicid', $rowcontent['topicid'], PDO::PARAM_INT);
    $stmt->execute();

    while ($_row = $stmt->fetch()) {
        $array_topic_module[$_row['topicid']] = $_row['title'];
    }
    $stmt->closeCursor();
}

$sql = 'SELECT sourceid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources ORDER BY weight ASC';
$result = $db->query($sql);
$array_source_module = [];
$array_source_module[0] = $nv_Lang->getModule('sources_sl');
while ($_row = $result->fetch()) {
    $array_source_module[$_row['sourceid']] = $_row['title'];
}
$result->closeCursor();

if ($rowcontent['status'] == 1 and $rowcontent['publtime'] > NV_CURRENTTIME) {
    $array_cat_check_content = $array_cat_pub_content;
} elseif ($rowcontent['status'] == Posts::STATUS_PUBLISH) {
    $array_cat_check_content = $array_cat_edit_content;
} elseif ($rowcontent['status'] == Posts::STATUS_REVIEW_TRANSFER) {
    $array_cat_check_content = $array_censor_content;
} else {
    $array_cat_check_content = $array_cat_add_content;
}

if (empty($array_cat_check_content)) {
    $redirect = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat';
    $contents = nv_theme_alert($nv_Lang->getModule('note_cat_title'), $nv_Lang->getModule('note_cat_content'), 'warning', $redirect, $nv_Lang->getModule('categories'));

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$nv_Lang->setGlobal('title_suggest_max', $nv_Lang->getGlobal('length_suggest_max', 65));
$nv_Lang->setGlobal('description_suggest_max', $nv_Lang->getGlobal('length_suggest_max', 160));

$rowcontent['style_content_bodytext_required'] = $rowcontent['external_link'] ? 'hidden' : '';

// Lấy danh sách báo lỗi
$stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE newsid = :newsid ORDER BY post_time DESC');
$stmt->bindValue(':newsid', $rowcontent['id'], PDO::PARAM_INT);
$stmt->execute();
$reportlist = [];
while ($reportrow = $stmt->fetch()) {
    $reportlist[$reportrow['id']] = $reportrow;
}
$stmt->closeCursor();

$rid = $nv_Request->get_int('rid', 'get', 0);
if (empty($reportlist) or !isset($reportlist[$rid])) {
    $rid = 0;
}

if ($rowcontent['id'] > 0) {
    $nv_Lang->setModule('save_temp', $nv_Lang->getModule('save'));
}

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $has_editor = true;
    $tpl->registerPlugin('modifier', 'editor', 'nv_aleditor');
} else {
    $has_editor = false;
}
$tpl->assign('HAS_EDITOR', $has_editor);
$tpl->assign('MCONFIG', $module_config[$module_name]);

$rowcontent['keywords'] = empty($rowcontent['keywords']) ? [] : explode(',', $rowcontent['keywords']);
$rowcontent['tags'] = empty($rowcontent['tags']) ? [] : explode(',', $rowcontent['tags']);
$rowcontent['group_view'] = !empty($rowcontent['group_view']) ? array_map('intval', explode(',', $rowcontent['group_view'])) : [];
$rowcontent['allowed_comm'] = !empty($rowcontent['allowed_comm']) ? array_map('intval', explode(',', $rowcontent['allowed_comm'])) : [];

$rowcontent['publ_date'] = $rowcontent['exp_date'] = '';
if (!empty($rowcontent['publtime'])) {
    $rowcontent['publ_date'] = nv_u2d_post($rowcontent['publtime']) . ' ' . nv_date('H:i', $rowcontent['publtime']);
}
if (!empty($rowcontent['exptime'])) {
    $rowcontent['exp_date'] = nv_u2d_post($rowcontent['exptime']) . ' ' . nv_date('H:i', $rowcontent['exptime']);
}
if (empty($rowcontent['uuid'])) {
    $rowcontent['uuid'] = nv_uuid4();
}

$tpl->assign('DATA', $rowcontent);
$tpl->assign('DATA_BLOCKS', $id_block_content);
$tpl->assign('DATA_TOPICS', $array_topic_module);
$tpl->assign('ARRAY_IMGPOSITION', $array_imgposition);
$tpl->assign('UPLOADS_DIR_USER', $uploads_dir_user);
$tpl->assign('UPLOAD_CURRENT', $currentpath);
$tpl->assign('LANGUES', $langues);
$tpl->assign('GROUPS_LIST', $groups_list);
$tpl->assign('ISCOPY', $copy);
$tpl->assign('RESTORE_ID', $restore_id);
$tpl->assign('RESTORE_HASH', $restore_hash);
$tpl->assign('CATS_PUBLIC', $array_cat_pub_content);
$tpl->assign('CATS_CENSOR', $array_censor_content);
$tpl->assign('AUTHORS_LIST', $internal_authors_list);
$tpl->assign('ERROR', $error);
$tpl->assign('IS_SUBMIT', $is_submit_form);
$tpl->assign('TOTAL_NEWS_CURRENT', $total_news_current);
$tpl->assign('REPORT_ID', $rid);
$tpl->assign('REPORTLIST', $reportlist);
$tpl->assign('SCHEMA_TYPES', $schema_types);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('AUTHORS_CHECKSS', csrf_create($admin_info['admin_id'] . '_' . $module_name . '_authors'));
$tpl->assign('AJ_TAGSAJ_CHECKSS', csrf_create($admin_info['admin_id'] . '_' . $module_name . '_tags-ajax'));
$tpl->assign('AJ_TAGS_CHECKSS', csrf_create($admin_info['admin_id'] . '_' . $module_name . '_tags'));
$tpl->assign('AJ_KEYWORDS_CHECKSS', csrf_create($admin_info['admin_id'] . '_' . $module_name . '_keywordsajax'));

// Xử lý bước đầu cho chuyên mục
$list_cats = [];
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
        $catiddisplay = (count($array_catid_in_row) > 1 and (in_array((int) $catid_i, array_map('intval', $array_catid_in_row), true))) ? true : false;
        $temp = [
            'catid' => $catid_i,
            'space' => (int) $array_value['lev'],
            'title' => $array_value['title'],
            'disabled' => (!in_array((int) $catid_i, array_map('intval', $array_cat_check_content), true) or (!in_array((int) $array_value['status'], array_map('intval', $global_code_defined['cat_visible_status']), true) and !in_array((int) $catid_i, array_map('intval', $array_catid_in_row), true))) ? true : false,
            'checked' => (in_array((int) $catid_i, array_map('intval', $array_catid_in_row), true)) ? true : false,
            'catidchecked' => ($catid_i == $rowcontent['catid']) ? ' checked="checked"' : '',
            'visible' => $catiddisplay
        ];
        $list_cats[$catid_i] = $temp;
    }
}
$tpl->assign('LIST_CATS', $list_cats);
$tpl->assign('LIST_BLOCKS', $array_block_cat_module);

// Đính kèm
$files = [];
if (!empty($rowcontent['files'])) {
    $rowcontent['files'] = array_filter($rowcontent['files']);
    foreach ($rowcontent['files'] as $_id => $_file) {
        if (!empty($_file)) {
            $files[] = [
                'id' => $_id,
                'value' => (!preg_match('/^http*/', $_file)) ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $_file : $_file
            ];
        }
    }
} else {
    $files[] = [
        'id' => 0,
        'value' => ''
    ];
}
$tpl->assign('FILES', $files);

// Layout cho bài viết
$array_layout = [];
foreach ($layout_array as $value) {
    $value = preg_replace($global_config['check_op_layout'], '\\1', $value);
    $array_layout[] = $value;
}
$tpl->assign('ARRAY_LAYOUT', $array_layout);

// Giọng đọc
$array_voices = [];
if (!empty($global_array_voices)) {
    foreach ($global_array_voices as $voice) {
        $voice['value'] = $rowcontent['voicedata'][$voice['id']] ?? '';
        if (!empty($voice['value']) and !nv_is_url($voice['value'])) {
            $voice['value'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $voice['value'];
        }
        $array_voices[] = [
            'id' => $voice['id'],
            'value' => $voice['value'],
            'title' => $voice['title'],
        ];
    }
}
$tpl->assign('ARRAY_VOICES', $array_voices);

// Tin bài liên quan
$related_news = [];
if (!empty($rowcontent['related_ids'])) {
    $sql = "SELECT id, title FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id IN(" . $rowcontent['related_ids'] . ")
    ORDER BY " . $order_articles_by . " DESC";
    $result = $db->query($sql);
    while ($_row = $result->fetch()) {
        $related_news[] = [
            'id' => $_row['id'],
            'title' => $_row['title']
        ];
    }
    $result->closeCursor();
}
$tpl->assign('RELATED_NEWS', $related_news);

$contents = $tpl->fetch('content.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
