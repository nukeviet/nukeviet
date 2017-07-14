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

$id = $nv_Request->get_int('id', 'post,get', 0);
$copy = $nv_Request->get_int('copy', 'get,post',0);

if ($id) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
    $row = $db->query($sql)->fetch();

    if (empty($row)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    $page_title = $lang_module['edit'];
    $action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id;
} else {
    $page_title = $lang_module['add'];
    $action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

$selectthemes = (!empty($site_mods[$module_name]['theme'])) ? $site_mods[$module_name]['theme'] : $global_config['site_theme'];
$layout_array = nv_scandir(NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', $global_config['check_op_layout']);
$error = '';
$groups_list = nv_groups_list();

if ($nv_Request->get_int('save', 'post') == '1') {
    $row['title'] = $nv_Request->get_title('title', 'post', '', 1);
    $row['alias'] = $nv_Request->get_title('alias', 'post', '', 1);

    $image = $nv_Request->get_string('image', 'post', '');
    if (nv_is_file($image, NV_UPLOADS_DIR . '/' . $module_upload)) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
        $row['image'] = substr($image, $lu);
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
    $row['gid'] = $nv_Request->get_int('gid', 'post', 0);
    $row['hot_post'] = $nv_Request->get_int('hot_post', 'post', 0);

    $_groups_post = $nv_Request->get_array('activecomm', 'post', array());
    $row['activecomm'] = !empty($_groups_post) ? implode(',', nv_groups_post(array_intersect($_groups_post, array_keys($groups_list)))) : '';

    if (empty($row['title'])) {
        $error = $lang_module['empty_title'];
    } elseif (strip_tags($row['bodytext']) == '') {
        $error = $lang_module['empty_bodytext'];
    } elseif (empty($row['layout_func']) or in_array('layout.' . $row['layout_func'] . '.tpl', $layout_array)) {
        $row['alias'] = empty($row['alias']) ? change_alias($row['title']) : change_alias($row['alias']);

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
            $_sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET title = :title, alias = :alias, image = :image, imagealt = :imagealt, imageposition = :imageposition, description = :description, bodytext = :bodytext, keywords = :keywords, socialbutton = :socialbutton, activecomm = :activecomm, layout_func = :layout_func, gid = :gid, edit_time = ' . NV_CURRENTTIME . ', hot_post = :hot_post WHERE id =' . $id;
            $publtime = $row['add_time'];
        } else {
            if ($copy)
                $row['alias'] = 'copy-' . $id;
            if ($page_config['news_first']) {
                $weight = 1;
            } else {
                $weight = $db->query("SELECT MAX(weight) FROM " . NV_PREFIXLANG . "_" . $module_data)->fetchColumn();
                $weight = intval($weight) + 1;
            }

            $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '
				(title, alias, image, imagealt, imageposition, description, bodytext, keywords, socialbutton, activecomm, layout_func, gid, weight,admin_id, add_time, edit_time, status,hot_post) VALUES
				(:title, :alias, :image, :imagealt, :imageposition, :description, :bodytext, :keywords, :socialbutton, :activecomm, :layout_func, :gid, ' . $weight . ', ' . $admin_info['admin_id'] . ', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', 1,:hot_post)';

            $publtime = NV_CURRENTTIME;
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
            $sth->bindParam(':gid', $row['gid'], PDO::PARAM_INT);
            $sth->bindParam(':hot_post', $row['hot_post'], PDO::PARAM_INT);
            $sth->execute();

            if ($sth->rowCount()) {
                if ($id) {
                    if ($copy) {
                        $id_copy = $db->lastInsertId();
                        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET alias = ' . $db->quote(change_alias($row['title']) . '-' . $id_copy) . ' WHERE id=' . $id_copy);
                    }
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit', 'ID: ' . $id, $admin_info['userid']);
                } else {
                    if ($page_config['news_first']) {
                        $id = $db->lastInsertId();
                        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET weight=weight+1 WHERE id!=' . $id);
                    }

                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Add', ' ', $admin_info['userid']);
                }

                $nv_Cache->delMod($module_name);
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
            } else {
                $error = $lang_module['errorsave'];
            }
        } catch (PDOException $e) {
            $error = $lang_module['errorsave'];
        }
    }
} elseif (empty($id)) {
    $row['image'] = '';
    $row['imagealt'] = '';
    $row['imageposition'] = 0;
    $row['layout_func'] = '';
    $row['description'] = '';
    $row['bodytext'] = '';
    $row['activecomm'] = $module_config[$module_name]['setcomm'];
    $row['socialbutton'] = 1;
    $row['gid'] = 0;
    $row['hot_post'] = 0;
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$row['description'] = nv_htmlspecialchars(nv_br2nl($row['description']));
$row['bodytext'] = htmlspecialchars(nv_editor_br2nl($row['bodytext']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $row['bodytext'] = nv_aleditor('bodytext', '100%', '300px', $row['bodytext']);
} else {
    $row['bodytext'] = '<textarea style="width:100%;height:300px" name="bodytext">' . $row['bodytext'] . '</textarea>';
}

if (!empty($row['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'])) {
    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
}
$lang_global['title_suggest_max'] = sprintf($lang_global['length_suggest_max'], 65);
$lang_global['description_suggest_max'] = sprintf($lang_global['length_suggest_max'], 160);

$xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', $action);
$xtpl->assign('UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('DATA', $row);
$xtpl->assign('BODYTEXT', $row['bodytext']);
$xtpl->assign('SOCIALBUTTON', ($row['socialbutton']) ? ' checked="checked"' : '');
$xtpl->assign('HOST_POST', ($row['hot_post']) ? ' checked="checked"' : '');
$xtpl->assign('ISCOPY', $copy);

foreach ($layout_array as $value) {
    $value = preg_replace($global_config['check_op_layout'], '\\1', $value);
    $xtpl->assign('LAYOUT_FUNC', array(
        'key' => $value,
        'selected' => ($row['layout_func'] == $value) ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.layout_func');
}
$sql = "SELECT * FROM " . $db_config['prefix'] . "_googleplus ORDER BY weight ASC";
$_grows = $db->query($sql)->fetchAll();
if (sizeof($_grows)) {
    $array_googleplus = array();
    $array_googleplus[] = array(
        'gid' => -1,
        'title' => $lang_module['googleplus_1']
    );
    $array_googleplus[] = array(
        'gid' => 0,
        'title' => $lang_module['googleplus_0']
    );
    foreach ($_grows as $grow) {
        $array_googleplus[] = $grow;
    }
    foreach ($array_googleplus as $grow) {
        $grow['selected'] = ($row['gid'] == $grow['gid']) ? ' selected="selected"' : '';
        $xtpl->assign('GOOGLEPLUS', $grow);
        $xtpl->parse('main.googleplus.gid');
    }
    $xtpl->parse('main.googleplus');
}

$activecomm = explode(',', $row['activecomm']);
foreach ($groups_list as $_group_id => $_title) {
    $xtpl->assign('ACTIVECOMM', array(
        'value' => $_group_id,
        'checked' => in_array($_group_id, $activecomm) ? ' checked="checked"' : '',
        'title' => $_title
    ));
    $xtpl->parse('main.activecomm');
}

if (empty($row['alias'])) {
    $xtpl->parse('main.get_alias');
}

// position images
$array_imgposition = array(
    0 => $lang_module['imgposition_0'],
    1 => $lang_module['imgposition_1'],
    2 => $lang_module['imgposition_2']
);
foreach ($array_imgposition as $id_imgposition => $title_imgposition) {
    $sl = ($id_imgposition == $row['imageposition']) ? ' selected="selected"' : '';
    $xtpl->assign('id_imgposition', $id_imgposition);
    $xtpl->assign('title_imgposition', $title_imgposition);
    $xtpl->assign('posl', $sl);
    $xtpl->parse('main.looppos');
}

if ($error) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
