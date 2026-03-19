<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

/* JSON i18n
{
    "i18n": {
        "vi": { "language": { "numrow": "Số dòng hiển thị" } },
        "en": { "language": { "numrow": "Number of rows" } }
    }
}
*/

function nv_block_config_tenblock($module, $data_block)
{
    global $nv_Lang;

    // $nv_Lang là một instance của NukeViet\Language\Language, cung cấp phương thức getModule(), getGlobal() để lấy chuỗi ngôn ngữ.
    $html = '<label>' . $nv_Lang->getModule('numrow') . '</label>';
    //...
}
