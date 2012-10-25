/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 9:36
 */

function nv_is_del_cron(cronid)
{
	if (confirm(nv_is_del_confirm[0]))
	{
		nv_ajax( 'get', window.location.href, nv_fc_variable + '=cronjobs_del&id=' + cronid, '', 'nv_is_del_cron_result' );
	}
	return false;
}

function nv_is_del_cron_result(res)
{
	if(res == 1)
	{
		alert(nv_is_del_confirm[1]);
		window.location.href = window.location.href;
	}
	else
	{
		alert(nv_is_del_confirm[2]);
	}
	return false;
}