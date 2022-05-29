<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

use NukeViet\Api\Exception;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
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
    if (!is_array($a)) {
        $a = [
            $a
        ];
    }

    foreach ($a as $b) {
        if (isset($_SERVER[$b])) {
            return $_SERVER[$b];
        }
        if (isset($_ENV[$b])) {
            return $_ENV[$b];
        }
        if (@getenv($b)) {
            return @getenv($b);
        }
        if (function_exists('apache_getenv') and apache_getenv($b, true)) {
            return apache_getenv($b, true);
        }
    }

    return '';
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
    if ($proxy_blocker == 3 and $is_proxy != 'No') {
        return true;
    }

    return false;
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
        $size = $size / 1024;
        ++$i;
    }

    return number_format($size, 2) . ' ' . $iec[$i];
}

/**
 * nv_convertfromSec()
 *
 * @param int $sec
 * @return string
 */
function nv_convertfromSec($sec = 0)
{
    global $lang_global;

    $sec = (int) $sec;
    $min = 60;
    $hour = 3600;
    $day = 86400;
    $year = 31536000;

    if ($sec == 0) {
        return '';
    }
    if ($sec < $min) {
        return plural($sec, $lang_global['plural_sec']);
    }
    if ($sec < $hour) {
        return trim(plural(floor($sec / $min), $lang_global['plural_min']) . (($sd = $sec % $min) ? ' ' . nv_convertfromSec($sd) : ''));
    }
    if ($sec < $day) {
        return trim(plural(floor($sec / $hour), $lang_global['plural_hour']) . (($sd = $sec % $hour) ? ' ' . nv_convertfromSec($sd) : ''));
    }
    if ($sec < $year) {
        return trim(plural(floor($sec / $day), $lang_global['plural_day']) . (($sd = $sec % $day) ? ' ' . nv_convertfromSec($sd) : ''));
    }

    return trim(plural(floor($sec / $year), $lang_global['plural_year']) . (($sd = $sec % $year) ? ' ' . nv_convertfromSec($sd) : ''));
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
    global $lang_global, $global_config;

    $login = trim(strip_tags($login));

    if (empty($login)) {
        return $lang_global['username_empty'];
    }
    if (isset($login[$max])) {
        return sprintf($lang_global['usernamelong'], $max);
    }
    if (!isset($login[$min - 1])) {
        return sprintf($lang_global['usernameadjective'], $min);
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

            return $login != strip_punctuation($_login) ? $lang_global['unick_type_' . $type] : '';
            break;
        default:
            return '';
    }
    if (!preg_match($pattern, $login)) {
        return $lang_global['unick_type_' . $type];
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
    global $lang_global, $db_config, $db, $global_config;

    $pass = trim(strip_tags($pass));

    if (empty($pass)) {
        return $lang_global['password_empty'];
    }
    if (isset($pass[$max])) {
        return sprintf($lang_global['passwordlong'], $max);
    }
    if (!isset($pass[$min - 1])) {
        return sprintf($lang_global['passwordadjective'], $min);
    }

    $type = $global_config['nv_upass_type'];
    if ($type == 1) {
        if (!(preg_match('#[a-z]#ui', $pass) and preg_match('#[0-9]#u', $pass))) {
            return $lang_global['upass_type_' . $type];
        }
    } elseif ($type == 3) {
        if (!(preg_match('#[A-Z]#u', $pass) and preg_match('#[0-9]#u', $pass))) {
            return $lang_global['upass_type_' . $type];
        }
    } elseif ($type == 2) {
        if (!(preg_match('#[^A-Za-z0-9]#u', $pass) and preg_match('#[a-z]#ui', $pass) and preg_match('#[0-9]#u', $pass))) {
            return $lang_global['upass_type_' . $type];
        }
    } elseif ($type == 4) {
        if (!(preg_match('#[^A-Za-z0-9]#u', $pass) and preg_match('#[A-Z]#u', $pass) and preg_match('#[0-9]#u', $pass))) {
            return $lang_global['upass_type_' . $type];
        }
    }

    $password_simple = $db->query('SELECT content FROM ' . NV_USERS_GLOBALTABLE . "_config WHERE config='password_simple'")->fetchColumn();
    $password_simple = explode('|', $password_simple);
    if (in_array($pass, $password_simple, true)) {
        return $lang_global['upass_type_simple'];
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
    global $lang_global, $global_config;

    if (empty($mail)) {
        return $return ? [
            $lang_global['email_empty'],
            $mail
        ] : $lang_global['email_empty'];
    }

    if ($return) {
        $mail = nv_strtolower(strip_tags(trim($mail)));
    }

    // Email quy định ký tự @ xuất hiện 1 lần duy nhất
    if (substr_count($mail, '@') !== 1) {
        return $return ? [
            $lang_global['email_incorrect'],
            $mail
        ] : $lang_global['email_incorrect'];
    }

    // Cắt email ra làm hai phần để kiểm tra
    $_mail = explode('@', $mail);
    $_mail_user = $_mail[0];
    $_mail_domain = nv_check_domain($_mail[1]);

    if (empty($_mail_domain)) {
        return $return ? [
            $lang_global['email_incorrect'],
            $mail
        ] : $lang_global['email_incorrect'];
    }

    // Chuyển lại email từ Unicode domain thành IDNA ASCII
    $mail = $_mail_user . '@' . $_mail_domain;

    if (function_exists('filter_var') and filter_var($mail, FILTER_VALIDATE_EMAIL) === false) {
        return $return ? [
            $lang_global['email_incorrect'],
            $mail
        ] : $lang_global['email_incorrect'];
    }

    if (!preg_match($global_config['check_email'], $mail)) {
        return $return ? [
            $lang_global['email_incorrect'],
            $mail
        ] : $lang_global['email_incorrect'];
    }

    if (!preg_match('/\.([a-z0-9\-]+)$/', $mail)) {
        return $return ? [
            $lang_global['email_incorrect'],
            $mail
        ] : $lang_global['email_incorrect'];
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

    if ($type == 'recaptcha') {
        if (!empty($global_config['recaptcha_secretkey'])) {
            $NV_Http = new NukeViet\Http\Http($global_config, NV_TEMP_DIR);
            $request = [
                'secret' => $crypt->decrypt($global_config['recaptcha_secretkey']),
                'response' => $seccode,
                'remoteip' => $client_info['ip']
            ];
            $args = [
                'headers' => [
                    'Referer' => NV_MY_DOMAIN
                ],
                'body' => $request
            ];
            $array = $NV_Http->post('https://www.google.com/recaptcha/api/siteverify', $args);
            if (is_array($array) and !empty($array['body'])) {
                $jsonRes = (array) json_decode($array['body'], true);
                if (isset($jsonRes['success']) and ((bool) $jsonRes['success']) === true) {
                    return true;
                }
            }
        }

        return false;
    }
    mt_srand((float) microtime() * 1000000);
    $maxran = 1000000;
    $random = mt_rand(0, $maxran);

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
    $_arr_m[] = ($type == 2 or $type == 4) ? 3 : mt_rand(0, 2); // 2. Đặc biệt
    $_arr_m[] = ($type == 3 or $type == 4) ? 1 : mt_rand(0, 2); // 3. HOA

    $length = $length - 4;
    for ($k = 0; $k < $length; ++$k) {
        $_arr_m[] = ($type == 2 or $type == 4) ? mt_rand(0, 3) : mt_rand(0, 2);
    }

    $pass = '';
    foreach ($_arr_m as $m) {
        $chars = $array_chars[$m];
        $max = strlen($chars) - 1;
        $pass .= $chars[mt_rand(0, $max)];
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
            for ($i = 0, $count = sizeof($list); $i < $count; ++$i) {
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
    if (in_array(5, $groups_view, true)) {
        // Guest
        return true;
    }

    return false;
}

/**
 * nv_groups_add_user()
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
    $_mod_table = ($mod_data == 'users') ? NV_USERS_GLOBALTABLE : $db_config['prefix'] . '_' . $mod_data;
    $query = $db->query('SELECT COUNT(*) FROM ' . $_mod_table . ' WHERE userid=' . $userid);
    if ($query->fetchColumn()) {
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

            return true;
        } catch (PDOException $e) {
            if ($group_id <= 3) {
                $data = $db->query('SELECT data FROM ' . $_mod_table . '_groups_users WHERE group_id=' . $group_id . ' AND userid=' . $userid)->fetchColumn();
                $data = ($data != '') ? explode(',', $data) : [];
                $data[] = $global_config['idsite'];
                $data = implode(',', array_unique(array_map('intval', $data)));
                $db->query('UPDATE ' . $_mod_table . "_groups_users SET data = '" . $data . "' WHERE group_id=" . $group_id . ' AND userid=' . $userid);

                return true;
            }
        }
    }

    return false;
}

/**
 * nv_groups_del_user()
 *
 * @param int    $group_id
 * @param int    $userid
 * @param string $mod_data
 * @return bool
 */
function nv_groups_del_user($group_id, $userid, $mod_data = 'users')
{
    global $db, $db_config, $global_config;

    $_mod_table = ($mod_data == 'users') ? NV_USERS_GLOBALTABLE : $db_config['prefix'] . '_' . $mod_data;
    $row = $db->query('SELECT data, approved FROM ' . $_mod_table . '_groups_users WHERE group_id=' . $group_id . ' AND userid=' . $userid)->fetch();
    if (!empty($row)) {
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

        return true;
    }

    return false;
}

/**
 * nv_show_name_user()
 *
 * @param string $first_name
 * @param string $last_name
 * @param string $user_name
 * @return string
 */
function nv_show_name_user($first_name, $last_name, $user_name = '')
{
    global $global_config;

    $full_name = ($global_config['name_show']) ? $first_name . ' ' . $last_name : $last_name . ' ' . $first_name;
    $full_name = trim($full_name);

    return empty($full_name) ? $user_name : $full_name;
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
    global $lang_global;

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
        '/\[Sun\](\W|$)/' => $lang_global['sun'] . '$1',
        '/\[Mon\](\W|$)/' => $lang_global['mon'] . '$1',
        '/\[Tue\](\W|$)/' => $lang_global['tue'] . '$1',
        '/\[Wed\](\W|$)/' => $lang_global['wed'] . '$1',
        '/\[Thu\](\W|$)/' => $lang_global['thu'] . '$1',
        '/\[Fri\](\W|$)/' => $lang_global['fri'] . '$1',
        '/\[Sat\](\W|$)/' => $lang_global['sat'] . '$1',
        '/\[Jan\](\W|$)/' => $lang_global['jan'] . '$1',
        '/\[Feb\](\W|$)/' => $lang_global['feb'] . '$1',
        '/\[Mar\](\W|$)/' => $lang_global['mar'] . '$1',
        '/\[Apr\](\W|$)/' => $lang_global['apr'] . '$1',
        '/\[May\](\W|$)/' => $lang_global['may2'] . '$1',
        '/\[Jun\](\W|$)/' => $lang_global['jun'] . '$1',
        '/\[Jul\](\W|$)/' => $lang_global['jul'] . '$1',
        '/\[Aug\](\W|$)/' => $lang_global['aug'] . '$1',
        '/\[Sep\](\W|$)/' => $lang_global['sep'] . '$1',
        '/\[Oct\](\W|$)/' => $lang_global['oct'] . '$1',
        '/\[Nov\](\W|$)/' => $lang_global['nov'] . '$1',
        '/\[Dec\](\W|$)/' => $lang_global['dec'] . '$1',
        '/Sunday(\W|$)/' => $lang_global['sunday'] . '$1',
        '/Monday(\W|$)/' => $lang_global['monday'] . '$1',
        '/Tuesday(\W|$)/' => $lang_global['tuesday'] . '$1',
        '/Wednesday(\W|$)/' => $lang_global['wednesday'] . '$1',
        '/Thursday(\W|$)/' => $lang_global['thursday'] . '$1',
        '/Friday(\W|$)/' => $lang_global['friday'] . '$1',
        '/Saturday(\W|$)/' => $lang_global['saturday'] . '$1',
        '/January(\W|$)/' => $lang_global['january'] . '$1',
        '/February(\W|$)/' => $lang_global['february'] . '$1',
        '/March(\W|$)/' => $lang_global['march'] . '$1',
        '/April(\W|$)/' => $lang_global['april'] . '$1',
        '/May(\W|$)/' => $lang_global['may'] . '$1',
        '/June(\W|$)/' => $lang_global['june'] . '$1',
        '/July(\W|$)/' => $lang_global['july'] . '$1',
        '/August(\W|$)/' => $lang_global['august'] . '$1',
        '/September(\W|$)/' => $lang_global['september'] . '$1',
        '/October(\W|$)/' => $lang_global['october'] . '$1',
        '/November(\W|$)/' => $lang_global['november'] . '$1',
        '/December(\W|$)/' => $lang_global['december'] . '$1'
    ];

    return preg_replace(array_keys($replaces), array_values($replaces), $return);
}

/**
 * nv_monthname()
 *
 * @param int $i
 * @return string
 */
function nv_monthname($i)
{
    global $lang_global;

    --$i;
    $month_names = [
        $lang_global['january'],
        $lang_global['february'],
        $lang_global['march'],
        $lang_global['april'],
        $lang_global['may'],
        $lang_global['june'],
        $lang_global['july'],
        $lang_global['august'],
        $lang_global['september'],
        $lang_global['october'],
        $lang_global['november'],
        $lang_global['december']
    ];

    return isset($month_names[$i]) ? $month_names[$i] : '';
}

/**
 * nv_unhtmlspecialchars()
 *
 * @param string $string
 * @return string
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
    } else {
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
 * nv_get_keywords()
 *
 * @param string $content
 * @param int    $keyword_limit
 * @return string
 */
function nv_get_keywords($content, $keyword_limit = 20)
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
        $b = sizeof($content_array);

        for ($i = 0; $i < $b - 3; ++$i) {
            $key3 = $content_array[$i] . ' ' . $content_array[$i + 1] . ' ' . $content_array[$i + 2];
            $key2 = $content_array[$i] . ' ' . $content_array[$i + 1];

            if (array_search($key3, $array_keywords_3, true)) {
                $keywords_return[] = $key3;
                $i = $i + 2;
            } elseif (array_search($key2, $array_keywords_2, true)) {
                $keywords_return[] = $key2;
                $i = $i + 1;
            }

            $keywords_return = array_unique($keywords_return);
            if (sizeof($keywords_return) > $keyword_limit) {
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

    return implode(',', $keywords_return);
}

/**
 * nv_sendmail()
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
 */
function nv_sendmail($from, $to, $subject, $message, $files = '', $AddEmbeddedImage = false, $testmode = false, $cc = [], $bcc = [])
{
    global $global_config, $sys_info;

    $sm_parameters = [];

    if (empty($to)) {
        return $testmode ? 'No receiver' : false;
    }
    $sm_parameters['to'] = is_array($to) ? array_values($to) : [
        $to
    ];

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

    $sm_parameters['from_name'] = $global_config['site_name'];
    $sm_parameters['from_address'] = '';
    $sm_parameters['reply_name'] = $global_config['site_name'];
    $sm_parameters['reply_address'] = '';

    // Xác định thông tin người gửi, người nhận từ giá trị truyền vào
    if (empty($from)) {
        $sm_parameters['reply_address'] = $global_config['site_email'];
    } elseif (is_array($from)) {
        if (!empty($from[3])) {
            $sm_parameters['from_address'] = $from[3];
        }
        if (!empty($from[2])) {
            $sm_parameters['from_name'] = $from[2];
        }
        if (!empty($from[1])) {
            $sm_parameters['reply_address'] = $from[1];
        }
        if (!empty($from[0])) {
            $sm_parameters['reply_name'] = $from[0];
        }
    } else {
        $sm_parameters['reply_address'] = $from;
    }

    // Cố định người gửi người nhận hoặc chỉ định khi không có giá trị truyền vào
    if (!empty($global_config['sender_name']) and (empty($sm_parameters['from_name']) or $global_config['force_sender'])) {
        $sm_parameters['from_name'] = $global_config['sender_name'];
    }
    if (!empty($global_config['reply_name']) and (empty($sm_parameters['reply_name']) or $global_config['force_reply'])) {
        $sm_parameters['reply_name'] = $global_config['reply_name'];
    }
    if (!empty($global_config['reply_email']) and (empty($sm_parameters['reply_address']) or $global_config['force_reply'])) {
        $sm_parameters['reply_address'] = $global_config['reply_email'];
    }
    if (!empty($global_config['sender_email']) and $global_config['force_sender']) {
        $sm_parameters['from_address'] = $global_config['sender_email'];
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
                $sm_parameters['reply'][$_reply] = isset($sm_parameters['reply_name'][$_k]) ? $sm_parameters['reply_name'][$_k] : '';
            }
        }
    }

    $sm_parameters['subject'] = $subject;
    $sm_parameters['message'] = $message;
    $sm_parameters['logo_add'] = $AddEmbeddedImage;
    if (function_exists('nv_mailHTML')) {
        $sm_parameters['message'] = nv_mailHTML($sm_parameters['subject'], $sm_parameters['message']);
        $sm_parameters['logo_add'] = true;
    }
    $sm_parameters['message'] = nv_url_rewrite($sm_parameters['message']);
    $optimizer = new NukeViet\Core\Optimizer($sm_parameters['message'], NV_BASE_SITEURL);
    $sm_parameters['message'] = $optimizer->process(false);
    $sm_parameters['message'] = nv_unhtmlspecialchars($sm_parameters['message']);

    $sm_parameters['files'] = !empty($files) ? array_map('trim', explode(',', $files)) : [];
    $sm_parameters['testmode'] = $testmode;

    if (isset($global_config['other_sendmail_method']) and function_exists($global_config['other_sendmail_method'])) {
        return call_user_func($global_config['other_sendmail_method'], $sm_parameters);
    }

    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->SetLanguage(NV_LANG_INTERFACE);
        $mail->CharSet = $global_config['site_charset'];

        $mailer_mode = strtolower($global_config['mailer_mode']);
        if ($mailer_mode == 'smtp') {
            // SMTP
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Port = $global_config['smtp_port'];
            $mail->Host = $global_config['smtp_host'];
            $mail->Username = $global_config['smtp_username'];
            $mail->Password = $global_config['smtp_password'];

            $SMTPSecure = (int) $global_config['smtp_ssl'];
            switch ($SMTPSecure) {
                case 1:
                    $mail->SMTPSecure = 'ssl';
                    break;
                case 2:
                    $mail->SMTPSecure = 'tls';
                    break;
                default:
                    $mail->SMTPSecure = '';
            }
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => (bool) $global_config['verify_peer_ssl'],
                    'verify_peer_name' => (bool) $global_config['verify_peer_name_ssl'],
                    'allow_self_signed' => true
                ]
            ];

            if (empty($sm_parameters['from_address'])) {
                if (filter_var($global_config['smtp_username'], FILTER_VALIDATE_EMAIL)) {
                    $sm_parameters['from_address'] = $global_config['smtp_username'];
                } else {
                    $sm_parameters['from_address'] = $global_config['site_email'];
                }
            }
        } elseif ($mailer_mode == 'sendmail') {
            // Linux Mail
            $mail->IsSendmail();

            if (empty($sm_parameters['from_address'])) {
                if (isset($_SERVER['SERVER_ADMIN']) and !empty($_SERVER['SERVER_ADMIN']) and filter_var($_SERVER['SERVER_ADMIN'], FILTER_VALIDATE_EMAIL)) {
                    $sm_parameters['from_address'] = $_SERVER['SERVER_ADMIN'];
                } elseif (checkdnsrr($_SERVER['SERVER_NAME'], 'MX') || checkdnsrr($_SERVER['SERVER_NAME'], 'A')) {
                    $sm_parameters['from_address'] = 'webmaster@' . $_SERVER['SERVER_NAME'];
                } else {
                    $sm_parameters['from_address'] = $global_config['site_email'];
                }
            }
        } elseif ($mailer_mode == 'mail' and !in_array('mail', $sys_info['disable_functions'], true)) {
            // PHPmail
            $mail->IsMail();

            if (empty($sm_parameters['from_address'])) {
                if (($php_email = @ini_get('sendmail_from')) != '' and filter_var($php_email, FILTER_VALIDATE_EMAIL)) {
                    $sm_parameters['from_address'] = $php_email;
                } elseif (preg_match("/([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+/", ini_get('sendmail_path'), $matches) and filter_var($matches[0], FILTER_VALIDATE_EMAIL)) {
                    $sm_parameters['from_address'] = $matches[0];
                } elseif (checkdnsrr($_SERVER['SERVER_NAME'], 'MX') || checkdnsrr($_SERVER['SERVER_NAME'], 'A')) {
                    $sm_parameters['from_address'] = 'webmaster@' . $_SERVER['SERVER_NAME'];
                } else {
                    $sm_parameters['from_address'] = $global_config['site_email'];
                }
            }
        } else {
            return $testmode ? 'No mail mode' : false;
        }

        $mail->setFrom($sm_parameters['from_address'], nv_unhtmlspecialchars($sm_parameters['from_name']));

        if (!empty($sm_parameters['reply'])) {
            foreach ($sm_parameters['reply'] as $_m => $_n) {
                $mail->addReplyTo($_m, nv_unhtmlspecialchars($_n));
            }
        }

        foreach ($sm_parameters['to'] as $_to) {
            $mail->addAddress($_to);
        }

        if (!empty($sm_parameters['cc'])) {
            foreach ($sm_parameters['cc'] as $_m => $_n) {
                $mail->addCC($_m, nv_unhtmlspecialchars($_n));
            }
        }

        if (!empty($sm_parameters['bcc'])) {
            foreach ($sm_parameters['bcc'] as $_m => $_n) {
                $mail->addBCC($_m, nv_unhtmlspecialchars($_n));
            }
        }

        $mail->Subject = nv_unhtmlspecialchars($sm_parameters['subject']);
        // https://www.php.net/manual/en/function.mail.php
        // Lines should not be larger than 70 characters.
        $mail->WordWrap = 70;
        $mail->Body = $sm_parameters['message'];
        $mail->AltBody = strip_tags($message);
        $mail->IsHTML(true);
        $mail->XMailer = 'NukeViet CMS with PHPMailer';

        if ($sm_parameters['logo_add']) {
            $mail->AddEmbeddedImage(NV_ROOTDIR . '/' . $global_config['site_logo'], 'sitelogo', basename(NV_ROOTDIR . '/' . $global_config['site_logo']));
        }

        if (!empty($sm_parameters['files'])) {
            foreach ($sm_parameters['files'] as $file) {
                $mail->addAttachment($file);
            }
        }

        $smime_included = !empty($global_config['smime_included']) ? array_map('trim', explode(',', $global_config['smime_included'])) : [];
        if (!empty($smime_included) and in_array($mailer_mode, $smime_included, true)) {
            // This PHPMailer example shows S/MIME signing a message and then sending.
            // https://github.com/PHPMailer/PHPMailer/blob/master/examples/smime_signed_mail.phps
            $email_name = str_replace('@', '__', $sm_parameters['from_address']);
            $cert_key = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $email_name . '.key';
            $cert_crt = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $email_name . '.crt';
            $certchain_pem = file_exists(NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $email_name . '.pem') ? NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $email_name . '.pem' : '';
            if (file_exists($cert_key) and file_exists($cert_crt)) {
                $mail->sign(
                    $cert_crt, // The location of your certificate file
                    $cert_key, // The location of your private key file
                    // The password you protected your private key with (not the Import Password!
                    // May be empty but the parameter must not be omitted!
                    '',
                    $certchain_pem // The location of your chain file
                );
            }
        }

        $dkim_included = !empty($global_config['dkim_included']) ? array_map('trim', explode(',', $global_config['dkim_included'])) : [];
        if (!empty($dkim_included) and in_array($mailer_mode, $dkim_included, true)) {
            // https://github.com/PHPMailer/PHPMailer/blob/master/examples/DKIM_sign.phps
            $domain = substr(strstr($sm_parameters['from_address'], '@'), 1);
            $privatekeyfile = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/nv_dkim.' . $domain . '.private.pem';
            $verifiedkey = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/nv_dkim.' . $domain . '.verified';
            if (file_exists($verifiedkey)) {
                $verifiedTime = file_get_contents($verifiedkey);
                $verifiedTime = (int) $verifiedTime + 604800;
                if (NV_CURRENTTIME > $verifiedTime) {
                    $verified = DKIM_verify($domain, 'nv');
                    if (!$verified) {
                        @unlink($verifiedkey);
                    } else {
                        $verifiedTime = NV_CURRENTTIME;
                        file_put_contents($verifiedkey, $verifiedTime, LOCK_EX);
                    }
                }
                if (NV_CURRENTTIME <= $verifiedTime and file_exists($privatekeyfile)) {
                    $mail->DKIM_domain = $domain;
                    $mail->DKIM_private = $privatekeyfile;
                    $mail->DKIM_selector = 'nv';
                    $mail->DKIM_passphrase = '';
                    $mail->DKIM_identity = $sm_parameters['from_address'];
                    $mail->DKIM_copyHeaderFields = false;
                    $mail->DKIM_extraHeaders = ['List-Unsubscribe', 'List-Help'];
                }
            }
        }

        if (!$mail->Send()) {
            if (!$testmode and !empty($global_config['notify_email_error'])) {
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
        $prevPage = NV_MAIN_DOMAIN . nv_url_rewrite($base_url . ($prev > 1 ? ($urlappend . $prev) : ''), true);
    }

    if ($page >= 1 and $page < $total) {
        $next = $page + 1;
        $nextPage = NV_MAIN_DOMAIN . nv_url_rewrite($base_url . $urlappend . $next, true);
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
    global $lang_global, $theme_config;

    $ul_class = isset($theme_config['pagination']['ul_class']) ? $theme_config['pagination']['ul_class'] : 'pagination';
    $li_class = isset($theme_config['pagination']['li_class']) ? $theme_config['pagination']['li_class'] : 'page-item';
    $a_class = isset($theme_config['pagination']['a_class']) ? $theme_config['pagination']['a_class'] : 'page-link';

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
            $href = !$onclick ? 'href="' . $href . '"' : 'href="javascript:void(0)" onclick="' . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($href)) . "','" . $containerid . "')\"";
            $page_string .= '<li' . ($i == $on_page ? $li_active_class : $li_class) . '><a' . $a_class . ($i == $on_page ? ' href="javascript:void(0)"' : ' ' . $href) . '>' . $i . '</a></li>';
        }

        if ($total_pages > 3) {
            if ($on_page > 1 and $on_page < $total_pages) {
                if ($on_page > 5) {
                    $page_string .= '<li' . $li_disabled_class . '><span>...</span></li>';
                }

                $init_page_min = ($on_page > 4) ? $on_page : 5;
                $init_page_max = ($on_page < $total_pages - 4) ? $on_page : $total_pages - 4;

                for ($i = $init_page_min - 1; $i < $init_page_max + 2; ++$i) {
                    $href = ($i > 1) ? $base_url . $amp . $i : $base_url;
                    $href = !$onclick ? 'href="' . $href . '"' : 'href="javascript:void(0)" onclick="' . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($href)) . "','" . $containerid . "')\"";
                    $page_string .= '<li' . ($i == $on_page ? $li_active_class : $li_class) . '><a' . $a_class . ($i == $on_page ? ' href="javascript:void(0)"' : ' ' . $href) . '>' . $i . '</a></li>';
                }

                if ($on_page < $total_pages - 4) {
                    $page_string .= '<li' . $li_disabled_class . '><span>...</span></li>';
                }
            } else {
                $page_string .= '<li' . $li_disabled_class . '><span>...</span></li>';
            }

            for ($i = $total_pages - 2; $i < $total_pages + 1; ++$i) {
                $href = ($i > 1) ? $base_url . $amp . $i : $base_url;
                $href = !$onclick ? 'href="' . $href . '"' : 'href="javascript:void(0)" onclick="' . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($href)) . "','" . $containerid . "')\"";
                $page_string .= '<li' . ($i == $on_page ? $li_active_class : $li_class) . '><a' . $a_class . ($i == $on_page ? ' href="javascript:void(0)"' : ' ' . $href) . '>' . $i . '</a></li>';
            }
        }
    } else {
        for ($i = 1; $i < $total_pages + 1; ++$i) {
            $href = ($i > 1) ? $base_url . $amp . $i : $base_url;
            $href = !$onclick ? 'href="' . $href . '"' : 'href="javascript:void(0)" onclick="' . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($href)) . "','" . $containerid . "')\"";
            $page_string .= '<li' . ($i == $on_page ? $li_active_class : $li_class) . '><a' . $a_class . ($i == $on_page ? ' href="javascript:void(0)"' : ' ' . $href) . '>' . $i . '</a></li>';
        }
    }

    if ($add_prevnext_text) {
        if ($on_page > 1) {
            $href = ($on_page > 2) ? $base_url . $amp . ($on_page - 1) : $base_url;
            $href = !$onclick ? 'href="' . $href . '"' : 'href="javascript:void(0)" onclick="' . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($href)) . "','" . $containerid . "')\"";
            $page_string = '<li' . $li_class . '><a' . $a_class . ' ' . $href . ' title="' . $lang_global['pageprev'] . '">&laquo;</a></li>' . $page_string;
        } else {
            $page_string = '<li' . $li_disabled_class . '><a' . $a_class . ' href="javascript:void(0)">&laquo;</a></li>' . $page_string;
        }

        if ($on_page < $total_pages) {
            $href = ($on_page) ? $base_url . $amp . ($on_page + 1) : $base_url;
            $href = !$onclick ? 'href="' . $href . '"' : 'href="javascript:void(0)" onclick="' . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($href)) . "','" . $containerid . "')\"";
            $page_string .= '<li' . $li_class . '><a' . $a_class . ' ' . $href . ' title="' . $lang_global['pagenext'] . '">&raquo;</a></li>';
        } else {
            $page_string .= '<li' . $li_disabled_class . '><a' . $a_class . ' href="javascript:void(0)">&raquo;</a></li>';
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
    global $lang_global, $theme_config;

    $ul_class = isset($theme_config['pagination']['ul_class']) ? $theme_config['pagination']['ul_class'] : 'pagination';
    $li_class = isset($theme_config['pagination']['li_class']) ? $theme_config['pagination']['li_class'] : 'page-item';
    $a_class = isset($theme_config['pagination']['a_class']) ? $theme_config['pagination']['a_class'] : 'page-link';

    $li_active_class = ' class="' . $li_class . (!empty($li_class) ? ' ' : '') . 'active"';
    $li_disabled_class = ' class="' . $li_class . (!empty($li_class) ? ' ' : '') . 'disabled"';
    $ul_class = !empty($ul_class) ? ' class="' . $ul_class . '"' : '';
    $li_class = !empty($li_class) ? ' class="' . $li_class . '"' : '';
    $a_class = !empty($a_class) ? ' class="' . $a_class . '"' : '';

    $total_pages = ceil($num_items / $per_page);

    if ($total_pages < 2) {
        return '';
    }

    $title .= NV_TITLEBAR_DEFIS . $lang_global['page'];
    $page_string = ($on_page == 1) ? '<li' . $li_active_class . '><a' . $a_class . ' href="javascript:void(0)">1</a></li>' : '<li' . $li_class . '><a' . $a_class . ' rel="prev" title="' . $title . ' 1" href="' . $base_url . '">1</a></li>';

    if ($total_pages > 7) {
        if ($on_page < 4) {
            $init_page_max = ($total_pages > 2) ? 2 : $total_pages;
            for ($i = 2; $i <= $init_page_max; ++$i) {
                if ($i == $on_page) {
                    $page_string .= '<li' . $li_active_class . '><a' . $a_class . ' href="javascript:void(0)">' . $i . '</a></li>';
                } else {
                    $rel = ($i > $on_page) ? 'next' : 'prev';
                    $page_string .= '<li' . $li_class . '><a' . $a_class . ' rel="' . $rel . '" title="' . $title . ' ' . $i . '" href="' . $base_url . '/page-' . $i . '">' . $i . '</a></li>';
                }
            }
        }

        if ($on_page > 1 and $on_page < $total_pages) {
            if ($on_page > 3) {
                $page_string .= '<li' . $li_disabled_class . '><span>...</span></li>';
            }

            $init_page_min = ($on_page > 3) ? $on_page : 4;
            $init_page_max = ($on_page < $total_pages - 3) ? $on_page : $total_pages - 3;

            for ($i = $init_page_min - 1; $i < $init_page_max + 2; ++$i) {
                if ($i == $on_page) {
                    $page_string .= '<li' . $li_active_class . '><a' . $a_class . ' href="javascript:void(0)">' . $i . '</a></li>';
                } else {
                    $rel = ($i > $on_page) ? 'next' : 'prev';
                    $page_string .= '<li' . $li_class . '><a' . $a_class . ' rel="' . $rel . '" title="' . $title . ' ' . $i . '" href="' . $base_url . '/page-' . $i . '">' . $i . '</a></li>';
                }
            }

            if ($on_page < $total_pages - 3) {
                $page_string .= '<li' . $li_disabled_class . '><span>...</span></li>';
            }
        } else {
            $page_string .= '<li' . $li_disabled_class . '><span>...</span></li>';
        }

        $init_page_min = ($total_pages - $on_page > 3) ? $total_pages : $total_pages - 1;
        for ($i = $init_page_min; $i <= $total_pages; ++$i) {
            if ($i == $on_page) {
                $page_string .= '<li' . $li_active_class . '><a' . $a_class . ' href="javascript:void(0)">' . $i . '</a></li>';
            } else {
                $rel = ($i > $on_page) ? 'next' : 'prev';
                $page_string .= '<li' . $li_class . '><a' . $a_class . ' rel="' . $rel . '" title="' . $title . ' ' . $i . '" href="' . $base_url . '/page-' . $i . '">' . $i . '</a></li>';
            }
        }
    } else {
        for ($i = 2; $i < $total_pages + 1; ++$i) {
            if ($i == $on_page) {
                $page_string .= '<li' . $li_active_class . '><a' . $a_class . ' href="javascript:void(0)">' . $i . '</a><li>';
            } else {
                $rel = ($i > $on_page) ? 'next' : 'prev';
                $page_string .= '<li' . $li_class . '><a' . $a_class . ' rel="' . $rel . '" title="' . $title . ' ' . $i . '" href="' . $base_url . '/page-' . $i . '">' . $i . '</a></li>';
            }
        }
    }

    if ($add_prevnext_text) {
        if ($on_page > 1) {
            $href = ($on_page > 2) ? $base_url . '/page-' . ($on_page - 1) : $base_url;
            $page_string = '<li' . $li_class . '><a' . $a_class . ' rel="prev" title="' . $title . ' ' . ($on_page - 1) . '" href="' . $href . '">&laquo;</a></li>' . $page_string;
        } else {
            $page_string = '<li' . $li_disabled_class . '><a' . $a_class . ' href="javascript:void(0)">&laquo;</a></li>' . $page_string;
        }

        if ($on_page < $total_pages) {
            $page_string .= '<li' . $li_class . '><a' . $a_class . ' rel="next" title="' . $title . ' ' . ($on_page + 1) . '" href="' . $base_url . '/page-' . ($on_page + 1) . '">&raquo;</a></li>';
        } else {
            $page_string .= '<li' . $li_disabled_class . '><a' . $a_class . ' href="javascript:void(0)">&raquo;</a></li>';
        }
    }

    if ($full_theme !== true) {
        return $page_string;
    }

    return '<ul' . $ul_class . '>' . $page_string . '</ul>';
}

