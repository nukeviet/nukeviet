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

$cache_file = '';
$contents = '';
$viewcat = $global_array_cat[$catid]['viewcat'];

$base_url_rewrite = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'];
if ($page > 1) {
    $base_url_rewrite .= '/page-' . $page;
}
$base_url_rewrite = nv_url_rewrite($base_url_rewrite, true);
if ($_SERVER['REQUEST_URI'] != $base_url_rewrite and NV_MAIN_DOMAIN . $_SERVER['REQUEST_URI'] != $base_url_rewrite) {
    nv_redirect_location($base_url_rewrite);
}

$set_view_page = ($page > 1 and substr($viewcat, 0, 13) == 'viewcat_main_') ? true : false;

if (!defined('NV_IS_MODADMIN') and $page < 5) {
    if ($set_view_page) {
        $cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_' . $catid . '_page_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
    } else {
        $cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_' . $catid . '_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
    }
    if (($cache = $nv_Cache->getItem($module_name, $cache_file, 3600)) != false) {
        $contents = $cache;
    }
}

$page_title = (!empty($global_array_cat[$catid]['titlesite'])) ? $global_array_cat[$catid]['titlesite'] : $global_array_cat[$catid]['title'];
$key_words = $global_array_cat[$catid]['keywords'];
$description = $global_array_cat[$catid]['description'];
$global_array_cat[$catid]['description'] = $global_array_cat[$catid]['descriptionhtml'];
if (!empty($global_array_cat[$catid]['image'])) {
    $meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $global_array_cat[$catid]['image'];
}

if (empty($contents)) {
    $array_catpage = array();
    $array_cat_other = array();
    $base_url = $global_array_cat[$catid]['link'];
    $show_no_image = $module_config[$module_name]['show_no_image'];

    if ($viewcat == 'viewcat_page_new' or $viewcat == 'viewcat_page_old' or $set_view_page) {
        $order_by = ($viewcat == 'viewcat_page_new') ? $order_articles_by . ' DESC, addtime DESC' : $order_articles_by . ' ASC, addtime ASC';

        $db_slave->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_' . $catid)
            ->where('status=1');

        $num_items = $db_slave->query($db_slave->sql())
            ->fetchColumn();

        $db_slave->select('id, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, weight, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating');

        $featured = 0;
        if ($global_array_cat[$catid]['featured'] != 0) {
            $db_slave->where('status=1 AND id=' . $global_array_cat[$catid]['featured']);
            $result = $db_slave->query($db_slave->sql());
            if ($item = $result->fetch()) {
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
        $array_catcontent = array();
        $array_subcatpage = array();

        $db_slave->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_' . $catid)
            ->where('status=1');

        $num_items = $db_slave->query($db_slave->sql())
            ->fetchColumn();

        $db_slave->select('id, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating');

        $featured = 0;
        if ($global_array_cat[$catid]['featured'] != 0) {
            $db_slave->where('status=1 AND id=' . $global_array_cat[$catid]['featured']);
            $result = $db_slave->query($db_slave->sql());
            if ($item = $result->fetch()) {
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

                $item['newday'] = $global_array_cat[$catid]['newday'];
                $item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                $array_catcontent[] = $item;
                $featured = $item['id'];
            }
        }

        $db_slave->order($order_articles_by . ' DESC')
            ->where('status=1 AND id != ' . $featured)
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

            $item['newday'] = $global_array_cat[$catid]['newday'];
            $item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $array_catcontent[] = $item;
        }
        unset($sql, $result);

        $array_cat_other = array();

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
        $contents = viewcat_top($array_catcontent, $generate_page);
        $contents .= call_user_func('viewsubcat_main', $viewcat, $array_cat_other);
    } elseif ($viewcat == 'viewcat_two_column') {
        // Cac bai viet phan dau
        $array_catcontent = array();

        $db_slave->sqlreset()
            ->select('id, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_' . $catid)
            ->where('status=1');
        $featured = 0;
        if ($global_array_cat[$catid]['featured'] != 0) {
            $db_slave->where('id=' . $global_array_cat[$catid]['featured'] . ' and status= 1');
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

                $item['newday'] = $global_array_cat[$catid]['newday'];
                $item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                $array_catcontent[] = $item;
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

            $item['newday'] = $global_array_cat[$catid]['newday'];
            $item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $array_catcontent[] = $item;
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

                $item['newday'] = $global_array_cat[$catid_i]['newday'];
                $item['link'] = $global_array_cat[$catid_i]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                $array_cat_other[$key]['content'][] = $item;
            }

            ++$key;
        }

        unset($sql, $result);
        //Het cac bai viet cua cac chu de con
        $contents = call_user_func($viewcat, $array_catcontent, $array_cat_other);
    } elseif ($viewcat == 'viewcat_grid_new' or $viewcat == 'viewcat_grid_old') {
        $order_by = ($viewcat == 'viewcat_grid_new') ? $order_articles_by . ' DESC, addtime DESC' : $order_articles_by . ' ASC, addtime ASC';

        $db_slave->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_' . $catid)
            ->where('status=1');

        $num_items = $db_slave->query($db_slave->sql())
            ->fetchColumn();

        $db_slave->select('id, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
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
        $featured = 0;
        if ($global_array_cat[$catid]['featured'] != 0) {
            $db_slave->select('id, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')->where('id=' . $global_array_cat[$catid]['featured']);
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
            $item['newday'] = $global_array_cat[$catid]['newday'];
            $item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $array_catpage[] = $item;
        }

        $viewcat = 'viewcat_list_new';
        $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
        $contents = call_user_func($viewcat, $array_catpage, $catid, ($page - 1) * $per_page, $generate_page);
    }

    if (!defined('NV_IS_MODADMIN') and $contents != '' and $cache_file != '') {
        $nv_Cache->setItem($module_name, $cache_file, $contents);
    }
}

if ($page > 1) {
    $page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
    $description .= ' ' . $page;
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';