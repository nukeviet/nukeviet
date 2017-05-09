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

$contents = '';

if ($nv_Request->isset_request('get_carrier', 'get')) {
    $shopsid = $nv_Request->get_int('shops_id', 'get', 0);
    $carrier_id = $nv_Request->get_int('carrier_id', 'get', 0);

    if (! empty($shopsid)) {
        $db->sqlreset()
          ->select('t2.id, t2.name')
          ->from($db_config['prefix'] . '_' . $module_data . '_shops_carrier t1')
          ->join('INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_carrier t2 ON t2.id = t1.carrier_id')
          ->where('shops_id=' . $shopsid);

        $_query = $db->query($db->sql());
        $i = 0;
        while ($row = $_query->fetch()) {
            $sl = $row['id'] == $carrier_id ? 'checked="checked"' : '';
            $contents .= '<label class="show"><input type="radio" name="carrier" value="' . $row['id'] . '" title="' . $row['name'] . '" onclick="nv_carrier_change()" ' . $sl . ' />' . $row['name'] . '</label>';
            $i++;
        }
    }
}

if ($nv_Request->isset_request('get_location', 'get, post')) {
    $locationid = $nv_Request->get_int('location_id', 'get, post', 0);
    if (! empty($locationid)) {
        $contents = $array_location[$locationid]['title'];
        while ($array_location[$locationid]['parentid'] > 0) {
            $items = $array_location[$array_location[$locationid]['parentid']];
            $contents .= ', ' . $items['title'];
            $array_location[$locationid]['parentid'] = $items['parentid'];
        }
    }
}

if ($nv_Request->isset_request('get_shipping_price', 'get')) {
    $weight = $nv_Request->get_float('weight', 'get', 0);
    $weight_unit = $nv_Request->get_string('weight_unit', 'get', '');
    $location_id = $nv_Request->get_int('location_id', 'get', 0);
    $shops_id = $nv_Request->get_int('shops_id', 'get', 0);
    $carrier_id = $nv_Request->get_int('carrier_id', 'get', 0);

    $str_error = '<span class="error">' . $lang_module['shipping_error'] . '</span>';

    if (!empty($weight)) {
        $contents = nv_shipping_price($weight, $weight_unit, $location_id, $shops_id, $carrier_id);
        if (!empty($contents)) {
            $contents = $contents . ' ' . $pro_config['money_unit'];
        } else {
            $contents = $str_error;
        }
    } else {
        $contents = $str_error;
    }
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
