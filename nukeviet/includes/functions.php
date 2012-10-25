<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 1/9/2010, 23:48
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

require_once ( NV_ROOTDIR . '/includes/utf8/' . $sys_info['string_handler'] . '_string_handler.php' );
require_once ( NV_ROOTDIR . '/includes/utf8/utf8_functions.php' );
require_once ( NV_ROOTDIR . '/includes/core/filesystem_functions.php' );
require_once ( NV_ROOTDIR . '/includes/core/cache_functions.php' );

/**
 * nv_object2array()
 *
 * @param mixed $a
 * @return
 */
function nv_object2array( $a )
{
	if( is_object( $a ) ) $a = get_object_vars( $a );
	
	return is_array( $a ) ? array_map( __function__, $a ) : $a;
}

/**
 * nv_getenv()
 *
 * @param mixed $a
 * @return
 */
function nv_getenv( $a )
{
	if( ! is_array( $a ) )
	{
		$a = array( $a );
	}
	
	foreach( $a as $b )
	{
		if( isset( $_SERVER[$b] ) ) return $_SERVER[$b];
		elseif( isset( $_ENV[$b] ) ) return $_ENV[$b];
		elseif( @getenv( $b ) ) return @getenv( $b );
		elseif( function_exists( 'apache_getenv' ) && apache_getenv( $b, true ) ) return apache_getenv( $b, true );
	}
	
	return "";
}

/**
 * nv_preg_quote()
 *
 * @param string $a
 * @return
 */
function nv_preg_quote( $a )
{
	return preg_quote( $a, "/" );
}

/**
 * nv_is_myreferer()
 *
 * @param string $referer
 * @return
 */
function nv_is_myreferer( $referer = "" )
{
	if( empty( $referer ) ) $referer = urldecode( nv_getenv( 'HTTP_REFERER' ) );
	if( empty( $referer ) ) return 2;
	
	$server_name = preg_replace( '/^[w]+\./e', '', nv_getenv( "HTTP_HOST" ) );
	$referer = preg_replace( array( '/^[a-zA-Z]+\:\/\/([w]+\.)?/e', '/^[w]+\./e' ), '', $referer );
	
	if( preg_match( "/^" . nv_preg_quote( $server_name ) . "/", $referer ) ) return 1;
	
	return 0;
}

/**
 * nv_is_blocker_proxy()
 *
 * @param string $is_proxy
 * @param integer $proxy_blocker
 * @return
 */
function nv_is_blocker_proxy( $is_proxy, $proxy_blocker )
{
	if( $proxy_blocker == 1 and $is_proxy == 'Strong' ) return true;
	if( $proxy_blocker == 2 and ( $is_proxy == 'Strong' || $is_proxy == 'Mild' ) ) return true;
	if( $proxy_blocker == 3 and $is_proxy != 'No' ) return true;
	
	return false;
}

/**
 * nv_is_banIp()
 *
 * @param string $ip
 * @return
 */
function nv_is_banIp( $ip )
{
	$array_banip_site = $array_banip_admin = array();

	if( file_exists( NV_ROOTDIR . "/" . NV_DATADIR . "/banip.php" ) ) include ( NV_ROOTDIR . "/" . NV_DATADIR . "/banip.php" );

	$banIp = ( defined( 'NV_ADMIN' ) ) ? $array_banip_admin : $array_banip_site;
	if( empty( $banIp ) ) return false;

	foreach( $banIp as $e => $f )
	{
		if( $f['begintime'] < NV_CURRENTTIME and ( $f['endtime'] == 0 or $f['endtime'] > NV_CURRENTTIME ) and ( preg_replace( $f['mask'], "", $ip ) == preg_replace( $f['mask'], "", $e ) ) ) return true;		
	}

	return false;
}

/**
 * nv_checkagent()
 *
 * @param string $a
 * @return
 */
function nv_checkagent( $a )
{
	$a = htmlspecialchars( substr( $a, 0, 255 ) );
	$a = str_replace( array( ",", "<" ), array( "-", "(" ), $a );
	
	return ( ( ! empty( $a ) and $a != "-" ) ? $a : "none" );
}

/**
 * nv_check_bot()
 *
 * @return
 */
function nv_check_bot()
{
	global $client_info;

	$file_bots = NV_ROOTDIR . "/" . NV_DATADIR . "/bots.config";
	$bots = ( file_exists( $file_bots ) and filesize( $file_bots ) ) ? unserialize( file_get_contents( $file_bots ) ) : array();

	if( empty( $bots ) and file_exists( NV_ROOTDIR . "/includes/bots.php" ) ) include ( NV_ROOTDIR . "/includes/bots.php" );

	if( empty( $bots ) ) return array();

	foreach( $bots as $name => $values )
	{
		$is_bot = false;

		if( $values['agent'] and preg_match( '#' . str_replace( '\*', '.*?', nv_preg_quote( $values['agent'], '#' ) ) . '#i', $client_info['agent'] ) ) $is_bot = true;

		if( ! empty( $values['ips'] ) and ( $is_bot or ! $values['agent'] ) )
		{
			$is_bot = false;
			$ips = implode( "|", array_map( "nv_preg_quote", explode( "|", $values['ips'] ) ) );
			if( preg_match( "/^" . $ips . "/", $client_info['ip'] ) ) $is_bot = true;
		}

		if( $is_bot ) return array(
			'name' => $name,
			'agent' => $values['agent'],
			'ip' => $client_info['ip'],
			'allowed' => $values['allowed']
		);
	}

	return array();
}

/**
 * nv_checkmobile()
 *
 * @param string $inifile
 * @return
 */
