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

if (! function_exists('nv_others_product')) {
    /**
     * nv_others_product()
     *
     * @return
     */
    function nv_others_product($block_config)
    {
        global $op, $global_config, $pro_config;

        $module = $block_config['module'];

        if ($op == 'detail') {
            global $site_mods, $module_name, $lang_module, $module_info, $module_file, $global_array_shops_cat, $db, $module_data, $db_config, $id, $catid, $pro_config, $global_config;

            $xtpl = new XTemplate('block.others_product.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
            $xtpl->assign('LANG', $lang_module);
            $xtpl->assign('THEME_TEM', NV_BASE_SITEURL . 'themes/' . $module_info['template']);
            $xtpl->assign('WIDTH', $pro_config['blockwidth']);

            $db->sqlreset()
                ->select('id, listcatid, ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_alias ,addtime, homeimgfile, homeimgthumb, product_price, money_unit, discount_id, showprice')
                ->from($db_config['prefix'] . '_' . $module_data . '_rows')
                ->where('status =1 AND listcatid = ' . $catid . ' AND id < ' . $id)
                ->order('id DESC')
                ->limit(20);

            $result = $db->query($db->sql());

            $i = 1;
            while (list($id_i, $listcatid_i, $title_i, $alias_i, $addtime_i, $homeimgfile_i, $homeimgthumb_i, $product_price_i, $money_unit_i, $discount_id_i, $showprice_i) = $result->fetch(3)) {
                if ($homeimgthumb_i == 1) {
                    //image thumb

                    $src_img = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $homeimgfile_i;
                } elseif ($homeimgthumb_i == 2) {
                    //image file

                    $src_img = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $homeimgfile_i;
                } elseif ($homeimgthumb_i == 3) {
                    //image url

                    $src_img = $homeimgfile_i;
                } else {
                    //no image

                    $src_img = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/shops/no-image.jpg';
                }

                $xtpl->assign('link', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_shops_cat[$listcatid_i]['alias'] . '/' . $alias_i . $global_config['rewrite_exturl']);
                $xtpl->assign('title', $title_i);
                $xtpl->assign('src_img', $src_img);
                $xtpl->assign('time', nv_date('d-m-Y h:i:s A', $addtime_i));

                if ($pro_config['active_price'] == '1') {
                    if ($showprice_i == '1') {
                        $price = nv_get_price($id_i, $money_unit_i);
                        $xtpl->assign('PRICE', $price);
                        if ($discount_id_i and $price['discount_percent'] > 0) {
                            $xtpl->parse('main.loop.price.discounts');
                        } else {
                            $xtpl->parse('main.loop.price.no_discounts');
                        }
                        $xtpl->parse('main.loop.price');
                    } else {
                        $xtpl->parse('main.loop.contact');
                    }
                }

                $bg = ($i % 2 == 0) ? 'bg' : '';
                $xtpl->assign('bg', $bg);
                $xtpl->parse('main.loop');
                ++$i;
            }

            $xtpl->parse('main');
            return $xtpl->text('main');
        }
    }
}

$content = nv_others_product($block_config);
