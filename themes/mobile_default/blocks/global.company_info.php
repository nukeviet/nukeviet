<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jan 17, 2011 11:34:27 AM
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_company_info')) {
    /**
     * nv_company_info_config()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_company_info_config($module, $data_block, $lang_block)
    {
        global $lang_global, $selectthemes;

        // Find language file
        if (file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/language/' . NV_LANG_INTERFACE . '.php')) {
            include NV_ROOTDIR . '/themes/' . $selectthemes . '/language/' . NV_LANG_INTERFACE . '.php';
        }

        $html = '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_global['company_name'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_name" value="' . $data_block['company_name'] . '"></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_global['company_sortname'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_sortname" value="' . $data_block['company_sortname'] . '"></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_global['company_regcode'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_regcode" value="' . $data_block['company_regcode'] . '"></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_global['company_regplace'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_regplace" value="' . $data_block['company_regplace'] . '"></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_global['company_licensenumber'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_licensenumber" value="' . $data_block['company_licensenumber'] . '"></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_global['company_responsibility'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_responsibility" value="' . $data_block['company_responsibility'] . '"></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_global['company_address'] . ':</label>';
        $html .= '<div class="col-sm-18">';
        $html .= '<div class="row">';
        $html .= '<div class="col-xs-16">';
        $html .= '<input type="text" class="form-control" name="config_company_address" id="config_company_address" value="' . $data_block['company_address'] . '">';
        $html .= '</div>';
        $html .= '<div class="col-xs-8">';
        $html .= '<select name="config_company_showmap" id="config_company_mapshow" class="form-control" onchange="return controlMap(true);">
					<option value="0"' . (empty($data_block['company_showmap']) ? ' selected="selected"' : '') . '>' . $lang_block['cominfo_map_no'] . '</option>
					<option value="1"' . (!empty($data_block['company_showmap']) ? ' selected="selected"' : '') . '>' . $lang_block['cominfo_map_yes'] . '</option>
				  </select>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div id="config_company_maparea">';
        $html .= '<div id="config_company_mapcanvas" style="margin:10px 0;"></div>';
        $html .= '<div class="row form-group">';
        $html .= '<div class="col-xs-6">';
        $html .= '<div class="input-group">
				  	<span class="input-group-addon">L</span>
				  	<input type="text" class="form-control" name="config_company_mapcenterlat" id="config_company_mapcenterlat" value="' . $data_block['company_mapcenterlat'] . '" readonly="readonly">
				  </div>';
        $html .= '</div>';
        $html .= '<div class="col-xs-6">';
        $html .= '<div class="input-group">
				  	<span class="input-group-addon">N</span>
				  	<input type="text" class="form-control" name="config_company_mapcenterlng" id="config_company_mapcenterlng" value="' . $data_block['company_mapcenterlng'] . '" readonly="readonly">
				  </div>';
        $html .= '</div>';
        $html .= '<div class="col-xs-6">';
        $html .= '<div class="input-group">
				  	<span class="input-group-addon">L</span>
				  	<input type="text" class="form-control" name="config_company_maplat" id="config_company_maplat" value="' . $data_block['company_maplat'] . '" readonly="readonly">
				  </div>';
        $html .= '</div>';
        $html .= '<div class="col-xs-6">';
        $html .= '<div class="input-group">
				  	<span class="input-group-addon">N</span>
				  	<input type="text" class="form-control" name="config_company_maplng" id="config_company_maplng" value="' . $data_block['company_maplng'] . '" readonly="readonly">
				  </div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="row m-bottom">';
        $html .= '<div class="col-xs-12">';
        $html .= '<div class="input-group">
				  	<span class="input-group-addon">Z</span>
				  	<input type="text" class="form-control" name="config_company_mapzoom" id="config_company_mapzoom" value="' . $data_block['company_mapzoom'] . '" readonly="readonly">
				  </div>';
        $html .= '</div>';
        $html .= '<div class="col-xs-12">';
        $html .= '<button class="btn btn-default" onclick="modalShow(\'' . $lang_block['cominfo_map_guide_title'] . '\',\'' . $lang_block['cominfo_map_guide_content'] . '\');return!1;">' . $lang_block['cominfo_map_guide_title'] . '</button>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_global['company_phone'] . ':</label>';
        $html .= '<div class="col-sm-18">
					<div class="margin-bottom"><input type="text" class="form-control" name="config_company_phone" value="' . $data_block['company_phone'] . '"></div>
					<button class="btn btn-default btn-xs" onclick="modalShow(\'' . $lang_global['phone_note_title'] . '\',\'' . $lang_global['phone_note_content'] . '\');return!1;">' . $lang_global['phone_note_title'] . '</button>
				  </div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_global['company_fax'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_fax" value="' . $data_block['company_fax'] . '"></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_global['company_email'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_email" value="' . $data_block['company_email'] . '"><span>' . $lang_global['multi_note'] . '</span></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_global['company_website'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_website" value="' . $data_block['company_website'] . '"><span>' . $lang_global['multi_note'] . '</span></div>';
        $html .= '<script type="text/javascript">$.getScript("' . NV_BASE_SITEURL . 'themes/default/js/block.global.company_info.js");</script>';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_company_info_submit()
     *
     * @return
     */
    function nv_company_info_submit()
    {
        global $nv_Request;

        $return = array();
        $return['error'] = array();
        $return['config']['company_name'] = $nv_Request->get_title('config_company_name', 'post');
        $return['config']['company_sortname'] = $nv_Request->get_title('config_company_sortname', 'post');
        $return['config']['company_regcode'] = $nv_Request->get_title('config_company_regcode', 'post');
        $return['config']['company_regplace'] = $nv_Request->get_title('config_company_regplace', 'post');
        $return['config']['company_licensenumber'] = $nv_Request->get_title('config_company_licensenumber', 'post');
        $return['config']['company_responsibility'] = $nv_Request->get_title('config_company_responsibility', 'post');
        $return['config']['company_address'] = $nv_Request->get_title('config_company_address', 'post');
        $return['config']['company_showmap'] = $nv_Request->get_int('config_company_showmap', 'post', 0) == 0 ? 0 : 1;
        $return['config']['company_mapcenterlat'] = $nv_Request->get_float('config_company_mapcenterlat', 'post', 20.984516000000013);
        $return['config']['company_mapcenterlng'] = $nv_Request->get_float('config_company_mapcenterlng', 'post', 105.79547500000001);
        $return['config']['company_maplat'] = $nv_Request->get_float('config_company_maplat', 'post', 20.984516000000013);
        $return['config']['company_maplng'] = $nv_Request->get_float('config_company_maplng', 'post', 105.79547500000001);
        $return['config']['company_mapzoom'] = $nv_Request->get_int('config_company_mapzoom', 'post', 17);
        $return['config']['company_phone'] = $nv_Request->get_title('config_company_phone', 'post');
        $return['config']['company_fax'] = $nv_Request->get_title('config_company_fax', 'post');
        $return['config']['company_email'] = $nv_Request->get_title('config_company_email', 'post');
        $return['config']['company_website'] = $nv_Request->get_title('config_company_website', 'post');

        return $return;
    }

    /**
     * nv_menu_theme_default_footer()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_company_info($block_config)
    {
        global $global_config, $lang_global;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.company_info.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.company_info.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $block_config['company_mapapikey'] = $global_config['googleMapsAPI'];

        $xtpl = new XTemplate('global.company_info.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('LANG', $lang_global);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('DATA', $block_config);

        if (!empty($block_config['company_name'])) {
            if (!empty($block_config['company_sortname'])) {
                $xtpl->parse('main.company_name.company_sortname');
            }
            $xtpl->parse('main.company_name');
        }

        $key = array();
        $i = 0;
        if (!empty($block_config['company_regcode'])) {
            $key[$i] = $lang_global['company_regcode2'] . ': ' . $block_config['company_regcode'];
            if (!empty($block_config['company_regplace'])) {
                $key[$i] .= ', ' . $lang_global['company_regplace'] . ' ' . $block_config['company_regplace'];
            }
            ++$i;
        }
        if (!empty($block_config['company_licensenumber'])) {
            $key[$i] = $lang_global['company_licensenumber'] . ': ' . $block_config['company_licensenumber'];
            ++$i;
        }

        if ($i) {
            $key = implode('.<br>', $key);
            $xtpl->assign('LICENSE', $key);
            $xtpl->parse('main.company_regcode');
        }

        if (!empty($block_config['company_responsibility'])) {
            $xtpl->parse('main.company_responsibility');
        }

        if (!empty($block_config['company_address'])) {
            if (!empty($block_config['company_showmap'])) {
                $xtpl->parse('main.company_map_modal');
                $xtpl->parse('main.company_address.company_map_triger');
            }

            $xtpl->parse('main.company_address');
        }

        if (!empty($block_config['company_phone'])) {
            $nums = array_map('trim', explode('|', nv_unhtmlspecialchars($block_config['company_phone'])));
            foreach ($nums as $k => $num) {
                unset($m);
                if (preg_match("/^(.*)\s*\[([0-9\+\.\,\;\*\#]+)\]$/", $num, $m)) {
                    $xtpl->assign('PHONE', array('number' => nv_htmlspecialchars($m[1]), 'href' => $m[2]));
                    $xtpl->parse('main.company_phone.item.href');
                    $xtpl->parse('main.company_phone.item.href2');
                } else {
                    $num = preg_replace("/\[[^\]]*\]/", "", $num);
                    $xtpl->assign('PHONE', array('number' => nv_htmlspecialchars($num)));
                }
                if ($k) {
                    $xtpl->parse('main.company_phone.item.comma');
                }
                $xtpl->parse('main.company_phone.item');
            }

            $xtpl->parse('main.company_phone');
        }
        if (!empty($block_config['company_fax'])) {
            $xtpl->parse('main.company_fax');
        }
        if (!empty($block_config['company_email'])) {
            $emails = array_map('trim', explode(',', $block_config['company_email']));
            foreach ($emails as $k => $email) {
                $xtpl->assign('EMAIL', $email);
                if ($k) {
                    $xtpl->parse('main.company_email.item.comma');
                }
                $xtpl->parse('main.company_email.item');
            }
            $xtpl->parse('main.company_email');
        }
        if (!empty($block_config['company_website'])) {
            $webs = array_map('trim', explode(',', $block_config['company_website']));
            foreach ($webs as $k => $web) {
                if (!preg_match("/^https?\:\/\//", $web)) {
                    $web = 'http://' . $web;
                }
                $xtpl->assign('WEBSITE', $web);
                if ($k) {
                    $xtpl->parse('main.company_website.item.comma');
                }
                $xtpl->parse('main.company_website.item');
            }
            $xtpl->parse('main.company_website');
        }
        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_company_info($block_config);
}
