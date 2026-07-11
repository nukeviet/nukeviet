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
     * Tạo mã băm HMAC-SHA1 cho dữ liệu đầu vào, cùng dữ liệu đầu vào sẽ
     * tạo ra dùng dữ liệu đầu ra. Phụ thuộc vào sitekey của hệ thống. Sitekey mất thì
     * dữ liệu cũ sẽ không thể giải mã được nữa. Do đó cần lưu trữ sitekey cẩn thận.
     *
     * VUI LÒNG KHÔNG SỬ DỤNG hàm này liên quan đến mật khẩu.
     *
     * @param mixed $data
     * @return string
     */
    public function hash($data)
    {
        $inner = pack('H32', sha1($this->_ipad . $data));
        return sha1($this->_opad . $inner);
    }

    /**
     * hash_password()
     *
     * WARNING: Vẫn hỗ trợ khởi tạo mã băm MD5 và SHA1 đã lỗi thời.
     * @deprecated Khuyến nghị không sử dụng cho các hệ thống tạo mới. Thay thế bằng password_hash() gốc của PHP.
     * @todo Thêm cấu hình vô hiệu hóa việc tạo mới mật khẩu bằng thuật toán cũ, ép buộc dùng SSHA512 hoặc thuật toán an toàn hơn.
     *
     * @param string $password
     * @param string $hashprefix
     * @return string
     */
    public function hash_password($password, $hashprefix = '{CRYPT}')
    {
        if ($hashprefix == '{CRYPT}') {
            return '{CRYPT}' . password_hash($password, PASSWORD_BCRYPT);
        }
        if ($hashprefix == '{SSHA512}') {
            $salt = random_bytes(16);

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
     * Xác thực mật khẩu hỗ trợ các chuẩn băm cũ (MD5, SHA, SSHA).
     * @deprecated Quá trình kiểm tra phụ thuộc vào các thuật toán băm yếu.
     * @todo Thêm cơ chế "needs_rehash" (tương tự password_needs_rehash của PHP) để tự động nâng cấp mật khẩu cũ (MD5/SHA) sang chuẩn mới khi người dùng đăng nhập thành công.
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function validate_password($password, $hash)
    {
        if (substr($hash, 0, 7) == '{CRYPT}') {
            return password_verify($password, substr($hash, 7));
        } elseif (substr($hash, 0, 9) == '{SSHA512}') {
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
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256'], NV_JSON_ENCODE);
        $payload = json_encode($payload, NV_JSON_ENCODE);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $secret, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
    }

    /**
     * decodeJwt()
     *
     * WARNING: Hàm này chỉ thực hiện giải mã (decode) chuỗi JWT để trích xuất dữ liệu (header và payload) mà KHÔNG HỀ XÁC THỰC (verify) chữ ký.
     * TUYỆT ĐỐI KHÔNG dùng hàm này để kiểm tra quyền hạn hay tính hợp lệ của token do kẻ tấn công có thể dễ dàng làm giả payload.
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
