<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 4/10/2010 19:43
 */

if( ! defined( 'E_STRICT' ) ) define( 'E_STRICT', 2048 ); //khong sua
if( ! defined( 'E_RECOVERABLE_ERROR' ) ) define( 'E_RECOVERABLE_ERROR', 4096 ); //khong sua
if( ! defined( 'E_DEPRECATED' ) ) define( 'E_DEPRECATED', 8192 ); //khong sua
if( ! defined( 'E_USER_DEPRECATED' ) ) define( 'E_USER_DEPRECATED', 16384 ); //khong sua
if( ! defined( 'NV_CURRENTTIME' ) ) define( 'NV_CURRENTTIME', time() );
if( ! defined( 'NV_ROOTDIR' ) ) define( 'NV_ROOTDIR', preg_replace( "/[\/]+$/", '', str_replace( '\\', '/', realpath( dirname( __file__ ) . '/../../' ) ) ) );

class Error
{
	const INCORRECT_IP = 'Incorrect IP address specified';
	const LOG_FILE_NAME_DEFAULT = 'error_log'; //ten file log
	const LOG_FILE_EXT_DEFAULT = 'log'; //duoi file log
	private $log_errors_list;
	private $display_errors_list;
	private $send_errors_list;
	private $error_send_mail;
	private $error_log_path;
	private $error_log_tmp = false;
	private $error_log_filename;
	private $error_log_fileext;
	private $error_log_256;
	private $errno = false;
	private $errstr = false;
	private $errfile = false;
	private $errline = false;
	private $ip = false;
	private $useragent = false;
	private $request = false;
	private $day;
	private $month;
	private $error_date;
	private $errortype = array(
		E_ERROR => 'Error',
		E_WARNING => 'Warning',
		E_PARSE => 'Parsing Error',
		E_NOTICE => 'Notice',
		E_CORE_ERROR => 'Core Error',
		E_CORE_WARNING => 'Core Warning',
		E_COMPILE_ERROR => 'Compile Error',
		E_COMPILE_WARNING => 'Compile Warning',
		E_USER_ERROR => 'User Error',
		E_USER_WARNING => 'User Warning',
		E_USER_NOTICE => 'User Notice',
		E_STRICT => 'Runtime Notice',
		E_RECOVERABLE_ERROR => 'Catchable fatal error',
		E_DEPRECATED => 'Run-time notices',
		E_USER_DEPRECATED => 'User-generated warning message'
	);

	/**
	 * Error::__construct()
	 *
	 * @param mixed $config
	 * @return
	 */
	public function __construct( $config )
	{
		$this->log_errors_list = $this->parse_error_num( ( int )$config['log_errors_list'] );
		$this->display_errors_list = $this->parse_error_num( ( int )$config['display_errors_list'] );
		$this->send_errors_list = $this->parse_error_num( ( int )$config['send_errors_list'] );
		$this->error_log_path = $this->get_error_log_path( ( string )$config['error_log_path'] );
		$this->error_send_mail = ( string )$config['error_send_email'];

		if( isset( $config['error_log_filename'] ) and preg_match( '/[a-z0-9\_]+/i', $config['error_log_filename'] ) )
		{
			$this->error_log_filename = $config['error_log_filename'];
		}
		else
		{
			$this->error_log_filename = Error::LOG_FILE_NAME_DEFAULT;
		}
		if( isset( $config['error_log_fileext'] ) and preg_match( '/[a-z]+/i', $config['error_log_fileext'] ) )
		{
			$this->error_log_fileext = $config['error_log_fileext'];
		}
		else
		{
			$this->error_log_fileext = Error::LOG_FILE_EXT_DEFAULT;
		}

		$this->day = date( 'd-m-Y', NV_CURRENTTIME );
		$this->error_date = date( 'r', NV_CURRENTTIME );
		$this->month = date( 'm-Y', NV_CURRENTTIME );

		$ip = $this->get_Env( 'REMOTE_ADDR' );
		if( preg_match( '#^(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$#', $ip ) )
		{
			$ip2long = ip2long( $ip );
		}
		else
		{
			if( substr_count( $ip, '::' ) )
			{
				$ip = str_replace( '::', str_repeat( ':0000', 8 - substr_count( $ip, ':' ) ) . ':', $ip );
			}
			$ip = explode( ':', $ip );
			$r_ip = '';
			foreach( $ip as $v )
			{
				$r_ip .= str_pad( base_convert( $v, 16, 2 ), 16, 0, STR_PAD_LEFT );
			}
			$ip2long = base_convert( $r_ip, 2, 10 );
		}

		if( $ip2long === - 1 and $ip2long === false ) die( Error::INCORRECT_IP );
		$this->ip = $ip;
		$request = $this->get_request();
		if( ! empty( $request ) ) $this->request = substr( $request, 500 );

		$useragent = $this->get_Env( 'HTTP_USER_AGENT' );
		if( ! empty( $useragent ) ) $this->useragent = substr( $useragent, 0, 500 );

		$this->nv_set_ini();
	}

