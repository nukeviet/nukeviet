function nv_del_row( hr, fid )
{
   if ( confirm( nv_is_del_confirm[0] ) )
   {
      nv_ajax( 'post', hr.href, 'del=1&id=' + fid, '', 'nv_del_row_result' );
   }
   return false;
}

//  ---------------------------------------

function nv_del_row_result( res )
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

function nv_download_file( fr, flnm )
{
   var download_hits = document.getElementById( 'download_hits' ).innerHTML;
   download_hits = intval( download_hits );
   download_hits = download_hits + 1;
   document.getElementById( 'download_hits' ).innerHTML = download_hits;
   
   //window.open( nv_siteroot + "index.php?" + nv_lang_variable + "=" + nv_sitelang + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=down&file=" + flnm, fr);
   window.location.href = nv_siteroot + "index.php?" + nv_lang_variable + "=" + nv_sitelang + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=down&file=" + flnm;
   return false;
}

//  ---------------------------------------

function nv_linkdirect( code )
{
   var download_hits = document.getElementById( 'download_hits' ).innerHTML;
   download_hits = intval( download_hits );
   download_hits = download_hits + 1;
   document.getElementById( 'download_hits' ).innerHTML = download_hits;

   win = window.open( nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=down&code=' + code, 'mydownload' );
   win.focus();
   return false;
}

//  ---------------------------------------

function nv_link_report( fid )
{
   nv_ajax( "post", nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name, nv_fc_variable + '=report&id=' + fid + '&num=' + nv_randomPassword( 8 ), '', 'nv_link_report_result' );
   return false;
}

//  ---------------------------------------

function nv_link_report_result( res )
{
   alert( report_thanks_mess );
   return false;
}

//  ---------------------------------------

function nv_sendrating ( fid, point )
{
   if( fid > 0 && point > 0 && point < 6 )
   {
      nv_ajax( 'post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&rating=' + fid + '_' + point + '&num=' + nv_randomPassword( 8 ), 'stringrating' );
   }
   return false;
}

//  ---------------------------------------

function nv_send_comment()
{
   var nv_timer = nv_settimeout_disable( 'comment_submit', 10000 );
   var query = 'uname=' + document.getElementById( 'comment_uname' ).value;
   query += '&uemail=' + document.getElementById( 'comment_uemail_iavim' ).value;
   query += '&subject=' + document.getElementById( 'comment_subject' ).value;
   query += '&content=' + document.getElementById( 'comment_content' ).value;
   query += '&seccode=' + document.getElementById( 'comment_seccode_iavim' ).value;
   query += '&id=' + document.getElementById( 'comment_fid' ).value;
   query += '&ajax=1';

   nv_ajax( 'post', nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=getcomment', query + '&num=' + nv_randomPassword( 8 ), '', 'nv_send_comment_res' );
}

//  ---------------------------------------

function nv_send_comment_res( res )
{

   if( res == 'OK' )
   {
      alert( comment_thanks_mess );
      nv_list_comments();
      document.getElementById( 'comment_subject' ).value = comment_subject_defaul;
      document.getElementById( 'comment_content' ).value = '';
      hidden_form();
   }
   else if( res == 'WAIT' )
   {
      alert( comment_please_wait );
      document.getElementById( 'comment_subject' ).value = comment_subject_defaul;
      document.getElementById( 'comment_content' ).value = '';
   }
   else
   {
      alert( res );
   }
   nv_change_captcha( 'vimg', 'comment_seccode_iavim' );
   return false;
}

//  ---------------------------------------

function nv_list_comments()
{
   fid = document.getElementById( 'comment_fid' ).value;
   nv_ajax( 'get', nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=getcomment', '&list_comment=' + fid + '&num=' + nv_randomPassword( 8 ), 'list_comments' );
   return false;
}

//  ---------------------------------------

function show_form()
{
   var hidden_form_comment = document.getElementById( 'hidden_form_comment' );
   var form_comment = document.getElementById( 'form_comment' );

   hidden_form_comment.style.visibility = 'hidden';
   hidden_form_comment.style.display = 'none';
   form_comment.style.visibility = 'visible';
   form_comment.style.display = 'block';
   window.location.href = "#cform";
   return;
}

//  ---------------------------------------

function hidden_form()
{
   var hidden_form_comment = document.getElementById( 'hidden_form_comment' );
   var form_comment = document.getElementById( 'form_comment' );

   form_comment.style.visibility = 'hidden';
   form_comment.style.display = 'none';
   hidden_form_comment.style.visibility = 'visible';
   hidden_form_comment.style.display = 'block';
   window.location.href = "#lcm";
   return;
}

//  ---------------------------------------

function nv_comment_del( hr, cid )
{
   if ( confirm( nv_is_del_confirm[0] ) )
   {
      nv_ajax( 'post', hr.href, 'del=1&id=' + cid, '', 'nv_comment_del_result' );
   }
   return false;
}

//  ---------------------------------------

function nv_comment_del_result( res )
{
   if( res == 'OK' )
   {
      nv_list_comments();
   }
   else
   {
      alert( nv_is_del_confirm[2] );
   }
   return false;
}
