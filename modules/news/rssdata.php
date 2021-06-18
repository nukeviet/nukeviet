<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (! defined('NV_IS_MOD_RSS')) {
    die('Stop!!!');
}

$rssarray = array();
$sql = "SELECT catid, parentid, title, alias FROM " . NV_PREFIXLANG . "_" . $mod_data . "_cat WHERE status=1 OR status=2 ORDER BY weight, sort";
//$rssarray[] = array( 'catid' => 0, 'parentid' => 0, 'title' => '', 'link' => '');

$list = $nv_Cache->db($sql, '', $mod_name);
foreach ($list as $value) {
    $value['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $mod_name . "&amp;" . NV_OP_VARIABLE . "=" . $mod_info['alias']['rss'] . "/" . $value['alias'];
    $rssarray[] = $value;
}