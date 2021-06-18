<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (! defined('NV_MOD_2STEP_VERIFICATION')) {
    die('Stop!!!');
}

/**
 * nv_theme_info_2step()
 *
 * @param mixed $backupcodes
 * @return
 */
function nv_theme_info_2step($backupcodes, $autoshowcode)
{
    global $module_info, $lang_global, $lang_module, $user_info, $module_name;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);

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
        foreach ($backupcodes as $code) {
            $code_unused += (!$code['is_used']);
            $xtpl->assign('CODE', $code);

            if ($code['is_used']) {
                $xtpl->parse('main.backupcodeModal.code.used');
            } else {
                $xtpl->parse('main.backupcodeModal.code.unuse');
            }

            $xtpl->parse('main.backupcodeModal.code');
        }
        $xtpl->parse('main.backupcodeModal');

        $xtpl->assign('NUM_CODE', sprintf($lang_module['backupcode_2step'], $code_unused));

        if ($autoshowcode) {
            $xtpl->parse('main.backupcode.autoshowcode');
        }

        $xtpl->parse('main.backupcode');
        $xtpl->parse('main.on');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_config_2step()
 *
 * @param mixed $secretkey
 * @param mixed $nv_redirect
 * @return
 */
function nv_theme_config_2step($secretkey, $nv_redirect)
{
    global $module_info, $lang_global, $lang_module, $module_name, $op;

    $xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
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
 * @return
 */
function nv_theme_confirm_password()
{
    global $module_info, $lang_global, $lang_module, $op, $module_name;

    $xtpl = new XTemplate('confirm_password.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);

    $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

    $xtpl->parse('main');
    return $xtpl->text('main');
}
