<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

use NukeViet\Http\Http;
use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\SignatureInvalidException;

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

if (!defined('NV_OPENID_ALLOWED') or !in_array('google-identity', $global_config['openid_servers'], true)) {
    nv_htmlOutput('This method to login is not supported');
}

if (!defined('NV_GOOGLE_IDENTITY_CERTS_URL')) {
    define('NV_GOOGLE_IDENTITY_CERTS_URL', 'https://www.googleapis.com/oauth2/v3/certs');
}

/**
 * Lấy bộ khóa công khai (JWKS) của Google để xác thực chữ ký ID token.
 * Khóa của Google được xoay vòng định kỳ nên kết quả được cache theo chỉ thị
 * "Cache-Control: max-age" trong response; khi token mang "kid" chưa có trong
 * cache (vừa xoay khóa) thì gọi với $force = true để fetch lại một lần.
 *
 * @link https://developers.google.com/identity/gsi/web/guides/verify-google-id-token?hl=vi
 * @param bool $force Bỏ qua cache, fetch mới (dùng khi không tìm thấy kid)
 * @return array|false Mảng JWKS dạng ['keys' => [...]] hoặc false nếu thất bại
 */
function nv_google_identity_get_certs(bool $force = false)
{
    global $global_config, $nv_Cache, $module_name;

    $cache_file = 'oauth_google_identity_jwks_' . NV_CACHE_PREFIX . '.cache';

    // Cache tối thiểu 5 phút, tối đa 24 giờ
    $ttl_min = 300;
    $ttl_max = 86400;

    /**
     * 2 lần fetch sang Google phải có thời hạn, kể cả $force
     * tránh kẻ tấn công truyền token có kid ngẫu nhiên ép server gọi Google liên tục
     */
    $refetch_min = 60;

    // Cache lưu dạng envelope {fetched, expires, jwks} để tự quản lý hạn theo Cache-Control
    $cached_envelope = null;
    $cache = $nv_Cache->getItem($module_name, $cache_file, ttl: $ttl_max);
    if ($cache !== false) {
        $envelope = json_decode($cache, true);
        if (is_array($envelope) && !empty($envelope['jwks']['keys'])) {
            $cached_envelope = $envelope;
        }
    }

    if ($cached_envelope !== null) {
        // Cache còn hạn logic (theo Cache-Control) thì dùng luôn
        if (!$force && !empty($cached_envelope['expires']) && $cached_envelope['expires'] > NV_CURRENTTIME) {
            return $cached_envelope['jwks'];
        }
        // Giới hạn tần suất refetch: vừa fetch trong $refetch_min giây qua thì không gọi lại Google
        if ($force && !empty($cached_envelope['fetched']) && $cached_envelope['fetched'] + $refetch_min > NV_CURRENTTIME) {
            return $cached_envelope['jwks'];
        }
    }

    // Fetch mới key từ Google
    $http = new Http($global_config, NV_TEMP_DIR);
    $result = $http->get(NV_GOOGLE_IDENTITY_CERTS_URL, [
        'headers' => ['Accept' => 'application/json'],
        'timeout' => 10
    ]);

    if (!empty(Http::$error) || empty($result['response']['code']) || $result['response']['code'] != 200 || empty($result['body'])) {
        /**
         * Fetch lỗi nhưng còn cache cũ thì vẫn dùng tạm (kể cả quá hạn) để sự cố mạng
         * trong chớp nhoáng không khóa toàn bộ đăng nhập Google
         */
        if ($cached_envelope !== null) {
            return $cached_envelope['jwks'];
        }
        return false;
    }

    $jwks = json_decode($result['body'], true);
    if (!is_array($jwks) || empty($jwks['keys'])) {
        return false;
    }

    // Xác định hạn cache từ "Cache-Control: max-age=..."
    $max_age = 0;
    if (!empty($result['headers']) && is_array($result['headers'])) {
        foreach ($result['headers'] as $h_name => $h_value) {
            if (strtolower($h_name) === 'cache-control') {
                $h_value = is_array($h_value) ? implode(',', $h_value) : $h_value;
                if (preg_match('/max-age\s*=\s*(\d+)/i', $h_value, $m)) {
                    $max_age = (int) $m[1];
                }
                break;
            }
        }
    }
    $max_age = max($ttl_min, min($ttl_max, $max_age ?: $ttl_min));

    $nv_Cache->setItem($module_name, $cache_file, json_encode([
        'fetched' => NV_CURRENTTIME,
        'expires' => NV_CURRENTTIME + $max_age,
        'jwks' => $jwks
    ], NV_JSON_ENCODE), ttl: $ttl_max);

    return $jwks;
}

