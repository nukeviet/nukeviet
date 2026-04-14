<?php

/**
 * @Project NUKEVIET 5.0
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2024 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

use NukeViet\Module\Devtool\Schema\SchemaRepository;

$table = $nv_Request->get_title('table', 'get', '');
$schemaRepo = new SchemaRepository($db, NV_ROOTDIR . '/data/devtool');

if (!$schemaRepo->tableExists($table)) {
    nv_jsonOutput(['status' => 'error', 'mess' => $nv_Lang->getModule('error_invalid_table')]);
}

$dbColumns = $schemaRepo->getTableColumns($table);
nv_jsonOutput(['status' => 'OK', 'columns' => array_keys($dbColumns)]);
