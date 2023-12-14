<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
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

    $filters = [
        'all' => [
            'name' => $nv_Lang->getGlobal('all'),
            'is_active' => false
        ],
        'unviewed' => [
            'name' => $nv_Lang->getGlobal('unviewed'),
            'is_active' => false
        ],
        'favorite' => [
            'name' => $nv_Lang->getGlobal('favorite'),
            'is_active' => false
        ]
    ];
    $inform_filter_default = $nv_Request->get_title('inform_filter', 'session', 'unviewed');
    !isset($filters[$inform_filter_default]) && $inform_filter_default = 'all';
    $filters[$inform_filter_default]['is_active'] = true;

    $u_groups = array_values(array_unique(array_filter(array_map(function ($gr) {
        return $gr >= 10 ? (int) $gr : 0;
    }, $user_info['in_groups']))));
    $u_groups = !empty($u_groups) ? implode(',', $u_groups) : '';

    $module_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=inform';
    $viewall_url = nv_apply_hook('inform', 'get_all_inform_link', [], $module_url);
    $csrf = md5($user_info['userid'] . $u_groups . NV_CHECK_SESSION);

    $block_js = get_tpl_dir([$block_config['real_theme'], $global_config['module_theme'], $global_config['site_theme']], 'default', '/js/block.inform.js');

    $stpl = new \NukeViet\Template\NVSmarty();
    $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
    $stpl->assign('LANG', $nv_Lang);
    $stpl->assign('BLOCK_JS', $block_js);
    $stpl->assign('FILTERS', $filters);
    $stpl->assign('REFRESH_TIME', $global_config['inform_refresh_time']);
    $stpl->assign('FILTER_DEFAULT', $inform_filter_default);
    $stpl->assign('INFORM_MODULE_URL', $module_url);
    $stpl->assign('INFORM_VIEWALL_URL', $viewall_url);
    $stpl->assign('CHECK_INFORM_URL', NV_BASE_SITEURL . 'sload.php');
    $stpl->assign('USERID', $user_info['userid']);
    $stpl->assign('USERGROUPS', $u_groups);
    $stpl->assign('CSRF', $csrf);

    $content = $stpl->fetch('block.inform.tpl');
}
