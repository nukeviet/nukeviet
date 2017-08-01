<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (@) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get', 0);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_send WHERE id=' . $id;
$row = $db->query($sql)->fetch();
$row['title'] = 'Re:' . $row['title'];
if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$contact_allowed = nv_getAllowed();
if (!isset($contact_allowed['view'][$row['cid']]) or !isset($contact_allowed['reply'][$row['cid']])) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$xtpl = new XTemplate('reply.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('POST', $row);

$is_read = intval($row['is_read']);
if (!$is_read) {
    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_send SET is_read=1 WHERE id=' . $id);
    $is_read = 1;
}

$mess_content = $error = '';

if ($nv_Request->get_int('save', 'post') == '1') {
    $mess_content = $nv_Request->get_editor('mess_content', '', NV_ALLOWED_HTML_TAGS);
    if (strip_tags($mess_content) != '') {

        $mail = new NukeViet\Core\Sendmail($global_config, NV_LANG_INTERFACE);
        $mail->To($row['sender_email']);

        $_array_email = array();
        $frow = $db->query('SELECT full_name, email, admins FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE id=' . $row['cid'])->fetch();
        if (!empty($frow)) {
            $_arr_mail = explode(',', $frow['email']);
            foreach ($_arr_mail as $_email) {
                if (nv_check_valid_email($_email) != '') {
                    $mail->addReplyTo($_email, $frow['full_name']);
                    $_array_email[] = $_email;
                }
            }

            // Gửi cho các quản trị trong bộ phận
            $obt_level = array();
            $admins_list = $frow['admins'];
            $admins_list = !empty($admins_list) ? array_map('trim', explode(';', $admins_list)) : array();
            foreach ($admins_list as $l) {
                $l2 = array_map('intval', explode('/', $l));
                if (isset($l2[3]) and $l2[3] === 1) {
                    $obt_level[] = intval($l2[0]);
                }
            }
            if (!empty($obt_level)) {
                $sql = 'SELECT username, email, first_name, last_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN (SELECT admin_id FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id IN (' . implode(',', $obt_level) . ') AND is_suspend=0) AND active=1';
                $_result = $db->query($sql);
                while ($_row = $_result->fetch()) {
                    if (!in_array($_row['email'], $_array_email)) {
                        $_row['full_name'] = nv_show_name_user($_row['first_name'], $_row['last_name'], $_row['username']);
                        $mail->Cc($_row['email'], $_row['full_name']);
                        $_array_email[] = $_row['email'];
                    }
                }
            }
        }

        if (empty($_array_email)) {
            $mail->addReplyTo($admin_info['email'], $admin_info['full_name']);
            $_array_email[] = $admin_info['email'];
        } elseif (!in_array($admin_info['email'], $_array_email)) {
            $mail->Cc($admin_info['email'], $admin_info['full_name']);
            $_array_email[] = $admin_info['email'];
        }

        $mail->Content($mess_content);
        $mail->Subject($row['title']);
        if ($mail->Send()) {
            $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_reply (id, reply_content, reply_time, reply_aid) VALUES (' . $id . ', :reply_content, ' . NV_CURRENTTIME . ', ' . $admin_info['admin_id'] . ')');
            $sth->bindParam(':reply_content', $mess_content, PDO::PARAM_STR, strlen($mess_content));
            $sth->execute();

            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_send SET is_reply=1 WHERE id=' . $id);

            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=view&id=' . $id);
        } else {
            $error = $lang_global['error_sendmail_admin'];
        }
    }
} else {
    $mess_content .= '--------------------------------------------------------------------------------<br />';
    $mess_content .= '<strong>From:</strong> ' . $row['sender_name'] . ' [mailto:' . $row['sender_email'] . ']<br />';
    $mess_content .= '<strong>Sent:</strong> ' . date('r', $row['send_time']) . '<br />';
    $mess_content .= '<strong>To:</strong> ' . $contact_allowed['view'][$row['cid']] . '<br />';
    $mess_content .= '<strong>Subject:</strong> ' . $row['title'] . '<br /><br />';
    $mess_content .= $row['content'];
    
    require_once NV_ROOTDIR . '/modules/contact/sign.php';
    $mess_content .= $sign_content;
}

$mess_content = htmlspecialchars(nv_editor_br2nl($mess_content));

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $mess_content = nv_aleditor('mess_content', '100%', '300px', $mess_content);
} else {
    $mess_content = '<textarea style="width:99%" name="mess_content" id="mess_content" cols="20" rows="8">' . $mess_content . '</textarea>';
}

$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id);
$xtpl->assign('MESS_CONTENT', $mess_content);

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