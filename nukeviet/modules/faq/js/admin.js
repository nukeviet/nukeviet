/* *
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function nv_cat_del( catid )
{
   if ( confirm( nv_is_del_confirm[0] ) )
   {
      nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&del=1&catid=' + catid, '', 'nv_cat_del_result' );
   }
   return false;
}

//  ---------------------------------------

function nv_cat_del_result( res )
{
   if( res == 'OK' )
   {
      window.location.href = window.location.href;
   }
   else
   {
      alert( nv_is_del_confirm[2] );
   }
   return false;
}

//  ---------------------------------------

function nv_chang_weight( catid )
{
   var nv_timer = nv_settimeout_disable( 'weight' + catid, 5000 );
   var newpos = document.getElementById( 'weight' + catid ).options[document.getElementById( 'weight' + catid ).selectedIndex].value;
   nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&changeweight=1&catid=' + catid + '&new=' + newpos + '&num=' + nv_randomPassword( 8 ), '', 'nv_chang_weight_result' );
   return;
}

//  ---------------------------------------

function nv_chang_weight_result( res )
{
   if ( res != 'OK' )
   {
      alert( nv_is_change_act_confirm[2] );
   }
   clearTimeout( nv_timer );
   window.location.href = window.location.href;
   return;
}

//  ---------------------------------------

function nv_chang_status( catid )
{
   var nv_timer = nv_settimeout_disable( 'change_status' + catid, 5000 );
   nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&changestatus=1&catid=' + catid + '&num=' + nv_randomPassword( 8 ), '', 'nv_chang_status_res' );
   return;
}

//  ---------------------------------------

function nv_chang_status_res( res )
{
   if( res != 'OK' )
   {
      alert( nv_is_change_act_confirm[2] );
      window.location.href = window.location.href;
   }
   return;
}

//  ---------------------------------------

function nv_chang_row_weight( fid )
{
   var nv_timer = nv_settimeout_disable( 'weight' + fid, 5000 );
   var newpos = document.getElementById( 'weight' + fid ).options[document.getElementById( 'weight' + fid ).selectedIndex].value;
   nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&changeweight=1&id=' + fid + '&new=' + newpos + '&num=' + nv_randomPassword( 8 ), '', 'nv_chang_row_weight_res' );
   return;
}

//  ---------------------------------------

function nv_chang_row_weight_res( res )
{
   if ( res != 'OK' )
   {
      alert( nv_is_change_act_confirm[2] );
   }
   clearTimeout( nv_timer );
   window.location.href = window.location.href;
   return;
}

//  ---------------------------------------

function nv_chang_row_status( fid )
{
   var nv_timer = nv_settimeout_disable( 'change_status' + fid, 5000 );
   nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&changestatus=1&id=' + fid + '&num=' + nv_randomPassword( 8 ), '', 'nv_chang_row_status_res' );
   return;
}

//  ---------------------------------------

function nv_chang_row_status_res( res )
{
   if( res != 'OK' )
   {
      alert( nv_is_change_act_confirm[2] );
      window.location.href = window.location.href;
   }
   return;
}

//  ---------------------------------------

function nv_row_del( fid )
{
   if ( confirm( nv_is_del_confirm[0] ) )
   {
      nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&del=1&id=' + fid, '', 'nv_row_del_result' );
   }
   return false;
}

//  ---------------------------------------

function nv_row_del_result( res )
{
   if( res == 'OK' )
   {
      window.location.href = window.location.href;
   }
   else
   {
      alert( nv_is_del_confirm[2] );
   }
   return false;
}
