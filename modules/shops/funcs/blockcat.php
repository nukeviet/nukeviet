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

if (isset($array_op[1])) {
    $alias = trim($array_op[1]);
    
    $page = (isset($array_op[2]) and substr($array_op[2], 0, 5) == 'page-') ? intval(substr($array_op[2], 5)) : 1;

    $stmt = $db->prepare('SELECT bid, ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_alias, image, ' . NV_LANG_DATA . '_description, ' . NV_LANG_DATA . '_keywords FROM ' . $db_config['prefix'] . '_' . $module_data . '_block_cat ORDER BY weight DESC');
        
    $stmt->execute();
    
    list($bid, $page_title, $alias, $image_group, $description, $key_words) = $stmt->fetch(3);

    if ($bid > 0) {
        $base_url_rewrite = $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['blockcat'] . '/' . $alias;
        
        if ($page > 1) {
            $page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
            $base_url_rewrite .= '/page-' . $page;
        }
        
        $base_url_rewrite = nv_url_rewrite($base_url_rewrite, true);
        
        if ($_SERVER['REQUEST_URI'] != $base_url_rewrite and NV_MAIN_DOMAIN . $_SERVER['REQUEST_URI'] != $base_url_rewrite) {
            Header('Location: ' . $base_url_rewrite);
            die();
        }

        $array_mod_title[] = array(
            'id' => 0,
            'title' => $page_title,
            'link' => $base_url
        );

        $item_array = array();
        $end_weight = 0;

        $db->sqlreset()
            ->select('COUNT(*)')
            ->from($db_config['prefix'] . '_' . $module_data . '_rows t1')
            ->join('INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_block t2 ON t1.id = t2.id')
            ->where('t2.bid= ' . $bid . ' AND t1.status= 1');
        
        $num_items = $db->query($db->sql())->fetchColumn();

        $db->select('t1.id, t1.listcatid, t1.publtime, t1.' . NV_LANG_DATA . '_title, t1.' . NV_LANG_DATA . '_alias, t1.' . NV_LANG_DATA . '_hometext, t1.homeimgalt, t1.homeimgfile, t1.homeimgthumb, t1.product_code, t1.product_number, t1.product_price, t1.money_unit, t1.discount_id, t1.showprice, t1.' . NV_LANG_DATA . '_gift_content, t1.gift_from, t1.gift_to')
            ->order('t2.weight ASC')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $result = $db->query($db->sql());
        
        while ($item = $result->fetch()) {
            if ($item['homeimgthumb'] == 1) {
                //image thumb

                $item['src'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
            } elseif ($item['homeimgthumb'] == 2) {
                //image file

                $item['src'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
            } elseif ($item['homeimgthumb'] == 3) {
                //image url

                $item['src'] = $item['homeimgfile'];
            } else {
                $item['src'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/shops/no-image.jpg';
            }

            $item['alt'] = ! empty($item['homeimgalt']) ? $item['homeimgalt'] : $item[NV_LANG_DATA . '_title'];

            $item['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_shops_cat[$item['listcatid']]['alias'] . '/' . $item[NV_LANG_DATA . '_alias'] . $global_config['rewrite_exturl'];
    
            $item_array[] = $item;
        }
        $result->closeCursor();
        unset($query, $row);
        
        $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
                
        $contents = view_home_blockcat($item_array, $num_items, $generate_page, $page_title, $description, $image_group);
    }
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
