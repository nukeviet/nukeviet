<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_DATABASE' ) ) die( 'Stop!!!' );

$page_title = $lang_global['mod_settings'];
$array_sql_ext = array( "sql", "gz" );

$errormess = '';
$array_config_global = array();
$array_config_global['dump_backup_day'] = $global_config['dump_backup_day'];
$array_config_global['dump_backup_ext'] = $global_config['dump_backup_ext'];
$array_config_global['dump_interval'] = $global_config['dump_interval'];

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array_config_global = array();
	$array_config_global['dump_backup_ext'] = $nv_Request->get_title( 'dump_backup_ext', 'post', '', 1 );
	$array_config_global['dump_autobackup'] = $nv_Request->get_int( 'dump_autobackup', 'post' );
	$array_config_global['dump_backup_day'] = $nv_Request->get_int( 'dump_backup_day', 'post' );
	$dump_interval = $nv_Request->get_int( 'dump_interval', 'post', 1 );
	if( $dump_interval != $global_config['dump_interval'] )
	{
		$array_config_global['dump_interval'] = $dump_interval;
		$dump_interval = $dump_interval * 1440;
		$db->sql_query( "UPDATE `" . NV_CRONJOBS_GLOBALTABLE . "` SET `interval`=" . $dump_interval . " WHERE `run_file` = 'dump_autobackup.php' AND `run_func` = 'cron_dump_autobackup'" );
	}
	$array_config_global['dump_backup_ext'] = ( in_array( $array_config_global['dump_backup_ext'], $array_sql_ext ) ) ? $array_config_global['dump_backup_ext'] : $array_sql_ext[0];

	foreach( $array_config_global as $config_name => $config_value )
	{
		$db->sql_query( "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` SET
			`config_value`=" . $db->dbescape_string( $config_value ) . "
			WHERE `config_name` = " . $db->dbescape_string( $config_name ) . "
			AND `lang` = 'sys' AND `module`='global'
			LIMIT 1" );
	}

	nv_save_file_config_global();

	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
	exit();
}
$array_config_global['dump_autobackup'] = ( $global_config['dump_autobackup'] ) ? ' checked="checked"' : '';

$xtpl = new XTemplate( "setting.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'DATA', $array_config_global );

foreach( $array_sql_ext as $ext_i )
{
	$xtpl->assign( 'BACKUPEXTSELECTED', ( $ext_i == $array_config_global['dump_backup_ext'] ) ? "selected='selected'" : "" );
	$xtpl->assign( 'BACKUPEXTVALUE', $ext_i );
	$xtpl->parse( 'main.dump_backup_ext' );
}

for( $index = 1; $index < 11; ++$index )
{
	$xtpl->assign( 'BACKUPDAYSELECTED', ( $index == $array_config_global['dump_interval'] ) ? "selected='selected'" : "" );
	$xtpl->assign( 'BACKUPDAYVALUE', $index );
	$xtpl->parse( 'main.dump_interval' );
}

for( $index = 2; $index < 100; ++$index )
{
	$xtpl->assign( 'BACKUPDAYSELECTED', ( $index == $array_config_global['dump_backup_day'] ) ? "selected='selected'" : "" );
	$xtpl->assign( 'BACKUPDAYVALUE', $index );
	$xtpl->parse( 'main.dump_backup_day' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

include ( NV_ROOTDIR . '/includes/header.php' );
echo nv_admin_theme( $content );
include ( NV_ROOTDIR . '/includes/footer.php' );

?>