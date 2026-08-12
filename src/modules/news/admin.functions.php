<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

if ($NV_IS_ADMIN_MODULE) {
    define('NV_IS_ADMIN_MODULE', true);
}

if ($NV_IS_ADMIN_FULL_MODULE) {
    define('NV_IS_ADMIN_FULL_MODULE', true);
}

define('NV_MIN_MEDIUM_SYSTEM_ROWS', 100000);

$array_viewcat_full = [
    'viewcat_page_new' => $nv_Lang->getModule('viewcat_page_new'),
    'viewcat_page_old' => $nv_Lang->getModule('viewcat_page_old'),
    'viewcat_list_new' => $nv_Lang->getModule('viewcat_list_new'),
    'viewcat_list_old' => $nv_Lang->getModule('viewcat_list_old'),
    'viewcat_grid_new' => $nv_Lang->getModule('viewcat_grid_new'),
    'viewcat_grid_old' => $nv_Lang->getModule('viewcat_grid_old'),
    'viewcat_main_left' => $nv_Lang->getModule('viewcat_main_left'),
    'viewcat_main_right' => $nv_Lang->getModule('viewcat_main_right'),
    'viewcat_main_bottom' => $nv_Lang->getModule('viewcat_main_bottom'),
    'viewcat_two_column' => $nv_Lang->getModule('viewcat_two_column'),
    'viewcat_none' => $nv_Lang->getModule('viewcat_none')
];
$array_viewcat_nosub = [
    'viewcat_page_new' => $nv_Lang->getModule('viewcat_page_new'),
    'viewcat_page_old' => $nv_Lang->getModule('viewcat_page_old'),
    'viewcat_list_new' => $nv_Lang->getModule('viewcat_list_new'),
    'viewcat_list_old' => $nv_Lang->getModule('viewcat_list_old'),
    'viewcat_grid_new' => $nv_Lang->getModule('viewcat_grid_new'),
    'viewcat_grid_old' => $nv_Lang->getModule('viewcat_grid_old')
];

$array_allowed_comm = [
    $nv_Lang->getGlobal('no'),
    $nv_Lang->getGlobal('level6'),
    $nv_Lang->getGlobal('level4')
];

