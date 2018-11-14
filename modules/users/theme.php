<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (!defined('NV_IS_MOD_USER')) {
    die('Stop!!!');
}

/**
 * user_register()
 *
 * @param mixed $gfx_chk
 * @param mixed $checkss
 * @param mixed $data_questions
 * @param mixed $array_field_config
 * @param mixed $custom_fields
 * @return
 */
function user_register($gfx_chk, $checkss, $data_questions, $array_field_config, $custom_fields, $group_id)
{
    global $module_info, $global_config, $lang_global, $lang_module, $module_name, $op, $nv_redirect, $global_array_genders;

    $xtpl = new XTemplate('register.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('NICK_MAXLENGTH', $global_config['nv_unickmax']);
    $xtpl->assign('NICK_MINLENGTH', $global_config['nv_unickmin']);
    $xtpl->assign('PASS_MAXLENGTH', $global_config['nv_upassmax']);
    $xtpl->assign('PASS_MINLENGTH', $global_config['nv_upassmin']);
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
                    $tempkey = intval($row['default_value']) - 1;
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
                        $xtpl->assign('FIELD_CHOICES', array(
                            'key' => $key,
                            'selected' => ($key == $row['value']) ? ' selected="selected"' : '',
                            'value' => $value
                        ));
                        $xtpl->parse('main.field.loop.select.loop');
                    }
                    $xtpl->parse('main.field.loop.select');
                } elseif ($row['field_type'] == 'radio') {
                    $number = 0;
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', array(
                            'id' => $row['fid'] . '_' . $number++,
                            'key' => $key,
                            'checked' => ($key == $row['value']) ? ' checked="checked"' : '',
                            'value' => $value
                        ));
                        $xtpl->parse('main.field.loop.radio.loop');
                    }
                    $xtpl->parse('main.field.loop.radio');
                } elseif ($row['field_type'] == 'checkbox') {
                    $number = 0;
                    $valuecheckbox = (!empty($row['value'])) ? explode(',', $row['value']) : array();
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', array(
                            'id' => $row['fid'] . '_' . $number++,
                            'key' => $key,
                            'checked' => (in_array($key, $valuecheckbox)) ? ' checked="checked"' : '',
                            'value' => $value
                        ));
                        $xtpl->parse('main.field.loop.checkbox.loop');
                    }
                    $xtpl->parse('main.field.loop.checkbox');
                } elseif ($row['field_type'] == 'multiselect') {
                    $valueselect = (!empty($row['value'])) ? explode(',', $row['value']) : array();
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', array(
                            'key' => $key,
                            'selected' => (in_array($key, $valueselect)) ? ' selected="selected"' : '',
                            'value' => $value
                        ));
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
        if ($global_config['captcha_type'] == 2) {
            $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
            $xtpl->parse('main.reg_recaptcha');
        } else {
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
            $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
            $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
            $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
            $xtpl->assign('CAPTCHA_REFR_SRC', NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/refresh.png');
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
            $li = array(
                'href' => $href,
                'title' => $_li['func_name'] == 'main' ? $module_info['custom_title'] : $_li['func_custom_name']
            );
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
 * @return
 */
function user_login($is_ajax = false)
{
    global $module_info, $global_config, $lang_global, $lang_module, $module_name, $op, $nv_header, $nv_redirect;

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

    if (in_array($global_config['gfx_chk'], array(
        2,
        4,
        5,
        7
    ))) {
        if ($global_config['captcha_type'] == 2) {
            $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
            $xtpl->parse('main.recaptcha.default');
            $xtpl->parse('main.recaptcha');
        } else {
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
        $xtpl->assign('SITE_NAME', $global_config['site_name']);
        $xtpl->assign('THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
        $size = @getimagesize(NV_ROOTDIR . '/' . $global_config['site_logo']);
        $logo = preg_replace('/\.[a-z]+$/i', '.svg', $global_config['site_logo']);
        if (!file_exists(NV_ROOTDIR . '/' . $logo)) {
            $logo = $global_config['site_logo'];
        }
        $xtpl->assign('LOGO_SRC', NV_BASE_SITEURL . $logo);
        $xtpl->assign('LOGO_WIDTH', $size[0]);
        $xtpl->assign('LOGO_HEIGHT', $size[1]);

        if (isset($size['mime']) and $size['mime'] == 'application/x-shockwave-flash') {
            $xtpl->parse('main.redirect2.swf');
        } else {
            $xtpl->parse('main.redirect2.image');
        }

        $xtpl->assign('REDIRECT', $nv_redirect);
        $xtpl->parse('main.redirect');
        $xtpl->parse('main.redirect2');
    } else {
        $xtpl->parse('main.not_redirect');
    }

    if (!empty($nv_header)) {
        $xtpl->assign('NV_HEADER', $nv_header);
        $xtpl->parse('main.header');
    }

    if (defined('NV_OPENID_ALLOWED')) {
        $assigns = array();
        $icons = array(
            'single-sign-on' => 'lock',
            'google' => 'google-plus',
            'facebook' => 'facebook'
        );
        foreach ($global_config['openid_servers'] as $server) {
            $assigns['href'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=oauth&amp;server=' . $server;
            if (!empty($nv_redirect)) {
                $assigns['href'] .= '&nv_redirect=' . $nv_redirect;
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
            $li = array(
                'href' => $href,
                'title' => $_li['func_name'] == 'main' ? $module_info['custom_title'] : $_li['func_custom_name']
            );
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
 * @return
 */
function user_openid_login($gfx_chk, $attribs)
{
    global $module_info, $global_config, $lang_global, $lang_module, $module_name, $nv_redirect;

    $xtpl = new XTemplate('openid_login.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/users');

    $xtpl->assign('USER_LOGIN', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;server=' . $attribs['server'] . '&amp;result=1');
    $xtpl->assign('NICK_MAXLENGTH', $global_config['nv_unickmax']);
    $xtpl->assign('PASS_MAXLENGTH', $global_config['nv_upassmax']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    if ($gfx_chk) {
        if ($global_config['captcha_type'] == 2) {
            $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
            $xtpl->parse('main.recaptcha');
        } else {
            $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
            $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
            $xtpl->assign('SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha&t=' . NV_CURRENTTIME);
            $xtpl->assign('GFX_MAXLENGTH', NV_GFX_NUM);
            $xtpl->parse('main.captcha');
        }
    }

    $info = $lang_module['openid_note1'];

    $xtpl->assign('REDIRECT', $nv_redirect);

    if ($global_config['allowuserreg'] != 0) {
        $info = $lang_module['openid_note2'];
        if (!empty($nv_redirect)) {
            $xtpl->parse('main.allowuserreg.redirect2');
        }
        $xtpl->parse('main.allowuserreg');
        $xtpl->parse('main.allowuserreg2');
    }

    $xtpl->assign('INFO', $info);

    if (!empty($nv_redirect)) {
        $xtpl->parse('main.redirect');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * user_lostpass()
 *
 * @param mixed $data
 * @return
 */
function user_lostpass($data)
{
    global $module_info, $global_config, $lang_global, $lang_module, $module_name, $op, $nv_redirect;

    $xtpl = new XTemplate('lostpass.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('DATA', $data);
    $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostpass');

    if ($global_config['captcha_type'] == 2) {
        $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
        $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
        $xtpl->parse('main.recaptcha');
    } else {
        $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
        $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
        $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
        $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
        $xtpl->assign('SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha&t=' . NV_CURRENTTIME);
        $xtpl->assign('GFX_MAXLENGTH', NV_GFX_NUM);
        $xtpl->parse('main.captcha');
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
            $li = array(
                'href' => $href,
                'title' => $_li['func_name'] == 'main' ? $module_info['custom_title'] : $_li['func_custom_name']
            );
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
 * @param mixed $data
 * @param mixed $question
 * @return
 */
function user_lostactivelink($data, $question)
{
    global $module_info, $global_config, $lang_global, $lang_module, $module_name, $op;

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

        if ($global_config['captcha_type'] == 2) {
            $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
            $xtpl->parse('main.step1.recaptcha');
        } else {
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
            $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
            $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
            $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
            $xtpl->assign('CAPTCHA_REFR_SRC', NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/refresh.png');
            $xtpl->assign('SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha&t=' . NV_CURRENTTIME);
            $xtpl->assign('GFX_MAXLENGTH', NV_GFX_NUM);
            $xtpl->parse('main.step1.captcha');
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
            $li = array(
                'href' => $href,
                'title' => $_li['func_name'] == 'main' ? $module_info['custom_title'] : $_li['func_custom_name']
            );
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
 * @param mixed $data
 * @param mixed $array_field_config
 * @param mixed $custom_fields
 * @param mixed $types
 * @param mixed $data_questions
 * @param mixed $data_openid
 * @param mixed $groups
 * @param mixed $pass_empty
 * @return
 */
function user_info($data, $array_field_config, $custom_fields, $types, $data_questions, $data_openid, $groups, $pass_empty)
{
    global $module_info, $global_config, $lang_global, $lang_module, $module_name, $op, $global_array_genders;

    $xtpl = new XTemplate('info.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);

    if (defined('ACCESS_EDITUS')) {
        $xtpl->assign('EDITINFO_FORM', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/' . $data['group_id'] . '/' . $data['userid']);
    } else {
        $xtpl->assign('EDITINFO_FORM', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo');
    }

    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
    $xtpl->assign('AVATAR_DEFAULT', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/no_avatar.png');
    $xtpl->assign('URL_AVATAR', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=avatar/src', true));

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('NICK_MAXLENGTH', $global_config['nv_unickmax']);
    $xtpl->assign('NICK_MINLENGTH', $global_config['nv_unickmin']);
    $xtpl->assign('PASS_MAXLENGTH', $global_config['nv_upassmax']);
    $xtpl->assign('PASS_MINLENGTH', $global_config['nv_upassmin']);

    $xtpl->assign('URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=');
    $xtpl->assign('URL_MODULE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);

    $xtpl->assign('DATA', $data);
    if ($pass_empty) {
        $xtpl->assign('FORM_HIDDEN', ' hidden');
    }

    // Thông tin cơ bản
    $array_basic_key = array(
        'first_name',
        'last_name',
        'gender',
        'birthday',
        'sig'
    );
    foreach ($array_basic_key as $key) {
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
        if ($row['field'] == 'gender') {
            foreach ($global_array_genders as $gender) {
                $gender['checked'] = $row['value'] == $gender['key'] ? ' checked="checked"' : '';
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

    $xtpl->assign(strtoupper($data['type']) . '_ACTIVE', 'active');
    $xtpl->assign(strtoupper('TAB_' . $data['type']) . '_ACTIVE', 'in active');

    if (in_array('username', $types)) {
        if ($pass_empty) {
            $xtpl->parse('main.tab_edit_username.username_empty_pass');
        }
        $xtpl->parse('main.edit_username');
        $xtpl->parse('main.tab_edit_username');
    }

    if (in_array('password', $types)) {
        if (!$pass_empty and !defined('ACCESS_PASSUS')) {
            $xtpl->parse('main.tab_edit_password.is_old_pass');
        }
        $xtpl->parse('main.edit_password');
        $xtpl->parse('main.tab_edit_password');
    }

    if (in_array('2step', $types)) {
        $xtpl->assign('URL_2STEP', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=two-step-verification', true));
        $xtpl->parse('main.2step');
    }

    if (in_array('email', $types)) {
        if ($pass_empty) {
            $xtpl->parse('main.tab_edit_email.email_empty_pass');
        }
        $xtpl->parse('main.edit_email');
        $xtpl->parse('main.tab_edit_email');
    }

    if (in_array('openid', $types)) {
        if (!empty($data_openid)) {
            $openid_del_al = 0;
            foreach ($data_openid as $openid) {
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
            $assigns = array();
            $assigns['href'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=oauth&amp;server=' . $server;
            $assigns['title'] = ucfirst($server);
            $assigns['img_src'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/' . $server . '.png';
            $assigns['img_width'] = $assigns['img_height'] = 24;

            $xtpl->assign('OPENID', $assigns);
            $xtpl->parse('main.tab_edit_openid.server');
        }

        $xtpl->parse('main.edit_openid');
        $xtpl->parse('main.tab_edit_openid');
    }

    if (in_array('group', $types)) {
        $group_check_all_checked = 1;
        $count = 0;
        foreach ($groups as $group) {
            $group['status'] = $lang_module['group_status_' . $group['status']];
            $group['group_type'] = $lang_module['group_type_' . $group['group_type']];
            $xtpl->assign('GROUP_LIST', $group);
            if ($group['is_leader']) {
                $xtpl->assign('URL_IS_LEADER', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=groups/' . $group['group_id'], true));
                $xtpl->parse('main.tab_edit_group.group_list.is_leader');
                $xtpl->parse('main.tab_edit_group.group_list.is_disable_checkbox');
            }
            $xtpl->parse('main.tab_edit_group.group_list');
            if (empty($group['checked'])) {
                $group_check_all_checked = 0;
            }
            $count++;
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

    if (in_array('others', $types) and sizeof($array_field_config) > 7) {
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
                        $xtpl->assign('FIELD_CHOICES', array(
                            'key' => $key,
                            'selected' => ($key == $row['value']) ? ' selected="selected"' : '',
                            'value' => $value
                        ));
                        $xtpl->parse('main.tab_edit_others.loop.select.loop');
                    }
                    $xtpl->parse('main.tab_edit_others.loop.select');
                } elseif ($row['field_type'] == 'radio') {
                    $number = 0;
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', array(
                            'id' => $row['fid'] . '_' . $number++,
                            'key' => $key,
                            'checked' => ($key == $row['value']) ? ' checked="checked"' : '',
                            'value' => $value
                        ));
                        $xtpl->parse('main.tab_edit_others.loop.radio.loop');
                    }
                    $xtpl->parse('main.tab_edit_others.loop.radio');
                } elseif ($row['field_type'] == 'checkbox') {
                    $number = 0;
                    $valuecheckbox = (!empty($row['value'])) ? explode(',', $row['value']) : array();

                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', array(
                            'id' => $row['fid'] . '_' . $number++,
                            'key' => $key,
                            'checked' => (in_array($key, $valuecheckbox)) ? ' checked="checked"' : '',
                            'value' => $value
                        ));
                        $xtpl->parse('main.tab_edit_others.loop.checkbox.loop');
                    }
                    $xtpl->parse('main.tab_edit_others.loop.checkbox');
                } elseif ($row['field_type'] == 'multiselect') {
                    $valueselect = (!empty($row['value'])) ? explode(',', $row['value']) : array();

                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', array(
                            'key' => $key,
                            'selected' => (in_array($key, $valueselect)) ? ' selected="selected"' : '',
                            'value' => $value
                        ));
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

    if (in_array('avatar', $types)) {
        $xtpl->parse('main.edit_avatar');
        $xtpl->parse('main.tab_edit_avatar');
    }

    if (in_array('question', $types)) {
        if ($pass_empty) {
            $xtpl->parse('main.question_empty_pass');
        }

        $array_question_key = array(
            'question',
            'answer'
        );
        foreach ($array_question_key as $key) {
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

        $xtpl->parse('main.edit_question');
        $xtpl->parse('main.tab_edit_question');
    }

    if (in_array('safemode', $types)) {
        if ($pass_empty) {
            $xtpl->parse('main.safemode_empty_pass');
        }
        $xtpl->parse('main.edit_safemode');
        $xtpl->parse('main.tab_edit_safemode');
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
            $li = array(
                'href' => $href,
                'title' => $_li['func_name'] == 'main' ? $lang_module['user_info'] : $_li['func_custom_name']
            );
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
 * @param mixed $openid_info
 * @return
 */
function openid_callback($openid_info)
{
    global $module_info;

    $xtpl = new XTemplate('openid_callback.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('OPIDRESULT', $openid_info);
    if ($openid_info['status'] == 'success') {
        $xtpl->parse('main.success');
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * user_welcome()
 *
 * @return
 */
function user_welcome()
{
    global $module_info, $global_config, $lang_global, $lang_module, $module_name, $user_info, $op;

    $xtpl = new XTemplate('userinfo.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=');
    $xtpl->assign('URL_MODULE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
    $xtpl->assign('URL_AVATAR', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=avatar/upd', true));
    $xtpl->assign('URL_GROUPS', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=groups', true));
    $xtpl->assign('URL_2STEP', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=two-step-verification', true));

    if (!empty($user_info['photo']) and file_exists(NV_ROOTDIR . '/' . $user_info['photo'])) {
        $xtpl->assign('IMG', array(
            'src' => NV_BASE_SITEURL . $user_info['photo'],
            'title' => $lang_module['img_size_title']
        ));
    } else {
        $xtpl->assign('IMG', array(
            'src' => NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/no_avatar.png',
            'title' => $lang_module['change_avatar']
        ));
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
        $_user_info['current_mode'] = $lang_module['mode_login_' . $user_info['current_mode']] . ': ' . $user_info['openid_server'] . ' (' . $user_info['openid_email'] . ')';
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
            $li = array(
                'href' => $href,
                'title' => $_li['func_name'] == 'main' ? $lang_module['user_info'] : $_li['func_custom_name']
            );
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
 * @param bool $error
 * @return
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
 * @param mixed $gfx_chk
 * @param mixed $attribs
 * @param mixed $user
 * @return
 */
function openid_account_confirm($gfx_chk, $attribs, $user)
{
    global $lang_global, $lang_module, $module_info, $module_name, $nv_redirect, $global_config;

    $xtpl = new XTemplate('confirm.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);

    $lang_module['openid_confirm_info'] = sprintf($lang_module['openid_confirm_info'], $attribs['contact/email'], $user['username']);

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('OPENID_LOGIN', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;server=' . $attribs['server'] . '&amp;result=1');

    if ($gfx_chk) {
        if ($global_config['captcha_type'] == 2) {
            $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
            $xtpl->parse('main.recaptcha');
        } else {
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
 * user_openid_administrator()
 *
 * @param mixed $data
 * @return
 */
function user_openid_administrator($data)
{
    global $lang_module, $module_info, $module_name, $global_config;

    $groups_list = nv_groups_list_pub($module_info['module_data']);

    $xtpl = new XTemplate('openid_administrator.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('OPENID_IMG_SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/openid.png');
    $xtpl->assign('OPENID_IMG_WIDTH', 150);
    $xtpl->assign('OPENID_IMG_HEIGHT', 60);

    $xtpl->assign('URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=');
    $xtpl->assign('URL_MODULE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);

    if (defined('NV_IS_USER_FORUM')) {
        $xtpl->parse('main.allowopenid');
    }

    if (!empty($groups_list) and $global_config['allowuserpublic'] == 1) {
        $xtpl->parse('main.regroups');
    }

    $xtpl->assign('DATA', $data);

    if (!empty($data['openid_list'])) {
        $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=openid&amp;del=1');

        foreach ($data['openid_list'] as $key => $openid_list) {
            if ($key % 2 == 0) {
                $xtpl->assign('OPENID_CLASS', ' gray');
            } else {
                $xtpl->assign('OPENID_CLASS', '');
            }
            $xtpl->assign('OPENID_LIST', $openid_list);
            $xtpl->parse('main.openid_empty.openid_list');
        }
        $xtpl->parse('main.openid_empty');
    }

    $assigns = array();
    foreach ($global_config['openid_servers'] as $server) {
        $assigns['href'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=oauth&amp;server=' . $server;
        $assigns['title'] = ucfirst($server);
        $assigns['img_src'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/' . $server . '.png';
        $assigns['img_width'] = $assigns['img_height'] = 24;

        $xtpl->assign('OPENID', $assigns);
        $xtpl->parse('main.server');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_memberslist_theme()
 *
 * @param mixed $users_array
 * @param mixed $array_order_new
 * @param mixed $generate_page
 * @return
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
            $li = array(
                'href' => $href,
                'title' => $_li['func_name'] == 'main' ? $lang_module['user_info'] : $_li['func_custom_name']
            );
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
 * @param mixed $item
 * @param mixed $array_field_config
 * @param mixed $custom_fields
 * @return
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
        $xtpl->assign('SRC_IMG', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/no_avatar.png');
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
        //var_dump($array_field_config); die();
        foreach ($array_field_config as $row) {
            if ($row['system'] == 1) continue;
            if ($row['show_profile']) {
                $question_type = $row['field_type'];
                if ($question_type == 'checkbox') {
                    $result = explode(',', $custom_fields[$row['field']]);
                    $value = '';
                    foreach ($result as $item) {
                        $value .= $row['field_choices'][$item] . '<br />';
                    }
                } elseif ($question_type == 'multiselect' or $question_type == 'select' or $question_type == 'radio') {
                    $value = $row['field_choices'][$custom_fields[$row['field']]];
                } else {
                    $value = $custom_fields[$row['field']];
                }
                $xtpl->assign('FIELD', array(
                    'title' => $row['title'],
                    'value' => $value
                ));
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
            $li = array(
                'href' => $href,
                'title' => $_li['func_name'] == 'main' ? $lang_module['user_info'] : $_li['func_custom_name']
            );
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
 * @param mixed $info
 * @param mixed $nv_redirect
 * @return void
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
 * @param mixed $array
 * @return void
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
 * @param mixed $data
 * @return
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
            $li = array(
                'href' => $href,
                'title' => $_li['func_name'] == 'main' ? $lang_module['user_info'] : $_li['func_custom_name']
            );
            $xtpl->assign('NAVBAR', $li);
            $xtpl->parse('main.navbar');
        }
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
