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
 * Kế thừa BaseTables để có magic getter __get() cho bảng chưa khai báo.
 * Các property dưới đây khai báo tường minh để IDE có autocomplete.
 *
 * Cách dùng:
 *   $tables = new Tables(NV_PREFIXLANG, $module_data);
 *   $tables->content → 'nv5_vi_content_content'  (property khai báo)
 *   $tables->cat     → 'nv5_vi_content_cat'       (property khai báo)
 *   $tables->tag     → 'nv5_vi_content_tag'       (magic getter từ BaseTables)
 *
 * Thêm bảng mới: chỉ cần thêm 1 public property và gán trong __construct().
 */
class Tables extends BaseTables
{
    /** Bảng bài viết: {prefix}_{lang}_{module_data}_content */
    public string $content;

    /** Bảng chủ đề: {prefix}_{lang}_{module_data}_cat */
    public string $cat;

    /**
     * @param string $tablePrefix Tiền tố + ngôn ngữ (VD: NV_PREFIXLANG = 'nv5_vi')
     * @param string $moduleData  Tên dữ liệu module (VD: 'content')
     */
    public function __construct(string $tablePrefix, string $moduleData)
    {
        parent::__construct($tablePrefix, $moduleData);

        $this->content = $this->prefix . '_content';
        $this->cat     = $this->prefix . '_cat';
    }
}