// Xác định layout giao diện của module đang dùng
$selectthemes = (!empty($site_mods[$module_name]['theme'])) ? $site_mods[$module_name]['theme'] : $global_config['site_theme'];
$layout_array = nv_scandir(NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', $global_config['check_op_layout']);

define('NV_IS_FILE_ADMIN', true);
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

// Documents
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news';
$array_url_instruction['cat'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news#quản_ly_chuyen_mục';
$array_url_instruction['content'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news#them_bai_viet';
$array_url_instruction['tags'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news#quản_ly_tags';
$array_url_instruction['groups'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news#cac_nhom_tin';
$array_url_instruction['topics'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news#theo_dong_sự_kiện';
$array_url_instruction['sources'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news#nguồn_tin';
$array_url_instruction['admins'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news#phan_quyền_quản_ly';
$array_url_instruction['setting'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news#cấu_hinh_module';

global $global_array_cat;
$global_array_cat = [];
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat ORDER BY sort ASC';
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $global_array_cat[$row['catid']] = $row;
}
$result->closeCursor();

/**
 * nv_fix_cat_order()
 *
 * @param int $parentid
 * @param int $order
 * @param int $lev
 */
function nv_fix_cat_order($parentid = 0, $order = 0, $lev = 0)
{
    global $db, $module_data;

    $stmt = $db->prepare('SELECT catid, parentid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid=:parentid ORDER BY weight ASC');
    $stmt->bindValue(':parentid', $parentid, PDO::PARAM_INT);
    $stmt->execute();
    $array_cat_order = [];
    while ($row = $stmt->fetch()) {
        $array_cat_order[] = $row['catid'];
    }
    $stmt->closeCursor();

    $weight = 0;
    if ($parentid > 0) {
        ++$lev;
    } else {
        $lev = 0;
    }

    foreach ($array_cat_order as $catid_i) {
        ++$order;
        ++$weight;
        $stmt_update = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=:weight, sort=:sort, lev=:lev WHERE catid=:catid');
        $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_update->bindValue(':sort', $order, PDO::PARAM_INT);
        $stmt_update->bindValue(':lev', $lev, PDO::PARAM_INT);
        $stmt_update->bindValue(':catid', $catid_i, PDO::PARAM_INT);
        $stmt_update->execute();

        $order = nv_fix_cat_order($catid_i, $order, $lev);
    }
    $numsubcat = $weight;
    if ($parentid > 0) {
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET numsubcat=:numsubcat';
        if ($numsubcat == 0) {
            // Chuyên mục cha không có chuyên mục con
            $sql .= ", subcatid='', viewcat=CASE
            WHEN viewcat='viewcat_main_left' THEN 'viewcat_page_new'
            WHEN viewcat='viewcat_main_right' THEN 'viewcat_page_new'
            WHEN viewcat='viewcat_main_bottom' THEN 'viewcat_page_new'
            WHEN viewcat='viewcat_two_column' THEN 'viewcat_page_new'
            ELSE viewcat END";
        } else {
            $sql .= ", subcatid=:subcatid";
        }
        $sql .= " WHERE catid=:catid";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':numsubcat', $numsubcat, PDO::PARAM_INT);
        if ($numsubcat > 0) {
            $stmt->bindValue(':subcatid', implode(',', $array_cat_order), PDO::PARAM_STR);
        }
        $stmt->bindValue(':catid', $parentid, PDO::PARAM_INT);
        $stmt->execute();
    }

    return $order;
}

/**
 * nv_fix_topic()
 */
function nv_fix_topic()
{
    global $db, $module_data;
    $stmt = $db->query('SELECT topicid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics ORDER BY weight ASC');
    $weight = 0;
    while ($_row_topic = $stmt->fetch()) {
        ++$weight;
        $stmt_update = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_topics SET weight= :weight WHERE topicid= :topicid');
        $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_update->bindValue(':topicid', $_row_topic['topicid'], PDO::PARAM_INT);
        $stmt_update->execute();
    }
    $stmt->closeCursor();
}

/**
 * nv_fix_block_cat()
 */
function nv_fix_block_cat()
{
    global $db, $module_data;
    $stmt = $db->query('SELECT bid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat ORDER BY weight ASC');
    $weight = 0;
    while ($_row_bcat = $stmt->fetch()) {
        ++$weight;
        $stmt_update = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat SET weight= :weight WHERE bid= :bid');
        $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_update->bindValue(':bid', $_row_bcat['bid'], PDO::PARAM_INT);
        $stmt_update->execute();
    }
    $stmt->closeCursor();
}

/**
 * nv_fix_source()
 */
function nv_fix_source()
{
    global $db, $module_data;
    $stmt = $db->query('SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources ORDER BY weight ASC');
    $weight = 0;
    while ($_row_src = $stmt->fetch()) {
        ++$weight;
        $stmt_update = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_sources SET weight= :weight WHERE sourceid= :sourceid');
        $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_update->bindValue(':sourceid', $_row_src['sourceid'], PDO::PARAM_INT);
        $stmt_update->execute();
    }
    $stmt->closeCursor();
}

/**
 * @param mixed $bid
 */
function nv_news_fix_block($bid)
{
    global $db, $module_data;
    $bid = (int) $bid;
    if ($bid > 0) {
        $stmt = $db->prepare('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid = :bid ORDER BY weight ASC');
        $stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
        $stmt->execute();
        $weight = 0;

        $stmt_update = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block SET weight= :weight WHERE bid= :bid AND id= :id');
        $stmt_delete = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid= :bid AND id= :id');
        while ($_row_blk = $stmt->fetch()) {
            ++$weight;
            if ($weight <= 100) {
                $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
                $stmt_update->bindValue(':bid', $bid, PDO::PARAM_INT);
                $stmt_update->bindValue(':id', $_row_blk['id'], PDO::PARAM_INT);
                $stmt_update->execute();
            } else {
                $stmt_delete->bindValue(':bid', $bid, PDO::PARAM_INT);
                $stmt_delete->bindValue(':id', $_row_blk['id'], PDO::PARAM_INT);
                $stmt_delete->execute();
            }
        }
        $stmt->closeCursor();
    }
}

/**
 * GetCatidInParent()
 *
 * @param mixed $catid
 */
function GetCatidInParent($catid)
{
    global $global_array_cat;
    $array_cat = [];
    $array_cat[] = $catid;
    $subcatid = explode(',', $global_array_cat[$catid]['subcatid']);
    if (!empty($subcatid)) {
        foreach ($subcatid as $id) {
            if ($id > 0) {
                if ($global_array_cat[$id]['numsubcat'] == 0) {
                    $array_cat[] = $id;
                } else {
                    $array_cat_temp = GetCatidInParent($id);
                    foreach ($array_cat_temp as $catid_i) {
                        $array_cat[] = $catid_i;
                    }
                }
            }
        }
    }

    return array_unique($array_cat);
}

/**
 * redriect()
 *
 * @param string $msg1
 * @param string $msg2
 * @param mixed  $nv_redirect
 * @param mixed  $autoSaveKey
 * @param mixed  $go_back
 */
function redriect($msg1, $msg2, $nv_redirect, $autoSaveKey = '', $go_back = '')
{
    global $module_name, $nv_Lang, $op;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('redriect.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);

    if (empty($nv_redirect)) {
        $nv_redirect = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
    }
    $tpl->assign('NV_REDIRECT', $nv_redirect);
    $tpl->assign('MSG1', $msg1);
    $tpl->assign('MSG2', $msg2);
    $tpl->assign('AUTOSAVEKEY', $autoSaveKey);

    if (nv_strlen($msg1) > 255) {
        $tpl->assign('REDRIECT_T1', 20);
        $tpl->assign('REDRIECT_T2', 20000);
    } else {
        $tpl->assign('REDRIECT_T1', 5);
        $tpl->assign('REDRIECT_T2', 5000);
    }
    $tpl->assign('GO_BACK', $go_back ? 1 : 0);

    $contents = $tpl->fetch('redriect.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

/**
 * get_mod_alias()
 *
 * @param mixed  $title
 * @param string $mod
 * @param int    $id
 */
function get_mod_alias($title, $mod = '', $id = 0)
{
    global $module_data, $module_config, $module_name, $db;

    if (empty($title)) {
        return '';
    }

    $alias = change_alias($title);
    if ($module_config[$module_name]['alias_lower']) {
        $alias = strtolower($alias);
    }
    $id = (int) $id;

    if ($mod == 'cat') {
        $tab = NV_PREFIXLANG . '_' . $module_data . '_cat';
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . $tab . ' WHERE catid!=' . $id . ' AND alias= :alias');
        $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
        $stmt->execute();
        $nb = $stmt->fetchColumn();
        if (!empty($nb)) {
            $nb = $db->query('SELECT MAX(catid) FROM ' . $tab)->fetchColumn();

            $alias .= '-' . ((int) $nb + 1);
        }
    } elseif ($mod == 'topics') {
        $tab = NV_PREFIXLANG . '_' . $module_data . '_topics';
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . $tab . ' WHERE topicid!=' . $id . ' AND alias= :alias');
        $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
        $stmt->execute();
        $nb = $stmt->fetchColumn();
        if (!empty($nb)) {
            $nb = $db->query('SELECT MAX(topicid) FROM ' . $tab)->fetchColumn();

            $alias .= '-' . ((int) $nb + 1);
        }
    } elseif ($mod == 'blockcat') {
        $tab = NV_PREFIXLANG . '_' . $module_data . '_block_cat';
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . $tab . ' WHERE bid!=' . $id . ' AND alias= :alias');
        $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
        $stmt->execute();
        $nb = $stmt->fetchColumn();
        if (!empty($nb)) {
            $nb = $db->query('SELECT MAX(bid) FROM ' . $tab)->fetchColumn();

            $alias .= '-' . ((int) $nb + 1);
        }
    }

    return $alias;
}

/**
 * nv_get_mod_countrows()
 */
function nv_get_mod_countrows()
{
    global $module_data, $nv_Cache, $module_name;
    $sql = 'SELECT COUNT(*) totalnews FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows';
    $list = $nv_Cache->db($sql, '', $module_name);

    return $list[0]['totalnews'];
}

/**
 * Tìm tags cho bài viết dựa vào thư viện tags
 *
 * @param mixed $content
 * @return array
 */
function nv_get_mod_tags($content)
{
    global $db, $module_data;

    $content = preg_replace('/<[^>]*>/', ' ', $content);
    $content = strip_tags($content);
    $content = nv_unhtmlspecialchars($content);
    $content = strip_punctuation($content);
    $content = trim($content);
    $content = nv_strtolower($content);
    $ts = explode(' ', $content);
    $ts = array_map('trim', $ts);
    $ts = array_filter($ts);
    $ts = array_unique($ts);
    $ts = array_map(function ($t) {
        return preg_replace('/([\W])/u', '\\\\$1', $t);
    }, $ts);

    /**
     * Lấy các tag có chứa ít nhất một từ của bài viết.
     * Chia các từ của bài viết thành nhiều chunks, mỗi chunk dài tối đa 2000 ký tự.
     * keywords trong bảng tags có thể là cụm nhiều từ, còn $ts chỉ là một từ đơn.
     */
    $patterns = [];
    $batch = [];
    $length = 0;
    foreach ($ts as $t) {
        if (!empty($batch) and $length + strlen($t) > 2000) {
            $patterns[] = implode('|', $batch);
            $batch = [];
            $length = 0;
        }
        $batch[] = $t;
        $length += strlen($t) + 1;
    }
    if (!empty($batch)) {
        $patterns[] = implode('|', $batch);
    }

    if (empty($patterns)) {
        return [];
    }

    /**
     * Với những tag tìm được, phân tách từ khóa của nó bởi dấu phảy ra,
     * và xác nhận bằng cách kiểm tra từ khóa đó xuất hiện trong nội dung bài viết.
     */
    $tags = [];
    $stmt = $db->prepare('SELECT keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags WHERE keywords REGEXP :p');
    foreach ($patterns as $pattern) {
        $stmt->bindValue(':p', $pattern, PDO::PARAM_STR);
        $stmt->execute();

        while ($_row_tag = $stmt->fetch()) {
            foreach (explode(',', $_row_tag['keywords']) as $keyword) {
                $keyword = trim($keyword);
                if ($keyword === '' or isset($tags[$keyword])) {
                    continue;
                }
                $pos = strpos($content, $keyword);
                if ($pos !== false) {
                    $tags[$keyword] = $pos;
                }
            }
        }
        $stmt->closeCursor();
    }

    // Sắp xếp theo thứ tự xuất hiện trong nội dung bài viết, từ đầu đến cuối
    asort($tags, SORT_NUMERIC);

    return array_map('strval', array_keys($tags));
}

/**
 * setTagAlias()
 *
 * @param mixed $keywords
 * @param int   $tid
 * @param int   $dbexist
 * @return string|null
 * @throws PDOException
 */
function setTagAlias($keywords, $tid = 0, &$dbexist = 0)
{
    global $db, $module_data, $module_config, $module_name;

    $alias = ($module_config[$module_name]['tags_alias']) ? get_mod_alias($keywords) : change_alias_tags($keywords);
    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags WHERE alias= :alias AND tid!= :tid');
    $stmt->bindValue(':alias', $alias, PDO::PARAM_STR);
    $stmt->bindValue(':tid', $tid, PDO::PARAM_INT);
    $stmt->execute();
    $dbexist = (bool) $stmt->fetchColumn();

    return $alias;
}

/**
 * setTagKeywords()
 *
 * @param mixed $keywords
 * @param bool  $isArr
 * @return array|string
 */
function setTagKeywords($keywords, $isArr = false)
{
    $keywords = nv_strtolower($keywords);
    $keywords = explode(',', $keywords);
    $keywords = array_map('trim', $keywords);
    $keywords = array_filter($keywords);
    $keywords = array_unique($keywords);
    sort($keywords);

    if ($isArr) {
        return $keywords;
    }

    return implode(',', $keywords);
}

/**
 * Lấy nút sửa bài viết
 *
 * @param array $info cần có ít nhất id, và listcatid
 * @return string
 */
function nv_link_edit_page(array $info)
{
    global $nv_Lang, $module_name;

    if (!nv_check_edit_page($info)) {
        return '';
    }
    $link = '<a class="btn btn-primary btn-xs btn_edit" href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $info['id'] . '"><i class="fa fa-edit fa-fw"></i> ' . $nv_Lang->getGlobal('edit') . '</a>';
    return $link;
}

/**
 * Lấy nút xóa bài viết
 *
 * @param array $info cần có ít nhất id, và listcatid
 * @param int $detail
 * @return string
 */
function nv_link_delete_page(array $info, int $detail = 0)
{
    global $nv_Lang, $admin_info, $module_name;

    if (!nv_check_delete_page($info)) {
        return '';
    }

    $link = '<a class="btn btn-danger btn-xs" href="#" data-toggle="nv_del_content" data-id="' . $info['id'] . '" data-checkss="' . csrf_create($admin_info['admin_id'] . '_' . $module_name . '_' . $info['id']) . '" data-adminurl="' . NV_BASE_ADMINURL . '" data-detail="' . $detail . '"><em class="fa fa-trash-o margin-right"></em> ' . $nv_Lang->getGlobal('delete') . '</a>';
    return $link;
}

/**
 * @param int $article_id
 * @param int $history_id
 * @param int $history_time
 * @return string
 */
function get_article_restore_csrf_key(int $article_id, int $history_id, int $history_time): string
{
    global $admin_info, $module_name;
    return $admin_info['admin_id'] . '_' . $module_name . '_' . $article_id . '_' . $history_id . '_' . $history_time;
}
