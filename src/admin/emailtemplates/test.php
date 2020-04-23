<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 24-06-2011 10:35
 */

if (!defined('NV_IS_FILE_EMAILTEMPLATES')) {
    die('Stop!!!');
}

$emailid = $nv_Request->get_absint('emailid', 'post,get', 0);

$sql = 'SELECT * FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' WHERE emailid = ' . $emailid;
$result = $db->query($sql);
$array = $result->fetch();
if (empty($array)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$array['title'] = $array[NV_LANG_DATA . '_title'];
$array['lang_subject'] = $array[NV_LANG_DATA . '_subject'];
$array['lang_content'] = $array[NV_LANG_DATA . '_content'];
$array['pids'] = array_filter(array_unique(array_merge_recursive(explode(',', $array['sys_pids']), explode(',', $array['pids']))));
$array['test_tomail'] = [$admin_info['email']];

// Hook xử lý biến $array khi lấy từ CSDL ra
$array = nv_apply_hook('', 'emailtemplates_content_from_db', [$array], $array);

$merge_fields = $field_data = [];
if (!empty($array['pids'])) {
    $args = [
        'mode' => 'PRE',
        'setpids' => $array['pids']
    ];
    $merge_fields = nv_apply_hook('', 'get_email_merge_fields', $args, [], 1);
}

$page_title = $nv_Lang->getModule('test');
$error = [];
$success = false;

if ($nv_Request->get_title('tokend', 'post', '') === NV_CHECK_SESSION) {
    // Lấy các email nhận
    $test_tomail = nv_nl2br($nv_Request->get_string('test_tomail', 'post', ''), '|');
    $test_tomail = array_unique(array_filter(array_map('trim', explode('|', $test_tomail))));
    $array['test_tomail'] = [];
    foreach ($test_tomail as $email) {
        $email_check = nv_check_valid_email($email, true);
        if (!empty($email_check[0])) {
            $error[] = $email_check[0] . ': ' . nv_htmlspecialchars($email);
        } else {
            $array['test_tomail'][] = $email_check[1];
        }
    }
    if (empty($array['test_tomail'])) {
        $error[] = $nv_Lang->getModule('test_error_tomail');
    }

    foreach ($merge_fields as $fieldname => $field) {
        $field_data[$fieldname] = $nv_Request->get_title('f_' . $fieldname, 'post', '');
    }

    $email_data = nv_get_email_template($emailid);
    if ($email_data === false) {
        $error[] = $nv_Lang->getModule('test_error_template');
    }

    if (empty($error)) {
        if (empty($email_data['from'][0])) {
            $email_data['from'][0] = $global_config['site_name'];
        }
        if (empty($email_data['from'][1])) {
            $email_data['from'][1] = $global_config['site_email'];
        }

        // Hook xử lý biến $email_data trước khi build ra HTML
        $email_data = nv_apply_hook('', 'get_email_data_before_fetch_test', [$emailid, $email_data, $merge_fields, $field_data], $email_data);

        $tpl_string = new \NukeViet\Template\Smarty();
        foreach ($merge_fields as $field_key => $field_value) {
            $tpl_string->assign($field_key, $field_data[$field_key]);
        }

        $email_content = $tpl_string->fetch('string:' . $email_data['content']);
        $email_subject = $tpl_string->fetch('string:' . $email_data['subject']);
        if ($email_data['is_plaintext']) {
            $email_content = nv_nl2br(strip_tags($email_content));
        } else {
            $email_content = preg_replace('/(["|\'])[\s]*' . nv_preg_quote(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/') . '/isu', '\\1' . NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/', $email_content);
        }

        // Gọi 1 hook trước khi gửi email test
        nv_apply_hook('', 'event_before_sending_test_mail', [$emailid, $email_data, $merge_fields, $field_data]);

        $check_send = nv_sendmail($email_data['from'], $array['test_tomail'], $email_subject, $email_content, implode(',', $email_data['attachments']), false, $email_data['cc'], $email_data['bcc'], true);
        if (!empty($check_send)) {
            $error[] = $check_send;
        } else {
            $success = true;
        }

        unset($tpl_string);
    }
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->registerPlugin('modifier', 'implode', 'implode');
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);

$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;emailid=' . $emailid);
$tpl->assign('DATA', $array);
$tpl->assign('ERROR', $error);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('CATS', $global_array_cat);
$tpl->assign('TOKEND', NV_CHECK_SESSION);
$tpl->assign('MERGE_FIELDS', $merge_fields);
$tpl->assign('FIELD_DATA', $field_data);
$tpl->assign('SUCCESS', $success);

$contents = $tpl->fetch('test.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
