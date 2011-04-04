<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1/9/2010, 23:48
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

require_once ( NV_ROOTDIR . '/includes/utf8/' . $sys_info['string_handler'] . '_string_handler.php' );
require_once ( NV_ROOTDIR . '/includes/utf8/utf8_functions.php' );
require_once ( NV_ROOTDIR . '/includes/core/filesystem_functions.php' );
require_once ( NV_ROOTDIR . '/includes/core/cache_functions.php' );

if ( ! function_exists( 'array_intersect_key' ) )
{

    /**
     * array_intersect_key()
     * 
     * @param mixed $isec
     * @param mixed $keys
     * @return
     */
    function array_intersect_key ( $isec, $keys )
    {
        $num = func_num_args();
        if ( $num > 2 )
        {
            for ( $i = 1; ! empty( $isec ) && $i < $num; $i ++ )
            {
                $arr = func_get_arg( $i );
                foreach ( array_keys( $isec ) as $key )
                {
                    if ( ! isset( $arr[$key] ) ) unset( $isec[$key] );
                }
            }
            return $isec;
        }
        else
        {
            $res = array();
            foreach ( array_keys( $isec ) as $key )
            {
                if ( isset( $keys[$key] ) ) $res[$key] = $isec[$key];
            }
            return $res;
        }
    }
}

if ( ! function_exists( 'array_diff_key' ) )
{

    /**
     * array_diff_key()
     * 
     * @return
     */
    function array_diff_key ( )
    {
        $arrs = func_get_args();
        $result = array_shift( $arrs );
        foreach ( $arrs as $array )
        {
            foreach ( $result as $key => $v )
            {
                if ( array_key_exists( $key, $array ) )
                {
                    unset( $result[$key] );
                }
            }
        }
        return $result;
    }
}

/**
 * nv_object2array()
 * 
 * @param mixed $data
 * @return
 */
function nv_object2array ( $data )
{
    if ( is_object( $data ) ) $data = get_object_vars( $data );
    return is_array( $data ) ? array_map( __function__, $data ) : $data;
}

/**
 * nv_getenv()
 * 
 * @param mixed $key
 * @return
 */
function nv_getenv ( $key )
{
    if ( isset( $_SERVER[$key] ) ) return $_SERVER[$key];
    elseif ( isset( $_ENV[$key] ) ) return $_ENV[$key];
    elseif ( @getenv( $key ) ) return @getenv( $key );
    elseif ( function_exists( 'apache_getenv' ) && apache_getenv( $key, true ) ) return apache_getenv( $key, true );
    return "";
}

/**
 * nv_preg_quote()
 * 
 * @param mixed $string
 * @return
 */
function nv_preg_quote ( $string )
{
    return preg_quote( $string, "/" );
}

/**
 * nv_is_myreferer()
 * 
 * @param string $referer
 * @return
 */
function nv_is_myreferer ( $referer = "" )
{
    if ( empty( $referer ) ) $referer = urldecode( nv_getenv( 'HTTP_REFERER' ) );
    if ( empty( $referer ) ) return 2;
    $server_name = preg_replace( '/^www\./e', '', nv_getenv( "HTTP_HOST" ) );
    $referer_nohttp = preg_replace( array( '/^[a-zA-Z]+\:\/\//e', '/www\./e' ), array( '', '' ), $referer );
    if ( preg_match( "/^" . preg_quote( $server_name ) . "/", $referer_nohttp ) ) return 1;
    return 0;
}

/**
 * nv_is_blocker_proxy()
 * 
 * @param mixed $is_proxy
 * @param mixed $proxy_blocker
 * @return
 */
function nv_is_blocker_proxy ( $is_proxy, $proxy_blocker )
{
    $blocker = false;
    switch ( $proxy_blocker )
    {
        case 1:
            if ( $is_proxy == 'Strong' ) $blocker = true;
            break;
        case 2:
            if ( $is_proxy == 'Strong' || $is_proxy == 'Mild' ) $blocker = true;
            break;
        case 3:
            if ( $is_proxy != 'No' ) $blocker = true;
            break;
    }
    return $blocker;
}

/**
 * nv_is_banIp()
 * 
 * @param mixed $ip
 * @return
 */
function nv_is_banIp ( $ip )
{
    global $global_config;
    if ( file_exists( NV_ROOTDIR . "/" . NV_DATADIR . "/banip.php" ) )
    {
        include ( NV_ROOTDIR . "/" . NV_DATADIR . "/banip.php" );
        $array_banip = ( defined( 'NV_ADMIN' ) ) ? $array_banip_admin : $array_banip_site;
        foreach ( $array_banip as $ip_i => $array_ip )
        {
            if ( $array_ip['begintime'] < NV_CURRENTTIME and ( $array_ip['endtime'] == 0 or $array_ip['endtime'] > NV_CURRENTTIME ) )
            {
                if ( preg_replace( $array_ip['mask'], "", $ip ) == preg_replace( $array_ip['mask'], "", $ip_i ) )
                {
                    return true;
                }
            }
        }
    }
    return false;
}

/**
 * nv_checkagent()
 * 
 * @param mixed $agent
 * @return
 */
function nv_checkagent ( $agent )
{
    $agent = htmlspecialchars( substr( $agent, 0, 255 ) );
    $agent = str_replace( ",", "-", $agent );
    $agent = str_replace( "<", "(", $agent );
    $agent = ( ! empty( $agent ) and $agent != "-" ) ? $agent : "none";
    return $agent;
}

/**
 * nv_check_bot()
 * 
 * @return
 */
function nv_check_bot ( )
{
    global $client_info;
    $bot_info = array();
    $file_bots = NV_ROOTDIR . "/" . NV_DATADIR . "/bots.config";
    $bots = ( file_exists( $file_bots ) and filesize( $file_bots ) != 0 ) ? unserialize( file_get_contents( $file_bots ) ) : array();
    if ( empty( $bots ) and file_exists( NV_ROOTDIR . "/includes/bots.php" ) )
    {
        include ( NV_ROOTDIR . "/includes/bots.php" );
    }
    if ( ! empty( $bots ) )
    {
        $bot = array();
        foreach ( $bots as $name => $values )
        {
            $bot = false;
            if ( $values['agent'] and preg_match( '#' . str_replace( '\*', '.*?', nv_preg_quote( $values['agent'], '#' ) ) . '#i', $client_info['agent'] ) ) $bot = true;
            if ( ! empty( $values['ips'] ) and ( $bot or ! $values['agent'] ) )
            {
                $bot = false;
                $ips = implode( "|", array_map( "nv_preg_quote", explode( "|", $values['ips'] ) ) );
                if ( preg_match( "/^" . $ips . "/", $client_info['ip'] ) ) $bot = true;
            }
            if ( $bot )
            {
                $bot_info = array( 'name' => $name, 'agent' => $values['agent'], 'ip' => $client_info['ip'], 'allowed' => $values['allowed'] );
                break;
            }
        }
    }
    return $bot_info;
}

/**
 * nv_checkmobile()
 * 
 * @return
 */
