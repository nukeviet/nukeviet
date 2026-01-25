<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// Các trường dữ liệu gửi email liên quan đến quản trị viên
$callback = function ($vars, $from_data, $receive_data) {
    $merge_fields = [];
    $vars['pid'] = (int) $vars['pid'];
    $vars['setpids'] = array_map('intval', $vars['setpids']);

    if (in_array($vars['pid'], $vars['setpids'], true)) {
        global $nv_Lang, $global_config;

        // Đọc ngôn ngữ tạm của module
        $nv_Lang->loadModule('authors', true, true);

        $merge_fields['greeting_user'] = [
            'name' => $nv_Lang->getGlobal('greeting_user'),
            'data' => ''
        ];
        $merge_fields['link'] = [
            'name' => $nv_Lang->getGlobal('link'),
            'data' => ''
        ];
        $merge_fields['time'] = [
            'name' => $nv_Lang->getGlobal('time'),
            'data' => 0
        ];
        $merge_fields['note'] = [
            'name' => $nv_Lang->getGlobal('note'),
            'data' => ''
        ];
        $merge_fields['email'] = [
            'name' => $nv_Lang->getGlobal('email'),
            'data' => ''
        ];
        $merge_fields['sig'] = [
            'name' => $nv_Lang->getModule('sig'),
            'data' => ''
        ];
        $merge_fields['position'] = [
            'name' => $nv_Lang->getModule('position'),
            'data' => ''
        ];
        $merge_fields['username'] = [
            'name' => $nv_Lang->getGlobal('username'),
            'data' => ''
        ];
        $merge_fields['oauth_name'] = [
            'name' => $nv_Lang->getModule('2step_oauth_gate'),
            'data' => ''
        ];
        $merge_fields['oauth_id'] = [
            'name' => $nv_Lang->getModule('2step_oauth_email_or_id'),
            'data' => ''
        ];

        if ($vars['mode'] != 'PRE') {
            // Field dữ liệu cho các fields
            $lang = !empty($vars['lang']) ? $vars['lang'] : NV_LANG_INTERFACE;
            if ($lang != NV_LANG_INTERFACE and in_array($lang, $global_config['setup_langs'], true)) {
                $nv_Lang->loadFile(NV_ROOTDIR . '/includes/language/' . $lang . '/global.php', true);
            }

            // Họ tên và câu chào
            if (isset($vars['username'], $vars['first_name'])) {
                $merge_fields['greeting_user']['data'] = greeting_for_user_create($vars['username'], $vars['first_name'], $vars['last_name'] ?? '', $vars['gender'] ?? '', $lang);
            }

            // Các field dạng chuỗi thuần
            $direct_keys = ['link', 'note', 'email', 'sig', 'position', 'username', 'oauth_name', 'oauth_id'];
            foreach ($direct_keys as $key) {
                $merge_fields[$key]['data'] = $vars[$key] ?? '';
            }

            // Các field dạng thời gian
            $time_keys = ['time'];
            foreach ($time_keys as $key) {
                if (!empty($vars[$key]) and is_numeric($vars[$key])) {
                    $merge_fields[$key]['data'] = nv_datetime_format($vars[$key], 1);
                }
            }
        }

        $nv_Lang->changeLang();
    }

    return $merge_fields;
};
nv_add_hook($module_name, 'get_email_merge_fields', $priority, $callback, $hook_module, $pid);
