<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1/9/2010, 23:48
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

/**
 * nv_object2array()
 *
 * @param mixed $a
 * @return
 */
function nv_object2array($a)
{
    if (is_object($a)) {
        $a = get_object_vars($a);
    }

    return is_array($a) ? array_map(__function__, $a) : $a;
}

/**
 * nv_getenv()
 *
 * @param mixed $a
 * @return
 */
function nv_getenv($a)
{
    if (! is_array($a)) {
        $a = array( $a );
    }

    foreach ($a as $b) {
        if (isset($_SERVER[$b])) {
            return $_SERVER[$b];
        } elseif (isset($_ENV[$b])) {
            return $_ENV[$b];
        } elseif (@getenv($b)) {
            return @getenv($b);
        } elseif (function_exists('apache_getenv') and apache_getenv($b, true)) {
            return apache_getenv($b, true);
        }
    }

    return '';
}

/**
 * nv_preg_quote()
 *
 * @param string $a
 * @return
 */
function nv_preg_quote($a)
{
    return preg_quote($a, '/');
}

/**
 * nv_is_myreferer()
 *
 * @param string $referer
 * @return
 */
function nv_is_myreferer($referer = '')
{
    if (empty($referer)) {
        $referer = urldecode(nv_getenv('HTTP_REFERER'));
    }
    if (empty($referer)) {
        return 2;
    }

    $server_name = preg_replace('/^[w]+\./', '', nv_getenv('HTTP_HOST'));
    $referer = preg_replace(array( '/^[a-zA-Z]+\:\/\/([w]+\.)?/', '/^[w]+\./' ), '', $referer);

    if (preg_match('/^' . nv_preg_quote($server_name) . '/', $referer)) {
        return 1;
    }

    return 0;
}

/**
 * nv_is_blocker_proxy()
 *
 * @param string $is_proxy
 * @param integer $proxy_blocker
 * @return
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
 * @return
 */
function nv_is_banIp($ip)
{
    $array_banip_site = $array_banip_admin = array();

    if (file_exists(NV_ROOTDIR . '/' . NV_DATADIR . '/banip.php')) {
        include NV_ROOTDIR . '/' . NV_DATADIR . '/banip.php' ;
    }

    $banIp = (defined('NV_ADMIN')) ? $array_banip_admin : $array_banip_site;
    if (empty($banIp)) {
        return false;
    }

    foreach ($banIp as $e => $f) {
        if ($f['begintime'] < NV_CURRENTTIME and ($f['endtime'] == 0 or $f['endtime'] > NV_CURRENTTIME) and (preg_replace($f['mask'], '', $ip) == preg_replace($f['mask'], '', $e))) {
            return true;
        }
    }

    return false;
}

/**
 * nv_checkagent()
 *
 * @param string $a
 * @return
 */
function nv_checkagent($a)
{
    $a = htmlspecialchars(substr($a, 0, 255));
    $a = str_replace(array( ', ', '<' ), array( '-', '(' ), $a);

    return ((! empty($a) and $a != '-') ? $a : 'none');
}

/**
 * nv_convertfromBytes()
 *
 * @param integer $size
 * @return
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
    $iec = array( 'bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );

    while (($size / 1024) > 1) {
        $size = $size / 1024;
        ++$i;
    }

    return number_format($size, 2) . ' ' . $iec[$i];
}

/**
 * nv_convertfromSec()
 *
 * @param integer $sec
 * @return
 */
