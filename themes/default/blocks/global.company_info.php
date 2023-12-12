<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_company_info')) {
    /**
     * nv_company_info_config()
     *
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_company_info_config($module, $data_block)
    {
        global $nv_Lang;

        $html = '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getGlobal('company_name') . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_name" value="' . $data_block['company_name'] . '"></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getGlobal('company_sortname') . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_sortname" value="' . $data_block['company_sortname'] . '"></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getGlobal('company_regcode') . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_regcode" value="' . $data_block['company_regcode'] . '"></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getGlobal('company_regplace') . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_regplace" value="' . $data_block['company_regplace'] . '"></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getGlobal('company_licensenumber') . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_licensenumber" value="' . $data_block['company_licensenumber'] . '"></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getGlobal('company_responsibility') . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_responsibility" value="' . $data_block['company_responsibility'] . '"></div>';
        $html .= '</div>';
        $html .= '<div class="row">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getGlobal('company_address') . ':</label>';
        $html .= '<div class="col-sm-18">';
        $html .= '<div class="form-group">';
        $html .= '<div class="col-xs-16">';
        $html .= '<input type="text" class="form-control" name="config_company_address" id="config_company_address" value="' . $data_block['company_address'] . '">';
        $html .= '</div>';
        $html .= '<div class="col-xs-8">';
        $html .= '<select name="config_company_showmap" id="config_company_mapshow" class="form-control">
                    <option value="0"' . (empty($data_block['company_showmap']) ? ' selected="selected"' : '') . '>' . $nv_Lang->getModule('cominfo_map_no') . '</option>
                    <option value="1"' . (!empty($data_block['company_showmap']) ? ' selected="selected"' : '') . '>' . $nv_Lang->getModule('cominfo_map_yes') . '</option>
                  </select>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div id="config_company_maparea"' . (!empty($data_block['company_showmap']) ? '' : ' class="hidden"') . '>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getModule('cominfo_mapurl') . ':</label>';
        $html .= '<div class="col-sm-18">';
        $html .= '<input type="text" class="form-control" name="config_company_mapurl" id="config_company_mapurl" value="' . $data_block['company_mapurl'] . '">';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getGlobal('company_phone') . ':</label>';
        $html .= '<div class="col-sm-18">
                    <input type="text" class="form-control margin-bottom" name="config_company_phone" value="' . $data_block['company_phone'] . '">
                    <button class="btn btn-default btn-xs" onclick="modalShow(\'' . $nv_Lang->getGlobal('phone_note_title') . '\',\'' . $nv_Lang->getGlobal('phone_note_content') . '\');return!1;">' . $nv_Lang->getGlobal('phone_note_title') . '</button>
                  </div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getGlobal('company_fax') . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_fax" value="' . $data_block['company_fax'] . '"></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getGlobal('company_email') . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_email" value="' . $data_block['company_email'] . '"><span>' . $nv_Lang->getGlobal('multi_note') . '</span></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getGlobal('company_website') . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_company_website" value="' . $data_block['company_website'] . '"><span>' . $nv_Lang->getGlobal('multi_note') . '</span></div>';
        $html .= '</div>';
        $html .= '<tr class="hide">';
        $html .= '<div class="col-sm-18" colspan="2"><script type="text/javascript">
        $(document).ready(function() {
            $("#config_company_mapshow").on("change", function() {
                if ($(this).val() == "1") {
                    $("#config_company_maparea").removeClass("hidden");
                } else {
                    $("#config_company_maparea").addClass("hidden");
                }
            });
        });
        </script></div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_company_info_submit()
     *
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
     * nv_company_info()
     *
     * @param array $block_config
     * @return string
     */
    function nv_company_info($block_config)
    {
        global $global_config, $nv_Lang;

        $company_regcode = '';
        if (!empty($block_config['company_regcode'])) {
            $company_regcode .= $nv_Lang->getGlobal('company_regcode2') . ': ' . $block_config['company_regcode'];
            if (!empty($block_config['company_regplace'])) {
                $company_regcode .= ', ' . $nv_Lang->getGlobal('company_regplace') . ' ' . $block_config['company_regplace'];
            }
        }
        if (!empty($block_config['company_licensenumber'])) {
            $company_regcode .= (!empty($company_regcode) ? '<br />' : '') . $nv_Lang->getGlobal('company_licensenumber') . ': ' . $block_config['company_licensenumber'];
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

        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
        $stpl->assign('LANG', $nv_Lang);
        $stpl->assign('SITE_LOGO', NV_MY_DOMAIN . NV_BASE_SITEURL . $global_config['site_logo']);
        $stpl->assign('DATA', $block_config);
        return $stpl->fetch('global.company_info.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_company_info($block_config);
}
