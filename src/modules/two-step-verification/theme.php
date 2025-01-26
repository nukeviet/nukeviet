<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MOD_2STEP_VERIFICATION')) {
    exit('Stop!!!');
}

/**
 * @param array $data
 * @return string
 */
function nv_theme_info_2step(array $data)
{
    global $nv_Lang, $user_info, $module_name, $global_config, $client_info;

    $xtpl = new XTemplate('main.tpl', get_module_tpl_dir('main.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('DATA', $data);

    // Thông báo bật xác thực 2 bước để tiếp tục
    if (empty($user_info['active2step'])) {
        $xtpl->assign('LINK_TURNON', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setup');
        $xtpl->parse('off');
        return $xtpl->text('off');
    }

    $template_js = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', 'js/users.passkey.js');
    $xtpl->assign('TEMPLATE_JS', $template_js);

    // Ghi chú khóa đăng nhập làm xác thực 2 bước
    if ($data['login_keys'] > 0) {
        $xtpl->assign('MESSAGE', $nv_Lang->getModule('rcode_note', nv_number_format($data['login_keys'])));
        $xtpl->parse('main.note_login_keys');
    }

    // Nút thêm nếu chưa có khóa bảo mật
    if ($data['security_keys'] == 0) {
        $xtpl->parse('main.btn_add_key');
    } else {
        // Collapse danh sách khóa
        if ($data['show_type'] == 'key') {
            $xtpl->assign('CSS_SHOW_KEYS1', ' in');
            $xtpl->assign('CSS_SHOW_KEYS2', 'true');
        } else {
            $xtpl->assign('CSS_SHOW_KEYS1', '');
            $xtpl->assign('CSS_SHOW_KEYS2', 'false');
        }

        // Hiển thị danh sách khóa bảo mật
        foreach ($data['publicKeys'] as $seckey) {
            if (!empty($seckey['enable_login'])) {
                continue;
            }

            $seckey['created_at'] = nv_datetime_format($seckey['created_at'], 1);
            $seckey['last_used_at'] = nv_datetime_format($seckey['last_used_at'], 1);

            $xtpl->assign('SECKEY', $seckey);

            if ($seckey['clid'] == $client_info['clid']) {
                $xtpl->parse('main.seckeys.loop.this_client');
            }

            $xtpl->parse('main.seckeys.loop');
        }

        $xtpl->assign('NUMBER_KEYS', $nv_Lang->getModule('number_keys', nv_number_format($data['security_keys'])));

        $xtpl->parse('main.btn_show_key');
        $xtpl->parse('main.configured_key');
        $xtpl->parse('main.seckeys');
    }

    // Mã dự phòng
    $code_unused = 0;
    foreach ($data['backupcodes'] as $code) {
        $code_unused += !$code['is_used'];
        $xtpl->assign('CODE', $code);

        if ($code['is_used']) {
            $xtpl->parse('main.code.used');
        } else {
            $xtpl->parse('main.code.unuse');
        }

        $xtpl->parse('main.code');
    }
    $xtpl->assign('REMAIN_CODE', $nv_Lang->getModule('remain_code', nv_number_format($code_unused)));

    // Thông báo còn ít mã dự phòng hoặc hết
    if ($code_unused < 1) {
        $xtpl->parse('main.usedup_code');
    } elseif ($code_unused < 3) {
        $xtpl->parse('main.lack_code');
    }

    // Collapse danh sách mã dự phòng
    if ($data['show_type'] == 'code') {
        $xtpl->assign('CSS_SHOW_CODES1', ' in');
        $xtpl->assign('CSS_SHOW_CODES2', 'true');
    } else {
        $xtpl->assign('CSS_SHOW_CODES1', '');
        $xtpl->assign('CSS_SHOW_CODES2', 'false');
    }

    // Thiết lập tự cuộn trang xuống phần app
    if ($data['show_type'] == 'app') {
        $xtpl->assign('QR_SRC', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=qrimg&amp;t=' . nv_genpass());
        $xtpl->assign('FORM_ACTION', $data['page_url'] . '&amp;type=app');
        $xtpl->assign('NV_REDIRECT', '');
        $xtpl->assign('SECRETKEY', strtolower($data['secretkey']));

        $xtpl->parse('main.scroll_app');
        $xtpl->parse('main.edit_app');
    }

    // Xác thực 2 bước ưa thích
    if ($data['pref_2fa'] == 2) {
        $xtpl->assign('PREF_2FA_1', '');
        $xtpl->assign('PREF_2FA_2', ' selected');
    } elseif ($data['pref_2fa'] == 1) {
        $xtpl->assign('PREF_2FA_1', ' selected');
        $xtpl->assign('PREF_2FA_2', '');
    } else {
        $xtpl->assign('PREF_2FA_1', '');
        $xtpl->assign('PREF_2FA_2', '');
    }
    if (!empty($data['publicKeys'])) {
        $xtpl->parse('main.pref_2fa_key');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_config_2step()
 *
 * @param string $secretkey
 * @param string $nv_redirect
 * @return string
 */
function nv_theme_config_2step($secretkey, $nv_redirect)
{
    global $module_name, $op;

    $xtpl = new XTemplate('config.tpl', get_module_tpl_dir('config.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);
    $xtpl->assign('NV_REDIRECT', $nv_redirect);

    $xtpl->assign('SECRETKEY', strtolower($secretkey));
    $xtpl->assign('QR_SRC', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=qrimg&amp;t=' . nv_genpass());
    $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_theme_confirm_password()
 *
 * @param bool $is_pass_valid
 * @return string
 */
function nv_theme_confirm_password($is_pass_valid)
{
    global $nv_Lang, $op, $module_name;

    $xtpl = new XTemplate('confirm_password.tpl', get_module_tpl_dir('confirm_password.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    if ($is_pass_valid) {
        $xtpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);

        $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

        $xtpl->parse('main');

        return $xtpl->text('main');
    }
    $xtpl->assign('CHANGE_2STEP_NOTVALID', $nv_Lang->getModule('change_2step_notvalid', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=editinfo/password'));
    $xtpl->parse('pass_empty');

    return $xtpl->text('pass_empty');
}

/**
 * Thông báo hoàn thành cài đặt xác thực hai bước
 *
 * @param array $backupcodes
 * @param array $array_data
 * @return string
 */
function nv_theme_complete_2step(array $backupcodes, array $array_data)
{
    $xtpl = new XTemplate('complete.tpl', get_module_tpl_dir('complete.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('DATA', $array_data);

    // Danh sách code
    foreach ($backupcodes as $code) {
        if (!empty($code['is_used'])) {
            continue;
        }
        $xtpl->assign('CODE', $code);
        $xtpl->parse('main.code');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * @param array $array_data
 * @return string
 */
function nv_theme_review_2step(array $array_data)
{
    $xtpl = new XTemplate('review.tpl', get_module_tpl_dir('review.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('DATA', $array_data);

    if ($array_data['login_keys'] > 0) {
        $xtpl->parse('main.configured_passkey');
    }
    if ($array_data['security_keys'] > 0) {
        $xtpl->parse('main.configured_seckey');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * @param array $backupcodes
 * @return string
 */
function nv_theme_print_code(array $backupcodes)
{
    $xtpl = new XTemplate('print.tpl', get_module_tpl_dir('print.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    // Danh sách code
    foreach ($backupcodes as $code) {
        if (!empty($code['is_used'])) {
            continue;
        }
        $xtpl->assign('CODE', $code);
        $xtpl->parse('main.code');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
