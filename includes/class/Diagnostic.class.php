<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 23/12/2010, 9:36
 */

if( defined( 'NV_CLASS_DIAGNOSTIC' ) ) return;
define( 'NV_CLASS_DIAGNOSTIC', true );

if( ! defined( 'NV_CURRENTTIME' ) ) define( 'NV_CURRENTTIME', time() );
if( ! defined( 'NV_ROOTDIR' ) ) define( 'NV_ROOTDIR', preg_replace( "/[\/]+$/", '', str_replace( '\\', '/', realpath( dirname( __file__ ) . '/../../' ) ) ) );
if( ! defined( 'NV_DATADIR' ) ) define( 'NV_DATADIR', 'data' );
if( ! defined( 'NV_SERVER_NAME' ) )
{
	$_server_name = ( isset( $_SERVER['SERVER_NAME'] ) and ! empty( $_SERVER['SERVER_NAME'] ) ) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];
	$_server_name = preg_replace( '/^[a-zA-Z]+\:\/\//', '', $_server_name );
	define( 'NV_SERVER_NAME', $_server_name );
	unset( $_server_name );
}

if( ! isset( $getContent ) or ! is_object( $getContent ) )
{
	if( ! isset( $global_config ) or empty( $global_config ) )
	{
		$global_config = array( 'version' => '3.0.12', 'sitekey' => mt_rand() );
	}

	if( ! class_exists( 'UrlGetContents' ) )
	{
		include NV_ROOTDIR . '/includes/class/geturl.class.php' ;
	}

	$getContent = new UrlGetContents( $global_config );
}

class Diagnostic
{
	private $googleDomains = array( 'toolbarqueries.google.com' );
	private $pattern = array(
		'PageRank' => 'http://%s/tbr?client=navclient-auto&ch=%s&features=Rank&q=info%s&num=100&filter=0',
		'AlexaRank' => 'http://data.alexa.com/data?cli=10&dat=nsa&url=%s',
		'GoogleBackLink' => 'http://www.google.com/search?hl=en&q=link%s',
		'GoogleIndexed' => 'http://www.google.com/search?hl=en&q=site%s',
		'BingBackLink' => 'http://www.bing.com/search?q=link%s&go=&qs=bs&form=QBRE',
		'BingIndexed' => 'http://www.bing.com/search?q=site%s&go=&qs=bs&form=QBRE'
	);
	private $myDomain;
	public $currentDomain;
	public $currentCache;
	private $max = 1000;
	private $disable_functions = array();

