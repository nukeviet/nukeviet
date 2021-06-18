<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

$lang_array = array(
    'vi' => $lang_module['addads_block_lang_vi'],
    'en' => $lang_module['addads_block_lang_en'],
    'ru' => $lang_module['addads_block_lang_ru'],
    'zz' => $lang_module['addads_block_lang_zz']
);

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE act=1 ORDER BY blang ASC';
$global_array_plans = $nv_Cache->db($sql, 'id', $module_name);

$manament = array();
$global_array_uplans = array();
// Kiểm tra quyền đăng quảng cáo
if (defined('NV_IS_USER')) {
    foreach ($global_array_plans as $plan) {
        if (nv_user_in_groups($plan['uploadgroup'])) {
            $global_array_uplans[$plan['id']] = $plan;
        }
    }
    unset($plan);
    if (!empty($global_array_uplans)) {
        define('NV_IS_BANNER_CLIENT', true);
        $manament['main'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
        $manament['addads'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=addads';
        $manament['stats'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=stats';
    }
}

define('NV_IS_MOD_BANNERS', true);
