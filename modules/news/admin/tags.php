<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

// Lấy tags từ nội dung bài viết
if ($nv_Request->isset_request('getTagsFromContent', 'post')) {
    $content = $nv_Request->get_title('content', 'post', '');
    $tags = nv_get_mod_tags($content);
    nv_jsonOutput($tags);
}

// Xóa các liên kết
if ($nv_Request->isset_request('tagsIdDel', 'post')) {
    $tid = $nv_Request->get_int('tid', 'post', 0);
    $ids = $nv_Request->get_title('ids', 'post', '');
    if (!empty($ids) and !empty($tid)) {
        $ids = preg_replace('/[^0-9\,]+/', '', $ids);
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE tid = ' . $tid . ' AND id IN (' . $ids . ')');

        $num = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id where tid=' . $tid)->fetchColumn();
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews=' . $num . ' WHERE tid=' . $tid);
    }

    exit('ok');
}

// Xóa tất cả liên kết
if ($nv_Request->isset_request('tagsIdAllDel', 'post')) {
    $tid = $nv_Request->get_int('tid', 'post', 0);
    if (!empty($tid)) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE tid = ' . $tid);
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews=0 WHERE tid=' . $tid);
    }

    exit('ok');
}

// Sửa keyword
if ($nv_Request->isset_request('keywordEdit', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $tid = $nv_Request->get_int('tid', 'post', 0);
    $keyword = $nv_Request->get_title('keyword', 'post', '');
    if (!empty($keyword)) {
        $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id SET keyword = :keyword WHERE id=' . $id . ' AND tid =' . $tid);
        $sth->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        $sth->execute();
    }

    exit('ok');
}

$checkss = $nv_Request->get_string('checkss', 'post', '');
// Xóa nhiều tags
if ($nv_Request->isset_request('del_listid', 'post')) {
    $del_listid = $nv_Request->get_string('del_listid', 'post', '');
    $del_listid = array_map('intval', explode(',', $del_listid));
    $del_listid = array_filter($del_listid);
    if (!empty($del_listid) and NV_CHECK_SESSION == $checkss) {
        $del_listid = implode(',', $del_listid);
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags WHERE tid IN (' . $del_listid . ')');
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE tid IN (' . $del_listid . ')');
    }

    exit('ok');
}

// Xóa tag
if ($nv_Request->isset_request('del_tid', 'post')) {
    $tid = $nv_Request->get_int('del_tid', 'post', 0);
    if (!empty($tid) and NV_CHECK_SESSION == $checkss) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags WHERE tid=' . $tid);
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE tid=' . $tid);
    }

    exit('ok');
}

// Thêm nhiều tags
if ($nv_Request->isset_request('savetag', 'post')) {
    $title = $nv_Request->get_textarea('mtitle', '', NV_ALLOWED_HTML_TAGS, true);
    $list_tag = explode('<br />', strip_tags($title, '<br>'));
    $added = [];
    $aliases = [];
    foreach ($list_tag as $tag_i) {
        $keywords = trim(strip_tags($tag_i));
        if (nv_strlen($keywords) >= 2) {
            $dbexist = false;
            $keywords = setTagKeywords($keywords);
            $alias = setTagAlias($keywords, 0, $dbexist);
            if (!$dbexist) {
                $title = nv_ucfirst($keywords);
                $sth = $db->prepare('INSERT IGNORE INTO ' . NV_PREFIXLANG . '_' . $module_data . "_tags (title, alias, description, keywords) VALUES (:title, :alias, '', :keywords)");
                $sth->bindParam(':title', $title, PDO::PARAM_STR);
                $sth->bindParam(':alias', $alias, PDO::PARAM_STR);
                $sth->bindParam(':keywords', $keywords, PDO::PARAM_STR);
                $sth->execute();
                $added[] = $keywords;
                $aliases[] = $alias;
            }
        }
    }

    if (empty($added)) {
        exit($nv_Lang->getModule('add_multi_tags_empty'));
    }
    $added = implode('; ', $added);
    $aliases = implode('; ', $aliases);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'add_multi_tags', $aliases, $admin_info['userid']);
    exit($nv_Lang->getModule('add_multi_tags') . ': ' . $added);
}

