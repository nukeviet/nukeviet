<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_NEWS')) {
    exit('Stop!!!');
}

$cache_file = $contents = '';
$schemas = [];
$viewcat = $global_array_cat[$catid]['viewcat'];
$set_view_page = ($page > 1 and (substr($viewcat, 0, 13) == 'viewcat_main_' or $viewcat == 'viewcat_two_column')) ? true : false;
$page_url = $base_url = $global_array_cat[$catid]['link'];

if (!defined('NV_IS_MODADMIN') and $page < 5) {
    if ($set_view_page) {
        $cache_file = $module_info['template'] . '_' . $op . '_' . $catid . '_page_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
    } else {
        $cache_file = $module_info['template'] . '_' . $op . '_' . $catid . '_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
    }
    if (($cache = $nv_Cache->getItem($module_name, $cache_file, ttl: 3600)) != false) {
        $cache = json_decode($cache, true);
        $contents = $cache['html'];
        $schemas = $cache['schemas'];
    }
    unset($cache);
}

if ($page > 1) {
    $page_url .= '/page-' . $page;
}

$canonicalUrl = getCanonicalUrl($page_url);

$page_title = (!empty($global_array_cat[$catid]['titlesite'])) ? $global_array_cat[$catid]['titlesite'] : $global_array_cat[$catid]['title'];
$key_words = $global_array_cat[$catid]['keywords'];
$description = $global_array_cat[$catid]['description'];
$global_array_cat[$catid]['description'] = $global_array_cat[$catid]['descriptionhtml'];
if (!empty($global_array_cat[$catid]['image'])) {
    $meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $global_array_cat[$catid]['image'];
    $meta_property['og:image:alt'] = $global_array_cat[$catid]['title'];
}

// Layout tùy chỉnh
if (!empty($global_array_cat[$catid]['layout_func'])) {
    $module_info['layout_funcs'][$op_file] = $global_array_cat[$catid]['layout_func'];
}

