<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

/**
 * nv_save_file_banip()
 *
 * @return
 */
function nv_save_file_banip()
{
	global $db, $db_config;

	$content_config_site = '';
	$content_config_admin = '';

	$result = $db->query( 'SELECT ip, mask, area, begintime, endtime FROM ' . $db_config['prefix'] . '_banip' );
	while( list( $dbip, $dbmask, $dbarea, $dbbegintime, $dbendtime ) = $result->fetch( 3 ) )
	{
		$dbendtime = intval( $dbendtime );
		$dbarea = intval( $dbarea );

		if( $dbendtime == 0 or $dbendtime > NV_CURRENTTIME )
		{
			switch( $dbmask )
			{
				case 3:
					$ip_mask = '/\.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}$/';
					break;
				case 2:
					$ip_mask = '/\.[0-9]{1,3}.[0-9]{1,3}$/';
					break;
				case 1:
					$ip_mask = '/\.[0-9]{1,3}$/';
					break;
				default:
					$ip_mask = '//';
			}

			if( $dbarea == 1 or $dbarea == 3 )
			{
				$content_config_site .= "\$array_banip_site['" . $dbip . "'] = array( 'mask' => \"" . $ip_mask . "\", 'begintime' => " . $dbbegintime . ", 'endtime' => " . $dbendtime . " );\n";
			}

			if( $dbarea == 2 or $dbarea == 3 )
			{
				$content_config_admin .= "\$array_banip_admin['" . $dbip . "'] = array( 'mask' => \"" . $ip_mask . "\", 'begintime' => " . $dbbegintime . ", 'endtime' => " . $dbendtime . " );\n";
			}
		}
	}

	if( ! $content_config_site and ! $content_config_admin )
	{
		nv_deletefile( NV_ROOTDIR . '/' . NV_DATADIR . '/banip.php' );
		return true;
	}

	$content_config = "<?php\n\n";
	$content_config .= NV_FILEHEAD . "\n\n";
	$content_config .= "if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );\n\n";
	$content_config .= "\$array_banip_site = array();\n";
	$content_config .= $content_config_site;
	$content_config .= "\n";
	$content_config .= "\$array_banip_admin = array();\n";
	$content_config .= $content_config_admin;

	$write = file_put_contents( NV_ROOTDIR . '/' . NV_DATADIR . '/banip.php', $content_config, LOCK_EX );

	if( $write === false ) return $content_config;

	return true;
}

$proxy_blocker_array = array(
	0 => $lang_module['proxy_blocker_0'],
	1 => $lang_module['proxy_blocker_1'],
	2 => $lang_module['proxy_blocker_2'],
	3 => $lang_module['proxy_blocker_3']
);

$captcha_array = array(
	0 => $lang_module['captcha_0'],
	1 => $lang_module['captcha_1'],
	2 => $lang_module['captcha_2'],
	3 => $lang_module['captcha_3'],
	4 => $lang_module['captcha_4'],
	5 => $lang_module['captcha_5'],
	6 => $lang_module['captcha_6'],
	7 => $lang_module['captcha_7']
);

$captcha_type_array = array( 0 => $lang_module['captcha_type_0'], 1 => $lang_module['captcha_type_1'] );

$errormess = '';
if( $nv_Request->isset_request( 'submitcaptcha', 'post' ) )
{
	$array_config_global = array();

	$proxy_blocker = $nv_Request->get_int( 'proxy_blocker', 'post' );
	if( isset( $proxy_blocker_array[$proxy_blocker] ) )
	{
		$array_config_global['proxy_blocker'] = $proxy_blocker;
	}

	$gfx_chk = $nv_Request->get_int( 'gfx_chk', 'post' );
	if( isset( $captcha_array[$gfx_chk] ) )
	{
		$array_config_global['gfx_chk'] = $gfx_chk;
	}
	$captcha_type = $nv_Request->get_int( 'captcha_type', 'post' );
	if( isset( $captcha_type_array[$captcha_type] ) )
	{
		$array_config_global['captcha_type'] = $captcha_type;
	}
	$array_config_global['str_referer_blocker'] = ( int )$nv_Request->get_bool( 'str_referer_blocker', 'post' );
	$array_config_global['is_flood_blocker'] = ( int )$nv_Request->get_bool( 'is_flood_blocker', 'post' );
	$array_config_global['max_requests_60'] = $nv_Request->get_int( 'max_requests_60', 'post' );
	$array_config_global['max_requests_300'] = $nv_Request->get_int( 'max_requests_300', 'post' );

	$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name" );
	foreach( $array_config_global as $config_name => $config_value )
	{
		$sth->bindParam( ':config_name', $config_name, PDO::PARAM_STR, 30 );
		$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR );
		$sth->execute();
	}

	$array_config_define = array();
	$array_config_define['nv_anti_agent'] = ( int )$nv_Request->get_bool( 'nv_anti_agent', 'post' );
	$array_config_define['nv_gfx_num'] = $nv_Request->get_int( 'nv_gfx_num', 'post' );
	$array_config_define['nv_gfx_width'] = $nv_Request->get_int( 'nv_gfx_width', 'post' );
	$array_config_define['nv_gfx_height'] = $nv_Request->get_int( 'nv_gfx_height', 'post' );
	$array_config_define['nv_anti_iframe'] = ( int )$nv_Request->get_bool( 'nv_anti_iframe', 'post' );
	$variable = $nv_Request->get_string( 'nv_allowed_html_tags', 'post' );
	$variable = str_replace( ';', ',', strtolower( $variable ) );
	$variable = explode( ',', $variable );
	$nv_allowed_html_tags = array();
	foreach( $variable as $value )
	{
		$value = trim( $value );
		if( preg_match( '/^[a-z0-9]+$/', $value ) and ! in_array( $value, $nv_allowed_html_tags) )
		{
			$nv_allowed_html_tags[] = $value;
		}
	}
	$array_config_define['nv_allowed_html_tags'] = implode( ', ', $nv_allowed_html_tags );

	$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'define' AND config_name = :config_name" );
	foreach( $array_config_define as $config_name => $config_value )
	{
		$sth->bindParam( ':config_name', $config_name, PDO::PARAM_STR, 30 );
		$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR );
		$sth->execute();
	}

	nv_save_file_config_global();
	if( empty( $errormess ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
		exit();
	}
}

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );

