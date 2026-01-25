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

    if (nv_strlen($q) < 2 or $nv_Request->get_title('checkss', 'post', '') != NV_CHECK_SESSION) {
        nv_jsonOutput($respon);
    }

    $db->sqlreset()
        ->select('COUNT(topicid)')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_topics')
        ->where('title LIKE :q_title');

    $sth = $db_slave->prepare($db_slave->sql());
    $sth->bindValue(':q_title', '%' . $q . '%', PDO::PARAM_STR);
    $sth->execute();
    $num_items = $sth->fetchColumn();
    $sth->closeCursor();

    $db_slave->select('topicid, title')->order('weight ASC')->limit($per_page)->offset(($page - 1) * $per_page);

    $sth = $db->prepare($db->sql());
    $sth->bindValue(':q_title', '%' . $q . '%', PDO::PARAM_STR);
    $sth->execute();

    if ($page == 1) {
        $respon['results'][] = [
            'id' => 0,
            'text' => $nv_Lang->getModule('admin_topic_slnone')
        ];
    }
    while ([$topicid, $title] = $sth->fetch(3)) {
        $respon['results'][] = [
            'id' => $topicid,
            'text' => nv_unhtmlspecialchars($title)
        ];
    }

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

    if (nv_strlen($q) < 2 or $nv_Request->get_title('checkss', 'post', '') != NV_CHECK_SESSION) {
        nv_jsonOutput($respon);
    }

    $db->sqlreset()
        ->select('COUNT(id)')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
        ->where('title LIKE :q_title AND id!=' . $id);

    $sth = $db_slave->prepare($db_slave->sql());
    $sth->bindValue(':q_title', '%' . $q . '%', PDO::PARAM_STR);
    $sth->execute();
    $num_items = $sth->fetchColumn();
    $sth->closeCursor();

    $db_slave->select('id, title')->order($order_articles_by . ' DESC')->limit($per_page)->offset(($page - 1) * $per_page);

    $sth = $db->prepare($db->sql());
    $sth->bindValue(':q_title', '%' . $q . '%', PDO::PARAM_STR);
    $sth->execute();

    while ([$id, $title] = $sth->fetch(3)) {
        $respon['results'][] = [
            'id' => $id,
            'text' => nv_unhtmlspecialchars($title)
        ];
    }

    $respon['pagination']['more'] = ($page * $per_page) < $num_items;
    nv_jsonOutput($respon);
}

$is_submit_form = (($nv_Request->get_int('save', 'post') == 1 and $nv_Request->get_title('checkss', 'post', '') === NV_CHECK_SESSION) ? true : false);
$is_auto_save = ($is_submit_form and $nv_Request->get_int('ajax_content', 'post', 0) == 1) ? true : false;

// Kiểm tra xem đang sửa có bị cướp quyền hay không, cập nhật thêm thời gian chỉnh sửa
if ($nv_Request->isset_request('id', 'post') and $nv_Request->isset_request('check_edit', 'post') and $is_submit_form) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $_query = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE new_id=' . $id . ' AND type=0');
    if ($row_tmp = $_query->fetch()) {
        if ($row_tmp['admin_id'] == $admin_info['admin_id']) {
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET
                time_late=' . NV_CURRENTTIME . ', ip=' . $db->quote($client_info['ip']) . '
            WHERE id=' . $row_tmp['id']);
        } else {
            $_username = $db->query('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid =' . $row_tmp['admin_id'])->fetchColumn();
            nv_jsonOutput([
                'status' => 'compromised',
                'mess' => $nv_Lang->getModule('dulicate_edit_takeover', $_username, nv_datetime_format($row_tmp['time_edit']))
            ]);
        }
    }
}

