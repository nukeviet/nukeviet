<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_comment_new')) {
    /**
     * nv_block_comment_new()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_comment_new($module, $data_block, $lang_block)
    {
        $html = '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['titlelength'] . ':</label>';
        $html .= '	<div class="col-sm-5"><input type="text" name="config_titlelength" class="form-control" value="' . $data_block['titlelength'] . '"/><span class="help-block panel-block-content-last">' . $lang_block['titlenote'] . '</span></div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['numrow'] . ':</label>';
        $html .= '	<div class="col-sm-5"><input type="text" name="config_numrow" class="form-control" value="' . $data_block['numrow'] . '"/></div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * nv_block_comment_new_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_comment_new_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['titlelength'] = $nv_Request->get_int('config_titlelength', 'post', 0);
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        return $return;
    }

    /**
     * nv_comment_new()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_comment_new($block_config)
    {
        global $db, $site_mods, $db_slave, $module_info, $global_config;

        $module = $block_config['module'];
        $mod_data = $site_mods[$module]['module_data'];

        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_comment WHERE module = " . $db->quote($module) . " AND status=1 ORDER BY post_time DESC LIMIT " . $block_config['numrow'];
        $result = $db_slave->query($sql);
        $array_comment = array();
        $array_news_id = array();
        while ($comment = $result->fetch()) {
            $array_comment[] = $comment;
            $array_news_id[] = $comment['id'];
        }

        if (!empty($array_news_id)) {
            $result = $db_slave->query('SELECT t1.id, t1.alias AS alias_id, t2.alias AS alias_cat FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_rows t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $mod_data . '_cat t2 ON t1.catid = t2.catid WHERE t1.id IN (' . implode(',', array_unique($array_news_id)) . ') AND t1.status = 1');
            $array_news_id = array();
            while ($row = $result->fetch()) {
                $array_news_id[$row['id']] = $row;
            }

            $mod_file = $site_mods[$module]['module_file'];
            if (file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $mod_file . '/block_new_comment.tpl')) {
                $block_theme = $module_info['template'];
            } else {
                $block_theme = 'default';
            }

            $xtpl = new XTemplate('block_new_comment.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file);
            $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
            $xtpl->assign('TEMPLATE', $block_theme);

            foreach ($array_comment as $comment) {
                if (isset($array_news_id[$comment['id']])) {
                    $comment['url_comment'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module . '&' . NV_OP_VARIABLE . '=' . $array_news_id[$comment['id']]['alias_cat'] . '/' . $array_news_id[$comment['id']]['alias_id'] . '-' . $comment['id'] . $global_config['rewrite_exturl'], true);
                    $comment['content'] = nv_clean60($comment['content'], $block_config['titlelength']);
                    $comment['post_time'] = nv_date('d/m/Y H:i', $comment['post_time']);
                    $xtpl->assign('COMMENT', $comment);
                    $xtpl->parse('main.loop');
                }
            }
            $xtpl->parse('main');
            return $xtpl->text('main');
        }
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_comment_new($block_config);
}
