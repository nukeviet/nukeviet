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

/**
 * user_register()
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
    global $module_info, $global_config, $lang_global, $lang_module, $module_name, $module_captcha, $op, $nv_redirect, $global_array_genders;

    $xtpl = new XTemplate('register.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('NICK_MAXLENGTH', $global_config['nv_unickmax']);
    $xtpl->assign('NICK_MINLENGTH', $global_config['nv_unickmin']);
    $xtpl->assign('PASS_MAXLENGTH', $global_config['nv_upassmax']);
    $xtpl->assign('PASS_MINLENGTH', $global_config['nv_upassmin']);
    $xtpl->assign('LOGINTYPE', $global_config['nv_unick_type']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('CHECKSS', $checkss);

    if ($group_id != 0) {
        $xtpl->assign('USER_REGISTER', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=register/' . $group_id);
    } else {
        $xtpl->assign('USER_REGISTER', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=register');
        $xtpl->parse('main.agreecheck');
    }

    $username_rule = empty($global_config['nv_unick_type']) ? sprintf($lang_global['username_rule_nolimit'], $global_config['nv_unickmin'], $global_config['nv_unickmax']) : sprintf($lang_global['username_rule_limit'], $lang_global['unick_type_' . $global_config['nv_unick_type']], $global_config['nv_unickmin'], $global_config['nv_unickmax']);
    $password_rule = empty($global_config['nv_upass_type']) ? sprintf($lang_global['password_rule_nolimit'], $global_config['nv_upassmin'], $global_config['nv_upassmax']) : sprintf($lang_global['password_rule_limit'], $lang_global['upass_type_' . $global_config['nv_upass_type']], $global_config['nv_upassmin'], $global_config['nv_upassmax']);

    $xtpl->assign('USERNAME_RULE', $username_rule);
    $xtpl->assign('PASSWORD_RULE', $password_rule);

    // Có trường nào có kiểu ngày tháng hay không
    $datepicker = false;
    // Có trường tùy chỉnh hay không
    $have_custom_fields = false;
    // Có hiển thị họ hoặc tên hay không
    $have_name_field = false;

    foreach ($array_field_config as $_k => $row) {
        $row['customID'] = $_k;

        if ($row['show_register']) {
            // Value luôn là giá trị mặc định
            if (!empty($row['field_choices'])) {
                if ($row['field_type'] == 'date') {
                    $row['value'] = ($row['field_choices']['current_date']) ? NV_CURRENTTIME : $row['default_value'];
                } elseif ($row['field_type'] == 'number') {
                    $row['value'] = $row['default_value'];
                } else {
                    $temp = array_keys($row['field_choices']);
                    $tempkey = (int) ($row['default_value']) - 1;
                    $row['value'] = (isset($temp[$tempkey])) ? $temp[$tempkey] : '';
                }
            } else {
                $row['value'] = $row['default_value'];
            }

            $row['required'] = ($row['required']) ? 'required' : '';
            $xtpl->assign('FIELD', $row);

            // Các trường hệ thống xuất độc lập
            if (!empty($row['system'])) {
                if ($row['field'] == 'birthday') {
                    $row['value'] = (empty($row['value'])) ? '' : date('d/m/Y', $row['value']);
                    $datepicker = true;
                } elseif ($row['field'] == 'sig') {
                    $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
                }
                $xtpl->assign('FIELD', $row);
                if ($row['field'] == 'first_name' or $row['field'] == 'last_name') {
                    $have_name_field = true;
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
                        $xtpl->assign('ERRMESS', $lang_module['field_req_uname_error']);
                    } else {
                        $xtpl->assign('CALLFUNC', 'uname_check');
                        $xtpl->assign('ERRMESS', $lang_module['field_uname_error']);
                    }
                    $xtpl->parse('main.' . $show_key . '.data_callback');
                }
                if ($row['field'] == 'gender') {
                    foreach ($global_array_genders as $gender) {
                        $gender['checked'] = $row['value'] == $gender['key'] ? ' checked="checked"' : '';
                        $xtpl->assign('GENDER', $gender);
                        $xtpl->parse('main.' . $show_key . '.gender');
                    }
                } elseif ($row['field'] == 'question') {
                    foreach ($data_questions as $array_question_i) {
                        $xtpl->assign('QUESTION', $array_question_i['title']);
                        $xtpl->parse('main.' . $show_key . '.frquestion');
                    }
                }
                if ($row['description']) {
                    $xtpl->parse('main.' . $show_key . '.description');
                }
                $xtpl->parse('main.' . $show_key);
            } else {
                if ($row['required']) {
                    $xtpl->parse('main.field.loop.required');
                }
                if ($row['field_type'] == 'textbox' or $row['field_type'] == 'number') {
                    if ($row['match_type'] == 'unicodename') {
                        if ($row['required']) {
                            $xtpl->assign('CALLFUNC', 'required_uname_check');
                            $xtpl->assign('ERRMESS', $lang_module['field_req_uname_error']);
                        } else {
                            $xtpl->assign('CALLFUNC', 'uname_check');
                            $xtpl->assign('ERRMESS', $lang_module['field_uname_error']);
                        }
                        $xtpl->parse('main.field.loop.textbox.data_callback');
                    }
                    $xtpl->parse('main.field.loop.textbox');
                } elseif ($row['field_type'] == 'date') {
                    $row['value'] = (empty($row['value'])) ? '' : date('d/m/Y', $row['value']);
                    $xtpl->assign('FIELD', $row);
                    $xtpl->parse('main.field.loop.date');
                    $datepicker = true;
                } elseif ($row['field_type'] == 'textarea') {
                    $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
                    $xtpl->assign('FIELD', $row);
                    $xtpl->parse('main.field.loop.textarea');
                } elseif ($row['field_type'] == 'editor') {
                    $row['value'] = htmlspecialchars(nv_editor_br2nl($row['value']));
                    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
                        $array_tmp = explode('@', $row['class']);
                        $edits = nv_aleditor('custom_fields[' . $row['field'] . ']', $array_tmp[0], $array_tmp[1], $row['value']);
                        $xtpl->assign('EDITOR', $edits);
                        $xtpl->parse('main.field.loop.editor');
                    } else {
                        $row['class'] = '';
                        $xtpl->assign('FIELD', $row);
                        $xtpl->parse('main.field.loop.textarea');
                    }
                } elseif ($row['field_type'] == 'select') {
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', [
                            'key' => $key,
                            'selected' => ($key == $row['value']) ? ' selected="selected"' : '',
                            'value' => $value
                        ]);
                        $xtpl->parse('main.field.loop.select.loop');
                    }
                    $xtpl->parse('main.field.loop.select');
                } elseif ($row['field_type'] == 'radio') {
                    $number = 0;
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', [
                            'id' => $row['fid'] . '_' . $number++,
                            'key' => $key,
                            'checked' => ($key == $row['value']) ? ' checked="checked"' : '',
                            'value' => $value
                        ]);
                        $xtpl->parse('main.field.loop.radio.loop');
                    }
                    $xtpl->parse('main.field.loop.radio');
                } elseif ($row['field_type'] == 'checkbox') {
                    $number = 0;
                    $valuecheckbox = (!empty($row['value'])) ? explode(',', $row['value']) : [];
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', [
                            'id' => $row['fid'] . '_' . $number++,
                            'key' => $key,
                            'checked' => (in_array((string) $key, $valuecheckbox, true)) ? ' checked="checked"' : '',
                            'value' => $value
                        ]);
                        $xtpl->parse('main.field.loop.checkbox.loop');
                    }
                    $xtpl->parse('main.field.loop.checkbox');
                } elseif ($row['field_type'] == 'multiselect') {
                    $valueselect = (!empty($row['value'])) ? explode(',', $row['value']) : [];
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', [
                            'key' => $key,
                            'selected' => (in_array((string) $key, $valueselect, true)) ? ' selected="selected"' : '',
                            'value' => $value
                        ]);
                        $xtpl->parse('main.field.loop.multiselect.loop');
                    }
                    $xtpl->parse('main.field.loop.multiselect');
                }
                $xtpl->parse('main.field.loop');
                $have_custom_fields = true;
            }
        }
    }

    if ($have_name_field) {
        $xtpl->parse('main.name_show_' . $global_config['name_show']);
    }

    if ($have_custom_fields) {
        $xtpl->parse('main.field');
    }

    if ($datepicker) {
        $xtpl->parse('main.datepicker');
    }

    if ($gfx_chk) {
        if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
            $xtpl->parse('main.reg_recaptcha3');
        } elseif ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
            $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
            $xtpl->parse('main.reg_recaptcha');
        } elseif ($module_captcha == 'captcha') {
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
            $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
            $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
            $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
            $xtpl->assign('CAPTCHA_REFR_SRC', NV_STATIC_URL . NV_ASSETS_DIR . '/images/refresh.png');
            $xtpl->assign('SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha&t=' . NV_CURRENTTIME);
            $xtpl->assign('GFX_MAXLENGTH', NV_GFX_NUM);
            $xtpl->parse('main.reg_captcha');
        }
    }

    if (!empty($nv_redirect)) {
        $xtpl->assign('REDIRECT', $nv_redirect);
        $xtpl->parse('main.redirect');
    }

    if ($global_config['allowuserreg'] == 2) {
        $xtpl->assign('LOSTACTIVELINK_SRC', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostactivelink');
        $xtpl->parse('main.lostactivelink');
    }

    if (defined('NV_IS_USER') and !defined('ACCESS_ADDUS')) {
        $xtpl->parse('main.agreecheck');
    }

    $_lis = $module_info['funcs'];
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        if ($_li['show_func'] and $_li['in_submenu'] and $_li['func_name'] != 'main') {
            if ($_li['func_name'] == $op or $_li['func_name'] == 'avatar' or $_li['func_name'] == 'groups') {
                continue;
            }
            if ($_li['func_name'] == 'register' and !$global_config['allowuserreg']) {
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
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * user_login()
 *
 * @param bool $is_ajax
 * @return string
 */
