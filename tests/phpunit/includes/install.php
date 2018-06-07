<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC.
 * All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

define('NV_TEST_ROOTDIR', rtrim(str_replace('\\', '/', realpath(__DIR__ . '/../../../')), '/'));
define('NV_TEST_DIR', NV_TEST_ROOTDIR . '/tests/phpunit/includes');
define('NV_ROOTDIR', NV_TEST_ROOTDIR . '/src');

// Load file cấu hình test
require NV_TEST_ROOTDIR . '/tests-config.php';
require NV_TEST_DIR . '/functions.php';

_tests_default_server();

define('NV_TESTS_INSTALL', true);

$step = $argv[1];

$_GET['language'] = $_POST['language'] = NV_TESTS_LANG;

// Các thông số cấu hình test CSDL
if ($step == 5) {
    $_POST['dbtype'] = NV_DB_TESTS_DBTYPE;
    $_POST['dbhost'] = NV_DB_TESTS_DBHOST;
    $_POST['dbname'] = NV_DB_TESTS_DBNAME;
    $_POST['dbuname'] = NV_DB_TESTS_DBUNAME;
    $_POST['dbpass'] = NV_DB_TESTS_DBPASS;
    $_POST['prefix'] = NV_DB_TESTS_PREFIX;
    $_POST['dbport'] = NV_DB_TESTS_DBPORT;

    $_POST['db_detete'] = true;
} elseif ($step == 6) {
    // Các thông số cấu hình site test
    $_POST['site_name'] = NV_SITE_TESTS_TITLE;
    $_POST['nv_login'] = NV_ADMIN_TESTS_USERNAME;
    $_POST['nv_email'] = NV_ADMIN_TESTS_EMAIL;
    $_POST['nv_password'] = NV_ADMIN_TESTS_PASSWORD;
    $_POST['re_password'] = NV_ADMIN_TESTS_PASSWORD;
    $_POST['lang_multi'] = NV_SITE_TESTS_LANG_MULTI;
    $_POST['question'] = NV_ADMIN_TESTS_QUESTION;
    $_POST['answer_question'] = NV_ADMIN_TESTS_ANSWER;
}

require NV_ROOTDIR . '/install/index.php';