/**
 * getPageUrl()
 *
 * @param string $page_url
 * @param bool   $query_check
 * @param bool   $abs_comp
 * @return false|string
 */
function getPageUrl($page_url, $query_check, $abs_comp)
{
    $url_rewrite = nv_url_rewrite($page_url, true);
    str_starts_with($url_rewrite, NV_MY_DOMAIN) && $url_rewrite = substr($url_rewrite, strlen(NV_MY_DOMAIN));
    $url_rewrite_check = str_replace('&amp;', '&', $url_rewrite);
    $url_rewrite_check = urldecode($url_rewrite_check);
    $url_rewrite_check = preg_replace_callback('/[^:\/@?&=#]+/usD', function ($matches) {
        return urlencode($matches[0]);
    }, $url_rewrite_check);
    $url_parts = parse_url($url_rewrite_check);
    $url_parts['path'] = urldecode($url_parts['path']);
    !isset($url_parts['query']) && $url_parts['query'] = '';

    $request_uri = nv_url_rewrite($_SERVER['REQUEST_URI'], true);
    str_starts_with($request_uri, NV_MY_DOMAIN) && $request_uri = substr($request_uri, strlen(NV_MY_DOMAIN));
    $request_parts = parse_url($request_uri);
    $request_parts['path'] = urldecode($request_parts['path']);
    !isset($request_parts['query']) && $request_parts['query'] = '';

    if (empty($request_parts['path']) or strcmp($url_parts['path'], $request_parts['path']) !== 0) {
        return false;
    }

    if ($query_check) {
        parse_str($url_parts['query'], $url_query_output);
        parse_str($request_parts['query'], $request_query_output);

        if (!empty($url_query_output)) {
            $diff = nv_array_diff_assoc($url_query_output, $request_query_output);
            if (!empty($diff)) {
                return false;
            }
        }

        if ($abs_comp and !empty($request_query_output)) {
            $diff = nv_array_diff_assoc($request_query_output, $url_query_output);
            if (!empty($diff)) {
                return false;
            }
        }
    }

    return NV_MAIN_DOMAIN . $url_rewrite;
}

