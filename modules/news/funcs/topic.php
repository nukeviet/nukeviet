<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if (!defined('NV_IS_MOD_NEWS')) {
    die('Stop!!!');
}

$show_no_image = $module_config[$module_name]['show_no_image'];

$array_mod_title[] = array(
    'catid' => 0,
    'title' => $module_info['funcs'][$op]['func_custom_name'],
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['topic']
);

$alias = isset($array_op[1]) ? trim($array_op[1]) : '';
$topic_array = array();

$topicid = 0;
if (!empty($alias)) {
    $page = (isset($array_op[2]) and substr($array_op[2], 0, 5) == 'page-') ? intval(substr($array_op[2], 5)) : 1;

    $sth = $db_slave->prepare('SELECT topicid, title, alias, image, description, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE alias= :alias');
    $sth->bindParam(':alias', $alias, PDO::PARAM_STR);
    $sth->execute();

    list ($topicid, $page_title, $alias, $topic_image, $description, $key_words) = $sth->fetch(3);

    if ($topicid > 0) {
        $base_url_rewrite = $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['topic'] . '/' . $alias;
        if ($page > 1) {
            $page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
            $base_url_rewrite .= '/page-' . $page;
        }
        $base_url_rewrite = nv_url_rewrite(str_replace('&amp;', '&', $base_url_rewrite), true);
        if ($_SERVER['REQUEST_URI'] != $base_url_rewrite and NV_MAIN_DOMAIN . $_SERVER['REQUEST_URI'] != $base_url_rewrite) {
            nv_redirect_location($base_url_rewrite);
        }

        $array_mod_title[] = array(
            'catid' => 0,
            'title' => $page_title,
            'link' => $base_url
        );

        $db_slave->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
            ->where('status=1 AND topicid = ' . $topicid);

        $num_items = $db_slave->query($db_slave->sql())
            ->fetchColumn();

        $db_slave->select('id, catid, topicid, admin_id, author, sourceid, addtime, edittime, weight, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
            ->order($order_articles_by . ' DESC')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $weight_publtime = 0;

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
            } elseif (!empty($show_no_image)) {
                //no image
                $item['src'] = NV_BASE_SITEURL . $show_no_image;
            } else {
                $item['imghome'] = '';
            }
            $item['alt'] = !empty($item['homeimgalt']) ? $item['homeimgalt'] : $item['title'];
            $item['width'] = $module_config[$module_name]['homewidth'];

            $weight_publtime = ($order_articles) ? $item['weight'] : $item['publtime'];

            $item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $topic_array[] = $item;
        }
        $result->closeCursor();
        unset($result, $row);

        $topic_other_array = array();
        if ($st_links > 0) {
            $db_slave->sqlreset()
                ->select('id, catid, addtime, edittime, publtime, title, alias, hitstotal, external_link')
                ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
                ->where('status=1 AND topicid = ' . $topicid . ' AND ' . $order_articles_by . ' < ' . $weight_publtime)
                ->order($order_articles_by.' DESC')
                ->limit($st_links);

            $result = $db_slave->query($db_slave->sql());
            while ($item = $result->fetch()) {
                $item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                $topic_other_array[] = $item;
            }
            unset($result, $row);
        }

        $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);

        if (!empty($topic_image)) {
            $topic_image = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/topics/' . $topic_image;
            $meta_property['og:image'] = NV_MY_DOMAIN . $topic_image;
        }

        $contents = topic_theme($topic_array, $topic_other_array, $generate_page, $page_title, $description, $topic_image);
    } else {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['topic']);
    }
} else {
    $page_title = $module_info['site_title'];
    $key_words = $module_info['keywords'];

    $result = $db_slave->query('SELECT topicid as id, title, alias, image, description as hometext, keywords, add_time as publtime FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics ORDER BY weight ASC');
    while ($item = $result->fetch()) {
        if (!empty($item['image']) and file_exists(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/topics/' . $item['image'])) {
            //image thumb
            $item['src'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/topics/' . $item['image'];
        } elseif (!empty($item['image'])) {
            //image file
            $item['src'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/topics/' . $item['image'];
        } elseif (!empty($show_no_image)) {
            //no image
            $item['src'] = NV_BASE_SITEURL . $show_no_image;
        } else {
            $item['src'] = '';
        }
        $item['alt'] = !empty($item['homeimgalt']) ? $item['homeimgalt'] : $item['title'];
        $item['width'] = $module_config[$module_name]['homewidth'];

        $item['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['topic'] . '/' . $item['alias'];
        $topic_array[] = $item;
    }
    $result->closeCursor();
    unset($result, $row);

    $topic_other_array = array();
    $contents = topic_theme($topic_array, $topic_other_array, '', $page_title, $description, '');
}
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';