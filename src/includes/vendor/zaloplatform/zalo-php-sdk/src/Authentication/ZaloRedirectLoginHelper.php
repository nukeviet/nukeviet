<?php
/**
 * Zalo © 2019
 *
 */

namespace Zalo\Authentication;

use Zalo\Authentication\AccessToken;
use Zalo\Exceptions\ZaloSDKException;
use Zalo\Url\UrlDetectionInterface;
use Zalo\Url\ZaloUrlDetectionHandler;


/**
 * Class ZaloRedirectLoginHelper
 *
 * @package Zalo
 */
class ZaloRedirectLoginHelper
{
    /**
     * @var OAuth2Client The OAuth 2.0 client service.
     */
    protected $oAuth2Client;

    /**
     * @var UrlDetectionInterface The URL detection handler.
     */
    protected $urlDetectionHandler;

    /**
     * @param OAuth2Client $oAuth2Client The OAuth 2.0 client service.
     * @param UrlDetectionInterface|null $urlHandler The URL detection handler.
     */
    public function __construct(OAuth2Client $oAuth2Client, UrlDetectionInterface $urlHandler = null)
    {
        $this->oAuth2Client = $oAuth2Client;
        $this->urlDetectionHandler = $urlHandler ?: new ZaloUrlDetectionHandler();
    }

    /**
     * Returns the URL detection handler.
     *
     * @return UrlDetectionInterface
     */
    public function getUrlDetectionHandler()
    {
        return $this->urlDetectionHandler;
    }

    /**
     * Stores CSRF state and returns a URL to which the user should be sent to in order to continue the login process with Zalo.
     *
     * @param string $redirectUrl The URL Zalo should redirect users to after login.
     * @param string $codeChallenge The code challenge is a Base64-URL-encoded string of the SHA256 hash of the code verifier.
     * @param string $state The CSPRNG-generated CSRF value.
     *
     * @return string
     */
    private function makeUrl($redirectUrl, $codeChallenge, $state)
    {
        return $this->oAuth2Client->getAuthorizationUrlByUser($redirectUrl, $codeChallenge, $state);
    }

    private function makeUrlByOA($redirectUrl, $codeChallenge, $state)
    {
        return $this->oAuth2Client->getAuthorizationUrlByOA($redirectUrl, $codeChallenge, $state);
    }

    /**
     * Returns the URL to send the user in order to login to Zalo.
     *
     * @param string $redirectUrl The URL Zalo should redirect users to after login.
     * @param string $codeChallenge The code challenge is a Base64-URL-encoded string of the SHA256 hash of the code verifier.
     * @param string $state The CSPRNG-generated CSRF value.
     *
     * @return string
     */
    public function getLoginUrl($redirectUrl, $codeChallenge, $state)
    {
        return $this->makeUrl($redirectUrl, $codeChallenge, $state);
    }

    /**
     * Returns the URL to send the oa admin in order to login to Zalo.
     *
     * @param $redirectUrl
     * @param string $codeChallenge The code challenge is a Base64-URL-encoded string of the SHA256 hash of the code verifier.
     * @param string $state The CSPRNG-generated CSRF value.
     * @return string
     */
    public function getLoginUrlByOA($redirectUrl, $codeChallenge, $state)
    {
        return $this->makeUrlByOA($redirectUrl, $codeChallenge, $state);
    }

    /**
     * Returns the URL to send the oa admin in order to login to Zalo.
     *
     * @param $redirectUrl
     * @param string $codeChallenge The code challenge is a Base64-URL-encoded string of the SHA256 hash of the code verifier.
     * @param string $state The CSPRNG-generated CSRF value.
     * @return string
     *
     * @deprecated getLoginUrlByPage() has been renamed to getLoginUrlByOA()
     */
    public function getLoginUrlByPage($redirectUrl, $codeChallenge, $state)
    {
        return $this->getLoginUrlByOA($redirectUrl, $codeChallenge, $state);
    }

    /**
     * Takes a valid code from a login redirect, and returns an ZaloToken entity.
     *
     * @param string $codeVerifier
     *
     * @return ZaloToken|null
     *
     * @throws ZaloSDKException
     */
    public function getZaloToken($codeVerifier)
    {
        if (!$code = $this->getCode()) {
            throw new ZaloSDKException('OAuth code mismatch.');
        }

        return $this->oAuth2Client->getZaloTokenFromCodeByUser($code, $codeVerifier);
    }

    /**
     * Takes a valid code from a login redirect, and returns an ZaloToken entity.
     *
     * @param string $codeVerifier
     *
     * @return ZaloToken|null
     *
     * @throws ZaloSDKException
     */
    public function getZaloTokenByOA($codeVerifier)
    {
        if (!$code = $this->getCode()) {
            throw new ZaloSDKException('OAuth code mismatch.');
        }

        return $this->oAuth2Client->getZaloTokenFromCodeByOA($code, $codeVerifier);
    }

    /**
     * Return the code.
     *
     * @return string|null
     */
    protected function getCode()
    {
        return $this->getInput('code');
    }

    /**
     * Return the code challenge.
     *
     * @return string|null
     */
    protected function getCodeChallenge()
    {
        return $this->getInput('code_challenge');
    }

    /**
     * Return the state.
     */
    protected function getState()
    {
        return $this->getInput('state');
    }

    /**
     * Returns a value from a GET param.
     *
     * @param string $key
     *
     * @return string|null
     */
    private function getInput($key)
    {
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }
}
