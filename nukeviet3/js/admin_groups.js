/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function nv_group_change_status(group_id)
{
   var nv_timer = nv_settimeout_disable('select_' + group_id, 5000);
   nv_ajax( "post", script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=act&group_id=' + group_id + '&num=' + nv_randomPassword(8), '', 'nv_group_change_res' );
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
      nv_ajax( 'post', script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=del&group_id=' + group_id, '', 'nv_group_del_result' );
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
      nv_ajax( "post", script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=add_user&group_id=' + group_id + '&userid=' + userid, '', 'nv_group_add_user_res' );
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
         var url2 = script_name + '?' + nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=search_users&group_id=' + res2[1];
         url2 = rawurlencode(url2);
         nv_group_search_users(url2, 'search_users_result');
      }

      var url3 = script_name + '?' + nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=users&group_id=' + res2[1];
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
      nv_ajax( "post", script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=exclude_user&group_id=' + group_id + '&userid=' + userid, '', 'nv_group_exclude_user_res' );
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
         var url2 = script_name + '?' + nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=search_users&id=' + res3[1];
         url2 = rawurlencode(url2);
         nv_group_search_users(url2, 'search_users_result');
      }

      var url3 = script_name+'?'+ nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=users&group_id=' + res3[1];
      url3 = rawurlencode(url3);
      nv_urldecode_ajax(url3, 'list_users');
   }
}