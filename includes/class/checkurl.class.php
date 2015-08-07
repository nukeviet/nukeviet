<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/28/2010 19:3
 */

/*
 * if ( defined( 'NV_CLASS_CHECKHOST' ) ) return; define( 'NV_CLASS_CHECKHOST', true );
 */
class CheckUrl
{
	protected $host = null;
	protected $port = null;

	public function fsockopen( $host, $port )
	{
		$this->host = preg_replace( '/http:\/\//i', '', $host );
		$this->port = $port;
		$connection = @fsockopen( $this->host, $this->port );

		if( $connection )
		{
			fclose( $connection );
			return true;
		}

		return false;
	}

	public function socket_connect()
	{
		$this->host = $host;
		$this->port = $port;

		$sock = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
		$connection = @socket_connect( $sock, $this->host, $this->port );

		if( $connection )
		{
			socket_close( $sock );
			return true;
		}

		return false;
	}

	public function check_curl( $host )
	{
		$this->host = $host;
		if( ! function_exists( "curl_init" ) ) return false;

		$curl = curl_init();
		$header = array();
		$header[] = "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection: keep-alive";
		$header[] = "Keep-Alive: 300";
		$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$header[] = "Accept-Language: en-us,en;q=0.5";
		$header[] = "Pragma: ";

		curl_setopt( $curl, CURLOPT_URL, $this->host );
		curl_setopt( $curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)' );
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $header );
		curl_setopt( $curl, CURLOPT_REFERER, 'http://www.google.com' );
		curl_setopt( $curl, CURLOPT_ENCODING, 'gzip,deflate' );
		curl_setopt( $curl, CURLOPT_AUTOREFERER, true );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $curl, CURLOPT_TIMEOUT, 10 );
		if( curl_exec( $curl ) )
		{
			curl_close( $curl );
			return true;
		}
		return false;
	}
}