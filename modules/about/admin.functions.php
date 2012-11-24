<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$submenu['content'] = $lang_module['aabout1'];

$allow_func = array( 'main', 'content', 'alias', 'change_status', 'change_weight', 'del' );

define( 'NV_IS_FILE_ADMIN', true );

?>