<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 10/01/2011, 13:03
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$rewrite["#([\"|\']" . NV_BASE_SITEURL . "index.php*\?)" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*#"] = "\\1\\3";

$rewrite["#([\"|\']" . NV_BASE_SITEURL . "index.php)*\?" . NV_LANG_VARIABLE . "=([a-z-]*)([\"|\'])#"] = "\\1\\3";

$rewrite["#([\"|\'|\>]" . $global_config['site_url'] . "/" . "index.php*\?)" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*#"] = "\\1\\3";
$rewrite["#([\"|\'|\>]" . $global_config['site_url'] . "/" . "index.php)*\?" . NV_LANG_VARIABLE . "=([a-z-]*)([\"|\'|\<])#"] = "\\1\\3";

?>