function nv_checkmobile ( )
{
    if ( isset( $_SERVER['HTTP_X_WAP_PROFILE'] ) || isset( $_SERVER['HTTP_PROFILE'] ) || isset( $_SERVER['X-OperaMini-Features'] ) || isset( $_SERVER['UA-pixels'] ) ) return 1;
    if ( isset( $_SERVER['HTTP_ACCEPT'] ) && preg_match( "/wap\.|\.wap/i", $_SERVER["HTTP_ACCEPT"] ) ) return 1;
    if ( preg_match( "/Creative\ AutoUpdate/i", NV_USER_AGENT ) ) return 0;
    $uamatches = array( "midp", "j2me", "avantg", "docomo", "novarra", "palmos", "palmsource", "240x320", "opwv", "chtml", "pda", "windows\ ce", "mmp\/", "blackberry", "mib\/", "symbian", "wireless", "nokia", "hand", "mobi", "phone", "cdm", "up\.b", "audio", "SIE\-", "SEC\-", "samsung", "HTC", "mot\-", "mitsu", "sagem", "sony", "alcatel", "lg", "erics", "vx", "NEC", "philips", "mmm", "xx", "panasonic", "sharp", "wap", "sch", "rover", "pocket", "benq", "java", "pt", "pg", "vox", "amoi", "bird", "compal", "kg", "voda", "sany", "kdd", "dbt", "sendo", "sgh", "gradi", "jb", "\d\d\di", "moto" );
    foreach ( $uamatches as $uastring )
    {
        if ( preg_match( "/" . $uastring . "/i", NV_USER_AGENT ) ) return 1;
    }
    return 0;
}

/**
 * nv_getBrowser()
 * 
 * @param mixed $agent
 * @param mixed $brinifile
 * @return
 */
function nv_getBrowser ( $agent, $brinifile )
{
    $browsers = nv_parse_ini_file( $brinifile, true );
    foreach ( $browsers as $key => $info )
    {
        if ( preg_match( "#" . $info['rule'] . "#i", $agent, $results ) )
        {
            if ( isset( $results[1] ) ) return ( $key . '|' . $info['name'] . ' v' . $results[1] );
            else return ( $key . '|' . $info['name'] );
        }
    }
    return ( "Unknown|Unknown" );
}

/**
 * nv_getOs()
 * 
 * @param mixed $agent
 * @param mixed $osinifile
 * @return
 */
function nv_getOs ( $agent, $osinifile )
{
    $os = nv_parse_ini_file( $osinifile, true );
    foreach ( $os as $key => $info )
    {
        if ( preg_match( "#" . $info['rule'] . "#i", $agent, $results ) )
        {
            if ( strstr( $key, "win" ) ) return ( $key . '|' . $info['name'] );
            elseif ( isset( $results[1] ) ) return ( $key . '|' . $info['name'] . ' ' . $results[1] );
            else return ( $key . '|' . $info['name'] );
        }
    }
    return ( "Unspecified|Unspecified" );
}

/**
 * nv_convertfromBytes()
 * 
 * @param mixed $size
 * @return
 */
function nv_convertfromBytes ( $size )
{
    if ( $size <= 0 ) return '0 bytes';
    if ( $size == 1 ) return '1 bytes';
    if ( $size < 1024 ) return $size . ' bytes';
    
    $i = 0;
    $iec = array( "bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB" );
    while ( ( $size / 1024 ) > 1 )
    {
        $size = $size / 1024;
        $i ++;
    }
    return number_format( $size, 2 ) . ' ' . $iec[$i];
}

/**
 * nv_convertfromSec()
 * 
 * @param mixed $sec
 * @return
 */
function nv_convertfromSec ( $sec )
{
    global $lang_global;
    $sec = intval( $sec );
    $min = 60;
    $hour = 60 * $min;
    $day = 24 * $hour;
    $year = 365 * $day;
    if ( $sec == 0 )
    {
        return "";
    }
    elseif ( $sec < $min )
    {
        return $sec . " " . $lang_global['sec'];
    }
    elseif ( $sec < $hour )
    {
        $sd = $sec % $min;
        return trim( floor( $sec / $min ) . " " . " " . $lang_global['min'] . ( $sd ? " " . nv_convertfromSec( $sd ) : "" ) );
    }
    elseif ( $sec < $day )
    {
        $sd = $sec % $hour;
        return trim( floor( $sec / $hour ) . " " . $lang_global['hour'] . ( $sd ? " " . nv_convertfromSec( $sd ) : "" ) );
    }
    elseif ( $sec < $year )
    {
        $sd = $sec % $day;
        return trim( floor( $sec / $day ) . " " . $lang_global['day'] . ( $sd ? " " . nv_convertfromSec( $sd ) : "" ) );
    }
    else
    {
        $sd = $sec % $year;
        return trim( floor( $sec / $year ) . " " . $lang_global['year'] . ( $sd ? " " . nv_convertfromSec( $sd ) : "" ) );
    }
}

/**
 * nv_converttoBytes()
 * 
 * @param mixed $string
 * @return
 */
function nv_converttoBytes ( $string )
{
    if ( preg_match( '/^([0-9\.]+)[ ]*([b|k|m|g|t|p|e|z|y]*)/i', $string, $matches ) )
    {
        $suffixes = array( "B" => 0, "K" => 1, "M" => 2, "G" => 3, "T" => 4, "P" => 5, "E" => 6, "Z" => 7, "Y" => 8 );
        if ( empty( $matches[2] ) ) return $matches[1];
        if ( isset( $suffixes[strtoupper( $matches[2] )] ) ) return round( $matches[1] * pow( 1024, $suffixes[strtoupper( $matches[2] )] ) );
    }
    return false;
}

/**
 * nv_base64_encode()
 * 
 * @param mixed $input
 * @return
 */
function nv_base64_encode ( $input )
{
    return strtr( base64_encode( $input ), '+/=', '-_,' );
}

/**
 * nv_base64_decode()
 * 
 * @param mixed $input
 * @return
 */
function nv_base64_decode ( $input )
{
    return base64_decode( strtr( $input, '-_,', '+/=' ) );
}

/**
 * nv_function_exists()
 * 
 * @param mixed $funcName
 * @return
 */
function nv_function_exists ( $funcName )
{
    global $sys_info;
    
    return ( function_exists( $funcName ) and ! in_array( $funcName, $sys_info['disable_functions'] ) );
}

/**
 * nv_class_exists()
 * 
 * @param mixed $clName
 * @return
 */
function nv_class_exists ( $clName )
{
    global $sys_info;
    
    return ( class_exists( $clName ) and ! in_array( $clName, $sys_info['disable_functions'] ) );
}

/**
 * nv_check_valid_login()
 * 
 * @param mixed $login
 * @param mixed $max
 * @param mixed $min
 * @return
 */
function nv_check_valid_login ( $login, $max, $min )
{
    global $lang_global, $global_config;
    $login = strip_tags( trim( $login ) );
    if ( empty( $login ) ) return $lang_global['nickname_empty'];
    elseif ( strlen( $login ) > $max ) return sprintf( $lang_global['nicknamelong'], $login, $max );
    elseif ( strlen( $login ) < $min ) return sprintf( $lang_global['nicknameadjective'], $login, $min );
    else return "";
}

/**
 * nv_check_valid_pass()
 * 
 * @param mixed $pass
 * @param mixed $max
 * @param mixed $min
 * @return
 */
function nv_check_valid_pass ( $pass, $max, $min )
{
    global $lang_global, $global_config;
    $pass = strip_tags( trim( $pass ) );
    if ( empty( $pass ) ) return $lang_global['password_empty'];
    elseif ( strlen( $pass ) > $max ) return sprintf( $lang_global['passwordlong'], $pass, $max );
    elseif ( strlen( $pass ) < $min ) return sprintf( $lang_global['passwordadjective'], $pass, $min );
    else return "";
}

