<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$post = [];
$post['title'] = $nv_Request->get_title('title', 'post');
$post['email'] = $nv_Request->get_title('email', 'post');

$mess_content = $error = '';

if ($nv_Request->get_int('save', 'post') == '1') {
    $mess_content = $nv_Request->get_editor('mess_content', '', NV_ALLOWED_HTML_TAGS);

    if (empty($post['email'])) {
        $error = $lang_module['error_mail_empty'];
    } elseif (strip_tags($mess_content) == '') {
        $error = $lang_module['no_content_send_title'];
    } elseif (empty($post['title'])) {
        $error = $lang_module['error_title'];
    } else {
        $mail = new NukeViet\Core\Sendmail($global_config, NV_LANG_INTERFACE);
        $mail->setSubject($post['title']);

        $_arr_mail = explode(',', $post['email']);
        foreach ($_arr_mail as $_email) {
            $_email = nv_unhtmlspecialchars($_email);
            if (nv_check_valid_email($_email) == '') {
                $mail->addTo($_email);
            }
        }

        $mail->setContent($mess_content);
        if ($mail->Send()) {
            $error = $lang_module['send_suc_send_title'];
        } else {
            $error = $lang_global['error_sendmail_admin'] . ': ' . $mail->ErrorInfo;
        }
    }
} else {
    require_once NV_ROOTDIR . '/modules/contact/sign.php';
    $mess_content .= $sign_content;
}

$mess_content = htmlspecialchars(nv_editor_br2nl($mess_content));

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $mess_content = nv_aleditor('mess_content', '100%', '300px', $mess_content);
} else {
    $mess_content = '<textarea style="width:99%" name="mess_content" id="mess_content" cols="20" rows="8">' . $mess_content . '</textarea>';
}

$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('MESS_CONTENT', $mess_content);
$xtpl->assign('POST', $post);

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $module_info['site_title'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
