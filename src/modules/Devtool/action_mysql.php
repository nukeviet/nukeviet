<?php

/**
 * @Project NUKEVIET 5.0
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2024 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$sql_drop_module = [];

$sql_create_module = [];
// Module này không sử dụng bảng CSDL riêng, chỉ làm Virtual Module
