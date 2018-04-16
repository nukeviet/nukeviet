<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (!defined('NV_IS_MOD_CONTACT')) {
    die('Stop!!!');
}

//Danh sach cac bo phan
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE act>0 ORDER BY weight';
$array_department = $nv_Cache->db($sql, 'id', $module_name);

$alias_url = isset($array_op[0]) ? $array_op[0] : '';
$alias_department = '';

$cats = array();
$cats[] = array(0, '');
$catsName = array();
$catsName[] = $lang_module['selectCat'];
$dpDefault = 0;
if (!empty($array_department)) {
    foreach ($array_department as $k => $department) {
        if ($department['alias'] == $alias_url) {
            $alias_department = $department['alias'];
            $dpDefault = $department['id'];
            $array_department = array($department['id'] => $department);
            $cats = array();
            $catsName = array_map('trim', explode('|', $department['cats']));
            foreach ($catsName as $_cats2) {
                $cats[] = array($department['id'], $_cats2);
            }
            break;
        }

        if (!empty($department['cats'])) {
            $_cats = array_map('trim', explode('|', $department['cats']));
            foreach ($_cats as $_cats2) {
                $cats[] = array($department['id'], $_cats2);
                $catsName[] = in_array($_cats2, $catsName) ? $_cats2 . ', ' . $department['full_name'] : $_cats2;
            }
        }

        if ($department['is_default']) {
            $dpDefault = $department['id'];
        }
    }
}

if (empty($dpDefault) and !empty($array_department)) {
    $key_department = array_keys($array_department);
    $dpDefault = $key_department[0];
}

$fname = '';
$femail = '';
$fphone = '';
$faddress = '';
$sendcopy = true;
if (!defined('NV_IS_MODADMIN') and empty($module_config[$module_name]['sendcopymode']) and (!defined('NV_IS_USER') or $user_info['email_verification_time'] == 0 or $user_info['email_verification_time'] == -1)) {
    $sendcopy = false;
}

if (defined('NV_IS_USER')) {
    $fname = !empty($user_info['full_name']) ? $user_info['full_name'] : $user_info['username'];
    $femail = $user_info['email'];
    $fphone = isset($user_info['phone']) ? $user_info['phone'] : '';
}

/**
 * Nhan thong tin va gui den admin
 */
