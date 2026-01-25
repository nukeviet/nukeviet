<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_RSS')) {
    exit('Stop!!!');
}

/**
 * subreset()
 *
 * @param array $rssarray
 * @param int   $parentid
 * @return array
 */
function subreset($rssarray, $parentid)
{
    $subs = [];
    $keys = array_keys($rssarray);
    foreach ($keys as $k) {
        $rss = $rssarray[$k];
        if (isset($rss['parentid']) and isset($rss['catid'])) {
            if ((int) $rss['parentid'] == (int) $parentid) {
                unset($rssarray[$k]);
                $subs[] = [
                    'title' => $rss['title'],
                    'link' => $rss['link'],
                    'sub' => !empty($rss['catid']) ? subreset($rssarray, $rss['catid']) : []
                ];
            }
        } else {
            unset($rssarray[$k]);
            $subs[] = [
                'title' => $rss['title'],
                'link' => $rss['link'],
                'sub' => []
            ];
        }
    }

    return $subs;
}

/**
 * nv_rss_main_theme()
 *
 * @param string $rsscontents
 * @return string
 */
function nv_rss_main_theme($rsscontents)
{
    global $site_mods, $db, $nv_Cache, $global_config;

    $rss_list = [];
    foreach ($site_mods as $mod_name => $mod_info) {
        if ((int) $mod_info['rss'] == 1 and isset($mod_info['alias']['rss']) and module_file_exists($mod_info['module_file'] . '/funcs/rss.php')) {
            $mod_file = $mod_info['module_file'];
            $mod_data = $mod_info['module_data'];
            $rssarray = [];
            if (module_file_exists($mod_file . '/rssdata.php')) {
                include NV_ROOTDIR . '/modules/' . $mod_file . '/rssdata.php';
            }

            $rss_list[] = [
                'title' => $mod_info['custom_title'],
                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod_name . '&amp;' . NV_OP_VARIABLE . '=' . $mod_info['alias']['rss'],
                'sub' => !empty($rssarray) ? subreset($rssarray, 0) : []
            ];
        }
    }

    if (!empty($rss_list)) {
        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__) . '/smarty');
        $stpl->assign('RSS_LIST', $rss_list);

        $rsscontents .= $stpl->fetch('main.tpl');
    }

    return $rsscontents;
}
