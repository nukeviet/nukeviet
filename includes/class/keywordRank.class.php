<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 25/12/2010, 11:2
 */

if( defined( 'NV_CLASS_KEYWORDRANK' ) ) return;
define( 'NV_CLASS_KEYWORDRANK', true );

if( ! defined( 'NV_CURRENTTIME' ) ) define( 'NV_CURRENTTIME', time() );
if( ! defined( 'NV_ROOTDIR' ) ) define( 'NV_ROOTDIR', preg_replace( "/[\/]+$/", '', str_replace( '\\', '/', realpath( dirname( __file__ ) . '/../../' ) ) ) );
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

class keywordRank
{
	private $keyword;
	private $lang;
	private $accuracy;
	private $myDomain;
	public $currentDomain;
	private $pattern = array( //
		'googleByDomain' => 'http://www.google.com/search?hl=en&domains=%s&q=%s&sitesearch=%s%s', //
		'googleByAll' => 'http://www.google.com/search?hl=en&q=%s%s' //
	);
	private $langList = array( //
		'af', 'sq', 'ar', 'be', 'bg', 'ca', 'zh-CN', 'hr', 'cs', 'da', 'nl', 'et', 'tl', 'fi', 'fr', 'gl', 'de', //
		'en', 'el', 'ht', 'iw', 'hi', 'hu', 'is', 'id', 'ga', 'it', 'ja', 'ko', 'lv', 'lt', 'mk', 'ms', 'mt', 'no', //
		'fa', 'pl', 'pt', 'ro', 'ru', 'sr', 'sk', 'sl', 'es', 'sw', 'sv', 'th', 'tr', 'uk', 'vi', 'cy', 'yi' //
);

	/**
	 * keywordRank::__construct()
	 *
	 * @param mixed $_pattern
	 * @return
	 */
	function __construct( $_pattern = array() )
	{
		if( isset( $_pattern['googleByDomain'] ) ) $this->$pattern['googleByDomain'] = $_pattern['googleByDomain'];
		if( isset( $_pattern['googleByAll'] ) ) $this->$pattern['googleByAll'] = $_pattern['googleByAll'];
		$this->myDomain = NV_SERVER_NAME;
		//$this->myDomain = 'nukeviet.vn';
	}

	/**
	 * keywordRank::getInfoFromGoogle()
	 *
	 * @return
	 */
	private function getInfoFromGoogle()
	{
		global $getContent;

		$key = $this->keyword;
		if( $this->accuracy == 'phrase' ) $key = "\"" . $key . "\"";
		$key = urlencode( $key );
		$domain = urlencode( $this->currentDomain );
		$lang = ! empty( $this->lang ) ? "&lr=lang_" . $this->lang : "";

		$url = sprintf( $this->pattern['googleByDomain'], $domain, $key, $domain, $lang );
		$content = $getContent->get( $url );

		$result = array();
		$result['myPages'] = 0;
		$result['top10MyPages'] = array();
		$result['allPages'] = 0;
		$result['top50AllPages'] = array();
		$result['rank'] = array();

		if( preg_match( '/\<div\>About ([0-9,]+) results\<\/div\>/is', $content, $match ) )
		{
			$bl = preg_replace( '/\,/', '', $match[1] );
			$result['myPages'] = ( int )$bl;

			unset( $links );
			preg_match_all( '/\<h3\s+class\=\"?r[^\>]*\>[^\<]*\<a\s+href\s?\=\s?\"([^\"]+)\"[^\>]*>/', $content, $links );

			if( ! empty( $links[1] ) )
			{
				foreach( $links[1] as $_k => $_v )
				{
					if( preg_match( '/url\?q\=(.*)\&amp\;sa\=/i', $_v, $m ) ) $links[1][$_k] = $m[1];
				}

				$result['top10MyPages'] = $links[1];
			}
		}

		$url = sprintf( $this->pattern['googleByAll'], $key, $lang );

		for( $i = 0; $i < 5; ++$i )
		{
			$start = $i * 10;
			if( $start != 0 ) $url .= '&start=' . $start;
			$content = $getContent->get( $url );

			if( $start == 0 )
			{
				if( preg_match( '/\<div\>About ([0-9,]+) results\<\/div\>/is', $content, $match ) )
				{
					$bl = preg_replace( '/\,/', '', $match[1] );
					$result['allPages'] = ( int )$bl;
				}
			}

			unset( $links );
			preg_match_all( '/\<h3\s+class\=\"?r[^\>]*\>[^\<]*\<a\s+href\s?\=\s?\"\/url\?q\=([^\"]+)\&amp\;sa\=([^\"]+)\"[^\>]*>/', $content, $links );
			if( ! empty( $links[1] ) ) $result['top50AllPages'] = array_merge( $result['top50AllPages'], $links[1] );
		}

		if( ! empty( $result['top50AllPages'] ) )
		{
			$fl_array = preg_grep( '/^http(s?)\:\/\/[(www.)]*' . preg_quote( $this->currentDomain, '/' ) . '/', $result['top50AllPages'] );
			$result['rank'] = array();
			$array_keys = array_keys( $fl_array );
			foreach( $array_keys as $k )
			{
				$result['rank'][$k] = $k + 1;
			}
		}

		return $result;
	}

	/**
	 * keywordRank::process()
	 *
	 * @param mixed $_keyword
	 * @param mixed $_lang
	 * @param mixed $_accuracy
	 * @param string $from
	 * @param string $domain
	 * @return
	 */
	public function process( $_keyword, $_lang, $_accuracy, $from = '', $domain = '' )
	{
		$this->keyword = $_keyword;

		if( $_accuracy != 'phrase' ) $_accuracy = 'keyword';
		$this->accuracy = $_accuracy;

		if( ! in_array( $_lang, $this->langList ) ) $_lang = '';
		$this->lang = $_lang;

		if( empty( $domain ) )
		{
			$domain = $this->myDomain;
		}

		$domain = preg_replace( '/^[a-zA-Z]+\:\/\//', '', $domain );

		$this->currentDomain = $domain;

		if( ! empty( $from ) ) $from = strtolower( $from );
		if( $from != 'yahoo' ) $from = 'google';

		if( preg_match( '/^localhost|127\.0\.0/is', $this->currentDomain ) )
		{
			return false;
		}

		$result = array();
		$result['myDomain'] = $this->currentDomain;
		$result['keyword'] = $this->keyword;
		$result['lang'] = $this->lang;
		$result['accuracy'] = $this->accuracy;
		$result['fromEngine'] = $from;
		$result['updtime'] = NV_CURRENTTIME;
		$result['detail'] = array();

		if( $from == 'yahoo' )
		{
			//Viet sau
		}
		else
		{
			$result['detail'] = $this->getInfoFromGoogle();
		}

		return $result;
	}
}