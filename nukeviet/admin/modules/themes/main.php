<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$page_title = $lang_module['theme_manager'];

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

$theme_list = nv_scandir( NV_ROOTDIR . "/themes/", $global_config['check_theme'] );
$theme_mobile_list = nv_scandir( NV_ROOTDIR . "/themes/", $global_config['check_theme_mobile'] );
$theme_list = array_merge( $theme_list, $theme_mobile_list );

$i = 1;
$number_theme = sizeof( $theme_list );

$errorconfig = array();

foreach( $theme_list as $value )
{
	// Load thumbnail image
	if( ! $xml = @simplexml_load_file( NV_ROOTDIR . '/themes/' . $value . '/config.ini' ) )
	{
		$errorconfig[] = $value;
		continue;
	}

	$info = $xml->xpath( 'info' );

	if( $global_config['site_theme'] == $value )
	{
		$xtpl->parse( 'main.loop.active' );
	}
	else
	{
		$xtpl->parse( 'main.loop.deactive' );
	}

	$xtpl->assign( 'ROW', array(
		'name' => ( string )$info[0]->name,
		'website' => ( string )$info[0]->website,
		'author' => ( string )$info[0]->author,
		'thumbnail' => ( string )$info[0]->thumbnail,
		'description' => ( string )$info[0]->description,
		'value' => $value
	) );

	$position = $xml->xpath( 'positions' );
	$positions = $position[0]->position;
	$pos = array();

	for( $j = 0, $count = sizeof( $positions ); $j < $count; ++$j )
	{
		$pos[] = $positions[$j]->name;
	}

	$xtpl->assign( 'POSITION', implode( ' | ', $pos ) );
	if( in_array( $value, $theme_mobile_list ) )
	{
		$xtpl->parse( 'main.loop.link_delete' );
	}
	else
	{
		if( $global_config['site_theme'] != $value )
		{
			$xtpl->parse( 'main.loop.link_active' );
			$xtpl->parse( 'main.loop.dash' );
		}
		$xtpl->parse( 'main.loop.link_delete' );
	}
	if( $i % 2 == 0 and $i < $number_theme )
	{
		$xtpl->parse( 'main.loop.endtr' );
	}
	else
	{
		$xtpl->parse( 'main.loop.endtd' );
	}

	++$i;

	$xtpl->parse( 'main.loop' );
}

if( ! empty( $errorconfig ) )
{
	$xtpl->assign( 'ERROR', implode( "<br />", $errorconfig ) );
	$xtpl->parse( 'main.error' );
}

$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );

$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>