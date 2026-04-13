<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\Content\Cat;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use NukeViet\Module\Content\Shared\ValidationException;

/**
 * CatValidator — Request Validator kiểm duyệt dữ liệu cho Chủ đề
 * Tách biệt việc kiểm tra tính đúng đắn của dữ liệu khỏi Service
 */
class CatValidator
{
    private CatRepository $repo;

    public function __construct(CatRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Validate dữ liệu chủ đề (title, alias...).
     * Kiểm tra toàn bộ trước khi ném — trả đủ lỗi trong 1 lần.
     * @param array $data Dữ liệu cần kiểm duyệt
     * @param int $excludeId Bỏ qua id khi check alias trùng lặp
     * @throws ValidationException Nếu có ít nhất 1 lỗi
     */
    public function validateSave(array $data, int $excludeId = 0): void
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors[1] = 'cat_empty_title';
        }

        if (!empty($data['alias']) && $this->repo->isAliasExists($data['alias'], $excludeId)) {
            $errors[2] = 'erroralias';
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
    }
}
