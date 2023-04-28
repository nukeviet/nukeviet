<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_CONTACT')) {
    exit('Stop!!!');
}

// Danh sach cac bo phan
list($departments, $departments_by_alias) = get_department_list();
$is_specific = false; // Xem bộ phận cụ thể
$categories = []; // Danh sách các chủ đề của bộ phận
$default = 0; // ID của bộ phận mặc định
$page_keywords = []; // Từ khóa
$current_departments = []; // Các bộ phận đc hiển thị
if (!empty($departments)) {
    if (!empty($array_op[0]) and isset($departments_by_alias[$array_op[0]])) {
        $is_specific = true;
        $default = $departments_by_alias[$array_op[0]];
        $current_departments = [
            $default => $departments[$default]
        ];
        $categories = [$default => $departments[$default]['cats']];
        $page_keywords = [$departments[$default]['full_name']];
    } else {
        foreach ($departments as $k => $department) {
            if (!empty($department['cats'])) {
                $categories[$k] = $department['cats'];
            }
            if ($department['is_default']) {
                $default = $department['id'];
            }
            $page_keywords[] = $department['full_name'];
        }
        $current_departments = $departments;
    }
}

if (empty($default) and !empty($departments)) {
    $default = array_key_first($departments);
}

$feedback = [
    'sender_name' => '',
    'sender_email' => '',
    'sender_phone' => '',
    'sender_phone_required' => (int) $module_config[$module_name]['feedback_phone'] === 1 ? true : false,
    'sender_address' => '',
    'sender_address_required' => (int) $module_config[$module_name]['feedback_address'] === 1 ? true : false,
    'sendcopy' => true
];

if (!defined('NV_IS_MODADMIN') and empty($module_config[$module_name]['sendcopymode']) and (!defined('NV_IS_USER') or $user_info['email_verification_time'] == 0 or $user_info['email_verification_time'] == -1)) {
    $feedback['sendcopy'] = false;
}

if (defined('NV_IS_USER')) {
    $feedback['sender_name'] = !empty($user_info['full_name']) ? $user_info['full_name'] : $user_info['username'];
    $feedback['sender_email'] = $user_info['email'];
    $feedback['sender_phone'] = isset($user_info['phone']) ? $user_info['phone'] : '';
    $feedback['sender_address'] = isset($user_info['address']) ? $user_info['address'] : '';
}

