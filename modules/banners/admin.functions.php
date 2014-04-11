<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$allow_func = array( 'main', 'client_list', 'cl_list', 'add_client', 'edit_client', 'del_client', 'change_act_client', 'info_client', 'info_cl', 'plans_list', 'plist', 'change_act_plan', 'add_plan', 'edit_plan', 'del_plan', 'info_plan', 'info_pl', 'banners_list', 'add_banner', 'edit_banner', 'b_list', 'change_act_banner', 'info_banner', 'show_stat', 'show_list_stat', 'del_banner' );

define( 'NV_IS_FILE_ADMIN', true );

$targets = array(
	'_blank' => $lang_module['target_blank'],
	'_top' => $lang_module['target_top'],
	'_self' => $lang_module['target_self'],
	'_parent' => $lang_module['target_parent']
);

/**
 * nv_CreateXML_bannerPlan()
 *
 * @return
 */
function nv_CreateXML_bannerPlan()
{
	global $db, $global_config;

	$pattern = ( $global_config['idsite'] ) ? '/^site\_' . $global_config['idsite'] . '\_bpl\_([0-9]+)\.xml$/' : '/^bpl\_([0-9]+)\.xml$/';
	$files = nv_scandir( NV_ROOTDIR . '/' . NV_DATADIR, $pattern );
	if( ! empty( $files ) )
	{
		foreach( $files as $file )
		{
			nv_deletefile( NV_ROOTDIR . '/' . NV_DATADIR . '/' . $file );
		}
	}

	include NV_ROOTDIR . '/includes/class/array2xml.class.php' ;

	$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE. '_plans WHERE act = 1';
	$result = $db->query( $sql );

	while( $row = $result->fetch() )
	{
		$id = intval( $row['id'] );
		if( $global_config['idsite'] )
		{
			$xmlfile = NV_ROOTDIR . '/' . NV_DATADIR . '/site_' . $global_config['idsite'] . '_bpl_' . $id . '.xml';
		}
		else
		{
			$xmlfile = NV_ROOTDIR . '/' . NV_DATADIR . '/bpl_' . $id . '.xml';
		}

		$plan = array();
		$plan['id'] = $id;
		$plan['lang'] = $row['blang'];
		$plan['title'] = $row['title'];

		if( ! empty( $row['description'] ) )
		{
			$plan['description'] = $row['description'];
		}

		$plan['form'] = $row['form'];
		$plan['width'] = $row['width'];
		$plan['height'] = $row['height'];

		$query2 = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE. '_rows WHERE pid = ' . $id . ' AND (exp_time > ' . NV_CURRENTTIME . ' OR exp_time = 0 ) AND act = 1';

		if( $row['form'] == 'sequential' )
		{
			$query2 .= ' ORDER BY weight ASC';
		}

		$plan['banners'] = array();

		$result2 = $db->query( $query2 );
		while( $row2 = $result2->fetch() )
		{
			$plan['banners'][] = array(
				'id' => $row2['id'],
				'title' => $row2['title'],
				'clid' => $row2['clid'],
				'file_name' => $row2['file_name'],
				'imageforswf' => $row2['imageforswf'],
				'file_ext' => $row2['file_ext'],
				'file_mime' => $row2['file_mime'],
				'file_width' => $row2['width'],
				'file_height' => $row2['height'],
				'file_alt' => $row2['file_alt'],
				'file_click' => $row2['click_url'],
				'target' => $row2['target'],
				'publ_time' => $row2['publ_time'],
				'exp_time' => $row2['exp_time']
			);
		}
		if( sizeof( $plan['banners'] ) )
		{
			$array2XML = new Array2XML();
			$array2XML->saveXML( $plan, 'plan', $xmlfile, $encoding = $global_config['site_charset'] );
		}
	}
}

/**
 * nv_fix_banner_weight()
 *
 * @param mixed $pid
 * @return
 */
function nv_fix_banner_weight( $pid )
{
	global $db, $global_config;

	list( $pid, $form ) = $db->query( 'SELECT id, form FROM ' . NV_BANNERS_GLOBALTABLE. '_plans WHERE id=' . intval( $pid ) )->fetch( 3 );

	if( $pid > 0 and $form == 'sequential' )
	{
		$query_weight = 'SELECT id FROM ' . NV_BANNERS_GLOBALTABLE. '_rows WHERE pid=' . $pid . ' ORDER BY weight ASC, id DESC';
		$result = $db->query( $query_weight );

		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			$sql = 'UPDATE ' . NV_BANNERS_GLOBALTABLE. '_rows SET weight=' . $weight . ' WHERE id=' . $row['id'];
			$db->query( $sql );
		}
	}
	elseif( $pid > 0 and $form == 'random' )
	{
		$sql = 'UPDATE ' . NV_BANNERS_GLOBALTABLE. '_rows SET weight=0 WHERE pid=' . $pid;
		$db->query( $sql );
	}
}

