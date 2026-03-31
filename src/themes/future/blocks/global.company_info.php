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

if (!nv_function_exists('nv_company_info')) {
    /**
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_company_info_config($module, $data_block)
    {
        global $nv_Lang;

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir(__DIR__);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('CONFIG', $data_block);

        return $tpl->fetch('global.company_info.config.tpl');
    }

    /**
     * @return array
     */
    function nv_company_info_submit()
    {
        global $nv_Request;

        $return = [];
        $return['error'] = [];
        $return['config']['company_name'] = $nv_Request->get_title('config_company_name', 'post');
        $return['config']['company_sortname'] = $nv_Request->get_title('config_company_sortname', 'post');
        $return['config']['company_regcode'] = $nv_Request->get_title('config_company_regcode', 'post');
        $return['config']['company_regplace'] = $nv_Request->get_title('config_company_regplace', 'post');
        $return['config']['company_licensenumber'] = $nv_Request->get_title('config_company_licensenumber', 'post');
        $return['config']['company_responsibility'] = $nv_Request->get_title('config_company_responsibility', 'post');
        $return['config']['company_address'] = $nv_Request->get_title('config_company_address', 'post');
        $return['config']['company_showmap'] = $nv_Request->get_int('config_company_showmap', 'post', 0) == 0 ? 0 : 1;
        $return['config']['company_mapurl'] = $nv_Request->get_title('config_company_mapurl', 'post', '');
        $return['config']['company_phone'] = $nv_Request->get_title('config_company_phone', 'post');
        $return['config']['company_fax'] = $nv_Request->get_title('config_company_fax', 'post');
        $return['config']['company_email'] = $nv_Request->get_title('config_company_email', 'post');
        $return['config']['company_website'] = $nv_Request->get_title('config_company_website', 'post');

        return $return;
    }

    /**
     * @param array $block_config
     * @return string
     */
    function nv_company_info($block_config)
    {
        global $global_config, $nv_Lang;

        // JSON-LD LocalBusiness
        $ld_json = [
            '@context' => 'http://schema.org',
            '@type' => 'LocalBusiness',
            'priceRange' => 'N/A',
            'image' => [NV_MY_DOMAIN . NV_BASE_SITEURL . $global_config['site_logo']],
        ];
        !empty($block_config['company_name']) && $ld_json['name'] = $block_config['company_name'];
        !empty($block_config['company_sortname']) && $ld_json['alternateName'] = $block_config['company_sortname'];
        if (!empty($block_config['company_responsibility'])) {
            $ld_json['founder'] = [
                '@type' => 'Person',
                'name' => $block_config['company_responsibility'],
            ];
        }
        if (!empty($block_config['company_address'])) {
            $ld_json['address'] = [
                '@type' => 'PostalAddress',
                'addressLocality' => $block_config['company_address'],
                'postalCode' => 'N/A',
                'streetAddress' => 'N/A',
                'addressCountry' => NV_LANG_DATA
            ];
        }
        if (!empty($block_config['company_showmap']) && !empty($block_config['company_mapurl'])) {
            $ld_json['hasMap'] = $block_config['company_mapurl'];
        }

        $company_regcode = '';
        if (!empty($block_config['company_regcode'])) {
            $company_regcode .= $nv_Lang->getGlobal('company_regcode2') . ': ' . $block_config['company_regcode'];
            if (!empty($block_config['company_regplace'])) {
                $company_regcode .= ', ' . $nv_Lang->getGlobal('company_regplace') . ' ' . $block_config['company_regplace'];
            }
        }
        if (!empty($block_config['company_licensenumber'])) {
            $company_regcode .= ', ' . $nv_Lang->getGlobal('company_licensenumber') . ': ' . $block_config['company_licensenumber'];
        }

        $block_config['company_regcode'] = $company_regcode;
        $block_config['company_phone'] = nv_parse_phone($block_config['company_phone']);
        $block_config['company_email'] = !empty($block_config['company_email']) ? array_map('trim', explode(',', $block_config['company_email'])) : [];
        $block_config['company_website'] = !empty($block_config['company_website']) ? array_map('trim', explode(',', $block_config['company_website'])) : [];
        !empty($block_config['company_website']) && $block_config['company_website'] = array_map(function($url) {
            if (!preg_match("/^https?\:\/\//", $url)) {
                $url = 'http://' . $url;
            }
            return $url;
        }, $block_config['company_website']);

        if (!empty($block_config['company_phone'])) {
            $ld_json['telephone'] = $block_config['company_phone'][0][1] ?? $block_config['company_phone'][0][0];
        }
        !empty($block_config['company_email']) && $ld_json['email'] = $block_config['company_email'][0];
        !empty($block_config['company_website']) && $ld_json['url'] = $block_config['company_website'][0];
        !empty($block_config['company_fax']) && $ld_json['faxNumber'] = $block_config['company_fax'];

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($block_config['real_path']);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('DATA', $block_config);
        $tpl->assign('LD_JSON', json_encode($ld_json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        return $tpl->fetch('global.company_info.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_company_info($block_config);
}