/**
 * getCanonicalUrl()
 *
 * @param string $page_url    Đường dẫn tuyệt đối từ thư mục gốc đến trang
 * @param bool   $query_check So sánh query của $page_url với query của $_SERVER['REQUEST_URI']
 * @param bool   $abs_comp    So sánh tuyệt đối (true) hoặc chỉ cần có chứa (false)
 * @return string
 */
function getCanonicalUrl($page_url, $query_check = false, $abs_comp = false)
{
    global $home;

    if ($home) {
        $page_url = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, true);
        str_starts_with($page_url, NV_MY_DOMAIN) && $page_url = substr($page_url, strlen(NV_MY_DOMAIN));

        $request_uri = nv_url_rewrite($_SERVER['REQUEST_URI'], true);
        str_starts_with($request_uri, NV_MY_DOMAIN) && $request_uri = substr($request_uri, strlen(NV_MY_DOMAIN));

        if ($request_uri != NV_BASE_SITEURL and $request_uri != $page_url) {
            nv_redirect_location($page_url);
        }

        return NV_MAIN_DOMAIN . $page_url;
    }

    $url = getPageUrl($page_url, $query_check, $abs_comp);
    if (empty($url)) {
        nv_redirect_location($page_url);
    }

    return $url;
}

/**
 * nv_check_domain()
 *
 * @param string $domain
 * @return string $domain_ascii
 */
