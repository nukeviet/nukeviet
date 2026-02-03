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

if (!nv_function_exists('site_terms')) {
    /**
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function site_terms_config(string $module, array $data_block): string
    {
        global $nv_Cache, $global_config, $site_mods, $db, $nv_Lang;

        $db->sqlreset()->select('id, title, alias, description')->from(NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'])->where('status = 1')->order('weight ASC');
        $list = $nv_Cache->db($db->sql(), 'id', $module);

        foreach ($list as $key => $item) {
            $item['title'] = nv_clean60($item['title'], 60);
            $item['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $item['alias'] . $global_config['rewrite_exturl'];
            $list[$key] = $item;
        }
        unset($item);

        $term_names = !empty($data_block['term_names']) ? array_map('trim', explode('|', $data_block['term_names'])) : [''];
        $term_queries = !empty($data_block['term_queries']) ? array_map('trim', explode('|', $data_block['term_queries'])) : [''];

        $term_data = [];
        foreach ($term_names as $key => $term_name) {
            $term_data[] = [
                'name' => $term_name,
                'query' => isset($term_queries[$key]) ? $term_queries[$key] : ''
            ];
        }

        [$block_theme, $dir] = get_block_tpl_dir('global.siteterms.config.tpl', $module, true);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('LIST', $list);
        $tpl->assign('TERM_DATA', $term_data);

        return $tpl->fetch('global.siteterms.config.tpl');
    }

    function site_terms_submit($module)
    {
        global $nv_Request;

        $return = [];
        $return['error'] = [];
        $return['config']['term_names'] = $nv_Request->get_typed_array('term_names', 'post', 'title', []);
        $return['config']['term_queries'] = $nv_Request->get_typed_array('term_queries', 'post', 'title', []);
        $return['config']['term_names'] = !empty($return['config']['term_names']) ? implode('|', $return['config']['term_names']) : '';
        $return['config']['term_queries'] = !empty($return['config']['term_queries']) ? implode('|', $return['config']['term_queries']) : '';

        return $return;
    }
    /**
     * @param array $block_config
     * @return string
     */
    function site_terms($block_config): string
    {
        $term_names = !empty($block_config['term_names']) ? array_map('trim', explode('|', $block_config['term_names'])) : [''];
        $term_queries = !empty($block_config['term_queries']) ? array_map('trim', explode('|', $block_config['term_queries'])) : [''];

        if (empty($term_names)) {
            return '';
        }

        $row = [];
        foreach ($term_names as $key => $name) {
            $row[] = [
                'title' => $name,
                'url' => isset($term_queries[$key]) ? $term_queries[$key] : ''
            ];
        }

        [$block_theme, $dir] = get_block_tpl_dir('global.siteterms.tpl', $block_config['module'], true);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir(template_dir: $dir);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('ROW', $row);

        return $tpl->fetch('global.siteterms.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = site_terms($block_config);
}
