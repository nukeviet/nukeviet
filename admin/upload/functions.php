<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$menu_top = [
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $lang_global['mod_upload']
];

define('NV_IS_FILE_ADMIN', true);

//Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:upload';
$array_url_instruction['thumbconfig'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:upload:thumbconfig';
$array_url_instruction['config'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:upload:config';
$array_url_instruction['uploadconfig'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:upload:uploadconfig';

$allow_func = [
    'main', 'imglist', 'delimg', 'createimg',
    'dlimg', 'renameimg', 'moveimg', 'folderlist',
    'delfolder', 'renamefolder', 'createfolder',
    'upload', 'addlogo', 'cropimg', 'rotateimg', 'download'
];

if (defined('NV_IS_SPADMIN')) {
    $allow_func[] = 'thumbconfig';
    $allow_func[] = 'recreatethumb';
    $allow_func[] = 'config';

    if (defined('NV_IS_GODADMIN')) {
        $allow_func[] = 'uploadconfig';
    }
}

/**
 * nv_check_allow_upload_dir()
 *
 * @param mixed $dir
 * @return
 */
function nv_check_allow_upload_dir($dir)
{
    global $site_mods, $allow_upload_dir, $admin_info, $global_config;

    $dir = trim($dir);
    if (empty($dir)) {
        return [];
    }

    $dir = str_replace('\\', '/', $dir);
    $dir = rtrim($dir, '/');
    $arr_dir = explode('/', $dir);
    $level = [];
    $autologomod = explode(',', $global_config['autologomod']);

    if (defined('NV_CONFIG_DIR')) {
        if (NV_UPLOADS_DIR == $arr_dir[0] . '/' . $arr_dir[1]) {
            $_dir_mod = isset($arr_dir[2]) ? $arr_dir[2] : '';
            $_dir_mod_sub = isset($arr_dir[3]) ? $arr_dir[3] : '';
        } else {
            return $level;
        }
    } elseif (in_array($arr_dir[0], $allow_upload_dir, true)) {
        $_dir_mod = isset($arr_dir[1]) ? $arr_dir[1] : '';
        $_dir_mod_sub = isset($arr_dir[2]) ? $arr_dir[2] : '';
    } else {
        return $level;
    }

    $mod_name = '';
    foreach ($site_mods as $_mod_name_i => $_row_i) {
        if ($_row_i['module_upload'] == $_dir_mod) {
            $mod_name = $_mod_name_i;
            break;
        }
    }

    // Quyen cua dieu hanh toi cao va dieu hanh chung
    if (defined('NV_IS_SPADMIN')) {
        $level['view_dir'] = true;

        // Cho phep tao thu muc con
        if ($admin_info['allow_create_subdirectories']) {
            $level['create_dir'] = true;
        }

        // Cho phep doi ten, xoa thu muc
        if ($admin_info['allow_modify_subdirectories'] and !in_array($dir, $allow_upload_dir, true)) {
            $level['rename_dir'] = true;
            $level['delete_dir'] = true;

            // Khong doi ten, xoa thu muc upload cua module
            if (isset($site_mods[$mod_name]) and $dir == NV_UPLOADS_DIR . '/' . $mod_name) {
                unset($level['rename_dir'], $level['delete_dir']);
            }
        }

        // Cho phep upload file
        if (!empty($admin_info['allow_files_type'])) {
            $level['upload_file'] = true;
        }

        // Cho phep sua, xoa file
        if ($admin_info['allow_modify_files']) {
            $level['create_file'] = true;
            $level['recreatethumb'] = !empty($_dir_mod) ? true : false;
            $level['rename_file'] = true;
            $level['delete_file'] = true;
            $level['move_file'] = true;
            $level['crop_file'] = true;
            $level['rotate_file'] = true;
        }
    } elseif (isset($site_mods[$mod_name])) {
        $level['view_dir'] = true;

        if ($admin_info['allow_create_subdirectories']) {
            $level['create_dir'] = true;
        }

        if (!empty($_dir_mod_sub) and $admin_info['allow_modify_subdirectories']) {
            $level['rename_dir'] = true;
            $level['delete_dir'] = true;
            // Khong doi ten, xoa thu muc upload cua module hoac thu muc co chua thu muc con
            if (isset($site_mods[$mod_name]) and !empty($_dir_mod_sub)) {
                unset($level['rename_dir'], $level['delete_dir']);
            }
        }

        if (!empty($admin_info['allow_files_type'])) {
            $level['upload_file'] = true;
        }

        if ($admin_info['allow_modify_files']) {
            $level['create_file'] = true;
            $level['recreatethumb'] = false;
            $level['rename_file'] = true;
            $level['delete_file'] = true;
            $level['move_file'] = true;
            $level['crop_file'] = true;
            $level['rotate_file'] = true;
        }
    }

    // Tự động chèn logo
    $level['auto_logo'] = false;
    if ($global_config['autologomod'] == 'all' or ($arr_dir[0] == NV_UPLOADS_DIR and isset($arr_dir[1]) and in_array($arr_dir[1], $autologomod, true))) {
        $level['auto_logo'] = true;
    }

    return $level;
}

/**
 * nv_check_path_upload()
 *
 * @param mixed $path
 * @return
 */
function nv_check_path_upload($path)
{
    $path = htmlspecialchars(trim($path), ENT_QUOTES);
    $path = rtrim($path, '/');
    if (empty($path)) {
        return '';
    }

    $path = NV_ROOTDIR . '/' . $path;
    if (($path = realpath($path)) === false) {
        return '';
    }

    $path = str_replace('\\', '/', $path);
    $path = str_replace(NV_ROOTDIR . '/', '', $path);
    if (preg_match('/^' . nv_preg_quote(NV_UPLOADS_DIR) . '/', $path) or $path = NV_UPLOADS_DIR) {
        return $path;
    }

    return '';
}

/**
 * nv_create_mobileModeImage()
 *
 * @param mixed $fileName
 * @param bool  $replace
 * @return
 */
function nv_create_mobileModeImage($fileName, $replace = 0)
{
    if (preg_match('/^' . nv_preg_quote(NV_UPLOADS_DIR) . '\/(([a-z0-9\-\_\/]+\/)*([a-z0-9\-\_\.]+)(\.(gif|jpg|jpeg|png|bmp|ico|webp)))$/i', $fileName, $m)) {
        $mobileFile = NV_MOBILE_FILES_DIR . '/' . $m[1];
        if (file_exists(NV_ROOTDIR . '/' . $mobileFile)) {
            if ($replace) {
                @nv_deletefile(NV_ROOTDIR . '/' . $mobileFile);
            } else {
                return true;
            }
        }

        $image = new NukeViet\Files\Image(NV_ROOTDIR . '/' . $fileName, NV_MAX_WIDTH, NV_MAX_HEIGHT);
        $resize_maxW = NV_MOBILE_MODE_IMG;
        $resize_maxH = 0;

        if ($image->fileinfo['width'] > $resize_maxW) {
            $m[2] = rtrim($m[2], '/');
            $mobileDir = NV_MOBILE_FILES_DIR;
            if (!empty($m[2])) {
                if (!is_dir(NV_ROOTDIR . '/' . $m[2])) {
                    $e = explode('/', $m[2]);
                    $cp = NV_MOBILE_FILES_DIR;
                    foreach ($e as $p) {
                        if (is_dir(NV_ROOTDIR . '/' . $cp . '/' . $p)) {
                            $mobileDir .= '/' . $p;
                        } else {
                            $mk = nv_mkdir(NV_ROOTDIR . '/' . $cp, $p);
                            if ($mk[0] > 0) {
                                $mobileDir .= '/' . $p;
                            }
                        }
                        $cp .= '/' . $p;
                    }
                }
            }

            $image->resizeXY($resize_maxW, $resize_maxH);
            $image->save(NV_ROOTDIR . '/' . $mobileDir, $m[3] . $m[4], 90);
            $error = $image->error;
            $image->close();

            return empty($error);
        }

        $image->close();
    }

    return false;
}

/**
 * nv_get_viewImage()
 *
 * @param mixed $fileName
 * @return
 */
function nv_get_viewImage($fileName, $refresh = 0)
{
    global $array_thumb_config;

    if (preg_match('/^' . nv_preg_quote(NV_UPLOADS_DIR) . '\/(([a-z0-9\-\_\/]+\/)*([a-z0-9\-\_\.]+)(\.(gif|jpg|jpeg|png|bmp|ico|webp)))$/i', $fileName, $m)) {
        if (defined('NV_MOBILE_MODE_IMG') and NV_MOBILE_MODE_IMG > 150) {
            nv_create_mobileModeImage($fileName, $refresh);
        }

        $viewFile = NV_FILES_DIR . '/' . $m[1];

        if (file_exists(NV_ROOTDIR . '/' . $viewFile)) {
            if ($refresh) {
                @nv_deletefile(NV_ROOTDIR . '/' . $viewFile);
            } else {
                $size = @getimagesize(NV_ROOTDIR . '/' . $viewFile);

                return [
                    $viewFile,
                    $size[0],
                    $size[1]
                ];
            }
        }

        $m[2] = rtrim($m[2], '/');
        if (isset($array_thumb_config[NV_UPLOADS_DIR . '/' . $m[2]])) {
            $thumb_config = $array_thumb_config[NV_UPLOADS_DIR . '/' . $m[2]];
        } else {
            $thumb_config = $array_thumb_config[''];
            $_arr_path = explode('/', NV_UPLOADS_DIR . '/' . $m[2]);
            while (sizeof($_arr_path) > 1) {
                array_pop($_arr_path);
                $_path = implode('/', $_arr_path);
                if (isset($array_thumb_config[$_path])) {
                    $thumb_config = $array_thumb_config[$_path];
                    break;
                }
            }
        }

        $viewDir = NV_FILES_DIR;
        if (!empty($m[2])) {
            if (!is_dir(NV_ROOTDIR . '/' . $m[2])) {
                $e = explode('/', $m[2]);
                $cp = NV_FILES_DIR;
                foreach ($e as $p) {
                    if (is_dir(NV_ROOTDIR . '/' . $cp . '/' . $p)) {
                        $viewDir .= '/' . $p;
                    } else {
                        $mk = nv_mkdir(NV_ROOTDIR . '/' . $cp, $p);
                        if ($mk[0] > 0) {
                            $viewDir .= '/' . $p;
                        }
                    }
                    $cp .= '/' . $p;
                }
            }
        }
        $image = new NukeViet\Files\Image(NV_ROOTDIR . '/' . $fileName, NV_MAX_WIDTH, NV_MAX_HEIGHT);
        $resize_maxW = $thumb_config['thumb_width'];
        $resize_maxH = $thumb_config['thumb_height'];
        if ($thumb_config['thumb_type'] == 4 or $thumb_config['thumb_type'] == 5) {
            if (($image->fileinfo['width'] / $image->fileinfo['height']) > ($thumb_config['thumb_width'] / $thumb_config['thumb_height'])) {
                $resize_maxW = 0;
            } else {
                $resize_maxH = 0;
            }
        }

        if ($image->fileinfo['width'] > $resize_maxW or $image->fileinfo['height'] > $resize_maxH) {
            /*
             * Resize và crop theo kích thước luôn có một trong hai giá trị width hoặc height = 0
             * Có nghĩa luôn cho ra ảnh đúng cấu hình mặc cho ảnh gốc có nhỏ hơn ảnh thumb
             */
            $image->resizeXY($resize_maxW, $resize_maxH);
            if ($thumb_config['thumb_type'] == 4) {
                $image->cropFromCenter($thumb_config['thumb_width'], $thumb_config['thumb_height']);
            } elseif ($thumb_config['thumb_type'] == 5) {
                $image->cropFromTop($thumb_config['thumb_width'], $thumb_config['thumb_height']);
            }
            $image->save(NV_ROOTDIR . '/' . $viewDir, $m[3] . $m[4], $thumb_config['thumb_quality']);
            $create_Image_info = $image->create_Image_info;
            $error = $image->error;
            $image->close();
            if (empty($error)) {
                return [
                    $viewDir . '/' . basename($create_Image_info['src']),
                    $create_Image_info['width'],
                    $create_Image_info['height']
                ];
            }
        } elseif (copy(NV_ROOTDIR . '/' . $fileName, NV_ROOTDIR . '/' . $viewDir . '/' . $m[3] . $m[4])) {
            /**
             * Đối với kiểu resize ảnh khác nếu ảnh gốc nhỏ hơn ảnh resize
             * thì ảnh resize chính là ảnh gốc
             */
            $return = [
                $viewDir . '/' . $m[3] . $m[4],
                $image->fileinfo['width'],
                $image->fileinfo['height']
            ];
            $image->close();

            return $return;
        } else {
            return false;
        }
    } else {
        $size = @getimagesize(NV_ROOTDIR . '/' . $fileName);

        return [
            $fileName,
            $size[0],
            $size[1]
        ];
    }

    return false;
}

/**
 * nv_getFileInfo()
 *
 * @param mixed $pathimg
 * @param mixed $file
 * @return
 */
function nv_getFileInfo($pathimg, $file)
{
    global $array_images, $array_flash, $array_archives, $array_documents;

    clearstatcache();

    unset($matches);
    preg_match("/([a-zA-Z0-9\.\-\_\\s\(\)]+)\.([a-zA-Z0-9]+)$/", $file, $matches);

    $info = [];
    $info['name'] = $file;
    if (isset($file[17])) {
        $info['name'] = substr($matches[1], 0, (13 - strlen($matches[2]))) . '...' . $matches[2];
    }

    $info['ext'] = $matches[2];
    $info['type'] = 'file';

    $stat = @stat(NV_ROOTDIR . '/' . $pathimg . '/' . $file);
    $info['filesize'] = $stat['size'];

    $info['src'] = NV_ASSETS_DIR . '/images/file.gif';
    $info['srcwidth'] = 32;
    $info['srcheight'] = 32;
    $info['size'] = '|';
    $ext = strtolower($matches[2]);

    if (in_array($ext, $array_images, true)) {
        $size = @getimagesize(NV_ROOTDIR . '/' . $pathimg . '/' . $file);
        $info['type'] = 'image';
        $info['src'] = $pathimg . '/' . $file;
        $info['srcwidth'] = (int) ($size[0]);
        $info['srcheight'] = (int) ($size[1]);
        $info['size'] = (int) ($size[0]) . '|' . (int) ($size[1]);

        if (preg_match('/^' . nv_preg_quote(NV_UPLOADS_DIR) . '\/([a-z0-9\-\_\.\/]+)$/i', $pathimg . '/' . $file)) {
            if (($thub_src = nv_get_viewImage($pathimg . '/' . $file)) !== false) {
                $info['src'] = $thub_src[0];
                $info['srcwidth'] = $thub_src[1];
                $info['srcheight'] = $thub_src[2];
            }
        }

        if ($info['srcwidth'] > 80) {
            $info['srcheight'] = round(80 / $info['srcwidth'] * $info['srcheight']);
            $info['srcwidth'] = 80;
        }

        if ($info['srcheight'] > 80) {
            $info['srcwidth'] = round(80 / $info['srcheight'] * $info['srcwidth']);
            $info['srcheight'] = 80;
        }
    } elseif (in_array($ext, $array_flash, true)) {
        $info['type'] = 'flash';
        $info['src'] = NV_ASSETS_DIR . '/images/flash.gif';

        if ($matches[2] == 'swf') {
            $size = @getimagesize(NV_ROOTDIR . '/' . $pathimg . '/' . $file);
            if (isset($size, $size[0], $size[1])) {
                $info['size'] = $size[0] . '|' . $size[1];
            }
        }
    } elseif (in_array($ext, $array_archives, true)) {
        $info['src'] = NV_ASSETS_DIR . '/images/zip.gif';
    } elseif (in_array($ext, $array_documents, true)) {
        if ($ext == 'doc' or $ext == 'docx') {
            $info['src'] = NV_ASSETS_DIR . '/images/msword.png';
        } elseif ($ext == 'xls' or $ext == 'xlsx') {
            $info['src'] = NV_ASSETS_DIR . '/images/excel.png';
        } elseif ($ext == 'pdf') {
            $info['src'] = NV_ASSETS_DIR . '/images/pdf.png';
        } else {
            $info['src'] = NV_ASSETS_DIR . '/images/doc.gif';
        }
    } elseif ($ext == 'svg') {
        $info['type'] = 'image';
        if (($xml = @simplexml_load_file(NV_ROOTDIR . '/' . $pathimg . '/' . $file)) !== false) {
            $attr = $xml->attributes();
            $maxWidth = $maxHeight = $width = $height = 0;

            if (isset($attr['viewBox'])) {
                $viewBox = explode(' ', (string) $attr['viewBox']);
                if (isset($viewBox[3])) {
                    $maxWidth = (int) ($viewBox[2]);
                    $maxHeight = (int) ($viewBox[3]);
                }
            }

            if (isset($attr['width']) and isset($attr['height'])) {
                $width = (int) ($attr['width']);
                $height = (int) ($attr['height']);
            } else {
                $width = $maxWidth;
                $height = $maxHeight;
            }

            if ($width > 0 and $height > 0) {
                $info['src'] = $pathimg . '/' . $file;
                $info['srcwidth'] = $width;
                $info['srcheight'] = $height;
                $info['size'] = (int) $width . '|' . (int) $height;

                if ($info['srcwidth'] > 80) {
                    $info['srcheight'] = round(80 / $info['srcwidth'] * $info['srcheight']);
                    $info['srcwidth'] = 80;
                }

                if ($info['srcheight'] > 80) {
                    $info['srcwidth'] = round(80 / $info['srcheight'] * $info['srcwidth']);
                    $info['srcheight'] = 80;
                }
            }
        }
    }

    $info['userid'] = 0;
    $info['mtime'] = $stat['mtime'];

    return $info;
}

/**
 * nv_filesListRefresh()
 *
 * @param mixed $pathimg
 * @return
 */
function nv_filesListRefresh($pathimg)
{
    global $array_hidefolders, $admin_info, $db, $array_dirname;

    $results = [];
    $did = $array_dirname[$pathimg];
    if (is_dir(NV_ROOTDIR . '/' . $pathimg)) {
        $result = $db->query('SELECT * FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = ' . $did);
        while ($row = $result->fetch()) {
            $results[$row['title']] = $row;
        }

        if ($dh = opendir(NV_ROOTDIR . '/' . $pathimg)) {
            while (($title = readdir($dh)) !== false) {
                if (in_array($title, $array_hidefolders, true)) {
                    continue;
                }

                if (preg_match('/([a-zA-Z0-9\.\-\_\\s\(\)]+)\.([a-zA-Z0-9]+)$/', $title)) {
                    $info = nv_getFileInfo($pathimg, $title);
                    $info['did'] = $did;
                    $info['title'] = $title;
                    $info['sizes'] = $info['size'];
                    unset($info['size']);

                    if (isset($results[$title])) {
                        $info['userid'] = $results[$title]['userid'];
                        $dif = array_diff_assoc($info, $results[$title]);
                        if (!empty($dif)) {
                            // Cập nhật CSDL file thay đổi
                            $db->query('UPDATE ' . NV_UPLOAD_GLOBALTABLE . '_file SET filesize=' . (int) ($info['filesize']) . ", src='" . $info['src'] . "', srcwidth=" . (int) ($info['srcwidth']) . ', srcheight=' . (int) ($info['srcheight']) . ", sizes='" . $info['sizes'] . "', userid=" . $admin_info['userid'] . ', mtime=' . $info['mtime'] . ' WHERE did = ' . $did . ' AND title = ' . $db->quote($title));
                        }
                        unset($results[$title]);
                    } else {
                        $info['userid'] = $admin_info['userid'];
                        $newalt = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1', $title);
                        $newalt = str_replace('-', ' ', change_alias($newalt));

                        // Thêm file mới
                        $sth = $db->prepare('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . "_file
                            (name, ext, type, filesize, src, srcwidth, srcheight, sizes, userid, mtime, did, title, alt)
                            VALUES (:name, '" . $info['ext'] . "', '" . $info['type'] . "', " . (int) ($info['filesize']) . ", '" . $info['src'] . "', " . (int) ($info['srcwidth']) . ', ' . (int) ($info['srcheight']) . ", '" . $info['sizes'] . "', " . $info['userid'] . ', ' . $info['mtime'] . ', ' . $did . ', :title, :newalt)');
                        $sth->bindParam(':name', $info['name'], PDO::PARAM_STR);
                        $sth->bindParam(':title', $title, PDO::PARAM_STR);
                        $sth->bindParam(':newalt', $newalt, PDO::PARAM_STR);
                        $sth->execute();
                    }
                }
            }
            closedir($dh);

            if (!empty($results)) {
                // Xóa CSDL file không còn tồn tại
                foreach ($results as $_row) {
                    $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = ' . $did . ' AND title=' . $db->quote($_row['title']));
                }
            }
            $db->query('UPDATE ' . NV_UPLOAD_GLOBALTABLE . '_dir SET time = ' . NV_CURRENTTIME . ' WHERE did = ' . $did);
        }
    } else {
        // Xóa CSDL thư mục không còn tồn tại
        $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = ' . $did);
        $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir WHERE did = ' . $did);
    }
}

/**
 * nv_listUploadDir()
 *
 * @param mixed $dir
 * @param mixed $real_dirlist
 * @return
 */
function nv_listUploadDir($dir, $real_dirlist = [])
{
    $real_dirlist[] = $dir;

    if (($dh = @opendir(NV_ROOTDIR . '/' . $dir)) !== false) {
        while (false !== ($subdir = readdir($dh))) {
            if (preg_match('/^[a-zA-Z0-9\-\_]+$/', $subdir)) {
                if (is_dir(NV_ROOTDIR . '/' . $dir . '/' . $subdir)) {
                    $real_dirlist = nv_listUploadDir($dir . '/' . $subdir, $real_dirlist);
                }
            }
        }

        closedir($dh);
    }

    return $real_dirlist;
}

$allow_upload_dir = [NV_UPLOADS_DIR];
$array_hidefolders = ['.', '..', 'index.html', '.htaccess', '.tmp'];

$array_images = ['gif', 'jpg', 'jpeg', 'pjpeg', 'png', 'bmp', 'ico', 'webp'];
$array_flash = ['swf', 'swc', 'flv'];
$array_archives = ['rar', 'zip', 'tar'];
$array_documents = ['doc', 'xls', 'chm', 'pdf', 'docx', 'xlsx'];
$array_dirname = [];
$array_thumb_config = [];

$refresh = $nv_Request->isset_request('refresh', 'get');
$path = nv_check_path_upload($nv_Request->get_string('path', 'get', NV_UPLOADS_DIR));

$sql = 'SELECT * FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir ORDER BY dirname ASC';
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $array_dirname[$row['dirname']] = $row['did'];
    if ($row['thumb_type']) {
        $array_thumb_config[$row['dirname']] = $row;
    }
    if (empty($row['time']) and $row['dirname'] == $path) {
        $refresh = true;
    }
}
unset($array_dirname['']);

if ($nv_Request->isset_request('dirListRefresh', 'get')) {
    $real_dirlist = nv_listUploadDir(NV_UPLOADS_DIR);
    $dirlist = array_keys($array_dirname);
    $result_no_exit = array_diff($dirlist, $real_dirlist);
    foreach ($result_no_exit as $dirname) {
        // Xóa CSDL thư mục không còn tồn tại
        $did = $array_dirname[$dirname];
        $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = ' . $did);
        $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir WHERE did = ' . $did);
        unset($array_dirname[$dirname]);
    }
    $result_new = array_diff($real_dirlist, $dirlist);
    foreach ($result_new as $dirname) {
        try {
            $array_dirname[$dirname] = $db->insert_id('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time, thumb_type, thumb_width, thumb_height, thumb_quality) VALUES ('" . $dirname . "', '0', '0', '0', '0', '0')", 'did');
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }
    }
}

$global_config['upload_logo'] = nv_unhtmlspecialchars($global_config['upload_logo']);
