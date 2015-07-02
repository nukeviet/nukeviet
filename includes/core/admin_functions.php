<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 2:13
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * nv_groups_list()
 *
 * @return
 */
function nv_groups_list()
{
	$cache_file = NV_LANG_DATA . '_groups_list_' . NV_CACHE_PREFIX . '.cache';
	if( ( $cache = nv_get_cache( 'users', $cache_file ) ) != false )
	{
		return unserialize( $cache );
	}
	else
	{
		global $db, $db_config, $global_config, $lang_global;

		$groups = array();
		$result = $db->query( 'SELECT group_id, title, idsite FROM ' . NV_GROUPS_GLOBALTABLE . ' WHERE (idsite = ' . $global_config['idsite'] . ' OR (idsite =0 AND siteus = 1)) ORDER BY idsite, weight' );
		while( $row = $result->fetch() )
		{
			if( $row['group_id'] < 9 ) $row['title'] = $lang_global['level' . $row['group_id']];
			$groups[$row['group_id']] = ( $global_config['idsite'] > 0 and empty( $row['idsite'] ) ) ? '<strong>' . $row['title'] . '</strong>' : $row['title'];
		}
		nv_set_cache( 'users', $cache_file, serialize( $groups ) );

		return $groups;
	}
}

/**
 * nv_groups_post()
 *
 * @param mixed $groups_view
 * @return
 */
function nv_groups_post( $groups_view )
{
	if( in_array( 6, $groups_view) )
	{
		return array( 6 );
	}
	if( in_array( 4, $groups_view) )
	{
		return array_intersect( $groups_view, array( 4, 5 ) );
	}
	if( in_array( 3, $groups_view) )
	{
		return array_diff( $groups_view, array( 1, 2 ) );
	}
	if( in_array( 2, $groups_view) )
	{
		return array_diff( $groups_view, array( 1 ) );
	}
	if( empty( $groups_view) )
	{
		return array( 1 );
	}
	return array_map( 'intval', $groups_view );
}

/**
 * nv_var_export()
 *
 * @param mixed $var_array
 * @return
 */
function nv_var_export( $var_array )
{
	$ct = preg_replace( '/[\s\t\r\n]+/', ' ', var_export( $var_array, true ) );
	$ct = str_replace( "', ), '", "'), '", $ct );
	$ct = str_replace( 'array ( ', 'array(', $ct );
	$ct = str_replace( ' => ', '=>', $ct );
	$ct = str_replace( '\', ), ), )', '\')))', $ct );
	$ct = str_replace( '\', ), )', '\'))', $ct );
	$ct = preg_replace( "/\'\, \)+$/", "')", $ct );
	return $ct;
}

/**
 * nv_save_file_config_global()
 *
 * @return
 */
