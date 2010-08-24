<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 23/8/2010, 1:48
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

require_once ( NV_ROOTDIR . '/includes/utf8/' . $sys_info['string_handler'] . '_string_handler.php' );
require_once ( NV_ROOTDIR . '/includes/utf8/utf8_functions.php' );

if ( ! function_exists( 'array_intersect_key' ) )
{
    /**
     * array_intersect_key()
     * 
     * @param mixed $isec
     * @param mixed $keys
     * @return
     */
    function array_intersect_key( $isec, $keys )
    {
        $num = func_num_args();
        if ( $num > 2 )
        {
            for ( $i = 1; ! empty( $isec ) && $i < $num; $i++ )
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

/**
 * nv_parse_ini_file()
 * 
 * @param mixed $filename
 * @param bool $process_sections
 * @return
 */
function nv_parse_ini_file( $filename, $process_sections = false )
{
    $process_sections = ( bool )$process_sections;

    if ( ! file_exists( $filename ) || ! is_readable( $filename ) ) return false;

    $data = file( $filename );
    $ini = array();
    $section = '';
    foreach ( $data as $line )
    {
        $line = trim( $line );
        if ( empty( $line ) || preg_match( "/^;/", $line ) ) continue;
        unset( $match );
        if ( preg_match( "/^\[(.*?)\]$/", $line, $match ) )
        {
            $section = $match[1];
            continue;
        }
        if ( ! strpos( $line, "=" ) ) continue;
        list( $key, $value ) = explode( "=", $line );
        $key = trim( $key );
        $value = trim( $value );
        $value = str_replace( array( '"', "'" ), array( "", "" ), $value );

        if ( $process_sections && ! empty( $section ) )
        {
            unset( $match );
            if ( preg_match( "/^(.*?)\[\]$/", $key, $match ) )
            {
                $ini[$section][$match[1]][] = $value;
            }
            else
            {
                $ini[$section][$key] = $value;
            }
        }
        else
        {
            unset( $match );
            if ( preg_match( "/^(.*?)\[(.*?)\]$/", $key, $match ) )
            {
                $ini[$match[1]][] = $value;
            }
            else
            {
                $ini[$key] = $value;
            }
        }
    }
    return $ini;
}

/**
 * nv_object2array()
 * 
 * @param mixed $data
 * @return
 */
function nv_object2array( $data )
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
function nv_getenv( $key )
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
function nv_preg_quote( $string )
{
    return preg_quote( $string, "/" );
}

/**
 * nv_scandir()
 * 
 * @param mixed $directory
 * @param mixed $pattern
 * @param integer $sorting_order
 * @return
 */
function nv_scandir( $directory, $pattern, $sorting_order = 0 )
{
    $return = array();
    if ( is_dir( $directory ) )
    {
        $files = @scandir( $directory, $sorting_order );
        if ( ! empty( $files ) )
        {
            foreach ( $files as $file )
            {
                if ( $file != "." and $file != ".." and preg_match( $pattern, $file ) ) $return[] = $file;
            }
        }
    }
    return $return;
}

/**
 * nv_is_myreferer()
 * 
 * @param string $referer
 * @return
 */
function nv_is_myreferer( $referer = "" )
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
function nv_is_blocker_proxy( $is_proxy, $proxy_blocker )
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
function nv_is_banIp( $ip )
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
function nv_checkagent( $agent )
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
function nv_check_bot()
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
function nv_checkmobile()
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
function nv_getBrowser( $agent, $brinifile )
{
    $browsers = nv_parse_ini_file( $brinifile, true );
    foreach ( $browsers as $key => $info )
    {
        if ( preg_match( "#" . $info['rule'] . "#i", $agent, $results ) )
        {
            if ( isset( $results[1] ) ) return ( $key . '|' . $info['name'] . ' v' . $results[1] );
            else  return ( $key . '|' . $info['name'] );
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
function nv_getOs( $agent, $osinifile )
{
    $os = nv_parse_ini_file( $osinifile, true );
    foreach ( $os as $key => $info )
    {
        if ( preg_match( "#" . $info['rule'] . "#i", $agent, $results ) )
        {
            if ( strstr( $key, "win" ) ) return ( $key . '|' . $info['name'] );
            elseif ( isset( $results[1] ) ) return ( $key . '|' . $info['name'] . ' ' . $results[1] );
            else  return ( $key . '|' . $info['name'] );
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
function nv_convertfromBytes( $size )
{
    $i = 0;
    $iec = array( "B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB" );
    while ( ( $size / 1024 ) > 1 )
    {
        $size = $size / 1024;
        $i++;
    }
    return substr( $size, 0, strpos( $size, '.' ) + 4 ) . $iec[$i];
}

/**
 * nv_convertfromSec()
 * 
 * @param mixed $sec
 * @return
 */
function nv_convertfromSec( $sec )
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
    } elseif ( $sec < $min )
    {
        return $sec . " " . $lang_global['sec'];
    } elseif ( $sec < $hour )
    {
        $sd = $sec % $min;
        return trim( floor( $sec / $min ) . " " . " " . $lang_global['min'] . ( $sd ? " " . nv_convertfromSec( $sd ) : "" ) );
    } elseif ( $sec < $day )
    {
        $sd = $sec % $hour;
        return trim( floor( $sec / $hour ) . " " . $lang_global['hour'] . ( $sd ? " " . nv_convertfromSec( $sd ) : "" ) );
    } elseif ( $sec < $year )
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
function nv_converttoBytes( $string )
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
 * nv_getextension()
 * 
 * @param mixed $filename
 * @return
 */
function nv_getextension( $filename )
{
    if ( strpos( $filename, '.' ) === false ) return '';
    $filename = basename( strtolower( $filename ) );
    $filename = explode( '.', $filename );
    return array_pop( $filename );
}

/**
 * nv_base64_encode()
 * 
 * @param mixed $input
 * @return
 */
function nv_base64_encode( $input )
{
    return strtr( base64_encode( $input ), '+/=', '-_,' );
}

/**
 * nv_base64_decode()
 * 
 * @param mixed $input
 * @return
 */
function nv_base64_decode( $input )
{
    return base64_decode( strtr( $input, '-_,', '+/=' ) );
}

/**
 * nv_check_valid_login()
 * 
 * @param mixed $login
 * @param mixed $max
 * @param mixed $min
 * @return
 */
function nv_check_valid_login( $login, $max, $min )
{
    global $lang_global, $global_config;
    $login = strip_tags( trim( $login ) );
    if ( empty( $login ) ) return $lang_global['nickname_empty'];
    elseif ( strlen( $login ) > $max ) return sprintf( $lang_global['nicknamelong'], $login, $max );
    elseif ( strlen( $login ) < $min ) return sprintf( $lang_global['nicknameadjective'], $login, $min );
    else  return "";
}

/**
 * nv_check_valid_pass()
 * 
 * @param mixed $pass
 * @param mixed $max
 * @param mixed $min
 * @return
 */
function nv_check_valid_pass( $pass, $max, $min )
{
    global $lang_global, $global_config;
    $pass = strip_tags( trim( $pass ) );
    if ( empty( $pass ) ) return $lang_global['password_empty'];
    elseif ( strlen( $pass ) > $max ) return sprintf( $lang_global['passwordlong'], $pass, $max );
    elseif ( strlen( $pass ) < $min ) return sprintf( $lang_global['passwordadjective'], $pass, $min );
    else  return "";
}

/**
 * nv_check_valid_email()
 * 
 * @param mixed $mail
 * @return
 */
function nv_check_valid_email( $mail )
{
    global $lang_global, $global_config;
    $mail = strip_tags( trim( $mail ) );
    if ( empty( $mail ) ) return $lang_global['email_empty'];
    if ( ! preg_match( $global_config['check_email'], $mail ) || strrpos( $mail, ' ' ) > 0 ) return sprintf( $lang_global['email_incorrect'], $mail );
    if ( ! preg_match( "/\.(ac|ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|asia|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cat|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|info|int|io|iq|ir|is|it|je|jm|jo|jobs|jp|ke|kg|kh|ki|km|kn|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mobi|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tel|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|travel|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$/", $mail ) ) return sprintf( $lang_global['email_incorrect'], $mail );
    else  return "";
}

/**
 * nv_capcha_txt()
 * 
 * @param mixed $seccode
 * @param string $scaptcha
 * @return
 */
function nv_capcha_txt( $seccode, $scaptcha = "captcha" )
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
function nv_genpass( $length = 8 )
{
    $pass = chr( mt_rand( 65, 90 ) );
    for ( $k = 0; $k < $length - 1; $k++ )
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
function nv_EncodeEmail( $strEmail, $strDisplay = '', $blnCreateLink = true )
{
    $strMailto = "&#109;&#097;&#105;&#108;&#116;&#111;&#058;";
    $strEncodedEmail = "";
    for ( $i = 0; $i < strlen( $strEmail ); $i++ )
    {
        $strEncodedEmail .= "&#" . ord( substr( $strEmail, $i ) ) . ";";
    }
    $strDisplay = ( strlen( trim( $strDisplay ) ) > 0 ) ? $strDisplay : $strEncodedEmail;
    if ( $blnCreateLink ) return "<a href=\"" . $strMailto . $strEncodedEmail . "\">" . $strDisplay . "</a>";
    else  return $strDisplay;
}

/**
 * nv_user_groups()
 * 
 * @param mixed $in_groups
 * @return
 */
function nv_user_groups( $in_groups )
{
    global $db;

    if ( empty( $in_groups ) ) return "";

    $groups = array();

    $sql = "SELECT `group_id` FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `group_id` IN ('" . $in_groups . "') AND `act`=1 AND (`exp_time`=0 OR `exp_time` >= " . NV_CURRENTTIME . ")";
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
function nv_is_in_groups( $in_groups, $groups )
{
    if ( empty( $groups ) || empty( $in_groups ) ) return false;
    $in_groups = explode( ",", $in_groups );
    $groups = explode( ",", $groups );
    if ( array_intersect( $in_groups, $groups ) != array() ) return true;
    return false;
}

/**
 * nv_date()
 * 
 * @param mixed $format
 * @param integer $time
 * @return
 */
function nv_date( $format, $time = 0 )
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
function nv_monthname( $i )
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
function nv_unhtmlspecialchars( $string )
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
function nv_htmlspecialchars( $string )
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
 * Xoa cac ky tu la
 * 
 * @param mixed $text
 * @return
 */
function strip_punctuation( $text )
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
        '/ +/', ), ' ', $text );
}

/**
 * nv_nl2br()
 * 
 * @param mixed $text
 * @param string $replacement
 * @return
 */
function nv_nl2br( $text, $replacement = '<br />' )
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
function nv_br2nl( $text )
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
function nv_editor_nl2br( $text )
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
function nv_editor_br2nl( $text )
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
function filter_text_input( $inputname, $mode = 'request', $default = '', $specialchars = false, $maxlength = 0, $preg_replace = array() )
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
function filter_text_textarea( $inputname, $default = '', $allowed_html_tags = '', $save = false, $nl2br_replacement = '<br />' )
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
function nv_editor_filter_textarea( $inputname, $default = '', $allowed_html_tags = '', $save = false, $nl2br_replacement = '<br />' )
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
 * nv_sendmail()
 * 
 * @param mixed $from
 * @param mixed $to
 * @param mixed $subject
 * @param mixed $message
 * @param string $files
 * @return
 */
function nv_sendmail( $from, $to, $subject, $message, $files = '' )
{
    global $global_config, $lang_global;
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
            $mail->SMTPSecure = $global_config['smtp_ssl'] == 1 ? 'ssl' : '';
        } elseif ( $mailer_mode == 'sendmail' )
        {
            $mail->IsSendmail();
        }
        else
        {
            $mail->IsMail();
        }
        $message = nv_change_buffer( $message );
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
 * nv_string_to_filename()
 * 
 * @param mixed $word
 * @return
 */
function nv_string_to_filename( $word )
{
    $word = preg_replace( '/^\W+|\W+$/', '', $word );
    $word = preg_replace( '/\s+/', '_', $word );
    return preg_replace( '/\W-/', '', $word );
}

if ( ! function_exists( 'mime_content_type' ) )
{

    /**
     * mime_content_type()
     * 
     * @param mixed $filename
     * @return
     */
    function mime_content_type( $filename )
    {
        if ( empty( $filename ) ) return false;

        $ext = strtolower( array_pop( explode( '.', $filename ) ) );

        if ( empty( $ext ) ) return false;

        if ( function_exists( 'finfo_open' ) )
        {
            $finfo = finfo_open( FILEINFO_MIME );
            $mimetype = finfo_file( $finfo, $filename );
            finfo_close( $finfo );
            return $mimetype;
        }
        else
        {
            $mime_types = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/mime.ini' );

            if ( array_key_exists( $ext, $mime_types ) )
            {
                if ( is_string( $mime_types[$ext] ) ) return $mime_types[$ext];
                else  return $mime_types[$ext][0];
            }
            else
            {
                return 'application/octet-stream';
            }
        }
    }
}

/**
 * nv_get_allowed_ext()
 * 
 * @param mixed $allowed_filetypes
 * @param mixed $forbid_extensions
 * @param mixed $forbid_mimes
 * @return
 */
function nv_get_allowed_ext( $allowed_filetypes, $forbid_extensions, $forbid_mimes )
{
    if ( $allowed_filetypes == "any" or ( ! empty( $allowed_filetypes ) and is_array( $allowed_filetypes ) and in_array( "any", $allowed_filetypes ) ) ) return "*";
    $ini = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/mime.ini', true );
    $allowmimes = array();
    if ( ! is_array( $allowed_filetypes ) ) $allowed_filetypes = array( $allowed_filetypes );
    if ( ! empty( $allowed_filetypes ) )
    {
        foreach ( $allowed_filetypes as $type )
        {
            if ( isset( $ini[$type] ) )
            {
                foreach ( $ini[$type] as $ext => $mimes )
                {
                    if ( ! empty( $ext ) and ! in_array( $ext, $forbid_extensions ) )
                    {
                        $a = true;
                        if ( ! is_array( $mimes ) )
                        {
                            if ( in_array( $mimes, $forbid_mimes ) ) $a = false;
                        }
                        else
                        {
                            foreach ( $mimes as $m )
                            {
                                if ( in_array( $m, $forbid_mimes ) )
                                {
                                    $a = false;
                                    break;
                                }
                            }
                        }
                        if ( $a ) $allowmimes[$ext] = $mimes;
                    }
                }
            }
        }
    }
    return $allowmimes;
}

/**
 * nv_mkdir()
 * 
 * @param mixed $path
 * @param mixed $dir_name
 * @return
 */
function nv_mkdir( $path, $dir_name )
{
    global $lang_global, $global_config, $sys_info;
    $dir_name = nv_string_to_filename( trim( basename( $dir_name ) ) );
    if ( ! preg_match( "/^[a-zA-Z0-9-_.]+$/", $dir_name ) ) return array( 0, sprintf( $lang_global['error_create_directories_name_invalid'], $dir_name ) );
    $path = @realpath( $path );
    if ( ! preg_match( '/\/$/', $path ) ) $path = $path . "/";

    if ( file_exists( $path . $dir_name ) ) return array( 2, sprintf( $lang_global['error_create_directories_name_used'], $dir_name ), $path . $dir_name );

    if ( ! is_dir( $path ) ) return array( 0, sprintf( $lang_global['error_directory_does_not_exist'], $path ) );

    $ftp_check_login = 0;
    if ( $sys_info['ftp_support'] and intval( $global_config['ftp_check_login'] ) == 1 )
    {
        $ftp_server = nv_unhtmlspecialchars( $global_config['ftp_server'] );
        $ftp_port = intval( $global_config['ftp_port'] );
        $ftp_user_name = nv_unhtmlspecialchars( $global_config['ftp_user_name'] );
        $ftp_user_pass = nv_unhtmlspecialchars( $global_config['ftp_user_pass'] );
        $ftp_path = nv_unhtmlspecialchars( $global_config['ftp_path'] );
        // set up basic connection
        $conn_id = ftp_connect( $ftp_server, $ftp_port );
        // login with username and password
        $login_result = ftp_login( $conn_id, $ftp_user_name, $ftp_user_pass );
        if ( ( ! $conn_id ) || ( ! $login_result ) )
        {
            $ftp_check_login = 3;
        } elseif ( ftp_chdir( $conn_id, $ftp_path ) )
        {
            $ftp_check_login = 1;
        }
        else
        {
            $ftp_check_login = 2;
        }
    }
    if ( $ftp_check_login == 1 )
    {
        $dir = str_replace( NV_ROOTDIR . "/", "", str_replace( '\\', '/', $path . $dir_name ) );
        $res = ftp_mkdir( $conn_id, $dir );
        ftp_chmod( $conn_id, 0777, $dir );
        ftp_close( $conn_id );
    }
    if ( ! is_dir( $path . $dir_name ) )
    {
        if ( ! is_writable( $path ) )
        {
            @chmod( $path, 0777 );
        }
        if ( ! is_writable( $path ) ) return array( 0, sprintf( $lang_global['error_directory_can_not_write'], $path ) );

        $oldumask = umask( 0 );
        $res = @mkdir( $path . $dir_name );
        umask( $oldumask );
    }
    if ( ! $res ) return array( 0, sprintf( $lang_global['error_create_directories_failed'], $dir_name ) );

    file_put_contents( $path . $dir_name . '/index.html', '' );

    return array( 1, sprintf( $lang_global['directory_was_created'], $dir_name ), $path . $dir_name );
}

/**
 * nv_delete_all_cache()
 * 
 * @return
 */
function nv_delete_all_cache()
{
    $files = scandir( NV_ROOTDIR . "/" . NV_CACHEDIR );
    $files2 = array_diff( $files, array( ".", "..", ".htaccess", "index.html", ".svn" ) );
    foreach ( $files2 as $f )
    {
        nv_deletefile( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $f, true );
    }
}

/**
 * nv_deletefile()
 * 
 * @param mixed $file
 * @param bool $delsub
 * @return
 */
function nv_deletefile( $file, $delsub = false )
{
    global $lang_global;
    $realpath = realpath( $file );
    if ( empty( $realpath ) ) return array( 0, sprintf( $lang_global['error_non_existent_file'], $file ) );
    $realpath = str_replace( '\\', '/', $realpath );
    $realpath = rtrim( $realpath, "\\/" );
    $preg_match = preg_match( "/^(" . nv_preg_quote( NV_ROOTDIR ) . ")(\/[\S]+)/", $realpath, $path );
    if ( empty( $preg_match ) ) return array( 0, sprintf( $lang_global['error_delete_forbidden'], $file ) );
    if ( is_dir( $realpath ) )
    {
        $files = scandir( $realpath );
        $files2 = array_diff( $files, array( ".", "..", ".htaccess", "index.html" ) );
        if ( count( $files2 ) and ! $delsub )
        {
            return array( 0, sprintf( $lang_global['error_delete_subdirectories_not_empty'], $path[2] ) );
        }
        else
        {
            $files = array_diff( $files, array( ".", ".." ) );
            if ( count( $files ) )
            {
                foreach ( $files as $f )
                {
                    $unlink = nv_deletefile( $realpath . '/' . $f, true );
                    if ( empty( $unlink[0] ) ) return $unlink[1];
                }
            }
            if ( ! @rmdir( $realpath ) ) return array( 0, sprintf( $lang_global['error_delete_subdirectories_failed'], $path[2] ) );
            else  return array( 1, sprintf( $lang_global['directory_deleted'], $path[2] ) );
        }
    }
    else
    {
        $filename = str_replace( NV_ROOTDIR, "", str_replace( '\\', '/', $realpath ) );
        if ( ! @unlink( $realpath ) ) return array( 0, sprintf( $lang_global['error_delete_failed'], $filename ) );
        else  return array( 1, sprintf( $lang_global['file_deleted'], $filename ) );
    }
}

/**
 * nv_copyfile()
 * 
 * @param mixed $file
 * @param mixed $newfile
 * @return
 */
function nv_copyfile( $file, $newfile )
{
    if ( ! copy( $file, $newfile ) )
    {
        $content = @file_get_contents( $file );
        $openedfile = fopen( $newfile, "w" );
        fwrite( $openedfile, $content );
        fclose( $openedfile );

        if ( $content === false ) return false;
    }

    if ( file_exists( $newfile ) )
    {
        return true;
    }
    return false;
}

/**
 * nv_renamefile()
 * 
 * @param mixed $file
 * @param mixed $newname
 * @return
 */
function nv_renamefile( $file, $newname )
{
    global $lang_global;
    
    $realpath = realpath( $file );
    if ( empty( $realpath ) ) return array( 0, sprintf( $lang_global['error_non_existent_file'], $file ) );
    $realpath = str_replace( '\\', '/', $realpath );
    $realpath = rtrim( $realpath, "\\/" );
    $preg_match = preg_match( "/^(" . nv_preg_quote( NV_ROOTDIR ) . ")(\/[\S]+)/", $realpath, $path );
    if ( empty( $preg_match ) ) return array( 0, sprintf( $lang_global['error_rename_forbidden'], $file ) );
    $newname = basename( trim( $newname ) );
    $pathinfo = pathinfo( $realpath );
    if ( file_exists( $pathinfo['dirname'] . '/' . $newname ) ) return array( 0, sprintf( $lang_global['error_rename_file_exists'], $newname ) );
    if ( is_dir( $realpath ) and ! preg_match( '/^[a-zA-Z0-9-_]+$/', $newname ) ) return array( 0, sprintf( $lang_global['error_rename_directories_invalid'], $newname ) );
    if ( ! is_dir( $realpath ) and ! preg_match( '/^[a-zA-Z0-9-_.]+$/', $newname ) ) return array( 0, sprintf( $lang_global['error_rename_file_invalid'], $newname ) );
    if ( ! is_dir( $realpath ) and $pathinfo['extension'] != nv_getextension( $newname ) ) return array( 0, sprintf( $lang_global['error_rename_extension_changed'], $newname, $pathinfo['basename'] ) );
    if ( ! @rename( $realpath, $pathinfo['dirname'] . '/' . $newname ) )
    {
        if ( ! @nv_copyfile( $realpath, $pathinfo['dirname'] . '/' . $newname ) )
        {
            return array( 0, sprintf( $lang_global['error_rename_failed'], $pathinfo['basename'], $newname ) );
        }
        else
        {
            @nv_deletefile( $realpath );
        }
    }
    return array( 1, sprintf( $lang_global['file_has_been_renamed'], $pathinfo['basename'], $newname ) );
}

/**
 * nv_chmod_dir()
 * 
 * @param mixed $conn_id
 * @param mixed $dir
 * @param bool $subdir
 * @return
 */
function nv_chmod_dir( $conn_id, $dir, $subdir = false )
{
    global $array_cmd_dir;
    $no_file = array( '.', '..', '.htaccess', 'index.html' );
    if ( ftp_chmod( $conn_id, 0777, $dir ) !== false )
    {
        $array_cmd_dir[] = $dir;
        if ( $subdir and is_dir( NV_ROOTDIR . '/' . $dir ) )
        {
            $list_files = ftp_nlist( $conn_id, $dir );
            foreach ( $list_files as $file_i )
            {
                if ( ! in_array( $file_i, $no_file ) )
                {
                    if ( is_dir( NV_ROOTDIR . '/' . $dir . '/' . $file_i ) )
                    {
                        nv_chmod_dir( $conn_id, $dir . '/' . $file_i, $subdir );
                    }
                    else
                    {
                        ftp_chmod( $conn_id, 0777, $dir . '/' . $file_i );
                    }
                }
            }
        }
    }
    else
    {
        $array_cmd_dir[] = '<b>' . $dir . ' --> no chmod 777 </b>';
    }
}

/**
 * nv_is_image()
 * 
 * @param mixed $img
 * @return
 */
function nv_is_image( $img )
{
    $typeflag = array();
    $typeflag[1] = array( 'type' => 'IMAGETYPE_GIF', 'ext' => 'gif' );
    $typeflag[2] = array( 'type' => 'IMAGETYPE_JPEG', 'ext' => 'jpg' );
    $typeflag[3] = array( 'type' => 'IMAGETYPE_PNG', 'ext' => 'png' );
    $typeflag[4] = array( 'type' => 'IMAGETYPE_SWF', 'ext' => 'swf' );
    $typeflag[5] = array( 'type' => 'IMAGETYPE_PSD', 'ext' => 'psd' );
    $typeflag[6] = array( 'type' => 'IMAGETYPE_BMP', 'ext' => 'bmp' );
    $typeflag[7] = array( 'type' => 'IMAGETYPE_TIFF_II', 'ext' => 'tiff' );
    $typeflag[8] = array( 'type' => 'IMAGETYPE_TIFF_MM', 'ext' => 'tiff' );
    $typeflag[9] = array( 'type' => 'IMAGETYPE_JPC', 'ext' => 'jpc' );
    $typeflag[10] = array( 'type' => 'IMAGETYPE_JP2', 'ext' => 'jp2' );
    $typeflag[11] = array( 'type' => 'IMAGETYPE_JPX', 'ext' => 'jpf' );
    $typeflag[12] = array( 'type' => 'IMAGETYPE_JB2', 'ext' => 'jb2' );
    $typeflag[13] = array( 'type' => 'IMAGETYPE_SWC', 'ext' => 'swc' );
    $typeflag[14] = array( 'type' => 'IMAGETYPE_IFF', 'ext' => 'aiff' );
    $typeflag[15] = array( 'type' => 'IMAGETYPE_WBMP', 'ext' => 'wbmp' );
    $typeflag[16] = array( 'type' => 'IMAGETYPE_XBM', 'ext' => 'xbm' );

    $imageinfo = array();
    $file = @getimagesize( $img );
    if ( $file )
    {
        $channels = isset( $file['channels'] ) ? intval( $file['channels'] ) : 0;
        $imageinfo['src'] = $img;
        $imageinfo['width'] = $file[0];
        $imageinfo['height'] = $file[1];
        $imageinfo['mime'] = $file['mime'];
        $imageinfo['type'] = $typeflag[$file[2]]['type'];
        $imageinfo['ext'] = $typeflag[$file[2]]['ext'];
        $imageinfo['bits'] = $file['bits'];
        $imageinfo['channels'] = isset( $file['channels'] ) ? intval( $file['channels'] ) : 0;
    }

    return $imageinfo;
}

/**
 * nv_pathinfo_filename()
 * 
 * @param mixed $file
 * @return
 */
function nv_pathinfo_filename( $file )
{
    if ( defined( 'PATHINFO_FILENAME' ) ) return pathinfo( $file, PATHINFO_FILENAME );
    if ( strstr( $file, '.' ) ) return substr( $file, 0, strrpos( $file, '.' ) );
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
function nv_generate_page( $base_url, $num_items, $per_page, $start_item, $add_prevnext_text = true, $onclick = false, $js_func_name = 'nv_urldecode_ajax', $containerid = 'generate_page' )
{
    global $lang_global;

    $total_pages = ceil( $num_items / $per_page );
    if ( $total_pages == 1 ) return '';
    @$on_page = floor( $start_item / $per_page ) + 1;
    $amp = preg_match( "/\?/", $base_url ) ? "&amp;" : "?";
    $page_string = "";
    if ( $total_pages > 10 )
    {
        $init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;
        for ( $i = 1; $i <= $init_page_max; $i++ )
        {
            $href = ! $onclick ? "href=\"" . $base_url . $amp . "page=" . ( ( $i - 1 ) * $per_page ) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $base_url . $amp . "page=" . ( ( $i - 1 ) * $per_page ) ) ) . "','" . $containerid . "')\"";
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
                for ( $i = $init_page_min - 1; $i < $init_page_max + 2; $i++ )
                {
                    $href = ! $onclick ? "href=\"" . $base_url . $amp . "page=" . ( ( $i - 1 ) * $per_page ) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $base_url . $amp . "page=" . ( ( $i - 1 ) * $per_page ) ) ) . "','" . $containerid . "')\"";
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

            for ( $i = $total_pages - 2; $i < $total_pages + 1; $i++ )
            {
                $href = ! $onclick ? "href=\"" . $base_url . $amp . "page=" . ( ( $i - 1 ) * $per_page ) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $base_url . $amp . "page=" . ( ( $i - 1 ) * $per_page ) ) ) . "','" . $containerid . "')\"";
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
        for ( $i = 1; $i < $total_pages + 1; $i++ )
        {
            $href = ! $onclick ? "href=\"" . $base_url . $amp . "page=" . ( ( $i - 1 ) * $per_page ) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $base_url . $amp . "page=" . ( ( $i - 1 ) * $per_page ) ) ) . "','" . $containerid . "')\"";
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
            $href = ! $onclick ? "href=\"" . $base_url . $amp . "page=" . ( ( $on_page - 2 ) * $per_page ) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $base_url . $amp . "page=" . ( ( $on_page - 2 ) * $per_page ) ) ) . "','" . $containerid . "')\"";
            $page_string = "&nbsp;&nbsp;<span><a " . $href . ">" . $lang_global['pageprev'] . "</a></span>&nbsp;&nbsp;" . $page_string;
        }
        if ( $on_page < $total_pages )
        {
            $href = ! $onclick ? "href=\"" . $base_url . $amp . "page=" . ( $on_page * $per_page ) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $base_url . $amp . "page=" . ( $on_page * $per_page ) ) ) . "','" . $containerid . "')\"";
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
function nv_is_url( $url )
{
    global $ips;
    $url = substr( $url, -1 ) == "/" ? substr( $url, 0, -1 ) : $url;
    if ( empty( $url ) ) return false;
    $url = nv_strtolower( $url );
    if ( ! ( $parts = @parse_url( $url ) ) ) return false;
    else
    {
        if ( ! isset( $parts['scheme'] ) or ! isset( $parts['host'] ) or ( $parts['scheme'] != "http" && $parts['scheme'] != "https" && $parts['scheme'] != "ftp" && $parts['scheme'] != "gopher" ) )
        {
            return false;
        } elseif ( ! preg_match( "/^[0-9a-z]([\-\.]?[0-9a-z])*\.(ac|ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|asia|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cat|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|info|int|io|iq|ir|is|it|je|jm|jo|jobs|jp|ke|kg|kh|ki|km|kn|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mobi|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tel|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|travel|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$/", $parts['host'] ) and ! $ips->nv_validip( $parts['host'] ) )
        {
            return false;
        } elseif ( isset( $parts['user'] ) and ! preg_match( "/^([0-9a-z\-]|[\_])*$/", $parts['user'] ) )
        {
            return false;
        } elseif ( isset( $parts['pass'] ) and ! preg_match( "/^([0-9a-z\-]|[\_])*$/", $parts['pass'] ) )
        {
            return false;
        } elseif ( isset( $parts['path'] ) and ! preg_match( "/^[0-9A-Za-z\/\_\.\@\~\-]*$/", $parts['path'] ) )
        {
            return false;
        } elseif ( isset( $parts['query'] ) and ! preg_match( "/^[0-9a-z\-\_\/\?\&\=\#\.\,\;]*$/", $parts['query'] ) )
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
function nv_check_url( $url, $is_200 = false )
{
    if ( empty( $url ) ) return false;
    $res = get_headers( $url );
    if ( ! $res ) return false;
    if ( preg_match( "/(200)/", $res[0] ) ) return true;
    if ( $is_200 ) return false;
    if ( preg_match( "/(301)|(302)|(303)/", $res[0] ) )
    {
        foreach ( $res as $k => $v )
        {
            unset( $matches );
            if ( preg_match( "/location:\s(.*?)$/is", $v, $matches ) )
            {
                $location = trim( $matches[1] );
                return nv_check_url( $location, true );
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
function nv_ParseIP( $ip )
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
function nv_getCountry( $ip )
{
    $result = nv_ParseIP( $ip );
    if ( empty( $result ) or $result == "localhost" ) return array( "unkown", "", "" );
    unset( $arr );
    if ( preg_match( '/^\x20*country\x20*:\x20*([A-Z]{2})/im', $result, $arr ) )
    {
        include ( NV_ROOTDIR . "/includes/ip_files/countries.php" );
        $three_letter_country_code = $countries[$arr[1]][0];
        $country_name = $countries[$arr[1]][1];
        return array( $arr[1], $three_letter_country_code, $country_name );
    }
    return array( "unkown", "", "" );
}

/**
 * nv_getCountry_from_file()
 * 
 * @param mixed $ip
 * @return
 */
function nv_getCountry_from_file( $ip )
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
function nv_check_rewrite_file()
{
    global $sys_info;

    if ( $sys_info['supports_rewrite'] == 'rewrite_mode_apache' )
    {
        if ( ! file_exists( NV_ROOTDIR . '/.htaccess' ) ) return false;

        $htaccess = @file_get_contents( NV_ROOTDIR . '/.htaccess' );
        if ( empty( $htaccess ) ) return false;
        if ( ! preg_match( "/\#nukeviet\_rewrite\_start(.*)\#nukeviet\_rewrite\_end/s", $htaccess ) ) return false;
        return true;
    } elseif ( $sys_info['supports_rewrite'] == 'rewrite_mode_iis' )
    {
        if ( ! file_exists( NV_ROOTDIR . '/web.config' ) ) return false;
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
 * @return
 */
function nv_url_rewrite( $buffer )
{
    global $global_config, $module_name, $sys_info;
    if ( ! isset( $global_config['is_url_rewrite'] ) or empty( $global_config['is_url_rewrite'] ) ) return $buffer;
    if ( ! empty( $sys_info['supports_rewrite'] ) )
    {
        $rewrite = array();
        include ( NV_ROOTDIR . "/includes/rewrite.php" );
        if ( ! empty( $rewrite ) )
        {
            $buffer = preg_replace( array_keys( $rewrite ), array_values( $rewrite ), $buffer );
        }
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
function nv_valid_html( $html, $config, $encoding = 'utf8' )
{
    if ( ! class_exists( 'tidy' ) )
    {
        if ( function_exists( 'tidy_parse_string' ) )
        {
            $tidy = tidy_parse_string( $html, $config, $encoding );
            tidy_clean_repair();
            return $tidy;
        }
        else  return $html;
    }
    else
    {
        //PHP 5
        $tidy = new tidy();
        $tidy->parseString( $html, $config, $encoding );
        $tidy->cleanRepair();
        return $tidy;
    }
}

/**
 * nv_change_buffer()
 * 
 * @param mixed $buffer
 * @return
 */
function nv_change_buffer( $buffer )
{
    global $db;
    
    $buffer = $db->unfixdb( $buffer );

    $buffer = nv_url_rewrite( $buffer );
    //http://tidy.sourceforge.net/docs/quickref.html
    $config = array( 'doctype' => 'transitional', // Chuan HTML: omit, auto, strict, transitional, user
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
        'indent' => true, // Thut dau dong
        'indent-spaces' => 4, //1 don vi indent = 4 dau cach
        'logical-emphasis' => true, // Thay cac tag i va b bang em va strong
        'lower-literals' => true, // Tat ca cac html-tags duoc bien thanh dang chu thuong
        'markup' => true, // Sua cac loi Markup
        'preserve-entities' => true, // Giu nguyen cac chu da duoc ma hoa trong nguon
        'quote-ampersand' => true, // Thay & bang &amp;
        'quote-marks' => true, // Thay cac dau ngoac bang ma html tuong ung
        'quote-nbsp' => true, // Thay dau cach bang to hop &nbsp;
        'show-warnings' => false, // Hien thi thong bao loi
        'wrap' => 150 ); // Moi dong khong qua 150 ky tu

    $buffer = nv_valid_html( $buffer, $config );
    return $buffer;
}

?>