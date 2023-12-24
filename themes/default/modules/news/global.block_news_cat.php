<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_block_news_cat')) {
    /**
     * nv_block_news_cat()
     *
     * @param array $block_config
     * @return string|void
     */
    function nv_block_news_cat($block_config)
    {
        global $nv_Cache, $module_array_cat, $site_mods, $module_config, $global_config, $db;

        $module = $block_config['module'];
        $show_no_image = $module_config[$module]['show_no_image'];
        $blockwidth = $module_config[$module]['blockwidth'];
        $order_articles_by = ($module_config[$module]['order_articles']) ? 'weight' : 'publtime';
        $numrow = $block_config['numrow'] ?? 20;

        if (empty($block_config['catid'])) {
            return '';
        }

        $catid = implode(',', $block_config['catid']);

        $db->sqlreset()
            ->select('id, catid, title, alias, homeimgfile, homeimgthumb, hometext, publtime, external_link')
            ->from(NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_rows')
            ->where('status= 1 AND catid IN(' . $catid . ')')
            ->order($order_articles_by . ' DESC')
            ->limit($numrow);
        $list = $nv_Cache->db($db->sql(), '', $module);

        if (empty($list)) {
            return '';
        }

        $array_block_news = [];
        foreach ($list as $l) {
            if ($l['homeimgthumb'] == 1) {
                $l['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $l['homeimgfile'];
                if (!empty($global_config['cdn_url'])) {
                    $l['thumb'] = $global_config['cdn_url'] . $l['thumb'];
                }
            } elseif ($l['homeimgthumb'] == 2) {
                $l['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $l['homeimgfile'];
                if (!empty($global_config['cdn_url'])) {
                    $l['thumb'] = $global_config['cdn_url'] . $l['thumb'];
                }
            } elseif ($l['homeimgthumb'] == 3) {
                $l['thumb'] = $l['homeimgfile'];
            } elseif (!empty($show_no_image)) {
                $l['thumb'] = NV_BASE_SITEURL . $show_no_image;
            } else {
                $l['thumb'] = '';
            }

            $array_block_news[] = [
                'id' => $l['id'],
                'title' => $l['title'],
                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $module_array_cat[$l['catid']]['alias'] . '/' . $l['alias'] . '-' . $l['id'] . $global_config['rewrite_exturl'],
                'imgurl' => $l['thumb'],
                'width' => $blockwidth,
                'hometext' => $l['hometext'],
                'external_link' => $l['external_link'],
                'newday' => $l['publtime'] + (86400 * $module_array_cat[$l['catid']]['newday'])
            ];
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
        if ($module == $module_name) {
            $module_array_cat = $global_array_cat;
            unset($module_array_cat[0]);
        } else {
            $module_array_cat = [];
            $sql = 'SELECT catid, parentid, title, alias, viewcat, subcatid, numlinks, description, keywords, groups_view, status FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_cat ORDER BY sort ASC';
            $list = $nv_Cache->db($sql, 'catid', $module);
            if (!empty($list)) {
                foreach ($list as $l) {
                    $module_array_cat[$l['catid']] = $l;
                    $module_array_cat[$l['catid']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
                }
            }
        }
        $content = nv_block_news_cat($block_config);
    }
}
