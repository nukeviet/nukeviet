<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 9/9/2010, 6:38
 */

if (!defined('NV_IS_FILE_EXTENSIONS')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('extUpd');

$eid = $nv_Request->get_int('eid', 'get', 0);
$fid = $nv_Request->get_int('fid', 'get', 0);

// Giải nén và kiểm tra gói cập nhật
if ($nv_Request->get_title('checksess', 'get', '') == md5('unzip' . $eid . $fid . NV_CHECK_SESSION)) {
    $tpl = new \NukeViet\Template\Smarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $tpl->assign('LANG', $nv_Lang);

    $filename = NV_TEMPNAM_PREFIX . 'extupd_' . NV_CHECK_SESSION . '.zip';

    if (file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $filename)) {
        $zip = new PclZip(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $filename);
        $ziplistContent = $zip->listContent();

        $temp_extract_dir = NV_TEMP_DIR . '/' . md5($filename . NV_CHECK_SESSION);

        $no_extract = [];
        $error_create_folder = [];
        $error_move_folder = [];

        if (is_dir(NV_ROOTDIR . '/' . $temp_extract_dir)) {
            nv_deletefile(NV_ROOTDIR . '/' . $temp_extract_dir, true);
        }

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

        $extract = $zip->extract(PCLZIP_OPT_PATH, NV_ROOTDIR . '/' . $temp_extract_dir);

        foreach ($extract as $extract_i) {
            $filename_i = str_replace(NV_ROOTDIR, '', str_replace('\\', '/', $extract_i['filename']));

            if ($extract_i['status'] != 'ok' and $extract_i['status'] != 'already_a_directory') {
                $no_extract[] = $filename_i;
            }
        }

        if (empty($no_extract)) {
            foreach ($ziplistContent as $array_file) {
                $dir_name = '';

                if (!empty($array_file['folder']) and !file_exists(NV_ROOTDIR . '/' . $array_file['filename'])) {
                    $dir_name = $array_file['filename'];
                } elseif (!file_exists(NV_ROOTDIR . '/' . dirname($array_file['filename']))) {
                    $dir_name = dirname($array_file['filename']);
                }

                if (!empty($dir_name)) {
                    $cp = '';
                    $e = explode('/', $dir_name);

                    foreach ($e as $p) {
                        if (!empty($p) and !is_dir(NV_ROOTDIR . '/' . $cp . $p)) {
                            if (!($ftp_check_login == 1 and ftp_mkdir($conn_id, $cp . $p))) {
                                @mkdir(NV_ROOTDIR . '/' . $cp . $p);
                            }
                            if (!is_dir(NV_ROOTDIR . '/' . $cp . $p)) {
                                $error_create_folder[] = $cp . $p;
                                break;
                            }
                        }

                        $cp .= $p . '/';
                    }
                }
            }

            $error_create_folder = array_unique($error_create_folder);

            if (empty($error_create_folder)) {
                foreach ($ziplistContent as $array_file) {
                    if (empty($array_file['folder'])) {
                        if (file_exists(NV_ROOTDIR . '/' . $array_file['filename'])) {
                            if (!($ftp_check_login == 1 and ftp_delete($conn_id, $array_file['filename']))) {
                                nv_deletefile(NV_ROOTDIR . '/' . $array_file['filename']);
                            }
                        }

                        if (!($ftp_check_login == 1 and ftp_rename($conn_id, $temp_extract_dir . '/' . $array_file['filename'], $array_file['filename']))) {
                            @rename(NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename'], NV_ROOTDIR . '/' . $array_file['filename']);
                        }

                        if (file_exists(NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename'])) {
                            $error_move_folder[] = $array_file['filename'];
                        }
                    }
                }

                if (empty($error_move_folder)) {
                    nv_deletefile($filename);
                    nv_deletefile(NV_ROOTDIR . '/' . $temp_extract_dir, true);
                }
            }

            if ($ftp_check_login > 0) {
                ftp_close($conn_id);
            }
        }

        asort($error_create_folder);
        asort($error_move_folder);

        $tpl->assign('NO_EXTRACT', $no_extract);
        $tpl->assign('ERROR_CREATE_FOLDER', $error_create_folder);
        $tpl->assign('ERROR_MOVE_FOLDER', $error_move_folder);
        $tpl->assign('URL_GO', NV_BASE_SITEURL . 'install/update.php');

        $contents = $tpl->fetch('update_unzip.tpl');

        include NV_ROOTDIR . '/includes/header.php';
        echo $contents;
        include NV_ROOTDIR . '/includes/footer.php';
    }

    nv_htmlOutput('File not exists!!!');
}

// Tải về gói cập nhật
if ($nv_Request->get_title('checksess', 'get', '') == md5('download' . $eid . $fid . NV_CHECK_SESSION)) {
    $tpl = new \NukeViet\Template\Smarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $tpl->assign('LANG', $nv_Lang);

    $NV_Http = new NukeViet\Http\Http($global_config, NV_TEMP_DIR);
    $stored_cookies = nv_get_cookies();

    $filename = NV_TEMPNAM_PREFIX . 'extupd_' . NV_CHECK_SESSION . '.zip';

    // Debug
    $args = [
        'headers' => [
            'Referer' => NUKEVIET_STORE_APIURL,
        ],
        'cookies' => $stored_cookies,
        'stream' => true,
        'filename' => NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $filename,
        'body' => [
            'lang' => NV_LANG_INTERFACE,
            'basever' => $global_config['version'],
            'mode' => 'getupdfile',
            'eid' => $eid,
            'fid' => $fid
        ],
        'timeout' => 0
    ];

    // Delete temp file if exists
    if (file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $filename)) {
        @nv_deletefile(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $filename);
    }

    $apidata = $NV_Http->post(NUKEVIET_STORE_APIURL, $args);

    if (!empty(NukeViet\Http\Http::$error)) {
        $error = nv_http_get_lang(NukeViet\Http\Http::$error);
    } elseif (empty($apidata['filename']) or !file_exists($apidata['filename']) or filesize($apidata['filename']) == 0) {
        $error = $nv_Lang->getModule('extUpdErrorDownload');
    }

    $tpl->assign('ERROR', $error);

    if (empty($error)) {
        $zip = new PclZip(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $filename);
        $ziplistContent = $zip->listContent();

        $warning = false;

        // Check security
        foreach ($ziplistContent as $zipContent) {
            if (!preg_match("/^install\//is", $zipContent['filename'])) {
                $warning = true;
            }
        }

        $tpl->assign('WARNING', $warning);
        $tpl->assign('LINK_UNZIP', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=extensions&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;eid=' . $eid . '&amp;fid=' . $fid . '&amp;checksess=' . md5('unzip' . $eid . $fid . NV_CHECK_SESSION));
    }

    $contents = $tpl->fetch('update_download.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

// Kiểm tra gói cập nhật: Đăng nhập, có phí, miễn phí, thanh toán...
if ($nv_Request->get_title('checksess', 'get', '') == md5('check' . $eid . $fid . NV_CHECK_SESSION)) {
    $tpl = new \NukeViet\Template\Smarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $tpl->assign('LANG', $nv_Lang);

    $NV_Http = new NukeViet\Http\Http($global_config, NV_TEMP_DIR);
    $stored_cookies = nv_get_cookies();

    // Lay thong tin ung dung cung nhu lay quyen nang cap
    $args = [
        'headers' => [
            'Referer' => NUKEVIET_STORE_APIURL,
        ],
        'cookies' => $stored_cookies,
        'body' => [
            'lang' => NV_LANG_DATA,
            'basever' => $global_config['version'],
            'mode' => 'checkupd',
            'eid' => $eid,
            'fid' => $fid
        ]
    ];

    $array = $NV_Http->post(NUKEVIET_STORE_APIURL, $args);
    $array = !empty($array['body']) ? @unserialize($array['body']) : [];

    $error = '';
    if (!empty(NukeViet\Http\Http::$error)) {
        $error = nv_http_get_lang(NukeViet\Http\Http::$error);
    } elseif (empty($array['status']) or !isset($array['error']) or !isset($array['data']) or !isset($array['pagination']) or !is_array($array['error']) or !is_array($array['data']) or !is_array($array['pagination']) or (!empty($array['error']) and (!isset($array['error']['level']) or empty($array['error']['message'])))) {
        $error = $nv_Lang->getGlobal('error_valid_response');
    } elseif (!empty($array['error']['message'])) {
        $error = $array['error']['message'];
    }

    $tpl->assign('ERROR', $error);

    if (empty($error)) {
        $array = $array['data'];
        $redirect = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=update&eid=' . $eid . '&fid=' . $fid . '&checksess=' . md5($eid . $fid . NV_CHECK_SESSION);

        $tpl->assign('DATA', $array);
        $tpl->assign('LINK_DOWNLOAD', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=update&eid=' . $eid . '&fid=' . $fid . '&checksess=' . md5('download' . $eid . $fid . NV_CHECK_SESSION));
        $tpl->assign('LINK_LOGIN', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;redirect=' . nv_redirect_encrypt($redirect));
    }

    $contents = $tpl->fetch('update_check.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

if ($nv_Request->get_title('checksess', 'get', '') == md5($eid . $fid . NV_CHECK_SESSION)) {
    $tpl = new \NukeViet\Template\Smarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);

    $tpl->assign('EID', $eid);
    $tpl->assign('FID', $fid);
    $tpl->assign('CHECKSESS', md5('check' . $eid . $fid . NV_CHECK_SESSION));

    $contents = $tpl->fetch('update.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 404);