/**
 * nv_check_valid_email()
 * 
 * @param mixed $mail
 * @return
 */
function nv_check_valid_email ( $mail )
{
    global $lang_global, $global_config;
    
    $mail = strip_tags( trim( $mail ) );
    
    if ( empty( $mail ) ) return $lang_global['email_empty'];
    
    if ( function_exists( 'filter_var' ) and filter_var( $mail, FILTER_VALIDATE_EMAIL ) === false )
    {
        return sprintf( $lang_global['email_incorrect'], $mail );
    
    }
    elseif ( ! preg_match( $global_config['check_email'], $mail ) )
    {
        return sprintf( $lang_global['email_incorrect'], $mail );
    }
    
    if ( ! preg_match( "/\.(ac|ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|asia|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cat|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|info|int|io|iq|ir|is|it|je|jm|jo|jobs|jp|ke|kg|kh|ki|km|kn|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mobi|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tel|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|travel|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$/", $mail ) )
    {
        return sprintf( $lang_global['email_incorrect'], $mail );
    }
    
    return "";
}

/**
 * nv_capcha_txt()
 * 
 * @param mixed $seccode
 * @param string $scaptcha
 * @return
 */
function nv_capcha_txt ( $seccode, $scaptcha = "captcha" )
{
    global $sys_info, $global_config, $nv_Request;
    
    $scaptcha = preg_replace( '/[^a-z0-9]/', '', $scaptcha );
    $skeycaptcha = ( $scaptcha == "captcha" ) ? "random_num" : "random_" . substr( $scaptcha, 0, 20 );
    
    $seccode = strtoupper( $seccode );
    $return = false;
    if ( ! $sys_info['gd_support'] ) return true;
    $random_num = $nv_Request->get_string( $skeycaptcha, 'session', 0 );
    $datekey = date( "F j" );
    $rcode = strtoupper( md5( NV_USER_AGENT . $global_config['sitekey'] . $random_num . $datekey ) );
    if ( preg_match( "/^[a-zA-Z0-9]{" . NV_GFX_NUM . "}$/", $seccode ) and $seccode == substr( $rcode, 2, NV_GFX_NUM ) )
    {
        $return = true;
    }
    mt_srand( ( double )microtime() * 1000000 );
    $maxran = 1000000;
    $random_num = mt_rand( 0, $maxran );
    $nv_Request->set_Session( $skeycaptcha, $random_num );
    return $return;
}

/**
 * nv_genpass()
 * 
 * @param integer $length
 * @return
 */
function nv_genpass ( $length = 8 )
{
    $pass = chr( mt_rand( 65, 90 ) );
    for ( $k = 0; $k < $length - 1; $k ++ )
    {
        $probab = mt_rand( 1, 10 );
        $pass .= ( $probab <= 8 ) ? chr( mt_rand( 97, 122 ) ) : chr( mt_rand( 48, 57 ) );
    }
    return $pass;
}

/**
 * nv_EncodeEmail()
 * 
 * @param mixed $strEmail
 * @param string $strDisplay
 * @param bool $blnCreateLink
 * @return
 */
function nv_EncodeEmail ( $strEmail, $strDisplay = '', $blnCreateLink = true )
{
    $strMailto = "&#109;&#097;&#105;&#108;&#116;&#111;&#058;";
    $strEncodedEmail = "";
    for ( $i = 0; $i < strlen( $strEmail ); $i ++ )
    {
        $strEncodedEmail .= "&#" . ord( substr( $strEmail, $i ) ) . ";";
    }
    $strDisplay = ( strlen( trim( $strDisplay ) ) > 0 ) ? $strDisplay : $strEncodedEmail;
    if ( $blnCreateLink ) return "<a href=\"" . $strMailto . $strEncodedEmail . "\">" . $strDisplay . "</a>";
    else return $strDisplay;
}

/**
 * nv_user_groups()
 * 
 * @param mixed $in_groups
 * @return
 */
function nv_user_groups ( $in_groups )
{
    global $db;
    
    if ( empty( $in_groups ) ) return "";
    
    $groups = array();
    
    $sql = "SELECT `group_id` FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `group_id` IN (" . $in_groups . ") AND `act`=1 AND (`exp_time`=0 OR `exp_time` >= " . NV_CURRENTTIME . ")";
    $result = $db->sql_query( $sql );
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $groups[] = $row['group_id'];
    }
    
    if ( empty( $groups ) ) return "";
    
    return implode( ",", $groups );
}

/**
 * nv_is_in_groups()
 * 
 * @param mixed $in_groups
 * @param mixed $groups
 * @return
 */
function nv_is_in_groups ( $in_groups, $groups )
{
    if ( empty( $groups ) || empty( $in_groups ) ) return false;
    $in_groups = explode( ",", $in_groups );
    $groups = explode( ",", $groups );
    if ( array_intersect( $in_groups, $groups ) != array() ) return true;
    return false;
}

/**
 * nv_set_allow()
 * 
 * @param mixed $who
 * @param mixed $groups
 * @return
 */
function nv_set_allow ( $who, $groups )
{
    global $user_info;
    
    if ( ! $who or ( $who == 1 and defined( 'NV_IS_USER' ) ) or ( $who == 2 and defined( 'NV_IS_ADMIN' ) ) )
    {
        return true;
    }
    elseif ( $who == 3 and ! empty( $groups ) and defined( 'NV_IS_USER' ) and nv_is_in_groups( $user_info['in_groups'], $groups ) )
    {
        return true;
    }
    
    return false;
}

/**
 * nv_date()
 * 
 * @param mixed $format
 * @param integer $time
 * @return
 */