// Thêm tag hoặc sửa tag
if ($nv_Request->isset_request('savecat', 'post')) {
    $tid = $nv_Request->get_int('tid', 'post', 0);
    if (!empty($tid)) {
        $num = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags where tid=' . $tid)->fetchColumn();
        if (!$num) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'tid',
                'message' => $nv_Lang->getModule('error_tag_tid')
            ]);
        }
    }

    $keywords = $nv_Request->get_title('keywords', 'post', '');
    if (nv_strlen($keywords) < 2) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'keywords',
            'message' => $nv_Lang->getModule('error_tag_keywords')
        ]);
    }
    $dbexist = false;
    $keywords = setTagKeywords($keywords);
    $alias = setTagAlias($keywords, $tid, $dbexist);

    if ($dbexist) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'keywords',
            'message' => $nv_Lang->getModule('error_tag_keywords_exist')
        ]);
    }

    $title = $nv_Request->get_title('title', 'post', '');
    if (nv_strlen($title) < 2) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'title',
            'message' => $nv_Lang->getModule('error_tag_title')
        ]);
    }

    $description = $nv_Request->get_string('description', 'post', '');
    $description = nv_nl2br(nv_htmlspecialchars(strip_tags($description)), '<br />');

    $image = $nv_Request->get_string('image', 'post', '');
    if (nv_is_file($image, NV_UPLOADS_DIR . '/' . $module_upload)) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
        $image = substr($image, $lu);
    } else {
        $image = '';
    }

    if (empty($tid)) {
        $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tags (title, alias, description, image, keywords) VALUES (:title, :alias, :description, :image, :keywords)');
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
        nv_jsonOutput([
            'status' => 'ok'
        ]);
    } catch (PDOException $e) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => '',
            'message' => $nv_Lang->getModule('errorsave')
        ]);
    }
}