if (empty($contents)) {
    $array_catpage = [];
    $array_cat_other = [];
    $show_no_image = $module_config[$module_name]['show_no_image'];

    if ($viewcat == 'viewcat_page_new' or $viewcat == 'viewcat_page_old' or $set_view_page) {
        $order_by = ($viewcat == 'viewcat_page_new') ? $order_articles_by . ' DESC, addtime DESC' : $order_articles_by . ' ASC, addtime ASC';

        $db_slave->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_' . $catid)
            ->where('status=1');

        $num_items = $db_slave->query($db_slave->sql())
            ->fetchColumn();

        // Không cho tùy ý đánh số page + xác định trang trước, trang sau
        betweenURLs($page, ceil($num_items / $per_page), $base_url, '/page-', $prevPage, $nextPage);

        $db_slave->select('id, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, weight, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating');

        $featured = 0;
        if ($global_array_cat[$catid]['featured'] != 0) {
            $db_slave->where('status=1 AND id=' . $global_array_cat[$catid]['featured']);
            $result = $db_slave->query($db_slave->sql());
            if ($item = $result->fetch()) {
                $item['imghome'] = $item['imgmobile'] = '';
                get_homeimgfile($item);

                $item['newday'] = $global_array_cat[$catid]['newday'];
                $item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                $array_catpage[] = $item;
                $featured = $item['id'];
            }
        }

        $db_slave->where('status=1 AND id != ' . $featured)
            ->order($order_by)
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);
        $result = $db_slave->query($db_slave->sql());
        $weight_publtime = 0;
        while ($item = $result->fetch()) {
            $item['imghome'] = $item['imgmobile'] = '';
            get_homeimgfile($item);

            $item['newday'] = $global_array_cat[$catid]['newday'];
            $item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $array_catpage[] = $item;
            $weight_publtime = ($order_articles) ? $item['weight'] : $item['publtime'];
        }

        if ($st_links > 0) {
            $db_slave->sqlreset()
                ->select('id, listcatid, addtime, edittime, publtime, title, alias, external_link, hitstotal')
                ->from(NV_PREFIXLANG . '_' . $module_data . '_' . $catid)
                ->order($order_by)
                ->limit($st_links);
            if ($viewcat == 'viewcat_page_new') {
                $db_slave->where('status=1 AND ' . $order_articles_by . ' < ' . $weight_publtime);
            } else {
                $db_slave->where('status=1 AND ' . $order_articles_by . ' > ' . $weight_publtime);
            }
            $result = $db_slave->query($db_slave->sql());
            while ($item = $result->fetch()) {
                $item['newday'] = $global_array_cat[$catid]['newday'];
                $item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                $array_cat_other[] = $item;
            }
        }
        $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
        $contents = viewcat_page_new($array_catpage, $array_cat_other, $generate_page);
    } elseif ($viewcat == 'viewcat_main_left' or $viewcat == 'viewcat_main_right' or $viewcat == 'viewcat_main_bottom') {
        $array_subcatpage = [];

        $db_slave->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_' . $catid)
            ->where('status=1');

        $num_items = $db_slave->query($db_slave->sql())
            ->fetchColumn();

        // Không cho tùy ý đánh số page + xác định trang trước, trang sau
        betweenURLs($page, ceil($num_items / $per_page), $base_url, '/page-', $prevPage, $nextPage);

        $db_slave->select('id, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating');

        $featured = 0;
        if ($global_array_cat[$catid]['featured'] != 0) {
            $db_slave->where('status=1 AND id=' . $global_array_cat[$catid]['featured']);
            $result = $db_slave->query($db_slave->sql());
            if ($item = $result->fetch()) {
                $item['imghome'] = $item['imgmobile'] = '';
                get_homeimgfile($item);

                $item['newday'] = $global_array_cat[$catid]['newday'];
                $item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                $array_catpage[] = $item;
                $featured = $item['id'];
            }
        }

        $db_slave->order($order_articles_by . ' DESC')
            ->where('status=1 AND id != ' . $featured)
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $result = $db_slave->query($db_slave->sql());
        while ($item = $result->fetch()) {
            $item['imghome'] = $item['imgmobile'] = '';
            get_homeimgfile($item);

            $item['newday'] = $global_array_cat[$catid]['newday'];
            $item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $array_catpage[] = $item;
        }
        unset($sql, $result);

        $array_cat_other = [];

        if ($global_array_cat[$catid]['subcatid'] != '') {
            $key = 0;
            $array_catid = explode(',', $global_array_cat[$catid]['subcatid']);

            foreach ($array_catid as $catid_i) {
                $array_cat_other[$key] = $global_array_cat[$catid_i];
                $db_slave->sqlreset()
                    ->select('id, catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
                    ->from(NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i);

                $featured = 0;
                if ($global_array_cat[$catid_i]['featured'] != 0) {
                    $db_slave->where('status=1 and id=' . $global_array_cat[$catid_i]['featured']);
                    $result = $db_slave->query($db_slave->sql());
                    if ($item = $result->fetch()) {
                        $item['imghome'] = $item['imgmobile'] = '';
                        get_homeimgfile($item);

                        $item['newday'] = $global_array_cat[$catid_i]['newday'];
                        $item['link'] = $global_array_cat[$catid_i]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                        $array_cat_other[$key]['content'][] = $item;
                        $featured = $item['id'];
                    }
                }

                if ($featured) {
                    $db_slave->where('status=1 AND id!=' . $featured)->limit($global_array_cat[$catid_i]['numlinks'] - 1);
                } else {
                    $db_slave->where('status=1')->limit($global_array_cat[$catid_i]['numlinks']);
                }
                $db_slave->order($order_articles_by . ' DESC');
                $result = $db_slave->query($db_slave->sql());
                while ($item = $result->fetch()) {
                    $item['imghome'] = $item['imgmobile'] = '';
                    get_homeimgfile($item);

                    $item['newday'] = $global_array_cat[$catid_i]['newday'];
                    $item['link'] = $global_array_cat[$catid_i]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                    $array_cat_other[$key]['content'][] = $item;
                }

                unset($sql, $result);
                ++$key;
            }

            unset($array_catid);
        }
        $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
        $contents = viewsubcat_main($viewcat, $array_cat_other, $array_catpage, $generate_page);
    } elseif ($viewcat == 'viewcat_two_column') {
        // Cac bai viet phan dau
        $db_slave->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_' . $catid)
            ->where('status=1');

        $num_items = $db_slave->query($db_slave->sql())
            ->fetchColumn();

        // Không cho tùy ý đánh số page + xác định trang trước, trang sau
        betweenURLs($page, ceil($num_items / $per_page), $base_url, '/page-', $prevPage, $nextPage);

        $db_slave->sqlreset()
            ->select('id, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_' . $catid)
            ->where('status=1');
        $featured = 0;
        if ($global_array_cat[$catid]['featured'] != 0) {
            $db_slave->where('id=' . $global_array_cat[$catid]['featured'] . ' and status= 1');
            $result = $db_slave->query($db_slave->sql());
            while ($item = $result->fetch()) {
                $item['imghome'] = $item['imgmobile'] = '';
                get_homeimgfile($item);

                $item['newday'] = $global_array_cat[$catid]['newday'];
                $item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                $array_catpage[] = $item;
                $featured = $item['id'];
            }
        }
        if ($featured) {
            $db_slave->where('status= 1 AND id!=' . $featured)->limit($array_cat_i['numlinks'] - 1);
        } else {
            $db_slave->where('status= 1')->limit($array_cat_i['numlinks']);
        }

        $db_slave->order($order_articles_by . ' DESC')->offset(($page - 1) * $per_page);
        $result = $db_slave->query($db_slave->sql());
        while ($item = $result->fetch()) {
            $item['imghome'] = $item['imgmobile'] = '';
            get_homeimgfile($item);

            $item['newday'] = $global_array_cat[$catid]['newday'];
            $item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $array_catpage[] = $item;
        }
        unset($sql, $result);
        // Het cac bai viet phan dau

        // cac bai viet cua cac chu de con
        $key = 0;
        $array_catid = explode(',', $global_array_cat[$catid]['subcatid']);

        foreach ($array_catid as $catid_i) {
            $array_cat_other[$key] = $global_array_cat[$catid_i];
            $db_slave->sqlreset()
                ->select('id, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
                ->from(NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i)
                ->where('status=1');

            $featured = 0;
            if ($global_array_cat[$catid_i]['featured'] != 0) {
                $db_slave->where('id=' . $global_array_cat[$catid_i]['featured'] . ' and status= 1');
                $result = $db_slave->query($db_slave->sql());
                while ($item = $result->fetch()) {
                    $item['imghome'] = $item['imgmobile'] = '';
                    get_homeimgfile($item);

                    $item['newday'] = $global_array_cat[$catid_i]['newday'];
                    $item['link'] = $global_array_cat[$catid_i]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                    $array_cat_other[$key]['content'][] = $item;
                    $featured = $item['id'];
                }
            }

            if ($featured) {
                $db_slave->where('status= 1 AND inhome=1 AND id!=' . $featured)
                    ->limit($array_cat_i['numlinks'] - 1)
                    ->order($order_articles_by . ' DESC');
            } else {
                $db_slave->where('status= 1 AND inhome=1')
                    ->limit($array_cat_i['numlinks'])
                    ->order($order_articles_by . ' DESC');
            }

            $result = $db_slave->query($db_slave->sql());
            while ($item = $result->fetch()) {
                $item['imghome'] = $item['imgmobile'] = '';
                get_homeimgfile($item);

                $item['newday'] = $global_array_cat[$catid_i]['newday'];
                $item['link'] = $global_array_cat[$catid_i]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                $array_cat_other[$key]['content'][] = $item;
            }

            ++$key;
        }

        unset($sql, $result);
        //Het cac bai viet cua cac chu de con
        $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
        $contents = viewcat_two_column($array_catpage, $generate_page, $array_cat_other);
    } elseif ($viewcat == 'viewcat_grid_new' or $viewcat == 'viewcat_grid_old') {
        $order_by = ($viewcat == 'viewcat_grid_new') ? $order_articles_by . ' DESC, addtime DESC' : $order_articles_by . ' ASC, addtime ASC';

        $db_slave->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_' . $catid)
            ->where('status=1');

        $num_items = $db_slave->query($db_slave->sql())
            ->fetchColumn();

        // Không cho tùy ý đánh số page + xác định trang trước, trang sau
        betweenURLs($page, ceil($num_items / $per_page), $base_url, '/page-', $prevPage, $nextPage);

        $db_slave->select('id, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
            ->order($order_by)
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $result = $db_slave->query($db_slave->sql());
        while ($item = $result->fetch()) {
            $item['imghome'] = $item['imgmobile'] = '';
            get_homeimgfile($item);

            $item['newday'] = $global_array_cat[$catid]['newday'];
            $item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $array_catpage[] = $item;
        }

        $viewcat = 'viewcat_grid_new';
        $featured = $global_array_cat[$catid]['featured'];
        $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
        $contents = call_user_func($viewcat, $array_catpage, $catid, $generate_page);
    } elseif ($viewcat == 'viewcat_list_new' or $viewcat == 'viewcat_list_old') {
        // Xem theo tieu de
        $order_by = ($viewcat == 'viewcat_list_new') ? $order_articles_by . ' DESC, addtime DESC' : $order_articles_by . ' ASC, addtime ASC';

        $db_slave->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_' . $catid)
            ->where('status=1');

        $num_items = $db_slave->query($db_slave->sql())
            ->fetchColumn();

        // Không cho tùy ý đánh số page + xác định trang trước, trang sau
        betweenURLs($page, ceil($num_items / $per_page), $base_url, '/page-', $prevPage, $nextPage);

        $featured = 0;
        if ($global_array_cat[$catid]['featured'] != 0) {
            $db_slave->select('id, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')->where('id=' . $global_array_cat[$catid]['featured']);
            $result = $db_slave->query($db_slave->sql());
            while ($item = $result->fetch()) {
                $item['imghome'] = $item['imgmobile'] = '';
                get_homeimgfile($item);

                $item['newday'] = $global_array_cat[$catid]['newday'];
                $item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                $array_catpage[] = $item;
                $featured = $item['id'];
            }
        }
        if ($featured) {
            $db_slave->where('status= 1 AND inhome=1 AND id!=' . $featured);
        } else {
            $db_slave->where('status= 1 AND inhome=1');
        }
        $db_slave->select('id, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
            ->order($order_by)
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);
        $results = $db_slave->query($db_slave->sql());
        while ($item = $results->fetch()) {
            $item['imghome'] = $item['imgmobile'] = '';
            get_homeimgfile($item);

            $item['newday'] = $global_array_cat[$catid]['newday'];
            $item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $array_catpage[] = $item;
        }

        $viewcat = 'viewcat_list_new';
        $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
        $contents = call_user_func($viewcat, $array_catpage, $catid, ($page - 1) * $per_page, $generate_page);
    }

    // Dữ liệu có cấu trúc CollectionPage
    if (!empty($array_catpage)) {
        $schemas_items = get_schema_colpage_items($array_catpage);
        $schemas = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $global_array_cat[$catid]['title'],
            'headline' => $global_array_cat[$catid]['titlesite'] ?: $global_array_cat[$catid]['title'],
            'description' => $global_array_cat[$catid]['description'] ?: $global_array_cat[$catid]['title'],
            'url' => $canonicalUrl,
            'inLanguage' => NV_LANG_DATA,
            'mainEntity' => [
                '@type' => 'ItemList',
                'itemListElement' => $schemas_items,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $global_config['site_name'],
                'url' => NV_MY_DOMAIN
            ],
        ];
        if (!empty($global_config['site_logo'])) {
            $schemas['publisher']['logo'] = [
                '@type' => 'ImageObject',
                'url' => NV_MY_DOMAIN . NV_BASE_SITEURL . $global_config['site_logo']
            ];
        }
    }

    if (!defined('NV_IS_MODADMIN') and $contents != '' and $cache_file != '') {
        $nv_Cache->setItem($module_name, $cache_file, json_encode([
            'html' => $contents,
            'schemas' => $schemas,
        ]), ttl: 3600);
    }
}

if (!empty($schemas)) {
    $nv_schemas[] = $schemas;
}

if ($page > 1) {
    $page_title .= NV_TITLEBAR_DEFIS . $nv_Lang->getGlobal('page') . ' ' . $page;
    $description .= ' ' . $page;
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
