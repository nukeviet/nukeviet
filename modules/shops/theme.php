<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 04 May 2014 12:41:32 GMT
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

/**
 * redict_link()
 *
 * @param mixed $lang_view
 * @param mixed $lang_back
 * @param mixed $nv_redirect
 * @return
 */
function redict_link( $lang_view, $lang_back, $nv_redirect )
{
	$contents = "<div class=\"frame\">";
	$contents .= $lang_view . "<br /><br />\n";
	$contents .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
	$contents .= "<a href=\"" . $nv_redirect . "\">" . $lang_back . "</a>";
	$contents .= "</div>";
	$contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . $nv_redirect . "\" />";
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

/**
 * draw_option_select_number()
 *
 * @param integer $select
 * @param integer $begin
 * @param integer $end
 * @param integer $step
 * @return
 */
function draw_option_select_number( $select = -1, $begin = 0, $end = 100, $step = 1 )
{
	$html = '';
	for( $i = $begin; $i < $end; $i = $i + $step )
	{
		if( $i == $select ) $html .= "<option value=\"" . $i . "\" selected=\"selected\">" . $i . "</option>";
		else
			$html .= "<option value=\"" . $i . "\">" . $i . "</option>";
	}
	return $html;
}

/**
 * view_home_group()
 *
 * @param mixed $data_content
 * @param string $html_pages
 * @return
 */
function view_home_group( $data_content, $html_pages = '', $sort = 0 )
{
	global $module_info, $lang_module, $module_name, $module_file, $pro_config, $array_wishlist_id;

	$xtpl = new XTemplate( 'main_procate.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	$num_view = $pro_config['per_row'];

	$xtpl->assign( 'CSS_PRODUCT_CODE', ! empty( $pro_config['show_product_code'] ) ? ' show-product-code' : '' );

	if( ! empty( $data_content ) )
	{
		foreach( $data_content as $data_row )
		{
			if( $data_row['num_pro'] > 0 )
			{
				$xtpl->assign( 'TITLE_CATALOG', $data_row['title'] );
				$xtpl->assign( 'LINK_CATALOG', $data_row['link'] );
				$xtpl->assign( 'NUM_PRO', $data_row['num_pro'] );
				$i = 1;
                $num_row = $pro_config['per_row'] == 3 ? 4 : 3;

				foreach( $data_row['data'] as $data_row_i )
				{
					$xtpl->assign( 'ID', $data_row_i['id'] );
					$xtpl->assign( 'LINK', $data_row_i['link_pro'] );
					$xtpl->assign( 'TITLE', $data_row_i['title'] );
					$xtpl->assign( 'TITLE0', nv_clean60( $data_row_i['title'], 40 ) );
					$xtpl->assign( 'IMG_SRC', $data_row_i['homeimgthumb'] );
					$xtpl->assign( 'LINK_ORDER', $data_row_i['link_order'] );
					$xtpl->assign( 'height', $pro_config['homeheight'] );
					$xtpl->assign( 'width', $pro_config['homewidth'] );
					$xtpl->assign( 'hometext', nv_clean60( $data_row_i['hometext'], 90 ) );
					$xtpl->assign( 'PRODUCT_CODE', $data_row_i['product_code'] );

					$newday = $data_row_i['publtime'] + ( 86400 * $data_row_i['newday'] );
					if ( $newday >= NV_CURRENTTIME )
					{
						$xtpl->parse( 'main.catalogs.items.new' );
					}

					$price = nv_currency_conversion( $data_row_i['product_price'], $data_row_i['money_unit'], $pro_config['money_unit'], $data_row_i['discount_id'] );

					if( $pro_config['active_price'] == '1' )
					{
						if( $data_row_i['showprice'] == '1' )
						{
							$xtpl->assign( 'PRICE', $price );
							if( $data_row_i['discount_id'] and $price['discount_percent'] > 0 )
							{
								$xtpl->parse( 'main.catalogs.items.price.discounts' );
								$xtpl->parse( 'main.catalogs.items.price.discounts.standard' );
							}
							else
							{
								$xtpl->parse( 'main.catalogs.items.price.no_discounts' );
							}
							$xtpl->parse( 'main.catalogs.items.price' );
						}
						else
						{
							$xtpl->parse( 'main.catalogs.items.contact' );
						}
					}

					$xtpl->assign( 'num', $num_row );

					if( $pro_config['active_order'] == '1' )
					{
						if( $data_row_i['showprice'] == '1' )
						{
							if( $data_row_i['product_number'] > 0 )
							{
								$xtpl->parse( 'main.catalogs.items.order' );
							}
							else
							{
								$xtpl->parse( 'main.catalogs.items.product_empty' );
							}
						}
					}
					if( $pro_config['active_tooltip'] == 1 ) $xtpl->parse( 'main.catalogs.items.tooltip' );

					if( ! empty( $pro_config['show_product_code'] ) and ! empty( $data_row_i['product_code'] ) )
					{
						$xtpl->parse( 'main.catalogs.items.product_code' );
					}

					if( defined( 'NV_IS_MODADMIN' ) )
					{
						$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data_row_i['id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data_row_i['id'] ) );
						$xtpl->parse( 'main.catalogs.items.adminlink' );
					}

                    // So sanh san pham
                    if( $pro_config['show_compare'] == 1 )
                    {
                        if( isset( $_SESSION[$module_name . '_array_id'] ) )
                        {
                            $array_id = $_SESSION[$module_name . '_array_id'];
                            $array_id = unserialize( $array_id );
                        }
                        else
                        {
                            $array_id = array();
                        }
						
	                    if( ! empty( $array_id ) )
	                    {
	                        $ch = ( in_array( $data_row['id'], $array_id ) ) ? ' checked="checked"' : '';
	                        $xtpl->assign( 'ch', $ch );
	                    }

                        $xtpl->parse( 'main.catalogs.items.compare' );
                    }

					// San pham yeu thich
					if( $pro_config['active_wishlist'] )
					{
						if( ! empty( $array_wishlist_id ) )
						{
							if( in_array( $data_row_i['id'], $array_wishlist_id) )
							{
								$xtpl->parse( 'main.catalogs.items.wishlist.disabled' );
							}
						}
						$xtpl->parse( 'main.catalogs.items.wishlist' );
					}

					if( $data_row_i['discount_id'] and $price['discount_percent'] > 0 )
					{
						$xtpl->parse( 'main.catalogs.items.discounts' );
					}

					$xtpl->parse( 'main.catalogs.items' );
					++$i;
				}
				if( $data_row['num_pro'] > $data_row['num_link'] ) $xtpl->parse( 'main.catalogs.view_next' );
				$xtpl->parse( 'main.catalogs' );
			}
		}
	}

	if( $pro_config['active_tooltip'] == 1 ) $xtpl->parse( 'main.tooltip_js' );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * view_home_cat()
 *
 * @param mixed $data_content
 * @param string $html_pages
 * @return
 */
function view_home_cat( $data_content, $html_pages = '', $sort = 0 )
{
	global $module_info, $lang_module, $module_file, $module_name, $pro_config, $array_wishlist_id;

	$xtpl = new XTemplate( 'main_procate.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	$num_view = $pro_config['per_row'];

	$xtpl->assign( 'CSS_PRODUCT_CODE', ! empty( $pro_config['show_product_code'] ) ? ' show-product-code' : '' );

	if( ! empty( $data_content ) )
	{
		foreach( $data_content as $data_row )
		{
			if( $data_row['num_pro'] > 0 )
			{
				$xtpl->assign( 'TITLE_CATALOG', $data_row['title'] );
				$xtpl->assign( 'LINK_CATALOG', $data_row['link'] );
				$xtpl->assign( 'NUM_PRO', $data_row['num_pro'] );
				$i = 1;
                $num_row = $pro_config['per_row'] == 3 ? 4 : 3;

				foreach( $data_row['data'] as $data_row_i )
				{
					$xtpl->assign( 'ID', $data_row_i['id'] );
					$xtpl->assign( 'LINK', $data_row_i['link_pro'] );
					$xtpl->assign( 'TITLE', $data_row_i['title'] );
					$xtpl->assign( 'TITLE0', nv_clean60( $data_row_i['title'], 40 ) );
					$xtpl->assign( 'IMG_SRC', $data_row_i['homeimgthumb'] );
					$xtpl->assign( 'LINK_ORDER', $data_row_i['link_order'] );
					$xtpl->assign( 'height', $pro_config['homeheight'] );
					$xtpl->assign( 'width', $pro_config['homewidth'] );
					$xtpl->assign( 'hometext', nv_clean60( $data_row_i['hometext'], 90 ) );
					$xtpl->assign( 'PRODUCT_CODE', $data_row_i['product_code'] );

					$newday = $data_row_i['publtime'] + ( 86400 * $data_row_i['newday'] );
					if ( $newday >= NV_CURRENTTIME )
					{
						$xtpl->parse( 'main.catalogs.items.new' );
					}

					$price = nv_currency_conversion( $data_row_i['product_price'], $data_row_i['money_unit'], $pro_config['money_unit'], $data_row_i['discount_id'] );

					if( $pro_config['active_price'] == '1' )
					{
						if( $data_row_i['showprice'] == '1' )
						{
							$xtpl->assign( 'PRICE', $price );
							if( $data_row_i['discount_id'] and $price['discount_percent'] > 0 )
							{
								$xtpl->parse( 'main.catalogs.items.price.discounts' );
								$xtpl->parse( 'main.catalogs.items.price.discounts.standard' );
							}
							else
							{
								$xtpl->parse( 'main.catalogs.items.price.no_discounts' );
							}
							$xtpl->parse( 'main.catalogs.items.price' );
						}
						else
						{
							$xtpl->parse( 'main.catalogs.items.contact' );
						}
					}

					$xtpl->assign( 'num', $num_row );

					if( $pro_config['active_order'] == '1' )
					{
						if( $data_row_i['showprice'] == '1' )
						{
							if( $data_row_i['product_number'] > 0 )
							{
								$xtpl->parse( 'main.catalogs.items.order' );
							}
							else
							{
								$xtpl->parse( 'main.catalogs.items.product_empty' );
							}
						}
					}

					if( ! empty( $pro_config['show_product_code'] ) and ! empty( $data_row_i['product_code'] ) )
					{
						$xtpl->parse( 'main.catalogs.items.product_code' );
					}

					if( defined( 'NV_IS_MODADMIN' ) )
					{
						$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data_row_i['id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data_row_i['id'] ) );
						$xtpl->parse( 'main.catalogs.items.adminlink' );
					}

                    // So sanh san pham
                    if( $pro_config['show_compare'] == 1 )
                    {
                        if( isset( $_SESSION[$module_name . '_array_id'] ) )
                        {
                            $array_id = $_SESSION[$module_name . '_array_id'];
                            $array_id = unserialize( $array_id );
                        }
                        else
                        {
                            $array_id = array();
                        }
						
	                    if( ! empty( $array_id ) )
	                    {
	                        $ch = ( in_array( $data_row['id'], $array_id ) ) ? ' checked="checked"' : '';
	                        $xtpl->assign( 'ch', $ch );
	                    }

                        $xtpl->parse( 'main.catalogs.items.compare' );
                    }
						
					// San pham yeu thich
					if( $pro_config['active_wishlist'] )
					{
						if( ! empty( $array_wishlist_id ) )
						{
							if( in_array( $data_row_i['id'], $array_wishlist_id) )
							{
								$xtpl->parse( 'main.catalogs.items.wishlist.disabled' );
							}
						}
						$xtpl->parse( 'main.catalogs.items.wishlist' );
					}

					if( $data_row_i['discount_id'] and $price['discount_percent'] > 0 )
					{
						$xtpl->parse( 'main.catalogs.items.discounts' );
					}

					$xtpl->parse( 'main.catalogs.items' );
					++$i;
				}
				if( $data_row['num_pro'] > $data_row['num_link'] ) $xtpl->parse( 'main.catalogs.view_next' );
				$xtpl->parse( 'main.catalogs' );
			}
		}
	}

	if( $pro_config['active_tooltip'] == 1 ) $xtpl->parse( 'main.tooltip_js' );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * view_home_all()
 *
 * @param mixed $data_content
 * @param string $html_pages
 * @return
 */
function view_home_all( $data_content, $html_pages = '', $sort = 0 )
{
	global $module_info, $lang_module, $module_file, $module_name, $pro_config, $op, $array_displays, $array_wishlist_id;

	$xtpl = new XTemplate( 'main_product.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	$xtpl->assign( 'CSS_PRODUCT_CODE', ! empty( $pro_config['show_product_code'] ) ? ' show-product-code' : '' );
	if( ( ! isset( $op ) OR $op != 'detail' ) && $pro_config['show_displays'] == 1 )
	{
		foreach( $array_displays as $k => $array_displays_i )
		{
			$se = '';
			$xtpl->assign( 'value', $array_displays_i );
			$xtpl->assign( 'key', $k );
			$se = ( $sort == $k ) ? 'selected="selected"' : '';
			$xtpl->assign( 'se', $se );
			$xtpl->parse( 'main.displays.sorts' );
		}
		$xtpl->parse( 'main.displays' );
	}

	if( ! empty( $data_content ) )
	{
		$i = 1;
        $num_row = $pro_config['per_row'] == 3 ? 4 : 3;

		if( $op == 'main')
		{
			$xtpl->parse( 'main.new_product_title' );
		}

		foreach( $data_content as $data_row )
		{
			$xtpl->assign( 'ID', $data_row['id'] );
			$xtpl->assign( 'LINK', $data_row['link_pro'] );
			$xtpl->assign( 'TITLE', $data_row['title'] );
			$xtpl->assign( 'TITLE0', nv_clean60( $data_row['title'], 40 ) );
			$xtpl->assign( 'IMG_SRC', $data_row['homeimgthumb'] );
			$xtpl->assign( 'LINK_ORDER', $data_row['link_order'] );
			$xtpl->assign( 'height', $pro_config['homeheight'] );
			$xtpl->assign( 'width', $pro_config['homewidth'] );
			$xtpl->assign( 'hometext', nv_clean60( $data_row['hometext'], 115 ) );
			$xtpl->assign( 'PRODUCT_CODE', $data_row['product_code'] );

			$xtpl->assign( 'num', $num_row );

			$newday = $data_row['publtime'] + ( 86400 * $data_row['newday'] );
			if ( $newday >= NV_CURRENTTIME )
			{
				$xtpl->parse( 'main.items.new' );
			}

			if( $pro_config['active_order'] == '1' )
			{
				if( $data_row['showprice'] == '1' )
				{
					if( $data_row['product_number'] > 0 )
					{
						$xtpl->parse( 'main.items.order' );
					}
					else
					{
						$xtpl->parse( 'main.items.product_empty' );
					}
				}
			}
			
			$price = nv_currency_conversion( $data_row['product_price'], $data_row['money_unit'], $pro_config['money_unit'], $data_row['discount_id'] );

			if( $pro_config['active_price'] == '1' )
			{
				if( $data_row['showprice'] == '1' )
				{
					$xtpl->assign( 'PRICE', $price );
					if( $data_row['discount_id'] and $price['discount_percent'] > 0 )
					{
						$xtpl->parse( 'main.items.price.discounts' );
						$xtpl->parse( 'main.items.price.discounts.standard' );
					}
					else
					{
						$xtpl->parse( 'main.items.price.no_discounts' );
					}
					$xtpl->parse( 'main.items.price' );
				}
				else
				{
					$xtpl->parse( 'main.items.contact' );
				}
			}
			
			if( $pro_config['active_tooltip'] == 1 ) $xtpl->parse( 'main.items.tooltip' );

			if( ! empty( $pro_config['show_product_code'] ) and ! empty( $data_row['product_code'] ) )
			{
				$xtpl->parse( 'main.items.product_code' );
			}

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data_row['id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data_row['id'] ) );
				$xtpl->parse( 'main.items.adminlink' );
			}

            // So sanh san pham
            if( $pro_config['show_compare'] == 1 )
            {
                if( isset( $_SESSION[$module_name . '_array_id'] ) )
                {
                    $array_id = $_SESSION[$module_name . '_array_id'];
                    $array_id = unserialize( $array_id );
                }
                else
                {
                    $array_id = array();
                }
				
	            if( ! empty( $array_id ) )
	            {
	                $ch = ( in_array( $data_row['id'], $array_id ) ) ? ' checked="checked"' : '';
	                $xtpl->assign( 'ch', $ch );
	            }
				
                $xtpl->parse( 'main.items.compare' );
            }
			
			// San pham yeu thich
			if( $pro_config['active_wishlist'] )
			{
				if( ! empty( $array_wishlist_id ) )
				{
					if( in_array( $data_row['id'], $array_wishlist_id) )
					{
						$xtpl->parse( 'main.items.wishlist.disabled' );
					}
				}
				$xtpl->parse( 'main.items.wishlist' );
			}

			if( $data_row['discount_id'] and $price['discount_percent'] > 0 )
			{
				$xtpl->parse( 'main.items.discounts' );
			}

			$xtpl->parse( 'main.items' );
			++$i;
		}

		if( ! empty( $html_pages ) )
		{
			$xtpl->assign( 'generate_page', $html_pages );
			$xtpl->parse( 'main.pages' );
		}
	}
	if( $pro_config['active_tooltip'] == 1 ) $xtpl->parse( 'main.tooltip_js' );
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * view_search_all()
 *
 * @param mixed $data_content
 * @param string $html_pages
 * @return
 */
function view_search_all( $data_content, $html_pages = '' )
{
	global $module_info, $lang_module, $module_file, $pro_config, $array_wishlist_id;

	$xtpl = new XTemplate( 'search_all.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	$num_view = $pro_config['per_row'];

	if( ! empty( $data_content ) )
	{
		$i = 1;
        $num_row = $pro_config['per_row'] == 3 ? 4 : 3;

		foreach( $data_content as $data_row )
		{
			$xtpl->assign( 'ID', $data_row['id'] );
			$xtpl->assign( 'LINK', $data_row['link_pro'] );
			$xtpl->assign( 'TITLE', $data_row['title'] );
			$xtpl->assign( 'TITLE0', nv_clean60( $data_row['title'], 40 ) );
			$xtpl->assign( 'IMG_SRC', $data_row['homeimgthumb'] );
			$xtpl->assign( 'LINK_ORDER', $data_row['link_order'] );
			$xtpl->assign( 'height', $pro_config['homeheight'] );
			$xtpl->assign( 'width', $pro_config['homewidth'] );
			$xtpl->assign( 'hometext', nv_clean60( $data_row['hometext'], 90 ) );
			$xtpl->assign( 'num', $num_row );

			if( $pro_config['active_order'] == '1' )
			{
				if( $data_row['showprice'] == '1' )
				{
					if( $data_row['product_number'] > 0 )
					{
						$xtpl->parse( 'main.items.order' );
					}
					else
					{
						$xtpl->parse( 'main.items.product_empty' );
					}
				}
			}

			$price = nv_currency_conversion( $data_row['product_price'], $data_row['money_unit'], $pro_config['money_unit'], $data_row['discount_id'] );
			
			if( $pro_config['active_price'] == '1' )
			{			
				if( $data_row['showprice'] == '1' )
				{
					$xtpl->assign( 'PRICE', $price );
					if( $data_row['discount_id'] and $price['discount_percent'] > 0 )
					{
						$xtpl->parse( 'main.items.price.discounts' );
						$xtpl->parse( 'main.items.price.discounts.standard' );
					}
					else
					{
						$xtpl->parse( 'main.items.price.no_discounts' );
					}
					$xtpl->parse( 'main.items.price' );
				}
				else
				{
					$xtpl->parse( 'main.items.contact' );
				}
				
				
			}
			if( $pro_config['active_tooltip'] == 1 ) $xtpl->parse( 'main.items.tooltip' );

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data_row['id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data_row['id'] ) );
				$xtpl->parse( 'main.items.adminlink' );
			}

            // So sanh san pham
            if( $pro_config['show_compare'] == 1 )
            {
                if( isset( $_SESSION[$module_name . '_array_id'] ) )
                {
                    $array_id = $_SESSION[$module_name . '_array_id'];
                    $array_id = unserialize( $array_id );
                }
                else
                {
                    $array_id = array();
                }
				
	            if( ! empty( $array_id ) )
	            {
	                $ch = ( in_array( $data_row['id'], $array_id ) ) ? ' checked="checked"' : '';
	                $xtpl->assign( 'ch', $ch );
	            }
			
                $xtpl->parse( 'main.items.compare' );
            }
			
			// San pham yeu thich
			if( $pro_config['active_wishlist'] )
			{
				if( ! empty( $array_wishlist_id ) )
				{
					if( in_array( $data_row['id'], $array_wishlist_id ) )
					{
						$xtpl->parse( 'main.items.wishlist.disabled' );
					}
				}
				$xtpl->parse( 'main.items.wishlist' );
			}
			
			if( $data_row['discount_id'] and $price['discount_percent'] > 0 )
			{
				$xtpl->parse( 'main.items.discounts' );
			}
			
			$newday = $data_row['publtime'] + ( 86400 * $data_row['newday'] );
			if ( $newday >= NV_CURRENTTIME )
			{
				$xtpl->parse( 'main.items.newday' );
			}

			$xtpl->parse( 'main.items' );
			++$i;
		}
		if( ! empty( $html_pages ) )
		{
			$xtpl->assign( 'generate_page', $html_pages );
			$xtpl->parse( 'main.pages' );
		}
	}
	if( $pro_config['active_tooltip'] == 1 ) $xtpl->parse( 'main.tooltip_js' );
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * viewcat_page_gird()
 *
 * @param mixed $data_content
 * @param mixed $pages
 * @return
 */
function viewcat_page_gird( $data_content, $pages, $sort = 0 )
{
	global $module_info, $lang_module, $module_file, $module_name, $pro_config, $array_displays, $array_wishlist_id;

	$xtpl = new XTemplate( 'view_gird.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'module_name', $module_file );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'alias', $data_content['alias'] );
	$xtpl->assign( 'catid', $data_content['id'] );
	$xtpl->assign( 'CAT_NAME', $data_content['title'] );
	$xtpl->assign( 'count', $data_content['count'] );
	
	$image = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $data_content['image'];
	if( ! empty( $data_content['image'] ) and file_exists( $image ) )
	{
		$xtpl->assign( 'IMAGE', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $data_content['image'] );
		$xtpl->parse( 'main.image' );
	}

	if( $pro_config['show_displays'] == 1 )
	{
		foreach( $array_displays as $k => $array_displays_i )
		{
			$se = '';
			$xtpl->assign( 'value', $array_displays_i );
			$xtpl->assign( 'key', $k );
			$se = ( $sort == $k ) ? 'selected="selected"' : '';
			$xtpl->assign( 'se', $se );
			$xtpl->parse( 'main.displays.sorts' );
		}
		$xtpl->parse( 'main.displays' );
	}

	if( ! empty( $data_content['data'] ) )
	{
		$i = 1;
		$num_row = $pro_config['per_row'] == 3 ? 4 : 3;
		$xtpl->assign( 'SUM', count( $data_content['data'] ) );

		foreach( $data_content['data'] as $data_row )
		{
			$xtpl->assign( 'id', $data_row['id'] );
			$xtpl->assign( 'title_pro', $data_row['title'] );
			$xtpl->assign( 'title_pro0', nv_clean60( $data_row['title'], 40 ) );
			$xtpl->assign( 'link_pro', $data_row['link_pro'] );
			$xtpl->assign( 'img_pro', $data_row['homeimgthumb'] );
			$xtpl->assign( 'link_order', $data_row['link_order'] );
			$xtpl->assign( 'intro', nv_clean60( $data_row['hometext'], 90 ) );
			$xtpl->assign( 'PRODUCT_CODE', $data_row['product_code'] );
			$xtpl->assign( 'height', $pro_config['homeheight'] );
			$xtpl->assign( 'width', $pro_config['homewidth'] );

			$newday = $data_row['publtime'] + ( 86400 * $data_row['newday'] );
			if ( $newday >= NV_CURRENTTIME )
			{
				$xtpl->parse( 'main.grid_rows.new' );
			}
			
			$price = nv_currency_conversion( $data_row['product_price'], $data_row['money_unit'], $pro_config['money_unit'], $data_row['discount_id'] );
			
			if( $pro_config['active_price'] == '1' )
			{
				if( $data_row['showprice'] == '1' )
				{
					$xtpl->assign( 'PRICE', $price );
					if( $data_row['discount_id'] and $price['discount_percent'] > 0 )
					{
						$xtpl->parse( 'main.grid_rows.price.discounts' );
						$xtpl->parse( 'main.grid_rows.price.discounts.standard' );
					}
					else
					{
						$xtpl->parse( 'main.grid_rows.price.no_discounts' );
					}
					$xtpl->parse( 'main.grid_rows.price' );
				}
				else
				{
					$xtpl->parse( 'main.grid_rows.contact' );
				}
			}

			$xtpl->assign( 'num', $num_row );
			$xtpl->assign( 'height', $pro_config['homeheight'] );
			$xtpl->assign( 'width', $pro_config['homewidth'] );

			if( $pro_config['active_order'] == '1' )
			{
				if( $data_row['showprice'] == '1' )
				{
					if( $data_row['product_number'] > 0 )
					{
						$xtpl->parse( 'main.grid_rows.order' );
					}
					else
					{
						$xtpl->parse( 'main.grid_rows.product_empty' );
					}
				}
			}
			if( $pro_config['active_tooltip'] == 1 ) $xtpl->parse( 'main.grid_rows.tooltip' );

			if( ! empty( $pro_config['show_product_code'] ) and ! empty( $data_row['product_code'] ) )
			{
				$xtpl->parse( 'main.grid_rows.product_code' );
			}

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data_row['id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data_row['id'] ) );
				$xtpl->parse( 'main.grid_rows.adminlink' );
			}

            // So sanh san pham
            if( $pro_config['show_compare'] == 1 )
            {
                if( isset( $_SESSION[$module_name . '_array_id'] ) )
                {
                    $array_id = $_SESSION[$module_name . '_array_id'];
                    $array_id = unserialize( $array_id );
                }
                else
                {
                    $array_id = array();
                }
				
				if( ! empty( $array_id ) )
				{
					$ch = ( in_array( $data_row['id'], $array_id ) ) ? ' checked="checked"' : '';
					$xtpl->assign( 'ch', $ch );
				}
			
                $xtpl->parse( 'main.grid_rows.compare' );
            }

			// San pham yeu thich
			if( $pro_config['active_wishlist'] )
			{
				if( ! empty( $array_wishlist_id ) )
				{
					if( in_array( $data_row['id'], $array_wishlist_id ) )
					{
						$xtpl->parse( 'main.grid_rows.wishlist.disabled' );
					}
				}
				$xtpl->parse( 'main.grid_rows.wishlist' );
			}

			if( $data_row['discount_id'] and $price['discount_percent'] > 0 )
			{
				$xtpl->parse( 'main.grid_rows.discounts' );
			}

			$xtpl->parse( 'main.grid_rows' );
			++$i;
		}
	}
	$xtpl->assign( 'pages', $pages );
	$xtpl->assign( 'LINK_LOAD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=loadcart' );
	if( $pro_config['active_tooltip'] == 1 ) $xtpl->parse( 'main.tooltip_js' );
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * viewcat_page_list()
 *
 * @param mixed $data_content
 * @param mixed $pages
 * @return
 */
function viewcat_page_list( $data_content, $pages, $sort = 0 )
{
	global $module_info, $lang_module, $module_file, $module_name, $pro_config, $array_displays, $array_wishlist_id;

	$xtpl = new XTemplate( 'view_list.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'module_name', $module_file );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'alias', $data_content['alias'] );
	$xtpl->assign( 'catid', $data_content['id'] );
	$xtpl->assign( 'CAT_NAME', $data_content['title'] );
	$xtpl->assign( 'link_order_all', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setcart' );
	$xtpl->assign( 'SUM', count( $data_content['data'] ) );

	if( isset( $data_content['image'] ) )
	{
		$image = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $data_content['image'];
		if( ! empty( $data_content['image'] ) and file_exists( $image ) )
		{
			$xtpl->assign( 'IMAGE', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $data_content['image'] );
			$xtpl->parse( 'main.image' );
		}	
	}

	if( $pro_config['show_compare'] == 1 )
	{
		if( isset( $_SESSION[$module_name . '_array_id'] ) )
		{
			$array_id = $_SESSION[$module_name . '_array_id'];
			$array_id = unserialize( $array_id );
		}
		else
		{
			$array_id = array();
		}
		
		if( ! empty( $array_id ) )
		{
			$ch = ( in_array( $data_row['id'], $array_id ) ) ? ' checked="checked"' : '';
			$xtpl->assign( 'ch', $ch );
		}
		$xtpl->parse( 'main.compare' );
	}

	if( $pro_config['show_displays'] == 1 )
	{
		foreach( $array_displays as $k => $array_displays_i )
		{
			$se = '';
			$xtpl->assign( 'value', $array_displays_i );
			$xtpl->assign( 'key', $k );
			$se = ( $sort == $k ) ? 'selected="selected"' : '';
			$xtpl->assign( 'se', $se );
			$xtpl->parse( 'main.displays.sorts' );
		}
		$xtpl->parse( 'main.displays' );
	}

	$xtpl->assign( 'count', $data_content['count'] );
	if( ! empty( $data_content['data'] ) )
	{
		foreach( $data_content['data'] as $data_row )
		{
			$xtpl->assign( 'id', $data_row['id'] );
			$xtpl->assign( 'title_pro', $data_row['title'] );
			$xtpl->assign( 'link_pro', $data_row['link_pro'] );
			$xtpl->assign( 'img_pro', $data_row['homeimgthumb'] );
			$xtpl->assign( 'link_order', $data_row['link_order'] );
			$xtpl->assign( 'intro', nv_clean60( $data_row['hometext'], 90 ) );
			$xtpl->assign( 'PRODUCT_CODE', $data_row['product_code'] );

			$newday = $data_row['publtime'] + ( 86400 * $data_row['newday'] );
			if ( $newday >= NV_CURRENTTIME )
			{
				$xtpl->parse( 'main.row.new' );
			}

			$price = nv_currency_conversion( $data_row['product_price'], $data_row['money_unit'], $pro_config['money_unit'], $data_row['discount_id'] );
			
			if( $pro_config['active_price'] == '1' )
			{
				if( $data_row['showprice'] == '1' )
				{
					$xtpl->assign( 'PRICE', $price );
					if( $data_row['discount_id'] and $price['discount_percent'] > 0 )
					{
						$xtpl->parse( 'main.row.price.discounts' );
						$xtpl->parse( 'main.row.price.discounts.standard' );
					}
					else
					{
						$xtpl->parse( 'main.row.price.no_discounts' );
					}
					$xtpl->parse( 'main.row.price' );
				}
				else
				{
					$xtpl->parse( 'main.row.contact' );
				}
			}
			$xtpl->assign( 'height', $pro_config['homeheight'] );
			$xtpl->assign( 'width', $pro_config['homewidth'] );
			$xtpl->assign( 'publtime', $lang_module['detail_dateup'] . ' ' . nv_date( 'd-m-Y h:i:s A', $data_row['publtime'] ) );
			if( $pro_config['active_order'] == '1' )
			{
				if( $data_row['showprice'] == '1' )
				{
					if( $data_row['product_number'] > 0 )
					{
						$xtpl->parse( 'main.row.order' );
					}
					else
					{
						$xtpl->parse( 'main.row.product_empty' );
					}
				}
			}

			if( ! empty( $pro_config['show_product_code'] ) and ! empty( $data_row['product_code'] ) )
			{
				$xtpl->parse( 'main.row.product_code' );
			}
			
			// San pham yeu thich
			if( $pro_config['active_wishlist'] )
			{
				if( ! empty( $array_wishlist_id ) )
				{
					if( in_array( $data_row['id'], $array_wishlist_id ) )
					{
						$xtpl->parse( 'main.row.wishlist.disabled' );
					}
				}
				$xtpl->parse( 'main.row.wishlist' );
			}

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data_row['id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data_row['id'] ) );
				$xtpl->parse( 'main.row.adminlink' );
			}
			else
			{
				if( $pro_config['show_compare'] == 1 )
				{
					$xtpl->parse( 'main.row.compare' );
				}
			}
			
			if( $data_row['discount_id'] and $price['discount_percent'] > 0 )
			{
				$xtpl->parse( 'main.row.discounts' );
			}

			$xtpl->parse( 'main.row' );
		}
	}
	$xtpl->assign( 'pages', $pages );
	$xtpl->assign( 'LINK_LOAD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=loadcart' );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * detail_product()
 *
 * @param mixed $data_content
 * @param mixed $data_unit
 * @param mixed $data_others
 * @param mixed $data_shop
 * @param mixed $array_other_view
 * @return
 */
function detail_product( $data_content, $data_unit, $data_shop, $data_others, $array_other_view )
{
	global $module_info, $lang_module, $module_file, $module_name, $my_head, $pro_config, $global_config, $global_array_group, $array_wishlist_id;

	if( ! defined( 'SHADOWBOX' ) )
	{
		$my_head .= "<link rel=\"Stylesheet\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
		$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
		$my_head .= "<script type=\"text/javascript\">Shadowbox.init({ handleOversize: \"drag\" });</script>";
		define( 'SHADOWBOX', true );
	}

	$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';
	$link2 = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=';

	$xtpl = new XTemplate( 'detail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'MODULE', $module_name );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	if( ! empty( $data_content ) )
	{
		$xtpl->assign( 'proid', $data_content['id'] );
		$xtpl->assign( 'SRC_PRO', $data_content['homeimgthumb'] );
		$xtpl->assign( 'SRC_PRO_LAGE', $data_content['homeimgfile'] );
		$xtpl->assign( 'TITLE', $data_content[NV_LANG_DATA . '_title'] );
		$xtpl->assign( 'NUM_VIEW', $data_content['hitstotal'] );
		$xtpl->assign( 'DATE_UP', $lang_module['detail_dateup'] . ' ' . nv_date( 'd-m-Y h:i:s A', $data_content['publtime'] ) );
		$xtpl->assign( 'DETAIL', $data_content[NV_LANG_DATA . '_bodytext'] );
		$xtpl->assign( 'LINK_ORDER', $link2 . 'setcart&id=' . $data_content['id'] );
		$price = nv_currency_conversion( $data_content['product_price'], $data_content['money_unit'], $pro_config['money_unit'], $data_content['discount_id'] );
		$xtpl->assign( 'PRICE', $price );
		$xtpl->assign( 'PRODUCT_CODE', $data_content['product_code'] );
		$xtpl->assign( 'PRODUCT_NUMBER', $data_content['product_number'] );
        $xtpl->assign( 'RATINGDETAIL', $data_content['ratingdetail'] );
        $xtpl->assign( 'PERCENT_RATE', $data_content['percent_rate'] );
        $xtpl->assign( 'RATE_AVG_PERCENT', $data_content['ratefercent_avg'] );
		$xtpl->assign( 'pro_unit', $data_unit['title'] );
		
		if( ! empty( $data_content['homeimgfile'] ) )
		{
			$xtpl->parse( 'main.shadowbox' );
		}

		if( ! empty( $data_content[NV_LANG_DATA . '_promotional'] ) )
		{
			$xtpl->assign( 'promotional', $data_content[NV_LANG_DATA . '_promotional'] );
			$xtpl->parse( 'main.promotional' );
		}
		
		if( ! empty( $data_content[NV_LANG_DATA . '_warranty'] ) )
		{
			$xtpl->assign( 'warranty', $data_content[NV_LANG_DATA . '_warranty'] );
			$xtpl->parse( 'main.warranty' );
		}
		
		if ( ! empty( $data_content['custom'] ) )
		{
			$array_custom = unserialize( $data_content['custom'] );
			foreach( $array_custom as $key => $custom )
			{
				if( ! empty( $custom ) )
				{
					$xtpl->assign( 'custom', array( 'lang' => $lang_module['custom_' . $key], 'title' => $custom ) );
					$xtpl->parse( 'main.custom.loop' );	
				}
			}
			$xtpl->parse( 'main.custom' );
		}

		// San pham yeu thich
		if( $pro_config['active_wishlist'] )
		{
			if( ! empty( $array_wishlist_id ) )
			{
				if( in_array( $data_content['id'], $array_wishlist_id) )
				{
					$xtpl->parse( 'main.wishlist.disabled' );
				}
			}
			$xtpl->parse( 'main.wishlist' );
		}
		
		if ( ! empty( $data_content[NV_LANG_DATA . '_custom'] ) )
		{
			$array_custom_lang = unserialize( $data_content[NV_LANG_DATA . '_custom'] );
			foreach( $array_custom_lang as $key => $custom_lang )
			{
				if( ! empty( $custom_lang ) )
				{
				$xtpl->assign( 'custom_lang', array( 'lang' => $lang_module['custom_' . $key], 'title' => $custom_lang ) );
				$xtpl->parse( 'main.custom_lang.loop' );	
				}
			}
			$xtpl->parse( 'main.custom_lang' );
		}

		$exptime = ( $data_content['exptime'] != 0 ) ? date( 'd-m-Y', $data_content['exptime'] ) : 'N/A';
		$xtpl->assign( 'exptime', $exptime );
		$xtpl->assign( 'height', $pro_config['homeheight'] );
		$xtpl->assign( 'width', $pro_config['homewidth'] );
		$xtpl->assign( 'RATE', $data_content['ratingdetail'] );
		
		if( $pro_config['active_showhomtext'] == '1' )
		{
			$xtpl->assign( 'hometext', $data_content[NV_LANG_DATA . '_hometext'] );
			$xtpl->parse( 'main.hometext' );
		}
		
		if( ! empty( $data_content['otherimage'] ) )
		{
			$otherimage = explode( '|', $data_content['otherimage'] );
		}
		else
		{
			$otherimage = array();
		}
		
		if( ! empty( $otherimage ) )
		{
			foreach( $otherimage as $otherimage_i )
			{
				if( ! empty( $otherimage_i ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $otherimage_i ) )
				{
					$otherimage_i = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $otherimage_i;
					$xtpl->assign( 'IMG_SRC_OTHER', $otherimage_i );
					$xtpl->parse( 'main.othersimg.loop' );
				}
			}
			$xtpl->parse( 'main.othersimg' );
			$xtpl->parse( 'main.othersimg_title' );
		}

		if( ! empty( $pro_config['show_product_code'] ) and ! empty( $data_content['product_code'] ) )
		{
			$xtpl->parse( 'main.product_code' );
		}
	}

	// Nhom san pham
	$listgroupid = GetGroupID( $data_content['id'] );
	$i = 0;
	foreach( $listgroupid as $gid )
	{
		$group_content = '';
		$group = $global_array_group[$gid];
		if( $group['parentid'] == 0 and $group['in_order'] )
		{
			$group_content .= '<select name="group" class="form-control form-group input-sm">';
			$group_content .= '<option value="" selected="selected">' . $group['title'] . '</option>';
			$group_content .= getgroup_selecthtml( $global_array_group, $group['groupid'], $listgroupid );
			$group_content .= '</select>';
			$xtpl->assign( 'GROUP', $group_content );
			$xtpl->parse( 'main.group.items' );
			$i++;
		}
	}
	if( $i > 0 ) $xtpl->parse( 'main.group' );


	// Chi tiet giam gia
	if( isset( $data_shop['discount'] ) and ! empty( $data_shop['discount'] ) )
	{
		$discount = $data_shop['discount'];
		$discount['config'] = unserialize( $discount['config'] );
		$discount['begin_time'] = sprintf( $lang_module['discount_content_begin'], nv_date( 'd/m/Y', $discount['begin_time'] ) );
		if( $discount['end_time'] )
		{
			$discount['end_time'] = sprintf( $lang_module['discount_content_end'], nv_date( 'd/m/Y', $discount['end_time'] ) );
		}
		else
		{
			$discount['end_time'] = '';
		}
		
		$discount['text'] = sprintf( $lang_module['discount_content_text'], $global_config['site_name'], $data_content[NV_LANG_DATA . '_title'] );
		
		foreach( $discount['config'] as $items )
		{
			$xtpl->assign( 'ITEMS', sprintf( $lang_module['discount_content_text_items'], $items['discount_number'], $items['discount_from'], $items['discount_to'] ) );
			$xtpl->parse( 'main.discount_content.items' );
		}
		
		$xtpl->parse( 'main.discount_title' );
		$xtpl->assign( 'DISCOUNT', $discount );
		$xtpl->parse( 'main.discount_content' );
	}

	if( ! empty( $data_others ) )
	{
		$hmtl = view_home_all( $data_others );
		$xtpl->assign( 'OTHER', $hmtl );
		$xtpl->parse( 'main.other' );
	}
	if( ! empty( $array_other_view ) )
	{
		$hmtl = view_home_all( $array_other_view );
		$xtpl->assign( 'OTHER_VIEW', $hmtl );
		$xtpl->parse( 'main.other_view' );
	}

	$xtpl->assign( 'LINK_LOAD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=loadcart' );
	$xtpl->assign( 'THEME_URL', NV_BASE_SITEURL . 'themes/' . $module_info['template'] );
	$xtpl->assign( 'LINK_PRINT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=print_pro&id=' . $data_content['id'] );
	$xtpl->assign( 'LINK_RATE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=rate&id=' . $data_content['id'] );

	if( $pro_config['active_price'] == '1' )
	{
		if( $data_content['showprice'] == '1' )
		{
			if( $data_content['discount_id'] and $price['discount_percent'] > 0 )
			{
				$xtpl->parse( 'main.price.discounts' );
				$xtpl->parse( 'main.price.discounts.standard' );
			}
			else
			{
				$xtpl->parse( 'main.price.no_discounts' );
			}
			$xtpl->parse( 'main.price' );
		} 
		else
		{
			$xtpl->parse( 'main.contact' );
		}
	}
	
	if( $pro_config['active_order'] == '1' )
	{
		if( $data_content['showprice'] == '1' )
		{
			if( $data_content['product_number'] > 0 )
			{
				$xtpl->parse( 'main.order' );
			}
			else
			{
				$xtpl->parse( 'main.product_empty' );
			}
		}
	}

	if( ! empty( $data_content['allowed_rating'] ) )
	{
		$xtpl->parse( 'main.allowed_rating' );
		$xtpl->parse( 'main.allowed_rating_js' );
	}
	if( ! empty( $data_content['allowed_send'] ) ) $xtpl->parse( 'main.allowed_send' );
	if( ! empty( $data_content['allowed_print'] ) )
	{
		$xtpl->parse( 'main.allowed_print' );
		$xtpl->parse( 'main.allowed_print_js' );
	}
	if( ! empty( $data_content['allowed_save'] ) ) $xtpl->parse( 'main.allowed_save' );

	if( defined( 'NV_IS_MODADMIN' ) )
	{
		$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data_content['id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data_content['id'] ) );
		$xtpl->parse( 'main.adminlink' );
	}

	if( defined( 'NV_COMM_URL' ) )
	{
		$xtpl->assign( 'NV_COMM_URL', NV_COMM_URL );
		$xtpl->parse( 'main.comment' );
		$xtpl->parse( 'main.comment_tab' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * print_product()
 *
 * @param mixed $data_content
 * @param mixed $data_unit
 * @param mixed $page_title
 * @return
 */
function print_product( $data_content, $data_unit, $page_title )
{
	global $module_info, $lang_module, $module_file, $global_config, $module_name, $pro_config, $global_array_cat;

	$xtpl = new XTemplate( 'print_pro.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	if( ! empty( $data_content ) )
	{
		$xtpl->assign( 'proid', $data_content['id'] );
		$data_content['money_unit'] = ( $data_content['money_unit'] != '' ) ? $data_content['money_unit'] : 'N/A';
		$data_content[NV_LANG_DATA . '_address'] = ( $data_content[NV_LANG_DATA . '_address'] != '' ) ? $data_content[NV_LANG_DATA . '_address'] : 'N/A';
		$xtpl->assign( 'SRC_PRO', $data_content['homeimgthumb'] );
		$xtpl->assign( 'SRC_PRO_LAGE', $data_content['homeimgthumb'] );
		$xtpl->assign( 'TITLE', $data_content[NV_LANG_DATA . '_title'] );
		$xtpl->assign( 'NUM_VIEW', $data_content['hitstotal'] );
		$xtpl->assign( 'DATE_UP', $lang_module['detail_dateup'] . date( ' d-m-Y ', $data_content['addtime'] ) . $lang_module['detail_moment'] . date( " h:i'", $data_content['addtime'] ) );
		$xtpl->assign( 'DETAIL', $data_content[NV_LANG_DATA . '_bodytext'] );
		$xtpl->assign( 'PRICE', nv_currency_conversion( $data_content['product_price'], $data_content['money_unit'], $pro_config['money_unit'] ) );
		$xtpl->assign( 'money_unit', $pro_config['money_unit'] );
		$xtpl->assign( 'pro_unit', $data_unit['title'] );
		$xtpl->assign( 'address', $data_content[NV_LANG_DATA . '_address'] );
		$xtpl->assign( 'product_number', $data_content['product_number'] );
		$exptime = ( $data_content['exptime'] != 0 ) ? date( 'd-m-Y', $data_content['exptime'] ) : 'N/A';
		$xtpl->assign( 'exptime', $exptime );
		$xtpl->assign( 'height', $pro_config['homeheight'] );
		$xtpl->assign( 'width', $pro_config['homewidth'] );

		$xtpl->assign( 'site_name', $global_config['site_name'] );
		$xtpl->assign( 'url', $global_config['site_url'] );
		$xtpl->assign( 'contact', $global_config['site_email'] );
		$xtpl->assign( 'page_title', $page_title );
	}
	if( $pro_config['active_price'] == '1' ) $xtpl->parse( 'main.price' );
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * cart_product()
 *
 * @param mixed $data_content
 * @param mixed $array_error_number
 * @return
 */
function cart_product( $data_content, $array_error_number )
{
	global $module_info, $lang_module, $module_file, $module_name, $pro_config, $money_config, $global_array_group;

	$xtpl = new XTemplate( 'cart.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$price_total = 0;
	if( ! empty( $data_content ) )
	{
		$i = 1;
		foreach( $data_content as $data_row )
		{
			$xtpl->assign( 'stt', $i );
			$xtpl->assign( 'id', $data_row['id'] );
			$xtpl->assign( 'title_pro', $data_row['title'] );
			$xtpl->assign( 'link_pro', $data_row['link_pro'] );
			$xtpl->assign( 'img_pro', $data_row['homeimgthumb'] );

			$price = nv_currency_conversion( $data_row['product_price'], $data_row['money_unit'], $pro_config['money_unit'], $data_row['discount_id'], $data_row['num'] );
			$xtpl->assign( 'PRICE', $price );
			$xtpl->assign( 'pro_num', $data_row['num'] );
			$xtpl->assign( 'link_remove', $data_row['link_remove'] );
			$xtpl->assign( 'product_unit', $data_row['product_unit'] );

			if( ! empty( $data_row['group'] ) )
			{
				$data_row['group'] = explode( ',', $data_row['group'] );
				foreach( $data_row['group'] as $groupid )
				{
					$xtpl->assign( 'group', $global_array_group[$groupid]['title'] );
					$xtpl->parse( 'main.rows.display_group.group' );
				}
				$xtpl->parse( 'main.rows.display_group' );
			}

			if( $pro_config['active_price'] == '1' ) $xtpl->parse( 'main.rows.price2' );

			$xtpl->parse( 'main.rows' );
			$price_total = $price_total + $price['sale'];
			$i++;
		}
	}
	if( ! empty( $array_error_number ) )
	{
		foreach( $array_error_number as $title_error )
		{
			$xtpl->assign( 'ERROR_NUMBER_PRODUCT', $title_error );
			$xtpl->parse( 'main.errortitle.errorloop' );
		}
		$xtpl->parse( 'main.errortitle' );
	}
	
	$xtpl->assign( 'price_total', nv_number_format( $price_total, $money_config[$pro_config['money_unit']]['decimals'] ) );
	$xtpl->assign( 'unit_config', $pro_config['money_unit'] );
	$xtpl->assign( 'LINK_DEL_ALL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=remove' );
	$xtpl->assign( 'LINK_CART', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart' );
	$xtpl->assign( 'LINK_PRODUCTS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '' );
	$xtpl->assign( 'link_order_all', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=order' );

	if( $pro_config['active_price'] == '1' )
	{
		$xtpl->parse( 'main.price1' );
		$xtpl->parse( 'main.price3' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * uers_order()
 *
 * @param mixed $data_content
 * @param mixed $data_order
 * @param mixed $error
 * @return
 */
function uers_order( $data_content, $data_order, $error )
{
	global $module_info, $lang_module, $module_file, $module_name, $pro_config, $money_config, $global_array_group;

	$xtpl = new XTemplate( 'order.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$price_total = 0;
	$i = 1;
	if( ! empty( $data_content ) )
	{
		foreach( $data_content as $data_row )
		{
			$xtpl->assign( 'id', $data_row['id'] );
			$xtpl->assign( 'title_pro', $data_row['title'] );
			$xtpl->assign( 'link_pro', $data_row['link_pro'] );
			
			if( ! empty( $data_row['group'] ) )
			{
				$data_row['group'] = explode( ',', $data_row['group'] );
				foreach( $data_row['group'] as $groupid )
				{
					$xtpl->assign( 'group', $global_array_group[$groupid]['title'] );
					$xtpl->parse( 'main.rows.display_group.group' );
				}
				$xtpl->parse( 'main.rows.display_group' );
			}

			$price = nv_currency_conversion( $data_row['product_price'], $data_row['money_unit'], $pro_config['money_unit'], $data_row['discount_id'], $data_row['num'] );
			$xtpl->assign( 'PRICE', $price );
			$xtpl->assign( 'pro_no', $i );
			$xtpl->assign( 'pro_num', $data_row['num'] );
			$xtpl->assign( 'product_unit', $data_row['product_unit'] );
			if( $pro_config['active_price'] == '1' ) $xtpl->parse( 'main.rows.price2' );
			$xtpl->parse( 'main.rows' );
			$price_total = $price_total + $price['sale'];
			++$i;
		}
	}
	$xtpl->assign( 'price_total', nv_number_format( $price_total, $money_config[$pro_config['money_unit']]['decimals'] ) );
	$xtpl->assign( 'unit_config', $pro_config['money_unit'] );
	$xtpl->assign( 'DATA', $data_order );
	$xtpl->assign( 'ERROR', $error );
	$xtpl->assign( 'LINK_CART', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart' );

	if( $pro_config['active_price'] == '1' )
	{
		$xtpl->parse( 'main.price1' );
		$xtpl->parse( 'main.price3' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * payment()
 *
 * @param mixed $data_content
 * @param mixed $data_pro
 * @param mixed $url_checkout
 * @param mixed $intro_pay
 * @return
 */
function payment( $data_content, $data_pro, $url_checkout, $intro_pay )
{
	global $module_info, $lang_module, $module_file, $global_config, $module_name, $pro_config, $money_config, $global_array_group;
	$xtpl = new XTemplate( 'payment.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'dateup', date( 'd-m-Y', $data_content['order_time'] ) );
	$xtpl->assign( 'moment', date( "h:i' ", $data_content['order_time'] ) );
	$xtpl->assign( 'DATA', $data_content );
	$xtpl->assign( 'order_id', $data_content['order_id'] );

	$i = 0;
	foreach( $data_pro as $pdata )
	{
		$xtpl->assign( 'product_name', $pdata['title'] );
		$xtpl->assign( 'product_number', $pdata['product_number'] );
		$xtpl->assign( 'product_price', nv_number_format( $pdata['product_price'], $money_config[$pro_config['money_unit']]['decimals'] ) );
		$xtpl->assign( 'money_unit', $pdata['money_unit'] );
		$xtpl->assign( 'product_unit', $pdata['product_unit'] );
		$xtpl->assign( 'link_pro', $pdata['link_pro'] );
		$xtpl->assign( 'pro_no', $i + 1 );
		
		if( ! empty( $pdata['product_group'] ) )
		{
			$pdata['product_group'] = explode( ',', $pdata['product_group'] );
			foreach( $pdata['product_group'] as $groupid )
			{
				$xtpl->assign( 'group', $global_array_group[$groupid]['title'] );
				$xtpl->parse( 'main.loop.display_group.group' );
			}
			$xtpl->parse( 'main.loop.display_group' );
		}

		if( $pro_config['active_price'] == '1' ) $xtpl->parse( 'main.loop.price2' );

		$xtpl->parse( 'main.loop' );
		++$i;
	}
	if( ! empty( $data_content['order_note'] ) )
	{
		$xtpl->parse( 'main.order_note' );
	}
	$xtpl->assign( 'order_total', nv_number_format( $data_content['order_total'], $money_config[$pro_config['money_unit']]['decimals'] ) );
	$xtpl->assign( 'unit', $data_content['unit_total'] );
	if( ! empty( $url_checkout ) )
	{
		$xtpl->assign( 'note_pay', '' );
		foreach( $url_checkout as $value )
		{
			$xtpl->assign( 'DATA_PAYMENT', $value );
			$xtpl->parse( 'main.actpay.payment.paymentloop' );
		}

		$xtpl->parse( 'main.actpay.payment' );
	}

	if( $pro_config['active_payment'] == '1' and $pro_config['active_order'] == '1' and $pro_config['active_price'] == '1' and $pro_config['active_order_number'] == '0' ) $xtpl->parse( 'main.actpay' );
	$xtpl->assign( 'url_finsh', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name );
	$xtpl->assign( 'url_print', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=print&order_id=' . $data_content['order_id'] . '&checkss=' . md5( $data_content['order_id'] . $global_config['sitekey'] . session_id() ) );

    if( ! empty( $intro_pay ) )
    {
        $xtpl->assign( 'intro_pay', $intro_pay );
        $xtpl->parse( 'main.intro_pay' );
    }

	if( $pro_config['active_price'] == '1' )
	{
		$xtpl->parse( 'main.price1' );
		$xtpl->parse( 'main.price3' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * print_pay()
 *
 * @param mixed $data_content
 * @param mixed $data_pro
 * @return
 */
function print_pay( $data_content, $data_pro )
{
	global $module_info, $lang_module, $module_file, $pro_config, $money_config;

	$xtpl = new XTemplate( 'print.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'dateup', date( 'd-m-Y', $data_content['order_time'] ) );
	$xtpl->assign( 'moment', date( "h:i' ", $data_content['order_time'] ) );
	$xtpl->assign( 'DATA', $data_content );
	$xtpl->assign( 'order_id', $data_content['order_id'] );

	$i = 0;
	foreach( $data_pro as $pdata )
	{
		$xtpl->assign( 'product_name', $pdata['title'] );
		$xtpl->assign( 'product_number', $pdata['product_number'] );
		$xtpl->assign( 'product_price', nv_number_format( $pdata['product_price'], $money_config[$pro_config['money_unit']]['decimals'] ) );
		$xtpl->assign( 'product_unit', $pdata['product_unit'] );
		$xtpl->assign( 'link_pro', $pdata['link_pro'] );
		$xtpl->assign( 'pro_no', $i + 1 );
		if( $pro_config['active_price'] == '1' ) $xtpl->parse( 'main.loop.price2' );
		$xtpl->parse( 'main.loop' );
		++$i;
	}
	if( ! empty( $data_content['order_note'] ) )
	{
		$xtpl->parse( 'main.order_note' );
	}
	$xtpl->assign( 'order_total', nv_number_format( $data_content['order_total'], $money_config[$pro_config['money_unit']]['decimals'] ) );
	$xtpl->assign( 'unit', $data_content['unit_total'] );

	$payment = '';
	if( $data_content['transaction_status'] == 4 )
	{
		$payment = $lang_module['history_payment_yes'];
	}
	elseif( $data_content['transaction_status'] == 3 )
	{
		$payment = $lang_module['history_payment_cancel'];
	}
	elseif( $data_content['transaction_status'] == 2 )
	{
		$payment = $lang_module['history_payment_check'];
	}
	elseif( $data_content['transaction_status'] == 1 )
	{
		$payment = $lang_module['history_payment_send'];
	}
	elseif( $data_content['transaction_status'] == 0 )
	{
		$payment = $lang_module['history_payment_no'];
	}
	elseif( $data_content['transaction_status'] == - 1 )
	{
		$payment = $lang_module['history_payment_wait'];
	}
	else
	{
		$payment = 'ERROR';
	}
	$xtpl->assign( 'payment', $payment );
	if( $pro_config['active_price'] == '1' )
	{
		$xtpl->parse( 'main.price1' );
		$xtpl->parse( 'main.price3' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * history_order()
 *
 * @param mixed $data_content
 * @param mixed $link_check_order
 * @return
 */
function history_order( $data_content, $link_check_order )
{
	global $module_info, $lang_module, $module_file, $module_name, $pro_config, $money_config;

	$xtpl = new XTemplate( 'history_order.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$i = 0;

	foreach( $data_content as $data_row )
	{
		$xtpl->assign( 'order_code', $data_row['order_code'] );
		$xtpl->assign( 'history_date', date( 'd-m-Y', $data_row['order_time'] ) );
		$xtpl->assign( 'history_moment', date( "h:i' ", $data_row['order_time'] ) );
		$xtpl->assign( 'history_total', nv_number_format( $data_row['order_total'], $money_config[$pro_config['money_unit']]['decimals'] ) );
		$xtpl->assign( 'unit_total', $data_row['unit_total'] );
		$xtpl->assign( 'note', $data_row['order_note'] );
		$xtpl->assign( 'URL_DEL_BACK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=history' );
		if( intval( $data_row['transaction_status'] ) == - 1 )
		{
			$xtpl->assign( 'link_remove', $data_row['link_remove'] );
            $xtpl->parse( 'main.rows.remove' );
		}
		else
		{
            $xtpl->parse( 'main.rows.no_remove' );
		}
		$xtpl->assign( 'link', $data_row['link'] );

		/* transaction_status: Trang thai giao dich:
		 0 - Giao dich moi tao
		 1 - Chua thanh toan;
		 2 - Da thanh toan, dang bi tam giu;
		 3 - Giao dich bi huy;
		 4 - Giao dich da hoan thanh thanh cong (truong hop thanh toan ngay hoac thanh toan tam giu nhung nguoi mua da phe chuan)
		 */
		if( $data_row['transaction_status'] == 4 )
		{
			$history_payment = $lang_module['history_payment_yes'];
		}
		elseif( $data_row['transaction_status'] == 3 )
		{
			$history_payment = $lang_module['history_payment_cancel'];
		}
		elseif( $data_row['transaction_status'] == 2 )
		{
			$history_payment = $lang_module['history_payment_check'];
		}
		elseif( $data_row['transaction_status'] == 1 )
		{
			$history_payment = $lang_module['history_payment_send'];
		}
		elseif( $data_row['transaction_status'] == 0 )
		{
			$history_payment = $lang_module['history_payment_no'];
		}
		elseif( $data_row['transaction_status'] == - 1 )
		{
			$history_payment = $lang_module['history_payment_wait'];
		}
		else
		{
			$history_payment = 'ERROR';
		}

		$xtpl->assign( 'LINK_CHECK_ORDER', $link_check_order );
		$xtpl->assign( 'history_payment', $history_payment );
		$bg = ( $i % 2 == 0 ) ? 'class="bg"' : '';
		$xtpl->assign( 'bg', $bg );
		$xtpl->assign( 'TT', $i + 1 );
		if( $pro_config['active_price'] == '1' ) $xtpl->parse( 'main.rows.price2' );
		$xtpl->parse( 'main.rows' );
		++$i;
	}
	if( $pro_config['active_price'] == '1' )
	{
		$xtpl->parse( 'main.price1' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * search_theme()
 *
 * @param mixed $key
 * @param mixed $check_num
 * @param mixed $date_array
 * @param mixed $array_cat_search
 * @return
 */
function search_theme( $key, $check_num, $date_array, $array_cat_search )
{
	global $module_name, $module_info, $module_file, $lang_module, $module_name;

	$xtpl = new XTemplate( "search.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );

	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'BASE_URL_SITE', NV_BASE_SITEURL );
	$xtpl->assign( 'TO_DATE', $date_array['to_date'] );
	$xtpl->assign( 'FROM_DATE', $date_array['from_date'] );
	$xtpl->assign( 'KEY', $key );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'OP_NAME', 'search' );

	foreach( $array_cat_search as $search_cat )
	{
		$xtpl->assign( 'SEARCH_CAT', $search_cat );
		$xtpl->parse( 'main.search_cat' );
	}
	for( $i = 0; $i <= 3; $i++ )
	{
		if( $check_num == $i ) $xtpl->assign( 'CHECK' . $i, "selected=\"selected\"" );
		else
			$xtpl->assign( 'CHECK' . $i, "" );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * search_result_theme()
 *
 * @param mixed $key
 * @param mixed $numRecord
 * @param mixed $per_pages
 * @param mixed $pages
 * @param mixed $array_content
 * @param mixed $url_link
 * @param mixed $catid
 * @return
 */
function search_result_theme( $key, $numRecord, $per_pages, $pages, $array_content, $url_link, $catid )
{
	global $module_file, $module_info, $lang_module, $global_array_cat, $pro_config;

	$xtpl = new XTemplate( "search.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );

	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'KEY', $key );

	$xtpl->assign( 'TITLE_MOD', $lang_module['search_modul_title'] );

	if( ! empty( $array_content ) )
	{
		foreach( $array_content as $value )
		{
		    $listcatid = explode( ",", $value['listcatid'] );
			$catid_i = ( $catid > 0 ) ? $catid : end( $listcatid );
			$url = $global_array_cat[$catid_i]['link'] . '/' . $value['alias'] . "-" . $value['id'];
			
			$value['hometext'] = nv_clean60( $value['hometext'], 170 );

			$xtpl->assign( 'LINK', $url );
			$xtpl->assign( 'TITLEROW', BoldKeywordInStr( $value['title'], $key ) );
			$xtpl->assign( 'CONTENT', BoldKeywordInStr( $value['hometext'], $key ) . "..." );
			$xtpl->assign( 'height', $pro_config['homeheight'] );
			$xtpl->assign( 'width', $pro_config['homewidth'] );

			$xtpl->assign( 'IMG_SRC', $value['homeimgthumb'] );
			$xtpl->parse( 'results.result.result_img' );

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $value['id'] ) . "&nbsp;-&nbsp;" . nv_link_delete_page( $value['id'] ) );
				$xtpl->parse( 'results.result.adminlink' );
			}

			$xtpl->parse( 'results.result' );
		}
	}
	if( $numRecord == 0 )
	{
		$xtpl->assign( 'KEY', $key );
		$xtpl->assign( 'INMOD', $lang_module['search_modul_title'] );
		$xtpl->parse( 'results.noneresult' );
	}
	if( $numRecord > $per_pages )// show pages
	{
		$url_link = $_SERVER['REQUEST_URI'];
		$in = strpos( $url_link, '&page' );
		if( $in != 0 ) $url_link = substr( $url_link, 0, $in );
		$generate_page = nv_generate_page( $url_link, $numRecord, $per_pages, $pages );
		$xtpl->assign( 'VIEW_PAGES', $generate_page );
		$xtpl->parse( 'results.pages_result' );
	}
	$xtpl->assign( 'MY_DOMAIN', NV_MY_DOMAIN );
	$xtpl->assign( 'NUMRECORD', $numRecord );
	$xtpl->parse( 'results' );
	return $xtpl->text( 'results' );
}

/**
 * email_new_order()
 *
 * @param mixed $data_content
 * @param mixed $data_pro
 * @return
 */
function email_new_order( $data_content, $data_pro )
{
	global $module_info, $lang_module, $module_file, $pro_config, $global_config, $money_config;

	$xtpl = new XTemplate( "email_new_order.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'dateup', date( "d-m-Y", $data_content['order_time'] ) );
	$xtpl->assign( 'moment', date( "h:i' ", $data_content['order_time'] ) );
	$xtpl->assign( 'DATA', $data_content );
	$xtpl->assign( 'SITE_NAME', $global_config['site_name'] );
	$xtpl->assign( 'SITE_DOMAIN', $global_config['site_url'] );

	$i = 0;
	foreach( $data_pro as $pdata )
	{
		$xtpl->assign( 'product_name', $pdata['title'] );
		$xtpl->assign( 'product_number', $pdata['product_number'] );
		$xtpl->assign( 'product_price', nv_number_format( $pdata['product_price'], $money_config[$pro_config['money_unit']]['decimals'] ) );
		$xtpl->assign( 'product_unit', $pdata['product_unit'] );
		$xtpl->assign( 'pro_no', $i + 1 );

		$bg = ( $i % 2 == 0 ) ? " style=\"background:#f3f3f3;\"" : "";
		$xtpl->assign( 'bg', $bg );

		if( $pro_config['active_price'] == '1' ) $xtpl->parse( 'main.loop.price2' );
		$xtpl->parse( 'main.loop' );
		++$i;
	}

	if( ! empty( $data_content['order_note'] ) )
	{
		$xtpl->parse( 'main.order_note' );
	}

	$xtpl->assign( 'order_total', nv_number_format( $data_content['order_total'], $money_config[$pro_config['money_unit']]['decimals'] ) );
	$xtpl->assign( 'unit', $data_content['unit_total'] );

	if( $pro_config['active_price'] == '1' )
	{
		$xtpl->parse( 'main.price1' );
		$xtpl->parse( 'main.price3' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * compare()
 *
 * @param mixed $data_pro
 * @return
 */
function compare( $data_pro )
{
	global $lang_module, $module_file, $module_info, $pro_config;
	
	$xtpl = new XTemplate( "compare.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'module_name', $module_file );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );

	foreach( $data_pro as $data_row )
	{
		$xtpl->assign( 'title_pro', $data_row['title'] );
		$xtpl->assign( 'link_pro', $data_row['link_pro'] );
		$xtpl->parse( 'main.title' );
		$xtpl->assign( 'link_pro', $data_row['link_pro'] );
		$xtpl->assign( 'img_pro', $data_row['homeimgthumb'] );
		$xtpl->parse( 'main.homeimgthumb' );
		$xtpl->assign( 'intro', nv_clean60( $data_row['hometext'], 200 ) );
		$xtpl->parse( 'main.hometext' );
		$xtpl->assign( 'bodytext', nv_clean60( $data_row['bodytext'], 400 ) );
		$xtpl->parse( 'main.bodytext' );
		$xtpl->assign( 'id', $data_row['id'] );
		$xtpl->parse( 'main.delete' );
		
		if( ! empty( $pro_config['show_product_code'] ) and ! empty( $data_row['product_code'] ) )
		{
			$xtpl->assign( 'product_code', $data_row['product_code'] );
		}
		else
		{
			$xtpl->assign( 'product_code', 'N/A' );
		}
		$xtpl->parse( 'main.product_code' );
		
		$price = nv_currency_conversion( $data_row['product_price'], $data_row['money_unit'], $pro_config['money_unit'], $data_row['discount_id'] );
		if( $pro_config['active_price'] == '1' )
		{
			if( $data_row['showprice'] == '1' )
			{
				$xtpl->assign( 'PRICE', $price );
				$xtpl->parse( 'main.product_price' );
			}
			else
			{
				$xtpl->parse( 'main.contact' );
			}
		}
		
		$xtpl->assign( 'PRICE', $price );
		$xtpl->parse( 'main.discount' );

		$xtpl->assign( 'promotional', $data_row['promotional'] );
		$xtpl->parse( 'main.promotional' );
		
		$xtpl->assign( 'warranty', $data_row['warranty'] );
		$xtpl->parse( 'main.warranty' );
		
		if ( ! empty( $data_row['custom'] ) )
		{
			$array_custom = unserialize( $data_row['custom'] );
			foreach( $array_custom as $key => $custom )
			{
				if( ! empty( $custom ) )
				{
					$xtpl->assign( 'custom', array( 'lang' => $lang_module['custom_' . $key], 'title' => $custom ) );
					$xtpl->parse( 'main.custom_field.custom.loop' );
				}
			}
			$xtpl->parse( 'main.custom_field.custom' );
		}

		if ( ! empty( $data_row[NV_LANG_DATA . '_custom'] ) )
		{
			$array_custom_lang = unserialize( $data_row[NV_LANG_DATA . '_custom'] );
			foreach( $array_custom_lang as $key => $custom_lang )
			{
				if( ! empty( $custom_lang ) )
				{
					$xtpl->assign( 'custom_lang', array( 'lang' => $lang_module['custom_' . $key], 'title' => $custom_lang ) );
					$xtpl->parse( 'main.custom_field.custom_lang.loop' );	
				}
			}
			$xtpl->parse( 'main.custom_field.custom_lang' );
		}
		$xtpl->parse( 'main.custom_field' );

	}
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * wishlist()
 *
 * @param mixed $data_content
 * @param string $html_pages
 * @return
 */
function wishlist( $data_content, $html_pages = '' )
{
	global $module_info, $lang_module, $module_file, $pro_config, $op, $array_displays, $array_wishlist_id, $module_name;

	$xtpl = new XTemplate( 'wishlist.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	
	$xtpl->assign( 'LANG', $lang_module );

	$xtpl->assign( 'CSS_PRODUCT_CODE', ! empty( $pro_config['show_product_code'] ) ? ' show-product-code' : '' );

	if( ! empty( $data_content ) )
	{
		foreach( $data_content as $data_row )
		{
			$xtpl->assign( 'ID', $data_row['id'] );
			$xtpl->assign( 'LINK', $data_row['link_pro'] );
			$xtpl->assign( 'TITLE', $data_row['title'] );
			$xtpl->assign( 'TITLE0', nv_clean60( $data_row['title'], 40 ) );
			$xtpl->assign( 'IMG_SRC', $data_row['homeimgthumb'] );
			$xtpl->assign( 'LINK_ORDER', $data_row['link_order'] );
			$xtpl->assign( 'height', $pro_config['homeheight'] );
			$xtpl->assign( 'width', $pro_config['homewidth'] );
			$xtpl->assign( 'hometext', nv_clean60( $data_row['hometext'], 115 ) );
			$xtpl->assign( 'PRODUCT_CODE', $data_row['product_code'] );

			$newday = $data_row['publtime'] + ( 86400 * $data_row['newday'] );
			if ( $newday >= NV_CURRENTTIME )
			{
				$xtpl->parse( 'main.items.new' );
			}

			if( $pro_config['active_order'] == '1' )
			{
				if( $data_row['showprice'] == '1' )
				{
					if( $data_row['product_number'] > 0 )
					{
						$xtpl->parse( 'main.items.order' );
					}
					else
					{
						$xtpl->parse( 'main.items.product_empty' );
					}
				}
			}

			if( $pro_config['active_price'] == '1' )
			{
				if( $data_row['showprice'] == '1' )
				{
					$price = nv_currency_conversion( $data_row['product_price'], $data_row['money_unit'], $pro_config['money_unit'], $data_row['discount_id'] );
					$xtpl->assign( 'PRICE', $price );
					if( $data_row['discount_id'] and $price['discount_percent'] > 0 )
					{
						$xtpl->parse( 'main.items.price.discounts' );
						$xtpl->parse( 'main.items.price.discounts.standard' );
					}
					else
					{
						$xtpl->parse( 'main.items.price.no_discounts' );
					}
					$xtpl->parse( 'main.items.price' );
				}
				else
				{
					$xtpl->parse( 'main.items.contact' );
				}
			}
			
			if( $pro_config['active_tooltip'] == 1 ) $xtpl->parse( 'main.items.tooltip' );

			if( ! empty( $pro_config['show_product_code'] ) and ! empty( $data_row['product_code'] ) )
			{
				$xtpl->parse( 'main.items.product_code' );
			}

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data_row['id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data_row['id'] ) );
				$xtpl->parse( 'main.items.adminlink' );
			}

            // So sanh san pham
            if( $pro_config['show_compare'] == 1 )
            {
                if( isset( $_SESSION[$module_name . '_array_id'] ) )
                {
                    $array_id = $_SESSION[$module_name . '_array_id'];
                    $array_id = unserialize( $array_id );
                }
                else
                {
                    $array_id = array();
                }
				
	            if( ! empty( $array_id ) )
	            {
	                $ch = ( in_array( $data_row['id'], $array_id ) ) ? ' checked="checked"' : '';
	                $xtpl->assign( 'ch', $ch );
	            }
			
                $xtpl->parse( 'main.items.compare' );
            }

			if( $data_row['discount_id'] and $price['discount_percent'] > 0 )
			{
				$xtpl->parse( 'main.items.discounts' );
			}

			$xtpl->parse( 'main.items' );
		}

		if( ! empty( $html_pages ) )
		{
			$xtpl->assign( 'generate_page', $html_pages );
			$xtpl->parse( 'main.pages' );
		}
	}
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
