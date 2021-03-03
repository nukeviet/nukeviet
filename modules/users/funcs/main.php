<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */
if (!defined('NV_IS_MOD_USER')) {
    die('Stop!!!');
}

if (isset($array_op[0])) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
$mod_title = isset($lang_module['main_title']) ? $lang_module['main_title'] : $module_info['custom_title'];

if (!defined('NV_IS_ADMIN') and !$global_config['allowuserlogin']) {
    $contents = user_info_exit($lang_module['notallowuserlogin']);
} else {
    if (!defined('NV_IS_USER')) {
        $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=login';
        $nv_redirect = nv_get_redirect();
        if (!empty($nv_redirect)) {
            $url .= '&nv_redirect=' . $nv_redirect;
        }
        nv_redirect_location($url);
    } else {
        // So nhom dang quan ly
        $user_info['group_manage'] = $db->query('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $user_info['userid'] . ' AND is_leader=1')->fetchColumn();

        // Lay cac du lieu tuy bien
        $array_field_config = [];
        $result_field = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field WHERE user_editable = 1 ORDER BY weight ASC');
        while ($row_field = $result_field->fetch()) {
            $language = unserialize($row_field['language']);
            $row_field['title'] = (isset($language[NV_LANG_DATA])) ? $language[NV_LANG_DATA][0] : $row['field'];
            $row_field['description'] = (isset($language[NV_LANG_DATA])) ? nv_htmlspecialchars($language[NV_LANG_DATA][1]) : '';
            
            if (!empty($row_field['field_choices'])) {
                $row_field['field_choices'] = unserialize($row_field['field_choices']);
            } elseif (!empty($row_field['sql_choices'])) {
                $row_field['sql_choices'] = explode('|', $row_field['sql_choices']);
                $row_field['field_choices'] = [];
                $sql = 'SELECT ' . $row_field['sql_choices'][2] . ', ' . $row_field['sql_choices'][3] . ' FROM ' . $row_field['sql_choices'][1];
                if (!empty($row_field['sql_choices'][4]) and !empty($row_field['sql_choices'][5])) {
                    $sql .= ' ORDER BY ' . $row_field['sql_choices'][4] . ' ' . $row_field['sql_choices'][5];
                }
                $result = $db->query($sql);
                
                $weight = 0;
                while (list ($key, $val) = $result->fetch(3)) {
                    $row_field['field_choices'][$key] = $val;
                }
            }
            $row_field['system'] = $row_field['is_system'];
            $array_field_config[] = $row_field;
        }

        // Cac du lieu tuy bien cua thanh vien
        $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_info WHERE userid=' . $user_info['userid'];
        $result = $db->query($sql);
        $custom_fields = $result->fetch();
        
        $contents = user_welcome($array_field_config, $custom_fields);
    }
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';