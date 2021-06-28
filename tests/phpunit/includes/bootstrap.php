<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

define('NV_TEST_ROOTDIR', rtrim(str_replace('\\', '/', realpath(__DIR__ . '/../../../')), '/'));
define('NV_TEST_DIR', NV_TEST_ROOTDIR . '/tests/phpunit/includes');
define('NV_ROOTDIR', NV_TEST_ROOTDIR . '/src');

require NV_TEST_ROOTDIR . '/vendor/autoload.php';

// Load file cấu hình test
require NV_TEST_ROOTDIR . '/tests-config.php';

// Cài đặt site mới
if (is_file(NV_ROOTDIR . '/config.php')) {
    unlink(NV_ROOTDIR . '/config.php');
}

// Uncomment for debug
//echo('Installing... ' . PHP_EOL);
for ($i = 1; $i <= 8; ++$i) {
    // Uncomment for debug
    //echo('Step ' . $i . '...' . PHP_EOL);
    unset($installRes);
    exec(NV_PHP_CMD . ' ' . escapeshellarg(NV_TEST_DIR . '/install.php') . ' ' . escapeshellarg($i) . ' ', $installRes);
    if ($installRes[0] !== 'OK') {
        exit('Test failed: Error install new site in step ' . $i . PHP_EOL);
    }
}

define('NV_WYSIWYG', true);
define('NV_ADMIN', true);
require NV_TEST_DIR . '/functions.php';
_tests_default_server();

require NV_ROOTDIR . '/includes/mainfile.php';
require NV_TEST_DIR . '/nv-testcase.php';
