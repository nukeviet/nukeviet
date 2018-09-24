<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:24:58 AM
 */

// Các trường dữ liệu khi xóa quản trị
$callback = function($vars, $from_data, $receive_data) {
    $merge_fields = [];

    if (in_array($vars['pid'], $vars['setpids'])) {
        global $nv_Lang;

        $merge_fields['site_name'] = [
            'name' => $nv_Lang->getGlobal('site_name'),
            'data' => '' // Dữ liệu ở đây
        ];
        $merge_fields['delete_time'] = [
            'name' => $nv_Lang->getGlobal('merge_field_author_delete_time'),
            'data' => '' // Dữ liệu ở đây
        ];
        $merge_fields['delete_reason'] = [
            'name' => $nv_Lang->getGlobal('merge_field_author_delete_reason'),
            'data' => '' // Dữ liệu ở đây
        ];
        $merge_fields['contact_link'] = [
            'name' => $nv_Lang->getGlobal('merge_field_contact_link'),
            'data' => '' // Dữ liệu ở đây
        ];

        if ($vars['mode'] != 'PRE') {
            $admin_info = $vars[0];
            $global_config = $vars[1];
            $reason = $vars[2];

            $contact_link = $admin_info['view_mail'] ? $admin_info['email'] : $global_config['site_email'];

            $merge_fields['site_name']['data'] = $global_config['site_name'];
            $merge_fields['delete_time']['data'] = nv_date('d/m/Y H:i', NV_CURRENTTIME);
            $merge_fields['delete_reason']['data'] = $reason;
            $merge_fields['contact_link']['data'] = $contact_link;
        }
    }

    return $merge_fields;
};
nv_add_hook($module_name, 'get_email_merge_fields', $priority, $callback, $hook_module, $pid);
