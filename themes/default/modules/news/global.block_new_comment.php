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

if (!nv_function_exists('nv_comment_new')) {
    /**
     * nv_comment_new()
     *
     * @param array $block_config
     * @return string|void
     */
    function nv_comment_new($block_config)
    {
        global $db, $site_mods, $db_slave, $module_info, $global_config;

        $module = $block_config['module'];
        $mod_data = $site_mods[$module]['module_data'];

        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_comment WHERE module = ' . $db->quote($module) . ' AND status=1 ORDER BY post_time DESC LIMIT ' . $block_config['numrow'];
        $result = $db_slave->query($sql);
        $array_comment = [];
        $array_news_id = [];
        while ($comment = $result->fetch()) {
            $array_comment[] = $comment;
            $array_news_id[] = $comment['id'];
        }

        $scomments = [];
        if (!empty($array_news_id)) {
            $result = $db_slave->query('SELECT t1.id, t1.alias AS alias_id, t2.alias AS alias_cat FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_rows t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $mod_data . '_cat t2 ON t1.catid = t2.catid WHERE t1.id IN (' . implode(',', array_unique($array_news_id)) . ') AND t1.status = 1');
            $array_news_id = [];
            while ($row = $result->fetch()) {
                $array_news_id[$row['id']] = $row;
            }

            foreach ($array_comment as $comment) {
                if (isset($array_news_id[$comment['id']])) {
                    $comment['url_comment'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module . '&' . NV_OP_VARIABLE . '=' . $array_news_id[$comment['id']]['alias_cat'] . '/' . $array_news_id[$comment['id']]['alias_id'] . '-' . $comment['id'] . $global_config['rewrite_exturl'], true);
                    $comment['post_time'] = nv_date('d/m, H:i', $comment['post_time']);
                    $scomments[] = $comment;
                }
            }
        }

        if (empty($scomments)) {
            return '';
        }

        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
        $stpl->assign('CONFIGS', $block_config);
        $stpl->assign('COMMENTS', $scomments);

        return $stpl->fetch('block_new_comment.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_comment_new($block_config);
}
