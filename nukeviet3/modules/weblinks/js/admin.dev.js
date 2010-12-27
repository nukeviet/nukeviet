/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1 - 31 - 2010 5 : 12
 */

// Xu ly cat ---------------------------------------

function nv_chang_cat( catid, mod )
{
   var nv_timer = nv_settimeout_disable('id_' + mod +'_' + catid, 5000 );
   var new_vid = document.getElementById( 'id_' + mod +'_' + catid).options[document.getElementById('id_' + mod +'_' + catid).selectedIndex].value;
   nv_ajax( "post", script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=change_cat&catid=' + catid + '&mod='+mod+'&new_vid=' + new_vid + '&num=' + nv_randomPassword( 8 ), '', 'nv_chang_cat_result' );
   return;
}

//  ---------------------------------------

function nv_chang_cat_result( res )
{
   var r_split = res.split( "_" );
   if( r_split[0] != 'OK' )
   {
      alert( nv_is_change_act_confirm[2] );
   }
   clearTimeout( nv_timer );
   nv_show_list_cat();
   return;
}

function nv_show_list_cat()
{
   if( document.getElementById( 'module_show_list' ) )
   {
      nv_ajax( "get", script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=list_cat&num=' + nv_randomPassword( 8 ), 'module_show_list' );
   }
   return;
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
   if(r_split[0] == 'OK')
   {
      nv_show_list_cat();
   }
   else if(r_split[0] == 'ERR'){
   		alert(r_split[1]);
   }
   else
   {
      	alert(nv_is_del_confirm[2]);
   }
   return false;
}