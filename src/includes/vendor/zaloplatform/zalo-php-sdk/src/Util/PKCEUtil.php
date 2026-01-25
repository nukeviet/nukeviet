<?php

namespace Zalo\Util;

/**
 * Class PKCEUtil
 *
 * @package Zalo
 */
class PKCEUtil
{
    /**
     * generates code verifier
     *
     * @return string
     */
    public static function genCodeVerifier()
    {
        $random = bin2hex(openssl_random_pseudo_bytes(32));
        return self::base64url_encode(pack('H*', $random));
    }

    /**
     * generates code challenge
     *
     * @param $codeVerifier
     * @return string
     */
    public static function genCodeChallenge($codeVerifier)
    {
        if (!isset($codeVerifier)) {
            return '';
        }

        return self::base64url_encode(pack('H*', hash('sha256', $codeVerifier)));
    }

    private static function base64url_encode($plainText)
    {
        $base64 = base64_encode($plainText);
        $base64 = trim($base64, "=");
        return strtr($base64, '+/', '-_');
    }
}