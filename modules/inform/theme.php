<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
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
    global $lang_global, $lang_module, $module_info, $module_name;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('PAGE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);

    $filters = ['unviewed' => $lang_module['filter_unviewed'], 'favorite' => $lang_module['filter_favorite'], 'hidden' => $lang_module['filter_hidden']];
    foreach ($filters as $key => $title) {
        $xtpl->assign('FILTER', [
            'key' => $key,
            'title' => $title
        ]);
        $xtpl->parse('main.filter');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * @param array $items mảng các thông báo
 * @param string $generate_page phân trang
 * @param string $filter kiểu list: tất cả, chưa đọc, yêu thích
 * @param string $page_url link trang
 * @return string
 */
function user_getlist_theme($items, $generate_page, $filter, $page_url)
{
    global $global_config, $lang_global, $lang_module, $module_info;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('PAGE_URL', nv_url_rewrite($page_url, true));

    if (!empty($items)) {
        foreach ($items as $item) {
            if (!empty($item['message'])) {

                if ($item['sender_avatar'] == 'group') {
                    $xtpl->parse('user_get_list.main_cont.loop.sender_group');
                } elseif ($item['sender_avatar'] == 'admin') {
                    $xtpl->parse('user_get_list.main_cont.loop.sender_admin');
                } else {
                    $xtpl->parse('user_get_list.main_cont.loop.sender_system');
                }

                $item['title'] = sprintf($lang_module['notification_title'], $item['title']);
                $item['is_hidden'] = $filter == 'hidden' ? 1 : 0;
                $item['is_viewed'] = !empty($item['viewed_time']) ? 1 : 0;
                $item['is_favorite'] = !empty($item['favorite_time']) ? 1 : 0;
                $item['add_time'] = nv_date('d.m.Y H:i', $item['add_time']);
                if (!empty($item['link']) and !preg_match('#^https?\:\/\/#', $item['link'])) {
                    $item['link'] = nv_url_rewrite(NV_BASE_SITEURL . $item['link'], true);
                }

                $xtpl->assign('LOOP', $item);

                if (!empty($item['message'][1])) {
                    $xtpl->parse('user_get_list.main_cont.loop.message_1');
                }

                if (!empty($item['link'])) {
                    $xtpl->parse('user_get_list.main_cont.loop.is_link');
                }

                if ($filter == 'hidden') {
                    $xtpl->parse('user_get_list.main_cont.loop.set_unhidden');
                } else {
                    $xtpl->parse('user_get_list.main_cont.loop.set_hidden');

                    if (empty($item['viewed_time'])) {
                        $xtpl->parse('user_get_list.main_cont.loop.set_viewed');
                    } else {
                        $xtpl->parse('user_get_list.main_cont.loop.set_unviewed');
                    }

                    if (empty($item['favorite_time'])) {
                        $xtpl->parse('user_get_list.main_cont.loop.set_favorite');
                    } else {
                        $xtpl->parse('user_get_list.main_cont.loop.set_unfavorite');
                    }
                }
                $xtpl->parse('user_get_list.main_cont.loop');
            }
        }

        $xtpl->parse('user_get_list.main_cont');
    } else {
        $xtpl->parse('user_get_list.main_empty');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('user_get_list.generate_page');
    }
    $xtpl->parse('user_get_list');

    return $xtpl->text('user_get_list');
}

function getlist_theme($items, $generate_page, $group_id, $members)
{
    global $lang_global, $lang_module, $module_info;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    if (!empty($items)) {
        foreach ($items as $item) {
            $xtpl->assign('ITEM', $item);

            if ($item['status'] == 'waiting') {
                $xtpl->parse('notifications_list.loop.waiting');
            } elseif ($item['status'] == 'expired') {
                $xtpl->parse('notifications_list.loop.expired');
            } else {
                $xtpl->parse('notifications_list.loop.active');
            }

            if (empty($item['receiver_ids'])) {
                $xtpl->parse('notifications_list.loop.to_all');
            } else {
                foreach ($item['receiver_ids'] as $mid) {
                    $xtpl->assign('MEMBER', $members[$mid]);
                    $xtpl->parse('notifications_list.loop.to_member');
                }
            }

            if (!empty($item['message'][1])) {
                $xtpl->parse('notifications_list.loop.message_1');
            }

            if (!empty($item['link'])) {
                $xtpl->parse('notifications_list.loop.link');
            }

            $xtpl->parse('notifications_list.loop');
        }

        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('notifications_list.generate_page');
        }
    }

    $xtpl->parse('notifications_list');

    return $xtpl->text('notifications_list');
}

function notifications_manager_theme($contents, $page_url, $filter, $checkss)
{
    global $lang_global, $lang_module, $module_info;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('PAGE_CONTENT', $contents);
    $xtpl->assign('MANAGER_PAGE_URL', $page_url);
    $xtpl->assign('CHECKSS', $checkss);

    $filters = [
        'active' => $lang_module['active'],
        'waiting' => $lang_module['waiting'],
        'expired' => $lang_module['expired'],
        '' => $lang_module['filter_all']
    ];

    foreach ($filters as $key => $name) {
        $xtpl->assign('FILTER', [
            'key' => $key,
            'sel' => $key == $filter ? ' selected="selected"' : '',
            'name' => $name
        ]);
        $xtpl->parse('notifications_manager.filter');
    }

    $xtpl->parse('notifications_manager');

    return $xtpl->text('notifications_manager');
}

function notification_action_theme($data, $page_url, $checkss)
{
    global $global_config, $language_array, $lang_global, $lang_module, $module_info;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('MANAGER_PAGE_URL', $page_url);
    $xtpl->assign('DATA', $data);
    $xtpl->assign('CHECKSS', $checkss);

    if (!empty($data['receiver_ids'])) {
        foreach ($data['receiver_ids'] as $id => $fullname) {
            $xtpl->assign('MEMBER', [
                'id' => $id,
                'fullname' => $fullname
            ]);
            $xtpl->parse('notification_action.receiver_ids');
        }
    }

    foreach ($global_config['setup_langs'] as $lang) {
        $xtpl->assign('MESS', [
            'lang' => $lang,
            'langname' => $language_array[$lang]['name'],
            'content' => !empty($data['message'][$lang]) ? nv_br2nl($data['message'][$lang]) : '',
            'checked' => $lang == $data['isdef'] ? ' checked="checked"' : ''
        ]);
        $xtpl->parse('notification_action.message');

        $xtpl->assign('LINK', [
            'lang' => $lang,
            'langname' => $language_array[$lang]['name'],
            'content' => !empty($data['link'][$lang]) ? $data['link'][$lang] : '',
        ]);
        $xtpl->parse('notification_action.link');
    }

    for ($i = 0; $i < 24; ++$i) {
        $xtpl->assign('ADD_HOUR', [
            'val' => $i,
            'sel' => $i == $data['add_hour'] ? ' selected="selected"' : '',
            'name' => str_pad($i, 2, '0', STR_PAD_LEFT)
        ]);
        $xtpl->parse('notification_action.add_hour');

        $xtpl->assign('EXP_HOUR', [
            'val' => $i,
            'sel' => $i == $data['exp_hour'] ? ' selected="selected"' : '',
            'name' => str_pad($i, 2, '0', STR_PAD_LEFT)
        ]);
        $xtpl->parse('notification_action.exp_hour');
    }

    for ($i = 0; $i < 60; ++$i) {
        $xtpl->assign('ADD_MIN', [
            'val' => $i,
            'sel' => $i == $data['add_min'] ? ' selected="selected"' : '',
            'name' => str_pad($i, 2, '0', STR_PAD_LEFT)
        ]);
        $xtpl->parse('notification_action.add_min');

        $xtpl->assign('EXP_MIN', [
            'val' => $i,
            'sel' => $i == $data['exp_min'] ? ' selected="selected"' : '',
            'name' => str_pad($i, 2, '0', STR_PAD_LEFT)
        ]);
        $xtpl->parse('notification_action.exp_min');
    }

    $xtpl->parse('notification_action');

    return $xtpl->text('notification_action');
}
