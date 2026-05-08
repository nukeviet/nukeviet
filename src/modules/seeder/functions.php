<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

define('NV_IS_MOD_SEEDER', true);

// Module admin-only — không expose ra frontend
$nv_BotManager->setPrivate();

// Không có frontend functions — mọi thứ chạy qua admin/ hoặc CLI
header('Location: ' . NV_BASE_SITEURL);
exit(0);