/**
 * Xác thực $credential chuẩn:
 * - Chữ ký RS256 đối chiếu JWKS công khai của Google
 * - iss thuộc {accounts.google.com, https://accounts.google.com}
 * - aud trùng google_client_id của site
 * - exp/iat/nbf còn hiệu lực do Firebase\JWT tự kiểm tra
 *
 * @link https://developers.google.com/identity/gsi/web/guides/verify-google-id-token?hl=vi
 * @param string $credential Chuỗi JWT nhận từ Google Identity
 * @param string $client_id  google_client_id cấu hình của site
 * @return array|false Payload đã xác thực hoặc false nếu không hợp lệ
 */
function nv_google_identity_verify_token(string $credential, string $client_id)
{
    if ($credential === '' or $client_id === '') {
        return false;
    }

    $jwks = nv_google_identity_get_certs();
    if ($jwks === false) {
        return false;
    }

    // Thời gian sai lệch tối đa giữa server và Google
    JWT::$leeway = 60;

    $decoded = null;
    for ($attempt = 0; $attempt < 2; $attempt++) {
        try {
            // parseKeySet pin thuật toán theo "alg" trong JWK (RS256); mặc định RS256 nếu thiếu
            $keys = JWK::parseKeySet($jwks, 'RS256');
            $decoded = JWT::decode($credential, $keys);
            break;
        } catch (SignatureInvalidException | ExpiredException | BeforeValidException) {
            // Chữ ký sai, hết hạn hoặc chưa hiệu lực thì từ chối
            return false;
        } catch (Throwable) {
            // Thất bại do Google xoay khóa thì fetch lại 1 lần
            if ($attempt === 0) {
                $jwks = nv_google_identity_get_certs(true);
                if ($jwks === false) {
                    return false;
                }
                continue;
            }
            return false;
        }
    }

    if ($decoded === null) {
        return false;
    }

    $payload = (array) $decoded;

    // Giá trị của iss trong mã thông báo nhận dạng bằng accounts.google.com hoặc https://accounts.google.com
    $iss = $payload['iss'] ?? '';
    if ($iss !== 'accounts.google.com' and $iss !== 'https://accounts.google.com') {
        return false;
    }

    // Giá trị của aud trong mã thông báo phải đúng client_id của site
    if (empty($payload['aud']) or !hash_equals($client_id, (string) $payload['aud'])) {
        return false;
    }

    return $payload;
}

