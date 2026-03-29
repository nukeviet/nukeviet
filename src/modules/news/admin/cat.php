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

$page_title = $nv_Lang->getModule('categories');

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$currentpath = NV_UPLOADS_DIR . '/' . $module_upload;
$admins = '';
$savecat = 0;
[$catid, $parentid, $title, $titlesite, $alias, $description, $descriptionhtml, $keywords, $groups_view, $image, $viewdescription, $featured, $ad_block_cat, $layout_func] = [
    0,
    0,
    '',
    '',
    '',
    '',
    '',
    '',
    '6',
    '',
    0,
    0,
    '',
    ''
];
$ad_block_cat_old = '';
$groups_list = nv_groups_list();
$parentid = $nv_Request->get_int('parentid', 'get,post', 0);
$catid = $nv_Request->get_int('catid', 'get,post', 0);

if ($catid > 0) {
    if (!isset($global_array_cat[$catid])) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }
    $parentid = $global_array_cat[$catid]['parentid'];
    $title = $global_array_cat[$catid]['title'];
    $titlesite = $global_array_cat[$catid]['titlesite'];
    $alias = $global_array_cat[$catid]['alias'];
    $description = $global_array_cat[$catid]['description'];
    $descriptionhtml = $global_array_cat[$catid]['descriptionhtml'];
    $viewdescription = $global_array_cat[$catid]['viewdescription'];
    $image = $global_array_cat[$catid]['image'];
    $keywords = $global_array_cat[$catid]['keywords'];
    $groups_view = $global_array_cat[$catid]['groups_view'];
    $featured = $global_array_cat[$catid]['featured'];
    $ad_block_cat = $ad_block_cat_old = $global_array_cat[$catid]['ad_block_cat'];
    $layout_func = $global_array_cat[$catid]['layout_func'];

    if (!defined('NV_IS_ADMIN_MODULE')) {
        if (!(isset($array_cat_admin[$admin_id][$parentid]) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1)) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $parentid);
        }
    }

    $caption = $nv_Lang->getModule('edit_cat');
    $array_in_cat = GetCatidInParent($catid);
} else {
    $caption = $nv_Lang->getModule('add_cat');
    $array_in_cat = [];
}

$savecat = $nv_Request->get_int('savecat', 'post', 0);

