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
		$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=" . $global_config['rewrite_op_mod'] . "\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)" . $global_config['rewrite_exturl'] . "\"#"] = "\\1\\3" . $global_config['rewrite_exturl'] . "\"";
		$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=" . $global_config['rewrite_op_mod'] . "\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)\"#"] = "\\1\\3" . $global_config['rewrite_endurl'] . "\"";
	}
	$rewrite["#(\"" . NV_BASE_SITEURL . ")([a-zA-Z0-9-/]+)/search/([^\"]+)?\"#"] = "\\1\\2/search" . $global_config['rewrite_endurl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]+)\&[amp;]*" . NV_OP_VARIABLE . "=search\&[amp;]*q=([^\"]+)\"#"] = "\\1\\3/search/\\4" . $global_config['rewrite_endurl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]+)\&[amp;]*" . NV_OP_VARIABLE . "=search/([^\"]+)\"#"] = "\\1\\3/search/\\4" . $global_config['rewrite_endurl'] . "\"";//phan trang module news
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=search\&[amp;]*q=([^\"]+)?\"#"] = "\\1\\2/search/\\3" . $global_config['rewrite_endurl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]+)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)" . $global_config['rewrite_exturl'] . "\"#"] = "\\1\\3/\\4" . $global_config['rewrite_exturl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]+)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)\"#"] = "\\1\\3/\\4" . $global_config['rewrite_endurl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]+)\"#"] = "\\1\\3" . $global_config['rewrite_endurl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\"#"] = "\\1\"";
}
else
{
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]+)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)" . $global_config['rewrite_exturl'] . "\"#"] = "\\1\\2/\\3/\\4" . $global_config['rewrite_exturl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]+)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)\"#"] = "\\1\\2/\\3/\\4" . $global_config['rewrite_endurl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]+)\"#"] = "\\1\\2/\\3" . $global_config['rewrite_endurl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\"#"] = "\\1\\2" . $global_config['rewrite_endurl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")([a-z-]+)/([a-zA-Z0-9-/]+)/search/([^\"]+)\"#"] = "\\1\\2/\\3/search/\\4" . $global_config['rewrite_endurl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")(index.php)/([a-z-]+)/([a-zA-Z0-9-/]+)/search/([^\"]+)\"#"] = "\\1\\2/\\3/\\4/search/\\5" . $global_config['rewrite_endurl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]+)\&[amp;]*" . NV_OP_VARIABLE . "=search\&[amp;]*q=([^\"]+)\"#"] = "\\1\\2/\\3/search/\\4" . $global_config['rewrite_endurl'] . "\"";//module news
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]+)\&[amp;]*" . NV_OP_VARIABLE . "=search/([^\"]+)\"#"] = "\\1\\2/\\3/search/\\4" . $global_config['rewrite_endurl'] . "\"";//phan trang module news
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=search\&[amp;]*q=([^\"]+)\"#"] = "\\1\\2/search/\\3" . $global_config['rewrite_endurl'] . "\"";//module search
		
}

?>