function nv_check_domain($domain)
{
    if (preg_match("/^([a-z0-9](-*[a-z0-9])*)(\.([a-z0-9](-*[a-z0-9])*))*$/i", $domain) and preg_match('/^.{1,253}$/', $domain) and preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain)) {
        return $domain;
    }

    if ($domain == 'localhost') {
        return $domain;
    }

    if (filter_var($domain, FILTER_VALIDATE_IP)) {
        return $domain;
    }

    if (!empty($domain)) {
        if (function_exists('idn_to_ascii')) {
            $domain = idn_to_ascii($domain, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
        } else {
            $Punycode = new TrueBV\Punycode();
            try {
                $domain = $Punycode->encode($domain);
            } catch (\Exception $e) {
                $domain = '';
            }
        }

        if (preg_match('/^xn\-\-([a-z0-9\-\.]+)\.([a-z0-9\-]+)$/', $domain)) {
            return $domain;
        }

        if ($domain == NV_SERVER_NAME) {
            return $domain;
        }
    }

    return '';
}

/**
 * xssValid()
 *
 * @param string $value
 * @return bool
 */
function xssValid($value)
{
    $value = preg_replace('/%3A%2F%2F/', '', $value); // :// to empty
    $value = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $value);
    $value = preg_replace('/%u0([a-z0-9]{3})/i', '&#x\\1;', $value);
    $value = preg_replace('/%([a-z0-9]{2})/i', '&#x\\1;', $value);
    $value = str_ireplace(['&#x53;&#x43;&#x52;&#x49;&#x50;&#x54;', '&#x26;&#x23;&#x78;&#x36;&#x41;&#x3B;&#x26;&#x23;&#x78;&#x36;&#x31;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x36;&#x3B;&#x26;&#x23;&#x78;&#x36;&#x31;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x33;&#x3B;&#x26;&#x23;&#x78;&#x36;&#x33;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x32;&#x3B;&#x26;&#x23;&#x78;&#x36;&#x39;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x30;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x34;&#x3B;', '/*', '*/', '<!--', '-->', '<!-- -->', '&#x0A;', '&#x0D;', '&#x09;', ''], '', $value);

    $search = '/&#[xX]0{0,8}(21|22|23|24|25|26|27|28|29|2a|2b|2d|2f|30|31|32|33|34|35|36|37|38|39|3a|3b|3d|3f|40|41|42|43|44|45|46|47|48|49|4a|4b|4c|4d|4e|4f|50|51|52|53|54|55|56|57|58|59|5a|5b|5c|5d|5e|5f|60|61|62|63|64|65|66|67|68|69|6a|6b|6c|6d|6e|6f|70|71|72|73|74|75|76|77|78|79|7a|7b|7c|7d|7e);?/i';
    $value = preg_replace_callback($search, function ($m) {
        return chr(hexdec($m[1]));
    }, $value);

    $search = '/&#0{0,8}(33|34|35|36|37|38|39|40|41|42|43|45|47|48|49|50|51|52|53|54|55|56|57|58|59|61|63|64|65|66|67|68|69|70|71|72|73|74|75|76|77|78|79|80|81|82|83|84|85|86|87|88|89|90|91|92|93|94|95|96|97|98|99|100|101|102|103|104|105|106|107|108|109|110|111|112|113|114|115|116|117|118|119|120|121|122|123|124|125|126);?/i';
    $value = preg_replace_callback($search, function ($m) {
        return chr($m[1]);
    }, $value);

    $search = ['&#60', '&#060', '&#0060', '&#00060', '&#000060', '&#0000060', '&#60;', '&#060;', '&#0060;', '&#00060;', '&#000060;', '&#0000060;', '&#x3c', '&#x03c', '&#x003c', '&#x0003c', '&#x00003c', '&#x000003c', '&#x3c;', '&#x03c;', '&#x003c;', '&#x0003c;', '&#x00003c;', '&#x000003c;', '&#X3c', '&#X03c', '&#X003c', '&#X0003c', '&#X00003c', '&#X000003c', '&#X3c;', '&#X03c;', '&#X003c;', '&#X0003c;', '&#X00003c;', '&#X000003c;', '&#x3C', '&#x03C', '&#x003C', '&#x0003C', '&#x00003C', '&#x000003C', '&#x3C;', '&#x03C;', '&#x003C;', '&#x0003C;', '&#x00003C;', '&#x000003C;', '&#X3C', '&#X03C', '&#X003C', '&#X0003C', '&#X00003C', '&#X000003C', '&#X3C;', '&#X03C;', '&#X003C;', '&#X0003C;', '&#X00003C;', '&#X000003C;', '\x3c', '\x3C', '\u003c', '\u003C'];
    $value = str_ireplace($search, '<', $value);

    $search = [
        'expression' => '/e\s*x\s*p\s*r\s*e\s*s\s*s\s*i\s*o\s*n/si',
        'javascript' => '/j\s*a\s*v\s*a\s*s\s*c\s*r\s*i\s*p\s*t/si',
        'livescript' => '/l\s*i\s*v\s*e\s*s\s*c\s*r\s*i\s*p\s*t/si',
        'behavior' => '/b\s*e\s*h\s*a\s*v\s*i\s*o\s*r/si',
        'vbscript' => '/v\s*b\s*s\s*c\s*r\s*i\s*p\s*t/si',
        'script' => '/s\s*c\s*r\s*i\s*p\s*t/si',
        'applet' => '/a\s*p\s*p\s*l\s*e\s*t/si',
        'alert' => '/a\s*l\s*e\s*r\s*t/si',
        'document' => '/d\s*o\s*c\s*u\s*m\s*e\s*n\s*t/si',
        'write' => '/w\s*r\s*i\s*t\s*e/si',
        'cookie' => '/c\s*o\s*o\s*k\s*i\s*e/si',
        'window' => '/w\s*i\s*n\s*d\s*o\s*w/si',
        'data:' => '/d\s*a\s*t\s*a\s*\:/si'
    ];
    $value = preg_replace(array_values($search), array_keys($search), $value);
    if (preg_match('/(expression|javascript|behavior|vbscript|mocha|livescript)(\:*)/', $value)) {
        return false;
    }

    if (strcasecmp($value, strip_tags($value)) !== 0) {
        return false;
    }

    $disableCommands = [
        'base64_decode',
        'cmd',
        'passthru',
        'eval',
        'exec',
        'system',
        'fopen',
        'fsockopen',
        'file',
        'file_get_contents',
        'readfile',
        'unlink'
    ];
    if (preg_match('#(' . implode('|', $disableCommands) . ')(\s*)\((.*?)\)#si', $value)) {
        return false;
    }

    return true;
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

    if (!preg_match('/^(http|https|ftp)\:\/\//', $url)) {
        return false;
    }

    $url = nv_strtolower($url);

    if (!xssValid($url)) {
        return false;
    }

    if (!($parts = parse_url($url))) {
        return false;
    }

    $domain = (isset($parts['host'])) ? nv_check_domain($parts['host']) : '';
    if (empty($domain)) {
        return false;
    }

    if (isset($parts['user']) and !preg_match('/^([0-9a-z\-]|[\_])*$/', $parts['user'])) {
        return false;
    }

    if (isset($parts['pass']) and !preg_match('/^([0-9a-z\-]|[\_])*$/', $parts['pass'])) {
        return false;
    }

    if (isset($parts['path']) and !preg_match('/^[0-9a-z\+\-\_\/\&\=\#\.\,\;\%\\s\!\:]*$/', $parts['path'])) {
        return false;
    }

    if (isset($parts['query']) and !preg_match('/^[0-9a-z\+\-\_\/\?\&\=\#\.\,\;\%\\s\!]*$/', $parts['query'])) {
        return false;
    }

    return true;
}

