
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

if (!nv_function_exists('nv_block_menu_footer')) {


    function nv_block_config_menu($module, $data_block, $nv_Lang)
    {
        global $nv_Cache;
        //print_r($data_block); die("ok");
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $nv_Lang->getBlock('title_menu') . ':</label>';
        $html .= '  <div class="col-sm-18">
                    <input type="text" name="title_menu" class="form-control w100" size="5" value="' . $data_block['title_menu'] . '"/></div>';
        $html .= '  </div>';
        $html .= '</div>';
        $html .= "<div class=\"form-group\">";
        $html .= "	<label class=\"control-label col-sm-6\">" . $nv_Lang->getBlock('menu') . ":</label>";
        $html .= "	<div class=\"col-sm-9\"><select name=\"menuid\" class=\"form-control\">\n";

        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_menu ORDER BY id DESC";
        // Module menu của hệ thống không ảo hóa, do đó chỉ định cache trực tiếp vào module tránh lỗi khi gọi file từ giao diện
        $list = $nv_Cache->db($sql, 'id', 'menu');
        foreach ($list as $l) {
            $sel = ($data_block['menuid'] == $l['id']) ? ' selected' : '';
            $html .= "<option value=\"" . $l['id'] . "\" " . $sel . ">" . $l['title'] . "</option>\n";
        }

        $html .= "	</select></div>\n";
        $html .= "</div>";


        return $html;
    }


    function nv_block_config_menu_submit($module, $nv_Lang)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['title_menu'] = $nv_Request->get_title('title_menu', 'post');
        $return['config']['menuid'] = $nv_Request->get_int('menuid', 'post', 0);

        return $return;
    }



    /**
     * nv_block_menu_footer()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_menu_footer($block_config)
    {
        global $global_config, $nv_Lang, $db;

        $sql = "SELECT * FROM nv4_vi_menu_rows WHERE mid =". $block_config['menuid'] ." AND parentid = 0";
        $stmt = $db->query($sql);
        $row = $stmt->fetchAll();



        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.menu_footer.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.menu_footer.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $tpl = new \NukeViet\Template\Smarty();
        $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $tpl->assign('NV_BASE_TEMPLATE', NV_BASE_SITEURL . 'themes/' . $block_theme);
        $tpl->assign('title', $block_config['title_menu']);
        $tpl->assign('row',$row);

        return $tpl->fetch('global.menu_footer.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_menu_footer($block_config);
}