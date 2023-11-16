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

if (!$myZalo->isValid()) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=settings');
}

$oa_info = get_oa_info();

$page_title = $nv_Lang->getModule('followers');

// Gui tin nhan
if ($nv_Request->isset_request('send_text,user_id,message_id,chat_text', 'post')) {
    $attachment_type = $nv_Request->get_title('attachment_type', 'post', 'plaintext');

    if ($attachment_type == 'request') {
        if (empty($oa_info['is_verified'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('feature_for_verified_OA')
            ]);
        }
    }

    $user_id = $nv_Request->get_string('user_id', 'post', '');
    $message_id = $nv_Request->get_string('message_id', 'post', '');

    if (empty($message_id)) {
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
    } else {
        if (!messExists($message_id, 1)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('message_id_not_found')
            ]);
        }

        if ($attachment_type == 'request') {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('feature_not_to_reply_message')
            ]);
        }
    }

    $chat_text = $nv_Request->get_title('chat_text', 'post', '');
    $attachment = $nv_Request->get_title('attachment', 'post', '');

    if (in_array($attachment_type, ['plaintext', 'site', 'internet', 'zalo'], true)) {
        if (empty($chat_text)) {
            $mess = in_array($attachment_type, ['site', 'internet', 'zalo'], true) ? $nv_Lang->getModule('description_for_photo_empty') : $nv_Lang->getModule('description_for_photo_empty');
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $mess
            ]);
        }
    }

    $is_localhost = is_localhost();

    if ($attachment_type == 'site' and $is_localhost) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('image_from_localhost')
        ]);
    }

    if (in_array($attachment_type, ['site', 'internet', 'zalo', 'file', 'request'], true)) {
        if (empty($attachment)) {
            $mess = $nv_Lang->getModule('attachment_empty');
            if ($attachment_type == 'request') {
                $mess = $nv_Lang->getModule('request_not_selected');
            }
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $mess
            ]);
        }
    }

    if ($attachment_type == 'internet' and !nv_is_url($attachment)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('image_is_invalid')
        ]);
    }

    if ($attachment_type == 'site' and nv_is_file($attachment, NV_UPLOADS_DIR) !== true) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('attachment_empty')
        ]);
    }

    if (in_array($attachment_type, ['site', 'internet'], true)) {
        if ($attachment_type == 'site') {
            $imginfo = @getimagesize(NV_ROOTDIR . $attachment);
            $size = filesize(NV_ROOTDIR . $attachment);
        } else {
            $data = file_get_contents($attachment);
            $imginfo = @getimagesizefromstring($data);
            $size = strlen($data);
        }

        $error = get_error_zalo_image($imginfo['mime'], $size);

        if (!empty($error)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $error
            ]);
        }
    }

    if ($attachment_type == 'zalo' or $attachment_type == 'file') {
        $zalo_id = get_zalo_id_by_id((int) $attachment);
        if (empty($zalo_id)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('attachment_empty')
            ]);
        }
    }

    if ($attachment_type == 'request') {
        $request_info = template_getinfo((int) $attachment);
        unset($request_info['type']);
        if (empty($request_info)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('request_not_selected')
            ]);
        }

        if (!nv_is_url($request_info['image_url'])) {
            if ($is_localhost) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('image_from_localhost')
                ]);
            }

            if (file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/zalo/' . $request_info['image_url'])) {
                $request_info['image_url'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/zalo/' . $request_info['image_url'];
            } else {
                $request_info['image_url'] = NV_MY_DOMAIN . $request_info['image_url'];
            }
        }
    }

    if ($attachment_type == 'site') {
        $attachment = NV_MY_DOMAIN . preg_replace('/\s/', '%20', $attachment);
    }

    get_accesstoken($accesstoken, true);

    // Neu dinh kem hinh tu site hoac internet
    if (in_array($attachment_type, ['site', 'internet'], true)) {
        $result = $myZalo->send_sitephoto($accesstoken, $user_id, $message_id, $chat_text, $attachment);
        $note = [
            'send_type' => $attachment_type,
            'type' => 'photo',
            'description' => $chat_text,
            'url' => $attachment
        ];
    }
    // Neu dinh kem hinh tu zalo
    elseif ($attachment_type == 'zalo') {
        $result = $myZalo->send_zaloimage($accesstoken, $user_id, $message_id, $chat_text, $zalo_id);
        $note = [
            'send_type' => $attachment_type,
            'type' => 'photo',
            'description' => $chat_text,
            'upload_id' => (int) $attachment
        ];
    }
    // Neu dinh kem file tu zalo
    elseif ($attachment_type == 'file') {
        $result = $myZalo->send_zalofile($accesstoken, $user_id, $message_id, $zalo_id);
        $note = [
            'send_type' => $attachment_type,
            'type' => 'file',
            'upload_id' => (int) $attachment
        ];
    }
    // Neu gui yeu cau cung cap thong tin
    elseif ($attachment_type == 'request') {
        $result = $myZalo->send_request_user_info($accesstoken, $user_id, $message_id, $request_info);
        $note = [
            'send_type' => $attachment_type,
            'type' => 'request',
            'request_info' => $request_info
        ];
    }
    // Neu dang van ban thuan
    elseif ($attachment_type == 'plaintext') {
        $result = $myZalo->send_text($accesstoken, $user_id, $message_id, $chat_text);
        $note = [
            'send_type' => $attachment_type,
            'type' => 'text',
            'message' => $chat_text
        ];
    }

    if (empty($result)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => zaloGetError()
        ]);
    }

    save_conversation($user_id, $result['data']['message_id'], json_encode($note, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

    $contents = [];
    $result = $myZalo->conversation($accesstoken, $user_id, 0, 10);
    if (empty($result)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => zaloGetError()
        ]);
    }

    $count = count($result['data']);

    if ($count) {
        foreach ($result['data'] as $new) {
            $contents[$new['message_id']] = [
                'message_id' => $new['message_id'],
                'src' => !empty($new['src']) ? 1 : 0,
                'time' => !empty($new['time']) ? floor($new['time'] / 1000) : 0,
                'type' => !empty($new['type']) ? $new['type'] : 'nosupport',
                'message' => !empty($new['message']) ? $new['message'] : '',
                'links' => !empty($new['links']) ? json_encode($new['links']) : '',
                'thumb' => !empty($new['thumb']) ? $new['thumb'] : '',
                'url' => !empty($new['url']) ? $new['url'] : '',
                'description' => !empty($new['description']) ? $new['description'] : '',
                'location' => !empty($new['location']) ? $new['location'] : ''
            ];
        }
    }

    if (empty($contents)) {
        nv_jsonOutput([
            'status' => 'success',
            'mess' => $nv_Lang->getModule('empty')
        ]);
    }

    save_last_conversation($contents, $user_id);
    $newContents = get_conversation($user_id);
    $contents = array_merge($contents, $newContents['contents']);
    $contents = conversation_to_html($contents, $user_id);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => $contents
    ]);
}