function nv_date ( $format, $time = 0 )
{
    global $lang_global;
    if ( ! $time ) $time = NV_CURRENTTIME;
    $return = date( $format, $time );
    $searchs = array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
    $replaces = array();
    foreach ( $searchs as $search )
    {
        $replaces[] = $lang_global[strtolower( $search )];
    }
    $return = str_replace( $searchs, $replaces, $return );
    
    $searchs = array( 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' );
    $replaces = array();
    foreach ( $searchs as $search )
    {
        $replaces[] = $lang_global[strtolower( $search )];
    }
    $return = str_replace( $searchs, $replaces, $return );
    
    return $return;
}

/**
 * nv_monthname()
 * 
 * @param mixed $i
 * @return
 */
function nv_monthname ( $i )
{
    global $lang_global;
    $month_names = array( $lang_global['january'], $lang_global['february'], $lang_global['march'], $lang_global['april'], $lang_global['may'], $lang_global['june'], $lang_global['july'], $lang_global['august'], $lang_global['september'], $lang_global['october'], $lang_global['november'], $lang_global['december'] );
    $i = $i - 1;
    return $month_names[$i];
}

/**
 * nv_unhtmlspecialchars()
 * 
 * @param mixed $string
 * @return
 */
function nv_unhtmlspecialchars ( $string )
{
    if ( empty( $string ) ) return $string;
    if ( is_array( $string ) )
    {
        foreach ( array_keys( $string ) as $key )
        {
            $string[$key] = nv_unhtmlspecialchars( $string[$key] );
        }
    }
    else
    {
        $search = array( '&amp;', '&#039;', '&quot;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x23;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;' );
        $replace = array( '&', '\'', '"', '<', '>', '\\', '/', '(', ')', '*', '[', ']', '!', '=', '#', '%', '^', ':', '{', '}', '`', '~' );
        $string = str_replace( $search, $replace, $string );
    }
    
    return $string;
}

/**
 * nv_htmlspecialchars()
 * 
 * @param mixed $string
 * @return
 */
function nv_htmlspecialchars ( $string )
{
    if ( empty( $string ) ) return $string;
    if ( is_array( $string ) )
    {
        foreach ( array_keys( $string ) as $key )
        {
            $string[$key] = nv_htmlspecialchars( $string[$key] );
        }
    }
    else
    {
        $search = array( '&', '\'', '"', '<', '>', '\\', '/', '(', ')', '*', '[', ']', '!', '=', '%', '^', ':', '{', '}', '`', '~' );
        $replace = array( '&amp;', '&#039;', '&quot;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;' );
        $string = str_replace( $replace, $search, $string );
        $string = str_replace( "&#x23;", "#", $string );
        $string = str_replace( $search, $replace, $string );
        $string = preg_replace( "/([^\&]+)\#/", "\\1&#x23;", $string );
    }
    
    return $string;
}

/**
 * strip_punctuation()
 * 
 * @param mixed $text
 * @return
 */
function strip_punctuation ( $text )
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
    
    return preg_replace( array( // Remove separator, control, formatting, surrogate, open/close quotes.
'/[\p{Z}\p{Cc}\p{Cf}\p{Cs}\p{Pi}\p{Pf}]/u', // Remove other punctuation except special cases
'/\p{Po}(?<![' . $specialquotes . $numseparators . $urlall . $nummodifiers . '])/u', // Remove non-URL open/close brackets, except URL brackets.
'/[\p{Ps}\p{Pe}](?<![' . $urlbrackets . '])/u', // Remove special quotes, dashes, connectors, number separators, and URL characters followed by a space
'/[' . $specialquotes . $numseparators . $urlspaceafter . '\p{Pd}\p{Pc}]+((?= )|$)/u', // Remove special quotes, connectors, and URL characters preceded by a space
'/((?<= )|^)[' . $specialquotes . $urlspacebefore . '\p{Pc}]+/u', // Remove dashes preceded by a space, but not followed by a number
'/((?<= )|^)\p{Pd}+(?![\p{N}\p{Sc}])/u', // Remove consecutive spaces
'/ +/' ), ' ', $text );
}

/**
 * nv_nl2br()
 * 
 * @param mixed $text
 * @param string $replacement
 * @return
 */
function nv_nl2br ( $text, $replacement = '<br />' )
{
    if ( empty( $text ) ) return '';
    return strtr( $text, array( "\r\n" => trim( $replacement ), "\r" => trim( $replacement ), "\n" => trim( $replacement ) ) );
}

/**
 * nv_br2nl()
 * 
 * @param mixed $text
 * @return
 */
function nv_br2nl ( $text )
{
    if ( empty( $text ) ) return '';
    return preg_replace( '/\<br(\s*)?\/?(\s*)?\>/i', chr( 13 ) . chr( 10 ), $text );
}

/**
 * nv_editor_nl2br()
 * 
 * @param mixed $text
 * @return
 */
function nv_editor_nl2br ( $text )
{
    if ( empty( $text ) ) return '';
    $replacement = defined( 'NV_EDITOR' ) ? '' : '<br />';
    return strtr( $text, array( "\r\n" => trim( $replacement ), "\r" => trim( $replacement ), "\n" => trim( $replacement ) ) );
}

/**
 * nv_editor_br2nl()
 * 
 * @param mixed $text
 * @return
 */
function nv_editor_br2nl ( $text )
{
    if ( empty( $text ) ) return '';
    if ( defined( 'NV_EDITOR' ) ) return $text;
    return preg_replace( '/\<br(\s*)?\/?(\s*)?\>/i', chr( 13 ) . chr( 10 ), $text );
}

/**
 * filter_text_input()
 * 
 * @param mixed $inputname
 * @param string $mode
 * @param string $default
 * @param bool $specialchars
 * @param integer $maxlength
 * @param mixed $preg_replace
 * @return
 */
function filter_text_input ( $inputname, $mode = 'request', $default = '', $specialchars = false, $maxlength = 0, $preg_replace = array() )
{
    global $nv_Request;
    
    $value = $nv_Request->get_string( $inputname, $mode, $default );
    $value = strip_tags( $value );
    if ( ( bool )$specialchars == true )
    {
        $value = nv_htmlspecialchars( $value );
    }
    if ( ( int )$maxlength > 0 )
    {
        $value = nv_substr( $value, 0, $maxlength );
    }
    if ( ! empty( $preg_replace ) )
    {
        if ( isset( $preg_replace['pattern'] ) and ! empty( $preg_replace['pattern'] ) and isset( $preg_replace['replacement'] ) )
        {
            $value = preg_replace( $preg_replace['pattern'], $preg_replace['replacement'], $value );
        }
    }
    return trim( $value );
}

/**
 * filter_text_textarea()
 * 
 * @param mixed $inputname
 * @param string $default
 * @param string $allowed_html_tags
 * @param bool $save
 * @param string $nl2br_replacement
 * @return
 */
function filter_text_textarea ( $inputname, $default = '', $allowed_html_tags = '', $save = false, $nl2br_replacement = '<br />' )
{
    global $nv_Request;
    $value = $nv_Request->get_string( $inputname, 'post', $default );
    if ( empty( $value ) ) return $value;
    if ( ! empty( $allowed_html_tags ) )
    {
        $allowed_html_tags = array_map( "trim", explode( ",", $allowed_html_tags ) );
        $allowed_html_tags = "<" . implode( "><", $allowed_html_tags ) . ">";
        $value = strip_tags( $value, $allowed_html_tags );
    }
    if ( ( bool )$save ) $value = nv_nl2br( $value, $nl2br_replacement );
    return $value;
}

/**
 * nv_editor_filter_textarea()
 * 
 * @param mixed $inputname
 * @param string $default
 * @param string $allowed_html_tags
 * @param bool $save
 * @param string $nl2br_replacement
 * @return
 */
function nv_editor_filter_textarea ( $inputname, $default = '', $allowed_html_tags = '', $save = false, $nl2br_replacement = '<br />' )
{
    global $nv_Request;
    $value = $nv_Request->get_string( $inputname, 'post', $default );
    if ( strip_tags( $value ) == '' ) return '';
    
    if ( ! empty( $allowed_html_tags ) and ! defined( 'NV_EDITOR' ) )
    {
        $allowed_html_tags = array_map( "trim", explode( ",", $allowed_html_tags ) );
        $allowed_html_tags = "<" . implode( "><", $allowed_html_tags ) . ">";
        $value = strip_tags( $value, $allowed_html_tags );
    }
    if ( ( bool )$save )
    {
        $value = nv_editor_nl2br( $value, $nl2br_replacement );
    }
    return $value;
}

/**
 * nv_get_keywords()
 * 
 * @param string $content
 * @return
 */
