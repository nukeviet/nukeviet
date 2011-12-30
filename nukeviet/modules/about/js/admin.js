/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1 - 31 - 2010 5 : 12
 */

//  ---------------------------------------

function nv_chang_weight( vid )
{
   var nv_timer = nv_settimeout_disable( 'change_weight_' + vid, 5000 );
   var new_weight = document.getElementById( 'change_weight_' + vid ).options[document.getElementById( 'change_weight_' + vid ).selectedIndex].value;
   nv_ajax( "post", script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=change_weight&id=' + vid + '&new_weight=' + new_weight + '&num=' + nv_randomPassword( 8 ), '', 'nv_chang_weight_res' );
   return;
}

//  ---------------------------------------

function nv_chang_status( vid )
{
   var nv_timer = nv_settimeout_disable( 'change_status_' + vid, 5000 );
   var new_status = document.getElementById( 'change_status_' + vid ).options[document.getElementById( 'change_status_' + vid ).selectedIndex].value;
   nv_ajax( "post", script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=change_status&id=' + vid + '&new_status=' + new_status + '&num=' + nv_randomPassword( 8 ), '', 'nv_chang_weight_res' );
   return;
}

//  ---------------------------------------

function nv_chang_weight_res( res )
{
   var r_split = res.split( "_" );
   if( r_split[0] != 'OK' )
   {
      alert( nv_is_change_act_confirm[2] );
   }
   clearTimeout( nv_timer );
   nv_show_list_mods();
   return;
}

function nv_show_list_mods()
{
   if( document.getElementById( 'module_show_list' ) )
   {
      nv_ajax( "get", script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=list&num=' + nv_randomPassword( 8 ), 'module_show_list' );
   }
   return;
}

function nv_module_del(did)
{
   if (confirm(nv_is_del_confirm[0]))
   {
      nv_ajax( 'post', script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=del&id=' + did, '', 'nv_module_del_result' );
   }
   return false;
}

function nv_module_del_result(res)
{
   var r_split = res.split( "_" );
   if(r_split[0] == 'OK')
   {
      window.location.href = strHref;
   }
   else
   {
      alert(nv_is_del_confirm[2]);
   }
   return false;
}

//---------------------------------------
function get_alias(id) {
	var title = strip_tags(document.getElementById('idtitle').value);
	if (title != '') {
		nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=alias&title=' + encodeURIComponent(title)+'&id=' + id, '', 'res_get_alias');
	}
	return false;
}

function res_get_alias(res) {
	if (res != "") {
		document.getElementById('idalias').value = res;
	} else {
		document.getElementById('idalias').value = '';
	}
	return false;
}