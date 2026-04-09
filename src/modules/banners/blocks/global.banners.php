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

if (!nv_function_exists('nv_block_data_config_banners')) {
    /**
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_data_config_banners($module, $data_block)
    {
        global $db, $language_array, $nv_Lang;

        [$block_theme, $dir] = get_block_tpl_dir('global.banners.config.tpl', true, $module);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('CONFIG', $data_block);

        $sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . "_plans WHERE (blang='" . NV_LANG_DATA . "' OR blang='') ORDER BY title ASC";
        $result = $db->query($sql);

        $plans = [];
        while ($row = $result->fetch()) {
            $row['show_title'] = $row['title'] . ' (';
            $row['show_title'] .= ((!empty($row['blang']) and isset($language_array[$row['blang']])) ? $language_array[$row['blang']]['name'] : $nv_Lang->getModule('blang_all')) . ', ';
            $row['show_title'] .= $row['form'] . ', ';
            $row['show_title'] .= $row['width'] . 'x' . $row['height'] . 'px';
            $row['show_title'] .= ')';

            $plans[$row['id']] = $row;
        }
        $result->closeCursor();

        $tpl->assign('PLANS', $plans);

        return $tpl->fetch('global.banners.config.tpl');
    }

    /**
     * @param string $module
     * @return array
     */
    function nv_block_data_config_banners_submit($module)
    {
        global $nv_Request, $nv_Lang;

        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['idplanbanner'] = $nv_Request->get_int('config_idplanbanner', 'post', 0);

        if (empty($return['config']['idplanbanner'])) {
            $return['error'][] = $nv_Lang->getModule('idplanbanner');
        }

        return $return;
    }

    /**
     * @param array $block_config
     * @return string|void
     */
    function nv_block_global_banners($block_config)
    {
        global $global_config, $client_info, $site_mods;

        if ($global_config['idsite']) {
            $xmlfile = NV_ROOTDIR . '/' . NV_DATADIR . '/site_' . $global_config['idsite'] . '_bpl_' . $block_config['idplanbanner'] . '.xml';
        } else {
            $xmlfile = NV_ROOTDIR . '/' . NV_DATADIR . '/bpl_' . $block_config['idplanbanner'] . '.xml';
        }

        if (!file_exists($xmlfile)) {
            return '';
        }

        $xml = simplexml_load_file($xmlfile);

        if ($xml === false) {
            return '';
        }

        $width_banners = (int) ($xml->width);
        $array_banners = $xml->banners->banners_item;

        $array_banners_content = [];

        foreach ($array_banners as $banners) {
            $banners = (array) $banners;
            if ($banners['publ_time'] < NV_CURRENTTIME and ($banners['exp_time'] == 0 or $banners['exp_time'] > NV_CURRENTTIME)) {
                $banners['file_height'] = empty($banners['file_height']) ? 0 : round($banners['file_height'] * $width_banners / $banners['file_width']);
                $banners['file_width'] = $width_banners;
                if (!empty($banners['imageforswf']) and !empty($client_info['is_mobile'])) {
                    $banners['file_name'] = $banners['imageforswf'];
                    $banners['file_ext'] = nv_getextension($banners['file_name']);
                }
                $banners['file_alt'] = (!empty($banners['file_alt'])) ? $banners['file_alt'] : $banners['title'];
                $banners['file_image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . NV_BANNER_DIR . '/' . $banners['file_name'];
                $banners['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=banners&amp;' . NV_OP_VARIABLE . '=click&amp;id=' . $banners['id'] . '&amp;s=' . md5($banners['id'] . NV_CHECK_SESSION);
                if (!empty($banners['bannerhtml'])) {
                    $banners['bannerhtml'] = html_entity_decode($banners['bannerhtml'], ENT_COMPAT | ENT_HTML401, strtoupper($global_config['site_charset']));
                }
                $array_banners_content[] = $banners;
            }
        }
        if (empty($array_banners_content)) {
            return '';
        }

        if ($xml->form == 'random') {
            shuffle($array_banners_content);
        } elseif ($xml->form == 'random_one') {
            $array_banners_content = [$array_banners_content[array_rand($array_banners_content)]];
        }
        unset($xml, $array_banners);

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir(get_block_tpl_dir('global.banners.tpl', module: $block_config['module']));
        $tpl->assign('DATA', $array_banners_content);

        return $tpl->fetch('global.banners.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_global_banners($block_config);
}
