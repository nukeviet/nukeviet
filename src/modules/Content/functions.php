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

use NukeViet\Module\Content\Content\ContentRepository;

// Khởi tạo ContentRepository để lấy config
// Chỉ load repo tối thiểu nhất tại đây để tránh phình bộ nhớ
$repo = new ContentRepository($db, NV_PREFIXLANG . '_' . $module_data, $nv_Cache, $module_name);
$content_config = $repo->getConfig();

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA
    . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