function nv_get_keywords ( $content = "" )
{
    if ( empty( $content ) ) return ( "" );
    
    $content = strip_tags( $content );
    $content = nv_unhtmlspecialchars( $content );
    $content = strip_punctuation( $content );
    $content = trim( $content );
    $content = nv_strtolower( $content );
    
    $content = " " . $content . " ";
    
    $pattern_word = array();
    
    if ( NV_SITEWORDS_MIN_3WORDS_LENGTH > 0 and NV_SITEWORDS_MIN_3WORDS_PHRASE_OCCUR > 0 )
    {
        $pattern_word[] = "/[\s]+([\S]{" . NV_SITEWORDS_MIN_3WORDS_LENGTH . ",}\s[\S]{" . NV_SITEWORDS_MIN_3WORDS_LENGTH . ",}\s[\S]{" . NV_SITEWORDS_MIN_3WORDS_LENGTH . ",})(\s.*\\1){" . NV_SITEWORDS_MIN_3WORDS_PHRASE_OCCUR . ",}[\s]+/uis";
    }
    
    if ( NV_SITEWORDS_MIN_2WORDS_LENGTH > 0 and NV_SITEWORDS_MIN_2WORDS_PHRASE_OCCUR > 0 )
    {
        $pattern_word[] = "/[\s]+([\S]{" . NV_SITEWORDS_MIN_2WORDS_LENGTH . ",}\s[\S]{" . NV_SITEWORDS_MIN_2WORDS_LENGTH . ",})(\s.*\\1){" . NV_SITEWORDS_MIN_2WORDS_PHRASE_OCCUR . ",}[\s]+/uis";
    }
    
    if ( NV_SITEWORDS_MIN_WORD_LENGTH > 0 and NV_SITEWORDS_MIN_WORD_OCCUR > 0 )
    {
        $pattern_word[] = "/[\s]+([\S]{" . NV_SITEWORDS_MIN_WORD_LENGTH . ",})(\s.*\\1){" . NV_SITEWORDS_MIN_WORD_OCCUR . ",}[\s]+/uis";
    }
    
    if ( empty( $pattern_word ) ) return ( "" );
    
    $keywords = array();
    $lenght = 0;
    $max_strlen = min( NV_SITEWORDS_MAX_STRLEN, 300 );
    
    foreach ( $pattern_word as $pattern )
    {
        unset( $matches );
        while ( preg_match( $pattern, $content, $matches ) )
        {
            $keywords[] = $matches[1];
            $lenght += nv_strlen( $matches[1] );
            
            $content = preg_replace( "/[\s]+(" . preg_quote( $matches[1] ) . ")[\s]+/uis", " ", $content );
            
            if ( $lenght >= $max_strlen ) break;
        }
        
        if ( $lenght >= $max_strlen ) break;
    }
    
    $keywords = array_unique( $keywords );
    $keywords = implode( ",", $keywords );
    
    return $keywords;
}

/**
 * nv_sendmail()
 * 
 * @param mixed $from
 * @param mixed $to
 * @param mixed $subject
 * @param mixed $message
 * @param string $files
 * @return
 */
function nv_sendmail ( $from, $to, $subject, $message, $files = '' )
{
    global $global_config, $lang_global, $sys_info;
    $sendmail_from = ini_get( 'sendmail_from' );
    require_once ( NV_ROOTDIR . '/includes/phpmailer/class.phpmailer.php' );
    try
    {
        $mail = new PHPMailer( true );
        $mail->CharSet = $global_config['site_charset'];
        $mailer_mode = strtolower( $global_config['mailer_mode'] );
        if ( $mailer_mode == 'smtp' )
        {
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->Port = $global_config['smtp_port'];
            $mail->Host = $global_config['smtp_host'];
            $mail->Username = $global_config['smtp_username'];
            $mail->Password = $global_config['smtp_password'];
            $SMTPSecure = intval( $global_config['smtp_ssl'] );
            switch ( $SMTPSecure )
            {
                case 1:
                    $mail->SMTPSecure = 'ssl';
                    break;
                case 2:
                    $mail->SMTPSecure = 'tls';
                    break;
                default:
                    $mail->SMTPSecure = '';
            }
        }
        elseif ( $mailer_mode == 'sendmail' )
        {
            $mail->IsSendmail();
        }
        elseif ( ! in_array( 'mail', $sys_info['disable_functions'] ) )
        {
            $mail->IsMail();
        }
        else
        {
            return false;
        }
        $message = nv_change_buffer( $message );
        
        $message = nv_unhtmlspecialchars( $message );
        $subject = nv_unhtmlspecialchars( $subject );
        
        $mail->From = $sendmail_from;
        $mail->FromName = $global_config['site_name'];
        if ( is_array( $from ) )
        {
            $mail->AddReplyTo( $from[1], $from[0] );
        }
        else
        {
            $mail->AddReplyTo( $from );
        }
        $mail->AddAddress( $to );
        $mail->Subject = $subject;
        $mail->WordWrap = 120;
        $mail->MsgHTML( $message );
        $mail->IsHTML( true );
        if ( ! empty( $files ) )
        {
            $files = array_map( "trim", explode( ",", $files ) );
            foreach ( $files as $file )
            {
                $mail->AddAttachment( $file );
            }
        }
        $send = $mail->Send();
        if ( ! $send )
        {
            trigger_error( $mail->ErrorInfo, E_USER_WARNING );
        }
        return $send;
    }
    catch ( phpmailerException $e )
    {
        trigger_error( $e->errorMessage(), E_USER_WARNING );
        return false;
    }
}

/**
 * nv_generate_page()
 * 
 * @param mixed $base_url
 * @param mixed $num_items
 * @param mixed $per_page
 * @param mixed $start_item
 * @param bool $add_prevnext_text
 * @param bool $onclick
 * @param string $js_func_name
 * @param string $containerid
 * @return
 */
function nv_generate_page ( $base_url, $num_items, $per_page, $start_item, $add_prevnext_text = true, $onclick = false, $js_func_name = 'nv_urldecode_ajax', $containerid = 'generate_page' )
{
    global $lang_global;
    
    $total_pages = ceil( $num_items / $per_page );
    if ( $total_pages == 1 ) return '';
    @$on_page = floor( $start_item / $per_page ) + 1;
    
    if ( ! is_array( $base_url ) )
    {
        $amp = preg_match( "/\?/", $base_url ) ? "&amp;" : "?";
        $amp .= "page=";
    }
    else
    {
        $amp = $base_url['amp'];
        $base_url = $base_url['link'];
    }
    
    $page_string = "";
    if ( $total_pages > 10 )
    {
        $init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;
        for ( $i = 1; $i <= $init_page_max; $i ++ )
        {
            $href = ! $onclick ? "href=\"" . $base_url . $amp . ( ( $i - 1 ) * $per_page ) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $base_url . $amp . ( ( $i - 1 ) * $per_page ) ) ) . "','" . $containerid . "')\"";
            $page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
            if ( $i < $init_page_max ) $page_string .= ", ";
        }
        if ( $total_pages > 3 )
        {
            if ( $on_page > 1 && $on_page < $total_pages )
            {
                $page_string .= ( $on_page > 5 ) ? " ... " : ", ";
                $init_page_min = ( $on_page > 4 ) ? $on_page : 5;
                $init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;
                for ( $i = $init_page_min - 1; $i < $init_page_max + 2; $i ++ )
                {
                    $href = ! $onclick ? "href=\"" . $base_url . $amp . ( ( $i - 1 ) * $per_page ) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $base_url . $amp . ( ( $i - 1 ) * $per_page ) ) ) . "','" . $containerid . "')\"";
                    $page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
                    if ( $i < $init_page_max + 1 )
                    {
                        $page_string .= ", ";
                    }
                }
                $page_string .= ( $on_page < $total_pages - 4 ) ? " ... " : ", ";
            }
            else
            {
                $page_string .= " ... ";
            }
            
            for ( $i = $total_pages - 2; $i < $total_pages + 1; $i ++ )
            {
                $href = ! $onclick ? "href=\"" . $base_url . $amp . ( ( $i - 1 ) * $per_page ) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $base_url . $amp . ( ( $i - 1 ) * $per_page ) ) ) . "','" . $containerid . "')\"";
                $page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
                if ( $i < $total_pages )
                {
                    $page_string .= ", ";
                }
            }
        }
    }
    else
    {
        for ( $i = 1; $i < $total_pages + 1; $i ++ )
        {
            $href = ! $onclick ? "href=\"" . $base_url . $amp . ( ( $i - 1 ) * $per_page ) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $base_url . $amp . ( ( $i - 1 ) * $per_page ) ) ) . "','" . $containerid . "')\"";
            $page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
            if ( $i < $total_pages )
            {
                $page_string .= ", ";
            }
        }
    }
    if ( $add_prevnext_text )
    {
        if ( $on_page > 1 )
        {
            $href = ! $onclick ? "href=\"" . $base_url . $amp . ( ( $on_page - 2 ) * $per_page ) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $base_url . $amp . ( ( $on_page - 2 ) * $per_page ) ) ) . "','" . $containerid . "')\"";
            $page_string = "&nbsp;&nbsp;<span><a " . $href . ">" . $lang_global['pageprev'] . "</a></span>&nbsp;&nbsp;" . $page_string;
        }
        if ( $on_page < $total_pages )
        {
            $href = ! $onclick ? "href=\"" . $base_url . $amp . ( $on_page * $per_page ) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $base_url . $amp . ( $on_page * $per_page ) ) ) . "','" . $containerid . "')\"";
            $page_string .= "&nbsp;&nbsp;<span><a " . $href . ">" . $lang_global['pagenext'] . "</a></span>";
        }
    }
    return $page_string;
}

