<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_FILE_SITEINFO' ) ) die( 'Stop!!!' );

$lang_siteinfo = nv_get_lang_module( $mod );

// So lien he chua doc
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_send` where `is_read`= 0" ) );
if( $number > 0 )
{
	$pendinginfo[] = array( 'key' => $lang_siteinfo['siteinfo_new'], 'value' => $number, 'link' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $mod );
}

?>