// Danh sách liên kết
if ($nv_Request->isset_request('tagLinks', 'post')) {
    $tid = $nv_Request->get_int('tid', 'post', 0);
    list($tid, $keywords) = $db_slave->query('SELECT tid, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags where tid=' . $tid)->fetch(3);
    if (empty($tid)) {
        exit('');
    }
    $keywords = explode(',', $keywords);
    $keywords = array_map('trim', $keywords);

    $sql = 'SELECT a.id, a.keyword, b.catid, b.title, b.alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id a, ' . NV_PREFIXLANG . '_' . $module_data . '_rows b WHERE a.tid=' . $tid . ' AND a.id=b.id';
    $result = $db_slave->query($sql);

    $xtpl = new XTemplate('tags.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
    $xtpl->assign('TID', $tid);

    while ($row = $result->fetch()) {
        $row['url'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$row['catid']]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'], true);
        $xtpl->assign('ROW', $row);

        if (!in_array($row['keyword'], $keywords, true)) {
            $xtpl->parse('taglinks.loop.invalid');
        }

        foreach ($keywords as $ks) {
            $xtpl->assign('KEYS', [
                'val' => $ks,
                'sel' => $ks == $row['keyword'] ? ' selected="selected"' : ''
            ]);
            $xtpl->parse('taglinks.loop.keyword');
        }
        $xtpl->parse('taglinks.loop');
    }

    $xtpl->parse('taglinks');
    $contents = $xtpl->text('taglinks');

    echo $contents;
    exit();
}

// Xuất form thêm nhiều tags
if ($nv_Request->isset_request('addMultiTags', 'post')) {
    $xtpl = new XTemplate('tags.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

    $xtpl->parse('add_multi_tags');
    $contents = $xtpl->text('add_multi_tags');

    echo $contents;
    exit();
}

// Xuất form thêm hoặc sửa tags
if ($nv_Request->isset_request('addTag', 'post') or $nv_Request->isset_request('editTag', 'post')) {
    $tid = 0;
    $title = $description = $image = $keywords = '';

    if ($nv_Request->isset_request('editTag', 'post')) {
        $tid = $nv_Request->get_int('tid', 'post', 0);
        list($tid, $title, $description, $image, $keywords) = $db_slave->query('SELECT tid, title, description, image, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags where tid=' . $tid)->fetch(3);
        if (empty($tid)) {
            exit('');
        }
    }

    $nv_Lang->setGlobal('title_suggest_max', $nv_Lang->getGlobal('length_suggest_max', 65));
    $nv_Lang->setGlobal('description_suggest_max', $nv_Lang->getGlobal('length_suggest_max', 160));

    $xtpl = new XTemplate('tags.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
    $xtpl->assign('TID', $tid);
    $xtpl->assign('TITLE', $title);
    $xtpl->assign('KEYWORDS', $keywords);
    $xtpl->assign('DESCRIPTION', nv_htmlspecialchars(nv_br2nl($description)));

    $currentpath = NV_UPLOADS_DIR . '/' . $module_upload;
    if (!empty($image) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $image)) {
        $image = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $image;
        $currentpath = dirname($image);
    }
    $xtpl->assign('IMAGE', $image);
    $xtpl->assign('UPLOAD_CURRENT', $currentpath);
    $xtpl->assign('UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_upload);

    $xtpl->parse('add_tag');
    $contents = $xtpl->text('add_tag');

    echo $contents;
    exit();
}

// Mặc định hiển thị danh sách tags
$complete = $nv_Request->get_bool('complete', 'get,post', false);
$incomplete = $nv_Request->get_bool('incomplete', 'get,post', false);
$page = $nv_Request->get_absint('page', 'get', 1);
$per_page = 20;
$where = [];
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

if ($incomplete === true) {
    $where[] = "description = ''";
    $base_url .= '&amp;incomplete=1';
} elseif ($complete === true) {
    $where[] = "description != ''";
    $base_url .= '&amp;complete=1';
}

$q = $nv_Request->get_title('q', 'get', '');
if (nv_strlen($q) > 2) {
    $where[] = "keywords LIKE '%" . $db_slave->dblikeescape($q) . "%'";
    $base_url .= '&amp;q=' . urlencode($q);
}

$where = !empty($where) ? implode(' AND ', $where) : '';

$db_slave->sqlreset()
    ->select('COUNT(tid)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_tags')
    ->where($where);

$sth = $db_slave->prepare($db_slave->sql());
$sth->execute();
$num_items = $sth->fetchColumn();

$nv_Lang->setGlobal('title_suggest_max', $nv_Lang->getGlobal('length_suggest_max', 65));
$nv_Lang->setGlobal('description_suggest_max', $nv_Lang->getGlobal('length_suggest_max', 160));

$xtpl = new XTemplate('tags.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ALL_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('COMPLETE_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;complete=1');
$xtpl->assign('INCOMPLETE_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;incomplete=1');
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . ($incomplete === true ? '&amp;incomplete=1' : ($complete === true ? '&amp;complete=1' : '')));
$xtpl->assign('Q', $q);

if ($incomplete) {
    $xtpl->parse('main.incomplete_link');
    $caption = $nv_Lang->getModule('tags_incomplete_link');
} elseif ($complete) {
    $xtpl->parse('main.complete_link');
    $caption = $nv_Lang->getModule('tags_complete_link');
} else {
    $xtpl->parse('main.all_link');
    $caption = $nv_Lang->getModule('tags_all_link');
}

if ($num_items) {
    $xtpl->assign('LIST_CAPTION', $caption . ': ' . $num_items);

    $db_slave->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_tags')
        ->where($where)
        ->order('title ASC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);

    $sth = $db_slave->prepare($db_slave->sql());
    $sth->execute();

    $number = 0;
    while ($row = $sth->fetch()) {
        $row['number'] = ++$number;
        $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['tag'] . '/' . $row['alias'];
        $row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;tid=' . $row['tid'] . ($incomplete === true ? '&amp;incomplete=1' : '') . '#edit';
        if (empty($row['title'])) {
            $row['title'] = nv_ucfirst($row['keywords']);
            $sths = $db_slave->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET title = :title WHERE tid =' . $row['tid']);
            $sths->bindParam(':title', $row['title'], PDO::PARAM_STR);
            $sths->execute();
        }
        $xtpl->assign('ROW', $row);

        if (empty($row['description'])) {
            $xtpl->parse('main.show_list.loop.incomplete');
        } else {
            $xtpl->parse('main.show_list.loop.complete');
        }

        if (empty($row['numnews'])) {
            $xtpl->parse('main.show_list.loop.nolink');
        }

        $xtpl->parse('main.show_list.loop');
    }
    $sth->closeCursor();

    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.show_list.generate_page');
    }

    $xtpl->parse('main.show_list');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $nv_Lang->getModule('tags_manage');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