function nv_save_file_config_global()
{
	global $db, $sys_info, $global_config, $db_config;

	if( $global_config['idsite'] )
	{
		return false;
	}

	$content_config = "<?php" . "\n\n";
	$content_config .= NV_FILEHEAD . "\n\n";
	$content_config .= "if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );\n\n";

	//disable_classes
	$sys_info['disable_classes'] = ( ( $disable_classes = ini_get( 'disable_classes' ) ) != '' and $disable_classes != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", $disable_classes ) ) : array();
	if( ! empty( $sys_info['disable_classes'] ) )
	{
		$disable_classes = "'" . implode( "','", $sys_info['disable_classes'] ) . "'";
	}
	else
	{
		$disable_classes = '';
	}
	$content_config .= "\$sys_info['disable_classes']=array(" . $disable_classes . ");\n";

	//disable_functions
	$sys_info['disable_functions'] = ( ( $disable_functions = ini_get( 'disable_functions' ) ) != '' and $disable_functions != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", $disable_functions ) ) : array();

	if( extension_loaded( 'suhosin' ) )
	{
		$sys_info['disable_functions'] = array_merge( $sys_info['disable_functions'], array_map( 'trim', preg_split( "/[\s,]+/", ini_get( 'suhosin.executor.func.blacklist' ) ) ) );
	}
	if( ! empty( $sys_info['disable_functions'] ) )
	{
		$disable_functions = "'" . implode( "','", $sys_info['disable_functions'] ) . "'";
	}
	else
	{
		$disable_functions = '';
	}
	$content_config .= "\$sys_info['disable_functions']=array(" . $disable_functions . ");\n";

	//ini_set_support
	$sys_info['ini_set_support'] = ( function_exists( 'ini_set' ) and ! in_array( 'ini_set', $sys_info['disable_functions'] ) ) ? true : false;
	$ini_set_support = ( $sys_info['ini_set_support'] ) ? 'true' : 'false';
	$content_config .= "\$sys_info['ini_set_support']= " . $ini_set_support . ";\n";
	//Kiem tra ho tro rewrite
	if( function_exists( 'apache_get_modules' ) )
	{
		$apache_modules = apache_get_modules();
		if( in_array( 'mod_rewrite', $apache_modules ) )
		{
			$sys_info['supports_rewrite'] = 'rewrite_mode_apache';
		}
		else
		{
			$sys_info['supports_rewrite'] = false;
		}
	}
	elseif( strpos( $_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS/7.' ) !== false )
	{
		if( isset( $_SERVER['IIS_UrlRewriteModule'] ) and class_exists( 'DOMDocument' ) )
		{
			$sys_info['supports_rewrite'] = 'rewrite_mode_iis';
		}
		else
		{
			$sys_info['supports_rewrite'] = false;
		}
	}

	if( $sys_info['supports_rewrite'] == 'rewrite_mode_iis' or $sys_info['supports_rewrite'] == 'rewrite_mode_apache' )
	{
		$content_config .= "\$sys_info['supports_rewrite']='" . $sys_info['supports_rewrite'] . "';\n";
	}
	else
	{
		$content_config .= "\$sys_info['supports_rewrite']=false;\n";
	}
	$content_config .= "\n";

	$config_variable = array();
	$allowed_html_tags = '';
	$sql = "SELECT module, config_name, config_value FROM " . NV_CONFIG_GLOBALTABLE . " WHERE lang='sys' AND (module='global' OR module='define') ORDER BY config_name ASC";
	$result = $db->query( $sql );

	while( list( $c_module, $c_config_name, $c_config_value ) = $result->fetch( 3 ) )
	{
		if( $c_module == 'define' )
		{
			if( preg_match( '/^\d+$/', $c_config_value ) )
			{
				$content_config .= "define('" . strtoupper( $c_config_name ) . "', " . $c_config_value . ");\n";
			}
			else
			{
				$content_config .= "define('" . strtoupper( $c_config_name ) . "', '" . $c_config_value . "');\n";
			}
			if( $c_config_name == 'nv_allowed_html_tags' )
			{
				$allowed_html_tags = $c_config_value;
			}
		}
		else
		{
			$config_variable[$c_config_name] = $c_config_value;
		}
	}

	$nv_eol = strtoupper( substr( PHP_OS, 0, 3 ) == 'WIN' ) ? '"\r\n"' : ( strtoupper( substr( PHP_OS, 0, 3 ) == 'MAC' ) ? '"\r"' : '"\n"' );
	$upload_max_filesize = min( nv_converttoBytes( ini_get( 'upload_max_filesize' ) ), nv_converttoBytes( ini_get( 'post_max_size' ) ), $config_variable['nv_max_size'] );

	$content_config .= "define('NV_EOL', " . $nv_eol . ");\n";
	$content_config .= "define('NV_UPLOAD_MAX_FILESIZE', " . floatval( $upload_max_filesize ) . ");\n";

	if( $config_variable['openid_mode'] )
	{
		$content_config .= "define('NV_OPENID_ALLOWED', true);\n\n";
	}

	$my_domains = array_map( 'trim', explode( ',', $config_variable['my_domains'] ) );
	$my_domains[] = NV_SERVER_NAME;
	$config_variable['my_domains'] = implode( ',', array_unique( $my_domains ) );

	$config_variable['check_rewrite_file'] = nv_check_rewrite_file();
	$config_variable['allow_request_mods'] = NV_ALLOW_REQUEST_MODS != '' ? NV_ALLOW_REQUEST_MODS : "request";
	$config_variable['request_default_mode'] = NV_REQUEST_DEFAULT_MODE != '' ? trim( NV_REQUEST_DEFAULT_MODE ) : 'request';
	$config_variable['session_save_path'] = NV_SESSION_SAVE_PATH;

	$config_variable['log_errors_list'] = NV_LOG_ERRORS_LIST;
	$config_variable['display_errors_list'] = NV_DISPLAY_ERRORS_LIST;
	$config_variable['send_errors_list'] = NV_SEND_ERRORS_LIST;
	$config_variable['error_log_path'] = NV_LOGS_DIR . '/error_logs';
	$config_variable['error_log_filename'] = NV_ERRORLOGS_FILENAME;
	$config_variable['error_log_fileext'] = NV_LOGS_EXT;
	$config_variable['error_send_email'] = $config_variable['error_send_email'];

	$config_name_array = array( 'file_allowed_ext', 'forbid_extensions', 'forbid_mimes', 'allow_sitelangs', 'openid_servers', 'allow_request_mods', 'config_sso' );

	if( empty( $config_variable['openid_servers'] ) )
	{
		$config_variable['openid_mode'] = 0;
	}

	if( $config_variable['is_user_forum'] )
	{
		$forum_files = @scandir( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet' );
		if( ! empty( $forum_files ) and in_array( 'is_user.php', $forum_files ) and in_array( 'changepass.php', $forum_files ) and in_array( 'editinfo.php', $forum_files ) and in_array( 'login.php', $forum_files ) and in_array( 'logout.php', $forum_files ) and in_array( 'lostpass.php', $forum_files ) and in_array( 'register.php', $forum_files ) )
		{
			$content_config .= "define( 'NV_IS_USER_FORUM', true );\n\n";
		}
		else
		{
			$config_variable['is_user_forum'] = 0;
		}
	}

	foreach( $config_variable as $c_config_name => $c_config_value )
	{
		if( $c_config_name == 'config_sso' )
		{
			$config_sso = empty( $c_config_value ) ? '' : nv_var_export( unserialize( $c_config_value ) );
			$content_config .= "\$global_config['" . $c_config_name . "']=" . $config_sso . ";\n";
		}
		elseif( in_array( $c_config_name, $config_name_array ) )
		{
			if( ! empty( $c_config_value ) )
			{
				$c_config_value = "'" . implode( "','", array_map( "trim", explode( ',', $c_config_value ) ) ) . "'";
			}
			else
			{
				$c_config_value = '';
			}
			$content_config .= "\$global_config['" . $c_config_name . "']=array(" . $c_config_value . ");\n";
		}
		else
		{
			if( preg_match( '/^\d+$/', $c_config_value ) AND $c_config_name != 'facebook_client_id' )
			{
				$content_config .= "\$global_config['" . $c_config_name . "']=" . $c_config_value . ";\n";
			}
			else
			{
				$c_config_value = nv_unhtmlspecialchars( $c_config_value );
				if( ! preg_match( "/^[a-z0-9\-\_\.\,\;\:\@\/\\s]+$/i", $c_config_value ) and $c_config_name != 'my_domains' )
				{
					$c_config_value = nv_htmlspecialchars( $c_config_value );
				}
				$content_config .= "\$global_config['" . $c_config_name . "']='" . $c_config_value . "';\n";
			}
		}
	}
	$content_config .= "\$global_config['array_theme_type']=" . nv_var_export( array_filter( array_map( 'trim', explode( ',', NV_THEME_TYPE ) ) ) ) . ";\n";

	//allowed_html_tags
	if( ! empty( $allowed_html_tags ) )
	{
		$allowed_html_tags = "'" . implode( "','", array_map( 'trim', explode( ',', $allowed_html_tags ) ) ) . "'";
	}
	else
	{
		$allowed_html_tags = '';
	}
	$content_config .= "\$global_config['allowed_html_tags']=array(" . $allowed_html_tags . ");\n";

	//Xac dinh cac search_engine
	$engine_allowed = ( file_exists( NV_ROOTDIR . '/' . NV_DATADIR . '/search_engine.xml' ) ) ? nv_object2array( simplexml_load_file( NV_ROOTDIR . '/' . NV_DATADIR . '/search_engine.xml' ) ) : array();
	$content_config .= "\$global_config['engine_allowed']=" . nv_var_export( $engine_allowed ) . ";\n";
	$content_config .= "\n";

	$language_array = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/langs.ini', true );
	$tmp_array = array();
	$lang_array_exit = nv_scandir( NV_ROOTDIR . "/language", "/^[a-z]{2}+$/" );
	foreach( $lang_array_exit as $lang )
	{
		$tmp_array[$lang] = $language_array[$lang];
	}
	unset( $language_array );
	$content_config .= "\$language_array=" . nv_var_export( $tmp_array ) . ";\n";

	$tmp_array = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/timezone.ini', true );
	$content_config .= "\$nv_parse_ini_timezone=" . nv_var_export( $tmp_array ) . ";\n";

	$rewrite = array();
	$global_config['rewrite_optional'] = $config_variable['rewrite_optional'];
	$global_config['rewrite_op_mod'] = $config_variable['rewrite_op_mod'];

	$global_config['rewrite_endurl'] = $config_variable['rewrite_endurl'];
	$global_config['rewrite_exturl'] = $config_variable['rewrite_exturl'];

	if( $config_variable['check_rewrite_file'] )
	{
		require NV_ROOTDIR . '/includes/rewrite.php';
	}
	else
	{
		require NV_ROOTDIR . '/includes/rewrite_index.php';
	}

	$content_config .= "\n";

    $nv_plugin_area = array();
    $_sql = 'SELECT * FROM ' . $db_config['prefix'] . '_plugin ORDER BY plugin_area ASC, weight ASC';
    $_query = $db->query( $_sql );
    while( $row = $_query->fetch() )
    {
        $nv_plugin_area[$row['plugin_area']][] = $row['plugin_file'];
    }
    $content_config .= "\$nv_plugin_area=" . nv_var_export( $nv_plugin_area ) . ";\n\n";

	$content_config .= "\$rewrite_keys=" . nv_var_export( array_keys( $rewrite ) ) . ";\n";
	$content_config .= "\$rewrite_values=" . nv_var_export( array_values( $rewrite ) ) . ";\n";

	$return = file_put_contents( NV_ROOTDIR . "/" . NV_DATADIR . "/config_global.php", trim( $content_config ), LOCK_EX );
	nv_delete_all_cache();

	return $return;
}

/**
 * nv_geVersion()
 *
 * @param integer $updatetime
 * @return
 */
function nv_geVersion( $updatetime = 3600 )
{
	global $global_config, $lang_global;

	$my_file = NV_ROOTDIR . '/' . NV_CACHEDIR . '/nukeviet.version.' . NV_LANG_INTERFACE . '.xml';

	$xmlcontent = false;

	$p = NV_CURRENTTIME - $updatetime;

	if( file_exists( $my_file ) and @filemtime( $my_file ) > $p )
	{
		$xmlcontent = simplexml_load_file( $my_file );
	}
	else
	{
		$NV_Http = new NV_Http( $global_config, NV_TEMP_DIR );

		$args = array(
			'headers' => array(
				'Referer' => NUKEVIET_STORE_APIURL,
			),
			'body' => array(
				'lang' > NV_LANG_INTERFACE,
				'basever' => $global_config['version'],
				'mode' => 'getsysver'
			)
		);

		$array = $NV_Http->post( NUKEVIET_STORE_APIURL, $args );
		$array = ! empty( $array['body'] ) ? @unserialize( $array['body'] ) : array();

		$error = '';
		if( ! empty( NV_Http::$error ) )
		{
			$error = nv_http_get_lang( NV_Http::$error );
		}
		elseif( ! isset( $array['error'] ) or ! isset( $array['data'] ) or ! isset( $array['pagination'] ) or ! is_array( $array['error'] ) or ! is_array( $array['data'] ) or ! is_array( $array['pagination'] ) or ( ! empty( $array['error'] ) and ( ! isset( $array['error']['level'] ) or empty( $array['error']['message'] ) ) ) )
		{
			$error = $lang_global['error_valid_response'];
		}
		elseif( ! empty( $array['error']['message'] ) )
		{
			$error = $array['error']['message'];
		}

		if( ! empty( $error ) )
		{
			return $error;
		}

		$array = $array['data'];

		$content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<cms>\n\t<name><![CDATA[" . $array['name'] . "]]></name>\n\t<version><![CDATA[" . $array['version'] . "]]></version>\n\t<date><![CDATA[" . $array['date'] . "]]></date>\n\t<message><![CDATA[" . $array['message'] . "]]></message>\n\t<link><![CDATA[" . $array['link'] . "]]></link>\n\t<updateable><![CDATA[" . $array['updateable'] . "]]></updateable>\n</cms>";

		$xmlcontent = simplexml_load_string( $content );

		if( $xmlcontent !== false )
		{
			file_put_contents( $my_file, $content );
		}
	}

	return $xmlcontent;
}

/**
 * nv_version_compare()
 *
 * @param mixed $version1
 * @param mixed $version2
 * @return
 */
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
 * nv_check_rewrite_file()
 *
 * @return
 */
function nv_check_rewrite_file()
{
	global $sys_info;

	if( $sys_info['supports_rewrite'] == 'rewrite_mode_apache' )
	{
		if( ! file_exists( NV_ROOTDIR . '/.htaccess' ) ) return false;

		$htaccess = @file_get_contents( NV_ROOTDIR . '/.htaccess' );

		return ( preg_match( '/\#nukeviet\_rewrite\_start(.*)\#nukeviet\_rewrite\_end/s', $htaccess ) );
	}

	if( $sys_info['supports_rewrite'] == 'rewrite_mode_iis' )
	{
		if( ! file_exists( NV_ROOTDIR . '/web.config' ) ) return false;

		$web_config = @file_get_contents( NV_ROOTDIR . '/web.config' );

		return ( preg_match( '/<rule name="nv_rule_rewrite">(.*)<\/rule>/s', $web_config ) );
	}

	return false;
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
	$endurl = ( $array_config_global['rewrite_endurl'] == $array_config_global['rewrite_exturl'] ) ? nv_preg_quote( $array_config_global['rewrite_endurl'] ) : nv_preg_quote( $array_config_global['rewrite_endurl'] ) . '|' . nv_preg_quote( $array_config_global['rewrite_exturl'] );

	if( $sys_info['supports_rewrite'] == 'rewrite_mode_iis' )
	{
		$filename = NV_ROOTDIR . '/web.config';
		$rulename = 0;
		$rewrite_rule .= "\n";
		$rewrite_rule .= " <rule name=\"nv_rule_" . ++$rulename . "\">\n";
		$rewrite_rule .= " <match url=\"^\" ignoreCase=\"false\" />\n";
		$rewrite_rule .= " <conditions>\n";
		$rewrite_rule .= " 		<add input=\"{REQUEST_FILENAME}\" pattern=\"/robots.txt$\" />\n";
		$rewrite_rule .= " </conditions>\n";
		$rewrite_rule .= " <action type=\"Rewrite\" url=\"robots.php?action={HTTP_HOST}\" appendQueryString=\"false\" />\n";
		$rewrite_rule .= " </rule>\n";

		$rewrite_rule .= " <rule name=\"nv_rule_" . ++$rulename . "\">\n";
		$rewrite_rule .= " \t<match url=\"^(.*?)sitemap\.xml$\" ignoreCase=\"false\" />\n";
		$rewrite_rule .= " \t<action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "=SitemapIndex\" appendQueryString=\"false\" />\n";
		$rewrite_rule .= " </rule>\n";

		$rewrite_rule .= " <rule name=\"nv_rule_" . ++$rulename . "\">\n";
		$rewrite_rule .= " \t<match url=\"^(.*?)sitemap\-([a-z]{2})\.xml$\" ignoreCase=\"false\" />\n";
		$rewrite_rule .= " \t<action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:2}&amp;" . NV_NAME_VARIABLE . "=SitemapIndex\" appendQueryString=\"false\" />\n";
		$rewrite_rule .= " </rule>\n";

		$rewrite_rule .= " <rule name=\"nv_rule_" . ++$rulename . "\">\n";
		$rewrite_rule .= " \t<match url=\"^(.*?)sitemap\-([a-z]{2})\.([a-zA-Z0-9-]+)\.xml$\" ignoreCase=\"false\" />\n";
		$rewrite_rule .= " \t<action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:2}&amp;" . NV_NAME_VARIABLE . "={R:3}&amp;" . NV_OP_VARIABLE . "=sitemap\" appendQueryString=\"false\" />\n";
		$rewrite_rule .= " </rule>\n";

		if( $sys_info['zlib_support'] )
		{
			$rewrite_rule .= " <rule name=\"nv_rule_" . ++$rulename . "\">\n";
			$rewrite_rule .= " \t<match url=\"^((?!http(s?)|ftp\:\/\/).*)\.(css|js)$\" ignoreCase=\"false\" />\n";
			$rewrite_rule .= " \t<action type=\"Rewrite\" url=\"CJzip.php?file={R:1}.{R:3}\" appendQueryString=\"false\" />\n";
			$rewrite_rule .= " </rule>\n";
		}

		$rewrite_rule .= " <rule name=\"nv_rule_rewrite\">\n";
		$rewrite_rule .= " 	<match url=\"(.*)(" . $endurl . ")$\" ignoreCase=\"false\" />\n";
		$rewrite_rule .= " 	<conditions logicalGrouping=\"MatchAll\">\n";
		$rewrite_rule .= " 		<add input=\"{REQUEST_FILENAME}\" matchType=\"IsFile\" ignoreCase=\"false\" negate=\"true\" />\n";
		$rewrite_rule .= " 		<add input=\"{REQUEST_FILENAME}\" matchType=\"IsDirectory\" ignoreCase=\"false\" negate=\"true\" />\n";
		$rewrite_rule .= " 	</conditions>\n";
		$rewrite_rule .= " 	<action type=\"Rewrite\" url=\"index.php\" />\n";
		$rewrite_rule .= " </rule>\n";

		if( $array_config_global['rewrite_optional'] )
		{
			if( ! empty( $array_config_global['rewrite_op_mod'] ) )
			{
				if( $array_config_global['rewrite_op_mod'] == 'seek' )
				{
					$rewrite_rule .= " <rule name=\"nv_rule_" . ++ $rulename . "\">\n";
					$rewrite_rule .= " \t<match url=\"^q\=(.*)$\" ignoreCase=\"false\" />\n";
					$rewrite_rule .= " \t<action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "=seek&amp;q={R:1}\" appendQueryString=\"false\" />\n";
					$rewrite_rule .= " </rule>\n";
				}
				else
				{
					$rewrite_rule .= " <rule name=\"nv_rule_" . ++ $rulename . "\">\n";
					$rewrite_rule .= " \t<match url=\"^seek\/q\=(.*)$\" ignoreCase=\"false\" />\n";
					$rewrite_rule .= " \t<action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "=seek&amp;q={R:1}\" appendQueryString=\"false\" />\n";
					$rewrite_rule .= " </rule>\n";
				}

				$rewrite_rule .= " <rule name=\"nv_rule_" . ++ $rulename . "\">\n";
				$rewrite_rule .= " \t<match url=\"^search\/q\=(.*)$\" ignoreCase=\"false\" />\n";
				$rewrite_rule .= " \t<action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "=" . $array_config_global['rewrite_op_mod'] . "&amp;" . NV_OP_VARIABLE . "=search&amp;q={R:1}\" appendQueryString=\"false\" />\n";
				$rewrite_rule .= " </rule>\n";
			}
			else
			{
				$rewrite_rule .= " <rule name=\"nv_rule_" . ++ $rulename . "\">\n";
				$rewrite_rule .= " \t<match url=\"^seek\/q\=(.*)$\" ignoreCase=\"false\" />\n";
				$rewrite_rule .= " \t<action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "=seek&amp;q={R:1}\" appendQueryString=\"false\" />\n";
				$rewrite_rule .= " </rule>\n";
			}

			$rewrite_rule .= " <rule name=\"nv_rule_" . ++ $rulename . "\">\n";
			$rewrite_rule .= " \t<match url=\"^([a-zA-Z0-9\-]+)\/search\/q\=(.*)$\" ignoreCase=\"false\" />\n";
			$rewrite_rule .= " \t<action type=\"Rewrite\" url=\"index.php?" . NV_NAME_VARIABLE . "={R:1}&amp;" . NV_OP_VARIABLE . "=search&amp;q={R:2}\" appendQueryString=\"false\" />\n";
			$rewrite_rule .= " </rule>\n";
		}
		else
		{
			$rewrite_rule .= " <rule name=\"nv_rule_" . ++ $rulename . "\">\n";
			$rewrite_rule .= " \t<match url=\"^([a-z]{2})\/seek\/q\=(.*)$\" ignoreCase=\"false\" />\n";
			$rewrite_rule .= " \t<action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:1}&amp;" . NV_NAME_VARIABLE . "=seek&amp;q={R:2}\" appendQueryString=\"false\" />\n";
			$rewrite_rule .= " </rule>\n";

			$rewrite_rule .= " <rule name=\"nv_rule_" . ++ $rulename . "\">\n";
			$rewrite_rule .= " \t<match url=\"^([a-z]{2})\/([a-zA-Z0-9\-]+)\/search\/q\=(.*)$\" ignoreCase=\"false\" />\n";
			$rewrite_rule .= " \t<action type=\"Rewrite\" url=\"index.php?" . NV_LANG_VARIABLE . "={R:1}&amp;" . NV_NAME_VARIABLE . "={R:2}&amp;" . NV_OP_VARIABLE . "=search&amp;q={R:3}\" appendQueryString=\"false\" />\n";
			$rewrite_rule .= " </rule>\n";
		}
		$rewrite_rule .= " <rule name=\"nv_rule_" . ++ $rulename . "\" stopProcessing=\"true\">\n";
		$rewrite_rule .= " \t<match url=\"^([a-zA-Z0-9-\/]+)\/([a-zA-Z0-9-]+)$\" ignoreCase=\"false\" />\n";
		$rewrite_rule .= " \t<action type=\"Redirect\" redirectType=\"Permanent\" url=\"" . NV_BASE_SITEURL . "{R:1}/{R:2}/\" />\n";
		$rewrite_rule .= " </rule>\n";

		$rewrite_rule .= " <rule name=\"nv_rule_" . ++ $rulename . "\" stopProcessing=\"true\">\n";
		$rewrite_rule .= " \t<match url=\"^([a-zA-Z0-9-]+)$\" ignoreCase=\"false\" />\n";
		$rewrite_rule .= " \t<action type=\"Redirect\" redirectType=\"Permanent\" url=\"" . NV_BASE_SITEURL . "{R:1}/\" />\n";
		$rewrite_rule .= " </rule>\n";

		$rewrite_rule = nv_rewrite_rule_iis7( $rewrite_rule );
	}
	elseif( $sys_info['supports_rewrite'] == 'rewrite_mode_apache' )
	{
		$filename = NV_ROOTDIR . '/.htaccess';
		$htaccess = '';

		$rewrite_rule = "##################################################################################\n";
		$rewrite_rule .= "#nukeviet_rewrite_start //Please do not change the contents of the following lines\n";
		$rewrite_rule .= "##################################################################################\n\n";
		$rewrite_rule .= "#Options +FollowSymLinks\n\n";
		$rewrite_rule .= "<IfModule mod_rewrite.c>\n";
		$rewrite_rule .= "RewriteEngine On\n";
		$rewrite_rule .= "#RewriteBase " . NV_BASE_SITEURL . "\n";

		if( $array_config_global['ssl_https'] )
		{
			$rewrite_rule .= "RewriteCond %{SERVER_PORT} !^443$\n";
			$rewrite_rule .= "RewriteRule (.*)  https://%{SERVER_NAME}%{REQUEST_URI} [L,R]\n";
		}

		$rewrite_rule .= "RewriteCond %{REQUEST_FILENAME} /robots.txt$ [NC]\n";
		$rewrite_rule .= "RewriteRule ^ robots.php?action=%{HTTP_HOST} [L]\n";
		$rewrite_rule .= "RewriteRule ^(.*?)sitemap\.xml$ index.php?" . NV_NAME_VARIABLE . "=SitemapIndex [L]\n";
		$rewrite_rule .= "RewriteRule ^(.*?)sitemap\-([a-z]{2})\.xml$ index.php?" . NV_LANG_VARIABLE . "=$2&" . NV_NAME_VARIABLE . "=SitemapIndex [L]\n";
		$rewrite_rule .= "RewriteRule ^(.*?)sitemap\-([a-z]{2})\.([a-zA-Z0-9-]+)\.xml$ index.php?" . NV_LANG_VARIABLE . "=$2&" . NV_NAME_VARIABLE . "=$3&" . NV_OP_VARIABLE . "=sitemap [L]\n";
		if( $sys_info['zlib_support'] )
		{
			$rewrite_rule .= "RewriteRule ^((?!http(s?)|ftp\:\/\/).*)\.(css|js)$ CJzip.php?file=$1.$3 [L]\n";
		}

		$rewrite_rule .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
		$rewrite_rule .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
		$rewrite_rule .= "RewriteRule (.*)(" . $endurl . ")\$ index.php\n";
		$rewrite_rule .= "RewriteRule (.*)tag\/(.*)$ index.php\n";

		if( $array_config_global['rewrite_optional'] )
		{
			if( ! empty( $array_config_global['rewrite_op_mod'] ) )
			{
				if( $array_config_global['rewrite_op_mod'] == 'seek' )
				{
					$rewrite_rule .= "RewriteRule ^q\=(.*)$ index.php?" . NV_NAME_VARIABLE . "=seek&q=$1 [L]\n";
				}
				else
				{
					$rewrite_rule .= "RewriteRule ^seek\/q\=(.*)$ index.php?" . NV_NAME_VARIABLE . "=seek&q=$1 [L]\n";
				}

				$rewrite_rule .= "RewriteRule ^search\/q\=(.*)$ index.php?" . NV_NAME_VARIABLE . "=" . $array_config_global['rewrite_op_mod'] . "&" . NV_OP_VARIABLE . "=search&q=$1 [L]\n";
			}
			else
			{
				$rewrite_rule .= "RewriteRule ^seek\/q\=(.*)$ index.php?" . NV_NAME_VARIABLE . "=seek&q=$1 [L]\n";;
			}

			$rewrite_rule .= "RewriteRule ^([a-zA-Z0-9\-]+)\/search\/q\=(.*)$ index.php?" . NV_NAME_VARIABLE . "=$1&" . NV_OP_VARIABLE . "=search&q=$2 [L]\n";;
		}
		else
		{
			$rewrite_rule .= "RewriteRule ^([a-z]{2})\/seek\/q\=(.*)$ index.php?" . NV_LANG_VARIABLE . "=$1&" . NV_NAME_VARIABLE . "=seek&q=$2 [L]\n";;
			$rewrite_rule .= "RewriteRule ^([a-z]{2})\/([a-zA-Z0-9\-]+)\/search\/q\=(.*)$ index.php?" . NV_LANG_VARIABLE . "=$1&" . NV_NAME_VARIABLE . "=$2&" . NV_OP_VARIABLE . "=search&q=$3 [L]\n";;
		}

		$rewrite_rule .= "RewriteRule ^([a-zA-Z0-9-\/]+)\/([a-zA-Z0-9-]+)$ " . NV_BASE_SITEURL . "$1/$2/ [L,R=301]\n";
		$rewrite_rule .= "RewriteRule ^([a-zA-Z0-9-]+)$ " . NV_BASE_SITEURL . "$1/ [L,R=301]\n";
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
	$return = true;
	if( ! empty( $filename ) and ! empty( $rewrite_rule ) )
	{
		try
		{
			$filesize = file_put_contents( $filename, $rewrite_rule, LOCK_EX );
			if( empty( $filesize ) )
			{
				$return = false;
			}
		}
		catch( exception $e )
		{
			$return = false;
		}
	}
	return array( $return, NV_BASE_SITEURL . basename( $filename ) );
}

/**
 * nv_rewrite_rule_iis7()
 *
 * @param mixed $rewrite_rule
 * @return
 */
function nv_rewrite_rule_iis7( $rewrite_rule = '' )
{
	$filename = NV_ROOTDIR . '/web.config';
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
 * nv_getExtVersion()
 *
 * @param integer $updatetime
 * @return
 */
function nv_getExtVersion( $updatetime = 3600 )
{
	global $global_config, $lang_global, $db, $db_config;

	$my_file = NV_ROOTDIR . '/' . NV_CACHEDIR . '/extensions.version.' . NV_LANG_INTERFACE . '.xml';

	$xmlcontent = false;

	$p = NV_CURRENTTIME - $updatetime;

	if( file_exists( $my_file ) and @filemtime( $my_file ) > $p )
	{
		$xmlcontent = simplexml_load_file( $my_file );
	}
	else
	{
		// Lấy các ứng dụng của hệ thống
		$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_setup_extensions WHERE title=basename ORDER BY title ASC';
		$result = $db->query( $sql );

		$array = $array_ext_ids = array();
		while( $row = $result->fetch() )
		{
			$row['version'] = explode( ' ', $row['version'] );

			$array[$row['title']] = array(
				'id' => $row['id'],
				'type' => $row['type'],
				'name' => $row['title'],
				'current_version' => trim( $row['version'][0] ),
				'current_release' => trim( $row['version'][1] ),
				'remote_version' => '',
				'remote_release' => 0,
				'updateable' => array(), // Thong tin cac phien ban co the update
				'author' => $row['author'],
				'license' => '',
				'mode' => $row['is_sys'] ? 'sys' : 'other',
				'message' => $row['note'],
				'link' => '',
				'support' => '',
				'origin' => false,
			);

			if( ! empty( $row['id'] ) )
			{
				$array_ext_ids[] = $row['id'];
			}
		}

		if( ! empty( $array_ext_ids ) )
		{
			$NV_Http = new NV_Http( $global_config, NV_TEMP_DIR );

			$args = array(
				'headers' => array(
					'Referer' => NUKEVIET_STORE_APIURL,
				),
				'body' => array(
					'lang' > NV_LANG_INTERFACE,
					'basever' => $global_config['version'],
					'mode' => 'checkextver',
					'ids' => implode( ',', $array_ext_ids ),
				)
			);

			$apidata = $NV_Http->post( NUKEVIET_STORE_APIURL, $args );
			$apidata = ! empty( $apidata['body'] ) ? @unserialize( $apidata['body'] ) : array();

			$error = '';
			if( ! empty( NV_Http::$error ) )
			{
				$error = nv_http_get_lang( NV_Http::$error );
			}
			elseif( ! isset( $apidata['error'] ) or ! isset( $apidata['data'] ) or ! isset( $apidata['pagination'] ) or ! is_array( $apidata['error'] ) or ! is_array( $apidata['data'] ) or ! is_array( $apidata['pagination'] ) or ( ! empty( $apidata['error'] ) and ( ! isset( $apidata['error']['level'] ) or empty( $apidata['error']['message'] ) ) ) )
			{
				$error = $lang_global['error_valid_response'];
			}
			elseif( ! empty( $apidata['error']['message'] ) )
			{
				$error = $apidata['error']['message'];
			}

			if( ! empty( $error ) )
			{
				return $error;
			}

			$apidata = $apidata['data'];
			$content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<cms>\n";

			// Xử lý dữ liệu
			foreach( $array as $row )
			{
				if( isset( $apidata[$row['id']] ) )
				{
					$row['remote_version'] = $apidata[$row['id']]['lastest_version'];
					$row['remote_release'] = $apidata[$row['id']]['lastest_release'];
					$row['updateable'] = $apidata[$row['id']]['updateable'];

					if( empty( $row['author'] ) )
					{
						$row['author'] = $apidata[$row['id']]['author'];
					}
					$row['license'] = $apidata[$row['id']]['license'];
					$row['message'] = $apidata[$row['id']]['note'];
					$row['link'] = $apidata[$row['id']]['link'];
					$row['support'] = $apidata[$row['id']]['support'];
					$row['origin'] = true;
				}

				$content .= "\t<extension>\n";
				$content .= "\t\t<id><![CDATA[" . $row['id'] . "]]></id>\n";
				$content .= "\t\t<type><![CDATA[" . $row['type'] . "]]></type>\n";
				$content .= "\t\t<name><![CDATA[" . $row['name'] . "]]></name>\n";
				$content .= "\t\t<version><![CDATA[" . $row['current_version'] . "]]></version>\n";
				$content .= "\t\t<date><![CDATA[" . gmdate( "D, d M Y H:i:s", $row['current_release'] ) . " GMT]]></date>\n";
				$content .= "\t\t<new_version><![CDATA[" . $row['remote_version'] . "]]></new_version>\n";
				$content .= "\t\t<new_date><![CDATA[" . ( $row['remote_release'] ? gmdate( "D, d M Y H:i:s", $row['current_release'] ) . " GMT" : "" ) . "]]></new_date>\n";
				$content .= "\t\t<author><![CDATA[" . $row['author'] . "]]></author>\n";
				$content .= "\t\t<license><![CDATA[" . $row['license'] . "]]></license>\n";
				$content .= "\t\t<mode><![CDATA[" . $row['mode'] . "]]></mode>\n";
				$content .= "\t\t<message><![CDATA[" . $row['message'] . "]]></message>\n";
				$content .= "\t\t<link><![CDATA[" . $row['link'] . "]]></link>\n";
				$content .= "\t\t<support><![CDATA[" . $row['support'] . "]]></support>\n";
				$content .= "\t\t<updateable>\n";

				if( ! empty( $row['updateable'] ) )
				{
					$content .= "\t\t\t<upds>\n";

					foreach( $row['updateable'] as $updateable )
					{
						$content .= "\t\t\t\t<upd>\n";
						$content .= "\t\t\t\t\t<upd_fid><![CDATA[" . $updateable['fid'] . "]]></upd_fid>\n";
						$content .= "\t\t\t\t\t<upd_old><![CDATA[" . $updateable['old_ver'] . "]]></upd_old>\n";
						$content .= "\t\t\t\t\t<upd_new><![CDATA[" . $updateable['new_ver'] . "]]></upd_new>\n";
						$content .= "\t\t\t\t</upd>\n";
					}
					$content .= "\t\t\t</upds>\n";

					unset( $updateable );
				}

				$content .= "\t\t</updateable>\n";
				$content .= "\t\t<origin><![CDATA[" . ( $row['origin'] === true ? 'true' : 'false' ) . "]]></origin>\n";
				$content .= "\t</extension>\n";
			}

			$content .= "</cms>";

			$xmlcontent = simplexml_load_string( $content );

			if( $xmlcontent !== false )
			{
				file_put_contents( $my_file, $content );
			}
		}
	}

	return $xmlcontent;
}

/**
 * nv_http_get_lang()
 *
 * @param mixed $input
 * @return
 */
function nv_http_get_lang( $input )
{
	global $lang_global;

	if( ! isset( $input['code'] ) or ! isset( $input['message'] ) )
	{
		return '';
	}

	if( ! empty( $lang_global['error_code_' . $input['code']] ) )
	{
		return $lang_global['error_code_' . $input['code']];
	}

	if( ! empty( $input['message'] ) )
	{
		return $input['message'];
	}

	return 'Error' . ( $input['code'] ? ': ' . $input['code'] . '.' : '.' );
}