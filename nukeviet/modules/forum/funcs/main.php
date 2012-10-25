<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:33
 */

if( ! defined( 'NV_IS_MOD_FORUM' ) ) die( 'Stop!!!' );

if( is_dir( NV_ROOTDIR . "/" . DIR_FORUM ) )
{
	Header( "Location: " . $global_config['site_url'] . "/" . DIR_FORUM . "/index.php" );
	exit();
}
else
{
	Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=users" );
	exit();
}

?>