<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1-27-2010 5:25
 */

class ips
{
	public $client_ip;
	public $forward_ip;
	public $remote_addr;
	public $remote_ip;
	public $is_proxy = 0;

	/**
 * sql_db::__construct()
 *
 * @param mixed $db_config
 * @return
 */
	public function __construct()
	{
		$this->client_ip = $this->nv_get_clientip();
		$this->forward_ip = $this->nv_get_forwardip();
		$this->remote_addr = $this->nv_get_remote_addr();
		$this->remote_ip = $this->nv_getip();
	}

	/**
	 * ips::nv_getenv()
	 *
	 * @param mixed $key
	 * @return
	 */
	private function nv_getenv( $key )
	{
		if( isset( $_SERVER[$key] ) )
		{
			return $_SERVER[$key];
		}
		elseif( isset( $_ENV[$key] ) )
		{
			return $_ENV[$key];
		}
		elseif( @getenv( $key ) )
		{
			return @getenv( $key );
		}
		elseif( function_exists( 'apache_getenv' ) && apache_getenv( $key, true ) )
		{
			return apache_getenv( $key, true );
		}
		return '';
	}

	/**
	 * ips::nv_validip()
	 *
	 * @param mixed $ip
	 * @return
	 */
	public function nv_validip( $ip )
	{
		return filter_var( $ip, FILTER_VALIDATE_IP );
	}

	/**
	 * ips::server_ip()
	 *
	 * @return
	 */
	public function server_ip()
	{
		$serverip = $this->nv_getenv( 'SERVER_ADDR' );
		if( $this->nv_validip( $serverip ) )
		{
			return $serverip;
		}
		elseif( $_SERVER['SERVER_NAME'] == 'localhost' )
		{
			return '127.0.0.1';
		}
		elseif( function_exists( 'gethostbyname' ) )
		{
			return gethostbyname( $_SERVER['SERVER_NAME'] );
		}
		return 'none';
	}

	/**
	 * ips::nv_get_clientip()
	 *
	 * @return
	 */
	private function nv_get_clientip()
	{
		$clientip = '';
		if( $this->nv_getenv( 'HTTP_CLIENT_IP' ) )
		{
			$clientip = $this->nv_getenv( 'HTTP_CLIENT_IP' );
		}
		elseif( $this->nv_getenv( 'HTTP_VIA' ) )
		{
			$clientip = $this->nv_getenv( 'HTTP_VIA' );
		}
		elseif( $this->nv_getenv( 'HTTP_X_COMING_FROM' ) )
		{
			$clientip = $this->nv_getenv( 'HTTP_X_COMING_FROM' );
		}
		elseif( $this->nv_getenv( 'HTTP_COMING_FROM' ) )
		{
			$clientip = $this->nv_getenv( 'HTTP_COMING_FROM' );
		}

		if( $this->nv_validip( $clientip ) )
		{
			return $clientip;
		}
		else
		{
			return 'none';
		}
	}

	/**
	 * ips::nv_get_forwardip()
	 *
	 * @return
	 */
	private function nv_get_forwardip()
	{
		if( $this->nv_getenv( 'HTTP_X_FORWARDED_FOR' ) and $this->nv_validip( $this->nv_getenv( 'HTTP_X_FORWARDED_FOR' ) ) )
		{
			return $this->nv_getenv( 'HTTP_X_FORWARDED_FOR' );
		}
		elseif( $this->nv_getenv( 'HTTP_X_FORWARDED' ) and $this->nv_validip( $this->nv_getenv( 'HTTP_X_FORWARDED' ) ) )
		{
			return $this->nv_getenv( 'HTTP_X_FORWARDED' );
		}
		elseif( $this->nv_getenv( 'HTTP_FORWARDED_FOR' ) and $this->nv_validip( $this->nv_getenv( 'HTTP_FORWARDED_FOR' ) ) )
		{
			return $this->nv_getenv( 'HTTP_FORWARDED_FOR' );
		}
		elseif( $this->nv_getenv( 'HTTP_FORWARDED' ) and $this->nv_validip( $this->nv_getenv( 'HTTP_FORWARDED' ) ) )
		{
			return $this->nv_getenv( 'HTTP_FORWARDED' );
		}
		else
		{
			return 'none';
		}
	}

	/**
	 * ips::nv_get_remote_addr()
	 *
	 * @return
	 */
	private function nv_get_remote_addr()
	{
		if( $this->nv_getenv( 'REMOTE_ADDR' ) and $this->nv_validip( $this->nv_getenv( 'REMOTE_ADDR' ) ) )
		{
			return $this->nv_getenv( 'REMOTE_ADDR' );
		}
		return 'none';
	}

	/**
	 * ips::nv_getip()
	 *
	 * @return
	 */
	private function nv_getip()
	{
		if( $this->client_ip != 'none' )
		{
			return $this->client_ip;
		}
		if( $this->forward_ip != 'none' )
		{
			return $this->forward_ip;
		}
		if( $this->remote_addr != 'none' )
		{
			return $this->remote_addr;
		}

		if( $_SERVER['SERVER_NAME'] == 'localhost' )
		{
			return '127.0.0.1';
		}
		return 'none';
	}

	/**
	 * ips::nv_chech_proxy()
	 *
	 * @return
	 */
	public function nv_check_proxy()
	{
		$proxy = 'No';
		if( $this->client_ip != 'none' || $this->forward_ip != 'none' ) $proxy = 'Lite';
		$host = @getHostByAddr( $this->remote_ip );
		if( stristr( $host, 'proxy' ) ) $proxy = 'Mild';
		if( $this->remote_ip == $host ) $proxy = 'Strong';
		return $proxy;
	}
}