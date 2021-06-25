<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$path = nv_check_path_upload($nv_Request->get_string('path', 'post,get', NV_UPLOADS_DIR));
$check_allow_upload_dir = nv_check_allow_upload_dir($path);
$newfilename = change_alias($nv_Request->get_title('newfilename', 'post', ''));
$responseType = $nv_Request->get_title('responseType', 'get', '');
$autologo = $nv_Request->get_int('autologo', 'post', 0);

$chunk_upload = [];
$chunk_upload['name'] = $nv_Request->get_title('name', 'post', '');
$chunk_upload['chunk'] = $nv_Request->get_int('chunk', 'post', 0);
$chunk_upload['chunks'] = $nv_Request->get_int('chunks', 'post', 0);
$chunk_upload['tmpdir'] = NV_ROOTDIR . '/' . NV_TEMP_DIR;
$chunk_upload['chunk_prefix'] = NV_TEMPNAM_PREFIX . 'chunk' . md5($global_config['sitekey'] . NV_CLIENT_IP) . '_';

$error = '';
$upload_info = [];
$is_remote_upload = false;

if (!isset($check_allow_upload_dir['upload_file'])) {
    $error = $lang_module['notlevel'];
} elseif (!isset($_FILES, $_FILES['upload'], $_FILES['upload']['tmp_name']) and !$nv_Request->isset_request('fileurl', 'post')) {
    $error = $lang_module['uploadError1'];
} elseif (!isset($_FILES) and !nv_is_url($nv_Request->get_string('fileurl', 'post,get'))) {
    $error = $lang_module['uploadError2'];
} elseif (isset($_FILES['upload']) and $global_config['upload_chunk_size'] > 0 and $chunk_upload['chunks'] > 0 and (empty($chunk_upload['name']) or empty($_FILES['upload']['name']) or ($chunk_upload['name'] != $_FILES['upload']['name'] and $_FILES['upload']['name'] != 'blob') or $chunk_upload['chunk'] >= $chunk_upload['chunks'])) {
    $error = $lang_module['uploadError3'];
} else {
    $type = $nv_Request->get_string('type', 'post,get');

    if ($type == 'image' and in_array('images', $admin_info['allow_files_type'], true)) {
        $allow_files_type = [
            'images'
        ];
    } elseif ($type == 'flash' and in_array('flash', $admin_info['allow_files_type'], true)) {
        $allow_files_type = [
            'flash'
        ];
    } elseif (empty($type)) {
        $allow_files_type = $admin_info['allow_files_type'];
    } else {
        $allow_files_type = [];
    }

    $sys_max_size = $sys_max_size_local = min($global_config['nv_max_size'], nv_converttoBytes(ini_get('upload_max_filesize')), nv_converttoBytes(ini_get('post_max_size')));
    if ($global_config['nv_overflow_size'] > $sys_max_size and $global_config['upload_chunk_size'] > 0) {
        $sys_max_size_local = $global_config['nv_overflow_size'];
    }

    $upload = new NukeViet\Files\Upload($allow_files_type, $global_config['forbid_extensions'], $global_config['forbid_mimes'], [$sys_max_size, $sys_max_size_local], NV_MAX_WIDTH, NV_MAX_HEIGHT);
    $upload->setLanguage($lang_global);

    if (isset($_FILES['upload']['tmp_name']) and is_uploaded_file($_FILES['upload']['tmp_name'])) {
        // Upload Chunk (nhiều phần)
        if ($global_config['upload_chunk_size'] > 0 and $chunk_upload['chunks'] > 0) {
            $upload->setChunkOption($chunk_upload);
        }
        $upload_info = $upload->save_file($_FILES['upload'], NV_ROOTDIR . '/' . $path, false, $global_config['nv_auto_resize']);
    } else {
        $urlfile = rawurldecode(trim($nv_Request->get_string('fileurl', 'post')));
        $upload_info = $upload->save_urlfile($urlfile, NV_ROOTDIR . '/' . $path, false, $global_config['nv_auto_resize']);
        $is_remote_upload = true;
    }

    if (!empty($upload_info['error'])) {
        $error = $upload_info['error'];
    } elseif ($upload_info['complete'] and preg_match('#image\/[x\-]*([a-z]+)#', $upload_info['mime']) and !$upload_info['is_svg']) {
        if (isset($array_thumb_config[$path])) {
            $thumb_config = $array_thumb_config[$path];
        } else {
            $thumb_config = $array_thumb_config[''];
            $_arr_path = explode('/', $path);
            while (sizeof($_arr_path) > 1) {
                array_pop($_arr_path);
                $_path = implode('/', $_arr_path);
                if (isset($array_thumb_config[$_path])) {
                    $thumb_config = $array_thumb_config[$_path];
                    break;
                }
            }
        }

        if ($global_config['nv_auto_resize'] and ($upload_info['img_info'][0] > NV_MAX_WIDTH or $upload_info['img_info'][1] > NV_MAX_HEIGHT)) {
            $createImage = new NukeViet\Files\Image(NV_ROOTDIR . '/' . $path . '/' . $upload_info['basename'], $upload_info['img_info'][0], $upload_info['img_info'][1]);
            $createImage->resizeXY(NV_MAX_WIDTH, NV_MAX_HEIGHT);
            $createImage->save(NV_ROOTDIR . '/' . $path, $upload_info['basename'], $thumb_config['thumb_quality']);
            $createImage->close();
            $info = $createImage->create_Image_info;
            $upload_info['img_info'][0] = $info['width'];
            $upload_info['img_info'][1] = $info['height'];
            $upload_info['size'] = filesize(NV_ROOTDIR . '/' . $path . '/' . $upload_info['basename']);
        }

        if ($is_remote_upload and $upload_info['size'] > $sys_max_size) {
            nv_deletefile(NV_ROOTDIR . '/' . $path . '/' . $upload_info['basename']);
            $error = sprintf($lang_global['error_upload_max_user_size'], nv_convertfromBytes($sys_max_size));
        } elseif ($upload_info['size'] > $sys_max_size_local) {
            nv_deletefile(NV_ROOTDIR . '/' . $path . '/' . $upload_info['basename']);
            $error = sprintf($lang_global['error_upload_max_user_size'], nv_convertfromBytes($sys_max_size_local));
        } else {
            if ($upload_info['img_info'][0] > NV_MAX_WIDTH or $upload_info['img_info'][1] > NV_MAX_HEIGHT) {
                nv_deletefile(NV_ROOTDIR . '/' . $path . '/' . $upload_info['basename']);
                if ($upload_info['img_info'][0] > NV_MAX_WIDTH) {
                    $error = sprintf($lang_global['error_upload_image_width'], NV_MAX_WIDTH);
                } else {
                    $error = sprintf($lang_global['error_upload_image_height'], NV_MAX_HEIGHT);
                }
            } else {
                $autologomod = explode(',', $global_config['autologomod']);
                $dir = str_replace('\\', '/', $path);
                $dir = rtrim($dir, '/');
                $arr_dir = explode('/', $dir);

                if ($autologo and !empty($global_config['upload_logo']) and file_exists(NV_ROOTDIR . '/' . $global_config['upload_logo'])) {
                    $logo_size = getimagesize(NV_ROOTDIR . '/' . $global_config['upload_logo']);
                    $file_size = $upload_info['img_info'];

                    if ($file_size[0] <= 150) {
                        $w = ceil($logo_size[0] * $global_config['autologosize1'] / 100);
                    } elseif ($file_size[0] < 350) {
                        $w = ceil($logo_size[0] * $global_config['autologosize2'] / 100);
                    } else {
                        if (ceil($file_size[0] * $global_config['autologosize3'] / 100) > $logo_size[0]) {
                            $w = $logo_size[0];
                        } else {
                            $w = ceil($file_size[0] * $global_config['autologosize3'] / 100);
                        }
                    }

                    $h = ceil($w * $logo_size[1] / $logo_size[0]);
                    $x = $file_size[0] - $w - 5;
                    $y = $file_size[1] - $h - 5;

                    $config_logo = [];
                    $config_logo['w'] = $w;
                    $config_logo['h'] = $h;

                    $config_logo['x'] = $file_size[0] - $w - 5; // Horizontal: Right
                    $config_logo['y'] = $file_size[1] - $h - 5; // Vertical: Bottom

                    // Logo vertical
                    if (preg_match('/^top/', $global_config['upload_logo_pos'])) {
                        $config_logo['y'] = 5;
                    } elseif (preg_match('/^center/', $global_config['upload_logo_pos'])) {
                        $config_logo['y'] = round(($file_size[1] / 2) - ($h / 2));
                    }

                    // Logo horizontal
                    if (preg_match('/Left$/', $global_config['upload_logo_pos'])) {
                        $config_logo['x'] = 5;
                    } elseif (preg_match('/Center$/', $global_config['upload_logo_pos'])) {
                        $config_logo['x'] = round(($file_size[0] / 2) - ($w / 2));
                    }

                    $createImage = new NukeViet\Files\Image(NV_ROOTDIR . '/' . $path . '/' . $upload_info['basename'], NV_MAX_WIDTH, NV_MAX_HEIGHT);
                    $createImage->addlogo(NV_ROOTDIR . '/' . $global_config['upload_logo'], '', '', $config_logo);
                    $createImage->save(NV_ROOTDIR . '/' . $path, $upload_info['basename'], $thumb_config['thumb_quality']);
                }
                //remame with option new filename
                if (!empty($newfilename)) {
                    $i = 1;
                    $newfilename = $newfilename . '.' . $upload_info['ext'];
                    $newname2 = $newfilename;
                    while (file_exists(NV_ROOTDIR . '/' . $path . '/' . $newname2)) {
                        $newname2 = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $newfilename);
                        ++$i;
                    }
                    $newfilename = $newname2;
                    if (@rename(NV_ROOTDIR . '/' . $path . '/' . $upload_info['basename'], NV_ROOTDIR . '/' . $path . '/' . $newfilename)) {
                        $upload_info['basename'] = $newfilename;
                    }
                }
            }
        }
    }
}

