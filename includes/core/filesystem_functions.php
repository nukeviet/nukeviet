<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2/9/2010, 2:33
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

/**
 * nv_parse_ini_file()
 *
 * @param mixed $filename
 * @param bool $process_sections
 * @return
 */
function nv_parse_ini_file($filename, $process_sections = false)
{
    $process_sections = ( bool )$process_sections;

    if (! file_exists($filename) or ! is_readable($filename)) {
        return false;
    }

    $data = file($filename);
    $ini = array();
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
        if (! strpos($line, '=')) {
            continue;
        }
        list($key, $value) = explode('=', $line);
        $key = trim($key);
        $value = trim($value);
        $value = str_replace(array( '"', "'" ), array( '', '' ), $value);

        if ($process_sections and ! empty($section)) {
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
 * @param mixed $directory
 * @param mixed $pattern
 * @param integer $sorting_order
 * @return
 */
function nv_scandir($directory, $pattern, $sorting_order = 0)
{
    $return = array();

    if (is_dir($directory)) {
        if ($dh = opendir($directory)) {
            while (($file = readdir($dh)) !== false) {
                if (preg_match('/^\.(.*)$/', $file) or $file == 'index.html') {
                    continue;
                }

                if (! is_array($pattern)) {
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
            if (! empty($return)) {
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
 * nv_get_mime_type()
 *
 * @param mixed $filename
 * @param string $magic_path
 * @param string $default_mime
 * @return
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

        if (is_resource($finfo)) {
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
                if (! empty($m)) {
                    $mime = preg_replace('/^([\.-\w]+)\/([\.-\w]+)(.*)$/i', '$1/$2', $m);
                }
            } elseif (nv_function_exists('exec')) {
                $m = @exec('file -bi ' . escapeshellarg($filename));
                $m = trim($m);
                if (! empty($m)) {
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
        $img_exts = array( 'png', 'gif', 'jpg', 'bmp', 'tiff', 'swf', 'psd' );
        if (in_array($ext, $img_exts)) {
            if (($img_info = @getimagesize($filename)) !== false) {
                if (isset($img_info['mime']) and ! empty($img_info['mime'])) {
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
        $mime_types = nv_parse_ini_file(NV_ROOTDIR . '/includes/ini/mime.ini');

        if (array_key_exists($ext, $mime_types)) {
            if (is_string($mime_types[$ext])) {
                return $mime_types[$ext];
            }

            return $mime_types[$ext][0];
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
 * @param mixed $filename
 * @return
 */
function nv_getextension($filename)
{
    if (strpos($filename, '.') === false) {
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
 * @param mixed $forbid_extensions
 * @param mixed $forbid_mimes
 * @return
 */
function nv_get_allowed_ext($allowed_filetypes, $forbid_extensions, $forbid_mimes)
{
    if ($allowed_filetypes == 'any' or (! empty($allowed_filetypes) and is_array($allowed_filetypes) and in_array('any', $allowed_filetypes))) {
        return '*';
    }
    $ini = nv_parse_ini_file(NV_ROOTDIR . '/includes/ini/mime.ini', true);
    $allowmimes = array();
    if (! is_array($allowed_filetypes)) {
        $allowed_filetypes = array( $allowed_filetypes );
    }
    if (! empty($allowed_filetypes)) {
        foreach ($allowed_filetypes as $type) {
            if (isset($ini[$type])) {
                foreach ($ini[$type] as $ext => $mimes) {
                    if (! empty($ext) and ! in_array($ext, $forbid_extensions)) {
                        $a = true;
                        if (! is_array($mimes)) {
                            if (in_array($mimes, $forbid_mimes)) {
                                $a = false;
                            }
                        } else {
                            foreach ($mimes as $m) {
                                if (in_array($m, $forbid_mimes)) {
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
 * @param mixed $word
 * @return
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
 * @param mixed $file
 * @return
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
 * @param mixed $path
 * @param mixed $dir_name
 * @return
 */
function nv_mkdir($path, $dir_name)
{
    global $lang_global, $global_config, $sys_info;
    $dir_name = nv_string_to_filename(trim(basename($dir_name)));
    if (! preg_match('/^[a-zA-Z0-9-_.]+$/', $dir_name)) {
        return array( 0, sprintf($lang_global['error_create_directories_name_invalid'], $dir_name) );
    }
    $path = @realpath($path);
    if (! preg_match('/\/$/', $path)) {
        $path = $path . '/';
    }

    if (file_exists($path . $dir_name)) {
        return array( 2, sprintf($lang_global['error_create_directories_name_used'], $dir_name), $path . $dir_name );
    }

    if (! is_dir($path)) {
        return array( 0, sprintf($lang_global['error_directory_does_not_exist'], $path) );
    }

    $ftp_check_login = 0;
    $res = false;
    if ($sys_info['ftp_support'] and intval($global_config['ftp_check_login']) == 1) {
        $ftp_server = nv_unhtmlspecialchars($global_config['ftp_server']);
        $ftp_port = intval($global_config['ftp_port']);
        $ftp_user_name = nv_unhtmlspecialchars($global_config['ftp_user_name']);
        $ftp_user_pass = nv_unhtmlspecialchars($global_config['ftp_user_pass']);
        $ftp_path = nv_unhtmlspecialchars($global_config['ftp_path']);
        // set up basic connection
        $conn_id = ftp_connect($ftp_server, $ftp_port, 10);
        // login with username and password
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
        if ((! $conn_id) or (! $login_result)) {
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
    if (! is_dir($path . $dir_name)) {
        if (! is_writable($path)) {
            @chmod($path, 0777);
        }
        if (! is_writable($path)) {
            return array( 0, sprintf($lang_global['error_directory_can_not_write'], $path) );
        }

        $oldumask = umask(0);
        $res = @mkdir($path . $dir_name);
        umask($oldumask);
    }
    if (! $res) {
        return array( 0, sprintf($lang_global['error_create_directories_failed'], $dir_name) );
    }

    file_put_contents($path . $dir_name . '/index.html', '');

    return array( 1, sprintf($lang_global['directory_was_created'], $dir_name), $path . $dir_name );
}

/**
 * nv_deletefile()
 *
 * @param mixed $file
 * @param bool $delsub
 * @return
 */
function nv_deletefile($file, $delsub = false)
{
    global $lang_global, $sys_info, $global_config;

    // Kiem tra ten file
    $realpath = realpath($file);
    if (empty($realpath)) {
        return array( 0, sprintf($lang_global['error_non_existent_file'], $file) );
    }
    $realpath = str_replace('\\', '/', $realpath);
    $realpath = rtrim($realpath, '\\/');
    $preg_match = preg_match('/^(' . nv_preg_quote(NV_ROOTDIR) . ')(\/[\S]+)/', $realpath, $path);
    if (empty($preg_match)) {
        return array( 0, sprintf($lang_global['error_delete_forbidden'], $file) );
    }

    $ftp_check_login = 0;
    if ($sys_info['ftp_support'] and intval($global_config['ftp_check_login']) == 1) {
        $ftp_server = nv_unhtmlspecialchars($global_config['ftp_server']);
        $ftp_port = intval($global_config['ftp_port']);
        $ftp_user_name = nv_unhtmlspecialchars($global_config['ftp_user_name']);
        $ftp_user_pass = nv_unhtmlspecialchars($global_config['ftp_user_pass']);
        $ftp_path = nv_unhtmlspecialchars($global_config['ftp_path']);

        // Ket noi, dang nhap
        $ftp = new NukeViet\Ftp\Ftp($ftp_server, $ftp_user_name, $ftp_user_pass, array( 'timeout' => 10 ), $ftp_port);

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
                return array( 0, $check );
            }
        } elseif ($ftp->unlink($filename) === false) {
            // Xoa file bang FTP khong duoc thi xoa theo cach thong thuong
            @unlink($realpath);
        }

        $ftp->close();
    } elseif (is_dir($realpath)) {
        // Khong dung FTP

        $files = scandir($realpath);
        $files2 = array_diff($files, array( '.', '..', '.htaccess', 'index.html' ));
        if (sizeof($files2) and ! $delsub) {
            return array( 0, sprintf($lang_global['error_delete_subdirectories_not_empty'], $path[2]) );
        } else {
            $files = array_diff($files, array( '.', '..' ));
            if (sizeof($files)) {
                foreach ($files as $f) {
                    $unlink = nv_deletefile($realpath . '/' . $f, true);
                    if (empty($unlink[0])) {
                        $filename = str_replace(NV_ROOTDIR, '', str_replace('\\', '/', $realpath . '/' . $f));
                        return array( 0, sprintf($lang_global['error_delete_failed'], $filename) );
                    }
                }
            }
            if (! @rmdir($realpath)) {
                return array( 0, sprintf($lang_global['error_delete_subdirectories_failed'], $path[2]) );
            } else {
                return array( 1, sprintf($lang_global['directory_deleted'], $path[2]) );
            }
        }
    } else {
        @unlink($realpath);
    }

    if (file_exists($realpath)) {
        return array( 0, sprintf($lang_global['error_delete_failed'], $filename) );
    } else {
        return array( 1, sprintf($lang_global['file_deleted'], $filename) );
    }
}

/**
 * nv_ftp_del_dir()
 *
 * @param mixed $conn_id
 * @param mixed $dst_dir
 * @param mixed $delsub
 * @return
 */
function nv_ftp_del_dir($ftp, $dst_dir, $delsub)
{
    global $lang_global;

    $dst_dir = preg_replace('/\\/\$/', '', $dst_dir);
    // Remove trailing slash
    $ar_files = $ftp->listDetail($dst_dir, 'all', true);
    // Danh sach cac file (bao gom ca file an)

    // Bao loi thu muc khong rong
    if (! empty($ar_files) and ! $delsub) {
        return sprintf($lang_global['error_delete_subdirectories_not_empty'], $dst_dir);
    }

    if (is_array($ar_files)) {
        // Makes sure there are files
        $sizeof = sizeof($ar_files);

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

                    return sprintf($lang_global['error_delete_failed'], $dst_dir . '/' . $st_file);
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
 * @param mixed $file
 * @param mixed $newfile
 * @return
 */
function nv_copyfile($file, $newfile)
{
    if (! copy($file, $newfile)) {
        $content = @file_get_contents($file);
        $openedfile = fopen($newfile, 'w');
        fwrite($openedfile, $content);
        fclose($openedfile);

        if ($content === false) {
            return false;
        }
    }

    if (file_exists($newfile)) {
        return true;
    }
    return false;
}

/**
 * nv_renamefile()
 *
 * @param mixed $file
 * @param mixed $newname
 * @return
 */
function nv_renamefile($file, $newname)
{
    global $lang_global;

    $realpath = realpath($file);
    if (empty($realpath)) {
        return array( 0, sprintf($lang_global['error_non_existent_file'], $file) );
    }
    $realpath = str_replace('\\', '/', $realpath);
    $realpath = rtrim($realpath, '\\/');
    $preg_match = preg_match('/^(' . nv_preg_quote(NV_ROOTDIR) . ')(\/[\S]+)/', $realpath, $path);
    if (empty($preg_match)) {
        return array( 0, sprintf($lang_global['error_rename_forbidden'], $file) );
    }
    $newname = basename(trim($newname));
    $pathinfo = pathinfo($realpath);
    if (file_exists($pathinfo['dirname'] . '/' . $newname)) {
        return array( 0, sprintf($lang_global['error_rename_file_exists'], $newname) );
    }
    if (is_dir($realpath) and ! preg_match('/^[a-zA-Z0-9-_]+$/', $newname)) {
        return array( 0, sprintf($lang_global['error_rename_directories_invalid'], $newname) );
    }
    if (! is_dir($realpath) and ! preg_match('/^[a-zA-Z0-9-_.]+$/', $newname)) {
        return array( 0, sprintf($lang_global['error_rename_file_invalid'], $newname) );
    }
    if (! is_dir($realpath) and $pathinfo['extension'] != nv_getextension($newname)) {
        return array( 0, sprintf($lang_global['error_rename_extension_changed'], $newname, $pathinfo['basename']) );
    }
    if (! @rename($realpath, $pathinfo['dirname'] . '/' . $newname)) {
        if (! @nv_copyfile($realpath, $pathinfo['dirname'] . '/' . $newname)) {
            return array( 0, sprintf($lang_global['error_rename_failed'], $pathinfo['basename'], $newname) );
        } else {
            @nv_deletefile($realpath);
        }
    }
    return array( 1, sprintf($lang_global['file_has_been_renamed'], $pathinfo['basename'], $newname) );
}

/**
 * nv_chmod_dir()
 *
 * @param mixed $conn_id
 * @param mixed $dir
 * @param bool $subdir
 * @return
 */
function nv_chmod_dir($conn_id, $dir, $subdir = false)
{
    global $sys_info, $array_cmd_dir;
    $no_file = array( '.', '..', '.htaccess', 'index.html' );
    if (substr($sys_info['os'], 0, 3) != 'WIN' and ftp_chmod($conn_id, 0777, $dir) !== false) {
        $array_cmd_dir[] = $dir;
        if ($subdir and is_dir(NV_ROOTDIR . '/' . $dir)) {
            ftp_chmod($conn_id, 0777, $dir);

            $list_files = ftp_nlist($conn_id, $dir);
            foreach ($list_files as $file_i) {
                $file_i = basename($file_i);
                if (! in_array($file_i, $no_file)) {
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
 * @param mixed $img
 * @return
 */
function nv_is_image($img)
{
    $imageinfo = array();
    if (is_file($img)) {
        $file = @getimagesize($img);
        if ($file) {
            $typeflag = array();
            $typeflag[1] = array( 'type' => IMAGETYPE_GIF, 'ext' => 'gif' );
            $typeflag[2] = array( 'type' => IMAGETYPE_JPEG, 'ext' => 'jpg' );
            $typeflag[3] = array( 'type' => IMAGETYPE_PNG, 'ext' => 'png' );
            $typeflag[4] = array( 'type' => IMAGETYPE_SWF, 'ext' => 'swf' );
            $typeflag[5] = array( 'type' => IMAGETYPE_PSD, 'ext' => 'psd' );
            $typeflag[6] = array( 'type' => IMAGETYPE_BMP, 'ext' => 'bmp' );
            $typeflag[7] = array( 'type' => IMAGETYPE_TIFF_II, 'ext' => 'tiff' );
            $typeflag[8] = array( 'type' => IMAGETYPE_TIFF_MM, 'ext' => 'tiff' );
            $typeflag[9] = array( 'type' => IMAGETYPE_JPC, 'ext' => 'jpc' );
            $typeflag[10] = array( 'type' => IMAGETYPE_JP2, 'ext' => 'jp2' );
            $typeflag[11] = array( 'type' => IMAGETYPE_JPX, 'ext' => 'jpf' );
            $typeflag[12] = array( 'type' => IMAGETYPE_JB2, 'ext' => 'jb2' );
            $typeflag[13] = array( 'type' => IMAGETYPE_SWC, 'ext' => 'swc' );
            $typeflag[14] = array( 'type' => IMAGETYPE_IFF, 'ext' => 'aiff' );
            $typeflag[15] = array( 'type' => IMAGETYPE_WBMP, 'ext' => 'wbmp' );
            $typeflag[16] = array( 'type' => IMAGETYPE_XBM, 'ext' => 'xbm' );

            $imageinfo['src'] = $img;
            $imageinfo['width'] = $file[0];
            $imageinfo['height'] = $file[1];
            $imageinfo['mime'] = $file['mime'];
            $imageinfo['type'] = $typeflag[$file[2]]['type'];
            $imageinfo['ext'] = $typeflag[$file[2]]['ext'];
            $imageinfo['bits'] = $file['bits'];
            $imageinfo['channels'] = isset($file['channels']) ? intval($file['channels']) : 0;
        }
    }
    return $imageinfo;
}

/**
 * nv_ImageInfo()
 * Function xuat ra cac thong tin ve IMAGE de dua vao HTML (src, width, height).
 *
 * @param mixed $original_name - duong dan tuyet doi den file goc (bat buoc)
 * @param integer $width - chieu rong xuat ra HTML (neu bang 0 se xuat ra kich thuoc thuc)
 * @param bool $is_create_thumb - Neu chieu rong cua hinh lon hon $width, co the tao thumbnail
 * @param string $thumb_path - neu tao thumbnail thi chi ra thu muc chua file thumbnail nay.
 * @return array( 'src', 'width', 'height' ) */
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

    unset($matches);
    if (! preg_match('/^' . nv_preg_quote(NV_ROOTDIR) . '\/(([a-z0-9\-\_\/]+\/)*([a-z0-9\-\_\.]+)(\.(gif|jpg|jpeg|png|bmp)))$/i', $original_name, $matches)) {
        return false;
    }

    $imageinfo = array();

    $size = @getimagesize($original_name);
    if (! $size or ! isset($size[0]) or ! isset($size[1]) or ! $size[0] or ! $size[1]) {
        return false;
    }

    $imageinfo['orig_src'] = $imageinfo['src'] = NV_BASE_SITEURL . $matches[1];
    $imageinfo['orig_width'] = $imageinfo['width'] = $size[0];
    $imageinfo['orig_height'] = $imageinfo['height'] = $size[1];

    if ($width) {
        $imageinfo['width'] = $width;
        $imageinfo['height'] = ceil($width * $imageinfo['orig_height'] / $imageinfo['orig_width']);
    }

    if ($is_create_thumb and $width and $imageinfo['orig_width'] > $width) {
        if (empty($thumb_path) or ! is_dir($thumb_path) or ! is_writeable($thumb_path)) {
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

        if (! empty($thumb_path) and ! preg_match('/\/$/', $thumb_path)) {
            $thumb_path = $thumb_path . '/';
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
 * @param integer $origX
 * @param integer $origY
 * @param integer $maxX
 * @param integer $maxY
 * @return
 */
function nv_imageResize($origX, $origY, $maxX, $maxY)
{
    $return = array( 'width' => $origX, 'height' => $origY );
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
 * @param mixed $filepath
 * @param mixed $folders
 * @return
 */
function nv_is_file($filepath, $folders = array())
{
    if (empty($folders)) {
        $folders = array( NV_UPLOADS_DIR, NV_ASSETS_DIR . '/images' );
    } elseif (! is_array($folders)) {
        $folders = array( $folders );
    }

    $filepath = htmlspecialchars(trim(NV_DOCUMENT_ROOT . $filepath), ENT_QUOTES);
    $filepath = rtrim($filepath, '/');

    if (empty($filepath)) {
        return false;
    }
    if (($filepath = realpath($filepath)) === false) {
        return false;
    }

    $filepath = str_replace("\\", '/', $filepath);

    $file_exists = 0;
    foreach ($folders as $folder) {
        if (preg_match('/^' . nv_preg_quote(NV_ROOTDIR . '/' . $folder) . '/', $filepath) and is_file($filepath)) {
            $file_exists ++;
        }
    }

    return $file_exists > 0 ? true : false;
}
