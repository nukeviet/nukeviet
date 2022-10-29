<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_RSS')) {
    exit('Stop!!!');
}

$rssarray = [];
$sql = 'SELECT catid, parentid, title, alias FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_cat WHERE status=1 OR status=2 ORDER BY weight, sort';
//$rssarray[] = array( 'catid' => 0, 'parentid' => 0, 'title' => '', 'link' => '');

$list = $nv_Cache->db($sql, '', $mod_name);
if (!empty($list)) {
    foreach ($list as $value) {
        $value['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod_name . '&amp;' . NV_OP_VARIABLE . '=' . $mod_info['alias']['rss'] . '/' . $value['alias'];
        $rssarray[] = $value;
    }
}
