<?php

/**
 * @Project NUKEVIET 3.3
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES. All rights reserved
 * @Createdate Dec 22, 2011 10:22:41 AM
 */

if (!defined('NV_IS_MOD_TAGS')) die('Stop!!!');

$q = filter_text_input('q', 'get', '', 0);
if( ! $q )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA, true ) );
	exit();
}
$q = str_replace('-', ' ', $q );
$page_title = $q . ' ' . NV_TITLEBAR_DEFIS . ' ' . $module_info['custom_title'];

if (preg_match("/(.*)\/page\-([0-9]+)$/", $q, $m))
{
    $q = $m[1];
    $page = $m[2];
}
else
{
    $page = 1;
}

list($tid) = $db->sql_fetchrow($db->sql_query("SELECT `tid` FROM `" . NV_PREFIXLANG . "_tags` WHERE `keys`=" . $db->dbescape($q)));

if ($tid)
{
    $per_page = 20;
    $base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;q=" . urlencode($q);
    $tableid = ceil($tid / 1000);
    $sql = "SELECT SQL_CALC_FOUND_ROWS t1.module, t1.link, t1.title, t1.text, t1.image FROM `" . NV_PREFIXLANG . "_tags_con_" . $tableid . "`  AS t1 INNER JOIN `" . NV_PREFIXLANG . "_tags_kid_" . $tableid . "` AS t2 ON (t1.sid = t2.sid AND t1.module = t2.module) WHERE t2.tid=" . $tid . " ORDER BY t1.publtime DESC LIMIT  " . ($page - 1) * $per_page . "," . $per_page;
    $result = $db->sql_query($sql);
    $result_all = $db->sql_query("SELECT FOUND_ROWS()");
    list($all_page) = $db->sql_fetchrow($result_all);

    $array_item = array();
    while ($item = $db->sql_fetch_assoc($result))
    {
        $item['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $item['module'] . "&amp;" . NV_OP_VARIABLE . "=" . $item['link'];
        if (is_file(NV_ROOTDIR . '/' . $item['image']))
        {
            $item['image'] = NV_BASE_SITEURL . $item['image'];
        }
        $array_item[] = $item;
    }

    $htmlpage = nv_alias_page($page_title, $base_url, $all_page, $per_page, $page);
    $contents = nv_tags_view($array_item, $htmlpage);
}

$key_words = $module_info['keywords'];
$mod_title = isset($lang_module['main_title']) ? $lang_module['main_title'] : $module_info['custom_title'];

include (NV_ROOTDIR . "/includes/header.php");
echo nv_site_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");

?>