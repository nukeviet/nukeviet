<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

$ajax = $nv_Request->isset_request('ajax', 'get, post');
$listgroupid = $nv_Request->get_string('listgroupid', 'get, post', '');
$array_id_group = array( );

if ($ajax) {
    $page = $nv_Request->get_int('page', 'get, post', 1);
    $catid = $nv_Request->get_int('catid', 'get, post', 0);

    if (!empty($listgroupid)) {
        $array_id_group = explode(',', $listgroupid);
    }
}

if (empty($catid)) {
    Header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
    exit();
}

$compare_id = $nv_Request->get_string($module_data . '_compare_id', 'session', '');
$compare_id = unserialize($compare_id);

unset($array_op[0]);
$array_url_group = array( );
foreach ($array_op as $_inurl) {
    if (preg_match('/^page\-([0-9]+)$/', $_inurl, $m)) {
        $page = $m[1];
    } elseif (preg_match('/^([a-z0-9\-]+)\_([a-z0-9\-]+)$/i', $_inurl, $m)) {
        $result = $db->query('SELECT groupid FROM ' . $db_config['prefix'] . '_' . $module_data . '_group WHERE ' . NV_LANG_DATA . '_alias = ' . $db->quote($m[2]));
        if ($result->rowCount() > 0) {
            $array_id_group[] = $result->fetchColumn();
        }
        $array_url_group[$m[1]][] = $m[2];
    }
}

$base_url_rewrite = $global_array_shops_cat[$catid]['link'];
if ($page > 1) {
    $base_url_rewrite .= '/page-' . $page;
}
$base_url_rewrite = nv_url_rewrite($base_url_rewrite, true);

if ($_SERVER['REQUEST_URI'] != $base_url_rewrite and NV_MAIN_DOMAIN . $_SERVER['REQUEST_URI'] != $base_url_rewrite) {
    header('HTTP/1.1 301 Moved Permanently');
    Header('Location: ' . $base_url_rewrite);
    die();
}

if (!empty($global_array_shops_cat[$catid]['title_custom'])) {
    $page_title = $global_array_shops_cat[$catid]['title_custom'];
} else {
    $page_title = $global_array_shops_cat[$catid]['title'];
}

if (!empty($global_array_shops_cat[$catid]['tag_description'])) {
    $description = $global_array_shops_cat[$catid]['tag_description'];
} else {
    $description = $global_array_shops_cat[$catid]['description'];
}

$key_words = $global_array_shops_cat[$catid]['keywords'];

$contents = '';
$cache_file = '';
$nv_Request->get_int('sorts', 'session', 0);
$sorts = $nv_Request->get_int('sort', 'post', 0);
$sorts_old = $nv_Request->get_int('sorts', 'session', $pro_config['sortdefault']);
$sorts = $nv_Request->get_int('sorts', 'post', $sorts_old);

$nv_Request->get_string('viewtype', 'session', '');
$viewtype = $nv_Request->get_string('viewtype', 'post', '');
$viewtype_old = $nv_Request->get_string('viewtype', 'session', '');
$viewtype = $nv_Request->get_string('viewtype', 'post', $viewtype_old);
if (!empty($viewtype)) {
    $global_array_shops_cat[$catid]['viewcat'] = $viewtype;
}

if (!defined('NV_IS_MODADMIN') and $page < 5 and !$ajax) {
    $cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_' . $catid . '_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
    if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
        $contents = $cache;
    }
}