function nv_convertfromSec($sec = 0)
{
    global $lang_global;

    $sec = intval($sec);
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
 * @return
 */
function nv_converttoBytes($string)
{
    if (preg_match('/^([0-9\.]+)[ ]*([b|k|m|g|t|p|e|z|y]*)/i', $string, $matches)) {
        if (empty($matches[2])) {
            return $matches[1];
        }

        $suffixes = array(
            'B' => 0,
            'K' => 1,
            'M' => 2,
            'G' => 3,
            'T' => 4,
            'P' => 5,
            'E' => 6,
            'Z' => 7,
            'Y' => 8
        );

        if (isset($suffixes[strtoupper($matches[2])])) {
            return round($matches[1] * pow(1024, $suffixes[strtoupper($matches[2])]));
        }
    }

    return false;
}

/**
 * nv_base64_encode()
 *
 * @param string $input
 * @return
 */
function nv_base64_encode($input)
{
    return strtr(base64_encode($input), '+/=', '-_,');
}

/**
 * nv_base64_decode()
 *
 * @param string $input
 * @return
 */
function nv_base64_decode($input)
{
    return base64_decode(strtr($input, '-_,', '+/='));
}

/**
 * nv_function_exists()
 *
 * @param string $funcName
 * @return
 */
function nv_function_exists($funcName)
{
    global $sys_info;

    return (function_exists($funcName) and ! in_array($funcName, $sys_info['disable_functions']));
}

/**
 * nv_class_exists()
 *
 * @param string $clName
 * @param bool $autoload
 * @return
 */
function nv_class_exists($clName, $autoload = true)
{
    global $sys_info;

    return (class_exists($clName, $autoload) and ! in_array($clName, $sys_info['disable_classes']));
}

/**
 * nv_md5safe()
 *
 * @param string $username
 * @return
 */
function nv_md5safe($username)
{
    return md5(nv_strtolower($username));
}

/**
 * nv_check_valid_login()
 *
 * @param string $login
 * @param integer $max
 * @param integer $min
 * @return
 */
function nv_check_valid_login($login, $max, $min)
{
    global $lang_global, $global_config;

    $login = trim(strip_tags($login));

    if (empty($login)) {
        return $lang_global['username_empty'];
    }
    if (isset($login{$max})) {
        return sprintf($lang_global['usernamelong'], $max);
    }
    if (! isset($login{$min - 1})) {
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
            return ($login != strip_punctuation($_login) ? $lang_global['unick_type_' . $type] : '');
            break;
        default:
            return '';
    }
    if (! preg_match($pattern, $login)) {
        return $lang_global['unick_type_' . $type];
    }
    return '';
}

/**
 * nv_check_valid_pass()
 *
 * @param string $pass
 * @param integer $max
 * @param integer $min
 * @return
 */
function nv_check_valid_pass($pass, $max, $min)
{
    global $lang_global, $db_config, $db, $global_config;

    $pass = trim(strip_tags($pass));

    if (empty($pass)) {
        return $lang_global['password_empty'];
    }
    if (isset($pass{$max})) {
        return sprintf($lang_global['passwordlong'], $max);
    }
    if (! isset($pass{$min - 1})) {
        return sprintf($lang_global['passwordadjective'], $min);
    }

    $type = $global_config['nv_upass_type'];
    if ($type == 1) {
        if (! (preg_match('#[a-z]#ui', $pass) and preg_match('#[0-9]#u', $pass))) {
            return $lang_global['upass_type_' . $type];
        }
    } elseif ($type == 3) {
        if (! (preg_match('#[A-Z]#u', $pass) and preg_match('#[0-9]#u', $pass))) {
            return $lang_global['upass_type_' . $type];
        }
    } elseif ($type == 2) {
        if (! (preg_match('#[^A-Za-z0-9]#u', $pass) and preg_match('#[a-z]#ui', $pass) and preg_match('#[0-9]#u', $pass))) {
            return $lang_global['upass_type_' . $type];
        }
    } elseif ($type == 4) {
        if (! (preg_match('#[^A-Za-z0-9]#u', $pass) and preg_match('#[A-Z]#u', $pass) and preg_match('#[0-9]#u', $pass))) {
            return $lang_global['upass_type_' . $type];
        }
    }

    $password_simple = $db->query("SELECT content FROM " . NV_USERS_GLOBALTABLE . "_config WHERE config='password_simple'")->fetchColumn();
    $password_simple = explode('|', $password_simple);
    if (in_array($pass, $password_simple)) {
        return $lang_global ['upass_type_simple'];
    }
    return '';
}

/**
 * nv_check_valid_email()
 *
 * @param string $mail
 * @return
 */
function nv_check_valid_email($mail)
{
    global $lang_global, $global_config;

    $mail = strip_tags(trim($mail));

    if (empty($mail)) {
        return $lang_global['email_empty'];
    }

    if (function_exists('filter_var') and filter_var($mail, FILTER_VALIDATE_EMAIL) === false) {
        return $lang_global['email_incorrect'];
    }

    if (! preg_match($global_config['check_email'], $mail)) {
        return $lang_global['email_incorrect'];
    }

    if (! preg_match('/\.([a-z0-9\-]+)$/', $mail)) {
        return $lang_global['email_incorrect'];
    }

    return '';
}

/**
 * nv_capcha_txt()
 *
 * @param mixed $seccode
 * @return
 */
function nv_capcha_txt($seccode)
{
    global $global_config, $nv_Request, $client_info, $crypt;

    if ($global_config['captcha_type'] == 2) {
        if (!empty($global_config['recaptcha_secretkey'])) {
            $NV_Http = new NukeViet\Http\Http($global_config, NV_TEMP_DIR);
            $request = array(
                'secret' => $crypt->decrypt($global_config['recaptcha_secretkey']),
                'response' => $seccode,
                'remoteip' => $client_info['ip']
            );
            $args = array(
                'headers' => array(
                    'Referer' => NV_MY_DOMAIN,
                ),
                'body' => $request
            );
            $array = $NV_Http->post('https://www.google.com/recaptcha/api/siteverify', $args);
            if (is_array($array) and isset($array['body'])) {
                $jsonRes = nv_object2array(@json_decode($array['body']));
                if (is_array($jsonRes) and isset($jsonRes['success']) and ((bool)$jsonRes['success']) === true) {
                    return true;
                }
            }
        }
        return false;
    } else {
        mt_srand(( double )microtime() * 1000000);
        $maxran = 1000000;
        $random = mt_rand(0, $maxran);

        $seccode = strtoupper($seccode);
        $random_num = $nv_Request->get_string('random_num', 'session', 0);
        $datekey = date('F j');
        $rcode = strtoupper(md5(NV_USER_AGENT . $global_config['sitekey'] . $random_num . $datekey));

        $nv_Request->set_Session('random_num', $random);
        return (preg_match('/^[a-zA-Z0-9]{' . NV_GFX_NUM . '}$/', $seccode) and $seccode == substr($rcode, 2, NV_GFX_NUM));
    }
}

/**
 * nv_genpass()
 *
 * @param integer $length
 * @return
 */
function nv_genpass($length = 8, $type = 0)
{
    $array_chars = array();
    $array_chars[0] = 'abcdefghijklmnopqrstuvwxyz';
    $array_chars[1] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $array_chars[2] = '0123456789';
    $array_chars[3] = '-=~!@#$%^&*()_+,./<>?;:[]{}\|';

    $_arr_m = array();
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
 * @param bool $blnCreateLink
 * @return
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
    $strDisplay = ! empty($strDisplay) ? $strDisplay : $strEncodedEmail;

    if ($blnCreateLink) {
        return '<a href="' . $strMailto . $strEncodedEmail . '">' . $strDisplay . '</a>';
    }

    return $strDisplay;
}

/**
 * nv_user_groups()
 *
 * @param string $in_groups
 * @param bool $res_2step
 * @param array $manual_groups
 * @return
 */
function nv_user_groups($in_groups, $res_2step = false, $manual_groups = array())
{
    global $nv_Cache, $db, $global_config;

    $_groups = array();
    $_2step_require = false;

    if (!empty($in_groups) or !empty($manual_groups)) {
        $query = 'SELECT group_id, title, require_2step_admin, require_2step_site, exp_time FROM ' . NV_GROUPS_GLOBALTABLE . ' WHERE act=1 AND (idsite = ' . $global_config['idsite'] . ' OR (idsite =0 AND siteus = 1)) ORDER BY idsite, weight';
        $list = $nv_Cache->db($query, '', 'users');
        if (!empty($list)) {
            $reload = array();
            $in_groups = explode(',', $in_groups);
            if (!empty($manual_groups)) {
                $in_groups = array_unique(array_merge_recursive($in_groups, $manual_groups));
            }
            for ($i = 0, $count = sizeof($list); $i < $count; ++$i) {
                if ($list[$i]['exp_time'] != 0 and $list[$i]['exp_time'] <= NV_CURRENTTIME) {
                    $reload[] = $list[$i]['group_id'];
                } elseif (in_array($list[$i]['group_id'], $in_groups)) {
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
        return array($_groups, $_2step_require);
    }

    return $_groups;
}

/**
 * nv_user_in_groups()
 *
 * @param string $groups_view
 * @return
 */
function nv_user_in_groups($groups_view)
{
    $groups_view = explode(',', $groups_view);
    if (in_array(6, $groups_view)) {
        // All
        return true;
    } elseif (defined('NV_IS_USER') or defined('NV_IS_ADMIN')) {
        global $user_info, $admin_info;

        $in_groups = defined('NV_IS_ADMIN') ? $admin_info['in_groups'] : $user_info['in_groups'];

        if (in_array(4, $groups_view) and (empty($in_groups) or !in_array(7, $in_groups))) {
            // User with no group or not in new users groups
            return true;
        } else {
            // Check group
            if (empty($in_groups)) {
                return false;
            }
            return (array_intersect($in_groups, $groups_view) != array());
        }
    } elseif (in_array(5, $groups_view)) {
        // Guest
        return true;
    }
    return false;
}

/**
 * nv_groups_add_user()
 *
 * @param int $group_id
 * @param int $userid
 * @return
 */
function nv_groups_add_user($group_id, $userid, $approved = 1, $mod_data = 'users')
{
    global $db, $db_config, $global_config;
    $_mod_table = ($mod_data == 'users') ? NV_USERS_GLOBALTABLE : $db_config['prefix'] . '_' . $mod_data;
    $query = $db->query('SELECT COUNT(*) FROM ' . $_mod_table . ' WHERE userid=' . $userid);
    if ($query->fetchColumn()) {
        try {
            $db->query("INSERT INTO " . $_mod_table . "_groups_users (group_id, userid, approved, data) VALUES (" . $group_id . ", " . $userid . ", " . $approved . ", '" . $global_config['idsite'] . "')");
            $db->query('UPDATE ' . $_mod_table . '_groups SET numbers = numbers+1 WHERE group_id=' . $group_id);
            return true;
        } catch (PDOException $e) {
            if ($group_id <= 3) {
                $data = $db->query('SELECT data FROM ' . $_mod_table . '_groups_users WHERE group_id=' . $group_id . ' AND userid=' . $userid)->fetchColumn();
                $data = ($data != '') ? explode(',', $data) : array();
                $data[] = $global_config['idsite'];
                $data = implode(',', array_unique(array_map('intval', $data)));
                $db->query("UPDATE " . $_mod_table . "_groups_users SET data = '" . $data . "' WHERE group_id=" . $group_id . " AND userid=" . $userid);
                return true;
            }
        }
    }
    return false;
}

/**
 * nv_groups_del_user()
 *
 * @param int $group_id
 * @param int $userid
 * @return
 */
function nv_groups_del_user($group_id, $userid, $mod_data = 'users')
{
    global $db, $db_config, $global_config;

    $_mod_table = ($mod_data == 'users') ? NV_USERS_GLOBALTABLE : $db_config['prefix'] . '_' . $mod_data;
    $row = $db->query('SELECT data FROM ' . $_mod_table . '_groups_users WHERE group_id=' . $group_id . ' AND userid=' . $userid)->fetch();
    if (! empty($row)) {
        $set_number = false;
        if ($group_id > 3) {
            $set_number = true;
        } else {
            $data = str_replace(',' . $global_config['idsite'] . ',', '', ',' . $row['data'] . ',');
            $data = trim($data, ',');
            if ($data == '') {
                $set_number = true;
            } else {
                $db->query("UPDATE " . $_mod_table . "_groups_users SET data = '" . $data . "' WHERE group_id=" . $group_id . " AND userid=" . $userid);
            }
        }

        if ($set_number) {
            $db->query('DELETE FROM ' . $_mod_table . '_groups_users WHERE group_id = ' . $group_id . ' AND userid = ' . $userid);

            // Chỗ này chỉ xóa những thành viên đã được xét duyệt vào nhóm nên sẽ cập nhật luôn số thành viên, không cần kiểm tra approved = 1 hay không
            $db->query('UPDATE ' . $_mod_table . '_groups SET numbers = numbers-1 WHERE group_id=' . $group_id);
        }
        return true;
    } else {
        return false;
    }
}

/**
 * nv_show_name_user()
 *
 * @param string $first_name
 * @param string $last_name
 * @param string $user_name
 *  *
 * @return
 */
function nv_show_name_user($first_name, $last_name,  $user_name = '')
{
    global $global_config;

    $full_name = ($global_config['name_show'])  ? $first_name . ' ' . $last_name : $last_name . ' ' . $first_name;
    $full_name = trim($full_name);
    return empty($full_name) ? $user_name : $full_name;
}

/**
 * nv_date()
 *
 * @param string $format
 * @param integer $time
 * @return
 */
function nv_date($format, $time = 0)
{
    global $lang_global;

    if (! $time) {
        $time = NV_CURRENTTIME;
    }
    $format = str_replace("r", "D, d M Y H:i:s O", $format);
    $format = str_replace(array( "D", "M" ), array( "[D]", "[M]" ), $format);
    $return = date($format, $time);

    $replaces = array(
        '/\[Sun\](\W|$)/' => $lang_global['sun'] . "$1",
        '/\[Mon\](\W|$)/' => $lang_global['mon'] . "$1",
        '/\[Tue\](\W|$)/' => $lang_global['tue'] . "$1",
        '/\[Wed\](\W|$)/' => $lang_global['wed'] . "$1",
        '/\[Thu\](\W|$)/' => $lang_global['thu'] . "$1",
        '/\[Fri\](\W|$)/' => $lang_global['fri'] . "$1",
        '/\[Sat\](\W|$)/' => $lang_global['sat'] . "$1",
        '/\[Jan\](\W|$)/' => $lang_global['jan'] . "$1",
        '/\[Feb\](\W|$)/' => $lang_global['feb'] . "$1",
        '/\[Mar\](\W|$)/' => $lang_global['mar'] . "$1",
        '/\[Apr\](\W|$)/' => $lang_global['apr'] . "$1",
        '/\[May\](\W|$)/' => $lang_global['may2'] . "$1",
        '/\[Jun\](\W|$)/' => $lang_global['jun'] . "$1",
        '/\[Jul\](\W|$)/' => $lang_global['jul'] . "$1",
        '/\[Aug\](\W|$)/' => $lang_global['aug'] . "$1",
        '/\[Sep\](\W|$)/' => $lang_global['sep'] . "$1",
        '/\[Oct\](\W|$)/' => $lang_global['oct'] . "$1",
        '/\[Nov\](\W|$)/' => $lang_global['nov'] . "$1",
        '/\[Dec\](\W|$)/' => $lang_global['dec'] . "$1",
        '/Sunday(\W|$)/' => $lang_global['sunday'] . "$1",
        '/Monday(\W|$)/' => $lang_global['monday'] . "$1",
        '/Tuesday(\W|$)/' => $lang_global['tuesday'] . "$1",
        '/Wednesday(\W|$)/' => $lang_global['wednesday'] . "$1",
        '/Thursday(\W|$)/' => $lang_global['thursday'] . "$1",
        '/Friday(\W|$)/' => $lang_global['friday'] . "$1",
        '/Saturday(\W|$)/' => $lang_global['saturday'] . "$1",
        '/January(\W|$)/' => $lang_global['january'] . "$1",
        '/February(\W|$)/' => $lang_global['february'] . "$1",
        '/March(\W|$)/' => $lang_global['march'] . "$1",
        '/April(\W|$)/' => $lang_global['april'] . "$1",
        '/May(\W|$)/' => $lang_global['may'] . "$1",
        '/June(\W|$)/' => $lang_global['june'] . "$1",
        '/July(\W|$)/' => $lang_global['july'] . "$1",
        '/August(\W|$)/' => $lang_global['august'] . "$1",
        '/September(\W|$)/' => $lang_global['september'] . "$1",
        '/October(\W|$)/' => $lang_global['october'] . "$1",
        '/November(\W|$)/' => $lang_global['november'] . "$1",
        '/December(\W|$)/' => $lang_global['december'] . "$1" );

    return preg_replace(array_keys($replaces), array_values($replaces), $return);
}

/**
 * nv_monthname()
 *
 * @param integer $i
 * @return
 */
function nv_monthname($i)
{
    global $lang_global;

    --$i;
    $month_names = array( $lang_global['january'], $lang_global['february'], $lang_global['march'], $lang_global['april'], $lang_global['may'], $lang_global['june'], $lang_global['july'], $lang_global['august'], $lang_global['september'], $lang_global['october'], $lang_global['november'], $lang_global['december'] );

    return (isset($month_names[$i]) ? $month_names[$i] : '');
}

/**
 * nv_unhtmlspecialchars()
 *
 * @param mixed $string
 * @return
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
        $search = array( '&amp;', '&#039;', '&quot;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x23;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;' );
        $replace = array( '&', '\'', '"', '<', '>', '\\', '/', '(', ')', '*', '[', ']', '!', '=', '#', '%', '^', ':', '{', '}', '`', '~' );

        $string = str_replace($search, $replace, $string);
    }

    return $string;
}

/**
 * nv_htmlspecialchars()
 *
 * @param mixed $string
 * @return
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
        $search = array( '&', '\'', '"', '<', '>', '\\', '/', '(', ')', '*', '[', ']', '!', '=', '%', '^', ':', '{', '}', '`', '~' );
        $replace = array( '&amp;', '&#039;', '&quot;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;' );

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
 * @param mixed $text
 * @return
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

    return preg_replace(array( // Remove separator, control, formatting, surrogate, open/close quotes.
        '/[\p{Z}\p{Cc}\p{Cf}\p{Cs}\p{Pi}\p{Pf}]/u', // Remove other punctuation except special cases
        '/\p{Po}(?<![' . $specialquotes . $numseparators . $urlall . $nummodifiers . '])/u', // Remove non-URL open/close brackets, except URL brackets.
        '/[\p{Ps}\p{Pe}](?<![' . $urlbrackets . '])/u', // Remove special quotes, dashes, connectors, number separators, and URL characters followed by a space
        '/[' . $specialquotes . $numseparators . $urlspaceafter . '\p{Pd}\p{Pc}]+((?= )|$)/u', // Remove special quotes, connectors, and URL characters preceded by a space
        '/((?<= )|^)[' . $specialquotes . $urlspacebefore . '\p{Pc}]+/u', // Remove dashes preceded by a space, but not followed by a number
        '/((?<= )|^)\p{Pd}+(?![\p{N}\p{Sc}])/u', // Remove consecutive spaces
        '/ +/'
    ), ' ', $text);
}

/**
 * nv_nl2br()
 *
 * @param string $text
 * @param string $replacement
 * @return
 */
function nv_nl2br($text, $replacement = '<br />')
{
    if (empty($text)) {
        return '';
    }

    return strtr($text, array(
        "\r\n" => $replacement,
        "\r" => $replacement,
        "\n" => $replacement
    ));
}

/**
 * nv_br2nl()
 *
 * @param string $text
 * @return
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
 * @return
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
 * @param mixed $text
 * @return
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
 * @return
 */
function nv_get_keywords($content, $keyword_limit = 20)
{
    $content = strip_tags($content);
    $content = nv_unhtmlspecialchars($content);
    $content = strip_punctuation($content);
    $content = trim($content);
    $content = nv_strtolower($content);

    $content = ' ' . $content . ' ';
    $keywords_return = array();

    $memoryLimitMB = ( integer )ini_get('memory_limit');

    if ($memoryLimitMB > 60 and file_exists(NV_ROOTDIR . '/includes/keywords/' . NV_LANG_DATA . '.php')) {
        require NV_ROOTDIR . '/includes/keywords/' . NV_LANG_DATA . '.php';

        $content_array = explode(' ', $content);
        $b = sizeof($content_array);

        for ($i = 0; $i < $b - 3; ++$i) {
            $key3 = $content_array[$i] . ' ' . $content_array[$i + 1] . ' ' . $content_array[$i + 2];
            $key2 = $content_array[$i] . ' ' . $content_array[$i + 1];

            if (array_search($key3, $array_keywords_3)) {
                $keywords_return[] = $key3;
                $i = $i + 2;
            } elseif (array_search($key2, $array_keywords_2)) {
                $keywords_return[] = $key2;
                $i = $i + 1;
            }

            $keywords_return = array_unique($keywords_return);
            if (sizeof($keywords_return) > $keyword_limit) {
                break;
            }
        }
    } else {
        $pattern_word = array();

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
 * @param mixed $from
 * @param mixed $to
 * @param string $subject
 * @param string $message
 * @param string $files
 * @return
 */
function nv_sendmail($from, $to, $subject, $message, $files = '', $AddEmbeddedImage = false)
{
    global $global_config, $sys_info;

    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->SetLanguage(NV_LANG_INTERFACE);
        $mail->CharSet = $global_config['site_charset'];

        $mailer_mode = strtolower($global_config['mailer_mode']);

        if ($mailer_mode == 'smtp') {
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Port = $global_config['smtp_port'];
            $mail->Host = $global_config['smtp_host'];
            $mail->Username = $global_config['smtp_username'];
            $mail->Password = $global_config['smtp_password'];

            $SMTPSecure = intval($global_config['smtp_ssl']);
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
            $mail->SMTPOptions = array(
            		'ssl' => array(
            				'verify_peer' => ($global_config['verify_peer_ssl'] == 1) ? true : false,
            				'verify_peer_name' => ($global_config['verify_peer_name_ssl'] == 1) ? true : false,
            				'allow_self_signed' => true
            		)
            );

            if (filter_var($global_config['smtp_username'], FILTER_VALIDATE_EMAIL)) {
                $mail->From = $global_config['smtp_username'];
            } else {
                $mail->From = $global_config['site_email'];
            }
        } elseif ($mailer_mode == 'sendmail') {
            $mail->IsSendmail();

            if (isset($_SERVER['SERVER_ADMIN']) and !empty($_SERVER['SERVER_ADMIN']) and filter_var($_SERVER['SERVER_ADMIN'], FILTER_VALIDATE_EMAIL)) {
                $mail->From = $_SERVER['SERVER_ADMIN'];
            } elseif (checkdnsrr($_SERVER['SERVER_NAME'], "MX") || checkdnsrr($_SERVER['SERVER_NAME'], "A")) {
                $mail->From = "webmaster@" . $_SERVER['SERVER_NAME'];
            } else {
                $mail->From = $global_config['site_email'];
            }
        } elseif (! in_array('mail', $sys_info['disable_functions'])) {
            $mail->IsMail();

            if (($php_email = @ini_get("sendmail_from")) != "" and filter_var($php_email, FILTER_VALIDATE_EMAIL)) {
                $mail->From = $php_email;
            } elseif (preg_match("/([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+/", ini_get("sendmail_path"), $matches) and filter_var($matches[0], FILTER_VALIDATE_EMAIL)) {
                $mail->From = $matches[0];
            } elseif (checkdnsrr($_SERVER['SERVER_NAME'], "MX") || checkdnsrr($_SERVER['SERVER_NAME'], "A")) {
                $mail->From = "webmaster@" . $_SERVER['SERVER_NAME'];
            } else {
                $mail->From = $global_config['site_email'];
            }
        } else {
            return false;
        }

        $AltBody = strip_tags($message);
        if (function_exists("nv_mailHTML")) {
            $message = nv_mailHTML($subject, $message);
            $AddEmbeddedImage = true;
        }
        $message = nv_url_rewrite($message);
        $optimizer = new NukeViet\Core\Optimizer($message, NV_BASE_SITEURL);
        $message = $optimizer->process(false);
        $message = nv_unhtmlspecialchars($message);

        $mail->FromName = nv_unhtmlspecialchars($global_config['site_name']);

        if (is_array($from)) {
            $mail->addReplyTo($from[1], $from[0]);
        } else {
            $mail->addReplyTo($from);
        }

        if (empty($to)) {
            return false;
        }

        if (! is_array($to)) {
            $to = array( $to );
        }

        foreach ($to as $_to) {
            $mail->addAddress($_to);
        }

        $mail->Subject = nv_unhtmlspecialchars($subject);
        $mail->WordWrap = 120;
        $mail->Body = $message;
        $mail->AltBody = $AltBody;
        $mail->IsHTML(true);

        if($AddEmbeddedImage) {
            $mail->AddEmbeddedImage(NV_ROOTDIR . '/' . $global_config['site_logo'], 'sitelogo', basename(NV_ROOTDIR . '/' . $global_config['site_logo']));
        }

        if (! empty($files)) {
            $files = array_map('trim', explode(',', $files));

            foreach ($files as $file) {
                $mail->addAttachment($file);
            }
        }

        if (! $mail->Send()) {
            trigger_error($mail->ErrorInfo, E_USER_WARNING);

            return false;
        }

        return true;
    } catch (PHPMailer\PHPMailer\Exception $e) {
        trigger_error($e->errorMessage(), E_USER_WARNING);

        return false;
    }
}

/**
 * nv_generate_page()
 *
 * @param string $base_url
 * @param integer $num_items
 * @param integer $per_page
 * @param integer $on_page
 * @param bool $add_prevnext_text
 * @param bool $onclick
 * @param string $js_func_name
 * @param string $containerid
 * @param bool $full_theme
 * @return
 */
function nv_generate_page($base_url, $num_items, $per_page, $on_page, $add_prevnext_text = true, $onclick = false, $js_func_name = 'nv_urldecode_ajax', $containerid = 'generate_page', $full_theme = true)
{
    global $lang_global;

    // Round up total page
    $total_pages = ceil($num_items / $per_page);

    if ($total_pages < 2) {
        return '';
    }

    if (! is_array($base_url)) {
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
            $href = ! $onclick ? "href=\"" . $href . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($href)) . "','" . $containerid . "')\"";
            $page_string .= '<li' . ($i == $on_page ? ' class="active"' : '') . '><a' . ($i == $on_page ? ' href="#"' : ' ' . $href) . '>' . $i . '</a></li>';
        }

        if ($total_pages > 3) {
            if ($on_page > 1 and $on_page < $total_pages) {
                if ($on_page > 5) {
                    $page_string .= '<li class="disabled"><span>...</span></li>';
                }

                $init_page_min = ($on_page > 4) ? $on_page : 5;
                $init_page_max = ($on_page < $total_pages - 4) ? $on_page : $total_pages - 4;

                for ($i = $init_page_min - 1; $i < $init_page_max + 2; ++$i) {
                    $href = ($i > 1) ? $base_url . $amp . $i : $base_url;
                    $href = ! $onclick ? "href=\"" . $href . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($href)) . "','" . $containerid . "')\"";
                    $page_string .= '<li' . ($i == $on_page ? ' class="active"' : '') . '><a' . ($i == $on_page ? ' href="#"' : ' ' . $href) . '>' . $i . '</a></li>';
                }

                if ($on_page < $total_pages - 4) {
                    $page_string .= '<li class="disabled"><span>...</span></li>';
                }
            } else {
                $page_string .= '<li class="disabled"><span>...</span></li>';
            }

            for ($i = $total_pages - 2; $i < $total_pages + 1; ++$i) {
                $href = ($i > 1) ? $base_url . $amp . $i : $base_url;
                $href = ! $onclick ? "href=\"" . $href . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($href)) . "','" . $containerid . "')\"";
                $page_string .= '<li' . ($i == $on_page ? ' class="active"' : '') . '><a' . ($i == $on_page ? ' href="#"' : ' ' . $href) . '>' . $i . '</a></li>';
            }
        }
    } else {
        for ($i = 1; $i < $total_pages + 1; ++$i) {
            $href = ($i > 1) ? $base_url . $amp . $i : $base_url;
            $href = ! $onclick ? "href=\"" . $href . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($href)) . "','" . $containerid . "')\"";
            $page_string .= '<li' . ($i == $on_page ? ' class="active"' : '') . '><a' . ($i == $on_page ? ' href="#"' : ' ' . $href) . '>' . $i . '</a></li>';
        }
    }

    if ($add_prevnext_text) {
        if ($on_page > 1) {
            $href = ($on_page > 2) ? $base_url . $amp . ($on_page - 1) : $base_url;
            $href = ! $onclick ? "href=\"" . $href . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($href)) . "','" . $containerid . "')\"";
            $page_string = "<li><a " . $href . " title=\"" . $lang_global['pageprev'] . "\">&laquo;</a></li>" . $page_string;
        } else {
            $page_string = '<li class="disabled"><a href="#">&laquo;</a></li>' . $page_string;
        }

        if ($on_page < $total_pages) {
            $href = ($on_page) ? $base_url . $amp . ($on_page + 1) : $base_url;
            $href = ! $onclick ? "href=\"" . $href . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($href)) . "','" . $containerid . "')\"";
            $page_string .= '<li><a ' . $href . ' title="' . $lang_global['pagenext'] . '">&raquo;</a></li>';
        } else {
            $page_string .= '<li class="disabled"><a href="#">&raquo;</a></li>';
        }
    }

    if ($full_theme !== true) {
        return $page_string;
    }

    return '<ul class="pagination">' . $page_string . '</ul>';
}

