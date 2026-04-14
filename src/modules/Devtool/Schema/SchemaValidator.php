<?php

/**
 * @Project NUKEVIET 5.0
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2024 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 */

declare(strict_types=1);

namespace NukeViet\Module\Devtool\Schema;

use NukeViet\Module\Devtool\Shared\ValidationException;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * SchemaValidator — Kiểm tra tính hợp lệ của dữ liệu trước khi lưu Schema.
 * Ném ValidationException chứa toàn bộ lỗi để Controller xử lý.
 *
 * Error codes:
 *   1 = table không hợp lệ / không tồn tại
 *   2 = columns rỗng
 */
class SchemaValidator
{
    public function __construct(private SchemaRepository $repo) {}

    /**
     * Validate toàn bộ trước khi ném — trả đủ lỗi trong 1 lần.
     *
     * @throws ValidationException nếu có ít nhất 1 lỗi
     */
    public function validateSave(string $table, array $columns): void
    {
        $errors = [];

        if (empty($table) || !$this->repo->tableExists($table)) {
            $errors[1] = 'error_invalid_table';
        }

        if (empty($columns)) {
            $errors[2] = 'error_empty_columns';
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
    }
}
