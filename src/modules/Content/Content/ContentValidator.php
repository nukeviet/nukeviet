<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\Content\Content;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use NukeViet\Module\Content\Shared\BaseValidator;

/**
 * ContentValidator — Kiểm duyệt dữ liệu cho Bài viết.
 * Kế thừa BaseValidator để tái sử dụng requireNotEmpty, requireUniqueAlias, throwIfErrors.
 */
class ContentValidator extends BaseValidator
{
    private ContentRepository $repo;

    public function __construct(ContentRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Validate dữ liệu bài viết.
     * Kiểm tra toàn bộ trước khi ném — trả đủ lỗi trong 1 lần.
     *
     * @param array $data      Dữ liệu cần kiểm duyệt (đã qua prepareSaveData)
     * @param int   $excludeId Bỏ qua ID khi check alias trùng (0 = thêm mới)
     * @throws \NukeViet\Module\Content\Shared\ValidationException nếu có lỗi
     */
    public function validateSave(array $data, int $excludeId = 0): void
    {
        // Field 1: title không được rỗng
        $this->requireNotEmpty($data['title'] ?? '', 1, 'empty_title');

        // Field 2: bodytext không được rỗng
        if (trim($data['bodytext'] ?? '') === '') {
            $this->addError(2, 'empty_bodytext');
        }

        // Field 3: alias không được trùng
        $this->requireUniqueAlias($this->repo, $data['alias'] ?? '', 3, 'erroralias', $excludeId);

        $this->throwIfErrors();
    }
}