/**
 *
 * @param mixed $title
 * @param mixed $base_url
 * @param mixed $num_items
 * @param mixed $per_page
 * @param mixed $on_page
 * @param bool $add_prevnext_text
 * @param bool $full_theme
 * @return
 */
function nv_alias_page($title, $base_url, $num_items, $per_page, $on_page, $add_prevnext_text = true, $full_theme = true)
{
    global $lang_global;

    $total_pages = ceil($num_items / $per_page);

    if ($total_pages < 2) {
        return '';
    }

    $title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'];
    $page_string = ($on_page == 1) ? '<li class="active"><a href="#">1</a></li>' : '<li><a rel="prev" title="' . $title . ' 1" href="' . $base_url . '">1</a></li>';

    if ($total_pages > 7) {
        if ($on_page < 4) {
            $init_page_max = ($total_pages > 2) ? 2 : $total_pages;
            for ($i = 2; $i <= $init_page_max; ++$i) {
                if ($i == $on_page) {
                    $page_string .= '<li class="active"><a href="#">' . $i . '</a></li>';
                } else {
                    $rel = ($i > $on_page) ? 'next' : 'prev';
                    $page_string .= '<li><a rel="' . $rel . '" title="' . $title . ' ' . $i . '" href="' . $base_url . '/page-' . $i . '">' . $i . '</a></li>';
                }
            }
        }

        if ($on_page > 1 and $on_page < $total_pages) {
            if ($on_page > 3) {
                $page_string .= '<li class="disabled"><span>...</span></li>';
            }

            $init_page_min = ($on_page > 3) ? $on_page : 4;
            $init_page_max = ($on_page < $total_pages - 3) ? $on_page : $total_pages - 3;

            for ($i = $init_page_min - 1; $i < $init_page_max + 2; ++$i) {
                if ($i == $on_page) {
                    $page_string .= '<li class="active"><a href="#">' . $i . '</a></li>';
                } else {
                    $rel = ($i > $on_page) ? 'next' : 'prev';
                    $page_string .= '<li><a rel="' . $rel . '" title="' . $title . ' ' . $i . '" href="' . $base_url . '/page-' . $i . '">' . $i . '</a></li>';
                }
            }

            if ($on_page < $total_pages - 3) {
                $page_string .= '<li class="disabled"><span>...</span></li>';
            }
        } else {
            $page_string .= '<li class="disabled"><span>...</span></li>';
        }

        $init_page_min = ($total_pages - $on_page > 3) ? $total_pages : $total_pages - 1;
        for ($i = $init_page_min; $i <= $total_pages; ++$i) {
            if ($i == $on_page) {
                $page_string .= '<li class="active"><a href="#">' . $i . '</a></li>';
            } else {
                $rel = ($i > $on_page) ? 'next' : 'prev';
                $page_string .= '<li><a rel="' . $rel . '" title="' . $title . ' ' . $i . '" href="' . $base_url . '/page-' . $i . '">' . $i . '</a></li>';
            }
        }
    } else {
        for ($i = 2; $i < $total_pages + 1; ++$i) {
            if ($i == $on_page) {
                $page_string .= '<li class="active"><a href="#">' . $i . '</a><li>';
            } else {
                $rel = ($i > $on_page) ? 'next' : 'prev';
                $page_string .= '<li><a rel="' . $rel . '" title="' . $title . ' ' . $i . '" href="' . $base_url . '/page-' . $i . '">' . $i . '</a></li>';
            }
        }
    }

    if ($add_prevnext_text) {
        if ($on_page > 1) {
            $href = ($on_page > 2) ? $base_url . '/page-' . ($on_page - 1) : $base_url;
            $page_string = '<li><a rel="prev" title="' . $title . ' ' . ($on_page - 1) . '" href="' . $href . '">&laquo;</a></li>' . $page_string;
        } else {
            $page_string = '<li class="disabled"><a href="#">&laquo;</a></li>' . $page_string;
        }

        if ($on_page < $total_pages) {
            $page_string .= '<li><a rel="next" title="' . $title . ' ' . ($on_page + 1) . '" href="' . $base_url . '/page-' . ($on_page + 1) . '">&raquo;</a></li>';
        } else {
            $page_string .= '<li class="disabled"><a href="#">&raquo;</a></li>';
        }
    }

    if ($full_theme !== true) {
        return $page_string;
    }

    return '<ul class="pagination">' . $page_string . '</ul>';
}

