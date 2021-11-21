<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Zalo;

use NukeViet\Http\Curl;
use ValueError;

/**
 * NukeViet\Zalo\Zalo
 *
 * @package NukeViet\Zalo
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Zalo
{
    const ERROR_OA_ID_EMPTY = 'oa_id_empty';
    const ERROR_REDIRECT_URI_EMPTY = 'redirect_uri_empty';
    const ERROR_APP_ID_EMPTY = 'app_id_empty';
    const ERROR_APP_SECKEY_EMPTY = 'app_seckey_empty';
    const ERROR_REFRESH_TOKEN_EMPTY = 'refresh_token_empty';
    const ERROR_NOT_RESPONSE = 'not_response';
    const ERROR_OA_ID_INCORRECT = 'oa_id_incorrect';
    const ERROR_REFRESH_TOKEN_EXP = 'refresh_token_expired';

    const PERMISSION_URL = 'https://oauth.zaloapp.com/v4/permission?';
    const OA_PERMISSION_URL = 'https://oauth.zaloapp.com/v4/oa/permission?';
    const OA_GET_ACCESSTOKEN_URL = 'https://oauth.zaloapp.com/v4/oa/access_token';
    const GET_ACCESSTOKEN_URL = 'https://oauth.zaloapp.com/v4/access_token';
    const GET_USERINO_URL = 'https://graph.zalo.me/v2.0/me?';

    private $oa_id = '';
    private $app_id = '';
    private $app_secret_key = '';
    private $oa_access_token = '';
    private $oa_refresh_token = '';
    private $oa_access_token_exp = false;
    private $oa_refresh_token_exp = false;

    private $error = '';

    /**
     * __construct()
     *
     * @param array  $configs
     */
    public function __construct($configs)
    {
        $this->oa_id = $configs['zaloOfficialAccountID'];
        $this->app_id = $configs['zaloAppID'];
        $this->app_secret_key = $configs['zaloAppSecretKey'];
        $this->oa_access_token = $configs['zaloOAAccessToken'];
        $this->oa_refresh_token = $configs['zaloOARefreshToken'];
        $this->oa_access_token_exp = (NV_CURRENTTIME - (int)$configs['zaloOAAccessTokenTime']) > 3000;
        $this->oa_refresh_token_exp = (NV_CURRENTTIME - (int)$configs['zaloOAAccessTokenTime']) > 7000000;
    }

    /**
     * preCheckValid()
     * 
     * @return false|void 
     */
    private function preCheckValid()
    {
        if (empty($this->oa_id)) {
            $this->error = self::ERROR_OA_ID_EMPTY;
            return false;
        }

        if (empty($this->app_id)) {
            $this->error = self::ERROR_APP_ID_EMPTY;
            return false;
        }

        if (empty($this->app_secret_key)) {
            $this->error = self::ERROR_APP_SECKEY_EMPTY;
            return false;
        }
    }

    /**
     * base64url_encode()
     * 
     * @param mixed $plainText 
     * @return string 
     */
    private static function base64url_encode($plainText)
    {
        $base64 = base64_encode($plainText);
        $base64 = trim($base64, "=");
        $base64url = strtr($base64, '+/', '-_');
        return ($base64url);
    }

    /**
     * stateCreate()
     * 
     * @param int $num 
     * @return string 
     */
    private static function stateCreate($num = 8)
    {
        return substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', 5)), $num, $num);
    }

    /**
     * codeVerifierCreate()
     * 
     * @return array 
     */
    private static function codeVerifierCreate()
    {
        $random = bin2hex(openssl_random_pseudo_bytes(32));
        $code_verifier = self::base64url_encode(pack('H*', $random));
        $code_challenge = self::base64url_encode(pack('H*', hash('sha256', $code_verifier)));
        return [$code_verifier, $code_challenge];
    }

    /**
     * getResponse()
     * 
     * @param mixed $url 
     * @param mixed $args 
     * @return mixed 
     * @throws ValueError 
     */
    private static function getResponse($url, $args)
    {
        $NV_Curl = new Curl();
        return $NV_Curl->request($url, $args);
    }

    /**
     * accesstoken_new_result()
     * 
     * @param mixed $url 
     * @param mixed $args 
     * @return false|array 
     * @throws ValueError 
     */
    private function accesstoken_new_result($url, $args)
    {
        $response = self::getResponse($url, $args);
        if (empty($response['body'])) {
            $this->error = self::ERROR_NOT_RESPONSE;
            return false;
        }

        $response = json_decode($response['body'], true);
        if (empty($response)) {
            $this->error = self::ERROR_NOT_RESPONSE;
            return false;
        }

        if (!empty($response['error'])) {
            $this->error = $response['error_name'];
            return false;
        }

        return [
            'access_token' => $response['access_token'],
            'refresh_token' => $response['refresh_token']
        ];
    }

    /**
     * oa_accesstoken_from_refreshtoken()
     * 
     * @return false|array 
     * @throws ValueError 
     */
    private function oa_accesstoken_from_refreshtoken()
    {
        if (empty($this->oa_refresh_token)) {
            $this->error = self::ERROR_REFRESH_TOKEN_EMPTY;
            return false;
        }

        if ($this->oa_refresh_token_exp) {
            $this->error = self::ERROR_REFRESH_TOKEN_EXP;
            return false;
        }

        $args = [
            'method' => 'POST',
            'headers' => [
                'secret_key' => $this->app_secret_key
            ],
            'body' => http_build_query([
                'refresh_token' => $this->oa_refresh_token,
                'app_id' => $this->app_id,
                'grant_type' => 'refresh_token'
            ])
        ];

        return $this->accesstoken_new_result(self::OA_GET_ACCESSTOKEN_URL, $args);
    }

    /**
     * permissionURLCreate()
     * 
     * @param mixed $redirect_uri 
     * @param string $level 
     * @return false|array 
     */
    public function permissionURLCreate($redirect_uri, $level = 'oa')
    {
        $this->preCheckValid();

        if (empty($redirect_uri)) {
            $this->error = self::ERROR_REDIRECT_URI_EMPTY;
            return false;
        }

        $state = self::stateCreate(8);
        list($code_verifier, $code_challenge) = self::codeVerifierCreate();
        $url = ($level == 'oa' ? self::OA_PERMISSION_URL : self::PERMISSION_URL) . http_build_query([
            'app_id' => $this->app_id,
            'redirect_uri' => $redirect_uri,
            'code_challenge' => $code_challenge,
            'state' => $state
        ]);

        return [
            'code_verifier' => $code_verifier,
            'permission_url' => $url
        ];
    }

    /**
     * oa_accesstoken_create()
     * 
     * @param mixed $redirect_uri 
     * @return false|array 
     * @throws ValueError 
     */
    public function oa_accesstoken_create($redirect_uri)
    {
        $this->preCheckValid();
        $result = $this->oa_accesstoken_from_refreshtoken();
        if (!empty($result)) {
            return $result;
        }

        return $this->permissionURLCreate($redirect_uri, 'oa');
    }

    public function oa_accesstoken_new($authorization_code, $oa_id, $code_verifier)
    {
        $this->preCheckValid();
        if ($oa_id != $this->oa_id) {
            $this->error = self::ERROR_OA_ID_INCORRECT;
            return false;
        }

        $args = [
            'method' => 'POST',
            'headers' => [
                'secret_key' => $this->app_secret_key
            ],
            'body' => http_build_query([
                'code' => $authorization_code,
                'app_id' => $this->app_id,
                'grant_type' => 'authorization_code',
                'code_verifier' => $code_verifier
            ])
        ];
        return $this->accesstoken_new_result(self::OA_GET_ACCESSTOKEN_URL, $args);
    }

    /**
     * accesstokenGet()
     * 
     * @param mixed $authorization_code 
     * @param mixed $code_verifier 
     * @return false|array 
     * @throws ValueError 
     */
    public function accesstokenGet($authorization_code, $code_verifier)
    {
        $this->preCheckValid();
        $args = [
            'method' => 'POST',
            'headers' => [
                'secret_key' => $this->app_secret_key
            ],
            'body' => http_build_query([
                'code' => $authorization_code,
                'app_id' => $this->app_id,
                'grant_type' => 'authorization_code',
                'code_verifier' => $code_verifier
            ])
        ];

        return $this->accesstoken_new_result(self::GET_ACCESSTOKEN_URL, $args);
    }

    /**
     * getUserInfo()
     * 
     * @param mixed $accesstoken 
     * @return false|array 
     * @throws ValueError 
     */
    public function getUserInfo($accesstoken)
    {
        $this->preCheckValid();
        $args = [
            'method' => 'GET',
            'headers' => [
                'access_token' => $accesstoken
            ]
        ];

        $url = self::GET_USERINO_URL . 'fields=id,birthday,name,gender,picture';
        $response = self::getResponse($url, $args);
        if (empty($response['body'])) {
            $this->error = self::ERROR_NOT_RESPONSE;
            return false;
        }

        $response = json_decode($response['body'], true);
        if (empty($response)) {
            $this->error = self::ERROR_NOT_RESPONSE;
            return false;
        }

        if (!empty($response['error'])) {
            $this->error = $response['message'];
            return false;
        }

        return [
            'id' => $response['id'],
            'name' => $response['name'],
            'gender' => $response['gender'],
            'birthday' => $response['birthday'],
            'picture' => $response['picture']['data']['url']
        ];
    }

    /**
     * getError()
     * 
     * @return string 
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * getCodeVerifier()
     * 
     * @return mixed 
     */
    public function getCodeVerifier()
    {
        return $this->code_verifier;
    }

    /**
     * isValid()
     * 
     * @return true 
     */
    public function isValid()
    {
        $this->preCheckValid();
        $this->error = '';
        return true;
    }

    /**
     * refreshToken_isExp()
     * 
     * @return bool 
     */
    public function refreshToken_isExp()
    {
        return $this->oa_refresh_token_exp;
    }

    /**
     * accessToken_isExp()
     * 
     * @return bool 
     */
    public function accessToken_isExp()
    {
        return $this->oa_access_token_exp;
    }
}
