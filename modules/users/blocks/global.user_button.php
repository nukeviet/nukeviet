<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 18:6
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

global $site_mods, $db_config, $client_info, $global_config, $module_name, $user_info, $lang_global, $my_head, $admin_info, $blockID;

$content = '';

if ($global_config['allowuserlogin']) {
    if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/users/block.user_button.tpl')) {
        $block_theme = $global_config['module_theme'];
    } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/users/block.user_button.tpl')) {
        $block_theme = $global_config['site_theme'];
    } else {
        $block_theme = 'default';
    }

    $xtpl = new XTemplate('block.user_button.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/users');

    if (file_exists(NV_ROOTDIR . '/modules/users/language/' . NV_LANG_DATA . '.php')) {
        include NV_ROOTDIR . '/modules/users/language/' . NV_LANG_DATA . '.php';
    } else {
        include NV_ROOTDIR . '/modules/users/language/vi.php';
    }

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('BLOCKID', $blockID);
    $xtpl->assign('BLOCK_THEME', $block_theme);

    if (defined('NV_IS_USER')) {
        if (file_exists(NV_ROOTDIR . '/' . $user_info['photo']) and !empty($user_info['photo'])) {
            $avata = NV_BASE_SITEURL . $user_info['photo'];
        } else {
            $avata = NV_BASE_SITEURL . 'themes/' . $block_theme . '/images/users/no_avatar.png';
        }

        $user_info['current_login_txt'] = nv_date('d/m, H:i', $user_info['current_login']);

        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
        $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
        $xtpl->assign('URL_LOGOUT', defined('NV_IS_ADMIN') ? 'nv_admin_logout' : 'bt_logout');
        $xtpl->assign('MODULENAME', $module_info['custom_title']);
        $xtpl->assign('AVATA', $avata);
        $xtpl->assign('USER', $user_info);
        $xtpl->assign('WELCOME', defined('NV_IS_ADMIN') ? $lang_global['admin_account'] : $lang_global['your_account']);
        $xtpl->assign('LEVEL', defined('NV_IS_ADMIN') ? $admin_info['level'] : 'user');
        $xtpl->assign('URL_MODULE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users');
        $xtpl->assign('URL_AVATAR', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=avatar/upd', true));
        $xtpl->assign('URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=');

        if (defined('NV_OPENID_ALLOWED')) {
            $xtpl->parse('signed.allowopenid');
        }

        if (defined('NV_IS_ADMIN')) {
            $new_drag_block = (defined('NV_IS_DRAG_BLOCK')) ? 0 : 1;
            $lang_drag_block = ($new_drag_block) ? $lang_global['drag_block'] : $lang_global['no_drag_block'];

            $xtpl->assign('NV_ADMINDIR', NV_ADMINDIR);
            $xtpl->assign('URL_DBLOCK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;drag_block=' . $new_drag_block);
            $xtpl->assign('LANG_DBLOCK', $lang_drag_block);
            $xtpl->assign('URL_ADMINMODULE', NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
            $xtpl->assign('URL_AUTHOR', NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=authors&amp;id=' . $admin_info['admin_id']);

            if (defined('NV_IS_SPADMIN')) {
                $xtpl->parse('signed.admintoolbar.is_spadadmin');
            }
            if (defined('NV_IS_MODADMIN') and !empty($module_info['admin_file'])) {
                $xtpl->parse('signed.admintoolbar.is_modadmin');
            }
            $xtpl->parse('signed.admintoolbar');
        }

        $xtpl->parse('signed');
        $content = $xtpl->text('signed');
    } else {
        $xtpl->assign('USER_LOGIN', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=login');
        $xtpl->assign('USER_REGISTER', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=register');
        $xtpl->assign('USER_LOSTPASS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=lostpass');
        $xtpl->assign('NICK_MAXLENGTH', $global_config['nv_unickmax']);
        $xtpl->assign('NICK_MINLENGTH', $global_config['nv_unickmin']);
        $xtpl->assign('PASS_MAXLENGTH', $global_config['nv_upassmax']);
        $xtpl->assign('PASS_MINLENGTH', $global_config['nv_upassmin']);
        $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
        $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
        $xtpl->assign('GFX_MAXLENGTH', NV_GFX_NUM);
        $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
        $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
        $xtpl->assign('SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha&t=' . NV_CURRENTTIME);
        $xtpl->assign('NV_HEADER', '');
        $xtpl->assign('NV_REDIRECT', '');
        $xtpl->assign('CHECKSS', NV_CHECK_SESSION);

        $username_rule = empty($global_config['nv_unick_type']) ? sprintf($lang_global['username_rule_nolimit'], $global_config['nv_unickmin'], $global_config['nv_unickmax']) : sprintf($lang_global['username_rule_limit'], $lang_global['unick_type_' . $global_config['nv_unick_type']], $global_config['nv_unickmin'], $global_config['nv_unickmax']);
        $password_rule = empty($global_config['nv_upass_type']) ? sprintf($lang_global['password_rule_nolimit'], $global_config['nv_upassmin'], $global_config['nv_upassmax']) : sprintf($lang_global['password_rule_limit'], $lang_global['upass_type_' . $global_config['nv_upass_type']], $global_config['nv_upassmin'], $global_config['nv_upassmax']);

        $xtpl->assign('USERNAME_RULE', $username_rule);
        $xtpl->assign('PASSWORD_RULE', $password_rule);

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
                $xtpl->parse('main.captcha');
            }
        }

        if (in_array($global_config['gfx_chk'], array(
            3,
            4,
            6,
            7
        ))) {
            if ($global_config['captcha_type'] == 2) {
                $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
                $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
                $xtpl->parse('main.allowuserreg.reg_recaptcha');
            } else {
                $xtpl->parse('main.allowuserreg.reg_captcha');
            }
        }

        if (defined('NV_OPENID_ALLOWED')) {
            $icons = array(
                'single-sign-on' => 'lock',
                'google' => 'google-plus',
                'facebook' => 'facebook'
            );
            foreach ($global_config['openid_servers'] as $server) {
                $assigns = array();
                $assigns['href'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=oauth&amp;server=' . $server . '&amp;nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']);
                $assigns['title'] = ucfirst($server);
                $assigns['server'] = $server;
                $assigns['icon'] = $icons[$server];

                $xtpl->assign('OPENID', $assigns);
                $xtpl->parse('main.openid.server');
            }
            $xtpl->parse('main.openid');
        }

        if ($global_config['allowuserreg']) {
            $global_array_genders = array(
                'N' => array(
                    'key' => 'N',
                    'title' => $lang_module['na'],
                    'selected' => ''
                ),
                'M' => array(
                    'key' => 'M',
                    'title' => $lang_module['male'],
                    'selected' => ''
                ),
                'F' => array(
                    'key' => 'F',
                    'title' => $lang_module['female'],
                    'selected' => ''
                )
            );

            $_mod_data = defined('NV_CONFIG_DIR') ? NV_USERS_GLOBALTABLE : $db_config['prefix'] . "_" . $site_mods[$block_config['module']]['module_data'];

            $data_questions = array();
            $sql = "SELECT qid, title FROM " . $_mod_data . "_question WHERE lang='" . NV_LANG_DATA . "' ORDER BY weight ASC";
            $result = $db->query($sql);
            while ($row = $result->fetch()) {
                $data_questions[$row['qid']] = array(
                    'qid' => $row['qid'],
                    'title' => $row['title']
                );
            }

            foreach ($data_questions as $array_question_i) {
                $xtpl->assign('QUESTION', $array_question_i['title']);
                $xtpl->parse('main.allowuserreg.frquestion');
            }

            $array_field_config = array();
            $result_field = $db->query('SELECT * FROM ' . $_mod_data . '_field ORDER BY weight ASC');
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
                    while (list ($key, $val) = $result->fetch(3)) {
                        $row_field['field_choices'][$key] = $val;
                    }
                }
                $array_field_config[$row_field['field']] = $row_field;
            }

            $datepicker = false;
            $have_custom_fields = false;

            if (!empty($array_field_config)) {
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
                                $show_key = 'name_show_' . $global_config['name_show'] . '.show_' . $row['field'];
                            } else {
                                $show_key = 'show_' . $row['field'];
                            }
                            if ($row['required']) {
                                $xtpl->parse('main.allowuserreg.' . $show_key . '.required');
                            }
                            if ($row['field'] == 'gender') {
                                foreach ($global_array_genders as $gender) {
                                    $gender['checked'] = $row['value'] == $gender['key'] ? ' checked="checked"' : '';
                                    $xtpl->assign('GENDER', $gender);
                                    $xtpl->parse('main.allowuserreg.' . $show_key . '.gender');
                                }
                            } elseif ($row['field'] == 'question') {
                                foreach ($data_questions as $array_question_i) {
                                    $xtpl->assign('QUESTION', $array_question_i['title']);
                                    $xtpl->parse('main.allowuserreg.' . $show_key . '.frquestion');
                                }
                            }
                            if ($row['description']) {
                                $xtpl->parse('main.allowuserreg.' . $show_key . '.description');
                            }
                            $xtpl->parse('main.allowuserreg.' . $show_key);
                            if ($row['field'] == 'gender') {
                                $xtpl->parse('main.allowuserreg.name_show_' . $global_config['name_show']);
                            }
                        } else {
                            if ($row['required']) {
                                $xtpl->parse('main.allowuserreg.field.loop.required');
                            }
                            if ($row['field_type'] == 'textbox' or $row['field_type'] == 'number') {
                                $xtpl->parse('main.allowuserreg.field.loop.textbox');
                            } elseif ($row['field_type'] == 'date') {
                                $row['value'] = (empty($row['value'])) ? '' : date('d/m/Y', $row['value']);
                                $xtpl->assign('FIELD', $row);
                                $xtpl->parse('main.allowuserreg.field.loop.date');
                                $datepicker = true;
                            } elseif ($row['field_type'] == 'textarea') {
                                $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
                                $xtpl->assign('FIELD', $row);
                                $xtpl->parse('main.allowuserreg.field.loop.textarea');
                            } elseif ($row['field_type'] == 'editor') {
                                $row['value'] = htmlspecialchars(nv_editor_br2nl($row['value']));
                                if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
                                    $array_tmp = explode('@', $row['class']);
                                    $edits = nv_aleditor('custom_fields[' . $row['field'] . ']', $array_tmp[0], $array_tmp[1], $row['value']);
                                    $xtpl->assign('EDITOR', $edits);
                                    $xtpl->parse('main.allowuserreg.field.loop.editor');
                                } else {
                                    $row['class'] = '';
                                    $xtpl->assign('FIELD', $row);
                                    $xtpl->parse('main.allowuserreg.field.loop.textarea');
                                }
                            } elseif ($row['field_type'] == 'select') {
                                foreach ($row['field_choices'] as $key => $value) {
                                    $xtpl->assign('FIELD_CHOICES', array(
                                        'key' => $key,
                                        'selected' => ($key == $row['value']) ? ' selected="selected"' : '',
                                        'value' => $value
                                    ));
                                    $xtpl->parse('main.allowuserreg.field.loop.select.loop');
                                }
                                $xtpl->parse('main.allowuserreg.field.loop.select');
                            } elseif ($row['field_type'] == 'radio') {
                                $number = 0;
                                foreach ($row['field_choices'] as $key => $value) {
                                    $xtpl->assign('FIELD_CHOICES', array(
                                        'id' => $row['fid'] . '_' . $number++,
                                        'key' => $key,
                                        'checked' => ($key == $row['value']) ? ' checked="checked"' : '',
                                        'value' => $value
                                    ));
                                    $xtpl->parse('main.allowuserreg.field.loop.radio.loop');
                                }
                                $xtpl->parse('main.allowuserreg.field.loop.radio');
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
                                    $xtpl->parse('main.allowuserreg.field.loop.checkbox.loop');
                                }
                                $xtpl->parse('main.allowuserreg.field.loop.checkbox');
                            } elseif ($row['field_type'] == 'multiselect') {
                                $valueselect = (!empty($row['value'])) ? explode(',', $row['value']) : array();
                                foreach ($row['field_choices'] as $key => $value) {
                                    $xtpl->assign('FIELD_CHOICES', array(
                                        'key' => $key,
                                        'selected' => (in_array($key, $valueselect)) ? ' selected="selected"' : '',
                                        'value' => $value
                                    ));
                                    $xtpl->parse('main.allowuserreg.field.loop.multiselect.loop');
                                }
                                $xtpl->parse('main.allowuserreg.field.loop.multiselect');
                            }
                            $xtpl->parse('main.allowuserreg.field.loop');
                            $have_custom_fields = true;
                        }
                    }
                }
            }

            if ($have_custom_fields) {
                $xtpl->parse('main.allowuserreg.field');
            }

            if ($global_config['allowuserreg'] == 2) {
                $xtpl->assign('LOSTACTIVELINK_SRC', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=lostactivelink');
                $xtpl->parse('main.allowuserreg.lostactivelink');
            }

            $xtpl->parse('main.allowuserreg.agreecheck');
            $xtpl->parse('main.allowuserreg');
            $xtpl->parse('main.allowuserreg2');
            $xtpl->parse('main.allowuserreg3');

            if ($datepicker) {
                $xtpl->parse('main.datepicker');
            }
        }

        $xtpl->parse('main');
        $content = $xtpl->text('main');
    }
}