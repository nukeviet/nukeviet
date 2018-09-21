<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:24:58 AM
 */

// Các trường dữ liệu khi gửi email thông tin kích hoạt tài khoản đến email của thành viên
$callback = function($vars, $from_data, $receive_data) {
    $merge_fields = [];

    if (in_array($vars['pid'], $vars['setpids'])) {
        global $nv_Lang;

        // Đọc ngôn ngữ tạm của module
        $nv_Lang->loadModule($receive_data['module_info']['module_file'], false, true);

        $merge_fields['user_full_name'] = [
            'name' => $nv_Lang->getModule('full_name'),
            'data' => '' // Dữ liệu ở đây
        ];
        $merge_fields['user_username'] = [
            'name' => $nv_Lang->getModule('username'),
            'data' => '' // Dữ liệu ở đây
        ];
        $merge_fields['user_email'] = [
            'name' => $nv_Lang->getModule('email'),
            'data' => '' // Dữ liệu ở đây
        ];
        $merge_fields['user_password'] = [
            'name' => $nv_Lang->getModule('password'),
            'data' => '' // Dữ liệu ở đây
        ];
        $merge_fields['site_name'] = [
            'name' => $nv_Lang->getGlobal('site_name'),
            'data' => '' // Dữ liệu ở đây
        ];
        $merge_fields['active_link'] = [
            'name' => $nv_Lang->getModule('merge_field_active_link'),
            'data' => '' // Dữ liệu ở đây
        ];
        $merge_fields['timeout'] = [
            'name' => $nv_Lang->getModule('merge_field_timeout'),
            'data' => '' // Dữ liệu ở đây
        ];

        if ($vars['mode'] != 'PRE') {
            // Field dữ liệu cho các fields
            foreach ($merge_fields as $fkey => $fval) {
                if (isset($vars[$fkey])) {
                    $merge_fields[$fkey]['data'] = $vars[$fkey];
                } else {
                    $merge_fields[$fkey]['data'] = null;
                }
            }
        }

        $nv_Lang->changeLang();
    }

    return $merge_fields;
};
nv_add_hook($module_name, 'get_email_merge_fields', $priority, $callback, $hook_module, $pid);