if (!empty($savecat)) {
    $catid = $nv_Request->get_int('catid', 'post', 0);
    $parentid_old = $nv_Request->get_int('parentid_old', 'post', 0);
    $parentid = $nv_Request->get_int('parentid', 'post', 0);
    $title = $nv_Request->get_title('title', 'post', '', 1);
    $titlesite = $nv_Request->get_title('titlesite', 'post', '', 1);
    $keywords = $nv_Request->get_title('keywords', 'post', '', 1);
    $description = $nv_Request->get_string('description', 'post', '');
    $description = nv_nl2br(nv_htmlspecialchars(strip_tags($description)), '<br />');
    $descriptionhtml = $nv_Request->get_editor('descriptionhtml', '', NV_ALLOWED_HTML_TAGS);

    $viewdescription = $nv_Request->get_int('viewdescription', 'post', 0);
    $featured = $nv_Request->get_int('featured', 'post', 0);

    // Xử lý liên kết tĩnh
    $_alias = $nv_Request->get_title('alias', 'post', '');
    $_alias = ($_alias == '') ? get_mod_alias($title, 'cat', $catid) : get_mod_alias($_alias, 'cat', $catid);

    if (empty($_alias) or !preg_match("/^([a-zA-Z0-9\_\-]+)$/", $_alias)) {
        if (empty($alias)) {
            if ($catid) {
                $alias = 'cat-' . $catid;
            } else {
                $_m_catid = $db->query('SELECT MAX(catid) AS cid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat')->fetchColumn();

                if (empty($_m_catid)) {
                    $alias = 'cat-1';
                } else {
                    $alias = 'cat-' . ((int) $_m_catid + 1);
                }
            }
        }
    } else {
        $alias = $_alias;
    }

    $_groups_post = $nv_Request->get_typed_array('groups_view', 'post', 'int', []);
    $groups_view = !empty($_groups_post) ? implode(',', nv_groups_post(array_intersect($_groups_post, array_keys($groups_list)))) : '';

    $_ad_block_cat = $nv_Request->get_typed_array('ad_block_cat', 'post', 'int', []);
    $_ad_block_cat = array_filter(array_unique($_ad_block_cat));
    $ad_block_cat = !empty($_ad_block_cat) ? implode(',', $_ad_block_cat) : '';

    $layout_func = $nv_Request->get_title('layout_func', 'post', '');
    if (!in_array('layout.' . $layout_func . '.tpl', $layout_array)) {
        $layout_func = '';
    }

    $image = $nv_Request->get_string('image', 'post', '');
    if (nv_is_file($image, NV_UPLOADS_DIR . '/' . $module_upload)) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
        $image = substr($image, $lu);
    } else {
        $image = '';
    }

    if (!defined('NV_IS_ADMIN_MODULE')) {
        if (!(isset($array_cat_admin[$admin_id][$parentid]) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('errorsave')
            ]);
        }
    }

    if (empty($title)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_name'),
            'input' => 'title',
            'input_parent' => '#idtitle_parent'
        ]);
    }

    if ($catid == 0) {
        $weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid=' . $parentid)->fetchColumn();
        $weight = (int) $weight + 1;
        $viewcat = 'viewcat_page_new';
        $subcatid = '';

        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . "_cat (
            parentid, title, titlesite, alias, description, descriptionhtml,
            image, viewdescription, weight, sort, lev, viewcat, numsubcat,
            subcatid, numlinks, newday, featured, ad_block_cat, layout_func, keywords,
            admins, add_time, edit_time, groups_view, status
        ) VALUES (
            :parentid, :title, :titlesite, :alias, :description, :descriptionhtml,
            '', '" . $viewdescription . "', :weight, '0', '0', :viewcat, '0',
            :subcatid, '3', '2', :featured, :ad_block_cat, :layout_func, :keywords, :admins,
            " . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', :groups_view, 1
        )';

        $data_insert = [];
        $data_insert['parentid'] = $parentid;
        $data_insert['title'] = $title;
        $data_insert['titlesite'] = $titlesite;
        $data_insert['alias'] = $alias;
        $data_insert['description'] = $description;
        $data_insert['descriptionhtml'] = $descriptionhtml;
        $data_insert['weight'] = $weight;
        $data_insert['viewcat'] = $viewcat;
        $data_insert['subcatid'] = $subcatid;
        $data_insert['keywords'] = $keywords;
        $data_insert['admins'] = $admins;
        $data_insert['groups_view'] = $groups_view;
        $data_insert['featured'] = $featured;
        $data_insert['ad_block_cat'] = $ad_block_cat;
        $data_insert['layout_func'] = $layout_func;

        $newcatid = $db->insert_id($sql, 'catid', $data_insert);
        if ($newcatid > 0) {
            require_once NV_ROOTDIR . '/includes/action_' . $db->dbtype . '.php';

            nv_copy_structure_table(NV_PREFIXLANG . '_' . $module_data . '_' . $newcatid, NV_PREFIXLANG . '_' . $module_data . '_rows');
            nv_fix_cat_order();

            if (!defined('NV_IS_ADMIN_MODULE')) {
                $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_admins (userid, catid, admin, add_content, pub_content, edit_content, del_content) VALUES (' . $admin_id . ', ' . $newcatid . ', 1, 1, 1, 1, 1)');
            }

            // Đăng kí các khối block tùy chỉnh
            foreach ($_ad_block_cat as $ad_block_id) {
                nv_register_block(nv_get_blcat_tag($newcatid, $ad_block_id));
            }

            $nv_Cache->delMod($module_name);
            nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('add_cat'), $title, $admin_info['userid']);
            nv_jsonOutput([
                'status' => 'OK',
                'redirect' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $parentid
            ]);
        }
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('errorsave')
        ]);
    } else {
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET
            parentid= :parentid, title= :title, titlesite=:titlesite, alias = :alias,
            description = :description, descriptionhtml = :descriptionhtml,
            image= :image, viewdescription= :viewdescription,featured=:featured,
            ad_block_cat=:ad_block_cat, layout_func=:layout_func, keywords= :keywords, groups_view= :groups_view,
            edit_time=' . NV_CURRENTTIME . '
        WHERE catid =' . $catid);
        $stmt->bindParam(':parentid', $parentid, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':titlesite', $titlesite, PDO::PARAM_STR);
        $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':viewdescription', $viewdescription, PDO::PARAM_STR);
        $stmt->bindParam(':keywords', $keywords, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR, strlen($description));
        $stmt->bindParam(':descriptionhtml', $descriptionhtml, PDO::PARAM_STR, strlen($descriptionhtml));
        $stmt->bindParam(':groups_view', $groups_view, PDO::PARAM_STR);
        $stmt->bindParam(':featured', $featured, PDO::PARAM_INT);
        $stmt->bindParam(':ad_block_cat', $ad_block_cat, PDO::PARAM_STR);
        $stmt->bindParam(':layout_func', $layout_func, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount()) {
            if ($parentid != $parentid_old) {
                $weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid=' . $parentid)->fetchColumn();
                $weight = (int) $weight + 1;

                $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=' . $weight . ' WHERE catid=' . (int) $catid;
                $db->query($sql);

                nv_fix_cat_order();
            }

            // Kiểm tra đăng kí, hủy đăng kí các khối block tùy chỉnh
            $ad_block_cat_old = array_filter(array_unique(array_map('intval', explode(',', $ad_block_cat_old))));
            $diff_add = array_diff($_ad_block_cat, $ad_block_cat_old);
            $diff_del = array_diff($ad_block_cat_old, $_ad_block_cat);

            foreach ($diff_add as $ad_block_id) {
                nv_register_block(nv_get_blcat_tag($catid, $ad_block_id));
            }
            foreach ($diff_del as $ad_block_id) {
                nv_unregister_block(nv_get_blcat_tag($catid, $ad_block_id));
            }

            $nv_Cache->delMod($module_name);
            nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('edit_cat'), $title, $admin_info['userid']);
            nv_jsonOutput([
                'status' => 'OK',
                'redirect' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $parentid
            ]);
        }
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('errorsave')
        ]);
    }
}

