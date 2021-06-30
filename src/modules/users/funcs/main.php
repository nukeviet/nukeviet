<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

if (isset($array_op[0])) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
$mod_title = isset($lang_module['main_title']) ? $lang_module['main_title'] : $module_info['custom_title'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;

if (!defined('NV_IS_ADMIN') and !$global_config['allowuserlogin']) {
    $contents = user_info_exit($lang_module['notallowuserlogin']);
} else {
    if (!defined('NV_IS_USER')) {
        $page_url .= '&' . NV_OP_VARIABLE . '=login';
        $nv_redirect = nv_get_redirect();
        if (!empty($nv_redirect)) {
            $page_url .= '&nv_redirect=' . $nv_redirect;
        }
        nv_redirect_location($page_url);
    } else {
        // So nhom dang quan ly
        $user_info['group_manage'] = $db->query('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $user_info['userid'] . ' AND is_leader=1')->fetchColumn();

        // Lay cac du lieu tuy bien
        $array_field_config = nv_get_users_field_config();

        // Cac du lieu tuy bien cua thanh vien
        $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_info WHERE userid=' . $user_info['userid'];
        $result = $db->query($sql);
        $custom_fields = $result->fetch();

        $contents = user_welcome($array_field_config, $custom_fields);
    }
}

$canonicalUrl = getCanonicalUrl($page_url, true, true);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
