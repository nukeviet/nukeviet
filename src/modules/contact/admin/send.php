<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

if ($nv_Request->isset_request('save', 'post')) {
    $checkss = $nv_Request->get_title('checkss', 'post', '');
    if (!hash_equals(NV_CHECK_SESSION, $checkss)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_invalid_request')
        ]);
    }

    $post = [
        'mail_lang' => $nv_Request->get_title('mail_lang', 'post', ''),
        'title' => $nv_Request->get_title('title', 'post', ''),
        'email' => $nv_Request->get_title('email', 'post', ''),
        'mess_content' => $nv_Request->get_editor('mess_content', '', NV_ALLOWED_HTML_TAGS)
    ];

    if (nv_strlen($post['title']) < 3) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('admin_error_title'),
            'input' => 'title'
        ]);
    }

    if (!empty($post['email'])) {
        $_arr_mail = array_map('trim', explode(';', $post['email']));
        $post['email'] = [];
        foreach ($_arr_mail as $_emails) {
            $_emails = array_map('trim', explode(',', $_emails));
            $ems = [];
            foreach ($_emails as $_em) {
                if (nv_check_valid_email($_em) == '') {
                    $ems[] = $_em;
                }
            }
            if (!empty($ems)) {
                $post['email'][] = $ems;
            }
        }
    }
    if (empty($post['email'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_mail_empty'),
            'input' => 'email'
        ]);
    }

    $test_content = strip_tags($post['mess_content']);
    if (empty($test_content)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('no_content_send_title')
        ]);
    }

    $a = 0;
    $s = false;
    $maillang = '';
    if (!empty($post['mail_lang']) and in_array($post['mail_lang'], $global_config['setup_langs'], true)) {
        if ($post['mail_lang'] != NV_LANG_INTERFACE) {
            $maillang = $post['mail_lang'];
        }
    } elseif (NV_LANG_DATA != NV_LANG_INTERFACE) {
        $maillang = NV_LANG_DATA;
    }

    foreach ((array) $post['email'] as $emails) {
        if ($s) {
            sleep(2);
        }
        nv_sendmail_async([
            $admin_info['full_name'],
            $admin_info['email']
        ], $emails, $post['title'], $post['mess_content'], '', false, false, [], [], true, [], $maillang);
        $s = true;
        ++$a;
        if ($a == 3) {
            break;
        }
    }

    // Ghi log gửi thư
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('bt_send_row_title'), $post['title'], $admin_info['userid']);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getModule('send_suc_send_title'),
        'refresh' => true
    ]);
}

// Lấy nội dung chữ ký mặc định
$sign_content = '';
require_once NV_ROOTDIR . '/modules/contact/sign.php';
$mess_content_default = htmlspecialchars(nv_editor_br2nl($sign_content));

// Khởi tạo editor
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $mess_content = nv_aleditor('mess_content', '100%', '300px', $mess_content_default, 'Basic');
} else {
    $mess_content = '<textarea name="mess_content" id="mess_content" cols="20" rows="8" class="form-control">' . $mess_content_default . '</textarea>';
}

// Chuẩn bị dữ liệu cho Smarty
$mail_langs = [];
if (count($global_config['setup_langs']) > 1) {
    foreach ($global_config['setup_langs'] as $langkey) {
        $mail_langs[] = [
            'key' => $langkey,
            'name' => $language_array[$langkey]['name']
        ];
    }
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('send.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('OP', $op);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('MESS_CONTENT', $mess_content);
$tpl->assign('MAIL_LANGS', $mail_langs);

$contents = $tpl->fetch('send.tpl');

$page_title = $module_info['site_title'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
