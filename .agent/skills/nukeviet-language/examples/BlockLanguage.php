<?php

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