	/**
	 * Diagnostic::__construct()
	 *
	 * @param mixed $_pattern
	 * @return
	 */
	function __construct( $_pattern = array() )
	{
		if( isset( $_pattern['PageRank'] ) ) $this->$pattern['PageRank'] = $_pattern['PageRank'];
		if( isset( $_pattern['AlexaRank'] ) ) $this->$pattern['AlexaRank'] = $_pattern['AlexaRank'];
		if( isset( $_pattern['GoogleBackLink'] ) ) $this->$pattern['GoogleBackLink'] = $_pattern['GoogleBackLink'];
		if( isset( $_pattern['GoogleIndexed'] ) ) $this->$pattern['GoogleIndexed'] = $_pattern['GoogleIndexed'];
		if( isset( $_pattern['BingBackLink'] ) ) $this->$pattern['BingBackLink'] = $_pattern['BingBackLink'];
		if( isset( $_pattern['BingIndexed'] ) ) $this->$pattern['BingIndexed'] = $_pattern['BingIndexed'];

		$disable_functions = ( ini_get( 'disable_functions' ) != '' and ini_get( 'disable_functions' ) != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", ini_get( 'disable_functions' ) ) ) : array();
		if( extension_loaded( 'suhosin' ) )
		{
			$disable_functions = array_merge( $disable_functions, array_map( 'trim', preg_split( "/[\s,]+/", ini_get( 'suhosin.executor.func.blacklist' ) ) ) );
		}

		$this->disable_functions = $disable_functions;
		$this->myDomain = NV_SERVER_NAME;
		// $this->myDomain = 'nukeviet.vn';
	}

	/**
	 * Diagnostic::strToNum()
	 *
	 * @param mixed $Str
	 * @param mixed $Check
	 * @param mixed $Magic
	 * @return
	 */
	private function strToNum( $Str, $Check, $Magic )
	{
		$Int32Unit = 4294967296;

		$length = strlen( $Str );
		for( $i = 0; $i < $length; ++$i )
		{
			$Check *= $Magic;

			if( $Check >= $Int32Unit )
			{
				$Check = ( $Check - $Int32Unit * ( int )( $Check / $Int32Unit ) );

				$Check = ( $Check < - 2147483648 ) ? ( $Check + $Int32Unit ) : $Check;
			}
			$Check += ord( $Str{$i} );
		}
		return $Check;
	}

	/**
	 * Diagnostic::hashURL()
	 *
	 * @param mixed $String
	 * @return
	 */
	private function hashURL( $String )
	{
		$Check1 = $this->strToNum( $String, 0x1505, 0x21 );
		$Check2 = $this->strToNum( $String, 0, 0x1003F );
		$Check1 >>= 2;
		$Check1 = ( ( $Check1 >> 4 ) & 0x3FFFFC0 ) | ( $Check1 & 0x3F );
		$Check1 = ( ( $Check1 >> 4 ) & 0x3FFC00 ) | ( $Check1 & 0x3FF );
		$Check1 = ( ( $Check1 >> 4 ) & 0x3C000 ) | ( $Check1 & 0x3FFF );

		$T1 = ( ( ( ( $Check1 & 0x3C0 ) << 4 ) | ( $Check1 & 0x3C ) ) << 2 ) | ( $Check2 & 0xF0F );
		$T2 = ( ( ( ( $Check1 & 0xFFFFC000 ) << 4 ) | ( $Check1 & 0x3C00 ) ) << 0xA ) | ( $Check2 & 0xF0F0000 );

		return ( $T1 | $T2 );
	}

	/**
	 * Diagnostic::checkHash()
	 *
	 * @param mixed $Hashnum
	 * @return
	 */
	private function checkHash( $Hashnum )
	{
		$CheckByte = 0;
		$Flag = 0;
		$HashStr = sprintf( '%u', $Hashnum );
		$length = strlen( $HashStr );

		for( $i = $length - 1; $i >= 0; --$i )
		{
			$Re = $HashStr{$i};
			if( 1 === ( $Flag % 2 ) )
			{
				$Re += $Re;
				$Re = ( int )( $Re / 10 ) + ( $Re % 10 );
			}
			$CheckByte += $Re;
			++$Flag;
		}

		$CheckByte %= 10;
		if( 0 !== $CheckByte )
		{
			$CheckByte = 10 - $CheckByte;
			if( 1 === ( $Flag % 2 ) )
			{
				if( 1 === ( $CheckByte % 2 ) )
				{
					$CheckByte += 9;
				}
				$CheckByte >>= 1;
			}
		}
		return '7' . $CheckByte . $HashStr;
	}

	/**
	 * Diagnostic::getPageRank()
	 *
	 * @return
	 */
	public function getPageRank()
	{
		global $getContent;

		if( extension_loaded( 'curl' ) and ( empty( $this->disable_functions ) or ( ! empty( $this->disable_functions ) and ! preg_grep( '/^curl\_/', $this->disable_functions ) ) ) )
		{
			$ch = $this->checkHash( $this->hashURL( $this->currentDomain ) );
			$host = $this->googleDomains[mt_rand( 0, sizeof( $this->googleDomains ) - 1 )];
			$url = sprintf( $this->pattern['PageRank'], $host, $ch, urlencode( ':' . $this->currentDomain ) );

			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_HEADER, 0 );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_URL, $url );
			$content = curl_exec( $ch );

			if( ! curl_errno( $ch ) )
			{
				$info = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
				if( $info == '200' )
				{
					if( preg_match( '/^Rank\_(\d+)\:(\d+)\:(\d+)$/i', $content, $matches ) )
					{
						curl_close( $ch );
						return ( int )$matches[3];
					}
				}
			}
			curl_close( $ch );
		}

