<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_CONSOLE')) {
    die('Stop!!!');
}

$parentDirName = basename(__DIR__);
if ($parentDirName === 'private') {
    define('NV_ROOTDIR', str_replace(DIRECTORY_SEPARATOR, '/', realpath(pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME) . '/../public_html')));
    $_SERVER['HTTP_HOST'] = 'nukeviet.vn';
} else {
    define('NV_ROOTDIR', str_replace(DIRECTORY_SEPARATOR, '/', realpath(pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME) . '/../src')));
    $_SERVER['HTTP_HOST'] = 'dev.nukeviet50.local';
}

$_SERVER['HTTPS'] = 'on';
$_SERVER['SERVER_PORT'] = '443';
$_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['SERVER_PROTOCOL'] = 'http';
$_SERVER['REQUEST_URI'] = '';
$_SERVER['PHP_SELF'] = '';
$_SERVER['HTTP_CLIENT_IP'] = '127.0.0.1';
$_SERVER['HTTP_USER_AGENT'] = 'Local shell';

$_GET['language'] = 'vi';

define('NV_IS_LOCAL_CONSOLE', true);

/**
 *
 * @param double $start
 * @param double $end
 * @return string
 */
function getConsoleExecuteTime($start, $end)
{
    $times = ($end - $start) * 1000;
    if ($times < 1000) {
        return (intval($times) . 'ms');
    }
    $times = intval($times / 1000);
    return nv_convertfromSec($times);
}

/**
 * @param string $message
 * @return void
 */
function logAndEcho(string $message): void
{
    global $log_file;
    echo $message;
    !empty($log_file) && file_put_contents($log_file, $message, FILE_APPEND);
}
