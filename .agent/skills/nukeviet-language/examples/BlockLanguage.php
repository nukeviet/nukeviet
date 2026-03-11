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
        "vi": { "config": { "numrow": "Số dòng hiển thị" } },
        "en": { "config": { "numrow": "Number of rows" } }
    }
}
*/

function nv_block_config_tenblock($module, $data_block, $lang_block)
{
    // $lang_block chứa chuỗi UI của form config block (từ JSON i18n)
    $html = '<label>' . $lang_block['numrow'] . '</label>';
    //...
}
