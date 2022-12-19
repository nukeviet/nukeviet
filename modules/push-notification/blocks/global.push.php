<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

global $global_config, $user_info, $lang_global, $blockID, $nv_Request;

$content = '';

if (!empty($global_config['push_active']) and defined('NV_IS_USER')) {
    if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/push-notification/block.push.tpl')) {
        $block_theme = $global_config['module_theme'];
    } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/push-notification/block.push.tpl')) {
        $block_theme = $global_config['site_theme'];
    } else {
        $block_theme = 'default';
    }

    if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/js/push.js')) {
        $block_js = $global_config['module_theme'];
    } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/js/push.js')) {
        $block_js = $global_config['site_theme'];
    } else {
        $block_js = 'default';
    }

    $filters = [
        'all' => $lang_global['all'],
        'unviewed' => $lang_global['unviewed'],
        'favorite' => $lang_global['favorite']
    ];
    $push_filter_default = $nv_Request->get_title('push_filter', 'session', 'unviewed');
    !isset($filters[$push_filter_default]) && $push_filter_default = 'all';

    $u_groups = array_values(array_unique(array_filter(array_map(function ($gr) {
        return $gr >= 10 ? (int) $gr : 0;
    }, $user_info['in_groups']))));
    $u_groups = !empty($u_groups) ? implode(',', $u_groups) : '';

    $xtpl = new XTemplate('block.push.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/push-notification');

    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('BLOCKID', $blockID);
    $xtpl->assign('BLOCK_THEME', $block_theme);
    $xtpl->assign('BLOCK_JS', $block_js);
    $xtpl->assign('REFRESH_TIME', $global_config['push_refresh_time']);
    $xtpl->assign('FILTER_DEFAULT', $push_filter_default);
    $xtpl->assign('PUSH_MODULE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=push-notification');
    $xtpl->assign('USERID', $user_info['userid']);
    $xtpl->assign('USERGROUPS', $u_groups);
    $xtpl->assign('CSRF', md5($user_info['userid'] . NV_CHECK_SESSION));

    foreach ($filters as $key => $name) {
        $xtpl->assign('FILTER', [
            'key' => $key,
            'name' => $name
        ]);

        if ($key == $push_filter_default) {
            $xtpl->parse('main.filter.default');
        }
        $xtpl->parse('main.filter');
    }

    $xtpl->parse('main');
    $content = $xtpl->text('main');
}
