<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
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
    global $nv_Lang, $module_name;

    $filters = ['unviewed' => $nv_Lang->getModule('filter_unviewed'), 'favorite' => $nv_Lang->getModule('filter_favorite'), 'hidden' => $nv_Lang->getModule('filter_hidden')];

    $stpl = new \NukeViet\Template\NVSmarty();
    $stpl->setTemplateDir(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__) . '/smarty');
    $stpl->assign('LANG', $nv_Lang);
    $stpl->assign('PAGE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
    $stpl->assign('FILTERS', $filters);

    return $stpl->fetch('main.tpl');
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
    global $nv_Lang;

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
            $items[$key]['add_time'] = nv_date('d.m.Y H:i', $items[$key]['add_time']);
            if (!empty($items[$key]['link']) and !preg_match('#^https?\:\/\/#', $items[$key]['link'])) {
                $items[$key]['link'] = nv_url_rewrite(NV_BASE_SITEURL . $items[$key]['link'], true);
            }
        }
    }

    $stpl = new \NukeViet\Template\NVSmarty();
    $stpl->setTemplateDir(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__) . '/smarty');
    $stpl->assign('LANG', $nv_Lang);
    $stpl->assign('PAGE_URL', nv_url_rewrite($page_url, true));
    $stpl->assign('ITEMS', $items);
    $stpl->assign('GENERATE_PAGE', $generate_page);

    return $stpl->fetch('user_get_list.tpl');
}

function getlist_theme($items, $generate_page, $group_id, $members)
{
    global $nv_Lang;

    $stpl = new \NukeViet\Template\NVSmarty();
    $stpl->setTemplateDir(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__) . '/smarty');
    $stpl->assign('LANG', $nv_Lang);
    $stpl->assign('ITEMS', $items);
    $stpl->assign('MEMBERS', $members);
    $stpl->assign('GENERATE_PAGE', $generate_page);

    return $stpl->fetch('notifications_list.tpl');
}

function notifications_manager_theme($contents, $page_url, $filter, $checkss)
{
    global $nv_Lang;

    $dir = str_replace(DIRECTORY_SEPARATOR, '/', __DIR__);
    $template = get_tpl_dir(pathinfo(realpath($dir . '/../../'), PATHINFO_BASENAME), 'default', 'main.tpl');

    $filters = [
        'active' => [
            'name' => $nv_Lang->getModule('active'),
            'sel' => $filter == 'active' ? true : false
        ],
        'waiting' => [
            'name' => $nv_Lang->getModule('waiting'),
            'sel' => $filter == 'waiting' ? true : false
        ],
        'expired' => [
            'name' => $nv_Lang->getModule('expired'),
            'sel' => $filter == 'expired' ? true : false
        ],
        '' => [
            'name' => $nv_Lang->getModule('filter_all'),
            'sel' => false
        ]
    ];

    $stpl = new \NukeViet\Template\NVSmarty();
    $stpl->setTemplateDir($dir . '/smarty');
    $stpl->assign('LANG', $nv_Lang);
    $stpl->assign('TEMPLATE', $template);
    $stpl->assign('PAGE_CONTENT', $contents);
    $stpl->assign('MANAGER_PAGE_URL', $page_url);
    $stpl->assign('CHECKSS', $checkss);
    $stpl->assign('FILTERS', $filters);

    return $stpl->fetch('notifications_manager.tpl');
}

function notification_action_theme($data, $page_url, $checkss)
{
    global $global_config, $language_array, $module_info, $nv_Lang;

    $messs = [];
    $links = [];
    foreach ($global_config['setup_langs'] as $lang) {
        $messs[] = [
            'lang' => $lang,
            'langname' => $language_array[$lang]['name'],
            'content' => !empty($data['message'][$lang]) ? nv_br2nl($data['message'][$lang]) : '',
            'checked' => $lang == $data['isdef'] ? true : false
        ];

        $links[] = [
            'lang' => $lang,
            'langname' => $language_array[$lang]['name'],
            'content' => !empty($data['link'][$lang]) ? $data['link'][$lang] : '',
        ];
    }

    $stpl = new \NukeViet\Template\NVSmarty();
    $stpl->setTemplateDir(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__) . '/smarty');
    $stpl->assign('LANG', $nv_Lang);
    $stpl->assign('MANAGER_PAGE_URL', $page_url);
    $stpl->assign('DATA', $data);
    $stpl->assign('MESSS', $messs);
    $stpl->assign('LINKS', $links);
    $stpl->assign('CHECKSS', $checkss);

    return $stpl->fetch('notification_action.tpl');
}
