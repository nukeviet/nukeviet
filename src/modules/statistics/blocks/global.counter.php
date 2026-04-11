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

if (!nv_function_exists('nv_block_counter')) {
    /**
     * @param array $block_config
     * @return string
     */
    function nv_block_counter(array $block_config)
    {
        global $db, $nv_Lang;

        $sql = 'SELECT c_type, c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE (c_type='day' AND c_val='" . date('d', NV_CURRENTTIME) . "') OR (c_type='month' AND c_val='" . date('M', NV_CURRENTTIME) . "') OR (c_type='total' AND c_val='hits')";
        $query = $db->query($sql);
        $count_data = [];
        while ($_scratch = $query->fetch(3)) {
            [$c_type, $c_count] = $_scratch;
            unset($_scratch);
            $c_type == 'total' && $c_type = 'all';
            $count_data[$c_type] = $c_count;
        }

        $sql = 'SELECT userid, username FROM ' . NV_SESSIONS_GLOBALTABLE . ' WHERE onl_time >= ' . (NV_CURRENTTIME - NV_ONLINE_UPD_TIME);
        $query = $db->query($sql);

        $count_data['online'] = $count_data['users'] = $count_data['bots'] = $count_data['guests'] = 0;
        while ($row = $query->fetch()) {
            ++$count_data['online'];

            if ($row['userid']) {
                ++$count_data['users'];
            } elseif (preg_match('/^bot\:/', $row['username'])) {
                ++$count_data['bots'];
            } else {
                ++$count_data['guests'];
            }
        }

        $count_data = array_map(function ($number) {
            return !empty($number) ? nv_number_format($number) : 0;
        }, $count_data);

        [$block_theme, $dir] = get_block_tpl_dir('global.counter.tpl', true, $block_config['module']);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('DATA', $count_data);

        return $tpl->fetch('global.counter.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    global $global_config;
    if ($global_config['online_upd']) {
        $content = nv_block_counter($block_config);
    }
}
