<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\Content\Shared;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * ValidationException — Exception gom nhiều lỗi validate.
 * Kế thừa InvalidArgumentException để tương thích với catch block hiện tại.
 * Message/Code của lỗi đầu tiên được dùng làm message/code chính (backward-compatible).
 */
class ValidationException extends \InvalidArgumentException
{
    /**
     * @var array<int, string> Danh sách lỗi: [error_code => lang_key]
     */
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
