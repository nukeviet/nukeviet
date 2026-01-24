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

if (!nv_function_exists('nv_page_list')) {
    /**
     * nv_block_config_page_list()
     *
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_config_page_list($module, $data_block)
    {
        global $nv_Lang;

        $html = '';
        $html .= '<div class="row mb-3">';
        $html .= '	<label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('title_length') . ':</label>';
        $html .= '	<div class="col-sm-5"><input type="text" class="form-control" name="config_title_length" value="' . $data_block['title_length'] . '"/></div>';
        $html .= '</div>';
        $html .= '<div class="row mb-3">';
        $html .= '	<label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('numrow') . ':</label>';
        $html .= '	<div class="col-sm-5"><input type="text" name="config_numrow" class="form-control" value="' . $data_block['numrow'] . '"/></div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_block_config_page_list_submit()
     *
     * @param string $module
     * @return array
     */
    function nv_block_config_page_list_submit($module)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 24);
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 5);

        return $return;
    }

    /**
     * Danh sách bài viết
     *
     * @param array $block_config
     * @return string
     */
    function nv_page_list(array $block_config): string
    {
        global $nv_Cache, $global_config, $site_mods, $db;
        $module = $block_config['module'];

        if (!isset($site_mods[$module])) {
            return '';
        }

        $db->sqlreset()
        ->select('id, title, alias, description')
        ->from(NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'])
        ->where('status = 1')
        ->order('weight ASC')
        ->limit($block_config['numrow']);

        $list = $nv_Cache->db($db->sql(), 'id', $module);

        if (empty($list)) return '';

        foreach ($list as &$l) {
            $l['title_clean60'] = nv_clean60($l['title'], $block_config['title_length']);
            $l['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'] . $global_config['rewrite_exturl'];
        }
        unset($l);

        [$block_theme, $dir] = get_block_tpl_dir('block.page_list.tpl', $module, true);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('DATA', $list);

        return $tpl->fetch('block.page_list.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_page_list($block_config);
}
