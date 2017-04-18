<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (! defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

if (! function_exists('nv_product_center')) {
    /**
     * nv_product_center()
     *
     * @return
     */
    function nv_product_center($block_config)
    {
        global $nv_Cache, $module_name, $lang_module, $module_info, $module_file, $global_array_shops_cat, $db, $module_data, $db_config, $pro_config, $global_config, $site_mods;

        $module = $block_config['module'];

        $num_view = 5;
        $num = 30;
        $array = array();

        $xtpl = new XTemplate('block.product_center.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('THEME_TEM', NV_BASE_SITEURL . 'themes/' . $module_info['template']);
        $xtpl->assign('WIDTH', $pro_config['homewidth']);
        $xtpl->assign('NUMVIEW', $num_view);

        $cache_file = NV_LANG_DATA . '_block_module_product_center_' . NV_CACHE_PREFIX . '.cache';
        if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
            $array = unserialize($cache);
        } else {
            $db->sqlreset()
                ->select('bid')
                ->from($db_config['prefix'] . '_' . $module_data . '_block_cat')
                ->order('weight ASC')
                ->limit(1);

            $result = $db->query($db->sql());
            $bid = $result->fetchColumn();

            if ($pro_config['sortdefault'] == 0) {
                $orderby = 't1.id DESC';
            } elseif ($pro_config['sortdefault'] == 1) {
                $orderby = 't1.product_price ASC, t1.id DESC';
            } else {
                $orderby = 't1.product_price DESC, t1.id DESC';
            }
            
            $db->sqlreset()
                ->select('t1.id, t1.listcatid, t1.' . NV_LANG_DATA . '_title AS title, t1.' . NV_LANG_DATA . '_alias AS alias, t1.homeimgfile, t1.homeimgthumb , t1.homeimgalt, t1.showprice, t1.discount_id')
                ->from($db_config['prefix'] . '_' . $module_data . '_rows t1')
                ->join('INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_block t2 ON t1.id = t2.id')
                ->where('t2.bid= ' . $bid . ' AND t1.status =1')
                ->order($orderby)
                ->limit($num);

            $array = $nv_Cache->db($db->sql(), 'id', $module_name);
            $cache = serialize($array);
            $nv_Cache->setItem($module_name, $cache_file, $cache);
        }

        foreach ($array as $row) {
            $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_shops_cat[$row['listcatid']]['alias'] . '/' . $row['alias'] . $global_config['rewrite_exturl'];

            if ($row['homeimgthumb'] == 1) {
                //image thumb

                $src_img = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['homeimgfile'];
            } elseif ($row['homeimgthumb'] == 2) {
                //image file

                $src_img = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['homeimgfile'];
            } elseif ($row['homeimgthumb'] == 3) {
                //image url

                $src_img = $row['homeimgfile'];
            } else {
                //no image

                $src_img = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/shops/no-image.jpg';
            }

            $xtpl->assign('LINK', $link);
            $xtpl->assign('TITLE', $row['title']);
            $xtpl->assign('TITLE0', nv_clean60($row['title'], 30));
            $xtpl->assign('SRC_IMG', $src_img);

            if ($pro_config['active_price'] == '1') {
                if ($row['showprice'] == '1') {
                    $price = nv_get_price($row['id'], $pro_config['money_unit']);
                    $xtpl->assign('PRICE', $price);
                    if ($row['discount_id'] and $price['discount_percent'] > 0) {
                        $xtpl->parse('main.items.price.discounts');
                    } else {
                        $xtpl->parse('main.items.price.no_discounts');
                    }
                    $xtpl->parse('main.items.price');
                } else {
                    $xtpl->parse('main.items.contact');
                }
            }

            $xtpl->parse('main.items');
        }

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

$content = nv_product_center($block_config);
