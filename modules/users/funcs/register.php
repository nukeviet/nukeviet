<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (!defined('NV_IS_MOD_USER')) {
    die('Stop!!!');
}

// Dang nhap thanh vien thi khong duoc truy cap
if (defined('NV_IS_USER') and !defined('ACCESS_ADDUS')) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

// Chuyen trang dang ki neu tich hop dien dan
if (defined('NV_IS_USER_FORUM')) {
    require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/register.php';
    exit();
}

// Ngung dang ki thanh vien
if (!$global_config['allowuserreg']) {
    $page_title = $lang_module['register'];
    $key_words = $module_info['keywords'];
    $mod_title = $lang_module['register'];

    $contents = user_info_exit($lang_module['no_allowuserreg']);
    $contents .= '<meta http-equiv="refresh" content="5;url=' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true) . '" />';

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$nv_redirect = '';
if ($nv_Request->isset_request('nv_redirect', 'post,get')) {
    $nv_redirect = nv_get_redirect();
}

/**
 * nv_check_username_reg()
 * Ham kiem tra ten dang nhap kha dung
 *
 * @param mixed $login
 * @return
 */
function nv_check_username_reg($login)
{
    global $db, $lang_module, $global_users_config, $global_config;

    $error = nv_check_valid_login($login, $global_config['nv_unickmax'], $global_config['nv_unickmin']);
    if ($error != '') {
        return preg_replace('/\&(l|r)dquo\;/', '', strip_tags($error));
    }
    if ("'" . $login . "'" != $db->quote($login)) {
        return sprintf($lang_module['account_deny_name'], $login);
    }

    if (!empty($global_users_config['deny_name']) and preg_match('/' . $global_users_config['deny_name'] . '/i', $login)) {
        return sprintf($lang_module['account_deny_name'], $login);
    }

    $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . ' WHERE md5username= :md5username');
    $stmt->bindValue(':md5username', nv_md5safe($login), PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        return sprintf($lang_module['account_registered_name'], $login);
    }

    $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_reg WHERE md5username= :md5username');
    $stmt->bindValue(':md5username', nv_md5safe($login), PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        return sprintf($lang_module['account_registered_name'], $login);
    }

    return '';
}

/**
 * nv_check_email_reg()
 * Ham kiem tra email kha dung
 *
 * @param mixed $email
 * @return
 */
function nv_check_email_reg($email)
{
    global $db, $lang_module, $global_users_config;

    $error = nv_check_valid_email($email);
    if ($error != '') {
        return preg_replace('/\&(l|r)dquo\;/', '', strip_tags($error));
    }

    if (!empty($global_users_config['deny_email']) and preg_match('/' . $global_users_config['deny_email'] . '/i', $email)) {
        return sprintf($lang_module['email_deny_name'], $email);
    }

    list($left, $right) = explode('@', $email);
    $left = preg_replace('/[\.]+/', '', $left);
    $pattern = str_split($left);
    $pattern = implode('.?', $pattern);
    $pattern = '^' . $pattern . '@' . $right . '$';

    $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . ' WHERE email RLIKE :pattern');
    $stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        return sprintf($lang_module['email_registered_name'], $email);
    }

    $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_reg WHERE email RLIKE :pattern');
    $stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        return sprintf($lang_module['email_registered_name'], $email);
    }

    $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_openid WHERE email RLIKE :pattern');
    $stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        return sprintf($lang_module['email_registered_name'], $email);
    }

    return '';
}

/**
 * reg_result()
 *
 * @param mixed $array
 * @return
 */
function reg_result($array)
{
    global $nv_redirect;

    $array['redirect'] = nv_redirect_decrypt($nv_redirect);
    $string = json_encode($array);
    return $string;
}

// Cau hoi lay lai mat khau
$data_questions = array();
$sql = "SELECT qid, title FROM " . NV_MOD_TABLE . "_question WHERE lang='" . NV_LANG_DATA . "' ORDER BY weight ASC";
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $data_questions[$row['qid']] = array(
        'qid' => $row['qid'],
        'title' => $row['title']
    );
}