// Lấy keywords từ nội dung bài viết
if ($nv_Request->isset_request('getKeywordsFromContent', 'post') and $nv_Request->get_title('checkss', 'post') === NV_CHECK_SESSION) {
    $content = $nv_Request->get_title('content', 'post', '');
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
while ([$bid_i, $adddefault_i, $title_i] = $result->fetch(3)) {
    $array_block_cat_module[$bid_i] = $title_i;
    if ($adddefault_i) {
        $id_block_content[] = $bid_i;
    }
}

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
    'instant_active' => $module_config[$module_name]['instant_articles_auto'] ?? 0,
    'instant_template' => '',
    'instant_creatauto' => 0,
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
$FBIA = new \NukeViet\Facebook\InstantArticles(\NukeViet\Core\Language::$lang_module);
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
    $body_contents = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail WHERE id=' . $rowcontent['id'])->fetch();
    $body_contents['localversions'] = !empty($body_contents['localization']) ? json_decode($body_contents['localization'], true) : [];
    $rowcontent = array_merge($rowcontent, $body_contents);
    unset($body_contents);

    // Lấy các tag của bài viết
    $_query = $db->query('SELECT tid, keyword FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id=' . $rowcontent['id'] . ' ORDER BY keyword ASC');
    while ($row = $_query->fetch()) {
        $array_tags_old[$row['tid']] = $row['keyword'];
    }
    $rowcontent['tags'] = implode(',', $array_tags_old);
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
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row_histories WHERE new_id=' . $rowcontent['id'] . ' AND id=' . $restore_id;
        $restore_data = $db->query($sql)->fetch();
        if (empty($restore_data) or $restore_hash !== md5(NV_CHECK_SESSION . $admin_info['admin_id'] . $rowcontent['id'] . $restore_id . $restore_data['historytime'])) {
            nv_error404();
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
    $rowcontent['voicedata'] = !empty($rowcontent['voicedata']) ? json_decode($rowcontent['voicedata'], true) : [];

    // Các nhóm tin của bài viết
    $id_block_content = [];
    $sql = 'SELECT bid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE id=' . $rowcontent['id'];
    $result = $db->query($sql);
    while ([$bid_i] = $result->fetch(3)) {
        $id_block_content[] = $bid_i;
    }

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
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_tmp
    WHERE id=" . $draft_id . " AND type=1 AND admin_id=" . $admin_info['admin_id'];
    if ($rowcontent['mode'] == 'add') {
        $sql .= " AND new_id=0";
    } else {
        $sql .= " AND new_id=" . $rowcontent['id'];
    }
    $draft = $db->query($sql)->fetch() ?: [];
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
    $row_tmp = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE new_id=' . $rowcontent['id'] . ' AND type=0')->fetch();
    if ($row_tmp) {
        // Xác định người đang sửa
        $_username = $db->query('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid =' . $row_tmp['admin_id'])->fetchColumn();

        // Kiểm tra nếu có người đang sửa
        if ($row_tmp['admin_id'] == $admin_info['admin_id']) {
            // Cập nhật thời gian sửa cuối
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET
                time_late=' . NV_CURRENTTIME . ',
                ip=' . $db->quote($client_info['ip']) . '
            WHERE id=' . $row_tmp['id']);
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
            WHERE id=' . $row_tmp['id']);
        } else {
            $link_takeover = '';
            $_authors_lev = $db->query('SELECT lev FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id =' . $row_tmp['admin_id'])->fetchColumn();
            if ($admin_info['level'] < $_authors_lev) {
                // Có quyền chiếm
                $takeover = md5($rowcontent['id'] . '_takeover_' . NV_CHECK_SESSION);
                if ($takeover == $nv_Request->get_title('takeover', 'get', '')) {
                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET
                        admin_id=' . $admin_info['admin_id'] . ',
                        time_edit=' . NV_CURRENTTIME . ',
                        time_late=' . NV_CURRENTTIME . ',
                        ip=' . $db->quote($client_info['ip']) . '
                    WHERE id=' . $row_tmp['id']);
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
        $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tmp (
            type, new_id, admin_id, time_edit, time_late, ip
        ) VALUES (
            0, ' . $rowcontent['id'] . ', ' . $admin_info['admin_id'] . ', ' . NV_CURRENTTIME . ',
            ' . NV_CURRENTTIME . ', ' . $db->quote($client_info['ip']) . '
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
            $stmt = $db->prepare('SELECT topicid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE title= :title');
            $stmt->bindParam(':title', $rowcontent['topictext'], PDO::PARAM_STR);
            $stmt->execute();
            $rowcontent['topicid'] = $stmt->fetchColumn();
        }
    }
    $rowcontent['author'] = $nv_Request->get_title('author', 'post', '', 1);
    $rowcontent['internal_authors'] = $nv_Request->get_typed_array('internal_authors', 'post', 'int', []);
    $rowcontent['sourcetext'] = $nv_Request->get_title('sourcetext', 'post', '');
    $rowcontent['publtime'] = nv_d2u_post($nv_Request->get_title('publ_date', 'post', ''));
    $rowcontent['publtime'] = $rowcontent['publtime'] ?: NV_CURRENTTIME;
    $rowcontent['exptime'] = nv_d2u_post($nv_Request->get_title('exp_date', 'post', ''));
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
    $rowcontent['homeimgalt'] = $nv_Request->get_title('homeimgalt', 'post', '', 1);
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
        $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id IN(" . implode(',', $related_ids) . ")";
        $related_ids = array_intersect($related_ids, $db->query($sql)->fetchAll(PDO::FETCH_COLUMN));
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
            $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_tmp WHERE uuid=" . $db->quote($uuid) . " AND type=1 AND new_id=0 AND admin_id=" . $admin_info['admin_id'];
            $tmp = $db->query($sql)->fetch();
            if (empty($tmp)) {
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tmp (
                    type, new_id, admin_id, time_edit, time_late, ip, uuid, properties
                ) VALUES (
                    1, 0, ' . $admin_info['admin_id'] . ', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', ' . $db->quote($client_info['ip']) . ', :uuid, :properties
                )');
                $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
                $stmt->bindValue(':properties', json_encode($rowcontent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), PDO::PARAM_STR);
                $stmt->execute();
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET
                    time_late=' . NV_CURRENTTIME . ',
                    ip=' . $db->quote($client_info['ip']) . ',
                    properties= :properties
                WHERE uuid=' . $db->quote($uuid) . ' AND type=1 AND new_id=0 AND admin_id=' . $admin_info['admin_id']);
                $stmt->bindValue(':properties', json_encode($rowcontent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), PDO::PARAM_STR);
                $stmt->execute();
            }
        } else {
            // Sửa bài
            $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_tmp WHERE new_id=" . $rowcontent['id'] . " AND type=1 AND admin_id=" . $admin_info['admin_id'];
            $tmp = $db->query($sql)->fetch();
            if (empty($tmp)) {
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tmp (
                    type, new_id, admin_id, time_edit, time_late, ip, properties
                ) VALUES (
                    1, ' . $rowcontent['id'] . ', ' . $admin_info['admin_id'] . ', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', ' . $db->quote($client_info['ip']) . ', :properties
                )');
                $stmt->bindValue(':properties', json_encode($rowcontent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), PDO::PARAM_STR);
                $stmt->execute();
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET
                    time_late=' . NV_CURRENTTIME . ',
                    ip=' . $db->quote($client_info['ip']) . ',
                    properties= :properties
                WHERE new_id=' . $rowcontent['id'] . ' AND type=1 AND admin_id=' . $admin_info['admin_id']);
                $stmt->bindValue(':properties', json_encode($rowcontent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), PDO::PARAM_STR);
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
                nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('content_add'), $rowcontent['title'], $admin_info['userid']);
                $ct_query = [];

                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_detail (
                    id, titlesite, description, bodyhtml, voicedata, keywords, sourcetext,
                    files, reject_reason, imgposition, layout_func, copyright,
                    allowed_send, allowed_print, allowed_save, auto_nav, group_view, localization,
                    related_ids, related_pos, schema_type
                ) VALUES (
                    ' . $rowcontent['id'] . ',
                    :titlesite,
                    :description,
                    :bodyhtml,
                    :voicedata,
                    :keywords,
                    :sourcetext,
                    :files,
                    :reject_reason,
                    ' . $rowcontent['imgposition'] . ',
                    :layout_func,
                    ' . $rowcontent['copyright'] . ',
                    ' . $rowcontent['allowed_send'] . ',
                    ' . $rowcontent['allowed_print'] . ',
                    ' . $rowcontent['allowed_save'] . ',
                    ' . $rowcontent['auto_nav'] . ',
                    :group_view,
                    :localization,
                    ' . $db->quote($rowcontent['related_ids']) . ',
                    ' . $rowcontent['related_pos'] . ',
                    ' . $db->quote($rowcontent['schema_type']) . '
                )');

                $voicedata = empty($rowcontent['voicedata']) ? '' : json_encode($rowcontent['voicedata']);
                $localization = empty($rowcontent['localversions']) ? '' : json_encode($rowcontent['localversions']);

                $stmt->bindParam(':files', $rowcontent['files'], PDO::PARAM_STR);
                $stmt->bindParam(':reject_reason', $rowcontent['reject_reason'], PDO::PARAM_STR, strlen($rowcontent['reject_reason']));
                $stmt->bindParam(':titlesite', $rowcontent['titlesite'], PDO::PARAM_STR);
                $stmt->bindParam(':layout_func', $rowcontent['layout_func'], PDO::PARAM_STR);
                $stmt->bindParam(':description', $rowcontent['description'], PDO::PARAM_STR, strlen($rowcontent['description']));
                $stmt->bindParam(':bodyhtml', $rowcontent['bodyhtml'], PDO::PARAM_STR, strlen($rowcontent['bodyhtml']));
                $stmt->bindParam(':voicedata', $voicedata, PDO::PARAM_STR, strlen($voicedata));
                $stmt->bindParam(':keywords', $rowcontent['keywords'], PDO::PARAM_STR, strlen($rowcontent['keywords']));
                $stmt->bindParam(':sourcetext', $rowcontent['sourcetext'], PDO::PARAM_STR, strlen($rowcontent['sourcetext']));
                $stmt->bindParam(':group_view', $rowcontent['group_view'], PDO::PARAM_STR, strlen($rowcontent['group_view']));
                $stmt->bindParam(':localization', $localization, PDO::PARAM_STR, strlen($localization));
                $ct_query[] = (int) $stmt->execute();

                foreach ($catids as $catid) {
                    $ct_query[] = (int) $db->exec('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $rowcontent['id']);
                }

                if (array_sum($ct_query) != count($ct_query)) {
                    $error[] = $nv_Lang->getModule('errorsave');
                }
                unset($ct_query);
                if ($module_config[$module_name]['elas_use'] == 1) {
                    /* connect to elasticsearch */
                    $body_contents = $db_slave->query('SELECT bodyhtml, sourcetext, reject_reason, imgposition, copyright, allowed_send, allowed_print, allowed_save, auto_nav FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail where id=' . $rowcontent['id'])->fetch();
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
                    imgposition=' . (int) ($rowcontent['imgposition']) . ',
                    layout_func=:layout_func,
                    copyright=' . (int) ($rowcontent['copyright']) . ',
                    allowed_send=' . (int) ($rowcontent['allowed_send']) . ',
                    allowed_print=' . (int) ($rowcontent['allowed_print']) . ',
                    allowed_save=' . (int) ($rowcontent['allowed_save']) . ',
                    auto_nav=' . (int) ($rowcontent['auto_nav']) . ',
                    group_view=:group_view,
                    localization=:localization,
                    related_ids=' . $db->quote($rowcontent['related_ids']) . ',
                    related_pos=' . $rowcontent['related_pos'] . ',
                    schema_type=' . $db->quote($rowcontent['schema_type']) . '
                WHERE id =' . $rowcontent['id']);

                $voicedata = empty($rowcontent['voicedata']) ? '' : json_encode($rowcontent['voicedata']);
                $localization = empty($rowcontent['localversions']) ? '' : json_encode($rowcontent['localversions']);

                $sth->bindParam(':files', $rowcontent['files'], PDO::PARAM_STR);
                $sth->bindParam(':reject_reason', $rowcontent['reject_reason'], PDO::PARAM_STR, strlen($rowcontent['reject_reason']));
                $sth->bindParam(':titlesite', $rowcontent['titlesite'], PDO::PARAM_STR);
                $sth->bindParam(':layout_func', $rowcontent['layout_func'], PDO::PARAM_STR, strlen($rowcontent['layout_func']));
                $sth->bindParam(':description', $rowcontent['description'], PDO::PARAM_STR, strlen($rowcontent['description']));
                $sth->bindParam(':bodyhtml', $rowcontent['bodyhtml'], PDO::PARAM_STR, strlen($rowcontent['bodyhtml']));
                $sth->bindParam(':voicedata', $voicedata, PDO::PARAM_STR, strlen($voicedata));
                $sth->bindParam(':keywords', $rowcontent['keywords'], PDO::PARAM_STR, strlen($rowcontent['keywords']));
                $sth->bindParam(':sourcetext', $rowcontent['sourcetext'], PDO::PARAM_STR, strlen($rowcontent['sourcetext']));
                $sth->bindParam(':group_view', $rowcontent['group_view'], PDO::PARAM_STR, strlen($rowcontent['group_view']));
                $sth->bindParam(':localization', $localization, PDO::PARAM_STR, strlen($localization));

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

                if (array_sum($ct_query) != count($ct_query)) {
                    $error[] = $nv_Lang->getModule('errorsave');
                }

                // Cập nhật bên ES
                if ($module_config[$module_name]['elas_use'] == 1) {
                    $body_contents = $db_slave->query('SELECT bodyhtml, sourcetext, imgposition, copyright, allowed_send, allowed_print, allowed_save, auto_nav FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail where id=' . $rowcontent['id'])->fetch();
                    $rowcontent = array_merge($rowcontent, $body_contents);

                    $rowcontent['unsigned_title'] = nv_EncString($rowcontent['title']);
                    $rowcontent['unsigned_bodyhtml'] = nv_EncString($rowcontent['bodyhtml']);
                    $rowcontent['unsigned_author'] = nv_EncString($rowcontent['author']);
                    $rowcontent['unsigned_hometext'] = nv_EncString($rowcontent['hometext']);

                    $nukeVietElasticSearh = new NukeViet\ElasticSearch\Functions($module_config[$module_name]['elas_host'], $module_config[$module_name]['elas_port'], $module_config[$module_name]['elas_index']);
                    $result_search = $nukeVietElasticSearh->update_data(NV_PREFIXLANG . '_' . $module_data . '_rows', $rowcontent['id'], $rowcontent);
                }

                // Sau khi sửa, tiến hành xóa bản ghi lưu trạng thái sửa trong csdl
                $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE new_id=' . $rowcontent['id'] . ' AND type=0');

                // Lưu lịch sử sửa bài viết nếu bật và đây không phải là hành động khôi phục
                if (!empty($module_config[$module_name]['active_history']) and empty($restore_id)) {
                    $change_field = nv_save_history($old_rowcontent, $rowcontent);
                    if (empty($change_field)) {
                        // Trường hợp ấn sửa mà không thay đổi gì thì không cập nhật edittime mới lên
                        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET edittime=' . $old_rowcontent['edittime'] . ' WHERE id=' . $rowcontent['id'];
                        $db->query($sql);
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
                $tags = setTagKeywords($rowcontent['tags'], true);
                foreach ($tags as $_tag) {
                    if (!in_array($_tag, $array_tags_old, true)) {
                        $alias_i = ($module_config[$module_name]['tags_alias']) ? get_mod_alias($_tag) : change_alias_tags($_tag);
                        $alias_i = nv_strtolower($alias_i);
                        $sth = $db->prepare('SELECT tid, alias, description, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags where alias= :alias OR FIND_IN_SET(:keyword, keywords)>0');
                        $sth->bindParam(':alias', $alias_i, PDO::PARAM_STR);
                        $sth->bindParam(':keyword', $_tag, PDO::PARAM_STR);
                        $sth->execute();

                        [$tid, $alias, $tag_i] = $sth->fetch(3);
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

            // Xóa trạng thái nháp
            $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_tmp
            WHERE type=1 AND admin_id=" . $admin_info['admin_id'] . " AND (new_id=" . $rowcontent['id'] . " OR uuid=" . $db->quote($rowcontent['uuid']) . ")";
            $db->query($sql);

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
    $db->sqlreset()
        ->select('topicid, title')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_topics')
        ->where('topicid=' . $rowcontent['topicid']);
    $result = $db->query($db->sql());

    while ([$topicid_i, $title_i] = $result->fetch(3)) {
        $array_topic_module[$topicid_i] = $title_i;
    }
}

$sql = 'SELECT sourceid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources ORDER BY weight ASC';
$result = $db->query($sql);
$array_source_module = [];
$array_source_module[0] = $nv_Lang->getModule('sources_sl');
while ([$sourceid_i, $title_i] = $result->fetch(3)) {
    $array_source_module[$sourceid_i] = $title_i;
}

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
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE newsid = ' . $rowcontent['id'] . ' ORDER BY post_time DESC';
$result = $db->query($sql);
$reportlist = [];
while ($reportrow = $result->fetch()) {
    $reportlist[$reportrow['id']] = $reportrow;
}

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
