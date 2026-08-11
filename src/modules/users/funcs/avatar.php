<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

$array = [];
$array['u'] = (isset($array_op[1]) and ($array_op[1] == 'upd' or $array_op[1] == 'opener' or $array_op[1] == 'src')) ? $array_op[1] : '';

$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

if (!empty($array['u'])) {
    $page_url .= '/' . $array['u'];
}

// Chuyển hướng sau khi đổi avatar. Chỉ dùng cho forum
$nv_redirect = '';
if ($nv_Request->isset_request('nv_redirect', 'post,get')) {
    // Luồng nv_redirect dùng báo cáo chuyển hướng sang forum
    $nv_redirect = nv_get_redirect();
    if ($nv_Request->isset_request('nv_redirect', 'get') and !empty($nv_redirect)) {
        $nv_Request->set_Session('nv_redirect_' . $module_data, $nv_redirect);
    }
} elseif ($nv_Request->isset_request('sso_redirect', 'get')) {
    // Luồng sso báo cáo chuyển hướng sang client sau khi đổi avatar
    $sso_redirect = $nv_Request->get_title('sso_redirect', 'get', '');
    if (!empty($sso_redirect)) {
        $nv_Request->set_Session('sso_redirect_' . $module_data, $sso_redirect);
    }
}
if (!empty($nv_redirect)) {
    $page_url .= '&amp;nv_redirect=' . $nv_redirect;
}

if (defined('NV_IS_USER_FORUM')) {
    require NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/avatar.php';
    exit();
}

if (!defined('NV_IS_ADMIN')) {
    if (!defined('NV_IS_USER') or !$global_config['allowuserlogin']) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    if ((int) $user_info['safemode'] > 0) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo');
    }
}

$array['checkss'] = csrf_create($g_csrf_key['avatar']);

// Kiểm tra CSRF ngay khi có checkss post
if (isset($_POST['checkss']) && !csrf_check($nv_Request->get_string('checkss', 'post', ''), $g_csrf_key['avatar'])) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

/**
 * updateAvatar()
 *
 * @param string $file
 * @throws PDOException
 */
function updateAvatar($file)
{
    global $db, $user_info, $module_upload;

    $tmp_photo = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $file;
    $new_photo_path = NV_ROOTDIR . '/' . SYSTEM_UPLOADS_DIR . '/' . $module_upload . '/';
    $new_photo_name = $file;
    $i = 1;
    while (file_exists($new_photo_path . $new_photo_name)) {
        $new_photo_name = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $file);
        ++$i;
    }

    if (nv_copyfile($tmp_photo, $new_photo_path . $new_photo_name)) {
        $sql = 'SELECT photo FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $user_info['userid'];
        $result = $db->query($sql);
        $oldAvatar = $result->fetchColumn();
        $result->closeCursor();

        if (!empty($oldAvatar) and file_exists(NV_ROOTDIR . '/' . $oldAvatar)) {
            nv_deletefile(NV_ROOTDIR . '/' . $oldAvatar);
        }

        $photo = SYSTEM_UPLOADS_DIR . '/' . $module_upload . '/' . $new_photo_name;
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET photo=:photo, last_update=' . NV_CURRENTTIME . ' WHERE userid=' . $user_info['userid']);
        $stmt->bindParam(':photo', $photo, PDO::PARAM_STR);
        $stmt->execute();
    }

    nv_deletefile($tmp_photo);
}

/**
 * deleteAvatar()
 *
 * @throws PDOException
 */
function deleteAvatar()
{
    global $db, $user_info;

    $sql = 'SELECT photo FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $user_info['userid'];
    $result = $db->query($sql);
    $oldAvatar = $result->fetchColumn();
    $result->closeCursor();

    if (!empty($oldAvatar)) {
        if (file_exists(NV_ROOTDIR . '/' . $oldAvatar)) {
            nv_deletefile(NV_ROOTDIR . '/' . $oldAvatar);
        }

        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . " SET photo='', last_update=" . NV_CURRENTTIME . ' WHERE userid=' . $user_info['userid']);
        $stmt->execute();
    }
}

$page_title = $nv_Lang->getModule('avatar_pagetitle');

$array['client'] = '';
if (defined('SSO_CLIENT_DOMAIN')) {
    $allowed_client_origin = explode(',', SSO_CLIENT_DOMAIN);
    $array['client'] = $nv_Request->get_title('client', 'get,post', '');
    if (!empty($array['client']) and !in_array($array['client'], $allowed_client_origin, true)) {
        // 406 Not Acceptable
        nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 406);
    }
}
if (!empty($array['client'])) {
    $page_url .= '&amp;client=' . urlencode($array['client']);
}
$array['form_action'] = $page_url;

// Xóa ảnh avatar, cần isset($_POST['checkss']) để đảm bảo đã kiểm tra CSRF
if (isset($_POST['checkss']) && $nv_Request->isset_request('del', 'post')) {
    deleteAvatar();
    nv_jsonOutput([
        'status' => 'ok',
        'input' => 'ok',
        'mess' => $nv_Lang->getModule('editinfo_ok')
    ]);
}

$global_config['avatar_width'] = $global_users_config['avatar_width'];
$global_config['avatar_height'] = $global_users_config['avatar_height'];

