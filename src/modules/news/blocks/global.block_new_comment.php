<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_comment_new')) {
    /**
     * nv_block_comment_new()
     *
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_comment_new($module, $data_block)
    {
        global $nv_Lang;

        [$block_theme, $dir] = get_block_tpl_dir('global.block_new_comment.config.tpl', true, $module);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('CONFIG', $data_block);

        return $tpl->fetch('global.block_new_comment.config.tpl');
    }

    /**
     * nv_block_comment_new_submit()
     *
     * @param string $module
     * @return array
     */
    function nv_block_comment_new_submit($module)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['titlelength'] = $nv_Request->get_int('config_titlelength', 'post', 0);
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);

        return $return;
    }

    /**
     * nv_comment_new()
     *
     * @param array $block_config
     * @return string
     */
    function nv_comment_new($block_config)
    {
        global $db, $site_mods, $global_config, $nv_Lang;

        $module = $block_config['module'];
        if (!isset($site_mods[$module])) {
            return '';
        }

        [$block_theme, $dir] = get_block_tpl_dir('global.block_new_comment.tpl', true, $module);
        if (empty($dir)) {
            return '';
        }

        // Bình luận bài viết được lưu với area là func_id của function detail
        $detail_alias = $site_mods[$module]['alias']['detail'] ?? '';
        if (empty($site_mods[$module]['funcs'][$detail_alias]['func_id'])) {
            return '';
        }
        $area = (int) $site_mods[$module]['funcs'][$detail_alias]['func_id'];

        $numrow = (int) $block_config['numrow'];
        if ($numrow <= 0) {
            return '';
        }

        $sql = 'SELECT id, content, post_time, post_name FROM ' . NV_PREFIXLANG . '_comment
        WHERE module = ' . $db->quote($module) . ' AND area = ' . $area . ' AND status = 1
        ORDER BY post_time DESC LIMIT ' . $numrow;
        $result = $db->query($sql);
        $array_comment = [];
        $array_news_id = [];
        while ($comment = $result->fetch()) {
            $array_comment[] = $comment;
            $array_news_id[] = (int) $comment['id'];
        }
        if (empty($array_comment)) {
            return '';
        }

        $mod_data = $site_mods[$module]['module_data'];
        $result = $db->query('SELECT t1.id, t1.alias AS alias_id, t2.alias AS alias_cat FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_rows t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $mod_data . '_cat t2 ON t1.catid = t2.catid WHERE t1.id IN (' . implode(',', array_unique($array_news_id)) . ') AND t1.status = 1');
        $array_news = [];
        while ($row = $result->fetch()) {
            $array_news[$row['id']] = $row;
        }

        $items = [];
        foreach ($array_comment as $comment) {
            if (!isset($array_news[$comment['id']])) {
                continue;
            }
            $news = $array_news[$comment['id']];

            // Nội dung có thể chứa thẻ br hoặc HTML từ editor nên loại bỏ thẻ trước khi cắt
            $items[] = [
                'post_name' => $comment['post_name'],
                'post_time' => nv_datetime_format($comment['post_time']),
                'content' => nv_clean60(strip_tags($comment['content']), (int) $block_config['titlelength']),
                'link' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module . '&' . NV_OP_VARIABLE . '=' . $news['alias_cat'] . '/' . $news['alias_id'] . '-' . $comment['id'] . $global_config['rewrite_exturl'], true)
            ];
        }
        if (empty($items)) {
            return '';
        }

        $nv_Lang->loadModule($site_mods[$module]['module_file'], loadtmp: true);

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('ITEMS', $items);

        $content = $tpl->fetch('global.block_new_comment.tpl');
        $nv_Lang->changeLang();

        return $content;
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_comment_new($block_config);
}
