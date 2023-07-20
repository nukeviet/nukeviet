<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ZALO')) {
    exit('Stop!!!');
}

if (!$zalo->isValid()) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=settings');
}

// Play file am thanh
if ($nv_Request->isset_request('player,url', 'get')) {
    $url = $nv_Request->get_string('url', 'get', '');
    $data = file_get_contents($url);
    $md5file = md5($url);
    header('Content-Type: audio/AMR');
    header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
    header('Pragma: public');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() - 3600) . ' GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Content-Disposition: inline; filename="' . $md5file . '.amr";');
    header('access-control-allow-origin: *');
    header('Vary: Accept-Encoding');
    if (empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
        // the content length may vary if the server is using compression
        header('Content-Length: ' . strlen($data));
    }
    echo $data;
    exit;
}

// Cap nhat hoi thoai moi
if ($nv_Request->isset_request('conversation_refresh,user_id', 'post')) {
    $user_id = $nv_Request->get_string('user_id', 'post', '');
    if (empty($user_id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('user_id_not_found')
        ]);
    }

    if (!userExists($user_id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('user_id_not_found')
        ]);
    }

    $offset = 0;
    get_accesstoken($accesstoken, true);
    for ($i = 0; $i < 5; ++$i) {
        $contents = [];
        $result = $zalo->conversation($accesstoken, $user_id, $offset, 10);
        if (empty($result)) {
            break;
            nv_jsonOutput([
                'status' => 'error',
                'mess' => zaloGetError()
            ]);
        }

        $count = count($result['data']);
        if (empty($count)) {
            break;
        }

        if ($count) {
            foreach ($result['data'] as $new) {
                $contents[$new['message_id']] = [
                    'message_id' => $new['message_id'],
                    'src' => !empty($new['src']) ? 1 : 0,
                    'time' => !empty($new['time']) ? floor($new['time'] / 1000) : 0,
                    'type' => !empty($new['type']) ? $new['type'] : 'nosupport',
                    'message' => !empty($new['message']) ? nv_nl2br(str_replace(["'", '"', '<', '>'], ['&#039;', '&quot;', '&lt;', '&gt;'], $new['message']), '<br/>') : '',
                    'links' => !empty($new['links']) ? json_encode($new['links']) : '',
                    'thumb' => !empty($new['thumb']) ? $new['thumb'] : '',
                    'url' => !empty($new['url']) ? $new['url'] : '',
                    'description' => !empty($new['description']) ? nv_nl2br(str_replace(["'", '"', '<', '>'], ['&#039;', '&quot;', '&lt;', '&gt;'], $new['description']), '<br/>') : '',
                    'location' => !empty($new['location']) ? $new['location'] : ''
                ];
            }

            save_last_conversation($contents, $user_id);
        }

        $offset = $offset + $count;
    }

    $contents = get_conversation($user_id);
    $contents = conversation_to_html($contents['contents'], $user_id);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => $contents
    ]);
}

// Lay hoi thoai tu CSDL
if ($nv_Request->isset_request('get_conversation,user_id,refresh', 'post')) {
    $user_id = $nv_Request->get_string('user_id', 'post', '');
    $refresh = $nv_Request->get_bool('refresh', 'post', false);
    if (empty($user_id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('user_id_not_found')
        ]);
    }

    if (!userExists($user_id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('user_id_not_found')
        ]);
    }

    $contents = get_conversation($user_id);
    if ($refresh) {
        if ($contents['updated']) {
            nv_jsonOutput([
                'status' => 'success',
                'mess' => conversation_to_html($contents['contents'], $user_id)
            ]);
        } else {
            nv_jsonOutput([
                'status' => 'notupdated',
                'mess' => ''
            ]);
        }
    } else {
        nv_jsonOutput([
            'status' => 'success',
            'mess' => conversation_to_html($contents['contents'], $user_id)
        ]);
    }
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=followers');
