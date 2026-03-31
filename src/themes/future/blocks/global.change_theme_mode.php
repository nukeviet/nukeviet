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

global $global_config, $module_info, $client_info, $module_name;

$content = '';
if (count($global_config['array_theme_type']) > 1) {
    $mobile_theme = empty($module_info['mobile']) ? $global_config['mobile_theme'] : (($module_info['mobile'] != ':pcmod' and $module_info['mobile'] != ':pcsite') ? $module_info['mobile'] : '');
    if (empty($mobile_theme) or empty($global_config['switch_mobi_des'])) {
        $array_theme_type = array_diff($global_config['array_theme_type'], ['m']);
    } else {
        $array_theme_type = $global_config['array_theme_type'];
    }
    $current_theme_type = (isset($global_config['current_theme_type']) and !empty($global_config['current_theme_type'])) ? $global_config['current_theme_type'] : 'd';

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir($block_config['real_path']);
    $tpl->registerPlugin('modifier', 'redirect_encrypt', 'nv_redirect_encrypt');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('TYPES', $array_theme_type);
    $tpl->assign('CURRENT_TYPE', $current_theme_type);
    $tpl->assign('CLIENT_INFO', $client_info);
    $tpl->assign('MODULE_NAME', $module_name);

    $content = $tpl->fetch('global.change_theme_mode.tpl');
}

/**
 *
foreach ($array_theme_type as $theme_type) {
            $xtpl->assign('STHEME_TYPE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;nv' . NV_LANG_DATA . 'themever=' . $theme_type . '&amp;nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']));
            $xtpl->assign('STHEME_TITLE', $nv_Lang->getGlobal('theme_type_' . $theme_type));
            $xtpl->assign('STHEME_INFO', $nv_Lang->getGlobal('theme_type_chose', $nv_Lang->getGlobal('theme_type_' . $theme_type)));
            $xtpl->assign('STHEME_ICON', $icons[$theme_type]);

            if ($theme_type == $current_theme_type) {
                $xtpl->parse('main.theme_type.loop.current');
            } else {
                $xtpl->parse('main.theme_type.loop.other');
            }

            $xtpl->parse('main.theme_type.loop');
        }
        $xtpl->parse('main.theme_type');
 */
