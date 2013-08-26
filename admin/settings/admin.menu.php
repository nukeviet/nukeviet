<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2013 VINADES.,JSC. All rights reserved
 * @createdate 07/30/2013 10:27
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

$submenu['main'] = $lang_module['site_config'];
if( defined( 'NV_IS_GODADMIN' ) OR ( defined( "NV_IS_SPADMIN" ) AND $global_config['idsite'] > 0 ) )
{
	$submenu['system'] = $lang_module['global_config'];
}
$submenu['smtp'] = $lang_module['smtp_config'];
if( defined( 'NV_IS_GODADMIN' ) )
{
	$submenu['security'] = $lang_module['security'];
	$submenu['cronjobs'] = $lang_global['mod_cronjobs'];
	$submenu['ftp'] = $lang_module['ftp_config'];
	$submenu['variables'] = $lang_module['variables'];
}

?>