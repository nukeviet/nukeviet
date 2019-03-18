<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

$ajax = $nv_Request->isset_request('ajax', 'get,post');
$listgroupid = $nv_Request->get_string('listgroupid', 'get,post', '');
$array_id_group = [];

if ($ajax) {
    // Xem qua ajax (lọc theo loại sản phẩm)
    $page = $nv_Request->get_int('page', 'get,post', 1);
    $catid = $nv_Request->get_int('catid', 'get,post', 0);

    if (!empty($listgroupid)) {
        $array_id_group = array_map('intval', explode(',', $listgroupid));
    }
}

if (empty($catid)) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true);
}

$compare_id = $nv_Request->get_string($module_data . '_compare_id', 'session', '');
$compare_id = unserialize($compare_id);

/*
 * Xem theo loại sản phẩm trên URL
 * Quy luật: GroupAliasLev1--GroupAliasLev2/GroupAliasLev1--GroupAliasLev2/...
 * Lưu ý: Sort lại mảng sau đó build ra lại url để có URL không trùng lặp tiêu đề
 */
unset($array_op[0]);
$array_url_group = $array_url_group_alias = [];
foreach ($array_op as $_inurl) {
    if (preg_match('/^page\-([0-9]+)$/', $_inurl, $m)) {
        $page = $m[1];
    } elseif (preg_match('/^([a-z0-9\-]+)\-\-([a-z0-9\-]+)$/i', $_inurl, $m)) {
        /*
         * Trong phần quản lý nhóm chỉ có hai cấp do đó
         * Xác định được groupid lev1 thì có nghĩa $m[2] đã là lev2
         */
        $m[2] = strtolower($m[2]);
        $_groupid_lev2 = isset($global_array_group_alias[$m[2]]) ? $global_array_group_alias[$m[2]] : 0;
        $_groupid_lev1 = $_groupid_lev2 ? $global_array_group[$_groupid_lev2]['parentid'] : 0;

        if ($_groupid_lev2 and $_groupid_lev1) {
            $array_id_group[] = $_groupid_lev2;
            $array_url_group[$global_array_group[$_groupid_lev1]['alias']][] = $global_array_group[$_groupid_lev2]['alias'];
            $array_url_group_alias[] = $global_array_group[$_groupid_lev1]['alias'] . '--' . $global_array_group[$_groupid_lev2]['alias'];
        }
    }
}

// Kiểm tra URL duy nhất nếu không ajax, khi ajax không kiểm tra.
if (!$ajax) {
    $base_url_rewrite = str_replace('&amp;', '&', $global_array_shops_cat[$catid]['link']);
    // URL khi xem nhóm sản phẩm
    if (!empty($array_url_group_alias)) {
        asort($array_url_group_alias);
        $base_url_rewrite .= '/' . implode('/', $array_url_group_alias);
    }
    if ($page > 1) {
        $base_url_rewrite .= '/page-' . $page;
    }
    $base_url_rewrite = nv_url_rewrite($base_url_rewrite, true);
    if ($_SERVER['REQUEST_URI'] != $base_url_rewrite and NV_MAIN_DOMAIN . $_SERVER['REQUEST_URI'] != $base_url_rewrite) {
        nv_redirect_location($base_url_rewrite);
    }
}

// Thẻ meta của site
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

// Dùng session, form để chủ động sắp xếp sản phẩm
$sorts_old = $nv_Request->get_int('sorts', 'session', $pro_config['sortdefault']);
$sorts = $nv_Request->get_int('sorts', 'post', $sorts_old);

// Dùng session, form để chủ động điều khiển kiểu hiển thị
$viewtype_old = $nv_Request->get_string('viewtype', 'session', '');
$viewtype = $nv_Request->get_string('viewtype', 'post', $viewtype_old);

if (!empty($viewtype)) {
    $global_array_shops_cat[$catid]['viewcat'] = $viewtype;
}

// Cache lại 5 trang đầu tiên đối với khách?
if (!defined('NV_IS_MODADMIN') and $page < 5 and !$ajax) {
    $cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . md5($op . '_' . $catid . '_' . $page . '_' . implode('|', $array_url_group_alias)) . '_' . NV_CACHE_PREFIX . '.cache';
    if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
        $contents = $cache;
    }
}

