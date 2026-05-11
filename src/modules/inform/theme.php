<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_INFORM')) {
    exit('Stop!!!');
}

/**
 * @return string
 */
function main_theme()
{
    global $nv_Lang, $module_info, $module_name;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('PAGE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);

    $filters = [
        ['key' => 'unviewed', 'title' => $nv_Lang->getModule('filter_unviewed')],
        ['key' => 'favorite', 'title' => $nv_Lang->getModule('filter_favorite')],
        ['key' => 'hidden', 'title' => $nv_Lang->getModule('filter_hidden')]
    ];
    $tpl->assign('FILTERS', $filters);

    return $tpl->fetch('main.tpl');
}

/**
 * @param array  $items         mảng các thông báo
 * @param string $generate_page phân trang
 * @param string $filter        kiểu list: tất cả, chưa đọc, yêu thích
 * @param string $page_url      link trang
 * @return string
 */
function user_getlist_theme($items, $generate_page, $filter, $page_url)
{
    global $global_config, $nv_Lang, $module_info;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('list.tpl'));
    $tpl->assign('LANG', $nv_Lang);

    if (!empty($items)) {
        $keys = array_keys($items);
        foreach ($keys as $key) {
            if (empty($items[$key]['message'])) {
                unset($items[$key]);
                continue;
            }
            $items[$key]['is_hidden'] = $filter == 'hidden' ? 1 : 0;
            $items[$key]['is_viewed'] = !empty($items[$key]['viewed_time']) ? 1 : 0;
            $items[$key]['is_favorite'] = !empty($items[$key]['favorite_time']) ? 1 : 0;
            $items[$key]['add_time'] = nv_datetime_format($items[$key]['add_time']);
            if (!empty($items[$key]['link']) and !preg_match('#^https?\:\/\/#', $items[$key]['link'])) {
                $items[$key]['link'] = nv_url_rewrite(NV_BASE_SITEURL . $items[$key]['link'], true);
            }
        }
    }

    $tpl->assign('PAGE_URL', nv_url_rewrite($page_url, true));
    $tpl->assign('ITEMS', $items);
    $tpl->assign('GENERATE_PAGE', $generate_page);

    return $tpl->fetch('list.tpl');
}

/**
 * @param array $items
 * @param string $generate_page
 * @param int $group_id
 * @param array $members
 * @return string
 */
function getlist_theme($items, $generate_page, $group_id, $members)
{
    global $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('notifications_list.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('ITEMS', $items);
    $tpl->assign('MEMBERS', $members);
    $tpl->assign('GENERATE_PAGE', $generate_page);

    return $tpl->fetch('notifications_list.tpl');
}

/**
 * @param string $contents
 * @param string $page_url
 * @param string $filter
 * @param string $checkss
 * @return string
 */
function notifications_manager_theme($contents, $page_url, $filter, $checkss)
{
    global $global_config, $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('notifications_manager.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('PAGE_CONTENT', $contents);
    $tpl->assign('MANAGER_PAGE_URL', $page_url);
    $tpl->assign('CHECKSS', $checkss);
    $tpl->assign('CURRENT_FILTER', $filter);
    $tpl->assign('INFORM_MANAGER_THEME', get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'future', 'js/inform-manager.js'));

    $filters = [
        ['key' => 'active', 'name' => $nv_Lang->getModule('active')],
        ['key' => 'waiting', 'name' => $nv_Lang->getModule('waiting')],
        ['key' => 'expired', 'name' => $nv_Lang->getModule('expired')],
        ['key' => '', 'name' => $nv_Lang->getModule('filter_all')]
    ];
    $tpl->assign('FILTERS', $filters);

    return $tpl->fetch('notifications_manager.tpl');
}

/**
 * @param array $data
 * @param string $page_url
 * @param string $checkss
 * @return string
 */
function notification_action_theme($data, $page_url, $checkss)
{
    global $global_config, $language_array, $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('notification_action.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MANAGER_PAGE_URL', $page_url);
    $tpl->assign('DATA', $data);
    $tpl->assign('CHECKSS', $checkss);

    $receiver_ids = [];
    if (!empty($data['receiver_ids'])) {
        foreach ($data['receiver_ids'] as $id => $fullname) {
            $receiver_ids[] = [
                'id' => $id,
                'fullname' => $fullname
            ];
        }
    }
    $tpl->assign('RECEIVER_IDS', $receiver_ids);

    $messages = [];
    $links = [];
    foreach ($global_config['setup_langs'] as $lang) {
        $messages[] = [
            'lang' => $lang,
            'langname' => $language_array[$lang]['name'],
            'content' => !empty($data['message'][$lang]) ? nv_br2nl($data['message'][$lang]) : ''
        ];
        $links[] = [
            'lang' => $lang,
            'langname' => $language_array[$lang]['name'],
            'content' => !empty($data['link'][$lang]) ? $data['link'][$lang] : ''
        ];
    }
    $tpl->assign('MESSAGES', $messages);
    $tpl->assign('LINKS', $links);

    $hours = [];
    $minutes = [];
    for ($i = 0; $i < 24; ++$i) {
        $hours[] = [
            'val' => $i,
            'name' => str_pad($i, 2, '0', STR_PAD_LEFT)
        ];
    }
    for ($i = 0; $i < 60; ++$i) {
        $minutes[] = [
            'val' => $i,
            'name' => str_pad($i, 2, '0', STR_PAD_LEFT)
        ];
    }
    $tpl->assign('HOURS', $hours);
    $tpl->assign('MINUTES', $minutes);

    return $tpl->fetch('notification_action.tpl');
}
