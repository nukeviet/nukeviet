<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$mod_name = $nv_Request->get_title('module_name', 'post', '');
$mod_upload = $site_mods[$mod_name]['module_upload'];
$path = nv_check_path_upload(NV_UPLOADS_DIR . '/' . $mod_upload);
$check_allow_upload_dir = nv_check_allow_upload_dir($path);

$data = $nv_Request->get_string('data', 'post', '');

if (isset($check_allow_upload_dir['upload_file']) and in_array('images', $admin_info['allow_files_type'], true) and preg_match_all('/<\s*img [^\>]*src\s*=\s*([\""\']?)([^\""\'>]*)([\""\']?)/i', $data, $matches_all)) {
    $imageMatch = array_unique($matches_all[2]);

    $pathsave = $nv_Request->get_title('pathsave', 'post', '');
    $upload_real_dir_page = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $mod_upload;
    $pathsave = preg_replace('#^' . NV_UPLOADS_DIR . '/' . $mod_upload . '#', '', $pathsave);
    $pathsave = trim($pathsave, '/');
    if (!empty($pathsave)) {
        if (!preg_match('/^[a-z0-9\-\_]+$/i', $module_name)) {
            $pathsave = change_alias($pathsave);
        }
        $pathsave = $mod_upload . '/' . $pathsave;

        $e = explode('/', $pathsave);
        if (!empty($e)) {
            $cp = '';
            foreach ($e as $p) {
                if (!empty($p) and !is_dir(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $cp . $p)) {
                    $mk = nv_mkdir(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $cp, $p);
                    if ($mk[0] > 0) {
                        $upload_real_dir_page = $mk[2];
                    }
                } elseif (!empty($p)) {
                    $upload_real_dir_page = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $cp . $p;
                }
                $cp .= $p . '/';
            }
        }
    }

    foreach ($imageMatch as $imageSrc) {
        if (nv_check_url($imageSrc)) {
            $_image = new NukeViet\Files\Image($imageSrc);
            if ($_image->fileinfo['width'] > 50) {
                if ($_image->fileinfo['width'] > NV_MAX_WIDTH) {
                    $_image->resizeXY(NV_MAX_WIDTH, NV_MAX_HEIGHT);
                }

                $basename = explode('.', basename($imageSrc));
                array_pop($basename);
                $basename = implode('-', $basename);
                $basename = preg_replace('/^\W+|\W+$/', '', $basename);
                $basename = preg_replace('/[ ]+/', '_', $basename);
                $basename = strtolower(preg_replace('/\W-/', '', $basename));
                $basename .= '.' . $_image->fileinfo['ext'];

                $thumb_basename = $basename;
                $i = 1;
                while (file_exists($upload_real_dir_page . '/' . $thumb_basename)) {
                    $thumb_basename = preg_replace('/(.*)(\.[a-zA-Z]+)$/', '\1_' . $i . '\2', $basename);
                    ++$i;
                }

                $_image->save($upload_real_dir_page, $thumb_basename);
                $image_path = $_image->create_Image_info['src'];
                if (!empty($image_path) and file_exists($image_path)) {
                    $new_imageSrc = str_replace(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $mod_upload . '/', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $mod_upload . '/', $image_path);
                    foreach ($matches_all[2] as $img_id => $img_Src) {
                        if ($imageSrc == $img_Src) {
                            $_html_img = str_replace($imageSrc, $new_imageSrc, $matches_all[0][$img_id]);
                            $data = str_replace($matches_all[0][$img_id], $_html_img, $data);
                        }
                    }
                }
            }
        }
    }
}

include NV_ROOTDIR . '/includes/header.php';
echo $data;
include NV_ROOTDIR . '/includes/footer.php';
