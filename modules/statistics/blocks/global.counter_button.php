<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_block_counter_button')) {
    /**
     * nv_block_counter_button()
     *
     * @return string
     */
    function nv_block_counter_button()
    {
        global $global_config, $db, $lang_global;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/statistics/global.counter_button.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/statistics/global.counter_button.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('global.counter_button.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/statistics');

        $xtpl->assign('LANG', $lang_global);
        $xtpl->assign('IMG_PATH', NV_STATIC_URL . 'themes/' . $block_theme . '/');

        $sql = 'SELECT c_type, c_count FROM ' . NV_COUNTER_GLOBALTABLE . " WHERE (c_type='day' AND c_val='" . date('d', NV_CURRENTTIME) . "') OR (c_type='month' AND c_val='" . date('M', NV_CURRENTTIME) . "') OR (c_type='total' AND c_val='hits')";
        $query = $db->query($sql);
        while (list($c_type, $c_count) = $query->fetch(3)) {
            if ($c_type == 'day') {
                $xtpl->assign('COUNT_DAY', number_format($c_count));
            } elseif ($c_type == 'month') {
                $xtpl->assign('COUNT_MONTH', number_format($c_count));
            } elseif ($c_type == 'total') {
                $xtpl->assign('COUNT_ALL', number_format($c_count));
            }
        }

        $sql = 'SELECT userid, username FROM ' . NV_SESSIONS_GLOBALTABLE . ' WHERE onl_time >= ' . (NV_CURRENTTIME - NV_ONLINE_UPD_TIME);
        $query = $db->query($sql);

        $count_online = $users = $bots = $guests = 0;
        while ($row = $query->fetch()) {
            ++$count_online;

            if ($row['userid']) {
                ++$users;
            } elseif (preg_match('/^bot\:/', $row['username'])) {
                ++$bots;
            } else {
                ++$guests;
            }
        }

        $xtpl->assign('COUNT_ONLINE', number_format($count_online));

        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

        if ($users) {
            $xtpl->assign('COUNT_USERS', number_format($users));
            $xtpl->parse('main.users');
        }

        if ($bots) {
            $xtpl->assign('COUNT_BOTS', number_format($bots));
            $xtpl->parse('main.bots');
        }

        if ($guests and $guests != $count_online) {
            $xtpl->assign('COUNT_GUESTS', number_format($guests));
            $xtpl->parse('main.guests');
        }

        $xtpl->parse('main');
        $content = $xtpl->text('main');

        return $content;
    }
}

if (defined('NV_SYSTEM')) {
    global $global_config;
    if ($global_config['online_upd']) {
        $content = nv_block_counter_button();
    }
}
