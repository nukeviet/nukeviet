<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 23/11/2010, 20:46
 */

class CJzip
{
	private $is_gzip = false;
	private $getName = 'file';
	private $file = array();
	private $maxAge = 2592000;
	private $encoding = 'none';
	private $currenttime;
	private $siteRoot;
	private $base_siteurl;
	private $isOptimized = false;
	private $root = false;
	private $cssImgNewPath = '';

	/**
	 * CJzip::__construct()
	 *
	 * @return
	 */
	public function __construct()
	{
		if( ! isset( $_GET[$this->getName] ) )
		{
			$this->browseInfo( 404 );
		}

		if( extension_loaded( 'zlib' ) and ini_get( 'output_handler' ) == '' )
		{
			if( strtolower( ini_get( 'zlib.output_compression' ) ) == 'on' or ini_get( 'zlib.output_compression' ) == 1 )
			{
				$disable_functions = ( ini_get( 'disable_functions' ) != '' and ini_get( 'disable_functions' ) != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", ini_get( 'disable_functions' ) ) ) : array();
				if( extension_loaded( 'suhosin' ) )
				{
					$disable_functions = array_merge( $disable_functions, array_map( 'trim', preg_split( "/[\s,]+/", ini_get( 'suhosin.executor.func.blacklist' ) ) ) );
				}

				$ini_set_support = ( function_exists( 'ini_set' ) and ! in_array( 'ini_set', $disable_functions ) ) ? true : false;
				if( $ini_set_support )
				{
					ini_set( 'zlib.output_compression_level', 6 );
				}
			}
			else
			{
				$this->is_gzip = true;
			}
		}

		$this->siteRoot = str_replace( '\\', '/', realpath( dirname( __file__ ) ) );
		$base_siteurl = pathinfo( $_SERVER['PHP_SELF'], PATHINFO_DIRNAME );
		if( $base_siteurl == '\\' or $base_siteurl == '/' ) $base_siteurl = '';
		if( ! empty( $base_siteurl ) ) $base_siteurl = str_replace( '\\', '/', $base_siteurl );
		if( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( '/[\/]+$/', '', $base_siteurl );
		if( ! empty( $base_siteurl ) )
		{
			$base_siteurl = preg_replace( '/^[\/]*(.*)$/', '/\\1', $base_siteurl );
			$base_siteurl = preg_replace( '#/index\.php(.*)$#', '', $base_siteurl );
		}
		$this->base_siteurl = $base_siteurl . '/';

		$filename = $_GET[$this->getName];
		if( preg_match( '/^\//', $filename ) ) $filename = preg_replace( '#^' . $this->base_siteurl . '#', '', $filename );

		$this->file['path'] = $this->siteRoot . '/' . $filename;
		$this->file['lastmod'] = @filemtime( $this->file['path'] );
		if( ! $this->file['lastmod'] )
		{
			$this->browseInfo( 404 );
		}

		unset( $matches );
		preg_match( '/(.*?)\.(css|js)$/', $this->file['path'], $matches );
		if( ! $matches )
		{
			$this->browseInfo( 403 );
		}

		$this->file['ext'] = $matches[2];
		$this->file['contenttype'] = ( $this->file['ext'] == 'css' ) ? 'css' : 'javascript';
		if( preg_match( '/\.opt$/', $matches[1] ) )
		{
			$this->isOptimized = true;
		}

		$this->currenttime = time();
		if( isset( $_GET['r'] ) )
		{
			$this->root = true;
			$this->file['md5file'] = md5( $this->file['path'] . '_root' );
		}
		else
		{
			$this->file['md5file'] = md5( $this->file['path'] );
		}

		$this->cssImgNewPath = str_replace( '\\', '/', dirname( $filename ) ) . '/';
	}

	/**
	 * CJzip::browseInfo()
	 *
	 * @param mixed $num
	 * @return
	 */
	public function browseInfo( $num )
	{
		switch( $num )
		{
			case 304:
				$info = 'HTTP/1.1 304 Not Modified';
				break;

			case 403:
				$info = 'HTTP/1.1 403 Forbidden';
				break;

			default:
				$info = 'HTTP/1.1 404 Not Found';
		}
		header( $info );
		header( 'Content-Length: 0' );
		exit();
	}

	/**
	 * CJzip::is_notModified()
	 *
	 * @param mixed $hash
	 * @return
	 */
	private function is_notModified( $hash )
	{
		return ( isset( $_SERVER['HTTP_IF_NONE_MATCH'] ) && stripslashes( $_SERVER['HTTP_IF_NONE_MATCH'] ) == '"' . $hash . '"' );
	}

	/**
	 * CJzip::check_encode()
	 *
	 * @return
	 */
	private function check_encode()
	{
		if( ! $this->is_gzip or ! function_exists( 'gzencode' ) or ! isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) ) return 'none';

		$encoding = strstr( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip' ) ? 'gzip' : ( strstr( $_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate' ) ? 'deflate' : 'none' );

		if( $encoding != 'none' )
		{
			if( ! strstr( $_SERVER['HTTP_USER_AGENT'], 'Opera' ) && preg_match( '/^Mozilla\/4\.0 \(compatible; MSIE ([0-9]\.[0-9])/i', $_SERVER['HTTP_USER_AGENT'], $matches ) )
			{
				$version = floatval( $matches[1] );
				if( $version < 6 || ( $version == 6 && ! strstr( $_SERVER['HTTP_USER_AGENT'], 'EV1' ) ) ) $encoding = 'none';
			}
		}

		return $encoding;
	}

