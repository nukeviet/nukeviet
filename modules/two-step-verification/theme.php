<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (! defined('NV_MOD_2STEP_VERIFICATION')) {
    die('Stop!!!');
}

function nv_theme_info_2step($data)
{
    global $module_info, $module_file, $global_config, $lang_global, $lang_module, $module_name, $op, $nv_redirect;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('DATA', $data);
    $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostpass');
    $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
    $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
    $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
    $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
    $xtpl->assign('SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha&t=' . NV_CURRENTTIME);
    $xtpl->assign('GFX_MAXLENGTH', NV_GFX_NUM);

    if (! empty($nv_redirect)) {
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
            if ($_li['func_name'] == 'register' and ! $global_config['allowuserreg']) {
                continue;
            }

            $href = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $_alias[$_li['func_name']];
            if (! empty($nv_redirect)) {
                $href .= '&nv_redirect=' . $nv_redirect;
            }
            $li = array( 'href' => $href, 'title' => $_li['func_name'] == 'main' ? $module_info['custom_title'] : $_li['func_custom_name'] );
            $xtpl->assign('NAVBAR', $li);
            $xtpl->parse('main.navbar');
        }
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_theme_creat_2step()
{
    
}

function nv_theme_confirm_password()
{
    
}
