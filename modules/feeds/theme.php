<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_RSS')) {
    exit('Stop!!!');
}

function nv_get_rss_link($rss_contents, $type, $id = 0)
{
    global $db, $nv_Cache, $module_data, $global_config;

    $contents = '';
    if ($type == 'mod') {
        foreach ($rss_contents as $mod_name => $mod_info) {
            $contents .= '<li>';
            $contents .= '<div class="item"><span>' . $mod_info['custom_title'] . '</span><span class="text-nowrap"><a class="rss" rel="nofollow" title="RSS" href="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod_name . '&amp;' . NV_OP_VARIABLE . '=' . $mod_info['alias']['rss'] . '">&nbsp;</a><a class="atom" rel="nofollow" title="ATOM" href="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod_name . '&amp;' . NV_OP_VARIABLE . '=' . $mod_info['alias']['rss'] . '&amp;type=atom">&nbsp;</a></span></div>';
            

            $mod_file = $mod_info['module_file'];
            $mod_data = $mod_info['module_data'];
            if (file_exists(NV_ROOTDIR . '/modules/' . $mod_file . '/rssdata.php')) {
                $rssarray = [];
                include NV_ROOTDIR . '/modules/' . $mod_file . '/rssdata.php';
                if (!empty($rssarray)) {
                    $contents .= nv_get_rss_link($rssarray, 'sub', 0);
                }
            }

            $contents .= '</li>';
        }
    } else {
        foreach ($rss_contents as $value) {
            $parentid = isset($value['parentid']) ? $value['parentid'] : 0;
            if ($parentid == $id) {
                $contents .= '<li>';
                $contents .= '<div class="item"><span>' . $value['title'] . '</span><span class="text-nowrap"><a class="rss" rel="nofollow" title="RSS" href="' . $value['link'] . '">&nbsp;</a><a class="atom" rel="nofollow" title="ATOM" href="' . $value['link'] . '&amp;type=atom">&nbsp;</a></span></div>';

                $catid = isset($value['catid']) ? $value['catid'] : 0;
                if ($catid > 0) {
                    $contents .= nv_get_rss_link($rss_contents, 'sub', $catid);
                }

                $contents .= '</li>';
            }
        }
    }

    return '<ul>' . $contents . '</ul>';
}

/**
 * nv_rss_main_theme()
 *
 * @param string $rsscontents
 * @return string
 */
function nv_rss_main_theme($rsscontents)
{
    global $site_mods;

    $rss_array = [];
    foreach ($site_mods as $mod_name => $mod_info) {
        if ($mod_info['rss'] == 1 and isset($mod_info['alias']['rss']) and file_exists(NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/funcs/rss.php')) {
            $rss_array[$mod_name] = $mod_info;
        }
    }

    if (!empty($rss_array)) {
        $rsscontents .= '<div class="tree">' . nv_get_rss_link($rss_array, 'mod') . '</div>';
    }

    return $rsscontents;
}
