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

$emailid = $nv_Request->get_int('emailid', 'post,get', 0);
$error = '';

if (!empty($emailid)) {
    $sql = 'SELECT * FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' WHERE emailid = ' . $emailid;
    $result = $db->query($sql);
    $array = $result->fetch();

    if (empty($array)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    $array['title'] = $array[NV_LANG_DATA . '_title'];
    $array['lang_subject'] = $array[NV_LANG_DATA . '_subject'];
    $array['lang_content'] = $array[NV_LANG_DATA . '_content'];

    $array['default_content'] = nv_editor_br2nl($array['default_content']);
    $array['lang_content'] = nv_editor_br2nl($array[NV_LANG_DATA . '_content']);
    $array['send_cc'] = explode(',', $array['send_cc']);
    $array['send_bcc'] = explode(',', $array['send_bcc']);
    $array['attachments'] = explode(',', $array['attachments']);

    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;emailid=' . $emailid;
    $page_title = $nv_Lang->getModule('edit_template');
} else {
    $array = [
        'emailid' => 0,
        'catid' => 0,
        'send_name' => '',
        'send_email' => '',
        'send_cc' => [],
        'send_bcc' => [],
        'attachments' => [],
        'is_plaintext' => 0,
        'is_disabled' => 0,
        'title' => '',
        'default_subject' => '',
        'default_content' => '',
        'lang_subject' => '',
        'lang_content' => ''
    ];
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
    $page_title = $nv_Lang->getModule('add_template');
}

if ($nv_Request->isset_request('submit', 'post')) {
    $array['catid'] = $nv_Request->get_int('catid', 'post', 0);
    $array['send_name'] = $nv_Request->get_title('send_name', 'post', '');
    $array['send_email'] = $nv_Request->get_title('send_email', 'post', '');
    $array['send_cc'] = $nv_Request->get_title('send_cc', 'post', '');
    $array['send_bcc'] = $nv_Request->get_title('send_bcc', 'post', '');
    $array['is_plaintext'] = intval($nv_Request->get_bool('is_plaintext', 'post', false));
    $array['is_disabled'] = intval($nv_Request->get_bool('is_disabled', 'post', false));
    $array['attachments'] = $nv_Request->get_typed_array('attachments', 'post', 'string', []);
    $array['title'] = $nv_Request->get_title('title', 'post', '');
    $array['default_subject'] = $nv_Request->get_title('default_subject', 'post', '');
    $array['default_content'] = $nv_Request->get_editor('default_content', '', NV_ALLOWED_HTML_TAGS);
    $array['lang_subject'] = $nv_Request->get_title('lang_subject', 'post', '');
    $array['lang_content'] = $nv_Request->get_editor('lang_content', '', NV_ALLOWED_HTML_TAGS);

    if (!empty($array['catid']) and !isset($global_array_cat[$array['catid']])) {
        $array['catid'] = 0;
    }
    $array['send_cc'] = array_unique(array_filter(array_map('trim', explode(',', $array['send_cc']))));
    $array['send_bcc'] = array_unique(array_filter(array_map('trim', explode(',', $array['send_bcc']))));
    $error_cc = $error_bcc = '';
    foreach ($array['send_cc'] as $email) {
        $check = nv_check_valid_email($email);
        if ($check != '') {
            $error_cc = $check;
            break;
        }
    }
    foreach ($array['send_bcc'] as $email) {
        $check = nv_check_valid_email($email);
        if ($check != '') {
            $error_bcc = $check;
            break;
        }
    }
    // Xác định lại các file đính kèm
    $attachments = [];
    foreach ($array['attachments'] as $_attachment) {
        if (nv_is_file($_attachment, NV_UPLOADS_DIR . '/' . $module_upload)) {
            $attachments[] = substr($_attachment, strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
        }
    }
    $attachments = array_unique($attachments);
    $array['attachments'] = $attachments;

    if (!empty($array['send_email']) and ($check = nv_check_valid_email($array['send_email'])) != '') {
        $error = $check;
    } elseif (!empty($error_cc)) {
        $error = $error_cc;
    } elseif (!empty($error_bcc)) {
        $error = $error_bcc;
    } elseif (empty($array['title'])) {
        $error = $nv_Lang->getModule('tpl_error_title');
    } elseif (empty($array['default_subject'])) {
        $error = $nv_Lang->getModule('tpl_error_default_subject');
    }elseif (empty($array['default_content'])) {
        $error = $nv_Lang->getModule('tpl_error_default_content');
    } else {
        $sql = 'SELECT * FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' WHERE ' . NV_LANG_DATA . '_title = :title' . ($array['emailid'] ? ' AND emailid != ' . $array['emailid'] : '');
        $sth = $db->prepare($sql);
        $sth->bindParam(':title', $array['title'], PDO::PARAM_STR);
        $sth->execute();
        $num = $sth->fetchColumn();

        if (!empty($num)) {
            $error = $nv_Lang->getModule('tpl_error_exists');
        } else {
            if (!$array['emailid']) {
                $sql = 'INSERT INTO ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' (
                    catid, time_add, send_name, send_email, send_cc, send_bcc, attachments, is_system, is_plaintext, is_disabled, default_subject, default_content,
                    ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_subject, ' . NV_LANG_DATA . '_content
                ) VALUES (
                    ' . $array['catid'] . ', ' . NV_CURRENTTIME . ', :send_name, :send_email, :send_cc, :send_bcc, :attachments, 0, ' . $array['is_plaintext'] . ', ' . $array['is_disabled'] . ',
                    :default_subject, :default_content, :lang_title, :lang_subject, :lang_content
                )';
            } else {
                $sql = 'UPDATE ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' SET
                    catid = ' . $array['catid'] . ',
                    time_update = ' . NV_CURRENTTIME . ',
                    send_name = :send_name,
                    send_email = :send_email,
                    send_cc = :send_cc,
                    send_bcc = :send_bcc,
                    attachments = :attachments,
                    is_plaintext = ' . $array['is_plaintext'] . ',
                    is_disabled = ' . $array['is_disabled'] . ',
                    default_subject = :default_subject,
                    default_content = :default_content,
                    ' . NV_LANG_DATA . '_title = :lang_title,
                    ' . NV_LANG_DATA . '_subject = :lang_subject,
                    ' . NV_LANG_DATA . '_content = :lang_content
                WHERE emailid = ' . $array['emailid'];
            }

            $send_cc = implode(',', $array['send_cc']);
            $send_bcc = implode(',', $array['send_bcc']);
            $attachments = implode(',', $array['attachments']);
            $default_content = nv_editor_nl2br($array['default_content']);
            $lang_content = nv_editor_nl2br($array['lang_content']);

            try {
                $sth = $db->prepare($sql);
                $sth->bindParam(':send_name', $array['send_name'], PDO::PARAM_STR);
                $sth->bindParam(':send_email', $array['send_email'], PDO::PARAM_STR);
                $sth->bindParam(':send_cc', $send_cc, PDO::PARAM_STR, strlen($send_cc));
                $sth->bindParam(':send_bcc', $send_bcc, PDO::PARAM_STR, strlen($send_bcc));
                $sth->bindParam(':attachments', $attachments, PDO::PARAM_STR, strlen($attachments));
                $sth->bindParam(':default_subject', $array['default_subject'], PDO::PARAM_STR);
                $sth->bindParam(':default_content', $default_content, PDO::PARAM_STR, strlen($default_content));
                $sth->bindParam(':lang_title', $array['title'], PDO::PARAM_STR);
                $sth->bindParam(':lang_subject', $array['lang_subject'], PDO::PARAM_STR);
                $sth->bindParam(':lang_content', $lang_content, PDO::PARAM_STR, strlen($lang_content));
                $sth->execute();

                if ($sth->rowCount()) {
                    if ($array['emailid']) {
                        nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit Email Template', 'ID: ' . $array['emailid'], $admin_info['userid']);
                    } else {
                        nv_insert_logs(NV_LANG_DATA, $module_name, 'Add Email Template', ' ', $admin_info['userid']);
                    }

                    $nv_Cache->delMod($module_name);
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
                } else {
                    $error = $nv_Lang->getModule('errorsave');
                }
            } catch (PDOException $e) {
                $error = $nv_Lang->getModule('errorsave');
            }
        }
    }
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

if (!empty($array['default_content'])) {
    $array['default_content'] = nv_htmlspecialchars($array['default_content']);
}
if (!empty($array['lang_content'])) {
    $array['lang_content'] = nv_htmlspecialchars($array['lang_content']);
}

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $array['default_content'] = nv_aleditor('default_content', '100%', '300px', $array['default_content'], '', NV_UPLOADS_DIR . '/' . $module_upload, NV_UPLOADS_DIR . '/' . $module_upload);
    $array['lang_content'] = nv_aleditor('lang_content', '100%', '300px', $array['lang_content'], '', NV_UPLOADS_DIR . '/' . $module_upload, NV_UPLOADS_DIR . '/' . $module_upload);
} else {
    $array['default_content'] = '<textarea class="form-control" style="width:100%;height:500px" name="default_content">' . $array['default_content'] . '</textarea>';
    $array['lang_content'] = '<textarea class="form-control" style="width:100%;height:500px" name="lang_content">' . $array['lang_content'] . '</textarea>';
}
$array['send_cc'] = implode(', ', $array['send_cc']);
$array['send_bcc'] = implode(', ', $array['send_bcc']);
if (empty($array['attachments'])) {
    $array['attachments'][] = '';
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('FORM_ACTION', $form_action);
$tpl->assign('DATA', $array);
$tpl->assign('ERROR', $error);
$tpl->assign('LANG_NAME', $language_array[NV_LANG_DATA]['name']);
$tpl->assign('CATS', $global_array_cat);
$tpl->assign('UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_upload);

$contents = $tpl->fetch('contents.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
