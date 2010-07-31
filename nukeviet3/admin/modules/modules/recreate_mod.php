<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-17-2010 0:5
 */

if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$contents = 'NO_' . $module_name;
$module_name = filter_text_input( 'mod', 'post');
if ( ! empty( $module_name ) and preg_match( $global_config['check_module'], $module_name ) )
{
	$contents = nv_setup_data_module( NV_LANG_DATA, $module_name );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>