/**
 * nv_add_client_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_add_client_theme( $contents )
{
	global $global_config, $module_file;

	$xtpl = new XTemplate( 'add_client.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'CLASS', $contents['is_error'] ? ' class="error"' : '' );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_edit_client_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_edit_client_theme( $contents )
{
	global $global_config, $module_file;

	$xtpl = new XTemplate( 'edit_client.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'CLASS', $contents['is_error'] ? ' class="error"' : '' );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_client_list_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_client_list_theme( $contents )
{
	global $global_config, $module_file;

	$xtpl = new XTemplate( 'client_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_cl_list_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_cl_list_theme( $contents )
{
	global $global_config, $module_file;

	$xtpl = new XTemplate( 'cl_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );

	foreach( $contents['thead'] as $key => $thead )
	{
		$xtpl->assign( 'THEAD', $thead );
		$xtpl->parse( 'main.thead' );
	}

	$a = 0;
	if( ! empty( $contents['rows'] ) )
	{
		foreach( $contents['rows'] as $cl_id => $values )
		{
			$values['checked'] = $values['act'][1] ? ' checked="checked"' : '';

			$xtpl->assign( 'ROW', $values );
			$xtpl->parse( 'main.loop' );
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_info_client_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_info_client_theme( $contents )
{
	global $global_config, $module_file;

	$xtpl = new XTemplate( 'info_client.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_info_cl_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_info_cl_theme( $contents )
{
	global $global_config, $module_file;

	$xtpl = new XTemplate( 'info_cl.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );

	$a = 0;
	foreach( $contents['rows'] as $row )
	{
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.loop' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_banners_client_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_banners_client_theme( $contents )
{
	global $global_config, $module_file;

	$xtpl = new XTemplate( 'banners_client.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );

	if( ! empty( $contents['info'] ) )
	{
		$xtpl->parse( 'main.info' );
	}
	else
	{
		$xtpl->parse( 'main.empty' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_add_plan_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_add_plan_theme( $contents )
{
	global $global_config, $module_file;

	$xtpl = new XTemplate( 'add_plan.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'CLASS', $contents['is_error'] ? ' class="error"' : '' );

	foreach( $contents['blang'][3] as $key => $blang )
	{
		$xtpl->assign( 'BLANG', array(
			'key' => $key,
			'title' => $blang['name'],
			'selected' => $key == $contents['blang'][4] ? ' selected="selected"' : ''
		) );
		$xtpl->parse( 'main.blang' );
	}

	foreach( $contents['form'][2] as $form )
	{
		$xtpl->assign( 'FORM', array(
			'key' => $form,
			'title' => $form,
			'selected' => $form == $contents['form'][3] ? ' selected="selected"' : ''
		) );
		$xtpl->parse( 'main.form' );
	}

	if( $contents['description'][5] and nv_function_exists( 'nv_aleditor' ) )
	{
		$description = nv_aleditor( $contents['description'][1], $contents['description'][3], $contents['description'][4], $contents['description'][2] );
	}
	else
	{
		$description = '<textarea name="' . $contents['description'][1] . '" id="' . $contents['description'][1] . '" style="width:' . $contents['description'][3] . ';height:' . $contents['description'][4] . '">' . $contents['description'][2] . '</textarea>\n';
	}

	$xtpl->assign( 'DESCRIPTION', $description );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_edit_plan_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_edit_plan_theme( $contents )
{
	global $global_config, $module_file;

	$xtpl = new XTemplate( 'edit_plan.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'CLASS', $contents['is_error'] ? ' class="error"' : '' );

	foreach( $contents['blang'][3] as $key => $blang )
	{
		$xtpl->assign( 'BLANG', array(
			'key' => $key,
			'title' => $blang['name'],
			'selected' => $key == $contents['blang'][4] ? ' selected="selected"' : ''
		) );
		$xtpl->parse( 'main.blang' );
	}

	foreach( $contents['form'][2] as $form )
	{
		$xtpl->assign( 'FORM', array(
			'key' => $form,
			'title' => $form,
			'selected' => $form == $contents['form'][3] ? ' selected="selected"' : ''
		) );
		$xtpl->parse( 'main.form' );
	}

	if( $contents['description'][5] and nv_function_exists( 'nv_aleditor' ) )
	{
		$description = nv_aleditor( $contents['description'][1], $contents['description'][3], $contents['description'][4], $contents['description'][2] );
	}
	else
	{
		$description = '<textarea name="' . $contents['description'][1] . '" id="' . $contents['description'][1] . '" style="width:' . $contents['description'][3] . ';height:' . $contents['description'][4] . '">' . $contents['description'][2] . '</textarea>\n';
	}

	$xtpl->assign( 'DESCRIPTION', $description );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_plans_list_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_plans_list_theme( $contents )
{
	global $global_config, $module_file;

	$xtpl = new XTemplate( 'plans_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_plist_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_plist_theme( $contents )
{
	global $global_config, $module_file;

	$xtpl = new XTemplate( 'plist.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );

	foreach( $contents['thead'] as $key => $thead )
	{
		$xtpl->assign( 'THEAD', $thead );
		$xtpl->parse( 'main.thead' );
	}

	$a = 0;
	if( ! empty( $contents['rows'] ) )
	{
		foreach( $contents['rows'] as $pl_id => $values )
		{
			$values['checked'] = $values['act'][1] ? ' checked="checked"' : '';

			$xtpl->assign( 'ROW', $values );
			$xtpl->parse( 'main.loop' );
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_info_plan_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_info_plan_theme( $contents )
{
	global $global_config, $module_file;

	$xtpl = new XTemplate( 'info_plan.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_info_pl_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_info_pl_theme( $contents )
{
	global $global_config, $module_file;

	$xtpl = new XTemplate( 'info_pl.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );

	$a = 0;
	foreach( $contents['rows'] as $key => $row )
	{
		$xtpl->assign( 'ROW', $row );

		if( $key != 'description' )
		{
			$xtpl->parse( 'main.loop.t1' );
		}
		else
		{
			$xtpl->parse( 'main.loop.t2' );
		}

		$xtpl->parse( 'main.loop' );
	}

	if( isset( $contents['rows']['description'] ) )
	{
		$xtpl->parse( 'main.description' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_add_banner_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_add_banner_theme( $contents )
{
	global $global_config, $module_file;

	$xtpl = new XTemplate( 'add_banner.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );

	if( ! empty( $contents['upload_blocked'] ) )
	{
		$xtpl->parse( 'upload_blocked' );
		return $xtpl->text( 'upload_blocked' );
	}

	$xtpl->assign( 'CLASS', $contents['is_error'] ? ' class="error"' : '' );

	foreach( $contents['plan'][2] as $pid => $ptitle )
	{
		$xtpl->assign( 'PLAN', array(
			'key' => $pid,
			'title' => $ptitle,
			'selected' => $pid == $contents['plan'][3] ? ' selected="selected"' : ''
		) );
		$xtpl->parse( 'main.plan' );
	}

	foreach( $contents['client'][2] as $clid => $clname )
	{
		$xtpl->assign( 'CLIENT', array(
			'key' => $clid,
			'title' => $clname,
			'selected' => $clid == $contents['client'][3] ? ' selected="selected"' : ''
		) );
		$xtpl->parse( 'main.client' );
	}
	foreach( $contents['target'][2] as $target => $ptitle )
	{
		$xtpl->assign( 'TARGET', array(
			'key' => $target,
			'title' => $ptitle,
			'selected' => $target == $contents['target'][3] ? ' selected="selected"' : ''
		) );
		$xtpl->parse( 'main.target' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_edit_banner_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_edit_banner_theme( $contents )
{
	global $global_config, $module_file;

	$xtpl = new XTemplate( 'edit_banner.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );

	if( ! empty( $contents['upload_blocked'] ) )
	{
		$xtpl->parse( 'upload_blocked' );
		return $xtpl->text( 'upload_blocked' );
	}

	$xtpl->assign( 'CLASS', $contents['is_error'] ? ' class="error"' : '' );

	foreach( $contents['plan'][2] as $pid => $ptitle )
	{
		$xtpl->assign( 'PLAN', array(
			'key' => $pid,
			'title' => $ptitle,
			'selected' => $pid == $contents['plan'][3] ? ' selected="selected"' : ''
		) );
		$xtpl->parse( 'main.plan' );
	}

	foreach( $contents['client'][2] as $clid => $clname )
	{
		$xtpl->assign( 'CLIENT', array(
			'key' => $clid,
			'title' => $clname,
			'selected' => $clid == $contents['client'][3] ? ' selected="selected"' : ''
		) );
		$xtpl->parse( 'main.client' );
	}

	foreach( $contents['target'][2] as $target => $ptitle )
	{
		$xtpl->assign( 'TARGET', array(
			'key' => $target,
			'title' => $ptitle,
			'selected' => $target == $contents['target'][3] ? ' selected="selected"' : ''
		) );
		$xtpl->parse( 'main.target' );
	}

	if( ! empty( $contents['file_name'][5] ) )
	{
		$xtpl->parse( 'main.imageforswf1' );
	}
	if( substr( $contents['file_name'][1], -3 ) == 'swf' )
	{
		$xtpl->parse( 'main.imageforswf2' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_banners_list_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_banners_list_theme( $contents )
{
	global $global_config, $module_file;

	$xtpl = new XTemplate( 'banners_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_b_list_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_b_list_theme( $contents )
{
	global $global_config, $module_file, $lang_module, $module_name;

	$xtpl = new XTemplate( 'b_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'MODULE_NAME', $module_name );

	if( defined( 'NV_BANNER_WEIGHT' ) )
	{
		$xtpl->parse( 'main.nv_banner_weight' );
	}

	foreach( $contents['thead'] as $key => $thead )
	{
		$xtpl->assign( 'THEAD', $thead );
		$xtpl->parse( 'main.thead' );
	}

	$a = 0;
	if( ! empty( $contents['rows'] ) )
	{
		foreach( $contents['rows'] as $b_id => $values )
		{
			$values['delfile'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=del_banner&amp;id=' . $b_id;
			$values['checked'] = $values['act'][1] == '1' ? ' checked="checked"' : '';

			$xtpl->assign( 'ROW', $values );

			if( defined( 'NV_BANNER_WEIGHT' ) ) $xtpl->parse( 'main.loop.nv_banner_weight' );

			if( ! empty( $values['clid'] ) )
			{
				$xtpl->parse( 'main.loop.t1' );
			}
			else
			{
				$xtpl->parse( 'main.loop.t2' );
			}

			$xtpl->parse( 'main.loop' );
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_info_b_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_info_b_theme( $contents )
{
	global $global_config, $module_file, $lang_module, $module_name;

	$xtpl = new XTemplate( 'info_b.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'MODULE_NAME', $module_name );

	if( isset( $contents['act'] ) )
	{
		$xtpl->parse( 'main.act' );
	}

	$a = 0;
	foreach( $contents['rows'] as $row )
	{
		$xtpl->assign( 'ROW1', $row );
		$xtpl->parse( 'main.loop1' );
	}

	foreach( $contents['stat'][3] as $k => $v )
	{
		$xtpl->assign( 'K', $k );
		$xtpl->assign( 'V', $v );
		$xtpl->parse( 'main.stat1' );
	}

	foreach( $contents['stat'][5] as $k => $v )
	{
		$xtpl->assign( 'K', $k );
		$xtpl->assign( 'V', $v );
		$xtpl->parse( 'main.stat2' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_show_stat_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_show_stat_theme( $contents )
{
	global $global_config, $module_file, $lang_module, $module_name;

	$xtpl = new XTemplate( 'show_stat.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'MODULE_NAME', $module_name );

	if( ! empty( $contents[2] ) )
	{
		$a = 0;
		foreach( $contents[2] as $key => $value )
		{
			$xtpl->assign( 'KEY', $key );
			$xtpl->assign( 'ROW', $value );

			if( ! preg_match( '/^[0-9]+$/', $key ) )
			{
				$xtpl->parse( 'main.loop.t1' );
			}
			else
			{
				$xtpl->parse( 'main.loop.t2' );
			}

			if( ! empty( $value[1] ) )
			{
				$xtpl->assign( 'WIDTH', $value[1] * 3 );
				$xtpl->parse( 'main.loop.t3' );
			}

			$xtpl->parse( 'main.loop' );
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_show_list_stat_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_show_list_stat_theme( $contents )
{
	global $global_config, $module_file, $lang_module, $module_name;

	$xtpl = new XTemplate( 'show_list_stat.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'MODULE_NAME', $module_name );

	foreach( $contents['thead'] as $key => $thead )
	{
		$xtpl->assign( 'THEAD', $thead );
		$xtpl->parse( 'main.thead' );
	}

	$a = 0;
	foreach( $contents['rows'] as $row )
	{
		$xtpl->assign( 'ROW', $row );

		foreach( $row as $r )
		{
			$xtpl->assign( 'R', $r );
			$xtpl->parse( 'main.loop.r' );
		}

		$xtpl->parse( 'main.loop' );
	}

	if( ! empty( $contents['generate_page'] ) )
	{
		$xtpl->parse( 'main.generate_page' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_main_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_main_theme( $contents )
{
	global $global_config, $module_file;

	$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	foreach( $contents['containerid'] as $containerid )
	{
		$xtpl->assign( 'CONTAINERID', $containerid );
		$xtpl->parse( 'main.loop1' );
	}

	foreach( $contents['aj'] as $aj )
	{
		$xtpl->assign( 'AJ', $aj );
		$xtpl->parse( 'main.loop2' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}