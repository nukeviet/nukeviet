<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
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
    $post = [
        'mail_lang' => $nv_Request->get_title('mail_lang', 'post', ''),
        'title' => $nv_Request->get_title('title', 'post', ''),
        'email' => $nv_Request->get_title('email', 'post', ''),
        'mess_content' => $nv_Request->get_editor('mess_content', '', NV_ALLOWED_HTML_TAGS)
    ];

    if (nv_strlen($post['title']) < 3) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['error_title']
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
            'mess' => $lang_module['error_mail_empty']
        ]);
    }

    $test_content = strip_tags($post['mess_content']);
    if (empty($test_content)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['no_content_send_title']
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

    foreach ($post['email'] as $part => $emails) {
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

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $lang_module['send_suc_send_title'] . ' ' . $lang_module['send_new_mail']
    ]);
}

$sign_content = '';
require_once NV_ROOTDIR . '/modules/contact/sign.php';
$mess_content = htmlspecialchars(nv_editor_br2nl($sign_content));

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $mess_content = nv_aleditor('mess_content', '100%', '300px', $mess_content, 'Basic');
} else {
    $mess_content = '<textarea style="width:99%" name="mess_content" id="mess_content" cols="20" rows="8">' . $mess_content . '</textarea>';
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('MESS_CONTENT', $mess_content);

if (sizeof($global_config['setup_langs']) > 1) {
    foreach ($global_config['setup_langs'] as $langkey) {
        $xtpl->assign('MAIL_LANG', [
            'key' => $langkey,
            'sel' => $langkey == NV_LANG_DATA ? ' selected="selected"' : '',
            'name' => $language_array[$langkey]['name']
        ]);
        $xtpl->parse('main.mail_lang.loop');
    }
    $xtpl->parse('main.mail_lang');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $module_info['site_title'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
