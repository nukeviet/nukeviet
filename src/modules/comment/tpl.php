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
 * nv_theme_comment_module()
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
function nv_theme_comment_module($module, $area, $id, $allowed_comm, $checkss, $comment, $sortcomm, $form_login, $header = 1)
{
    global $global_config, $module_data, $module_config, $admin_info, $user_info, $nv_Lang, $module_name;
    $nv_Lang->loadModule('comment', false, true);
    $template = get_tpl_dir($global_config['module_theme'], 'default', '/modules/comment/main.tpl');
    $templateCSS = get_tpl_dir($global_config['module_theme'], 'default', '/css/comment.css');
    $templateJS = get_tpl_dir($global_config['module_theme'], 'default', '/js/comment.js');

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/comment');
    $xtpl->assign('LANG', \NukeViet\Core\Language::$tmplang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $template);
    $xtpl->assign('TEMPLATE_CSS', $templateCSS);
    $xtpl->assign('TEMPLATE_JS', $templateJS);
    $xtpl->assign('CHECKSS_COMM', $checkss);
    $xtpl->assign('MODULE_COMM', $module);
    $xtpl->assign('MODULE_DATA', $module_data);
    $xtpl->assign('AREA_COMM', $area);
    $xtpl->assign('ID_COMM', $id);
    $xtpl->assign('ALLOWED_COMM', $allowed_comm);
    $xtpl->assign('COMMENTCONTENT', $comment);

    // Hiện không dùng, giữ lại để tương thích phiên bản cũ.
    // $xtpl->assign('BASE_URL_COMM', $base_url);

    if (defined('NV_COMM_ID')) {
        $xtpl->parse('main.header');
    }

    // Order by comm
    for ($i = 0; $i <= 2; ++$i) {
        $xtpl->assign('OPTION', [
            'key' => $i,
            'title' => $nv_Lang->getModule('sortcomm_' . $i),
            'selected' => ($i == $sortcomm) ? ' selected="selected"' : ''
        ]);

        $xtpl->parse('main.sortcomm');
    }

    $allowed_comm = nv_user_in_groups($allowed_comm);
    if ($allowed_comm) {
        $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=comment&amp;' . NV_OP_VARIABLE . '=post');

        if (defined('NV_IS_USER')) {
            $xtpl->assign('NAME', $user_info['full_name']);
            $xtpl->assign('EMAIL', $user_info['email']);
            $xtpl->assign('DISABLED', ' disabled="disabled"');
        } else {
            $xtpl->assign('NAME', '');
            $xtpl->assign('EMAIL', '');
            $xtpl->assign('DISABLED', '');
        }

        if (!empty($module_config[$module]['allowattachcomm'])) {
            $xtpl->assign('ENCTYPE', ' enctype="multipart/form-data"');
            $xtpl->parse('main.allowed_comm.attach');
        }

        if (!empty($module_config[$module]['alloweditorcomm'])) {
            $xtpl->assign('EDITOR_COMM', 1);
            if ($header) {
                $replaces = [];
                $replaces[] = "width:'100%', height:'200px', removePlugins: 'uploadfile,uploadimage,autosave', toolbar: 'User', format_tags: 'p;div;h2;h3;h4;h5;h6', forcePasteAsPlainText: true, tabSpaces: 0, fillEmptyBlocks: false";

                $allowed_html_tags = ['b', 'blockquote', 'br', 'div', 'em', 'h2', 'h3', 'h4', 'h5', 'h6', 'i', 'li', 'p', 'span', 'strong', 's', 'u', 'ul', 'ol'];
                $allowedContent = [];
                foreach ($allowed_html_tags as $tag) {
                    $allowedContent[] = $tag . '[*]{*}(*)';
                }
                $replaces[] = "allowedContent:'" . implode(';', $allowedContent) . "'";
                $replaces[] = "disallowedContent:'script; *[on*,action,background,codebase,dynsrc,lowsrc,allownetworking,allowscriptaccess,fscommand,seeksegmenttime]'";
                $replaces = implode(', ', $replaces);
                $xtpl->assign('NV_EDITORSDIR', NV_EDITORSDIR);
                $xtpl->assign('TIMESTAMP', $global_config['timestamp']);
                $xtpl->assign('REPLACES', $replaces);
                $xtpl->parse('main.allowed_comm.editor');
            }
        } else {
            $xtpl->assign('EDITOR_COMM', 0);
        }

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

        if ($show_captcha) {
            if ($captcha_type == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
                $xtpl->parse('main.allowed_comm.recaptcha3');
            } elseif ($captcha_type == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
                $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
                $xtpl->assign('GFX_NUM', -1);
                $xtpl->parse('main.allowed_comm.recaptcha');
            } elseif ($captcha_type == 'captcha') {
                $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode'));
                $xtpl->parse('main.allowed_comm.captcha');
            } elseif ($captcha_type == 'turnstile') {
                $xtpl->parse('main.allowed_comm.turnstile');
            } else {
                $xtpl->assign('GFX_NUM', 0);
            }
        } else {
            $xtpl->assign('GFX_NUM', 0);
        }

        if (!empty($global_config['data_warning']) or !empty($global_config['antispam_warning'])) {
            if (!empty($global_config['data_warning'])) {
                $xtpl->assign('DATA_USAGE_CONFIRM', !empty($global_config['data_warning_content']) ? $global_config['data_warning_content'] : $nv_Lang->getGlobal('data_warning_content'));
                $xtpl->parse('main.allowed_comm.confirm.data_sending');
            }

            if (!empty($global_config['antispam_warning'])) {
                $xtpl->assign('ANTISPAM_CONFIRM', !empty($global_config['antispam_warning_content']) ? $global_config['antispam_warning_content'] : $nv_Lang->getGlobal('antispam_warning_content'));
                $xtpl->parse('main.allowed_comm.confirm.antispam');
            }
            $xtpl->parse('main.allowed_comm.confirm');
        }

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
    $nv_Lang->changeLang();

    return $xtpl->text('main');
}

/**
 * nv_comment_module_data()
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
 * nv_comment_module_data_reply()
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