if ($nv_Request->isset_request('credential', 'post')) {
    $is_edit = (defined('NV_IS_USER') and $nv_Request->isset_request('g_csrf_token', 'post'));
    if ($is_edit) {
        $csrf_token_cookie = $_COOKIE['g_csrf_token'];
        if (!$csrf_token_cookie) {
            nv_htmlOutput('No CSRF token in Cookie.');
        }
        $csrf_token_body = $nv_Request->get_title('g_csrf_token', 'post', '');
        if (!$csrf_token_body) {
            nv_htmlOutput('No CSRF token in post body.');
        }
        if (!hash_equals($csrf_token_cookie, $csrf_token_body)) {
            nv_htmlOutput('Failed to verify double submit cookie.');
        }
    } else {
        $csrf = $nv_Request->get_title('_csrf', 'post', '');
        if (!csrf_check($csrf, $module_name . '_oauth')) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => 'Failed to verify CSRF. Try reloading the page',
                'is_reload' => 1
            ]);
        }
    }
    $raw_credential = $nv_Request->get_title('credential', 'post', '');

    // Xác thực credential chuẩn khuyến nghị của Google
    $payload = nv_google_identity_verify_token($raw_credential, (string) $global_config['google_client_id']);
    if ($payload === false) {
        if ($is_edit) {
            nv_htmlOutput('Invalid ID token.');
        }
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Invalid ID token.'
        ]);
    }

    // Chuyển credential sang cấu trúc chuẩn
    $credential = [null, $payload];

    if (empty($credential[1]['email_verified'])) {
        if ($is_edit) {
            nv_htmlOutput('Your email is not verified.');
        }
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Your email is not verified.'
        ]);
    }

    $nv_redirect_session = $nv_Request->get_title('nv_redirect_' . $module_data, 'session', '');
    if (!empty($nv_redirect_session) and nv_redirect_decrypt($nv_redirect_session) != '') {
        $nv_redirect = $nv_redirect_session;
    }

    if (!$is_edit) {
        // Case đăng nhập bằng Oauth Google Identity
        $server = 'google-identity';
        $custom_method = nv_apply_hook($module_name, 'find_oauth_google_identity', [$credential]);

        if (is_null($custom_method)) {
            $opid = $crypt->hash($credential[1]['sub']);
            $stmt = $db->prepare('SELECT a.userid AS uid, b.email AS uemail, b.active AS uactive, b.safemode AS safemode
            FROM ' . NV_MOD_TABLE . '_openid a
            INNER JOIN ' . NV_MOD_TABLE . ' b ON a.userid=b.userid
            WHERE a.openid=:openid AND a.opid= :opid');
            $stmt->bindParam(':openid', $server, PDO::PARAM_STR);
            $stmt->bindParam(':opid', $opid, PDO::PARAM_STR);
            $stmt->execute();
            [$user_id, $op_email, $user_active, $safemode] = $stmt->fetch(3);
        } else {
            [$user_id, $op_email, $user_active, $safemode] = $custom_method;
        }

        if ($user_id) {
            if ($safemode == 1) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('safe_deactivate_openidlogin')
                ]);
            }

            if (!$user_active) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('login_no_active')
                ]);
            }

            if (defined('NV_IS_USER_FORUM') or defined('SSO_SERVER')) {
                require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/set_user_login.php';
            } else {
                $query = 'SELECT * FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $user_id;
                $row = $db->query($query)->fetch();
                validUserLog($row, 1, [
                    'id' => $opid,
                    'provider' => $server
                ], 3);
            }

            nv_jsonOutput([
                'redirect' => nv_redirect_decrypt($nv_redirect),
                'status' => 'success',
                'mess' => $nv_Lang->getModule('login_ok')
            ]);
        }
    }

    // Case thêm Oauth vào tài khoản tại trang editinfo/openid
    $attribs = [
        'identity' => $credential[1]['sub'],
        'result' => 'is_res',
        'id' => $credential[1]['sub'],
        'contact/email' => $credential[1]['email'],
        'namePerson/first' => $credential[1]['family_name'],
        'namePerson/last' => $credential[1]['given_name'],
        'namePerson' => $credential[1]['name'],
        'person/gender' => '',
        'server' => 'google-identity',
        'picture_url' => $credential[1]['picture'],
        'picture_mode' => 0, // 0: Remote picture
        'current_mode' => 3
    ];
    /**
     * Khi email là Gmail hoặc đã xác minh và thuộc Workspace Google quản lý
     * thì mới đủ tin cậy, còn lại nếu muốn liên kết với tài khoản có sẵn
     * thì phải nhập mật khẩu của tài khoản để xác thực. Trường hợp tài khoản
     * không có mật khẩu thì từ chối liên kết. Lúc này để liên kết phải đăng nhập tài khoản
     * vào khu vực editinfo/openid, rồi nhấn nút "Thêm" để liên kết với Google Identity.
     */
    $attribs['email_trusted'] = (
        str_ends_with(strtolower((string) $credential[1]['email']), '@gmail.com')
        || (!empty($credential[1]['email_verified']) && !empty($credential[1]['hd']))
    );

    nv_apply_hook($module_name, 'prehandling_oauth_google_identity', [$is_edit, $attribs]);
    $nv_Request->set_Session('openid_attribs', json_encode($attribs, NV_JSON_ENCODE));
    if ($is_edit) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=editinfo/openid&server=google-identity&result=1&t=' . NV_CURRENTTIME);
    } else {
        if (!empty($nv_redirect)) {
            $nv_redirect = '&nv_redirect=' . $nv_redirect;
        }
        $nv_redirect .= '&t=' . NV_CURRENTTIME;

        nv_jsonOutput([
            'status' => 'OK',
            'redirect' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=login&server=google-identity&result=1' . $nv_redirect, true)
        ]);
    }
}

$contents = '<script src="https://accounts.google.com/gsi/client" async defer></script>';
$contents .= '<div style="height:100vh;display:flex;justify-content:center;align-items:center">
<div id="g_id_onload"
    data-client_id="' . $global_config['google_client_id'] . '"
    data-context="use"
    data-login_uri="' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=oauth&server=google-identity', true) . '"
    data-nonce=""
    data-close_on_tap_outside="false"
    data-itp_support="true"
    data-use_fedcm_for_prompt="true">
</div>

<div class="g_id_signin"
     data-type="standard"
     data-shape="rectangular"
     data-theme="outline"
     data-text="continue_with"
     data-size="large"
     data-locale="' . NV_LANG_INTERFACE . '"
     data-logo_alignment="center">
</div>
</div>';

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents, false);
include NV_ROOTDIR . '/includes/footer.php';
