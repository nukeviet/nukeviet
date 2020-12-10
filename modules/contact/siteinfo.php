<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (! defined('NV_IS_FILE_SITEINFO')) {
    die('Stop!!!');
}

$lang_siteinfo = nv_get_lang_module($mod);

// So lien he chua doc
$number = $db->query("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $mod_data . "_send where is_read= 0")->fetchColumn();
if ($number > 0) {
    $pendinginfo[] = array(
        'key' => $lang_siteinfo['siteinfo_new'],
        'value' => number_format($number),
        'link' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $mod
    );
}
