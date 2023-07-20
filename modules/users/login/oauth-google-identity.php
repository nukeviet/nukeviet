<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

if (!defined('NV_OPENID_ALLOWED') or !in_array('google-identity', $global_config['openid_servers'], true)) {
    exit('This method to login is not supported');
}

if ($nv_Request->isset_request('credential', 'post')) {
    $is_edit = (defined('NV_IS_USER') and $nv_Request->isset_request('g_csrf_token', 'post'));
    if ($is_edit) {
        $csrf_token_cookie = $_COOKIE['g_csrf_token'];
        if (!$csrf_token_cookie) {
            exit('No CSRF token in Cookie.');
        }
        $csrf_token_body = $nv_Request->get_title('g_csrf_token', 'post', '');
        if (!$csrf_token_body) {
            exit('No CSRF token in post body.');
        }
        if (!hash_equals($csrf_token_cookie, $csrf_token_body)) {
            exit('Failed to verify double submit cookie.');
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
    $credential = $nv_Request->get_title('credential', 'post', '');
    $credential = $crypt->decodeJwt($credential);
    if (!$credential) {
        if ($is_edit) {
            exit('Invalid ID token.');
        }
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Invalid ID token.'
        ]);
    }

    if (empty($credential[1]['aud']) or strcmp($credential[1]['aud'], $global_config['google_client_id']) !== 0) {
        if ($is_edit) {
            exit('Invalid ID token.');
        }
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Invalid ID token.'
        ]);
    }

    if (empty($credential[1]['email_verified'])) {
        if ($is_edit) {
            exit('Your email is not verified.');
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

    nv_apply_hook($module_name, 'prehandling_oauth_google_identity', [$is_edit, $attribs]);
    $nv_Request->set_Session('openid_attribs', serialize($attribs));
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
    data-itp_support="true">
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