function nv_checkmobile( $inifile )
{
	$user_agent = $_SERVER['HTTP_USER_AGENT'];

	if( preg_match( "/Creative\ AutoUpdate/i", $user_agent ) ) return array();

	$browsers = array();
	if( file_exists( $inifile ) ) $browsers = nv_parse_ini_file( $inifile, true );

	if( ! empty( $browsers ) )
	{
		foreach( $browsers as $key => $info )
		{
			if( preg_match( $info['rule'], $user_agent ) ) return array( 'key' => $key, 'name' => $info['name'] );			
		}
	}

	if( preg_match( "/Nokia([^\/]+)\/([^ SP]+)/i", $user_agent, $matches ) )
	{
		if( stripos( $user_agent, 'Series60' ) !== false || strpos( $user_agent, 'S60' ) !== false )
		{
			return array( 'key' => 'nokia', 'name' => 'Nokia S60 V.' . $matches[2] );
		}
		else
		{
			return array( 'key' => 'nokia', 'name' => 'Nokia V.' . $matches[2] );
		}
	}

	if( isset( $_SERVER['X-OperaMini-Features'] ) ) return array( 'key' => 'opera', 'name' => 'Opera Mini' );
	if( isset( $_SERVER['UA-pixels'] ) ) return array( 'key' => 'mobile', 'name' => 'UA-pixels' );
	if( isset( $_SERVER['HTTP_X_WAP_PROFILE'] ) || isset( $_SERVER['HTTP_PROFILE'] ) ) return array( 'key' => 'mobile', 'name' => 'Unknown' );
	if( isset( $_SERVER['HTTP_ACCEPT'] ) && preg_match( "/wap\.|\.wap/i", $_SERVER["HTTP_ACCEPT"] ) ) return array( 'key' => 'mobile', 'name' => 'Unknown' );

	if( preg_match( '/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i', $user_agent ) )
	{
 		return array( 'key' => 'mobile', 'name' => 'Unknown' );		
	}

    $mbs = array('1207', '3gso', '4thp', '501i', '502i', '503i', '504i', '505i', '506i', '6310', '6590', '770s', '802s', 'a wa', 'acer', 'acs-', 'airn', 'alav', 'asus', 'attw', 'au-m', 'aur ', 'aus ', 'abac', 'acoo', 'aiko', 'alco', 'alca', 'amoi', 'anex', 'anny', 'anyw', 'aptu', 'arch', 'argo', 'bell', 'bird', 'bw-n', 'bw-u', 'beck', 'benq', 'bilb', 'blac', 'c55/', 'cdm-', 'chtm', 'capi', 'cond', 'craw', 'dall', 'dbte', 'dc-s', 'dica', 'ds-d', 'ds12', 'dait', 'devi', 'dmob', 'doco', 'dopo', 'el49', 'erk0', 'esl8', 'ez40', 'ez60', 'ez70', 'ezos', 'ezze', 'elai', 'emul', 'eric', 'ezwa', 'fake', 'fly-', 'fly_', 'g-mo', 'g1 u', 'g560', 'gf-5', 'grun', 'gene', 'go.w', 'good', 'grad', 'hcit', 'hd-m', 'hd-p', 'hd-t', 'hei-', 'hp i', 'hpip', 'hs-c', 'htc ', 'htc-', 'htca', 'htcg', 'htcp', 'htcs', 'htct', 'htc_', 'haie', 'hita', 'huaw', 'hutc', 'i-20', 'i-go', 'i-ma', 'i230', 'iac', 'iac-', 'iac/', 'ig01', 'im1k', 'inno', 'iris', 'jata', 'java', 'kddi', 'kgt', 'kgt/', 'kpt ', 'kwc-', 'klon', 'lexi', 'lg g', 'lg-a', 'lg-b', 'lg-c', 'lg-d', 'lg-f', 'lg-g', 'lg-k', 'lg-l', 'lg-m', 'lg-o', 'lg-p', 'lg-s', 'lg-t', 'lg-u', 'lg-w', 'lg/k', 'lg/l', 'lg/u', 'lg50', 'lg54', 'lge-', 'lge/', 'lynx', 'leno', 'm1-w', 'm3ga', 'm50/', 'maui', 'mc01', 'mc21', 'mcca', 'medi', 'meri', 'mio8', 'mioa', 'mo01', 'mo02', 'mode', 'modo', 'mot ', 'mot-', 'mt50', 'mtp1', 'mtv ', 'mate', 'maxo', 'merc', 'mits', 'mobi', 'motv', 'mozz', 'n100', 'n101', 'n102', 'n202', 'n203', 'n300', 'n302', 'n500', 'n502', 'n505', 'n700', 'n701', 'n710', 'nec-', 'nem-', 'newg', 'neon', 'netf', 'noki', 'nzph', 'o2 x', 'o2-x', 'opwv', 'owg1', 'opti', 'oran', 'p800', 'pand', 'pg-1', 'pg-2', 'pg-3', 'pg-6', 'pg-8', 'pg-c', 'pg13', 'phil', 'pn-2', 'pt-g', 'palm', 'pana', 'pire', 'pock', 'pose', 'psio', 'qa-a', 'qc-2', 'qc-3', 'qc-5', 'qc-7', 'qc07', 'qc12', 'qc21', 'qc32', 'qc60', 'qci-', 'qwap', 'qtek', 'r380', 'r600', 'raks', 'rim9', 'rove', 's55/', 'sage', 'sams', 'sc01', 'sch-', 'scp-', 'sdk/', 'se47', 'sec-', 'sec0', 'sec1', 'semc', 'sgh-', 'shar', 'sie-', 'sk-0', 'sl45', 'slid', 'smb3', 'smt5', 'sp01', 'sph-', 'spv ', 'spv-', 'sy01', 'samm', 'sany', 'sava', 'scoo', 'send', 'siem', 'smar', 'smit', 'soft', 'sony', 't-mo', 't218', 't250', 't600', 't610', 't618', 'tcl-', 'tdg-', 'telm', 'tim-', 'ts70', 'tsm-', 'tsm3', 'tsm5', 'tx-9', 'tagt', 'talk', 'teli', 'topl', 'hiba', 'up.b', 'upg1', 'utst', 'v400', 'v750', 'veri', 'vk-v', 'vk40', 'vk50', 'vk52', 'vk53', 'vm40', 'vx98', 'virg', 'vite', 'voda', 'vulc', 'w3c ', 'w3c-', 'wapj', 'wapp', 'wapu', 'wapm', 'wig ', 'wapi', 'wapr', 'wapv', 'wapy', 'wapa', 'waps', 'wapt', 'winc', 'winw', 'wonu', 'x700', 'xda2', 'xdag', 'yas-', 'your', 'zte-', 'zeto', 'acs-', 'alav', 'alca', 'amoi', 'aste', 'audi', 'avan', 'benq', 'bird', 'blac', 'blaz', 'brew', 'brvw', 'bumb', 'ccwa', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eml2', 'eric', 'fetc', 'hipt', 'http', 'ibro', 'idea', 'ikom', 'inno', 'ipaq', 'jbro', 'jemu', 'java', 'jigs', 'kddi', 'keji', 'kyoc', 'kyok', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-', 'libw', 'm-cr', 'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'mywa', 'nec-', 'newt', 'nok6', 'noki', 'o2im', 'opwv', 'palm', 'pana', 'pant', 'pdxg', 'phil', 'play', 'pluc', 'port', 'prox', 'qtek', 'qwap', 'rozo', 'sage', 'sama', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar', 'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-', 'tosh', 'treo', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'vx52', 'vx53', 'vx60', 'vx61', 'vx70', 'vx80', 'vx81', 'vx83', 'vx85', 'wap-', 'wapa', 'wapi', 'wapp', 'wapr', 'webc', 'whit', 'winw', 'wmlb', 'xda-', );

	$user_agent = strtolower( substr( $user_agent, 0, 4 ) );
	
	if( in_array( $user_agent, $mbs ) ) return array( 'key' => 'mobile', 'name' => 'Unknown' );

	return array();
}

/**
 * nv_getBrowser()
 *
 * @param string $agent
 * @param string $brinifile
 * @return
 */
function nv_getBrowser( $agent, $brinifile )
{
	$browsers = nv_parse_ini_file( $brinifile, true );
	
	foreach( $browsers as $key => $info )
	{
		if( preg_match( "#" . $info['rule'] . "#i", $agent, $results ) )
		{
			if( isset( $results[1] ) ) return ( $key . '|' . $info['name'] . ' v' . $results[1] );
			
			return ( $key . '|' . $info['name'] );
		}
	}
	
	return ( "Unknown|Unknown" );
}

/**
 * nv_getOs()
 *
 * @param string $agent
 * @param string $osinifile
 * @return
 */
