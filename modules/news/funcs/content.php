<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12-11-2010 20:40
 */

if (!defined('NV_IS_MOD_NEWS')) {
    die('Stop!!!');
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
} elseif (!nv_function_exists('nv_aleditor') and file_exists(NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor.js')) {
    define('NV_EDITOR', true);
    define('NV_IS_CKEDITOR', true);
    $my_head .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . NV_EDITORSDIR . '/ckeditor/ckeditor.js"></script>';

    /**
     * nv_aleditor()
     *
     * @param mixed $textareaname
     * @param string $width
     * @param string $height
     * @param string $val
     * @param string $customtoolbar
     * @return
     */
    function nv_aleditor($textareaname, $width = '100%', $height = '450px', $val = '', $customtoolbar = '')
    {
        global $module_data;
        $return = '<textarea style="width: ' . $width . '; height:' . $height . ';" id="' . $module_data . '_' . $textareaname . '" name="' . $textareaname . '">' . $val . '</textarea>';
        $return .= "<script type=\"text/javascript\">
        CKEDITOR.replace( '" . $module_data . "_" . $textareaname . "', {" . (!empty($customtoolbar) ? 'toolbar : "' . $customtoolbar . '",' : '') . " width: '" . $width . "',height: '" . $height . "',removePlugins: 'uploadfile,uploadimage'});
        </script>";
        return $return;
    }
}

$page_title = $lang_module['content'];
$key_words = $module_info['keywords'];

// check user post content
$array_post_config = array();
$sql = 'SELECT group_id, addcontent, postcontent, editcontent, delcontent FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config_post';
$result = $db->query($sql);
while (list ($group_id, $addcontent, $postcontent, $editcontent, $delcontent) = $result->fetch(3)) {
    $array_post_config[$group_id] = array(
        'addcontent' => $addcontent,
        'postcontent' => $postcontent,
        'editcontent' => $editcontent,
        'delcontent' => $delcontent
    );
}

$array_post_user = array(
    'addcontent' => isset($array_post_config[5]['addcontent']) ? $array_post_config[5]['addcontent'] : 0,
    'postcontent' => isset($array_post_config[5]['postcontent']) ? $array_post_config[5]['postcontent'] : 0,
    'editcontent' => isset($array_post_config[5]['editcontent']) ? $array_post_config[5]['editcontent'] : 0,
    'delcontent' => isset($array_post_config[5]['delcontent']) ? $array_post_config[5]['delcontent'] : 0
);

if (defined('NV_IS_USER') and isset($array_post_config[4])) {
    if ($array_post_config[4]['addcontent']) {
        $array_post_user['addcontent'] = 1;
    }

    if ($array_post_config[4]['postcontent']) {
        $array_post_user['postcontent'] = 1;
    }

    if ($array_post_config[4]['editcontent']) {
        $array_post_user['editcontent'] = 1;
    }

    if ($array_post_config[4]['delcontent']) {
        $array_post_user['delcontent'] = 1;
    }

    foreach ($user_info['in_groups'] as $group_id_i) {
        if ($group_id_i > 0 and isset($array_post_config[$group_id_i])) {
            if ($array_post_config[$group_id_i]['addcontent']) {
                $array_post_user['addcontent'] = 1;
            }

            if ($array_post_config[$group_id_i]['postcontent']) {
                $array_post_user['postcontent'] = 1;
            }

            if ($array_post_config[$group_id_i]['editcontent']) {
                $array_post_user['editcontent'] = 1;
            }

            if ($array_post_config[$group_id_i]['delcontent']) {
                $array_post_user['delcontent'] = 1;
            }
        }
    }
}

// check user post content
if ($array_post_user['postcontent']) {
    $array_post_user['addcontent'] = 1;
}

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

if (!$array_post_user['addcontent']) {
    if (defined('NV_IS_USER')) {
        $array_temp['urlrefresh'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;
    } else {
        $array_temp['urlrefresh'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']);
    }

    $array_temp['content'] = $lang_module['error_addcontent'];
    $template = $module_info['template'];

    if (!file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme'] . '/content.tpl')) {
        $template = 'default';
    }

    $array_temp['urlrefresh'] = nv_url_rewrite($array_temp['urlrefresh'], true);

    $xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/' . $module_file);
    $xtpl->assign('DATA', $array_temp);
    $xtpl->parse('mainrefresh');
    $contents = $xtpl->text('mainrefresh');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

if ($nv_Request->isset_request('get_alias', 'post')) {
    $title = $nv_Request->get_title('get_alias', 'post', '');
    $alias = change_alias($title);

    include NV_ROOTDIR . '/includes/header.php';
    echo $alias;
    include NV_ROOTDIR . '/includes/footer.php';
}

$contentid = $nv_Request->get_int('contentid', 'get,post', 0);
$fcheckss = $nv_Request->get_title('checkss', 'get,post', '');
$checkss = md5($contentid . NV_CHECK_SESSION);

// Lua chon Layout
$selectthemes = (!empty($site_mods[$module_name]['theme'])) ? $site_mods[$module_name]['theme'] : $global_config['site_theme'];
$layout_array = nv_scandir(NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', $global_config['check_op_layout']);

if ($nv_Request->isset_request('contentid', 'get,post') and $fcheckss == $checkss) {
    if ($contentid > 0) {
        $rowcontent_old = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $contentid . ' AND admin_id= ' . $user_info['userid'] . ' AND status<=' . $global_code_defined['row_locked_status'])->fetch();
        $contentid = (isset($rowcontent_old['id'])) ? intval($rowcontent_old['id']) : 0;

        if (empty($contentid)) {
            nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        }

        if ($nv_Request->get_int('delcontent', 'get') and (empty($rowcontent_old['status']) or $array_post_user['delcontent'])) {
            nv_del_content_module($contentid);
            nv_fix_weight_content($rowcontent_old['weight']);

            $user_content = defined('NV_IS_USER') ? ' | ' . $user_info['username'] : '';
            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['del_content'], $contentid . ' | ' . $client_info['ip'] . $user_content, 0);

            if ($rowcontent_old['status'] == 1) {
                $nv_Cache->delMod($module_name);
            }

            nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        } elseif (!(empty($rowcontent_old['status']) or $array_post_user['editcontent'])) {
            nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        }

        $page_title = $lang_module['update_content'];
    } else {
        $page_title = $lang_module['add_content'];
    }

    $array_mod_title[] = array(
        'catid' => 0,
        'title' => $lang_module['add_content'],
        'link' => $base_url
    );

    $array_imgposition = array(
        0 => $lang_module['imgposition_0'],
        1 => $lang_module['imgposition_1'],
        2 => $lang_module['imgposition_2']
    );

    $rowcontent = array(
        'id' => '',
        'listcatid' => '',
        'catid' => ($contentid > 0) ? $rowcontent_old['catid'] : 0,
        'topicid' => '',
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
        'imgposition' => 1,
        'titlesite' => '',
        'description' => '',
        'bodyhtml' => '',
        'copyright' => 0,
        'inhome' => 1,
        'allowed_comm' => 4,
        'allowed_rating' => 1,
        'external_link' => 0,
        'allowed_send' => 1,
        'allowed_print' => 1,
        'allowed_save' => 1,
        'hitstotal' => 0,
        'hitscm' => 0,
        'total_rating' => 0,
        'click_rating' => 0,
        'layout_func' => ''
    );

    $array_catid_module = array();
    $sql = 'SELECT catid, title, lev FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE status IN(' . implode(',', $global_code_defined['cat_visible_status']) . ') ORDER BY sort ASC';
    $result_cat = $db->query($sql);

    while (list ($catid_i, $title_i, $lev_i) = $result_cat->fetch(3)) {
        $array_catid_module[] = array(
            'catid' => $catid_i,
            'title' => $title_i,
            'lev' => $lev_i
        );
    }

    $sql = 'SELECT topicid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics ORDER BY weight ASC';
    $result = $db->query($sql);
    $array_topic_module = array();
    $array_topic_module[0] = $lang_module['topic_sl'];

    while (list ($topicid_i, $title_i) = $result->fetch(3)) {
        $array_topic_module[$topicid_i] = $title_i;
    }

    $error = '';

    if ($nv_Request->isset_request('contentid', 'post')) {
        $rowcontent['id'] = $contentid;
        if ($global_config['captcha_type'] == 2) {
            $fcode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
        } else {
            $fcode = $nv_Request->get_title('fcode', 'post', '');
        }
        $catids = array_unique($nv_Request->get_typed_array('catids', 'post', 'int', array()));

        $rowcontent['listcatid'] = implode(',', $catids);
        $rowcontent['topicid'] = $nv_Request->get_int('topicid', 'post', 0);
        $rowcontent['author'] = $nv_Request->get_title('author', 'post', '', 1);

        $rowcontent['title'] = $nv_Request->get_title('title', 'post', '', 1);

        // Xu ly Alias
        $rowcontent['alias'] = strtolower(change_alias($rowcontent['title']));
        if ($module_config[$module_name]['frontend_edit_alias'] == 1 and $rowcontent['id'] == 0) {
            $alias = $nv_Request->get_title('alias', 'post', '');
            $rowcontent['alias'] = ($alias == '') ? change_alias($rowcontent['title']) : change_alias($alias);
        }

        $rowcontent['hometext'] = $nv_Request->get_title('hometext', 'post', '');

        $rowcontent['homeimgfile'] = $nv_Request->get_title('homeimgfile', 'post', '');
        $rowcontent['homeimgalt'] = $nv_Request->get_title('homeimgalt', 'post', '', 1);
        $rowcontent['imgposition'] = $nv_Request->get_int('imgposition', 'post', 0);
        $rowcontent['sourcetext'] = $nv_Request->get_title('sourcetext', 'post', '');

        // Lua chon Layout
        if ($module_config[$module_name]['frontend_edit_layout'] == 1) {
            $rowcontent['layout_func'] = $nv_Request->get_title('layout_func', 'post', '');
        }
        // Xu ly anh minh hoa
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

        if (!array_key_exists($rowcontent['imgposition'], $array_imgposition)) {
            $rowcontent['imgposition'] = 1;
        }
        if (!array_key_exists($rowcontent['topicid'], $array_topic_module)) {
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
        } elseif (!nv_capcha_txt($fcode)) {
            $error = ($global_config['captcha_type'] == 2 ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect']);
        } else {
            if (($array_post_user['postcontent']) and $nv_Request->isset_request('status1', 'post')) {
                $rowcontent['status'] = 1;
            } elseif ($nv_Request->isset_request('status0', 'post')) {
                $rowcontent['status'] = 5;
            } elseif ($nv_Request->isset_request('status4', 'post')) {
                $rowcontent['status'] = 4;
            }
            $rowcontent['catid'] = in_array($rowcontent['catid'], $catids) ? $rowcontent['catid'] : $catids[0];
            $rowcontent['sourceid'] = 0;
            if (!empty($rowcontent['sourcetext'])) {
                $url_info = @parse_url($rowcontent['sourcetext']);

                if (isset($url_info['scheme']) and isset($url_info['host'])) {
                    $sourceid_link = $url_info['scheme'] . '://' . $url_info['host'];
                    $rowcontent['sourceid'] = $db->query('SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE link=' . $db->quote($sourceid_link))
                        ->fetchColumn();

                    if (empty($rowcontent['sourceid'])) {
                        $weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources')->fetchColumn();
                        $weight = intval($weight) + 1;
                        $_sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_sources (title, link, logo, weight, add_time, edit_time) VALUES (" . $db->quote($url_info['host']) . ", " . $db->quote($sourceid_link) . ", '', " . $db->quote($weight) . ", " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";
                        $rowcontent['sourceid'] = $db->insert_id($_sql, 'sourceid');
                    }
                }
            }
            if ($rowcontent['id'] == 0) {
                $_weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows')->fetchColumn();
                $_weight = intval($_weight) + 1;

                $_sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_rows
                        (catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, status, weight, publtime, exptime, archive, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, inhome, allowed_comm, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating) VALUES
                         (" . intval($rowcontent['catid']) . ",
                         " . $db->quote($rowcontent['listcatid']) . ",
                         " . intval($rowcontent['topicid']) . ",
                         " . intval($rowcontent['admin_id']) . ",
                         " . $db->quote($rowcontent['author']) . ",
                         " . intval($rowcontent['sourceid']) . ",
                         " . intval($rowcontent['addtime']) . ",
                         " . intval($rowcontent['edittime']) . ",
                         " . intval($rowcontent['status']) . ",
                         " . $_weight . ",
                         " . intval($rowcontent['publtime']) . ",
                         " . intval($rowcontent['exptime']) . ",
                         " . intval($rowcontent['archive']) . ",
                         " . $db->quote($rowcontent['title']) . ",
                         " . $db->quote($rowcontent['alias']) . ",
                         " . $db->quote($rowcontent['hometext']) . ",
                         " . $db->quote($rowcontent['homeimgfile']) . ",
                         " . $db->quote($rowcontent['homeimgalt']) . ",
                         " . intval($rowcontent['homeimgthumb']) . ",
                         " . intval($rowcontent['inhome']) . ",
                         " . intval($rowcontent['allowed_comm']) . ",
                         " . intval($rowcontent['allowed_rating']) . ",
                         " . intval($rowcontent['external_link']) . ",
                         " . intval($rowcontent['hitstotal']) . ",
                         " . intval($rowcontent['hitscm']) . ",
                         " . intval($rowcontent['total_rating']) . ",
                         " . intval($rowcontent['click_rating']) . ")";

                $rowcontent['id'] = $db->insert_id($_sql, 'id');
                if ($rowcontent['id'] > 0) {
                    foreach ($catids as $catid) {
                        $db->query("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $rowcontent['id']);
                    }

                    $db->query("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_detail (id, titlesite, description, bodyhtml, sourcetext, imgposition, layout_func, copyright, allowed_send, allowed_print, allowed_save, gid) VALUES (
                            " . $rowcontent['id'] . ",
                            " . $db->quote($rowcontent['titlesite']) . ",
                            " . $db->quote($rowcontent['description']) . ",
                            " . $db->quote($rowcontent['bodyhtml']) . ",
                            " . $db->quote($rowcontent['sourcetext']) . ",
                            " . intval($rowcontent['imgposition']) . ",
                            " . $db->quote($rowcontent['layout_func']) . ",
                            " . intval($rowcontent['copyright']) . ",
                            " . intval($rowcontent['allowed_send']) . ",
                            " . intval($rowcontent['allowed_print']) . ",
                            " . intval($rowcontent['allowed_save']) . ", 0
                        )");

                    $user_content = defined('NV_IS_USER') ? ' | ' . $user_info['username'] : '';

                    // Them vao thong bao
                    if (empty($rowcontent['status'])) {
                        $content = array(
                            'title' => $rowcontent['title'],
                            'hometext' => $rowcontent['hometext']
                        );
                        nv_insert_notification($module_name, 'post_queue', $content, $rowcontent['id']);
                    }

                    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['add_content'], $rowcontent['title'] . ' | ' . $client_info['ip'] . $user_content, 0);
                } else {
                    $error = $lang_module['errorsave'];
                }
            } else {
                if ($rowcontent_old['status'] == 1) {
                    $rowcontent['status'] = 1;
                }

                $_sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET
                         catid=" . intval($rowcontent['catid']) . ",
                         listcatid=" . $db->quote($rowcontent['listcatid']) . ",
                         topicid=" . intval($rowcontent['topicid']) . ",
                         author=" . $db->quote($rowcontent['author']) . ",
                         sourceid=" . intval($rowcontent['sourceid']) . ",
                         status=" . intval($rowcontent['status']) . ",
                         publtime=" . intval($rowcontent['publtime']) . ",
                         exptime=" . intval($rowcontent['exptime']) . ",
                         archive=" . intval($rowcontent['archive']) . ",
                         title=" . $db->quote($rowcontent['title']) . ",
                         alias=" . $db->quote($rowcontent['alias']) . ",
                         hometext=" . $db->quote($rowcontent['hometext']) . ",
                         homeimgfile=" . $db->quote($rowcontent['homeimgfile']) . ",
                         homeimgalt=" . $db->quote($rowcontent['homeimgalt']) . ",
                         homeimgthumb=" . intval($rowcontent['homeimgthumb']) . ",
                         inhome=" . intval($rowcontent['inhome']) . ",
                         allowed_comm=" . intval($rowcontent['allowed_comm']) . ",
                         allowed_rating=" . intval($rowcontent['allowed_rating']) . ",
                         external_link=" . intval($rowcontent['external_link']) . ",
                         edittime=" . NV_CURRENTTIME . "
                        WHERE id =" . $rowcontent['id'];

                if ($db->exec($_sql)) {
                    $array_cat_old = explode(',', $rowcontent_old['listcatid']);

                    foreach ($array_cat_old as $catid) {
                        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id = ' . $rowcontent['id']);
                    }

                    $array_cat_new = explode(',', $rowcontent['listcatid']);

                    foreach ($array_cat_new as $catid) {
                        $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $rowcontent['id']);
                    }

                    $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_detail SET
                            bodyhtml=" . $db->quote($rowcontent['bodyhtml']) . ",
                            imgposition=" . intval($rowcontent['imgposition']) . ",
                            layout_func=" . $db->quote($rowcontent['layout_func']) . ",
                            sourcetext=" . $db->quote($rowcontent['sourcetext']) . ",
                            copyright=" . intval($rowcontent['copyright']) . ",
                            allowed_send=" . intval($rowcontent['allowed_send']) . ",
                            allowed_print=" . intval($rowcontent['allowed_print']) . ",
                            allowed_save=" . intval($rowcontent['allowed_save']) . "
                            WHERE id =" . $rowcontent['id']);

                    $user_content = defined('NV_IS_USER') ? ' | ' . $user_info['username'] : '';

                    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['update_content'], $rowcontent['title'] . ' | ' . $client_info['ip'] . $user_content, 0);
                } else {
                    $error = $lang_module['errorsave'];
                }
            }

            if (empty($error)) {
                $array_temp = array();

                if (defined('NV_IS_USER')) {
                    $array_temp['urlrefresh'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

                    if ($rowcontent['status']) {
                        $array_temp['content'] = $lang_module['save_content_ok'];
                        $nv_Cache->delMod($module_name);
                    } else {
                        $array_temp['content'] = $lang_module['save_content_waite'];
                    }
                } elseif ($rowcontent['status'] == 1 and sizeof($catids)) {
                    $catid = $catids[0];
                    $array_temp['urlrefresh'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'] . '/' . $rowcontent['alias'] . '-' . $rowcontent['id'];
                    $array_temp['content'] = $lang_module['save_content_view_page'];
                    $nv_Cache->delMod($module_name);
                } else {
                    $array_temp['urlrefresh'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;
                    $array_temp['content'] = $lang_module['save_content_waite_home'];
                }

                $template = $module_info['template'];

                if (!file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme'] . '/content.tpl')) {
                    $template = 'default';
                }

                $array_temp['urlrefresh'] = nv_url_rewrite($array_temp['urlrefresh'], true);

                $xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/' . $module_file);
                $xtpl->assign('DATA', $array_temp);
                $xtpl->parse('mainrefresh');
                $contents = $xtpl->text('mainrefresh');

                include NV_ROOTDIR . '/includes/header.php';
                echo nv_site_theme($contents);
                include NV_ROOTDIR . '/includes/footer.php';
            }
        }
    } elseif ($contentid > 0) {
        $rowcontent = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows where id=' . $contentid)->fetch();

        if (empty($rowcontent['id'])) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
        }

        $body_contents = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail where id=' . $rowcontent['id'])->fetch();
        $rowcontent = array_merge($rowcontent, $body_contents);
        unset($body_contents);
    }

    if (!empty($rowcontent['homeimgfile']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile'])) {
        $rowcontent['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile'];
    }

    $rowcontent['bodyhtml'] = htmlspecialchars(nv_editor_br2nl($rowcontent['bodyhtml']));
    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
        $htmlbodyhtml = nv_aleditor('bodyhtml', '100%', '300px', $rowcontent['bodyhtml'], 'Basic');
    } else {
        $htmlbodyhtml .= "<textarea class=\"textareaform\" name=\"bodyhtml\" id=\"bodyhtml\" cols=\"60\" rows=\"15\">" . $rowcontent['bodyhtml'] . "</textarea>";
    }

    if (!empty($error)) {
        $my_head .= "<script type=\"text/javascript\">\n";
        $my_head .= "   alert('" . $error . "')\n";
        $my_head .= "</script>\n";
    }

    $template = $module_info['template'];

    if (!file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme'] . '/content.tpl')) {
        $template = 'default';
    }

    $xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('OP', $module_info['alias']['content']);
    $xtpl->assign('DATA', $rowcontent);
    $xtpl->assign('HTMLBODYTEXT', $htmlbodyhtml);

    if ($global_config['captcha_type'] == 2) {
        $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
        $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
        $xtpl->parse('main.recaptcha');
    } else {
        $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
        $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
        $xtpl->assign('CAPTCHA_REFR_SRC', NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/refresh.png');
        $xtpl->assign('NV_GFX_NUM', NV_GFX_NUM);
        $xtpl->assign('CHECKSS', $checkss);
        $xtpl->parse('main.captcha');
    }

    // Xu ly alias
    if ($module_config[$module_name]['frontend_edit_alias'] == 1 and $rowcontent['id'] == 0) {
        $xtpl->parse('main.alias');
    }

    // Lua chon Layout
    if ($module_config[$module_name]['frontend_edit_layout'] == 1) {
        foreach ($layout_array as $value) {
            $value = preg_replace($global_config['check_op_layout'], '\\1', $value);
            $xtpl->assign('LAYOUT_FUNC', array(
                'key' => $value,
                'selected' => ($rowcontent['layout_func'] == $value) ? ' selected="selected"' : ''
            ));
            $xtpl->parse('main.layout_func.loop');
        }
        $xtpl->parse('main.layout_func');
    }

    $xtpl->assign('CONTENT_URL', $base_url . '&contentid=' . $rowcontent['id'] . '&checkss=' . $checkss);
    $array_catid_in_row = explode(',', $rowcontent['listcatid']);

    foreach ($array_catid_module as $value) {
        $xtitle_i = '';

        if ($value['lev'] > 0) {
            for ($i = 1; $i <= $value['lev']; ++$i) {
                $xtitle_i .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            }
        }

        $array_temp = array();
        $array_temp['value'] = $value['catid'];
        $array_temp['title'] = $xtitle_i . $value['title'];
        $array_temp['checked'] = (in_array($value['catid'], $array_catid_in_row)) ? ' checked="checked"' : '';

        $xtpl->assign('DATACATID', $array_temp);
        $xtpl->parse('main.catid');
    }

    foreach ($array_topic_module as $topicid_i => $title_i) {
        $array_temp = array();
        $array_temp['value'] = $topicid_i;
        $array_temp['title'] = $title_i;
        $array_temp['selected'] = ($topicid_i == $rowcontent['topicid']) ? ' selected="selecte"' : '';
        $xtpl->assign('DATATOPIC', $array_temp);
        $xtpl->parse('main.topic');
    }

    foreach ($array_imgposition as $id_imgposition => $title_imgposition) {
        $array_temp = array();
        $array_temp['value'] = $id_imgposition;
        $array_temp['title'] = $title_imgposition;
        $array_temp['selected'] = ($id_imgposition == $rowcontent['imgposition']) ? ' selected="selecte"' : '';

        $xtpl->assign('DATAIMGOP', $array_temp);
        $xtpl->parse('main.imgposition');
    }

    if (!($rowcontent['status'] and $rowcontent['id'])) {
        $xtpl->parse('main.save_temp');
    }

    if ($array_post_user['postcontent'] or ($rowcontent['status'] and $rowcontent['id'] and $array_post_user['editcontent'])) {
        $xtpl->parse('main.postcontent');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    if (empty($rowcontent['alias'])) {
        $contents .= "<script type=\"text/javascript\">\n";
        $contents .= '$("#idtitle").change(function () {
        get_alias("' . $module_info['alias']['content'] . '");
        });';
        $contents .= "</script>\n";
    }
} elseif (defined('NV_IS_USER')) {
    $page = 1;

    if (isset($array_op[1]) and substr($array_op[1], 0, 5) == 'page-') {
        $page = intval(substr($array_op[1], 5));
    }

    $contents = "<div style=\"border: 1px solid #ccc;margin: 10px; font-size: 15px; font-weight: bold; text-align: center;\"><a href=\"" . $base_url . "&amp;contentid=0&checkss=" . md5("0" . NV_CHECK_SESSION) . "\">" . $lang_module['add_content'] . "</a></h1></div>";

    $array_catpage = array();

    $db->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
        ->where('admin_id= ' . $user_info['userid'] . ' AND status<=' . $global_code_defined['row_locked_status']);

    $num_items = $db->query($db->sql())
        ->fetchColumn();
    if ($num_items) {
        $db->select('id, catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, status, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, hitstotal, hitscm, total_rating, click_rating')
            ->order('id DESC')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $result = $db->query($db->sql());
        while ($item = $result->fetch()) {
            if ($item['homeimgthumb'] == 1) {
                // image thumb
                $item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
            } elseif ($item['homeimgthumb'] == 2) {
                // image file
                $item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
            } elseif ($item['homeimgthumb'] == 3) {
                // image url
                $item['imghome'] = $item['homeimgfile'];
            } else {
                // no image
                $item['imghome'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/no_image.gif';
            }

            $item['is_edit_content'] = (empty($item['status']) or $array_post_user['editcontent']) ? 1 : 0;
            $item['is_del_content'] = (empty($item['status']) or $array_post_user['delcontent']) ? 1 : 0;

            $catid = $item['catid'];
            $item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $array_catpage[] = $item;
        }

        // parse content
        $xtpl = new XTemplate('viewcat_page.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('IMGWIDTH1', $module_config[$module_name]['homewidth']);

        $a = 0;
        foreach ($array_catpage as $array_row_i) {
            $array_row_i['publtime'] = nv_date('d/m/Y h:i:s A', $array_row_i['publtime']);
            $xtpl->assign('CONTENT', $array_row_i);
            $id = $array_row_i['id'];
            $array_link_content = array();

            if ($array_row_i['is_edit_content']) {
                $array_link_content[] = "<em class=\"fa fa-edit fa-lg\">&nbsp;</em> <a href=\"" . $base_url . "&amp;contentid=" . $id . "&amp;checkss=" . md5($id . NV_CHECK_SESSION) . "\">" . $lang_global['edit'] . "</a>";
            }

            if ($array_row_i['is_del_content']) {
                $array_link_content[] = "<em class=\"fa fa-trash-o fa-lg\">&nbsp;</em> <a onclick=\"return confirm(nv_is_del_confirm[0]);\" href=\"" . $base_url . "&amp;contentid=" . $id . "&amp;delcontent=1&amp;checkss=" . md5($id . NV_CHECK_SESSION) . "\">" . $lang_global['delete'] . "</a>";
            }

            if (!empty($array_link_content)) {
                $xtpl->assign('ADMINLINK', implode('&nbsp;-&nbsp;', $array_link_content));
                $xtpl->parse('main.viewcatloop.news.adminlink');
            }

            if ($array_row_i['imghome'] != '') {
                $xtpl->assign('HOMEIMG1', $array_row_i['imghome']);
                $xtpl->assign('HOMEIMGALT1', !empty($array_row_i['homeimgalt']) ? $array_row_i['homeimgalt'] : $array_row_i['title']);
                $xtpl->parse('main.viewcatloop.news.image');
            }

            $xtpl->parse('main.viewcatloop.news');
            ++$a;
        }
        $xtpl->parse('main.viewcatloop');

        $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);

        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.generate_page');
        }

        $xtpl->parse('main');
        $contents .= $xtpl->text('main');

        if ($page > 1) {
            $page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
        }
    }
} elseif ($array_post_user['addcontent']) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&contentid=0&checkss=' . md5('0' . NV_CHECK_SESSION));
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';