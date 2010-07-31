<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$rewrite = array();
if ( $global_config['rewrite_optional'] && $global_config['is_url_rewrite'] )
{
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")[index.php]*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]*)\&[amp;]*id=([0-9]*)([\"|\'])#"] = "\\1\\3/\\4/\\5/\\6";
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")[index.php]*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'])#"] = "\\1\\3/\\4/\\5";
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")[index.php]*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'])#"] = "\\1\\3/\\4";
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")[index.php]*\?" . NV_LANG_VARIABLE . "=([a-z-]*)([\"|\'])#"] = "\\1\\3";

    $rewrite["#([\"|\']" . $global_config['site_url'] . NV_BASE_SITEURL . ")[index.php]*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'])#"] = "\\1\\3/\\4/\\5\\6";
}
else
{
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")[index.php]*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]*)\&[amp;]*id=([0-9]*)([\"|\'])#"] = "\\1\\2/\\3/\\4/\\5/\\6";
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")[index.php]*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'])#"] = "\\1\\2/\\3/\\4/\\5";
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")[index.php]*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'])#"] = "\\1\\2/\\3/\\4";
    $rewrite["#([\"|\']" . NV_BASE_SITEURL . ")[index.php]*\?" . NV_LANG_VARIABLE . "=([a-z-]*)([\"|\'])#"] = "\\1\\2/\\3";

    $rewrite["#([\"|\']" . $global_config['site_url'] . NV_BASE_SITEURL . ")[index.php]*\?" . NV_LANG_VARIABLE . "=([a-z-]*)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]*)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]*)([\"|\'])#"] = "\\1\\2/\\3/\\4/\\5\\6";
}

?>