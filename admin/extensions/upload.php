<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (!defined('NV_IS_FILE_EXTENSIONS')) {
    die('Stop!!!');
}

$page_title = $lang_module['autoinstall_install'];
$set_active_op = 'manage';

$xtpl = new XTemplate('upload.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$filename = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'auto_' . NV_CHECK_SESSION . '.zip';

// Giai nen goi ung dung
if ($nv_Request->isset_request('extract', 'get')) {
    $extract = $nv_Request->get_title('extract', 'get', '');

    if ($extract == md5($filename . NV_CHECK_SESSION)) {
        if (!file_exists($filename)) {
            $xtpl->assign('ERROR', $lang_module['autoinstall_error_downloaded']);
            $xtpl->parse('extract.error');
        } else {
            $zip = new PclZip($filename);
            $ziplistContent = $zip->listContent();

            $temp_extract_dir = NV_TEMP_DIR . '/' . md5($filename . NV_CHECK_SESSION);

            $no_extract = array();
            $error_create_folder = array();
            $error_move_folder = array();
            $extConfig = array();
            $fileConfig = array();

            if (NV_ROOTDIR . '/' . $temp_extract_dir) {
                nv_deletefile(NV_ROOTDIR . '/' . $temp_extract_dir, true);
            }

            // Kiem tra FTP
            $ftp_check_login = 0;

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
                if ((!$conn_id) or (!$login_result)) {
                    $ftp_check_login = 3;
                } elseif (ftp_chdir($conn_id, $ftp_path)) {
                    $ftp_check_login = 1;
                } else {
                    $ftp_check_login = 2;
                }
            }

            // Tao thu muc bang FTP neu co
            if ($ftp_check_login == 1) {
                ftp_mkdir($conn_id, $temp_extract_dir);

                if (substr($sys_info['os'], 0, 3) != 'WIN') {
                    ftp_chmod($conn_id, 0777, $temp_extract_dir);
                }

                foreach ($ziplistContent as $array_file) {
                    if (!empty($array_file['folder']) and !file_exists(NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename'])) {
                        $cp = '';
                        $e = explode('/', $array_file['filename']);
                        foreach ($e as $p) {
                            if (!empty($p) and !is_dir(NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $cp . $p)) {
                                ftp_mkdir($conn_id, $temp_extract_dir . '/' . $cp . $p);
                                if (substr($sys_info['os'], 0, 3) != 'WIN') {
                                    ftp_chmod($conn_id, 0777, $temp_extract_dir . '/' . $cp . $p);
                                }
                            }
                            $cp .= $p . '/';
                        }
                    }
                }
            }

            // Giai nen vao thuc muc tam
            $extract = $zip->extract(PCLZIP_OPT_PATH, NV_ROOTDIR . '/' . $temp_extract_dir);

            foreach ($extract as $extract_i) {
                if ($extract_i['status'] != 'ok' and $extract_i['status'] != 'already_a_directory') {
                    $no_extract[] = $extract_i['stored_filename'];
                }

                if ($extract_i['stored_filename'] == 'config.ini' and empty($extract_i['folder']) and $extract_i['status'] == 'ok') {
                    $extConfig = nv_parse_ini_file($extract_i['filename'], true);
                }

                // Xac dinh ung dung he thong hoac module
                if (preg_match("/^modules\/[a-zA-Z0-9\-]+\/version\.php$/", $extract_i['stored_filename'])) {
                    $module_version = array();
                    include $extract_i['filename'];

                    if (isset($module_version['is_sysmod'])) {
                        $fileConfig['sys'] = $module_version['is_sysmod'];
                    }

                    if (isset($module_version['virtual'])) {
                        $fileConfig['virtual'] = $module_version['virtual'];
                    }

                    unset($module_version);
                }

                // Delete .htaccess file
                $array_name_i = explode('/', $extract_i['stored_filename']);

                if ($array_name_i[sizeof($array_name_i) - 1] == '.htaccess') {
                    nv_deletefile($extract_i['filename']);
                }
            }

            $extConfig['extension']['sys'] = 0;
            $extConfig['extension']['virtual'] = 0;

            if (isset($fileConfig['sys'])) {
                $extConfig['extension']['sys'] = $fileConfig['sys'];
            }

            if (isset($fileConfig['virtual'])) {
                $extConfig['extension']['virtual'] = $fileConfig['virtual'];
            }

            if (nv_check_ext_config_filecontent($extConfig) !== true) {
                $xtpl->assign('ERROR', $lang_module['autoinstall_error_downloaded']);
                $xtpl->parse('extract.error');
            } elseif (empty($no_extract)) {
                $array_error_mine = array();
                $error_create_folder = array_unique($error_create_folder);
                $array_cute_files = array();
                $array_exists_files = array();
                $dimiss_mime = $nv_Request->get_title('dismiss', 'get', '') == md5('dismiss' . $filename . NV_CHECK_SESSION) ? true : false;

                // Kiem tra mime
                if (!$dimiss_mime) {
                    $all_ini = array();

                    $data = file(NV_ROOTDIR . '/includes/ini/mime.ini');
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

                        list($key, $value) = explode('=', $line);
                        $key = trim($key);
                        $value = trim($value);
                        $value = str_replace(array('"', "'"), array('', ''), $value);

                        if (preg_match('/^(.*?)\[\]$/', $key, $match)) {
                            $all_ini[$section][$match[1]][] = $value;
                        } else {
                            $all_ini[$section][$key][] = $value;
                        }
                    }

                    $ini = array();
                    foreach ($all_ini as $section => $line) {
                        $ini = array_merge($ini, $line);
                    }

                    // Kiem tra mime file
                    foreach ($ziplistContent as $array_file) {
                        $array_name_i = explode('/', $array_file['stored_filename']);

                        if (!preg_match("/\.(tpl|php)$/i", $array_file['stored_filename']) and $array_file['size'] > 0 and $array_name_i[sizeof($array_name_i) - 1] != '.htaccess' and $array_file['stored_filename'] != 'config.ini') {
                            $mime_real = $mime_check = nv_get_mime_type(NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename']);
                            $file_ext = nv_getextension($array_file['filename']);

                            if (!empty($mime_check) and (!isset($ini[$file_ext]) or !in_array($mime_check, $ini[$file_ext]))) {
                                $mime_check = '';
                            }

                            if (empty($mime_check)) {
                                if (preg_match("/\.(ini)$/i", $array_file['stored_filename'])) {
                                    if ($_xml = @simplexml_load_file(NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename'])) {
                                        continue;
                                    }
                                }
                                $array_error_mine[] = array('mime' => $mime_real, 'filename' => $array_file['stored_filename']);
                            }
                        }
                    }
                }

                if (empty($array_error_mine)) {
                    // Tao thu muc tren he thong neu chua co
                    $extract_dir = NV_ROOTDIR;
                    if (isset($extConfig['extension']['type']) and $extConfig['extension']['type'] == 'theme') {
                        $extract_dir .= '/themes';
                        if (!($ftp_check_login == 1 and ftp_mkdir($conn_id, 'themes'))) {
                            @mkdir($extract_dir);
                        }
                    }

                    foreach ($ziplistContent as $array_file) {
                        $dir_name = '';

                        if (!empty($array_file['folder']) and !file_exists($extract_dir . '/' . $array_file['filename'])) {
                            $dir_name = $array_file['filename'];
                        } elseif (!file_exists($extract_dir . '/' . dirname($array_file['filename']))) {
                            $dir_name = dirname($array_file['filename']);
                        }

                        if (!empty($dir_name)) {
                            $cp = '';
                            $e = explode('/', $dir_name);
                            foreach ($e as $p) {
                                if (!empty($p) and !is_dir($extract_dir . '/' . $cp . $p)) {
                                    if (!($ftp_check_login == 1 and ftp_mkdir($conn_id, $cp . $p))) {
                                        @mkdir($extract_dir . '/' . $cp . $p);
                                    }
                                    if (!is_dir($extract_dir . '/' . $cp . $p)) {
                                        $error_create_folder[] = $cp . $p;
                                        break;
                                    }
                                }
                                $cp .= $p . '/';
                            }
                        }
                    }

                    // Di chuyen cac file vao thu muc trong site
                    if (empty($error_create_folder)) {
                        foreach ($ziplistContent as $array_file) {
                            $array_name_i = explode('/', $extract_i['stored_filename']);

                            if (empty($array_file['folder']) and $array_file['filename'] != 'config.ini' and $array_name_i[sizeof($array_name_i) - 1] != '.htaccess') {
                                // Xoa file neu ton tai
                                if (file_exists(NV_ROOTDIR . '/' . $array_file['filename'])) {
                                    if (!($ftp_check_login == 1 and ftp_delete($conn_id, $array_file['filename']))) {
                                        nv_deletefile(NV_ROOTDIR . '/' . $array_file['filename']);
                                    }

                                    $array_exists_files[] = $array_file['filename'];
                                }

                                // Di chuyen file
                                if (!($ftp_check_login == 1 and ftp_rename($conn_id, $temp_extract_dir . '/' . $array_file['filename'], $array_file['filename']))) {
                                    @rename(NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename'], $extract_dir . '/' . $array_file['filename']);
                                }

                                // Di chuyen that bai
                                if (file_exists(NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename'])) {
                                    $error_move_folder[] = $array_file['filename'];
                                }

                                // Danh sach file quy chuan
                                $array_cute_files[] = $array_file['filename'];
                            }
                        }

                        if (empty($error_move_folder)) {
                            // Luu vao bang extensions neu ung dung chua co
                            $sql = 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_setup_extensions WHERE type=:type AND title=:title';
                            $sth = $db->prepare($sql);
                            $sth->bindParam(':type', $extConfig['extension']['type'], PDO::PARAM_STR);
                            $sth->bindParam(':title', $extConfig['extension']['name'], PDO::PARAM_STR);
                            $sth->execute();

                            if (!$sth->fetchColumn()) {
                                $sql = 'INSERT INTO ' . $db_config['prefix'] . '_setup_extensions VALUES( ' . intval($extConfig['extension']['id']) . ', :type, :title, ' . (intval($extConfig['extension']['sys']) == 1 ? 1 : 0) . ', ' . (intval($extConfig['extension']['virtual']) == 1 ? 1 : 0) . ', :basename, :table_prefix, :version, ' . NV_CURRENTTIME . ', :author, :note )';
                                $table_prefix = preg_replace('/(\W+)/i', '_', $extConfig['extension']['name']);
                                $author = $extConfig['author']['name'] . ' (' . $extConfig['author']['email'] . ')';
                                $version = $extConfig['extension']['version'] . ' ' . NV_CURRENTTIME;

                                $sth = $db->prepare($sql);
                                $sth->bindParam(':type', $extConfig['extension']['type'], PDO::PARAM_STR);
                                $sth->bindParam(':title', $extConfig['extension']['name'], PDO::PARAM_STR);
                                $sth->bindParam(':basename', $extConfig['extension']['name'], PDO::PARAM_STR);
                                $sth->bindParam(':table_prefix', $table_prefix, PDO::PARAM_STR);
                                $sth->bindParam(':version', $version, PDO::PARAM_STR);
                                $sth->bindParam(':author', $author, PDO::PARAM_STR);
                                $sth->bindParam(':note', $extConfig['note']['text'], PDO::PARAM_STR);
                                $sth->execute();
                            }

                            // Danh sach file moi trong mang $array_cute_files
                            // Lay danh sach file neu ung dung da co tren he thong
                            $sql = 'SELECT path FROM ' . $db_config['prefix'] . '_extension_files WHERE type=' . $db->quote($extConfig['extension']['type']) . ' AND title=' . $db->quote($extConfig['extension']['name']);
                            $files = $db->query($sql)->fetchAll(PDO::FETCH_COLUMN, 0);

                            $new_files = array_diff($array_cute_files, $files);
                            $array_exists_files = array_diff($array_exists_files, $files);

                            // Luu danh sach file moi vao CSDL
                            if (!empty($new_files)) {
                                foreach ($new_files as $file) {
                                    $sql = 'INSERT INTO ' . $db_config['prefix'] . '_extension_files VALUES( NULL, :type, :title, :path, ' . NV_CURRENTTIME . ', 0 )';
                                    $sth = $db->prepare($sql);
                                    $sth->bindParam(':type', $extConfig['extension']['type'], PDO::PARAM_STR);
                                    $sth->bindParam(':title', $extConfig['extension']['name'], PDO::PARAM_STR);
                                    $sth->bindParam(':path', $file, PDO::PARAM_STR);
                                    $sth->execute();
                                }
                            }

                            // Cap nhat cac file da co
                            if (!empty($array_exists_files)) {
                                foreach ($array_exists_files as $file) {
                                    $sql = 'UPDATE ' . $db_config['prefix'] . '_extension_files SET duplicate = duplicate + 1 WHERE path = :path';
                                    $sth = $db->prepare($sql);
                                    $sth->bindParam(':path', $file, PDO::PARAM_STR);
                                    $sth->execute();
                                }
                            }
                        }
                    }
                }

                // Xoa file da upload va thu muc tam
                if (empty($array_error_mine)) {
                    nv_deletefile($filename);
                    nv_deletefile(NV_ROOTDIR . '/' . $temp_extract_dir, true);
                }

                if ($ftp_check_login > 0) {
                    ftp_close($conn_id);
                }

                if (!empty($no_extract)) {
                    $i = 0;
                    foreach ($no_extract as $tmp) {
                        $xtpl->assign('FILENAME', $tmp);
                        $xtpl->parse('extract.complete.no_extract.loop');
                        ++$i;
                    }
                    $xtpl->parse('extract.complete.no_extract');
                } elseif (!empty($error_create_folder)) {
                    $i = 0;
                    asort($error_create_folder);
                    foreach ($error_create_folder as $tmp) {
                        $xtpl->assign('FILENAME', $tmp);
                        $xtpl->parse('extract.complete.error_create_folder.loop');
                        ++$i;
                    }
                    $xtpl->parse('extract.complete.error_create_folder');
                } elseif (!empty($error_move_folder)) {
                    $i = 0;
                    asort($error_move_folder);
                    foreach ($error_move_folder as $tmp) {
                        $xtpl->assign('FILENAME', $tmp);
                        $xtpl->parse('extract.complete.error_move_folder.loop');
                        ++$i;
                    }
                    $xtpl->parse('extract.complete.error_move_folder');
                } elseif (!empty($array_error_mine)) {
                    $xtpl->assign('DISMISS_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&extract=' . md5($filename . NV_CHECK_SESSION) . '&dismiss=' . md5('dismiss' . $filename . NV_CHECK_SESSION));

                    $i = 0;
                    asort($array_error_mine);
                    foreach ($array_error_mine as $tmp) {
                        $xtpl->assign('FILENAME', $tmp['filename']);
                        $xtpl->assign('MIME', $tmp['mime']);
                        $xtpl->parse('extract.complete.error_mine.loop');
                        ++$i;
                    }
                    $xtpl->parse('extract.complete.error_mine');
                } else {
                    if ($extConfig['extension']['type'] == 'module') {
                        $xtpl->assign('URL_GO', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=modules&' . NV_OP_VARIABLE . '=setup');
                    } elseif ($extConfig['extension']['type'] == 'theme') {
                        $xtpl->assign('URL_GO', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=themes');
                    } elseif ($extConfig['extension']['type'] == 'block') {
                        $xtpl->assign('URL_GO', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=themes&' . NV_OP_VARIABLE . '=blocks');
                    } elseif ($extConfig['extension']['type'] == 'cronjob') {
                        $xtpl->assign('URL_GO', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=settings&' . NV_OP_VARIABLE . '=cronjobs_add&file=' . $extConfig['extension']['name']);
                    } else {
                        $xtpl->assign('URL_GO', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' + $module_name + '&' . NV_OP_VARIABLE . '=' . $op);
                    }

                    $xtpl->parse('extract.complete.ok');
                }

                $xtpl->parse('extract.complete');
            }
        }

        $xtpl->parse('extract');
        $contents = $xtpl->text('extract');

        include NV_ROOTDIR . '/includes/header.php';
        echo $contents;
        include NV_ROOTDIR . '/includes/footer.php';
    }

    die('Error Access!!!');
}

$error = "";
$info = array();

if ($nv_Request->isset_request('uploaded', 'get')) {
    if (!file_exists($filename)) {
        $error = $lang_module['autoinstall_error_downloaded'];
    }
} elseif ($global_config['extension_setup'] == 1 or $global_config['extension_setup'] == 3) {
    if (!isset($_FILES, $_FILES['extfile'], $_FILES['extfile']['tmp_name'])) {
        $error = $lang_module['autoinstall_error_downloaded'];
    } elseif (!$sys_info['zlib_support']) {
        $error = $lang_global['error_zlib_support'];
    } elseif (!empty($_FILES['extfile']['error'])) {
        $error = sprintf($lang_module['autoinstall_error_uploadfile1'], nv_convertfromBytes(NV_UPLOAD_MAX_FILESIZE));
    } elseif (is_uploaded_file($_FILES['extfile']['tmp_name'])) {
        if (file_exists($filename)) {
            nv_deletefile($filename);
        }

        if (!move_uploaded_file($_FILES['extfile']['tmp_name'], $filename)) {
            $error = $lang_module['autoinstall_error_uploadfile'];
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['autoinstall_install'], basename($_FILES['extfile']['name']), $admin_info['userid']);

        if (!file_exists($filename)) {
            $error = $lang_module['autoinstall_error_downloaded'];
        }
    } else {
        $error = $lang_module['autoinstall_error_downloaded'];
    }
}

// Lay thong tin file tai len
if (empty($error)) {
    $arraySysOption = array(
        'allowfolder' => array(
            'assets',
            'themes',
            'modules',
            'uploads',
            'includes/plugin',
            'vendor'
        ),
        'forbidExt' => array(
            'php',
            'php3',
            'php4',
            'php5',
            'phtml',
            'inc'
        ),
        'allowExtType' => array(
            'module',
            'block',
            'theme',
            'cron'
        ),
        'checkName' => array(
            'module' => $global_config['check_module'],
            'block' => array($global_config['check_block_module'], $global_config['check_block_theme']),
            'theme' => $global_config['check_theme'],
            'cron' => $global_config['check_cron'],
        ),
    );

    $zip = new PclZip($filename);
    $status = $zip->properties();

    if ($status['status'] == 'ok') {
        $listFiles = $zip->listContent();
        $sizeLists = sizeof($listFiles);
        $iniIndex = -1;

        // Tim ra vi tri file config.ini
        for ($i = $sizeLists - 1; $i >= 0; --$i) {
            if (!$listFiles[$i]['folder'] and trim($listFiles[$i]['filename']) == 'config.ini') {
                $iniIndex = $i;
                break;
            }
        }

        // Loi khong co file cau hinh
        if ($iniIndex == -1) {
            $error = $lang_module['autoinstall_error_missing_cfg'];
        } else {
            // Giai nen file config de doc thong tin
            $temp_extract_dir = NV_TEMP_DIR;

            // Xoa file config neu ton tai
            if (file_exists(NV_ROOTDIR . '/' . $temp_extract_dir . '/config.ini')) {
                @nv_deletefile(NV_ROOTDIR . '/' . $temp_extract_dir . '/config.ini');
            }

            $extract = $zip->extractByIndex($iniIndex, PCLZIP_OPT_PATH, NV_ROOTDIR . '/' . $temp_extract_dir);

            if (empty($extract) or !isset($extract[0]['status']) or $extract[0]['status'] != 'ok' or !file_exists(NV_ROOTDIR . '/' . $temp_extract_dir . '/config.ini')) {
                $error = $lang_module['autoinstall_cantunzip'];
            } else {
                // Doc, kiem tra thong tin file config.ini
                $extConfig = nv_parse_ini_file(NV_ROOTDIR . '/' . $temp_extract_dir . '/config.ini', true);
                $extConfigCheck = nv_check_ext_config_filecontent($extConfig);

                if (!$extConfigCheck) {
                    $error = $lang_module['autoinstall_error_cfg_content'];
                } elseif (!in_array($extConfig['extension']['type'], $arraySysOption['allowExtType'])) {
                    $error = $lang_module['autoinstall_error_cfg_type'];
                } elseif (!preg_match($global_config['check_version'], $extConfig['extension']['version'])) {
                    $error = $lang_module['autoinstall_error_cfg_version'];
                } elseif (is_array($arraySysOption['checkName'][$extConfig['extension']['type']])) {
                    foreach ($arraySysOption['checkName'][$extConfig['extension']['type']] as $check) {
                        if (!preg_match($check, $extConfig['extension']['name'])) {
                            $error = $lang_module['autoinstall_error_cfg_name'];
                        }
                    }
                } elseif (!preg_match($arraySysOption['checkName'][$extConfig['extension']['type']], $extConfig['extension']['name'])) {
                    $error = $lang_module['autoinstall_error_cfg_name'];
                }

                @nv_deletefile(NV_ROOTDIR . '/' . $temp_extract_dir . '/config.ini');
            }
        }

        unset($check, $extract, $iniIndex, $extConfigCheck);

        // Duyet danh sach file lay thong tin va kiem tra
        if (empty($error)) {
            $info['classcfg'] = array('invaild' => 'fa-exclamation-triangle', 'exists' => 'fa-info');
            $info['extname'] = $extConfig['extension']['name'];
            $info['exttype'] = $extConfig['extension']['type'];
            $info['extversion'] = $extConfig['extension']['version'];
            $info['extauthor'] = $extConfig['author']['name'] . ' (' . $extConfig['author']['email'] . ')';
            $info['filesize'] = nv_convertfromBytes(filesize($filename));
            $info['filenum'] = $status['nb'];
            $info['existsnum'] = 0; // So file trung lap
            $info['invaildnum'] = 0; // So file khong hop chuan
            $info['filelist'] = array(); // Danh sach cac file
            $info['checkresult'] = 'success'; // success - warning - fail

            for ($i = 0, $j = 1; $i < $sizeLists; ++$i, ++$j) {
                // Xac dinh dung luong file tai len
                if (!$listFiles[$i]['folder']) {
                    $bytes = nv_convertfromBytes($listFiles[$i]['size']);
                } else {
                    $bytes = '';
                }

                $info['filelist'][$j] = array(
                    'title' => '[' . $j . '] ' . ($info['exttype'] == 'theme' ? 'themes/' : '') . $listFiles[$i]['filename'] . ' ' . $bytes,
                    'class' => array(),
                );

                // Kiem tra file ton tai tren he thong
                if (empty($listFiles[$i]['folder']) and (($info['exttype'] == 'theme' and file_exists(NV_ROOTDIR . '/themes/' . trim($listFiles[$i]['filename']))) or ($info['exttype'] != 'theme' and file_exists(NV_ROOTDIR . '/' . trim($listFiles[$i]['filename']))))) {
                    $info['existsnum']++;
                    $info['filelist'][$j]['class'][] = $info['classcfg']['exists'];

                    if ($info['checkresult'] != 'fail') {
                        $info['checkresult'] = 'warning';
                    }
                }

                // Check valid folder structure nukeviet (modules, themes, uploads)
                $folder = explode('/', $listFiles[$i]['filename']);

                if (trim($listFiles[$i]['filename']) != 'config.ini' and (($info['exttype'] == 'theme' and $folder[0] != $info['extname']) or ($info['exttype'] != 'theme' and !in_array($folder[0], $arraySysOption['allowfolder']) and (isset($folder[1]) and !in_array($folder[0] . '/' . $folder[1], $arraySysOption['allowfolder']))) or ($folder[0] == 'assets' and in_array(nv_getextension($listFiles[$i]['filename']), $arraySysOption['forbidExt'])))) {
                    $info['invaildnum']++;
                    $info['filelist'][$j]['class'][] = $info['classcfg']['invaild'];
                    $info['checkresult'] = 'fail';

                    // Delete file
                    nv_deletefile($filename);
                }
            }
        }
    } else {
        $error = $lang_module['autoinstall_error_invalidfile'];
    }
}

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('info.error');
}

if (!empty($info)) {
    $info['exttype'] = isset($lang_module['extType_' . $info['exttype']]) ? $lang_module['extType_' . $info['exttype']] : $lang_module['extType_other'];

    $xtpl->assign('INFO', $info);
    $xtpl->assign('EXTRACTLINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&extract=' . md5($filename . NV_CHECK_SESSION));

    // Thong bao trong thai ung dung
    if ($info['checkresult'] == 'success') {
        $xtpl->parse('info.fileinfo.success');
    } elseif ($info['checkresult'] == 'warning') {
        $xtpl->parse('info.fileinfo.warning');
    } else {
        $xtpl->parse('info.fileinfo.fail');
    }

    if (!empty($info['filelist'])) {
        $i = 0;
        foreach ($info['filelist'] as $file) {
            $xtpl->assign('FILE', $file['title']);

            if (!empty($file['class'])) {
                foreach ($file['class'] as $icon) {
                    $xtpl->assign('ICON', $icon);
                    $xtpl->parse('info.fileinfo.file.loop.icons.icon');
                }

                $xtpl->parse('info.fileinfo.file.loop.icons');
            }

            $xtpl->parse('info.fileinfo.file.loop');
            ++$i;
        }

        $xtpl->parse('info.fileinfo.file');
    }

    $xtpl->parse('info.fileinfo');
}

$xtpl->parse('info');
$contents = $xtpl->text('info');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
