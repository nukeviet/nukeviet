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

global $global_config, $db, $db_config;

define('NV_MOD_TABLE', $db_config['prefix'] . '_zalo');

$module_configs = [];
$event_actions = [];
$keyword_actions = [];
$sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_settings';
$result = $db->query($sql);
while ($row = $result->fetch()) {
    if ($row['type'] == 'action') {
        $event_actions[$row['skey']] = json_decode($row['svalue'], true);
    } elseif ($row['type'] == 'keyword') {
        $keyword_actions[$row['skey']] = json_decode($row['svalue'], true);
    } else {
        !isset($module_configs[$row['type']]) && $module_configs[$row['type']] = [];
        $module_configs[$row['type']][$row['skey']] = $row['svalue'];
    }
}

/**
 * Xác định mã gọi + số điện thoại
 * parse_phone()
 *
 * @param mixed $number
 * @return array
 */
function parse_phone($number)
{
    include NV_ROOTDIR . '/' . NV_DATADIR . '/callingcodes.php';

    $number = (string) $number;
    $number = preg_replace('/[^0-9]+/', '', $number);
    if (str_starts_with($number, '0')) {
        $number = '84' . substr($number, 1);
    }
    $digist6 = (int) substr($number, 0, 6);
    if (isset($callingcodes2[$digist6])) {
        return [$callingcodes2[$digist6][0], substr($number, 6)];
    }

    $digist4 = (int) substr($number, 0, 4);
    if (isset($callingcodes2[$digist4])) {
        return [$callingcodes2[$digist4][0], substr($number, 4)];
    }

    $digist3 = (int) substr($number, 0, 3);
    if (isset($callingcodes2[$digist3])) {
        return [$callingcodes2[$digist3][0], substr($number, 3)];
    }

    $digist2 = (int) substr($number, 0, 2);
    if (isset($callingcodes2[$digist2])) {
        return [$callingcodes2[$digist2][0], substr($number, 2)];
    }

    return ['', $number];
}

/**
 * Xác định ID của tỉnh/thành phố
 * get_province_id()
 *
 * @param mixed $name
 * @return mixed
 */
function get_province_id($name)
{
    include NV_ROOTDIR . '/' . NV_DATADIR . '/vnsubdivisions.php';

    foreach ($provinces as $id => $province_name) {
        foreach ($province_name as $_name) {
            if (str_ends_with($_name, $name)) {
                return $id;
            }
        }
    }

    return '';
}

/**
 * Xác định ID của huyện/quận
 * get_district_id()
 *
 * @param mixed $city_id
 * @param mixed $district
 * @return mixed
 */
function get_district_id($city_id, $district)
{
    include NV_ROOTDIR . '/' . NV_DATADIR . '/vnsubdivisions.php';

    foreach ($districts[$city_id] as $id => $district_name) {
        foreach ($district_name as $_district) {
            if (str_ends_with($_district, $district)) {
                return $id;
            }
        }
    }

    return '';
}

/**
 * Tự động xử lý khi có sự kiện mới được gửi tới từ Zalo
 * webhook_handle()
 *
 * @param mixed $data
 * @throws PDOException
 */
