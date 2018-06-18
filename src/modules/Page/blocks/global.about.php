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

if (!nv_function_exists('nv_message_page')) {

    /**
     * nv_block_config_about()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_about($module, $data_block, $nv_Lang)
    {
        global $db, $site_mods;

        $html = '';
        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $nv_Lang->getBlock('title_length') . ':</label>';
        $html .= '	<div class="col-sm-9"><input type="text" class="form-control" name="config_title_length" value="' . $data_block['title_length'] . '"/></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $nv_Lang->getBlock('bodytext_length') . ':</label>';
        $html .= '	<div class="col-sm-9"><input type="text" class="form-control" name="config_bodytext_length" value="' . $data_block['bodytext_length'] . '"/></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '	<label class="text-right col-sm-6">' . $nv_Lang->getBlock('show_image') . ':</label>';
        $ck = $data_block['show_image'] ? 'checked="checked"' : '';
        $html .= '	<div class="col-sm-9"><input type="checkbox" name="config_show_image" value="1" ' . $ck . '/></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $nv_Lang->getBlock('rowid') . ':</label>';
        $html .= '	<div class="col-sm-9"><select class="form-control" name="config_rowid"><option value="0">---' . $nv_Lang->getBlock('rowid_select') . '---</option>';

        $result = $db->query('SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . ' WHERE status=1');
        while (list ($id, $title) = $result->fetch(3)) {
            $sl = $id == $data_block['rowid'] ? 'selected="selected"' : '';
            $html .= '<option value="' . $id . '" ' . $sl . '>' . $title . '</option>';
        }

        $html .= '  </select><span class="help-block">' . $nv_Lang->getBlock('rowid_note') . '</span></div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * nv_block_config_about_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_about_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 0);
        $return['config']['bodytext_length'] = $nv_Request->get_int('config_bodytext_length', 'post', 300);
        $return['config']['rowid'] = $nv_Request->get_int('config_rowid', 'post', 0);
        $return['config']['show_image'] = $nv_Request->get_int('config_show_image', 'post', 0);
        return $return;
    }

    /**
     * nv_message_page()
     *
     * @return
     */
    function nv_message_page($block_config)
    {
        global $nv_Cache, $global_config, $site_mods, $db_slave, $module_name;
        $module = $block_config['module'];

        if (!isset($site_mods[$module])) {
            return '';
        }

        if ($module_name == $module) {
            return '';
        }

        $is_show = false;

        $pattern = '/^' . NV_LANG_DATA . '\_([a-zA-z0-9\_\-]+)\_([0-9]+)\_' . NV_CACHE_PREFIX . '\.cache$/i';

        $cache_files = nv_scandir(NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $module, $pattern);

        if (($count = sizeof($cache_files)) >= 1) {
            $num = rand(1, $count);
            --$num;
            $cache_file = $cache_files[$num];

            if (($cache = $nv_Cache->getItem($module, $cache_file)) != false) {
                $cache = unserialize($cache);
                $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $cache['alias'] . $global_config['rewrite_exturl'];
                $title = $cache['page_title'];
                $bodytext = strip_tags($cache['bodytext']);

                $is_show = true;
            }
        }
        if (!$is_show) {
            $where = $order = '';
            if (!empty($block_config['rowid'])) {
                $where = ' AND id=' . $block_config['rowid'];
            } else {
                $order = ' ORDER BY rand() DESC';
            }
            $sql = 'SELECT id, title, alias, bodytext, keywords, add_time, edit_time, image FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . ' WHERE status=1' . $where . $order;
            if (($query = $db_slave->query($sql)) !== false) {
                if (($row = $query->fetch()) !== false) {
                    $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'];
                    $title = $row['title'];
                    $bodytext = strip_tags($row['bodytext']);
                    $bodytext = nv_clean60($bodytext, $block_config['bodytext_length']);
                    if ($block_config['show_image']) {
                        if (!empty($row['image']) and file_exists(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['image'])) {
                            $image = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['image'];
                        } elseif (!empty($row['image']) and file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['image'])) {
                            $image = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['image'];
                        } else {
                            $image = '';
                        }
                    }
                    $is_show = true;
                }
            }
        }

        if ($is_show) {
            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/Page/block.about.tpl')) {
                $block_theme = $global_config['module_theme'];
            } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/Page/block.about.tpl')) {
                $block_theme = $global_config['site_theme'];
            } else {
                $block_theme = 'default';
            }

            $xtpl = new XTemplate('block.about.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/Page');
            $xtpl->assign('LINK', $link);
            $xtpl->assign('TITLE', $title);
            $xtpl->assign('TITLE_TRIM', nv_clean60($title, $block_config['title_length']));
            $xtpl->assign('BODYTEXT', $bodytext);

            if ($block_config['show_image'] and !empty($image)) {
                $xtpl->assign('IMAGE', $image);
                $xtpl->parse('main.image');
            }

            $xtpl->parse('main');
            return $xtpl->text('main');
        }

        return '';
    }
}

$content = nv_message_page($block_config);