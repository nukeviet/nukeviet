<?php

/**
 * @Project NUKEVIET 5.0
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2024 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 */

declare(strict_types=1);

namespace NukeViet\Module\Devtool\Shared;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * ValidationException — Gom nhiều lỗi validate thành một Exception duy nhất.
 * Kế thừa InvalidArgumentException để tương thích catch block chuẩn NukeViet 5.
 * Message/Code của lỗi đầu tiên được dùng làm message/code chính.
 */
class ValidationException extends \InvalidArgumentException
{
    /** @var array<int, string> Danh sách lỗi: [error_code => lang_key] */
    private array $errors;

    /**
     * @param array<int, string> $errors [error_code => lang_key] — ít nhất 1 phần tử
     */
    public function __construct(array $errors)
    {
        $firstCode = (int) array_key_first($errors);
        parent::__construct($errors[$firstCode], $firstCode);
        $this->errors = $errors;
    }

    /**
     * Trả toàn bộ lỗi: [error_code => lang_key]
     * @return array<int, string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