	/**
	 * CJzip::loadData()
	 *
	 * @return
	 */
	private function loadData()
	{
		$disable_functions = ( ( $disable_functions = ini_get( 'disable_functions' ) ) != '' and $disable_functions != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", $disable_functions ) ) : array();
		if( extension_loaded( 'suhosin' ) )
		{
			$disable_functions = array_merge( $disable_functions, array_map( 'trim', preg_split( "/[\s,]+/", ini_get( 'suhosin.executor.func.blacklist' ) ) ) );
		}
		if( function_exists( 'ini_set' ) and ! in_array( 'ini_set', $disable_functions ) )
		{
			if( ( integer )ini_get( 'memory_limit' ) < 64 )
			{
				ini_set( 'memory_limit', '64M' );
			}
		}
		$data = file_get_contents( $this->file['path'] );

		if( ! $this->isOptimized )
		{
			$data = ( $this->file['contenttype'] == 'css' ) ? $this->compress_css( $data ) : $this->compress_javascript( $data );
		}

		if( $this->file['contenttype'] == 'css' and $this->root == true )
		{
			$data = preg_replace_callback( "/url[\s]*\([\s]*[\'\"]*([^\'\"\)]+)[\'\"]*[\s]*\)/", array( $this, 'changeCssURL' ), $data );
		}

		if( $this->encoding != 'none' )
		{
			$data = gzencode( $data, 6, $this->encoding == 'gzip' ? FORCE_GZIP : FORCE_DEFLATE );
			header( 'Content-Encoding: ' . $this->encoding );
			header( 'Vary: Accept-Encoding' );
		}

		header( 'Content-Type: text/' . $this->file['contenttype'] . '; charset=utf-8' );
		header( 'Cache-Control: public; max-age=' . $this->maxAge );
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $this->file['lastmod'] ) . ' GMT' );
		header( 'expires: ' . gmdate( 'D, d M Y H:i:s', $this->currenttime + $this->maxAge ) . ' GMT' );

