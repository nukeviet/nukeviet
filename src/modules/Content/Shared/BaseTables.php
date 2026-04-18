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
 * BaseTables — Lớp cha cho Value Object chứa tên các bảng DB.
 *
 * Cung cấp magic getter __get() để trả về tên bảng theo convention:
 *   {tablePrefix}_{moduleData}_{name}
 *
 * Class con (Tables) khai báo các property cụ thể để IDE có autocomplete.
 * Khi cần bảng mới trong tương lai, chỉ cần thêm public property vào Tables
 * hoặc dùng thẳng magic getter nếu chỉ dùng tạm thời.
 *
 * Cách dùng:
 *   $tables = new Tables(NV_PREFIXLANG, $module_data);
 *   $tables->content → 'nv5_vi_content_content'  (property khai báo)
 *   $tables->tag     → 'nv5_vi_content_tag'       (magic getter)
 */
abstract class BaseTables
{
    protected string $prefix;

    /**
     * @param string $tablePrefix Tiền tố + ngôn ngữ (VD: NV_PREFIXLANG = 'nv5_vi')
     * @param string $moduleData  Tên dữ liệu module (VD: 'content')
     */
    public function __construct(string $tablePrefix, string $moduleData)
    {
        $this->prefix = $tablePrefix . '_' . $moduleData;
    }

    /**
     * Magic getter — tự tạo tên bảng theo convention nếu property không khai báo.
     * VD: $tables->tag → 'nv5_vi_content_tag'
     */
    public function __get(string $name): string
    {
        return $this->prefix . '_' . $name;
    }
}