function nv_getOs( $agent, $osinifile )
{
	$os = nv_parse_ini_file( $osinifile, true );
	
	foreach( $os as $key => $info )
	{
		if( preg_match( "#" . $info['rule'] . "#i", $agent, $results ) )
		{
			if( strstr( $key, "win" ) ) return ( $key . '|' . $info['name'] );
			if( isset( $results[1] ) ) return ( $key . '|' . $info['name'] . ' ' . $results[1] );
			
			return ( $key . '|' . $info['name'] );
		}
	}
	
	return ( "Unspecified|Unspecified" );
}

/**
 * nv_convertfromBytes()
 *
 * @param integer $size
 * @return
 */
function nv_convertfromBytes( $size )
{
	if( $size <= 0 ) return '0 bytes';
	if( $size == 1 ) return '1 byte';
	if( $size < 1024 ) return $size . ' bytes';

	$i = 0;
	$iec = array( "bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB" );
	
	while( ( $size / 1024 ) > 1 )
	{
		$size = $size / 1024;
		++$i;
	}
	
	return number_format( $size, 2 ) . ' ' . $iec[$i];
}

/**
 * nv_convertfromSec()
 *
 * @param integer $sec
 * @return
 */
function nv_convertfromSec( $sec = 0 )
{
	global $lang_global;

	$sec = intval( $sec );
	$min = 60;
	$hour = 3600;
	$day = 86400;
	$year = 31536000;

	if( $sec == 0 ) return "";
	if( $sec < $min ) return $sec . " " . $lang_global['sec'];
	if( $sec < $hour ) return trim( floor( $sec / $min ) . " " . " " . $lang_global['min'] . ( ( $sd = $sec % $min ) ? " " . nv_convertfromSec( $sd ) : "" ) );
	if( $sec < $day ) return trim( floor( $sec / $hour ) . " " . $lang_global['hour'] . ( ( $sd = $sec % $hour ) ? " " . nv_convertfromSec( $sd ) : "" ) );
	if( $sec < $year ) return trim( floor( $sec / $day ) . " " . $lang_global['day'] . ( ( $sd = $sec % $day ) ? " " . nv_convertfromSec( $sd ) : "" ) );

	return trim( floor( $sec / $year ) . " " . $lang_global['year'] . ( ( $sd = $sec % $year ) ? " " . nv_convertfromSec( $sd ) : "" ) );
}

/**
 * nv_converttoBytes()
 *
 * @param string $string
 * @return
 */
function nv_converttoBytes( $string )
{
	if( preg_match( '/^([0-9\.]+)[ ]*([b|k|m|g|t|p|e|z|y]*)/i', $string, $matches ) )
	{
		if( empty( $matches[2] ) ) return $matches[1];

		$suffixes = array( "B" => 0, "K" => 1, "M" => 2, "G" => 3, "T" => 4, "P" => 5, "E" => 6, "Z" => 7, "Y" => 8 );
			
		if( isset( $suffixes[strtoupper( $matches[2] )] ) ) return round( $matches[1] * pow( 1024, $suffixes[strtoupper( $matches[2] )] ) );
	}
	
	return false;
}

/**
 * nv_base64_encode()
 *
 * @param string $input
 * @return
 */
function nv_base64_encode( $input )
{
	return strtr( base64_encode( $input ), '+/=', '-_,' );
}

/**
 * nv_base64_decode()
 *
 * @param string $input
 * @return
 */
function nv_base64_decode( $input )
{
	return base64_decode( strtr( $input, '-_,', '+/=' ) );
}

/**
 * nv_function_exists()
 *
 * @param string $funcName
 * @return
 */
function nv_function_exists( $funcName )
{
	global $sys_info;

	return ( function_exists( $funcName ) and ! in_array( $funcName, $sys_info['disable_functions'] ) );
}

/**
 * nv_class_exists()
 *
 * @param string $clName
 * @return
 */
function nv_class_exists( $clName )
{
	global $sys_info;

	return ( class_exists( $clName ) and ! in_array( $clName, $sys_info['disable_classes'] ) );
}

/**
 * nv_check_valid_login()
 *
 * @param string $login
 * @param integer $max
 * @param integer $min
 * @return
 */
function nv_check_valid_login( $login, $max, $min )
{
	global $lang_global;

	$login = strip_tags( trim( $login ) );
	
	if( empty( $login ) ) return $lang_global['username_empty'];
	if( isset( $login{$max} ) ) return sprintf( $lang_global['usernamelong'], $login, $max );
	if( ! isset( $login{$min - 1} ) ) return sprintf( $lang_global['usernameadjective'], $login, $min );
	
	return "";
}

/**
 * nv_check_valid_pass()
 *
 * @param string $pass
 * @param integer $max
 * @param integer $min
 * @return
 */
function nv_check_valid_pass( $pass, $max, $min )
{
	global $lang_global;

	$pass = strip_tags( trim( $pass ) );
	
	if( empty( $pass ) ) return $lang_global['password_empty'];
	if( isset( $pass{$max} ) ) return sprintf( $lang_global['passwordlong'], $pass, $max );
	if( ! isset( $pass{$min - 1} ) ) return sprintf( $lang_global['passwordadjective'], $pass, $min );
	
	return "";
}

/**
 * nv_check_valid_email()
 *
 * @param string $mail
 * @return
 */
function nv_check_valid_email( $mail )
{
	global $lang_global, $global_config;

	$mail = strip_tags( trim( $mail ) );

	if( empty( $mail ) ) return $lang_global['email_empty'];

	if( function_exists( 'filter_var' ) and filter_var( $mail, FILTER_VALIDATE_EMAIL ) === false ) return sprintf( $lang_global['email_incorrect'], $mail );

	if( ! preg_match( $global_config['check_email'], $mail ) ) return sprintf( $lang_global['email_incorrect'], $mail );

	if( ! preg_match( "/\.(ac|ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|asia|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cat|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|info|int|io|iq|ir|is|it|je|jm|jo|jobs|jp|ke|kg|kh|ki|km|kn|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mobi|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tel|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|travel|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw|xxx)$/", $mail ) ) return sprintf( $lang_global['email_incorrect'], $mail );

	return "";
}

/**
 * nv_capcha_txt()
 *
 * @param string $seccode
 * @param string $scaptcha
 * @return
 */
