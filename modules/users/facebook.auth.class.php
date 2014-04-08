<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/6/2012, 2:4
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

/**
 * FaceBookAuth
 *
 * @package
 * @author VINADES.,JSC
 * @copyright VINADES.,JSC
 * @version 2012
 * @access public
 */
class FaceBookAuth
{
	// ID ung dung
	private $app_id = '';

	// Ma bi mat
	private $app_secret = '';

	// Url tra lai
	private $return_url = '';

	// Url xac nhan
	private $base_auth_url = "https://www.facebook.com/dialog/oauth?client_id=%s&redirect_uri=%s&scope=%s&state=%s";

	//
	private $token_url = "https://graph.facebook.com/oauth/access_token?client_id=%s&redirect_uri=%s&client_secret=%s&code=%s";

	// Get thong tin
	private $graph_base_url = "https://graph.facebook.com/me?access_token=%s";

	/**
	 * FaceBookAuth::__construct()
	 *
	 * @param mixed $id
	 * @param mixed $secret
	 * @param mixed $url
	 * @return
	 */
	public function __construct( $id, $secret, $url )
	{
		$this->app_id = $id;
		$this->app_secret = $secret;
		$this->return_url = $url;
	}

	/**
	 * FaceBookAuth::GetOAuthDialogUrl()
	 *
	 * @param mixed $state
	 * @param mixed $scope
	 * @return
	 */
	public function GetOAuthDialogUrl( $state, $scope )
	{
		return sprintf( $this->base_auth_url, $this->app_id, urlencode( $this->return_url ), $scope, $state );
	}

	/**
	 * FaceBookAuth::GetTokend()
	 *
	 * @param mixed $code
	 * @return
	 */
	private function GetTokend( $code )
	{
		$tokend = sprintf( $this->token_url, $this->app_id, urlencode( $this->return_url ), $this->app_secret, $code );
		return file_get_contents( $tokend );
	}

	/**
	 * FaceBookAuth::decode()
	 *
	 * @param mixed $data
	 * @return
	 */
	private function decode( $data )
	{
		return json_decode( $data );
	}

	/**
	 * FaceBookAuth::GraphBase()
	 *
	 * @param mixed $code
	 * @return
	 */
	public function GraphBase( $code )
	{
		$tokend = $this->GetTokend( $code );

		unset( $params );
		parse_str( $tokend, $params );

		$data = file_get_contents( sprintf( $this->graph_base_url, $params['access_token'] ) );

		return $this->decode( $data );
	}

	/**
	 * FaceBookAuth::getAttributes()
	 *
	 * @param mixed $data
	 * @param mixed $config
	 * @return
	 */
	public function getAttributes( $data, $config )
	{
		$return = array();
		foreach( $config as $val => $key )
		{
			if( ! isset( $data->$val ) )
			{
				$return[$key] = '';
			}
			else
			{
				$return[$key] = $data->$val;
			}
		}
		return $return;
	}
}