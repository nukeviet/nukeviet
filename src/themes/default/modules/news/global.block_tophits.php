<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_news_block_tophits')) {
    /**
     * nv_news_block_tophits()
     *
     * @param array  $block_config
     * @param string $mod_data
     * @return string
     */
    function nv_news_block_tophits($block_config, $mod_data)
    {
        global $module_array_cat, $site_mods, $db_slave, $module_config, $global_config, $nv_Cache;

        $module = $block_config['module'];
        $blockwidth = $module_config[$module]['blockwidth'];
        $show_no_image = $module_config[$module]['show_no_image'];
        $publtime = NV_CURRENTTIME - $block_config['number_day'] * 86400;
        $numrow = $block_config['numrow'] ?? 20;

        $cache_file = preg_replace('/[^a-z0-9\_\-]+/', '_', $block_config['block_name']) . '_' . $numrow . '_' . NV_CACHE_PREFIX . '.cache';
        if (($cache = $nv_Cache->getItem($module, $cache_file)) != false) {
            $array_block_news = json_decode($cache, true);
        } else {
            $array_block_news = [];
            $db_slave->sqlreset()
                ->select('id, catid, publtime, title, alias, homeimgthumb, homeimgfile, hometext, external_link')
                ->from(NV_PREFIXLANG . '_' . $mod_data . '_rows')
                ->order('hitstotal DESC')
                ->limit($numrow);
            if (empty($block_config['nocatid'])) {
                $db_slave->where('status= 1 AND publtime > ' . $publtime);
            } else {
                $db_slave->where('status= 1 AND publtime > ' . $publtime . ' AND catid NOT IN (' . implode(',', $block_config['nocatid']) . ')');
            }

            $result = $db_slave->query($db_slave->sql());
            while ([$id, $catid, $publtime, $title, $alias, $homeimgthumb, $homeimgfile, $hometext, $external_link] = $result->fetch(3)) {
                if ($homeimgthumb == 1) {
                    // image thumb
                    $imgurl = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $homeimgfile;
                    if (!empty($global_config['cdn_url'])) {
                        $imgurl = $global_config['cdn_url'] . $imgurl;
                    }
                } elseif ($homeimgthumb == 2) {
                    // image file
                    $imgurl = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $homeimgfile;
                    if (!empty($global_config['cdn_url'])) {
                        $imgurl = $global_config['cdn_url'] . $imgurl;
                    }
                } elseif ($homeimgthumb == 3) {
                    // image url
                    $imgurl = $homeimgfile;
                } elseif (!empty($show_no_image)) {
                    // no image
                    $imgurl = NV_BASE_SITEURL . $show_no_image;
                } else {
                    $imgurl = '';
                }

                $array_block_news[] = [
                    'id' => $id,
                    'title' => $title,
                    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $module_array_cat[$catid]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
                    'imgurl' => $imgurl,
                    'width' => $blockwidth,
                    'hometext' => $hometext,
                    'external_link' => $external_link,
                    'newday' => $publtime + (86400 * $module_array_cat[$catid]['newday'])
                ];
            }

            $cache = json_encode($array_block_news);
            $nv_Cache->setItem($module, $cache_file, $cache);
        }

        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
        $stpl->assign('CONFIGS', $block_config);
        $stpl->assign('ITEMS', $array_block_news);

        return $stpl->fetch('block_articlelist.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    global $nv_Cache, $site_mods, $module_name, $global_array_cat, $module_array_cat;
    $module = $block_config['module'];
    if (isset($site_mods[$module])) {
        $mod_data = $site_mods[$module]['module_data'];
        if ($module == $module_name) {
            $module_array_cat = $global_array_cat;
            unset($module_array_cat[0]);
        } else {
            $module_array_cat = [];
            $sql = 'SELECT catid, parentid, title, alias, viewcat, subcatid, numlinks, description, keywords, groups_view, status, newday FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_cat ORDER BY sort ASC';
            $list = $nv_Cache->db($sql, 'catid', $module);
            if (!empty($list)) {
                foreach ($list as $l) {
                    $module_array_cat[$l['catid']] = $l;
                    $module_array_cat[$l['catid']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
                }
            }
        }
        $content = nv_news_block_tophits($block_config, $mod_data);
    }
}