if ($nv_Request->isset_request('checkss', 'post')) {
    $checkss = $nv_Request->get_title('checkss', 'post', '');
    if ($checkss != NV_CHECK_SESSION) {
        die();
    }

    /**
     * Ajax
     */
    if ($nv_Request->isset_request('loadForm', 'post')) {
        $array_content = array(
            'fname' => $fname,
            'femail' => $femail,
            'fphone' => $fphone,
            'sendcopy' => $sendcopy,
            'bodytext' => ''
        );

        $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

        $form = contact_form_theme($array_content, $catsName, $base_url, NV_CHECK_SESSION);

        exit($form);
    }

    if (!defined('NV_IS_USER')) {
        $fname = nv_substr($nv_Request->get_title('fname', 'post', '', 1), 0, 100);
        $femail = nv_substr($nv_Request->get_title('femail', 'post', '', 1), 0, 100);
    }

    if (empty($fname)) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'fname',
            'mess' => $lang_module['error_fullname']));
    }

    if (($check_valid_email = nv_check_valid_email($femail)) != '') {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'femail',
            'mess' => $check_valid_email));
    }

    if (($ftitle = nv_substr($nv_Request->get_title('ftitle', 'post', '', 1), 0, 255)) == '') {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'ftitle',
            'mess' => $lang_module['error_title']));
    }
    if (($fcon = $nv_Request->get_editor('fcon', '', NV_ALLOWED_HTML_TAGS)) == '') {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'fcon',
            'mess' => $lang_module['error_content']));
    }
    if (!nv_capcha_txt(($global_config['captcha_type'] == 2 ? $nv_Request->get_title('g-recaptcha-response', 'post', '') : $nv_Request->get_title('fcode', 'post', '')))) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => ($global_config['captcha_type'] == 2 ? '' : 'fcode'),
            'mess' => ($global_config['captcha_type'] == 2 ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect'])));
    }

    $fcat = $nv_Request->get_int('fcat', 'post', 0);
    if (isset($cats[$fcat])) {
        $fpart = (int)$cats[$fcat][0];
        $fcat = $cats[$fcat][1];
    } else {
        $fpart = (int)$cats[0][0];
        $fcat = $cats[0][1];
    }

    if ($fpart == 0) {
        $fpart = $dpDefault;
        $fcat = '';
    }

    $fcon = nv_nl2br($fcon);
    $fphone = nv_substr($nv_Request->get_title('fphone', 'post', '', 1), 0, 100);
    $faddress = nv_substr($nv_Request->get_title('faddress', 'post', '', 1), 0, 100);
    $fsendcopy = ((int)$nv_Request->get_bool('sendcopy', 'post') and $sendcopy);
    $sender_id = intval(defined('NV_IS_USER') ? $user_info['userid'] : 0);

    $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_send
    (cid, cat, title, content, send_time, sender_id, sender_name, sender_email, sender_phone, sender_address, sender_ip, is_read, is_reply) VALUES
    (' . $fpart . ', :cat, :title, :content, ' . NV_CURRENTTIME . ', ' . $sender_id . ', :sender_name, :sender_email, :sender_phone, :sender_address, :sender_ip, 0, 0)';
    $data_insert = array();
    $data_insert['cat'] = $fcat;
    $data_insert['title'] = $ftitle;
    $data_insert['content'] = $fcon;
    $data_insert['sender_name'] = $fname;
    $data_insert['sender_email'] = $femail;
    $data_insert['sender_phone'] = $fphone;
    $data_insert['sender_address'] = $faddress;
    $data_insert['sender_ip'] = $client_info['ip'];
    $row_id = $db->insert_id($sql, 'id', $data_insert);
    if ($row_id > 0) {
        $fcon_mail = contact_sendcontact($row_id, $fcat, $ftitle, $fname, $femail, $fphone, $fcon, $fpart);

        $email_list = array();
        if (!empty($array_department[$fpart]['email'])) {
            $_emails = array_map('trim', explode(',', $array_department[$fpart]['email']));
            $email_list[] = $_emails[0];
        }

        if (!empty($array_department[$fpart]['admins'])) {
            $admins = array_filter(array_map('trim', explode(';', $array_department[$fpart]['admins'])));

            $a_l = array();
            foreach ($admins as $adm) {
                unset($adm2);
                if (preg_match('/^([0-9]+)\/[0-1]{1}\/[0-1]{1}\/1$/', $adm, $adm2)) {
                    $a_l[] = $adm2[1];
                }
            }

            if (!empty($a_l)) {
                $a_l = implode(',', $a_l);

                $sql = 'SELECT t2.email as admin_email FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.admin_id = t2.userid WHERE t1.lev!=0 AND t1.is_suspend=0 AND t2.active=1 AND t1.admin_id IN (' . $a_l . ')';
                $result = $db_slave->query($sql);

                while ($row = $result->fetch()) {
                    if (nv_check_valid_email($row['admin_email']) == '') {
                        $email_list[] = $row['admin_email'];
                    }
                }
            }
        }

        if (!empty($email_list)) {
            $from = array($fname, $femail);
            $email_list = array_unique($email_list);
            @nv_sendmail($from, $email_list, $ftitle, $fcon_mail);
        }

        // Gửi bản sao đến hộp thư người gửi
        if ($fsendcopy) {
            $from = array($global_config['site_name'], $global_config['site_email']);
            $fcon_mail = contact_sendcontact($row_id, $fcat, $ftitle, $fname, $femail, $fphone, $fcon, $fpart, false);
            @nv_sendmail($from, $femail, $ftitle, $fcon_mail);
        }

        nv_insert_notification($module_name, 'contact_new', array('title' => $ftitle), $row_id, 0, $sender_id, 1);

        nv_jsonOutput(array(
            'status' => 'ok',
            'input' => '',
            'mess' => $lang_module['sendcontactok']));
    }

    nv_jsonOutput(array(
        'status' => 'error',
        'input' => '',
        'mess' => $lang_module['sendcontactfailed']));
}


$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
$mod_title = isset($lang_module['main_title']) ? $lang_module['main_title'] : $module_info['custom_title'];

$full_theme = true;
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
if (!empty($alias_department)) {
    $base_url .= '&amp;' . NV_OP_VARIABLE . '=' . $alias_department;
    if (isset($array_op[1]) and $array_op[1] == 0) {
        $base_url .= '/0';
        $full_theme = false;
    }
}

$base_url_rewrite = nv_url_rewrite($base_url, true);
$base_url_rewrite_location = str_replace('&amp;', '&', $base_url_rewrite);
if ($_SERVER['REQUEST_URI'] == $base_url_rewrite_location) {
    $canonicalUrl = NV_MAIN_DOMAIN . $base_url_rewrite;
} elseif (NV_MAIN_DOMAIN . $_SERVER['REQUEST_URI'] != $base_url_rewrite_location) {
    nv_redirect_location($base_url_rewrite_location);
} else {
    $canonicalUrl = $base_url_rewrite;
}

$array_content = array(
    'fname' => $fname,
    'femail' => $femail,
    'fphone' => $fphone,
    'sendcopy' => $sendcopy,
    'bodytext' => $module_config[$module_name]['bodytext']
);

$contents = contact_main_theme($array_content, $array_department, $catsName, $base_url, NV_CHECK_SESSION);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents, $full_theme);
include NV_ROOTDIR . '/includes/footer.php';
