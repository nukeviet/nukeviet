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
 * BaseValidator — Lớp cha cho mọi Validator của module Content.
 *
 * Cung cấp các helper method tái sử dụng để giảm boilerplate trong class con:
 * - addError()           : thêm lỗi vào $errors
 * - throwIfErrors()      : ném ValidationException nếu có lỗi
 * - requireNotEmpty()    : kiểm tra trường không rỗng
 * - requireUniqueAlias() : kiểm tra alias không trùng (dùng Repository)
 *
 * Quy ước: Key của mỗi lỗi là số thứ tự field trên form UI.
 * VD: 1 = title, 2 = bodytext, 3 = alias...
 *
 * Class con PHẢI gọi throwIfErrors() ở cuối validateSave() để ném ValidationException.
 */
abstract class BaseValidator
{
    /** @var array<int, string> [error_code => lang_key] */
    protected array $errors = [];

    /**
     * Thêm một lỗi vào danh sách lỗi.
     *
     * @param int    $code    Mã lỗi / thứ tự field trên form (VD: 1 = title)
     * @param string $langKey Key ngôn ngữ (VD: 'empty_title')
     */
    protected function addError(int $code, string $langKey): void
    {
        $this->errors[$code] = $langKey;
    }

    /**
     * Ném ValidationException nếu có ít nhất 1 lỗi trong $errors.
     * Tự động reset $errors sau khi ném để Validator có thể tái sử dụng.
     *
     * @throws ValidationException
     */
    protected function throwIfErrors(): void
    {
        if (!empty($this->errors)) {
            $errors       = $this->errors;
            $this->errors = [];
            throw new ValidationException($errors);
        }
    }

    /**
     * Kiểm tra giá trị không được rỗng. Nếu rỗng, thêm lỗi.
     *
     * @param mixed  $value   Giá trị cần kiểm tra
     * @param int    $code    Mã lỗi / thứ tự field
     * @param string $langKey Key ngôn ngữ khi lỗi
     */
    protected function requireNotEmpty(mixed $value, int $code, string $langKey): void
    {
        if (empty($value) && $value !== 0 && $value !== '0') {
            $this->addError($code, $langKey);
        }
    }

    /**
     * Kiểm tra alias không trùng. Nếu trùng, thêm lỗi.
     * Yêu cầu Repository implement isAliasExists() (AliasRepositoryTrait).
     *
     * @param BaseRepository $repo      Repository có hàm isAliasExists()
     * @param string         $alias     Alias cần kiểm tra
     * @param int            $code      Mã lỗi / thứ tự field
     * @param string         $langKey   Key ngôn ngữ khi lỗi
     * @param int            $excludeId Bỏ qua ID đang edit (0 = không bỏ qua)
     */
    protected function requireUniqueAlias(BaseRepository $repo, string $alias, int $code, string $langKey, int $excludeId = 0): void
    {
        if (!empty($alias) && method_exists($repo, 'isAliasExists') && $repo->isAliasExists($alias, $excludeId)) {
            $this->addError($code, $langKey);
        }
    }
}
