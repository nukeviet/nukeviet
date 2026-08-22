<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

/**
 * Giao diện đăng ký tài khoản
 *
 * @param bool   $gfx_chk
 * @param string $checkss
 * @param array  $data_questions
 * @param array  $array_field_config
 * @param array  $custom_fields
 * @param int    $group_id
 * @return string
 */
function user_register($gfx_chk, $checkss, $data_questions, $array_field_config, $custom_fields, $group_id)
{
    global $module_info, $global_config, $nv_Lang, $module_name, $module_captcha, $op, $nv_redirect, $global_array_genders, $global_users_config;

    $tpl = new \NukeViet\Template\NVSmarty();
    [$template, $dir] = get_module_tpl_dir('register.tpl', true);
    $tpl->setTemplateDir($dir);

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('TEMPLATE', $template);
    $tpl->assign('CHECKSS', $checkss);
    $tpl->assign('NV_REDIRECT', $nv_redirect);

    // Action
    $user_register = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=register';
    if ($group_id != 0) {
        $user_register .= '/' . $group_id;
    }
    $tpl->assign('USER_REGISTER', $user_register);

    // Hiển thị điều khoản khi đăng ký thông thường, không phải trưởng nhóm tạo tài khoản
    $show_agreecheck = $group_id == 0 || (defined('NV_IS_USER') && !defined('ACCESS_ADDUS'));
    $tpl->assign('SHOW_AGREECHECK', $show_agreecheck);

    // Captcha attributes
    $tpl->assign('CAPTCHA_ATTRS', $gfx_chk ? nv_captcha_form_attrs('nv_seccode') : '');

    // Link lấy lại liên kết kích hoạt
    $tpl->assign('LOSTACTIVELINK', $global_config['allowuserreg'] == 2 ? NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostactivelink' : '');

    $username_rule = empty($global_config['nv_unick_type']) ? $nv_Lang->getGlobal('username_rule_nolimit', $global_config['nv_unickmin'], $global_config['nv_unickmax']) : $nv_Lang->getGlobal('username_rule_limit', $nv_Lang->getGlobal('unick_type_' . $global_config['nv_unick_type']), $global_config['nv_unickmin'], $global_config['nv_unickmax']);
    $password_rule = empty($global_config['nv_upass_type']) ? $nv_Lang->getGlobal('password_rule_nolimit', $global_config['nv_upassmin'], $global_config['nv_upassmax']) : $nv_Lang->getGlobal('password_rule_limit', $nv_Lang->getGlobal('upass_type_' . $global_config['nv_upass_type']), $global_config['nv_upassmin'], $global_config['nv_upassmax']);
    $password_pattern = '/^';
    if ($global_config['nv_upass_type'] == 1) {
        $password_pattern .= "(?=.*[a-zA-Z])(?=.*\d)";
    } elseif ($global_config['nv_upass_type'] == 2) {
        $password_pattern .= "(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W\_])";
    } elseif ($global_config['nv_upass_type'] == 3) {
        $password_pattern .= "(?=.*[a-z])(?=.*[A-Z])(?=.*\d)";
    } elseif ($global_config['nv_upass_type'] == 4) {
        $password_pattern .= "(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W\_])";
    }
    $password_pattern .= '(.){' . $global_config['nv_upassmin'] . ',' . $global_config['nv_upassmax'] . '}$/';

    $tpl->assign('USERNAME_RULE', $username_rule);
    $tpl->assign('PASSWORD_RULE', $password_rule);
    $tpl->assign('PASSWORD_PATTERN', $password_pattern);

    // Có trường nào có kiểu ngày tháng hay không
    $datepicker = false;
    // Các trường hệ thống hiển thị độc lập (first_name, last_name, gender, birthday, sig, question, answer)
    $system_fields = [];
    // Các trường tùy chỉnh hiển thị theo vòng lặp
    $custom_fields_list = [];

    foreach ($array_field_config as $_k => $row) {
        if (empty($row['show_register'])) {
            continue;
        }
        $row['customID'] = $_k;

        // Value luôn là giá trị mặc định
        if (!empty($row['field_choices'])) {
            if ($row['field_type'] == 'date') {
                $row['value'] = ($row['field_choices']['current_date']) ? NV_CURRENTTIME : $row['default_value'];
            } elseif ($row['field_type'] == 'number') {
                $row['value'] = $row['default_value'];
            } else {
                $temp = array_keys($row['field_choices']);
                $tempkey = (int) ($row['default_value']) - 1;
                $row['value'] = $temp[$tempkey] ?? '';
            }
        } else {
            $row['value'] = get_value_by_lang($row['default_value']);
        }
        $row['required'] = (bool) $row['required'];
        $row['callfunc'] = '';
        $row['errmess'] = fieldErrorMessage($row);

        if (!empty($row['system'])) {
            // Trường hệ thống
            if ($row['field'] == 'birthday') {
                $row['value'] = nv_u2d_post($row['value']);
                $row['min_old_user'] = $global_users_config['min_old_user'];
                $datepicker = true;
            } elseif ($row['field'] == 'sig') {
                $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
            }
            if ($row['match_type'] == 'unicodename') {
                $row['callfunc'] = $row['required'] ? 'required_person_name_check' : 'person_name_check';
                $row['errmess'] = $row['required'] ? $nv_Lang->getModule('field_req_uname_error') : $nv_Lang->getModule('field_uname_error');
            }
            if ($row['field'] == 'gender') {
                $genders = [];
                foreach ($global_array_genders as $gender) {
                    $gender['checked'] = ($row['value'] == $gender['key']);
                    $genders[] = $gender;
                }
                $row['genders'] = $genders;
            }
            $system_fields[$row['field']] = $row;
        } else {
            // Trường tùy chỉnh
            $row['is_editor'] = false;
            $row['editor'] = '';
            $row['choices'] = [];
            if ($row['field_type'] == 'date') {
                $row['value'] = nv_u2d_post($row['value']);
                $datepicker = true;
            } elseif ($row['field_type'] == 'textarea') {
                $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
            } elseif ($row['field_type'] == 'editor') {
                $row['value'] = htmlspecialchars(nv_editor_br2nl($row['value']));
                if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
                    $array_tmp = explode('@', $row['class']);
                    $row['editor'] = nv_aleditor('custom_fields[' . $row['field'] . ']', $array_tmp[0], $array_tmp[1], $row['value'], 'User');
                    $row['is_editor'] = true;
                } else {
                    // Không có quyền dùng trình soạn thảo, hạ về textarea
                    $row['class'] = '';
                    $row['field_type'] = 'textarea';
                }
            } elseif ($row['field_type'] == 'select') {
                foreach ($row['field_choices'] as $key => $value) {
                    $row['choices'][] = [
                        'key' => $key,
                        'selected' => ($key == $row['value']),
                        'value' => get_value_by_lang2($key, $value)
                    ];
                }
            } elseif ($row['field_type'] == 'radio') {
                $number = 0;
                foreach ($row['field_choices'] as $key => $value) {
                    $row['choices'][] = [
                        'id' => $row['fid'] . '_' . $number++,
                        'key' => $key,
                        'checked' => ($key == $row['value']),
                        'value' => get_value_by_lang2($key, $value)
                    ];
                }
            } elseif ($row['field_type'] == 'checkbox') {
                $number = 0;
                $valuecheckbox = (!empty($row['value'])) ? explode(',', $row['value']) : [];
                foreach ($row['field_choices'] as $key => $value) {
                    $row['choices'][] = [
                        'id' => $row['fid'] . '_' . $number++,
                        'key' => $key,
                        'checked' => in_array((string) $key, $valuecheckbox, true),
                        'value' => get_value_by_lang2($key, $value)
                    ];
                }
            } elseif ($row['field_type'] == 'multiselect') {
                $valueselect = (!empty($row['value'])) ? explode(',', $row['value']) : [];
                foreach ($row['field_choices'] as $key => $value) {
                    $row['choices'][] = [
                        'key' => $key,
                        'selected' => in_array((string) $key, $valueselect, true),
                        'value' => get_value_by_lang2($key, $value)
                    ];
                }
            } elseif ($row['field_type'] == 'file') {
                $row['limited_values'] = !empty($row['limited_values']) ? json_decode($row['limited_values'], true) : [];
                $row['fileaccept'] = !empty($row['limited_values']['mime']) ? '.' . implode(',.', $row['limited_values']['mime']) : '';
                $row['filemaxsize'] = $row['limited_values']['file_max_size'] ?? 0;
                $row['filemaxsize_format'] = nv_convertfromBytes($row['limited_values']['file_max_size'] ?? 0);
                $row['filemaxnum'] = $row['limited_values']['maxnum'] ?? 0;
                $row['csrf'] = csrf_create($module_name . '_field_' . $row['field']);
                $row['url_module'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
                $row['widthlimit'] = image_size_info($row['limited_values']['widthlimit'], 'width');
                $row['heightlimit'] = image_size_info($row['limited_values']['heightlimit'], 'height');
            }
            if (($row['field_type'] == 'textbox' or $row['field_type'] == 'number') and $row['match_type'] == 'unicodename') {
                $row['callfunc'] = $row['required'] ? 'required_person_name_check' : 'person_name_check';
            }

            // Các giá trị phục vụ validate, giao diện tự quyết định gắn vào thuộc tính nào.
            // Tính sau cùng vì field_type có thể đã đổi khi editor bị hạ về textarea ở trên
            $row['errmess'] = fieldErrorMessage($row);
            $row['pattern'] = '';
            $row['number_type'] = 0;
            $row['min_date'] = $row['max_date'] = '';

            if ($row['field_type'] == 'number') {
                $row['number_type'] = !empty($row['field_choices']['number_type']) ? (int) $row['field_choices']['number_type'] : 1;
                $row['pattern'] = ($row['number_type'] == 1) ? '/^-?[0-9]+$/' : '/^-?[0-9]+([.,][0-9]+)?$/';
            } elseif ($row['field_type'] == 'date') {
                // Khoảng ngày là tùy chọn, min_length = 0 nghĩa là không giới hạn
                if ($row['min_length'] > 0 and $row['max_length'] > $row['min_length']) {
                    $row['min_date'] = nv_u2d_post($row['min_length']);
                    $row['max_date'] = nv_u2d_post($row['max_length']);
                }
            } elseif ($row['match_type'] == 'alphanumeric') {
                $row['pattern'] = '/^[a-zA-Z0-9_]+$/';
            } elseif ($row['match_type'] == 'url') {
                $row['pattern'] = '/^https?:\/\/[^\s\/$.?#][^\s]*$/i';
            } elseif ($row['match_type'] == 'regex' and !empty($row['match_regex'])) {
                // Biểu thức do quản trị nhập, giao diện tự bỏ qua nếu trình duyệt không dịch được
                $row['pattern'] = $row['match_regex'];
            }

            $custom_fields_list[] = $row;
        }
    }

    $tpl->assign('SYSTEM', $system_fields);
    $tpl->assign('FIELDS', $custom_fields_list);
    $tpl->assign('QUESTIONS', $data_questions);
    $tpl->assign('DATEPICKER', $datepicker);

    // Menu điều hướng cuối form
    $_lis = \NukeViet\Module\users\Shared\Navs::getNavs($module_info['funcs']);
    $_alias = $module_info['alias'];
    $navs = [];
    foreach ($_lis as $_li) {
        if ($_li['func_name'] == $op) {
            continue;
        }

        $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
        if (!empty($nv_redirect)) {
            $href .= '&nv_redirect=' . $nv_redirect;
        }
        $navs[] = [
            'href' => $href,
            'title' => $_li['func_name'] == 'main' ? $module_info['custom_title'] : $_li['func_custom_name']
        ];
    }
    $tpl->assign('NAVS', $navs);

    return $tpl->fetch('register.tpl');
}

/**
 * Giao diện đăng nhập
 *
 * @param bool $is_ajax
 * @return string
 */
function user_login(bool $is_ajax = false): string
{
    global $module_info, $global_config, $nv_Lang, $module_name, $module_captcha, $op, $nv_header, $nv_redirect, $page_url;

    // Lấy lang phù hợp với kiểu đăng nhập
    $method = (preg_match('/^([^0-9]+[a-z0-9\_]+)$/', $global_config['login_name_type']) and module_file_exists('users/methods/' . $global_config['login_name_type'] . '.php')) ? $global_config['login_name_type'] : 'username';
    if ($nv_Lang->existsGlobal('login_name_type_' . $method)) {
        $nv_Lang->setGlobal('username_email', $nv_Lang->getGlobal('login_name_type_' . $method));
    } elseif ($nv_Lang->existsGlobal($method)) {
        $nv_Lang->setGlobal('username_email', $nv_Lang->getGlobal($method));
    } elseif ($nv_Lang->existsModule($method)) {
        $nv_Lang->setGlobal('username_email', $nv_Lang->getModule($method));
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl_file = $is_ajax ? 'login_ajax.tpl' : 'login.tpl';
    [$template, $dir] = get_module_tpl_dir($tpl_file, true);
    $tpl->setTemplateDir($dir);
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('TEMPLATE', $template);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('CSS_JS', addition_module_assets($module_name, 'both', false));
    $tpl->assign('CSRF', md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op));

    $array_gfx_chk = !empty($global_config['captcha_area']) ? explode(',', $global_config['captcha_area']) : [];
    $gfx_chk = (!empty($array_gfx_chk) and in_array('l', $array_gfx_chk, true)) ? 1 : 0;

    $tpl->assign('CAPTCHA_ATTRS', $gfx_chk ? nv_captcha_form_attrs('nv_seccode') : '');
    $tpl->assign('NV_REDIRECT', $nv_redirect);
    $tpl->assign('DEFAULT_REDIRECT', nv_redirect_encrypt(empty($page_url) ? NV_MY_DOMAIN : urlRewriteWithDomain($page_url, NV_MY_DOMAIN)));
    $tpl->assign('NV_HEADER', $nv_header);
    $tpl->assign('OAUTH_CHECKSS', csrf_create($module_name . '_oauth'));

    // Xử lý giao diện nav cuối form
    $_lis = \NukeViet\Module\users\Shared\Navs::getNavs($module_info['funcs']);
    $_alias = $module_info['alias'];
    $navs = [];
    foreach ($_lis as $_li) {
        if ($_li['func_name'] == $op) {
            continue;
        }

        $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
        if (!empty($nv_redirect)) {
            $href .= '&nv_redirect=' . $nv_redirect;
        }
        $navs[] = [
            'href' => $href,
            'title' => $_li['func_name'] == 'main' ? $module_info['custom_title'] : $_li['func_custom_name']
        ];
    }
    $tpl->assign('NAVS', $navs);

    return $tpl->fetch($tpl_file);
}

/**
 * user_openid_login()
 *
 * @param mixed $attribs
 * @param array $op_process
 * @return string
 */
function user_openid_login($attribs, $op_process)
{
    global $global_config, $nv_Lang, $module_name, $nv_redirect, $page_title;

    $xtpl = new XTemplate('openid_login.tpl', get_module_tpl_dir('openid_login.tpl'));

    $reg_username = '';
    $reg_email = '';
    if (!empty($attribs['contact/email'])) {
        $reg_email = $attribs['contact/email'];
        $reg_username = create_username_from_email($reg_email);
    }
    $xtpl->assign('USER_LOGIN', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;server=' . $attribs['server'] . '&amp;result=1');
    $xtpl->assign('USER_NAME', $reg_username);
    $xtpl->assign('USER_EMAIL', $reg_email);
    $xtpl->assign('NICK_MAXLENGTH', $global_config['nv_unickmax']);
    $xtpl->assign('PASS_MAXLENGTH', $global_config['nv_upassmax']);
    $xtpl->assign('PASS_MINLENGTH', $global_config['nv_upassmin']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('PAGETITLE', $page_title);

    $op_process_count = count($op_process);
    $first = array_key_first($op_process);
    if ($op_process_count > 1) {
        foreach ($op_process as $process => $val) {
            $xtpl->assign('ACTION', [
                'key' => $process,
                'name' => $nv_Lang->getModule('openid_processing_' . $process)
            ]);
            $xtpl->parse('main.choose_action.option');
        }
        $xtpl->parse('main.choose_action');

        $info = $nv_Lang->getModule('openid_note');
    } else {
        $info = $nv_Lang->getModule('openid_' . $first . '_note');
    }
    if (empty($reg_email) and str_contains($global_config['openid_processing'], 'auto') and !in_array('auto', $op_process, true) and !empty($global_config['allowuserreg'])) {
        $info = $nv_Lang->getModule('openid_without_email_note') . ' ' . $info;
    }

    $xtpl->assign('INFO', $info . ':');

    $xtpl->assign('REDIRECT', $nv_redirect);

    if (isset($op_process['connect'])) {
        if ($first != 'connect') {
            $xtpl->parse('main.userlogin.isHide');
        }
        if (!empty($nv_redirect)) {
            $xtpl->parse('main.userlogin.redirect');
        }
        $xtpl->parse('main.userlogin');
    }

    if (isset($op_process['create'])) {
        if ($first != 'create') {
            $xtpl->parse('main.allowuserreg.isHide');
        }
        if (!empty($nv_redirect)) {
            $xtpl->parse('main.allowuserreg.redirect');
        }
        if (!empty($reg_email)) {
            $xtpl->parse('main.allowuserreg.readonly');
        } else {
            $xtpl->parse('main.allowuserreg.email_verify');
        }
        $xtpl->parse('main.allowuserreg');
    }

    if (isset($op_process['auto'])) {
        if (!empty($nv_redirect)) {
            $xtpl->parse('main.auto.redirect');
        }
        $xtpl->parse('main.auto');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * user_lostpass()
 *
 * @param array $data
 * @return string
 */
function user_lostpass($data)
{
    global $module_info, $global_config, $nv_Lang, $module_name, $module_captcha, $op, $nv_redirect;

    $xtpl = new XTemplate('lostpass.tpl', get_module_tpl_dir('lostpass.tpl'));

    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('DATA', $data);
    $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostpass');

    $password_rule = empty($global_config['nv_upass_type']) ? $nv_Lang->getGlobal('password_rule_nolimit', $global_config['nv_upassmin'], $global_config['nv_upassmax']) : $nv_Lang->getGlobal('password_rule_limit', $nv_Lang->getGlobal('upass_type_' . $global_config['nv_upass_type']), $global_config['nv_upassmin'], $global_config['nv_upassmax']);
    $password_pattern = '/^';
    if ($global_config['nv_upass_type'] == 1) {
        $password_pattern .= "(?=.*[a-zA-Z])(?=.*\d)";
    } elseif ($global_config['nv_upass_type'] == 2) {
        $password_pattern .= "(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W\_])";
    } elseif ($global_config['nv_upass_type'] == 3) {
        $password_pattern .= "(?=.*[a-z])(?=.*[A-Z])(?=.*\d)";
    } elseif ($global_config['nv_upass_type'] == 4) {
        $password_pattern .= "(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W\_])";
    }
    $password_pattern .= '(.){' . $global_config['nv_upassmin'] . ',' . $global_config['nv_upassmax'] . '}$/';

    $xtpl->assign('PASSWORD_PATTERN', $password_pattern);
    $xtpl->assign('PASSWORD_RULE', $password_rule);
    $xtpl->assign('PASS_MAXLENGTH', $global_config['nv_upassmax']);

    $array_gfx_chk = !empty($global_config['captcha_area']) ? explode(',', $global_config['captcha_area']) : [];

    if (!empty($array_gfx_chk) and in_array('p', $array_gfx_chk, true)) {
        // Nếu dùng reCaptcha v3
        if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
            $xtpl->parse('main.recaptcha3');
        }
        // Nếu dùng reCaptcha v2
        elseif ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
            $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
            $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode1'));
            $xtpl->parse('main.recaptcha');
        } elseif ($module_captcha == 'turnstile') {
            $xtpl->parse('main.turnstile');
        } elseif ($module_captcha == 'captcha') {
            $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode'));
            $xtpl->parse('main.captcha');
        }
    }

    if (!empty($nv_redirect)) {
        $xtpl->assign('REDIRECT', $nv_redirect);
        $xtpl->parse('main.redirect');
    }

    $_lis = \NukeViet\Module\users\Shared\Navs::getNavs($module_info['funcs']);
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        if ($_li['func_name'] == $op) {
            continue;
        }

        $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
        if (!empty($nv_redirect)) {
            $href .= '&nv_redirect=' . $nv_redirect;
        }
        $li = [
            'href' => $href,
            'title' => $_li['func_name'] == 'main' ? $module_info['custom_title'] : $_li['func_custom_name']
        ];
        $xtpl->assign('NAVBAR', $li);
        $xtpl->parse('main.navbar');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * user_lostactivelink()
 *
 * @param array  $data
 * @param string $question
 * @return string
 */
function user_lostactivelink($data, $question)
{
    global $module_info, $global_config, $nv_Lang, $module_name, $module_captcha, $op;

    $xtpl = new XTemplate('lostactivelink.tpl', get_module_tpl_dir('lostactivelink.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('DATA', $data);

    if ($data['step'] == 2) {
        $xtpl->assign('FORM2_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostactivelink');
        $xtpl->assign('QUESTION', $question);
        $xtpl->parse('main.step2');
    } else {
        $xtpl->assign('FORM1_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostactivelink');

        $array_gfx_chk = !empty($global_config['captcha_area']) ? explode(',', $global_config['captcha_area']) : [];

        if (!empty($array_gfx_chk) and in_array('m', $array_gfx_chk, true)) {
            if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
                $xtpl->parse('main.step1.recaptcha3');
            } elseif ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
                $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
                $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode1'));
                $xtpl->parse('main.step1.recaptcha');
            } elseif ($module_captcha == 'turnstile') {
                $xtpl->parse('main.step1.turnstile');
            } elseif ($module_captcha == 'captcha') {
                $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode'));
                $xtpl->parse('main.step1.captcha');
            }
        }

        $xtpl->parse('main.step1');
    }

    $_lis = \NukeViet\Module\users\Shared\Navs::getNavs($module_info['funcs']);
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        if ($_li['func_name'] == $op) {
            continue;
        }

        $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
        $li = [
            'href' => $href,
            'title' => $_li['func_name'] == 'main' ? $module_info['custom_title'] : $_li['func_custom_name']
        ];
        $xtpl->assign('NAVBAR', $li);
        $xtpl->parse('main.navbar');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * user_info()
 *
 * @param array $data
 * @param array $array_field_config
 * @param array $custom_fields
 * @param array $types
 * @param array $data_questions
 * @param array $data_openid
 * @param array $groups
 * @param bool  $pass_empty
 * @return string
 */
function user_info($data, $array_field_config, $custom_fields, $types, $data_questions, $data_openid, $groups, $pass_empty)
{
    global $module_info, $global_config, $nv_Lang, $module_name, $op, $global_array_genders, $is_custom_field, $user_info, $global_users_config, $group_lists, $group_id, $language_array, $client_info;

    [$template, $dir] = get_module_tpl_dir('info.tpl', true);
    $template_js = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], NV_DEFAULT_SITE_THEME, 'js/users.passkey.js');

    $xtpl = new XTemplate('info.tpl', $dir);

    if (defined('ACCESS_EDITUS')) {
        $xtpl->assign('EDITINFO_FORM', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/' . $data['group_id'] . '/' . $data['userid']);
    } else {
        $xtpl->assign('EDITINFO_FORM', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo');
    }

    $xtpl->assign('AVATAR_DEFAULT', NV_STATIC_URL . 'themes/' . $template . '/images/' . $module_info['module_theme'] . '/no_avatar.png');
    $xtpl->assign('URL_AVATAR', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=avatar/src', true));
    $xtpl->assign('TEMPLATE', $template);
    $xtpl->assign('TEMPLATE_JS', $template_js);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('NICK_MAXLENGTH', $global_config['nv_unickmax']);
    $xtpl->assign('NICK_MINLENGTH', $global_config['nv_unickmin']);
    $xtpl->assign('PASS_MAXLENGTH', $global_config['nv_upassmax']);
    $xtpl->assign('PASS_MINLENGTH', $global_config['nv_upassmin']);
    $xtpl->assign('LOGINTYPE', $global_config['nv_unick_type']);

    $xtpl->assign('URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=');
    $xtpl->assign('URL_MODULE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);

    $username_rule = empty($global_config['nv_unick_type']) ? $nv_Lang->getGlobal('username_rule_nolimit', $global_config['nv_unickmin'], $global_config['nv_unickmax']) : $nv_Lang->getGlobal('username_rule_limit', $nv_Lang->getGlobal('unick_type_' . $global_config['nv_unick_type']), $global_config['nv_unickmin'], $global_config['nv_unickmax']);
    $password_rule = empty($global_config['nv_upass_type']) ? $nv_Lang->getGlobal('password_rule_nolimit', $global_config['nv_upassmin'], $global_config['nv_upassmax']) : $nv_Lang->getGlobal('password_rule_limit', $nv_Lang->getGlobal('upass_type_' . $global_config['nv_upass_type']), $global_config['nv_upassmin'], $global_config['nv_upassmax']);
    $password_pattern = '/^';
    if ($global_config['nv_upass_type'] == 1) {
        $password_pattern .= "(?=.*[a-zA-Z])(?=.*\d)";
    } elseif ($global_config['nv_upass_type'] == 2) {
        $password_pattern .= "(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W\_])";
    } elseif ($global_config['nv_upass_type'] == 3) {
        $password_pattern .= "(?=.*[a-z])(?=.*[A-Z])(?=.*\d)";
    } elseif ($global_config['nv_upass_type'] == 4) {
        $password_pattern .= "(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W\_])";
    }
    $password_pattern .= '(.){' . $global_config['nv_upassmin'] . ',' . $global_config['nv_upassmax'] . '}$/';

    $xtpl->assign('PASSWORD_PATTERN', $password_pattern);
    $xtpl->assign('USERNAME_RULE', $username_rule);
    $xtpl->assign('PASSWORD_RULE', $password_rule);

    $xtpl->assign('DATA', $data);
    if ($pass_empty) {
        $xtpl->assign('FORM_HIDDEN', ' hidden d-none');
    }

    if ((int) $user_info['pass_reset_request'] == 2 and $data['type'] != 'password') {
        $xtpl->assign('CHANGEPASS_INFO', $nv_Lang->getModule('pass_reset2_info', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/password'));
        $xtpl->parse('main.changepass_request2');
    }

    if ((int) $user_info['email_reset_request'] == 2 and $data['type'] != 'email') {
        $xtpl->assign('CHANGEEMAIL_INFO', $nv_Lang->getModule('email_reset2_info', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/email'));
        $xtpl->parse('main.changeemail_request2');
    }

    // Thông tin cơ bản
    $array_basic_key = [
        'first_name',
        'last_name',
        'gender',
        'birthday',
        'sig'
    ];
    $datepicker = false;
    foreach ($array_basic_key as $key) {
        // Không tồn tại có nghĩa là không cho phép sửa
        if (isset($array_field_config[$key])) {
            $row = $array_field_config[$key];
            $row['value'] = (isset($custom_fields[$row['field']])) ? $custom_fields[$row['field']] : '';
            $row['required'] = ($row['required']) ? 'required' : '';
            if ($row['field'] == 'birthday') {
                $row['value'] = nv_u2d_post($row['value']);
                $row['min_old_user'] = $global_users_config['min_old_user'];
                $datepicker = true;
            } elseif ($row['field'] == 'sig') {
                $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
            }
            $xtpl->assign('FIELD', $row);
            if ($row['field'] == 'first_name' or $row['field'] == 'last_name') {
                $show_key = 'name_show_' . $global_config['name_show'] . '.show_' . $row['field'];
            } else {
                $show_key = 'show_' . $row['field'];
            }
            if ($row['required']) {
                $xtpl->parse('main.' . $show_key . '.required');
            }
            if ($row['match_type'] == 'unicodename') {
                if ($row['required']) {
                    $xtpl->assign('CALLFUNC', 'required_uname_check');
                    $xtpl->assign('ERRMESS', $nv_Lang->getModule('field_req_uname_error'));
                } else {
                    $xtpl->assign('CALLFUNC', 'uname_check');
                    $xtpl->assign('ERRMESS', $nv_Lang->getModule('field_uname_error'));
                }
                $xtpl->parse('main.' . $show_key . '.data_callback');
            }
            if ($row['field'] == 'gender') {
                foreach ($global_array_genders as $gender) {
                    $gender['sel'] = $row['value'] == $gender['key'] ? ' selected="selected"' : '';
                    $xtpl->assign('GENDER', $gender);
                    $xtpl->parse('main.' . $show_key . '.gender');
                }
            }
            if ($row['field'] == 'birthday') {
                if (!empty($global_users_config['min_old_user'])) {
                    $xtpl->parse('main.' . $show_key . '.min_old_user');
                } else {
                    $xtpl->parse('main.' . $show_key . '.not_min_old_user');
                }
            }
            if ($row['description']) {
                $xtpl->parse('main.' . $show_key . '.description');
            }
            $xtpl->parse('main.' . $show_key);
            if ($row['field'] == 'gender') {
                $xtpl->parse('main.name_show_' . $global_config['name_show']);
            }
        }
    }

    $xtpl->assign(strtoupper($data['type']) . '_ACTIVE', 'active');
    $xtpl->assign(strtoupper('TAB_' . $data['type']) . '_ACTIVE', 'in active');
    $xtpl->assign(strtoupper('TAB5_' . $data['type']) . '_ACTIVE', 'show active'); // For bootstrap 4/5

    $item_active = [
        'name' => $data['type']
    ];
    $titles = [
        'avatar' => 'edit_avatar',
        'username' => 'edit_login',
        'email' => 'edit_email',
        'password' => 'edit_password',
        'passkey' => 'edit_passkey',
        'langinterface' => 'langinterface',
        'question' => 'edit_question',
        'openid' => 'openid_administrator',
        'group' => 'group',
        'others' => 'edit_others',
        'safemode' => 'safe_mode',
        'securityprivacy' => 'security_privacy'
    ];
    $item_active['title'] = isset($titles[$data['type']]) ? $nv_Lang->getModule($titles[$data['type']]) : $nv_Lang->getModule('edit_basic');
    $xtpl->assign('ITEM_ACTIVE', $item_active);

    if (defined('ACCESS_EDITUS')) {
        $xtpl->assign('GROUP_MANAGE', [
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=groups/' . $group_id,
            'title' => $nv_Lang->getModule('return_group_manage', $group_lists[$group_id]['title'])
        ]);
        $xtpl->parse('main.return_group_manage');
    }

    // Tab đổi tên đăng nhập
    if (in_array('username', $types, true)) {
        if ($pass_empty) {
            $xtpl->parse('main.tab_edit_username.username_empty_pass');
        }
        if (!empty($global_config['allowuserloginmulti'])) {
            $xtpl->parse('main.tab_edit_username.forcedrelogin');
        }
        $xtpl->parse('main.edit_username');
        $xtpl->parse('main.tab_edit_username');
    }

    // Tab đổi mật khẩu
    if (in_array('password', $types, true)) {
        if (!$pass_empty and !defined('ACCESS_PASSUS')) {
            $xtpl->parse('main.tab_edit_password.is_old_pass');
        }
        $xtpl->parse('main.edit_password');

        if (defined('ACCESS_EDITUS')) {
            $xtpl->assign('GROUP_MANAGE', [
                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=groups/' . $group_id,
                'title' => $nv_Lang->getModule('return_group_manage', $group_lists[$group_id]['title'])
            ]);
            $xtpl->parse('main.tab_edit_password.return_group_manage');
        }
        if (!empty($global_config['allowuserloginmulti'])) {
            $xtpl->parse('main.tab_edit_password.forcedrelogin');
        }
        $xtpl->parse('main.tab_edit_password');
    }

    // Tab passkey
    if (in_array('passkey', $types, true)) {
        if (!$data['confirmed_pass']) {
            $xtpl->assign('URL_CONFIRM_PASS_PASSKEY', $data['confirm_pass_url']);
            $xtpl->parse('main.edit_passkey_linked');
        } else {
            if (empty($data['login_keys'])) {
                $xtpl->parse('main.tab_edit_passkey.pass_confirmed.no_loginkey');
            } else {
                foreach ($data['publicKeys'] as $publicKey) {
                    if (empty($publicKey['enable_login'])) {
                        continue;
                    }

                    $publicKey['created_at'] = nv_datetime_format($publicKey['created_at'], 1);
                    $publicKey['last_used_at'] = nv_datetime_format($publicKey['last_used_at'], 1);

                    $xtpl->assign('PUBLICKEY', $publicKey);

                    if ($publicKey['clid'] == $client_info['clid']) {
                        $xtpl->parse('main.tab_edit_passkey.pass_confirmed.loginkeys.loop.this_client');
                    }

                    $xtpl->parse('main.tab_edit_passkey.pass_confirmed.loginkeys.loop');
                }

                $xtpl->parse('main.tab_edit_passkey.pass_confirmed.loginkeys');
            }

            $xtpl->parse('main.edit_passkey_tab');
            $xtpl->parse('main.tab_edit_passkey.pass_confirmed');
        }

        $xtpl->parse('main.tab_edit_passkey');
    }

    // Tab đổi ngôn ngữ hiển thị
    if (in_array('langinterface', $types, true)) {
        $xtpl->parse('main.edit_langinterface');

        foreach ($global_config['allow_sitelangs'] as $lang_i) {
            $xtpl->assign('OPTION', [
                'val' => $lang_i,
                'sel' => $lang_i == $data['langinterface'] ? ' selected="selected"' : '',
                'name' => !empty($language_array[$lang_i]['name']) ? $language_array[$lang_i]['name'] : $lang_i
            ]);
            $xtpl->parse('main.tab_edit_langinterface.lang_option');
        }
        $xtpl->parse('main.tab_edit_langinterface');
    }

    // Tab quản lý xác thực hai bước
    if (in_array('2step', $types, true)) {
        $xtpl->assign('URL_2STEP', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=two-step-verification', true));
        $xtpl->parse('main.2step');
    }

    // Tab đổi email
    if (in_array('email', $types, true)) {
        if ($pass_empty) {
            $xtpl->parse('main.tab_edit_email.email_empty_pass');
        }
        if (!empty($global_config['allowuserloginmulti'])) {
            $xtpl->parse('main.tab_edit_email.forcedrelogin');
        }

        // Thông báo lí do vì sao bị đưa đến trang đổi email
        if ($user_info['email_reset_request'] == 1) {
            $xtpl->parse('main.tab_edit_email.change_required');
        }

        $xtpl->parse('main.edit_email');
        $xtpl->parse('main.tab_edit_email');
    }

    // Tab quản lý openid
    if (in_array('openid', $types, true)) {
        if (!empty($data_openid)) {
            $openid_del_al = 0;
            foreach ($data_openid as $openid) {
                $openid['email_or_id'] = !empty($openid['email']) ? $openid['email'] : $openid['id'];
                $openid['opid'] = $openid['opid'] . '_' . $openid['openid'];
                $openid['openid'] = ucwords($openid['openid']);
                $xtpl->assign('OPENID_LIST', $openid);
                if (!$openid['disabled']) {
                    $xtpl->parse('main.tab_edit_openid.openid_not_empty.openid_list.is_act');
                    ++$openid_del_al;
                } else {
                    $xtpl->parse('main.tab_edit_openid.openid_not_empty.openid_list.disabled');
                }
                $xtpl->parse('main.tab_edit_openid.openid_not_empty.openid_list');
            }

            if ($openid_del_al) {
                if ($openid_del_al > 1) {
                    $xtpl->parse('main.tab_edit_openid.openid_not_empty.checkAll');
                }
                $xtpl->parse('main.tab_edit_openid.openid_not_empty.button');
            }

            $xtpl->parse('main.tab_edit_openid.openid_not_empty');
        }

        foreach ($global_config['openid_servers'] as $server) {
            $img = $server;
            if ($server == 'google-identity') {
                $img = 'google';
            }
            $assigns = [];
            $assigns['server'] = $server;
            $assigns['href'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=oauth&amp;server=' . $server;
            $assigns['title'] = ucfirst($server);
            $assigns['img_src'] = NV_STATIC_URL . 'themes/' . $template . '/images/' . $module_info['module_theme'] . '/' . $img . '.png';
            $assigns['img_width'] = $assigns['img_height'] = 24;

            $xtpl->assign('OPENID', $assigns);
            $xtpl->parse('main.tab_edit_openid.server');
        }

        $xtpl->parse('main.edit_openid');
        $xtpl->parse('main.tab_edit_openid');
    }

    // Tab nhóm thành viên
    if (in_array('group', $types, true)) {
        $group_check_all_checked = 1;
        $count = 0;
        foreach ($groups as $group) {
            $group['isChecked'] = !empty($group['checked']) ? 1 : 0;
            $group['status_mess'] = $nv_Lang->getModule('group_status_' . $group['status']);
            $group['group_type_mess'] = $nv_Lang->getModule('group_type_' . $group['group_type']);
            $group['group_type_note'] = !empty($nv_Lang->getModule('group_type_' . $group['group_type'] . '_note')) ? $nv_Lang->getModule('group_type_' . $group['group_type'] . '_note') : '';
            $xtpl->assign('GROUP_LIST', $group);
            if ($group['status'] == 1) {
                $xtpl->parse('main.tab_edit_group.group_list.if_joined');
            } elseif ($group['status'] == 2) {
                $xtpl->parse('main.tab_edit_group.group_list.if_waited');
            } else {
                $xtpl->parse('main.tab_edit_group.group_list.if_not_joined');
            }
            if ($group['is_leader']) {
                $xtpl->assign('URL_IS_LEADER', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=groups/' . $group['group_id'], true));
                $xtpl->parse('main.tab_edit_group.group_list.is_leader');
            }
            if ($group['group_type']) {
                if ($group['is_leader']) {
                    $xtpl->parse('main.tab_edit_group.group_list.is_checkbox.is_disable_checkbox');
                }
                $xtpl->parse('main.tab_edit_group.group_list.is_checkbox');
            }
            if (!empty($group['group_type_note'])) {
                $xtpl->parse('main.tab_edit_group.group_list.group_type_note');
            }
            $xtpl->parse('main.tab_edit_group.group_list');
            if (empty($group['checked'])) {
                $group_check_all_checked = 0;
            }
            ++$count;
        }

        if ($count > 1) {
            if ($group_check_all_checked) {
                $xtpl->assign('CHECK_ALL_CHECKED', ' checked="checked"');
            }
            $xtpl->parse('main.tab_edit_group.checkAll');
        }

        $xtpl->parse('main.edit_group');
        $xtpl->parse('main.tab_edit_group');
    }

    // Tab sửa các thông tin khác (các trường dữ liệu tùy chỉnh)
    if (in_array('others', $types, true) and !empty($is_custom_field)) {
        // Parse custom fields
        foreach ($array_field_config as $row) {
            if (empty($row['system'])) {
                $row['value'] = (isset($custom_fields[$row['field']])) ? $custom_fields[$row['field']] : get_value_by_lang($row['default_value']);
                $row['required'] = ($row['required']) ? 'required' : '';

                $xtpl->assign('FIELD', $row);

                if ($row['required']) {
                    $xtpl->parse('main.tab_edit_others.loop.required');
                }

                if ($row['field_type'] == 'textbox' or $row['field_type'] == 'number') {
                    if ($row['match_type'] == 'unicodename') {
                        if ($row['required']) {
                            $xtpl->assign('CALLFUNC', 'required_uname_check');
                            $xtpl->assign('ERRMESS', $nv_Lang->getModule('field_req_uname_error'));
                        } else {
                            $xtpl->assign('CALLFUNC', 'uname_check');
                            $xtpl->assign('ERRMESS', $nv_Lang->getModule('field_uname_error'));
                        }
                        $xtpl->parse('main.tab_edit_others.loop.textbox.data_callback');
                    }
                    if (!empty($row['description'])) {
                        $xtpl->parse('main.tab_edit_others.loop.textbox.description');
                    }
                    $xtpl->parse('main.tab_edit_others.loop.textbox');
                } elseif ($row['field_type'] == 'date') {
                    $row['value'] = nv_u2d_post($row['value']);
                    $datepicker = true;
                    $xtpl->assign('FIELD', $row);
                    if (!empty($row['min_length'])) {
                        $xtpl->parse('main.tab_edit_others.loop.date.minDate');
                    }
                    if (!empty($row['max_length'])) {
                        $xtpl->parse('main.tab_edit_others.loop.date.maxDate');
                    }
                    if (!empty($row['description'])) {
                        $xtpl->parse('main.tab_edit_others.loop.date.description');
                    }
                    $xtpl->parse('main.tab_edit_others.loop.date');
                } elseif ($row['field_type'] == 'textarea') {
                    $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
                    $xtpl->assign('FIELD', $row);
                    if (!empty($row['description'])) {
                        $xtpl->parse('main.tab_edit_others.loop.textarea.description');
                    }
                    $xtpl->parse('main.tab_edit_others.loop.textarea');
                } elseif ($row['field_type'] == 'editor') {
                    $row['value'] = htmlspecialchars(nv_editor_br2nl($row['value']));
                    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
                        $array_tmp = explode('@', $row['class']);
                        $edits = nv_aleditor('custom_fields[' . $row['field'] . ']', $array_tmp[0], $array_tmp[1], $row['value'], 'User');
                        $xtpl->assign('EDITOR', $edits);
                        if (!empty($row['description'])) {
                            $xtpl->parse('main.tab_edit_others.loop.editor.description');
                        }
                        $xtpl->parse('main.tab_edit_others.loop.editor');
                    } else {
                        $row['class'] = '';
                        $xtpl->assign('FIELD', $row);
                        if (!empty($row['description'])) {
                            $xtpl->parse('main.tab_edit_others.loop.textarea.description');
                        }
                        $xtpl->parse('main.tab_edit_others.loop.textarea');
                    }
                } elseif ($row['field_type'] == 'select') {
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', [
                            'key' => $key,
                            'selected' => ($key == $row['value']) ? ' selected="selected"' : '',
                            'value' => get_value_by_lang2($key, $value)
                        ]);
                        $xtpl->parse('main.tab_edit_others.loop.select.loop');
                    }
                    if (!empty($row['description'])) {
                        $xtpl->parse('main.tab_edit_others.loop.select.description');
                    }
                    $xtpl->parse('main.tab_edit_others.loop.select');
                } elseif ($row['field_type'] == 'radio') {
                    $number = 0;
                    $count = count($row['field_choices']);
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', [
                            'id' => $row['fid'] . '_' . $number++,
                            'key' => $key,
                            'checked' => ($key == $row['value']) ? ' checked="checked"' : '',
                            'value' => get_value_by_lang2($key, $value)
                        ]);
                        $xtpl->parse('main.tab_edit_others.loop.radio.loop');
                    }
                    if ($number == $count) {
                        $xtpl->parse('main.tab_edit_others.loop.radio.loop.invalidtooltip');
                    }
                    if (!empty($row['description'])) {
                        $xtpl->parse('main.tab_edit_others.loop.radio.description');
                    }
                    $xtpl->parse('main.tab_edit_others.loop.radio');
                } elseif ($row['field_type'] == 'checkbox') {
                    $number = 0;
                    $count = count($row['field_choices']);
                    $valuecheckbox = (!empty($row['value'])) ? explode(',', $row['value']) : [];

                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', [
                            'id' => $row['fid'] . '_' . $number++,
                            'key' => $key,
                            'checked' => (in_array((string) $key, $valuecheckbox, true)) ? ' checked="checked"' : '',
                            'value' => get_value_by_lang2($key, $value)
                        ]);
                        $xtpl->parse('main.tab_edit_others.loop.checkbox.loop');
                    }
                    if ($number == $count) {
                        $xtpl->parse('main.tab_edit_others.loop.checkbox.loop.invalidtooltip');
                    }
                    if (!empty($row['description'])) {
                        $xtpl->parse('main.tab_edit_others.loop.checkbox.description');
                    }
                    $xtpl->parse('main.tab_edit_others.loop.checkbox');
                } elseif ($row['field_type'] == 'multiselect') {
                    $valueselect = (!empty($row['value'])) ? explode(',', $row['value']) : [];

                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', [
                            'key' => $key,
                            'selected' => (in_array((string) $key, $valueselect, true)) ? ' selected="selected"' : '',
                            'value' => get_value_by_lang2($key, $value)
                        ]);
                        $xtpl->parse('main.tab_edit_others.loop.multiselect.loop');
                    }
                    if (!empty($row['description'])) {
                        $xtpl->parse('main.tab_edit_others.loop.multiselect.description');
                    }
                    $xtpl->parse('main.tab_edit_others.loop.multiselect');
                } elseif ($row['field_type'] == 'file') {
                    $filelist = !empty($row['value']) ? explode(',', $row['value']) : [];
                    foreach ($filelist as $file_item) {
                        $assign = file_type_name($file_item);
                        $assign['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;userfile=' . $file_item . '&amp;field=' . $row['field'];
                        if (defined('ACCESS_EDITUS')) {
                            $assign['url'] .= '&amp;groupid=' . $data['group_id'] . '&amp;userid=' . $data['userid'];
                        }
                        $xtpl->assign('FILE_ITEM', $assign);
                        $xtpl->parse('main.tab_edit_others.loop.file.loop');
                    }
                    $xtpl->assign('FILEACCEPT', !empty($row['limited_values']['mime']) ? '.' . implode(',.', $row['limited_values']['mime']) : '');
                    $xtpl->assign('FILEMAXSIZE', $row['limited_values']['file_max_size'] ?? 0);
                    $xtpl->assign('FILEMAXSIZE_FORMAT', nv_convertfromBytes($row['limited_values']['file_max_size'] ?? 0));
                    $xtpl->assign('FILEMAXNUM', $row['limited_values']['maxnum'] ?? 0);
                    $xtpl->assign('CSRF', csrf_create($module_name . '_field_' . $row['field']));
                    $widthlimit = image_size_info($row['limited_values']['widthlimit'], 'width');
                    $heightlimit = image_size_info($row['limited_values']['heightlimit'], 'height');
                    if (!empty($widthlimit)) {
                        $xtpl->assign('WIDTHLIMIT', $widthlimit);
                        $xtpl->parse('main.tab_edit_others.loop.file.widthlimit');
                    }
                    if (!empty($heightlimit)) {
                        $xtpl->assign('HEIGHTLIMIT', $heightlimit);
                        $xtpl->parse('main.tab_edit_others.loop.file.heightlimit');
                    }
                    if (!(empty($row['limited_values']['maxnum']) or (count($filelist) < $row['limited_values']['maxnum']))) {
                        $xtpl->parse('main.tab_edit_others.loop.file.addfile');
                    }
                    $xtpl->parse('main.tab_edit_others.loop.file');
                }
                $xtpl->parse('main.tab_edit_others.loop');
            }
        }
        $xtpl->parse('main.edit_others');

        if (defined('ACCESS_EDITUS')) {
            $xtpl->assign('GROUP_MANAGE', [
                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=groups/' . $group_id,
                'title' => $nv_Lang->getModule('return_group_manage', $group_lists[$group_id]['title'])
            ]);
            $xtpl->parse('main.tab_edit_others.return_group_manage');
        }
        $xtpl->parse('main.tab_edit_others');
    }

    // Tab đổi ảnh đại diện
    if (in_array('avatar', $types, true)) {
        $xtpl->parse('main.edit_avatar');
        $xtpl->parse('main.tab_edit_avatar');
    }

    // Tab đổi câu hỏi bảo mật (điều kiện trường dữ liệu câu hỏi và câu trả lời đều tồn tại)
    if (in_array('question', $types, true) and (isset($array_field_config['question']) or isset($array_field_config['answer']))) {
        if ($pass_empty) {
            $xtpl->parse('main.tab_edit_question.question_empty_pass');
        }

        $array_question_key = [
            'question',
            'answer'
        ];
        foreach ($array_question_key as $key) {
            if (isset($array_field_config[$key])) {
                $row = $array_field_config[$key];
                $show_key = 'show_' . $row['field'];
                $row['value'] = (isset($custom_fields[$row['field']])) ? $custom_fields[$row['field']] : '';
                $row['required'] = ($row['required']) ? 'required' : '';
                $xtpl->assign('FIELD', $row);
                foreach ($data_questions as $array_question_i) {
                    $xtpl->assign('QUESTION', $array_question_i['title']);
                    $xtpl->parse('main.tab_edit_question.' . $show_key . '.frquestion');
                }
                if ($row['required']) {
                    $xtpl->parse('main.tab_edit_question.' . $show_key . '.required');
                }
                if ($row['description']) {
                    $xtpl->parse('main.tab_edit_question.' . $show_key . '.description');
                }
                $xtpl->parse('main.tab_edit_question.' . $show_key);
            }
        }

        $xtpl->parse('main.edit_question');
        $xtpl->parse('main.tab_edit_question');
    }

    // Tab chế độ an toàn
    if (in_array('safemode', $types, true)) {
        if ($pass_empty) {
            $xtpl->parse('main.tab_edit_safemode.safemode_empty_pass');
        }
        $xtpl->parse('main.edit_safemode');
        $xtpl->parse('main.tab_edit_safemode');
    }

    // Tab bảo mật và quyền riêng tư
    if (in_array('securityprivacy', $types, true)) {
        $xtpl->assign('URL_SECURITY_PRIVACY', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=security-privacy', true));
        $xtpl->parse('main.securityprivacy');
    }

    // Xuất menu cuối form
    $_lis = \NukeViet\Module\users\Shared\Navs::getNavs($module_info['funcs']);
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        if ($_li['func_name'] == $op) {
            continue;
        }

        $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
        $li = [
            'href' => $href,
            'title' => $_li['func_name'] == 'main' ? $nv_Lang->getModule('user_info') : $_li['func_custom_name']
        ];
        $xtpl->assign('NAVBAR', $li);
        $xtpl->parse('main.navbar');
    }

    if ($datepicker) {
        $xtpl->parse('main.datepicker');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * @param array $openid_info
 * @return string
 */
function openid_callback($openid_info)
{
    $xtpl = new XTemplate('openid_callback.tpl', get_module_tpl_dir('openid_callback.tpl'));
    $xtpl->assign('OPIDRESULT', $openid_info);

    if ($openid_info['status'] == 'success') {
        $xtpl->parse('main.success');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Giao diện trang chính hiển thị thông tin người dùng.
 *
 * @param array $array_field_config
 * @param array $custom_fields
 * @return string
 */
function user_welcome(array $array_field_config, array $custom_fields): string
{
    global $module_info, $global_config, $nv_Lang, $module_name, $user_info, $op, $language_array;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('userinfo.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('URL_AVATAR', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=avatar/upd', true));
    $tpl->assign('URL_GROUPS', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=groups', true));
    $tpl->assign('URL_2STEP', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=two-step-verification', true));

    $tpl->assign('CHANGEPASS_INFO', (int) $user_info['pass_reset_request'] === 2 ? $nv_Lang->getModule('pass_reset2_info', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/password') : '');
    $tpl->assign('CHANGEEMAIL_INFO', (int) $user_info['email_reset_request'] === 2 ? $nv_Lang->getModule('email_reset2_info', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/email') : '');

    // src rỗng tpl sẽ chuyển sang dạng avatar chữ
    $tpl->assign('IMG', [
        'src' => $user_info['avata'] ?? '',
        'title' => !empty($user_info['avata']) ? $nv_Lang->getModule('img_size_title') : $nv_Lang->getModule('change_avatar')
    ]);

    $_user_info = $user_info;

    // Tính lại phòng trường hợp tài khoản diễn đàn hoặc SSO
    $_user_info['avatar_letters'] = $user_info['avatar_letters'] ?? nv_user_avatar_letters($user_info['first_name'] ?? '', $user_info['last_name'] ?? '', $user_info['username'] ?? '');
    $_user_info['avatar_color'] = $user_info['avatar_color'] ?? nv_user_avatar_color($user_info['username'] ?? '');

    $_user_info['gender'] = ($user_info['gender'] == 'M') ? $nv_Lang->getModule('male') : ($user_info['gender'] == 'F' ? $nv_Lang->getModule('female') : '');
    $_user_info['birthday'] = empty($user_info['birthday']) ? '' : nv_date_format(1, $user_info['birthday']);
    $_user_info['regdate'] = nv_date_format(1, $user_info['regdate']);
    $_user_info['view_mail'] = empty($user_info['view_mail']) ? $nv_Lang->getModule('no') : $nv_Lang->getModule('yes');
    $_user_info['prev_login'] = empty($user_info['prev_login']) ? '' : nv_datetime_format($user_info['prev_login'], 0, 0);
    $_user_info['last_login'] = nv_datetime_format($user_info['last_login'], 0, 0);
    $_user_info['current_login'] = nv_datetime_format($user_info['current_login'], 0, 0);
    $_user_info['st_login'] = !empty($user_info['st_login']) ? $nv_Lang->getModule('yes') : $nv_Lang->getModule('no');
    $_user_info['active2step'] = !empty($user_info['active2step']) ? $nv_Lang->getGlobal('on') : $nv_Lang->getGlobal('off');

    $method = (preg_match('/^([^0-9]+[a-z0-9\_]+)$/', $global_config['login_name_type']) and module_file_exists('users/methods/' . $global_config['login_name_type'] . '.php')) ? $global_config['login_name_type'] : 'username';
    $_user_info['login_name'] = $nv_Lang->existsGlobal('login_name_type_' . $method) ? $nv_Lang->getGlobal('login_name_type_' . $method) : $method;
    if ($global_config['lang_multi']) {
        $_user_info['langinterface'] = !empty($_user_info['language']) ? (!empty($language_array[$_user_info['language']]['name']) ? $language_array[$_user_info['language']]['name'] : $_user_info['language']) : $nv_Lang->getModule('bydatalang');
    }

    if (isset($user_info['current_mode']) and $user_info['current_mode'] == 5) {
        $_user_info['current_mode'] = $nv_Lang->getModule('admin_login');
    } elseif (isset($user_info['current_mode']) and $user_info['current_mode'] == 6) {
        $_user_info['current_mode'] = $nv_Lang->getModule('mode_login_6') . (!empty($user_info['current_passkey']) ? (' &quot;' . $user_info['current_passkey'] . '&quot;') : '');
    } elseif (isset($user_info['current_mode']) and $nv_Lang->existsModule('mode_login_' . $user_info['current_mode'])) {
        $_user_info['current_mode'] = $nv_Lang->getModule('mode_login_' . $user_info['current_mode']) . ': ' . $user_info['openid_server'] . ' (' . (!empty($user_info['openid_email']) ? $user_info['openid_email'] : $user_info['openid_id']) . ')';
    } else {
        $_user_info['current_mode'] = $nv_Lang->getModule('mode_login_1');
    }

    $_user_info['change_name_info'] = $nv_Lang->getModule('change_name_info', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/username');
    $_user_info['pass_empty_note'] = $nv_Lang->getModule('pass_empty_note', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/password');
    $_user_info['question_empty_note'] = $nv_Lang->getModule('question_empty_note', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/question');

    $tpl->assign('USER', $_user_info);

    $tpl->assign('SHOW_LANGINTERFACE', (bool) $global_config['lang_multi']);
    $tpl->assign('SHOW_CHANGE_LOGIN_NOTE', (
        !$global_config['allowloginchange']
        && !empty($user_info['current_openid'])
        && empty($user_info['prev_login'])
        && empty($user_info['prev_agent'])
        && empty($user_info['prev_ip'])
        && empty($user_info['prev_openid'])
    ));
    $tpl->assign('SHOW_PASS_EMPTY', empty($user_info['st_login']));
    $tpl->assign('SHOW_QUESTION_EMPTY', empty($user_info['valid_question']));
    $tpl->assign('SHOW_GROUP_MANAGE', $user_info['group_manage'] > 0);

    // Các trường dữ liệu tùy biến
    $array_custom_fields = [];
    if (!empty($array_field_config)) {
        foreach ($array_field_config as $row) {
            if ($row['system'] == 1) {
                continue;
            }
            if ($row['show_profile']) {
                $question_type = $row['field_type'];
                if ($question_type == 'date') {
                    $value = nv_date_format(1, $custom_fields[$row['field']] ?? 0);
                } elseif ($question_type == 'checkbox') {
                    $result = explode(',', $custom_fields[$row['field']]);
                    $value = [];
                    foreach ($result as $item) {
                        $_val = $item;
                        if (isset($row['field_choices'][$item])) {
                            if (is_string($row['field_choices'][$item])) {
                                $_val = $row['field_choices'][$item];
                            } elseif(is_array($row['field_choices'][$item]) and isset($row['field_choices'][$item][NV_LANG_DATA])) {
                                $_val = $row['field_choices'][$item][NV_LANG_DATA];
                            }
                        }
                        $value[] = $_val;
                    }
                    $value = empty($value) ? '' : implode('<br />', $value);
                } elseif ($question_type == 'multiselect' or $question_type == 'select' or $question_type == 'radio') {
                    $value = $custom_fields[$row['field']];
                    if (isset($row['field_choices'][$custom_fields[$row['field']]])) {
                        if (is_string($row['field_choices'][$custom_fields[$row['field']]])) {
                            $value = $row['field_choices'][$custom_fields[$row['field']]];
                        } elseif(is_array($row['field_choices'][$custom_fields[$row['field']]]) and isset($row['field_choices'][$custom_fields[$row['field']]][NV_LANG_DATA])) {
                            $value = $row['field_choices'][$custom_fields[$row['field']]][NV_LANG_DATA];
                        }
                    }
                } elseif ($question_type == 'file') {
                    $value = $custom_fields[$row['field']];
                    if (!empty($value)) {
                        $tempfiles = explode(',', $value);
                        $value = '';
                        foreach ($tempfiles as $tempfile) {
                            $tempfile = trim($tempfile);
                            $pathinfo = pathinfo($tempfile);
                            $value .= '<button type="button" class="btn btn-success btn-file type-' . file_type($pathinfo['extension']) . '" data-url="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;userfile=' . $tempfile . '&amp;field=' . $row['field'] . '">' . shorten_name($pathinfo['filename'], $pathinfo['extension']) . '</button> ';
                        }
                    }
                } else {
                    $value = $custom_fields[$row['field']];
                }
                $array_custom_fields[] = [
                    'title' => $row['title'],
                    'value' => $value
                ];
            }
        }
    }
    $tpl->assign('CUSTOM_FIELDS', $array_custom_fields);

    // Các công cụ cuối trang
    $array_navbars = [];
    $_lis = \NukeViet\Module\users\Shared\Navs::getNavs($module_info['funcs']);
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        if ($_li['func_name'] == $op) {
            continue;
        }
        $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
        $array_navbars[$_li['func_name']] = [
            'href' => $href,
            'title' => $_li['func_name'] == 'main' ? $nv_Lang->getModule('user_info') : $_li['func_custom_name']
        ];
    }

    $tpl->assign('NAVBARS', $array_navbars);

    return $tpl->fetch('userinfo.tpl');
}

/**
 * user_info_exit()
 *
 * @param mixed $info
 * @param bool  $error
 * @return string
 */
function user_info_exit($info, $error = false)
{
    global $module_info, $module_file;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('info_exit.tpl'));
    $tpl->assign('INFO', $info);
    $tpl->assign('IS_ERROR', $error);

    return $tpl->fetch('info_exit.tpl');
}

/**
 * openid_account_confirm()
 *
 * @param bool  $gfx_chk
 * @param array $attribs
 * @param array $user
 * @return string
 */
function openid_account_confirm($gfx_chk, $attribs, $user)
{
    global $nv_Lang, $module_info, $module_name, $module_captcha, $nv_redirect, $global_config, $page_title;

    $xtpl = new XTemplate('confirm.tpl', get_module_tpl_dir('confirm.tpl'));

    $nv_Lang->setModule('openid_confirm_info', $nv_Lang->getModule('openid_confirm_info', ucwords($attribs['server']), $attribs['contact/email']));

    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('PAGETITLE', $page_title);
    $xtpl->assign('OPENID_LOGIN', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;server=' . $attribs['server'] . '&amp;result=1');

    if ($gfx_chk) {
        // Nếu dùng reCaptcha v3
        if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
            $xtpl->parse('main.recaptcha3');
        }
        // Nếu dùng reCaptcha v2
        elseif ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
            $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
            $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode1'));
            $xtpl->parse('main.recaptcha');
        } elseif ($module_captcha == 'captcha') {
            $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode'));
            $xtpl->parse('main.captcha');
        }
    }

    if (!empty($nv_redirect)) {
        $xtpl->assign('REDIRECT', $nv_redirect);
        $xtpl->parse('main.redirect');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * Danh sách người dùng, dành cho admin hoặc trưởng nhóm
 *
 * @param array  $users_array
 * @param string $orderby
 * @param string $sortby
 * @param array  $array_order_new
 * @param string $generate_page
 * @return string
 */
function nv_memberslist_theme($users_array, $orderby, $sortby, $array_order_new, $generate_page)
{
    global $module_info, $module_name, $nv_Lang, $op;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('memberslist.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('USERS', $users_array);
    $tpl->assign('ORDER_LINKS', $array_order_new);
    $tpl->assign('ORDERBY', $orderby);
    $tpl->assign('SORTBY', strtoupper($sortby));
    $tpl->assign('GENERATE_PAGE', $generate_page);

    // Các công cụ cuối trang
    $array_navbars = [];
    $_lis = \NukeViet\Module\users\Shared\Navs::getNavs($module_info['funcs']);
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        if ($_li['func_name'] == $op) {
            continue;
        }

        $array_navbars[$_li['func_name']] = [
            'href' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']],
            'title' => $_li['func_name'] == 'main' ? $nv_Lang->getModule('user_info') : $_li['func_custom_name']
        ];
    }

    $tpl->assign('NAVBARS', $array_navbars);

    return $tpl->fetch('memberslist.tpl');
}

/**
 * Chi tiết người dùng, dành cho admin hoặc trưởng nhóm xem thành viên khác
 *
 * @param array $item
 * @param array $array_field_config
 * @param array $custom_fields
 * @param bool  $full
 * @return string
 */
function nv_memberslist_detail_theme($item, $array_field_config, $custom_fields, $full)
{
    global $module_info, $nv_Lang, $module_name, $admin_info, $op;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('memberslist-detail.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);

    // Ảnh đại diện để rỗng khi tài khoản chưa có ảnh, giao diện tự dựng avatar dạng chữ
    $item['avata'] = (!empty($item['photo']) and file_exists(NV_ROOTDIR . '/' . $item['photo'])) ? NV_BASE_SITEURL . $item['photo'] : '';
    $item['avatar_letters'] = nv_user_avatar_letters($item['first_name'], $item['last_name'], $item['username']);
    $item['avatar_color'] = nv_user_avatar_color($item['username']);

    $item['gender'] = ($item['gender'] == 'M') ? $nv_Lang->getModule('male') : ($item['gender'] == 'F' ? $nv_Lang->getModule('female') : '');
    $item['birthday'] = empty($item['birthday']) ? '' : nv_date_format(1, $item['birthday']);
    $item['regdate'] = nv_date_format(1, $item['regdate']);
    $item['last_login'] = empty($item['last_login']) ? '' : nv_datetime_format($item['last_login'], 0, 0);

    $tpl->assign('USER', $item);
    $tpl->assign('SHOW_ADMIN', (bool) ($item['is_admin'] and $full));
    $tpl->assign('SHOW_EMAIL', !empty($item['view_mail']));

    // Token xóa tài khoản dùng chung khóa với chức năng quản lý thành viên bên quản trị
    $tpl->assign('CHECKSS', $item['allow_delete'] ? csrf_create($admin_info['admin_id'] . '_' . $module_name . '_main') : '');

    // Các trường dữ liệu tùy biến
    $array_custom_fields = [];
    foreach ($array_field_config as $row) {
        if ($row['system'] == 1 or empty($row['show_profile'])) {
            continue;
        }

        $question_type = $row['field_type'];
        if ($question_type == 'date') {
            $value = nv_date_format(1, $custom_fields[$row['field']] ?? 0);
        } elseif ($question_type == 'checkbox') {
            $value = [];
            foreach (explode(',', $custom_fields[$row['field']]) as $choice) {
                if (empty($choice)) {
                    continue;
                }
                $value[] = nv_users_field_choice($row['field_choices'][$choice] ?? '', $choice);
            }
            $value = implode('<br />', $value);
        } elseif ($question_type == 'multiselect' or $question_type == 'select' or $question_type == 'radio') {
            $choice = $custom_fields[$row['field']];
            $value = nv_users_field_choice($row['field_choices'][$choice] ?? '', $choice);
        } else {
            $value = $custom_fields[$row['field']];
        }

        $array_custom_fields[] = [
            'title' => $row['title'],
            'value' => $value
        ];
    }

    $tpl->assign('CUSTOM_FIELDS', $array_custom_fields);

    // Các công cụ cuối trang
    $array_navbars = [];
    $_lis = \NukeViet\Module\users\Shared\Navs::getNavs($module_info['funcs']);
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        if ($_li['func_name'] == $op) {
            continue;
        }

        $array_navbars[$_li['func_name']] = [
            'href' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']],
            'title' => $_li['func_name'] == 'main' ? $nv_Lang->getModule('user_info') : $_li['func_custom_name']
        ];
    }

    $tpl->assign('NAVBARS', $array_navbars);

    return $tpl->fetch('memberslist-detail.tpl');
}

/**
 * user_info_exit_redirect()
 *
 * @param mixed  $info
 * @param string $nv_redirect
 */
function user_info_exit_redirect($info, $nv_redirect)
{
    global $module_info;

    $xtpl = new XTemplate('info_exit_redirect.tpl', get_module_tpl_dir('info_exit_redirect.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('INFO', $info);
    $xtpl->assign('NV_REDIRECT', $nv_redirect);

    $xtpl->parse('main');

    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

/**
 * Giao diện trang đổi ảnh đại diện
 *
 * @param array $array
 * @return string
 */
function nv_avatar($array)
{
    global $nv_Lang, $global_config;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('avatar.tpl'));

    // Xác định giao diện avatar.js
    $jsDir = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/js/avatar.js');

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('DATA', $array);
    $tpl->assign('JS_DIR', $jsDir);

    // Dung lượng tối đa của file tải lên, dạng chuỗi dễ đọc
    $tpl->assign('UPLOAD_MAX_FILESIZE_TEXT', nv_convertfromBytes(NV_UPLOAD_MAX_FILESIZE));

    return $tpl->fetch('avatar.tpl');
}

/**
 * safe_deactivate()
 *
 * @param array $data
 * @return string
 */
function safe_deactivate($data)
{
    global $module_info, $module_name, $nv_Lang, $global_config, $op, $nv_redirect;

    $xtpl = new XTemplate('safe.tpl', get_module_tpl_dir('safe.tpl'));
    $xtpl->assign('EDITINFO_FORM', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo');
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('PASS_MAXLENGTH', $global_config['nv_upassmax']);
    $xtpl->assign('PASS_MINLENGTH', $global_config['nv_upassmin']);
    $xtpl->assign('DATA', $data);
    $xtpl->assign('NV_REDIRECT', $nv_redirect);

    if ($data['safeshow']) {
        $xtpl->assign('SHOW1', ' style="display:none"');
    } else {
        $xtpl->assign('SHOW2', ' style="display:none"');
    }

    $_lis = \NukeViet\Module\users\Shared\Navs::getNavs($module_info['funcs']);
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        if ($_li['func_name'] == $op) {
            continue;
        }

        $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
        $li = [
            'href' => $href,
            'title' => $_li['func_name'] == 'main' ? $nv_Lang->getModule('user_info') : $_li['func_custom_name']
        ];
        $xtpl->assign('NAVBAR', $li);
        $xtpl->parse('main.navbar');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * @param int $pass_timeout
 * @param bool $pass_empty
 * @param string $checkss
 * @return string
 */
function theme_changePass($pass_timeout, $pass_empty, $checkss)
{
    global $module_info, $module_name, $nv_Lang, $global_config;

    $xtpl = new XTemplate('changepass.tpl', get_module_tpl_dir('changepass.tpl'));
    $xtpl->assign('CHANGEPASS_FORM', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/password');
    $xtpl->assign('URL_LOGOUT', defined('NV_IS_ADMIN') ? 'nv_admin_logout' : 'bt_logout');
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('LOGO', NV_BASE_SITEURL . $global_config['site_logo']);
    $xtpl->assign('SITE_NAME', $global_config['site_name']);
    $xtpl->assign('PASS_MAXLENGTH', $global_config['nv_upassmax']);
    $xtpl->assign('PASS_MINLENGTH', $global_config['nv_upassmin']);
    $xtpl->assign('CHECKSS', $checkss);

    $xtpl->assign('CHANGEPASS_INFO', $pass_timeout ? $nv_Lang->getModule('pass_reset3_info', floor($global_config['pass_timeout'] / 86400)) : $nv_Lang->getModule('pass_reset1_info'));

    $password_rule = empty($global_config['nv_upass_type']) ? $nv_Lang->getGlobal('password_rule_nolimit', $global_config['nv_upassmin'], $global_config['nv_upassmax']) : $nv_Lang->getGlobal('password_rule_limit', $nv_Lang->getGlobal('upass_type_' . $global_config['nv_upass_type']), $global_config['nv_upassmin'], $global_config['nv_upassmax']);
    $password_pattern = '/^';
    if ($global_config['nv_upass_type'] == 1) {
        $password_pattern .= "(?=.*[a-zA-Z])(?=.*\d)";
    } elseif ($global_config['nv_upass_type'] == 2) {
        $password_pattern .= "(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W\_])";
    } elseif ($global_config['nv_upass_type'] == 3) {
        $password_pattern .= "(?=.*[a-z])(?=.*[A-Z])(?=.*\d)";
    } elseif ($global_config['nv_upass_type'] == 4) {
        $password_pattern .= "(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W\_])";
    }
    $password_pattern .= '(.){' . $global_config['nv_upassmin'] . ',' . $global_config['nv_upassmax'] . '}$/';

    $xtpl->assign('PASSWORD_PATTERN', $password_pattern);
    $xtpl->assign('PASSWORD_RULE', $password_rule);

    if (!$pass_empty) {
        $xtpl->parse('main.is_old_pass');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * @param array $data
 * @param string $page_url
 * @return string
 */
function user_r2s($data, $page_url)
{
    $xtpl = new XTemplate('r2s.tpl', get_module_tpl_dir('r2s.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('FORM_ACTION', $page_url);
    $xtpl->assign('DATA', $data);

    if (!empty($data['question'])) {
        $xtpl->parse('main.sec_question');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * Giao diện xóa dữ liệu người dùng (xóa tài khoản) từ bên thứ ba
 *
 * @param array $data
 * @return string
 */
function user_data_deletion(array $data): string
{
    global $nv_Lang;

    $xtpl = new XTemplate('data_deletion.tpl', get_module_tpl_dir('data_deletion.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    $data['request_source'] = nv_ucfirst(nv_htmlspecialchars($data['request_source']));
    $data['deletion_time'] = $data['delete_at'] ? nv_datetime_format($data['delete_at']) : '';

    $xtpl->assign('DATA', $data);

    if (empty($data['delete_at']) or $data['delete_at'] <= NV_CURRENTTIME) {
        // Gỡ liên kết
        $xtpl->parse('main.unlink_account');
    } else {
        // Xóa tài khoản
        $xtpl->parse('main.delete_account');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Giao diện trang bảo mật và quyền riêng tư
 *
 * @param array $array
 * @param array $array_logins
 * @return string
 */
function user_security_privacy(array $array, array $array_logins): string
{
    global $checkss, $limit;

    $xtpl = new XTemplate('security_privacy.tpl', get_module_tpl_dir('security_privacy.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('CHECKSS', $checkss);
    $xtpl->assign('DATA', $array);

    $browser_icons = [
        'opera' => 'fa-opera',
        'operamini' => 'fa-opera',
        'explorer' => 'fa-internet-explorer',
        'edge' => 'fa-edge',
        'firefox' => 'fa-firefox',
        'mozilla' => 'fa-firefox',
        'safari' => 'fa-safari',
        'iphone' => 'fa-safari',
        'ipod' => 'fa-safari',
        'ipad' => 'fa-safari',
        'chrome' => 'fa-chrome',
        'android' => 'fa-android'
    ];
    $os_icons = [
        'win' => 'fa-windows',
        'apple' => 'fa-apple',
        'linux' => 'fa-linux',
        'android' => 'fa-android'
    ];

    if (empty($array_logins)) {
        $xtpl->parse('main.no_logins');
    } else {
        $stt = 0;
        $next_id = 0;
        foreach ($array_logins as $login) {
            if (++$stt >= $limit) {
                $next_id = $login['id'];
                break;
            }

            $login['icon_browser'] = $browser_icons[$login['browser_key']] ?? 'fa-globe';
            $login['icon_os'] = $os_icons[$login['os_family']] ?? 'fa-server';

            $xtpl->assign('LOGIN', $login);

            if ($login['is_current']) {
                $xtpl->parse('main.has_logins.ctn_loop.loop.current1');
                $xtpl->parse('main.has_logins.ctn_loop.loop.current2');
            } else {
                $xtpl->parse('main.has_logins.ctn_loop.loop.logout');
            }
            if ($login['is_admin']) {
                $xtpl->parse('main.has_logins.ctn_loop.loop.is_admin');
            }

            $xtpl->parse('main.has_logins.ctn_loop.loop');
        }

        $xtpl->parse('main.has_logins.ctn_loop');
        if ($array['loadmorelogins']) {
            return $xtpl->text('main.has_logins.ctn_loop');
        }

        $xtpl->assign('NEXT_OFFSET', $next_id);

        if (count($array_logins) > ($limit - 1)) {
            $xtpl->parse('main.has_logins.more');
        }
        if (count($array_logins) > 1) {
            $xtpl->parse('main.has_logins.logout_all');
        }

        $xtpl->parse('main.has_logins');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Giao diện trang xác thực mật khẩu
 *
 * @param array $array
 * @return string
 */
function user_verify_password(array $array): string
{
    global $module_captcha, $checkss, $global_config;

    $xtpl = new XTemplate('verify_password.tpl', get_module_tpl_dir('verify_password.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('CHECKSS', $checkss);

    $xtpl->assign('DATA', $array);

    if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
        // Nếu dùng reCaptcha v3
        $xtpl->parse('main.recaptcha3');
    } elseif ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
        // Nếu dùng reCaptcha v2
        $xtpl->parse('main.recaptcha');
    } elseif ($module_captcha == 'turnstile') {
        // Nếu dùng Turnstile
        $xtpl->parse('main.turnstile');
    } elseif ($module_captcha == 'captcha') {
        // Captcha mặc định
        $xtpl->parse('main.captcha');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Giao diện yêu cầu xóa dữ liệu người dùng
 *
 * @param array $array
 * @return string
 */
function user_request_deletion(array $array): string
{
    global $checkss, $global_users_config, $nv_Lang;

    $xtpl = new XTemplate('data_deletion_request.tpl', get_module_tpl_dir('data_deletion_request.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    $xtpl->assign('DATA', $array);
    $xtpl->assign('CHECKSS', $checkss);

    if (!$array['i_confirmed']) {
        if (empty($global_users_config['hold_deleted_username'])) {
            $hold_message = $nv_Lang->getModule('delaccount_explain12');
        } elseif ($global_users_config['hold_deleted_username'] > 999) {
            $hold_message = $nv_Lang->getModule('delaccount_explain11');
        } else {
            // Nếu số ngày giữ là bội số năm
            if ($global_users_config['hold_deleted_username'] % 365 == 0) {
                $hold_message = $nv_Lang->getModule('delaccount_explain9', $global_users_config['hold_deleted_username'] / 365);
            } else {
                $hold_message = $nv_Lang->getModule('delaccount_explain10', $global_users_config['hold_deleted_username']);
            }
        }
        $xtpl->assign('HOLD_MESSAGE', $hold_message);
        $xtpl->parse('main.not_confirmed');
    } else {
        if ($array['time_code_remain'] > 0) {
            $xtpl->parse('main.verification_page.timing_code');
        } else {
            $xtpl->parse('main.verification_page.request_new_code');
        }

        if (!empty($array['error'])) {
            $xtpl->parse('main.verification_page.error');
        }

        $xtpl->parse('main.verification_page');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Giao diện thành công yêu cầu xóa dữ liệu
 *
 * @param array $array
 * @return string
 */
function user_success_deletion(array $array): string
{
    $xtpl = new XTemplate('data_deletion_request_success.tpl', get_module_tpl_dir('data_deletion_request_success.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    $xtpl->assign('DATA', $array);

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Giao diện chờ xử lý yêu cầu xóa dữ liệu
 *
 * @param array $array
 * @return string
 */
function user_pending_deletion(array $array): string
{
    global $nv_redirect, $checkss;

    $xtpl = new XTemplate('data_deletion_request_pending.tpl', get_module_tpl_dir('data_deletion_request_pending.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    $xtpl->assign('DATA', $array);
    $xtpl->assign('CHECKSS', $checkss);
    $xtpl->assign('NV_REDIRECT', $nv_redirect);

    // Thông báo đã hủy
    if ($array['is_cancel']) {
        $xtpl->parse('cancel');
        return $xtpl->text('cancel');
    }

    if (!empty($array['error'])) {
        $xtpl->parse('main.error');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Giao diện danh sách nhóm đang quản lý
 *
 * @param array $groupsList
 * @return string
 */
function user_groups(array $groupsList): string
{
    global $global_config, $op, $module_name, $module_info, $nv_Lang;

    $xtpl = new XTemplate('groups.tpl', get_module_tpl_dir('groups.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $global_config['module_theme']);
    $xtpl->assign('MODULE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE);
    $xtpl->assign('OP', $op);

    // Xuất danh sách nhóm
    foreach ($groupsList as $group_id => $values) {
        $xtpl->assign('GROUP_ID', $group_id);

        $loop = [
            'title' => $values['title'],
            'add_time' => nv_datetime_format($values['add_time']),
            'exp_time' => !empty($values['exp_time']) ? nv_datetime_format($values['exp_time']) : $nv_Lang->getGlobal('indefinitely'),
            'number' => nv_number_format($values['numbers']),
            'link_userlist' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_id, true)
        ];

        $xtpl->assign('LOOP', $loop);
        $xtpl->parse('main.loop');
    }

    // Nav cuối
    $_lis = \NukeViet\Module\users\Shared\Navs::getNavs($module_info['funcs']);
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
        if (!empty($nv_redirect)) {
            $href .= '&nv_redirect=' . $nv_redirect;
        }
        $li = [
            'href' => $href,
            'title' => $_li['func_name'] == 'main' ? $nv_Lang->getModule('user_info') : $_li['func_custom_name']
        ];
        $xtpl->assign('NAVBAR', $li);
        $xtpl->parse('main.navbar');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Giao diện trang danh sách người dùng của nhóm
 *
 * @param array $group_data
 * @param array $group_users
 * @param array $array_users
 * @param array $array_search
 * @return string
 */
function user_groups_list_users(array $group_data, array $group_users, array $array_users, array $array_search): string
{
    global $global_config, $op, $module_name, $per_page, $nv_Lang;

    $xtpl = new XTemplate('groups_users.tpl', get_module_tpl_dir('groups_users.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $global_config['module_theme']);
    $xtpl->assign('MODULE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE);
    $xtpl->assign('OP', $op);

    $group_data['data_number_view'] = array_map('nv_number_format', $group_data['data_number']);

    $xtpl->assign('GID', $group_data['group_id']);
    $xtpl->assign('DATA', $group_data);
    $xtpl->assign('MIN_SEARCH', $nv_Lang->getModule('min_search', NV_MIN_SEARCH_LENGTH));
    $xtpl->assign('EDIT_GROUP_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_data['group_id'] . '/edit');

    if ($group_data['group_id'] > 9) {
        if (!empty($global_config['inform_active'])) {
            $xtpl->assign('INFORM_NOTIFICATIONS_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_data['group_id'] . '/inform');
            $xtpl->parse('main.tools.inform_notifications');
        }
        if ($group_data['config']['access_groups_add'] != 0) {
            $xtpl->parse('main.tools.addUserGroup');
        }
        if ($group_data['config']['access_addus'] != 0) {
            $xtpl->parse('main.tools.add_user');
        }
        if ($group_data['config']['access_waiting'] != 0) {
            $xtpl->parse('main.tools.user_waiting');
        }
        $xtpl->parse('main.tools');
    }

    if (!empty($group_data['description'])) {
        $xtpl->parse('main.group_desc');
    }

    if (!empty($group_data['group_type_note'])) {
        $xtpl->parse('main.group_type_note');
    }

    if (!empty($group_data['content'])) {
        $xtpl->parse('main.group_content');
    }

    // Xuất danh sách người dùng
    if (empty($group_users)) {
        $xtpl->parse('main.no_users');
    } else {
        foreach ($group_users as $_type => $arr_users) {
            $xtpl->assign('PTITLE', $nv_Lang->getModule($_type . '_in_group_caption'));

            $stt = 1;
            foreach ($arr_users as $row) {
                $xtpl->assign('STT', $stt);
                $xtpl->assign('LOOP', $row);

                // Quyền xem chi tiết thông tin thành viên
                if ($group_data['viewuser_allowed'] and $_type != 'pending') {
                    $xtpl->parse('main.users.' . $_type . '.loop.linkuser');
                } else {
                    $xtpl->parse('main.users.' . $_type . '.loop.textuser');
                }

                // Các công cụ quản lý thành viên
                if ($row['tools_allowed']) {
                    if ($group_data['config']['access_groups_del']) {
                        $xtpl->parse('main.users.' . $_type . '.loop.tools.deletemember');
                    }

                    if (!$row['is_admin']) {
                        if ($group_data['config']['access_editus']) {
                            $xtpl->parse('main.users.' . $_type . '.loop.tools.edituser');
                        }

                        if ($group_data['config']['access_delus'] and $row['group_count'] == 1) {
                            $xtpl->parse('main.users.' . $_type . '.loop.tools.deluser');
                        }
                    }

                    $xtpl->parse('main.users.' . $_type . '.loop.tools');
                }

                $xtpl->parse('main.users.' . $_type . '.loop');
                ++$stt;
            }

            if (!empty($group_data['generate_page'])) {
                // Phân trang
                $xtpl->assign('PAGE', $group_data['generate_page']);
                $xtpl->parse('main.users.' . $_type . '.page');
            } elseif ($group_data['data_number'][$_type] > $per_page) {
                // Hiển thị nút xem thêm
                $xtpl->parse('main.users.' . $_type . '.viewmore');
            }

            $xtpl->parse('main.users.' . $_type);
        }

        $xtpl->parse('main.users');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Giao diện trang tìm kiếm người dùng đợi kích hoạt của nhóm
 *
 * @param int $gid
 * @return string
 */
function user_groups_getuserid(int $gid): string
{
    global $global_config, $op, $module_name, $module_file, $g_csrf_key, $op_file;

    $xtpl = new XTemplate('groups_getuserid.tpl', get_module_tpl_dir('groups_users.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $global_config['module_theme']);
    $xtpl->assign('GLOBAL_CONFIG', $global_config);
    $xtpl->assign('OP', $op);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('CHECKSS', csrf_create($g_csrf_key[$op_file]));
    $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;gid=' . $gid . '&amp;getuserid=1');

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Giao diện kết quả tìm kiếm người dùng đợi kích hoạt trong nhóm
 *
 * @param array $array
 * @param array $array_user
 * @return string
 */
function user_groups_getuserid_result(array $array, array $array_user): string
{
    $xtpl = new XTemplate('groups_getuserid_result.tpl', get_module_tpl_dir('groups_users.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('ARRAY', $array);
    $xtpl->assign('ARRAY_USER', $array_user);

    if (!empty($array_user)) {
        foreach ($array_user as $row) {
            $xtpl->assign('ROW', $row);
            $xtpl->parse('main.data.row');
        }

        if (!empty($array['generate_page'])) {
            $xtpl->assign('GENERATE_PAGE', $array['generate_page']);
            $xtpl->parse('main.data.generate_page');
        }

        $xtpl->parse('main.data');
    } else {
        $xtpl->parse('main.nodata');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Giao diện chỉnh sửa thông tin nhóm
 *
 * @param array $group_data
 * @return string
 */
function user_groups_edit(array $group_data): string
{
    global $global_config, $op, $module_name, $module_info, $nv_Lang;

    $xtpl = new XTemplate('groups_edit.tpl', get_module_tpl_dir('groups_edit.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $global_config['module_theme']);
    $xtpl->assign('OP', $op);
    $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_data['group_id'] . '/edit');
    $xtpl->assign('DATA', $group_data);

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Giao diện thông báo của nhóm
 *
 * @param array $group_data
 * @return string
 */
function user_groups_inform(array $group_data): string
{
    global $global_config, $op, $module_name, $module_info, $nv_Lang;

    $xtpl = new XTemplate('groups_inform.tpl', get_module_tpl_dir('groups_inform.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $global_config['module_theme']);
    $xtpl->assign('OP', $op);
    $xtpl->assign('DATA', $group_data);
    $xtpl->assign('GROUP_MANAGER_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_data['group_id']);
    $xtpl->assign('INFORM_MANAGER_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=inform&amp;manager=' . $group_data['group_id'] . '&amp;filter=active');

    $xtpl->parse('main');
    return $xtpl->text('main');
}
