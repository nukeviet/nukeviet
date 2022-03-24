<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_EXTENSIONS')) {
    exit('Stop!!!');
}

$page_title = $lang_module['extUpd'];

$eid = $nv_Request->get_int('eid', 'get', 0);
$fid = $nv_Request->get_int('fid', 'get', 0);

if ($nv_Request->get_title('checksess', 'get', '') == md5('unzip' . $eid . $fid . NV_CHECK_SESSION)) {
    $xtpl = new XTemplate('update.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);

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

        if (!empty($no_extract)) {
            $i = 0;
            foreach ($no_extract as $tmp) {
                $xtpl->assign('FILENAME', $tmp);
                $xtpl->parse('complete.no_extract.loop');
                ++$i;
            }

            $xtpl->parse('complete.no_extract');
        } elseif (!empty($error_create_folder)) {
            $i = 0;
            asort($error_create_folder);

            foreach ($error_create_folder as $tmp) {
                $xtpl->assign('FILENAME', $tmp);
                $xtpl->parse('complete.error_create_folder.loop');
                ++$i;
            }

            $xtpl->parse('complete.error_create_folder');
        } elseif (!empty($error_move_folder)) {
            $i = 0;
            asort($error_move_folder);

            foreach ($error_move_folder as $tmp) {
                $xtpl->assign('FILENAME', $tmp);
                $xtpl->parse('complete.error_move_folder.loop');
                ++$i;
            }

            $xtpl->parse('complete.error_move_folder');
        } else {
            $xtpl->assign('URL_GO', NV_BASE_SITEURL . 'install/update.php');
            $xtpl->parse('complete.ok');
        }

        $xtpl->parse('complete');
        echo $xtpl->text('complete');
    }

    exit();
}

if ($nv_Request->get_title('checksess', 'get', '') == md5('download' . $eid . $fid . NV_CHECK_SESSION)) {
    $xtpl = new XTemplate('update.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);

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
        $error = $lang_module['extUpdErrorDownload'];
    }

    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);

        $xtpl->parse('error');
        echo $xtpl->text('error');
    } else {
        $zip = new PclZip(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $filename);
        $ziplistContent = $zip->listContent();

        $warning = false;

        // Check security
        foreach ($ziplistContent as $zipContent) {
            if (!preg_match("/^install\//is", $zipContent['filename'])) {
                $warning = true;
            }
        }

        if ($warning === true) {
            $xtpl->assign('MESSAGE', sprintf($lang_module['get_update_warning'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=extensions&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;eid=' . $eid . '&amp;fid=' . $fid . '&amp;checksess=' . md5('unzip' . $eid . $fid . NV_CHECK_SESSION)));

            $xtpl->parse('warning');
            echo $xtpl->text('warning');
        } else {
            $xtpl->assign('MESSAGE', sprintf($lang_module['get_update_ok'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=extensions&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;eid=' . $eid . '&amp;fid=' . $fid . '&amp;checksess=' . md5('unzip' . $eid . $fid . NV_CHECK_SESSION)));

            $xtpl->parse('ok');
            echo $xtpl->text('ok');
        }
    }

    exit();
}

if ($nv_Request->get_title('checksess', 'get', '') == md5('check' . $eid . $fid . NV_CHECK_SESSION)) {
    $xtpl = new XTemplate('update.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);

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
    $array = (is_array($array) and !empty($array['body'])) ? @unserialize($array['body']) : [];

    $error = '';
    if (!empty(NukeViet\Http\Http::$error)) {
        $error = nv_http_get_lang(NukeViet\Http\Http::$error);
    } elseif (empty($array['status']) or !isset($array['error']) or !isset($array['data']) or !isset($array['pagination']) or !is_array($array['error']) or !is_array($array['data']) or !is_array($array['pagination']) or (!empty($array['error']) and (!isset($array['error']['level']) or empty($array['error']['message'])))) {
        $error = $lang_global['error_valid_response'];
    } elseif (!empty($array['error']['message'])) {
        $error = $array['error']['message'];
    }

    // Show error
    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);

        $xtpl->parse('error');
        echo $xtpl->text('error');
    }

    $array = $array['data'];

    // Ok co the download
    if ($array['fileInfo'] === 'ready') {
        $array['icon'] = 'fa-check';
        $array['message'] = $lang_module['extUpdCheckSuccess'];
        $array['class'] = 'success';

        $xtpl->assign('LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=update&eid=' . $eid . '&fid=' . $fid . '&checksess=' . md5('download' . $eid . $fid . NV_CHECK_SESSION));
        $xtpl->parse('check.ready');
    } elseif ($array['fileInfo'] == 'notlogin') {
        $array['icon'] = 'fa-frown-o';
        $array['message'] = $lang_module['extUpdNotLogin'];
        $array['class'] = 'warning';

        $redirect = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=update&eid=' . $eid . '&fid=' . $fid . '&checksess=' . md5($eid . $fid . NV_CHECK_SESSION);
        $xtpl->assign('MESSAGE', sprintf($lang_module['extUpdLoginRequire'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;redirect=' . nv_redirect_encrypt($redirect)));
        $xtpl->parse('check.message');
    } elseif ($array['fileInfo'] == 'unpaid') {
        $array['icon'] = 'fa-frown-o';
        $array['message'] = $lang_module['extUpdUnpaid'];
        $array['class'] = 'warning';

        $xtpl->assign('MESSAGE', sprintf($lang_module['extUpdPaidRequire'], $array['link'] . '#tabs-1'));
        $xtpl->parse('check.message');
    } else {
        $array['icon'] = 'fa-frown-o';
        $array['message'] = $lang_module['extUpdInvalid'];
        $array['class'] = 'danger';

        $xtpl->assign('MESSAGE', $lang_module['extUpdInvalidNote']);
        $xtpl->parse('check.message');
    }

    $xtpl->assign('DATA', $array);

    $xtpl->parse('check');
    echo $xtpl->text('check');
    exit();
}

if ($nv_Request->get_title('checksess', 'get', '') == md5($eid . $fid . NV_CHECK_SESSION)) {
    $xtpl = new XTemplate('update.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('EID', $eid);
    $xtpl->assign('FID', $fid);
    $xtpl->assign('CHECKSESS', md5('check' . $eid . $fid . NV_CHECK_SESSION));

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
