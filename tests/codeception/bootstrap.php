<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

define('NV_TEST_ROOTDIR', rtrim(str_replace('\\', '/', realpath(__DIR__ . '/../../')), '/'));
define('NV_TEST_DIR', NV_TEST_ROOTDIR . '/tests/codeception');
define('NV_ROOTDIR', NV_TEST_ROOTDIR . '/src');

date_default_timezone_set('Asia/Ho_Chi_Minh');

require NV_TEST_ROOTDIR . '/vendor/autoload.php';

// Đọc các biến môi trường để test
if (file_exists(NV_TEST_ROOTDIR . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(NV_TEST_ROOTDIR);
    $dotenv->load();
}