function webhook_handle($data)
{
    global $db, $global_config;

    if (!empty($data['message']['msg_id'])) {
        $sth = $db->prepare('INSERT IGNORE INTO ' . NV_MOD_TABLE . "_conversation 
        (message_id, user_id, src, time, type, message, links, thumb, url, description, location, note) VALUES 
        (:message_id, :user_id, :src, :time, :type, :message, :links, :thumb, :url, :description, :location, '')");

        if ($data['sender']['id'] != $global_config['zaloOfficialAccountID']) {
            $src = 1;
            $user_id = $data['sender']['id'];
        } else {
            $src = 0;
            $user_id = $data['recipient']['id'];
        }

        $contents = [
            'message_id' => $data['message']['msg_id'],
            'time' => $data['timestamp'],
            'type' => 'nosupport',
            'message' => !empty($data['message']['text']) ? $data['message']['text'] : '',
            'links' => '',
            'thumb' => '',
            'url' => '',
            'description' => '',
            'location' => ''
        ];

        switch ($data['event_name']) {
            case 'user_send_location':
                $contents['type'] = 'location';
                $contents['location'] = '{"longitude":"' . $data['message']['attachments'][0]['payload']['coordinates']['longitude'] . '","latitude":"' . $data['message']['attachments'][0]['payload']['coordinates']['latitude'] . '"}';
                break;
            case 'user_send_image':
            case 'oa_send_image':
                $contents['type'] = 'photo';
                $contents['thumb'] = $data['message']['attachments'][0]['payload']['thumbnail'];
                $contents['url'] = $data['message']['attachments'][0]['payload']['url'];
                break;
            case 'user_send_link':
                $contents['type'] = 'link';
                $contents['links'] = '[{"title":"","url":"' . $data['message']['attachments'][0]['payload']['url'] . '","thumb":"' . $data['message']['attachments'][0]['payload']['thumbnail'] . '","description":"' . $data['message']['attachments'][0]['payload']['description'] . '"}]';
                break;
            case 'user_send_text':
            case 'oa_send_text':
                $contents['type'] = 'text';
                break;
            case 'user_send_sticker':
                $contents['type'] = 'sticker';
                $contents['url'] = $data['message']['attachments'][0]['payload']['url'];
                break;
            case 'user_send_gif':
            case 'oa_send_gif':
                $contents['type'] = 'gif';
                $contents['thumb'] = $data['message']['attachments'][0]['payload']['thumbnail'];
                $contents['url'] = $data['message']['attachments'][0]['payload']['url'];
                break;
            case 'user_send_audio':
                $contents['type'] = 'voice';
                $contents['url'] = $data['message']['attachments'][0]['payload']['url'];
                break;
            case 'user_send_video':
                $contents['type'] = 'video';
                $contents['thumb'] = $data['message']['attachments'][0]['payload']['thumbnail'];
                $contents['description'] = $data['message']['attachments'][0]['payload']['description'];
                $contents['url'] = $data['message']['attachments'][0]['payload']['url'];
                break;
            case 'user_send_file':
            case 'oa_send_file':
                $contents['type'] = 'file';
                $contents['description'] = $data['message']['attachments'][0]['payload']['name'];
                $contents['url'] = $data['message']['attachments'][0]['payload']['url'];
                break;
            case 'oa_send_list':
                $links = [];
                foreach ($data['message']['attachments'] as $link) {
                    $links[] = $link['payload'];
                }
                $contents['type'] = 'links';
                $contents['links'] = json_encode($links);
                break;
        }

        $sth->bindValue(':message_id', $contents['message_id'], PDO::PARAM_STR);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $sth->bindParam(':src', $src, PDO::PARAM_INT);
        $sth->bindParam(':time', $contents['time'], PDO::PARAM_INT);
        $sth->bindParam(':type', $contents['type'], PDO::PARAM_STR);
        $sth->bindParam(':message', $contents['message'], PDO::PARAM_STR);
        $sth->bindParam(':links', $contents['links'], PDO::PARAM_STR);
        $sth->bindParam(':thumb', $contents['thumb'], PDO::PARAM_STR);
        $sth->bindParam(':url', $contents['url'], PDO::PARAM_STR);
        $sth->bindParam(':description', $contents['description'], PDO::PARAM_STR);
        $sth->bindParam(':location', $contents['location'], PDO::PARAM_STR);
        $sth->execute();
    } elseif ($data['event_name'] == 'follow' or $data['event_name'] == 'unfollow') {
        $isfollow = $data['event_name'] == 'follow' ? 1 : 0;

        $offset = $db->query('SELECT MAX(weight) FROM ' . NV_MOD_TABLE . '_followers')->fetchColumn();
        ++$offset;

        $sth = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . "_followers (user_id, app_id, user_id_by_app, tags_info, notes_info, isfollow, weight, updatetime) VALUES 
        (:user_id, :app_id, :user_id_by_app, '', '', :isfollow, :weight, :updatetime) ON DUPLICATE KEY UPDATE app_id=VALUES(app_id), isfollow=VALUES(isfollow), updatetime=VALUES(updatetime)");

        $sth->bindValue(':user_id', $data['follower']['id'], PDO::PARAM_STR);
        $sth->bindValue(':app_id', $data['app_id'], PDO::PARAM_STR);
        $sth->bindValue(':user_id_by_app', $data['user_id_by_app'], PDO::PARAM_STR);
        $sth->bindValue(':isfollow', $isfollow, PDO::PARAM_INT);
        $sth->bindValue(':weight', $offset, PDO::PARAM_INT);
        $sth->bindValue(':updatetime', $data['timestamp'], PDO::PARAM_INT);
        $sth->execute();
    } elseif ($data['event_name'] == 'user_submit_info') {
        $phone_code = $phone_number = '';
        if (!empty($data['info']['phone'])) {
            [$phone_code, $phone_number] = parse_phone($data['info']['phone']);
        }
        $address = !empty($data['info']['address']) ? $data['info']['address'] : '';
        if (!empty($data['info']['ward'])) {
            !empty($address) && $address .= ', ';
            $address .= $data['info']['ward'];
        }
        $city_id = !empty($data['info']['city']) ? get_province_id($data['info']['city']) : '';
        $district_id = (!empty($city_id) and !empty($data['info']['district'])) ? get_district_id($city_id, $data['info']['district']) : '';
        $name = !empty($data['info']['name']) ? $data['info']['name'] : '';
        $offset = $db->query('SELECT MAX(weight) FROM ' . NV_MOD_TABLE . '_followers')->fetchColumn();
        ++$offset;

        $sth = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . "_followers (user_id, app_id, user_id_by_app, tags_info, notes_info, weight, name, phone_code, phone_number, address, city_id, district_id, is_sync, updatetime) VALUES 
        (:user_id, :app_id, :user_id_by_app, '', '', :weight, :name, :phone_code, :phone_number, :address, :city_id, :district_id, 0, :updatetime) ON DUPLICATE KEY UPDATE 
        app_id=VALUES(app_id), name=VALUES(name), phone_code=VALUES(phone_code), phone_number=VALUES(phone_number), address=VALUES(address), city_id=VALUES(city_id), district_id=VALUES(district_id), updatetime=VALUES(updatetime)");
        $sth->bindValue(':user_id', $data['sender']['id'], PDO::PARAM_STR);
        $sth->bindValue(':app_id', $data['app_id'], PDO::PARAM_STR);
        $sth->bindValue(':user_id_by_app', $data['user_id_by_app'], PDO::PARAM_STR);
        $sth->bindValue(':weight', $offset, PDO::PARAM_INT);
        $sth->bindValue(':name', $name, PDO::PARAM_STR);
        $sth->bindValue(':phone_code', $phone_code, PDO::PARAM_STR);
        $sth->bindValue(':phone_number', $phone_number, PDO::PARAM_STR);
        $sth->bindValue(':address', $address, PDO::PARAM_STR);
        $sth->bindValue(':city_id', $city_id, PDO::PARAM_STR);
        $sth->bindValue(':district_id', $district_id, PDO::PARAM_STR);
        $sth->bindValue(':updatetime', $data['timestamp'], PDO::PARAM_INT);
        $sth->execute();
    }
}

/**
 * Cập nhật Access Token
 * accessTokenUpdate()
 *
 * @param mixed $result
 * @throws PDOException
 */
function accessTokenUpdate($result)
{
    global $db, $nv_Cache;

    $array_config_site = [];
    $array_config_site['zaloOAAccessToken'] = $result['access_token'];
    $array_config_site['zaloOARefreshToken'] = $result['refresh_token'];
    $array_config_site['zaloOAAccessTokenTime'] = NV_CURRENTTIME;

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config_site as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delAll(false);
}

/**
 * Lưu tin nhắn của OA gửi cho người dùng
 * save_conversation()
 *
 * @param mixed $user_id
 * @param mixed $message_id
 * @param mixed $note
 * @throws PDOException
 */
function save_conversation($user_id, $message_id, $note)
{
    global $db;

    if (!empty($message_id)) {
        $sth = $db->prepare('INSERT IGNORE INTO ' . NV_MOD_TABLE . '_conversation 
        (message_id, user_id, src, time, message, links, description, note) VALUES 
        (:message_id, :user_id, 0, ' . NV_CURRENTTIME . ", '', '', '', :note)");

        $sth->bindValue(':message_id', $message_id, PDO::PARAM_STR);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $sth->bindParam(':note', $note, PDO::PARAM_STR);
        $sth->execute();
    }

    $oldtime = $db->query('SELECT time FROM ' . NV_MOD_TABLE . '_conversation WHERE user_id = ' . $db->quote($user_id) . ' ORDER BY time DESC LIMIT 100, 1')->fetchColumn();
    if (!empty($oldtime)) {
        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_conversation WHERE user_id = ' . $db->quote($user_id) . ' AND time <= ' . $oldtime);
    }
}

/**
 * Lấy access token
 * get_accesstoken()
 *
 * @param mixed $accesstoken
 * @throws ValueError
 * @throws PDOException
 */
function get_accesstoken()
{
    global $global_config;

    $myZalo = new NukeViet\Zalo\MyZalo($global_config);

    $get_accesstoken_info = $myZalo->oa_accesstoken_info();
    if ($get_accesstoken_info['result'] == 'ok') {
        return $get_accesstoken_info['access_token'];
    }

    if ($get_accesstoken_info['result'] == 'update') {
        accessTokenUpdate($get_accesstoken_info);

        return $get_accesstoken_info['access_token'];
    }

    return '';
}

/**
 * Cập nhật các hội thoại mới
 * last_conversation_update()
 *
 * @param mixed $accesstoken
 * @param mixed $user_id
 * @throws ValueError
 * @throws PDOException
 */
function last_conversation_update($accesstoken, $user_id)
{
    global $db, $global_config;

    $myZalo = new NukeViet\Zalo\MyZalo($global_config);

    $result = $myZalo->conversation($accesstoken, $user_id, 0, 10);
    if (!empty($result)) {
        $sth = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . "_conversation 
        (message_id, user_id, src, time, type, message, links, thumb, url, description, location, note) VALUES 
        (:message_id, :user_id, :src, :time, :type, :message, :links, :thumb, :url, :description, :location, '') ON DUPLICATE KEY UPDATE 
        time=VALUES(time), type=VALUES(type), message=VALUES(message), links=VALUES(links), thumb=VALUES(thumb), url=VALUES(url), description=VALUES(description), location=VALUES(location)");

        $isUpdated = false;
        foreach ($result['data'] as $new) {
            $content = [
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

            if (!empty($new['message_id'])) {
                $sth->bindValue(':message_id', $new['message_id'], PDO::PARAM_STR);
                $sth->bindParam(':user_id', $user_id, PDO::PARAM_STR);
                $sth->bindParam(':src', $content['src'], PDO::PARAM_INT);
                $sth->bindParam(':time', $content['time'], PDO::PARAM_INT);
                $sth->bindParam(':type', $content['type'], PDO::PARAM_STR);
                $sth->bindParam(':message', $content['message'], PDO::PARAM_STR);
                $sth->bindParam(':links', $content['links'], PDO::PARAM_STR);
                $sth->bindParam(':thumb', $content['thumb'], PDO::PARAM_STR);
                $sth->bindParam(':url', $content['url'], PDO::PARAM_STR);
                $sth->bindParam(':description', $content['description'], PDO::PARAM_STR);
                $sth->bindParam(':location', $content['location'], PDO::PARAM_STR);
                $sth->execute();
                $isUpdated = true;
            }
        }
        if ($isUpdated) {
            $oldtime = $db->query('SELECT time FROM ' . NV_MOD_TABLE . '_conversation WHERE user_id = ' . $db->quote($user_id) . ' ORDER BY time DESC LIMIT 100, 1')->fetchColumn();
            if (!empty($oldtime)) {
                $db->query('DELETE FROM ' . NV_MOD_TABLE . '_conversation WHERE user_id = ' . $db->quote($user_id) . ' AND time <= ' . $oldtime);
            }
        }
    }
}

/**
 * Gửi tin nhắn dạng văn bản
 * sent_text_message()
 *
 * @param mixed $template_id
 * @param mixed $user_id
 * @param mixed $message_id
 * @return false|void
 * @throws ValueError
 * @throws PDOException
 */
function sent_text_message($template_id, $user_id, $message_id)
{
    global $db, $global_config;

    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_template WHERE id=' . $template_id . " AND type='plaintext'";
    $result = $db->query($sql);
    $row = $result->fetch();
    if (!$row) {
        return false;
    }

    $content = json_decode($row['content'], true);
    $content = $content['content'];

    $accesstoken = get_accesstoken();
    if (!empty($accesstoken) and (!empty($user_id) or !empty($message_id))) {
        $myZalo = new NukeViet\Zalo\MyZalo($global_config);
        $result = $myZalo->send_text($accesstoken, $user_id, $message_id, $content);
        if (!empty($result)) {
            save_conversation($user_id, $result['data']['message_id'], json_encode([
                'send_type' => 'plaintext',
                'type' => 'text',
                'message' => $content
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            last_conversation_update($accesstoken, $user_id);
        }
    }
}

/**
 * Gửi hình
 * sent_image_message()
 *
 * @param mixed $fileid
 * @param mixed $user_id
 * @param mixed $message_id
 * @return false|void
 * @throws ValueError
 * @throws PDOException
 */
function sent_image_message($fileid, $user_id, $message_id)
{
    global $db, $global_config;

    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_upload WHERE id=' . $fileid . " AND type='image'";
    $result = $db->query($sql);
    $row = $result->fetch();
    if (!$row) {
        return false;
    }

    $accesstoken = get_accesstoken();
    if (!empty($accesstoken) and (!empty($user_id) or !empty($message_id))) {
        $myZalo = new NukeViet\Zalo\MyZalo($global_config);
        $result = $myZalo->send_zaloimage($accesstoken, $user_id, $message_id, $row['description'], $row['zalo_id']);
        if (!empty($result)) {
            save_conversation($user_id, $result['data']['message_id'], json_encode([
                'send_type' => 'zalo',
                'type' => 'photo',
                'description' => $row['description'],
                'upload_id' => $fileid
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            last_conversation_update($accesstoken, $user_id);
        }
    }
}

/**
 * Gửi file
 * sent_file_message()
 *
 * @param mixed $fileid
 * @param mixed $user_id
 * @param mixed $message_id
 * @return false|void
 * @throws ValueError
 * @throws PDOException
 */
function sent_file_message($fileid, $user_id, $message_id)
{
    global $db, $global_config;

    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_upload WHERE id=' . $fileid . " AND type='file'";
    $result = $db->query($sql);
    $row = $result->fetch();
    if (!$row) {
        return false;
    }

    $accesstoken = get_accesstoken();
    if (!empty($accesstoken) and (!empty($user_id) or !empty($message_id))) {
        $myZalo = new NukeViet\Zalo\MyZalo($global_config);
        $result = $myZalo->send_zalofile($accesstoken, $user_id, $message_id, $row['zalo_id']);
        if (!empty($result)) {
            save_conversation($user_id, $result['data']['message_id'], json_encode([
                'send_type' => 'file',
                'type' => 'file',
                'upload_id' => $fileid
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            last_conversation_update($accesstoken, $user_id);
        }
    }
}

$webhook_data['app_id'] = preg_replace('/[^0-9]+/', '', $webhook_data['app_id']);
!empty($webhook_data['message']['msg_id']) && $webhook_data['message']['msg_id'] = preg_replace('/[^a-zA-Z0-9\_]+/', '', $webhook_data['message']['msg_id']);
!empty($webhook_data['message']['text']) && $webhook_data['message']['text'] = trim(strip_tags($webhook_data['message']['text'], '<br>'));
!empty($webhook_data['message']['attachments'][0]['payload']['id']) && $webhook_data['message']['attachments'][0]['payload']['id'] = preg_replace('/[^a-zA-Z0-9\_]+/', '', $webhook_data['message']['attachments'][0]['payload']['id']);
if (!empty($webhook_data['message']['attachments'][0]['payload']['thumbnail'])) {
    if (!nv_is_url($webhook_data['message']['attachments'][0]['payload']['thumbnail'])) {
        $webhook_data['message']['attachments'][0]['payload']['thumbnail'] = '';
    }
}
if (!empty($webhook_data['message']['attachments'][0]['payload']['url'])) {
    if (!nv_is_url($webhook_data['message']['attachments'][0]['payload']['url'])) {
        $webhook_data['message']['attachments'][0]['payload']['url'] = '';
    }
}
!empty($webhook_data['message']['attachments'][0]['payload']['coordinates']['latitude']) && $webhook_data['message']['attachments'][0]['payload']['coordinates']['latitude'] = preg_replace('/[^0-9\.\+\-]+/', '', $webhook_data['message']['attachments'][0]['payload']['coordinates']['latitude']);
!empty($webhook_data['message']['attachments'][0]['payload']['coordinates']['longitude']) && $webhook_data['message']['attachments'][0]['payload']['coordinates']['longitude'] = preg_replace('/[^0-9\.\+\-]+/', '', $webhook_data['message']['attachments'][0]['payload']['coordinates']['longitude']);
!empty($webhook_data['message']['attachments'][0]['payload']['size']) && $webhook_data['message']['attachments'][0]['payload']['size'] = preg_replace('/[^0-9]+/', '', $webhook_data['message']['attachments'][0]['payload']['size']);
!empty($webhook_data['message']['attachments'][0]['payload']['name']) && $webhook_data['message']['attachments'][0]['payload']['name'] = trim(strip_tags($webhook_data['message']['attachments'][0]['payload']['name']));
!empty($webhook_data['message']['attachments'][0]['payload']['description']) && $webhook_data['message']['attachments'][0]['payload']['description'] = trim(strip_tags($webhook_data['message']['attachments'][0]['payload']['description'], '<br>'));
!empty($webhook_data['info']['address']) && $webhook_data['info']['address'] = trim(strip_tags($webhook_data['info']['address'], '<br>'));
!empty($webhook_data['info']['phone']) && $webhook_data['info']['phone'] = preg_replace('/[^0-9]+/', '', $webhook_data['info']['phone']);
!empty($webhook_data['info']['city']) && $webhook_data['info']['city'] = trim(strip_tags($webhook_data['info']['city']));
!empty($webhook_data['info']['district']) && $webhook_data['info']['district'] = trim(strip_tags($webhook_data['info']['district']));
!empty($webhook_data['info']['name']) && $webhook_data['info']['name'] = trim(strip_tags($webhook_data['info']['name']));
!empty($webhook_data['info']['ward']) && $webhook_data['info']['ward'] = trim(strip_tags($webhook_data['info']['ward']));

!empty($webhook_data['sender']['id']) && $webhook_data['sender']['id'] = preg_replace('/[^0-9]+/', '', $webhook_data['sender']['id']);
$webhook_data['user_id_by_app'] = preg_replace('/[^0-9]+/', '', $webhook_data['user_id_by_app']);
$webhook_data['timestamp'] = floor((int) $webhook_data['timestamp'] / 1000);

webhook_handle($webhook_data);

/*$file = NV_ROOTDIR . '/zalo/test_' . NV_CURRENTTIME . '.txt';
ob_start();
print_r($webhook_data);
$contents = ob_get_contents();
ob_end_clean();
file_put_contents($file, $contents);*/

// Nếu người dùng gửi tin nhắn là từ khóa lệnh
// https://nukeviet.vn/admin/index.php?nv=zalo&op=chatbot&tab=command_keywords
$is_act = false;
if (
    !empty($keyword_actions) and
    $webhook_data['event_name'] == 'user_send_text' and
    !empty($webhook_data['message']['text']) and
    !empty($webhook_data['sender']['id']) and
    !empty($webhook_data['message']['msg_id'])
) {
    unset($matches);
    if (
        preg_match('/^query\:[0-9]+\:[^\:]+:(.+)$/', $webhook_data['message']['text'], $matches) and
        !empty($keyword_actions[$matches[1]])
    ) {
        $is_act = true;
        call_user_func_array($keyword_actions[$matches[1]][0], [$keyword_actions[$matches[1]][1], $webhook_data['sender']['id'], $webhook_data['message']['msg_id']]);
    } elseif (
        !preg_match('/[^a-z0-9\-]/', $webhook_data['message']['text']) and
        isset($keyword_actions[$webhook_data['message']['text']])
    ) {
        $is_act = true;
        call_user_func_array($keyword_actions[$webhook_data['message']['text']][0], [$keyword_actions[$webhook_data['message']['text']][1], $webhook_data['sender']['id'], $webhook_data['message']['msg_id']]);
    }
}
// Nếu không sẽ thực thi hành động khi xảy ra sự kiện Zalo
// https://nukeviet.vn/admin/index.php?nv=zalo&op=chatbot&tab=zalo_events
if (
    !$is_act and
    !empty($event_actions[$webhook_data['event_name']]) and
    function_exists($event_actions[$webhook_data['event_name']][0]) and
    !empty($webhook_data['sender']['id']) and
    !empty($webhook_data['message']['msg_id'])
) {
    call_user_func_array($event_actions[$webhook_data['event_name']][0], [$event_actions[$webhook_data['event_name']][1], $webhook_data['sender']['id'], $webhook_data['message']['msg_id']]);
}
