<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

use NukeViet\Api\Exception;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * @param mixed $a
 */
function pr($a)
{
    exit('<pre><code>' . htmlspecialchars(print_r($a, true)) . '</code></pre>');
}

/**
 * nv_object2array()
 *
 * @param mixed $a
 * @return mixed
 */
function nv_object2array($a)
{
    if (is_object($a)) {
        $a = get_object_vars($a);
    }

    return is_array($a) ? array_map(__FUNCTION__, $a) : $a;
}

/**
 * nv_getenv()
 *
 * @param mixed $a
 * @return string
 */
function nv_getenv($a)
{
    return NukeViet\Site::getEnv($a);
}

/**
 * nv_preg_quote()
 *
 * @param string $a
 * @return string
 */
function nv_preg_quote($a)
{
    return preg_quote($a, '/');
}

/**
 * nv_array_diff_assoc()
 *
 * @param array $array1
 * @param array $array2
 * @return array
 */
function nv_array_diff_assoc($array1, $array2)
{
    $difference = [];
    foreach ($array1 as $key => $value) {
        if (is_array($value)) {
            if (!isset($array2[$key]) or !is_array($array2[$key])) {
                $difference[$key] = $value;
            } else {
                $new_diff = nv_array_diff_assoc($value, $array2[$key]);
                if (!empty($new_diff)) {
                    $difference[$key] = $new_diff;
                }
            }
        } elseif (!array_key_exists($key, $array2) or $array2[$key] !== $value) {
            $difference[$key] = $value;
        }
    }

    return $difference;
}

/**
 * nv_is_myreferer()
 *
 * @param string $referer
 * @return int
 */
function nv_is_myreferer($referer = '')
{
    if (empty($referer)) {
        $referer = urldecode(nv_getenv('HTTP_REFERER'));
    }
    if (empty($referer)) {
        return 2;
    }

    $referer = preg_replace([
        '/^[a-zA-Z]+\:\/\/([w]+\.)?/',
        '/^[w]+\./'
    ], '', $referer);

    if (preg_match('/^' . nv_preg_quote(NV_SERVER_NAME) . '/', $referer)) {
        return 1;
    }

    return 0;
}

/**
 * nv_is_blocker_proxy()
 *
 * @param string $is_proxy
 * @param int    $proxy_blocker
 * @return bool
 */
function nv_is_blocker_proxy($is_proxy, $proxy_blocker)
{
    if ($proxy_blocker == 1 and $is_proxy == 'Strong') {
        return true;
    }
    if ($proxy_blocker == 2 and ($is_proxy == 'Strong' or $is_proxy == 'Mild')) {
        return true;
    }

    return (bool) ($proxy_blocker == 3 and $is_proxy != 'No');
}

/**
 * nv_is_banIp()
 *
 * @param string $ip
 * @return bool
 */
function nv_is_banIp($ip)
{
    global $ips;

    $array_banip_site = $array_banip_admin = [];

    if (file_exists(NV_ROOTDIR . '/' . NV_DATADIR . '/banip.php')) {
        include NV_ROOTDIR . '/' . NV_DATADIR . '/banip.php';
    }

    $banIp = (defined('NV_ADMIN')) ? $array_banip_admin : $array_banip_site;
    if (empty($banIp)) {
        return false;
    }

    foreach ($banIp as $e => $f) {
        if ($f['begintime'] < NV_CURRENTTIME and ($f['endtime'] == 0 or $f['endtime'] > NV_CURRENTTIME) and ((empty($f['ip6']) and preg_replace($f['mask'], '', $ip) == preg_replace($f['mask'], '', $e)) or (!empty($f['ip6']) and $ips->checkIp6($ip, $f['mask']) === true))) {
            return true;
        }
    }

    return false;
}

/**
 * nv_checkagent()
 *
 * @param string $a
 * @return string
 */
function nv_checkagent($a)
{
    $a = htmlspecialchars(substr($a, 0, 255));
    $a = str_replace([
        ', ',
        '<'
    ], [
        '-',
        '('
    ], $a);

    return (!empty($a) and $a != '-') ? $a : 'none';
}

/**
 * nv_convertfromBytes()
 *
 * @param int $size
 * @return string
 */
function nv_convertfromBytes($size)
{
    if ($size <= 0) {
        return '0 bytes';
    }
    if ($size == 1) {
        return '1 byte';
    }
    if ($size < 1024) {
        return $size . ' bytes';
    }

    $i = 0;
    $iec = [
        'bytes',
        'KB',
        'MB',
        'GB',
        'TB',
        'PB',
        'EB',
        'ZB',
        'YB'
    ];

    while (($size / 1024) > 1) {
        $size /= 1024;
        ++$i;
    }

    return nv_number_format($size) . ' ' . $iec[$i];
}

/**
 * nv_convertfromSec()
 *
 * @param int $sec
 * @return string
 */
function nv_convertfromSec($sec = 0)
{
    global $nv_Lang;

    $sec = (int) $sec;
    $min = 60;
    $hour = 3600;
    $day = 86400;
    $year = 31536000;

    if ($sec == 0) {
        return '';
    }
    if ($sec < $min) {
        return plural($sec, $nv_Lang->getGlobal('plural_sec'));
    }
    if ($sec < $hour) {
        return trim(plural(floor($sec / $min), $nv_Lang->getGlobal('plural_min')) . (($sd = $sec % $min) ? ' ' . nv_convertfromSec($sd) : ''));
    }
    if ($sec < $day) {
        return trim(plural(floor($sec / $hour), $nv_Lang->getGlobal('plural_hour')) . (($sd = $sec % $hour) ? ' ' . nv_convertfromSec($sd) : ''));
    }
    if ($sec < $year) {
        return trim(plural(floor($sec / $day), $nv_Lang->getGlobal('plural_day')) . (($sd = $sec % $day) ? ' ' . nv_convertfromSec($sd) : ''));
    }

    return trim(plural(floor($sec / $year), $nv_Lang->getGlobal('plural_year')) . (($sd = $sec % $year) ? ' ' . nv_convertfromSec($sd) : ''));
}

/**
 * nv_converttoBytes()
 *
 * @param string $string
 * @return false|float|string
 */
function nv_converttoBytes($string)
{
    if (preg_match('/^([0-9\.]+)[ ]*([b|k|m|g|t|p|e|z|y]*)/i', $string, $matches)) {
        if (empty($matches[2])) {
            return $matches[1];
        }

        $suffixes = [
            'B' => 0,
            'K' => 1,
            'M' => 2,
            'G' => 3,
            'T' => 4,
            'P' => 5,
            'E' => 6,
            'Z' => 7,
            'Y' => 8
        ];

        if (isset($suffixes[strtoupper($matches[2])])) {
            return round($matches[1] * pow(1024, $suffixes[strtoupper($matches[2])]));
        }
    }

    return false;
}

/**
 * nv_base64_encode()
 *
 * @param mixed $input
 * @return string
 */
function nv_base64_encode($input)
{
    return strtr(base64_encode($input), '+/=', '-_,');
}

/**
 * nv_base64_decode()
 *
 * @param mixed $input
 * @return false|string
 */
function nv_base64_decode($input)
{
    return base64_decode(strtr($input, '-_,', '+/='), true);
}

/**
 * nv_function_exists()
 *
 * @param string $funcName
 * @return bool
 */
function nv_function_exists($funcName)
{
    global $sys_info;

    return function_exists($funcName) and !in_array($funcName, $sys_info['disable_functions'], true);
}

/**
 * nv_class_exists()
 *
 * @param string $clName
 * @param bool   $autoload
 * @return bool
 */
function nv_class_exists($clName, $autoload = true)
{
    global $sys_info;

    return class_exists($clName, $autoload) and !in_array($clName, $sys_info['disable_classes'], true);
}

/**
 * nv_md5safe()
 *
 * @param string $username
 * @return string
 */
function nv_md5safe($username)
{
    return md5(nv_strtolower($username));
}

/**
 * nv_check_valid_login()
 *
 * @param string $login
 * @param int    $max
 * @param int    $min
 * @return string
 */
function nv_check_valid_login($login, $max, $min)
{
    global $nv_Lang, $global_config;

    $login = trim(strip_tags($login));

    if (empty($login)) {
        return $nv_Lang->getGlobal('username_empty');
    }
    if (isset($login[$max])) {
        return $nv_Lang->getGlobal('usernamelong', $max);
    }
    if (!isset($login[$min - 1])) {
        return $nv_Lang->getGlobal('usernameadjective', $min);
    }

    $type = $global_config['nv_unick_type'];
    switch ($type) {
        case 1:
            $pattern = '/^[0-9]+$/';
            break;
        case 2:
            $pattern = '/^[0-9a-z]+$/i';
            break;
        case 3:
            $pattern = '/^[0-9a-z]+[0-9a-z\-\_\\s]+[0-9a-z]+$/i';
            break;
        case 4:
            $_login = str_replace('@', '', $login);

            return $login != strip_punctuation($_login) ? $nv_Lang->getGlobal('unick_type_' . $type) : '';
        default:
            return '';
    }
    if (!preg_match($pattern, $login)) {
        return $nv_Lang->getGlobal('unick_type_' . $type);
    }

    return '';
}

/**
 * nv_check_valid_pass()
 *
 * @param string $pass
 * @param int    $max
 * @param int    $min
 * @return string
 */
function nv_check_valid_pass($pass, $max, $min)
{
    global $nv_Lang, $db_config, $db, $global_config;

    $pass = trim(strip_tags($pass));

    if (empty($pass)) {
        return $nv_Lang->getGlobal('password_empty');
    }
    if (isset($pass[$max])) {
        return $nv_Lang->getGlobal('passwordlong', $max);
    }
    if (!isset($pass[$min - 1])) {
        return $nv_Lang->getGlobal('passwordadjective', $min);
    }

    $type = $global_config['nv_upass_type'];
    if ($type == 1) {
        if (!(preg_match('#[a-z]#ui', $pass) and preg_match('#[0-9]#u', $pass))) {
            return $nv_Lang->getGlobal('upass_type_' . $type);
        }
    } elseif ($type == 3) {
        if (!(preg_match('#[A-Z]#u', $pass) and preg_match('#[0-9]#u', $pass))) {
            return $nv_Lang->getGlobal('upass_type_' . $type);
        }
    } elseif ($type == 2) {
        if (!(preg_match('#[^A-Za-z0-9]#u', $pass) and preg_match('#[a-z]#ui', $pass) and preg_match('#[0-9]#u', $pass))) {
            return $nv_Lang->getGlobal('upass_type_' . $type);
        }
    } elseif ($type == 4) {
        if (!(preg_match('#[^A-Za-z0-9]#u', $pass) and preg_match('#[A-Z]#u', $pass) and preg_match('#[0-9]#u', $pass))) {
            return $nv_Lang->getGlobal('upass_type_' . $type);
        }
    }

    $password_simple = $db->query('SELECT content FROM ' . NV_USERS_GLOBALTABLE . "_config WHERE config='password_simple'")->fetchColumn();
    $password_simple = explode('|', $password_simple);
    if (in_array($pass, $password_simple, true)) {
        return $nv_Lang->getGlobal('upass_type_simple');
    }

    return '';
}

/**
 * nv_check_valid_email()
 *
 * Kiểm tra email có hợp lệ hay không
 * Nếu $return = true thì trả về email đã được hợp chuẩn
 *
 * @since 4.3.08
 *
 * @param string $mail
 * @param bool   $return
 * @return mixed
 */
function nv_check_valid_email($mail, $return = false)
{
    global $nv_Lang, $global_config;

    if (empty($mail)) {
        return $return ? [
            $nv_Lang->getGlobal('email_empty'),
            $mail
        ] : $nv_Lang->getGlobal('email_empty');
    }

    if ($return) {
        $mail = nv_strtolower(strip_tags(trim($mail)));
    }

    // Email quy định ký tự @ xuất hiện 1 lần duy nhất
    if (substr_count($mail, '@') !== 1) {
        return $return ? [
            $nv_Lang->getGlobal('email_incorrect'),
            $mail
        ] : $nv_Lang->getGlobal('email_incorrect');
    }

    // Cắt email ra làm hai phần để kiểm tra
    $_mail = explode('@', $mail);
    $_mail_user = $_mail[0];
    $_mail_domain = nv_check_domain($_mail[1]);

    if (empty($_mail_domain)) {
        return $return ? [
            $nv_Lang->getGlobal('email_incorrect'),
            $mail
        ] : $nv_Lang->getGlobal('email_incorrect');
    }

    // Chuyển lại email từ Unicode domain thành IDNA ASCII
    $mail = $_mail_user . '@' . $_mail_domain;

    if (function_exists('filter_var') and filter_var($mail, FILTER_VALIDATE_EMAIL) === false) {
        return $return ? [
            $nv_Lang->getGlobal('email_incorrect'),
            $mail
        ] : $nv_Lang->getGlobal('email_incorrect');
    }

    if (!preg_match($global_config['check_email'], $mail)) {
        return $return ? [
            $nv_Lang->getGlobal('email_incorrect'),
            $mail
        ] : $nv_Lang->getGlobal('email_incorrect');
    }

    if (!preg_match('/\.([a-z0-9\-]+)$/', $mail)) {
        return $return ? [
            $nv_Lang->getGlobal('email_incorrect'),
            $mail
        ] : $nv_Lang->getGlobal('email_incorrect');
    }

    return $return ? [
        '',
        $mail
    ] : '';
}

/**
 * nv_capcha_txt()
 *
 * @param string $seccode
 * @param string $type
 * @return bool
 */
function nv_capcha_txt($seccode, $type = 'captcha')
{
    global $global_config, $nv_Request, $client_info, $crypt;

    if ($type == 'recaptcha' || $type == 'turnstile') {
        $seckey = $type == 'recaptcha' ? $global_config['recaptcha_secretkey'] : $global_config['turnstile_secretkey'];
        $check_endpoint = $type == 'recaptcha' ? 'https://www.google.com/recaptcha/api/siteverify' : 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
        if (!empty($seckey)) {
            $NV_Http = new NukeViet\Http\Http($global_config, NV_TEMP_DIR);
            $request = [
                'secret' => $crypt->decrypt($seckey),
                'response' => $seccode,
                'remoteip' => $client_info['ip']
            ];
            $args = [
                'headers' => [
                    'Referer' => NV_MY_DOMAIN
                ],
                'body' => $request,
                'httpversion' => '1.1'
            ];
            $array = $NV_Http->post($check_endpoint, $args);
            if (is_array($array) and !empty($array['body'])) {
                $jsonRes = (array) json_decode($array['body'], true);
                if (isset($jsonRes['success']) and ((bool) $jsonRes['success']) === true) {
                    return true;
                }
            }
        }

        return false;
    }

    mt_srand(microtime(true) * 1000000);
    $maxran = 1000000;
    $random = random_int(0, $maxran);

    $seccode = strtoupper($seccode);
    $random_num = $nv_Request->get_string('random_num', 'session', 0);
    $datekey = date('F j');
    $rcode = strtoupper(md5(NV_USER_AGENT . $global_config['sitekey'] . $random_num . $datekey));

    $nv_Request->set_Session('random_num', $random);

    return preg_match('/^[a-zA-Z0-9]{' . NV_GFX_NUM . '}$/', $seccode) and $seccode == substr($rcode, 2, NV_GFX_NUM);
}

/**
 * nv_genpass()
 *
 * @param int $length
 * @param int $type
 * @return string
 */
function nv_genpass($length = 8, $type = 0)
{
    $array_chars = [];
    $array_chars[0] = 'abcdefghijklmnopqrstuvwxyz';
    $array_chars[1] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $array_chars[2] = '0123456789';
    $array_chars[3] = '-=~!@#$%^&*()_+,./<>?;:[]{}\|';

    $_arr_m = [];
    $_arr_m[] = 0; // Chữ
    $_arr_m[] = 2; // 1. Số
    $_arr_m[] = ($type == 2 or $type == 4) ? 3 : random_int(0, 2); // 2. Đặc biệt
    $_arr_m[] = ($type == 3 or $type == 4) ? 1 : random_int(0, 2); // 3. HOA

    $length -= 4;
    for ($k = 0; $k < $length; ++$k) {
        $_arr_m[] = ($type == 2 or $type == 4) ? random_int(0, 3) : random_int(0, 2);
    }

    $pass = '';
    foreach ($_arr_m as $m) {
        $chars = $array_chars[$m];
        $max = strlen($chars) - 1;
        $pass .= $chars[random_int(0, $max)];
    }

    return $pass;
}

/**
 * nv_EncodeEmail()
 *
 * @param string $strEmail
 * @param string $strDisplay
 * @param bool   $blnCreateLink
 * @return string
 */
function nv_EncodeEmail($strEmail, $strDisplay = '', $blnCreateLink = true)
{
    $strMailto = '&#109;&#097;&#105;&#108;&#116;&#111;&#058;';
    $strEncodedEmail = '';
    $strlen = strlen($strEmail);

    for ($i = 0; $i < $strlen; ++$i) {
        $strEncodedEmail .= '&#' . ord(substr($strEmail, $i)) . ';';
    }

    $strDisplay = trim($strDisplay);
    $strDisplay = !empty($strDisplay) ? $strDisplay : $strEncodedEmail;

    if ($blnCreateLink) {
        return '<a href="' . $strMailto . $strEncodedEmail . '">' . $strDisplay . '</a>';
    }

    return $strDisplay;
}

/**
 * nv_user_groups()
 *
 * @param mixed $in_groups
 * @param bool  $res_2step
 * @param array $manual_groups
 * @return array
 */
function nv_user_groups($in_groups, $res_2step = false, $manual_groups = [])
{
    global $nv_Cache, $db, $global_config;

    $_groups = [];
    $_2step_require = false;

    if (!empty($in_groups) or !empty($manual_groups)) {
        $query = 'SELECT g.group_id, d.title, g.require_2step_admin, g.require_2step_site, g.exp_time FROM ' . NV_GROUPS_GLOBALTABLE . ' AS g LEFT JOIN ' . NV_GROUPSDETAIL_GLOBALTABLE . " d ON ( g.group_id = d.group_id AND d.lang='" . NV_LANG_DATA . "' ) WHERE g.act=1 AND (g.idsite = " . $global_config['idsite'] . ' OR (g.idsite =0 AND g.siteus = 1)) ORDER BY g.idsite, g.weight';
        $list = $nv_Cache->db($query, '', 'users');
        if (!empty($list)) {
            $reload = [];
            $in_groups = explode(',', $in_groups);
            $in_groups = array_map('intval', $in_groups);
            if (!empty($manual_groups)) {
                $in_groups = array_unique(array_merge_recursive($in_groups, $manual_groups));
            }
            for ($i = 0, $count = count($list); $i < $count; ++$i) {
                if ($list[$i]['exp_time'] != 0 and $list[$i]['exp_time'] <= NV_CURRENTTIME) {
                    $reload[] = $list[$i]['group_id'];
                } elseif (in_array((int) $list[$i]['group_id'], $in_groups, true)) {
                    $_groups[] = $list[$i]['group_id'];
                    if (defined('NV_ADMIN')) {
                        if (!empty($list[$i]['require_2step_admin'])) {
                            $_2step_require = true;
                        }
                    } elseif (!empty($list[$i]['require_2step_site'])) {
                        $_2step_require = true;
                    }
                }
            }

            if ($reload) {
                $db->query('UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET act=0 WHERE group_id IN (' . implode(',', $reload) . ')');
                $nv_Cache->delMod('users');
            }
        }
    }

    if ($res_2step) {
        return [
            $_groups,
            $_2step_require
        ];
    }

    return $_groups;
}

/**
 * nv_user_in_groups()
 *
 * @param string $groups_view
 * @return bool
 */
function nv_user_in_groups($groups_view)
{
    $groups_view = explode(',', $groups_view);
    $groups_view = array_map('intval', $groups_view);
    if (in_array(6, $groups_view, true)) {
        // All
        return true;
    }
    if (defined('NV_IS_USER') or defined('NV_IS_ADMIN')) {
        global $user_info, $admin_info;

        $in_groups = defined('NV_IS_ADMIN') ? $admin_info['in_groups'] : $user_info['in_groups'];

        if (in_array(4, $groups_view, true) and (empty($in_groups) or !in_array(7, $in_groups, true))) {
            // User with no group or not in new users groups
            return true;
        }
        // Check group
        if (empty($in_groups)) {
            return false;
        }

        return array_intersect($in_groups, $groups_view) != [];
    }

    return (bool) (in_array(5, $groups_view, true));
}

