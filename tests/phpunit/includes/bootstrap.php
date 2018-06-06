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

require NV_TEST_ROOTDIR . '/vendor/autoload.php';

/** @test */
echo 'Init...' . PHP_EOL;
