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

$id = $nv_Request->get_int('id', 'post,get', 0);
$copy = $nv_Request->get_int('copy', 'get,post', 0);

if ($id) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
    $row = $db->query($sql)->fetch();

    if (empty($row)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    /*
     * Khi sao chép bài viết chuyển liên kết tĩnh thành không trùng
     * người đăng bài có trách nhiệm tự thay thế liên kết tĩnh khác
     */
    if ($copy) {
        $row['alias'] .= '-copy' . nv_date('Hidmy');
    }

    $page_title = $nv_Lang->getModule('edit');
} else {
    $page_title = $nv_Lang->getModule('add');
}

if (!empty($global_config['over_capacity']) and !defined('NV_IS_GODADMIN')) {
    $contents = nv_theme_alert('', $nv_Lang->getGlobal('error_upload_over_capacity1'));
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$selectthemes = (!empty($site_mods[$module_name]['theme'])) ? $site_mods[$module_name]['theme'] : $global_config['site_theme'];
$layout_array = nv_scandir(NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', $global_config['check_op_layout']);
$groups_list = nv_groups_list();
$checkss = md5(NV_CHECK_SESSION . '-' . $module_name . '-' . $op . '-' . $id);

// Xử lý khi lưu (AJAX)
if ($checkss == $nv_Request->get_string('checkss', 'post')) {
    $respon = [
        'status' => 'error',
        'mess' => '',
    ];
    $row['title'] = nv_substr($nv_Request->get_title('title', 'post', ''), 0, 250);
    $row['alias'] = $nv_Request->get_title('alias', 'post', '');
    $row['alias'] = empty($row['alias']) ? change_alias($row['title']) : change_alias($row['alias']);
    if (!empty($page_config['alias_lower'])) {
        $row['alias'] = strtolower($row['alias']);
    }
    $row['alias'] = nv_substr($row['alias'], 0, 250);

    $image = $nv_Request->get_string('image', 'post', '');
    if (nv_is_file($image, NV_UPLOADS_DIR . '/' . $module_upload)) {
        $row['image'] = substr($image, strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } else {
        $row['image'] = '';
    }
    $row['imagealt'] = $nv_Request->get_title('imagealt', 'post', '', 1);
    $row['imageposition'] = $nv_Request->get_int('imageposition', 'post', 0);

    $row['description'] = $nv_Request->get_textarea('description', '', 'br', 1);
    $row['bodytext'] = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);
    $row['keywords'] = nv_strtolower($nv_Request->get_title('keywords', 'post', '', 0));

    $row['socialbutton'] = $nv_Request->get_int('socialbutton', 'post', 0);

    $row['layout_func'] = $nv_Request->get_title('layout_func', 'post', '');
    if (!empty($row['layout_func']) and !in_array('layout.' . $row['layout_func'] . '.tpl', $layout_array, true)) {
        $row['layout_func'] = '';
    }

    $row['hot_post'] = $nv_Request->get_int('hot_post', 'post', 0);

    $_groups_post = $nv_Request->get_array('activecomm', 'post', []);
    $row['activecomm'] = !empty($_groups_post) ? implode(',', nv_groups_post(array_intersect($_groups_post, array_keys($groups_list)))) : '';

    $row['schema_type'] = $nv_Request->get_title('schema_type', 'post', '');
    $row['schema_about'] = nv_substr($nv_Request->get_title('schema_about', 'post', ''), 0, 50);
    if (!array_key_exists($row['schema_type'], $schema_types)) {
        $row['schema_type'] = 'newsarticle';
    }
    if ($row['schema_type'] == 'webpage' and empty($row['schema_about'])) {
        $row['schema_about'] = 'Organization';
    }

    // Kiểm tra trùng
    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE alias=' . $db->quote($row['alias']);
    if ($id and !$copy) {
        $sql .= ' AND id!=' . $id;
    }
    $is_exists = $db->query($sql)->fetchColumn();

    if (empty($row['title'])) {
        $respon['input'] = 'title';
        $respon['mess'] = $nv_Lang->getModule('empty_title');
        nv_jsonOutput($respon);
    }
    if ($is_exists) {
        $respon['input'] = 'alias';
        $respon['mess'] = $nv_Lang->getModule('erroralias');
        nv_jsonOutput($respon);
    }
    if (trim($row['bodytext']) == '') {
        $respon['input'] = 'bodytext';
        $respon['mess'] = $nv_Lang->getModule('empty_bodytext');
        nv_jsonOutput($respon);
    }

    if (empty($row['keywords'])) {
        $row['keywords'] = nv_get_keywords($row['title']);
        if (empty($row['keywords'])) {
            $row['keywords'] = nv_unhtmlspecialchars($row['keywords']);
            $row['keywords'] = strip_punctuation($row['keywords']);
            $row['keywords'] = trim($row['keywords']);
            $row['keywords'] = nv_strtolower($row['keywords']);
            $row['keywords'] = preg_replace('/[ ]+/', ',', $row['keywords']);
        }
    }

    if ($id and !$copy) {
        $_sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET
            title = :title, alias = :alias, image = :image, imagealt = :imagealt,
            imageposition = :imageposition, description = :description,
            bodytext = :bodytext, keywords = :keywords, socialbutton = :socialbutton,
            activecomm = :activecomm, layout_func = :layout_func,
            edit_time = ' . NV_CURRENTTIME . ', hot_post = :hot_post, schema_type=:schema_type,
            schema_about=:schema_about
        WHERE id =' . $id;
    } else {
        if ($page_config['news_first']) {
            $weight = 1;
        } else {
            $weight = $db->query('SELECT MAX(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data)->fetchColumn();
            $weight = (int) $weight + 1;
        }

        $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (
            title, alias, image, imagealt, imageposition, description, bodytext, keywords,
            socialbutton, activecomm, layout_func, weight,admin_id, add_time, edit_time, status, hot_post,
            schema_type, schema_about
        ) VALUES (
            :title, :alias, :image, :imagealt, :imageposition, :description, :bodytext,
            :keywords, :socialbutton, :activecomm, :layout_func, ' . $weight . ',
            ' . $admin_info['admin_id'] . ', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', 1, :hot_post,
            :schema_type, :schema_about
        )';
    }

    try {
        $sth = $db->prepare($_sql);
        $sth->bindParam(':title', $row['title'], PDO::PARAM_STR);
        $sth->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
        $sth->bindParam(':image', $row['image'], PDO::PARAM_STR);
        $sth->bindParam(':imagealt', $row['imagealt'], PDO::PARAM_STR);
        $sth->bindParam(':imageposition', $row['imageposition'], PDO::PARAM_INT);
        $sth->bindParam(':description', $row['description'], PDO::PARAM_STR);
        $sth->bindParam(':bodytext', $row['bodytext'], PDO::PARAM_STR, strlen($row['bodytext']));
        $sth->bindParam(':keywords', $row['keywords'], PDO::PARAM_STR);
        $sth->bindParam(':socialbutton', $row['socialbutton'], PDO::PARAM_INT);
        $sth->bindParam(':activecomm', $row['activecomm'], PDO::PARAM_INT);
        $sth->bindParam(':layout_func', $row['layout_func'], PDO::PARAM_STR);
        $sth->bindParam(':hot_post', $row['hot_post'], PDO::PARAM_INT);
        $sth->bindParam(':schema_type', $row['schema_type'], PDO::PARAM_STR);
        $sth->bindParam(':schema_about', $row['schema_about'], PDO::PARAM_STR);
        $sth->execute();

        if ($sth->rowCount()) {
            if ($id and !$copy) {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit', 'ID: ' . $id, $admin_info['userid']);
            } else {
                if ($page_config['news_first']) {
                    $id = $db->lastInsertId();
                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET weight=weight+1 WHERE id!=' . $id);
                }

                nv_insert_logs(NV_LANG_DATA, $module_name, 'Add', ' ', $admin_info['userid']);
            }

            $nv_Cache->delMod($module_name);

            $respon['status'] = 'success';
            $respon['mess'] = $nv_Lang->getGlobal('save_success');
            $respon['redirect'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
            nv_jsonOutput($respon);
        } else {
            $respon['mess'] = $nv_Lang->getModule('errorsave');
            nv_jsonOutput($respon);
        }
    } catch (PDOException $e) {
        trigger_error(print_r($e, true));
        $respon['mess'] = $nv_Lang->getModule('errorsave');
        nv_jsonOutput($respon);
    }
} elseif (empty($id)) {
    $row['title'] = '';
    $row['alias'] = '';
    $row['image'] = '';
    $row['imagealt'] = '';
    $row['imageposition'] = 0;
    $row['layout_func'] = '';
    $row['description'] = '';
    $row['bodytext'] = '';
    $row['keywords'] = '';
    $row['activecomm'] = $module_config[$module_name]['setcomm'];
    $row['socialbutton'] = 1;
    $row['hot_post'] = 0;
    $row['schema_type'] = $page_config['schema_type'];
    $row['schema_about'] = $schema_abouts[$page_config['schema_about']] ?? 'Organization';
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$row['description'] = nv_htmlspecialchars(nv_br2nl($row['description']));
$row['bodytext'] = htmlspecialchars(nv_editor_br2nl($row['bodytext']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $row['bodytext'] = nv_aleditor('bodytext', '100%', '400px', $row['bodytext'], '', NV_UPLOADS_DIR . '/' . $module_upload, NV_UPLOADS_DIR . '/' . $module_upload);
} else {
    $row['bodytext'] = '<textarea class="form-control" name="bodytext" id="bodytext" rows="15">' . $row['bodytext'] . '</textarea>';
}

if (!empty($row['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'])) {
    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
}
$nv_Lang->setGlobal('title_suggest_max', $nv_Lang->getGlobal('length_suggest_max', 65));
$nv_Lang->setGlobal('description_suggest_max', $nv_Lang->getGlobal('length_suggest_max', 160));

// Chuẩn bị mảng layout
$layout_list = [];
foreach ($layout_array as $value) {
    $layout_list[] = preg_replace($global_config['check_op_layout'], '\\1', $value);
}

// Chuẩn bị mảng activecomm
$activecomm = array_map('intval', explode(',', $row['activecomm']));

// Chuẩn bị mảng vị trí ảnh
$array_imgposition = [
    0 => $nv_Lang->getModule('imgposition_0'),
    1 => $nv_Lang->getModule('imgposition_1'),
    2 => $nv_Lang->getModule('imgposition_2')
];

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('content.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('ID', $id);
$tpl->assign('UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_upload);
$tpl->assign('UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_upload);
$tpl->assign('DATA', $row);
$tpl->assign('ISCOPY', $copy);
$tpl->assign('LAYOUT_ARRAY', $layout_list);
$tpl->assign('GROUPS_LIST', $groups_list);
$tpl->assign('ACTIVECOMM', $activecomm);
$tpl->assign('ARRAY_IMGPOSITION', $array_imgposition);
$tpl->assign('SCHEMA_TYPES', $schema_types);
$tpl->assign('CHECKSS', $checkss);

$contents = $tpl->fetch('content.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
