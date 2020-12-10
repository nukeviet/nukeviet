<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:11:28 AM 
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$submenu['add'] = $lang_module['add'];

$allow_func = array( 
    'main', 'add' , 'del' , 'delall'
);

define( 'NV_IS_OCHU_ADMIN', true );

?>