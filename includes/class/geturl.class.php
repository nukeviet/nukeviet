<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 5-8-2010 1:13
 */

class UrlGetContents
{
	private $allow_methods = array();
	private $safe_mode;
	private $open_basedir;
	private $url_info = false;
	private $login = '';
	private $password = '';
	private $ref = '';
	private $user_agent = '';
	private $redirectCount = 0;
	public $time_limit = 60;
	private $disable_functions = array();

	/**
	 * UrlGetContents::__construct()
	 *
	 * @return
	 */
	function __construct( $global_config, $time_limit = 60 )
	{
		$this->user_agent = 'NUKEVIET CMS ' . $global_config['version'] . '. Developed by VINADES. Url: http://nukeviet.vn. Code: ' . md5( $global_config['sitekey'] );

		$disable_functions = (ini_get( 'disable_functions' ) != '' and ini_get( 'disable_functions' ) != false) ? array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "disable_functions" ) ) ) : array();
		if( extension_loaded( 'suhosin' ) )
		{
			$disable_functions = array_merge( $disable_functions, array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "suhosin.executor.func.blacklist" ) ) ) );
		}
		$this->disable_functions = $disable_functions;

		$safe_mode = (ini_get( 'safe_mode' ) == '1' || strtolower( ini_get( 'safe_mode' ) ) == 'on') ? 1 : 0;

		$this->time_limit = ( int )$time_limit;

		if( !$safe_mode and function_exists( 'set_time_limit' ) and !in_array( 'set_time_limit', $this->disable_functions ) )
		{
			set_time_limit( $this->time_limit );
		}

		if( function_exists( 'ini_set' ) and !in_array( 'ini_set', $this->disable_functions ) )
		{
			ini_set( 'allow_url_fopen', 1 );
			ini_set( 'default_socket_timeout', $this->time_limit );
			$memoryLimitMB = ( integer )ini_get( 'memory_limit' );
			if( $memoryLimitMB < 64 )
			{
				ini_set( 'memory_limit', '64M' );
			}
			ini_set( 'user_agent', $this->user_agent );
		}

		if( extension_loaded( 'curl' ) and (empty( $this->disable_functions ) or (!empty( $this->disable_functions ) and !preg_grep( '/^curl\_/', $this->disable_functions ))) )
		{
			$this->allow_methods[] = 'curl';
		}

		if( function_exists( 'fsockopen' ) and !in_array( 'fsockopen', $this->disable_functions ) )
		{
			$this->allow_methods[] = 'fsockopen';
		}

		if( ini_get( 'allow_url_fopen' ) == '1' or strtolower( ini_get( 'allow_url_fopen' ) ) == 'on' )
		{
			if( function_exists( 'fopen' ) and !in_array( 'fopen', $this->disable_functions ) )
			{
				$this->allow_methods[] = 'fopen';
			}

			if( function_exists( 'file_get_contents' ) and !in_array( 'file_get_contents', $this->disable_functions ) )
			{
				$this->allow_methods[] = 'file_get_contents';
			}
		}

		if( function_exists( 'file' ) and !in_array( 'file', $this->disable_functions ) )
		{
			$this->allow_methods[] = 'file';
		}

		if( ini_get( 'safe_mode' ) == '1' || strtolower( ini_get( 'safe_mode' ) ) == 'on' )
		{
			$this->safe_mode = true;
		}
		else
		{
			$this->safe_mode = false;
		}

		$this->open_basedir = @ini_get( 'open_basedir' ) ? true : false;
	}

	/**
	 * UrlGetContents::check_url()
	 *
	 * @param integer $is_200
	 * @return
	 */
	private function check_url( $is_200 = 0 )
	{
		$allow_url_fopen = (ini_get( 'allow_url_fopen' ) == '1' || strtolower( ini_get( 'allow_url_fopen' ) ) == 'on') ? 1 : 0;

		if( function_exists( 'get_headers' ) and !in_array( 'get_headers', $this->disable_functions ) and $allow_url_fopen == 1 )
		{
			$res = get_headers( $this->url_info['uri'] );
		}
		elseif( function_exists( 'curl_init' ) and !in_array( 'curl_init', $this->disable_functions ) and function_exists( 'curl_exec' ) and !in_array( 'curl_exec', $this->disable_functions ) )
		{
			$url_info = @parse_url( $this->url_info['uri'] );
			$port = isset( $url_info['port'] ) ? intval( $url_info['port'] ) : 80;

			$userAgents = array(
				'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0',
				'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
				'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)',
				'Mozilla/4.8 [en] (Windows NT 6.0; U)',
				'Opera/9.25 (Windows NT 6.0; U; en)'
			);
			srand( ( float )microtime() * 10000000 );
			$rand = array_rand( $userAgents );
			$agent = $userAgents[$rand];

			$curl = curl_init( $this->url_info['uri'] );
			curl_setopt( $curl, CURLOPT_HEADER, true );
			curl_setopt( $curl, CURLOPT_NOBODY, true );

			curl_setopt( $curl, CURLOPT_PORT, $port );
			if( !$this->safe_mode and !$this->open_basedir )
			{
				curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
			}

			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

			curl_setopt( $curl, CURLOPT_TIMEOUT, 15 );
			curl_setopt( $curl, CURLOPT_USERAGENT, $agent );

			$response = curl_exec( $curl );
			curl_close( $curl );

			if( $response === false )
			{
				return false;
			}
			else
			{
				$res = explode( "\n", $response );
			}
		}
		elseif( function_exists( "fsockopen" ) and !in_array( 'fsockopen', $this->disable_functions ) and function_exists( "fgets" ) and !in_array( 'fgets', $this->disable_functions ) )
		{
			$res = array();
			$url_info = parse_url( $this->url_info['uri'] );
			$port = isset( $url_info['port'] ) ? intval( $url_info['port'] ) : 80;
			$fp = fsockopen( $url_info['host'], $port, $errno, $errstr, 15 );
			if( $fp )
			{
				$path = !empty( $url_info['path'] ) ? $url_info['path'] : '/';
				$path .= !empty( $url_info['query'] ) ? '?' . $url_info['query'] : '';

				fputs( $fp, "HEAD " . $path . " HTTP/1.0\r\n" );
				fputs( $fp, "Host: " . $url_info['host'] . ":" . $port . "\r\n" );
				fputs( $fp, "Connection: close\r\n\r\n" );

				while( !feof( $fp ) )
				{
					if( $header = trim( fgets( $fp, 1024 ) ) )
					{
						$res[] = $header;
					}
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}

		if( !$res )
			return false;
		if( preg_match( '/(200)/', $res[0] ) )
			return true;
		if( $is_200 > 5 )
			return false;
		if( preg_match( '/(301)|(302)|(303)/', $res[0] ) )
		{
			foreach( $res as $k => $v )
			{
				if( preg_match( "/location:\s(.*?)$/is", $v, $matches ) )
				{
					++$is_200;
					$location = trim( $matches[1] );
					if( substr( $location, 0, 1 ) == '/' )
					{
						$location = $this->url_info['scheme'] . '://' . $this->url_info['host'] . $location;
					}
					$this->url_info = $this->url_get_info( $location );
					if( !$this->url_info or !isset( $this->url_info['scheme'] ) )
					{
						return false;
					}
					return $this->check_url( $is_200 );
				}
			}
		}
		return false;
	}

	/**
	 * UrlGetContents::generate_newUrl()
	 *
	 * @param mixed $url
	 * @return
	 */
	private function generate_newUrl( $url )
	{
		$m = trim( $url );

		if( substr( $m, 0, 1 ) == '/' )
		{
			$newurl = $this->url_info['scheme'] . '://' . $this->url_info['host'] . $m;
		}
		else
		{
			$newurl = $m;
		}

		return $newurl;
	}

	/**
	 * UrlGetContents::curl_Get()
	 *
	 * @param mixed $url
	 * @param string $login
	 * @param string $password
	 * @param string $ref
	 * @return
	 */
	private function curl_Get()
	{
		$curlHandle = curl_init();
		curl_setopt( $curlHandle, CURLOPT_ENCODING, '' );
		curl_setopt( $curlHandle, CURLOPT_URL, $this->url_info['uri'] );
		curl_setopt( $curlHandle, CURLOPT_HEADER, true );
		curl_setopt( $curlHandle, CURLOPT_RETURNTRANSFER, 1 );

		if( !empty( $this->login ) )
		{
			curl_setopt( $curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
			curl_setopt( CURLOPT_USERPWD, '[' . $this->login . ']:[' . $this->password . ']' );
		}

		curl_setopt( $curlHandle, CURLOPT_USERAGENT, $this->user_agent );

		if( !empty( $this->ref ) )
		{
			curl_setopt( $curlHandle, CURLOPT_REFERER, urlencode( $this->ref ) );
		}
		else
		{
			curl_setopt( $curlHandle, CURLOPT_REFERER, $this->url_info['uri'] );
		}

		if( !$this->safe_mode and !$this->open_basedir )
		{
			curl_setopt( $curlHandle, CURLOPT_FOLLOWLOCATION, 1 );
			curl_setopt( $curlHandle, CURLOPT_MAXREDIRS, 10 );
		}

		curl_setopt( $curlHandle, CURLOPT_TIMEOUT, 30 );

		$result = curl_exec( $curlHandle );

		if( curl_errno( $curlHandle ) == 23 || curl_errno( $curlHandle ) == 61 )
		{
			curl_setopt( $curlHandle, CURLOPT_ENCODING, 'none' );
			$result = curl_exec( $curlHandle );
		}

		if( curl_errno( $curlHandle ) )
		{
			curl_close( $curlHandle );
			return false;
		}

		list( $header, $result ) = preg_split( "/\r?\n\r?\n/", $result, 2 );

		$response = curl_getinfo( $curlHandle );

		if( $this->safe_mode or $this->open_basedir )
		{
			if( $response['http_code'] == 301 || $response['http_code'] == 302 || $response['http_code'] == 303 )
			{
				if( preg_match( '/^(Location:|URI:)[\s]*(.*?)$/m', $header, $matches ) and $this->redirectCount <= 5 )
				{
					++$this->redirectCount;

					$newurl = $this->generate_newUrl( $matches[2] );

					curl_setopt( $curlHandle, CURLOPT_URL, $newurl );

					$this->url_info = $this->url_get_info( $newurl );

					if( !$this->url_info or !isset( $this->url_info['scheme'] ) )
					{
						return false;
					}

					return $this->curl_Get();
				}
			}
		}

		if( ($response['http_code'] < 200) || (300 <= $response['http_code']) )
		{
			curl_close( $curlHandle );
			return false;
		}

		curl_close( $curlHandle );

		if( preg_match( '/(<meta http-equiv=)(.*?)(refresh)(.*?)(url=)([^\'\"]+)[\'|"]\s*[\/]*>/is', $result, $matches ) and $this->redirectCount <= 5 )
		{
			++$this->redirectCount;

			$newurl = $this->generate_newUrl( $matches[6] );

			curl_setopt( $curlHandle, CURLOPT_URL, $newurl );

			$this->url_info = $this->url_get_info( $newurl );

			if( !$this->url_info or !isset( $this->url_info['scheme'] ) )
			{
				return false;
			}

			return $this->curl_Get();
		}

		return $result;
	}

	/**
	 * UrlGetContents::fsockopen_Get()
	 *
	 * @param mixed $url
	 * @param string $login
	 * @param string $password
	 * @param string $ref
	 * @return
	 */
	private function fsockopen_Get()
	{
		if( strtolower( $this->url_info['scheme'] ) == 'https' )
		{
			$this->url_info['host'] = 'ssl://' . $this->url_info['host'];
			$this->url_info['port'] = 443;
		}

		$fp = @fsockopen( $this->url_info['host'], $this->url_info['port'], $errno, $errstr, 30 );
		if( !$fp )
		{
			return false;
		}

		$request = 'GET ' . $this->url_info['path'] . $this->url_info['query'];
		$request .= " HTTP/1.0\r\n";
		$request .= 'Host: ' . $this->url_info['host'];

		if( $this->url_info['port'] != 80 )
		{
			$request .= ':' . $this->url_info['port'];
		}
		$request .= "\r\n";

		$request .= "Connection: Close\r\n";
		$request .= "User-Agent: " . $this->user_agent . "\r\n\r\n";

		if( function_exists( 'gzinflate' ) )
		{
			$request .= "Accept-Encoding: gzip,deflate\r\n";
		}

		$request .= "Accept: */*\r\n";

		if( !empty( $this->ref ) )
		{
			$request .= "Referer: " . urlencode( $this->ref ) . "\r\n";
		}
		else
		{
			$request .= "Referer: " . $this->url_info['uri'] . "\r\n";
		}

		if( !empty( $this->login ) )
		{
			$request .= 'Authorization: Basic ';
			$request .= base64_encode( $this->login . ':' . $this->password );
			$request .= "\r\n";
		}

		$request .= "\r\n";

		if( @fwrite( $fp, $request ) === false )
		{
			@fclose( $fp );
			return false;
		}

		@stream_set_blocking( $fp, true );
		@stream_set_timeout( $fp, 30 );
		$in_f = @stream_get_meta_data( $fp );

		$response = '';

		while( (!@feof( $fp )) && (!$in_f['timed_out']) )
		{
			$response .= @fgets( $fp, 4096 );
			$inf = @stream_get_meta_data( $fp );
			if( $inf['timed_out'] )
			{
				@fclose( $fp );
				return false;
			}
		}

		if( function_exists( 'gzinflate' ) and substr( $response, 0, 8 ) == "\x1f\x8b\x08\x00\x00\x00\x00\x00" )
		{
			$response = substr( $response, 10 );
			$response = gzinflate( $response );
		}

		@fclose( $fp );

		list( $header, $result ) = preg_split( "/\r?\n\r?\n/", $response, 2 );

		if( preg_match( '/^(Location:|URI:)[\s]*(.*?)$/m', $header, $matches ) and $this->redirectCount <= 5 )
		{
			++$this->redirectCount;

			$newurl = $this->generate_newUrl( $matches[2] );

			$this->url_info = $this->url_get_info( $newurl );

			if( !$this->url_info or !isset( $this->url_info['scheme'] ) )
			{
				return false;
			}

			return $this->fsockopen_Get();
		}

		preg_match( "/^HTTP\/[0-9\.]+\s+(\d+)\s+/", $header, $matches );
		if( $matches == array() )
			return false;
		if( $matches[1] != 200 )
			return false;

		if( preg_match( '/(<meta http-equiv=)(.*?)(refresh)(.*?)(url=)([^\'\"]+)[\'|"]\s*[\/]*>/is', $result, $matches ) and $this->redirectCount <= 5 )
		{
			++$this->redirectCount;

			$newurl = $this->generate_newUrl( $matches[6] );

			$this->url_info = $this->url_get_info( $newurl );

			if( !$this->url_info or !isset( $this->url_info['scheme'] ) )
			{
				return false;
			}

			return $this->fsockopen_Get();
		}

		return $result;
	}

	/**
	 * UrlGetContents::fopen_Get()
	 *
	 * @param mixed $url
	 * @return
	 */
	private function fopen_Get()
	{
		$ctx = stream_context_create( array( 'http' => array(
				'method' => 'GET',
				'max_redirects' => '2',
				'ignore_errors' => '0',
				'timeout' => 30
			) ) );

		if( ($fd = @fopen( $this->url_info['uri'], 'rb', 0, $ctx )) === false )
		{
			return false;
		}

		$result = '';
		while( ($data = fread( $fd, 4096 )) != '' )
		{
			$result .= $data;
		}
		fclose( $fd );

		return $result;
	}

	/**
	 * UrlGetContents::file_get_contents_Get()
	 *
	 * @param mixed $url
	 * @return
	 */
	private function file_get_contents_Get()
	{
		$ctx = stream_context_create( array( 'http' => array(
				'method' => 'GET',
				'max_redirects' => '5',
				'ignore_errors' => '0',
				'timeout' => 30
			) ) );

		return file_get_contents( $this->url_info['uri'], 0, $ctx );
	}

	/**
	 * UrlGetContents::file_Get()
	 *
	 * @param mixed $url
	 * @return void
	 */
	private function file_Get()
	{
		$ctx = stream_context_create( array( 'http' => array(
				'method' => 'GET',
				'max_redirects' => '5',
				'ignore_errors' => '0',
				'timeout' => 30
			) ) );

		$result = file( $this->url_info['uri'], 0, $ctx );

		if( $result )
			return implode( $result );
		return '';
	}

	/**
	 * UrlGetContents::url_get_info()
	 *
	 * @param mixed $url
	 * @return
	 */
	private function url_get_info( $url )
	{
		//URL: http://username:password@www.example.com:80/dir/page.php?foo=bar&foo2=bar2#bookmark
		$url_info = @parse_url( $url );

		//[host] => www.example.com
		if( !isset( $url_info['host'] ) )
		{
			return false;
		}

		//[port] => :80
		$url_info['port'] = isset( $url_info['port'] ) ? $url_info['port'] : 80;

		//[login] => username:password@
		$url_info['login'] = '';
		if( isset( $url_info['user'] ) )
		{
			$url_info['login'] = $url_info['user'];
			if( isset( $url_info['pass'] ) )
			{
				$url_info['login'] .= ':' . $url_info['pass'];
			}
			$url_info['login'] .= '@';
		}

		//[path] => /dir/page.php
		if( isset( $url_info['path'] ) )
		{
			if( substr( $url_info['path'], 0, 1 ) != '/' )
			{
				$url_info['path'] = '/' . $url_info['path'];
			}
			$path_array = explode( '/', $url_info['path'] );
			$path_array = array_map( 'rawurlencode', $path_array );
			$url_info['path'] = implode( '/', $path_array );
		}
		else
		{
			$url_info['path'] = '/';
		}

		//[query] => ?foo=bar&foo2=bar2
		$url_info['query'] = (isset( $url_info['query'] ) and !empty( $url_info['query'] )) ? '?' . $url_info['query'] : '';

		//[fragment] => bookmark
		$url_info['fragment'] = isset( $url_info['fragment'] ) ? $url_info['fragment'] : '';

		//[file] => page.php
		$url_info['file'] = explode( '/', $url_info['path'] );
		$url_info['file'] = array_pop( $url_info['file'] );

		//[dir] => /dir
		$url_info['dir'] = substr( $url_info['path'], 0, strrpos( $url_info['path'], '/' ) );

		//[base] => http://www.example.com/dir
		$url_info['base'] = $url_info['scheme'] . '://' . $url_info['host'] . $url_info['dir'];

		//[uri] => http://username:password@www.example.com:80/dir/page.php?#bookmark
		$url_info['uri'] = $url_info['scheme'] . '://' . $url_info['login'] . $url_info['host'];
		if( $url_info['port'] != 80 )
		{
			$url_info['uri'] .= ':' . $url_info['port'];
		}
		$url_info['uri'] .= $url_info['path'] . $url_info['query'];
		/*
		 * if ( $url_info['fragment'] != '' ) { $url_info['uri'] .= '#' . $url_info['fragment']; }
		 */

		return $url_info;
	}

	/**
	 * UrlGetContents::get()
	 *
	 * @param mixed $url
	 * @param string $login
	 * @param string $password
	 * @param string $ref
	 * @return
	 */
	public function get( $url, $login = '', $password = '', $ref = '' )
	{
		$this->url_info = $this->url_get_info( $url );

		if( !$this->url_info or !isset( $this->url_info['scheme'] ) )
			return false;

		if( $this->check_url() === false )
			return false;

		$this->login = ( string )$login;
		$this->password = ( string )$password;
		$this->ref = ( string )$ref;

		if( !empty( $this->allow_methods ) )
		{
			foreach( $this->allow_methods as $method )
			{
				$result = call_user_func( array(
					&$this,
					$method . '_Get'
				) );

				if( !empty( $result ) )
				{
					return $result;
					break;
				}
			}
		}

		return '';
	}

}