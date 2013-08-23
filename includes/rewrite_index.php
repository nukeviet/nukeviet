<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( $global_config['rewrite_optional'] )
{
	if( $global_config['rewrite_op_mod'] != '' )
	{
		$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=" . $global_config['rewrite_op_mod'] . "\&[amp;]*" . NV_OP_VARIABLE . "=tag/([^\"]+)\"#"] = "\\1index.php/tag/\\3\"";
		$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=" . $global_config['rewrite_op_mod'] . "\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)" . $global_config['rewrite_exturl'] . "\"#"] = "\\1index.php/\\3" . $global_config['rewrite_exturl'] . "\"";
		$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=" . $global_config['rewrite_op_mod'] . "\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)\"#"] = "\\1index.php/\\3" . $global_config['rewrite_endurl'] . "\"";
	}
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-]+)\&[amp;]*" . NV_OP_VARIABLE . "=tag/([^\"]+)\"#"] = "\\1index.php/\\3/tag/\\4\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-]+)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)" . $global_config['rewrite_exturl'] . "\"#"] = "\\1index.php/\\3/\\4" . $global_config['rewrite_exturl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-]+)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)\"#"] = "\\1index.php/\\3/\\4" . $global_config['rewrite_endurl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-]+)\"#"] = "\\1index.php/\\3" . $global_config['rewrite_endurl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\"#"] = "\\1\"";
}
else
{
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-]+)\&[amp;]*" . NV_OP_VARIABLE . "=tag/([^\"]+)\"#"] = "\\1index.php/\\2/\\3/tag/\\4\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-]+)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)" . $global_config['rewrite_exturl'] . "\"#"] = "\\1index.php/\\2/\\3/\\4" . $global_config['rewrite_exturl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-]+)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)\"#"] = "\\1index.php/\\2/\\3/\\4" . $global_config['rewrite_endurl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-]+)\"#"] = "\\1index.php/\\2/\\3" . $global_config['rewrite_endurl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\"#"] = "\\1index.php/\\2" . $global_config['rewrite_endurl'] . "\"";
}

?>