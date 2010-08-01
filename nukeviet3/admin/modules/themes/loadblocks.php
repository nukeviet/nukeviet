<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );
$type = filter_text_input( 'type', 'get', '', 1 );
$bid = $nv_Request->get_int( 'bid', 'get' );
list( $file ) = $db->sql_fetchrow( $db->sql_query( "SELECT file_path FROM `" . NV_BLOCKS_TABLE . "` WHERE bid=" . $bid . "" ) );
if ( $type == 'global' )
{
	$block_file_list = nv_scandir( NV_ROOTDIR . "/includes/blocks", $global_config['check_block_global'] );
	foreach ( $block_file_list as $value )
	{
		$sel = ( $file == $value ) ? ' selected' : '';
		echo "<option value=\"" . $value . "\" " . $sel . ">" . $value . "</option>\n";
	}
} elseif ( isset( $site_mods[$type] ) )
{
	$module_file = $site_mods[$type]['module_file'];
	if ( file_exists( NV_ROOTDIR . "/modules/" . $module_file . '/blocks' ) )
	{
		$block_file_list = nv_scandir( NV_ROOTDIR . "/modules/" . $module_file . '/blocks', $global_config['check_block_module'] );
		foreach ( $block_file_list as $value )
		{
			$sel = ( $file == $value ) ? ' selected' : '';
			echo "<option value=\"" . $value . "\" " . $sel . ">" . $value . "</option>\n";
		}
	}
}

?>