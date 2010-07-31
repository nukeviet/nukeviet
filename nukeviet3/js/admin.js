/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 9:36
 */

function nv_admin_logout()
{
   if (confirm(nv_admlogout_confirm[0]))
   {
      nv_ajax( 'get', nv_siteroot + 'index.php?second=admin_logout', 'js=1', '', 'nv_adminlogout_check' );
   }
   return false;
}

//  ---------------------------------------

function nv_adminlogout_check(res)
{
   if(res == 1)
   {
      alert(nv_admlogout_confirm[1]);
      if(nv_area_admin == 1)
      {
         window.location.href = nv_siteroot + 'index.php';
      }
      else
      {
         window.location.href = strHref;
      }
   }
   return false;
}