function user_login($is_ajax = false)
{
    global $module_info, $global_config, $lang_global, $lang_module, $module_name, $module_captcha, $op, $nv_header, $nv_redirect, $page_url;

    if ($is_ajax) {
        $xtpl = new XTemplate('ajax_login.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/users');
    } else {
        $xtpl = new XTemplate('login.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/users');
    }

    $xtpl->assign('USER_LOGIN', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login');
    $xtpl->assign('USER_LOSTPASS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostpass');
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('TEMPLATE', $module_info['template']);

    $array_gfx_chk = !empty($global_config['captcha_area']) ? explode(',', $global_config['captcha_area']) : [];
    $gfx_chk = (!empty($array_gfx_chk) and in_array('l', $array_gfx_chk, true)) ? 1 : 0;

    if ($gfx_chk) {
        // Nếu dùng reCaptcha v3
        if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
            $xtpl->parse('main.recaptcha3');
        }
        // Nếu dùng reCaptcha v2
        elseif ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
            $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
            $xtpl->parse('main.recaptcha.default');
            $xtpl->parse('main.recaptcha');
        } elseif ($module_captcha == 'captcha') {
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
            $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
            $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
            $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
            $xtpl->assign('SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha&t=' . NV_CURRENTTIME);
            $xtpl->assign('GFX_MAXLENGTH', NV_GFX_NUM);
            $xtpl->parse('main.captcha');
        }
    }

    if (!empty($nv_redirect)) {
        $xtpl->assign('REDIRECT', $nv_redirect);
        $xtpl->parse('main.redirect');
    } else {
        $xtpl->parse('main.not_redirect');
    }

    if (!empty($nv_header)) {
        $xtpl->assign('NV_HEADER', $nv_header);
        $xtpl->parse('main.header');

        // Hiển thị logo tại login box
        $xtpl->assign('SITE_NAME', $global_config['site_name']);
        $xtpl->assign('THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
        $xtpl->assign('LOGO_SRC', NV_BASE_SITEURL . $global_config['site_logo']);
        $xtpl->parse('main.redirect2');
    }

    if (defined('NV_OPENID_ALLOWED')) {
        $assigns = [];
        $icons = [
            'single-sign-on' => 'lock',
            'google' => 'google-plus',
            'facebook' => 'facebook',
            'zalo' => 'zalo'
        ];
        $default_redirect = nv_redirect_encrypt(NV_MY_DOMAIN . (empty($page_url) ? '' : nv_url_rewrite(str_replace('&amp;', '&', $page_url), true)));
        foreach ($global_config['openid_servers'] as $server) {
            $assigns['href'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=oauth&amp;server=' . $server;
            if (!empty($nv_redirect)) {
                $assigns['href'] .= '&nv_redirect=' . $nv_redirect;
            } else {
                $assigns['href'] .= '&nv_redirect=' . $default_redirect;
            }
            $assigns['server'] = $server;
            $assigns['title'] = ucfirst($server);
            $assigns['icon'] = $icons[$server];

            $xtpl->assign('OPENID', $assigns);
            $xtpl->parse('main.openid.server');
        }

        $xtpl->parse('main.openid');
    }

    $_lis = $module_info['funcs'];
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        if ($_li['show_func'] and $_li['in_submenu'] and $_li['func_name'] != 'main') {
            if ($_li['func_name'] == $op or $_li['func_name'] == 'avatar' or $_li['func_name'] == 'groups') {
                continue;
            }
            if ($_li['func_name'] == 'register' and !$global_config['allowuserreg']) {
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
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * user_openid_login()
 * 
 * @param mixed $gfx_chk 
 * @param mixed $attribs 
 * @param array $op_process 
 * @return string 
 */
function user_openid_login($attribs, $op_process)
{
    global $module_info, $global_config, $lang_global, $lang_module, $module_name, $nv_redirect;

    $xtpl = new XTemplate('openid_login.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/users');

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
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    $op_process_count = count($op_process);

    if ($op_process_count > 1) {
        foreach($op_process as $process => $val) {
            $xtpl->assign('ACTION', [
                'key' => $process,
                'name' => $lang_module['openid_processing_' . $process]
            ]);
            $xtpl->parse('main.choose_action.option');
        }
        $xtpl->parse('main.choose_action');
    }

    $first = array_keys($op_process);
    $first = array_shift($first);
    $info = $op_process_count > 1 ? $lang_module['openid_note'] : $lang_module['openid_' . $first . '_note'];
    $xtpl->assign('INFO', $info);

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
    global $module_info, $global_config, $lang_global, $lang_module, $module_name, $module_captcha, $op, $nv_redirect;

    $xtpl = new XTemplate('lostpass.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('DATA', $data);
    $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostpass');

    $array_gfx_chk = !empty($global_config['captcha_area']) ? explode(',', $global_config['captcha_area']) : [];

    if (!empty($array_gfx_chk) and in_array('p', $array_gfx_chk, true)) {
        // Nếu dùng reCaptcha v3
        if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
            $xtpl->parse('main.recaptcha3');
        }
        // Nếu dùng reCaptcha v2
        elseif ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
            $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
            $xtpl->parse('main.recaptcha');
        } elseif ($module_captcha == 'captcha') {
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
            $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
            $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
            $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
            $xtpl->assign('SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha&t=' . NV_CURRENTTIME);
            $xtpl->assign('GFX_MAXLENGTH', NV_GFX_NUM);
            $xtpl->parse('main.captcha');
        }
    }

    if (!empty($nv_redirect)) {
        $xtpl->assign('REDIRECT', $nv_redirect);
        $xtpl->parse('main.redirect');
    }

    $_lis = $module_info['funcs'];
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        if ($_li['show_func'] and $_li['in_submenu'] and $_li['func_name'] != 'main') {
            if ($_li['func_name'] == $op or $_li['func_name'] == 'avatar' or $_li['func_name'] == 'groups') {
                continue;
            }
            if ($_li['func_name'] == 'register' and !$global_config['allowuserreg']) {
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
    global $module_info, $global_config, $lang_global, $lang_module, $module_name, $module_captcha, $op;

    $xtpl = new XTemplate('lostactivelink.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
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
                $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
                $xtpl->parse('main.step1.recaptcha');
            } elseif ($module_captcha == 'captcha') {
                $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
                $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
                $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
                $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
                $xtpl->assign('CAPTCHA_REFR_SRC', NV_STATIC_URL . NV_ASSETS_DIR . '/images/refresh.png');
                $xtpl->assign('SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha&t=' . NV_CURRENTTIME);
                $xtpl->assign('GFX_MAXLENGTH', NV_GFX_NUM);
                $xtpl->parse('main.step1.captcha');
            }
        }

        $xtpl->parse('main.step1');
    }

    $_lis = $module_info['funcs'];
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        if ($_li['show_func'] and $_li['in_submenu'] and $_li['func_name'] != 'main') {
            if ($_li['func_name'] == $op or $_li['func_name'] == 'avatar' or $_li['func_name'] == 'groups') {
                continue;
            }
            if ($_li['func_name'] == 'register' and !$global_config['allowuserreg']) {
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
    global $module_info, $global_config, $lang_global, $lang_module, $module_name, $op, $global_array_genders, $is_custom_field;

    $xtpl = new XTemplate('info.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);

    if (defined('ACCESS_EDITUS')) {
        $xtpl->assign('EDITINFO_FORM', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/' . $data['group_id'] . '/' . $data['userid']);
    } else {
        $xtpl->assign('EDITINFO_FORM', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo');
    }

    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
    $xtpl->assign('AVATAR_DEFAULT', NV_STATIC_URL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/no_avatar.png');
    $xtpl->assign('URL_AVATAR', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=avatar/src', true));

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('NICK_MAXLENGTH', $global_config['nv_unickmax']);
    $xtpl->assign('NICK_MINLENGTH', $global_config['nv_unickmin']);
    $xtpl->assign('PASS_MAXLENGTH', $global_config['nv_upassmax']);
    $xtpl->assign('PASS_MINLENGTH', $global_config['nv_upassmin']);
    $xtpl->assign('LOGINTYPE', $global_config['nv_unick_type']);

    $xtpl->assign('URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=');
    $xtpl->assign('URL_MODULE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);

    $username_rule = empty($global_config['nv_unick_type']) ? sprintf($lang_global['username_rule_nolimit'], $global_config['nv_unickmin'], $global_config['nv_unickmax']) : sprintf($lang_global['username_rule_limit'], $lang_global['unick_type_' . $global_config['nv_unick_type']], $global_config['nv_unickmin'], $global_config['nv_unickmax']);
    $password_rule = empty($global_config['nv_upass_type']) ? sprintf($lang_global['password_rule_nolimit'], $global_config['nv_upassmin'], $global_config['nv_upassmax']) : sprintf($lang_global['password_rule_limit'], $lang_global['upass_type_' . $global_config['nv_upass_type']], $global_config['nv_upassmin'], $global_config['nv_upassmax']);

    $xtpl->assign('USERNAME_RULE', $username_rule);
    $xtpl->assign('PASSWORD_RULE', $password_rule);

    $xtpl->assign('DATA', $data);
    if ($pass_empty) {
        $xtpl->assign('FORM_HIDDEN', ' hidden');
    }

    // Thông tin cơ bản
    $array_basic_key = [
        'first_name',
        'last_name',
        'gender',
        'birthday',
        'sig'
    ];
    foreach ($array_basic_key as $key) {
        // Không tồn tại có nghĩa là không cho phép sửa
        if (isset($array_field_config[$key])) {
            $row = $array_field_config[$key];
            $row['value'] = (isset($custom_fields[$row['field']])) ? $custom_fields[$row['field']] : '';
            $row['required'] = ($row['required']) ? 'required' : '';
            if ($row['field'] == 'birthday') {
                $row['value'] = (empty($row['value'])) ? '' : date('d/m/Y', $row['value']);
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
                    $xtpl->assign('ERRMESS', $lang_module['field_req_uname_error']);
                } else {
                    $xtpl->assign('CALLFUNC', 'uname_check');
                    $xtpl->assign('ERRMESS', $lang_module['field_uname_error']);
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

    // Tab đổi tên đăng nhập
    if (in_array('username', $types, true)) {
        if ($pass_empty) {
            $xtpl->parse('main.tab_edit_username.username_empty_pass');
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
        $xtpl->parse('main.tab_edit_password');
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
        $xtpl->parse('main.edit_email');
        $xtpl->parse('main.tab_edit_email');
    }

    // Tab quản lý openid
    if (in_array('openid', $types, true)) {
        if (!empty($data_openid)) {
            $openid_del_al = 0;
            foreach ($data_openid as $openid) {
                $openid['email_or_id'] = !empty($openid['email']) ? $openid['email'] : $openid['id'];
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
            $assigns = [];
            $assigns['href'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=oauth&amp;server=' . $server;
            $assigns['title'] = ucfirst($server);
            $assigns['img_src'] = NV_STATIC_URL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/' . $server . '.png';
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
            $group['status_mess'] = $lang_module['group_status_' . $group['status']];
            $group['group_type_mess'] = $lang_module['group_type_' . $group['group_type']];
            $group['group_type_note'] = !empty($lang_module['group_type_' . $group['group_type'] . '_note']) ? $lang_module['group_type_' . $group['group_type'] . '_note'] : '';
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
                $row['value'] = (isset($custom_fields[$row['field']])) ? $custom_fields[$row['field']] : $row['default_value'];
                $row['required'] = ($row['required']) ? 'required' : '';

                $xtpl->assign('FIELD', $row);

                if ($row['required']) {
                    $xtpl->parse('main.tab_edit_others.loop.required');
                }

                if ($row['field_type'] == 'textbox' or $row['field_type'] == 'number') {
                    if ($row['match_type'] == 'unicodename') {
                        if ($row['required']) {
                            $xtpl->assign('CALLFUNC', 'required_uname_check');
                            $xtpl->assign('ERRMESS', $lang_module['field_req_uname_error']);
                        } else {
                            $xtpl->assign('CALLFUNC', 'uname_check');
                            $xtpl->assign('ERRMESS', $lang_module['field_uname_error']);
                        }
                        $xtpl->parse('main.tab_edit_others.loop.textbox.data_callback');
                    }
                    $xtpl->parse('main.tab_edit_others.loop.textbox');
                } elseif ($row['field_type'] == 'date') {
                    $row['value'] = (empty($row['value'])) ? '' : date('d/m/Y', $row['value']);
                    $xtpl->assign('FIELD', $row);
                    $xtpl->parse('main.tab_edit_others.loop.date');
                } elseif ($row['field_type'] == 'textarea') {
                    $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
                    $xtpl->assign('FIELD', $row);
                    $xtpl->parse('main.tab_edit_others.loop.textarea');
                } elseif ($row['field_type'] == 'editor') {
                    $row['value'] = htmlspecialchars(nv_editor_br2nl($row['value']));
                    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
                        $array_tmp = explode('@', $row['class']);
                        $edits = nv_aleditor('custom_fields[' . $row['field'] . ']', $array_tmp[0], $array_tmp[1], $row['value'], 'Basic');
                        $xtpl->assign('EDITOR', $edits);
                        $xtpl->parse('main.tab_edit_others.loop.editor');
                    } else {
                        $row['class'] = '';
                        $xtpl->assign('FIELD', $row);
                        $xtpl->parse('main.tab_edit_others.loop.textarea');
                    }
                } elseif ($row['field_type'] == 'select') {
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', [
                            'key' => $key,
                            'selected' => ($key == $row['value']) ? ' selected="selected"' : '',
                            'value' => $value
                        ]);
                        $xtpl->parse('main.tab_edit_others.loop.select.loop');
                    }
                    $xtpl->parse('main.tab_edit_others.loop.select');
                } elseif ($row['field_type'] == 'radio') {
                    $number = 0;
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', [
                            'id' => $row['fid'] . '_' . $number++,
                            'key' => $key,
                            'checked' => ($key == $row['value']) ? ' checked="checked"' : '',
                            'value' => $value
                        ]);
                        $xtpl->parse('main.tab_edit_others.loop.radio.loop');
                    }
                    $xtpl->parse('main.tab_edit_others.loop.radio');
                } elseif ($row['field_type'] == 'checkbox') {
                    $number = 0;
                    $valuecheckbox = (!empty($row['value'])) ? explode(',', $row['value']) : [];

                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', [
                            'id' => $row['fid'] . '_' . $number++,
                            'key' => $key,
                            'checked' => (in_array((string) $key, $valuecheckbox, true)) ? ' checked="checked"' : '',
                            'value' => $value
                        ]);
                        $xtpl->parse('main.tab_edit_others.loop.checkbox.loop');
                    }
                    $xtpl->parse('main.tab_edit_others.loop.checkbox');
                } elseif ($row['field_type'] == 'multiselect') {
                    $valueselect = (!empty($row['value'])) ? explode(',', $row['value']) : [];

                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', [
                            'key' => $key,
                            'selected' => (in_array((string) $key, $valueselect, true)) ? ' selected="selected"' : '',
                            'value' => $value
                        ]);
                        $xtpl->parse('main.tab_edit_others.loop.multiselect.loop');
                    }
                    $xtpl->parse('main.tab_edit_others.loop.multiselect');
                }
                $xtpl->parse('main.tab_edit_others.loop');
            }
        }
        if (defined('CKEDITOR')) {
            $xtpl->parse('main.tab_edit_others.ckeditor');
        }
        $xtpl->parse('main.edit_others');
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

    // Xuất menu cuối form
    $_lis = $module_info['funcs'];
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        if ($_li['show_func']) {
            if ($_li['func_name'] == $op or $_li['func_name'] == 'avatar' or $_li['func_name'] == 'groups') {
                continue;
            }
            if ($_li['func_name'] == 'register' and !$global_config['allowuserreg']) {
                continue;
            }

            $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
            $li = [
                'href' => $href,
                'title' => $_li['func_name'] == 'main' ? $lang_module['user_info'] : $_li['func_custom_name']
            ];
            $xtpl->assign('NAVBAR', $li);
            $xtpl->parse('main.navbar');
        }
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * openid_callback()
 *
 * @param array $openid_info
 * @return string
 */
function openid_callback($openid_info)
{
    global $module_info;

    $xtpl = new XTemplate('openid_callback.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('OPIDRESULT', $openid_info);

    if (!empty($openid_info['client'])) {
        $xtpl->parse('main.client');
    } else {
        if ($openid_info['status'] == 'success') {
            $xtpl->parse('main.regular.success');
        }

        $xtpl->parse('main.regular');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * user_welcome()
 *
 * @param array $array_field_config
 * @param array $custom_fields
 * @return string
 */
function user_welcome($array_field_config, $custom_fields)
{
    global $module_info, $global_config, $lang_global, $lang_module, $module_name, $user_info, $op;

    $xtpl = new XTemplate('userinfo.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=');
    $xtpl->assign('URL_MODULE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
    $xtpl->assign('URL_AVATAR', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=avatar/upd', true));
    $xtpl->assign('URL_GROUPS', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=groups', true));
    $xtpl->assign('URL_2STEP', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=two-step-verification', true));

    if (defined('SSO_REGISTER_DOMAIN')) {
        $xtpl->assign('SSO_REGISTER_ORIGIN', SSO_REGISTER_DOMAIN);
        $xtpl->parse('main.crossdomain_listener');
    }

    if (!empty($user_info['avata'])) {
        $xtpl->assign('IMG', [
            'src' => $user_info['avata'],
            'title' => $lang_module['img_size_title']
        ]);
    } else {
        $xtpl->assign('IMG', [
            'src' => NV_STATIC_URL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/no_avatar.png',
            'title' => $lang_module['change_avatar']
        ]);
    }

    $_user_info = $user_info;

    $_user_info['gender'] = ($user_info['gender'] == 'M') ? $lang_module['male'] : ($user_info['gender'] == 'F' ? $lang_module['female'] : $lang_module['na']);
    $_user_info['birthday'] = empty($user_info['birthday']) ? $lang_module['na'] : nv_date('d/m/Y', $user_info['birthday']);
    $_user_info['regdate'] = nv_date('d/m/Y', $user_info['regdate']);
    $_user_info['view_mail'] = empty($user_info['view_mail']) ? $lang_module['no'] : $lang_module['yes'];
    $_user_info['last_login'] = empty($user_info['last_login']) ? '' : nv_date('l, d/m/Y H:i', $user_info['last_login']);
    $_user_info['current_login'] = nv_date('l, d/m/Y H:i', $user_info['current_login']);
    $_user_info['st_login'] = !empty($user_info['st_login']) ? $lang_module['yes'] : $lang_module['no'];
    $_user_info['active2step'] = !empty($user_info['active2step']) ? $lang_global['on'] : $lang_global['off'];

    if (isset($user_info['current_mode']) and $user_info['current_mode'] == 5) {
        $_user_info['current_mode'] = $lang_module['admin_login'];
    } elseif (isset($user_info['current_mode']) and isset($lang_module['mode_login_' . $user_info['current_mode']])) {
        $_user_info['current_mode'] = $lang_module['mode_login_' . $user_info['current_mode']] . ': ' . $user_info['openid_server'] . ' (' . (!empty($user_info['openid_email']) ? $user_info['openid_email'] : $user_info['openid_id']) . ')';
    } else {
        $_user_info['current_mode'] = $lang_module['mode_login_1'];
    }

    $_user_info['change_name_info'] = sprintf($lang_module['change_name_info'], NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/username');
    $_user_info['pass_empty_note'] = sprintf($lang_module['pass_empty_note'], NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/password');
    $_user_info['question_empty_note'] = sprintf($lang_module['question_empty_note'], NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/question');

    $xtpl->assign('USER', $_user_info);

    if (!$global_config['allowloginchange'] and !empty($user_info['current_openid']) and empty($user_info['last_login']) and empty($user_info['last_agent']) and empty($user_info['last_ip']) and empty($user_info['last_openid'])) {
        $xtpl->parse('main.change_login_note');
    }

    if (empty($user_info['st_login'])) {
        $xtpl->parse('main.pass_empty_note');
    }

    if (empty($user_info['valid_question'])) {
        $xtpl->parse('main.question_empty_note');
    }

    if ($user_info['group_manage'] > 0) {
        $xtpl->parse('main.group_manage');
    }

    // Parse custom fields
    if (!empty($array_field_config)) {
        foreach ($array_field_config as $row) {
            if ($row['system'] == 1) {
                continue;
            }
            if ($row['show_profile']) {
                $question_type = $row['field_type'];
                if ($question_type == 'date') {
                    $value = !empty($custom_fields[$row['field']]) ? date('d/m/Y', $custom_fields[$row['field']]) : '';
                } elseif ($question_type == 'checkbox') {
                    $result = explode(',', $custom_fields[$row['field']]);
                    $value = [];
                    foreach ($result as $item) {
                        if (isset($row['field_choices'][$item])) {
                            $value[] = $row['field_choices'][$item];
                        } elseif (!empty($item)) {
                            $value[] = $item;
                        }
                    }
                    $value = empty($value) ? '' : implode('<br />', $value);
                } elseif ($question_type == 'multiselect' or $question_type == 'select' or $question_type == 'radio') {
                    if (isset($row['field_choices'][$custom_fields[$row['field']]])) {
                        $value = $row['field_choices'][$custom_fields[$row['field']]];
                    } else {
                        $value = $custom_fields[$row['field']];
                    }
                } else {
                    $value = $custom_fields[$row['field']];
                }
                $xtpl->assign('FIELD', [
                    'title' => $row['title'],
                    'value' => $value
                ]);
                $xtpl->parse('main.field.loop');
            }
        }
        $xtpl->parse('main.field');
    }

    $_lis = $module_info['funcs'];
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        if ($_li['show_func']) {
            if ($_li['func_name'] == $op or $_li['func_name'] == 'avatar' or $_li['func_name'] == 'groups') {
                continue;
            }
            if ($_li['func_name'] == 'register' and !$global_config['allowuserreg']) {
                continue;
            }

            $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
            $li = [
                'href' => $href,
                'title' => $_li['func_name'] == 'main' ? $lang_module['user_info'] : $_li['func_custom_name']
            ];
            $xtpl->assign('NAVBAR', $li);
            $xtpl->parse('main.navbar');
        }
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
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

    $xtpl = new XTemplate('info_exit.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('INFO', $info);

    if ($error) {
        $xtpl->parse('main.danger');
    } else {
        $xtpl->parse('main.info');
    }
    $xtpl->parse('main');

    return $xtpl->text('main');
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
    global $lang_global, $lang_module, $module_info, $module_name, $module_captcha, $nv_redirect, $global_config;

    $xtpl = new XTemplate('confirm.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);

    $lang_module['openid_confirm_info'] = sprintf($lang_module['openid_confirm_info'], $attribs['contact/email'], $user['username']);

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('OPENID_LOGIN', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;server=' . $attribs['server'] . '&amp;result=1');

    if ($gfx_chk) {
        // Nếu dùng reCaptcha v3
        if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
            $xtpl->parse('main.recaptcha3');
        }
        // Nếu dùng reCaptcha v2
        elseif ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
            $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
            $xtpl->parse('main.recaptcha');
        } elseif ($module_captcha == 'captcha') {
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
            $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
            $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
            $xtpl->assign('GFX_MAXLENGTH', NV_GFX_NUM);
            $xtpl->assign('SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha&t=' . NV_CURRENTTIME);
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
 * nv_memberslist_theme()
 *
 * @param array  $users_array
 * @param array  $array_order_new
 * @param string $generate_page
 * @return string
 */
function nv_memberslist_theme($users_array, $array_order_new, $generate_page)
{
    global $module_info, $module_name, $global_config, $lang_module, $op;

    $xtpl = new XTemplate('memberslist.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);

    foreach ($array_order_new as $key => $link) {
        $xtpl->assign($key, $link);
    }

    foreach ($users_array as $user) {
        $xtpl->assign('USER', $user);

        if (!empty($user['first_name']) and $user['first_name'] != $user['username']) {
            $xtpl->parse('main.list.fullname');
        }
        $xtpl->parse('main.list');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $_lis = $module_info['funcs'];
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        if ($_li['show_func']) {
            if ($_li['func_name'] == $op or $_li['func_name'] == 'avatar' or $_li['func_name'] == 'groups') {
                continue;
            }
            if ($_li['func_name'] == 'register' and !$global_config['allowuserreg']) {
                continue;
            }

            $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
            $li = [
                'href' => $href,
                'title' => $_li['func_name'] == 'main' ? $lang_module['user_info'] : $_li['func_custom_name']
            ];
            $xtpl->assign('NAVBAR', $li);
            $xtpl->parse('main.navbar');
        }
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_memberslist_detail_theme()
 *
 * @param array $item
 * @param array $array_field_config
 * @param array $custom_fields
 * @return string
 */
function nv_memberslist_detail_theme($item, $array_field_config, $custom_fields)
{
    global $module_info, $lang_module, $lang_global, $module_name, $global_config, $op;

    $xtpl = new XTemplate('viewdetailusers.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=');
    $xtpl->assign('URL_MODULE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);

    $item['full_name'] = nv_show_name_user($item['first_name'], $item['last_name']);
    if (!empty($item['photo']) and file_exists(NV_ROOTDIR . '/' . $item['photo'])) {
        $xtpl->assign('SRC_IMG', NV_BASE_SITEURL . $item['photo']);
    } else {
        $xtpl->assign('SRC_IMG', NV_STATIC_URL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/no_avatar.png');
    }

    $item['gender'] = ($item['gender'] == 'M') ? $lang_module['male'] : ($item['gender'] == 'F' ? $lang_module['female'] : $lang_module['na']);
    $item['birthday'] = empty($item['birthday']) ? $lang_module['na'] : nv_date('d/m/Y', $item['birthday']);
    $item['regdate'] = nv_date('d/m/Y', $item['regdate']);
    $item['last_login'] = empty($item['last_login']) ? '' : nv_date('l, d/m/Y H:i', $item['last_login']);

    $xtpl->assign('USER', $item);

    if ($item['is_admin']) {
        if ($item['allow_edit']) {
            $xtpl->assign('LINK_EDIT', $item['link_edit']);
            $xtpl->parse('main.for_admin.edit');
        }
        if ($item['allow_delete']) {
            $xtpl->parse('main.for_admin.delete');
        }
        $xtpl->parse('main.for_admin');
    }

    if (!empty($item['view_mail'])) {
        $xtpl->parse('main.viewemail');
    }

    // Parse custom fields
    if (!empty($array_field_config)) {
        foreach ($array_field_config as $row) {
            if ($row['system'] == 1) {
                continue;
            }
            if ($row['show_profile']) {
                $question_type = $row['field_type'];
                if ($question_type == 'date') {
                    $value = !empty($custom_fields[$row['field']]) ? date('d/m/Y', $custom_fields[$row['field']]) : '';
                } elseif ($question_type == 'checkbox') {
                    $result = explode(',', $custom_fields[$row['field']]);
                    $value = [];
                    foreach ($result as $item) {
                        if (isset($row['field_choices'][$item])) {
                            $value[] = $row['field_choices'][$item];
                        } elseif (!empty($item)) {
                            $value[] = $item;
                        }
                    }
                    $value = empty($value) ? '' : implode('<br />', $value);
                } elseif ($question_type == 'multiselect' or $question_type == 'select' or $question_type == 'radio') {
                    if (isset($row['field_choices'][$custom_fields[$row['field']]])) {
                        $value = $row['field_choices'][$custom_fields[$row['field']]];
                    } else {
                        $value = $custom_fields[$row['field']];
                    }
                } else {
                    $value = $custom_fields[$row['field']];
                }
                $xtpl->assign('FIELD', [
                    'title' => $row['title'],
                    'value' => $value
                ]);
                $xtpl->parse('main.field.loop');
            }
        }
        $xtpl->parse('main.field');
    }

    $_lis = $module_info['funcs'];
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        if ($_li['show_func']) {
            if ($_li['func_name'] == $op or $_li['func_name'] == 'avatar' or $_li['func_name'] == 'groups') {
                continue;
            }
            if ($_li['func_name'] == 'register' and !$global_config['allowuserreg']) {
                continue;
            }

            $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
            $li = [
                'href' => $href,
                'title' => $_li['func_name'] == 'main' ? $lang_module['user_info'] : $_li['func_custom_name']
            ];
            $xtpl->assign('NAVBAR', $li);
            $xtpl->parse('main.navbar');
        }
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * user_info_exit_redirect()
 *
 * @param mixed  $info
 * @param string $nv_redirect
 */
function user_info_exit_redirect($info, $nv_redirect)
{
    global $module_info, $lang_module;

    $xtpl = new XTemplate('info_exit_redirect.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('INFO', $info);
    $xtpl->assign('NV_REDIRECT', $nv_redirect);

    $xtpl->parse('main');

    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

/**
 * nv_avatar()
 *
 * @param array $array
 * @return string
 */
function nv_avatar($array)
{
    global $module_info, $module_name, $lang_module, $lang_global, $global_config;

    $xtpl = new XTemplate('avatar.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('TEMPLATE', $global_config['module_theme']);
    $xtpl->assign('MODULE_FILE', $module_info['module_file']);

    $xtpl->assign('NV_AVATAR_WIDTH', $global_config['avatar_width']);
    $xtpl->assign('NV_AVATAR_HEIGHT', $global_config['avatar_height']);
    $xtpl->assign('NV_MAX_WIDTH', NV_MAX_WIDTH);
    $xtpl->assign('NV_MAX_HEIGHT', NV_MAX_HEIGHT);
    $xtpl->assign('NV_UPLOAD_MAX_FILESIZE', NV_UPLOAD_MAX_FILESIZE);
    $xtpl->assign('DATA', $array);

    $form_action = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=avatar';
    if (!empty($array['u'])) {
        $form_action .= '/' . $array['u'];
    }
    $xtpl->assign('NV_AVATAR_UPLOAD', $form_action);

    $lang_module['avatar_bigfile'] = sprintf($lang_module['avatar_bigfile'], nv_convertfromBytes(NV_UPLOAD_MAX_FILESIZE));
    $lang_module['avatar_bigsize'] = sprintf($lang_module['avatar_bigsize'], NV_MAX_WIDTH, NV_MAX_HEIGHT);
    $lang_module['avatar_smallsize'] = sprintf($lang_module['avatar_smallsize'], $global_config['avatar_width'], $global_config['avatar_height']);

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    if ($array['error']) {
        $xtpl->assign('ERROR', $array['error']);
        $xtpl->parse('main.error');
    }
    if ($array['success'] == 1) {
        $xtpl->assign('FILENAME', $array['filename']);
        $xtpl->parse('main.complete');
    } elseif ($array['success'] == 2) {
        $xtpl->parse('main.complete2');
    } elseif ($array['success'] == 3) {
        $xtpl->assign('FILENAME', $array['filename']);
        $xtpl->parse('main.complete3');
    } else {
        $xtpl->parse('main.init');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * safe_deactivate()
 *
 * @param array $data
 * @return string
 */
function safe_deactivate($data)
{
    global $module_info, $module_name, $lang_module, $lang_global, $global_config, $op;

    $xtpl = new XTemplate('safe.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('EDITINFO_FORM', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo');
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('PASS_MAXLENGTH', $global_config['nv_upassmax']);
    $xtpl->assign('PASS_MINLENGTH', $global_config['nv_upassmin']);
    $xtpl->assign('DATA', $data);

    if ($data['safeshow']) {
        $xtpl->assign('SHOW1', ' style="display:none"');
    } else {
        $xtpl->assign('SHOW2', ' style="display:none"');
    }

    $_lis = $module_info['funcs'];
    $_alias = $module_info['alias'];
    foreach ($_lis as $_li) {
        if ($_li['show_func']) {
            if ($_li['func_name'] == $op or $_li['func_name'] == 'avatar' or $_li['func_name'] == 'groups') {
                continue;
            }
            if ($_li['func_name'] == 'register' and !$global_config['allowuserreg']) {
                continue;
            }

            $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
            $li = [
                'href' => $href,
                'title' => $_li['func_name'] == 'main' ? $lang_module['user_info'] : $_li['func_custom_name']
            ];
            $xtpl->assign('NAVBAR', $li);
            $xtpl->parse('main.navbar');
        }
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}