/**
 * Thêm thành viên vào nhóm
 *
 * @param int    $group_id
 * @param int    $userid
 * @param int    $approved
 * @param string $mod_data
 * @return bool
 */
function nv_groups_add_user($group_id, $userid, $approved = 1, $mod_data = 'users')
{
    global $db, $db_config, $global_config;

    $group_id = (int) $group_id;
    $userid = (int) $userid;

    $_mod_table = ($mod_data == 'users') ? NV_USERS_GLOBALTABLE : $db_config['prefix'] . '_' . $mod_data;
    $query = $db->query('SELECT COUNT(*) FROM ' . $_mod_table . ' WHERE userid=' . $userid);
    if (!$query->fetchColumn()) {
        return false;
    }

    try {
        $db->query('INSERT INTO ' . $_mod_table . '_groups_users (
            group_id, userid, approved, data, time_requested, time_approved
        ) VALUES (
            ' . $group_id . ', ' . $userid . ', ' . $approved . ", '" . $global_config['idsite'] . "',
            " . NV_CURRENTTIME . ', ' . ($approved ? NV_CURRENTTIME : 0) . '
        )');
        if ($approved) {
            $db->query('UPDATE ' . $_mod_table . '_groups SET numbers = numbers+1 WHERE group_id=' . $group_id);
        }
    } catch (Throwable $e) {
        if ($group_id > 3) {
            return false;
        }

        $data = $db->query('SELECT data FROM ' . $_mod_table . '_groups_users WHERE group_id=' . $group_id . ' AND userid=' . $userid)->fetchColumn();
        $data = ($data != '') ? explode(',', $data) : [];
        $data[] = $global_config['idsite'];
        $data = implode(',', array_unique(array_map('intval', $data)));
        $db->query('UPDATE ' . $_mod_table . "_groups_users SET data = '" . $data . "' WHERE group_id=" . $group_id . ' AND userid=' . $userid);
    }

    // Cập nhật lại danh sách nhóm cho user
    if ($approved) {
        $sql = "SELECT group_id FROM " . $_mod_table . "_groups_users WHERE userid=" . $userid . " AND approved=1";
        $in_groups = $db->query($sql)->fetchAll(PDO::FETCH_COLUMN);
        $in_groups = array_map('intval', $in_groups);

        // Loại bỏ nhóm mới nếu thêm quản trị hoặc thành viên chính thức
        if (in_array($group_id, [1, 2, 3, 4])) {
            $in_groups = array_diff($in_groups, [7]);
        }
        // Luôn luôn bổ sung nhóm thành viên chính thức nếu không phải thành viên mới
        if (!in_array(7, $in_groups)) {
            $in_groups[] = 4;
        }

        $in_groups = array_unique($in_groups);
        $in_groups = empty($in_groups) ? '' : implode(',', $in_groups);

        $db->query('UPDATE ' . $_mod_table . ' SET in_groups = ' . $db->quote($in_groups) . ' WHERE userid=' . $userid);
    }

    return true;
}

/**
 * Loại bỏ thành viên khỏi nhóm
 *
 * @param int    $group_id
 * @param int    $userid
 * @param string $mod_data
 * @return bool
 */
function nv_groups_del_user($group_id, $userid, $mod_data = 'users')
{
    global $db, $db_config, $global_config;

    $group_id = (int) $group_id;
    $userid = (int) $userid;

    $_mod_table = ($mod_data == 'users') ? NV_USERS_GLOBALTABLE : $db_config['prefix'] . '_' . $mod_data;
    $row = $db->query('SELECT data, approved FROM ' . $_mod_table . '_groups_users WHERE group_id=' . $group_id . ' AND userid=' . $userid)->fetch();
    if (empty($row)) {
        return false;
    }

    $sql = 'SELECT userid, group_id FROM ' . $_mod_table . ' WHERE userid=' . $userid;
    $user = $db->query($sql)->fetch();
    if (empty($user)) {
        return false;
    }

    $set_number = false;
    if ($group_id > 3) {
        $set_number = true;
    } else {
        $data = str_replace(',' . $global_config['idsite'] . ',', '', ',' . $row['data'] . ',');
        $data = trim($data, ',');
        if ($data == '') {
            $set_number = true;
        } else {
            $db->query('UPDATE ' . $_mod_table . "_groups_users SET data = '" . $data . "' WHERE group_id=" . $group_id . ' AND userid=' . $userid);
        }
    }

    if ($set_number) {
        $db->query('DELETE FROM ' . $_mod_table . '_groups_users WHERE group_id = ' . $group_id . ' AND userid = ' . $userid);

        if ($row['approved']) {
            $db->query('UPDATE ' . $_mod_table . '_groups SET numbers = numbers-1 WHERE group_id=' . $group_id);
        }
    }

    // Cập nhật lại danh sách nhóm cho user
    $sql = "SELECT group_id FROM " . $_mod_table . "_groups_users WHERE userid=" . $userid . " AND approved=1";
    $in_groups = $db->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    $in_groups = array_map('intval', $in_groups);

    // Xử lý lại nhóm chính nếu xóa khỏi nhóm này
    $new_group_id = $user['group_id'];
    if ($new_group_id == $group_id) {
        $new_group_id = 4;

        // Tìm nhóm chính theo luật ưu tiên thành viên mới => quản trị cấp tăng dần, mặc định thành viên chính thức
        $groups = [7, 3, 2, 1];
        foreach ($groups as $g) {
            if (in_array($g, $in_groups, true)) {
                $new_group_id = $g;
            }
        }
    }

    // Luôn luôn bổ sung nhóm thành viên chính thức nếu không phải thành viên mới
    if (!in_array(7, $in_groups)) {
        $in_groups[] = 4;
    }

    $in_groups = array_unique($in_groups);
    $in_groups = empty($in_groups) ? '' : implode(',', $in_groups);

    $db->query('UPDATE ' . $_mod_table . ' SET in_groups = ' . $db->quote($in_groups) . ', group_id=' . $new_group_id . ' WHERE userid=' . $userid);

    return true;
}

/**
 * nv_show_name_user()
 *
 * @param string $first_name
 * @param string $last_name
 * @param string $user_name
 * @param string $lang
 * @return string
 */
