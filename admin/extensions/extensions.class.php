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
		'site_charset' => 'utf-8',
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
		if( ! empty( $config['site_charset'] ) )
		{
			$this->site_config['site_charset'] = $config['site_charset'];
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

		if( $args['httpversion'] == '1.1' and ! isset( $args['headers']['connection'] ) )
		{
			$args['headers']['connection'] = 'close';
		}
		
		NV_Extensions::buildCookieHeader( $args );
		
		//mbstring_binary_safe_encoding();
		
		if( ! isset( $args['headers']['Accept-Encoding'] ) )
		{
			if( $encoding = NV_http_encoding::accept_encoding( $url, $args ) )
			{
				$args['headers']['Accept-Encoding'] = $encoding;
			}	
		}

		if( ( ! is_null( $args['body'] ) and '' != $args['body'] ) or $args['method'] == 'POST' or $args['method'] == 'PUT' )
		{
			if( is_array( $args['body'] ) or is_object( $args['body'] ) )
			{
				$args['body'] = http_build_query( $args['body'], null, '&' );

				if( ! isset( $args['headers']['Content-Type'] ) )
				{
					$args['headers']['Content-Type'] = 'application/x-www-form-urlencoded; charset=' . $this->site_config['site_charset'];
				}
			}

			if( $args['body'] === '' )
			{
				$args['body'] = null;
			}	

			if( ! isset( $args['headers']['Content-Length'] ) and ! isset( $args['headers']['content-length'] ) )
			{
				$args['headers']['Content-Length'] = strlen( $args['body'] );
			}
		}

		$response = $this->_dispatch_request( $url, $args );

		//reset_mbstring_encoding();

		if( $this->is_error( $response ) )
		{
			return $response;
		}

		// Append cookies that were used in this request to the response
		if( ! empty( $args['cookies'] ) )
		{
			$cookies_set = array();
			foreach( $response['cookies'] as $key => $value )
			{
				if( is_object( $value ) )
				{
					$cookies_set[$key] = $value->name;
				}
				else
				{
					$cookies_set[$key] = $value['name'];
				}
			}
			
			foreach( $args['cookies'] as $cookie )
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
			elseif( function_exists( 'apache_getenv' ) and apache_getenv( $k, true ) ) return apache_getenv( $k, true );
		}
		
		return '';
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
			case 4: $message = "There are no HTTP transports available which can complete the requested request."; break;
			case 5: $message = "Too many redirects."; break;
			default: $message = "There are some unknow errors had been occurred.";
		}
		
		$this->error['code'] = $code;
		$this->error['message'] = $message;
	}

	private function _dispatch_request( $url, $args )
	{
		static $transports = array();

		$class = $this->_get_first_available_transport( $args, $url );
		
		if( ! $class )
		{
			$this->set_error(4);
			return false;
		}

		// Transport claims to support request, instantiate it and give it a whirl.
		if( empty( $transports[$class] ) )
		{
			$transports[$class] = new $class;
		}

		$response = $transports[$class]->request( $url, $args );

		return $response;
	}
	
	static function handle_redirects( $url, $args, $response )
	{
		static $nv_http;
		
		// If no redirects are present, or, redirects were not requested, perform no action.
		if( ! isset( $response['headers']['location'] ) or $args['_redirection'] === 0 )
		{
			return false;
		}

		// Only perform redirections on redirection http codes
		if( $response['response']['code'] > 399 or $response['response']['code'] < 300 )
		{
			return false;
		}

		// Don't redirect if we've run out of redirects
		if( $args['redirection'] -- <= 0 )
		{
			$this->set_error(5);
			return false;
		}

		$redirect_location = $response['headers']['location'];

		// If there were multiple Location headers, use the last header specified
		if( is_array( $redirect_location ) )
		{
			$redirect_location = array_pop( $redirect_location );
		}

		$redirect_location = NV_Extensions::make_absolute_url( $redirect_location, $url );

		// POST requests should not POST to a redirected location
		if( $args['method'] == 'POST' )
		{
			if( in_array( $response['response']['code'], array( 302, 303 ) ) )
			{
				$args['method'] = 'GET';
			}
		}

		// Include valid cookies in the redirect process
		if( ! empty( $response['cookies'] ) )
		{
			foreach ( $response['cookies'] as $cookie )
			{
				if( $cookie->test( $redirect_location ) )
				{
					$args['cookies'][] = $cookie;
				}
			}
		}

		// Create object if null
		if( is_null( $nv_http ) )
		{
			$nv_http = new NV_Extensions();
		}
		
		return $nv_http->request( $redirect_location, $args );
	}
	
	static function make_absolute_url( $maybe_relative_path, $url )
	{
		if( empty( $url ) )
		{
			return $maybe_relative_path;
		}

		// Check for a scheme
		if( strpos( $maybe_relative_path, '://' ) !== false )
		{
			return $maybe_relative_path;
		}

		if( ! $url_parts = @parse_url( $url ) )
		{
			return $maybe_relative_path;
		}

		if( ! $relative_url_parts = @parse_url( $maybe_relative_path ) )
		{
			return $maybe_relative_path;
		}

		$absolute_path = $url_parts['scheme'] . '://' . $url_parts['host'];
		
		if( isset( $url_parts['port'] ) )
		{
			$absolute_path .= ':' . $url_parts['port'];
		}

		// Start off with the Absolute URL path
		$path = ! empty( $url_parts['path'] ) ? $url_parts['path'] : '/';

		// If it's a root-relative path, then great
		if( ! empty( $relative_url_parts['path'] ) and $relative_url_parts['path'][0] == '/' )
		{
			$path = $relative_url_parts['path'];
		}
		// Else it's a relative path
		elseif( ! empty( $relative_url_parts['path'] ) )
		{
			// Strip off any file components from the absolute path
			$path = substr( $path, 0, strrpos( $path, '/' ) + 1 );

			// Build the new path
			$path .= $relative_url_parts['path'];

			// Strip all /path/../ out of the path
			while( strpos( $path, '../' ) > 1 )
			{
				$path = preg_replace( '![^/]+/\.\./!', '', $path );
			}

			// Strip any final leading ../ from the path
			$path = preg_replace( '!^/(\.\./)+!', '', $path );
		}

		// Add the Query string
		if( ! empty( $relative_url_parts['query'] ) )
		{
			$path .= '?' . $relative_url_parts['query'];
		}

		return $absolute_path . '/' . ltrim( $path, '/' );
	}
	
	public function reset()
	{
		$this->error = array();
	}
	
	public function is_error( $resources )
	{
		if( is_object( $resources ) and isset( $resources->error ) and empty( $resources->error ) )
		{
			return false;
		}
		
		return true;
	}
	
	public function _get_first_available_transport( $args, $url = null )
	{
		$request_order = array( 'curl', 'streams' );

		// Loop over each transport on each HTTP request looking for one which will serve this request's needs
		foreach( $request_order as $transport )
		{
			$class = 'NV_http_' . $transport;

			// Check to see if this transport is a possibility, calls the transport statically
			if( ! call_user_func( array( $class, 'test' ), $args, $url ) )
			{
				continue;
			}

			return $class;
		}

		return false;
	}
	
	public static function build_args( $args, $defaults )
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
	
	public static function processResponse( $strResponse )
	{
		$res = explode( "\r\n\r\n", $strResponse, 2 );

		return array( 'headers' => $res[0], 'body' => isset( $res[1] ) ? $res[1] : '' );
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
			if( ! empty( $headers[$i] ) and strpos( $headers[$i], ':' ) === false )
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

			if( strpos( $tempheader, ':' ) === false )
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
	
	static function is_ip_address( $maybe_ip )
	{
		if( preg_match( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $maybe_ip ) )
		{
			return 4;
		}

		if( strpos( $maybe_ip, ':' ) !== false and preg_match( '/^(((?=.*(::))(?!.*\3.+\3))\3?|([\dA-F]{1,4}(\3|:\b|$)|\2))(?4){5}((?4){2}|(((2[0-4]|1\d|[1-9])?\d|25[0-5])\.?\b){4})$/i', trim( $maybe_ip, ' []' ) ) )
		{
			return 6;
		}

		return false;
	}
	
	function post( $url, $args = array() )
	{
		$defaults = array( 'method' => 'POST' );
		$args = $this->build_args( $args, $defaults );
		return $this->request( $url, $args );
	}

	function get( $url, $args = array() )
	{
		$defaults = array( 'method' => 'GET' );
		$args = $this->build_args( $args, $defaults );
		return $this->request( $url, $args );
	}

	function head( $url, $args = array() )
	{
		$defaults = array('method' => 'HEAD');
		$args = $this->build_args( $args, $defaults );
		return $this->request( $url, $args );
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
				
				if( $key == 'expires' )
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
		$url['port'] = isset( $url['port'] ) ? $url['port'] : ( $url['scheme'] == 'https' ? 443 : 80 );
		$url['path'] = isset( $url['path'] ) ? $url['path'] : '/';

		// Values to use for comparison against the URL
		$path   = isset( $this->path )   ? $this->path   : '/';
		$port   = isset( $this->port )   ? $this->port   : null;
		$domain = isset( $this->domain ) ? strtolower( $this->domain ) : strtolower( $url['host'] );
		
		if( stripos( $domain, '.' ) === false )
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

class NV_http_curl
{

	/**
	 * Temporary header storage for during requests.
	 * @access private
	 * @var string
	 */
	private $headers = '';

	/**
	 * Temporary body storage for during requests.
	 * @access private
	 * @var string
	 */
	private $body = '';

	/**
	 * The maximum amount of data to recieve from the remote server
	 * @access private
	 * @var int
	 */
	private $max_body_length = false;

	/**
	 * The file resource used for streaming to file.
	 * @access private
	 * @var resource
	 */
	private $stream_handle = false;
	
	/**
	 * The error code and error message.
	 * @access public
	 * @var array
	 */
	public $error = array();

	function request( $url, $args = array() )
	{
		$defaults = array(
			'method' => 'GET',
			'timeout' => 5,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => null,
			'cookies' => array()
		);

		$args = NV_Extensions::build_args( $args, $defaults );

		if( isset( $args['headers']['User-Agent'] ) )
		{
			$args['user-agent'] = $args['headers']['User-Agent'];
			unset($args['headers']['User-Agent']);
		}
		elseif( isset( $args['headers']['user-agent'] ) )
		{
			$args['user-agent'] = $args['headers']['user-agent'];
			unset( $args['headers']['user-agent'] );
		}

		// Construct Cookie: header if any cookies are set.
		NV_Extensions::buildCookieHeader( $args );

		$handle = curl_init();

		/*
		// No Proxy setting so proxy be omitted
		// cURL offers really easy proxy support.
		$proxy = new NV_http_proxy();

		if( $proxy->is_enabled() and $proxy->send_through_proxy( $url ) )
		{
			curl_setopt( $handle, CURLOPT_PROXYTYPE, CURLPROXY_HTTP );
			curl_setopt( $handle, CURLOPT_PROXY, $proxy->host() );
			curl_setopt( $handle, CURLOPT_PROXYPORT, $proxy->port() );

			if( $proxy->use_authentication() )
			{
				curl_setopt( $handle, CURLOPT_PROXYAUTH, CURLAUTH_ANY );
				curl_setopt( $handle, CURLOPT_PROXYUSERPWD, $proxy->authentication() );
			}
		}
		*/

		$is_local = isset( $args['local']) and $args['local'];
		$ssl_verify = isset( $args['sslverify'] ) and $args['sslverify'];

		// CURLOPT_TIMEOUT and CURLOPT_CONNECTTIMEOUT expect integers. Have to use ceil since
		// a value of 0 will allow an unlimited timeout.
		$timeout = ( int ) ceil( $args['timeout'] );
		curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, $timeout );
		curl_setopt( $handle, CURLOPT_TIMEOUT, $timeout );

		curl_setopt( $handle, CURLOPT_URL, $url );
		curl_setopt( $handle, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $handle, CURLOPT_SSL_VERIFYHOST, ( $ssl_verify === true ) ? 2 : false );
		curl_setopt( $handle, CURLOPT_SSL_VERIFYPEER, $ssl_verify );
		curl_setopt( $handle, CURLOPT_CAINFO, $args['sslcertificates'] );
		curl_setopt( $handle, CURLOPT_USERAGENT, $args['user-agent'] );
		
		// The option doesn't work with safe mode or when open_basedir is set, and there's a
		curl_setopt( $handle, CURLOPT_FOLLOWLOCATION, false );
		
		if( defined( 'CURLOPT_PROTOCOLS' ) )
		{
			// PHP 5.2.10 / cURL 7.19.4
			curl_setopt( $handle, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS );
		}

		switch( $args['method'] )
		{
			case 'HEAD':
				curl_setopt( $handle, CURLOPT_NOBODY, true );
				break;
			case 'POST':
				curl_setopt( $handle, CURLOPT_POST, true );
				curl_setopt( $handle, CURLOPT_POSTFIELDS, $args['body'] );
				break;
			case 'PUT':
				curl_setopt( $handle, CURLOPT_CUSTOMREQUEST, 'PUT' );
				curl_setopt( $handle, CURLOPT_POSTFIELDS, $args['body'] );
				break;
			default:
				curl_setopt( $handle, CURLOPT_CUSTOMREQUEST, $args['method'] );
				
				if( ! is_null( $args['body'] ) )
				{
					curl_setopt( $handle, CURLOPT_POSTFIELDS, $args['body'] );
				}
					
				break;
		}

		if( $args['blocking'] === true )
		{
			curl_setopt( $handle, CURLOPT_HEADERFUNCTION, array( $this, 'stream_headers' ) );
			curl_setopt( $handle, CURLOPT_WRITEFUNCTION, array( $this, 'stream_body' ) );
		}

		curl_setopt( $handle, CURLOPT_HEADER, false );

		if( isset( $args['limit_response_size'] ) )
		{
			$this->max_body_length = intval( $args['limit_response_size'] );
		}
		else
		{
			$this->max_body_length = false;		
		}				

		// If streaming to a file open a file handle, and setup our curl streaming handler
		if( $args['stream'] )
		{
			$this->stream_handle = @fopen( $args['filename'], 'w+' );
				
			if( ! $this->stream_handle )
			{
				$this->set_error(1);
				return $this;
			}
		}
		else
		{
			$this->stream_handle = false;
		}

		if( ! empty( $args['headers'] ) )
		{
			// cURL expects full header strings in each element
			$headers = array();
			foreach( $args['headers'] as $name => $value )
			{
				$headers[] = "{$name}: $value";
			}
			
			curl_setopt( $handle, CURLOPT_HTTPHEADER, $headers );
		}

		if( $args['httpversion'] == '1.0' )
		{
			curl_setopt( $handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0 );
		}
		else
		{
			curl_setopt( $handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
		}

		// We don't need to return the body, so don't. Just execute request and return.
		if( ! $args['blocking'] )
		{
			curl_exec( $handle );

			if( $curl_error = curl_error( $handle ) )
			{
				curl_close( $handle );
				
				$this->set_error(2);
				return $this;
			}
			
			if( in_array( curl_getinfo( $handle, CURLINFO_HTTP_CODE ), array( 301, 302 ) ) )
			{
				curl_close( $handle );
				
				$this->set_error(3);
				return $this;
			}

			curl_close( $handle );
			return array( 'headers' => array(), 'body' => '', 'response' => array( 'code' => false, 'message' => false ), 'cookies' => array() );
		}

		$theResponse = curl_exec( $handle );
		$theHeaders = NV_Extensions::processHeaders( $this->headers, $url );
		$theBody = $this->body;

		$this->headers = '';
		$this->body = '';

		$curl_error = curl_errno( $handle );

		// If an error occured, or, no response
		if( $curl_error or ( strlen( $theBody ) == 0 and empty( $theHeaders['headers'] ) ) )
		{
			if( CURLE_WRITE_ERROR /* 23 */ == $curl_error and $args['stream'] )
			{
				fclose( $this->stream_handle );
				
				$this->set_error(4);
				return $this;
			}
			
			if( $curl_error = curl_error( $handle ) )
			{
				curl_close( $handle );
				
				$this->set_error(2);
				return $this;
			}
			
			if( in_array( curl_getinfo( $handle, CURLINFO_HTTP_CODE ), array( 301, 302 ) ) )
			{
				curl_close( $handle );
				
				$this->set_error(3);
				return $this;
			}
		}

		$response = array();
		$response['code'] = curl_getinfo( $handle, CURLINFO_HTTP_CODE );
		$response['message'] = $response['code'];

		curl_close( $handle );

		if( $args['stream'] )
		{
			fclose( $this->stream_handle );
		}

		$response = array(
			'headers' => $theHeaders['headers'],
			'body' => null,
			'response' => $response,
			'cookies' => $theHeaders['cookies'],
			'filename' => $args['filename']
		);

		// Handle redirects
		if( ( $redirect_response = NV_Extensions::handle_redirects( $url, $args, $response ) ) !== false )
		{
			return $redirect_response;
		}

		if( $args['decompress'] === true and NV_http_encoding::should_decode( $theHeaders['headers'] ) === true )
		{
			$theBody = NV_http_encoding::decompress( $theBody );
		}

		$response['body'] = $theBody;

		return $response;
	}

	private function stream_headers( $handle, $headers )
	{
		$this->headers .= $headers;
		return strlen( $headers );
	}

	private function stream_body( $handle, $data )
	{
		$data_length = strlen( $data );

		if( $this->max_body_length and ( strlen( $this->body ) + $data_length ) > $this->max_body_length )
		{
			$data = substr( $data, 0, ( $this->max_body_length - $data_length ) );
		}

		if( $this->stream_handle )
		{
			$bytes_written = fwrite( $this->stream_handle, $data );
		}
		else
		{
			$this->body .= $data;
			$bytes_written = $data_length;
		}

		return $bytes_written;
	}

	private function set_error( $code )
	{
		$code = intval( $code );
		$message = "";
		
		switch( $code )
		{
			case 1: $message = "Could not open handle for fopen() to streamfile."; break;
			case 2: $message = "HTTP Curl request failed."; break;
			case 3: $message = "Too many redirects."; break;
			case 4: $message = "Failed to write request to temporary file."; break;
			default: $message = "There are some unknow errors had been occurred.";
		}
		
		$this->error['code'] = $code;
		$this->error['message'] = $message;
	}

	public static function test( $args = array() )
	{
		if( ! function_exists( 'curl_init' ) or ! function_exists( 'curl_exec' ) )
		{
			return false;
		}

		$is_ssl = isset( $args['ssl'] ) and $args['ssl'];

		if( $is_ssl )
		{
			$curl_version = curl_version();
			if( ! ( CURL_VERSION_SSL & $curl_version['features'] ) )
			{
				// Does this cURL version support SSL requests?
				return false;
			}
		}

		return true;
	}
}

class NV_http_encoding
{

	public static function compress( $raw, $level = 9, $supports = null )
	{
		return gzdeflate( $raw, $level );
	}

	public static function decompress( $compressed, $length = null )
	{
		if( empty( $compressed ) )
		{
			return $compressed;
		}

		if( ( $decompressed = @gzinflate( $compressed ) ) !== false )
		{
			return $decompressed;
		}

		if( ( $decompressed = NV_http_encoding::compatible_gzinflate( $compressed ) ) !== false )
		{
			return $decompressed;
		}

		if( ( $decompressed = @gzuncompress( $compressed ) ) !== false )
		{
			return $decompressed;
		}

		if( function_exists('gzdecode') )
		{
			$decompressed = @gzdecode( $compressed );

			if( $decompressed !== false )
			{
				return $decompressed;
			}
		}

		return $compressed;
	}

	public static function compatible_gzinflate( $gzData )
	{
		// Compressed data might contain a full header, if so strip it for gzinflate()
		if( substr( $gzData, 0, 3 ) == "\x1f\x8b\x08" )
		{
			$i = 10;
			$flg = ord( substr( $gzData, 3, 1 ) );
			if( $flg > 0 )
			{
				if( $flg & 4 )
				{
					list( $xlen ) = unpack( 'v', substr( $gzData, $i, 2 ) );
					$i = $i + 2 + $xlen;
				}
				
				if( $flg & 8 )
				{
					$i = strpos( $gzData, "\0", $i ) + 1;
				}
				
				if( $flg & 16 )
				{
					$i = strpos( $gzData, "\0", $i ) + 1;
				}
					
				if( $flg & 2 )
				{
					$i = $i + 2;
				}
			}
			
			$decompressed = @gzinflate( substr( $gzData, $i, -8 ) );
			
			if( $decompressed !== false )
			{
				return $decompressed;
			}
		}

		// Compressed data from java.util.zip.Deflater amongst others.
		$decompressed = @gzinflate( substr( $gzData, 2 ) );
		
		if( $decompressed !== false )
		{
			return $decompressed;
		}

		return false;
	}

	public static function accept_encoding( $url, $args )
	{
		$type = array();
		$compression_enabled = NV_http_encoding::is_available();

		if( ! $args['decompress'] )
		{
			// decompression specifically disabled
			$compression_enabled = false;
		}
		elseif( $args['stream'] )
		{
			// disable when streaming to file
			$compression_enabled = false;
		}	
		elseif( isset( $args['limit_response_size'] ) )
		{
			// If only partial content is being requested, we won't be able to decompress it
			$compression_enabled = false;
		}

		if( $compression_enabled )
		{
			if( function_exists( 'gzinflate' ) )
			{
				$type[] = 'deflate;q=1.0';
			}

			if( function_exists( 'gzuncompress' ) )
			{
				$type[] = 'compress;q=0.5';
			}

			if( function_exists( 'gzdecode' ) )
			{
				$type[] = 'gzip;q=0.5';
			}
		}

		return implode( ', ', $type );
	}

	public static function content_encoding()
	{
		return 'deflate';
	}

	public static function should_decode( $headers )
	{
		if( is_array( $headers ) )
		{
			if( array_key_exists('content-encoding', $headers) and ! empty( $headers['content-encoding'] ) )
			{
				return true;
			}
		}
		elseif( is_string( $headers ) )
		{
			return ( stripos( $headers, 'content-encoding:' ) !== false );
		}

		return false;
	}

	public static function is_available()
	{
		return ( function_exists('gzuncompress') or function_exists('gzdeflate') or function_exists('gzinflate') );
	}
}