$editor = $nv_Request->get_title('editor', 'post,get', '');

if (!preg_match("/^([a-zA-Z0-9\-\_]+)$/", $editor)) {
    $editor = '';
}

if (!empty($error)) {
    // Lỗi upload
    if ($responseType == 'json') {
        $array_data = [];
        $array_data['uploaded'] = 0;
        $array_data['error'] = [
            'message' => $error
        ];

        nv_jsonOutput($array_data);
    } elseif ($editor == 'ckeditor') {
        nv_jsonOutput([
            'uploaded' => 0,
            'error' => [
                'message' => $error
            ]
        ]);
    } else {
        echo 'ERROR_' . $error;
    }
} elseif (!empty($upload_info['complete'])) {
    // Upload hoàn thành
    if (isset($array_dirname[$path])) {
        $did = $array_dirname[$path];
        $info = nv_getFileInfo($path, $upload_info['basename']);
        $info['userid'] = $admin_info['userid'];

        $newalt = $nv_Request->get_title('filealt', 'post', '', true);

        if (empty($newalt)) {
            $newalt = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1', $upload_info['basename']);
            $newalt = str_replace('-', ' ', change_alias($newalt));
        }

        $sth = $db->prepare('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . "_file (
            name, ext, type, filesize, src, srcwidth, srcheight, sizes, userid, mtime, did, title, alt
        ) VALUES (
            '" . $info['name'] . "', '" . $info['ext'] . "', '" . $info['type'] . "', " . $info['filesize'] . ",
            '" . $info['src'] . "', " . $info['srcwidth'] . ', ' . $info['srcheight'] . ", '" . $info['size'] . "',
            " . $info['userid'] . ', ' . $info['mtime'] . ', ' . $did . ", '" . $upload_info['basename'] . "', :newalt
        )");

        $sth->bindParam(':newalt', $newalt, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['upload_file'], $path . '/' . $upload_info['basename'], $admin_info['userid']);

    if ($editor == 'ckeditor') {
        if ($responseType == 'json') {
            $array_data = [];
            $array_data['uploaded'] = 1;
            $array_data['fileName'] = $upload_info['basename'];
            $array_data['url'] = NV_BASE_SITEURL . $path . '/' . $upload_info['basename'];

            nv_jsonOutput($array_data);
        } else {
            nv_jsonOutput([
                'uploaded' => 1,
                'fileName' => $upload_info['basename'],
                'url' => NV_BASE_SITEURL . $path . '/' . $upload_info['basename']
            ]);
        }
    } else {
        echo $upload_info['basename'];
    }
}
    // Upload chunk hoàn thành