/**
 * nv_is_url()
 * 
 * @param mixed $url
 * @return
 */
function nv_is_url ( $url )
{
    global $ips;
    $url = substr( $url, - 1 ) == "/" ? substr( $url, 0, - 1 ) : $url;
    if ( empty( $url ) ) return false;
    $url = nv_strtolower( $url );
    if ( ! ( $parts = @parse_url( $url ) ) ) return false;
    else
    {
        if ( ! isset( $parts['scheme'] ) or ! isset( $parts['host'] ) or ( $parts['scheme'] != "http" && $parts['scheme'] != "https" && $parts['scheme'] != "ftp" && $parts['scheme'] != "gopher" ) )
        {
            return false;
        }
        elseif ( ! preg_match( "/^[0-9a-z]([\-\.]?[0-9a-z])*\.(ac|ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|asia|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cat|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|info|int|io|iq|ir|is|it|je|jm|jo|jobs|jp|ke|kg|kh|ki|km|kn|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mobi|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tel|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|travel|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$/", $parts['host'] ) and ! $ips->nv_validip( $parts['host'] ) )
        {
            return false;
        }
        elseif ( isset( $parts['user'] ) and ! preg_match( "/^([0-9a-z\-]|[\_])*$/", $parts['user'] ) )
        {
            return false;
        }
        elseif ( isset( $parts['pass'] ) and ! preg_match( "/^([0-9a-z\-]|[\_])*$/", $parts['pass'] ) )
        {
            return false;
        }
        elseif ( isset( $parts['path'] ) and ! preg_match( "/^[0-9A-Za-z\/\_\.\@\~\-\%\\s]*$/", $parts['path'] ) )
        {
            return false;
        }
        elseif ( isset( $parts['query'] ) and ! preg_match( "/^[0-9a-z\-\_\/\?\&\=\#\.\,\;\%\\s]*$/", $parts['query'] ) )
        {
            return false;
        }
    }
    
    return true;
}

/**
 * nv_check_url()
 * 
 * @param mixed $url
 * @param bool $is_200
 * @return
 */
function nv_check_url ( $url, $is_200 = 0 )
{
    if ( empty( $url ) ) return false;
    $url = str_replace( " ", "%20", $url );
    $allow_url_fopen = ( ini_get( 'allow_url_fopen' ) == '1' || strtolower( ini_get( 'allow_url_fopen' ) ) == 'on' ) ? 1 : 0;
    if ( nv_function_exists( 'get_headers' ) and $allow_url_fopen == 1 )
    {
        $res = get_headers( $url );
    }
    elseif ( nv_function_exists( 'curl_init' ) and nv_function_exists( 'curl_exec' ) )
    {
        $url_info = @parse_url( $url );
        $port = isset( $url_info['port'] ) ? intval( $url_info['port'] ) : 80;
        
        $userAgents = array( //
'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0', //
'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', //
'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)', //
'Mozilla/4.8 [en] (Windows NT 6.0; U)', //
'Opera/9.25 (Windows NT 6.0; U; en)' ); //
        

        $safe_mode = ( ini_get( 'safe_mode' ) == '1' || strtolower( ini_get( 'safe_mode' ) ) == 'on' ) ? 1 : 0;
        $open_basedir = ( ini_get( 'open_basedir' ) == '1' || strtolower( ini_get( 'open_basedir' ) ) == 'on' ) ? 1 : 0;
        
        srand( ( float )microtime() * 10000000 );
        $rand = array_rand( $userAgents );
        $agent = $userAgents[$rand];
        
        $curl = curl_init( $url );
        curl_setopt( $curl, CURLOPT_HEADER, true );
        curl_setopt( $curl, CURLOPT_NOBODY, true );
        
        curl_setopt( $curl, CURLOPT_PORT, $port );
        if ( ! $safe_mode and $open_basedir )
        {
            curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
        }
        
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        
        curl_setopt( $curl, CURLOPT_TIMEOUT, 15 );
        curl_setopt( $curl, CURLOPT_USERAGENT, $agent );
        
        $response = curl_exec( $curl );
        curl_close( $curl );
        
        if ( $response === false )
        {
            trigger_error( curl_error( $curl ), E_USER_WARNING );
            return false;
        }
        else
        {
            $res = explode( "\n", $response );
        }
    }
    elseif ( nv_function_exists( 'fsockopen' ) and nv_function_exists( 'fgets' ) )
    {
        $res = array();
        $url_info = parse_url( $url );
        $port = isset( $url_info['port'] ) ? intval( $url_info['port'] ) : 80;
        $fp = fsockopen( $url_info['host'], $port, $errno, $errstr, 15 );
        if ( $fp )
        {
            $path = ! empty( $url_info['path'] ) ? $url_info['path'] : '/';
            $path .= ! empty( $url_info['query'] ) ? '?' . $url_info['query'] : '';
            
            fputs( $fp, "HEAD " . $path . " HTTP/1.0\r\n" );
            fputs( $fp, "Host: " . $url_info['host'] . ":" . $port . "\r\n" );
            fputs( $fp, "Connection: close\r\n\r\n" );
            
            while ( ! feof( $fp ) )
            {
                if ( $header = trim( fgets( $fp, 1024 ) ) )
                {
                    $res[] = $header;
                }
            }
        }
        else
        {
            trigger_error( $errstr, E_USER_WARNING );
            return false;
        }
    }
    else
    {
        trigger_error( 'error server no support check url', E_USER_WARNING );
        return false;
    }
    if ( empty( $res ) ) return false;
    if ( preg_match( "/(200)/", $res[0] ) ) return true;
    if ( $is_200 > 5 ) return false;
    if ( preg_match( "/(301)|(302)|(303)/", $res[0] ) )
    {
        foreach ( $res as $k => $v )
        {
            unset( $matches );
            if ( preg_match( "/location:\s(.*?)$/is", $v, $matches ) )
            {
                $is_200 ++;
                $location = trim( $matches[1] );
                return nv_check_url( $location, $is_200 );
            }
        }
    }
    return false;
}

