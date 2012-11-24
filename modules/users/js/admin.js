/* *
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function nv_chang_question( qid )
{
   var nv_timer = nv_settimeout_disable( 'id_weight_' + qid, 5000 );
   var new_vid = document.getElementById( 'id_weight_' + qid ).options[document.getElementById( 'id_weight_' + qid ).selectedIndex].value;
   nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=question&changeweight=1&qid=' + qid + '&new_vid=' + new_vid + '&num=' + nv_randomPassword( 8 ), '', 'nv_chang_question_result' );
   return;
}

//  ---------------------------------------

function nv_chang_question_result( res )
{
   if ( res != 'OK' )
   {
      alert( nv_is_change_act_confirm[2] );
   }
   clearTimeout( nv_timer );
   nv_show_list_question();
   return;
}

//  ---------------------------------------

function nv_save_title( qid )
{
    var new_title = document.getElementById( 'title_' + qid );
    var hidden_title = document.getElementById( 'hidden_' + qid );
    
    if(new_title.value == hidden_title.value)
    {
        return;
    }
    
    if(new_title.value == '')
    {
        alert( nv_content );
        new_title.focus();
        return false;
    }
    
    var nv_timer = nv_settimeout_disable( 'title_' + qid, 5000 );
    nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=question&edit=1&qid=' + qid + '&title=' + new_title.value + '&num=' + nv_randomPassword( 8 ), '', 'nv_save_title_result' );
   return;
}

//  ---------------------------------------

function nv_save_title_result(res)
{
    if ( res != 'OK' )
    {
        alert( nv_is_change_act_confirm[2] );
    }
    clearTimeout( nv_timer );
    nv_show_list_question();
   return;
}

//  ---------------------------------------

function nv_show_list_question()
{
   if ( document.getElementById( 'module_show_list' ) )
   {
      nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=question&qlist=1&num=' + nv_randomPassword( 8 ), 'module_show_list' );
   }
   return;
}

//  ---------------------------------------

function nv_del_question( qid )
{
   if ( confirm( nv_is_del_confirm[0] ) )
   {
      nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=question&del=1&qid=' + qid, '', 'nv_del_question_result' );
   }
   return false;
}

//  ---------------------------------------

function nv_del_question_result( res )
{
   if ( res == 'OK' )
   {
      nv_show_list_question();
   }
   else
   {
      alert( nv_is_del_confirm[2] );
   }
   return false;
}

//  ---------------------------------------

function nv_add_question()
{
    var new_title = document.getElementById( 'new_title' );
 
    if(new_title.value == '')
    {
        alert( nv_content );
        new_title.focus();
        return false;
    }
    
    var nv_timer = nv_settimeout_disable( 'new_title', 5000 );
    
    nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=question&add=1&title=' + new_title.value + '&num=' + nv_randomPassword( 8 ), '', 'nv_add_question_result' );
   return;
}

//  ---------------------------------------

function nv_add_question_result( res )
{
   if ( res == 'OK' )
   {
      nv_show_list_question();
   }
   else
   {
      alert( nv_content );
   }
   return false;
}

//  ---------------------------------------

function nv_row_del( vid )
{
   if ( confirm( nv_is_del_confirm[0] ) )
   {
      nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del&userid=' + vid, '', 'nv_row_del_result' );
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

function nv_waiting_row_del( uid )
{
    if ( confirm( nv_is_del_confirm[0] ) )
    {
        nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=user_waiting&del=1&userid=' + uid, '', 'nv_waiting_row_del_result' );
    }
   return false;
}

//  ---------------------------------------

function nv_waiting_row_del_result( res )
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

function nv_chang_status( vid )
{
   var nv_timer = nv_settimeout_disable( 'change_status_' + vid, 5000 );
   nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=setactive&userid=' + vid + '&num=' + nv_randomPassword( 8 ), '', 'nv_chang_status_res' );
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

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function nv_group_change_status(group_id)
{
   var nv_timer = nv_settimeout_disable('select_' + group_id, 5000);
   nv_ajax( "post", script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=groups_act&group_id=' + group_id + '&num=' + nv_randomPassword(8), '', 'nv_group_change_res' );
   return;
}

//  ---------------------------------------

function nv_group_change_res(res)
{
   var r_split = res.split( "_" );
   var sl = document.getElementById( 'select_' + r_split[1] );
   if(r_split[0] != 'OK')
   {
      alert(nv_is_change_act_confirm[2]);
      if(sl.checked == true)
      sl.checked = false;
      else
      sl.checked = true;
      clearTimeout(nv_timer);
      sl.disabled = true;
      return;
   }
   return;
}

//  ---------------------------------------

function nv_group_del(group_id)
{
   if (confirm(nv_is_del_confirm[0]))
   {
      nv_ajax( 'post', script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=groups_del&group_id=' + group_id, '', 'nv_group_del_result' );
   }
   return false;
}

//  ---------------------------------------

function nv_group_del_result(res)
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

//  ---------------------------------------

function nv_group_search_users(my_url)
{
   var search_query = document.getElementById( 'search_query' );
   var search_option = document.getElementById( 'search_option' ).options[document.getElementById( 'search_option' ).selectedIndex].value;
   var is_search = document.getElementById( 'is_search' );
   is_search.value = 1;
   nv_settimeout_disable('search_click', 5000);
   search_query = rawurlencode(search_query.value);
   my_url = rawurldecode(my_url);
   nv_ajax( 'get', my_url, 'search_query=' + search_query + '&search_option=' + search_option, 'search_users_result' );
   return;
}

//  ---------------------------------------

function nv_group_add_user(group_id, userid)
{
   var user_checkbox = document.getElementById( 'user_' + userid );
   if (confirm(nv_is_add_user_confirm[0]))
   {
      user_checkbox.disabled = true;
      nv_ajax( "post", script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=groups_add_user&group_id=' + group_id + '&userid=' + userid, '', 'nv_group_add_user_res' );
   }
   else
   {
      user_checkbox.checked = false;
   }

   return;
}

//  ---------------------------------------

function nv_group_add_user_res(res)
{
   var res2 = res.split( "_" );
   if(res2[0] != 'OK')
   {
      var user_checkbox = document.getElementById( 'user_' + userid );
      user_checkbox.disabled = false;
      user_checkbox.checked = false;
      alert(nv_is_add_user_confirm[2]);
      return false;
   }
   else
   {
      var count_user = document.getElementById( 'count_users_' + res2[1] ).innerHTML;
      count_user = intval(count_user) + 1;
      document.getElementById( 'count_users_' + res2[1] ).innerHTML = count_user;

      var is_search = document.getElementById( 'is_search' ).value;
      if(is_search != 0)
      {
         var url2 = script_name + '?' + nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=groups_search_users&group_id=' + res2[1];
         url2 = rawurlencode(url2);
         nv_group_search_users(url2, 'search_users_result');
      }

      var url3 = script_name + '?' + nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=groups_users&group_id=' + res2[1];
      url3 = rawurlencode(url3);
      nv_urldecode_ajax(url3, 'list_users');
   }
}

//  ---------------------------------------

function nv_group_exclude_user(group_id, userid)
{
   var user_checkbox2 = document.getElementById( 'exclude_user_' + userid );
   if (confirm(nv_is_exclude_user_confirm[0]))
   {
      user_checkbox2.disabled = true;
      nv_ajax( "post", script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=groups_exclude_user&group_id=' + group_id + '&userid=' + userid, '', 'nv_group_exclude_user_res' );
   }
   else
   {
      user_checkbox2.checked = false;
   }

   return;
}

//  ---------------------------------------

function nv_group_exclude_user_res(res)
{
   var res3 = res.split( "_" );
   if(res3[0] != 'OK')
   {
      var user_checkbox2 = document.getElementById( 'exclude_user_' + userid );
      user_checkbox2.disabled = false;
      user_checkbox2.checked = false;
      alert(nv_is_exclude_user_confirm[2]);
      return false;
   }
   else
   {
      var count_user = document.getElementById( 'count_users_' + res3[1] ).innerHTML;
      count_user = intval(count_user) - 1;
      document.getElementById( 'count_users_' + res3[1] ).innerHTML = count_user;

      var is_search = document.getElementById( 'is_search' ).value;
      if(is_search != 0)
      {
         var url2 = script_name + '?' + nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=groups_search_users&id=' + res3[1];
         url2 = rawurlencode(url2);
         nv_group_search_users(url2, 'search_users_result');
      }

      var url3 = script_name+'?'+ nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=groups_users&group_id=' + res3[1];
      url3 = rawurlencode(url3);
      nv_urldecode_ajax(url3, 'list_users');
   }
}