// Captcha
$gfx_chk = (in_array($global_config['gfx_chk'], array(
    3,
    4,
    6,
    7
))) ? 1 : 0;

$array_register = array();
$array_register['checkss'] = NV_CHECK_SESSION;
$array_register['nv_redirect'] = $nv_redirect;
$checkss = $nv_Request->get_title('checkss', 'post', '');

//Check email address for AJAX
if ($nv_Request->isset_request('checkMail', 'post') and $checkss == $array_register['checkss']) {
    $email = nv_strtolower(nv_substr($nv_Request->get_title('email', 'post', '', 1), 0, 100));
    $check_email = nv_check_email_reg($email);
    if (!empty($check_email)) {
        die(json_encode(array(
            'status' => 'error',
            'mess' => $check_email
        )));
    }
    die(json_encode(array(
        'status' => 'success',
        'mess' => 'OK'
    )));
}

//Check Login for AJAX
if ($nv_Request->isset_request('checkLogin', 'post') and $checkss == $array_register['checkss']) {
    $login = $nv_Request->get_title('login', 'post', '', 1);
    $check_login = nv_check_username_reg($login);
    if (!empty($check_login)) {
        die(json_encode(array(
            'status' => 'error',
            'mess' => $check_login
        )));
    }
    die(json_encode(array(
        'status' => 'success',
        'mess' => 'OK'
    )));
}

if (defined('NV_IS_USER') and defined('ACCESS_ADDUS')) {
    $lang_module['register'] = $lang_module['add_users'];
    $lang_module['info'] = $lang_module['info_user'];
}

// Dang ky thong thuong
$page_title = $lang_module['register'];
$key_words = $module_info['keywords'];
$mod_title = $lang_module['register'];

