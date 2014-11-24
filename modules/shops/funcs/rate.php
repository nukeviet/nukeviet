<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

$contents = "";
$difftimeout = 360;
$id = $nv_Request->get_int( 'id', 'get,post', 0 );
$showdata = $nv_Request->get_int( 'showdata', 'get,post', 0 );
if( $showdata == 1 )
{
	$sql = "SELECT ratingdetail FROM " . $db_config['prefix'] . "_" . $module_data . "_rows WHERE id=" . $id;
    $ratingdetail = $db->query( $sql )->fetchColumn();
    
	if( ! empty( $ratingdetail ) || $ratingdetail != 0 )
	{
		$ratingdetail = unserialize( $ratingdetail );
		$total_value = array_sum( $ratingdetail );
		$percent_rate = array();
		$percent_rate[1] = round( $ratingdetail[1] * 100 / $total_value );
		$percent_rate[2] = round( $ratingdetail[2] * 100 / $total_value );
		$percent_rate[3] = round( $ratingdetail[3] * 100 / $total_value );
		$percent_rate[4] = round( $ratingdetail[4] * 100 / $total_value );
		$percent_rate[5] = round( $ratingdetail[5] * 100 / $total_value );
        
        $total_rate = $ratingdetail[1] + ( $ratingdetail[2] * 2 ) + ( $ratingdetail[3] * 3 ) + ( $ratingdetail[4] * 4 ) + ( $ratingdetail[5] * 5 ); 
        $ratefercent_avg = round($total_rate / $total_value,1);
    
		$xtpl = new XTemplate( "detail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'RATINGDETAIL', $ratingdetail );
		$xtpl->assign( 'PERCENT_RATE', $percent_rate );
        $xtpl->assign( 'RATE_AVG_PERCENT', $ratefercent_avg );
		$xtpl->parse( 'main.allowed_rating' );
		$contents = $xtpl->text( 'main.allowed_rating' );
		include NV_ROOTDIR . '/includes/header.php';
		echo $contents;
		include NV_ROOTDIR . '/includes/footer.php';
        exit();
	}
}
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
$val = $nv_Request->get_int( 'val', 'get,post', 0 );
$timeout = $nv_Request->get_int( $module_data . '_' . $op . '_' . $id, 'cookie', 0 );

if( $timeout == 0 or NV_CURRENTTIME - $timeout > $difftimeout )
{
	$sql = "SELECT ratingdetail FROM " . $db_config['prefix'] . "_" . $module_data . "_rows WHERE id=" . $id;
	$ratingdetail = $db->query( $sql )->fetchColumn();
	if( ! empty( $ratingdetail ) )
	{
		$ratingdetail = unserialize( $ratingdetail );
	}
	else
	{
		$ratingdetail = array(
			1 => 0,
			2 => 0,
			3 => 0,
			4 => 0,
			5 => 0 );
	}
    
	$ratingdetail[$val] = $ratingdetail[$val] + 1;

	$ratingdetail = serialize( $ratingdetail );
	$sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET ratingdetail='" . $ratingdetail . "' WHERE id=" . $id;
	$db->query( $sql );

	$nv_Request->set_Cookie( $module_data . '_' . $op . '_' . $id, NV_CURRENTTIME );
	$msg = sprintf( $lang_module['detail_rate_ok'], $val );
	$contents = "OK_" . $msg;
}
else
{
	$timeout = ceil( ( $difftimeout - NV_CURRENTTIME + $timeout ) / 60 );
	$timeoutmsg = sprintf( $lang_module['detail_rate_timeout'], $timeout );
	$contents = "ERR_" . $timeoutmsg;
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';