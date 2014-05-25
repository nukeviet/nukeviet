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
		if ( $args['stream'] and empty( $args['filename'] ) )
		{
			$args['filename'] = $this->tmp_dir . '/' . basename( $url );
		}
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
			default: $message = "There are some unknow errors had been occurred.";
		}
		
		$this->error['code'] = $code;
		$this->error['message'] = $message;
	}
	
	public function reset()
	{
		$this->error = array();
	}
}