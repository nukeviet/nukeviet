<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 9/9/2010, 6:38
 */

if( ! defined( 'NV_IS_FILE_WEBTOOLS' ) ) die( 'Stop!!!' );

Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=clearsystem' );
exit();

?>