<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 28/10/2012, 14:51
 */

if (! defined('NV_IS_FILE_SETTINGS')) {
    die('Stop!!!');
}

if (NV_CHECK_SESSION == $nv_Request->get_string('cdndl', 'get')) {
    $dir = NV_ROOTDIR;
    $allowzip = array();
    $allowzip[] = $dir . '/.htaccess';
    $allowzip[] = $dir . '/web.config';
    $allowzip[] = $dir . '/modules/index.html';
    $allowzip[] = $dir . '/themes/index.html';
    $allowzip[] = $dir . '/' . NV_EDITORSDIR . '/index.html';
    $dir_no_scan = array( NV_ROOTDIR . '/' . 'install', NV_ROOTDIR . '/' . NV_ADMINDIR, NV_ROOTDIR . '/' . NV_UPLOADS_DIR, NV_ROOTDIR . '/' . NV_FILES_DIR, NV_ROOTDIR . '/' . NV_LOGS_DIR, NV_ROOTDIR . '/' . NV_TEMP_DIR, NV_ROOTDIR . '/' . NV_DATADIR, NV_ROOTDIR . '/' . NV_CACHEDIR );
    $error = array();
    //Ten thu muc luu data
    $stack[] = $dir;
    while ($stack) {
        $thisdir = array_pop($stack);
        if ($dircont = scandir($thisdir)) {
            $i = 0;
            while (isset($dircont[$i])) {
                if ($dircont[$i] != '.' and $dircont[$i] != '..') {
                    $current_file = $thisdir . '/' . $dircont[$i];
                    if (is_file($current_file)) {
                        if (preg_match('/\.js$/', $dircont[$i])) {
                            $filename = $thisdir . '/' . $dircont[$i];
                            $allowzip[] = $filename;
                            $filename = dirname($filename) . '/index.html';
                            if (! in_array($filename, $allowzip)) {
                                if (file_exists($filename)) {
                                    $allowzip[] = $filename;
                                }
                            }
                        } elseif (preg_match('/\.css/', $dircont[$i])) {
                            $filename = $thisdir . '/' . $dircont[$i];
                            $allowzip[] = $filename;
                            $css = file_get_contents($filename);
                            $filename = dirname($filename) . '/index.html';
                            if (! in_array($filename, $allowzip)) {
                                if (file_exists($filename)) {
                                    $allowzip[] = $filename;
                                }
                            }
                            if (preg_match_all("/url[\s]*\([\s]*[\'\"]*([^\'\"\)]+)[\'\"]*[\s]*\)/", $css, $m)) {
                                foreach ($m[1] as $fimg) {
                                    if (preg_match('/\.(gif|jpg|jpeg|png|bmp)$/i', $fimg)) {
                                        $filename = $thisdir . '/' . $fimg;
                                        while (preg_match('/([^\/(\.\.)]+)\/\.\.\//', $filename)) {
                                            $filename = preg_replace('/([^\/(\.\.)]+)\/\.\.\//', '', $filename);
                                        }
                                        if (file_exists($filename)) {
                                            $allowzip[] = $filename;
                                            $filename = dirname($filename) . '/index.html';
                                            if (! in_array($filename, $allowzip)) {
                                                if (file_exists($filename)) {
                                                    $allowzip[] = $filename;
                                                }
                                            }
                                        } else {
                                            $error[$thisdir . '/' . $dircont[$i]][] = $fimg . ' ---- ' . $filename;
                                        }
                                    }
                                }
                            }
                        }
                    } elseif (is_dir($current_file) and ! in_array($current_file, $dir_no_scan)) {
                        $stack[] = $current_file;
                    }
                }
                $i++;
            }
        }
    }
    if (empty($error)) {
        $allowzip = array_unique($allowzip);
        $file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'cdn_' . md5(nv_genpass(10) . NV_CHECK_SESSION) . '.zip';
        $zip = new PclZip($file_src);
        $zip->add($allowzip, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR);
        $zip->add(NV_ROOTDIR . '/themes/index.html', PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . '/themes');

        //Download file
        $download = new NukeViet\Files\Download($file_src, NV_ROOTDIR . '/' . NV_TEMP_DIR, 'js_css_cdn_' . date('Ymd') . '.zip');
        $download->download_file();
        exit();
    } else {
        $page_title = 'File not exit';
        $contents = '<br>';
        foreach ($error as $key => $value) {
            $value = array_unique($value);
            asort($value);
            $contents .= '<strong>' . $key . ' </strong><br>&nbsp;&nbsp;&nbsp;&nbsp; ' . implode('<br>&nbsp;&nbsp;&nbsp;&nbsp;', $value) . '<br><br>';
        }
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents);
        include NV_ROOTDIR . '/includes/footer.php';
    }
}