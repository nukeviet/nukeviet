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
 * nv_theme_info_2step()
 *
 * @param array $data
 * @param bool  $autoshowcode
 * @return string
 */
function nv_theme_info_2step($data, $autoshowcode)
{
    global $nv_Lang, $user_info, $module_name, $global_config;

    $template_js = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', 'js/users.passkey.js');

    $xtpl = new XTemplate('main.tpl', get_module_tpl_dir('main.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('DATA', $data);
    $xtpl->assign('TEMPLATE_JS', $template_js);

    if (empty($user_info['active2step'])) {
        $xtpl->assign('LINK_TURNON', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setup');
        $xtpl->parse('main.turnon');
    } else {
        $xtpl->parse('main.turnoff');
    }

    if (empty($user_info['active2step'])) {
        $xtpl->parse('main.off');
    } else {
        $code_unused = 0;
        $xtpl->parse('main.backupcodeModal');

        $xtpl->assign('NUM_CODE', $nv_Lang->getModule('backupcode_2step', $code_unused));

        if ($autoshowcode) {
            $xtpl->parse('main.backupcode.autoshowcode');
        }

        $xtpl->parse('main.backupcode');
        $xtpl->parse('main.on');
    }

    // Mã dự phòng
    if (empty($data['backupcodes'])) {
        $xtpl->parse('main.btn_create_code');
    } else {
        $code_unused = 0;

        foreach ($data['backupcodes'] as $code) {
            $code_unused += !$code['is_used'];
            $xtpl->assign('CODE', $code);

            if ($code['is_used']) {
                $xtpl->parse('main.bcodes.code.used');
            } else {
                $xtpl->parse('main.bcodes.code.unuse');
            }

            $xtpl->parse('main.bcodes.code');
        }

        $xtpl->parse('main.btn_view_code');
        $xtpl->parse('main.bcodes');
    }

    // Ghi chú khóa đăng nhập làm xác thực 2 bước
    if ($data['login_keys'] > 0) {
        $xtpl->parse('main.note_login_keys');
    }

    // Nút thêm nếu chưa có khóa bảo mật
    if ($data['security_keys'] == 0) {
        $xtpl->parse('main.btn_add_key');
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
    $xtpl->assign('QR_SRC', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/qr-image/' . nv_genpass());
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
        $xtpl->assign('CODE', $code);
        $xtpl->parse('main.code');
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