// Nhận phản hồi
if ($nv_Request->isset_request('checkss', 'post')) {
    $checkss = $nv_Request->get_title('checkss', 'post', '');
    if ($checkss != NV_CHECK_SESSION) {
        exit();
    }

    /*
     * Ajax
     */
    if ($nv_Request->isset_request('loadForm', 'post')) {
        $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

        $form = contact_form_theme([
            'fname' => $feedback['sender_name'],
            'femail' => $feedback['sender_email'],
            'fphone' => $feedback['sender_phone'],
            'sender_phone_required' => $feedback['sender_phone_required'],
            'faddress' => $feedback['sender_address'],
            'sender_address_required' => $feedback['sender_address_required'],
            'sendcopy' => $feedback['sendcopy'],
            'bodytext' => ''
        ], $current_departments, $categories, $base_url, NV_CHECK_SESSION);

        nv_htmlOutput($form);
    }

    unset($fcaptcha);
    // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
    if ($module_captcha == 'recaptcha') {
        $fcaptcha = $nv_Request->get_title('g-recaptcha-response', 'post', '');
    }
    // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
    elseif ($module_captcha == 'captcha') {
        $fcaptcha = $nv_Request->get_title('fcode', 'post', '');
    }

    // Kiểm tra tính hợp lệ của captcha nhập vào, nếu không hợp lệ => thông báo lỗi
    if (isset($fcaptcha) and !nv_capcha_txt($fcaptcha, $module_captcha)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => '',
            'mess' => ($module_captcha == 'recaptcha') ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect']
        ]);
    }

    $feedback['title'] = nv_substr($nv_Request->get_title('ftitle', 'post', '', 1), 0, 255);
    if (empty($feedback['title'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'ftitle',
            'mess' => $lang_module['error_title']
        ]);
    }

    if (!defined('NV_IS_USER')) {
        $feedback['sender_name'] = nv_substr($nv_Request->get_title('fname', 'post', ''), 0, 100);
        $feedback['sender_email'] = nv_substr($nv_Request->get_title('femail', 'post', '', 1), 0, 100);
    }

    if (empty($feedback['sender_name']) or !preg_match('/^([\p{L}\p{Mn}\p{Pd}\'][\p{L}\p{Mn}\p{Pd}\',\s]*)*$/u', str_replace('&#039;', "'", $feedback['sender_name']))) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'fname',
            'mess' => $lang_module['error_fullname']
        ]);
    }

    $check_valid_email = nv_check_valid_email($feedback['sender_email'], true);
    $feedback['sender_email'] = $check_valid_email[1];
    if ($check_valid_email[0] != '') {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'femail',
            'mess' => $check_valid_email[0]
        ]);
    }

    $feedback['sender_phone'] = nv_substr($nv_Request->get_title('fphone', 'post', '', 1), 0, 100);
    if ($feedback['sender_phone_required'] and empty($feedback['sender_phone'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'fphone',
            'mess' => $lang_module['phone_error']
        ]);
    }

    $feedback['sender_address'] = nv_substr($nv_Request->get_title('faddress', 'post', '', 1), 0, 100);
    if ($feedback['sender_address_required'] and empty($feedback['sender_address'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'fphone',
            'mess' => $lang_module['address_error']
        ]);
    }

    $feedback['content'] = $nv_Request->get_editor('fcon', '', NV_ALLOWED_HTML_TAGS);
    if ((trim(strip_tags($feedback['content']))) == '') {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'fcon',
            'mess' => $lang_module['error_content']
        ]);
    }

    $data_permission_confirm = !empty($global_config['data_warning']) ? (int) $nv_Request->get_bool('data_permission_confirm', 'post', false) : -1;
    $antispam_confirm = !empty($global_config['antispam_warning']) ? (int) $nv_Request->get_bool('antispam_confirm', 'post', false) : -1;
    if ($data_permission_confirm === 0) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'data_permission_confirm',
            'mess' => $lang_global['data_warning_error']
        ]);
    }

    if ($antispam_confirm === 0) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'antispam_confirm',
            'mess' => $lang_global['antispam_warning_error']
        ]);
    }

    $feedback['department'] = $default;
    $feedback['category'] = '';
    $_fcat = $nv_Request->get_title('fcat', 'post', '');
    unset($m);
    if (preg_match('/^([0-9]+)\_([0-9]+|other)$/', $_fcat, $m)) {
        if (isset($departments[$m[1]])) {
            $feedback['department'] = $m[1];
            if (isset($departments[$m[1]]['cats'][$_fcat])) {
                $feedback['category'] = $departments[$m[1]]['cats'][$_fcat];
            }
        }
    }

    $feedback['content'] = nv_nl2br($feedback['content']);
    $fsendcopy = ($nv_Request->get_bool('sendcopy', 'post') and $feedback['sendcopy']);
    $feedback['sender_id'] = (int) (defined('NV_IS_USER') ? $user_info['userid'] : 0);

    $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_send
    (cid, cat, title, content, send_time, sender_id, sender_name, sender_email, sender_phone, sender_address, sender_ip, is_read, is_reply) VALUES
    (' . $feedback['department'] . ', :cat, :title, :content, ' . NV_CURRENTTIME . ', ' . $feedback['sender_id'] . ', :sender_name, :sender_email, :sender_phone, :sender_address, :sender_ip, 0, 0)';
    $data_insert = [];
    $data_insert['cat'] = $feedback['category'];
    $data_insert['title'] = $feedback['title'];
    $data_insert['content'] = $feedback['content'];
    $data_insert['sender_name'] = $feedback['sender_name'];
    $data_insert['sender_email'] = $feedback['sender_email'];
    $data_insert['sender_phone'] = $feedback['sender_phone'];
    $data_insert['sender_address'] = $feedback['sender_address'];
    $data_insert['sender_ip'] = $client_info['ip'];
    $feedback['id'] = $db->insert_id($sql, 'id', $data_insert);
    if ($feedback['id'] > 0) {
        $custom_headers = [
            'References' => md5('contact' . $feedback['id'] . $global_config['sitekey'])
        ];
        $feedback['filter_title'] = nv_autoLinkDisable($feedback['title']);
        $feedback['filter_content'] = nv_autoLinkDisable($feedback['content']);
        $feedback['filter_sender_phone'] = nv_autoLinkDisable($feedback['sender_phone']);

        $auto_forward = [];
        if (empty($module_config[$module_name]['silent_mode'])) {
            $mail_content = contact_sendcontact($feedback, $departments);

            $email_list = [];
            if (!empty($departments[$feedback['department']]['email'])) {
                $email_list[] = $departments[$feedback['department']]['email'][0];
                $auto_forward[] = $departments[$feedback['department']]['email'][0];
            }

            if (!empty($departments[$feedback['department']]['admins']['obt_level'])) {
                $sql = 'SELECT t1.admin_id, t2.email as admin_email FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.admin_id = t2.userid WHERE t1.lev!=0 AND t1.is_suspend=0 AND t2.active=1 AND t1.admin_id IN (' . implode(',', $departments[$feedback['department']]['admins']['obt_level']) . ')';
                $result = $db_slave->query($sql);
                while ($row = $result->fetch()) {
                    if (nv_check_valid_email($row['admin_email']) == '') {
                        $email_list[] = $row['admin_email'];
                        $auto_forward[] = $row['admin_id'];
                    }
                }
            }

            if (!empty($email_list)) {
                $email_list = array_unique($email_list);
                foreach ($email_list as $to) {
                    $from = [
                        $feedback['sender_name'],
                        $feedback['sender_email']
                    ];
                    nv_sendmail_async($from, $to, $feedback['title'], $mail_content, '', false, false, [], [], true, $custom_headers);
                }

                $auto_forward = array_unique($auto_forward);
                $auto_forward = implode(',', $auto_forward);
                $db->query('UPDATE ' . NV_MOD_TABLE . '_send SET auto_forward=' . $db->quote($auto_forward) . ' WHERE id=' . $feedback['id']);
            }
        }

        // Gửi bản sao đến hộp thư người gửi
        if ($fsendcopy) {
            $from = [
                $global_config['site_name'],
                $global_config['site_email']
            ];
            $mail_content = contact_sendcontact($feedback, $departments, false);
            nv_sendmail_async($from, $feedback['sender_email'], $feedback['title'], $mail_content, '', false, false, [], [], true, $custom_headers);
        }

        nv_insert_notification($module_name, 'contact_new', [
            'title' => $feedback['title']
        ], $feedback['id'], 0, $feedback['sender_id'], 1);

        nv_jsonOutput([
            'status' => 'success',
            'input' => '',
            'mess' => $lang_module['sendcontactok']
        ]);
    }

    nv_jsonOutput([
        'status' => 'error',
        'input' => '',
        'mess' => $lang_module['sendcontactfailed']
    ]);
}

