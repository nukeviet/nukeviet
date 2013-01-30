<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['rpc'];
if( nv_function_exists( "curl_init" ) and nv_function_exists( "curl_exec" ) )
{

	/**
	 * Chu y:
	 * Khi them link vao danh sach, can dam bao cau truc nhu sau:
	 * $services[] = array( 'method', 'abc.com', 'link_abc.com' );
	 * trong do: gia tri dau tien co the la weblogUpdates.ping hoac weblogUpdates.extendedPing;
	 * Gia tri thu 2 la ten hien thi cua link
	 * Gia tri thu 3 la link
	 */
	$services = array();
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.com',
		'http://blogsearch.google.com/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.com.vn',
		'http://blogsearch.google.com.vn/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.de',
		'http://blogsearch.google.de/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.es',
		'http://blogsearch.google.es/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.fi',
		'http://blogsearch.google.fi/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.fr',
		'http://blogsearch.google.fr/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.gr',
		'http://blogsearch.google.gr/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.hr',
		'http://blogsearch.google.hr/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.ie',
		'http://blogsearch.google.ie/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.it',
		'http://blogsearch.google.it/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.jp',
		'http://blogsearch.google.jp/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.lt',
		'http://blogsearch.google.lt/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.nl',
		'http://blogsearch.google.nl/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.pl',
		'http://blogsearch.google.pl/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.pt',
		'http://blogsearch.google.pt/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.ro',
		'http://blogsearch.google.ro/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.ru',
		'http://blogsearch.google.ru/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.se',
		'http://blogsearch.google.se/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.sk',
		'http://blogsearch.google.sk/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.us',
		'http://blogsearch.google.us/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.ae',
		'http://blogsearch.google.ae/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.at',
		'http://blogsearch.google.at/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.bg',
		'http://blogsearch.google.bg/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.ch',
		'http://blogsearch.google.ch/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.be',
		'http://blogsearch.google.be/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.ca',
		'http://blogsearch.google.ca/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.cl',
		'http://blogsearch.google.cl/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.co.cr',
		'http://blogsearch.google.co.cr/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.co.hu',
		'http://blogsearch.google.co.hu/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.co.id',
		'http://blogsearch.google.co.id/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.co.il',
		'http://blogsearch.google.co.il/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.co.in',
		'http://blogsearch.google.co.in/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.co.jp',
		'http://blogsearch.google.co.jp/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.co.ma',
		'http://blogsearch.google.co.ma/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.co.nz',
		'http://blogsearch.google.co.nz/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.co.th',
		'http://blogsearch.google.co.th/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.co.uk',
		'http://blogsearch.google.co.uk/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.co.ve',
		'http://blogsearch.google.co.ve/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.co.za',
		'http://blogsearch.google.co.za/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.com.au',
		'http://blogsearch.google.com.au/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.com.co',
		'http://blogsearch.google.com.co/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.com.ar',
		'http://blogsearch.google.com.ar/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.com.br',
		'http://blogsearch.google.com.br/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.com.do',
		'http://blogsearch.google.com.do/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.com.mx',
		'http://blogsearch.google.com.mx/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.com.my',
		'http://blogsearch.google.com.my/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.com.pe',
		'http://blogsearch.google.com.pe/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.com.sa',
		'http://blogsearch.google.com.sa/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.com.sg',
		'http://blogsearch.google.com.sg/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.com.tr',
		'http://blogsearch.google.com.tr/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.com.tw',
		'http://blogsearch.google.com.tw/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.com.ua',
		'http://blogsearch.google.com.ua/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Google.com.uy',
		'http://blogsearch.google.com.uy/ping/RPC2',
		'google.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Pingomatic',
		'http://rpc.pingomatic.com',
		'pingomatic.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Weblogs.com 1',
		'http://rpc.weblogs.com/RPC2',
		'weblogs.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Weblogs.com 2',
		'http://audiorpc.weblogs.com/RPC2',
		'weblogs.png'
	);
	$services[] = array(
		'weblogUpdates.extendedPing',
		'Blo.gs',
		'http://ping.blo.gs/'
	);
	$services[] = array(
		'weblogUpdates.ping',
		'Pubsub.com',
		'http://xping.pubsub.com/ping/',
		'pubsub.png'
	);
	$services[] = array(
		'weblogUpdates.ping',
		'Yandex.ru',
		'http://ping.blogs.yandex.ru/RPC2',
		'yandex.png'
	);
	$services[] = array(
		'weblogUpdates.ping',
		'Feedsky.com',
		'http://www.feedsky.com/api/RPC2',
		'feedsky.png'
	);
	$services[] = array(
		'weblogUpdates.ping',
		'Fc2.com',
		'http://ping.fc2.com',
		'fc2.png'
	);
	$services[] = array(
		'weblogUpdates.ping',
		'Bloggers.jp',
		'http://ping.bloggers.jp/rpc/'
	);
	$services[] = array(
		'weblogUpdates.ping',
		'Myblog.jp',
		'http://ping.myblog.jp'
	);
	$services[] = array(
		'weblogUpdates.ping',
		'Blogpeople 1',
		'http://blogpeople.net/servlet/weblogUpdates'
	);
	$services[] = array(
		'weblogUpdates.ping',
		'Blogpeople 2',
		'http://blogpeople.net/ping'
	);
	$services[] = array(
		'weblogUpdates.ping',
		'Twingly.com',
		'http://rpc.twingly.com/'
	);
	$services[] = array(
		'weblogUpdates.ping',
		'Bloggnytt.se',
		'http://ping.bloggnytt.se'
	);
	$services[] = array(
		'weblogUpdates.ping',
		'Wordblog.de',
		'http://ping.wordblog.de'
	);
	$services[] = array(
		'weblogUpdates.ping',
		'Aitellu.com',
		'http://rpc.aitellu.com'
	);
	$services[] = array(
		'weblogUpdates.ping',
		'Livedoor.com',
		'http://rpc.reader.livedoor.com/ping'
	);
	$services[] = array(
		'weblogUpdates.ping',
		'Newsgator.com',
		'http://services.newsgator.com/ngws/xmlrpcping.aspx'
	);
	$services[] = array(
		'weblogUpdates.ping',
		'Feedblitz.com',
		'http://www.feedblitz.com/f/f.fbz?XmlPing'
	);
	$services[] = array(
		'weblogUpdates.ping',
		'Bloglines.com',
		'http://www.bloglines.com/ping'
	);

	function nv_getRPC( $url, $data )
	{
		global $module_file, $lang_module, $sys_info;

		$userAgents = array( //
			'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1) Gecko/20090624 Firefox/3.5 (.NET CLR 3.5.30729)', //
			'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', //
			'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)', //
			'Mozilla/4.8 [en] (Windows NT 6.0; U)', //
			'Opera/9.25 (Windows NT 6.0; U; en)' //
		);

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
		$url_info['query'] = (isset( $url_info['query'] ) and ! empty( $url_info['query'] )) ? '?' . $url_info['query'] : '';

		$proxy = array();
		if( file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/proxies.php" ) )
		{
			include ( NV_ROOTDIR . "/modules/" . $module_file . "/proxies.php" );
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
						$http_request .= "Proxy-Authorization: Basic " . base64_encode( "" . $proxy[3] . ":" . $proxy[4] . "" ) . "\r\n";
					}
					$http_request .= "\r\n";
					$response = "";
					fputs( $fp, $http_request );
					fputs( $fp, $data );
					while( ! feof( $fp ) )
						$response .= fgets( $fp, 64000 );
					fclose( $fp );
					list( $header, $result ) = preg_split( "/\r?\n\r?\n/", $response, 2 );

					unset( $matches );
					preg_match( "/^HTTP\/[0-9\.]+\s+(\d+)\s+/", $header, $matches );
					if( ! isset( $matches[1] ) or (isset( $matches[1] ) and $matches[1] != 200) )
					{
						if( ! empty( $errstr ) )
						{
							return array(
								2,
								trim( strip_tags( $errstr . "(" . $errno . ")" ) )
							);
						}
						return array(
							3,
							$lang_module['rpc_error_unknown']
						);
					}

					unset( $matches1, $matches2 );
					if( preg_match( "/\<member\>[\s\n\t\r]*\<name\>[\s\n\t\r]*flerror[\s\n\t\r]*\<\/name\>[\s\n\t\r]*\<value\>[\s\n\t\r]*(\<boolean\>)?[\s\n\t\r]*([0|1]{1})[\s\n\t\r]*(\<\/boolean\>)?[\s\n\t\r]*\<\/value\>[\s\n\t\r]*\<\/member\>/is", $result, $matches1 ) and preg_match( "/\<member\>[\s\n\t\r]*\<name\>[\s\n\t\r]*message[\s\n\t\r]*\<\/name\>[\s\n\t\r]*\<value\>[\s\n\t\r]*(\<string\>)?[\s\n\t\r]*([^\<]*)[\s\n\t\r]*(\<\/string\>)?[\s\n\t\r]*\<\/value\>[\s\n\t\r]*\<\/member\>/is", $result, $matches2 ) )
					{
						return array(
							( int )$matches1[2],
							( string )$matches2[2]
						);
					}
					return array(
						3,
						$lang_module['rpc_error_unknown']
					);
				}
			}

			$fp = @fsockopen( $url_info['host'], $url_info['port'], $errno, $errstr, 10 );
			if( ! $fp )
			{
				return array(
					3,
					$lang_module['rpc_error_unknown']
				);
			}

			$http_request = "POST " . $url_info['path'] . $url_info["query"] . " HTTP/1.0\r\n";
			$http_request .= "Host: " . $url_info['host'] . ":" . $url_info['port'] . "\r\n";
			$http_request .= "Content-Type: text/xml\r\n";
			$http_request .= "Content-Length: " . strlen( $data ) . "\r\n";
			$http_request .= "User-Agent: " . $agent . "\r\n\r\n";
			$response = "";
			fputs( $fp, $http_request );
			fputs( $fp, $data );
			while( ! feof( $fp ) )
				$response .= fgets( $fp, 64000 );
			fclose( $fp );
			list( $header, $result ) = preg_split( "/\r?\n\r?\n/", $response, 2 );

			unset( $matches );
			preg_match( "/^HTTP\/[0-9\.]+\s+(\d+)\s+/", $header, $matches );
			if( ! isset( $matches[1] ) or (isset( $matches[1] ) and $matches[1] != 200) )
			{
				if( ! empty( $errstr ) )
				{
					return array(
						2,
						trim( strip_tags( $errstr . "(" . $errno . ")" ) )
					);
				}
				return array(
					3,
					$lang_module['rpc_error_unknown']
				);
			}

			unset( $matches1, $matches2 );
			if( preg_match( "/\<member\>[\s\n\t\r]*\<name\>[\s\n\t\r]*flerror[\s\n\t\r]*\<\/name\>[\s\n\t\r]*\<value\>[\s\n\t\r]*(\<boolean\>)?[\s\n\t\r]*([0|1]{1})[\s\n\t\r]*(\<\/boolean\>)?[\s\n\t\r]*\<\/value\>[\s\n\t\r]*\<\/member\>/is", $result, $matches1 ) and preg_match( "/\<member\>[\s\n\t\r]*\<name\>[\s\n\t\r]*message[\s\n\t\r]*\<\/name\>[\s\n\t\r]*\<value\>[\s\n\t\r]*(\<string\>)?[\s\n\t\r]*([^\<]*)[\s\n\t\r]*(\<\/string\>)?[\s\n\t\r]*\<\/value\>[\s\n\t\r]*\<\/member\>/is", $result, $matches2 ) )
			{
				return array(
					( int )$matches1[2],
					( string )$matches2[2]
				);
			}
			return array(
				3,
				$lang_module['rpc_error_unknown']
			);
		}

		if( ! function_exists( "curl_init" ) or in_array( 'curl_init', $sys_info['disable_functions'] ) or ! function_exists( "curl_exec" ) or in_array( 'curl_exec', $sys_info['disable_functions'] ) )
		{
			return array(
				3,
				$lang_module['rpc_error_unknown']
			);
		}

		$header = array( //
			"Content-Type:text/xml", //
			"Host:" . $url_info['host'] . ":" . $url_info['port'], //
			"User-Agent:" . $agent, //
			"Content-length: " . strlen( $data ) //
		);

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
			return array(
				( int )$matches1[2],
				( string )$matches2[2]
			);
		}

		if( ! empty( $result['ERR'] ) )
			return array(
				2,
				trim( strip_tags( $result['ERR'] ) )
			);

		return array(
			3,
			$lang_module['rpc_error_unknown']
		);
	}

	function nv_rpcXMLCreate( $webtitle, $webhome, $linkpage, $webrss = "", $method = "weblogUpdates.ping" )
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
			// Dường dẫn đến bài viết

			if( ! empty( $webrss ) )
			{
				$param4 = $params->appendChild( $xml->createElement( 'param' ) );
				$value4 = $param4->appendChild( $xml->createElement( 'value' ) );
				$value4->nodeValue = $webrss;
			}
		}

		return $xml->saveXML();
	}

	$id = $nv_Request->get_int( 'id', 'post,get', '' );
	if( $id > 0 )
	{
		$query = $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id` = " . $id . "" );
		$news_contents = $db->sql_fetchrow( $query );
		$nv_redirect = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
		$nv_redirect2 = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&id=" . $id . "&checkss=" . md5( $id . $global_config['sitekey'] . session_id() ) . "&rand=" . nv_genpass();

		$prcservice = ( isset( $module_config[$module_name]['prcservice'] )) ? $module_config[$module_name]['prcservice'] : "";
		$prcservice = ( ! empty( $prcservice )) ? explode( ",", $prcservice ) : array();

		if( $news_contents['id'] > 0 and ! empty( $prcservice ) )
		{
			if( $news_contents['status'] == 1 and $news_contents['publtime'] < NV_CURRENTTIME + 1 and ($news_contents['exptime'] == 0 or $news_contents['exptime'] > NV_CURRENTTIME + 1) )
			{
				if( $nv_Request->get_string( 'checkss', 'post,get', '' ) == md5( $id . $global_config['sitekey'] . session_id() ) )
				{
					$services_active = array();
					foreach( $services as $key => $service )
					{
						if( in_array( $service[1], $prcservice ) )
						{
							$services_active[] = $service;
						}
					}

					$getdata = $nv_Request->get_int( 'getdata', 'post,get', '0' );
					if( empty( $getdata ) )
					{
						$page_title = $lang_module['rpc'] . ": " . $news_contents['title'];
						$xtpl = new XTemplate( "rpc_ping.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
						$xtpl->assign( 'LANG', $lang_module );

						$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
						$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
						$xtpl->assign( 'MODULE_NAME', $module_name );
						$xtpl->assign( 'OP', $op );
						$xtpl->assign( 'LOAD_DATA', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&id=" . $id . "&checkss=" . md5( $id . $global_config['sitekey'] . session_id() ) . "&getdata=1" );

						$xtpl->assign( 'HOME', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
						$xtpl->assign( 'IMGPATH', NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/" . $module_file );
						$a = 0;
						foreach( $services_active as $key => $service )
						{
							$a++;
							$xtpl->assign( 'SERVICE', array(
								'id' => $key,
								'title' => $service[1],
								'class' => ($a % 2 == 0) ? 'class="second"' : '',
								'icon' => (isset( $service[3] ) ? $service[3] : "")
							) );

							if( isset( $service[3] ) and ! empty( $service[3] ) )
							{
								$xtpl->parse( 'main.service.icon' );
							}
							else
							{
								$xtpl->parse( 'main.service.noticon' );
							}
							$xtpl->parse( 'main.service' );
						}
						$xtpl->parse( 'main' );
						$contents = $xtpl->text( 'main' );
					}
					else
					{
						$xml2 = new DOMDocument( '1.0', 'UTF-8' );
						$xml2->formatOutput = true;
						$xml2->preserveWhiteSpace = false;
						$xml2->substituteEntities = false;
						$rs = $xml2->appendChild( $xml2->createElement( 'pingResult' ) );
						$finish = $rs->appendChild( $xml2->createElement( 'finish' ) );

						$timeout = $nv_Request->get_int( "rpct", 'cookie', 0 );
						$timeout = NV_CURRENTTIME - $timeout;
						if( ($timeout != 0) and ($timeout < 60) )
						{
							$timeout = 60 - $timeout;
							$timeout = nv_convertfromSec( $timeout );
							$finish->nodeValue = "glb|" . sprintf( $lang_module['rpc_error_timeout'], $timeout );
							$content = $xml2->saveXML();
							@Header( "Content-Type: text/xml; charset=utf-8" );
							print_r( $content );
							die();
						}

						$listcatid_arr = explode( ",", $news_contents['listcatid'] );
						$catid_i = $listcatid_arr[0];

						$webtitle = htmlspecialchars( nv_unhtmlspecialchars( $news_contents['title'] ), ENT_QUOTES );
						$webhome = nv_url_rewrite( NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA );
						$linkpage = nv_url_rewrite( NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid_i]['alias'] . '/' . $news_contents['alias'] . '-' . $news_contents['id'], 1 );
						$webrss = nv_url_rewrite( NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=rss/" . $global_array_cat[$catid_i]['alias'], 1 );

						$pingtotal = $nv_Request->get_int( 'total', 'post', 0 );
						if( $sys_info['allowed_set_time_limit'] )
						{
							set_time_limit( 0 );
						}
						if( $sys_info['ini_set_support'] )
						{
							ini_set( 'allow_url_fopen', 1 );
							ini_set( 'default_socket_timeout', 200 );
						}

						$sCount = count( $services_active );

						if( $pingtotal > $sCount )
						{
							$finish->nodeValue = "OK";
						}
						else
						{
							for( $i = $pingtotal, $a = 0; $i <= $sCount, $a <= 5; $i++, $a++ )
							{
								if( $a == 5 or $i == $sCount )
								{
									$servicebreak = $rs->appendChild( $xml2->createElement( 'break' ) );
									$servicebreak->nodeValue = $i;

									if( $i == $sCount )
									{
										$nv_Request->set_Cookie( "rpct", NV_CURRENTTIME );
										$finish->nodeValue = "OK";
									}
									else
									{
										$finish->nodeValue = "WAIT";
									}

									break;
								}

								$data = nv_rpcXMLCreate( $webtitle, $webhome, $linkpage, $webrss, $services[$i][0] );
								$results = nv_getRPC( $services[$i][2], $data );

								$service = $rs->appendChild( $xml2->createElement( 'service' ) );
								$serviceID = $service->appendChild( $xml2->createElement( 'id' ) );
								$serviceID->nodeValue = $i;
								$flerrorCode = $service->appendChild( $xml2->createElement( 'flerrorCode' ) );
								$flerrorCode->nodeValue = $results[0];
								$flerrorMes = $service->appendChild( $xml2->createElement( 'message' ) );
								$flerrorMes->nodeValue = $results[1];
							}
						}

						$content = $xml2->saveXML();

						@Header( "Content-Type: text/xml; charset=utf-8" );
						print_r( $content );
						die();
					}
				}
				else
				{
					$msg1 = $lang_module['content_saveok'];
					$msg2 = $lang_module['content_main'] . " " . $module_info['custom_title'];

					$contents = "<table><tr><td>";
					$contents .= "<div align=\"center\">";
					$contents .= "<strong>" . $msg1 . "</strong><br /><br />\n";
					$contents .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\" /><br /><br />\n";
					$contents .= "<strong><a href=\"" . $nv_redirect2 . "\">" . $lang_module['rpc_ping_page'] . "</a></strong>";
					$contents .= " -  <strong><a href=\"" . $nv_redirect . "\">" . $msg2 . "</a></strong>";
					$contents .= "</div>";
					$contents .= "</td></tr></table>";
					$contents .= "<meta http-equiv=\"refresh\" content=\"3;url=" . $nv_redirect2 . "\" />";
				}
			}
			else
			{
				$contents = "<meta http-equiv=\"refresh\" content=\"1;url=" . $nv_redirect . "\" />";
			}
		}
		else
		{
			$contents = "<meta http-equiv=\"refresh\" content=\"1;url=" . $nv_redirect . "\" />";
		}
	}
	else
	{
		if( $nv_Request->isset_request( 'submitprcservice', 'post' ) )
		{
			$prcservice = $nv_Request->get_array( 'prcservice', 'post' );
			$prcservice = implode( ",", $prcservice );
			$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES('" . NV_LANG_DATA . "', " . $db->dbescape( $module_name ) . ", " . $db->dbescape( "prcservice" ) . ", " . $db->dbescape( $prcservice ) . ")" );
			nv_del_moduleCache( 'settings' );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&rand=" . nv_genpass() );
			die();
		}
		$prcservice = ( isset( $module_config[$module_name]['prcservice'] )) ? $module_config[$module_name]['prcservice'] : "";
		$prcservice = ( ! empty( $prcservice )) ? explode( ",", $prcservice ) : array();

		$xtpl = new XTemplate( "rpc_setting.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		$xtpl->assign( 'LANG', $lang_module );

		$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
		$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
		$xtpl->assign( 'MODULE_NAME', $module_name );
		$xtpl->assign( 'OP', $op );

		$xtpl->assign( 'HOME', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
		$xtpl->assign( 'IMGPATH', NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/" . $module_file );
		$a = 0;
		foreach( $services as $key => $service )
		{
			$a++;
			$xtpl->assign( 'SERVICE', array(
				'id' => $key,
				'title' => $service[1],
				'class' => ($a % 2 == 0) ? 'class="second"' : '',
				'checked' => ( ! isset( $module_config[$module_name]['prcservice'] ) or in_array( $service[1], $prcservice )) ? 'checked="checked"' : '',
				'icon' => (isset( $service[3] ) ? $service[3] : "")
			) );
			if( isset( $service[3] ) and ! empty( $service[3] ) )
			{
				$xtpl->parse( 'main.service.icon' );
			}
			else
			{
				$xtpl->parse( 'main.service.noticon' );
			}
			$xtpl->parse( 'main.service' );
		}

		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );
	}
}
else
{
	$contents = 'System not support function php "curl_init" !';
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>