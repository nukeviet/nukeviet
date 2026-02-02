<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_THEMES')) {
    exit('Stop!!!');
}

$selectthemes = $nv_Request->get_title('theme', 'post', '');
if (md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $admin_info['userid'] . '_' . $selectthemes) != $nv_Request->get_string('checkss', 'post') or empty($selectthemes) or !(preg_match($global_config['check_theme'], $selectthemes) or preg_match($global_config['check_theme_mobile'], $selectthemes))) {
    exit();
}

$sth = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id=0 AND theme= :theme');
$sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
$sth->execute();
if (preg_match($global_config['check_theme'], $selectthemes) and $sth->fetchColumn()) {
    // Kích hoạt sử dụng nếu đã có thiết lập
    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value= :theme WHERE config_name='site_theme' AND lang='" . NV_LANG_DATA . "'");
    $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
    $sth->execute();

    $global_config['site_theme'] = $selectthemes;
    $nv_Cache->delAll();
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('block_active') . ' theme: "' . $selectthemes . '"', '', $admin_info['userid']);

    echo 'OK_' . $selectthemes;
} elseif (!empty($selectthemes) and file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini')) {
    // Thiết lập giao diện theo cấu hình mặc định
    $sth = $db->prepare('SELECT count(*) FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id = 0 AND theme= :theme');
    $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
    $sth->execute();
    $count = $sth->fetchColumn();
    if (empty($count)) {
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('config') . ' theme: "' . $selectthemes . '"', '', $admin_info['userid']);

        // Thiết lập Layout
        $xml = simplexml_load_file(NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini');
        $layoutdefault = (string) $xml->layoutdefault;
        $layout = $xml->xpath('setlayout/layout');

        for ($i = 0, $count = count($layout); $i < $count; ++$i) {
            $layout_name = (string) $layout[$i]->name;

            $layout_funcs = $layout[$i]->xpath('funcs');

            for ($j = 0, $sizeof = count($layout_funcs); $j < $sizeof; ++$j) {
                $mo_funcs = (string) $layout_funcs[$j];
                $mo_funcs = explode(':', $mo_funcs);
                $m = $mo_funcs[0];
                $arr_f = explode(',', $mo_funcs[1]);

                foreach ($arr_f as $f) {
                    $array_layout_func_default[$m][$f] = $layout_name;
                }
            }
        }

        $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_modthemes (func_id, layout, theme) VALUES (:func_id, :layout, :theme)');
        $sth->bindValue(':func_id', 0, PDO::PARAM_INT);
        $sth->bindParam(':layout', $layoutdefault, PDO::PARAM_STR);
        $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
        $sth->execute();

        $fnresult = $db->query('SELECT func_id, func_name, func_custom_name, in_module FROM ' . NV_MODFUNCS_TABLE . ' WHERE show_func=1 ORDER BY subweight ASC');
        while ([$func_id, $func_name, $func_custom_name, $in_module] = $fnresult->fetch(3)) {
            $layout_name = (isset($array_layout_func_default[$in_module][$func_name])) ? $array_layout_func_default[$in_module][$func_name] : $layoutdefault;
            $sth->bindParam(':func_id', $func_id, PDO::PARAM_INT);
            $sth->bindParam(':layout', $layout_name, PDO::PARAM_STR);
            $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
            $sth->execute();
        }

        // Thiết lập Block
        $array_all_funcid = [];
        $func_result = $db->query('SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE show_func = 1 ORDER BY in_module ASC, subweight ASC');
        while ([$func_id_i] = $func_result->fetch(3)) {
            $array_all_funcid[] = $func_id_i;
        }

        $blocks = $xml->xpath('setblocks/block');
        for ($i = 0, $count = count($blocks); $i < $count; ++$i) {
            $row = (array) $blocks[$i];

            if (!isset($row['link'])) {
                $row['link'] = '';
            }
            if (!isset($row['module'])) {
                $row['module'] = 'theme';
            }
            if (!isset($row['heading'])) {
                $row['heading'] = 0;
            }

            $file_name = $row['file_name'];

            if ($row['module'] == 'theme' and preg_match($global_config['check_block_theme'], $file_name, $matches)) {
                if (!file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $file_name)) {
                    continue;
                }
            } elseif (isset($site_mods[$row['module']]) and preg_match($global_config['check_block_module'], $file_name, $matches)) {
                $mod_file = $site_mods[$row['module']]['module_file'];
                if (!file_exists(NV_ROOTDIR . '/modules/' . $mod_file . '/blocks/' . $file_name)) {
                    continue;
                }
            } else {
                continue;
            }

            $sth = $db->prepare('SELECT MAX(weight) FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme AND position= :position');
            $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
            $sth->bindParam(':position', $row['position'], PDO::PARAM_STR);
            $sth->execute();

            $row['weight'] = (int) ($sth->fetchColumn()) + 1;

            $row['dtime_type'] = 'regular';
            $row['dtime_details'] = '[]';
            $row['active'] = 1;
            $row['bot_visible'] = 1;
            $row['groups_view'] = '6';

            $all_func = ($row['all_func'] == 1 and preg_match('/^global\.([a-zA-Z0-9\-\_\.]+)\.php$/', $file_name)) ? 1 : 0;

            $_sql = 'INSERT INTO ' . NV_BLOCKS_TABLE . "_groups (
                theme, module, file_name, title, link, template, heading, position,
                dtime_type, dtime_details, active, bot_visible, groups_view, all_func, weight, config
            ) VALUES (
                :selectthemes, :module, :file_name, :title, :link, :template, :heading, :position,
                :dtime_type, :dtime_details, '" . $row['active'] . "', '" . $row['bot_visible'] . "', :groups_view,
                '" . $all_func . "', '" . $row['weight'] . "', :config
            )";
            $data = [];
            $data['selectthemes'] = $selectthemes;
            $data['module'] = $row['module'];
            $data['file_name'] = $file_name;
            $data['title'] = $row['title'];
            $data['link'] = $row['link'];
            $data['template'] = (string) $row['template'];
            $data['heading'] = (int) $row['heading'];
            $data['position'] = $row['position'];
            $data['dtime_type'] = $row['dtime_type'];
            $data['dtime_details'] = $row['dtime_details'];
            $data['groups_view'] = $row['groups_view'];
            $data['config'] = !empty($row['config']) ? (string) $row['config'] : '';
            $row['bid'] = $db->insert_id($_sql, 'bid', $data);
            if ($all_func) {
                $array_funcid = $array_all_funcid;
            } else {
                $array_funcid = [];
                if (!is_array($row['funcs'])) {
                    $row['funcs'] = [$row['funcs']];
                }
                foreach ($row['funcs'] as $_funcs_list) {
                    [$mod, $func_list] = explode(':', $_funcs_list);
                    if (isset($site_mods[$mod])) {
                        $func_array = explode(',', $func_list);
                        foreach ($site_mods[$mod]['funcs'] as $_tmp) {
                            if (in_array($_tmp['func_name'], $func_array, true)) {
                                $array_funcid[] = $_tmp['func_id'];
                            }
                        }
                    }
                }
            }

            $sth = $db->prepare('SELECT MAX(t1.weight) FROM ' . NV_BLOCKS_TABLE . '_weight t1 INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid WHERE t1.func_id= :func_id AND t2.theme= ' . $db->quote($selectthemes) . ' AND t2.position= :position');
            foreach ($array_funcid as $func_id) {
                $sth->bindParam(':func_id', $func_id, PDO::PARAM_INT);
                $sth->bindParam(':position', $row['position'], PDO::PARAM_STR);
                $sth->execute();
                $weight = $sth->fetchColumn();
                $weight = (int) $weight + 1;

                $db->query('INSERT INTO ' . NV_BLOCKS_TABLE . '_weight (bid, func_id, weight) VALUES (' . $row['bid'] . ', ' . $func_id . ', ' . $weight . ')');
            }
        }
    }
    echo 'OK_' . $selectthemes;
} else {
    echo $nv_Lang->getModule('theme_created_activate_layout');
}
