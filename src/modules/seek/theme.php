<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_SEARCH')) {
    exit('Stop!!!');
}

/**
 * search_main_theme()
 *
 * @param bool  $is_search
 * @param array $search
 * @param array $array_modul
 * @return string
 */
function search_main_theme($is_search, $search, $array_modul)
{
    global $module_info, $global_config, $nv_Lang, $module_name;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('form.tpl'));

    $mods = [];
    foreach ($array_modul as $m_name => $m_info) {
        $mods[] = [
            'value' => $m_name,
            'custom_title' => $m_info['custom_title'],
            'adv_search' => (bool) $m_info['adv_search'],
            'url' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $m_name . '&' . NV_OP_VARIABLE . '=search',
            'is_selected' => ($m_name == $search['mod']),
        ];
    }

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('DATA', $search);
    $tpl->assign('MODS', $mods);
    $tpl->assign('IS_SEARCH', $is_search);
    $tpl->assign('SEARCH_ENGINE_ID', $global_config['searchEngineUniqueID'] ?? '');

    return $tpl->fetch('form.tpl');
}

/**
 * urlencode_rfc_3986()
 *
 * @param string $string
 * @return string
 */
function urlencode_rfc_3986($string)
{
    $entities = ['%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D'];
    $replacements = ['!', '*', "'", '(', ')', ';', ':', '@', '&', '=', '+', '$', ',', '/', '?', '%', '#', '[', ']'];

    return str_replace($entities, $replacements, urlencode($string));
}

/**
 * search_result_theme()
 *
 * @param array  $result_array
 * @param string $mod
 * @param string $mod_custom_title
 * @param array  $search
 * @param bool   $is_generate_page
 * @param int    $limit
 * @param int    $num_items
 * @return string
 */
function search_result_theme($result_array, $mod, $mod_custom_title, $search, $is_generate_page, $limit, $num_items)
{
    global $module_name, $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('result.tpl'));

    $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&q=' . urlencode_rfc_3986($search['key']);
    if ($mod != 'all') {
        $base_url .= '&m=' . $mod;
    }
    if (empty($search['logic'])) {
        $base_url .= '&l=' . $search['logic'];
    }

    $pagination = '';
    $more = '';
    if ($is_generate_page) {
        $pagination = nv_generate_page($base_url, $num_items, $limit, $search['page']) ?? '';
    } elseif ($num_items > $limit) {
        $more = $base_url;
    }

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('SEARCH_RESULT_NUM', $num_items);
    $tpl->assign('MODULE_CUSTOM_TITLE', $mod_custom_title);
    $tpl->assign('HIDDEN_KEY', $search['key']);
    $tpl->assign('RESULTS', $result_array);
    $tpl->assign('PAGINATION', $pagination);
    $tpl->assign('MORE', $more);

    return $tpl->fetch('result.tpl');
}