$groups_view = array_map('intval', explode(',', $groups_view));
$ad_block_cat = array_filter(array_unique(array_map('intval', explode(',', $ad_block_cat))));

$array_cat_list = [];
if (defined('NV_IS_ADMIN_MODULE')) {
    $array_cat_list[0] = $nv_Lang->getModule('cat_sub_sl');
}
foreach ($global_array_cat as $catid_i => $array_value) {
    $lev_i = $array_value['lev'];
    if (defined('NV_IS_ADMIN_MODULE') or (isset($array_cat_admin[$admin_id][$catid_i]) and $array_cat_admin[$admin_id][$catid_i]['admin'] == 1)) {
        $xtitle_i = '';
        if ($lev_i > 0) {
            $xtitle_i .= '&nbsp;&nbsp;&nbsp;|';
            for ($i = 1; $i <= $lev_i; ++$i) {
                $xtitle_i .= '---';
            }
            $xtitle_i .= '>&nbsp;';
        }
        $xtitle_i .= $array_value['title'];
        $array_cat_list[$catid_i] = $xtitle_i;
    }
}

// Build danh sách chuyên mục cha
$cat_listsub = [];
foreach ($array_cat_list as $catid_i => $title_i) {
    if (!in_array((int) $catid_i, array_map('intval', $array_in_cat), true)) {
        $cat_listsub[] = [
            'value' => $catid_i,
            'title' => $title_i
        ];
    }
}

