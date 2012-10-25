<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:13
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * nv_db_mods()
 *
 * @return
 */
function nv_site_mods()
{
	global $db, $admin_info;
	$site_mods = array();
	$sql = "SELECT * FROM `" . NV_MODULES_TABLE . "` ORDER BY `weight` ASC";
	$list = nv_db_cache( $sql, '', 'modules' );
	foreach( $list as $row )
	{
		$allowed = false;
		if( defined( 'NV_IS_SPADMIN' ) )
		{
			$allowed = true;
		}
		elseif( defined( 'NV_IS_ADMIN' ) and ! empty( $row['admins'] ) and in_array( $admin_info['admin_id'], explode( ",", $row['admins'] ) ) )
		{
			$allowed = true;
		}
		if( $allowed )
		{
			$row['title'] = $db->unfixdb( $row['title'] );
			
			$site_mods[$row['title']] = array(
				'module_file' => $db->unfixdb( $row['module_file'] ),
				'module_data' => $db->unfixdb( $row['module_data'] ),
				'custom_title' => empty( $row['admin_title'] ) ? $row['custom_title'] : $row['admin_title'],
				'main_file' => $row['main_file'],
				'admin_file' => $row['admin_file'],
				'theme' => $db->unfixdb( $row['theme'] ),
				'keywords' => $row['keywords'],
				'groups_view' => $row['groups_view'],
				'in_menu' => intval( $row['in_menu'] ),
				'submenu' => intval( $row['submenu'] ),
				'act' => intval( $row['act'] ),
				'admins' => $row['admins'],
				'rss' => $row['rss'] );
		}
	}

	return $site_mods;
}

/**
 * nv_groups_list()
 *
 * @return
 */
function nv_groups_list()
{
	global $db;
	$query = "SELECT `group_id`, `title` FROM `" . NV_GROUPS_GLOBALTABLE . "` ORDER BY `weight`";
	$result = $db->sql_query( $query );
	$groups = array();
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$groups[$row['group_id']] = $row['title'];
	}
	return $groups;
}

function nv_save_file_config_global()
{
	global $db;

	$content_config = "<?php\n\n";
	$content_config .= NV_FILEHEAD . "\n\n";
	$content_config .= "if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );\n\n";

	$sql = "SELECT `config_name`, `config_value` FROM `" . NV_CONFIG_GLOBALTABLE . "` WHERE `lang`='sys' ORDER BY `config_name` ASC";
	$result = $db->sql_query( $sql );
	while( list( $c_config_name, $c_config_value ) = $db->sql_fetchrow( $result ) )
	{
		if( ! is_numeric( $c_config_value ) || ( isset( $c_config_value{1} ) and ( $c_config_value{0} == '0' or $c_config_value{0} == '.' ) ) )
		{
			$content_config .= "\$global_config['" . $c_config_name . "'] = \"" . nv_htmlspecialchars( $c_config_value ) . "\";\n";
		}
		else
		{
			$content_config .= "\$global_config['" . $c_config_name . "'] = " . intval( $c_config_value ) . ";\n";
		}
	}
	$content_config .= "\n";
	$content_config .= "?>";

	$return = file_put_contents( NV_ROOTDIR . "/" . NV_DATADIR . "/config_global.php", $content_config, LOCK_EX );
	nv_delete_all_cache();

	return $return;
}

/**
 * nv_rand_getVersion()
 *
 * @param mixed $nv_sites
 * @param mixed $getContent
 * @param bool $is_modules
 * @return
 */
function nv_rand_getVersion( $nv_sites, $getContent, $is_modules = false )
{
	srand( ( float )microtime() * 10000000 );
	$rand = array_rand( $nv_sites );
	$nv_site = $nv_sites[$rand];

	if( $is_modules )
	{
		$content = $getContent->get( "http://" . $nv_site . "/nukeviet.version.xml?module=all&lang=" . NV_LANG_INTERFACE );
	}
	else
	{
		$content = $getContent->get( "http://" . $nv_site . "/nukeviet.version.xml?lang=" . NV_LANG_INTERFACE );
	}

	unset( $nv_sites[$rand] );
	if( empty( $content ) and ! empty( $nv_sites ) )
	{
		$nv_sites = array_values( $nv_sites );
		$content = nv_rand_getVersion( $nv_sites, $getContent, $is_modules );
	}

	return $content;
}

/**
 * nv_geVersion()
 *
 * @param integer $updatetime
 * @return
 */