$array_field_config = array();
$result_field = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field ORDER BY weight ASC');
while ($row_field = $result_field->fetch()) {
    $language = unserialize($row_field['language']);
    $row_field['title'] = (isset($language[NV_LANG_DATA])) ? $language[NV_LANG_DATA][0] : $row['field'];
    $row_field['description'] = (isset($language[NV_LANG_DATA])) ? nv_htmlspecialchars($language[NV_LANG_DATA][1]) : '';
    if (!empty($row_field['field_choices'])) {
        $row_field['field_choices'] = unserialize($row_field['field_choices']);
    } elseif (!empty($row_field['sql_choices'])) {
        $row_field['sql_choices'] = explode('|', $row_field['sql_choices']);
        $query = 'SELECT ' . $row_field['sql_choices'][2] . ', ' . $row_field['sql_choices'][3] . ' FROM ' . $row_field['sql_choices'][1];
        $result = $db->query($query);
        $weight = 0;
        while (list($key, $val) = $result->fetch(3)) {
            $row_field['field_choices'][$key] = $val;
        }
    }
    $array_field_config[] = $row_field;
}
if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$custom_fields = $nv_Request->get_array('custom_fields', 'post');
if ($checkss == $array_register['checkss']) {

    $array_register['first_name'] = nv_substr($nv_Request->get_title('first_name', 'post', '', 1), 0, 255);
    $array_register['last_name'] = nv_substr($nv_Request->get_title('last_name', 'post', '', 1), 0, 255);
    $array_register['username'] = $nv_Request->get_title('username', 'post', '', 1);
    $array_register['password'] = $nv_Request->get_title('password', 'post', '');
    $array_register['re_password'] = $nv_Request->get_title('re_password', 'post', '');
    $array_register['email'] = nv_strtolower(nv_substr($nv_Request->get_title('email', 'post', '', 1), 0, 100));

    $array_register['question'] = $nv_Request->get_int('question', 'post', 0);
    if (!isset($data_questions[$array_register['question']])) {
        $array_register['question'] = 0;
    }
    $data_questions[$array_register['question']]['selected'] = ' selected="selected"';

    $array_register['your_question'] = $nv_Request->get_title('your_question', 'post', '', 1);
    $array_register['answer'] = nv_substr($nv_Request->get_title('answer', 'post', '', 1), 0, 255);

    $array_register['agreecheck'] = $nv_Request->get_int('agreecheck', 'post', 0);
    $array_gender = $nv_Request->get_array('gender', 'post', array());
    $array_register['gender'] = $array_gender[0];
    $array_register['birthday'] = $nv_Request->get_title('birthday', 'post', '');
    $array_register['sig'] = $nv_Request->get_title('sig', 'post', '');

    if ($global_config['captcha_type'] == 2) {
        $nv_seccode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
    } else {
        $nv_seccode = $nv_Request->get_title('nv_seccode', 'post', '');
    }

    $check_seccode = !$gfx_chk ? true : (nv_capcha_txt($nv_seccode) ? true : false);

    $complete = '';

    if (!$check_seccode) {
        die(reg_result(array(
            'status' => 'error',
            'input' => ($global_config['captcha_type'] == 2 ? '' : 'nv_seccode'),
            'mess' => ($global_config['captcha_type'] == 2 ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect'])
        )));
    }

    if ((($check_login = nv_check_username_reg($array_register['username']))) != '') {
        die(reg_result(array(
            'status' => 'error',
            'input' => 'username',
            'mess' => $check_login
        )));
    }

    if (($check_email = nv_check_email_reg($array_register['email'])) != '') {
        die(reg_result(array(
            'status' => 'error',
            'input' => 'email',
            'mess' => $check_email
        )));
    }

    if (($check_pass = nv_check_valid_pass($array_register['password'], $global_config['nv_upassmax'], $global_config['nv_upassmin'])) != '') {
        die(reg_result(array(
            'status' => 'error',
            'input' => 'password',
            'mess' => $check_pass
        )));
    }

    if ($array_register['password'] != $array_register['re_password']) {
        die(reg_result(array(
            'status' => 'error',
            'input' => 're_password',
            'mess' => $lang_global['passwordsincorrect']
        )));
    }

    if (empty($array_register['your_question']) and empty($array_register['question'])) {
        die(reg_result(array(
            'status' => 'error',
            'input' => 'your_question',
            'mess' => $lang_global['your_question_empty']
        )));
    }

    if (empty($array_register['answer'])) {
        die(reg_result(array(
            'status' => 'error',
            'input' => 'answer',
            'mess' => $lang_global['answer_empty']
        )));
    }

    if (empty($array_register['agreecheck']) and !defined('ACCESS_ADDUS')) {
        die(reg_result(array(
            'status' => 'error',
            'input' => 'agreecheck',
            'mess' => $lang_global['agreecheck_empty']
        )));
    }
    foreach ($array_field_config as $_k => $row_f) {
        if ($row_f['system'] == 1) {
            if ($row_f['field'] == 'first_name' || $row_f['field'] == 'last_name' || $row_f['field'] == 'question' || $row_f['field'] == 'answer') {
                if ($row_f['match_type'] == 'alphanumeric') {
                    if (!preg_match('/^[a-zA-Z0-9\_]+$/', $array_register[$row_f['field']])) {
                        die(json_encode(array(
                            'status' => 'error',
                            'input' => $row_f['field'],
                            'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                        )));
                    }
                } elseif ($row_f['match_type'] == 'email') {
                    if (($error = nv_check_valid_email($array_register[$row_f['field']])) != '') {
                        die(json_encode(array(
                            'status' => 'error',
                            'input' => $row_f['field'],
                            'mess' => $error
                        )));
                    }
                } elseif ($row_f['match_type'] == 'url') {
                    if (!nv_is_url($array_register[$row_f['field']])) {
                        die(json_encode(array(
                            'status' => 'error',
                            'input' => $row_f['field'],
                            'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                        )));
                    }
                } elseif ($row_f['match_type'] == 'regex') {
                    if (!preg_match('/' . $row_f['match_regex'] . '/', $array_register[$row_f['field']])) {
                        die(json_encode(array(
                            'status' => 'error',
                            'input' => $row_f['field'],
                            'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                        )));
                    }
                } elseif ($row_f['match_type'] == 'callback') {
                    if (function_exists($row_f['func_callback'])) {
                        if (!call_user_func($row_f['func_callback'], $array_register[$row_f['field']])) {
                            die(json_encode(array(
                                'status' => 'error',
                                'input' => $row_f['field'],
                                'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                            )));
                        }
                    } else {
                        die(json_encode(array(
                            'status' => 'error',
                            'input' => $row_f['field'],
                            'mess' => 'error function not exists ' . $row_f['func_callback']
                        )));
                    }
                } else {
                    $array_register[$row_f['field']] = nv_htmlspecialchars($array_register[$row_f['field']]);
                }
                $strlen = nv_strlen($array_register[$row_f['field']]);

                if ($strlen < $row_f['min_length'] or $strlen > $row_f['max_length']) {
                    die(json_encode(array(
                        'status' => 'error',
                        'input' => $row_f['field'],
                        'mess' => sprintf($lang_module['field_min_max_error'], $row_f['title'], $row_f['min_length'], $row_f['max_length'])
                    )));
                }
            }
            if ($row_f['field'] == 'gender') {
                if (!isset($row_f['field_choices'][$array_register[$row_f['field']]])) {
                    die(json_encode(array(
                        'status' => 'error',
                        'input' => 'custom_fields[' . $row_f['field'] . ']',
                        'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                    )));
                }
            }
            if ($row_f['field'] == 'birthday') {
                if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_register[$row_f['field']], $m)) {
                    $array_register[$row_f['field']] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
                    if ((floor((NV_CURRENTTIME - $array_register[$row_f['field']])/31536000)) < $global_users_config['min_old_user']) {
                        die(json_encode(array(
                            'status' => 'error',
                            'input' => 'custom_fields[' . $row_f['field'] . ']',
                            'mess' => sprintf($lang_module['old_min_user_error'], $global_users_config['min_old_user'])
                        )));
                    }
                    if ($row_f['min_length'] > 0 and ($array_register[$row_f['field']] < $row_f['min_length'] or $array_register[$row_f['field']] > $row_f['max_length'])) {
                        die(json_encode(array(
                            'status' => 'error',
                            'input' => 'custom_fields[' . $row_f['field'] . ']',
                            'mess' => sprintf($lang_module['field_min_max_value'], $row_f['title'], date('d/m/Y', $row_f['min_length']), date('d/m/Y', $row_f['max_length']))
                        )));
                    }
                } else {
                    die(json_encode(array(
                        'status' => 'error',
                        'input' => 'custom_fields[' . $row_f['field'] . ']',
                        'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                    )));
                }
            }
            if ($row_f['field'] == 'sig') {
                $allowed_html_tags = array_map('trim', explode(',', NV_ALLOWED_HTML_TAGS));
                $allowed_html_tags = '<' . implode('><', $allowed_html_tags) . '>';
                $array_register[$row_f['field']] = strip_tags($array_register[$row_f['field']], $allowed_html_tags);
                if ($row_f['match_type'] == 'regex') {
                    if (!preg_match('/' . $row_f['match_regex'] . '/', $array_register[$row_f['field']])) {
                        die(json_encode(array(
                            'status' => 'error',
                            'input' => 'custom_fields[' . $row_f['field'] . ']',
                            'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                        )));
                    }
                } elseif ($row_f['match_type'] == 'callback') {
                    if (function_exists($row_f['func_callback'])) {
                        if (!call_user_func($row_f['func_callback'], $array_register[$row_f['field']])) {
                            die(json_encode(array(
                                'status' => 'error',
                                'input' => 'custom_fields[' . $row_f['field'] . ']',
                                'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                            )));
                        }
                    } else {
                        die(json_encode(array(
                            'status' => 'error',
                            'input' => 'custom_fields[' . $row_f['field'] . ']',
                            'mess' => 'error function not exists ' . $row_f['func_callback']
                        )));
                    }
                }

                $array_register[$row_f['field']] = ($row_f['field_type'] == 'textarea') ? nv_nl2br($array_register[$row_f['field']], '<br />') : $array_register[$row_f['field']];
                $strlen = nv_strlen($array_register[$row_f['field']]);

                if ($strlen < $row_f['min_length'] or $strlen > $row_f['max_length']) {
                    die(json_encode(array(
                        'status' => 'error',
                        'input' => 'custom_fields[' . $row_f['field'] . ']',
                        'mess' => sprintf($lang_module['field_min_max_error'], $row_f['title'], $row_f['min_length'], $row_f['max_length'])
                    )));
                }
            }
        }
    }
    $query_field = array('userid' => 0);
    if (!empty($array_field_config)) {
        $userid = 0;
        require NV_ROOTDIR . '/modules/users/fields.check.php';
    }

    $password = $crypt->hash_password($array_register['password'], $global_config['hashprefix']);
    $your_question = !empty($array_register['your_question']) ? $array_register['your_question'] : $data_questions[$array_register['question']]['title'];
    $checknum = nv_genpass(10);
    $checknum = md5($checknum);
    if (empty($array_register['first_name'])) {
        $array_register['first_name'] = $array_register['username'];
    }

    if (!defined('ACCESS_ADDUS') and ($global_config['allowuserreg'] == 2 or $global_config['allowuserreg'] == 3)) {
        $sql = "INSERT INTO " . NV_MOD_TABLE . "_reg (username, md5username, password, email, first_name, last_name, gender, birthday, sig, regdate, question, answer, checknum, users_info) VALUES (
			:username,
			:md5username,
			:password,
			:email,
			:first_name,
			:last_name,
			:gender,
			:birthday,
			:sig,
			" . NV_CURRENTTIME . ",
			:your_question,
			:answer,
			:checknum,
			:users_info
		)";

        $data_insert = array();
        $data_insert['username'] = $array_register['username'];
        $data_insert['md5username'] = nv_md5safe($array_register['username']);
        $data_insert['password'] = $password;
        $data_insert['email'] = $array_register['email'];
        $data_insert['first_name'] = $array_register['first_name'];
        $data_insert['last_name'] = $array_register['last_name'];
		$data_insert['gender'] = $array_register['gender'];
        $data_insert['birthday'] = $array_register['birthday'];
        $data_insert['sig'] = $array_register['sig'];
        $data_insert['your_question'] = $your_question;
        $data_insert['answer'] = $array_register['answer'];
        $data_insert['checknum'] = $checknum;
        $data_insert['users_info'] = nv_base64_encode(serialize($query_field));

        $userid = $db->insert_id($sql, 'userid', $data_insert);

        if (!$userid) {
            die(reg_result(array(
                'status' => 'error',
                'input' => '',
                'mess' => $lang_module['err_no_save_account']
            )));
        } else {
            if ($global_config['allowuserreg'] == 2) {
                $subject = $lang_module['account_active'];
                $message = sprintf($lang_module['account_active_info'], $array_register['first_name'], $global_config['site_name'], NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=active&userid=' . $userid . '&checknum=' . $checknum, $array_register['username'], $array_register['email'], nv_date('H:i d/m/Y', NV_CURRENTTIME + 86400));
                $send = nv_sendmail($global_config['site_email'], $array_register['email'], $subject, $message);

                if ($send) {
                    $info = $lang_module['account_active_mess'];
                } else {
                    $info = $lang_module['account_active_mess_error_mail'];
                }
            } else {
                $info = $lang_module['account_register_to_admin'];
            }

            $nv_redirect = '';
            die(reg_result(array(
                'status' => 'ok',
                'input' => '',
                'mess' => $info
            )));
        }
    } else {
        $sql = "INSERT INTO " . NV_MOD_TABLE . "
		(group_id, username, md5username, password, email, first_name, last_name, gender, photo, birthday, sig, regdate,
		question, answer, passlostkey, view_mail, remember, in_groups,
		active, checknum, last_login, last_ip, last_agent, last_openid, idsite) VALUES (
        " . (defined('ACCESS_ADDUS') ? $group_id : ($global_users_config['active_group_newusers'] ? 7 : 4)) . ",
		:username,
		:md5username,
		:password,
		:email,
		:first_name,
		:last_name,
		:gender
		, '',
		:birthday,
		:sig,
		 " . NV_CURRENTTIME . ",
		:your_question,
		:answer,
		'', 0, 1,
		'" . (defined('ACCESS_ADDUS') ? $group_id : ($global_users_config['active_group_newusers'] ? 7 : 4)) . "',
		1, '', 0, '', '', '', " . $global_config['idsite'] . ")";

        $data_insert = array();
        $data_insert['username'] = $array_register['username'];
        $data_insert['md5username'] = nv_md5safe($array_register['username']);
        $data_insert['password'] = $password;
        $data_insert['email'] = $array_register['email'];
        $data_insert['first_name'] = $array_register['first_name'];
        $data_insert['last_name'] = $array_register['last_name'];
        $data_insert['your_question'] = $your_question;
        $data_insert['answer'] = $array_register['answer'];
        $data_insert['gender'] = $array_register['gender'];
        $data_insert['birthday'] = $array_register['birthday'];
        $data_insert['sig'] = $array_register['sig'];

        $userid = $db->insert_id($sql, 'userid', $data_insert);

        if (!$userid) {
            die(reg_result(array(
                'status' => 'error',
                'input' => '',
                'mess' => $lang_module['err_no_save_account']
            )));
        } else {
            $query_field['userid'] = $userid;
            $db->query('INSERT INTO ' . NV_MOD_TABLE . '_info (' . implode(', ', array_keys($query_field)) . ') VALUES (' . implode(', ', array_values($query_field)) . ')');

            if (defined('ACCESS_ADDUS')) {
                $db->query('INSERT INTO ' . NV_MOD_TABLE . '_groups_users (group_id, userid, is_leader, approved, data) VALUES (' . $group_id . ',' . $userid . ', 0, 1, \'0\')');
            }

            $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers+1 WHERE group_id=' . (defined('ACCESS_ADDUS') ? $group_id : ($global_users_config['active_group_newusers'] ? 7 : 4)));
            $subject = $lang_module['account_register'];
            $_url = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true);
            if (strpos($_url, NV_MY_DOMAIN) !== 0) {
                $_url = NV_MY_DOMAIN . $_url;
            }
            $message = sprintf($lang_module['account_register_info'], $array_register['first_name'], $global_config['site_name'], $_url, $array_register['username']);
            nv_sendmail($global_config['site_email'], $array_register['email'], $subject, $message);

            if (defined('ACCESS_ADDUS')) {
                $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=groups/' . $group_id;
            } else if (!empty($global_config['auto_login_after_reg'])) {
                // Auto login
                $array_user = array(
                    'userid' => $userid,
                    'last_agent' => '',
                    'last_ip' => '',
                    'last_login' => 0,
                    'last_openid' => ''
                );
                validUserLog($array_user, 1, '');

                $nv_redirect = nv_redirect_decrypt($nv_redirect);
                $url = !empty($nv_redirect) ? $nv_redirect : NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
            } else {
                $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=login';
                if (!empty($nv_redirect)) {
                    $url .= '&nv_redirect=' . $nv_redirect;
                }
            }
            $nv_Cache->delMod($module_name);
            $nv_redirect = '';
            die(reg_result(array(
                'status' => 'ok',
                'input' => nv_url_rewrite($url, true),
                'mess' => $lang_module['register_ok']
            )));
        }
    }
}

if ($nv_Request->isset_request('get_usage_terms', 'post')) {
    include NV_ROOTDIR . '/includes/header.php';
    echo $global_users_config['siteterms_' . NV_LANG_DATA];
    include NV_ROOTDIR . '/includes/footer.php';
}

$contents = user_register($gfx_chk, $array_register['checkss'], $data_questions, $array_field_config, $custom_fields, $group_id);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
