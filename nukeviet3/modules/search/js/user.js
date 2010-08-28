function nv_send_search( qmin, qmax )
{
   var nv_timer = nv_settimeout_disable( 'search_submit', 1000 );
   var search_query = document.getElementById( 'search_query' );
   if( search_query.value.length >= qmin && search_query.value.length <= qmax )
   {
      var q = formatStringAsUriComponent( search_query.value );
      search_query.value = q;
      if( q.length >= qmin && q.length <= qmax )
      {
         q = rawurlencode( q );
         var mod = document.getElementById( 'search_query_mod' ).options[document.getElementById( 'search_query_mod' ).selectedIndex].value;
         var checkss = document.getElementById( 'search_checkss' ).value;
         var search_logic_and = document.getElementById( 'search_logic_and' );
         var search_url = nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=adv&search_query=' + q + '&search_mod=' + mod + '&search_ss=' + checkss;
         if( search_logic_and.checked == true )
         {
            search_url += '&logic=AND';
         }
         nv_ajax( 'get', search_url, '', 'search_result' );
         return;
      }
   }
   search_query.focus();
   return false;
}

//  ---------------------------------------

function nv_search_viewall( mod, qmin, qmax )
{
   document.getElementById( 'search_query' ).value = document.getElementById( 'hidden_key' ).value;
   document.getElementById( 'search_query_mod' ).value = mod;
   nv_send_search( qmin, qmax );
   return;
}

//  ---------------------------------------

function GoUrl ( qmin, qmax )
{
   var nv_timer = nv_settimeout_disable( 'search_submit', 1000 );
   var mod = document.getElementById( 'search_query_mod' ).options[document.getElementById( 'search_query_mod' ).selectedIndex].value;

   if( mod == 'all' || mod == '' )
   {
      alert( "please chose module name!" );
      document.getElementById( 'search_query_mod' ).focus();
      return false;
   }

   var link = nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + mod + '&' + nv_fc_variable + '=search';
   var search_query = document.getElementById( 'search_query' );
   if( search_query.value.length >= qmin && search_query.value.length <= qmax )
   {
      var q = formatStringAsUriComponent( search_query.value );
      if( q.length >= qmin && q.length <= qmax )
      {
         q = rawurlencode( q );
         link = link + '&q=' + q;
      }
   }
   window.location.href = link;
   return false;
}

//  ---------------------------------------

function GoGoogle( qmin, qmax )
{
   var nv_timer = nv_settimeout_disable( 'search_submit', 1000 );
   var search_query = document.getElementById( 'search_query' );
   if( search_query.value.length >= qmin && search_query.value.length <= qmax )
   {
      var q = rawurlencode( search_query.value );
      var mydomain = rawurlencode( document.getElementById( 'mydomain' ).value );
      var confirm_search_on_internet = document.getElementById( 'confirm_search_on_internet' ).value;
      var link = 'http://www.google.com/custom?hl=' + nv_sitelang + '&domains=' + mydomain + '&q=' + q;
      if ( ! confirm( confirm_search_on_internet + ' ?' ) )
      {
         link += '&sitesearch=' + mydomain;
      }
      window.open( link , '_blank' );
      // window.location.href = link;
      return;
   }

   search_query.focus();
   return false;
}