function nv_geVersion( $updatetime = 3600 )
{
	global $global_config;

	$my_file = NV_ROOTDIR . '/' . NV_CACHEDIR . '/nukeviet.version.' . NV_LANG_INTERFACE . '.xml';

	$xmlcontent = false;

	$p = NV_CURRENTTIME - $updatetime;

	if( file_exists( $my_file ) and @filemtime( $my_file ) > $p )
	{
		$xmlcontent = simplexml_load_file( $my_file );
	}
	else
	{
		include ( NV_ROOTDIR . "/includes/class/geturl.class.php" );
		$getContent = new UrlGetContents( $global_config, 6 );

		$nv_sites = array( //
			'update.nukeviet.vn', //
			'update2.nukeviet.vn', //
			'update.nukeviet.info', //
			'update2.nukeviet.info' );

		$content = nv_rand_getVersion( $nv_sites, $getContent, false );

		if( ! empty( $content ) )
		{
			$xmlcontent = simplexml_load_string( $content );
			if( $xmlcontent !== false )
			{
				file_put_contents( $my_file, $content );
			}
		}
	}

	return $xmlcontent;
}

function nv_version_compare( $version1, $version2 )
{
	$v1 = explode( '.', $version1 );
	$v2 = explode( '.', $version2 );

	if( $v1[0] > $v2[0] )
	{
		return 1;
	}

	if( $v1[0] < $v2[0] )
	{
		return - 1;
	}

	if( $v1[1] > $v2[1] )
	{
		return 1;
	}

	if( $v1[1] < $v2[1] )
	{
		return - 1;
	}

	if( $v1[2] > $v2[2] )
	{
		return 1;
	}

	if( $v1[2] < $v2[2] )
	{
		return - 1;
	}

	return 0;
}

/**
 * nv_rewrite_change()
 *
 * @param mixed $rewrite_optional
 * @return
 */
