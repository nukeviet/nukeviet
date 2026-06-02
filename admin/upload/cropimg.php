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

$path = nv_check_path_upload($nv_Request->get_string('path', 'post,get'));
$check_allow_upload_dir = nv_check_allow_upload_dir($path);

if (!isset($check_allow_upload_dir['delete_file'])) {
    exit('ERROR#' . $lang_module['notlevel']);
}

$file = htmlspecialchars(trim($nv_Request->get_string('file', 'post,get')), ENT_QUOTES);
$file = basename($file);

if (empty($file) or !nv_is_file(NV_BASE_SITEURL . $path . '/' . $file, $path)) {
    exit('ERROR#' . $lang_module['errorNotSelectFile'] . NV_ROOTDIR . '/' . $path . '/' . $file);
}

if ($nv_Request->isset_request('path', 'post') and $nv_Request->isset_request('x', 'post') and $nv_Request->isset_request('y', 'post')) {
    $config_logo = [];
    $config_logo['x'] = $nv_Request->get_int('x', 'post', 0);
    $config_logo['y'] = $nv_Request->get_int('y', 'post', 0);
    $config_logo['w'] = $nv_Request->get_int('w', 'post', 0);
    $config_logo['h'] = $nv_Request->get_int('h', 'post', 0);

    $keep_original = $nv_Request->get_int('k', 'post', 0);

    if ($config_logo['w'] > 0 and $config_logo['h'] > 0) {
        $createImage = new NukeViet\Files\Image(NV_ROOTDIR . '/' . $path . '/' . $file, NV_MAX_WIDTH, NV_MAX_HEIGHT);
        $createImage->cropFromLeft($config_logo['x'], $config_logo['y'], $config_logo['w'], $config_logo['h']);

        if ($keep_original) {
            $file_ext = nv_getextension($file);
            $file_old = $file = substr($file, 0, -(strlen($file_ext) + 1));
            $file_add = '-' . $config_logo['w'] . 'x' . $config_logo['h'];
            $i = 0;
            while (file_exists(NV_ROOTDIR . '/' . $path . '/' . $file . $file_add . '.' . $file_ext)) {
                ++$i;
                $file = $file_old . 'v' . $i;
            }
            $file = $file . $file_add . '.' . $file_ext;
        }

        $createImage->save(NV_ROOTDIR . '/' . $path, $file);
        $createImage->close();

        if (isset($array_dirname[$path])) {
            if (!$keep_original and preg_match('/^' . nv_preg_quote(NV_UPLOADS_DIR) . '\/(([a-z0-9\-\_\/]+\/)*([a-z0-9\-\_\.]+)(\.(gif|jpg|jpeg|png|bmp|webp)))$/i', $path . '/' . $file, $m)) {
                @nv_deletefile(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $m[1]);
                @nv_deletefile(NV_ROOTDIR . '/' . NV_MOBILE_FILES_DIR . '/' . $m[1]);
            }

            $info = nv_getFileInfo($path, $file);

            $did = $array_dirname[$path];

            if ($keep_original) {
                $newalt = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1', $file);
                $newalt = str_replace('-', ' ', change_alias($newalt));

                $stmt = $db->prepare('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . '_file (
                    name, ext, type, filesize, src, srcwidth, srcheight, sizes, userid, mtime, did, title, alt
                ) VALUES (
                    :name, :ext, :type, :filesize, :src, :srcwidth, :srcheight, :sizes, :userid, :mtime, :did, :title, :newalt
                )');
                $stmt->bindValue(':name', $info['name'], PDO::PARAM_STR);
                $stmt->bindValue(':ext', $info['ext'], PDO::PARAM_STR);
                $stmt->bindValue(':type', $info['type'], PDO::PARAM_STR);
                $stmt->bindValue(':newalt', $newalt, PDO::PARAM_STR);
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_UPLOAD_GLOBALTABLE . '_file SET
                    filesize = :filesize, src = :src, srcwidth = :srcwidth, srcheight = :srcheight,
                    sizes = :sizes, userid = :userid, mtime = :mtime
                WHERE did = :did AND title = :title');
            }
            $stmt->bindValue(':filesize', $info['filesize'], PDO::PARAM_INT);
            $stmt->bindValue(':src', $info['src'], PDO::PARAM_STR);
            $stmt->bindValue(':srcwidth', $info['srcwidth'], PDO::PARAM_INT);
            $stmt->bindValue(':srcheight', $info['srcheight'], PDO::PARAM_INT);
            $stmt->bindValue(':sizes', $info['size'], PDO::PARAM_STR);
            $stmt->bindValue(':userid', $admin_info['userid'], PDO::PARAM_INT);
            $stmt->bindValue(':mtime', $info['mtime'], PDO::PARAM_INT);
            $stmt->bindValue(':did', $did, PDO::PARAM_INT);
            $stmt->bindValue(':title', $file, PDO::PARAM_STR);
            $stmt->execute();

            nv_dirListRefreshSize();
        }

        exit('OK#' . basename($file));
    }
    exit('ERROR#' . $lang_module['notlevel']);
}

exit('ERROR#Error Access!!!');
