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

/**
 * Hiển thị một mục trong cây RSS: biểu tượng RSS liên kết đến feed và tiêu đề liên kết đến trang nội dung
 *
 * @param string $title   Tiêu đề
 * @param string $url     Liên kết đến trang nội dung (module/chuyên mục), rỗng thì tiêu đề liên kết đến feed
 * @param string $rss_url Liên kết đến RSS feed
 * @param bool   $strong  In đậm tiêu đề (dùng cho cấp module)
 * @return string
 */
function nv_rss_item_html($title, $url, $rss_url, $strong = false)
{
    $title_text = $strong ? '<strong>' . $title . '</strong>' : $title;
    if (!empty($url)) {
        $title_html = '<a title="' . $title . '" href="' . $url . '">' . $title_text . '</a>';
    } else {
        $title_html = '<a rel="nofollow" title="' . $title . '" href="' . $rss_url . '">' . $title_text . '</a>';
    }

    return '<span><a rel="nofollow" title="RSS: ' . $title . '" href="' . $rss_url . '"><i class="fa fa-rss text-warning"></i></a> ' . $title_html . '</span>';
}

/**
 * nv_get_rss_link()
 *
 * @return string
 */
function nv_get_rss_link()
{
    global $db, $nv_Cache, $module_data, $global_config, $site_mods;
    $contentrss = '';

    foreach ($site_mods as $mod_name => $mod_info) {
        if ($mod_info['rss'] == 1 and isset($mod_info['alias']['rss']) and file_exists(NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/funcs/rss.php')) {
            $mod_data = $mod_info['module_data'];
            $mod_file = $mod_info['module_file'];

            $mod_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod_name;
            $mod_rss_url = $mod_url . '&amp;' . NV_OP_VARIABLE . '=' . $mod_info['alias']['rss'];

            $contentrss .= '<li>' . nv_rss_item_html($mod_info['custom_title'], $mod_url, $mod_rss_url, true);
            if (file_exists(NV_ROOTDIR . '/modules/' . $mod_file . '/rssdata.php')) {
                $rssarray = [];
                include NV_ROOTDIR . '/modules/' . $mod_file . '/rssdata.php';

                $contentrss .= '<ul>';
                foreach ($rssarray as $key => $value) {
                    $parentid = (isset($value['parentid'])) ? $value['parentid'] : 0;
                    if ($parentid == 0) {
                        $contentrss .= '<li>' . nv_rss_item_html($value['title'], $value['url'] ?? '', $value['link']);
                        $catid = (isset($value['catid'])) ? $value['catid'] : 0;
                        if ($catid > 0) {
                            $contentrss .= nv_get_sub_rss_link($rssarray, $catid);
                        }
                        $contentrss .= '</li>';
                    }
                }
                $contentrss .= '</ul>';
            }
            $contentrss .= '</li>';
        }
    }

    return $contentrss;
}

/**
 * nv_get_sub_rss_link()
 *
 * @param array $rssarray
 * @param int   $id
 * @return string
 */
function nv_get_sub_rss_link($rssarray, $id)
{
    $content = '';
    $content .= '<ul>';
    foreach ($rssarray as $value) {
        if (isset($value['parentid']) and $value['parentid'] == $id) {
            $content .= '<li>' . nv_rss_item_html($value['title'], $value['url'] ?? '', $value['link']);
            $catid = (isset($value['catid'])) ? $value['catid'] : 0;
            if ($catid > 0) {
                $content .= nv_get_sub_rss_link($rssarray, $catid);
            }
            $content .= '</li>';
        }
    }
    $content .= '</ul>';

    return $content;
}

$page_title = $module_info['site_title'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$canonicalUrl = getCanonicalUrl($page_url);

$array = '';
$content_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . 'Content.txt';
if (file_exists($content_file)) {
    $array = file_get_contents($content_file);
}

$contents = nv_rss_main_theme($array);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
