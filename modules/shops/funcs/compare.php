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
$array_id = unserialize($nv_Request->get_string($module_data . '_compare_id', 'session', ''));

if ($nv_Request->isset_request('compare', 'post')) {
    $idss = $nv_Request->get_int('id', 'post', 0);
    $array_id = $nv_Request->get_string($module_data . '_compare_id', 'session', '');
    $array_id = unserialize($array_id);

    if (in_array($idss, $array_id)) {
        unset($array_id[$idss]);
        $array_id = serialize($array_id);
        $nv_Request->set_Session($module_data . '_compare_id', $array_id);
        $nv_Cache->delMod($module_name);
        die('OK');
    } else {
        $array_id[$idss] = $idss;
        if (count($array_id) > 4) {
            die('ERROR[NV]' . $lang_module['compare_limit'] . '[NV]' . $idss);
        } else {
            $array_id = serialize($array_id);
            $nv_Request->set_Session($module_data . '_compare_id', $array_id);
            $nv_Cache->delMod($module_name);
            die('OK');
        }
    }
}

if ($nv_Request->isset_request('compareresult', 'post')) {
    $array_id = $nv_Request->get_string($module_data . '_compare_id', 'session', '');
    $array_id = unserialize($array_id);

    if (count($array_id) < 2) {
        die($lang_module['num0']);
    } else {
        die('OK');
    }
}

if ($nv_Request->isset_request('compare_del', 'post') and $nv_Request->isset_request('id', 'post') and $nv_Request->isset_request('all', 'post')) {
    $action = $nv_Request->get_int('all', 'post', 0);

    $array_id = $nv_Request->get_string($module_data . '_compare_id', 'session', '');
    $array_id = unserialize($array_id);

    if ($action) {
        unset($array_id);
        $nv_Request->unset_request($module_data . '_compare_id', 'session');
    } else {
        $rm_id = $nv_Request->get_int('id', 'post', 0);
        unset($array_id[$rm_id]);
        $array_id = serialize($array_id);
        $nv_Request->set_Session($module_data . '_compare_id', $array_id);
    }
    $nv_Cache->delMod($module_name);
    die('OK');
}

$compare_url_rewrite = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=compare';
$compare_url_rewrite = nv_url_rewrite($compare_url_rewrite, true);
if ($_SERVER['REQUEST_URI'] != $compare_url_rewrite) {
    header('Location:' . $compare_url_rewrite);
    die();
}

$array_id = $nv_Request->get_string($module_data . '_compare_id', 'session', '');
$array_id = unserialize($array_id);
$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';

if (! empty($array_id)) {
    foreach ($array_id as $array_id_i) {
        $sql = 'SELECT id, listcatid, publtime, ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_alias, ' . NV_LANG_DATA . '_hometext, homeimgfile, homeimgalt, homeimgthumb, product_code, product_number, product_price, money_unit, discount_id, showprice, ' . NV_LANG_DATA . '_gift_content, ' . NV_LANG_DATA . '_bodytext FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id = ' . $array_id_i;
        $result = $db->query($sql);
        while (list($id, $listcatid, $publtime, $title, $alias, $hometext, $homeimgfile, $homeimgalt, $homeimgthumb, $product_code, $product_number, $product_price, $money_unit, $discount_id, $showprice, $gift_content, $bodytext) = $result->fetch(3)) {
            // Xac dinh anh lon
            $homeimgfiles1 = $homeimgfile;
            if ($homeimgthumb == 1) {
                //image thumb

                $homeimgthumbs = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $homeimgfiles1;
                $homeimgthumbs = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $homeimgfiles1;
            } elseif ($homeimgthumb == 2) {
                //image file

                $homeimgthumbs = $homeimgfiles1 = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $homeimgfiles1;
            } elseif ($homeimgthumb == 3) {
                //image url

                $homeimgthumbs = $homeimgfile = $homeimgfiles1;
            } else {
                //no image

                $homeimgthumbs = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image.jpg';
            }

            $data_pro[] = array(
                'id' => $id,
                'publtime' => $publtime,
                'title' => $title,
                'alias' => $alias,
                'hometext' => $hometext,
                'homeimgalt' => $homeimgalt,
                'homeimgthumb' => $homeimgthumbs,
                'product_code' => $product_code,
                'product_number' => $product_number,
                'product_price' => $product_price,
                'discount_id' => $discount_id,
                'money_unit' => $money_unit,
                'showprice' => $showprice,
                'link_pro' => $link . $global_array_shops_cat[$listcatid]['alias'] . '/' . $alias . $global_config['rewrite_exturl'],
                'link_order' => $link . 'setcart&amp;id=' . $id,
                'gift_content' => $gift_content,
                'bodytext' => $bodytext
            );
        }
    }
} else {
    Header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true));
    die();
}

$contents = compare($data_pro);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