// Build danh sách nhóm người dùng
$groups_views = [];
foreach ($groups_list as $group_id => $grtl) {
    $groups_views[] = [
        'value' => $group_id,
        'is_checked' => in_array((int) $group_id, $groups_view, true),
        'title' => $grtl
    ];
}

// Build danh sách khối quảng cáo
$ad_block_list = [
    1 => $nv_Lang->getModule('ad_block_top'),
    2 => $nv_Lang->getModule('ad_block_bot')
];
$ad_block_cats = [];
foreach ($ad_block_list as $ad_block_id => $ad_block_tl) {
    $ad_block_cats[] = [
        'value' => $ad_block_id,
        'is_checked' => in_array((int) $ad_block_id, $ad_block_cat, true),
        'title' => $ad_block_tl
    ];
}

// Build tùy chọn hiển thị mô tả
$viewdescription_options = [];
for ($i = 0; $i <= 2; ++$i) {
    $viewdescription_options[] = [
        'value' => $i,
        'title' => $nv_Lang->getModule('viewdescription_' . $i)
    ];
}

$nv_Lang->setGlobal('title_suggest_max', $nv_Lang->getGlobal('length_suggest_max', 65));
$nv_Lang->setGlobal('description_suggest_max', $nv_Lang->getGlobal('length_suggest_max', 160));

if (!empty($image) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $image)) {
    $image = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $image;
    $currentpath = dirname($image);
}

// Build danh sách bài viết nổi bật (chỉ khi sửa chuyên mục)
$featured_news = [];
if ($catid > 0) {
    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE status=1 ORDER BY ' . $order_articles_by . ' DESC LIMIT 100';
    $result = $db->query($sql);
    $array_id = [$featured];
    while ($row = $result->fetch()) {
        $array_id[] = $row['id'];
    }

    $sql1 = 'SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id IN (' . implode(',', $array_id) . ') ORDER BY ' . $order_articles_by . ' DESC';
    $result = $db->query($sql1);
    while ($row = $result->fetch()) {
        $featured_news[] = [
            'id' => $row['id'],
            'title' => $row['title']
        ];
    }
}

// Build danh sách bố cục tùy chỉnh
$layout_opts = [];
foreach ($layout_array as $value) {
    $layout_opts[] = preg_replace($global_config['check_op_layout'], '\\1', $value);
}

$descriptionhtml = nv_htmlspecialchars(nv_editor_br2nl($descriptionhtml));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $_uploads_dir = NV_UPLOADS_DIR . '/' . $module_upload;
    $descriptionhtml = nv_aleditor('descriptionhtml', '100%', '200px', $descriptionhtml, 'Basic', $_uploads_dir, $_uploads_dir);
} else {
    $descriptionhtml = '<textarea style="width: 100%" name="descriptionhtml" id="descriptionhtml" cols="20" rows="15">' . $descriptionhtml . '</textarea>';
}

// Build danh sách chuyên mục hiển thị theo quyền hạn
$array_cat_check_content = [];
foreach ($global_array_cat as $catid_i => $array_value) {
    if (defined('NV_IS_ADMIN_MODULE')) {
        $array_cat_check_content[] = $catid_i;
    } elseif (isset($array_cat_admin[$admin_id][$catid_i])) {
        if (
            $array_cat_admin[$admin_id][$catid_i]['admin'] == 1
            || $array_cat_admin[$admin_id][$catid_i]['add_content'] == 1
            || $array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1
            || $array_cat_admin[$admin_id][$catid_i]['edit_content'] == 1
        ) {
            $array_cat_check_content[] = $catid_i;
        }
    }
}

