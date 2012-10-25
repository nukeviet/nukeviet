/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 1 - 31 - 2010 5 : 12
 */

// Xu ly cat ---------------------------------------

function nv_chang_cat(object, catid, mod )
{
	var new_vid = $(object).val();
	nv_ajax( "post", script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=change_cat&catid=' + catid + '&mod='+mod+'&new_vid=' + new_vid + '&num=' + nv_randomPassword( 8 ), '', 'nv_chang_cat_result' );
	return;
}

//  ---------------------------------------

function nv_chang_cat_result( res )
{
	window.location = url_back;
	return false;
}

function nv_del_cat(catid)
{
   if (confirm(nv_is_del_confirm[0]))
   {
      nv_ajax( 'post', script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=del_cat&catid=' + catid, '', 'nv_del_cat_result' );
   }
   return false;
}

function nv_del_cat_result(res)
{
	var r_split = res.split( "_" );
	if (r_split[0] == 'OK') {
		window.location = url_back;
	} else if (r_split[0] == 'ERR') {
		alert(r_split[1]);
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}