<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Core;

/**
 * NukeViet\Core\Encryption
 *
 * @package NukeViet\Core
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class Encryption
{
    private $_ipad;
    private $_opad;
    private $_key;

    /**
     * __construct()
     *
     * @param mixed $key
     */
    public function __construct($key)
    {
        $this->_key = sha1($key);
        if (isset($key[64])) {
            $key = pack('H32', $this->_key);
        }

        if (!isset($key[63])) {
            $key = str_pad($key, 64, chr(0));
        }

        $this->_ipad = substr($key, 0, 64) ^ str_repeat(chr(0x36), 64);
        $this->_opad = substr($key, 0, 64) ^ str_repeat(chr(0x5C), 64);
    }

    /**
     * hash()
     *
     * @param mixed $data
     * @param bool  $is_salt
     * @return string
     */
    public function hash($data, $is_salt = false)
    {
        $inner = pack('H32', sha1($this->_ipad . $data));
        $digest = sha1($this->_opad . $inner);
        if (!$is_salt) {
            return $digest;
        }

        $salt = substr(sha1(microtime(true) . $this->_key . random_bytes(8)), 0, 8);
        $derivedSalt = hash_pbkdf2('sha1', $digest, $salt, 4, 8, true);
        $finalHash = hash('sha1', $digest . $derivedSalt, true);
        $encoded = strtr(base64_encode($finalHash . $derivedSalt), '+/=', '-_,');
        return $encoded;
    }

    /**
     * hash_password()
     *
     * @param string $password
     * @param string $hashprefix
     * @return string
     */
    public function hash_password($password, $hashprefix = '{SSHA}')
    {
        if ($hashprefix == '{SSHA512}') {
            $salt = substr(sha1(microtime() . $this->_key), 0, 4);

            return '{SSHA512}' . base64_encode(hash('sha512', $password . $salt, true) . $salt);
        }
        if ($hashprefix == '{SSHA256}') {
            $salt = substr(sha1(microtime() . $this->_key), 0, 4);

            return '{SSHA256}' . base64_encode(hash('sha256', $password . $salt, true) . $salt);
        }
        if ($hashprefix == '{SSHA}') {
            $salt = substr(sha1(microtime() . $this->_key), 0, 4);

            return '{SSHA}' . base64_encode(sha1($password . $salt, true) . $salt);
        }
        if ($hashprefix == '{SHA}') {
            return '{SHA}' . base64_encode(sha1($password, true));
        }
        if ($hashprefix == '{MD5}') {
            return '{MD5}' . base64_encode(md5($password, true));
        }

        return $this->hash($password);
    }

    /**
     * validate_password()
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function validate_password($password, $hash)
    {
        if (substr($hash, 0, 9) == '{SSHA512}') {
            $salt = substr(base64_decode(substr($hash, 9), true), 64);
            $validate_hash = '{SSHA512}' . base64_encode(hash('sha512', $password . $salt, true) . $salt);
        } elseif (substr($hash, 0, 9) == '{SSHA256}') {
            $salt = substr(base64_decode(substr($hash, 9), true), 32);
            $validate_hash = '{SSHA256}' . base64_encode(hash('sha256', $password . $salt, true) . $salt);
        } elseif (substr($hash, 0, 6) == '{SSHA}') {
            $salt = substr(base64_decode(substr($hash, 6), true), 20);
            $validate_hash = '{SSHA}' . base64_encode(sha1($password . $salt, true) . $salt);
        } elseif (substr($hash, 0, 5) == '{SHA}') {
            $validate_hash = '{SHA}' . base64_encode(sha1($password, true));
        } elseif (substr($hash, 0, 5) == '{MD5}') {
            $validate_hash = '{MD5}' . base64_encode(md5($password, true));
        } else {
            $validate_hash = $this->hash($password);
        }

        return hash_equals($hash, $validate_hash);
    }

    /**
     * encrypt()
     *
     * @param mixed  $data
     * @param string $iv
     * @return string
     */
    public function encrypt($data, $iv = '')
    {
        $iv = empty($iv) ? substr($this->_key, 0, 16) : substr($iv, 0, 16);

        $data = openssl_encrypt($data, 'aes-256-cbc', $this->_key, 0, $iv);

        return strtr($data, '+/=', '-_,');
    }

    /**
     * decrypt()
     *
     * @param mixed  $data
     * @param string $iv
     * @return false|string
     */
    public function decrypt($data, $iv = '')
    {
        $iv = empty($iv) ? substr($this->_key, 0, 16) : substr($iv, 0, 16);

        $data = strtr($data, '-_,', '+/=');

        return openssl_decrypt($data, 'aes-256-cbc', $this->_key, 0, $iv);
    }

    /**
     * encodeJwt()
     * Hàm mã hóa mảng dạng [key1 => value1, key2 => value2]
     * Dùng để truyền qua URL
     *
     * @param array  $payload
     * @param string $secret
     * @return string
     */
    public function encodeJwt($payload, $secret = '')
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode($payload);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $secret, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
    }

    /**
     * decodeJwt()
     *
     * @param mixed $token
     * @return array
     */
    public function decodeJwt($token)
    {
        $token = strtr($token, '-_', '+/');
        $tokenParts = explode('.', $token);
        $tokenHeader = base64_decode($tokenParts[0], true);
        $tokenPayload = base64_decode($tokenParts[1], true);
        $jwtHeader = json_decode($tokenHeader, true);
        $jwtPayload = json_decode($tokenPayload, true);

        return [$jwtHeader, $jwtPayload];
    }
}
