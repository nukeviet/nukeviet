<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');
if (!function_exists('CurrencyConversion'))
{
    function CurrencyConversion($price, $currency_curent, $currency_convert, $block_config)
    {
        global $money_config, $module_config;

        $price_convert = $price;
        $module = $block_config['module'];
        $pro_config = $module_config[$module];

        if ($currency_curent != $currency_convert)
        {
            // Chuyen tien hien tai sang tien goc la lay so hien tai nhan cho ti gia
            if (isset($money_config[$currency_curent]['exchange']))
            {
                $value = doubleval($money_config[$currency_curent]['exchange']);
                $price_convert = $price_convert * $value;
            }
            else
            {
                return "N/A";
            }

            // Neu tien can chuen khong phai l� tien goc tien chuyen tu tien goc sang tien can chuyen (lay tien goc chia cho ti gia)
            if ($currency_convert != $pro_config['money_unit'])
            {
                if (isset($money_config[$currency_convert]['exchange']))
                {
                    $value = doubleval($money_config[$currency_convert]['exchange']);
                    $price_convert = $price_convert / $value;
                }
                else
                {
                    return "N/A";
                }
            }
        }

        $temp = explode(".", $price_convert);
        if (sizeof($temp) == 2)
        {
            $strlen = strlen($temp[1]);
            $strlen = ($price_convert < 1000) ? (($strlen > 8) ? 8 : $strlen) : 2;
            return number_format($price_convert, $strlen, '.', ' ');
        }
        else
        {
            return number_format($price_convert, 0, '.', ' ');
        }
    }

}
if (!function_exists('nv_relates_product'))
{
    function nv_relates_product($block_config)
    {
        global $site_mods, $global_config, $module_config, $module_name, $module_info, $global_array_cat, $db, $db_config, $my_head;
        $module = $block_config['module'];
        $mod_data = $site_mods[$module]['module_data'];
        $mod_file = $site_mods[$module]['module_file'];
        $pro_config = $module_config[$module];
        $array_cat = $global_array_cat;
        if (file_exists(NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $mod_file . "/block.others_product.tpl"))
        {
            $block_theme = $module_info['template'];
        }
        else
        {
            $block_theme = "default";
        }
        if ($module != $module_name)
        {
            $sql = "SELECT catid, parentid, lev," . NV_LANG_DATA . "_title, " . NV_LANG_DATA . "_alias, viewcat, numsubcat, subcatid, numlinks, del_cache_time, " . NV_LANG_DATA . "_description, inhome, " . NV_LANG_DATA . "_keywords, who_view, groups_view FROM `" . $db_config['prefix'] . "_" . $mod_data . "_catalogs` ORDER BY `order` ASC";
            $result = $db->sql_query($sql);
            while (list($catid_i, $parentid_i, $lev_i, $title_i, $alias_i, $viewcat_i, $numsubcat_i, $subcatid_i, $numlinks_i, $del_cache_time_i, $description_i, $inhome_i, $keywords_i, $who_view_i, $groups_view_i) = $db->sql_fetchrow($result))
            {
                $link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i . "";
                $array_cat[$catid_i] = array("catid" => $catid_i, "parentid" => $parentid_i, "title" => $title_i, "alias" => $alias_i, "link" => $link_i, "viewcat" => $viewcat_i, "numsubcat" => $numsubcat_i, "subcatid" => $subcatid_i, "numlinks" => $numlinks_i, "description" => $description_i, "inhome" => $inhome_i, "keywords" => $keywords_i, "who_view" => $who_view_i, "groups_view" => $groups_view_i, 'lev' => $lev_i);
            }
            if (file_exists(NV_ROOTDIR . "/themes/" . $block_theme . "/css/" . $mod_file . ".css"))
            {
                $my_head .= '<link rel="StyleSheet" href="' . NV_BASE_SITEURL . 'themes/' . $block_theme . '/css/' . $mod_file . '.css' . '" type="text/css" />';
            }
        }
        $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=";

        $xtpl = new XTemplate("block.others_product.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $mod_file);
        $sql = "SELECT id,listcatid, " . NV_LANG_DATA . "_title," . NV_LANG_DATA . "_alias,addtime,homeimgthumb,product_price,product_discounts,money_unit,showprice  FROM `" . $db_config['prefix'] . "_" . $mod_data . "_rows` ORDER BY ratingdetail DESC LIMIT 0,10";
        $query = $db->sql_query($sql);
        $i = 1;
        ///////////////////////////////////////////////////////////
        while (list($id_i, $listcatid_i, $title_i, $alias_i, $addtime_i, $homeimgthumb_i, $product_price_i, $product_discounts_i, $money_unit_i, $showprice_i) = $db->sql_fetchrow($query))
        {
            $thumb = explode("|", $homeimgthumb_i);
            if (!empty($thumb[0]) && !nv_is_url($thumb[0]))
            {
                $thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module . "/" . $thumb[0];
            }
            else
            {
                $thumb[0] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $mod_file . "/no-image.jpg";
            }
            $xtpl->assign('link', $link . $array_cat[$listcatid_i]['alias'] . "/" . $alias_i . "-" . $id_i);
            $xtpl->assign('title', $title_i);
            $xtpl->assign('src_img', $thumb[0]);
            $xtpl->assign('time', nv_date('d-m-Y h:i:s A', $addtime_i));
            if ($pro_config['active_price'] == '1' && $showprice_i == '1')
            {
                $product_price = CurrencyConversion($product_price_i, $money_unit_i, $pro_config['money_unit'], $block_config);
                $xtpl->assign('product_price', $product_price);
                $xtpl->assign('money_unit', $pro_config['money_unit']);
                if ($product_discounts_i != 0)
                {
                    $price_product_discounts = $product_price_i - ($product_price_i * ($product_discounts_i / 100));
                    $xtpl->assign('product_discounts', CurrencyConversion($price_product_discounts, $money_unit_i, $pro_config['money_unit'], $block_config));
                    $xtpl->assign('class_money', 'discounts_money');
                    $xtpl->parse('main.loop.discounts');
                }
                else
                {
                    $xtpl->assign('class_money', 'money');
                }
                $xtpl->parse('main.loop.price');
            }
            $bg = ($i % 2 == 0) ? "bg" : "";
            $xtpl->assign("bg", $bg);
            $xtpl->parse('main.loop');
            ++$i;
        }
        ///////////////////////////////////////////////////////////////////////////////////
        $xtpl->parse('main');
        return $xtpl->text('main');
    }

}

if (defined('NV_SYSTEM'))
{
    global $db_config, $site_mods, $global_array_cat, $module_name, $money_config;
    $module = $block_config['module'];
    if ($module != $module_name)
    {
        // lay ty gia ngoai te
        $sql = "SELECT `id` , `code` , `currency` , `exchange`  FROM `" . $db_config['prefix'] . "_" . $site_mods[$module]['module_data'] . "_money_" . NV_LANG_DATA . "`";
        $money_config = nv_db_cache($sql, 'code', $module);
    }

    $content = nv_relates_product($block_config);
}
?>