// function IP
/**
 * nv_ParseIP()
 * 
 * @param mixed $ip
 * @return
 */
function nv_ParseIP ( $ip )
{
    global $sys_info;
    if ( $ip == '127.0.0.1' || $ip == '0.0.0.1' ) return "localhost";
    if ( ! function_exists( "fsockopen" ) or in_array( 'fsockopen', $sys_info['disable_functions'] ) ) return false;
    
    if ( ! $fp = @fsockopen( "whois.arin.net", 43, $errno, $errstr, 10 ) ) return false;
    
    if ( @fwrite( $fp, $ip . "\r\n" ) === false )
    {
        @fclose( $fp );
        return false;
    }
    
    $response = "";
    while ( ! @feof( $fp ) )
    {
        $response .= @fgets( $fp, 4096 );
    }
    @fclose( $fp );
    
    $extra = "";
    $nextServer = "";
    if ( preg_match( "/" . preg_quote( "nic.ad.jp" ) . "/", $response ) )
    {
        $nextServer = "whois.nic.ad.jp";
        $extra = "/e";
    }
    else
    {
        if ( preg_match( "/" . preg_quote( "whois.registro.br" ) . "/", $response ) ) $nextServer = "whois.registro.br";
        elseif ( preg_match( "/" . preg_quote( "whois.apnic.net" ) . "/", $response ) ) $nextServer = "whois.apnic.net";
        elseif ( preg_match( "/" . preg_quote( "ripe.net" ) . "/", $response ) ) $nextServer = "whois.ripe.net";
    }
    
    if ( ! empty( $nextServer ) )
    {
        $response = "";
        if ( ! $fp = @fsockopen( $nextServer, 43, $errno, $errstr, 10 ) ) return false;
        
        if ( @fwrite( $fp, $ip . $extra . "\r\n" ) === false )
        {
            @fclose( $fp );
            return false;
        }
        while ( ! @feof( $fp ) )
        {
            $response .= @fgets( $fp, 4096 );
        }
        @fclose( $fp );
    }
    return $response;
}

/**
 * nv_getCountry()
 * 
 * @param mixed $ip
 * @return
 */
function nv_getCountry ( $ip )
{
    $result = nv_ParseIP( $ip );
    if ( empty( $result ) or $result == "localhost" )
    {
        return array( "unkown", "", "" );
    }
    unset( $arr );
    if ( preg_match( '/^\x20*country\x20*:\x20*([A-Z]{2})/im', $result, $arr ) )
    {
        include ( NV_ROOTDIR . "/includes/ip_files/countries.php" );
        $code = strtoupper( $arr[1] );
        if ( isset( $countries[$code] ) )
        {
            return array( $arr[1], $countries[$code][0], $countries[$code][1] );
        }
    }
    return array( "unkown", "", "" );
}

/**
 * nv_getCountry_from_file()
 * 
 * @param mixed $ip
 * @return
 */
function nv_getCountry_from_file ( $ip )
{
    $numbers = preg_split( "/\./", $ip );
    $ranges = $countries = array();
    $two_letter_country_code = $three_letter_country_code = $country_name = "";
    
    include ( NV_ROOTDIR . "/includes/ip_files/" . $numbers[0] . ".php" );
    $code = ( $numbers[0] * 16777216 ) + ( $numbers[1] * 65536 ) + ( $numbers[2] * 256 ) + ( $numbers[3] );
    if ( ! empty( $ranges ) )
    {
        foreach ( $ranges as $key => $value )
        {
            if ( $key <= $code )
            {
                if ( $ranges[$key][0] >= $code )
                {
                    $two_letter_country_code = $ranges[$key][1];
                    break;
                }
            }
        }
    }
    
    if ( $two_letter_country_code == "" )
    {
        return nv_getCountry( $ip );
    }
    else
    {
        include ( NV_ROOTDIR . "/includes/ip_files/countries.php" );
        $three_letter_country_code = $countries[$two_letter_country_code][0];
        $country_name = $countries[$two_letter_country_code][1];
    
    }
    return array( $two_letter_country_code, $three_letter_country_code, $country_name );
}

/**
 * nv_check_rewrite_file()
 * 
 * @return
 */
function nv_check_rewrite_file ( )
{
    global $sys_info;
    
    if ( $sys_info['supports_rewrite'] == 'rewrite_mode_apache' )
    {
        if ( ! file_exists( NV_ROOTDIR . '/.htaccess' ) ) return false;
        
        $htaccess = @file_get_contents( NV_ROOTDIR . '/.htaccess' );
        if ( ! preg_match( "/\#nukeviet\_rewrite\_start(.*)\#nukeviet\_rewrite\_end/s", $htaccess ) ) return false;
        return true;
    }
    elseif ( $sys_info['supports_rewrite'] == 'rewrite_mode_iis' )
    {
        if ( ! file_exists( NV_ROOTDIR . '/web.config' ) ) return false;
        $web_config = @file_get_contents( NV_ROOTDIR . '/web.config' );
        if ( ! preg_match( "/<rule name=\"nv_rule_rewrite\">(.*)</rule>/s", $web_config ) ) return false;
        return true;
    }
    else
    {
        return false;
    }
}

/**
 * nv_url_rewrite()
 * 
 * @param mixed $buffer
 * @param bool $is_url
 * @return
 */
function nv_url_rewrite ( $buffer, $is_url = false )
{
    global $global_config, $module_name, $sys_info, $rewrite;
    if ( ! empty( $rewrite ) )
    {
        if ( $is_url ) $buffer = "\"" . $buffer . "\"";
        
        $buffer = preg_replace( array_keys( $rewrite ), array_values( $rewrite ), $buffer );
        
        if ( $is_url ) $buffer = substr( $buffer, 1, - 1 );
    }
    return $buffer;
}

/**
 * nv_valid_html()
 * 
 * @param mixed $html
 * @param mixed $config
 * @param string $encoding
 * @return
 */
function nv_valid_html ( $html, $config, $encoding = 'utf8' )
{
    global $sys_info;
    
    if ( $sys_info['supports_tidy'] == "class" )
    {
        //PHP 5
        $tidy = new tidy();
        $tidy->parseString( $html, $config, $encoding );
        $tidy->cleanRepair();
        return $tidy;
    }
    elseif ( $sys_info['supports_tidy'] == "func" )
    {
        $tidy = tidy_parse_string( $html, $config, $encoding );
        tidy_clean_repair();
        return $tidy;
    }
    
    return $html;
}

/**
 * nv_change_buffer()
 * 
 * @param mixed $buffer
 * @return
 */
