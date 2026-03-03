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

    $template_js = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], NV_DEFAULT_SITE_THEME, 'js/users.passkey.js');
    $link_turnon = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setup';

    $showKeys = ($data['show_type'] == 'key');
    $showCodes = ($data['show_type'] == 'code');
    $messageLoginKeys = '';
    if (!empty($data['login_keys'])) {
        $messageLoginKeys = $nv_Lang->getModule('rcode_note', nv_number_format($data['login_keys']));
    }

    // Security keys list (exclude login keys)
    $secKeys = [];
    if (!empty($data['publicKeys'])) {
        foreach ($data['publicKeys'] as $seckey) {
            if (!empty($seckey['enable_login'])) {
                continue;
            }
            $secKeys[] = [
                'id' => $seckey['id'],
                'nickname' => $seckey['nickname'],
                'created_at' => nv_datetime_format($seckey['created_at'], 1),
                'last_used_at' => nv_datetime_format($seckey['last_used_at'], 1),
                'this_client' => ($seckey['clid'] == ($client_info['clid'] ?? ''))
            ];
        }
    }
    $numberKeysText = $nv_Lang->getModule('number_keys', nv_number_format($data['security_keys'] ?? 0));

    // Recovery codes info
    $codeUnused = 0;
    if (!empty($data['backupcodes'])) {
        foreach ($data['backupcodes'] as $code) {
            $codeUnused += empty($code['is_used']) ? 1 : 0;
        }
    }
    $remainCodeText = $nv_Lang->getModule('remain_code', nv_number_format($codeUnused));
    $usedupCode = ($codeUnused < 1);
    $lackCode = (!$usedupCode && $codeUnused < 3);

    // App flow data
    $qrSrc = '';
    $formAction = '';
    $nvRedirect = '';
    $secretkeyLower = '';
    $scrollApp = false;
    if ($data['show_type'] == 'app') {
        $qrSrc = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=qrimg&amp;t=' . nv_genpass();
        $formAction = ($data['page_url'] ?? '') . '&amp;type=app';
        $nvRedirect = '';
        $secretkeyLower = isset($data['secretkey']) ? strtolower($data['secretkey']) : '';
        $scrollApp = true;
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $tpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);
    $tpl->assign('ACTIVE_2STEP', !empty($user_info['active2step']));
    $tpl->assign('LINK_TURNON', $link_turnon);
    $tpl->assign('TEMPLATE_JS', $template_js);
    $tpl->assign('DATA', $data);
    $tpl->assign('MESSAGE', $messageLoginKeys);
    $tpl->assign('SHOW_KEYS', $showKeys);
    $tpl->assign('SHOW_CODES', $showCodes);
    $tpl->assign('SECKEYS', $secKeys);
    $tpl->assign('NUMBER_KEYS_TEXT', $numberKeysText);
    $tpl->assign('REMAIN_CODE_TEXT', $remainCodeText);
    $tpl->assign('USEDUP_CODE', $usedupCode);
    $tpl->assign('LACK_CODE', $lackCode);
    $tpl->assign('QR_SRC', $qrSrc);
    $tpl->assign('FORM_ACTION', $formAction);
    $tpl->assign('NV_REDIRECT', $nvRedirect);
    $tpl->assign('SECRETKEY', $secretkeyLower);
    $tpl->assign('SCROLL_APP', $scrollApp);

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

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('confirm_password.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('IS_VALID', $is_pass_valid);
    if ($is_pass_valid) {
        $tpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);
        $tpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
    } else {
        $tpl->assign('CHANGE_2STEP_NOTVALID', $nv_Lang->getModule('change_2step_notvalid', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=editinfo/password'));
    }

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
