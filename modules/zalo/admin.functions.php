<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

use Com\Tecnick\Barcode\Exception;
use Com\Tecnick\Color\Exception as ColorException;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$menu_top = [
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $lang_global['mod_zalo']
];

$allow_func = [
    'main'
];
if (defined('NV_IS_GODADMIN')) {
    $allow_func[] = 'oa_info';
    $allow_func[] = 'followers';
    $allow_func[] = 'unfollowers';
    $allow_func[] = 'tags';
    $allow_func[] = 'templates';
    $allow_func[] = 'upload';
    $allow_func[] = 'conversation';
    $allow_func[] = 'article';
    $allow_func[] = 'video';
    $allow_func[] = 'chatbot';
    $allow_func[] = 'settings';
}
unset($page_title, $select_options);

define('NV_IS_FILE_ZALO', true);
define('NV_MOD_TABLE', $db_config['prefix'] . '_' . $module_data);
$zalo = new NukeViet\Zalo\Zalo($global_config);

$module_configs = [];
$sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_settings ORDER by type, skey';
$result = $db->query($sql);
while ($row = $result->fetch()) {
    !isset($module_configs[$row['type']]) && $module_configs[$row['type']] = [];
    $module_configs[$row['type']][$row['skey']] = $row['svalue'];
}

/**
 * Kiểm tra có lưu file trên máy chủ của site hay không
 * 
 * if_store_on_server()
 * 
 * @return bool 
 */
function if_store_on_server()
{
    global $global_config;

    $norm = 5242880;
    $allow_files = ['adobe', 'documents', 'images'];

    $upload_max_filesize = nv_converttoBytes(ini_get('upload_max_filesize'));
    $post_max_size = nv_converttoBytes(ini_get('post_max_size'));
    $nv_max_size = (int) $global_config['nv_max_size'];
    $file_allowed_ext = $global_config['file_allowed_ext'];

    if ($upload_max_filesize < $norm) {
        return false;
    }

    if ($post_max_size < $norm) {
        return false;
    }

    if ($nv_max_size < $norm) {
        return false;
    }

    $file_allowed_ext_current = array_intersect($allow_files, $file_allowed_ext);

    return $file_allowed_ext_current == $allow_files;
}

/**
 * Tạo tên file để lưu trên máy chủ của site
 * 
 * filename_create()
 * 
 * @param mixed $name 
 * @param mixed $ext 
 * @param mixed $dir 
 * @return string 
 */
function filename_create($name, $ext, $dir)
{
    $finished = '';
    $i = 0;
    while (empty($finished)) {
        $name2 = !empty($i) ? $name . '_' . $i : $name;
        if (!file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/zalo/' . $dir . '/' . $name2 . '.' . $ext)) {
            $finished = $name2 . '.' . $ext;
        }
        ++$i;
    }

    return $finished;
}

/**
 * Từ số điện thoại => Mã nước + số
 * 
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
 * Lưu Access Token mới vào CSDL
 * 
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
 * Lưu thông tin cập nhật của OA vào CSDL
 * 
 * OAInfoUpdate()
 * 
 * @param mixed $oa_info 
 * @throws PDOException 
 */
function OAInfoUpdate($oa_info)
{
    global $db;

    if (!empty($oa_info)) {
        $oa_info['is_verified'] = (int) $oa_info['is_verified'];
        $db->query('DELETE FROM ' . NV_MOD_TABLE . "_settings WHERE type='oa_info'");
        $sth = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . "_settings (skey, type, svalue) VALUES (?, 'oa_info', ?)");
        foreach ($oa_info as $info_key => $info_value) {
            $vals = [$info_key, $info_value];
            $sth->execute($vals);
        }
    }
}

/**
 * Làm sạch thông tin OA trong CSDL
 * 
 * oa_truncate()
 * 
 */
function oa_truncate()
{
    global $db;

    $db->query('DELETE FROM ' . NV_MOD_TABLE . "_settings WHERE type='oa_info'");
}

/**
 * Lấy Access Token
 * 
 * get_accesstoken()
 * 
 * @param mixed $accesstoken 
 * @param bool $isAjax 
 * @throws PDOException 
 */
function get_accesstoken(&$accesstoken, $isAjax = false)
{
    global $zalo, $lang_module, $module_name;

    $get_accesstoken_info = $zalo->oa_accesstoken_info();
    if ($get_accesstoken_info['result'] == 'ok') {
        $accesstoken = $get_accesstoken_info['access_token'];
    } elseif ($get_accesstoken_info['result'] == 'update') {
        accessTokenUpdate($get_accesstoken_info);
        $accesstoken = $get_accesstoken_info['access_token'];
    } else {
        if ($isAjax) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $lang_module['refresh_token_expired_note']
            ]);
        } else {
            info_redirect($lang_module['refresh_token_expired_note'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=settings');
        }
    }
}

/**
 * Lấy mã tỉnh/thành phố trực thuộc TƯ
 * 
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
 * Lấy mã huyện/quận
 * 
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
 * Lấy danh sách huyện/quận
 * 
 * get_districts()
 * 
 * @param mixed $city_id 
 * @return string 
 */
function get_districts($city_id)
{
    include NV_ROOTDIR . '/' . NV_DATADIR . '/vnsubdivisions.php';

    $contents = '<option value=""></option>';
    if (!empty($city_id) and !empty($districts[$city_id])) {
        foreach ($districts[$city_id] as $district_id => $district_name) {
            $contents .= '<option value="' . $district_id . '">' . $district_name[0] . '</option>';
        }
    }

    return $contents;
}

/**
 * Lấy danh sách người quan tâm chưa được cập nhật thông tin
 * 
 * get_followers_not_sync()
 * 
 * @param int $limit 
 * @return array 
 */
function get_followers_not_sync($limit = 0)
{
    global $db, $global_config;

    $list = [];
    $sql = 'SELECT user_id FROM ' . NV_MOD_TABLE . '_followers WHERE is_sync = 0 and app_id = ' . $db->quote($global_config['zaloAppID']);
    if ($limit) {
        $sql .= ' LIMIT ' . $limit;
    }
    $result = $db->query($sql);

    while ($row = $result->fetch()) {
        $list[] = $row['user_id'];
    }

    return $list;
}

/**
 * Lưu thông tin của người quan tâm vào CSDL
 * 
 * follower_profile_save()
 * 
 * @param mixed $user_id 
 * @param mixed $follower_profile 
 * @throws PDOException 
 */
