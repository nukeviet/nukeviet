<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_NEWS')) {
    exit('Stop!!!');
}

/**
 * getPostLevel()
 *
 * @return array
 */
function getPostLevel()
{
    global $db, $module_data, $user_info;

    // check user post content
    $post_config = [];
    $sql = 'SELECT group_id, addcontent, postcontent, editcontent, delcontent FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config_post';
    $result = $db->query($sql);
    while (list($group_id, $addcontent, $postcontent, $editcontent, $delcontent) = $result->fetch(3)) {
        $post_config[$group_id] = [
            'addcontent' => $addcontent,
            'postcontent' => $postcontent,
            'editcontent' => $editcontent,
            'delcontent' => $delcontent
        ];
    }

    $post_level = [
        'addcontent' => !empty($post_config[5]['addcontent']) ? true : false,
        'postcontent' => !empty($post_config[5]['postcontent']) ? true : false,
        'editcontent' => false,
        'delcontent' => false
    ];

    if (defined('NV_IS_USER')) {
        foreach ($user_info['in_groups'] as $group_id_i) {
            if ($group_id_i and isset($post_config[$group_id_i])) {
                if (!empty($post_config[$group_id_i]['addcontent'])) {
                    $post_level['addcontent'] = true;
                }

                if (!empty($post_config[$group_id_i]['postcontent'])) {
                    $post_level['postcontent'] = true;
                }

                if (!empty($post_config[$group_id_i]['editcontent'])) {
                    $post_level['editcontent'] = true;
                }

                if (!empty($post_config[$group_id_i]['delcontent'])) {
                    $post_level['delcontent'] = true;
                }
            }
        }
    }

    // check user post content
    if ($post_level['postcontent']) {
        $post_level['addcontent'] = true;
    }

    return $post_level;
}

$page_title = $lang_module['content'];
$key_words = $module_info['keywords'];
$page_url = $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$array_mod_title[] = [
    'catid' => 0,
    'title' => $lang_module['content'],
    'link' => $base_url
];
$post_level = getPostLevel();

// Nếu không có quyền quản lý bài viết => di chuyển đến trang đăng nhập hoặc trang chủ
if (!$post_level['postcontent'] and !$post_level['addcontent'] and !$post_level['editcontent'] and !$post_level['delcontent']) {
    $data = [];
    if (defined('NV_IS_USER')) {
        $data['urlrefresh'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, true);
    } else {
        $data['urlrefresh'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']), true);
    }
    $data['content'] = $lang_module['error_content_management'];
    $contents = content_refresh($data);
    $canonicalUrl = getCanonicalUrl($page_url);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Thông tin tác giả
$my_author_detail = defined('NV_IS_USER') ? my_author_detail($user_info['userid']) : [];

if (defined('NV_IS_USER') and $nv_Request->isset_request('author_info', 'get')) {
    $page_url .= '&amp;author_info=1';
    $page_title = $lang_module['author_info'];

    if ($nv_Request->isset_request('save', 'post')) {
        $pseudonym = $nv_Request->get_title('pseudonym', 'post', '', 1);
        if (empty($pseudonym)) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'pseudonym',
                'mess' => $lang_module['author_pseudonym_empty']
            ]);
        }
        $alias = get_pseudonym_alias($pseudonym, $my_author_detail['id']);
        if (!$alias) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'pseudonym',
                'mess' => $lang_module['author_pseudonym_error']
            ]);
        }
        $description = $nv_Request->get_string('description', 'post', '');
        $description = nv_nl2br(nv_htmlspecialchars(strip_tags($description)), '<br />');

        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_author SET alias= :alias, pseudonym = :pseudonym, description= :description, edit_time=' . NV_CURRENTTIME . ' WHERE id =' . $my_author_detail['id']);
        $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
        $stmt->bindParam(':pseudonym', $pseudonym, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        if ($stmt->execute()) {
            $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist SET alias= :alias, pseudonym = :pseudonym WHERE aid =' . $my_author_detail['id']);
            $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
            $stmt->bindParam(':pseudonym', $pseudonym, PDO::PARAM_STR);
            $stmt->execute();

            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['edit_author_info'], 'id ' . $my_author_detail['id'], $my_author_detail['uid']);
            nv_jsonOutput([
                'status' => 'OK',
                'input' => '',
                'mess' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op, true)
            ]);
        } else {
            nv_jsonOutput([
                'status' => 'error',
                'input' => '',
                'mess' => $lang_module['author_unspecified_error']
            ]);
        }
    }

    $contents = edit_author_info($my_author_detail, $base_url);
    $canonicalUrl = getCanonicalUrl($page_url);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Tạo alias (AJAX)
