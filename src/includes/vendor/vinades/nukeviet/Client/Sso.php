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
            http_response_code(500);
            trigger_error('Invalid return_url', E_USER_ERROR);
        }
        if (!str_starts_with($return_url, NV_MY_DOMAIN)) {
            $return_url = NV_MY_DOMAIN . $return_url;
        }

        // Reset token encrypted
        $sso_reset = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&sso_reset=1&sso_rcount=' . $reset . '&sso_rtoken=' . md5(NV_CHECK_SESSION . '_sso_reset') . '&nv_redirect=' . nv_redirect_encrypt($return_url);
        $sso_reset = self::encrypt(urlRewriteWithDomain($sso_reset, NV_MY_DOMAIN));

        /** @disregard P1011 */
        return SSO_REGISTER_DOMAIN . (!defined('SSO_REGISTER_LANGSINGLE') ? '/' . NV_LANG_DATA : '') . '/users/login/?sso_redirect=' . self::encrypt(str_replace('&amp;', '&', $return_url)) . '&sso_reset=' . $sso_reset . '&client=' . urlencode(NV_MY_DOMAIN);
    }

    /**
     * @param string $str
     * @return string
     */
    public static function encrypt(string $str): string
    {
        /** @disregard P1011 */
        return strtr(openssl_encrypt($str, 'aes-256-cbc', SSO_REGISTER_SECRET, 0, substr(SSO_REGISTER_SECRET, 0, 16)), '+/=', '-_,');
    }

    public static function decrypt(string $str): string
    {
        /** @disregard P1011 */
        return openssl_decrypt(strtr($str, '-_,', '+/='), 'aes-256-cbc', SSO_REGISTER_SECRET, 0, substr(SSO_REGISTER_SECRET, 0, 16));
    }
}
