<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 14/4/2011, 19:29
 */

if (!defined('NV_IS_MOD_SITEMAP')) {
    die('Stop!!!');
}

$sublinks = [];

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $site_mods[$mName]['module_data'] . "_topic ORDER BY weight ASC";
$result = $db->query($sql);

while ($row = $result->fetch()) {
    $sublinks[] = [
        'title' => $row['title'],
        'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $mName . "/" . $row['alias'],
        'subs' => []
    ];
}
