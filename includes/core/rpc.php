<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/02/2013 15:07
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function nv_getRPC( $url, $data )
{
	global $module_file, $lang_module, $sys_info;

	$userAgents = array( 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1) Gecko/20090624 Firefox/3.5 (.NET CLR 3.5.30729)', 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)', 'Mozilla/4.8 [en] (Windows NT 6.0; U)', 'Opera/9.25 (Windows NT 6.0; U; en)' );

	srand( ( float )microtime() * 10000000 );
	$rand = array_rand( $userAgents );
	$agent = $userAgents[$rand];

	$url_info = @parse_url( $url );
	$url_info['port'] = isset( $url_info['port'] ) ? intval( $url_info['port'] ) : 80;
	if( isset( $url_info['path'] ) )
	{
		if( substr( $url_info['path'], 0, 1 ) != '/' )
		{
			$url_info['path'] = '/' . $url_info['path'];
		}
	}
	else
	{
		$url_info['path'] = '/';
	}
	$url_info['query'] = ( isset( $url_info['query'] ) and ! empty( $url_info['query'] ) ) ? '?' . $url_info['query'] : '';

	$proxy = array();
	if( file_exists( NV_ROOTDIR . '/' . NV_DATADIR . '/proxies.php' ) )
	{
		include NV_ROOTDIR . '/' . NV_DATADIR . '/proxies.php' ;
		if( ! empty( $proxy ) )
		{
			$proxy = $proxy[rand( 0, count( $proxy ) - 1 )];
		}
	}
	if( function_exists( "fsockopen" ) and ! in_array( 'fsockopen', $sys_info['disable_functions'] ) )
	{
		if( ! empty( $proxy ) )
		{
			$fp = @fsockopen( $proxy[1], $proxy[2], $errno, $errstr, 10 );
			if( $fp )
			{
				$http_request = "POST " . $url . " HTTP/1.0\r\n";
				$http_request .= "Host: " . $url_info['host'] . ":" . $url_info['port'] . "\r\n";
				$http_request .= "Content-Type: text/xml\r\n";
				$http_request .= "Content-Length: " . strlen( $data ) . "\r\n";
				$http_request .= "User-Agent: " . $agent . "\r\n";

				if( isset( $proxy[3], $proxy[4] ) and ! empty( $proxy[3] ) and ! empty( $proxy[4] ) )
				{
					$http_request .= "Proxy-Authorization: Basic " . base64_encode( $proxy[3] . ":" . $proxy[4] ) . "\r\n";
				}
				$http_request .= "\r\n";
				$response = '';
				fputs( $fp, $http_request );
				fputs( $fp, $data );
				while( ! feof( $fp ) )
					$response .= fgets( $fp, 64000 );
				fclose( $fp );
				list( $header, $result ) = preg_split( "/\r?\n\r?\n/", $response, 2 );

				unset( $matches );
				preg_match( "/^HTTP\/[0-9\.]+\s+(\d+)\s+/", $header, $matches );
				if( ! isset( $matches[1] ) or ( isset( $matches[1] ) and $matches[1] != 200 ) )
				{
					if( ! empty( $errstr ) )
					{
						return array( 2, trim( strip_tags( $errstr . "(" . $errno . ")" ) ) );
					}
					return array( 3, $lang_module['rpc_error_unknown'] );
				}

				unset( $matches1, $matches2 );
				if( preg_match( "/\<member\>[\s\n\t\r]*\<name\>[\s\n\t\r]*flerror[\s\n\t\r]*\<\/name\>[\s\n\t\r]*\<value\>[\s\n\t\r]*(\<boolean\>)?[\s\n\t\r]*([0|1]{1})[\s\n\t\r]*(\<\/boolean\>)?[\s\n\t\r]*\<\/value\>[\s\n\t\r]*\<\/member\>/is", $result, $matches1 ) and preg_match( "/\<member\>[\s\n\t\r]*\<name\>[\s\n\t\r]*message[\s\n\t\r]*\<\/name\>[\s\n\t\r]*\<value\>[\s\n\t\r]*(\<string\>)?[\s\n\t\r]*([^\<]*)[\s\n\t\r]*(\<\/string\>)?[\s\n\t\r]*\<\/value\>[\s\n\t\r]*\<\/member\>/is", $result, $matches2 ) )
				{
					return array( ( int )$matches1[2], ( string )$matches2[2] );
				}
				return array( 3, $lang_module['rpc_error_unknown'] );
			}
		}

		$fp = @fsockopen( $url_info['host'], $url_info['port'], $errno, $errstr, 10 );
		if( ! $fp )
		{
			return array( 3, $lang_module['rpc_error_unknown'] );
		}

		$http_request = "POST " . $url_info['path'] . $url_info["query"] . " HTTP/1.0\r\n";
		$http_request .= "Host: " . $url_info['host'] . ":" . $url_info['port'] . "\r\n";
		$http_request .= "Content-Type: text/xml\r\n";
		$http_request .= "Content-Length: " . strlen( $data ) . "\r\n";
		$http_request .= "User-Agent: " . $agent . "\r\n\r\n";
		$response = '';
		fputs( $fp, $http_request );
		fputs( $fp, $data );
		while( ! feof( $fp ) )
			$response .= fgets( $fp, 64000 );
		fclose( $fp );
		list( $header, $result ) = preg_split( "/\r?\n\r?\n/", $response, 2 );

		unset( $matches );
		preg_match( "/^HTTP\/[0-9\.]+\s+(\d+)\s+/", $header, $matches );
		if( ! isset( $matches[1] ) or ( isset( $matches[1] ) and $matches[1] != 200 ) )
		{
			if( ! empty( $errstr ) )
			{
				return array( 2, trim( strip_tags( $errstr . "(" . $errno . ")" ) ) );
			}
			return array( 3, $lang_module['rpc_error_unknown'] );
		}

		unset( $matches1, $matches2 );
		if( preg_match( "/\<member\>[\s\n\t\r]*\<name\>[\s\n\t\r]*flerror[\s\n\t\r]*\<\/name\>[\s\n\t\r]*\<value\>[\s\n\t\r]*(\<boolean\>)?[\s\n\t\r]*([0|1]{1})[\s\n\t\r]*(\<\/boolean\>)?[\s\n\t\r]*\<\/value\>[\s\n\t\r]*\<\/member\>/is", $result, $matches1 ) and preg_match( "/\<member\>[\s\n\t\r]*\<name\>[\s\n\t\r]*message[\s\n\t\r]*\<\/name\>[\s\n\t\r]*\<value\>[\s\n\t\r]*(\<string\>)?[\s\n\t\r]*([^\<]*)[\s\n\t\r]*(\<\/string\>)?[\s\n\t\r]*\<\/value\>[\s\n\t\r]*\<\/member\>/is", $result, $matches2 ) )
		{
			return array( ( int )$matches1[2], ( string )$matches2[2] );
		}
		return array( 3, $lang_module['rpc_error_unknown'] );
	}

	if( ! function_exists( "curl_init" ) or in_array( 'curl_init', $sys_info['disable_functions'] ) or ! function_exists( "curl_exec" ) or in_array( 'curl_exec', $sys_info['disable_functions'] ) )
	{
		return array( 3, $lang_module['rpc_error_unknown'] );
	}

	$header = array( "Content-Type:text/xml", "Host:" . $url_info['host'] . ":" . $url_info['port'], "User-Agent:" . $agent, "Content-length: " . strlen( $data ) );

	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_HEADER, 0 );
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
	curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_USERAGENT, $agent );
	curl_setopt( $ch, CURLOPT_POST, 1 );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );

	if( ! empty( $proxy ) )
	{
		if( $proxy[0] == "SOCKS4" )
		{
			curl_setopt( $ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4 );
		}
		elseif( $proxy[0] == "SOCKS5" )
		{
			curl_setopt( $ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5 );
		}
		else
		{
			curl_setopt( $ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP );
		}

		curl_setopt( $ch, CURLOPT_PROXY, $proxy[1] );
		curl_setopt( $ch, CURLOPT_PROXYPORT, $proxy[2] );

		if( $proxy[3] and $proxy[4] )
		{
			curl_setopt( $ch, CURLOPT_PROXYUSERPWD, $proxy[3] . ':' . $proxy[4] );
		}
	}

	$result['XML'] = curl_exec( $ch );
	$result['ERR'] = trim( curl_error( $ch ) );
	curl_close( $ch );

	unset( $matches1, $matches2 );
	if( preg_match( "/\<member\>[\s\n\t\r]*\<name\>[\s\n\t\r]*flerror[\s\n\t\r]*\<\/name\>[\s\n\t\r]*\<value\>[\s\n\t\r]*(\<boolean\>)?[\s\n\t\r]*([0|1]{1})[\s\n\t\r]*(\<\/boolean\>)?[\s\n\t\r]*\<\/value\>[\s\n\t\r]*\<\/member\>/is", $result['XML'], $matches1 ) and preg_match( "/\<member\>[\s\n\t\r]*\<name\>[\s\n\t\r]*message[\s\n\t\r]*\<\/name\>[\s\n\t\r]*\<value\>[\s\n\t\r]*(\<string\>)?[\s\n\t\r]*([^\<]*)[\s\n\t\r]*(\<\/string\>)?[\s\n\t\r]*\<\/value\>[\s\n\t\r]*\<\/member\>/is", $result['XML'], $matches2 ) )
	{
		return array( ( int )$matches1[2], ( string )$matches2[2] );
	}

	if( ! empty( $result['ERR'] ) ) return array( 2, trim( strip_tags( $result['ERR'] ) ) );

	return array( 3, $lang_module['rpc_error_unknown'] );
}