function follower_profile_save($user_id, $follower_profile)
{
    global $db, $global_config;

    $query = 'SELECT * FROM ' . NV_MOD_TABLE . '_followers WHERE user_id=' . $db->quote($user_id);
    $row = $db->query($query)->fetch();

    if (!empty($follower_profile['avatars'][120])) {
        $row['avatar120'] = $follower_profile['avatars'][120];
    } elseif (!empty($follower_profile['avatar'])) {
        $row['avatar120'] = $follower_profile['avatar'];
    }
    if (!empty($follower_profile['avatars'][240])) {
        $row['avatar240'] = $follower_profile['avatars'][240];
    }
    isset($follower_profile['user_gender']) && $row['user_gender'] = (int) $follower_profile['user_gender'];
    !empty($follower_profile['user_id_by_app']) && $row['user_id_by_app'] = $follower_profile['user_id_by_app'];
    !empty($follower_profile['display_name']) && $row['display_name'] = $follower_profile['display_name'];
    $row['tags_info'] = !empty($follower_profile['tags_and_notes_info']['tag_names']) ? implode(', ', $follower_profile['tags_and_notes_info']['tag_names']) : '';
    $row['notes_info'] = !empty($follower_profile['tags_and_notes_info']['notes']) ? implode(', ', $follower_profile['tags_and_notes_info']['notes']) : '';
    $row['name'] = !empty($follower_profile['shared_info']['name']) ? $follower_profile['shared_info']['name'] : '';
    if (!empty($follower_profile['shared_info']['phone'])) {
        list($row['phone_code'], $row['phone_number']) = parse_phone($follower_profile['shared_info']['phone']);
    } else {
        $row['phone_code'] = $row['phone_number'] = '';
    }
    $row['address'] = !empty($follower_profile['shared_info']['address']) ? $follower_profile['shared_info']['address'] : '';
    $row['city_id'] = !empty($follower_profile['shared_info']['city']) ? get_province_id($follower_profile['shared_info']['city']) : '';
    $row['district_id'] = (!empty($row['city_id']) and !empty($follower_profile['shared_info']['district'])) ? get_district_id($row['city_id'], $follower_profile['shared_info']['district']) : '';

    $sth = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_followers SET 
        app_id = ' . $db->quote($global_config['zaloAppID']) . ',
        user_id_by_app = :user_id_by_app,
        display_name = :display_name,
        avatar120 = :avatar120,
        avatar240 = :avatar240,
        user_gender = :user_gender,
        tags_info = :tags_info,
        notes_info = :notes_info,
        isfollow = 1,
        name = :name,
        phone_code = :phone_code,
        phone_number = :phone_number,
        address = :address,
        city_id = :city_id,
        district_id = :district_id,
        is_sync = 1,
        updatetime = ' . NV_CURRENTTIME . ' 
        WHERE user_id = ' . $db->quote($user_id));
    $sth->bindParam(':user_id_by_app', $row['user_id_by_app'], PDO::PARAM_STR);
    $sth->bindParam(':display_name', $row['display_name'], PDO::PARAM_STR);
    $sth->bindParam(':avatar120', $row['avatar120'], PDO::PARAM_STR);
    $sth->bindParam(':avatar240', $row['avatar240'], PDO::PARAM_STR);
    $sth->bindParam(':user_gender', $row['user_gender'], PDO::PARAM_STR);
    $sth->bindParam(':tags_info', $row['tags_info'], PDO::PARAM_STR);
    $sth->bindParam(':notes_info', $row['notes_info'], PDO::PARAM_STR);
    $sth->bindParam(':name', $row['name'], PDO::PARAM_STR);
    $sth->bindParam(':phone_code', $row['phone_code'], PDO::PARAM_STR);
    $sth->bindParam(':phone_number', $row['phone_number'], PDO::PARAM_STR);
    $sth->bindParam(':address', $row['address'], PDO::PARAM_STR);
    $sth->bindParam(':city_id', $row['city_id'], PDO::PARAM_STR);
    $sth->bindParam(':district_id', $row['district_id'], PDO::PARAM_STR);
    $sth->execute();

    remove_follower_alltags($user_id);

    if (!empty($follower_profile['tags_and_notes_info']['tag_names'])) {
        $tags = get_tags();
        foreach ($follower_profile['tags_and_notes_info']['tag_names'] as $alias) {
            if (!isset($tags[$alias])) {
                add_tag(['alias' => $alias, 'name' => $alias]);
            }
            add_follower_tag($user_id, $alias);
        }
    }
}

/**
 * Hiên thị thông tin và chuyển hướng
 * 
 * info_redirect()
 * 
 * @param mixed $mess 
 * @param mixed $redirect_url 
 */
