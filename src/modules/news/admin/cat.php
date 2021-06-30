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

$page_title = $lang_module['categories'];

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$currentpath = NV_UPLOADS_DIR . '/' . $module_upload;
$error = $admins = '';
$savecat = 0;
list($catid, $parentid, $title, $titlesite, $alias, $description, $descriptionhtml, $keywords, $groups_view, $image, $viewdescription, $featured, $ad_block_cat) = [
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
    ''
];

$groups_list = nv_groups_list();

$parentid = $nv_Request->get_int('parentid', 'get,post', 0);

$catid = $nv_Request->get_int('catid', 'get', 0);

if ($catid > 0 and isset($global_array_cat[$catid])) {
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
    $ad_block_cat = $global_array_cat[$catid]['ad_block_cat'];

    if (!defined('NV_IS_ADMIN_MODULE')) {
        if (!(isset($array_cat_admin[$admin_id][$parentid]) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1)) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $parentid);
        }
    }

    $caption = $lang_module['edit_cat'];
    $array_in_cat = GetCatidInParent($catid);
} else {
    $caption = $lang_module['add_cat'];
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

    $_groups_post = $nv_Request->get_array('groups_view', 'post', []);
    $groups_view = !empty($_groups_post) ? implode(',', nv_groups_post(array_intersect($_groups_post, array_keys($groups_list)))) : '';

    $_ad_block_cat = $nv_Request->get_array('ad_block_cat', 'post', []);
    $ad_block_cat = !empty($_ad_block_cat) ? implode(',', $_ad_block_cat) : '';

    $image = $nv_Request->get_string('image', 'post', '');
    if (nv_is_file($image, NV_UPLOADS_DIR . '/' . $module_upload)) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
        $image = substr($image, $lu);
    } else {
        $image = '';
    }

    if (!defined('NV_IS_ADMIN_MODULE')) {
        if (!(isset($array_cat_admin[$admin_id][$parentid]) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1)) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $parentid);
        }
    }

    if ($catid == 0 and $title != '') {
        $weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid=' . $parentid)->fetchColumn();
        $weight = (int) $weight + 1;
        $viewcat = 'viewcat_page_new';
        $subcatid = '';

        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . "_cat (parentid, title, titlesite, alias, description, descriptionhtml, image, viewdescription, weight, sort, lev, viewcat, numsubcat, subcatid, numlinks, newday, featured, ad_block_cat, keywords, admins, add_time, edit_time, groups_view, status) VALUES
			(:parentid, :title, :titlesite, :alias, :description, :descriptionhtml, '', '" . $viewdescription . "', :weight, '0', '0', :viewcat, '0', :subcatid, '3', '2', :featured, :ad_block_cat, :keywords, :admins, " . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', :groups_view, 1)';

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

        $newcatid = $db->insert_id($sql, 'catid', $data_insert);
        if ($newcatid > 0) {
            $check_ad_block_cat = $_ad_block_cat;
            require_once NV_ROOTDIR . '/includes/action_' . $db->dbtype . '.php';

            nv_copy_structure_table(NV_PREFIXLANG . '_' . $module_data . '_' . $newcatid, NV_PREFIXLANG . '_' . $module_data . '_rows');
            nv_fix_cat_order();

            if (!defined('NV_IS_ADMIN_MODULE')) {
                $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_admins (userid, catid, admin, add_content, pub_content, edit_content, del_content) VALUES (' . $admin_id . ', ' . $newcatid . ', 1, 1, 1, 1, 1)');
            }

            if (in_array('1', $check_ad_block_cat, true)) {
                $ini_edit = nv_add_block_topcat_news($newcatid);
            }
            if (in_array('2', $check_ad_block_cat, true)) {
                $ini_edit2 = nv_add_block_botcat_news($newcatid);
            }

            $nv_Cache->delMod($module_name);
            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['add_cat'], $title, $admin_info['userid']);
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $parentid);
        } else {
            $error = $lang_module['errorsave'];
        }
    } elseif ($catid > 0 and $title != '') {
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET parentid= :parentid, title= :title, titlesite=:titlesite, alias = :alias, description = :description, descriptionhtml = :descriptionhtml, image= :image, viewdescription= :viewdescription,featured=:featured, ad_block_cat=:ad_block_cat, keywords= :keywords, groups_view= :groups_view, edit_time=' . NV_CURRENTTIME . ' WHERE catid =' . $catid);
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
        $stmt->execute();

        if ($stmt->rowCount()) {
            $check_ad_block_cat = explode(',', $ad_block_cat);

            $_r_b = nv_remove_block_botcat_news($catid);
            $_r_t = nv_remove_block_topcat_news($catid);
            if ($parentid != $parentid_old) {
                $weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid=' . $parentid)->fetchColumn();
                $weight = (int) $weight + 1;

                $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=' . $weight . ' WHERE catid=' . (int) $catid;
                $db->query($sql);

                nv_fix_cat_order();
                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['edit_cat'], $title, $admin_info['userid']);
            }

            if (in_array('1', $check_ad_block_cat, true)) {
                $ini_edit = nv_add_block_topcat_news($catid);
            }
            if (in_array('2', $check_ad_block_cat, true)) {
                $ini_edit2 = nv_add_block_botcat_news($catid);
            }

            $nv_Cache->delMod($module_name);
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $parentid);
        } else {
            $error = $lang_module['errorsave'];
        }
    } else {
        $error = $lang_module['error_name'];
    }
}