/**
 * nv_check_url()
 *
 * @param string $url
 * @param bool   $isTriggerError
 * @param int    $is_200
 * @return bool
 */
function nv_check_url($url, $isTriggerError = true, $is_200 = 0)
{
    if (empty($url)) {
        return false;
    }

    $url = str_replace(' ', '%20', $url);
    $url = nv_strtolower($url);

    if (!preg_match('/^(http|https|ftp)\:\/\//', $url)) {
        return false;
    }

    if (!xssValid($url)) {
        return false;
    }

    if (!($url_info = parse_url($url))) {
        return false;
    }

    $domain = (isset($url_info['host'])) ? nv_check_domain($url_info['host']) : '';
    if (empty($domain)) {
        return false;
    }

    if (isset($paurl_inforts['user']) and !preg_match('/^([0-9a-z\-]|[\_])*$/', $url_info['user'])) {
        return false;
    }

    if (isset($url_info['pass']) and !preg_match('/^([0-9a-z\-]|[\_])*$/', $url_info['pass'])) {
        return false;
    }

    if (isset($url_info['path']) and !preg_match('/^[0-9a-z\+\-\_\/\&\=\#\.\,\;\%\\s\!\:]*$/', $url_info['path'])) {
        return false;
    }

    if (isset($url_info['query']) and !preg_match('/^[0-9a-z\+\-\_\/\?\&\=\#\.\,\;\%\\s\!]*$/', $url_info['query'])) {
        return false;
    }

    $allow_url_fopen = ini_get('allow_url_fopen') == '1' or strtolower(ini_get('allow_url_fopen')) == 'on';
    $isHttps = $url_info['scheme'] == 'https';

    if (nv_function_exists('curl_init') and nv_function_exists('curl_exec')) {
        $port = isset($url_info['port']) ? (int) $url_info['port'] : ($isHttps ? 443 : 80);

        $userAgents = [
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0',
            'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)',
            'Mozilla/4.8 [en] (Windows NT 6.0; U)',
            'Opera/9.25 (Windows NT 6.0; U; en)'
        ];

        $open_basedir = (ini_get('open_basedir') == '1' or strtolower(ini_get('open_basedir')) == 'on') ? 1 : 0;

        srand((float) microtime() * 10000000);
        $rand = array_rand($userAgents);
        $agent = $userAgents[$rand];
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_PORT, $port);

        if ($isHttps) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYSTATUS, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }

        if ($open_basedir) {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_USERAGENT, $agent);

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response === false) {
            if ($isTriggerError) {
                trigger_error(curl_error($curl), E_USER_WARNING);
            }

            return false;
        }
        $res = explode(PHP_EOL, $response);
    } elseif (nv_function_exists('get_headers') and $allow_url_fopen) {
        if ($isHttps) {
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ]);
        } else {
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\nAccept-Encoding: gzip, deflate, br\r\nUser-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0\r\n"
                ]
            ]);
        }

        $res = get_headers($url, 0, $context);
    } elseif (nv_function_exists('stream_socket_client') and nv_function_exists('fgets')) {
        $res = [];
        if ($isHttps) {
            $scheme = 'ssl://';
            $port = isset($url_info['port']) ? (int) $url_info['port'] : 443;
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ]);
        } else {
            $scheme = '';
            $port = isset($url_info['port']) ? (int) $url_info['port'] : 80;
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\nAccept-Encoding: gzip, deflate, br\r\nUser-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0\r\n"
                ]
            ]);
        }

        $fp = stream_socket_client($scheme . $url_info['host'] . ':' . $port, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);

        if (!$fp) {
            if ($isTriggerError) {
                trigger_error($errstr, E_USER_WARNING);
            }

            return false;
        }

        $path = !empty($url_info['path']) ? $url_info['path'] : '/';
        $path .= !empty($url_info['query']) ? '?' . $url_info['query'] : '';

        fwrite($fp, 'HEAD ' . $path . " HTTP/1.0\r\n");
        fwrite($fp, 'Host: ' . $url_info['host'] . ':' . $port . "\r\n");
        fwrite($fp, "Connection: close\r\n\r\n");

        while (!feof($fp)) {
            if ($header = trim(fgets($fp, 1024))) {
                $res[] = $header;
            }
        }
        @fclose($fp);
    } else {
        if ($isTriggerError) {
            trigger_error('error server no support check url', E_USER_WARNING);
        }

        return false;
    }

    if (empty($res)) {
        return false;
    }

    if (preg_match('/(200)/', $res[0])) {
        return true;
    }
    if ($is_200 > 5) {
        return false;
    }

    if (preg_match('/(301)|(302)|(303)|(307)/', $res[0])) {
        foreach ($res as $v) {
            if (preg_match('/location:\s(.*?)$/is', $v, $matches)) {
                ++$is_200;
                $location = trim($matches[1]);

                return nv_check_url($location, $isTriggerError, $is_200);
            }
        }
    }

    return false;
}

