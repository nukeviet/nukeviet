<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if( !defined( 'NV_SYSTEM' ) )
{
	die( 'Stop!!!' );
}

if( !in_array( $op, array(
	'detail',
	'result'
) ) )
{
	define( 'NV_IS_MOD_VOTING', true );
}

if( !empty( $array_op ) )
{
	unset( $matches );
	if( preg_match( "/^result\-([0-9]+)$/", $array_op[0], $matches ) )
	{
		$id = ( int )$matches[1];
		$op = "result";
	}
}
/**
 * nv_view_voting()
 *
 * @return
 */
function nv_view_voting($current_voting,$xtpl,$module_name )
{
	global $lang_global,$global_config,$lang_module,$site_mods,$nv_Cache;
	$voting_array = array(
		'checkss' => md5( $current_voting['vid'] . NV_CHECK_SESSION ),
		'accept' => (int)$current_voting['acceptcm'],
		'active_captcha' => ((int)$current_voting['active_captcha'] ? ($global_config['captcha_type'] == 2 ? 2 : 1) : 0),
		'errsm' => (int)$current_voting['acceptcm'] > 1 ? sprintf( $lang_module['voting_warning_all'], (int)$current_voting['acceptcm'] ) : $lang_module['voting_warning_accept1'],
		'vid' => $current_voting['vid'],
		'question' => (empty( $current_voting['link'] )) ? $current_voting['question'] : '<a target="_blank" href="' . $current_voting['link'] . '">' . $current_voting['question'] . '</a>',
		'action' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name,
		'langresult' => $lang_module['voting_result'],
		'langsubmit' => $lang_module['voting_hits'],
		'publtime' => nv_date( 'l - d/m/Y H:i', $current_voting['publ_time'] )
	);
	$xtpl->assign( 'VOTING', $voting_array );

	$sql = 'SELECT id, vid, title, url FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module_name]['module_data'] . '_rows WHERE vid = ' . $current_voting['vid'] . ' ORDER BY id ASC';
	$list = $nv_Cache->db( $sql, '', $module_name );
	foreach( $list as $row )
	{
		if( !empty( $row['url'] ) )
		{
			$row['title'] = '<a target="_blank" href="' . $row['url'] . '">' . $row['title'] . '</a>';
		}
		$xtpl->assign( 'RESULT', $row );
		if( (int)$current_voting['acceptcm'] > 1 )
		{
			$xtpl->parse( 'main.loop.resultn' );
		}
		else
		{
			$xtpl->parse( 'main.loop.result1' );
		}
	}

	if( $voting_array['active_captcha'] )
	{
		if( $global_config['captcha_type'] == 2 )
		{
			$xtpl->assign( 'RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass( 8 ) );
			$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode1'] );
			$xtpl->parse( 'main.loop.has_captcha.recaptcha' );
		}
		else
		{
			$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
			$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
			$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
			$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
			$xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha&t=' . NV_CURRENTTIME );
			$xtpl->assign( 'GFX_MAXLENGTH', NV_GFX_NUM );
			$xtpl->parse( 'main.loop.has_captcha.basic' );
		}
		$xtpl->parse( 'main.loop.has_captcha' );
	}
	$xtpl->parse( 'main.loop' );

}
