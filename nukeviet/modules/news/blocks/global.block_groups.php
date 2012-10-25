<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 - 2011 VINADES.,JSC. All rights reserved
 * @Createdate Sat, 10 Dec 2011 06:46:54 GMT
 */

if (!defined('NV_MAINFILE')) die('Stop!!!');

if (!nv_function_exists('nv_block_news_groups'))
{
    function nv_block_config_news_groups($module, $data_block, $lang_block)
    {
        global $site_mods;
        $html = "";
        $html .= "<tr>";
        $html .= "<td>" . $lang_block['blockid'] . "</td>";
        $html .= "<td><select name=\"config_blockid\">\n";
        $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $site_mods[$module]['module_data'] . "_block_cat` ORDER BY `weight` ASC";
        $list = nv_db_cache($sql, '', $module);
        foreach ($list as $l)
        {
            $html .= "<option value=\"" . $l['bid'] . "\" " . (($data_block['blockid'] == $l['bid']) ? " selected=\"selected\"" : "") . ">" . $l['title'] . "</option>\n";
        }
        $html .= "</select></td>\n";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td>" . $lang_block['numrow'] . "</td>";
        $html .= "<td><input type=\"text\" name=\"config_numrow\" size=\"5\" value=\"" . $data_block['numrow'] . "\"/></td>";
        $html .= "</tr>";
        return $html;
    }

    function nv_block_config_news_groups_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['blockid'] = $nv_Request->get_int('config_blockid', 'post', 0);
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        return $return;
    }

    function nv_block_news_groups($block_config)
    {
        global $module_array_cat, $module_info, $site_mods;
        $module = $block_config['module'];

        $sql = "SELECT t1.id, t1.catid, t1.title, t1.alias, t1.homeimgfile, t1.homeimgthumb,t1.hometext,t1.publtime FROM `" . NV_PREFIXLANG . "_" . $site_mods[$module]['module_data'] . "_rows` as t1 INNER JOIN `" . NV_PREFIXLANG . "_" . $site_mods[$module]['module_data'] . "_block` AS t2 ON t1.id = t2.id WHERE t2.bid= " . $block_config['blockid'] . " AND t1.status= 1 ORDER BY t2.weight ASC LIMIT 0 , " . $block_config['numrow'];
        $list = nv_db_cache($sql, '', $module);

        $i = 1;
        if (!empty($list))
        {
            if (file_exists(NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/news/block_blocknews.tpl"))
            {
                $block_theme = $module_info['template'];
            }
            else
            {
                $block_theme = "default";
            }
            $xtpl = new XTemplate("block_blocknews.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/news");
            foreach ($list as $l)
            {
                $l['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $module_array_cat[$l['catid']]['alias'] . "/" . $l['alias'] . "-" . $l['id'];
                ;
                $l['thumb'] = "";
                if (!empty($l['homeimgthumb']))
                {
                    $array_img = array();
                    $array_img = explode("|", $l['homeimgthumb']);
                    if ($array_img[0] != "" and file_exists(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module . '/' . $array_img[0]))
                    {
                        $imgurl = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module . '/' . $array_img[0];
                        $l['thumb'] = $imgurl;
                    }
                }
                elseif (nv_is_url($l['homeimgfile']))
                {
                    $l['thumb'] = $l['homeimgfile'];
                }
				
                $xtpl->assign('ROW', $l);
                if (!empty($l['thumb']))$xtpl->parse('main.loop.img');
                $xtpl->assign('bg', (++$i % 2) ? "bg" : "");
                $xtpl->parse('main.loop');
            }
			
            $xtpl->parse('main');
            return $xtpl->text('main');
        }
    }

}
if (defined('NV_SYSTEM'))
{
    global $site_mods, $module_name, $global_array_cat, $module_array_cat;
    $module = $block_config['module'];
    if (isset($site_mods[$module]))
    {
        if ($module == $module_name)
        {
            $module_array_cat = $global_array_cat;
            unset($module_array_cat[0]);
        }
        else
        {
            $module_array_cat = array();
            $sql = "SELECT catid, parentid, title, alias, viewcat, subcatid, numlinks, description, inhome, keywords, who_view, groups_view FROM `" . NV_PREFIXLANG . "_" . $site_mods[$module]['module_data'] . "_cat` ORDER BY `order` ASC";
            $list = nv_db_cache($sql, 'catid', $module);
            foreach ($list as $l)
            {
                $module_array_cat[$l['catid']] = $l;
                $module_array_cat[$l['catid']]['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $l['alias'];
            }
        }
        $content = nv_block_news_groups($block_config);
    }
}
?>