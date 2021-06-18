<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 04 May 2014 12:41:32 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_block_qr_code')) {
    /**
     * nv_block_qr_code_config()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_qr_code_config($module, $data_block, $lang_block)
    {
        global $selectthemes;

        // Find language file
        if (file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/language/' . NV_LANG_INTERFACE . '.php')) {
            include NV_ROOTDIR . '/themes/' . $selectthemes . '/language/' . NV_LANG_INTERFACE . '.php';
        }

        $array_levels = array(
            'L',
            'M',
            'Q',
            'H'
        );

        $html = '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . (empty($lang_block['qr_level']) ? 'qr_level' : $lang_block['qr_level']) . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '		<select class="form-control" name="config_level">';

        foreach ($array_levels as $level) {
            $html .= '		<option value="' . $level . '"' . ($level == $data_block['level'] ? ' selected="selected"' : '') . '>' . $level . '</option>';
        }

        $html .= '		</select>';
        $html .= '	</div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . (empty($lang_block['qr_pixel_per_point']) ? 'qr_pixel_per_point' : $lang_block['qr_pixel_per_point']) . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '		<select class="form-control" name="config_pixel_per_point">';

        for ($i = 1; $i <= 12; $i++) {
            $html .= '		<option value="' . $i . '"' . ($i == $data_block['pixel_per_point'] ? ' selected="selected"' : '') . '>' . $i . '</option>';
        }

        $html .= '		</select>';
        $html .= '	</div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . (empty($lang_block['qr_outer_frame']) ? 'qr_outer_frame' : $lang_block['qr_outer_frame']) . ':</label>';
        $html .= '	<div class="col-sm-9">';
        $html .= '		<select class="form-control" name="config_outer_frame">';

        for ($i = 1; $i <= 5; $i++) {
            $html .= '		<option value="' . $i . '"' . ($i == $data_block['outer_frame'] ? ' selected="selected"' : '') . '>' . $i . '</option>';
        }

        $html .= '		</select>';
        $html .= '	</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_block_qr_code_config_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_qr_code_config_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config']['level'] = $nv_Request->get_title('config_level', 'post');
        $return['config']['pixel_per_point'] = $nv_Request->get_int('config_pixel_per_point', 'post', 4);
        $return['config']['outer_frame'] = $nv_Request->get_int('config_outer_frame', 'post', 1);
        return $return;
    }

    /**
     * nv_block_qr_code()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_qr_code($block_config)
    {
        global $page_title, $global_config, $client_info, $lang_global;

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

        $block_config['selfurl'] = $client_info['selfurl'];
        $block_config['title'] = "QR-Code: " . str_replace('"', "&quot;", ($page_title ? $page_title : $global_config['site_name']));
        $xtpl->assign('QRCODE', $block_config);

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_qr_code($block_config);
}
