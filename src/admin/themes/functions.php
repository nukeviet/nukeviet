<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$menu_top = [
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $nv_Lang->getGlobal('mod_themes')
];

define('NV_IS_FILE_THEMES', true);

// Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:themes';
$array_url_instruction['config'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:themes:config';
$array_url_instruction['setuplayout'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:themes:setuplayout';
$array_url_instruction['blocks'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:themes:blocks';
$array_url_instruction['xcopyblock'] = 'https://wiki.nukeviet.vn/themes:xcopyblock';
$array_url_instruction['package_theme_module'] = 'https://wiki.nukeviet.vn/themes:package_theme_module';

/**
 * Hiển thị khoảng thời gian hiển thị block khi thêm/sửa block
 *
 * @param string $dtime_type
 * @param array $dtime_details
 * @return string
 */
function get_dtime_details($dtime_type, $dtime_details)
{
    global $global_config, $module_file, $nv_Lang, $module_name, $op;

    if ($dtime_type == 'regular') {
        return '';
    }

    [$template, $dir] = get_module_tpl_dir('block-dtime.tpl', true);
    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir($dir);
    $tpl->registerPlugin('modifier', 'str_pad', 'str_pad');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('TEMPLATE', $template);

    $tpl->assign('DTIME_TYPE', $dtime_type);

    if ($dtime_type == 'specific') {
        if (isset($dtime_details[0]['start_date'])) {
            $keys = count($dtime_details);
        } else {
            $keys = 1;
            $dtime_details = [];
        }
    } elseif ($dtime_type == 'daily') {
        if (isset($dtime_details[0]['start_h']) and count($dtime_details[0]) == 4) {
            $keys = count($dtime_details);
        } else {
            $keys = 1;
            $dtime_details = [];
        }
    } elseif ($dtime_type == 'weekly') {
        if (isset($dtime_details[0]['day_of_week'])) {
            $keys = count($dtime_details);
        } else {
            $keys = 1;
            $dtime_details = [];
        }
    } elseif ($dtime_type == 'monthly') {
        if (isset($dtime_details[0]['day']) and count($dtime_details[0]) == 5) {
            $keys = count($dtime_details);
        } else {
            $keys = 1;
            $dtime_details = [];
        }
    } elseif ($dtime_type == 'yearly') {
        if (isset($dtime_details[0]['month'])) {
            $keys = count($dtime_details);
        } else {
            $keys = 1;
            $dtime_details = [];
        }
    }

    $tpl->assign('CFG_LINE', $keys);
    $tpl->assign('DETAILS', $dtime_details);

    return $tpl->fetch('block-dtime.tpl');
}

/**
 * @param string $file_ini
 * @param string $file_json
 * @return array
 */
function get_block_info(string $file_ini, string $file_json)
{
    $block_name = '';
    $has_config = 0;

    // Lấy thông tin theo thứ tự ngược từ json (mới) đến ini (cũ)
    if (file_exists($file_json)) {
        $json = json_decode(file_get_contents($file_json), true);
        if (!empty($json) and is_array($json)) {
            // Xác định có config không
            if (!empty($json['datafunction']) and is_string($json['datafunction'])) {
                $has_config = 1;
            }
            // Xác định tên block
            if (!empty($json['i18n']) and is_array($json['i18n'])) {
                if (!empty($json['i18n'][NV_LANG_INTERFACE]) and is_array($json['i18n'][NV_LANG_INTERFACE]) and !empty($json['i18n'][NV_LANG_INTERFACE]['info']) and is_array($json['i18n'][NV_LANG_INTERFACE]['info'])) {
                    $block_name = strval($json['i18n'][NV_LANG_INTERFACE]['info']['name'] ?? '');
                }
                if (empty($block_name) and !empty($json['i18n']['en']) and is_array($json['i18n']['en']) and !empty($json['i18n']['en']['info']) and is_array($json['i18n']['en']['info'])) {
                    $block_name = strval($json['i18n']['en']['info']['name'] ?? '');
                }
            }
        }
    }
    if (empty($has_config) and file_exists($file_ini)) {
        $xml = simplexml_load_file($file_ini);
        if ($xml !== false and trim((string) $xml->datafunction) != '') {
            $has_config = 1;
        }
    }

    return [
        'config' => $has_config,
        'name' => $block_name
    ];
}

/**
 * Lấy danh sách option block của module/giao diện khi thêm/sửa block
 *
 * @param string $module
 * @param int $bid
 * @param string $selectthemes
 * @return string
 */
function loadblock($module, $bid, $selectthemes = '')
{
    global $db, $nv_Lang, $global_config, $site_mods;

    $row = ['theme' => '', 'file_name' => ''];
    if ($bid > 0) {
        $row = $db->query('SELECT theme, file_name FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $bid)->fetch();
    }

    $return = '<option value="">' . $nv_Lang->getModule('block_select') . '</option>';

    if ($module == 'theme') {
        // Block của giao diện
        if (empty($row['theme'])) {
            $row['theme'] = !empty($selectthemes) ? $selectthemes : $global_config['site_theme'];
        }

        $block_file_list = nv_scandir(NV_ROOTDIR . '/themes/' . $row['theme'] . '/blocks', $global_config['check_block_theme']);
        foreach ($block_file_list as $file_name) {
            if (preg_match($global_config['check_block_theme'], $file_name, $matches)) {
                $sel = ($file_name == $row['file_name']) ? ' selected="selected"' : '';

                $file_ini = NV_ROOTDIR . '/themes/' . $row['theme'] . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini';
                $file_json = NV_ROOTDIR . '/themes/' . $row['theme'] . '/blocks/' . $matches[1] . '.' . $matches[2] . '.json';
                $block_info = get_block_info($file_ini, $file_json);

                $load_mod_array = [];
                if ($matches[1] != 'global') {
                    foreach ($site_mods as $mod => $row_i) {
                        if ($row_i['module_file'] == $matches[1]) {
                            $load_mod_array[] = $mod;
                        }
                    }
                }

                $block_name = nv_ucfirst($matches[1]) . ': ' . ($block_info['name'] ?: $matches[2]);
                $return .= '<option value="' . $file_name . '|' . $block_info['config'] . '|' . implode('.', $load_mod_array) . '" ' . $sel . '>' . $block_name . ' </option>';
            }
        }
    } elseif (isset($site_mods[$module]['module_file'])) {
        // Block của module
        $module_file = $site_mods[$module]['module_file'];
        if (!empty($module_file) and file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/blocks')) {
            $block_file_list = nv_scandir(NV_ROOTDIR . '/modules/' . $module_file . '/blocks', $global_config['check_block_module']);

            foreach ($block_file_list as $file_name) {
                $sel = ($file_name == $row['file_name']) ? ' selected="selected"' : '';

                unset($matches);
                preg_match($global_config['check_block_module'], $file_name, $matches);

                $file_ini = NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini';
                $file_json = NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.json';
                $block_info = get_block_info($file_ini, $file_json);

                $block_name = nv_ucfirst($matches[1]) . ': ' . ($block_info['name'] ?: $matches[2]);
                $return .= '<option value="' . $file_name . '|' . $block_info['config'] . '|" ' . $sel . '>' . $block_name . ' </option>';
            }
        }
    }

    return $return;
}