function nv_show_name_user($first_name, $last_name, $user_name = '', $lang = '')
{
    global $global_config, $db;

    if (!empty($lang) and $lang != NV_LANG_DATA) {
        $name_show = $db->query('SELECT config_value FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE config_name='name_show' AND lang='" . $lang . "' AND module='global'")->fetchColumn();
    } else {
        $name_show = $global_config['name_show'];
    }

    $full_name = $name_show ? $first_name . ' ' . $last_name : $last_name . ' ' . $first_name;
    $full_name = trim($full_name);

    return empty($full_name) ? $user_name : $full_name;
}

/**
 * greeting_for_user_create()
 * Function tạo lời chào trong email
 *
 * @param string $first_name
 * @param string $last_name
 * @param string $user_name
 * @param string $gender
 * @param string $lang
 * @return string
 */
function greeting_for_user_create($user_name, $first_name, $last_name = '', $gender = '', $lang = '')
{
    global $nv_Lang;

    if ($gender == 'M' or $gender == 'F') {
        $name = $nv_Lang->getGlobal('greeting_title_' . $gender, nv_show_name_user($first_name, $last_name, $user_name, $lang));
    } else {
        $name = $nv_Lang->getGlobal('greeting_title', nv_show_name_user($first_name, $last_name, $user_name, $lang));
    }

    return $nv_Lang->getGlobal('greeting_for_user', $name, $user_name);
}

/**
 * nv_date()
 *
 * @param string $format
 * @param int    $time
 * @return string|null
 */
function nv_date($format, $time = 0)
{
    global $nv_Lang;

    if (!$time) {
        $time = NV_CURRENTTIME;
    }
    $format = str_replace('r', 'D, d M Y H:i:s O', $format);
    $format = str_replace([
        'D',
        'M'
    ], [
        '[D]',
        '[M]'
    ], $format);
    $return = date($format, $time);

    $replaces = [
        '/\[Sun\](\W|$)/' => $nv_Lang->getGlobal('sun') . '$1',
        '/\[Mon\](\W|$)/' => $nv_Lang->getGlobal('mon') . '$1',
        '/\[Tue\](\W|$)/' => $nv_Lang->getGlobal('tue') . '$1',
        '/\[Wed\](\W|$)/' => $nv_Lang->getGlobal('wed') . '$1',
        '/\[Thu\](\W|$)/' => $nv_Lang->getGlobal('thu') . '$1',
        '/\[Fri\](\W|$)/' => $nv_Lang->getGlobal('fri') . '$1',
        '/\[Sat\](\W|$)/' => $nv_Lang->getGlobal('sat') . '$1',
        '/\[Jan\](\W|$)/' => $nv_Lang->getGlobal('jan') . '$1',
        '/\[Feb\](\W|$)/' => $nv_Lang->getGlobal('feb') . '$1',
        '/\[Mar\](\W|$)/' => $nv_Lang->getGlobal('mar') . '$1',
        '/\[Apr\](\W|$)/' => $nv_Lang->getGlobal('apr') . '$1',
        '/\[May\](\W|$)/' => $nv_Lang->getGlobal('may2') . '$1',
        '/\[Jun\](\W|$)/' => $nv_Lang->getGlobal('jun') . '$1',
        '/\[Jul\](\W|$)/' => $nv_Lang->getGlobal('jul') . '$1',
        '/\[Aug\](\W|$)/' => $nv_Lang->getGlobal('aug') . '$1',
        '/\[Sep\](\W|$)/' => $nv_Lang->getGlobal('sep') . '$1',
        '/\[Oct\](\W|$)/' => $nv_Lang->getGlobal('oct') . '$1',
        '/\[Nov\](\W|$)/' => $nv_Lang->getGlobal('nov') . '$1',
        '/\[Dec\](\W|$)/' => $nv_Lang->getGlobal('dec') . '$1',
        '/Sunday(\W|$)/' => $nv_Lang->getGlobal('sunday') . '$1',
        '/Monday(\W|$)/' => $nv_Lang->getGlobal('monday') . '$1',
        '/Tuesday(\W|$)/' => $nv_Lang->getGlobal('tuesday') . '$1',
        '/Wednesday(\W|$)/' => $nv_Lang->getGlobal('wednesday') . '$1',
        '/Thursday(\W|$)/' => $nv_Lang->getGlobal('thursday') . '$1',
        '/Friday(\W|$)/' => $nv_Lang->getGlobal('friday') . '$1',
        '/Saturday(\W|$)/' => $nv_Lang->getGlobal('saturday') . '$1',
        '/January(\W|$)/' => $nv_Lang->getGlobal('january') . '$1',
        '/February(\W|$)/' => $nv_Lang->getGlobal('february') . '$1',
        '/March(\W|$)/' => $nv_Lang->getGlobal('march') . '$1',
        '/April(\W|$)/' => $nv_Lang->getGlobal('april') . '$1',
        '/May(\W|$)/' => $nv_Lang->getGlobal('may') . '$1',
        '/June(\W|$)/' => $nv_Lang->getGlobal('june') . '$1',
        '/July(\W|$)/' => $nv_Lang->getGlobal('july') . '$1',
        '/August(\W|$)/' => $nv_Lang->getGlobal('august') . '$1',
        '/September(\W|$)/' => $nv_Lang->getGlobal('september') . '$1',
        '/October(\W|$)/' => $nv_Lang->getGlobal('october') . '$1',
        '/November(\W|$)/' => $nv_Lang->getGlobal('november') . '$1',
        '/December(\W|$)/' => $nv_Lang->getGlobal('december') . '$1'
    ];

    return preg_replace(array_keys($replaces), array_values($replaces), $return);
}

/**
 * @param int $short
 * @param int $timestamp
 * @param string $lang
 * @return string
 */
function nv_date_format(int $short = 1, ?int $timestamp = null, string $lang = '')
{
    global $global_config, $nv_default_regions;

    if (!is_null($timestamp) and empty($timestamp)) {
        return '';
    }
    if (is_null($timestamp)) {
        $timestamp = NV_CURRENTTIME;
    }
    if (empty($lang)) {
        $lang = NV_LANG_INTERFACE;
    }
    $region = $global_config['region'][$lang] ?? $nv_default_regions[$lang] ?? $nv_default_regions['en'];

    return nv_date($short ? $region['date_short'] : $region['date_long'], $timestamp);
}

/**
 * @param int $short
 * @param int $timestamp
 * @param string $lang
 * @return string
 */
function nv_time_format(int $short = 1, ?int $timestamp = null, string $lang = '')
{
    global $global_config, $nv_default_regions;

    if (!is_null($timestamp) and empty($timestamp)) {
        return '';
    }
    if (is_null($timestamp)) {
        $timestamp = NV_CURRENTTIME;
    }
    if (empty($lang)) {
        $lang = NV_LANG_INTERFACE;
    }
    $region = $global_config['region'][$lang] ?? $nv_default_regions[$lang] ?? $nv_default_regions['en'];
    $replaces = [
        'am' => $region['am_char'],
        'AM' => $region['am_char'],
        'pm' => $region['pm_char'],
        'PM' => $region['pm_char'],
    ];
    return str_replace(array_keys($replaces), array_values($replaces), nv_date($short ? $region['time_short'] : $region['time_long'], $timestamp));
}

/**
 * Hiển thị đầy đủ ngày tháng năm, giờ phút hoặc ngược lại
 *
 * @param int $timestamp
 * @param int $reverse
 * @param int $short
 * @param string $lang
 * @return string
 */
function nv_datetime_format(?int $timestamp = null, int $reverse = 0, int $short = 1, string $lang = '')
{
    if (!is_null($timestamp) and empty($timestamp)) {
        return '';
    }
    if ($reverse) {
        return nv_time_format($short, $timestamp, $lang) . ' ' . nv_date_format($short, $timestamp, $lang);
    }

    return nv_date_format($short, $timestamp, $lang) . ' ' . nv_time_format($short, $timestamp, $lang);
}

/**
 * nv_monthname()
 *
 * @param int $i
 * @return string
 */
function nv_monthname($i)
{
    global $nv_Lang;

    --$i;
    $month_names = [
        $nv_Lang->getGlobal('january'),
        $nv_Lang->getGlobal('february'),
        $nv_Lang->getGlobal('march'),
        $nv_Lang->getGlobal('april'),
        $nv_Lang->getGlobal('may'),
        $nv_Lang->getGlobal('june'),
        $nv_Lang->getGlobal('july'),
        $nv_Lang->getGlobal('august'),
        $nv_Lang->getGlobal('september'),
        $nv_Lang->getGlobal('october'),
        $nv_Lang->getGlobal('november'),
        $nv_Lang->getGlobal('december')
    ];

    return $month_names[$i] ?? '';
}

/**
 * nv_unhtmlspecialchars()
 *
 * @param string|int|float|array $string
 * @return string|int|float|array
 */
function nv_unhtmlspecialchars($string)
{
    if (empty($string)) {
        return $string;
    }

    if (is_array($string)) {
        $array_keys = array_keys($string);

        foreach ($array_keys as $key) {
            $string[$key] = nv_unhtmlspecialchars($string[$key]);
        }
    } elseif (is_string($string)) {
        $search = ['&amp;', '&#039;', '&quot;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x23;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;'];
        $replace = ['&', '\'', '"', '<', '>', '\\', '/', '(', ')', '*', '[', ']', '!', '=', '#', '%', '^', ':', '{', '}', '`', '~'];

        $string = str_replace($search, $replace, $string);
    }

    return $string;
}

/**
 * nv_htmlspecialchars()
 *
 * @param string $string
 * @return string
 */
function nv_htmlspecialchars($string)
{
    if (empty($string)) {
        return $string;
    }

    if (is_array($string)) {
        $array_keys = array_keys($string);

        foreach ($array_keys as $key) {
            $string[$key] = nv_htmlspecialchars($string[$key]);
        }
    } else {
        $search = ['&', '\'', '"', '<', '>', '\\', '/', '(', ')', '*', '[', ']', '!', '=', '%', '^', ':', '{', '}', '`', '~'];
        $replace = ['&amp;', '&#039;', '&quot;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;'];

        $string = str_replace($replace, $search, $string);
        $string = str_replace('&#x23;', '#', $string);
        $string = str_replace($search, $replace, $string);
        $string = preg_replace('/([^\&]+)\#/', '\\1&#x23;', $string);
    }

    return $string;
}

/**
 * strip_punctuation()
 *
 * @param string $text
 * @return string
 */
function strip_punctuation($text)
{
    $urlbrackets = '\[\]\(\)';
    $urlspacebefore = ':;\'_\*%@&?!' . $urlbrackets;
    $urlspaceafter = '\.,:;\'\-_\*@&\/\\\\\?!#' . $urlbrackets;
    $urlall = '\.,:;\'\-_\*%@&\/\\\\\?!#' . $urlbrackets;

    $specialquotes = '\'"\*<>';

    $fullstop = '\x{002E}\x{FE52}\x{FF0E}';
    $comma = '\x{002C}\x{FE50}\x{FF0C}';
    $arabsep = '\x{066B}\x{066C}';
    $numseparators = $fullstop . $comma . $arabsep;

    $numbersign = '\x{0023}\x{FE5F}\x{FF03}';
    $percent = '\x{066A}\x{0025}\x{066A}\x{FE6A}\x{FF05}\x{2030}\x{2031}';
    $prime = '\x{2032}\x{2033}\x{2034}\x{2057}';
    $nummodifiers = $numbersign . $percent . $prime;

    return preg_replace([ // Remove separator, control, formatting, surrogate, open/close quotes.
        '/[\p{Z}\p{Cc}\p{Cf}\p{Cs}\p{Pi}\p{Pf}]/u', // Remove other punctuation except special cases
        '/\p{Po}(?<![' . $specialquotes . $numseparators . $urlall . $nummodifiers . '])/u', // Remove non-URL open/close brackets, except URL brackets.
        '/[\p{Ps}\p{Pe}](?<![' . $urlbrackets . '])/u', // Remove special quotes, dashes, connectors, number separators, and URL characters followed by a space
        '/[' . $specialquotes . $numseparators . $urlspaceafter . '\p{Pd}\p{Pc}]+((?= )|$)/u', // Remove special quotes, connectors, and URL characters preceded by a space
        '/((?<= )|^)[' . $specialquotes . $urlspacebefore . '\p{Pc}]+/u', // Remove dashes preceded by a space, but not followed by a number
        '/((?<= )|^)\p{Pd}+(?![\p{N}\p{Sc}])/u', // Remove consecutive spaces
        '/ +/'
    ], ' ', $text);
}

/**
 * nv_nl2br()
 *
 * @param string $text
 * @param string $replacement
 * @return string
 */
function nv_nl2br($text, $replacement = '<br />')
{
    if (empty($text)) {
        return '';
    }

    return strtr($text, [
        "\r\n" => $replacement,
        "\r" => $replacement,
        "\n" => $replacement
    ]);
}

/**
 * nv_br2nl()
 *
 * @param string $text
 * @return string
 */
function nv_br2nl($text)
{
    if (empty($text)) {
        return '';
    }

    return preg_replace('/\<br(\s*)?\/?(\s*)?\>/i', chr(13) . chr(10), $text);
}

/**
 * nv_editor_nl2br()
 *
 * @param string $text
 * @return string
 */
function nv_editor_nl2br($text)
{
    if (empty($text)) {
        return '';
    }

    return nv_nl2br($text, (defined('NV_EDITOR') ? '' : '<br />'));
}

/**
 * nv_editor_br2nl()
 *
 * @param string $text
 * @return string
 */
function nv_editor_br2nl($text)
{
    if (empty($text)) {
        return '';
    }

    if (defined('NV_EDITOR')) {
        return $text;
    }

    return nv_br2nl($text);
}

/**
 * @param string $str
 * @param string $tag
 * @return string
 */
function nv_nl2tag($str, $tag = 'p')
{
    if (empty($str)) {
        return '';
    }

    $arr = explode("\n", $str);
    $arr = array_map('trim', $arr);
    $arr = array_filter($arr);

    return '<' . $tag . '>' . implode('</' . $tag . '><' . $tag . '>', $arr) . '</' . $tag . '>';
}

/**
 * @param string $str
 * @param string $tag
 * @return string
 */
function nv_tag2nl($str, $tag = 'p')
{
    $str = preg_replace('/<' . $tag . '[^>]*?>/', '', $str);

    return str_replace('</' . $tag . '>', chr(13) . chr(10), $str);
}

/**
 * nv_get_keywords()
 *
 * @param mixed $content
 * @param int   $keyword_limit
 * @param bool  $isArr
 * @return array|string
 */
function nv_get_keywords($content, $keyword_limit = 20, $isArr = false)
{
    $content = strip_tags($content);
    $content = nv_unhtmlspecialchars($content);
    $content = strip_punctuation($content);
    $content = trim($content);
    $content = nv_strtolower($content);

    $content = ' ' . $content . ' ';
    $keywords_return = [];

    $memoryLimitMB = (int) ini_get('memory_limit');

    if ($memoryLimitMB > 60 and file_exists(NV_ROOTDIR . '/includes/keywords/' . NV_LANG_DATA . '.php')) {
        require NV_ROOTDIR . '/includes/keywords/' . NV_LANG_DATA . '.php';

        $content_array = explode(' ', $content);
        $b = count($content_array);

        for ($i = 0; $i < $b - 3; ++$i) {
            $key3 = $content_array[$i] . ' ' . $content_array[$i + 1] . ' ' . $content_array[$i + 2];
            $key2 = $content_array[$i] . ' ' . $content_array[$i + 1];

            if (array_search($key3, $array_keywords_3, true)) {
                $keywords_return[] = $key3;
                $i += 2;
            } elseif (array_search($key2, $array_keywords_2, true)) {
                $keywords_return[] = $key2;
                $i += 1;
            }

            $keywords_return = array_unique($keywords_return);
            if (count($keywords_return) > $keyword_limit) {
                break;
            }
        }
    } else {
        $pattern_word = [];

        if (NV_SITEWORDS_MIN_3WORDS_LENGTH > 0 and NV_SITEWORDS_MIN_3WORDS_PHRASE_OCCUR > 0) {
            $pattern_word[] = "/[\s]+([\S]{" . NV_SITEWORDS_MIN_3WORDS_LENGTH . ",}\s[\S]{" . NV_SITEWORDS_MIN_3WORDS_LENGTH . ",}\s[\S]{" . NV_SITEWORDS_MIN_3WORDS_LENGTH . ",})[\s]+/uis";
        }

        if (NV_SITEWORDS_MIN_2WORDS_LENGTH > 0 and NV_SITEWORDS_MIN_2WORDS_PHRASE_OCCUR > 0) {
            $pattern_word[] = "/[\s]+([\S]{" . NV_SITEWORDS_MIN_2WORDS_LENGTH . ",}\s[\S]{" . NV_SITEWORDS_MIN_2WORDS_LENGTH . ",})[\s]+/uis";
        }

        if (NV_SITEWORDS_MIN_WORD_LENGTH > 0 and NV_SITEWORDS_MIN_WORD_OCCUR > 0) {
            $pattern_word[] = "/[\s]+([\S]{" . NV_SITEWORDS_MIN_WORD_LENGTH . ",})[\s]+/uis";
        }

        if (empty($pattern_word)) {
            return '';
        }

        $lenght = 0;
        $max_strlen = min(NV_SITEWORDS_MAX_STRLEN, 300);

        foreach ($pattern_word as $pattern) {
            while (preg_match($pattern, $content, $matches)) {
                $keywords_return[] = $matches[1];
                $lenght += nv_strlen($matches[1]);

                $content = preg_replace("/[\s]+(" . preg_quote($matches[1]) . ")[\s]+/uis", ' ', $content);

                if ($lenght >= $max_strlen) {
                    break;
                }
            }

            if ($lenght >= $max_strlen) {
                break;
            }
        }

        $keywords_return = array_unique($keywords_return);
    }

    if ($isArr) {
        return $keywords_return;
    }

    return implode(',', $keywords_return);
}

/**
 * Thêm khung HTML vào nội dung mail.
 *
 * @param string $subject
 * @param string $body
 * @param array  $gconfigs
 * @param string $lang
 * @return string
 */
function mailAddHtml($subject, $body, $gconfigs, $lang)
{
    global $nv_Lang;

    $subject = nv_autoLinkDisable($subject);

    // Gửi mail trên ngôn ngữ khác giao diện hiện tại thì đọc tạm ngôn ngữ mới
    if ($lang != NV_LANG_INTERFACE) {
        $nv_Lang->loadGlobal(false, true);
    }

    $mail_tpl = NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/tpl/mail.tpl';
    $template_tpl = 'default';
    if (!empty($gconfigs['mail_tpl'])) {
        $path_tpl = '';
        if (file_exists(NV_ROOTDIR . '/' . $gconfigs['mail_tpl'])) {
            $mail_tpl = NV_ROOTDIR . '/' . $gconfigs['mail_tpl'];
            $path_tpl = $gconfigs['mail_tpl'];
        } elseif (file_exists($gconfigs['mail_tpl'])) {
            $mail_tpl = $gconfigs['mail_tpl'];
            $path_tpl = substr($gconfigs['mail_tpl'], strlen(NV_ROOTDIR . '/'));
        }
        if (preg_match('/\/([a-zA-Z0-9\-\_]+)\/system\//', $path_tpl, $m)) {
            $template_tpl = get_tpl_dir($m[1], $template_tpl, 'theme_email.php');
        }
    }

    $html = require NV_ROOTDIR . '/themes/' . $template_tpl . '/theme_email.php';

    // Trả về ngôn ngữ hiện tại sau khi xử lý nội dung email
    if ($lang != NV_LANG_INTERFACE) {
        $nv_Lang->changeLang(NV_LANG_INTERFACE);
    }

    return $html;
}

/**
 * nv_sendmail()
 *
 * @param mixed  $from
 * @param mixed  $to
 * @param string $subject
 * @param string $message
 * @param string $files
 * @param bool   $AddEmbeddedImage
 * @param bool   $testmode
 * @param mixed  $cc
 * @param array  $bcc
 * @param bool   $mailhtml
 * @param array  $custom_headers
 * @param mixed  $lang
 * @return bool
 *
 * $from:             Nếu là string thì nó được hiểu là reply_address
 *                    Nếu là array thì có các giá trị sau đây:
 *                    [reply_name: 'Reply Me' (string|array),
 *                    reply_address: 'reply@nukeviet.vn'(string|array),
 *                    from_name: contact@nukeviet.vn (string),
 *                    from_address: 'NukeViet']
 *
 * $to:               address1@nukeviet.vn
 *                    Hoặc: [address1@nukeviet.vn,address2@nukeviet.vn]
 *
 * $files:            Có thể gửi nhiều files, ngăn cách bởi dấu phẩy
 *                    Đường dẫn đến file là tuyệt đối
 *
 * $AddEmbeddedImage: Có thêm logo của site hay không.
 *                    Nếu có thì nó sẽ thay thế cho src="cid:sitelogo" trong thẻ img
 *
 * $cc:               contact@nukeviet.vn
 *                    Hoặc: contact@nukeviet.vn => NukeViet1, contact2@nukeviet.vn => NukeViet2
 *                    Hoặc: contact@nukeviet.vn,contact2@nukeviet.vn
 *
 * $bcc:              contact@nukeviet.vn
 *                    Hoặc: contact@nukeviet.vn => NukeViet1, contact2@nukeviet.vn => NukeViet2
 *                    Hoặc: contact@nukeviet.vn,contact2@nukeviet.vn
 *
 * $mailhtml:         Xác định có thêm khung HTML vào nội dung thư hay không, mặc định true
 *
 * $custom_headers:   Tiêu đề tùy chỉnh thêm vào phần header của mail (Dạng: Khóa => Giá trị)
 *
 * $lang:             Ngôn ngữ gửi mail, nếu rỗng sẽ là NV_LANG_DATA
 * string $mail_tpl:  Tệp mẫu thư tùy chỉnh, nếu trống sẽ lấy theo cấu hình gửi mail
 */
function nv_sendmail($from, $to, $subject, $message, $files = '', $AddEmbeddedImage = false, $testmode = false, $cc = [], $bcc = [], $mailhtml = true, $custom_headers = [], $lang = '', $mail_tpl = '')
{
    global $global_config, $db, $crypt;

    $sm_parameters = [];
    $sm_parameters['language'] = (empty($lang) or !in_array($lang, $global_config['setup_langs'], true)) ? NV_LANG_INTERFACE : $lang;

    $gconfigs = $global_config;
    if ($lang != NV_LANG_DATA) {
        $result = $db->query('SELECT lang, module, config_name, config_value FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE lang='" . $lang . "' AND module='global'");
        while ($row = $result->fetch()) {
            if ($row['config_name'] == 'smtp_password') {
                $row['config_value'] = $crypt->decrypt($row['config_value']);
            }
            if ($row['config_name'] == 'site_logo') {
                if (empty($row['config_value'])) {
                    $row['config_value'] = NV_ASSETS_DIR . '/images/logo.png';
                }
            }
            $gconfigs[$row['config_name']] = $row['config_value'];
        }
    }
    if (!empty($mail_tpl)) {
        $gconfigs['mail_tpl'] = $mail_tpl;
    }

    if ($gconfigs['mailer_mode'] == 'no') {
        return $testmode ? 'Mailing service has been turned off' : false;
    }

    if (empty($to)) {
        return $testmode ? 'No receiver' : false;
    }
    $sm_parameters['to'] = is_array($to) ? array_values($to) : [$to];

    $sm_parameters['from_name'] = $sm_parameters['from_address'] = $sm_parameters['reply_name'] = $sm_parameters['reply_address'] = '';
    // Xác định thông tin người gửi, người nhận từ giá trị truyền vào
    if (empty($from)) {
        // $sm_parameters['reply_address'] = $gconfigs['site_email'];
    } elseif (is_array($from)) {
        $sm_parameters['from_address'] = !empty($from[3]) ? $from[3] : (!empty($from['from_address']) ? $from['from_address'] : $sm_parameters['from_address']);
        $sm_parameters['from_name'] = !empty($from[2]) ? $from[2] : (!empty($from['from_name']) ? $from['from_name'] : $sm_parameters['from_name']);
        $sm_parameters['reply_address'] = !empty($from[1]) ? $from[1] : (!empty($from['reply_address']) ? $from['reply_address'] : $sm_parameters['reply_address']);
        $sm_parameters['reply_name'] = !empty($from[0]) ? $from[0] : (!empty($from['reply_name']) ? $from['reply_name'] : $sm_parameters['reply_name']);
    } else {
        $sm_parameters['reply_address'] = $from;
    }

    $sm_parameters['cc'] = [];
    if (!empty($cc)) {
        if (!is_array($cc)) {
            $sm_parameters['cc'][$cc] = '';
        } else {
            foreach ($cc as $_k => $_cc) {
                $_m = is_numeric($_k) ? $_cc : $_k;
                $_n = is_numeric($_k) ? '' : $_cc;
                $sm_parameters['cc'][$_m] = $_n;
            }
        }
    }

    $sm_parameters['bcc'] = [];
    if (!empty($bcc)) {
        if (!is_array($bcc)) {
            $sm_parameters['bcc'][$bcc] = '';
        } else {
            foreach ($bcc as $_k => $_bcc) {
                $_m = is_numeric($_k) ? $_bcc : $_k;
                $_n = is_numeric($_k) ? '' : $_bcc;
                $sm_parameters['bcc'][$_m] = $_n;
            }
        }
    }

    $sm_parameters['subject'] = $subject;
    $sm_parameters['message'] = $message;
    $sm_parameters['logo_add'] = $AddEmbeddedImage;
    $sm_parameters['mailhtml'] = $mailhtml;
    $sm_parameters['testmode'] = $testmode;
    $sm_parameters['files'] = !empty($files) ? array_map('trim', explode(',', $files)) : [];

    // Nếu gửi mail bằng hình thức riêng
    if (isset($gconfigs['other_sendmail_method']) and function_exists($gconfigs['other_sendmail_method'])) {
        return _otherMethodSendmail($gconfigs, $sm_parameters);
    }

    try {
        $mail = new NukeViet\Core\Sendmail($gconfigs, $sm_parameters['language']);
        // Có thêm khung HTML vào nội dung mail hay không
        $mail->setMailHtml($mailhtml);

        // Add logo
        $AddEmbeddedImage && $mail->addLogo();

        // Xác định TO
        foreach ($sm_parameters['to'] as $_email) {
            $mail->addTo($_email);
        }

        // Xác định CC
        if (!empty($sm_parameters['cc'])) {
            foreach ($sm_parameters['cc'] as $_k => $_cc) {
                $_m = is_numeric($_k) ? $_cc : $_k;
                $_n = is_numeric($_k) ? '' : $_cc;
                $mail->addCC($_m, $_n);
            }
        }

        // Xác định BCC
        if (!empty($sm_parameters['bcc'])) {
            foreach ($sm_parameters['bcc'] as $_k => $_bcc) {
                $_m = is_numeric($_k) ? $_bcc : $_k;
                $_n = is_numeric($_k) ? '' : $_bcc;
                $mail->addBCC($_m, $_n);
            }
        }

        // Xác định FROM
        if (!empty($sm_parameters['from_address'])) {
            $mail->setSender($sm_parameters['from_address'], $sm_parameters['from_name']);
        }

        // Xác định REPLYTO
        if (!empty($sm_parameters['reply_address'])) {
            if (!is_array($sm_parameters['reply_address'])) {
                $mail->addReply($sm_parameters['reply_address'], (!is_array($sm_parameters['reply_name']) ? $sm_parameters['reply_name'] : $sm_parameters['reply_name'][0]));
            } else {
                !is_array($sm_parameters['reply_name']) && $sm_parameters['reply_name'] = [$sm_parameters['reply_name']];
                foreach ($sm_parameters['reply_address'] as $_k => $_reply) {
                    $mail->addReply($_reply, ($sm_parameters['reply_name'][$_k] ?? ''));
                }
            }
        }

        // Set Subject
        $mail->setSubject($sm_parameters['subject']);

        // Set Content
        $mail->setContent($sm_parameters['message']);

        // Add files
        if (!empty($sm_parameters['files'])) {
            foreach ($sm_parameters['files'] as $file) {
                $mail->addFile($file);
            }
        }

        // Thêm tiêu đề tùy chỉnh
        if (!empty($custom_headers)) {
            foreach ($custom_headers as $key => $val) {
                $mail->addCustomHeader($key, $val);
            }
        }

        nv_apply_hook('', 'sendmail_others_actions', [$gconfigs, $mail]);

        // Gửi mail
        if (!$mail->Send()) {
            if (!$testmode and !empty($gconfigs['notify_email_error'])) {
                nv_insert_notification('settings', 'sendmail_failure', [
                    $sm_parameters['subject'],
                    implode(', ', $sm_parameters['to'])
                ], 0, 0, 0, 1, 2);
            }
            trigger_error($mail->ErrorInfo, E_USER_WARNING);

            return $testmode ? $mail->ErrorInfo : false;
        }

        return $testmode ? '' : true;
    } catch (PHPMailer\PHPMailer\Exception $e) {
        trigger_error($e->errorMessage(), E_USER_WARNING);

        return $testmode ? $e->errorMessage() : false;
    }
}

/**
 * _otherMethodSendmail()
 *
 * @param array $gconfigs
 * @param mixed $sm_parameters
 * @return mixed
 */
function _otherMethodSendmail($gconfigs, $sm_parameters)
{
    global $sys_info;

    empty($sm_parameters['from_name']) && $sm_parameters['from_name'] = $gconfigs['site_name'];
    empty($sm_parameters['reply_name']) && $sm_parameters['reply_name'] = $gconfigs['site_name'];

    // Cố định người gửi người nhận hoặc chỉ định khi không có giá trị truyền vào
    if (!empty($gconfigs['sender_name']) and (empty($sm_parameters['from_name']) or $gconfigs['force_sender'])) {
        $sm_parameters['from_name'] = $gconfigs['sender_name'];
    }
    if (!empty($gconfigs['reply_name']) and (empty($sm_parameters['reply_name']) or $gconfigs['force_reply'])) {
        $sm_parameters['reply_name'] = $gconfigs['reply_name'];
    }
    if (!empty($gconfigs['reply_email']) and (empty($sm_parameters['reply_address']) or $gconfigs['force_reply'])) {
        $sm_parameters['reply_address'] = $gconfigs['reply_email'];
    }
    if (!empty($gconfigs['sender_email']) and $gconfigs['force_sender']) {
        $sm_parameters['from_address'] = $gconfigs['sender_email'];
    }

    $sm_parameters['reply'] = [];
    if (!empty($sm_parameters['reply_address'])) {
        if (!is_array($sm_parameters['reply_address'])) {
            $sm_parameters['reply'][$sm_parameters['reply_address']] = !is_array($sm_parameters['reply_name']) ? $sm_parameters['reply_name'] : $sm_parameters['reply_name'][0];
        } else {
            !is_array($sm_parameters['reply_name']) && $sm_parameters['reply_name'] = [
                $sm_parameters['reply_name']
            ];
            foreach ($sm_parameters['reply_address'] as $_k => $_reply) {
                $sm_parameters['reply'][$_reply] = $sm_parameters['reply_name'][$_k] ?? '';
            }
        }
    }

    if ($sm_parameters['mailhtml']) {
        $sm_parameters['message'] = mailAddHtml($sm_parameters['subject'], $sm_parameters['message'], $gconfigs, $sm_parameters['language']);
        $sm_parameters['logo_add'] = true;
    }
    $sm_parameters['message'] = nv_url_rewrite($sm_parameters['message']);
    $optimizer = new NukeViet\Core\Optimizer($sm_parameters['message'], NV_BASE_SITEURL, !empty($sys_info['is_http2']), 0, $gconfigs);
    $sm_parameters['message'] = $optimizer->process(false);
    $sm_parameters['message'] = nv_unhtmlspecialchars($sm_parameters['message']);

    return call_user_func($gconfigs['other_sendmail_method'], $sm_parameters, $gconfigs);
}

/**
 * nv_sendmail_async()
 * Khởi tạo một luồng truy vấn không đồng bộ/chạy nền để gửi mail
 * Nếu gửi mail không cần trả về kết quả thì nên sử dụng function này
 *
 * @param array|string $from
 * @param array|string $to
 * @param string       $subject
 * @param string       $message
 * @param string       $files
 * @param bool         $AddEmbeddedImage
 * @param bool         $testmode
 * @param array|string $cc
 * @param array        $bcc
 * @param bool         $mailhtml
 * @param array        $custom_headers
 * @param string       $lang
 */
function nv_sendmail_async($from, $to, $subject, $message, $files = '', $AddEmbeddedImage = false, $testmode = false, $cc = [], $bcc = [], $mailhtml = true, $custom_headers = [], $lang = '')
{
    global $global_config;

    // Mặc định gửi email bằng ngôn ngữ giao diện hiện tại đang dùng. Nếu không sẽ chỉ gửi bằng ngôn ngữ mặc định của site.
    if (empty($lang)) {
        $lang = NV_LANG_INTERFACE;
    }

    $json_contents = json_encode([
        'from' => $from,
        'to' => $to,
        'subject' => $subject,
        'message' => $message,
        'files' => $files,
        'AddEmbeddedImage' => $AddEmbeddedImage,
        'testmode' => $testmode,
        'cc' => $cc,
        'bcc' => $bcc,
        'mailhtml' => $mailhtml,
        'custom_headers' => $custom_headers,
        'lang' => $lang
    ], JSON_UNESCAPED_UNICODE);

    $file_name = nv_genpass(8);
    $temp_file = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . md5($global_config['sitekey'] . $file_name);
    file_put_contents($temp_file, $json_contents, LOCK_EX);
    post_async(NV_BASE_SITEURL . 'sload.php', ['__sendmail' => $file_name]);
}

/**
 * Khởi tạo một luồng truy vấn không đồng bộ/chạy nền để gửi mail theo mẫu
 * Nếu gửi mail không cần trả về kết quả thì nên sử dụng function này
 *
 * @param int|array     $emailid
 * @param array         $data
 * @param string        $lang dùng để lấy ra subject và body của email.
 *                            Nếu không chỉ ra thì là ngôn ngữ giao diện hiện hành.
 *                            Biến này không nên trống, nếu để trống có khả năng nội dung email và tên website có thể lệch nhau
 * @param string        $attachments
 */
function nv_sendmail_template_async($emailid, $data = [], $lang = '', $attachments = '')
{
    global $global_config;

    // Mặc định gửi email bằng ngôn ngữ giao diện hiện tại đang dùng. Nếu không sẽ chỉ gửi bằng ngôn ngữ mặc định của site.
    if (empty($lang)) {
        $lang = NV_LANG_INTERFACE;
    }
    // Mặc định gửi email trên module dữ liệu hiện hành
    if (is_array($emailid) and !isset($emailid[2])) {
        $emailid[2] = NV_LANG_DATA;
    }

    $json_contents = json_encode([
        'emailid' => $emailid,
        'data' => $data,
        'attachments' => $attachments,
        'lang' => $lang
    ], JSON_UNESCAPED_UNICODE);

    $file_name = nv_genpass(8);
    $temp_file = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . md5($global_config['sitekey'] . $file_name);
    file_put_contents($temp_file, $json_contents, LOCK_EX);
    post_async(NV_BASE_SITEURL . 'sload.php', ['__sendmail_template' => $file_name]);
}

/**
 * betweenURLs()
 *
 * @param int    $page
 * @param int    $total
 * @param string $base_url
 * @param string $urlappend
 * @param string $prevPage
 * @param string $nextPage
 */
function betweenURLs($page, $total, $base_url, $urlappend, &$prevPage, &$nextPage)
{
    if ($page < 1 or ($page > 1 and $page > $total)) {
        nv_redirect_location($base_url);
    }

    if ($page > 1) {
        $prev = $page - 1;
        $prevPage = urlRewriteWithDomain($base_url . ($prev > 1 ? ($urlappend . $prev) : ''), NV_MAIN_DOMAIN);
    }

    if ($page >= 1 and $page < $total) {
        $next = $page + 1;
        $nextPage = urlRewriteWithDomain($base_url . $urlappend . $next, NV_MAIN_DOMAIN);
    }
}

/**
 * nv_generate_page()
 *
 * @param string $base_url
 * @param int    $num_items
 * @param int    $per_page
 * @param int    $on_page
 * @param bool   $add_prevnext_text
 * @param bool   $onclick
 * @param string $js_func_name
 * @param string $containerid
 * @param bool   $full_theme
 * @return string
 */
function nv_generate_page($base_url, $num_items, $per_page, $on_page, $add_prevnext_text = true, $onclick = false, $js_func_name = 'nv_urldecode_ajax', $containerid = 'generate_page', $full_theme = true)
{
    global $nv_Lang, $theme_config;

    $ul_class = $theme_config['pagination']['ul_class'] ?? 'pagination';
    $li_class = $theme_config['pagination']['li_class'] ?? 'page-item';
    $a_class = $theme_config['pagination']['a_class'] ?? 'page-link';

    $li_active_class = ' class="' . $li_class . (!empty($li_class) ? ' ' : '') . 'active"';
    $li_disabled_class = ' class="' . $li_class . (!empty($li_class) ? ' ' : '') . 'disabled"';
    $ul_class = !empty($ul_class) ? ' class="' . $ul_class . '"' : '';
    $li_class = !empty($li_class) ? ' class="' . $li_class . '"' : '';
    $a_class = !empty($a_class) ? ' class="' . $a_class . '"' : '';

    // Round up total page
    $total_pages = ceil($num_items / $per_page);

    if ($total_pages < 2) {
        return '';
    }

    if (!is_array($base_url)) {
        $amp = preg_match('/\?/', $base_url) ? '&amp;' : '?';
        $amp .= 'page=';
    } else {
        $amp = $base_url['amp'];
        $base_url = $base_url['link'];
    }

    $page_string = '';

    if ($total_pages > 10) {
        $init_page_max = ($total_pages > 3) ? 3 : $total_pages;

        for ($i = 1; $i <= $init_page_max; ++$i) {
            $href = ($i > 1) ? $base_url . $amp . $i : $base_url;
            $href = !$onclick ? 'href="' . $href . '"' : 'href="#" data-toggle="gen-page-js" data-func="' . $js_func_name . '" data-href="' . $href . '" data-obj="' . $containerid . '"';
            $page_string .= '<li' . ($i == $on_page ? $li_active_class : $li_class) . '><a' . $a_class . ($i == $on_page ? ' href="#"' : ' ' . $href) . '>' . nv_number_format($i) . '</a></li>';
        }

        if ($total_pages > 3) {
            if ($on_page > 1 and $on_page < $total_pages) {
                if ($on_page > 5) {
                    $page_string .= '<li' . $li_disabled_class . '><span' . $a_class . '>...</span></li>';
                }

                $init_page_min = ($on_page > 4) ? $on_page : 5;
                $init_page_max = ($on_page < $total_pages - 4) ? $on_page : $total_pages - 4;

                for ($i = $init_page_min - 1; $i < $init_page_max + 2; ++$i) {
                    $href = ($i > 1) ? $base_url . $amp . $i : $base_url;
                    $href = !$onclick ? 'href="' . $href . '"' : 'href="#" data-toggle="gen-page-js" data-func="' . $js_func_name . '" data-href="' . $href . '" data-obj="' . $containerid . '"';
                    $page_string .= '<li' . ($i == $on_page ? $li_active_class : $li_class) . '><a' . $a_class . ($i == $on_page ? ' href="#"' : ' ' . $href) . '>' . nv_number_format($i) . '</a></li>';
                }

                if ($on_page < $total_pages - 4) {
                    $page_string .= '<li' . $li_disabled_class . '><span' . $a_class . '>...</span></li>';
                }
            } else {
                $page_string .= '<li' . $li_disabled_class . '><span' . $a_class . '>...</span></li>';
            }

            for ($i = $total_pages - 2; $i < $total_pages + 1; ++$i) {
                $href = ($i > 1) ? $base_url . $amp . $i : $base_url;
                $href = !$onclick ? 'href="' . $href . '"' : 'href="#" data-toggle="gen-page-js" data-func="' . $js_func_name . '" data-href="' . $href . '" data-obj="' . $containerid . '"';
                $page_string .= '<li' . ($i == $on_page ? $li_active_class : $li_class) . '><a' . $a_class . ($i == $on_page ? ' href="#"' : ' ' . $href) . '>' . nv_number_format($i) . '</a></li>';
            }
        }
    } else {
        for ($i = 1; $i < $total_pages + 1; ++$i) {
            $href = ($i > 1) ? $base_url . $amp . $i : $base_url;
            $href = !$onclick ? 'href="' . $href . '"' : 'href="#" data-toggle="gen-page-js" data-func="' . $js_func_name . '" data-href="' . $href . '" data-obj="' . $containerid . '"';
            $page_string .= '<li' . ($i == $on_page ? $li_active_class : $li_class) . '><a' . $a_class . ($i == $on_page ? ' href="#"' : ' ' . $href) . '>' . nv_number_format($i) . '</a></li>';
        }
    }

    if ($add_prevnext_text) {
        if ($on_page > 1) {
            $href = ($on_page > 2) ? $base_url . $amp . ($on_page - 1) : $base_url;
            $href = !$onclick ? 'href="' . $href . '"' : 'href="#" data-toggle="gen-page-js" data-func="' . $js_func_name . '" data-href="' . $href . '" data-obj="' . $containerid . '"';
            $page_string = '<li' . $li_class . '><a' . $a_class . ' ' . $href . ' title="' . $nv_Lang->getGlobal('pageprev') . '">&laquo;</a></li>' . $page_string;
        } else {
            $page_string = '<li' . $li_disabled_class . '><a' . $a_class . ' href="#">&laquo;</a></li>' . $page_string;
        }

        if ($on_page < $total_pages) {
            $href = ($on_page) ? $base_url . $amp . ($on_page + 1) : $base_url;
            $href = !$onclick ? 'href="' . $href . '"' : 'href="#" data-toggle="gen-page-js" data-func="' . $js_func_name . '" data-href="' . $href . '" data-obj="' . $containerid . '"';
            $page_string .= '<li' . $li_class . '><a' . $a_class . ' ' . $href . ' title="' . $nv_Lang->getGlobal('pagenext') . '">&raquo;</a></li>';
        } else {
            $page_string .= '<li' . $li_disabled_class . '><a' . $a_class . ' href="#">&raquo;</a></li>';
        }
    }

    if ($full_theme !== true) {
        return $page_string;
    }

    return '<ul' . $ul_class . '>' . $page_string . '</ul>';
}

/**
 * nv_alias_page()
 *
 * @param string $title
 * @param string $base_url
 * @param int    $num_items
 * @param int    $per_page
 * @param int    $on_page
 * @param bool   $add_prevnext_text
 * @param bool   $full_theme
 * @return string
 */
function nv_alias_page($title, $base_url, $num_items, $per_page, $on_page, $add_prevnext_text = true, $full_theme = true)
{
    global $nv_Lang, $theme_config;

    $ul_class = $theme_config['pagination']['ul_class'] ?? 'pagination';
    $li_class = $theme_config['pagination']['li_class'] ?? 'page-item';
    $a_class = $theme_config['pagination']['a_class'] ?? 'page-link';

    $li_active_class = ' class="' . $li_class . (!empty($li_class) ? ' ' : '') . 'active"';
    $li_disabled_class = ' class="' . $li_class . (!empty($li_class) ? ' ' : '') . 'disabled"';
    $ul_class = !empty($ul_class) ? ' class="' . $ul_class . '"' : '';
    $li_class = !empty($li_class) ? ' class="' . $li_class . '"' : '';
    $a_class = !empty($a_class) ? ' class="' . $a_class . '"' : '';

    $total_pages = ceil($num_items / $per_page);

    if ($total_pages < 2) {
        return '';
    }

    $title .= NV_TITLEBAR_DEFIS . $nv_Lang->getGlobal('page');
    $page_string = ($on_page == 1) ? '<li' . $li_active_class . '><a' . $a_class . ' href="#">1</a></li>' : '<li' . $li_class . '><a' . $a_class . ' rel="prev" title="' . $title . ' 1" href="' . $base_url . '">1</a></li>';

    if ($total_pages > 7) {
        if ($on_page < 4) {
            $init_page_max = ($total_pages > 2) ? 2 : $total_pages;
            for ($i = 2; $i <= $init_page_max; ++$i) {
                if ($i == $on_page) {
                    $page_string .= '<li' . $li_active_class . '><a' . $a_class . ' href="#">' . nv_number_format($i) . '</a></li>';
                } else {
                    $rel = ($i > $on_page) ? 'next' : 'prev';
                    $page_string .= '<li' . $li_class . '><a' . $a_class . ' rel="' . $rel . '" title="' . $title . ' ' . $i . '" href="' . $base_url . '/page-' . $i . '">' . nv_number_format($i) . '</a></li>';
                }
            }
        }

        if ($on_page > 1 and $on_page < $total_pages) {
            if ($on_page > 3) {
                $page_string .= '<li' . $li_disabled_class . '><span' . $a_class . '>...</span></li>';
            }

            $init_page_min = ($on_page > 3) ? $on_page : 4;
            $init_page_max = ($on_page < $total_pages - 3) ? $on_page : $total_pages - 3;

            for ($i = $init_page_min - 1; $i < $init_page_max + 2; ++$i) {
                if ($i == $on_page) {
                    $page_string .= '<li' . $li_active_class . '><a' . $a_class . ' href="#">' . nv_number_format($i) . '</a></li>';
                } else {
                    $rel = ($i > $on_page) ? 'next' : 'prev';
                    $page_string .= '<li' . $li_class . '><a' . $a_class . ' rel="' . $rel . '" title="' . $title . ' ' . $i . '" href="' . $base_url . '/page-' . $i . '">' . nv_number_format($i) . '</a></li>';
                }
            }

            if ($on_page < $total_pages - 3) {
                $page_string .= '<li' . $li_disabled_class . '><span' . $a_class . '>...</span></li>';
            }
        } else {
            $page_string .= '<li' . $li_disabled_class . '><span' . $a_class . '>...</span></li>';
        }

        $init_page_min = ($total_pages - $on_page > 3) ? $total_pages : $total_pages - 1;
        for ($i = $init_page_min; $i <= $total_pages; ++$i) {
            if ($i == $on_page) {
                $page_string .= '<li' . $li_active_class . '><a' . $a_class . ' href="#">' . nv_number_format($i) . '</a></li>';
            } else {
                $rel = ($i > $on_page) ? 'next' : 'prev';
                $page_string .= '<li' . $li_class . '><a' . $a_class . ' rel="' . $rel . '" title="' . $title . ' ' . $i . '" href="' . $base_url . '/page-' . $i . '">' . nv_number_format($i) . '</a></li>';
            }
        }
    } else {
        for ($i = 2; $i < $total_pages + 1; ++$i) {
            if ($i == $on_page) {
                $page_string .= '<li' . $li_active_class . '><a' . $a_class . ' href="#">' . nv_number_format($i) . '</a><li>';
            } else {
                $rel = ($i > $on_page) ? 'next' : 'prev';
                $page_string .= '<li' . $li_class . '><a' . $a_class . ' rel="' . $rel . '" title="' . $title . ' ' . $i . '" href="' . $base_url . '/page-' . $i . '">' . nv_number_format($i) . '</a></li>';
            }
        }
    }

    if ($add_prevnext_text) {
        if ($on_page > 1) {
            $href = ($on_page > 2) ? $base_url . '/page-' . ($on_page - 1) : $base_url;
            $page_string = '<li' . $li_class . '><a' . $a_class . ' rel="prev" title="' . $title . ' ' . ($on_page - 1) . '" href="' . $href . '">&laquo;</a></li>' . $page_string;
        } else {
            $page_string = '<li' . $li_disabled_class . '><a' . $a_class . ' href="#">&laquo;</a></li>' . $page_string;
        }

        if ($on_page < $total_pages) {
            $page_string .= '<li' . $li_class . '><a' . $a_class . ' rel="next" title="' . $title . ' ' . ($on_page + 1) . '" href="' . $base_url . '/page-' . ($on_page + 1) . '">&raquo;</a></li>';
        } else {
            $page_string .= '<li' . $li_disabled_class . '><a' . $a_class . ' href="#">&raquo;</a></li>';
        }
    }

    if ($full_theme !== true) {
        return $page_string;
    }

    return '<ul' . $ul_class . '>' . $page_string . '</ul>';
}

/**
 * check_endurl_variables()
 *
 * @param mixed $request_query
 */
function check_endurl_variables(&$request_query)
{
    global $global_config;

    if (!empty($request_query) and !empty($global_config['end_url_variables'])) {
        $kl = array_keys($request_query);
        foreach ($kl as $k) {
            if (isset($global_config['end_url_variables'][$k])) {
                $pattern = '';
                in_array('lower', $global_config['end_url_variables'][$k], true) && $pattern .= 'a-z';
                in_array('upper', $global_config['end_url_variables'][$k], true) && $pattern .= 'A-Z';
                in_array('number', $global_config['end_url_variables'][$k], true) && $pattern .= '0-9';
                in_array('dash', $global_config['end_url_variables'][$k], true) && $pattern .= '\-';
                in_array('under', $global_config['end_url_variables'][$k], true) && $pattern .= '\_';
                in_array('dot', $global_config['end_url_variables'][$k], true) && $pattern .= '\.';
                in_array('at', $global_config['end_url_variables'][$k], true) && $pattern .= '\@';
                $pattern = '/^[' . $pattern . ']+$/';

                if (preg_match($pattern, $request_query[$k])) {
                    unset($request_query[$k]);
                }
            }
        }
    }
}

/**
 * getPageUrl()
 *
 * @param string $page_url
 * @param bool   $is_query_check
 * @param bool   $is_abs_check
 * @param string $request_uri
 * @return false|string
 */
function getPageUrl($page_url, $is_query_check, $is_abs_check, &$request_uri = '')
{
    // Rewrite $page_url và cắt domain nếu nó tồn tại
    $url_rewrite = nv_url_rewrite($page_url, true);
    str_starts_with($url_rewrite, NV_MY_DOMAIN) && $url_rewrite = substr($url_rewrite, strlen(NV_MY_DOMAIN));

    // Phân tích url đã rewrite được ra path và query
    $url_rewrite_check = str_replace('&amp;', '&', $url_rewrite);
    $url_rewrite_check = urldecode($url_rewrite_check);
    $url_rewrite_check = preg_replace_callback('/[^:\/@?&=#]+/usD', function ($matches) {
        return urlencode($matches[0]);
    }, $url_rewrite_check);
    $url_parts = parse_url($url_rewrite_check);
    $url_parts['path'] = urldecode($url_parts['path']);
    !isset($url_parts['query']) && $url_parts['query'] = '';

    // Xử lý $_SERVER['REQUEST_URI'] cắt domain nếu nó tồn tại, phân tích ra path và query
    $request_uri = $_SERVER['REQUEST_URI'];
    str_starts_with($request_uri, NV_MY_DOMAIN) && $request_uri = substr($request_uri, strlen(NV_MY_DOMAIN));
    $request_parts = parse_url($request_uri);
    $request_parts['path'] = urldecode($request_parts['path']);
    !isset($request_parts['query']) && $request_parts['query'] = '';

    if (empty($request_parts['path']) or strcmp($url_parts['path'], $request_parts['path']) !== 0) {
        return false;
    }

    if ($is_query_check) {
        parse_str($url_parts['query'], $url_query_output);
        parse_str($request_parts['query'], $request_query_output);
        check_endurl_variables($request_query_output);

        if (!empty($url_query_output)) {
            $diff = nv_array_diff_assoc($url_query_output, $request_query_output);
            if (!empty($diff)) {
                return false;
            }
        }

        if ($is_abs_check and !empty($request_query_output)) {
            $diff = nv_array_diff_assoc($request_query_output, $url_query_output);
            if (!empty($diff)) {
                return false;
            }
        }
    }

    return NV_MAIN_DOMAIN . $url_rewrite;
}

/**
 * Lấy biến canonical URL và kiểm soát việc chuyển hướng
 *
 * @param string $page_url    Đường dẫn tuyệt đối từ thư mục gốc đến trang
 * @param bool   $query_check So sánh query của $page_url với query của $_SERVER['REQUEST_URI']
 * @param bool   $abs_comp    So sánh tuyệt đối (true) hoặc chỉ cần có chứa (false)
 * @return string
 */
function getCanonicalUrl($page_url, $query_check = false, $abs_comp = false)
{
    global $home, $global_config;

    if ($home) {
        // Cắt tên miền nếu nó tồn tại trong $page_url
        $page_url = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, true);
        str_starts_with($page_url, NV_MY_DOMAIN) && $page_url = substr($page_url, strlen(NV_MY_DOMAIN));

        // Cắt tên miền nếu nó tồn tại trong $_SERVER['REQUEST_URI']
        $request_uri = $_SERVER['REQUEST_URI'];
        str_starts_with($request_uri, NV_MY_DOMAIN) && $request_uri = substr($request_uri, strlen(NV_MY_DOMAIN));
        if (!$query_check) {
            $request_uri = preg_replace('/\?.*/', '', $request_uri);
        }

        $check_url = $query_check ? $page_url : preg_replace('/\?.*/', '', $page_url);
        if ($request_uri != NV_BASE_SITEURL and $request_uri != $check_url) {
            nv_redirect_location($page_url);
        }

        return NV_MAIN_DOMAIN . $page_url;
    }

    if ($global_config['request_uri_check'] == 'not') {
        str_starts_with($page_url, NV_MY_DOMAIN) && $page_url = substr($page_url, strlen(NV_MY_DOMAIN));

        return urlRewriteWithDomain($page_url, NV_MAIN_DOMAIN);
    }

    $is_query_check = $is_abs_check = false;
    if ($global_config['request_uri_check'] != 'page') {
        if ($global_config['request_uri_check'] == 'query') {
            $is_query_check = true;
        } elseif ($global_config['request_uri_check'] == 'abs') {
            $is_query_check = true;
            $is_abs_check = true;
        }
    } else {
        if (!empty($query_check)) {
            $is_query_check = true;

            if (!empty($abs_comp)) {
                $is_abs_check = true;
            }
        }
    }

    $request_uri = '';
    $url = getPageUrl($page_url, $is_query_check, $is_abs_check, $request_uri);

    if (empty($url)) {
        nv_redirect_location($page_url);
    }

    return $url;
}

/**
 * nv_check_domain()
 *
 * @param string $domain
 * @return string
 */
function nv_check_domain($domain)
{
    $domain = NukeViet\Http\Http::filter_domain($domain);
    if (!empty($domain)) {
        return $domain;
    }

    if ($domain == NV_SERVER_NAME) {
        return $domain;
    }

    return '';
}

/**
 * nv_is_url()
 *
 * @param string $url
 * @param bool   $isInternal
 * @return bool
 */
function nv_is_url($url, $isInternal = false)
{
    if ($isInternal and str_starts_with($url, NV_BASE_SITEURL) and !preg_match('/^(http|https|ftp)\:\/\//i', $url)) {
        $url = NV_MY_DOMAIN . $url;
    }

    $url = nv_strtolower($url);

    if (filter_var($url, FILTER_VALIDATE_URL) === false) {
        return false;
    }

    $sanitizer = new NukeViet\Core\Sanitizer();
    if (!$sanitizer->xssValid($url)) {
        return false;
    }

    return NukeViet\Http\Http::parse_url($url, true);
}

/**
 * nv_check_url()
 *
 * @param string $url
 * @param bool   $isArray
 * @return bool|array
 */
function nv_check_url($url, $isArray = false)
{
    global $global_config, $nv_Lang;

    $res = [
        'url' => $url,
        'isvalid' => false,
        'code' => 0,
        'message' => ''
    ];

    if (empty($url)) {
        if ($isArray) {
            $res['message'] = 'Empty URL';

            return $res;
        }

        return false;
    }

    $url = str_replace(' ', '%20', $url);
    $url = nv_strtolower($url);

    if (filter_var($url, FILTER_VALIDATE_URL) === false) {
        if ($isArray) {
            $res['message'] = 'Invalid URL';

            return $res;
        }

        return false;
    }

    if (!NukeViet\Http\Http::parse_url($url, true)) {
        if ($isArray) {
            $res['message'] = 'Invalid URL';

            return $res;
        }

        return false;
    }

    $args = [
        'headers' => [
            'Referer' => $url
        ],
        'nobody' => true
    ];

    $NV_Http = new NukeViet\Http\Http($global_config);
    $result = $NV_Http->get($url, $args);

    $error = '';
    if (!empty(NukeViet\Http\Http::$error)) {
        $error = nv_http_get_lang(NukeViet\Http\Http::$error);
    } elseif (is_object($result) and isset($result->error) and !empty($result->error)) {
        $error = $result->error;
    } elseif (empty($result['response'])) {
        $error = $nv_Lang->getGlobal('error_valid_response');
    } elseif ($result['response']['code'] != 200) {
        $error = !empty($result['response']['message']) ? $result['response']['message'] : $result['response']['code'];
    }

    if (!empty($error)) {
        if ($isArray) {
            $res['code'] = (!is_object($result) and isset($result['response']['code'])) ? $result['response']['code'] : 0;
            $res['message'] = $error;

            return $res;
        }

        return false;
    }

    if ($isArray) {
        $res['isvalid'] = true;
        $res['code'] = $result['response']['code'] ?? 0;
        $res['message'] = 'OK';

        return $res;
    }

    return true;
}

/**
 * url_get_contents()
 *
 * @param mixed $url
 * @return mixed
 */
function url_get_contents($url)
{
    global $global_config;

    if (!nv_is_url($url)) {
        return false;
    }

    $userAgents = [
        'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0',
        'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)',
        'Mozilla/4.8 [en] (Windows NT 6.0; U)',
        'Opera/9.25 (Windows NT 6.0; U; en)'
    ];
    mt_srand(microtime(true) * 1000000);
    $rand = array_rand($userAgents);
    $agent = $userAgents[$rand];

    $args = [
        'headers' => [
            'Referer' => $url,
            'User-Agent' => $agent
        ]
    ];

    $Http = new NukeViet\Http\Http($global_config);
    $result = $Http->get($url, $args);
    if (NukeViet\Http\Http::$error) {
        return false;
    }

    return $result['body'];
}

/**
 * is_localhost()
 *
 * @return bool
 */
function is_localhost()
{
    if (in_array(NV_CLIENT_IP, ['127.0.0.1', '::1'], true)) {
        return true;
    }

    return (bool) (NV_SERVER_NAME == 'localhost' or substr(NV_SERVER_NAME, 0, 3) == '10.' or substr(NV_SERVER_NAME, 0, 7) == '192.168');
}

/**
 * @param string $buffer
 * @param bool   $is_url
 * @return string
 */
function nv_url_rewrite($buffer, $is_url = false)
{
    global $global_config;

    $areas = [];
    if ($global_config['rewrite_enable']) {
        $areas[] = preg_quote(NV_BASE_SITEURL, '#');
    }
    if ($global_config['admin_rewrite']) {
        $areas[] = preg_quote(NV_BASE_ADMINURL, '#');
    }
    if (empty($areas)) {
        return $buffer;
    }

    $is_url && ($buffer = '"' . $buffer . '"');
    $buffer = preg_replace_callback('#"(' . implode('|', $areas) . ')index.php\\?' . preg_quote(NV_LANG_VARIABLE, '#') . '=([^"]+)"#', 'nv_url_rewrite_callback', $buffer);
    $is_url && ($buffer = substr($buffer, 1, -1));

    return $buffer;
}

/**
 * @param string $matches
 * @return string
 */
function nv_url_rewrite_callback($matches)
{
    global $global_config;

    $query_string = NV_LANG_VARIABLE . '=' . $matches[2];
    $query_array = [];
    $is_amp = str_contains($query_string, '&amp;');
    $is_acp = $matches[1] == NV_BASE_ADMINURL;
    parse_str(str_replace('&amp;', '&', $query_string), $query_array);

    if (!empty($query_array)) {
        $op_rewrite = [];
        $op_rewrite_count = 0;
        $query_array_keys = array_keys($query_array);
        if (defined('NV_IS_GODADMIN') or defined('NV_IS_SPADMIN')) {
            $allow_langkeys = $global_config['setup_langs'];
        } else {
            $allow_langkeys = $global_config['allow_sitelangs'];
        }
        if (!in_array($query_array[NV_LANG_VARIABLE], $allow_langkeys, true) or (isset($query_array[NV_NAME_VARIABLE]) and (!isset($query_array_keys[1]) or $query_array_keys[1] != NV_NAME_VARIABLE)) or (isset($query_array[NV_OP_VARIABLE]) and (!isset($query_array_keys[2]) or $query_array_keys[2] != NV_OP_VARIABLE))) {
            return $matches[0];
        }
        if (!$global_config['rewrite_optional'] or $is_acp) {
            $op_rewrite[] = $query_array[NV_LANG_VARIABLE];
            ++$op_rewrite_count;
        }
        unset($query_array[NV_LANG_VARIABLE]);
        if (isset($query_array[NV_NAME_VARIABLE])) {
            if (str_contains($query_array[NV_NAME_VARIABLE], '/')) {
                if (isset($query_array[NV_OP_VARIABLE])) {
                    return $matches[0];
                }
                $name_variable = explode('/', $query_array[NV_NAME_VARIABLE]);
                $query_array[NV_NAME_VARIABLE] = $name_variable[0];
                unset($name_variable[0]);
                $query_array[NV_OP_VARIABLE] = implode('/', $name_variable);
            }
            if ($global_config['rewrite_op_mod'] != $query_array[NV_NAME_VARIABLE] or $is_acp) {
                $op_rewrite[] = $query_array[NV_NAME_VARIABLE];
                ++$op_rewrite_count;
            }
            unset($query_array[NV_NAME_VARIABLE]);
        }
        if (isset($query_array[NV_OP_VARIABLE]) and $query_array[NV_OP_VARIABLE] == 'main') {
            unset($query_array[NV_OP_VARIABLE]);
        }
        $rewrite_end = $global_config['rewrite_endurl'];
        if (isset($query_array[NV_OP_VARIABLE])) {
            if (preg_match('/^tag\/(.*)$/', $query_array[NV_OP_VARIABLE], $m)) {
                if (str_contains($m[1], '/') and !preg_match('/page\-[0-9]+$/', $m[1])) {
                    return $matches[0];
                }
                $rewrite_end = '';
            } elseif (preg_match('/^[a-zA-Z0-9\-\/]+(' . nv_preg_quote($global_config['rewrite_exturl']) . ')*$/', $query_array[NV_OP_VARIABLE], $m)) {
                if (!empty($m[1])) {
                    $rewrite_end = '';
                }
            } else {
                return $matches[0];
            }
            $op_rewrite[] = $query_array[NV_OP_VARIABLE];
            ++$op_rewrite_count;
            unset($query_array[NV_OP_VARIABLE]);
        }

        $rewrite_string = nv_apply_hook('', 'get_rewrite_domain', [$op_rewrite_count, $op_rewrite, $rewrite_end, $query_array, $is_amp, $is_acp], '') . ($is_acp ? NV_BASE_ADMINURL : NV_BASE_SITEURL) . ($global_config['check_rewrite_file'] ? '' : 'index.php/') . implode('/', $op_rewrite) . ($op_rewrite_count ? $rewrite_end : '');

        if (!empty($query_array)) {
            $rewrite_string .= '?' . http_build_query($query_array, '', $is_amp ? '&amp;' : '&');
        }

        return '"' . $rewrite_string . '"';
    }

    return $matches[0];
}

/**
 * @param string $url
 * @param string $domain
 * @return string
 */
function urlRewriteWithDomain($url, $domain)
{
    global $nv_hooks;

    $url = nv_url_rewrite($url, true);

    if (!isset($nv_hooks['']['get_rewrite_domain'])) {
        return $domain . $url;
    }

    if (str_starts_with($url, $domain)) {
        return $url;
    }

    $url_info = parse_url($url);
    if (isset($url_info['scheme'], $url_info['host']) and defined('CUSTOM_REWRITE_DOMAIN')) {
        return $url;
    }
    $url = '';
    !empty($url_info['path']) && $url .= $url_info['path'];
    !empty($url_info['query']) && $url .= '?' . $url_info['query'];
    !empty($url_info['fragment']) && $url .= '#' . $url_info['fragment'];

    return $domain . $url;
}

/**
 * api_url_create()
 *
 * @param string $action
 * @param string $language
 * @param string $module
 * @param string $domain
 * @return false|string
 */
function api_url_create($action, $language = '', $module = '', $domain = '')
{
    global $global_config;

    if (empty($action) or !preg_match('/^[a-zA-Z0-9]+$/', $action)) {
        return false;
    }

    if (!empty($language) and !preg_match('/^[a-z]{2}$/', $language)) {
        $language = '';
    }

    if (!empty($module) and !preg_match('/^[a-zA-Z0-9]+$/', $module)) {
        $module = '';
    }

    if ($global_config['rewrite_enable']) {
        $url = nv_apply_hook('', 'get_rewrite_domain', [], '') . NV_BASE_SITEURL . 'api';
        !empty($language) && $url .= '/' . $language;
        !empty($module) && $url .= '/' . $module;
        $url .= '/' . $action;
        $url .= '/';

        if (empty($domain)) {
            return $url;
        }

        if (str_starts_with($url, $domain)) {
            return $url;
        }

        str_starts_with($url, NV_MY_DOMAIN) && $url = substr($url, strlen(NV_MY_DOMAIN));

        return $domain . $url;
    }

    $url = nv_apply_hook('', 'get_rewrite_domain', [], '') . NV_BASE_SITEURL . 'api.php';
    if (!empty($domain)) {
        if (!str_starts_with($url, $domain)) {
            str_starts_with($url, NV_MY_DOMAIN) && $url = substr($url, strlen(NV_MY_DOMAIN));
            $url = $domain . $url;
        }
    }

    $params = [];
    if (!empty($language)) {
        $params[NV_LANG_VARIABLE] = $language;
    }
    if (!empty($module)) {
        $params['module'] = $module;
    }
    $params['action'] = $action;

    $url .= '?' . http_build_query($params, '', '&');

    return $url;
}

/**
 * nv_change_buffer()
 *
 * @param string $buffer
 * @return string
 */
function nv_change_buffer($buffer)
{
    global $global_config, $client_info, $array_mod_title, $nv_Lang, $strdata;

    $script = 'script' . (defined('NV_SCRIPT_NONCE') ? ' nonce="' . NV_SCRIPT_NONCE . '"' : '');

    if (defined('NV_SYSTEM') and (defined('GOOGLE_ANALYTICS_SYSTEM') or (isset($global_config['googleAnalyticsID']) and preg_match('/^UA-\d{4,}-\d+$/', $global_config['googleAnalyticsID'])))) {
        $_google_analytics = '<' . $script . " data-show=\"inline\">(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){" . PHP_EOL;
        $_google_analytics .= '(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),' . PHP_EOL;
        $_google_analytics .= 'm=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)' . PHP_EOL;
        $_google_analytics .= "})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');" . PHP_EOL;
        if (isset($global_config['googleAnalyticsID']) and preg_match('/^UA-\d{4,}-\d+$/', $global_config['googleAnalyticsID'])) {
            $_google_analytics .= "ga('create', '" . $global_config['googleAnalyticsID'] . "', '" . $global_config['cookie_domain'] . "');" . PHP_EOL;
        }
        if (defined('GOOGLE_ANALYTICS_SYSTEM')) {
            /** @disregard P1011 */
            $_google_analytics .= "ga('create', '" . GOOGLE_ANALYTICS_SYSTEM . "', 'auto');" . PHP_EOL;
        }
        $_google_analytics .= "ga('send', 'pageview');" . PHP_EOL;
        $_google_analytics .= '</script>' . PHP_EOL;
        $buffer = preg_replace('/(<\/head[^>]*>)/', PHP_EOL . $_google_analytics . PHP_EOL . '$1', $buffer, 1);
    }

    if (defined('NV_SYSTEM') and isset($global_config['googleAnalytics4ID']) and (preg_match('/^UA-\d{4,}-\d+$/', $global_config['googleAnalytics4ID']) or preg_match('/^G\-[a-zA-Z0-9]{8,}$/', $global_config['googleAnalytics4ID']))) {
        $_google_analytics4 = '<' . $script . ' async src="https://www.googletagmanager.com/gtag/js?id=' . $global_config['googleAnalytics4ID'] . '"></script>' . PHP_EOL;
        $_google_analytics4 .= '<' . $script . ">window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date);gtag('config','" . $global_config['googleAnalytics4ID'] . "');</script>" . PHP_EOL;
        $buffer = preg_replace('/(<\/head[^>]*>)/', PHP_EOL . $_google_analytics4 . PHP_EOL . '$1', $buffer, 1);
    }

    if (defined('NV_SYSTEM') and !empty($global_config['google_tag_manager']) and preg_match('/^GTM-[A-Z0-9]{6,}$/', $global_config['google_tag_manager'])) {
        $buffer = preg_replace('/(<\/head[^>]*>)/', PHP_EOL . '<' . $script . ' data-show="inline">!function(e,t,a,n){e[n]=e[n]||[],e[n].push({"gtm.start":(new Date).getTime(),event:"gtm.js"});var g=t.getElementsByTagName(a)[0],m=t.createElement(a);m.async=!0,m.src="https://www.googletagmanager.com/gtm.js?id=' . $global_config['google_tag_manager'] . '",g.parentNode.insertBefore(m,g)}(window,document,"script","dataLayer");</script>' . PHP_EOL . '$1', $buffer, 1);
        $buffer = preg_replace('/(<body[^>]*>)/', '$1' . PHP_EOL . '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . $global_config['google_tag_manager'] . '" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>' . PHP_EOL, $buffer, 1);
    }

    if (NV_ANTI_IFRAME and empty($client_info['is_myreferer'])) {
        $buffer = preg_replace('/(<body[^>]*>)/', '$1' . PHP_EOL . '<' . $script . '>if(window.top!==window.self){document.write="";window.top.location=window.self.location;setTimeout(function(){document.body.innerHTML=""},1);window.self.onload=function(){document.body.innerHTML=""}};</script>', $buffer, 1);
    }

    /**
     * Bên ngoài site và các khu vực được load sau định nghĩa NV_MAIN_DOMAIN thực hiện các thao tác này
     * Tránh lỗi khi chưa được load đến NV_MAIN_DOMAIN đã chạy hàm nv_change_buffer
     * @link https://github.com/nukeviet/nukeviet/issues/3779
     */
    if (defined('NV_SYSTEM') and defined('NV_MAIN_DOMAIN')) {
        if ($client_info['is_bot'] or stripos(NV_USER_AGENT, 'google') !== false) {
            //  Cung cấp tên trang web cho Google Tìm kiếm
            // https://developers.google.com/search/docs/appearance/site-names?hl=vi#json-ld
            $typeWebSite = [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => $global_config['site_name']
            ];
            // Việc cung cấp tên website thay thế bằng thuộc tính alternateName
            // giúp Google xem xét các lựa chọn khác nếu tên bạn ưu tiên không được chọn
            if (!empty($global_config['custom_configs']['site_alternate_name'])) {
                $typeWebSite['alternateName'] = $global_config['custom_configs']['site_alternate_name'];
            }
            $typeWebSite['url'] = NV_MAIN_DOMAIN . '/';
            if (!preg_match('/^' . nv_preg_quote(NV_MY_DOMAIN) . '\/?$/', $client_info['selfurl'])) {
                // Thêm Hộp tìm kiếm liên kết trang web lên Google Search
                // https://developers.google.com/search/docs/appearance/structured-data/sitelinks-searchbox
                if (!empty($global_config['sitelinks_search_box_schema'])) {
                    $typeWebSite['potentialAction'] = [
                        '@type' => 'SearchAction',
                        'target' => [
                            '@type' => 'EntryPoint',
                            'urlTemplate' => NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=seek&amp;q=', true) . '{search_term_string}'
                        ],
                        'query-input' => 'required name=search_term_string'
                    ];
                }
            }
            $strdata[] = $typeWebSite;
            // Thêm biểu trưng của tổ chức lên Google Search
            // https://developers.google.com/search/docs/appearance/structured-data/logo
            if (!empty($global_config['organization_logo'])) {
                $strdata[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Organization',
                    'url' => NV_MAIN_DOMAIN,
                    'logo' => NV_MY_DOMAIN . NV_BASE_SITEURL . $global_config['organization_logo']
                ];
            }
            // Thêm đường dẫn breadcrumb của trang hiện tại lên Google Search
            // https://developers.google.com/search/docs/appearance/structured-data/breadcrumb
            if (!empty($global_config['breadcrumblist']) and !empty($array_mod_title)) {
                array_unshift($array_mod_title, [
                    'catid' => 0,
                    'title' => $nv_Lang->getGlobal('Home'),
                    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA
                ]);
                $breadcrumbs = [];
                $position = 0;
                foreach ($array_mod_title as $breadcrumb) {
                    ++$position;
                    $breadcrumbs[] = [
                        '@type' => 'ListItem',
                        'position' => $position,
                        'name' => $breadcrumb['title'],
                        'item' => NV_MY_DOMAIN . nv_url_rewrite($breadcrumb['link'], true)
                    ];
                }
                $strdata[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => $breadcrumbs
                ];
            }
            // Hiển thị thông tin doanh nghiệp trên Google Search
            // https://developers.google.com/search/docs/appearance/structured-data/local-business
            if (!empty($global_config['localbusiness'])) {
                if (file_exists(NV_ROOTDIR . '/' . NV_DATADIR . '/localbusiness.json')) {
                    $data = file_get_contents(NV_ROOTDIR . '/' . NV_DATADIR . '/localbusiness.json');
                    $data = json_decode($data, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $strdata[] = $data;
                    }
                }
            }
            if (!empty($strdata)) {
                if (count($strdata) == 1) {
                    $strdata = $strdata[0];
                }
                $strdata = json_encode($strdata, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                $strdata = '<script type="application/ld+json">' . PHP_EOL . $strdata . PHP_EOL . '</script>';
                $buffer = preg_replace('/(<\/head[^>]*>)/', PHP_EOL . $strdata . '$1', $buffer, 1);
            }
        }
    }

    return $buffer;
}

/**
 * parse_csp()
 *
 * @param string $json_csp
 * @return string
 */
function parse_csp($json_csp)
{
    global $nv_Cache, $global_config;

    $script_nonce = defined('NV_SCRIPT_NONCE') ? NV_SCRIPT_NONCE : '';
    $md5 = 'static_domains-' . $global_config['cdn_url'] . $global_config['nv_static_url'] . $global_config['assets_cdn_url'];
    $md5 = md5($md5);

    $cacheFile = 'csp_' . NV_CACHE_PREFIX . '.cache';
    if (($cache = $nv_Cache->getItem('settings', $cacheFile)) != false) {
        $_info = unserialize($cache);
        if (!empty($_info['md5']) and $_info['md5'] == $md5) {
            return preg_replace('/nonce\-([^\']+)/', 'nonce-' . $script_nonce, $_info['content']);
        }
    }

    $static_domains = array_map(function ($url) {
        if (empty($url)) {
            return '';
        }

        $url = preg_replace('/^(https?\:)?\/\//', '', $url);
        $url = str_replace('www.', '', $url);
        $url = parse_url('http://' . $url);

        return !empty($url['host']) ? $url['host'] : '';
    }, [$global_config['cdn_url'], $global_config['nv_static_url'], $global_config['assets_cdn_url']]);
    $static_domains = array_filter($static_domains);
    !empty($static_domains) && $static_domains = array_unique($static_domains);

    $static_csp = [];
    if (!empty($static_domains)) {
        foreach ($static_domains as $url) {
            $static_host_keys = ['default-src', 'script-src', 'style-src', 'img-src', 'font-src', 'connect-src', 'media-src', 'frame-src', 'form-action', 'manifest-src'];
            foreach ($static_host_keys as $key) {
                !isset($static_csp[$key]['hosts']) && $static_csp[$key]['hosts'] = [];
                $static_csp[$key]['hosts'][] = $url;
            }
        }
    }

    $csp_sources = [
        'none' => "'none'",
        'all' => '*',
        'self' => "'self'",
        'data' => 'data:',
        'unsafe-inline' => "'unsafe-inline'",
        'unsafe-eval' => "'unsafe-eval'"
    ];
    $_csp = json_decode($json_csp, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $_csp = [];
    }
    $keys = array_keys($_csp);
    foreach ($keys as $key) {
        if (!empty($_csp[$key]['hosts'])) {
            if (is_string($_csp[$key]['hosts'])) {
                $_csp[$key]['hosts'] = explode(' ', $_csp[$key]['hosts']);
                $_csp[$key]['hosts'] = array_filter($_csp[$key]['hosts']);
            }
        } else {
            unset($_csp[$key]['hosts']);
        }
    }
    !empty($static_csp) && $_csp = array_merge_recursive($_csp, $static_csp);
    $csp = [];
    if (!empty($_csp)) {
        foreach ($_csp as $directive => $sources) {
            $csp[$directive] = [];
            foreach ($sources as $source => $val) {
                if ($source != 'hosts') {
                    $csp[$directive][] = $csp_sources[$source];
                } else {
                    $csp[$directive][] = !empty($val) ? implode(' ', array_unique($val)) : '';
                }
            }
            $csp[$directive] = $directive . ' ' . implode(' ', $csp[$directive]);
        }
    }

    if (!empty($script_nonce)) {
        !isset($csp['script-src']) && $csp['script-src'] = 'script-src';
        $csp['script-src'] .= " 'nonce-" . $script_nonce . "' 'strict-dynamic'";
    }

    $content = implode('; ', $csp);
    $nv_Cache->setItem('settings', $cacheFile, serialize(['md5' => $md5, 'content' => $content]));

    return $content;
}

/**
 * nv_insert_logs()
 *
 * @param string $lang
 * @param string $module_name
 * @param string $name_key
 * @param string $note_action
 * @param int    $userid
 * @param string $link_acess
 * @return bool
 * @throws PDOException
 */
function nv_insert_logs($lang = '', $module_name = '', $name_key = '', $note_action = '', $userid = 0, $link_acess = '')
{
    global $db_config, $db;

    $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_logs
        (lang, module_name, name_key, note_action, link_acess, userid, log_time) VALUES
        (:lang, :module_name, :name_key, :note_action, :link_acess, :userid, ' . NV_CURRENTTIME . ')');
    $sth->bindParam(':lang', $lang, PDO::PARAM_STR);
    $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
    $sth->bindParam(':name_key', $name_key, PDO::PARAM_STR);
    $sth->bindParam(':note_action', $note_action, PDO::PARAM_STR, strlen($note_action));
    $sth->bindParam(':link_acess', $link_acess, PDO::PARAM_STR);
    $sth->bindParam(':userid', $userid, PDO::PARAM_INT);

    return (bool) ($sth->execute());
}

/**
 * nv_sys_mods()
 *
 * @param string $lang
 * @return array
 */
function nv_sys_mods($lang = '')
{
    global $nv_Cache, $db, $db_config;

    empty($lang) && $lang = NV_LANG_DATA;

    $cache_file = 'smods_' . NV_CACHE_PREFIX . '.cache';
    if (($cache = $nv_Cache->getItem('modules', $cache_file, $lang)) != false) {
        return unserialize($cache);
    }

    $sys_mods = [];
    try {
        $result = $db->query('SELECT m.*, f.func_id, f.func_name, f.alias, f.func_custom_name, f.func_site_title, f.description AS func_description, f.in_submenu, f.show_func FROM ' . $db_config['prefix'] . '_' . $lang . '_modules m LEFT JOIN ' . $db_config['prefix'] . '_' . $lang . '_modfuncs f ON m.title=f.in_module WHERE m.act = 1 ORDER BY m.weight, f.subweight');
        while ($row = $result->fetch()) {
            $m_title = $row['title'];
            $f_name = $row['func_name'];
            $f_alias = $row['alias'];
            if (!isset($sys_mods[$m_title])) {
                $sys_mods[$m_title] = [
                    'module_file' => $row['module_file'],
                    'module_data' => $row['module_data'],
                    'module_upload' => $row['module_upload'],
                    'module_theme' => $row['module_theme'],
                    'custom_title' => $row['custom_title'],
                    'site_title' => (empty($row['site_title'])) ? $row['custom_title'] : $row['site_title'],
                    'admin_title' => (empty($row['admin_title'])) ? $row['custom_title'] : $row['admin_title'],
                    'admin_file' => $row['admin_file'],
                    'main_file' => $row['main_file'],
                    'theme' => $row['theme'],
                    'mobile' => $row['mobile'],
                    'description' => $row['description'],
                    'keywords' => $row['keywords'],
                    'groups_view' => $row['groups_view'],
                    'is_modadmin' => false,
                    'admins' => $row['admins'],
                    'rss' => $row['rss'],
                    'sitemap' => $row['sitemap'],
                    'is_search' => file_exists(NV_ROOTDIR . '/modules/' . $row['module_file'] . '/search.php') ? 1 : 0,
                    'icon' => $row['icon'],
                    'funcs' => []
                ];
            }
            $sys_mods[$m_title]['funcs'][$f_alias] = [
                'func_id' => $row['func_id'],
                'func_name' => $f_name,
                'show_func' => $row['show_func'],
                'func_custom_name' => $row['func_custom_name'],
                'func_site_title' => empty($row['func_site_title']) ? $row['func_custom_name'] : $row['func_site_title'],
                'description' => $row['func_description'],
                'in_submenu' => $row['in_submenu']
            ];
            $sys_mods[$m_title]['alias'][$f_name] = $f_alias;
        }
        $cache = serialize($sys_mods);
        $nv_Cache->setItem('modules', $cache_file, $cache, $lang);
        unset($cache, $result);
    } catch (PDOException $e) {
        // trigger_error( $e->getMessage() );
    }

    return $sys_mods;
}

/**
 * nv_site_mods()
 *
 * @param string $lang
 * @return array|void
 */
function nv_site_mods($lang = '')
{
    global $admin_info, $global_config;

    if (empty($lang)) {
        global $sys_mods;
        $site_mods = $sys_mods;
    } else {
        $site_mods = nv_sys_mods($lang);
    }

    if (defined('NV_SYSTEM')) {
        foreach ($site_mods as $m_title => $row) {
            /*
             * Điều hành chung và quản trị module được xem module
             * mà không phụ thuộc vào thiết lập quyền xem
             */
            if (defined('NV_IS_SPADMIN')) {
                $site_mods[$m_title]['is_modadmin'] = true;
            } elseif (defined('NV_IS_ADMIN') and !empty($row['admins']) and !empty($admin_info['admin_id']) and in_array((int) $admin_info['admin_id'], array_map('intval', explode(',', $row['admins'])), true)) {
                $site_mods[$m_title]['is_modadmin'] = true;
            } elseif (!nv_user_in_groups($row['groups_view'])) {
                unset($site_mods[$m_title]);
            }
        }
        if (isset($site_mods['users'])) {
            if (defined('NV_IS_USER')) {
                $user_ops = [
                    'main',
                    'logout',
                    'editinfo',
                    'avatar',
                    'groups',
                    'security-privacy',
                    'verify-password'
                ];
            } else {
                $user_ops = [
                    'main',
                    'login',
                    'register',
                    'lostpass',
                    'r2s'
                ];
                if ($global_config['allowuserreg'] == 2 or $global_config['allowuserreg'] == 1) {
                    $user_ops[] = 'lostactivelink';
                    $user_ops[] = 'active';
                }
            }
            if (nv_user_in_groups($global_config['whoviewuser'])) {
                $user_ops[] = 'memberlist';
            }
            if (defined('NV_OPENID_ALLOWED')) {
                $user_ops[] = 'oauth';
            }
            $user_ops[] = 'datadeletion';
            $func_us = $site_mods['users']['funcs'];
            foreach ($func_us as $func => $row) {
                if (!in_array($func, $user_ops, true)) {
                    unset($site_mods['users']['funcs'][$func]);
                }
            }
        }
    } elseif (defined('NV_ADMIN')) {
        foreach ($site_mods as $m_title => $row) {
            if (!((defined('NV_IS_SPADMIN')) or (!empty($row['admins']) and in_array((int) $admin_info['admin_id'], array_map('intval', explode(',', $row['admins'])), true)))) {
                unset($site_mods[$m_title]);
            }
        }
    } else {
        return;
    }

    return $site_mods;
}

/**
 * nv_insert_notification()
 *
 * @param string    $module             module_name xảy ra thông báo
 * @param string    $type               loại thông báo, do module tùy ý đặt để xử lý
 * @param array     $content            dữ liệu tùy ý do module đặt
 * @param int       $obid               id đối tượng thông báo, tùy ý do module đặt
 * @param array|int $send_to            ID người nhận, bỏ trống nếu để người nhận là tất cả
 * @param int       $send_from          ID người tạo thông báo, để trống nếu là hệ thống
 * @param int       $area               xem mô tả bên dưới
 * @param int       $admin_view_allowed 0: Tất cả các admin, 1: Quản trị tối cao, 2: Điều hành chung + Quản trị tối cao
 * @param int       $logic_mode         0: 0 admin cấp trên thấy thông báo của cấp dưới, 1: Chỉ cấp đó được xem của cấp đó
 * @return int
 */
function nv_insert_notification($module, $type, $content = [], $obid = 0, $send_to = 0, $send_from = 0, $area = 1, $admin_view_allowed = 0, $logic_mode = 0)
{
    global $db, $global_config;

    /*
     * $area
     * 0: Khu vuc ngoai site
     * 1: Khu vuc quan tri
     * 2: Ca 2 khu vuc tren
     */

    $new_id = 0;
    if ($global_config['notification_active']) {
        $content = !empty($content) ? serialize($content) : '';

        $_sql = 'INSERT INTO ' . NV_NOTIFICATION_GLOBALTABLE . ' (
            admin_view_allowed, logic_mode, send_to, send_from, area, language, module, obid, type, content, add_time, view
        ) VALUES (
            :admin_view_allowed, :logic_mode, :send_to, :send_from, :area, ' . $db->quote(NV_LANG_DATA) . ',
            :module, :obid, :type, :content, ' . NV_CURRENTTIME . ', 0
        )';
        $data_insert = [];
        if (empty($send_to)) {
            $send_to = '';
        } elseif (is_array($send_to)) {
            $send_to = implode(',', array_map('intval', $send_to));
        } else {
            $send_to = (string) (int) $send_to;
        }
        $admin_view_allowed = (int) $admin_view_allowed;
        if ($admin_view_allowed < 0 or $admin_view_allowed > 2) {
            $admin_view_allowed = 0;
        }
        if ($logic_mode > 1 or $logic_mode < 0) {
            $logic_mode = 0;
        }
        $data_insert['admin_view_allowed'] = $admin_view_allowed;
        $data_insert['logic_mode'] = $logic_mode;
        $data_insert['send_to'] = $send_to;
        $data_insert['send_from'] = $send_from;
        $data_insert['area'] = $area;
        $data_insert['module'] = $module;
        $data_insert['obid'] = $obid;
        $data_insert['type'] = $type;
        $data_insert['content'] = $content;
        $new_id = $db->insert_id($_sql, 'id', $data_insert);
    }

    return $new_id;
}

/**
 * nv_delete_notification()
 *
 * @param string           $language
 * @param string           $module
 * @param string           $type
 * @param array|int|string $obid
 * @return true
 */
function nv_delete_notification($language, $module, $type, $obid)
{
    global $db, $global_config;

    $in = is_array($obid) ? implode(',', $obid) : $obid;
    if ($global_config['notification_active']) {
        try {
            $sth = $db->prepare('DELETE FROM ' . NV_NOTIFICATION_GLOBALTABLE . ' WHERE language = :language AND module = :module AND obid IN (' . $in . ') AND type = :type');
            $sth->bindParam(':language', $language, PDO::PARAM_STR);
            $sth->bindParam(':module', $module, PDO::PARAM_STR);
            $sth->bindParam(':type', $type, PDO::PARAM_STR);
            $sth->execute();
        } catch (PDOException $e) {
            trigger_error(print_r($e, true));
        }
    }

    return true;
}

/**
 * nv_status_notification()
 *
 * @param string $language
 * @param string $module
 * @param string $type
 * @param int    $obid
 * @param int    $status
 * @param int    $area
 * @return true
 * @throws PDOException
 */
function nv_status_notification($language, $module, $type, $obid, $status = 1, $area = 1)
{
    global $db, $global_config;

    if ($global_config['notification_active']) {
        $sth = $db->prepare('UPDATE ' . NV_NOTIFICATION_GLOBALTABLE . ' SET view = :view WHERE language = :language AND module = :module AND obid = :obid AND type = :type AND area = :area');
        $sth->bindParam(':view', $status, PDO::PARAM_INT);
        $sth->bindParam(':language', $language, PDO::PARAM_STR);
        $sth->bindParam(':module', $module, PDO::PARAM_STR);
        $sth->bindParam(':obid', $obid, PDO::PARAM_INT);
        $sth->bindParam(':type', $type, PDO::PARAM_STR);
        $sth->bindParam(':area', $area, PDO::PARAM_INT);
        $sth->execute();
    }

    return true;
}

/**
 * add_notification()
 * $args có thể chứa các phần tử:
 * receiver_grs (dạng mảng): Danh sách ID của các nhóm nhận thông báo
 * receiver_ids (dạng mảng): Danh sách ID của người dùng nhận thông báo.
 *                           Nếu có giá trị rỗng = tất cả người dùng
 * sender_role (dạng chuỗi): Gửi từ (gồm: system, group, admin)
 * sender_group (dạng số):   ID của nhóm gửi thông báo (sử dụng khi sender_role là group)
 * sender_admin (dạng số):   ID của admin gửi thông báo (sử dụng khi sender_role là admin)
 * isdef (dạng chuỗi):       Mã ngôn ngữ của tin nhắn mặc định (bắt buộc)
 * message (dạng mảng):      Nội dung thông báo (bắt buộc, có dạng ['mã ngôn ngữ 1' => 'Nội dung 1', 'mã ngôn ngữ 2' => 'Nội dung 2'])
 * link (dạng chuỗi):        Liên kết của thông báo
 * add_time (dạng số):       Thời gian đăng thông báo (0 = thời gian hiển thị đầu tiên)
 * exp_time (dạng số):       Thời gian hết hạn thông báo (0 = vô thời hạn)
 *
 * @param array $args
 * @return false|string
 */
function add_notification($args)
{
    global $global_config, $db;

    if (empty($global_config['inform_active'])) {
        return false;
    }

    $data = [
        'receiver_grs' => [],
        'receiver_ids' => [],
        'sender_role' => 'system',
        'sender_group' => 0,
        'sender_admin' => 0,
        'isdef' => '',
        'message' => [],
        'link' => '',
        'add_time' => NV_CURRENTTIME,
        'exp_time' => !empty($global_config['inform_default_exp']) ? (NV_CURRENTTIME + (int) $global_config['inform_default_exp']) : 0
    ];
    $data = array_merge($data, $args);

    if (empty($data['isdef']) or !in_array($data['isdef'], $global_config['setup_langs'], true) or empty($data['message']) or empty($data['message'][$data['isdef']]) or nv_strlen($data['message'][$data['isdef']]) < 3) {
        return false;
    }

    if (!(!empty($data['message']) and ($data['sender_role'] == 'system' or ($data['sender_role'] == 'group' and !empty($data['sender_group'])) or ($data['sender_role'] == 'admin' and !empty($data['sender_admin']))))) {
        return false;
    }

    $data['receiver_grs'] = !empty($data['receiver_grs']) ? implode(',', $data['receiver_grs']) : '';
    $data['sender_role'] == 'group' && $data['receiver_grs'] = '';
    $data['receiver_ids'] = !empty($data['receiver_ids']) ? implode(',', $data['receiver_ids']) : '';

    $contents = [];
    foreach ($data['message'] as $lang => $message) {
        if (nv_strlen($message) >= 3 and in_array($lang, $global_config['setup_langs'], true)) {
            $contents[$lang] = nv_nl2br(strip_tags($message, '<br>'), '<br/>');
        }
    }
    $data['message'] = json_encode([
        'isdef' => $data['isdef'],
        'contents' => $contents
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    if (!empty($data['link']) and !preg_match('#^https?\:\/\/#', $data['link'])) {
        str_starts_with($data['link'], NV_BASE_SITEURL) && $data['link'] = substr($data['link'], strlen(NV_BASE_SITEURL));
    }

    $sth = $db->prepare('INSERT INTO ' . NV_INFORM_GLOBALTABLE . ' (receiver_grs, receiver_ids, sender_role, sender_group, sender_admin, message, link, add_time, exp_time) VALUES
    (:receiver_grs, :receiver_ids, :sender_role, ' . $data['sender_group'] . ', ' . $data['sender_admin'] . ', :message, :link, ' . $data['add_time'] . ', ' . $data['exp_time'] . ')');
    $sth->bindValue(':receiver_grs', $data['receiver_grs'], PDO::PARAM_STR);
    $sth->bindValue(':receiver_ids', $data['receiver_ids'], PDO::PARAM_STR);
    $sth->bindValue(':sender_role', $data['sender_role'], PDO::PARAM_STR);
    $sth->bindValue(':message', $data['message'], PDO::PARAM_STR);
    $sth->bindValue(':link', $data['link'], PDO::PARAM_STR);
    $sth->execute();

    return $db->lastInsertId();
}

/**
 * nv_redirect_location()
 *
 * @param string $url
 * @param int    $error_code
 * @param bool   $noreferrer
 */
function nv_redirect_location($url, $error_code = 301, $noreferrer = false)
{
    if (is_int($error_code) and $error_code >= 100) {
        http_response_code($error_code);
    }
    if ($noreferrer) {
        header('Referrer-Policy: no-referrer');
    }
    $url = nv_apply_hook('', 'nv_redirect_location', [$url, $error_code, $noreferrer], $url);
    header('Location: ' . str_replace('&amp;', '&', nv_url_rewrite($url, true)));
    exit(0);
}

/**
 * nv_redirect_encrypt()
 *
 * @param string $url
 * @return string
 */
function nv_redirect_encrypt($url)
{
    global $crypt;
    return $crypt->encrypt(str_replace('&amp;', '&', $url), NV_CHECK_SESSION);
}

/**
 * nv_redirect_decrypt()
 *
 * @param string $string
 * @param bool   $insite
 * @return string
 */
function nv_redirect_decrypt($string, $insite = true)
{
    global $crypt;
    $url = $crypt->decrypt($string, NV_CHECK_SESSION);
    if (empty($url)) {
        return '';
    }

    if (preg_match('/^(http|https|ftp|gopher)\:\/\//i', $url)) {
        if ($insite and !preg_match('/^' . nv_preg_quote(NV_MY_DOMAIN) . '/', $url)) {
            return '';
        }

        if (!nv_is_url($url)) {
            return '';
        }
    } elseif (!nv_is_url(NV_MY_DOMAIN . $url)) {
        return '';
    }

    return $url;
}

/**
 * nv_get_redirect()
 *
 * @param string $mode
 * @param bool   $decode
 * @return string
 */
function nv_get_redirect($mode = 'post,get', $decode = false)
{
    global $nv_Request;

    $nv_redirect = '';
    if ($mode != 'post' and $mode != 'get') {
        $mode = 'post,get';
    }

    if ($nv_Request->isset_request('nv_redirect', $mode)) {
        $nv_redirect = $nv_Request->get_title('nv_redirect', $mode, '');

        $rdirect = nv_redirect_decrypt($nv_redirect);

        if ($decode) {
            return $rdirect;
        }

        if (empty($rdirect)) {
            $nv_redirect = '';
        }
    }

    return $nv_redirect;
}

/**
 * nv_set_authorization()
 *
 * @return array
 */
function nv_set_authorization()
{
    $auth_user = $auth_pw = '';
    if (nv_getenv('PHP_AUTH_USER')) {
        $auth_user = nv_getenv('PHP_AUTH_USER');
    } elseif (nv_getenv('REMOTE_USER')) {
        $auth_user = nv_getenv('REMOTE_USER');
    } elseif (nv_getenv('AUTH_USER')) {
        $auth_user = nv_getenv('AUTH_USER');
    } elseif (nv_getenv('HTTP_AUTHORIZATION')) {
        $auth_user = nv_getenv('HTTP_AUTHORIZATION');
    } elseif (nv_getenv('Authorization')) {
        $auth_user = nv_getenv('Authorization');
    }

    if (nv_getenv('PHP_AUTH_PW')) {
        $auth_pw = nv_getenv('PHP_AUTH_PW');
    } elseif (nv_getenv('REMOTE_PASSWORD')) {
        $auth_pw = nv_getenv('REMOTE_PASSWORD');
    } elseif (nv_getenv('AUTH_PASSWORD')) {
        $auth_pw = nv_getenv('AUTH_PASSWORD');
    }

    if (strcmp(substr($auth_user, 0, 6), 'Basic ') == 0) {
        $usr_pass = base64_decode(substr($auth_user, 6), true);
        if (!empty($usr_pass) and str_contains($usr_pass, ':')) {
            [$auth_user, $auth_pw] = explode(':', $usr_pass);
        }
        unset($usr_pass);
    }

    return [
        'auth_user' => $auth_user,
        'auth_pw' => $auth_pw
    ];
}

/**
 * Make an asynchronous POST request
 * Thực hiện yêu cầu POST không đồng bộ trong nội bộ site mà không cần chờ phản hồi
 * => Không ảnh hưởng, không trì hoãn tiến trình đang chạy
 *
 * post_async()
 *
 * @param string $url
 * @param array  $params
 * @param array  $headers
 */
function post_async($url, $params = [], $headers = [])
{
    if (!str_starts_with($url, NV_BASE_SITEURL)) {
        trigger_error('Invalid URL for post_async', E_USER_NOTICE);

        return false;
    }

    $headers['Referer'] = NV_MY_DOMAIN;
    empty($headers['User-Agent']) && $headers['User-Agent'] = NUKEVIET_USER_AGENT;

    $server_ip = nv_getenv('SERVER_ADDR');
    if (!empty($server_ip)) {
        if (filter_var($server_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $server_ip = '[' . $server_ip . ']';
        }
        $server_domain = NV_SERVER_PROTOCOL . '://' . $server_ip . NV_SERVER_PORT;
        $headers['Host'] = NV_SERVER_NAME;
    } elseif ($_SERVER['SERVER_NAME'] == 'localhost') {
        $server_domain = NV_SERVER_PROTOCOL . '://127.0.0.1' . NV_SERVER_PORT;
        $headers['Host'] = NV_SERVER_NAME;
    } else {
        $server_domain = NV_MY_DOMAIN;
    }

    if (!empty($params)) {
        ksort($params);
        $post_string = http_build_query($params);
    } else {
        $post_string = '';
    }

    $_headers = [];
    foreach ($headers as $name => $value) {
        $_headers[] = "{$name}: {$value}";
    }

    $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_ENCODING => '',
        CURLOPT_HEADER => false,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $post_string,
        CURLOPT_NOSIGNAL => 1,
        CURLOPT_HTTPHEADER => $_headers,
        CURLOPT_FRESH_CONNECT => true
    ];
    if (version_compare(PHP_VERSION, '7.16.2', '<')) {
        $options[CURLOPT_TIMEOUT] = NV_POST_ASYNC_TIMEOUT;
    } else {
        $options[CURLOPT_TIMEOUT_MS] = NV_POST_ASYNC_TIMEOUT_MS;
    }
    // Bỏ comment 2 dòng dưới nếu muốn kiểm tra tiến trình chạy curl
    // $options[CURLOPT_VERBOSE] = true;
    // $options[CURLOPT_STDERR] = fopen(NV_ROOTDIR . '/curl.txt', 'a+');

    $ch = curl_init($server_domain . $url);
    curl_setopt_array($ch, $options);
    curl_exec($ch);
    curl_close($ch);
}

/**
 * nv_local_api()
 *
 * @param string $cmd
 * @param string $params
 * @param string $adminidentity
 * @param string $module
 * @return mixed
 * @throws Exception
 */
function nv_local_api($cmd, $params, $adminidentity = '', $module = '')
{
    global $global_config, $sys_mods;

    // Default api trả về error
    $apiresults = new NukeViet\Api\ApiResult();

    /*
     * Kiểm tra nếu là API của module
     * API là kiểu chạy sau khi tài nguyên của hệ thống đã load
     * Do đó chỉ cần truyền module_name vào và căn cứ $sys_mods để lấy các thông tin còn lại
     * Khác với HOOK phải tuyền module_file vào để xác định
     */

    if (preg_match($global_config['check_module'], $module)) {
        if (!isset($sys_mods[$module])) {
            $apiresults->setCode(NukeViet\Api\ApiResult::CODE_MODULE_NOT_EXISTS)->setMessage('Module not exists!!!');

            return $apiresults->getResult();
        }

        $module_info = $sys_mods[$module];
        $module_file = $module_info['module_file'];
        $classname = 'NukeViet\\Module\\' . $module_file . '\\Api\\' . $cmd;
    } elseif ($module != '') {
        $apiresults->setCode(NukeViet\Api\ApiResult::CODE_MODULE_INVALID)->setMessage('Module is invalid!!!');

        return $apiresults->getResult();
    } else {
        $classname = 'NukeViet\\Api\\' . $cmd;
    }

    // Class tồn tại
    if (!class_exists($classname)) {
        $apiresults->setCode(NukeViet\Api\ApiResult::CODE_API_NOT_EXISTS)->setMessage('API not exists!!!');

        return $apiresults->getResult();
    }

    // Kiểm tra quyền hạn admin
    if (empty($adminidentity) and !defined('NV_IS_ADMIN')) {
        $apiresults->setCode(NukeViet\Api\ApiResult::CODE_NO_ADMIN_IDENT)->setMessage('Admin Ident is required if no admin logged!!!');

        return $apiresults->getResult();
    }
    if ($adminidentity) {
        global $db;
        if (is_numeric($adminidentity)) {
            $where = 'tb2.userid=' . (int) $adminidentity;
        } else {
            $where = 'tb2.username=' . $db->quote($adminidentity);
        }
        $sql = 'SELECT tb1.admin_id, tb1.lev, tb2.username FROM ' . NV_AUTHORS_GLOBALTABLE . ' tb1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' tb2
        ON tb1.admin_id=tb2.userid WHERE tb1.is_suspend=0 AND tb2.active=1 AND ' . $where;
        $admin_info = $db->query($sql)->fetch();
        if (empty($admin_info)) {
            $apiresults->setCode(NukeViet\Api\ApiResult::CODE_NO_ADMIN_FOUND)->setMessage('No admin found!!!');

            return $apiresults->getResult();
        }
        NukeViet\Api\Api::setAdminId($admin_info['admin_id']);
        NukeViet\Api\Api::setAdminLev($admin_info['lev']);
        NukeViet\Api\Api::setAdminName($admin_info['username']);
    } else {
        global $admin_info;
        NukeViet\Api\Api::setAdminId($admin_info['admin_id']);
        NukeViet\Api\Api::setAdminLev($admin_info['level']);
        NukeViet\Api\Api::setAdminName($admin_info['username']);
    }

    /*
     * Nếu API của module kiểm tra xem admin có phải là Admin module không
     * Nếu quản trị tối cao và điều hành chung thì nghiễm nhiên có quyền quản trị module
     */
    if ($module != '' and NukeViet\Api\Api::getAdminLev() > 2 and !in_array((int) NukeViet\Api\Api::getAdminId(), array_map('intval', explode(',', $sys_mods[$module]['admins'])), true)) {
        $apiresults->setCode(NukeViet\Api\ApiResult::CODE_NO_MODADMIN_RIGHT)->setMessage('Admin do not have the right to manage this module!!!');

        return $apiresults->getResult();
    }

    // Kiểm tra quyền thực thi API theo quy định của API
    if ($classname::getAdminLev() < NukeViet\Api\Api::getAdminLev()) {
        $apiresults->setCode(NukeViet\Api\ApiResult::CODE_ADMINLEV_NOT_ENOUGH)->setMessage('Admin level not enough to perform this api!!!');

        return $apiresults->getResult();
    }

    // Lưu thông tin module nếu là API của module để sử dụng trong API
    if ($module != '') {
        NukeViet\Api\Api::setModuleName($module);
        NukeViet\Api\Api::setModuleInfo($module_info);
    }

    // Sau khi đã xong tất cả các bước kiểm tra quyền thì tiến hành chạy API
    if (!is_array($params)) {
        $params = [];
    }

    $_POSTbackup = $_POST;
    $_POST = [];

    foreach ($params as $_key => $_value) {
        if (NukeViet\Api\Api::testParamKey($_key)) {
            $_POST[$_key] = $_value;
        }
    }

    // Thực hiện API
    $api = new $classname();
    $api->setResultHander($apiresults);
    $return = $api->execute();

    $_POST = $_POSTbackup;
    NukeViet\Api\Api::reset();

    return $return;
}

/**
 * DKIM_verify()
 *
 * @param string $domain
 * @param string $selector
 * @return bool
 */
function DKIM_verify($domain, $selector)
{
    $publickeyfile = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $selector . '_dkim.' . $domain . '.public.pem';
    $publickey = file_get_contents($publickeyfile);
    $publickey = preg_replace('/^-+.*?-+$/m', '', $publickey);
    $publickey = str_replace(["\r", "\n"], '', $publickey);
    $publickey = str_split($publickey, 253);
    $publickey = implode('', $publickey);

    $result = dns_get_record($selector . '._domainkey.' . $domain, DNS_TXT);

    if (empty($result[0]) or empty($result[0]['host']) or $result[0]['host'] != $selector . '._domainkey.' . $domain or empty($result[0]['txt'])) {
        return false;
    }

    $els = array_map('trim', explode(';', $result[0]['txt']));
    $els2 = [];
    foreach ($els as $el) {
        $el = array_map('trim', explode('=', $el, 2));
        $els2[$el[0]] = $el[1];
    }
    if (!empty($els2['p'])) {
        $els2['p'] = str_replace(["\r", "\n"], '', $els2['p']);
        $els2['p'] = str_split($els2['p'], 253);
        $els2['p'] = implode('', $els2['p']);
    }

    return !(!isset($els2['v']) or strcasecmp($els2['v'], 'dkim1') != 0 or !isset($els2['p']) or $els2['p'] != $publickey);
}

/**
 * nv_autoLinkDisable()
 * Disable email engines from automatically hyperlinking a URL
 *
 * @param string $text
 * @return string
 */
function nv_autoLinkDisable($text)
{
    $text = str_replace('&#x3A;', '<span>&#58;</span>', $text);

    return str_replace(['@', '.', ':'], ['<span>&#64;</span>', '<span>&#46;</span>', '<span>&#58;</span>'], $text);
}

/**
 * @param string $module      => Module khởi chạy, để trống là hệ thống
 * @param mixed  $tag         => Khóa
 * @param mixed  $args        => Tham số truyền vào
 * @param mixed  $default     => Dữ liệu mặc định trả về nếu hook không tồn tại
 * @param mixed  $return_type => Để trống thì dữ liệu trả về là giá trị cuối cùng. 1: Gộp array_merge. 2: Gộp array_merge_recursive
 */
function nv_apply_hook($module, $tag, $args = [], $default = null, $return_type = 0)
{
    global $nv_hooks, $sys_mods;

    // Kiểm tra module khởi chạy tồn tại
    if ((!empty($module) and !isset($sys_mods[$module])) or !isset($nv_hooks[$module][$tag])) {
        return $default;
    }

    $value = null;
    foreach ($nv_hooks[$module][$tag] as $priority_funcs) {
        foreach ($priority_funcs as $func) {
            // Thông tin module khởi chạy nếu có
            $from_data = [];
            if (isset($sys_mods[$module])) {
                $from_data['module_name'] = $module;
                $from_data['module_info'] = $sys_mods[$module];
            }

            // Thông tin module nhận dữ liệu nếu có
            $receive_data = [];
            if (isset($sys_mods[$func['module']])) {
                $receive_data['module_name'] = $func['module'];
                $receive_data['module_info'] = $sys_mods[$func['module']];
            }

            // Đưa tham số ID trong CSDL vào các biến
            $args['pid'] = $func['pid'];
            $_value = call_user_func_array($func['callback'], [&$args, $from_data, $receive_data]);
            if (!is_null($_value)) {
                if (empty($return_type)) {
                    $value = $_value;
                } elseif (is_array($_value)) {
                    if (is_null($value)) {
                        $value = [];
                    }
                    $value = ($return_type == 1 ? array_merge($_value, $value) : array_merge_recursive($_value, $value));
                }
            }
        }
    }
    if (is_null($value)) {
        return $default;
    }

    return $value;
}

/**
 * nv_add_hook()
 *
 * @param mixed  $module_name => Module xảy ra event
 * @param mixed  $tag         => TAG
 * @param int    $priority    => Ưu tiên
 * @param mixed  $callback    => Hàm chạy
 * @param string $hook_module => Module sử dụng dữ liệu
 * @param int    $pid         => ID quản lý trong CSDL
 */
function nv_add_hook($module_name, $tag, $priority, $callback, $hook_module = '', $pid = 0)
{
    global $nv_hooks;

    if (!isset($nv_hooks[$module_name])) {
        $nv_hooks[$module_name] = [];
    }
    if (!isset($nv_hooks[$module_name][$tag])) {
        $nv_hooks[$module_name][$tag] = [];
    }
    if (!isset($nv_hooks[$module_name][$tag][$priority])) {
        $nv_hooks[$module_name][$tag][$priority] = [];
    }

    $nv_hooks[$module_name][$tag][$priority][] = [
        'callback' => $callback,
        'module' => $hook_module,
        'pid' => $pid
    ];
}

/**
 * set_cdn_urls()
 *
 * @param mixed $global_config
 * @param mixed $cdn_is_enabled
 * @param mixed $cl_country
 */
function set_cdn_urls(&$global_config, $cdn_is_enabled, $cl_country)
{
    global $countries;

    // Không áp dụng CDN ở môi trường localhost
    if (is_localhost()) {
        $global_config['cdn_url'] = $global_config['nv_static_url'] = $global_config['assets_cdn_url'] = '';
    } else {
        // Chỉ áp dụng CDN khi bật hook và $global_config['cdn_url'] không rỗng
        if ($cdn_is_enabled and !empty($global_config['cdn_url'])) {
            // Nếu $global_config['cdn_url'] dạng mảng
            if (is_array($global_config['cdn_url'])) {
                // Áp dụng CDN theo quốc gia chỉ trong trường hợp quốc gia được xác định hợp lệ
                $set_country = false;
                if (isset($countries[$cl_country]) and ($cl_country != 'ZZ')) {
                    $set_country = true;
                }
                // Nếu quốc gia trong danh sách không kích hoạt CDN => loại trừ
                $except_countries = '';
                if (!empty($global_config['cdn_url']['except'][1])) {
                    $except_countries = is_array($global_config['cdn_url']['except'][1]) ? implode(' ', $global_config['cdn_url']['except'][1]) : $global_config['cdn_url']['except'][1];
                }
                if ($set_country and !empty($except_countries) and str_contains($except_countries, $cl_country)) {
                    $global_config['cdn_url'] = '';
                } else {
                    $urls = $global_config['cdn_url'];
                    unset($urls['except']);
                    $global_config['cdn_url'] = '';
                    foreach ($urls as $cdn => $vals) {
                        // Xác định CDN mặc định nếu nó chưa được định nghĩa
                        if (empty($global_config['cdn_url']) and !empty($vals[0])) {
                            $global_config['cdn_url'] = $cdn;
                        }
                        // Tìm CDN được chỉ định riêng cho quốc gia,
                        // nếu tìm ra thì dừng vòng lặp và áp dụng ngay
                        elseif (!empty($vals[1]) and $set_country) {
                            $inc_countries = is_array($vals[1]) ? implode(' ', $vals[1]) : $vals[1];
                            if (str_contains($inc_countries, $cl_country)) {
                                $global_config['cdn_url'] = $cdn;
                                break;
                            }
                        }
                    }
                }
            }
        } else {
            $global_config['cdn_url'] = '';
        }

        // Nếu bật CDN jsDelivr thì $global_config['assets_cdn_url']
        // được gán cho giá trị $global_config['core_cdn_url'] ghi trong /config.php
        $global_config['assets_cdn_url'] = !empty($global_config['assets_cdn']) ? (!empty($global_config['core_cdn_url']) ? $global_config['core_cdn_url'] : 'https://cdn.jsdelivr.net/gh/nukeviet/nukeviet/') : '';

        (!empty($global_config['nv_static_url']) && !preg_match('/^((https?\:)?\/\/)/', $global_config['nv_static_url'])) && $global_config['nv_static_url'] = '//' . $global_config['nv_static_url'];
        (!empty($global_config['cdn_url']) && !preg_match('/^((https?\:)?\/\/)/', $global_config['cdn_url'])) && $global_config['cdn_url'] = '//' . $global_config['cdn_url'];
        (!empty($global_config['assets_cdn_url']) && !preg_match('/^((https?\:)?\/\/)/', $global_config['assets_cdn_url'])) && $global_config['assets_cdn_url'] = '//' . $global_config['assets_cdn_url'];
    }
}

/**
 * nv_http_get_lang()
 *
 * @param array $input
 * @return string
 */
function nv_http_get_lang($input)
{
    global $nv_Lang;

    if (!isset($input['code']) or !isset($input['message'])) {
        return '';
    }

    if (!empty($nv_Lang->getGlobal('error_code_' . $input['code']))) {
        return $nv_Lang->getGlobal('error_code_' . $input['code']);
    }

    if (!empty($input['message'])) {
        return $input['message'];
    }

    return 'Error' . ($input['code'] ? ': ' . $input['code'] . '.' : '.');
}

/**
 * mhash_create()
 *
 * @param string $module
 * @param string $op
 * @return string
 */
function mhash_create($module, $op)
{
    return md5(NV_CHECK_SESSION . '_' . $module . '_' . $op);
}

/**
 * mload_url_generate()
 * Tạo URL truy vấn vào mload.php
 *
 * @param string $module     Tên module
 * @param string $op         Function của module
 * @param string $amp        Sử dụng & hay &amp; trong URL
 * @param bool   $checkuser  Có kết nối file kiểm tra tư cách user hay không
 * @param array  $other_data Các biến khác cần truyền vào URL
 * @return string
 */
function mload_url_generate($module, $op, $amp = '&amp;', $checkuser = false, $other_data = [])
{
    $url = NV_BASE_SITEURL . 'mload.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . $amp . NV_NAME_VARIABLE . '=' . $module . $amp . NV_OP_VARIABLE . '=' . $op . $amp . 'mhash=' . mhash_create($module, $op);
    if ($checkuser) {
        $url .= $amp . 'checkuser=1';
    }
    if (!empty($other_data)) {
        $url .= $amp . http_build_query($other_data, '', $amp);
    }

    return $url;
}

/**
 * csrf_create()
 * Hàm tạo mã CSRF
 *
 * @param string $key
 * @return string
 */
function csrf_create($key)
{
    $timestamp = NV_CURRENTTIME;

    return md5(NV_CHECK_SESSION . '_' . $key . '_' . $timestamp) . $timestamp;
}

/**
 * csrf_check()
 * Hàm kiểm tra mã CSRF
 *
 * @param string $csrf
 * @param string $key
 * @return bool
 */
function csrf_check($csrf, $key)
{
    $timestamp = substr($csrf, -10, 10);
    $timestamp = (int) $timestamp;
    $lifetime = 3600; // Thời lượng sống của mã CSRF, mặc định 60 phút
    if ($timestamp < (NV_CURRENTTIME - $lifetime) or $timestamp > NV_CURRENTTIME) {
        return false;
    }
    $expected = md5(NV_CHECK_SESSION . '_' . $key . '_' . $timestamp) . $timestamp;

    return hash_equals($expected, $csrf);
}

/**
 * Kiểm tra xem mật khẩu đã được xác thực cho khu vực area hay chưa
 *
 * @param string $area
 * @param string $module
 * @return bool
 */
function is_verified_password(string $area, string $module = ''): bool
{
    global $module_name, $nv_Request, $site_mods;

    empty($module) && $module = $module_name;
    if (!isset($site_mods[$module])) {
        return false;
    }

    $confirm_pwd = $nv_Request->get_string($site_mods[$module]['module_data'] . '_confirm_pwd', 'session', '');
    if (empty($confirm_pwd)) {
        return false;
    }

    $confirm_pwd = json_decode($confirm_pwd, true);
    if (
        !is_array($confirm_pwd) or !isset($confirm_pwd['time']) or
        (NV_CURRENTTIME - $confirm_pwd['time'] > 3600) or !isset($confirm_pwd['area']) or $confirm_pwd['area'] !== $area
        or !isset($confirm_pwd['csrf']) or !csrf_check($confirm_pwd['csrf'], $site_mods[$module]['module_data'] . '_confirm_pwd')
    ) {
        return false;
    }

    return true;
}

/**
 * Đặt trạng thái đã xác thực mật khẩu cho khu vực area
 *
 * @param string $area
 * @param string $module
 * @return void
 */
function set_verified_password(string $area, string $module = ''): void
{
    global $module_name, $nv_Request, $site_mods;

    empty($module) && $module = $module_name;
    if (!isset($site_mods[$module])) {
        return;
    }

    $nv_Request->set_Session($site_mods[$module]['module_data'] . '_confirm_pwd', json_encode([
        'time' => NV_CURRENTTIME,
        'area' => $area,
        'csrf' => csrf_create($site_mods[$module]['module_data'] . '_confirm_pwd')
    ]));
}

/**
 * Xóa trạng thái đã xác thực mật khẩu. Một hành động bảo mật
 * không được phép thực hiện đồng thời nhiều khu vực do đó khi xóa
 * thì xóa hết tất cả các khu vực theo module
 *
 * @param string $module
 * @return void
 */
function clear_verified_password(string $module = ''): void
{
    global $module_name, $nv_Request, $site_mods;

    empty($module) && $module = $module_name;
    if (!isset($site_mods[$module])) {
        return;
    }
    $nv_Request->unset_request($site_mods[$module]['module_data'] . '_confirm_pwd', 'session');
}

/**
 * Chuyển hướng đến trang xác thực mật khẩu
 *
 * @param string $area         Khu vực cần xác thực mật khẩu
 * @param string $redirect_url Url trở về sau khi xác thực thành công
 * @param bool   $go_direct    true: chuyển hướng ngay, false: trả về URL để chuyển hướng
 * @return string
 * @throws Exception
 */
function go_verified_password(string $area, string $redirect_url, bool $go_direct = true): string
{
    $url = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=verify-password&area=' . urlencode($area) . '&nv_redirect=' . nv_redirect_encrypt(nv_url_rewrite($redirect_url, true)), true);
    if ($go_direct) {
        nv_redirect_location($url);
    }
    return $url;
}

/**
 * parse_phone()
 *
 * @param mixed $phone
 * @return array
 */
function nv_parse_phone($phone)
{
    if (empty($phone)) {
        return [];
    }

    $_phones = explode('|', nv_unhtmlspecialchars($phone));
    $phones = [];
    foreach ($_phones as $phone) {
        if (preg_match("/^(.*)\s*\[([0-9\+\.\,\;\*\#]+)\]$/", $phone, $m)) {
            $phones[] = [nv_htmlspecialchars($m[1]), $m[2]];
        } else {
            $phones[] = [nv_htmlspecialchars(preg_replace("/\[[^\]]*\]/", '', $phone))];
        }
    }

    return $phones;
}

/**
 * @param int|array $emailid
 * @param string $lang ngôn ngữ nội dung và tiêu đề email
 * @return bool|mixed
 */
function nv_get_email_template($emailid, $lang = '')
{
    global $db, $global_config;

    if (empty($lang) or !in_array($lang, $global_config['setup_langs'], true)) {
        $lang = NV_LANG_INTERFACE;
    }

    $sql = 'SELECT sys_pids, pids, send_name, send_email, send_cc, send_bcc, attachments, is_plaintext, is_disabled,
    is_selftemplate, mailtpl, default_subject, default_content,
    ' . $lang . '_subject lang_subject, ' . $lang . '_content lang_content FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' WHERE ';
    if (is_array($emailid)) {
        $sql .= 'lang=:lang AND module_name=:module_name AND id=:id';
    } else {
        $sql .= 'emailid=:emailid';
    }

    $sth = $db->prepare($sql);
    if (is_array($emailid)) {
        $sth->bindValue(':lang', $emailid[2] ?? NV_LANG_DATA, PDO::PARAM_STR);
        $sth->bindValue(':module_name', $emailid[0] ?? '', PDO::PARAM_STR);
        $sth->bindValue(':id', $emailid[1] ?? 0, PDO::PARAM_INT);
    } else {
        $sth->bindParam(':emailid', $emailid, PDO::PARAM_INT);
    }

    $sth->execute();
    $email_data = $sth->fetch();
    if (empty($email_data)) {
        // Không
        return false;
    }

    $attachments = [];
    $email_data['attachments'] = explode(',', $email_data['attachments']);
    foreach ($email_data['attachments'] as $attachment) {
        if (is_file(NV_UPLOADS_REAL_DIR . '/emailtemplates/' . $attachment)) {
            $attachments[] = NV_UPLOADS_REAL_DIR . '/emailtemplates/' . $attachment;
        }
    }

    // Xác định tệp mẫu thư tùy biến
    $mailtpl = '';
    if (!empty($email_data['mailtpl']) and preg_match('/^([a-zA-Z0-9\-\_]+)\:([a-zA-Z0-9\-\_]+)\.tpl$/i', $email_data['mailtpl'], $matches)) {
        if ($matches[1] == 'assets') {
            $mailtpl = NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/tpl/' . $matches[2] . '.tpl';
        } else {
            $mailtpl = NV_ROOTDIR . '/themes/' . $matches[1] . '/system/' . $matches[2] . '.tpl';
        }
        if (!file_exists($mailtpl)) {
            $mailtpl = '';
        }
    }

    // Dữ liệu trả về
    $data = [
        'pids' => array_filter(array_unique(array_merge_recursive(explode(',', $email_data['sys_pids']), explode(',', $email_data['pids'])))),
        'from' => [$email_data['send_name'], $email_data['send_email']],
        'cc' => array_filter(explode(',', $email_data['send_cc'])),
        'bcc' => array_filter(explode(',', $email_data['send_bcc'])),
        'attachments' => $attachments,
        'subject' => empty($email_data['lang_subject']) ? $email_data['default_subject'] : $email_data['lang_subject'],
        'content' => empty($email_data['lang_content']) ? $email_data['default_content'] : $email_data['lang_content'],
        'is_disabled' => $email_data['is_disabled'],
        'is_plaintext' => $email_data['is_plaintext'],
        'is_selftemplate' => $email_data['is_selftemplate'],
        'mailtpl' => $mailtpl
    ];

    return $data;
}

/**
 * @param int|array $emailid
 * @param array     $data
 * @param string    $lang ngôn ngữ để lấy subject và body của email,
 *                        không chỉ ra thì lấy ngôn ngữ giao diện hiện hành.
 *                        Trường hợp gửi email bất đồng bộ cần chú ý nếu không chỉ ra nó sẽ luôn gửi
 *                        bằng ngôn ngữ mặc định của site
 * @param string    $attachments
 * @param boolean   $test_mode
 * @return bool|bool[]
 */
function nv_sendmail_from_template($emailid, $data = [], $lang = '', $attachments = '', $test_mode = false)
{
    global $global_config, $db, $nv_Lang;

    $email_data = nv_get_email_template($emailid, $lang);
    if ($email_data === false) {
        return false;
    }
    if ($email_data['is_disabled'] or empty($data)) {
        return true;
    }

    $args = [
        'mode' => 'FULL',
        'setpids' => $email_data['pids']
    ];
    $gconfigs = [
        'site_name' => $global_config['site_name'],
        'site_email' => $global_config['site_email'],
        'site_phone' => $global_config['site_phone']
    ];
    if ((empty($email_data['from'][0]) or empty($email_data['from'][1])) and !empty($lang) and $lang != NV_LANG_DATA and in_array($lang, $global_config['setup_langs'], true)) {
        // Gửi email ngôn ngữ khác thì lấy lại tên site trong CSDL
        $in = "'" . implode("', '", array_keys($gconfigs)) . "'";
        $result = $db->query('SELECT config_name, config_value FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE lang='" . $lang . "' AND module='global' AND config_name IN (" . $in . ')');
        while ($row = $result->fetch()) {
            $gconfigs[$row['config_name']] = $row['config_value'];
        }
    }
    if (empty($email_data['from'][0])) {
        $email_data['from'][0] = $gconfigs['site_name'];
    }
    if (empty($email_data['from'][1])) {
        $email_data['from'][1] = $gconfigs['site_email'];
    }
    if (!empty($attachments)) {
        $email_data['attachments'] = array_merge_recursive(array_unique(array_filter(array_map('trim', explode(',', $attachments)))));
    }
    $result = [];
    foreach ($data as $row) {
        try {
            !isset($row['data']) && $row['data'] = [];

            $simple_value = false;
            if (!empty($row['data_as_fields'])) {
                // Dùng luôn data làm trường dữ liệu
                $merge_fields = $row['data'];
                $simple_value = true;
            } else {
                $_args = array_merge($args, $row['data']);
                $merge_fields = nv_apply_hook('', 'get_email_merge_fields', $_args, [], 1);

                // Dùng data làm trường dữ liệu nếu không chọn hoặc trình xử lý dữ liệu trả rỗng
                if (empty($merge_fields) and !empty($row['data_def_fields'])) {
                    $merge_fields = $row['data'];
                    $simple_value = true;
                }
            }

            // Thêm một số biến global nếu chúng chưa chỉ ra trong $merge_fields
            foreach ($gconfigs as $key => $value) {
                if (!isset($merge_fields[$key])) {
                    if ($simple_value) {
                        $merge_fields[$key] = $value;
                    } else {
                        $merge_fields[$key] = [
                            'name' => $nv_Lang->getGlobal($key),
                            'data' => $value
                        ];
                    }
                }
            }

            $tpl = new \NukeViet\Template\NVSmarty();
            foreach ($merge_fields as $field_key => $field_value) {
                $tpl->assign($field_key, $simple_value ? $field_value : $field_value['data']);
            }

            // Dùng để xử lý cả biến $email_data trước khi gọi Smarty thực hiện
            $_email_data = nv_apply_hook('', 'get_email_data_before_fetch', [$emailid, $email_data, $merge_fields, $row], $email_data);

            $email_content = $tpl->fetch('string:' . $_email_data['content']);
            $email_subject = $tpl->fetch('string:' . $_email_data['subject']);
            if ($_email_data['is_plaintext']) {
                $email_content = nv_nl2br(strip_tags($email_content));
            } else {
                $email_content = preg_replace('/(["|\'])[\s]*' . nv_preg_quote(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/') . '/isu', '\\1' . NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/', $email_content);
            }

            // Dùng để xử lý nội dung email trước khi gửi
            $email_content = nv_apply_hook('', 'get_email_content_before_send', [$email_content, $_email_data, $row, $emailid], $email_content);
            $email_lang = $lang;
            if (is_array($email_content)) {
                $email_lang = $email_content[1];
                $email_content = $email_content[0];
            }
            if (!empty($row['from'])) {
                if (is_array($row['from'])) {
                    $_email_data['from'][0] = $row['from'][0];
                    $_email_data['from'][1] = $row['from'][1];
                } else {
                    $_email_data['from'][1] = $row['from'];
                }
            }

            $result[] = nv_sendmail($_email_data['from'], $row['to'], $email_subject, $email_content, implode(',', $_email_data['attachments']), false, $test_mode, $_email_data['cc'], $_email_data['bcc'], !$email_data['is_selftemplate'], [], $email_lang, $_email_data['mailtpl']);
        } catch (Throwable $e) {
            trigger_error(print_r($e, true));
            $result[] = false;
        }
    }

    if (!isset($result[1])) {
        return $result[0];
    }
    return $result;
}

/**
 * @param float $num
 * @param string $lang
 * @return string
 */
function nv_number_format(float $num, string $lang = '')
{
    global $nv_default_regions, $global_config;

    if (empty($lang)) {
        $lang = NV_LANG_INTERFACE;
    }
    $region = $global_config['region'][$lang] ?? $nv_default_regions[$lang] ?? $nv_default_regions['en'];
    $num = number_format($num, $region['decimal_length'], $region['decimal_symbol'], $region['thousand_symbol']);
    if (strpos($num, $region['decimal_symbol']) !== false) {
        if ($region['trailing_zero']) {
            $num = rtrim($num, '0');
            $num = rtrim($num, $region['decimal_symbol']);
        }
        if ($region['leading_zero']) {
            $num = ltrim($num, '0');
        }
    }

    return $num;
}

/**
 * @param float $num
 * @param string $lang
 * @return string
 */
function nv_currency_format(float $num, string $lang = '')
{
    global $nv_default_regions, $global_config;

    if (empty($lang)) {
        $lang = NV_LANG_INTERFACE;
    }
    $region = $global_config['region'][$lang] ?? $nv_default_regions[$lang] ?? $nv_default_regions['en'];
    $num = number_format($num, $region['currency_decimal_length'], $region['currency_decimal_symbol'], $region['currency_thousand_symbol']);
    if (strpos($num, $region['currency_decimal_symbol']) !== false) {
        if ($region['currency_trailing_zero']) {
            $num = rtrim($num, '0');
            $num = rtrim($num, $region['currency_decimal_symbol']);
        }
    }

    switch ($region['currency_display']) {
        case 3: $num = $region['currency_symbol'] . ' ' . $num; break;
        case 2: $num = $region['currency_symbol'] . $num; break;
        case 1: $num = $num . ' ' . $region['currency_symbol']; break;
        default: $num .= $region['currency_symbol'];
    }

    return $num;
}

/**
 * @param string $key
 * @param string $lang
 * @return null|string|number
 */
function nv_region_config(string $key, string $lang = '')
{
    global $nv_default_regions, $global_config;

    if (empty($lang)) {
        $lang = NV_LANG_INTERFACE;
    }
    $region = $global_config['region'][$lang] ?? $nv_default_regions[$lang] ?? $nv_default_regions['en'];
    return $region[$key] ?? null;
}

/**
 * Hàm lấy unixtime từ chuỗi get
 *
 * @param string $str
 * @param int $hh
 * @param int $mm
 * @param int $ss
 * @param string $lang
 * @param string $method
 * @return int
 */
function nv_d2u_get(string $str, ?int $hh = null, ?int $mm = null, ?int $ss = null, string $lang = '', string $method = 'get')
{
    if (empty($lang)) {
        $lang = NV_LANG_INTERFACE;
    }
    $format = nv_region_config($method == 'get' ? 'date_get' : 'date_post', $lang);
    $sep = substr($format, 1, 1);
    $arr_fmt = explode($sep, $format);
    $regex = [];
    $regex_data = [
        'd' => '([0-9]{1,2})',
        'm' => '([0-9]{1,2})',
        'Y' => '([0-9]{4})',
    ];
    $offset_d = $offset_m = $offset_y = 0;
    foreach ($arr_fmt as $offset => $fmt) {
        $regex[] = $regex_data[$fmt];
        switch ($fmt) {
            case 'd':
                $offset_d = $offset + 1;
                break;
            case 'm':
                $offset_m = $offset + 1;
                break;
            default:
                $offset_y = $offset + 1;
        }
    }
    $regex = '/^[\s]*' . implode(nv_preg_quote($sep), $regex) . '[\s]*(.*?)$/';
    if (!preg_match($regex, $str, $matches)) {
        return 0;
    }

    if (is_null($hh) or is_null($mm) or is_null($ss)) {
        // Tự tìm thêm ngày giờ nếu không chỉ định
        $hh = $mm = $ss = 0;
        if (!empty($matches[4]) and preg_match('/([0-9]{1,2})\:([0-9]{1,2})\:*([0-9]{0,2})/', $matches[4], $m)) {
            $hh = intval($m[1]);
            $mm = intval($m[2]);
            $ss = intval($m[3]);
        }
    }

    return (int) mktime($hh, $mm, $ss, intval($matches[$offset_m]), intval($matches[$offset_d]), intval($matches[$offset_y]));
}

/**
 * Hàm lấy unixtime từ chuỗi post
 *
 * @param string $str
 * @param int $hh
 * @param int $mm
 * @param int $ss
 * @param string $lang
 * @return int
 */
function nv_d2u_post(string $str, ?int $hh = null, ?int $mm = null, ?int $ss = null, string $lang = '')
{
    return nv_d2u_get($str, $hh, $mm, $ss, $lang, 'post');
}

/**
 * Chuyển unixtime sang chuỗi ngày tháng trong GET
 *
 * @param int $timestamp
 * @param string $lang
 * @param string $method
 * @return string
 */
function nv_u2d_get(?int $timestamp, string $lang = '', string $method = 'get')
{
    if (empty($timestamp)) {
        return '';
    }
    if (empty($lang)) {
        $lang = NV_LANG_INTERFACE;
    }
    $format = nv_region_config($method == 'get' ? 'date_get' : 'date_post', $lang);
    return nv_date($format, $timestamp);
}

/**
 * Chuyển unixtime sang chuỗi ngày tháng trong POST
 *
 * @param int $timestamp
 * @param string $lang
 * @return string
 */
function nv_u2d_post(?int $timestamp, string $lang = '')
{
    return nv_u2d_get($timestamp, $lang, 'post');
}

/**
 * Hàm tạo UUID v4
 * @see https://tools.ietf.org/html/rfc4122
 * @return string
 */
function nv_uuid4()
{
    $data = unpack('C*', random_bytes(16));
    $data[7] = ($data[7] & 0x0f) | 0x40;
    $data[9] = ($data[9] & 0x3f) | 0x80;
    $hex = array_map(fn($byte) => str_pad(dechex($byte), 2, '0', STR_PAD_LEFT), $data);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(implode('', $hex), 4));
}

/**
 * Hàm chuyển Unicode tổ hợp sang Unicode dựng sẵn
 * @param mixed $value
 * @return mixed
 */
function nv_compound_unicode($value)
{
    return NukeViet\Core\Request::compound_unicode($value);
}
