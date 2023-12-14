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

if (!nv_function_exists('nv_block_freecontent')) {
    /**
     * nv_block_freecontent()
     *
     * @param array $block_config
     * @return string
     * @throws PDOException
     */
    function nv_block_freecontent($block_config)
    {
        global $site_mods, $module_config, $nv_Cache, $db;

        $module = $block_config['module'];

        // Set content status
        if (!empty($module_config[$module]['next_execute']) and $module_config[$module]['next_execute'] <= NV_CURRENTTIME) {
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_rows SET status = 2 WHERE end_time > 0 AND end_time < ' . NV_CURRENTTIME;
            $db->query($sql);

            // Get next execute
            $sql = 'SELECT MIN(end_time) next_execute FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_rows WHERE end_time > 0 AND status = 1';
            $result = $db->query($sql);
            $next_execute = (int) ($result->fetchColumn());
            $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = 'next_execute'");
            $sth->bindParam(':module_name', $module, PDO::PARAM_STR);
            $sth->bindParam(':config_value', $next_execute, PDO::PARAM_STR);
            $sth->execute();

            $nv_Cache->delMod('settings');
            $nv_Cache->delMod($module);

            unset($next_execute);
        }

        if (!isset($site_mods[$module]) or empty($block_config['blockid'])) {
            return '';
        }

        $sql = 'SELECT id, title, description, image, link, target FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_rows WHERE status = 1 AND bid = ' . $block_config['blockid'];
        $list = $nv_Cache->db($sql, 'id', $module);
        if (empty($list)) {
            return '';
        }

        shuffle($list);
        if ($block_config['numrows'] <= sizeof($list)) {
            $list = array_slice($list, 0, $block_config['numrows']);
        }

        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
        $stpl->assign('MODULE_UPLOAD', $site_mods[$module]['module_upload']);
        $stpl->assign('LIST', $list);

        return $stpl->fetch('block.free_content.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_freecontent($block_config);
}