/**
 * nv_check_domain()
 *
 * @param string $domain
 * @return string $domain_ascii
 */
function nv_check_domain($domain)
{
    if (preg_match('/^([a-z0-9]+)([a-z0-9\-\.]+)\.([a-z0-9\-]+)$/', $domain) or $domain == 'localhost' or filter_var($domain, FILTER_VALIDATE_IP)) {
        return $domain;
    } elseif (!empty($domain)) {
        if (function_exists('idn_to_ascii')) {
            $domain_ascii = idn_to_ascii($domain);
        } else {
            $Punycode = new TrueBV\Punycode();
            $domain_ascii = $Punycode->encode($domain);
        }
        if (preg_match('/^xn\-\-([a-z0-9\-\.]+)\.([a-z0-9\-]+)$/', $domain_ascii)) {
            return $domain_ascii;
        } elseif ($domain == NV_SERVER_NAME) {
            return $domain;
        }
    }
    return '';
}

/**
 * nv_is_url()
 *
 * @param string $url
 * @return
 */
function nv_is_url($url)
{
    if (! preg_match('/^(http|https|ftp|gopher)\:\/\//', $url)) {
        return false;
    }

    $url = nv_strtolower($url);

    if (! ($parts = @parse_url($url))) {
        return false;
    }

    $domain = (isset($parts['host'])) ? nv_check_domain($parts['host']) : '';
    if (empty($domain)) {
        return false;
    }

    if (isset($parts['user']) and ! preg_match('/^([0-9a-z\-]|[\_])*$/', $parts['user'])) {
        return false;
    }

    if (isset($parts['pass']) and ! preg_match('/^([0-9a-z\-]|[\_])*$/', $parts['pass'])) {
        return false;
    }

    if (isset($parts['path']) and ! preg_match('/^[0-9a-z\+\-\_\/\&\=\#\.\,\;\%\\s\!]*$/', $parts['path'])) {
        return false;
    }

    if (isset($parts['query']) and ! preg_match('/^[0-9a-z\+\-\_\/\?\&\=\#\.\,\;\%\\s\!]*$/', $parts['query'])) {
        return false;
    }

    return true;
}

