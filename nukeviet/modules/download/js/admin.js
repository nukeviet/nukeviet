/* *
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1 - 31 - 2010 5 : 12
 */

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

function nv_row_del( catid )
{
   if ( confirm( nv_is_del_confirm[0] ) )
   {
      nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&del=1&catid=' + catid, '', 'nv_row_del_result' );
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

//  ---------------------------------------

function nv_file_del( fid )
{
   if ( confirm( nv_is_del_confirm[0] ) )
   {
      nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&del=1&id=' + fid, '', 'nv_file_del_result' );
   }
   return false;
}

//  ---------------------------------------

function nv_file_del_result( res )
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

function nv_chang_file_status( fid )
{
   var nv_timer = nv_settimeout_disable( 'change_status' + fid, 5000 );
   nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&changestatus=1&id=' + fid + '&num=' + nv_randomPassword( 8 ), '', 'nv_chang_file_status_res' );
   return;
}

//  ---------------------------------------

function nv_chang_file_status_res( res )
{
   if( res != 'OK' )
   {
      alert( nv_is_change_act_confirm[2] );
      window.location.href = window.location.href;
   }
   return;
}

//  ---------------------------------------

function nv_filequeue_del( fid )
{
   if ( confirm( nv_is_del_confirm[0] ) )
   {
      nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=filequeue&del=1&id=' + fid, '', 'nv_filequeue_del_result' );
   }
   return false;
}

//  ---------------------------------------

function nv_filequeue_del_result( res )
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

function nv_filequeue_alldel()
{
   if ( confirm( nv_is_del_confirm[0] ) )
   {
      nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=filequeue&alldel=1', '', 'nv_filequeue_alldel_result' );
   }
   return false;
}

//  ---------------------------------------

function nv_filequeue_alldel_result( res )
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

function nv_checkfile( mylink, is_myUrl, butt )
{
   var nv_timer = nv_settimeout_disable( butt, 5000 );
   var link = document.getElementById( mylink ).value;
   if( trim( link ) == '' )
   {
      document.getElementById( mylink ).value = '';
      return false;
   }

   link = rawurlencode( link );

   nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&check=1&url=' + link + '&is_myurl=' + is_myUrl + '&num=' + nv_randomPassword( 8 ), '', 'nv_checkfile_result' );
   return false;
}

//  ---------------------------------------

function nv_gourl( mylink, is_myUrl, butt )
{
   var nv_timer = nv_settimeout_disable( butt, 5000 );
   var link = document.getElementById( mylink ).value;
   if( trim( link ) == '' )
   {
      document.getElementById( mylink ).value = '';
      return false;
   }

   if( is_myUrl )
   {
      link = rawurlencode( link );
      link = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&fdownload=' + link;
      window.location.href = link;

   }
   else
   {
      if( ! link.match( /^(http|ftp)\:\/\/\w+([\.\-]\w+)*\.\w{2,4}(\:\d+)*([\/\.\-\?\&\%\#]\w+)*\/?$/i ) )
      {
         alert( nv_url );
         document.getElementById( mylink ).focus();
      }
      else
      {
         var w = window.open( link );
         w.focus();
      }
   }
   return false;
}

//  ---------------------------------------

function nv_checkfile_result( res )
{
   alert( res );
   return false;
}

//  ---------------------------------------

function nv_report_del( rid )
{
   if ( confirm( nv_is_del_confirm[0] ) )
   {
      nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=report&del=1&id=' + rid, '', 'nv_report_del_result' );
   }
   return false;
}

//  ---------------------------------------

function nv_report_del_result( res )
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

function nv_report_check( fid )
{
   nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=report&linkcheck=1&id=' + fid + '&num=' + nv_randomPassword( 8 ), '', 'nv_report_check_result' );
   return false;
}

//  ---------------------------------------

function nv_report_check_result( res )
{
   var r_split = res.split( "_" );

   if( r_split[0] == "OK" )
   {
      var report_check_ok = document.getElementById( 'report_check_ok' ).value;
      if ( confirm( report_check_ok ) )
      {
         nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=report&del=1&id=' + r_split[1], '', 'nv_report_del_result' );
      }
   }
   else
   {
      if( r_split[0] == "NO" )
      {
         var report_check_error = document.getElementById( 'report_check_error' ).value;
         if ( confirm( report_check_error ) )
         {
            nv_report_edit( r_split[1] );
         }
      }
      else
      {
         var report_check_error2 = document.getElementById( 'report_check_error2' ).value;
         if ( confirm( report_check_error2 ) )
         {
            nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=report&del=1&id=' + r_split[1], '', 'nv_report_del_result' );
         }
      }
   }
   return false;
}

//  ---------------------------------------

function nv_report_edit( fid )
{
   window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&edit=1&id=' + fid + '&report=1';
   return false;
}

//  ---------------------------------------

function nv_report_alldel()
{
   if ( confirm( nv_is_del_confirm[0] ) )
   {
      nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=report&alldel=1', '', 'nv_report_alldel_result' );
   }
   return false;
}

//  ---------------------------------------

function nv_report_alldel_result( res )
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

function nv_chang_comment_status ( cid )
{
   var nv_timer = nv_settimeout_disable( 'status' + cid, 5000 );
   nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=comment&changestatus=1&id=' + cid + '&num=' + nv_randomPassword( 8 ), '', 'nv_chang_comment_status_res' );
   return;
}

//  ---------------------------------------

function nv_chang_comment_status_res( res )
{
   if( res != 'OK' )
   {
      alert( nv_is_change_act_confirm[2] );
   }
   window.location.href = window.location.href;
   return;
}

//  ---------------------------------------

function nv_comment_del( cid )
{
   if ( confirm( nv_is_del_confirm[0] ) )
   {
      nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=comment&del=1&id=' + cid, '', 'nv_comment_del_result' );
   }
   return false;
}

//  ---------------------------------------

function nv_comment_del_result( res )
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

function nv_file_additem()
{
   file_items ++ ;
   var newitem = "<input readonly=\"readonly\" class=\"txt\" value=\"\" name=\"fileupload[]\" id=\"fileupload" + file_items + "\" style=\"width : 300px\" maxlength=\"255\" />";
   newitem += "&nbsp;<input type=\"button\" value=\"" + file_selectfile + "\" name=\"selectfile\" onclick=\"nv_open_browse_file( '" + nv_base_adminurl + "index.php?" + nv_name_variable + "=upload&popup=1&area=fileupload" + file_items + "&path=" + file_dir + "&type=file', 'NVImg', 850, 500, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' ); return false; \" />";
   newitem += "&nbsp;<input type=\"button\" value=\"" + file_checkUrl + "\" id= \"check_fileupload" + file_items + "\" onclick=\"nv_checkfile( 'fileupload" + file_items + "', 1, 'check_fileupload" + file_items + "' ); \" />";
   newitem += "&nbsp;<input type=\"button\" value=\"" + file_gourl + "\" id= \"go_fileupload" + file_items + "\" onclick=\"nv_gourl( 'fileupload" + file_items + "', 1, 'go_fileupload" + file_items + "' ); \" /><br />";
   $( "#fileupload_items" ).append( newitem );
}

//  ---------------------------------------

function nv_file_additem2()
{
   var newitem = "<input readonly=\"readonly\" class=\"txt\" value=\"\" name=\"fileupload2[]\" id=\"fileupload2_" + file_items + "\" style=\"width : 300px\" maxlength=\"255\" />";
   newitem += "&nbsp;<input type=\"button\" value=\"" + file_selectfile + "\" name=\"selectfile\" onclick=\"nv_open_browse_file( '" + nv_base_adminurl + "index.php?" + nv_name_variable + "=upload&popup=1&area=fileupload2_" + file_items + "&path=" + file_dir + "&type=file', 'NVImg', 850, 500, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' ); return false; \" />";
   newitem += "&nbsp;<input type=\"button\" value=\"" + file_checkUrl + "\" id= \"check_fileupload2_" + file_items + "\" onclick=\"nv_checkfile( 'fileupload2_" + file_items + "', 1, 'check_fileupload2_" + file_items + "' ); \" />";
   newitem += "&nbsp;<input type=\"button\" value=\"" + file_gourl + "\" id= \"go_fileupload2_" + file_items + "\" onclick=\"nv_gourl( 'fileupload2_" + file_items + "', 1, 'go_fileupload2_" + file_items + "' ); \" /><br />";
   $( "#fileupload2_items" ).append( newitem );
   file_items ++ ;
}

//  ---------------------------------------

function nv_linkdirect_additem()
{
   var newitem = "<textarea name=\"linkdirect[]\" id=\"linkdirect" + linkdirect_items + "\" style=\"width : 300px; height : 150px\"></textarea>";
   newitem += "&nbsp;<input type=\"button\" value=\"" + file_checkUrl + "\" id=\"check_linkdirect" + linkdirect_items + "\" onclick=\"nv_checkfile( 'linkdirect" + linkdirect_items + "', 0, 'check_linkdirect" + linkdirect_items + "' ); \" /><br />";
   $( "#linkdirect_items" ).append( newitem );
   linkdirect_items ++ ;
}
