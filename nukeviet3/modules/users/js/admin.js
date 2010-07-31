/* *
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2010 VINADES., JSC. All rights reserved
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
