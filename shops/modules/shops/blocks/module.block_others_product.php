<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

if( ! function_exists( 'nv_others_product' ) )
{
	/**
	 * nv_others_product()
	 * 
	 * @return
	 */
	function nv_others_product()
	{
		global $op;

		if( $op == "detail" )
		{
			global $module_name, $lang_module, $module_info, $module_file, $global_array_cat, $db, $module_data, $db_config, $id, $catid, $pro_config;

			$xtpl = new XTemplate( "block.others_product.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
			$xtpl->assign( 'LANG', $lang_module );
			$xtpl->assign( 'THEME_TEM', NV_BASE_SITEURL . "themes/" . $module_info['template'] );

			$sql = "SELECT `id`, `listcatid`, `" . NV_LANG_DATA . "_title`, `" . NV_LANG_DATA . "_alias` ,`addtime`, `homeimgthumb`, `product_price`, `product_discounts`, `money_unit`, `showprice` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `status`=1 AND `listcatid` = " . $catid . " AND `id` < " . $id . " ORDER BY `id` DESC LIMIT 0,20";
			$result = $db->sql_query( $sql );

			$i = 1;
			while( list( $id_i, $listcatid_i, $title_i, $alias_i, $addtime_i, $homeimgthumb_i, $product_price_i, $product_discounts_i, $money_unit_i, $showprice_i ) = $db->sql_fetchrow( $result ) )
			{
				$thumb = explode( "|", $homeimgthumb_i );
				if( ! empty( $thumb[0] ) and ! nv_is_url( $thumb[0] ) )
				{
					$thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $thumb[0];
				}
				else
				{
					$thumb[0] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/no-image.jpg";
				}
				$xtpl->assign( 'link', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$listcatid_i]['alias'] . "/" . $alias_i . "-" . $id_i );
				$xtpl->assign( 'title', $title_i );
				$xtpl->assign( 'src_img', $thumb[0] );
				$xtpl->assign( 'time', nv_date( 'd-m-Y h:i:s A', $addtime_i ) );
				if( $pro_config['active_price'] == '1' and $showprice_i == '1' )
				{
					$product_price = CurrencyConversion( $product_price_i, $money_unit_i, $pro_config['money_unit'] );
					$xtpl->assign( 'product_price', $product_price );
					$xtpl->assign( 'money_unit', $pro_config['money_unit'] );
					if( $product_discounts_i != 0 )
					{
						$price_product_discounts = $product_price_i - ( $product_price_i * ( $product_discounts_i / 100 ) );
						$xtpl->assign( 'product_discounts', CurrencyConversion( $price_product_discounts, $money_unit_i, $pro_config['money_unit'] ) );
						$xtpl->assign( 'class_money', 'discounts_money' );
						$xtpl->parse( 'main.loop.discounts' );
					}
					else
					{
						$xtpl->assign( 'class_money', 'money' );
					}
					$xtpl->parse( 'main.loop.price' );
				}
				$bg = ( $i % 2 == 0 ) ? "bg" : "";
				$xtpl->assign( "bg", $bg );
				$xtpl->parse( 'main.loop' );
				$i++;
			}

			$xtpl->parse( 'main' );
			return $xtpl->text( 'main' );
		}
	}
}

$content = nv_others_product();

?>