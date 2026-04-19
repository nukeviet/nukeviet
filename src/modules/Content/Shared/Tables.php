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
 * Tables — Value Object chứa tên các bảng DB của module Content.
 *
 * Tên bảng được ghép tự động từ $tablePrefix + $moduleData + suffix hard-code.
 * Đây là nơi DUY NHẤT khai báo suffix của từng bảng — thêm bảng mới chỉ cần thêm 1 property.
 *
 * Cách dùng:
 *   $tables = new Tables(NV_PREFIXLANG, $module_data);
 *   // → $tables->content = 'nv5_vi_content'
 *   // → $tables->cat     = 'nv5_vi_content_cat'
 */
readonly class Tables
{
    public string $content;
    public string $cat;

    /**
     * @param string $tablePrefix Tiền tố + ngôn ngữ (VD: NV_PREFIXLANG = 'nv5_vi')
     * @param string $moduleData  Tên dữ liệu module (VD: 'content')
     */
    public function __construct(string $tablePrefix, string $moduleData)
    {
        $prefix = $tablePrefix . '_' . $moduleData;

        $this->content = $prefix . '_content';  // nv5_vi_content_content
        $this->cat     = $prefix . '_cat';      // nv5_vi_content_cat
    }
}