/**
 * nv_url_rewrite()
 *
 * @param string $buffer
 * @param bool   $is_url
 * @return string
 */
function nv_url_rewrite($buffer, $is_url = false)
{
    global $global_config;

    if ($global_config['rewrite_enable']) {
        if ($is_url) {
            $buffer = '"' . $buffer . '"';
        }

        $buffer = preg_replace_callback('#"(' . preg_quote(NV_BASE_SITEURL, '#') . ')index.php\\?' . preg_quote(NV_LANG_VARIABLE, '#') . '=([^"]+)"#', 'nv_url_rewrite_callback', $buffer);

        if ($is_url) {
            $buffer = substr($buffer, 1, -1);
        }
    }

    return $buffer;
}

/**
 * nv_url_rewrite_callback()
 *
 * @param string $matches
 * @return string
 */
function nv_url_rewrite_callback($matches)
{
    global $global_config;

    $query_string = NV_LANG_VARIABLE . '=' . $matches[2];
    $query_array = [];
    $is_amp = str_contains($query_string, '&amp;');
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
        if (!$global_config['rewrite_optional']) {
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
            if ($global_config['rewrite_op_mod'] != $query_array[NV_NAME_VARIABLE]) {
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

        $rewrite_string = (defined('NV_IS_REWRITE_OBSOLUTE') ? NV_MY_DOMAIN : '') . NV_BASE_SITEURL . ($global_config['check_rewrite_file'] ? '' : 'index.php/') . implode('/', $op_rewrite) . ($op_rewrite_count ? $rewrite_end : '');

        if (!empty($query_array)) {
            $rewrite_string .= '?' . http_build_query($query_array, '', $is_amp ? '&amp;' : '&');
        }

        return '"' . $rewrite_string . '"';
    }

    return $matches[0];
}

/**
 * nv_change_buffer()
 *
 * @param string $buffer
 * @return string
 */
function nv_change_buffer($buffer)
{
    global $global_config, $client_info;

    if (defined('NV_SYSTEM') and (defined('GOOGLE_ANALYTICS_SYSTEM') or (isset($global_config['googleAnalyticsID']) and preg_match('/^UA-\d{4,}-\d+$/', $global_config['googleAnalyticsID'])))) {
        $_google_analytics = "<script data-show=\"inline\">(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){" . PHP_EOL;
        $_google_analytics .= '(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),' . PHP_EOL;
        $_google_analytics .= 'm=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)' . PHP_EOL;
        $_google_analytics .= "})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');" . PHP_EOL;
        if (isset($global_config['googleAnalyticsID']) and preg_match('/^UA-\d{4,}-\d+$/', $global_config['googleAnalyticsID'])) {
            $_google_analytics .= "ga('create', '" . $global_config['googleAnalyticsID'] . "', '" . $global_config['cookie_domain'] . "');" . PHP_EOL;
        }
        if (defined('GOOGLE_ANALYTICS_SYSTEM')) {
            $_google_analytics .= "ga('create', '" . GOOGLE_ANALYTICS_SYSTEM . "', 'auto');" . PHP_EOL;
        }
        $_google_analytics .= "ga('send', 'pageview');" . PHP_EOL;
        $_google_analytics .= '</script>' . PHP_EOL;
        $buffer = preg_replace('/(<\/head[^>]*>)/', PHP_EOL . $_google_analytics . '$1', $buffer, 1);
    }

    if (defined('NV_SYSTEM') and isset($global_config['googleAnalytics4ID']) and (preg_match('/^UA-\d{4,}-\d+$/', $global_config['googleAnalytics4ID']) or preg_match('/^G\-[a-zA-Z0-9]{8,}$/', $global_config['googleAnalytics4ID']))) {
        $_google_analytics4 = '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $global_config['googleAnalytics4ID'] . '"></script>' . PHP_EOL;
        $_google_analytics4 .= "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date);gtag('config','" . $global_config['googleAnalytics4ID'] . "');</script>" . PHP_EOL;
        $buffer = preg_replace('/(<\/head[^>]*>)/', PHP_EOL . $_google_analytics4 . '$1', $buffer, 1);
    }

    if (NV_ANTI_IFRAME and empty($client_info['is_myreferer'])) {
        $buffer = preg_replace('/(<body[^>]*>)/', '$1' . PHP_EOL . '<script>if(window.top!==window.self){document.write="";window.top.location=window.self.location;setTimeout(function(){document.body.innerHTML=""},1);window.self.onload=function(){document.body.innerHTML=""}};</script>', $buffer, 1);
    }

    if (isset($global_config['cronjobs_next_time']) and NV_CURRENTTIME > $global_config['cronjobs_next_time']) {
        $_body_cronjobs = '<div id="run_cronjobs" style="visibility:hidden;display:none;"><img alt="cron" src="' . NV_BASE_SITEURL . 'index.php?second=cronjobs&amp;p=' . nv_genpass() . '" width="1" height="1" /></div>' . PHP_EOL;
        $buffer = preg_replace('/\s*<\/body>/i', PHP_EOL . $_body_cronjobs . '</body>', $buffer, 1);
    }

    return $buffer;
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
    if ($sth->execute()) {
        return true;
    }

    return false;
}

/**
 * nv_site_mods()
 *
 * @return array
 */
function nv_site_mods()
{
    global $sys_mods, $admin_info, $global_config;

    $site_mods = $sys_mods;
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
                    'groups'
                ];
            } else {
                $user_ops = [
                    'main',
                    'login',
                    'register',
                    'lostpass'
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
        !empty($content) and $content = serialize($content);

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
 * @param string $language
 * @param string $module
 * @param string $type
 * @param int    $obid
 * @return true
 */
function nv_delete_notification($language, $module, $type, $obid)
{
    global $db_config, $db, $global_config;

    if ($global_config['notification_active']) {
        try {
            $sth = $db->prepare('DELETE FROM ' . NV_NOTIFICATION_GLOBALTABLE . ' WHERE language = :language AND module = :module AND obid = :obid AND type = :type');
            $sth->bindParam(':language', $language, PDO::PARAM_STR);
            $sth->bindParam(':module', $module, PDO::PARAM_STR);
            $sth->bindParam(':obid', $obid, PDO::PARAM_INT);
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

    return $crypt->encrypt($url, NV_CHECK_SESSION);
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
            list($auth_user, $auth_pw) = explode(':', $usr_pass);
        }
        unset($usr_pass);
    }

    return [
        'auth_user' => $auth_user,
        'auth_pw' => $auth_pw
    ];
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
    // Default api trả về error
    $apiresults = new NukeViet\Api\ApiResult();

    /*
     * Kiểm tra nếu là API của module
     * API là kiểu chạy sau khi tài nguyên của hệ thống đã load
     * Do đó chỉ cần truyền module_name vào và căn cứ $sys_mods để lấy các thông tin còn lại
     * Khác với HOOK phải tuyền module_file vào để xác định
     */
    if (NukeViet\Api\Api::test($module)) {
        global $sys_mods;
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
    if (!isset($els2['v']) or strcasecmp($els2['v'], 'dkim1') != 0 or !isset($els2['p']) or $els2['p'] != $publickey) {
        return false;
    }

    return true;
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
 * Make an asynchronous POST request
 * Thực hiện yêu cầu POST không đồng bộ trong nội bộ site mà không cần chờ phản hồi
 * => Không ảnh hưởng, không trì hoãn tiến trình đang chạy
 * 
 * post_async()
 * 
 * @param mixed $url 
 * @param mixed $params 
 * @param array $headers 
 */
function post_async($url, $params, $headers = [])
{
    ksort($params);
    $post_string = http_build_query($params);
    !str_starts_with($url, NV_MY_DOMAIN) && $url = NV_MY_DOMAIN . $url;
    $parts = parse_url($url);

    $is_https = ($parts['scheme'] === 'https');
    $referer = $parts['scheme'] . '://' . $parts['host'];
    if (!$is_https) {
        $port = isset($parts['port']) ? $parts['port'] : 80;
        $host = $parts['host'] . ($port != 80 ? ':' . $port : '');
        isset($parts['port']) && $referer .= ':' . $parts['port'];
        $fp = fsockopen($parts['host'], $port, $errno, $errstr, 30);
    } else {
        $context = stream_context_create([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false
            ]
        ]);
        $port = isset($parts['port']) ? $parts['port'] : 443;
        $host = $parts['host'] . ($port != 443 ? ':' . $port : '');
        $referer .= ':' . (isset($parts['port']) ? $parts['port'] : 443);
        $fp = stream_socket_client('ssl://' . $parts['host'] . ':' . $port, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);
    }

    $path = isset($parts['path']) ? $parts['path'] : '/';
    if (isset($parts['query'])) {
        $path .= '?' . $parts['query'];
    }

    $out = "POST " . $path . " HTTP/1.1\r\n";
    $out .= "Host: " . $host . "\r\n";
    $out .= "User-Agent: NUKEVIET\r\n";
    $out .= "Referer: " . $referer . "\r\n";
    $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out .= "Content-Length: " . strlen($post_string) . "\r\n";
    if (!empty($headers)) {
        foreach ($headers as $key => $value) {
            $out .= "{$key}: {$value}\r\n";
        }
    }
    $out .= "Connection: Close\r\n\r\n";
    $out .= $post_string;

    fwrite($fp, $out);
    if ($is_https) {
        stream_set_timeout($fp, 1);
        stream_get_contents($fp, -1);
    }
    fclose($fp);
}
