<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

if (defined('NV_IS_USER')) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

if (defined('NV_IS_USER_FORUM')) {
    require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/lostpass.php';
    exit();
}

if ($global_config['allowuserreg'] != 2) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$page_title = $mod_title = $lang_module['lostpass_page_title'];
$key_words = $module_info['keywords'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

$array_gfx_chk = !empty($global_config['captcha_area']) ? explode(',', $global_config['captcha_area']) : [];
$gfx_chk = (!empty($array_gfx_chk) and in_array('m', $array_gfx_chk, true)) ? 1 : 0;

$data = [];
$data['checkss'] = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op);
$data['userField'] = nv_substr($nv_Request->get_title('userField', 'post', '', 1), 0, 100);
$data['answer'] = nv_substr($nv_Request->get_title('answer', 'post', '', 1), 0, 255);
$data['send'] = $nv_Request->get_bool('send', 'post', false);
if ($module_captcha == 'recaptcha') {
    $data['nv_seccode'] = $nv_Request->get_title('g-recaptcha-response', 'post', '');
    $data['nv_seccode2'] = $nv_Request->get_title('nv_seccode', 'post', '');
} elseif ($module_captcha == 'captcha') {
    $data['nv_seccode'] = $data['nv_seccode2'] = $nv_Request->get_title('nv_seccode', 'post', '');
}
$checkss = $nv_Request->get_title('checkss', 'post', '');

$seccode = $nv_Request->get_string('lostactivelink_seccode', 'session', '');

$step = 1;
$error = $question = '';

if ($checkss == $data['checkss']) {
    $check_seccode = ($gfx_chk and isset($data['nv_seccode'])) ? ((!empty($seccode) and md5($data['nv_seccode2']) == $seccode) or nv_capcha_txt($data['nv_seccode'], $module_captcha)) : true;
    if ($check_seccode) {
        if (!empty($data['userField'])) {
            $check_email = nv_check_valid_email($data['userField'], true);
            $check_login = nv_check_valid_login($data['userField'], $global_config['nv_unickmax'], $global_config['nv_unickmin']);

            if (!empty($check_email[0]) and !empty($check_login)) {
                $step = 1;
                $nv_Request->unset_request('lostactivelink_seccode', 'session');
                $error = $lang_module['lostactivelink_no_info2'];
            } else {
                // Xác định thành viên đăng ký chờ kích hoạt trong vòng 1 ngày
                $exp = NV_CURRENTTIME - 86400;
                if (empty($check_email[0])) {
                    $userField = $check_email[1];
                    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_reg WHERE email= :userField AND regdate>' . $exp;
                } else {
                    $userField = $data['userField'];
                    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_reg WHERE username= :userField AND regdate>' . $exp;
                }
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':userField', $userField, PDO::PARAM_STR);
                $stmt->execute();
                $row = $stmt->fetch();

                if (!empty($row)) {
                    $step = 2;
                    if (empty($seccode)) {
                        $nv_Request->set_Session('lostactivelink_seccode', md5($data['nv_seccode']));
                    }
                    $question = $row['question'];

                    // Kiểm tra xem hệ thống có yêu cầu nhập câu hỏi bảo mật và câu trả lời không
                    $array_field_config = nv_get_users_field_config();
                    $is_question_require = true;
                    if (isset($array_field_config['question']) and isset($array_field_config['answer']) and empty($array_field_config['question']['required']) and empty($array_field_config['answer']['required'])) {
                        $is_question_require = false;
                    }

                    if ($is_question_require) {
                        $info = '';
                        if (empty($row['question']) or empty($row['answer'])) {
                            $info = $lang_module['lostactivelink_question_empty'];
                        }

                        if (!empty($info)) {
                            $nv_Request->unset_request('lostactivelink_seccode', 'session');

                            $contents = user_info_exit($info);
                            $contents .= '<meta http-equiv="refresh" content="15;url=' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true) . '" />';

                            include NV_ROOTDIR . '/includes/header.php';
                            echo nv_site_theme($contents);
                            include NV_ROOTDIR . '/includes/footer.php';
                        }
                    }

                    if ($data['send'] or !$is_question_require) {
                        if ($data['answer'] == $row['answer'] or !$is_question_require) {
                            $nv_Request->unset_request('lostactivelink_seccode', 'session');

                            $rand = rand($global_config['nv_upassmin'], $global_config['nv_upassmax']);
                            $password_new = nv_genpass($rand);
                            $checknum = nv_genpass(10);
                            $checknum = md5($checknum);

                            $subject = $lang_module['lostactive_mailtitle'];
                            $_url = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=active&userid=' . $row['userid'] . '&checknum=' . $checknum, true);
                            $message = sprintf($lang_module['lostactive_active_info'], $row['first_name'], $global_config['site_name'], $_url, $row['username'], $row['email'], $password_new, nv_date('H:i d/m/Y', $row['regdate'] + 86400));
                            $ok = nv_sendmail([
                                $global_config['site_name'],
                                $global_config['site_email']
                            ], $row['email'], $subject, $message);

                            if ($ok) {
                                $password = $crypt->hash_password($password_new, $global_config['hashprefix']);
                                $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_reg SET password= :password, checknum= :checknum WHERE userid=' . $row['userid']);
                                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                                $stmt->bindParam(':checknum', $checknum, PDO::PARAM_STR);
                                $stmt->execute();
                                $info = sprintf($lang_module['lostactivelink_send'], $row['email']);
                            } else {
                                $info = $lang_global['error_sendmail'];
                            }

                            $contents = user_info_exit($info);
                            $contents .= '<meta http-equiv="refresh" content="5;url=' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true) . '" />';

                            include NV_ROOTDIR . '/includes/header.php';
                            echo nv_site_theme($contents);
                            include NV_ROOTDIR . '/includes/footer.php';
                        } else {
                            $step = 2;
                            // Pass bước 1 thì lưu mã xác nhận lại thành 1 dạng để kiểm tra session
                            $data['nv_seccode'] = $data['nv_seccode2'];
                            $error = $lang_module['answer_failed'];
                        }
                    }
                } else {
                    $step = 1;
                    $nv_Request->unset_request('lostactivelink_seccode', 'session');
                    $error = $lang_module['lostactivelink_no_info2'];
                }
            }
        } else {
            $step = 1;
            $nv_Request->unset_request('lostactivelink_seccode', 'session');
            $error = $lang_module['lostactivelink_no_info1'];
        }
    } else {
        $step = 1;
        $nv_Request->unset_request('lostactivelink_seccode', 'session');
        $error = $lang_global['securitycodeincorrect'];
    }
}

if ($step == 2) {
    $data['step'] = 2;
    $data['info'] = empty($error) ? $lang_module['step2'] : '<span style="color:#fb490b;">' . $error . '</span>';
} else {
    $data['step'] = 1;
    $data['info'] = empty($error) ? $lang_module['step1'] : '<span style="color:#fb490b;">' . $error . '</span>';
}

$contents = user_lostactivelink($data, $question);

$canonicalUrl = getCanonicalUrl($page_url);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