if ($nv_Request->isset_request('get_alias', 'post')) {
    $title = $nv_Request->get_title('get_alias', 'post', '');
    $alias = change_alias($title);

    include NV_ROOTDIR . '/includes/header.php';
    echo $alias;
    include NV_ROOTDIR . '/includes/footer.php';
}

// Thêm/sửa/xóa bài viết
if ($nv_Request->isset_request('contentid,checkss', 'get')) {
    $contentid = $nv_Request->get_int('contentid', 'get', 0);
    $fcheckss = $nv_Request->get_title('checkss', 'get', '');
    $checkss = md5($contentid . NV_CHECK_SESSION);
    $page_url .= '&amp;contentid=' . $contentid . '&amp;checkss=' . $fcheckss;

    if ($fcheckss != $checkss) {
        nv_redirect_location($base_url);
    }

    $post_status = [];

    if ($contentid) {
        if (!defined('NV_IS_USER')) {
            nv_redirect_location($base_url);
        }

        $rowcontent = $db->query('SELECT r.* FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows r 
            LEFT JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist a ON r.id=a.id 
            WHERE r.id=' . $contentid . ' AND a.aid= ' . $my_author_detail['id'] . ' AND r.status<=' . $global_code_defined['row_locked_status'])->fetch();

        if (empty($rowcontent['id'])) {
            nv_redirect_location($base_url);
        }

        // Xóa bài viết
        if ($nv_Request->get_int('delcontent', 'get')) {
            if (empty($rowcontent['status']) or $post_level['delcontent']) {
                nv_del_content_module($contentid);
                nv_fix_weight_content($rowcontent['weight']);
                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['del_content'], $contentid . ' | ' . $client_info['ip'] . ' | ' . $user_info['username'], 0);

                if ($rowcontent['status'] == 1) {
                    $nv_Cache->delMod($module_name);
                }
            }

            nv_redirect_location($base_url);
        }

        // Nếu không được phép sửa bài viết thì chuyển đến trang chính
        if (!($rowcontent['status'] != 1 or $post_level['editcontent'])) {
            nv_redirect_location($base_url);
        }

        $rowcontent['old_listcatid'] = $rowcontent['listcatid'];

        $body_contents = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail where id=' . $contentid)->fetch();
        $rowcontent = array_merge($rowcontent, $body_contents);

        if ($rowcontent['status'] != 1) {
            $post_status[] = 4;
            $post_status[] = 5;
            if (!empty($post_level['postcontent'])) {
                $post_status[] = 1;
            }
        } else {
            $post_status[] = 1;
        }

        $page_title = $lang_module['update_content'];
    } else {
        $rowcontent = [
            'id' => 0,
            'catid' => 0,
            'listcatid' => '',
            'topicid' => 0,
            'admin_id' => (defined('NV_IS_USER')) ? $user_info['userid'] : 0,
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
            'homeimgfile' => '',
            'homeimgalt' => '',
            'homeimgthumb' => 0,
            'inhome' => 1,
            'allowed_comm' => 4,
            'allowed_rating' => 1,
            'external_link' => 0,
            'hitstotal' => 0,
            'hitscm' => 0,
            'total_rating' => 0,
            'click_rating' => 0,
            'titlesite' => '',
            'description' => '',
            'bodyhtml' => '',
            'keywords' => '',
            'sourcetext' => '',
            'imgposition' => 2,
            'layout_func' => '',
            'copyright' => 0,
            'allowed_send' => 1,
            'allowed_print' => 1,
            'allowed_save' => 1
        ];

        if (!$post_level['addcontent']) {
            nv_redirect_location($base_url);
        }

        defined('NV_IS_USER') && $post_status[] = 4;
        $post_status[] = 5;
        !empty($post_level['postcontent']) && $post_status[] = 1;

        $page_title = $lang_module['add_content'];
    }

    if (empty($post_status)) {
        nv_redirect_location($base_url);
    }

    if (defined('NV_EDITOR')) {
        require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
    } elseif (!nv_function_exists('nv_aleditor') and file_exists(NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor.js')) {
        define('NV_EDITOR', true);
        define('NV_IS_CKEDITOR', true);
        $my_head .= '<script type="text/javascript" src="' . NV_STATIC_URL . NV_EDITORSDIR . '/ckeditor/ckeditor.js"></script>';

        /**
         * nv_aleditor()
         *
         * @param string $textareaname
         * @param string $width
         * @param string $height
         * @param string $val
         * @param string $customtoolbar
         * @return string
         */
        function nv_aleditor($textareaname, $width = '100%', $height = '450px', $val = '', $customtoolbar = '')
        {
            global $module_data;
            $return = '<textarea style="width: ' . $width . '; height:' . $height . ';" id="' . $module_data . '_' . $textareaname . '" name="' . $textareaname . '">' . $val . '</textarea>';
            $return .= "<script type=\"text/javascript\">
            CKEDITOR.replace( '" . $module_data . '_' . $textareaname . "', {" . (!empty($customtoolbar) ? 'toolbar : "' . $customtoolbar . '",' : '') . " width: '" . $width . "',height: '" . $height . "',removePlugins: 'uploadfile,uploadimage'});
            </script>";

            return $return;
        }
    }

    $catidList = [];
    $sql = 'SELECT catid, title, lev FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE status IN (' . implode(',', $global_code_defined['cat_visible_status']) . ') ORDER BY sort ASC';
    $result_cat = $db->query($sql);
    while (list($catid_i, $title_i, $lev_i) = $result_cat->fetch(3)) {
        $catidList[] = [
            'catid' => (int) $catid_i,
            'title' => $title_i,
            'lev' => $lev_i
        ];
    }

    $topicList = [];
    $sql = 'SELECT topicid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics ORDER BY weight ASC';
    $result = $db->query($sql);
    $topicList[0] = $lang_module['topic_sl'];

    while (list($topicid_i, $title_i) = $result->fetch(3)) {
        $topicList[$topicid_i] = $title_i;
    }

    $selectthemes = (!empty($site_mods[$module_name]['theme'])) ? $site_mods[$module_name]['theme'] : $global_config['site_theme'];
    $layouts = nv_scandir(NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', $global_config['check_op_layout']);
    $error = '';

    if ($nv_Request->isset_request('save', 'post')) {
        $rowcontent['status'] = $nv_Request->get_int('status', 'post', 0);

        if (!in_array($rowcontent['status'], $post_status, true)) {
            nv_redirect_location($base_url);
        }

        unset($fcode);
        // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
        if ($module_captcha == 'recaptcha') {
            $fcode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
        }
        // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
        elseif ($module_captcha == 'captcha') {
            $fcode = $nv_Request->get_title('fcode', 'post', '');
        }

        $catids = array_unique($nv_Request->get_typed_array('catids', 'post', 'int', []));
        $rowcontent['listcatid'] = implode(',', $catids);
        $rowcontent['author'] = $nv_Request->get_title('author', 'post', '', 1);
        $rowcontent['title'] = $nv_Request->get_title('title', 'post', '', 1);
        $rowcontent['alias'] = strtolower(change_alias($rowcontent['title']));
        if ($module_config[$module_name]['frontend_edit_alias'] == 1 and $contentid == 0) {
            $alias = $nv_Request->get_title('alias', 'post', '');
            !empty($alias) && $rowcontent['alias'] = strtolower(change_alias($alias));
        }
        $rowcontent['hometext'] = $nv_Request->get_title('hometext', 'post', '');
        $rowcontent['homeimgalt'] = $nv_Request->get_title('homeimgalt', 'post', '', 1);
        $rowcontent['sourcetext'] = $nv_Request->get_title('sourcetext', 'post', '');
        if ($module_config[$module_name]['frontend_edit_layout'] == 1) {
            $rowcontent['layout_func'] = $nv_Request->get_title('layout_func', 'post', '');
        }
        $rowcontent['homeimgfile'] = $nv_Request->get_title('homeimgfile', 'post', '');
        $rowcontent['homeimgthumb'] = 0;
        if (!nv_is_url($rowcontent['homeimgfile']) and nv_is_file($rowcontent['homeimgfile'], NV_UPLOADS_DIR . '/' . $module_upload)) {
            $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
            $rowcontent['homeimgfile'] = substr($rowcontent['homeimgfile'], $lu);
            if (is_file(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile'])) {
                $rowcontent['homeimgthumb'] = 1;
            } else {
                $rowcontent['homeimgthumb'] = 2;
            }
        } elseif (nv_is_url($rowcontent['homeimgfile'])) {
            $rowcontent['homeimgthumb'] = 3;
        } else {
            $rowcontent['homeimgfile'] = '';
        }
        $rowcontent['topicid'] = $nv_Request->get_int('topicid', 'post', 0);
        if (!array_key_exists($rowcontent['topicid'], $topicList)) {
            $rowcontent['topicid'] = 0;
        }
        $bodyhtml = $nv_Request->get_string('bodyhtml', 'post', '');
        $rowcontent['bodyhtml'] = defined('NV_EDITOR') ? nv_nl2br($bodyhtml, '') : nv_nl2br(nv_htmlspecialchars(strip_tags($bodyhtml)), '<br />');

        if (empty($rowcontent['title'])) {
            $error = $lang_module['error_title'];
        } elseif (empty($rowcontent['listcatid'])) {
            $error = $lang_module['error_cat'];
        } elseif (trim(strip_tags($rowcontent['bodyhtml'])) == '') {
            $error = $lang_module['error_bodytext'];
        } elseif (isset($fcode) and !nv_capcha_txt($fcode, $module_captcha)) {
            $error = ($module_captcha == 'recaptcha') ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect'];
        } else {
            $rowcontent['catid'] = in_array((int) $rowcontent['catid'], $catids, true) ? $rowcontent['catid'] : $catids[0];
            $rowcontent['sourceid'] = 0;
            if (!empty($rowcontent['sourcetext'])) {
                $url_info = parse_url($rowcontent['sourcetext']);

                if (isset($url_info['scheme']) and isset($url_info['host'])) {
                    $sourceid_link = $url_info['scheme'] . '://' . $url_info['host'];
                    $rowcontent['sourceid'] = $db->query('SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE link=' . $db->quote($sourceid_link))->fetchColumn();

                    if (empty($rowcontent['sourceid'])) {
                        $weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources')->fetchColumn();
                        $weight = (int) $weight + 1;
                        $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_sources (title, link, logo, weight, add_time, edit_time) VALUES (' . $db->quote($url_info['host']) . ', ' . $db->quote($sourceid_link) . ", '', " . $db->quote($weight) . ', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ')';
                        $rowcontent['sourceid'] = $db->insert_id($_sql, 'sourceid');
                    }
                }
            }

            if (empty($contentid)) {
                $_weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows')->fetchColumn();
                $_weight = (int) $_weight + 1;

                $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_rows
                        (catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, status, weight, publtime, exptime, archive, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, inhome, allowed_comm, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating) VALUES
                         (' . (int) ($rowcontent['catid']) . ',
                         ' . $db->quote($rowcontent['listcatid']) . ',
                         ' . (int) ($rowcontent['topicid']) . ',
                         ' . (int) ($rowcontent['admin_id']) . ',
                         ' . $db->quote($rowcontent['author']) . ',
                         ' . (int) ($rowcontent['sourceid']) . ',
                         ' . (int) ($rowcontent['addtime']) . ',
                         ' . (int) ($rowcontent['edittime']) . ',
                         ' . (int) ($rowcontent['status']) . ',
                         ' . $_weight . ',
                         ' . (int) ($rowcontent['publtime']) . ',
                         ' . (int) ($rowcontent['exptime']) . ',
                         ' . (int) ($rowcontent['archive']) . ',
                         ' . $db->quote($rowcontent['title']) . ',
                         ' . $db->quote($rowcontent['alias']) . ',
                         ' . $db->quote($rowcontent['hometext']) . ',
                         ' . $db->quote($rowcontent['homeimgfile']) . ',
                         ' . $db->quote($rowcontent['homeimgalt']) . ',
                         ' . (int) ($rowcontent['homeimgthumb']) . ',
                         ' . (int) ($rowcontent['inhome']) . ',
                         ' . (int) ($rowcontent['allowed_comm']) . ',
                         ' . (int) ($rowcontent['allowed_rating']) . ',
                         ' . (int) ($rowcontent['external_link']) . ',
                         ' . (int) ($rowcontent['hitstotal']) . ',
                         ' . (int) ($rowcontent['hitscm']) . ',
                         ' . (int) ($rowcontent['total_rating']) . ',
                         ' . (int) ($rowcontent['click_rating']) . ')';

                $contentid = $rowcontent['id'] = $db->insert_id($_sql, 'id');
                if (!empty($contentid)) {
                    foreach ($catids as $catid) {
                        $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $contentid);
                    }

                    $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_detail (id, titlesite, description, bodyhtml, sourcetext, imgposition, layout_func, copyright, allowed_send, allowed_print, allowed_save) VALUES (
                            ' . $contentid . ',
                            ' . $db->quote($rowcontent['titlesite']) . ',
                            ' . $db->quote($rowcontent['description']) . ',
                            ' . $db->quote($rowcontent['bodyhtml']) . ',
                            ' . $db->quote($rowcontent['sourcetext']) . ',
                            ' . (int) ($rowcontent['imgposition']) . ',
                            ' . $db->quote($rowcontent['layout_func']) . ',
                            ' . (int) ($rowcontent['copyright']) . ',
                            ' . (int) ($rowcontent['allowed_send']) . ',
                            ' . (int) ($rowcontent['allowed_print']) . ',
                            ' . (int) ($rowcontent['allowed_save']) . '
                        )');

                    if (defined('NV_IS_USER')) {
                        $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist (id, aid, alias, pseudonym) VALUES (' . $contentid . ', :aid, :alias, :pseudonym)');
                        $sth->bindParam(':aid', $my_author_detail['id'], PDO::PARAM_INT);
                        $sth->bindParam(':alias', $my_author_detail['alias'], PDO::PARAM_STR);
                        $sth->bindParam(':pseudonym', $my_author_detail['pseudonym'], PDO::PARAM_STR);
                        $sth->execute();
                        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_author SET numnews = numnews+1 WHERE id = ' . $my_author_detail['id']);
                    }

                    if ($rowcontent['status'] == 5) {
                        $content = [
                            'title' => $rowcontent['title'],
                            'hometext' => $rowcontent['hometext']
                        ];
                        nv_insert_notification($module_name, 'post_queue', $content, $contentid);
                    }

                    $user_content = defined('NV_IS_USER') ? ' | ' . $user_info['username'] : '';
                    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['add_content'], $rowcontent['title'] . ' | ' . $client_info['ip'] . $user_content, 0);
                } else {
                    $error = $lang_module['errorsave'];
                }
            } else {
                $_sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET
                         catid=' . (int) ($rowcontent['catid']) . ',
                         listcatid=' . $db->quote($rowcontent['listcatid']) . ',
                         topicid=' . (int) ($rowcontent['topicid']) . ',
                         author=' . $db->quote($rowcontent['author']) . ',
                         sourceid=' . (int) ($rowcontent['sourceid']) . ',
                         status=' . (int) ($rowcontent['status']) . ',
                         title=' . $db->quote($rowcontent['title']) . ',
                         alias=' . $db->quote($rowcontent['alias']) . ',
                         hometext=' . $db->quote($rowcontent['hometext']) . ',
                         homeimgfile=' . $db->quote($rowcontent['homeimgfile']) . ',
                         homeimgalt=' . $db->quote($rowcontent['homeimgalt']) . ',
                         homeimgthumb=' . (int) ($rowcontent['homeimgthumb']) . ',
                         edittime=' . NV_CURRENTTIME . '
                        WHERE id =' . $contentid;

                if ($db->exec($_sql)) {
                    $array_cat_old = explode(',', $rowcontent['old_listcatid']);

                    foreach ($array_cat_old as $catid) {
                        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id = ' . $contentid);
                    }

                    $array_cat_new = explode(',', $rowcontent['listcatid']);

                    foreach ($array_cat_new as $catid) {
                        $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $contentid);
                    }

                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_detail SET
                            bodyhtml=' . $db->quote($rowcontent['bodyhtml']) . ',
                            layout_func=' . $db->quote($rowcontent['layout_func']) . ',
                            sourcetext=' . $db->quote($rowcontent['sourcetext']) . '
                            WHERE id =' . $contentid);

                    if ($rowcontent['status'] == 5) {
                        $content = [
                            'title' => $rowcontent['title'],
                            'hometext' => $rowcontent['hometext']
                        ];
                        nv_insert_notification($module_name, 'post_queue', $content, $contentid);
                    }
                    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['update_content'], $rowcontent['title'] . ' | ' . $client_info['ip'] . ' | ' . $user_info['username'], 0);
                } else {
                    $error = $lang_module['errorsave'];
                }
            }
        }

        if (empty($error)) {
            $data = [];
            if (defined('NV_IS_USER')) {
                $data['urlrefresh'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

                if ($rowcontent['status'] == 1) {
                    $data['content'] = $lang_module['save_content_ok'];
                    $nv_Cache->delMod($module_name);
                } elseif ($rowcontent['status'] == 4) {
                    $data['content'] = $lang_module['save_draft_ok'];
                } else {
                    $data['content'] = $lang_module['save_content_waite'];
                }
            } elseif ($rowcontent['status'] == 1) {
                $catid = $catids[0];
                $data['urlrefresh'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'] . '/' . $rowcontent['alias'] . '-' . $contentid;
                $data['content'] = $lang_module['save_content_view_page'];
                $nv_Cache->delMod($module_name);
            } else {
                $data['urlrefresh'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;
                $data['content'] = $lang_module['save_content_waite_home'];
            }

            $data['urlrefresh'] = nv_url_rewrite($data['urlrefresh'], true);
            $contents = content_refresh($data);

            $canonicalUrl = getCanonicalUrl($page_url);

            include NV_ROOTDIR . '/includes/header.php';
            echo nv_site_theme($contents);
            include NV_ROOTDIR . '/includes/footer.php';
        }
    }

    $rowcontent['internal_authors'] = [];
    if ($contentid) {
        $db->sqlreset()
            ->select('*')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_authorlist')
            ->where('id = ' . $contentid);
        $result = $db->query($db->sql());
        while ($row = $result->fetch()) {
            $rowcontent['internal_authors'][] = [
                'href' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=author/' . $row['alias'],
                'pseudonym' => $row['pseudonym']
            ];
        }
    } elseif (defined('NV_IS_USER')) {
        $rowcontent['internal_authors'][] = [
            'href' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=author/' . $my_author_detail['alias'],
            'pseudonym' => $my_author_detail['pseudonym']
        ];
    }

    if (!empty($rowcontent['homeimgfile']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile'])) {
        $rowcontent['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile'];
    }

    $rowcontent['bodyhtml'] = htmlspecialchars(nv_editor_br2nl($rowcontent['bodyhtml']));
    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
        $htmlbodyhtml = nv_aleditor('bodyhtml', '100%', '300px', $rowcontent['bodyhtml'], 'Basic');
    } else {
        $htmlbodyhtml .= '<textarea class="textareaform" name="bodyhtml" id="bodyhtml" cols="60" rows="15">' . $rowcontent['bodyhtml'] . '</textarea>';
    }

    if (!empty($error)) {
        $my_head .= "<script>\n";
        $my_head .= "   alert('" . $error . "')\n";
        $my_head .= "</script>\n";
    }

    $contents = content_add($rowcontent, $htmlbodyhtml, $catidList, $topicList, $post_status, $layouts, $base_url);

    if (empty($rowcontent['alias'])) {
        $contents .= "<script>\n";
        $contents .= '$("#idtitle").change(function () {
        get_alias("' . $module_info['alias']['content'] . '");
        });';
        $contents .= "</script>\n";
    }

    $canonicalUrl = getCanonicalUrl($page_url);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

if (!defined('NV_IS_USER')) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&contentid=0&checkss=' . md5('0' . NV_CHECK_SESSION));
}

// Danh sách bài viết
$page = 1;
if (isset($array_op[1]) and substr($array_op[1], 0, 5) == 'page-') {
    $page = (int) (substr($array_op[1], 5));
    if ($page > 1) {
        $page_url .= '/page-' . $page;
    }
}

$articles = [];
$generate_page = '';

$from = NV_PREFIXLANG . '_' . $module_data . '_rows r LEFT JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist a ON r.id=a.id';
$db->sqlreset()
    ->select('COUNT(*)')
    ->from($from)
    ->where('a.aid= ' . $my_author_detail['id'] . ' AND r.status<=' . $global_code_defined['row_locked_status']);

$num_items = $db->query($db->sql())
    ->fetchColumn();
if ($num_items) {
    $urlappend = '/page-';
    betweenURLs($page, ceil($num_items / $per_page), $base_url, $urlappend, $prevPage, $nextPage);

    $db->select('r.id, r.catid, r.listcatid, r.topicid, r.admin_id, r.author, r.sourceid, r.addtime, r.edittime, r.status, r.publtime, r.title, r.alias, r.hometext, r.homeimgfile, r.homeimgalt, r.homeimgthumb, r.allowed_rating, r.hitstotal, r.hitscm, r.total_rating, r.click_rating, a.aid AS author_id, a.alias AS author_alias, a.pseudonym AS author_pseudonym')
        ->order('r.id DESC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $result = $db->query($db->sql());
    while ($item = $result->fetch()) {
        $item['publtime'] = nv_date('d/m/Y h:i:s A', $item['publtime']);
        $item['status_note'] = $item['status'] != 1 ? sprintf($lang_module['status_alert'], $lang_module['status_' . $item['status']]) : '';
        if ($item['homeimgthumb'] == 1) {
            $item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
        } elseif ($item['homeimgthumb'] == 2) {
            $item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
        } elseif ($item['homeimgthumb'] == 3) {
            $item['imghome'] = $item['homeimgfile'];
        } else {
            $item['imghome'] = NV_STATIC_URL . 'themes/' . $global_config['site_theme'] . '/images/no_image.gif';
        }

        $item['is_edit_content'] = ($item['status'] != 1 or $post_level['editcontent']) ? true : false;
        $item['is_del_content'] = ($item['status'] != 1 or $post_level['delcontent']) ? true : false;

        $catid = $item['catid'];
        $item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
        $articles[] = $item;
    }

    $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
    if ($page > 1) {
        $page_title .= NV_TITLEBAR_DEFIS . $lang_global['page'] . ' ' . $page;
    }
}

$contents = content_list($articles, $my_author_detail, $base_url, $generate_page);
$canonicalUrl = getCanonicalUrl($page_url);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
