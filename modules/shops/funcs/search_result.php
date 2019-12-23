<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (! defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$bid = 1;
// block host
$num = $pro_config['per_page'];
$data_content = array();
$search = "";
$url = '';

$keyword = $nv_Request->get_string('keyword', 'get');
$keyword = str_replace('+', ' ', $keyword);
$kwhtml = nv_htmlspecialchars($keyword);
$price1_temp = $nv_Request->get_string('price1', 'get', '');
$price2_temp = $nv_Request->get_string('price2', 'get', '');
$typemoney = $nv_Request->get_string('typemoney', 'get', '');
$cataid = $nv_Request->get_int('cata', 'get', 0);
$groupid = $nv_Request->get_string('filter', 'get', '');
$group_price = $nv_Request->get_string('group_price', 'get', '');
if (! empty($group_price)) {
    $url .= '&group_price=' . $group_price;
    $group_price = nv_base64_decode($group_price);
    $group_price = unserialize($group_price);
    if (!empty($group_price)) {
        $search .= " AND";
        foreach ($group_price as $i => $group_price_i) {
            $group_price_i = array_map('intval', explode('-', $group_price_i));
            if ($group_price_i[0] <= $group_price_i[1]) {
                $search .= ($i > 0 ? " OR " : "") . " product_price BETWEEN " . $group_price_i[0] . " AND " . $group_price_i[1] . " ";
            } else {
                $search .= ($i > 0 ? " OR " : "") . " product_price > " . $group_price_i[0] . " ";
            }
        }
    }
}

$sid = $nv_Request->get_int('sid', 'get', 0);
$page = $nv_Request->get_int('page', 'get', 1);
$num_items = 0;

$compare_id = $nv_Request->get_string($module_data . '_compare_id', 'session', '');
$compare_id = unserialize($compare_id);

if ($price1_temp == '') {
    $price1 = - 1;
} else {
    $price1 = floatval($price1_temp);
}
if ($price2_temp == '') {
    $price2 = - 1;
} else {
    $price2 = floatval($price2_temp);
}

// Set data form search
$xtpl = new XTemplate("search_all.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
foreach ($global_array_shops_cat as $row) {
    $xtitle_i = "";
    if ($row['lev'] > 0) {
        $xtitle_i .= "&nbsp;&nbsp;&nbsp;";
        for ($i = 1; $i <= $row['lev']; $i++) {
            $xtitle_i .= "&nbsp;&nbsp;&nbsp;";
        }
        $xtitle_i .= "&nbsp;";
    }
    $row['xtitle'] = $xtitle_i . $row['title'];
    $row['selected'] = ($cataid == $row['catid']) ? "selected=\"selected\"" : "";
    $xtpl->assign('ROW', $row);
    $xtpl->parse('form.loopcata');
}

// Get money
$sql = "SELECT code,currency FROM " . $db_config['prefix'] . "_" . $module_data . "_money_" . NV_LANG_DATA;
$result = $db->query($sql);

while ($row = $result->fetch()) {
    $row['selected'] = ($typemoney == $row['code']) ? "selected=\"selected\"" : "";
    $xtpl->assign('ROW', $row);
    $xtpl->parse('form.typemoney');
}

if ($price1 == - 1) {
    $price1_temp = "";
}
if ($price2 == - 1) {
    $price2_temp = "";
}

$xtpl->assign('value_keyword', $keyword);
$xtpl->assign('value_price1', $price1_temp);
$xtpl->assign('value_price2', $price2_temp);

if ($pro_config['active_price']) {
    $xtpl->parse('form.price');
}

$xtpl->parse('form');
$contents = $xtpl->text('form');

if (! empty($groupid)) {
    $url .= '&filter=' . $groupid;
    $groupid = nv_base64_decode($groupid);
    $groupid = unserialize($groupid);

    $arr_id = array();
    foreach ($groupid as $id_group) {
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
        $search .= " AND t1.id IN(" . $_sql . ")";
    }
}

if ($keyword != "") {
    $search .= " AND (t1." . NV_LANG_DATA . "_title LIKE '%" . $db->dblikeescape($kwhtml) . "%' OR product_code LIKE '%" . $db->dblikeescape($keyword) . "%')";
}

if (($price1 >= 0 and $price2 > 0)) {
    $search .= " AND product_price BETWEEN " . $price1 . " AND " . $price2 . " ";
} elseif ($price2 == - 1 and $price1 >= 0) {
    $search .= " AND product_price >= " . $price1 . " ";
} elseif ($price1 == - 1 and $price2 > 0) {
    $search .= " AND product_price < " . $price2 . " ";
}

if (! empty($typemoney)) {
    $search .= " AND money_unit = " . $db->quote($typemoney);
}
$sql_i = ", if(t1.money_unit ='" . $pro_config['money_unit'] . "', t1.product_price , t1.product_price * t2.exchange ) AS product_saleproduct ";
$order_by = " product_saleproduct DESC ";

if (! empty($typemoney)) {
    $search .= " AND money_unit = " . $db->quote($typemoney);
}
if ($cataid != 0) {
    $array_cat = GetCatidInParent($cataid);
    $search .= " AND listcatid IN (" . implode(",", $array_cat) . ") ";
}
if ($sid != 0) {
    $search .= " AND source_id =" . $sid . " ";
}

if (empty($search)) {
    $contents = "<div align =\"center\">" . $lang_module['notresult'] . "</div>";
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

$show_price = "";
if ($pro_config['active_price']) {
    if (! empty($price1_temp) or ! empty($price2_temp)) {
        $show_price = "AND showprice=1";
    }
}

$table_search = "" . $db_config['prefix'] . "_" . $module_data . "_rows t1";
$table_exchange = " LEFT JOIN " . $db_config['prefix'] . "_" . $module_data . "_money_" . NV_LANG_DATA . " t2 ON t1.money_unit=t2.code";
$table_exchange1 = " INNER JOIN " . $db_config['prefix'] . "_" . $module_data . "_catalogs t3 ON t3.catid = t1.listcatid";

// Fetch Limit
$db->sqlreset()->select('DISTINCT COUNT(*)')->from($table_search . " " . $table_exchange . " " . $table_exchange1)->where("t1.status =1 " . $search . " " . $show_price);
$sth = $db->prepare($db->sql());
$sth->execute();

$num_items = $sth->fetchColumn();

$db->select("DISTINCT t1.id, t1.listcatid, t1.publtime, t1." . NV_LANG_DATA . "_title, t1." . NV_LANG_DATA . "_alias, t1." . NV_LANG_DATA . "_hometext, t1.homeimgalt, t1.homeimgfile, t1.homeimgthumb, t1.product_number, t1.product_price, t1.discount_id, t1.money_unit, t1.showprice, t1." . NV_LANG_DATA . "_gift_content, t1.gift_from, t1.gift_to, t3.newday, t2.exchange " . $sql_i)
    ->order($order_by)
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);
$result = $db->query($db->sql());

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=search_result&keyword=' . $keyword . '&price1=' . $price1 . '&price2=' . $price2 . '&typemoney=' . $typemoney . '&cata=' . $cataid . $url;
$html_pages = nv_generate_page($base_url, $num_items, $per_page, $page);

$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';

while (list($id, $listcatid, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgfile, $homeimgthumb, $product_number, $product_price, $discount_id, $money_unit, $showprice, $gift_content, $gift_from, $gift_to, $newday) = $result->fetch(3)) {
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

    $data_content[] = array(
        'id' => $id,
        'listcatid' => $listcatid,
        'publtime' => $publtime,
        'title' => $title,
        'alias' => $alias,
        'hometext' => $hometext,
        'homeimgalt' => $homeimgalt,
        'homeimgthumb' => $thumb,
        'product_number' => $product_number,
        'product_price' => $product_price,
        'discount_id' => $discount_id,
        'money_unit' => $money_unit,
        'showprice' => $showprice,
        'gift_content' => $gift_content,
        'gift_from' => $gift_from,
        'gift_to' => $gift_to,
        'newday' => $newday,
        'link_pro' => $link . $global_array_shops_cat[$listcatid]['alias'] . '/' . $alias . $global_config['rewrite_exturl'],
        'link_order' => $link . 'setcart&amp;id=' . $id
    );
}

if (count($data_content) == 0) {
    $contents .= "<div align =\"center\">" . $lang_module['notresult'] . "</div>";
} else {
    $contents .= view_search_all($data_content, $compare_id, $html_pages);
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