		return 0;
	}

	/**
	 * Diagnostic::getAlexaRank()
	 *
	 * @return
	 */
	public function getAlexaRank()
	{
		global $getContent;

		$url = sprintf( $this->pattern['AlexaRank'], urlencode( $this->currentDomain ) );
		$content = $getContent->get( $url );
		$xmldata = simplexml_load_string( $content );

		$result = array( 0, 0, 0 );
		if( isset( $xmldata->SD[1]->POPULARITY['TEXT'] ) )
		{
			$result[0] = ( int )$xmldata->SD[1]->POPULARITY['TEXT'];
		}
		if( isset( $xmldata->SD[0]->LINKSIN['NUM'] ) )
		{
			$result[1] = ( int )$xmldata->SD[0]->LINKSIN['NUM'];
		}
		if( isset( $xmldata->SD[1]->REACH['RANK'] ) )
		{
			$result[2] = ( int )$xmldata->SD[1]->REACH['RANK'];
		}

		return $result;
	}

	/**
	 * Diagnostic::getGoogleBackLink()
	 *
	 * @return
	 */
	public function getGoogleBackLink()
	{
		global $getContent;

		$url = sprintf( $this->pattern['GoogleBackLink'], urlencode( ':' . $this->currentDomain ) );
		$content = $getContent->get( $url );

		if( preg_match( '/\<div(.*?)\>About ([0-9\,]+) results(.*?)<\/div\>/isU', $content, $match ) )
		{
			$bl = preg_replace( '/\,/', '', $match[2] );
			return ( int )$bl;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Diagnostic::getGoogleIndexed()
	 *
	 * @return
	 */
	public function getGoogleIndexed()
	{
		global $getContent;

		$url = sprintf( $this->pattern['GoogleIndexed'], urlencode( ':' . $this->currentDomain ) );
		$content = $getContent->get( $url );

		if( preg_match( '/\<div(.*?)\>About ([0-9\,]+) results(.*?)\<\/div\>/isU', $content, $match ) )
		{
			$bl = preg_replace( '/\,/', '', $match[2] );
			return ( int )$bl;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Diagnostic::getBingBackLink()
	 *
	 * @return
	 */
	public function getBingBackLink()
	{
		global $getContent;

		$url = sprintf( $this->pattern['BingBackLink'], urlencode( ':' . $this->currentDomain ) );
		$content = $getContent->get( $url );

		if( preg_match( "/\<span class\=\"sb\_count\" id\=\"count\"\>([0-9\,]+) results\<\/span\>/isU", $content, $match ) )
		{
			$bl = preg_replace( '/\,/', '', $match[1] );
			return ( int )$bl;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Diagnostic::getBingIndexed()
	 *
	 * @return
	 */
	public function getBingIndexed()
	{
		global $getContent;

		$url = sprintf( $this->pattern['BingIndexed'], urlencode( ':' . $this->currentDomain ) );
		$content = $getContent->get( $url );

		if( preg_match( "/\<span class\=\"sb\_count\" id\=\"count\"\>([0-9\,]+) results\<\/span\>/isU", $content, $match ) )
		{
			$bl = preg_replace( '/\,/', '', $match[1] );
			return ( int )$bl;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Diagnostic::newGetInfo()
	 *
	 * @param mixed $content
	 * @return
	 */
	public function newGetInfo( $content )
	{
		$count = sizeof( $content['item'] );
		if( $count >= $this->max )
		{
			krsort( $content['item'] );
			$array_chunk = array_chunk( $content['item'], ( $this->max - 1 ), false );
			$content['item'] = $array_chunk[0];
			krsort( $content['item'] );
		}

		$result = array();
		$result['date'] = gmdate( 'D, d M Y H:i:s', NV_CURRENTTIME ) . ' GMT';
		$result['PageRank'] = $this->getPageRank();
		list( $result['AlexaRank'], $result['AlexaBackLink'], $result['AlexaReach'] ) = $this->getAlexaRank();
		$result['GoogleBackLink'] = $this->getGoogleBackLink();
		$result['GoogleIndexed'] = $this->getGoogleIndexed();
		$result['BingBackLink'] = $this->getBingBackLink();
		$result['BingIndexed'] = $this->getBingIndexed();
		$content['item'][] = $result;

		$xmlHeader = '<?xml version="1.0" encoding="UTF-8"?><diagnostic></diagnostic>';
		$xml = new SimpleXMLElement( $xmlHeader );
		foreach( $content['item'] as $item )
		{
			$row = $xml->addChild( 'item' );
			foreach( $item as $k => $v )
			{
				$row->addChild( $k, $v );
			}
		}
		$cont = $xml->asXML();
		file_put_contents( $this->currentCache, $cont );
		return $content;
	}

	/**
	 * Diagnostic::getInfo()
	 *
	 * @param mixed $time
	 * @return
	 */
	private function getInfo( $time )
	{
		$content = array();
		$content['item'] = array();

		if( preg_match( '/^localhost|127\.0\.0/is', $this->currentDomain ) )
		{
			$content['item'][] = array(
				'date' => gmdate( 'D, d M Y H:i:s', NV_CURRENTTIME ) . ' GMT',
				'PageRank' => 0,
				'AlexaRank' => 0,
				'AlexaBackLink' => 0,
				'AlexaReach' => 0,
				'GoogleBackLink' => 0,
				'GoogleIndexed' => 0,
				'BingBackLink' => 0,
				'BingIndexed' => 0
			);
			return $content;
		}

		$lastProcess = 0;

		if( file_exists( $this->currentCache ) )
		{
			$content = simplexml_load_file( $this->currentCache );
			$content = nv_object2array( $content );
			if( ! isset( $content['item'][0] ) )
			{
				$content['item'] = array( $content['item'] );
			}

			if( ! empty( $time ) )
			{
				$p = NV_CURRENTTIME - $time;
				$filemtime = @filemtime( $this->currentCache );

				if( $filemtime < $p )
				{
					return $this->newGetInfo( $content );
				}
			}

			$lastProcess = $content['item'][( sizeof( $content['item'] ) - 1 )]['date'];
			$lastProcess = strtotime( $lastProcess );
		}

		$currentMonth = mktime( 0, 0, 0, date( 'm', NV_CURRENTTIME ), 1, date( 'Y', NV_CURRENTTIME ) );

		if( $lastProcess < $currentMonth )
		{
			$content = $this->newGetInfo( $content );
		}

		return $content;
	}

	/**
	 * Diagnostic::process()
	 *
	 * @param integer $time
	 * @param string $domain
	 * @return
	 */
	public function process( $time = 0, $domain = '' )
	{
		if( empty( $domain ) )
		{
			$domain = $this->myDomain;
		}

		$domain = preg_replace( array( '/^[a-z]+\:\/\//i', '/^www\./i' ), array( '', '' ), $domain );

		$this->currentDomain = $domain;
		$this->currentCache = NV_ROOTDIR . '/' . NV_DATADIR . '/diagnostic-' . $this->currentDomain . '.xml';

		return $this->getInfo( $time );
	}
}