// Xử lý upload ảnh avatar lên
if (isset($_POST['checkss'], $_FILES['image_file']) and is_uploaded_file($_FILES['image_file']['tmp_name']) and !empty($array['u'])) {
    // Tọa độ x,y bắt đầu cắt
    $array['crop_x'] = $nv_Request->get_int('crop_x', 'post', 0);
    $array['crop_y'] = $nv_Request->get_int('crop_y', 'post', 0);

    // Kích thước rộng, cao của vùng cắt
    $array['crop_width'] = $nv_Request->get_int('crop_width', 'post', 0);
    $array['crop_height'] = $nv_Request->get_int('crop_height', 'post', 0);

    // Xoay và kích thước ảnh sau khi xoay
    $array['crop_rotate'] = $nv_Request->get_float('crop_rotate', 'post', 0);
    $array['rotated_width'] = $nv_Request->get_int('rotated_width', 'post', 0);
    $array['rotated_height'] = $nv_Request->get_int('rotated_height', 'post', 0);

    // Kích thước ảnh gốc
    $array['source_width'] = $nv_Request->get_int('source_width', 'post', 0);
    $array['source_height'] = $nv_Request->get_int('source_height', 'post', 0);

    // Kiểm tra dữ liệu cắt ảnh có hợp lệ không
    if (
        $array['crop_width'] < 1 or $array['crop_height'] < 1
        or $array['rotated_width'] < 1 or $array['rotated_height'] < 1
        or $array['source_width'] < 1 or $array['source_height'] < 1
    ) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('avatar_error_data')
        ]);
    }

    $upload = new NukeViet\Files\Upload([
        'images'
    ], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE);
    $upload->setLanguage(\NukeViet\Core\Language::$lang_global);

    // Storage in temp dir
    $upload_info = $upload->save_file($_FILES['image_file'], NV_ROOTDIR . '/' . NV_TEMP_DIR, false);

    // Delete upload tmp
    if (file_exists($_FILES['image_file']['tmp_name'])) {
        unlink($_FILES['image_file']['tmp_name']);
    }

    // Lỗi upload
    if (!empty($upload_info['error'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $upload_info['error']
        ]);
    }

    $basename = $upload_info['basename'];
    $basename = preg_replace('/(.*)(\.[a-zA-Z]+)$/', '\1_' . nv_genpass(8) . '_' . $user_info['userid'] . '\2', $basename);

    $image = new NukeViet\Files\Image($upload_info['name']);

    /*
    * Trình duyệt tự áp EXIF orientation khi đo ảnh, Upload::save_file cũng
    * xoay lại file theo EXIF. Hai bên lệch nhau thì toạ độ cắt vô nghĩa nên
    * dừng lại thay vì cắt sai.
    */
    if (
        (int) $image->create_Image_info['width'] !== $array['source_width']
        or (int) $image->create_Image_info['height'] !== $array['source_height']
    ) {
        nv_deletefile($upload_info['name']);
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('avatar_error_data')
        ]);
    }

    // Xoay 0 độ thì trong này đã bỏ qua
    $image->rotate($array['crop_rotate']);

    /*
     * imagerotate làm tròn hộp bao khác công thức của JS nên lệch được
     * 1-2px, phải quy đổi toạ độ theo kích thước thật sau khi xoay.
     */
    $kx = $image->create_Image_info['width'] / $array['rotated_width'];
    $ky = $image->create_Image_info['height'] / $array['rotated_height'];

    $crop_sx = (int) round($array['crop_x'] * $kx);
    $crop_sy = (int) round($array['crop_y'] * $ky);
    $crop_sw = (int) round($array['crop_width'] * $kx);
    $crop_sh = (int) round($array['crop_height'] * $ky);

    /*
     * Quanh góc 45 độ khung cắt chạm đúng biên vùng ảnh nên chỉ cần sai
     * số làm tròn 1px là lọt điểm nền mà imagerotate() đổ vào góc. Thu
     * vào 1px mỗi cạnh, mắt không thấy nhưng chắc chắn không lộ nền.
     */
    if ($crop_sw > 4 and $crop_sh > 4 and abs(fmod($array['crop_rotate'], 360)) > 0.001) {
        $crop_sx += 1;
        $crop_sy += 1;
        $crop_sw -= 2;
        $crop_sh -= 2;
    }

    $image->cropResize(
        $crop_sx,
        $crop_sy,
        $crop_sw,
        $crop_sh,
        $global_config['avatar_width'],
        $global_config['avatar_height']
    );

    // Save new image
    $image->save(NV_ROOTDIR . '/' . NV_TEMP_DIR, $basename);
    $image->close();

    // Lỗi tạo ảnh
    if (!file_exists($image->create_Image_info['src'])) {
        nv_deletefile($upload_info['name']);
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('avatar_error_save')
        ]);
    }

    $array['filename'] = str_replace(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/', '', $image->create_Image_info['src']);
    $array['avatar_src'] = $array['filename'];
    if ($array['u'] == 'upd' or $array['u'] == 'src') {
        updateAvatar($array['filename']);
        $array['avatar_src'] = NV_BASE_SITEURL . SYSTEM_UPLOADS_DIR . '/' . $module_upload . '/' . $array['filename'];
    }

    nv_deletefile($upload_info['name']);

    $redirect = '';
    if (defined('SSO_REGISTER_SECRET')) {
        $redirect = $nv_Request->get_title('sso_redirect_' . $module_data, 'session', '');
        $redirect = NukeViet\Client\Sso::decrypt($redirect);
        $nv_Request->unset_request('sso_redirect_' . $module_data, 'session');

        // Redirect phải bắt đầu bằng $array['client'] để tránh redirect sang domain khác
        if (!empty($redirect) && (empty($array['client']) || !str_starts_with($redirect, $array['client']))) {
            $redirect = '';
        }
    }

    nv_jsonOutput([
        'status' => 'ok',
        'src' => $array['avatar_src'],
        'filename' => $array['filename'],
        'action' => $array['u'],
        'client' => $array['client'],
        'redirect' => $redirect
    ]);
}

$canonicalUrl = getCanonicalUrl($page_url);
$contents = nv_avatar($array);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents, false);
include NV_ROOTDIR . '/includes/footer.php';
