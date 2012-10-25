<?php

/**
 * @Project NUKEVIET 3.x
 * @author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. all rights reserved
 * @createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_WEBLINKS' ) ) die( 'Stop!!!' );

$submit = $nv_Request->get_string( 'submit', 'post' );
$report_id = $nv_Request->get_int( 'report_id', 'post' );
$id = ( $id == 0 ) ? $report_id : $id;

$sql = "SELECT `title`, `alias` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`='" . $id . "'";

$result = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $result );
unset( $sql, $result );

$row['error'] = "";
$row['action'] = nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=reportlink-" . $row['alias'] . "-" . $id, true );
$row['id'] = $id;

if( $id )
{
	$check = false;
	if( $submit and $report_id )
	{
		$sql = "SELECT `type` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_report` WHERE `id`='" . $report_id . "'";
		$result = $db->sql_query( $sql );
		$rows = $db->sql_fetchrow( $result );
		
		$report = $nv_Request->get_int( 'report', 'post' );
		$report_note = filter_text_input( 'report_note', 'post', '', 1, 255 );
		
		$row['report_note'] = $report_note;
		if( $report == 0 and empty( $report_note ) )
		{
			$row['error'] = $lang_module['error'];

		}
		elseif( ! empty( $report_note ) and ! isset( $report_note{9} ) )
		{
			$row['error'] = $lang_module['error_word_min'];
		}
		elseif( $rows['type'] == $report )
		{
			$check = true;
		}
		else
		{
			$report_note = nv_nl2br( $report_note );
			$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_report` (`id`, `type`, `report_time`, `report_userid`, `report_ip`, `report_browse_key`, `report_browse_name`, `report_os_key`, `report_os_name`, `report_note`) VALUE ('" . $report_id . "', '" . $report . "', UNIX_TIMESTAMP(), '0', " . $db->dbescape_string( $client_info['ip'] ) . ", " . $db->dbescape_string( $client_info['browser']['key'] ) . ", " . $db->dbescape_string( $client_info['browser']['name'] ) . ", " . $db->dbescape_string( $client_info['client_os']['key'] ) . ", " . $db->dbescape_string( $client_info['client_os']['name'] ) . ", " . $db->dbescape_string( $report_note ) . ")";
			$check = $db->sql_query( $sql );
		}
	}

	$contents = call_user_func( "report", $row, $check );
}
else
{
	die( "you don't permission to access!!!" );
	exit();
}

?>