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

/**
 * nv_show_tags_list()
 *
 * @param string $q
 * @param bool   $incomplete
 * @return string
 */
function nv_show_tags_list($q = '', $incomplete = false)
{
    global $db_slave, $lang_module, $lang_global, $module_name, $module_data, $op, $module_file, $global_config, $module_info, $module_config, $nv_Request;
    $page = $nv_Request->get_absint('page', 'get', 1);
    $per_page_old = $nv_Request->get_absint('per_page_tagadmin_' . $module_data, 'cookie', 50);
    $per_page = $nv_Request->get_absint('per_page', 'get', $per_page_old);

    if ($per_page < 1 and $per_page > 500) {
        $per_page = 50;
    }
    if ($per_page_old != $per_page) {
        $nv_Request->set_Cookie('per_page_tagadmin_' . $module_data, $per_page, NV_LIVE_COOKIE_TIME);
    }

    $where = [];
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;per_page=' . $per_page;
    if (!empty($q)) {
        $where[] = "keywords LIKE '%" . $db_slave->dblikeescape($q) . "%'";
        $base_url .= '&amp;q=' . urlencode($q);
    }
    if ($incomplete === true) {
        $where[] = "description = ''";
        $base_url .= '&amp;incomplete=1';
    }

    $db_slave->sqlreset()
        ->select('COUNT(tid)')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_tags')
        ->where(implode(' AND ', $where));

    $sth = $db_slave->prepare($db_slave->sql());
    $sth->execute();
    $num_items = $sth->fetchColumn();

    $db_slave->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_tags')
        ->where(implode(' AND ', $where))
        ->order('alias ASC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);

    $sth = $db_slave->prepare($db_slave->sql());
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

    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
    if (!empty($q)) {
        $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page, true, true, 'nv_urldecode_ajax', 'module_show_list');
    }
    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
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
list($tid, $title, $alias, $description, $image, $keywords) = [0, '', '', '', '', ''];
$currentpath = NV_UPLOADS_DIR . '/' . $module_upload;

$savetag = $nv_Request->get_int('savetag', 'post', 0);
if (!empty($savetag)) {
    $title = $nv_Request->get_textarea('mtitle', '', NV_ALLOWED_HTML_TAGS, true);
    $list_tag = explode('<br />', strip_tags($title, '<br>'));
    foreach ($list_tag as $tag_i) {
        $sth = $db->prepare('INSERT IGNORE INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tags (numnews, title, alias, keywords) VALUES (0, :title, :alias, :keywords)');
        $sth->bindParam(':title', $tag_i, PDO::PARAM_STR);
        $sth->bindParam(':alias', change_alias_tags($tag_i), PDO::PARAM_STR);
        $sth->bindParam(':keywords', $tag_i, PDO::PARAM_STR);
        $sth->execute();
        nv_insert_logs(NV_LANG_DATA, $module_name, 'add_multil_tags', change_alias_tags($tag_i), $admin_info['userid']);
    }
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . ($incomplete ? '&incomplete=1' : ''));
}

$savecat = $nv_Request->get_int('savecat', 'post', 0);
if (!empty($savecat)) {
    $tid = $nv_Request->get_int('tid', 'post', 0);
    $title = $nv_Request->get_title('title', 'post', '');
    $keywords = $nv_Request->get_title('keywords', 'post', '');
    $alias = $nv_Request->get_title('alias', 'post', '');
    $description = $nv_Request->get_string('description', 'post', '');
    $description = nv_nl2br(nv_htmlspecialchars(strip_tags($description)), '<br />');

    $keywords = explode(',', $keywords);
    $keywords = array_map('trim', $keywords);
    $keywords = array_diff($keywords, ['']);
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
            $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tags (numnews, title, alias, description, image, keywords) VALUES (0, :title, :alias, :description, :image, :keywords)');
            $msg_lg = 'add_tags';
        } else {
            $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET title = :title, alias = :alias, description = :description, image = :image, keywords = :keywords WHERE tid =' . $tid);
            $msg_lg = 'edit_tags';
        }

        try {
            $sth->bindParam(':title', $title, PDO::PARAM_STR);
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
    list($tid, $title, $alias, $description, $image, $keywords) = $db_slave->query('SELECT tid, title, alias, description, image, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags where tid=' . $tid)->fetch(3);
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
$xtpl->assign('title', $title);
$xtpl->assign('alias', $alias);
$xtpl->assign('keywords', $keywords);
$xtpl->assign('description', nv_htmlspecialchars(nv_br2nl($description)));

if (!empty($image) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $image)) {
    $image = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $image;
    $currentpath = dirname($image);
}
$xtpl->assign('image', $image);
$xtpl->assign('UPLOAD_CURRENT', $currentpath);
$xtpl->assign('UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_upload);

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

if (empty($rowcontent['alias'])) {
    $xtpl->parse('main.getalias');
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
