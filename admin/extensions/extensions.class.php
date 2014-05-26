<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2/3/2012, 9:10
 */

if( ! defined( 'NV_IS_FILE_EXTENSIONS' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_EXTENSIONS_CLASS' ) ) define( 'NV_EXTENSIONS_CLASS', true );

/**
 * NV_Extensions
 *
 * @package NukeViet
 * @author VINADES.,JSC
 * @copyright VINADES.,JSC
 * @version 2014
 * @access public
 */
class NV_Extensions
{
	/**
	 * NukeViet Store API url
	 * This url is private and use on all nukeviet system
	 */
	private $api_url = 'http://api.nukeviet.vn/store/';
	
	/**
	 * Variable to set dir
	 */
	private $root_dir = '';
	private $tmp_dir = '';
	
	/**
	 * All site config
	 */
	private $site_config = array(
		'version' => '4.x',
		'sitekey' => 'default',
	);
	
	/**
	 * Error message and error code
	 * Error code help user to show error message with optional language
	 * Error message is default by english.
	 */
	public $error = array();
	
	public function __construct( $config, $tmp_dir = 'tmp' )
	{
		/**
		 * Important!
		 * This class must be put in a file which be stored in 2 subdir with root dir
		 * If you store this file on other folder, you must change $store_dir below
		 */
		$store_dir = '/../../';
		$this->root_dir = preg_replace( '/[\/]+$/', '', str_replace( DIRECTORY_SEPARATOR, '/', realpath( dirname( __file__ ) . $store_dir ) ) );
		
		// Custom some config
		if( ! empty( $config['version'] ) )
		{
			$this->site_config['version'] = $config['version'];
		}
		if( ! empty( $config['version'] ) )
		{
			$this->site_config['sitekey'] = $config['sitekey'];
		}
		
		// Find my domain
		$server_name = preg_replace( '/^[a-z]+\:\/\//i', '', $this->get_Env( array( 'HTTP_HOST', 'SERVER_NAME' ) ) );
		$server_protocol = strtolower( preg_replace( '/^([^\/]+)\/*(.*)$/', '\\1', $this->get_Env( 'SERVER_PROTOCOL' ) ) ) . ( ( $this->get_Env( 'HTTPS' ) == 'on' ) ? 's' : '' );
		$server_port = $this->get_Env( 'SERVER_PORT' );
		$server_port = ( $server_port == '80' ) ? '' : ( ':' . $server_port );
		
		if( filter_var( $server_name, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) === false )
		{
			$this->site_config['my_domain'] = $server_protocol . '://' . $server_name . $server_port;
		}
		else
		{
			$this->site_config['my_domain'] = $server_protocol . '://[' . $server_name . ']' . $server_port;
		}
		
		// Check user custom temp dir
		$this->tmp_dir = $this->root_dir . '/' . $tmp_dir;
		
		if( ! is_dir( $this->tmp_dir ) )
		{
			$this->tmp_dir = $this->root_dir . '/tmp';
		}
	}
	
	private function request( $url, $args )
	{
		$defaults = array(
			'method' => 'GET',
			'timeout' => 5,
			'redirection' => 5,
			'requested' => 0,  // Number requested if redirection
			'httpversion' => 1.0,
			'user-agent' => 'NUKEVIET CMS ' . $this->site_config['version'] . '. Developed by VINADES. Url: http://nukeviet.vn. Code: ' . md5( $this->site_config['sitekey'] ),
			'reject_unsafe_urls' => false,
			'blocking' => true,
			'headers' => array(),
			'cookies' => array(),
			'body' => null,
			'compress' => false,
			'decompress' => true,
			'sslverify' => true,
			'sslcertificates' => $this->root_dir . '/includes/certificates/ca-bundle.crt',
			'stream' => false,
			'filename' => null,
			'limit_response_size' => null,
		);
		
		// Get full args
		$args = $this->build_args( $args, $defaults );
		
		// Get url info
		$infoURL = @parse_url( $url );
		
		// Check valid url
		if( empty( $url ) or empty( $infoURL['scheme'] ) )
		{
			$this->set_error(1);
			return false;
		}
		
		// Set SSL
		$args['ssl'] = $infoURL['scheme'] == 'https' or $infoURL['scheme'] == 'ssl';
		
		/**
		 * Block url
		 * By basic version, all url will be enabled and no blocking by check function
		 */
	 	//if( $this->is_blocking( $url ) )
	 	//{
		//	$this->set_error(2);
		//	return false;
	 	//}

		// Determine if this request is to OUR install of NukeViet
		$homeURL = parse_url( $this->site_config['my_domain'] );
		$args['local'] = $homeURL['host'] == $infoURL['host'] || 'localhost' == $infoURL['host'];
		unset( $homeURL );
		
		// If Stream but no file, default is a file in temp dir with base $url name
		if( $args['stream'] and empty( $args['filename'] ) )
		{
			$args['filename'] = $this->tmp_dir . '/' . basename( $url );
		}
		
		// Check if streaming a file
		if( $args['stream'] )
		{
			$args['blocking'] = true;
			if( ! @is_writable( dirname( $args['filename'] ) ) )
			{
				$this->set_error(3);
				return false;
			}
		}
		
		// Default header is an empty array
		if( is_null( $args['headers'] ) )
		{
			$args['headers'] = array();
		}
		
		if( ! is_array( $args['headers'] ) )
		{
			$processedHeaders = NV_Extensions::processHeaders( $args['headers'], $url );
			$args['headers'] = $processedHeaders['headers'];
		}

		if( isset( $args['headers']['User-Agent'] ) )
		{
			$args['user-agent'] = $args['headers']['User-Agent'];
			unset( $args['headers']['User-Agent'] );
		}

		if( isset( $args['headers']['user-agent'] ) )
		{
			$args['user-agent'] = $args['headers']['user-agent'];
			unset( $args['headers']['user-agent'] );
		}

		if( '1.1' == $args['httpversion'] and ! isset( $args['headers']['connection'] ) )
		{
			$args['headers']['connection'] = 'close';
		}
		
		NV_Extensions::buildCookieHeader( $args );
		
		mbstring_binary_safe_encoding();
		
		if( ! isset( $args['headers']['Accept-Encoding'] ) )
		{
			if( $encoding = WP_Http_Encoding::accept_encoding( $url, $args ) )
			{
				$args['headers']['Accept-Encoding'] = $encoding;
			}	
		}

		if( ( ! is_null( $args['body'] ) and '' != $args['body'] ) || 'POST' == $args['method'] || 'PUT' == $args['method'] )
		{
			if( is_array( $args['body'] ) || is_object( $args['body'] ) )
			{
				$args['body'] = http_build_query( $args['body'], null, '&' );

				if( ! isset( $args['headers']['Content-Type'] ) )
				{
					$args['headers']['Content-Type'] = 'application/x-www-form-urlencoded; charset=' . get_option( 'blog_charset' );
				}
			}

			if( '' === $args['body'] )
			{
				$args['body'] = null;
			}	

			if( ! isset( $args['headers']['Content-Length'] ) and ! isset( $args['headers']['content-length'] ) )
			{
				$args['headers']['Content-Length'] = strlen( $args['body'] );
			}
		}

		$response = $this->_dispatch_request( $url, $args );

		reset_mbstring_encoding();

		if( is_wp_error( $response ) )
			return $response;

		// Append cookies that were used in this request to the response
		if( ! empty( $r['cookies'] ) )
		{
			$cookies_set = wp_list_pluck( $response['cookies'], 'name' );
			
			foreach( $r['cookies'] as $cookie )
			{
				if( ! in_array( $cookie->name, $cookies_set ) and $cookie->test( $url ) )
				{
					$response['cookies'][] = $cookie;
				}
			}
		}

		return $response;
	}
	
	private function get_Env( $key )
	{
		if( ! is_array( $key ) )
		{
			$key = array( $key );
		}
		
		foreach( $key as $k )
		{
			if( isset( $_SERVER[$k] ) ) return $_SERVER[$k];
			elseif( isset( $_ENV[$k] ) ) return $_ENV[$k];
			elseif( @getenv( $k ) ) return @getenv( $k );
			elseif( function_exists( 'apache_getenv' ) && apache_getenv( $k, true ) ) return apache_getenv( $k, true );
		}
		
		return '';
	}
	
	private function build_args( $args, $defaults )
	{
		if( is_object( $args ) )
		{
			$args = get_object_vars( $args );
		}
		elseif( ! is_array( $args ) )
		{
			$args = $this->parse_str( $args );
		}
		
		return array_merge( $defaults, $args );
	}
	
	private function parse_str( $str )
	{
		$r = array();
		parse_str( $str, $r );
		
		if( get_magic_quotes_gpc() )
		{
			$r = array_map( "stripslashes", $r );
		}
		
		return $r;
	}
	
	private function set_error( $code )
	{
		$code = intval( $code );
		$message = "";
		
		switch( $code )
		{
			case 1: $message = "A valid URL was not provided."; break;
			case 2: $message = "User has blocked requests through HTTP."; break;
			case 3: $message = "Destination directory for file streaming does not exist or is not writable."; break;
			default: $message = "There are some unknow errors had been occurred.";
		}
		
		$this->error['code'] = $code;
		$this->error['message'] = $message;
	}
	
	public function reset()
	{
		$this->error = array();
	}
	
	public static function processHeaders( $headers, $url = '' )
	{
		// Split headers, one per array element
		if( is_string( $headers ) )
		{
			$headers = str_replace( "\r\n", "\n", $headers );
			$headers = preg_replace( '/\n[ \t]/', ' ', $headers );
			$headers = explode( "\n", $headers );
		}

		$response = array(
			'code' => 0,
			'message' => ''
		);

		// If a redirection has taken place, The headers for each page request may have been passed.
		// In this case, determine the final HTTP header and parse from there.
		for( $i = sizeof( $headers ) - 1; $i >= 0; $i -- )
		{
			if( ! empty( $headers[$i] ) and false === strpos( $headers[$i], ':' ) )
			{
				$headers = array_splice( $headers, $i );
				break;
			}
		}

		$cookies = array();
		$newheaders = array();
		foreach( ( array ) $headers as $tempheader )
		{
			if( empty( $tempheader ) )
			{
				continue;
			}				

			if( false === strpos( $tempheader, ':' ) )
			{
				$stack = explode( ' ', $tempheader, 3 );
				$stack[] = '';
				list( , $response['code'], $response['message'] ) = $stack;
				continue;
			}

			list( $key, $value ) = explode( ':', $tempheader, 2 );

			$key = strtolower( $key );
			$value = trim( $value );

			if( isset( $newheaders[$key] ) )
			{
				if( ! is_array( $newheaders[$key] ) )
				{
					$newheaders[$key] = array( $newheaders[$key] );
				}
					
				$newheaders[$key][] = $value;
			}
			else
			{
				$newheaders[$key] = $value;
			}
			
			if( 'set-cookie' == $key )
			{
				$cookies[] = new NV_http_cookie( $value, $url );
			}
		}

		return array(
			'response' => $response,
			'headers' => $newheaders,
			'cookies' => $cookies
		);
	}
	
	public static function buildCookieHeader( &$args )
	{
		if( ! empty( $args['cookies'] ) )
		{
			// Upgrade any name => value cookie pairs to NV_http_cookie instances
			foreach( $args['cookies'] as $name => $value )
			{
				if( ! is_object( $value ) )
				{
					$args['cookies'][$name] = new NV_http_cookie( array( 'name' => $name, 'value' => $value ) );
				}
			}

			$cookies_header = '';
			foreach( ( array ) $args['cookies'] as $cookie )
			{
				$cookies_header .= $cookie->getHeaderValue() . '; ';
			}

			$cookies_header = substr( $cookies_header, 0, -2 );
			$args['headers']['cookie'] = $cookies_header;
		}
	}
}

class NV_http_cookie{

	/**
	 * Cookie name.
	 * @var string
	 */
	var $name;

	/**
	 * Cookie value.
	 * @var string
	 */
	var $value;

	/**
	 * When the cookie expires.
	 * @var string
	 */
	var $expires;

	/**
	 * Cookie URL path.
	 * @var string
	 */
	var $path;

	/**
	 * Cookie Domain.
	 * @var string
	 */
	var $domain;

	function __construct( $data, $requested_url = '' )
	{
		if( $requested_url )
		{
			$arrURL = @parse_url( $requested_url );
		}
			
		if( isset( $arrURL['host'] ) )
		{
			$this->domain = $arrURL['host'];
		}
			
		$this->path = isset( $arrURL['path'] ) ? $arrURL['path'] : '/';
		
		if( '/' != substr( $this->path, -1 ) )
		{
			$this->path = dirname( $this->path ) . '/';
		}

		if( is_string( $data ) )
		{
			// Assume it's a header string direct from a previous request
			$pairs = explode( ';', $data );

			// Special handling for first pair; name=value. Also be careful of "=" in value
			$name  = trim( substr( $pairs[0], 0, strpos( $pairs[0], '=' ) ) );
			$value = substr( $pairs[0], strpos( $pairs[0], '=' ) + 1 );
			$this->name  = $name;
			$this->value = urldecode( $value );
			array_shift( $pairs ); //Removes name=value from items.

			// Set everything else as a property
			foreach( $pairs as $pair )
			{
				$pair = rtrim( $pair );
				
				if( empty( $pair ) )
				{
					// Handles the cookie ending in ; which results in a empty final pair
					continue;
				}	

				list( $key, $val ) = strpos( $pair, '=' ) ? explode( '=', $pair ) : array( $pair, '' );
				$key = strtolower( trim( $key ) );
				
				if( 'expires' == $key )
				{
					$val = strtotime( $val );
				}
				
				$this->$key = $val;
			}
		}
		else
		{
			if( ! isset( $data['name'] ) )
			{
				return false;
			}

			// Set properties based directly on parameters
			foreach ( array( 'name', 'value', 'path', 'domain', 'port' ) as $field )
			{
				if( isset( $data[ $field ] ) )
				{
					$this->$field = $data[$field];
				}	
			}

			if( isset( $data['expires'] ) )
			{
				$this->expires = is_int( $data['expires'] ) ? $data['expires'] : strtotime( $data['expires'] );
			}	
			else
			{
				$this->expires = null;
			}	
		}
	}

	function test( $url )
	{
		if( is_null( $this->name ) )
		{
			return false;
		}

		// Expires - if expired then nothing else matters
		if( isset( $this->expires ) and time() > $this->expires )
		{
			return false;
		}

		// Get details on the URL we're thinking about sending to
		$url = parse_url( $url );
		$url['port'] = isset( $url['port'] ) ? $url['port'] : ( 'https' == $url['scheme'] ? 443 : 80 );
		$url['path'] = isset( $url['path'] ) ? $url['path'] : '/';

		// Values to use for comparison against the URL
		$path   = isset( $this->path )   ? $this->path   : '/';
		$port   = isset( $this->port )   ? $this->port   : null;
		$domain = isset( $this->domain ) ? strtolower( $this->domain ) : strtolower( $url['host'] );
		
		if( false === stripos( $domain, '.' ) )
		{
			$domain .= '.local';
		}

		// Host - very basic check that the request URL ends with the domain restriction (minus leading dot)
		$domain = substr( $domain, 0, 1 ) == '.' ? substr( $domain, 1 ) : $domain;
		if( substr( $url['host'], - strlen( $domain ) ) != $domain )
		{
			return false;
		}
		
		// Port - supports "port-lists" in the format: "80,8000,8080"
		if( ! empty( $port ) and ! in_array( $url['port'], explode( ',', $port) ) )
		{
			return false;
		}

		// Path - request path must start with path restriction
		if( substr( $url['path'], 0, strlen( $path ) ) != $path )
		{
			return false;
		}

		return true;
	}

	function getHeaderValue()
	{
		if( ! isset( $this->name ) or ! isset( $this->value ) )
		{
			return '';
		}
		
		return $this->name . '=' . $this->value;
	}

	function getFullHeader()
	{
		return 'Cookie: ' . $this->getHeaderValue();
	}
}