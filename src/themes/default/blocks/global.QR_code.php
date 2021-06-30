<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_block_qr_code')) {
    /**
     * nv_block_qr_code_config()
     *
     * @param string $module
     * @param array  $data_block
     * @param array  $lang_block
     * @return string
     */
    function nv_block_qr_code_config($module, $data_block, $lang_block)
    {
        global $selectthemes;

        // Find language file
        if (file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/language/' . NV_LANG_INTERFACE . '.php')) {
            include NV_ROOTDIR . '/themes/' . $selectthemes . '/language/' . NV_LANG_INTERFACE . '.php';
        }

        $array_levels = [
            'L' => 'Low',
            'M' => 'Medium',
            'Q' => 'Quartile',
            'H' => 'High'
        ];

        $html = '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . (empty($lang_block['qr_level']) ? 'qr_level' : $lang_block['qr_level']) . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '		<select class="form-control" name="config_level">';

        foreach ($array_levels as $level => $lev_val) {
            $html .= '		<option value="' . $level . '"' . ($level == $data_block['level'] ? ' selected="selected"' : '') . '>' . $lev_val . '</option>';
        }

        $html .= '		</select>';
        $html .= '	</div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . (empty($lang_block['qr_size']) ? 'qr_size' : $lang_block['qr_size']) . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '		<input type="text" class="form-control" name="size" value="' . $data_block['size'] . '"/>';
        $html .= '	</div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . (empty($lang_block['qr_margin']) ? 'qr_margin' : $lang_block['qr_margin']) . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '		<input type="text" class="form-control" name="margin" value="' . $data_block['margin'] . '"/>';
        $html .= '	</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_block_qr_code_config_submit()
     *
     * @param string $module
     * @param array  $lang_block
     * @return array
     */
    function nv_block_qr_code_config_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config']['level'] = $nv_Request->get_title('config_level', 'post');
        $return['config']['size'] = $nv_Request->get_int('size', 'post', 150);
        $return['config']['margin'] = $nv_Request->get_int('margin', 'post', 5);

        ($return['config']['size'] < 150 or $return['config']['size'] > 300) && $return['config']['size'] = 150;
        ($return['config']['margin'] < 0 or $return['config']['margin'] > 10) && $return['config']['margin'] = 5;

        return $return;
    }

    /**
     * nv_block_qr_code()
     *
     * @param array $block_config
     * @return string
     */
    function nv_block_qr_code($block_config)
    {
        global $page_title, $global_config, $page_url, $module_name, $home, $op, $lang_global;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.QR_code.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.QR_code.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('global.QR_code.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('LANG', $lang_global);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

        if (empty($page_url)) {
            if ($home) {
                $current_page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;
            } else {
                $current_page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
                if ($op != 'main') {
                    $current_page_url .= '&amp;' . NV_OP_VARIABLE . '=' . $op;
                }
            }
        } else {
            $current_page_url = $page_url;
        }

        $block_config['selfurl'] = NV_MAIN_DOMAIN . nv_url_rewrite($current_page_url, true);
        $block_config['title'] = 'QR-Code: ' . str_replace('"', '&quot;', ($page_title ? $page_title : $global_config['site_name']));
        $block_config['width'] = $block_config['height'] = (int) $block_config['size'] + (2 * (int) $block_config['margin']);
        $xtpl->assign('QRCODE', $block_config);

        $xtpl->parse('main');

        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_qr_code($block_config);
}
