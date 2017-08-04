<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (!defined('NV_IS_MOD_USER')) {
    die('Stop!!!');
}

if (!defined('NV_IS_ADMIN')) {
    if (!defined('NV_IS_USER') or !$global_config['allowuserlogin']) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    if ((int) $user_info['safemode'] > 0) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo');
    }
}

/**
 * updateAvatar()
 *
 * @param mixed $file
 * @return void
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
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET photo=:photo WHERE userid=' . $user_info['userid']);
        $stmt->bindParam(':photo', $photo, PDO::PARAM_STR);
        $stmt->execute();
    }

    nv_deletefile($tmp_photo);
}

/**
 * deleteAvatar()
 *
 * @return void
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

        $stmt = $db->prepare("UPDATE " . NV_MOD_TABLE . " SET photo='' WHERE userid=" . $user_info['userid']);
        $stmt->execute();
    }
}

$page_title = $lang_module['avatar_pagetitle'];

$array = array();
$array['success'] = 0;
$array['error'] = '';
$array['u'] = (isset($array_op[1]) and ($array_op[1] == 'upd' or $array_op[1] == 'opener' or $array_op[1] == 'src')) ? $array_op[1] : '';
$array['checkss'] = NV_CHECK_SESSION;
$checkss = $nv_Request->get_title('checkss', 'post', '');

//Xoa avatar
if ($checkss == $array['checkss'] and $nv_Request->isset_request('del', 'post')) {
    deleteAvatar();
    nv_jsonOutput(array(
        'status' => 'ok',
        'input' => 'ok',
        'mess' => $lang_module['editinfo_ok']
    ));
}

$global_config['avatar_width'] = $global_users_config['avatar_width'];
$global_config['avatar_height'] = $global_users_config['avatar_height'];

if (isset($_FILES['image_file']) and is_uploaded_file($_FILES['image_file']['tmp_name']) and !empty($array['u'])) {
    // Get post data
    $array['crop_x'] = $nv_Request->get_int('crop_x', 'post', 0);
    $array['crop_y'] = $nv_Request->get_int('crop_y', 'post', 0);
    $array['crop_width'] = $array['avatar_width'] = $nv_Request->get_int('crop_width', 'post', 0);
    $array['crop_height'] = $array['avatar_height'] = $nv_Request->get_int('crop_height', 'post', 0);

    if ($array['avatar_width'] < $global_config['avatar_width'] or $array['avatar_height'] < $global_config['avatar_height']) {
        $array['error'] = $lang_module['avatar_error_data'];
    } else {
        $upload = new NukeViet\Files\Upload(array(
            'images'
        ), $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
        $upload->setLanguage($lang_global);

        // Storage in temp dir
        $upload_info = $upload->save_file($_FILES['image_file'], NV_ROOTDIR . '/' . NV_TEMP_DIR, false);

        // Delete upload tmp
        @unlink($_FILES['image_file']['tmp_name']);

        if (empty($upload_info['error'])) {
            $basename = $upload_info['basename'];
            $basename = preg_replace('/(.*)(\.[a-zA-Z]+)$/', '\1_' . nv_genpass(8) . '_' . $user_info['userid'] . '\2', $basename);

            $image = new NukeViet\Files\Image($upload_info['name'], NV_MAX_WIDTH, NV_MAX_HEIGHT);

            // Resize image, crop image
            //$image->resizeXY($array['avatar_width'], $array['avatar_height']);
            $image->cropFromLeft($array['crop_x'], $array['crop_y'], $array['avatar_width'], $array['avatar_height']);
            $image->resizeXY($global_config['avatar_width'], $global_config['avatar_height']);

            // Save new image
            $image->save(NV_ROOTDIR . '/' . NV_TEMP_DIR, $basename);
            $image->close();

            if (file_exists($image->create_Image_info['src'])) {
                $array['filename'] = str_replace(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/', '', $image->create_Image_info['src']);

                if ($array['u'] == 'upd') {
                    updateAvatar($array['filename']);
                    $array['success'] = 2;
                } elseif ($array['u'] == 'src') {
                    updateAvatar($array['filename']);
                    $array['filename'] = NV_BASE_SITEURL . SYSTEM_UPLOADS_DIR . '/' . $module_upload . '/' . $array['filename'];
                    $array['success'] = 3;
                } else {
                    $array['success'] = 1;
                }
            } else {
                $array['error'] = $lang_module['avatar_error_save'];
            }
            @nv_deletefile($upload_info['name']);
        } else {
            $array['error'] = $upload_info['error'];
        }
    }
}

$contents = nv_avatar($array);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents, false);
include NV_ROOTDIR . '/includes/footer.php';