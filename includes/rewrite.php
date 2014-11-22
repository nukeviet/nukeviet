<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

// Rewrite with config in .htaccess or web.config

// Rewrite with no lang variable
if( $global_config['rewrite_optional'] )
{
	// Rewrite module page
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=page\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-]+)" . $global_config['rewrite_exturl'] . "\"#"] = "\\1\\3" . $global_config['rewrite_exturl'] . "\"";

	// Rewrite to remove module name on url
	if( $global_config['rewrite_op_mod'] != '' )
	{
		// Search
		$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=" . $global_config['rewrite_op_mod'] . "\&[amp;]*q\=([^\"]+)\"#"] = "\\1q=\\3\"";
		$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=" . $global_config['rewrite_op_mod'] . "\&[amp;]*" . NV_OP_VARIABLE . "=search\&[amp;]*q\=([^\"]+)\"#"] = "\\1search/q=\\3\"";

		// Tags
		$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=" . $global_config['rewrite_op_mod'] . "\&[amp;]*" . NV_OP_VARIABLE . "=tag/([^\"]+)\"#"] = "\\1tag/\\3\"";

		// Module
		$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=" . $global_config['rewrite_op_mod'] . "\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)" . $global_config['rewrite_exturl'] . "\"#"] = "\\1\\3" . $global_config['rewrite_exturl'] . "\"";
		$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=" . $global_config['rewrite_op_mod'] . "\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)\"#"] = "\\1\\3" . $global_config['rewrite_endurl'] . "\"";
	}

	// Rewrite search url
	if( $global_config['rewrite_op_mod'] != 'seek' )
	{
		$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=seek\&[amp;]*q\=([^\"]+)\"#"] = "\\1seek/q=\\3\"";	
	}
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-]+)\&[amp;]*" . NV_OP_VARIABLE . "=search\&[amp;]*q\=([^\"]+)\"#"] = "\\1\\3/search/q=\\4\"";

	// Rewrite tag url
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-]+)\&[amp;]*" . NV_OP_VARIABLE . "=tag/([^\"]+)\"#"] = "\\1\\3/tag/\\4\"";

	// Rewrite module has funcs url
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-]+)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)" . $global_config['rewrite_exturl'] . "\"#"] = "\\1\\3/\\4" . $global_config['rewrite_exturl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-]+)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)\"#"] = "\\1\\3/\\4" . $global_config['rewrite_endurl'] . "\"";

	// Rewrite module url
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]+)\"#"] = "\\1\\3" . $global_config['rewrite_endurl'] . "\"";

	// Rewrite site url
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\"#"] = "\\1\"";
}
// Rewrite with lang variable
else
{
	// Rewrite search url
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=seek\&[amp;]*q\=([^\"]+)\"#"] = "\\1\\2/seek/q=\\3\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-]+)\&[amp;]*" . NV_OP_VARIABLE . "=search\&[amp;]*q\=([^\"]+)\"#"] = "\\1\\2/\\3/search/q=\\4\"";

	// Rewrite tag url
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-]+)\&[amp;]*" . NV_OP_VARIABLE . "=tag/([^\"]+)\"#"] = "\\1\\2/\\3/tag/\\4\"";

	// Rewrite module has funcs url
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-]+)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)" . $global_config['rewrite_exturl'] . "\"#"] = "\\1\\2/\\3/\\4" . $global_config['rewrite_exturl'] . "\"";
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-]+)\&[amp;]*" . NV_OP_VARIABLE . "=([a-zA-Z0-9-/]+)\"#"] = "\\1\\2/\\3/\\4" . $global_config['rewrite_endurl'] . "\"";

	// Rewrite module url
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\&[amp;]*" . NV_NAME_VARIABLE . "=([a-zA-Z0-9-/]+)\"#"] = "\\1\\2/\\3" . $global_config['rewrite_endurl'] . "\"";

	// Rewrite site url
	$rewrite["#(\"" . NV_BASE_SITEURL . ")index.php\?" . NV_LANG_VARIABLE . "=([a-z-]+)\"#"] = "\\1\\2" . $global_config['rewrite_endurl'] . "\"";
}