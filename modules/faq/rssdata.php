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
//$rssarray[] = array( 'catid' => 0, 'parentid' => 0, 'title' => '', 'link' =>  '');


$sql = "SELECT id catid, parentid, title, alias FROM " . NV_PREFIXLANG . "_" . $mod_name . "_categories ORDER BY weight ASC";
$list = $nv_Cache->db($sql, '', $mod_name);
foreach ($list as $value) {
    $value['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $mod_name . "&amp;" . NV_OP_VARIABLE . "=rss/" . $value['alias'];
    $rssarray[] = $value;
}