$groups_view = array_map('intval', explode(',', $groups_view));

if (!empty($ad_block_cat)) {
    $ad_block_cat = explode(',', $ad_block_cat);
} else {
    $ad_block_cat = [];
}
$array_cat_list = [];
if (defined('NV_IS_ADMIN_MODULE')) {
    $array_cat_list[0] = $lang_module['cat_sub_sl'];
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

if (!empty($array_cat_list)) {
    $cat_listsub = [];
    foreach ($array_cat_list as $catid_i => $title_i) {
        if (!in_array((int) $catid_i, array_map('intval', $array_in_cat), true)) {
            $cat_listsub[] = [
                'value' => $catid_i,
                'selected' => ($catid_i == $parentid) ? ' selected="selected"' : '',
                'title' => $title_i
            ];
        }
    }

    $groups_views = [];
    foreach ($groups_list as $group_id => $grtl) {
        $groups_views[] = [
            'value' => $group_id,
            'checked' => in_array((int) $group_id, $groups_view, true) ? ' checked="checked"' : '',
            'title' => $grtl
        ];
    }

    $ad_block_cats = [];
    $ad_block_list = [
        1 => $lang_module['ad_block_top'],
        2 => $lang_module['ad_block_bot']
    ];
    foreach ($ad_block_list as $ad_block_id => $ad_block_tl) {
        $ad_block_cats[] = [
            'value' => $ad_block_id,
            'checked' => in_array((int) $ad_block_id, array_map('intval', $ad_block_cat), true) ? ' checked="checked"' : '',
            'title' => $ad_block_tl
        ];
    }
}

$lang_global['title_suggest_max'] = sprintf($lang_global['length_suggest_max'], 65);
$lang_global['description_suggest_max'] = sprintf($lang_global['length_suggest_max'], 160);

if (!empty($image) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $image)) {
    $image = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $image;
    $currentpath = dirname($image);
}

$xtpl = new XTemplate('cat.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$xtpl->assign('caption', $caption);
$xtpl->assign('catid', $catid);
$xtpl->assign('title', $title);
$xtpl->assign('titlesite', $titlesite);
$xtpl->assign('alias', $alias);
$xtpl->assign('parentid', $parentid);
$xtpl->assign('keywords', $keywords);
$xtpl->assign('description', nv_htmlspecialchars(nv_br2nl($description)));
$xtpl->assign('CAT_LIST', nv_show_cat_list($parentid));
$xtpl->assign('UPLOAD_CURRENT', $currentpath);
$xtpl->assign('UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('image', $image);

for ($i = 0; $i <= 2; ++$i) {
    $data = [
        'value' => $i,
        'selected' => ($viewdescription == $i) ? ' checked="checked"' : '',
        'title' => $lang_module['viewdescription_' . $i]
    ];
    $xtpl->assign('VIEWDESCRIPTION', $data);
    $xtpl->parse('main.content.viewdescription');
}
if ($catid > 0) {
    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE status=1 ORDER BY ' . $order_articles_by . ' DESC LIMIT 100';
    $result = $db->query($sql);
    $array_id = [];
    $array_id[] = $featured;
    while ($row = $result->fetch()) {
        $array_id[] = $row['id'];
    }

    $sql1 = 'SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id IN (' . implode(',', $array_id) . ') ORDER BY ' . $order_articles_by . ' DESC';
    $result = $db->query($sql1);

    while ($row = $result->fetch()) {
        $row = [
            'id' => $row['id'],
            'selected' => ($featured == $row['id']) ? ' selected="selected"' : '',
            'title' => $row['title']
        ];
        $xtpl->assign('FEATURED_NEWS', $row);
        $xtpl->parse('main.content.featured.featured_loop');
    }
    $xtpl->parse('main.content.featured');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

if (!empty($array_cat_list)) {
    if (empty($alias)) {
        $xtpl->parse('main.content.getalias');
    }

    foreach ($cat_listsub as $data) {
        $xtpl->assign('cat_listsub', $data);
        $xtpl->parse('main.content.cat_listsub');
    }

    foreach ($groups_views as $data) {
        $xtpl->assign('groups_views', $data);
        $xtpl->parse('main.content.groups_views');
    }

    foreach ($ad_block_cats as $ads) {
        $xtpl->assign('ad_block_cats', $ads);
        $xtpl->parse('main.content.ad_block_cats');
    }

    $descriptionhtml = nv_htmlspecialchars(nv_editor_br2nl($descriptionhtml));
    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
        $_uploads_dir = NV_UPLOADS_DIR . '/' . $module_upload;
        $descriptionhtml = nv_aleditor('descriptionhtml', '100%', '200px', $descriptionhtml, 'Basic', $_uploads_dir, $_uploads_dir);
    } else {
        $descriptionhtml = '<textarea style="width: 100%" name="descriptionhtml" id="descriptionhtml" cols="20" rows="15">' . $descriptionhtml . '</textarea>';
    }
    $xtpl->assign('DESCRIPTIONHTML', $descriptionhtml);

    $xtpl->parse('main.content');
}

$xtpl->parse('main');
$contents .= $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