$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$error = array();
$contents = '';

$cid = $nv_Request->get_int( 'id', 'get' );
$del = $nv_Request->get_int( 'del', 'get' );

if( ! empty( $del ) and ! empty( $cid ) )
{
	$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_banip WHERE id=' . $cid );
	nv_save_file_banip();
}

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$cid = $nv_Request->get_int( 'cid', 'post', 0 );
	$ip = $nv_Request->get_title( 'ip', 'post', '', 1 );
	$area = $nv_Request->get_int( 'area', 'post', 0 );
	$mask = $nv_Request->get_int( 'mask', 'post', 0 );

	if( empty( $ip ) || ! $ips->nv_validip( $ip ) )
	{
		$error[] = $lang_module['banip_error_validip'];
	}

	if( empty( $area ) )
	{
		$error[] = $lang_module['banip_error_area'];
	}

	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'begintime', 'post' ), $m ) )
	{
		$begintime = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$begintime = NV_CURRENTTIME;
	}

	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'endtime', 'post' ), $m ) )
	{
		$endtime = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$endtime = 0;
	}

	$notice = $nv_Request->get_title( 'notice', 'post', '', 1 );

	if( empty( $error ) )
	{
		if( $cid > 0 )
		{
			$sth = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_banip
				SET ip= :ip, mask= :mask,area=' . $area . ', begintime=' . $begintime . ', endtime=' . $endtime . ', notice= :notice
				WHERE id=' . $cid );
			$sth->bindParam( ':ip', $ip, PDO::PARAM_STR );
			$sth->bindParam( ':mask', $mask, PDO::PARAM_STR );
			$sth->bindParam( ':notice', $notice, PDO::PARAM_STR );
			$sth->execute();
		}
		else
		{
			$sth = $db->prepare( 'REPLACE INTO ' . $db_config['prefix'] . '_banip ( ip, mask, area, begintime, endtime, notice) VALUES ( :ip, :mask, ' . $area . ', ' . $begintime . ', ' . $endtime . ', :notice )' );
			$sth->bindParam( ':ip', $ip, PDO::PARAM_STR );
			$sth->bindParam( ':mask', $mask, PDO::PARAM_STR );
			$sth->bindParam( ':notice', $notice, PDO::PARAM_STR );
			$sth->execute();
		}

		$save = nv_save_file_banip();

		if( $save !== true )
		{
			$xtpl->assign( 'MESSAGE', sprintf( $lang_module['banip_error_write'], NV_DATADIR, NV_DATADIR ) );
			$xtpl->assign( 'CODE', str_replace( array( '\n', '\t' ), array( "<br />", "&nbsp;&nbsp;&nbsp;&nbsp;" ), nv_htmlspecialchars( $save ) ) );
			$xtpl->parse( 'main.manual_save' );
		}
		else
		{
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
			die();
		}
	}
	else
	{
		$xtpl->assign( 'ERROR', implode( '<br/>', $error ) );
		$xtpl->parse( 'main.error' );
	}
}
else
{
	$id = $ip = $mask = $area = $begintime = $endtime = $notice = '';
}

$xtpl->assign( 'ANTI_AGENT', ( NV_ANTI_AGENT ) ? ' checked="checked"' : '' );
foreach( $proxy_blocker_array as $proxy_blocker_i => $proxy_blocker_v )
{
	$xtpl->assign( 'PROXYSELECTED', ( $global_config['proxy_blocker'] == $proxy_blocker_i ) ? ' selected="selected"' : '' );
	$xtpl->assign( 'PROXYOP', $proxy_blocker_i );
	$xtpl->assign( 'PROXYVALUE', $proxy_blocker_v );
	$xtpl->parse( 'main.proxy_blocker' );
}
$xtpl->assign( 'REFERER_BLOCKER', ( $global_config['str_referer_blocker'] ) ? ' checked="checked"' : '' );
$xtpl->assign( 'IS_FLOOD_BLOCKER', ( $global_config['is_flood_blocker'] ) ? ' checked="checked"' : '' );
$xtpl->assign( 'MAX_REQUESTS_60', $global_config['max_requests_60'] );
$xtpl->assign( 'MAX_REQUESTS_300', $global_config['max_requests_300'] );
$xtpl->assign( 'ANTI_IFRAME', ( NV_ANTI_IFRAME ) ? ' checked="checked"' : '' );

