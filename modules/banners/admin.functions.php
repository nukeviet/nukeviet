<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

/**
 * nv_CreateXML_bannerPlan()
 * 
 * @return
 */
function nv_CreateXML_bannerPlan()
{
	global $db, $global_config;

	$files = nv_scandir( NV_ROOTDIR . '/' . NV_DATADIR, "/^bpl\_([0-9]+)\.xml$/" );
	if( ! empty( $files ) )
	{
		foreach( $files as $file )
		{
			nv_deletefile( NV_ROOTDIR . '/' . NV_DATADIR . '/' . $file );
		}
	}

	include ( NV_ROOTDIR . '/includes/class/array2xml.class.php' );

	$sql = "SELECT * FROM `" . NV_BANNERS_PLANS_GLOBALTABLE . "` WHERE `act` = 1";
	$result = $db->sql_query( $sql );
	
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$id = intval( $row['id'] );

		$xmlfile = NV_ROOTDIR . '/' . NV_DATADIR . '/bpl_' . $id . '.xml';

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

		$query2 = "SELECT * FROM `" . NV_BANNERS_ROWS_GLOBALTABLE . "` WHERE `pid` = " . $id . " AND (`exp_time` > " . NV_CURRENTTIME . " OR `exp_time` = 0 ) AND `act` = 1";
		
		if( $row['form'] == "sequential" )
		{
			$query2 .= " ORDER BY `weight` ASC";
		}
		
		$result2 = $db->sql_query( $query2 );
		$numrows2 = $db->sql_numrows( $result2 );
		
		if( empty( $numrows2 ) )
		{
			continue;
		}
		
		$plan['banners'] = array();
		
		while( $row2 = $db->sql_fetchrow( $result2 ) )
		{
			$plan['banners'][] = array(
				'id' => $row2['id'], //
				'title' => $row2['title'], //
				'clid' => $row2['clid'], //
				'file_name' => $row2['file_name'], //
				'file_ext' => $row2['file_ext'], //
				'file_mime' => $row2['file_mime'], //
				'file_width' => $row2['width'], //
				'file_height' => $row2['height'], //
				'file_alt' => $row2['file_alt'], //
				'file_click' => $row2['click_url'] //
			);
		}

		$array2XML = new Array2XML();
		$array2XML->saveXML( $plan, 'plan', $xmlfile, $encoding = $global_config['site_charset'] );
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
	
	list( $pid, $form ) = $db->sql_fetchrow( $db->sql_query( "SELECT `id`, `form` FROM `" . NV_BANNERS_PLANS_GLOBALTABLE . "` WHERE `id`=" . intval( $pid ) . "" ) );
	
	if( $pid > 0 and $form == "sequential" )
	{
		$query_weight = "SELECT `id` FROM `" . NV_BANNERS_ROWS_GLOBALTABLE . "` WHERE  `pid`=" . $pid . " ORDER BY `weight` ASC, `id` DESC";
		$result = $db->sql_query( $query_weight );
		
		$weight = 0;
		while( $row = $db->sql_fetchrow( $result ) )
		{
			++$weight;
			$sql = "UPDATE `" . NV_BANNERS_ROWS_GLOBALTABLE . "` SET `weight`=" . $weight . " WHERE `id`=" . $row['id'];
			$db->sql_query( $sql );
		}
	}
	elseif( $pid > 0 and $form == "random" )
	{
		$sql = "UPDATE `" . NV_BANNERS_ROWS_GLOBALTABLE . "` SET `weight`='0' WHERE `pid`=" . $pid;
		$db->sql_query( $sql );
	}
}

$submenu['client_list'] = $lang_module['client_list'];
$submenu['add_client'] = $lang_module['add_client'];
$submenu['plans_list'] = $lang_module['plans_list'];
$submenu['add_plan'] = $lang_module['add_plan'];
$submenu['banners_list'] = $lang_module['banners_list'];
$submenu['add_banner'] = $lang_module['add_banner'];