/**
 * nv_check_url()
 *
 * @param string $url
 * @param bool $is_200
 * @return
 */
function nv_check_url($url, $is_200 = 0)
{
    if (empty($url)) {
        return false;
    }

    $url = str_replace(' ', '%20', $url);
    $allow_url_fopen = (ini_get('allow_url_fopen') == '1' or strtolower(ini_get('allow_url_fopen')) == 'on') ? 1 : 0;

    if (nv_function_exists('get_headers') and $allow_url_fopen == 1) {
        $res = get_headers($url);
    } elseif (nv_function_exists('curl_init') and nv_function_exists('curl_exec')) {
        $url_info = @parse_url($url);
        $port = isset($url_info['port']) ? intval($url_info['port']) : 80;

        $userAgents = array(
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0',
            'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)',
            'Mozilla/4.8 [en] (Windows NT 6.0; U)',
            'Opera/9.25 (Windows NT 6.0; U; en)'
        );

        $open_basedir = (ini_get('open_basedir') == '1' or strtolower(ini_get('open_basedir')) == 'on') ? 1 : 0;

        srand(( float )microtime() * 10000000);
        $rand = array_rand($userAgents);
        $agent = $userAgents[$rand];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_PORT, $port);

        if ($open_basedir) {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_USERAGENT, $agent);

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response === false) {
            trigger_error(curl_error($curl), E_USER_WARNING);

            return false;
        } else {
            $res = explode('\n', $response);
        }
    } elseif (nv_function_exists('fsockopen') and nv_function_exists('fgets')) {
        $res = array();
        $url_info = parse_url($url);
        $port = isset($url_info['port']) ? intval($url_info['port']) : 80;
        $fp = fsockopen($url_info['host'], $port, $errno, $errstr, 15);

        if (! $fp) {
            trigger_error($errstr, E_USER_WARNING);
            return false;
        }

        $path = ! empty($url_info['path']) ? $url_info['path'] : '/';
        $path .= ! empty($url_info['query']) ? '?' . $url_info['query'] : '';

        fputs($fp, "HEAD " . $path . " HTTP/1.0\r\n");
        fputs($fp, "Host: " . $url_info['host'] . ":" . $port . "\r\n");
        fputs($fp, "Connection: close\r\n\r\n");

        while (! feof($fp)) {
            if ($header = trim(fgets($fp, 1024))) {
                $res[] = $header;
            }
        }
        @fclose($fp);
    } else {
        trigger_error('error server no support check url', E_USER_WARNING);

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

    if (preg_match('/(301)|(302)|(303)/', $res[0])) {
        foreach ($res as $k => $v) {
            if (preg_match('/location:\s(.*?)$/is', $v, $matches)) {
                ++$is_200;
                $location = trim($matches[1]);

                return nv_check_url($location, $is_200);
            }
        }
    }

    return false;
}

