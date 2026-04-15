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

use NukeViet\Module\Content\Cat\CatRepository;
use NukeViet\Module\Content\Cat\CatService;

if (!nv_function_exists('nv_block_config_content_list')) {
    /**
     * nv_block_config_content_list()
     *
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_config_content_list($module, $data_block)
    {
        global $db, $nv_Cache, $nv_Lang, $site_mods;

        $tables = new \NukeViet\Module\Content\Shared\Tables(NV_PREFIXLANG, $site_mods[$module]['module_data']);
        $catRepo = new CatRepository($db, $tables, $nv_Cache, $module);
        $catService = new CatService($catRepo);

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir(NV_ROOTDIR . '/themes/admin_future/modules/' . $module);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('DATA', $data_block);
        $tpl->assign('CATS', $catService->getSelectList(true));

        return $tpl->fetch('global.content_list.config.tpl');
    }

    /**
     * nv_block_config_content_list_submit()
     *
     * @param string $module
     * @return array
     */
    function nv_block_config_content_list_submit($module)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 24);
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 5);
        $return['config']['catid'] = $nv_Request->get_int('config_catid', 'post', 0);

        return $return;
    }

    /**
     * nv_content_list()
     *
     * @param array $block_config
     * @return string
     */
    function nv_content_list($block_config)
    {
        global $nv_Cache, $global_config, $site_mods, $db;
        $module = $block_config['module'];

        if (!isset($site_mods[$module])) {
            return '';
        }

        $numrow = (int) $block_config['numrow'];
        $catid = (int) $block_config['catid'];
        $title_length = (int) $block_config['title_length'];

        $sql = 'SELECT id, title, alias FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . " WHERE status = 1";
        if ($catid > 0) {
            $sql .= ' AND catid = ' . $catid;
        }
        $sql .= " ORDER BY weight ASC LIMIT " . $numrow;
        $list = $nv_Cache->db($sql, 'id', $module);

        if (!empty($list)) {
            $array_data = [];
            foreach ($list as $l) {
                $l['title_clean60'] = nv_clean60($l['title'], $title_length);
                $l['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'] . $global_config['rewrite_exturl'];
                $array_data[] = $l;
            }

            $tpl = new \NukeViet\Template\NVSmarty();
            $tpl->setTemplateDir(get_module_tpl_dir('global.content_list.tpl'));
            $tpl->assign('DATA', $array_data);

            return $tpl->fetch('global.content_list.tpl');
        }

        return '';
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_content_list($block_config);
}