function nv_change_buffer ( $buffer )
{
    global $db, $sys_info, $global_config;
    
    $buffer = $db->unfixdb( $buffer );
    $buffer = nv_url_rewrite( $buffer );
    
    if ( defined( "NV_ANTI_IFRAME" ) and NV_ANTI_IFRAME )
    {
        $buffer = preg_replace( "/(<body[^>]*>)/", "$1\r\n<script type=\"text/javascript\">if(window.top!==window.self){document.write=\"\";window.top.location=window.self.location;setTimeout(function(){document.body.innerHTML=\"\"},1);window.self.onload=function(){document.body.innerHTML=\"\"}};</script>", $buffer, 1 );
    }
    
    if ( ! empty( $global_config['googleAnalyticsID'] ) and preg_match( '/^UA-\d{4,}-\d+$/', $global_config['googleAnalyticsID'] ) )
    {
        $dp = "";
        if ( $global_config['googleAnalyticsSetDomainName'] == 1 )
        {
            $dp .= "_gaq.push([\"_setDomainName\",\"" . $global_config['cookie_domain'] . "\"]);";
        }
        elseif ( $global_config['googleAnalyticsSetDomainName'] == 2 )
        {
            $dp .= "_gaq.push([\"_setDomainName\",\"none\"]);_gaq.push([\"_setAllowLinker\",true]);";
        }
        $googleAnalytics = "<script type=\"text/javascript\">\r\n";
        $googleAnalytics .= "//<![CDATA[\r\n";
        $googleAnalytics .= "var _gaq=_gaq||[];_gaq.push([\"_setAccount\",\"" . $global_config['googleAnalyticsID'] . "\"]);" . $dp . "_gaq.push([\"_trackPageview\"]);(function(){var a=document.createElement(\"script\");a.type=\"text/javascript\";a.async=true;a.src=(\"https:\"==document.location.protocol?\"https://ssl\":\"http://www\")+\".google-analytics.com/ga.js\";var b=document.getElementsByTagName(\"script\")[0];b.parentNode.insertBefore(a,b)})();\r\n";
        $googleAnalytics .= "//]]>\r\n";
        $googleAnalytics .= "</script>\r\n";
        $buffer = preg_replace( '/(<\/head>)/i', $googleAnalytics . "\\1", $buffer, 1 );
    }
    $optActive = false;
    if ( $global_config['optActive'] == 1 )
    {
        $optActive = true;
    }
    elseif ( ! defined( 'NV_ADMIN' ) and $global_config['optActive'] == 2 )
    {
        $optActive = true;
    }
    elseif ( defined( 'NV_ADMIN' ) and $global_config['optActive'] == 3 )
    {
        $optActive = true;
    }
    
    if ( $optActive )
    {
        include_once ( NV_ROOTDIR . '/includes/class/optimizer.class.php' );
        $optimezer = new optimezer( $buffer, $sys_info['supports_tidy'] );
        $buffer = $optimezer->process();
        
        if ( ! $sys_info['supports_rewrite'] )
        {
            $buffer = preg_replace( "/\<(script|link)(.*?)(src|href)=['\"]((?!http(s?)|ftp\:\/\/).*?\.(js|css))['\"](.*?)\>/", "<\\1\\2\\3=\"" . NV_BASE_SITEURL . "CJzip.php?file=\\4&amp;r=1\"\\7>", $buffer );
        }
        
        //http://tidy.sourceforge.net/docs/quickref.html
        $config = array( //
'doctype' => 'transitional', // Chuan HTML: omit, auto, strict, transitional, user
'input-encoding' => 'utf8', // Bang ma nguon
'output-encoding' => 'utf8', //Bang ma dich
'output-xhtml' => true, // Chuan xhtml
'drop-empty-paras' => true, // Xoa cac tags p rong
'drop-proprietary-attributes' => true, // Xoa tat ca nhung attributes dac thu cua microsoft (vi du: tu word)
'word-2000' => true, //Xoa tat ca nhung ma cua word khong phu hop voi chuan html
'enclose-block-text' => true, // Tat ca cac block-text duoc dong bang tag p
'enclose-text' => true, // Tat ca cac text nam trong khu vuc body nhung khong nam trong bat ky mot tag nao khac se duoc cho vao <p>text</p>
'hide-comments' => false, // Xoa cac chu thich
'hide-endtags' => true, // Xoa tat ca ve^' dong khong cua nhung tag khong doi hoi phai dong
'indent' => false, // Thut dau dong
'indent-spaces' => 4, //1 don vi indent = 4 dau cach
'logical-emphasis' => true, // Thay cac tag i va b bang em va strong
'lower-literals' => true, // Tat ca cac html-tags duoc bien thanh dang chu thuong
'markup' => true, // Sua cac loi Markup
'preserve-entities' => true, // Giu nguyen cac chu da duoc ma hoa trong nguon
'quote-ampersand' => true, // Thay & bang &amp;
'quote-marks' => true, // Thay cac dau ngoac bang ma html tuong ung
'quote-nbsp' => true, // Thay dau cach bang to hop &nbsp;
'show-warnings' => false, // Hien thi thong bao loi
'wrap' => 0, // Moi dong khong qua 150 ky tu
'alt-text' => true ); //Bat buoc phai co alt trong IMG
        

        $buffer = nv_valid_html( $buffer, $config );
    }
    return $buffer;
}

/**
 * nv_insert_logs()
 *
 * @param mixed $lang
 * @param mixed $module_name
 * @param mixed $name_key
 * @param mixed $note_action
 * @param mixed $userid
 * @param mixed $link_acess
 * @return
 */
function nv_insert_logs ( $lang = "", $module_name = "", $name_key = "", $note_action = "", $userid = 0, $link_acess = "" )
{
    global $db_config, $db;
    $sql = "INSERT INTO `" . $db_config['prefix'] . "_logs` (`id` ,`lang`,`module_name`,`name_key`,`note_action` ,`link_acess` ,`userid` ,`log_time` ) VALUES ( NULL , " . $db->dbescape( $lang ) . ", " . $db->dbescape( $module_name ) . ", " . $db->dbescape( $name_key ) . ", " . $db->dbescape( $note_action ) . ", " . $db->dbescape( $note_action ) . ", " . intval( $userid ) . ", " . NV_CURRENTTIME . ");";
    return $db->sql_query_insert_id( $sql );
}

/**
 * nv_listDir()
 * 
 * @param mixed $dir
 * @param mixed $real_dirlist
 * @return
 */
function nv_listUploadDir ( $dir, $real_dirlist )
{
    $real_dirlist[] = $dir;
    
    if ( ( $dh = @opendir( NV_ROOTDIR . '/' . $dir ) ) !== false )
    {
        while ( false !== ( $subdir = readdir( $dh ) ) )
        {
            if ( preg_match( "/^[a-zA-Z0-9\-\_]+$/", $subdir ) )
            {
                if ( is_dir( NV_ROOTDIR . '/' . $dir . '/' . $subdir ) )
                {
                    $real_dirlist = nv_listUploadDir( $dir . '/' . $subdir, $real_dirlist );
                }
            }
        }
        closedir( $dh );
    }
    
    return $real_dirlist;
}

/**
 * nv_loadDirList()
 * 
 * @return void
 */
function nv_loadUploadDirList ( $return = true )
{
    $allow_upload_dir = array( 'images', NV_UPLOADS_DIR );
    $dirlistCache = NV_ROOTDIR . "/" . NV_FILES_DIR . "/dcache/dirlist-" . md5( implode( $allow_upload_dir ) );
    
    $real_dirlist = array();
    foreach ( $allow_upload_dir as $dir )
    {
        $real_dirlist = nv_listUploadDir( $dir, $real_dirlist );
    }
    
    ksort( $real_dirlist );
    file_put_contents( $dirlistCache, serialize( $real_dirlist ) );
    
    if ( $return ) return $real_dirlist;
}

?>