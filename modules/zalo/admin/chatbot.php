<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ZALO')) {
    exit('Stop!!!');
}

if (!$zalo->isValid()) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=settings');
}

$events = [
    'user_send_location' => ['user_orient_actions'],
    'user_send_image' => ['user_orient_actions'],
    'user_send_link' => ['user_orient_actions'],
    'user_send_text' => ['user_orient_actions'],
    'user_send_sticker' => ['user_orient_actions'],
    'user_send_gif' => ['user_orient_actions'],
    'user_send_audio' => ['user_orient_actions'],
    'user_send_video' => ['user_orient_actions'],
    'user_send_file' => ['user_orient_actions'],
    'user_received_message' => [],
    'user_seen_message' => [],
    'user_submit_info' => [],
    'follow' => [],
    'unfollow' => [],
    'add_user_to_tag' => [],
    'shop_has_order' => [],
    'oa_send_text' => [],
    'oa_send_image' => [],
    'oa_send_list' => [],
    'oa_send_gif' => [],
    'oa_send_file' => []
];

$user_orient_actions = [
    'sent_text_message',
    'sent_image_message',
    'sent_file_message',
    'sent_textlist_message',
    'sent_btnlist_message'
];

//change_alias
if ($nv_Request->isset_request('change_alias', 'post')) {
    $text = $nv_Request->get_title('change_alias', 'post', '');
    if (!empty($text)) {
        $text = strtolower(change_alias($text));
        $text = substr($text, 0, 90);
        $finished = '';
        $i = 1;
        while (empty($finished)) {
            $text2 = ($i > 1) ? $text . '-' . $i : $text;
            if (!keyword_is_exists($text2)) {
                $finished = $text2;
            }
            ++$i;
        }
    }

    echo $finished;
    exit();
}