// Build breadcrumb điều hướng chuyên mục cha
$cat_title = [];
if ($parentid > 0) {
    $parentid_nav = $parentid;
    $array_cat_title = [];
    $stt = 0;
    while ($parentid_nav > 0) {
        $array_cat_title[] = [
            'active' => ($stt++ == 0),
            'link'   => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat&amp;parentid=' . $parentid_nav,
            'title'  => $global_array_cat[$parentid_nav]['title']
        ];
        $parentid_nav = $global_array_cat[$parentid_nav]['parentid'];
    }
    $array_cat_title[] = [
        'active' => false,
        'link'   => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat',
        'title'  => $nv_Lang->getModule('cat_parent')
    ];
    krsort($array_cat_title, SORT_NUMERIC);
    $cat_title = array_values($array_cat_title);
}

// Truy vấn danh sách chuyên mục con trực tiếp
$sql_cat_list = 'SELECT catid, parentid, title, alias, weight, viewcat, numsubcat, numlinks, newday, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid = ' . $parentid . ' ORDER BY weight ASC';
$rowall_cat = $db->query($sql_cat_list)->fetchAll(3);
$num_cats = count($rowall_cat);
$array_status = [
    $nv_Lang->getModule('cat_status_0'),
    $nv_Lang->getModule('cat_status_1'),
    $nv_Lang->getModule('cat_status_2')
];
$is_large_system = (nv_get_mod_countrows() > NV_MIN_MEDIUM_SYSTEM_ROWS);

$status_list = [];
foreach ($array_status as $_key => $_val) {
    if (!$is_large_system || $_key != 0) {
        $status_list[] = ['key' => $_key, 'value' => $_val];
    }
}

