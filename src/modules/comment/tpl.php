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
 * @param mixed  $comment HTML danh sách bình luận
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
        } elseif ($captcha_type == 'captcha') {
            $captcha_value = 'captcha';
        } elseif ($captcha_type == 'turnstile') {
            $captcha_value = 'turnstile';
        }
    }
    $tpl->assign('CAPTCHA_VALUE', $captcha_value);
    $tpl->assign('FORM_LOGIN', $form_login);

    return $tpl->fetch('main.tpl');
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
function nv_comment_module_data($module, $comment_array, $is_delete, $allowed_comm, $status_comment = '')
{
    global $global_config, $module_config, $nv_Lang;

    if (empty($comment_array['comment'])) {
        return '';
    }

    $template = get_tpl_dir($global_config['module_theme'], 'default', '/modules/comment/comment.tpl');

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $template . '/modules/comment');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('TEMPLATE', $template);
    $tpl->assign('MCONFIG', $module_config[$module]);
    $tpl->assign('ALLOWED_COMM', $allowed_comm);
    $tpl->assign('ALLOWED_DELETE', $is_delete);

    // Hiển thị trạng thái sau khi gửi một bình luận
    !empty($status_comment) && $status_comment = nv_htmlspecialchars(nv_base64_decode($status_comment));
    $tpl->assign('COMMENT_RESULT', $status_comment);

    // Xử lý danh sách bình luận trước khi đưa ra giao diện
    foreach ($comment_array['comment'] as $cid => $row) {
        // Render giao diện bình luận con nếu có
        $row['children'] = !empty($row['subcomment']) ? nv_comment_module_data($module, ['comment' => $row['subcomment']], $is_delete, $allowed_comm) : '';
        $row['post_name_letter'] = $row['post_name'] ? nv_strtoupper(nv_substr($row['post_name'], 0, 1)) : 'V';
        if (!empty($row['userid'])) {
            $row['post_name'] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
        }

        $comment_array['comment'][$cid] = $row;
    }
    $tpl->assign('DATA', $comment_array);

    return $tpl->fetch('comment.tpl');
}
