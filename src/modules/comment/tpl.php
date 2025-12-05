<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * Xử lý giao diện đầy đủ cho một khối bình luận
 *
 * @param string $module
 * @param string $area
 * @param int    $id
 * @param mixed  $allowed_comm
 * @param string $checkss
 * @param mixed  $comment
 * @param int    $sortcomm
 * @param array  $form_login
 * @param int    $header
 * @return string
 */
function nv_theme_comment_module($module, $area, $id, $allowed_comm, $checkss, $comment, $sortcomm, $form_login, $header = 1): string
{
    global $global_config, $module_data, $module_config, $admin_info, $user_info, $nv_Lang, $module_name;

    addition_module_assets('comment', 'both');

    $template = get_tpl_dir($global_config['module_theme'], 'default', '/modules/comment/main.tpl');

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $template . '/modules/comment');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('TEMPLATE', $template);
    $tpl->assign('COMMENTCONTENT', $comment);
    $tpl->assign('SORTCOMM', $sortcomm);
    $tpl->assign('ALLOWED_COMM_BOOL', nv_user_in_groups($allowed_comm));
    $tpl->assign('MCONFIG', $module_config[$module]);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('MODULE_COMM', $module);
    $tpl->assign('AREA_COMM', $area);
    $tpl->assign('ID_COMM', $id);
    $tpl->assign('ALLOWED_COMM', $allowed_comm);
    $tpl->assign('CHECKSS_COMM', $checkss);
    $tpl->assign('HEADER', $header);
    $tpl->assign('MODULE_DATA', $module_data);

    if (defined('NV_IS_USER')) {
        $tpl->assign('NAME', $user_info['full_name']);
        $tpl->assign('EMAIL', $user_info['email']);
    }

    // Xác định captcha
    $captcha = (int) ($module_config[$module]['captcha_area_comm']);
    $show_captcha = true;
    if ($captcha == 0) {
        $show_captcha = false;
    } elseif ($captcha == 1 and defined('NV_IS_USER')) {
        $show_captcha = false;
    } elseif ($captcha == 2 and defined('NV_IS_MODADMIN')) {
        if (defined('NV_IS_SPADMIN')) {
            $show_captcha = false;
        } else {
            $adminscomm = array_map('intval', explode(',', $module_config[$module]['adminscomm']));
            if (in_array((int) $admin_info['admin_id'], $adminscomm, true)) {
                $show_captcha = false;
            }
        }
    }

    $captcha_type = (empty($module_config['comment']['captcha_type']) or in_array($module_config['comment']['captcha_type'], ['captcha', 'recaptcha', 'turnstile'], true)) ? $module_config['comment']['captcha_type'] : 'captcha';
    if ($captcha_type == 'recaptcha' and (empty($global_config['recaptcha_sitekey']) or empty($global_config['recaptcha_secretkey']))) {
        $captcha_type = 'captcha';
    }
    if ($captcha_type == 'turnstile' and (empty($global_config['turnstile_sitekey']) or empty($global_config['turnstile_secretkey']))) {
        $captcha_type = 'captcha';
    }
    $tpl->assign('SHOW_CAPTCHA', $show_captcha);

    $captcha_value = '';
    if ($show_captcha) {
        if ($captcha_type == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
            $captcha_value = 'recaptcha3';
        } elseif ($captcha_type == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
            $captcha_value = 'recaptcha2';
            // $xtpl->assign('GFX_NUM', -1);
        } elseif ($captcha_type == 'captcha') {
            $captcha_value = 'captcha';
        } elseif ($captcha_type == 'turnstile') {
            $captcha_value = 'turnstile';
        } else {
            // $xtpl->assign('GFX_NUM', 0);
        }
    } else {
        // $xtpl->assign('GFX_NUM', 0);
    }
    $tpl->assign('CAPTCHA_VALUE', $captcha_value);
    $tpl->assign('FORM_LOGIN', $form_login);

    return $tpl->fetch('main.tpl');

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/comment');
    $xtpl->assign('LANG', \NukeViet\Core\Language::$tmplang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $template);
    $xtpl->assign('TEMPLATE_CSS', $templateCSS);
    $xtpl->assign('TEMPLATE_JS', $templateJS);


    $xtpl->assign('COMMENTCONTENT', $comment);

    if ($allowed_comm) {

        $xtpl->parse('main.allowed_comm');
    } elseif ($form_login['display']) {
        if ($form_login['mode'] == 'direct') {
            // Thành viên đăng nhập trực tiếp
            $xtpl->assign('LOGIN_MESSAGE', $nv_Lang->getModule('comment_login', $form_login['groups'][0]));
            $xtpl->parse('main.form_login.message_login');
        } else {
            // Tham gia nhóm để bình luận
            $xtpl->assign('LANG_REG_GROUPS', $nv_Lang->getModule('comment_register_groups', implode(', ', $form_login['groups']), $form_login['link']));
            $xtpl->parse('main.form_login.message_register_group');
        }
        $xtpl->parse('main.form_login');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Xử lý giao diện danh sách bình luận cấp 1
 *
 * @param string $module
 * @param array  $comment_array
 * @param bool   $is_delete
 * @param bool   $allowed_comm
 * @param string $status_comment
 * @return string
 */
function nv_comment_module_data($module, $comment_array, $is_delete, $allowed_comm, $status_comment)
{
    global $global_config, $module_config;

    if (!empty($comment_array['comment'])) {
        $template = get_tpl_dir($global_config['module_theme'], 'default', '/modules/comment/comment.tpl');
        $templateJS = get_tpl_dir($global_config['module_theme'], 'default', '/js/comment.js');

        $xtpl = new XTemplate('comment.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/comment');
        $xtpl->assign('TEMPLATE', $template);
        $xtpl->assign('LANG', \NukeViet\Core\Language::$tmplang_module);
        $xtpl->assign('TEMPLATE_JS', $templateJS);

        if (!empty($status_comment)) {
            $status_comment = nv_base64_decode($status_comment);
            $xtpl->assign('STATUS_COMMENT', $status_comment);
            $xtpl->parse('main.comment_result');
        }

        $viewuser = nv_user_in_groups($global_config['whoviewuser']);

        foreach ($comment_array['comment'] as $comment_array_i) {
            if (!empty($comment_array_i['subcomment'])) {
                $comment_array_reply = nv_comment_module_data_reply($module, $comment_array_i['subcomment'], $is_delete, $allowed_comm);
                $xtpl->assign('CHILDREN', $comment_array_reply);
                $xtpl->parse('main.detail.children');
            }
            $comment_array_i['post_time'] = nv_datetime_format($comment_array_i['post_time']);

            if (!empty($comment_array_i['photo']) and file_exists(NV_ROOTDIR . '/' . $comment_array_i['photo'])) {
                $comment_array_i['photo'] = NV_BASE_SITEURL . $comment_array_i['photo'];
            } elseif (is_file(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/images/users/no_avatar.png')) {
                $comment_array_i['photo'] = NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/images/users/no_avatar.png';
            } else {
                $comment_array_i['photo'] = NV_STATIC_URL . 'themes/default/images/users/no_avatar.png';
            }

            if (!empty($comment_array_i['userid'])) {
                $comment_array_i['post_name'] = nv_show_name_user($comment_array_i['first_name'], $comment_array_i['last_name'], $comment_array_i['username']);
            }

            $xtpl->assign('COMMENT', $comment_array_i);

            if ($viewuser and !empty($comment_array_i['user'])) {
                $xtpl->parse('main.detail.viewuser');
                $xtpl->parse('main.detail.viewuser2');
            }

            if ($module_config[$module]['emailcomm'] and !empty($comment_array_i['post_email'])) {
                $xtpl->parse('main.detail.emailcomm');
            }

            if ($allowed_comm) {
                $xtpl->parse('main.detail.allowed_comm');
            }

            if ($is_delete) {
                $xtpl->parse('main.detail.delete');
            }

            if (!empty($comment_array_i['attach'])) {
                $xtpl->parse('main.detail.attach');
            }

            $xtpl->parse('main.detail');
        }
        if (!empty($comment_array['page'])) {
            $xtpl->assign('PAGE', $comment_array['page']);
        }
        $xtpl->parse('main');

        return $xtpl->text('main');
    }

    return '';
}

/**
 * Xử lý giao diện danh sách bình luận cấp con (reply)
 * Đệ quy cho mọi cấp bên trong nữa
 *
 * @param string $module
 * @param array  $comment_array
 * @param bool   $is_delete
 * @param bool   $allowed_comm
 * @return string
 */
function nv_comment_module_data_reply($module, $comment_array, $is_delete, $allowed_comm)
{
    global $global_config, $module_file, $module_config;

    $template = get_tpl_dir($global_config['module_theme'], 'default', '/modules/comment/comment.tpl');
    $templateJS = get_tpl_dir($global_config['module_theme'], 'default', '/js/comment.js');

    $xtpl = new XTemplate('comment.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/comment');
    $xtpl->assign('TEMPLATE', $template);
    $xtpl->assign('TEMPLATE_JS', $templateJS);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$tmplang_module);

    $viewuser = nv_user_in_groups($global_config['whoviewuser']);

    foreach ($comment_array as $comment_array_i) {
        if (!empty($comment_array_i['subcomment'])) {
            $comment_array_reply = nv_comment_module_data_reply($module, $comment_array_i['subcomment'], $is_delete, $allowed_comm);
            $xtpl->assign('CHILDREN', $comment_array_reply);
            $xtpl->parse('children.detail.children');
        }
        $comment_array_i['post_time'] = nv_datetime_format($comment_array_i['post_time']);

        if (!empty($comment_array_i['photo']) and file_exists(NV_ROOTDIR . '/' . $comment_array_i['photo'])) {
            $comment_array_i['photo'] = NV_BASE_SITEURL . $comment_array_i['photo'];
        } else {
            $comment_array_i['photo'] = NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/images/users/no_avatar.png';
        }

        if (!empty($comment_array_i['userid'])) {
            $comment_array_i['post_name'] = nv_show_name_user($comment_array_i['first_name'], $comment_array_i['last_name']);
        }

        $xtpl->assign('COMMENT', $comment_array_i);

        if ($viewuser and !empty($comment_array_i['user'])) {
            $xtpl->parse('children.detail.viewuser');
        }

        if ($module_config[$module]['emailcomm'] and !empty($comment_array_i['post_email'])) {
            $xtpl->parse('children.detail.emailcomm');
        }

        if ($allowed_comm) {
            $xtpl->parse('children.detail.allowed_comm');
        }

        if ($is_delete) {
            $xtpl->parse('children.detail.delete');
        }

        if (!empty($comment_array_i['attach'])) {
            $xtpl->parse('children.detail.attach');
        }

        $xtpl->parse('children.detail');
    }
    $xtpl->parse('children');

    return $xtpl->text('children');
}