$cat_rows = [];
$cat_weight_idx = 1;
foreach ($rowall_cat as $row_r) {
    [$catid_r, $parentid_r, $title_r, $alias_r, $weight_r, $viewcat_r, $numsubcat_r, $numlinks_r, $newday_r, $status_r] = $row_r;

    if (defined('NV_IS_ADMIN_MODULE')) {
        $check_show = 1;
    } else {
        $array_cat_r = GetCatidInParent($catid_r);
        $check_show = array_intersect($array_cat_r, $array_cat_check_content);
    }

    if (empty($check_show)) {
        continue;
    }

    $array_viewcat_r = ($numsubcat_r > 0) ? $array_viewcat_full : $array_viewcat_nosub;
    if (!array_key_exists($viewcat_r, $array_viewcat_r)) {
        $viewcat_r = 'viewcat_page_new';
        $stmt_vc = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET viewcat= :viewcat WHERE catid=' . (int) $catid_r);
        $stmt_vc->bindParam(':viewcat', $viewcat_r, PDO::PARAM_STR);
        $stmt_vc->execute();
    }

    $admin_funcs = [];
    $weight_disabled = $func_cat_disabled = true;

    if (!empty($module_config[$module_name]['instant_articles_active'])) {
        $admin_funcs['instant_articles'] = urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=instant-rss/' . $alias_r, NV_MY_DOMAIN);
    }
    if (defined('NV_IS_ADMIN_MODULE') || (isset($array_cat_admin[$admin_id][$catid_r]) && $array_cat_admin[$admin_id][$catid_r]['add_content'] == 1)) {
        $func_cat_disabled = false;
        $admin_funcs['add'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;catid=' . $catid_r . '&amp;parentid=' . $parentid_r;
    }
    if (defined('NV_IS_ADMIN_MODULE') || ($parentid_r > 0 && isset($array_cat_admin[$admin_id][$parentid_r]) && $array_cat_admin[$admin_id][$parentid_r]['admin'] == 1)) {
        $func_cat_disabled = false;
        $admin_funcs['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat&amp;catid=' . $catid_r . '&amp;parentid=' . $parentid_r;
    }
    if (defined('NV_IS_ADMIN_MODULE') || ($parentid_r > 0 && isset($array_cat_admin[$admin_id][$parentid_r]) && $array_cat_admin[$admin_id][$parentid_r]['admin'] == 1)) {
        $weight_disabled = false;
        $admin_funcs['delete'] = 1;
    }

    if ($status_r > $global_code_defined['cat_locked_status']) {
        $status_text_r = $nv_Lang->getModule('cat_locked_byparent');
        $status_can_change_r = false;
    } elseif ($func_cat_disabled || ($is_large_system && $status_r == 0)) {
        $status_text_r = $array_status[$status_r];
        $status_can_change_r = false;
    } else {
        $status_text_r = $array_status[$status_r];
        $status_can_change_r = true;
    }

    $cat_rows[] = [
        'catid'               => $catid_r,
        'title'               => $title_r,
        'link'                => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat&amp;parentid=' . $catid_r,
        'numsubcat'           => $numsubcat_r,
        'weight'              => $cat_weight_idx,
        'weight_can_change'   => !$weight_disabled,
        'numlinks'            => $numlinks_r,
        'numlinks_can_change' => !$func_cat_disabled,
        'newday'              => $newday_r,
        'newday_can_change'   => !$func_cat_disabled,
        'viewcat_text'        => $array_viewcat_r[$viewcat_r],
        'viewcat_val'         => $viewcat_r,
        'viewcat_mode'        => ($numsubcat_r > 0) ? 'full' : 'nosub',
        'viewcat_can_change'  => !$func_cat_disabled,
        'status_text'         => $status_text_r,
        'status_val'          => $status_r,
        'status_can_change'   => $status_can_change_r,
        'adminfuncs'          => $admin_funcs,
        'checkss'             => csrf_create($admin_info['admin_id'] . '_' . $module_name . '_cat' . $catid_r)
    ];
    ++$cat_weight_idx;
}


$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('cat.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('MODULE_UPLOAD', $module_upload);
$tpl->assign('IS_EDIT', $catid > 0);
$tpl->assign('CAPTION', $caption);
$tpl->assign('CATID', $catid);
$tpl->assign('PARENTID', $parentid);
$tpl->assign('TITLE', $title);
$tpl->assign('TITLESITE', $titlesite);
$tpl->assign('ALIAS', $alias);
$tpl->assign('KEYWORDS', $keywords);
$tpl->assign('DESCRIPTION', nv_htmlspecialchars(nv_br2nl($description)));
$tpl->assign('CAT_TITLE', $cat_title);
$tpl->assign('ROWS', $cat_rows);
$tpl->assign('MAX_WEIGHT', $num_cats);
$tpl->assign('VIEWCAT_FULL', $array_viewcat_full);
$tpl->assign('VIEWCAT_NOSUB', $array_viewcat_nosub);
$tpl->assign('STATUS_LIST', $status_list);
$tpl->assign('UPLOAD_CURRENT', $currentpath);
$tpl->assign('IMAGE', $image);
$tpl->assign('CAT_LISTSUB', $cat_listsub);
$tpl->assign('GROUPS_VIEWS', $groups_views);
$tpl->assign('AD_BLOCK_CATS', $ad_block_cats);
$tpl->assign('AD_BLOCK_NOTE', $catid > 0 && !empty($ad_block_cat_old));
$tpl->assign('VIEWDESCRIPTION', (int) $viewdescription);
$tpl->assign('VIEWDESCRIPTION_OPTIONS', $viewdescription_options);
$tpl->assign('FEATURED', (int) $featured);
$tpl->assign('FEATURED_NEWS', $featured_news);
$tpl->assign('LAYOUT_FUNC', $layout_func);
$tpl->assign('LAYOUT_OPTS', $layout_opts);
$tpl->assign('DESCRIPTIONHTML', $descriptionhtml);
$tpl->assign('HAS_CAT_LIST', !empty($array_cat_list));

$contents = $tpl->fetch('cat.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
