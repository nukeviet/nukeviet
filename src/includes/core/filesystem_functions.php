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

/**
 * nv_parse_ini_file()
 *
 * @param string $filename
 * @param bool   $process_sections
 * @return array|false
 */
function nv_parse_ini_file($filename, $process_sections = false)
{
    $process_sections = (bool) $process_sections;

    if (!file_exists($filename) or !is_readable($filename)) {
        return false;
    }

    $data = file($filename);
    $ini = [];
    $section = '';
    foreach ($data as $line) {
        $line = trim($line);
        if (empty($line) or preg_match('/^;/', $line)) {
            continue;
        }
        if (preg_match('/^\[(.*?)\]$/', $line, $match)) {
            $section = $match[1];
            continue;
        }
        if (!strpos($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line);
        $key = trim($key);
        $value = trim($value);
        $value = str_replace(['"', "'"], ['', ''], $value);

        if ($process_sections and !empty($section)) {
            if (preg_match('/^(.*?)\[\]$/', $key, $match)) {
                $ini[$section][$match[1]][] = $value;
            } else {
                $ini[$section][$key] = $value;
            }
        } else {
            if (preg_match('/^(.*?)\[(.*?)\]$/', $key, $match)) {
                $ini[$match[1]][] = $value;
            } else {
                $ini[$key] = $value;
            }
        }
    }

    return $ini;
}

/**
 * nv_scandir()
 *
 * @param string $directory
 * @param string|array $pattern
 * @param int    $sorting_order
 * @return array
 */
function nv_scandir($directory, $pattern, $sorting_order = 0)
{
    $return = [];

    if (is_dir($directory)) {
        if ($dh = opendir($directory)) {
            while (($file = readdir($dh)) !== false) {
                if (preg_match('/^\.(.*)$/', $file) or $file == 'index.html') {
                    continue;
                }

                if (!is_array($pattern)) {
                    if (preg_match($pattern, $file)) {
                        $return[] = $file;
                    }
                } else {
                    foreach ($pattern as $p) {
                        if (preg_match($p, $file)) {
                            $return[] = $file;
                            break;
                        }
                    }
                }
            }
            closedir($dh);
            if (!empty($return)) {
                if ($sorting_order) {
                    rsort($return);
                } else {
                    sort($return);
                }
            }
        }
    }

    return $return;
}

/**
 * nv_get_mime_from_ini()
 *
 * @param string $ext
 * @return string
 */
function nv_get_mime_from_ini($ext)
{
    $mime_types = nv_parse_ini_file(NV_ROOTDIR . '/includes/ini/mime.ini');

    if (array_key_exists($ext, $mime_types)) {
        if (is_string($mime_types[$ext])) {
            return $mime_types[$ext];
        }

        return $mime_types[$ext][0];
    }

    return '';
}

/**
 * nv_get_mime_type()
 *
 * @param string $filename
 * @param string $magic_path
 * @param string $default_mime
 * @return mixed
 */
function nv_get_mime_type($filename, $magic_path = '', $default_mime = 'application/octet-stream')
{
    global $sys_info;

    if (empty($filename)) {
        return false;
    }
    $_array_name = explode('.', $filename);
    $ext = strtolower(array_pop($_array_name));
    if (empty($ext)) {
        return false;
    }

    $mime = $default_mime;

    if (nv_function_exists('finfo_open')) {
        if (empty($magic_path)) {
            $finfo = finfo_open(FILEINFO_MIME);
        } elseif ($magic_path != 'auto') {
            $finfo = finfo_open(FILEINFO_MIME, $magic_path);
        } else {
            if (($magic = getenv('MAGIC')) !== false) {
                $finfo = finfo_open(FILEINFO_MIME, $magic);
            } else {
                if (substr($sys_info['os'], 0, 3) == 'WIN') {
                    $path = realpath(ini_get('extension_dir') . '/../') . 'extras/magic';
                    $finfo = finfo_open(FILEINFO_MIME, $path);
                } else {
                    $finfo = finfo_open(FILEINFO_MIME, '/usr/share/file/magic');
                }
            }
        }

        if ($finfo !== false) {
            $mime = finfo_file($finfo, realpath($filename));
            finfo_close($finfo);
            $mime = preg_replace('/^([\.\-\w]+)\/([\.\-\w]+)(.*)$/i', '$1/$2', trim($mime));
        }
    }

    if (empty($mime) or $mime == 'application/octet-stream') {
        if (nv_class_exists('finfo', false)) {
            $finfo = new finfo(FILEINFO_MIME);
            if ($finfo) {
                $mime = $finfo->file(realpath($filename));
                $mime = preg_replace('/^([\.-\w]+)\/([\.-\w]+)(.*)$/i', '$1/$2', trim($mime));
            }
        }
    }

    if (empty($mime) or $mime == 'application/octet-stream') {
        if (substr($sys_info['os'], 0, 3) != 'WIN') {
            if (nv_function_exists('system')) {
                ob_start();
                system('file -i -b ' . escapeshellarg($filename));
                $m = ob_get_clean();
                $m = trim($m);
                if (!empty($m)) {
                    $mime = preg_replace('/^([\.-\w]+)\/([\.-\w]+)(.*)$/i', '$1/$2', $m);
                }
            } elseif (nv_function_exists('exec')) {
                $m = @exec('file -bi ' . escapeshellarg($filename));
                $m = trim($m);
                if (!empty($m)) {
                    $mime = preg_replace('/^([\.-\w]+)\/([\.-\w]+)(.*)$/i', '$1/$2', $m);
                }
            }
        }
    }

    if (empty($mime) or $mime == 'application/octet-stream') {
        if (nv_function_exists('mime_content_type')) {
            $mime = mime_content_type($filename);
            $mime = preg_replace('/^([\.-\w]+)\/([\.-\w]+)(.*)$/i', '$1/$2', trim($mime));
        }
    }

    if (empty($mime) or $mime == 'application/octet-stream') {
        $img_exts = ['png', 'gif', 'jpg', 'bmp', 'tiff', 'swf', 'psd', 'webp'];
        if (in_array($ext, $img_exts, true)) {
            if (($img_info = @getimagesize($filename)) !== false) {
                if (isset($img_info['mime']) and !empty($img_info['mime'])) {
                    $mime = trim($img_info['mime']);
                    $mime = preg_replace('/^([\.-\w]+)\/([\.-\w]+)(.*)$/i', '$1/$2', $mime);
                }

                if (empty($mime) and isset($img_info[2])) {
                    $mime = image_type_to_mime_type($img_info[2]);
                }
            }
        }
    }

    if (empty($mime) or $mime == 'application/octet-stream') {
        $mime2 = nv_get_mime_from_ini($ext);
        if (!empty($mime2)) {
            return $mime2;
        }
    }

    if (preg_match('/^application\/(?:x-)?zip(?:-compressed)?$/is', $mime)) {
        if ($ext == 'docx') {
            $mime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        } elseif ($ext == 'dotx') {
            $mime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.template';
        } elseif ($ext == 'potx') {
            $mime = 'application/vnd.openxmlformats-officedocument.presentationml.template';
        } elseif ($ext == 'ppsx') {
            $mime = 'application/vnd.openxmlformats-officedocument.presentationml.slideshow';
        } elseif ($ext == 'pptx') {
            $mime = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
        } elseif ($ext == 'xlsx') {
            $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        } elseif ($ext == 'xltx') {
            $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.template';
        } elseif ($ext == 'docm') {
            $mime = 'application/vnd.ms-word.document.macroEnabled.12';
        } elseif ($ext == 'dotm') {
            $mime = 'application/vnd.ms-word.template.macroEnabled.12';
        } elseif ($ext == 'potm') {
            $mime = 'application/vnd.ms-powerpoint.template.macroEnabled.12';
        } elseif ($ext == 'ppam') {
            $mime = 'application/vnd.ms-powerpoint.addin.macroEnabled.12';
        } elseif ($ext == 'ppsm') {
            $mime = 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12';
        } elseif ($ext == 'pptm') {
            $mime = 'application/vnd.ms-powerpoint.presentation.macroEnabled.12';
        } elseif ($ext == 'xlam') {
            $mime = 'application/vnd.ms-excel.addin.macroEnabled.12';
        } elseif ($ext == 'xlsb') {
            $mime = 'application/vnd.ms-excel.sheet.binary.macroEnabled.12';
        } elseif ($ext == 'xlsm') {
            $mime = 'application/vnd.ms-excel.sheet.macroEnabled.12';
        } elseif ($ext == 'xltm') {
            $mime = 'application/vnd.ms-excel.template.macroEnabled.12';
        }
    }

    return $mime;
}

/**
 * nv_getextension()
 *
 * @param string $filename
 * @return string
 */
function nv_getextension($filename)
{
    if (!str_contains($filename, '.')) {
        return '';
    }
    $filename = basename(strtolower($filename));
    $filename = explode('.', $filename);

    return array_pop($filename);
}

/**
 * nv_get_allowed_ext()
 *
 * @param mixed $allowed_filetypes
 * @param array $forbid_extensions
 * @param array $forbid_mimes
 * @return array|string
 */
function nv_get_allowed_ext($allowed_filetypes, $forbid_extensions, $forbid_mimes)
{
    if ($allowed_filetypes == 'any' or (!empty($allowed_filetypes) and is_array($allowed_filetypes) and in_array('any', $allowed_filetypes, true))) {
        return '*';
    }
    $ini = nv_parse_ini_file(NV_ROOTDIR . '/includes/ini/mime.ini', true);
    $allowmimes = [];
    if (!is_array($allowed_filetypes)) {
        $allowed_filetypes = [$allowed_filetypes];
    }
    if (!empty($allowed_filetypes)) {
        foreach ($allowed_filetypes as $type) {
            if (isset($ini[$type])) {
                foreach ($ini[$type] as $ext => $mimes) {
                    if (!empty($ext) and !in_array($ext, $forbid_extensions, true)) {
                        $a = true;
                        if (!is_array($mimes)) {
                            if (in_array($mimes, $forbid_mimes, true)) {
                                $a = false;
                            }
                        } else {
                            foreach ($mimes as $m) {
                                if (in_array($m, $forbid_mimes, true)) {
                                    $a = false;
                                    break;
                                }
                            }
                        }
                        if ($a) {
                            $allowmimes[$ext] = $mimes;
                        }
                    }
                }
            }
        }
    }

    return $allowmimes;
}

/**
 * nv_string_to_filename()
 *
 * @param string $word
 * @return string
 */
function nv_string_to_filename($word)
{
    $word = nv_EncString($word);
    $word = preg_replace('/[^a-z0-9\.\-\_ ]/i', '', $word);
    $word = preg_replace('/^\W+|\W+$/', '', $word);
    $word = preg_replace('/[ ]+/', '-', $word);

    return strtolower(preg_replace('/\W-/', '', $word));
}

/**
 * nv_pathinfo_filename()
 *
 * @param string $file
 * @return string|string[]|void
 */
function nv_pathinfo_filename($file)
{
    if (defined('PATHINFO_FILENAME')) {
        return pathinfo($file, PATHINFO_FILENAME);
    }
    if (strstr($file, '.')) {
        return substr($file, 0, strrpos($file, '.'));
    }
}

/**
 * nv_mkdir()
 *
 * @param string $path
 * @param string $dir_name
 * @return array
 */
function nv_mkdir($path, $dir_name)
{
    global $nv_Lang, $global_config, $sys_info;
    $dir_name = nv_string_to_filename(trim(basename($dir_name)));
    if (!preg_match('/^[a-zA-Z0-9-_.]+$/', $dir_name)) {
        return [0, $nv_Lang->getGlobal('error_create_directories_name_invalid', $dir_name)];
    }
    $path = @realpath($path);
    if (!preg_match('/\/$/', $path)) {
        $path .= '/';
    }

    if (file_exists($path . $dir_name)) {
        return [2, $nv_Lang->getGlobal('error_create_directories_name_used', $dir_name), $path . $dir_name];
    }

    if (!is_dir($path)) {
        return [0, $nv_Lang->getGlobal('error_directory_does_not_exist', $path)];
    }

    $ftp_check_login = 0;
    $res = false;
    if ($sys_info['ftp_support'] and (int) ($global_config['ftp_check_login']) == 1) {
        $ftp_server = nv_unhtmlspecialchars($global_config['ftp_server']);
        $ftp_port = (int) ($global_config['ftp_port']);
        $ftp_user_name = nv_unhtmlspecialchars($global_config['ftp_user_name']);
        $ftp_user_pass = nv_unhtmlspecialchars($global_config['ftp_user_pass']);
        $ftp_path = nv_unhtmlspecialchars($global_config['ftp_path']);
        // set up basic connection
        $conn_id = ftp_connect($ftp_server, $ftp_port, 10);
        // login with username and password
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
        if ((!$conn_id) or (!$login_result)) {
            $ftp_check_login = 3;
        } elseif (ftp_chdir($conn_id, $ftp_path)) {
            $ftp_check_login = 1;
        } else {
            $ftp_check_login = 2;
        }
    }
    if ($ftp_check_login == 1) {
        $dir = str_replace(NV_ROOTDIR . '/', '', str_replace('\\', '/', $path . $dir_name));
        $res = ftp_mkdir($conn_id, $dir);
        if (substr($sys_info['os'], 0, 3) != 'WIN') {
            ftp_chmod($conn_id, 0777, $dir);
        }
        ftp_close($conn_id);
    }
    if (!is_dir($path . $dir_name)) {
        if (!is_writable($path)) {
            @chmod($path, 0777);
        }
        if (!is_writable($path)) {
            return [0, $nv_Lang->getGlobal('error_directory_can_not_write', $path)];
        }

        $oldumask = umask(0);
        $res = @mkdir($path . $dir_name);
        umask($oldumask);
    }
    if (!$res) {
        return [0, $nv_Lang->getGlobal('error_create_directories_failed', $dir_name)];
    }

    file_put_contents($path . $dir_name . '/index.html', '');

    return [1, $nv_Lang->getGlobal('directory_was_created', $dir_name), $path . $dir_name];
}

/**
 * nv_deletefile()
 *
 * @param string $file
 * @param bool   $delsub
 * @return array
 */
function nv_deletefile($file, $delsub = false)
{
    global $nv_Lang, $sys_info, $global_config;

    // Kiem tra ten file
    $realpath = realpath($file);
    if (empty($realpath)) {
        return [0, $nv_Lang->getGlobal('error_non_existent_file', $file)];
    }
    $realpath = str_replace('\\', '/', $realpath);
    $realpath = rtrim($realpath, '\\/');
    $preg_match = preg_match('/^(' . nv_preg_quote(NV_ROOTDIR) . ')(\/[\S]+)/', $realpath, $path);
    if (empty($preg_match)) {
        return [0, $nv_Lang->getGlobal('error_delete_forbidden', $file)];
    }

    $ftp_check_login = 0;
    if ($sys_info['ftp_support'] and (int) ($global_config['ftp_check_login']) == 1) {
        $ftp_server = nv_unhtmlspecialchars($global_config['ftp_server']);
        $ftp_port = (int) ($global_config['ftp_port']);
        $ftp_user_name = nv_unhtmlspecialchars($global_config['ftp_user_name']);
        $ftp_user_pass = nv_unhtmlspecialchars($global_config['ftp_user_pass']);
        $ftp_path = nv_unhtmlspecialchars($global_config['ftp_path']);

        // Ket noi, dang nhap
        $ftp = new NukeViet\Ftp\Ftp($ftp_server, $ftp_user_name, $ftp_user_pass, ['timeout' => 10], $ftp_port);

        // Chuyen thu muc
        if ($ftp->chdir($ftp_path) === true) {
            $ftp_check_login = 1;
        } else {
            $ftp->close();
        }
    }

    $filename = str_replace(NV_ROOTDIR . '/', '', str_replace('\\', '/', $realpath));
    // Tinh chinh lai file cho phu hop voi chdir

    if ($ftp_check_login == 1) {
        if (is_dir($realpath)) {
            // Xoa thu muc
            $check = nv_ftp_del_dir($ftp, $filename, $delsub);
            if ($check !== true) {
                return [0, $check];
            }
        } elseif ($ftp->unlink($filename) === false) {
            // Xoa file bang FTP khong duoc thi xoa theo cach thong thuong
            @unlink($realpath);
        }

        $ftp->close();
    } elseif (is_dir($realpath)) {
        // Khong dung FTP

        $files = scandir($realpath);
        $files2 = array_diff($files, ['.', '..', '.htaccess', 'index.html']);
        if (count($files2) and !$delsub) {
            return [0, $nv_Lang->getGlobal('error_delete_subdirectories_not_empty', $path[2])];
        }
        $files = array_diff($files, ['.', '..']);
        if (count($files)) {
            foreach ($files as $f) {
                $unlink = nv_deletefile($realpath . '/' . $f, true);
                if (empty($unlink[0])) {
                    $filename = str_replace(NV_ROOTDIR, '', str_replace('\\', '/', $realpath . '/' . $f));

                    return [0, $nv_Lang->getGlobal('error_delete_failed', $filename)];
                }
            }
        }
        if (!@rmdir($realpath)) {
            return [0, $nv_Lang->getGlobal('error_delete_subdirectories_failed', $path[2])];
        }

        return [1, $nv_Lang->getGlobal('directory_deleted', $path[2])];
    } else {
        @unlink($realpath);
    }

    if (file_exists($realpath)) {
        return [0, $nv_Lang->getGlobal('error_delete_failed', $filename)];
    }

    return [1, $nv_Lang->getGlobal('file_deleted', $filename)];
}

/**
 * nv_ftp_del_dir()
 *
 * @param mixed  $ftp
 * @param string $dst_dir
 * @param bool   $delsub
 * @return mixed
 */
function nv_ftp_del_dir($ftp, $dst_dir, $delsub)
{
    global $nv_Lang;

    $dst_dir = preg_replace('/\\/\$/', '', $dst_dir);
    // Remove trailing slash
    $ar_files = $ftp->listDetail($dst_dir, 'all', true);
    // Danh sach cac file (bao gom ca file an)

    // Bao loi thu muc khong rong
    if (!empty($ar_files) and !$delsub) {
        return $nv_Lang->getGlobal('error_delete_subdirectories_not_empty', $dst_dir);
    }

    if (is_array($ar_files)) {
        // Makes sure there are files
        $sizeof = count($ar_files);

        for ($i = 0; $i < $sizeof; ++$i) {
            $st_file = $ar_files[$i]['name'];
            // Ten file/folder
            $st_type = $ar_files[$i]['type'];
            // 1: folder | 0: file

            if ($st_file == '.' or $st_file == '..') {
                continue;
            }
            // Kiem tra neu co cac file ngoai le

            if ($st_type == 1) {
                // Neu la thu muc thi chay tiep

                $check = nv_ftp_del_dir($ftp, $dst_dir . '/' . $st_file, $delsub);

                if ($check !== true) {
                    return $check;
                }
            } else {
                if ($ftp->unlink($dst_dir . '/' . $st_file) === false) {
                    // Khong the xoa duoc file

                    return $nv_Lang->getGlobal('error_delete_failed', $dst_dir . '/' . $st_file);
                }
            }
        }
    }

    // Xoa thu muc rong
    return $ftp->rmdir($dst_dir);
}

/**
 * nv_copyfile()
 *
 * @param string $file
 * @param string $newfile
 * @return bool
 */
function nv_copyfile($file, $newfile)
{
    if (!copy($file, $newfile)) {
        $content = @file_get_contents($file);
        $openedfile = fopen($newfile, 'w');
        fwrite($openedfile, $content);
        fclose($openedfile);

        if ($content === false) {
            return false;
        }
    }

    return (bool) (file_exists($newfile));
}

/**
 * nv_renamefile()
 *
 * @param mixed $file
 * @param mixed $newname
 * @return array
 */
function nv_renamefile($file, $newname)
{
    global $nv_Lang;

    $realpath = realpath($file);
    if (empty($realpath)) {
        return [0, $nv_Lang->getGlobal('error_non_existent_file', $file)];
    }
    $realpath = str_replace('\\', '/', $realpath);
    $realpath = rtrim($realpath, '\\/');
    $preg_match = preg_match('/^(' . nv_preg_quote(NV_ROOTDIR) . ')(\/[\S]+)/', $realpath, $path);
    if (empty($preg_match)) {
        return [0, $nv_Lang->getGlobal('error_rename_forbidden', $file)];
    }
    $newname = basename(trim($newname));
    $pathinfo = pathinfo($realpath);
    if (file_exists($pathinfo['dirname'] . '/' . $newname)) {
        return [0, $nv_Lang->getGlobal('error_rename_file_exists', $newname)];
    }
    if (is_dir($realpath) and !preg_match('/^[a-zA-Z0-9-_]+$/', $newname)) {
        return [0, $nv_Lang->getGlobal('error_rename_directories_invalid', $newname)];
    }
    if (!is_dir($realpath) and !preg_match('/^[a-zA-Z0-9-_.]+$/', $newname)) {
        return [0, $nv_Lang->getGlobal('error_rename_file_invalid', $newname)];
    }
    if (!is_dir($realpath) and $pathinfo['extension'] != nv_getextension($newname)) {
        return [0, $nv_Lang->getGlobal('error_rename_extension_changed', $newname, $pathinfo['basename'])];
    }
    if (!@rename($realpath, $pathinfo['dirname'] . '/' . $newname)) {
        if (!@nv_copyfile($realpath, $pathinfo['dirname'] . '/' . $newname)) {
            return [0, $nv_Lang->getGlobal('error_rename_failed', $pathinfo['basename'], $newname)];
        }
        @nv_deletefile($realpath);
    }

    return [1, $nv_Lang->getGlobal('file_has_been_renamed', $pathinfo['basename'], $newname)];
}

/**
 * nv_chmod_dir()
 *
 * @param mixed $conn_id
 * @param mixed $dir
 * @param bool  $subdir
 */
function nv_chmod_dir($conn_id, $dir, $subdir = false)
{
    global $sys_info, $array_cmd_dir;
    $no_file = ['.', '..', '.htaccess', 'index.html'];
    if (substr($sys_info['os'], 0, 3) != 'WIN' and ftp_chmod($conn_id, 0777, $dir) !== false) {
        $array_cmd_dir[] = $dir;
        if ($subdir and is_dir(NV_ROOTDIR . '/' . $dir)) {
            ftp_chmod($conn_id, 0777, $dir);

            $list_files = ftp_nlist($conn_id, $dir);
            foreach ($list_files as $file_i) {
                $file_i = basename($file_i);
                if (!in_array($file_i, $no_file, true)) {
                    if (is_dir(NV_ROOTDIR . '/' . $dir . '/' . $file_i)) {
                        nv_chmod_dir($conn_id, $dir . '/' . $file_i, $subdir);
                    } else {
                        ftp_chmod($conn_id, 0777, $dir . '/' . $file_i);
                    }
                }
            }
        }
    } else {
        $array_cmd_dir[] = '<strong>' . $dir . ' --> no chmod 777 </strong>';
    }
}

/**
 * nv_is_image()
 *
 * @param string $img
 * @return array
 */
function nv_is_image($img)
{
    $imageinfo = [];
    if (is_file($img)) {
        $file = @getimagesize($img);
        if ($file) {
            $typeflag = [];
            $typeflag[1] = ['type' => IMAGETYPE_GIF, 'ext' => 'gif'];
            $typeflag[2] = ['type' => IMAGETYPE_JPEG, 'ext' => 'jpg'];
            $typeflag[3] = ['type' => IMAGETYPE_PNG, 'ext' => 'png'];
            $typeflag[4] = ['type' => IMAGETYPE_SWF, 'ext' => 'swf'];
            $typeflag[5] = ['type' => IMAGETYPE_PSD, 'ext' => 'psd'];
            $typeflag[6] = ['type' => IMAGETYPE_BMP, 'ext' => 'bmp'];
            $typeflag[7] = ['type' => IMAGETYPE_TIFF_II, 'ext' => 'tiff'];
            $typeflag[8] = ['type' => IMAGETYPE_TIFF_MM, 'ext' => 'tiff'];
            $typeflag[9] = ['type' => IMAGETYPE_JPC, 'ext' => 'jpc'];
            $typeflag[10] = ['type' => IMAGETYPE_JP2, 'ext' => 'jp2'];
            $typeflag[11] = ['type' => IMAGETYPE_JPX, 'ext' => 'jpf'];
            $typeflag[12] = ['type' => IMAGETYPE_JB2, 'ext' => 'jb2'];
            $typeflag[13] = ['type' => IMAGETYPE_SWC, 'ext' => 'swc'];
            $typeflag[14] = ['type' => IMAGETYPE_IFF, 'ext' => 'aiff'];
            $typeflag[15] = ['type' => IMAGETYPE_WBMP, 'ext' => 'wbmp'];
            $typeflag[16] = ['type' => IMAGETYPE_XBM, 'ext' => 'xbm'];
            defined('IMAGETYPE_WEBP') && $typeflag[18] = ['type' => IMAGETYPE_WEBP, 'ext' => 'webp'];

            $imageinfo['src'] = $img;
            $imageinfo['width'] = $file[0];
            $imageinfo['height'] = $file[1];
            $imageinfo['mime'] = $file['mime'];
            $imageinfo['type'] = $typeflag[$file[2]]['type'];
            $imageinfo['ext'] = $typeflag[$file[2]]['ext'];
            $imageinfo['bits'] = $file['bits'];
            $imageinfo['channels'] = isset($file['channels']) ? (int) ($file['channels']) : 0;
        }
    }

    return $imageinfo;
}

/**
 * Danh sách các phần mở rộng ảnh được hỗ trợ bởi GD.
 *
 * @return array
 */
function nv_editable_imgexts(): array
{
    $gdInfo = gd_info();
    $extensions = [];
    $map = [
        'JPEG Support'      => ['jpg', 'jpeg'],
        'PNG Support'       => ['png'],
        'GIF Read Support'  => ['gif'],
        'WebP Support'      => ['webp'],
        'BMP Support'       => ['bmp'],
        'XBM Support'       => ['xbm'],
        'WBMP Support'      => ['wbmp'],
    ];

    foreach ($map as $key => $extList) {
        if (!empty($gdInfo[$key])) {
            foreach ($extList as $ext) {
                $extensions[] = $ext;
            }
        }
    }

    return array_unique($extensions);
}

/**
 * Xuất ra các thông tin về ảnh để đưa vào HTML (src, width, height).
 *
 * @param string $original_name Duong dan tuyet doi den file goc (bat buoc)
 * @param int    $width Chieu rong xuat ra HTML (neu bang 0 se xuat ra kich thuoc thuc)
 * @param bool   $is_create_thumb Neu chieu rong cua hinh lon hon $width, co the tao thumbnail
 * @param string $thumb_path Neu tao thumbnail thi chi ra thu muc chua file thumbnail nay
 * @return array|false
 */
function nv_ImageInfo($original_name, $width = 0, $is_create_thumb = false, $thumb_path = '')
{
    if (empty($original_name)) {
        return false;
    }

    $original_name = realpath($original_name);
    if (empty($original_name)) {
        return false;
    }

    $original_name = str_replace('\\', '/', $original_name);
    $original_name = rtrim($original_name, '\\/');

    $allowed_exts = ['gif', 'jpg', 'jpeg', 'png', 'bmp', 'webp'];
    if (!$is_create_thumb) {
        $allowed_exts[] = 'svg';
    }
    unset($matches);
    if (!preg_match('/^' . nv_preg_quote(NV_ROOTDIR) . '\/(([a-z0-9\-\_\/]+\/)*([a-z0-9\-\_\.]+)(\.(' . implode('|', $allowed_exts) . ')))$/i', $original_name, $matches)) {
        return false;
    }

    $imageinfo = [];
    $imageinfo['orig_src'] = $imageinfo['src'] = NV_BASE_SITEURL . $matches[1];

    $ext = nv_getextension($original_name);
    if ($ext == 'svg') {
        // Đọc SVG
        if (($xml = @simplexml_load_file($original_name)) === false) {
            return false;
        }

        $attr = $xml->attributes();
        if (!isset($attr['width']) and !isset($attr['height']) and !isset($attr['viewBox'])) {
            return false;
        }

        if (isset($attr['width']) and isset($attr['height'])) {
            $imageinfo['orig_width'] = $imageinfo['width'] = (int) ($attr['width']);
            $imageinfo['orig_height'] = $imageinfo['height'] = (int) ($attr['height']);
        } elseif (isset($attr['viewBox'])) {
            $viewBox = explode(' ', (string) $attr['viewBox']);
            if (!isset($viewBox[3])) {
                return false;
            }
            $imageinfo['orig_width'] = $imageinfo['width'] = (int) ($viewBox[2]);
            $imageinfo['orig_height'] = $imageinfo['height'] = (int) ($viewBox[3]);
        } else {
            return false;
        }
    } else {
        // Đọc các định dạng ảnh khác
        $size = @getimagesize($original_name);
        if (!$size or !isset($size[0]) or !isset($size[1]) or !$size[0] or !$size[1]) {
            return false;
        }
        $imageinfo['orig_width'] = $imageinfo['width'] = $size[0];
        $imageinfo['orig_height'] = $imageinfo['height'] = $size[1];
    }

    if ($width) {
        $imageinfo['width'] = $width;
        $imageinfo['height'] = ceil($width * $imageinfo['orig_height'] / $imageinfo['orig_width']);
    }

    if ($is_create_thumb and $width and $imageinfo['orig_width'] > $width) {
        if (empty($thumb_path) or !is_dir($thumb_path) or !is_writable($thumb_path)) {
            $thumb_path = $matches[2];
        } else {
            $thumb_path = realpath($thumb_path);
            if (empty($thumb_path)) {
                $thumb_path = $matches[2];
            } else {
                $thumb_path = str_replace('\\', '/', $thumb_path);

                unset($matches2);
                if (preg_match('/^' . nv_preg_quote(NV_ROOTDIR) . '([a-z0-9\-\_\/]+)*$/i', $thumb_path, $matches2)) {
                    $thumb_path = ltrim($matches2[1], '\\/');
                } else {
                    $thumb_path = $matches[2];
                }
            }
        }

        if (!empty($thumb_path) and !preg_match('/\/$/', $thumb_path)) {
            $thumb_path .= '/';
        }

        $new_src = $thumb_path . $matches[3] . '_' . md5($original_name . $width) . $matches[4];

        $is_create = true;

        if (is_file(NV_ROOTDIR . '/' . $new_src)) {
            $size = @getimagesize(NV_ROOTDIR . '/' . $new_src);
            if ($size and isset($size[0]) and isset($size[1]) and $size[0] and $size[1]) {
                $imageinfo['src'] = NV_BASE_SITEURL . $new_src;
                $imageinfo['width'] = $size[0];
                $imageinfo['height'] = $size[1];

                $is_create = false;
            }
        }

        if ($is_create) {
            $image = new NukeViet\Files\Image($original_name, NV_MAX_WIDTH, NV_MAX_HEIGHT);
            $image->resizeXY($width);
            $image->save(NV_ROOTDIR . '/' . $thumb_path, $matches[3] . '_' . md5($original_name . $width) . $matches[4]);
            $image_info = $image->create_Image_info;

            if (file_exists(NV_ROOTDIR . '/' . $new_src)) {
                $imageinfo['src'] = NV_BASE_SITEURL . $new_src;
                $imageinfo['width'] = $image_info['width'];
                $imageinfo['height'] = $image_info['height'];
            }
        }
    }

    return $imageinfo;
}

/**
 * nv_imageResize()
 *
 * @param int $origX
 * @param int $origY
 * @param int $maxX
 * @param int $maxY
 * @return array
 */
function nv_imageResize($origX, $origY, $maxX, $maxY)
{
    $return = ['width' => $origX, 'height' => $origY];
    if ($origX > $maxX or $origY > $maxY) {
        if ($origX >= $origY) {
            $return['width'] = $maxX;
            $return['height'] = ceil($maxX * $origY / $origX);

            if ($return['height'] > $maxY) {
                $return['width'] = ceil($return['width'] / $return['height'] * $maxY);
                $return['height'] = $maxY;
            }
        } else {
            $return['width'] = ceil($origX / $origY * $maxY);
            $return['height'] = $maxY;

            if ($return['width'] > $maxX) {
                $return['height'] = ceil($maxX * $return['height'] / $return['width']);
                $return['width'] = $maxX;
            }
        }
    }

    return $return;
}

/**
 * nv_is_file()
 *
 * @param string $filepath
 * @param string $folders
 * @return bool
 */
function nv_is_file($filepath, $folders = [])
{
    if (empty($folders)) {
        $folders = [NV_UPLOADS_DIR, NV_ASSETS_DIR . '/images'];
    } elseif (!is_array($folders)) {
        $folders = [$folders];
    }

    $filepath = htmlspecialchars(trim(NV_DOCUMENT_ROOT . $filepath), ENT_QUOTES);
    $filepath = rtrim($filepath, '/');

    if (empty($filepath)) {
        return false;
    }
    if (($filepath = realpath($filepath)) === false) {
        return false;
    }

    $filepath = str_replace('\\', '/', $filepath);

    $file_exists = 0;
    foreach ($folders as $folder) {
        if (preg_match('/^' . nv_preg_quote(NV_ROOTDIR . '/' . $folder) . '/', $filepath) and is_file($filepath)) {
            ++$file_exists;
        }
    }

    return $file_exists > 0 ? true : false;
}

/**
 * nv_scandirfile()
 * Lấy danh sách các dir và file theo $pattern trong một thư mục nhất định
 *
 * @param mixed  $directory
 * @param mixed  $pattern
 * @param mixed  $files
 * @param string $cut
 */
function nv_scandirfile($directory, $pattern, &$files, $cut = '')
{
    if (is_dir($directory)) {
        !empty($cut) && $cut = str_replace(NV_ROOTDIR, '', $cut);
        !empty($cut) && $cut = trim($cut, '/');
        !empty($cut) && $cut = '/' . $cut;
        $ab_directory = str_replace(NV_ROOTDIR . $cut, '', $directory);
        $ab_directory = trim($ab_directory, '/');
        $files[$ab_directory] = [];
        if ($dh = opendir($directory)) {
            $subdirs = [];
            while (($file = readdir($dh)) !== false) {
                if (!preg_match('/^\./', $file) and $file != 'index.html') {
                    if (is_dir($directory . '/' . $file)) {
                        $subdirs[] = $directory . '/' . $file;
                        $files[$ab_directory . (!empty($ab_directory) ? '/' : '') . $file] = [];
                    } else {
                        if (!is_array($pattern)) {
                            if (preg_match($pattern, $file)) {
                                $files[$ab_directory][] = $file;
                            }
                        } else {
                            foreach ($pattern as $p) {
                                if (preg_match($p, $file)) {
                                    $files[$ab_directory][] = $file;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            closedir($dh);
            if (!empty($subdirs)) {
                foreach ($subdirs as $subdir) {
                    nv_scandirfile($subdir, $pattern, $files, $cut);
                }
            }
        }
    }
}

/**
 * get_tpl_dir()
 *
 * @param array|string $dir_basenames
 * @param string       $default_dir_basename
 * @param string       $file
 * @return string
 */
function get_tpl_dir($dir_basenames, $default_dir_basename, $file = '')
{
    if (!empty($file)) {
        $file = trim($file, '/');
    }
    if (!is_array($dir_basenames)) {
        $dir_basenames = [$dir_basenames];
    }
    $dir_basenames = array_filter($dir_basenames);
    $dir_basenames = array_unique($dir_basenames);
    if (!empty($dir_basenames)) {
        foreach ($dir_basenames as $dir_basename) {
            if (theme_file_exists($dir_basename . '/' . $file)) {
                return $dir_basename;
            }
        }
    }

    return $default_dir_basename;
}

/**
 * get_theme_filelist()
 *
 * @return mixed
 */
function get_theme_filelist()
{
    global $nv_Cache;

    $themefilelist = [];
    $cache_file = 'themefiles_' . NV_CACHE_PREFIX . '.cache';
    if (NV_DEBUG and defined('NV_IS_ADMIN')) {
        nv_scandirfile(NV_ROOTDIR . '/themes', '/([a-zA-Z0-9\.\-\_]+)\.(php|ini|json|ttf|woff|woff2|tpl|js|css|gif|jpg|jpeg|png|webp|avg|ico|xsl)$/', $themefilelist, 'themes');
        $nv_Cache->setItem('sys', $cache_file, json_encode($themefilelist));
    } else {
        if (($cache = $nv_Cache->getItem('sys', $cache_file)) != false) {
            $themefilelist = json_decode($cache, true);
        } else {
            nv_scandirfile(NV_ROOTDIR . '/themes', '/([a-zA-Z0-9\.\-\_]+)\.(php|ini|json|ttf|woff|woff2|tpl|js|css|gif|jpg|jpeg|png|webp|avg|ico|xsl)$/', $themefilelist, 'themes');
            $nv_Cache->setItem('sys', $cache_file, json_encode($themefilelist));
        }
    }

    return $themefilelist;
}

/**
 * get_module_filelist()
 *
 * @return mixed
 */
function get_module_filelist()
{
    global $nv_Cache;

    $modulefilelist = [];
    $cache_file = 'modulefiles_' . NV_CACHE_PREFIX . '.cache';
    if (NV_DEBUG and defined('NV_IS_ADMIN')) {
        nv_scandirfile(NV_ROOTDIR . '/modules', '/([a-zA-Z0-9\.\-\_]+)\.(php|ini|json|ttf|woff|woff2|tpl|js|css|gif|jpg|jpeg|png|webp|avg|ico|xsl)$/', $modulefilelist, 'modules');
        $nv_Cache->setItem('sys', $cache_file, json_encode($modulefilelist));
    } else {
        if (($cache = $nv_Cache->getItem('sys', $cache_file)) != false) {
            $modulefilelist = json_decode($cache, true);
        } else {
            nv_scandirfile(NV_ROOTDIR . '/modules', '/([a-zA-Z0-9\.\-\_]+)\.(php|ini|json|ttf|woff|woff2|tpl|js|css|gif|jpg|jpeg|png|webp|avg|ico|xsl)$/', $modulefilelist, 'modules');
            $nv_Cache->setItem('sys', $cache_file, json_encode($modulefilelist));
        }
    }

    return $modulefilelist;
}

/**
 * theme_file_exists()
 *
 * @param string $file
 * @return bool
 */
function theme_file_exists($file)
{
    global $themefilelist;

    $file = str_replace(NV_ROOTDIR . '/themes', '', $file);
    $file = trim($file, '/');

    if (!empty($themefilelist)) {
        $path_parts = pathinfo($file);
        if (!empty($path_parts['extension'])) {
            $_dir = $path_parts['dirname'];
            $_file = $path_parts['basename'];
        } else {
            $_dir = $path_parts['dirname'] . '/' . $path_parts['basename'];
            $_file = '';
        }

        if (empty($_file)) {
            return isset($themefilelist[$_dir]);
        }

        return !empty($themefilelist[$_dir]) and in_array($_file, $themefilelist[$_dir], true);
    }

    return file_exists(NV_ROOTDIR . '/themes/' . $file);
}

/**
 * module_file_exists()
 *
 * @param string $file
 * @return bool
 */
function module_file_exists($file)
{
    global $modulefilelist;

    $file = str_replace(NV_ROOTDIR . '/modules', '', $file);
    $file = trim($file, '/');

    if (!empty($modulefilelist)) {
        $path_parts = pathinfo($file);
        if (!empty($path_parts['extension'])) {
            $_dir = $path_parts['dirname'];
            $_file = $path_parts['basename'];
        } else {
            $_dir = $path_parts['dirname'] . '/' . $path_parts['basename'];
            $_file = '';
        }

        if (empty($_file)) {
            return isset($modulefilelist[$_dir]);
        }

        return !empty($modulefilelist[$_dir]) and in_array($_file, $modulefilelist[$_dir], true);
    }

    return file_exists(NV_ROOTDIR . '/modules/' . $file);
}

/**
 * Lấy thư mục chứa tệp tpl $filename của module hiện tại đang xem. Thứ tự ưu tiên như sau
 * - Thư mục module_theme của giao diện module
 * - Thư mục module_theme của giao diện site
 * - Thư mục module_theme của giao diện mặc định
 * - Thư mục module_file của giao diện module
 * - Thư mục module_file của giao diện site
 * - Thư mục module_file của giao diện mặc định
 *
 * @param string $filename
 * @param bool   $array
 * @return array|string|void
 */
function get_module_tpl_dir($filename, $array = false)
{
    global $global_config, $module_info, $module_file;

    $themes_check = [];
    $themes_check[$global_config['module_theme']] = $global_config['module_theme'];
    if (defined('NV_ADMIN')) {
        if (!isset($themes_check[$global_config['admin_theme']])) {
            $themes_check[$global_config['admin_theme']] = $global_config['admin_theme'];
        }
        if (!isset($themes_check[NV_DEFAULT_ADMIN_THEME])) {
            $themes_check[NV_DEFAULT_ADMIN_THEME] = NV_DEFAULT_ADMIN_THEME;
        }
    } else {
        if (!isset($themes_check[$global_config['site_theme']])) {
            $themes_check[$global_config['site_theme']] = $global_config['site_theme'];
        }
        if (!isset($themes_check[NV_DEFAULT_SITE_THEME])) {
            $themes_check[NV_DEFAULT_SITE_THEME] = NV_DEFAULT_SITE_THEME;
        }
    }

    $dirs_check = [];
    $module_theme = $module_info['module_theme'] ?? $module_file;
    $dirs_check[$module_theme] = $module_theme;
    if ($module_theme != $module_file) {
        $dirs_check[$module_file] = $module_file;
    }

    foreach ($dirs_check as $dir) {
        foreach ($themes_check as $theme) {
            if (theme_file_exists($theme . '/modules/' . $dir . '/' . $filename)) {
                if ($array) {
                    return [$theme, NV_ROOTDIR . '/themes/' . $theme . '/modules/' . $dir];
                }
                return NV_ROOTDIR . '/themes/' . $theme . '/modules/' . $dir;
            }
        }
    }

    trigger_error('Template file not found: ' . $filename . ', module: ' . $module_theme);
    http_response_code(500);
    trigger_error('Template file not found!', E_USER_ERROR);
}
