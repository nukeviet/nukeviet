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

global $global_config, $user_info, $nv_Lang, $blockID, $nv_Request;

$content = '';

if (!empty($global_config['inform_active']) and defined('NV_IS_USER') and !defined('NV_IS_BLOCK_INFORM')) {
    // Giới hạn block này chỉ thêm 1 lần duy nhất
    define('NV_IS_BLOCK_INFORM', true);

    if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/inform/block.inform.tpl')) {
        $block_theme = $global_config['module_theme'];
    } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/inform/block.inform.tpl')) {
        $block_theme = $global_config['site_theme'];
    } else {
        $block_theme = 'default';
    }

    if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/js/block.inform.js')) {
        $block_js = $global_config['module_theme'];
    } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/js/block.inform.js')) {
        $block_js = $global_config['site_theme'];
    } else {
        $block_js = 'default';
    }

    $filters = [
        'all' => $nv_Lang->getGlobal('all'),
        'unviewed' => $nv_Lang->getGlobal('unviewed'),
        'favorite' => $nv_Lang->getGlobal('favorite')
    ];
    $inform_filter_default = $nv_Request->get_title('inform_filter', 'session', 'unviewed');
    !isset($filters[$inform_filter_default]) && $inform_filter_default = 'all';

    $u_groups = array_values(array_unique(array_filter(array_map(function ($gr) {
        return $gr >= 10 ? (int) $gr : 0;
    }, $user_info['in_groups']))));
    $u_groups = !empty($u_groups) ? implode(',', $u_groups) : '';

    $xtpl = new XTemplate('block.inform.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/inform');

    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('BLOCKID', $blockID);
    $xtpl->assign('BLOCK_THEME', $block_theme);
    $xtpl->assign('BLOCK_JS', $block_js);
    $xtpl->assign('REFRESH_TIME', $global_config['inform_refresh_time']);
    $xtpl->assign('FILTER_DEFAULT', $inform_filter_default);

    $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=inform';
    $xtpl->assign('INFORM_MODULE_URL', $url);
    $xtpl->assign('INFORM_VIEWALL_URL', nv_apply_hook('inform', 'get_all_inform_link', [], $url));
    $xtpl->assign('CHECK_INFORM_URL', NV_BASE_SITEURL . 'sload.php');

    $xtpl->assign('USERID', $user_info['userid']);
    $xtpl->assign('USERGROUPS', $u_groups);
    $xtpl->assign('CSRF', md5($user_info['userid'] . $u_groups . NV_CHECK_SESSION));

    foreach ($filters as $key => $name) {
        $xtpl->assign('FILTER', [
            'key' => $key,
            'name' => $name
        ]);

        if ($key == $inform_filter_default) {
            $xtpl->parse('main.filter.default');
        }
        $xtpl->parse('main.filter');
    }

    $xtpl->parse('main');
    $content = $xtpl->text('main');
}
