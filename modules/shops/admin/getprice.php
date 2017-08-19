<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$id = $nv_Request->get_title('id', 'get', 0);
$cid = $nv_Request->get_title('cid', 'get', 0);

if ($id > 0) {
    $rowcontent = $db->query("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_rows where id=" . $id)->fetch();
    $rowcontent['product_price'] = ($rowcontent['product_price'] > 0) ? number_format($rowcontent['product_price'], nv_get_decimals($pro_config['money_unit'])) : '';
} else {
    $rowcontent = array(
        'money_unit' => $pro_config['money_unit'],
        'discount_id' => 0,
        'price_config' => ''
    );
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('rowcontent', $rowcontent);

foreach ($money_config as $code => $info) {
    $info['select'] = ($rowcontent['money_unit'] == $code) ? "selected=\"selected\"" : "";
    $xtpl->assign('MON', $info);
    $xtpl->parse('main.product_price.money_unit');
    $xtpl->parse('main.typeprice2.money_unit');
}

$typeprice = ($cid) ? $global_array_shops_cat[$cid]['typeprice'] : 1;
if ($typeprice == 1) {
    // List discount
    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_discounts';
    $_result = $db->query($sql);
    while ($_discount = $_result->fetch()) {
        $_discount['selected'] = ($_discount['did'] == $rowcontent['discount_id']) ? "selected=\"selected\"" : "";
        $xtpl->assign('DISCOUNT', $_discount);
        $xtpl->parse('main.typeprice1.discount');
    }
    $xtpl->parse('main.typeprice1');
    $xtpl->parse('main.product_price');
} elseif ($typeprice == 2) {
    $_arr_price_config = (empty($rowcontent['price_config'])) ? array( ) : unserialize($rowcontent['price_config']);
    $i = sizeof($_arr_price_config);
    ++$i;
    $_arr_price_config[$i] = array(
        'id' => $i,
        'number_to' => ($i == 1) ? 1 : '',
        'price' => ($i == 1) ? $rowcontent['product_price'] : '',
    );

    foreach ($_arr_price_config as $price_config) {
        $price_config['price'] = ($price_config['price'] > 0) ? number_format($price_config['price'], nv_get_decimals($pro_config['money_unit'])) : '';
        $xtpl->assign('PRICE_CONFIG', $price_config);
        $xtpl->parse('main.typeprice2.loop');
    }
    $xtpl->parse('main.typeprice2');
} else {
    $xtpl->parse('main.product_price');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
