<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 05 Jun 2015 08:59:50 GMT
 */

 if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );
 
// Khai báo thêm vào menu
$submenu['tools'] =  $lang_module['tools'];
 
 // Khai báo thêm vào allow_func
$allow_func[] = 'tools';