		echo $data;
		exit();
	}

	/**
	 * CJzip::commentCB()
	 *
	 * @param mixed $m
	 * @return
	 */
	private function commentCB( $m )
	{
		$hasSurroundingWs = ( trim( $m[0] ) !== $m[1] );
		$m = $m[1];
		if( $m === 'keep' )
		{
			return '/**/';
		}
		if( $m === '" "' )
		{
			return '/*" "*/';
		}
		if( preg_match( '@";\\}\\s*\\}/\\*\\s+@', $m ) )
		{
			return '/*";}}/* */';
		}
		if( preg_match( '@^/\\s*(\\S[\\s\\S]+?)\\s*/\\*@x', $m, $n ) )
		{
			return "/*/" . $n[1] . "/**/";
		}
		if( substr( $m, -1 ) === '\\' )
		{
			return '/*\\*/';
		}
		if( $m !== '' && $m[0] === '/' )
		{
			return '/*/*/';
		}
		return $hasSurroundingWs ? ' ' : '';
	}

	/**
	 * CJzip::selectorsCB()
	 *
	 * @param mixed $m
	 * @return
	 */
	private function selectorsCB( $m )
	{
		return preg_replace( '/\\s*([,>+~])\\s*/', '$1', $m[0] );
	}

	/**
	 * CJzip::fontFamilyCB()
	 *
	 * @param mixed $m
	 * @return
	 */
	private function fontFamilyCB( $m )
	{
		$m[1] = preg_replace( '/\\s*("[^"]+"|\'[^\']+\'|[\\w\\-]+)\\s*/x', '$1', $m[1] );
		return 'font-family:' . $m[1] . $m[2];
	}

	/**
	 * CJzip::changeCssURL()
	 *
	 * @param mixed $matches
	 * @return
	 */
	private function changeCssURL( $matches )
	{
		if( preg_match( '/^(http(s?)|ftp\:\/\/)/', $matches[1] ) )
		{
			$url = $matches[1];
		}
		else
		{
			$url = $this->cssImgNewPath . $matches[1];
			while( preg_match( '/([^\/(\.\.)]+)\/\.\.\//', $url ) )
			{
				$url = preg_replace( '/([^\/(\.\.)]+)\/\.\.\//', '', $url );
			}
		}
		return 'url(' . $url . ')';
	}

	/**
	 * CJzip::compress_css()
	 *
	 * @param mixed $cssContent
	 * @return
	 */
	private function compress_css( $cssContent )
	{
		// $cssContent = preg_replace( "/url[\s]*\([\s]*[\'|\"](.*)?[\'|\"][\s]*\)/", "url($1)", $cssContent );

		$cssContent = preg_replace( '@>/\\*\\s*\\*/@', '>/*keep*/', $cssContent );
		$cssContent = preg_replace( '@/\\*\\s*\\*/\\s*:@', '/*keep*/:', $cssContent );
		$cssContent = preg_replace( '@:\\s*/\\*\\s*\\*/@', ':/*keep*/', $cssContent );
		$cssContent = preg_replace_callback( '@\\s*/\\*([\\s\\S]*?)\\*/\\s*@', array( $this, 'commentCB' ), $cssContent );

		$cssContent = preg_replace( '/[\s\t\r\n]+/', ' ', $cssContent );
		$cssContent = preg_replace( '/[\s]*(\:|\,|\;|\{|\})[\s]*/', "$1", $cssContent );
		$cssContent = preg_replace( "/[\#]+/", "#", $cssContent );
		$cssContent = str_replace( array( ' 0px', ':0px', ';}', ':0 0 0 0', ':0.', ' 0.' ), array( ' 0', ':0', '}', ':0', ':.', ' .' ), $cssContent );
		$cssContent = preg_replace( '/\\s*([{;])\\s*([\\*_]?[\\w\\-]+)\\s*:\\s*(\\b|[#\'"-])/x', '$1$2:$3', $cssContent );

		//$cssContent = preg_replace_callback( '/(?:\\s*[^~>+,\\s]+\\s*[,>+~])+\\s*[^~>+,\\s]+{/x', array( $this, 'selectorsCB' ), $cssContent );
		$cssContent = preg_replace( '/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i', '$1#$2$3$4$5', $cssContent );
		$cssContent = preg_replace_callback( '/font-family:([^;}]+)([;}])/', array( $this, 'fontFamilyCB' ), $cssContent );
		$cssContent = preg_replace( '/@import\\s+url/', '@import url', $cssContent );
		$cssContent = preg_replace( '/:first-l(etter|ine)\\{/', ':first-l$1 {', $cssContent );
		$cssContent = preg_replace( "/[^\}]+\{[\s|\;]*\}[\s]*/", "", $cssContent );
		$cssContent = preg_replace( "/[ ]+/", " ", $cssContent );
		$cssContent = trim( $cssContent );
		return $cssContent;
	}

	/**
	 * CJzip::compress_javascript()
	 *
	 * @param mixed $jsContent
	 * @return
	 */
	private function compress_javascript( $jsContent )
	{
		$jsContent = preg_replace( "/(\r\n)+|(\n|\r)+/", "\r\n", $jsContent );
		return $jsContent;
	}

	/**
	 * CJzip::loadFile()
	 *
	 * @return
	 */
	public function loadFile()
	{
		$hash = $this->file['lastmod'] . '-' . $this->file['md5file'];
		header( 'Etag: "' . $hash . '"' );

		if( $this->is_notModified( $hash ) ) $this->browseInfo( 304 );

		$this->encoding = $this->check_encode();
		$this->loadData();
	}
}

$CJzip = new CJzip();
$CJzip->loadFile();