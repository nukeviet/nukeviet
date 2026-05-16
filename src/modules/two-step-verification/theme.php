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

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
    $tpl->registerPlugin('modifier', 'dnumber', 'nv_number_format');

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('ACTIVE2STEP', !empty($user_info['active2step']));
    $tpl->assign('LINK_TURNON', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setup');

    if (empty($user_info['active2step'])) {
        return $tpl->fetch('main.tpl');
    }

    $template_js = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', 'js/users.passkey.js');
    $tpl->assign('TEMPLATE_JS', $template_js);

    // Build danh sách khóa bảo mật đã xử lý
    $seckeys = [];
    foreach ($data['publicKeys'] as $seckey) {
        if (!empty($seckey['enable_login'])) {
            continue;
        }
        $seckey['created_at'] = nv_datetime_format($seckey['created_at'], 1);
        $seckey['last_used_at'] = nv_datetime_format($seckey['last_used_at'], 1);
        $seckey['is_this_client'] = ($seckey['clid'] == $client_info['clid']);
        $seckeys[] = $seckey;
    }

    // Đếm mã dự phòng chưa dùng
    $code_unused = 0;
    foreach ($data['backupcodes'] as $code) {
        if (empty($code['is_used'])) {
            $code_unused++;
        }
    }

    // Biến cho form thiết lập app (chỉ có giá trị khi show_type == 'app')
    $tpl->assign('QR_SRC', '');
    $tpl->assign('FORM_ACTION', '');
    $tpl->assign('SECRETKEY', '');
    $tpl->assign('NV_REDIRECT', '');

    if ($data['show_type'] == 'app') {
        $tpl->assign('QR_SRC', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=qrimg&amp;t=' . nv_genpass());
        $tpl->assign('FORM_ACTION', $data['page_url'] . '&amp;type=app');
        $tpl->assign('SECRETKEY', strtolower($data['secretkey'] ?? ''));
    }

    $tpl->assign('DATA', $data);
    $tpl->assign('SECKEYS', $seckeys);
    $tpl->assign('CODE_UNUSED', $code_unused);

    return $tpl->fetch('main.tpl');
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
    global $nv_Lang, $module_name, $op;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('config.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('SECRETKEY', strtolower($secretkey));
    $tpl->assign('QR_SRC', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=qrimg&amp;t=' . nv_genpass());
    $tpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
    $tpl->assign('NV_REDIRECT', $nv_redirect);

    return $tpl->fetch('config.tpl');
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

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('confirm_password.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('IS_PASS_VALID', $is_pass_valid);
    $tpl->assign('USERS_PASS_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=editinfo/password');

    return $tpl->fetch('confirm_password.tpl');
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
    global $nv_Lang, $module_name;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('complete.tpl'));

    $codes = [];
    foreach ($backupcodes as $code) {
        if (!empty($code['is_used'])) {
            continue;
        }
        $codes[] = $code;
    }

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('CODES', $codes);
    $tpl->assign('DATA', $array_data);

    return $tpl->fetch('complete.tpl');
}

/**
 * @param array $array_data
 * @return string
 */
function nv_theme_review_2step(array $array_data)
{
    global $nv_Lang, $module_name;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('review.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('DATA', $array_data);

    return $tpl->fetch('review.tpl');
}

/**
 * @param array $backupcodes
 * @return string
 */
function nv_theme_print_code(array $backupcodes)
{
    global $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('print.tpl'));

    $codes = [];
    foreach ($backupcodes as $code) {
        if (!empty($code['is_used'])) {
            continue;
        }
        $codes[] = $code;
    }

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('CODES', $codes);

    return $tpl->fetch('print.tpl');
}