function info_redirect($mess, $redirect_url)
{
    global $global_config, $module_file;

    $xtpl = new XTemplate('error.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('REDIRECT_URL', $redirect_url);
    $xtpl->assign('MESS', $mess);
    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

/**
 * Lấy danh sách nhãn từ CSDL
 * 
 * get_tags()
 * 
 * @return array 
 */
function get_tags()
{
    global $db;

    $result = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_tags ORDER BY alias ASC');
    $tags = [];
    while ($row = $result->fetch()) {
        $tags[$row['alias']] = $row['name'];
    }

    return $tags;
}

/**
 * Ghi vào CSDL thông tin video đã được upload lên Zalo
 * 
 * video_add()
 * 
 * @param mixed $video 
 * @throws PDOException 
 */
function video_add($video)
{
    global $db;

    $sth = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_video 
        (video_id, token, video_name, video_size, description, view, thumb, status, status_message, convert_percent, convert_error_code, addtime) VALUES 
        (:video_id, :token, :video_name, :video_size, :description, :view, :thumb, :status, :status_message, :convert_percent, :convert_error_code, ' . NV_CURRENTTIME . ')');
    $sth->bindValue(':video_id', $video['video_id'], PDO::PARAM_STR);
    $sth->bindValue(':token', $video['token'], PDO::PARAM_STR);
    $sth->bindParam(':video_name', $video['video_name'], PDO::PARAM_STR);
    $sth->bindParam(':video_size', $video['video_size'], PDO::PARAM_INT);
    $sth->bindParam(':description', $video['description'], PDO::PARAM_STR);
    $sth->bindParam(':view', $video['view'], PDO::PARAM_STR);
    $sth->bindParam(':thumb', $video['thumb'], PDO::PARAM_STR);
    $sth->bindParam(':status', $video['status'], PDO::PARAM_INT);
    $sth->bindParam(':status_message', $video['status_message'], PDO::PARAM_STR);
    $sth->bindParam(':convert_percent', $video['convert_percent'], PDO::PARAM_INT);
    $sth->bindParam(':convert_error_code', $video['convert_error_code'], PDO::PARAM_INT);
    $sth->execute();
}

/**
 * Cập nhật thông tin video đã được upload lên Zalo
 * 
 * video_update()
 * 
 * @param mixed $id 
 * @param mixed $video 
 * @throws PDOException 
 */
function video_update($id, $video)
{
    global $db;

    $sth = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_video SET 
    token = :token, status_message = :status_message, video_name = :video_name, video_size = :video_size, convert_percent = :convert_percent, convert_error_code = :convert_error_code, video_id = :video_id, status = :status 
    WHERE id = ' . $id);
    $sth->bindParam(':token', $video['token'], PDO::PARAM_STR);
    $sth->bindParam(':status_message', $video['status_message'], PDO::PARAM_STR);
    $sth->bindParam(':video_name', $video['video_name'], PDO::PARAM_STR);
    $sth->bindParam(':video_size', $video['video_size'], PDO::PARAM_INT);
    $sth->bindParam(':convert_percent', $video['convert_percent'], PDO::PARAM_INT);
    $sth->bindParam(':convert_error_code', $video['convert_error_code'], PDO::PARAM_INT);
    $sth->bindParam(':video_id', $video['video_id'], PDO::PARAM_STR);
    $sth->bindParam(':status', $video['status'], PDO::PARAM_INT);
    $sth->execute();
}

/**
 * Lưu thông tin chỉnh sửa nội bộ video (Không cập nhật trên Zalo)
 * 
 * video_edit_save()
 * 
 * @param mixed $id 
 * @param mixed $view 
 * @param mixed $thumb 
 * @param mixed $description 
 * @throws PDOException 
 */
function video_edit_save($id, $view, $thumb, $description)
{
    global $db;

    $sth = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_video SET 
    view = :view, thumb = :thumb, description = :description 
    WHERE id = ' . $id);
    $sth->bindParam(':view', $view, PDO::PARAM_STR);
    $sth->bindParam(':thumb', $thumb, PDO::PARAM_STR);
    $sth->bindParam(':description', $description, PDO::PARAM_STR);
    $sth->execute();
}

/**
 * Lấy danh sách các video đã upload lên Zalo
 * 
 * video_get_list()
 * 
 * @return array 
 */
function video_get_list()
{
    global $db;

    $result = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_video ORDER BY addtime DESC');
    $files = [];
    while ($row = $result->fetch()) {
        $files[$row['id']] = $row;
    }

    return $files;
}

/**
 * Lấy token của video đã upload lên Zalo
 * 
 * video_get_token()
 * 
 * @param mixed $id 
 * @return mixed 
 */
function video_get_token($id)
{
    global $db;

    $token = $db->query('SELECT token FROM ' . NV_MOD_TABLE . '_video WHERE id = ' . $id)->fetchColumn();

    return $token;
}

/**
 * Kiểm tra sự tồn tại của video
 * 
 * video_check()
 * 
 * @param mixed $video_id 
 * @return mixed 
 */
function video_check($video_id)
{
    global $db;

    $isExists = $db->query('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_video WHERE video_id = ' . $db->quote($video_id) . ' AND status=1')->fetchColumn();

    return $isExists;
}

/**
 * Xóa video
 * 
 * video_delete()
 * 
 * @param mixed $id 
 */
function video_delete($id)
{
    global $db;

    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_video WHERE id=' . $id);
}

/**
 * Lấy danh sách file/hình ảnh đã upload lên Zalo
 * 
 * get_upload()
 * 
 * @param mixed $type 
 * @return array 
 */
function get_upload($type)
{
    global $db, $lang_module;

    if (!empty($type)) {
        $result = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_upload WHERE type=' . $db->quote($type) . ' ORDER BY addtime ASC');
    } else {
        $result = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_upload ORDER BY addtime ASC');
    }

    $files = [];
    while ($row = $result->fetch()) {
        $exptime = $row['addtime'] + (604800 - 60);
        $row['addtime'] = nv_date('H:i d/m/Y', $row['addtime']);
        $row['exptime'] = nv_date('H:i d/m/Y', $exptime);
        $row['type_name'] = $lang_module['type_' . $row['type']];
        $row['isexpired'] = ((NV_CURRENTTIME - (int) $row['addtime']) < 604800);
        $row['fullname'] = !empty($row['localfile']) ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/zalo/' . $row['localfile'] : '';
        $files[] = $row;
    }

    return $files;
}

/**
 * Lưu thông tin file/hình ảnh đã upload lên Zalo
 * 
 * upload_save()
 * 
 * @param mixed $type 
 * @param mixed $file 
 * @param mixed $localfile 
 * @param mixed $extension 
 * @param mixed $width 
 * @param mixed $height 
 * @param mixed $zalo_id 
 * @param mixed $description 
 * @throws PDOException 
 */
function upload_save($type, $file, $localfile, $extension, $width, $height, $zalo_id, $description)
{
    global $db;

    $sth = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_upload 
        (type, extension, file, localfile, width, height, zalo_id, description, addtime) VALUES 
        (:type, :extension, :file, :localfile, ' . $width . ', ' . $height . ', :zalo_id, :description, ' . NV_CURRENTTIME . ')');
    $sth->bindValue(':type', $type, PDO::PARAM_STR);
    $sth->bindValue(':extension', $extension, PDO::PARAM_STR);
    $sth->bindValue(':file', $file, PDO::PARAM_STR);
    $sth->bindValue(':localfile', $localfile, PDO::PARAM_STR);
    $sth->bindParam(':zalo_id', $zalo_id, PDO::PARAM_STR);
    $sth->bindParam(':description', $description, PDO::PARAM_STR);
    $sth->execute();
}

/**
 * Cập nhật thông tin zalo_id của file/hình ảnh đã upload lên Zalo
 * 
 * upload_update()
 * 
 * @param mixed $id 
 * @param mixed $zalo_id 
 * @param mixed $addtime 
 * @throws PDOException 
 */
function upload_update($id, $zalo_id, $addtime)
{
    global $db;

    $sth = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_upload SET zalo_id = :zalo_id, addtime=' . $addtime . ' WHERE id = ' . $id);
    $sth->bindParam(':zalo_id', $zalo_id, PDO::PARAM_STR);
    $sth->execute();
}

/**
 * Xóa khỏi CSDL file/hình ảnh đã upload lên Zalo (chỉ nội bộ, không xóa file trực tiếp trên Zalo được)
 * 
 * upload_delete()
 * 
 * @param mixed $id 
 */
function upload_delete($id)
{
    global $db;

    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_upload WHERE id=' . $id);
}

/**
 * Cập nhật thông tin mô tả file/hình ảnh (Nội bộ)
 * 
 * file_desc_update()
 * 
 * @param mixed $id 
 * @param mixed $description 
 * @throws PDOException 
 */
function file_desc_update($id, $description)
{
    global $db;

    $sth = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_upload SET description = :description WHERE id = ' . $id);
    $sth->bindParam(':description', $description, PDO::PARAM_STR);
    $sth->execute();
}

/**
 * Lấy thông tin file/hình ảnh đã upload lên Zalo
 * 
 * get_file_upload_info()
 * 
 * @param mixed $id 
 * @return mixed 
 */
function get_file_upload_info($id)
{
    global $db;

    $result = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_upload WHERE id=' . $id);

    return $result->fetch();
}

/**
 * Lấy đường dẫn lưu file/hình ảnh trên máy chủ của site
 * 
 * get_file_by_id()
 * 
 * @param mixed $id 
 * @return mixed 
 */
function get_file_by_id($id)
{
    global $db;

    $file = $db->query('SELECT localfile FROM ' . NV_MOD_TABLE . '_upload WHERE id = ' . $id)->fetchColumn();

    return $file;
}

/**
 * Lấy thông tin zalo_id của file/hình ảnh đã upload lên Zalo
 * 
 * get_zalo_id_by_id()
 * 
 * @param mixed $id 
 * @return mixed 
 */
function get_zalo_id_by_id($id)
{
    global $db;

    $file = $db->query('SELECT zalo_id FROM ' . NV_MOD_TABLE . '_upload WHERE id = ' . $id)->fetchColumn();

    return $file;
}

/**
 * Lấy danh sách nhãn được gán cho người quan tâm
 * 
 * get_follower_tags()
 * 
 * @param mixed $user_id 
 * @return array 
 */
function get_follower_tags($user_id)
{
    global $db;

    $result = $db->query('SELECT tag FROM ' . NV_MOD_TABLE . '_tags_follower WHERE user_id=' . $db->quote($user_id) . ' ORDER BY tag DESC');
    $ftags = [];
    while ($row = $result->fetch()) {
        $ftags[] = $row['tag'];
    }

    return $ftags;
}

/**
 * Thêm nhãn mới
 * 
 * add_tag()
 * 
 * @param mixed $new_tag 
 */
function add_tag($new_tag)
{
    global $db;

    $sql = 'INSERT  IGNORE INTO ' . NV_MOD_TABLE . '_tags (alias, name) VALUES (' . $db->quote($new_tag['alias']) . ', ' . $db->quote($new_tag['name']) . ')';
    $db->query($sql);
}

/**
 * Cập nhật thông tin nhãn
 * 
 * update_tag()
 * 
 * @param mixed $alias 
 * @param mixed $new_name 
 * @throws PDOException 
 */
function update_tag($alias, $new_name)
{
    global $db;

    $sth = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_tags SET 
    name = :name WHERE alias = :alias');
    $sth->bindParam(':name', $new_name, PDO::PARAM_STR);
    $sth->bindParam(':alias', $alias, PDO::PARAM_STR);
    $sth->execute();
}

/**
 * Gán nhãn cho người quan tâm
 * add_follower_tag()
 * 
 * @param mixed $user_id 
 * @param mixed $post_tag 
 */
function add_follower_tag($user_id, $post_tag)
{
    global $db;

    $sql = 'INSERT  IGNORE INTO ' . NV_MOD_TABLE . '_tags_follower (tag, user_id) VALUES (' . $db->quote($post_tag) . ', ' . $db->quote($user_id) . ')';
    $db->query($sql);
}

/**
 * Xóa toàn bộ nhãn của người quan tâm
 * 
 * remove_follower_alltags()
 * 
 * @param mixed $user_id 
 */
function remove_follower_alltags($user_id)
{
    global $db;

    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_tags_follower WHERE user_id=' . $db->quote($user_id));
}

/**
 * Xóa 01 nhãn của người quan tâm
 * 
 * remove_follower_tag()
 * 
 * @param mixed $user_id 
 * @param mixed $tag_alias 
 */
function remove_follower_tag($user_id, $tag_alias)
{
    global $db;

    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_tags_follower WHERE tag=' . $db->quote($tag_alias) . ' AND user_id=' . $db->quote($user_id));
}

/**
 * Xóa nhãn
 * 
 * delete_tag()
 * 
 * @param mixed $tag_alias 
 */
function delete_tag($tag_alias)
{
    global $db;

    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_tags WHERE alias=' . $db->quote($tag_alias));
}

/**
 * Kiểm tra số người quan tâm được gắn nhãn
 * 
 * get_user_count_by_tag()
 * 
 * @param mixed $tag 
 * @return mixed 
 */
function get_user_count_by_tag($tag)
{
    global $db;

    $sql = 'SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_tags_follower WHERE tag=' . $db->quote($tag);

    return $db->query($sql)->fetchColumn();
}

/**
 * Hiển thị hội thoại
 * 
 * conversation_to_html()
 * 
 * @param mixed $contents 
 * @param mixed $user_id 
 * @return mixed 
 */
function conversation_to_html($contents, $user_id)
{
    global $global_config, $module_name, $module_file, $lang_module, $lang_global;

    $xtpl = new XTemplate('conversation.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    $count = count($contents);

    if (empty($count)) {
        return $lang_module['empty'];
    }

    $oa_info = get_oa_info();
    $follower_info = get_follower_info($user_id);
    $src = -1;

    $content_keys = array_keys($contents);
    for ($i = 0; $i < $count; ++$i) {
        $y = $count - $i - 1;
        $message = $contents[$content_keys[$y]];
        $message['avatar'] = !empty($message['src']) ? $follower_info['avatar120'] : $oa_info['avatar'];
        $message['time_format'] = nv_date('H:i d/m/Y', $message['time']);

        if ($message['src'] == '0' and !empty($message['note'])) {
            $note = json_decode($message['note'], true);
            $message['type'] = $note['type'];
            if ($note['send_type'] == 'plaintext') {
                $message['message'] = nv_nl2br($note['message']);
            } elseif ($note['send_type'] == 'site' or $note['send_type'] == 'internet') {
                empty($message['url']) && $message['url'] = $note['url'];
                empty($message['description']) && $message['description'] = nv_nl2br($note['description']);
            } elseif ($note['send_type'] == 'zalo') {
                if (empty($message['url'])) {
                    $file = get_file_by_id($note['upload_id']);
                    if (!empty($file)) {
                        $message['url'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/zalo/' . get_file_by_id($note['upload_id']);
                    }
                }
                $message['description'] = $note['description'];
            } elseif ($note['send_type'] == 'file') {
                $file = get_file_by_id($note['upload_id']);
                if (!empty($file)) {
                    $message['url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload&amp;file_download=1&amp;id=' . (int) $note['upload_id'];
                    $message['description'] = nv_pathinfo_filename($file);
                    $message['ext'] = nv_getextension($file);
                }
            } elseif ($note['send_type'] == 'request') {
                if (nv_is_url($note['request_info']['image_url'])) {
                    $message['url'] = $note['request_info']['image_url'];
                } elseif (file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/zalo/' . $note['request_info']['image_url'])) {
                    $message['url'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/zalo/' . $note['request_info']['image_url'];
                } else {
                    $message['url'] = $note['request_info']['image_url'];
                }
                $message['title'] = $note['request_info']['title'];
                $message['subtitle'] = $note['request_info']['subtitle'];
                $message['message'] = $lang_module['info_request'];
            } elseif ($note['send_type'] == 'textlist') {
                $message['links'] = $note['textlist'];
            } elseif ($note['send_type'] == 'btnlist') {
                $message['message'] = $note['message'];
                $message['links'] = $note['buttons'];
            }
        }

        if ($message['type'] == 'file') {
            if (empty($message['ext'])) {
                $message['ext'] = nv_getextension($message['url']);
            }
        }

        if ($message['type'] == 'photo' or $message['type'] == 'gif') {
            if (empty($message['thumb'])) {
                $message['thumb'] = $message['url'];
            }
        }

        if ($message['type'] == 'location') {
            $coordinates = json_decode($message['location'], true);
            $message['url'] = '//www.google.com/maps/place/' . $coordinates['latitude'] . ',' . $coordinates['longitude'];
            $message['latitude'] = $coordinates['latitude'];
            $message['longitude'] = $coordinates['longitude'];
        }

        if ($message['type'] == 'voice') {
            $message['playfile'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=conversation&amp;player=1&amp;url=' . urlencode($message['url']);
        }

        unset($matches);
        $isAutoMess = false;
        if ($message['type'] == 'text' and preg_match('/^query\:[0-9]+\:[^\:]+:(.+)$/', $message['message'], $matches)) {
            $message['message'] = $matches[1];
            $isAutoMess = true;
        }
        $xtpl->assign('MESSAGE', $message);

        if ($src != $message['src']) {
            $xtpl->parse('messages.message.avatar');
            $src = $message['src'];
        }

        if ($message['type'] == 'text') {
            if ($isAutoMess) {
                $xtpl->parse('messages.message.text.auto_mess');
            }
            $xtpl->parse('messages.message.text');
        } elseif ($message['type'] == 'photo') {
            if (!empty($message['description'])) {
                $xtpl->parse('messages.message.photo.description');
            }
            $xtpl->parse('messages.message.photo');
        } elseif ($message['type'] == 'gif') {
            $xtpl->parse('messages.message.GIF');
        } elseif ($message['type'] == 'link') {
            if (!empty($message['links'])) {
                $links = json_decode($message['links'], true);
                foreach ($links as $link) {
                    if ($link['url'] == 'https://zalo.me') {
                        unset($matches);
                        if (preg_match('/\{\"phone\"\:\"([^\"]+)\"\}/', $link['description'], $matches)) {
                            $link['url'] = 'https://zalo.me/' . $matches[1];
                            $link['description'] = '<i class="fa fa-id-card-o" aria-hidden="true"></i> ' . $matches[1];
                        }
                    }
                    $xtpl->assign('LINK', $link);

                    if (!empty($link['title'])) {
                        $xtpl->parse('messages.message.link.title');
                    }

                    if (!empty($link['thumb'])) {
                        $xtpl->parse('messages.message.link.thumb');
                    }
                    if (!empty($link['description'])) {
                        $xtpl->parse('messages.message.link.description');
                    }
                    $xtpl->parse('messages.message.link');
                }
            }
        } elseif ($message['type'] == 'links') {
            if (!empty($message['links'])) {
                if ($message['src'] == '0' and !empty($message['note'])) {
                    $elements = $message['links'];
                } else {
                    $elements = json_decode($message['links'], true);
                }

                foreach ($elements as $element) {
                    if (empty($element['subtitle']) and isset($element['description'])) {
                        $element['subtitle'] = $element['description'];
                    }
                    if (empty($element['default_action']) and !empty($element['url'])) {
                        $element['default_action'] = [
                            'type' => 'oa.open.url',
                            'url' => $element['url']
                        ];
                    }
                    $xtpl->assign('ELEMENT', $element);
                    if (!empty($element['default_action'])) {
                        $content = '';
                        if ($element['default_action']['type'] == 'oa.open.url') {
                            $content = $lang_module['url'] . ': ' . $element['default_action']['url'];
                        }
                        if ($element['default_action']['type'] == 'oa.query.show' or $element['default_action']['type'] == 'oa.query.hide') {
                            $content = $lang_module['content'] . ': ' . $element['default_action']['payload'];
                        }
                        if ($element['default_action']['type'] == 'oa.open.sms') {
                            $content = $lang_module['content'] . ': ' . $element['default_action']['payload']['content'] . '; ' . $lang_module['phone'] . ': ' . $element['default_action']['payload']['phone_code'];
                        }
                        if ($element['default_action']['type'] == 'oa.open.phone') {
                            $content = $lang_module['phone'] . ': ' . $element['default_action']['payload']['phone_code'];
                        }
                        $xtpl->assign('ACTION', [
                            'action_title' => $lang_module[str_replace('.', '_', $element['default_action']['type'])],
                            'action_content' => $content
                        ]);
                        $xtpl->parse('messages.message.links.element.action');
                    }
                    if (!empty($element['subtitle'])) {
                        $xtpl->parse('messages.message.links.element.subtitle');
                    }
                    $xtpl->parse('messages.message.links.element');
                }
                $xtpl->parse('messages.message.links');
            }
        } elseif ($message['type'] == 'buttons') {
            if (!empty($message['message']) and !empty($message['links'])) {
                if ($message['src'] == '0' and !empty($message['note'])) {
                    $buttons = $message['links'];
                } else {
                    $buttons = json_decode($message['links'], true);
                }

                foreach ($buttons as $button) {
                    $content = '';
                    if ($button['type'] == 'oa.open.url') {
                        $content = $lang_module['url'] . ': ' . $button['payload']['url'];
                    }
                    if ($button['type'] == 'oa.query.show' or $button['type'] == 'oa.query.hide') {
                        $content = $lang_module['content'] . ': ' . $button['payload'];
                    }
                    if ($button['type'] == 'oa.open.sms') {
                        $content = $lang_module['content'] . ': ' . $button['payload']['content'] . '; ' . $lang_module['phone'] . ': ' . $button['payload']['phone_code'];
                    }
                    if ($button['type'] == 'oa.open.phone') {
                        $content = $lang_module['phone'] . ': ' . $button['payload']['phone_code'];
                    }
                    $button['action_title'] = $lang_module[str_replace('.', '_', $button['type'])];
                    $button['action_content'] = $content;
                    $xtpl->assign('BTN', $button);
                    $xtpl->parse('messages.message.buttons.btn');
                }
                $xtpl->parse('messages.message.buttons');
            }
        } elseif ($message['type'] == 'sticker') {
            $xtpl->parse('messages.message.sticker');
        } elseif ($message['type'] == 'location') {
            $xtpl->parse('messages.message.location');
        } elseif ($message['type'] == 'voice') {
            $xtpl->parse('messages.message.voice');
        } elseif ($message['type'] == 'file') {
            if ($message['ext'] == 'pdf') {
                $xtpl->parse('messages.message.file.pdf');
            } else {
                $xtpl->parse('messages.message.file.doc');
            }
            $xtpl->parse('messages.message.file');
        } else {
            $xtpl->parse('messages.message.nosupport');
        }

        if ($message['src'] == '1') {
            $xtpl->parse('messages.message.tool');
        }

        $xtpl->parse('messages.message');
    }

    $xtpl->parse('messages');

    return $xtpl->text('messages');
}

/**
 * Lưu các hội thoại gần nhất của người quan tâm
 * 
 * save_last_conversation()
 * 
 * @param mixed $contents 
 * @param mixed $user_id 
 * @throws PDOException 
 */
function save_last_conversation($contents, $user_id)
{
    global $db;

    $sth = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . "_conversation 
        (message_id, user_id, src, time, type, message, links, thumb, url, description, location, note) VALUES 
        (:message_id, :user_id, :src, :time, :type, :message, :links, :thumb, :url, :description, :location, '') ON DUPLICATE KEY UPDATE 
        time=VALUES(time), type=VALUES(type), message=VALUES(message), links=VALUES(links), thumb=VALUES(thumb), url=VALUES(url), description=VALUES(description), location=VALUES(location)");
    foreach ($contents as $content) {
        if (!empty($content['message_id'])) {
            $sth->bindValue(':message_id', $content['message_id'], PDO::PARAM_STR);
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
        }
    }

    $oldtime = $db->query('SELECT time FROM ' . NV_MOD_TABLE . '_conversation WHERE user_id = ' . $db->quote($user_id) . ' ORDER BY time DESC LIMIT 100, 1')->fetchColumn();
    if (!empty($oldtime)) {
        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_conversation WHERE user_id = ' . $db->quote($user_id) . ' AND time <= ' . $oldtime);
    }
}

/**
 * Lưu tin nhắn từ OA đến người quan tâm
 * 
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
 * Lấy danh sách 50 hội thoại mới nhất liên quan đến người quan tâm
 * 
 * get_conversation()
 * 
 * @param mixed $user_id 
 * @return (bool|array)[] 
 */
function get_conversation($user_id)
{
    global $db;

    $result = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_conversation WHERE user_id = ' . $db->quote($user_id) . ' ORDER BY time DESC LIMIT 50');
    $contents = [];
    $updated = false;
    while ($row = $result->fetch()) {
        $contents[$row['message_id']] = $row;
        if (empty($row['displayed'])) {
            $updated = true;
        }
    }
    if ($updated) {
        $db->query('UPDATE ' . NV_MOD_TABLE . '_conversation SET displayed=1 WHERE user_id = ' . $db->quote($user_id));
    }

    return [
        'updated' => $updated,
        'contents' => $contents
    ];
}

/**
 * Lấy thông tin người quan tâm
 * 
 * get_follower_info()
 * 
 * @param mixed $user_id 
 * @return mixed 
 */
function get_follower_info($user_id)
{
    global $db;

    $query = 'SELECT * FROM ' . NV_MOD_TABLE . '_followers WHERE user_id=' . $db->quote($user_id);

    return $db->query($query)->fetch();
}

/**
 * Lấy thông tin OA
 * 
 * get_oa_info()
 * 
 * @return mixed 
 */
function get_oa_info()
{
    global $module_configs;

    return !empty($module_configs['oa_info']) ? $module_configs['oa_info'] : [];
}

/**
 * Lấy danh sách các hành động cho các sự kiện webhook của Zalo
 * 
 * get_webhook_actions()
 * 
 * @return array 
 */
function get_webhook_actions()
{
    global $module_configs;

    if (empty($module_configs['action'])) {
        return [];
    }

    $actions = [];
    foreach ($module_configs['action'] as $event => $json) {
        $actions[$event] = json_decode($json, true);
    }

    return $actions;
}

/**
 * Lưu các hành động cho các sự kiện webhook của Zalo
 * webhook_actions_save()
 * 
 * @param mixed $action 
 * @param mixed $parameter 
 * @throws PDOException 
 */
function webhook_actions_save($action, $parameter)
{
    global $db;

    $db->query('DELETE FROM ' . NV_MOD_TABLE . "_settings WHERE type ='action'");
    $sth = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . "_settings (skey, type, svalue) VALUES (:skey, 'action', :svalue)");
    foreach ($action as $key => $act) {
        $par = $parameter[$key];
        if (!empty($act) and !empty($par)) {
            $val = json_encode([$act, $par]);
            $sth->bindValue(':skey', $key, PDO::PARAM_STR);
            $sth->bindParam(':svalue', $val, PDO::PARAM_STR);
            $sth->execute();
        }
    }
}

/**
 * Lấy danh sách khóa lệnh
 * 
 * get_keyword_actions()
 * 
 * @return array 
 */
function get_keyword_actions()
{
    global $module_configs;

    if (empty($module_configs['keyword'])) {
        return [];
    }

    $actions = [];
    foreach ($module_configs['keyword'] as $key => $json) {
        $json = json_decode($json, true);
        array_push($json, $key);
        $actions[] = $json;
    }

    return $actions;
}

/**
 * Kiểm tra sự tồn tại của khóa lệnh
 * 
 * keyword_is_exists()
 * 
 * @param mixed $keyword 
 * @return bool 
 */
function keyword_is_exists($keyword)
{
    global $module_configs;

    if (empty($module_configs['keyword'])) {
        return false;
    }

    return isset($module_configs['keyword'][$keyword]);
}

/**
 * Lưu các khóa lệnh
 * 
 * keyword_actions_save()
 * 
 * @param mixed $title 
 * @param mixed $keyword 
 * @param mixed $action 
 * @param mixed $parameter 
 * @throws PDOException 
 */
function keyword_actions_save($title, $keyword, $action, $parameter)
{
    global $db;

    $db->query('DELETE FROM ' . NV_MOD_TABLE . "_settings WHERE type ='keyword'");
    $sth = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . "_settings (skey, type, svalue) VALUES (:skey, 'keyword', :svalue)");
    $keys = [];
    foreach ($keyword as $k => $key) {
        $key = strtolower(change_alias($key));
        $key = substr($key, 0, 90);
        $ttl = $title[$k];
        if (empty($ttl)) {
            $ttl = $key;
        }
        $act = $action[$k];
        $par = $parameter[$k];
        if (!empty($key) and !empty($act) and !empty($par) and (empty($keys) or !in_array($key, $keys, true))) {
            $keys[] = $key;
            $val = json_encode([$act, $par, $ttl], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $sth->bindValue(':skey', $key, PDO::PARAM_STR);
            $sth->bindParam(':svalue', $val, PDO::PARAM_STR);
            $sth->execute();
        }
    }
}

/**
 * Lấy danh sách các mẫu tin nhắn
 * 
 * template_getlist()
 * 
 * @param mixed $type 
 * @return array 
 */
function template_getlist($type)
{
    global $db;

    $list = [];
    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_template WHERE type = ' . $db->quote($type);
    $result = $db->query($sql);

    while ($row = $result->fetch()) {
        $content = json_decode($row['content'], true);
        $content['id'] = $row['id'];
        $list[$row['id']] = $content;
    }

    return $list;
}

/**
 * Lưu mẫu tin nhắn
 * 
 * template_save()
 * 
 * @param mixed $type 
 * @param mixed $content 
 * @throws PDOException 
 */
function template_save($type, $content)
{
    global $db;

    $sth = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_template 
        (type, content) VALUES 
        (:type, :content)');

    $sth->bindValue(':type', $type, PDO::PARAM_STR);
    $sth->bindParam(':content', $content, PDO::PARAM_STR);
    $sth->execute();
}

/**
 * Cập nhật mẫu tin nhắn
 * 
 * template_update()
 * 
 * @param mixed $id 
 * @param mixed $content 
 * @throws PDOException 
 */
function template_update($id, $content)
{
    global $db;

    $sth = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_template SET 
    content = :content WHERE id = ' . $id);
    $sth->bindParam(':content', $content, PDO::PARAM_STR);
    $sth->execute();
}

/**
 * Xóa mẫu tin nhắn
 * 
 * template_delete()
 * 
 * @param mixed $id 
 */
function template_delete($id)
{
    global $db;

    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_template WHERE id =' . $id);
}

/**
 * Lấy thông tin mẫu tin nhắn
 * 
 * template_getinfo()
 * 
 * @param mixed $id 
 * @return mixed 
 */
function template_getinfo($id)
{
    global $db;

    $content = [];
    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_template WHERE id=' . $id;
    $result = $db->query($sql);
    $row = $result->fetch();
    if ($row) {
        $content = json_decode($row['content'], true);
        $content['type'] = $row['type'];
    }

    return $content;
}

/**
 * Lấy danh sách bài viết
 * 
 * get_articles()
 * 
 * @return array 
 */
function get_articles()
{
    global $db;

    $list = [];
    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_article';
    $result = $db->query($sql);

    while ($row = $result->fetch()) {
        $list[$row['id']] = $row;
    }

    return $list;
}

/**
 * Xóa bài viết
 * 
 * article_delete()
 * 
 * @param mixed $id 
 */
function article_delete($id)
{
    global $db;

    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_article WHERE id =' . $id);
}

/**
 * Lấy thông tin bài viết
 * 
 * get_article_info()
 * 
 * @param mixed $id 
 * @return mixed 
 */
function get_article_info($id)
{
    global $db;

    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_article WHERE id=' . $id;
    $result = $db->query($sql);
    $row = $result->fetch();
    if ($row) {
        $row['body'] = json_decode($row['body'], true);
        $row['related_medias'] = json_decode($row['related_medias'], true);
    }

    return $row;
}

/**
 * Lấy danh sách bài viết theo danh sách zalo_id
 * 
 * get_article_title_by_zalo_id()
 * 
 * @param mixed $ids 
 * @return array 
 */
function get_article_title_by_zalo_id($ids)
{
    global $db;

    $list = [];
    $in = "'" . implode("','", $ids) . "'";
    $sql = 'SELECT zalo_id, title FROM ' . NV_MOD_TABLE . '_article WHERE zalo_id IN (' . $in . ')';
    $result = $db->query($sql);

    while ($row = $result->fetch()) {
        $list[$row['zalo_id']] = $row['title'];
    }

    return $list;
}

/**
 * Lấy token của bài viết
 * 
 * get_article_token_by_id()
 * 
 * @param mixed $id 
 * @return mixed 
 */
function get_article_token_by_id($id)
{
    global $db;

    return $db->query('SELECT token FROM ' . NV_MOD_TABLE . '_article WHERE id = ' . $id)->fetchColumn();
}

/**
 * Lấy zalo_id của bài viết
 * 
 * get_article_zalo_id()
 * 
 * @param mixed $id 
 * @return mixed 
 */
function get_article_zalo_id($id)
{
    global $db;

    return $db->query('SELECT zalo_id FROM ' . NV_MOD_TABLE . '_article WHERE id = ' . $id)->fetchColumn();
}

/**
 * Cập nhật zalo_id của bài viết
 * 
 * zalo_id_update()
 * 
 * @param mixed $id 
 * @param mixed $zalo_id 
 * @throws PDOException 
 */
function zalo_id_update($id, $zalo_id)
{
    global $db;

    $sth = $db->prepare('UPDATE ' . NV_MOD_TABLE . "_article SET 
    zalo_id = :zalo_id, token = '' WHERE id = " . $id);
    $sth->bindParam(':zalo_id', $zalo_id, PDO::PARAM_STR);
    $sth->execute();
}

/**
 * Tạo nội dung bài viết trước khi gửi lên Zalo
 * 
 * article_body_create()
 * 
 * @param mixed $article_body 
 * @return string|false 
 */
function article_body_create($article_body)
{
    $body = [];
    if (!empty($article_body)) {
        foreach ($article_body as $_body) {
            $body[] = [
                'body_type' => $_body['type'],
                'body_content' => ($_body['type'] == 'text' and !empty($_body['content'])) ? $_body['content'] : '',
                'body_photo_url' => ($_body['type'] == 'image' and !empty($_body['url'])) ? $_body['url'] : '',
                'body_video_type' => ($_body['type'] == 'video') ? (!empty($_body['video_id']) ? 'id' : 'url') : '',
                'body_video_content' => ($_body['type'] == 'video') ? (!empty($_body['video_id']) ? $_body['video_id'] : $_body['url']) : '',
                'body_product_id' => ($_body['type'] == 'product' and !empty($_body['id'])) ? $_body['id'] : '',
                'body_caption' => ($_body['type'] == 'image' and !empty($_body['caption'])) ? $_body['caption'] : '',
                'body_thumb' => ($_body['type'] == 'video' and !empty($_body['thumb'])) ? $_body['thumb'] : ''
            ];
        }
    }

    return !empty($body) ? json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : '';
}

/**
 * Lưu bài viết sau khi gửi lên Zalo
 * 
 * save_article()
 * 
 * @param mixed $save_article 
 * @return mixed 
 */
function save_article($save_article)
{
    global $db;

    $data = [
        'token' => $save_article['token'],
        'type' => $save_article['type'],
        'title' => $save_article['title'],
        'author' => !empty($save_article['author']) ? $save_article['author'] : '',
        'cover_type' => !empty($save_article['cover']['cover_type']) ? $save_article['cover']['cover_type'] : '',
        'cover_photo_url' => !empty($save_article['cover']['photo_url']) ? $save_article['cover']['photo_url'] : '',
        'cover_video_id' => !empty($save_article['cover']['video_id']) ? $save_article['cover']['video_id'] : '',
        'cover_view' => !empty($save_article['cover']['cover_view']) ? $save_article['cover']['cover_view'] : '',
        'cover_status' => !empty($save_article['cover']['status']) ? $save_article['cover']['status'] : 'hide',
        'description' => !empty($save_article['description']) ? $save_article['description'] : '',
        'body' => !empty($save_article['body']) ? article_body_create($save_article['body']) : '',
        'related_medias' => !empty($save_article['related_medias']) ? json_encode($save_article['related_medias']) : '',
        'tracking_link' => !empty($save_article['tracking_link']) ? $save_article['tracking_link'] : '',
        'video_id' => !empty($save_article['video_id']) ? $save_article['video_id'] : '',
        'video_avatar' => !empty($save_article['avatar']) ? $save_article['avatar'] : '',
        'status' => !empty($save_article['status']) ? $save_article['status'] : 'show',
        'comment' => !empty($save_article['comment']) ? $save_article['comment'] : 'show'
    ];

    $sql = 'INSERT INTO ' . NV_MOD_TABLE . '_article 
        (token, type, title, author, cover_type, cover_photo_url, cover_video_id, cover_view, cover_status, description, body, related_medias, tracking_link, video_id, video_avatar, status, comment, create_date, update_date, is_sync) VALUES 
        (:token, :type, :title, :author, :cover_type, :cover_photo_url, :cover_video_id, :cover_view, :cover_status, :description, :body, :related_medias, :tracking_link, :video_id, :video_avatar, :status, :comment, ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', 1)';

    return $db->insert_id($sql, 'id', $data);
}

/**
 * Lưu bài viết sau khi cập nhật lên Zalo
 * 
 * update_article()
 * 
 * @param mixed $id 
 * @param mixed $save_article 
 * @throws PDOException 
 */
function update_article($id, $save_article)
{
    global $db;

    $data = [
        'title' => $save_article['title'],
        'author' => !empty($save_article['author']) ? $save_article['author'] : '',
        'cover_type' => !empty($save_article['cover']['cover_type']) ? $save_article['cover']['cover_type'] : '',
        'cover_photo_url' => !empty($save_article['cover']['photo_url']) ? $save_article['cover']['photo_url'] : '',
        'cover_video_id' => !empty($save_article['cover']['video_id']) ? $save_article['cover']['video_id'] : '',
        'cover_view' => !empty($save_article['cover']['cover_view']) ? $save_article['cover']['cover_view'] : '',
        'cover_status' => !empty($save_article['cover']['status']) ? $save_article['cover']['status'] : 'hide',
        'description' => !empty($save_article['description']) ? $save_article['description'] : '',
        'body' => !empty($save_article['body']) ? article_body_create($save_article['body']) : '',
        'related_medias' => !empty($save_article['related_medias']) ? json_encode($save_article['related_medias']) : '',
        'tracking_link' => !empty($save_article['tracking_link']) ? $save_article['tracking_link'] : '',
        'video_id' => !empty($save_article['video_id']) ? $save_article['video_id'] : '',
        'video_avatar' => !empty($save_article['avatar']) ? $save_article['avatar'] : '',
        'status' => !empty($save_article['status']) ? $save_article['status'] : 'show',
        'comment' => !empty($save_article['comment']) ? $save_article['comment'] : 'show'
    ];

    $sth = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_article SET 
    title = :title, author = :author, cover_type = :cover_type, cover_photo_url = :cover_photo_url, 
    cover_video_id = :cover_video_id, cover_view = :cover_view, cover_status = :cover_status, description = :description,
    body = :body, related_medias = :related_medias, tracking_link = :tracking_link, video_id = :video_id, 
    video_avatar = :video_avatar, status = :status, comment = :comment, update_date = ' . NV_CURRENTTIME . ', is_sync = 1 WHERE id = ' . $id);
    $sth->bindParam(':title', $data['title'], PDO::PARAM_STR);
    $sth->bindParam(':author', $data['author'], PDO::PARAM_STR);
    $sth->bindParam(':cover_type', $data['cover_type'], PDO::PARAM_STR);
    $sth->bindParam(':cover_photo_url', $data['cover_photo_url'], PDO::PARAM_STR);
    $sth->bindParam(':cover_video_id', $data['cover_video_id'], PDO::PARAM_STR);
    $sth->bindParam(':cover_view', $data['cover_view'], PDO::PARAM_STR);
    $sth->bindParam(':cover_status', $data['cover_status'], PDO::PARAM_STR);
    $sth->bindParam(':description', $data['description'], PDO::PARAM_STR);
    $sth->bindParam(':body', $data['body'], PDO::PARAM_STR);
    $sth->bindParam(':related_medias', $data['related_medias'], PDO::PARAM_STR);
    $sth->bindParam(':tracking_link', $data['tracking_link'], PDO::PARAM_STR);
    $sth->bindParam(':video_id', $data['video_id'], PDO::PARAM_STR);
    $sth->bindParam(':video_avatar', $data['video_avatar'], PDO::PARAM_STR);
    $sth->bindParam(':status', $data['status'], PDO::PARAM_STR);
    $sth->bindParam(':comment', $data['comment'], PDO::PARAM_STR);
    $sth->execute();
}

/**
 * Lấy danh sách bài viếstc hưa được cập nhật thông tin từ Zalo
 * 
 * get_article_not_sync()
 * 
 * @return array 
 */
function get_article_not_sync()
{
    global $db;

    $list = [];
    $sql = 'SELECT id, zalo_id FROM ' . NV_MOD_TABLE . '_article WHERE is_sync = 0';
    $result = $db->query($sql);

    while ($row = $result->fetch()) {
        $list[$row['id']] = $row['zalo_id'];
    }

    return $list;
}

/**
 * Kiểm tra sự tồn tại của người quan tâm
 * 
 * userExists()
 * 
 * @param mixed $user_id 
 * @return mixed 
 */
function userExists($user_id)
{
    global $db;

    $sql = 'SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_followers WHERE user_id=' . $db->quote($user_id);

    return $db->query($sql)->fetchColumn();
}

/**
 * Kiểm tra sự tồn tại của tin nhắn
 * 
 * messExists()
 * 
 * @param mixed $message_id 
 * @param mixed $src 
 * @return mixed 
 */
function messExists($message_id, $src)
{
    global $db;

    $src = !empty($src) ? 1 : 0;

    $sql = 'SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_conversation WHERE message_id=' . $db->quote($message_id) . ' AND src=' . $src;

    return $db->query($sql)->fetchColumn();
}

/**
 * get_error_zalo_image()
 * 
 * @param mixed $mime 
 * @param mixed $size 
 * @return mixed 
 */
function get_error_zalo_image($mime, $size)
{
    global $lang_module;

    if (empty($mime)) {
        return $lang_module['image_is_invalid'];
    }

    if (!preg_match('/^image\/(x\-)*(png|jpe?g)$/', $mime)) {
        return $lang_module['extension_not_supported'];
    }

    if ($size > 1048576) {
        return $lang_module['max_capacity_exceeded'];
    }

    return '';
}

/**
 * get_error_image()
 * 
 * @param mixed $image_url 
 * @return mixed 
 */
function get_error_image($image_url)
{
    global $lang_module;

    $isURL = nv_is_url($image_url);
    $fromSite = nv_is_file($image_url, NV_UPLOADS_DIR . '/zalo');

    if (!$isURL and !$fromSite) {
        return $lang_module['image_url_invalid'];
    }

    if ($isURL) {
        $data = file_get_contents($image_url);
        $imginfo = @getimagesizefromstring($data);
        $size = strlen($data);
    } else {
        $imginfo = @getimagesize(NV_ROOTDIR . $image_url);
        $size = filesize(NV_ROOTDIR . $image_url);
    }

    return get_error_zalo_image($imginfo['mime'], $size);
}

/**
 * zaloGetError()
 * 
 * @return mixed 
 */
function zaloGetError()
{
    global $zalo, $lang_module;

    $error = $zalo->getError();
    $error_code = $zalo->getErrorCode();
    if (!empty($lang_module[$error])) {
        $error = $lang_module[$error];
    }
    if (!empty($error_code)) {
        if (!empty($lang_module['error' . $error_code])) {
            $error = $lang_module['error' . $error_code] . ' (' . $error . ')';
        } else {
            $error = $lang_module['error_code'] . ' ' . $error_code . ' (' . $error . ')';
        }
    }

    return $error;
}

/**
 * Hiển thị danh sách các đơn vị hành chính Việt Nam
 * 
 * vnsubdivisions_to_html()
 * 
 * @param mixed $provinces 
 * @param mixed $data 
 * @param mixed $subdivParent 
 * @return string 
 */
function vnsubdivisions_to_html($provinces, $data, $subdivParent)
{
    global $global_config, $op, $module_name, $module_file, $lang_global, $lang_module;

    $xtpl = new XTemplate('settings.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('PARENT', $subdivParent);

    foreach ($provinces as $code => $names) {
        $xtpl->assign('PROVINCE', [
            'code' => $code,
            'sel' => $code == $subdivParent ? ' selected="selected"' : '',
            'name' => sprintf($lang_module['vnsubdivisions_parent'], $names[0])
        ]);
        $xtpl->parse('vnsubdivisions_page.province');
    }

    $i = 0;
    foreach ($data as $code => $names) {
        ++$i;
        $mainname = array_shift($names);
        $xtpl->assign('SUBDIV', [
            'tt' => $i,
            'code' => $code,
            'code_format' => (!empty($subdivParent) ? $subdivParent . '-' : '') . $code,
            'mainname' => $mainname
        ]);

        if (empty($names)) {
            $names = [''];
        }

        foreach ($names as $othername) {
            $xtpl->assign('OTHER_NAME', $othername);
            $xtpl->parse('vnsubdivisions_page.loop.other_name');
        }
        $xtpl->parse('vnsubdivisions_page.loop');
    }

    $xtpl->parse('vnsubdivisions_page');

    return $xtpl->text('vnsubdivisions_page');
}

/**
 * Hiển thị danh sách các mã gọi của các quốc gia
 * 
 * callingcodes_to_html()
 * 
 * @param mixed $callingcodes 
 * @return string 
 */
function callingcodes_to_html($callingcodes)
{
    global $global_config, $op, $module_name, $module_file, $lang_global, $lang_module;

    $xtpl = new XTemplate('settings.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);

    $countries = [];
    foreach ($callingcodes as $country) {
        !isset($countries[$country[1]]) && $countries[$country[1]] = [];
        $countries[$country[1]][] = $country[0];
    }

    foreach ($countries as $code => $callcodes) {
        $xtpl->assign('COUNTRY', [
            'code' => $code,
            'name' => isset($lang_global['country_' . $code]) ? $lang_global['country_' . $code] : $code
        ]);

        foreach ($callcodes as $callcode) {
            $xtpl->assign('CALLCODE', $callcode);
            $xtpl->parse('callingcodes_page.loop.callcode');
        }
        $xtpl->parse('callingcodes_page.loop');
    }

    $xtpl->parse('callingcodes_page');

    return $xtpl->text('callingcodes_page');
}

/**
 * DOMinnerHTML()
 * 
 * @param mixed $element 
 * @return string 
 */
function DOMinnerHTML($element)
{
    $innerHTML = '';
    $children = $element->childNodes;
    foreach ($children as $child) {
        $tmp_dom = new DOMDocument();
        $tmp_dom->appendChild($tmp_dom->importNode($child, true));
        $innerHTML .= trim($tmp_dom->saveHTML());
    }

    return $innerHTML;
}

/**
 * Lấy thông tin thêm từ trang chủ của OA
 * 
 * parse_OA_info()
 * 
 * @return array 
 */
function parse_OA_info()
{
    global $global_config;

    $info = [];

    $contents = @file_get_contents('https://oa.zalo.me/' . $global_config['zaloOfficialAccountID']);
    if (!empty($contents)) {
        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        if ($dom->loadHTML($contents)) {
            $xpath = new DOMXPath($dom);
            $nodeList = $xpath->query('//div[@class="oa-info-wrapper"]/div[@class="desc-more"]');
            if ($nodeList->length > 0) {
                foreach ($nodeList as $node) {
                    $dom_i = new DOMDocument();
                    if ($dom_i->loadHTML(DOMinnerHTML($node))) {
                        $xpath_i = new DOMXPath($dom_i);
                        $nodeLis_i = $xpath_i->query('//*[@class="label"]');
                        if ($nodeLis_i->length) {
                            $skey = trim(html_entity_decode(DOMinnerHTML($nodeLis_i[0]), ENT_QUOTES, 'UTF-8'));
                            $skey = strtolower(change_alias($skey));
                            $nodeLis_byid = $xpath_i->query('//*[@id="fullDesc"]');
                            $nodeLis_byclass = $xpath_i->query('//*[contains(@class, "ctn-label")]');
                            if ($nodeLis_byid->length) {
                                $info['description'] = trim(html_entity_decode($nodeLis_byid->item(0)->getAttribute('value'), ENT_QUOTES, 'UTF-8'));
                            } elseif ($nodeLis_byclass->length) {
                                switch ($skey) {
                                    case 'dia-chi':
                                        $k = 'address';
                                        break;
                                    case 'danh-muc':
                                        $k = 'category';
                                        break;
                                    default:
                                        $k = $skey;
                                }
                                $info[$k] = trim(html_entity_decode($nodeLis_byclass->item(0)->nodeValue, ENT_QUOTES, 'UTF-8'));
                            }
                        }
                    }
                }
            }

            $nodeList = $xpath->query('//div[@class="qrcode"]/img');
            if ($nodeList->length) {
                $info['qrcode'] = trim($nodeList->item(0)->getAttribute('src'));
            }
        }
    }

    if (!empty($info['hotline'])) {
        $info['hotline'] = parse_phone($info['hotline']);
        $info['hotline'] = substr($info['hotline'][0], 2) . $info['hotline'][1];
    }

    return $info;
}

/**
 * oa_qrcode_create()
 * 
 * @return string|false 
 * @throws Exception 
 * @throws ColorException 
 */
function oa_qrcode_create()
{
    global $global_config;

    $qrcode_url = 'http://zalo.me/' . $global_config['zaloOfficialAccountID'] . '?src=qr&f=1';
    $qrcode_file = NV_UPLOADS_DIR . '/zalo/qrcode_' . $global_config['zaloOfficialAccountID'] . '.png';
    $barcode = new Com\Tecnick\Barcode\Barcode();
    $bobj = $barcode->getBarcodeObj(
        'QRCODE,H',
        $qrcode_url,
        160,
        160,
        'black',
        [5, 5, 5, 5]
    )->setBackgroundColor('white');
    $data = $bobj->getPngData();
    if (@file_put_contents(NV_ROOTDIR . '/' . $qrcode_file, $data, LOCK_EX)) {
        return NV_BASE_SITEURL . $qrcode_file;
    }

    return false;
}

// Lay danh sach cac quan/huyen theo tinh/thanh pho
if ($nv_Request->isset_request('get_districts,city_id', 'post')) {
    $city_id = $nv_Request->get_string('city_id', 'post', '');
    echo get_districts($city_id);
    exit;
}

// Lay access token
if ($nv_Request->isset_request('get_accesstoken', 'post')) {
    get_accesstoken($accesstoken, true);
    echo $accesstoken;
    exit();
}
