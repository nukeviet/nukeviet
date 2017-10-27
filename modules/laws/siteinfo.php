<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

if (!defined('NV_IS_FILE_SITEINFO')) die('Stop!!!');

$lang_siteinfo = nv_get_lang_module($mod);

// Tong so bai viet 
$number = $db->query("SELECT COUNT(*) as number FROM " . NV_PREFIXLANG . "_" . $mod_data . "_row")->fetchColumn();
if ($number > 0) {
    $siteinfo[] = array(
        'key' => $lang_siteinfo['siteinfo_numlaws'],
        'value' => $number
    );
}