function nv_rpcXMLCreate( $webtitle, $webhome, $linkpage, $webrss = '', $method = 'weblogUpdates.ping' )
{
	if( $method != "weblogUpdates.ping" ) $method = "weblogUpdates.extendedPing";

	$xml = new DOMDocument( '1.0' );
	$xml->formatOutput = true;
	$xml->preserveWhiteSpace = false;
	$xml->substituteEntities = false;
	$methodCall = $xml->appendChild( $xml->createElement( 'methodCall' ) );
	$methodName = $methodCall->appendChild( $xml->createElement( 'methodName' ) );
	$methodName->nodeValue = $method;

	$params = $methodCall->appendChild( $xml->createElement( 'params' ) );
	$param1 = $params->appendChild( $xml->createElement( 'param' ) );
	$value1 = $param1->appendChild( $xml->createElement( 'value' ) );
	$value1->nodeValue = $webtitle;
	// Tên bài viết hoặc tên site

	$param2 = $params->appendChild( $xml->createElement( 'param' ) );
	$value2 = $param2->appendChild( $xml->createElement( 'value' ) );
	$value2->nodeValue = $webhome;
	// Trang chủ: vinades.vn

	if( $method == "weblogUpdates.extendedPing" )
	{
		$param3 = $params->appendChild( $xml->createElement( 'param' ) );
		$value3 = $param3->appendChild( $xml->createElement( 'value' ) );
		$value3->nodeValue = $linkpage;
		// Đường dẫn đến bài viết

		if( ! empty( $webrss ) )
		{
			$param4 = $params->appendChild( $xml->createElement( 'param' ) );
			$value4 = $param4->appendChild( $xml->createElement( 'value' ) );
			$value4->nodeValue = $webrss;
		}
	}

	return $xml->saveXML();
}