$allow_func = array( 'main', 'client_list', 'cl_list', 'add_client', 'edit_client', 'del_client', 'change_act_client', 'info_client', 'info_cl', 'plans_list','plist', 'change_act_plan', 'add_plan', 'edit_plan', 'del_plan', 'info_plan', 'info_pl', 'banners_list', 'add_banner', 'edit_banner', 'b_list','change_act_banner', 'info_banner', 'show_stat', 'show_list_stat', 'del_banner' );

define( 'NV_IS_FILE_ADMIN', true );

/**
 * nv_add_client_theme()
 * 
 * @param mixed $contents
 * @return
 */
function nv_add_client_theme( $contents )
{
	global $global_config, $module_file;
	
	$xtpl = new XTemplate( "add_client.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'CLASS', $contents['is_error'] ? " class=\"error\"" : "" );
	
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
	
	$xtpl = new XTemplate( "edit_client.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'CLASS', $contents['is_error'] ? " class=\"error\"" : "" );
	
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
	
	$xtpl = new XTemplate( "client_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
	
	$xtpl = new XTemplate( "cl_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
			$values['class'] = ( $a ++ % 2 ) ? " class=\"second\"" : "";
			$values['checked'] = $values['act'][1] ? " checked=\"checked\"" : "";
			
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
	
	$xtpl = new XTemplate( "info_client.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
	
	$xtpl = new XTemplate( "info_cl.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );

	$a = 0;
	foreach( $contents['rows'] as $row )
	{		
		$row['class'] = ( $a ++ % 2 ) ? " class=\"second\"" : "";
		
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
	
	$xtpl = new XTemplate( "banners_client.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
	
	$xtpl = new XTemplate( "add_plan.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'CLASS', $contents['is_error'] ? " class=\"error\"" : "" );

	foreach( $contents['blang'][3] as $key => $blang )
	{
		$xtpl->assign( 'BLANG', array( 'key' => $key, 'title' => $blang['name'], 'selected' => $key == $contents['blang'][4] ? " selected=\"selected\"" : "" ) );
		$xtpl->parse( 'main.blang' );
	}

	foreach( $contents['form'][2] as $form )
	{
		$xtpl->assign( 'FORM', array( 'key' => $form, 'title' => $form, 'selected' => $form == $contents['form'][3] ? " selected=\"selected\"" : "" ) );
		$xtpl->parse( 'main.form' );
	}
	

	if( $contents['description'][5] and nv_function_exists( 'nv_aleditor' ) )
	{
		$description = nv_aleditor( $contents['description'][1], $contents['description'][3], $contents['description'][4], $contents['description'][2] );
	}
	else
	{
		$description = "<textarea name=\"" . $contents['description'][1] . "\" id=\"" . $contents['description'][1] . "\" style=\"width:" . $contents['description'][3] . ";height:" . $contents['description'][4] . "\">" . $contents['description'][2] . "</textarea>\n";
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
	
	$xtpl = new XTemplate( "edit_plan.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'CLASS', $contents['is_error'] ? " class=\"error\"" : "" );

	foreach( $contents['blang'][3] as $key => $blang )
	{
		$xtpl->assign( 'BLANG', array( 'key' => $key, 'title' => $blang['name'], 'selected' => $key == $contents['blang'][4] ? " selected=\"selected\"" : "" ) );
		$xtpl->parse( 'main.blang' );
	}

	foreach( $contents['form'][2] as $form )
	{
		$xtpl->assign( 'FORM', array( 'key' => $form, 'title' => $form, 'selected' => $form == $contents['form'][3] ? " selected=\"selected\"" : "" ) );
		$xtpl->parse( 'main.form' );
	}
	
	if( $contents['description'][5] and nv_function_exists( 'nv_aleditor' ) )
	{
		$description = nv_aleditor( $contents['description'][1], $contents['description'][3], $contents['description'][4], $contents['description'][2] );
	}
	else
	{
		$description = "<textarea name=\"" . $contents['description'][1] . "\" id=\"" . $contents['description'][1] . "\" style=\"width:" . $contents['description'][3] . ";height:" . $contents['description'][4] . "\">" . $contents['description'][2] . "</textarea>\n";
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
	
	$xtpl = new XTemplate( "plans_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
	
	$xtpl = new XTemplate( "plist.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
			$values['class'] = ( $a ++ % 2 ) ? " class=\"second\"" : "";
			$values['checked'] = $values['act'][1] ? " checked=\"checked\"" : "";
			
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
	
	$xtpl = new XTemplate( "info_plan.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
	
	$xtpl = new XTemplate( "info_pl.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );

	$a = 0;
	foreach( $contents['rows'] as $key => $row )
	{
		$row['class'] = ( $a ++ % 2 ) ? " class=\"second\"" : "";
		
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
	
	$xtpl = new XTemplate( "add_banner.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );

	if( ! empty( $contents['upload_blocked'] ) )
	{
		$xtpl->parse( 'upload_blocked' );
		return $xtpl->text( 'upload_blocked' );
	}
	
	$xtpl->assign( 'CLASS', $contents['is_error'] ? " class=\"error\"" : "" );

	foreach( $contents['plan'][2] as $pid => $ptitle )
	{
		$xtpl->assign( 'PLAN', array( 'key' => $pid, 'title' => $ptitle, 'selected' => $pid == $contents['plan'][3] ? " selected=\"selected\"" : "" ) );
		$xtpl->parse( 'main.plan' );
	}

	foreach( $contents['client'][2] as $clid => $clname )
	{
		$xtpl->assign( 'CLIENT', array( 'key' => $clid, 'title' => $clname, 'selected' => $clid == $contents['client'][3] ? " selected=\"selected\"" : "" ) );
		$xtpl->parse( 'main.client' );
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
	
	$xtpl = new XTemplate( "edit_banner.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );

	if( ! empty( $contents['upload_blocked'] ) )
	{
		$xtpl->parse( 'upload_blocked' );
		return $xtpl->text( 'upload_blocked' );
	}
	
	$xtpl->assign( 'CLASS', $contents['is_error'] ? " class=\"error\"" : "" );

	foreach( $contents['plan'][2] as $pid => $ptitle )
	{
		$xtpl->assign( 'PLAN', array( 'key' => $pid, 'title' => $ptitle, 'selected' => $pid == $contents['plan'][3] ? " selected=\"selected\"" : "" ) );
		$xtpl->parse( 'main.plan' );
	}

	foreach( $contents['client'][2] as $clid => $clname )
	{
		$xtpl->assign( 'CLIENT', array( 'key' => $clid, 'title' => $clname, 'selected' => $clid == $contents['client'][3] ? " selected=\"selected\"" : "" ) );
		$xtpl->parse( 'main.client' );
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
	
	$xtpl = new XTemplate( "banners_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
	
	$xtpl = new XTemplate( "b_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
			$values['class'] = ( $a ++ % 2 ) ? " class=\"second\"" : "";
			$values['delfile'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=del_banner&amp;id=" . $b_id;
			$values['checked'] = $values['act'][1] == '1' ? " checked=\"checked\"" : "";
			
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
	
	$xtpl = new XTemplate( "info_b.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
		$row['class'] = ( $a ++ % 2 ) ? " class=\"second\"" : "";
		
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
	
	$xtpl = new XTemplate( "show_stat.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'CONTENTS', $contents );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'MODULE_NAME', $module_name );

	if( ! empty( $contents[2] ) )
	{
		$a = 0;
		foreach( $contents[2] as $key => $value )
		{
			$value['class'] = ( $a ++ % 2 ) ? " class=\"second\"" : "";
			
			$xtpl->assign( 'KEY', $key );
			$xtpl->assign( 'ROW', $value );
			
			if( ! preg_match( "/^[0-9]+$/", $key ) )
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
	
	$xtpl = new XTemplate( "show_list_stat.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
		$row['class'] = ( $a ++ % 2 ) ? " class=\"second\"" : "";
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
	
	$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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

?>