<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

 if (! defined('NV_IS_MOD_NEWS')) {
     die('Stop!!!');
 }

$show_no_image = $module_config[$module_name]['show_no_image'];
if (isset($array_op[1])) {
    $alias = trim($array_op[1]);
    $page = (isset($array_op[2]) and substr($array_op[2], 0, 5) == 'page-') ? intval(substr($array_op[2], 5)) : 1;

    $stmt = $db_slave->prepare('SELECT bid, title, alias, image, description, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat WHERE alias= :alias');
    $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
    $stmt->execute();
    list($bid, $page_title, $alias, $image_group, $description, $key_words) = $stmt->fetch(3);
    if ($bid > 0) {
        $base_url_rewrite = $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['groups'] . '/' . $alias;

        if ($page > 1) {
            $page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
            $base_url_rewrite .= '/page-' . $page;
        }
        $base_url_rewrite = nv_url_rewrite($base_url_rewrite, true);
        if ($_SERVER['REQUEST_URI'] != $base_url_rewrite and NV_MAIN_DOMAIN . $_SERVER['REQUEST_URI'] != $base_url_rewrite) {
            nv_redirect_location($base_url_rewrite);
        }

        $array_mod_title[] = array(
            'catid' => 0,
            'title' => $page_title,
            'link' => $base_url
        );

        $item_array = array();
        $end_weight = 0;

        $db_slave->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_rows t1')
            ->join('INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_block t2 ON t1.id = t2.id')
            ->where('t2.bid= ' . $bid . ' AND t1.status= 1');

        $num_items = $db_slave->query($db_slave->sql())->fetchColumn();

        $db_slave->select('t1.id, t1.catid, t1.admin_id, t1.author, t1.sourceid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hometext, t1.homeimgfile, t1.homeimgalt, t1.homeimgthumb, t1.allowed_rating, t1.external_link, t1.hitstotal, t1.hitscm, t1.total_rating, t1.click_rating, t2.weight')
            ->order('t2.weight ASC')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $result = $db_slave->query($db_slave->sql());
        while ($item = $result->fetch()) {
            if ($item['homeimgthumb'] == 1) {
                //image thumb
                $item['src'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
            } elseif ($item['homeimgthumb'] == 2) {
                //image file
                $item['src'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
            } elseif ($item['homeimgthumb'] == 3) {
                //image url
                $item['src'] = $item['homeimgfile'];
            } elseif (! empty($show_no_image)) {
                //no image
                $item['src'] = NV_BASE_SITEURL . $show_no_image;
            } else {
                $item['src'] = '';
            }

            $item['alt'] = ! empty($item['homeimgalt']) ? $item['homeimgalt'] : $item['title'];
            $item['width'] = $module_config[$module_name]['homewidth'];

            $end_weight = $item['weight'];

            $item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $item_array[] = $item;
        }
        $result->closeCursor();
        unset($query, $row);

        $item_array_other = array();
        if ($st_links > 0) {
            $db_slave->sqlreset()
                ->select('t1.id, t1.catid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hitstotal, t1.external_link')
                ->from(NV_PREFIXLANG . '_' . $module_data . '_rows t1')
                ->join('INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_block t2 ON t1.id = t2.id')
                ->where('t2.bid= ' . $bid . ' AND t2.weight > ' . $end_weight)
                ->order('t2.weight ASC')
                ->limit($st_links);
            $result = $db_slave->query($db_slave->sql());
            while ($item = $result->fetch()) {
                $item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                $item_array_other[] = $item;
            }
            unset($query, $row);
        }

        $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
        if (! empty($image_group)) {
            $image_group = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $image_group;
        }
        $contents = topic_theme($item_array, $item_array_other, $generate_page, $page_title, $description, $image_group);
    }
} else {
    $array_cat = array();
    $key = 0;

    $query_cat = $db_slave->query('SELECT bid, numbers, title, alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat ORDER BY weight ASC');

    while (list($bid, $numberlink, $btitle, $balias) = $query_cat->fetch(3)) {
        $array_cat[$key] = array(
            'catid' => $bid,
            'alias' => '',
            'subcatid' => '',
            'title' => $btitle,
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['groups'] . '/' . $balias
        );

        $db_slave->sqlreset()
            ->select('t1.id, t1.catid, t1.admin_id, t1.author, t1.sourceid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hometext, t1.homeimgfile, t1.homeimgalt, t1.homeimgthumb, t1.allowed_rating, t1.external_link, t1.hitstotal, t1.hitscm, t1.total_rating, t1.click_rating')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_rows t1')
            ->join('INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_block t2 ON t1.id = t2.id')
            ->where('t2.bid= ' . $bid . ' AND t1.status= 1')
            ->order('t2.weight ASC')
            ->limit($numberlink);
        $result = $db_slave->query($db_slave->sql());
        while ($item = $result->fetch()) {
            if ($item['homeimgthumb'] == 1) {
                //image thumb
                $item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
            } elseif ($item['homeimgthumb'] == 2) {
                //image file
                $item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
            } elseif ($item['homeimgthumb'] == 3) {
                //image url
                $item['imghome'] = $item['homeimgfile'];
            } elseif (! empty($show_no_image)) {
                //no image
                $item['imghome'] = NV_BASE_SITEURL . $show_no_image;
            } else {
                $item['imghome'] = '';
            }

            $item['alt'] = ! empty($item['homeimgalt']) ? $item['homeimgalt'] : $item['title'];
            $item['width'] = $module_config[$module_name]['homewidth'];

            $item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'];
            $array_cat[$key]['content'][] = $item;
        }
        ++$key;
    }

    $viewcat = $module_config[$module_name]['indexfile'];

    if ($viewcat != 'viewcat_main_left' and $viewcat != 'viewcat_main_bottom') {
        $viewcat = 'viewcat_main_right';
    }

    $contents = viewsubcat_main($viewcat, $array_cat);

    $page_title = $module_info['funcs']['groups']['func_site_title'];
    $key_words = $module_info['keywords'];
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