if (empty($contents)) {
    $data_content = array( );

    $count = 0;
    $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';
    $orderby = '';
    if ($sorts == 0) {
        $orderby = 'id DESC ';
    } elseif ($sorts == 1) {
        $orderby = 'product_price ASC, id DESC ';
    } else {
        $orderby = ' product_price DESC, id DESC ';
    }

    $_sql = '';
    if (!empty($array_id_group)) {
        $arr_id = array();
        foreach ($array_id_group as $id_group) {
            $group = $global_array_group[$id_group];
            $arr_id[$group['parentid']][] = $id_group;
        }

        $_sql = 'SELECT DISTINCT pro_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_items WHERE ';
        $j = 1;
        foreach ($arr_id as $listid) {
            $a = sizeof($listid);
            if ($a > 0) {
                $arr_sql = array();
                for ($i = 0; $i < $a; $i++) {
                    $arr_sql[]= ' pro_id IN (SELECT pro_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_items WHERE group_id=' . $listid[$i] . ')';
                }
                $_sql .= ' ('. implode(' OR ', $arr_sql) . ')';
            }
            if ($j < count($arr_id)) {
                $_sql .= ' AND ';
            }
            $j++;
        }

        if (!empty($_sql)) {
            $_sql = ' AND t1.id IN ( ' . $_sql . ' )';
        }
    }

    if ($global_array_shops_cat[$catid]['viewcat'] == 'view_home_cat' and $global_array_shops_cat[$catid]['numsubcat'] > 0) {
        $data_content = array( );
        $array_subcatid = explode(',', $global_array_shops_cat[$catid]['subcatid']);

        foreach ($array_subcatid as $catid_i) {
            $array_info_i = $global_array_shops_cat[$catid_i];

            $array_cat = array( );
            $array_cat = GetCatidInParent($catid_i);

            // Fetch Limit
            if ($array_url_group or $ajax) {
                $db->sqlreset()->select('COUNT(*)')->from($db_config['prefix'] . '_' . $module_data . '_rows t1')->where('t1.listcatid IN (' . implode(',', $array_cat) . ') AND t1.status=1' . $_sql);
            } else {
                $db->sqlreset()->select('COUNT(*)')->from($db_config['prefix'] . '_' . $module_data . '_rows t1')->where('t1.listcatid IN (' . implode(',', $array_cat) . ') AND t1.status =1');
            }

            $num_pro = $db->query($db->sql())->fetchColumn();

            $db->select('t1.id, t1.listcatid, t1.publtime, t1.' . NV_LANG_DATA . '_title, t1.' . NV_LANG_DATA . '_alias, t1.' . NV_LANG_DATA . '_hometext, t1.homeimgalt, t1.homeimgfile, t1.homeimgthumb, t1.product_code, t1.product_number, t1.product_price, t1.money_unit, t1.discount_id, t1.showprice,t1.' . NV_LANG_DATA . '_gift_content, t1.gift_from, t1.gift_to, t2.newday')->join('INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_catalogs t2 ON t2.catid = t1.listcatid')->order($orderby)->limit($array_info_i['numlinks']);
            $result = $db->query($db->sql());

            $data_pro = array( );

            while (list($id, $listcatid, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgfile, $homeimgthumb, $product_code, $product_number, $product_price, $money_unit, $discount_id, $showprice, $gift_content, $gift_from, $gift_to, $newday) = $result->fetch(3)) {
                if ($homeimgthumb == 1) {
                    //image thumb

                    $thumb = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $homeimgfile;
                } elseif ($homeimgthumb == 2) {
                    //image file

                    $thumb = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $homeimgfile;
                } elseif ($homeimgthumb == 3) {
                    //image url

                    $thumb = $homeimgfile;
                } else {
                    //no image

                    $thumb = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image.jpg';
                }
                $data_pro[] = array(
                    'id' => $id,
                    'listcatid' => $listcatid,
                    'publtime' => $publtime,
                    'title' => $title,
                    'alias' => $alias,
                    'hometext' => $hometext,
                    'homeimgalt' => $homeimgalt,
                    'homeimgthumb' => $thumb,
                    'product_code' => $product_code,
                    'product_number' => $product_number,
                    'product_price' => $product_price,
                    'discount_id' => $discount_id,
                    'money_unit' => $money_unit,
                    'showprice' => $showprice,
                    'newday' => $newday,
                    'gift_content' => $gift_content,
                    'gift_from' => $gift_from,
                    'gift_to' => $gift_to,
                    'link_pro' => $link . $global_array_shops_cat[$catid_i]['alias'] . '/' . $alias . $global_config['rewrite_exturl'],
                    'link_order' => $link . 'setcart&amp;id=' . $id
                );
            }

            $data_content[] = array(
                'catid' => $catid_i,
                'subcatid' => $array_info_i['subcatid'],
                'title' => $array_info_i['title'],
                'link' => $array_info_i['link'],
                'data' => $data_pro,
                'num_pro' => $num_pro,
                'num_link' => $array_info_i['numlinks'],
                'image' => $array_info_i['image']
            );
        }

        if ($page > 1) {
            Header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
            exit();
        }

        $contents = call_user_func('view_home_cat', $data_content, $sorts);
    } else {
        // Fetch Limit
        if ($global_array_shops_cat[$catid]['numsubcat'] == 0) {
            $where = ' t1.listcatid=' . $catid;
        } else {
            $array_cat = array( );
            $array_cat = GetCatidInParent($catid, true);
            $where = ' t1.listcatid IN (' . implode(',', $array_cat) . ')';
        }

        if ($array_url_group or !empty($array_id_group)) {
            $db->sqlreset()->select('COUNT(*)')->from($db_config['prefix'] . '_' . $module_data . '_rows t1')->where($where . ' AND t1.status =1' . $_sql);
        } else {
            $db->sqlreset()->select('COUNT(*)')->from($db_config['prefix'] . '_' . $module_data . '_rows t1')->where($where . ' AND t1.status =1');
        }

        $num_items = $db->query($db->sql())->fetchColumn();

        $db->select('t1.id, t1.listcatid, t1.publtime, t1.' . NV_LANG_DATA . '_title, t1.' . NV_LANG_DATA . '_alias, t1.' . NV_LANG_DATA . '_hometext, t1.homeimgalt, t1.homeimgfile, t1.homeimgthumb, t1.product_code, t1.product_number, t1.product_price, t1.money_unit, t1.discount_id, t1.showprice, t1.' . NV_LANG_DATA . '_gift_content, t1.gift_from, t1.gift_to, t2.newday, t2.image')->join('INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_catalogs t2 ON t2.catid = t1.listcatid')->order($orderby)->limit($per_page)->offset(($page - 1) * $per_page);
        $result = $db->query($db->sql());

        $data_content = GetDataIn($result, $catid);
        $data_content['count'] = $num_items;

        $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;ajax=1&amp;catid=' . $catid . '&amp;listgroupid=' . $listgroupid;
        $pages = nv_generate_page($base_url, $num_items, $per_page, $page, true, true, 'nv_urldecode_ajax', 'category');

        if (empty($array_url_group) and !$ajax) {
            $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_shops_cat[$catid]['alias'];
            $pages = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
        }

        if (sizeof($data_content['data']) < 1 and $page > 1) {
            Header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
            exit();
        }
        $contents = call_user_func($global_array_shops_cat[$catid]['viewcat'], $data_content, $compare_id, $pages, $sorts, $viewtype);
    }

    if (!defined('NV_IS_MODADMIN') and $contents != '' and $cache_file != '' and !$ajax) {
        $nv_Cache->setItem($module_name, $cache_file, $contents);
    }
}

if ($page > 1) {
    $page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
    $description .= ' ' . $page;
}

include NV_ROOTDIR . '/includes/header.php';
if ($ajax) {
    echo $contents;
} else {
    echo nv_site_theme($contents);
}
include NV_ROOTDIR . '/includes/footer.php';
