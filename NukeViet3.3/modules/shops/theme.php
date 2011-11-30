<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if (!defined('NV_IS_MOD_SHOPS'))
    die('Stop!!!');

function draw_option_select_number($select = -1, $begin = 0, $end = 100, $step = 1)
{
    $html = "";
    for ($i = $begin; $i < $end; $i = $i + $step)
    {
        if ($i == $select)
            $html .= "<option value=\"" . $i . "\" selected=\"selected\">" . $i . "</option>";
        else
            $html .= "<option value=\"" . $i . "\">" . $i . "</option>";
    }
    return $html;
}

function view_home_cat($data_content)
{
    global $module_info, $lang_module, $module_file, $global_config, $module_name, $pro_config;
    $xtpl = new XTemplate("main_procate.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $num_view = $pro_config['per_row'];
    if (!empty($data_content))
    {
        foreach ($data_content as $data_row)
        {
            if ($data_row['num_pro'] > 0)
            {
                $xtpl->assign('TITLE_CATALOG', $data_row['title']);
                $xtpl->assign('LINK_CATALOG', $data_row['link']);
                $xtpl->assign('NUM_PRO', $data_row['num_pro']);
                $i = 1;
                foreach ($data_row['data'] as $data_row_i)
                {
                    $xtpl->assign('ID', $data_row_i['id']);
                    $xtpl->assign('LINK', $data_row_i['link_pro']);
                    $xtpl->assign('TITLE', $data_row_i['title']);
                    $xtpl->assign('TITLE0', nv_clean60($data_row_i['title'], 25));
                    $xtpl->assign('IMG_SRC', $data_row_i['homeimgthumb']);
                    $xtpl->assign('LINK_ORDER', $data_row_i['link_order']);
                    $xtpl->assign('height', $pro_config['homeheight']);
                    $xtpl->assign('width', $pro_config['homewidth']);
                    if ($i % $pro_config['per_row'] == 0)
                    {
                        $xtpl->parse('main.catalogs.items.break');
                    }
                    if ($pro_config['active_price'] == '1')
                    {
                        if ($data_row_i['showprice'] == '1')
                        {
                            $xtpl->assign('product_price', CurrencyConversion($data_row_i['product_price'], $data_row_i['money_unit'], $pro_config['money_unit']));
                            $xtpl->assign('money_unit', $pro_config['money_unit']);
                            if ($data_row_i['product_discounts'] != 0)
                            {
                                $price_product_discounts = $data_row_i['product_price'] - ($data_row_i['product_price'] * ($data_row_i['product_discounts'] / 100));
                                $xtpl->assign('product_discounts', CurrencyConversion($price_product_discounts, $data_row_i['money_unit'], $pro_config['money_unit']));
                                $xtpl->assign('class_money', 'discounts_money');
                                $xtpl->parse('main.catalogs.items.price.discounts');
                            }
                            else
                            {
                                $xtpl->assign('class_money', 'money');
                            }
                            $xtpl->parse('main.catalogs.items.price');
                        }
                        else
                        {
                            $xtpl->parse('main.catalogs.items.contact');
                        }
                    }
                    $pwidth = ( float )(100 / $num_view);
                    $xtpl->assign('pwidth', $pwidth);
                    if ($pro_config['active_order'] == '1')
                    {
                        if ($data_row_i['showprice'] == '1')
                        {
                            $xtpl->parse('main.catalogs.items.order');
                        }
                    }
                    $xtpl->parse('main.catalogs.items');
                    ++$i;
                }
                if ($data_row['num_pro'] > $data_row['num_link'])
                    $xtpl->parse('main.catalogs.view_next');
                $xtpl->parse('main.catalogs');
            }
        }
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}

function view_home_all($data_content)
{
    global $module_info, $lang_module, $module_file, $global_config, $module_name, $pro_config;
    $xtpl = new XTemplate("main_product.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $num_view = $pro_config['per_row'];
    if (!empty($data_content))
    {
        $i = 1;
        foreach ($data_content as $data_row)
        {
            $xtpl->assign('ID', $data_row['id']);
            $xtpl->assign('LINK', $data_row['link_pro']);
            $xtpl->assign('TITLE', $data_row['title']);
            $xtpl->assign('TITLE0', nv_clean60($data_row['title'], 25));
            $xtpl->assign('IMG_SRC', $data_row['homeimgthumb']);
            $xtpl->assign('LINK_ORDER', $data_row['link_order']);
            $xtpl->assign('height', $pro_config['homeheight']);
            $xtpl->assign('width', $pro_config['homewidth']);

            if ($i % $pro_config['per_row'] == 0)
            {
                $xtpl->parse('main.items.break');
            }
            /****/
            $pwidth = ( float )(100 / $num_view);
            $xtpl->assign('pwidth', $pwidth);
            if ($pro_config['active_order'] == '1')
            {
                if ($data_row['showprice'] == '1')
                {
                    $xtpl->parse('main.items.order');
                }
            }
            if ($pro_config['active_price'] == '1')
            {
                if ($data_row['showprice'] == '1')
                {
                    $xtpl->assign('product_price', CurrencyConversion($data_row['product_price'], $data_row['money_unit'], $pro_config['money_unit']));
                    $xtpl->assign('money_unit', $pro_config['money_unit']);
                    if ($data_row['product_discounts'] != 0)
                    {
                        $price_product_discounts = $data_row['product_price'] - ($data_row['product_price'] * ($data_row['product_discounts'] / 100));
                        $xtpl->assign('product_discounts', CurrencyConversion($price_product_discounts, $data_row['money_unit'], $pro_config['money_unit']));
                        $xtpl->assign('class_money', 'discounts_money');
                        $xtpl->parse('main.items.price.discounts');
                    }
                    else
                    {
                        $xtpl->assign('class_money', 'money');
                    }
                    $xtpl->parse('main.items.price');
                }
                else
                {
                    $xtpl->parse('main.items.contact');
                }
            }
            $xtpl->parse('main.items');
            ++$i;
        }
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}

function view_home_none($data_content)
{
    return "";
}

function viewcat_page_gird($data_content, $pages)
{
    global $module_info, $lang_module, $module_file, $module_name, $pro_config;
    $xtpl = new XTemplate("view_gird.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('module_name', $module_file);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('alias', $data_content['alias']);
    $xtpl->assign('catid', $data_content['id']);
    $xtpl->assign('CAT_NAME', $data_content['title']);
    $xtpl->assign('count', $data_content['count']);
    if (!empty($data_content['data']))
    {
        $i = 1;
        $num_view = $pro_config['per_row'];
        foreach ($data_content['data'] as $data_row)
        {
            $xtpl->assign('id', $data_row['id']);
            $xtpl->assign('title_pro', $data_row['title']);
            $xtpl->assign('title_pro0', nv_clean60($data_row['title'], 25));
            $xtpl->assign('link_pro', $data_row['link_pro']);
            $xtpl->assign('img_pro', $data_row['homeimgthumb']);
            $xtpl->assign('link_order', $data_row['link_order']);
            $xtpl->assign('intro', $data_row['hometext']);
            if ($pro_config['active_price'] == '1')
            {
                if ($data_row['showprice'] == '1')
                {
                    $xtpl->assign('product_price', CurrencyConversion($data_row['product_price'], $data_row['money_unit'], $pro_config['money_unit']));
                    $xtpl->assign('money_unit', $pro_config['money_unit']);
                    if ($data_row['product_discounts'] != 0)
                    {
                        $price_product_discounts = $data_row['product_price'] - ($data_row['product_price'] * ($data_row['product_discounts'] / 100));
                        $xtpl->assign('product_discounts', CurrencyConversion($price_product_discounts, $data_row['money_unit'], $pro_config['money_unit']));
                        $xtpl->assign('class_money', 'discounts_money');
                        if ($pro_config['active_price'] == '1')
                            $xtpl->parse('main.grid_rows.price.discounts');
                    }
                    else
                    {
                        $xtpl->assign('class_money', 'money');
                    }
                    $xtpl->parse('main.grid_rows.price');
                }
                else
                {
                    $xtpl->parse('main.grid_rows.contact');
                }
            }
            $pwidth = ( float )(100 / $num_view);
            $xtpl->assign('pwidth', $pwidth);
            $xtpl->assign('height', $pro_config['homeheight']);
            $xtpl->assign('width', $pro_config['homewidth']);
            if ($i % $num_view == 0)
                $xtpl->parse('main.grid_rows.end_row');
            if ($pro_config['active_order'] == '1')
            {
                if ($data_row['showprice'] == '1')
                {
                    $xtpl->parse('main.grid_rows.order');
                }
            }
            $xtpl->parse('main.grid_rows');
            ++$i;
        }
    }
    $xtpl->assign('pages', $pages);
    $xtpl->assign('LINK_LOAD', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=loadcart");
    $xtpl->parse('main');
    return $xtpl->text('main');
}

function viewcat_page_list($data_content, $pages)
{
    global $module_info, $lang_module, $module_file, $module_name, $pro_config;
    $xtpl = new XTemplate("view_list.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('module_name', $module_file);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('alias', $data_content['alias']);
    $xtpl->assign('catid', $data_content['id']);
    $xtpl->assign('CAT_NAME', $data_content['title']);
    $xtpl->assign('link_order_all', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=setcart");

    $xtpl->assign('count', $data_content['count']);
    if (!empty($data_content['data']))
    {
        foreach ($data_content['data'] as $data_row)
        {
            $xtpl->assign('id', $data_row['id']);
            $xtpl->assign('title_pro', $data_row['title']);
            $xtpl->assign('link_pro', $data_row['link_pro']);
            $xtpl->assign('img_pro', $data_row['homeimgthumb']);
            $xtpl->assign('link_order', $data_row['link_order']);
            $xtpl->assign('intro', $data_row['hometext']);

            if ($pro_config['active_price'] == '1')
            {
                if ($data_row['showprice'] == '1')
                {
                    $xtpl->assign('product_price', CurrencyConversion($data_row['product_price'], $data_row['money_unit'], $pro_config['money_unit']));
                    $xtpl->assign('money_unit', $pro_config['money_unit']);
                    if ($data_row['product_discounts'] != 0)
                    {
                        $price_product_discounts = $data_row['product_price'] - ($data_row['product_price'] * ($data_row['product_discounts'] / 100));
                        $xtpl->assign('product_discounts', CurrencyConversion($price_product_discounts, $data_row['money_unit'], $pro_config['money_unit']));
                        $xtpl->assign('class_money', 'discounts_money');
                        $xtpl->parse('main.row.price.discounts');
                    }
                    else
                    {
                        $xtpl->assign('class_money', 'money');
                    }
                    $xtpl->parse('main.row.price');
                }
                else
                {
                    $xtpl->parse('main.row.contact');
                }
            }
            $xtpl->assign('address', $data_row['address']);
            $xtpl->assign('height', $pro_config['homeheight']);
            $xtpl->assign('width', $pro_config['homewidth']);
            $xtpl->assign('publtime', $lang_module['detail_dateup'] . " " . nv_date('d-m-Y h:i:s A', $data_row['publtime']));
            if ($pro_config['active_order'] == '1')
            {
                if ($data_row['showprice'] == '1')
                {
                    $xtpl->parse('main.row.order');
                }
            }
            $xtpl->parse('main.row');
        }
    }
    $xtpl->assign('pages', $pages);
    $xtpl->assign('LINK_LOAD', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=loadcart");
    $xtpl->parse('main');
    return $xtpl->text('main');
}

function detail_product($data_content, $data_unit, $data_comment, $num_comment, $data_others, $data_shop, $array_other_view)
{
    global $module_info, $lang_module, $module_file, $global_config, $module_name, $my_head, $pro_config, $module_data;
    if (!defined('SHADOWBOX'))
    {
        $my_head .= "<link rel=\"Stylesheet\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
        $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
        $my_head .= "<script type=\"text/javascript\">Shadowbox.init({ handleOversize: \"drag\" });</script>";
        define('SHADOWBOX', true);
    }
    $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=";
    $link2 = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=";
    $xtpl = new XTemplate("detail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    if (!empty($data_content))
    {
        $xtpl->assign('proid', $data_content['id']);
        $data_content['money_unit'] = ($data_content['money_unit'] != "") ? $data_content['money_unit'] : "N/A";
        $data_content[NV_LANG_DATA . '_address'] = ($data_content[NV_LANG_DATA . '_address'] != "") ? $data_content[NV_LANG_DATA . '_address'] : "N/A";
        $xtpl->assign('SRC_PRO', $data_content['homeimgthumb']);
        $xtpl->assign('SRC_PRO_LAGE', $data_content['homeimgfile']);
        $xtpl->assign('TITLE', $data_content[NV_LANG_DATA . '_title']);
        $xtpl->assign('NUM_VIEW', $data_content['hitstotal']);
        $xtpl->assign('DATE_UP', $lang_module['detail_dateup'] . " " . nv_date('d-m-Y h:i:s A', $data_content['publtime']));
        $xtpl->assign('DETAIL', $data_content[NV_LANG_DATA . '_bodytext']);
        $xtpl->assign('LINK_ORDER', $link2 . "setcart&id=" . $data_content['id']);
        $xtpl->assign('product_price', CurrencyConversion($data_content['product_price'], $data_content['money_unit'], $pro_config['money_unit']));
        $xtpl->assign('money_unit', $pro_config['money_unit']);
        if ($data_content['product_discounts'] != 0)
        {
            $price_product_discounts = $data_content['product_price'] - ($data_content['product_price'] * ($data_content['product_discounts'] / 100));
            $xtpl->assign('product_discounts', CurrencyConversion($price_product_discounts, $data_content['money_unit'], $pro_config['money_unit']));
            $xtpl->assign('class_money', 'discounts_money');
            $xtpl->parse('main.price.discounts');
        }
        else
        {
            $xtpl->assign('class_money', 'money');
        }
        $xtpl->assign('pro_unit', $data_unit['title']);
        $xtpl->assign('address', $data_content[NV_LANG_DATA . '_address']);
        $xtpl->assign('product_number', $data_content['product_number']);
        $exptime = ($data_content['exptime'] != 0) ? date("d-m-Y", $data_content['exptime']) : "N/A";
        $xtpl->assign('exptime', $exptime);
        $xtpl->assign('height', $pro_config['homeheight']);
        $xtpl->assign('width', $pro_config['homewidth']);
        $xtpl->assign('RATE', $data_content['ratingdetail']);
        if ($pro_config['active_showhomtext'] == "1")
        {
            $xtpl->assign('hometext', $data_content[NV_LANG_DATA . '_hometext']);
            $xtpl->parse('main.hometext');
        }
        if (!empty($data_content['product_code']))
        {
            $xtpl->assign('PRODUCT_CODE', $data_content['product_code']);
            $xtpl->parse('main.product_code');
        }
    }
    if ($pro_config['comment'] == "1")
    {
        if (!empty($data_comment))
        {
            foreach ($data_comment as $cdata)
            {
                $xtpl->assign('username', $cdata['post_name']);
                $xtpl->assign('avata', $cdata['photo']);
                $xtpl->assign('content', $cdata['content']);
                $xtpl->assign('date_up', nv_date('d-m-Y h:i:s A', $cdata['post_time']));
                $xtpl->parse('main.comment.list');
            }
        }
        $xtpl->parse('main.comment');
    }
    $xtpl->assign('link_addcomment', $link2 . "addcomment");
    $xtpl->assign('num_comment', $num_comment);
    $xtpl->assign('link_shop_re', $link . "=detail/" . $data_content['id'] . "/" . $data_content[NV_LANG_DATA . '_alias']);

    if (!empty($data_others))
    {
        $hmtl = view_home_all($data_others);
        $xtpl->assign('OTHER', $hmtl);
        $xtpl->parse('main.other');
    }
    if (!empty($array_other_view))
    {
        $hmtl = view_home_all($array_other_view);
        $xtpl->assign('OTHER_VIEW', $hmtl);
        $xtpl->parse('main.other_view');
    }

    $xtpl->assign('LINK_LOAD', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=loadcart");
    $xtpl->assign('THEME_URL', NV_BASE_SITEURL . "themes/" . $module_info['template']);
    $xtpl->assign('LINK_PRINT', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=print_pro&id=" . $data_content['id']);
    $xtpl->assign('LINK_RATE', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=rate&id=" . $data_content['id']);

    if (!empty($data_shop))
    {
        $xtpl->assign('title_shop', $data_shop[NV_LANG_DATA . '_title']);
        $xtpl->assign('link_shop', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=estore&amp;" . NV_OP_VARIABLE . "=shop/" . $data_shop['alias'] . "-" . $data_shop['com_id']);
        $xtpl->parse('main.shop');
    }
    if ($pro_config['active_price'] == '1')
    {
        if ($data_content['showprice'] == '1')
            $xtpl->parse('main.price');
        else
            $xtpl->parse('main.contact');
    }
    if ($pro_config['active_order_number'] == '0')
        $xtpl->parse('main.order.num');
    if ($pro_config['active_price'] == '1' && $pro_config['active_order_number'] == '0')
    {
        if ($data_content['showprice'] == '1')
        {
            $xtpl->parse('main.order');
        }
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}

function print_product($data_content, $data_unit, $page_title)
{
    global $module_info, $lang_module, $module_file, $global_config, $module_name, $my_head, $pro_config;
    $xtpl = new XTemplate("print_pro.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    if (!empty($data_content))
    {
        $xtpl->assign('proid', $data_content['id']);
        $data_content['money_unit'] = ($data_content['money_unit'] != "") ? $data_content['money_unit'] : "N/A";
        $data_content[NV_LANG_DATA . '_address'] = ($data_content[NV_LANG_DATA . '_address'] != "") ? $data_content[NV_LANG_DATA . '_address'] : "N/A";
        $xtpl->assign('SRC_PRO', $data_content['homeimgthumb']);
        $xtpl->assign('SRC_PRO_LAGE', $data_content['homeimgthumb']);
        $xtpl->assign('TITLE', $data_content[NV_LANG_DATA . '_title']);
        $xtpl->assign('NUM_VIEW', $data_content['hitstotal']);
        $xtpl->assign('DATE_UP', $lang_module['detail_dateup'] . date(' d-m-Y ', $data_content['addtime']) . $lang_module['detail_moment'] . date(" h:i'", $data_content['addtime']));
        $xtpl->assign('DETAIL', $data_content[NV_LANG_DATA . '_bodytext']);
        $xtpl->assign('product_price', CurrencyConversion($data_content['product_price'], $data_content['money_unit'], $pro_config['money_unit']));
        $xtpl->assign('money_unit', $pro_config['money_unit']);
        $xtpl->assign('pro_unit', $data_unit['title']);
        $xtpl->assign('address', $data_content[NV_LANG_DATA . '_address']);
        $xtpl->assign('product_number', $data_content['product_number']);
        $exptime = ($data_content['exptime'] != 0) ? date("d-m-Y", $data_content['exptime']) : "N/A";
        $xtpl->assign('exptime', $exptime);
        $xtpl->assign('height', $pro_config['homeheight']);
        $xtpl->assign('width', $pro_config['homewidth']);

        $link_url = $global_config['site_url'] . '/' . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=detail/" . $data_content['id'] . "/" . $data_content[NV_LANG_DATA . '_alias'] . "";
        $xtpl->assign('link_url', $link_url);
        $xtpl->assign('site_name', $global_config['site_name']);
        $xtpl->assign('url', $global_config['site_url']);
        $xtpl->assign('contact', $global_config['site_email']);
        $xtpl->assign('page_title', $page_title);
    }
    if ($pro_config['active_price'] == '1')
        $xtpl->parse('main.price');
    $xtpl->parse('main');
    return $xtpl->text('main');
}

function cart_product($data_content, $array_error_number)
{
    global $module_info, $lang_module, $module_file, $global_config, $module_name, $pro_config;
    $xtpl = new XTemplate("cart.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $price_total = 0;
    $i = 1;
    if (!empty($data_content))
    {
        foreach ($data_content as $data_row)
        {
            $xtpl->assign('stt', $i);
            $xtpl->assign('id', $data_row['id']);
            $xtpl->assign('title_pro', $data_row['title']);
            $xtpl->assign('link_pro', $data_row['link_pro']);
            $xtpl->assign('img_pro', $data_row['homeimgthumb']);
            $note = str_replace("|", ", ", $data_row['note']);
            $xtpl->assign('note', nv_clean60($note, 50));
            $price_product_discounts = $data_row['product_price'] - ($data_row['product_price'] * ($data_row['product_discounts'] / 100));
            $price_product_discounts = CurrencyConversionToNumber($price_product_discounts, $data_row['money_unit'], $pro_config['money_unit']);
            $xtpl->assign('product_price', nv_number_format($price_product_discounts));
            $xtpl->assign('pro_num', $data_row['num']);
            $xtpl->assign('product_unit', $data_row['product_unit']);
            $xtpl->assign('link_remove', $data_row['link_remove']);
            $bg = ($i % 2 == 0) ? "class=\"bg\"" : "";
            $xtpl->assign('bg', $bg);
            if ($pro_config['active_price'] == '1')
                $xtpl->parse('main.rows.price2');
            if ($pro_config['active_order_number'] == '0')
                $xtpl->parse('main.rows.num2');
            $xtpl->parse('main.rows');
            $price_total = $price_total + ( double )($price_product_discounts) * ( int )($data_row['num']);
            ++$i;
        }
    }
    if (!empty($array_error_number))
    {
        foreach ($array_error_number as $title_error)
        {
            $xtpl->assign('ERROR_NUMBER_PRODUCT', $title_error);
            $xtpl->parse('main.errortitle.errorloop');
        }
        $xtpl->parse('main.errortitle');
    }
    $xtpl->assign('price_total', nv_number_format($price_total));
    $xtpl->assign('unit_config', $pro_config['money_unit']);
    $xtpl->assign('LINK_DEL_ALL', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=remove");
    $xtpl->assign('LINK_CART', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cart");
    $xtpl->assign('LINK_PRODUCTS', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "");
    $xtpl->assign('link_order_all', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=order");
    if ($pro_config['active_price'] == '1')
        $xtpl->parse('main.price1');
    if ($pro_config['active_order_number'] == '0')
    {
        $xtpl->parse('main.num1');
        $xtpl->parse('main.num4');
    }
    if ($pro_config['active_price'] == '1' && $pro_config['active_order_number'] == '0')
        $xtpl->parse('main.price3');
    $xtpl->parse('main');
    return $xtpl->text('main');
}

function uers_order($data_content, $data_order, $error)
{
    global $module_info, $lang_module, $module_file, $global_config, $module_name, $pro_config;
    $xtpl = new XTemplate("order.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $price_total = 0;
    $i = 1;
    if (!empty($data_content))
    {
        foreach ($data_content as $data_row)
        {
            $xtpl->assign('id', $data_row['id']);
            $xtpl->assign('title_pro', $data_row['title']);
            $xtpl->assign('link_pro', $data_row['link_pro']);
            $note = str_replace("|", ", ", $data_row['note']);
            $xtpl->assign('note', nv_clean60($note, 50));
            $price_product_discounts = $data_row['product_price'] - ($data_row['product_price'] * ($data_row['product_discounts'] / 100));
            $price_product_discounts = CurrencyConversionToNumber($price_product_discounts, $data_row['money_unit'], $pro_config['money_unit']);
            $xtpl->assign('product_price', nv_number_format($price_product_discounts));
            $xtpl->assign('pro_num', $data_row['num']);
            $xtpl->assign('product_unit', $data_row['product_unit']);
            $xtpl->assign('pro_no', $i);
            $bg = ($i % 2 == 0) ? "class=\"bg\"" : "";
            $xtpl->assign('bg', $bg);
            if ($pro_config['active_price'] == '1')
                $xtpl->parse('main.rows.price2');
            if ($pro_config['active_order_number'] == '0')
                $xtpl->parse('main.rows.num2');
            $xtpl->parse('main.rows');
            $price_total = $price_total + ( double )($price_product_discounts) * ( int )($data_row['num']);
            ++$i;
        }
    }
    $xtpl->assign('price_total', nv_number_format($price_total));
    $xtpl->assign('unit_config', $pro_config['money_unit']);
    $xtpl->assign('DATA', $data_order);
    $xtpl->assign('ERROR', $error);
    $xtpl->assign('LINK_CART', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cart");
    if ($pro_config['active_price'] == '1')
        $xtpl->parse('main.price1');
    if ($pro_config['active_order_number'] == '0')
        $xtpl->parse('main.num1');
    if ($pro_config['active_price'] == '1' && $pro_config['active_order_number'] == '0')
        $xtpl->parse('main.price3');
    $xtpl->parse('main');
    return $xtpl->text('main');
}

function payment($data_content, $data_pro, $url_checkout, $intro_pay)
{
    global $module_info, $lang_module, $module_file, $global_config, $module_name, $pro_config;
    $xtpl = new XTemplate("payment.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('dateup', date("d-m-Y", $data_content['order_time']));
    $xtpl->assign('moment', date("h:i' ", $data_content['order_time']));
    $xtpl->assign('DATA', $data_content);
    $xtpl->assign('order_id', $data_content['order_id']);
    ////////////////////////////////////////////////////////
    $i = 0;
    foreach ($data_pro as $pdata)
    {
        $xtpl->assign('product_name', $pdata['title']);
        $xtpl->assign('product_number', $pdata['product_number']);
        $xtpl->assign('product_price', nv_number_format($pdata['product_price']));
        $xtpl->assign('product_unit', $pdata['product_unit']);
        $xtpl->assign('product_note', $pdata['product_note']);
        $xtpl->assign('link_pro', $pdata['link_pro']);
        $xtpl->assign('pro_no', $i + 1);
        $bg = ($i % 2 == 0) ? "class=\"bg\"" : "";
        $xtpl->assign('bg', $bg);
        if ($pro_config['active_price'] == '1')
            $xtpl->parse('main.loop.price2');
        if ($pro_config['active_order_number'] == '0')
            $xtpl->parse('main.loop.num2');
        $xtpl->parse('main.loop');
        ++$i;
    }
    if (!empty($data_content['order_note']))
    {
        $xtpl->parse('main.order_note');
    }
    $xtpl->assign('order_total', nv_number_format($data_content['order_total']));
    $xtpl->assign('unit', $data_content['unit_total']);
    if (!empty($url_checkout))
    {
        $xtpl->assign('note_pay', '');
        foreach ($url_checkout as $value)
        {
            $xtpl->assign('DATA_PAYMENT', $value);
            $xtpl->parse('main.actpay.payment.paymentloop');
        }
        $xtpl->parse('main.actpay.payment');
    }
    $xtpl->assign('intro_pay', $intro_pay);
    if ($pro_config['active_payment'] == '1' && $pro_config['active_order'] == '1' && $pro_config['active_price'] == '1' && $pro_config['active_order_number'] == '0')
        $xtpl->parse('main.actpay');
    $xtpl->assign('url_finsh', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name);
    $xtpl->assign('url_print', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=print&order_id=" . $data_content['order_id'] . "&checkss=" . md5($data_content['order_id'] . $global_config['sitekey'] . session_id()));
    if ($pro_config['active_price'] == '1')
        $xtpl->parse('main.price1');
    if ($pro_config['active_order_number'] == '0')
        $xtpl->parse('main.num1');
    if ($pro_config['active_price'] == '1' && $pro_config['active_order_number'] == '0')
        $xtpl->parse('main.price3');
    $xtpl->parse('main');
    return $xtpl->text('main');
}

function print_pay($data_content, $data_pro)
{
    global $module_info, $lang_module, $module_file, $global_config, $module_name, $pro_config;
    $xtpl = new XTemplate("print.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('dateup', date("d-m-Y", $data_content['order_time']));
    $xtpl->assign('moment', date("h:i' ", $data_content['order_time']));
    $xtpl->assign('DATA', $data_content);
    $xtpl->assign('order_id', $data_content['id']);
    ////////////////////////////////////////////////////////
    $i = 0;
    foreach ($data_pro as $pdata)
    {
        $xtpl->assign('product_name', $pdata['title']);
        $xtpl->assign('product_number', $pdata['product_number']);
        $xtpl->assign('product_price', nv_number_format($pdata['product_price']));
        $xtpl->assign('product_unit', $pdata['product_unit']);
        $xtpl->assign('product_note', $pdata['product_note']);
        $xtpl->assign('link_pro', $pdata['link_pro']);
        $xtpl->assign('pro_no', $i + 1);
        $bg = ($i % 2 == 0) ? "class=\"bg\"" : "";
        $xtpl->assign('bg', $bg);
        if ($pro_config['active_price'] == '1')
            $xtpl->parse('main.loop.price2');
        if ($pro_config['active_order_number'] == '0')
            $xtpl->parse('main.loop.num2');
        $xtpl->parse('main.loop');
        ++$i;
    }
    if (!empty($data_content['order_note']))
    {
        $xtpl->parse('main.order_note');
    }
    $xtpl->assign('order_total', nv_number_format($data_content['order_total']));
    $xtpl->assign('unit', $data_content['unit_total']);

    $payment = "";
    if ($data_content['transaction_status'] == 4)
    {
        $payment = $lang_module['history_payment_yes'];
    }
    elseif ($data_content['transaction_status'] == 3)
    {
        $payment = $lang_module['history_payment_cancel'];
    }
    elseif ($data_content['transaction_status'] == 2)
    {
        $payment = $lang_module['history_payment_check'];
    }
    elseif ($data_content['transaction_status'] == 1)
    {
        $payment = $lang_module['history_payment_send'];
    }
    elseif ($data_content['transaction_status'] == 0)
    {
        $payment = $lang_module['history_payment_no'];
    }
    elseif ($data_content['transaction_status'] == -1)
    {
        $payment = $lang_module['history_payment_wait'];
    }
    else
    {
        $payment = "ERROR";
    }
    $xtpl->assign('payment', $payment);
    if ($pro_config['active_price'] == '1')
        $xtpl->parse('main.price1');
    if ($pro_config['active_order_number'] == '0')
        $xtpl->parse('main.num1');
    if ($pro_config['active_price'] == '1' && $pro_config['active_order_number'] == '0')
        $xtpl->parse('main.price3');
    $xtpl->parse('main');
    return $xtpl->text('main');
}

function history_order($data_content, $link_check_order)
{
    global $module_info, $lang_module, $module_file, $global_config, $module_name, $pro_config;
    $xtpl = new XTemplate("history_order.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $i = 0;

    foreach ($data_content as $data_row)
    {
        $xtpl->assign('order_code', $data_row['order_code']);
        $xtpl->assign('history_date', date("d-m-Y", $data_row['order_time']));
        $xtpl->assign('history_moment', date("h:i' ", $data_row['order_time']));
        $xtpl->assign('history_total', nv_number_format($data_row['order_total']));
        $xtpl->assign('unit_total', $data_row['unit_total']);
        $xtpl->assign('note', $data_row['order_note']);
        $xtpl->assign('URL_DEL_BACK', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=history");
        if (intval($data_row['transaction_status']) == -1)
        {
            $xtpl->assign('text_no_remove', "");
            $xtpl->assign('link_remove', $data_row['link_remove']);
            $xtpl->parse('main.rows.remove');
        }
        else
        {
            $xtpl->assign('text_no_remove', '');
        }
        $xtpl->assign('link', $data_row['link']);

        /* transaction_status: Trang thai giao dich:
         0 - Giao dich moi tao
         1 - Chua thanh toan;
         2 - Da thanh toan, dang bi tam giu;
         3 - Giao dich bi huy;
         4 - Giao dich da hoan thanh thanh cong (truong hop thanh toan ngay hoac thanh toan tam giu nhung nguoi mua da phe chuan)
         */
        if ($data_row['transaction_status'] == 4)
        {
            $history_payment = $lang_module['history_payment_yes'];
        }
        elseif ($data_row['transaction_status'] == 3)
        {
            $history_payment = $lang_module['history_payment_cancel'];
        }
        elseif ($data_row['transaction_status'] == 2)
        {
            $history_payment = $lang_module['history_payment_check'];
        }
        elseif ($data_row['transaction_status'] == 1)
        {
            $history_payment = $lang_module['history_payment_send'];
        }
        elseif ($data_row['transaction_status'] == 0)
        {
            $history_payment = $lang_module['history_payment_no'];
        }
        elseif ($data_row['transaction_status'] == -1)
        {
            $history_payment = $lang_module['history_payment_wait'];
        }
        else
        {
            $history_payment = "ERROR";
        }

        $xtpl->assign('LINK_CHECK_ORDER', $link_check_order);
        $xtpl->assign('history_payment', $history_payment);
        $bg = ($i % 2 == 0) ? "class=\"bg\"" : "";
        $xtpl->assign('bg', $bg);
        $xtpl->assign('TT', $i + 1);
        if ($pro_config['active_price'] == '1')
            $xtpl->parse('main.rows.price2');
        $xtpl->parse('main.rows');
        ++$i;
    }
    if ($pro_config['active_price'] == '1')
    {
        $xtpl->parse('main.price1');
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}

//// search.php
function search_theme($key, $check_num, $date_array, $array_cat_search)
{
    global $module_name, $module_info, $module_file, $global_config, $lang_module, $module_name;
    $xtpl = new XTemplate("search.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $base_url_site = NV_BASE_SITEURL . "?";
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('BASE_URL_SITE', $base_url_site);
    $xtpl->assign('TO_DATE', $date_array['to_date']);
    $xtpl->assign('FROM_DATE', $date_array['from_date']);
    $xtpl->assign('KEY', $key);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('OP_NAME', 'search');

    foreach ($array_cat_search as $search_cat)
    {
        $xtpl->assign('SEARCH_CAT', $search_cat);
        $xtpl->parse('main.search_cat');
    }
    for ($i = 0; $i <= 3; ++$i)
    {
        if ($check_num == $i)
            $xtpl->assign('CHECK' . $i, "selected=\"selected\"");
        else
            $xtpl->assign('CHECK' . $i, "");
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}

function search_result_theme($key, $numRecord, $per_pages, $pages, $array_content, $url_link, $catid)
{
    global $module_file, $module_info, $global_config, $lang_global, $lang_module, $db, $module_name, $global_array_cat;
    $xtpl = new XTemplate("search.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('KEY', $key);

    $xtpl->assign('TITLE_MOD', $lang_module['search_modul_title']);

    if (!empty($array_content))
    {
        foreach ($array_content as $value)
        {
            $catid_i = ($catid > 0) ? $catid : end(explode(",", $value['listcatid']));
            $url = $global_array_cat[$catid_i]['link'] . '/' . $value['alias'] . "-" . $value['id'];
            $xtpl->assign('LINK', $url);
            $xtpl->assign('TITLEROW', BoldKeywordInStr($value['title'], $key));
            $xtpl->assign('CONTENT', BoldKeywordInStr($value['hometext'], $key) . "...");

            $xtpl->assign('IMG_SRC', $value['homeimgthumb']);
            $xtpl->parse('results.result.result_img');

            $xtpl->parse('results.result');
        }
    }
    if ($numRecord == 0)
    {
        $xtpl->assign('KEY', $key);
        $xtpl->assign('INMOD', $lang_module['search_modul_title']);
        $xtpl->parse('results.noneresult');
    }
    if ($numRecord > $per_pages)// show pages
    {
        $url_link = $_SERVER['REQUEST_URI'];
        $in = strpos($url_link, '&page');
        if ($in != 0)
            $url_link = substr($url_link, 0, $in);
        $generate_page = nv_generate_page($url_link, $numRecord, $per_pages, $pages);
        $xtpl->assign('VIEW_PAGES', $generate_page);
        $xtpl->parse('results.pages_result');
    }
    $xtpl->assign('MY_DOMAIN', NV_MY_DOMAIN);
    $xtpl->assign('NUMRECORD', $numRecord);
    $xtpl->parse('results');
    return $xtpl->text('results');
}
?>