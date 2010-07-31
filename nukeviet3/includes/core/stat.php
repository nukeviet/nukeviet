<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/29/2009 20:7
 */

if (! defined('NV_MAINFILE'))
	die('Stop!!!');

function nv_stat_update()
{
	global $db, $client_info;

	list($last_update) = $db->sql_fetchrow($db->sql_query("SELECT `c_count` FROM `" . NV_COUNTER_TABLE . "` WHERE `c_type` = 'c_time' AND `c_val`= 'last'"));
	$last_year = date("Y", $last_update);
	$last_month = date("M", $last_update);
	$last_day = date("d", $last_update);
	if ($last_year != NV_CURRENTYEAR_FNUM)
	{
		$query = "UPDATE `" . NV_COUNTER_TABLE . "` SET `c_count`= 0 WHERE (`c_type`='month' OR `c_type`='day' OR `c_type`='hour')";
		$db->sql_query($query);
	} elseif ($last_month != NV_CURRENTMONTH_STXT)
	{
		$query = "UPDATE `" . NV_COUNTER_TABLE . "` SET `c_count`= 0 WHERE (`c_type`='day' OR `c_type`='hour')";
		$db->sql_query($query);
	} elseif ($last_day != NV_CURRENTDAY_2NUM)
	{
		$query = "UPDATE `" . NV_COUNTER_TABLE . "` SET `c_count`= 0 WHERE `c_type`='hour'";
		$db->sql_query($query);
	}

	$bot_name = ($client_info['is_bot'] and ! empty($client_info['bot_info']['name'])) ? $client_info['bot_info']['name'] : "Not_bot";
	$browser = ($client_info['is_mobile']) ? "Mobile" : $client_info['browser']['key'];
    $country = nv_getCountry_from_file($client_info['ip']);

	$query = "UPDATE `" . NV_COUNTER_TABLE . "` SET `c_count`= c_count + 1, `last_update`=" . NV_CURRENTTIME . " WHERE 
	(`c_type`='total' AND `c_val`='hits') OR 
	(`c_type`='year' AND `c_val`='" . NV_CURRENTYEAR_FNUM . "') OR 
	(`c_type`='month' AND `c_val`='" . NV_CURRENTMONTH_STXT . "') OR 
	(`c_type`='day' AND `c_val`='" . NV_CURRENTDAY_2NUM . "') OR 
    (`c_type`='dayofweek' AND `c_val`='" . date( 'l', NV_CURRENTTIME ) . "') OR 
	(`c_type`='hour' AND `c_val`='" . NV_CURRENT24HOUR_2NUM . "') OR 
	(`c_type`='bot' AND `c_val`=" . $db->dbescape($bot_name) . ") OR 
	(`c_type`='browser' AND `c_val`=" . $db->dbescape($browser) . ") OR 
	(`c_type`='os' AND `c_val`=" . $db->dbescape($client_info['client_os']['key']) . ") OR 
    (`c_type`='country' AND `c_val`=" . $db->dbescape($country[0]) . ")";
	$db->sql_query($query);

	$query = "UPDATE `" . NV_COUNTER_TABLE . "` SET `c_count`= " . NV_CURRENTTIME . " WHERE `c_type`='c_time' AND `c_val`= 'last'";
	$db->sql_query($query);
}

nv_stat_update();
$nv_Request->set_Session('stat', NV_CURRENTTIME);
?>