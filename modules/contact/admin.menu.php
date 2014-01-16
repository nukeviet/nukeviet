<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

if( defined( 'NV_IS_GODADMIN' ) )
{
	$submenu['list_row'] = $lang_module['list_row_title'];
	$submenu['content'] = $lang_module['content'];
	$allow_func = array( 'main', 'reply', 'del', 'list_row', 'row', 'del_row', 'content', 'view', 'change_status' );
}
else
{
	$allow_func = array( 'main', 'reply', 'del', 'view' );
}

?>