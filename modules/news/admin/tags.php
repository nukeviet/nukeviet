<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

/**
 * nv_show_tags_list()
 *
 * @param string $q
 * @param integer $incomplete
 * @return
 */
function nv_show_tags_list($q = '', $incomplete = false)
{
    global $db_slave, $lang_module, $lang_global, $module_name, $module_data, $op, $module_file, $global_config, $module_info, $module_config, $nv_Request;

    $db_slave->sqlreset()->select('*')->from(NV_PREFIXLANG . '_' . $module_data . '_tags')->order('alias ASC');

    if ($incomplete === true) {
        $db_slave->where('description = \'\'');
    }

    if (! empty($q)) {
        $q = strip_punctuation($q);
        $db_slave->where('keywords LIKE :keywords');
    } else {
        $per_page = $nv_Request->get_int('per_page', 'cookie', $module_config[$module_name]['per_page']);
        $db_slave->order('alias ASC')->limit($per_page);
    }

    $sth = $db_slave->prepare($db_slave->sql());
    if (! empty($q)) {
        $sth->bindValue(':keywords', '%' . $q . '%', PDO::PARAM_STR);
    }
    $sth->execute();

    $xtpl = new XTemplate('tags_lists.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);

    $number = 0;
    while ($row = $sth->fetch()) {
        $row['number'] = ++$number;
        $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['tag'] . '/' . $row['alias'];
        $row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;tid=' . $row['tid'] . ($incomplete === true ? '&amp;incomplete=1' : '') . '#edit';

        $xtpl->assign('ROW', $row);

        if (empty($row['description']) and $incomplete === false) {
            $xtpl->parse('main.loop.incomplete');
        }

        $xtpl->parse('main.loop');
    }
    $sth->closeCursor();

    if (empty($q) and $number >= $module_config[$module_name]['per_page']) {
        $xtpl->parse('main.other');
    }
    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    if (empty($contents)) {
        $contents = '&nbsp;';
    }
    return $contents;
}

$checkss = $nv_Request->get_string('checkss', 'get', '');
$del_listid = $nv_Request->get_string('del_listid', 'get', '');
if (!empty($del_listid) and NV_CHECK_SESSION == $checkss) {
    $del_listid = array_map('intval', explode(',', $del_listid));
    $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags WHERE tid IN (' . implode(',', $del_listid) . ')');
    $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE tid IN (' . implode(',', $del_listid) . ')');
}

if ($nv_Request->isset_request('del_tid', 'get')) {
    $tid = $nv_Request->get_int('del_tid', 'get', 0);
    if ($tid) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags WHERE tid=' . $tid);
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE tid=' . $tid);
    }
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_show_tags_list();
    include NV_ROOTDIR . '/includes/footer.php';
} elseif ($nv_Request->isset_request('q', 'get')) {
    $q = $nv_Request->get_title('q', 'get', '');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_show_tags_list($q);
    include NV_ROOTDIR . '/includes/footer.php';
}

$error = '';
$savecat = 0;
$incomplete = $nv_Request->get_bool('incomplete', 'get,post', false);
list($tid, $title, $alias, $description, $image, $keywords) = array( 0, '', '', '', '', '' );
$currentpath = NV_UPLOADS_DIR . '/' . $module_upload;

$savecat = $nv_Request->get_int('savecat', 'post', 0);
if (! empty($savecat)) {
    $tid = $nv_Request->get_int('tid', 'post', 0);
    $keywords = $nv_Request->get_title('keywords', 'post', '');
    $alias = $nv_Request->get_title('alias', 'post', '');
    $description = $nv_Request->get_string('description', 'post', '');
    $description = nv_nl2br(nv_htmlspecialchars(strip_tags($description)), '<br />');

    $keywords = explode(',', $keywords);
    $keywords[] = $alias;
    $keywords = array_map('trim', $keywords);
    $keywords = array_diff($keywords, array(''));
    $keywords = array_unique($keywords);
    $keywords = implode(',', $keywords);

    $alias = ($module_config[$module_name]['tags_alias']) ? get_mod_alias($alias) : change_alias_tags($alias);

    $image = $nv_Request->get_string('image', 'post', '');
    if (nv_is_file($image, NV_UPLOADS_DIR . '/' . $module_upload)) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
        $image = substr($image, $lu);
    } else {
        $image = '';
    }
    if (empty($alias)) {
        $error = $lang_module['error_name'];
    } else {
        if ($tid == 0) {
            $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tags (numnews, alias, description, image, keywords) VALUES (0, :alias, :description, :image, :keywords)');
            $msg_lg = 'add_tags';
        } else {
            $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET alias = :alias, description = :description, image = :image, keywords = :keywords WHERE tid =' . $tid);
            $msg_lg = 'edit_tags';
        }

        try {
            $sth->bindParam(':alias', $alias, PDO::PARAM_STR);
            $sth->bindParam(':description', $description, PDO::PARAM_STR);
            $sth->bindParam(':image', $image, PDO::PARAM_STR);
            $sth->bindParam(':keywords', $keywords, PDO::PARAM_STR);
            $sth->execute();

            nv_insert_logs(NV_LANG_DATA, $module_name, $msg_lg, $alias, $admin_info['userid']);
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . ($incomplete ? '&incomplete=1' : ''));
        } catch (PDOException $e) {
            $error = $lang_module['errorsave'];
        }
    }
}

$tid = $nv_Request->get_int('tid', 'get', 0);

if ($tid > 0) {
    list($tid, $alias, $description, $image, $keywords) = $db_slave->query('SELECT tid, alias, description, image, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags where tid=' . $tid)->fetch(3);
    $lang_module['add_tags'] = $lang_module['edit_tags'];
}

$lang_global['title_suggest_max'] = sprintf($lang_global['length_suggest_max'], 65);
$lang_global['description_suggest_max'] = sprintf($lang_global['length_suggest_max'], 160);

$xtpl = new XTemplate('tags.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$xtpl->assign('TAGS_LIST', nv_show_tags_list('', $incomplete));

$xtpl->assign('tid', $tid);
$xtpl->assign('alias', $alias);
$xtpl->assign('keywords', $keywords);
$xtpl->assign('description', nv_htmlspecialchars(nv_br2nl($description)));

if (! empty($image) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $image)) {
    $image = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $image;
    $currentpath = dirname($image);
}
$xtpl->assign('image', $image);
$xtpl->assign('UPLOAD_CURRENT', $currentpath);
$xtpl->assign('UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_upload);

if (! empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

// Nhac nho dang xem cac tags duoi dang khong co mo ta, thay doi gia tri submit form
if ($incomplete) {
    $xtpl->assign('ALL_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

    $xtpl->parse('main.incomplete');
    $xtpl->parse('main.incomplete_link');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['tags'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';