// Gỡ nhãn khỏi người quan tâm
if ($nv_Request->isset_request('remove_ftag,user_id,tag_alias', 'post')) {
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

    $tag_alias = $nv_Request->get_title('tag_alias', 'post', '');
    $ftags = get_follower_tags($user_id); //Cac tag cua follower
    if (empty($tag_alias) or !in_array($tag_alias, $ftags, true)) {
        nv_jsonOutput([
            'status' => 'success',
            'mess' => ''
        ]);
    }

    get_accesstoken($accesstoken, true);

    $result = $myZalo->rmfollowerfromtag($accesstoken, $user_id, $tag_alias);
    if (empty($result)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => zaloGetError()
        ]);
    }

    $follower_profile = $myZalo->get_follower_profile($accesstoken, $user_id);
    if (empty($follower_profile)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => zaloGetError()
        ]);
    }
    follower_profile_save($user_id, $follower_profile['data']);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

// Gán nhãn cho người quan tâm
if ($nv_Request->isset_request('add_follower_tag,user_id', 'post')) {
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

    $tags = get_tags(); // Danh sach cac tag
    $ftags = get_follower_tags($user_id); //Cac tag cua follower
    $add_tag = $nv_Request->get_title('add_tag', 'post', '');
    $add_newtag = $nv_Request->get_title('add_newtag', 'post', '');

    $post_tag = '';
    $new_tag = [];
    if (!empty($add_tag) and !empty($tags[$add_tag])) {
        $post_tag = $add_tag;
    } elseif (!empty($add_newtag)) {
        $alias = strtolower(change_alias($add_newtag));
        if (strlen($alias) >= 3) {
            if (in_array($alias, $ftags, true)) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('tag_exists')
                ]);
            }

            $post_tag = $alias;
            $new_tag = [
                'alias' => $alias,
                'name' => $add_newtag
            ];
        }
    }

    if (empty($post_tag)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('tag_empty')
        ]);
    }

    get_accesstoken($accesstoken, true);

    $result = $myZalo->tagfollower($accesstoken, $user_id, $post_tag);
    if (empty($result)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => zaloGetError()
        ]);
    }

    if (!empty($new_tag)) {
        add_tag($new_tag);
    }

    $follower_profile = $myZalo->get_follower_profile($accesstoken, $user_id);
    if (empty($follower_profile)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => zaloGetError()
        ]);
    }
    follower_profile_save($user_id, $follower_profile['data']);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getModule('update_completed')
    ]);
}

