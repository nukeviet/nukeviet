<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 18:6
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! function_exists( 'nv_cart_info' ) )
{
	/**
	 * nv_cart_info()
	 * 
	 * @param mixed $block_config
	 * @return
	 */
	function nv_cart_info ( $block_config )
	{
		$module = $block_config['module'];

		$content = '
		<div class="block clearfix">
			<div class="block_cart clearfix" id="cart_' . $module . '"></div>
			<script type="text/javascript">
			$("#cart_' . $module . '").load("' . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&" . NV_OP_VARIABLE . "=loadcart" . '");
			</script>
		</div>
		';
		return $content;
	}
}

if ( defined( 'NV_SYSTEM' ) )
{
	$content = nv_cart_info( $block_config );
}