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
        $merge_fields['leader_name'] = [
            'name' => $nv_Lang->getModule('merge_field_leader_name'),
            'data' => '' // Dữ liệu ở đây
        ];
        $merge_fields['group_name'] = [
            'name' => $nv_Lang->getModule('group_name'),
            'data' => '' // Dữ liệu ở đây
        ];
        $merge_fields['link'] = [
            'name' => $nv_Lang->getModule('merge_field_do_link'),
            'data' => '' // Dữ liệu ở đây
        ];

        if ($vars['mode'] != 'PRE') {
            $group_info = $vars[0];
            //$global_config = $vars[1];
            $url_group = $vars[2];
            $user_info = $vars[3];

            $merge_fields['user_full_name']['data'] = nv_show_name_user($user_info['first_name'], $user_info['last_name'], $user_info['username']);
            $merge_fields['leader_name']['data'] = $group_info['title'];
            $merge_fields['group_name']['data'] = $group_info['title'];
            $merge_fields['link']['data'] = $url_group;
        }

        $nv_Lang->changeLang();
    }

    return $merge_fields;
};
nv_add_hook($module_name, 'get_email_merge_fields', $priority, $callback, $hook_module, $pid);