function nv_capcha_txt( $seccode )
{
	global $sys_info, $global_config, $nv_Request;
    
    mt_srand( ( double )microtime() * 1000000 );
    $maxran = 1000000;
    $random = mt_rand( 0, $maxran );

	if( $global_config['captcha_type'] == 1 )
	{
	    $scaptcha = isset( $_SESSION['scaptcha'] ) ? $_SESSION['scaptcha'] : '';
	    $_SESSION['scaptcha'] = $random;
		if( !empty( $scaptcha ) AND strtolower( $scaptcha ) == strtolower( $seccode ) ) return true;
		return false;
	}
	else
	{
		$seccode = strtoupper( $seccode );
		$random_num = $nv_Request->get_string( 'random_num', 'session', 0 );
		$datekey = date( "F j" );
		$rcode = strtoupper( md5( NV_USER_AGENT . $global_config['sitekey'] . $random_num . $datekey ) );

		$nv_Request->set_Session( 'random_num', $random );
		return ( preg_match( "/^[a-zA-Z0-9]{" . NV_GFX_NUM . "}$/", $seccode ) and $seccode == substr( $rcode, 2, NV_GFX_NUM ) );
	}
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
	
	for( $k = 0; $k < $length - 1; ++$k )
	{
		$probab = mt_rand( 1, 10 );
		$pass .= ( $probab <= 8 ) ? chr( mt_rand( 97, 122 ) ) : chr( mt_rand( 48, 57 ) );
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
function nv_EncodeEmail( $strEmail, $strDisplay = '', $blnCreateLink = true )
{
	$strMailto = "&#109;&#097;&#105;&#108;&#116;&#111;&#058;";
	$strEncodedEmail = "";
	$strlen = strlen( $strEmail );
	
	for( $i = 0; $i < $strlen; ++$i )
	{
		$strEncodedEmail .= "&#" . ord( substr( $strEmail, $i ) ) . ";";
	}

	$strDisplay = trim( $strDisplay );
	$strDisplay = ! empty( $strDisplay ) ? $strDisplay : $strEncodedEmail;
	
	if( $blnCreateLink ) return "<a href=\"" . $strMailto . $strEncodedEmail . "\">" . $strDisplay . "</a>";
	
	return $strDisplay;
}

/**
 * nv_user_groups()
 *
 * @param string $in_groups
 * @return
 */
function nv_user_groups( $in_groups )
{
	global $db;

	if( empty( $in_groups ) ) return "";

	$query = "SELECT `group_id`, `title`, `exp_time`, `public` FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `act`=1 ORDER BY `weight`";
	$list = nv_db_cache( $query, '', 'users' );

	if( empty( $list ) ) return "";

	$in_groups = explode( ",", $in_groups );
	$groups = array();
	$reload = array();
	
	for( $i = 0, $count = sizeof( $list ); $i < $count; ++$i )
	{
		if( $list[$i]['exp_time'] != 0 and $list[$i]['exp_time'] <= NV_CURRENTTIME )
		{
			$reload[] = $list[$i]['group_id'];
		}
		elseif( in_array( $list[$i]['group_id'], $in_groups ) )
		{
			$groups[] = $list[$i]['group_id'];
		}
	}

	if( $reload )
	{
		$sql = "UPDATE `" . NV_GROUPS_GLOBALTABLE . "` SET `act`='0' WHERE `group_id` IN (" . implode( ",", $reload ) . ")";
		$db->sql_query( $sql );
		nv_del_moduleCache( 'users' );
	}

	if( empty( $groups ) ) return "";

	return implode( ",", $groups );
}

/**
 * nv_is_in_groups()
 *
 * @param string $in_groups
 * @param string $groups
 * @return
 */
function nv_is_in_groups( $in_groups, $groups )
{
	if( empty( $groups ) || empty( $in_groups ) ) return false;
	
	$in_groups = explode( ",", $in_groups );
	$groups = explode( ",", $groups );
	
	return ( array_intersect( $in_groups, $groups ) != array() );
}

/**
 * nv_set_allow()
 *
 * @param integer $who
 * @param string $groups
 * @return
 */
function nv_set_allow( $who, $groups )
{
	global $user_info;

	if( ! $who or ( $who == 1 and defined( 'NV_IS_USER' ) ) or ( $who == 2 and defined( 'NV_IS_ADMIN' ) ) ) return true;

	if( $who == 3 and ! empty( $groups ) and defined( 'NV_IS_USER' ) and nv_is_in_groups( $user_info['in_groups'], $groups ) ) return true;

	return false;
}

/**
 * nv_date()
 *
 * @param string $format
 * @param integer $time
 * @return
 */
function nv_date( $format, $time = 0 )
{
	global $lang_global;

	if( ! $time ) $time = NV_CURRENTTIME;
	$return = date( $format, $time );

	$replaces = array(
		'Sunday' => $lang_global['sunday'],
		'Monday' => $lang_global['monday'],
		'Tuesday' => $lang_global['tuesday'],
		'Wednesday' => $lang_global['wednesday'],
		'Thursday' => $lang_global['thursday'],
		'Friday' => $lang_global['friday'],
		'Saturday' => $lang_global['saturday'],
		'January' => $lang_global['january'],
		'February' => $lang_global['february'],
		'March' => $lang_global['march'],
		'April' => $lang_global['april'],
		'May' => $lang_global['may'],
		'June' => $lang_global['june'],
		'July' => $lang_global['july'],
		'August' => $lang_global['august'],
		'September' => $lang_global['september'],
		'October' => $lang_global['october'],
		'November' => $lang_global['november'],
		'December' => $lang_global['december']
	);
	$return = str_replace( array_keys( $replaces ), array_values( $replaces ), $return );

	$replaces = array(
		'Sun' => $lang_global['sun'],
		'Mon' => $lang_global['mon'],
		'Tue' => $lang_global['tue'],
		'Wed' => $lang_global['wed'],
		'Thu' => $lang_global['thu'],
		'Fri' => $lang_global['fri'],
		'Sat' => $lang_global['sat'],
		'Jan' => $lang_global['jan'],
		'Feb' => $lang_global['feb'],
		'Mar' => $lang_global['mar'],
		'Apr' => $lang_global['apr'],
		'May' => $lang_global['may2'],
		'Jun' => $lang_global['jun'],
		'Jul' => $lang_global['jul'],
		'Aug' => $lang_global['aug'],
		'Sep' => $lang_global['sep'],
		'Oct' => $lang_global['oct'],
		'Nov' => $lang_global['nov'],
		'Dec' => $lang_global['dec']
	);
	
	return str_replace( array_keys( $replaces ), array_values( $replaces ), $return );
}

/**
 * nv_monthname()
 *
 * @param integer $i
 * @return
 */
function nv_monthname( $i )
{
	global $lang_global;

	--$i;
	$month_names = array(
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
	);

	return ( isset( $month_names[$i] ) ? $month_names[$i] : "" );
}

/**
 * nv_unhtmlspecialchars()
 *
 * @param mixed $string
 * @return
 */
function nv_unhtmlspecialchars( $string )
{
	if( empty( $string ) ) return $string;

	if( is_array( $string ) )
	{
		$array_keys = array_keys( $string );
		
		foreach( $array_keys as $key )
		{
			$string[$key] = nv_unhtmlspecialchars( $string[$key] );
		}
	}
	else
	{
		$search = array('&amp;', '&#039;', '&quot;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x23;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;');
		$replace = array('&', '\'', '"', '<', '>', '\\', '/', '(', ')', '*', '[', ']', '!', '=', '#', '%', '^', ':', '{', '}', '`', '~');
		
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
	if( empty( $string ) ) return $string;

	if( is_array( $string ) )
	{
		$array_keys = array_keys( $string );
		
		foreach( $array_keys as $key )
		{
			$string[$key] = nv_htmlspecialchars( $string[$key] );
		}
	}
	else
	{
		$search = array('&', '\'', '"', '<', '>', '\\', '/', '(', ')', '*', '[', ']', '!', '=', '%', '^', ':', '{', '}', '`', '~');
		$replace = array('&amp;', '&#039;', '&quot;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;');

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

	return preg_replace(
		array( // Remove separator, control, formatting, surrogate, open/close quotes.
			'/[\p{Z}\p{Cc}\p{Cf}\p{Cs}\p{Pi}\p{Pf}]/u', // Remove other punctuation except special cases
			'/\p{Po}(?<![' . $specialquotes . $numseparators . $urlall . $nummodifiers . '])/u', // Remove non-URL open/close brackets, except URL brackets.
			'/[\p{Ps}\p{Pe}](?<![' . $urlbrackets . '])/u', // Remove special quotes, dashes, connectors, number separators, and URL characters followed by a space
			'/[' . $specialquotes . $numseparators . $urlspaceafter . '\p{Pd}\p{Pc}]+((?= )|$)/u', // Remove special quotes, connectors, and URL characters preceded by a space
			'/((?<= )|^)[' . $specialquotes . $urlspacebefore . '\p{Pc}]+/u', // Remove dashes preceded by a space, but not followed by a number
			'/((?<= )|^)\p{Pd}+(?![\p{N}\p{Sc}])/u', // Remove consecutive spaces
			'/ +/'
		),
		' ',
		$text
	);
}

/**
 * nv_nl2br()
 *
 * @param string $text
 * @param string $replacement
 * @return
 */
function nv_nl2br( $text, $replacement = '<br />' )
{
	if( empty( $text ) ) return '';
	
	return strtr( $text, array(
			"\r\n" => $replacement,
			"\r" => $replacement,
			"\n" => $replacement
		)
	);
}

/**
 * nv_br2nl()
 *
 * @param string $text
 * @return
 */
function nv_br2nl( $text )
{
	if( empty( $text ) ) return '';
	
	return preg_replace( '/\<br(\s*)?\/?(\s*)?\>/i', chr( 13 ) . chr( 10 ), $text );
}

/**
 * nv_editor_nl2br()
 *
 * @param string $text
 * @return
 */
function nv_editor_nl2br( $text )
{
	if( empty( $text ) ) return '';
	
	$replacement = defined( 'NV_EDITOR' ) ? '' : '<br />';
	
	return nv_nl2br( $text, ( defined( 'NV_EDITOR' ) ? '' : '<br />' ) );
}

/**
 * nv_editor_br2nl()
 *
 * @param mixed $text
 * @return
 */
function nv_editor_br2nl( $text )
{
	if( empty( $text ) ) return '';
	
	if( defined( 'NV_EDITOR' ) ) return $text;
	
	return nv_br2nl( $text );
}

/**
 * filter_text_input()
 *
 * @param string $inputname
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
	
	if( ( bool )$specialchars == true )
	{
		$value = nv_htmlspecialchars( $value );
	}
	
	if( ( int )$maxlength > 0 )
	{
		$value = nv_substr( $value, 0, $maxlength );
	}
	
	if( ! empty( $preg_replace ) )
	{
		if( isset( $preg_replace['pattern'] ) and ! empty( $preg_replace['pattern'] ) and isset( $preg_replace['replacement'] ) )
		{
			$value = preg_replace( $preg_replace['pattern'], $preg_replace['replacement'], $value );
		}
	}
	
	return trim( $value );
}

/**
 * filter_text_textarea()
 *
 * @param string $inputname
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
	
	if( empty( $value ) ) return $value;
	
	if( ! empty( $allowed_html_tags ) )
	{
		$allowed_html_tags = array_map( "trim", explode( ",", $allowed_html_tags ) );
		$allowed_html_tags = "<" . implode( "><", $allowed_html_tags ) . ">";
		$value = strip_tags( $value, $allowed_html_tags );
	}
	
	if( ( bool )$save ) $value = nv_nl2br( $value, $nl2br_replacement );
	
	return $value;
}

/**
 * nv_editor_filter_textarea()
 *
 * @param string $inputname
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
	
	if( empty( $value ) ) return '';

	if( ! empty( $allowed_html_tags ) and ! defined( 'NV_EDITOR' ) )
	{
		$allowed_html_tags = array_map( "trim", explode( ",", $allowed_html_tags ) );
		$allowed_html_tags = "<" . implode( "><", $allowed_html_tags ) . ">";
		$value = strip_tags( $value, $allowed_html_tags );
	}

	if( empty( $value ) ) return '';

	if( ( bool )$save )
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
function nv_get_keywords( $content = "" )
{
	if( empty( $content ) ) return ( "" );

	$content = strip_tags( $content );
	$content = nv_unhtmlspecialchars( $content );
	$content = strip_punctuation( $content );
	$content = trim( $content );
	$content = nv_strtolower( $content );

	$content = " " . $content . " ";
	$keywords_return = array();

	$memoryLimitMB = ( integer )ini_get( 'memory_limit' );
	
	if( $memoryLimitMB > 60 and file_exists( NV_ROOTDIR . "/includes/keywords/" . NV_LANG_DATA . ".php" ) )
	{
		require ( NV_ROOTDIR . "/includes/keywords/" . NV_LANG_DATA . ".php" );
		
		$content_array = explode( " ", $content );
		$a = 0;
		$b = sizeof( $content_array );
		
		for( $i = 0; $i < $b - 3; ++$i )
		{
			$key3 = $content_array[$i] . ' ' . $content_array[$i + 1] . ' ' . $content_array[$i + 2];
			$key2 = $content_array[$i] . ' ' . $content_array[$i + 1];
			
			if( array_search( $key3, $array_keywords_3 ) )
			{
				$keywords_return[] = $key3;
				$i = $i + 2;
			}
			elseif( array_search( $key2, $array_keywords_2 ) )
			{
				$keywords_return[] = $key2;
				$i = $i + 1;
			}
			
			$keywords_return = array_unique( $keywords_return );
			
			if( sizeof( $keywords_return ) > 20 )
			{
				break;
			}
		}
	}
	else
	{
		$pattern_word = array();

		if( NV_SITEWORDS_MIN_3WORDS_LENGTH > 0 and NV_SITEWORDS_MIN_3WORDS_PHRASE_OCCUR > 0 )
		{
			$pattern_word[] = "/[\s]+([\S]{" . NV_SITEWORDS_MIN_3WORDS_LENGTH . ",}\s[\S]{" . NV_SITEWORDS_MIN_3WORDS_LENGTH . ",}\s[\S]{" . NV_SITEWORDS_MIN_3WORDS_LENGTH . ",})[\s]+/uis";
		}

		if( NV_SITEWORDS_MIN_2WORDS_LENGTH > 0 and NV_SITEWORDS_MIN_2WORDS_PHRASE_OCCUR > 0 )
		{
			$pattern_word[] = "/[\s]+([\S]{" . NV_SITEWORDS_MIN_2WORDS_LENGTH . ",}\s[\S]{" . NV_SITEWORDS_MIN_2WORDS_LENGTH . ",})[\s]+/uis";
		}

		if( NV_SITEWORDS_MIN_WORD_LENGTH > 0 and NV_SITEWORDS_MIN_WORD_OCCUR > 0 )
		{
			$pattern_word[] = "/[\s]+([\S]{" . NV_SITEWORDS_MIN_WORD_LENGTH . ",})[\s]+/uis";
		}

		if( empty( $pattern_word ) ) return ( "" );

		$lenght = 0;
		$max_strlen = min( NV_SITEWORDS_MAX_STRLEN, 300 );

		foreach( $pattern_word as $pattern )
		{
			while( preg_match( $pattern, $content, $matches ) )
			{
				$keywords_return[] = $matches[1];
				$lenght += nv_strlen( $matches[1] );

				$content = preg_replace( "/[\s]+(" . preg_quote( $matches[1] ) . ")[\s]+/uis", " ", $content );

				if( $lenght >= $max_strlen ) break;
			}

			if( $lenght >= $max_strlen ) break;
		}
		
		$keywords_return = array_unique( $keywords_return );
	}
	
	return implode( ",", $keywords_return );
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
function nv_sendmail( $from, $to, $subject, $message, $files = '' )
{
	global $global_config, $sys_info;

	$sendmail_from = ini_get( 'sendmail_from' );
	
	require_once ( NV_ROOTDIR . '/includes/phpmailer/class.phpmailer.php' );
	
	try
	{
		$mail = new PHPMailer( true );
		$mail->SetLanguage(NV_LANG_INTERFACE, NV_ROOTDIR . '/includes/phpmailer/language/');
		$mail->CharSet = $global_config['site_charset'];
		$mailer_mode = strtolower( $global_config['mailer_mode'] );
		
		if( $mailer_mode == 'smtp' )
		{
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->Port = $global_config['smtp_port'];
			$mail->Host = $global_config['smtp_host'];
			$mail->Username = $global_config['smtp_username'];
			$mail->Password = $global_config['smtp_password'];
			$SMTPSecure = intval( $global_config['smtp_ssl'] );
			
			switch( $SMTPSecure )
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
		elseif( $mailer_mode == 'sendmail' )
		{
			$mail->IsSendmail();
		}
		elseif( ! in_array( 'mail', $sys_info['disable_functions'] ) )
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
		
		if( is_array( $from ) )
		{
			$mail->AddReplyTo( $from[1], $from[0] );
		}
		else
		{
			$mail->AddReplyTo( $from );
		}

		if( empty( $to ) ) return false;

		if( ! is_array( $to ) ) $to = array( $to );

		foreach( $to as $_to )
		{
			$mail->AddAddress( $_to );
		}

		$mail->Subject = $subject;
		$mail->WordWrap = 120;
		$mail->MsgHTML( $message );
		$mail->IsHTML( true );
		
		if( ! empty( $files ) )
		{
			$files = array_map( "trim", explode( ",", $files ) );
			
			foreach( $files as $file )
			{
				$mail->AddAttachment( $file );
			}
		}
		
		$send = $mail->Send();
		
		if( ! $send )
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
 * @param string $base_url
 * @param integer $num_items
 * @param integer $per_page
 * @param integer $start_item
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
	
	if( $total_pages == 1 ) return '';

	$on_page = @floor( $start_item / $per_page ) + 1;

	if( ! is_array( $base_url ) )
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
	
	if( $total_pages > 10 )
	{
		$init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;
		
		for( $i = 1; $i <= $init_page_max; ++$i )
		{
			$href = ( $i - 1 ) * $per_page;
			$href = $href ? $base_url . $amp . $href : $base_url;
			$href = ! $onclick ? "href=\"" . $href . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $href ) ) . "','" . $containerid . "')\"";
			$page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
			if( $i < $init_page_max ) $page_string .= ", ";
		}
		
		if( $total_pages > 3 )
		{
			if( $on_page > 1 && $on_page < $total_pages )
			{
				$page_string .= ( $on_page > 5 ) ? " ... " : ", ";
				$init_page_min = ( $on_page > 4 ) ? $on_page : 5;
				$init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;
				
				for( $i = $init_page_min - 1; $i < $init_page_max + 2; ++$i )
				{
					$href = ( $i - 1 ) * $per_page;
					$href = $href ? $base_url . $amp . $href : $base_url;
					$href = ! $onclick ? "href=\"" . $href . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $href ) ) . "','" . $containerid . "')\"";
					$page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
					
					if( $i < $init_page_max + 1 )
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

			for( $i = $total_pages - 2; $i < $total_pages + 1; ++$i )
			{
				$href = ( $i - 1 ) * $per_page;
				$href = $href ? $base_url . $amp . $href : $base_url;
				$href = ! $onclick ? "href=\"" . $href . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $href ) ) . "','" . $containerid . "')\"";
				$page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
			
				if( $i < $total_pages )
				{
					$page_string .= ", ";
				}
			}
		}
	}
	else
	{
		for( $i = 1; $i < $total_pages + 1; ++$i )
		{
			$href = ( $i - 1 ) * $per_page;
			$href = $href ? $base_url . $amp . $href : $base_url;
			$href = ! $onclick ? "href=\"" . $href . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $href ) ) . "','" . $containerid . "')\"";
			$page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
		
			if( $i < $total_pages )
			{
				$page_string .= ", ";
			}
		}
	}

	if( $add_prevnext_text )
	{
		if( $on_page > 1 )
		{
			$href = ( $on_page - 2 ) * $per_page;
			$href = $href ? $base_url . $amp . $href : $base_url;
			$href = ! $onclick ? "href=\"" . $href . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $href ) ) . "','" . $containerid . "')\"";
			$page_string = "&nbsp;&nbsp;<span><a " . $href . ">" . $lang_global['pageprev'] . "</a></span>&nbsp;&nbsp;" . $page_string;
		}
	
		if( $on_page < $total_pages )
		{
			$href = $on_page * $per_page;
			$href = $href ? $base_url . $amp . $href : $base_url;
			$href = ! $onclick ? "href=\"" . $href . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode( nv_unhtmlspecialchars( $href ) ) . "','" . $containerid . "')\"";
			$page_string .= "&nbsp;&nbsp;<span><a " . $href . ">" . $lang_global['pagenext'] . "</a></span>";
		}
	}
	
	return $page_string;
}

function nv_alias_page( $title, $base_url, $num_items, $per_page, $on_page, $add_prevnext_text = true )
{
	global $lang_global;

	$total_pages = ceil( $num_items / $per_page );
	
	if( $total_pages < 2 ) return '';

	$title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'];
	$page_string = ( $on_page == 1 ) ? "<strong>1</strong> " : "<a title=\"" . $title . " 1\" href=\"" . $base_url . "\">1</a> ";

	if( $total_pages > 10 )
	{
		$init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;
	
		for( $i = 2; $i <= $init_page_max; ++$i )
		{
			$page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a title=\"" . $title . " " . $i . "\" href=\"" . $base_url . "/page-" . $i . "\">" . $i . "</a>";
			
			if( $i < $init_page_max ) $page_string .= " ";
		}
	
		if( $total_pages > 3 )
		{
			if( $on_page > 1 && $on_page < $total_pages )
			{
				$page_string .= ( $on_page > 5 ) ? " ... " : " ";
				$init_page_min = ( $on_page > 4 ) ? $on_page : 5;
				$init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;
			
				for( $i = $init_page_min - 1; $i < $init_page_max + 2; ++$i )
				{
					$page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a title=\"" . $title . " " . $i . "\" href=\"" . $base_url . "/page-" . $i . "\">" . $i . "</a>";
				
					if( $i < $init_page_max + 1 )
					{
						$page_string .= " ";
					}
				}
			
				$page_string .= ( $on_page < $total_pages - 4 ) ? " ... " : " ";
			}
			else
			{
				$page_string .= " ... ";
			}

			for( $i = $total_pages - 2; $i < $total_pages + 1; ++$i )
			{
				$page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a title=\"" . $title . " " . $i . "\" href=\"" . $base_url . "/page-" . $i . "\">" . $i . "</a>";
				
				if( $i < $total_pages )
				{
					$page_string .= " ";
				}
			}
		}
	}
	else
	{
		for( $i = 2; $i < $total_pages + 1; ++$i )
		{
			$page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a title=\"" . $title . " " . $i . "\" href=\"" . $base_url . "/page-" . $i . "\">" . $i . "</a>";
		
			if( $i < $total_pages )
			{
				$page_string .= " ";
			}
		}
	}

	if( $add_prevnext_text )
	{
		if( $on_page > 1 )
		{
			$page_string = "&nbsp;&nbsp;<span><a title=\"" . $title . " " . ( $on_page - 1 ) . "\" href=\"" . $base_url . "/page-" . ( $on_page - 1 ) . "\">" . $lang_global['pageprev'] . "</a></span>&nbsp;&nbsp;" . $page_string;
		}
	
		if( $on_page < $total_pages )
		{
			$page_string .= "&nbsp;&nbsp;<span><a title=\"" . $title . " " . ( $on_page + 1 ) . "\"  href=\"" . $base_url . "/page-" . ( $on_page + 1 ) . "\">" . $lang_global['pagenext'] . "</a></span>";
		}
	}
	
	return $page_string;
}

/**
 * nv_is_url()
 *
 * @param string $url
 * @return
 */
function nv_is_url( $url )
{
	if( empty( $url ) ) return false;

	$url = nv_strtolower( $url );

	if( ! ( $parts = @parse_url( $url ) ) ) return false;

	if( ! isset( $parts['scheme'] ) or ! isset( $parts['host'] ) or ( ! in_array( $parts['scheme'], array( 'http', 'https', 'ftp', 'gopher' ) ) ) ) return false;

	if( ! preg_match( "/^[0-9a-z]([\-\.]?[0-9a-z])*\.(ac|ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|asia|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cat|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|info|int|io|iq|ir|is|it|je|jm|jo|jobs|jp|ke|kg|kh|ki|km|kn|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mobi|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tel|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|travel|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw|xxx)$/", $parts['host'] ) )
	{
		if( $parts['host'] != 'localhost' and ! filter_var($parts['host'], FILTER_VALIDATE_IP) )
		{
			return false;
		}
	}

	if( isset( $parts['user'] ) and ! preg_match( "/^([0-9a-z\-]|[\_])*$/", $parts['user'] ) ) return false;

	if( isset( $parts['pass'] ) and ! preg_match( "/^([0-9a-z\-]|[\_])*$/", $parts['pass'] ) ) return false;

	if( isset( $parts['path'] ) and ! preg_match( "/^[0-9A-Za-z\/\_\.\@\~\-\%\\s]*$/", $parts['path'] ) ) return false;

	if( isset( $parts['query'] ) and ! preg_match( "/^[0-9a-z\-\_\/\?\&\=\#\.\,\;\%\\s]*$/", $parts['query'] ) ) return false;

	return true;
}

/**
 * nv_check_url()
 *
 * @param string $url
 * @param bool $is_200
 * @return
 */
function nv_check_url( $url, $is_200 = 0 )
{
	if( empty( $url ) ) return false;

	$url = str_replace( " ", "%20", $url );
	$allow_url_fopen = ( ini_get( 'allow_url_fopen' ) == '1' || strtolower( ini_get( 'allow_url_fopen' ) ) == 'on' ) ? 1 : 0;

	if( nv_function_exists( 'get_headers' ) and $allow_url_fopen == 1 )
	{
		$res = get_headers( $url );
	}
	elseif( nv_function_exists( 'curl_init' ) and nv_function_exists( 'curl_exec' ) )
	{
		$url_info = @parse_url( $url );
		$port = isset( $url_info['port'] ) ? intval( $url_info['port'] ) : 80;

		$userAgents = array( //
			'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0', //
			'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', //
			'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)', //
			'Mozilla/4.8 [en] (Windows NT 6.0; U)', //
			'Opera/9.25 (Windows NT 6.0; U; en)'
		);

		$safe_mode = ( ini_get( 'safe_mode' ) == '1' || strtolower( ini_get( 'safe_mode' ) ) == 'on' ) ? 1 : 0;
		$open_basedir = ( ini_get( 'open_basedir' ) == '1' || strtolower( ini_get( 'open_basedir' ) ) == 'on' ) ? 1 : 0;

		srand( ( float )microtime() * 10000000 );
		$rand = array_rand( $userAgents );
		$agent = $userAgents[$rand];

		$curl = curl_init( $url );
		curl_setopt( $curl, CURLOPT_HEADER, true );
		curl_setopt( $curl, CURLOPT_NOBODY, true );
		curl_setopt( $curl, CURLOPT_PORT, $port );
	
		if( ! $safe_mode and $open_basedir )
		{
			curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
		}

		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_TIMEOUT, 15 );
		curl_setopt( $curl, CURLOPT_USERAGENT, $agent );

		$response = curl_exec( $curl );
		curl_close( $curl );

		if( $response === false )
		{
			trigger_error( curl_error( $curl ), E_USER_WARNING );
		
			return false;
		}
		else
		{
			$res = explode( "\n", $response );
		}
	}
	elseif( nv_function_exists( 'fsockopen' ) and nv_function_exists( 'fgets' ) )
	{
		$res = array();
		$url_info = parse_url( $url );
		$port = isset( $url_info['port'] ) ? intval( $url_info['port'] ) : 80;
		$fp = fsockopen( $url_info['host'], $port, $errno, $errstr, 15 );
	
		if( ! $fp )
		{
			trigger_error( $errstr, E_USER_WARNING );
			return false;
		}

		$path = ! empty( $url_info['path'] ) ? $url_info['path'] : '/';
		$path .= ! empty( $url_info['query'] ) ? '?' . $url_info['query'] : '';

		fputs( $fp, "HEAD " . $path . " HTTP/1.0\r\n" );
		fputs( $fp, "Host: " . $url_info['host'] . ":" . $port . "\r\n" );
		fputs( $fp, "Connection: close\r\n\r\n" );

		while( ! feof( $fp ) )
		{
			if( $header = trim( fgets( $fp, 1024 ) ) )
			{
				$res[] = $header;
			}
		}
		@fclose( $fp );
	}
	else
	{
		trigger_error( 'error server no support check url', E_USER_WARNING );
		
		return false;
	}

	if( empty( $res ) ) return false;

	if( preg_match( "/(200)/", $res[0] ) ) return true;
	if( $is_200 > 5 ) return false;

	if( preg_match( "/(301)|(302)|(303)/", $res[0] ) )
	{
		foreach( $res as $k => $v )
		{
			if( preg_match( "/location:\s(.*?)$/is", $v, $matches ) )
			{
				++$is_200;
				$location = trim( $matches[1] );
			
				return nv_check_url( $location, $is_200 );
			}
		}
	}

	return false;
}

/**
 * nv_check_rewrite_file()
 *
 * @return
 */
function nv_check_rewrite_file()
{
	global $sys_info;

	if( $sys_info['supports_rewrite'] == 'rewrite_mode_apache' )
	{
		if( ! file_exists( NV_ROOTDIR . '/.htaccess' ) ) return false;

		$htaccess = @file_get_contents( NV_ROOTDIR . '/.htaccess' );
	
		return ( preg_match( "/\#nukeviet\_rewrite\_start(.*)\#nukeviet\_rewrite\_end/s", $htaccess ) );
	}

	if( $sys_info['supports_rewrite'] == 'rewrite_mode_iis' )
	{
		if( ! file_exists( NV_ROOTDIR . '/web.config' ) ) return false;

		$web_config = @file_get_contents( NV_ROOTDIR . '/web.config' );
	
		return ( preg_match( "/<rule name=\"nv_rule_rewrite\">(.*)<\/rule>/s", $web_config ) );
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
function nv_url_rewrite( $buffer, $is_url = false )
{
	global $rewrite;

	if( ! empty( $rewrite ) )
	{
		if( $is_url ) $buffer = "\"" . $buffer . "\"";

		$buffer = preg_replace( array_keys( $rewrite ), array_values( $rewrite ), $buffer );

		if( $is_url ) $buffer = substr( $buffer, 1, -1 );
	}

	return $buffer;
}

/**
 * nv_valid_html()
 *
 * @param string $html
 * @param mixed $config
 * @param string $encoding
 * @return
 */
function nv_valid_html( $html, $config, $encoding = 'utf8' )
{
	global $sys_info;

	if( $sys_info['supports_tidy'] == "class" )
	{
		$tidy = new tidy();
		$tidy->parseString( $html, $config, $encoding );
		$tidy->cleanRepair();
		return $tidy;
	}

	if( $sys_info['supports_tidy'] == "func" )
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
function nv_change_buffer( $buffer )
{
	global $db, $sys_info, $global_config;

	$buffer = $db->unfixdb( $buffer );
	$buffer = nv_url_rewrite( $buffer );

	if( defined( "NV_ANTI_IFRAME" ) and NV_ANTI_IFRAME ) $buffer = preg_replace( "/(<body[^>]*>)/", "$1\r\n<script type=\"text/javascript\">if(window.top!==window.self){document.write=\"\";window.top.location=window.self.location;setTimeout(function(){document.body.innerHTML=\"\"},1);window.self.onload=function(){document.body.innerHTML=\"\"}};</script>", $buffer, 1 );

	if( ! empty( $global_config['googleAnalyticsID'] ) and preg_match( '/^UA-\d{4,}-\d+$/', $global_config['googleAnalyticsID'] ) )
	{
		$dp = "";
		if( $global_config['googleAnalyticsSetDomainName'] == 1 )
		{
			$dp .= "_gaq.push([\"_setDomainName\",\"" . $global_config['cookie_domain'] . "\"]);";
		}
		elseif( $global_config['googleAnalyticsSetDomainName'] == 2 )
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

	if ( ( $global_config['optActive'] == 1 ) || ( ! defined( 'NV_ADMIN' ) and $global_config['optActive'] == 2 ) || ( defined( 'NV_ADMIN' ) and $global_config['optActive'] == 3 ) )
	{
		include_once ( NV_ROOTDIR . '/includes/class/optimizer.class.php' );
		$optimezer = new optimezer( $buffer, $sys_info['supports_tidy'] );
		$buffer = $optimezer->process();

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
			'alt-text' => true	//Bat buoc phai co alt trong IMG
		);
		$buffer = nv_valid_html( $buffer, $config );
	}

	if( ! $sys_info['supports_rewrite'] ) 
	{
		$buffer = preg_replace( "/\<(script|link)(.*?)(src|href)=['\"]((?!http(s?)|ftp\:\/\/).*?\.(js|css))['\"](.*?)\>/", "<\\1\\2\\3=\"" . NV_BASE_SITEURL . "CJzip.php?file=\\4&amp;r=".$global_config['timestamp']."\"\\7>", $buffer );
	}
	else
	{
		$buffer = preg_replace( "/\<(script|link)(.*?)(src|href)=['\"]((?!http(s?)|ftp\:\/\/).*?\.(js|css))['\"](.*?)\>/", "<\\1\\2\\3=\"\\4?t=".$global_config['timestamp']."\"\\7>", $buffer );
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
 * @param integer $userid
 * @param string $link_acess
 * @return
 */
function nv_insert_logs( $lang = "", $module_name = "", $name_key = "", $note_action = "", $userid = 0, $link_acess = "" )
{
	global $db_config, $db;

	$sql = "INSERT INTO `" . $db_config['prefix'] . "_logs` (`id` ,`lang`,`module_name`,`name_key`,`note_action` ,`link_acess` ,`userid` ,`log_time` ) VALUES ( NULL , " . $db->dbescape( $lang ) . ", " . $db->dbescape( $module_name ) . ", " . $db->dbescape( $name_key ) . ", " . $db->dbescape( $note_action ) . ", " . $db->dbescape( $note_action ) . ", " . intval( $userid ) . ", " . NV_CURRENTTIME . ");";

	if( $db->sql_query_insert_id( $sql ) )
	{
		nv_del_moduleCache( 'siteinfo' );
		return true;
	}
	
	return false;
}

/**
 * nv_listUploadDir()
 *
 * @param mixed $dir
 * @param mixed $real_dirlist
 * @return
 */
function nv_listUploadDir( $dir, $real_dirlist = array() )
{
	$real_dirlist[] = $dir;

	if( ( $dh = @opendir( NV_ROOTDIR . '/' . $dir ) ) !== false )
	{
		while( false !== ( $subdir = readdir( $dh ) ) )
		{
			if( preg_match( "/^[a-zA-Z0-9\-\_]+$/", $subdir ) )
			{
				if( is_dir( NV_ROOTDIR . '/' . $dir . '/' . $subdir ) ) $real_dirlist = nv_listUploadDir( $dir . '/' . $subdir, $real_dirlist );				
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
function nv_loadUploadDirList( $return = true )
{
	$allow_upload_dir = array( 'images', NV_UPLOADS_DIR );
	$dirlistCache = NV_ROOTDIR . "/" . NV_FILES_DIR . "/dcache/dirlist-" . md5( implode( $allow_upload_dir ) );

	$real_dirlist = array();

	foreach( $allow_upload_dir as $dir )
	{
		$real_dirlist = nv_listUploadDir( $dir, $real_dirlist );
	}

	ksort( $real_dirlist );
	file_put_contents( $dirlistCache, serialize( $real_dirlist ) );

	if( $return ) return $real_dirlist;
}

?>