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

$num = isset($_SESSION[$module_data . '_cart']) ? count($_SESSION[$module_data . '_cart']) : 0;
$total = 0;
$total_old = 0;
$total_coupons = 0;

$coupons_check = $nv_Request->get_int('coupons_check', 'get', 0);
$coupons_load = $nv_Request->get_int('coupons_load', 'get', 0);
$coupons_code = $nv_Request->get_title('coupons_code', 'get', '');

$counpons = array(
    'title' => '',
    'type' => 'p',
    'discount' => 0,
    'total_amount' => 0,
    'date_start' => 0,
    'date_end' => 0
);

if (!empty($coupons_code)) {
    $result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_coupons WHERE code = ' . $db->quote($coupons_code));
    $counpons = $result->fetch();
    $result = $db->query('SELECT pid FROM ' . $db_config['prefix'] . '_' . $module_data . '_coupons_product WHERE cid = ' . $counpons['id']);
    while (list($pid) = $result->fetch(3)) {
        $counpons['product'][] = $pid;
    }
}

if ($coupons_load) {
    $_SESSION[$module_data . '_coupons']['check'] = $coupons_check;
}

if (! empty($_SESSION[$module_data . '_cart'])) {
    foreach ($_SESSION[$module_data . '_cart'] as $pro_id => $info) {
    	$array=explode('_', $pro_id);
		$pro_id	=$array[0];
        $price = nv_get_price($pro_id, $pro_config['money_unit'], $info['num']);
        // Ap dung giam gia cho tung san pham dac biet
        if (!empty($counpons['product'])) {
            if (in_array($pro_id, $counpons['product'])) {
                $total_coupons = $total_coupons + $price['sale'];
            }
        }
        $total = $total + $price['sale'];
    }
    $total_old = $total;
}

if ((empty($counpons['total_amount']) or $total >= $counpons['total_amount']) and NV_CURRENTTIME >= $counpons['date_start'] and (empty($counpons['uses_per_coupon']) or $counpons['uses_per_coupon_count'] < $counpons['uses_per_coupon']) and (empty($counpons['date_end']) or NV_CURRENTTIME < $counpons['date_end'])) {
    // Ap dung giam gia cho tung san pham dac biet
    if ($total_coupons > 0) {
        if ($counpons['type'] == 'p') {
            if ($coupons_check) {
                $total = $total  - (($total_coupons * $counpons['discount']) / 100);
            }
        } else {
            if ($coupons_check) {
                $total = ($total_coupons - $counpons['discount']);
            }
        }
    } else {
        // Ap dung cho don hang

        if ($counpons['type'] == 'p') {
            if ($coupons_check) {
                $total = $total  - (($total * $counpons['discount']) / 100);
            }
        } else {
            if ($coupons_check) {
                $total = $total - $counpons['discount'];
            }
        }
    }

    if ($coupons_check) {
        $_SESSION[$module_data . '_coupons']['discount'] = $total_old - $total;
    }
}

if ($nv_Request->isset_request('get_shipping_price', 'get')) {
    $weight = $nv_Request->get_float('weight', 'get', 0);
    $weight_unit = $nv_Request->get_string('weight_unit', 'get', '');
    $location_id = $nv_Request->get_int('location_id', 'get', 0);
    $shops_id = $nv_Request->get_int('shops_id', 'get', 0);
    $carrier_id = $nv_Request->get_int('carrier_id', 'get', 0);

    $ship_price = nv_shipping_price($weight, $weight_unit, $location_id, $shops_id, $carrier_id);
    if (!empty($ship_price)) {
        $total += $ship_price;
    }
}

if ($pro_config['active_price'] == '0') {
    $total = 0;
}

$total = nv_number_format($total, nv_get_decimals($pro_config['money_unit']));

$lang_tmp['cart_title'] = $lang_module['cart_title'];
$lang_tmp['cart_product_title'] = $lang_module['cart_product_title'];
$lang_tmp['cart_product_total'] = $lang_module['cart_product_total'];
$lang_tmp['cart_check_out'] = $lang_module['cart_check_out'];
$lang_tmp['history_title'] = $lang_module['history_title'];
$lang_tmp['active_order_dis'] = $lang_module['active_order_dis'];
$lang_tmp['wishlist_product'] = $lang_module['wishlist_product'];
$lang_tmp['point_cart_text'] = $lang_module['point_cart_text'];
$lang_tmp['title_products'] = $lang_module['title_products'];
$lang_tmp['cart_check_cart'] = $lang_module['cart_check_cart'];

$xtpl = new XTemplate("block.cart.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_tmp);
$xtpl->assign('total', $total);
$xtpl->assign('TEMPLATE', $module_info['template']);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('LINK_VIEW', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=cart");
$xtpl->assign('WISHLIST', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=wishlist");

if (defined('NV_IS_USER')) {
    // Danh sach san pham yeu thich
    if ($pro_config['active_wishlist']) {
        $count = 0;
        $listid = $db->query('SELECT listid FROM ' . $db_config['prefix'] . '_' . $module_data . '_wishlist WHERE user_id = ' . $user_info['userid'] . '')->fetchColumn();
        if ($listid) {
            $count = count(explode(',', $listid));
        }
        $xtpl->assign('NUM_ID', $count);
        $xtpl->parse('main.wishlist');
    }

    // Lich su giao dich
    $xtpl->assign('LINK_HIS', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=history");
    $xtpl->parse('main.history');

    // Diem tich luy
    if ($pro_config['point_active']) {
        $xtpl->assign('POINT_URL', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=point");

        $point = 0;
        $result = $db->query('SELECT point_total FROM ' . $db_config['prefix'] . '_' . $module_data . '_point WHERE userid = ' . $user_info['userid']);
        if ($result->rowCount()) {
            $point = $result->fetchColumn();
        }
        $xtpl->assign('POINT', $point);
        $xtpl->parse('main.point');
    }
}

$xtpl->assign('money_unit', $pro_config['money_unit']);
$xtpl->assign('num', $num);

if ($pro_config['active_price'] == '1') {
    $xtpl->parse('main.enable.price');
}

if ($pro_config['active_order'] == '1') {
    $xtpl->parse('main.enable');
} else {
    $xtpl->parse('main.disable');
}

$xtpl->parse('main');
$content = $xtpl->text('main');
$content = nv_url_rewrite($content);

$type = $nv_Request->get_int('t', 'get', 0);
switch ($type) {
    case 0:
        echo $content;
        break;
    case 1:
        echo $num;
        break;
    case 2:
        echo $total;
        break;
    default:
        echo $content;
        break;
}
