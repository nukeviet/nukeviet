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
use NukeViet\Module\Devtool\Schema\SchemaService;
use NukeViet\Module\Devtool\Schema\SchemaValidator;
use NukeViet\Module\Devtool\Shared\ValidationException;

if ($nv_Request->isset_request('submit', 'post')) {
    // 1. Kiểm tra CSRF token
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $admin_info['admin_id'] . '_' . $module_name . '_schemas')) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $schemaRepo = new SchemaRepository($db, NV_ROOTDIR . '/data/devtool');
    $schemaService = new SchemaService($schemaRepo);
    $validator = new SchemaValidator($schemaRepo);

    // 2. Thu thập dữ liệu từ Request
    $rawData = $schemaService->collectRequestData($nv_Request, $module_name);

    try {
        // 3. Chuẩn hóa → Entity (trước khi validate)
        $entity = $schemaService->prepareEntity($rawData, $module_name);

        // 4. Validate (ném ValidationException nếu sai)
        $validator->validateSave($entity->table, $rawData['columns'], $rawData['page_settings']);

        // 5. Lưu schema + sinh mã action_mysql
        $sqlCode = $schemaService->saveSchema($entity, $db_config);

        // 6. Ghi log
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Save Schema', 'table: ' . $entity->table, $admin_info['userid']);

        nv_jsonOutput([
            'status' => 'OK',
            'mess' => sprintf($nv_Lang->getModule('save_success') ?: 'Đã lưu cấu hình Schema cho bảng %s thành công!', $entity->table),
            'sql_code' => $sqlCode,
        ]);
    } catch (ValidationException $e) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule($e->getMessage()) ?: $e->getMessage()
        ]);
    } catch (\Throwable $e) {
        trigger_error($e);
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_system')
        ]);
    }
}