if (empty($contents)) {
    $data_content = [];

    $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';
    $orderby = '';
    if ($sorts == 0) {
        $orderby = 'id DESC ';
    } elseif ($sorts == 1) {
        $orderby = 'product_price ASC, id DESC ';
    } else {
        $orderby = ' product_price DESC, id DESC ';
    }

    // Lọc sản phẩm theo nhóm
    $sql_groups = '';
    if (!empty($array_id_group)) {
        $arr_id = [];
        $array_id_group = array_unique($array_id_group);
        foreach ($array_id_group as $id_group) {
            $group = $global_array_group[$id_group];
            $arr_id[$group['parentid']][] = $id_group;
        }

        $sql_groups = 'SELECT DISTINCT pro_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_items WHERE ';
        $j = 1;
        foreach ($arr_id as $listid) {
            $a = sizeof($listid);
            if ($a > 0) {
                $arr_sql = [];
                for ($i = 0; $i < $a; $i++) {
                    $arr_sql[] = ' pro_id IN (SELECT pro_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_items WHERE group_id=' . $listid[$i] . ')';
                }
                $sql_groups .= ' (' . implode(' OR ', $arr_sql) . ')';
            }
            if ($j < sizeof($arr_id)) {
                $sql_groups .= ' AND ';
            }
            $j++;
        }

        $sql_groups = ' AND t1.id IN ( ' . $sql_groups . ' )';
    }

    if ($global_array_shops_cat[$catid]['viewcat'] == 'view_home_cat' and $global_array_shops_cat[$catid]['numsubcat'] > 0) {
        // Hiển thị theo loại sản phẩm
        $data_content = [];
        $array_subcatid = explode(',', $global_array_shops_cat[$catid]['subcatid']);

        foreach ($array_subcatid as $catid_i) {
            $array_info_i = $global_array_shops_cat[$catid_i];

            $array_cat = [];
            $array_cat = GetCatidInParent($catid_i);

            $db->sqlreset()
            ->select('COUNT(*)')
            ->from($db_config['prefix'] . '_' . $module_data . '_rows t1')
            ->where('t1.listcatid IN (' . implode(',', $array_cat) . ') AND t1.status=1' . $sql_groups);

            $num_pro = $db->query($db->sql())
                ->fetchColumn();

            $db->select('t1.id, t1.listcatid, t1.publtime, t1.' . NV_LANG_DATA . '_title, t1.' . NV_LANG_DATA . '_alias, t1.' . NV_LANG_DATA . '_hometext, t1.homeimgalt, t1.homeimgfile, t1.homeimgthumb, t1.product_code, t1.product_number, t1.product_price, t1.money_unit, t1.discount_id, t1.showprice,t1.' . NV_LANG_DATA . '_gift_content, t1.gift_from, t1.gift_to, t2.newday')
                ->join('INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_catalogs t2 ON t2.catid = t1.listcatid')
                ->order($orderby)
                ->limit($array_info_i['numlinks']);
            $result = $db->query($db->sql());

            $data_pro = [];

            while (list ($id, $listcatid, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgfile, $homeimgthumb, $product_code, $product_number, $product_price, $money_unit, $discount_id, $showprice, $gift_content, $gift_from, $gift_to, $newday) = $result->fetch(3)) {
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
            nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true);
        }

        $contents = call_user_func('view_home_cat', $data_content, $sorts);
    } else {
        // Hiển thị danh sách sản phẩm
        if ($global_array_shops_cat[$catid]['numsubcat'] == 0) {
            $where = ' t1.listcatid=' . $catid;
        } else {
            $array_cat = [];
            $array_cat = GetCatidInParent($catid, true);
            $where = ' t1.listcatid IN (' . implode(',', $array_cat) . ')';
        }

        $db->sqlreset()
        ->select('COUNT(*)')
        ->from($db_config['prefix'] . '_' . $module_data . '_rows t1')
        ->where($where . ' AND t1.status =1' . $sql_groups);

        $num_items = $db->query($db->sql())
            ->fetchColumn();

        $db->select('t1.id, t1.listcatid, t1.publtime, t1.' . NV_LANG_DATA . '_title, t1.' . NV_LANG_DATA . '_alias, t1.' . NV_LANG_DATA . '_hometext, t1.homeimgalt, t1.homeimgfile, t1.homeimgthumb, t1.product_code, t1.product_number, t1.product_price, t1.money_unit, t1.discount_id, t1.showprice, t1.' . NV_LANG_DATA . '_gift_content, t1.gift_from, t1.gift_to, t2.newday, t2.image')
            ->join('INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_catalogs t2 ON t2.catid = t1.listcatid')
            ->order($orderby)
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);
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
            nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true);
        }

        $contents = nv_template_viewcat($data_content, $compare_id, $pages, $sorts, $global_array_shops_cat[$catid]['viewcat']);
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
