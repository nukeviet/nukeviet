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
    global $module_info, $global_config, $nv_Lang, $module_name, $op, $nv_redirect;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('lostpass.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('DATA', array_merge(['checkss' => ''], $data));
    $tpl->assign('NV_REDIRECT', $nv_redirect);

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

    $tpl->assign('PASSWORD_PATTERN', $password_pattern);
    $tpl->assign('PASSWORD_RULE', $password_rule);

    // Thuộc tính captcha gắn lên form
    $array_gfx_chk = !empty($global_config['captcha_area']) ? explode(',', $global_config['captcha_area']) : [];
    $gfx_chk = (!empty($array_gfx_chk) and in_array('p', $array_gfx_chk, true)) ? 1 : 0;
    $tpl->assign('CAPTCHA_ATTRS', $gfx_chk ? nv_captcha_form_attrs('nv_seccode') : '');

    // Các liên kết chức năng khác cuối form
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

    return $tpl->fetch('lostpass.tpl');
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
    global $module_info, $global_config, $nv_Lang, $module_name, $op;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('lostactivelink.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    // Controller chỉ gán nv_seccode khi module có dùng captcha
    $tpl->assign('DATA', array_merge(['nv_seccode' => ''], $data));
    $tpl->assign('QUESTION', $question);

    // Thuộc tính captcha gắn lên form bước 1
    $array_gfx_chk = !empty($global_config['captcha_area']) ? explode(',', $global_config['captcha_area']) : [];
    $gfx_chk = (!empty($array_gfx_chk) and in_array('m', $array_gfx_chk, true)) ? 1 : 0;
    $tpl->assign('CAPTCHA_ATTRS', $gfx_chk ? nv_captcha_form_attrs('nv_seccode') : '');

    // Các liên kết chức năng khác cuối form
    $_lis = \NukeViet\Module\users\Shared\Navs::getNavs($module_info['funcs']);
    $_alias = $module_info['alias'];
    $navs = [];
    foreach ($_lis as $_li) {
        if ($_li['func_name'] == $op) {
            continue;
        }

        $navs[] = [
            'href' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']],
            'title' => $_li['func_name'] == 'main' ? $module_info['custom_title'] : $_li['func_custom_name']
        ];
    }
    $tpl->assign('NAVS', $navs);

    return $tpl->fetch('lostactivelink.tpl');
}

/**
 * Giao diện sửa thông tin tài khoản
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

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('info.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('DATA', $data);
    $tpl->assign('PASS_EMPTY', $pass_empty);
    $tpl->assign('TEMPLATE_JS', get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], NV_DEFAULT_SITE_THEME, 'js/users.passkey.js'));

    // Trưởng nhóm có quyền đổi mật khẩu thành viên thì không cần mật khẩu cũ
    $tpl->assign('SHOW_OLD_PASS', !$pass_empty and !defined('ACCESS_PASSUS'));

    // Form action, trưởng nhóm sửa thông tin thành viên thì kèm nhóm và thành viên
    $editinfo_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo';
    if (defined('ACCESS_EDITUS')) {
        $editinfo_url .= '/' . $data['group_id'] . '/' . $data['userid'];
    }
    $tpl->assign('EDITINFO_FORM', $editinfo_url);

    // Liên kết quay lại trang quản lý nhóm khi trưởng nhóm sửa thông tin thành viên
    $group_manage = [];
    if (defined('ACCESS_EDITUS')) {
        $group_manage = [
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=groups/' . $group_id,
            'title' => $nv_Lang->getModule('return_group_manage', $group_lists[$group_id]['title'])
        ];
    }
    $tpl->assign('GROUP_MANAGE', $group_manage);

    $tpl->assign('CHANGEPASS_INFO', ((int) $user_info['pass_reset_request'] == 2 and $data['type'] != 'password') ? $nv_Lang->getModule('pass_reset2_info', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/password') : '');
    $tpl->assign('CHANGEEMAIL_INFO', ((int) $user_info['email_reset_request'] == 2 and $data['type'] != 'email') ? $nv_Lang->getModule('email_reset2_info', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/email') : '');
    $tpl->assign('EMAIL_CHANGE_REQUIRED', (int) $user_info['email_reset_request'] == 1);

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

    // Các tab được hiển thị, 2step và securityprivacy chỉ là liên kết sang trang khác
    $tabs = [
        'basic' => true,
        'avatar' => in_array('avatar', $types, true),
        'username' => in_array('username', $types, true),
        'email' => in_array('email', $types, true),
        'password' => in_array('password', $types, true),
        'passkey' => in_array('passkey', $types, true) and $data['confirmed_pass'],
        'passkey_link' => in_array('passkey', $types, true) and !$data['confirmed_pass'],
        'langinterface' => in_array('langinterface', $types, true),
        '2step' => in_array('2step', $types, true),
        'question' => in_array('question', $types, true) and (isset($array_field_config['question']) or isset($array_field_config['answer'])),
        'openid' => in_array('openid', $types, true),
        'group' => in_array('group', $types, true),
        'others' => in_array('others', $types, true) and !empty($is_custom_field),
        'safemode' => in_array('safemode', $types, true),
        'securityprivacy' => in_array('securityprivacy', $types, true)
    ];
    $tpl->assign('TABS', $tabs);

    // Tab đang mở, loại không có khung nội dung thì quay về tab cơ bản
    $active = $data['type'];
    if (empty($tabs[$active]) or in_array($active, ['passkey_link', '2step', 'securityprivacy'], true)) {
        $active = 'basic';
    }
    $tpl->assign('ACTIVE', $active);

    // Tên tab đang mở, hiển thị trên nút thu gọn danh sách tab ở màn hình nhỏ
    $titles = [
        'basic' => $nv_Lang->getModule('edit_basic'),
        'avatar' => $nv_Lang->getModule('edit_avatar'),
        'username' => $nv_Lang->getModule('edit_login'),
        'email' => $nv_Lang->getModule('edit_email'),
        'password' => $nv_Lang->getModule('edit_password'),
        'passkey' => $nv_Lang->getModule('edit_passkey'),
        'langinterface' => $nv_Lang->getGlobal('langinterface'),
        'question' => $nv_Lang->getModule('edit_question'),
        'openid' => $nv_Lang->getModule('openid_administrator'),
        'group' => $nv_Lang->getModule('group'),
        'others' => $nv_Lang->getModule('edit_others'),
        'safemode' => $nv_Lang->getModule('safe_mode')
    ];
    $tpl->assign('ACTIVE_TITLE', $titles[$active]);

    $tpl->assign('URL_2STEP', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=two-step-verification', true));
    $tpl->assign('URL_SECURITY_PRIVACY', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=security-privacy', true));

    // Có trường nào có kiểu ngày tháng hay không
    $datepicker = false;

    // Thông tin cơ bản, không tồn tại trong cấu hình có nghĩa là không cho phép sửa
    $system_fields = [];
    foreach (['first_name', 'last_name', 'gender', 'birthday', 'sig'] as $key) {
        if (!isset($array_field_config[$key])) {
            continue;
        }
        $row = $array_field_config[$key];
        $row['value'] = $custom_fields[$row['field']] ?? '';
        $row['required'] = (bool) $row['required'];
        $row['callfunc'] = '';
        $row['errmess'] = fieldErrorMessage($row);

        if ($row['field'] == 'birthday') {
            $row['value'] = nv_u2d_post($row['value']);
            $row['min_old_user'] = $global_users_config['min_old_user'];
            $datepicker = true;
        } elseif ($row['field'] == 'sig') {
            $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
        } elseif ($row['field'] == 'gender') {
            $genders = [];
            foreach ($global_array_genders as $gender) {
                $gender['checked'] = ($row['value'] == $gender['key']);
                $genders[] = $gender;
            }
            $row['genders'] = $genders;
        }
        if ($row['match_type'] == 'unicodename') {
            $row['callfunc'] = $row['required'] ? 'required_person_name_check' : 'person_name_check';
            $row['errmess'] = $row['required'] ? $nv_Lang->getModule('field_req_uname_error') : $nv_Lang->getModule('field_uname_error');
        }
        $system_fields[$row['field']] = $row;
    }
    $tpl->assign('SYSTEM', $system_fields);
    $tpl->assign('VIEW_MAIL', !empty($custom_fields['view_mail']));

    // Câu hỏi và câu trả lời bảo mật, không điền sẵn giá trị cũ
    $question_fields = [];
    if ($tabs['question']) {
        foreach (['question', 'answer'] as $key) {
            if (!isset($array_field_config[$key])) {
                continue;
            }
            $row = $array_field_config[$key];
            $row['required'] = (bool) $row['required'];
            $row['errmess'] = fieldErrorMessage($row);
            $question_fields[$key] = $row;
        }
    }
    $tpl->assign('QUESTION_FIELDS', $question_fields);
    $tpl->assign('QUESTIONS', $data_questions);

    // Khóa đăng nhập
    $login_keys = [];
    if ($tabs['passkey'] and !empty($data['login_keys'])) {
        foreach ($data['publicKeys'] as $publicKey) {
            if (empty($publicKey['enable_login'])) {
                continue;
            }
            $publicKey['created_at'] = nv_datetime_format($publicKey['created_at'], 1);
            $publicKey['last_used_at'] = nv_datetime_format($publicKey['last_used_at'], 1);
            $publicKey['is_this_client'] = ($publicKey['clid'] == $client_info['clid']);
            $login_keys[] = $publicKey;
        }
    }
    $tpl->assign('LOGIN_KEYS', $login_keys);

    // Ngôn ngữ giao diện
    $langs = [];
    if ($tabs['langinterface']) {
        foreach ($global_config['allow_sitelangs'] as $lang_i) {
            $langs[] = [
                'val' => $lang_i,
                'name' => !empty($language_array[$lang_i]['name']) ? $language_array[$lang_i]['name'] : $lang_i
            ];
        }
    }
    $tpl->assign('LANGS', $langs);

    // Tài khoản bên thứ ba đã kết nối và các nhà cung cấp có thể kết nối thêm
    $openids = [];
    $openid_del_count = 0;
    $openid_servers = [];
    if ($tabs['openid']) {
        foreach ($data_openid as $openid) {
            $openids[] = [
                'opid' => $openid['opid'] . '_' . $openid['openid'],
                'openid' => ucwords($openid['openid']),
                'email_or_id' => !empty($openid['email']) ? $openid['email'] : $openid['id'],
                'disabled' => $openid['disabled']
            ];
            if (!$openid['disabled']) {
                ++$openid_del_count;
            }
        }
        foreach ($global_config['openid_servers'] as $server) {
            $openid_servers[] = [
                'server' => $server,
                'icon' => $server == 'google-identity' ? 'google' : $server,
                'title' => ucfirst($server),
                'href' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=oauth&amp;server=' . $server
            ];
        }
    }
    $tpl->assign('OPENIDS', $openids);
    $tpl->assign('OPENID_DEL_COUNT', $openid_del_count);
    $tpl->assign('OPENID_SERVERS', $openid_servers);

    // Nhóm thành viên
    $groups_list = [];
    $group_check_all = true;
    if ($tabs['group']) {
        foreach ($groups as $group) {
            $group['checked'] = ($group['status'] > 0);
            $group['status_mess'] = $nv_Lang->getModule('group_status_' . $group['status']);
            $group['group_type_mess'] = $nv_Lang->getModule('group_type_' . $group['group_type']);
            $group['group_type_note'] = $nv_Lang->existsModule('group_type_' . $group['group_type'] . '_note') ? $nv_Lang->getModule('group_type_' . $group['group_type'] . '_note') : '';
            $group['leader_url'] = $group['is_leader'] ? nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=groups/' . $group['group_id'], true) : '';
            if (!$group['checked']) {
                $group_check_all = false;
            }
            $groups_list[] = $group;
        }
    }
    $tpl->assign('GROUPS', $groups_list);
    $tpl->assign('GROUP_CHECK_ALL', $group_check_all);

    // Các trường dữ liệu tùy chỉnh
    $custom_fields_list = [];
    if ($tabs['others']) {
        foreach ($array_field_config as $row) {
            if (!empty($row['system'])) {
                continue;
            }
            $row['value'] = $custom_fields[$row['field']] ?? get_value_by_lang($row['default_value']);
            $row['required'] = (bool) $row['required'];
            $row['callfunc'] = '';
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
                // limited_values đã được giải mã JSON ở controller
                $row['fileaccept'] = !empty($row['limited_values']['mime']) ? '.' . implode(',.', $row['limited_values']['mime']) : '';
                $row['filemaxsize'] = $row['limited_values']['file_max_size'] ?? 0;
                $row['filemaxsize_format'] = nv_convertfromBytes($row['limited_values']['file_max_size'] ?? 0);
                $row['filemaxnum'] = $row['limited_values']['maxnum'] ?? 0;
                $row['csrf'] = csrf_create($module_name . '_field_' . $row['field']);
                $row['url_module'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
                $row['widthlimit'] = image_size_info($row['limited_values']['widthlimit'], 'width');
                $row['heightlimit'] = image_size_info($row['limited_values']['heightlimit'], 'height');

                // Các tệp đã tải lên trước đó
                $row['files'] = [];
                $filelist = !empty($row['value']) ? explode(',', $row['value']) : [];
                foreach ($filelist as $file_item) {
                    $file = file_type_name($file_item);
                    $file['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;userfile=' . $file_item . '&amp;field=' . $row['field'];
                    if (defined('ACCESS_EDITUS')) {
                        $file['url'] .= '&amp;groupid=' . $data['group_id'] . '&amp;userid=' . $data['userid'];
                    }
                    $row['files'][] = $file;
                }
                $row['addfile_hidden'] = (!empty($row['filemaxnum']) and count($row['files']) >= $row['filemaxnum']);
            }
            if (($row['field_type'] == 'textbox' or $row['field_type'] == 'number') and $row['match_type'] == 'unicodename') {
                $row['callfunc'] = $row['required'] ? 'required_person_name_check' : 'person_name_check';
            }

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
    $tpl->assign('FIELDS', $custom_fields_list);
    $tpl->assign('DATEPICKER', $datepicker);

    // Ảnh đại diện, src rỗng tpl sẽ chuyển sang dạng avatar chữ
    $avatar = [];
    if ($tabs['avatar']) {
        $avatar = [
            'src' => $user_info['avata'] ?? '',
            'letters' => $user_info['avatar_letters'] ?? nv_user_avatar_letters($user_info['first_name'] ?? '', $user_info['last_name'] ?? '', $user_info['username'] ?? ''),
            'color' => $user_info['avatar_color'] ?? nv_user_avatar_color($user_info['username'] ?? ''),
            'direct_change' => (!empty($data['avatar_direct_change']) and $data['type'] == 'avatar')
        ];
    }
    $tpl->assign('AVATAR', $avatar);

    // Menu điều hướng cuối trang
    $_lis = \NukeViet\Module\users\Shared\Navs::getNavs($module_info['funcs']);
    $_alias = $module_info['alias'];
    $navs = [];
    foreach ($_lis as $_li) {
        if ($_li['func_name'] == $op) {
            continue;
        }

        $navs[] = [
            'href' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']],
            'title' => $_li['func_name'] == 'main' ? $nv_Lang->getModule('user_info') : $_li['func_custom_name']
        ];
    }
    $tpl->assign('NAVS', $navs);

    return $tpl->fetch('info.tpl');
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

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('safe.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('DATA', $data);
    $tpl->assign('NV_REDIRECT', $nv_redirect);

    // Các liên kết chức năng khác cuối trang
    $_lis = \NukeViet\Module\users\Shared\Navs::getNavs($module_info['funcs']);
    $_alias = $module_info['alias'];
    $navs = [];
    foreach ($_lis as $_li) {
        if ($_li['func_name'] == $op) {
            continue;
        }

        $navs[] = [
            'href' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']],
            'title' => $_li['func_name'] == 'main' ? $nv_Lang->getModule('user_info') : $_li['func_custom_name']
        ];
    }
    $tpl->assign('NAVS', $navs);

    return $tpl->fetch('safe.tpl');
}

/**
 * @param int $pass_timeout
 * @param bool $pass_empty
 * @param string $checkss
 * @return string
 */
function theme_changePass($pass_timeout, $pass_empty, $checkss)
{
    global $module_name, $nv_Lang, $global_config;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('changepass.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('CHECKSS', $checkss);
    $tpl->assign('PASS_EMPTY', $pass_empty);
    $tpl->assign('LOGOUT_TOGGLE', defined('NV_IS_ADMIN') ? 'nv_admin_logout' : 'bt_logout');
    // Dùng URL đã rewrite để index.php nhận ra trang logout khi đang bắt buộc đổi mật khẩu
    $tpl->assign('LOGOUT_URL', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=logout', true));
    $tpl->assign('CHANGEPASS_INFO', $pass_timeout ? $nv_Lang->getModule('pass_reset3_info', floor($global_config['pass_timeout'] / 86400)) : $nv_Lang->getModule('pass_reset1_info'));

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

    $tpl->assign('PASSWORD_PATTERN', $password_pattern);
    $tpl->assign('PASSWORD_RULE', $password_rule);

    return $tpl->fetch('changepass.tpl');
}

/**
 * @param array $data
 * @param string $page_url
 * @return string
 */
function user_r2s($data, $page_url)
{
    global $module_name, $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('r2s.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('FORM_ACTION', $page_url);
    $tpl->assign('DATA', $data);

    return $tpl->fetch('r2s.tpl');
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
    global $checkss, $limit, $module_name, $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('security_privacy.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    // Phần tử thứ $limit chỉ dùng để xác định còn phiên đăng nhập để tải thêm
    $tpl->assign('LOGINS', array_slice($array_logins, 0, max(0, $limit - 1)));

    // Tải thêm phiên đăng nhập chỉ trả về danh sách
    if ($array['loadmorelogins']) {
        return $tpl->fetch('security_privacy_logins.tpl');
    }

    $tpl->assign('CHECKSS', $checkss);
    $tpl->assign('DATA', $array);
    $tpl->assign('NEXT_OFFSET', $array_logins[$limit - 1]['id'] ?? 0);
    $tpl->assign('HAS_MORE', count($array_logins) > ($limit - 1));
    $tpl->assign('HAS_LOGOUT_ALL', count($array_logins) > 1);

    return $tpl->fetch('security_privacy.tpl');
}

/**
 * Giao diện trang xác thực mật khẩu
 *
 * @param array $array
 * @return string
 */
function user_verify_password(array $array): string
{
    global $checkss, $module_name, $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('verify_password.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('CHECKSS', $checkss);
    $tpl->assign('DATA', $array);

    // Thuộc tính captcha gắn lên form
    $tpl->assign('CAPTCHA_ATTRS', nv_captcha_form_attrs('nv_seccode'));

    return $tpl->fetch('verify_password.tpl');
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
    global $global_config, $op, $module_name, $module_info, $nv_Lang, $nv_redirect;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('groups.tpl'));
    $tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
    $tpl->registerPlugin('modifier', 'dnumber', 'nv_number_format');

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('OP', $op);
    $tpl->assign('MODULE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE);

    // Danh sách nhóm
    $groups = [];
    foreach ($groupsList as $group_id => $values) {
        $groups[$group_id] = [
            'group_id' => $group_id,
            'title' => $values['title'],
            'add_time' => (int) $values['add_time'],
            'exp_time' => (int) $values['exp_time'],
            'numbers' => (int) $values['numbers'],
            'link_userlist' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_id, true)
        ];
    }
    $tpl->assign('GROUPS', $groups);

    // Menu điều hướng cuối trang
    $navs = [];
    $_lis = \NukeViet\Module\users\Shared\Navs::getNavs($module_info['funcs']);
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
        if (!empty($nv_redirect)) {
            $href .= '&amp;nv_redirect=' . $nv_redirect;
        }
        $navs[] = [
            'href' => $href,
            'title' => $_li['func_name'] == 'main' ? $nv_Lang->getModule('user_info') : $_li['func_custom_name']
        ];
    }
    $tpl->assign('NAVS', $navs);

    return $tpl->fetch('groups.tpl');
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

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('groups_users.tpl'));
    $tpl->registerPlugin('modifier', 'dnumber', 'nv_number_format');

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('OP', $op);
    $tpl->assign('MODULE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE);

    // Công cụ quản lý nhóm chỉ dành cho nhóm tự tạo
    $show_tools = $group_data['group_id'] > 9;

    $tpl->assign('DATA', $group_data);
    $tpl->assign('GROUP_USERS', $group_users);
    $tpl->assign('PER_PAGE', $per_page);
    $tpl->assign('SHOW_TOOLS', $show_tools);
    $tpl->assign('SHOW_ADD_MEMBER', $show_tools && !empty($group_data['config']['access_groups_add']));
    $tpl->assign('MIN_SEARCH', $nv_Lang->getModule('min_search', NV_MIN_SEARCH_LENGTH));
    $tpl->assign('EDIT_GROUP_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_data['group_id'] . '/edit');
    $tpl->assign('INFORM_NOTIFICATIONS_URL', ($show_tools && !empty($global_config['inform_active'])) ? NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_data['group_id'] . '/inform' : '');

    return $tpl->fetch('groups_users.tpl');
}

/**
 * Giao diện trang tìm kiếm người dùng đợi kích hoạt của nhóm
 *
 * @param int $gid
 * @return string
 */
function user_groups_getuserid(int $gid): string
{
    global $global_config, $op, $module_name, $nv_Lang, $g_csrf_key, $op_file;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('groups_getuserid.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('OP', $op);
    $tpl->assign('CHECKSS', csrf_create($g_csrf_key[$op_file]));
    $tpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;gid=' . $gid . '&amp;getuserid=1');

    return $tpl->fetch('groups_getuserid.tpl');
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
    global $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('groups_getuserid_result.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('SEARCH', $array);
    $tpl->assign('USERS', $array_user);
    $tpl->assign('GENERATE_PAGE', !empty($array_user) ? ($array['generate_page'] ?? '') : '');

    return $tpl->fetch('groups_getuserid_result.tpl');
}

/**
 * Giao diện chỉnh sửa thông tin nhóm
 *
 * @param array $group_data
 * @return string
 */
function user_groups_edit(array $group_data): string
{
    global $global_config, $op, $module_name, $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('groups_edit.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('OP', $op);
    $tpl->assign('DATA', $group_data);
    $tpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_data['group_id'] . '/edit');

    return $tpl->fetch('groups_edit.tpl');
}

/**
 * Giao diện thông báo của nhóm
 *
 * @param array $group_data
 * @return string
 */
function user_groups_inform(array $group_data): string
{
    global $global_config, $op, $module_name, $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('groups_inform.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('OP', $op);
    $tpl->assign('DATA', $group_data);
    $tpl->assign('GROUP_MANAGER_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/' . $group_data['group_id']);
    $tpl->assign('INFORM_MANAGER_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=inform&amp;manager=' . $group_data['group_id'] . '&amp;filter=active');

    return $tpl->fetch('groups_inform.tpl');
}
