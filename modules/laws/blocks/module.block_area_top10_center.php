<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 06, 2011, 06:31:13 AM
 */

if (!defined('NV_IS_MOD_LAWS')) die('Stop!!!');

if (!function_exists('nv_law_block_10area')) {

    function nv_law_block_10area()
    {
        global $lang_module, $module_info, $module_file, $global_config, $nv_laws_listarea, $module_name, $db, $module_data;
        
        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/block_top10_area.tpl')) {
            $block_theme = $global_config['module_theme'];
        } else {
            $block_theme = 'default';
        }
        
        $xtpl = new XTemplate("block_top10_area.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module_file);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('TEMPLATE', $block_theme);
        $xtpl->assign('MODULE_FILE', $module_file);
        
        $title_length = 34;
        
        unset($nv_laws_listarea[0]);
        
        $i = 1;
        foreach ($nv_laws_listarea as $cat) {
            if ($cat['parentid'] == 0) {
                $in = "";
                if (empty($cat['subcats'])) {
                    $in = " t2.area_id=" . $cat['id'];
                } else {
                    $in = $cat['subcats'];
                    $in = " t2.area_id IN(" . implode(",", $in) . ")";
                }
                
                $sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_row t1 INNER JOIN " . NV_PREFIXLANG . "_" . $module_data . "_row_area t2 ON t1.id=t2.row_id WHERE" . $in . " AND status=1";
                
                $result = $db->query($sql);
                $num = $result->fetchColumn();
                
                $cat['name'] = nv_clean60($cat['title'], $title_length);
                $cat['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=area/" . $cat['alias'];
                
                $xtpl->assign('NUM_LAW', $num);
                $xtpl->assign('CAT', $cat);
                $xtpl->parse('main.loop');
                
                if ($i >= 10) break;
                
                $i++;
            }
        }
        
        $sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_row WHERE status=1";
        $result = $db->query($sql);
        $num = $result->fetchColumn();
        
        $xtpl->assign('INFO_NUM', sprintf($lang_module['info_num'], $num));
        
        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_law_block_10area();
}