<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use NukeViet\Template\Email\Emf;

// Các trường dữ liệu gửi email liên quan đến thành viên
$callback = function ($vars, $from_data, $receive_data) {
    $merge_fields = [];
    $vars['pid'] = (int) $vars['pid'];
    $vars['setpids'] = array_map('intval', $vars['setpids']);

    if (in_array($vars['pid'], $vars['setpids'], true)) {
        global $nv_Lang, $global_config;

        // Đọc ngôn ngữ tạm của module
        if (!empty($receive_data)) {
            // Trường hợp gửi email async không load ra cái này bởi chưa load đến $sys_mod
            $nv_Lang->loadModule($receive_data['module_info']['module_file'], false, true);
        }

        $merge_fields['greeting_user'] = [
            'name' => $nv_Lang->getGlobal('greeting_user'),
            'data' => ''
        ];
        $merge_fields['full_name'] = [
            'name' => $nv_Lang->getModule('full_name'),
            'data' => ''
        ];
        $merge_fields['username'] = [
            'name' => $nv_Lang->getGlobal('username'),
            'data' => ''
        ];
        $merge_fields['email'] = [
            'name' => $nv_Lang->getGlobal('email'),
            'data' => ''
        ];
        $merge_fields['active_deadline'] = [
            'name' => $nv_Lang->getModule('merge_field_active_deadline'),
            'data' => ''
        ];
        $merge_fields['link'] = [
            'name' => $nv_Lang->getModule('merge_field_link'),
            'data' => ''
        ];
        $merge_fields['new_code'] = [
            'name' => $nv_Lang->getModule('user_2step_newcodes'),
            'data' => [],
            'type' => Emf::T_LIST
        ];
        $merge_fields['oauth_name'] = [
            'name' => $nv_Lang->getModule('openid_server'),
            'data' => ''
        ];
        $merge_fields['pass_reset'] = [
            'name' => $nv_Lang->getModule('pass_reset_request'),
            'data' => 0
        ];
        $merge_fields['email_reset'] = [
            'name' => $nv_Lang->getModule('email_reset_request'),
            'data' => 0
        ];
        $merge_fields['password'] = [
            'name' => $nv_Lang->getModule('password'),
            'data' => ''
        ];
        $merge_fields['code'] = [
            'name' => $nv_Lang->getModule('code'),
            'data' => ''
        ];
        $merge_fields['send_newvalue'] = [
            'name' => $nv_Lang->getModule('mf_send_newvalue'),
            'data' => 0
        ];
        $merge_fields['newvalue'] = [
            'name' => $nv_Lang->getModule('editcensor_new'),
            'data' => ''
        ];
        $merge_fields['label'] = [
            'name' => $nv_Lang->getModule('mf_label'),
            'data' => ''
        ];
        $merge_fields['deadline'] = [
            'name' => $nv_Lang->getModule('mf_deadline'),
            'data' => ''
        ];
        $merge_fields['group_name'] = [
            'name' => $nv_Lang->getModule('group_name'),
            'data' => ''
        ];
        $merge_fields['security_key'] = [
            'name' => $nv_Lang->getModule('edit_seckey'),
            'data' => ''
        ];
        $merge_fields['passkey'] = [
            'name' => $nv_Lang->getModule('edit_passkey'),
            'data' => ''
        ];
        $merge_fields['user_agent'] = [
            'name' => $nv_Lang->getGlobal('browser'),
            'data' => ''
        ];
        $merge_fields['ip'] = [
            'name' => $nv_Lang->getGlobal('ip'),
            'data' => ''
        ];
        $merge_fields['action_time'] = [
            'name' => $nv_Lang->getModule('action_time'),
            'data' => ''
        ];
        $merge_fields['tstep_link'] = [
            'name' => $nv_Lang->getModule('tstep_link'),
            'data' => ''
        ];
        $merge_fields['pass_link'] = [
            'name' => $nv_Lang->getModule('pass_link'),
            'data' => ''
        ];
        $merge_fields['code_link'] = [
            'name' => $nv_Lang->getModule('code_link'),
            'data' => ''
        ];
        $merge_fields['passkey_link'] = [
            'name' => $nv_Lang->getModule('passkey_link'),
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
                $merge_fields['full_name']['data'] = nv_show_name_user($vars['first_name'], $vars['last_name'] ?? '', $vars['username'], $lang);
            }

            // Các field dạng chuỗi thuần
            $direct_keys = [
                'username', 'email', 'link', 'oauth_name', 'password', 'code', 'label', 'newvalue', 'group_name',
                'ip', 'security_key', 'passkey', 'user_agent', 'tstep_link', 'pass_link', 'code_link', 'passkey_link'
            ];
            foreach ($direct_keys as $key) {
                $merge_fields[$key]['data'] = $vars[$key] ?? '';
            }

            // Các field dạng số, mặc định 0
            $number_keys = ['send_newvalue', 'pass_reset'];
            foreach ($number_keys as $key) {
                $merge_fields[$key]['data'] = $vars[$key] ?? 0;
            }

            // Các field dạng thời gian
            $time_keys = ['active_deadline', 'deadline', 'action_time'];
            foreach ($time_keys as $key) {
                if (!empty($vars[$key]) and is_numeric($vars[$key])) {
                    $merge_fields[$key]['data'] = nv_datetime_format($vars[$key], 1);
                }
            }

            if (isset($vars['new_code']) and is_array($vars['new_code'])) {
                $merge_fields['new_code']['data'] = $vars['new_code'];
            }

            // Thêm các biến khác của data truyền vào nếu có
            foreach ($vars as $key => $value) {
                if (!isset($merge_fields[$key])) {
                    $merge_fields[$key] = [
                        'name' => $key,
                        'data' => $value
                    ];
                }
            }
        }

        $nv_Lang->changeLang();
    }

    return $merge_fields;
};
nv_add_hook($module_name, 'get_email_merge_fields', $priority, $callback, $hook_module, $pid);
