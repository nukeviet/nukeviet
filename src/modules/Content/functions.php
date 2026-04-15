<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

define('NV_IS_MOD_CONTENT', true);

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

// Lấy cấu hình module từ biến hệ thống
$config = $module_config[$module_name];

// Khởi tạo danh sách bảng DB cho module — dùng chung cho mọi Repository
use NukeViet\Module\Content\Shared\Tables;
$tables = new Tables(NV_PREFIXLANG, $module_data);
