<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Client;

use NukeViet\Http\HttpException;

/**
 * NukeViet\Client\Sso
 *
 * @package NukeViet\Client
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class Sso
{
    /**
     * Lấy đường dẫn đăng nhập SSO
     *
     * @param string $return_url url đã được rewrite, chưa mã hóa, có thể bắt đầu bằng domain hoặc không
     * @param int $reset
     * @return string
     */
    public static function getLoginUrl(string $return_url, int $reset = 0): string
    {
        $return_url = nv_url_rewrite($return_url, true);
        if (!str_starts_with($return_url, NV_MY_DOMAIN) and preg_match('/^(https?:\/\/|\/\/)/i', $return_url)) {
            throw new HttpException('Invalid return_url', 500);
        }
        if (!str_starts_with($return_url, NV_MY_DOMAIN)) {
            $return_url = NV_MY_DOMAIN . $return_url;
        }

        // Reset token encrypted
        $sso_reset = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&sso_reset=1&sso_rcount=' . $reset . '&sso_rtoken=' . md5(NV_CHECK_SESSION . '_sso_reset') . '&nv_redirect=' . nv_redirect_encrypt($return_url);
        $sso_reset = self::encrypt(urlRewriteWithDomain($sso_reset, NV_MY_DOMAIN));

        /** @disregard P1011 */
        // phpcs:ignore
        return SSO_REGISTER_DOMAIN . (!defined('SSO_REGISTER_LANGSINGLE') ? '/' . NV_LANG_DATA : '') . '/users/login/?sso_redirect=' . self::encrypt(str_replace('&amp;', '&', $return_url)) . '&sso_reset=' . $sso_reset . '&client=' . urlencode(NV_MY_DOMAIN);
    }

    /**
     * Mã hóa chuỗi để truyền qua URL giữa các site SSO.
     *
     * @param string $str
     * @return string Chuỗi đã mã hóa, hoặc '' nếu mã hóa thất bại
     */
    public static function encrypt(string $str): string
    {
        /** @disregard P1011 */
        // phpcs:ignore
        $key = SSO_REGISTER_SECRET;

        $iv = random_bytes(16);
        $ciphertext = openssl_encrypt($str, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        if ($ciphertext === false) {
            return '';
        }

        $hmac = hash_hmac('sha256', $iv . $ciphertext, $key, true);
        $packed = $hmac . $iv . $ciphertext;

        return strtr(base64_encode($packed), '+/=', '-_,');
    }

    /**
     * Giải mã chuỗi nhận từ URL SSO.
     *
     * @param string $str
     * @return string Chuỗi gốc, hoặc '' nếu giải mã/xác thực thất bại
     */
    public static function decrypt(string $str): string
    {
        /** @disregard P1011 */
        // phpcs:ignore
        $key = SSO_REGISTER_SECRET;

        $packed = base64_decode(strtr($str, '-_,', '+/='), true);
        if ($packed === false || strlen($packed) < 48) {
            return '';
        }

        $hmac = substr($packed, 0, 32);
        $iv = substr($packed, 32, 16);
        $ciphertext = substr($packed, 48);

        $calculated_hmac = hash_hmac('sha256', $iv . $ciphertext, $key, true);
        if (!hash_equals($hmac, $calculated_hmac)) {
            return '';
        }

        $plaintext = openssl_decrypt($ciphertext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        return $plaintext === false ? '' : $plaintext;
    }
}