	/**
	 * Error::nv_set_ini()
	 *
	 * @return
	 */
	public function nv_set_ini()
	{
		$disable_functions = ( ini_get( 'disable_functions' ) != '' and ini_get( 'disable_functions' ) != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", ini_get( 'disable_functions' ) ) ) : array();
		if( extension_loaded( 'suhosin' ) )
		{
			$disable_functions = array_merge( $disable_functions, array_map( 'trim', preg_split( "/[\s,]+/", ini_get( 'suhosin.executor.func.blacklist' ) ) ) );
		}
		if( ( function_exists( 'ini_set' ) and ! in_array( 'ini_set', $disable_functions ) ) )
		{
			ini_set( 'display_startup_errors', 0 );
			ini_set( 'track_errors', 1 );

			ini_set( 'log_errors', 0 );
			ini_set( 'display_errors', 0 );
		}
	}

	/**
	 * Error::get_Env()
	 *
	 * @param mixed $key
	 * @return
	 */
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

	/**
	 * Error::get_error_log_path()
	 *
	 * @param mixed $path
	 * @return
	 */
	private function get_error_log_path( $path )
	{
		$path = ltrim( rtrim( preg_replace( array( "/\\\\/", "/\/{2,}/" ), "/", $path ), '/' ), '/' );
		if( is_dir( NV_ROOTDIR . '/' . $path ) )
		{
			$log_path = NV_ROOTDIR . '/' . $path;
		}
		else
		{
			$log_path = NV_ROOTDIR;
			$e = explode( '/', $path );
			$cp = '';
			foreach( $e as $p )
			{
				if( preg_match( '#[^a-zA-Z0-9\_]#', $p ) )
				{
					$cp = '';
					break;
				}
				if( ! is_dir( NV_ROOTDIR . '/' . $cp . $p ) )
				{
					if( ! @mkdir( NV_ROOTDIR . '/' . $cp . $p, 0777 ) )
					{
						$cp = '';
						break;
					}
				}
				$cp .= $p . '/';
			}
			$log_path .= '/' . $path;
			@mkdir( $log_path . '/tmp' );
			@mkdir( $log_path . '/errors256' );
			@mkdir( $log_path . '/old' );
		}
		if( is_dir( $log_path . '/tmp' ) )
		{
			$this->error_log_tmp = $log_path . '/tmp';
		}
		if( is_dir( $log_path . '/errors256' ) )
		{
			$this->error_log_256 = $log_path . '/errors256';
		}
		return $log_path;
	}

	/**
	 * Error::parse_error_num()
	 *
	 * @param mixed $num
	 * @return
	 */
	private function parse_error_num( $num )
	{
		if( $num > E_ALL + E_STRICT ) $num = E_ALL + E_STRICT;
		if( $num < 0 ) $num = 0;
		$result = array();
		$n = 1;
		while( $num > 0 )
		{
			if( $num & 1 == 1 )
			{
				$result[$n] = $this->errortype[$n];
			}
			$n *= 2;
			$num >>= 1;
		}

		return $result;
	}

	function get_request()
	{
		$request = array();
		if( sizeof( $_GET ) )
		{
			foreach( $_GET as $key => $value )
			{
				if( preg_match( '/^[a-zA-Z0-9\_]+$/', $key ) and ! is_numeric( $key ) )
				{
					$value = $this->fixQuery( $key, $value );
					if( $value !== false ) $request[$key] = $value;
				}
			}
		}

		$request = ! empty( $request ) ? '?' . http_build_query( $request ) : '';
		$request = $this->get_Env( 'PHP_SELF' ) . $request;

		return $request;
	}

	private function fixQuery( $key, $value )
	{
		if( preg_match( '/^[a-zA-Z0-9\_]+$/', $key ) )
		{
			if( is_array( $value ) )
			{
				foreach( $value as $k => $v )
				{
					$_value = $this->fixQuery( $k, $v );
					if( $_value !== false ) $value[$k] = $_value;
				}
				return $value;
			}

			$value = strip_tags( stripslashes( $value ) );
			$value = preg_replace( "/[\'|\"|\t|\r|\n|\.\.\/]+/", "", $value );
			$value = str_replace( array( "'", '"', "&" ), array( '&rsquo;', '&quot;', '&amp;' ), $value );
			return $value;
		}

		return false;
	}

	private function info_die()
	{
		$error_code = md5( $this->errno . ( string )$this->errfile . ( string )$this->errline . $this->ip );
		$error_code2 = md5( $error_code );
		$error_file = $this->error_log_256 . '/' . $this->month . '__' . $error_code2 . '__' . $error_code . '.' . $this->error_log_fileext;

		if( ! file_exists( $error_file ) )
		{
			$content = "TIME: " . $this->error_date . "\r\n";
			if( ! empty( $this->ip ) ) $content .= "IP: " . $this->ip . "\r\n";
			$content .= "INFO: " . $this->errortype[$this->errno] . "(" . $this->errno . "): " . $this->errstr . "\r\n";
			if( ! empty( $this->errfile ) ) $content .= "FILE: " . $this->errfile . "\r\n";
			if( ! empty( $this->errline ) ) $content .= "LINE: " . $this->errline . "\r\n";
			if( ! empty( $this->request ) ) $content .= "REQUEST: " . $this->request . "\r\n";
			if( ! empty( $this->useragent ) ) $content .= "USER-AGENT: " . $this->useragent . "\r\n";

			file_put_contents( $error_file, $content, FILE_APPEND );
		}

		$strEncodedEmail = '';
		$strlen = strlen( $this->error_send_mail );
		for( $i = 0; $i < $strlen; ++$i )
		{
			$strEncodedEmail .= "&#" . ord( substr( $this->error_send_mail, $i ) ) . ";";
		}

		$size = @getimagesize( NV_ROOTDIR . '/images/' . $this->site_logo );
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\n";
		echo "\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
		echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
		echo "<head>\n";
		echo "	<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\n";
		echo "	<meta http-equiv=\"expires\" content=\"0\" />\n";
		echo "<title>" . $this->errortype[$this->errno] . "</title>\n";
		echo "</head>\n\n";
		echo "<body>\n";
		echo "	<div style=\"width: 400px; margin-right: auto; margin-left: auto; margin-top: 20px; margin-bottom: 20px; color: #dd3e31; text-align: center;\"><span style=\"font-weight: bold;\">" . $this->errortype[$this->errno] . "</span><br />\n";
		echo "	<span style=\"color: #1a264e;font-weight: bold;\">" . $this->errstr . "</span><br />\n";
		echo "	<span style=\"color: #1a264e;\">(Code: " . $error_code2 . ")</span></div>\n";
		echo "	<div style=\"width: 400px; margin-right: auto; margin-left: auto;text-align:center\">\n";
		echo "	If you have any questions about this site,<br />please <a href=\"mailto:" . $strEncodedEmail . "\">contact</a> the site administrator for more information</div>\n";
		echo "</body>\n";
		echo "</html>";
		die();
	}

	private function _log()
	{
		$content = '[' . $this->error_date . ']';
		if( ! empty( $this->ip ) ) $content .= ' [' . $this->ip . ']';
		$content .= ' [' . $this->errortype[$this->errno] . '(' . $this->errno . '): ' . $this->errstr . ']';
		if( ! empty( $this->errfile ) ) $content .= ' [FILE: ' . $this->errfile . ']';
		if( ! empty( $this->errline ) ) $content .= ' [LINE: ' . $this->errline . ']';
		if( ! empty( $this->request ) ) $content .= ' [REQUEST: ' . $this->request . ']';
		$content .= "\r\n";
		$error_log_file = $this->error_log_path . '/' . $this->day . '_' . $this->error_log_filename . '.' . $this->error_log_fileext;
		error_log( $content, 3, $error_log_file );
	}

	private function _send()
	{
		$content = '[' . $this->error_date . ']';
		if( ! empty( $this->ip ) ) $content .= ' [' . $this->ip . ']';
		$content .= ' [' . $this->errortype[$this->errno] . '(' . $this->errno . '): ' . $this->errstr . ']';
		if( ! empty( $this->errfile ) ) $content .= ' [FILE: ' . $this->errfile . ']';
		if( ! empty( $this->errline ) ) $content .= ' [LINE: ' . $this->errline . ']';
		if( ! empty( $this->request ) ) $content .= ' [REQUEST: ' . $this->request . ']';
		if( ! empty( $this->useragent ) ) $content .= ' [AGENT: ' . $this->useragent . ']';
		$content .= "\r\n";
		$error_log_file = $this->error_log_path . '/sendmail.' . $this->error_log_fileext;
		error_log( $content, 3, $error_log_file );
	}

	private function _display()
	{
		global $error_info;

		$info = $this->errstr;
		if( $this->errno != E_USER_ERROR and $this->errno != E_USER_WARNING and $this->errno != E_USER_NOTICE )
		{
			if( ! empty( $this->errfile ) ) $info .= ' in file ' . $this->errfile;
			if( ! empty( $this->errline ) ) $info .= ' on line ' . $this->errline;
		}

		$error_info[] = array( 'errno' => $this->errno, 'info' => $info );
	}

	/**
	 * Error::error_handler()
	 *
	 * @param mixed $errno
	 * @param mixed $errstr
	 * @param mixed $errfile
	 * @param mixed $errline
	 * @return
	 */
	public function error_handler( $errno, $errstr, $errfile, $errline )
	{
		if( empty( $errno ) ) return;
		if( ! empty( $errno ) ) $this->errno = $errno;
		if( isset( $errstr ) and ! empty( $errstr ) ) $this->errstr = $errstr;
		if( isset( $errfile ) and ! empty( $errfile ) ) $this->errfile = str_replace( NV_ROOTDIR, '', str_replace( '\\', '/', $errfile ) );
		if( isset( $errline ) and ! empty( $errline ) ) $this->errline = $errline;

		$track_errors = $this->day . '_' . md5( $this->errno . ( string )$this->errfile . ( string )$this->errline . $this->ip );
		$track_errors = $this->error_log_tmp . '/' . $track_errors . '.' . $this->error_log_fileext;

		if( ! file_exists( $track_errors ) )
		{
			file_put_contents( $track_errors, '', FILE_APPEND );
			if( ! empty( $this->log_errors_list ) and isset( $this->log_errors_list[$errno] ) )
			{
				$this->_log();
			}

			if( ! empty( $this->send_errors_list ) and isset( $this->send_errors_list[$errno] ) )
			{
				$this->_send();
			}

			if( ! empty( $this->display_errors_list ) and isset( $this->display_errors_list[$errno] ) and ! preg_match( "/^ftp\_login\(\)/i", $errstr ) )
			{
				$this->_display();
			}
		}

		if( $this->errno == 256 )
		{
			$this->info_die();
		}
	}
}