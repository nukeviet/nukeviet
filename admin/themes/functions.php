<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$menu_top = [
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $lang_global['mod_themes']
];

define('NV_IS_FILE_THEMES', true);

// Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:themes';
$array_url_instruction['config'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:themes:config';
$array_url_instruction['setuplayout'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:themes:setuplayout';
$array_url_instruction['blocks'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:themes:blocks';
$array_url_instruction['xcopyblock'] = 'https://wiki.nukeviet.vn/themes:xcopyblock';
$array_url_instruction['package_theme_module'] = 'https://wiki.nukeviet.vn/themes:package_theme_module';

function get_dtime_details($dtime_type, $dtime_details)
{
    global $global_config, $module_file, $lang_module, $lang_global;

    if ($dtime_type != 'regular') {
        $xtpl = new XTemplate('block_content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('GLANG', $lang_global);

        if ($dtime_type == 'specific') {
            if (isset($dtime_details[0]['start_date'])) {
                $keys = count($dtime_details);
            } else {
                $keys = 1;
                $dtime_details = [];
            }

            for ($key = 0; $key < $keys; ++$key) {
                isset($dtime_details[$key]) && $xtpl->assign('DTIME_TYPE_ONCE', $dtime_details[$key]);

                for ($i = 0; $i < 24; ++$i) {
                    $name = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $xtpl->assign('START_H', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['start_h']) and $i == $dtime_details[$key]['start_h']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_specific.loop.start_h');

                    $xtpl->assign('END_H', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['end_h']) and $i == $dtime_details[$key]['end_h']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_specific.loop.end_h');
                }

                for ($i = 0; $i < 60; ++$i) {
                    $name = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $xtpl->assign('START_I', [
                        'val' => $i,
                        'sel' => (isset($dtime_details['start_i']) and $i == $dtime_details['start_i']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_specific.loop.start_i');

                    $xtpl->assign('END_I', [
                        'val' => $i,
                        'sel' => (isset($dtime_details['end_i']) and $i == $dtime_details['end_i']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_specific.loop.end_i');
                }
                $xtpl->parse('dtime_details.dtime_type_specific.loop');
            }
            $xtpl->parse('dtime_details.dtime_type_specific');
        } elseif ($dtime_type == 'daily') {
            if (isset($dtime_details[0]['start_h']) and count($dtime_details[0]) == 4) {
                $keys = count($dtime_details);
            } else {
                $keys = 1;
                $dtime_details = [];
            }
            for ($key = 0; $key < $keys; ++$key) {
                for ($i = 0; $i < 24; ++$i) {
                    $name = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $xtpl->assign('START_H', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['start_h']) and $i == $dtime_details[$key]['start_h']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_daily.loop.start_h');

                    $xtpl->assign('END_H', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['end_h']) and $i == $dtime_details[$key]['end_h']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_daily.loop.end_h');
                }

                for ($i = 0; $i < 60; ++$i) {
                    $name = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $xtpl->assign('START_I', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['start_i']) and $i == $dtime_details[$key]['start_i']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_daily.loop.start_i');

                    $xtpl->assign('END_I', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['end_i']) and $i == $dtime_details[$key]['end_i']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_daily.loop.end_i');
                }
                $xtpl->parse('dtime_details.dtime_type_daily.loop');
            }
            $xtpl->parse('dtime_details.dtime_type_daily');
        } elseif ($dtime_type == 'weekly') {
            if (isset($dtime_details[0]['day_of_week'])) {
                $keys = count($dtime_details);
            } else {
                $keys = 1;
                $dtime_details = [];
            }
            for ($key = 0; $key < $keys; ++$key) {
                for ($i = 1; $i <= 7; ++$i) {
                    $xtpl->assign('DAY_OF_WEEK', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['day_of_week']) and $i == $dtime_details[$key]['day_of_week']) ? ' selected="selected"' : '',
                        'name' => $lang_module['day_of_week_' . $i]
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_weekly.loop.day_of_week');
                }
                for ($i = 0; $i < 24; ++$i) {
                    $name = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $xtpl->assign('START_H', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['start_h']) and $i == $dtime_details[$key]['start_h']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_weekly.loop.start_h');

                    $xtpl->assign('END_H', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['end_h']) and $i == $dtime_details[$key]['end_h']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_weekly.loop.end_h');
                }

                for ($i = 0; $i < 60; ++$i) {
                    $name = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $xtpl->assign('START_I', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['start_i']) and $i == $dtime_details[$key]['start_i']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_weekly.loop.start_i');

                    $xtpl->assign('END_I', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['end_i']) and $i == $dtime_details[$key]['end_i']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_weekly.loop.end_i');
                }
                $xtpl->parse('dtime_details.dtime_type_weekly.loop');
            }
            $xtpl->parse('dtime_details.dtime_type_weekly');
        } elseif ($dtime_type == 'monthly') {
            if (isset($dtime_details[0]['day']) and count($dtime_details[0]) == 5) {
                $keys = count($dtime_details);
            } else {
                $keys = 1;
                $dtime_details = [];
            }
            for ($key = 0; $key < $keys; ++$key) {
                for ($i = 1; $i <= 31; ++$i) {
                    $xtpl->assign('DAY', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['day']) and $i == $dtime_details[$key]['day']) ? ' selected="selected"' : ''
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_monthly.loop.day');
                }
                for ($i = 0; $i < 24; ++$i) {
                    $name = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $xtpl->assign('START_H', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['start_h']) and $i == $dtime_details[$key]['start_h']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_monthly.loop.start_h');

                    $xtpl->assign('END_H', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['end_h']) and $i == $dtime_details[$key]['end_h']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_monthly.loop.end_h');
                }

                for ($i = 0; $i < 60; ++$i) {
                    $name = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $xtpl->assign('START_I', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['start_i']) and $i == $dtime_details[$key]['start_i']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_monthly.loop.start_i');

                    $xtpl->assign('END_I', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['end_i']) and $i == $dtime_details[$key]['end_i']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_monthly.loop.end_i');
                }
                $xtpl->parse('dtime_details.dtime_type_monthly.loop');
            }
            $xtpl->parse('dtime_details.dtime_type_monthly');
        } elseif ($dtime_type == 'yearly') {
            if (isset($dtime_details[0]['month'])) {
                $keys = count($dtime_details);
            } else {
                $keys = 1;
                $dtime_details = [];
            }

            for ($key = 0; $key < $keys; ++$key) {
                for ($i = 1; $i <= 12; ++$i) {
                    $xtpl->assign('MONTH', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['month']) and $i == $dtime_details[$key]['month']) ? ' selected="selected"' : ''
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_yearly.loop.month');
                }
                for ($i = 1; $i <= 31; ++$i) {
                    $xtpl->assign('DAY', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['day']) and $i == $dtime_details[$key]['day']) ? ' selected="selected"' : ''
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_yearly.loop.day');
                }
                for ($i = 0; $i < 24; ++$i) {
                    $name = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $xtpl->assign('START_H', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['start_h']) and $i == $dtime_details[$key]['start_h']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_yearly.loop.start_h');

                    $xtpl->assign('END_H', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['end_h']) and $i == $dtime_details[$key]['end_h']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_yearly.loop.end_h');
                }

                for ($i = 0; $i < 60; ++$i) {
                    $name = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $xtpl->assign('START_I', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['start_i']) and $i == $dtime_details[$key]['start_i']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_yearly.loop.start_i');

                    $xtpl->assign('END_I', [
                        'val' => $i,
                        'sel' => (isset($dtime_details[$key]['end_i']) and $i == $dtime_details[$key]['end_i']) ? ' selected="selected"' : '',
                        'name' => $name
                    ]);
                    $xtpl->parse('dtime_details.dtime_type_yearly.loop.end_i');
                }
                $xtpl->parse('dtime_details.dtime_type_yearly.loop');
            }
            $xtpl->parse('dtime_details.dtime_type_yearly');
        }
        $xtpl->parse('dtime_details');

        return $xtpl->text('dtime_details');
    }

    return '';
}

function loadblock($module, $bid, $selectthemes = '')
{
    global $db, $lang_module, $global_config, $site_mods;

    $row = ['theme' => '', 'file_name' => ''];
    if ($bid > 0) {
        $row = $db->query('SELECT theme, file_name FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $bid)->fetch();
    }

    $return = '<option value="">' . $lang_module['block_select'] . '</option>';

    if ($module == 'theme') {
        if (empty($row['theme'])) {
            $row['theme'] = !empty($selectthemes) ? $selectthemes : $global_config['site_theme'];
        }

        $block_file_list = nv_scandir(NV_ROOTDIR . '/themes/' . $row['theme'] . '/blocks', $global_config['check_block_theme']);
        foreach ($block_file_list as $file_name) {
            if (preg_match($global_config['check_block_theme'], $file_name, $matches)) {
                $sel = ($file_name == $row['file_name']) ? ' selected="selected"' : '';
                $load_config = (file_exists(NV_ROOTDIR . '/themes/' . $row['theme'] . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini')) ? 1 : 0;
                $load_mod_array = [];
                if ($matches[1] != 'global') {
                    foreach ($site_mods as $mod => $row_i) {
                        if ($row_i['module_file'] == $matches[1]) {
                            $load_mod_array[] = $mod;
                        }
                    }
                }
                $return .= '<option value="' . $file_name . '|' . $load_config . '|' . implode('.', $load_mod_array) . '" ' . $sel . '>' . $matches[1] . ' ' . $matches[2] . ' </option>';
            }
        }
    } elseif (isset($site_mods[$module]['module_file'])) {
        $module_file = $site_mods[$module]['module_file'];
        if (!empty($module_file)) {
            if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/blocks')) {
                $block_file_list = nv_scandir(NV_ROOTDIR . '/modules/' . $module_file . '/blocks', $global_config['check_block_module']);

                foreach ($block_file_list as $file_name) {
                    $sel = ($file_name == $row['file_name']) ? ' selected="selected"' : '';

                    unset($matches);
                    preg_match($global_config['check_block_module'], $file_name, $matches);

                    $load_config = (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini')) ? 1 : 0;

                    $return .= '<option value="' . $file_name . '|' . $load_config . '|" ' . $sel . '>' . $matches[1] . ' ' . $matches[2] . ' </option>';
                }
            }
        }
    }

    return $return;
}
