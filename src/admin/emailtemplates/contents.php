<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_EMAILTEMPLATES')) {
    exit('Stop!!!');
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'implode', 'implode');
$tpl->registerPlugin('modifier', 'sizeof', 'sizeof');
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);

// Lấy các plugin xử lý dữ liệu
$sql = 'SELECT pid, plugin_file, plugin_module_name FROM ' . $db_config['prefix'] . "_plugins WHERE plugin_area='get_email_merge_fields' AND hook_module='' ORDER BY weight ASC";
$array_mplugins = [];
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $array_mplugins[$row['pid']] = $row;
}

// Đọc mẫu email
$array_mailtpl = [];
$themes = nv_scandir(NV_ROOTDIR . '/themes', [$global_config['check_theme'], $global_config['check_theme_mobile'], $global_config['check_theme_admin']]);

foreach ($themes as $theme) {
    $lists = nv_scandir(NV_ROOTDIR . '/themes/' . $theme . '/system/', '/^mail(\-|\_)*(.*)\.tpl$/');
    foreach ($lists as $mailtpl) {
        $array_mailtpl[] = $theme . ':' . $mailtpl;
    }
}
$lists = nv_scandir(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/tpl/', '/^mail(\-|\_)*(.*)\.tpl$/');
foreach ($lists as $mailtpl) {
    $array_mailtpl[] = 'assets:' . $mailtpl;
}

// List các merge fields
if ($nv_Request->isset_request('getMergeFields', 'post')) {
    $pids = $nv_Request->get_typed_array('pids', 'post', 'int', []);
    $pids = array_intersect($pids, array_keys($array_mplugins));

    $args = [
        'mode' => 'PRE',
        'setpids' => $pids
    ];
    $merge_fields = nv_apply_hook('', 'get_email_merge_fields', $args, [], 1);

    // Các field của hệ thống luôn khả dụng với mọi trình xử lý
    $merge_fields['site_name'] = ['name' => $nv_Lang->getGlobal('site_name')];
    $merge_fields['site_email'] = ['name' => $nv_Lang->getGlobal('site_email')];
    $merge_fields['site_phone'] = ['name' => $nv_Lang->getGlobal('site_phone')];
    $merge_fields['NV_BASE_SITEURL'] = ['name' => $nv_Lang->getGlobal('merge_field_sys_siteurl')];
    $merge_fields['NV_NAME_VARIABLE'] = ['name' => $nv_Lang->getGlobal('merge_field_sys_nv')];
    $merge_fields['NV_OP_VARIABLE'] = ['name' => $nv_Lang->getGlobal('merge_field_sys_op')];
    $merge_fields['NV_LANG_VARIABLE'] = ['name' => $nv_Lang->getGlobal('merge_field_sys_langvar')];
    $merge_fields['NV_LANG_DATA'] = ['name' => $nv_Lang->getGlobal('merge_field_sys_langdata')];
    $merge_fields['NV_LANG_INTERFACE'] = ['name' => $nv_Lang->getGlobal('merge_field_sys_langinterface')];
    $merge_fields['NV_ASSETS_DIR'] = ['name' => $nv_Lang->getGlobal('merge_field_sys_assetsdir')];
    $merge_fields['NV_FILES_DIR'] = ['name' => $nv_Lang->getGlobal('merge_field_sys_filesdir')];

    $tpl->assign('FIELDS', $merge_fields);

    $contents = $tpl->fetch('contents_fields.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

$emailid = $nv_Request->get_absint('emailid', 'post,get', 0);
$copyid = $nv_Request->get_absint('copyid', 'post,get', 0);
$error = [];

if ($emailid or $copyid) {
    $sql = 'SELECT * FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' WHERE emailid = ' . ($emailid ? $emailid : $copyid);
    $result = $db->query($sql);
    $array = $result->fetch();

    if (empty($array)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    $array['default_content'] = nv_editor_br2nl($array['default_content']);
    $array['title'] = $array['subject'] = $array['content'] = [];
    foreach ($global_config['setup_langs'] as $lang) {
        $array['title'][$lang] = $array[$lang . '_title'];
        $array['subject'][$lang] = $array[$lang . '_subject'];
        $array['content'][$lang] = nv_editor_br2nl($array[$lang . '_content']);
    }

    $array['send_cc'] = $array['send_cc'] ? explode(',', $array['send_cc']) : [];
    $array['send_bcc'] = $array['send_bcc'] ? explode(',', $array['send_bcc']) : [];
    $array['attachments'] = $array['attachments'] ? explode(',', $array['attachments']) : [];
    $array['pids'] = $array['pids'] ? explode(',', $array['pids']) : [];
    $array['sys_pids'] = $array['sys_pids'] ? explode(',', $array['sys_pids']) : [];

    // Hook xử lý biến $array khi lấy từ CSDL ra
    $array = nv_apply_hook('', 'emailtemplates_content_from_db', [$array], $array);
}

$update_for = [];

if ($emailid) {
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;emailid=' . $emailid;
    $page_title = $nv_Lang->getModule('edit_template');

    // Tìm thông tin khôi phục lại nguyên bản như email gốc
    $array['allow_rollback'] = 0;
    $array['data_rollback'] = [
        'pids' => [],
        'default_subject' => '',
        'default_content' => '',
        'langs' => []
    ];
    if (!empty($array['module_file'])) {
        foreach ($global_config['setup_langs'] as $lang) {
            $module_emails = [];
            $file = NV_ROOTDIR . '/modules/' . $array['module_file'] . '/language/email_' . $lang . '.php';
            if (file_exists($file)) {
                $module_name_backup = $module_name;
                $module_name = $array['module_name'];
                include $file;
                $module_name = $module_name_backup;
            }
            if (!empty($module_emails) and isset($module_emails[$array['id']], $module_emails[$array['id']]['t'], $module_emails[$array['id']]['s'], $module_emails[$array['id']]['c'])) {
                if ($lang == $array['lang']) {
                    $array['data_rollback']['default_subject'] = $module_emails[$array['id']]['s'];
                    $array['data_rollback']['default_content'] = $module_emails[$array['id']]['c'];
                    $array['data_rollback']['langs'][$lang]['title'] = $module_emails[$array['id']]['t'];
                    $array['data_rollback']['langs'][$lang]['subject'] = '';
                    $array['data_rollback']['langs'][$lang]['content'] = '';
                    $array['allow_rollback'] = 1;
                } else {
                    $array['data_rollback']['langs'][$lang]['title'] = $module_emails[$array['id']]['t'];
                    $array['data_rollback']['langs'][$lang]['subject'] = $module_emails[$array['id']]['s'];
                    $array['data_rollback']['langs'][$lang]['content'] = $module_emails[$array['id']]['c'];
                }
            }
        }
        unset($module_emails);

        $update_for = [
            1 => $nv_Lang->getModule('update_for1'),
            2 => $nv_Lang->getModule('update_for2', $language_array[$array['lang']]['name']),
            3 => $nv_Lang->getModule('update_for3', $array['module_name']),
            4 => $nv_Lang->getModule('update_for4'),
        ];
    } elseif ($array['is_system']) {
        foreach ($global_config['setup_langs'] as $lang) {
            // Init các biến để gọi file
            $install_lang = [];
            $lang_data = '';
            $file = NV_ROOTDIR . '/install/data_' . $lang . '.php';
            if (file_exists($file)) {
                include $file;
            }
            if (!empty($install_lang['emailtemplates']) and !empty($install_lang['emailtemplates']['emails']) and isset($install_lang['emailtemplates']['emails'][$emailid])) {
                $email = $install_lang['emailtemplates']['emails'][$emailid];
                if ($lang == $global_config['site_lang']) {
                    $array['allow_rollback'] = 1;
                    $array['data_rollback']['default_subject'] = $email['s'];
                    $array['data_rollback']['default_content'] = $email['c'];
                    $array['data_rollback']['langs'][$lang]['title'] = $email['t'];
                    $array['data_rollback']['langs'][$lang]['subject'] = '';
                    $array['data_rollback']['langs'][$lang]['content'] = '';
                } else {
                    $array['data_rollback']['langs'][$lang]['title'] = $email['t'];
                    $array['data_rollback']['langs'][$lang]['subject'] = $email['s'];
                    $array['data_rollback']['langs'][$lang]['content'] = $email['c'];
                }
            }
            unset($email, $install_lang, $menu_rows_lev0, $menu_rows_lev1);
        }
    }
} elseif ($copyid) {
    $array['emailid'] = 0;
    $array['is_system'] = 0;
    $array['allow_rollback'] = 0;
    $array['module_name'] = '';
    $array['module_file'] = '';
    $array['sys_pids'] = [];

    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
    $page_title = $nv_Lang->getModule('add_template');
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
        'is_selftemplate' => 0,
        'mailtpl' => '',
        'default_subject' => '',
        'default_content' => '',
        'title' => [],
        'subject' => [],
        'content' => [],
        'is_system' => 0,
        'pids' => [],
        'sys_pids' => [],
        'allow_rollback' => 0
    ];
    foreach ($global_config['setup_langs'] as $lang) {
        $array['title'][$lang] = '';
        $array['subject'][$lang] = '';
        $array['content'][$lang] = '';
    }
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
    $page_title = $nv_Lang->getModule('add_template');
}

$array['showlang'] = NV_LANG_DATA;
$array['update_for'] = 1;

if ($nv_Request->get_title('saveform', 'post', '') == NV_CHECK_SESSION) {
    if (empty($array['is_system'])) {
        $array['catid'] = $nv_Request->get_int('catid', 'post', 0);
        $array['title'] = $nv_Request->get_typed_array('title', 'post', 'title', []);
    }
    $array['showlang'] = $nv_Request->get_title('showlang', 'post', '');
    if (!in_array($array['showlang'], $global_config['setup_langs'])) {
        $array['showlang'] = NV_LANG_DATA;
    }

    $array['send_name'] = $nv_Request->get_title('send_name', 'post', '');
    $array['send_email'] = $nv_Request->get_title('send_email', 'post', '');
    $array['send_cc'] = $nv_Request->get_title('send_cc', 'post', '');
    $array['send_bcc'] = $nv_Request->get_title('send_bcc', 'post', '');
    $array['is_plaintext'] = (int) ($nv_Request->get_bool('is_plaintext', 'post', false));
    $array['is_disabled'] = (int) ($nv_Request->get_bool('is_disabled', 'post', false));
    $array['is_selftemplate'] = (int) ($nv_Request->get_bool('is_selftemplate', 'post', false));
    $array['mailtpl'] = $nv_Request->get_title('mailtpl', 'post', '');
    $array['attachments'] = $nv_Request->get_typed_array('attachments', 'post', 'string', []);
    $array['update_for'] = $nv_Request->get_absint('update_for', 'post', 0);

    if ($array['allow_rollback'] and $nv_Request->isset_request('submitrollback', 'post')) {
        $array['default_subject'] = $array['data_rollback']['default_subject'];
        $array['default_content'] = $array['data_rollback']['default_content'];
        $array['pids'] = $array['data_rollback']['pids'];
        $array['subject'] = $array['content'] = $array['title'] = [];

        foreach ($global_config['setup_langs'] as $lang) {
            if (isset($array['data_rollback']['langs'][$lang])) {
                $array['title'][$lang] = $array['data_rollback']['langs'][$lang]['title'];
                $array['subject'][$lang] = $array['data_rollback']['langs'][$lang]['subject'];
                $array['content'][$lang] = $array['data_rollback']['langs'][$lang]['content'];
            } else {
                $array['title'][$lang] = '';
                $array['subject'][$lang] = '';
                $array['content'][$lang] = '';
            }
        }
    } else {
        $array['default_subject'] = $nv_Request->get_title('default_subject', 'post', '');
        $array['default_content'] = $nv_Request->get_editor('default_content', '', NV_ALLOWED_HTML_TAGS);
        $array['pids'] = $nv_Request->get_typed_array('pids', 'post', 'int', []);
        $array['pids'] = array_intersect(array_unique(array_filter(array_diff($array['pids'], $array['sys_pids']))), array_keys($array_mplugins));

        $array['subject'] = $nv_Request->get_typed_array('subject', 'post', 'title', '');
        $array['content'] = [];
        foreach ($global_config['setup_langs'] as $lang) {
            $array['content'][$lang] = $nv_Request->get_editor('lang_content_' . $lang, '', NV_ALLOWED_HTML_TAGS);
        }
    }

    if (!empty($array['catid']) and !isset($global_array_cat[$array['catid']])) {
        $array['catid'] = 0;
    }
    if (!in_array($array['mailtpl'], $array_mailtpl, true)) {
        $array['mailtpl'] = '';
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
        $error[] = $check;
    }
    if (!empty($error_cc)) {
        $error[] = $error_cc;
    }
    if (!empty($error_bcc)) {
        $error[] = $error_bcc;
    }

    // Kiểm tra lỗi rỗng tiêu đề, trùng tiêu đề
    $default_title = $array['title'][NV_LANG_DATA] ?? '';
    foreach ($global_config['setup_langs'] as $lang) {
        // Ngôn ngữ khác, không nhập tiêu đề thì lấy bằng ngôn ngữ đang thao tác
        if (empty($array['title'][$lang]) and $lang != NV_LANG_DATA) {
            $array['title'][$lang] = $default_title;
        }

        if (empty($array['title'][$lang]) and $lang == NV_LANG_DATA) {
            $error[] = $nv_Lang->getModule('tpl_error_title', $language_array[$lang]['name']);
        } elseif (!empty($default_title)) {
            // Kiểm tra trùng
            $sql = 'SELECT * FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' WHERE ' . NV_LANG_DATA . '_title = :title AND module_name=\'\' AND emailid != ' . $array['emailid'];
            $sth = $db->prepare($sql);
            $sth->bindParam(':title', $array['title'][$lang], PDO::PARAM_STR);
            $sth->execute();
            if ($sth->fetchColumn()) {
                $error[] = $nv_Lang->getModule('tpl_error_exists', $language_array[$lang]['name']);
            }
        }
    }

    $pattern_smarty = '/\{[\s]*\$smarty[\s]*(\.|\[)/i';

    if (empty($array['default_subject'])) {
        $error[] = $nv_Lang->getModule('tpl_error_default_subject');
    } elseif (preg_match($pattern_smarty, $array['default_subject'])) {
        $error[] = $nv_Lang->getModule('tpl_error_smarty_subject');
    }
    if (empty($array['default_content'])) {
        $error[] = $nv_Lang->getModule('tpl_error_default_content');
    } elseif (preg_match($pattern_smarty, $array['default_content'])) {
        $error[] = $nv_Lang->getModule('tpl_error_smarty_content');
    }

    // Kiểm tra bảo mật tiêu đề và nội dung email theo ngôn ngữ
    foreach ($array['subject'] as $lang => $subject) {
        if (preg_match($pattern_smarty, $subject)) {
            $error[] = $nv_Lang->getModule('tpl_error_smarty_subject1', $language_array[$lang]['name']);
        }
    }
    foreach ($array['content'] as $lang => $content) {
        if (preg_match($pattern_smarty, $content)) {
            $error[] = $nv_Lang->getModule('tpl_error_smarty_content1', $language_array[$lang]['name']);
        }
    }

    if (empty($error)) {
        // Hook xử lý biến $array trước khi lưu vào CSDL
        $array = nv_apply_hook('', 'emailtemplates_content_correct_before_save', [$array], $array);

        if (!$array['emailid']) {
            $field_title = $field_value = '';
            foreach ($global_config['setup_langs'] as $lang) {
                $field_title .= ', ' . $lang . '_title, ' . $lang . '_subject, ' . $lang . '_content';
                $field_value .= ', :' . $lang . '_title, :' . $lang . '_subject, :' . $lang . '_content';
            }

            $sql = 'INSERT INTO ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' (
                catid, pids, time_add, send_name, send_email, send_cc, send_bcc, attachments, is_system, is_plaintext, is_disabled,
                is_selftemplate, mailtpl, default_subject, default_content' . $field_title . '
            ) VALUES (
                ' . $array['catid'] . ', ' . $db->quote(implode(',', $array['pids'])) . ', ' . NV_CURRENTTIME . ',
                :send_name, :send_email, :send_cc, :send_bcc, :attachments, 0,
                ' . $array['is_plaintext'] . ', ' . $array['is_disabled'] . ', ' . $array['is_selftemplate'] . ', :mailtpl,
                :default_subject, :default_content' . $field_value . '
            )';
        } else {
            $sql = 'UPDATE ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' SET
                catid = ' . $array['catid'] . ',
                pids = ' . $db->quote(implode(',', $array['pids'])) . ',
                time_update = ' . NV_CURRENTTIME . ',
                send_name = :send_name,
                send_email = :send_email,
                send_cc = :send_cc,
                send_bcc = :send_bcc,
                attachments = :attachments,
                is_plaintext = ' . $array['is_plaintext'] . ',
                is_disabled = ' . $array['is_disabled'] . ',
                is_selftemplate = ' . $array['is_selftemplate'] . ',
                mailtpl = :mailtpl,
                default_subject = :default_subject,
                default_content = :default_content';
            foreach ($global_config['setup_langs'] as $lang) {
                $sql .= ', ' . $lang . '_title = :' . $lang . '_title,
                ' . $lang . '_subject = :' . $lang . '_subject,
                ' . $lang . '_content = :' . $lang . '_content';
            }
            $sql .= ' WHERE emailid = ' . $array['emailid'];
        }

        $send_cc = implode(',', $array['send_cc']);
        $send_bcc = implode(',', $array['send_bcc']);
        $attachments = implode(',', $array['attachments']);
        $default_content = nv_editor_nl2br($array['default_content']);

        try {
            $sth = $db->prepare($sql);
            $sth->bindParam(':send_name', $array['send_name'], PDO::PARAM_STR);
            $sth->bindParam(':send_email', $array['send_email'], PDO::PARAM_STR);
            $sth->bindParam(':send_cc', $send_cc, PDO::PARAM_STR, strlen($send_cc));
            $sth->bindParam(':send_bcc', $send_bcc, PDO::PARAM_STR, strlen($send_bcc));
            $sth->bindParam(':attachments', $attachments, PDO::PARAM_STR, strlen($attachments));
            $sth->bindParam(':mailtpl', $array['mailtpl'], PDO::PARAM_STR);
            $sth->bindParam(':default_subject', $array['default_subject'], PDO::PARAM_STR);
            $sth->bindParam(':default_content', $default_content, PDO::PARAM_STR, strlen($default_content));

            foreach ($global_config['setup_langs'] as $lang) {
                $sth->bindValue(':' . $lang . '_title', $array['title'][$lang] ?? '', PDO::PARAM_STR);
                $sth->bindValue(':' . $lang . '_subject', $array['subject'][$lang] ?? '', PDO::PARAM_STR);
                $sth->bindValue(':' . $lang . '_content', nv_editor_nl2br($array['content'][$lang] ?? ''), PDO::PARAM_STR);
            }

            $sth->execute();
            $new_emailid = $db->lastInsertId();

            if ($sth->rowCount()) {
                if ($array['emailid']) {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit Email Template', 'ID: ' . $array['emailid'], $admin_info['userid']);
                } else {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Add Email Template', 'ID: ' . $new_emailid, $admin_info['userid']);

                    $sql = "UPDATE " . NV_EMAILTEMPLATES_GLOBALTABLE . " SET id=" . $new_emailid . " WHERE emailid=" . $new_emailid;
                    $db->query($sql);
                }

                // Thực hiện cập nhật cho các mẫu khác
                if (!empty($update_for) and isset($update_for[$array['update_for']]) and $array['update_for'] != 4) {
                    $sql = "SELECT emailid, lang FROM " . NV_EMAILTEMPLATES_GLOBALTABLE . " WHERE emailid!=" . $array['emailid'] . " AND
                    id=" . $array['id'] . " AND ";
                    if ($array['update_for'] == 1) {
                        // Tất cả
                        $sql .= 'module_file=' . $db->quote($array['module_file']) . " AND module_file!=''";
                    } elseif ($array['update_for'] == 2) {
                        // Cùng ngôn ngữ
                        $sql .= "lang=" . $db->quote($array['lang']) . " AND lang!='' AND module_file=" . $db->quote($array['module_file']) . " AND module_file!=''";
                    } else {
                        // Cùng tên
                        $sql .= "module_file=" . $db->quote($array['module_file']) . " AND module_file!='' AND module_name=" . $db->quote($array['module_name']) . " AND module_name!=''";
                    }
                    $result = $db->query($sql);

                    while ($row = $result->fetch()) {
                        $sql = 'UPDATE ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' SET
                            time_update = ' . NV_CURRENTTIME . ',
                            default_subject = :default_subject,
                            default_content = :default_content';
                        foreach ($global_config['setup_langs'] as $lang) {
                            $sql .= ', ' . $lang . '_title = :' . $lang . '_title,
                            ' . $lang . '_subject = :' . $lang . '_subject,
                            ' . $lang . '_content = :' . $lang . '_content';
                        }
                        $sql .= ' WHERE emailid=' . $row['emailid'];
                        $sth = $db->prepare($sql);

                        // Chuyển mặc định của ngôn ngữ đích thành tương ứng ngôn ngữ nguồn nếu có. Hoặc là mặc định của nguồn
                        $d_subject = !empty($array['subject'][$row['lang']]) ? $array['subject'][$row['lang']] : $array['default_subject'];
                        $d_content = !empty($array['content'][$row['lang']]) ? nv_editor_nl2br($array['content'][$row['lang']]) : $default_content;

                        $sth->bindValue(':default_subject', $d_subject, PDO::PARAM_STR);
                        $sth->bindValue(':default_content', $d_content, PDO::PARAM_STR);

                        foreach ($global_config['setup_langs'] as $lang) {
                            $sth->bindValue(':' . $lang . '_title', $array['title'][$lang] ?? '', PDO::PARAM_STR);

                            $subject = $content = '';
                            if (!empty($array['subject'][$lang])) {
                                $subject = $array['subject'][$lang];
                            } elseif ($lang == $array['lang']) {
                                $subject = $array['default_subject'];
                            }
                            if (!empty($array['content'][$lang])) {
                                $content = nv_editor_nl2br($array['content'][$lang]);
                            } elseif ($lang == $array['lang']) {
                                $content = $default_content;
                            }
                            // Trên ngôn ngữ đích mặc định nếu theo ngôn ngữ giống với mặc định thì cho nó rỗng
                            if ($lang == $row['lang']) {
                                $subject == $d_subject && $subject = '';
                                $content == $d_content && $content = '';
                            }

                            $sth->bindValue(':' . $lang . '_subject', $subject, PDO::PARAM_STR);
                            $sth->bindValue(':' . $lang . '_content', $content, PDO::PARAM_STR);
                        }

                        $sth->execute();
                    }
                    $result->closeCursor();
                }

                nv_apply_hook('', 'emailtemplates_content_after_save', [$array]);

                $nv_Cache->delMod($module_name);
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
            } else {
                $error[] = $nv_Lang->getModule('errorsave');
            }
        } catch (Throwable $e) {
            // Hook khi bị lỗi lưu vào CSDL
            trigger_error(print_r($e, true));
            nv_apply_hook('', 'emailtemplates_on_emailtemplate_save_error', [$array, $e]);
            $error[] = $nv_Lang->getModule('errorsave');
        }
    }
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

if (!empty($array['default_content'])) {
    $array['default_content'] = nv_htmlspecialchars($array['default_content']);
}
$array['content'] = array_map('nv_htmlspecialchars', $array['content']);

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $array['default_content'] = nv_aleditor('default_content', '100%', '300px', $array['default_content'], '', NV_UPLOADS_DIR . '/' . $module_upload, NV_UPLOADS_DIR . '/' . $module_upload, 'setEditorReady');

    foreach ($array['content'] as $lang => $content) {
        $array['content'][$lang] = nv_aleditor('lang_content_' . $lang, '100%', '300px', $content, '', NV_UPLOADS_DIR . '/' . $module_upload, NV_UPLOADS_DIR . '/' . $module_upload, 'setEditorReady');
    }
} else {
    $array['default_content'] = '<textarea class="form-control" data-toggle="textareaemailcontent" style="width:100%;height:500px" name="default_content" id="emailtemplates_default_content">' . $array['default_content'] . '</textarea>';

    foreach ($array['content'] as $lang => $content) {
        $array['content'][$lang] = '<textarea class="form-control" data-toggle="textareaemailcontent" style="width:100%;height:500px" name="lang_content_' . $lang . '" id="emailtemplates_lang_content_' . $lang . '">' . $content . '</textarea>';
    }
}
$array['send_cc'] = implode(', ', $array['send_cc']);
$array['send_bcc'] = implode(', ', $array['send_bcc']);
if (empty($array['attachments'])) {
    $array['attachments'][] = '';
}

$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('FORM_ACTION', $form_action);
$tpl->assign('DATA', $array);
$tpl->assign('ERROR', $error);
$tpl->assign('LANGUAGE_ARRAY', $language_array);
$tpl->assign('CATS', $global_array_cat);
$tpl->assign('UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_upload);
$tpl->assign('PLUGINS', $array_mplugins);
$tpl->assign('ARRAY_MAILTPL', $array_mailtpl);
$tpl->assign('UPDATE_FOR', $update_for);

$contents = $tpl->fetch('contents.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