function nv_rewrite_change( $array_config_global )
{
	global $sys_info, $lang_module;
	$rewrite_rule = $filename = '';

	$endurl = ( $array_config_global['rewrite_endurl'] == $array_config_global['rewrite_exturl'] ) ? nv_preg_quote( $array_config_global['rewrite_endurl'] ) : nv_preg_quote( $array_config_global['rewrite_endurl'] ) . "|" . nv_preg_quote( $array_config_global['rewrite_exturl'] );

	if( $sys_info['supports_rewrite'] == "rewrite_mode_iis" )
	{
		$filename = NV_ROOTDIR . "/web.config";
		$rulename = 0;
		$rewrite_rule .= "\n";
		$rewrite_rule .= "                <rule name=\"nv_rule_" . ++$rulename . "\">\n";
		$rewrite_rule .= "                    <match url=\"^\" ignoreCase=\"false\" />\n";
		$rewrite_rule .= "                    <conditions>\n";
		$rewrite_rule .= "                    		<add input=\"{REQUEST_FILENAME}\" pattern=\"/robots.txt$\" />\n";
		$rewrite_rule .= "                    </conditions>\n";
		$rewrite_rule .= "                    <action type=\"Rewrite\" url=\"robots.php?action={HTTP_HOST}\" appendQueryString=\"false\" />\n";
		$rewrite_rule .= "                </rule>\n";
		$rewrite_rule .= "                <rule name=\"nv_rule_" . ++$rulename . "\">\n";
		$rewrite_rule .= "                    <match url=\"^(.*?)Sitemap\.xml$\" ignoreCase=\"false\" />\n";
		$rewrite_rule .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "=SitemapIndex\" appendQueryString=\"false\" />\n";
		$rewrite_rule .= "                </rule>\n";
		$rewrite_rule .= "                <rule name=\"nv_rule_" . ++$rulename . "\">\n";
		$rewrite_rule .= "                    <match url=\"^(.*?)Sitemap\-([a-z]{2})\.xml$\" ignoreCase=\"false\" />\n";
		$rewrite_rule .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:2}&amp;" . NV_NAME_VARIABLE . "=SitemapIndex\" appendQueryString=\"false\" />\n";
		$rewrite_rule .= "                </rule>\n";
		$rewrite_rule .= "                <rule name=\"nv_rule_" . ++$rulename . "\">\n";
		$rewrite_rule .= "                    <match url=\"^(.*?)Sitemap\-([a-z]{2})\.([a-zA-Z0-9-]+)\.xml$\" ignoreCase=\"false\" />\n";
		$rewrite_rule .= "                    <action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:2}&amp;" . NV_NAME_VARIABLE . "={R:3}&amp;" . NV_OP_VARIABLE . "=Sitemap\" appendQueryString=\"false\" />\n";
		$rewrite_rule .= "                </rule>\n";
		if( $sys_info['zlib_support'] )
		{
			$rewrite_rule .= "                <rule name=\"nv_rule_" . ++$rulename . "\">\n";
			$rewrite_rule .= "                    <match url=\"^((?!http(s?)|ftp\:\/\/).*)\.(css|js)$\" ignoreCase=\"false\" />\n";
			$rewrite_rule .= "                    <action type=\"Rewrite\" url=\"CJzip.php?file={R:1}.{R:3}\" appendQueryString=\"false\" />\n";
			$rewrite_rule .= "                </rule>\n";
		}
		$rewrite_rule .= "                <rule name=\"nv_rule_rewrite\">\n";
		$rewrite_rule .= "                	<match url=\"(.*)(" . $endurl . ")$\" ignoreCase=\"false\" />\n";
		$rewrite_rule .= "                	<conditions logicalGrouping=\"MatchAll\">\n";
		$rewrite_rule .= "                		<add input=\"{REQUEST_FILENAME}\" matchType=\"IsFile\" ignoreCase=\"false\" negate=\"true\" />\n";
		$rewrite_rule .= "                 		<add input=\"{REQUEST_FILENAME}\" matchType=\"IsDirectory\" ignoreCase=\"false\" negate=\"true\" />\n";
		$rewrite_rule .= "                	</conditions>\n";
		$rewrite_rule .= "                	<action type=\"Rewrite\" url=\"index.php\" />\n";
		$rewrite_rule .= "                </rule>\n";
		$rewrite_rule = nv_rewrite_rule_iis7( $rewrite_rule );
	}
	elseif( $sys_info['supports_rewrite'] == "rewrite_mode_apache" )
	{
		$filename = NV_ROOTDIR . "/.htaccess";
		$htaccess = "";

		$rewrite_rule = "##################################################################################\n";
		$rewrite_rule .= "#nukeviet_rewrite_start //Please do not change the contents of the following lines\n";
		$rewrite_rule .= "##################################################################################\n\n";
		$rewrite_rule .= "#Options +FollowSymLinks\n\n";
		$rewrite_rule .= "<IfModule mod_rewrite.c>\n";
		$rewrite_rule .= "RewriteEngine On\n";
		$rewrite_rule .= "#RewriteBase " . NV_BASE_SITEURL . "\n";
		$rewrite_rule .= "RewriteCond %{REQUEST_FILENAME} /robots.txt$ [NC]\n";
		$rewrite_rule .= "RewriteRule ^ robots.php?action=%{HTTP_HOST} [L]\n";
		$rewrite_rule .= "RewriteRule ^(.*?)Sitemap\.xml$ index.php?" . NV_NAME_VARIABLE . "=SitemapIndex [L]\n";
		$rewrite_rule .= "RewriteRule ^(.*?)Sitemap\-([a-z]{2})\.xml$ index.php?" . NV_LANG_VARIABLE . "=$2&" . NV_NAME_VARIABLE . "=SitemapIndex [L]\n";
		$rewrite_rule .= "RewriteRule ^(.*?)Sitemap\-([a-z]{2})\.([a-zA-Z0-9-]+)\.xml$ index.php?" . NV_LANG_VARIABLE . "=$2&" . NV_NAME_VARIABLE . "=$3&" . NV_OP_VARIABLE . "=Sitemap [L]\n";
		if( $sys_info['zlib_support'] )
		{
			$rewrite_rule .= "RewriteRule ^((?!http(s?)|ftp\:\/\/).*)\.(css|js)$ CJzip.php?file=$1.$3 [L]\n";
		}
		$rewrite_rule .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
		$rewrite_rule .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
		$rewrite_rule .= "RewriteRule (.*)(" . $endurl . ")\$ index.php\n";
		$rewrite_rule .= "</IfModule>\n\n";
		$rewrite_rule .= "#nukeviet_rewrite_end\n";
		$rewrite_rule .= "##################################################################################\n\n";

		if( file_exists( $filename ) )
		{
			$htaccess = @file_get_contents( $filename );
			if( ! empty( $htaccess ) )
			{
				$htaccess = preg_replace( "/[\n]*[\#]+[\n]+\#nukeviet\_rewrite\_start(.*)\#nukeviet\_rewrite\_end[\n]+[\#]+[\n]*/s", "\n", $htaccess );
				$htaccess = trim( $htaccess );
			}
		}
		$htaccess .= "\n\n" . $rewrite_rule;
		$rewrite_rule = $htaccess;
	}
	$errormess = false;
	if( ! empty( $filename ) and ! empty( $rewrite_rule ) )
	{
		$savefile = true;
		try
		{
			file_put_contents( $filename, $rewrite_rule, LOCK_EX );
			if( ! file_exists( $filename ) or filesize( $filename ) == 0 )
			{
				$errormess .= sprintf( $lang_module['err_writable'], NV_BASE_SITEURL . $filename );
				$savefile = false;
			}
		}
		catch ( exception $e )
		{
			$savefile = false;
		}
		if( ! $savefile )
		{
			$errormess .= sprintf( $lang_module['err_writable'], NV_BASE_SITEURL . basename( $filename ) );
		}
	}
	return $errormess;
}

