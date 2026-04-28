<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_message_page')) {
    /**
     * nv_message_page()
     *
     * @param array $block_config
     * @return string
     */
    function nv_message_page(array $block_config): string
    {
        global $nv_Cache, $global_config, $site_mods, $db, $module_name;
        $module = $block_config['module'];

        if (!isset($site_mods[$module]) or $module_name == $module) {
            return '';
        }

        $is_show = false;

        $pattern = '/^' . NV_LANG_DATA . '\_([a-zA-z0-9\_\-]+)\_([0-9]+)\_' . NV_CACHE_PREFIX . '\.cache$/i';

        $cache_files = nv_scandir(NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $module, $pattern);

        if (($count = count($cache_files)) >= 1) {
            $num = random_int(1, $count);
            --$num;
            $cache_file = $cache_files[$num];

            if (($cache = $nv_Cache->getItem($module, $cache_file)) != false) {
                $cache = unserialize($cache, NV_UNSERIALIZE_SAFE);
                $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $cache['alias'] . $global_config['rewrite_exturl'];
                $title = $cache['page_title'];
                $bodytext = strip_tags($cache['bodytext']);

                $is_show = true;
            }
        }
        if (!$is_show) {
            $sql = 'SELECT id,title,alias,bodytext,keywords,add_time,edit_time FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . ' WHERE status=1 ORDER BY rand() DESC';

            if (($query = $db->query($sql)) !== false) {
                if (($row = $query->fetch()) !== false) {
                    $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'];
                    $title = $row['title'];
                    $bodytext = strip_tags($row['bodytext']);
                    $bodytext = nv_clean60($bodytext, 300);

                    $is_show = true;
                }
            }
        }

        if (!$is_show) {
            return '';
        }

        [$block_theme, $dir] = get_block_tpl_dir('block.about.tpl', true, $module);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('LINK', $link);
        $tpl->assign('TITLE', $title);
        $tpl->assign('BODYTEXT', $bodytext);

        return $tpl->fetch('block.about.tpl');
    }
}

$content = nv_message_page($block_config);
