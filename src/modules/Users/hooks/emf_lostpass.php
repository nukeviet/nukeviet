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
        $merge_fields['code'] = [
            'name' => $nv_Lang->getModule('verifykey'),
            'data' => '' // Dữ liệu ở đây
        ];
        $merge_fields['site_name'] = [
            'name' => $nv_Lang->getGlobal('site_name'),
            'data' => '' // Dữ liệu ở đây
        ];
        $merge_fields['timeout'] = [
            'name' => $nv_Lang->getModule('merge_field_timeout'),
            'data' => '' // Dữ liệu ở đây
        ];

        if ($vars['mode'] != 'PRE') {
            $user_info = $vars[0];
            $passlostkey = $vars[2];
            $timeout = $vars[3];
            $global_config = $vars[1];

            $merge_fields['user_full_name']['data'] = nv_show_name_user($user_info['first_name'], $user_info['last_name'], $user_info['username']);
            $merge_fields['site_name']['data'] = $global_config['site_name'];
            $merge_fields['code']['data'] = $passlostkey;
            $merge_fields['timeout']['data'] = nv_date('H:i d/m/Y', $timeout);
        }

        $nv_Lang->changeLang();
    }

    return $merge_fields;
};
nv_add_hook($module_name, 'get_email_merge_fields', $priority, $callback, $hook_module, $pid);
