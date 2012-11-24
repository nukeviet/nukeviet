/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
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

function nv_open_browse_file(theURL,winName,w,h,features) {
	LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
	settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition;
	if(features != '') {
		settings = settings + ','+features;
	}
	window.open(theURL,winName,settings);
	window.blur();
}

//---------------------------------------

function nv_sh(sl_id, div_id){
    var new_opt = document.getElementById(sl_id).options[document.getElementById(sl_id).selectedIndex].value;
    if (new_opt == 3) 
        nv_show_hidden(div_id, 1);
    else 
        nv_show_hidden(div_id, 0);
    return false;
}