foreach( $captcha_array as $gfx_chk_i => $gfx_chk_lang )
{
	$array = array(
		"value" => $gfx_chk_i,
		"select" => ( $global_config['gfx_chk'] == $gfx_chk_i ) ? ' selected="selected"' : '',
		"text" => $gfx_chk_lang
	);
	$xtpl->assign( 'OPTION', $array );
	$xtpl->parse( 'main.opcaptcha' );
}

foreach( $captcha_type_array as $captcha_type_i => $captcha_type_lang )
{
	$array = array(
		'value' => $captcha_type_i,
		'select' => ( $global_config['captcha_type'] == $captcha_type_i ) ? ' selected="selected"' : '',
		'text' => $captcha_type_lang
	);
	$xtpl->assign( 'OPTION', $array );
	$xtpl->parse( 'main.captcha_type' );
}
for( $i = 2; $i < 10; $i++ )
{
	$array = array(
		'value' => $i,
		'select' => ( $i == NV_GFX_NUM ) ? ' selected="selected"' : '',
		'text' => $i
	);
	$xtpl->assign( 'OPTION', $array );
	$xtpl->parse( 'main.nv_gfx_num' );
}
$xtpl->assign( 'NV_GFX_WIDTH', NV_GFX_WIDTH );
$xtpl->assign( 'NV_GFX_HEIGHT', NV_GFX_HEIGHT );
$xtpl->assign( 'NV_ALLOWED_HTML_TAGS', NV_ALLOWED_HTML_TAGS );

$mask_text_array = array();
$mask_text_array[0] = '255.255.255.255';
$mask_text_array[3] = '255.255.255.xxx';
$mask_text_array[2] = '255.255.xxx.xxx';
$mask_text_array[1] = '255.xxx.xxx.xxx';

$banip_area_array = array();
$banip_area_array[0] = $lang_module['banip_area_select'];
$banip_area_array[1] = $lang_module['banip_area_front'];
$banip_area_array[2] = $lang_module['banip_area_admin'];
$banip_area_array[3] = $lang_module['banip_area_both'];

$sql = 'SELECT id, ip, mask, area, begintime, endtime FROM ' . $db_config['prefix'] . '_banip ORDER BY ip DESC';
$result = $db->query( $sql );
$i = 0;
while( list( $dbid, $dbip, $dbmask, $dbarea, $dbbegintime, $dbendtime ) = $result->fetch( 3 ) )
{
	++$i;
	$xtpl->assign( 'ROW', array(
		'dbip' => $dbip,
		'dbmask' => $mask_text_array[$dbmask],
		'dbarea' => $banip_area_array[$dbarea],
		'dbbegintime' => ! empty( $dbbegintime ) ? date( 'd/m/Y', $dbbegintime ) : '',
		'dbendtime' => ! empty( $dbendtime ) ? date( 'd/m/Y', $dbendtime ) : $lang_module['banip_nolimit'],
		'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $dbid,
		'url_delete' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;del=1&amp;id=' . $dbid
	) );

	$xtpl->parse( 'main.listip.loop' );
}
if( $i )
{
	$xtpl->parse( 'main.listip' );
}

if( ! empty( $cid ) )
{
	list( $id, $ip, $mask, $area, $begintime, $endtime, $notice ) = $db->query( 'SELECT id, ip, mask, area, begintime, endtime, notice FROM ' . $db_config['prefix'] . '_banip WHERE id=' . $cid )->fetch( 3 );
	$lang_module['banip_add'] = $lang_module['banip_edit'];
}

$xtpl->assign( 'MASK_TEXT_ARRAY', $mask_text_array );
$xtpl->assign( 'BANIP_AREA_ARRAY', $banip_area_array );
$xtpl->assign( 'BANIP_TITLE', ( $cid ) ? $lang_module['banip_title_edit'] : $lang_module['banip_title_add'] );

$xtpl->assign( 'DATA', array(
	'cid' => $cid,
	'ip' => $ip,
	'selected3' => ( $mask == 3 ) ? ' selected="selected"' : '',
	'selected2' => ( $mask == 2 ) ? ' selected="selected"' : '',
	'selected1' => ( $mask == 1 ) ? ' selected="selected"' : '',
	'selected_area_1' => ( $area == 1 ) ? ' selected="selected"' : '',
	'selected_area_2' => ( $area == 2 ) ? ' selected="selected"' : '',
	'selected_area_3' => ( $area == 3 ) ? ' selected="selected"' : '',
	'begintime' => ! empty( $begintime ) ? date( 'd/m/Y', $begintime ) : '',
	'endtime' => ! empty( $endtime ) ? date( 'd/m/Y', $endtime ) : '',
	'endtime' => $notice
) );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['security'];
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';