/**
 * nv_url_rewrite()
 *
 * @param string $buffer
 * @param bool $is_url
 * @return
 */
function nv_url_rewrite($buffer, $is_url = false)
{
    global $global_config;

    if ($global_config['rewrite_enable']) {
        if ($is_url) {
            $buffer = "\"" . $buffer . "\"";
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
 * @param mixed $matches
 * @return
 */
function nv_url_rewrite_callback($matches)
{
    global $global_config;

    $query_string = NV_LANG_VARIABLE . '=' . $matches[2];
    $query_array = array();
    $is_amp = (strpos($query_string, '&amp;') !== false);
    parse_str(str_replace('&amp;', '&', $query_string), $query_array);

    if (!empty($query_array)) {
        $op_rewrite = array();
        $op_rewrite_count = 0;
        $query_array_keys = array_keys($query_array);
        if (defined('NV_IS_GODADMIN') or defined('NV_IS_SPADMIN')) {
            $allow_langkeys = $global_config['setup_langs'];
        } else {
            $allow_langkeys = $global_config['allow_sitelangs'];
        }
        if (!in_array($query_array[NV_LANG_VARIABLE], $allow_langkeys) or (isset($query_array[NV_NAME_VARIABLE]) and (!isset($query_array_keys[1]) or $query_array_keys[1] != NV_NAME_VARIABLE)) or (isset($query_array[NV_OP_VARIABLE]) and (!isset($query_array_keys[2]) or $query_array_keys[2] != NV_OP_VARIABLE))) {
            return $matches[0];
        }
        if (!$global_config['rewrite_optional']) {
            $op_rewrite[] = $query_array[NV_LANG_VARIABLE];
            $op_rewrite_count++;
        }
        unset($query_array[NV_LANG_VARIABLE]);
        if (isset($query_array[NV_NAME_VARIABLE])) {
            if (strpos($query_array[NV_NAME_VARIABLE], '/') !== false) {
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
                $op_rewrite_count++;
            }
            unset($query_array[NV_NAME_VARIABLE]);
        }
        $rewrite_end = $global_config['rewrite_endurl'];
        if (isset($query_array[NV_OP_VARIABLE])) {
            if (preg_match('/^tag\/(.*)$/', $query_array[NV_OP_VARIABLE], $m)) {
                if (strpos($m[1], '/') !== false) {
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
            $op_rewrite_count++;
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
 * @param mixed $buffer
 * @return
 */
function nv_change_buffer($buffer)
{
    global $global_config, $client_info;

    if (defined('NV_SYSTEM') and (defined('GOOGLE_ANALYTICS_SYSTEM') or preg_match('/^UA-\d{4,}-\d+$/', $global_config['googleAnalyticsID']))) {
        $_google_analytics = "<script data-show=\"inline\">(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){" . PHP_EOL;
        $_google_analytics .= "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o)," . PHP_EOL;
        $_google_analytics .= "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)" . PHP_EOL;
        $_google_analytics .= "})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');" . PHP_EOL;
        if (preg_match('/^UA-\d{4,}-\d+$/', $global_config['googleAnalyticsID'])) {
            $_google_analytics .= "ga('create', '" . $global_config['googleAnalyticsID'] . "', '" . $global_config['cookie_domain'] . "');" . PHP_EOL;
        }
        if (defined('GOOGLE_ANALYTICS_SYSTEM')) {
            $_google_analytics .= "ga('create', '" . GOOGLE_ANALYTICS_SYSTEM . "', 'auto');" . PHP_EOL;
        }
        $_google_analytics .= "ga('send', 'pageview');" . PHP_EOL;
        $_google_analytics .= "</script>" . PHP_EOL;
        $buffer = preg_replace('/(<\/head[^>]*>)/', PHP_EOL . $_google_analytics . "$1", $buffer, 1);
    }

    if (NV_ANTI_IFRAME and ! $client_info['is_myreferer']) {
        $buffer = preg_replace('/(<body[^>]*>)/', "$1" . PHP_EOL . "<script>if(window.top!==window.self){document.write=\"\";window.top.location=window.self.location;setTimeout(function(){document.body.innerHTML=\"\"},1);window.self.onload=function(){document.body.innerHTML=\"\"}};</script>", $buffer, 1);
    }

    if (NV_CURRENTTIME > $global_config['cronjobs_next_time']) {
        $_body_cronjobs = "<div id=\"run_cronjobs\" style=\"visibility:hidden;display:none;\"><img alt=\"\" src=\"" . NV_BASE_SITEURL . "index.php?second=cronjobs&amp;p=" . nv_genpass() . "\" width=\"1\" height=\"1\" /></div>" . PHP_EOL;
        $buffer = preg_replace('/\s*<\/body>/i', PHP_EOL . $_body_cronjobs . '</body>', $buffer, 1);
    }

    $optimizer = new NukeViet\Core\Optimizer($buffer,  NV_BASE_SITEURL);
    return $optimizer->process();
}

/**
 * nv_insert_logs()
 *
 * @param string $lang
 * @param string $module_name
 * @param string $name_key
 * @param string $note_action
 * @param integer $userid
 * @param string $link_acess
 * @return
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
 * @return
 */
function nv_site_mods()
{
    global $sys_mods, $admin_info, $global_config;

    $site_mods = $sys_mods;
    if (defined('NV_SYSTEM')) {
        foreach ($site_mods as $m_title => $row) {
            if (! nv_user_in_groups($row['groups_view'])) {
                unset($site_mods[$m_title]);
            } elseif (defined('NV_IS_SPADMIN')) {
                $site_mods[$m_title]['is_modadmin'] = true;
            } elseif (defined('NV_IS_ADMIN') and ! empty($row['admins']) and ! empty($admin_info['admin_id']) and in_array($admin_info['admin_id'], explode(',', $row['admins']))) {
                $site_mods[$m_title]['is_modadmin'] = true;
            }
        }
        if (isset($site_mods['users'])) {
            if (defined('NV_IS_USER')) {
                $user_ops = array( 'main', 'logout', 'editinfo', 'avatar', 'groups' );
            } else {
                $user_ops = array( 'main', 'login', 'register', 'lostpass' );
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
                if (! in_array($func, $user_ops)) {
                    unset($site_mods['users']['funcs'][$func]);
                }
            }
        }
    } elseif (defined('NV_ADMIN')) {
        foreach ($site_mods as $m_title => $row) {
            if (defined('NV_IS_SPADMIN')) {
                $allowed = true;
            } elseif (! empty($row['admins']) and in_array($admin_info['admin_id'], explode(',', $row['admins']))) {
                $allowed = true;
            } else {
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
 * @param string $module
 * @param string $type
 * @param array $content
 * @param int $obid
 * @param integer $send_to
 * @param integer $send_from
 * @param integer $area
 * @return
 */
function nv_insert_notification($module, $type, $content = array(), $obid = 0, $send_to = 0, $send_from = 0, $area = 1)
{
    global  $db, $global_config;

    /* $area
     * 0: Khu vuc ngoai site
     * 1: Khu vuc quan tri
     * 2: Ca 2 khu vuc tren
     */

    $new_id = 0;
    if ($global_config['notification_active']) {
        !empty($content) and $content = serialize($content);

        $_sql = 'INSERT INTO ' . NV_NOTIFICATION_GLOBALTABLE . '
		(send_to, send_from, area, language, module, obid, type, content, add_time, view)	VALUES
		(:send_to, :send_from, :area, ' . $db->quote(NV_LANG_DATA) . ', :module, :obid, :type, :content, ' . NV_CURRENTTIME . ', 0)';
        $data_insert = array();
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
 * @param mixed $language
 * @param mixed $module
 * @param mixed $type
 * @param mixed $obid
 * @return
 */
function nv_delete_notification($language, $module, $type, $obid)
{
    global $db_config, $db, $global_config;

    if ($global_config['notification_active']) {
        $sth = $db->prepare('DELETE FROM ' . NV_NOTIFICATION_GLOBALTABLE . ' WHERE language = :language AND module = :module AND obid = :obid AND type = :type');
        $sth->bindParam(':language', $language, PDO::PARAM_STR);
        $sth->bindParam(':module', $module, PDO::PARAM_STR);
        $sth->bindParam(':obid', $obid, PDO::PARAM_INT);
        $sth->bindParam(':type', $type, PDO::PARAM_STR);
        $sth->execute();
    }
    return true;
}

/**
 * nv_status_notification()
 *
 * @param string $language
 * @param string $module
 * @param integer $obid
 * @param string $type
 * @param integer $area
 * @return
 */
function nv_status_notification($language, $module, $type, $obid, $status = 1, $area = 1)
{
    global $db_config, $db, $global_config;

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
 * @param integer $error_code
 * @return void
 *
 */
function nv_redirect_location($url, $error_code = 301)
{
    http_response_code($error_code);
    Header('Location: ' . nv_url_rewrite($url, true));
    exit(0);
}

/**
 * nv_redirect_encrypt()
 *
 * @param string $array
 * @return string
 *
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
 * @param boolean $insite
 * @return string
 *
 */
function nv_redirect_decrypt($string, $insite = true)
{
    global $crypt;
    $url = $crypt->decrypt($string, NV_CHECK_SESSION);
    if (empty($url)) {
        return '';
    }

    if (preg_match('/^(http|https|ftp|gopher)\:\/\//i', $url)) {
        if ($insite and ! preg_match('/^' . nv_preg_quote(NV_MY_DOMAIN) . '/', $url)) {
            return '';
        }

        if (! nv_is_url($url)) {
            return '';
        }
    } elseif (! nv_is_url(NV_MY_DOMAIN . $url)) {
        return '';
    }

    return $url;
}

/**
 * nv_get_redirect()
 *
 * @param string $mode
 * @param bool $decode
 * @return
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
 * @return
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
        $usr_pass = base64_decode(substr($auth_user, 6));
        if (! empty($usr_pass) and strpos($usr_pass, ':') !== false) {
            list($auth_user, $auth_pw) = explode(':', $usr_pass);
        }
        unset($usr_pass);
    }
    return array( 'auth_user' => $auth_user, 'auth_pw' => $auth_pw );
}
