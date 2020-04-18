<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 18:6
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_block_freecontent')) {
    /**
     * nv_block_config_freecontent()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_freecontent($module, $data_block, $lang_block)
    {
        global $site_mods, $nv_Cache;

        $html = '';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['blockid'] . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '		<select name="config_blockid" class="form-control">';

        $sql = 'SELECT bid, title FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_blocks ORDER BY title ASC';
        $list = $nv_Cache->db($sql, '', $module);

        foreach ($list as $row) {
            $html .= '	<option value="' . $row['bid'] . '"' . ($row['bid'] == $data_block['blockid'] ? ' selected="selected"' : '') . '>' . $row['title'] . '</option>';
        }

        $html .= '		</select>';
        $html .= '	</div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['numrows'] . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '		<select name="config_numrows" class="form-control">';

        for ($i = 1; $i <= 10; $i++) {
            $html .= '	<option value="' . $i . '"' . ($i == $data_block['numrows'] ? ' selected="selected"' : '') . '>' . $i . '</option>';
        }

        $html .= '		</select>';
        $html .= '	</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_block_config_freecontent_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_freecontent_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['blockid'] = $nv_Request->get_int('config_blockid', 'post', 0);
        $return['config']['numrows'] = $nv_Request->get_int('config_numrows', 'post', 2);
        return $return;
    }

    /**
     * nv_block_freecontent()
     *
     * @return
     */
    function nv_block_freecontent($block_config)
    {
        global $global_config, $site_mods, $module_config, $nv_Cache, $db;

        $module = $block_config['module'];

        // Set content status
        if (!empty($module_config[$module]['next_execute']) and $module_config[$module]['next_execute'] <= NV_CURRENTTIME) {
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_rows SET status = 2 WHERE end_time > 0 AND end_time < ' . NV_CURRENTTIME;
            $db->query($sql);

            // Get next execute
            $sql = 'SELECT MIN(end_time) next_execute FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_rows WHERE end_time > 0 AND status = 1';
            $result = $db->query($sql);
            $next_execute = intval($result->fetchColumn());
            $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = 'next_execute'");
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

        if (!empty($list)) {
            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $site_mods[$module]['module_file'] . '/block.free_content.tpl')) {
                $block_theme = $global_config['module_theme'];
            } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $site_mods[$module]['module_file'] . '/block.free_content.tpl')) {
                $block_theme = $global_config['site_theme'];
            } else {
                $block_theme = 'default';
            }

            $xtpl = new XTemplate('block.free_content.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $site_mods[$module]['module_file']);

            shuffle($list);
            if ($block_config['numrows'] <= sizeof($list)) {
                $list = array_slice($list, 0, $block_config['numrows']);
            }

            foreach ($list as $row) {
                if (!empty($row['image'])) {
                    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['image'];
                }

                $xtpl->assign('ROW', $row);

                if (!empty($row['link'])) {
                    if (!empty($row['target'])) {
                        $xtpl->parse('main.loop.title_link.target');
                    }

                    $xtpl->parse('main.loop.title_link');
                } else {
                    $xtpl->parse('main.loop.title_text');
                }

                if (!empty($row['image'])) {
                    if (!empty($row['link'])) {
                        if (!empty($row['target'])) {
                            $xtpl->parse('main.loop.image_link.target');
                        }

                        $xtpl->parse('main.loop.image_link');
                    } else {
                        $xtpl->parse('main.loop.image_only');
                    }
                }

                $xtpl->parse('main.loop');
            }

            $xtpl->parse('main');
            return $xtpl->text('main');
        }

        return '';
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_freecontent($block_config);
}