/**
 * nv_rewrite_rule_iis7()
 *
 * @param mixed $rewrite_rule
 * @return
 */

function nv_rewrite_rule_iis7( $rewrite_rule = "" )
{
	$filename = NV_ROOTDIR . "/web.config";
	if( ! class_exists( 'DOMDocument' ) ) return false;

	// If configuration file does not exist then we create one.
	if( ! file_exists( $filename ) )
	{
		$fp = fopen( $filename, 'w' );
		fwrite( $fp, '<configuration/>' );
		fclose( $fp );
	}

	$doc = new DOMDocument();
	$doc->preserveWhiteSpace = false;

	if( $doc->load( $filename ) === false ) return false;

	$xpath = new DOMXPath( $doc );

	// Check the XPath to the rewrite rule and create XML nodes if they do not exist
	$xmlnodes = $xpath->query( '/configuration/system.webServer/rewrite/rules' );
	if( $xmlnodes->length > 0 )
	{
		$child = $xmlnodes->item( 0 );
		$parent = $child->parentNode;
		$parent->removeChild( $child );
	}
	if( ! empty( $rewrite_rule ) )
	{
		$rules_node = $doc->createElement( 'rules' );

		$xmlnodes = $xpath->query( '/configuration/system.webServer/rewrite' );
		if( $xmlnodes->length > 0 )
		{
			$rewrite_node = $xmlnodes->item( 0 );
			$rewrite_node->appendChild( $rules_node );
		}
		else
		{
			$rewrite_node = $doc->createElement( 'rewrite' );
			$rewrite_node->appendChild( $rules_node );

			$xmlnodes = $xpath->query( '/configuration/system.webServer' );
			if( $xmlnodes->length > 0 )
			{
				$system_webServer_node = $xmlnodes->item( 0 );
				$system_webServer_node->appendChild( $rewrite_node );
			}
			else
			{
				$system_webServer_node = $doc->createElement( 'system.webServer' );
				$system_webServer_node->appendChild( $rewrite_node );

				$xmlnodes = $xpath->query( '/configuration' );
				if( $xmlnodes->length > 0 )
				{
					$config_node = $xmlnodes->item( 0 );
					$config_node->appendChild( $system_webServer_node );
				}
				else
				{
					$config_node = $doc->createElement( 'configuration' );
					$doc->appendChild( $config_node );
					$config_node->appendChild( $system_webServer_node );
				}
			}
		}
		$rule_fragment = $doc->createDocumentFragment();
		$rule_fragment->appendXML( $rewrite_rule );
		$rules_node->appendChild( $rule_fragment );
	}
	$doc->formatOutput = true;
	return $doc->saveXML();
}

/**
 * nv_getModVersion()
 * 
 * @param integer $updatetime
 * @return
 */
function nv_getModVersion( $updatetime = 3600 )
{
    global $global_config;

    $my_file = NV_ROOTDIR . '/' . NV_CACHEDIR . '/modules.version.' . NV_LANG_INTERFACE . '.xml';

    $xmlcontent = false;

    $p = NV_CURRENTTIME - $updatetime;

    if ( file_exists( $my_file ) and @filemtime( $my_file ) > $p )
    {
        $xmlcontent = simplexml_load_file( $my_file );
    }
    else
    {
        include ( NV_ROOTDIR . "/includes/class/geturl.class.php" );
        $getContent = new UrlGetContents( $global_config, 6 );
		
        $nv_sites = array( //
            'update.nukeviet.vn', //
            'update2.nukeviet.vn', //
            'update.nukeviet.info', //
            'update2.nukeviet.info'
		);

        $content = nv_rand_getVersion( $nv_sites, $getContent, true );

        if ( ! empty( $content ) )
        {
            $xmlcontent = simplexml_load_string( $content );
            if ( $xmlcontent !== false )
            {
                file_put_contents( $my_file, $content );
            }
        }
    }

    return $xmlcontent;
}

?>