$page_title = $module_info['site_title'];
array_unshift($page_keywords, $module_info['keywords'], $module_info['site_title']);
$key_words = implode(', ', array_filter($page_keywords));
$description = $module_config[$module_name]['bodytext'];

$array_content = [
    'fname' => $feedback['sender_name'],
    'femail' => $feedback['sender_email'],
    'fphone' => $feedback['sender_phone'],
    'sender_phone_required' => $feedback['sender_phone_required'],
    'faddress' => $feedback['sender_address'],
    'sender_address_required' => $feedback['sender_address_required'],
    'sendcopy' => $feedback['sendcopy'],
    'bodytext' => $module_config[$module_name]['bodytext']
];

$supporters = get_supporter_list($departments);

$full_theme = true;
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
if ($is_specific) {
    $page_url .= '&amp;' . NV_OP_VARIABLE . '=' . $array_op[0];
    if (isset($array_op[1]) and $array_op[1] === '0') {
        $page_url .= '/0';
        $full_theme = false;
    }

    $page_title = $departments[$default]['full_name'];
    if (!empty($departments[$default]['note'])) {
        $description = $departments[$default]['note'];
    }

    // Them vao tieu de
    $array_mod_title[] = [
        'catid' => 0,
        'title' => $page_title,
        'link' => $page_url
    ];

    $array_content['bodytext'] = $departments[$default]['note'];
    $current_departments[$default]['note'] = '';
    $supporters = !empty($supporters[$default]) ? $supporters[$default] : '';
} else {
    $supporters = !empty($supporters[0]) ? $supporters[0] : '';
}

$canonicalUrl = getCanonicalUrl($page_url, true, true);

$contents = contact_main_theme($array_content, $is_specific, $current_departments, $categories, $supporters, $page_url, NV_CHECK_SESSION);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents, $full_theme);
include NV_ROOTDIR . '/includes/footer.php';
