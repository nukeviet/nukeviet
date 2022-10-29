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

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
$page_url = $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

$contents = '';
$cache_file = '';
$isMob = ((!empty($global_config['mobile_theme']) and $module_info['template'] == $global_config['mobile_theme']) or $client_info['is_mobile']);
$viewcat = $isMob ? $module_config[$module_name]['mobile_indexfile'] : $module_config[$module_name]['indexfile'];
$no_generate = ['viewcat_none', 'viewcat_main_left', 'viewcat_main_right', 'viewcat_main_bottom', 'viewcat_two_column'];

if ($page > 1) {
    $page_url .= '&amp;' . NV_OP_VARIABLE . '=page-' . $page;

    /*
     * @link https://github.com/nukeviet/nukeviet/issues/2990
     * Một số kiểu hiển thị không được đánh page
     */
    if (in_array($viewcat, $no_generate, true)) {
        nv_redirect_location($base_url);
    }
}

$canonicalUrl = getCanonicalUrl($page_url, true, true);

if (!defined('NV_IS_MODADMIN') and $page < 5) {
    $cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '-' . $op . '-' . $viewcat . '-' . $page . '-' . NV_CACHE_PREFIX . '.cache';
    if (($cache = $nv_Cache->getItem($module_name, $cache_file, 3600)) != false) {
        $contents = $cache;
    }
}

