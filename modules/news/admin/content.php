<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

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

    $array_data = array();
    while (list ($topicid, $title) = $sth->fetch(3)) {
        $array_data[] = array(
            'id' => $topicid,
            'title' => $title
        );
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
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET  time_late=' . NV_CURRENTTIME . ', ip=' . $db->quote($admin_info['last_ip']) . ' WHERE id=' . $id);
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

$username_alias = change_alias($admin_info['username']);
$array_structure_image = array();
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

// Lua chon Layout
$selectthemes = (!empty($site_mods[$module_name]['theme'])) ? $site_mods[$module_name]['theme'] : $global_config['site_theme'];
$layout_array = nv_scandir(NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', $global_config['check_op_layout']);

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
                        $db->query("INSERT INTO " . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . NV_UPLOADS_DIR . "/" . $cp . $p . "', 0)");
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
if (!defined('NV_IS_SPADMIN') and strpos($structure_upload, 'username') !== false) {
    $array_currentpath = explode('/', $currentpath);
    if ($array_currentpath[2] == $username_alias) {
        $uploads_dir_user = NV_UPLOADS_DIR . '/' . $module_upload . '/' . $username_alias;
    }
}

$array_block_cat_module = array();
$id_block_content = array();
$sql = 'SELECT bid, adddefault, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat ORDER BY weight ASC';
$result = $db->query($sql);
while (list ($bid_i, $adddefault_i, $title_i) = $result->fetch(3)) {
    $array_block_cat_module[$bid_i] = $title_i;
    if ($adddefault_i) {
        $id_block_content[] = $bid_i;
    }
}

$catid = $nv_Request->get_int('catid', 'get', 0);
$parentid = $nv_Request->get_int('parentid', 'get', 0);
$array_imgposition = array(
    0 => $lang_module['imgposition_0'],
    1 => $lang_module['imgposition_1'],
    2 => $lang_module['imgposition_2']
);
$total_news_current = nv_get_mod_countrows();
$is_submit_form = false;

$rowcontent = array(
    'id' => '',
    'catid' => $catid,
    'listcatid' => $catid . ',' . $parentid,
    'topicid' => '',
    'admin_id' => $admin_id,
    'author' => '',
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
    'files' => array(),
    'homeimgfile' => '',
    'homeimgalt' => '',
    'homeimgthumb' => '',
    'imgposition' => isset($module_config[$module_name]['imgposition']) ? $module_config[$module_name]['imgposition'] : 1,
    'titlesite' => '',
    'description' => '',
    'bodyhtml' => '',
    'copyright' => 0,
    'gid' => 0,
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
    'keywords' => '',
    'keywords_old' => '',
    'instant_active' => isset($module_config[$module_name]['instant_articles_auto']) ? $module_config[$module_name]['instant_articles_auto'] : 0,
    'instant_template' => '',
    'instant_creatauto' => 0,
    'mode' => 'add'
);

$rowcontent['topictext'] = '';
$page_title = $lang_module['content_add'];
$error = array();
$groups_list = nv_groups_list();
$array_keywords_old = array();
$FBIA = new \NukeViet\Facebook\InstantArticles($lang_module);

// ID của bài viết cần sửa hoặc cần copy
$rowcontent['id'] = $nv_Request->get_int('id', 'get,post', 0);
$copy = $nv_Request->get_int('copy', 'get,post', 0);

if ($rowcontent['id'] > 0) {
    $check_permission = false;
    $rowcontent = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows where id=' . $rowcontent['id'])->fetch();
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

    if (!$check_permission) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    $page_title = $lang_module['content_edit'];
    $rowcontent['topictext'] = '';

    $body_contents = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail where id=' . $rowcontent['id'])->fetch();
    $body_contents['files'] = !empty($body_contents['files']) ? explode(",", $body_contents['files']) : array();
    $rowcontent = array_merge($rowcontent, $body_contents);
    unset($body_contents);

    $_query = $db->query('SELECT tid, keyword FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id=' . $rowcontent['id'] . ' ORDER BY keyword ASC');
    while ($row = $_query->fetch()) {
        $array_keywords_old[$row['tid']] = $row['keyword'];
    }
    $rowcontent['keywords'] = implode(', ', $array_keywords_old);
    $rowcontent['keywords_old'] = $rowcontent['keywords'];

    $id_block_content = array();
    $sql = 'SELECT bid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block where id=' . $rowcontent['id'];
    $result = $db->query($sql);
    while (list ($bid_i) = $result->fetch(3)) {
        $id_block_content[] = $bid_i;
    }

    if (empty($rowcontent['status'])) {
        nv_status_notification(NV_LANG_DATA, $module_name, 'post_queue', $rowcontent['id']);
    }

    if (!empty($rowcontent['homeimgfile']) and !nv_is_url($rowcontent['homeimgfile']) and file_exists(NV_UPLOADS_REAL_DIR)) {
        $currentpath = NV_UPLOADS_DIR . '/' . $module_upload . '/' . dirname($rowcontent['homeimgfile']);
    }

    if (empty($module_config[$module_name]['htmlhometext'])) {
        $rowcontent['hometext'] = strip_tags($rowcontent['hometext'], 'br');
    }
}

// Xác định các chuyên mục được quyền đăng bài, xuất bản bài viết, sửa bài, kiểm duyệt bài, các chuyên mục hiện đang bị khóa
$array_cat_add_content = $array_cat_pub_content = $array_cat_edit_content = $array_censor_content = array();
$array_cat_locked = array();
foreach ($global_array_cat as $catid_i => $array_value) {
    if (!in_array($array_value['status'], $global_code_defined['cat_visible_status'])) {
        $array_cat_locked[] = $catid_i;
    }
    /**
     * Đăng bài thì kiểm tra chuyên mục không bị đình chỉ
     * Sửa bài thì kiểm tra thêm cả chuyên mục bị đình chỉ và bài viết đang sửa thuộc chuyên mục đó
     */
    if (in_array($array_value['status'], $global_code_defined['cat_visible_status']) or ($rowcontent['id'] > 0 and in_array($catid_i, $rowcontent['old_listcatid']))) {
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

if ($nv_Request->get_int('save', 'post') == 1) {
    $is_submit_form = true;
    $rowcontent['referer'] = $nv_Request->get_string('referer', 'get,post');
    $catids = array_unique($nv_Request->get_typed_array('catids', 'post', 'int', array()));
    $rowcontent['listcatid'] = implode(',', $catids);
    $rowcontent['catid'] = $nv_Request->get_int('catid', 'post', 0);

    $id_block_content_post = array_unique($nv_Request->get_typed_array('bids', 'post', 'int', array()));

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
        $array_cat_check_content = $array_cat_pub_content;
    } elseif ($rowcontent['status'] == 1 and $rowcontent['publtime'] <= NV_CURRENTTIME) {
        $array_cat_check_content = $array_cat_edit_content;
    } elseif ($rowcontent['status'] == 0) {
        $array_cat_check_content = $array_censor_content;
        $message_error_show = $lang_module['permissions_sendspadmin_error'];
    } else {
        $array_cat_check_content = $array_cat_add_content;
    }

    foreach ($catids as $catid_i) {
        if (!in_array($catid_i, $array_cat_check_content)) {
            $error[] = sprintf($message_error_show, $global_array_cat[$catid_i]['title']);
        }
    }
    if (!empty($catids)) {
        $rowcontent['catid'] = in_array($rowcontent['catid'], $catids) ? $rowcontent['catid'] : $catids[0];
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
    //Xử lý file đính kèm
    $rowcontent['files'] = array();
    $fileupload = $nv_Request->get_array('files', 'post');
    if (!empty($fileupload)) {
        $fileupload = array_map("trim", $fileupload);
        $fileupload = array_unique($fileupload);
        foreach ($fileupload as $_file) {
            if (preg_match("/^" . str_replace("/", "\/", NV_BASE_SITEURL . NV_UPLOADS_DIR) . "\//", $_file)) {
                $_file = substr($_file, strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));

                if (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $_file)) {
                    $rowcontent['files'][] = $_file;
                }
            } elseif (preg_match("/^http*/", $_file)) {
                $rowcontent['files'][] = $_file;
            }
        }
    }
    $rowcontent['files'] = !empty($rowcontent['files']) ? implode(",", $rowcontent['files']) : "";

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

    $_groups_post = $nv_Request->get_array('allowed_comm', 'post', array());
    $rowcontent['allowed_comm'] = !empty($_groups_post) ? implode(',', nv_groups_post(array_intersect($_groups_post, array_keys($groups_list)))) : '';

    $rowcontent['allowed_rating'] = (int) $nv_Request->get_bool('allowed_rating', 'post');
    $rowcontent['external_link'] = (int) $nv_Request->get_bool('external_link', 'post');
    if ($rowcontent['external_link'] and empty($rowcontent['sourcetext'])) {
        $rowcontent['external_link'] = 0;
    }

    $rowcontent['allowed_send'] = (int) $nv_Request->get_bool('allowed_send', 'post');
    $rowcontent['allowed_print'] = (int) $nv_Request->get_bool('allowed_print', 'post');
    $rowcontent['allowed_save'] = (int) $nv_Request->get_bool('allowed_save', 'post');
    $rowcontent['gid'] = $nv_Request->get_int('gid', 'post', 0);

    $rowcontent['keywords'] = $nv_Request->get_array('keywords', 'post', '');
    $rowcontent['keywords'] = implode(', ', $rowcontent['keywords']);

    // Tu dong xac dinh keywords
    if ($rowcontent['keywords'] == '' and !empty($module_config[$module_name]['auto_tags'])) {
        $keywords = ($rowcontent['hometext'] != '') ? $rowcontent['hometext'] : $rowcontent['bodyhtml'];
        $keywords = nv_get_keywords($keywords, 100);
        $keywords = explode(',', $keywords);

        // Ưu tiên lọc từ khóa theo các từ khóa đã có trong tags thay vì đọc từ từ điển
        $keywords_return = array();
        foreach ($keywords as $keyword_i) {
            $sth = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id where keyword = :keyword');
            $sth->bindParam(':keyword', $keyword_i, PDO::PARAM_STR);
            $sth->execute();
            if ($sth->fetchColumn()) {
                $keywords_return[] = $keyword_i;
                if (sizeof($keywords_return) > 20) {
                    break;
                }
            }
        }

        if (sizeof($keywords_return) < 20) {
            foreach ($keywords as $keyword_i) {
                if (!in_array($keyword_i, $keywords_return)) {
                    $keywords_return[] = $keyword_i;
                    if (sizeof($keywords_return) > 20) {
                        break;
                    }
                }
            }
        }
        $rowcontent['keywords'] = implode(',', $keywords_return);
    }

    if ($rowcontent['status'] != 4) {
        if (empty($rowcontent['title'])) {
            $error[] = $lang_module['error_title'];
        } elseif (empty($rowcontent['listcatid'])) {
            $error[] = $lang_module['error_cat'];
        } elseif (empty($rowcontent['external_link']) and trim(strip_tags($rowcontent['bodyhtml'])) == '' and !preg_match("/\<img[^\>]*alt=\"([^\"]+)\"[^\>]*\>/is", $rowcontent['bodyhtml']) and !preg_match("/<iframe.*src=\"(.*)\".*><\/iframe>/isU", $rowcontent['bodyhtml'])) {
            $error[] = $lang_module['error_bodytext'];
        }
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
            $weightopic = intval($weightopic) + 1;
            $aliastopic = get_mod_alias($rowcontent['topictext'], 'topics');
            $_sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_topics (title, alias, description, image, weight, keywords, add_time, edit_time) VALUES ( :title, :alias, :description, '', :weight, :keywords, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";
            $data_insert = array();
            $data_insert['title'] = $rowcontent['topictext'];
            $data_insert['alias'] = $aliastopic;
            $data_insert['description'] = $rowcontent['topictext'];
            $data_insert['weight'] = $weightopic;
            $data_insert['keywords'] = $rowcontent['topictext'];
            $rowcontent['topicid'] = $db->insert_id($_sql, 'topicid', $data_insert);
        }

        $rowcontent['sourceid'] = 0;
        if (!empty($rowcontent['sourcetext'])) {
            $url_info = @parse_url($rowcontent['sourcetext']);
            if (isset($url_info['scheme']) and isset($url_info['host'])) {
                $sourceid_link = $url_info['scheme'] . '://' . $url_info['host'];
                $stmt = $db->prepare('SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE link= :link');
                $stmt->bindParam(':link', $sourceid_link, PDO::PARAM_STR);
                $stmt->execute();
                $rowcontent['sourceid'] = $stmt->fetchColumn();

                if (empty($rowcontent['sourceid'])) {
                    $weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources')->fetchColumn();
                    $weight = intval($weight) + 1;
                    $_sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_sources (title, link, logo, weight, add_time, edit_time) VALUES ( :title ,:sourceid_link, '', :weight, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";

                    $data_insert = array();
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
                    $weight = intval($weight) + 1;
                    $_sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_sources (title, link, logo, weight, add_time, edit_time) VALUES ( :title, '', '', " . $weight . " , " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";
                    $data_insert = array();
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
            if (!defined('NV_IS_SPADMIN') and intval($rowcontent['publtime']) < NV_CURRENTTIME) {
                $rowcontent['publtime'] = NV_CURRENTTIME;
            }
            if ($rowcontent['status'] == 1 and $rowcontent['publtime'] > NV_CURRENTTIME) {
                $rowcontent['status'] = 2;
            }
            //Reset lượt xem, lượt tải, số comment, số vote, điểm vote về 0
            if ($copy) {
                $rowcontent['hitstotal'] = 0;
                $rowcontent['hitscm'] = 0;
                $rowcontent['total_rating'] = 0;
                $rowcontent['click_rating'] = 0;
            }

            // Nếu bài viết trong chuyên mục bị khóa thì xây dựng lại status
            if (array_intersect($catids, $array_cat_locked) != array() and $rowcontent['status'] <= $global_code_defined['row_locked_status']) {
                $rowcontent['status'] += ($global_code_defined['row_locked_status'] + 1);
            }

            $_weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows')->fetchColumn();
            $_weight = intval($_weight) + 1;

            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_rows (
                catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, status, weight, publtime, exptime, archive, title, alias, hometext,
                homeimgfile, homeimgalt, homeimgthumb, inhome, allowed_comm, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating, instant_active, instant_template,
                instant_creatauto
            ) VALUES (
                 ' . intval($rowcontent['catid']) . ',
                 :listcatid,
                 ' . $rowcontent['topicid'] . ',
                 ' . intval($rowcontent['admin_id']) . ',
                 :author,
                 ' . intval($rowcontent['sourceid']) . ',
                 ' . intval($rowcontent['addtime']) . ',
                 ' . intval($rowcontent['edittime']) . ',
                 ' . intval($rowcontent['status']) . ',
                 ' . $_weight . ',
                 ' . intval($rowcontent['publtime']) . ',
                 ' . intval($rowcontent['exptime']) . ',
                 ' . intval($rowcontent['archive']) . ',
                 :title,
                 :alias,
                 :hometext,
                 :homeimgfile,
                 :homeimgalt,
                 :homeimgthumb,
                 ' . intval($rowcontent['inhome']) . ',
                 :allowed_comm,
                 ' . intval($rowcontent['allowed_rating']) . ',
                 ' . intval($rowcontent['external_link']) . ',
                 ' . intval($rowcontent['hitstotal']) . ',
                 ' . intval($rowcontent['hitscm']) . ',
                 ' . intval($rowcontent['total_rating']) . ',
                 ' . intval($rowcontent['click_rating']) . ',
                 ' . intval($rowcontent['instant_active']) . ',
                 :instant_template,
                 ' . intval($rowcontent['instant_creatauto']) . ')';

            $data_insert = array();
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
                $ct_query = array();

                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_detail VALUES (
                    ' . $rowcontent['id'] . ',
                    :titlesite,
                    :description,
                    :bodyhtml,
                    :sourcetext,
                    :files,
                    ' . $rowcontent['imgposition'] . ',
                    :layout_func,
                    ' . $rowcontent['copyright'] . ',
                    ' . $rowcontent['allowed_send'] . ',
                    ' . $rowcontent['allowed_print'] . ',
                    ' . $rowcontent['allowed_save'] . ',
                    ' . $rowcontent['gid'] . '
                )');
                $stmt->bindParam(':files', $rowcontent['files'], PDO::PARAM_STR);
                $stmt->bindParam(':titlesite', $rowcontent['titlesite'], PDO::PARAM_STR);
                $stmt->bindParam(':layout_func', $rowcontent['layout_func'], PDO::PARAM_STR);
                $stmt->bindParam(':description', $rowcontent['description'], PDO::PARAM_STR, strlen($rowcontent['description']));
                $stmt->bindParam(':bodyhtml', $rowcontent['bodyhtml'], PDO::PARAM_STR, strlen($rowcontent['bodyhtml']));
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
                    /*connect to elasticsearch */
                    $body_contents = $db_slave->query('SELECT bodyhtml, sourcetext, imgposition, copyright, allowed_send, allowed_print, allowed_save, gid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail where id=' . $rowcontent['id'])->fetch();
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

            if (!defined('NV_IS_SPADMIN') and intval($rowcontent['publtime']) < intval($rowcontent_old['addtime'])) {
                $rowcontent['publtime'] = $rowcontent_old['addtime'];
            }

            if ($rowcontent['status'] == 1 and $rowcontent['publtime'] > NV_CURRENTTIME) {
                $rowcontent['status'] = 2;
            }

            // Nếu bài viết trong chuyên mục bị khóa thì xây dựng lại status
            if (array_intersect($catids, $array_cat_locked) != array() and $rowcontent['status'] <= $global_code_defined['row_locked_status']) {
                $rowcontent['status'] += ($global_code_defined['row_locked_status'] + 1);
            }

            $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET
                catid=' . intval($rowcontent['catid']) . ',
                listcatid=:listcatid,
                topicid=' . $rowcontent['topicid'] . ',
                author=:author,
                sourceid=' . intval($rowcontent['sourceid']) . ',
                status=' . intval($rowcontent['status']) . ',
                publtime=' . intval($rowcontent['publtime']) . ',
                exptime=' . intval($rowcontent['exptime']) . ',
                archive=' . intval($rowcontent['archive']) . ',
                title=:title,
                alias=:alias,
                hometext=:hometext,
                homeimgfile=:homeimgfile,
                homeimgalt=:homeimgalt,
                homeimgthumb=:homeimgthumb,
                inhome=' . intval($rowcontent['inhome']) . ',
                allowed_comm=:allowed_comm,
                allowed_rating=' . intval($rowcontent['allowed_rating']) . ',
                external_link=' . intval($rowcontent['external_link']) . ',
                instant_active=' . intval($rowcontent['instant_active']) . ',
                instant_template=:instant_template,
                instant_creatauto=' . intval($rowcontent['instant_creatauto']) . ',
                edittime=' . NV_CURRENTTIME . '
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

                $ct_query = array();
                $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_detail SET
                    titlesite=:titlesite,
                    description=:description,
                    bodyhtml=:bodyhtml,
                    sourcetext=:sourcetext,
                    files=:files,
                    imgposition=' . intval($rowcontent['imgposition']) . ',
                    layout_func=:layout_func,
                    copyright=' . intval($rowcontent['copyright']) . ',
                    allowed_send=' . intval($rowcontent['allowed_send']) . ',
                    allowed_print=' . intval($rowcontent['allowed_print']) . ',
                    allowed_save=' . intval($rowcontent['allowed_save']) . ',
                    gid=' . intval($rowcontent['gid']) . '
                WHERE id =' . $rowcontent['id']);

                $sth->bindParam(':files', $rowcontent['files'], PDO::PARAM_STR);
                $sth->bindParam(':titlesite', $rowcontent['titlesite'], PDO::PARAM_STR);
                $sth->bindParam(':layout_func', $rowcontent['layout_func'], PDO::PARAM_STR, strlen($rowcontent['layout_func']));
                $sth->bindParam(':description', $rowcontent['description'], PDO::PARAM_STR, strlen($rowcontent['description']));
                $sth->bindParam(':bodyhtml', $rowcontent['bodyhtml'], PDO::PARAM_STR, strlen($rowcontent['bodyhtml']));
                $sth->bindParam(':sourcetext', $rowcontent['sourcetext'], PDO::PARAM_STR, strlen($rowcontent['sourcetext']));

                $ct_query[] = (int) $sth->execute();

                if ($rowcontent_old['listcatid'] != $rowcontent['listcatid']) {
                    $array_cat_old = explode(',', $rowcontent_old['listcatid']);
                    $array_cat_new = explode(',', $rowcontent['listcatid']);
                    $array_cat_diff = array_diff($array_cat_old, $array_cat_new);
                    foreach ($array_cat_diff as $catid) {
                        if (!empty($catid)) {
                            $ct_query[] = $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id = ' . intval($rowcontent['id']));
                        }
                    }
                }

                foreach ($catids as $catid) {
                    if (!empty($catid)) {
                        $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id = ' . $rowcontent['id']);
                        $ct_query[] = $db->exec('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $rowcontent['id']);
                    }
                }

                if (array_sum($ct_query) != sizeof($ct_query)) {
                    $error[] = $lang_module['errorsave'];
                }
                if ($module_config[$module_name]['elas_use'] == 1) {
                    $body_contents = $db_slave->query('SELECT bodyhtml, sourcetext, imgposition, copyright, allowed_send, allowed_print, allowed_save, gid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail where id=' . $rowcontent['id'])->fetch();
                    $rowcontent = array_merge($rowcontent, $body_contents);

                    $rowcontent['unsigned_title'] = nv_EncString($rowcontent['title']);
                    $rowcontent['unsigned_bodyhtml'] = nv_EncString($rowcontent['bodyhtml']);
                    $rowcontent['unsigned_author'] = nv_EncString($rowcontent['author']);
                    $rowcontent['unsigned_hometext'] = nv_EncString($rowcontent['hometext']);

                    $nukeVietElasticSearh = new NukeViet\ElasticSearch\Functions($module_config[$module_name]['elas_host'], $module_config[$module_name]['elas_port'], $module_config[$module_name]['elas_index']);
                    $result_search = $nukeVietElasticSearh->update_data(NV_PREFIXLANG . '_' . $module_data . '_rows', $rowcontent['id'], $rowcontent);
                }

                // sau khi sửa, tiến hành xóa bản ghi lưu trạng thái sửa trong csdl
                $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE id = ' . $rowcontent['id']);
            } else {
                $error[] = $lang_module['errorsave'];
            }
        }

        nv_set_status_module();
        if (empty($error)) {
            $id_block_content_new = $rowcontent['mode'] == 'edit' ? array_diff($id_block_content_post, $id_block_content) : $id_block_content_post;
            $id_block_content_del = $rowcontent['mode'] == 'edit' ? array_diff($id_block_content, $id_block_content_post) : array();

            $array_block_fix = array();
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

            if ($rowcontent['keywords'] != $rowcontent['keywords_old'] or $copy) {
                $keywords = explode(',', $rowcontent['keywords']);
                $keywords = array_map('strip_punctuation', $keywords);
                $keywords = array_map('trim', $keywords);
                $keywords = array_diff($keywords, array(
                    ''
                ));
                $keywords = array_unique($keywords);
                foreach ($keywords as $keyword) {
                    $keyword = str_replace('&', ' ', $keyword);
                    if (!in_array($keyword, $array_keywords_old)) {
                        $alias_i = ($module_config[$module_name]['tags_alias']) ? get_mod_alias($keyword) : str_replace(' ', '-', $keyword);
                        $alias_i = nv_strtolower($alias_i);
                        $sth = $db->prepare('SELECT tid, alias, description, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags where alias= :alias OR FIND_IN_SET(:keyword, keywords)>0');
                        $sth->bindParam(':alias', $alias_i, PDO::PARAM_STR);
                        $sth->bindParam(':keyword', $keyword, PDO::PARAM_STR);
                        $sth->execute();

                        list ($tid, $alias, $keywords_i) = $sth->fetch(3);
                        if (empty($tid)) {
                            $array_insert = array();
                            $array_insert['alias'] = $alias_i;
                            $array_insert['keyword'] = $keyword;

                            $tid = $db->insert_id("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_tags (numnews, alias, description, image, keywords) VALUES (1, :alias, '', '', :keyword)", "tid", $array_insert);
                        } else {
                            if ($alias != $alias_i) {
                                if (!empty($keywords_i)) {
                                    $keyword_arr = explode(',', $keywords_i);
                                    $keyword_arr[] = $keyword;
                                    $keywords_i2 = implode(',', array_unique($keyword_arr));
                                } else {
                                    $keywords_i2 = $keyword;
                                }
                                if ($keywords_i != $keywords_i2) {
                                    $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET keywords= :keywords WHERE tid =' . $tid);
                                    $sth->bindParam(':keywords', $keywords_i2, PDO::PARAM_STR);
                                    $sth->execute();
                                }
                            }
                            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews = numnews+1 WHERE tid = ' . $tid);
                        }

                        // insert keyword for table _tags_id
                        try {
                            $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id (id, tid, keyword) VALUES (' . $rowcontent['id'] . ', ' . intval($tid) . ', :keyword)');
                            $sth->bindParam(':keyword', $keyword, PDO::PARAM_STR);
                            $sth->execute();
                        } catch (PDOException $e) {
                            $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id SET keyword = :keyword WHERE id = ' . $rowcontent['id'] . ' AND tid=' . intval($tid));
                            $sth->bindParam(':keyword', $keyword, PDO::PARAM_STR);
                            $sth->execute();
                        }
                        unset($array_keywords_old[$tid]);
                    }
                }

                foreach ($array_keywords_old as $tid => $keyword) {
                    if (!in_array($keyword, $keywords)) {
                        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews = numnews-1 WHERE tid = ' . $tid);
                        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id = ' . $rowcontent['id'] . ' AND tid=' . $tid);
                    }
                }
            }

            if (isset($module_config['seotools']['prcservice']) and !empty($module_config['seotools']['prcservice']) and $rowcontent['status'] == 1 and $rowcontent['publtime'] < NV_CURRENTTIME + 1 and ($rowcontent['exptime'] == 0 or $rowcontent['exptime'] > NV_CURRENTTIME + 1)) {
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=rpc&id=' . $rowcontent['id'] . '&rand=' . nv_genpass());
            } else {

                $referer = $crypt->decrypt($rowcontent['referer']);
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
    } else {
        $url = 'javascript: history.go(-1)';
        $msg1 = implode('<br />', $error);
        $msg2 = $lang_module['content_back'];
        redriect($msg1, $msg2, $url, $module_data . '_detail', 'back');
    }
    $id_block_content = $id_block_content_post;
} elseif ($rowcontent['id'] > 0) {
    $rowcontent['referer'] = $crypt->encrypt($client_info['referer']);

    // Lưu thông tin người đang sửa
    $_query = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp
        WHERE id =' . $rowcontent['id']);
    if ($row_tmp = $_query->fetch()) {
        if ($row_tmp['admin_id'] == $admin_info['admin_id']) {
            // Cập nhật thời gian sửa cuối
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET time_late=' . NV_CURRENTTIME . ',ip=' . $db->quote($admin_info['last_ip']) . ' WHERE id=' . $rowcontent['id']);
        } elseif ($row_tmp['time_late'] < NV_CURRENTTIME - 300) {
            //Cho phép sửa nếu người đang sửa 5 phút không thao tác đến
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET admin_id=' . $admin_info['admin_id'] . ', time_late=' . NV_CURRENTTIME . ',ip=' . $db->quote($admin_info['last_ip']) . '  WHERE id=' . $rowcontent['id']);
        } else {
            // Thông báo không có quyền sửa.
            $_username = $db->query('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid =' . $row_tmp['admin_id'])->fetchColumn();
            $_authors_lev = $db->query('SELECT lev FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id =' . $row_tmp['admin_id'])->fetchColumn();
            if ($admin_info['level'] < $_authors_lev) {
                $takeover = md5($rowcontent['id'] . '_takeover_' . NV_CHECK_SESSION);
                if ($takeover == $nv_Request->get_title('takeover', 'get', '')) {
                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tmp SET admin_id=' . $admin_info['admin_id'] . ', time_late=' . NV_CURRENTTIME . ',ip=' . $db->quote($admin_info['last_ip']) . '  WHERE id=' . $rowcontent['id']);
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&id=' . $rowcontent['id'] . '&rand=' . nv_genpass());
                }
                $contents = sprintf($lang_module['dulicate_edit_admin'], $rowcontent['title'], $_username, date('H:i d/m/Y', $row_tmp['time_edit']));
                $contents .= '<br><a type="button" class="btn btn-danger" href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&id=' . $rowcontent['id'] . '&takeover=' . $takeover . '">' . $lang_module['dulicate_takeover'] . '</a>';
            } else {
                $contents = sprintf($lang_module['dulicate_edit'], $rowcontent['title'], $_username, date('H:i d/m/Y', $row_tmp['time_edit']));
            }

            include NV_ROOTDIR . '/includes/header.php';
            echo nv_admin_theme('<br><br><br><h2 class="text-center">' . $contents . '</h2>');
            include NV_ROOTDIR . '/includes/footer.php';
        }
    } else {
        $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tmp (id, admin_id, time_edit, time_late, ip)
            VALUES (' . $rowcontent['id'] . ',' . $admin_info['admin_id'] . ',' . NV_CURRENTTIME . ',' . NV_CURRENTTIME . ',' . $db->quote($admin_info['last_ip']) . ')');
    }
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

$array_topic_module = array();
$array_topic_module[0] = $lang_module['topic_sl'];
if (!empty($rowcontent['topicid'])) {
    $db->sqlreset()
        ->select('topicid, title')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_topics')
        ->where('topicid=' . $rowcontent['topicid']);
    $result = $db->query($db->sql());

    while (list ($topicid_i, $title_i) = $result->fetch(3)) {
        $array_topic_module[$topicid_i] = $title_i;
    }
}

$sql = 'SELECT sourceid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources ORDER BY weight ASC';
$result = $db->query($sql);
$array_source_module = array();
$array_source_module[0] = $lang_module['sources_sl'];
while (list ($sourceid_i, $title_i) = $result->fetch(3)) {
    $array_source_module[$sourceid_i] = $title_i;
}

$tdate = date('H|i', $rowcontent['publtime']);
$publ_date = date('d/m/Y', $rowcontent['publtime']);
list ($phour, $pmin) = explode('|', $tdate);
if ($rowcontent['exptime'] == 0) {
    $emin = $ehour = 0;
    $exp_date = '';
} else {
    $exp_date = date('d/m/Y', $rowcontent['exptime']);
    $tdate = date('H|i', $rowcontent['exptime']);
    list ($ehour, $emin) = explode('|', $tdate);
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
$contents = '';

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
$xtpl->assign('OP', $op);

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
    /**
     * Thêm bài viết không hiển thị chuyên mục bị đình chỉ hoạt động
     * Sửa bài viết hiển thị chuyên mục bị đình chỉ hoạt động với:
     * - Bài viết đang thuộc chuyên mục thì enable
     * - Bài viết chưa nằm trong chuyên mục thì disable
     */
    if (!empty($check_show) and ($rowcontent['id'] > 0 or in_array($array_value['status'], $global_code_defined['cat_visible_status']))) {
        $space = intval($array_value['lev']) * 30;
        $catiddisplay = (sizeof($array_catid_in_row) > 1 and (in_array($catid_i, $array_catid_in_row))) ? '' : ' display: none;';
        $temp = array(
            'catid' => $catid_i,
            'space' => $space,
            'title' => $array_value['title'],
            'disabled' => (!in_array($catid_i, $array_cat_check_content) or (!in_array($array_value['status'], $global_code_defined['cat_visible_status']) and !in_array($catid_i, $array_catid_in_row))) ? ' disabled="disabled"' : '',
            'checked' => (in_array($catid_i, $array_catid_in_row)) ? ' checked="checked"' : '',
            'catidchecked' => ($catid_i == $rowcontent['catid']) ? ' checked="checked"' : '',
            'catiddisplay' => $catiddisplay
        );
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
            $xtpl->assign('FILEUPL', array(
                'id' => $_id,
                'value' => (!preg_match("/^http*/", $_file)) ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $_file : $_file
            ));
            $xtpl->parse('main.files');
        }
    }
} else {
    $xtpl->assign('FILEUPL', array(
        'id' => 0,
        'value' => ''
    ));
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
    $select .= "<option value=\"" . $i . "\"" . (($i == $phour) ? ' selected="selected"' : '') . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('phour', $select);
$select = '';
for ($i = 0; $i < 60; ++$i) {
    $select .= "<option value=\"" . $i . "\"" . (($i == $pmin) ? ' selected="selected"' : '') . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('pmin', $select);

// time exp
$xtpl->assign('exp_date', $exp_date);
$select = '';
for ($i = 0; $i <= 23; ++$i) {
    $select .= "<option value=\"" . $i . "\"" . (($i == $ehour) ? ' selected="selected"' : '') . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('ehour', $select);
$select = '';
for ($i = 0; $i < 60; ++$i) {
    $select .= "<option value=\"" . $i . "\"" . (($i == $emin) ? ' selected="selected"' : '') . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('emin', $select);

// allowed comm
$allowed_comm = explode(',', $rowcontent['allowed_comm']);
foreach ($groups_list as $_group_id => $_title) {
    $xtpl->assign('ALLOWED_COMM', array(
        'value' => $_group_id,
        'checked' => in_array($_group_id, $allowed_comm) ? ' checked="checked"' : '',
        'title' => $_title
    ));
    $xtpl->parse('main.allowed_comm');
}
if ($module_config[$module_name]['allowed_comm'] != '-1') {
    $xtpl->parse('main.content_note_comm');
}

// Lua chon Layout
foreach ($layout_array as $value) {
    $value = preg_replace($global_config['check_op_layout'], '\\1', $value);
    $xtpl->assign('LAYOUT_FUNC', array(
        'key' => $value,
        'selected' => ($rowcontent['layout_func'] == $value) ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.layout_func');
}

// source
$select = '';
foreach ($array_source_module as $sourceid_i => $source_title_i) {
    $source_sl = ($sourceid_i == $rowcontent['sourceid']) ? ' selected="selected"' : '';
    $select .= "<option value=\"" . $sourceid_i . "\" " . $source_sl . ">" . $source_title_i . "</option>\n";
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
    $editshometext = "<textarea style=\"width: 100%\" name=\"hometext\" id=\"" . $module_name . "_hometext\" cols=\"20\" rows=\"5\">" . $rowcontent['hometext'] . "</textarea>";
}
if ($has_editor) {
    $edits = nv_aleditor('bodyhtml', '100%', '400px', $rowcontent['bodyhtml'], '', $uploads_dir_user, $currentpath);
} else {
    $edits = "<textarea style=\"width: 100%\" name=\"bodyhtml\" id=\"bodyhtml\" cols=\"20\" rows=\"15\">" . $rowcontent['bodyhtml'] . "</textarea>";
}

$shtm = '';
if (sizeof($array_block_cat_module)) {
    foreach ($array_block_cat_module as $bid_i => $bid_title) {
        $xtpl->assign('BLOCKS', array(
            'title' => $bid_title,
            'bid' => $bid_i,
            'checked' => in_array($bid_i, $id_block_content) ? 'checked="checked"' : ''
        ));
        $xtpl->parse('main.block_cat.loop');
    }
    $xtpl->parse('main.block_cat');
}
if (!empty($rowcontent['keywords'])) {
    $keywords_array = explode(',', $rowcontent['keywords']);
    foreach ($keywords_array as $keywords) {
        $xtpl->assign('KEYWORDS', $keywords);
        $xtpl->parse('main.keywords');
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

$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_googleplus ORDER BY weight ASC';
$_array = $db->query($sql)->fetchAll();
if (sizeof($_array)) {
    $array_googleplus = array();
    $array_googleplus[] = array(
        'gid' => -1,
        'title' => $lang_module['googleplus_1']
    );
    $array_googleplus[] = array(
        'gid' => 0,
        'title' => $lang_module['googleplus_0']
    );
    foreach ($_array as $row) {
        $array_googleplus[] = $row;
    }
    foreach ($array_googleplus as $grow) {
        $grow['selected'] = ($rowcontent['gid'] == $grow['gid']) ? ' selected="selected"' : '';
        $xtpl->assign('GOOGLEPLUS', $grow);
        $xtpl->parse('main.googleplus.gid');
    }
    $xtpl->parse('main.googleplus');
}

if ($module_config[$module_name]['auto_tags']) {
    $xtpl->parse('main.auto_tags');
}
if (!empty($module_config[$module_name]['instant_articles_active'])) {
    $xtpl->parse('main.instant_articles_active');
}

$xtpl->parse('main');
$contents .= $xtpl->text('main');

$my_footer .= '<script type="text/javascript">timer_check_takeover = setTimeout(function() {nv_timer_check_takeover(' . $rowcontent['id'] . ');}, 30000);</script>';

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';