// Luu tu khoa lenh
if ($nv_Request->isset_request('command_keywords,keyword,action,parameter', 'post')) {
    $title = $nv_Request->get_typed_array('title', 'post', 'title', []);
    $keyword = $nv_Request->get_typed_array('keyword', 'post', 'title', []);
    $action = $nv_Request->get_typed_array('action', 'post', 'title', []);
    $parameter = $nv_Request->get_typed_array('parameter', 'post', 'title', []);

    keyword_actions_save($title, $keyword, $action, $parameter);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

// Luu zalo_events
if ($nv_Request->isset_request('zalo_events,action,parameter', 'post')) {
    $action = $nv_Request->get_typed_array('action', 'post', 'title', []);
    $parameter = $nv_Request->get_typed_array('parameter', 'post', 'title', []);

    webhook_actions_save($action, $parameter);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

$event_actions = get_webhook_actions();
$keyword_actions = get_keyword_actions();

$tab = $nv_Request->get_title('tab', 'get', '');
if ($tab != 'command_keywords') {
    $tab = 'zalo_events';
}

$popup = $nv_Request->get_bool('popup', 'get', false);
$idfield = $nv_Request->get_title('idfield', 'get', '');

$xtpl = new XTemplate('chatbot.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('DATA', $global_config);
$xtpl->assign('PAGE_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('TAB_ACTIVE', $tab);
$xtpl->assign('IDFIELD', $idfield);

if (is_localhost() and !$popup) {
    $xtpl->parse('main.chatbot_note');
}

if (!$popup) {
    foreach ($events as $event => $action_types) {
        $xtpl->assign('EVENT', [
            'key' => $event,
            'name' => $nv_Lang->getModule($event),
            'action' => !empty($event_actions[$event][0]) ? $event_actions[$event][0] : '',
            'parameter' => !empty($event_actions[$event][1]) ? $event_actions[$event][1] : ''
        ]);

        if (!empty($action_types) and in_array('user_orient_actions', $action_types, true)) {
            foreach ($user_orient_actions as $action) {
                switch ($action) {
                    case 'sent_text_message':
                        $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=templates&amp;type=plaintext&amp;popup=1&amp;parameter=parameter_' . $event;
                        $view_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=templates&amp;preview=1&amp;id=';
                        break;
                    case 'sent_image_message':
                        $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload&amp;type=image&amp;popup=1&amp;idfield=parameter_' . $event;
                        $view_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload&amp;preview=1&amp;id=';
                        break;
                    case 'sent_file_message':
                        $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload&amp;type=file&amp;popup=1&amp;idfield=parameter_' . $event;
                        $view_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload&amp;preview=1&amp;id=';
                        break;
                    case 'sent_textlist_message':
                        $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=templates&amp;type=textlist&amp;popup=1&amp;idfield=parameter_' . $event;
                        $view_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=templates&amp;preview=1&amp;id=';
                        break;
                    case 'sent_btnlist_message':
                        $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=templates&amp;type=btnlist&amp;popup=1&amp;idfield=parameter_' . $event;
                        $view_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=templates&amp;preview=1&amp;id=';
                        break;
                }

                $xtpl->assign('ACTION', [
                    'key' => $action,
                    'name' => $nv_Lang->getModule('action_' . $action),
                    'sel' => (!empty($event_actions[$event][0]) and $action == $event_actions[$event][0]) ? ' selected="selected"' : '',
                    'url' => $url,
                    'view_url' => $view_url
                ]);
                $xtpl->parse('main.if_not_popup.event.user_orient_actions');
            }
        } else {
            $xtpl->parse('main.if_not_popup.event.readonly');
        }
        $xtpl->parse('main.if_not_popup.event');
    }

    $xtpl->parse('main.if_not_popup');
    $xtpl->parse('main.if_not_popup2');
} else {
    $xtpl->parse('main.if_popup');
    $xtpl->parse('main.if_popup2');
}

$keyword_count = count($keyword_actions);

$keyword_actions[] = ['', '', '', ''];

foreach ($keyword_actions as $i => $action) {
    $xtpl->assign('KEYWORD', [
        'key' => $action[3],
        'title' => $action[2],
        'action' => $action[0],
        'parameter' => $action[1],
        'i' => $i
    ]);

    foreach ($user_orient_actions as $act) {
        switch ($act) {
            case 'sent_text_message':
                $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=templates&amp;type=plaintext&amp;popup=1&amp;parameter=par_' . $i;
                $view_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=templates&amp;preview=1&amp;id=';
                break;
            case 'sent_image_message':
                $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload&amp;type=image&amp;popup=1&amp;idfield=par_' . $i;
                $view_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload&amp;preview=1&amp;id=';
                break;
            case 'sent_file_message':
                $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload&amp;type=file&amp;popup=1&amp;idfield=par_' . $i;
                $view_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload&amp;preview=1&amp;id=';
                break;
            case 'sent_textlist_message':
                $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=templates&amp;type=textlist&amp;popup=1&amp;idfield=par_' . $i;
                $view_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=templates&amp;preview=1&amp;id=';
                break;
            case 'sent_btnlist_message':
                $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=templates&amp;type=btnlist&amp;popup=1&amp;idfield=par_' . $i;
                $view_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=templates&amp;preview=1&amp;id=';
                break;
        }

        $xtpl->assign('ACTION', [
            'key' => $act,
            'name' => $nv_Lang->getModule('action_' . $act),
            'sel' => (!empty($action[0]) and $act == $action[0]) ? ' selected="selected"' : '',
            'url' => $url,
            'view_url' => $view_url
        ]);
        $xtpl->parse('main.keyword.user_orient_actions');
    }

    if ($popup) {
        if (empty($action[0]) or empty($action[1]) or empty($action[3])) {
            $xtpl->parse('main.keyword.if_popup.disabled');
        }
        $xtpl->parse('main.keyword.if_popup');
    }

    if ($i < $keyword_count) {
        $xtpl->parse('main.keyword.remove');
    }

    $xtpl->parse('main.keyword');
}
$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $nv_Lang->getModule('chatbot');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents, !$popup);
include NV_ROOTDIR . '/includes/footer.php';
