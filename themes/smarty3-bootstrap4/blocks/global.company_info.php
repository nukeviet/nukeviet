
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

if (!nv_function_exists('nv_block_company_info')) {
    function nv_company_info_config($module, $data_block, $lang_block)

    {
        global $selectthemes, $nv_Lang;

        $html = '';
        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $lang_block['company_address'] . ':</label>';
        $html .= '  <div class="col-sm-18">
                    <input type="text" name="company_address" class="form-control " value="' . $data_block['company_address'] . '"/></div>';
        $html .= '  </div>';

        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $lang_block['company_headquarters'] . ':</label>';
        $html .= '  <div class="col-sm-18">
                    <input type="text" name="company_headquarters" class="form-control " value="' . $data_block['company_headquarters'] . '"/></div>';
        $html .= '  </div>';

        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $lang_block['company_email'] . ':</label>';
        $html .= '  <div class="col-sm-18">
                    <input type="text" name="company_email" class="form-control " value="' . $data_block['company_email'] . '"/></div>';
        $html .= '  </div>';

        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $lang_block['company_cellphonenumber'] . ':</label>';
        $html .= '  <div class="col-sm-18">
                    <input type="text" name="company_cellphonenumber" class="form-control " value="' . $data_block['company_cellphonenumber'] . '"/></div>';
        $html .= '  </div>';

        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $lang_block['company_deskphonenumber'] . ':</label>';
        $html .= '  <div class="col-sm-18">
                    <input type="text" name="company_deskphonenumber" class="form-control " value="' . $data_block['company_deskphonenumber'] . '"/></div>';
        $html .= '  </div>';






        return $html;
    }

    function nv_company_info_submit()
    {
        global $nv_Request;

        $return = array();
        $return['error'] = array();
        $return['config']['company_address'] = $nv_Request->get_title('company_address', 'post');
        $return['config']['company_headquarters'] = $nv_Request->get_title('company_headquarters', 'post');
        $return['config']['company_email'] = $nv_Request->get_title('company_email', 'post');
        $return['config']['company_cellphonenumber'] = $nv_Request->get_title('company_cellphonenumber', 'post');
        $return['config']['company_deskphonenumber'] = $nv_Request->get_title('company_deskphonenumber', 'post');


        return $return;
    }


    /**
     * nv_block_company_info()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_company_info($block_config)
    {
        global $global_config, $lang_global;


        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.company_info.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.company_info.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $tpl = new \NukeViet\Template\NvSmarty();
        $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $tpl->assign('NV_BASE_TEMPLATE', NV_BASE_SITEURL . 'themes/' . $block_theme);
        $tpl->assign("row",$block_config);
        $site_description = $global_config['site_description'];
        $size = @getimagesize(NV_ROOTDIR . '/' . $global_config['site_logo']);
        $logo = preg_replace('/\.[a-z]+$/i', '.svg', $global_config['site_logo']);
        if (!file_exists(NV_ROOTDIR . '/' . $logo)) {
            $logo = $global_config['site_logo'];
        }
        $_logo = array(
            'src' => NV_BASE_SITEURL . $logo,
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA,
            'width' => $size[0],
            'height' => $size[1]
        );
        $tpl->assign('des',$site_description);
        $tpl->assign('logo', $_logo);
        return $tpl->fetch('global.company_info.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_company_info($block_config);
}