// Lưu thông tin người quan tâm được chỉnh sửa vao CSDL va Zalo
if ($nv_Request->isset_request('change_profile,user_id', 'post')) {
    $user_id = $nv_Request->get_string('user_id', 'post', '');
    if (empty($user_id)) {
        info_redirect($nv_Lang->getModule('user_id_not_found'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=followers');
    }

    if (!userExists($user_id)) {
        info_redirect($nv_Lang->getModule('user_id_not_found'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=followers');
    }

    $data = [
        'user_id' => $user_id,
        'name' => $nv_Request->get_string('name', 'post', ''),
        'phone_code' => $nv_Request->get_string('phone_code', 'post', ''),
        'phone_number' => $nv_Request->get_string('phone_number', 'post', ''),
        'address' => $nv_Request->get_string('address', 'post', ''),
        'city_id' => '',
        'district_id' => ''
    ];

    require_once NV_ROOTDIR . '/' . NV_DATADIR . '/callingcodes.php';
    if (!empty($data['phone_code']) and !isset($callingcodes[$data['phone_code']])) {
        $data['phone_code'] = '';
    }
    !empty($data['phone_number']) && $data['phone_number'] = preg_replace('/[^0-9]/', '', $data['phone_number']);

    $_city_id = $nv_Request->get_string('city_id', 'post', '');
    $_district_id = $nv_Request->get_string('district_id', 'post', '');

    require_once NV_ROOTDIR . '/' . NV_DATADIR . '/vnsubdivisions.php';
    if (!empty($_city_id) and !empty($provinces[$_city_id])) {
        $data['city_id'] = (int) $_city_id;

        if (!empty($_district_id) and !empty($districts[$_city_id][$_district_id])) {
            $data['district_id'] = (int) $_district_id;
        }
    }

    foreach ($data as $key => $val) {
        if (empty($val)) {
            info_redirect($nv_Lang->getModule('error_not_declared', $nv_Lang->getModule($key)), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=followers&user_id=' . $data['user_id'] . '&action=edit_fi');
        }
    }
    $data['phone'] = substr($data['phone_code'], 2) . $data['phone_number'];
    unset($data['phone_code'], $data['phone_number']);
    get_accesstoken($accesstoken);
    $result = $myZalo->updatefollowerinfo($accesstoken, $data);
    if (empty($result)) {
        info_redirect(zaloGetError(), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=followers&user_id=' . $data['user_id'] . '&action=edit_fi');
    }
    $follower_profile = $myZalo->get_follower_profile($accesstoken, $user_id);
    if (empty($follower_profile)) {
        info_redirect(zaloGetError(), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=followers&user_id=' . $data['user_id'] . '&action=edit_fi');
    }

    follower_profile_save($user_id, $follower_profile['data']);

    info_redirect($nv_Lang->getModule('update_completed'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=followers&user_id=' . $data['user_id'] . '&action=edit_fi');
}

// Lay thong tin nguoi quan tam tu Zalo
if ($nv_Request->isset_request('get_follower_profile,user_id', 'post')) {
    $user_id = $nv_Request->get_string('user_id', 'post', '');
    if (empty($user_id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('user_id_not_found')
        ]);
    }

    $query = 'SELECT * FROM ' . NV_MOD_TABLE . '_followers WHERE user_id=' . $db->quote($user_id);
    $row = $db->query($query)->fetch();
    if (!$row) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('user_id_not_found')
        ]);
    }

    get_accesstoken($accesstoken);
    $follower_profile = $myZalo->get_follower_profile($accesstoken, $user_id);
    if (empty($follower_profile)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => zaloGetError()
        ]);
    }

    follower_profile_save($user_id, $follower_profile['data']);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

// Tu dong lay thong tin nguoi quan tam tu Zalo
if ($nv_Request->isset_request('getfollowersProfile', 'get')) {
    $not_sync = get_followers_not_sync(10);
    if (empty($not_sync)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=followers');
    }

    foreach ($not_sync as $user_id) {
        get_accesstoken($accesstoken);
        $follower_profile = $myZalo->get_follower_profile($accesstoken, $user_id);
        if (empty($follower_profile)) {
            $contents = zaloGetError();
            include NV_ROOTDIR . '/includes/header.php';
            echo nv_admin_theme($contents);
            include NV_ROOTDIR . '/includes/footer.php';
        }

        follower_profile_save($user_id, $follower_profile['data']);
    }

    info_redirect($nv_Lang->getModule('wait_update_info'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=followers&getfollowersProfile=1');
}

// Lay danh sach followers tu zalo
if ($nv_Request->isset_request('getfollowers', 'get')) {
    get_accesstoken($accesstoken);
    $offset = $nv_Request->get_int('offset', 'get', 0);

    $data = [
        'offset' => $offset,
        'count' => 50,
        'tag_name' => ''
    ];
    $result = $myZalo->get_followers($accesstoken, $data);
    if (empty($result)) {
        $contents = zaloGetError();
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents);
        include NV_ROOTDIR . '/includes/footer.php';
    }

    $total = (int) $result['data']['total'];

    $values = [];
    if (!empty($result['data']['followers'])) {
        if (empty($offset)) {
            $db->query('UPDATE ' . NV_MOD_TABLE . '_followers SET isfollow=0 WHERE app_id=' . $db->quote($global_config['zaloAppID']));
        }
        foreach ($result['data']['followers'] as $follower) {
            ++$offset;
            $values[] = '(' . $db->quote($follower['user_id']) . ', ' . $db->quote($global_config['zaloAppID']) . ", '', '',  1, " . $offset . ', 0, ' . NV_CURRENTTIME . ')';
        }
    }
    if (!empty($values)) {
        $values = implode(', ', $values);
        $sql = 'INSERT INTO ' . NV_MOD_TABLE . '_followers (user_id, app_id, tags_info, notes_info, isfollow, weight, is_sync, updatetime) VALUES ' . $values . ' ON DUPLICATE KEY UPDATE app_id=VALUES(app_id), isfollow=1, weight=VALUES(weight)';
        $db->query($sql);
    }

    if ($offset < $total) {
        info_redirect($nv_Lang->getModule('wait_update_info'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=followers&getfollowers=1&offset=' . $offset);
    }

    $not_sync = get_followers_not_sync(1);
    if (!empty($not_sync)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=followers&getfollowersProfile=1');
    } else {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=followers');
    }
}

// Thông tin chi tiết của follower
if ($nv_Request->isset_request('user_id', 'get')) {
    $user_id = $nv_Request->get_string('user_id', 'get', '');
    if (empty($user_id)) {
        info_redirect($nv_Lang->getModule('user_id_not_found'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=followers');
    }

    $user_id = preg_replace('/[^0-9]/', '', $user_id);

    $row = get_follower_info($user_id);
    if (!$row) {
        info_redirect($nv_Lang->getModule('user_id_not_found'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=followers');
    }

    $xtpl = new XTemplate('followers.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=followers');
    $xtpl->assign('TAG_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=followers');
    $xtpl->assign('CONVERSATION_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=conversation');
    $xtpl->assign('LOCATION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=followers&amp;user_id=' . $user_id);
    $xtpl->assign('POPUP_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload&amp;type=image&amp;popup=1&amp;idfield=attachment&amp;textfield=chat_text');
    $xtpl->assign('POPUP_FILE_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload&amp;type=file&amp;popup=1&amp;idfield=attachment&amp;textfield=chat_text&amp;clfield=chat_submit');
    $xtpl->assign('POPUP_REQUEST_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=templates&amp;type=request&amp;popup=1&amp;idfield=attachment&amp;clfield=chat_submit');
    $xtpl->assign('POPUP_PLAINTEXT_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=templates&amp;type=plaintext&amp;popup=1&amp;idfield=chat_text');

    require_once NV_ROOTDIR . '/' . NV_DATADIR . '/callingcodes.php';
    require_once NV_ROOTDIR . '/' . NV_DATADIR . '/vnsubdivisions.php';
    $tags = get_tags();

    foreach ($row as $key => $val) {
        if (!in_array($key, ['app_id', 'isfollow', 'weight', 'is_sync'], true)) {
            if ($key != 'is_sensitive' && empty($val)) {
                continue;
            }
            $key == 'user_gender' && $val = (in_array((int) $val, [1,2], true) ? $nv_Lang->getModule('user_gender_' . $val) : '');
            $key == 'is_sensitive' && $val = $nv_Lang->getModule('is_sensitive_' . $val);
            $key == 'updatetime' && $val = nv_date('d/m/Y H:i', $val);
            $key == 'user_id_by_app' && $key = $nv_Lang->getModule($key) . '<br/>' . $row['app_id'];
            $key == 'phone_code' && $val = $callingcodes[$val][1] . ' +' . $callingcodes[$val][0];
            $key == 'city_id' && $val = $provinces[$val][0];
            $key == 'district_id' && !empty($row['city_id']) && $val = $districts[$row['city_id']][$val][0];
            $key == 'tags_info' && $val = implode(', ', array_map(function ($v) {
                global $tags;

                return $tags[trim($v)];
            }, explode(',', $val)));

            $xtpl->assign('FOLLOWER', [
                'key' => !empty($nv_Lang->getModule($key)) ? $nv_Lang->getModule($key) : $key,
                'val' => $val
            ]);

            if ($key == 'user_id') {
                $xtpl->parse('follower_profile.follower.id');
            } elseif ($key == 'avatar120' and !empty($val)) {
                $xtpl->parse('follower_profile.follower.avatar120');
            } elseif ($key == 'avatar240' and !empty($val)) {
                $xtpl->parse('follower_profile.follower.avatar240');
            } else {
                $xtpl->parse('follower_profile.follower.normal');
            }
            $xtpl->parse('follower_profile.follower');
        }
    }

    $edit_info = [
        'user_id' => $user_id,
        'name' => !empty($row['name']) ? $row['name'] : '',
        'phone_code' => !empty($row['phone_code']) ? $row['phone_code'] : '',
        'phone_number' => !empty($row['phone_number']) ? $row['phone_number'] : '',
        'address' => !empty($row['address']) ? $row['address'] : '',
        'city_id' => !empty($row['city_id']) ? $row['city_id'] : '',
        'district_id' => !empty($row['district_id']) ? $row['district_id'] : ''
    ];
    $xtpl->assign('OTHER', $edit_info);

    $isSel = false;
    foreach ($callingcodes as $code => $vals) {
        $sel = '';
        if (!empty($edit_info['phone_code']) and $edit_info['phone_code'] == $code) {
            $sel = ' selected="selected"';
            $isSel = true;
        }
        if (!$isSel and $client_info['country'] != 'ZZ' and $client_info['country'] == $vals[1]) {
            $sel = ' selected="selected"';
            $isSel = true;
        }
        if (!$isSel and $vals[1] == 'VN') {
            $sel = ' selected="selected"';
            $isSel = true;
        }
        $xtpl->assign('PHONE_CODE', [
            'key' => $code,
            'sel' => $sel,
            'name' => $vals[1] . ' +' . $vals[0]
        ]);
        $xtpl->parse('follower_profile.phone_code');
    }

    foreach ($provinces as $city_id => $city_name) {
        $xtpl->assign('CITY', [
            'id' => $city_id,
            'sel' => (!empty($edit_info['city_id']) and $city_id == $edit_info['city_id']) ? ' selected="selected"' : '',
            'name' => $city_name[0]
        ]);
        $xtpl->parse('follower_profile.city_id');
    }

    if (!empty($edit_info['city_id']) and !empty($districts[$edit_info['city_id']])) {
        foreach ($districts[$edit_info['city_id']] as $district_id => $district_name) {
            $xtpl->assign('DISTRICT', [
                'id' => $district_id,
                'sel' => (!empty($edit_info['district_id']) and $district_id == $edit_info['district_id']) ? ' selected="selected"' : '',
                'name' => $district_name[0]
            ]);
            $xtpl->parse('follower_profile.district_id');
        }
    }

    $ftags = !empty($row['tags_info']) ? array_map('trim', explode(',', $row['tags_info'])) : []; //Cac tag cua follower

    if (empty($ftags)) {
        $xtpl->parse('follower_profile.no_tags_assigned');
    }

    if (!empty($tags)) {
        if (!empty($ftags)) {
            foreach ($ftags as $alias) {
                $xtpl->assign('FOLLOWER_TAG', [
                    'alias' => $alias,
                    'name' => $tags[$alias]
                ]);
                $xtpl->parse('follower_profile.follower_tag');
            }
        }

        foreach ($tags as $alias => $tag) {
            if (empty($ftags) or !in_array($alias, $ftags, true)) {
                $xtpl->assign('TAG', [
                    'alias' => $alias,
                    'name' => $tag
                ]);
                $xtpl->parse('follower_profile.tag_list');
            }
        }
    }

    $action = $nv_Request->get_string('action', 'get', '');
    if (!empty($action) and in_array($action, ['edit_fi', 'edit_ftags', 'last_conversation'], true)) {
        $xtpl->assign('ACTION', $action);
        $xtpl->parse('follower_profile.action');
    }

    if (!empty($oa_info['is_verified'])) {
        $xtpl->parse('follower_profile.for_verified_OA');
    }

    $xtpl->parse('follower_profile');
    $contents = $xtpl->text('follower_profile');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=followers';

$tags = get_tags(); // Danh sach cac tag
$tag = '';
$_tag = $nv_Request->get_title('tag', 'get', '');
if (!empty($_tag) and !empty($tags) and !empty($tags[$_tag])) {
    $tag = $_tag;
    $base_url .= '&amp;tag=' . $tag;
}

$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 50;

// Lấy danh sách followers từ CSDL
if (empty($tag)) {
    $db->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_MOD_TABLE . '_followers')
        ->where('isfollow=1 AND app_id=' . $db->quote($global_config['zaloAppID']));
    $followers_count = $db->query($db->sql())
        ->fetchColumn();

    $db->select('*')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page)
        ->order('weight ASC');
} else {
    $db->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_MOD_TABLE . '_followers tb1, ' . NV_MOD_TABLE . '_tags_follower tb2')
        ->where('tb1.isfollow=1 AND tb1.app_id=' . $db->quote($global_config['zaloAppID']) . ' AND tb2.user_id=tb1.user_id AND tb2.tag=' . $db->quote($tag));
    $followers_count = $db->query($db->sql())
        ->fetchColumn();

    $db->select('tb1.*')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page)
        ->order('tb1.weight ASC');
}

$result = $db->query($db->sql());

$followers = [];
while ($row = $result->fetch()) {
    $followers[$row['user_id']] = $row;
}

$generate_page = nv_generate_page($base_url, $followers_count, $per_page, $page);

$xtpl = new XTemplate('followers.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('GETFOLLOWERS_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=followers&amp;getfollowers=1');
$xtpl->assign('UNFOLLOWERS_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=unfollowers');
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=followers');

if (!empty($tags)) {
    foreach ($tags as $alias => $name) {
        $xtpl->assign('TAG', [
            'alias' => $alias,
            'name' => $name,
            'sel' => (!empty($tag) and $alias == $tag) ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.isTags.tag');
    }
    $xtpl->parse('main.isTags');
}

if ($followers_count) {
    foreach ($followers as $follower) {
        $follower['user_gender'] = (in_array((int) $follower['user_gender'], [1,2], true) ? $nv_Lang->getModule('user_gender_' . $follower['user_gender']) : '');
        $follower['updatetime_format'] = nv_date('d/m/Y H:i', $follower['updatetime']);
        $xtpl->assign('FOLLOWER', $follower);
        $xtpl->parse('main.isFollowers.follower');
    }
    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.isFollowers.generate_page');
    }
    $xtpl->parse('main.isFollowers');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
