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

$alias = $nv_Request->get_title('alias', 'get');
$array_op = explode('/', $alias);
$alias = $array_op[0];

if (isset($array_op[1])) {
    if (sizeof($array_op) == 2 and preg_match('/^page\-([0-9]+)$/', $array_op[1], $m)) {
        $page = (int) ($m[1]);
    } else {
        $alias = '';
    }
}

$stmt = $db_slave->prepare('SELECT tid, title, image, description, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags WHERE alias= :alias');
$stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
$stmt->execute();
list($tid, $page_title, $image_tag, $description, $key_words) = $stmt->fetch(3);

if ($tid > 0) {
    if (empty($page_title)) {
        $page_title = nv_ucfirst(trim(str_replace('-', ' ', $alias)));
    }

    $page_url = $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=tag/' . $alias;
    if ($page > 1) {
        $page_url .= '/page-' . $page;
        $page_title .= NV_TITLEBAR_DEFIS . $lang_global['page'] . ' ' . $page;
    }

    $canonicalUrl = getCanonicalUrl($page_url, true);

    $array_mod_title[] = [
        'catid' => 0,
        'title' => $page_title,
        'link' => $base_url
    ];

    $item_array = [];
    $end_publtime = 0;
    $show_no_image = $module_config[$module_name]['show_no_image'];

    $db_slave->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
        ->where('status=1 AND id IN (SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE tid=' . $tid . ')');

    $num_items = $db_slave->query($db_slave->sql())
        ->fetchColumn();
    // Không cho tùy ý đánh số page + xác định trang trước, trang sau
    betweenURLs($page, ceil($num_items / $per_page), $base_url, '/page-', $prevPage, $nextPage);

    $db_slave->select('id, catid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
        ->order($order_articles_by . ' DESC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);

    $result = $db_slave->query($db_slave->sql());
    while ($item = $result->fetch()) {
        if ($item['homeimgthumb'] == 1) {
            // image thumb
            $item['src'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
        } elseif ($item['homeimgthumb'] == 2) {
            // image file
            $item['src'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
        } elseif ($item['homeimgthumb'] == 3) {
            // image url
            $item['src'] = $item['homeimgfile'];
        } elseif (!empty($show_no_image)) {
            // no image
            $item['src'] = NV_BASE_SITEURL . $show_no_image;
        } else {
            $item['imghome'] = '';
        }
        $item['alt'] = !empty($item['homeimgalt']) ? $item['homeimgalt'] : $item['title'];
        $item['width'] = $module_config[$module_name]['homewidth'];

        $end_publtime = $item['publtime'];

        $item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
        $item_array[] = $item;
    }
    $result->closeCursor();
    unset($query, $row);

    $item_array_other = [];
    if ($st_links > 0) {
        $db_slave->sqlreset()
            ->select('id, catid, addtime, edittime, publtime, title, alias, hitstotal, external_link')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
            ->where('status=1 AND id IN (SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE tid=' . $tid . ') and publtime < ' . $end_publtime)
            ->order($order_articles_by . ' DESC')
            ->limit($st_links);
        $result = $db_slave->query($db_slave->sql());
        while ($item = $result->fetch()) {
            $item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $item_array_other[] = $item;
        }
        unset($query, $row);
    }

    $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);

    if (!empty($image_tag)) {
        $image_tag = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $image_tag;
    }
    $contents = topic_theme($item_array, $item_array_other, $generate_page, $page_title, $description, $image_tag);

    if ($page > 1) {
        $page_title .= NV_TITLEBAR_DEFIS . $lang_global['page'] . ' ' . $page;
    }
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$redirect = '<meta http-equiv="Refresh" content="3;URL=' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true) . '" />';
nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect, 404);