if (empty($contents)) {
    $show_no_image = $module_config[$module_name]['show_no_image'];
    $array_catpage = [];
    $array_cat_other = [];

    if ($viewcat == 'viewcat_none') {
        $contents = '';
    } elseif ($viewcat == 'viewcat_page_new' or $viewcat == 'viewcat_page_old') {
        $order_by = ($viewcat == 'viewcat_page_new') ? $order_articles_by . ' DESC, addtime DESC' : $order_articles_by . ' ASC, addtime ASC';
        $db_slave->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
            ->where('status= 1 AND inhome=1');

        $num_items = $db_slave->query($db_slave->sql())
            ->fetchColumn();

        // Không cho tùy ý đánh số page + xác định trang trước, trang sau
        betweenURLs($page, ceil($num_items / $per_page), $base_url, '&amp;' . NV_OP_VARIABLE . '=page-', $prevPage, $nextPage);

        $db_slave->select('id, catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, weight, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
            ->order($order_by)
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $weight_publtime = 0;
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
            } elseif (!empty($show_no_image)) {
                //no image
                $item['imghome'] = NV_BASE_SITEURL . $show_no_image;
            } else {
                $item['imghome'] = '';
            }

            $item['newday'] = $global_array_cat[$item['catid']]['newday'];
            $item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $array_catpage[] = $item;
            $weight_publtime = ($order_articles) ? $item['weight'] : $item['publtime'];
        }

        if ($st_links > 0) {
            $db_slave->sqlreset()
                ->select('id, catid, addtime, edittime, publtime, title, alias, external_link, hitstotal')
                ->from(NV_PREFIXLANG . '_' . $module_data . '_rows');

            if ($viewcat == 'viewcat_page_new') {
                $db_slave->where('status= 1 AND inhome=1 AND ' . $order_articles_by . ' < ' . $weight_publtime);
            } else {
                $db_slave->where('status= 1 AND inhome=1 AND ' . $order_articles_by . ' > ' . $weight_publtime);
            }
            $db_slave->order($order_by)->limit($st_links);

            $result = $db_slave->query($db_slave->sql());
            while ($item = $result->fetch()) {
                $item['newday'] = $global_array_cat[$item['catid']]['newday'];
                $item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                $array_cat_other[] = $item;
            }
        }

        $viewcat = 'viewcat_page_new';
        $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
        $contents = call_user_func($viewcat, $array_catpage, $array_cat_other, $generate_page);
    } elseif ($viewcat == 'viewcat_main_left' or $viewcat == 'viewcat_main_right' or $viewcat == 'viewcat_main_bottom') {
        $array_cat = [];

        $key = 0;
        $db_slave->sqlreset()
            ->select('id, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
            ->order($order_articles_by . ' DESC');

        foreach ($global_array_cat as $_catid => $array_cat_i) {
            if ($array_cat_i['parentid'] == 0 and $array_cat_i['status'] == 1) {
                $array_cat[$key] = $array_cat_i;
                $featured = 0;
                if ($array_cat_i['featured'] != 0) {
                    $result = $db_slave->query($db_slave->from(NV_PREFIXLANG . '_' . $module_data . '_' . $_catid)
                        ->where('id=' . $array_cat_i['featured'] . ' and status= 1 AND inhome=1')
                        ->sql());
                    if ($item = $result->fetch()) {
                        if ($item['homeimgthumb'] == 1) {
                            $item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
                        } elseif ($item['homeimgthumb'] == 2) {
                            $item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
                        } elseif ($item['homeimgthumb'] == 3) {
                            $item['imghome'] = $item['homeimgfile'];
                        } elseif (!empty($show_no_image)) {
                            $item['imghome'] = NV_BASE_SITEURL . $show_no_image;
                        } else {
                            $item['imghome'] = '';
                        }

                        $item['newday'] = $array_cat_i['newday'];
                        $item['link'] = $array_cat_i['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                        $array_cat[$key]['content'][] = $item;
                        $featured = $item['id'];
                    }
                }

                if ($featured) {
                    $db_slave->from(NV_PREFIXLANG . '_' . $module_data . '_' . $_catid)
                        ->where('status= 1 AND inhome=1 AND id!=' . $featured)
                        ->limit($array_cat_i['numlinks'] - 1);
                } else {
                    $db_slave->from(NV_PREFIXLANG . '_' . $module_data . '_' . $_catid)
                        ->where('status= 1 AND inhome=1')
                        ->limit($array_cat_i['numlinks']);
                }

                $result = $db_slave->query($db_slave->sql());
                while ($item = $result->fetch()) {
                    if ($item['homeimgthumb'] == 1) {
                        $item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
                    } elseif ($item['homeimgthumb'] == 2) {
                        $item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
                    } elseif ($item['homeimgthumb'] == 3) {
                        $item['imghome'] = $item['homeimgfile'];
                    } elseif (!empty($show_no_image)) {
                        $item['imghome'] = NV_BASE_SITEURL . $show_no_image;
                    } else {
                        $item['imghome'] = '';
                    }

                    $item['newday'] = $array_cat_i['newday'];
                    $item['link'] = $array_cat_i['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                    $array_cat[$key]['content'][] = $item;
                }

                ++$key;
            }
        }

        $contents = viewsubcat_main($viewcat, $array_cat);
    } elseif ($viewcat == 'viewcat_two_column') {
        // Cac bai viet phan dau
        $array_content = $array_catpage = [];

        // cac bai viet cua cac chu de con
        $key = 0;

        $db_slave->sqlreset()
            ->select('id, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
            ->where('status= 1 AND inhome=1')
            ->order($order_articles_by . ' DESC');
        foreach ($global_array_cat as $_catid => $array_cat_i) {
            if ($array_cat_i['parentid'] == 0 and $array_cat_i['status'] == 1) {
                $array_catpage[$key] = $array_cat_i;
                $featured = 0;
                if ($array_cat_i['featured'] != 0) {
                    $result = $db_slave->query($db_slave->from(NV_PREFIXLANG . '_' . $module_data . '_' . $_catid)
                        ->where('id=' . $array_cat_i['featured'] . ' and status= 1 AND inhome=1')
                        ->limit($array_cat_i['numlinks'])
                        ->sql());
                    while ($item = $result->fetch()) {
                        if ($item['homeimgthumb'] == 1) {
                            $item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
                        } elseif ($item['homeimgthumb'] == 2) {
                            $item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
                        } elseif ($item['homeimgthumb'] == 3) {
                            $item['imghome'] = $item['homeimgfile'];
                        } elseif (!empty($show_no_image)) {
                            $item['imghome'] = NV_BASE_SITEURL . $show_no_image;
                        } else {
                            $item['imghome'] = '';
                        }

                        $item['newday'] = $array_cat_i['newday'];
                        $item['link'] = $array_cat_i['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                        $array_catpage[$key]['content'][] = $item;
                        $featured = $item['id'];
                    }
                }
                if ($featured) {
                    $db_slave->from(NV_PREFIXLANG . '_' . $module_data . '_' . $_catid)
                        ->where('status= 1 AND inhome=1 AND id!=' . $featured)
                        ->limit($array_cat_i['numlinks'] - 1);
                } else {
                    $db_slave->from(NV_PREFIXLANG . '_' . $module_data . '_' . $_catid)
                        ->where('status= 1 AND inhome=1')
                        ->limit($array_cat_i['numlinks']);
                }
                $result = $db_slave->query($db_slave->sql());

                while ($item = $result->fetch()) {
                    if ($item['homeimgthumb'] == 1) {
                        $item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
                    } elseif ($item['homeimgthumb'] == 2) {
                        $item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
                    } elseif ($item['homeimgthumb'] == 3) {
                        $item['imghome'] = $item['homeimgfile'];
                    } elseif (!empty($show_no_image)) {
                        $item['imghome'] = NV_BASE_SITEURL . $show_no_image;
                    } else {
                        $item['imghome'] = '';
                    }

                    $item['newday'] = $array_cat_i['newday'];
                    $item['link'] = $array_cat_i['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                    $array_catpage[$key]['content'][] = $item;
                }
            }

            ++$key;
        }
        unset($sql, $result);
        //Het cac bai viet cua cac chu de con
        $contents = viewcat_two_column($array_content, $array_catpage);
    } elseif ($viewcat == 'viewcat_grid_new' or $viewcat == 'viewcat_grid_old') {
        $order_by = ($viewcat == 'viewcat_grid_new') ? $order_articles_by . '  DESC' : $order_articles_by . '  ASC';
        $db_slave->sqlreset()
            ->select('COUNT(*) ')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
            ->where('status= 1 AND inhome=1');

        $num_items = $db_slave->query($db_slave->sql())
            ->fetchColumn();

        // Không cho tùy ý đánh số page + xác định trang trước, trang sau
        betweenURLs($page, ceil($num_items / $per_page), $base_url, '&amp;' . NV_OP_VARIABLE . '=page-', $prevPage, $nextPage);

        $db_slave->select('id, catid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
            ->order($order_by)
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $result = $db_slave->query($db_slave->sql());
        while ($item = $result->fetch()) {
            if ($item['homeimgthumb'] == 1) {
                $item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
            } elseif ($item['homeimgthumb'] == 2) {
                $item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
            } elseif ($item['homeimgthumb'] == 3) {
                $item['imghome'] = $item['homeimgfile'];
            } elseif (!empty($show_no_image)) {
                $item['imghome'] = NV_BASE_SITEURL . $show_no_image;
            } else {
                $item['imghome'] = '';
            }

            $item['newday'] = $global_array_cat[$item['catid']]['newday'];
            $item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $array_catpage[] = $item;
        }

        $viewcat = 'viewcat_grid_new';
        $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
        $contents = call_user_func($viewcat, $array_catpage, 0, $generate_page);
    } elseif ($viewcat == 'viewcat_list_new' or $viewcat == 'viewcat_list_old') {
        // Xem theo tieu de
        $order_by = ($viewcat == 'viewcat_list_new') ? $order_articles_by . ' DESC, addtime DESC' : $order_articles_by . ' ASC, addtime ASC';

        $db_slave->sqlreset()
            ->select('COUNT(*) ')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
            ->where('status= 1 AND inhome=1');

        $num_items = $db_slave->query($db_slave->sql())
            ->fetchColumn();

        // Không cho tùy ý đánh số page + xác định trang trước, trang sau
        betweenURLs($page, ceil($num_items / $per_page), $base_url, '&amp;' . NV_OP_VARIABLE . '=page-', $prevPage, $nextPage);

        $db_slave->select('id, catid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
            ->order($order_by)
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

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
            } elseif (!empty($show_no_image)) {
                //no image
                $item['imghome'] = NV_BASE_SITEURL . $show_no_image;
            } else {
                $item['imghome'] = '';
            }

            $item['newday'] = $global_array_cat[$item['catid']]['newday'];
            $item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $array_catpage[] = $item;
        }

        $viewcat = 'viewcat_list_new';
        $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
        $contents = call_user_func($viewcat, $array_catpage, 0, ($page - 1) * $per_page, $generate_page);
    }

    if (!defined('NV_IS_MODADMIN') and $contents != '' and $cache_file != '') {
        $nv_Cache->setItem($module_name, $cache_file, $contents);
    }
}

if ($page > 1) {
    $page_title .= NV_TITLEBAR_DEFIS . $lang_global['page'] . ' ' . $page;
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
